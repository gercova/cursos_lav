{{-- resources/views/pdf.blade.php --}}
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Certificado</title>

    <style>
        /* DomPDF: define tamaño A4 apaisado y sin márgenes */
        @page { size: A4 landscape; margin: 0; }

        html, body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: DejaVu Sans, sans-serif;
        }

        :root{
            --border-blue: #1788C7;     /* borde del marco */
            --text-gray:  #4B5563;      /* rgb(75,85,99) */
            --name-blue:  #1E40AF;      /* rgb(30,64,175) */
            --link-blue:  #1155CC;      /* rgb(17,85,204) */
            --course-green:#059669;     /* rgb(5,150,105) */
            --muted-gray: #6B7280;      /* rgb(107,114,128) */
        }

        /* Lienzo A4 landscape ~ 297mm x 210mm */
        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #fff;
        }

        /* Marco interior */
        .frame {
            position: absolute;
            top: 12mm;
            left: 12mm;
            right: 12mm;
            bottom: 12mm;
            border: 2px solid var(--border-blue);
        }

        /* Contenido centrado */
        .content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            text-align: center;
        }

        /* Logo IPF */
        .logo-wrap{
            margin-top: 18mm;
            position: relative;
            height: 28mm;
        }
        .logo {
            width: 22mm;
            height: 22mm;
            border-radius: 999px;
            background: #1D4ED8;
            color: #ffffff;
            font-weight: 700;
            font-size: 20pt;
            line-height: 22mm;
            display: inline-block;
            position: relative;
            z-index: 3;
        }
        .logo-shadow-1,
        .logo-shadow-2{
            position: absolute;
            top: 1mm;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 999px;
            z-index: 1;
            opacity: .35;
        }
        .logo-shadow-1{
            width: 28mm; height: 28mm;
            background: #93C5FD;
        }
        .logo-shadow-2{
            width: 34mm; height: 34mm;
            background: #BFDBFE;
            top: -2mm;
            opacity: .22;
        }

        /* Tipografía / jerarquía */
        h1{
            margin: 10mm 0 0 0;
            font-size: 22pt;
            font-weight: 700;
            letter-spacing: .5px;
        }
        .subtitle{
            margin-top: 4mm;
            font-size: 12pt;
            font-style: italic;
            color: var(--text-gray);
        }
        .to{
            margin-top: 5mm;
            font-size: 12pt;
            font-style: italic;
            color: var(--text-gray);
        }
        .name{
            margin-top: 3mm;
            font-size: 20pt;
            font-style: italic;
            color: var(--name-blue);
        }
        .p{
            margin-top: 6mm;
            font-size: 13pt;
            font-style: italic;
            color: var(--text-gray);
        }
        .course{
            margin-top: 2mm;
            font-size: 20pt;
            font-style: italic;
            color: var(--course-green);
        }
        .p-tight{
            margin-top: 2mm;
            font-size: 13pt;
            font-style: italic;
            color: var(--text-gray);
        }

        /* Bloque inferior (2 columnas) */
        .bottom{
            position: absolute;
            left: 0;
            right: 0;
            bottom: 32mm;
            text-align: center;
        }
        table.meta{
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 11pt;
        }
        table.meta th{
            padding: 0 10mm 4mm 10mm;
            font-weight: 600;
            color: var(--name-blue);
        }
        table.meta td{
            padding: 0 10mm;
            color: var(--muted-gray);
            font-weight: 500;
        }
        a.verify{
            color: var(--link-blue);
            text-decoration: underline;
        }
    </style>
</head>

<body>
@php
    // Valores por defecto (puedes pasarlos desde el controller)
    $nombre     = $nombre     ?? 'Dr. Shany Predovic';
    $curso      = $curso      ?? 'Laravel 10: De Principiante a Experto';
    $fecha      = $fecha      ?? '03/02/2026';
    $horas      = $horas      ?? 40;
    $certNumero = $certNumero ?? '000700010001IPF-EDUCA';
    $verifyUrl  = $verifyUrl  ?? 'http://127.0.0.1:8000/verify/CERT-0001-0007';
@endphp

<div class="page">
    <div class="frame"></div>

    <div class="content">
        <div class="logo-wrap">
            {{-- <div class="logo-shadow-2"></div>
            <div class="logo-shadow-1"></div>
            <div class="logo">IPF</div> --}}
            {{-- <img src="{{ storage_path('/storage/ipf-logo.png') }}" alt="Logo IPF-Educa"> --}}
            <img src="{{ $logoPath }}" alt="Logo IPF-Educa" style="width:15%;">
            
        </div>

        <h1>CERTIFICADO DE CAPACITACIÓN</h1>

        <div class="subtitle">Otorgado por IPF Educa</div>

        <div class="to">A:</div>
        <div class="name">{{ $certificate->user->names }}</div>

        <div class="p">Por haber culminado y aprobado con éxito el curso de</div>
        <div class="course">{{ $certificate->course->title }}</div>

        <div class="p-tight">Realizado de manera virtual el {{ $certificate->getFormattedIssueDate() }}</div>
        <div class="p-tight">con una duración total de {{ round($certificate->total_hours, 1) }} horas lectivas.</div>

        <div class="bottom">
            <table class="meta">
                <thead>
                    <tr>
                        <th>Número de Certificado</th>
                        <th>Enlace de verificación</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $certificate->certificate_number }}</td>
                        <td><a class="verify" href="{{ route('verify.certificate', $certificate->certificate_code) }}">{{ $verifyUrl }}</a></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
</html>
