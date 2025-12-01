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

    <form id="checkoutForm" action="{{ route('buyer.orders.place') }}" method="POST">
        @csrf
        <input type="hidden" name="payment_method" id="payment_method_input">

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
                            <button type="button" class="btn btn-primary btn-lg" onclick="openPaymentModal()">
                                <i class="fas fa-check-circle"></i> Proceed to Payment
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

<!-- Payment Method Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-green); color: white;">
                <h5 class="modal-title"><i class="fas fa-credit-card"></i> Select Payment Method</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <h4 style="color: var(--primary-green);">Total Amount: ₱<span id="modal-total">0.00</span></h4>
                </div>

                <div class="payment-options">
                    <!-- Credit/Debit Card -->
                    <div class="payment-option mb-3" onclick="selectPayment('card')">
                        <input type="radio" name="payment_option" id="payment_card" value="card" class="payment-radio">
                        <label for="payment_card" class="payment-label">
                            <div class="d-flex align-items-center">
                                <div class="payment-icon">
                                    <i class="fas fa-credit-card fa-2x" style="color: #4A90E2;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong>Credit / Debit Card</strong>
                                    <p class="mb-0 small text-muted">Visa, Mastercard, or other cards</p>
                                </div>
                                <div class="payment-check">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- GCash -->
                    <div class="payment-option mb-3" onclick="selectPayment('gcash')">
                        <input type="radio" name="payment_option" id="payment_gcash" value="gcash" class="payment-radio">
                        <label for="payment_gcash" class="payment-label">
                            <div class="d-flex align-items-center">
                                <div class="payment-icon">
                                    <i class="fas fa-mobile-alt fa-2x" style="color: #007DFF;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong>GCash</strong>
                                    <p class="mb-0 small text-muted">Pay using your GCash wallet</p>
                                </div>
                                <div class="payment-check">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Cash on Delivery -->
                    <div class="payment-option mb-3" onclick="selectPayment('cod')">
                        <input type="radio" name="payment_option" id="payment_cod" value="cod" class="payment-radio">
                        <label for="payment_cod" class="payment-label">
                            <div class="d-flex align-items-center">
                                <div class="payment-icon">
                                    <i class="fas fa-money-bill-wave fa-2x" style="color: #2ECC71;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong>Cash on Delivery</strong>
                                    <p class="mb-0 small text-muted">Pay when you receive your order</p>
                                </div>
                                <div class="payment-check">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="alert alert-warning mt-3">
                    <small><i class="fas fa-info-circle"></i> <strong>Note:</strong> This is a mock payment system. No actual payment will be processed.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="confirmPayment()" disabled id="confirmPaymentBtn">
                    <i class="fas fa-check"></i> Confirm & Place Order
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .payment-options {
        max-width: 500px;
        margin: 0 auto;
    }

    .payment-option {
        position: relative;
        cursor: pointer;
    }

    .payment-radio {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .payment-label {
        display: block;
        padding: 20px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        margin: 0;
    }

    .payment-label:hover {
        border-color: var(--primary-green);
        background: var(--pale-green);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .payment-radio:checked + .payment-label {
        border-color: var(--primary-green);
        background: var(--pale-green);
        box-shadow: 0 0 0 3px rgba(124, 179, 66, 0.2);
    }

    .payment-icon {
        width: 60px;
        text-align: center;
        margin-right: 15px;
    }

    .payment-check {
        color: var(--primary-green);
        font-size: 1.5rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .payment-radio:checked + .payment-label .payment-check {
        opacity: 1;
    }

    .payment-label strong {
        font-size: 1.1rem;
        display: block;
        margin-bottom: 5px;
    }
</style>

<script>
    const subtotal = {{ $subtotal }};
    const maxPoints = {{ $points->total_points ?? 0 }};
    let paymentModal;
    let selectedPaymentMethod = null;

    document.addEventListener('DOMContentLoaded', function() {
        paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        calculateTotal();
    });

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

    function openPaymentModal() {
        // Validate delivery address
        const deliveryAddress = document.getElementById('delivery_address').value.trim();
        if (!deliveryAddress) {
            alert('Please enter your delivery address');
            document.getElementById('delivery_address').focus();
            return;
        }

        // Update modal total
        const finalTotal = document.getElementById('final-total').textContent;
        document.getElementById('modal-total').textContent = finalTotal;

        // Reset payment selection
        selectedPaymentMethod = null;
        document.querySelectorAll('.payment-radio').forEach(radio => {
            radio.checked = false;
        });
        document.getElementById('confirmPaymentBtn').disabled = true;

        // Show modal
        paymentModal.show();
    }

    function selectPayment(method) {
        selectedPaymentMethod = method;
        document.getElementById('payment_' + method).checked = true;
        document.getElementById('confirmPaymentBtn').disabled = false;
    }

    function confirmPayment() {
        if (!selectedPaymentMethod) {
            alert('Please select a payment method');
            return;
        }

        // Set payment method in hidden input
        document.getElementById('payment_method_input').value = selectedPaymentMethod;

        // Show processing message
        const btn = document.getElementById('confirmPaymentBtn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        btn.disabled = true;

        // Simulate payment processing
        setTimeout(() => {
            // Submit the form
            document.getElementById('checkoutForm').submit();
        }, 1500);
    }
</script>
@endsection
