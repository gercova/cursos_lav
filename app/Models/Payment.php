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
}
