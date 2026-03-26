<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseValidate;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CoursesAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin', 'prevent.back']);
    }

    public function index(Request $request): View {
        $query = Course::with(['category', 'sections'])
            ->withCount(['enrollments as students_count'])
            ->withCount('sections')
            ->where('category_id', '<>', 4)
            ->where('type', 'course');

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
        $categories = Category::where('is_active', true)->where('id', '<>', 4)->orderBy('name')->get();
        $instructors = User::where('role', 'instructor')->orWhere('role', 'admin')->get();

        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    public function edit(Course $course): View {
        $categories = Category::where('is_active', true)->where('id', '<>', 4)->orderBy('name')->get();
        $instructors = User::where('role', 'instructor')->orWhere('role', 'admin')->get();

        // Cargar relaciones necesarias
        $course->loadCount(['sections', 'enrollments']);

        // Convertir arrays JSON a arrays PHP para los campos de formulario
        $course->what_you_learn = $course->what_you_learn ?? [];
        $course->requirements = $course->requirements ?? [];

        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    // public function students(Request $request, Course $course) {
    //     $query = $course->enrollments()->with('user');

    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->whereHas('user', function ($q) use ($search) {
    //             $q->where('names', 'like', "%{$search}%")
    //             ->orWhere('email', 'like', "%{$search}%")
    //             ->orWhere('dni', 'like', "%{$search}%");
    //         });
    //     }

    //     $enrollments = $query->latest('enrolled_at')->paginate(10);

    //     // ✅ Si es AJAX, retorna solo las filas (el partial)
    //     if ($request->ajax()) {
    //         return view('admin.courses.partials.students-table', compact('enrollments'));
    //     }

    //     // Carga normal de la página completa
    //     return view('admin.courses.students', compact('course', 'enrollments'));
    // }

    public function students(Request $request, Course $course) {
        $query = $course->enrollments()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('names', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->latest('enrolled_at')->paginate(10);

        // AJAX: retorna JSON con las 3 partes por separado
        if ($request->ajax()) {
            $tableHtml = view('admin.courses.partials.students-table', compact('enrollments'))->render();

            $paginationHtml = $enrollments->hasPages()
                ? $enrollments->appends(['search' => $request->search])->links()->render()
                : '';

            return response()->json([
                'table'      => $tableHtml,
                'pagination' => $paginationHtml,
                'total'      => $enrollments->total(),
            ]);
        }

        return view('admin.courses.students', compact('course', 'enrollments'));
    }

    public function store(CourseValidate $request) {
        try {
            $validated = $request->validated();
            
            // Procesar imagen
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('courses', 'public');
                $validated['image_url'] = $path;
            }

            // Filtrar elementos vacíos de los arrays
            if (isset($validated['requirements'])) {
                $validated['requirements'] = array_values(array_filter($validated['requirements']));
            }
            if (isset($validated['what_you_learn'])) {
                $validated['what_you_learn'] = array_values(array_filter($validated['what_you_learn']));
            }

            // Crear slug si no se proporcionó
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            DB::beginTransaction();
            
            $course = Course::create($validated);
            
            DB::commit();
            
            if($course && $course->is_active == true) {
                $students = User::where('role', 'student')->where('is_active', true)->get();
                foreach ($students as $student) {
                    NotificationService::sendNewCourseNotification($student, $course);
                }
            }

            return redirect()->route('admin.courses.edit', $course)->with('success', 'Curso creado exitosamente');
                
        } catch (ValidationException $e) {
            // Si es error de validación, mostrar los errores específicos
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error al crear curso: ' . $th->getMessage());
            Log::error($th->getTraceAsString());
            
            return back()->withInput()->with('error', 'Ocurrió un error al crear el curso: ' . $th->getMessage());
        }
    }

    public function update(CourseValidate $request, Course $course) {
        try {
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
        } catch (ValidationException $e) {
            // Si es error de validación, mostrar los errores específicos
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error al actualizar el curso: ' . $th->getMessage());
            Log::error($th->getTraceAsString());
            
            return back()->withInput()->with('error', 'Ocurrió un error al actualizar el curso: ' . $th->getMessage());
        }
    }

    public function updatePrices(Request $request, Course $course): JsonResponse {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'promotion_price' => [
                'nullable', 
                'numeric', 
                'min:0',
                // Regla personalizada: promotion_price debe ser menor que price
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > 0 && $value >= $request->price) {
                        $fail('El precio promocional debe ser menor al precio normal.');
                    }
                },
            ],
        ]);

        try {
            $course->update([
                'price' => $request->price,
                'promotion_price' => $request->promotion_price > 0 ? $request->promotion_price : null,
            ]);

            $this->logActivity("Actualizó precios del curso: {$course->title} a S/ {$request->price}");

            return response()->json([
                'success' => true,
                'message' => 'Precios actualizados correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Course $course) {
        // Verificar si hay inscripciones activas
        if ($course->enrollments()->where('status', 'active')->exists()) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el curso porque tiene estudiantes inscritos activamente.'
                ], 422);
            }
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

        // <-- SOLUCIÓN: Si es Axios, devolvemos JSON
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Curso eliminado exitosamente.'
            ]);
        }

        return redirect()->route('admin.courses.index')->with('success', 'Curso eliminado exitosamente.');
    }

    public function toggleStatus(Course $course): JsonResponse {
        $course->update([
            'is_active' => !$course->is_active
        ]);

        $status = $course->is_active ? 'activó' : 'desactivó';
        $this->logActivity("{$status} el curso: {$course->title}");

        return response()->json([
            'success'   => true,
            'is_active' => $course->is_active,
            'message'   => 'Estado del curso actualizado.'
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
