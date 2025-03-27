<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
//    public function add($id){
//        $product = Product::findOrFail($id);
//
//        Cart::add([
//           'id' => $id,
//            'name' => $product->name,
//            'qty' => 1,
//            'price' => $product->discount ?? $product->price,
//            'weight' => $product->weight ?? 0,
//            'options' => [
//                'images' => $product->productImage,
//            ],
//        ]);
//        return back();
//    }

        public function add(Request $request, $id) {
            $product = Product::findOrFail($id);

            // Tìm chi tiết sản phẩm theo size & màu
            $productDetail = ProductDetail::where('product_id', $id)
                ->where('size', $request->size)
                ->where('color', $request->color)
                ->first();

            if (!$productDetail) {
                return back()->with('error', 'Size hoặc màu không hợp lệ!');
            }
            // Lấy ảnh từ cột image trong bảng product_images
            $productImage = ProductImage::where('product_id', $id)->value('image');

            // Kiểm tra nếu không có ảnh thì dùng ảnh mặc định
            $imagePath = $productImage ? asset('storage/products/' . $productImage) : asset('front/img/default-product.jpg');

            Cart::add(
                $productDetail->id, // ID biến thể sản phẩm
                $product->title, // Tên sản phẩm
                1, // Số lượng
                $product->compare_price ?? $product->price, // Giá
                0, // Trọng lượng
                [
                    'image' => $imagePath,
                    'size' => $productDetail->size,
                    'color' => $productDetail->color,
                ]
            );
//            dd(Cart::content());

            return back()->with('success', 'Sản phẩm đã thêm vào giỏ hàng!');
        }


    public function index(Request $request)
    {
        $carts = Cart::content();
        $subtotal = (float) str_replace(',', '', Cart::subtotal()); // Loại bỏ dấu phẩy, đảm bảo float
        $total = (float) str_replace(',', '', Cart::total());

        // Kiểm tra nếu có mã giảm giá từ request
        $discountData = ['discount' => 0];
        if ($request->has('coupon')) {
            $discountData = $this->checkDiscount($request->coupon);
        }

        $discount = (float) $discountData['discount'];
        $totalAfterDiscount = $subtotal - $discount;

        return view('front.shop.cart', compact(
            'carts',
            'subtotal',
            'discount',
            'totalAfterDiscount'
        ));
    }



    public function delete($rowId){
        Cart::remove($rowId);
        return back();
    }

    public function destroy(){
        Cart::destroy();
        return back();
    }

    public function update(Request $request){
        if($request->ajax()){
            Cart::update($request->rowId, $request->qty);
        }
    }

//    public function applyDicount()
//    {
//        $code = DiscountCoupon::where('code', request('code'))->first();
////        dd($code); // Debug để kiểm tra dữ liệu
//
//        if (!$code) {
//           return response()->json([
//               'status' => 'error',
//               'message' => 'Invalid discount coupon!'
//           ]);
//        }
//
//        $now = Carbon::now();
//
//        if($code->starts_at !=""){
//            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);
//            if($now->lt($startDate)){
//                return response()->json([
//                    'status' => 'error',
//                    'message' => 'Discount coupon is not started yet!'
//                ]);
//            }
//        }
//        if($code->expires_at !=""){
//            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);
//            if($now->gt($endDate)){
//                return response()->json([
//                    'status' => 'error',
//                    'message' => 'Invalid discount coupon!'
//                ]);
//            }
//        }
//
//        session()->put('discount', [
//            'name' => $code->name,
//            'discount' => $code->discount,
//            'discount_type' => $code->discount_type,
//        ]);
//        return response()->json([
//            'status' => 'success',
//            'message' => 'Discount applied successfully!',
//            'discount' => session('discount')
//        ]);
//
//    }

//    public function checkDiscount($code)
//    {
//        $coupon = DiscountCoupon::where('code', $code)->first();
//
//        if (!$coupon) {
//            return [
//                'status' => 'error',
//                'message' => 'Invalid discount coupon!',
//                'discount' => 0
//            ];
//        }
//
//        $now = Carbon::now();
//
//        // Kiểm tra ngày bắt đầu
//        if (!empty($coupon->starts_at) && $now->lt(Carbon::parse($coupon->starts_at))) {
//            return [
//                'status' => 'error',
//                'message' => 'Discount coupon is not started yet!',
//                'discount' => 0
//            ];
//        }
//
//        // Kiểm tra ngày hết hạn
//        if (!empty($coupon->expires_at) && $now->gt(Carbon::parse($coupon->expires_at))) {
//            return [
//                'status' => 'error',
//                'message' => 'Discount coupon has expired!',
//                'discount' => 0
//            ];
//        }
//
//        // Kiểm tra giá trị tối thiểu
//        $subtotal = (float) str_replace(',', '', Cart::subtotal());
//        if ($coupon->min_amount && $subtotal < $coupon->min_amount) {
//            return [
//                'status' => 'error',
//                'message' => 'You need to spend at least ' . number_format($coupon->min_amount, 0, ',', '.') . ' VND to use this coupon!',
//                'discount' => 0
//            ];
//        }
//
//        // Tính giảm giá
//        if ($coupon->type === 'percent') {
//            $discountAmount = (float) $coupon->discount_amount;
//            $discount = ($subtotal * $discountAmount) / 100;
//        } else {
//            $discount = (float) $coupon->discount_amount;
//        }
//
//        return [
//            'status' => 'success',
//            'message' => 'Discount applied successfully!',
//            'discount' => $discount
//        ];
//    }
    public function checkDiscount($code)
    {
        $coupon = DiscountCoupon::where('code', $code)->first();

        if (!$coupon) {
            return [
                'status' => 'error',
                'message' => 'Invalid discount coupon!',
                'discount' => 0
            ];
        }

        $now = Carbon::now();

        // Kiểm tra ngày bắt đầu
        if (!empty($coupon->starts_at) && $now->lt(Carbon::parse($coupon->starts_at))) {
            return [
                'status' => 'error',
                'message' => 'Discount coupon is not started yet!',
                'discount' => 0
            ];
        }

        // Kiểm tra ngày hết hạn
        if (!empty($coupon->expires_at) && $now->gt(Carbon::parse($coupon->expires_at))) {
            return [
                'status' => 'error',
                'message' => 'Discount coupon has expired!',
                'discount' => 0
            ];
        }

        // Kiểm tra giá trị tối thiểu
        $subtotal = (float) str_replace(',', '', Cart::subtotal());
        if ($coupon->min_amount && $subtotal < $coupon->min_amount) {
            return [
                'status' => 'error',
                'message' => 'You need to spend at least ' . number_format($coupon->min_amount, 0, ',', '.') . ' VND to use this coupon!',
                'discount' => 0
            ];
        }

        // Tính giảm giá
        if ($coupon->type === 'percent') {
            $discountAmount = (float) $coupon->discount_amount;
            $discount = ($subtotal * $discountAmount) / 100;
        } else {
            $discount = (float) $coupon->discount_amount;
        }

        $discountData = [
            'status' => 'success',
            'message' => 'Discount applied successfully!',
            'discount' => $discount,
            'code' => $coupon->code
        ];

        // LƯU DISCOUNT VÀO SESSION
        session(['discountData' => $discountData]);

        return $discountData;
    }


}
