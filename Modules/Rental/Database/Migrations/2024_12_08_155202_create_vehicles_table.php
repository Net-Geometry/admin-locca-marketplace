<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('images')->nullable();
            $table->integer('provider_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('model', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('engine_capacity', 255)->nullable();
            $table->string('engine_power', 255)->nullable();
            $table->string('seating_capacity', 255)->nullable();
            $table->integer('air_condition')->default(0);
            $table->string('fuel_type', 255)->nullable();
            $table->string('transmission_type', 255)->nullable();
            $table->integer('multiple_vehicles', 1)->default(0);
            $table->integer('trip_hourly',11)->default(0);
            $table->integer('trip_distance',11)->default(0);
            $table->decimal('hourly_price',23,2)->default(0.00);
            $table->decimal('distance_price',23,2)->default(0.00);
            $table->string('discount_type',255)->nullable();
            $table->decimal('discount_price',23, 2)->default(0.00);
            $table->text('tag')->nullable();
            $table->text('documents')->nullable();
            $table->integer('status')->default(1);
            $table->integer('new_tag')->default(1);
            $table->integer('total_trip')->default(0);
            $table->string('avg_rating')->default(0.00);
            $table->integer('total_reviews')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
