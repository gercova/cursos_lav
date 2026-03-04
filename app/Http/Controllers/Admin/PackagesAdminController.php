<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PackageValidate;
use App\Models\Category;
use App\Models\Course;
use App\Models\PackageCourse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PackagesAdminController extends Controller {
    
    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    public function index(): View {
        $packages = Course::with(['categories', 'courses'])
            ->where('category_id', 4)
            ->where('type', 'package')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.packages.index', compact('packages'));
    }

    public function create(): View {
        $categories = Category::where('id', '<>', 4)->where('is_active', true)->get();
        $courses    = Course::where('is_active', true)
            ->where('type', 'course') // Solo cursos, no paquetes
            ->with('category')
            ->get()
            ->map(function($course) {
                return [
                    'id'        => $course->id,
                    'title'     => $course->title,
                    'price'     => $course->price,
                    'category'  => $course->category ? [
                        'id'    => $course->category->id,
                        'name'  => $course->category->name
                    ] : null,
                    'students_count' => $course->students_count ?? 0
                ];
            });
        
        return view('admin.packages.create', compact('categories', 'courses'));
    }

    public function edit(Course $package): View {
        // Verificar que sea un paquete
        if ($package->type !== 'package' || $package->category_id != 4) {
            abort(404, 'El recurso solicitado no es un paquete válido');
        }

        $categories = Category::where('is_active', true)->get();
        
        // CORREGIDO: Obtener todos los cursos activos (no paquetes) para el selector
        $allCourses = Course::where('is_active', true)
            ->where('type', 'course')
            ->with('category')
            ->get()
            ->map(function($course) {
                return [
                    'id'        => $course->id,
                    'title'     => $course->title,
                    'price'     => $course->price,
                    'category'  => $course->category ? [
                        'id'    => $course->category->id,
                        'name'  => $course->category->name
                    ] : null,
                    'students_count' => $course->students_count ?? 0
                ];
            });
        
        // Cargar relaciones con los campos pivote correctos
        $package->load([
            'courses' => function($query) {
                $query->withPivot('quantity', 'sort_order');
            }, 
            'categories' => function($query) {
                $query->withPivot('max_courses_per_category');
            }
        ]);
        
        return view('admin.packages.edit', compact('package', 'categories', 'allCourses'));
    }

    public function store(PackageValidate $request): JsonResponse  {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Crear slug
            $slug = Str::slug($validated['name']);
            
            // Verificar slug único
            $count = 1;
            $originalSlug = $slug;
            while (Course::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            // which_includes ya debería ser array gracias a prepareForValidation
            $whichIncludes = array_values(array_filter($validated['which_includes'] ?? []));

            // Validar que haya al menos un elemento en which_includes después del filter
            if (empty($whichIncludes)) {
                throw ValidationException::withMessages([
                    'which_includes' => ['Debes proporcionar al menos un elemento en "¿Qué incluye este paquete?"']
                ]);
            }

            // Preparar datos del paquete
            $packageData = [
                'title'             => $validated['name'],
                'slug'              => $slug,
                'description'       => $validated['description'] ?? null,
                'short_description' => Str::limit($validated['description'] ?? '', 150),
                'meta_description'  => $validated['meta_description'] ?? null,
                'meta_keywords'     => $validated['meta_keywords'] ?? null,
                'which_includes'    => $whichIncludes, // Array limpio
                'price'             => $validated['price'],
                'promotion_price'   => $validated['promotion_price'] ?? null,
                'seats_min'         => $validated['seats_min'],
                'seats_max'         => $validated['seats_max'],
                'category_id'       => 4, // Categoría fija para paquetes
                'type'              => 'package',
                'instructor_id'     => auth()->id() ?? 1,
                'is_active'         => $validated['is_active'] ?? true,
            ];

            // Manejar la subida de imagen
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('packages', 'public');
                $packageData['image_url'] = $path;
            }

            // Crear paquete
            $package = Course::create($packageData);

            // Guardar cursos específicos
            if (!empty($validated['courses'])) {
                $this->saveCourses($package->id, $validated['courses']);
            }

            // Sincronizar categorías con sus límites
            if (!empty($validated['categories'])) {
                $categoriesSync = [];
                foreach ($validated['categories'] as $category) {
                    if (!empty($category['id'])) {
                        $categoriesSync[$category['id']] = [
                            'max_courses_per_category' => $category['max_courses_per_category'] ?? null
                        ];
                    }
                }
                if (!empty($categoriesSync)) {
                    $package->categories()->sync($categoriesSync);
                }
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Paquete creado exitosamente',
                'redirect'  => route('admin.packages.index')
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el paquete: ' . $e->getMessage()
            ], 500);
        }
    }

    // public function store(PackageValidate $request): JsonResponse  {
    //     try {
    //         DB::beginTransaction();

    //         $validated = $request->validated();

    //         // Crear slug
    //         $slug = Str::slug($validated['name']);
            
    //         // Verificar slug único
    //         $count = 1;
    //         $originalSlug = $slug;
    //         while (Course::where('slug', $slug)->exists()) {
    //             $slug = $originalSlug . '-' . $count++;
    //         }

    //         // Parsear which_includes si viene como JSON string
    //         $whichIncludes = [];
    //         if (isset($validated['which_includes'])) {
    //             $whichIncludes = is_string($validated['which_includes']) 
    //                 ? json_decode($validated['which_includes'], true) 
    //                 : $validated['which_includes'];
    //         }

    //         // Preparar datos del paquete
    //         $packageData = [
    //             'title'             => $validated['name'],
    //             'slug'              => $slug,
    //             'description'       => $validated['description'] ?? null,
    //             'short_description' => Str::limit($validated['description'] ?? '', 150),
    //             'meta_description'  => $validated['meta_description'] ?? null,
    //             'meta_keywords'     => $validated['meta_keywords'] ?? null,
    //             'which_includes'    => array_values(array_filter($whichIncludes)),
    //             'price'             => $validated['price'],
    //             'promotion_price'   => !empty($validated['promotion_price']) ? $validated['promotion_price'] : null,
    //             'seats_min'         => $validated['seats_min'],
    //             'seats_max'         => $validated['seats_max'],
    //             'category_id'       => 4, // ¿Por qué está fijo?
    //             'type'              => 'package',
    //             'instructor_id'     => auth()->id() ?? 1,
    //             'is_active'         => $validated['is_active'] ?? true,
    //         ];

    //         // Manejar la subida de imagen
    //         if ($request->hasFile('image')) {
    //             $path = $request->file('image')->store('packages', 'public');
    //             $packageData['image_url'] = $path;
    //         }

    //         // Crear paquete
    //         $package = Course::create($packageData);

    //         // Guardar cursos específicos
    //         if (!empty($validated['courses'])) {
    //             $courses = is_string($validated['courses']) 
    //                 ? json_decode($validated['courses'], true) 
    //                 : $validated['courses'];
    //             $this->saveCourses($package->id, $courses);
    //         }

    //         // Sincronizar categorías con sus límites
    //         if (!empty($validated['categories'])) {
    //             $categories = is_string($validated['categories']) 
    //                 ? json_decode($validated['categories'], true) 
    //                 : $validated['categories'];
                    
    //             $categoriesSync = [];
    //             foreach ($categories as $category) {
    //                 if (!empty($category['id'])) {
    //                     $categoriesSync[$category['id']] = [
    //                         'max_courses_per_category' => $category['max_courses_per_category'] ?? null
    //                     ];
    //                 }
    //             }
    //             if (!empty($categoriesSync)) {
    //                 $package->categories()->sync($categoriesSync);
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success'   => true,
    //             'message'   => 'Paquete creado exitosamente',
    //             'redirect'  => route('admin.packages.index')
    //         ]);

    //     } catch (ValidationException $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $e->errors()
    //         ], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error al crear el paquete: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    // private function saveCourses($packageId, $courses)
    // {
    //     // Eliminar relaciones existentes
    //     DB::table('course_course')->where('package_id', $packageId)->delete();
        
    //     // Insertar nuevas relaciones
    //     foreach ($courses as $course) {
    //         DB::table('course_course')->insert([
    //             'package_id' => $packageId,
    //             'course_id' => $course['id'],
    //             'quantity' => $course['quantity'] ?? 1,
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);
    //     }
    // }

    public function update(PackageValidate $request, Course $package): JsonResponse {
        // Verificar que sea un paquete
        if ($package->type !== 'package' || $package->category_id != 4) {
            return response()->json([
                'success' => false,
                'message' => 'El recurso solicitado no es un paquete válido'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            $validated = $request->validated();

            // Actualizar slug si cambió el nombre
            $newSlug = Str::slug($validated['name']);
            if ($newSlug !== $package->slug) {
                $count = 1;
                $originalSlug = $newSlug;
                while (Course::where('slug', $newSlug)->where('id', '!=', $package->id)->exists()) {
                    $newSlug = $originalSlug . '-' . $count++;
                }
                $package->slug = $newSlug;
            }

            // Preparar datos de actualización
            $updateData = [
                'title'             => $validated['name'],
                'slug'              => $package->slug,
                'description'       => $validated['description'] ?? null,
                'meta_description'  => $validated['meta_description'] ?? null,
                'meta_keywords'     => $validated['meta_keywords'] ?? null,
                'price'             => $validated['price'],
                'promotion_price'   => !empty($validated['promotion_price']) ? $validated['promotion_price'] : null,
                'seats'             => $validated['seats'],
                'is_active'         => $validated['is_active'] ?? true,
            ];

            // Manejar la subida de imagen
            if ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($package->image_url && !Str::startsWith($package->image_url, 'http')) {
                    Storage::disk('public')->delete($package->image_url);
                }
                
                $path = $request->file('image')->store('packages', 'public');
                $updateData['image_url'] = $path;
            }

            // Actualizar paquete
            $package->update($updateData);

            // Sincronizar cursos - CORREGIDO: usar 'quantity'
            if (isset($validated['courses'])) {
                $coursesSync = [];
                foreach ($validated['courses'] as $index => $course) {
                    if (!empty($course['id'])) {
                        $coursesSync[$course['id']] = [
                            'quantity' => $course['quantity'] ?? 1, // Cambiado a 'quantity'
                            'sort_order' => $index
                        ];
                    }
                }
                $package->courses()->sync($coursesSync);
            }

            // Sincronizar categorías
            if (isset($validated['categories'])) {
                $categoriesSync = [];
                foreach ($validated['categories'] as $category) {
                    if (!empty($category['id'])) {
                        $categoriesSync[$category['id']] = [
                            'max_courses_per_category' => $category['max_courses_per_category'] ?? null
                        ];
                    }
                }
                $package->categories()->sync($categoriesSync);
            }

            DB::commit();

            return response()->json([
                'success'   => true,
                'message'   => 'Paquete actualizado exitosamente',
                'redirect'  => route('admin.packages.index')
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success'   => false,
                'errors'    => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el paquete: ' . $e->getMessage()
            ], 500);
        }
    }

    private function saveCourses($packageId, $courses): void {
        $data = [];
        
        // Verificar que $courses sea un array
        if (!is_array($courses)) {
            Log::error('saveCourses: $courses no es un array', ['courses' => $courses]);
            return;
        }
        
        foreach ($courses as $index => $course) {
            // Verificar que cada curso tenga la estructura esperada
            if (is_array($course) && isset($course['id'])) {
                $data[] = [
                    'package_id'    => $packageId,
                    'course_id'     => $course['id'],
                    'quantity'      => $course['quantity'] ?? 1, // Usar quantity del request o 1 por defecto
                    'sort_order'    => $index, // Usar el índice como orden
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            } elseif (is_numeric($course)) {
                // Por si acaso solo envían el ID (para compatibilidad)
                $data[] = [
                    'package_id'    => $packageId,
                    'course_id'     => $course,
                    'quantity'      => 1,
                    'sort_order'    => $index,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
        }
        
        if (!empty($data)) {
            PackageCourse::insert($data);
        }
    }

    public function destroy(Course $package): JsonResponse {
        // Verificar que sea un paquete
        if ($package->type !== 'package' || $package->category_id != 4) {
            return response()->json([
                'success' => false,
                'message' => 'El recurso solicitado no es un paquete válido'
            ], 404);
        }

        try {
            // Verificar si tiene relaciones
            if ($package->courses()->count() > 0 || $package->categories()->count() > 0) {
                // Eliminar relaciones primero
                $package->courses()->detach();
                $package->categories()->detach();
            }

            // Eliminar imagen si existe
            if ($package->image_url && !Str::startsWith($package->image_url, 'http')) {
                Storage::disk('public')->delete($package->image_url);
            }

            $package->delete();

            return response()->json([
                'success' => true,
                'message' => 'Paquete eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el paquete: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(Course $package): JsonResponse {
        // Verificar que sea un paquete
        if ($package->type !== 'package' || $package->category_id != 4) {
            return response()->json([
                'success' => false,
                'message' => 'El recurso solicitado no es un paquete válido'
            ], 404);
        }

        try {
            $package->update([
                'is_active' => !$package->is_active
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Estado actualizado exitosamente',
                'is_active' => $package->is_active
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado: ' . $e->getMessage()
            ], 500);
        }
    }
}
