<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Certificado IPF</title>
    <style>
        @page {
            /* size: 297mm 210mm; */
            margin: 0mm;
        }

        html,
        body {
            /* margin: 0;
            padding: 0; */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #111111;
        }

        /* ─── SIDEBAR: posición fija, cubre toda la altura de la página ─── */
        /* mPDF soporta position:fixed para elementos que se repiten en todas las páginas */
        div.sidebar-z {
            /* z-index: 50; */
            left: 0mm;
            top: 0mm;
            background-color: #0055cc;
            width: 297mm;
            height: 2mm;
        }

        div.sidebar-bg {
            position: fixed;
            left: 0mm;
            top: 0mm;
            width: 2mm;
            height: 210mm;
            background-color: #0055cc;
        }

        div.sidebar-logo {
            position: fixed;
            /* position: relative; */
            left: 9mm;
            top: 78mm;
            width: 32mm;
            text-align: center;
        }

        div.sidebar-logo img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.50);
        }

        /* ─── ÁREA DE CONTENIDO ─── */
        div.content-area {
            margin-left: 50mm;
            padding: 11mm 13mm 9mm 12mm;
        }

        /* ─── TABLA ENCABEZADO ─── */
        table.t-header {
            width: 100%;
            border-collapse: collapse;
        }

        td.th-left {
            vertical-align: top;
        }

        td.th-right {
            width: 50mm;
            vertical-align: top;
            text-align: right;
        }

        /* ─── TIPOGRAFÍA ─── */
        .cert-title {
            font-size: 25pt;
            font-weight: bold;
            color: #111111;
            /* letter-spacing: 0.6pt;
            line-height: 1.2; */
        }

        .cert-granted {
            font-size: 15pt;
            color: #555555;
            margin-top: 3mm;
        }

        .student-name {
            /* font-family: Georgia, 'Times New Roman', serif; */
            font-size: 25pt;
            font-weight: bold;
            /* font-style: italic; */
            color: #111111;
            margin-top: 1.5mm;
            line-height: 1.15;
        }

        .cert-desc {
            font-size: 15pt;
            color: #555555;
            margin-top: 3.5mm;
        }

        .course-name {
            font-size: 25pt;
            font-weight: bold;
            color: #111111;
            line-height: 1.4;
            margin-top: 1.5mm;
        }

        .cert-detail {
            font-size: 15pt;
            color: #555555;
            margin-top: 3mm;
            line-height: 1.5;
        }

        /* ─── FIRMAS ─── */
        table.t-sigs {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6mm;
        }

        td.sig-cell {
            width: 50%;
            vertical-align: bottom;
        }

        .sig-line {
            border-top: 1pt solid #4a4a4a;
            width: 60mm;
            margin-top: 2px;
        }

        .sig-name {
            font-size: 15pt;
            font-weight: bold;
            color: #111111;
            margin-top: 3px;
        }

        .sig-role {
            font-size: 15pt;
            color: #555555;
            margin-top: 1px;
        }
    </style>
</head>

<body>
    <div class="sidebar-z"></div>
    {{-- ══ SIDEBAR AZUL (fondo fijo a página completa) ══ --}}
    <div class="sidebar-bg"></div>
    {{-- ══ LOGO CENTRADO EN SIDEBAR ══ --}}
    <div class="sidebar-logo">
        <img src="{{ $logoPath }}" alt="IPF">
    </div>
    {{-- ══════ CONTENIDO PRINCIPAL ══════ --}}
    <div class="content-area">
        {{-- ENCABEZADO: título + nombre | QR --}}
        <table class="t-header">
            <tr>
                <td class="th-left">
                    <div class="cert-title">CERTIFICADO DE CAPACITACIÓN</div>
                    <div class="cert-granted">Otorgado por {{ $enterprise->company_name }} a:</div>
                    <div class="student-name">{{ $certificate->user->names }}</div>
                </td>
                <td class="th-right">
                    <barcode code="{{ route('verify.certificate', $certificate->certificate_code) }}" type="QR"
                        size="1.5" error="M" style="border: 1px solid black;">
                </td>
            </tr>
        </table>
        {{-- CURSO --}}
        <div class="cert-desc">Por haber culminado y aprobado con éxito el curso de:</div>
        <div class="course-name">{{ $certificate->course->title }}</div>
        {{-- FECHA Y DURACIÓN --}}
        <div class="cert-detail">
            Realizado de manera virtual el {{ $certificate->getFormattedIssueDate() }}<br>
            con una duración total de {{ (int) round($certificate->total_hours, 0) }} horas lectivas.
        </div>
        {{-- FIRMAS --}}
        <table class="t-sigs">
            <tr>
                {{-- Firma Gerente --}}
                <td class="sig-cell">
                    @if ($managerSignature && file_exists($managerSignature))
                        <img src="{{ $managerSignature }}" style="width:200px;" alt="Firma Gerente">
                    @else
                        <div style="height:44px;"></div>
                    @endif
                    <hr>
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $enterprise->legal_representative }}</div>
                    <div class="sig-role">Gerente de Operaciones</div>
                    {{-- <hr> --}}
                    @if ($instructor->colegial_type && $instructor->colegial)
                        <div style="color:#ffffff" class="sig-role">{{ $instructor->colegial_type }}:
                            {{ $instructor->colegial }}</div>
                    @endif
                </td>
                {{-- Firma Instructor --}}
                <td class="sig-cell">
                    @if ($instructorSignature && file_exists($instructorSignature))
                        <img src="{{ $instructorSignature }}" style="width:200px;" alt="Firma Especialista">
                    @else
                        <div style="height:44px;"></div>
                    @endif
                    <hr>
                    <div class="sig-line"></div>
                    <div class="sig-name">{{ $instructor->names }}</div>
                    <div class="sig-role">{{ $instructor->profession }}</div>
                    {{-- <hr> --}}
                    @if ($instructor->colegial_type && $instructor->colegial)
                        <div class="sig-role">{{ $instructor->colegial_type }}: {{ $instructor->colegial }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
