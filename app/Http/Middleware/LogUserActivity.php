<?php

namespace App\Http\Middleware;

use App\Models\UserActivity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity {

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar si el usuario está autenticado
        if (Auth::check()) {
            $this->logRouteActivity($request);
        }

        return $response;
    }

    private function logRouteActivity(Request $request)
    {
        $routeName  = $request->route()->getName();
        $userId     = Auth::id();
        $method     = $request->method();

        // Definir qué rutas registrar
        $routesToLog = [
            'student.dashboard'     => ['type' => 'dashboard_access', 'action' => 'Acceso al dashboard'],
            'student.my-courses'    => ['type' => 'courses_viewed', 'action' => 'Vista de mis cursos'],
            'course.show'           => ['type' => 'course_detail_viewed', 'action' => 'Vista de detalle de curso'],
            'student.profile'       => ['type' => 'profile_viewed', 'action' => 'Vista de perfil'],
            'student.certificates'  => ['type' => 'certificates_viewed', 'action' => 'Vista de certificados'],
            'student.exams'         => ['type' => 'exams_viewed', 'action' => 'Vista de exámenes'],
            'student.progress'      => ['type' => 'progress_viewed', 'action' => 'Vista de progreso'],
        ];

        if (array_key_exists($routeName, $routesToLog)) {
            $routeInfo = $routesToLog[$routeName];

            UserActivity::create([
                'user_id'       => $userId,
                'type'          => $routeInfo['type'],
                'action'        => $routeInfo['action'],
                'description'   => "Accedió a: {$routeInfo['action']}",
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
                'data' => [
                    'route'     => $routeName,
                    'method'    => $method,
                    'url'       => $request->fullUrl(),
                    'timestamp' => now()->toDateTimeString()
                ]
            ]);
        }
    }
}
