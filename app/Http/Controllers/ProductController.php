<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'category', 'variations'])->latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        $variations = Variation::all();
        $attributes = Attribute::all();
        $currency = Currency::where('is_default' , 1)->first();
        return view('products.create', compact('brands', 'categories', 'variations', 'attributes','currency'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'quantity' => 'nullable|integer',
            'stock_status' => 'nullable|in:in_stock,out_of_stock',
            'video_url' => 'nullable|url',
            'weight' => 'nullable|numeric',
            'dimensions' => 'nullable|string',
            'tags' => 'nullable|string',
            'features' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_canonical' => 'nullable|string|max:255',
            'cost_price' => 'nullable|numeric',
            'shipping_class' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive,draft',
            'average_rating' => 'nullable|numeric|between:0,5',
            'published_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);



        $mainImageName = null;
        if ($request->hasFile('main_image')) {
            $image = $request->file('main_image');
            $destinationPath = public_path('assets/products');
            $originalName = $image->getClientOriginalName();
            $storedName = time() . '_' . uniqid() . '_' . $originalName;
            $image->move($destinationPath, $storedName);
            $mainImageName = 'assets/products/' . $storedName;
        }

        $galleryNames = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $gallery) {
                $original = $gallery->getClientOriginalName();
                $storedName = time() . '_' . uniqid() . '_' . $original;
                $gallery->move(public_path('assets/products'), $storedName);
                $galleryNames[] = 'assets/products/' . $storedName;
            }
        }


        $product = Product::create(array_merge(
            $request->except(['main_image', 'gallery_images', 'variations']),
            [
                'user_id' => Auth::id(),
                'slug' => $request->slug ?? Str::slug($request->name),
                'main_image' => $mainImageName,
                'gallery_images' => json_encode($galleryNames),
            ]
        ));

        if ($request->has('variations')) {
            foreach ($request->variations as $variation) {
                $variationGallery = [];

                if (isset($variation['gallery_images']) && is_array($variation['gallery_images'])) {
                    foreach ($variation['gallery_images'] as $image) {
                        if ($image && $image->isValid()) {
                            $original = $image->getClientOriginalName();
                            $storedName = time() . '_' . uniqid() . '_' . $original;
                            $image->move(public_path('assets/products'), $storedName);
                            $variationGallery[] = 'assets/products/' . $storedName;
                        }
                    }
                }

                Variation::create([
                    'product_id' => $product->id,
                    'attribute_id' => $variation['attribute_id'] ?? null,
                    'attribute_value_id' => $variation['value_id'] ?? null,
                    'name' => $variation['name'] ?? null,
                    'sku' => $variation['sku'] ?? null,
                    'description' => $variation['description'] ?? null,
                    'sale_price' => $variation['sale_price'] ?? null,
                    'stock_status' => $variation['stock_status'] ?? 'in_stock',
                    'gallery_images' => !empty($variationGallery) ? json_encode($variationGallery) : null,
                ]);
            }
        }


        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $brands = Brand::all();
        $categories = Category::all();
        $variations = Variation::all();
        $product->load('variations');
        $currency = Currency::where('is_default' , 1)->first();
        return view('products.edit', compact('product', 'brands', 'categories', 'variations','currency'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'sku' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'currency' => 'nullable|string|max:10',
            'quantity' => 'nullable|integer',
            'stock_status' => 'nullable|in:in_stock,out_of_stock',
            'video_url' => 'nullable|url',
            'weight' => 'nullable|numeric',
            'dimensions' => 'nullable|string',
            'tags' => 'nullable|string',
            'features' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_canonical' => 'nullable|string|max:255',
            'cost_price' => 'nullable|numeric',
            'shipping_class' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive,draft',
            'average_rating' => 'nullable|numeric|between:0,5',
            'published_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);



        if ($request->hasFile('main_image')) {
            if ($product->main_image && file_exists(public_path($product->main_image))) {
                unlink(public_path($product->main_image));
            }

            $image = $request->file('main_image');
            $storedName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
            $image->move(public_path('assets/products'), $storedName);
            $product->main_image = 'assets/products/' . $storedName;
        }

        if ($request->hasFile('gallery_images')) {
            if ($product->gallery_images) {
                foreach (json_decode($product->gallery_images, true) as $oldImage) {
                    if (file_exists(public_path($oldImage))) {
                        unlink(public_path($oldImage));
                    }
                }
            }

            $galleryNames = [];
            foreach ($request->file('gallery_images') as $gallery) {
                $storedName = time() . '_' . uniqid() . '_' . $gallery->getClientOriginalName();
                $gallery->move(public_path('assets/products'), $storedName);
                $galleryNames[] = 'assets/products/' . $storedName;
            }

            $product->gallery_images = json_encode($galleryNames);
        }


        $product->update(array_merge(
            $request->except(['main_image', 'gallery_images', 'variations']),
            [
                'slug' => $request->slug ?? Str::slug($request->name),
            ]
        ));

        if ($request->has('variations')) {
            $product->variations()->delete();
            
            foreach ($request->variations as $variation) {
                $variationGallery = [];

                if (isset($variation['gallery_images']) && is_array($variation['gallery_images'])) {
                    foreach ($variation['gallery_images'] as $image) {
                        if ($image && $image->isValid()) {
                            $original = $image->getClientOriginalName();
                            $storedName = time() . '_' . uniqid() . '_' . $original;
                            $image->move(public_path('assets/products'), $storedName);
                            $variationGallery[] = 'assets/products/' . $storedName;
                        }
                    }
                }

                Variation::create([
                    'product_id' => $product->id,
                    'attribute_id' => $variation['attribute_id'] ?? null,
                    'attribute_value_id' => $variation['value_id'] ?? null,
                    'name' => $variation['name'] ?? null,
                    'sku' => $variation['sku'] ?? null,
                    'description' => $variation['description'] ?? null,
                    'sale_price' => $variation['sale_price'] ?? null,
                    'stock_status' => $variation['stock_status'] ?? 'in_stock',
                    'gallery_images' => !empty($variationGallery) ? json_encode($variationGallery) : null,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image && file_exists(public_path('assets/products/' . $product->main_image))) {
            unlink(public_path('assets/products/' . $product->main_image));
        }
        if ($product->gallery_images) {
            foreach (json_decode($product->gallery_images) as $img) {
                if (file_exists(public_path('assets/products/' . $img))) {
                    unlink(public_path('assets/products/' . $img));
                }
            }
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted');
    }

    public function showAll()
    {
        $products = Product::all();
        return view('products.allProducts', compact('products'));
    }

    public function showSingleProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('products.product_detail', compact('product'));
    }

    public function getAttributeValues($ids)
    {
        $idArray = explode(',', $ids);

        $values = AttributeValue::whereIn('attribute_id', $idArray)
            ->get(['id', 'name', 'attribute_id']);

        return response()->json($values);
    }
}
