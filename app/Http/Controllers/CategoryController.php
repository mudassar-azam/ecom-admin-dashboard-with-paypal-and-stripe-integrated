<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'user_id' => 1,
            'name' => $request->name,
            'slug' => $request->slug ?? Str::slug($request->name),
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $destinationPath = public_path('assets/categories');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $originalName = $image->getClientOriginalName();
            $imageName = time() . '_' . uniqid() . '_' . $originalName;
            $image->move($destinationPath, $imageName);
            $data['image'] = 'assets/categories/' . $imageName;
        }

        Category::create($data);

        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => $request->slug,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
        ];

        if ($request->hasFile('image')) {
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }
            
            $image = $request->file('image');
            $destinationPath = public_path('assets/categories');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $originalName = $image->getClientOriginalName();
            $imageName = time() . '_' . uniqid() . '_' . $originalName;
            $image->move($destinationPath, $imageName);
            $data['image'] = 'assets/categories/' . $imageName;
        }

        $category->update($data);

        notify()->success('Category updated successfully.');
        return redirect()->route('categories.index');
    }


    public function destroy(Category $category)
    {
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }
        
        $category->delete();

        notify()->success('Category deleted successfully.');
        return redirect()->route('categories.index');
    }
}
