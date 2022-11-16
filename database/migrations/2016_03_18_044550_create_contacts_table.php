<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
//            Schema::create('contacts', function(Blueprint $table) {
//                $table->increments('id');
//                $table->string('type');
//$table->string('company_name');
//$table->string('service');
//$table->string('service_offered');
//$table->string('location');
//$table->string('contact_name');
//$table->string('preferred_name');
//$table->text('notes');
//$table->string('website');
//$table->string('facebook');
//$table->string('email');
//$table->string('phone');
//$table->string('address_line_one');
//$table->string('address_line_two');
//$table->string('city');
//$table->string('country');
//$table->string('state');
//$table->integer('postal_code');
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
        Schema::drop('contacts');
    }

}
