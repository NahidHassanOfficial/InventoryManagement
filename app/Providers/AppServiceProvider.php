<?php

namespace App\Providers;

use App\Models\ShopDetail;
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
    public function boot(): void
    {
        View::composer('components.layouts.dashboard', function ($view) {
            $shop = ShopDetail::where('user_id', request()->header('id'))->first();

            $view->with('title', $shop->title);
        });

    }
}
