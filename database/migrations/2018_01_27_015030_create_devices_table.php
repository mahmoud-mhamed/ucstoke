<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mac')->unique()->comment('if device is localHost store mac ,else store ip');
            $table->string('hash_check')->nullable()->unique();
            $table->string('name')->unique()->comment('store name for device and treasury');
            $table->string('treasury_value')->default(0);
            $table->bigInteger('default_stoke')->unsigned()->nullable();
            $table->foreign('default_stoke')->references('id')->on('stokes')->onDelete('set null');
            $table->bigInteger('design_bill_print')->unsigned()->nullable();
            $table->foreign('design_bill_print')->references('id')->on('bill_prints')->onDelete('RESTRICT');

            $table->boolean('state_download_backup')->default(false);
            $table->integer('download_backup_every')->nullable();
            $table->date('day_download')->nullable();

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
        Schema::dropIfExists('devices');
    }
}
