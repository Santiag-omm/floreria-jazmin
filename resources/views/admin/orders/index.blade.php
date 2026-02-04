@extends('layouts.app')

@section('content')
<div class="admin-tech">
    @include('partials.admin-tabs')
    <h3 class="fw-bold mb-3">Pedidos</h3>

    <div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->code }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>$ {{ number_format($order->total, 2) }}</td>
                        <td><span class="badge bg-secondary">{{ $order->status }}</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.show', $order) }}">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Sin pedidos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <div class="mt-3">{{ $orders->links() }}</div>
</div>
@endsection
