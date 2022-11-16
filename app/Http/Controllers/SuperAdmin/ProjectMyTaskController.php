<?php

namespace App\Http\Controllers\SuperAdmin;

use App\CalendarSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\PipelineProcess\Column;
use App\Models\PipelineProcess\Attachment;
use App\Models\PipelineProcess\Comment;
use App\Models\PipelineProcess\Project;
use App\Models\PipelineProcess\EpicProcess;
use App\Staff;
use DB;
use App\Clients;
use App\Models\PipelineProcess\PipelineProcessTask;
use App\SalesProcessProgress;
use App\Http\Traits\SalesProcessProgressTrait;
use App\Http\Traits\SalesProcessTrait;
use App\StaffEventClass;
use App\StaffEventSingleService;
use Response;
use Auth;

class ProjectMyTaskController extends Controller
{
    use SalesProcessProgressTrait;

    /**
     * Store new task.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        
        $this->validate($request, [
            'content' => ['required', 'max:255'],
        ]);
        // dd($request->all());
        $column = Column::findOrFail($request->id);

        // $this->authorize('create', [PipelineProcessTask::class, $column->project]);

        $column->pipeline_process_tasks()->create([
            'content' => $request->input('content'),
            'index'   => $column->pipeline_process_tasks->count(),
        ]);

        return back();
    }

    /**
     * Update task.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request)
    {
        // dd($request->all());
        // $this->validate($request, [
        //     'content' => ['required', 'max:255'],
        // ]);

        $task = PipelineProcessTask::findOrFail($request->input('id'));
        
        // $this->authorize('update', $task);
        if(!empty($request->input('task_name'))){
            $task->update([
                'content'  => $request->input('task_name'),
            ]);
            $client = Clients::select('id','firstname','lastname')->where('id',$task->content)->first();
            if(isset($client)){
                $data = array('task_name'=> $client->firstname.' '.$client->lastname);
            }else{
                $data = array('task_name'=> $request->input('task_name'));
            }
            return response()->json($data);
        }

        if(!empty($request->input('priority'))){
            $task->update([
                'priority'  => $request->input('priority'),
            ]);
            if($task->priority == 4){
                $priority = 'Urgent';
            }elseif($task->priority == 3){
                $priority = 'High';
            }elseif($task->priority == 2){
                $priority = 'Medium';
            }else{
                $priority = 'Low';
            }
            $data = array('priority'=> $priority,'priority_no'=>$task->priority);

            return response()->json($data);
        }

        if(!empty($request->input('duedate'))){
            $task->update([
                'due_date'  => $request->input('duedate'),
            ]);
            $data = array('duedate'=> $task->due_date);

            return response()->json($data);
        }

        if ($request->input('is_completed') == '1') {
            $task->markAsCompleted();
            $data = array('completed_at'=> $task->completed_at);
            $staffs = Staff::where('business_id',55)->get()->toArray();
            $parent_task_id = $request->task_id;
            $pendingTasks = PipelineProcessTask::with('assignUser')->where('task_id',$request->task_id)->where('completed_at','=', NULL)->get()->toArray();
            $completeTasks = PipelineProcessTask::with('assignUser')->where('task_id',$request->task_id)->where('completed_at','!=', NULL)->get()->toArray();
            return view('PipelineProcess.Projects.sub-task',compact('pendingTasks','completeTasks','staffs','parent_task_id'));
            // return response()->json($data);
        } 
        if ($request->input('is_completed') == '0') {
            $task->markAsIncomplete();
            $data = array('completed_at'=> $task->completed_at);
            $staffs = Staff::where('business_id',55)->get()->toArray();
            $parent_task_id = $request->task_id;
            $pendingTasks = PipelineProcessTask::with('assignUser')->where('task_id',$request->task_id)->where('completed_at','=', NULL)->get()->toArray();
            $completeTasks = PipelineProcessTask::with('assignUser')->where('task_id',$request->task_id)->where('completed_at','!=', NULL)->get()->toArray();
            return view('PipelineProcess.Projects.sub-task',compact('pendingTasks','completeTasks','staffs','parent_task_id'));
            // return response()->json($data);
        }

        return back();
    }

    /**
     * Delete task.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        // dd($request->id);
        
        $task = PipelineProcessTask::findOrFail($request->id);
        if(isset($task)){
            $comment = Comment::where('pipeline_process_task_id',$task->id)->pluck('id');
            if(count($comment)){
                Attachment::whereIn('comment_id',$comment)->delete();
                Comment::where('pipeline_process_task_id',$task->id)->delete();
            }
            $sub_task = PipelineProcessTask::where('task_id',$task->id)->delete();
            $task->delete();
        }
        

        return back();
    }

    public function taskComment(Request $request)
    {
        $this->validate($request, [
            'content' => 'required',
        ]);

        $task = PipelineProcessTask::findOrFail($request->id)->comments()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->user()->id,
        ]);
        if($task){
            if(!empty($request->filename)){
                $file_name = array();
                foreach($request->filename as $key=>$filename){
                    $file = null;
                    if ($request->hasFile('filename')) {
                        $image = $request->file('filename')[$key];
                        $filename = 'filename'.$key.time().'.'.$image->getClientOriginalExtension();
                        $destinationPath = public_path('/attachment-file');
                        $image->move($destinationPath, $filename);
                        $file = $filename;
                    }
                    $file_name[$key]=[
                        'comment_id' => $task->id,
                        'filename' => $file,
                    ];
                }
                // dd($file_name);
                Attachment::insert($file_name);
            }
        }
        $task_id = $request->id;
        $data = Comment::where('pipeline_process_task_id',$request->id)->get();
        $comments = Comment::where("pipeline_process_task_id",$request->id)->pluck('id')->toArray();
        $attachments = Attachment::whereIn("comment_id",$comments)->get()->toArray();
        // $data = PipelineProcessTask::findOrFail($request->id)->comments->sortByDesc('created_at')->transform(function ($comment) {
        //     return [
        //         'id'          => $comment->id,
        //         'content'     => $comment->content,
        //         'created_at'  => $comment->created_at->diffForHumans(),
        //     ];
        // })->values();
        
        return view('PipelineProcess.Projects.attachment',compact('data','attachments','task_id'));


    }

    public function subTask(Request $request)
    {
        $this->validate($request, [
            'content' => ['required', 'max:255'],
        ]);

        $task = PipelineProcessTask::where('id', $request->id)->firstOrFail();

        // $this->authorize('create', $task);

        $subTask = PipelineProcessTask::create([
            'content' => $request->input('content'),
            'task_id' => $task->id,
            'user_id' => $request->user_id,
            'due_date' => $request->due_date,
        ]);
        $parent_task_id = $task->id;
        $staffs = Staff::where('business_id',55)->get()->toArray();
        $pendingTasks = PipelineProcessTask::with('assignUser')->where('task_id',$task->id)->where('completed_at','=', NULL)->get()->toArray();
        $completeTasks = PipelineProcessTask::with('assignUser')->where('task_id',$task->id)->where('completed_at','!=', NULL)->get()->toArray();
        return view('PipelineProcess.Projects.sub-task',compact('pendingTasks','completeTasks','staffs','parent_task_id'));
        // return back();
    }

    public function download(Request $request, $filename){
        $filepath = public_path('attachment-file/').$filename;
        return Response::download($filepath);
    }

    public function assignUser(Request $request)
    {
        $task = PipelineProcessTask::where('id',$request->task_id)->first();
        if($task)
        {
            $data['user_id'] = $request->user_id;
            $task->update($data);
            $staff = Staff::find($request->user_id);
            return response()->json([
                'staff' => $staff,
                'status'=> true,
            ]);
        }
    }

    public function changeTaskColumn(Request $request)
    {
        $task = PipelineProcessTask::where('id',$request->task_id)->first();
        if($task)
        {
            $data['column_id'] = $request->column_id;
            $task->update($data);
            if($task)
            {
                return response()->json([
                'message'=>'Status updated successfully !!',
                'status'=> true,
                ]);
            }
        }
    }

    // public function sales_preference_add(Request $request){
    //     $sales_preferences = null;
    //     $task = PipelineProcessTask::findOrFail($request->task_id);
    //     if($task){
    //         if(!empty($task->task_id)){
    //             $sub_task = PipelineProcessTask::findOrFail($task->task_id);
    //             $sales_preferences = $sub_task->sales_group;
    //             $sales_preferences = $sales_preferences != null ? $sales_preferences.','.$request->group : $request->group;
    //             $sales_preferences = str_replace(',,',",",$sales_preferences);
    //             $sales_preferences = ltrim($sales_preferences, ',');
    //             $sales_preferences = rtrim($sales_preferences, ',');
    //             $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
    //             $sub_task->update(['sales_group' => $sales_preferences,'total_sales'=>$request->count]);
    //         }else{
    //             $sales_preferences = $task->sales_group;
    //             $sales_preferences = $sales_preferences != null ? $sales_preferences.','.$request->group : $request->group;
    //             $sales_preferences = str_replace(',,',",",$sales_preferences);
    //             $sales_preferences = ltrim($sales_preferences, ',');
    //             $sales_preferences = rtrim($sales_preferences, ',');
    //             $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
    //             $task->update(['sales_group' => $sales_preferences,'total_sales'=>$request->count]);
    //         }
            
    //     }
    //     // return response()->json($task->sales_group);
    //     $task = PipelineProcessTask::find($request->task_id);
    //     $column = Column::find($request->column_id);
    //     return view('PipelineProcess.Projects.drag-drop',compact('task','column'));

    // }

    // public function sales_preference_remove(Request $request){
    //     $sales_preferences = null;
    //     $task = PipelineProcessTask::findOrFail($request->task_id);
    //     if($task){
    //         if(!empty($task->task_id)){
    //             $sub_task = PipelineProcessTask::findOrFail($task->task_id);
    //             $sales_preferences = $sub_task->sales_group;
    //             $sales_preferences = $sales_preferences != null ? str_replace($request->group,"",$sales_preferences) : null;
    //             $sales_preferences = str_replace(',,',",",$sales_preferences);
    //             $sales_preferences = ltrim($sales_preferences, ',');
    //             $sales_preferences = rtrim($sales_preferences, ',');
    //             $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
    //             $sub_task->update(['sales_group' => $sales_preferences,'total_sales'=>$request->count]);
    //         }else{
    //             $sales_preferences = $task->sales_group;
    //             $sales_preferences = $sales_preferences != null ? str_replace($request->group,"",$sales_preferences) : null;
    //             $sales_preferences = str_replace(',,',",",$sales_preferences);
    //             $sales_preferences = ltrim($sales_preferences, ',');
    //             $sales_preferences = rtrim($sales_preferences, ',');
    //             $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
    //             $task->update(['sales_group' => $sales_preferences,'total_sales'=>$request->count]);
    //         }
    //     }
    //     // return response()->json($task->sales_group);
    //     $task = PipelineProcessTask::find($request->task_id);
    //     $column = Column::find($request->column_id);
    //     return view('PipelineProcess.Projects.drag-drop',compact('task','column'));
    // }

    public function updateTasksProccess(Request $request){
        $sales_preferences = null;
        // if($request->data_from == 'complimentry'){
        //     $task = PipelineProcessTask::findOrFail($request->task_id);
        //     if($task){
        //         $sales_preferences = $task->sales_group;
        //         $sales_preferences = $sales_preferences != null ? $sales_preferences.','.$request->proccess_data : $request->proccess_data;
        //         $sales_preferences = str_replace(',,',",",$sales_preferences);
        //         $sales_preferences = ltrim($sales_preferences, ',');
        //         $sales_preferences = rtrim($sales_preferences, ',');
        //         $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
        //         $task->update(['sales_group' => $sales_preferences]);
        //     }
        //     // $columns = Column::where('id','>',$request->column_id)->where('id','<',$request->second_column_id)->pluck('id');
        //     // if(count($columns) > 0){
        //     //     $get_column = PipelineProcessTask::where('id',$request->task_id)->whereIn('column_id',$columns)->get();
        //     //     dd($get_column->toArray());
        //     // }
            
        // }
        // if($request->data_from == 'other'){
            $task = EpicProcess::where('pipeline_process_task_id',$request->task_id)->where('column_id',$request->column_id)->first();
            if($task){
                $sales_preferences = $task->sales_group;
                $sales_preferences = $sales_preferences != null ? $sales_preferences.','.$request->proccess_data : $request->proccess_data;
                $sales_preferences = str_replace(',,',",",$sales_preferences);
                $sales_preferences = ltrim($sales_preferences, ',');
                $sales_preferences = rtrim($sales_preferences, ',');
                $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
                $task->update(['sales_group' => $sales_preferences]);
            }else{
                $task = EpicProcess::create([
                    'column_id' => $request->column_id,
                    'pipeline_process_task_id' => $request->task_id,
                    'sales_group' => $request->proccess_data ?? '',
                    'total_sales' => $request->count ?? 0,
                ]);
            }
            if($request->column_id < $request->second_column_id){
                $columns = Column::where('id','>',$request->column_id)->where('id','<',$request->second_column_id)->orderBy('id','asc')->pluck('id');
            }else{
                $columns = Column::where('id','<',$request->column_id)->where('id','>',$request->second_column_id)->orderBy('id','desc')->pluck('id');
            }
            if(count($columns) > 0){
                    foreach($columns as $column){
                        $epic_process1 = EpicProcess::where('pipeline_process_task_id', $request->task_id)->where('column_id', $column)->first();
                        if(isset($epic_process1)){
                            $sales_count = explode(',',$epic_process1->sales_group);
                            $total_sales = $epic_process1->total_sales;
                            if(count($sales_count) != $total_sales){
                                $drag_column_tasks_array = $request->drag_column_tasks_array;
                                $drop_column_tasks_array = $request->drop_column_tasks_array;
                                $initial_column_id = $request->initial_column_id;
                                $task = PipelineProcessTask::findOrFail($request->task_id);
                                $column = Column::find($column);
                                $first_column = Column::find($request->column_id);
                                $second_column = Column::find($request->second_column_id);
                                $client = Clients::select('firstname','lastname','id','sale_process_setts')->where('id',$task->content)->first();
                                return view('PipelineProcess.Projects.draggble-popup',compact('task','column','client','second_column','first_column','initial_column_id','drag_column_tasks_array','drop_column_tasks_array'));
                            }
                            else{
                                if(end($columns->toArray()) == $column){
                                    return response()->json([
                                        'status' => true
                                    ]);
                                }
                            }
                        }else{
                            $drag_column_tasks_array = $request->drag_column_tasks_array;
                            $drop_column_tasks_array = $request->drop_column_tasks_array;
                            $initial_column_id = $request->initial_column_id;
                            $task = PipelineProcessTask::findOrFail($request->task_id);
                            $column = Column::find($column);
                            $first_column = Column::find($request->column_id);
                            $second_column = Column::find($request->second_column_id);
                            // dd($task->toArray(),$column->toArray());
                            $client = Clients::select('firstname','lastname','id','sale_process_setts')->where('id',$task->content)->first();
                            return view('PipelineProcess.Projects.draggble-popup',compact('task','column','client','second_column','first_column','initial_column_id','drag_column_tasks_array','drop_column_tasks_array'));
                        }
                    }
                
                
            }else{
                return response()->json([
                    'status' => true
                ]);
            }
        // }
    }

    public function completeAllTasksProccess(Request $request){
        $sales_preferences = null;
        // if($request->data_from == 'complimentry'){
        //     $task = PipelineProcessTask::findOrFail($request->task_id);
        //     if($task){
        //         $sales_preferences = implode(',',$request->proccess_data);
        //         $task->update(['sales_group' => $sales_preferences]);

        //         $task = PipelineProcessTask::find($request->task_id);
        //         $column = Column::find($request->column_id);
        //         return view('PipelineProcess.Projects.drag-drop',compact('task','column'));
        //     }else{
        //         $sub_task = PipelineProcessTask::find($request->task_id);
        //         if(isset($sub_task)){
        //             $task1 = EpicProcess::where('pipeline_process_task_id',$sub_task->task_id)->where('column_id',$request->column_id)->first();
        //             $sales_preferences = implode(',',$request->proccess_data);
        //             $task1->update(['sales_group' => $sales_preferences]);
        //         }
        //     }
           
        // }
        // if($request->data_from == 'other'){
            $task = EpicProcess::where('pipeline_process_task_id',$request->task_id)->where('column_id',$request->column_id)->first();
            if($task){
                $sales_preferences = implode(',',$request->proccess_data);
                $task->update(['sales_group' => $sales_preferences]);
            }else{
                $sub_task = PipelineProcessTask::find($request->task_id);
                if(isset($sub_task)){
                    $task1 = EpicProcess::where('pipeline_process_task_id',$sub_task->task_id)->where('column_id',$request->column_id)->first();
                    if(isset($task1)){
                        $sales_preferences = implode(',',$request->proccess_data);
                        $task1->update(['sales_group' => $sales_preferences]);
                    }else{
                        EpicProcess::create([
                            'column_id' => $request->column_id,
                            'pipeline_process_task_id' => $sub_task->task_id ?? $request->task_id,
                            'sales_group' => implode(',',$request->proccess_data),
                            'total_sales' => $request->count,
                        ]);
                    }
                    
                }else{
                    EpicProcess::create([
                        'column_id' => $request->column_id,
                        'pipeline_process_task_id' => $request->task_id,
                        'sales_group' => implode(',',$request->proccess_data),
                        'total_sales' => $request->count,
                    ]);
                }
                
            }
            $task = PipelineProcessTask::find($request->task_id);
            $column = Column::find($request->column_id);
            return view('PipelineProcess.Projects.drag-drop',compact('task','column'));
        // }
        
    }

    public function epic_process_add(Request $request){
        $sales_preferences = null;
        $epic_process = EpicProcess::where('pipeline_process_task_id', $request->task_id)->where('column_id', $request->column_id)->first();
        if($epic_process){
            $sales_preferences = $epic_process->sales_group;
            $sales_preferences = $sales_preferences != null ? $sales_preferences.','.$request->group : $request->group;
            $sales_preferences = str_replace(',,',",",$sales_preferences);
            $sales_preferences = ltrim($sales_preferences, ',');
            $sales_preferences = rtrim($sales_preferences, ',');
            $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
            $epic_process->update(['sales_group' => $sales_preferences,'total_sales'=>$request->count]);
        }else{
            $sub_task = PipelineProcessTask::find($request->task_id);
            if(isset($sub_task)){
                $epic_process1 = EpicProcess::where('pipeline_process_task_id', $sub_task->task_id)->where('column_id', $request->column_id)->first();
                if(isset($epic_process1)){
                    $sales_preferences = $epic_process1->sales_group;
                    $sales_preferences = $sales_preferences != null ? $sales_preferences.','.$request->group : $request->group;
                    $sales_preferences = str_replace(',,',",",$sales_preferences);
                    $sales_preferences = ltrim($sales_preferences, ',');
                    $sales_preferences = rtrim($sales_preferences, ',');
                    $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
                    $epic_process1->update(['sales_group' => $sales_preferences,'total_sales'=>$request->count]);
                }else{
                    EpicProcess::create([
                        'column_id' => $request->column_id,
                        'pipeline_process_task_id' => $sub_task->task_id ?? $request->task_id,
                        'sales_group' => $request->group,
                        'total_sales' => $request->count,
                    ]);
                }
                
            }else{
                $epic_process = EpicProcess::create([
                    'column_id' => $request->column_id,
                    'pipeline_process_task_id' => $request->task_id,
                    'sales_group' => $request->group,
                    'total_sales' => $request->count,
                ]);
            }
            
        }
        // return response()->json($epic_process->sales_group);
        $task = PipelineProcessTask::find($request->task_id);
        $column = Column::find($request->column_id);
        return view('PipelineProcess.Projects.drag-drop',compact('task','column'));
    }

    public function epic_process_remove(Request $request){
        $sales_preferences = null;
        $epic_process = EpicProcess::where('pipeline_process_task_id', $request->task_id)->where('column_id', $request->column_id)->first();
        if($epic_process){
            $sales_preferences = $epic_process->sales_group;
            $sales_preferences = $sales_preferences != null ? str_replace($request->group,"",$sales_preferences) : null;
            $sales_preferences = str_replace(',,',",",$sales_preferences);
            $sales_preferences = ltrim($sales_preferences, ',');
            $sales_preferences = rtrim($sales_preferences, ',');
            $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
            $epic_process->update(['sales_group' => $sales_preferences,'total_sales'=>$request->count]);
        }else{
            $sub_task = PipelineProcessTask::find($request->task_id);
            if(isset($sub_task)){
                $epic_process1 = EpicProcess::where('pipeline_process_task_id', $sub_task->task_id)->where('column_id', $request->column_id)->first();
                $sales_preferences = $epic_process1->sales_group;
                $sales_preferences = $sales_preferences != null ? str_replace($request->group,"",$sales_preferences) : null;
                $sales_preferences = str_replace(',,',",",$sales_preferences);
                $sales_preferences = ltrim($sales_preferences, ',');
                $sales_preferences = rtrim($sales_preferences, ',');
                $sales_preferences = implode(',',array_unique(explode(',', $sales_preferences)));
                $epic_process1->update(['sales_group' => $sales_preferences,'total_sales'=>$request->count]);
            }
        }
        // return response()->json($epic_process->sales_group);
        $task = PipelineProcessTask::find($request->task_id);
        $column = Column::find($request->column_id);
        return view('PipelineProcess.Projects.drag-drop',compact('task','column'));
    }

    public function get_epic_process_data(Request $request){
       // if($request->column_name != strtoupper('COMPLIMENTARY TRAINING')){
            $epic_process = EpicProcess::where('pipeline_process_task_id', $request->task_id)->where('column_id', $request->column_id)->first();
            if($request->projectName == SalesProcessProgress::EPIC_SALES_PROGRESS && $request->count == 2){
                $oldStepIndex =$request->stepIndex;
                if($request->stepIndex == 7){
                    $request->stepIndex = 11;
                    $stepType = 'attend';
                    $bookType = 'team';
                }else if($request->stepIndex == 8){
                    $request->stepIndex = 7;
                }else if($request->stepIndex == 9){
                    $request->stepIndex = 23;
                    $stepType = 'attend';
                    $bookType = 'team';
                }else if($request->stepIndex == 10){
                    $request->stepIndex = 8;
                }else if($request->stepIndex == 11){
                    $request->stepIndex = 24;
                    $stepType = 'attend';
                    $bookType = 'team';
                }else if($request->stepIndex == 12){
                    $request->stepIndex = 9;
                }else if($request->stepIndex == 13){
                    $request->stepIndex = 25;
                    $stepType = 'attend';
                    $bookType = 'team';
                }else if($request->stepIndex == 14){
                    $request->stepIndex = 10;
                }else if($request->stepIndex == 15){
                    $request->stepIndex = 26;
                    $stepType = 'attend';
                    $bookType = 'team';
                }else if($request->stepIndex == 16){
                    $request->stepIndex = 12;
                }else if($request->stepIndex == 17){
                    $request->stepIndex = 17;
                    $stepType = 'attend';
                    $bookType = 'indiv';
                }else if($request->stepIndex == 18){
                    $request->stepIndex = 13;
                }else if($request->stepIndex == 19){
                    $request->stepIndex = 19;
                    $stepType = 'attend';
                    $bookType = 'indiv';
                }else if($request->stepIndex == 20){
                    $request->stepIndex = 14;
                }else if($request->stepIndex == 21){
                    $request->stepIndex = 20;
                    $stepType = 'attend';
                    $bookType = 'indiv';
                }else if($request->stepIndex == 22){
                    $request->stepIndex = 15;
                }else if($request->stepIndex == 23){
                    $request->stepIndex = 21;
                    $stepType = 'attend';
                    $bookType = 'indiv';
                }else if($request->stepIndex == 24){
                    $request->stepIndex = 16;
                }else if($request->stepIndex == 25){
                    $request->stepIndex = 22;
                    $stepType = 'attend';
                    $bookType = 'indiv';
                }

                $msg = [];
                $calendarData = CalendarSetting::where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
                $clients = Clients::findOrFailClient($request->clientId);
                if($clients->sale_process_setts == null || $clients->sale_process_setts == ''){
                    $clients->sale_process_setts = $calendarData->sales_process_settings;
                    $this->salesProcSettingsUpdate($request->clientId,$calendarData->sales_process_settings);
                    $clients->refresh();
                    $clients->sale_process_setts = $calendarData->sales_process_settings;
                }
                $clientStatus = json_decode($clients->sale_process_setts,1);
                if(($request->stepIndex == SalesProcessProgress::BOOK_CONSULTATION || $request->stepIndex == SalesProcessProgress::CONTACT || $request->stepIndex == SalesProcessProgress::BOOK_BENCHMARK || $request->stepIndex == SalesProcessProgress::BOOK_TEAM1 || $request->stepIndex == SalesProcessProgress::BOOK_TEAM2|| $request->stepIndex == SalesProcessProgress::BOOK_TEAM3|| $request->stepIndex == SalesProcessProgress::BOOK_TEAM4|| $request->stepIndex == SalesProcessProgress::BOOK_TEAM5 || $request->stepIndex == SalesProcessProgress::BOOK_INDIVIDUAL1 || $request->stepIndex == SalesProcessProgress::BOOK_INDIVIDUAL2|| $request->stepIndex == SalesProcessProgress::BOOK_INDIVIDUAL3|| $request->stepIndex == SalesProcessProgress::BOOK_INDIVIDUAL4|| $request->stepIndex == SalesProcessProgress::BOOK_INDIVIDUAL5) && (in_array($request->stepIndex,$clientStatus['steps']) || in_array($request->stepIndex,$clientStatus['session']) || $request->stepIndex == 2 || $request->stepIndex == 1)){
                    $SalesProcessProgress = SalesProcessProgress::where('spp_client_id',$request->clientId)->where('spp_step_numb',$request->stepIndex)->whereNull('deleted_at')->latest()->first();
                    $salesProcessRelatedStatus =$this->calculateBookedStep((int)$request->stepIndex);
                   $checkSteps =   $this->isDependantStepComp( $salesProcessRelatedStatus['dependantStep'],$request->clientId, $clients->SaleProcessEnabledSteps);
                   if($checkSteps && $clients->account_status != 'pending'){
                        if($SalesProcessProgress){
                            $msg['status'] = 'booked';
                            $msg['step'] =  $request->stepIndex;
                            $msg['$oldStepIndex'] = $oldStepIndex;

                        }else{
                            $msg['status'] = 'notBooked';
                            $msg['step'] =  $request->stepIndex;
                            
                        }
                    }else{
                        $msg['status'] = 'error';
                        $msg['alert'] = "Can't book this step. Please Book Previous Steps first.";
                    }
                   
                }else if(($request->stepIndex == SalesProcessProgress::CONSULTATION || $request->stepIndex == SalesProcessProgress::BENCHMARK ||  $request->stepIndex == SalesProcessProgress::TEAM1 || $request->stepIndex == SalesProcessProgress::TEAM2|| $request->stepIndex == SalesProcessProgress::TEAM3|| $request->stepIndex == SalesProcessProgress::TEAM4|| $request->stepIndex == SalesProcessProgress::TEAM5 ||  $request->stepIndex == SalesProcessProgress::INDIVIDUAL1 || $request->stepIndex == SalesProcessProgress::INDIVIDUAL2|| $request->stepIndex == SalesProcessProgress::INDIVIDUAL3|| $request->stepIndex == SalesProcessProgress::INDIVIDUAL4|| $request->stepIndex == SalesProcessProgress::INDIVIDUAL5) && (in_array($request->stepIndex,$clientStatus['steps']) || in_array($request->stepIndex,$clientStatus['session']) || $request->stepIndex == 3)){
                    $SalesProcessProgress = SalesProcessProgress::where('spp_client_id',$request->clientId)->where('spp_step_numb',$request->stepIndex)->whereNull('deleted_at')->latest()->first();
                    $salesProcessRelatedStatus = $this->calculateAttendStep((int)$request->stepIndex);

                    $checkSteps =   $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'],$request->clientId, $clients->SaleProcessEnabledSteps);
                    if($checkSteps){
                    if($SalesProcessProgress && $clients->account_status != 'pending'){
                        $msg['status'] = 'booked';
                        $msg['step'] =  $request->stepIndex;
                        $msg['$oldStepIndex'] = $oldStepIndex;

                    }else{
                        $msg['bookDate'] = $this->findClientAttendStep($request->clientId,$request->stepIndex);
                        $msg['status'] = 'notBooked';
                        $msg['step'] =  $request->stepIndex;
                        $msg['stepType'] =  $stepType;
                        $msg['bookType'] =  $bookType;

                    }
                }else{
                    $msg['status'] = 'error';
                    $msg['alert'] = "Can't mark this step as attended. Please Book Previous Steps first.";
                }
                
                }else if(!in_array($request->stepIndex,$clientStatus['steps'])){
                    $msg['status'] = 'notExist';
                    $msg['step'] =  $request->stepIndex;

                }else{
                    $msg['status'] = 'failed';
                    $msg['step'] =  $request->stepIndex;

                }
                return json_encode($msg);
            }else if($request->projectName == SalesProcessProgress::EPIC_SALES_PROGRESS && $request->count == 1){
                $msg['status'] = '';
            } else if($epic_process){
                $total_sales = explode(',',$epic_process->sales_group);
                $total = $epic_process->total_sales;
                if($epic_process->sales_group != '' && count($total_sales) == $total){
                    if($request->column_id < $request->second_column_id){
                        $columns = Column::where('id','>',$request->column_id)->where('id','<',$request->second_column_id)->orderBy('id','asc')->pluck('id');
                    }else{
                        $columns = Column::where('id','<',$request->column_id)->where('id','>',$request->second_column_id)->orderBy('id','desc')->pluck('id');
                    }
                    // dd($columns);
                    if(count($columns) > 0){
                        foreach($columns as $column){
                            $epic_process1 = EpicProcess::where('pipeline_process_task_id', $request->task_id)->where('column_id', $column)->first();
                            if(isset($epic_process1)){
                                $sales_count = explode(',',$epic_process1->sales_group);
                                $total_sales1 = $epic_process1->total_sales;
                                if(count($sales_count) != $total_sales1){
                                    $data = 'true_with_more_column';
                                    return response()->json($data);
                                }
                                else{
                                    if(end($columns->toArray()) == $column){
                                        $data = true;
                                    }
                                }
                            }else{
                                $data = 'true_with_more_column';
                            }
                        }
                    }else{
                        $data = true;
                    }
                    
                }else{
                    $data = false;
                }
                
            }else{
                $data = false;
            }
            return response()->json($data);
        // }else{
        //     $task = PipelineProcessTask::findOrFail($request->task_id);
        //     $total_sales = explode(',',$task->sales_group);
        //     $total = $task->total_sales;
        //     if($task->sales_group != null && count($total_sales) == $total){
        //         $data = true;
        //     }else{
        //         $data = false;
        //     }
        //     return response()->json($data);
        // }
    }

    public function salesProcSettingsUpdate($id,$settData)
    {
        $client = Clients::findOrFailClient($id);
        $data = json_decode($settData,1);
        $client->is_bookbench_on = 0;
        if (in_array(4, $data['steps'])) {
            $client->is_bookbench_on = 1;
        }
        $client->save();
        $client->sale_process_setts = $settData;
        $salesAttendanceSteps    = salesAttendanceSteps();
        $newStatus               = '';
        $disabledAttendanceSteps = [];
        foreach ($salesAttendanceSteps as $slug) {
            if ($slug == 'teamed') {
                $teamAttendSteps  = teamAttendSteps();
                $indivAttendSteps = indivAttendSteps();
                $teamedEnabled    = $this->isStepEnabled($teamAttendSteps[0], $client->SaleProcessEnabledAttendSteps);
                $indivedEnabled   = $this->isStepEnabled($indivAttendSteps[0], $client->SaleProcessEnabledAttendSteps);

                if (!$teamedEnabled && !$indivedEnabled) {
                    //Neither team nor indiv is disabled
                    $thisDetails = calcSalesProcessRelatedStatus($slug);

                    if (!array_key_exists('dependantStep', $thisDetails) || $this->isDependantStepComp($thisDetails['dependantStep'], $id, $client->SaleProcessEnabledSteps)) {
                        //Its dependant step is completed
                        $newStatus = (array_key_exists('clientStatus', $thisDetails)) ? $thisDetails['clientStatus'] : $thisDetails['clientPrevStatus'];
                    } else {
                        break;
                    }

                } else if ($teamedEnabled && $indivedEnabled) {
                    //Team and indiv both are enabled
                    $lastIdx = count($data['session']) - 1;
                    $step    = $data['session'][$lastIdx];
                    if ($this->isStepComp($step, $id, $client->SaleProcessEnabledSteps)) {
                        //Step is  complete
                        $thisDetails = calcSalesProcessRelatedStatus($slug);
                        $newStatus   = $thisDetails['clientStatus'];
                    } else {
                        break;
                    }

                } else if ($teamedEnabled) {
                    $step = 0;
                    for ($i = count($data['session']) - 1; $i >= 0; $i--) {
                        if (in_array($data['session'][$i], $teamAttendSteps)) {
                            $step = $data['session'][$i]; //Team attendance Last step
                            break;
                        }
                    }
                    if ($this->isStepComp($step, $id, $client->SaleProcessEnabledSteps)) {
                        //Step is  complete
                        $thisDetails = calcSalesProcessRelatedStatus($slug);
                        $newStatus   = $thisDetails['clientStatus'];
                    } else {
                        break;
                    }

                } else if ($indivedEnabled) {
                    $step = 0;
                    for ($i = count($data['session']) - 1; $i >= 0; $i--) {
                        if (in_array($data['session'][$i], $indivAttendSteps)) {
                            $step = $data['session'][$i]; //Indiv attendance Last step
                            break;
                        }
                    }
                    if ($this->isStepComp($step, $id, $client->SaleProcessEnabledSteps)) {
                        //Step is  complete
                        $thisDetails = calcSalesProcessRelatedStatus($slug);
                        $newStatus   = $thisDetails['clientStatus'];
                    } else {
                        break;
                    }

                }
            } else if ($slug == 'indiv') {
                continue;
            } else {
                $thisDetails = calcSalesProcessRelatedStatus($slug);

                if (!in_array($thisDetails['saleProcessStepNumb'], $client->SaleProcessEnabledAttendSteps)) {
                    //Step is disabled
                    if (!array_key_exists('dependantStep', $thisDetails) || $this->isDependantStepComp($thisDetails['dependantStep'], $id, $client->SaleProcessEnabledSteps)) { //Its dependant step is completed
                        $newStatus = (array_key_exists('clientStatus', $thisDetails)) ? $thisDetails['clientStatus'] : $thisDetails['clientPrevStatus'];
                    } else {
                        break;
                    }

                } else {
                    //Step is enabled
                    if ($this->isStepComp($thisDetails['saleProcessStepNumb'], $id, $client->SaleProcessEnabledSteps)) //Step is  complete
                    {
                        $newStatus = $thisDetails['clientStatus'];
                    } else {
                        break;
                    }

                }
            }
        }

        if ($newStatus) {
            $clientOldStatus        = $client->account_status;
            // $newStatus              = preventActiveContraOverwrite($clientOldStatus, $newStatus);
            // $client->account_status = $newStatus;
            $client->save();
            // $this->processSalesProcessOnStatusChange($client, $clientOldStatus, $newStatus, 'Sales settings changed');
        }
        $client->update(['sale_process_setts'=> null]);
        return true;
    }

    public  function calculateAttendStep($stepNumb){
        $return = [];
      
             if($stepNumb === 11){
                     $return['dependantStep'] = 6;
                }else if($stepNumb === 23){
                  
                    $return['dependantStep'] = 7;
                } else if($stepNumb === 24){
                  
                    $return['dependantStep'] = 8;
                } else if($stepNumb === 25){
                  
                    $return['dependantStep'] = 9;
                }else if($stepNumb === 26){
                  
                    $return['dependantStep'] = 10;
                }  else if($stepNumb === 3){
                  
                    $return['dependantStep'] = 2;
                } else if($stepNumb === 5){
                   $return['dependantStep'] = 4;
                }
                else if($stepNumb === 17 || $stepNumb == 'book_indiv'){
                    $return['clientPrevStatus'] = 'Pre-Training';
                    $return['salesProcessType'] = 'book_indiv';
                    $return['saleProcessStepNumb'] = 12;
                    $return['dependantStep'] = 12;
                }
                else if($stepNumb === 19 || $stepNumb == 'book_indiv'){
                    $return['clientPrevStatus'] = 'Pre-Training';
                    $return['salesProcessType'] = 'book_indiv';
                    $return['saleProcessStepNumb'] = 13;
                    $return['dependantStep'] = 13;
                }
                else if($stepNumb === 20 || $stepNumb == 'book_indiv'){
                    $return['clientPrevStatus'] = 'Pre-Training';
                    $return['salesProcessType'] = 'book_indiv';
                    $return['saleProcessStepNumb'] = 14;
                    $return['dependantStep'] = 14;
                }
                else if($stepNumb === 21 || $stepNumb == 'book_indiv'){
                    $return['clientPrevStatus'] = 'Pre-Training';
                    $return['salesProcessType'] = 'book_indiv';
                    $return['saleProcessStepNumb'] = 15;
                    $return['dependantStep'] = 15;
                }
                else if($stepNumb === 22 || $stepNumb == 'book_indiv'){
                    $return['clientPrevStatus'] = 'Pre-Training';
                    $return['salesProcessType'] = 'book_indiv';
                    $return['saleProcessStepNumb'] = 16;
                    $return['dependantStep'] = 16;
                }
                
               
        
               
        
                return $return;
            }

            public function calculateBookedStep($stepNumb){
                $return = [];
                     if($stepNumb === 6){
                             $return['dependantStep'] = 4;
                        }else if($stepNumb === 7){
                          
                            $return['dependantStep'] = 6;
                        } else if($stepNumb === 8){
                          
                            $return['dependantStep'] = 7;
                        } else if($stepNumb === 9){
                          
                            $return['dependantStep'] = 8;
                        }else if($stepNumb === 10){
                          
                            $return['dependantStep'] = 9;
                        }  else if($stepNumb === 12){
                          
                            $return['dependantStep'] = 4;
                        } else if($stepNumb === 13){
                           $return['dependantStep'] = 12;
                        }
                        else if($stepNumb === 14 ){
                      
                            $return['dependantStep'] = 13;
                        }
                        else if($stepNumb === 15 || $stepNumb == 'book_indiv'){
                      
                            $return['dependantStep'] = 14;
                        }
                        else if($stepNumb === 16 ){
                       
                            $return['dependantStep'] = 15;
                        }
                        else if($stepNumb === 2 ){
                         
                            $return['dependantStep'] = 1;
                        }
                        else if($stepNumb === 4 ){
                            $return['clientPrevStatus'] = 'Pre-Training';
                            $return['salesProcessType'] = 'book_indiv';
                            $return['saleProcessStepNumb'] = 16;
                            $return['dependantStep'] = 2;
                        }
                        
                       
                
                       
                
                        return $return;
                    }


                    public function findClientAttendStep($id,$stepNumb){
                                if($stepNumb == 11 || $stepNumb == 23 || $stepNumb == 24 || $stepNumb == 25 || $stepNumb == 26){
                                 
                                    if($stepNumb == 11){
                                        $stepNumb =6; 
                                    }else if($stepNumb == 23){
                                        $stepNumb =7; 
                                    }else if($stepNumb == 24){
                                        $stepNumb =8; 
                                    }else if($stepNumb == 25){
                                        $stepNumb =9; 
                                    }else if($stepNumb == 26){
                                        $stepNumb =10; 
                                    }
                                    $classId =  DB::table('staff_event_class_clients')->select('secc_sec_id')->where('secc_client_id',$id)->where('sales_step_number', $stepNumb)->whereNull('deleted_at')->first();
                                    $class = StaffEventClass::where('sec_id',$classId->secc_sec_id)->first();
                                    $bookDate= dbDateToDateString($class->sec_date);
                                   
                                }else if($stepNumb == 17 || $stepNumb == 19 || $stepNumb == 20 || $stepNumb == 21 || $stepNumb == 22 || $stepNumb == 3 || $stepNumb == 5){
                                    
                                 
                                    if($stepNumb == 17){
                                        $stepNumb =12; 
                                    }else if($stepNumb == 19){
                                        $stepNumb =13; 
                                    }else if($stepNumb == 20){
                                        $stepNumb =14; 
                                    }else if($stepNumb == 21){
                                        $stepNumb =15; 
                                    }else if($stepNumb == 22){
                                        $stepNumb =16; 
                                    }else if($stepNumb == 3){
                                        $stepNumb =2; 
                                    }else if($stepNumb == 5){
                                        $stepNumb =4; 
                                    }
                                    $serviceDate =  StaffEventSingleService::where('sess_client_id',$id)->where('sales_step_number', $stepNumb)->whereNull('deleted_at')->first();
                                    $serviceName = $serviceDate->service->name;
                                    $bookDate= dbDateToDateString($serviceDate->sess_date);
        
                                }
                                return $bookDate;
                        
                            }

        
    
}
