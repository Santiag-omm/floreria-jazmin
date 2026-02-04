<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);

        return view('shop.cart', compact('cart'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $quantity = (int) $request->input('quantity', 1);
        $discountPercent = Promotion::discountForProduct($product);
        $finalPrice = Promotion::applyDiscount((float) $product->price, $discountPercent);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
            $cart[$product->id]['price'] = $finalPrice;
            $cart[$product->id]['original_price'] = (float) $product->price;
            $cart[$product->id]['discount_percent'] = $discountPercent;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $finalPrice,
                'original_price' => (float) $product->price,
                'discount_percent' => $discountPercent,
                'quantity' => $quantity,
            ];
        }

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);
        foreach ($data['items'] as $productId => $item) {
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = (int) $item['quantity'];
            }
        }
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Carrito actualizado.');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');

        return back()->with('success', 'Carrito vacío.');
    }
}
