<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function dashboard() {
        $user = Auth::user();
        
        // Traemos las inscripciones junto con las relaciones que necesitamos para calcular
        $enrollments = Enrollment::with([
            'course.category',
            'course.sections.lessons', // Necesario para contar lecciones totales
            'completedLessons'         // Relación hacia la tabla 'completed_lessons'
        ])
        ->where('user_id', $user->id)
        ->orderBy('last_accessed_at', 'desc') // Ordenamos por último acceso
        ->get();

        // Usamos nuestra función para empaquetar los datos
        $coursesData = $this->prepareCoursesData($enrollments);

        // Variables vacías por defecto para que no falle tu dashboard.blade.php
        // (Luego puedes cargar aquí tus consultas reales)
        $recentActivities = collect(); 
        $upcomingExams    = collect(); 
        $certificates     = collect(); 

        return view('student.dashboard', compact('user', 'coursesData', 'recentActivities', 'upcomingExams', 'certificates'));
    }

    public function myCourses() {
        $user = Auth::user();
        
        $enrollments = Enrollment::with([
            'course.category',
            'course.sections.lessons',
            'completedLessons'
        ])
        ->where('user_id', $user->id)
        ->whereHas('course', function($query) {
            $query->where('type', 'course');
        })
        ->orderBy('created_at', 'desc')
        ->get();

        $coursesData = $this->prepareCoursesData($enrollments);

        return view('student.my-courses', compact('enrollments', 'coursesData'));
    }

    public function learn(Course $course): View {
        return view('student.courses.learn', compact('course'));
    }

    /**
     * FUNCIÓN CLAVE: Procesa los enrollments y calcula el progreso real
     */
    private function prepareCoursesData($enrollments) {
        return $enrollments->map(function($enrollment) {
            $course = $enrollment->course;
            
            // 1. Contabilizar Módulos (Secciones activas)
            $modulesCount = $course->sections ? $course->sections->where('is_active', true)->count() : 0;
            
            // 2. Contabilizar Total de Lecciones (solo activas)
            $totalLessons = 0;
            if ($course->sections) {
                $totalLessons = $course->sections->where('is_active', true)->sum(function($section) {
                    return $section->lessons ? $section->lessons->where('is_active', true)->count() : 0;
                });
            }
            
            // 3. Contabilizar Lecciones Completadas desde la tabla 'completed_lessons'
            $completedLessonsCount = $enrollment->completedLessons ? $enrollment->completedLessons->count() : 0;
            
            // 4. Calcular Porcentaje de Progreso Real
            $progress = 0;
            if ($totalLessons > 0) {
                // Multiplicamos completadas / totales * 100 y redondeamos
                $progress = round(($completedLessonsCount / $totalLessons) * 100);
            }
            
            // 5. Opcional pero recomendado: Actualizar el progreso en la tabla enrollments
            if ($enrollment->progress != $progress) {
                $enrollment->update(['progress' => $progress]);
            }

            // Devolvemos el array EXACTO que esperan tus vistas blade y Alpine.js
            return [
                'id'                => $enrollment->id,
                'course_id'         => $course->id,
                'title'             => $course->title,
                'description'       => $course->description ?? '',
                'category'          => $course->category ? $course->category->name : 'Sin categoría',
                'image'             => $course->image_url ?: null,
                'progress'          => $progress,
                'status'            => $progress >= 100 ? 'completed' : 'in_progress',
                'modules'           => $modulesCount,
                'lessons'           => $totalLessons,
                'duration'          => $course->duration ?: 'Flexible',
                'enrolled_date'     => $enrollment->created_at->format('d/m/Y'),
                'last_accessed'     => $enrollment->last_accessed_at ? $enrollment->last_accessed_at->format('d/m/Y') : null,
                'completed_lessons' => $completedLessonsCount,
                'total_lessons'     => $totalLessons,
                'continue_url'      => route('student.course.learn', $course->id)
            ];
        });
    }
}
