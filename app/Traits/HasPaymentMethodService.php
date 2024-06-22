<?php

namespace App\Traits;

use Error;

trait HasPaymentMethodService
{
    public function paymentService(string $service)
    {
        $methods = config("payments.gateways");

        if(!in_array($service, array_keys($methods)))
        {
            throw new Error("Service payment {$service} dont available");
        }

        $gatewayClass = $methods[$service];

        return app()->make($gatewayClass);
    }
}
