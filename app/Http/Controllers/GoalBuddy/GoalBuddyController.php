<?php
namespace App\Http\Controllers\GoalBuddy;

use App\ClientMenu;
use App\GoalBuddy;
use App\GoalBuddyHabit;
use App\GoalBuddyMilestones;
use App\GoalBuddyTask;
use App\GoalBuddyUpdate;
use App\SocialFriend;
use App\Clients;
use App\Http\Controllers\Controller;
use App\Http\Traits\GoalBuddyTrait;
use App\Http\Traits\HelperTrait;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;
use Session;
use App\{SocialUserDirectMessage, SocialPost, GoalBuddyHabitList};
use View;
use Response;

class GoalBuddyController extends Controller
{
    use HelperTrait, GoalBuddyTrait;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $clientSelectedMenus = [];
        if (Auth::user()->account_type == 'Client') {
            $selectedMenus       = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];

            if (!in_array('epic_goal', $clientSelectedMenus)) {
                Redirect::to('access-restricted')->send();
            }

        }
    }

    public function index()
    {
       
        $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        if(isset($selectedMenus) && !in_array('epic_goal', explode(',', $selectedMenus))){
            return redirect('access-restricted');
        }

        $habitsArray = $milestonesArray = array();
        $goals       = GoalBuddy::with('goalBuddyHabit', 'goalBuddyMilestones')
                 ->where('gb_client_id', Auth::user()->account_id)
                //  ->get();
                ->paginate(10);
        
        if ($goals->count() > 0) {
            $completed  = $success  = $missed  = array();
            $monthArray = $datesnotfound = $daysBetweenDates = array();

            foreach ($goals as $key=>$goalInfo) {
                $habits = $goalInfo->goalBuddyHabit;

                //Other goals name should be take from gb_goal_name_other field
                if($goalInfo->gb_goal_name == "Other"){
                    $goals[$key]->gb_goal_name = $goalInfo->gb_goal_name_other;
                }
                
                //$milestones = $goalInfo->goalBuddyMilestones;
                //$milestoneActiveData = GoalBuddyMilestones::where('goal_id',$goalInfo->id)->where('gb_milestones_status','1')->get();

                if ($habits->count() > 0) {
                    foreach ($habits as $habitsInfo) {
                        $habitUpdateData    = GoalBuddyUpdate::where('habit_id', $habitsInfo->id)->where('task_id', 0)->whereRaw('due_date <= curdate()')->get();
                        $habitCompletedData = GoalBuddyUpdate::where('habit_id', $habitsInfo->id)->where('task_id', 0)->where('status', '1')->whereRaw('due_date <= curdate()')->get();

                        $totalCount                 = $habitUpdateData->count();
                        $completedCount             = $habitCompletedData->count();
                        $missed[$habitsInfo->id]    = $totalCount - $completedCount;
                        $completed[$habitsInfo->id] = $completedCount;
                        if ($totalCount == 0) {
                            $totalCount = 1;
                        }

                        $success[$habitsInfo->id] = round(($completedCount * 100) / $totalCount, 2);

                        //}
                    } //end foreach

                }
                //$goalDetails[$goalInfo->id] = array('name' =>$goalInfo->gb_goal_name ,'habits' => $habitsArray,'milestones' =>$milestones);
                $habitsArray     = [];
                $milestonesArray = [];
            }
            // return view('Result.goal-buddy.goallisting', compact('goals', 'missed', 'success', 'completed')); //,'goalDetails'
        } 
        return view('Result.goal-buddy.goallisting', compact('goals', 'missed', 'success', 'completed')); //,'goalDetails'

        // else {
        //     return view('Result.goal-buddy.create');
        // }
    }

    public function goalPrint()
    {
        $habitsArray = $milestonesArray = array();
        $goals       = GoalBuddy::getGoalByUser(Auth::user()->id);
        foreach ($goals as $goalInfo) {
            $habits     = GoalBuddy::getHabit($goalInfo->id);
            $milestones = GoalBuddy::getMilestone($goalInfo->id);
            if (count($habits) > 0) {
                foreach ($habits as $habitsInfo) {
                    $habitsArray[] = array('h_name' => $habitsInfo->gb_habit_name, 'h_seen' => $habitsInfo->gb_habit_seen);
                }
            }
            if (count($milestones) > 0) {
                foreach ($milestones as $milestonesInfo) {
                    $milestonesArray[] = array('m_name' => $milestonesInfo->gb_milestones_name);
                }
            }
            $goalDetails[$goalInfo->id] = array('name' => $goalInfo->gb_goal_name, 'habits' => $habitsArray, 'milestones' => $milestonesArray);
            $habitsArray                = [];
            $milestonesArray            = [];
        }
        return view('Result.goal-buddy.goalprint', compact('goals', 'goalDetails'));
    }

    public function create_old()
    {
        $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('added_client_id')->toArray();
        $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('client_id')->toArray();
        $all_friends = array_merge($send_request_accepred,$recieve_request_accepted);
        $my_friends = Clients::select('id','business_id',DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"))
        ->whereIn('id',$all_friends)->get(); //remove all code from here
        return view('Result.goal-buddy.create-old',compact('my_friends'));
    }

    /**
     * Store a newly created goal into table.
     * @param  GoalBuddyRequest $request
     * @return Response
     */
    public function store(Request $request)
    {
        $postData = $request->all()['formData'];
        //   dd(  $postData);
       $forHabit = false;
        $todayDate       = date("Y-m-d");
        $currentDay      = date("l");
        $currentDateOnly = date("j");
        $lastInsertId    = '';
        $goalBuddyHabit  = $data  =$updateHabit= array();

        if (Session::get('hostname') == 'crm') {
            $clientId = $postData["ClientId"];
        } else {
            $clientId = Auth::user()->account_id;
        }
       // For Notes
        if (isset($postData["goal_notes"])) {
            $data['gb_goal_notes'] = $postData["goal_notes"];
            if (isset($postData["last_insert_id"]) && $postData["last_insert_id"] != '' &&  $data['gb_goal_notes'] != "") {           
                $goalDetails  = GoalBuddy::findOrFail($postData["last_insert_id"]);
                $goalBuddy    = $goalDetails->update($data);
            }
        }               
        // end
        if ($postData["form_no"] == 1) {
            $data['gb_client_id'] = $clientId;

            if ($postData["image"]) {
                $data['gb_user_pic'] = $postData["image"];
            }

            $data['gb_company_name'] = 'company';

            if ($postData["name"]) {
                $data['gb_goal_name'] = $postData["name"];
            }
            if ($postData["gb_goal_name_other"]) {
                $data['gb_goal_name_other'] = $postData["gb_goal_name_other"];
            }

            if ($postData["describe_achieve"]) {
                $data['gb_achieve_description'] = $postData["describe_achieve"];
            }
            if ($postData["gb_achieve_description_other"]) {
                $data['gb_achieve_description_other'] = $postData["gb_achieve_description_other"];
            }

            if ($postData["template"]) {
                if ($postData["accomplish"]) {
                    $data["gb_important_accomplish"] = implode(',', $postData["accomplish"]);
                }
                if ($postData["gb_relevant_goal"]) {
                    $data["gb_relevant_goal"] = implode(',', $postData["gb_relevant_goal"]);
                }
            }else{
                if ($postData["accomplish"]) {
                    $data["gb_important_accomplish"] = $postData["accomplish"];
                }
                if ($postData["gb_relevant_goal"]) {
                    $data["gb_relevant_goal"] = $postData["gb_relevant_goal"];
                }
            }
            if ($postData["gb_important_accomplish_other"]) {
                $data['gb_important_accomplish_other'] = $postData["gb_important_accomplish_other"];
            }

            if ($postData["gb_fail_description_other"]) {
                $data['gb_fail_description_other'] = $postData["gb_fail_description_other"];
            }
            if ($postData["gb_relevant_goal_other"]) {
                $data['gb_relevant_goal_other'] = $postData["gb_relevant_goal_other"];
            }

            if ($postData["change_life"]) {
                $data['gb_change_life_reason'] = implode(',', $postData["change_life"]);
                // dd($data['gb_change_life_reason']);
            }

            if (isset($postData["gb_change_life_reason_other"])) {
                $data['gb_change_life_reason_other'] = $postData["gb_change_life_reason_other"];
            }            

            

            // if ($postData["failDescription"]) {
            //      $data["gb_fail_description"] = $postData["failDescription"];
            // }

            if ($postData["template"]) {
                if ($postData["failDescription"]) {
                    $data["gb_fail_description"] = implode(',', $postData["failDescription"]);
                }     
            }else{
                if ($postData["failDescription"]) {
                    $data["gb_fail_description"] = $postData["failDescription"];
                }      
            }
           

            if ($postData["template"]) {
                $data['gb_template'] = $postData["template"];
            }

            if ($postData["due_date"]) {
                $data['gb_due_date'] = $postData["due_date"];
            }

            if (isset($postData["ClientName"]) && $postData["ClientName"] != '') {
                $data['gb_user_name'] = $postData["ClientName"];
            }

            if (isset($postData['gb_relevant_goal_event'])) {
                $data["gb_relevant_goal_event"] = $postData["gb_relevant_goal_event"];
            }
            if (isset($postData['gb_relevant_goal_event_other'])) {
                $data["gb_relevant_goal_event_other"] = $postData["gb_relevant_goal_event_other"];
            }

            if (isset($postData['image'])) {
                $data['gb_image_url'] = $postData["image"];
            }

            if (isset($postData['goal_seen'])) {
                $data['gb_goal_seen'] = $postData["goal_seen"];
            }

            if (isset($postData['goal_selective_friends'])) {
                $data['gb_goal_selective_friends'] = $postData["goal_selective_friends"];
            }

            if (isset($postData['goal_year'])) {
                $data['gb_is_top_goal'] = $postData["goal_year"];
            }

            if (isset($postData['send_msg_type'])) {
                $data['gb_reminder_type'] = $postData["send_msg_type"];
            }
            if (isset($postData['gb_reminder_type_epichq'])) {
                $data['gb_reminder_type_epichq'] = $postData["gb_reminder_type_epichq"];
            }           
            if (isset($postData['Send_mail_time'])) {
                $data['gb_reminder_goal_time'] = $postData["Send_mail_time"];
            }

            if ($postData["update_status"] == 'update-yes') {
                $goalDetails  = GoalBuddy::findOrFail($postData["last_insert_id"]);
                $data['is_step_completed'] = true;
                $goalBuddy    = $goalDetails->update($data);
                $lastInsertId = $postData["last_insert_id"];
                GoalBuddyUpdate::where('goal_id', $lastInsertId)->where('milestone_id', '=', 0)->where('habit_id', '=', 0)->where('task_id', '=', 0)->delete();
            } else {
                $data['is_step_completed'] = true;
                $goalBuddy    = GoalBuddy::create($data);
                $lastInsertId = $goalBuddy->id;
            }

    /*  message for social-friend */
    if(isset($postData['update_status'])){
        if (isset($postData['goal_seen']) && $postData['goal_seen']!='Just_me') {
            $goal_seen = $postData["goal_seen"];
            if($goal_seen == 'Selected friends'){
                if($postData['goal_selective_friends']){
                    $friend_id = explode(',', $postData['goal_selective_friends']);
                } 
              }
            if($goal_seen == 'everyone'){           
                $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                        ->where('status','Accepted')
                                        ->pluck('added_client_id')
                                        ->toArray();
                 $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                    ->where('status','Accepted')
                                    ->pluck('client_id')
                                    ->toArray();
                 $friend_id = array_merge($send_request_accepred,$recieve_request_accepted);
               
            }
            if($friend_id){
                $friends = Clients::whereIn('id', $friend_id)
                     ->select('id', 'firstname','lastname')
                     ->get();
            }     
            $user = Auth::user();
            $due_date = date("d-m-Y",  strtotime($postData['due_date']));
            
             if ($friends && $postData['name']){
               foreach($friends as $friend){
                  
                     $name = $friend['firstname'].' '. $friend['lastname'];
                     if($postData['update_status'] == "update-yes"){
                        $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a goal ". $postData['name'] ." which is due on ". $due_date ;
                     } else {
                        $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a goal ". $postData['name'] ." which is due on ". $due_date ;
                     }
                    //  $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a goal ". $goalBuddy['gb_goal_name'] ." which is due on ". $due_date ;
                     $message = new SocialUserDirectMessage();
                     $message->sender_user_id = $user->account_id;
                     $message->receiver_user_id = $friend->id;
                     $message->message = $text;
                     $message->save();
                     /* post */
                     $post = new SocialPost();
                     $post->content = $text;
                     $post->goal_client_id = Auth::user()->account_id;
                     $post->goal_friend_id = $friend->id;
                     $post->save();

                }
             }
           }
          
       }
                          
         /*  end message for social-friend */

            # Get goal template details
            if ($postData["update_status"] != 'update-yes') {
            if (array_key_exists('gb_template', $data) && $data['gb_template']) {
                $goalTemplateDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->find($data['gb_template']);
                if ($goalTemplateDetails) {
                    # Add goal template milestone to new goal
                    if ($goalTemplateDetails->goalBuddyMilestones) {
                        $templateMilestones = $goalTemplateDetails->goalBuddyMilestones;

                        foreach ($templateMilestones as $milestone) {
                            $tmpMilestoneData = [
                                'gb_milestones_name' => $milestone->gb_milestones_name,
                                'goal_id'            => $lastInsertId,
                                'gb_client_id'       => $clientId,
                            ];
                            if (GoalBuddyMilestones::where($tmpMilestoneData)->count() == 0) {
                                //$savedData = GoalBuddyMilestones::create($tmpMilestoneData);
                            }

                        }
                    }

                    # Add goal template habit to new goal
                    if ($goalTemplateDetails->goalBuddyHabit) {
                        $templateHabits = $goalTemplateDetails->goalBuddyHabit;
          
                        foreach ($templateHabits as $habit) {
                            $tmpHabitData = [
                                'gb_habit_name'  => $habit->gb_habit_name,
                                // 'gb_habit_notes' => $habit->gb_habit_notes,
                                'goal_id'        => $lastInsertId,
                                'gb_client_id'   => $clientId,
                            ];

                            if (GoalBuddyHabit::where($tmpHabitData)->count() == 0) {
                                $savedData = GoalBuddyHabit::create($tmpHabitData);
                            }

                        }
                    }

                    # Add goal template task to new goal
                    if ($goalTemplateDetails->goalBuddyTask) {
                        $templateTasks = $goalTemplateDetails->goalBuddyTask;

                        foreach ($templateTasks as $task) {
                        	if($task->taskhabit) {
	                        	$tmpTaskHabitId  = GoalBuddyHabit::where([
	                        		'goal_id' => $lastInsertId,
	                        		'gb_client_id' => $clientId,
	                        		'gb_habit_name' => $task->taskhabit->gb_habit_name
	                        	])->pluck('id')->first();


	                            $tmpTaskData = [
	                                'gb_task_name' => $task->gb_task_name,
	                                'gb_task_note' => $task->gb_task_note,
	                                'goal_id'      => $lastInsertId,
	                                'gb_client_id' => $clientId,
	                                'gb_habit_id' =>  $tmpTaskHabitId,
	                            ];

	                            if (GoalBuddyTask::where($tmpTaskData)->count() == 0) {
	                                $savedData = GoalBuddyTask::create($tmpTaskData);
	                            }
	                        }

                        }
                    }
                }
            }
        }

            $inserData['goal_id']      = $lastInsertId;
            $inserData['gb_client_id'] = $clientId;
            $inserData['due_date']     = $postData["due_date"];

            $goalupdate = GoalBuddyUpdate::create($inserData);
        } else if ($postData["form_no"] == 2) {
            $milestonesInsertData = array();
            $timestamp            = createTimestamp();

            if (isset($postData["last_insert_id"]) && $postData["last_insert_id"] != '') {
                $lastInsertGoalId = $postData["last_insert_id"];
            }

            if (isset($postData["goal_id_mile"]) && $postData["goal_id_mile"] != '') {
                $lastInsertGoalId = $postData["goal_id_mile"];
            }

            if (isset($postData["Send_mail_milestones_time"])) {
                $gb_milestones_reminder_time = $postData["Send_mail_milestones_time"];
            }else{
                $gb_milestones_reminder_time = NULL;
            }
            if (isset($postData["gb_milestones_selective_friends"])) {
                $gb_milestones_selective_friends = $postData["gb_milestones_selective_friends"];
            }else{
                $gb_milestones_selective_friends = NULL;
            }

            $lastInsertId    = $lastInsertGoalId;
            $milestonArray   = array();
            $milestoneUpdate = GoalBuddyMilestones::where('goal_id', $lastInsertId)->get();
            foreach ($milestoneUpdate as $mileston) {
                $mileston->update(['gb_milestones_seen' => $postData['gb_milestones_seen'], 'gb_milestones_reminder' => $postData["gb_milestones_reminder"],'gb_milestones_reminder_epichq' => $postData["gb_milestones_reminder_epichq"],'gb_milestones_selective_friends' => $gb_milestones_selective_friends, 'gb_milestones_reminder_time' => $gb_milestones_reminder_time,  'gb_client_id' => $clientId,'is_step_completed'=> true]);

                $milestonArray[] = array('id' => $mileston->id, 'gb_milestones_name' => $mileston->gb_milestones_name);
            }

            $mileStoneIdStr = $this->insertMilestoneUpdates($lastInsertId, $clientId);
            if(isset($postData['save_as_draft']) && $postData['save_as_draft'] == true){
                $saveAsDraft = $postData['save_as_draft'];
            }else{
                $saveAsDraft = $postData['save_as_draft'];
            }

     /*  message for social-friend */
    if( count($postData['milestones-names-id']) > 0 ){
        if (isset($postData['gb_milestones_seen']) && $postData['gb_milestones_seen'] != 'Just_Me') {
            $goal_seen = $postData["gb_milestones_seen"];
            if($goal_seen == 'Selected friends'){
                if($postData['gb_milestones_selective_friends']){
                    $friend_id = explode(',', $postData['gb_milestones_selective_friends']);
                } 
              }
            if($goal_seen == 'everyone'){           
                $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                        ->where('status','Accepted')
                                        ->pluck('added_client_id')
                                        ->toArray();
                 $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                    ->where('status','Accepted')
                                    ->pluck('client_id')
                                    ->toArray();
                 $friend_id = array_merge($send_request_accepred,$recieve_request_accepted);
               
              }
            if($friend_id){
                $friends = Clients::whereIn('id', $friend_id)
                     ->select('id', 'firstname','lastname')
                     ->get();
            }  
             
            $user = Auth::user();
/*  */
        foreach($postData['milestones-names-id'] as $key => $name){ 
            $result = explode(':', $name);
            $due_date = date("d-m-Y",  strtotime($postData['milestones-dates'][$key]));
            // $text = $user['name'].' '. $user['last_name']. " created a goal ". $result[1]  ." which is due on ". $due_date ;
             if ($friends){
               foreach($friends as $friend){
                     $name = $friend['firstname'].' '. $friend['lastname'];
                     if(!empty($postData['milestones_id'])){
                        $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a milestone ". $result[1]  ." which is due on ". $due_date ;
                     } else {
                        $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a milestone ". $result[1]  ." which is due on ". $due_date ;
                     }
                     
                     $message = new SocialUserDirectMessage();
                     $message->sender_user_id = $user->account_id;
                     $message->receiver_user_id = $friend->id;
                     $message->message = $text;
                     $message->save();
                  /* post */
                     $post = new SocialPost();
                     $post->content = $text;
                     $post->goal_client_id = Auth::user()->account_id;
                     $post->goal_friend_id = $friend->id;
                     $post->save();

                 }
              }
            }
           
/*  */
         }
          
        }
                          
         /*  end message for social-friend */
            $milestonesData = array('form' => 'milestones-list', 'milestonesId' => $mileStoneIdStr, 'mdata' => $milestonArray,'saveAsDraft' => $saveAsDraft);

            return json_encode($milestonesData);
        } else if ($postData["form_no"] == 3) {
            $data                  = array();
            $data['gb_client_id']  = $clientId;
            if(isset($postData["habit_name"])){
                $data['gb_habit_name'] = $postData["habit_name"];
            }
            

            if ($postData["last_insert_id"] != '') {
                $data['goal_id'] = $postData["last_insert_id"];
            }
            if ($postData["habit_recurrence"] != '') {
                $data['gb_habit_recurrence_type'] = $postData["habit_recurrence"];

                if ($postData["habit_recurrence"] == "weekly" && isset($postData['habit_weeks'])) {
                    $weekData                          = implode(',', $postData['habit_weeks']);
                    $data['gb_habit_recurrence_week']  = $weekData;
                    $data['gb_habit_recurrence_month'] = '';
                    $data['gb_habit_recurrence']       = '';
                } else if ($postData["habit_recurrence"] == "monthly" && isset($postData['month'])) {
                    $data['gb_habit_recurrence_month'] = $postData['month'];
                    $data['gb_habit_recurrence_week']  = '';
                    $data['gb_habit_recurrence']       = '';
                } else {
                    $data['gb_habit_recurrence']       = $postData["habit_recurrence"];
                    $data['gb_habit_recurrence_month'] = '';
                    $data['gb_habit_recurrence_week']  = '';
                }
            }

            if (isset($postData["habit_milestone"]) && is_array($postData["habit_milestone"])) {
                $data['gb_milestones_id'] = implode(',', $postData["habit_milestone"]);
            } else {
                $data['gb_milestones_id'] = '';
            }

            // if (isset($postData["habit_notes"])) {
            //     // $data['gb_habit_notes'] = $postData["habit_notes"];
            //     $data['gb_habit_notes'] = implode(',', $postData["habit_notes"]);
            // }

             if (isset($postData["habit_notes"]) && is_array($postData["habit_notes"])) {
                    $data['gb_habit_notes'] = implode(',', $postData["habit_notes"]);
             } else {
                    $data['gb_habit_notes'] = $postData["habit_notes"];
             }

            if ($postData["gb_habit_note_other"]) {
                $data['gb_habit_note_other'] = $postData["gb_habit_note_other"];
            }

            if (isset($postData["habit_seen"])) {
                $data['gb_habit_seen'] = $postData["habit_seen"];
            }

            if (isset($postData["syg2_selective_friends"])) {
                $data['gb_habit_selective_friends'] = $postData["syg2_selective_friends"];
            }

            if (isset($postData["habit_reminders"])) {
                $data['gb_habit_reminder'] = $postData["habit_reminders"];
            }
            if (isset($postData["Send_mail_habits_time"])) {
                $data['gb_habit_reminder_time'] = $postData["Send_mail_habits_time"];             
            }
            if (isset($postData["gb_habit_reminder_epichq"])) {
                $data['gb_habit_reminder_epichq'] = $postData["gb_habit_reminder_epichq"];         
            }
            $data['is_step_completed'] = true;
               $edit_habit_form = null;
            if (isset($postData["habit_id"]) && $postData["habit_id"]) {
                $edit_habit_form = 'yes';
                $habits       = GoalBuddyHabit::find($postData["habit_id"]);
                $goalBuddy    = $habits->update($data);
                $lastHabitId  = $postData["habit_id"];
                $lastInsertId = $habits->goal_id;
            } else if (isset($data["gb_habit_name"]) && $data["gb_habit_name"]) {
                $edit_habit_form = 'yes';
                $habits       = GoalBuddyHabit::where('gb_habit_name', $data['gb_habit_name'])
                                ->where('goal_id', $data['goal_id']);
                if($habits->count()){
                    $goalBuddy    = $habits->update($data);
                    $lastHabitId  = $goalBuddy->id;
                    $lastInsertId = $data['goal_id'];    
                }else{
                    $goalBuddy    = GoalBuddyHabit::create($data);
                    $lastHabitId  = $goalBuddy->id;
                    $lastInsertId = $postData["last_insert_id"];    
                }
                
            } else{
                $goalBuddy    = GoalBuddyHabit::create($data);
                $lastHabitId  = $goalBuddy->id;
                $lastInsertId = $postData["last_insert_id"];
            }

            $goalDetails    = GoalBuddy::with('goalBuddyHabit')->findOrFail($lastInsertId);
            $habit_due_date = $goalDetails->gb_due_date;

            if ($lastHabitId) {
                GoalBuddyUpdate::where('habit_id', $lastHabitId)->where('task_id', '=', 0)->delete();
                $this->updateHabitActivity(['habit_id' => $lastHabitId, 'due_date' => $habit_due_date]);
            }

            $goalBuddyHabit = $goalDetails->goalBuddyHabit;
            $habitArray     = array();
            foreach ($goalBuddyHabit as  $habitVal) {
                $habitArray[] = array('id'=>$habitVal->id,'gb_habit_recurrence'=>$habitVal->gb_habit_recurrence, 'gb_habit_recurrence_week'=>$habitVal->gb_habit_recurrence_week, 'gb_habit_recurrence_month'=>$habitVal->gb_habit_recurrence_month, 'gb_habit_name'=>$habitVal->gb_habit_name, 'gb_habit_seen'=>$habitVal->gb_habit_seen, 'gb_habit_recurrence_type'=>$habitVal->gb_habit_recurrence_type, 'gb_habit_notes' => $habitVal->gb_habit_notes, 'mile_stone_name' =>implode(',', $habitVal->getMilestoneNames()));
            }
           

            $habitData['habitId']  = $lastHabitId;
            $habitData['form']     = 'habit-list';
            $habitData['habit_list'] = $habitArray;
            if(isset($postData['save_as_draft']) && $postData['save_as_draft'] == true){
                $saveAsDraft = $postData['save_as_draft'];
            }else{
                $saveAsDraft = $postData['save_as_draft'];
            }
            $habitData['saveAsDraft'] = $saveAsDraft;

    /*  message for social-friend */

    // if($postData['update_status'] == ""){
        if (isset($postData['habit_seen']) && $postData['habit_seen'] != 'Just_Me') {
            $goal_seen = $postData["habit_seen"];
            if($goal_seen == 'Selected friends'){
                if($postData['syg2_selective_friends']){
                    $friend_id = explode(',', $postData['syg2_selective_friends']);
                } 
              }
            if($goal_seen == 'everyone'){           
                $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                        ->where('status','Accepted')
                                        ->pluck('added_client_id')
                                        ->toArray();
                 $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                    ->where('status','Accepted')
                                    ->pluck('client_id')
                                    ->toArray();
                 $friend_id = array_merge($send_request_accepred,$recieve_request_accepted);
               
              }
              if($friend_id != null){
                $friends = Clients::whereIn('id', $friend_id)
                     ->select('id', 'firstname','lastname')
                     ->get();
              }
             
            $user = Auth::user();
            if(isset($postData['habit_weeks'])){
                if(($postData['habit_weeks']) > 1){
                 $last_key = array_key_last($postData['habit_weeks']);
                    foreach ($postData['habit_weeks'] as $key => $value) {
                        if ($value === reset($postData['habit_weeks'])) {
                            $habit .= $value ;
                        }else if ($key == $last_key) {
                            $habit .= ' and '. $value ;
                        } else {
                            $habit .= ', '. $value ;   
                        }
                    }
                } else {
                     $habit = $postData['habit_weeks'][0];
                 }
              }
            $habit_text = '';
            if(isset($postData['habit_recurrence'])){
                if($postData['habit_recurrence'] == 'daily'){
                    $habit_text = ' with a frequency of every day.' ;
                }
                if($postData['habit_recurrence'] == 'weekly'){
                    $habit_text = ' with a frequency of '. $habit . ' every week.';
                }
                if($postData['habit_recurrence'] == 'monthly'){
                    $habit_text = ' day '. $postData['month'] . ' of every month.';
                }
             }

             if ($friends){
               foreach($friends as $friend){
                    $name = $friend['firstname'].' '. $friend['lastname'];
                    if( $edit_habit_form == 'yes'){
                      $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a habit  ". $postData['habit_name'] .  $habit_text ;
                     } else {
                      $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a habit  ". $postData['habit_name'] .  $habit_text ;
                     }
                     $message = new SocialUserDirectMessage();
                     $message->sender_user_id = $user->account_id;
                     $message->receiver_user_id = $friend->id;
                     $message->message = $text;
                     $message->save();

                        /* post */
                        $post = new SocialPost();
                        $post->content = $text;
                        $post->goal_client_id = Auth::user()->account_id;
                        $post->goal_friend_id = $friend->id;
                        $post->save();
                }
             }
           }    
        //  }

       /*  message for social-friend */     
       return json_encode($habitData);
        } else if ($postData["form_no"] == 4) {
            $taskHabit           = array();
            $milestonesDataArray = array();

            $data['gb_client_id']     = $clientId;
            $data['gb_task_name']     = $postData["task_name"];
            $data['gb_task_priority'] = $postData["task_priority"];

            if (isset($postData["note"])) {
                $data['gb_task_note'] = $postData["note"];
            }

            if (isset($postData["last_insert_id"])) {
                $data['goal_id'] = $postData["last_insert_id"];
            }

            if (isset($postData["task_habit_id"])) {
                $taskHabit           = GoalBuddyHabit::find($postData["task_habit_id"]);
                $data['gb_habit_id'] = $postData["task_habit_id"];
            }

            if (isset($postData["task_seen"])) {
                $data['gb_task_seen'] = $postData["task_seen"];
            }

            if (isset($postData["SYG3_selective_friends"])) {
                $data['gb_task_selective_friends'] = $postData["SYG3_selective_friends"];
            }

            if (isset($postData["task_reminders"])) {
                $data['gb_task_reminder'] = $postData["task_reminders"];
            }

            if (isset($postData["Send_mail_task_time"])) {
                $data['gb_task_reminder_time'] = $postData["Send_mail_task_time"];                
            }
            if (isset($postData["gb_task_reminder_epichq"])) {
                $data['gb_task_reminder_epichq'] = $postData["gb_task_reminder_epichq"];         
            }

            if (isset($postData["task_recurrence"])) {
                $data['gb_task_recurrence_type'] = $postData["task_recurrence"];
               
                if ($postData["task_recurrence"] == "weekly" && isset($postData['task_weeks'])) {
                    $weekData                        = implode(',', $postData['task_weeks']);
                    $data['gb_task_recurrence_week'] = $weekData;
                    
                    $habitWeekdays = explode(',', $taskHabit->gb_habit_recurrence_week);
                    $new_list = array_diff($postData['task_weeks'],$habitWeekdays);
                    
                    if(!empty($new_list)){
                        $new_list = $habitWeekdays+$new_list;
                        $new_list = implode(',', $new_list);
                    }
                    
                    if (isset($postData["task_habit_id"]) && !empty($new_list)) {
                        $updateHabit['gb_habit_recurrence_week']  = $new_list;
                        $updateHabit['gb_habit_recurrence_month'] = '';
                        $updateHabit['gb_habit_recurrence']       = '';
                        
                        $taskHabit->update($updateHabit);
                        $forHabit = true;
                  
                    }
                } else if ($postData["task_recurrence"] == "monthly" && isset($postData['month'])) {
                    $data['gb_task_recurrence_month'] = $postData['month'];
                  
                    /*if (isset($postData["task_habit_id"])) {
                        $updateHabit['gb_habit_recurrence_month'] = $postData['month'];
                        $updateHabit['gb_habit_recurrence']       = '';
                        $updateHabit['gb_habit_recurrence_week']  = '';
                        $taskHabit->update($updateHabit);
                        $forHabit = true;
                    }*/
                    
                }
            }
            $data['is_step_completed'] = true;
            $edit_task_form = null;
            if (isset($postData["task_id"]) && $postData["task_id"]) {
                $edit_task_form = 'yes';
                $task                 = GoalBuddyTask::find($postData["task_id"]);
                $goalBuddy            = $task->update($data);
                $lastTaskId           = $postData["task_id"];
                $lastInsertId         = $task->goal_id;
                $resetGoalBuddyUpdate = false;
            } else {
                $goalBuddy            = GoalBuddyTask::create($data);
                $lastTaskId           = $goalBuddy->id;
                $lastInsertId         = $postData["last_insert_id"];
                $resetGoalBuddyUpdate = true;
            }
            //$goalDetails  = GoalBuddy::with('goalBuddyTask.taskhabit')->findOrFail($lastInsertId);
            //$task_due_date=$goalDetails->gb_due_date;
            $task_due_date = GoalBuddy::where('id', $lastInsertId)->pluck('gb_due_date')->first();
            if($forHabit){
                GoalBuddyUpdate::where('habit_id', $postData["task_habit_id"])->where('task_id', '=', 0)->delete();
                $this->updateHabitActivity(['habit_id' =>$postData["task_habit_id"], 'due_date' => $task_due_date]);
            }

            GoalBuddyUpdate::where('task_id', $lastTaskId)->delete();
            $this->updateTaskActivity(['task_id' => $lastTaskId, 'due_date' => $task_due_date]);
            $goalBuddyTask = $task_due_date;

          
            
            if ($resetGoalBuddyUpdate) {
                $currDate = Carbon::now()->toDateString();
                $habit    = GoalBuddyUpdate::where('task_id', $lastTaskId)->where('due_date', '<=', $currDate)->select('habit_id')->first();

                if ($habit) {
                    GoalBuddyUpdate::where('habit_id', $habit->habit_id)->where('task_id', 0)->where('due_date', '<=', $currDate)->update(['status' => 0]);
                }

            }

            $goalDetails   = GoalBuddy::with('goalBuddyTask.taskhabit')->findOrFail($lastInsertId);
            $goalBuddyTask = $goalDetails->goalBuddyTask;

            $listData = array();
            foreach ($goalBuddyTask as $task_value) {
                $listData[] = array('id' => $task_value->id, 'gb_task_name' => $task_value->gb_task_name, 'gb_task_priority' => $task_value->gb_task_priority, 'gb_task_seen' => $task_value->gb_task_seen, 'task_habit_name' =>isset($task_value->taskhabit->gb_habit_name)?$task_value->taskhabit->gb_habit_name:'');
            }

            $goalBuddyData = GoalBuddy::findOrFail($lastInsertId);
            if(isset($postData['save_as_draft']) && $postData['save_as_draft'] == true){
                $saveAsDraft = $postData['save_as_draft'];
            }else{
                $saveAsDraft = $postData['save_as_draft'];
            }   
            
            /*  */
            if (isset($postData['task_seen']) && $postData['task_seen'] != 'Just_Me') {
                $goal_seen = $postData["task_seen"];
                if($goal_seen == 'Selected friends'){
                    if($postData['SYG3_selective_friends']){
                        $friend_id = explode(',', $postData['SYG3_selective_friends']);
                    } 
                  }
                if($goal_seen == 'everyone'){           
                    $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                            ->where('status','Accepted')
                                            ->pluck('added_client_id')
                                            ->toArray();
                     $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                        ->where('status','Accepted')
                                        ->pluck('client_id')
                                        ->toArray();
                     $friend_id = array_merge($send_request_accepred,$recieve_request_accepted);
                   
                  }
                if($friend_id){
                    $friends = Clients::whereIn('id', $friend_id)
                        ->select('id', 'firstname','lastname')
                        ->get();
                }  
                
                $user = Auth::user();

                if(isset($postData['task_weeks'])){
                    if(($postData['task_weeks']) > 1){
                     $last_key = array_key_last($postData['task_weeks']);
                        foreach ($postData['task_weeks'] as $key => $value) {
                            if ($value === reset($postData['task_weeks'])) {
                                $habit .= $value ;
                            }else if ($key == $last_key) {
                                $habit .= ' and '. $value ;
                            } else {
                                $habit .= ', '. $value ;   
                            }
                        }
                    } else {
                         $habit = $postData['task_weeks'][0];
                     }
                  }
                $habit_text = '';
                if(isset($postData['task_recurrence'])){
                    if($postData['task_recurrence'] == 'daily'){
                        $habit_text = ' with a frequency of every day.' ;
                    }
                    if($postData['task_recurrence'] == 'weekly'){
                        $habit_text = ' with a frequency of '. $habit . ' every week.';
                    }
                    if($postData['task_recurrence'] == 'monthly'){
                        $habit_text = ' day '. $postData['month'] . ' of every month.';
                    }
                 }
                // $text = $user['name'].' '. $user['last_name']. " created a goal ". $postData['task_name'] ;
                 if ($friends){
                   foreach($friends as $friend){
                         $name = $friend['firstname'].' '. $friend['lastname'];
                         if( $edit_task_form == 'yes'){
                            $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a task  ". $postData['task_name'] .  $habit_text ;
                         } else{
                           $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a task  ". $postData['task_name'] .  $habit_text ;
                         }
                         $message = new SocialUserDirectMessage();
                         $message->sender_user_id = $user->account_id;
                         $message->receiver_user_id = $friend->id;
                         $message->message = $text;
                         $message->save();

                            /* post */
                            $post = new SocialPost();
                            $post->content = $text;
                            $post->goal_client_id = Auth::user()->account_id;
                            $post->goal_friend_id = $friend->id;
                            $post->save();
                    }
                 }
               } 

            /*  */
            
            $taskData = array("goalInfo" => $goalBuddyData,'form' => 'task-list', 'task_list' => $listData, 'taskId' => $lastTaskId,'saveAsDraft' => $saveAsDraft);
            return json_encode($taskData);
        } else if ($postData["form_no"] == 5) {
            $data['gb_goal_review'] = implode(',', $postData["review"]);
            $data['final_submitted'] = 1;
            $goalBuddy              = GoalBuddy::updateBuddy($data, $postData["last_insert_id"]);
            $lastInsertId           = $postData["last_insert_id"];
            $data['form'] = 'preview';
        }

        if (!is_null($goalBuddy)) {
            $goalBuddyData = GoalBuddy::findOrFail($lastInsertId);

            $goalMilestone = $goalBuddyData->goalBuddyMilestones;
           

            $goalBuddyHabit = $goalBuddyData->goalBuddyHabit;
            $habitArray     = array();
            foreach ($goalBuddyHabit as $habitVal) {
                $habitArray[] = array('id' => $habitVal->id, 'gb_habit_recurrence' => $habitVal->gb_habit_recurrence, 'gb_habit_recurrence_week' => $habitVal->gb_habit_recurrence_week, 'gb_habit_recurrence_month' => $habitVal->gb_habit_recurrence_month, 'gb_habit_name' => $habitVal->gb_habit_name, 'gb_habit_seen' => $habitVal->gb_habit_seen, 'gb_habit_recurrence_type' => $habitVal->gb_habit_recurrence_type, 'mile_stone_name' => implode(', ', $habitVal->getMilestoneNames()));
            }

            $goalBuddyTask = $goalBuddyData->goalBuddyTask;
            $listData = array();
            foreach ($goalBuddyTask as $task_value) {
                $listData[] = array('id' => $task_value->id, 'gb_task_name' => $task_value->gb_task_name, 'gb_task_priority' => $task_value->gb_task_priority, 'gb_task_seen' => $task_value->gb_task_seen, 'task_habit_name' => $task_value->taskhabit->gb_habit_name);
            }
            if(isset($postData['save_as_draft']) && $postData['save_as_draft'] == true){
                $saveAsDraft = $postData['save_as_draft'];
            }else{
                $saveAsDraft = $postData['save_as_draft'];
            }
            $message = array("status" => "success", "goalBuddy" => $lastInsertId, "goalInfo" => $goalBuddyData, 'milestone_list' => $goalMilestone, 'habit_list' => $habitArray, 'task_list' => $listData,'saveAsDraft' => $saveAsDraft,'form'=>$data['form']);
        } else {
            $message = array("status" => "false", "goalBuddy" => null, 'milestone_list' => null, 'habit_list' => null, 'task_list' => null);
        }
        echo json_encode($message);
    }

    /**
     * Update milestone
     * @param
     * @return Response
     */
    public function fetchdataforsteponeedit($goalid)
    {
        $goalDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->findOrFail($goalid); 
        $milestoneOption = array();
        $milestonesData  = $goalDetails->goalBuddyMilestones;
        // echo '<pre>';
        // var_dump($milestonesData->toArray());
        // echo '</pre>';
        // echo '---------';
        // exit;

        if (count($milestonesData)) {
            foreach ($milestonesData as $milestones) {
                $milestoneOption[$milestones->id] = $milestones->gb_milestones_name;
            }
        }

        $habitData   = $goalDetails->goalBuddyHabit->sortBy('id');
        $taskData    = $goalDetails->goalBuddyTask->sortBy('id');
        $clientId    = $goalDetails->gb_client_id;
        $review_data = array();
        $milestonesId             = [];
        $milestonesList           = [];
        $milestonesListWithIdName = [];

        $review_data = array(
            "created_at"              => $goalDetails["created_at"],
            "deleted_at"              => $goalDetails["deleted_at"],
            "gb_achieve_description"  => $goalDetails["gb_achieve_description"],
            "gb_change_life_reason"   => $goalDetails["gb_change_life_reason"],
            "gb_company_name"         => $goalDetails["gb_company_name"],
            "gb_start_date"           => $goalDetails["gb_start_date"],
            "gb_due_date"             => $goalDetails["gb_due_date"],
            "gb_fail_description"     => $goalDetails["gb_fail_description"],
            "gb_goal_name"            => $goalDetails["gb_goal_name"],
            "gb_goal_review"          => $goalDetails["gb_goal_review"],
            "gb_goal_seen"            => $goalDetails["gb_goal_seen"],
            "gb_goal_selective_friends"            => $goalDetails["gb_goal_selective_friends"],
            "gb_goal_status"          => $goalDetails["gb_goal_status"],
            "gb_habit_name"           => $goalDetails["gb_habit_name"],
            "gb_goal_notes"           => $goalDetails["gb_goal_notes"],
            "gb_habit_notes"          => $goalDetails["gb_habit_notes"],
            "gb_habit_recurrence"     => $goalDetails["gb_habit_recurrence"],
            "gb_habit_reminder"       => $goalDetails["gb_habit_reminder"],
            "gb_habit_seen"           => $goalDetails["gb_habit_seen"],
            "gb_image_url"            => $goalDetails["gb_image_url"],
            "gb_important_accomplish" => $goalDetails["gb_important_accomplish"],
            "gb_is_top_goal"          => $goalDetails["gb_is_top_goal"],
            "gb_relevant_goal"        => $goalDetails["gb_relevant_goal"],
            "gb_relevant_goal_event"  => $goalDetails["gb_relevant_goal_event"],
            "gb_reminder_type"        => $goalDetails["gb_reminder_type"],
            "gb_task_due_date"        => $goalDetails["gb_task_due_date"],
            "gb_task_name"            => $goalDetails["gb_task_name"],
            "gb_task_priority"        => $goalDetails["gb_task_priority"],
            "gb_task_reminder"        => $goalDetails["gb_task_reminder"],
            "gb_task_seen"            => $goalDetails["gb_task_seen"],
            "gb_task_time"            => $goalDetails["gb_task_time"],
            "gb_template"             => $goalDetails["gb_template"],
            "gb_client_id"            => $goalDetails["gb_client_id"],
            "gb_user_name"            => $goalDetails["gb_user_name"],
            "gb_user_pic"             => $goalDetails["gb_user_pic"],
            "id"                      => $goalDetails["id"],
            "updated_at"              => $goalDetails["updated_at"],
            "is_step_completed"       => $goalDetails['is_step_completed'],
            "final_submitted"         => $goalDetails['final_submitted'],
        );
       

        if ($milestonesData) {
            foreach ($milestonesData as $milVal) {
                $milestonesId[]                        = $milVal->id;
                $milestonesList[]                      = ["milestones_id" => $milVal->id, "milestones_name" => $milVal->gb_milestones_name, "gb_milestones_seen" => $milVal->gb_milestones_seen, "gb_milestones_selective_friends" => $milVal->gb_milestones_selective_friends,"is_step_completed" => $milVal->is_step_completed];
                $milestonesListWithIdName[$milVal->id] = $milVal->gb_milestones_name;
            }
            $mileStoneIdStr            = implode(",", $milestonesId);
            $review_data['milestones'] = $milestonesList;
        }

        if ($habitData) {
            $hab_data = [];
            foreach ($habitData as $key => $value) {
                $mile_names = [];
                if (!empty($value['gb_milestones_id'])) {
                    foreach (explode(",", $value['gb_milestones_id']) as $mk => $mv) {
                        if (!empty($mv) && $mv != " " && in_array($mv, $milestonesListWithIdName)) {
                            $mile_names[] = $milestonesListWithIdName[$mv];
                        }
                    }
                }
                $hab_data[$key]["id"]                        = $value['id'];
                $hab_data[$key]["goal_id"]                   = $value['goal_id'];
                $hab_data[$key]["gb_client_id"]              = $value['gb_client_id'];
                $hab_data[$key]["gb_habit_name"]             = $value['gb_habit_name'];
                $hab_data[$key]["gb_habit_recurrence"]       = $value['gb_habit_recurrence'];
                $hab_data[$key]["gb_habit_recurrence_week"]  = $value['gb_habit_recurrence_week'];
                $hab_data[$key]["gb_habit_recurrence_month"] = $value['gb_habit_recurrence_month'];
                $hab_data[$key]["gb_habit_notes"]            = $value['gb_habit_notes'];
                $hab_data[$key]["gb_habit_seen"]             = $value['gb_habit_seen'];
                $hab_data[$key]["gb_habit_selective_friends"]             = $value['gb_habit_selective_friends'];
                $hab_data[$key]["gb_habit_reminder"]         = $value['gb_habit_reminder'];
                $hab_data[$key]["gb_milestones_id"]          = $value['gb_milestones_id'];
                $hab_data[$key]["gb_milestones_name"]        = implode(",", $mile_names);
                $hab_data[$key]["is_step_completed"]        = $value['is_step_completed'];
                $hab_data[$key]["gb_habit_recurrence_type"]  = $value['gb_habit_recurrence_type'];
                $hab_data[$key]["created_at"]                = (!is_null($value['created_at'])) ? $value['created_at']->toDateString() : null;
                $hab_data[$key]["updated_at"]                = (!is_null($value['updated_at'])) ? $value['updated_at']->toDateString() : null;
                $hab_data[$key]["deleted_at"]                = (!is_null($value['deleted_at'])) ? $value['deleted_at']->toDateString() : null;
            }
            $review_data['taskhabit'] = $hab_data;
        }

        if ($taskData) {
            $task_data = [];
            foreach ($taskData as $key => $value) {
                $gbHabitWeekDetails = [];
                if ($value["gb_task_recurrence_type"] == 'weekly' && $value["gb_task_recurrence_week"]) {
                    $gbHabitWeekDetails = explode(",", $value["gb_task_recurrence_week"]);
                }

                $task_data[] = array(
                    "id"                       => $value['id'],
                    "goal_id"                  => $value['goal_id'],
                    "gb_client_id"             => $value['gb_client_id'],
                    "gb_task_name"             => $value['gb_task_name'],
                    "gb_habit_name"            => $value->taskhabit ? $value->taskhabit->gb_habit_name : '',
                    "gb_task_note"             => $value['gb_task_note'],
                    "gb_task_due_date"         => $value['gb_task_due_date'],
                    "gb_task_time"             => $value['gb_task_time'],
                    "gb_task_priority"         => $value['gb_task_priority'],
                    "gb_task_seen"             => $value['gb_task_seen'],
                    "gb_task_selective_friends"             => $value['gb_task_selective_friends'],
                    "gb_task_reminder"         => $value['gb_task_reminder'],
                    "gb_habit_id"              => $value['gb_habit_id'],
                    "gb_task_recurrence_type"  => $value['gb_task_recurrence_type'],
                    "gb_task_recurrence_week"  => $value['gb_task_recurrence_week'],
                    "gb_task_recurrence_month" => $value['gb_task_recurrence_month'],
                    "is_step_completed"        => $value['is_step_completed'],

                    "created_at"               => (!is_null($value['created_at'])) ? $value['created_at']->toDateString() : null,
                    "updated_at"               => (!is_null($value['updated_at'])) ? $value['updated_at']->toDateString() : null,
                    "deleted_at"               => (!is_null($value['deleted_at'])) ? $value['deleted_at']->toDateString() : null,

                    "gbHabitWeekDetails"       => $gbHabitWeekDetails,
                );
            }
            $review_data['taskdata'] = $task_data;
        }

        $review_data = json_encode($review_data);
        $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('added_client_id')->toArray();
        $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('client_id')->toArray();
        $all_friends = array_merge($send_request_accepred,$recieve_request_accepted);
        $my_friends = Clients::select('id','business_id',DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as name"))
        ->whereIn('id',$all_friends)->get();
        // dd($goalDetails,$milestonesData,$habitData,$taskData);
        if (Session::get('hostname') == 'crm') {
            return view('goal-buddy.edit', compact('goalid', 'goalDetails', 'milestonesData', 'habitData', 'taskData', 'mileStoneIdStr', 'review_data', 'clientId', 'milestoneOption','my_friends'));
        } else {
            $this->storeSessionEditGoal($goalid, $goalDetails, $milestonesData, $habitData, $taskData, $mileStoneIdStr, $review_data, $clientId, $milestoneOption, $my_friends);
            return view('Result.goal-buddy.goal.create', compact('goalid'));
            //return view('Result.goal-buddy.edit-old', compact('goalid', 'goalDetails', 'milestonesData', 'habitData', 'taskData', 'mileStoneIdStr', 'review_data', 'clientId', 'milestoneOption','my_friends'));
        }

    }
    public function emptyFormCheck($goalDetails,$habit_data,$task_data,$milestonesData,$gb_milestones_seen,$gb_milestones_reminder){
       
        $empty_form_no = 0;
       
        if($goalDetails['gb_achieve_description'] == ""){ //Form 2
            $empty_form_no = 3;
        }
        else if($goalDetails['gb_is_top_goal'] == ""){ //Form 2
            $empty_form_no = 4;
        }
        else if($goalDetails['gb_change_life_reason'] == ""){ //Form 2
            $empty_form_no = 5;
        }
        else if($goalDetails['gb_important_accomplish'] == ""){ //Form 2
            $empty_form_no = 6;
        }
        else if($goalDetails['gb_fail_description'] == ""){ //Form 2
            $empty_form_no = 7;
        }
        else if($goalDetails['gb_relevant_goal'] == ""){ //Form 2
            $empty_form_no = 8;
        }
        else if($goalDetails['gb_relevant_goal_event'] == ""){ //Form 2
            $empty_form_no = 9;
        }
        else if($goalDetails['gb_due_date'] == ""){ //Form 2
            $empty_form_no = 10;
        }
        else if($goalDetails['gb_goal_seen'] == ""){ //Form 2
            $empty_form_no = 11;
        }
        else if($goalDetails['gb_reminder_type'] == ""){ //Form 12
            $empty_form_no = 12;
        }
        else if(count($milestonesData) == 0){ //Form 13
            $empty_form_no = 13;
        }
        else if($gb_milestones_seen == ""){ //Form 12
            $empty_form_no = 14;
        }
        else if($gb_milestones_reminder == ""){ //Form 12
            $empty_form_no = 15;
        }
        else if(count($habit_data) == 0){ //Form 13
            $empty_form_no = 16;
        }
        else if(count($task_data) == 0){ //Form 13
            $empty_form_no = 18;
        }
        else if($goalDetails['gb_goal_review'] == ""){ //Form 12
            $empty_form_no = 20;
        }
        
        if(isset($goalDetails['gb_template']) && $goalDetails['gb_template'] != null && $goalDetails['gb_template'] != ""){

            if($empty_form_no == 20 && count($habit_data) != 0){
                $empty_habit_no = 1;
                foreach ($habit_data as $habit) {
                    if($habit['is_step_completed'] == 0){
                        $empty_form_no = 16;
                        session(['empty_habit_no' => $empty_habit_no]);
                        break;
                    }
                    $empty_habit_no++;
                }
            }

            if($empty_form_no == 20 && count($task_data) != 0){
                $empty_task_no = 1;
                foreach ($task_data as $key=>$task) {
                    if($task['is_step_completed'] == 0){
                        $empty_form_no = 18;
                        session(['empty_task_no' => $empty_task_no]);
                        break;
                    }
                    $empty_task_no++;
                }
            }
            $empty_form_no++;
        }

        // echo '<pre>';
        // var_dump($empty_form_no);
        // echo '</pre>';
        // exit;
        session(['empty_form_no' => $empty_form_no]);

    }
    public function storeSessionEditGoal($goalid, $goalDetails, $milestonesData, $habitData, $taskData, $mileStoneIdStr, $review_data, $clientId, $milestoneOption, $my_friends){

        $step = 1;
        $goal_data = [];

        session(['edit_goal' => true]);
        session(['gb_goal_notes' => $goalDetails['gb_goal_notes']]);
        session(['gb_start_date' => $goalDetails['gb_start_date']]);
        session(['gb_due_date' => $goalDetails['gb_due_date']]);
        
        //Step 14
        $milestone_names = [];
        $milestone_dates = [];
        $gb_milestones_seen = "";
        $gb_milestones_selective_friends = "";
        $gb_milestones_reminder = "";
        $gb_milestones_reminder_epichq = "";

        if(!empty($milestonesData)){
            foreach ($milestonesData as $milestones) {
                array_push($milestone_names,$milestones->id.":".$milestones->gb_milestones_name);
                array_push($milestone_dates,date("Y-m-d", strtotime($milestones->gb_milestones_date)));
                
                if($milestones->gb_milestones_seen){
                    $gb_milestones_seen = $milestones->gb_milestones_seen;
                    $gb_milestones_selective_friends = $milestones->gb_milestones_selective_friends;
                }
                if($milestones->gb_milestones_reminder){
                    $gb_milestones_reminder = $milestones->gb_milestones_reminder;
                    $gb_milestones_reminder_time = $milestones->gb_milestones_reminder_time;
                    $gb_milestones_reminder_epichq = $milestones->gb_milestones_reminder_epichq;
                }
               
                
            }
        }
        $habit_data = $habitData->toArray();
        $task_data = $taskData->toArray();

        //Check if any form is no filled while goal creation it will redirect to that empty form (custom and template both)
        $this->emptyFormCheck($goalDetails,$habit_data,$task_data,$milestonesData,$gb_milestones_seen,$gb_milestones_reminder);
        
        
        if(isset($goalDetails['gb_template']) && $goalDetails['gb_template'] != null && $goalDetails['gb_template'] != ""){
            for ($i=1; $i <=21 ; $i++) { 
                session()->forget('customGoalSession'.$i);
                session()->forget('templateGoalSession'.$i);
            }
            session(['last_goal_id' => $goalid]);
            session(['goal_type' => "choose_form_template"]);

            //Step 1
            $goal_data[1]["chooseGoal"] = "choose_form_template";
            
            //Step 2
            $goal_data[2]["template"] = $goalDetails['gb_template'];
             
            //Step 3
            $goal_data[3]["name_goal"] = $goalDetails['gb_goal_name'];
            $goal_data[3]["gb_goal_name_other"] = $goalDetails['gb_goal_name_other'];
            $goal_data[3]["template"] = $goalDetails['gb_template'];
            
            //Step 4
            $goal_data[4]["describe_achieve"] = $goalDetails['gb_achieve_description'];
            $goal_data[4]["gb_achieve_description_other"] = $goalDetails['gb_achieve_description_other'];
            $goal_data[4]["prePhotoName"] = $goalDetails['gb_image_url'];
            
            //Step 5
            $goal_data[5]["goal"] = $goalDetails['gb_is_top_goal'];
            
            //Step 6
            $goal_data[6]["life-change"] = explode(',',$goalDetails['gb_change_life_reason']);
            $goal_data[6]["gb_change_life_reason_other"] = $goalDetails['gb_change_life_reason_other'];
            
            //Step 7
            $goal_data[7]["accomplish"] = explode(',',$goalDetails['gb_important_accomplish']);
            $goal_data[7]["gb_important_accomplish_other"] = $goalDetails['gb_important_accomplish_other'];
            
            //Step 8
            $goal_data[8]["fail-description"] = explode(',',$goalDetails['gb_fail_description']);
            $goal_data[8]["gb_fail_description_other"] = $goalDetails['gb_fail_description_other'];
            
            //Step 9
            $goal_data[9]["gb_relevant_goal"] = explode(',',$goalDetails['gb_relevant_goal']);
            $goal_data[9]["gb_relevant_goal_other"] = $goalDetails['gb_relevant_goal_other'];
            
            //Step 10
            $goal_data[10]["gb_relevant_goal_event"] = $goalDetails['gb_relevant_goal_event'];
            $goal_data[10]["gb_relevant_goal_event_other"] = $goalDetails['gb_relevant_goal_event_other'];
            
            //Step 11
            $goal_data[11]["start_date"] = date('D, d M Y',strtotime($goalDetails['gb_start_date']));
            $goal_data[11]["due_date"] = date('D, d M Y',strtotime($goalDetails['gb_due_date']));
            
            //Step 12
            $goal_data[12]["goal_seen"] = $goalDetails['gb_goal_seen'];
            $goal_data[12]["goal_selective_friends"] = $goalDetails['gb_goal_selective_friends'];
            
            //Step 13
            $goal_data[13]["goal-Send-mail"] = $goalDetails['gb_reminder_type'];
            $goal_data[13]["gb_reminder_goal_time"] = $goalDetails['gb_reminder_goal_time'];
            $goal_data[13]["goal-Send-epichq"] = $goalDetails['gb_reminder_type_epichq'];
            
            
            $goal_data[14]["milestones-names-id"] = implode(',',$milestone_names);
            $goal_data[14]["milestones-dates"] = implode(',',$milestone_dates);
            
            //Step 15
            $goal_data[15]["gb_milestones_seen"] = $gb_milestones_seen;
            $goal_data[15]["gb_milestones_selective_friends"] = $gb_milestones_selective_friends;
            
            //Step 16
            $goal_data[16]["milestones-Send-mail"] = $gb_milestones_reminder;
            $goal_data[16]["Send_mail_milestones_time"] = $gb_milestones_reminder_time;
            $goal_data[16]["milestones-Send-epichq"] = $gb_milestones_reminder_epichq;
            
            //Step 21
            $goal_data[21]["gb_goal_review"] = explode(',',$goalDetails['gb_goal_review']);
            $goal_data[21]["gb_goal_notes"] = $goalDetails['gb_goal_notes'];
            
            foreach($goal_data as $key=>$data){
                session(['templateGoalSession'.$key => $data]);
            }
        }else{
            for ($i=1; $i <=20 ; $i++) { 
                session()->forget('customGoalSession'.$i);
            }
            session(['last_goal_id' => $goalid]);
            session(['goal_type' => "create_new_goal"]);
            
            //Step 1
            $goal_data[1]["chooseGoal"] = "create_new_goal";
            $goal_data[1]["edit_goal"] = true;
            $goal_data[1]["goal_buddy_id"] = $goalid;
            
            //Step 2
            $goal_data[2]["name_goal"] = $goalDetails['gb_goal_name'];
            $goal_data[2]["template"] = "";
            
            //Step 3
            $goal_data[3]["describe_achieve"] = $goalDetails['gb_achieve_description'];
            $goal_data[3]["prePhotoName"] = $goalDetails['gb_image_url'];
            
            //Step 4
            $goal_data[4]["goal"] = $goalDetails['gb_is_top_goal'];
            $goal_data[4]["template"] = "";
            
            //Step 5
            $goal_data[5]["life-change"] = explode(',',$goalDetails['gb_change_life_reason']);
            $goal_data[5]["gb_change_life_reason_other"] = $goalDetails['gb_change_life_reason_other'];
            
            //Step 6
            $goal_data[6]["accomplish"] = $goalDetails['gb_important_accomplish'];
            
            //Step 7
            $goal_data[7]["fail-description"] = $goalDetails['gb_fail_description'];
            
            //Step 8
            $goal_data[8]["gb_relevant_goal"] = $goalDetails['gb_relevant_goal'];
            
            //Step 9
            $goal_data[9]["gb_relevant_goal_event"] = $goalDetails['gb_relevant_goal_event'];
            
            //Step 10
            $goal_data[10]["start_date"] = date('D, d M Y',strtotime($goalDetails['gb_start_date']));
            $goal_data[10]["due_date"] = date('D, d M Y',strtotime($goalDetails['gb_due_date']));
            
            //Step 11
            $goal_data[11]["goal_seen"] = $goalDetails['gb_goal_seen'];
            $goal_data[11]["goal_selective_friends"] = $goalDetails['gb_goal_selective_friends'];
            
            //Step 12
            $goal_data[12]["goal-Send-mail"] = $goalDetails['gb_reminder_type'];
            $goal_data[12]["gb_reminder_goal_time"] = $goalDetails['gb_reminder_goal_time'];
            $goal_data[12]["goal-Send-epichq"] = $goalDetails['gb_reminder_type_epichq'];
            
            $goal_data[13]["milestones-names-id"] = implode(',',$milestone_names);
            $goal_data[13]["milestones-dates"] = implode(',',$milestone_dates);
            
            //Step 14
            $goal_data[14]["gb_milestones_seen"] = $gb_milestones_seen;
            $goal_data[14]["gb_milestones_selective_friends"] = $gb_milestones_selective_friends;
            
            //Step 15
            $goal_data[15]["milestones-Send-mail"] = $gb_milestones_reminder;
            $goal_data[15]["Send_mail_milestones_time"] = $gb_milestones_reminder_time;
            $goal_data[15]["milestones-Send-epichq"] = $gb_milestones_reminder_epichq;
            
            //Step 20
            $goal_data[20]["gb_goal_review"] = explode(',',$goalDetails['gb_goal_review']);
            $goal_data[20]["gb_goal_notes"] = $goalDetails['gb_goal_notes'];
            
            foreach($goal_data as $key=>$data){
                session(['customGoalSession'.$key => $data]);
            }
        }
    }
    public function checkGoalForm(Request $request){
        $goalId = $request->goalId;
        $goalDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->findOrFail($goalId);
        $canFinalSubmit = true;
        $incompletedStep = '';
        if(!$goalDetails->is_step_completed){
            $canFinalSubmit = false;
            $incompletedStep = 'Step 1';
        }
        if($canFinalSubmit){
            $milestoneOption = array();
            $milestonesData  = $goalDetails->goalBuddyMilestones->sortBy('gb_milestones_date');
            if(count($milestonesData)) {
                foreach ($milestonesData as $milestones) {
                    if(!$milestones['is_step_completed']){
                        $canFinalSubmit = false;
                        $incompletedStep = 'Milestone';
                        break;
                    }
                }
            }else{
                $canFinalSubmit = false;
                $incompletedStep = 'Milestone';
            }
        }
        if($canFinalSubmit){
            $habitData   = $goalDetails->goalBuddyHabit->sortBy('id');
            if(count($habitData)) {
                foreach ($habitData as $habit) {
                    if(!$habit->is_step_completed){
                        $canFinalSubmit = false;
                        $incompletedStep = 'Habit '.$habit->gb_habit_name;
                        break;
                    }
                }
            }
        }
        if($canFinalSubmit){
            $taskData = $goalDetails->goalBuddyTask->sortBy('id');
            if(count($taskData)) {
                foreach ($taskData as $task) {
                    if(!$task->is_step_completed){
                        $canFinalSubmit = false;
                        $incompletedStep = 'Task '.$task->gb_task_name;
                        break;
                    }
                }
            }
        }
        $response = [
            'canFinalSubmit' => $canFinalSubmit,
            'incompletedStep' => $incompletedStep." is incomplete"
        ];
        return response()->json($response);

    }

    /**
     * Update milestone
     * @param
     * @return Response
     */
    public function updatemilestones(Request $request)
    {
        $response    = array('status' => '', 'msg' => '');
        $postData    = $request->all();
        $data        = $goalBuddyMilestones        = array();
        $milestoneId = 0;

        if(session()->has('last_goal_id')){
            $postData['goalId'] = session('last_goal_id');
        }

        if (isset($postData['mValue'])) {
            $data['gb_milestones_name'] = $postData['mValue'];
        }

        if (isset($postData['mDateValue'])) {
            $data['gb_milestones_date'] = $postData['mDateValue'];
        }

        if (isset($postData['status'])) {
            $data['gb_milestones_status'] = $postData['status'];
        }

        if (isset($postData['milestonesId'])) {
            $milestoneId = trim($postData['milestonesId']);
        }

        if ($milestoneId) {
            $milestones       = GoalBuddyMilestones::find($postData['milestonesId']);
            $milestonesUpdate = $milestones->update($data);
            if (isset($postData['status'])) {
                if (isset($postData['clientId'])) {
                    $clientId = $postData['clientId'];
                } else {
                    $clientId = Auth::user()->account_id;
                }

                GoalBuddyUpdate::where('milestone_id', $milestoneId)->where('gb_client_id', $clientId)->update(['status' => $postData['status']]);
            }
            $response['id']  = $milestoneId;
        } else {
            $data['goal_id'] = $postData['goalId'];
            $milestones      = GoalBuddyMilestones::create($data);
            $response['id']  = $milestones->id;
        }

        $response['status'] = "true";
        $response['msg']    = "update successfully";
        return json_encode($response);
    }

    /**
     * Delete milestone
     * @param
     * @return
     */
    public function deletemilestones(Request $request)
    {
        $response['status']  = 'error';
        $goalBuddyMilestones = [];
        $goalBuddyHabit      = [];
        $goalBuddyTask       = [];

        $deleteMilestones = GoalBuddyMilestones::find($request->eventId);

       
        

        if ($deleteMilestones) {
            $goalBuddyMilestones = GoalBuddyMilestones::where('goal_id', $deleteMilestones->goal_id)->get();
            $deleteMilestones->delete();

            $milestoneIds = [];
            foreach ($goalBuddyMilestones as $milestone) {
                $milestoneIds[] = $milestone->id;
            }

            if (count($milestoneIds)) {
                $goalBuddyHabit = GoalBuddyHabit::with('milestones')->whereIn('gb_milestones_id', $milestoneIds)->get();
                $habitIds       = [];
                foreach ($goalBuddyHabit as $habit) {
                    $habitIds[] = $habit->id;
                }

                if (count($habitIds)) {
                    $goalBuddyTask = GoalBuddyTask::with('taskhabit')->whereIn('gb_habit_id', $habitIds)->get();
                }

            }

            // session()->forget('customGoalSession13');
            // session()->forget('templateGoalSession14');


            $response['status']    = "true";
            $response['listData']  = $goalBuddyMilestones;
            $response['habitData'] = $goalBuddyHabit;
            $response['taskData']  = $goalBuddyTask;
            $response['msg']       = "Milestones delete successfully";
        }
        return json_encode($response);
    }

    /**
     *  showhabit
     *     @param habit id
     *    @return habit response
     */
    public function showhabit(Request $request)
    {
        $habit_note_list = GoalBuddyHabitList::get();
        $goalBuddyHabitData = GoalBuddyHabit::findOrFail($request->habitId);
        $message            = array("status" => "true", "goalBuddy" => $goalBuddyHabitData,'habitNoteList'=>$habit_note_list);
        return json_encode($message);
    }

    /**
     *  showtask
     *     @param task id
     *    @return task data response
     */
    public function showtask(Request $request)
    {
        $goalBuddyTaskData = GoalBuddyTask::findOrFail($request->taskId);

        // $allHabitArray = GoalBuddyHabit::where('goal_id',$goalBuddyTaskData->goal_id)->select('gb_habit_name','id')->get();
        $allHabitArray = GoalBuddyHabit::where('goal_id', $goalBuddyTaskData->goal_id)->get();
        $message       = array("status" => "true", "goalBuddy" => $goalBuddyTaskData, "habitTask" => $allHabitArray);
        return json_encode($message);
    }

    /**
     * Edit Goal
     * @param goal id
     * @return edit goal view
     */
    public function editgoal($goalid)
    {
        $goalDetails    = GoalBuddy::findOrFail($goalid);
        $milestonesData = $goalDetails->goalBuddyMilestones;
        $clientId       = $goalDetails->gb_client_id;

        if (Session::get('hostname') == 'crm') {
            return view('goal-buddy.edit', compact('goalid', 'goalDetails', 'milestonesData', 'clientId'));
        } else {
            return view('Result.goal-buddy.edit-old', compact('goalid', 'goalDetails', 'milestonesData', 'clientId'));
        }

    }

    /**
     * Edit Milestone
     * @param Milestone Id
     * @return Milestone
     */
    public function editmilestone($milestoneid)
    {
        $milestoneDetails = GoalBuddyMilestones::findOrFail($milestoneid);
        $milestonesData   = GoalBuddyMilestones::where('goal_id', $milestoneDetails->goal_id)->get();
        // dd( $milestonesData);
        if (Session::get('hostname') == 'crm') {
            return view('goal-buddy.edit', compact('milestonesData'));
        } else {
            return view('Result.goal-buddy.edit-old', compact('milestonesData'));
        }

    }

    /**
     * edit habit
     * @param habit id
     * @return habit view
     */
    public function edithabit($habitid)
    {
        $habitDetails   = GoalBuddyHabit::findOrFail($habitid);
        $milestonesData = GoalBuddyMilestones::where('goal_id', $habitDetails->goal_id)->select('gb_milestones_name', 'id')->get();
        $clientId       = $habitDetails->gb_client_id;

        if (Session::get('hostname') == 'crm') {
            return view('goal-buddy.edit', compact('milestonesData', 'habitDetails', 'clientId'));
        } else {
            return view('Result.goal-buddy.edit-old', compact('milestonesData', 'habitDetails', 'clientId'));
        }

    }

    /**
     * edit task
     * @param task id
     * @return task view
     */
    public function edittask($taskid)
    {
        $taskDetails        = GoalBuddyTask::findOrFail($taskid);
        $habitData          = GoalBuddyHabit::where('goal_id', $taskDetails->goal_id)->select('gb_habit_name', 'id')->get();
        $gbHabitWeekDetails = [];
        if ($taskDetails->gb_task_recurrence_type == 'weekly' && $taskDetails->gb_task_recurrence_week) {
            $gbHabitWeekDetails = explode(",", $taskDetails->gb_task_recurrence_week);
        }

        $clientId = $taskDetails->gb_client_id;

        if (Session::get('hostname') == 'crm') {
            return view('goal-buddy.edit', compact('habitData', 'taskDetails', 'gbHabitWeekDetails', 'clientId'));
        } else {
            return view('Result.goal-buddy.edit-old', compact('habitData', 'taskDetails', 'gbHabitWeekDetails', 'clientId'));
        }

    }

    /**
     * Delete habit
     * @param
     * @return
     */
    public function deletehabit(Request $request)
    {
        $response      = array('habitData' => '', 'taskData' => '', 'status' => '', 'msg' => '');
        $goalHabit     = [];
        $goalBuddyTask = [];
        $deletehabit   = GoalBuddyHabit::find($request->eventId);

        if ($deletehabit) {
            $deletehabit->delete();
            $goalHabit = GoalBuddyHabit::where('goal_id', $deletehabit->goal_id)->get();

            $habitIds = [];
            foreach ($goalHabit as $habit) {
                $habitIds[] = $habit->id;
            }

            if (count($habitIds)) {
                $goalBuddyTask = GoalBuddyTask::with('taskhabit')->whereIn('gb_habit_id', $habitIds)->get();
            }

            $response['habitData'] = $goalHabit;
            $response['taskData']  = $goalBuddyTask;
            $response['status']    = "true";
            $response['msg']       = "Habit delete successfully";
        }
        return json_encode($response);
    }

    /**
     * Delete task
     * @param
     * @return
     */
    public function deletetask(Request $request)
    {
        $response   = array('status' => '', 'msg' => '');
        $deletetask = GoalBuddyTask::find($request->eventId);
        GoalBuddyUpdate::where('task_id', '=', $request->taskId)->delete();
        if ($deletetask) {
            $deletetask->delete();
            $response['status'] = "true";
            $response['msg']    = "Task delete successfully";
        }
        return json_encode($response);
    }

    /**
     * delete particular goal.
     * @param  GoalBuddyRequest $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $response = array('status' => '', 'msg' => '');
        $goal     = GoalBuddy::find($request->eventId);
        if ($goal) {
            GoalBuddyUpdate::where('goal_id', $request->eventId)->delete();
             $delete_goal = $goal->delete();
             if($delete_goal){
                $deleteMilestones = GoalBuddyMilestones::where('goal_id', $request->eventId)->get();
                if( $deleteMilestones){
                    GoalBuddyMilestones::where('goal_id', $request->eventId)->delete();
                 }
                $deletehabit   = GoalBuddyHabit::where('goal_id', $request->eventId)->get();
                if( $deletehabit){
                    GoalBuddyHabit::where('goal_id', $request->eventId)->delete();
                 }
                $deletetask = GoalBuddyTask::where('goal_id', $request->eventId)->get();
                if( $deletetask){
                    GoalBuddyTask::where('goal_id', $request->eventId)->delete();
                 }
             }
            $response['status'] = "true";
            $response['msg']    = "Goal delete successfully";
        }
        return json_encode($response);
    }

    /**
     * insert Milestone Updates data
     * @param goalId
     * @return milestonesId
     */
    private function insertMilestoneUpdates($goalId, $clientId)
    {
        $goalBuddyMilestones = GoalBuddyMilestones::where('goal_id', $goalId)->get();
        $milestonesId        = [];
        if ($goalBuddyMilestones) {
            GoalBuddyUpdate::where('goal_id', $goalId)->where('milestone_id', '!=', 0)->delete();
            foreach ($goalBuddyMilestones as $milVal) {
            	if($milVal->gb_milestones_date) {
	                $milestonesId[]            = $milVal->id;
	                $inserData['goal_id']      = $milVal->goal_id;
	                $inserData['milestone_id'] = $milVal->id;
	                $inserData['gb_client_id'] = $clientId;
	                $inserData['due_date']     = dateStringToDbDate($milVal->gb_milestones_date);
	                $goalupdate                = GoalBuddyUpdate::create($inserData);
            	}
            }
            return implode(",", $milestonesId);
        }
    }

    /**
     * insert Milestone Updates data
     * @param goalId
     * @return milestonesId
     */
    private function insertHabitUpdates($goalId, $clientId, $due_date)
    {
        $goalBuddyGoals = GoalBuddyHabit::where('goal_id', $goalId)->get();
        $habitIds       = [];
        if ($goalBuddyGoals) {
            GoalBuddyUpdate::where('goal_id', $goalId)->where('habit_id', '!=', 0)->delete();
            foreach ($goalBuddyGoals as $milVal) {
                $habitIds[]                = $milVal->id;
                $inserData['goal_id']      = $milVal->goal_id;
                $inserData['habit_id']     = $milVal->id;
                $inserData['gb_client_id'] = $clientId;
                $inserData['due_date']     = $due_date;
                $goalupdate                = GoalBuddyUpdate::create($inserData);
            }
            return implode(",", $habitIds);
        }
    }

    /**
     * insert Milestone Updates data
     * @param goalId
     * @return milestonesId
     */
    private function insertTaskUpdates($goalId, $clientId, $due_date)
    {
        $goalBuddyTask = GoalBuddyTask::where('goal_id', $goalId)->get();
        $taskId        = [];
        if ($goalBuddyTask) {
            GoalBuddyUpdate::where('goal_id', $goalId)->where('task_id', '!=', 0)->delete();
            foreach ($goalBuddyTask as $milVal) {
                $taskId[] = $milVal->id;

                $inserData['goal_id']      = $milVal->goal_id;
                $inserData['task_id']      = $milVal->id;
                $inserData['habit_id']     = $milVal->gb_habit_id;
                $inserData['due_date']     = $due_date;
                $inserData['gb_client_id'] = $clientId;
                // $inserData['due_date'] = dateStringToDbDate($milVal->gb_milestones_date);
                $goalupdate = GoalBuddyUpdate::create($inserData);
            }
            return implode(",", $taskId);
        }
    }

    /**
     * fetch a newly created habit from table.
     *
     * @param  GoalBuddyRequest $request
     * @return message
     */
    public function getHabit(Request $request)
    {
        $goalBuddyHabitRespData = collect();
        $goalBuddyHabitData     = GoalBuddy::getHabitById($request->habit_id);
        if (count($goalBuddyHabitData)) {
            foreach ($goalBuddyHabitData as $HabitData) {
                $mileId                        = (int) $HabitData->gb_milestones_id;
                $HabitData->gb_milestones_name = GoalBuddyMilestones::where('id', $mileId)->pluck('gb_milestones_name')->first();
                $goalBuddyHabitRespData        = $HabitData;
            }
        }

        $message = array("status" => "true", "goalBuddy" => $goalBuddyHabitRespData);
        echo json_encode($message);
    }

    /**
     * fetch a newly created Task from table.
     *
     * @param  GoalBuddyRequest $request
     * @return message
     */
    public function getTask(Request $request)
    {
        $goalBuddyTaskData = GoalBuddy::getTaskById($request->task_id);
        $message           = array("status" => "true", "goalBuddy" => $goalBuddyTaskData);
        echo json_encode($message);
    }

    /**
     * fetch goal template details
     *
     * @param  Integer $remplateId
     * @return JSON
     */
    public function getGoalTemplate(Request $request, $templateId)
    {
        $response = ['status' => false, 'goal_template' => []];
        if ($templateId) {
            $fetchData = null;
            if(isset($request->goal_id) && !empty($request->goal_id)){
                $fetchData = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->find($request->goal_id);
            }
            if($templateId == 7){
                $goalDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTaskc1.taskhabit')->find($templateId);

            }else if($templateId == 3){
                $goalDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTaskId.taskhabit')->find($templateId);  
            }else{
                $goalDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->find($templateId);    
            }
            
            // dd( $fetchData,  $goalDetails);
            if ($goalDetails) {
                $response['status']        = true;
                $response['goal_template'] = $goalDetails;
                $response['fetch_data'] = $fetchData;
            }

        }
        return json_encode($response);
    }

 	/**
     * fetch habit details
     * @param  Request $request
     * @return JSON
     */
    public function getHabitUpdate(Request $request) {
    	$postData = $request->all();
    	$response = array('status' => false, 'habitDetails' => []);

    	if(count($postData) && array_key_exists('habit_id', $postData)) {
    		$habitDetails = GoalBuddyHabit::find($postData['habit_id']);

    		$habitArray = array('id' => $habitDetails->id, 'gb_habit_recurrence' => $habitDetails->gb_habit_recurrence, 'gb_habit_recurrence_week' => $habitDetails->gb_habit_recurrence_week, 'gb_habit_recurrence_month' => $habitDetails->gb_habit_recurrence_month, 'gb_habit_name' => $habitDetails->gb_habit_name, 'gb_habit_seen' => $habitDetails->gb_habit_seen,'gb_habit_selective_friends' => $habitDetails->gb_habit_selective_friends, 'gb_habit_recurrence_type' => $habitDetails->gb_habit_recurrence_type, 'gb_habit_notes' => $habitDetails->gb_habit_notes, 'mile_stone_name' => implode(', ', $habitDetails->getMilestoneNames()));
            

    		$response['status'] = true;
    		$response['habitDetails'] = $habitArray;
    	}
    

    	return json_encode($response);
    }

    /**
     * fetch task details
     * @param  Request $request
     * @return JSON
     */
    public function getTaskUpdate(Request $request) {
    	$postData = $request->all();
    	$response = array('status' => false, 'taskDetails' => []);

    	if(count($postData) && array_key_exists('taskId', $postData)) {
    		$taskDetails = GoalBuddyTask::find($postData['taskId']);

    		$taskArray = array('id' => $taskDetails->id, 'gb_task_name' => $taskDetails->gb_task_name, 'gb_task_priority' => $taskDetails->gb_task_priority, 'gb_task_seen' => $taskDetails->gb_task_seen, 'task_habit_name' => $taskDetails->taskhabit->gb_habit_name, 
    			'gb_task_recurrence_type' => $taskDetails->gb_task_recurrence_type, 'gb_task_recurrence_week' => $taskDetails->gb_task_recurrence_week, 'gb_task_note' => $taskDetails->gb_task_note,'gb_task_recurrence_month' => $taskDetails->gb_task_recurrence_month);

    		$response['status'] = true;
    		$response['taskDetails'] = $taskArray;
    	}


    	return json_encode($response);
    }

    /**
     * fetch goal details
     * @param  Request $request
     * @return JSON
     */
    public function getGoalUpdate(Request $request) {
    	$postData = $request->all();
    	$response = array('status' => false, 'goalDetails' => []);

    	if(count($postData) && array_key_exists('goalId', $postData)) {
    		$goalDetails = GoalBuddy::find($postData['goalId']);
    		
    		$goalArray = array('id' => $goalDetails->id, 'gb_goal_name' => $goalDetails->gb_goal_name, 'gb_achieve_description' => $goalDetails->gb_achieve_description, 'gb_fail_description' => $goalDetails->gb_fail_description, 'gb_goal_seen' => $goalDetails->gb_goal_seen,  'gb_due_date' => $goalDetails->gb_due_date);

    		$response['status'] = true;
    		$response['goalDetails'] = $goalArray;
    	}

    	return json_encode($response);
    }

    /**
     * fetch milestones details
     * @param  Request $request
     * @return JSON
     */
    public function getMilestoneUpdate(Request $request) {
    	$postData = $request->all();
    	$response = array('status' => false, 'milestoneDetails' => []);

    	if(count($postData) && array_key_exists('milestoneId', $postData)) {
    		$milestoneDetails = GoalBuddyMilestones::find($postData['milestoneId']);
    		
    		$milestoneArray = array('id' => $milestoneDetails->id, 'gb_milestones_name' => $milestoneDetails->gb_milestones_name, 'gb_milestones_date' => $milestoneDetails->gb_milestones_date, 'gb_milestones_seen' => $milestoneDetails->gb_milestones_seen, 'gb_milestones_reminder' => $milestoneDetails->gb_milestones_reminder);

    		$response['status'] = true;
    		$response['milestoneDetails'] = $milestoneArray;
    	}

    	return json_encode($response);
    }

    public function updateGoalStatus(Request $request)
    {
        $goalBuddy = GoalBuddy::find($request->goal_id);
        $goalBuddy->update([
            'gb_goal_status' => $request->status
        ]);
        $response['status'] = "true";
        $response['msg']    = "update successfully";
        return json_encode($response);
    }


   
    /* cron job */
    public function due_message(){
        /*  message for social-friend */
        $date = Carbon::now()->format("Y-m-d");
        $goal = GoalBuddy::where('gb_due_date', $date)->get();
        $milestones = GoalBuddyMilestones::where('gb_milestones_date', $date)->get();
       
        $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                    ->where('status','Accepted')
                    ->pluck('added_client_id')
                    ->toArray();
        $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                    ->where('status','Accepted')
                    ->pluck('client_id')
                    ->toArray();
        $friend_ids = array_merge($send_request_accepred,$recieve_request_accepted);
      
        $user = Auth::user();  
 
        if (count($goal) > 0) {
            foreach($goal as $goal_data){
                $goal_seen = $goal_data["gb_goal_seen"];
                if($goal_seen == 'Selected friends'){
                    if($goal_data['gb_goal_selective_friends']){
                        $friend_id = explode(',', $goal_data['gb_goal_selective_friends']);
                    } 
                }
                if($goal_seen == 'everyone'){           
                    $friend_id = $friend_ids;
                }
                if($friend_id){
                    $friends = Clients::whereIn('id', $friend_id)
                        ->select('id', 'firstname','lastname')
                        ->get();
                }        
                
                if ($friends){
                foreach($friends as $friend){
                        $name = $friend['firstname'].' '. $friend['lastname'];
                        $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a goal ". $goal_data['gb_goal_name'] ." which is due today" ;
                        $message = new SocialUserDirectMessage();
                        $message->sender_user_id = $user->account_id;
                        $message->receiver_user_id = $friend->id;
                        $message->message = $text;
                        $message->save();
                     }
                    }
                }
            }
    /* end 1st foreach  */

            if (count($milestones) > 0) {
               
                foreach($milestones as $milestone){
                    $goal_seen = $milestone["gb_milestones_seen"];
                    if($goal_seen == 'Selected friends'){
                        if($milestone['gb_milestones_selective_friends']){
                            $milestone_friend_id = explode(',', $milestone['gb_milestones_selective_friends']);
                        } 
                    }
                
                    if($goal_seen == 'everyone'){           
                        $milestone_friend_id = $friend_ids;  
                    }
                    $friends = Clients::whereIn('id', $milestone_friend_id)
                            ->select('id', 'firstname','lastname')
                            ->get();
                        
                    if ($friends){
                        foreach($friends as $friend){
                                $name = $friend['firstname'].' '. $friend['lastname'];
                                $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a milestone ". $milestone['gb_milestones_name'] ." which is due today" ;
                                $message = new SocialUserDirectMessage();
                                $message->sender_user_id = $user->account_id;
                                $message->receiver_user_id = $friend->id;
                                $message->message = $text;
                                $message->save();
                            }
                    }
                  }
                }

      /* end 2nd  foreach  */
       }  

       public function getAllHabit(Request $request){
        if(isset($request->goal_id))
            $goal_id = $request->goal_id;
        else
            $goal_id = session('last_goal_id');

        $allHabitArray = GoalBuddyHabit::where('goal_id', $goal_id)->get();
        $message       = array("status" => "true", "allHabit" => $allHabitArray);
        return json_encode($message);
       }

// new goal-process funciotns - start

    public function create()
    {
        //delete all goal session data 
        for ($i=1; $i <21 ; $i++) { 
            session()->forget('customGoalSession'.$i);
        }
        for ($i=1; $i <=21 ; $i++) { 
            session()->forget('templateGoalSession'.$i);
        }
        session()->forget('last_goal_id');
        session()->forget('goal_buddy_id');
        session()->forget('edit_goal');
        session()->forget('goal_type');
        session()->forget('gb_goal_notes');
        session()->forget('empty_form_no');
        session()->forget('empty_task_no');
        session()->forget('empty_habit_no');
        
        //delete all goal session data - end
        return view('Result.goal-buddy.goal.create');
    }

    public function friendDataForAutoComplete(){
        $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('added_client_id')->toArray();
        $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)->where('status','Accepted')->pluck('client_id')->toArray();
        $all_friends = array_merge($send_request_accepred,$recieve_request_accepted);
        $my_friends = Clients::select('id as value',DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as tag"))
        ->whereIn('id',$all_friends)->get();

        $response['my_friends'] = $my_friends;
        return Response::json($response);
    }

    public function loadFirstStep(){


        $html = View::make('Result.partials.goal-steps.custom.step1');
        $response['html'] = $html->render();

        //Edit goal parameters define
        if(session('edit_goal') == true && session('goal_type') == "choose_form_template" && session('last_goal_id'))
        {
            $response['edit_goal'] = true;
            $template_id = session('templateGoalSession2')['template'];
            $response['template_id'] = $template_id;
            $response['goal_buddy_id'] = session('last_goal_id');
            $response['goal_type'] = session('goal_type');

            if(session('empty_form_no')) $response['empty_form_no'] = session('empty_form_no');
            if(session('empty_habit_no')) $response['empty_habit_no'] = session('empty_habit_no');
            if(session('empty_task_no')) $response['empty_task_no'] = session('empty_task_no');
            $response['gb_goal_notes'] = session('gb_goal_notes');

            $response['gb_start_date'] = session('gb_start_date');
            $response['gb_due_date'] = session('gb_due_date');
            
            
        }
        if(session('edit_goal') == true && session('goal_type') == "create_new_goal" && session('last_goal_id'))
        {
            $response['edit_goal'] = true;
            $response['goal_buddy_id'] = session('last_goal_id');
            $response['goal_type'] = session('goal_type');

            $response['empty_form_no'] = session('empty_form_no');
            $response['gb_goal_notes'] = session('gb_goal_notes');

            $response['gb_start_date'] = session('gb_start_date');
            $response['gb_due_date'] = session('gb_due_date');

        }

        return Response::json($response);
    }

    
    public function editGoaldetails(Request $request){

        $step = $request->current_step;
        
        $goalDataNew = $this->getGoalDataNew($request);


        if(session('edit_goal') == true && session('goal_type') == "choose_form_template" && session('empty_habit_no')) 
            $goalDataNew['current_habit_step'] = session('empty_habit_no')-1;
        if(session('edit_goal') == true && session('goal_type') == "choose_form_template" && session('empty_task_no')) 
            $goalDataNew['current_task_step'] = session('empty_task_no')-1;

        $response['html'] = '';
        if($request->goal_type == "create_new_goal"){
            $html = View::make('Result.partials.goal-steps.custom.step'.$step, compact('goalDataNew'));
            $response['html'] = $html->render();
        }
        else if($request->goal_type == "choose_form_template"){
            $html = View::make('Result.partials.goal-steps.template.step'.$step, compact('goalDataNew'));
            $response['html'] = $html->render();
        }
        
        return Response::json($response);
    }
    public function storeNew(Request $request){
    
        if($request->current_step == 1){
            session()->forget('goalSessionData');
        }
        //No need to save data while click on previous button
        if($request->move != 'back'){
            $this->saveGoalDataNew($request); 
        }

        //For Milestone save in session for prev button only
        if($request->move == 'back'){
            if($request->goal_type == "create_new_goal" && $request->current_step == 13){
                $this->saveGoalDataNew($request); 
            }else if ($request->goal_type == "choose_form_template" && $request->current_step == 14){
                $this->saveGoalDataNew($request); 
            }
        }

        //retrun Response::json($request);

        $view = $this->manageSteps($request);
        return Response::json($view);
    }

    public function saveGoalDataNew($postData){
        $clientId = Auth::user()->account_id;

        //dd($postData);

        //save every step in sesion - start
        if($postData->goal_type == 'create_new_goal'){
            
            session(['customGoalSession'.$postData->current_step => $postData->except('goal_type', 'current_step', 'move')]);
            if($postData->current_step == 12){
                $this->saveForm1($clientId,$postData->goal_type);    
            }else if($postData->current_step == 15){
               $this->saveForm2($clientId,$postData->goal_type);    
            }else if($postData->current_step == 16){
               $this->saveForm3($clientId,$postData->goal_type);    
            }else if($postData->current_step == 18){
               $this->saveForm4($clientId,$postData->goal_type);    
            }else if($postData->current_step == 20){
               $this->saveForm5($clientId,$postData->goal_type);    
            }  
        }
        else if($postData->goal_type == 'choose_form_template'){
            session(['templateGoalSession'.$postData->current_step => $postData->except('goal_type', 'current_step', 'move')]);

            // var_dump($postData->current_step);
            // exit;

            if($postData->current_step == 13){
                $this->saveForm1($clientId,$postData->goal_type);    
            }else if($postData->current_step == 16){
               $this->saveForm2($clientId,$postData->goal_type);    
            }else if($postData->current_step == 17){
               $this->saveForm3($clientId,$postData->goal_type);    
            }else if($postData->current_step == 19){
               $this->saveForm4($clientId,$postData->goal_type);    
            }else if($postData->current_step == 21){
               $this->saveForm5($clientId,$postData->goal_type);    
            }  

        }
        
        //save every step in sesion - end

        
        // var_dump($postData->goal_type);
        // exit;

              

        /*if($postData->current_step == 15){
            for ($i=1; $i <=15 ; $i++) { 
                print_r(session('customGoalSession'.$i));
            }
            dd("end");
        }*/
        /*$lastInsertId    = '';
        $clientId = Auth::user()->account_id;
        $data = [];

        if($postData->current_step >= 1 && $postData->current_step <= 12){ //store in session for 3 steps
            $this->storeDataInSessionForm1($postData);
        }
        
        if($postData->current_step > 12 && $postData->current_step <= 15){ //milestone
            $this->storeDataInSessionForm2($postData);
        }

        if($postData->current_step == 15){
            $this->saveForm2($clientId);    
        }

        if($postData->current_step > 15 && $postData->current_step <= 16){ //habit
            $this->storeDataInSessionForm3($postData);
        }

        if($postData->current_step == 16){
            $this->saveForm3($clientId);    
        }

        if($postData->current_step == 18){ // task
            $this->saveForm4($clientId);    
        }

        if($postData->current_step == 20){ // SMART
            $this->saveForm5($clientId);    
        }*/
    }

   /* public function storeDataInSessionForm1($request){
        $postData = $request->all();

        if (isset($postData["goal_notes"])) {
            $data['gb_goal_notes'] = $postData["goal_notes"];
            if (isset($postData["last_insert_id"]) && $postData["last_insert_id"] != '' &&  $data['gb_goal_notes'] != "") {           
                $goalDetails  = GoalBuddy::findOrFail($postData["last_insert_id"]);
                $goalBuddy    = $goalDetails->update($data);
            }
        }  

            if ($postData["image"]) {
                $data['gb_user_pic'] = $postData["image"];
            }

            if ($postData["name_goal"]) {
                $data['gb_goal_name'] = $postData["name_goal"];
            }

            if ($postData["gb_goal_name_other"]) {
                $data['gb_goal_name_other'] = $postData["gb_goal_name_other"];
            }
            if ($postData["describe_achieve"]) {
                $data['gb_achieve_description'] = $postData["describe_achieve"];
            }
            if ($postData["gb_achieve_description_other"]) {
                $data['gb_achieve_description_other'] = $postData["gb_achieve_description_other"];
            }

            if ($postData["template"]) {
                if ($postData["accomplish"]) {
                    $data["gb_important_accomplish"] = implode(',', $postData["accomplish"]);
                }
                if ($postData["gb_relevant_goal"]) {
                    $data["gb_relevant_goal"] = implode(',', $postData["gb_relevant_goal"]);
                }
            }else{
                if ($postData["accomplish"]) {
                    $data["gb_important_accomplish"] = $postData["accomplish"];
                }
                if ($postData["gb_relevant_goal"]) {
                    $data["gb_relevant_goal"] = $postData["gb_relevant_goal"];
                }
            }
            if ($postData["gb_important_accomplish_other"]) {
                $data['gb_important_accomplish_other'] = $postData["gb_important_accomplish_other"];
            }

            if ($postData["gb_fail_description_other"]) {
                $data['gb_fail_description_other'] = $postData["gb_fail_description_other"];
            }
            if ($postData["gb_relevant_goal_other"]) {
                $data['gb_relevant_goal_other'] = $postData["gb_relevant_goal_other"];
            }

            if ($postData["change_life"]) {
                $data['gb_change_life_reason'] = implode(',', $postData["change_life"]);
            }

            if (isset($postData["gb_change_life_reason_other"])) {
                $data['gb_change_life_reason_other'] = $postData["gb_change_life_reason_other"];
            }            


            if ($postData["template"]) {
                if ($postData["failDescription"]) {
                    $data["gb_fail_description"] = implode(',', $postData["failDescription"]);
                }     
            }else{
                if ($postData["failDescription"]) {
                    $data["gb_fail_description"] = $postData["failDescription"];
                }      
            }
           

            if ($postData["template"]) {
                $data['gb_template'] = $postData["template"];
            }

            if ($postData["due_date"]) {
                $data['gb_due_date'] = $postData["due_date"];
            }

            if (isset($postData["ClientName"]) && $postData["ClientName"] != '') {
                $data['gb_user_name'] = $postData["ClientName"];
            }

            if (isset($postData['gb_relevant_goal_event'])) {
                $data["gb_relevant_goal_event"] = $postData["gb_relevant_goal_event"];
            }
            if (isset($postData['gb_relevant_goal_event_other'])) {
                $data["gb_relevant_goal_event_other"] = $postData["gb_relevant_goal_event_other"];
            }

            if (isset($postData['image'])) {
                $data['gb_image_url'] = $postData["image"];
            }

            if (isset($postData['goal_seen'])) {
                $data['gb_goal_seen'] = $postData["goal_seen"];
            }

            if (isset($postData['goal_selective_friends'])) {
                $data['gb_goal_selective_friends'] = $postData["goal_selective_friends"];
            }

            if (isset($postData['goal_year'])) {
                $data['gb_is_top_goal'] = $postData["goal_year"];
            }

            if (isset($postData['send_msg_type'])) {
                $data['gb_reminder_type'] = $postData["send_msg_type"];
            }
            if (isset($postData['gb_reminder_type_epichq'])) {
                $data['gb_reminder_type_epichq'] = $postData["gb_reminder_type_epichq"];
            }           
            if (isset($postData['Send_mail_time'])) {
                $data['gb_reminder_goal_time'] = $postData["Send_mail_time"];
            }


            session()->push('goalSessionData', $data);
    }*/

    public function storeDataInSessionForm2($request){
         //$response    = array('status' => '', 'msg' => '');
        $postData    = $request->all();
        $data        = $goalBuddyMilestones        = array();
        $milestoneId = 0;
        //$postData['goalId'] = session('last_goal_id');

        if (isset($postData['mValue'])) {
            $data['gb_milestones_name'] = $postData['mValue'];
        }

        if (isset($postData['mDateValue'])) {
            $data['gb_milestones_date'] = $postData['mDateValue'];
        }

        if (isset($postData['status'])) {
            $data['gb_milestones_status'] = $postData['status'];
        }

        /*if (isset($postData['milestonesId'])) {
            $milestoneId = trim($postData['milestonesId']);
        }*/

        if (isset($postData["last_insert_id"]) && $postData["last_insert_id"] != '') {
            $data['lastInsertGoalId'] = $postData["last_insert_id"];
        }

        if (isset($postData["goal_id_mile"]) && $postData["goal_id_mile"] != '') {
            $data['lastInsertGoalId'] = $postData["goal_id_mile"];
        }

        if (isset($postData["Send_mail_milestones_time"])) {
            $data['gb_milestones_reminder_time'] = $postData["Send_mail_milestones_time"];
        }else{
            $data['gb_milestones_reminder_time'] = NULL;
        }
        if (isset($postData["gb_milestones_selective_friends"])) {
            $data['gb_milestones_selective_friends'] = $postData["gb_milestones_selective_friends"];
        }else{
            $data['gb_milestones_selective_friends'] = NULL;
        }

        session()->push('milestoneSessionData', $data);

        //return json_encode($response);
    }

    public function saveform1($clientId,$goal_type){

    
        //$goal_type = session('customGoalSession1')['chooseGoal'];
        $data = [];

        
        if($goal_type == 'create_new_goal'){
            for ($i=2; $i <=12 ; $i++) { 
                $data = array_merge($data,session('customGoalSession'.$i));
            }
        }else if($goal_type == 'choose_form_template'){
            for ($i=2; $i <=13 ; $i++) { 
                $data = array_merge($data,session('templateGoalSession'.$i));
            }
        }

        $postData = $data;
        

        // echo '<pre>';
        // var_dump($data);
        // echo '</pre>';
        // exit;

        if ($postData["template"]) {
            $data['gb_template'] = $postData["template"];
        }

        if ($postData["prePhotoName"]) {
            $data['gb_user_pic'] = $postData["prePhotoName"];
        }

        if (isset($postData['prePhotoName'])) {
            $data['gb_image_url'] = $postData["prePhotoName"];
        }

        if ($postData["name_goal"]) {
            $data['gb_goal_name'] = $postData["name_goal"];
        }

        if (isset($postData['goal'])) {
            $data['gb_is_top_goal'] = $postData["goal"];
        }

        if ($postData["describe_achieve"]) {
            $data['gb_achieve_description'] = $postData["describe_achieve"];
        }
        if (isset($postData['goal_selective_friends'])) {
            $data['gb_goal_selective_friends'] = $postData["goal_selective_friends"];
        }

        if (isset($postData['goal-Send-epichq'])) {
            $data['gb_reminder_type_epichq'] = $postData["goal-Send-epichq"];
        }

        if ($postData["life-change"]) {
            $data['gb_change_life_reason'] = implode(',', $postData["life-change"]);
        }

        if (isset($postData["gb_change_life_reason_other"])) {
            $data['gb_change_life_reason_other'] = $postData["gb_change_life_reason_other"];
        }    

        if ($postData["accomplish"]) {
            $data['gb_important_accomplish'] = $postData["accomplish"];

            if(is_array($postData["accomplish"])){
                $data['gb_important_accomplish'] = implode(',', $postData["accomplish"]);
            }
        }

        if ($postData["fail-description"]) {
            $data['gb_fail_description'] = $postData["fail-description"];
            if(is_array($postData["fail-description"])){
                $data['gb_fail_description'] = implode(',', $postData["fail-description"]);
            }
        }
        if ($postData["gb_relevant_goal"] && is_array($postData["gb_relevant_goal"])) {
            $data['gb_relevant_goal'] = implode(',', $postData["gb_relevant_goal"]);
        }

        if ($postData["goal_seen"]) {
            $data['gb_goal_seen'] = $postData["goal_seen"];
        }

        if ($postData["due_date"]) {
            $data['gb_due_date'] = date("Y-m-d", strtotime($postData["due_date"]));
        }
        

        if ($postData["start_date"]) {
            $data['gb_start_date'] = date("Y-m-d", strtotime($postData["start_date"]));
        }
        
        if ($postData["goal-Send-mail"]) {
            $data['gb_reminder_type'] = $postData["goal-Send-mail"];
        }

        $data['gb_company_name'] = 'company';
        $data['gb_client_id'] = $clientId;

        if(session()->has('last_goal_id')){
            $postData["last_insert_id"] = session('last_goal_id');
            $postData["update_status"] = 'update-yes';
        }

       

        if (isset($postData["goal_notes"])) {
            $data['gb_goal_notes'] = $postData["goal_notes"];
            if (isset($postData["last_insert_id"]) && $postData["last_insert_id"] != '' &&  $data['gb_goal_notes'] != "") {           
                $goalDetails  = GoalBuddy::findOrFail($postData["last_insert_id"]);
                $goalBuddy    = $goalDetails->update($data);
            }
        } 
        if ($postData["update_status"] == 'update-yes') {
            $goalDetails  = GoalBuddy::findOrFail($postData["last_insert_id"]);
            $data['is_step_completed'] = true;
            $goalBuddy    = $goalDetails->update($data);
            $lastInsertId = $postData["last_insert_id"];
            GoalBuddyUpdate::where('goal_id', $lastInsertId)->where('milestone_id', '=', 0)->where('habit_id', '=', 0)->where('task_id', '=', 0)->delete();
        } else {
            $data['is_step_completed'] = true;
            $goalBuddy    = GoalBuddy::create($data);
            $lastInsertId = $goalBuddy->id;
        }
        /*  message for social-friend */
        // if(isset($postData['update_status'])){
            if (isset($postData['goal_seen']) && $postData['goal_seen']!='Just_me') {
                $goal_seen = $postData["goal_seen"];
                if($goal_seen == 'Selected friends'){
                    if($postData['goal_selective_friends']){
                        $friend_id = explode(',', $postData['goal_selective_friends']);
                    } 
                }
                if($goal_seen == 'everyone'){           
                    $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                        ->where('status','Accepted')
                                        ->pluck('added_client_id')
                                        ->toArray();
                    $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                    ->where('status','Accepted')
                                    ->pluck('client_id')
                                    ->toArray();
                    $friend_id = array_merge($send_request_accepred,$recieve_request_accepted);
                
                }
                    
                  
                if($friend_id){
                    $friends = Clients::whereIn('id', $friend_id)
                        ->select('id', 'firstname','lastname')
                        ->get();
                }
                        
                $user = Auth::user();
                $due_date = date("d-m-Y",  strtotime($postData['due_date']));
                if ($friends && $postData['name_goal']){
                  
                    if( $postData['name_goal'] == "Other"){
                         $goalName = $postData['gb_goal_name_other'];
                    }else {
                        $goalName = $postData['name_goal'];
                    }
                    foreach($friends as $friend){
                        
                            $name = $friend['firstname'].' '. $friend['lastname'];
                            if($postData['update_status'] == "update-yes"){
                            $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a goal ". $goalName ." which is due on ". $due_date ;
                            } else {
                            $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a goal ". $goalName ." which is due on ". $due_date ;
                            }
                            
                        //  $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a goal ". $goalBuddy['gb_goal_name'] ." which is due on ". $due_date ;
                            $message = new SocialUserDirectMessage();
                            $message->sender_user_id = $user->account_id;
                            $message->receiver_user_id = $friend->id;
                            $message->message = $text;
                            $message->save();
                            /* post */
                            $post = new SocialPost();
                            $post->content = $text;
                            $post->goal_client_id = Auth::user()->account_id;
                            $post->goal_friend_id = $friend->id;
                            $post->save();

                    }
                }
            }
        // }
                         
         /*  end message for social-friend */

        # Get goal template details
        if ($postData["update_status"] != 'update-yes') {
            if (array_key_exists('gb_template', $data) && $data['gb_template']) {
                $goalTemplateDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->find($data['gb_template']);
                if ($goalTemplateDetails) {
                    # Add goal template milestone to new goal
                    if ($goalTemplateDetails->goalBuddyMilestones) {
                        $templateMilestones = $goalTemplateDetails->goalBuddyMilestones;

                        foreach ($templateMilestones as $milestone) {
                            $tmpMilestoneData = [
                                'gb_milestones_name' => $milestone->gb_milestones_name,
                                'goal_id'            => $lastInsertId,
                                'gb_client_id'       => $clientId,
                            ];
                            if (GoalBuddyMilestones::where($tmpMilestoneData)->count() == 0) {
                                //$savedData = GoalBuddyMilestones::create($tmpMilestoneData);
                            }

                        }
                    }

                    # Add goal template habit to new goal
                    if ($goalTemplateDetails->goalBuddyHabit) {
                        $templateHabits = $goalTemplateDetails->goalBuddyHabit;
          
                        foreach ($templateHabits as $habit) {
                            $tmpHabitData = [
                                'gb_habit_name'  => $habit->gb_habit_name,
                                // 'gb_habit_notes' => $habit->gb_habit_notes,
                                'goal_id'        => $lastInsertId,
                                'gb_client_id'   => $clientId,
                                'is_primary'     => 1,
                            ];

                            if (GoalBuddyHabit::where($tmpHabitData)->count() == 0) {
                                $savedData = GoalBuddyHabit::create($tmpHabitData);
                            }

                        }
                    }

                    # Add goal template task to new goal
                    if ($goalTemplateDetails->goalBuddyTask) {
                        $templateTasks = $goalTemplateDetails->goalBuddyTask->sortBy('gb_habit_id');

                        foreach ($templateTasks as $task) {
                            if($task->taskhabit) {
                                $tmpTaskHabitId  = GoalBuddyHabit::where([
                                    'goal_id' => $lastInsertId,
                                    'gb_client_id' => $clientId,
                                    'gb_habit_name' => $task->taskhabit->gb_habit_name
                                ])->pluck('id')->first();


                                //$task->gb_task_note = trim(preg_replace('/\s\s+/', ' ', $task->gb_task_note));

                                $tmpTaskData = [
                                    'gb_task_name' => $task->gb_task_name,
                                    //'gb_task_note' => $task->gb_task_note,
                                    'goal_id'      => $lastInsertId,
                                    'gb_client_id' => $clientId,
                                    'gb_habit_id' =>  $tmpTaskHabitId,
                                    'is_primary'     => 1,
                                ];

                                if (GoalBuddyTask::where($tmpTaskData)->count() == 0) {
                                    $savedData = GoalBuddyTask::create($tmpTaskData);
                                }
                            }

                        }
                    }
                }
            }
        }

        $inserData['goal_id']      = $lastInsertId;
        $inserData['gb_client_id'] = $clientId;
        $inserData['due_date']     = $data["due_date"];

        $goalupdate = GoalBuddyUpdate::create($inserData);

        session(['last_goal_id' => $lastInsertId]);

        session(['goal_buddy_id' => $lastInsertId]); //Made new session variable for get current goal_id

    }

    public function saveForm2($clientId,$goal_type){

        //$goal_type = session('customGoalSession1')['chooseGoal'];
        $data = [];

        if($goal_type == 'create_new_goal'){
            for ($i=13; $i <=15 ; $i++) { 
                $data = array_merge($data,session('customGoalSession'.$i));
            }
        }else if($goal_type == 'choose_form_template'){
            for ($i=14; $i <=16 ; $i++) { 
                $data = array_merge($data,session('templateGoalSession'.$i));
            }
        }
        

        $postData = $data;

        $postData["goalId"] = session('last_goal_id');
        $postData['lastInsertGoalId'] = session('last_goal_id');
        $postData["last_insert_id"] = session('last_goal_id');
        $postData["clientId"] == $clientId;

        if(session()->has('last_milestone_id')){
            $milestonesId = session('last_milestone_id');
        }


        //
        $milestonesInsertData = array();
            $timestamp            = createTimestamp();

            if (isset($postData["last_insert_id"]) && $postData["last_insert_id"] != '') {
                $lastInsertGoalId = $postData["last_insert_id"];
            }

            if (isset($postData["goal_id_mile"]) && $postData["goal_id_mile"] != '') {
                $lastInsertGoalId = $postData["goal_id_mile"];
            }
            if (isset($postData["milestones-Send-mail"]) && $postData["milestones-Send-mail"] != '') {
                $postData["gb_milestones_reminder"] = $postData["milestones-Send-mail"];
            }
            if (isset($postData["milestones-Send-epichq"]) && $postData["milestones-Send-epichq"] != '') {
                $postData["gb_milestones_reminder_epichq"] = $postData["milestones-Send-epichq"];
            }
            if (isset($postData["Send_mail_milestones_time"])) {
                $gb_milestones_reminder_time = $postData["Send_mail_milestones_time"];
            }
            if (isset($postData["gb_milestones_selective_friends"])) {
                $gb_milestones_selective_friends = $postData["gb_milestones_selective_friends"];
            }else{
                $gb_milestones_selective_friends = NULL;
            }

            $lastInsertId    = $lastInsertGoalId;
            $milestonArray   = array();
            $milestoneUpdate = GoalBuddyMilestones::where('goal_id', $lastInsertId)->get();
            foreach ($milestoneUpdate as $mileston) {
                $mileston->update(['gb_milestones_seen' => $postData['gb_milestones_seen'], 'gb_milestones_reminder' => $postData["gb_milestones_reminder"],'gb_milestones_reminder_epichq' => $postData["gb_milestones_reminder_epichq"],'gb_milestones_selective_friends' => $gb_milestones_selective_friends, 'gb_milestones_reminder_time' => $gb_milestones_reminder_time,  'gb_client_id' => $clientId,'is_step_completed'=> true]);

                $milestonArray[] = array('id' => $mileston->id, 'gb_milestones_name' => $mileston->gb_milestones_name);
            }

            $mileStoneIdStr = $this->insertMilestoneUpdates($lastInsertId, $clientId);
            if(isset($postData['save_as_draft']) && $postData['save_as_draft'] == true){
                $saveAsDraft = $postData['save_as_draft'];
            }else{
                $saveAsDraft = $postData['save_as_draft'];
            }

            /*  message for social-friend */
            if( count($postData['milestones-names-id']) > 0 ){
             
                if (isset($postData['gb_milestones_seen']) && $postData['gb_milestones_seen'] != 'Just_Me') {
                    $goal_seen = $postData["gb_milestones_seen"];
                 
                    if($goal_seen == 'Selected friends'){
                        if($postData['gb_milestones_selective_friends']){
                            $friend_id = explode(',', $postData['gb_milestones_selective_friends']);
                        } 
                    }
                    if($goal_seen == 'everyone'){           
                        $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                                ->where('status','Accepted')
                                                ->pluck('added_client_id')
                                                ->toArray();
                        $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                            ->where('status','Accepted')
                                            ->pluck('client_id')
                                            ->toArray();
                        $friend_id = array_merge($send_request_accepred,$recieve_request_accepted);
                    
                    }
               
                    if($friend_id){
                        $friends = Clients::whereIn('id', $friend_id)
                            ->select('id', 'firstname','lastname')
                            ->get();
                    }        
                    $user = Auth::user();
                /*  */
                $milestoneArray = explode(',', $postData['milestones-names-id']);
                $milestoneDateArray = explode(',', $postData['milestones-dates']);

                foreach($milestoneArray as $key => $name){ 
                    $result = explode(':', $name);      
                    $due_date = date("d-m-Y",  strtotime($milestoneDateArray[$key]));
                    // $text = $user['name'].' '. $user['last_name']. " created a goal ". $result[1]  ." which is due on ". $due_date ;
                    if ($friends){
                    foreach($friends as $friend){
                            $name = $friend['firstname'].' '. $friend['lastname'];
                            if(!empty($postData['milestones_id'])){
                                $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a milestone ". $result[1]  ." which is due on ". $due_date ;
                            } else {
                                $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a milestone ". $result[1]  ." which is due on ". $due_date ;
                            }
                            
                            $message = new SocialUserDirectMessage();
                            $message->sender_user_id = $user->account_id;
                            $message->receiver_user_id = $friend->id;
                            $message->message = $text;
                            $message->save();
                        /* post */
                            $post = new SocialPost();
                            $post->content = $text;
                            $post->goal_client_id = Auth::user()->account_id;
                            $post->goal_friend_id = $friend->id;
                            $post->save();

                        }
                    }
                }
            }
                
        }
                          
    }

    public function saveForm3($clientId,$goal_type){

        //$goal_type = session('customGoalSession1')['chooseGoal'];
        $data = [];

        if($goal_type == 'create_new_goal'){
            for ($i=16; $i <=16 ; $i++) { 
                $data = array_merge($data,session('customGoalSession'.$i));
            }
        }else if($goal_type == 'choose_form_template'){
            for ($i=17; $i <=17 ; $i++) { 
                $data = array_merge($data,session('templateGoalSession'.$i));
            }
        }
        $postData = $data;
        $postData["last_insert_id"] = session('last_goal_id');
        $data['gb_client_id']  = $clientId;
            
        //Current goal id to update goal record
        if ($postData["last_insert_id"] != '') {
            $data['goal_id'] = $postData["last_insert_id"];
        }
        
        //Habit data save to DB//
        if(isset($postData["SYG_habits"])){
            $data['gb_habit_name'] = $postData["SYG_habits"];
        }
        if ($postData["SYG_habit_recurrence"] != '') {
            $data['gb_habit_recurrence_type'] = $postData["SYG_habit_recurrence"];
            if ($postData["SYG_habit_recurrence"] == "weekly" && isset($postData['SYG_habit_recurrence'])) {
                $weekData                          = implode(',', $postData['habitRecWeek']);
                $data['gb_habit_recurrence_week']  = $weekData;
                $data['gb_habit_recurrence_month'] = '';
                $data['gb_habit_recurrence']       = '';
            } else if ($postData["SYG_habit_recurrence"] == "monthly" && isset($postData['SYG_habit_recurrence'])) {
                $data['gb_habit_recurrence_month'] = $postData['month'];
                $data['gb_habit_recurrence_week']  = '';
                $data['gb_habit_recurrence']       = '';
            } else {
                $data['gb_habit_recurrence']       = $postData["SYG_habit_recurrence"];
                $data['gb_habit_recurrence_month'] = '';
                $data['gb_habit_recurrence_week']  = '';
            }
        }
        if (isset($postData["SYG_notes"])) {
            if(is_array($postData["SYG_notes"])){
                if(count($postData["SYG_notes"]) > 1){
                    $new_array = array();
                    foreach($postData["SYG_notes"] as $habitnote){
                        $new_array[] = trim(preg_replace('/\s\s+/', ' ', $habitnote));
                    }
                    $data['gb_habit_notes'] = implode(',',$new_array);
                }else{
                    $data['gb_habit_notes'] = implode(',',$postData["SYG_notes"]);
                    $data['gb_habit_notes'] = trim($data['gb_habit_notes']);
                }
            }else{
                $data['gb_habit_notes'] = $postData["SYG_notes"];
            }
        }

        if (isset($postData["syg2_see_habit"])) {
            $data['gb_habit_seen'] = $postData["syg2_see_habit"];
        }
        if (isset($postData["syg2_selective_friends"])) {
            $data['gb_habit_selective_friends'] = $postData["syg2_selective_friends"];
        }
        if (isset($postData["habits-send-mail"])) {
            $data['gb_habit_reminder'] = $postData["habits-send-mail"];
        }
        if (isset($postData["Send_mail_habits_time"])) {
            $data['gb_habit_reminder_time'] = $postData["Send_mail_habits_time"];             
        }
        if (isset($postData["habits-send-epichq"])) {
            $data['gb_habit_reminder_epichq'] = $postData["habits-send-epichq"];         
        }
        if ($postData["gb_habit_note_other"]) {
            $data['gb_habit_note_other'] = $postData["gb_habit_note_other"];
        }
        if (isset($postData["milestone_value"]) && is_array($postData["milestone_value"])) {
            $data['gb_milestones_id'] = implode(',', $postData["milestone_value"]);
        } else {
            $data['gb_milestones_id'] = $postData["milestone_value"];
        }
        //////////////////////////////////
            
        $data['is_step_completed'] = true;
        $edit_habit_form = null;

        if (isset($postData["habit_id"]) && $postData["habit_id"] && $postData['habit_id'] != '') {
            $edit_habit_form = 'yes';
            $habits       = GoalBuddyHabit::find($postData["habit_id"]);
            $goalBuddy    = $habits->update($data);
            $lastHabitId  = $postData["habit_id"];
            $lastInsertId = $habits->goal_id;
        } else if (isset($data["gb_habit_name"]) && $data["gb_habit_name"]) {
            $edit_habit_form = 'yes';
            $habits       = GoalBuddyHabit::where('gb_habit_name', $data['gb_habit_name'])
                            ->where('goal_id', $data['goal_id'])->first();
            if(count($habits)){

                $habits = $habits->toArray();
                //unset all 
                unset($data["habitid"]);
                unset($data['SYG_habits']);
                unset($data['SYG_habit_recurrence']);
                unset($data['SYG_notes']);
                unset($data['goal_notes']);
                unset($data['previusSelectedMilesone']);
                unset($data['milestone_value']);
                unset($data['syg2_see_habit']);
                unset($data['syg2_selective_friends']);
                unset($data['habits-send-mail']);
                unset($data['habit_notes']);
                unset($data['habits-send-epichq']);
                unset($data['gb_milestones_reminder']);
                unset($data['gb_milestones_reminder_epichq']);
                unset($data['gb_task_reminder_epichq']);
                unset($data['habit_name']);
                unset($data['habit_id']);
                unset($data['habit_recurrence']);
                unset($data['habit_milestone']);
                unset($data['habit_seen']);
                unset($data['habit_reminders']);
                unset($data['task_id']);
                unset($data['task_habit_id']);
                unset($data['task_name']);
                unset($data['task_priority']);
                unset($data['task_recurrence']);
                unset($data['task_seen']);
                unset($data['task_notes']);
                unset($data['task_reminders']);
                unset($data['SYG3_selective_friends']);
                unset($data['habitRecWeek']);
                unset($data['template']);
                unset($data['current_habit_step']);
                unset($data['repeat_habit']);
                unset($data['repeat_task']);
                unset($data['current_task_step']);
                
                //unset all - end
                
                $habits       = GoalBuddyHabit::find($habits["id"]);
                $goalBuddy    = $habits->update($data);
                $lastHabitId  = $habits->id;
                $lastInsertId = $habits->goal_id;    

            }else{
                $goalBuddy    = GoalBuddyHabit::create($data);
                $lastHabitId  = $goalBuddy->id;
                $lastInsertId = $postData["last_insert_id"];    
            }
            
        } else{
            $goalBuddy    = GoalBuddyHabit::create($data);
            $lastHabitId  = $goalBuddy->id;
            $lastInsertId = $postData["last_insert_id"];
        }

        $goalDetails    = GoalBuddy::with('goalBuddyHabit')->findOrFail($lastInsertId);
        $habit_due_date = $goalDetails->gb_due_date;

        //GoalBuddyUpdate table to show updates in goal calender
        if ($lastHabitId) {
            GoalBuddyUpdate::where('habit_id', $lastHabitId)->where('task_id', '=', 0)->delete();
            $this->updateHabitActivity(['habit_id' => $lastHabitId, 'due_date' => $habit_due_date]);
        }

        $goalBuddyHabit = $goalDetails->goalBuddyHabit;
        $habitArray     = array();
        foreach ($goalBuddyHabit as  $habitVal) {
            $habitArray[] = array('id'=>$habitVal->id,'gb_habit_recurrence'=>$habitVal->gb_habit_recurrence, 'gb_habit_recurrence_week'=>$habitVal->gb_habit_recurrence_week, 'gb_habit_recurrence_month'=>$habitVal->gb_habit_recurrence_month, 'gb_habit_name'=>$habitVal->gb_habit_name, 'gb_habit_seen'=>$habitVal->gb_habit_seen, 'gb_habit_recurrence_type'=>$habitVal->gb_habit_recurrence_type, 'gb_habit_notes' => $habitVal->gb_habit_notes, 'mile_stone_name' =>implode(', ', $habitVal->getMilestoneNames()));
        }
        

        $habitData['habitId']  = $lastHabitId;
        $habitData['form']     = 'habit-list';
        $habitData['habit_list'] = $habitArray;
        if(isset($postData['save_as_draft']) && $postData['save_as_draft'] == true){
            $saveAsDraft = $postData['save_as_draft'];
        }else{
            $saveAsDraft = $postData['save_as_draft'];
        }
        $habitData['saveAsDraft'] = $saveAsDraft;

        /*  message for social-friend */
        // if($postData['update_status'] == ""){
        if (isset($postData['syg2_see_habit']) && $postData['syg2_see_habit'] != 'Just_Me') {
            $goal_seen = $postData["syg2_see_habit"];
            if($goal_seen == 'Selected friends'){
                if($postData['syg2_selective_friends']){
                    $friend_id = explode(',', $postData['syg2_selective_friends']);
                } 
            }
            if($goal_seen == 'everyone'){           
                $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                        ->where('status','Accepted')
                                        ->pluck('added_client_id')
                                        ->toArray();
                $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                    ->where('status','Accepted')
                                    ->pluck('client_id')
                                    ->toArray();
                $friend_id = array_merge($send_request_accepred,$recieve_request_accepted);
            
            }
            if($friend_id != null){
                $friends = Clients::whereIn('id', $friend_id)
                    ->select('id', 'firstname','lastname')
                    ->get();
            }
            
            $user = Auth::user();
            if(isset($postData['habitRecWeek'])){
                if(($postData['habitRecWeek']) > 1){
                $last_key = array_key_last($postData['habitRecWeek']);
                    foreach ($postData['habitRecWeek'] as $key => $value) {
                        if ($value === reset($postData['habitRecWeek'])) {
                            $habit .= $value ;
                        }else if ($key == $last_key) {
                            $habit .= ' and '. $value ;
                        } else {
                            $habit .= ', '. $value ;   
                        }
                    }
                } else {
                    $habit = $postData['habitRecWeek'][0];
                }
            }
            $habit_text = '';
            if(isset($postData['SYG_habit_recurrence'])){
                if($postData['SYG_habit_recurrence'] == 'daily'){
                    $habit_text = ' with a frequency of every day.' ;
                }
                if($postData['SYG_habit_recurrence'] == 'weekly'){
                    $habit_text = ' with a frequency of '. $habit . ' every week.';
                }
                if($postData['SYG_habit_recurrence'] == 'monthly'){
                    $habit_text = ' day '. $postData['month'] . ' of every month.';
                }
            }

            if ($friends){
            foreach($friends as $friend){
                    $name = $friend['firstname'].' '. $friend['lastname'];
                    if( $edit_habit_form == 'yes'){
                      $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a habit ". $postData['SYG_habits'] .  $habit_text ;
                    // $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a habit  ". $postData['habit_name'] .  $habit_text ;
                    } else {
                      $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a habit ". $postData['SYG_habits'] .  $habit_text ;
                    }
                    $message = new SocialUserDirectMessage();
                    $message->sender_user_id = $user->account_id;
                    $message->receiver_user_id = $friend->id;
                    $message->message = $text;
                    $message->save();

                        /* post */
                        $post = new SocialPost();
                        $post->content = $text;
                        $post->goal_client_id = Auth::user()->account_id;
                        $post->goal_friend_id = $friend->id;
                        $post->save();
                }
            }
        }    
    }

    public function saveForm4($clientId,$goal_type){

        //$goal_type = session('customGoalSession1')['chooseGoal'];
        $data = [];

        if($goal_type == 'create_new_goal'){
            for ($i=18; $i <=18 ; $i++) { 
                $data = array_merge($data,session('customGoalSession'.$i));
            }
        }else if($goal_type == 'choose_form_template'){
            for ($i=19; $i <=19 ; $i++) { 
                $data = array_merge($data,session('templateGoalSession'.$i));
            }
        }
        

        $postData = $data;

        // echo '<pre>';
        // //var_dump(session()->all());
        // var_dump($postData);
        // echo '</pre>';
        // exit;

        $postData["goalId"] = session('last_goal_id');
        $postData['lastInsertGoalId'] = session('last_goal_id');
        $postData["last_insert_id"] = session('last_goal_id');
        //$postData["clientId"] == $clientId;
        //dd($postData);
        //
        $taskHabit           = array();
        $milestonesDataArray = array();

        $data['gb_client_id']     = $clientId;
        if (isset($postData["last_insert_id"])) {
            $data['goal_id'] = $postData["last_insert_id"];
        }


        //Get habit data from Task recurrence
        if (isset($postData["habit_value"])) {
            $taskHabit           = GoalBuddyHabit::find($postData["habit_value"]);
            $data['gb_habit_id'] = $postData["habit_value"];
        }

        if(isset($postData["SYG3_task"])){
            $data['gb_task_name']     = $postData["SYG3_task"];
        }
        if(isset($postData["Priority"])){
            $data['gb_task_priority']     = $postData["Priority"];
        }
        if (isset($postData["SYG_task_note"])) {
            if(is_array($postData["SYG_task_note"])){
                if(count($postData["SYG_task_note"]) > 1){
                    $new_array = array();
                    foreach($postData["SYG_task_note"] as $tasknote){
                        $new_array[] = trim(preg_replace('/\s\s+/', ' ', $tasknote));
                    }
                    $data['gb_task_note'] = implode(',',$new_array);
                }else{
                    $data['gb_task_note'] = implode(',',$postData["SYG_task_note"]);
                    $data['gb_task_note'] = trim($data['gb_task_note']);
                }
            }else{
                $data['gb_task_note'] = $postData["SYG_task_note"];
            }
        }

        // echo '<pre>';
        // //var_dump(session()->all());
        // var_dump($data);
        // echo '</pre>';
        // exit;

        if (isset($postData["SYG3_see_task"])) {
            $data['gb_task_seen'] = $postData["SYG3_see_task"];
        }
        if (isset($postData["SYG3_selective_friends"])) {
            $data['gb_task_selective_friends'] = $postData["SYG3_selective_friends"];
        }
        if (isset($postData["creattask-send-mail"])) {
            $data['gb_task_reminder'] = $postData["creattask-send-mail"];
        }
        if (isset($postData["creattask-send-epichq"])) {
            $data['gb_task_reminder_epichq'] = $postData["creattask-send-epichq"];         
        }

        if (isset($postData["Send_mail_task_time"])) {
            $data['gb_task_reminder_time'] = $postData["Send_mail_task_time"];                
        }
        if (isset($postData["SYG_task_recurrence"])) {
            $data['gb_task_recurrence_type'] = $postData["SYG_task_recurrence"];
           
            if ($postData["SYG_task_recurrence"] == "weekly" && isset($postData['task_recurrence_week'])) {
                
                $data['gb_task_recurrence_week'] = implode(',', $postData['task_recurrence_week']);
                $data['gb_task_recurrence_month'] = '';
                
                //Task recurrence should be linked with the recurrence of associated habit
                $habitWeekdays = explode(',', $taskHabit->gb_habit_recurrence_week);
                $new_list = array_diff($postData['task_recurrence_week'],$habitWeekdays);
                if(!empty($new_list)){
                    $new_list = $habitWeekdays+$new_list;
                    $new_list = implode(',', $new_list);
                }
                if (isset($postData["habit_value"]) && !empty($new_list)) {
                    $updateHabit['gb_habit_recurrence_week']  = $new_list;
                    $updateHabit['gb_habit_recurrence_month'] = '';
                    $updateHabit['gb_habit_recurrence']       = '';
                    $taskHabit->update($updateHabit);
                    $forHabit = true;
                }
                ///////
            
            } else if ($postData["SYG_task_recurrence"] == "monthly" && isset($postData['gb_task_recurrence_month'])) {
                $data['gb_task_recurrence_month'] = $postData['gb_task_recurrence_month'];
                $data['gb_task_recurrence_week'] = '';
              
                /*if (isset($postData["habit_value"])) {
                    $updateHabit['gb_habit_recurrence_month'] = $postData['month'];
                    $updateHabit['gb_habit_recurrence']       = '';
                    $updateHabit['gb_habit_recurrence_week']  = '';
                    $taskHabit->update($updateHabit);
                    $forHabit = true;
                }*/
                
            }
        }
    
        $data['is_step_completed'] = true;
        $edit_task_form = null;
        
        

        if (isset($postData["task_id"]) && $postData["task_id"] && $postData['task_id'] != "" && $postData['task_id'] != null) {
            $edit_task_form = 'yes';
            $task                 = GoalBuddyTask::find($postData["task_id"]);
            $goalBuddy            = $task->update($data);
            $lastTaskId           = $postData["task_id"];
            $lastInsertId         = $task->goal_id;
            $resetGoalBuddyUpdate = false;
        } else if (isset($data["gb_task_name"]) && $data["gb_task_name"]) {
            $edit_task_form = 'yes';
            $goal_task = GoalBuddyTask::where('gb_task_name', $data['gb_task_name'])
                            ->where('goal_id', $data['goal_id'])->first();
            
            if(count($goal_task))
            {
                $goal_task = $goal_task->toArray();
                //unset all 
                unset($data["habitid"]);
                unset($data['SYG_habits']);
                unset($data['SYG_habit_recurrence']);
                unset($data['SYG_notes']);
                unset($data['previusSelectedMilesone']);
                unset($data['milestone_value']);
                unset($data['syg2_see_habit']);
                unset($data['syg2_selective_friends']);
                unset($data['habits-send-mail']);
                unset($data['habit_notes']);
                unset($data['habits-send-epichq']);
                unset($data['gb_milestones_reminder']);
                unset($data['gb_milestones_reminder_epichq']);
                unset($data['habit_name']);
                unset($data['habit_id']);
                unset($data['habit_recurrence']);
                unset($data['habit_milestone']);
                unset($data['habit_seen']);
                unset($data['habit_reminders']);
                unset($data['task_id']);
                unset($data['task_habit_id']);
                unset($data['task_name']);
                unset($data['task_priority']);
                unset($data['task_recurrence']);
                unset($data['task_seen']);
                unset($data['task_notes']);
                unset($data['task_reminders']);
                unset($data['SYG3_selective_friends']);
                unset($data['habitRecWeek']);
                unset($data["associatedHabitWithTask"]);
                unset($data["goalTaskData"]);
                unset($data["habit_value"]);
                unset($data["SYG3_task"]);
                unset($data["SYG_task_note"]);
                unset($data["SYG_task_note"]);
                unset($data["Priority"]);
                unset($data["SYG_task_recurrence"]);
                unset($data["SYG3_see_task"]);
                unset($data["creattask-send-mail"]);
                unset($data["creattask-send-epichq"]);
                unset($data["task_weeks"]);
                unset($data["gb_habit_reminder_epichq"]);
                unset($data["task_recurrence_week"]);
                unset($data["template"]);
                unset($data['current_habit_step']);
                unset($data['repeat_habit']);
                unset($data['repeat_task']);
                unset($data['current_task_step']);
                //unset all - end
                
                $task                 = GoalBuddyTask::find($goal_task["id"]);
                $goalBuddy            = $task->update($data);
                $lastTaskId           = $task->id;
                $lastInsertId         = $task->goal_id;
                $resetGoalBuddyUpdate = false;   
                
            }else{
                $goalBuddy            = GoalBuddyTask::create($data);
                $lastTaskId           = $goalBuddy->id;
                $lastInsertId         = $postData["last_insert_id"];
                $resetGoalBuddyUpdate = true;   
            }
            
        } else{
            $goalBuddy            = GoalBuddyTask::create($data);
            $lastTaskId           = $goalBuddy->id;
            $lastInsertId         = $postData["last_insert_id"];
            $resetGoalBuddyUpdate = true;
        }

        //return true;
        // echo '<pre>';
        // var_dump($goalBuddy);
        // echo '</pre>';
        // exit;
        

        //$goalDetails  = GoalBuddy::with('goalBuddyTask.taskhabit')->findOrFail($lastInsertId);
        //$task_due_date=$goalDetails->gb_due_date;
        $task_due_date = GoalBuddy::where('id', $lastInsertId)->pluck('gb_due_date')->first();
        if($forHabit){
            GoalBuddyUpdate::where('habit_id', $postData["habit_value"])->where('task_id', '=', 0)->delete();
            $this->updateHabitActivity(['habit_id' =>$postData["habit_value"], 'due_date' => $task_due_date]);
        }

        GoalBuddyUpdate::where('task_id', $lastTaskId)->delete();
        $this->updateTaskActivity(['task_id' => $lastTaskId, 'due_date' => $task_due_date]);
        $goalBuddyTask = $task_due_date;

        
        if ($resetGoalBuddyUpdate) {
            $currDate = Carbon::now()->toDateString();
            $habit    = GoalBuddyUpdate::where('task_id', $lastTaskId)->where('due_date', '<=', $currDate)->select('habit_id')->first();

            if ($habit) {
                GoalBuddyUpdate::where('habit_id', $habit->habit_id)->where('task_id', 0)->where('due_date', '<=', $currDate)->update(['status' => 0]);
            }

        }

        $goalDetails   = GoalBuddy::with('goalBuddyTask.taskhabit')->findOrFail($lastInsertId);
        $goalBuddyTask = $goalDetails->goalBuddyTask;

       

        $listData = array();
        foreach ($goalBuddyTask as $task_value) {
            $listData[] = array('id' => $task_value->id, 'gb_task_name' => $task_value->gb_task_name, 'gb_task_priority' => $task_value->gb_task_priority, 'gb_task_seen' => $task_value->gb_task_seen, 'task_habit_name' =>isset($task_value->taskhabit->gb_habit_name)?$task_value->taskhabit->gb_habit_name:'');
        }

        $goalBuddyData = GoalBuddy::findOrFail($lastInsertId);
        if(isset($postData['save_as_draft']) && $postData['save_as_draft'] == true){
            $saveAsDraft = $postData['save_as_draft'];
        }else{
            $saveAsDraft = $postData['save_as_draft'];
        }   
        
        
        if (isset($postData['SYG3_see_task']) && $postData['SYG3_see_task'] != 'Just_Me') {
            $goal_seen = $postData["SYG3_see_task"];
            if($goal_seen == 'Selected friends'){
                if($postData['SYG3_selective_friends']){
                    $friend_id = explode(',', $postData['SYG3_selective_friends']);
                } 
              }


      
            if($goal_seen == 'everyone'){           
                $send_request_accepred = SocialFriend::where('client_id',Auth::user()->account_id)
                                        ->where('status','Accepted')
                                        ->pluck('added_client_id')
                                        ->toArray();
                 $recieve_request_accepted = SocialFriend::where('added_client_id',Auth::user()->account_id)
                                    ->where('status','Accepted')
                                    ->pluck('client_id')
                                    ->toArray();
                 $friend_id = array_merge($send_request_accepred,$recieve_request_accepted);
               
              }
            
            if($friend_id != null){
                 $friends = Clients::whereIn('id', $friend_id)
                         ->select('id', 'firstname','lastname')
                         ->get();
            }         
            $user = Auth::user();
            // dd( $habit, $postData['task_recurrence_week'] );
            if(isset($postData['task_recurrence_week'])){
                if(($postData['task_recurrence_week']) > 1){
                 $last_key = array_key_last($postData['task_recurrence_week']);
                    foreach ($postData['task_recurrence_week'] as $key => $value) {
             
                        if ($value === reset($postData['task_recurrence_week'])) {
                               $habitText .= $value ;
                            // $habit .= $value ;
                        }else if ($key == $last_key) {
                            $habitText .= ' and '. $value ;
                            // $habit .= ' and '. $value ;
                        } else {
                            $habitText .= ', '. $value ;
                            // $habit .= ', '. $value ;   
                        }
                    }
                } else {
                     $habitText = $postData['task_recurrence_week'][0];
                 }
              }

      
    
    // dd( $habitText, $postData['task_recurrence_week'] );
 
            $habit_text = '';

            if(isset($postData['SYG_task_recurrence'])){
                if($postData['SYG_task_recurrence'] == 'daily'){
                    $habit_text = ' with a frequency of every day.' ;
                }
                if($postData['SYG_task_recurrence'] == 'weekly'){
                    $habit_text = ' with a frequency of '. $habitText . ' every week.';
                    // $habit_text = ' with a frequency of '. $habit . ' every week.';
                }
                if($postData['SYG_task_recurrence'] == 'monthly'){
                    $habit_text = ' day '. $postData['gb_task_recurrence_month'] . ' of every month.';
                }
             }
           
            // $text = $user['name'].' '. $user['last_name']. " created a goal ". $postData['task_name'] ;
             if ($friends){
               foreach($friends as $friend){
                     $name = $friend['firstname'].' '. $friend['lastname'];
                     if( $edit_task_form == 'yes'){
                        $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a task ". $postData['SYG3_task'] .  $habit_text ;
                        // $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " updated a task  ". $postData['task_name'] .  $habit_text ;
                     } else{
                       $text = 'Hi '.ucfirst($name).', '.$user['name'].' '. $user['last_name']. " created a task ". $postData['SYG3_task'] .  $habit_text ;
                     }
         
                     $message = new SocialUserDirectMessage();
                     $message->sender_user_id = $user->account_id;
                     $message->receiver_user_id = $friend->id;
                     $message->message = $text;
                     $message->save();

                        
                        $post = new SocialPost();
                        $post->content = $text;
                        $post->goal_client_id = Auth::user()->account_id;
                        $post->goal_friend_id = $friend->id;
                        $post->save();
                }
             }
           } 
        
           
    }

    public function saveForm5($clientId,$goal_type){
        //$goal_type = session('customGoalSession1')['chooseGoal'];
        $data = [];
        if($goal_type == 'create_new_goal'){
            for ($i=20; $i <=20 ; $i++) { 
                $data = array_merge($data,session('customGoalSession'.$i));
            }
        }else if($goal_type == 'choose_form_template'){
            for ($i=21; $i <=21 ; $i++) { 
                $data = array_merge($data,session('templateGoalSession'.$i));
            }
        }

        $postData = $data;
        
        $postData["last_insert_id"] = session('last_goal_id');
        $postData["clientId"] == $clientId;
        $data['gb_goal_notes'] = $postData['goal_notes'];

        $data['gb_goal_review'] = implode(',', $postData["review"]);
        $data['final_submitted'] = 1;

        unset($data["gb_milestones_reminder"]);
        unset($data["gb_milestones_reminder_epichq"]);
        unset($data["goal_notes"]);
        unset($data["template"]);
        unset($data["review"]);

        $goalDetails  = GoalBuddy::findOrFail($postData["last_insert_id"]);
        $goalBuddy    = $goalDetails->update($data);
        //$goalBuddy              = GoalBuddy::updateBuddy($data, $postData["last_insert_id"]);
        $lastInsertId           = $postData["last_insert_id"];
        

    }
    //habit steps - 17, 18, 19 for template.
    //task steps for template - 20, 21, 22, 23, 24, 25, 26, 27, 28, 29.
    public function manageSteps($data){
     
        $step = 0;

        if($data->move == 'next'){

            if($data->goal_type == 'create_new_goal'){
                for ($i=1; $i <20 ; $i++) { 
                    if($data->current_step == $i){
                        
                        if((isset($data->repeat_habit) && $data->repeat_habit == 'true') || 
                            (isset($data->repeat_task) && $data->repeat_task == 'true')){
                            $step = $i;
                        }
                        else{
                            $step = $i+1;
                        }

                        $goalDataNew = $this->getGoalDataNew($data);
                  
                        if(isset($data->current_habit_step)){
                            $goalDataNew['current_habit_step'] = $data->current_habit_step;
                        }
                        else if(isset($data->current_task_step)){
                            $goalDataNew['current_task_step'] = $data->current_task_step;
                        }

                        if($data->current_step == 20){
                            $step = 20;
                        }

                        $html = View::make('Result.partials.goal-steps.custom.step'.$step, compact('goalDataNew'));

                        $response['html'] = $html->render();
                        return $response;
                    }
                }
            }

            if($data->goal_type == 'choose_form_template'){
                for ($i=1; $i <=21 ; $i++) { 
                    if($data->current_step == $i){
                        
                        if((isset($data->repeat_habit) && $data->repeat_habit == 'true') || 
                            (isset($data->repeat_task) && $data->repeat_task == 'true')){
                            $step = $i;
                        }
                        else{
                            $step = $i+1;
                        }
                        
                        $goalDataNew = $this->getGoalDataNew($data);
                        
                        if(isset($data->current_habit_step)){
                            $goalDataNew['current_habit_step'] = $data->current_habit_step;
                        }
                        else if(isset($data->current_task_step)){
                            $goalDataNew['current_task_step'] = $data->current_task_step;
                        }
                       
                        if($data->current_step == 21){
                            $step = 21;
                        }
                        
                        $html = View::make('Result.partials.goal-steps.template.step'.$step, compact('goalDataNew'));

                        $response['html'] = $html->render();
                        return $response;
                    }
                }
            }   
        }

        if($data->move == 'back'){

            if($data->goal_type == 'create_new_goal'){
                for ($i=20; $i >1 ; $i--) { 
                    if($data->current_step == $i){
                        
                        if((isset($data->repeat_habit) && $data->repeat_habit == 'true') || 
                            (isset($data->repeat_task) && $data->repeat_task == 'true')){
                            $step = $i;
                        }
                        else{
                            $step = $i-1;
                        }

                        $goalDataNew = $this->getGoalDataNew($data);

                        if(isset($data->current_habit_step)){
                            $goalDataNew['current_habit_step'] = $data->current_habit_step;
                        }
                        else if(isset($data->current_task_step)){
                            $goalDataNew['current_task_step'] = $data->current_task_step;
                        }
                        $html = View::make('Result.partials.goal-steps.custom.step'.$step, compact('goalDataNew'));

                        $response['html'] = $html->render();
                        return $response;
                    }
                }
            }

            if($data->goal_type == 'choose_form_template'){
                for ($i=21; $i >1 ; $i--) { 
                    if($data->current_step == $i){

                        if((isset($data->repeat_habit) && $data->repeat_habit == 'true') || 
                            (isset($data->repeat_task) && $data->repeat_task == 'true')){
                            $step = $i;
                        }
                        else{
                            $step = $i-1;
                        }
                        $goalDataNew = $this->getGoalDataNew($data);
                        
                        if(isset($data->current_habit_step)){
                            $goalDataNew['current_habit_step'] = $data->current_habit_step;
                        }
                        else if(isset($data->current_task_step)){
                            $goalDataNew['current_task_step'] = $data->current_task_step;
                        }
                        $html = View::make('Result.partials.goal-steps.template.step'.$step, compact('goalDataNew'));

                        $response['html'] = $html->render();
                        return $response;
                    }
                }
            }
        }
    }

    public function getGoalDataNew($data){
        $mdata = [];

        $getDataFlag = false;

        if($data->goal_type == 'choose_form_template'){
            if($data->current_step >= '16' || $data->current_step <= '20' && session('last_goal_id'))
            {
                $getDataFlag = true;
            }
        }else{
            if($data->current_step >= '15' || $data->current_step <= '19' && session('last_goal_id')){
                $getDataFlag = true;  
            }
        }

        if($getDataFlag){
            $goalDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->findOrFail(session('last_goal_id')); 
            $milestoneOption = array();
            $milestonesData  = $goalDetails->goalBuddyMilestones->sortBy('id');
            if (count($milestonesData)) {
                foreach ($milestonesData as $milestones) {
                    $milestoneOption[$milestones->id] = $milestones->gb_milestones_name;
                }
            }
            //dd("herer");
            $mdata['milestoneOption'] = $milestoneOption;
            $mdata['milestonesData'] = $milestonesData;
            $mdata['habitDetails'] = $goalDetails->goalBuddyHabit->sortBy('id'); 
            $mdata['taskDetails'] = $goalDetails->goalBuddyTask->sortBy('gb_habit_id'); 
            return $mdata;
        }
    }

public function getHabitDataGoal(){
   // dd(session('last_goal_id'));
    if(session('last_goal_id')){
        $goalDetails    = GoalBuddy::with('goalBuddyHabit')->findOrFail(session('last_goal_id'));
        $habit_due_date = $goalDetails->gb_due_date;

        if ($lastHabitId) {
            GoalBuddyUpdate::where('habit_id', $lastHabitId)->where('task_id', '=', 0)->delete();
            $this->updateHabitActivity(['habit_id' => $lastHabitId, 'due_date' => $habit_due_date]);
        }

        $goalBuddyHabit = $goalDetails->goalBuddyHabit;
        $habitArray     = array();
        foreach ($goalBuddyHabit as  $habitVal) {
            $habitArray[] = array('id'=>$habitVal->id,'is_primary'=>$habitVal->is_primary,'gb_habit_recurrence'=>$habitVal->gb_habit_recurrence, 'gb_habit_recurrence_week'=>$habitVal->gb_habit_recurrence_week, 'gb_habit_recurrence_month'=>$habitVal->gb_habit_recurrence_month, 'gb_habit_name'=>$habitVal->gb_habit_name, 'gb_habit_seen'=>$habitVal->gb_habit_seen, 'gb_habit_recurrence_type'=>$habitVal->gb_habit_recurrence_type, 'gb_habit_notes' => $habitVal->gb_habit_notes, 'mile_stone_name' =>implode(', ', $habitVal->getMilestoneNames()));
        }

        $response = [
            'listData' => $habitArray
        ];
        return response()->json($response);
    }
}

public function loadCustomHabitStep(Request $request){
    $goalDataNew = [];
    $goalDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->findOrFail(session('last_goal_id')); 
    $milestoneOption = array();
    $milestonesData  = $goalDetails->goalBuddyMilestones->sortBy('id');
    if (count($milestonesData)) {
        foreach ($milestonesData as $milestones) {
            $milestoneOption[$milestones->id] = $milestones->gb_milestones_name;
        }
    }
    //dd("herer");
    $goalDataNew['milestoneOption'] = $milestoneOption;
    $goalDataNew['milestonesData'] = $milestonesData; 

    //NEW
    $goalDataNew['customHabitNew'] = true; 
    $html = View::make('Result.partials.goal-steps.custom.step16', compact('goalDataNew'));

    if(isset($request->goal_type) && $request->goal_type == "choose_form_template"){
        $html = View::make('Result.partials.goal-steps.template.step17', compact('goalDataNew'));
    }
    
    $response['html'] = $html->render();
    return Response::json($response);
}

public function loadCustomHaitList(){
    $goalBuddyTaskData = GoalBuddyTask::findOrFail($request->taskId);

    // $allHabitArray = GoalBuddyHabit::where('goal_id',$goalBuddyTaskData->goal_id)->select('gb_habit_name','id')->get();
    $allHabitArray = GoalBuddyHabit::where('goal_id', $goalBuddyTaskData->goal_id)->get();
    $message       = array("status" => "true", "goalBuddy" => $goalBuddyTaskData, "habitTask" => $allHabitArray);
    return json_encode($message);
}

public function loadCustomTaskStep(Request $request){

    $goalTaskNew = [];
     //NEW
    $goalTaskNew['customTaskNew'] = true; 
    $html = View::make('Result.partials.goal-steps.custom.step18', compact('goalTaskNew'));

    if(isset($request->goal_type) && $request->goal_type == "choose_form_template"){
        $goalTaskNew['customTaskNew'] = true; 
        $html = View::make('Result.partials.goal-steps.template.step19', compact('goalTaskNew'));
    }

    //$html = View::make('Result.partials.goal-steps.custom.step18');
    $response['html'] = $html->render();
    return Response::json($response);
}

public function loadCustomTaskList(){
    $goalBuddyData = GoalBuddy::findOrFail(session('last_goal_id'));

    $goalBuddyTask = $goalBuddyData->goalBuddyTask;
    $listData = array();
    foreach ($goalBuddyTask as $task_value) {
        $listData[] = array('id' => $task_value->id,'is_primary' => $task_value->is_primary, 'gb_task_name' => $task_value->gb_task_name, 'gb_task_priority' => $task_value->gb_task_priority, 'gb_task_seen' => $task_value->gb_task_seen, 'task_habit_name' => $task_value->taskhabit->gb_habit_name);
    }
    if(isset($postData['save_as_draft']) && $postData['save_as_draft'] == true){
        $saveAsDraft = $postData['save_as_draft'];
    }else{
        $saveAsDraft = $postData['save_as_draft'];
    }
    $message = array("status" => "success", 'task_list' => $listData);
    return json_encode($message);
}

public function loadCustomMilestoneList(){
    $goalDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->findOrFail(session('last_goal_id')); 
            $milestoneOption = array();
            $milestonesData  = $goalDetails->goalBuddyMilestones->sortBy('id');
            if (count($milestonesData)) {
                foreach ($milestonesData as $milestones) {
                    $milestoneOption[$milestones->id] = $milestones->gb_milestones_name;
                }
            }
            //dd("herer");
            $mdata['milestoneOption'] = $milestoneOption;
            $mdata['milestonesData'] = $milestonesData; 
            return json_encode($message);
}
public function loadFinalStep(){
    $goalBuddyData = GoalBuddy::findOrFail(session('last_goal_id'));

    $goalMilestone = $goalBuddyData->goalBuddyMilestones;
    

    $goalBuddyHabit = $goalBuddyData->goalBuddyHabit;
    $habitArray     = array();
    foreach ($goalBuddyHabit as $habitVal) {
        $habitArray[] = array('id' => $habitVal->id, 'gb_habit_recurrence' => $habitVal->gb_habit_recurrence, 'gb_habit_recurrence_week' => $habitVal->gb_habit_recurrence_week, 'gb_habit_recurrence_month' => $habitVal->gb_habit_recurrence_month, 'gb_habit_name' => $habitVal->gb_habit_name, 'gb_habit_seen' => $habitVal->gb_habit_seen, 'gb_habit_recurrence_type' => $habitVal->gb_habit_recurrence_type, 'mile_stone_name' => implode(', ', $habitVal->getMilestoneNames()));
    }

    $goalBuddyTask = $goalBuddyData->goalBuddyTask;
    $listData = array();
    foreach ($goalBuddyTask as $task_value) {
        $listData[] = array('id' => $task_value->id, 'gb_task_name' => $task_value->gb_task_name, 'gb_task_priority' => $task_value->gb_task_priority, 'gb_task_seen' => $task_value->gb_task_seen, 'task_habit_name' => $task_value->taskhabit->gb_habit_name);
    }
    if(isset($postData['save_as_draft']) && $postData['save_as_draft'] == true){
        $saveAsDraft = $postData['save_as_draft'];
    }else{
        $saveAsDraft = $postData['save_as_draft'];
    }
    $message = array("status" => "success", "goalBuddy" => $lastInsertId, "goalInfo" => $goalBuddyData, 'milestone_list' => $goalMilestone, 'habit_list' => $habitArray, 'task_list' => $listData,'saveAsDraft' => $saveAsDraft,'form'=>$data['form']);

   // $message = array("status" => "success", 'goalInfo' => $goalBuddyData);
    return json_encode($message);

}

public function getDataFromSession(Request $request){

    $goal_type = session('customGoalSession1')['chooseGoal'];
    $current_step = $request->current_step;
    $data = session()->all();
    if($goal_type){
        if($goal_type == 'create_new_goal'){
            $response = [
                'data' => session('customGoalSession'.$current_step)
            ];
        }
    }else{
        $goal_type = session('templateGoalSession1')['chooseGoal'];
        if($goal_type == "choose_form_template"){
            $response = [
                'data' => session('templateGoalSession'.$current_step)
            ];
        }
    }
    
    return response()->json($response);
}

// new goal-process functions - end

}
