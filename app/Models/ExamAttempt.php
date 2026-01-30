<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamAttempt extends Model {

    use HasFactory;
    protected $table        = 'exam_attempts';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'exam_id',
        'user_id',
        'score',
        'total_points',
        'passed',
        'started_at',
        'completed_at',
        'answers',
        'attempt_number',
    ];

    protected $casts = [
        'passed'        => 'boolean',
        'started_at'    => 'datetime',
        'completed_at'  => 'datetime',
        'answers'       => 'array',
    ];

    public function exam(): BelongsTo {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function certificate(): HasOne {
        return $this->hasOne(Certificate::class);
    }

    // public function getTimeRemainingAttribute() {
    //     if (!$this->started_at || $this->completed_at) {
    //         return 0;
    //     }

    //     $endTime = $this->started_at->addMinutes($this->exam->duration);
    //     return now()->diffInSeconds($endTime, false);
    // }

    // public function isExpired() {
    //     return $this->time_remaining <= 0;
    // }

    public function getTimeRemainingAttribute() {
        if (!$this->started_at || $this->completed_at) {
            return 0;
        }

        $endTime = $this->started_at->addMinutes($this->exam->duration);
        $remaining = now()->diffInSeconds($endTime, false);

        // Asegurar que no sea negativo
        return max(0, $remaining);
    }

    public function isExpired() {
        return $this->time_remaining <= 0;
    }

    // Agregar método para verificar si puede ser completado
    public function canBeSubmitted() {
        if ($this->completed_at) {
            return false; // Ya está completado
        }

        if ($this->isExpired()) {
            return false; // Tiempo expirado
        }

        return true;
    }
}
