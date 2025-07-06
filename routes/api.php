<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/categories', [ApiController::class, 'getAllCategories']);
Route::get('/categories/{id}', [ApiController::class, 'getCategory']);
Route::get('/products', [ApiController::class, 'getAllProducts']);
Route::get('/products/search', [ApiController::class, 'searchProducts']);
Route::get('/products/{id}', [ApiController::class, 'getProduct']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/test', [ApiController::class, 'test']);
    Route::post('/cart/add', [ApiController::class, 'addToCart']);
    Route::delete('/cart/remove', [ApiController::class, 'removeFromCart']);
}); 