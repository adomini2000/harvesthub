@extends('layouts.app')

@section('title', 'Rider Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Header -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="mb-3 mb-md-0">
                        <h2 class="mb-0">
                            <i class="fas fa-motorcycle"></i> Welcome, {{ $user->name }}!
                        </h2>
                        <p class="text-muted mb-0">Manage your deliveries</p>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <a href="{{ route('rider.history') }}" class="btn btn-outline-primary">
                            <i class="fas fa-history"></i> View History
                        </a>
                        <form action="{{ route('rider.status.update') }}" method="POST" id="statusForm">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-lg" onchange="this.form.submit()"
                                    style="border: 2px solid var(--primary-green);">
                                <option value="normal" {{ $rider->status == 'normal' ? 'selected' : '' }}>
                                    🟢 Available
                                </option>
                                <option value="busy" {{ $rider->status == 'busy' ? 'selected' : '' }}>
                                    🟡 Busy
                                </option>
                                <option value="closed" {{ $rider->status == 'closed' ? 'selected' : '' }}>
                                    🔴 Closed
                                </option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rider Info -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="fas fa-id-card"></i> Rider Information
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-circle" style="font-size: 4rem; color: var(--primary-green);"></i>
                </div>
                <h5 class="text-center">{{ $user->name }}</h5>
                <hr>
                <div class="mb-2">
                    <i class="fas fa-motorcycle text-muted"></i>
                    <strong>Vehicle:</strong> {{ ucfirst($rider->vehicle_type) }}
                </div>
                <div class="mb-2">
                    <i class="fas fa-weight text-muted"></i>
                    <strong>Max Capacity:</strong> {{ $rider->max_capacity_kg }}kg
                </div>
                <div class="mb-2">
                    <i class="fas fa-circle text-muted"></i>
                    <strong>Status:</strong>
                    @if($rider->status == 'normal')
                        <span class="badge bg-success">Available</span>
                    @elseif($rider->status == 'busy')
                        <span class="badge bg-warning">Busy</span>
                    @else
                        <span class="badge bg-danger">Closed</span>
                    @endif
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
                        <h3 class="mt-2">{{ $availableOrders->count() }}</h3>
                        <p class="text-muted mb-0">Available Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-truck" style="font-size: 2rem; color: var(--primary-green);"></i>
                        <h3 class="mt-2">{{ $myOrders->count() }}</h3>
                        <p class="text-muted mb-0">Active Deliveries</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-check-circle" style="font-size: 2rem; color: var(--primary-green);"></i>
                        <h3 class="mt-2">{{ $completedToday }}</h3>
                        <p class="text-muted mb-0">Completed Today</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Deliveries -->
@if($myOrders->isNotEmpty())
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-shipping-fast"></i> My Active Deliveries
            </div>
            <div class="card-body">
                @foreach($myOrders as $order)
                <div class="border rounded p-4 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order #{{ $order->order_number }}</h5>
                            <div class="mb-2">
                                <i class="fas fa-store text-muted"></i>
                                <strong>From:</strong> {{ $order->seller->shop_name }}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-user text-muted"></i>
                                <strong>Buyer:</strong> {{ $order->buyer->name }}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt text-muted"></i>
                                <strong>Address:</strong> {{ $order->delivery_address }}
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-weight text-muted"></i>
                                <strong>Weight:</strong> {{ number_format($order->total_weight_kg, 2) }}kg
                            </div>
                            @if($order->eta)
                                <div class="mb-2">
                                    <i class="fas fa-clock text-muted"></i>
                                    <strong>ETA:</strong> {{ $order->eta }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h4 class="mb-3" style="color: var(--primary-green);">
                                ₱{{ number_format($order->total, 2) }}
                            </h4>
                            <div class="mb-3">
                                @php
                                    $statusColors = [
                                        'picked_up' => 'primary',
                                        'out_for_delivery' => 'warning',
                                    ];
                                    $color = $statusColors[$order->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} fs-6">
                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>

                            @if($order->status == 'picked_up')
                                <!-- Set ETA -->
                                <form action="{{ route('rider.orders.setEta', $order->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group input-group-sm">
                                        <select name="eta" class="form-select" required>
                                            <option value="">Set ETA</option>
                                            <option value="15 minutes">15 minutes</option>
                                            <option value="30 minutes">30 minutes</option>
                                            <option value="45 minutes">45 minutes</option>
                                            <option value="1 hour">1 hour</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-clock"></i> Set
                                        </button>
                                    </div>
                                </form>
                                <!-- Mark Out for Delivery -->
                                <form action="{{ route('rider.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="out_for_delivery">
                                    <button type="submit" class="btn btn-warning btn-sm w-100">
                                        <i class="fas fa-truck"></i> Out for Delivery
                                    </button>
                                </form>
                            @elseif($order->status == 'out_for_delivery')
                                <!-- Mark as Delivered -->
                                <form action="{{ route('rider.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="delivered">
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="fas fa-check"></i> Mark as Delivered
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<!-- Available Orders -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list"></i> Available Orders
            </div>
            <div class="card-body">
                @if($availableOrders->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: var(--light-green);"></i>
                        <p class="text-muted mt-3">No available orders at the moment.</p>
                    </div>
                @else
                    @foreach($availableOrders as $order)
                    <div class="border rounded p-4 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5>Order #{{ $order->order_number }}</h5>
                                <div class="mb-2">
                                    <i class="fas fa-store text-muted"></i>
                                    <strong>Pickup:</strong> {{ $order->seller->shop_name }}
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                    <strong>Deliver to:</strong> {{ Str::limit($order->delivery_address, 80) }}
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-box text-muted"></i>
                                    <strong>Items:</strong> {{ $order->items->count() }} product(s)
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-weight text-muted"></i>
                                    <strong>Total Weight:</strong>
                                    <span class="badge bg-info">{{ number_format($order->total_weight_kg, 2) }}kg</span>
                                    @if($order->total_weight_kg <= $rider->max_capacity_kg)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Within your capacity
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Too heavy
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <h4 class="mb-3" style="color: var(--primary-green);">
                                    ₱{{ number_format($order->total, 2) }}
                                </h4>
                                @if($rider->status == 'normal' && $order->total_weight_kg <= $rider->max_capacity_kg)
                                    <form action="{{ route('rider.orders.accept', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-hand-paper"></i> Accept Order
                                        </button>
                                    </form>
                                @elseif($rider->status != 'normal')
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="fas fa-ban"></i> Set Status to Available
                                    </button>
                                @else
                                    <button class="btn btn-danger w-100" disabled>
                                        <i class="fas fa-times"></i> Exceeds Capacity
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
