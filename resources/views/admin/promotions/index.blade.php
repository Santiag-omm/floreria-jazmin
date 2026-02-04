@extends('layouts.app')

@section('content')
<div class="admin-tech">
    @include('partials.admin-tabs')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Promociones</h3>
        <a class="btn btn-primary" href="{{ route('admin.promotions.create') }}">Nueva promoción</a>
    </div>

    <div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Descuento</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $promotion)
                    <tr>
                        <td>{{ $promotion->title }}</td>
                        <td>{{ $promotion->discount_percent }}%</td>
                        <td>{{ $promotion->target_type === 'premium' ? 'Premium' : 'General' }}</td>
                        <td>
                            <span class="badge {{ $promotion->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $promotion->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.promotions.edit', $promotion) }}">Editar</a>
                            <form class="d-inline" method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Sin promociones</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <div class="mt-3">{{ $promotions->links() }}</div>
</div>
@endsection
