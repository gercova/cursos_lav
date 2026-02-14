<?php

namespace App\Services;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;

class MercadoPagoService {
    
    public function __construct() {
        // Configuramos el token desde el archivo de config
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
    }

    public function createCoursePreference(array $userData, array $courses,$orderNumber) {
        $client = new PreferenceClient();
        $items = [];
        $courseIds = [];
        foreach ($courses as $course) {
            $items[] = [
                "id"         => (string) $course['id'],
                "title"      => (string) $course['title'],
                "quantity"   => 1,
                "unit_price" => (float) number_format($course['price'], 2, '.', ''),
                "currency_id" => "PEN"
            ];
            $courseIds[] = $course['id'];
        }
        try {
            return $client->create([
                "items" => $items,
                "payer" => [
                    "name"  => $userData['name'] ?? 'Estudiante',
                    "email" => $userData['email'] ?? 'estudiante@ejemplo.com',
                ],
                "payment_methods" => [
                    "excluded_payment_types" => [
                        // ["id" => "ticket"]
                    ],
                    "installments" => 1,            // Para Yape/Débito suele ser 1 cuota
                    "default_payment_method_id" => null
                ],
                // Esto ayuda a que el Smart Checkout de MP identifique mejor al usuario peruano
                "notification_url" => config('app.url') . '/api/mp/webhook',
                "back_urls" => [
                    "success" => config('app.url') . "/pago/exitoso",
                    "failure" => config('app.url') . "/pago/fallido",
                    "pending" => config('app.url') . "/pago/pendiente",
                ],
                "auto_return" => "approved",
                "external_reference" => $orderNumber,
                "statement_descriptor" => "IPF EDUCA",
            ]);
        } catch (MPApiException $e) {
            // Esto es vital: captura el error real de la API
            Log::error("Error API Mercado Pago: " . json_encode($e->getApiResponse()->getContent()));
            return null;
        } catch (\Exception $e) {
            Log::error("Error General en MP Service: " . $e->getMessage());
            return null;
        }
    }
}