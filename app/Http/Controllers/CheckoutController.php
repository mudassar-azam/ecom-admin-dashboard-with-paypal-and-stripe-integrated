<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', 1)->get();
        $shippingSetting = \App\Models\ShippingSetting::first();
        $shippingCost = $shippingSetting ? $shippingSetting->shipping_cost : 0;

        return view('order.checkout', compact('cartItems', 'shippingCost'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'shipping_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'required|string',
            'country' => 'required|string',
            'product_ids' => 'required|array',
            'quantities' => 'required|array',
            'payment_method' => 'required|in:paypal,stripe',
            'total' => 'required|numeric|min:0',
        ]);

        $shippingSetting = \App\Models\ShippingSetting::first();
        $shippingCost = $shippingSetting ? $shippingSetting->shipping_cost : 0;

        $order = Order::create([
            'user_id' => 1,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'shipping_address' => $validated['shipping_address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zipcode' => $validated['zipcode'],
            'country' => $validated['country'],
            'total' => $validated['total'],
            'payment_method' => $validated['payment_method'],
            'status' => 1,
        ]);

        foreach ($validated['product_ids'] as $index => $productId) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $validated['quantities'][$index],
            ]);
        }

        Cart::where('user_id', 1)->delete();

        if ($validated['payment_method'] === 'paypal') {
            return redirect()->route('paypal.payment', $order->id);
        } else if ($validated['payment_method'] === 'stripe') {
            return redirect()->route('stripe.payment', $order->id);
        } else {
            return redirect()->route('products.index')->with('error', 'Invalid payment method.');
        }
    }
}
