<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
//            Schema::create('clients', function(Blueprint $table) {
//                $table->increments('id');
//                $table->string('first_name');
//$table->string('last_name');
//$table->string('client_status');
//$table->string('email');
//$table->string('phone');
//$table->string('referral_type');
//$table->string('referral_name');
//$table->string('goals');
//$table->text('client_notes');
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
        Schema::drop('clients');
    }

}
