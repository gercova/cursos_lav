<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;
    protected $table        = 'courses';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'title',   
        'slug',
        'meta_description',
        'meta_keywords',
        'description',
        'short_description',
        'learning_outcomes',
        'requirements',
        'what_you_learn',
        'parent_id',
        'image_url',
        'price',
        'promotion_price',
        'seats',
        'category_id', 
        'type',
        'instructor_id',
        'duration',
        'is_active'
    ];

    protected $casts = [
        'price'             => 'decimal:2',
        'promotion_price'   => 'decimal:2',
        'is_active'         => 'boolean',
        'requirements'      => 'array',
        'what_you_learn'    => 'array',
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function instructor(): BelongsTo {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function coursePromotionCode(): HasMany {
        return $this->hasMany(CoursePromotionCode::class, 'course_id', 'id');
    }

    public function sections(): HasMany {
        return $this->hasMany(CourseSection::class)->orderBy('order');
    }

    public function lessons(): HasMany {
        return $this->hasMany(Lesson::class, 'course_id', 'id')->orderBy('order');
    }

    public function enrollments(): HasMany {
        return $this->hasMany(Enrollment::class, 'course_id', 'id');
    }

    public function documents(): HasMany {
        return $this->hasMany(Document::class);
    }

    public function exams(): HasMany {
        return $this->hasMany(Exam::class, 'course_id', 'id');
    }

    public function getIsOnPromotionAttribute(): bool {
        return !is_null($this->promotion_price) && $this->promotion_price < $this->price;
    }

    public function getFinalPriceAttribute(): float {
        return $this->promotion_price ?? $this->price;
    }

    public function getStudentsCountAttribute(): int {
        return $this->enrollments()->count();
    }

    protected function imageUrl(): Attribute {
        return Attribute::make(
            get: fn (?string $value) => match (true) {
                empty($value) => 'https://images.unsplash.com/photo-1497636577773-f1231844b336?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80',
                Str::startsWith($value, ['http://', 'https://']) => $value,
                default => Storage::url($value),
            }
        );
    }

    public function courses(): BelongsToMany {
        return $this->belongsToMany(Course::class, 'package_courses', 'package_id', 'course_id')
            ->withPivot('quantity', 'sort_order')
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

    /**
     * CORREGIDO: Obtener el precio por persona con validación
     * Cambié max_participants por seats que es el campo real
     * y agregué validación para evitar división por cero
     */
    public function getPricePerPersonAttribute(): float {
        // Validar que seats exista y sea mayor que cero
        if (!$this->seats || $this->seats <= 0) {
            return 0;
        }
        
        return $this->price / $this->seats;
    }

    /**
     * NUEVO: Precio promocional por persona
     */
    public function getPromotionPricePerPersonAttribute(): ?float {
        if (!$this->promotion_price) {
            return null;
        }
        
        if (!$this->seats || $this->seats <= 0) {
            return 0;
        }
        
        return $this->promotion_price / $this->seats;
    }

    /**
     * NUEVO: Método seguro para obtener precio por persona
     */
    public function calculatePricePerPerson($price = null): float {
        $priceToUse = $price ?? $this->price;
        
        if (!$this->seats || $this->seats <= 0) {
            return 0;
        }
        
        return $priceToUse / $this->seats;
    }

    /**
     * Relación con los registros de package_course (para acceso directo).
     */
    public function packageCourses(): HasMany {
        return $this->hasMany(PackageCourse::class, 'course_id', 'id');
    }

    /**
     * Relación con los registros de package_category (para acceso directo).
     */
    public function packageCategories(): HasMany {
        return $this->hasMany(PackageCategory::class, 'course_id', 'id');
    }

    /**
     * Scope para paquetes activos.
     */
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    /**
     * Scope para paquetes en promoción.
     */
    public function scopeOnPromotion($query) {
        return $query->whereNotNull('promotion_price')
            ->whereColumn('promotion_price', '<', 'price')
            ->where('promotion_price', '>', 0);
    }

    /**
     * Obtener la ruta clave para el modelo.
     */
    // public function getRouteKeyName() {
    //     return 'slug';
    // }
}
