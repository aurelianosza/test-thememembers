<?php

namespace Tests\Feature;

use App\Models\Buyer;
use App\Services\BuyerService;
use Tests\TestCase;

class BuyerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_buyer_success(): void
    {
        Buyer::where("document", "61873947046")->delete();
        $response = $this->post("api/buyer", [
            "document" => "61873947046",
            "email" => "test@test.com",
        ]);
        $response->assertStatus(201);

        Buyer::where("document", "61873947046")->delete();
    }

    public function test_buyer_fail(): void
    {
        $response = $this->post("api/buyer", [
            "document" => "000000000",
            "email" => "test@test.com",
        ]);
        $response->assertStatus(302);
    }

    public function test_buyer_conflict(): void
    {
        $buyer = Buyer::create([
            "document" => "61873947046",
            "email" => "test@test.com",
        ]);
        
        $response = $this->post("api/buyer", [
            "document" => "61873947046",
            "email" => "test@test.com",
        ]);
        $response->assertStatus(400);

        $buyer->delete();
    }
}
