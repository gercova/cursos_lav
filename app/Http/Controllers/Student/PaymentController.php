<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
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

    public function checkout() {
        $userId = Auth::id();
        $cartItems = Cart::getItems($userId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío');
        }

        // Calcular totales
        $subtotal   = Cart::getTotal($userId);
        $tax        = $subtotal * 0.18; // 18% IGV (Perú)
        $total      = $subtotal + $tax;

        // Formatear items para la vista
        $formattedItems = $cartItems->map(function ($item) {
            return [
                'id'                => $item->course_id,
                'title'             => $item->course->title,
                'instructor'        => $item->course->instructor->names,
                'image_url'         => $item->course->image_url,
                'price'             => $item->course->promotion_price ?? $item->course->price,
                'original_price'    => $item->course->price,
                'promotion_price'   => $item->course->promotion_price
            ];
        });

        return view('student.checkout', compact(
            'cartItems',
            'formattedItems',
            'subtotal',
            'tax',
            'total'
        ));
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
            'card_holder_name'      => 'required|string'
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
            // 1. Crear el cargo en Culqi
            $chargeResponse = $this->createCulqiCharge([
                'token_id'      => $request->token_id,
                'amount'        => $request->amount * 100, // Culqi espera centavos
                'currency_code' => 'PEN',
                'email'         => $request->customer['email'],
                'source_id'     => $request->token_id
            ]);

            if (!$chargeResponse['success']) {
                throw new \Exception($chargeResponse['message']);
            }

            // 2. Crear registro de pago en la base de datos
            $payment = Payment::create([
                'user_id'           => $userId,
                'transaction_id'    => $chargeResponse['data']['id'],
                'amount'            => $request->amount,
                'currency'          => 'PEN',
                'payment_method'    => 'culqi_card',
                'status'            => 'completed',
                'metadata' => [
                    'culqi_charge_id'   => $chargeResponse['data']['id'],
                    'card_last_four'    => $chargeResponse['data']['source']['card_number'] ?? null,
                    'card_brand'        => $chargeResponse['data']['source']['card_brand'] ?? null,
                    'customer'          => $request->customer,
                    'card_holder_name'  => $request->card_holder_name
                ]
            ]);

            // 3. Crear inscripciones para cada curso
            foreach ($cartItems as $item) {
                Enrollment::create([
                    'user_id'       => $userId,
                    'course_id'     => $item->course_id,
                    'payment_id'    => $payment->id,
                    'enrolled_at'   => now(),
                    'progress'      => 0,
                    'status'        => 'active'
                ]);

                // 4. Incrementar contador de estudiantes del curso
                Course::where('id', $item->course_id)->increment('students_count');
            }

            // 5. Limpiar carrito
            Cart::clear($userId);

            DB::commit();

            // 6. Enviar email de confirmación (opcional)
            $this->sendPaymentConfirmationEmail($payment, $cartItems);

            return response()->json([
                'success' => true,
                'message' => '¡Pago realizado exitosamente! Ya puedes acceder a tus cursos.',
                'redirect_url' => route('student.my-courses'),
                'payment_id' => $payment->id
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
            'amount'                => 'required|numeric|min:1'
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
            // 1. Generar CIP en Culqi para PagoEfectivo
            $cipResponse = $this->createCulqiCIP([
                'amount'        => $request->amount * 100,
                'currency_code' => 'PEN',
                'email'         => $request->customer['email'],
                'first_name'    => $request->customer['first_name'],
                'last_name'     => $request->customer['last_name'],
                'phone_number'  => $request->customer['phone']
            ]);

            if (!$cipResponse['success']) {
                throw new \Exception($cipResponse['message']);
            }

            // 2. Crear registro de pago pendiente
            $payment = Payment::create([
                'user_id'           => $userId,
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
                    'customer'      => $request->customer
                ]
            ]);

            // 3. Guardar CIP en sesión para mostrar al usuario
            session()->put('pending_cip', [
                'cip_code'      => $cipResponse['data']['cip_code'],
                'cip_url'       => $cipResponse['data']['cip_url'],
                'amount'        => $request->amount,
                'expires_at'    => $cipResponse['data']['expires_at'],
                'payment_id'    => $payment->id
            ]);

            DB::commit();

            return response()->json([
                'success'       => true,
                'message'       => 'CIP generado exitosamente',
                'redirect_url'  => route('payment.cip-instructions', $payment->id),
                'payment_id'    => $payment->id
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
                'metadata' => [
                    'platform'  => 'EduPlatform',
                    'user_id'   => Auth::id()
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

    private function createCulqiCIP($data)
    {
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
                'order_id'          => 'ORD-' . time() . '-' . Auth::id(),
                'expiration_date'   => now()->addDays(1)->timestamp,
                'metadata' => [
                    'platform'  => 'EduPlatform',
                    'user_id'   => Auth::id()
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
        $payment = Payment::where('id', $paymentId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $cipData = $payment->metadata;

        return view('student.cip-instructions', compact('payment', 'cipData'));
    }

    public function cipStatus($paymentId) {
        $payment = Payment::where('id', $paymentId)
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

                // Si el CIP está pagado, actualizar el pago y crear inscripciones
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
            $payment = Payment::where('metadata->culqi_cip_id', $cipId)
                ->where('status', 'pending')
                ->first();

            if ($payment) {
                // Actualizar estado del pago
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now()
                ]);

                // Crear inscripciones
                $cartItems = Cart::getItems($payment->user_id);

                foreach ($cartItems as $item) {
                    Enrollment::create([
                        'user_id'       => $payment->user_id,
                        'course_id'     => $item->course_id,
                        'payment_id'    => $payment->id,
                        'enrolled_at'   => now(),
                        'progress'      => 0,
                        'status'        => 'active'
                    ]);

                    Course::where('id', $item->course_id)->increment('students_count');
                }

                // Limpiar carrito
                Cart::clear($payment->user_id);

                DB::commit();

                // Enviar email de confirmación
                $this->sendPaymentConfirmationEmail($payment, $cartItems);
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Process paid CIP error: ' . $e->getMessage());
        }
    }

    private function sendPaymentConfirmationEmail($payment, $courses) {
        // Implementar envío de email
        // Puedes usar Laravel Mail o un servicio como Mailgun
        // Por ahora solo logueamos
        Log::info('Payment confirmation email sent for payment: ' . $payment->id);
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
        $computedSignature  = hash_hmac('sha256', $payload, $webhookSecret);

        return hash_equals($signature, $computedSignature);
    }

    private function handleChargeSucceeded($charge) {
        // Buscar pago por transaction_id
        $payment = Payment::where('transaction_id', $charge['id'])->first();

        if ($payment && $payment->status !== 'completed') {
            $payment->update([
                'status'    => 'completed',
                'paid_at'   => Carbon::createFromTimestamp($charge['creation_date']),
                'metadata'  => array_merge($payment->metadata, [
                    'culqi_response' => $charge
                ])
            ]);

            Log::info('Payment completed via webhook: ' . $payment->id);
        }
    }

    private function handleCIPPaid($cip) {
        $this->processPaidCIP($cip['id']);
    }
}
