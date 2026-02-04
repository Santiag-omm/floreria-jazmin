@extends('layouts.app')

@section('content')
<h3 class="fw-bold mb-3">Mis pedidos</h3>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->code }}</td>
                        <td>{{ optional($order->placed_at)->format('d/m/Y H:i') }}</td>
                        <td>$ {{ number_format($order->total, 2) }}</td>
                        <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Sin pedidos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection
