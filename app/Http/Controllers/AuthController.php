<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }

    public function register()
    {
        return view('front.account.register');
    }

    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return redirect()->back() // Quay lại trang trước
            ->withErrors($validator) // Gửi lỗi về session
            ->withInput(); // Giữ lại dữ liệu nhập
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->role = 'user';
        $user->save();

        return redirect()->route('account.login') // Chuyển hướng đến trang login
        ->with('success', 'Registration Successful! Please log in.');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Ngăn chặn tấn công Session Fixation
            return redirect()->route('account.profile')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function profile()
    {
        return view('front.account.profile');
    }
    public function updateProfile(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:15',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Update the user's details
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone') ?? $user->phone; // Only update if phone is not empty

        // Save the user details
        $user->save();

        // Redirect back with a success message
        return back()->with('success', 'Your information has been updated!');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')
            ->with('success', 'Logout successful!');
    }

    public function orders(){
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $data['orders'] = $orders;
        return view('front.account.order', $data);
    }

    public function orderDetail($id){
        $data = [];
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        $data['order'] = $order;

        $orderItems = OrderItem::where('order_id', $id)->get();
        $data['orderItems'] = $orderItems;
//        dd($data);
        return view('front.account.order-detail', $data);
    }

    public function wishlist(){
//       $wishlists = Wishlist::where('user_id', Auth::user()->id)->get();
//       $data['wishlists'] = $wishlists;
//        return view('front.account.wishlist', $data);
        $user = auth()->user();

        // Lấy tất cả wishlist
        $wishlists = Wishlist::with('product.tags')->where('user_id', $user->id)->get();

        // Lấy tất cả tag_id từ các sản phẩm đã wishlist
        $tagIds = $wishlists->pluck('product.tags')->flatten()->pluck('id')->unique();

        // Lấy sản phẩm gợi ý có ít nhất 1 tag trùng
        $recommendedProducts = Product::whereHas('tags', function ($query) use ($tagIds) {
            $query->whereIn('tags.id', $tagIds);
        })
            ->whereNotIn('id', $wishlists->pluck('product_id')) // loại trừ sản phẩm đã wishlist
            ->paginate(4);
        return view('front.account.wishlist', compact('wishlists', 'recommendedProducts'));
    }
}
