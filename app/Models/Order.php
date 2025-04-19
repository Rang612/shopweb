<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $fillable=[
        'user_id',
        'subtotal',
        'shipping',
        'coupon_code',
        'coupon_code_id',
        'discount',
        'grand_total',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'country_id',
        'district',
        'ward',
        'street',
        'house_number',
        'zip',
        'notes',
        'payment_status',
        'payment_method_id'
    ];
    public function orderDetail(){
        return $this->hasMany(OrderItem::class,'order_id','id');
    }

    public function city()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
