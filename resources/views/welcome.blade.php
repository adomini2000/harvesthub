@extends('layouts.app')

@section('title', 'Welcome to HarvestHub')

@section('content')
<div class="text-center py-5">
    <div class="mb-5">
        <i class="fas fa-seedling" style="font-size: 5rem; color: var(--primary-green);"></i>
        <h1 class="display-3 fw-bold mt-3" style="color: var(--dark-green);">Welcome to HarvestHub</h1>
        <p class="lead text-muted">Fresh from Farm to Table - Connecting Farmers, Buyers, and Riders</p>
    </div>

    <div class="row mt-5">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-shopping-cart" style="font-size: 3rem; color: var(--primary-green);"></i>
                    <h4 class="mt-3">For Buyers</h4>
                    <p class="text-muted">Browse fresh products, earn points, and get fast delivery</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-store" style="font-size: 3rem; color: var(--primary-green);"></i>
                    <h4 class="mt-3">For Sellers</h4>
                    <p class="text-muted">Manage your shop, products, and orders easily</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center p-4">
                    <i class="fas fa-motorcycle" style="font-size: 3rem; color: var(--primary-green);"></i>
                    <h4 class="mt-3">For Riders</h4>
                    <p class="text-muted">Accept deliveries, earn money, set your own schedule</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
            <i class="fas fa-user-plus"></i> Get Started
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-sign-in-alt"></i> Login
        </a>
    </div>
</div>
@endsection
