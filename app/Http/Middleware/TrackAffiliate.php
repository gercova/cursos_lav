<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class TrackAffiliate {
    public function handle(Request $request, Closure $next): Response{
        // la ruta es ipf-educa.com/cursos/{code}
        $code = $request->route('code'); 
        if ($code) {
            $response = $next($request);
            // Guardamos la cookie por 1 hora (60 minutos)
            // 'vendedor_code' es el nombre, $code es el valor
            return $response->cookie('seller_code', $code, 3600); // 1 hora 
        }
        return $next($request);
    }
}
