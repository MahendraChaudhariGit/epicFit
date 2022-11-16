<?php
namespace App\Http\Controllers\Result;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\GoalBuddy;
use App\GoalBuddyMilestones;
use App\GoalBuddyHabit;
use App\GoalBuddyHabitMetaData;
use App\GoalBuddyTask;
use App\GoalBuddyUpdate;
use Carbon\Carbon;
use App\Http\Traits\GoalBuddyTrait;
use App\Http\Traits\LocationAreaTrait;
use App\Http\Traits\StaffTrait;
use App\Http\Traits\CalendarSettingTrait;
use App\Http\Traits\ClientTrait;
use App\CalendarSetting;
use Session;
use Auth;
use DB;
use App\ClientMenu;
use Redirect;

class GoalBuddyCalendarController extends Controller{
    use GoalBuddyTrait,LocationAreaTrait,StaffTrait,ClientTrait,CalendarSettingTrait;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $clientSelectedMenus = [];
        if(Auth::user()->account_type == 'Client') {
            $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
 
            if(!in_array('epic_goal', $clientSelectedMenus))
              Redirect::to('access-restricted')->send();
        }    
    }

    public function index(){
      $goals = GoalBuddy::where('gb_client_id', Auth::user()->account_id)->get();
		  return view('Result.goal-buddy.goalBuddyCalendar', compact('goals'));
	  }

  	public function show(Request $request){
     $calArray=[];
     $eventType=$eventtitel='';
     $eventStartDate=$request->startDate;
     $eventEndDate=$request->endDate;
     $eventCalType=$request->eventType;
	 $sqlEventtype = [];
	 if( $request->goalId > 0 ){
		$sqlEventtype[] = ['goal_id', '=', $request->goalId];
	 }
	 else{
		$sqlEventtype[] = ['goal_id', '!=', 0];
	 }
     if($eventCalType=='goal')
      $sqlEventtype = array_merge($sqlEventtype, [['milestone_id','=',0],['habit_id','=',0],['task_id','=',0]]);
     else if($eventCalType=='milestone')
      $sqlEventtype = array_merge($sqlEventtype, [['milestone_id','!=',0],['habit_id','==',0],['task_id','=',0]]);
    else if($eventCalType=='habit')
      $sqlEventtype = array_merge($sqlEventtype, [['milestone_id','=',0],['habit_id','!=',0],['task_id','=',0]]);
    else if($eventCalType=='task')
      $sqlEventtype = array_merge($sqlEventtype, [['milestone_id','=',0],['habit_id','!=',0],['task_id','!=',0]]);
    /*else
       $sqlEventtype=[['goal_id','>=',0]];*/
    
     // DB::enableQueryLog();
    $activityArr =GoalBuddyUpdate::with('goal','milestone','habits','task')->where('gb_user_id',Auth::user()->id)->where($sqlEventtype)->where('due_date','>=',$eventStartDate)->where('due_date','<=',$eventEndDate)->orderBy('due_date', 'asc')->orderBy('goal_id', 'asc')->orderBy('milestone_id', 'asc')->orderBy('habit_id', 'asc')->orderBy('task_id', 'asc')->get();
//dd(DB::getQueryLog());
  
    //dd($activityArr);
   foreach ($activityArr as  $value) {
    
	$parent_id = 0;
    $goal_id=$value->goal_id;
    $milestone_id=$value->milestone_id;
    $habit_id=$value->habit_id;
    $task_id=$value->task_id;
    
   if($goal_id !='' && $milestone_id==0 && $habit_id==0 && $task_id==0){
    $eventType='goal';
    $parentId = 0;
	/*if(!isset($value->goal))
	dd($value);
	else*/
    $eventtitel=$value->goal->gb_goal_name;

   }if($goal_id !='' && $milestone_id!='' && $habit_id==0 && $task_id==0){
    $eventType='milestone';
    $parent_id = $goal_id;
    $eventtitel=$value->milestone->gb_milestones_name;

   }else if($goal_id !='' && $milestone_id==0 && $habit_id!='' && $task_id==0){
    $eventType='habit';
    $parent_id = $goal_id;
    $eventtitel=$value->habits->gb_habit_name;
   }
   else if($goal_id !='' && $milestone_id==0 && $habit_id!='' && $task_id!=''){
    $eventType='task';
    $parent_id = $habit_id;
    $eventtitel=$value->task->gb_task_name;
   }


      $calArray[] = array('goalId' =>$goal_id,'milestoneId' =>$milestone_id,'habitId' =>$habit_id,'taskId' =>$task_id,'eventTitel'=>$eventtitel,'eventType' =>$eventType,'eventDueDate'=>$value->due_date,'eventId'=>$value->id,'eventStatus'=>$value->status,'parentId' => $parent_id);


   }
//dd($calArray);
          return json_encode($calArray);
      	
  	}

    public function showwithaction(Request $request){

      $eventaction=$request['actionType'];
      $activityArr = GoalBuddyUpdate::where('goal_id','!=','')->get();
        return json_encode($activityArr);
    }

  

    public function statusChange(Request $request){
    
        $message = array('status' =>'false','data' =>'','updateHabitId'=>'');
        $habitId='';
        $data['status'] = $request['actionStatus'];
        $goalBuddyUpdate = GoalBuddyUpdate::with('task', 'milestone')->find($request['eventId']);

        if($goalBuddyUpdate->update($data)){
			if($goalBuddyUpdate->task){
          		$updateHabitId= $this->updateTasKHabit($goalBuddyUpdate->task->gb_habit_id,$goalBuddyUpdate->due_date);
          		$habitId=$updateHabitId['updateId'];
         	}
			else if($goalBuddyUpdate->milestone){
				$goalBuddyUpdate->milestone->update(['gb_milestones_status'=>$data['status']]);
			}
          	$message = array('status' =>'true','updateHabitId'=>$habitId);
        }
        echo json_encode($message);

    }


        public function show_calendar(Request $request)
        {


        $data = $this->locAreasForEvents();
        $locsAreas = $data['locsAreas'];
        $ifClassesExit = $data['ifClassesExit'];
        $ifServicesExit = $data['ifServicesExit'];
        //$defaultStaffDetails = $data['defaultStaffDetails'];

        $staffs = ['all-ros' => 'All rostered staff', 'all' => 'All staff'] + $this->staffs('all');

        $staffHoursRequest = new \Illuminate\Http\Request();
        $staffHoursRequest->staffId = 'all';
        $staffHoursRequest->areaId = 'all';
        $staffHours = $this->getHoursFromTrait($staffHoursRequest);
        
        $modalLocsAreas = $locsAreas;
        if(count($locsAreas))
            $locsAreas = ['all' => 'All Locations'] + $locsAreas;

        $eventRepeatIntervalOpt = $this->prepareEventRecurDdOpt();

        //dd($request);
        if($request->has('subview')){
            $subview = true;
            if(Auth::user()->account_id){
                $client = Clients::findClient(Auth::user()->account_id);

                if($client){
                    $cl['id'] = Auth::user()->account_id;
                    $cl['name'] = $client->firstname.' '.$client->lastname;
                    $cl['email'] = $client->email;
                    $cl['phone'] = $client->phonenumber;
                    if($request->has('consultationRestriction'))
                        $enableDateFrom = $client->consultation_date;
                    $cl = json_encode($cl);
                }
            }
        }
        if(!isset($cl)){
            $clientDetailsRequest = new \Illuminate\Http\Request();
            $clientDetailsRequest->calendar = true;
            $clients = $this->allClientsFromTrait($clientDetailsRequest);
        }

        if($request->has('enableDatePeriod'))
            $enableDatePeriod = $request->enableDatePeriod;

         $calendarSettingVal=CalendarSetting::where('cs_business_id',Session::get('businessId'))->first()->toArray();
          $reasons = $this->getCancelReasons($calendarSettingVal['id']);

        return view('Result.goal-buddy.goalBuddyEventCalendar', compact('modalLocsAreas', 'locsAreas', 'ifClassesExit', 'ifServicesExit'/*, 'defaultStaffDetails'*/, 'eventRepeatIntervalOpt', 'subview', 'cl', 'enableDateFrom', 'enableDatePeriod', 'staffs', 'staffHours', 'clients','reasons','calendarSettingVal'));

        }
       
}