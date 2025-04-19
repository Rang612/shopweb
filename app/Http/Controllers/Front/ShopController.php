<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductComment;
use App\Models\Tag;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    //
    public function show($id){
        //get categories, brand
        $categories = Category::all();
        $brands = Brand::all();
        $tags = Tag::all(); // Lấy toàn bộ tags
        $product = Product::with(['productImages','tags', 'productComment'])->findOrFail($id);
        $avgRating = 0;
        $sumRating = array_sum(array_column($product->productComment->toArray(), 'rating'));
        $countRating = count($product->productComment);
        if ($countRating != 0){
            $avgRating = $sumRating/$countRating;
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->whereHas('tags', function ($query) use ($product) {
                $query->whereIn('tags.id', $product->tags->pluck('id'));
            })
            ->limit(4)
            ->get();


        $wishlistIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];
        return view('front.shop.show', compact('product', 'categories','brands', 'avgRating', 'relatedProducts','tags','wishlistIds'));
    }

    public function postComment(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'messages' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $validated['name'] = $user->name;
        $validated['email'] = $user->email;

        ProductComment::create($validated);

        return redirect()->back()->with('success', 'Your comment has been posted!');
    }



    public function index(Request $request)
    {
        //get categories, brand
        $categories = Category::all();
        $brands = Brand::all();
        $tags = Tag::all(); // Lấy toàn bộ tags

        //get product
        $perPage = $request->show ?? 3;
        $sortBy = $request->sort_by ?? 'latest';
        $search = $request->search ?? '';

        $products = Product::where('title', 'like', '%' . $search . '%');

        $products = $this->filter($products, $request);

        $products = $this->sortAndPagination($products, $sortBy, $perPage);
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
        $wishlistIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

       return view('front.shop.index', compact('products', 'brands','categories','tags','wishlistIds'));
    }

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
    public function category($categorySlug, Request $request) {
        // Lấy danh mục theo slug
        $category = Category::where('slug', $categorySlug)->first();

        // Kiểm tra nếu không tìm thấy danh mục
        if (!$category) {
            abort(404, 'Category not found');
        }

        // Lấy danh sách danh mục, thương hiệu, tags
        $categories = Category::all();
        $brands = Brand::all();
        $tags = Tag::all();

        // Lấy sản phẩm của danh mục
        $perPage = $request->show ?? 3;
        $sortBy = $request->sort_by ?? 'latest';
        $products = $category->products()->getQuery();

        // Áp dụng bộ lọc
        $products = $this->filter($products, $request);

        // Sắp xếp và phân trang
        $products = $this->sortAndPagination($products, $sortBy, $perPage);

        return view('front.shop.index', compact('categories', 'brands', 'products', 'tags'));
    }
    public function sortAndPagination($products, $sortBy, $perPage)
    {
        switch ($sortBy) {
            case 'latest':
                $products = $products->orderByDesc('id'); // Mới nhất
                break;
            case 'oldest':
                $products = $products->orderBy('id'); // Cũ nhất
                break;
            case 'name-ascending':
                $products = $products->orderBy('title');
                break;
            case 'name-descending':
                $products = $products->orderByDesc('title');
                break;
            case 'price-ascending':
                $products = $products->orderByRaw("COALESCE(compare_price, price)");
                break;
            case 'price-descending':
                $products = $products->orderByRaw("COALESCE(compare_price, price) DESC");
                break;
            default:
                $products = $products->orderByDesc('id'); // Mặc định: sắp xếp mới nhất
        }

        $products = $products->paginate($perPage);

        // Gán tất cả query parameters vào pagination
        $products->appends(request()->query());

        return $products;
    }
    public function filter($products, Request $request) {
            // Lọc theo thương hiệu (brand)
            $brands = $request->brand ?? [];  // Lấy danh sách thương hiệu từ request
            $brand_ids = array_keys($brands); // Lấy các ID thương hiệu đã chọn

            if (!empty($brand_ids)) {
                $products = $products->whereIn('brand_id', $brand_ids);
            }

            // Lọc theo giá (price)
            $priceMin = $request->price_min;
            $priceMax = $request->price_max;

            // Loại bỏ dấu . và ký tự "đ" để đảm bảo giá trị là số
            $priceMin = str_replace(['.', 'đ', ','], '', $priceMin);
            $priceMax = str_replace(['.', 'đ', ','], '', $priceMax);

            // Chuyển thành số float để lọc
            $priceMin = (float) $priceMin;
            $priceMax = (float) $priceMax;

            // Áp dụng bộ lọc giá nếu có
            if ($priceMin > 0 && $priceMax > 0) {
                $products = $products->whereBetween('price', [$priceMin, $priceMax]);
            }
            // Lọc theo kích thước (cho phép nhiều kích thước)
            $sizes = $request->size ?? [];
            if (!empty($sizes)) {
                $products = $products->whereHas('productDetail', function ($query) use ($sizes) {
                    return $query->whereIn('size', $sizes)->where('qty', '>', 0);
                });
            }
            // Lọc theo tags (cho phép nhiều tag)
            $tags = $request->tags ?? [];
            if (!empty($tags)) {
                $products = $products->whereHas('tags', function ($query) use ($tags) {
                    return $query->whereIn('tags.id', $tags);
                });
            }
            return $products;  // Trả về danh sách sản phẩm đã lọc
        }
}
