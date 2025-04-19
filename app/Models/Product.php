<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

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
    ];
    use Searchable;

    public function searchableAs(): string
    {
        return 'products';
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
        ];
    }
    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id','id');
    }
    public function productcategory(){
        return $this->belongsTo(Category::class,'category_id','id');

    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }
    public function productImages(): \Illuminate\Database\Eloquent\Relations\HasMany
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
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }


}
