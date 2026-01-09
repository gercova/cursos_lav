<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Enterprise;
use Illuminate\Contracts\View\View;
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

    public function courses(Request $request) {
        $query = Course::with(['category', 'instructor'])->where('is_active', true);
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
            /*case 'rating':
                // Asumiendo que tienes un campo de rating o reviews
                $query->orderBy('rating', 'desc');
                break;*/
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

    public function show($slug): View {
        $course = Course::with(['sections.lessons', 'category', 'instructor', 'documents'])
            ->where('is_active', true)
            ->whereHas('sections', function ($query) {
                $query->where('is_active', true)
                    ->whereHas('lessons', function ($lessonQuery) {
                        $lessonQuery->where('is_active', true);
                    });
            })
            ->where('slug', $slug)
            ->first();

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->exists();
        }

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
