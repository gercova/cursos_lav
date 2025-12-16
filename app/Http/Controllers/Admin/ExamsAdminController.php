<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamsAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View {
        $query = Exam::with(['course.category'])
            ->withCount(['questions', 'examAttempts as attempts_count'])
            ->withCount(['examAttempts as passed_count' => function($query) {
                $query->where('passed', true);
            }]);

        // Búsqueda
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filtro por curso
        if ($course = $request->input('course')) {
            $query->where('course_id', $course);
        }

        // Ordenar
        $query->orderBy('created_at', 'desc');

        $exams = $query->paginate(10);
        $courses = Course::where('is_active', true)
            ->with('category')
            ->orderBy('title')
            ->get();

        return view('admin.exams.index', compact('exams', 'courses'));
    }

    public function create(): View {
        $courses = Course::where('is_active', true)
            ->with('category')
            ->orderBy('title')
            ->get();

        return view('admin.exams.create', compact('courses'));
    }

    public function edit(Exam $exam): View {
        $courses = Course::where('is_active', true)
            ->with('category')
            ->orderBy('title')
            ->get();

        // Cargar estadísticas
        $exam->loadCount(['questions', 'examAttempts']);
        $exam->passed_count = $exam->examAttempts()->where('passed', true)->count();
        $exam->attempts_count = $exam->examAttempts()->count();

        return view('admin.exams.edit', compact('exam', 'courses'));
    }

    public function duplicate(Exam $exam): JsonResponse {
        try {
            // Duplicar examen
            $newExam = $exam->replicate();
            $newExam->title = $exam->title . ' (Copia)';
            $newExam->save();

            // Duplicar preguntas
            foreach ($exam->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->exam_id = $newExam->id;
                $newQuestion->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Examen duplicado exitosamente',
                'new_exam_id' => $newExam->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al duplicar el examen'
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'course_id'     => 'required|exists:courses,id',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit'    => 'nullable|integer|min:0',
            'max_attempts'  => 'nullable|integer|min:0',
            'is_final'      => 'boolean',
            'show_results'  => 'boolean',
            'randomize_questions' => 'boolean',
            'is_active'     => 'boolean',
        ]);

        $exam = Exam::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Examen creado exitosamente',
            'exam' => $exam
        ]);
    }

    public function update(Request $request, Exam $exam): JsonResponse {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'course_id'     => 'required|exists:courses,id',
            'passing_score' => 'required|integer|min:0|max:100',
            'time_limit'    => 'nullable|integer|min:0',
            'max_attempts'  => 'nullable|integer|min:0',
            'is_final'      => 'boolean',
            'show_results'  => 'boolean',
            'randomize_questions' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $exam->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Examen actualizado exitosamente',
            'exam' => $exam
        ]);
    }

    public function destroy(Exam $exam): JsonResponse {
        $exam->delete();

        return response()->json([
            'success' => true,
            'message' => 'Examen eliminado exitosamente'
        ]);
    }

    public function toggleStatus(Exam $exam): JsonResponse {
        $exam->update(['is_active' => !$exam->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $exam->is_active
        ]);
    }

    public function show(Exam $exam): JsonResponse {
        return response()->json($exam);
    }

    public function questions(Exam $exam): View {
        $questions = $exam->questions()
            ->orderBy('order')
            ->paginate(20);

        return view('admin.exams.questions', compact('exam', 'questions'));
    }
}
