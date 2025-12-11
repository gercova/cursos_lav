<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseSectionAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Course $course) {
        $sections = $course->sections()
            ->withCount('lessons')
            ->orderBy('order')
            ->get()
            ->map(function($section) {
                return [
                    'id'            => $section->id,
                    'course_id'     => $section->course_id,
                    'title'         => $section->title,
                    'description'   => $section->description,
                    'order'         => $section->order,
                    'is_active'     => $section->is_active,
                    'media_url'     => $section->media_url,
                    'media_type'    => $section->media_type,
                    'lessons_count' => $section->lessons_count ?? 0,
                    'created_at'    => $section->created_at->toDateTimeString(),
                ];
            });

        $totalLessons = $sections->sum('lessons_count');

        return view('admin.coursesections.index', [
            'course'        => $course,
            'sections'      => $sections,
            'totalLessons'  => $totalLessons
        ]);
    }


    /**
     * Mostrar el formulario para crear una nueva sección.
     */
    public function create(Course $course): View {
        // Determinar el siguiente orden disponible
        $nextOrder = $course->sections()->max('order') + 1;
        return view('admin.coursesections.create', compact('course', 'nextOrder'));
    }

    /**
     * Almacenar una nueva sección.
     */
    public function store(Request $request, Course $course) {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'mediafile'     => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm,mkv,jpg,jpeg,png,gif,webp,pdf,doc,docx,txt,ppt,pptx|max:51200', // 50MB
            'order'         => 'required|integer|min:1',
            'is_active'     => 'boolean',
        ]);

        // Manejar la subida del archivo
        if ($request->hasFile('mediafile')) {
            $validated['mediafile'] = $request->file('mediafile')->store('course-sections', 'public');
        }

        $validated['course_id'] = $course->id;
        $validated['is_active'] = $request->has('is_active');

        // Reordenar secciones si es necesario
        $this->reorderSections($course, $validated['order']);

        CourseSection::create($validated);

        return redirect()->route('admin.courses.edit', $course)
            ->with('success', 'Sección creada exitosamente.');
    }

    /**
     * Mostrar el formulario para editar una sección.
     */
    public function edit(Course $course, CourseSection $section): View {
        return view('admin.coursesections.edit', compact('course', 'section'));
    }

    /**
     * Actualizar una sección existente.
     */
    public function update(Request $request, Course $course, CourseSection $section) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'mediafile' => 'nullable|file|mimes:mp4,avi,mov,wmv,flv,webm,mkv,jpg,jpeg,png,gif,webp,pdf,doc,docx,txt,ppt,pptx|max:51200',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Manejar eliminación del archivo actual
        if ($request->has('remove_media') && $section->mediafile) {
            Storage::disk('public')->delete($section->mediafile);
            $validated['mediafile'] = null;
        }

        // Manejar nueva subida de archivo
        if ($request->hasFile('mediafile')) {
            // Eliminar archivo anterior si existe
            if ($section->mediafile) {
                Storage::disk('public')->delete($section->mediafile);
            }

            $validated['mediafile'] = $request->file('mediafile')->store('course-sections', 'public');
        } else {
            // Mantener el archivo actual si no se sube uno nuevo
            unset($validated['mediafile']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Reordenar secciones si el orden cambió
        if ($section->order != $validated['order']) {
            $this->reorderSections($course, $validated['order'], $section->id);
        }

        $section->update($validated);

        return redirect()->route('admin.courses.edit', $course)
            ->with('success', 'Sección actualizada exitosamente.');
    }

    /**
     * Eliminar una sección.
     */
    public function destroy(Course $course, CourseSection $section) {
        // Eliminar archivo asociado si existe
        if ($section->mediafile) {
            Storage::disk('public')->delete($section->mediafile);
        }

        $section->delete();

        // Reordenar las secciones restantes
        $this->reorderAfterDelete($course);

        return redirect()->route('admin.courses.edit', $course)
            ->with('success', 'Sección eliminada exitosamente.');
    }

    /**
     * Reordenar secciones al insertar una nueva o cambiar el orden.
     */
    private function reorderSections(Course $course, $newOrder, $excludeId = null) {
        $sections = $course->sections()
            ->where('id', '!=', $excludeId)
            ->orderBy('order')
            ->get();

        $currentOrder = 1;

        foreach ($sections as $section) {
            if ($currentOrder == $newOrder) {
                $currentOrder++; // Hacer espacio para la nueva sección
            }

            $section->update(['order' => $currentOrder]);
            $currentOrder++;
        }
    }

    /**
     * Reordenar secciones después de eliminar una.
     */
    private function reorderAfterDelete(Course $course) {
        $sections = $course->sections()->orderBy('order')->get();

        $order = 1;
        foreach ($sections as $section) {
            $section->update(['order' => $order]);
            $order++;
        }
    }

    public function toggleStatus(Course $course, CourseSection $section): JsonResponse {
        $section->update(['is_active' => !$section->is_active]);
        return response()->json([
            'success'   => true,
            'section'   => $section,
            'message'   => 'Estado actualizado correctamente'
        ]);
    }
}
