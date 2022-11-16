<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PipelineProcess\Column;
use App\Models\PipelineProcess\Attachment;
use App\Models\PipelineProcess\Comment;
use App\Models\PipelineProcess\Project;
use App\Models\PipelineProcess\PipelineProcessTask;
use Response;
use Carbon\Carbon;
use Auth;

class ColumnController extends Controller
{
   
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
        ]);

        $project = Project::findOrFail($request->id);

        // $this->authorize('view', $project);

        $project->columns()->create([
            'name'  => $request->input('name'),
            'index' => $project->columns->max('index') + 1,
        ]);

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $column = Column::findOrFail($request->id);

        // $this->authorize('view', $column->project);

        $column->update([
            'name' => $request->input('name'),
        ]);

        return back();
    }

    public function destroy(Request $request)
    {
        $task_id = PipelineProcessTask::where('column_id',$request->id)->pluck('id');
        if(isset($task_id)){
            $comment = Comment::whereIn('pipeline_process_task_id',$task_id)->pluck('id');
            if(count($comment)){
                Attachment::whereIn('comment_id',$comment)->delete();
                Comment::whereIn('pipeline_process_task_id',$task_id)->delete();
            }
            PipelineProcessTask::whereIn('task_id',$task_id)->delete();
            PipelineProcessTask::where('column_id',$request->id)->delete();
        }
        $column = Column::findOrFail($request->id)->delete();

        return back();
    }

    public function getTask(Request $request)
    {
        $tasks = PipelineProcessTask::with('comments')->where('id',$request->id)->get();
        return view('PipelineProcess.MyTasks.column-popup',compact('tasks'));
    }
}
