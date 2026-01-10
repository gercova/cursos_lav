<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'exam_attempt_id',
        'certificate_code',
        'certificate_number',
        'issue_date',
        'expiry_date',
        'total_hours',
        'download_count',
        'total_hours',
    ];

    protected $casts = [
        'issue_date'    => 'datetime',
        'expiry_date'   => 'datetime',
        'total_hours'   => 'decimal:1',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function examAttempt(): BelongsTo {
        return $this->belongsTo(ExamAttempt::class);
    }

    public static function generateCertificateNumber($year = null) {
        $year = $year ?? date('Y');

        // Contar certificados del año actual
        $count = self::whereYear('issue_date', $year)->count() + 1;

        // Formato: 000X-YYYY-IPF-EDUCA
        return str_pad($count, 4, '0', STR_PAD_LEFT) . '-' . $year . '-IPF-EDUCA';
    }

    public function getFormattedCertificateNumber() {
        return $this->certificate_number ?? self::generateCertificateNumber($this->issue_date?->year);
    }

    public function getQrCode($size = 150) {
        $verificationUrl = $this->verification_url ?? url('/verify-certificate/' . $this->certificate_code);

        return QrCode::size($size)->format('png')->generate($verificationUrl);
    }

    public function getQrCodeBase64($size = 150) {
        $qrCode = $this->getQrCode($size);
        return 'data:image/png;base64,' . base64_encode($qrCode);
    }

    public static function generateVerificationCode() {
        return 'CERT-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }

    public function getVerificationUrlAttribute() {
        return url('/verify-certificate/' . $this->certificate_code);
    }
}
