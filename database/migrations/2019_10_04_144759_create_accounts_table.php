<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->bigInteger('device_id')->unsigned()->comment('last device for this account');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');

            $table->string('name');
            $table->string('tel')->default('');
            $table->string('address')->default('');
            $table->string('account')->default('0');
            $table->string('note')->default('');
            $table->boolean('is_supplier')->nullable()->default(false);
            $table->boolean('is_customer')->nullable()->default(false);
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
        Schema::dropIfExists('accounts');
    }
}
