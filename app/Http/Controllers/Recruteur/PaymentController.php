<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeSession;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public function checkout(JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->company_id !== auth()->user()->company_id) abort(404);
        if ($jobOffer->status === 'published' && $jobOffer->isPaid()) {
            return redirect()->route('recruteur.offres.show', $jobOffer)->with('info', 'Cette offre est déjà publiée et payée.');
        }

        $pack = $jobOffer->recruitmentPack;
        $amountMad = (float) $pack->price_mad;
        $amountCents = (int) round($amountMad * 100);

        $key = config('services.stripe.secret');
        if (empty($key)) {
            return redirect()->route('recruteur.offres.show', $jobOffer)
                ->with('error', 'Stripe n\'est pas configuré. En développement, vous pouvez publier sans paiement.');
        }

        $stripe = new StripeClient($key);
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'mad',
                    'product_data' => [
                        'name' => 'Publication offre : ' . $jobOffer->title,
                        'description' => $pack->name . ' - ' . $pack->publication_days . ' jours',
                    ],
                    'unit_amount' => $amountCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('recruteur.payment.success', ['jobOffer' => $jobOffer->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('recruteur.offres.show', $jobOffer),
            'metadata' => ['job_offer_id' => (string) $jobOffer->id],
        ]);

        $jobOffer->update(['stripe_checkout_session_id' => $session->id]);

        return redirect($session->url);
    }

    public function success(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->company_id !== auth()->user()->company_id) abort(404);

        $sessionId = $request->query('session_id');
        if (!$sessionId || $jobOffer->stripe_checkout_session_id !== $sessionId) {
            return redirect()->route('recruteur.offres.show', $jobOffer)->with('error', 'Session de paiement invalide.');
        }

        $key = config('services.stripe.secret');
        if (empty($key)) {
            return redirect()->route('recruteur.offres.show', $jobOffer)->with('error', 'Stripe non configuré.');
        }

        $stripe = new StripeClient($key);
        try {
            $session = $stripe->checkout->sessions->retrieve($sessionId);
            if ($session->payment_status === 'paid') {
                $jobOffer->update([
                    'paid_at' => now(),
                    'status' => 'published',
                    'published_at' => now(),
                ]);
                return redirect()->route('recruteur.offres.show', $jobOffer)->with('success', 'Paiement reçu. Votre offre est publiée.');
            }
        } catch (\Throwable $e) {
            Log::warning('Stripe session retrieve failed: ' . $e->getMessage());
        }

        return redirect()->route('recruteur.offres.show', $jobOffer)->with('error', 'Impossible de confirmer le paiement.');
    }

    public function webhook(Request $request): \Illuminate\Http\Response
    {
        $secret = config('services.stripe.webhook_secret');
        if (empty($secret)) {
            return response('Webhook secret not set', 400);
        }

        $payload = $request->getContent();
        $sig = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig, $secret);
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature error: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $offerId = $session->metadata->job_offer_id ?? null;
            if ($offerId) {
                $offer = JobOffer::find($offerId);
                if ($offer && !$offer->isPaid()) {
                    $offer->update([
                        'paid_at' => now(),
                        'status' => 'published',
                        'published_at' => now(),
                    ]);
                }
            }
        }

        return response('', 200);
    }
}
