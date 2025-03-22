<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'compare_price',
        'category_id',
        'sub_category_id',
        'brand_id',
        'is_featured',
        'sku',
        'barcode',
        'track_qty',
        'qty',
        'status',
        'tag',
    ];

    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
    public function productcategory(){
        return $this->belongsTo(Category::class,'product_category_id','id');

    }
    public function product_images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');

    }

    public function productDetail(){
        return $this->hasMany(ProductDetail::class,'product_id','id');
    }
    public function productComment(){
        return $this->hasMany(ProductComment::class,'product_id','id');
    }

    public function orderDetail(){
        return $this->hasMany(OrderItem::Detail::class,'product_id','id');
    }

}
