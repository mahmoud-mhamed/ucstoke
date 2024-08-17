<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->bigInteger('device_id')->unsigned();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');
            $table->bigInteger('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('RESTRICT');
            $table->bigInteger('stoke_id')->unsigned();
            $table->foreign('stoke_id')->references('id')->on('stokes')->onDelete('RESTRICT');
            $table->string('discount')->default('0');
            $table->string('total_price')->comment('total price after discount');
            $table->string('total_paid');
            $table->string('message')->default(' ');
            $table->integer('type')->comment('0=>for bill buy,1=>for bill sale');
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
        Schema::dropIfExists('bills');
    }
}
