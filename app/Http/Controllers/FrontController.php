<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FrontController extends Controller
{
    public function addToWishlist($productId)
    {
        if (!Auth::check()) {
            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false,
                'message' => 'Please login to add to wishlist'
            ]);
        }
        $product = Product::where('id', $productId)->first();
        if (!Product::find($productId)) {
            return response()->json([
                'status' => false,
                'message' => '<div class="alert alert-danger">Product not found</div>'
            ]);
        }

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ]);
        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->title.'"</strong> added to wishlist successfully</div>'
        ]);
    }

    public function removeToWishlist($productId)
    {
        $wishlist = Wishlist::where('id', $productId)->where('user_id', Auth::id())->firstOrFail();
        $wishlist->delete();
        return redirect()->back()->with('success', 'Removed from wishlist.');
    }
}
