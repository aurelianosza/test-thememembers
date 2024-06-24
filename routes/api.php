<?php

use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Buyer\BuyerPostCsvController;
use App\Http\Controllers\Product\ProductPostCsvController;
use App\Http\Controllers\Product\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post(
    ProductPostCsvController::CONTROLLER_PREFIX,
    ProductPostCsvController::class
);

Route::group([
    "controller"    => ProductController::class,
    "as"            => "products.",
    "prefix"        => ProductController::CONTROLLER_PREFIX
], function() {


    Route::get('/', 'index')
        ->name('index');

    Route::post('/', 'store')
        ->name('store');

    Route::get('/{product}', 'show')
        ->name('show');

    Route::patch('/{product}', 'update')
        ->name('update');

    Route::delete('/{product}', 'destroy')
        ->name('destroy');
});


Route::post(
    BuyerPostCsvController::CONTROLLER_PREFIX,
    BuyerPostCsvController::class
);

Route::group([
    "controller"    => BuyerController::class,
    "as"            => "buyers.",
    "prefix"        => BuyerController::CONTROLLER_PREFIX
], function() {

    Route::get('/', 'index')
        ->name('index');

    Route::post('/', 'store')
        ->name('store');

    Route::get('/{buyer}', 'show')
        ->name('show');

    Route::patch('/{buyer}', 'update')
        ->name('update');

    Route::delete('/{buyer}', 'destroy')
        ->name('destroy');

});

Route::group([
    "controller"    => PaymentController::class,
    "prefix"        => PaymentController::CONTROLLER_PREFIX,
    "as"            => "payments."
], function() {

    Route::post("/", "store")
        ->name("store");

    Route::get("/{payment}/document", "showDocument")
        ->name("document");

});
