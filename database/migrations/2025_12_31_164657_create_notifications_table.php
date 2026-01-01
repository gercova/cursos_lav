<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'new_course', 'payment_pending', 'exam_pending', 'course_completed', etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Datos adicionales en JSON
            $table->string('link')->nullable(); // Enlace relacionado
            $table->string('icon')->default('bell');
            $table->string('color')->default('blue');
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('notifications');
    }
};
