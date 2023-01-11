<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportGoodProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_good_products', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('import_good_id');
            $table->string('product_id');
            $table->string('product_attribute_value_id');
            $table->integer('price');
            $table->integer('monetary_unit_type');
            $table->integer('count');
            $table->integer('measure_unit_type');
            $table->timestamps();
            $table->foreign('import_good_id')->references('id')->on('import_goods')->onDelete('cascade');
            $table->foreign('product_attribute_value_id')->references('id')->on('product_attribute_values')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_good_products');
    }
}
