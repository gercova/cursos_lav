<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;
    protected $table    = 'order_items';
    protected $fillable = [
        'order_id',
        'course_id',
        'course_title',
        'course_image',
        'price',
        'promotion_price',
        'final_price'
    ];

    protected $casts = [
        'price'             => 'decimal:2',
        'promotion_price'   => 'decimal:2',
        'final_price'       => 'decimal:2'
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }
}
