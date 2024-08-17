<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT');
            $table->string('company_name')->default('UltimateCode')->comment('store company name in barcode');
            $table->string('company_name_font_size')->default('8');
            $table->string('company_name_color')->default('#000000');
            $table->string('barcode_font_size')->default('6');
            $table->string('barcode_type')->default('CODE128');
            $table->string('barcode_width')->default('38');
            $table->string('barcode_height')->default('25');
            $table->string('product_font_size')->default('6');
            $table->string('product_color')->default('6');
            $table->string('price_font_size')->default('6');
            $table->string('price_color')->default('#000000');
            $table->string('time_font_size')->default('6');
            $table->string('time_color')->default('#000000');
            $table->string('padding_top')->default('1')->comment('by milly metre');
            $table->string('padding_bottom')->default('1')->comment('by milly metre');
            $table->string('padding_right')->default('1')->comment('by milly metre');
            $table->string('padding_left')->default('1')->comment('by milly metre');
            $table->string('barcode_color')->default('#000000');
            $table->string('last_barcode')->default('0')->comment('store last barcode create by program');

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
        Schema::dropIfExists('barcodes');
    }
}
