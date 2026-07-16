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
        'enrolled_at',
        'completed_at',
        'progress',
        'status',
        'source',
    ];

    protected $casts = [
        'last_accessed_at'  => 'datetime',
        'enrolled_at'       => 'datetime',
        'completed_at'      => 'datetime',
        'progress'          => 'decimal:2',
        'source'            => 'string',
    ];

    // ── Scopes ───────────────────────────────────────────────────────────────

    /**
     * Solo inscripciones de origen directo (compra, admin, código).
     * Estas son las que aparecen en /mis-cursos.
     */
    public function scopeDirect($query)
    {
        return $query->where('source', 'direct');
    }

    /**
     * Solo inscripciones creadas automáticamente desde el cronograma de empresa.
     * Estas se gestionan desde /cronograma.
     */
    public function scopeFromSchedule($query)
    {
        return $query->where('source', 'schedule');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id')->where('type', 'course');
    }

    public function package(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id')->where('type', 'package');
    }

    public function completedLessons() {
        return $this->hasMany(CompletedLessons::class, 'enrollment_id', 'id');
    }

    // public function completedLessons(): BelongsToMany {
    //     return $this->belongsToMany(Lesson::class, 'completed_lessons')
    //         ->withPivot('completed_at', 'time_spent_minutes')
    //         ->withTimestamps();
    // }

    public function completedDocuments(): BelongsToMany {
        return $this->belongsToMany(Document::class, 'completed_documents')
            ->withPivot('completed_at', 'time_spent_minutes')
            ->withTimestamps();
    }
}
