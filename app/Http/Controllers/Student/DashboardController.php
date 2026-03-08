<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\LessonProgress;
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
        $user = Auth::user(); 
        
        // 1. Añadimos whereHas('course') para traer SOLO inscripciones que sean de tipo 'course'
        $enrollments = Enrollment::with(['course.category', 'course.sections.lessons'])
            ->where('user_id', $user->id)
            ->whereHas('course') // <-- Esta es la clave principal
            ->orderBy('created_at', 'desc')
            ->get();

        $coursesData = $enrollments->map(function($enrollment) {
            $course = $enrollment->course;

            // 2. Salvaguarda: Si el curso es null (ej. es un paquete), retornamos null
            if (!$course) {
                return null;
            }

            $progress       = $enrollment->progress ?: 0;
            $totalLessons   = 0;

            // Ahora es seguro calcular el total de lecciones
            if ($course->sections) {
                $totalLessons = $course->sections->sum(function($section) {
                    return $section->lessons ? $section->lessons->count() : 0;
                });
            }

            return [
                'id'                => $enrollment->id,
                'course_id'         => $course->id,
                'title'             => $course->title,
                'description'       => $course->description,
                'category'          => $course->category ? $course->category->name : 'Sin categoría',
                'image'             => $course->image_url ?: null,
                'progress'          => $progress,
                'status'            => $progress >= 100 ? 'completed' : 'in_progress',
                'modules'           => $course->sections ? $course->sections->count() : 0,
                'lessons'           => $totalLessons,
                'duration'          => $course->duration ?: '0 horas',
                'enrolled_date'     => $enrollment->created_at->format('d/m/Y'),
                'last_accessed'     => $enrollment->last_accessed_at ? 
                    $enrollment->last_accessed_at->format('d/m/Y H:i') : 
                    now()->format('d/m/Y H:i'),
                'completed_lessons' => $enrollment->completed_lessons_count ?: 0,
                'total_lessons'     => $totalLessons,
                'continue_url'      => route('student.course.learn', $course)
            ];
        })->filter()->values(); // 3. filter() elimina los 'null' del array y values() reordena los índices

        // 2. Actividad Reciente (Últimas 10)
        $recentActivities = UserActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // 3. Exámenes Pendientes (Exámenes de los cursos inscritos que aún no han sido aprobados)
        $upcomingExams = Exam::whereHas('course.enrollments', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereDoesntHave('examAttempts', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('passed', true);
            })
            ->where('is_active', true)
            ->limit(5)
            ->get();

        // 4. Certificados Obtenidos (Últimos obtenidos)
        $certificates = Certificate::with(['course', 'examAttempt'])
            ->where('user_id', $user->id)
            ->orderBy('issue_date', 'desc')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact('coursesData', 'recentActivities', 'upcomingExams', 'certificates'));
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
        $monthlyProgress = Enrollment::where('user_id', $user->id)->whereMonth('created_at', now()->month)->avg('progress') ?? 0;

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
                'title'         => 'Asistencia Perfecta',
                'description'   => 'Activo 5 de los últimos 7 días',
                'icon'          => 'calendar-check',
                'color'         => 'green'
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
            'lesson_completed'      => 'green',
            'course_enrolled'       => 'blue',
            'exam_taken'            => 'red',
            'certificate_earned'    => 'yellow',
            'comment'               => 'purple'
        ];

        return $colors[$type] ?? 'gray';
    }

    // Nuevas funciones 
    public function dashboardExams(): JsonResponse {
        $user = Auth::user();
        
        // Obtener exámenes pendientes del estudiante
        $exams = Exam::whereHas('course.enrollments', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('start_date', '>=', now())
            ->whereDoesntHave('examAttempts', function($q) use ($user) {
                $q->where('user_id', $user->id)
                ->where('passed', true);
            })
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();

        $formattedExams = $exams->map(function($exam) {
            $daysUntil = now()->diffInDays($exam->start_date);
            
            return [
                'id'        => $exam->id,
                'title'     => $exam->title,
                'course'    => $exam->course->title ?? 'Sin curso',
                'day'       => $exam->start_date->format('D'),
                'date'      => $exam->start_date->format('d/m'),
                'time'      => $exam->start_date->format('H:i'),
                'duration'  => $exam->duration ? $exam->duration . ' min' : 'Sin duración',
                'link'      => route('student.exams.show', $exam->id),
                'urgency'   => $daysUntil <= 2 ? 'high' : ($daysUntil <= 7 ? 'medium' : 'low')
            ];
        });

        return response()->json($formattedExams);
    }

    public function dashboardCertificates(): JsonResponse {
        $user = Auth::user();
        
        // Obtener certificados del estudiante
        $certificates = Certificate::with(['course', 'exam'])
            ->where('user_id', $user->id)
            ->orderBy('issued_at', 'desc')
            ->limit(5)
            ->get();

        $formattedCertificates = $certificates->map(function($certificate) {
            return [
                'id'            => $certificate->id,
                'title'         => $certificate->title ?? 'Certificado de Finalización',
                'course'        => $certificate->course->title ?? ($certificate->exam->title ?? 'Curso'),
                'date'          => $certificate->issued_at ? $certificate->issued_at->format('d/m/Y') : 'No emitido',
                'score'         => $certificate->score ? round($certificate->score, 1) . '%' : null,
                'link'          => route('student.certificates.show', $certificate->id),
                'download_link' => route('student.certificates.download-exact', $certificate->id)
            ];
        });

        return response()->json($formattedCertificates);
    }

    public function dashboardStats(Request $request): JsonResponse {
        $user = Auth::user();
        
        // Cursos activos
        $activeCourses = Enrollment::where('user_id', $user->id)
            ->whereHas('course', function($q) {
                $q->where('is_active', true);
            })
            ->count();

        // Exámenes pendientes
        $pendingExams = Exam::whereHas('course.enrollments', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('start_date', '>=', now())
            ->whereDoesntHave('examAttempts', function($q) use ($user) {
                $q->where('user_id', $user->id)
                ->where('passed', true);
            })
            ->count();

        // Certificados obtenidos
        $certificatesCount = Certificate::where('user_id', $user->id)->count();

        // Horas de estudio (ejemplo - deberías tener un campo o calcularlo)
        $studyHours = Enrollment::where('user_id', $user->id)
            ->with(['course' => function($q) {
                $q->select('id', 'duration');
            }])
            ->get()
            ->sum(function($enrollment) {
                // Convertir duración del curso a horas estimadas
                if ($enrollment->course && $enrollment->course->duration) {
                    preg_match('/(\d+)/', $enrollment->course->duration, $matches);
                    return $matches[1] ?? 0;
                }
                return 0;
            });

        // Progreso mensual promedio
        $monthlyProgress = Enrollment::where('user_id', $user->id)
            ->whereMonth('updated_at', now()->month)
            ->avg('progress') ?? 0;

        // Metas diarias (ejemplo)
        $today = now()->toDateString();
        $dailyGoals = [
            'lessonsCompleted'  => LessonProgress::where('user_id', $user->id)
                ->whereDate('completed_at', $today)
                ->count(),
            'totalLessons'      => 3, // Meta diaria
            'minutesStudied'    => LessonProgress::where('user_id', $user->id)
                ->whereDate('completed_at', $today)
                ->sum('time_watched') ?? 0,
            'targetMinutes'     => 60 // 1 hora de estudio diaria
        ];

        return response()->json([
            'activeCourses'     => $activeCourses,
            'pendingExams'      => $pendingExams,
            'certificatesCount' => $certificatesCount,
            'studyHours'        => $studyHours,
            'monthlyProgress'   => round($monthlyProgress, 1),
            'dailyGoals'        => $dailyGoals
        ]);
    }
}
