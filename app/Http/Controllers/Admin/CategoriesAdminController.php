<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryValidate;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class CategoriesAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin', 'prevent.back']);
    }

    /*public function index(Request $request): View {
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
    }*/

    /*public function index(Request $request) {
        $query = Category::query();
        // Búsqueda
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('is_active', $request->input('status'));
        }

        $categories = $query->orderBy('name')->paginate(10);
        // Si es una solicitud AJAX, devolver solo el HTML del panel
        if ($request->ajax() || $request->input('ajax')) {
            return view('admin.categories.partials.table', compact('categories'))->render();
        }

        return view('admin.categories.index', compact('categories'));
    }*/

    public function index(Request $request): View {
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

        $categories = $query->orderBy('name')->paginate(5);

        return view('admin.categories.index', compact('categories'));
    }

    public function search(Request $request) {
        $query = Category::query();

        // Búsqueda
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('is_active', $request->input('status'));
        }

        $categories = $query->orderBy('name')->paginate(5);

        // Renderizar solo la tabla
        $html = view('admin.categories.partials.table', compact('categories'))->render();

        return response()->json([
            'html' => $html,
            'pagination' => [
                'current_page'  => $categories->currentPage(),
                'last_page'     => $categories->lastPage(),
                'total'         => $categories->total(),
                'from'          => $categories->firstItem(),
                'to'            => $categories->lastItem()
            ]
        ]);
    }

    public function toggleStatus($categoryId): JsonResponse {
        $category = Category::findOrFail($categoryId);
        $category->update(['is_active' => !$category->is_active]);
        return response()->json([
            'success'   => true,
            'category'  => $category,
            'message'   => 'Estado actualizado correctamente'
        ]);
    }

    public function show(Category $category): JsonResponse {
        return response()->json($category);
    }

    public function store(CategoryValidate $request) {
        $validated      = $request->validated();
        $proccessData   = [
            'slug'      => Str::slug($validated['name']),
        ];

        $data = array_merge($validated, $proccessData);
        DB::beginTransaction();
        try {
            $category = Category::updateOrCreate(
                ['id' => $request->input('id')],
                $data
            );

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success'  => true,
                    'category' => $category
                ], 201); // Código 201 para creación exitosa
            }

            return redirect()->route('admin.categories.index')
                ->with('success', $request->input('id')
                    ? 'Categoría actualizada exitosamente'
                    : 'Categoría creada exitosamente');

        } catch (Throwable $th) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar la categoría',
                    'error'   => config('app.debug') ? $th->getMessage() : null
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al guardar la categoría: ' . $th->getMessage());
        }
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
