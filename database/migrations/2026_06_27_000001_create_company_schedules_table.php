<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla para el cronograma de capacitaciones por empresa.
     * Permite programar qué cursos deben realizarse en qué mes/año
     * y opcionalmente restringirlo a un código de empresa específico
     * (NULL = aplica a TODAS las cuentas empresa).
     */
    public function up(): void
    {
        Schema::create('company_schedules', function (Blueprint $table) {
            $table->id();

            // El curso programado
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');

            // Mes y año de publicación (1-12 para el mes)
            $table->tinyInteger('month')->unsigned()->comment('1=Enero … 12=Diciembre');
            $table->smallInteger('year')->unsigned()->comment('Año de publicación');

            // Código de empresa (NULL = aplica a TODAS las empresas)
            $table->string('company_code', 50)->nullable()->index();

            // Modalidad y área responsable (como en la imagen de referencia)
            $table->string('modality', 50)->nullable()->comment('Virtual, Presencial, etc.');
            $table->string('responsible_area', 100)->nullable()->comment('SST, ENFERMERO, etc.');
            $table->string('scope', 100)->nullable()->comment('Todos, Personal nuevo, Conductores…');

            // Notas adicionales del administrador
            $table->text('notes')->nullable();

            // Activo / inactivo
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Un curso NO se repite en el mismo mes/año para el mismo código de empresa
            $table->unique(['course_id', 'month', 'year', 'company_code'], 'unique_schedule');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_schedules');
    }
};
