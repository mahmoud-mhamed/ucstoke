<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMakesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('makes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('device_id')->unsigned();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->bigInteger('stoke_id')->unsigned();
            $table->foreign('stoke_id')->references('id')->on('stokes')->onDelete('RESTRICT');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('RESTRICT');
            $table->bigInteger('store_id')->unsigned()->nullable()->comment('use nullable because in add save it without store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('RESTRICT');
            $table->bigInteger('product_unit_id')->unsigned();
            $table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('RESTRICT');
            $table->string('relation_qte')->comment('store value relation product_unit_id and main unit');
            $table->string('qte')->comment('qte by main unit');
            $table->string('price_make')->nullable('use nullable because in add save it without store_id')->comment('price for 1 main unit');
            $table->longText('note')->nullable();

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
        Schema::dropIfExists('makes');
    }
}
