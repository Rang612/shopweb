<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class CheckOutController extends Controller
{
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
    public function addOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('account.login')->with('notification', 'Please login to continue.');
        }
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'phone-number'   => 'required|string|max:20',
            'email'          => 'required|email|max:255',
            'city'           => 'required|string',
            'district'       => 'required|string',
            'ward'           => 'required|string',
            'street'         => 'required|string|max:255',
            'house'          => 'required|string|max:255',
            'shipping_cost'  => 'required|numeric|min:0',
            'coupon_code'    => 'nullable|string|max:255',
            'payment_type'   => 'required|in:pay_later,online_payment',
        ]);

        // 3. Chuẩn bị dữ liệu đơn hàng dùng chung
        $subtotal     = (float)Cart::subtotal(0, '', '');
        $shippingCost = (float)$request->shipping_cost;
        //Lấy thông tin giảm giá từ session
            $discountData = session('discountData', ['discount' => 0, 'code' => null]);
            $discount = (float)$discountData['discount'];
            $couponCode = $discountData['code'];
            // Tìm coupon_code_id từ database
            $coupon = DiscountCoupon::where('code', $couponCode)->first();
            $couponCodeId = $coupon ? $coupon->id : null;
        $country      = Country::where('name', $request->city)->first();
        if (!$country) {
            return back()->withErrors(['city' => 'Invalid city name: ' . $request->city]);
        }
        $total = $subtotal + $shippingCost - $discount;
        $orderData = [
            'user_id'          => Auth::id(),
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'mobile'           => $request->input('phone-number'),
            'email'            => $request->email,
            'country_id'       => $country->id,
            'district'         => $request->district,
            'ward'             => $request->ward,
            'street'           => $request->street,
            'house_number'     => $request->house,
            'zip'              => $request->postcode_zip,
            'notes'            => $request->note,
            'subtotal'         => $subtotal,
            'shipping'         => $shippingCost,
            'coupon_code'      => $couponCode,
            'coupon_code_id'   => $couponCodeId,
            'discount'         => $discount,
            'grand_total'      => $total,
            'order_status'     => 'pending',
        ];
        if ($request->payment_type === 'pay_later') {
            $orderData['payment_status']     = 'cod';
            $orderData['payment_method_id']  = PaymentMethod::where('name', 'cod')->value('id');
        } elseif ($request->payment_type === 'online_payment') {
            $orderData['payment_status']     = 'pending';
            $orderData['payment_method_id']  = PaymentMethod::where('name', 'vnpay')->value('id');
        }
        $order = Order::create($orderData);
        foreach (Cart::content() as $cart) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $cart->options->product_id,
                'qty'        => $cart->qty,
                'name'       => $cart->name,
                'price'      => $cart->price,
                'total'      => $cart->price * $cart->qty,
                'color'      => $cart->options->color ?? null,
                'size'       => $cart->options->size ?? null,
            ]);
        }
        if ($couponCodeId) {
            DB::table('coupon_user')->insert([
                'user_id'    => Auth::id(),
                'coupon_id'  => $couponCodeId,
                'used_at'    => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if ($request->payment_type === 'pay_later') {
            $this->sendEmail($order, Cart::total(), Cart::subtotal());
        }
        if ($request->payment_type === 'pay_later') {
            Cart::destroy();
        }

        // 11. Redirect tuỳ theo phương thức thanh toán
        if ($request->payment_type === 'pay_later') {
            return redirect('checkout/result')->with('notification', 'Success! Please check your email.');
        } else {
            // VNPAY → chuyển tới controller xử lý thanh toán
            return redirect()->route('vnpay.checkout', $order->id);
        }

    }
    public function result()
    {
        $notification = session('notification');

        return view('front.checkout.result', compact('notification'));
    }
    private function sendEmail($order, $total, $subtotal){
        $email_to = $order->email;

        // Ép kiểu tại đây (an toàn)
        $total = (float) str_replace(',', '', $total);
        $subtotal = (float) str_replace(',', '', $subtotal);

        Mail::send('front.checkout.email', compact('order', 'total', 'subtotal'), function ($message) use($email_to){
            $message->from('tranhuonggiang6122003@gmail.com', 'XRAY SHOP');
            $message->to($email_to, $email_to);
            $message->subject('Order Notification');
        });
    }
    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        if (in_array($order->status, ['pending', 'processing'])) {
            $order->status = 'decline';
            $order->save();
            return redirect()->back()->with('success', 'The order has been canceled.');
        }
        return redirect()->back()->with('error', 'This order cannot be canceled.');
    }
}
