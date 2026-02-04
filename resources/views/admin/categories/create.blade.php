@extends('layouts.app')

@section('content')
@include('partials.admin-tabs')
<h3 class="fw-bold mb-3">Nueva categoría</h3>
<form method="POST" action="{{ route('admin.categories.store') }}" class="card p-4 shadow-sm border-0">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    <div class="form-check mb-3">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
        <label class="form-check-label" for="is_active">Activa</label>
    </div>
    <button class="btn btn-primary">Guardar</button>
</form>
@endsection
