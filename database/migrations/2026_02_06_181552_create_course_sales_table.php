<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Estudiante que promocionó
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade'); // Estudiante que compró
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('enrollment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('promotion_code')->nullable(); // Código usado
            $table->decimal('commission_amount', 10, 2)->default(0); // Comisión ganada
            $table->decimal('sale_amount', 10, 2); // Monto de la venta
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('sold_at');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('promotion_code');
            $table->index('sold_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_sales');
    }
};
