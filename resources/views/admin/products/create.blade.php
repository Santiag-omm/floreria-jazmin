@extends('layouts.app')

@section('content')
<div class="admin-tech">
    @include('partials.admin-tabs')
    <h3 class="fw-bold mb-3">Nueva flor</h3>
    <form method="POST" action="{{ route('admin.products.store') }}" class="card p-4 shadow-sm border-0" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="mb-3">
                <label class="form-label">Flor</label>
                <input type="text" name="name" class="form-control" placeholder="Ej: Ramo Primavera" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe el arreglo, colores y tamaño"></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Precio</label>
                    <input type="number" step="0.01" name="price" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="0" required>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 bg-body-tertiary p-3 mb-3">
                <label class="form-label">Categoría</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="form-check mt-3">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <label class="form-check-label" for="is_active">Visible en catálogo</label>
                </div>
                <div class="form-check mt-2">
                    <input type="hidden" name="is_premium" value="0">
                    <input class="form-check-input" type="checkbox" name="is_premium" id="is_premium" value="1">
                    <label class="form-check-label" for="is_premium">Producto premium</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Imágenes (múltiples)</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                <div class="form-text">La primera imagen será la portada.</div>
            </div>
        </div>
    </div>
        <button class="btn btn-primary">Guardar flor</button>
    </form>
</div>
@endsection
