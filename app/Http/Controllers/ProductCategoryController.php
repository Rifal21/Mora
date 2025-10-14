<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bisnisList = auth()->user()->bisnis()->get();
        $categories = ProductCategory::with('bisnis')->where('bisnis_id', auth()->user()->bisnis->first()->id ?? null)->latest()->get();
        return view('main.productCategory.index', compact('categories', 'bisnisList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bisnis_id' => 'required|exists:bisnis,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('product_categories', 'public');
        }
        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(4);

        ProductCategory::create($validated);

        return redirect()->route('product-categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'bisnis_id' => 'required|exists:bisnis,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);
        $validated['slug'] = Str::slug($request->name) . '-' . Str::random(4);

        if ($request->hasFile('image')) {
            if ($productCategory->image && Storage::disk('public')->exists($productCategory->image)) {
                Storage::disk('public')->delete($productCategory->image);
            }
            $validated['image'] = $request->file('image')->store('product_categories', 'public');
        }

        $productCategory->update($validated);

        return redirect()->route('product-categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->image && Storage::disk('public')->exists($productCategory->image)) {
            Storage::disk('public')->delete($productCategory->image);
        }

        $productCategory->delete();

        return redirect()->route('product-categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
