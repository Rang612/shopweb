<?php

namespace App\Providers;

use App\Models\Wishlist;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use OpenAI;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
        public function register(): void
    {
        $this->app->singleton(OpenAI\Client::class, function () {
            return OpenAI::client(env('OPENAI_API_KEY'));
        });
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
