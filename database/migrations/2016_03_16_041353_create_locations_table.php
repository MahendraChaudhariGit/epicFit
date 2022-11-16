<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
//            Schema::create('locations', function(Blueprint $table) {
//                $table->increments('id');
//                $table->string('venue');
//$table->string('venue_training_area');
//$table->string('location');
//$table->string('location_training_area');
//$table->string('logo');
//$table->text('stuff_selection');
//$table->boolean('monday');
//$table->boolean('tuesday');
//$table->boolean('wednesday');
//$table->boolean('thursday');
//$table->boolean('friday');
//$table->boolean('saturday');
//$table->boolean('sunday');
//$table->string('website');
//$table->string('facebook');
//$table->string('email');
//$table->string('phone');
//$table->string('fixed_location');
//$table->string('fixed_mobile');
//$table->string('address_line_one');
//$table->string('address_line_two');
//$table->string('city');
//$table->string('country');
//$table->string('state');
//$table->integer('postal_code');
//$table->string('time_zone');
//$table->boolean('disp_location_web');
//$table->boolean('disp_location_online');
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
        Schema::drop('locations');
    }

}
