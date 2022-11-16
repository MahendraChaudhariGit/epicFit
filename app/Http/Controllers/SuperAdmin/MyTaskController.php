<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PipelineProcess\Column;
use App\Models\PipelineProcess\Attachment;
use App\Models\PipelineProcess\Comment;
use App\Models\PipelineProcess\Project;
use App\Models\PipelineProcess\PipelineProcessTask;
use App\Staff;
use App\Clients;
use Response;
use Carbon\Carbon;
use Auth;
use DB;

class MyTaskController extends Controller
{
    public function index(){
        $staffs = Staff::where('business_id',55)->get()->toArray();
        $clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->where('business_id',55)->get()->toArray();
        $projects = Project::select('id','name','color')->where('archive',0)->get();
        $total_task = PipelineProcessTask::with('column.project')
            ->whereHas('column.project', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->whereNull('completed_at')
            ->get()->count();
        // dd($total_task);
        $total_projects = Project::pluck('id')->toArray();
        $columns = Column::whereIn('project_id',$total_projects)->pluck('id')->toArray();
        $sub_task = PipelineProcessTask::whereIn('column_id',$columns)->pluck('id');
        // dd($sub_task);
        $todays = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
            $q->select('id','firstname','lastname');
        }])
        // $todays = PipelineProcessTask::with('column.project','clients')
            
        //     ->whereHas('column.project', function ($query) {
        //         $query->whereNull('deleted_at');
        //     })
            ->orderBy('due_date', 'ASC')
            ->where('due_date', Carbon::today())
            ->whereNull('completed_at')
            ->get();
            // dd($todays);
            $upcomings = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            
            // ->whereHas('column.project', function ($query) {
            //     $query->whereNull('deleted_at');
            // })
            ->orderBy('due_date', 'ASC')
            ->where('due_date', '>', Carbon::today())
            ->whereNull('completed_at')
            ->get();
        // dd($upcomings);

        $no_due_dates = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
            $q->select('id','firstname','lastname');
        }])
            
            // ->whereHas('column.project', function ($query) {
            //     $query->whereNull('deleted_at');
            // })
            ->orderBy('due_date', 'ASC')
            ->whereNull('due_date')
            ->orWhere('due_date', '0000-00-00')
            ->whereNull('completed_at')
            ->get();
        // dd($no_due_dates);

        // $completes = PipelineProcessTask::with('column.project')
        //     ->orderBy('due_date', 'ASC')
        //     ->whereHas('column.project', function ($query) {
        //         $query->whereNull('deleted_at');
        //     })
        //     ->whereNotNull('completed_at')
        //     ->get();

        $overdues = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
            $q->select('id','firstname','lastname');
        }])
            
            // ->whereHas('column.project', function ($query) {
            //     $query->whereNull('deleted_at');
            // })
            ->orderBy('due_date', 'ASC')
            ->where('due_date','!=', '0000-00-00')
            ->where('due_date','<', Carbon::today())
            ->whereNull('completed_at')
            ->get();

        return view('PipelineProcess.MyTasks.index',compact('clients','total_task', 'staffs','projects','todays','upcomings','no_due_dates','overdues'));
    }

    public function filterTask($time){
        $clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->where('business_id',55)->get()->toArray();
        $staffs = Staff::where('business_id',55)->get()->toArray();
        $projects = Project::select('id','name','color')->get();
        $total_task = PipelineProcessTask::with('column.project')
            ->whereHas('column.project', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->whereNull('completed_at')
            ->get()->count();
            $total_projects = Project::pluck('id')->toArray();
            $columns = Column::whereIn('project_id',$total_projects)->pluck('id')->toArray();
            $sub_task = PipelineProcessTask::whereIn('column_id',$columns)->pluck('id');
        $todays = null;
        if ($time === 'today') {
            $todays = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            // $todays = PipelineProcessTask::with('column.project','clients')
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) {
            //     $query->whereNull('deleted_at');
            // })
            ->where('due_date', Carbon::today())
            ->whereNull('completed_at')
            ->get();
        }

        $upcomings = null;
        if ($time === 'upcoming') {
            $upcomings = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            // $upcomings = PipelineProcessTask::with('column.project','clients')
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) {
            //     $query->whereNull('deleted_at');
            // })
            ->where('due_date', '>', Carbon::today())
            ->whereNull('completed_at')
            ->get();
        }

        $no_due_dates = null;
        if ($time === 'no_overdue') {
            $no_due_dates = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            // $no_due_dates = PipelineProcessTask::with('column.project','clients')
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) {
            //     $query->whereNull('deleted_at');
            // })
            ->whereNull('due_date')
            ->orWhere('due_date', '0000-00-00')
            ->whereNull('completed_at')
            ->get();
        }

        $completes = null;
        if ($time === 'completed') {
            $completes = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            // $completes = PipelineProcessTask::with('column.project','clients')
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) {
            //     $query->whereNull('deleted_at');
            // })
            ->whereNotNull('completed_at')
            ->get();
        }
        // dd($completes);

        $overdues = null;
        if ($time === 'overdue') {
            $overdues = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            // $overdues = PipelineProcessTask::with('column.project','clients')
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) {
            //     $query->whereNull('deleted_at');
            // })
            ->where('due_date','!=', '0000-00-00')
            ->where('due_date','<', Carbon::today())
            ->whereNull('completed_at')
            ->get();
        }
        return view('PipelineProcess.MyTasks.index',compact('clients','total_task','staffs','projects','todays','upcomings','no_due_dates','completes','overdues'));
    }

    public function projectTask($project){
        $clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->where('business_id',55)->get()->toArray();
        $staffs = Staff::where('business_id',55)->get()->toArray();
        $projects = Project::select('id','name','color')->get();
        $total_task = PipelineProcessTask::with('column.project')
            ->whereHas('column.project', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->whereNull('completed_at')
            ->get()->count();
            // $total_projects = Project::pluck('id')->toArray();
            $columns = Column::where('project_id',$project)->pluck('id')->toArray();
            $sub_task = PipelineProcessTask::whereIn('column_id',$columns)->pluck('id');
        $todays = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) use ($project) {
            //     $query->whereNull('deleted_at')->where('id','=',$project);
            // })
            ->where('due_date', Carbon::today())
            ->whereNull('completed_at')
            ->get();

        $upcomings = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) use ($project) {
            //     $query->whereNull('deleted_at')->where('id','=',$project);
            // })
            ->where('due_date', '>', Carbon::today())
            ->whereNull('completed_at')
            ->get();
            
        $no_due_dates = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) use ($project) {
            //     $query->whereNull('deleted_at')->where('id','=',$project);
            // })
            ->whereNull('due_date')
            ->orWhere('due_date', '0000-00-00')
            ->whereNull('completed_at')
            ->get();

        $completes = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) use ($project) {
            //     $query->whereNull('deleted_at')->where('id','=',$project);
            // })
            ->whereNotNull('completed_at')
            ->get();

        $overdues = PipelineProcessTask::whereIn('task_id',$sub_task)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
                $q->select('id','firstname','lastname');
            }])
            ->orderBy('due_date', 'ASC')
            // ->whereHas('column.project', function ($query) use ($project) {
            //     $query->whereNull('deleted_at')->where('id','=',$project);
            // })
            ->where('due_date','!=', '0000-00-00')
            ->where('due_date','<', Carbon::today())
            ->whereNull('completed_at')
            ->get();

        return view('PipelineProcess.MyTasks.index',compact('clients','total_task','parent.column','staffs','projects','todays','upcomings','no_due_dates','completes','overdues'));
    }

    public function taskPopup(Request $request){
        $clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->where('business_id',55)->get()->toArray();
        $staffs = Staff::where('business_id',55)->get()->toArray();
        $task_id = $request->task_id;
        $sub_task_id = $request->sub_task_id;
        $task =  PipelineProcessTask::where('id',$sub_task_id)->with(['parent','parent.column','parent.comments','parent.clients'=>function($q){
            $q->select('id','firstname','lastname');
        }])->first();
        // dd($task);
        return view('PipelineProcess.MyTasks.task-popup',compact('clients','staffs','task','sub_task_id'));
    }

}
