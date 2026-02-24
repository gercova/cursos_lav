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
        $managerSignature   = storage_path('app/public/signature-photos/francisco-llactas-flores.png');
        $instructor         = User::where('id', $certificate->course->instructor->id)->first();

        if($instructor->names == 'Erick B. Ruiz Odicio'){
            $instructorSignature = storage_path('app/public/signature-photos/erick-b-ruiz-odicio.png');
        } elseif ($instructor->names == 'Jhon L. Ramirez Cueva') {
            $instructorSignature = storage_path('app/public/signature-photos/jhon-l-cueva-ramirez.png');
        }

        // Configurar PDF con DomPDF
        $pdf = Pdf::loadView('student.certificates.pdf_exacto', compact('certificate', 'enterprise', 'logoPath', 'instructor', 'managerSignature', 'instructorSignature'))
            ->setPaper('A4', 'landscape')
            ->setOptions([
                'isRemoteEnabled'       => true,
                'isHtml5ParserEnabled'  => true,
                'chroot'                => base_path(),

            ]);
        // Mostrar en el navegador
        return $pdf->stream('certificado-' . $certificate->certificate_code . '.pdf');
    }
}
