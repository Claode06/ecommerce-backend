<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);

    Route::get('products', [App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('products/{slug}', [App\Http\Controllers\Api\ProductController::class, 'show']);
    Route::get('categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::get('brands', [App\Http\Controllers\Api\BrandController::class, 'index']);
    Route::get('promotions/active', [App\Http\Controllers\Api\PromotionController::class, 'active']);
    Route::get('payment-accounts', [App\Http\Controllers\Api\PaymentAccountController::class, 'index']);
    Route::get('warehouses', [App\Http\Controllers\Api\WarehouseController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::get('profile', [App\Http\Controllers\Api\ProfileController::class, 'show']);
        Route::put('profile', [App\Http\Controllers\Api\ProfileController::class, 'update']);

        Route::get('cart', [App\Http\Controllers\Api\CartController::class, 'index']);
        Route::post('cart', [App\Http\Controllers\Api\CartController::class, 'store']);
        Route::put('cart/{id}', [App\Http\Controllers\Api\CartController::class, 'update']);
        Route::delete('cart/{id}', [App\Http\Controllers\Api\CartController::class, 'destroy']);

        Route::post('checkout', [App\Http\Controllers\Api\CheckoutController::class, 'store']);

        Route::get('orders', [App\Http\Controllers\Api\OrderController::class, 'index']);
        Route::get('orders/{id}', [App\Http\Controllers\Api\OrderController::class, 'show']);
        Route::post('orders/{id}/payment', [App\Http\Controllers\Api\OrderController::class, 'uploadPayment']);
        Route::get('orders/{id}/shipment', [App\Http\Controllers\Api\OrderController::class, 'shipment']);

        Route::post('reviews', [App\Http\Controllers\Api\ReviewController::class, 'store']);

        Route::get('points', [App\Http\Controllers\Api\PointController::class, 'index']);
    });
});
