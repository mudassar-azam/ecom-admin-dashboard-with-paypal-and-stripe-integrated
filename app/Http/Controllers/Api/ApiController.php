<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function test(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'API is working!',
            'data' => [
                'user' => $request->user()
            ]
        ]);
    }

    public function getAllCategories(): JsonResponse
    {
        $categories = Category::all();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Categories fetched successfully',
            'data' => $categories
        ]);
    }

    public function getCategory($id): JsonResponse
    {
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Category fetched successfully',
            'data' => $category
        ]);
    }

    public function getAllProducts(): JsonResponse
    {
        $products = Product::with(['brand', 'category', 'variations.attribute', 'variations.value'])->get();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Products fetched successfully',
            'data' => $products
        ]);
    }

    public function getProduct($id): JsonResponse
    {
        $product = Product::with(['brand', 'category', 'variations.attribute', 'variations.value'])->find($id);
        
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => 'Product fetched successfully',
            'data' => $product
        ]);
    }

    public function addToCart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to cart successfully',
            'data' => $cart
        ]);
    }

    public function removeFromCart(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required'
        ]);

        $cart = Cart::find($request->id);

        if (!$cart) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found in cart'
            ], 404);
        }

        $cart->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product removed from cart successfully'
        ]);
    }

    public function searchProducts(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string',
            'brand' => 'nullable|string',
            'category' => 'nullable|string'
        ]);

        $query = Product::with(['brand', 'category', 'variations.attribute', 'variations.value']);

        if ($request->has('name') && $request->name) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->has('brand') && $request->brand) {
            $query->whereHas('brand', function ($brandQuery) use ($request) {
                $brandQuery->where('name', 'LIKE', '%' . $request->brand . '%');
            });
        }

        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function ($categoryQuery) use ($request) {
                $categoryQuery->where('name', 'LIKE', '%' . $request->category . '%');
            });
        }

        $products = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Products searched successfully',
            'data' => $products
        ]);
    }
}
