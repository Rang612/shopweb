<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\StoreLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
//    public function index(Request $request)
//    {
//        $query = $request->input('search');
//        $stores = StoreLocation::when($query, function ($q) use ($query) {
//            return $q->where('name', 'like', "%$query%")
//                ->orWhere('address', 'like', "%$query%")
//                ->orWhere('city', 'like', "%$query%");
//        })->get();
//        $featuredStores = StoreLocation::where('is_featured', true)->limit(3)->get();
//        return view('front.store', compact('stores', 'query', 'featuredStores'));
//    }

    public function index(Request $request)
    {
        $query = $request->input('search');

        $stores = StoreLocation::when($query, function ($q) use ($query) {
            return $q->where('name', 'like', "%$query%")
                ->orWhere('address', 'like', "%$query%")
                ->orWhere('city', 'like', "%$query%");
        })->get();

        // Gán image path local hoặc tải về từ Imgur
        foreach ($stores as $store) {
            if ($store->image_url && $store->image) {
                $store->saved_image = $this->downloadAndSaveImage($store->image_url, $store->image);
            } elseif ($store->image) {
                $store->saved_image = "storage/{$store->image}";
            } else {
                $store->saved_image = 'front/img/default-store.jpg'; // ảnh mặc định
            }
        }

        $featuredStores = StoreLocation::where('is_featured', true)->limit(3)->get();

        return view('front.store', compact('stores', 'query', 'featuredStores'));
    }

    private function downloadAndSaveImage($imageUrl, $imageName)
    {
        if (!$imageUrl || !$imageName) return null;

        $path = "public/stores/{$imageName}";

        if (Storage::exists($path)) {
            return "storage/stores/{$imageName}";
        }

        $imageContents = @file_get_contents($imageUrl);
        if (!$imageContents) return null;

        Storage::put($path, $imageContents);

        return "storage/stores/{$imageName}";
    }



}
