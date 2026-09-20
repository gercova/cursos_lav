<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("UPDATE certificates SET expiry_date = DATE_ADD(issue_date, INTERVAL 1 YEAR) WHERE expiry_date IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No operation needed for rollback to avoid clearing valid data
    }
};
