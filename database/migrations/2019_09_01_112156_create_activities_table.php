<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->integer('type')->
            comment('users=>0,stokes=>1,saves=>2,backups->3,settings=>4,supplier=>5,customer=>6,supplierCustomer=>7,8->damageProduct,9=>devices,
            10=>add and take money from treasury,11=>for expenses_types and expenses,12=>for product_units,barcode,product,productsCategory
            13=>for bill buy,14=>for bill sale,15=>for bill message and bill design print,16=>for making,17=>for emp,18 =>exist_deals,
            19=>for visit or plan');
            $table->longText('data');
            $table->integer('notification')->default(0)->comment('0 =>if activity don\'t have notification,1 => if activity has notification,2 => if activity notification delete');
            $table->string('button')->nullable()->comment('for store button to show activity');
            $table->bigInteger('device_id')->unsigned()->nullable();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');
            $table->integer('relation_treasury')->default(0)->comment('0 =>for don\'t have relation with treasury , 1 => for have relation with treasury add,2 => for have relation with treasury subtract');
            $table->string('treasury_value')->nullable()->comment('store value add or subtract from treasury');
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
        Schema::dropIfExists('activities');
    }
}
