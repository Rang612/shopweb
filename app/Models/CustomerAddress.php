<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    use HasFactory;
    protected $table='customer_addresses';
    protected $fillable=[
        'user_id',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'country_id',
        'district',
        'ward',
        'street',
        'house_number',
        'zip'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
