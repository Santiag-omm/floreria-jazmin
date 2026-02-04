@extends('layouts.app')

@section('content')
<div class="hero p-5 mb-4 shadow-sm">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <span class="badge badge-soft mb-3">Detalles florales premium</span>
            <h1 class="hero-title fw-bold">Floreria Jazmin</h1>
            <p class="lead text-muted">Arreglos elegantes, entregas puntuales y una experiencia de compra simple.</p>
            <div class="d-flex gap-2">
                <a class="btn btn-primary btn-lg" href="#productos">Ver catálogo</a>
                <a class="btn btn-outline-secondary btn-lg" href="{{ route('cart.index') }}">Ver carrito</a>
            </div>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <div class="rounded-4 bg-white border overflow-hidden" style="min-height:260px;">
                @if($products->count())
                    <div id="heroProductsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($products->take(5) as $index => $product)
                                <button type="button" data-bs-target="#heroProductsCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($products->take(5) as $index => $product)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @if($product->coverImage())
                                        <img src="{{ asset('storage/' . $product->coverImage()->path) }}" class="d-block w-100" style="height:260px; object-fit:cover;" alt="{{ $product->name }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-body-secondary" style="height:260px;">
                                            <div class="text-center">
                                                <div class="fs-1">🌸</div>
                                                <div class="text-muted">{{ $product->name }}</div>
                                            </div>
                                        </div>
                                    @endif
                                        @php
                                            $discountPercent = \App\Models\Promotion::discountForProduct($product, $activePromotions);
                                            $finalPrice = \App\Models\Promotion::applyDiscount((float) $product->price, $discountPercent);
                                        @endphp
                                        <div class="carousel-caption d-none d-md-block">
                                            <h5 class="fw-bold">{{ $product->name }}</h5>
                                            <p class="mb-0">
                                                $ {{ number_format($finalPrice, 2) }}
                                                @if($discountPercent > 0)
                                                    <span class="price-old">$ {{ number_format($product->price, 2) }}</span>
                                                    <span class="badge bg-danger">-{{ $discountPercent }}%</span>
                                                @endif
                                            </p>
                                        </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroProductsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroProductsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center" style="min-height:260px;">
                        <div class="text-center">
                            <div class="fs-1">🌸</div>
                            <div class="text-muted">Diseños florales exclusivos</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($promotions->count())
    <div class="mb-4">
        <h4 class="fw-bold">Promociones destacadas</h4>
        <div class="row g-3">
            @foreach($promotions as $promotion)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $promotion->title }}</h5>
                            <p class="text-muted">{{ $promotion->description }}</p>
                            <span class="badge bg-primary">{{ $promotion->discount_percent }}% OFF</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if($categories->count())
    <div class="mb-3">
        @foreach($categories as $category)
            <a class="category-chip" href="{{ route('category.show', $category) }}">{{ $category->name }}</a>
        @endforeach
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3" id="productos">
    <h4 class="fw-bold">Catálogo</h4>
</div>

<div class="row g-3">
    @forelse($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm h-100 product-card">
                @if($product->coverImage())
                    <img class="card-img-top" src="{{ asset('storage/' . $product->coverImage()->path) }}" alt="{{ $product->name }}">
                @else
                    <div class="bg-body-secondary d-flex align-items-center justify-content-center placeholder-img">
                        <span class="text-muted">Sin imagen</span>
                    </div>
                @endif
                    @php
                        $discountPercent = \App\Models\Promotion::discountForProduct($product, $activePromotions);
                        $finalPrice = \App\Models\Promotion::applyDiscount((float) $product->price, $discountPercent);
                    @endphp
                    <div class="card-body">
                        <h6 class="fw-bold">{{ $product->name }}</h6>
                        <p class="text-muted mb-1">
                            $ {{ number_format($finalPrice, 2) }}
                            @if($discountPercent > 0)
                                <span class="price-old">$ {{ number_format($product->price, 2) }}</span>
                            @endif
                        </p>
                        @if($discountPercent > 0)
                            <span class="badge bg-danger">-{{ $discountPercent }}%</span>
                        @endif
                    <div class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('product.show', $product) }}">Ver detalle</a>
                        <form method="POST" action="{{ route('cart.add', $product) }}">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button class="btn btn-sm btn-primary" type="submit" data-flower-burst>Comprar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Sin productos disponibles</p>
    @endforelse
</div>

<div class="mt-3">{{ $products->links() }}</div>
@endsection
