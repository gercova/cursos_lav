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
            $table->renameColumn('seats', 'seats_max');
            $table->integer('seats_min')->nullable()->default(0)->after('promotion_price');
            $table->longText('which_includes')->nullable()->after('what_you_learn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['seats_min', 'which_includes']);
            $table->renameColumn('seats_max', 'seats');
        });
    }
};
