<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->bigInteger('product_category_id')->unsigned();
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('RESTRICT');
            $table->string('note');
            $table->boolean('state')->default(true)->comment('true => this product show in bill,false =>this product can\'t show in bill');
            $table->boolean('special')->default(false)->comment('true => this product show in special product in bill');

            $table->boolean('allow_buy')->comment('true => this product can buy');
            $table->boolean('allow_sale')->comment('true => this product can sale');
            $table->boolean('allow_make')->comment('true => this product can make');
            $table->boolean('allow_no_qte')->comment('true => this product has no qte in stoke');

            $table->bigInteger('product_unit_id')->unsigned();
            $table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('RESTRICT');

            $table->string('min_qte')->comment('store min qte for product in stoke');
            $table->string('barcode1')->nullable();
            $table->string('barcode2')->nullable();
            $table->string('barcode3')->nullable();
            $table->string('price_buy');
            $table->string('price_sale1')->default(0);
            $table->string('price_sale2')->default(0);
            $table->string('price_sale3')->default(0);
            $table->string('price_sale4')->default(0);

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
