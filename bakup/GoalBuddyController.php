<?php
namespace App\Http\Controllers\GoalBuddy;

use App\ClientMenu;
use App\GoalBuddy;
use App\GoalBuddyHabit;
use App\GoalBuddyMilestones;
use App\GoalBuddyTask;
use App\GoalBuddyUpdate;
use App\Http\Controllers\Controller;
use App\Http\Traits\GoalBuddyTrait;
use App\Http\Traits\HelperTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;
use Session;

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
        $habitsArray = $milestonesArray = array();
        $goals       = GoalBuddy::with('goalBuddyHabit', 'goalBuddyMilestones')->where('gb_client_id', Auth::user()->account_id)->get();
        if ($goals->count() > 0) {
            $completed  = $success  = $missed  = array();
            $monthArray = $datesnotfound = $daysBetweenDates = array();

            foreach ($goals as $goalInfo) {
                $habits = $goalInfo->goalBuddyHabit;
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

            return view('Result.goal-buddy.goallisting', compact('goals', 'missed', 'success', 'completed')); //,'goalDetails'
        } else {
            return view('Result.goal-buddy.create');
        }
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

    public function create()
    {
        return view('Result.goal-buddy.create');
    }

    /**
     * Store a newly created goal into table.
     * @param  GoalBuddyRequest $request
     * @return Response
     */
    public function store(Request $request)
    {

        $postData = $request->all()['formData'];
            
        $todayDate       = date("Y-m-d");
        $currentDay      = date("l");
        $currentDateOnly = date("j");
        $lastInsertId    = '';
        $goalBuddyHabit  = $data  = array();

        if (Session::get('hostname') == 'crm') {
            $clientId = $postData["ClientId"];
        } else {
            $clientId = Auth::user()->account_id;
        }
       // For Notes
        if (isset($postData["goal_notes"])) {
            $data['gb_goal_notes'] = $postData["goal_notes"];
            if (isset($postData["last_insert_id"]) &&  $data['gb_goal_notes'] != "") {           
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

            if ($postData["describe_achieve"]) {
                $data['gb_achieve_description'] = $postData["describe_achieve"];
            }

            if ($postData["change_life"]) {
                $data['gb_change_life_reason'] = implode(',', $postData["change_life"]);
            }

            if (isset($postData["gb_change_life_reason_other"])) {
                $data['gb_change_life_reason_other'] = $postData["gb_change_life_reason_other"];
            }            

            if ($postData["accomplish"]) {
                $data["gb_important_accomplish"] = $postData["accomplish"];
            }

            if ($postData["failDescription"]) {
                $data["gb_fail_description"] = $postData["failDescription"];
            }

            if ($postData["gb_relevant_goal"]) {
                $data["gb_relevant_goal"] = $postData["gb_relevant_goal"];
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

            if (isset($postData['image'])) {
                $data['gb_image_url'] = $postData["image"];
            }

            if (isset($postData['goal_seen'])) {
                $data['gb_goal_seen'] = $postData["goal_seen"];
            }

            if (isset($postData['goal_year'])) {
                $data['gb_is_top_goal'] = $postData["goal_year"];
            }

            if (isset($postData['send_msg_type'])) {
                $data['gb_reminder_type'] = $postData["send_msg_type"];
            }

            if ($postData["update_status"] == 'update-yes') {
                $goalDetails  = GoalBuddy::findOrFail($postData["last_insert_id"]);
                $goalBuddy    = $goalDetails->update($data);
                $lastInsertId = $postData["last_insert_id"];
                GoalBuddyUpdate::where('goal_id', $lastInsertId)->where('milestone_id', '=', 0)->where('habit_id', '=', 0)->where('task_id', '=', 0)->delete();
            } else {
                $goalBuddy    = GoalBuddy::create($data);
                $lastInsertId = $goalBuddy->id;
            }

            # Get goal template details
            if (array_key_exists('gb_template', $data) && $data['gb_template']) {
                //dd($request->all());
                $goalTemplateDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->find($data['gb_template']);
                    //dd($goalTemplateDetails);
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
                                'gb_habit_notes' => $habit->gb_habit_notes,
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

            $inserData['goal_id']      = $lastInsertId;
            $inserData['gb_client_id'] = $clientId;
            $inserData['due_date']     = $postData["due_date"];

            $goalupdate = GoalBuddyUpdate::create($inserData);
        } else if ($postData["form_no"] == 2) {
            //dd($request->all());
            $milestonesInsertData = array();
            $timestamp            = createTimestamp();

            if (isset($postData["last_insert_id"]) && $postData["last_insert_id"] != '') {
                $lastInsertGoalId = $postData["last_insert_id"];
            }

            if (isset($postData["goal_id_mile"]) && $postData["goal_id_mile"] != '') {
                $lastInsertGoalId = $postData["goal_id_mile"];
            }

            $lastInsertId    = $lastInsertGoalId;
            $milestonArray   = array();
            $milestoneUpdate = GoalBuddyMilestones::where('goal_id', $lastInsertId)->get();
            foreach ($milestoneUpdate as $mileston) {
                $mileston->update(['gb_milestones_seen' => $postData['gb_milestones_seen'], 'gb_milestones_reminder' => $postData["gb_milestones_reminder"], 'gb_client_id' => $clientId]);

                $milestonArray[] = array('id' => $mileston->id, 'gb_milestones_name' => $mileston->gb_milestones_name);
            }

            $mileStoneIdStr = $this->insertMilestoneUpdates($lastInsertId, $clientId);
            $milestonesData = array('form' => 'milestones-list', 'milestonesId' => $mileStoneIdStr, 'mdata' => $milestonArray);

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

            if (isset($postData["habit_notes"])) {
                $data['gb_habit_notes'] = $postData["habit_notes"];
            }

            if (isset($postData["habit_seen"])) {
                $data['gb_habit_seen'] = $postData["habit_seen"];
            }

            if (isset($postData["habit_reminders"])) {
                $data['gb_habit_reminder'] = $postData["habit_reminders"];
            }
            if (isset($postData["habit_id"]) && $postData["habit_id"]) {
                $habits       = GoalBuddyHabit::find($postData["habit_id"]);
                $goalBuddy    = $habits->update($data);
                $lastHabitId  = $postData["habit_id"];
                $lastInsertId = $habits->goal_id;
            } else {
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
            foreach ($goalBuddyHabit as $habitVal) {
                $habitArray[] = array('id' => $habitVal->id, 'gb_habit_recurrence' => $habitVal->gb_habit_recurrence, 'gb_habit_recurrence_week' => $habitVal->gb_habit_recurrence_week, 'gb_habit_recurrence_month' => $habitVal->gb_habit_recurrence_month, 'gb_habit_name' => $habitVal->gb_habit_name, 'gb_habit_seen' => $habitVal->gb_habit_seen, 'gb_habit_recurrence_type' => $habitVal->gb_habit_recurrence_type, 'mile_stone_name' => implode(', ', $habitVal->getMilestoneNames()));
            }

            $habitData['habitId']  = $lastHabitId;
            $habitData['form']     = 'habit-list';
            $habitData['habit_list'] = $habitArray;
            return json_encode($habitData);
        } else if ($postData["form_no"] == 4) {
            // dd($request->all());
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

            if (isset($postData["task_reminders"])) {
                $data['gb_task_reminder'] = $postData["task_reminders"];
            }

            if (isset($postData["task_recurrence"])) {
                $data['gb_task_recurrence_type'] = $postData["task_recurrence"];
                if ($postData["task_recurrence"] == "weekly" && isset($postData['task_weeks'])) {
                    $weekData                        = implode(',', $postData['task_weeks']);
                    $data['gb_task_recurrence_week'] = $weekData;
                } else if ($postData["task_recurrence"] == "monthly" && isset($postData['month'])) {
                    $data['gb_task_recurrence_month'] = $postData['month'];
                }
            }

            if (isset($postData["task_id"]) && $postData["task_id"]) {
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
            //dd($goalBuddyTask);
            foreach ($goalBuddyTask as $task_value) {
                $listData[] = array('id' => $task_value->id, 'gb_task_name' => $task_value->gb_task_name, 'gb_task_priority' => $task_value->gb_task_priority, 'gb_task_seen' => $task_value->gb_task_seen, 'task_habit_name' =>isset($task_value->taskhabit->gb_habit_name)?$task_value->taskhabit->gb_habit_name:'');
            }

            $goalBuddyData = GoalBuddy::findOrFail($lastInsertId);            
            $taskData = array("goalInfo" => $goalBuddyData,'form' => 'task-list', 'task_list' => $listData, 'taskId' => $lastTaskId);
            return json_encode($taskData);
        } else if ($postData["form_no"] == 5) {
            $data['gb_goal_review'] = implode(',', $postData["review"]);
            $goalBuddy              = GoalBuddy::updateBuddy($data, $postData["last_insert_id"]);
            $lastInsertId           = $postData["last_insert_id"];
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

            $message = array("status" => "success", "goalBuddy" => $lastInsertId, "goalInfo" => $goalBuddyData, 'milestone_list' => $goalMilestone, 'habit_list' => $habitArray, 'task_list' => $listData);
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
        if (count($milestonesData)) {
            foreach ($milestonesData as $milestones) {
                $milestoneOption[$milestones->id] = $milestones->gb_milestones_name;
            }
        }

        $habitData   = $goalDetails->goalBuddyHabit;
        $taskData    = $goalDetails->goalBuddyTask;
        $clientId    = $goalDetails->gb_client_id;
        $review_data = array();
        // dd($taskData->toArray());
        $milestonesId             = [];
        $milestonesList           = [];
        $milestonesListWithIdName = [];

        $review_data = array(
            "created_at"              => $goalDetails["created_at"],
            "deleted_at"              => $goalDetails["deleted_at"],
            "gb_achieve_description"  => $goalDetails["gb_achieve_description"],
            "gb_change_life_reason"   => $goalDetails["gb_change_life_reason"],
            "gb_company_name"         => $goalDetails["gb_company_name"],
            "gb_due_date"             => $goalDetails["gb_due_date"],
            "gb_fail_description"     => $goalDetails["gb_fail_description"],
            "gb_goal_name"            => $goalDetails["gb_goal_name"],
            "gb_goal_review"          => $goalDetails["gb_goal_review"],
            "gb_goal_seen"            => $goalDetails["gb_goal_seen"],
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
        );

        if ($milestonesData) {
            foreach ($milestonesData as $milVal) {
                $milestonesId[]                        = $milVal->id;
                $milestonesList[]                      = ["milestones_id" => $milVal->id, "milestones_name" => $milVal->gb_milestones_name, "gb_milestones_seen" => $milVal->gb_milestones_seen];
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
                $hab_data[$key]["gb_habit_reminder"]         = $value['gb_habit_reminder'];
                $hab_data[$key]["gb_milestones_id"]          = $value['gb_milestones_id'];
                $hab_data[$key]["gb_milestones_name"]        = implode(",", $mile_names);
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
                    "gb_task_reminder"         => $value['gb_task_reminder'],
                    "gb_habit_id"              => $value['gb_habit_id'],
                    "gb_task_recurrence_type"  => $value['gb_task_recurrence_type'],
                    "gb_task_recurrence_week"  => $value['gb_task_recurrence_week'],
                    "gb_task_recurrence_month" => $value['gb_task_recurrence_month'],

                    "created_at"               => (!is_null($value['created_at'])) ? $value['created_at']->toDateString() : null,
                    "updated_at"               => (!is_null($value['updated_at'])) ? $value['updated_at']->toDateString() : null,
                    "deleted_at"               => (!is_null($value['deleted_at'])) ? $value['deleted_at']->toDateString() : null,

                    "gbHabitWeekDetails"       => $gbHabitWeekDetails,
                );
            }
            $review_data['taskdata'] = $task_data;
        }
        $review_data = json_encode($review_data);

        if (Session::get('hostname') == 'crm') {
            return view('goal-buddy.edit', compact('goalid', 'goalDetails', 'milestonesData', 'habitData', 'taskData', 'mileStoneIdStr', 'review_data', 'clientId', 'milestoneOption'));
        } else {
            return view('Result.goal-buddy.edit', compact('goalid', 'goalDetails', 'milestonesData', 'habitData', 'taskData', 'mileStoneIdStr', 'review_data', 'clientId', 'milestoneOption'));
        }

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
        $goalBuddyHabitData = GoalBuddyHabit::findOrFail($request->habitId);
        $message            = array("status" => "true", "goalBuddy" => $goalBuddyHabitData);
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
            return view('Result.goal-buddy.edit', compact('goalid', 'goalDetails', 'milestonesData', 'clientId'));
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

        if (Session::get('hostname') == 'crm') {
            return view('goal-buddy.edit', compact('milestonesData'));
        } else {
            return view('Result.goal-buddy.edit', compact('milestonesData'));
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
            return view('Result.goal-buddy.edit', compact('milestonesData', 'habitDetails', 'clientId'));
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
            return view('Result.goal-buddy.edit', compact('habitData', 'taskDetails', 'gbHabitWeekDetails', 'clientId'));
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
            $goal->delete();
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
    public function getGoalTemplate($templateId)
    {
        $response = ['status' => false, 'goal_template' => []];
        if ($templateId) {
            $goalDetails = GoalBuddy::with('goalBuddyMilestones', 'goalBuddyHabit', 'goalBuddyTask.taskhabit')->find($templateId);
            if ($goalDetails) {
                $response['status']        = true;
                $response['goal_template'] = $goalDetails;
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

    		$habitArray = array('id' => $habitDetails->id, 'gb_habit_recurrence' => $habitDetails->gb_habit_recurrence, 'gb_habit_recurrence_week' => $habitDetails->gb_habit_recurrence_week, 'gb_habit_recurrence_month' => $habitDetails->gb_habit_recurrence_month, 'gb_habit_name' => $habitDetails->gb_habit_name, 'gb_habit_seen' => $habitDetails->gb_habit_seen, 'gb_habit_recurrence_type' => $habitDetails->gb_habit_recurrence_type, 'mile_stone_name' => implode(', ', $habitDetails->getMilestoneNames()));

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
    			'gb_task_recurrence_type' => $taskDetails->gb_task_recurrence_type, 'gb_task_recurrence_week' => $taskDetails->gb_task_recurrence_week, 'gb_task_recurrence_month' => $taskDetails->gb_task_recurrence_month);

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
}
