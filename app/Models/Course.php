<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;
    protected $table        = 'courses';
    protected $primaryKey   = 'id';
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'price',
        'promotion_price',
        'category_id',
        'instructor_id',
        'level',
        'duration',
        'is_active',
        'requirements',
        'what_you_learn',
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

    public function sections(): HasMany {
        return $this->hasMany(CourseSection::class)->orderBy('order');
    }

    public function enrollments(): HasMany {
        return $this->hasMany(Enrollment::class);
    }

    public function documents(): HasMany {
        return $this->hasMany(Document::class);
    }

    public function exam(): HasMany {
        return $this->hasMany(Exam::class);
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
}
