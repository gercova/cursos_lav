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
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
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
        $managerSignature = storage_path('app/public/signature-photos/francisco-llactas-flores.png');

        $instructor     = User::where('id', $certificate->course->instructor->id)->first();

        if($instructor->names == 'Erick B. Ruiz Odicio'){
            $instructorSignature = storage_path('app/public/signature-photos/erick-b-ruiz-odicio.png');
        } elseif ($instructor->names == 'Jhon L. Ramirez Cueva') {
            $instructorSignature = storage_path('app/public/signature-photos/jhon-l-cueva-ramirez.png');
        }

        // Configurar PDF con DomPDF
        $pdf = Pdf::loadView('student.certificates.pdf_exacto', compact('certificate', 'enterprise', 'logoPath', 'instructor', 'managerSignature', 'instructorSignature'))
            ->setPaper('A4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOPtion('enable-local-file-access', true);
        $fileName = 'certificado-' . $certificate->certificate_code . '.pdf';

        // Retornar descarga
        return $pdf->download($fileName);
    }

    public function viewExact($certificateId) {
        $certificate    = Certificate::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($certificateId);
        $enterprise     = Enterprise::first();
        $logoPath       = storage_path('app/public/ipf-logo.png');
        // $managerSignature = storage_path('app/public/signature-photos/francisco-llactas-flores.png');
        // $managerSignature = storage_path('app/'.$enterprise->manager_signature);
        $managerSignature = storage_path('app/public/enterprise/pablo-torres-garcia.png');

        $instructor     = User::where('id', $certificate->course->instructor->id)->first();

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
