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
            $table->unsignedBigInteger('easy_parcel_country_id')->nullable()->after('postal_code');
            $table->unsignedBigInteger('easy_parcel_state_id')->nullable()->after('easy_parcel_country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn(['postal_code', 'easy_parcel_country_id', 'easy_parcel_state_id']);
        });
    }
};
