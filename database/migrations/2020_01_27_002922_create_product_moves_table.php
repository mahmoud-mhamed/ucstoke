<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   //this table store Damaged qte
        Schema::create('product_moves', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('device_id')->unsigned();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');

            $table->bigInteger('store_id')->unsigned()->nullable()->comment('used if delete Damaged qte , or delete making, nullable Because type product no qte');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('RESTRICT');

            $table->bigInteger('stoke_id')->unsigned()->nullable()->comment('nullable Because type product no qte');
            $table->foreign('stoke_id')->references('id')->on('stokes')->onDelete('RESTRICT');


            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->bigInteger('product_unit_id')->unsigned();
            $table->foreign('product_unit_id')->references('id')->on('product_units')->onDelete('RESTRICT');
            $table->string('relation_qte')->comment('store value relation product_unit_id and main unit');

            $table->string('qte')->comment('qte by main unit');
            $table->string('price')->comment('price for 1 main unit');

            $table->string('type')->comment('0 => bill buy,1 => bill sale, 2 => Damaged Qte buy,3 => Damaged Qte Make, 4 => making,5=>use to make,
            6 =>bill buy back,7 => bill sale back,8 =>bill buy back replace,9 => bill back sale replace,
            10=>for move To other stoke (-),11 => for move to this stoke (+),
            12 => for old qte before edit bill buy,13 => old qte before eqit bill sale,
            14 => new qte after edit bill buy,15 =>new qte after edit bill sale,16 => when create product');

            $table->bigInteger('bill_id')->unsigned()->nullable()->comment('used if type is 1 or 2');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');

            $table->bigInteger('bill_back_id')->unsigned()->nullable()->comment('used if type is 6 or 7 if delete it');
            $table->foreign('bill_back_id')->references('id')->on('bill_backs')->onDelete('cascade');


            $table->bigInteger('make_id')->unsigned()->nullable()->comment('used if type is 4');
            $table->foreign('make_id')->references('id')->on('makes')->onDelete('cascade');

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
        Schema::dropIfExists('product_moves');
    }
}
