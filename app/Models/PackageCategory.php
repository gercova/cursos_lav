<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageCategory extends Model
{
    use HasFactory;

    protected $table        = 'package_category';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'course_id',
        'category_id',
        'max_courses_per_category',
    ];

    protected $casts = [
        'max_courses_per_category'  => 'integer',
        'created_at'                => 'datetime',
        'updated_at'                => 'datetime'
    ];

    /**
     * Obtener el paquete al que pertenece esta relación.
     */
    public function package(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /**
     * Obtener la categoría asociada.
     */
    public function category(): BelongsTo {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Scope para filtrar por paquete.
     */
    public function scopeByPackage($query, $courseId) {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope para filtrar por categoría.
     */
    public function scopeByCategory($query, $categoryId) {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Verificar si la categoría tiene límite de cursos.
     */
    public function getHasLimitAttribute(): bool {
        return !is_null($this->max_courses_per_category) && $this->max_courses_per_category > 0;
    }

    /**
     * Obtener el límite de cursos (texto formateado).
     */
    public function getLimitFormattedAttribute(): string {
        if ($this->has_limit) {
            return "Máximo {$this->max_courses_per_category} ". ($this->max_courses_per_category === 1 ? 'curso' : 'cursos');
        }
        
        return 'Sin límite';
    }

}
