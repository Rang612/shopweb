<?php

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\SubCategory;

function getCategories(){
       return Category::orderBy('name', 'ASC')
           ->with('sub_category')
           ->where('status', 1)
           ->where('showHome','Yes')
           ->get();
    }

function getProductImage($productId)
{
    return \App\Models\ProductImage::where('product_id', $productId)->first();
}

function staticPages(){
    $page = Page::orderBy('name', 'ASC')
        ->get();
    return $page;
}

?>
