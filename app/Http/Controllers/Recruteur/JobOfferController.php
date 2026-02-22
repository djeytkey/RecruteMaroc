<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\JobOffer;
use App\Models\RecruitmentPack;
use App\Models\Sector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobOfferController extends Controller
{
    public function index(): View
    {
        $company = auth()->user()->company;
        $offers = $company ? $company->jobOffers()->with('recruitmentPack')->latest()->paginate(10) : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        return view('recruteur.offres.index', compact('offers'));
    }

    public function create(): View
    {
        $packs = RecruitmentPack::all();
        $sectors = Sector::orderBy('name')->get();
        return view('recruteur.offres.create', compact('packs', 'sectors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $company = auth()->user()->company;
        if (!$company) return redirect()->route('recruteur.dashboard')->with('error', 'Complétez le profil entreprise.');

        $data = $request->validate([
            'recruitment_pack_id' => 'required|exists:recruitment_packs,id',
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'sector_id' => 'nullable|exists:sectors,id',
            'contract_type' => 'required|string|in:CDI,CDD,Freelance,Stage,Alternance,Temps partiel',
            'description' => 'required|string',
            'main_criteria' => 'nullable|string',
        ]);

        $pack = RecruitmentPack::findOrFail($data['recruitment_pack_id']);
        $data['company_id'] = $company->id;
        $data['status'] = 'draft';
        $data['expires_at'] = now()->addDays($pack->publication_days);

        $offer = JobOffer::create($data);

        $criteria = $request->input('criteria', []);
        $order = 0;
        foreach ($criteria as $c) {
            if (empty($c['label'] ?? null)) continue;
            $offer->offerCriteria()->create([
                'type' => $c['type'] ?? 'quantitative',
                'label' => $c['label'],
                'weight_percentage' => (int) ($c['weight_percentage'] ?? 25),
                'expected_level' => isset($c['expected_level']) && $c['expected_level'] !== '' ? (int) $c['expected_level'] : null,
                'options' => $c['options'] ?? null,
                'is_blocking' => !empty($c['is_blocking']),
                'order' => $order++,
            ]);
        }

        return redirect()->route('recruteur.offres.show', $offer)->with('success', 'Offre créée. Choisissez « Publier » après paiement (simulation : le statut peut être mis à « publiée » pour les tests).');
    }

    public function show(JobOffer $jobOffer): View|RedirectResponse
    {
        if ($jobOffer->company_id !== auth()->user()->company_id) abort(404);
        $jobOffer->load(['offerCriteria', 'recruitmentPack', 'applications']);
        return view('recruteur.offres.show', compact('jobOffer'));
    }

    public function edit(JobOffer $jobOffer): View
    {
        if ($jobOffer->company_id !== auth()->user()->company_id) abort(404);
        if ($jobOffer->status === 'published') return redirect()->route('recruteur.offres.show', $jobOffer)->with('error', 'Offre déjà publiée.');
        $jobOffer->load('offerCriteria');
        $packs = RecruitmentPack::all();
        $sectors = Sector::orderBy('name')->get();
        return view('recruteur.offres.edit', compact('jobOffer', 'packs', 'sectors'));
    }

    public function update(Request $request, JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->company_id !== auth()->user()->company_id) abort(404);
        if ($jobOffer->status === 'published') return redirect()->back()->with('error', 'Offre déjà publiée.');

        $jobOffer->update($request->validate([
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'sector_id' => 'nullable|exists:sectors,id',
            'contract_type' => 'required|string|in:CDI,CDD,Freelance,Stage,Alternance,Temps partiel',
            'description' => 'required|string',
            'main_criteria' => 'nullable|string',
        ]));

        return redirect()->route('recruteur.offres.show', $jobOffer)->with('success', 'Offre mise à jour.');
    }

    public function publish(JobOffer $jobOffer): RedirectResponse
    {
        if ($jobOffer->company_id !== auth()->user()->company_id) abort(404);
        $jobOffer->update(['status' => 'published', 'published_at' => now()]);
        return redirect()->back()->with('success', 'Offre publiée.');
    }
}
