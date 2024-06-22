<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private ProductService $productService;

    public function setUp() : void
    {
        parent::setUp();

        $this->productService = $this->app
            ->make(ProductService::class);
    }

    public function test_product_store_using_product_service()
    {
        $productData = Product::factory()
            ->make();

        $this->productService
            ->create($productData->toArray());

        $this->assertDatabaseHas("products", $productData->toArray());
    }

    public function test_product_store_calling_store_route()
    {
        $productData = Product::factory()
            ->make();

        $response = $this->post("/api/products", $productData->toArray());

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas("products", $productData->toArray());
    }

    public function test_product_store_without_code_error()
    {
        $productData = Product::factory()
            ->make();

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/products", []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment([
            "code"              => [
                __("validation.required", [
                    "attribute"         => __("validation.attributes.product_code")
                ])
            ],
        ]);
        $response->assertJsonFragment([
            "name"              => [
                __("validation.required", [
                    "attribute"         => __("validation.attributes.name")
                ])
            ],
        ]);
        $response->assertJsonFragment([
            "description"              => [
                __("validation.present", [
                    "attribute"         => __("validation.attributes.description")
                ])
            ],
        ]);
        $response->assertJsonFragment([
            "price"              => [
                __("validation.required", [
                    "attribute"         => __("validation.attributes.price")
                ])
            ],
        ]);

        $this->assertDatabaseMissing("products", $productData->toArray());
    }

    public function test_product_store_code_attribute_can_be_max_chars()
    {
        $max = 16;

        $productData = Product::factory()
            ->make([
                "code"      => str_repeat(fake()->randomLetter(), $max + 1)
            ]);


        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/products", $productData->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment([
            "code"              => [
                __("validation.max.string", [
                    "attribute"         => __("validation.attributes.product_code"),
                    "max"               => $max
                ])
            ],
        ]);

        $this->assertDatabaseMissing("products", $productData->toArray());
    }

    public function test_product_store_code_attribute_can_be_unique()
    {
        $productInDatabasee = Product::factory()
            ->create();

        $productData = Product::factory()
            ->make([
                "code"      => $productInDatabasee->code
            ]);


        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/products", $productData->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment([
            "code"              => [
                __("validation.unique", [
                    "attribute"         => __("validation.attributes.product_code"),
                ])
            ],
        ]);

        $this->assertDatabaseMissing("products", $productData->toArray());
    }

    public function test_product_store_name_attribute_can_be_max_chars()
    {
        $max = 64;

        $productData = Product::factory()
            ->make([
                "name"      => str_repeat(fake()->randomLetter(), $max + 1)
            ]);


        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/products", $productData->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment([
            "name"              => [
                __("validation.max.string", [
                    "attribute"         => __("validation.attributes.name"),
                    "max"               => $max
                ])
            ],
        ]);

        $this->assertDatabaseMissing("products", $productData->toArray());
    }

    public function test_product_store_description_attribute_can_be_max_chars()
    {
        $max = 255;

        $productData = Product::factory()
            ->make([
                "description"      => str_repeat(fake()->randomLetter(), $max + 1)
            ]);


        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/products", $productData->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment([
            "description"              => [
                __("validation.max.string", [
                    "attribute"         => __("validation.attributes.description"),
                    "max"               => $max
                ])
            ],
        ]);

        $this->assertDatabaseMissing("products", $productData->toArray());
    }

    public function test_product_store_price_attribute_cant_be_zero_value()
    {
        $productData = Product::factory()
            ->make([
                "price"      => 0
            ]);


        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/products", $productData->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment([
            "price"              => [
                __("validation.gt.numeric", [
                    "attribute"         => __("validation.attributes.price"),
                    "value"               => 0
                ])
            ],
        ]);

        $this->assertDatabaseMissing("products", $productData->toArray());
    }

    public function test_product_store_price_attribute_can_be_numeric()
    {
        $productData = Product::factory()
            ->make();

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/products", [
            ...$productData->toArray(),
            "price" => (string)$productData->price . fake()->randomLetter()
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment([
            "price"              => [
                __("validation.gt.numeric", [
                    "attribute"         => __("validation.attributes.price"),
                    "value"               => 0
                ]),
                __("validation.numeric", [
                    "attribute"         => __("validation.attributes.price"),
                ]),
                __("validation.lt.numeric", [
                    "attribute"         => __("validation.attributes.price"),
                    "value"               => 99999
                ]),
            ],
        ]);

        $this->assertDatabaseMissing("products", $productData->toArray());
    }

    public function test_product_store_price_attribute_can_less_than_value()
    {
        $max = 99999;

        $productData = Product::factory()
            ->make([
                "price" => $max + 1
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post("/api/products", $productData->toArray());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment([
            "price"              => [
                __("validation.lt.numeric", [
                    "attribute"         => __("validation.attributes.price"),
                    "value"             => $max
                ]),
            ],
        ]);

        $this->assertDatabaseMissing("products", $productData->toArray());
    }
}
