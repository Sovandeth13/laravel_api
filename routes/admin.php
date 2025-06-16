<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\CategoryController;

Route::prefix('admin')->middleware(['api', 'auth:api', 'admin'])->group(function () {
    // Admin Auth
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout']);

    // Admin Users
    Route::get('/users', [AdminController::class, 'users']);
    Route::post('/users/{id}/make-admin', [AdminController::class, 'makeAdmin']);
    Route::post('/users/{id}/remove-admin', [AdminController::class, 'removeAdmin']);

    // Admin Products and Orders
    Route::apiResource('products', AdminProductController::class);
    Route::apiResource('orders', AdminOrderController::class);

    // Categories (used by admin and frontend)
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::middleware(['auth:sanctum', 'admin'])->group(function() {
    Route::get('/orders', [AdminOrderController::class, 'index']);         // Admin: list all orders
    Route::get('/orders/{id}', [AdminOrderController::class, 'show']);     // Admin: single order
    Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus']); // Admin: change status
});
});
