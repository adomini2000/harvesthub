@extends('layouts.app')

@section('title', 'Browse Products')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-store"></i> Browse Products</h2>
    </div>
</div>

<!-- Filters -->
<div class="col-md-3">
    <select name="category" class="form-select">
    <option value="">All Categories</option>
    @foreach($categories as $category)
        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
            {{ $category->name }} ({{ $category->products_count }})
        </option>
    @endforeach
</select>
</div>
                    <div class="col-md-3">
                        <select name="sort" class="form-select">
                            <option value="">Sort By</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="row">
    @if($products->isEmpty())
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-box-open" style="font-size: 5rem; color: var(--light-green);"></i>
                    <h4 class="mt-4">No products found</h4>
                    <p class="text-muted">Try adjusting your filters</p>
                </div>
            </div>
        </div>
    @else
        @foreach($products as $product)
<div class="col-md-3 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <div class="text-center mb-3">
                @if($product->image_url)
                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}"
                         class="rounded" style="width: 100%; height: 180px; object-fit: cover;">
                @else
                    <i class="fas fa-leaf" style="font-size: 3.5rem; color: var(--light-green);"></i>
                @endif
            </div>
            <h5 class="card-title">{{ $product->name }}</h5>
            <p class="text-muted small">{{ Str::limit($product->description, 60) }}</p>
            <div class="mb-2">
                <small class="text-muted">
                    <i class="fas fa-store"></i> {{ $product->seller->shop_name }}
                </small>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-success">{{ $product->stock }} in stock</span>
                <small class="text-muted">{{ $product->weight_kg }}kg</small>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0" style="color: var(--primary-green);">
                    ₱{{ number_format($product->price, 2) }}
                </h4>
            </div>
            <div class="d-grid gap-2">
                <form action="{{ route('buyer.cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
                <a href="{{ route('buyer.products.show', $product->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-eye"></i> View Details
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

        <!-- Pagination -->
        <div class="col-12 mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
