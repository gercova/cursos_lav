<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Services\AffiliateService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
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

    public function applyPromoCode(Request $request) {
        $request->validate([
            'promo_code' => 'required|string|max:20'
        ]);

        $code = strtoupper(trim($request->promo_code));
        $user = Auth::user();

        // Verificar si el código es válido
        $affiliate = User::where('code', $code)
            ->where('id', '!=', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$affiliate) {
            return response()->json([
                'success' => false,
                'message' => 'Código de promoción inválido o inactivo'
            ], 400);
        }

        // Guardar código en sesión
        $request->session()->put('promotion_code', $code);

        return response()->json([
            'success' => true,
            'message' => 'Código aplicado correctamente',
            'discount_percentage' => 10, // Porcentaje de descuento
            'affiliate_name' => $affiliate->names
        ]);
    }

    public function processCulqiPayment(Request $request) {
        $request->validate([
            'token_id'              => 'required|string',
            'customer.first_name'   => 'required|string|max:100',
            'customer.last_name'    => 'required|string|max:100',
            'customer.email'        => 'required|email',
            'customer.phone'        => 'required|string|max:20',
            'customer.address'      => 'required|string',
            'amount'                => 'required|numeric|min:1',
            'card_holder_name'      => 'required|string',
            'promotion_code'        => 'nullable|string|max:20'
        ]);

        $userId = Auth::id();
        $cartItems = Cart::getItems($userId);

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tu carrito está vacío'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Obtener código de promoción
            $promotionCode = $request->promotion_code ?: $request->session()->get('promotion_code');
            
            // Verificar afiliado
            $affiliate = null;
            if ($promotionCode) {
                $affiliate = User::where('code', $promotionCode)
                    ->where('id', '!=', $userId)
                    ->where('is_active', true)
                    ->first();
                
                if (!$affiliate) {
                    $promotionCode = null;
                }
            }

            // Crear orden primero
            $order = $this->createOrder($request, $cartItems, $promotionCode);

            // 1. Crear el cargo en Culqi
            $chargeResponse = $this->createCulqiCharge([
                'token_id'      => $request->token_id,
                'amount'        => $request->amount * 100, // Culqi espera centavos
                'currency_code' => 'PEN',
                'email'         => $request->customer['email'],
                'source_id'     => $request->token_id,
                'order_id'      => $order->order_number
            ]);

            if (!$chargeResponse['success']) {
                throw new \Exception($chargeResponse['message']);
            }

            // 2. Crear registro de pago en la base de datos
            $payment = Payment::create([
                'user_id'           => $userId,
                'order_id'          => $order->id,
                'transaction_id'    => $chargeResponse['data']['id'],
                'amount'            => $request->amount,
                'currency'          => 'PEN',
                'payment_method'    => 'culqi_card',
                'status'            => 'completed',
                'paid_at'           => now(),
                'metadata' => [
                    'culqi_charge_id'   => $chargeResponse['data']['id'],
                    'card_last_four'    => $chargeResponse['data']['source']['card_number'] ?? null,
                    'card_brand'        => $chargeResponse['data']['source']['card_brand'] ?? null,
                    'customer'          => $request->customer,
                    'card_holder_name'  => $request->card_holder_name,
                    'promotion_code'    => $promotionCode,
                    'affiliate_id'      => $affiliate?->id
                ]
            ]);

            // 3. Actualizar orden con el pago
            $order->update(['status' => 'completed']);

            // 4. Crear inscripciones para cada curso y registrar ventas de afiliado
            $enrollments = [];
            foreach ($cartItems as $item) {
                $enrollment = Enrollment::create([
                    'user_id'       => $userId,
                    'course_id'     => $item->course_id,
                    'payment_id'    => $payment->id,
                    'order_id'      => $order->id,
                    'enrolled_at'   => now(),
                    'progress'      => 0,
                    'status'        => 'active'
                ]);

                $enrollments[] = $enrollment;

                // 5. Incrementar contador de estudiantes del curso
                Course::where('id', $item->course_id)->increment('students_count');

                // 6. Registrar venta de afiliado si hay código
                if ($promotionCode && $affiliate) {
                    AffiliateService::registerSale($order, $enrollment, $promotionCode);
                }
            }

            // 7. Limpiar carrito y sesión
            Cart::clear($userId);
            $request->session()->forget('promotion_code');

            DB::commit();

            // 8. Enviar email de confirmación
            $this->sendPaymentConfirmationEmail($payment, $cartItems);

            return response()->json([
                'success' => true,
                'message' => '¡Pago realizado exitosamente! Ya puedes acceder a tus cursos.',
                'redirect_url' => route('student.my-courses'),
                'payment_id' => $payment->id,
                'order_number' => $order->order_number
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Culqi payment error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processPagoEfectivo(Request $request) {
        $request->validate([
            'customer.first_name'   => 'required|string|max:100',
            'customer.last_name'    => 'required|string|max:100',
            'customer.email'        => 'required|email',
            'customer.phone'        => 'required|string|max:20',
            'customer.address'      => 'required|string',
            'amount'                => 'required|numeric|min:1',
            'promotion_code'        => 'nullable|string|max:20'
        ]);

        $userId = Auth::id();
        $cartItems = Cart::getItems($userId);

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tu carrito está vacío'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Obtener código de promoción
            $promotionCode = $request->promotion_code ?: $request->session()->get('promotion_code');
            
            // Verificar afiliado
            $affiliate = null;
            if ($promotionCode) {
                $affiliate = User::where('code', $promotionCode)
                    ->where('id', '!=', $userId)
                    ->where('is_active', true)
                    ->first();
                
                if (!$affiliate) {
                    $promotionCode = null;
                }
            }

            // Crear orden primero
            $order = $this->createOrder($request, $cartItems, $promotionCode);

            // 1. Generar CIP en Culqi para PagoEfectivo
            $cipResponse = $this->createCulqiCIP([
                'amount'        => $request->amount * 100,
                'currency_code' => 'PEN',
                'email'         => $request->customer['email'],
                'first_name'    => $request->customer['first_name'],
                'last_name'     => $request->customer['last_name'],
                'phone_number'  => $request->customer['phone'],
                'order_id'      => $order->order_number
            ]);

            if (!$cipResponse['success']) {
                throw new \Exception($cipResponse['message']);
            }

            // 2. Crear registro de pago pendiente
            $payment = Payment::create([
                'user_id'           => $userId,
                'order_id'          => $order->id,
                'transaction_id'    => $cipResponse['data']['id'],
                'amount'            => $request->amount,
                'currency'          => 'PEN',
                'payment_method'    => 'pago_efectivo',
                'status'            => 'pending',
                'metadata' => [
                    'culqi_cip_id'  => $cipResponse['data']['id'],
                    'cip_code'      => $cipResponse['data']['cip_code'],
                    'cip_url'       => $cipResponse['data']['cip_url'],
                    'expires_at'    => $cipResponse['data']['expires_at'],
                    'customer'      => $request->customer,
                    'promotion_code' => $promotionCode,
                    'affiliate_id'  => $affiliate?->id
                ]
            ]);

            // 3. Crear order items (pero no inscripciones aún)
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'          => $order->id,
                    'course_id'         => $item->course_id,
                    'course_title'      => $item->course->title,
                    'course_image'      => $item->course->image_url,
                    'price'             => $item->course->price,
                    'promotion_price'   => $item->course->promotion_price,
                    'final_price'       => $item->course->final_price
                ]);
            }

            // 4. Guardar CIP en sesión para mostrar al usuario
            session()->put('pending_cip', [
                'cip_code'      => $cipResponse['data']['cip_code'],
                'cip_url'       => $cipResponse['data']['cip_url'],
                'amount'        => $request->amount,
                'expires_at'    => $cipResponse['data']['expires_at'],
                'payment_id'    => $payment->id,
                'order_id'      => $order->id
            ]);

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'CIP generado exitosamente',
                'redirect_url'  => route('payment.cip-instructions', $payment->id),
                'payment_id'    => $payment->id,
                'order_number'  => $order->order_number
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('PagoEfectivo CIP error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al generar el CIP: ' . $e->getMessage()
            ], 500);
        }
    }

    private function createOrder(Request $request, $cartItems, $promotionCode = null) {
        // Calcular totales
        $subtotal = Cart::getTotal(Auth::id());
        
        // Aplicar descuento por código de promoción
        $discount = 0;
        if ($promotionCode) {
            $discount = $subtotal * 0.10; // 10% de descuento
        }
        
        $subtotalWithDiscount   = max(0, $subtotal - $discount);
        $tax                    = $subtotalWithDiscount * 0.18;
        $total                  = $subtotalWithDiscount + $tax;

        // Crear orden
        $order = Order::create([
            'user_id'       => Auth::id(),
            'order_number'  => 'ORD-' . time() . '-' . Auth::id(),
            'subtotal'      => $subtotal,
            'tax'           => $tax,
            'discount'      => $discount,
            'total'         => $total,
            'currency'      => 'PEN',
            'status'        => 'pending',
            'billing_info'  => [
                'first_name'    => $request->customer['first_name'] ?? Auth::user()->names,
                'last_name'     => $request->customer['last_name'] ?? '',
                'email'         => $request->customer['email'] ?? Auth::user()->email,
                'phone'         => $request->customer['phone'] ?? Auth::user()->phone,
                'address'       => $request->customer['address'] ?? Auth::user()->address
            ],
            'payment_method' => $request->isMethod('post') ? 'pago_efectivo' : 'culqi_card',
            'notes'         => $promotionCode ? "Código promocional: {$promotionCode}" : null
        ]);

        // Crear items de la orden
        foreach ($cartItems as $item) {
            $finalPrice = $item->course->final_price;
            
            // Aplicar descuento por código promocional
            if ($promotionCode) {
                $finalPrice = $finalPrice * 0.90;
            }
            
            OrderItem::create([
                'order_id'          => $order->id,
                'course_id'         => $item->course_id,
                'course_title'      => $item->course->title,
                'course_image'      => $item->course->image_url,
                'price'             => $item->course->price,
                'promotion_price'   => $item->course->promotion_price,
                'final_price'       => $finalPrice
            ]);
        }

        return $order;
    }

    private function createCulqiCharge($data) {
        $privateKey = config('services.culqi.secret_key');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $privateKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.culqi.com/v2/charges', [
                'amount'        => $data['amount'],
                'currency_code' => $data['currency_code'],
                'email'         => $data['email'],
                'source_id'     => $data['source_id'],
                'capture'       => true,
                'description'   => 'Pago de cursos en plataforma educativa',
                'order_id'      => $data['order_id'],
                'metadata' => [
                    'platform'  => 'EduPlatform',
                    'user_id'   => Auth::id(),
                    'order_id'  => $data['order_id']
                ]
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            } else {
                Log::error('Culqi charge error: ' . $response->body());
                return [
                    'success' => false,
                    'message' => $response->json()['merchant_message'] ?? 'Error al procesar el pago'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Culqi API error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de conexión con el procesador de pagos'
            ];
        }
    }

    private function createCulqiCIP($data) {
        $privateKey = config('services.culqi.secret_key');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $privateKey,
                'Content-Type'  => 'application/json'
            ])->post('https://api.culqi.com/v2/cips', [
                'amount'            => $data['amount'],
                'currency_code'     => $data['currency_code'],
                'email'             => $data['email'],
                'first_name'        => $data['first_name'],
                'last_name'         => $data['last_name'],
                'phone_number'      => $data['phone_number'],
                'order_id'          => $data['order_id'],
                'expiration_date'   => now()->addDays(1)->timestamp,
                'metadata' => [
                    'platform'  => 'EduPlatform',
                    'user_id'   => Auth::id(),
                    'order_id'  => $data['order_id']
                ]
            ]);

            if ($response->successful()) {
                return [
                    'success'   => true,
                    'data'      => $response->json()
                ];
            } else {
                Log::error('Culqi CIP error: ' . $response->body());
                return [
                    'success' => false,
                    'message' => $response->json()['merchant_message'] ?? 'Error al generar el CIP'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Culqi CIP API error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de conexión con Culqi'
            ];
        }
    }

    public function cipInstructions($paymentId) {
        $payment = Payment::with(['order', 'order.items'])
            ->where('id', $paymentId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $cipData = $payment->metadata;

        return view('student.cip-instructions', compact('payment', 'cipData'));
    }

    public function cipStatus($paymentId) {
        $payment = Payment::with(['order'])
            ->where('id', $paymentId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Verificar estado del CIP en Culqi
        $status = $this->checkCulqiCIPStatus($payment->metadata['culqi_cip_id']);

        return response()->json([
            'status' => $status,
            'payment_status' => $payment->status
        ]);
    }

    private function checkCulqiCIPStatus($cipId) {
        $privateKey = config('services.culqi.secret_key');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $privateKey
            ])->get("https://api.culqi.com/v2/cips/{$cipId}");

            if ($response->successful()) {
                $data = $response->json();

                // Si el CIP está pagado, procesar
                if ($data['state'] === 'paid') {
                    $this->processPaidCIP($data['id']);
                }

                return $data['state'];
            }

        } catch (\Exception $e) {
            Log::error('Check CIP status error: ' . $e->getMessage());
        }

        return 'unknown';
    }

    private function processPaidCIP($cipId) {
        DB::beginTransaction();

        try {
            // Buscar pago pendiente con este CIP
            $payment = Payment::with(['order', 'order.items'])
                ->where('metadata->culqi_cip_id', $cipId)
                ->where('status', 'pending')
                ->first();

            if ($payment && $payment->order) {
                // Actualizar estado del pago
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now()
                ]);

                // Actualizar estado de la orden
                $payment->order->update(['status' => 'completed']);

                // Crear inscripciones
                foreach ($payment->order->items as $item) {
                    $enrollment = Enrollment::create([
                        'user_id'       => $payment->user_id,
                        'course_id'     => $item->course_id,
                        'payment_id'    => $payment->id,
                        'order_id'      => $payment->order_id,
                        'enrolled_at'   => now(),
                        'progress'      => 0,
                        'status'        => 'active'
                    ]);

                    // Incrementar contador de estudiantes del curso
                    Course::where('id', $item->course_id)->increment('students_count');

                    // Registrar venta de afiliado si hay código
                    $promotionCode = $payment->metadata['promotion_code'] ?? null;
                    if ($promotionCode) {
                        AffiliateService::registerSale($payment->order, $enrollment, $promotionCode);
                    }
                }

                // Limpiar carrito
                Cart::clear($payment->user_id);

                // Limpiar sesión
                session()->forget('pending_cip');
                session()->forget('promotion_code');

                DB::commit();

                // Enviar email de confirmación
                $this->sendPaymentConfirmationEmail($payment, $payment->order->items);
                
                Log::info('CIP paid and processed successfully: ' . $cipId);
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Process paid CIP error: ' . $e->getMessage());
        }
    }

    private function sendPaymentConfirmationEmail($payment, $items) {
        $user = Auth::user();
        $order = $payment->order;
        
        // Aquí implementarías el envío de email
        // Por ahora solo logueamos
        Log::info('Payment confirmation email queued for payment: ' . $payment->id, [
            'user_id' => $user->id,
            'order_number' => $order->order_number,
            'amount' => $payment->amount,
            'items_count' => count($items)
        ]);
    }

    public function webhook(Request $request) {
        $signature = $request->header('Culqi-Signature');
        $payload = $request->getContent();

        // Verificar firma de Culqi
        if (!$this->verifyCulqiSignature($signature, $payload)) {
            Log::warning('Invalid Culqi webhook signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = $request->all();

        Log::info('Culqi webhook received: ' . json_encode($event));

        // Procesar diferentes tipos de eventos
        switch ($event['type']) {
            case 'charge.succeeded':
                $this->handleChargeSucceeded($event['data']);
                break;

            case 'charge.failed':
                $this->handleChargeFailed($event['data']);
                break;

            case 'cip.paid':
                $this->handleCIPPaid($event['data']);
                break;

            case 'cip.expired':
                $this->handleCIPExpired($event['data']);
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    private function verifyCulqiSignature($signature, $payload) {
        $webhookSecret      = config('services.culqi.webhook_secret');
        if (!$webhookSecret) {
            Log::warning('Culqi webhook secret not configured');
            return false;
        }
        
        $computedSignature  = hash_hmac('sha256', $payload, $webhookSecret);
        return hash_equals($signature, $computedSignature);
    }

    private function handleChargeSucceeded($charge) {
        // Buscar pago por transaction_id
        $payment = Payment::with(['order'])
            ->where('transaction_id', $charge['id'])
            ->first();

        if ($payment && $payment->status !== 'completed') {
            DB::beginTransaction();
            try {
                $payment->update([
                    'status'    => 'completed',
                    'paid_at'   => Carbon::createFromTimestamp($charge['creation_date']),
                    'metadata'  => array_merge($payment->metadata, [
                        'culqi_response' => $charge
                    ])
                ]);

                // Actualizar orden
                if ($payment->order) {
                    $payment->order->update(['status' => 'completed']);
                }

                // Crear inscripciones si no existen
                if ($payment->order) {
                    $enrollmentsExist = Enrollment::where('order_id', $payment->order_id)->exists();
                    if (!$enrollmentsExist) {
                        foreach ($payment->order->items as $item) {
                            $enrollment = Enrollment::create([
                                'user_id'       => $payment->user_id,
                                'course_id'     => $item->course_id,
                                'payment_id'    => $payment->id,
                                'order_id'      => $payment->order_id,
                                'enrolled_at'   => now(),
                                'progress'      => 0,
                                'status'        => 'active'
                            ]);

                            // Incrementar contador de estudiantes
                            Course::where('id', $item->course_id)->increment('students_count');

                            // Registrar venta de afiliado
                            $promotionCode = $payment->metadata['promotion_code'] ?? null;
                            if ($promotionCode) {
                                AffiliateService::registerSale($payment->order, $enrollment, $promotionCode);
                            }
                        }

                        // Limpiar carrito
                        Cart::clear($payment->user_id);
                    }
                }

                DB::commit();
                Log::info('Payment completed via webhook: ' . $payment->id);

            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error processing charge.succeeded webhook: ' . $e->getMessage());
            }
        }
    }

    private function handleChargeFailed($charge) {
        $payment = Payment::where('transaction_id', $charge['id'])->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'metadata' => array_merge($payment->metadata, [
                    'culqi_response' => $charge,
                    'failure_reason' => $charge['failure_message'] ?? 'Unknown'
                ])
            ]);
            
            // Actualizar orden
            if ($payment->order) {
                $payment->order->update(['status' => 'failed']);
            }
            
            Log::info('Payment failed via webhook: ' . $payment->id);
        }
    }

    private function handleCIPPaid($cip) {
        $this->processPaidCIP($cip['id']);
    }

    private function handleCIPExpired($cip) {
        $payment = Payment::where('metadata->culqi_cip_id', $cip['id'])
            ->where('status', 'pending')
            ->first();
        
        if ($payment) {
            $payment->update([
                'status' => 'expired',
                'metadata' => array_merge($payment->metadata, [
                    'expired_at' => now()->toISOString()
                ])
            ]);
            
            // Actualizar orden
            if ($payment->order) {
                $payment->order->update(['status' => 'expired']);
            }
            
            Log::info('CIP expired: ' . $cip['id']);
        }
    }

    public function removePromoCode(Request $request) {
        $request->session()->forget('promotion_code');
        
        return response()->json([
            'success' => true,
            'message' => 'Código de promoción removido'
        ]);
    }
}
