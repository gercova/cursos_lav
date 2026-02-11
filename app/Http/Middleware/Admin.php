<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) {

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verificar si tiene algún rol permitido
        if (!Auth::user()->hasAnyRole(['admin', 'instructor'])) {
            return redirect()->route('login')->withErrors('Acceso denegado. Rol no autorizado.');
        }

        return $next($request);
    }
}
