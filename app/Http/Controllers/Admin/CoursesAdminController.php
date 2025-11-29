<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Document;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CoursesAdminController extends Controller {

    /**
     * Display a listing of the courses.
     */
    public function index(Request $request): View {
        $query = Course::with(['category', 'instructor', 'enrollments']);
        // Filtros
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('instructor_id') && $request->instructor_id) {
            $query->where('instructor_id', $request->instructor_id);
        }

        if ($request->has('level') && $request->level) {
            $query->where('level', $request->level);
        }

        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('instructor', function($q) use ($search) {
                        $q->where('names', 'like', "%{$search}%");
                    });
            });
        }

        // Ordenamiento
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
        }

        $courses = $query->paginate(15);
        $categories = Category::where('is_active', true)->get();
        $instructors = User::where('role', 'instructor')->get();

        return view('admin.courses.index', compact('courses', 'categories', 'instructors'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): View {
        $categories = Category::where('is_active', true)->get();
        $instructors = User::where('role', 'instructor')->get();

        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'required|exists:categories,id',
            'instructor_id'     => 'required|exists:users,id',
            'level'             => 'required|in:beginner,intermediate,advanced',
            'price'             => 'required|numeric|min:0',
            'promotion_price'   => 'nullable|numeric|min:0|lt:price',
            'duration'          => 'nullable|integer|min:1',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'requirements'      => 'nullable|array',
            'requirements.*'    => 'string|max:255',
            'what_you_learn'    => 'nullable|array',
            'what_you_learn.*'  => 'string|max:255',
            'is_active'         => 'boolean',
        ]);

        // Generar slug único
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Procesar imagen
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        // Crear curso
        $course = Course::create([
            'title'             => $validated['title'],
            'slug'              => $slug,
            'description'       => $validated['description'],
            'short_description' => $validated['short_description'],
            'category_id'       => $validated['category_id'],
            'instructor_id'     => $validated['instructor_id'],
            'level'             => $validated['level'],
            'price'             => $validated['price'],
            'promotion_price'   => $validated['promotion_price'],
            'duration'          => $validated['duration'],
            'image_url'         => $imagePath,
            'requirements'      => $validated['requirements'] ? array_filter($validated['requirements']) : null,
            'what_you_learn'    => $validated['what_you_learn'] ? array_filter($validated['what_you_learn']) : null,
            'is_active'         => $request->has('is_active'),
        ]);

        // Crear examen por defecto
        Exam::create([
            'course_id'     => $course->id,
            'title'         => "Examen Final - {$course->title}",
            'description'   => "Examen de certificación para el curso {$course->title}",
            'duration'      => 60,
            'passing_score' => 14.00,
            'max_attempts'  => 3,
            'is_active'     => true,
        ]);

        $this->logActivity("Creó el curso: {$course->title}");
        return redirect()->route('admin.courses.edit', $course->id)
            ->with('success', 'Curso creado exitosamente. Ahora puedes agregar secciones y lecciones.');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): View {
        $course->load(['category', 'instructor', 'sections.lessons', 'documents', 'exam', 'enrollments.user']);

        $stats = [
            'total_students'        => $course->enrollments()->count(),
            'completed_students'    => $course->enrollments()->where('status', 'completed')->count(),
            'total_revenue'         => $course->enrollments()
                ->join('payments', 'enrollments.id', '=', 'payments.enrollment_id')
                ->where('payments.status', 'completed')
                ->sum('payments.amount'),
            'average_rating' => 4.8, // Esto vendría de un sistema de reviews
        ];

        return view('admin.courses.show', compact('course', 'stats'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course) {
        $course->load(['sections.lessons', 'documents', 'exam.questions']);
        $categories = Category::where('is_active', true)->get();
        $instructors = User::where('role', 'instructor')->get();

        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, Course $course) {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'required|exists:categories,id',
            'instructor_id'     => 'required|exists:users,id',
            'level'             => 'required|in:beginner,intermediate,advanced',
            'price'             => 'required|numeric|min:0',
            'promotion_price'   => 'nullable|numeric|min:0|lt:price',
            'duration'          => 'nullable|integer|min:1',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'requirements'      => 'nullable|array',
            'requirements.*'    => 'string|max:255',
            'what_you_learn'    => 'nullable|array',
            'what_you_learn.*'  => 'string|max:255',
            'is_active'         => 'boolean',
        ]);

        // Actualizar slug si el título cambió
        if ($course->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $counter = 1;

            while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        // Procesar imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($course->image_url) {
                Storage::disk('public')->delete($course->image_url);
            }
            $validated['image_url'] = $request->file('image')->store('courses', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['requirements'] = $validated['requirements'] ? array_filter($validated['requirements']) : null;
        $validated['what_you_learn'] = $validated['what_you_learn'] ? array_filter($validated['what_you_learn']) : null;

        $course->update($validated);

        $this->logActivity("Actualizó el curso: {$course->title}");
        return redirect()->route('admin.courses.edit', $course->id)->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course) {
        // Verificar si hay inscripciones activas
        if ($course->enrollments()->where('status', 'active')->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar el curso porque tiene estudiantes inscritos activamente.');
        }

        $courseTitle = $course->title;

        // Eliminar recursos asociados
        if ($course->image_url) {
            Storage::disk('public')->delete($course->image_url);
        }

        $course->delete();

        $this->logActivity("Eliminó el curso: {$courseTitle}");
        return redirect()->route('admin.courses.index')->with('success', 'Curso eliminado exitosamente.');
    }

    /**
     * Toggle course status (active/inactive)
     */
    public function toggleStatus(Course $course): JsonResponse {
        $course->update([
            'is_active' => !$course->is_active
        ]);

        $status = $course->is_active ? 'activó' : 'desactivó';
        $this->logActivity("{$status} el curso: {$course->title}");

        return response()->json([
            'success' => true,
            'is_active' => $course->is_active,
            'message' => 'Estado del curso actualizado.'
        ]);
    }

    /**
     * Duplicate a course
     */
    public function duplicate(Course $course) {
        DB::transaction(function () use ($course) {
            // Duplicar curso
            $newCourse          = $course->replicate();
            $newCourse->title   = $course->title . ' (Copia)';
            $newCourse->slug    = Str::slug($newCourse->title) . '-' . uniqid();
            $newCourse->is_active = false;
            $newCourse->save();

            // Duplicar secciones y lecciones
            foreach ($course->sections as $section) {
                $newSection = $section->replicate();
                $newSection->course_id = $newCourse->id;
                $newSection->save();

                foreach ($section->lessons as $lesson) {
                    $newLesson = $lesson->replicate();
                    $newLesson->course_section_id = $newSection->id;
                    $newLesson->save();
                }
            }

            // Duplicar examen
            if ($course->exam) {
                $newExam = $course->exam->replicate();
                $newExam->course_id = $newCourse->id;
                $newExam->title = "Examen Final - {$newCourse->title}";
                $newExam->save();

                // Duplicar preguntas del examen
                foreach ($course->exam->questions as $question) {
                    $newQuestion = $question->replicate();
                    $newQuestion->exam_id = $newExam->id;
                    $newQuestion->save();
                }
            }
        });

        $this->logActivity("Duplicó el curso: {$course->title}");
        return redirect()->route('admin.courses.index')->with('success', 'Curso duplicado exitosamente.');
    }

    /**
     * Course Sections Management
     */
    public function addSection(Request $request, Course $course): JsonResponse {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
        ]);

        // Obtener el último orden
        $lastOrder = $course->sections()->max('order') ?? 0;

        $section = CourseSection::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'order' => $lastOrder + 1,
            'is_active' => true,
        ]);

        $this->logActivity("Agregó sección '{$section->title}' al curso: {$course->title}");

        return response()->json([
            'success' => true,
            'section' => $section,
            'message' => 'Sección agregada exitosamente.'
        ]);
    }

    public function updateSection(Request $request, Course $course, CourseSection $section): JsonResponse {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'order'         => 'required|integer|min:0',
        ]);

        $section->update($validated);

        $this->logActivity("Actualizó sección '{$section->title}' del curso: {$course->title}");

        return response()->json([
            'success' => true,
            'message' => 'Sección actualizada exitosamente.'
        ]);
    }

    public function deleteSection(Course $course, CourseSection $section): JsonResponse {
        // Verificar si la sección tiene lecciones
        if ($section->lessons()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la sección porque contiene lecciones.'
            ], 422);
        }

        $sectionTitle = $section->title;
        $section->delete();

        // Reordenar las secciones restantes
        $this->reorderSections($course);

        $this->logActivity("Eliminó sección '{$sectionTitle}' del curso: {$course->title}");

        return response()->json([
            'success' => true,
            'message' => 'Sección eliminada exitosamente.'
        ]);
    }

    public function toggleSectionStatus(Course $course, CourseSection $section): JsonResponse {
        $section->update([
            'is_active' => !$section->is_active
        ]);

        $status = $section->is_active ? 'activó' : 'desactivó';
        $this->logActivity("{$status} sección '{$section->title}' del curso: {$course->title}");

        return response()->json([
            'success' => true,
            'is_active' => $section->is_active,
            'message' => 'Estado de la sección actualizado.'
        ]);
    }

    /**
     * Lessons Management
     */
    public function addLesson(Request $request, Course $course, CourseSection $section): JsonResponse {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'video_url'     => 'nullable|url',
            'duration'      => 'nullable|integer|min:0',
            'is_free'       => 'boolean',
        ]);

        // Obtener el último orden
        $lastOrder = $section->lessons()->max('order') ?? 0;

        $lesson = Lesson::create([
            'course_section_id' => $section->id,
            'title'             => $validated['title'],
            'description'       => $validated['description'],
            'video_url'         => $validated['video_url'],
            'duration'          => $validated['duration'],
            'order'             => $lastOrder + 1,
            'is_free'           => $request->has('is_free'),
            'is_active'         => true,
        ]);

        $this->logActivity("Agregó lección '{$lesson->title}' a la sección '{$section->title}'");

        return response()->json([
            'success'   => true,
            'lesson'    => $lesson,
            'message'   => 'Lección agregada exitosamente.'
        ]);
    }

    public function updateLesson(Request $request, Course $course, CourseSection $section, Lesson $lesson): JsonResponse {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'video_url'     => 'nullable|url',
            'duration'      => 'nullable|integer|min:0',
            'order'         => 'required|integer|min:0',
            'is_free'       => 'boolean',
        ]);

        $lesson->update($validated);

        $this->logActivity("Actualizó lección '{$lesson->title}' de la sección '{$section->title}'");

        return response()->json([
            'success' => true,
            'message' => 'Lección actualizada exitosamente.'
        ]);
    }

    public function deleteLesson(Course $course, CourseSection $section, Lesson $lesson): JsonResponse {
        $lessonTitle = $lesson->title;
        $lesson->delete();

        // Reordenar las lecciones restantes
        $this->reorderLessons($section);

        $this->logActivity("Eliminó lección '{$lessonTitle}' de la sección '{$section->title}'");

        return response()->json([
            'success' => true,
            'message' => 'Lección eliminada exitosamente.'
        ]);
    }

    public function toggleLessonStatus(Course $course, CourseSection $section, Lesson $lesson): JsonResponse {
        $lesson->update([
            'is_active' => !$lesson->is_active
        ]);

        $status = $lesson->is_active ? 'activó' : 'desactivó';
        $this->logActivity("{$status} lección '{$lesson->title}' de la sección '{$section->title}'");

        return response()->json([
            'success' => true,
            'is_active' => $lesson->is_active,
            'message' => 'Estado de la lección actualizado.'
        ]);
    }

    /**
     * Course Documents Management
     */
    public function uploadDocument(Request $request, Course $course) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('document');
        $filePath = $file->store('documents', 'public');

        $document = Document::create([
            'course_id' => $course->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'is_active' => true,
        ]);

        $this->logActivity("Subió documento '{$document->title}' al curso: {$course->title}");

        return response()->json([
            'success' => true,
            'document' => $document,
            'message' => 'Documento subido exitosamente.'
        ]);
    }

    public function deleteDocument(Course $course, Document $document): JsonResponse {
        // Eliminar archivo físico
        Storage::disk('public')->delete($document->file_path);
        $documentTitle = $document->title;
        $document->delete();

        $this->logActivity("Eliminó documento '{$documentTitle}' del curso: {$course->title}");

        return response()->json([
            'success' => true,
            'message' => 'Documento eliminado exitosamente.'
        ]);
    }

    /**
     * Course Statistics
     */
    public function statistics(Course $course): View {
        $course->load(['enrollments.user', 'enrollments.payments']);
        $stats = [
            'total_enrollments' => $course->enrollments()->count(),
            'active_enrollments' => $course->enrollments()->where('status', 'active')->count(),
            'completed_enrollments' => $course->enrollments()->where('status', 'completed')->count(),
            'total_revenue' => $course->enrollments()
                ->join('payments', 'enrollments.id', '=', 'payments.enrollment_id')
                ->where('payments.status', 'completed')
                ->sum('payments.amount'),
            'completion_rate' => $this->calculateCourseCompletionRate($course),
            'average_progress' => $course->enrollments()->avg('progress') ?? 0,
        ];

        // Datos para gráficos
        $enrollmentTrends = $this->getEnrollmentTrends($course);
        $revenueByMonth = $this->getRevenueByMonth($course);

        return view('admin.courses.statistics', compact('course', 'stats', 'enrollmentTrends', 'revenueByMonth'));
    }

    /**
     * Bulk Actions
     */
    public function bulkActions(Request $request): JsonResponse {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $courseIds = $request->course_ids;
        $action = $request->action;

        switch ($action) {
            case 'activate':
                Course::whereIn('id', $courseIds)->update(['is_active' => true]);
                $message = 'Cursos activados exitosamente.';
                break;

            case 'deactivate':
                Course::whereIn('id', $courseIds)->update(['is_active' => false]);
                $message = 'Cursos desactivados exitosamente.';
                break;

            case 'delete':
                // Verificar que no tengan inscripciones activas
                $coursesWithEnrollments = Course::whereIn('id', $courseIds)
                    ->whereHas('enrollments', function($query) {
                        $query->where('status', 'active');
                    })->count();

                if ($coursesWithEnrollments > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Algunos cursos tienen estudiantes inscritos activamente y no pueden ser eliminados.'
                    ], 422);
                }

                Course::whereIn('id', $courseIds)->delete();
                $message = 'Cursos eliminados exitosamente.';
                break;
        }

        $this->logActivity("Ejecutó acción masiva: {$action} en " . count($courseIds) . " cursos");

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Helper Methods
     */
    private function reorderSections(Course $course) {
        $sections = $course->sections()->orderBy('order')->get();

        foreach ($sections as $index => $section) {
            $section->update(['order' => $index + 1]);
        }
    }

    private function reorderLessons(CourseSection $section) {
        $lessons = $section->lessons()->orderBy('order')->get();

        foreach ($lessons as $index => $lesson) {
            $lesson->update(['order' => $index + 1]);
        }
    }

    private function calculateCourseCompletionRate(Course $course) {
        $totalEnrollments = $course->enrollments()->count();
        $completedEnrollments = $course->enrollments()->where('status', 'completed')->count();

        return $totalEnrollments > 0 ? ($completedEnrollments / $totalEnrollments) * 100 : 0;
    }

    private function getEnrollmentTrends(Course $course) {
        return $course->enrollments()
            ->select(
                DB::raw('COUNT(*) as count'),
                DB::raw('MONTH(enrolled_at) as month'),
                DB::raw('YEAR(enrolled_at) as year')
            )
            ->where('enrolled_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }

    private function getRevenueByMonth(Course $course) {
        return $course->enrollments()
            ->join('payments', 'enrollments.id', '=', 'payments.enrollment_id')
            ->select(
                DB::raw('SUM(payments.amount) as revenue'),
                DB::raw('MONTH(payments.created_at) as month'),
                DB::raw('YEAR(payments.created_at) as year')
            )
            ->where('payments.status', 'completed')
            ->where('payments.created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }

    private function logActivity($action) {
        DB::table('activity_logs')->insert([
            'action'        => $action,
            'description'   => $action,
            'user_id'       => auth()->id(),
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
