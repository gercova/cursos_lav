<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model {

    use HasFactory;
    protected $table        = 'lesson_progress';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'user_id',
        'lesson_id',
        'enrollment_id',
        'completed',
        'progress',
        'time_watched',
        'completed_at'
    ];

    protected $casts = [
        'completed'     => 'boolean',
        'completed_at'  => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function lesson(): BelongsTo {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');

    }

    public function enrollment(): BelongsTo {
        return $this->belongsTo(Enrollment::class, 'enrollment_id', 'id');
    }
}
