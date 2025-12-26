<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseValidate;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;

class CoursesAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View {
        $query = Course::with(['category', 'sections'])
            ->withCount(['enrollments as students_count'])
            ->withCount('sections');

        // Búsqueda
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'promotion') {
                $query->whereNotNull('promotion_price')->whereColumn('promotion_price', '<', 'price');
            }
        }

        // Filtro por categoría
        if ($category = $request->input('category')) {
            $query->where('category_id', $category);
        }

        // Ordenar
        $query->orderBy('created_at', 'desc');
        $courses    = $query->paginate(10);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.courses.index', compact('courses', 'categories'));
    }

    // Método para obtener secciones (API)
    public function getSections(Course $course): JsonResponse {
        $sections = $course->sections()->orderBy('order')->get();
        return response()->json($sections);
    }

    public function create(): View {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $instructors = User::where('role', 'instructor')->orWhere('role', 'admin')->get();

        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    public function edit(Course $course): View {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $instructors = User::where('role', 'instructor')->orWhere('role', 'admin')->get();

        // Cargar relaciones necesarias
        $course->loadCount(['sections', 'enrollments']);

        // Convertir arrays JSON a arrays PHP para los campos de formulario
        $course->what_you_learn = $course->what_you_learn ?? [];
        $course->requirements = $course->requirements ?? [];

        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    public function store(CourseValidate $request) {
        $validated = $request->validated();

        // Procesar imagen
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $validated['image_url'] = $path;
        }

        // Filtrar elementos vacíos de los arrays
        if (isset($validated['requirements'])) {
            $validated['requirements'] = array_filter($validated['requirements']);
        }
        if (isset($validated['what_you_learn'])) {
            $validated['what_you_learn'] = array_filter($validated['what_you_learn']);
        }

        // Crear slug si no se proporcionó
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        DB::beginTransaction();

        try {
            $course = Course::create($validated);
            DB::commit();
            return redirect()->route('admin.courses.edit', $course)->with('success', 'Curso creado exitosamente');
        } catch (\Throwable $th) {
            // Log del error (opcional pero recomendado)
            Log::error('Error al crear curso: ' . $th->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al crear el curso. Por favor, intenta nuevamente.');
        }
    }

    public function update(CourseValidate $request, Course $course) {
        $validated = $request->validated();

        // Procesar imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen anterior si existe
            if ($course->image_url) {
                Storage::disk('public')->delete($course->image_url);
            }

            $path = $request->file('image')->store('courses', 'public');
            $validated['image_url'] = $path;
        }

        // Filtrar elementos vacíos de los arrays
        if (isset($validated['requirements'])) {
            $validated['requirements'] = array_filter($validated['requirements']);
        }
        if (isset($validated['what_you_learn'])) {
            $validated['what_you_learn'] = array_filter($validated['what_you_learn']);
        }

        $course = Course::updateOrCreate(['id' => $request->input('id')], $validated);

        return redirect()->route('admin.courses.edit', $course->id)->with('success', 'Curso actualizado exitosamente');
    }

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

    public function addSection(Request $request, Course $course): JsonResponse {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
        ]);

        // Obtener el último orden
        $lastOrder = $course->sections()->max('order') ?? 0;

        $section = CourseSection::create([
            'course_id'     => $course->id,
            'title'         => $validated['title'],
            'description'   => $validated['description'],
            'order'         => $lastOrder + 1,
            'is_active'     => true,
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
            'success'   => true,
            'is_active' => $lesson->is_active,
            'message'   => 'Estado de la lección actualizado.'
        ]);
    }

    /**
     * Course Documents Management
     */
    public function uploadDocument(Request $request, Course $course) {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'document'      => 'required|file|max:10240', // 10MB max
        ]);

        $file       = $request->file('document');
        $filePath   = $file->store('documents', 'public');

        $document = Document::create([
            'course_id'     => $course->id,
            'title'         => $validated['title'],
            'description'   => $validated['description'],
            'file_path'     => $filePath,
            'file_type'     => $file->getClientMimeType(),
            'file_size'     => $file->getSize(),
            'is_active'     => true,
        ]);

        $this->logActivity("Subió documento '{$document->title}' al curso: {$course->title}");

        return response()->json([
            'success'   => true,
            'document'  => $document,
            'message'   => 'Documento subido exitosamente.'
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
            'total_enrollments'     => $course->enrollments()->count(),
            'active_enrollments'    => $course->enrollments()->where('status', 'active')->count(),
            'completed_enrollments' => $course->enrollments()->where('status', 'completed')->count(),
            'total_revenue'         => $course->enrollments()
                ->join('payments', 'enrollments.id', '=', 'payments.enrollment_id')
                ->where('payments.status', 'completed')
                ->sum('payments.amount'),
            'completion_rate'       => $this->calculateCourseCompletionRate($course),
            'average_progress'      => $course->enrollments()->avg('progress') ?? 0,
        ];

        // Datos para gráficos
        $enrollmentTrends   = $this->getEnrollmentTrends($course);
        $revenueByMonth     = $this->getRevenueByMonth($course);

        return view('admin.courses.statistics', compact('course', 'stats', 'enrollmentTrends', 'revenueByMonth'));
    }

    /**
     * Bulk Actions
     */
    public function bulkActions(Request $request): JsonResponse {
        $request->validate([
            'action'        => 'required|in:activate,deactivate,delete',
            'course_ids'    => 'required|array',
            'course_ids.*'  => 'exists:courses,id',
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

        foreach ($sections as $index    => $section) {
            $section->update(['order'   => $index + 1]);
        }
    }

    private function reorderLessons(CourseSection $section) {
        $lessons = $section->lessons()->orderBy('order')->get();

        foreach ($lessons as $index => $lesson) {
            $lesson->update(['order' => $index + 1]);
        }
    }

    private function calculateCourseCompletionRate(Course $course) {
        $totalEnrollments       = $course->enrollments()->count();
        $completedEnrollments   = $course->enrollments()->where('status', 'completed')->count();

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
