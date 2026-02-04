@extends('layouts.app')

@section('content')
<h3 class="fw-bold mb-3">{{ $category->name }}</h3>

<div class="row g-3">
    @forelse($products as $product)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                @if($product->coverImage())
                    <img class="card-img-top" src="{{ asset('storage/' . $product->coverImage()->path) }}" alt="{{ $product->name }}">
                @else
                    <div class="bg-body-secondary d-flex align-items-center justify-content-center" style="height:180px;">
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
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('product.show', $product) }}">Ver detalle</a>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Sin productos en esta categoría</p>
    @endforelse
</div>

<div class="mt-3">{{ $products->links() }}</div>
@endsection
