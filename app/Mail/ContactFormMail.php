<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $data,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'SOHMC Contact Form: ' . $this->data['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: view('emails.contact-form', ['data' => $this->data])->render(),
        );
    }
}
