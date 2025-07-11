<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use \App\Http\Controllers\Front;
use App\Http\Controllers\Front\BlogController;
use App\Http\Controllers\Front\CheckOutController;
use App\Http\Controllers\Front\ShopController;
use App\Http\Controllers\Front\StoreController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\VNPayController;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [Front\HomeController::class, 'index'])->name('front.home');

Route::prefix('shop')->group(function(){
    Route::get('/product/{id}', [Front\ShopController::class, 'show'])->name('front.product.detail');
    Route::post('/product/{id}', [Front\ShopController::class, 'postComment'])->name('front.product.comment');
    Route::get('/', [Front\ShopController::class, 'index'])->name('front.shop.index');
    Route::get('/{categorySlug}', [Front\ShopController::class, 'category'])->name('front.shop.category');
    Route::get('/subcategory/{subcategorySlug}', [ShopController::class, 'subcategory'])->name('front.shop.subcategory');

});

Route::prefix('cart')->group(function(){
    Route::post('add/{id}', [Front\CartController::class, 'add'])->name('cart.add');
    Route::get('/',[Front\CartController::class,'index'])->name('cart.index');
    Route::get('delete/{rowId}', [Front\CartController::class,'delete']);
    Route::get('/destroy', [Front\CartController::class,'destroy']);
    Route::get('/update', [Front\CartController::class,'update']);
    //apply coupon
    Route::post('/apply-discount', [Front\CartController::class,'applyDiscount'])->name('cart.applyDiscount');
});

Route::prefix('checkout')->group(function(){
    Route::get('/',[Front\CheckOutController::class,'index']);
    Route::post('/add-order', [Front\CheckOutController::class, 'addOrder'])->name('add.order');
    Route::get('/result',[Front\CheckOutController::class,'result']);
    Route::post('/checkout/shipping-cost', [CheckoutController::class, 'getShippingCost'])->name('checkout.shipping-cost');
    Route::get('/proxy/provinces', function () {
        try {
            $http = app()->environment('local')
                ? Http::withoutVerifying()
                : Http::withOptions(['verify' => true]);

            $response = $http->get('https://provinces.open-api.vn/api/?depth=2');

            return $response->throw()->json();
        } catch (\Exception $e) {
            Log::error('Lỗi khi gọi provinces API: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể tải dữ liệu tỉnh'], 500);
        }
    });
    Route::get('/proxy/provinces/{cityCode}', function ($cityCode) {
        try {
            $http = app()->environment('local')
                ? Http::withoutVerifying()
                : Http::withOptions(['verify' => true]);
            $response = $http->get("https://provinces.open-api.vn/api/p/{$cityCode}?depth=2");
            return $response->throw()->json();
        } catch (\Exception $e) {
            \Log::error("Lỗi khi gọi districts API của tỉnh {$cityCode}: " . $e->getMessage());
            return response()->json(['error' => 'Không thể tải danh sách quận/huyện'], 500);
        }
    });
    Route::get('/proxy/districts/{districtCode}', function ($districtCode) {
        try {
            $http = app()->environment('local')
                ? Http::withoutVerifying()
                : Http::withOptions(['verify' => true]);
            $response = $http->get("https://provinces.open-api.vn/api/d/{$districtCode}?depth=2");
            return $response->throw()->json();
        } catch (\Exception $e) {
            Log::error("Lỗi khi gọi wards API của quận {$districtCode}: " . $e->getMessage());
            return response()->json(['error' => 'Không thể tải danh sách phường/xã'], 500);
        }
    });
    Route::get('/vnpay/{order}', [VNPayController::class, 'createPayment'])->name('vnpay.checkout');
    Route::get('/vnpay-return', [VNPayController::class, 'vnpayReturn'])->name('vnpay.return');
});

Route::prefix('wishlist')->group(function(){
    Route::post('/add-to-wishlist/{productId}', [FrontController::class, 'addToWishlist'])->name('front.addToWishlist');
    Route::delete('/wishlist/{productId}', [FrontController::class, 'removeToWishlist'])->name('front.removeToWishlist');
});

Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('front.blog.index');
    Route::get('/{id}', [BlogController::class, 'show'])->name('front.blog.show');
    Route::post('/create', [BlogController::class, 'store'])->middleware('auth')->name('front.blog.store');
    Route::delete('/my-blogs/{id}', [BlogController::class, 'destroy'])->name('front.blog.destroy');
});

Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login',[AuthController::class,'login'])->name('account.login');
        Route::post('/login',[AuthController::class,'authenticate'])->name('account.authenticate');
        Route::get('/register',[AuthController::class,'register'])->name('account.register');
        Route::post('/process-register',[AuthController::class,'processRegister'])->name('account.processRegister');
        Route::get('/forgot-password',[AuthController::class,'forgotPassword'])->name('account.forgotPassword');
        Route::post('/forgot-password',[AuthController::class,'processForgotPassword'])->name('account.processForgotPassword');
        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile',[AuthController::class,'profile'])->name('account.profile');
        Route::get('/my-orders',[AuthController::class,'orders'])->name('account.orders');
        Route::get('/my-wishlist',[AuthController::class,'wishlist'])->name('account.wishlist');
        Route::get('/my-blogs',[AuthController::class,'blog'])->name('account.blogs');
        Route::get('/order-detail/{orderId}',[AuthController::class,'orderDetail'])->name('account.orderDetail');
        Route::get('/account/change-password', [AuthController::class, 'showForm'])->name('password.form');
        Route::post('/account/change-password', [AuthController::class, 'change'])->name('password.change');
        Route::post('/logout', [AuthController::class, 'logout'])->name('account.logout');
        Route::post('/update-user', [AuthController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/update-address', [AuthController::class, 'updateAddress'])->name('account.updateAddress');

        Route::put('/my-orders/{id}/cancel', [CheckOutController::class, 'cancel'])->name('orders.cancel');
        Route::get('/blogs/create', [\App\Http\Controllers\Front\BlogController::class, 'create'])->name('blogs.create');
        Route::post('/blogs/store', [\App\Http\Controllers\Front\BlogController::class, 'store'])->name('blogs.store');
        Route::post('/blogs/{blog}/comment', [BlogController::class, 'comment'])->name('blogs.comment');
        Route::post('/product/{id}/comment', [ShopController::class, 'postComment'])->name('product.comment');

    });
});
Route::get('/contact',[Front\PageController::class,'contact']);
Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
Route::get('/page/{slug}', [Front\PageController::class, 'page'])->name('page.show');
Route::post('/contact/send', [Front\PageController::class, 'sendContactUsEmail'])->name('contact.send');
Route::post('/chatbot', [ChatbotController::class, 'handle']);
