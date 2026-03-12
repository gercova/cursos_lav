<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
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
            $enterprise = Enterprise::first();
            $view->with(['enterprise' => $enterprise]);
        });

        View::composer('layouts.admin', function ($view) {
            $enterprise = Enterprise::first();
            $view->with(['enterprise' => $enterprise]);
        });

        // View::composer('layouts.student', function ($view) {
        //     $enterprise         = Enterprise::first();
        //     $hasAnyPackage      = User::find(auth()->id())->studentCourses()->where('courses.type', 'package')->exists();
        //     $purchasedPackage   = User::find(auth()->id())->studentCourses()->where('courses.type', 'package')->first();
        //     $view->with([
        //         'enterprise'        => $enterprise, 
        //         'hasAnyPackage'     => $hasAnyPackage,
        //         'purchasedPackage'  => $purchasedPackage,
        //     ]);
        // });

        View::composer('layouts.student', function ($view) {
            $user = auth()->user();
            
            // Optimización: evitar consultas duplicadas
            $studentCourses = $user->studentCourses()
                ->where('courses.type', 'package')
                ->get();
            
            $purchasedPackage = $studentCourses->first();
            
            $view->with([
                'enterprise'        => Enterprise::first(),
                'hasAnyPackage'     => $studentCourses->isNotEmpty(),
                'purchasedPackage'  => $purchasedPackage,
            ]);
        });
    }
}
