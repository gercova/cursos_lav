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
        $cartItems = Cart::with('course.category')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->course->final_price;
        });

        return view('student.cart', compact('cartItems', 'total'));
    }

    public function add($courseId): JsonResponse {
        $course = Course::where('is_active', true)->findOrFail($courseId);

        // Verificar si ya está en el carrito
        $existingCartItem = Cart::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->first();

        if ($existingCartItem) {
            return response()->json([
                'success' => false,
                'message' => 'El curso ya está en el carrito'
            ]);
        }

        // Verificar si ya está inscrito
        $isEnrolled = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->exists();

        if ($isEnrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Ya estás inscrito en este curso'
            ]);
        }

        Cart::create([
            'user_id' => Auth::id(),
            'course_id' => $courseId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Curso agregado al carrito'
        ]);
    }

    public function remove($courseId): JsonResponse {
        Cart::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Curso removido del carrito'
        ]);
    }

    public function checkout(Request $request) {
        $cartItems = Cart::with('course')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'El carrito está vacío');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->course->final_price;
        });

        // Simular procesamiento de pago
        foreach ($cartItems as $cartItem) {
            // Crear inscripción
            $enrollment = Enrollment::create([
                'user_id'       => Auth::id(),
                'course_id'     => $cartItem->course_id,
                'enrolled_at'   => now(),
                'status'        => 'active',
                'progress'      => 0,
            ]);

            // Registrar pago
            Payment::create([
                'enrollment_id' => $enrollment->id,
                'amount' => $cartItem->course->final_price,
                'currency' => 'PEN',
                'payment_method' => 'card',
                'status' => 'completed',
                'paid_at' => now(),
                'transaction_id' => 'TXN-' . uniqid(),
            ]);

            // Eliminar del carrito
            $cartItem->delete();
        }

        return redirect()->route('my-courses')
            ->with('success', '¡Pago procesado exitosamente! Ya puedes acceder a tus cursos.');
    }

    public function getCartCount() {
        $count = Cart::where('user_id', Auth::id())->count();

        return response()->json([
            'count' => $count
        ]);
    }
}
