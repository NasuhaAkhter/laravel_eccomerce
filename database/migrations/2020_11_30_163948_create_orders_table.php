<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('address_id');
            $table->integer('franchise_id');
            $table->float('total');
            $table->float('subTotal');
            $table->float('shipingCharge');
            $table->string('paymentType');
            $table->date('orderDate');
            $table->string('orderType');
            $table->string('status');
            $table->timestamp('statusUpdated');
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
        Schema::dropIfExists('orders');
    }
}
