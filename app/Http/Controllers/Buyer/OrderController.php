<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\BuyerPoint;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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

        $orders = Order::where('buyer_id', $user->id)
            ->with(['seller', 'rider.user', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('buyer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $user = auth()->user();

        if ($user->role !== 'buyer' || $order->buyer_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $order->load(['seller', 'rider.user', 'items.product', 'rating']);

        return view('buyer.orders.show', compact('order'));
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('buyer.cart.index')->with('error', 'Your cart is empty');
        }

        // Group cart items by seller
        $ordersBySeller = [];
        $totalWeight = 0;
        $subtotal = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            if (!$product || !$product->is_active || $product->stock < $item['quantity']) {
                return redirect()->route('buyer.cart.index')
                    ->with('error', 'Some products are no longer available');
            }

            $sellerId = $product->seller_id;

            if (!isset($ordersBySeller[$sellerId])) {
                $ordersBySeller[$sellerId] = [
                    'seller' => $product->seller,
                    'items' => [],
                    'subtotal' => 0,
                    'weight' => 0,
                ];
            }

            $itemTotal = $product->price * $item['quantity'];
            $itemWeight = $product->weight_kg * $item['quantity'];

            $ordersBySeller[$sellerId]['items'][] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $itemTotal,
                'weight' => $itemWeight,
            ];

            $ordersBySeller[$sellerId]['subtotal'] += $itemTotal;
            $ordersBySeller[$sellerId]['weight'] += $itemWeight;

            $subtotal += $itemTotal;
            $totalWeight += $itemWeight;
        }

        $points = $user->buyerPoints;

        return view('buyer.checkout', compact('ordersBySeller', 'subtotal', 'totalWeight', 'points'));
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string|max:500',
            'use_points' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:card,gcash,cod',
        ]);

        $user = auth()->user();
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('buyer.cart.index')->with('error', 'Your cart is empty');
        }

        try {
            DB::beginTransaction();

            // Group items by seller
            $ordersBySeller = [];

            foreach ($cart as $productId => $item) {
                $product = Product::lockForUpdate()->find($productId);

                if (!$product || $product->stock < $item['quantity']) {
                    throw new \Exception('Product ' . $product->name . ' is out of stock');
                }

                $sellerId = $product->seller_id;

                if (!isset($ordersBySeller[$sellerId])) {
                    $ordersBySeller[$sellerId] = [
                        'items' => [],
                        'subtotal' => 0,
                        'weight' => 0,
                    ];
                }

                $itemTotal = $product->price * $item['quantity'];
                $itemWeight = $product->weight_kg * $item['quantity'];

                $ordersBySeller[$sellerId]['items'][] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'weight' => $itemWeight,
                ];

                $ordersBySeller[$sellerId]['subtotal'] += $itemTotal;
                $ordersBySeller[$sellerId]['weight'] += $itemWeight;
            }

            // Calculate points discount
            $usePoints = $request->use_points ?? 0;
            $buyerPoints = $user->buyerPoints;

            if ($usePoints > 0 && $usePoints > $buyerPoints->total_points) {
                throw new \Exception('Insufficient points');
            }

            $pointsDiscount = $usePoints;
            $totalOrderValue = array_sum(array_column($ordersBySeller, 'subtotal'));

            if ($pointsDiscount > $totalOrderValue) {
                $pointsDiscount = $totalOrderValue;
            }

            // Distribute discount proportionally across orders
            $discountPerOrder = [];
            foreach ($ordersBySeller as $sellerId => $orderData) {
                $proportion = $orderData['subtotal'] / $totalOrderValue;
                $discountPerOrder[$sellerId] = $pointsDiscount * $proportion;
            }

            // Create orders for each seller
            foreach ($ordersBySeller as $sellerId => $orderData) {
                $orderDiscount = $discountPerOrder[$sellerId];
                $orderTotal = $orderData['subtotal'] - $orderDiscount;

                $order = Order::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $sellerId,
                    'order_number' => Order::generateOrderNumber(),
                    'subtotal' => $orderData['subtotal'],
                    'points_discount' => $orderDiscount,
                    'total' => $orderTotal,
                    'total_weight_kg' => $orderData['weight'],
                    'status' => 'ordered',
                    'delivery_address' => $validated['delivery_address'],
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'paid',
                ]);

                // Create order items and reduce stock
                foreach ($orderData['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'weight_kg' => $item['weight'],
                    ]);

                    // Reduce product stock
                    $item['product']->decrement('stock', $item['quantity']);
                }
            }

            // Deduct points if used
            if ($usePoints > 0) {
                $buyerPoints->deductPoints($usePoints);
            }

            // Calculate and add 2% cashback points
            $earnedPoints = $totalOrderValue * 0.02;
            $buyerPoints->addPoints($earnedPoints);

            // Clear cart
            session()->forget('cart');

            DB::commit();

            $paymentMethodText = [
                'card' => 'Credit/Debit Card',
                'gcash' => 'GCash',
                'cod' => 'Cash on Delivery'
            ];

            return redirect()->route('buyer.orders.index')
                ->with('success', 'Orders placed successfully via ' . $paymentMethodText[$validated['payment_method']] . '! You earned ' . number_format($earnedPoints, 2) . ' points!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Order failed: ' . $e->getMessage());
        }
    }

    public function rate(Request $request, Order $order)
    {
        $user = auth()->user();

        if ($user->role !== 'buyer' || $order->buyer_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Check if order is delivered
        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'You can only rate delivered orders');
        }

        // Check if already rated
        if ($order->rating) {
            return redirect()->back()->with('error', 'You have already rated this order');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Create rating
        $rating = \App\Models\Rating::create([
            'order_id' => $order->id,
            'buyer_id' => $user->id,
            'seller_id' => $order->seller_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'bonus_points' => 10,
        ]);

        // Update seller's average rating
        $seller = $order->seller;
        $totalRatings = $seller->ratings()->count();
        $averageRating = $seller->ratings()->avg('rating');

        $seller->update([
            'rating' => $averageRating,
            'total_ratings' => $totalRatings,
        ]);

        // Award bonus points to buyer
        $buyerPoints = $user->buyerPoints;
        $buyerPoints->addPoints(10);

        return redirect()->route('buyer.orders.index')->with('success', 'Thank you for your rating! You earned 10 bonus points!');
    }
}
