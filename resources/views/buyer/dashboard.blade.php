@extends('layouts.app')

@section('title', 'Buyer Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">
                            <i class="fas fa-shopping-bag"></i> Welcome, {{ $user->name }}!
                        </h2>
                        <p class="text-muted mb-0">Start shopping for fresh products</p>
                    </div>
                    <div class="text-end">
                        <h4 class="mb-0" style="color: var(--primary-green);">
                            <i class="fas fa-coins"></i> {{ number_format($points->total_points ?? 0, 2) }} Points
                        </h4>
                        <small class="text-muted">Available to redeem</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buyer Profile -->
<div class="col-12 mb-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-user-circle"></i> My Profile</span>
            <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editBuyerProfileModal"
        style="border: 2px solid #333; font-weight: 600; color: #333;">
    <i class="fas fa-edit"></i> Edit
</button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Name:</strong><br>
                    {{ $user->name }}
                </div>
                <div class="col-md-4">
                    <strong>Email:</strong><br>
                    {{ $user->email }}
                </div>
                <div class="col-md-4">
                    <strong>Phone:</strong><br>
                    {{ $user->phone ?: 'Not set' }}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <strong>Default Delivery Address:</strong><br>
                    @if($user->address)
                        <i class="fas fa-map-marker-alt text-muted"></i> {{ $user->address }}
                    @else
                        <span class="text-muted">No address set. Add your address for faster checkout!</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Quick Stats -->
    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-shopping-cart" style="font-size: 2.5rem; color: var(--primary-green);"></i>
                <h3 class="mt-3">{{ $orders->count() }}</h3>
                <p class="text-muted mb-0">Total Orders</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-clock" style="font-size: 2.5rem; color: var(--primary-green);"></i>
                <h3 class="mt-3">{{ $orders->whereIn('status', ['ordered', 'preparing', 'out_for_delivery'])->count() }}</h3>
                <p class="text-muted mb-0">Active Orders</p>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="fas fa-star" style="font-size: 2.5rem; color: var(--primary-green);"></i>
                <h3 class="mt-3">{{ number_format($points->total_points ?? 0, 0) }}</h3>
                <p class="text-muted mb-0">Reward Points</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('buyer.cart.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart"></i> View Cart
                    </a>
                    <a href="{{ route('buyer.orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-receipt"></i> My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products by Category -->
@php
    $categoriesWithProducts = \App\Models\Category::with(['products' => function($query) {
        $query->where('is_active', true)
              ->where('stock', '>', 0)
              ->with('seller.user')
              ->orderBy('name', 'asc'); // alphabetical order
    }])->orderBy('id')->get();
@endphp

@foreach($categoriesWithProducts as $category)
<div class="row mb-4">
    <div class="col-12">
        <!-- Category Header -->
        <div class="d-flex align-items-center mb-3 pb-2" style="border-bottom: 3px solid {{ $category->color }};">
            <i class="{{ $category->icon }}" style="font-size: 1.5rem; color: {{ $category->color }}; margin-right: 10px;"></i>
            <h5 class="mb-0" style="color: {{ $category->color }};">{{ $category->name }}</h5>
        </div>

        @if($category->products->isEmpty())
            <!-- Empty State -->
            <div class="col-12">
                <div class="alert alert-light text-center">
                    <i class="fas fa-box-open text-muted"></i>
                    <small class="text-muted">No products available in this category yet.</small>
                </div>
            </div>
        @else
            <!-- Products Row -->
            <div class="row">
                @foreach($category->products as $product)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <!-- Product Image -->
                            <div class="text-center mb-3">
                                @if($product->image_url)
                                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}"
                                         class="rounded" style="width: 100%; height: 150px; object-fit: cover;">
                                @else
                                    <i class="{{ $category->icon }}" style="font-size: 3rem; color: {{ $category->color }};"></i>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted small">{{ Str::limit($product->description, 60) }}</p>

                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-store"></i> {{ $product->seller->shop_name }}
                                </small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0" style="color: var(--primary-green);">₱{{ number_format($product->price, 2) }}</h4>
                                <small class="text-muted">{{ $product->weight_kg }}kg</small>
                            </div>

                            <!-- Add to Cart Button -->
                            <div class="d-grid">
                                <form action="{{ route('buyer.cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-cart-plus"></i> Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endforeach


<!-- Recent Orders -->
@if($orders->isNotEmpty())
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-receipt"></i> Recent Orders
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Seller</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->seller->shop_name }}</td>
                                <td>{{ $order->items->count() }} item(s)</td>
                                <td>₱{{ number_format($order->total, 2) }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'ordered' => 'info',
                                            'preparing' => 'warning',
                                            'ready_for_pickup' => 'primary',
                                            'picked_up' => 'primary',
                                            'out_for_delivery' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $color = $statusColors[$order->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('buyer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Edit Buyer Profile Modal -->
<div class="modal fade" id="editBuyerProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-green); color: white;">
                <h5 class="modal-title"><i class="fas fa-user-edit"></i> Edit Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('buyer.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" placeholder="+63 912 345 6789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Delivery Address</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="House/Unit No., Street, Barangay, City, Province">{{ $user->address }}</textarea>
                        <small class="text-muted">This will be used as your default address during checkout.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
