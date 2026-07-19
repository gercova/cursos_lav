<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Enterprise;
use App\Models\User;
use App\Models\UserSignature;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CertificatesController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back'])->except('verify');
    }

    public function index() {
        $certificates = Certificate::with('course')->where('user_id', Auth::id())->orderBy('issue_date', 'desc')->paginate(10);
        return view('student.certificates.index', compact('certificates'));
    }

    public function show($certificateId): View {
        $enterprise     = Enterprise::first();
        $certificate    = Certificate::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($certificateId);
        return view('student.certificates.show', compact('certificate', 'enterprise'));
    }

    public function download($certificateId) {
        $certificate    = Certificate::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($certificateId);
        $enterprise     = Enterprise::first();
        $logoPath       = storage_path('app/public/ipf-logo.png');

        $instructor     = User::where('id', $certificate->course->instructor->id)->first();

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
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);

        $mpdf->WriteHTML($html);
        $fileName = 'certificado-' . $certificate->certificate_code . '.pdf';

        return response($mpdf->Output($fileName, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    public function viewExact($certificateId) {
        $certificate    = Certificate::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($certificateId);
        $enterprise     = Enterprise::first();
        $logoPath       = storage_path('app/public/ipf-logo.png');

        $instructor     = User::where('id', $certificate->course->instructor->id)->first();

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

    public function generateCertificate($enrollmentId) {
        $enrollment = Enrollment::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($enrollmentId);

        // Verificar si ya existe un certificado
        $existingCertificate = Certificate::where('user_id', Auth::id())->where('course_id', $enrollment->course_id)->first();

        if ($existingCertificate) {
            return redirect()->route('student.certificates.show', $existingCertificate->id)->with('info', 'Ya tienes un certificado para este curso.');
        }

        // Crear nuevo certificado
        $certificate = Certificate::create([
            'user_id'               => Auth::id(),
            'course_id'             => $enrollment->course_id,
            'certificate_code'      => Certificate::generateVerificationCode(),
            'certificate_number'    => Certificate::generateCertificateNumber(),
            'issue_date'            => now(),
            'total_hours'           => $enrollment->course->duration ?? 4.0,
        ]);

        return redirect()->route('student.certificates.show', $certificate->id)->with('success', 'Certificado generado exitosamente.');
    }

    public function verify($code): View {
        $certificate    = Certificate::with(['user', 'course'])->where('certificate_code', $code)->first();
        $enterprise     = Enterprise::first();

        if (!$certificate) {
            return view('student.certificates.verify', [
                'enterprise'    => $enterprise,
                'valid'         => false,
                'message'       => 'Certificado no encontrado o código inválido'
            ]);
        }

        if ($certificate->expiry_date && $certificate->expiry_date->isPast()) {
            return view('student.certificates.verify', [
                'enterprise'    => $enterprise,
                'valid'         => false,
                'message'       => 'Certificado expirado'
            ]);
        }

        return view('student.certificates.verify', [
            'enterprise'        => $enterprise,
            'valid'             => true,
            'certificate'       => $certificate,
            'enterprise'        => $enterprise,
            'verification_date' => now()->format('d/m/Y H:i:s')
        ]);
    }
}
