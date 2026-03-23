<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"/>
    <title>Certificado IPF</title>
    <style>
        @page {
            size: A4 landscape;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif; 
        }

        /* div principal */
        .certificado {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #fff;
        }

        .inner-border {
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border:3px solid #1a3c8f;
            border-radius:10px;
            position: absolute;
        }
        
        .logo-wrapper { text-align:center; }

        .logo-wrapper img { margin-top: 50px; width: 125px; height: 125px; }
        
        .titulo {
            text-align:center; 
            font-size:32px; 
            font-weight:bold;
            color:#111; 
            letter-spacing:1px; 
            margin-bottom:5px; 
            text-transform:uppercase;
        }
        
        .subtitulo { 
            text-align:center; 
            font-size:18px; 
            font-style:italic; 
            color:#444; 
            margin-bottom:3px; 
        }
        
        .subtitulo-a { text-align:center; font-size:18px; font-style:italic; color:#444; margin-bottom:5px; }

        .nombre { text-align:center; font-size:25px; font-weight:bold; font-style:italic; color:#2563b0; margin-bottom:5px; }

        .descripcion { text-align:center; font-size:18px; font-style:italic; color:#333; margin-bottom:8px; }

        .curso { text-align:center; font-size:30px; font-weight:bold; font-style:italic; color:#1ba009; margin-bottom:14px; margin: 30px !important; }

        .detalle { text-align:center; font-size:18px; font-style:italic; color:#222; margin-bottom:28px; }

        .firmas { display:table; width:100%; margin-bottom:20px; }

        .firma-col { display:table-cell; width:50%; text-align:center; vertical-align:top; }

        .firma-col img { width:120px; height:80px; }

        .firma-nombre { font-size:18px; font-style:italic; font-weight:bold; color:#222; margin-top:2px; }

        .firma-cargo { font-size:18px; font-style:italic; color:#555; }

        .footer { display:table; width:100%; padding-top:10px; margin-top:5px; }

        .footer-col { display:table-cell; width:50%; text-align:center; vertical-align:top; }

        .footer-label { font-size:18px; font-weight:bold; color:#2563b0; margin-bottom:3px; }

        .footer-value { font-size:18px; color:#222; }

        .footer-link { font-size:18px; color:#2563b0; text-decoration:underline; }
    </style>
</head>
<body>
<div class="certificado">
    <div class="inner-border"></div>

    <div class="logo-wrapper">
        <img src="{{ $logoPath }}" alt="IPF">
    </div>

    <div class="titulo">Certificado de Capacitación</div>
    <div class="subtitulo">Otorgado por {{ $enterprise->company_name }}</div>
    <div class="subtitulo-a">A:</div>
    <div class="nombre">{{ $certificate->user->names }}</div>
    <div class="descripcion">Por haber culminado y aprobado con éxito el curso de</div>
    <div class="curso">{{ $certificate->course->title }}</div>
    <div class="detalle">
        Realizado de manera virtual el {{ $certificate->getFormattedIssueDate() }} con una duración total de {{ (int) round($certificate->total_hours, 0) + 1 }} horas lectivas.
    </div>

    <div class="firmas">
        <div class="firma-col">
            <img src="{{ $managerSignature }}" alt="Firma Gerente {{ $enterprise->legal_representative }}" style="size:100%;">
            <div class="firma-nombre">{{ $enterprise->legal_representative }}</div>
            <div class="firma-cargo">Gerente General</div>
            @if($instructor->colegial_type && $instructor->colegial)
                <div class="firma-cargo">
                    {{ $instructor->colegial_type }}: {{ $instructor->colegial }}
                </div>
            @endif
        </div>
        <div class="firma-col">
            <img src="{{ $instructorSignature }}" alt="Firma Especialista {{ $instructor->names }}" style="size:100%;">
            <div class="firma-nombre">{{ $instructor->names }}</div>
            <div class="firma-cargo">{{ $instructor->profession }}</div>
            {{-- <div class="firma-cargo">CIP: 1234567</div> --}}
            @if($instructor->colegial_type && $instructor->colegial)
                <div class="firma-cargo">
                    {{ $instructor->colegial_type }}: {{ $instructor->colegial }}
                </div>
            @endif
        </div>
    </div>

    <div class="footer">
        <div class="footer-col">
            <div class="footer-label">Número de Certificado</div>
            <div class="footer-value">{{ $certificate->certificate_number }}</div>
        </div>
        <div class="footer-col">
            <div class="footer-label">Enlace de verificación</div>
            <div class="footer-link"><a href="{{ route('verify.certificate', $certificate->certificate_code) }}">{{ $certificate->certificate_code }}</a></div>
        </div>
    </div>
</div>
</body>
</html>
