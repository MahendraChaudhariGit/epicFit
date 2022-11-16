<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('users', function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('account_id')->nullable();
//            $table->string('name');
//            $table->string('last_name');
//            $table->string('email')->unique();
//            $table->string('password', 60);
//            $table->string('telephone');
//            $table->string('referral');
//
//            $table->enum('client_management', [0, 1])->default(0);
//            $table->enum('business_support', [0, 1])->default(0);
//            $table->enum('Knowledge', [0, 1])->default(0);
//            $table->enum('resources', [0, 1])->default(0);
//            $table->enum('mentoring', [0, 1])->default(0);
//            $table->enum('agree', [0, 1])->default(0);
//
//            $table->string('confirmation_code');
//            $table->boolean('confirmed')->default(config('access.users.confirm_email') ? false : true);
//            $table->rememberToken();
//            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at');
//            $table->softDeletes();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
