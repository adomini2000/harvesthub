<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'seller_id',
        'rider_id',
        'order_number',
        'subtotal',
        'points_discount',
        'total',
        'total_weight_kg',
        'status',
        'delivery_address',
        'eta',
        'payment_method',
        'payment_status',
        'delivery_fee',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'points_discount' => 'decimal:2',
        'total' => 'decimal:2',
        'total_weight_kg' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
    ];

    // Relationships
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    // Helper method to generate order number
    public static function generateOrderNumber()
    {
        return 'ORD-' . strtoupper(uniqid());
    }

    // NEW: Helper method to get payment method display name
    public function getPaymentMethodNameAttribute()
    {
        $methods = [
            'card' => 'Credit/Debit Card',
            'gcash' => 'GCash',
            'cod' => 'Cash on Delivery'
        ];

        return $methods[$this->payment_method] ?? 'Unknown';
    }

    // NEW: Helper method to get payment status badge color
    public function getPaymentStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger'
        ];

        return $colors[$this->payment_status] ?? 'secondary';
    }
}
