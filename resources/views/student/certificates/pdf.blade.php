<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado - {{ $certificate->course->title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .certificate {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .border {
            border: 20px solid transparent;
            border-image: linear-gradient(45deg, #667eea, #764ba2);
            border-image-slice: 1;
            height: calc(100% - 40px);
            width: calc(100% - 40px);
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .content {
            padding: 60px;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 40px;
        }
        .title {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }
        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin: 30px 0;
            padding: 20px;
            border-top: 2px solid #667eea;
            border-bottom: 2px solid #667eea;
        }
        .course-info {
            font-size: 18px;
            color: #333;
            margin: 20px 0;
        }
        .details {
            margin: 40px 0;
            text-align: left;
            display: inline-block;
        }
        .detail-item {
            margin: 10px 0;
            font-size: 16px;
        }
        .qr-code {
            margin: 30px auto;
            width: 150px;
            height: 150px;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #666;
        }
        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #666;
        }
        .verification {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border"></div>
        <div class="content">
            <div class="logo">PLATAFORMA DE CURSOS</div>
            <div class="title">CERTIFICADO DE APROBACIÓN</div>
            <div class="subtitle">Se otorga el presente certificado a</div>

            <div class="student-name">{{ $certificate->user->names }}</div>

            <div class="course-info">
                por haber aprobado satisfactoriamente el curso
            </div>

            <div class="course-info" style="font-weight: bold; font-size: 22px;">
                "{{ $certificate->course->title }}"
            </div>

            <div class="details">
                <div class="detail-item">
                    <strong>Fecha de emisión:</strong> {{ $certificate->issue_date->format('d/m/Y') }}
                </div>
                <div class="detail-item">
                    <strong>Fecha de expiración:</strong> {{ $certificate->expiry_date->format('d/m/Y') }}
                </div>
                <div class="detail-item">
                    <strong>Calificación obtenida:</strong> {{ number_format($certificate->examAttempt->score, 1) }}/20
                </div>
                <div class="detail-item">
                    <strong>Código de verificación:</strong> {{ $certificate->certificate_code }}
                </div>
            </div>

            <div class="qr-code">
                <!-- En una implementación real, aquí iría un código QR -->
                CÓDIGO QR<br>
                {{ $certificate->certificate_code }}
            </div>

            <div class="footer">
                <div>Plataforma de Cursos Online</div>
                <div class="verification">
                    Verifique este certificado en: {{ $certificate->verification_url }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
