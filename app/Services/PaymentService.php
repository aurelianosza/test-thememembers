<?php

namespace App\Services;

use App\Jobs\EmailNotificationsJob;
use App\Jobs\ProcessPaymentJob;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected $payment;
    protected $buyerService;
    protected $productService;
    protected $paymentGateway;

    public function __construct(
        Payment $payment,
        BuyerService $buyerService,
        ProductService $productService,
    ) {
        $this->payment = $payment;
        $this->buyerService = $buyerService;
        $this->productService = $productService;
    }

    public function create(array $data) : Payment
    {
        $payment = DB::transaction(function() use ($data) {

            $payment = Payment::create([
                "payment_hash"      => "",
                "payment_method"    => $data["payment_method"],
                "status"            => Payment::STATUS_PENDING,
                "amount"            => $data["amount"],
                "buyer_id"          => $data["buyer_id"]
            ]);

            $productList = Product::query()
                ->whereIn("code", array_map(fn($item) => $item['code'], $data['products']))
                ->get();

            $payment
                ->products()
                ->sync(
                    array_reduce($data["products"], function($carry, $item) use ($productList) {

                        $product = $productList->first(fn($productItem) => $productItem->code == $item['code']);

                        return [
                            "{$product->id}"    => [
                                "amount"            => $item["amount"],
                                "unitary_price"     => $product->price
                            ],
                            ...$carry,
                        ];
                    }, [])
                );

            return $payment;
        });

        return $payment;
    }

    function processPayment(Payment $payment)
    {
        ProcessPaymentJob::dispatch($payment, $payment->payment_method)
            ->onQueue("payment");

        //envia email
        // EmailNotificationsJob::dispatch($buyer, $paymentData["amount"]);
    
    }

    function aprovesBoleto(array $paymentHashs)
    {
        Payment::query()
            ->whereIn("payment_hash", $paymentHashs)
            ->update([
                "status" => Payment::STATUS_SUCCESS
            ]);
    }
}
