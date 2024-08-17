<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emps', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');

            $table->bigInteger('emp_jop_id')->unsigned();
            $table->foreign('emp_jop_id')->references('id')->on('emp_jops')->onDelete('RESTRICT');


            $table->bigInteger('device_id')->unsigned()->comment('for device can allow use this emp');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');

            $table->string('name');
            $table->string('tel')->default('');
            $table->string('address')->default('');
            $table->string('account')->default('0');
            $table->string('day_salary')->default('0');
            $table->string('note')->default('');

            $table->boolean('state')->default(1)->comment('0 =>not active,1 =>active');

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
        Schema::dropIfExists('emps');
    }
}
