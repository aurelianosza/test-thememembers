<?php

namespace App\Traits;

use App\Services\Payments\Interfaces\PaymentInterface;
use Error;

trait HasPaymentMethodService
{
    public function paymentService(string $service) : PaymentInterface
    {
        $methods = config("payments.services");

        if(!in_array($service, array_keys($methods)))
        {
            throw new Error("Service payment {$service} dont available");
        }

        $gatewayClass = data_get($methods, $service. '.service_class');

        return app()->make($gatewayClass);
    }
}
