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
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('type', ['course', 'package'])->default('course')->after('category_id');

            $table->unsignedInteger('seats')
                ->comment('cantidad de usuarios que incluye el paquete')
                ->default(20)
                ->after('promotion_price');

            $table->unsignedBigInteger('parent_id')
                ->nullable()
                ->after('what_you_learn');

            $table->foreign('parent_id')
                ->references('id')->on('courses')
                ->nullOnDelete();

            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['seats', 'type', 'parent_id']);
        });
    }
};
