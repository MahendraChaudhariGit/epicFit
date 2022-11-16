<?php

namespace App\Http\Controllers\SuperAdmin;

use App\CalendarSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PipelineProcess\Project;
use App\Models\PipelineProcess\ProjectMember;
use Illuminate\Support\Carbon;
use App\Models\PipelineProcess\Column;
use App\Clients;
use App\Staff;
use App\Http\Traits\HelperTrait;
use App\Models\PipelineProcess\PipelineProcessTask;
use App\Models\PipelineProcess\Attachment;
use App\Models\PipelineProcess\Comment;
use App\SalesProcessProgress;
use App\StaffEventClass;
use App\StaffEventSingleService;
use Auth;
use DB;
use Dotenv\Regex\Success;

class ProjectController extends Controller
{
    use HelperTrait;
    public function index(Request $request){
        $status = "";
        $visibility = "";
        $projects = Project::with('columns','columns.pipeline_process_tasks');
        if ($request->status === 'ongoing') {
            $projects = $projects->whereDate('start_date', '<=', date('Y-m-d'));
            $projects = $projects->whereDate('end_date', '>', date('Y-m-d'));
            $projects = $projects->whereNull('completed_at');
        }
        if ($request->status === 'overdue') {
            $projects = $projects->whereDate('end_date','<=', date('Y-m-d'));
            $projects = $projects->whereNull('completed_at');
        }
        if ($request->status === 'completed') {
            $projects = $projects->whereNotNull('completed_at');
        }
        if ($request->status === 'archived') {
            $projects = $projects->where('archive',1);
        }else{
            $projects = $projects->where('archive',0);
        }
        if($request->visibility1){
            $projects = $projects->where('visibility', $request->visibility1);
        }
        $projects = $projects->latest()
        ->paginate(10);
        $status = $request->status;
        $visibility = $request->visibility1;
         $staffs = Staff::where('business_id',55)->get()->toArray();
         $staff_id = Staff::where('business_id',55)->pluck('id')->toArray();
        return view('PipelineProcess.Projects.index',compact('projects','staffs','status','visibility','staff_id'));
    }

    public function filterProject(Request $request){
        $projects = Project::with('columns','columns.pipeline_process_tasks');
        if($request->search){
            $projects = $projects->where('name', 'LIKE', "%$request->search%")
                ->orWhere('description', 'LIKE', "%$request->search%");
        }
        $projects = $projects->where('archive',0);
        $projects = $projects->latest()
        ->paginate(10);
        return view('PipelineProcess.Projects.filter-project',compact('projects'));
    }

    public function projectDetail($slug){

        $project_id = Project::where('slug',$slug)->pluck('id')->toArray();
        $column_id = Column::whereIn('project_id', $project_id)->pluck('id')->toArray();
        $client_id = PipelineProcessTask::whereIn('column_id',$column_id)->pluck('content')->toArray();
        $staffs = Staff::where('business_id',55)->get()->toArray();
        $staff_id = Staff::where('business_id',55)->pluck('id')->toArray();
        $all_clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->where('business_id',55)->get()->toArray();
        $clients = Clients::select(DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"),'id')->where('business_id',55)->whereNotIn('id',$client_id)->get()->toArray();
        $project = Project::with(['columns'=>function($q){
            $q->orderBy('index','asc');
        }],'columns.pipeline_process_tasks','columns.pipeline_process_tasks.comments','pipeline_process_tasks.assignUser','projectMember')
            ->where('slug', $slug)
            ->firstOrFail();
        if($project){
            return view('PipelineProcess.Projects.project-detail',compact('all_clients','project','clients','staffs','staff_id'));
        }else{
            return redirect('pipeline-process/projects');
        }
        
    }


    /**
     * Store new project.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $member_id_array = explode(',',$request->staff_member_id[0]);
        $this->validate($request, [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'visibility'  => ['required'],
            'color'       => ['required'],
        ]);
        
        $slug_url = null;
        $slugs = explode(" ",strtolower($request->input('name')));
        if(count($slugs) > 0){
            $slug_url = implode("-",($slugs));
        }
        $project = Project::create([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'color'       => $request->input('color'),
            'visibility'  => $request->input('visibility'),
            'slug'        => $slug_url,
            // 'user_id'     => Auth::user()->id,
        ]);
        if(!empty($member_id_array) && $member_id_array[0] != "")
        {
            foreach ($member_id_array as $key => $member_id) 
            {
                $project_member = new ProjectMember();
                $project_member->project_id = $project->id;
                $project_member->member_id = $member_id;
                $project_member->save();
            }
        }


        $this->updateProjectTimeline($project, $request);

        session()->flash('message', 'The project has been created successfully');

        return back();
    }

    /**
     * Update an existing project.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $member_id_array = explode(',',$request->staff_member_id[0]);
        $this->validate($request, [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'visibility'  => ['required'],
            'color'       => ['required']
        ]);

        $project = Project::findOrFail($request->id);
        // dd($project);
        // $this->authorize('update', $project);
        $slug_url = null;
        $slugs = explode(" ",strtolower($request->input('name')));
        if(count($slugs) > 0){
            $slug_url = implode("-",($slugs));
        }
        $project->update([
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
            'color'       => $request->input('color'),
            'visibility'  => $request->input('visibility'),
            'slug'        => $slug_url,
        ]);

        if(isset($request->visibility) && $request->visibility == '2')
        {
            ProjectMember::where('project_id',$request->id)->delete();
        }
        else
        {
            ProjectMember::where('project_id',$request->id)->delete();
            if(!empty($member_id_array) && $member_id_array[0] != "")
            {
                foreach ($member_id_array as $key => $member_id) 
                {
                    $project_member = new ProjectMember();
                    $project_member->project_id = $project->id;
                    $project_member->member_id = $member_id;
                    $project_member->save();
                }
            }
        }
        $this->updateProjectTimeline($project, $request);

        session()->flash('message', 'The project has been updated successfully');

        return redirect('pipeline-process/projects/'.$slug_url);
    }

    /**
     * Delete an existing project.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Request $request)
    {
        $project = Project::withTrashed()->findOrFail($request->project);

        if ($project->trashed()) {
            $this->authorize('forceDelete', $project);
            $project->forceDelete();
            session()->flash('message', 'The project has been deleted successfully');
        } else {
            $this->authorize('delete', $project);
            $project->delete();
            session()->flash('message', 'The project has been archived successfully');
        }

        return redirect()->route('projects.index');
    }

     /**
     * Update the start and end dates of the project.
     *
     * @param \App\Models\Project $project
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function updateProjectTimeline($project, $request)
    {
        if($request->input('timeline') !== null) {
            $date = explode(" to ",$request->input('timeline'));
            $start = $date[0];
            $end = $date[1];
            return $project->update([
                'start_date' => $start,
                'end_date'   => $end,
            ]);
        }

        return $project->update([
            'start_date' => null,
            'end_date'   => null,
        ]);
    }

    /**
     * Mark the specified project as completed.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function projectComplete(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        // $this->authorize('update', $project);

        $project->markAsCompleted();

        session()->flash('message', 'The project has been updated');

        return back();
    }

    /**
     * Mark the specified project as incomplete.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function projectIncomplete(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // $this->authorize('update', $project);

        $project->markAsIncomplete();

        session()->flash('message', 'The project has been updated');

        return back();
    }

    public function projectAddFavorite(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        if($project){
            $project->update(['favorite'=>1]);
            session()->flash('message', 'Project added as favorite');
        }
        else{
            session()->flash('message', 'Project not found');
        }
        return back();
    }
    public function projectRemoveFavorite(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        if($project){
            $project->update(['favorite'=>0]);
            session()->flash('message', 'Project remove from favorite');
        }
        else{
            session()->flash('message', 'Project not found');
        }
        return back();
    }

    public function projectAddArchive(Request $request)
    {
        $project = Project::findOrFail($request->id);
        if($project){
            $project->update(['archive'=>1]);
            session()->flash('message', 'Project added as archive');
        }
        else{
            session()->flash('message', 'Project not found');
        }
        return back();
    }
    public function projectRestore(Request $request)
    {
        $project = Project::findOrFail($request->id);
        if($project){
            $project->update(['archive'=>0]);
            session()->flash('message', 'Project restore from archive');
        }
        else{
            session()->flash('message', 'Project not found');
        }
        return back();
    }

    public function projectDelete(Request $request)
    {
         Project::findOrFail($request->id)->delete();
         exit;
    }

    public function updateProjectColumnsState(Request $request)
    {
        $columns_ids_array = explode(',',$request->project_columns_ids);
        foreach ($columns_ids_array as $key => $column_id) 
        {
            $column = Column::where('id',$column_id)->first();
            if($column)
            {
                $data['index'] = $key;
                $column->update($data);
            }
        }
    }

    public function updateColumnTasksState(Request $request)
    {
        $tasks_ids_array = explode(',',$request->column_tasks_ids);
        if(!empty($tasks_ids_array))
        {
            $tasks = [];
            foreach ($tasks_ids_array as $key => $task_id) 
            {
                $task = PipelineProcessTask::select('id','sales_group','total_sales','column_id','index','completed_at')->where('id',$task_id)->first();
                $tasks[$key] = $task;
                if($task)
                {
                    $data['column_id'] = $request->column_id;
                    $data['index'] = $key;
                    $task->update($data);
                }
            }
        }
        // dd($request->task_id);
        if($request->task_id){
            $task_details = PipelineProcessTask::select('id','sales_group','total_sales','column_id','index','completed_at')->where('id',$request->task_id)->first();
            $tasks[$key+1] = $task_details;
        }
        return response()->json($tasks);
    }

    public function updateLeaveColumnTasksState(Request $request)
    {
        // dd($request->all());
        if($request->drag_column_tasks_array && $request->movable_column_id){
            $tasks_ids_array = explode(',',$request->drag_column_tasks_array);
            if(!empty($tasks_ids_array))
            {
                $tasks = [];
                foreach ($tasks_ids_array as $key => $task_id) 
                {
                    $task = PipelineProcessTask::select('id','sales_group','total_sales','column_id','index','completed_at')->where('id',$task_id)->first();
                    $tasks[$key] = $task;
                    if($task)
                    {
                        $data['column_id'] = $request->movable_column_id;
                        $data['index'] = $key;
                        $task->update($data);
                    }
                }
            }
        }

        if($request->drop_column_tasks_array && $request->second_column_id){
            $tasks_ids_array = explode(',',$request->drop_column_tasks_array);
            if(!empty($tasks_ids_array))
            {
                $tasks = [];
                foreach ($tasks_ids_array as $key => $task_id) 
                {
                    $task = PipelineProcessTask::select('id','sales_group','total_sales','column_id','index','completed_at')->where('id',$task_id)->first();
                    $tasks[$key] = $task;
                    if($task)
                    {
                        $data['column_id'] = $request->second_column_id;
                        $data['index'] = $key;
                        $task->update($data);
                    }
                }
            }
        }
        
        // dd($request->task_id);
        // if($request->task_id){
        //     $task_details = PipelineProcessTask::select('id','sales_group','total_sales','column_id','index','completed_at')->where('id',$request->task_id)->first();
        //     $tasks[$key+1] = $task_details;
        // }
        return response()->json($tasks);
    }

    public function getAssignedProjectMember(Request $request)
    {
        $assigned_member = ProjectMember::where('project_id',$request->project_id)->get()->toArray();
        if(!empty($assigned_member))
        {
            return response()->json([
            'message'=>'Done',
            'status'=> true,
            'data'=> $assigned_member
            ]);
        }
        else
        {
            return response()->json([
            'message'=>'Done',
            'status'=> false,
            ]);
        }
    }

    public function deleteSelectedProject(Request $request)
    {
       if(!empty($request->project_ids_array))
       {
            foreach ($request->project_ids_array as $key => $project_id) 
            {
                
                $columns = Column::where('project_id',$project_id)->get();
                if(count($columns) > 0){
                    foreach($columns as $column){
                        $tasks = PipelineProcessTask::where('column_id',$column->id)->get();
                        if($tasks){
                            foreach($tasks as $task){
                                PipelineProcessTask::where('task_id',$task->id)->delete();
                            }
                        }
                        PipelineProcessTask::where('column_id',$column->id)->delete();
                    }
                }
                Column::where('project_id',$project_id)->delete();
                Project::where('id',$project_id)->delete();
            }
            return response()->json([
            'message'=>'Deleted successfully!!',
            'status'=> true,
            ]);
       }
    }

    public function salesProcessStep(Request $request){
        if($request->stepIndex == 2 || $request->stepIndex == 4 || $request->stepIndex == 6){
            $stepType = 'booked';
        }else if($request->stepIndex == 3 || $request->stepIndex == 5 ){
            $stepType = 'attend';

        }
        if($request->stepIndex == 7){
            $request->stepIndex = 11;
            $stepType = 'attend';
            $bookType = 'team';
        }else if($request->stepIndex == 8){
            $request->stepIndex = 7;
            $stepType = 'book';
            $bookType = 'team';
        }else if($request->stepIndex == 9){
            $request->stepIndex = 23;
            $stepType = 'attend';
            $bookType = 'team';
        }else if($request->stepIndex == 10){
            $request->stepIndex = 8;
            $stepType = 'book';
            $bookType = 'team';
        }else if($request->stepIndex == 11){
            $request->stepIndex = 24;
            $stepType = 'attend';
            $bookType = 'team';
        }else if($request->stepIndex == 12){
            $request->stepIndex = 9;
            $stepType = 'book';
            $bookType = 'team';
        }else if($request->stepIndex == 13){
            $request->stepIndex = 25;
            $stepType = 'attend';
            $bookType = 'team';
        }else if($request->stepIndex == 14){
            $request->stepIndex = 10;
            $stepType = 'book';
            $bookType = 'team';
        }else if($request->stepIndex == 15){
            $request->stepIndex = 26;
            $stepType = 'attend';
            $bookType = 'team';
        }else if($request->stepIndex == 16){
            $request->stepIndex = 12;
            $stepType = 'book';
            $bookType = 'indiv';
        }else if($request->stepIndex == 17){
            $request->stepIndex = 17;
            $stepType = 'attend';
            $bookType = 'indiv';
        }else if($request->stepIndex == 18){
            $request->stepIndex = 13;
            $stepType = 'book';
            $bookType = 'indiv';
        }else if($request->stepIndex == 19){
            $request->stepIndex = 19;
            $stepType = 'attend';
            $bookType = 'indiv';
        }else if($request->stepIndex == 20){
            $request->stepIndex = 14;
            $stepType = 'book';
            $bookType = 'indiv';
        }else if($request->stepIndex == 21){
            $request->stepIndex = 20;
            $stepType = 'attend';
            $bookType = 'indiv';
        }else if($request->stepIndex == 22){
            $request->stepIndex = 15;
            $stepType = 'book';
            $bookType = 'indiv';
        }else if($request->stepIndex == 23){
            $request->stepIndex = 21;
            $stepType = 'attend';
            $bookType = 'indiv';
        }else if($request->stepIndex == 24){
            $request->stepIndex = 16;
            $stepType = 'book';
            $bookType = 'indiv';
        }else if($request->stepIndex == 25){
            $request->stepIndex = 22;
            $stepType = 'attend';
            $bookType = 'indiv';
        }else if($request->stepIndex == 26){
            $request->stepIndex = 18;
        }
        if($request->has('stepIndex')){

            $SalesProcessProgress = SalesProcessProgress::where('spp_client_id',$request->clientId)->where('spp_step_numb',$request->stepIndex)->whereNull('deleted_at')->latest()->first();
        }
        if($SalesProcessProgress){
            $msg['status'] = 'success'; 
            $msg['stepNumb'] = $request->stepIndex;
            $msg['stepType'] = $stepType;
            $msg['bookType'] = $bookType;

           $msg['bookData'] = $this->findClientStep($request->clientId,$request->stepIndex);

         
        }else{
            $msg['status'] = 'failed'; 
            $msg['stepNumb'] = $request->stepIndex;
            $msg['stepType'] = $stepType;
            $msg['bookType'] = $bookType;
        }

        return $msg;
       
    }
    public function findClientStep($id,$stepNumb){
        $return = [];
        if($stepNumb == 6 || $stepNumb == 7 || $stepNumb == 8 || $stepNumb == 9 || $stepNumb == 10){
            
            
            $classId =  DB::table('staff_event_class_clients')->select('secc_sec_id')->where('secc_client_id',$id)->where('sales_step_number', $stepNumb)->whereNull('deleted_at')->first();
            $class = StaffEventClass::where('sec_id',$classId->secc_sec_id)->first();
            $return['bookDate'] = dbDateToDateString($class->sec_date);
            $classData = $class->clas;
            $return['name'] = $classData->cl_name;
            $return['time'] = date("h:i A", strtotime($class->sec_time));;
            $return['duration'] = $class->sec_duration;
        }else if($stepNumb == 2 || $stepNumb == 4 || $stepNumb == 12 || $stepNumb == 13 || $stepNumb == 14 || $stepNumb == 15 || $stepNumb == 16){
            $serviceDate =  StaffEventSingleService::where('sess_client_id',$id)->where('sales_step_number', $stepNumb)->whereNull('deleted_at')->first();
            $serviceName = $serviceDate->service->name;
            $return['bookDate'] =dbDateToDateString($serviceDate->sess_date);
            $return['name'] = $serviceName;
            $return['time'] =date("h:i A", strtotime( $serviceDate->sess_time));;
            $return['duration'] = $serviceDate->sess_duration;

        }
        return $return;
        
    }
   
}
