@extends('layouts.app')

@section('content')
<h3 class="fw-bold mb-3">Carrito</h3>

@if(empty($cart))
    <div class="alert alert-info">Tu carrito está vacío.</div>
    <a class="btn btn-primary" href="{{ route('home') }}">Ir al catálogo</a>
@else
    <form id="update-cart" method="POST" action="{{ route('cart.update') }}">
        @csrf
    </form>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>
                                $ {{ number_format($item['price'], 2) }}
                                @if(!empty($item['original_price']) && $item['original_price'] > $item['price'])
                                    <span class="price-old">$ {{ number_format($item['original_price'], 2) }}</span>
                                @endif
                            </td>
                            <td style="max-width:120px;">
                                <input type="number" name="items[{{ $item['product_id'] }}][quantity]" class="form-control" value="{{ $item['quantity'] }}" min="1" form="update-cart">
                            </td>
                            <td>$ {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td>
                                <form id="remove-{{ $item['product_id'] }}" method="POST" action="{{ route('cart.remove', $item['product_id']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-3">
        <button class="btn btn-outline-secondary" form="update-cart">Actualizar carrito</button>
        <div class="text-end">
            <a class="btn btn-outline-danger" href="{{ route('cart.clear') }}" onclick="event.preventDefault(); document.getElementById('clear-cart').submit();">Vaciar</a>
            <a class="btn btn-primary" href="{{ route('checkout.index') }}">Ir a pagar</a>
        </div>
    </div>
    <form id="clear-cart" method="POST" action="{{ route('cart.clear') }}">
        @csrf
    </form>
@endif
@endsection
