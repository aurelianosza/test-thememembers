<?php

namespace App\Services\Payments\Interfaces;

use App\Services\Payments\Interfaces\CanBePaydInterface;

interface PaymentInterface
{
    public function pay(CanBePaydInterface $payment) : void;
    public function paymentDocument(CanBePaydInterface $payment);
}
