<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CandidateRejectionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Application $application
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Réponse à votre candidature - ' . $this->application->jobOffer->title,
            from: config('mail.from.address', 'noreply@recrutement.ma'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.candidate-rejection',
        );
    }
}
