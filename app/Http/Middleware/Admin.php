<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Admin
{
    public function handle(Request $request, Closure $next) {

        Log::info('Admin middleware', [
            'user' => Auth::check() ? Auth::id() : 'guest',
            'url' => $request->url(),
            'intended' => $request->session()->get('url.intended')
        ]);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verificar si tiene algún rol permitido
        if (!Auth::user()->hasAnyRole(['admin', 'instructor'])) {
            // En lugar de redirigir al login, redirige a una página de acceso denegado o al home
            return redirect()->route('home')->withErrors('Acceso denegado. Rol no autorizado.');
        }

        return $next($request);
    }
}