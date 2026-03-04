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
        Schema::table('users', function (Blueprint $table) {
            // 1. Agregamos la columna (debe ser unsignedBigInteger para matchear con el id)
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');

            // 2. Agregamos la llave foránea recursiva
            $table->foreign('parent_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete(); // Si borras al papu, los hijos se quedan huérfanos (null) en vez de borrarse
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. Primero borramos la foreign key
            $table->dropForeign(['parent_id']);
            
            // 2. Luego borramos la columna
            $table->dropColumn('parent_id');
        });
    }
};
