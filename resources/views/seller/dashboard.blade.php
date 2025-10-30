@extends('layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body p-4">
                <h2 class="mb-0">
                    <i class="fas fa-store"></i> Welcome, {{ $user->name }}!
                </h2>
                <p class="text-muted mb-0">Manage your shop and products</p>
            </div>
        </div>
    </div>

    <!-- Shop Info -->
<div class="col-md-4 mb-4">
    <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-info-circle"></i> Shop Information</span>
            <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#editProfileModal"
        style="border: 2px solid white; font-weight: 600;">
    <i class="fas fa-edit"></i> Edit
</button>
        </div>
        <div class="card-body">
            <h5>{{ $seller->shop_name }}</h5>
            <p class="text-muted mb-3">{{ $seller->shop_description ?: 'No description yet' }}</p>

            @if($seller->phone)
                <div class="mb-2">
                    <i class="fas fa-phone text-muted"></i>
                    <strong>Phone:</strong> {{ $seller->phone }}
                </div>
            @endif

            @if($seller->address)
                <div class="mb-2">
                    <i class="fas fa-map-marker-alt text-muted"></i>
                    <strong>Address:</strong> {{ $seller->address }}
                </div>
            @endif

            <hr>

            <div class="mb-2">
                <strong>Rating:</strong>
                <span class="text-warning">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $seller->rating)
                            <i class="fas fa-star"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </span>
                {{ number_format($seller->rating, 1) }}
            </div>
            <div>
                <strong>Total Ratings:</strong> {{ $seller->total_ratings }}
            </div>
            <div class="mt-3">
                <span class="badge bg-success">
                    <i class="fas fa-check-circle"></i> Subscription Active
                </span>
            </div>
        </div>
    </div>
</div>

    <!-- Statistics -->
    <div class="col-md-8 mb-4">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-box" style="font-size: 2rem; color: var(--primary-green);"></i>
                        <h3 class="mt-2">{{ $products->count() }}</h3>
                        <p class="text-muted mb-0">Total Products</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-shopping-bag" style="font-size: 2rem; color: var(--primary-green);"></i>
                        <h3 class="mt-2">{{ $orders->count() }}</h3>
                        <p class="text-muted mb-0">Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-clock" style="font-size: 2rem; color: var(--primary-green);"></i>
                        <h3 class="mt-2">{{ $orders->whereIn('status', ['ordered', 'preparing'])->count() }}</h3>
                        <p class="text-muted mb-0">Pending Orders</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-boxes"></i> My Products</span>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>
            <div class="card-body">
                @if($products->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-box-open" style="font-size: 3rem; color: var(--light-green);"></i>
                        <p class="text-muted mt-3">No products yet. Add your first product!</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Weight</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image_url)
                                                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}"
                                                     class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $product->name }}</strong><br>
                                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->category)
                                            <span class="badge" style="background-color: {{ $product->category->color }};">
                                                <i class="{{ $product->category->icon }}"></i> {{ $product->category->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Uncategorized</span>
                                        @endif
                                    </td>
                                    <td>₱{{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @if($product->stock > 10)
                                            <span class="badge bg-success">{{ $product->stock }}</span>
                                        @elseif($product->stock > 0)
                                            <span class="badge bg-warning">{{ $product->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->weight_kg }}kg</td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-receipt"></i> Recent Orders
            </div>
            <div class="card-body">
                @if($orders->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-bag" style="font-size: 3rem; color: var(--light-green);"></i>
                        <p class="text-muted mt-3">No orders yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Buyer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->buyer->name }}</td>
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
                                        <span class="badge bg-{{ $color }}">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($order->status == 'ordered')
                                            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="preparing">
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-check"></i> Start Preparing
                                                </button>
                                            </form>
                                        @elseif($order->status == 'preparing')
                                            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="ready_for_pickup">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-check"></i> Ready for Pickup
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('seller.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-green); color: white;">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Add New Product</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (₱)</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" name="weight_kg" class="form-control" step="0.01" value="1.0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-image"></i> Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Optional - JPG, PNG, or GIF (Max 2MB)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-green); color: white;">
                <h5 class="modal-title"><i class="fas fa-user-edit"></i> Edit Shop Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('seller.profile.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Shop Name *</label>
                        <input type="text" name="shop_name" class="form-control" value="{{ $seller->shop_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shop Description</label>
                        <textarea name="shop_description" class="form-control" rows="3">{{ $seller->shop_description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ $seller->phone }}" placeholder="+63 912 345 6789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Shop Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Street, Barangay, City, Province">{{ $seller->address }}</textarea>
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

<!-- Edit Product Modals -->
@foreach($products as $product)
<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-green); color: white;">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Product</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (₱)</label>
                            <input type="number" name="price" class="form-control" step="0.01" value="{{ $product->price }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Weight (kg)</label>
                        <input type="number" name="weight_kg" class="form-control" step="0.01" value="{{ $product->weight_kg }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-image"></i> Product Image</label>
                        @if($product->image_url)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}"
                                     class="img-thumbnail" style="max-height: 150px;">
                                <p class="small text-muted mt-1">Current image</p>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ $product->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
