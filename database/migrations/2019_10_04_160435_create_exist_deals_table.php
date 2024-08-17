<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExistDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exist_deals', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('device_id')->unsigned();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');

            $table->bigInteger('account_id')->nullable()->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('RESTRICT');

            $table->string('value')->default('0')->comment('store value for operation');

            $table->string('value_add_to_treasury')->default('0')->comment('store value added to treasury by + number');

            $table->integer('type')->comment('0=>for profit,1 =>for loses');

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
        Schema::dropIfExists('exist_deals');
    }
}
