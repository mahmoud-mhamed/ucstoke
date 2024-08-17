<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            //setting for public
            $table->boolean('show_treasury_value_in_header')->default(true)->comment('true=>show_treasury_value_in_header');
            $table->boolean('allow_sound')->default(true)->comment('true=>for_allow_sound');

            //setting for account
            $table->boolean('allow_account_without_tel')->default(true)->comment('true=>allow_account_without_tel');
            $table->boolean('allow_repeat_tell_account')->default(false)->comment('true=>for allow_repeat_tell_account');
            $table->boolean('allow_repeat_supplier_name')->default(false)->comment('true=>for allow_repeat_supplier_name');
            $table->boolean('allow_repeat_customer_name')->default(false)->comment('true=>for allow_repeat_customer_name');
            $table->boolean('allow_account_with_negative_account')->default(false)->comment('true=>for allow_account_with_negative_account');
            $table->boolean('allow_pay_money_to_account_with_negative_account')->default(false)->comment('true=>for allow_pay_money_to_account_with_negative_account');
            $table->boolean('allow_take_money_from_account_with_negative_account')->default(false)->comment('true=>for allow_take_money_from_account_with_negative_account');


            //setting for product
            $table->boolean('edit_auto_for_default_min_qte_unit')->default(true)->comment('true=>for allow_edit automatic for min qte when add product');
            $table->string('price1_name')->default('قطاعى')->comment('sore price one name');
            $table->string('price2_name')->default('نص جملة')->comment('sore price two name');
            $table->string('price3_name')->default('جملة')->comment('sore price three name');
            $table->string('price4_name')->default('مندوب')->comment('sore price four name');

            //setting for expenses
            $table->boolean('allow_add_expenses_without_subtract_from_treasury')->default(false)->comment('true=>for allow_add_expenses_without_subtract_from_treasury');

            //setting for bill
            $table->boolean('auto_update_price_product_bill_buy')->default(true)->comment('true=>for allow_auto_update_price_product when change in_bill_buy');
            $table->boolean('auto_update_price_product_bill_sale')->default(true)->comment('true=>for allow_auto_update_price_product when change in _bill_sale');
            $table->boolean('show_unit_when_print_bill')->default(true);

            //to store default message in bill buy and sale
            $table->bigInteger('bill_message_buy_id')->unsigned()->nullable();
            $table->foreign('bill_message_buy_id')->references('id')->on('bill_messages')->onDelete('set null');

            //to store default message in bill buy and sale
            $table->bigInteger('bill_message_sale_id')->unsigned()->nullable();
            $table->foreign('bill_message_sale_id')->references('id')->on('bill_messages')->onDelete('set null');

            //to allow use small price 0.001
            $table->boolean('use_small_price')->default(false);

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
        Schema::dropIfExists('settings');
    }
}
