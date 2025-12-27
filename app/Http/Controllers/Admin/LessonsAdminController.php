<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonValidate;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\Lesson;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LessonsAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin', 'prevent.back']);
    }

    // Listar lecciones de una sección
    public function index(Course $course, CourseSection $section): View {
        $lessons = $section->lessons()->orderBy('order')->get();
        return view('admin.courses.lessons.index', compact('course', 'section', 'lessons'));
    }

    // Mostrar formulario para crear lección
    public function create(Course $course, CourseSection $section): View {
        return view('admin.courses.lessons.create', compact('course', 'section'));
    }

    // Guardar nueva lección
    public function store(LessonValidate $request, Course $course, CourseSection $section) {
        DB::beginTransaction();
        try {
            $data                       = $request->validated();
            $data['course_id']          = $course->id;
            $data['course_section_id']  = $section->id;
            $data['is_free']            = $request->boolean('is_free');
            $data['is_active']          = $request->boolean('is_active');

            $lesson = Lesson::create($data);

            DB::commit();
            return redirect()
                ->route('admin.courses.sections.lessons.index', [$course, $section])
                ->with('success', 'Lección creada exitosamente.');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error al crear lección: ' . $th->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error...');
        }
    }

    // Mostrar formulario para editar lección
    public function edit(Course $course, CourseSection $section, Lesson $lesson) {
        return view('admin.courses.lessons.edit', compact('course', 'section', 'lesson'));
    }

    // Actualizar lección
    public function update(LessonValidate $request, Course $course, CourseSection $section, Lesson $lesson) {
        $validated              = $request->validated();
        $validated['is_free']   = $request->has('is_free');
        $validated['is_active'] = $request->has('is_active');

        $lesson->update($validated);

        return redirect()
            ->route('admin.courses.sections.lessons.index', [$course, $section])
            ->with('success', 'Lección actualizada exitosamente.');
    }

    // Eliminar lección
    public function destroy(Course $course, CourseSection $section, Lesson $lesson) {
        $lesson->delete();
        return redirect()
            ->route('admin.courses.sections.lessons.index', [$course, $section])
            ->with('success', 'Lección eliminada exitosamente.');
    }

    // Cambiar estado de la lección
    public function toggleStatus(Course $course, CourseSection $section, Lesson $lesson) {
        $lesson->update(['is_active' => !$lesson->is_active]);
        return response()->json([
            'success'   => true,
            'is_active' => $lesson->is_active
        ]);
    }

    // Reordenar lecciones (para arrastrar y soltar)
    public function reorder(Request $request, Course $course, CourseSection $section) {
        $request->validate([
            'lessons'           => 'required|array',
            'lessons.*.id'      => 'required|exists:lessons,id',
            'lessons.*.order'   => 'required|integer|min:1'
        ]);

        foreach ($request->lessons as $item) {
            Lesson::where('id', $item['id'])->where('course_section_id', $section->id)->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    // Vista previa de lección
    public function preview(Course $course, CourseSection $section, Lesson $lesson) {
        return view('admin.courses.lessons.preview', compact('course', 'section', 'lesson'));
    }
}
