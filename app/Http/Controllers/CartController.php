<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store($productId)
    {
        Cart::firstOrCreate([
            'user_id' => 1,
            'product_id' => $productId
        ]);

        return redirect()->back()->with('success', 'Added to cart');
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        if ($cart->user_id === Auth::id()) {
            $cart->delete();
        }

        return redirect()->back()->with('success', 'Removed from cart');
    }
}
