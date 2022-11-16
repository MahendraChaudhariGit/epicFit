<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PipelineProcess\Column;
use App\Models\PipelineProcess\Project;
use App\Models\PipelineProcess\PipelineProcessTask;
use App\Staff;
use App\Clients;
use DB;
use Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->where('business_id',55)->get()->toArray();
        $staffs = Staff::where('business_id',55)->get()->toArray();
        $total_projects = Project::pluck('id')->toArray();
        $columns = Column::whereIn('project_id',$total_projects)->pluck('id')->toArray();
        // $tasks = PipelineProcessTask::whereIn('column_id',$columns)->with('child','assignUser','comments','clients')->where('due_date','!=',null)->get();

        $sub_task = PipelineProcessTask::whereIn('column_id',$columns)->pluck('id');
        $tasks = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
            $q->select('id','firstname','lastname');
        }])->where('due_date','!=',null)->get();
        // dd($tasks);
        return view('PipelineProcess.Calendar.index',compact('clients','tasks','staffs'));
    }
}
