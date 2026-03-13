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

        View::composer(['layouts.app', 'layouts.admin', 'layouts.student'], function ($view) {
            $view->with('enterprise', Enterprise::first());
        });

        // 2. Lógica específica solo para el layout del estudiante
        View::composer('layouts.student', function ($view) {
            $user = auth()->user();
            
            $hasAnyPackage = false;
            $purchasedPackage = null;

            // IMPORTANTE: Validar siempre que el usuario exista para evitar errores 500 
            // si la vista intenta renderizarse para un visitante (guest).
            if ($user) {
                // Obtenemos el MEJOR paquete basándonos en el plan_type_id, 
                // igual que hicimos en los controladores.
                $purchasedPackage = $user->studentCourses()
                    ->where('courses.type', 'package')
                    ->orderByDesc('courses.plan_type_id')
                    ->first();

                $hasAnyPackage = !is_null($purchasedPackage);
            }
            
            $view->with([
                'hasAnyPackage'     => $hasAnyPackage,
                'purchasedPackage'  => $purchasedPackage,
            ]);
        });
    }
}
