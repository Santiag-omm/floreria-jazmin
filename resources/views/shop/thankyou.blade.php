@extends('layouts.app')

@section('content')
<div class="text-center">
    <h2 class="fw-bold">¡Gracias por tu compra!</h2>
    <p class="text-muted">Tu pedido {{ $order->code }} fue recibido.</p>
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 420px;">
        <div class="card-body">
            <p><strong>Estado:</strong> {{ $order->status }}</p>
            <p><strong>Total:</strong> $ {{ number_format($order->total, 2) }}</p>
            <p><strong>Entrega:</strong> {{ $order->customer_address }}</p>
        </div>
    </div>
    <a class="btn btn-primary mt-3" href="{{ route('home') }}">Seguir comprando</a>
</div>
@endsection
