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

    public function createCoursePreference(array $userData, array $courseData) {
        $client = new PreferenceClient();
        
        try {
            return $client->create([
                "items" => [
                    [
                        "id"         => (string) $courseData['id'],
                        "title"      => (string) $courseData['title'],
                        "quantity"   => 1,
                        // Forzamos float y 2 decimales
                        "unit_price" => (float) number_format($courseData['price'], 2, '.', ''),
                        "currency_id" => "PEN"
                    ]
                ],
                "payer" => [
                    "name"  => $userData['name'] ?? 'Estudiante',
                    "email" => $userData['email'] ?? 'test_user_ipf@test.com',
                ],
                "payment_methods" => [
                    "excluded_payment_types" => [], // Asegúrate de que no haya nada excluido
                    "installments" => 1,            // Para Yape/Débito suele ser 1 cuota
                ],
                // Esto ayuda a que el Smart Checkout de MP identifique mejor al usuario peruano
                // "notification_url" => route('mp.webhook'),
                "back_urls" => [
                    "success" => "https://google.com", // Solo para probar si el error 400 desaparece
                    "failure" => "https://google.com",
                    "pending" => "https://google.com",
                ],
                "auto_return" => "approved",
                "external_reference" => "ORDER-" . time(),
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