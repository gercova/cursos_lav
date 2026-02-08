<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model {

    use HasFactory;
    protected $table        = 'enrollments';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'user_id',
        'course_id',
        'payment_id',
        'enrolled_at',
        'completed_at',
        'progress',
        'status',
    ];

    protected $casts = [
        'enrolled_at'   => 'datetime',
        'completed_at'  => 'datetime',
        'progress'      => 'decimal:2',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }

    public function completedLessons(): BelongsToMany {
        return $this->belongsToMany(Lesson::class, 'completed_lessons')
            ->withPivot('completed_at', 'time_spent_minutes')
            ->withTimestamps();
    }

    public function completedDocuments(): BelongsToMany {
        return $this->belongsToMany(Document::class, 'completed_documents')
            ->withPivot('completed_at', 'time_spent_minutes')
            ->withTimestamps();
    }
}
