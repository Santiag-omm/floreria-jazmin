@extends('layouts.app')

@section('content')
@include('partials.admin-tabs')
<h3 class="fw-bold mb-3">Editar flor</h3>
<form method="POST" action="{{ route('admin.products.update', $product) }}" class="card p-4 shadow-sm border-0" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="mb-3">
                <label class="form-label">Flor</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Precio</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 bg-body-tertiary p-3 mb-3">
                <label class="form-label">Categoría</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="form-check mt-3">
                    <input type="hidden" name="is_active" value="0">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Visible en catálogo</label>
                </div>
                <div class="form-check mt-2">
                    <input type="hidden" name="is_premium" value="0">
                    <input class="form-check-input" type="checkbox" name="is_premium" id="is_premium" value="1" {{ $product->is_premium ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_premium">Producto premium</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Agregar nuevas imágenes</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Imágenes actuales</label>
        <div class="row g-3">
            @foreach($product->images as $image)
                <div class="col-md-3 text-center">
                    <img class="img-fluid rounded mb-2" src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cover_image_id" value="{{ $image->id }}" {{ $image->is_cover ? 'checked' : '' }}>
                        <label class="form-check-label">Portada</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $image->id }}">
                        <label class="form-check-label">Eliminar</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <button class="btn btn-primary">Actualizar flor</button>
</form>
@endsection
