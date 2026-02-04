@extends('layouts.app')

@section('content')
@include('partials.admin-tabs')
<h3 class="fw-bold mb-3">Nueva promoción</h3>
<form method="POST" action="{{ route('admin.promotions.store') }}" class="card p-4 shadow-sm border-0">
    @csrf
    <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>
    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">Descuento (%)</label>
            <input type="number" step="0.01" name="discount_percent" class="form-control" value="0" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Aplica a</label>
            <select name="target_type" class="form-select" required>
                <option value="general" selected>General</option>
                <option value="premium">Premium</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Inicio</label>
            <input type="date" name="starts_at" class="form-control">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Fin</label>
            <input type="date" name="ends_at" class="form-control">
        </div>
    </div>
    <div class="form-check mb-3">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
        <label class="form-check-label" for="is_active">Activa</label>
    </div>
    <button class="btn btn-primary">Guardar</button>
</form>
@endsection
