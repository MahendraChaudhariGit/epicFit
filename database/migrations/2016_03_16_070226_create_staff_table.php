<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
//            Schema::create('staff', function(Blueprint $table) {
//                $table->increments('id');
//                $table->string('first_name');
//                $table->string('last_name');
//                $table->string('gender');
//                $table->string('date_of_birth');
//                $table->string('job_title');
//                $table->text('staff_permissions');
//                $table->text('biography');
//                $table->text('profile_picture');
//                $table->boolean('login_with_email');
//                $table->text('clients_book_staff');
//                $table->boolean('monday');
//                $table->boolean('tuesday');
//                $table->boolean('wednesday');
//                $table->boolean('thursday');
//                $table->boolean('friday');
//                $table->boolean('saturday');
//                $table->boolean('sunday');
//                $table->string('website');
//                $table->string('facebook');
//                $table->string('email');
//                $table->string('phone');
//                $table->string('fixed_location');
//                $table->string('fixed_mobile');
//                $table->string('address_line_one');
//                $table->string('address_line_two');
//                $table->string('city');
//                $table->string('country');
//                $table->string('state');
//                $table->integer('postal_code');
//                $table->string('time_zone');
//                $table->string('staff_services');
//                $table->string('hourly_payment_label');
//                $table->string('hourly_rate');
//                $table->string('hourly_range');
//                $table->string('hourly_member');
//                $table->string('commission_payment');
//                $table->string('commission_role');
//                $table->string('commission_income_category');
//                $table->string('commission_referral_source');
//                $table->string('commission_rate_options');
//                $table->string('commission_hourly_rate');
//                $table->string('commission_date_range');
//                $table->string('commission_staff_member');
//                $table->string('per_session_role');
//                $table->string('per_session_program_type');
//                $table->string('per_session_program_category');
//                $table->string('per_session_rate_options');
//                $table->string('per_session_hourly_rate');
//                $table->string('per_session_attendee');
//                $table->string('per_session_date_range');
//                $table->string('per_session_staff_member');
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
        Schema::drop('staff');
    }

}
