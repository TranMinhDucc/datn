<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Chia sẻ dữ liệu recommendedProducts cho mọi view
        View::composer('layouts.client', function ($view) {
            $recommendedProducts = Product::where('is_active', 1)
                ->inRandomOrder()
                ->take(6)
                ->get();
            $view->with('recommendedProducts', $recommendedProducts);
        });
    }
}
