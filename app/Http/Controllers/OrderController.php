<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::latest()->paginate(12);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string'],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Estado actualizado.');
    }

    public function myOrders(Request $request): View
    {
        $orders = Order::where('user_id', $request->user()->id)->latest()->paginate(10);

        return view('shop.orders', compact('orders'));
    }

    public function thankYou(Order $order, Request $request): View
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403, 'No autorizado.');
        }

        return view('shop.thankyou', compact('order'));
    }
}
