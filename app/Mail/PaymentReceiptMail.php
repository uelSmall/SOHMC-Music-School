<?php

namespace App\Mail;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Payment Receipt '.$this->payment->receipt_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-receipt',
            with: [
                'receiptUrl' => route('frontend.receipts.view', $this->payment->receipt_token),
            ],
        );
    }

    public function attachments(): array
    {
        $pdf = Pdf::loadView('receipts.receipt', [
            'payment' => $this->payment,
            'logoBase64' => $this->logoBase64(),
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), $this->payment->receipt_number.'.pdf')
                ->withMime('application/pdf'),
        ];
    }

    protected function logoBase64(): string
    {
        return receipt_logo_base64();
    }
}