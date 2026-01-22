<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $table        = 'exam_questions';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'exam_id',
        'question',
        'type',
        'options',
        'correct_answer',
        'points',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function exam(): BelongsTo {
        return $this->belongsTo(Exam::class);
    }

    public function getOptionsAttribute($value) {
        return is_array($value) ? $value : json_decode($value, true);
    }

    // Agregar accessor para consistentemente obtener correct_answer como string
    public function getCorrectAnswerAttribute($value) {
        if (is_numeric($value)) {
            return (string) $value;
        }
        return $value;
    }
}
