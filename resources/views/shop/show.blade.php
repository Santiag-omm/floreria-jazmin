@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div id="productCarousel" class="carousel slide">
            <div class="carousel-inner">
                @foreach($product->images as $index => $image)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <img class="d-block w-100 rounded" src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}">
                    </div>
                @endforeach
                @if($product->images->isEmpty())
                    <div class="carousel-item active">
                        <div class="bg-body-secondary d-flex align-items-center justify-content-center rounded" style="height:320px;">
                            <span class="text-muted">Sin imagen</span>
                        </div>
                    </div>
                @endif
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
    <div class="col-lg-6">
        @php
            $discountPercent = \App\Models\Promotion::discountForProduct($product, $activePromotions);
            $finalPrice = \App\Models\Promotion::applyDiscount((float) $product->price, $discountPercent);
        @endphp
        <h2 class="fw-bold">{{ $product->name }}</h2>
        <p class="text-muted">{{ $product->description }}</p>
        <div class="fs-4 fw-bold mb-3">
            $ {{ number_format($finalPrice, 2) }}
            @if($discountPercent > 0)
                <span class="price-old">$ {{ number_format($product->price, 2) }}</span>
                <span class="badge bg-danger">-{{ $discountPercent }}%</span>
            @endif
        </div>
        <form method="POST" action="{{ route('cart.add', $product) }}" class="d-flex gap-2">
            @csrf
            <input type="number" name="quantity" class="form-control w-25" value="1" min="1">
            <button class="btn btn-primary" data-flower-burst>Agregar al carrito</button>
        </form>
    </div>
</div>

@if($related->count())
    <div class="mt-5">
        <h4 class="fw-bold">Relacionados</h4>
        <div class="row g-3">
            @foreach($related as $item)
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        @if($item->coverImage())
                            <img class="card-img-top" src="{{ asset('storage/' . $item->coverImage()->path) }}" alt="{{ $item->name }}">
                        @else
                            <div class="bg-body-secondary d-flex align-items-center justify-content-center" style="height:160px;">
                                <span class="text-muted">Sin imagen</span>
                            </div>
                        @endif
                        @php
                            $relatedDiscount = \App\Models\Promotion::discountForProduct($item, $activePromotions);
                            $relatedFinal = \App\Models\Promotion::applyDiscount((float) $item->price, $relatedDiscount);
                        @endphp
                        <div class="card-body">
                            <h6 class="fw-bold">{{ $item->name }}</h6>
                            <div class="text-muted mb-2">
                                $ {{ number_format($relatedFinal, 2) }}
                                @if($relatedDiscount > 0)
                                    <span class="price-old">$ {{ number_format($item->price, 2) }}</span>
                                @endif
                            </div>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('product.show', $item) }}">Ver</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
