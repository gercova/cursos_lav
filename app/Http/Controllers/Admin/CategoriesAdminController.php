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
        $categories = Category::orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function show(Category $category): JsonResponse {
        return response()->json($category);
    }

    public function create(): View {
        return view('admin.categories.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'          => 'required|string|max:255|unique:categories',
            'description'   => 'nullable|string',
        ]);

        Category::create([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'description'   => $request->description,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Category $category): View {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
        $request->validate([
            'name'          => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description'   => 'nullable|string',
            'is_active'     => 'boolean',
        ]);

        $category->update([
            'name'          => $request->name,
            'slug'          => Str::slug($request->name),
            'description'   => $request->description,
            'is_active'     => $request->has('is_active'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Category $category) {
        if ($category->courses()->exists()) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar la categoría porque tiene cursos asociados.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada exitosamente.');
    }
}
