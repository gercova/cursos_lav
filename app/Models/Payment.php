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
    protected $fillable     = [
        'enrollment_id',
        'transaction_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'paid_at',
        'payment_details',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'paid_at'           => 'datetime',
        'payment_details'   => 'array',
    ];

    public function enrollment(): BelongsTo {
        return $this->belongsTo(Enrollment::class);
    }

    public function user(): HasOneThrough {
        return $this->hasOneThrough(User::class, Enrollment::class);
    }
}
