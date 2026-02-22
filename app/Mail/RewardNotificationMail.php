<?php

namespace App\Mail;

use App\Models\Reward;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RewardNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Reward $reward
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre récompense de recrutement - ' . config('app.name'),
            from: config('mail.from.address', 'noreply@recrutement.ma'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reward-notification',
        );
    }
}
