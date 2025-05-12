<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\DiscountCoupon;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('is_featured', 'Yes')
            ->with('productImages', 'tags')
            ->where('status', 1)
            ->get();

        foreach ($products as $product) {
            if ($product->productImages->isNotEmpty()) {
                $firstImage = $product->productImages->first(); // Lấy ảnh đầu tiên

                // Lấy tên ảnh từ cột `image`
                $imageName = $firstImage->image;

                // Kiểm tra xem có tên ảnh không
                if ($imageName) {
                    // Tải và lưu ảnh với đúng tên
                    $imagePath = $this->downloadAndSaveImage($firstImage->imgur_link, $imageName);

                    // Gán đường dẫn ảnh để hiển thị trong view
                    $product->saved_image = $imagePath;
                } else {
                    $product->saved_image = 'front/img/default-product.jpg';
                }
            } else {
                $product->saved_image = 'front/img/default-product.jpg';
            }
        }

        $data['products'] = $products;
        $data['menProducts'] = Product::where('is_featured', 'Yes')->where('category_id', 1)->get();
        $data['womenProducts'] = Product::where('is_featured', 'Yes')->where('category_id', 2)->get();
        // Xử lý danh mục Women
        $womenCategory = Category::where('slug', 'woman-product')->with('sub_category')->first();
        $womenSubCategories = $womenCategory ? $womenCategory->sub_category : collect();
        $womenProductsBySubcategory = [];

        foreach ($womenSubCategories as $subCategory) {
            $womenProductsBySubcategory[$subCategory->slug] = Product::where('sub_category_id', $subCategory->id)
                ->where('is_featured', 'Yes')
                ->where('status', 1)
                ->get();
        }

        // Xử lý danh mục Men
        $menCategory = Category::where('slug', 'men-product')->with('sub_category')->first();
        $menSubCategories = $menCategory ? $menCategory->sub_category : collect();
        $menProductsBySubcategory = [];

        foreach ($menSubCategories as $subCategoryMen) {
            $menProductsBySubcategory[$subCategoryMen->slug] = Product::where('sub_category_id', $subCategoryMen->id)
                ->where('is_featured', 'Yes')
                ->where('status', 1)
                ->get();
        }
        $wishlistIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];
        $latestBlogs = Blog::where('is_approved', true)
            ->orderByDesc('created_at')
            ->take(6)
            ->get();
        $vouchers = DiscountCoupon::where('status', 1)
            ->where('expires_at', '>', now())
            ->get();

        // Gán dữ liệu vào mảng
        $data['womenProductsBySubcategory'] = $womenProductsBySubcategory;
        $data['womenSubCategories'] = $womenSubCategories;
        $data['menProductsBySubcategory'] = $menProductsBySubcategory;
        $data['menSubCategories'] = $menSubCategories;
        $data['wishlistIds'] = $wishlistIds;
        $data['latestBlogs'] = $latestBlogs;
        $data['vouchers'] = $vouchers;
//        dd($menProductsBySubcategory);

//        dd($coupons);
        return view('front.index', $data);
    }

// Hàm tải ảnh và lưu vào storage
    private function downloadAndSaveImage($imageUrl, $imageName)
    {
        // Kiểm tra xem có link ảnh không
        if (!$imageUrl || !$imageName) {
            return null;
        }

        // Định dạng lại tên file và đường dẫn
        $path = "public/products/{$imageName}";

        // Kiểm tra nếu ảnh đã tồn tại
        if (Storage::exists($path)) {
            return "storage/products/{$imageName}"; // Trả về đường dẫn cũ nếu đã có ảnh
        }

        // Tải ảnh từ imgur
        $imageContents = @file_get_contents($imageUrl);
        if (!$imageContents) {
            return null; // Nếu tải thất bại, trả về null
        }

        // Lưu ảnh vào storage
        Storage::put($path, $imageContents);

        return "storage/products/{$imageName}"; // Trả về đường dẫn đã lưu
    }
}
