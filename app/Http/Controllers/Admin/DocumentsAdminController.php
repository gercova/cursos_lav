<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentValidate;
use App\Models\Document;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DocumentsAdminController extends Controller {

    public function __construct() {
        $this->middleware(['admin', 'prevent.back']);
    }

    public function index(Request $request): View {
        $query = Document::with(['course.category'])
            ->latest();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('file_type', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    });
                });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('course')) {
            $query->where('course_id', $request->course);
        }

        if ($request->filled('type')) {
            $query->where('file_type', $request->type);
        }

        $documents  = $query->paginate(20);

        // Datos para filtros
        $courses    = Course::where('is_active', true)->get();
        $fileTypes  = Document::distinct()->pluck('file_type');

        return view('admin.documents.index', compact('documents', 'courses', 'fileTypes'));
    }

    /*public function create(): View {
        $courses = Course::where('is_active', true)
            ->with(['category'])
            ->withCount('enrollments as students_count')
            ->get();

        return view('admin.documents.create', compact('courses'));
    }*/

    public function store(DocumentValidate $request) {
        $validator = $request->validated();

        // Procesar archivo
        $file           = $request->file('file');
        $originalName   = $file->getClientOriginalName();
        $extension      = $file->getClientOriginalExtension();
        $filename       = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;

        // Guardar archivo
        $path           = $file->storeAs('documents', $filename, 'public');

        // Crear documento
        $document = Document::create([
            'course_id'     => $request->course_id,
            'title'         => $request->title,
            'description'   => $request->description,
            'file_path'     => $path,
            'file_type'     => strtolower($extension),
            'file_size'     => $file->getSize(),
            'is_active'     => $request->boolean('is_active', false), // Valor por defecto
        ]);

        // --- CORRECCIÓN AQUÍ ---
        // Devolver éxito en formato JSON si es una solicitud AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Documento subido exitosamente.',
                'data' => $document // Opcional: devolver los datos del documento creado
            ]);
        }
        // Si no es AJAX, usar el comportamiento original
        return redirect()->route('admin.documents.index')
            ->with('success', 'Documento subido exitosamente.');
        // --- FIN CORRECCIÓN ---
    }

    public function show(Document $document): View {
        $document->load(['course.category', 'course.enrollments']);
        return view('admin.documents.show', compact('document'));
    }

    public function edit(Document $document): View {
        $courses = Course::where('is_active', true)
            ->with(['category'])
            ->withCount('enrollments as students_count')
            ->get();

        return view('admin.documents.edit', compact('document', 'courses'));
    }

    public function update(DocumentValidate $request, Document $document) {
        $validator = $request->validated();

        $data = [
            'course_id'     => $request->course_id,
            'title'         => $request->title,
            'description'   => $request->description,
            'is_active'     => $request->boolean('is_active', false), // Valor por defecto
        ];

        // Si hay nuevo archivo
        if ($request->hasFile('file')) {
            // Eliminar archivo anterior
            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Subir nuevo archivo
            $file           = $request->file('file');
            $originalName   = $file->getClientOriginalName();
            $extension      = $file->getClientOriginalExtension();
            $filename       = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
            $path           = $file->storeAs('documents', $filename, 'public');

            $data['file_path'] = $path;
            $data['file_type'] = strtolower($extension);
            $data['file_size'] = $file->getSize();
        }

        $document->update($data);

        // --- CORRECCIÓN AQUÍ ---
        // Devolver éxito en formato JSON si es una solicitud AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Documento actualizado exitosamente.',
                'data' => $document // Opcional: devolver los datos del documento actualizado
            ]);
        }
        // Si no es AJAX, usar el comportamiento original
        return redirect()->route('admin.documents.index')->with('success', 'Documento actualizado exitosamente.');
        // --- FIN CORRECCIÓN ---
    }

    public function destroy(Document $document): JsonResponse {
        // Eliminar archivo físico
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Documento eliminado exitosamente.'
        ]);
    }

    public function duplicate(Document $document): JsonResponse {
        try {
            // Crear copia del documento
            $newDocument = $document->replicate();
            $newDocument->title = $document->title . ' (Copia)';
            $newDocument->save();

            return response()->json([
                'success' => true,
                'message' => 'Documento duplicado exitosamente.',
                'new_document_id' => $newDocument->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al duplicar el documento.'
            ], 500);
        }
    }

    public function toggleStatus(Document $document): JsonResponse {
        $document->update(['is_active' => !$document->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del documento actualizado.',
            'is_active' => $document->is_active
        ]);
    }
}
