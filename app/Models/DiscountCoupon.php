<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    use HasFactory;
    protected $table = 'discount_coupons';
    protected $fillable = [
        'code',
        'name',
        'description',
        'max_uses',
        'max_uses_user',
        'type',
        'discount_amount',
        'min_amount',
        'status',
        'starts_at',
        'expires_at',
    ];

    public function usersUsed()
    {
        return $this->belongsToMany(User::class, 'coupon_user', 'coupon_id', 'user_id')
            ->withTimestamps()
            ->withPivot('used_at');
    }

}
