<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_histories', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('customer_id');
            $table->string('user_id');
            $table->bigInteger('total_debt');
            $table->bigInteger('total_payment');
            $table->bigInteger('rest_debt');
            $table->tinyInteger('is_current');
            $table->integer('update_type');
            $table->string('order_id')->nullable();
            $table->string('container_order_id')->nullable();
            $table->string('vat_id')->nullable();
            $table->string('other_debt_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->date('updated_date');
            $table->bigInteger('number_of_money');
            $table->integer('monetary_unit_type');
            $table->string('comment')->nullable();
            $table->integer('version');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('container_order_id')->references('id')->on('container_orders')->onDelete('cascade');
            $table->foreign('vat_id')->references('id')->on('vats')->onDelete('cascade');
            $table->foreign('other_debt_id')->references('id')->on('other_debts')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debt_histories');
    }
}
