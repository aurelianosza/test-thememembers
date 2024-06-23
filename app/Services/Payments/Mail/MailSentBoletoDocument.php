<?php

namespace App\Services\Payments\Mail;

use App\Services\Payments\Interfaces\CanBePaydInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailSentBoletoDocument extends Mailable implements ShouldQueue
{
    use Queueable,
        SerializesModels;

    protected CanBePaydInterface $payment;

    public function __construct(CanBePaydInterface $payment)
    {   
        $this->payment = $payment;
        $this->onQueue("emails");
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Payment Notification",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: "mail.mail-sent-boleto-document",
            with: [
                "payment" => $this->payment
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
