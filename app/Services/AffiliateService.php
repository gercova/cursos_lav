<?php

namespace App\Services;

use App\Models\CourseSale;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AffiliateService {
    
    /**
     * Registrar una venta realizada con código de afiliado
     */
    public static function registerSale(Order $order, Enrollment $enrollment, ?string $promotionCode = null): ?CourseSale {
        // Buscar si el código de promoción pertenece a un usuario
        if ($promotionCode) {
            $affiliate = User::where('code', $promotionCode)->first();
            
            if ($affiliate && $affiliate->id !== $order->user_id) {
                // Buscar el item del curso en la orden
                $orderItem = OrderItem::where('order_id', $order->id)->where('course_id', $enrollment->course_id)->first();
                
                if ($orderItem) {
                    // Calcular comisión (10% del precio final)
                    $commission = $orderItem->final_price * 0.10;
                    
                    try {
                        DB::beginTransaction();
                        
                        $sale = CourseSale::create([
                            'user_id'           => $affiliate->id,
                            'buyer_id'          => $order->user_id,
                            'course_id'         => $enrollment->course_id,
                            'order_id'          => $order->id,
                            'enrollment_id'     => $enrollment->id,
                            'promotion_code'    => $promotionCode,
                            'commission_amount' => $commission,
                            'sale_amount'       => $orderItem->final_price,
                            'status'            => $order->status === 'completed' ? 'completed' : 'pending',
                            'sold_at'           => now(),
                        ]);
                        
                        // Si la orden está completada, actualizar contador del afiliado
                        if ($order->status === 'completed') {
                            $sale->markAsCompleted();
                        }
                        
                        DB::commit();
                        return $sale;
                        
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Error al registrar venta de afiliado: ' . $e->getMessage());
                    }
                }
            }
        }
        
        return null;
    }

    /**
     * Actualizar estado de ventas cuando una orden se completa
     */
    public static function updateSalesOnOrderCompletion(Order $order): void {
        $sales = CourseSale::where('order_id', $order->id)->where('status', 'pending')->get();
        foreach ($sales as $sale) {
            $sale->markAsCompleted();
        }
    }

    /**
     * Obtener estadísticas de afiliado
     */
    public static function getAffiliateStats(User $user): array {
        return [
            'total_sales'       => $user->courses_sold_count ?? 0,
            'total_commission'  => $user->total_commission ?? 0,
            'pending_payout'    => CourseSale::where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('commission_amount'),
            'this_month_sales'  => CourseSale::where('user_id', $user->id)
                ->whereMonth('sold_at', now()->month)
                ->whereYear('sold_at', now()->year)
                ->count(),
        ];
    }
}