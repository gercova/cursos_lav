<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Finalización</title>
    <style>
        @page {
            margin: 0cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #333;
        }

        /* --- ELEMENTOS DE DISEÑO DE FONDO (Figuras Geométricas) --- */

        /* Triángulo azul oscuro inferior izquierdo grande */
        .shape-bottom-left {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 180px 0 0 1000px; /* Ajusta el ángulo */
            border-color: transparent transparent transparent #003399; /* Azul Oscuro IPF */
            z-index: -1;
        }

        /* Franja decorativa celeste inferior */
        .shape-bottom-accent {
            position: absolute;
            bottom: 40px;
            left: 0;
            width: 60%;
            height: 20px;
            background-color: #00AEEF; /* Celeste IPF */
            transform: skewX(-45deg);
            z-index: -2;
        }

        /* Triángulo/Detalle superior izquierdo */
        .shape-top-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 150px;
            height: 150px;
            background-color: transparent;
            border-top: 5px solid #00AEEF;
            border-left: 5px solid #00AEEF;
        }

        /* Detalles de líneas en la esquina derecha */
        .lines-right {
            position: absolute;
            top: 40px;
            right: 0;
            width: 150px;
            height: 100px;
        }
        .line {
            height: 3px;
            background-color: #00AEEF;
            margin-bottom: 10px;
            width: 100%;
        }

        /* --- CONTENIDO --- */
        .container {
            padding: 60px 50px;
            text-align: center;
            position: relative;
        }

        .header-logo {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Círculo simulado para el logo IPF si no tienes la imagen exacta */
        .logo-placeholder {
            display: inline-block;
            width: 80px;
            height: 80px;
            background-color: #00AEEF;
            color: white;
            border-radius: 50%;
            line-height: 80px;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #555;
            letter-spacing: 2px;
            margin-bottom: 30px;
        }

        .certificate-title {
            font-size: 36px;
            font-weight: 900;
            color: #003399; /* Azul oscuro */
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            margin: 20px 0;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            display: inline-block;
            padding-bottom: 5px;
            min-width: 60%;
        }

        .body-text {
            font-size: 14px;
            line-height: 1.6;
            color: #444;
            width: 80%;
            margin: 0 auto 30px auto;
        }

        /* --- PIE DE PÁGINA Y FIRMAS --- */
        .footer {
            width: 100%;
            margin-top: 40px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-col-left {
            width: 30%;
            text-align: left;
            vertical-align: bottom;
            padding-left: 20px;
        }

        .footer-col-center {
            width: 40%;
            text-align: center;
            vertical-align: bottom;
        }

        .footer-col-right {
            width: 30%;
            text-align: right;
            vertical-align: bottom;
            padding-right: 20px;
        }

        /* Código QR */
        .qr-container img {
            width: 100px;
            height: 100px;
            border: 2px solid #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.2);
        }

        /* Firma */
        .signature-line {
            width: 80%;
            margin: 0 auto;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .instructor-name {
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }
        .instructor-title {
            font-size: 11px;
            color: #555;
        }

        /* Datos inferiores */
        .cert-number {
            font-family: monospace;
            font-size: 14px;
            color: white; /* Texto blanco sobre el fondo azul */
            font-weight: bold;
            position: absolute;
            bottom: 20px;
            left: 30px;
            z-index: 10;
        }

        .issue-date {
            font-size: 12px;
            color: #444;
            margin-bottom: 40px; /* Espacio para la firma */
        }

    </style>
</head>
<body>

    <div class="shape-bottom-accent"></div>
    <div class="shape-bottom-left"></div>
    <div class="shape-top-left"></div>

    <div class="lines-right">
        <div class="line" style="width: 100%"></div>
        <div class="line" style="width: 80%; margin-left: 20%"></div>
        <div class="line" style="width: 60%; margin-left: 40%"></div>
    </div>

    <div class="container">
        <div class="header-logo">
            <div class="logo-placeholder">IPF</div>
        </div>

        <div class="company-name">IPF CONSULTORES SAC</div>

        <div class="subtitle">OTORGA EL SIGUIENTE</div>

        <h1 class="certificate-title">CERTIFICADO DE CAPACITACIÓN</h1>

        <div class="subtitle" style="margin-top: 30px;">PARA:</div>

        <div class="student-name">
            {{ $certificate->user->names }}
        </div>

        <div class="body-text">
            Por haber participado y aprobado el curso de <strong>{{ strtoupper($certificate->course->title) }}</strong>,
            organizado por IPF CONSULTORES SAC, realizado de manera virtual
            el día {{ \Carbon\Carbon::parse($certificate->issue_date)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
            con una duración total de <strong>{{ $certificate->total_hours }} horas lectivas</strong>.
        </div>

        <div class="issue-date">
            Trujillo, {{ \Carbon\Carbon::parse($certificate->issue_date)->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}
        </div>

        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-col-left">
                        <div class="qr-container">
                            <img src="{{ $certificate->getQrCodeBase64(100) }}" alt="QR Code">
                        </div>
                    </td>

                    <td class="footer-col-center">
                        </td>

                    <td class="footer-col-right">
                        <div style="height: 50px;"></div> <div class="signature-line"></div>
                        <div class="instructor-name">
                            {{ $certificate->course->instructor->names ?? 'INSTRUCTOR RESPONSABLE' }}
                        </div>
                        <div class="instructor-title">
                            {{ $certificate->course->instructor->profession ?? 'ESPECIALISTA' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="cert-number">
        CERTIFICADO N°: {{ $certificate->getFormattedCertificateNumber() }}
    </div>
</body>
</html>
