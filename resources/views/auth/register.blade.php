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

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required>
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
                            <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                            <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                            <option value="rider" {{ old('role') == 'rider' ? 'selected' : '' }}>Rider</option>
                        </select>
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
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
                                <option value="bike">Bike (Max 10kg)</option>
                                <option value="motorcycle">Motorcycle (Max 25kg)</option>
                                <option value="car">Car (Max 50kg)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus"></i> Register
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

        sellerFields.style.display = 'none';
        riderFields.style.display = 'none';

        if (role === 'seller') {
            sellerFields.style.display = 'block';
        } else if (role === 'rider') {
            riderFields.style.display = 'block';
        }
    }

    // Show fields on page load if role is already selected
    document.addEventListener('DOMContentLoaded', function() {
        toggleRoleFields();
    });
</script>
@endpush
@endsection
