<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationProductUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relation_product_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->bigInteger('product_unit_id')->unsigned();
            $table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('RESTRICT');

            $table->string('relation_qte')->comment('store vale greater than main unit');

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
        Schema::dropIfExists('relation_product_units');
    }
}
