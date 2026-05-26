<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AuthorizationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\PaymentAccountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\FileStorageController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('role:Superadmin')->group(function () {
            Route::resource('roles', RoleController::class);
            Route::get('roles/{role}/authorizations', [AuthorizationController::class, 'edit'])->name('roles.authorizations.edit');
            Route::put('roles/{role}/authorizations', [AuthorizationController::class, 'update'])->name('roles.authorizations.update');
            Route::resource('admins', AdminController::class);
        });

        Route::resource('users', UserController::class)->only(['index', 'show', 'destroy']);
        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('products', ProductController::class);
        Route::resource('products.variants', ProductVariantController::class);
        Route::resource('warehouses', WarehouseController::class);
        Route::resource('stocks', StockController::class)->only(['index', 'store']);
        Route::resource('promotions', PromotionController::class);
        Route::resource('promotions.items', \App\Http\Controllers\Admin\PromotionItemController::class)->except(['index', 'show']);
        Route::resource('payment-accounts', PaymentAccountController::class);
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::resource('payments', PaymentController::class)->only(['index', 'show']);
        Route::post('payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
        Route::post('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
        Route::resource('shipments', ShipmentController::class);
        Route::post('shipments/{shipment}/update-status', [ShipmentController::class, 'updateStatus'])->name('shipments.update-status');
        Route::resource('points', PointController::class)->only(['index', 'show']);
        Route::resource('reviews', ReviewController::class)->only(['index']);
        Route::post('reviews/{review}/toggle', [ReviewController::class, 'toggle'])->name('reviews.toggle');
        Route::resource('file-storages', FileStorageController::class)->only(['index']);
    });
});
