<?php

namespace App\Providers;

use App\Services\Payments\Interfaces\PaymentInterface;
use App\Models\Payment;
use App\Traits\HasPaymentMethodService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    use HasPaymentMethodService;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentInterface::class, function ($app) {     
          
            return $this->paymentService($app->request->input("payment_method"));

        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::bind("payment", function($value){

            return Payment::query()
                ->where("id", $value)
                ->orWhere("payment_hash", $value)
                ->firstOrFail();

        });
    }
}
