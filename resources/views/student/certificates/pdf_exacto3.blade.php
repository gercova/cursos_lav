<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <title>Certificado IPF</title>
    <style>
        @page {
            size: 297mm 210mm;
            margin: 0mm;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #ffffff;
        }

        .certificate-border {
            width: 277mm;
            height: 188mm;
            border: 2px solid #111111;
            margin: 10mm;
            box-sizing: border-box;
        }

        .content-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }

        .content-cell {
            padding: 12mm 15mm;
            vertical-align: top;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 45mm;
            vertical-align: top;
            text-align: left;
        }

        .logo-img {
            width: 32mm;
            height: 32mm;
        }

        .title-cell {
            width: 157mm;
            vertical-align: top;
            text-align: center;
            padding-top: 2mm;
        }

        .cert-title {
            font-size: 24pt;
            font-weight: bold;
            color: #111111;
            letter-spacing: 0.5px;
        }

        .cert-granted {
            font-size: 11pt;
            color: #444444;
            margin-top: 4mm;
        }

        .student-name {
            font-size: 24pt;
            font-weight: bold;
            color: #111111;
            margin-top: 3mm;
        }

        .qr-cell {
            width: 45mm;
            vertical-align: top;
            text-align: right;
        }

        .qr-container {
            border: 1px solid #111111;
            padding: 5px;
            width: 28mm;
            height: 28mm;
            display: inline-block;
            text-align: center;
            vertical-align: top;
        }

        .course-section {
            text-align: center;
            margin-top: 10mm;
        }

        .course-desc {
            font-size: 11pt;
            color: #444444;
        }

        .course-name {
            font-size: 20pt;
            font-weight: bold;
            color: #111111;
            margin-top: 4mm;
            line-height: 1.35;
            padding: 0 10mm;
        }

        .details-section {
            text-align: center;
            margin-top: 8mm;
            font-size: 11pt;
            color: #444444;
            line-height: 1.45;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14mm;
        }

        .sig-cell {
            width: 42%;
            text-align: center;
            vertical-align: bottom;
        }

        .spacer-cell {
            width: 16%;
        }

        .sig-img-container {
            height: 18mm;
            text-align: center;
            vertical-align: bottom;
            margin-bottom: 2mm;
        }

        .sig-img {
            height: 16mm;
            max-width: 60mm;
        }

        .sig-line {
            border-top: 1px solid #777777;
            width: 60mm;
            margin: 0 auto;
        }

        .sig-name {
            font-size: 11pt;
            font-weight: bold;
            color: #111111;
            margin-top: 2mm;
        }

        .sig-role {
            font-size: 9.5pt;
            color: #555555;
            margin-top: 1px;
        }

        .sig-credential {
            font-size: 9.5pt;
            color: #555555;
            margin-top: 1px;
        }
    </style>
</head>

<body>
    <div class="certificate-border">
        <table class="content-table">
            <tr>
                <td class="content-cell">
                    <!-- Header -->
                    <table class="header-table">
                        <tr>
                            <td class="logo-cell">
                                @if ($logoPath && file_exists($logoPath))
                                    <img src="{{ $logoPath }}" class="logo-img" alt="Logo">
                                @endif
                            </td>
                            <td class="title-cell">
                                <div class="cert-title">CERTIFICADO DE CAPACITACIÓN</div>
                                <div class="cert-granted">Otorgado por {{ $enterprise->company_name }} a:</div>
                                <div class="student-name">{{ $certificate->user->names }}</div>
                            </td>
                            <td class="qr-cell">
                                <div class="qr-container">
                                    <barcode code="{{ route('verify.certificate', $certificate->certificate_code) }}"
                                        type="QR" size="1.1" error="M" />
                                </div>
                            </td>
                        </tr>
                    </table>

                    <!-- Course Description & Title -->
                    <div class="course-section">
                        <div class="course-desc">Por haber culminado y aprobado con éxito el curso de:</div>
                        <div class="course-name">{{ mb_strtoupper($certificate->course->title, 'UTF-8') }}</div>
                    </div>

                    <!-- Date and Duration -->
                    <div class="details-section">
                        Realizado de manera virtual el {{ $certificate->getFormattedIssueDate() }}<br>
                        con una duración total de {{ (int) round($certificate->total_hours, 0) }} horas lectivas.
                    </div>

                    <!-- Signatures -->
                    <table class="signatures-table">
                        <tr>
                            <!-- Manager Signature -->
                            <td class="sig-cell">
                                <div class="sig-img-container">
                                    @if ($managerSignature && file_exists($managerSignature))
                                        <img src="{{ $managerSignature }}" class="sig-img" alt="Firma Gerente">
                                    @else
                                        <div style="height: 16mm;"></div>
                                    @endif
                                </div>
                                <div class="sig-line"></div>
                                <div class="sig-name">{{ $enterprise->legal_representative }}</div>
                                <div class="sig-role">Gerente de Operaciones</div>
                                @if ($enterprise->colegial_type && $enterprise->colegial)
                                    <div class="sig-credential">{{ $enterprise->colegial_type }}:
                                        {{ $enterprise->colegial }}</div>
                                @endif
                            </td>

                            <!-- Spacer -->
                            <td class="spacer-cell"></td>

                            <!-- Instructor Signature -->
                            <td class="sig-cell">
                                <div class="sig-img-container">
                                    @if ($instructorSignature && file_exists($instructorSignature))
                                        <img src="{{ $instructorSignature }}" class="sig-img" alt="Firma Instructor">
                                    @else
                                        <div style="height: 16mm;"></div>
                                    @endif
                                </div>
                                <div class="sig-line"></div>
                                <div class="sig-name">{{ $instructor->names }}</div>
                                <div class="sig-role">{{ $instructor->profession }}</div>
                                @if ($instructor->colegial_type && $instructor->colegial)
                                    <div class="sig-credential">{{ $instructor->colegial_type }}:
                                        {{ $instructor->colegial }}</div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
