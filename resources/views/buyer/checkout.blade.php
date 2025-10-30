@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2><i class="fas fa-credit-card"></i> Checkout</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('buyer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('buyer.cart.index') }}">Cart</a></li>
                <li class="breadcrumb-item active">Checkout</li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('buyer.orders.place') }}" method="POST">
        @csrf
        <div class="row">
            <!-- Left Column - Order Details -->
            <div class="col-lg-8 mb-4">
                <!-- Delivery Address -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-map-marker-alt"></i> Delivery Address
                        </div>
                        <div class="card-body">
                <div class="mb-3">
                    <label for="delivery_address" class="form-label">Complete Address *</label>
                    <textarea id="delivery_address" name="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror"
                                rows="3" required placeholder="House/Unit No., Street, Barangay, City">{{ old('delivery_address', auth()->user()->address) }}</textarea>
                    @error('delivery_address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    @if(auth()->user()->address)
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Using your default address. You can edit it above if needed.
                    </small>
                @else
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> No default address set. <a href="{{ route('buyer.dashboard') }}">Add one in your profile</a> for faster checkout next time!
                    </small>
                @endif
            </div>
        </div>
    </div>

                <!-- Orders by Seller -->
                @foreach($ordersBySeller as $sellerId => $orderData)
                <div class="card mb-4">
                    <div class="card-header" style="background: var(--light-green); color: var(--text-dark);">
                        <i class="fas fa-store"></i> {{ $orderData['seller']->shop_name }}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Weight</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderData['items'] as $item)
                                    <tr>
                                        <td>{{ $item['product']->name }}</td>
                                        <td>₱{{ number_format($item['price'], 2) }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>{{ number_format($item['weight'], 2) }}kg</td>
                                        <td class="text-end">₱{{ number_format($item['subtotal'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                        <td class="text-end"><strong>₱{{ number_format($orderData['subtotal'], 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end">Total Weight:</td>
                                        <td class="text-end">{{ number_format($orderData['weight'], 2) }}kg</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Right Column - Payment Summary -->
            <div class="col-lg-4 mb-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fas fa-receipt"></i> Payment Summary
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong>₱{{ number_format($subtotal, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Weight:</span>
                            <strong>{{ number_format($totalWeight, 2) }}kg</strong>
                        </div>
                        <hr>

                        <!-- Points Redemption -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-coins" style="color: var(--primary-green);"></i>
                                Use Points (Available: {{ number_format($points->total_points ?? 0, 2) }})
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" name="use_points" id="use_points" class="form-control"
                                       min="0" max="{{ $points->total_points ?? 0 }}" step="0.01"
                                       value="0" onchange="calculateTotal()">
                            </div>
                            <small class="text-muted">1 point = ₱1 discount</small>
                        </div>

                        <div class="d-flex justify-content-between mb-2" id="discount-row" style="display: none !important;">
                            <span style="color: var(--primary-green);">Points Discount:</span>
                            <strong style="color: var(--primary-green);">-₱<span id="discount-amount">0.00</span></strong>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <h5>Total to Pay:</h5>
                            <h5 style="color: var(--primary-green);">₱<span id="final-total">{{ number_format($subtotal, 2) }}</span></h5>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                You'll earn <strong id="earn-points">{{ number_format($subtotal * 0.02, 2) }}</strong> points (2% cashback) from this order!
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-check-circle"></i> Place Order
                            </button>
                            <a href="{{ route('buyer.cart.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Cart
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="card">
                    <div class="card-body" style="background: var(--pale-green);">
                        <h6 class="mb-3"><i class="fas fa-info-circle"></i> Order Information</h6>
                        <ul class="small mb-0">
                            <li>Orders will be grouped by seller</li>
                            <li>Each seller will prepare your items</li>
                            <li>A rider will be assigned for delivery</li>
                            <li>Track your order status in real-time</li>
                            <li>Earn points for rating sellers</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const subtotal = {{ $subtotal }};
    const maxPoints = {{ $points->total_points ?? 0 }};

    function calculateTotal() {
        const usePoints = parseFloat(document.getElementById('use_points').value) || 0;

        // Validate points
        if (usePoints > maxPoints) {
            document.getElementById('use_points').value = maxPoints;
            calculateTotal();
            return;
        }

        if (usePoints < 0) {
            document.getElementById('use_points').value = 0;
            calculateTotal();
            return;
        }

        // Calculate discount (can't exceed subtotal)
        const discount = Math.min(usePoints, subtotal);
        const finalTotal = subtotal - discount;
        const earnPoints = finalTotal * 0.02;

        // Update display
        document.getElementById('discount-amount').textContent = discount.toFixed(2);
        document.getElementById('final-total').textContent = finalTotal.toFixed(2);
        document.getElementById('earn-points').textContent = earnPoints.toFixed(2);

        // Show/hide discount row
        const discountRow = document.getElementById('discount-row');
        if (discount > 0) {
            discountRow.style.display = 'flex';
        } else {
            discountRow.style.display = 'none';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();
    });
</script>
@endpush
@endsection
