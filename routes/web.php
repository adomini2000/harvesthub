<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Buyer\BuyerDashboardController;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Rider\RiderDashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Buyer\ProductController as BuyerProductController;
use App\Http\Controllers\Buyer\CartController;
use App\Http\Controllers\Buyer\OrderController as BuyerOrderController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
})->name('home'); // ADD NAME

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/pending-users', [AdminController::class, 'pendingUsers'])->name('admin.pending');
    Route::post('/approve-user/{id}', [AdminController::class, 'approveUser'])->name('admin.approve');
    Route::post('/reject-user/{id}', [AdminController::class, 'rejectUser'])->name('admin.reject');
    Route::get('/all-users', [AdminController::class, 'allUsers'])->name('admin.users');
    Route::post('/toggle-user/{id}', [AdminController::class, 'toggleUserStatus'])->name('admin.toggle');
});

// Authentication Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Pending Approval Page - ADD THIS
Route::get('/pending-approval', function () {
    return view('pending-approval');
})->middleware('auth')->name('pending.approval');

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Buyer Routes - ADD 'approved' MIDDLEWARE
Route::prefix('buyer')->name('buyer.')->middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', [BuyerDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::patch('/profile', [\App\Http\Controllers\Buyer\ProfileController::class, 'update'])->name('profile.update');

    // Product Browsing
    Route::get('/products', [BuyerProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [BuyerProductController::class, 'show'])->name('products.show');

    // Shopping Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Orders
    Route::get('/orders', [BuyerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [BuyerOrderController::class, 'show'])->name('orders.show');
    Route::get('/checkout', [BuyerOrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [BuyerOrderController::class, 'placeOrder'])->name('orders.place');

    // Rating
    Route::post('/orders/{order}/rate', [BuyerOrderController::class, 'rate'])->name('orders.rate');
});

// Seller Routes - ADD 'approved' MIDDLEWARE
Route::prefix('seller')->name('seller.')->middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::patch('/profile', [\App\Http\Controllers\Seller\ProfileController::class, 'update'])->name('profile.update');

    // Product Management
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Order Management
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Rider Routes - ADD 'approved' MIDDLEWARE
Route::prefix('rider')->name('rider.')->middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', [RiderDashboardController::class, 'index'])->name('dashboard');

    // Delivery History
    Route::get('/history', [\App\Http\Controllers\Rider\OrderController::class, 'history'])->name('history');

    // Order Management
    Route::patch('/orders/{order}/accept', [\App\Http\Controllers\Rider\OrderController::class, 'accept'])->name('orders.accept');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Rider\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/eta', [\App\Http\Controllers\Rider\OrderController::class, 'setEta'])->name('orders.setEta');

    // Status Management
    Route::patch('/status', [\App\Http\Controllers\Rider\StatusController::class, 'update'])->name('status.update');
});
