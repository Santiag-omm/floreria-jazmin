<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ShopController::class, 'index'])->middleware('cache.response:60')->name('home');
Route::get('/producto/{product:slug}', [ShopController::class, 'show'])->middleware('cache.response:60')->name('product.show');
Route::get('/categoria/{category:slug}', [ShopController::class, 'category'])->middleware('cache.response:60')->name('category.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/password/forgot', [AuthController::class, 'showForgot'])->name('password.request');
Route::post('/password/forgot', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showReset'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');

Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/carrito/actualizar', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/eliminar/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrito/limpiar', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/mis-pedidos', [OrderController::class, 'myOrders'])->name('orders.mine');
    Route::get('/pedido/{order}/gracias', [OrderController::class, 'thankYou'])->name('orders.thankyou');
});

Route::middleware(['auth', 'role:admin,empleado'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permission:manage_categories')->group(function () {
        Route::get('/categorias', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categorias/crear', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categorias', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categorias/{category}/editar', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categorias/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::middleware('permission:manage_products')->group(function () {
        Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
        Route::get('/productos/crear', [ProductController::class, 'create'])->name('products.create');
        Route::post('/productos', [ProductController::class, 'store'])->name('products.store');
        Route::get('/productos/{product}/editar', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/productos/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/productos/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    Route::middleware('permission:manage_promotions')->group(function () {
        Route::get('/promociones', [PromotionController::class, 'index'])->name('promotions.index');
        Route::get('/promociones/crear', [PromotionController::class, 'create'])->name('promotions.create');
        Route::post('/promociones', [PromotionController::class, 'store'])->name('promotions.store');
        Route::get('/promociones/{promotion}/editar', [PromotionController::class, 'edit'])->name('promotions.edit');
        Route::put('/promociones/{promotion}', [PromotionController::class, 'update'])->name('promotions.update');
        Route::delete('/promociones/{promotion}', [PromotionController::class, 'destroy'])->name('promotions.destroy');
    });

    Route::middleware('permission:manage_orders')->group(function () {
        Route::get('/pedidos', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/pedidos/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/pedidos/{order}/estado', [OrderController::class, 'updateStatus'])->name('orders.status');
    });
});
