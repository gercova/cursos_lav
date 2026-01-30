<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Student
{
    public function handle(Request $request, Closure $next): Response {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isStudent()) {
            return redirect()->route('home')->with('error', 'No tienes permisos para acceder a esta área.');
        }

        return $next($request);
    }
}
