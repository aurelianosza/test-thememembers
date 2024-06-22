<?php

namespace App\Services;

use App\Interfaces\PaymentGatewayInterface;
use App\Jobs\EmailNotificationsJob;
use App\Models\Payment;
use App\Models\Product;
use App\Traits\SaveLogTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentService
{
    use SaveLogTrait;

    protected $payment;
    protected $buyerService;
    protected $productService;
    protected $paymentGateway;

    public function __construct(
        Payment $payment,
        BuyerService $buyerService,
        ProductService $productService,
        PaymentGatewayInterface $paymentGateway
    ) {
        $this->payment = $payment;
        $this->buyerService = $buyerService;
        $this->productService = $productService;
        $this->paymentGateway = $paymentGateway;
    }

    public function create(array $data)
    {
        DB::transaction(function() use ($data) {

            $payment = Payment::create([
                "payment_hash"      => md5(serialize($data)),
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
                            ...$carry,
                            $product->id    => [
                                "amount"        => $item["amount"],
                                "unitary_price" => $product->price
                            ]
                        ];
                    }, [])
                );
        });

    }

    function processPayment(array $data): Payment
    {
        try {
            $buyer = $this->buyerService->findByDocument($data["buyer_document"]);
            if (!$buyer) {
                throw new Exception("Buyer not found.");
            }
            $product = $this->productService->findByCode($data["product_id"]);
            if (!$product) {
                throw new Exception("Product not found.");
            }

            $paymentData = [
                "payment_method" => $data["payment_method"],
                "status" => "ok",
                "amount" => $data["amount"],
                "buyer_id" => $buyer->id,
                "product_id" => $product->id
            ];

            //Processa o pagamento no gateway referente ao tipo de pagamento
            $gatewayResponse =  $this->paymentGateway->pay($paymentData);
            dump($gatewayResponse);

            //registra no BD
            $paymentResponse = $this->payment->create($paymentData);

            //envia email
            EmailNotificationsJob::dispatch($buyer, $paymentData["amount"]);

            return $paymentResponse;
        } catch (\Throwable $th) {
            $this->saveLog("Erro: " . $th->getMessage());
            dump("Erro: " . $th->getMessage());
            return null;
        }
    }
}
