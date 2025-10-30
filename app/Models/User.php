<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function seller()
    {
        return $this->hasOne(Seller::class);
    }

    public function rider()
    {
        return $this->hasOne(Rider::class);
    }

    public function buyerPoints()
    {
        return $this->hasOne(BuyerPoint::class, 'buyer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'buyer_id');
    }

    // Helper methods
    public function isBuyer()
    {
        return $this->role === 'buyer';
    }

    public function isSeller()
    {
        return $this->role === 'seller';
    }

    public function isRider()
    {
        return $this->role === 'rider';
    }
}
