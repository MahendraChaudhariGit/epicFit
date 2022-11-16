<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpnMealMainCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpn_meal_main_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mp_meal_id');
            $table->unsignedBigInteger('main_category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->foreign('mp_meal_id')->references('id')->on('mpn_meal')->onDelete('cascade');
            $table->foreign('main_category_id')->references('id')->on('main_categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories')->onDelete('cascade');
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
        Schema::dropIfExists('mpn_meal_main_categories');
    }
}
