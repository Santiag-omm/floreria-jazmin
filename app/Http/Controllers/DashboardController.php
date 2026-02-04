<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSales = Order::sum('total');
        $activeOrders = Order::where('status', '!=', Order::STATUS_DELIVERED)->count();
        $totalOrders = Order::count();
        $productsCount = Product::count();

        $topProducts = OrderItem::selectRaw('product_id, product_name, SUM(quantity) as total_qty')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $recentOrders = Order::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'activeOrders',
            'totalOrders',
            'productsCount',
            'topProducts',
            'recentOrders'
        ));
    }
}
