<?php 

namespace App\Gateways\Payments;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Payment;
use App\Models\PaymentLog;
use App\Traits\SaveLogTrait;
use Illuminate\Support\Facades\Storage;

class CreditCardGateway implements PaymentGatewayInterface
{
    use SaveLogTrait;
    public function pay(array $data): string
    {
        $this->saveLog( "Cartão de Credito");
        return "Payd with Credit card";
    }

    public function paymentDocument(Payment $payment)
    {
        return view("payments.credit_card",[
            "payment"   => $payment
        ]);
    }
}
