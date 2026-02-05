<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $categories = Cache::remember('shop.categories', now()->addMinutes(10), function () {
            return Category::where('is_active', true)->orderBy('name')->get();
        });

        $promotions = Cache::remember('shop.promotions.latest', now()->addMinutes(10), function () {
            return Promotion::where('is_active', true)->latest()->limit(3)->get();
        });

        $activePromotions = Cache::remember('shop.promotions.active', now()->addMinutes(5), function () {
            return Promotion::active()->get();
        });

        $page = request()->integer('page', 1);
        $products = Cache::remember("shop.products.page.{$page}", now()->addMinutes(5), function () {
            return Product::with(['images' => function ($query) {
                    $query->where('is_cover', true);
                }])
                ->where('is_active', true)
                ->latest()
                ->simplePaginate(12);
        });

        return view('shop.index', compact('categories', 'promotions', 'activePromotions', 'products'));
    }

    public function show(Product $product): View
    {
        $product->load('images', 'category');
        $activePromotions = Cache::remember('shop.promotions.active', now()->addMinutes(5), function () {
            return Promotion::active()->get();
        });
        $related = Cache::remember("shop.related.{$product->id}", now()->addMinutes(5), function () use ($product) {
            return Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->limit(4)
                ->get();
        });

        return view('shop.show', compact('product', 'related', 'activePromotions'));
    }

    public function category(Category $category): View
    {
        $categories = Cache::remember('shop.categories', now()->addMinutes(10), function () {
            return Category::where('is_active', true)->orderBy('name')->get();
        });
        $activePromotions = Cache::remember('shop.promotions.active', now()->addMinutes(5), function () {
            return Promotion::active()->get();
        });
        $page = request()->integer('page', 1);
        $products = Cache::remember("shop.category.{$category->id}.page.{$page}", now()->addMinutes(5), function () use ($category) {
            return $category->products()
                ->with(['images' => function ($query) {
                    $query->where('is_cover', true);
                }])
                ->where('is_active', true)
                ->simplePaginate(12);
        });

        return view('shop.category', compact('category', 'categories', 'products', 'activePromotions'));
    }
}
