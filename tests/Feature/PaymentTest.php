<?php

namespace Tests\Feature;

use App\Models\Buyer;
use App\Models\Product;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    // public function test_payment_can_be_created()
    // {
    //     Buyer::where("document", "61873947046")->delete();
    //     $buyer = Buyer::create([
    //         "document" => "61873947046",
    //         "email" => "test@test.com",
    //     ]);

    //     Product::where("code", "testid")->delete();
    //     $product = Product::create([
    //         "code" => "testid",
    //         "name" => "Test",
    //         "description" => "Test",
    //     ]);

    //     $response = $this->post("api/payment", [
    //         "amount" => 100,
    //         "payment_method" => "pix",
    //         "buyer_document" => $buyer->document,
    //         "product_id" => "testid",
    //     ]);
    //     $response->assertStatus(200);

    //     $buyer->delete();
    //     $product->delete();

    // }
}
