@extends('layouts.app')

@section('content')
@include('partials.admin-tabs')
<h3 class="fw-bold mb-3">Editar promoción</h3>
<form method="POST" action="{{ route('admin.promotions.update', $promotion) }}" class="card p-4 shadow-sm border-0">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="title" class="form-control" value="{{ $promotion->title }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="description" class="form-control" rows="3">{{ $promotion->description }}</textarea>
    </div>
    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">Descuento (%)</label>
            <input type="number" step="0.01" name="discount_percent" class="form-control" value="{{ $promotion->discount_percent }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Aplica a</label>
            <select name="target_type" class="form-select" required>
                <option value="general" {{ $promotion->target_type === 'general' ? 'selected' : '' }}>General</option>
                <option value="premium" {{ $promotion->target_type === 'premium' ? 'selected' : '' }}>Premium</option>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Inicio</label>
            <input type="date" name="starts_at" class="form-control" value="{{ optional($promotion->starts_at)->format('Y-m-d') }}">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Fin</label>
            <input type="date" name="ends_at" class="form-control" value="{{ optional($promotion->ends_at)->format('Y-m-d') }}">
        </div>
    </div>
    <div class="form-check mb-3">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $promotion->is_active ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Activa</label>
    </div>
    <button class="btn btn-primary">Actualizar</button>
</form>
@endsection
