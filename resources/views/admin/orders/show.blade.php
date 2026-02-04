@extends('layouts.app')

@section('content')
@include('partials.admin-tabs')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="fw-bold">Pedido {{ $order->code }}</h3>
        <p class="text-muted">{{ $order->customer_name }} - {{ $order->customer_address }}</p>
    </div>
    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="d-flex gap-2">
        @csrf
        @method('PATCH')
        <select name="status" class="form-select">
            @foreach([\App\Models\Order::STATUS_PENDING, \App\Models\Order::STATUS_PREPARATION, \App\Models\Order::STATUS_ON_ROUTE, \App\Models\Order::STATUS_DELIVERED] as $status)
                <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary">Actualizar</button>
    </form>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-bold">Detalle</h6>
        <ul class="list-group list-group-flush mb-3">
            @foreach($order->items as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $item->product_name }} x {{ $item->quantity }}</span>
                    <span>$ {{ number_format($item->total, 2) }}</span>
                </li>
            @endforeach
        </ul>
        <div class="text-end">
            <div>Subtotal: $ {{ number_format($order->subtotal, 2) }}</div>
            <div>Descuento: $ {{ number_format($order->discount_total, 2) }}</div>
            <div class="fw-bold">Total: $ {{ number_format($order->total, 2) }}</div>
        </div>
    </div>
</div>
@endsection
