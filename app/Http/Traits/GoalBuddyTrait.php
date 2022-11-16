<?php
/* 
REQUIREMENT: 
    HelperTrait for (goalListing())
*/

namespace App\Http\Traits;
use App\GoalBuddy;
use App\GoalBuddyMilestones;
use App\GoalBuddyHabit;
use App\GoalBuddyTask;
use App\GoalBuddyUpdate;
use Carbon\Carbon;
use Session;
use Auth;

trait GoalBuddyTrait{

    protected function goalListing($clientData){

        //$length = $this->getTableLengthFromCookie('goal');

        $habitsArray = $milestonesArray = $goalDetails = array();
        $goals = GoalBuddy::with('goalBuddyHabit','goalBuddyMilestones')->where('gb_client_id',$clientData['id'])->get();

        if($goals->count() > 0){
            $completed = $success = $missed =array();
            $monthArray =$datesnotfound=$daysBetweenDates= array();

            foreach($goals as $goalInfo){
                $habits = $goalInfo->goalBuddyHabit;
                $milestones = $goalInfo->goalBuddyMilestones;
    
                if($habits->count() > 0){
                foreach($habits as $habitsInfo){
                  if($habitsInfo->gb_habit_recurrence_type){
                        $habitsArray[] = array('h_name' =>$habitsInfo->gb_habit_name,'h_seen' =>$habitsInfo->gb_habit_seen,'h_id' =>$habitsInfo->id,'h_recurrence'=>$habitsInfo->gb_habit_recurrence,'h_milestone' =>$habitsInfo->gb_milestones_name);


                        $habitUpdateData = GoalBuddyUpdate::where('habit_id',$habitsInfo->id)->get();
                        $habitCompletedData = GoalBuddyUpdate::where('habit_id',$habitsInfo->id)->where('status','1')->get();
                        $totalCount = $habitUpdateData->count();
                        $completedCount = $habitCompletedData->count();
                        $missed[$habitsInfo->id] = $totalCount - $completedCount;
                        $completed[$habitsInfo->id] = $completedCount;

                           if($totalCount==0) 
                               $totalCount=1;

                        $success[$habitsInfo->id] = round(($completedCount*100)/$totalCount ,2);

                    }
                }//end foreach
            }
            $goalDetails[$goalInfo->id] = array('name' =>$goalInfo->gb_goal_name ,'habits' => $habitsArray,'milestones' =>$milestones);
        }       
     }
     return compact('goals','goalDetails');     
     } 
   

    public function clientDetails(){
        $business_id=Session::get('businessId');
        $clientArray = GoalBuddy::getClientDetails($business_id);
        return compact('clientArray');
    }


    protected function  updateGoalActivity($goalData){
     // dd($goalData);
       $goalInfo = GoalBuddy::find($goalData['goal_id']);
       $timestamp = createTimestamp();
       $data=array();
       $daysBetweenDates=$this->calcDateBetweenTwoDate($goalInfo->created_at,$goalData['due_date']);
         
        for($i=0; $i<=$daysBetweenDates+1;$i++){
          if(isset($goalData['created_at']))
            $startGoalDatetime = $goalData['created_at'];
          else
            $startGoalDatetime = Carbon::today();
           $due_date_time=$startGoalDatetime->addDays($i);
           $due_date=$due_date_time->format('Y-m-d');
           $data[]=array('goal_id'=>$goalData['goal_id'],'due_date'=>$due_date,'gb_user_id'=>$goalData['gb_user_id'],'created_at'=>$timestamp);
       }
       if($data) 
           GoalBuddyUpdate::insert($data); 
      

    }

   
  /**
   * update Habit Activity
   * @param  habit_id, due_date in array
   * @return void
   */   
  protected function updateHabitActivity($habitData){
    // dd($habitData);
    $habitsInfo = GoalBuddyHabit::find($habitData['habit_id']); 
    $timestamp = createTimestamp();
    $data=array();
    $created_time = date("Y-m-d",strtotime($habitsInfo->created_at));
    $startDatetime = Carbon::parse($habitsInfo->created_at);

    if($habitsInfo->gb_habit_recurrence_type == "daily"){
      $daysBetweenDates=$this->calcDateBetweenTwoDate($habitsInfo->created_at,$habitData['due_date']);

      for($i=0; $i<=$daysBetweenDates+1;$i++){
        $starthabitDatetime = Carbon::parse($habitsInfo->created_at);
        $due_date_time=$starthabitDatetime->addDays($i);
        $due_date=$due_date_time->format('Y-m-d');
        // $data[]=array('habit_id'=>$habitsInfo->id,'due_date'=>$due_date,'gb_client_id'=>$habitsInfo->gb_client_id,'created_at'=>$timestamp);
        $data[]=array('habit_id'=>$habitsInfo->id,'due_date'=>$due_date,'gb_client_id'=>$habitsInfo->gb_client_id,'created_at'=>$timestamp, 'goal_id' => $habitsInfo->goal_id);
      }
      if($data) 
        GoalBuddyUpdate::insert($data); 

    }
    else if($habitsInfo->gb_habit_recurrence_type == "weekly"){
      $dayName = array("0" => "Sunday","1" => "Monday","2" => "Tuesday","3" => "Wednesday","4" => "Thursday","5" => "Friday","6" => "Saturday");

      $semifinalarry = [];
      $daysBetweenDates=$this->calcWeekDates($habitsInfo->created_at ,$habitData['due_date']);

      if(!$daysBetweenDates)
        $daysBetweenDates=1;

      $days = explode(',',$habitsInfo->gb_habit_recurrence_week);
      $weeklyHabitdays = 0;
      $totalweeklyHabitdays = $daysBetweenDates * count($days);

      foreach($days as $d){
        $daybetween = $this->getDateForSpecificDayBetweenDates($startDatetime, $habitData['due_date'],array_search($d, $dayName));
        $semifinalarry = array_merge($semifinalarry,$daybetween);  
      } 

      foreach($semifinalarry as $daysArr){
        $data[]=array('habit_id'=>$habitsInfo->id,'due_date'=>$daysArr,'gb_client_id'=>$habitsInfo->gb_client_id,'created_at'=>$timestamp, 'goal_id' => $habitsInfo->goal_id);
      }

      if($data) 
        GoalBuddyUpdate::insert($data);

    }
    else if($habitsInfo->gb_habit_recurrence_type == "monthly"){  
      $totalMonthlyHabitdays=0; 
      $monthBetweenDates = $this->calcMonthBetweenDates($habitsInfo->created_at ,$habitData['due_date']);
      $datesAftermonth =$startDatetime->addMonths($monthBetweenDates);
      // $dateBetweenDates =$this->calcDateBetweenDates($datesAftermonth);
      $dateBetweenDates =$this->calcDateBetweenTwoDate($datesAftermonth,$habitData['due_date']);
      $createHabitDate = date("j",strtotime($habitsInfo->created_at));
      $habitsInfo->gb_habit_recurrence_month;
      if($monthBetweenDates!=0)
        $totalMonthlyHabitdays = $monthBetweenDates * 1;
      else if($dateBetweenDates !=0 )
        $totalMonthlyHabitdays = $totalMonthlyHabitdays + 1;

      if($totalMonthlyHabitdays>0){
        for($m=0;$m<$totalMonthlyHabitdays; $m++){
          $startDatemonth = Carbon::parse($habitsInfo->created_at);
          $datesAftermonth =$startDatemonth->addMonths($m);
          $habitMonthYearInfo=$this->getDateMonthYear($datesAftermonth);
          $habitDate = $habitMonthYearInfo['year'].'-'.$habitMonthYearInfo['month'].'-'.$habitsInfo->gb_habit_recurrence_month;

          $data[]=array('habit_id'=>$habitsInfo->id,'due_date'=>$habitDate,'gb_client_id'=>$habitsInfo->gb_client_id,'created_at'=>$timestamp, 'goal_id' => $habitsInfo->goal_id);     
        }
        if($data) 
          GoalBuddyUpdate::insert($data);
      }
    }
  }


  /**
   * update Habit Activity
   * @param  habit_id, due_date in array
   * @return void
   */   
  protected function updateTaskActivity($taskData){
    $taskInfo = GoalBuddyTask::find($taskData['task_id']); 
    $updateInfo = GoalBuddyUpdate::where('goal_id',$taskInfo->goal_id)->where('habit_id',$taskInfo->gb_habit_id)->get();
    $timestamp = createTimestamp();
    $data=array();

    $created_time = date("Y-m-d",strtotime($taskInfo->created_at));
    $startDatetime = Carbon::parse($taskInfo->created_at);
    if($taskInfo->gb_task_recurrence_type == "daily"){
      $daysBetweenDates=$this->calcDateBetweenTwoDate($taskInfo->created_at,$taskData['due_date']);

      for($i=0; $i<=$daysBetweenDates+1;$i++){
        $startTaskDatetime = Carbon::parse($taskInfo->created_at);
        $due_date_time=$startTaskDatetime->addDays($i);
        $due_date=$due_date_time->format('Y-m-d');
        $data[]=array('goal_id'=>$taskInfo->goal_id,'habit_id'=>$taskInfo->gb_habit_id,'task_id'=>$taskInfo->id,'due_date'=>$due_date,'gb_client_id'=>$taskInfo->gb_client_id,'created_at'=>$timestamp);     
      }
      if($data) 
        GoalBuddyUpdate::insert($data);       
    }
    else if($taskInfo->gb_task_recurrence_type == "weekly"){
      $dayName = array("0" => "Sunday","1" => "Monday","2" => "Tuesday","3" => "Wednesday","4" => "Thursday","5" => "Friday","6" => "Saturday");

      $semifinalarry = [];
      $daysBetweenDates=$this->calcWeekDates($taskInfo->created_at ,$taskData['due_date']);
      if(!$daysBetweenDates)
        $daysBetweenDates=1;

      $days = explode(',',$taskInfo->gb_task_recurrence_week);
      $weeklyTaskdays = 0;
      $totalweeklyTaskdays = $daysBetweenDates * count($days);

      foreach($days as $d){
        $daybetween = $this->getDateForSpecificDayBetweenDates($startDatetime, $taskData['due_date'],array_search($d, $dayName));
        $semifinalarry = array_merge($semifinalarry,$daybetween);   
      }
      
      if (!in_array($taskData['due_date'], $semifinalarry))
      {
        array_push($semifinalarry,$taskData['due_date']);
      }

      foreach($semifinalarry as $daysArr){
        $data[]=array('goal_id'=>$taskInfo->goal_id,'habit_id'=>$taskInfo->gb_habit_id,'task_id'=>$taskInfo->id,'due_date'=>$daysArr,'gb_client_id'=>$taskInfo->gb_client_id,'created_at'=>$timestamp);
      }
      if($data) 
        GoalBuddyUpdate::insert($data);
    }
    else if($taskInfo->gb_task_recurrence_type == "monthly"){
      $totalMonthlyTaskdays=$dateBetweenDates=0; 
      $monthBetweenDates = $this->calcMonthBetweenDates($taskInfo->created_at ,$taskData['due_date']);
      $datesAftermonth =$startDatetime->addMonths($monthBetweenDates);

      if($monthBetweenDates!=0)
        $dateBetweenDates =$this->calcDateBetweenTwoDate($datesAftermonth,$taskData['due_date']);
      else
        $daysBetweenDates=$this->calcDateBetweenTwoDate($taskInfo->created_at,$taskData['due_date']);

      $createTaskDate = date("j",strtotime($taskInfo->created_at));
      $taskInfo->gb_task_recurrence_month;
      if($monthBetweenDates!=0)
        $totalMonthlyTaskdays = $monthBetweenDates * 1;
      else if($dateBetweenDates !=0 )
        $totalMonthlyTaskdays = $totalMonthlyTaskdays + 1;
      else
        $totalMonthlyTaskdays = $totalMonthlyTaskdays + 1;

      if($totalMonthlyTaskdays>0){
        for($m=0;$m<=$totalMonthlyTaskdays; $m++){
          $startDatemonth = Carbon::parse($taskInfo->created_at);
          $datesAftermonth =$startDatemonth->addMonths($m);
          $taskMonthYearInfo=$this->getDateMonthYear($datesAftermonth);
          $taskDate = $taskMonthYearInfo['year'].'-'.$taskMonthYearInfo['month'].'-'.$taskInfo->gb_task_recurrence_month;

          $data[]=array('goal_id'=>$taskInfo->goal_id,'habit_id'=>$taskInfo->gb_habit_id,'task_id'=>$taskInfo->id,'due_date'=>$taskDate,'gb_client_id'=>$taskInfo->gb_client_id,'created_at'=>$timestamp);     
        }
        if($data) 
          GoalBuddyUpdate::insert($data);
      }
    }
  }

  
  protected function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
        {
          $startDate = strtotime($startDate);
          $endDate = strtotime($endDate);
          $dateArr = array();
          do
          {
              if(date("w", $startDate) != $weekdayNumber)
              {
                  $startDate += (24 * 3600); // add 1 day
              }
          } while(date("w", $startDate) != $weekdayNumber);


          while($startDate <= $endDate)
          {
              $dateArr[] = date('Y-m-d', $startDate);
              $startDate += (7 * 24 * 3600); // add 7 days
          }

          return($dateArr);
        } 

    protected function calcDateBetweenTwoDate($startDate,$endDate){
        $startDatetime = Carbon::parse($startDate);
        $endDatetime = Carbon::parse($endDate);
        return $startDatetime->diffInDays($endDatetime);
    }

    protected function calcDateBetweenDates($startDate){
        $startDatetime = $startDate;
        $endDatetime = Carbon::today();
        return $startDatetime->diffInDays($endDatetime);
    }

    protected function calcWeekDates($startDate,$endDate){
        $startDatetime = Carbon::parse($startDate);
        //$endDatetime = Carbon::today();
        $endDatetime = Carbon::parse($endDate);
        return $startDatetime->diffInWeeks($endDatetime);
    }

    protected function calcMonthBetweenDates($startDate,$endDate){
        $startDatetime = Carbon::parse($startDate);
       // $endDatetime = Carbon::today();
         $endDatetime = Carbon::parse($endDate);
        return $startDatetime->diffInMonths($endDatetime);
    }

    protected function getDateMonthYear($startDate){
        $output= array();
        $output['day']=$startDate->day;
        $output['month']=$startDate->month;
        $output['year']=$startDate->year;
        
        return $output;
    }
        

}