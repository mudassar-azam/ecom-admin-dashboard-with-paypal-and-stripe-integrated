<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticated;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShippingSettingController;

Auth::routes();
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(Authenticated::class)->group(function () {
    Route::get('/dashboard', function () {
        return view('index');
    })->name('dashboard');

    //category
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);


    //brand
    Route::get('brand', [BrandController::class, 'index'])->name('brands.index');
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('store/brand', [BrandController::class, 'store'])->name('brands.store');
    Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

    // variation 
    Route::get('/product-variation/{id}', [VariationController::class, 'getAllVariations'])->name('product.variations');
    Route::get('/get-attribute-values', [VariationController::class, 'getAttributeValues'])->name('attribute.values');
    Route::get('variations', [VariationController::class, 'index'])->name('variations.index');
    Route::get('variations/create', [VariationController::class, 'create'])->name('variations.create');
    Route::post('variations', [VariationController::class, 'store'])->name('variations.store');
    Route::get('variations/{variation}/edit', [VariationController::class, 'edit'])->name('variations.edit');
    Route::put('variations/{variation}', [VariationController::class, 'update'])->name('variations.update');
    Route::delete('variations/{variation}', [VariationController::class, 'destroy'])->name('variations.destroy');

    // product 
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('all-products', [ProductController::class, 'showAll'])->name('front.allProducts');
    Route::get('/product-details/{id}', [ProductController::class, 'showSingleProduct'])->name('products.details');
    Route::get('/attribute-values/{ids}', [ProductController::class, 'getAttributeValues']);


    Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');

    // currency
    Route::get('currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::post('currencies/set-default', [CurrencyController::class, 'setDefault'])->name('currencies.setDefault');
    Route::get('currencies/create', [CurrencyController::class, 'create'])->name('currencies.create');
    Route::post('currencies', [CurrencyController::class, 'store'])->name('currencies.store');
    Route::get('currencies/{currency}/edit', [CurrencyController::class, 'edit'])->name('currencies.edit');
    Route::put('currencies/{currency}', [CurrencyController::class, 'update'])->name('currencies.update');
    Route::delete('currencies/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');

    //checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.page');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    //order/paypal
    Route::get('/paypal/payment/{orderId}', [PaymentController::class, 'paypal'])->name('paypal.payment');
    Route::get('/paypal/success/{orderId}', [PaymentController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PaymentController::class, 'cancel'])->name('paypal.cancel');
    Route::get('/orders/paypal', [OrderController::class, 'paypalOrders'])->name('orders.paypal');
    Route::get('/orders/stripe', [OrderController::class, 'stripeOrders'])->name('orders.stripe');
    Route::put('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    Route::get('/stripe/payment/{orderId}', [PaymentController::class, 'stripe'])->name('stripe.payment');
    Route::get('/stripe/success/{orderId}', [PaymentController::class, 'stripeSuccess'])->name('stripe.success');
    Route::get('/stripe/cancel', [PaymentController::class, 'stripeCancel'])->name('stripe.cancel');

    Route::get('/shipping-settings', [ShippingSettingController::class, 'index'])->name('shipping-settings.index');
    Route::post('/shipping-settings/update', [ShippingSettingController::class, 'update'])->name('shipping-settings.update');
});
