<?php

namespace App\Services\Payments\Interfaces;

interface CanBePaydInterface
{
    public function paymentData() : array;
    public function setStatus(string $status) : void;
    public function getStatus() : string;
    public function getStatusName() : string;
    public function logPayment(string $logData) : void;
}
