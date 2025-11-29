<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Enterprise;
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
    public function boot(): void {

        View::composer('layouts.app', function ($view) {
            $enterprise = Enterprise::find(1);
            $view->with(['enterprise' => $enterprise]);
        });

        View::composer('student.home', function ($view) {
            $enterprise = Enterprise::find(1);
            $view->with(['enterprise' => $enterprise]);
        });
    }
}
