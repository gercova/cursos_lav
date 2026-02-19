<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageCourse extends Model
{
    use HasFactory;

    protected $table        = 'package_courses';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'package_id',
        'course_id',
        'quantity',      // Este es el campo correcto en tu tabla
        'sort_order',
    ];

    protected $casts = [
        'quantity'    => 'integer',
        'sort_order'  => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime'
    ];

    public function package(): BelongsTo {
        return $this->belongsTo(Course::class, 'package_id', 'id');
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function scopeByPackage($query, $packageId) {
        return $query->where('package_id', $packageId);
    }

    /**
     * Scope para filtrar por curso.
     */
    public function scopeByCourse($query, $courseId) {
        return $query->where('course_id', $courseId);
    }

    /**
     * CORREGIDO: Usar 'quantity' en lugar de 'sessions_per_course'
     */
    public function getHasQuantityAttribute(): bool {
        return !is_null($this->quantity) && $this->quantity > 0;
    }

    /**
     * CORREGIDO: Usar 'quantity' en lugar de 'sessions_per_course'
     */
    public function getQuantityTextAttribute(): string {
        if ($this->has_quantity) {
            return "{$this->quantity} " . ($this->quantity === 1 ? 'sesión' : 'sesiones');
        }
        
        return 'Sesiones no definidas';
    }

    /**
     * NUEVO: Obtener el curso con formato para selects
     */
    public function getCourseOptionAttribute(): array {
        return [
            'id'    => $this->course_id,
            'text'  => $this->course ? $this->course->title : 'Curso no encontrado'
        ];
    }
}