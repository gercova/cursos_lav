<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryValidate;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CategoriesAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin', 'prevent.back']);
    }

    public function index(Request $request): View|JsonResponse {
        $query = Category::query()
            ->withCount('courses');

        // Búsqueda
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
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


        // Ordenar
        $categories = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function stats(): JsonResponse {
        $stats = [
            'total'     => Category::count(),
            'active'    => Category::where('is_active', true)->count(),
            'inactive'  => Category::where('is_active', false)->count(),
            'recent'    => Category::where('created_at', '>=', now()->subWeek())->count(),
        ];

        return response()->json($stats);
    }

    public function toggleStatus($categoryId): JsonResponse {
        try {
            $category = Category::findOrFail($categoryId);
            $category->is_active = !$category->is_active;
            $category->save();

            return response()->json([
                'success'       => true,
                'category'      => $category,
                'message'       => 'Estado actualizado correctamente',
                'new_status'    => $category->is_active
            ]);

        } catch (Throwable $e) {
            Log::error('Error toggling category status', [
                'category_id' => $categoryId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado'
            ], 500);
        }
    }

    public function show(Category $category): JsonResponse {
        $category->load('courses');

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    public function store(CategoryValidate $request) {
        DB::beginTransaction();

        try {
            $validated  = $request->validated();
            $categoryId = $request->input('id');
            $validated['slug'] = Str::slug($validated['name']);
            $validated['slug'] = $this->generateUniqueSlug($validated['slug'], $categoryId);
            $category = Category::updateOrCreate(['id' => $categoryId], $validated);
            DB::commit();

            $message = $categoryId ? 'Categoría actualizada exitosamente' : 'Categoría creada exitosamente';

            if ($request->wantsJson()) {
                return response()->json([
                    'success'   => true,
                    'category'  => $category->fresh(['courses']),
                    'message'   => $message
                ], $categoryId ? 200 : 201);
            }

            return redirect()->route('admin.categories.index')->with('success', $message);

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error storing category', [
                'data' => $request->all(),
                'error' => $e->getMessage()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success'   => false,
                    'message'   => 'Error al guardar la categoría',
                    'error'     => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Error al guardar la categoría');
        }
    }

    private function generateUniqueSlug(string $slug, $excludeId = null): string{
        $originalSlug = $slug;
        $count = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    private function slugExists(string $slug, $excludeId = null): bool {
        $query = Category::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function update(CategoryValidate $request, $categoryId) {
        $category = Category::findOrFail($categoryId);
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            // Generar slug único si el nombre cambió
            if ($category->name !== $validated['name']) {
                $validated['slug'] = $this->generateUniqueSlug(Str::slug($validated['name']), $category->id);
            }

            $category->update($validated);
            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success'   => true,
                    'category'  => $category->fresh(['courses']),
                    'message'   => 'Categoría actualizada exitosamente'
                ]);
            }

            return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada exitosamente');

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error updating category', [
                'category_id'   => $category->id,
                'error'         => $e->getMessage()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la categoría'
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Error al actualizar la categoría');
        }
    }

    public function destroy($categoryId) {
        $category = Category::findOrFail($categoryId);
        DB::beginTransaction();

        try {
            // Verificar si tiene cursos asociados
            if ($category->courses()->exists()) {
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar una categoría con cursos asociados'
                    ], 422);
                }

                return redirect()->back()->with('error', 'No se puede eliminar una categoría con cursos asociados');
            }

            $category->delete();
            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoría eliminada exitosamente'
                ]);
            }

            return redirect()->route('admin.categories.index')->with('success', 'Categoría eliminada exitosamente');

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error deleting category', [
                'category_id' => $category->id,
                'error' => $e->getMessage()
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la categoría'
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al eliminar la categoría');
        }
    }

    public function bulkAction(Request $request): JsonResponse {
        $request->validate([
            'action'    => 'required|in:activate,deactivate,delete',
            'ids'       => 'required|array|min:1',
            'ids.*'     => 'exists:categories,id'
        ]);

        DB::beginTransaction();

        try {
            $action = $request->input('action');
            $ids = $request->input('ids');

            switch ($action) {
                case 'activate':
                    Category::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = 'Categorías activadas exitosamente';
                    break;

                case 'deactivate':
                    Category::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = 'Categorías desactivadas exitosamente';
                    break;

                case 'delete':
                    Category::whereIn('id', $ids)
                        ->doesntHave('courses')
                        ->delete();
                    $message = 'Categorías eliminadas exitosamente';
                    break;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Error in bulk action', [
                'action' => $request->input('action'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la acción masiva'
            ], 500);
        }
    }
}
