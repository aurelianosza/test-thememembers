<?php

namespace App\Jobs;

use App\Services\Payments\Exceptions\PaymentErrorException;
use App\Services\Payments\Interfaces\CanBePaydInterface;
use App\Traits\HasPaymentMethodService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Services\Payments\Mail\{MailNotifyFailPayment, MailNotifyPayment};

class ProcessPaymentJob implements ShouldQueue
{
    use
        Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        HasPaymentMethodService;

    /**
     * Create a new job instance.
     */
    private CanBePaydInterface  $payment;
    private string              $paymentMethod;
    
    public function __construct(CanBePaydInterface $payment, string $paymentMethod)
    {
        $this->payment          = $payment;
        $this->paymentMethod    = $paymentMethod;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            $this->paymentService($this->paymentMethod)
                ->pay($this->payment);

            Mail::to($this->payment->paymentData()["email"])
                ->queue(new  MailNotifyPayment($this->payment));

        } catch (PaymentErrorException $exception) {

            Mail::to($this->payment->paymentData()["email"])
                ->queue(new  MailNotifyFailPayment($this->payment));
        }
        finally {}

    }
}
