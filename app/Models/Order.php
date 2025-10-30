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
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'points_discount' => 'decimal:2',
        'total' => 'decimal:2',
        'total_weight_kg' => 'decimal:2',
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
}
