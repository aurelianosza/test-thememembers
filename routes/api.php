<?php

use App\Http\Controllers\BuyerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Product\ProductPostCsvController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/payment", [PaymentController::class, "store"]);
Route::get("/payment/{payment}/document", [PaymentController::class, "showDocument"]);


Route::group([
    "controller"    => ProductController::class,
    "as"            => "products.",
    "prefix"        => ProductController::CONTROLLER_PREFIX
], function() {

    Route::post("/post-csv", ProductPostCsvController::class);

    Route::get('/', 'index')
        ->name('index');

    Route::post('/', 'store')
        ->name('post');

    Route::get('/{product}', 'show')
        ->name('show');

    Route::patch('/{product}', 'update')
        ->name('update');

    Route::delete('/{product}', 'destroy')
        ->name('destroy');
});


// Route::post("/product", [ProductController::class, "store"]);
// Route::patch("/product/{product}", [ProductController::class, "update"]);

Route::post("/buyer", [BuyerController::class, "store"]);
