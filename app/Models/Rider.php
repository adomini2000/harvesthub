<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'max_capacity_kg',
        'status',
        'total_earnings',
    ];

    protected $casts = [
        'max_capacity_kg' => 'decimal:2',
        'total_earnings' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'normal';
    }

    public function canCarry($weight)
    {
        return $weight <= $this->max_capacity_kg;
    }

     public function addEarnings($amount)
    {
        $this->total_earnings += $amount;
        $this->save();
    }
}
