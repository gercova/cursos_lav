<?php

namespace App\Traits;

use App\Models\UserActivity;

trait StudentActivityLogger {
    /**
     * Registrar actividad de estudiante
     */
    public static function logStudentActivity($userId, $type, $description, $data = [], $relatedModel = null) {
        $activity = UserActivity::create([
            'user_id' => $userId,
            'type' => $type,
            'action' => self::getActivityAction($type),
            'description' => $description,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        if ($relatedModel) {
            $activity->model_type = get_class($relatedModel);
            $activity->model_id = $relatedModel->id;
            $activity->save();
        }

        return $activity;
    }

    /**
     * Registrar inicio de sesión
     */
    public static function logStudentLogin($userId)
    {
        return UserActivity::logLogin(
            $userId,
            request()->ip(),
            request()->userAgent()
        );
    }

    /**
     * Registrar cierre de sesión
     */
    public static function logStudentLogout($userId)
    {
        return UserActivity::logLogout($userId);
    }

    /**
     * Registrar acceso a curso
     */
    public static function logCourseAccess($userId, $course)
    {
        return UserActivity::create([
            'user_id' => $userId,
            'type' => UserActivity::TYPE_COURSE_ACCESSED,
            'action' => 'Acceso a curso',
            'description' => "Accedió al curso: {$course->title}",
            'course_id' => $course->id,
            'data' => [
                'course_title' => $course->title,
                'access_time' => now()->toDateTimeString(),
                'progress' => self::getCourseProgress($userId, $course->id)
            ]
        ]);
    }

    /**
     * Registrar progreso de lección
     */
    public static function logLessonProgress($userId, $lesson, $course, $progress)
    {
        return UserActivity::create([
            'user_id' => $userId,
            'type' => 'lesson_progress',
            'action' => 'Progreso en lección',
            'description' => "Avanzó en la lección: {$lesson->title}",
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'data' => [
                'lesson_title' => $lesson->title,
                'course_title' => $course->title,
                'progress' => $progress,
                'time_spent' => self::calculateTimeSpent($userId, $lesson->id)
            ]
        ]);
    }

    /**
     * Registrar actividad en el carrito
     */
    public static function logCartActivity($userId, $action, $course)
    {
        $actions = [
            'add' => ['type' => UserActivity::TYPE_CART_ADDED, 'action' => 'Agregar al carrito'],
            'remove' => ['type' => 'cart_removed', 'action' => 'Remover del carrito'],
            'clear' => ['type' => 'cart_cleared', 'action' => 'Limpiar carrito']
        ];

        $actionData = $actions[$action] ?? $actions['add'];

        return UserActivity::create([
            'user_id' => $userId,
            'type' => $actionData['type'],
            'action' => $actionData['action'],
            'description' => "{$actionData['action']}: {$course->title}",
            'course_id' => $course->id,
            'data' => [
                'course_title' => $course->title,
                'price' => $course->final_price,
                'action' => $action,
                'timestamp' => now()->toDateTimeString()
            ]
        ]);
    }

    /**
     * Registrar actividad en la lista de deseos
     */
    public static function logWishlistActivity($userId, $action, $course)
    {
        return UserActivity::logGenericActivity(
            $userId,
            $action === 'add' ? UserActivity::TYPE_WISHLIST_ADDED : 'wishlist_removed',
            $action === 'add' ? 'Agregar a lista de deseos' : 'Remover de lista de deseos',
            $action === 'add'
                ? "Agregó a lista de deseos: {$course->title}"
                : "Removió de lista de deseos: {$course->title}",
            [
                'course_title' => $course->title,
                'price' => $course->final_price,
                'action' => $action
            ],
            $course
        );
    }

    /**
     * Registrar estudio diario
     */
    public static function logStudySession($userId, $minutes, $course = null)
    {
        $data = [
            'study_minutes' => $minutes,
            'session_start' => now()->subMinutes($minutes)->toDateTimeString(),
            'session_end' => now()->toDateTimeString()
        ];

        if ($course) {
            $data['course_title'] = $course->title;
            $data['course_id'] = $course->id;
        }

        return UserActivity::create([
            'user_id' => $userId,
            'type' => 'study_session',
            'action' => 'Sesión de estudio',
            'description' => $course
                ? "Estudió {$minutes} minutos en: {$course->title}"
                : "Estudió {$minutes} minutos",
            'course_id' => $course?->id,
            'data' => $data
        ]);
    }

    /**
     * Obtener estadísticas de actividad del estudiante
     */
    public static function getStudentStats($userId, $period = 'month')
    {
        $query = UserActivity::where('user_id', $userId);

        switch ($period) {
            case 'day':
                $query->today();
                break;
            case 'week':
                $query->thisWeek();
                break;
            case 'month':
                $query->thisMonth();
                break;
            default:
                $query->recent(30);
        }

        $activities = $query->get();

        return [
            'total_activities' => $activities->count(),
            'study_time' => $activities->where('type', 'study_session')->sum(function($activity) {
                return $activity->data['study_minutes'] ?? 0;
            }),
            'lessons_completed' => $activities->where('type', UserActivity::TYPE_LESSON_COMPLETED)->count(),
            'exams_taken' => $activities->whereIn('type', [
                UserActivity::TYPE_EXAM_STARTED,
                UserActivity::TYPE_EXAM_COMPLETED
            ])->count(),
            'courses_accessed' => $activities->where('type', UserActivity::TYPE_COURSE_ACCESSED)->count(),
            'last_activity' => $activities->last() ? $activities->last()->created_at->diffForHumans() : 'Sin actividad',
            'activity_by_type' => $activities->groupBy('type')->map->count(),
            'daily_streak' => self::calculateDailyStreak($userId)
        ];
    }

    /**
     * Calcular racha diaria de estudio
     */
    private static function calculateDailyStreak($userId)
    {
        $activities = UserActivity::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(30))
            ->whereIn('type', [
                'study_session',
                UserActivity::TYPE_LESSON_COMPLETED,
                UserActivity::TYPE_COURSE_ACCESSED
            ])
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->get()
            ->pluck('date')
            ->map(fn($date) => \Carbon\Carbon::parse($date));

        $streak = 0;
        $today = now()->startOfDay();

        foreach ($activities as $date) {
            if ($date->equalTo($today->copy()->subDays($streak))) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Obtener acción basada en tipo
     */
    private static function getActivityAction($type)
    {
        $actions = [
            'login' => 'Inicio de sesión',
            'logout' => 'Cierre de sesión',
            'course_enrolled' => 'Inscripción a curso',
            'lesson_completed' => 'Lección completada',
            'exam_started' => 'Examen iniciado',
            'exam_completed' => 'Examen completado',
            'certificate_earned' => 'Certificado obtenido',
            'profile_updated' => 'Perfil actualizado',
            'payment_completed' => 'Pago completado',
            'cart_added' => 'Carrito actualizado',
            'wishlist_added' => 'Lista de deseos actualizada',
            'course_accessed' => 'Acceso a curso',
            'password_changed' => 'Contraseña cambiada',
            'study_session' => 'Sesión de estudio',
            'lesson_progress' => 'Progreso en lección'
        ];

        return $actions[$type] ?? 'Actividad';
    }

    /**
     * Obtener progreso del curso
     */
    private static function getCourseProgress($userId, $courseId)
    {
        $enrollment = \App\Models\Enrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        return $enrollment ? $enrollment->progress : 0;
    }

    /**
     * Calcular tiempo dedicado (ejemplo simple)
     */
    private static function calculateTimeSpent($userId, $lessonId)
    {
        // Implementar lógica para calcular tiempo real
        return rand(5, 30); // Ejemplo
    }
}
