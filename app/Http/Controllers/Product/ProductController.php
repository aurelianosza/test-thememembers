<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Response\ApiPaginationResponse;
use App\Http\Response\ApiResponse;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    const CONTROLLER_PREFIX = '/products';

    public function index(
        Request $request,
        ProductService $productService,
        ApiPaginationResponse $response
    )
    {
        $productListQuery = $productService
            ->search($request->only("query", "order_by", "direction"));

        return $response
            ->setData($productListQuery, ProductResource::class)
            ->respond();
    }

    public function store(
        ProductStoreRequest $request,
        ProductService $productService,
        ApiResponse $response
    )
    {
        $product = $productService
            ->create($request->productPayload());
        
        return $response
            ->created()
            ->setData([
                "message"   => __("messages.cruds.created_with_success", [
                    "model"     => __("models.". Product::class . ".name")
                ]),
                "product"   => new ProductResource($product)
            ])
            ->respond();
    }

    public function show(
        Product $product,
        ApiResponse $response
    )
    {
        return $response
            ->success()
            ->setData([
                "message"   => __("messages.cruds.found_success", [
                    "model"     => __("models." . Product::class. ".name")
                ]),
                "product"   => new ProductResource($product)
            ])
            ->respond();
    }

    public function update(
        Product $product,
        ProductUpdateRequest $request,
        ProductService $productService,
        ApiResponse $response
    )
    {
        $productService 
            ->update($product, $request->productPayload());

        return $response
            ->success()
            ->setData([
                "message"   => __("messages.cruds.updated_with_success", [
                    "model"     => __("models.". Product::class . ".name")
                ]),
                "product"   => new ProductResource($product)
            ])
            ->respond();
    }

    public function destroy(
        Product $product,
        ApiResponse $response
    )
    {
        $product->delete();

        return $response
            ->success()
            ->setData([
                "message"   => __("messages.cruds.deleted_with_success", [
                    "model"     => __("models." . Product::class . ".name")
                ]),
                "product"   => new ProductResource($product)
            ])
            ->respond();
    }
}
