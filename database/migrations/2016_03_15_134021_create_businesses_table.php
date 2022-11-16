<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
//            Schema::create('businesses', function(Blueprint $table) {
//                $table->increments('id');
//                $table->integer('user_id')->unsigned();
//                $table->string('trading_name');
//                $table->string('type');
//                $table->string('relationship');
//                $table->string('cp_first_name');
//                $table->string('cp_last_name');
//                $table->text('description');
//                $table->string('currency');
//                $table->string('time_zone');
//                $table->string('logo');
//                $table->string('website');
//                $table->string('facebook');
//                $table->string('email')->unique();
//                $table->string('phone');
//                $table->string('address_line_one');
//                $table->string('address_line_two');
//                $table->string('city');
//                $table->string('country');
//                $table->string('state');
//                $table->integer('postal_code');
//                $table->boolean('venue_location');
//                $table->boolean('billing_info');
//
//                $table->timestamps();
//
//
//                $table->foreign('user_id')
//                    ->references('id')
//                    ->on('users')
//                    ->onDelete('cascade');
//            });
            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('businesses');
    }

}
