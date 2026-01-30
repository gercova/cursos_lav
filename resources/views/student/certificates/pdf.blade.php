<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado</title>
    <style>
        body {
            font-family: 'Arial', sans-serif !important;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: #2d3748;
        }
        .certificate-container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            padding: 10px;
        }
        .certificate-card {
            width: 98%;
            max-width: 1000px;
            height: 95%;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 3px solid #3b82f6;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 30px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .logo {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 4px 4px rgba(0,0,0,0.1));
        }
        .title-container {
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .certificate-title {
            font-family: 'Georgia', serif;
            font-size: 2.5rem;
            color: #2563eb;
            margin: 0 0 10px 0;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .subtitle {
            font-size: 1rem;
            color: #4b5563;
            margin: 0;
            font-style: italic;
        }
        .recipient-section {
            text-align: center;
            margin: 20px 0;
        }
        .recipient-label {
            font-size: 1.1rem;
            color: #4b5563;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        .recipient-name {
            font-family: 'Georgia', serif;
            font-size: 2rem;
            color: #1e40af;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            word-break: break-word;
        }
        .course-section {
            text-align: center;
            margin: 20px 0;
        }
        .course-label {
            font-size: 1.1rem;
            color: #4b5563;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        .course-title {
            font-family: 'Georgia', serif;
            font-size: 1.5rem;
            color: #059669;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .details-section {
            text-align: center;
            margin: 20px 0;
        }
        .details-text {
            font-size: 1.1rem;
            color: #2d3748;
            line-height: 1.6;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #cbd5e0;
            color: #6b7280;
            font-size: 0.9rem;
        }
        .info-column {
            flex: 1;
            text-align: left;
        }
        .info-column h4 {
            font-size: 0.9rem;
            color: #1e40af;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        .verification-link {
            margin-top: 5px;
            font-size: 0.9rem;
            color: #3b82f6;
            word-break: break-all;
        }
        .footer-code {
            position: absolute;
            bottom: 15px;
            left: 20px;
            font-size: 0.85rem;
            color: #718096;
            font-weight: bold;
            background-color: #edf2f7;
            padding: 5px 10px;
            border-radius: 4px;
            z-index: 10;
        }
        .seal {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 80px;
            height: 80px;
            opacity: 0.05;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-card">
            <!-- Sello de fondo (opcional) -->
            <div class="seal"></div>

            <!-- Encabezado con título y subtítulo -->
            <div class="header">
                <div class="title-container">
                    <img src="{{ $enterprise->logo_path }}" alt="Logo IPF" class="logo">
                    <h1 class="certificate-title">CERTIFICADO DE CAPACITACIÓN</h1>
                    <p class="subtitle">Otorgado por {{ $enterprise->trade_name ?? 'IPF CONSULTORES SAC' }}</p>
                </div>
            </div>

            <!-- Sección del participante -->
            <div class="recipient-section">
                <p class="recipient-label">A:</p>
                <h2 class="recipient-name">{{ $certificate->user->names ?? 'Nombre del Participante' }}</h2>
            </div>

            <!-- Sección del curso -->
            <div class="course-section">
                <p class="course-label">Por haber culminado y aprobado con éxito el curso de</p>
                <h3 class="course-title">{{ $certificate->course->title ?? 'CURSO ESPECÍFICO' }}</h3>
            </div>

            <!-- Detalles del curso -->
            <div class="details-section">
                <p class="details-text">
                    Realizado de manera virtual el <strong>{{ \Carbon\Carbon::parse($certificate->issue_date)->format('d/m/Y') }}</strong><br>
                    con una duración total de <strong>{{ round($certificate->total_hours, 1) }} horas lectivas</strong>.
                </p>
            </div>

            <!-- Pie de página con información y verificación -->
            <div class="footer">
                <div class="info-column">
                    <h4>Número de Certificado</h4>
                    <p>{{ $certificate->certificate_number }}</p>
                    {{-- <h4>Fecha de Emisión</h4>
                    <p>{{ \Carbon\Carbon::parse($certificate->issue_date)->format('d/m/Y') }}</p> --}}
                </div>
                <div class="info-column">
                    <h4>Verificación</h4>
                    <p class="verification-link">
                        <a href="{{ route('verify.certificate', $certificate->certificate_code) }}">
                            {{ route('verify.certificate', $certificate->certificate_code) }}
                        </a>
                    </p>
                </div>
            </div>

            <!-- Código en la parte inferior izquierda -->
            {{-- <div class="footer-code">
                CÓDIGO: {{ $certificate->certificate_code }}
            </div> --}}
        </div>
    </div>
</body>
</html>
