<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportGoodProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_good_products', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('export_good_id');
            $table->string('product_id');
            $table->string('product_attribute_value_id');
            $table->integer('count');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('export_good_id')->references('id')->on('export_goods')->onDelete('cascade');
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
        Schema::dropIfExists('export_good_products');
    }
}
