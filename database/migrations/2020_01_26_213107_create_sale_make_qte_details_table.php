<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleMakeQteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //this table store details for qte in bill sale and details for make
        Schema::create('sale_make_qte_details', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('make_id')->unsigned()->nullable();
            $table->foreign('make_id')->references('id')->on('makes')->onDelete('cascade');

            $table->bigInteger('bill_detail_id')->unsigned()->nullable();
            $table->foreign('bill_detail_id')->references('id')->on('bill_details')->onDelete('cascade');

            $table->bigInteger('bill_back_detail_id')->unsigned()->nullable();
            $table->foreign('bill_back_detail_id')->references('id')->on('bill_back_details')->onDelete('cascade');


            $table->bigInteger('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('RESTRICT');
            $table->string('qte')->comment('qte by main unit');
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
        Schema::dropIfExists('sale_make_qte_details');
    }
}
