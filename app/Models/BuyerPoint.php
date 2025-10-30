<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'total_points',
    ];

    protected $casts = [
        'total_points' => 'decimal:2',
    ];

    // Relationships
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Helper methods
    public function addPoints($amount)
    {
        $this->total_points += $amount;
        $this->save();
    }

    public function deductPoints($amount)
    {
        if ($this->total_points >= $amount) {
            $this->total_points -= $amount;
            $this->save();
            return true;
        }
        return false;
    }
}
