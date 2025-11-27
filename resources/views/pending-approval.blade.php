@extends('layouts.app')

@section('title', 'Pending Approval')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-clock"></i> Account Pending Approval
            </div>
            <div class="card-body text-center p-5">
                <div class="mb-4">
                    <i class="fas fa-hourglass-half" style="font-size: 64px; color: #ff9800;"></i>
                </div>

                <h3 class="mb-3">Thank you for registering!</h3>

                <p class="lead">
                    Your account has been created as a <strong>{{ ucfirst(auth()->user()->role) }}</strong>.
                </p>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Your account is currently pending admin approval. This is to ensure the safety and quality of our platform.
                </div>

                <p class="text-muted">
                    You will receive access once an administrator reviews and approves your account.
                    This usually takes <strong>24-48 hours</strong>.
                </p>

                <hr class="my-4">

                <div class="mb-3">
                    <p><strong>What happens next?</strong></p>
                    <ul class="list-unstyled text-start" style="max-width: 500px; margin: 0 auto;">
                        <li class="mb-2"><i class="fas fa-check text-success"></i> Admin reviews your registration</li>
                        <li class="mb-2"><i class="fas fa-check text-success"></i> You'll be notified once approved</li>
                        <li class="mb-2"><i class="fas fa-check text-success"></i> Login again to access your dashboard</li>
                    </ul>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
