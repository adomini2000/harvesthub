<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->role !== 'seller') {
            return redirect()->route('dashboard');
        }

        $seller = $user->seller;
        $products = $seller->products()->latest()->get();
        $orders = $seller->orders()->latest()->take(10)->get();

        return view('seller.dashboard', compact('user', 'seller', 'products', 'orders'));
    }
}
