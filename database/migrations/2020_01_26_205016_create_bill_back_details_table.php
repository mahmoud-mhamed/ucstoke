<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillBackDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_back_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('bill_back_id')->unsigned();
            $table->foreign('bill_back_id')->references('id')->on('bill_backs')->onDelete('cascade');

            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('RESTRICT');

            $table->bigInteger('bill_details_id')->unsigned();
            $table->foreign('bill_details_id')->references('id')->on('bill_details')->onDelete('cascade');

            $table->bigInteger('product_unit_id')->unsigned();
            $table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('RESTRICT');
            $table->string('relation_qte')->comment('store value relation product_unit_id and main unit');

            $table->string('qte')->comment('qte by main unit');
            $table->string('price')->comment('price for 1 main unit');

            $table->bigInteger('store_id')->unsigned()->nullable()->comment('used in delete bill buy back');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('RESTRICT');


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
        Schema::dropIfExists('bill_back_details');
    }
}
