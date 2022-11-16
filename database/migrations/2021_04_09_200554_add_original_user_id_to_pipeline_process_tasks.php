<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOriginalUserIdToPipelineProcessTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pipeline_process_tasks', function (Blueprint $table) {
            $table->unsignedInteger('original_user_id')->nullable()->after('assign_by')->comment('i.e original user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pipeline_process_tasks', function (Blueprint $table) {
            $table->dropColumn('original_user_id');
        });
    }
}
