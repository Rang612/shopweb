<?php

use \App\Http\Controllers\Front;
use App\Models\ProductDetail;
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
    Route::post('/',[Front\CheckOutController::class,'addOrder']);
    Route::get('/result',[Front\CheckOutController::class,'result']);

});
Route::get('/contact',[Front\ContactController::class,'contact']);
