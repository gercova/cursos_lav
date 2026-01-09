<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CertificatesController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function index() {
        $certificates = Certificate::with('course')->where('user_id', Auth::id())->orderBy('issue_date', 'desc')->paginate(10);
        return view('student.certificates.index', compact('certificates'));
    }

    public function show($certificateId): View {
        $certificate = Certificate::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($certificateId);
        return view('student.certificate.show', compact('certificate'));
    }

    public function download($certificateId) {
        $certificate = Certificate::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($certificateId);
        // Incrementar contador de descargas
        $certificate->increment('download_count');

        $pdf = PDF::loadView('student.certificate.pdf', compact('certificate'))->setPaper('a4', 'portrait')->setOption('defaultFont', 'Times New Roman');
        $slug = Str::slug($certificate->course->title);

        $fileName = 'certificado-'.$slug.'.pdf';

        return $pdf->download($fileName);
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
            'verification_url'      => url('/verify-certificate/'.Certificate::generateVerificationCode()),
        ]);

        return redirect()->route('student.certificates.show', $certificate->id)->with('success', 'Certificado generado exitosamente.');
    }

    public function verify($code): View {
        $certificate = Certificate::with(['user', 'course'])->where('certificate_code', $code)->first();

        if (!$certificate) {
            return view('student.certificate.verify', [
                'valid'     => false,
                'message'   => 'Certificado no encontrado'
            ]);
        }

        if ($certificate->expiry_date && $certificate->expiry_date->isPast()) {
            return view('student.certificate.verify', [
                'valid'     => false,
                'message'   => 'Certificado expirado'
            ]);
        }

        return view('student.certificate.verify', [
            'valid'         => true,
            'certificate'   => $certificate
        ]);
    }
}
