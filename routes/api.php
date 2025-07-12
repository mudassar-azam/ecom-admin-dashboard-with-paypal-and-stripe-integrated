<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\PaymentController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/categories', [ApiController::class, 'getAllCategories']);
Route::get('/categories/{id}', [ApiController::class, 'getCategory']);
Route::get('/products', [ApiController::class, 'getAllProducts']);
Route::get('/products/search', [ApiController::class, 'searchProducts']);
Route::get('/products/{id}', [ApiController::class, 'getProduct']);
Route::get('/currency/default', [ApiController::class, 'getDefaultCurrency']);
Route::get('/shipping-settings', [ApiController::class, 'getShippingSettings']);
Route::get('/products/category/{slug}', [ApiController::class, 'getProductsByCategorySlug']);
Route::get('/cart/user/{userId}', [ApiController::class, 'getCartItemsByUserId']);

Route::post('/checkout', [PaymentController::class, 'apiCheckout']);
Route::get('/payment/paypal/{orderId}', [PaymentController::class, 'apiPaypal'])->name('api.paypal');
Route::get('/payment/stripe/{orderId}', [PaymentController::class, 'apiStripe'])->name('api.stripe');
Route::get('/payment/stripe/success/{orderId}', [PaymentController::class, 'apiStripeSuccess'])->name('api.stripe.success');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/test', [ApiController::class, 'test']);
    Route::post('/cart/add', [ApiController::class, 'addToCart']);
    Route::delete('/cart/remove', [ApiController::class, 'removeFromCart']);
}); 