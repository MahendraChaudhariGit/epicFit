<?php
namespace App\Http\Traits;
use Carbon\Carbon;
use App\Task;
use App\TaskCategory;
use App\TaskReminder;
use DB;
use Auth;

trait NewTrait{
	public function getData($request){

        $isCommonCateg = TaskCategory::select('id')->whereIn('id',$request->categId)->where('t_cat_user_id',0)
                                      ->where('t_cat_business_id',0)->first();
        if($isCommonCateg){
          	$catarray = array();
  	        $catarray = $request->categId;
  	        $CommonCategId = $isCommonCateg->id;
  	        array_splice($catarray, 0, 1);
  	        //DB::enableQueryLog();
  	        $categdata = Task::
  	        			where('task_due_date','>=',$request->getEventsFrom)->where('task_due_date','<=',$request->getEventsUpto)
  	        			->where(function($query) use($catarray,$CommonCategId){
  	        				$query->whereIn('task_category',$catarray)
  	        				->orWhere(function($query) use($CommonCategId){
                              	$query->where('task_category',$CommonCategId)
                                   ->where('task_user_id',Auth::id());
                          	});
                          })
                          ->with('reminders','categoryName')->select('id','task_name','task_due_date','task_due_time','task_category','task_status','is_repeating','completed_by','task_user_id')->get();
            //dd(DB::getQueryLog());
            //dd($categdata);
       	}
       	else{
          //DB::enableQueryLog();
        	$categdata = Task::select('id','task_name','task_due_date','task_due_time','task_category','task_status','is_repeating','completed_by','task_user_id')->whereIn('task_category',$request->categId)->with('completer', 'reminders','categoryName')->where('task_due_date','>=',$request->getEventsFrom)->where('task_due_date','<=',$request->getEventsUpto)->where('task_user_id',Auth::id())->get();
          //dd(DB::getQueryLog());
    	}
        
        echo json_encode($categdata);
	}
}
