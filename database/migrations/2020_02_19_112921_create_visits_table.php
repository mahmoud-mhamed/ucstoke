<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->bigInteger('device_id')->unsigned()->nullable();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');

            $table->bigInteger('bill_id')->unsigned()->nullable();
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->bigInteger('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('RESTRICT');

            $table->string('price')->comment('this price add to treasury when finish');
            $table->integer('type')->comment('0=>for free vist,1=>for online visit,2 =>for in place visit,3 => for plan');
            $table->boolean('state_visit')->default(1)->comment('0=>not finish,1=>finish');//0 for not finish , 1 for finish
            $table->integer('alarm_before')->nullable()->default(0)->comment('store day alarm before date alarm');
            $table->date('date_alarm')->nullable()->default(null);
            $table->date('date_finish')->nullable()->default(null);
            $table->longText('note');

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
        Schema::dropIfExists('visits');
    }
}
