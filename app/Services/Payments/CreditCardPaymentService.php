<?php 

namespace App\Services\Payments;

use App\Services\Payments\Exceptions\PaymentErrorException;
use App\Services\Payments\Interfaces\CanBePaydInterface;
use App\Services\Payments\Interfaces\PaymentInterface;

class CreditCardPaymentService implements PaymentInterface
{    
    public function pay(CanBePaydInterface $payment): void
    {
        $payment
            ->logPayment("Request to pay {$payment->paymentData()["payment_hash"]} with credit card.");
        
        sleep(rand(5, 30));

        // Nesta área estarei simulando um número aletório para erro de pagamento via cartão
        if(rand(0, 5) == 5)
        {
            $payment
                ->setStatus("error");

            $payment
                ->logPayment("Payment {$payment->paymentData()["payment_hash"]} fail with credit card.");

            throw new PaymentErrorException();
        }

        $payment
            ->setStatus("success");

        $payment
            ->logPayment("Payment {$payment->paymentData()["payment_hash"]} success with credit card.");
    }

    public function paymentDocument(CanBePaydInterface $payment)
    {
        return view("payments.credit_card",[
            "payment"   => $payment
        ]);
    }
}
