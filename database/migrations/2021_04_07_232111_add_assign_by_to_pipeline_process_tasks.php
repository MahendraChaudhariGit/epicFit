<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignByToPipelineProcessTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pipeline_process_tasks', function (Blueprint $table) {
            $table->unsignedInteger('assign_by')->nullable()->after('user_id')->comment('i.e user_id');
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
            $table->dropColumn(['assign_by']);
        });
    }
}
