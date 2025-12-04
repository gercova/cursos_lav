<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Document;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentsAdminController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View {
        $query = Document::with(['course.category']);

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

        // Filtro por tipo de archivo
        if ($type = $request->input('type')) {
            $query->where('file_type', $type);
        }

        // Ordenar
        $query->orderBy('created_at', 'desc');

        $documents = $query->paginate(15);
        $courses = Course::where('is_active', true)
            ->with('category')
            ->orderBy('title')
            ->get();

        // Obtener tipos de archivo únicos
        $fileTypes = Document::select('file_type')
            ->distinct()
            ->pluck('file_type')
            ->filter()
            ->values();

        return view('admin.documents.index', compact('documents', 'courses', 'fileTypes'));
    }

    public function store(Request $request): JsonResponse {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'file' => 'required|file|max:51200', // 50MB
            'is_active' => 'boolean',
        ]);

        // Procesar archivo
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('documents', 'public');

            $validated['file_path'] = $path;
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_size'] = $file->getSize();
        }

        $document = Document::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Documento subido exitosamente',
            'document' => $document
        ]);
    }

    public function update(Request $request, Document $document): JsonResponse {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'file' => 'nullable|file|max:51200', // 50MB
            'is_active' => 'boolean',
        ]);

        // Procesar archivo si se subió uno nuevo
        if ($request->hasFile('file')) {
            // Eliminar archivo anterior
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('documents', 'public');

            $validated['file_path'] = $path;
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_size'] = $file->getSize();
        }

        $document->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Documento actualizado exitosamente',
            'document' => $document
        ]);
    }

    public function destroy(Document $document): JsonResponse {
        // Eliminar archivo físico
        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Documento eliminado exitosamente'
        ]);
    }

    public function toggleStatus(Document $document): JsonResponse {
        $document->update(['is_active' => !$document->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $document->is_active
        ]);
    }

    public function show(Document $document): JsonResponse {
        return response()->json($document);
    }
}
