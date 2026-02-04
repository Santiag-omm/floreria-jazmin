<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        return view('shop.checkout', compact('cart'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'customer_address' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $subtotal = collect($cart)->sum(fn ($item) => ($item['original_price'] ?? $item['price']) * $item['quantity']);
        $discountTotal = collect($cart)->sum(fn ($item) => (($item['original_price'] ?? $item['price']) - $item['price']) * $item['quantity']);
        $total = $subtotal - $discountTotal;

        $order = Order::create([
            'user_id' => $request->user()->id,
            'code' => 'FLR-' . Str::upper(Str::random(8)),
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'total' => $total,
            'status' => Order::STATUS_PENDING,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'] ?? null,
            'customer_address' => $data['customer_address'],
            'notes' => $data['notes'] ?? null,
            'placed_at' => now(),
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity'],
            ]);
        }

        $request->session()->forget('cart');

        return redirect()->route('orders.thankyou', $order);
    }
}
