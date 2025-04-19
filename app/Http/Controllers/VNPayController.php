<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Mail;

class VNPayController extends Controller
{
    public function createPayment($orderId)
    {
        $order = Order::findOrFail($orderId);

        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_Returnurl = route('vnpay.return');

        $vnp_TxnRef = $order->id;
        $vnp_Amount = $order->grand_total * 100;
        $vnp_OrderInfo = "Thanh toán đơn hàng #" . $order->id;

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => now()->format('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => 'vn',
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => 'billpayment',
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];
        // Bỏ vnp_SecureHash trước khi hash
        ksort($inputData);
        $hashdata = '';
        $first = true;
        foreach ($inputData as $key => $value) {
            if ($first) {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $first = false;
            } else {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            }
        }
        $vnp_SecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $inputData['vnp_SecureHash'] = $vnp_SecureHash;
        $vnp_Url .= '?' . http_build_query($inputData);
        return redirect($vnp_Url);
    }
    public function vnpayReturn(Request $request)
    {
        $orderId = $request->vnp_TxnRef;
        $order = Order::findOrFail($orderId);

        if ($request->vnp_ResponseCode == '00') {
            $order->update([
                'payment_status' => 'paid',
                'order_status'   => 'processing'
            ]);
            // Gửi mail ở đây sau khi thanh toán thành công
            $this->sendEmail($order, $order->grand_total, $order->subtotal);
            // Xoá giỏ hàng (an toàn nếu người dùng quay lại)
            Cart::destroy();
            return redirect('checkout/result')->with('notification', 'Success! Please check your email.');
        } else {
            return redirect('checkout/result')->with('notification', 'Payment failed please pay within 24 hours.');
        }
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
}
