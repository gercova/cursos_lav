<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseSale;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseSale>
 */
class CourseSaleFactory extends Factory {

    public function __construct(){
        
    }

    protected $model = CourseSale::class;
    
    public function definition(): array {
        // Primero, aseguramos que existan usuarios con y sin código
        $userWithCode       = User::whereNotNull('code')->inRandomOrder()->first();
        $userWithoutCode    = User::whereNull('code')->inRandomOrder()->first();
        
        // Si no existen, los creamos
        // if (!$userWithCode) {
        //     $userWithCode = User::factory()->create(['code' => $this->faker->unique()->regexify('[A-Z0-9]{8}')]);
        // }
        
        // if (!$userWithoutCode) {
        //     $userWithoutCode = User::factory()->create(['code' => null]);
        // }
        
        // Buscar o crear una orden con items
        $order = Order::inRandomOrder()->first();
        if (!$order) {
            $order = $this->createOrderWithItems($userWithoutCode);
        }
        
        // Buscar o crear un order item
        $orderItem = OrderItem::where('order_id', $order->id)->inRandomOrder()->first();
        if (!$orderItem) {
            $orderItem = $this->createOrderItem($order);
        }
        
        // Asegurar que el order item tiene un curso
        $course = $orderItem->course()->first();
        if (!$course) {
            $course = \App\Models\Course::factory()->create();
            $orderItem->course_id = $course->id;
            $orderItem->save();
        }
        
        // Buscar o crear una inscripción
        $enrollment = Enrollment::where('user_id', $userWithoutCode->id)
            ->where('course_id', $course->id)
            ->first();
            
        if (!$enrollment) {
            $enrollment = Enrollment::factory()->create([
                'user_id'       => $userWithoutCode->id,
                'course_id'     => $course->id,
                'enrolled_at'   => now(),
            ]);
        }
        
        // Calcular comisión (20% del precio final)
        $saleAmount         = $course->final_price ?? $course->price;
        $commissionAmount   = round($saleAmount * 0.20, 2);
        
        return [
            'user_id'           => $userWithCode->id,
            'buyer_id'          => $userWithoutCode->id,
            'course_id'         => $course->id,
            'order_id'          => $order->id,
            'enrollment_id'     => $enrollment->id,
            'promotion_code'    => $userWithCode->code,
            'commission_amount' => $commissionAmount,
            'sale_amount'       => $saleAmount,
            'status'            => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'sold_at'           => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
    
    /**
     * Crea una orden con items para un usuario
     */
    private function createOrderWithItems(User $user): Order {
        $order = Order::factory()->create([
            'user_id'   => $user->id,
            'subtotal'  => 0,
            'total'     => 0,
        ]);
        
        // Crear al menos un item
        $this->createOrderItem($order);
        
        // Actualizar totales de la orden
        $subtotal = $order->items()->sum('final_price');
        $order->update([
            'subtotal'  => $subtotal,
            'total'     => $subtotal,
        ]);
        
        return $order;
    }
    
    /**
     * Crea un item de orden
     */
    private function createOrderItem(Order $order): OrderItem {
        $course     = Course::factory()->create();
        $finalPrice = $course->promotion_price ?? $course->price;
        
        return OrderItem::factory()->create([
            'order_id'          => $order->id,
            'course_id'         => $course->id,
            'course_title'      => $course->title,
            'price'             => $course->price,
            'promotion_price'   => $course->promotion_price,
            'final_price'       => $finalPrice,
        ]);
    }
    
    /**
     * Estado: venta completada
     */
    public function completed(): static {
        return $this->state(function (array $attributes) {
            return [
                'status'    => 'completed',
                'sold_at'   => now()->subDays(rand(1, 30)),
            ];
        });
    }
    
    /**
     * Estado: venta pendiente
     */
    public function pending(): static {
        return $this->state(function (array $attributes) {
            return [
                'status'    => 'pending',
                'sold_at'   => now(),
            ];
        });
    }
    
    /**
     * Estado: venta cancelada
     */
    public function cancelled(): static {
        return $this->state(function (array $attributes) {
            return [
                'status'    => 'cancelled',
                'sold_at'   => now()->subDays(rand(1, 10)),
            ];
        });
    }
    
    /**
     * Con comisión específica
     */
    public function withCommission(float $percentage): static {
        return $this->state(function (array $attributes) use ($percentage) {
            $saleAmount = $attributes['sale_amount'] ?? 100;
            return [
                'commission_amount' => round($saleAmount * ($percentage / 100), 2),
            ];
        });
    }
    
    /**
     * Para un afiliado específico
     */
    public function forAffiliate(User $affiliate): static {
        return $this->state(function (array $attributes) use ($affiliate) {
            return [
                'user_id'           => $affiliate->id,
                'promotion_code'    => $affiliate->code,
            ];
        });
    }
}