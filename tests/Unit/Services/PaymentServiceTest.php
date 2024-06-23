<?php

namespace Tests\Unit\Services;

use App\Gateways\Payments\PixGateway;
use App\Models\Buyer;
use App\Models\Payment;
use App\Models\Product;
use App\Services\BuyerService;
use App\Services\PaymentService;
use App\Services\ProductService;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    // public function test_process_payment_method()
    // {
    //     $paymentGateway = new PixGateway();
    //     $payment = new Payment();
    //     $buyer = Buyer::create([
    //         "name" => "Test Buyer",
    //         "document" => "61873947046",
    //         "email" => "test@example.com",
    //     ]);
    //     $product = Product::create([
    //         "name" => "Test Product",
    //         "code" => "test-product",
    //         "description" => "Test Product"
    //     ]);

    //     $buyerService = new BuyerService($buyer);
    //     $productService = new ProductService();
    //     $paymentService = new PaymentService($payment, $buyerService, $productService,  $paymentGateway);

    //     $data = [
    //         "amount" => 100,
    //         "buyer_document" => $buyer->document,
    //         "product_id" => $product->code,
    //         "payment_method" => "pix",
    //     ];
    //     $processPayment = $paymentService->processPayment($data);

    //     $this->assertIsArray($data);
    //     $this->assertNotEmpty($processPayment);
    //     $this->assertInstanceOf(Payment::class, $processPayment);
    //     $this->assertEquals(100, $processPayment->amount);
    //     $this->assertEquals($buyer->id, $processPayment->buyer_id);
    //     $this->assertEquals($product->id, $processPayment->product_id);
    //     $this->assertEquals("pix", $processPayment->payment_method);

    //     $buyer->delete();
    //     $product->delete();
    // }
}
