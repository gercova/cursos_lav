<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #2563eb; padding: 24px; text-align: center; color: white; }
        .content { padding: 32px; color: #374151; line-height: 1.6; }
        .button { display: inline-block; padding: 12px 24px; background-color: #2563eb; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 20px 0; text: #fff;}
        .footer { background-color: #f3f4f6; padding: 16px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0;">Recuperación de Contraseña</h2>
        </div>
        <div class="content">
            <p>Hola, <strong>{{ $user->names }}</strong>,</p>
            <p>Recibes este correo porque hemos recibido una solicitud para restablecer la contraseña de tu cuenta.</p>
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Restablecer Contraseña</a>
            </div>
            <p>Este enlace expirará en poco tiempo por razones de seguridad.</p>
            <p>Si no realizaste esta solicitud, no es necesario realizar ninguna acción. Tu cuenta sigue segura.</p>
        </div>
        <div class="footer">
            <p>Si tienes problemas haciendo clic en el botón "Restablecer Contraseña", copia y pega la siguiente URL en tu navegador web:</p>
            <p style="word-break: break-all;">{{ $url }}</p>
        </div>
    </div>
</body>
</html>