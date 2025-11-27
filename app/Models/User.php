<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property bool $is_approved
 * @property string|null $phone
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Seller|null $seller
 * @property-read \App\Models\Rider|null $rider
 * @property-read \App\Models\BuyerPoint|null $buyerPoints
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Order> $orders
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Rating> $ratings
 *
 * @method bool isApproved()
 * @method bool isAdmin()
 * @method bool isBuyer()
 * @method bool isSeller()
 * @method bool isRider()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',
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
            'is_approved' => 'boolean',
        ];
    }

    // Helper methods
    public function isApproved()
    {
        return $this->is_approved;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

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
}
