<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';
    protected $fillable = [
        'name',
        'slug',
        'meta_description',
        'meta_keywords',
        'description',
        'price',
        'promotion_price',
        'seats',
        'is_active',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'is_active'     => 'boolean'
    ];

    // Relación con cursos específicos
    public function courses(): BelongsToMany {
        return $this->belongsToMany(Course::class, 'package_course')
            ->withPivot('sessions_per_course')
            ->withTimestamps();
    }

    // Relación con categorías
    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class, 'package_category')
            ->withPivot('max_courses_per_category')
            ->withTimestamps();
    }

    // Obtener todos los cursos disponibles en el paquete
    public function getAllCoursesAttribute() {
        // Cursos específicos del paquete
        $specificCourses = $this->courses;
        
        // Cursos de las categorías incluidas
        $categoryCourses = collect();
        foreach ($this->categories as $category) {
            $categoryCourses = $categoryCourses->merge(
                $category->courses()->where('is_active', true)->get()
            );
        }
        
        return $specificCourses->merge($categoryCourses)->unique('id');
    }

    // Obtener el precio por persona
    public function getPricePerPersonAttribute(): float {
        return $this->price / $this->max_participants;
    }
}
