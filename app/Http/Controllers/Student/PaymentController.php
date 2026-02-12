<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Services\MercadoPagoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller {
    protected $mpService;
    public function __construct(MercadoPagoService $mpService) {
        $this->mpService = $mpService;
        // $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function checkout(Request $request) {
        $userId = Auth::id();
        $cartItems = Cart::getItems($userId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío');
        }

        // Obtener código de promoción si existe
        $promotionCode = $request->session()->get('promotion_code');
        
        // Verificar si el código pertenece a un afiliado
        $affiliate = null;
        if ($promotionCode) {
            $affiliate = User::where('code', $promotionCode)
                ->where('id', '!=', $userId)
                ->where('is_active', true)
                ->first();
            
            if (!$affiliate) {
                // Código inválido o pertenece al mismo usuario
                $request->session()->forget('promotion_code');
                $promotionCode = null;
            }
        }

        // Calcular totales con descuento si aplica
        $subtotal = Cart::getTotal($userId);
        
        // Aplicar descuento por código de promoción (si aplica)
        $discount = 0;
        if ($promotionCode && $affiliate) {
            // Buscar códigos promocionales del afiliado
            foreach ($cartItems as $item) {
                // Aquí deberías verificar si el curso tiene un descuento específico con este código
                // Por ahora aplicamos un 10% de descuento general como ejemplo
                $discount += $item->course->final_price * 0.10;
            }
        }
        
        $subtotalWithDiscount = max(0, $subtotal - $discount);
        $tax = $subtotalWithDiscount * 0.18; // 18% IGV (Perú)
        $total = $subtotalWithDiscount + $tax;

        // Formatear items para la vista
        $formattedItems = $cartItems->map(function ($item) use ($promotionCode) {
            $originalPrice = $item->course->price;
            $finalPrice = $item->course->final_price;
            
            // Aplicar descuento adicional por código promocional si existe
            if ($promotionCode) {
                $finalPrice = $finalPrice * 0.90; // 10% de descuento por código
            }
            
            return [
                'id'                => $item->course_id,
                'title'             => $item->course->title,
                'instructor'        => $item->course->instructor->names,
                'image_url'         => $item->course->image_url,
                'price'             => $originalPrice,
                'final_price'       => $finalPrice,
                'promotion_price'   => $item->course->promotion_price,
                'has_discount'      => $promotionCode ? true : $item->course->getIsOnPromotionAttribute()
            ];
        });

        return view('student.checkout', compact(
            'cartItems',
            'formattedItems',
            'subtotal',
            'discount',
            'subtotalWithDiscount',
            'tax',
            'total',
            'promotionCode',
            'affiliate'
        ));
    }

    public function createPreference(Request $request) {
        // 1. Validar que el total llegue desde el frontend
        if (!$request->total) {
            return response()->json(['error' => 'El total es requerido'], 400);
        }

        // 2. Preparar datos (pueden ser los que vienen de Alpine o de tu DB)
        $userData = [
            'name'  => Auth::user()->names ?? 'Usuario IPF',
            'email' => Auth::user()->email ?? 'estudiante@ejemplo.com',
        ];

        $courseData = [
            'id'    => 'CUR-IPF-001', // ID genérico o del curso real
            'title' => 'Especialización en Ingeniería - IPF Educa',
            'price' => $request->total // Usamos el total que calculó tu Alpine.js
        ];

        // 3. Crear la preferencia
        $preference = $this->mpService->createCoursePreference($userData, $courseData);

        if (!$preference) {
            return response()->json([
                'error' => 'No se pudo crear la preferencia',
                'detail' => 'Revisa los logs del servidor para ver el error de la API'
            ], 500);
        }

        // 4. Retornar el ID y el punto de inicio para la redirección
        return response()->json([
            'id' => $preference->id,
            'init_point' => $preference->init_point
        ]);
    }

    public function success(Request $request) {
        return view('student.payments.success'); // Asegúrate que la ruta del archivo sea correcta
    }

    public function failure(Request $request) {
        return view('student.payments.failure'); // Asegúrate que la ruta del archivo sea correcta
    }

    public function pending(Request $request) {
        return view('student.payments.pending'); // Asegúrate que la ruta del archivo sea correcta
    }
}
