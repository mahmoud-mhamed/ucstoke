<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->bigInteger('device_id')->nullable()->unsigned()->comment('to store temp treasury id');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('RESTRICT');

            $table->boolean('state')->default(true)->comment('0 not active , 1 is active');

            $table->string('bg')->default('bg2');
            $table->longText('bg_img')->nullable();
            $table->integer('type')->comment('1=>admin,2=>normalUser');

            //logout security
            $table->boolean('log_out_security')->default(false)->comment('true=>for allow_log_out_security_when try to open window you can\'t have permetion to open it');

            //users
            $table->boolean('allow_edit_my_account')->default(false)->comment('true=>for allow_edit_account_my_account');
            $table->boolean('allow_edit_my_account_name')->default(false)->comment('true=>for allow_edit_my_account_name');
            $table->boolean('allow_edit_account_email')->default(false)->comment('true=>for allow_edit_account_my_account');
            $table->boolean('allow_change_background')->default(false)->comment('true=>for allow_edit_account_my_account');

            //activities
            $table->boolean('allow_manage_activities')->default(false)->comment('true=>for allow_manage_activities');

            //customer and supplier and customer_supplier
            $table->boolean('allow_add_account')->default(true)->comment('true=>for allow_add_account');
            $table->boolean('create_notification_when_add_account_with_old_account')->default(false)->comment('true=>for create_notification_when_add_account_with_old_account');
            $table->boolean('allow_access_index_account')->default(false)->comment('true=>for allow_access_index_account');
            $table->boolean('allow_edit_account')->default(false)->comment('true=>for allow_access_index_account');
            $table->boolean('allow_edit_account_type')->default(false)->comment('true=>for allow_edit_account_type');
            $table->boolean('allow_edit_account_name')->default(false)->comment('true=>for allow_edit_account_name');
            $table->boolean('allow_edit_account_tel')->default(false)->comment('true=>for allow_edit_account_tel');
            $table->boolean('allow_delete_account')->default(false)->comment('true=>for allow_delete_account');
            $table->boolean('allow_adjust_account')->default(false)->comment('true=>for allow_adjust_account');
            $table->boolean('create_notification_when_adjust_account')->default(false)->comment('true=>for create_notification_when_adjust_account');
            $table->boolean('allow_access_report_account')->default(false)->comment('true=>for allow_access_report_account');
            $table->boolean('allow_delete_account_buy_take_money')->default(false)->comment('true=>for allow_delete_account_buy_take_money');
            $table->boolean('notification_when_delete_account_buy_take_money')->default(false)->comment('true=>for create_notification_when_delete_buy_or_take');


            //stoke
            $table->boolean('allow_mange_stoke')->default(false)->comment('true=>for allow_mange_stoke');
            $table->boolean('allow_mange_place_in_stoke')->default(false)->comment('true=>for allow_mange_place_in_stoke');
            $table->boolean('allow_mange_product_place_in_stoke')->default(false)->comment('true=>for allow_mange_product_place_in_stoke');


            //backups
            $table->boolean('allow_mange_backup')->default(false)->comment('true=>for allow_mange_backup');
            $table->boolean('allow_download_backup')->default(false)->comment('true=>for allow_download_backup');

            //treasury
            $table->boolean('allow_mange_treasury')->default(false)->comment('true=>for allow_mange_treasury');
            $table->boolean('allow_delete_treasury')->default(false)->comment('true=>for allow_delete_treasury');
            $table->boolean('create_notification_when_delete_treasury')->default(false)->comment('true=>for create_notification_when_delete_treasury');

            //more
            $table->boolean('allow_mange_setting')->default(false)->comment('true=>for allow_mange_setting');
            $table->boolean('allow_mange_device')->default(false)->comment('true=>for allow_mange_device');
            $table->boolean('allow_access_total_report')->default(false)->comment('true=>for allow_access_total_report');

            //expenses
            $table->boolean('allow_add_expenses_and_expenses_type')->default(false)->comment('true=>for allow_add_expenses_and_expenses_type');
            $table->boolean('allow_mange_expenses_type')->default(false)->comment('true=>for allow_mange_expenses_type');
            $table->boolean('allow_mange_expenses')->default(false)->comment('true=>for allow_mange_expenses');
            $table->boolean('allow_delete_expenses')->default(false)->comment('true=>for allow_delete_expenses');
            $table->boolean('allow_add_expenses_with_out_subtract_form_treasury')->default(false)->comment('true=>for allow_add_expenses_with_out_subtract_form_treasury');
            $table->boolean('notification_when_add_expenses_with_out_subtract_form_treasury')->default(false)->comment('true=>for create_notification_when_add_expenses_with_out_subtract_form_treasury');
            $table->boolean('notification_when_delete_expenses')->default(false)->comment('true=>for notification_when_delete_expenses');


            //product , productCategory , product_units, barcode
            $table->boolean('allow_add_product')->default(false)->comment('true=>for allow_mange_product_unit');
            $table->boolean('allow_manage_product')->default(false)->comment('true=>for allow_mange_product_unit');
            $table->boolean('allow_mange_barcode')->default(false)->comment('true=>for allow_mange_barcode');
            $table->boolean('allow_mange_product_category')->default(false)->comment('true=>for allow_mange_product_category');
            $table->boolean('allow_mange_product_unit')->default(false)->comment('true=>for allow_mange_product_unit');


            //bill
            $table->boolean('allow_mange_print_setting')->default(false)->comment('true=>for allow_mange_bill_message');
            $table->boolean('allow_mange_bill_message')->default(false)->comment('true=>for allow_mange_bill_message');
            $table->boolean('allow_create_bill_buy')->default(false)->comment('true=>for allow_create_bill_buy');
            $table->boolean('allow_create_bill_sale_show')->default(false)->comment('true=>for allow_create_bill_buy');
            $table->boolean('allow_manage_bill_buy')->default(false)->comment('true=>for allow_manage_bill_buy');
            $table->boolean('allow_edit_bill_buy')->default(false)->comment('true=>for allow_edit_bill_buy');
            $table->boolean('allow_delete_bill_buy')->default(false)->comment('true=>for allow_delete_bill_buy');
            $table->boolean('notification_delete_bill_buy')->default(false)->comment('true=>for notification_delete_bill_buy');
            $table->boolean('allow_create_bill_sale')->default(false)->comment('true=>for allow_create_bill_sale');
            $table->boolean('allow_manage_bill_sale')->default(false)->comment('true=>for allow_manage_bill_sale');
            $table->boolean('allow_manage_bill_sale_with_profit')->default(false)->comment('true=>for allow_manage_bill_sale_with_profit');
            $table->boolean('allow_edit_bill_sale')->default(false)->comment('true=>for allow_edit_bill_sale');
            $table->boolean('allow_delete_bill_sale')->default(false)->comment('true=>for allow_delete_bill_sale');
            $table->boolean('notification_delete_bill_sale')->default(false)->comment('true=>for notification_delete_bill_sale');

            //make
            $table->boolean('allow_add_make')->default(false)->comment('true=>for allow_add_make');
            $table->boolean('allow_manage_make')->default(false)->comment('true=>for allow_manage_make');
            $table->boolean('allow_delete_make')->default(false)->comment('true=>for allow_delete_make');
            $table->boolean('notification_delete_make')->default(false)->comment('true=>for notification_delete_make');

            //damage and product move and store
            $table->boolean('allow_access_product_in_stoke')->default(false)->comment('true=>for allow_access_product_in_store');
            $table->boolean('allow_access_product_in_all_stoke')->default(false)->comment('true=>for allow_access_product_in_all_stoke');
            $table->boolean('allow_move_product_in_stoke')->default(false)->comment('true=>for allow_move_product_in_stoke');
            $table->boolean('notification_when_move_product')->default(false)->comment('true=>for notification_when_move_product');
            $table->boolean('allow_add_damage')->default(false)->comment('true=>for allow_add_damage');
            $table->boolean('notification_when_add_damage')->default(false)->comment('true=>for notification_when_add_damage');
            $table->boolean('allow_access_product_move')->default(false)->comment('true=>for allow_access_product_move');
            $table->boolean('allow_delete_damage')->default(false)->comment('true=>for allow_access_product_move');
            $table->boolean('notification_when_delete_damage')->default(false)->comment('true=>for notification_when_delete_damage');
            $table->boolean('allow_access_product_profit')->default(false)->comment('true=>for allow_access_product_profit');

            //emps
            $table->boolean('allow_add_emp')->default(false)->comment('true=>for allow_add_emp');
            $table->boolean('allow_manage_emp_jops')->default(false)->comment('true=>for allow_add_emp');
            $table->boolean('allow_manage_emp')->default(false)->comment('true=>for allow_add_emp');
            $table->boolean('allow_manage_emp_operation')->default(false)->comment('true=>for allow_add_emp');
            $table->boolean('allow_manage_emp_move')->default(false)->comment('true=>for allow_add_emp');
            $table->boolean('allow_manage_emp_attend')->default(false)->comment('true=>for allow_add_emp');

            //exit deal
            $table->boolean('allow_create_exit_deal')->default(false)->comment('true=>for allow_create_exit_deal');
            $table->boolean('allow_manage_exit_deal')->default(false)->comment('true=>for allow_manage_exit_deal');
            $table->boolean('allow_delete_exit_deal')->default(false)->comment('true=>for allow_delete_exit_deal');
            $table->boolean('notification_when_delete_exit_deal')->default(false)->comment('true=>for notification_when_delete_exit_deal');

            //visits or notes
            $table->boolean('allow_add_visit')->default(false)->comment('true=>for allow_create_exit_deal');
            $table->boolean('allow_manage_visit')->default(false)->comment('true=>for allow manage and edit');
            $table->boolean('allow_delete_visit')->default(false);
            $table->boolean('notification_when_delete_visit')->default(false);
            $table->boolean('show_notification_visit')->default(false)->comment('true=>for allow manage and edit');

            //other

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
