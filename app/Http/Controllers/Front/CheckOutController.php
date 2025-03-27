<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Mail;


class CheckOutController extends Controller
{
    public function index()
    {
        $carts = Cart::content();
        $subtotal = (float) str_replace(',', '', Cart::subtotal());
        $total = (float) str_replace(',', '', Cart::total());

        // Lấy discount từ session (nếu có)
        $discountData = session('discountData', ['discount' => 0, 'code' => null]);
        $discount = (float) $discountData['discount'];
        $totalAfterDiscount = $subtotal - $discount;

        return view('front.checkout.index', compact(
            'carts',
            'subtotal',
            'total',
            'discount',
            'totalAfterDiscount',
            'discountData'
        ));
    }


    public function addOrder(Request $request)
    {
        if($request->payment_type == 'pay_later'){
            //thêm đơn hàng
            $order = Order::create($request->all());

            //thêm chi tiết đơn hàng
            $carts = Cart::content();

            foreach ($carts as $cart){
                $data = [
                    'order_id' => $order->id,
                    'product_id' => $cart->id,
                    'qty' => $cart->qty,
                    'amount' => $cart->price,
                    'total' => $cart->price * $cart->qty,
                ];
                OrderItem::create($data);
            }
            // gửi mail xác nhận
            $total = Cart::total();
            $subtotal = Cart::subtotal();
            $this->sendEmail($order, $total,$subtotal);

            //xóa giỏ hàng
            Cart::destroy();
            //trả về kết quả
            return redirect('checkout/result')->with('notification', 'Success! Please check your email.');
        }
        else {
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
