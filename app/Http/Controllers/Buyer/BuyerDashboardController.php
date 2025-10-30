<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class BuyerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->role !== 'buyer') {
            return redirect()->route('dashboard');
        }

        // Get or create buyer points
        $points = $user->buyerPoints;
        if (!$points) {
            $points = \App\Models\BuyerPoint::create([
                'buyer_id' => $user->id,
                'total_points' => 0,
            ]);
        }

        // Get recent orders
        $orders = Order::where('buyer_id', $user->id)
            ->with(['seller', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Get featured products (latest active products)
        $featuredProducts = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->with('seller.user')
            ->latest()
            ->take(8)
            ->get();

        return view('buyer.dashboard', compact('user', 'points', 'orders', 'featuredProducts'));
    }
}
