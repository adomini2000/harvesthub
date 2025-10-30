@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2><i class="fas fa-shopping-cart"></i> Shopping Cart</h2>
    </div>

    @if(empty($cartItems))
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-cart" style="font-size: 5rem; color: var(--light-green);"></i>
                    <h4 class="mt-4">Your cart is empty</h4>
                    <p class="text-muted">Start adding some fresh products!</p>
                    <a href="{{ route('buyer.products.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-store"></i> Browse Products
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Cart Items -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list"></i> Cart Items ({{ count($cartItems) }})</span>
                    <form action="{{ route('buyer.cart.clear') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Clear all items from cart?')">
                            <i class="fas fa-trash"></i> Clear Cart
                        </button>
                    </form>
                </div>
                <div class="card-body p-0">
                 @foreach($cartItems as $item)
<div class="p-4 border-bottom">
    <div class="row align-items-center">
        <div class="col-md-2 text-center mb-3 mb-md-0">
            @if($item['product']->image_url)
                <img src="{{ asset('storage/' . $item['product']->image_url) }}" alt="{{ $item['product']->name }}"
                     class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
            @else
                <i class="fas fa-leaf" style="font-size: 3rem; color: var(--light-green);"></i>
            @endif
        </div>

        <div class="col-md-4 mb-3 mb-md-0">
            <h5 class="mb-1">{{ $item['product']->name }}</h5>
            <small class="text-muted">
                <i class="fas fa-store"></i> {{ $item['product']->seller->shop_name }}
            </small><br>
            <small class="text-muted">
                <i class="fas fa-weight"></i> {{ $item['product']->weight_kg }}kg per item
            </small>
        </div>

        <div class="col-md-2 text-center mb-3 mb-md-0">
            <strong style="color: var(--primary-green);">
                ₱{{ number_format($item['product']->price, 2) }}
            </strong>
        </div>

        <div class="col-md-2 mb-3 mb-md-0">
            <form action="{{ route('buyer.cart.update', $item['product']->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="input-group input-group-sm">
                    <button class="btn btn-outline-secondary" type="button"
                            onclick="this.parentElement.querySelector('input[type=number]').stepDown(); this.form.submit();">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" name="quantity" class="form-control text-center"
                           value="{{ $item['quantity'] }}" min="1"
                           max="{{ $item['product']->stock }}"
                           onchange="this.form.submit()">
                    <button class="btn btn-outline-secondary" type="button"
                            onclick="this.parentElement.querySelector('input[type=number]').stepUp(); this.form.submit();">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </form>
            <small class="text-muted d-block text-center mt-1">
                {{ $item['product']->stock }} available
            </small>
        </div>

        <div class="col-md-2 text-end">
            <div class="mb-2">
                <strong>₱{{ number_format($item['subtotal'], 2) }}</strong>
            </div>
            <form action="{{ route('buyer.cart.remove', $item['product']->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endforeach

                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-receipt"></i> Order Summary
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <strong>₱{{ number_format($total, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Weight:</span>
                        <strong>{{ number_format($totalWeight, 2) }}kg</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span>Available Points:</span>
                        <strong style="color: var(--primary-green);">
                            {{ number_format($points->total_points ?? 0, 2) }}
                        </strong>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <h5>Total:</h5>
                        <h5 style="color: var(--primary-green);">₱{{ number_format($total, 2) }}</h5>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('buyer.checkout') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-check-circle"></i> Proceed to Checkout
                        </a>
                        <a href="{{ route('buyer.products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

            <!-- Points Info -->
            <div class="card mt-3">
                <div class="card-body" style="background: var(--pale-green);">
                    <div class="text-center">
                        <i class="fas fa-coins" style="font-size: 2rem; color: var(--primary-green);"></i>
                        <h6 class="mt-2 mb-1">Earn Rewards!</h6>
                        <p class="small text-muted mb-0">
                            You'll earn <strong>₱{{ number_format($total * 0.02, 2) }}</strong> in points
                            (2% cashback) from this order!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
