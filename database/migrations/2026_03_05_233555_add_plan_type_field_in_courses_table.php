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
            $table->integer('plan_type_id')->nullable()->after('image_url')->index();
            $table->foreign('plan_type_id')->references('id')->on('plan_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['plan_type_id']);
            $table->dropColumn(['plan_type_id']);
        });
    }
};
