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
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->foreignId('easy_parcel_country_id')->nullable();
            $table->foreignId('easy_parcel_state_id')->nullable();
            $table->string("postal_code")->nullable()->after('easy_parcel_state_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_addresses', function (Blueprint $table) {
            $table->dropColumn(['easy_parcel_country_id', 'easy_parcel_state_id']);
            $table->dropColumn('postal_code');
        });
    }
};
