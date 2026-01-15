<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Payment extends Model
{
    use HasFactory;

    protected $table        = 'payments';
    protected $primaryKey   = 'id';
    protected $fillable = [
        'order_id',
        'user_id',
        'payment_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'payment_details',
        'error_message',
        'paid_at'
    ];

    protected $casts = [
        'payment_details' => 'array',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function enrollment(): HasOneThrough {
        return $this->hasOneThrough(
            Enrollment::class,
            Order::class,
            'id', // Foreign key on orders table
            'id', // Foreign key on enrollments table
            'order_id', // Local key on payments table
            'id' // Local key on orders table
        );
    }

    public function getCoursesAttribute() {
        if ($this->order) {
            return $this->order->items->map(function ($item) {
                return [
                    'title' => $item->course_title,
                    'course' => $item->course
                ];
            });
        }
        return collect();
    }

    public function getFirstCourseAttribute() {
        if ($this->order && $this->order->items->isNotEmpty()) {
            return $this->order->items->first()->course;
        }
        return null;
    }
}
