<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $tables       = 'carts';
    protected $primaryKey   = 'id';
    protected $fillable = ['user_id', 'course_id'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    // Calcular el total del carrito
    public static function getTotal($userId) {
        $cartItems = self::where('user_id', $userId)
            ->with('course')
            ->get();

        return $cartItems->sum(function ($item) {
            return $item->course->promotion_price ?? $item->course->price;
        });
    }

    // Obtener los items del carrito con detalles
    public static function getItems($userId) {
        return self::where('user_id', $userId)
            ->with(['course' => function ($query) {
                $query->with('category', 'instructor');
            }])
            ->get();
    }

    // Limpiar el carrito después del pago
    public static function clear($userId) {
        return self::where('user_id', $userId)->delete();
    }
}
