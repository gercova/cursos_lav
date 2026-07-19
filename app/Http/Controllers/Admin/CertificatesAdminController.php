<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enterprise;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;

class CertificatesAdminController extends Controller
{
    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    public function show(Certificate $certificate) {
        $enterprise         = Enterprise::first();
        $logoPath           = storage_path('app/public/ipf-logo.png');
        $instructor         = User::where('id', $certificate->course->instructor->id)->first();

        // Obtener firma del instructor de user_signatures
        $instructorSignature = null;
        if ($instructor && $instructor->signature) {
            $rawPath = $instructor->signature->getRawOriginal('signature');
            if ($rawPath) {
                $instructorSignature = storage_path('app/public/' . $rawPath);
            }
        }

        // Fallback si no tiene firma registrada
        if (!$instructorSignature || !file_exists($instructorSignature)) {
            if ($instructor->names == 'Erick B. Ruiz Odicio') {
                $instructorSignature = storage_path('app/public/signature-photos/erick-b-ruiz-odicio.png');
            } elseif ($instructor->names == 'Jhon L. Ramirez Cueva') {
                $instructorSignature = storage_path('app/public/signature-photos/jhon-l-cueva-ramirez.png');
            } else {
                $instructorSignature = storage_path('app/public/instructors/instructor-ipf.png');
            }
        }

        // Obtener firma del gerente/administrador de user_signatures
        $adminUser = User::where('role', 'admin')->first();
        $managerSignature = null;
        if ($adminUser && $adminUser->signature) {
            $rawPath = $adminUser->signature->getRawOriginal('signature');
            if ($rawPath) {
                $managerSignature = storage_path('app/public/' . $rawPath);
            }
        }

        // Fallback si no tiene firma registrada
        if (!$managerSignature || !file_exists($managerSignature)) {
            $managerSignature = storage_path('app/public/enterprise/pablo-torres-garcia.png');
        }

        // Configurar y generar PDF con mPDF
        $html = view('student.certificates.pdf_exacto', compact('certificate', 'enterprise', 'logoPath', 'instructor', 'managerSignature', 'instructorSignature'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4-L',
            'margin_left'   => 0,
            'margin_right'  => 0,
            'margin_top'    => 0,
            'margin_bottom' => 0,
        ]);

        $mpdf->WriteHTML($html);
        $fileName = 'certificado-' . $certificate->certificate_code . '.pdf';

        return response($mpdf->Output($fileName, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }
}
