<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Bisnis;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $bisnisList = auth()->user()->bisnis()->get();
        $categories = ProductCategory::with('bisnis')->where('bisnis_id', auth()->user()->bisnis->first()->id ?? null)->latest()->get();
        $products = Product::with(['bisnis', 'category'])->where('bisnis_id', auth()->user()->bisnis->first()->id ?? null)->latest()->get();
        return view('main.products.index', compact('products', 'bisnisList', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bisnis_id' => 'required|exists:bisnis,id',
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|string',
            'stock' => 'required|string',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name'] . '-' . uniqid());

        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
            $validated['images'] = $imagePaths;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'bisnis_id' => 'required|exists:bisnis,id',
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|string',
            'stock' => 'required|string',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name'] . '-' . uniqid());

        if ($request->hasFile('images')) {
            // hapus gambar lama jika ada
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
            $validated['images'] = $imagePaths;
        }


        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
