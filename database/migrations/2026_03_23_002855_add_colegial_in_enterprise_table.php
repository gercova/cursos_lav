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
        Schema::table('enterprise', function (Blueprint $table) {
            $table->string('colegial_type', 20)->nullable()->after('legal_representative');
            $table->string('colegial', 20)->nullable()->after('colegial_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enterprise', function (Blueprint $table) {
            $table->dropColumn(['colegial_type', 'colegial']);
        });
    }
};
