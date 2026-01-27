<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $certificate->course->title }}</title>
    <style>
        /* ... mantén todos los estilos existentes ... */
        /* ELIMINA las clases relacionadas con QR: */
        /* .qr-container, .qr-code, .verification-text */

        /* En su lugar, añade: */
        .verification-section {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .verification-link {
            color: #003399;
            font-weight: bold;
            text-decoration: none;
            word-break: break-all;
            display: block;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }

        .verification-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-border">
            <!-- ... mantén todo el contenido existente ... -->

            <!-- Pie de página con número de certificado y enlace de verificación -->
            <div class="footer">
                <div class="certificate-number">
                    Certificado N°: {{ $certificate->getFormattedCertificateNumber() }}
                </div>

                <!-- REEMPLAZAR QR por enlace de verificación -->
                <div class="verification-section">
                    <p class="verification-text">Verificar certificado en:</p>
                    <a href="{{ $certificate->verification_url }}"
                       target="_blank"
                       class="verification-link">
                       {{ $certificate->verification_url }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
