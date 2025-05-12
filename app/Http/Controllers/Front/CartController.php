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
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
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
            $imagePath = $productImage ? asset('uploads/products/small/' . $productImage) : asset('front/img/default-product.jpg');
            Cart::add(
                $productDetail->id, // ID biến thể sản phẩm
                $product->title, // Tên sản phẩm
                1, // Số lượng
                $product->compare_price ?? $product->price, // Giá
                0, // Trọng lượng
                [
                    'product_id' => $product->id,           // ✅ Thêm ID sản phẩm gốc
                    'image' => $imagePath,
                    'size' => $productDetail->size,
                    'color' => $productDetail->color,
                ]
            );
            return back()->with('success', 'Sản phẩm đã thêm vào giỏ hàng!');
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
    public function index(Request $request)
    {
        $carts = Cart::content();
        $subtotal = (float) str_replace(',', '', Cart::subtotal());

        $discount = 0;
        $discountMessage = null;

        if ($request->has('coupon')) {
            if (!auth()->check()) {
                $discountMessage = 'Please login to use discount code!';
            } else {
                $discountData = $this->checkDiscount($request->coupon);
                $discountMessage = $discountData['message'];
                $discount = (float) ($discountData['discount'] ?? 0);
            }

            session(['discountMessage' => $discountMessage]);
        } else {
            session()->forget('discountMessage');
        }

        $totalAfterDiscount = $subtotal - $discount;

        return view('front.shop.cart', compact(
            'carts',
            'subtotal',
            'discount',
            'totalAfterDiscount',
            'discountMessage'
        ));
    }

    public function checkDiscount($code)
    {
        $user = auth()->user();
        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'You must be logged in to use a discount code!',
                'discount' => 0
            ];
        }

        $coupon = DiscountCoupon::where('code', $code)->first();

        if (!$coupon) {
            return [
                'status' => 'error',
                'message' => 'Invalid discount coupon!',
                'discount' => 0
            ];
        }

        $now = Carbon::now();

        // Kiểm tra ngày bắt đầu và hết hạn
        if (!empty($coupon->starts_at) && $now->lt(Carbon::parse($coupon->starts_at))) {
            return [
                'status' => 'error',
                'message' => 'Discount coupon is not started yet!',
                'discount' => 0
            ];
        }

        if (!empty($coupon->expires_at) && $now->gt(Carbon::parse($coupon->expires_at))) {
            return [
                'status' => 'error',
                'message' => 'Discount coupon has expired!',
                'discount' => 0
            ];
        }

        // Kiểm tra số lượt dùng
        $totalUsed = DB::table('coupon_user')->where('coupon_id', $coupon->id)->count();
        $userUsed = DB::table('coupon_user')
            ->where('coupon_id', $coupon->id)
            ->where('user_id', $user->id)
            ->count();

        if ($coupon->max_uses !== null && $totalUsed >= $coupon->max_uses) {
            return [
                'status' => 'error',
                'message' => 'This coupon has been used up!',
                'discount' => 0
            ];
        }

        if ($coupon->max_uses_user !== null && $userUsed >= $coupon->max_uses_user) {
            return [
                'status' => 'error',
                'message' => 'You have already used this coupon!',
                'discount' => 0
            ];
        }

        // Kiểm tra đơn hàng tối thiểu
        $subtotal = (float) str_replace(',', '', Cart::subtotal());
        if ($coupon->min_amount && $subtotal < $coupon->min_amount) {
            return [
                'status' => 'error',
                'message' => 'You need to spend at least ' . number_format($coupon->min_amount, 0, ',', '.') . ' VND to use this coupon!',
                'discount' => 0
            ];
        }

        // Tính toán giảm giá
        if ($coupon->type === 'percent') {
            $discount = ($subtotal * (float) $coupon->discount_amount) / 100;
        } else {
            $discount = (float) $coupon->discount_amount;
        }
        $discountData = [
            'status' => 'success',
            'message' => 'Discount applied successfully!',
            'discount' => $discount,
            'code' => $coupon->code
        ];
        session(['discountData' => $discountData]);
        return $discountData;
    }
}
