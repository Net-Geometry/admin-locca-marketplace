<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxOnMultiDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_on_multi_data', function (Blueprint $table) {
            $table->id();
            $table->string('data_type');
            $table->foreignId('data_id');
            $table->foreignId('tax_vat_id');
            $table->foreignId('system_tax_vat_id');
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
        Schema::dropIfExists('tax_on_multi_data');
    }
}
