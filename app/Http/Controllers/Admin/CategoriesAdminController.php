<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View {
        $query = Category::query();
        // Búsqueda
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if (request()->has('status') && request('status') !== '') {
            $query->where('is_active', request('status'));
        }

        $categories = $query->orderBy('name')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function toggleStatus(Category $category): JsonResponse {
        $category->update(['is_active' => !$category->is_active]);
        return response()->json([
            'success' => true,
            'is_active' => $category->is_active
        ]);
    }

    public function show(Category $category): JsonResponse {
        return response()->json($category);
    }

    public function create(): View {
        return view('admin.categories.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $category = Category::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    public function update(Request $request, Category $category) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $category->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'category' => $category->fresh()
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroy(Category $category) {
        $category->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada'
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }
}
