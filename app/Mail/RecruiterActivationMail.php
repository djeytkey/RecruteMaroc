<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecruiterActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $activationUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Activez votre compte recruteur - ' . config('app.name'),
            from: config('mail.from.address', 'noreply@recrutement.ma'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recruiter-activation',
        );
    }
}
