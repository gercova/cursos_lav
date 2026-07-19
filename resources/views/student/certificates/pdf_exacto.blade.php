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
            background-color: #ffffff;
        }

        /* ── Border wrapper + inner content table ─────────────────── */
        /* .cert-wrap {
            width: 277mm;
            border: 1.5pt solid #2a2a2a;
            margin: 10mm;
            box-sizing: border-box;
        } */
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

        .cert-card {
            width: 100%;
            border-collapse: collapse;
        }

        .cert-card-td {
            padding: 9mm 13mm 11mm 13mm;
            vertical-align: top;
        }

        /* ── Header table ───────────────────────────────────────────── */
        .hdr {
            width: 100%;
            border-collapse: collapse;
        }

        .hdr-logo {
            width: 33mm;
            vertical-align: middle;
            text-align: left;
        }

        .logo-img {
            width: 28mm;
            height: 28mm;
        }

        .hdr-center {
            text-align: center;
            vertical-align: middle;
            padding: 0 4mm;
        }

        .title-main {
            font-size: 25pt;
            font-weight: bold;
            color: #111111;
            letter-spacing: 0.5pt;
            line-height: 1.0;
        }

        .title-sub {
            font-size: 15pt;
            color: #555555;
            margin-top: 3.5mm;
        }

        .title-name {
            font-size: 20pt;
            font-weight: bold;
            color: #111111;
            margin-top: 2mm;
        }

        .hdr-qr {
            width: 33mm;
            vertical-align: middle;
            text-align: right;
        }

        .qr-box {
            width: 31mm;
            height: 31mm;
            border: 1pt solid #2a2a2a;
            padding: 2pt;
            display: inline-block;
            box-sizing: border-box;
            text-align: center;
        }

        /* ── Body sections (using inner table for centering) ─────────── */
        .body-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10mm;
            margin-left: 50mm;
            margin-right: 50mm;
            margin-bottom: 40;
        }

        .body-td {
            text-align: center;
            vertical-align: top;
            /* margin: 40 40 40 40px; */

        }

        .body-intro {
            font-size: 15pt;
            color: #555555;
            margin-bottom: 5mm;
        }

        .course-name {
            font-size: 20pt;
            font-weight: bold;
            color: #111111;
            line-height: 1.25;
        }

        .detail-text {
            font-size: 15pt;
            color: #555555;
            margin-top: 5mm;
            line-height: 1.65;
            text-align: center;
        }

        /* ── Signatures ─────────────────────────────────────────────── */
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 7mm;
        }

        .sig-col {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            margin-left: 10mm;
            margin-right: 10mm;

        }

        .sig-gap {
            width: 12%;
        }

        .sig-img-wrap {
            height: 10mm;
            text-align: center;
            vertical-align: bottom;
        }

        .sig-img-wrap img {
            /* max-height: 14mm;
            max-width: 25mm; */
            /* display: block; */
            /* margin: 0 auto; */
        }

        .sig-line {
            border-top: 5pt solid #999999;
            width: 72%;
            margin: 1.5mm auto 0;
        }

        .sig-name {
            font-size: 15pt;
            font-weight: bold;
            color: #111111;
            margin-top: 1.5mm;
        }

        .sig-role {
            font-size: 15pt;
            color: #555555;
            margin-top: 1pt;
        }

        .sig-cred {
            font-size: 15pt;
            color: #555555;
            margin-top: 1pt;
        }
    </style>
</head>

<body>
    <div class="sidebar-z"></div>
    {{-- ══ SIDEBAR AZUL (fondo fijo a página completa) ══ --}}
    <div class="sidebar-bg"></div>
    <div class="cert-wrap">
        <table class="cert-card">
            <tr>
                <td class="cert-card-td">
                    {{-- ══ HEADER ══ --}}
                    <table class="hdr">
                        <tr>
                            <td class="hdr-logo">
                                @if ($logoPath && file_exists($logoPath))
                                    <img src="{{ $logoPath }}" class="logo-img" alt="IPF">
                                @endif
                            </td>
                            <td class="hdr-center">
                                <div class="title-main">CERTIFICADO DE CAPACITACIÓN</div>
                                {{-- <br> --}}
                                <div class="title-sub">Otorgado por {{ $enterprise->company_name }} a:</div>
                                <br>
                                <div class="title-name">{{ $certificate->user->names }}</div>
                            </td>
                            <td class="hdr-qr">
                                <div class="qr-box">
                                    <barcode code="{{ route('verify.certificate', $certificate->certificate_code) }}"
                                        type="QR" size="1.5" error="M" />
                                </div>
                            </td>
                        </tr>
                    </table>
                    {{-- ══ BODY (inner table keeps text centred in mPDF) ══ --}}
                    <table class="body-table">
                        <tr>
                            <td class="body-td">
                                <div class="body-intro">Por haber culminado y aprobado con éxito el curso de:</div>
                                <div class="course-name">{{ mb_strtoupper($certificate->course->title, 'UTF-8') }}</div>
                                <div class="detail-text">
                                    Realizado de manera virtual el {{ $certificate->getFormattedIssueDate() }}<br>
                                    con una duración total de {{ (int) round($certificate->total_hours, 0) }} horas
                                    lectivas.
                                </div>
                            </td>
                        </tr>
                    </table>
                    {{-- ══ SIGNATURES ══ --}}
                    <table class="sig-table">
                        <tr>
                            <td class="sig-col">
                                <div class="sig-img-wrap">
                                    @if ($managerSignature && file_exists($managerSignature))
                                        <img src="{{ $managerSignature }}" alt="Firma Gerente" width="25%">
                                    @endif
                                </div>
                                <hr style="width: 72%; margin: 1.5mm auto 0; margin-bottom: 5mm;">
                                {{-- <div class="sig-line"></div> --}}
                                <div class="sig-name">{{ $enterprise->legal_representative }}</div>
                                <div class="sig-role">Gerente de Operaciones</div>
                                @if ($enterprise->colegial_type && $enterprise->colegial)
                                    <div style="color: #ffffff;" class="sig-cred">{{ $enterprise->colegial_type }}: {{ $enterprise->colegial }}
                                    </div>
                                @endif
                            </td>
                            <td class="sig-gap"></td>
                            <td class="sig-col">
                                <div class="sig-img-wrap">
                                    @if ($instructorSignature && file_exists($instructorSignature))
                                        <img src="{{ $instructorSignature }}" alt="Firma Instructor" width="25%">
                                    @endif
                                </div>
                                <hr style="width: 72%; margin: 1.5mm auto 0; margin-bottom: 5mm;">
                                {{-- <div class="sig-line"></div> --}}
                                <div class="sig-name">{{ $instructor->names }}</div>
                                <div class="sig-role">{{ $instructor->profession }}</div>
                                @if ($instructor->colegial_type && $instructor->colegial)
                                    <div class="sig-cred">{{ $instructor->colegial_type }}:
                                        {{ $instructor->colegial }}</div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>{{-- /.cert-wrap --}}
</body>

</html>
