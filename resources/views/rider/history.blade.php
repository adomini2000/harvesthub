@extends('layouts.app')

@section('title', 'Delivery History')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-history"></i> Delivery History</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('rider.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('rider.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Lifetime Statistics -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-line"></i> Lifetime Statistics
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-box" style="font-size: 2.5rem; color: var(--primary-green);"></i>
                            <h3 class="mt-2 mb-0">{{ $totalDeliveries }}</h3>
                            <p class="text-muted mb-0">Total Deliveries</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-peso-sign" style="font-size: 2.5rem; color: var(--primary-green);"></i>
                            <h3 class="mt-2 mb-0">₱{{ number_format($totalEarnings, 2) }}</h3>
                            <p class="text-muted mb-0">Total Orders Value</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-weight" style="font-size: 2.5rem; color: var(--primary-green);"></i>
                            <h3 class="mt-2 mb-0">{{ number_format($totalDistance, 2) }}kg</h3>
                            <p class="text-muted mb-0">Total Weight Delivered</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-motorcycle" style="font-size: 2.5rem; color: var(--primary-green);"></i>
                            <h3 class="mt-2 mb-0">{{ ucfirst($rider->vehicle_type) }}</h3>
                            <p class="text-muted mb-0">{{ $rider->max_capacity_kg }}kg capacity</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Statistics -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar-day" style="font-size: 2rem; color: var(--light-green);"></i>
                        <h4 class="mt-2 mb-0">{{ $completedToday }}</h4>
                        <p class="text-muted mb-0">Completed Today</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar-week" style="font-size: 2rem; color: var(--light-green);"></i>
                        <h4 class="mt-2 mb-0">{{ $completedThisWeek }}</h4>
                        <p class="text-muted mb-0">This Week</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt" style="font-size: 2rem; color: var(--light-green);"></i>
                        <h4 class="mt-2 mb-0">{{ $completedThisMonth }}</h4>
                        <p class="text-muted mb-0">This Month</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery History Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-list"></i> Completed Deliveries
            </div>
            <div class="card-body">
                @if($completedOrders->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: var(--light-green);"></i>
                        <p class="text-muted mt-3">No completed deliveries yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Seller</th>
                                    <th>Buyer</th>
                                    <th>Items</th>
                                    <th>Weight</th>
                                    <th>Amount</th>
                                    <th>Delivered On</th>
                                    <th>ETA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>
                                        <i class="fas fa-store text-muted"></i>
                                        {{ $order->seller->shop_name }}
                                    </td>
                                    <td>
                                        <i class="fas fa-user text-muted"></i>
                                        {{ $order->buyer->name }}
                                    </td>
                                    <td>{{ $order->items->count() }} item(s)</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ number_format($order->total_weight_kg, 2) }}kg
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: var(--primary-green);">
                                            ₱{{ number_format($order->total, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <small>{{ $order->updated_at->format('M d, Y') }}</small><br>
                                        <small class="text-muted">{{ $order->updated_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($order->eta)
                                            <span class="badge bg-secondary">{{ $order->eta }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $completedOrders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-light {
        transition: all 0.3s;
    }
    .bg-light:hover {
        background-color: var(--pale-green) !important;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>
@endpush
@endsection
