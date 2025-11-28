<?php

namespace App\Providers;

use App\Models\Category;
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
        View::composer('layouts.app', function ($view) {
            $categories = Category::where('is_active', true)
                ->orderBy('name')
                ->get();
            $view->with('categories', $categories);
        });

        View::composer('student.home', function ($view) {
            $categories = Category::where('is_active', true)
                ->orderBy('name')
                ->get();
            $view->with('categories', $categories);
        });
    }
}
