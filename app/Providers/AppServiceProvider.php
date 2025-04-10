<?php

namespace App\Providers;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    public function boot()
    {
        View::composer('*', function ($view) {
            $wishlistCount = 0;
            $wishlists = [];

            if (Auth::check()) {
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
                $wishlists = Wishlist::with('product')->where('user_id', Auth::id())->latest()->get();
            }

            $view->with([
                'wishlistCount' => $wishlistCount,
                'wishlists' => $wishlists,
            ]);
        });
    }
}
