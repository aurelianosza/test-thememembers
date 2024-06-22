<?php

namespace App\Interfaces;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function pay(array $data): string;
    public function paymentDocument(Payment $payment);
}
