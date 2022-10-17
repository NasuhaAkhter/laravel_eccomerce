<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('partner_shop_id');
            $table->integer('product_category_id');
            $table->integer('product_subcategory_id');
            $table->integer('franchise_id');
            $table->integer('band_id');
            $table->float('dicount');
            $table->float('percentage');
            $table->string('image');
            $table->integer('totalSale');
            $table->string('warranty');
            $table->float('amount');
            $table->boolean('isFeatured');
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
        Schema::dropIfExists('products');
    }
}
