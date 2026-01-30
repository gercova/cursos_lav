<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursePromotionCode extends Model
{
    use HasFactory;
    protected $table        = 'course_promotion_code';
    protected $primaryKey   = 'id';

    protected $fillable     = [
        'course_id',
        'user_id',
        'code',
        'discount_percentage',
        'price',
        'promotion_price',
        'is_active',
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'is_active'     => 'boolean',
    ];

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
