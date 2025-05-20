<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxOnAdditionalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_on_additional_data', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('country_code',20)->nullable()->index();
            $table->foreignId('system_tax_vat_id')->nullable();
            $table->string('tax_payer',20)->nullable()->default('vendor');
            $table->tinyText('tax_vat_ids',255)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_included')->default(false);
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
        Schema::dropIfExists('tax_on_additional_data');
    }
}
