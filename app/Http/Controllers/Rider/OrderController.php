<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function accept(Order $order)
    {
        $user = auth()->user();

        if ($user->role !== 'rider') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $rider = $user->rider;

        // Check if rider is available
        if ($rider->status !== 'normal') {
            return redirect()->back()->with('error', 'You must be available to accept orders');
        }

        // Check if order is available
        if ($order->status !== 'ready_for_pickup' || $order->rider_id !== null) {
            return redirect()->back()->with('error', 'Order is no longer available');
        }

        // Check weight capacity
        if ($order->total_weight_kg > $rider->max_capacity_kg) {
            return redirect()->back()->with('error', 'Order exceeds your vehicle capacity');
        }

        // Accept the order
        $order->update([
            'rider_id' => $rider->id,
            'status' => 'picked_up',
        ]);

        // Update rider status to busy
        $rider->update(['status' => 'busy']);

        return redirect()->route('rider.dashboard')->with('success', 'Order accepted successfully!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = auth()->user();

            if ($user->role !== 'rider' || $order->rider_id !== $user->rider->id) {
                return redirect()->back()->with('error', 'Unauthorized access');
            }

        $validated = $request->validate([
            'status' => 'required|in:out_for_delivery,delivered',
        ]);

        $order->update(['status' => $validated['status']]);

        // If delivered, set rider back to normal and add earnings
            if ($validated['status'] == 'delivered') {
                $rider = $user->rider;
                $rider->update(['status' => 'normal']);

        // Add delivery fee to rider's earnings (₱15.00 per delivery)
            $rider->addEarnings($order->delivery_fee);
        }

        return redirect()->route('rider.dashboard')->with('success', 'Order status updated!');
    }

    public function setEta(Request $request, Order $order)
    {
        $user = auth()->user();

        if ($user->role !== 'rider' || $order->rider_id !== $user->rider->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'eta' => 'required|string|max:50',
        ]);

        $order->update(['eta' => $validated['eta']]);

        return redirect()->route('rider.dashboard')->with('success', 'ETA updated!');
    }
    public function history()
{
        $user = auth()->user();

        if ($user->role !== 'rider') {
            return redirect()->back()->with('error', 'Unauthorized access');
    }

        $rider = $user->rider;

        // Get all completed orders
        $completedOrders = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->with(['seller', 'buyer', 'items'])
            ->latest()
            ->paginate(15);

        // Calculate stats
        $totalDeliveries = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->count();

        $totalEarnings = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->sum('total');

        $totalDistance = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->sum('total_weight_kg'); // Using weight as proxy for distance

        $completedToday = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->whereDate('updated_at', today())
            ->count();

        $completedThisWeek = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $completedThisMonth = Order::where('rider_id', $rider->id)
            ->where('status', 'delivered')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        return view('rider.history', compact(
            'rider',
            'completedOrders',
            'totalDeliveries',
            'totalEarnings',
            'totalDistance',
            'completedToday',
            'completedThisWeek',
            'completedThisMonth'
        ));
    }
}
