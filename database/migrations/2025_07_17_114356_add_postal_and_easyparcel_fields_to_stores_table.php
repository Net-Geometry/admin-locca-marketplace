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
        Schema::table('stores', function (Blueprint $table) {
            $table->string('postal_code')->nullable()->after('address'); 
            $table->unsignedBigInteger('easyparcel_country_id')->nullable()->after('postal_code');
            $table->unsignedBigInteger('easyparcel_state_id')->nullable()->after('easyparcel_country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['postal_code', 'easyparcel_country_id', 'easyparcel_state_id']);
        });
    }
};
