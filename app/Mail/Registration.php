<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class Registration extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private $name)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')),
            subject: 'Successful Registration with Habit Tracker',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.registration',
            with: ['name' => $this->name],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
