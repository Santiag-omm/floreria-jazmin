@extends('layouts.app')

@section('content')
<div class="admin-tech">
    @include('partials.admin-tabs')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Categorías</h3>
        <a class="btn btn-primary" href="{{ route('admin.categories.create') }}">Nueva categoría</a>
    </div>

    <div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>
                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.edit', $category) }}">Editar</a>
                            <form class="d-inline" method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">Sin categorías</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <div class="mt-3">{{ $categories->links() }}</div>
</div>
@endsection
