<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token) {
        $this->token = $token;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        // Generamos la URL de recuperación usando la ruta que tienes en web.php
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Pasamos los datos a la vista Blade que crearemos
        return (new MailMessage)
            ->subject(Lang::get('Recuperación de Contraseña'))
            ->view('emails.auth.reset-password', [
                'url' => $url,
                'user' => $notifiable
            ]);
    }
}
