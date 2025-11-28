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
        'issue_date',
        'expiry_date',
        'verification_url',
        'download_count',
    ];

    protected $casts = [
        'issue_date'    => 'datetime',
        'expiry_date'   => 'datetime',
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

    public static function generateVerificationCode() {
        return 'CERT-' . strtoupper(uniqid()) . '-' . date('Ymd');
    }

    public function getVerificationUrlAttribute() {
        return url('/verify-certificate/' . $this->certificate_code);
    }
}
