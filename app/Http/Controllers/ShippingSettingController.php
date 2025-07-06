<?php

namespace App\Http\Controllers;

use App\Models\ShippingSetting;
use Illuminate\Http\Request;

class ShippingSettingController extends Controller
{
    public function index()
    {
        $shippingSetting = ShippingSetting::first();
        return view('shipping_settings.index', compact('shippingSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shipping_cost' => 'required|numeric|min:0',
        ]);

        $shippingSetting = ShippingSetting::first();
        if (!$shippingSetting) {
            $shippingSetting = new ShippingSetting();
        }

        $shippingSetting->shipping_cost = $request->shipping_cost;
        $shippingSetting->save();

        return redirect()->back()->with('success', 'Shipping cost updated successfully.');
    }
} 