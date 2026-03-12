<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $user = Auth::user();
        
        // Verificar si el usuario tiene un paquete comprado con plan_type_id 5,6,7
        $hasCompanyPlan = $user->studentCourses()
            ->where('courses.type', 'package')
            ->whereIn('plan_type_id', [5, 6, 7])
            ->exists();
        
        if (!$hasCompanyPlan) {
            // Redirigir o mostrar error 403
            return redirect()->route('student.dashboard')
                ->with('error', 'No tienes acceso al panel de empresa.');
        }
        
        return $next($request);
    }
}
