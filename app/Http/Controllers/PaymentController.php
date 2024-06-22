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

    public function store(
        PaymentRequest  $request,
        PaymentService  $paymentService,
        BuyerService    $buyerService,
        ApiResponse     $response
    )
    {
        // $data = $request->all();
        // ProcessPaymentJob::dispatch($data, $paymentService);

        // return $this->processing("Payment request received and is being processed.");

        $buyer = $buyerService->findByDocument($request->buyer_document);

        $paymentService
            ->create([
                'buyer_id'  => $buyer->id,
                ...$request->all()
            ]);

        
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
