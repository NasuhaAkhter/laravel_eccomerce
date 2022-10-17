<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTransectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transections', function (Blueprint $table) {
            $table->id();
            $table->integer('franchise_id');
            $table->integer('petner_shop_id');
            $table->integer('user_id');
            $table->integer('order_id');
            $table->integer('pakage_id');
            $table->float('discount');
            $table->float('amount');
            $table->date('date');
            $table->float('benifit');
            $table->string('paymetnType');
            $table->string('purpose');
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
        Schema::dropIfExists('payment_transections');
    }
}
