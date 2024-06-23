<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Response\ApiResponse;
use App\Interfaces\PaymentGatewayInterface;
use App\Jobs\ProcessPaymentJob;
use App\Models\Payment;
use App\Services\BuyerService;
use App\Services\PaymentService;
use App\Traits\HasPaymentMethodService;

class PaymentController extends Controller
{
    use HasPaymentMethodService;

    const CONTROLLER_PREFIX = "/payments";

    public function store(
        PaymentRequest  $request,
        PaymentService  $paymentService,
        BuyerService    $buyerService,
        ApiResponse     $response
    )
    {
        $buyer = $buyerService->findByDocument($request->buyer_document);

        $payment = $paymentService
            ->create([
                'buyer_id'  => $buyer->id,
                ...$request->all()
            ]);

        $paymentService->processPayment($payment);

        return $response
            ->success()
            ->setData([
                "message"   => __("models." . Payment::class . ".messages.payment_request"),
                "payment"   => $payment
            ])
            ->respond();
    }

    public function showDocument(
        Payment $payment
    )
    {
        $document = $this->paymentService($payment->payment_method)
            ->paymentDocument($payment);

        echo $document;
    }
}
