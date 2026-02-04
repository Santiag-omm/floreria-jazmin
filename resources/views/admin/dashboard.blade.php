@extends('layouts.app')

@section('content')
<div class="admin-tech">
    @include('partials.admin-tabs')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Menú de configuración</h2>
            <p class="text-muted">Panel técnico y métricas clave</p>
        </div>
        <div class="text-muted">{{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted">Ventas totales</div>
                <div class="fs-4 fw-bold">$ {{ number_format($totalSales, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted">Pedidos activos</div>
                <div class="fs-4 fw-bold">{{ $activeOrders }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted">Pedidos totales</div>
                <div class="fs-4 fw-bold">{{ $totalOrders }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="text-muted">Productos</div>
                <div class="fs-4 fw-bold">{{ $productsCount }}</div>
            </div>
        </div>
    </div>
</div>
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">Productos más vendidos</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($topProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $product->product_name }}
                                <span class="badge bg-primary rounded-pill">{{ $product->total_qty }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Sin datos todavía</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">Pedidos recientes</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($recentOrders as $order)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $order->code }}</div>
                                    <small class="text-muted">{{ $order->customer_name }}</small>
                                </div>
                                <span class="badge bg-secondary">{{ $order->status }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Sin pedidos todavía</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
