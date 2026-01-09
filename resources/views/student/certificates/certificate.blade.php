<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $certificate->course->title }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif;
            background-color: #ffffff;
        }

        .certificate-container {
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
        }

        .certificate-border {
            position: absolute;
            top: 0.5cm;
            left: 0.5cm;
            right: 0.5cm;
            bottom: 0.5cm;
            border: 3px solid #2c3e50;
            padding: 1.5cm;
        }

        .header {
            text-align: center;
            margin-bottom: 1.5cm;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 32px;
            margin: 0;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .header h2 {
            color: #3498db;
            font-size: 20px;
            margin: 5px 0 0 0;
            font-weight: normal;
        }

        .content {
            text-align: center;
            margin-bottom: 2cm;
        }

        .student-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin: 1cm 0;
            text-transform: uppercase;
        }

        .course-details {
            font-size: 18px;
            line-height: 1.6;
            margin: 0.5cm 0;
        }

        .course-title {
            font-size: 22px;
            font-weight: bold;
            color: #e74c3c;
            margin: 0.5cm 0;
        }

        .hours {
            font-size: 18px;
            margin: 0.5cm 0;
        }

        .date {
            font-size: 16px;
            margin: 1cm 0;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 2cm;
            padding: 0 2cm;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-line {
            border-top: 1px solid #2c3e50;
            width: 80%;
            margin: 0.5cm auto;
            padding-top: 0.5cm;
        }

        .footer {
            position: absolute;
            bottom: 1.5cm;
            left: 1.5cm;
            right: 1.5cm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .certificate-number {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: bold;
        }

        .qr-container {
            text-align: center;
        }

        .qr-code {
            width: 150px;
            height: 150px;
            margin-bottom: 10px;
        }

        .verification-text {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(52, 152, 219, 0.1);
            font-weight: bold;
            z-index: -1;
        }

        .logo {
            height: 80px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Borde decorativo -->
        <div class="certificate-border">

            <!-- Marca de agua -->
            <div class="watermark">IPF CONSULTORES SAC</div>

            <!-- Logo y encabezado -->
            <div class="header">
                <h1>IPF CONSULTORES SAC</h1>
                <h2>OTORGA EL SIGUIENTE</h2>
                <h1>CERTIFICADO DE CAPACITACIÓN</h1>
            </div>

            <!-- Contenido principal -->
            <div class="content">
                <p class="student-name">{{ $certificate->user->names }}</p>

                <p class="course-details">
                    Por haber participado y aprobado el
                </p>

                <p class="course-title">"{{ $certificate->course->title }}"</p>

                <p class="course-details">
                    organizado por IPF CONSULTORES SAC,
                </p>

                <p class="hours">
                    con una duración total de <strong>{{ number_format($certificate->total_hours ?? $certificate->course->duration, 1) }} horas lectivas</strong>
                </p>

                <p class="date">
                    <strong>Fecha de emisión:</strong> {{ $certificate->issue_date->format('d/m/Y') }}
                </p>
            </div>

            <!-- Firmas -->
            <div class="signatures">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p><strong>FRANCISCO LLACTAS FLORES</strong></p>
                    <p>CIP N°: 206139 - ESPECIALISTA EN SST</p>
                </div>

                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p><strong>DIRECTOR ACADÉMICO</strong></p>
                    <p>IPF CONSULTORES SAC</p>
                </div>
            </div>

            <!-- Pie de página con número de certificado y QR -->
            <div class="footer">
                <div class="certificate-number">
                    Certificado N°: {{ $certificate->getFormattedCertificateNumber() }}
                </div>

                <div class="qr-container">
                    <img src="{{ $certificate->getQrCodeBase64(120) }}" alt="Código QR" class="qr-code">
                    <p class="verification-text">Escanear para verificar</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
