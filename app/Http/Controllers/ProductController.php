<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_premium' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,webp,avif,heic,heif', 'max:4096'],
        ]);

        $product = Product::create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'is_active' => $request->boolean('is_active'),
            'is_premium' => $request->boolean('is_premium'),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_cover' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto creado.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_premium' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['sometimes', 'file', 'mimes:jpg,jpeg,png,webp,avif,heic,heif', 'max:4096'],
            'delete_images' => ['array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],
            'cover_image_id' => ['nullable', 'integer', 'exists:product_images,id'],
        ]);

        $product->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'is_active' => $request->boolean('is_active'),
            'is_premium' => $request->boolean('is_premium'),
        ]);

        if (!empty($data['delete_images'])) {
            $images = ProductImage::whereIn('id', $data['delete_images'])->get();
            foreach ($images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_cover' => false,
                ]);
            }
        }

        if (!empty($data['cover_image_id'])) {
            $product->images()->update(['is_cover' => false]);
            $product->images()->where('id', $data['cover_image_id'])->update(['is_cover' => true]);
        } elseif ($product->images()->count() > 0 && !$product->images()->where('is_cover', true)->exists()) {
            $first = $product->images()->first();
            $first?->update(['is_cover' => true]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado.');
    }
}
