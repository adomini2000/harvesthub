@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2><i class="fas fa-receipt"></i> My Orders</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('buyer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </nav>
    </div>

    @if($orders->isEmpty())
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-shopping-bag" style="font-size: 5rem; color: var(--light-green);"></i>
                    <h4 class="mt-4">No orders yet</h4>
                    <p class="text-muted">Start shopping for fresh products!</p>
                    <a href="{{ route('buyer.products.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-store"></i> Browse Products
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @foreach($orders as $order)
                    <div class="border rounded p-4 mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="mb-1">Order #{{ $order->order_number }}</h5>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> {{ $order->created_at->format('M d, Y - h:i A') }}
                                        </small>
                                    </div>
                                    <div>
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
                                        <span class="badge bg-{{ $color }} fs-6">
                                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="mb-2">
                                        <i class="fas fa-store text-muted"></i>
                                        <strong>Seller:</strong> {{ $order->seller->shop_name }}
                                    </div>
                                    <div class="mb-2">
                                        <i class="fas fa-box text-muted"></i>
                                        <strong>Items:</strong> {{ $order->items->count() }} product(s)
                                    </div>
                                    <div class="mb-2">
                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                        <strong>Delivery:</strong> {{ Str::limit($order->delivery_address, 50) }}
                                    </div>
                                    @if($order->rider)
                                        <div class="mb-2">
                                            <i class="fas fa-motorcycle text-muted"></i>
                                            <strong>Rider:</strong> {{ $order->rider->user->name }}
                                        </div>
                                    @endif
                                    @if($order->eta)
                                        <div class="mb-2">
                                            <i class="fas fa-clock text-muted"></i>
                                            <strong>ETA:</strong> {{ $order->eta }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Order Timeline -->
                                <div class="mb-3">
                                    <small class="text-muted"><strong>Order Progress:</strong></small>
                                    <div class="d-flex align-items-center mt-2">
                                        <div class="flex-grow-1">
                                            <div class="progress" style="height: 8px;">
                                                @php
                                                    $statusProgress = [
                                                        'ordered' => 20,
                                                        'preparing' => 40,
                                                        'ready_for_pickup' => 60,
                                                        'picked_up' => 70,
                                                        'out_for_delivery' => 85,
                                                        'delivered' => 100,
                                                        'cancelled' => 0
                                                    ];
                                                    $progress = $statusProgress[$order->status] ?? 0;
                                                @endphp
                                                <div class="progress-bar" role="progressbar"
                                                     style="width: {{ $progress }}%; background-color: var(--primary-green);"
                                                     aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <span class="ms-2 small">{{ $progress }}%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 text-md-end">
                                <div class="mb-3">
                                    @if($order->points_discount > 0)
                                        <div class="text-muted small">
                                            <del>₱{{ number_format($order->subtotal, 2) }}</del>
                                        </div>
                                        <div class="text-success small">
                                            <i class="fas fa-coins"></i> -₱{{ number_format($order->points_discount, 2) }}
                                        </div>
                                    @endif
                                    <h4 style="color: var(--primary-green);">
                                        ₱{{ number_format($order->total, 2) }}
                                    </h4>
                                    <small class="text-muted">{{ number_format($order->total_weight_kg, 2) }}kg</small>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('buyer.orders.show', $order->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>

                                    @if($order->status == 'delivered' && !$order->rating)
                                        <button class="btn btn-outline-primary btn-sm" onclick="openRatingModal({{ $order->id }}, '{{ addslashes($order->seller->shop_name) }}')">
                                            <i class="fas fa-star"></i> Rate Seller
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Single Rating Modal (Outside the loop) -->
<div class="modal fade" id="ratingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-green); color: white;">
                <h5 class="modal-title"><i class="fas fa-star"></i> Rate Your Experience</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="ratingForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rating for <strong id="sellerName"></strong></label>
                        <select name="rating" class="form-select form-select-lg text-center" required style="font-size: 1.5rem;">
                            <option value="">Select Rating</option>
                            <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                            <option value="4">⭐⭐⭐⭐ Good</option>
                            <option value="3">⭐⭐⭐ Average</option>
                            <option value="2">⭐⭐ Poor</option>
                            <option value="1">⭐ Very Poor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comment (Optional)</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-gift"></i> Earn <strong>10 bonus points</strong> for rating!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let ratingModal;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modal once on page load
        const modalElement = document.getElementById('ratingModal');
        ratingModal = new bootstrap.Modal(modalElement);
    });

    function openRatingModal(orderId, sellerName) {
        // Update form action URL
        document.getElementById('ratingForm').action = `/buyer/orders/${orderId}/rate`;

        // Update seller name
        document.getElementById('sellerName').textContent = sellerName;

        // Reset form fields
        document.getElementById('ratingForm').reset();

        // Show modal instantly
        ratingModal.show();
    }
</script>
@endpush

@endsection
