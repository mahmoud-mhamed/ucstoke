<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mange_stoke')->nullable();
            $table->string('place_product')->nullable()->comment('for product place in stoke');
            $table->string('sup_cust')->nullable()->comment('for allow use supplier customer');
            $table->string('product_make')->nullable()->comment('for allow use product make');
            $table->string('product_no_qte')->nullable()->comment('for allow use product no qte');
            $table->string('use_barcode')->nullable()->comment('null or error value for not use barcode');
            $table->string('use_barcode2')->nullable()->comment('null or error value for not use barcode 2');
            $table->string('use_barcode3')->nullable()->comment('null or error value for not use barcode3');
            $table->string('bill_design')->nullable()->comment('for allow change bill_design');
            $table->string('use_expenses')->nullable()->comment('for allow use_expenses');
            $table->string('use_exit_deal')->nullable()->comment('for allow use_exit_deal');
            $table->string('use_emp')->nullable()->comment('for allow use_emp');
            $table->string('account_product_move')->nullable()->comment('for allow show report account_product_move for supplier or customer');
            $table->string('use_visit')->nullable()->comment('for allow use_visit');
            $table->string('only_product_no_qte')->nullable()->comment('null or error value for only use product_no_qte only in project,dependent permit product_no_qte');
            $table->integer('login_counter')->nullable()->comment('store number of login');
            $table->date('expire_date')->nullable()->comment('null for don\'t have expire date');
            $table->string('use_price2')->nullable()->comment('null or wrong value for not use price2 for sale');
            $table->string('use_price3')->nullable()->comment('null or wrong value for not use price3 for sale');
            $table->string('use_price4')->nullable()->comment('null or wrong value for not use price4 for sale');
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
        Schema::dropIfExists('permits');
    }
}
