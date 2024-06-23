<?php

namespace App\Services\Payments\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Services\Payments\Interfaces\CanBePaydInterface;

class MailNotifyPayment extends Mailable implements ShouldQueue
{
    use Queueable,
        SerializesModels;

    /**
     * Create a new message instance.
     */

     protected CanBePaydInterface $payment;

    public function __construct(CanBePaydInterface $payment)
    {
        $this->payment = $payment;
        $this->onQueue("emails");
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Payment Notification",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: "mail.default_mail_template",
            with: [
                "payment" => $this->payment
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
