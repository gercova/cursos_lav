<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificatesController extends Controller
{
    public function show($certificateId): View {
        $certificate = Certificate::with(['user', 'course'])
            ->where('user_id', Auth::id())
            ->findOrFail($certificateId);

        return view('student.certificate.show', compact('certificate'));
    }

    public function download($certificateId)
    {
        $certificate = Certificate::with(['user', 'course'])
            ->where('user_id', Auth::id())
            ->findOrFail($certificateId);

        // Incrementar contador de descargas
        $certificate->increment('download_count');

        $pdf = PDF::loadView('student.certificate.pdf', compact('certificate'));

        return $pdf->download("certificado-{$certificate->course->title}.pdf");
    }

    public function verify($code)
    {
        $certificate = Certificate::with(['user', 'course'])
            ->where('certificate_code', $code)
            ->first();

        if (!$certificate) {
            return view('student.certificate.verify', [
                'valid' => false,
                'message' => 'Certificado no encontrado'
            ]);
        }

        if ($certificate->expiry_date && $certificate->expiry_date->isPast()) {
            return view('student.certificate.verify', [
                'valid' => false,
                'message' => 'Certificado expirado'
            ]);
        }

        return view('student.certificate.verify', [
            'valid' => true,
            'certificate' => $certificate
        ]);
    }
}
