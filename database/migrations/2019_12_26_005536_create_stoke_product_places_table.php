<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStokeProductPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stoke_product_places', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stoke_id')->unsigned();
            $table->foreign('stoke_id')->references('id')->on('stokes')->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('stoke_place_name_id')->unsigned();
            $table->foreign('stoke_place_name_id')->references('id')->on('stoke_place_names')->onDelete('RESTRICT');
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
        Schema::dropIfExists('stoke_product_places');
    }
}
