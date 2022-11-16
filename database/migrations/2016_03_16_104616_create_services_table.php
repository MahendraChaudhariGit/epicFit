<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
//            Schema::create('services', function(Blueprint $table) {
//                $table->increments('id');
//                $table->string('type');
//$table->string('category');
//$table->string('location');
//$table->string('one_on_one_name');
//$table->text('one_on_one_description');
//$table->string('one_on_one_training_logo');
//$table->string('one_on_one_colour');
//$table->boolean('one_on_one_call_client_online');
//$table->string('one_on_one_duration');
//$table->string('one_on_one_staffs');
//$table->string('one_on_one_price');
//$table->string('one_on_one_tax');
//$table->string('team_name');
//$table->text('team_description');
//$table->string('team_training_logo');
//$table->text('team_colour');
//$table->boolean('team_can_book_online');
//$table->string('team_duration');
//$table->string('team_capacity');
//$table->string('team_staffs');
//$table->string('team_price');
//$table->string('team_tax');
//
//                $table->timestamps();
//            });
            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('services');
    }

}
