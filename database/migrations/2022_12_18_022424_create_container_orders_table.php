<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_orders', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->bigInteger('cost');
            $table->integer('monetary_unit_type');
            $table->string('comment')->nullable();
            $table->string('customer_id');
            $table->string('user_id');
            $table->integer('payment_status');
            $table->date('arising_date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('container_orders');
    }
}
