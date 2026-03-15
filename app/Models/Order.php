<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    protected $table        = 'orders';
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    protected $fillable     = [
        'order_number',
        'user_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'currency',
        'status',
        'billing_info',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'billing_info'  => 'array',
        'subtotal'      => 'decimal:2',
        'tax'           => 'decimal:2',
        'discount'      => 'decimal:2',
        'total'         => 'decimal:2',
        // 'created_at'    => 'datetime',
        // 'updated_at'    => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne {
        return $this->hasOne(Payment::class);
    }

    public function enrollments(): HasMany {
        return $this->hasMany(Enrollment::class);
    }

    // protected static function boot(){
    //     parent::boot();

    //     static::creating(function ($order) {
    //         $date           = Carbon::now()->format('Ymd'); // Ejemplo: 20251025
    //         $random         = strtoupper(Str::random(5)); // Ejemplo: XJ829
    //         $orderNumber    = "IPF-{$date}-{$random}";
    //         $order->order_number = $orderNumber;
    //     });
    // }
}
