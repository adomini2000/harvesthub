<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class RiderDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->role !== 'rider') {
            return redirect()->route('dashboard');
        }

        $rider = $user->rider;

        // Available orders (ready for pickup and within rider's capacity)
        $availableOrders = Order::where('status', 'ready_for_pickup')
            ->whereNull('rider_id')
            ->where('total_weight_kg', '<=', $rider->max_capacity_kg)
            ->with(['seller', 'buyer', 'items'])
            ->latest()
            ->get();

        // Rider's current orders (active deliveries)
        $myOrders = Order::where('rider_id', $rider->id)
            ->whereIn('status', ['picked_up', 'out_for_delivery'])
            ->with(['seller', 'buyer', 'items'])
            ->latest()
            ->get();

        // Completed today count
        $completedToday = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->whereDate('updated_at', today())
            ->count();

        return view('rider.dashboard', compact('user', 'rider', 'availableOrders', 'myOrders', 'completedToday'));
    }
}
