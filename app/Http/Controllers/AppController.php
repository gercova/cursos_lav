<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Enterprise;
use App\Models\PlanType;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller {

    public function home(Request $request): View {
        $query = Course::with(['instructor', 'category'])
            ->where('category_id', '<>', 4)
            ->where('type', 'course')
            ->where('is_active', true);

        // Filtro por búsqueda de texto
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('category', function($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('instructor', function($q) use ($searchTerm) {
                        $q->where('names', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Filtrar por categoría
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Ordenamiento
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
        }

        $courses      = $query->paginate(12)->withQueryString();
        $totalCourses = Course::where('is_active', true)->get();
        $users        = User::where('is_active', true)->get();
        $categories   = Category::where('is_active', true)->where('id', '<>', 4)->get();
        $enterprise   = Enterprise::first();
        $currentSearch    = $request->get('search', '');
        $currentCategory  = $request->get('category', '');
        $currentSort      = $sort;
        return view('student.home', compact(
            'courses', 'totalCourses', 'users', 'categories', 'enterprise',
            'currentSearch', 'currentCategory', 'currentSort'
        ));
    }

    // public function courses(Request $request) {
    public function courses(Request $request, $code = null) {
        $query = Course::with(['category', 'instructor'])
            ->where('category_id', '<>', 4)
            ->where('type', 'course')
            ->where('is_active', true);

        // Filtro por búsqueda con sanitización y límites
        if ($request->filled('search')) {
            $searchTerm = substr(strip_tags(trim($request->search)), 0, 100);
            if (!empty($searchTerm)) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                        ->orWhere('description', 'like', '%' . $searchTerm . '%')
                        ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                        ->orWhereHas('category', function($q) use ($searchTerm) {
                            $q->where('name', 'like', '%' . $searchTerm . '%');
                        })
                        ->orWhereHas('instructor', function($q) use ($searchTerm) {
                            $q->where('names', 'like', '%' . $searchTerm . '%');
                        });
                });
            }
        }

        // Filtrar por categoría
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Ordenar
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'popular':
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
        }

        $courses    = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->where('id', '<>', 4)->get();
        $enterprise = Enterprise::first();
        // Validar al partner code
        $partner    = $code ? User::where('code', $code)->where('is_active', true)->first() : null;

        // Si es una petición AJAX, retornar solo la vista parcial
        if ($request->ajax()) {
            return view('student.partials.courses-grid', compact('courses', 'code'))->render();
        }

        return view('student.courses', compact('courses', 'categories', 'code', 'partner', 'enterprise'));
    }

    // public function packages(Request $request) {
    public function packages(Request $request, $code = null) {
        $query = Course::with(['categories'])->where('type', 'package')->where('is_active', true);

        // 1. Búsqueda por nombre o descripción
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('meta_description', 'like', '%' . $searchTerm . '%');
            });
        }

        // 2. Filtro por categorías (relación muchos a muchos)
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // 3. Filtro por rango de precios
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 4. Filtro por fecha de creación (últimos 30 días, último año, etc)
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereDate('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->whereDate('created_at', '>=', now()->subMonth());
                    break;
                case 'year':
                    $query->whereDate('created_at', '>=', now()->subYear());
                    break;
            }
        }

        // 5. Filtro por promociones (paquetes con precio promocional)
        if ($request->boolean('on_promotion')) {
            $query->whereNotNull('promotion_price')
                ->where('promotion_price', '<', 'price')
                ->where('promotion_price', '>', 0);
        }

        // 6. Ordenamiento
        $sort = $request->get('sort', 'newest');
        
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popular':
                // Asumiendo que tienes un contador de ventas o similar
                $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
        }

        // 7. Paginación (12 items por página como en cursos)
        $packages = $query->paginate(12)->withQueryString();

        // 8. Enriquecer los paquetes con información adicional
        $packages->transform(function ($package) {
            // Calcular precio final (promoción si existe)
            $package->final_price = $package->promotion_price && $package->promotion_price < $package->price 
                ? $package->promotion_price 
                : $package->price;
            
            $package->has_promotion = $package->promotion_price && $package->promotion_price < $package->price;
            
            if ($package->has_promotion) {
                $package->discount_percentage = round((($package->price - $package->promotion_price) / $package->price) * 100);
            }
            
            // Contar cursos totales disponibles en el paquete
            $package->total_courses = $package->getAllCoursesAttribute()->count();
            
            return $package;
        });

        $enterprise = Enterprise::first();
        $courses    = Course::where('is_active', true)->where('type', 'course')->get(); 
        // Validar al partner code
        $partner    = $code ? User::where('code', $code)->where('is_active', true)->first() : null;

        // 10. Si es petición AJAX, devolver solo la cuadrícula
        if ($request->ajax()) {
            return view('student.partials.packages-grid', compact('packages', 'courses', 'code'))->render();
        }

        return view('student.packages', compact('packages', 'enterprise', 'courses', 'code'));
    }

    // public function showPackage(string $slug): View {
    public function showPackage(string $slug, $code = null): View {
        $package = Course::with([
                'courses' => function($query) {
                    $query->withPivot('quantity', 'sort_order')->where('is_active', true);
                },
                'courses.category',
                'categories' => function($query) {
                    $query->withPivot('max_courses_per_category');
                }
            ])
            ->where('type', 'package')
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail();

        // Enriquecer con información adicional
        $package->final_price = $package->promotion_price && $package->promotion_price < $package->price 
            ? $package->promotion_price 
            : $package->price;
        
        $package->has_promotion = $package->promotion_price && $package->promotion_price < $package->price;
        
        if ($package->has_promotion) {
            $package->discount_percentage = round((($package->price - $package->promotion_price) / $package->price) * 100);
        }
        
        // Total de cursos
        $package->total_courses = $package->courses->count();

        $planType   = PlanType::get();
        $enterprise = Enterprise::first();
        // Validar al partner code
        $partner    = $code ? User::where('code', $code)->where('is_active', true)->first() : null;

        return view('student.package-detail', compact('package', 'planType', 'enterprise', 'code'));
    }

    // public function show(string $slug): View {
    public function showCourse(string $slug, $code = null): View {
        $course     = Course::with([
                'sections' => function($query) {
                    $query->where('is_active', true)->orderBy('order');
                },
                'sections.lessons' => function($query) {
                    $query->where('is_active', true)->orderBy('order');
                },
                'category',
                'instructor',
                'documents' => function($query) {
                    $query->where('is_active', true);
                }
            ])
            ->where('is_active', true)
            ->where('slug', $slug)
            ->firstOrFail(); // Usar firstOrFail para 404 automático

        $isEnrolled = Auth::check() ? Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->exists(): false;
        $partner    = $code ? User::where('code', $code)->where('is_active', true)->first() : null;

        return view('student.course-detail', compact('course', 'isEnrolled', 'code'));
    }

    public function aboutus(): View {
        $enterprise = Enterprise::first();
        return view('student.about', compact('enterprise'));
    }

    public function contact(): View {
        $enterprise = Enterprise::first();
        return view('student.contact', compact('enterprise'));
    }

    public function terms(): View {
        $enterprise = Enterprise::first();
        return view('student.terms', compact('enterprise'));
    }

    public function policies(): View {
        $enterprise = Enterprise::first();
        return view('student.policies', compact('enterprise'));
    }

    public function cookies(): View {
        $enterprise = Enterprise::first();
        return view('student.cookies', compact('enterprise'));
    }

    public function sendMessage(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'   => false,
                'errors'    => $validator->errors()
            ], 422);
        }

        try {
            $contactData = [
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'subject'       => $request->subject,
                'user_message'  => $request->message, // <-- ¡CAMBIO AQUÍ! Le cambiamos el nombre
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->userAgent(),
            ];

            // Envío de email real
            Mail::send('emails.contact.contact', $contactData, function($message) use ($contactData) {
                // Aquí pones a dónde quieres que lleguen los mensajes de tus clientes
                $message->to('informes@ipf-educa.com') 
                    ->subject('Nuevo mensaje de contacto: ' . $contactData['subject'])
                    ->replyTo($contactData['email'], $contactData['name']);
            });

            return response()->json([
                'success' => true,
                'message' => '¡Mensaje enviado con éxito! Te contactaremos pronto.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje: ' . $e->getMessage()
            ], 500);
        }
    }
}
