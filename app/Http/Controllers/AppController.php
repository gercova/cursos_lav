<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\CoursePromotionCode;
use App\Models\Enrollment;
use App\Models\Enterprise;
use App\Models\Package;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller {

    public function home(Request $request): View {
        $query = Course::with(['instructor'])
            ->where('category_id', '<>', 4)
            ->where('type', 'course')
            ->where('is_active', true);
        // Filtrar por categoría
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $courses    = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $enterprise = Enterprise::first();
        return view('student.home', compact('courses', 'categories', 'enterprise'));
    }

    public function courses(Request $request) {
        $query = Course::with(['category', 'instructor'])
            ->where('category_id', '<>', 4)
            ->where('type', 'course')
            ->where('is_active', true);
        // Filtro por búsqueda
        if ($request->has('search') && $request->search) {
            $searchTerm = $request->search;
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

        // Filtrar por categoría
        if ($request->has('category') && $request->category) {
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
                // Asumiendo que tienes un campo de rating o reviews
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

        $courses    = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $enterprise = Enterprise::first();

        // Si es una petición AJAX, retornar solo la vista parcial
        if ($request->ajax()) {
            return view('student.partials.courses-grid', compact('courses'))->render();
        }

        return view('student.courses', compact('courses', 'categories', 'enterprise'));
    }

    public function packages(Request $request) {
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

        // 9. Obtener categorías para el filtro (solo las que tienen paquetes activos)
        // $categories = Category::whereHas('packages', function($q) {
        //         $q->where('is_active', true);
        //     })
        //     ->where('is_active', true)
        //     ->get();

        $enterprise = Enterprise::first();

        // 10. Si es petición AJAX, devolver solo la cuadrícula
        if ($request->ajax()) {
            return view('student.partials.packages-grid', compact('packages'))->render();
        }

        // return view('student.packages', compact('packages', 'categories', 'enterprise'));
        return view('student.packages', compact('packages', 'enterprise'));
    }

    public function coursesPartner(Request $request, $code = null) {
        if($code == null) {
            return redirect()->route('cursos');
        }

        $query      = Course::query()->select('courses.*')->with(['category', 'instructor'])->where('courses.is_active', true);
        $partner    = User::where('code', $code)->where('is_active', true)->first();

        if ($partner) {
            // Obtener todos los códigos promocionales asociados a este partner
            $promoCodes = CoursePromotionCode::where('user_id', $partner->id)->where('is_active', true)->pluck('code')->toArray();

            if (!empty($promoCodes)) {
                // Filtrar cursos que tienen este código promocional del partner
                $query->whereHas('coursePromotionCode', function ($q) use ($promoCodes) {
                    $q->whereIn('code', $promoCodes);
                });
                // Cargamos la relación con el código promocional activo
                $query->with(['coursePromotionCode' => function($q) use ($promoCodes) {
                    $q->whereIn('code', $promoCodes)->where('is_active', true);
                }]);
            } else {
                // Si el partner existe pero no tiene códigos promocionales
                // Mostramos cursos normales (sin descuento)
                $partner = null;
            }
        } else {
            // Código inválido - mostrar cursos normales sin descuento
            // O si prefieres no mostrar nada, descomenta la siguiente línea:
            // $query->whereRaw('0 = 1');
            $partner = null;
        }

        // 3. Filtro por búsqueda
        $query->when($request->search, function ($q, $search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('short_description', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($cat) use ($search) {
                        $cat->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('instructor', function ($inst) use ($search) {
                        $inst->where('names', 'like', '%' . $search . '%');
                    });
            });
        });

        // 4. Filtrar por categoría
        $query->when($request->category, function ($q, $category_id) {
            $q->where('category_id', $category_id);
        });

        // 5. Ordenamiento
        $sort = $request->get('sort', 'newest');

        match ($sort) {
            'oldest'     => $query->orderBy('courses.created_at', 'asc'),
            'popular'    => $query->withCount('enrollments')->orderBy('enrollments_count', 'desc'),
            'price_low'  => $query->orderBy('courses.price', 'asc'),
            'price_high' => $query->orderBy('courses.price', 'desc'),
            'name_asc'   => $query->orderBy('courses.title', 'asc'),
            'name_desc'  => $query->orderBy('courses.title', 'desc'),
            default      => $query->orderBy('courses.created_at', 'desc'),
        };

        // 6. Ejecución
        $courses = $query->paginate(12)->withQueryString();

        // 7. Para cada curso, determinar el precio final según si hay código promocional
        $courses->transform(function ($course) use ($partner) {
            // Si hay un partner válido, buscar si este curso tiene descuento específico
            if ($partner && $course->coursePromotionCode->isNotEmpty()) {
                $promoCode = $course->coursePromotionCode->first();

                // Usar precio promocional si está disponible y es menor
                if ($promoCode->promotion_price < $course->price) {
                    $course->final_price            = $promoCode->promotion_price;
                    $course->original_price         = $course->price;
                    $course->has_promotion          = true;
                    $course->discount_percentage    = $promoCode->discount_percentage;
                    $course->promo_code             = $promoCode->code;
                } else {
                    // Mantener precio normal
                    $course->final_price    = $course->getFinalPriceAttribute();
                    $course->has_promotion  = $course->getIsOnPromotionAttribute();
                }
            } else {
                // Sin código o sin partner válido, usar precio normal
                $course->final_price        = $course->getFinalPriceAttribute();
                $course->has_promotion      = $course->getIsOnPromotionAttribute();
            }

            return $course;
        });

        // 8. Respuesta AJAX o normal
        if ($request->ajax()) {
            return view('student.partials.courses-grid', compact('courses', 'partner'))->render();
        }

        $categories = Category::where('is_active', true)->get();
        $enterprise = Enterprise::first();

        return view('student.courses-partner', compact('courses', 'categories', 'enterprise', 'partner'));
    }

    public function show(string $slug): View {
        $course = Course::with([
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

        $isEnrolled = Auth::check()
            ? Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->exists()
            : false;

        return view('student.course-detail', compact('course', 'isEnrolled'));
    }

    public function showPartner(string $slug, string $code): View {
        $partner    = User::where('code', $code)->where('is_active', true)->first();
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

        $isEnrolled = Auth::check()
            ? Enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->exists()
            : false;

        return view('student.course-detail-partner', compact('course', 'partner', 'isEnrolled'));
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
            // Aquí puedes:
            // 1. Guardar en la base de datos
            // 2. Enviar email
            // 3. Integrar con CRM

            $contactData = [
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'subject'   => $request->subject,
                'message'   => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            // Ejemplo de envío de email (descomentar cuando configures mail)

            Mail::send('emails.contact', $contactData, function($message) use ($contactData) {
                $message->to('info@eduplatform.com')
                    ->subject('Nuevo mensaje de contacto: ' . $contactData['subject'])
                    ->from($contactData['email'], $contactData['name']);
            });

            return response()->json([
                'success' => true,
                'message' => '¡Mensaje enviado con éxito! Te contactaremos pronto.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje. Por favor, intenta nuevamente.'
            ], 500);
        }
    }
}
