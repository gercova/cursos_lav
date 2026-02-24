<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id', 'id');
    }

    public static function generateCertificateNumber($year = null) {
        $year = $year ?? date('Y');

        $count = self::whereYear('issue_date', $year)->count() + 1;
        return str_pad($count, 4, '0', STR_PAD_LEFT) . '-' . $year . '-IPF-EDUCA';
    }

    public function getFormattedCertificateNumber() {
        return $this->certificate_number ?? self::generateCertificateNumber($this->issue_date?->year);
    }

    public static function generateVerificationCode() {
        return 'CERT-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }

    // Accessor para verification_url (no se guarda en BD)
    public function getVerificationUrlAttribute() {
        return url('/verify/' . $this->certificate_code); // Cambiado a ruta más simple
    }

    // En el modelo Certificate, agrega este método
    public function getFormattedIssueDate() {
        // Traducir meses al español
        $months = [
            'January'   => 'enero',
            'February'  => 'febrero',
            'March'     => 'marzo',
            'April'     => 'abril',
            'May'       => 'mayo',
            'June'      => 'junio',
            'July'      => 'julio',
            'August'    => 'agosto',
            'September' => 'septiembre',
            'October'   => 'octubre',
            'November'  => 'noviembre',
            'December'  => 'diciembre'
        ];

        $month = $months[$this->issue_date->format('F')];
        return $this->issue_date->format('d') . ' de ' . $month . ' del ' . $this->issue_date->format('Y');
    }
}
