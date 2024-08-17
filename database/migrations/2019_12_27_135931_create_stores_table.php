<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stoke_id')->unsigned();
            $table->foreign('stoke_id')->references('id')->on('stokes')->onDelete('RESTRICT');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('RESTRICT');
            $table->string('qte')->comment('qte by main unit');
            $table->string('price')->comment('price for 1 main unit');
            $table->integer('type')->comment('0 =>if qte not make,1=>if qte is make');
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
        Schema::dropIfExists('stores');
    }
}
