<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Agrega el campo 'source' a la tabla enrollments.
     *
     * Valores posibles:
     *  - 'direct'   → inscripción por compra, código de acceso, admin u otro medio directo.
     *  - 'schedule' → inscripción creada automáticamente al acceder a un curso
     *                 programado en el cronograma de empresa (/cronograma).
     *
     * El default es 'direct' para que todos los registros existentes queden
     * correctamente etiquetados sin necesidad de backfill.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->enum('source', ['direct', 'schedule'])
                  ->default('direct')
                  ->after('status')
                  ->comment('Origen de la inscripción: direct = compra/admin, schedule = cronograma empresa');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
