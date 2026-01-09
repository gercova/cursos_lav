<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompletedLessons extends Model
{
    use HasFactory;
    protected $table = 'completed_lessons';
    protected $primaryKey = 'id';
    protected $fillable = [
        'enrollment_id',
        'lesson_id',
        'completed_at',
        'time_spent_minutes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function enrollment(): BelongsTo {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }

    public function lesson(): BelongsTo {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }
}
