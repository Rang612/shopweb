<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class CheckOutController extends Controller
{
//    public function index()
//    {
//        $carts = Cart::content();
//        $subtotal = (float) str_replace(',', '', Cart::subtotal());
//        $total = (float) str_replace(',', '', Cart::total());
//
//        // Lấy discount từ session (nếu có)
//        $discountData = session('discountData', ['discount' => 0, 'code' => null]);
//        $discount = (float) $discountData['discount'];
//        $totalAfterDiscount = $subtotal - $discount;
//
//        return view('front.checkout.index', compact(
//            'carts',
//            'subtotal',
//            'total',
//            'discount',
//            'totalAfterDiscount',
//            'discountData'
//        ));
//    }

//    public function index(Request $request)
//    {
//        $carts = Cart::content();
//        $subtotal = (float) str_replace(',', '', Cart::subtotal());
//        $total = (float) str_replace(',', '', Cart::total());
//
//        // Lấy giảm giá từ session (nếu có)
//        $discountData = session('discountData', ['discount' => 0, 'code' => null]);
//        $discount = (float) $discountData['discount'];
//        $totalAfterDiscount = $subtotal - $discount;
//
//        // Lấy phí vận chuyển dựa vào tỉnh/thành phố
//        $shippingCost = 0;
//        if ($request->has('city')) {
//            $city = $request->input('city');
//            $shippingCostData = ShippingCharge::where('country_id', $city)->first();
//            if ($shippingCostData) {
//                $shippingCost = $shippingCostData->shipping_cost;
//            }
//        }
//
//        // Cộng phí vận chuyển vào tổng đơn hàng
//        $totalAfterDiscount += $shippingCost;
//
//        return view('front.checkout.index', compact(
//            'carts',
//            'subtotal',
//            'total',
//            'discount',
//            'totalAfterDiscount',
//            'discountData',
//            'shippingCost'
//        ));
//    }
    public function index(Request $request)
    {
        $carts = Cart::content();
        $subtotal = (float) str_replace(',', '', Cart::subtotal());
        $total = (float) str_replace(',', '', Cart::total());

        // Lấy giảm giá từ session (nếu có)
        $discountData = session('discountData', ['discount' => 0, 'code' => null]);
        $discount = (float) $discountData['discount'];
        $totalAfterDiscount = $subtotal - $discount;

        // Lấy phí vận chuyển nếu có thành phố được chọn
        $shippingCost = 0;
        if ($request->has('city')) {
            $cityName = $request->input('city');

            // Tìm ID thành phố
            $city = DB::table('countries')->where('name', $cityName)->first();
            if ($city) {
                $shippingCharge = ShippingCharge::where('country_id', $city->id)->first();
                $shippingCost = $shippingCharge->shipping_cost ?? 0;
            }
        }

        // Cập nhật tổng tiền
        $totalAfterDiscount += $shippingCost;

        return view('front.checkout.index', compact(
            'carts',
            'subtotal',
            'total',
            'discount',
            'totalAfterDiscount',
            'discountData',
            'shippingCost'
        ));
    }
    public function getShippingCost(Request $request)
    {
        $cityName = trim(strtolower($request->input('city')));

        $country = DB::table('countries')->whereRaw('LOWER(name) = ?', [$cityName])->first();

        if ($country) {
            $shippingCharge = DB::table('shipping_charges')->where('country_id', $country->id)->first();
            $shippingCost = $shippingCharge->shipping_cost ?? 0;

            return response()->json([
                'shipping_cost' => (float) $shippingCost // Trả về số
            ]);
        }

        return response()->json(['shipping_cost' => 0]);
    }
//    public function addOrder(Request $request)
//    {
//        // Kiểm tra xem người dùng đã đăng nhập chưa
//        if (!Auth::check()) {
//            return redirect()->route('login')->with('notification', 'Please login to continue.');
//        }
//        if($request->payment_type == 'pay_later'){
//            //thêm đơn hàng
//            $order = Order::create($request->all());
//
//            //thêm chi tiết đơn hàng
//            $carts = Cart::content();
//
//            foreach ($carts as $cart){
//                $data = [
//                    'order_id' => $order->id,
//                    'product_id' => $cart->id,
//                    'qty' => $cart->qty,
//                    'amount' => $cart->price,
//                    'total' => $cart->price * $cart->qty,
//                ];
//                OrderItem::create($data);
//            }
//            // gửi mail xác nhận
//            $total = Cart::total();
//            $subtotal = Cart::subtotal();
//            $this->sendEmail($order, $total,$subtotal);
//
//            //xóa giỏ hàng
//            Cart::destroy();
//            //trả về kết quả
//            return redirect('checkout/result')->with('notification', 'Success! Please check your email.');
//        }
//        else {
//            return "Online payment method is not supported, please try with pay later";
//        }
//
//    }
    public function addOrder(Request $request)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('account.login')->with('notification', 'Please login to continue.');
        }

        // Validation dữ liệu đầu vào
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone-number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'city' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'street' => 'required|string|max:255',
            'house' => 'required|string|max:255',
            'payment_type' => 'required|in:pay_later,online_payment',
            'shipping_cost' => 'required|numeric|min:0',
            'coupon_code' => 'nullable|string|max:255',
        ]);

        if ($request->payment_type == 'pay_later') {
            // Tính toán subtotal và shipping cost
            $subtotal = (float)Cart::subtotal(0, '', '');
            $shippingCost = (float)$request->shipping_cost;

            // Lấy thông tin giảm giá từ session
            $discountData = session('discountData', ['discount' => 0, 'code' => null]);
            $discount = (float)$discountData['discount'];
            $couponCode = $discountData['code'];

            // Tìm coupon_code_id từ database
            $coupon = DiscountCoupon::where('code', $couponCode)->first();
            $couponCodeId = $coupon ? $coupon->id : null;

            // Tìm country_id từ tên city
            $cityName = $request->city;
            $country = Country::where('name', $cityName)->first();
            if (!$country) {
                return back()->withErrors(['city' => 'Invalid city name: ' . $cityName]);
            }
            $countryId = $country->id;

            // Tính tổng tiền sau khi áp dụng giảm giá
            $total = $subtotal + $shippingCost - $discount;

            // Chuẩn bị dữ liệu đơn hàng
            $orderData = [
                'user_id' => Auth::id(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile' => $request->input('phone-number'),
                'email' => $request->email,
                'country_id' => $countryId, // Lưu country_id thay vì tên city
                'district' => $request->district,
                'ward' => $request->ward,
                'street' => $request->street,
                'house' => $request->house,
                'postcode_zip' => $request->postcode_zip,
                'note' => $request->note,
                'payment_type' => $request->payment_type,
                'subtotal' => $subtotal,
                'shipping' => $shippingCost,
                'coupon_code' => $couponCode,
                'coupon_code_id' => $couponCodeId,
                'discount' => $discount,
                'grand_total' => $total,
                'status' => 'pending',
            ];

            // Debug dữ liệu
//            dd($orderData);

            // Thêm đơn hàng
            $order = Order::create($orderData);

            // Thêm chi tiết đơn hàng
            $carts = Cart::content();
            foreach ($carts as $cart) {
                $data = [
                    'order_id' => $order->id,
                    'product_id' => $cart->id,
                    'qty' => $cart->qty,
                    'name' => $cart->name,
                    'price' => $cart->price,
                    'total' => $cart->price * $cart->qty,
                    'color' => $cart->options->color ?? null,
                    'size' => $cart->options->size ?? null,
                ];
                dd($data);
                OrderItem::create($data);
            }
            // gửi mail xác nhận
            $total = Cart::total();
            $subtotal = Cart::subtotal();
//            $this->sendEmail($order, $total, $subtotal);

            //xóa giỏ hàng
            Cart::destroy();
            //trả về kết quả
            return redirect('checkout/result')->with('notification', 'Success! Please check your email.');
        } else {
            return "Online payment method is not supported, please try with pay later";
        }
    }
    public function result()
    {
        $notification = session('notification');

        return view('front.checkout.result', compact('notification'));
    }
    private function sendEmail($order, $total, $subtotal){
        $email_to = $order->email;
        Mail::send('front.checkout.email', compact('order','total','subtotal'), function ($message) use($email_to){
            $message->from('tranhuonggiang6122003@gmail.com', 'XRAY SHOP'); //gui tu email nao co ten la gi
            $message->to($email_to, $email_to);
            $message->subject('Oder Notification');
        });
    }
}
