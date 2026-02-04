<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $promotions = Promotion::where('is_active', true)->latest()->limit(3)->get();
        $activePromotions = Promotion::active()->get();
        $products = Product::with('images')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('shop.index', compact('categories', 'promotions', 'activePromotions', 'products'));
    }

    public function show(Product $product): View
    {
        $product->load('images', 'category');
        $activePromotions = Promotion::active()->get();
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'related', 'activePromotions'));
    }

    public function category(Category $category): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $activePromotions = Promotion::active()->get();
        $products = $category->products()->where('is_active', true)->paginate(12);

        return view('shop.category', compact('category', 'categories', 'products', 'activePromotions'));
    }
}
