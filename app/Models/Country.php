<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function shippingCharges()
    {
        return $this->hasMany(ShippingCharge::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'country_id', 'id');
    }
}
