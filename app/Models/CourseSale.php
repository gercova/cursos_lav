<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSale extends Model {
    use HasFactory;
    protected $table        = 'course_sales';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'user_id',
        'buyer_id',
        'course_id',
        'order_id',
        'enrollment_id',
        'promotion_code',
        'commission_amount',
        'sale_amount',
        'status',
        'sold_at'
    ];

    protected $casts = [
        'commission_amount' => 'decimal:2',
        'sale_amount'       => 'decimal:2',
        'sold_at'           => 'datetime',
    ];

    /**
     * Usuario que promocionó (afiliado)
     */
    public function affiliate(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Usuario que compró
     */
    public function buyer(): BelongsTo {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Curso vendido
     */
    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    /**
     * Orden asociada
     */
    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    /**
     * Inscripción generada
     */
    public function enrollment(): BelongsTo {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Marcar venta como completada
     */
    public function markAsCompleted(): void {
        $this->status = 'completed';
        $this->save();
        
        // Actualizar contador del afiliado
        if ($this->affiliate) {
            $this->affiliate->incrementSalesCount();
            $this->affiliate->addCommission($this->commission_amount);
        }
    }
}
