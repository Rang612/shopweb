<?php

use App\Http\Controllers\AuthController;
use \App\Http\Controllers\Front;
use App\Http\Controllers\Front\CheckOutController;
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
});

Route::prefix('cart')->group(function(){
    Route::post('add/{id}', [Front\CartController::class, 'add'])->name('cart.add');
    Route::get('/',[Front\CartController::class,'index'])->name('cart.index');
    Route::get('delete/{rowId}', [Front\CartController::class,'delete']);
    Route::get('/destroy', [Front\CartController::class,'destroy']);
    Route::get('/update', [Front\CartController::class,'update']);
    //apply coupon
    Route::post('/apply-discount', [Front\CartController::class,'applyDicount'])->name('cart.applyDiscount');
});

Route::prefix('checkout')->group(function(){
    Route::get('/',[Front\CheckOutController::class,'index']);
    Route::post('/add-order', [Front\CheckOutController::class, 'addOrder'])->name('add.order');
    Route::get('/result',[Front\CheckOutController::class,'result']);

});
Route::get('/profile',[AuthController::class,'profile'])->name('account.profile');

Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login',[AuthController::class,'login'])->name('account.login');
        Route::post('/login',[AuthController::class,'authenticate'])->name('account.authenticate');

        Route::get('/register',[AuthController::class,'register'])->name('account.register');
        Route::post('/process-register',[AuthController::class,'processRegister'])->name('account.processRegister');
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile',[AuthController::class,'profile'])->name('account.profile');
        Route::get('/my-orders',[AuthController::class,'orders'])->name('account.orders');
        Route::get('/order-detail/{orderId}',[AuthController::class,'orderDetail'])->name('account.orderDetail');
        Route::post('/logout', [AuthController::class, 'logout'])->name('account.logout');
        Route::post('/update-user', [AuthController::class, 'updateProfile'])->name('account.updateProfile');

    });
});
Route::get('/contact',[Front\ContactController::class,'contact']);
Route::post('/checkout/shipping-cost', [CheckoutController::class, 'getShippingCost'])->name('checkout.shipping-cost');

Route::get('/proxy/provinces', function () {
    return Http::get('https://provinces.open-api.vn/api/p/?depth=2')->body();
});

Route::get('/proxy/provinces/{cityCode}', function ($cityCode) {
    return Http::get("https://provinces.open-api.vn/api/p/{$cityCode}?depth=2")->body();
});

Route::get('/proxy/districts/{districtCode}', function ($districtCode) {
    return Http::get("https://provinces.open-api.vn/api/d/{$districtCode}?depth=2")->body();
});
