<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_calculations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('device_id')->unsigned();
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');

            $table->bigInteger('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('RESTRICT');

            $table->string('value')->default('0')->comment('store value for operation');
            $table->string('rent')->default('0')->comment('store value add ,subtract from account');
            $table->integer('type')->comment('0 =>for account when add this person,1 =>for takeMoneyFromCustomer ,11 =>for take MoneyFromSupplier
            2 => for payMoneyToSupplierOrSupplierCustomer,
            3 => for add this number to adjust account,4 => for bill buy,
            5 => for bill sale,
            6=>bill back with type discount from account,
            7=>bill back with type replace,
            8=>bill back with type takeMoney,
            9=>exist Deal typ profit,
            10=>for Deal type loses,
            11 =>for take MoneyFromSupplier,
            ');

            $table->bigInteger('bill_id')->unsigned()->nullable()->comment('null => if type not equal 4 or 5');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');

            $table->bigInteger('exist_deal_id')->unsigned()->nullable()->comment('null => if type not equal 9 or 10');
            $table->foreign('exist_deal_id')->references('id')->on('exist_deals')->onDelete('cascade');


            $table->string('account_after_this_action');

            $table->integer('relation_account')->default(0)->comment('0 =>for don\'t update account,1=>for add value to account , 2 =>for subtract value from account');
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
        Schema::dropIfExists('account_calculations');
    }
}
