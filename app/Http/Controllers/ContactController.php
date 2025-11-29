<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller {

    public function sendMessage(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Aquí puedes:
            // 1. Guardar en la base de datos
            // 2. Enviar email
            // 3. Integrar con CRM

            $contactData = [
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'subject'   => $request->subject,
                'message'   => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            // Ejemplo de envío de email (descomentar cuando configures mail)

            Mail::send('emails.contact', $contactData, function($message) use ($contactData) {
                $message->to('info@eduplatform.com')
                    ->subject('Nuevo mensaje de contacto: ' . $contactData['subject'])
                    ->from($contactData['email'], $contactData['name']);
            });

            return response()->json([
                'success' => true,
                'message' => '¡Mensaje enviado con éxito! Te contactaremos pronto.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el mensaje. Por favor, intenta nuevamente.'
            ], 500);
        }
    }
}
