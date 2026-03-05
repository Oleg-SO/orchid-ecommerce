<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\Category; 

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
        View::composer('layouts.footer', function ($view) {

            $popularCategories = Category::where('active', true) // можно фильтровать
                ->withCount('products')
                ->having('products_count', '>', 0)
                ->take(4)
                ->get();

            if ($popularCategories->isEmpty()) {
                $popularCategories = Category::where('active', true)
                    ->take(4)
                    ->get();
            }

            $view->with('popularCategories', $popularCategories);

        });
    }
}