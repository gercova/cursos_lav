<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificado - {{ $certificate->getFormattedCertificateNumber() }}</title>
    <style>
        /* Reset y configuración básica */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            /* Fuente sans-serif como solicitado */
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: #ffffff;
            color: #000000;
            width: 29.7cm;  /* A4 landscape */
            height: 21cm;
            position: relative;
            overflow: hidden;
            line-height: 1.4;
        }

        /* Logo en la parte superior izquierda */
        .logo-container {
            position: absolute;
            top: 2.5cm;
            left: 2.5cm;
            z-index: 10;
        }

        .logo-img {
            width: 120px;
            height: auto;
            display: block;
        }

        /* Elementos decorativos geométricos */
        .decoration-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4cm;
            z-index: 1;
            overflow: hidden;
        }

        .blue-triangle {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 70%;
            height: 100%;
            background: #0038a8;
            clip-path: polygon(0 40%, 0% 100%, 100% 100%);
        }

        .light-accent {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80%;
            height: 100%;
            background: #0099cc;
            opacity: 0.3;
            clip-path: polygon(0 30%, 0% 100%, 100% 100%);
        }

        /* Contenido principal centrado */
        .main-content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 5;
            padding: 0 2cm;
            text-align: center;
        }

        /* Encabezado de la empresa */
        .company-name {
            font-size: 28pt;
            font-weight: bold;
            color: #000;
            margin-bottom: 0.5cm;
            letter-spacing: 1px;
        }

        .subtitle {
            font-size: 16pt;
            color: #333;
            margin-bottom: 1cm;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Título principal del certificado */
        .certificate-title {
            font-size: 36pt;
            font-weight: bold;
            color: #000;
            margin-bottom: 1.5cm;
            padding-bottom: 0.5cm;
            border-bottom: 2px solid #0038a8;
            display: inline-block;
        }

        /* Sección del destinatario */
        .recipient-section {
            margin: 1.5cm 0;
            width: 100%;
        }

        .for-label {
            font-size: 18pt;
            font-weight: bold;
            color: #0038a8;
            margin-bottom: 0.3cm;
        }

        .recipient-name {
            font-size: 44pt;
            font-weight: bold;
            color: #000;
            margin: 0.5cm 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Descripción del curso */
        .course-description {
            font-size: 16pt;
            line-height: 1.6;
            margin: 1cm auto;
            width: 80%;
            text-align: center;
        }

        .course-title {
            font-weight: bold;
            color: #0038a8;
            text-transform: uppercase;
        }

        .hours {
            font-weight: bold;
            color: #d40000;
        }

        /* Fecha y lugar */
        .date-location {
            font-size: 14pt;
            margin-top: 1cm;
            color: #333;
        }

        .city {
            font-weight: bold;
            color: #0038a8;
        }

        /* Firma removida según solicitud */

        /* Pie de página */
        .footer {
            position: absolute;
            bottom: 1cm;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2.5cm;
            z-index: 10;
        }

        .certificate-number {
            font-size: 14pt;
            font-weight: bold;
            color: #ffffff;
            background: #0038a8;
            padding: 0.5cm 1cm;
            border-radius: 4px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .verification-section {
            text-align: right;
        }

        .verification-text {
            font-size: 10pt;
            color: #444;
            margin-bottom: 0.2cm;
        }

        .verification-link {
            font-size: 11pt;
            font-weight: bold;
            color: #0038a8;
            text-decoration: none;
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 0.3cm 0.5cm;
            border-radius: 4px;
            border: 1px solid #ddd;
            display: inline-block;
        }

        /* Elemento decorativo superior derecho */
        .top-decoration {
            position: absolute;
            top: 0;
            right: 0;
            width: 6cm;
            height: 6cm;
            background: linear-gradient(135deg, #0099cc22 0%, #0038a822 100%);
            clip-path: polygon(100% 0, 0 0, 100% 100%);
            z-index: 2;
        }

        /* Sello decorativo */
        .seal {
            position: absolute;
            bottom: 3.5cm;
            right: 3cm;
            width: 3cm;
            height: 3cm;
            border: 3px dashed #0038a8;
            border-radius: 50%;
            opacity: 0.3;
            z-index: 3;
        }

        /* Líneas decorativas */
        .border-line-top {
            position: absolute;
            top: 1.5cm;
            left: 2cm;
            right: 2cm;
            height: 1px;
            background: linear-gradient(90deg, transparent, #0038a8, transparent);
        }

        .border-line-bottom {
            position: absolute;
            bottom: 5.5cm;
            left: 2cm;
            right: 2cm;
            height: 1px;
            background: linear-gradient(90deg, transparent, #0038a8, transparent);
        }
    </style>
</head>
<body>

    <!-- Logo -->
    <div class="logo-container">
        <!-- IMPORTANTE: Usar public_path() para rutas absolutas -->
        <img src="{{ public_path('storage/logos/ipf-logo.png') }}" alt="IPF CONSULTORES SAC" class="logo-img" onerror="this.style.display='none'">
    </div>

    <!-- Decoraciones -->
    <div class="top-decoration"></div>
    <div class="seal"></div>
    <div class="border-line-top"></div>
    <div class="border-line-bottom"></div>

    <!-- Contenido principal -->
    <div class="main-content">
        <div class="company-name">IPF CONSULTORES SAC</div>
        <div class="subtitle">OTORGA EL SIGUIENTE</div>
        <h1 class="certificate-title">CERTIFICADO DE CAPACITACIÓN</h1>

        <div class="recipient-section">
            <div class="for-label">PARA:</div>
            <div class="recipient-name">{{ strtoupper($certificate->user->names) }}</div>
        </div>

        <div class="course-description">
            Por haber participado y aprobado el curso
            <span class="course-title">"{{ strtoupper($certificate->course->title) }}"</span>
            organizado por IPF CONSULTORES SAC, realizado de manera virtual el
            <strong>{{ $certificate->getFormattedIssueDate() }}</strong>
            con una duración total de
            <span class="hours">{{ number_format($certificate->total_hours, 0) }} HORAS LECTIVAS</span>.
        </div>

        <div class="date-location">
            <span class="city">PUCALLPA</span>, {{ $certificate->getFormattedIssueDate() }}
        </div>
    </div>

    <!-- Decoración inferior -->
    <div class="decoration-bottom">
        <div class="light-accent"></div>
        <div class="blue-triangle"></div>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        <div class="certificate-number">
            CERTIFICADO N°: {{ $certificate->getFormattedCertificateNumber() }}
        </div>

        <div class="verification-section">
            <div class="verification-text">Validar autenticidad en:</div>
            <div class="verification-link">
                {{ config('app.url') }}/verify/{{ $certificate->certificate_code }}
            </div>
        </div>
    </div>

    <!-- Script para manejar errores de imágenes -->
    <script type="text/javascript">
        // Manejo de errores en imágenes
        document.addEventListener('DOMContentLoaded', function() {
            var images = document.getElementsByTagName('img');
            for (var i = 0; i < images.length; i++) {
                images[i].onerror = function() {
                    this.style.display = 'none';
                    console.log('Imagen no encontrada: ' + this.src);
                };
            }
        });
    </script>

</body>
</html>
