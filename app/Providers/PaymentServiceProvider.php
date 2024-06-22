<?php

namespace App\Providers;

use App\Interfaces\PaymentGatewayInterface;
use App\Traits\HasPaymentMethodService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    use HasPaymentMethodService;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {     
          
            return $this->paymentService($app->request->input("payment_method"));

        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
