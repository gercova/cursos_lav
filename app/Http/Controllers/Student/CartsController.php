<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartsController extends Controller
{
    public function index(): View {
        $cartItems  = Cart::where('user_id', Auth::id())->with('course.category')->get();
        $total      = $cartItems->sum(function ($item) {
            return $item->course->promotion_price ?? $item->course->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function count() {
        $count = auth()->check() ? auth()->user()->cartItems()->count() : 0;
        return response()->json(['count' => $count]);
    }

    // Agregar al carrito
    public function add(Course $course) {
        // Verificar si ya está en el carrito
        $exists = Cart::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Este curso ya está en tu carrito.'
            ]);
        }

        // Verificar si ya está inscrito
        $isEnrolled = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->exists();

        if ($isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Ya estás inscrito en este curso.'
            ]);
        }

        Cart::create([
            'user_id'   => Auth::id(),
            'course_id' => $course->id
        ]);

        $cartCount = Cart::where('user_id', Auth::id())->count();

        return response()->json([
            'success'   => true,
            'message'   => 'Curso agregado al carrito.',
            'cartCount' => $cartCount
        ]);
    }

    // Eliminar del carrito
    public function remove(Course $course): JsonResponse {
        Cart::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->delete();

        $cartCount = Cart::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Curso eliminado del carrito.',
            'cartCount' => $cartCount
        ]);
    }

    // Limpiar carrito
    public function clear(): JsonResponse {
        Cart::where('user_id', Auth::id())->delete();
        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado correctamente.'
        ]);
    }
}
