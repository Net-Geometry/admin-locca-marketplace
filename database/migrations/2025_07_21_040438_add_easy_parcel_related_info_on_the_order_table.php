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
            $table->string("awb_no")->nullable();
            $table->string("easy_parcel_order_no")->nullable();
            $table->string("awb_id_link")->nullable();
            $table->string("tracking_url")->nullable();  

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn("awb_no");
            $table->dropColumn("easy_parcel_order_no");
            $table->dropColumn("awb_id_link");
            $table->dropColumn("tracking_url");
        });
    }
};
