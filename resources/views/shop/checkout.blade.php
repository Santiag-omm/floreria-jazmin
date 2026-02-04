@extends('layouts.app')

@section('content')
<h3 class="fw-bold mb-3">Checkout</h3>

<div class="row">
    <div class="col-lg-6">
        <form method="POST" action="{{ route('checkout.store') }}" class="card p-4 shadow-sm border-0">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="customer_name" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="text" name="customer_phone" class="form-control" value="{{ auth()->user()->phone }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Dirección</label>
                <input type="text" name="customer_address" class="form-control" value="{{ auth()->user()->address }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Notas</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
            <button class="btn btn-primary" data-flower-burst>Confirmar pedido</button>
        </form>
    </div>
    <div class="col-lg-6">
        <div class="card p-4 shadow-sm border-0">
            <h5 class="fw-bold">Resumen</h5>
            <ul class="list-group list-group-flush">
                @foreach($cart as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                        <span>$ {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </li>
                @endforeach
            </ul>
            @php
                $subtotal = collect($cart)->sum(fn ($item) => ($item['original_price'] ?? $item['price']) * $item['quantity']);
                $discountTotal = collect($cart)->sum(fn ($item) => (($item['original_price'] ?? $item['price']) - $item['price']) * $item['quantity']);
                $total = $subtotal - $discountTotal;
            @endphp
            <div class="text-end mt-3">
                <div>Subtotal: $ {{ number_format($subtotal, 2) }}</div>
                @if($discountTotal > 0)
                    <div class="text-success">Descuento: -$ {{ number_format($discountTotal, 2) }}</div>
                @endif
                <div class="fw-bold">Total: $ {{ number_format($total, 2) }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
