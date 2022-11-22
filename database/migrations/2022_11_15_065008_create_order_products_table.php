<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('order_id');
            $table->string('product_id');
            $table->string('product_attribute_value_id');
            $table->string('product_attribute_price_id');
            $table->integer('count');
            $table->integer('measure_unit_type');
            $table->string('coupon_id')->nullable();
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
        Schema::dropIfExists('order_products');
    }
}
