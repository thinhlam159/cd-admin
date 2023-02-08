<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_inventories', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('product_attribute_value_id');
            $table->integer('count');
            $table->integer('measure_unit_type');
            $table->string('import_good_product_id')->nullable();
            $table->string('order_product_id')->nullable();
            $table->integer('update_type');
            $table->integer('number_of_update');
            $table->boolean('is_current');
            $table->foreign('product_attribute_value_id')->references('id')->on('product_attribute_values')->onDelete('cascade');
            $table->foreign('order_product_id')->references('id')->on('order_products')->onDelete('cascade');
            $table->foreign('import_good_product_id')->references('id')->on('import_good_products')->onDelete('cascade');
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
        Schema::dropIfExists('product_inventories');
    }
}
