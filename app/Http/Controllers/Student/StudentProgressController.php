<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\CourseSection;
use App\Models\Exam;
use App\Models\Certificate;
use App\Models\CompletedLessons;
use App\Models\Document;
use App\Models\UserActivity;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentProgressController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'student', 'prevent.back']);
    }
    /**
     * Mostrar página principal de progreso
     */
    // public function index(Request $request): View {
    //     $user = Auth::user();

    //     // Obtener estadísticas generales
    //     $stats = $this->getProgressStats($user);

    //     // Obtener cursos con progreso
    //     $courses = $user->enrollments()
    //         ->with(['course' => function($query) {
    //             $query->withCount(['lessons', 'documents']);
    //         }])
    //         ->whereHas('course', function($query) {
    //             $query->where('is_active', true);
    //         })
    //         ->where('status', 'active')
    //         ->get()
    //         ->map(function($enrollment) {
    //             $course             = $enrollment->course;
    //             $totalLessons       = $course->lessons_count + $course->documents_count;
    //             $completedLessons   = $enrollment->completedLessons()->count();

    //             return [
    //                 'id'                => $course->id,
    //                 'title'             => $course->title,
    //                 'slug'              => $course->slug,
    //                 'image_url'         => $course->image_url,
    //                 'instructor'        => $course->instructor->names ?? 'Instructor',
    //                 'progress'          => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0,
    //                 'completed_lessons' => $completedLessons,
    //                 'total_lessons'     => $totalLessons,
    //                 'last_accessed'     => $enrollment->last_accessed_at,
    //                 'has_exam'          => $course->exam ? true : false,
    //                 'exam_status'       => $enrollment->exam_status,
    //                 'certificate_available' => $enrollment->certificate_available,
    //             ];
    //         });

    //     // Actividad reciente
    //     $recentActivity = UserActivity::where('user_id', $user->id)
    //         // ->where('action_type', 'lesson_completed')
    //         ->where('type', 'lesson_completed')
    //         ->with('course')
    //         ->latest()
    //         ->limit(10)
    //         ->get();

    //     // Cursos completados
    //     $completedCourses = $courses->where('progress', 100)->values();

    //     return view('student.progress.index', compact('stats', 'courses', 'completedCourses', 'recentActivity'));
    // }
    public function index(Request $request): View {
        $user = Auth::user();

        // Obtener estadísticas generales
        $stats = $this->getProgressStats($user);

        // Obtener TODOS los cursos con progreso (activos y completados)
        $courses = $user->enrollments()
            ->with(['course' => function($query) {
                // CORRECCIÓN 1: Cambiamos 'exam' por 'exams'
                $query->withCount(['lessons', 'documents'])->with('exams');
            }])
            ->whereHas('course', function($query) {
                $query->where('is_active', true);
            })
            ->get()
            ->map(function($enrollment) use ($user) {
                $course             = $enrollment->course;
                $totalLessons       = $course->lessons_count + $course->documents_count;
                $completedLessons   = $enrollment->completedLessons()->count();
                $progress           = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

                // Determinar la última lección completada o la primera del curso
                $lastCompleted = CompletedLessons::where('enrollment_id', $enrollment->id)
                    ->latest('completed_at')->first();
                
                $lastLessonId = null;
                if ($lastCompleted) {
                    $lastLessonId = $lastCompleted->lesson_id;
                } else {
                    $firstLesson = Lesson::where('course_id', $course->id)->orderBy('order')->first();
                    $lastLessonId = $firstLesson ? $firstLesson->id : null;
                }

                // CORRECCIÓN 2: Tomar el primer examen de la colección 'exams'
                $exam = $course->exams->first();
                $hasExam = $exam ? true : false;
                $examId = $exam ? $exam->id : null;
                
                // Verificar si aprobó el examen
                $hasPassedExam = false;
                if ($hasExam) {
                    $hasPassedExam = \App\Models\ExamAttempt::where('user_id', $user->id)
                        ->where('exam_id', $examId)
                        // CORRECCIÓN 3: Cambiamos 'is_passed' a 'passed' según tu modelo
                        ->where('passed', true)
                        ->exists();
                }

                // Verificar si ya tiene certificado
                $certificate = Certificate::where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->first();

                return [
                    'id'                => $course->id,
                    'title'             => $course->title,
                    'slug'              => $course->slug,
                    'image_url'         => $course->image_url,
                    'instructor'        => $course->instructor->names ?? 'Instructor',
                    'progress'          => $progress,
                    'completed_lessons' => $completedLessons,
                    'total_lessons'     => $totalLessons,
                    'last_accessed'     => $enrollment->last_accessed_at,
                    'has_exam'          => $hasExam,
                    'exam_id'           => $examId,
                    'has_passed_exam'   => $hasPassedExam,
                    'certificate_id'    => $certificate ? $certificate->id : null,
                    'last_lesson_id'    => $lastLessonId,
                ];
            });

        // Actividad reciente
        $recentActivity = UserActivity::where('user_id', $user->id)
            ->where('type', 'lesson_completed')
            ->with('course')
            ->latest()
            ->limit(10)
            ->get();

        // Filtrar completados para la sección lateral (y ordenarlos por más reciente)
        $completedCourses = $courses->where('progress', 100)->sortByDesc('last_accessed')->values();

        return view('student.progress.index', compact('stats', 'courses', 'completedCourses', 'recentActivity'));
    }

    /**
     * Obtener estadísticas de progreso
     */
    private function getProgressStats($user) {
        $enrollments = $user->enrollments()
            ->with('course')
            ->whereHas('course', function($query) {
                $query->where('is_active', true);
            })
            ->where('status', 'active')
            ->get();

        $totalCourses       = $enrollments->count();
        $completedCourses   = $enrollments->filter(function($enrollment) {
            $course = $enrollment->course;
            
            if (!$course) {
                return false;
            }

            $totalLessons       = $course->lessons()->count() + $course->documents()->count();
            $completedLessons   = $enrollment->completedLessons()->count();
            
            return $totalLessons > 0 && ($completedLessons / $totalLessons) >= 1;
        })->count();

        // SOLUCIÓN: Sumamos los minutos directamente de las lecciones completadas del usuario
        $totalMinutes = CompletedLessons::whereHas('enrollment', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->sum('time_spent_minutes');

        $totalStudyHours = $totalMinutes > 0 ? ($totalMinutes / 60) : 0;

        // Días consecutivos estudiando
        $streakDays = $this->calculateStreakDays($user);

        return [
            'total_courses'     => $totalCourses,
            'completed_courses' => $completedCourses,
            'completion_rate'   => $totalCourses > 0 ? round(($completedCourses / $totalCourses) * 100) : 0,
            'total_study_hours' => round($totalStudyHours, 1),
            'streak_days'       => $streakDays,
            'average_progress'  => $enrollments->avg('progress_percentage') ?? 0,
        ];
    }

    /**
     * Calcular días consecutivos de estudio
     */
    private function calculateStreakDays($user) {
        // SOLUCIÓN: Cambiamos 'action_type' por 'type'
        $activities = UserActivity::where('user_id', $user->id)
            ->where('type', UserActivity::TYPE_LESSON_COMPLETED) // <-- ESTA ES LA LÍNEA CORREGIDA
            ->select(DB::raw('DATE(created_at) as date'))
            ->distinct()
            ->orderBy('date', 'desc')
            ->get()
            ->pluck('date')
            ->map(function($date) {
                return Carbon::parse($date);
            });

        $streak = 0;
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Verificar si estudió hoy
        if ($activities->contains(function($date) use ($today) {
            return $date->isSameDay($today);
        })) {
            $streak = 1;

            // Contar días consecutivos hacia atrás
            $currentDate = $yesterday;
            foreach ($activities as $activityDate) {
                if ($activityDate->isSameDay($currentDate)) {
                    $streak++;
                    $currentDate = $currentDate->subDay();
                } elseif ($activityDate->lt($currentDate)) {
                    break;
                }
            }
        }
        // Verificar si estudió ayer (para mantener racha)
        elseif ($activities->contains(function($date) use ($yesterday) {
            return $date->isSameDay($yesterday);
        })) {
            $streak = 1;

            // Contar días consecutivos hacia atrás
            $currentDate = $yesterday->subDay();
            foreach ($activities as $activityDate) {
                if ($activityDate->isSameDay($currentDate)) {
                    $streak++;
                    $currentDate = $currentDate->subDay();
                } elseif ($activityDate->lt($currentDate)) {
                    break;
                }
            }
        }

        return $streak;
    }

    /**
     * Obtener progreso detallado de un curso
     */
    public function getCourseProgress($courseId) {
        $user = Auth::user();

        // Verificar inscripción
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->firstOrFail();

        $course = Course::with(['sections.lessons', 'documents', 'exam'])->findOrFail($courseId);

        // Progreso por sección
        $sectionsProgress   = [];
        $totalLessons       = 0;
        $completedLessons   = 0;

        foreach ($course->sections as $section) {
            $sectionLessons     = $section->lessons->count();
            $sectionDocuments   = $course->documents()->where('section_id', $section->id)->count();
            $sectionTotal       = $sectionLessons + $sectionDocuments;

            $sectionCompleted = $enrollment->completedLessons()
                ->whereIn('lesson_id', $section->lessons->pluck('id'))
                ->count();

            $documentsCompleted = $enrollment->completedDocuments()
                ->where('section_id', $section->id)
                ->count();

            $totalCompleted = $sectionCompleted + $documentsCompleted;

            $sectionsProgress[] = [
                'id'                => $section->id,
                'title'             => $section->title,
                'order'             => $section->order,
                'total_items'       => $sectionTotal,
                'completed_items'   => $totalCompleted,
                'progress'          => $sectionTotal > 0 ? round(($totalCompleted / $sectionTotal) * 100) : 0,
                'lessons'           => $section->lessons->map(function($lesson) use ($enrollment) {
                    $isCompleted = $enrollment->completedLessons()->where('lesson_id', $lesson->id)->exists();

                    return [
                        'id'            => $lesson->id,
                        'title'         => $lesson->title,
                        'type'          => $lesson->type,
                        'duration'      => $lesson->duration_minutes,
                        'is_completed'  => $isCompleted,
                        'completed_at'  => $isCompleted ?
                            $enrollment->completedLessons()->where('lesson_id', $lesson->id)->first()->pivot->completed_at : null,
                    ];
                }),
            ];

            $totalLessons += $sectionTotal;
            $completedLessons += $totalCompleted;
        }

        // Estadísticas del curso
        $courseProgress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        // Información del examen
        $examInfo = null;
        if ($course->exam) {
            $examAttempt = $user->examAttempts()
                ->where('exam_id', $course->exam->id)
                ->latest()
                ->first();

            $examInfo = [
                'has_exam'          => true,
                'exam_id'           => $course->exam->id,
                'title'             => $course->exam->title,
                'description'       => $course->exam->description,
                'passing_score'     => $course->exam->passing_score,
                'time_limit'        => $course->exam->time_limit,
                'attempts_count'    => $user->examAttempts()->where('exam_id', $course->exam->id)->count(),
                'best_score'        => $examAttempt ? $examAttempt->score : null,
                'is_passed'         => $examAttempt ? $examAttempt->is_passed : false,
                'last_attempt'      => $examAttempt ? $examAttempt->created_at : null,
            ];
        }

        // Información del certificado
        $certificateInfo = null;
        $certificate = Certificate::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->first();

        if ($certificate) {
            $certificateInfo = [
                'has_certificate'   => true,
                'certificate_id'    => $certificate->id,
                'issue_date'        => $certificate->issue_date,
                'expiration_date'   => $certificate->expiration_date,
                'download_url'      => route('certificates.download', $certificate->id),
            ];
        }

        return response()->json([
            'success' => true,
            'course' => [
                'id'            => $course->id,
                'title'         => $course->title,
                'description'   => $course->description,
                'thumbnail'     => $course->thumbnail_path,
                'instructor'    => $course->instructor->names ?? 'Instructor',
            ],
            'progress'          => $courseProgress,
            'total_items'       => $totalLessons,
            'completed_items'   => $completedLessons,
            'sections'          => $sectionsProgress,
            'exam'              => $examInfo,
            'certificate'       => $certificateInfo,
            'last_accessed'     => $enrollment->last_accessed_at,
            'enrollment_date'   => $enrollment->created_at,
        ]);
    }

    /**
     * Marcar lección como completada (CON NOTIFICACIÓN)
     */
    public function completeLesson(Request $request, $lessonId) {
        $user   = Auth::user();
        $lesson = Lesson::with('section.course')->findOrFail($lessonId);
        $course = $lesson->section->course;

        // Verificar inscripción
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->firstOrFail();

        // Verificar si ya está completada
        $isAlreadyCompleted = $enrollment->completedLessons()
            ->where('lesson_id', $lessonId)
            ->exists();

        if (!$isAlreadyCompleted) {
            // Marcar como completada
            $enrollment->completedLessons()->attach($lessonId, [
                'completed_at' => now(),
                'time_spent_minutes' => $request->input('time_spent', 0),
            ]);

            // Actualizar última fecha de acceso
            $enrollment->update([
                'last_accessed_at'      => now(),
                'progress_percentage'   => $this->calculateCourseProgress($enrollment, $course),
            ]);

            // Registrar actividad
            UserActivity::create([
                'user_id'       => $user->id,
                'course_id'     => $course->id,
                'action_type'   => 'lesson_completed',
                'details'       => json_encode([
                    'lesson_id'     => $lessonId,
                    'lesson_title'  => $lesson->title,
                    'course_title'  => $course->title,
                    'time_spent'    => $request->input('time_spent', 0),
                ]),
                'duration_minutes' => $request->input('time_spent', 0),
            ]);

            // Calcular progreso actualizado
            $newProgress = $this->calculateCourseProgress($enrollment, $course);

            // NOTIFICACIÓN: Verificar si el curso está completo (100%)
            if ($newProgress >= 100) {
                // Marcar inscripción como completada
                $enrollment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                // Enviar notificación de curso completado
                NotificationService::sendCourseCompletedNotification($user, $course);

                // Si el curso tiene examen, enviar notificación de examen pendiente
                if ($course->exam) {
                    NotificationService::sendExamPendingNotification($user, $course);

                    // Actualizar estado del examen en la inscripción
                    $enrollment->update(['exam_status' => 'pending']);
                }
            }

            // NOTIFICACIÓN: Hitos de progreso (25%, 50%, 75%)
            $this->checkProgressMilestones($user, $course, $newProgress);

            return response()->json([
                'success'               => true,
                'message'               => 'Lección marcada como completada',
                'progress'              => $newProgress,
                'is_course_completed'   => $newProgress >= 100,
            ]);
        }

        return response()->json([
            'success'   => false,
            'message'   => 'Esta lección ya estaba completada',
            'progress'  => $enrollment->progress_percentage,
        ]);
    }

    /**
     * Marcar documento como completado
     */
    public function completeDocument(Request $request, $documentId) {
        $user       = Auth::user();
        $document   = Document::with('course')->findOrFail($documentId);
        $course     = $document->course;

        // Verificar inscripción
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->firstOrFail();

        // Marcar como completado
        $enrollment->completedDocuments()->attach($documentId, [
            'completed_at' => now(),
            'time_spent_minutes' => $request->input('time_spent', 0),
        ]);

        // Actualizar progreso
        $newProgress = $this->calculateCourseProgress($enrollment, $course);
        $enrollment->update([
            'progress_percentage'   => $newProgress,
            'last_accessed_at'      => now(),
        ]);

        // Registrar actividad
        UserActivity::create([
            'user_id'       => $user->id,
            'course_id'     => $course->id,
            'action_type'   => 'document_completed',
            'details'       => json_encode([
                'document_id'       => $documentId,
                'document_title'    => $document->title,
                'course_title'      => $course->title,
                'time_spent'        => $request->input('time_spent', 0),
            ]),
            'duration_minutes' => $request->input('time_spent', 0),
        ]);

        // NOTIFICACIÓN: Verificar si el curso está completo (100%)
        if ($newProgress >= 100) {
            $enrollment->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            NotificationService::sendCourseCompletedNotification($user, $course);

            if ($course->exam) {
                NotificationService::sendExamPendingNotification($user, $course);
                $enrollment->update(['exam_status' => 'pending']);
            }
        }

        // NOTIFICACIÓN: Hitos de progreso
        $this->checkProgressMilestones($user, $course, $newProgress);

        return response()->json([
            'success'               => true,
            'message'               => 'Documento marcado como completado',
            'progress'              => $newProgress,
            'is_course_completed'   => $newProgress >= 100,
        ]);
    }

    /**
     * Calcular progreso del curso
     */
    private function calculateCourseProgress($enrollment, $course) {
        $totalLessons   = $course->lessons()->count();
        $totalDocuments = $course->documents()->count();
        $totalItems     = $totalLessons + $totalDocuments;

        if ($totalItems === 0) {
            return 0;
        }

        $completedLessons   = $enrollment->completedLessons()->count();
        $completedDocuments = $enrollment->completedDocuments()->count();
        $completedItems     = $completedLessons + $completedDocuments;

        return round(($completedItems / $totalItems) * 100);
    }

    /**
     * Verificar y notificar hitos de progreso
     */
    private function checkProgressMilestones($user, $course, $progress) {
        $milestones = [25, 50, 75];

        foreach ($milestones as $milestone) {
            if ($progress >= $milestone && $progress < $milestone + 10) {
                // Verificar si ya notificamos este hito
                $alreadyNotified = \App\Models\Notification::where('user_id', $user->id)
                    ->where('type', 'progress_milestone')
                    ->whereJsonContains('data->milestone', $milestone)
                    ->whereJsonContains('data->course_id', $course->id)
                    ->exists();

                if (!$alreadyNotified) {
                    NotificationService::create(
                        $user,
                        'progress_milestone',
                        '¡Hito alcanzado!',
                        "Has completado el {$milestone}% del curso '{$course->title}'. ¡Sigue así!",
                        [
                            'course_id'     => $course->id,
                            'course_title'  => $course->title,
                            'milestone'     => $milestone,
                            'progress'      => $progress,
                        ],
                        route('student.progress')
                    );
                }
                break;
            }
        }
    }

    /**
     * Obtener actividad reciente (API)
     */
    public function getRecentActivity(Request $request) {
        $user = Auth::user();

        $activities = UserActivity::where('user_id', $user->id)
            ->with('course')
            ->latest()
            ->limit($request->input('limit', 20))
            ->get()
            ->map(function($activity) {
                // $details = json_decode($activity->details, true);
                $details = $activity->data;

                return [
                    'id'            => $activity->id,
                    // 'type'          => $activity->action_type,
                    'type'          => $activity->type,
                    'description'   => $this->getActivityDescription($activity),
                    'course'    => $activity->course ? [
                        'id'    => $activity->course->id,
                        'title' => $activity->course->title,
                        'slug'  => $activity->course->slug,
                    ] : null,
                    'time'      => $activity->created_at->diffForHumans(),
                    'date'      => $activity->created_at->format('d/m/Y H:i'),
                    'details'   => $details,
                    // 'duration'  => $activity->duration_minutes,
                ];
            });

        return response()->json([
            'success'       => true,
            'activities'    => $activities,
            'total'         => $activities->count(),
        ]);
    }

    /**
     * Generar descripción de actividad
     */
    private function getActivityDescription($activity) {
        $details = json_decode($activity->details, true);

        switch ($activity->action_type) {
            case 'lesson_completed':
                return "Completaste la lección: {$details['lesson_title']}";
            case 'document_completed':
                return "Completaste el documento: {$details['document_title']}";
            case 'exam_started':
                return "Iniciaste el examen: {$details['exam_title']}";
            case 'exam_completed':
                return "Completaste el examen: {$details['exam_title']}";
            case 'course_enrolled':
                return "Te inscribiste al curso: {$details['course_title']}";
            default:
                return "Actividad realizada";
        }
    }

    /**
     * Obtener estadísticas para gráficos (API)
     */
    public function getChartData(Request $request) {
        $user = Auth::user();

        // Progreso por día (últimos 30 días)
        $dailyProgress = UserActivity::where('user_id', $user->id)
            ->where('action_type', 'lesson_completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as lessons_completed'),
                DB::raw('SUM(duration_minutes) as total_minutes')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Progreso por curso
        $courseProgress = $user->enrollments()
            ->with('course')
            ->where('status', 'active')
            ->get()
            ->map(function($enrollment) {
                return [
                    'course' => $enrollment->course->title,
                    'progress' => $enrollment->progress_percentage,
                ];
            });

        return response()->json([
            'success' => true,
            'daily_progress' => [
                'dates' => $dailyProgress->pluck('date'),
                'lessons' => $dailyProgress->pluck('lessons_completed'),
                'minutes' => $dailyProgress->pluck('total_minutes'),
            ],
            'course_progress' => $courseProgress,
        ]);
    }

    /**
     * Reiniciar progreso de un curso
     */
    public function resetCourseProgress(Request $request, $courseId) {
        $user       = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)->where('course_id', $courseId)->firstOrFail();

        // Eliminar lecciones completadas
        $enrollment->completedLessons()->detach();
        $enrollment->completedDocuments()->detach();

        // Reiniciar estadísticas
        $enrollment->update([
            'progress_percentage'   => 0,
            'status'                => 'active',
            'completed_at'          => null,
            'exam_status'           => null,
        ]);

        // NOTIFICACIÓN: Notificar reinicio de progreso
        $course = Course::find($courseId);
        NotificationService::create(
            $user,
            'progress_reset',
            'Progreso reiniciado',
            "Has reiniciado el progreso del curso '{$course->title}'. ¡Puedes comenzar de nuevo!",
            [
                'course_id'     => $courseId,
                'course_title'  => $course->title,
            ],
            route('course.show', $courseId)
        );

        return response()->json([
            'success' => true,
            'message' => 'Progreso del curso reiniciado exitosamente',
        ]);
    }

    /**
     * Obtener insignias/logros (API)
     */
    public function getAchievements() {
        $user           = Auth::user();
        $achievements   = $this->calculateAchievements($user);
        return response()->json([
            'success'       => true,
            'achievements'  => $achievements,
        ]);
    }

    /**
     * Calcular logros del usuario
     */
    private function calculateAchievements($user) {
        $achievements = [];

        // Logros basados en cursos completados
        $completedCourses = $user->enrollments()
            ->where('status', 'completed')
            ->count();

        if ($completedCourses >= 1) {
            $achievements[] = [
                'id'            => 'first_course',
                'title'         => 'Primer curso completado',
                'description'   => 'Completaste tu primer curso',
                'icon'          => 'fas fa-medal',
                'color'         => 'bronze',
                'unlocked_at'   => $user->enrollments()
                    ->where('status', 'completed')
                    ->oldest('completed_at')
                    ->first()->completed_at ?? now(),
            ];
        }

        if ($completedCourses >= 3) {
            $achievements[] = [
                'id'            => 'three_courses',
                'title'         => 'Triplete académico',
                'description'   => 'Completaste 3 cursos',
                'icon'          => 'fas fa-medal',
                'color'         => 'silver',
                'unlocked_at'   => now(),
            ];
        }

        if ($completedCourses >= 5) {
            $achievements[] = [
                'id'            => 'five_courses',
                'title'         => 'Maestro del aprendizaje',
                'description'   => 'Completaste 5 cursos',
                'icon'          => 'fas fa-medal',
                'color'         => 'gold',
                'unlocked_at'   => now(),
            ];
        }

        // Logro por racha de estudio
        $streakDays = $this->calculateStreakDays($user);

        if ($streakDays >= 7) {
            $achievements[] = [
                'id'            => 'week_streak',
                'title'         => 'Racha semanal',
                'description'   => 'Estudiaste 7 días consecutivos',
                'icon'          => 'fas fa-fire',
                'color'         => 'orange',
                'unlocked_at'   => now(),
            ];
        }

        if ($streakDays >= 30) {
            $achievements[] = [
                'id'            => 'month_streak',
                'title'         => 'Racha mensual',
                'description'   => 'Estudiaste 30 días consecutivos',
                'icon'          => 'fas fa-fire',
                'color'         => 'red',
                'unlocked_at'   => now(),
            ];
        }

        // Logro por horas de estudio
        $totalHours = UserActivity::where('user_id', $user->id)->sum('duration_minutes') / 60;

        if ($totalHours >= 10) {
            $achievements[] = [
                'id'            => '10_hours',
                'title'         => '10 horas de estudio',
                'description'   => 'Completaste 10 horas de estudio',
                'icon'          => 'fas fa-clock',
                'color'         => 'blue',
                'unlocked_at'   => now(),
            ];
        }

        if ($totalHours >= 50) {
            $achievements[] = [
                'id'            => '50_hours',
                'title'         => '50 horas de estudio',
                'description'   => 'Completaste 50 horas de estudio',
                'icon'          => 'fas fa-clock',
                'color'         => 'purple',
                'unlocked_at'   => now(),
            ];
        }

        return $achievements;
    }

    /**
     * Exportar progreso como PDF
     */
    public function exportProgressPDF($courseId = null) {
        $user = Auth::user();

        if ($courseId) {
            // Exportar progreso de un curso específico
            $enrollment = Enrollment::where('user_id', $user->id)
                ->where('course_id', $courseId)
                ->firstOrFail();

            $data = [
                'user'              => $user,
                'course'            => $enrollment->course,
                'enrollment'        => $enrollment,
                'completed_lessons' => $enrollment->completedLessons()->count(),
                'total_lessons'     => $enrollment->course->lessons()->count() + $enrollment->course->documents()->count(),
                'progress'          => $enrollment->progress_percentage,
            ];

            // TODO: Generar PDF específico del curso
            // return PDF::loadView('student.progress.pdf.course', $data)->download();

        } else {
            // Exportar progreso general
            $stats      = $this->getProgressStats($user);
            $courses    = $user->enrollments()->with('course')->where('status', 'active')->get();

            $data = [
                'user'          => $user,
                'stats'         => $stats,
                'courses'       => $courses,
                'export_date'   => now()->format('d/m/Y H:i'),
            ];

            // TODO: Generar PDF general
            // return PDF::loadView('student.progress.pdf.overview', $data)->download();
        }

        return response()->json([
            'success'   => true,
            'message'   => 'Exportación de progreso (PDF) - En desarrollo',
            'data'      => $data,
        ]);
    }

    /**
     * Sincronizar progreso offline
     */
    public function syncOfflineProgress(Request $request) {
        $user           = Auth::user();
        $completedItems = $request->input('completed_items', []);
        $syncResults    = [];

        foreach ($completedItems as $item) {
            $type           = $item['type']; // 'lesson' o 'document'
            $id             = $item['id'];
            $timeSpent      = $item['time_spent'] ?? 0;
            $completedAt    = $item['completed_at'] ?? now();

            try {
                if ($type === 'lesson') {
                    $lesson = Lesson::with('section.course')->find($id);
                    if ($lesson) {
                        $course = $lesson->section->course;
                        $enrollment = Enrollment::firstOrCreate([
                            'user_id'   => $user->id,
                            'course_id' => $course->id,
                        ], [
                            'status'        => 'active',
                            'enrolled_at'   => now(),
                        ]);

                        // Marcar como completada
                        $enrollment->completedLessons()->syncWithoutDetaching([
                            $id => [
                                'completed_at'          => $completedAt,
                                'time_spent_minutes'    => $timeSpent,
                            ]
                        ]);

                        $syncResults[] = [
                            'type'      => 'lesson',
                            'id'        => $id,
                            'success'   => true,
                            'message'   => 'Lección sincronizada',
                        ];
                    }
                } elseif ($type === 'document') {
                    $document = Document::with('course')->find($id);
                    if ($document) {
                        $course     = $document->course;
                        $enrollment = Enrollment::firstOrCreate([
                            'user_id'   => $user->id,
                            'course_id' => $course->id,
                        ], [
                            'status' => 'active',
                            'enrolled_at' => now(),
                        ]);

                        // Marcar como completado
                        $enrollment->completedDocuments()->syncWithoutDetaching([
                            $id => [
                                'completed_at'          => $completedAt,
                                'time_spent_minutes'    => $timeSpent,
                            ]
                        ]);

                        $syncResults[] = [
                            'type'      => 'document',
                            'id'        => $id,
                            'success'   => true,
                            'message'   => 'Documento sincronizado',
                        ];
                    }
                }
            } catch (\Exception $e) {
                $syncResults[] = [
                    'type'      => $type,
                    'id'        => $id,
                    'success'   => false,
                    'message'   => 'Error: ' . $e->getMessage(),
                ];
            }
        }

        // NOTIFICACIÓN: Sincronización completada
        if (!empty($syncResults)) {
            $successCount = count(array_filter($syncResults, fn($r) => $r['success']));

            NotificationService::create(
                $user,
                'progress_synced',
                'Progreso sincronizado',
                "Se sincronizaron {$successCount} elementos desde modo offline.",
                [
                    'total_items'       => count($completedItems),
                    'successful_items'  => $successCount,
                    'failed_items'      => count($completedItems) - $successCount,
                ],
                route('student.progress')
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Progreso sincronizado',
            'results' => $syncResults,
            'total_synced' => count(array_filter($syncResults, fn($r) => $r['success'])),
        ]);
    }
}
