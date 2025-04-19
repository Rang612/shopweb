<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\StoreLocation;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $stores = StoreLocation::when($query, function ($q) use ($query) {
            return $q->where('name', 'like', "%$query%")
                ->orWhere('address', 'like', "%$query%")
                ->orWhere('city', 'like', "%$query%");
        })->get();
        $featuredStores = StoreLocation::where('is_featured', true)->limit(3)->get();
        return view('front.store', compact('stores', 'query', 'featuredStores'));
    }
}
