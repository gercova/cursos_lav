<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 30px; border-top: 4px solid #2563eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h2 { color: #1f2937; margin-top: 0; }
        .data-row { margin-bottom: 15px; border-bottom: 1px solid #f3f4f6; padding-bottom: 10px; }
        .label { font-weight: bold; color: #4b5563; display: block; margin-bottom: 5px; font-size: 14px; }
        .value { color: #111827; font-size: 16px; }
        .message-box { background-color: #f9fafb; padding: 15px; border-radius: 6px; margin-top: 10px; color: #374151; line-height: 1.5; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Nuevo mensaje desde la web</h2>
        <p style="color: #6b7280; margin-bottom: 20px;">Has recibido un nuevo mensaje a través del formulario de contacto.</p>

        <div class="data-row">
            <span class="label">Nombre del remitente:</span>
            <span class="value">{{ $name }}</span>
        </div>
        
        <div class="data-row">
            <span class="label">Correo electrónico:</span>
            <span class="value"><a href="mailto:{{ $email }}">{{ $email }}</a></span>
        </div>

        <div class="data-row">
            <span class="label">Teléfono:</span>
            <span class="value">{{ $phone ?? 'No proporcionado' }}</span>
        </div>

        <div class="data-row">
            <span class="label">Asunto seleccionado:</span>
            <span class="value">{{ ucfirst($subject) }}</span>
        </div>

        <div>
            <span class="label">Mensaje:</span>
            <div class="message-box">{{ $user_message }}</div> </div>
        </div>
    </div>
</body>
</html>