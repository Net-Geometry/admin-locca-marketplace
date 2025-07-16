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
        Schema::create('easy_parcel_states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('State name');
            $table->string('state_code');
            $table->boolean('status')->default(1);
            $table->foreignId('easy_parcel_country_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('easy_parcel_states');
    }
};
