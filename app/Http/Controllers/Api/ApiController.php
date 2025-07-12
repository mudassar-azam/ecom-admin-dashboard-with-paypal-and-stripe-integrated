<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Currency;
use App\Models\ShippingSetting;
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
        $category = is_numeric($id)
            ? Category::find($id)
            : Category::where('slug', $id)->first();
    
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
        $products = $products->map(function ($product) {
            $product->main_image = $product->main_image ? asset($product->main_image) : null;
            $gallery = $product->gallery_images;
            if (is_string($gallery)) {
                $gallery = json_decode($gallery, true);
            }
            if (!is_array($gallery)) {
                $gallery = [];
            }
            $product->gallery_images = array_map(function ($img) { return asset($img); }, $gallery);
            if ($product->brand && isset($product->brand->logo) && $product->brand->logo) {
                $product->brand->logo = asset($product->brand->logo);
            }
            if ($product->category && isset($product->category->image) && $product->category->image) {
                $product->category->image = asset($product->category->image);
            }
            if ($product->variations) {
                foreach ($product->variations as $variation) {
                    $vgallery = $variation->gallery_images;
                    if (is_string($vgallery)) {
                        $vgallery = json_decode($vgallery, true);
                    }
                    if (!is_array($vgallery)) {
                        $vgallery = [];
                    }
                    $variation->gallery_images = array_map(function ($img) { return asset($img); }, $vgallery);
                }
            }
            return $product;
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Products fetched successfully',
            'data' => $products
        ]);
    }

    public function getProduct($id): JsonResponse
    {
        $product = is_numeric($id)
            ? Product::with(['brand', 'category', 'variations.attribute', 'variations.value'])->find($id)
            : Product::with(['brand', 'category', 'variations.attribute', 'variations.value'])->where('slug', $id)->first();
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
        $product->main_image = $product->main_image ? asset($product->main_image) : null;
        $gallery = $product->gallery_images;
        if (is_string($gallery)) {
            $gallery = json_decode($gallery, true);
        }
        if (!is_array($gallery)) {
            $gallery = [];
        }
        $product->gallery_images = array_map(function ($img) { return asset($img); }, $gallery);
        if ($product->brand && isset($product->brand->logo) && $product->brand->logo) {
            $product->brand->logo = asset($product->brand->logo);
        }
        if ($product->category && isset($product->category->image) && $product->category->image) {
            $product->category->image = asset($product->category->image);
        }
        if ($product->variations) {
            foreach ($product->variations as $variation) {
                $vgallery = $variation->gallery_images;
                if (is_string($vgallery)) {
                    $vgallery = json_decode($vgallery, true);
                }
                if (!is_array($vgallery)) {
                    $vgallery = [];
                }
                $variation->gallery_images = array_map(function ($img) { return asset($img); }, $vgallery);
            }
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
        $products = $products->map(function ($product) {
            $product->main_image = $product->main_image ? asset($product->main_image) : null;
            $gallery = $product->gallery_images;
            if (is_string($gallery)) {
                $gallery = json_decode($gallery, true);
            }
            if (!is_array($gallery)) {
                $gallery = [];
            }
            $product->gallery_images = array_map(function ($img) { return asset($img); }, $gallery);
            if ($product->brand && isset($product->brand->logo) && $product->brand->logo) {
                $product->brand->logo = asset($product->brand->logo);
            }
            if ($product->category && isset($product->category->image) && $product->category->image) {
                $product->category->image = asset($product->category->image);
            }
            if ($product->variations) {
                foreach ($product->variations as $variation) {
                    $vgallery = $variation->gallery_images;
                    if (is_string($vgallery)) {
                        $vgallery = json_decode($vgallery, true);
                    }
                    if (!is_array($vgallery)) {
                        $vgallery = [];
                    }
                    $variation->gallery_images = array_map(function ($img) { return asset($img); }, $vgallery);
                }
            }
            return $product;
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Products searched successfully',
            'data' => $products
        ]);
    }
    
    public function getDefaultCurrency(): JsonResponse
    {
        $currency = Currency::where('is_default', 1)->first();
    
        if (!$currency) {
            return response()->json([
                'status' => 'error',
                'message' => 'Default currency not found'
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Default currency fetched successfully',
            'data' => $currency
        ]);
    }
    
    public function getShippingSettings(): JsonResponse
    {
        $setting = ShippingSetting::first();
    
        if (!$setting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shipping settings not found'
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Shipping settings fetched successfully',
            'data' => $setting
        ]);
    }
    
    public function getProductsByCategorySlug($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found'
            ], 404);
        }
        $products = Product::with(['brand', 'category', 'variations.attribute', 'variations.value'])
            ->where('category_id', $category->id)
            ->get();
        $products = $products->map(function ($product) {
            $product->main_image = $product->main_image ? asset($product->main_image) : null;
            $gallery = $product->gallery_images;
            if (is_string($gallery)) {
                $gallery = json_decode($gallery, true);
            }
            if (!is_array($gallery)) {
                $gallery = [];
            }
            $product->gallery_images = array_map(function ($img) { return asset($img); }, $gallery);
            if ($product->brand && isset($product->brand->logo) && $product->brand->logo) {
                $product->brand->logo = asset($product->brand->logo);
            }
            if ($product->category && isset($product->category->image) && $product->category->image) {
                $product->category->image = asset($product->category->image);
            }
            if ($product->variations) {
                foreach ($product->variations as $variation) {
                    $vgallery = $variation->gallery_images;
                    if (is_string($vgallery)) {
                        $vgallery = json_decode($vgallery, true);
                    }
                    if (!is_array($vgallery)) {
                        $vgallery = [];
                    }
                    $variation->gallery_images = array_map(function ($img) { return asset($img); }, $vgallery);
                }
            }
            return $product;
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Products fetched successfully',
            'data' => $products
        ]);
    }
    
    public function getCartItemsByUserId($userId)
    {
        $cartItems = Cart::with(['product.brand', 'product.category', 'product.variations.attribute', 'product.variations.value'])
            ->where('user_id', $userId)
            ->get();
        $cartItems = $cartItems->map(function ($item) {
            if ($item->product) {
                $item->product->main_image = $item->product->main_image ? asset($item->product->main_image) : null;
                $gallery = $item->product->gallery_images;
                if (is_string($gallery)) {
                    $gallery = json_decode($gallery, true);
                }
                if (!is_array($gallery)) {
                    $gallery = [];
                }
                $item->product->gallery_images = array_map(function ($img) { return asset($img); }, $gallery);
                if ($item->product->brand && isset($item->product->brand->logo) && $item->product->brand->logo) {
                    $item->product->brand->logo = asset($item->product->brand->logo);
                }
                if ($item->product->category && isset($item->product->category->image) && $item->product->category->image) {
                    $item->product->category->image = asset($item->product->category->image);
                }
                if ($item->product->variations) {
                    foreach ($item->product->variations as $variation) {
                        $vgallery = $variation->gallery_images;
                        if (is_string($vgallery)) {
                            $vgallery = json_decode($vgallery, true);
                        }
                        if (!is_array($vgallery)) {
                            $vgallery = [];
                        }
                        $variation->gallery_images = array_map(function ($img) { return asset($img); }, $vgallery);
                    }
                }
            }
            return $item;
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Cart items fetched successfully',
            'data' => $cartItems
        ]);
    }
}
