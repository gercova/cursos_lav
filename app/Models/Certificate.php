<?php

namespace App\Models;

use Carbon\Carbon;
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
        'expiration_date',
        'total_hours',
        'download_count',
    ];

    protected $casts = [
        'issue_date'    => 'datetime',
        'expiry_date'   => 'datetime',
        'total_hours'   => 'decimal:1',
    ];

    protected $appends = [
        'verification_url',
        'expiration_date',
        'is_expired',
        'is_valid',
    ];

    protected static function booted()
    {
        static::creating(function (Certificate $certificate) {
            if (empty($certificate->issue_date)) {
                $certificate->issue_date = now();
            }

            if (empty($certificate->expiry_date)) {
                $issueDate = $certificate->issue_date instanceof \Carbon\CarbonInterface
                    ? $certificate->issue_date
                    : Carbon::parse($certificate->issue_date);
                $certificate->expiry_date = $issueDate->copy()->addYear();
            }
        });
    }

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

    public function getExpiryDateAttribute($value)
    {
        if ($value) {
            return $this->asDateTime($value);
        }

        if ($this->issue_date) {
            return $this->asDateTime($this->issue_date)->copy()->addYear();
        }

        return null;
    }

    public function getExpirationDateAttribute()
    {
        return $this->expiry_date;
    }

    public function setExpirationDateAttribute($value)
    {
        $this->attributes['expiry_date'] = $value;
    }

    public function isExpired(): bool
    {
        return (bool) ($this->expiry_date && $this->expiry_date->isPast());
    }

    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->isExpired();
    }

    public function getIsValidAttribute(): bool
    {
        return $this->isValid();
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }

        return (int) now()->diffInDays($this->expiry_date, false);
    }

    // En el modelo Certificate, formatea la fecha de emisión en español
    public function getFormattedIssueDate() {
        if (!$this->issue_date) {
            return '';
        }

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

        $monthName = $this->issue_date->format('F');
        $month = $months[$monthName] ?? $monthName;
        return $this->issue_date->format('d') . ' de ' . $month . ' del ' . $this->issue_date->format('Y');
    }

    // Formatea la fecha de expiración en español
    public function getFormattedExpiryDate(): ?string
    {
        if (!$this->expiry_date) {
            return null;
        }

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

        $monthName = $this->expiry_date->format('F');
        $month = $months[$monthName] ?? $monthName;
        return $this->expiry_date->format('d') . ' de ' . $month . ' del ' . $this->expiry_date->format('Y');
    }
}
