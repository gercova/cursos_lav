<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Services\MercadoPagoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

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
        if (!$request->total) {
            return response()->json(['error' => 'El total es requerido'], 400);
        }
        $user = Auth::user();
        $cartItems = Cart::getItems($user->id);
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Carrito vacío'], 400);
        }

        $subtotal = Cart::getTotal($user->id);
        $tax = $subtotal * 0.18;
        $total = $subtotal + $tax;
        $discount = 0;

        $date = Carbon::now()->format('Ymd'); // Ejemplo: 20251025
        $random = strtoupper(Str::random(5)); // Ejemplo: XJ829
        $orderNumber = "IPF-{$date}-{$random}";
        //se crea la orden
        try{
            return DB::transaction(function () use ($user, $cartItems, $subtotal, $tax, $total, $discount, $orderNumber) {
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id'      => $user->id,
                    'subtotal'     => $subtotal,
                    'tax'          => $tax,
                    'discount'     => $discount,
                    'total'        => $total,
                    'currency'     => 'PEN',
                    'status'       => 'pending',
                ]);
                $formattedCourses = [];
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id'        => $order->id,
                        'course_id'       => $item->course_id,
                        'course_title'    => $item->course->title,
                        'course_image'    => $item->course->image_url,
                        'price'           => $item->course->price,
                        'promotion_price' => $item->course->promotion_price,
                        'final_price'     => $item->course->final_price,
                    ]);

                    // Preparamos el array para el servicio de Mercado Pago
                    $formattedCourses[] = [
                        'id'    => $item->course_id,
                        'title' => $item->course->title,
                        'price' => $item->course->final_price
                    ];
                }

                $userData = [
                    'id'    => $user->id,
                    'name'  => Auth::user()->names ?? 'Usuario IPF',
                    'email' => Auth::user()->email ?? 'estudiante@ejemplo.com',
                ];

                //Crear la preferencia
                $preference = $this->mpService->createCoursePreference(
                    $userData, 
                    $formattedCourses,
                    $order->order_number
                );

                if (!$preference) {
                    throw new \Exception("Error al conectar con Mercado Pago");
                }
                return response()->json([
                    'id' => $preference->id,
                    'init_point' => $preference->init_point
                ]);
            });
        } catch (\Exception $e) {
            Log::error("Error al crear la orden: " . $e->getMessage());
            return response()->json(['error' => 'No se pudo procesar la orden'], 500);
        }
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

    public function webhook(Request $request) {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
        $id = $request->input('data.id') ?? $request->input('id');
        $type = $request->input('type');

        // Solo procesamos si es una notificación de pago
        if (!$id || ($type !== 'payment' && $type !== 'payment.created')) {
            return response()->json(['status' => 'ignored'], 200);
        }

        try {
            $client = new PaymentClient();
            $payment = $client->get($id);

            if ($payment->status === 'approved') {
                //La external_reference ahora es el order_number (ej: IPF-20250213-ABCDE)
                $orderNumber = $payment->external_reference;

                //Buscamos la orden
                $order = Order::with('items')->where('order_number', $orderNumber)->first();
                if (!$order) {
                    Log::error("Orden no encontrada en DB para la referencia: " . $orderNumber);
                    return response()->json(['status' => 'not_found'], 200);
                }
                if ($order && $order->status !== 'completed') {
                    DB::transaction(function () use ($order, $payment, $id) {
                        // Actualizamos la orden
                        $order->update([
                            'status' => 'completed',
                            'payment_method' => $payment->payment_method_id, // Guarda 'yape', 'visa', etc.
                            'notes' => "Pago aprobado MP ID: {$id}"
                        ]);
                        Payment::create([
                            'order_id'       => $order->id,
                            'user_id'        => $order->user_id,
                            'payment_id'     => $id, // ID de Mercado Pago
                            'payment_method' => $payment->payment_method_id,
                            'amount'         => $payment->transaction_amount,
                            'currency'       => $payment->currency_id,
                            'status'         => $order->status, // El enum que tienes en la imagen
                            'paid_at'        => now(),
                        ]);
                        foreach ($order->items as $item) {
                            Enrollment::updateOrCreate(
                                [
                                    'user_id'   => $order->user_id,
                                    'course_id' => $item->course_id,
                                ],
                                [
                                    // 'payment_id'   => $id,
                                    'enrolled_at'  => now(),
                                    'status'       => 'active', // Estado de la inscripción
                                    'progress'     => 0,        // Iniciamos en 0%
                                ]
                            );
                        }
                        Cart::where('user_id', $order->user_id)->delete();
                        Log::info("Pago Procesado: Orden {$order->order_number} pagada. Alumno {$order->user_id} inscrito.");
                    });
                }
            }
            return response()->json(['status' => 'ok'], 200);
        } catch (\Exception $e) {
            Log::error("Error en Webhook MP: " . $e->getMessage());
            return response()->json(['status' => 'error'], 200); // Retornamos 200 para que MP no reintente fallidos
        }
    }
}
