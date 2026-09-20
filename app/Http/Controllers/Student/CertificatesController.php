<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Enterprise;
use App\Models\User;
use App\Models\UserSignature;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class CertificatesController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back'])->except('verify');
    }

    public function index() {
        $certificates = Certificate::with('course')->where('user_id', Auth::id())->orderBy('issue_date', 'desc')->paginate(10);
        return view('student.certificates.index', compact('certificates'));
    }

    public function show(int $certificateId): View {
        $enterprise  = Enterprise::first();
        $certificate = Certificate::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($certificateId);
        return view('student.certificates.show', compact('certificate', 'enterprise'));
    }

    public function download(int $certificateId) {
        $certificate = Certificate::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($certificateId);
        $enterprise  = Enterprise::first();
        $logoPath    = storage_path('app/public/ipf-logo.png');

        $instructor = User::where('id', $certificate->course->instructor->id)->first();

        // Firma del instructor — fuente dinámica: UserSignature (siempre fresca desde BD)
        $instructorSignature = $this->resolveSignaturePath(
            $instructor?->id,
            storage_path('app/public/instructors/instructor-ipf.png')
        );

        // Firma del gerente/administrador — fuente dinámica: UserSignature
        $adminUser        = User::where('role', 'admin')->first();
        $managerSignature = $this->resolveSignaturePath(
            $adminUser?->id,
            storage_path('app/public/enterprise/pablo-torres-garcia.png')
        );

        $html = view('student.certificates.pdf_exacto', compact(
            'certificate', 'enterprise', 'logoPath',
            'instructor', 'managerSignature', 'instructorSignature'
        ))->render();

        $mpdf = new Mpdf([
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
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function viewExact(int $certificateId) {
        $certificate = Certificate::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($certificateId);
        $enterprise  = Enterprise::first();
        $logoPath    = storage_path('app/public/ipf-logo.png');

        $instructor = User::where('id', $certificate->course->instructor->id)->first();

        // Firma del instructor — fuente dinámica: UserSignature (siempre fresca desde BD)
        $instructorSignature = $this->resolveSignaturePath(
            $instructor?->id,
            storage_path('app/public/instructors/instructor-ipf.png')
        );

        // Firma del gerente/administrador — fuente dinámica: UserSignature
        $adminUser        = User::where('role', 'admin')->first();
        $managerSignature = $this->resolveSignaturePath(
            $adminUser?->id,
            storage_path('app/public/enterprise/pablo-torres-garcia.png')
        );

        $html = view('student.certificates.pdf_exacto', compact(
            'certificate', 'enterprise', 'logoPath',
            'instructor', 'managerSignature', 'instructorSignature'
        ))->render();

        $mpdf = new Mpdf([
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
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function generateCertificate(int $enrollmentId) {
        $enrollment = Enrollment::with(['user', 'course'])->where('user_id', Auth::id())->findOrFail($enrollmentId);

        // Verificar si ya existe un certificado
        $existingCertificate = Certificate::where('user_id', Auth::id())
            ->where('course_id', $enrollment->course_id)
            ->first();

        if ($existingCertificate) {
            return redirect()->route('student.certificates.show', $existingCertificate->id)
                ->with('info', 'Ya tienes un certificado para este curso.');
        }

        // Crear nuevo certificado
        $issueDate = now();
        $certificate = Certificate::create([
            'user_id'            => Auth::id(),
            'course_id'          => $enrollment->course_id,
            'certificate_code'   => Certificate::generateVerificationCode(),
            'certificate_number' => Certificate::generateCertificateNumber(),
            'issue_date'         => $issueDate,
            'expiry_date'        => $issueDate->copy()->addYear(),
            'total_hours'        => $enrollment->course->duration ?? 4.0,
        ]);

        return redirect()->route('student.certificates.show', $certificate->id)
            ->with('success', 'Certificado generado exitosamente.');
    }

    public function verify(?string $code = null): View {
        $searchCode = trim((string) ($code ?: request('code')));
        $enterprise  = Enterprise::first();

        if (empty($searchCode)) {
            return view('student.certificates.verify', [
                'enterprise'        => $enterprise,
                'certificate'       => null,
                'status'            => 'search',
                'valid'             => false,
                'isExpired'         => false,
                'searchCode'        => '',
                'message'           => 'Ingresa un código de verificación para consultar la autenticidad y vigencia del certificado.',
                'verification_date' => now()->format('d/m/Y H:i:s'),
            ]);
        }

        $certificate = Certificate::with(['user', 'course.instructor'])->where('certificate_code', $searchCode)->first();

        if (!$certificate) {
            return view('student.certificates.verify', [
                'enterprise'        => $enterprise,
                'certificate'       => null,
                'status'            => 'not_found',
                'valid'             => false,
                'isExpired'         => false,
                'searchCode'        => $searchCode,
                'message'           => 'Certificado no encontrado o código de verificación inválido.',
                'verification_date' => now()->format('d/m/Y H:i:s'),
            ]);
        }

        // Si no tuviera fecha de expiración, se garantiza el cálculo a 1 año desde emisión
        if (!$certificate->expiry_date && $certificate->issue_date) {
            $certificate->expiry_date = $certificate->issue_date->copy()->addYear();
        }

        $isExpired = $certificate->isExpired();
        $status    = $isExpired ? 'expired' : 'valid';

        return view('student.certificates.verify', [
            'enterprise'        => $enterprise,
            'certificate'       => $certificate,
            'status'            => $status,
            'valid'             => !$isExpired,
            'isExpired'         => $isExpired,
            'searchCode'        => $searchCode,
            'message'           => $isExpired
                ? 'Certificado auténtico pero con vigencia de 1 año expirada.'
                : 'Certificado oficial válido y vigente.',
            'verification_date' => now()->format('d/m/Y H:i:s'),
        ]);
    }

    /**
     * Resuelve la ruta absoluta en disco de la firma de un usuario.
     *
     * Consulta la tabla `user_signatures` directamente para obtener siempre
     * el valor más reciente guardado en BD (fuente dinámica).
     * Si el usuario no tiene registro en esa tabla o el archivo no existe
     * físicamente en disco, retorna $defaultPath como firma de respaldo.
     *
     * @param  int|null  $userId       ID del usuario cuya firma se busca.
     * @param  string    $defaultPath  Ruta absoluta del archivo de firma por defecto.
     * @return string                  Ruta absoluta en disco lista para mPDF.
     */
    private function resolveSignaturePath(?int $userId, string $defaultPath): string
    {
        if ($userId !== null) {
            $signatureRecord = UserSignature::where('user_id', $userId)->latest()->first();

            if ($signatureRecord) {
                $rawPath = $signatureRecord->getRawOriginal('signature');

                if (!empty($rawPath)) {
                    $absolutePath = storage_path('app/public/' . ltrim($rawPath, '/'));

                    if (file_exists($absolutePath)) {
                        return $absolutePath;
                    }
                }
            }
        }

        return $defaultPath;
    }
}
