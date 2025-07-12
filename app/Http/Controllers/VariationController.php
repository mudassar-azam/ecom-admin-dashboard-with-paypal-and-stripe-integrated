<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Http\Request;

class VariationController extends Controller
{
    public function getAllVariations($id)
    {
        $product = Product::with('variations')->findOrFail($id);
        $variations = $product->variations;
        return view('variations.all', compact('variations'));
    }

    public function getAttributeValues(Request $request)
    {
        $attributeId = $request->input('attribute_id');

        $values = AttributeValue::where('attribute_id', $attributeId)->get();

        return response()->json($values);
    }

    public function index()
    {
        $attributes = Attribute::with('values')->get();
        return view('variations.index', compact('attributes'));
    }

    public function create()
    {
        return view('variations.create');
    }

    public function store(Request $request)
    {
        $attribute = Attribute::create([
            'name' => $request->input('name')
        ]);
        $values = $request->input('values', []);
        $images = $request->file('images', []);
        $colors = $request->input('colors', []);
        foreach ($values as $index => $valueName) {
            $imagePath = null;
            if (isset($images[$index]) && $images[$index]) {
                $image = $images[$index];
                $destinationPath = public_path('assets/attribute_values');
                $originalName = $image->getClientOriginalName();
                $storedName = time() . '_' . uniqid() . '_' . $originalName;
                $image->move($destinationPath, $storedName);
                $imagePath = 'assets/attribute_values/' . $storedName;
            }
            $color = $colors[$index] ?? null;
            if (trim($valueName) !== '') {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'name' => $valueName,
                    'image' => $imagePath,
                    'color' => $color,
                ]);
            }
        }
        return redirect()->route('variations.index')->with('success', 'Attribute and value created successfully.');
    }

    public function edit($id)
    {
        $attribute = Attribute::with('values')->findOrFail($id);

        return view('variations.edit', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'values' => 'required|array',
        ]);
        $attribute = Attribute::findOrFail($id);
        $attribute->update([
            'name' => $request->input('name'),
        ]);
        $values = $request->input('values');
        $valueIds = $request->input('value_ids', []);
        $images = $request->file('images', []);
        $colors = $request->input('colors', []);
        foreach ($values as $index => $valueName) {
            $valueId = $valueIds[$index] ?? null;
            $imagePath = null;
            if (isset($images[$index]) && $images[$index]) {
                $image = $images[$index];
                $destinationPath = public_path('assets/attribute_values');
                $originalName = $image->getClientOriginalName();
                $storedName = time() . '_' . uniqid() . '_' . $originalName;
                $image->move($destinationPath, $storedName);
                $imagePath = 'assets/attribute_values/' . $storedName;
            }
            $color = $colors[$index] ?? null;
            if ($valueId) {
                $existing = AttributeValue::find($valueId);
                if ($existing) {
                    if (trim($valueName) === '') {
                        $existing->delete(); 
                    } else {
                        $updateData = ['name' => $valueName];
                        if ($imagePath) {
                            $updateData['image'] = $imagePath;
                        }
                        if ($color) {
                            $updateData['color'] = $color;
                        }
                        $existing->update($updateData);
                    }
                }
            } else {
                if (trim($valueName) !== '') {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'name' => $valueName,
                        'image' => $imagePath,
                        'color' => $color,
                    ]);
                }
            }
        }
        return redirect()->route('variations.index')->with('success', 'Attribute and values updated successfully.');
    }


    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        AttributeValue::where('attribute_id', $attribute->id)->delete();
        $attribute->delete();

        return redirect()->route('variations.index')->with('success', 'Attribute and its values deleted successfully.');
    }
}
