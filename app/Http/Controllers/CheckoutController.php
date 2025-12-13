<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function __construct() {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // Mostrar la página de checkout
    public function showCheckout() {
        $user       = Auth::user();
        $cartItems  = Cart::getItems($user->id);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío.');
        }

        $subtotal   = Cart::getTotal($user->id);
        $tax        = $subtotal * 0.18; // 18% IGV en Perú
        $total      = $subtotal + $tax;

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    // Crear sesión de pago con Stripe
    public function createPaymentSession(Request $request) {
        $user = Auth::user();
        $cartItems = Cart::getItems($user->id);

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Carrito vacío'], 400);
        }

        // Crear orden temporal
        $order = $this->createOrder($user->id, $cartItems);

        // Crear línea items para Stripe
        $lineItems = [];
        foreach ($cartItems as $item) {
            $price = $item->course->promotion_price ?? $item->course->price;

            $lineItems[] = [
                'price_data'        => [
                    'currency'      => 'pen',
                    'product_data'  => [
                        'name'          => $item->course->title,
                        'description'   => $item->course->short_description,
                        'images'        => [$item->course->image_url],
                    ],
                    'unit_amount'   => $price * 100, // Stripe usa centavos
                ],
                'quantity' => 1,
            ];
        }

        // Agregar impuestos
        $subtotal = Cart::getTotal($user->id);
        $tax = $subtotal * 0.18;

        if ($tax > 0) {
            $lineItems[] = [
                'price_data'        => [
                    'currency'      => 'pen',
                    'product_data'  => [
                        'name'      => 'IGV (18%)',
                    ],
                    'unit_amount'   => $tax * 100,
                ],
                'quantity' => 1,
            ];
        }

        try {
            $session = Session::create([
                'customer_email'        => $user->email,
                'payment_method_types'  => ['card'],
                'line_items'            => $lineItems,
                'mode'                  => 'payment',
                'success_url'           => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
                'cancel_url'            => route('checkout.cancel') . '?order_id=' . $order->id,
                'metadata'              => [
                    'order_id'  => $order->id,
                    'user_id'   => $user->id,
                ],
            ]);

            return response()->json([
                'sessionId' => $session->id,
                'url'       => $session->url
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Crear orden en la base de datos
    private function createOrder($userId, $cartItems) {
        $subtotal   = Cart::getTotal($userId);
        $tax        = $subtotal * 0.18;
        $total      = $subtotal + $tax;

        $order = Order::create([
            'user_id'   => $userId,
            'subtotal'  => $subtotal,
            'tax'       => $tax,
            'total'     => $total,
            'status'    => 'pending',
        ]);

        foreach ($cartItems as $item) {
            $price = $item->course->promotion_price ?? $item->course->price;

            OrderItem::create([
                'order_id'          => $order->id,
                'course_id'         => $item->course_id,
                'course_title'      => $item->course->title,
                'course_image'      => $item->course->image_url,
                'price'             => $item->course->price,
                'promotion_price'   => $item->course->promotion_price,
                'final_price'       => $price,
            ]);
        }

        return $order;
    }

    // Pago exitoso
    public function success(Request $request) {
        $sessionId  = $request->get('session_id');
        $orderId    = $request->get('order_id');

        try {
            $session    = Session::retrieve($sessionId);
            $order      = Order::findOrFail($orderId);

            if ($session->payment_status === 'paid') {
                // Actualizar orden
                $order->update([
                    'status'            => 'completed',
                    'payment_method'    => 'stripe',
                ]);

                // Crear registro de pago
                $this->createPaymentRecord($order, $session);

                // Matricular al usuario en los cursos
                $this->enrollUserInCourses($order);

                // Limpiar carrito
                Cart::clear(Auth::id());

                return view('checkout.success', [
                    'order' => $order,
                    'session' => $session
                ]);
            }

        } catch (\Exception $e) {
            return redirect()->route('checkout.failure')->with('error', 'Error al procesar el pago.');
        }

        return redirect()->route('cart');
    }

    // Pago cancelado
    public function cancel(Request $request) {
        $orderId    = $request->get('order_id');
        $order      = Order::find($orderId);

        if ($order) {
            $order->update(['status' => 'cancelled']);
        }

        return view('checkout.cancel', compact('order'));
    }

    // Crear registro de pago
    private function createPaymentRecord(Order $order, $session) {
        Payment::create([
            'order_id'          => $order->id,
            'user_id'           => $order->user_id,
            'payment_id'        => $session->payment_intent,
            'payment_method'    => 'stripe',
            'amount'            => $order->total,
            'currency'          => 'PEN',
            'status'            => 'completed',
            'payment_details'   => [
                'session_id'        => $session->id,
                'customer_email'    => $session->customer_email,
                'payment_status'    => $session->payment_status,
            ],
            'paid_at'           => now(),
        ]);
    }

    // Matricular usuario en cursos
    private function enrollUserInCourses(Order $order) {
        foreach ($order->items as $item) {
            // Verificar si ya está inscrito
            $existingEnrollment = Enrollment::where('user_id', $order->user_id)
                ->where('course_id', $item->course_id)
                ->first();

            if (!$existingEnrollment) {
                Enrollment::create([
                    'user_id'       => $order->user_id,
                    'course_id'     => $item->course_id,
                    'order_id'      => $order->id,
                    'status'        => 'active',
                    'enrolled_at'   => now(),
                ]);
            }
        }
    }

    // Webhook para recibir notificaciones de Stripe
    public function webhook(Request $request) {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    private function handleCheckoutSessionCompleted($session) {
        $orderId = $session->metadata->order_id;
        $order = Order::find($orderId);

        if ($order && $order->status === 'pending') {
            $order->update([
                'status' => 'completed',
                'payment_method' => 'stripe',
            ]);

            $this->createPaymentRecord($order, $session);
            $this->enrollUserInCourses($order);
        }
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        // Lógica adicional si es necesario
    }
}
