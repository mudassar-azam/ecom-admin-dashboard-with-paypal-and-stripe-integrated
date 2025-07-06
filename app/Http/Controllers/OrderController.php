<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function paypalOrders(){
        $orders = Order::with(['items.product'])->where('payment_method', 'paypal')->orderBy('created_at', 'desc')->get();
        return view('order.paypal_orders', compact('orders'));
    }

    public function stripeOrders(){
        $orders = Order::with(['items.product'])->where('payment_method', 'stripe')->orderBy('created_at', 'desc')->get();
        return view('order.stripe_orders', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|between:1,5',
            'status_notes' => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        
        // Get status labels
        $statusLabels = [
            1 => 'Pending',
            2 => 'Delivered', 
            3 => 'Cancelled',
            4 => 'Processing',
            5 => 'Shipped'
        ];

        $oldStatus = $statusLabels[$order->status] ?? 'Unknown';
        $newStatus = $statusLabels[$request->status] ?? 'Unknown';

        $order->update([
            'status' => $request->status,
            'status_notes' => $request->status_notes,
        ]);

        // Determine redirect based on payment method
        if ($order->payment_method === 'paypal') {
            return redirect()->route('orders.paypal')->with('success', "Order status updated from {$oldStatus} to {$newStatus} successfully!");
        } else {
            return redirect()->route('orders.stripe')->with('success', "Order status updated from {$oldStatus} to {$newStatus} successfully!");
        }
    }
}
