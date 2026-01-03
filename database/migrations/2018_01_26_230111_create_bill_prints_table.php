<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillPrintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_prints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->default('UltimateCode');
            $table->string('company_name')->default('شركة UltimateCode للبرمجيات ترحب بكم');
            $table->string('row_under_company_name')->default('سعداء بخدمتكم');
            $table->string('row_contact1')->default('إدارة : م/أحمد الغمرى : 01018030420');
            $table->string('row_contact2')->default('للتواصل مع خدمة العملاء : م/تامر عبدو : 01018030420');
            $table->boolean('use_small_size')->default(0)->comment('1 for small size,0 for not small size');
            $table->string('small_size')->default(6)->comment('store value for width for bill when type_size =1');
            $table->longText('icon')->nullable();

            $table->string('header_size')->default('1.75')->comment('store size for company_name & row_under_company_name by rem');
            $table->string('bill_number_date_size')->default('1.1')->comment('store size for bill_number & date by rem');
            $table->string('contact_size')->default('1.5')->comment('store size for row_contact1 & row_contact 2 by rem');
            $table->string('account_size')->default('1.5')->comment('store size for account data by rem');
            $table->string('table_header_size')->default('1.4')->comment('store size for table_header_size by rem');
            $table->string('table_body_size')->default('1.3')->comment('store size for table_body_size by rem');
            $table->string('table_footer_size')->default('1.4')->comment('store size for table_footer_size by rem');
            $table->string('message_uc_size')->default('1')->comment('store size for message_uc_size by rem');
            $table->string('opacity_background')->default('0')->comment('store opacity for background in bill');

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
        Schema::dropIfExists('bill_prints');
    }
}
