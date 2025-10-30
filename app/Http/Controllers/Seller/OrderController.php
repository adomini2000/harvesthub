<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $user = auth()->user();

        if ($user->role !== 'seller' || $order->seller_id !== $user->seller->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'status' => 'required|in:preparing,ready_for_pickup',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('seller.dashboard')->with('success', 'Order status updated!');
    }

    public function show(Order $order)
    {
        $user = auth()->user();

        if ($user->role !== 'seller' || $order->seller_id !== $user->seller->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        return view('seller.orders.show', compact('order'));
    }
}
