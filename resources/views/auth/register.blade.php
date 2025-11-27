@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-plus"></i> Create Your HarvestHub Account
            </div>
            <div class="card-body p-4">
                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone (Optional) -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number <small class="text-muted">(Optional)</small></label>
                        <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                               name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Address (Optional) -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Address <small class="text-muted">(Optional)</small></label>
                        <textarea id="address" class="form-control @error('address') is-invalid @enderror"
                                  name="address" rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required>
                        <small class="text-muted">Minimum 8 characters</small>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" type="password" class="form-control"
                               name="password_confirmation" required>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Register As</label>
                        <select id="role" class="form-select @error('role') is-invalid @enderror"
                                name="role" required onchange="toggleRoleFields()">
                            <option value="">Select Role</option>
                            <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>🛒 Buyer - Browse & Purchase</option>
                            <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>🏪 Seller - Sell Products</option>
                            <option value="rider" {{ old('role') == 'rider' ? 'selected' : '' }}>🏍️ Rider - Deliver Orders</option>
                        </select>
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Approval Warning (shown for seller/rider) -->
                    <div id="approval-warning" style="display: none;">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Admin Approval Required:</strong>
                            Seller and Rider accounts require admin approval before you can access the platform.
                            This usually takes 24-48 hours.
                        </div>
                    </div>

                    <!-- Seller Fields -->
                    <div id="seller-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="shop_name" class="form-label">Shop Name</label>
                            <input id="shop_name" type="text" class="form-control"
                                   name="shop_name" value="{{ old('shop_name') }}">
                        </div>
                        <div class="mb-3">
                            <label for="shop_description" class="form-label">Shop Description</label>
                            <textarea id="shop_description" class="form-control" name="shop_description"
                                      rows="3">{{ old('shop_description') }}</textarea>
                        </div>
                    </div>

                    <!-- Rider Fields -->
                    <div id="rider-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="vehicle_type" class="form-label">Vehicle Type</label>
                            <select id="vehicle_type" class="form-select" name="vehicle_type">
                                <option value="bike" {{ old('vehicle_type') == 'bike' ? 'selected' : '' }}>🚲 Bike (Max 10kg)</option>
                                <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>🏍️ Motorcycle (Max 25kg)</option>
                                <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>🚗 Car (Max 50kg)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="license_number" class="form-label">Driver's License Number <small class="text-muted">(Optional)</small></label>
                            <input id="license_number" type="text" class="form-control"
                                   name="license_number" value="{{ old('license_number') }}">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus"></i> Create Account
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <p class="mb-0">Already have an account?
                            <a href="{{ route('login') }}" style="color: var(--primary-green);">Login here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleRoleFields() {
        const role = document.getElementById('role').value;
        const sellerFields = document.getElementById('seller-fields');
        const riderFields = document.getElementById('rider-fields');
        const approvalWarning = document.getElementById('approval-warning');

        // Hide all fields first
        sellerFields.style.display = 'none';
        riderFields.style.display = 'none';
        approvalWarning.style.display = 'none';

        // Show relevant fields based on role
        if (role === 'seller') {
            sellerFields.style.display = 'block';
            approvalWarning.style.display = 'block';
        } else if (role === 'rider') {
            riderFields.style.display = 'block';
            approvalWarning.style.display = 'block';
        }
    }

    // Show fields on page load if role is already selected
    document.addEventListener('DOMContentLoaded', function() {
        toggleRoleFields();
    });
</script>
@endpush
@endsection
