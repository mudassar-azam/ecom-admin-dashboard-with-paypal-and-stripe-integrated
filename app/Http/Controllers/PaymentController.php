<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    private function getPayPalAccessToken()
    {
        $config = config('paypal');
        $mode = $config['mode'];
        $credentials = $config[$mode];
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Accept-Language' => 'en_US',
        ])->withBasicAuth($credentials['client_id'], $credentials['client_secret'])
        ->asForm()
        ->post($mode === 'sandbox' ? 'https://api-m.sandbox.paypal.com/v1/oauth2/token' : 'https://api-m.paypal.com/v1/oauth2/token', [
            'grant_type' => 'client_credentials'
        ]);
        
        if ($response->successful()) {
            return $response->json()['access_token'];
        }
        
        throw new \Exception('Failed to get PayPal access token: ' . $response->body());
    }

    public function paypal($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        if ($order->items->isEmpty()) {
            return redirect()->route('front.allProducts')->with('error', 'Order has no items.');
        }

        try {
            $config = config('paypal');
            $mode = $config['mode'];
            
            $accessToken = $this->getPayPalAccessToken();
            Log::info('PayPal Access Token obtained successfully');

            if ($order->total <= 0) {
                return redirect()->route('front.allProducts')->with('error', 'Order total amount is invalid: ' . $order->total);
            }

            $orderData = [
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.success', $order->id),
                    "cancel_url" => route('paypal.cancel'),
                ],
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => number_format($order->total, 2, '.', ''),
                        ],
                    ]
                ]
            ];
            
            Log::info('PayPal Order Data:', $orderData);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($mode === 'sandbox' ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders' : 'https://api-m.paypal.com/v2/checkout/orders', $orderData);
            
            Log::info('PayPal Response:', $response->json());

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['id']) && $responseData['status'] == 'CREATED') {
                    foreach ($responseData['links'] as $link) {
                        if ($link['rel'] === 'approve') {
                            return redirect()->away($link['href']);
                        }
                    }
                }
            }

            return redirect()->route('front.allProducts')->with('error', 'PayPal order creation failed. Response: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('PayPal Error: ' . $e->getMessage());
            Log::error('PayPal Error Stack: ' . $e->getTraceAsString());
            return redirect()->route('front.allProducts')->with('error', 'PayPal Error: ' . $e->getMessage());
        }
    }

    public function success(Request $request, $orderId)
    {
        try {
            $config = config('paypal');
            $mode = $config['mode'];
            $accessToken = $this->getPayPalAccessToken();
            $captureUrl = $mode === 'sandbox'
                ? 'https://api-m.sandbox.paypal.com/v2/checkout/orders/' . $request->token . '/capture'
                : 'https://api-m.paypal.com/v2/checkout/orders/' . $request->token . '/capture';

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->withBody('{}', 'application/json')->post($captureUrl);

            Log::info('PayPal Capture Response:', $response->json());

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['status']) && $responseData['status'] === 'COMPLETED') {
                    $order = Order::findOrFail($orderId);
                    $order->update([
                        'status' => 1, 
                        'paypal_order_id' => $request->token
                    ]);

                    return redirect()->route('front.allProducts')->with('success', 'Payment successful! Your order has been completed.');
                }
            }

            return redirect()->route('front.allProducts')->with('error', 'Payment failed.');
        } catch (\Exception $e) {
            return redirect()->route('front.allProducts')->with('error', 'PayPal Capture Error: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('front.allProducts')->with('error', 'Payment was canceled.');
    }


    public function stripe($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        if ($order->items->isEmpty()) {
            return redirect()->route('front.allProducts')->with('error', 'Order has no items.');
        }

        if ($order->total <= 0) {
            return redirect()->route('front.allProducts')->with('error', 'Order total amount is invalid: ' . $order->total);
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => intval($item->product->sale_price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.success', $order->id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel'),
            'metadata' => [
                'order_id' => $order->id
            ]
        ]);
        return redirect($session->url);
    }

    public function stripeSuccess(Request $request, $orderId)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = StripeSession::retrieve($request->get('session_id'));
        if ($session && $session->payment_status === 'paid') {
            $order = Order::findOrFail($orderId);
            $order->update([
                'status' => 1,
                'payment_method' => 'stripe',
            ]);
            return redirect()->route('front.allProducts')->with('success', 'Stripe payment successful! Your order has been completed.');
        }
        return redirect()->route('front.allProducts')->with('error', 'Stripe payment failed.');
    }

    public function stripeCancel()
    {
        return redirect()->route('front.allProducts')->with('error', 'Stripe payment was canceled.');
    }
}
