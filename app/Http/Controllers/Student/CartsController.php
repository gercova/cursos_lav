<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Enrollment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartsController extends Controller {

    public function index(): View {
        $userId = Auth::id();

        // Obtener items del carrito usando el modelo
        $cartItems = Cart::getItems($userId);

        // Calcular totales
        $subtotal = Cart::getTotal($userId);
        $tax = $subtotal * 0.18; // 18% de impuesto (ajusta según tu país)
        $total = $subtotal + $tax;

        // Verificar si hay un cupón activo en la sesión
        $discount = session('coupon_discount', 0);

        return view('student.card', compact(
            'cartItems',
            'subtotal',
            'tax',
            'total',
            'discount'
        ));
    }

    public function add($course): JsonResponse {
        $userId = Auth::id();

        // Verificar si el curso ya está en el carrito
        $existing = Cart::where('user_id', $userId)
            ->where('course_id', $course)
            ->first();

        if ($existing) {
            return response()->json([
                'existing'  => true,
                'success'   => false,
                'message'   => 'Este curso ya está en tu carrito'
            ], 400);
        }

        // Verificar si el usuario ya está inscrito en el curso
        $enrolled = Enrollment::where('user_id', $userId)
            ->where('course_id', $course)
            ->exists();

        if ($enrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Ya estás inscrito en este curso'
            ], 400);
        }

        // Agregar al carrito
        Cart::create([
            'user_id'   => $userId,
            'course_id' => $course
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Curso agregado al carrito'
        ]);
    }

    public function remove($courseId): JsonResponse {
        $userId = Auth::id();

        $deleted = Cart::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Curso eliminado del carrito'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el curso'
        ], 400);
    }

    public function count(): JsonResponse {
        $userId = Auth::id();
        $count  = Cart::where('user_id', $userId)->count();

        return response()->json([
            'count' => $count
        ]);
    }

    public function checkout(Request $request) {
        $userId     = Auth::id();
        $cartItems  = Cart::getItems($userId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')
                ->with('error', 'Tu carrito está vacío');
        }

        // Aquí implementarías la lógica de checkout
        // Por ahora redirigimos a una página de confirmación
        return redirect()->route('payment.checkout')->with('cart_items', $cartItems);
    }

    public function applyCoupon(Request $request) {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        // Aquí implementarías la lógica de validación del cupón
        // Por ahora simulamos un descuento del 10%
        $couponCode = $request->coupon_code;

        // Validar cupón (ejemplo)
        if (strtoupper($couponCode) === 'DESCUENTO10') {
            session(['coupon_discount' => 0.10]); // 10% de descuento
            return response()->json([
                'success'   => true,
                'message'   => 'Cupón aplicado correctamente',
                'discount'  => 10
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Cupón inválido o expirado'
        ], 400);
    }

    public function clear(): JsonResponse {
        $userId = Auth::id();
        Cart::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado correctamente'
        ]);
    }
}
