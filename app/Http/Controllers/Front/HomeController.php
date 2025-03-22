<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $products = Product::where('is_featured', 'Yes')
                            ->with('product_images')
                            ->where('status',1)
                            ->get();
        $data['products'] = $products;
        $menProducts = Product::where('is_featured', 'Yes')->where('category_id', 1)->get();
        $womenProducts = Product::where('is_featured', 'Yes')->where('category_id', 2)->get();
        $data['menProducts'] = $menProducts;
        $data['womenProducts'] = $womenProducts;
//        $blogs = Blog::orderBy('id', 'desc')->limit(3)->get();
        return view('front.index', $data);
    }
}
