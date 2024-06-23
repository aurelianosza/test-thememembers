<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment)
    {
        $payment->generatePaymentHash();
    }
}
