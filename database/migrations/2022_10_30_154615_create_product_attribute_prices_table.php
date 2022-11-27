<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_prices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('product_attribute_value_id');
            $table->integer('price');
            $table->string('monetary_unit');
            $table->integer('notice_price_type');
            $table->boolean('is_current');
            $table->foreign('product_attribute_value_id')->references('id')->on('product_attribute_values')->onDelete('cascade');
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
        Schema::dropIfExists('product_attribute_prices');
    }
}
