<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model {

    use HasFactory;
    protected $table        = 'exams';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'course_id',
        'title',
        'description',
        'duration',
        'passing_score',
        'max_attempts',
        'is_active',
    ];

    protected $casts = [
        'duration'  => 'integer',
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function questions(): HasMany {
        return $this->hasMany(ExamQuestion::class, 'exam_id', 'id');
    }

    public function attempts(): HasMany {
        return $this->hasMany(ExamAttempt::class, 'exam_id', 'id');
    }

    public function examAttempts(): HasMany {
        return $this->hasMany(ExamAttempt::class);
    }

    public function getRandomQuestions($limit = null) {
        $limit = $limit ?? $this->questions()->count();
        return $this->questions()->inRandomOrder()->limit($limit)->get();
    }
}
