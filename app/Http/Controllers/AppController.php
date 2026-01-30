<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\CoursePromotionCode;
use App\Models\Enrollment;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller {

    public function home(Request $request): View {
        $query = Course::with(['instructor'])->where('is_active', true);
        // Filtrar por categoría
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        $courses    = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $enterprise = Enterprise::first();
        return view('student.home', compact('courses', 'categories', 'enterprise'));
    }

    // public function courses(Request $request, $code = null) {

    //     $exists = User::where('code', $code)->where('is_active', true)->first();
    //     if($code == null){
    //         $query = Course::with(['category', 'instructor'])->where('is_active', true);
    //     } else if($exists) {
    //         $query  = Course::with(['category', 'instructor', 'coursePromotionCode'])
    //             ->join('course_promotion_code as cpc', 'courses.id', '=', 'cpc.course_id')
    //             ->where('cpc.code', $exists->code)
    //             ->where('courses.is_active', true);
    //     }

    //     // Filtro por búsqueda
    //     if ($request->has('search') && $request->search) {
    //         $searchTerm = $request->search;
    //         $query->where(function($q) use ($searchTerm) {
    //             $q->where('title', 'like', '%' . $searchTerm . '%')
    //                 ->orWhere('description', 'like', '%' . $searchTerm . '%')
    //                 ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
    //                 ->orWhereHas('category', function($q) use ($searchTerm) {
    //                     $q->where('name', 'like', '%' . $searchTerm . '%');
    //                 })
    //                 ->orWhereHas('instructor', function($q) use ($searchTerm) {
    //                     $q->where('names', 'like', '%' . $searchTerm . '%');
    //                 });
    //         });
    //     }

    //     // Filtrar por categoría
    //     if ($request->has('category') && $request->category) {
    //         $query->where('category_id', $request->category);
    //     }

    //     // Ordenar
    //     $sort = $request->get('sort', 'newest');
    //     switch ($sort) {
    //         case 'oldest':
    //             $query->orderBy('courses.created_at', 'asc');
    //             break;
    //         case 'popular':
    //             $query->withCount('enrollments')->orderBy('enrollments_count', 'desc');
    //             break;
    //         case 'price_low':
    //             $query->orderBy('courses.price', 'asc');
    //             break;
    //         case 'price_high':
    //             $query->orderBy('courses.price', 'desc');
    //             break;
    //         case 'name_asc':
    //             $query->orderBy('title', 'asc');
    //             break;
    //         case 'name_desc':
    //             $query->orderBy('title', 'desc');
    //             break;
    //         default: // newest
    //             $query->orderBy('courses.created_at', 'desc');
    //     }

    //     $courses    = $query->paginate(12);
    //     $categories = Category::where('is_active', true)->get();
    //     $enterprise = Enterprise::first();

    //     // Si es una petición AJAX, retornar solo la vista parcial
    //     if ($request->ajax()) {
    //         return view('student.partials.courses-grid', compact('courses'))->render();
    //     }

    //     return view('student.courses', compact('courses', 'categories', 'enterprise'));
    // }

    public function courses(Request $request, $code = null) {
        $query = Course::query()->select('courses.*')->with(['category', 'instructor'])->where('courses.is_active', true);

        // 2. Lógica del Código Promocional / Partner
        $partner = null;
        if ($code) {
            $partner = User::where('code', $code)->where('is_active', true)->first();

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
                    $course->final_price = $promoCode->promotion_price;
                    $course->original_price = $course->price;
                    $course->has_promotion = true;
                    $course->discount_percentage = $promoCode->discount_percentage;
                    $course->promo_code = $promoCode->code;
                } else {
                    // Mantener precio normal
                    $course->final_price = $course->getFinalPriceAttribute();
                    $course->has_promotion = $course->getIsOnPromotionAttribute();
                }
            } else {
                // Sin código o sin partner válido, usar precio normal
                $course->final_price = $course->getFinalPriceAttribute();
                $course->has_promotion = $course->getIsOnPromotionAttribute();
            }

            return $course;
        });

        // 8. Respuesta AJAX o normal
        if ($request->ajax()) {
            return view('student.partials.courses-grid', compact('courses'))->render();
        }

        $categories = Category::where('is_active', true)->get();
        $enterprise = Enterprise::first();

        return view('student.courses', compact('courses', 'categories', 'enterprise', 'partner'));
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
