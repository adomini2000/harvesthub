@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <i class="fas fa-user-shield"></i> Admin Dashboard
            </div>
            <div class="card-body">
                <h3>Welcome, Admin!</h3>
                <p class="text-muted">Manage users and monitor platform activity</p>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">
                                            <i class="fas fa-user-clock text-warning"></i>
                                            Pending Approvals
                                        </h5>
                                        <p class="text-muted mb-0">Review and approve new sellers and riders</p>
                                    </div>
                                    <div class="text-end">
                                        <h2 class="mb-0">
                                            {{ \App\Models\User::where('is_approved', false)->whereIn('role', ['seller', 'rider'])->count() }}
                                        </h2>
                                    </div>
                                </div>
                                <a href="{{ route('admin.pending') }}" class="btn btn-warning mt-3 w-100">
                                    <i class="fas fa-eye"></i> View Pending Users
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">
                                            <i class="fas fa-users text-primary"></i>
                                            All Users
                                        </h5>
                                        <p class="text-muted mb-0">Manage all registered users</p>
                                    </div>
                                    <div class="text-end">
                                        <h2 class="mb-0">
                                            {{ \App\Models\User::where('role', '!=', 'admin')->count() }}
                                        </h2>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users') }}" class="btn btn-primary mt-3 w-100">
                                    <i class="fas fa-list"></i> View All Users
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-shopping-cart fa-3x text-info mb-3"></i>
                                <h5>{{ \App\Models\User::where('role', 'buyer')->count() }}</h5>
                                <p class="text-muted mb-0">Buyers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-store fa-3x text-success mb-3"></i>
                                <h5>{{ \App\Models\User::where('role', 'seller')->count() }}</h5>
                                <p class="text-muted mb-0">Sellers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-motorcycle fa-3x text-warning mb-3"></i>
                                <h5>{{ \App\Models\User::where('role', 'rider')->count() }}</h5>
                                <p class="text-muted mb-0">Riders</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <h5>{{ \App\Models\User::where('is_approved', true)->where('role', '!=', 'admin')->count() }}</h5>
                                <p class="text-muted mb-0">Approved</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
