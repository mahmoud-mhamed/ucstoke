<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('bill_id')->unsigned();
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('RESTRICT');
            $table->bigInteger('store_id')->unsigned()->nullable()->comment('used in delete bill buy or add Discarded to bill buy');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('RESTRICT');
            $table->bigInteger('product_unit_id')->unsigned();
            $table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('RESTRICT');
            $table->string('relation_qte')->comment('store value relation product_unit_id and main unit');

            $table->string('qte')->comment('qte by main unit');
            $table->string('price')->comment('price for 1 main unit');

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
        Schema::dropIfExists('bill_details');
    }
}
