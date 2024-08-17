<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_moves', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->date('date');

            $table->bigInteger('device_id')->unsigned();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');

            $table->bigInteger('emp_id')->unsigned();
            $table->foreign('emp_id')->references('id')->on('emps')->onDelete('RESTRICT');

            $table->string('value')->default('0');
            $table->integer('type')->comment('0 =>for account when add this person,1 => for add addition, 2 => for discount ,
            3 => for borrow ,4 => for puy money,5 =>for attend,6 =>for not attend(this row add when change to not attend),7 =>not attend but was attend');

            $table->string('account_after_this_action');
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
        Schema::dropIfExists('emp_moves');
    }
}
