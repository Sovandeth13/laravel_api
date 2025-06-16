<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\PayPalController;
// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/discounts', [DiscountController::class, 'index']);
Route::get('categories/{id}/products', [CategoryController::class, 'products']);

// Protected routes
Route::middleware('auth:sanctum')->group(function() {
    // Order routes
    Route::post('/orders', [OrderController::class, 'store']);         // User place order
    Route::get('/orders/my', [OrderController::class, 'myOrders']);    // User see own orders
    Route::get('/orders/{id}', [OrderController::class, 'show']);      // User see one order

    // Cart routes (ADD THESE)
    Route::get('/cart', [CartController::class, 'index']);             // Get user's cart
    Route::post('/cart', [CartController::class, 'store']);            // Add to cart
    Route::put('/cart/{id}', [CartController::class, 'update']);       // Update cart item
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);   // Remove cart item



});
Route::post('/test-api', function() {
    return response()->json(['message' => 'API is working!']);
});
Route::post('/create-paypal-order', [PayPalController::class, 'createOrder']);
    Route::post('/capture-paypal-order', [PayPalController::class, 'captureOrder']);
