<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Buyer\BuyerStoreRequest;
use App\Http\Requests\Buyer\BuyerUpdateRequest;
use App\Http\Resources\BuyerResource;
use App\Http\Response\ApiPaginationResponse;
use App\Http\Response\ApiResponse;
use App\Services\BuyerService;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    const CONTROLLER_PREFIX = "/buyers";

    public function index(
        Request $request,
        BuyerService $buyerService,
        ApiPaginationResponse $response
    )
    {
        $productListQuery = $buyerService
            ->search($request->only("query", "order_by", "direction"));

        return $response
            ->setData($productListQuery, BuyerResource::class)
            ->respond();
    }

    public function store(
        BuyerStoreRequest $request,
        BuyerService $buyerService,
        ApiResponse $response 
    ) {
        
        $buyer = $buyerService->create($request->all());

        return $response
            ->created()
            ->setData([
                'buyer' => new BuyerResource($buyer)
            ])
            ->respond();       
    }

    public function show(
        Buyer $buyer,
        ApiResponse $response
    )
    {
        return $response
            ->success()
            ->setData([
                "message"   => __("messages.cruds.found_success", [
                    "model"     => __("models." . Buyer::class. ".name")
                ]),
                "product"   => new BuyerResource($buyer)
            ])
            ->respond();
    }

    public function update(
        Buyer $buyer,
        BuyerUpdateRequest $request,
        BuyerService $buyerService,
        ApiResponse $response
    )
    {
        $buyerService 
            ->update($buyer, $request->all());

        return $response
            ->success()
            ->setData([
                "message"   => __("messages.cruds.updated_with_success", [
                    "model"     => __("models.". Buyer::class . ".name")
                ]),
                "product"   => new BuyerResource($buyer)
            ])
            ->respond();
    }

    public function destroy(
        Buyer $buyer,
        ApiResponse $response
    )
    {
        $buyer->delete();

        return $response
            ->success()
            ->setData([
                "message"   => __("messages.cruds.deleted_with_success", [
                    "model"     => __("models." . Buyer::class . ".name")
                ]),
                "product"   => new BuyerResource($buyer)
            ])
            ->respond();
    }
}
