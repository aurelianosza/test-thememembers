<?php

namespace Tests\Feature;

use App\Models\Buyer;
use App\Services\BuyerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class BuyerTest extends TestCase
{
    use RefreshDatabase;

    private BuyerService $buyerservice;

    public function setUp() : void
    {
        parent::setUp();

        $this->buyerservice = $this->app
            ->make(BuyerService::class);
    }

    public function test_buyer_create_using_buyer_service()
    {
        $buyerData = Buyer::factory()
            ->make();

        $this->buyerservice
            ->create($buyerData->toArray());

        $this->assertDatabaseHas("buyers", $buyerData->toArray());
    }

    public function test_buyer_store_using_store_route()
    {
        $buyerData = Buyer::factory()
            ->make();

        $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", $buyerData->toArray());

        $this->assertDatabaseHas("buyers", $buyerData->toArray());
    }

    public function test_buyer_with_clean_request_error()
    {
        $buyerData = Buyer::factory()
            ->make();

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", []);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonFragment([
            "name"              => [
                __("validation.required", [
                    "attribute"         => __("validation.attributes.name")
                ])
            ],
        ]);

        $response->assertJsonFragment([
            "email"              => [
                __("validation.required", [
                    "attribute"         => __("validation.attributes.email")
                ])
            ],
        ]);

        $response->assertJsonFragment([
            "document"              => [
                __("validation.required", [
                    "attribute"         => __("validation.attributes.document")
                ])
            ],
        ]);

        $this->assertDatabaseMissing("buyers", $buyerData->toArray());
    }

    public function test_buyer_create_name_can_be_only_string()
    {
        $buyerData = Buyer::factory()
            ->make([
                "name"  => (int)str_repeat('4', rand(1, 99))
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", $buyerData->toArray());

        $response->assertJsonFragment([
            "name"              => [
                __("validation.string", [
                    "attribute"         => __("validation.attributes.name")
                ])
            ],
        ]);

        $this->assertDatabaseMissing("buyers", $buyerData->toArray());
    }

    public function test_buyer_create_name_can_has_max_chars()
    {
        $max = 255;

        $buyerData = Buyer::factory()
            ->make([
                "name"  => str_repeat('a', $max + 1)
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", $buyerData->toArray());

        $response->assertJsonFragment([
            "name"              => [
                __("validation.max.string", [
                    "attribute"         => __("validation.attributes.name"),
                    "max"               => $max
                ])
            ],
        ]);

        $this->assertDatabaseMissing("buyers", $buyerData->toArray());
    }

    public function test_buyer_create_email_should_be_valid_email()
    {
        $buyerData = Buyer::factory()
            ->make([
                "email"  => "not_a_valid_email"
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", $buyerData->toArray());

        $response->assertJsonFragment([
            "email"              => [
                __("validation.email", [
                    "attribute"         => __("validation.attributes.email"),
                ])
            ],
        ]);

        $this->assertDatabaseMissing("buyers", $buyerData->toArray());        
    }

    public function test_buyer_create_email_has_max_chars()
    {
        $max = 255;

        $buyerData = Buyer::factory()
            ->make([
                "email"  => str_repeat("a", $max) . "@mail.com"
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", $buyerData->toArray());

        $response->assertJsonFragment([
            "email"              => [
                __("validation.max.string", [
                    "attribute"         => __("validation.attributes.email"),
                    "max"               => $max
                ])
            ],
        ]);

        $this->assertDatabaseMissing("buyers", $buyerData->toArray());        
    }

    public function test_buyer_create_email_should_be_unique()
    {
        $buyerExistingData = Buyer::factory()
            ->create();

        $buyerData = Buyer::factory()
            ->make([
                "email" => $buyerExistingData->email
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", $buyerData->toArray());

        $response->assertJsonFragment([
            "email"              => [
                __("validation.unique", [
                    "attribute"         => __("validation.attributes.email"),
                ])
            ],
        ]);

        $this->assertDatabaseMissing("buyers", $buyerData->toArray());        
    }

    public function test_buyer_create_document_should_be_unique()
    {
        $buyerExistingData = Buyer::factory()
            ->create();

        $buyerData = Buyer::factory()
            ->make([
                "document" => $buyerExistingData->document
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", $buyerData->toArray());

        $response->assertJsonFragment([
            "document"              => [
                __("validation.unique", [
                    "attribute"         => __("validation.attributes.document"),
                ])
            ],
        ]);

        $this->assertDatabaseMissing("buyers", $buyerData->toArray());        
    }

    public function test_buyer_create_document_should_be_valid_document_number()
    {
        $buyerData = Buyer::factory()
            ->make();

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", [
            ...$buyerData->toArray(),
            "document"      => "111.222.333-44"
        ]);

        $response->assertJsonFragment([
            "document"              => [
                __("validation.cpf.invalid", [
                    "attribute"         => __("validation.attributes.document"),
                ])
            ],
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/buyers", [
            ...$buyerData->toArray(),
            "document"      => "aaa.bbb.ccc-dd"
        ]);

        $response->assertJsonFragment([
            "document"              => [
                __("validation.cpf.with_pointing", [
                    "attribute"        => __("validation.attributes.document")
                ]),
                __("validation.cpf.length", [
                    "attribute"         => __("validation.attributes.document"),
                ])
            ],
        ]);

        $this->assertDatabaseMissing("buyers", $buyerData->toArray());        
    }
}
