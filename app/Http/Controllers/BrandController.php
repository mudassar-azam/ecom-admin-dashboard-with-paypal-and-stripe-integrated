<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('brands.index', compact('brands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $mainImageName = null;
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $destinationPath = public_path('assets/brands');
            $originalName = $image->getClientOriginalName();
            $uniqueName = time() . '_' . uniqid() . '_' . $originalName;
            $image->move($destinationPath, $uniqueName);
            $mainImageName = 'assets/products/' . $uniqueName;
        }

        Brand::create([
            'user_id' => 1,
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'logo' => $mainImageName,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return redirect()->route('brands.index');
    }


    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'slug' => 'required|string|max:255|unique:brands,slug,' . $brand->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $logoName = $brand->logo;

        if ($request->hasFile('logo')) {
            $oldPath = public_path('assets/brands/' . $brand->logo);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $image = $request->file('logo');
            $destinationPath = public_path('assets/brands');
            $originalName = $image->getClientOriginalName();
            $logoName = time() . '_' . uniqid() . '_' . $originalName;
            $image->move($destinationPath, $logoName);
            $logoName = 'assets/products/' . $logoName;
        }

        $brand->update([
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'logo' => $logoName,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ]);

        return redirect()->route('brands.index');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->logo) {
            $logoPath = public_path('assets/brands/' . $brand->logo);
            if (File::exists($logoPath)) {
                File::delete($logoPath);
            }
        }
        $brand->delete();
        return redirect()->route('brands.index');
    }
}
