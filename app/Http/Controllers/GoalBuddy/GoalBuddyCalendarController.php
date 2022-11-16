<?php
namespace App\Http\Controllers\GoalBuddy;

use App\CalendarSetting;
use App\GoalBuddy;
use App\GoalBuddyUpdate;
use App\Http\Controllers\Controller;
use App\Http\Traits\CalendarSettingTrait;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\GoalBuddyTrait;
use App\Http\Traits\LocationAreaTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\StaffTrait;
use Auth;
use Illuminate\Http\Request;
use Session;

class GoalBuddyCalendarController extends Controller
{
    use GoalBuddyTrait, LocationAreaTrait, StaffTrait, StaffEventsTrait, ClientTrait, CalendarSettingTrait;

    /**
     * Render calendar event
     * @param startDate, endDate, eventType, clientId
     * @return events
     */
    public function show(Request $request)
    {
        $response       = [];
        $eventStartDate = $request->startDate;
        $eventEndDate   = $request->endDate;
        $eventCalType   = $request->eventType;

        if (Session::get('hostname') == 'crm') {
            $clientId = $request->clientId;
        } else {
            $clientId = Auth::user()->account_id;
        }

        $query = GoalBuddyUpdate::with('goal', 'milestone', 'habits', 'task')
            ->where('gb_client_id', $clientId)
            ->where('due_date', '>=', $eventStartDate)
            ->where('due_date', '<=', $eventEndDate)
            ->whereNull('deleted_at')
            ->where('goal_id', '!=', 0);

        if ($request->has('goalId') && $request['goalId']) {
            $query->where('goal_id', $request['goalId']);
        }

        $query->orderBy('due_date', 'asc')
            ->orderBy('milestone_id', 'desc')
            ->orderBy('goal_id', 'asc')

            ->orderBy('habit_id', 'asc')
            ->orderBy('task_id', 'asc')
            ->orderBy('due_date', 'asc');

        $activityArr = $query->get();

        if ($activityArr && $activityArr->count()) {
            foreach ($activityArr as $value) {
                $parent_id         = 0;
                $isError           = true;
                $subEventTotal     = 0;
                $subEventCompleted = 0;

                if ($value->milestone_id == 0 && $value->habit_id == 0 && $value->task_id == 0) {
                    $goalDb = $value->goal()->first();

                    if (count($goalDb)) {
                        # total milestones of goal
                        $totalMilestone = GoalBuddyUpdate::where('gb_client_id', $clientId)
                            ->where('goal_id', $goalDb->id)
                            ->where('milestone_id', '!=', 0)
                            ->count();

                        # milestones completed
                        $completedMilestone = GoalBuddyUpdate::where('gb_client_id', $clientId)
                            ->where('goal_id', $goalDb->id)
                            ->where('milestone_id', '!=', 0)
                            ->where('status', 1)
                            ->count();

                        $subEventTotal     = $totalMilestone;
                        $subEventCompleted = $completedMilestone;
                        $eventType         = 'goal';
                        $parentId          = 0;
                        $eventtitel        = $goalDb->gb_goal_name;
                        $isError           = false;
                    }
                }
                if ($value->milestone_id != 0 && $value->habit_id == 0 && $value->task_id == 0) {
                    if (count($value->milestone)) {
                        $eventType  = 'milestone';
                        $parent_id  = $value->goal_id;
                        $eventtitel = $value->milestone->gb_milestones_name;
                        $isError    = false;
                    }
                }
                if ($value->milestone_id == 0 && $value->habit_id != 0 && $value->task_id == 0) {
                    $habitDb = $value->habits()->first();

                    if (count($habitDb)) {
                        # Total tasks of habits
                        $totalTask = GoalBuddyUpdate::where('gb_client_id', $clientId)
                            ->where('goal_id', $value->goal_id)
                            ->where('habit_id', $habitDb->id)
                            ->where('task_id', '!=', 0)
                            ->where('due_date', $value->due_date)
                            ->count();

                        # tasks completed
                        $completedTask = GoalBuddyUpdate::where('gb_client_id', $clientId)
                            ->where('goal_id', $value->goal_id)
                            ->where('habit_id', $habitDb->id)
                            ->where('task_id', '!=', 0)
                            ->where('due_date', $value->due_date)
                            ->where('status', '1')
                            ->count();

                        $subEventTotal     = $totalTask;
                        $subEventCompleted = $completedTask;
                        $eventType         = 'habit';
                        $parent_id         = $value->goal_id;
                        $eventtitel        = $habitDb->gb_habit_name;
                        $isError           = false;
                    }
                }

                // if($value->milestone_id == 0 && $value->habit_id == 0 && $value->task_id != 0 ){
                if ($value->milestone_id == 0 && $value->habit_id != 0 && $value->task_id != 0) {
                    $taskDb = $value->task()->first();

                    $taskParentId = GoalBuddyUpdate::where([
                        'goal_id'  => $value->goal_id,
                        'habit_id' => $value->habit_id,
                        'due_date' => $value->due_date,
                        'task_id'  => 0,
                    ])->pluck('id')->first();

                    if (count($taskDb)) {
                        $eventType = 'task';
                        // $parent_id = $value->goal_id;
                        // $parent_id  = $value->habit_id;
                        $parent_id  = $taskParentId;
                        $eventtitel = $taskDb->gb_task_name;
                        $isError    = false;
                    }
                }

                if($value->due_date <= $value->goal->gb_due_date && $value->due_date >= date('Y-m-d', strtotime($value->goal->created_at))){

                if (!$isError && ($eventCalType == $eventType || $eventCalType == '' || $eventCalType == 'all')) {
                    $response[] = array(
                        'goalId'            => $value->goal_id,
                        'milestoneId'       => $value->milestone_id,
                        'habitId'           => $value->habit_id,
                        'taskId'            => $value->task_id,
                        'eventTitel'        => $eventtitel,
                        'subEventCompleted' => $subEventCompleted,
                        'subEventTotal'     => $subEventTotal,
                        'eventType'         => $eventType,
                        'eventDueDate'      => $value->due_date,
                        'eventId'           => $value->id,
                        'eventStatus'       => $value->status,
                        'parentId'          => $parent_id);
                }
                }
            }
        }

        return json_encode($response);
    }

    public function showwithaction(Request $request)
    {

        $eventaction = $request['actionType'];
        $activityArr = GoalBuddyUpdate::where('goal_id', '!=', '')->get();
        return json_encode($activityArr);
    }

    public function statusChange(Request $request)
    {
        // dd($request->all());
        $message         = array('status' => 'false', 'data' => '', 'updateHabitId' => '');
        $eventId         = '';
        $data['status']  = $request['actionStatus'];
        $goalBuddyUpdate = GoalBuddyUpdate::with('task', 'milestone')->find($request['eventId']);

        if ($goalBuddyUpdate) {
            if ($goalBuddyUpdate->update($data)) {
                $eventId = $goalBuddyUpdate->id;
            }

            if ($request->eventType == 'milestone' && $goalBuddyUpdate->milestone) {
                $goalBuddyUpdate->milestone->update(['gb_milestones_status' => $data['status']]);
                $eventId = $goalBuddyUpdate->id;
            }

            $message = array('status' => 'true', 'updateEventId' => $eventId, 'eventType' => $request['eventType']);
        }
        return json_encode($message);
    }

    public function show_calendar(Request $request)
    {

        $data           = $this->locAreasForEvents();
        $locsAreas      = $data['locsAreas'];
        $ifClassesExit  = $data['ifClassesExit'];
        $ifServicesExit = $data['ifServicesExit'];
        //$defaultStaffDetails = $data['defaultStaffDetails'];

        $staffs = ['all-ros' => 'All rostered staff', 'all' => 'All staff'] + $this->staffs('all');

        $staffHoursRequest          = new \Illuminate\Http\Request();
        $staffHoursRequest->staffId = 'all';
        $staffHoursRequest->areaId  = 'all';
        $staffHours                 = $this->getHoursFromTrait($staffHoursRequest);

        $modalLocsAreas = $locsAreas;
        if (count($locsAreas)) {
            $locsAreas = ['all' => 'All Locations'] + $locsAreas;
        }

        $eventRepeatIntervalOpt = $this->prepareEventRecurDdOpt();

        //dd($request);
        if ($request->has('subview')) {
            $subview = true;
            if (Auth::user()->account_id) {
                $client = Clients::findClient(Auth::user()->account_id);

                if ($client) {
                    $cl['id']    = Auth::user()->account_id;
                    $cl['name']  = $client->firstname . ' ' . $client->lastname;
                    $cl['email'] = $client->email;
                    $cl['phone'] = $client->phonenumber;
                    if ($request->has('consultationRestriction')) {
                        $enableDateFrom = $client->consultation_date;
                    }

                    $cl = json_encode($cl);
                }
            }
        }
        if (!isset($cl)) {
            $clientDetailsRequest           = new \Illuminate\Http\Request();
            $clientDetailsRequest->calendar = true;
            $clients                        = $this->allClientsFromTrait($clientDetailsRequest);
        }

        if ($request->has('enableDatePeriod')) {
            $enableDatePeriod = $request->enableDatePeriod;
        }

        $calendarSettingVal = CalendarSetting::where('cs_business_id', Session::get('businessId'))->first()->toArray();
        $reasons            = $this->getCancelReasons($calendarSettingVal['id']);

        return view('goal-buddy.goalBuddyEventCalendar', compact('modalLocsAreas', 'locsAreas', 'ifClassesExit', 'ifServicesExit' /*, 'defaultStaffDetails'*/, 'eventRepeatIntervalOpt', 'subview', 'cl', 'enableDateFrom', 'enableDatePeriod', 'staffs', 'staffHours', 'clients', 'reasons', 'calendarSettingVal'));

    }

}
