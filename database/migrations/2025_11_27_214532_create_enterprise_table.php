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
        Schema::create('enterprise', function (Blueprint $table) {
            $table->id();
            $table->string('ruc', 11)->unique();
            $table->string('company_name', 150);
            $table->string('trade_name', 150);
            $table->string('legal_representative_dni', 11);
            $table->string('legal_representative', 100);
            $table->string('address', 100);
            $table->string('geographical_code', 6);
            $table->string('city', 150);
            $table->string('business_sector');
            $table->string('phrase');
            $table->text('description');
            $table->text('vision');
            $table->text('mission');
            $table->string('phone_number_1', 20);
            $table->string('phonen_umber_2', 20);
            $table->string('email', 100)->unique();
            $table->string('facebook_link');
            $table->string('linkedin_link');
            $table->string('twitter_link');
            $table->string('instagram_link');
            $table->string('whatsapp_link');
            $table->string('logo_principal');
            $table->string('logo_mini');
            $table->string('logotipo');
            $table->string('isologo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entreprise');
    }
};
