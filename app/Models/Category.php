<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'product_categories';
    protected $fillable = ['name', 'slug', 'status', 'image'];
    public function products(){
        return $this->hasMany(Product::class,'product_category_id','id');

    }

    public function sub_category(){
        return $this->hasMany(SubCategory::class,'category_id','id');
    }
}
