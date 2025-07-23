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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('easy_parcel_rate_id')->nullable();
            $table->string('easy_parcel_service_id')->nullable();
            $table->string('easy_parcel_courier_id')->nullable();
            $table->string('easy_parcel_courier_logo_link')->nullable();
            $table->string('easy_parcel_scheduled_start_date')->nullable();
            $table->string('easy_parcel_pickup_date')->nullable();
            $table->string('easy_parcel_delivery')->nullable();
            $table->string('easy_parcel_service_name')->nullable();
            $table->string('easy_parcel_courier_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
