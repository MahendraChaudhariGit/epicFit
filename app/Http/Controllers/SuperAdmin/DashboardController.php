<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PipelineProcess\PipelineProcessTask;
use App\Models\PipelineProcess\Project;
use App\Models\PipelineProcess\Column;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = Project::where('archive',0)->count();
        $total_projects = Project::where('archive',0)->pluck('id')->toArray();
        $columns = Column::whereIn('project_id',$total_projects)->pluck('id')->toArray();
        $pending_tasks = PipelineProcessTask::whereIn('column_id',$columns)->whereNotNull('due_date')->where('completed_at','=', NULL)->count();
        $complete_tasks = PipelineProcessTask::whereIn('column_id',$columns)->whereNotNull('due_date')->where('completed_at','!=', NULL)->count();
        $total_tasks = PipelineProcessTask::whereIn('column_id',$columns)->whereNotNull('due_date')->count();

        return view('PipelineProcess.Dashboard.index',compact('projects','pending_tasks','complete_tasks','total_tasks'));
    }
}
