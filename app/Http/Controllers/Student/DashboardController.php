<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\UserActivity;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function index(): View {
        return view('student.dashboard');
    }

    public function stats(Request $request) {
        $user = Auth::user();

        // Cursos activos
        $activeCourses = Enrollment::where('user_id', $user->id)
            ->whereHas('course', function($q) {
                $q->where('is_active', true);
            })
            ->count();

        // Certificados obtenidos
        $certificatesCount = Certificate::where('user_id', $user->id)->count();

        // Progreso mensual (ejemplo simple)
        $monthlyProgress = Enrollment::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->avg('progress') ?? 0;

        return response()->json([
            'activeCourses'         => $activeCourses,
            'pendingAssignments'    => 0, // Implementar si tienes tareas
            'certificatesCount'     => $certificatesCount,
            'monthlyProgress'       => round($monthlyProgress, 1),
            'unreadDiscussions'     => 0 // Implementar si tienes foros
        ]);
    }

    public function dashboardCourses() {
        $user           = Auth::user();
        $enrollments    = Enrollment::with(['course.category', 'course.instructor'])
            ->where('user_id', $user->id)
            ->whereHas('course', function($q) {
                $q->where('is_active', true);
            })
            ->orderBy('last_accessed_at', 'desc')
            ->limit(5)
            ->get();

        $courses = $enrollments->map(function($enrollment) {
            return [
                'id'            => $enrollment->course->id,
                'title'         => $enrollment->course->title,
                'slug'          => $enrollment->course->slug,
                'instructor'    => $enrollment->course->instructor->names ?? 'Instructor',
                'progress'      => $enrollment->progress ?? 0,
                'color'         => $this->getColorByProgress($enrollment->progress),
                'icon'          => 'book',
                'last_activity' => $enrollment->last_accessed_at ? $enrollment->last_accessed_at->diffForHumans() : 'Sin actividad',
                'next_lesson'   => 'Próxima lección' // Implementar lógica
            ];
        });

        return response()->json($courses);
    }

    public function progressCourses(): JsonResponse {
        $user = Auth::user();
        $enrollments = Enrollment::with('course')
            ->where('user_id', $user->id)
            ->where('progress', '<', 100)
            ->where('progress', '>', 0)
            ->orderBy('progress', 'desc')
            ->limit(3)
            ->get();

        $courses = $enrollments->map(function($enrollment) {
            return [
                'title'     => $enrollment->course->title,
                'slug'      => $enrollment->course->slug,
                'progress'  => $enrollment->progress ?? 0,
                'color'     => $this->getColorByProgress($enrollment->progress)
            ];
        });

        return response()->json($courses);
    }

    public function recentActivity(): JsonResponse {
        $user = Auth::user();

        // Usar UserActivity o crear una lógica similar
        $activities = UserActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $formatted = $activities->map(function($activity) {
            return [
                'description'   => $activity->description,
                'time'          => $activity->created_at->diffForHumans(),
                'icon'          => $this->getActivityIcon($activity->type),
                'color'         => $this->getActivityColor($activity->type),
                'badge'         => $activity->type,
                'badge_color'   => 'bg-blue-100 text-blue-800'
            ];
        });

        // Si no hay actividades, devolver datos de ejemplo
        if ($formatted->isEmpty()) {
            return response()->json([
                [
                    'description'   => 'Completaste la lección "Introducción a Laravel"',
                    'time'          => 'Hace 2 horas',
                    'icon'          => 'check-circle',
                    'color'         => 'green'
                ],
                [
                    'description'   => 'Inscrito en el curso "PHP Avanzado"',
                    'time'          => 'Ayer',
                    'icon'          => 'book',
                    'color'         => 'blue'
                ]
            ]);
        }

        return response()->json($formatted);
    }

    public function upcomingEvents(): JsonResponse {
        $user   = Auth::user();
        $exams  = Exam::whereHas('course.enrollments', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        $events = $exams->map(function($exam) {
            return [
                'title'     => $exam->title,
                'course'    => $exam->course->title,
                'day'       => $exam->start_date->format('D'),
                'date'      => $exam->start_date->format('d'),
                'time'      => $exam->start_date->format('H:i'),
                'color'     => 'red',
                'link'      => route('student.exams')
            ];
        });

        return response()->json($events);
    }

    public function achievements() {
        $user = Auth::user();

        // Lógica para logros - ejemplo básico
        $achievements = [];

        // Logro por completar cursos
        $completedCourses = Enrollment::where('user_id', $user->id)
            ->where('progress', '>=', 100)
            ->count();

        if ($completedCourses >= 1) {
            $achievements[] = [
                'title'         => 'Primer Curso Completado',
                'description'   => 'Completaste tu primer curso',
                'icon'          => 'trophy',
                'color'         => 'yellow'
            ];
        }

        if ($completedCourses >= 3) {
            $achievements[] = [
                'title' => 'Aprendiz Avanzado',
                'description' => 'Completaste 3 cursos',
                'icon' => 'star',
                'color' => 'purple'
            ];
        }

        // Logro por asistencia
        $activeDays = UserActivity::where('user_id', $user->id)
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->distinct('date')
            ->count();

        if ($activeDays >= 5) {
            $achievements[] = [
                'title' => 'Asistencia Perfecta',
                'description' => 'Activo 5 de los últimos 7 días',
                'icon' => 'calendar-check',
                'color' => 'green'
            ];
        }

        return response()->json($achievements);
    }

    private function getColorByProgress($progress) {
        if ($progress >= 80) return 'green';
        if ($progress >= 50) return 'blue';
        if ($progress >= 30) return 'yellow';
        return 'gray';
    }

    private function getActivityIcon($type) {
        $icons = [
            'lesson_completed'      => 'check-circle',
            'course_enrolled'       => 'book',
            'exam_taken'            => 'file-alt',
            'certificate_earned'    => 'certificate',
            'comment'               => 'comment'
        ];

        return $icons[$type] ?? 'circle';
    }

    private function getActivityColor($type) {
        $colors = [
            'lesson_completed' => 'green',
            'course_enrolled' => 'blue',
            'exam_taken' => 'red',
            'certificate_earned' => 'yellow',
            'comment' => 'purple'
        ];

        return $colors[$type] ?? 'gray';
    }
}
