@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2><i class="fas fa-receipt"></i> Order Details</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('buyer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('buyer.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item active">{{ $order->order_number }}</li>
            </ol>
        </nav>
    </div>

    <!-- Order Header -->
    <div class="col-lg-8 mb-4">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-info-circle"></i> Order #{{ $order->order_number }}</span>
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
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Order Date:</strong><br>
                        {{ $order->created_at->format('F d, Y - h:i A') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Seller:</strong><br>
                        <i class="fas fa-store"></i> {{ $order->seller->shop_name }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Delivery Address:</strong><br>
                        <i class="fas fa-map-marker-alt"></i> {{ $order->delivery_address }}
                    </div>
                    <div class="col-md-6">
                        @if($order->rider)
                            <strong>Rider:</strong><br>
                            <i class="fas fa-motorcycle"></i> {{ $order->rider->user->name }}<br>
                            <small class="text-muted">{{ ucfirst($order->rider->vehicle_type) }} ({{ $order->rider->max_capacity_kg }}kg capacity)</small>
                        @else
                            <strong>Rider:</strong><br>
                            <span class="text-muted">Waiting for assignment...</span>
                        @endif
                    </div>
                </div>
                @if($order->eta)
                    <div class="alert alert-info">
                        <i class="fas fa-clock"></i> <strong>Estimated Arrival:</strong> {{ $order->eta }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-list-ol"></i> Order Timeline
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ $order->status == 'ordered' || in_array($order->status, ['preparing', 'ready_for_pickup', 'picked_up', 'out_for_delivery', 'delivered']) ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Order Placed</h6>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ in_array($order->status, ['preparing', 'ready_for_pickup', 'picked_up', 'out_for_delivery', 'delivered']) ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Preparing</h6>
                            <small class="text-muted">Seller is preparing your order</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ in_array($order->status, ['ready_for_pickup', 'picked_up', 'out_for_delivery', 'delivered']) ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Ready for Pickup</h6>
                            <small class="text-muted">Order is ready for rider pickup</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ in_array($order->status, ['picked_up', 'out_for_delivery', 'delivered']) ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Picked Up</h6>
                            <small class="text-muted">Rider has picked up the order</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ in_array($order->status, ['out_for_delivery', 'delivered']) ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Out for Delivery</h6>
                            <small class="text-muted">On the way to your location</small>
                        </div>
                    </div>
                    <div class="timeline-item {{ $order->status == 'delivered' ? 'active' : '' }}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <h6>Delivered</h6>
                            <small class="text-muted">Order has been delivered</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-box"></i> Order Items
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Weight</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product->name }}</strong><br>
                                    <small class="text-muted">{{ $item->product->description }}</small>
                                </td>
                                <td>₱{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->weight_kg, 2) }}kg</td>
                                <td class="text-end">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary Sidebar -->
    <div class="col-lg-4 mb-4">
        <div class="card mb-3">
            <div class="card-header">
                <i class="fas fa-calculator"></i> Order Summary
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong>₱{{ number_format($order->subtotal, 2) }}</strong>
                </div>
                @if($order->points_discount > 0)
                    <div class="d-flex justify-content-between mb-2" style="color: var(--primary-green);">
                        <span><i class="fas fa-coins"></i> Points Discount:</span>
                        <strong>-₱{{ number_format($order->points_discount, 2) }}</strong>
                    </div>
                @endif
                <div class="d-flex justify-content-between mb-2">
                    <span>Total Weight:</span>
                    <strong>{{ number_format($order->total_weight_kg, 2) }}kg</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5>Total:</h5>
                    <h5 style="color: var(--primary-green);">₱{{ number_format($order->total, 2) }}</h5>
                </div>
            </div>
        </div>

        @if($order->rating)
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-star"></i> Your Rating
                </div>
                <div class="card-body">
                    <div class="text-center mb-2">
                        <div class="text-warning" style="font-size: 1.5rem;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $order->rating->rating)
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    @if($order->rating->comment)
                        <p class="mb-0 text-muted small">{{ $order->rating->comment }}</p>
                    @endif
                    <div class="alert alert-success mt-3 mb-0">
                        <small><i class="fas fa-gift"></i> You earned {{ $order->rating->bonus_points }} bonus points!</small>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 30px;
        border-left: 2px solid #ddd;
    }
    .timeline-item.active {
        border-left-color: var(--primary-green);
    }
    .timeline-item.active .timeline-marker {
        background-color: var(--primary-green);
        border-color: var(--primary-green);
    }
    .timeline-marker {
        position: absolute;
        left: -8px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: #ddd;
        border: 3px solid white;
    }
    .timeline-content {
        padding-left: 20px;
    }
</style>
@endpush
@endsection
