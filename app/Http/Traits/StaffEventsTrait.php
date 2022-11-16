<?php
/*
REQUIREMENT:
LocationAreaTrait for (eventsListForOverview())
ClosedDateTrait for (calcRepeatsDateAfterCase(), calcRepeatsDateOnOrNeverCase(), neverEndClassRepeats(), neverEndSingleServiceRepeats())
ClientTrait for (neverEndClassRepeats(), satisfyMembershipRestrictions())
StaffEventTrait for (satisfyMembershipRestrictions())
HelperTrait for (neverEndClassRepeats(), neverEndSingleServiceRepeats())
 */

#log-1505: removed membership limit check while generating repeat events

namespace App\Http\Traits;
use App\ClassCat;
use App\Clas;
use App\ClientMember;
use App\ClientMemberLimit;
use App\Clients;
use App\Http\Traits\StaffEventClassTrait;
use App\Invoice;
use App\InvoiceItems;
use App\Ldc;
use App\MemberShip;
use App\Staff;
use App\StaffEvent;
use App\StaffEventBusy;
use App\StaffEventBusyRepeat;
use App\StaffEventClass;
use App\StaffEventClassRepeat;
use App\StaffEventRepeat;
use App\StaffEventResource;
use App\StaffEventSingleService;
use App\StaffEventSingleServiceRepeat;
use App\StaffEventSkip;
use App\Task;
use App\TaskCategory;
use App\TaskReminder;
use App\TaskRepeat;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Session;
// use App\Http\Traits\LocationAreaTrait;
use \stdClass;

trait StaffEventsTrait
{
    // use LocationAreaTrait;
    use StaffEventClassTrait;

    private $weekDaysArr = [0 => 'Mon', 1 => 'Tue', 2 => 'Wed', 3 => 'Thu', 4 => 'Fri', 5 => 'Sat', 6 => 'Sun'];

    protected function staffHasDayActivity($request)
    {
        $eventData            = new stdClass();
        $eventData->areaId    = $request->areaId;
        $eventData->staffId   = $request->staffId;
        $eventData->startDate = $request->startDate;

        if (StaffEventClass::OfAreaAndStaffAndDated($eventData)->get()->count() || /*StaffEvent::OfAreaAndStaffAndDated($eventData)->get()->count()*/StaffEventSingleService::OfAreaAndStaffAndDated($eventData)->get()->count() || StaffEventBusy::OfAreaAndStaffAndDated($eventData)->get()->count() || count(Staff::getStaffHours($request->staffId, $request->day))) {
            return true;
        }

        return false;
    }

    protected function staffHasWeekActivity($request)
    {
        $eventData            = new stdClass();
        $eventData->areaId    = $request->areaId;
        $eventData->staffId   = $request->staffId;
        $eventData->startDate = $request->startDate;
        $eventData->endDate   = $request->endDate;

        if (StaffEventClass::OfAreaAndStaffAndDatedBetween($eventData)->get()->count() || /*StaffEvent::OfAreaAndStaffAndDatedBetween($eventData)->get()->count()*/StaffEventSingleService::OfAreaAndStaffAndDatedBetween($eventData)->get()->count() || StaffEventBusy::OfAreaAndStaffAndDatedBetween($eventData)->get()->count() || count(Staff::getStaffHours($request->staffId))) {
            return true;
        }

        return false;
    }

    protected function storeEventrepeatData($request, $event, $recreate = false)
    {
        if (!$request->eventRepeat || ($request->eventRepeat == 'Weekly' && !$request->has('eventRepeatWeekdays'))) {
            return false;
        }

        $repeat             = new StaffEventRepeat();
        $dates              = [];
        $repeat->ser_repeat = $request->eventRepeat;

        if ($request->eventRepeat == 'Daily' || $request->eventRepeat == 'Weekly' || $request->eventRepeat == 'Monthly') {
            $repeat->ser_repeat_interval = $request->eventRepeatInterval;
            $repeat->ser_repeat_end      = $request->eventRepeatEnd;

            if ($request->has('eventRepeatWeekdays')) {
                $repeat->ser_repeat_week_days = json_encode($request->eventRepeatWeekdays);
            }

            $eventTable     = $event->getTable();
            $incClosedDates = true;
            $skipData       = [];
            if ($eventTable == 'staff_event_classes') {
                $eventDate = $event->sec_date;
                if (!$event->sec_parent_id) {
                    $skipData['skips']     = $event->skips;
                    $skipData['startTime'] = $event->sec_time;
                    $skipData['duration']  = $event->sec_duration;
                }
            } else if ($eventTable == 'staff_events') {
                $eventDate = $event->se_date;
            } else if ($eventTable == 'staff_event_single_services') {
                $eventDate = $event->sess_date;
                if (!$event->sec_parent_id) {
                    $skipData['skips']     = $event->skips;
                    $skipData['startTime'] = $event->sess_time;
                    $skipData['duration']  = $event->sess_duration;
                }
            } else if ($eventTable == "tasks") {
                $eventDate      = $event->task_due_date;
                $incClosedDates = false;
            }

            if ($request->eventRepeatEnd == 'After') {
                $repeat->ser_repeat_end_after_occur = $request->eventRepeatEndAfterOccur;

                /*$eventDate = new Carbon($eventDate);
                for($i=0; $i<$request->eventRepeatEndAfterOccur;){
                $param = ['eventDate' => $eventDate, 'eventRepeat' => $request->eventRepeat, 'repeatIntv' => $request->eventRepeatInterval];

                if($request->eventRepeat == 'Weekly')
                $param['eventRepeatWeekdays'] = $request->eventRepeatWeekdays;

                $repeatDate = $this->calcRepeatsDate($param);
                if($repeatDate){
                $dates[] = $repeatDate;
                $i++;
                }
                }

                $this->getRepeatsDate($dates);*/
                $dates = $this->calcRepeatsDateAfterCase(['eventDate' => $eventDate, 'eventRepeatEndAfterOccur' => $request->eventRepeatEndAfterOccur, 'eventRepeat' => $request->eventRepeat, 'eventRepeatInterval' => $request->eventRepeatInterval, 'eventRepeatWeekdays' => $request->eventRepeatWeekdays], $incClosedDates, $skipData);
            } else if ($request->eventRepeatEnd == 'On') {
                $repeat->ser_repeat_end_after_occur = 0;
                $repeat->ser_repeat_end_on_date     = $request->eventRepeatEndOnDate;

                /*$eventDate = new Carbon($eventDate);
                $eventRepeatEndOnDate = new Carbon($request->eventRepeatEndOnDate);
                while($eventDate->lte($eventRepeatEndOnDate)){
                $param = ['eventDate' => $eventDate, 'eventRepeat' => $request->eventRepeat, 'repeatIntv' => $request->eventRepeatInterval];

                if($request->eventRepeat == 'Weekly')
                $param['eventRepeatWeekdays'] = $request->eventRepeatWeekdays;

                $dates[] = $this->calcRepeatsDate($param);
                }

                $this->getRepeatsDate($dates);*/

                $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $eventDate, 'eventRepeatEndOnDate' => $request->eventRepeatEndOnDate, 'eventRepeat' => $request->eventRepeat, 'eventRepeatInterval' => $request->eventRepeatInterval, 'eventRepeatWeekdays' => $request->eventRepeatWeekdays], $incClosedDates, $skipData);
            } else if ($request->eventRepeatEnd == 'Never') {
                $repeat->ser_repeat_end_after_occur = 0;

                /*$eventDate = new Carbon($eventDate);
                $eventRepeatEndOnDate = new Carbon($request->calendEndDate);
                while($eventDate->lte($eventRepeatEndOnDate)){
                $param = ['eventDate' => $eventDate, 'eventRepeat' => $request->eventRepeat, 'repeatIntv' => $request->eventRepeatInterval];

                if($request->eventRepeat == 'Weekly')
                $param['eventRepeatWeekdays'] = $request->eventRepeatWeekdays;

                $dates[] = $this->calcRepeatsDate($param);
                }

                $this->getRepeatsDate($dates);*/

                $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $eventDate, 'eventRepeatEndOnDate' => $request->calendEndDate, 'eventRepeat' => $request->eventRepeat, 'eventRepeatInterval' => $request->eventRepeatInterval, 'eventRepeatWeekdays' => $request->eventRepeatWeekdays], $incClosedDates, $skipData);
            }
        }

        $event->repeat()->save($repeat);
        /*if(count($dates)){
        $event->se_is_repeating = 1;
        $event->save();
        }*/
        //dd($dates);
        //$this->generateEventRepeat($dates, $event, $repeat, $recreate);

        $this->generateEventRepeat($dates, $event, $repeat, ['recreate' => $recreate, 'oldDate' => $request->oldDate, 'noBreak' => false]);
        //}
        //}
    }

    protected function calcRepeatsDateAfterCase($data, $incClosedDates = true, $skipData = [])
    {

        if ($incClosedDates) {
            $closedDates = explode(',', $this->getClosedDates());
        } else {
            $closedDates = [];
        }

        $dates     = [];
        $eventDate = new Carbon($data['eventDate']);
        for ($i = 0; $i < $data['eventRepeatEndAfterOccur'];) {
            $param = ['eventDate' => $eventDate, 'eventRepeat' => $data['eventRepeat'], 'repeatIntv' => $data['eventRepeatInterval']];

            if ($data['eventRepeat'] == 'Weekly' && count($data['eventRepeatWeekdays'])) {
                $param['eventRepeatWeekdays'] = $data['eventRepeatWeekdays'];
            }

            $repeatDate = $this->calcRepeatsDate($param);
            if ($repeatDate && (!count($closedDates) || !in_array($repeatDate, $closedDates)) && !$this->ifSkipDate($skipData, $repeatDate)) {
                $dates[] = $repeatDate;
                $i++;
            }
        }

        $this->getRepeatsDate($dates);
        return $dates;
    }

    protected function calcRepeatsDateOnOrNeverCase($data, $incClosedDates = true, $skipData = [])
    {

        if ($incClosedDates) {
            $closedDates = explode(',', $this->getClosedDates());
        } else {
            $closedDates = [];
        }

        $dates                = [];
        $eventDate            = new Carbon($data['eventDate']);
        $eventRepeatEndOnDate = new Carbon($data['eventRepeatEndOnDate']);
        while ($eventDate->lte($eventRepeatEndOnDate)) {
            $param = ['eventDate' => $eventDate, 'eventRepeat' => $data['eventRepeat'], 'repeatIntv' => $data['eventRepeatInterval']];

            if ($data['eventRepeat'] == 'Weekly' && count($data['eventRepeatWeekdays'])) {
                $param['eventRepeatWeekdays'] = $data['eventRepeatWeekdays'];
            }

            //$dates[] = $this->calcRepeatsDate($param);
            $repeatDate = $this->calcRepeatsDate($param);
            if ($repeatDate && (!count($closedDates) || !in_array($repeatDate, $closedDates)) && !$this->ifSkipDate($skipData, $repeatDate)) {
                $dates[] = $repeatDate;
            }
        }

        $this->getRepeatsDate($dates);
        return $dates;
    }

    protected function ifSkipDate($skipData, $date)
    {
        if (count($skipData)) {
            $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $skipData['startTime'], 'startDate' => $date, 'duration' => $skipData['duration']]);
            $skips               = $skipData['skips'];

            $skip = $skips->where('sk_start_datetime', $startAndEndDatetime['startDatetime'])->where('sk_end_datetime', $startAndEndDatetime['endDatetime'])->first();
            if ($skip) {
                return true;
            }

        }
        return false;
    }

    /**
     * Filter and insert class repeat data
     *
     * @param Collection/Class Request
     * @return void
     */
    protected function neverEndClassRepeats($request)
    {
        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'create-staff-event-class')) {
            return false;
        }

        if (isUserType(['Staff']) && $request->has('areaId') && $request->areaId != 'all') {
            $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->areaId, 'staffId' => Auth::user()->account_id]);
        } else {
            $isAreaLinkedToStaff = true;
        }

        if (!$isAreaLinkedToStaff) {
            return false;
        }

        if ($request->has('repeatId')) {
            $repeatEventId = (int) $request->repeatId;
        }elseif ($request->has('eventId') && $request->eventId != null) {
            $staffClassData = StaffEventClass::select('sec_secr_id')->where('sec_id',$request->eventId)->first();
            if($staffClassData){
                $repeatEventId = $staffClassData->sec_secr_id;
            }
            else
            {
               $repeatEventId = 0; 
            }
        } else {
            $repeatEventId = 0;
        }

        $insertRepeatUpto = $request->insertRepeatUpto;
        // $insertRepeatUpto =  new Carbon("2020-06-30");
        $insertRepeatUpto  = date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 365 day"));        
        $insertRepeatUpto =  new Carbon($insertRepeatUpto);
        if($repeatEventId != 0)
        {
            $this->createRecurrenceClass($repeatEventId, $insertRepeatUpto);
        }
        // $this->createRecurrenceClass(431, $insertRepeatUpto);
    }

    /**
     * Create repeat/recurrence class
     *
     * @param int reapetEventId
     * @param date insertRepeatUpto
     * @return void
     */
    protected function createRecurrenceClass($reapetEventId, $insertRepeatUpto)
    {
       
        $businessId = Session::get('businessId');
        if ($reapetEventId != 0) {
            // $repeatEvent = StaffEventClassRepeat::where('secr_id', $reapetEventId)->get();
            $repeatEvent = StaffEventClassRepeat::select('secr_id', 'secr_business_id', 'secr_repeat', 'secr_repeat_interval', 'secr_repeat_end', 'secr_repeat_end_after_occur', 'secr_repeat_end_on_date', 'secr_repeat_week_days', 'secr_child_count', 'secr_area_id', 'secr_client_id', 'secr_resources', 'secr_staff_id', 'secr_start_time', 'secr_end_time', 'secr_class_id', 'secr_duration', 'secr_capacity', 'secr_price', 'created_at', 'updated_at', 'deleted_at')->where('secr_id', $reapetEventId)->whereNull('deleted_at')->get();
        } else {
            // $repeatEvent = StaffEventClassRepeat::where('secr_business_id', $businessId)->get();
            $repeatEvent = StaffEventClassRepeat::where('secr_business_id', $businessId)->select('secr_id', 'secr_business_id', 'secr_repeat', 'secr_repeat_interval', 'secr_repeat_end', 'secr_repeat_end_after_occur', 'secr_repeat_end_on_date', 'secr_repeat_week_days', 'secr_child_count', 'secr_area_id', 'secr_client_id', 'secr_resources', 'secr_staff_id', 'secr_start_time', 'secr_end_time', 'secr_class_id', 'secr_duration', 'secr_capacity', 'secr_price', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->get();
        }

        // dd($repeatEvent->toArray());
        $dates = array();
        if (count($repeatEvent)) {
            foreach ($repeatEvent as $recurData) {
                $dates = array();

                if ($recurData->secr_repeat_week_days != '') {
                    $eventRepeatWeekdays = json_decode($recurData->secr_repeat_week_days);
                } else {
                    $eventRepeatWeekdays = [];
                }

                // $event = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id', 'sec_class_id', 'sec_duration', 'sec_capacity', 'sec_price', 'sec_notes', 'sec_date', 'sec_time', 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->where('sec_secr_id', $recurData->secr_id)->orderBy('sec_date', 'asc')->whereNull('deleted_at')->first();
                $event = StaffEventClass::/*withTrashed()->*/where('sec_business_id', $businessId)->where('sec_secr_id', $recurData->secr_id)->orderBy('sec_date','desc')->first();

                $eventStartDate = StaffEventClass::/*withTrashed()->*/where('sec_business_id', $businessId)->where('sec_secr_id', $recurData->secr_id)->orderBy('sec_date', 'asc')->pluck('sec_date')->first();

                // $eventStartDate = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id', 'sec_class_id', 'sec_duration', 'sec_capacity', 'sec_price', 'sec_notes', 'sec_date', 'sec_time', 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->where('sec_business_id', $businessId)->where('sec_secr_id', $recurData->secr_id)->orderBy('sec_date', 'asc')->pluck('sec_date')->first();

                // dd($event->toArray());
                if ($eventStartDate) {
                    if ($recurData->secr_repeat_end == 'After') {
                        $dates = $this->calcRepeatsDateAfterCase(['eventDate' => $eventStartDate, 'eventRepeatEndAfterOccur' => $recurData->secr_repeat_end_after_occur, 'eventRepeat' => $recurData->secr_repeat, 'eventRepeatInterval' => $recurData->secr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->secr_repeat_end == 'On') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $eventStartDate, 'eventRepeatEndOnDate' => $recurData->secr_repeat_end_on_date, 'eventRepeat' => $recurData->secr_repeat, 'eventRepeatInterval' => $recurData->secr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->secr_repeat_end == 'Never') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $eventStartDate, 'eventRepeatEndOnDate' => $insertRepeatUpto, 'eventRepeat' => $recurData->secr_repeat, 'eventRepeatInterval' => $recurData->secr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    }
                }

                /* Get only future dates */
                $dates = array_filter($dates, function ($value) use ($event){
                    return $value > /*date('Y-m-d')*/ $event->sec_date;
                });
                $dates = array_values($dates);

              
                if (!empty($dates) && !empty($event)) {
                    foreach ($dates as $date) {
                        $existClassEvent = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id', 'sec_class_id', 'sec_duration', 'sec_capacity', 'sec_price', 'sec_notes', 'sec_date', 'sec_time', 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->where('sec_business_id', $businessId)->where('sec_secr_id', $recurData->secr_id)->where('sec_class_id', $recurData->secr_class_id)->whereDate('sec_date', '=', $date) /*->whereNull('deleted_at')*/->first();
                        
                        $classDay = date('l', strtotime($date));
                        $eventStartDatetime = new Carbon($date.' '.$recurData->secr_start_time);
                        $eventEndDatetime   = new carbon($date.' '.$recurData->secr_end_time);

                        # Check if areas are busy at specified hours.
                        if ($recurData->secr_area_id) 
                            $busyAreaIds = $this->areAreasBusy(['areaId' => [$recurData->secr_area_id], 'startDatetime' => $eventStartDatetime->toDateTimeString(), 'endDatetime' => $eventEndDatetime->toDateTimeString()], 'class');
                        
                        # Check if staff is busy at specified hours.
                        if ($recurData->secr_staff_id) 
                            $staffBusy = $this->isStaffBusy(['staffId' => $recurData->secr_staff_id, 'day' => $classDay, 'startDatetime' =>  $eventStartDatetime->toDateTimeString(), 'endDatetime' => $eventEndDatetime->toDateTimeString()], 'class');

                        # Check if class is busy at specified hours.
                        if($recurData->secr_class_id) 
                            $classBusy = $this->isClassBusy(['classId' => $recurData->secr_class_id, 'startDatetime' => $eventStartDatetime->toDateTimeString(), 'endDatetime' => $eventEndDatetime->toDateTimeString()]);
            
                        // dd($existClassEvent->toArray());
                        if (empty($existClassEvent) && !$staffBusy && count($busyAreaIds) == 0 && !$classBusy) {
                            /* Class event not exist create and add clients */
                            $newClassEvent               = $event->replicate();
                            $newClassEvent->sec_date     = $date;
                            $newClassEvent->sec_staff_id = $recurData->secr_staff_id;
                            $newClassEvent->sec_class_id = $recurData->secr_class_id;
                            $newClassEvent->sec_duration = $recurData->secr_duration;
                            $newClassEvent->sec_capacity = $recurData->secr_capacity;
                            $newClassEvent->sec_price    = $recurData->secr_price;
                            $newClassEvent->sec_time     = $recurData->secr_start_time;

                            $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $recurData->secr_start_time, 'startDate' => $date, 'duration' => $recurData->secr_duration]);

                            $newClassEvent->sec_start_datetime = $startAndEndDatetime['startDatetime'];
                            $newClassEvent->sec_end_datetime   = $startAndEndDatetime['endDatetime'];
                            $newClassEvent->sec_payment        = 0;
                            $newClassEvent->save();

                            $logText = 'Class( Id:'.$newClassEvent->sec_id.', Date:'.$date.') added for future recurrence';
                            setInfoLog($logText, $newClassEvent->sec_id);
                            /* Link area to sibling class */
                            $repeatingEventAreaData = array();
                            if ($recurData->secr_area_id != '') {
                                $areas = explode(',', $recurData->secr_area_id);
                                if (count($areas) > 0) {
                                    foreach ($areas as $area) {
                                        $repeatingEventAreaData[] = array('seca_business_id' => $recurData->secr_business_id, 'seca_sec_id' => $newClassEvent->sec_id, 'seca_la_id' => $area);
                                    }
                                }
                            }

                            /* Link resources to sibling class */
                            $repeatingEventResourcesData = array();
                            if ($recurData->secr_resources != '') {
                                $resources = json_decode($recurData->secr_resources, true);
                                if (count($resources) > 0) {
                                    foreach ($resources as $resource => $item) {
                                        $timestamp                     = createTimestamp();
                                        $repeatingEventResourcesData[] = ['serc_event_id' => $newClassEvent->sec_id, 'serc_event_type' => 'App\StaffEventClass', 'serc_res_id' => $resource, 'serc_item_quantity' => $item, 'created_at' => $timestamp, 'updated_at' => $timestamp];
                                    }
                                }
                            }

                            /* Link Clients to sibling class */
                            $repeatingEventClients = array();
                            $clientsAddedCount     = 0;
                            if ($recurData->secr_client_id != '') {

                                $clients = json_decode($recurData->secr_client_id, true);

                                if (!empty($clients)) {
                                    foreach ($clients as $client_id => $clientRecur) {
                                        $timestamp     = createTimestamp();
                                        $busyClientIds = [];
                                        $membership    = $this->satisfyMembershipRestrictions($client_id, ['event_type' => 'class', 'event_id' => $newClassEvent->sec_class_id, 'event_date' => $date]);
                                        $isInvoice     = false;
                                        $cmid          = $membership['clientMembId'];

                                        $busyClientIds = $this->isClientBusy(['clientId' => [$client_id], 'startDatetime' => $newClassEvent->sec_start_datetime, 'endDatetime' => $newClassEvent->sec_end_datetime]);

                                        if (count($busyClientIds) == 0) {
                                            if ($membership['satisfy']) {
                                                $data = array('secc_sec_id' => $newClassEvent->sec_id, 'secc_client_id' => $client_id, 'secc_if_recur' => 1, 'secc_cmid' => $cmid, 'secc_with_invoice' => 0, 'secc_epic_credit' => 0, 'created_at' => $timestamp, 'updated_at' => $timestamp);

                                                if ($clientsAddedCount >= $recurData->secr_capacity) {
                                                    $data['secc_client_status'] = 'Waiting';
                                                } else {
                                                    $data['secc_client_status'] = 'Confirm';
                                                }

                                                $data['secc_event_log']           = 'Client booked with membership';
                                                $data['secc_action_performed_by'] = getLoggedUserName();

                                                $clientsAddedCount++;

                                                $repeatingEventClients[] = $data;

                                                if ($membership['satisfy']) {
                                                    # Set info log
                                                    setInfoLog('Client membership limit updated on class booked with membership ', $client_id);

                                                    $this->updateClientMembershipLimit($client_id, [$date], ['type' => 'class', 'action' => 'add', 'eventId' => $newClassEvent->sec_class_id, 'limit_type' => $membership['limit_type']]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            /* Save class area */
                            if (!empty($repeatingEventAreaData)) {
                                DB::table('staff_event_class_areas')->insert($repeatingEventAreaData);
                            }
                            /* Save class client */
                            if (!empty($repeatingEventClients)) {
                                DB::table('staff_event_class_clients')->insert($repeatingEventClients);
                            }

                            /* Delete Resources class and insert new resources class */
                            StaffEventResource::where('serc_event_id', $newClassEvent->sec_id)->where('serc_event_type', 'App\StaffEventClass')->forcedelete();
                            if (!empty($repeatingEventResourcesData)) {
                                StaffEventResource::insert($repeatingEventResourcesData);
                            }
                        } /*else {
                    $repeatingEventClients = array();
                    $clientsAddedCount     = 0;
                    if ($recurData->secr_client_id != '') {
                    $clients = json_decode($recurData->secr_client_id, false);

                    if (!empty($clients)) {
                    foreach ($clients as $client_id => $clientRecur) {
                    $timestamp  = createTimestamp();
                    $membership = $this->satisfyMembershipRestrictions($client_id, ['event_type' => 'class', 'event_id' => $existClassEvent->sec_class_id, 'event_date' => $date]);
                    $isInvoice  = false;
                    $cmid       = $membership['clientMembId'];

                    if ($membership['satisfy']) {
                    $data = array('secc_sec_id' => $existClassEvent->sec_id, 'secc_client_id' => $client_id, 'secc_if_recur' => 1, 'secc_cmid' => $cmid, 'secc_with_invoice' => 0, 'secc_epic_credit' => 0, 'created_at' => $timestamp, 'updated_at' => $timestamp);

                    if ($clientsAddedCount >= $recurData->secr_capacity) {
                    $data['secc_client_status'] = 'Waiting';
                    } else {
                    $data['secc_client_status'] = 'Confirm';
                    }

                    $data['secc_event_log']           = 'Client booked with membership';
                    $data['secc_action_performed_by'] = getLoggedUserName();

                    $clientsAddedCount++;

                    $repeatingEventClients[] = $data;

                    if ($membership['satisfy']) {
                    setInfoLog('Client membership limit updated on class booked with membership ', $client_id);

                    $this->updateClientMembershipLimit($client_id, [$date], ['type' => 'class', 'action' => 'add', 'eventId' => $existClassEvent->sec_class_id, 'limit_type' => $membership['limit_type']]);
                    }
                    }
                    }
                    }
                    }

                    if (!empty($repeatingEventClients)) {
                    DB::table('staff_event_class_clients')->insert($repeatingEventClients);
                    }
                    }*/
                    }
                }

            }
        }
    }

    /**
     * Filter and insert Single Service repeat data
     *
     * @param Collection/Class Request
     * @return void
     */
    protected function neverEndSingleServiceRepeats($request)
    {
        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'create-staff-event-appointment')) {
            return false;
        }

        if (isUserType(['Staff']) && $request->has('areaId') && $request->areaId != 'all') {
            $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->areaId, 'staffId' => Auth::user()->account_id]);
        } else {
            $isAreaLinkedToStaff = true;
        }

        if (!$isAreaLinkedToStaff) {
            return false;
        }

        if ($request->has('repeatId')) {
            $repeatEventId = (int) $request->repeatId;
        }elseif ($request->has('eventId') && $request->eventId != null) {
            $staffServiceData = StaffEventSingleService::select('sess_sessr_id')->where('sess_id',$request->eventId)->first();
            if($staffServiceData){
                $repeatEventId = $staffServiceData->sess_sessr_id;
            }
            else{
                $repeatEventId = 0;
            }
        } else {
            $repeatEventId = 0;
        }

        $insertRepeatUpto = $request->insertRepeatUpto ? new Carbon($request->insertRepeatUpto) : null;
        // $insertRepeatUpto =  new Carbon("2020-06-30");
        $insertRepeatUpto = date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 365 day"));
        $insertRepeatUpto =  new Carbon($insertRepeatUpto);
        if($repeatEventId != 0){
            $this->createRecurrenceSingleService($repeatEventId, $insertRepeatUpto);
        }
        // $this->createRecurrenceSingleService(159, $insertRepeatUpto);
        // $this->createRecurrenceSingleService($repeatEventId, $insertRepeatUpto ? $insertRepeatUpto->addYears('1')->toDateString() : '2019-06-30');
    }

    /**
     * Create repeat/recurrence class
     *
     * @param int reapetEventId
     * @param date insertRepeatUpto
     * @return void
     */
    protected function createRecurrenceSingleService($reapetEventId, $insertRepeatUpto)
    {
        // dd($reapetEventId);
        if ($reapetEventId != 0) {
            $repeatEvent = StaffEventSingleServiceRepeat::select('sessr_id', 'sessr_business_id', 'sessr_repeat', 'sessr_repeat_interval', 'sessr_repeat_end', 'sessr_repeat_end_after_occur', 'sessr_repeat_end_on_date', 'sessr_repeat_week_days', 'sessr_child_count', 'sessr_area_id', 'sessr_resources', 'sessr_client_id', 'sessr_staff_id', 'sessr_start_time', 'sessr_end_time', 'sessr_service_id', 'sessr_duration', 'sessr_price', 'sessr_with_invoice', 'sessr_booking_status', 'sessr_auto_expire', 'created_at', 'updated_at', 'deleted_at')->where('sessr_id', $reapetEventId)->whereNull('deleted_at')->get();
        } else {
            $repeatEvent = StaffEventSingleServiceRepeat::select('sessr_id', 'sessr_business_id', 'sessr_repeat', 'sessr_repeat_interval', 'sessr_repeat_end', 'sessr_repeat_end_after_occur', 'sessr_repeat_end_on_date', 'sessr_repeat_week_days', 'sessr_child_count', 'sessr_area_id', 'sessr_resources', 'sessr_client_id', 'sessr_staff_id', 'sessr_start_time', 'sessr_end_time', 'sessr_service_id', 'sessr_duration', 'sessr_price', 'sessr_with_invoice', 'sessr_booking_status', 'sessr_auto_expire', 'created_at', 'updated_at', 'deleted_at')->where('sessr_business_id', Session::get('businessId'))->whereNull('deleted_at')->get();
        }

        // dd($repeatEvent->toArray());

        $dates = array();
        if (count($repeatEvent)) {
            foreach ($repeatEvent as $recurData) {
                // dd( $recurData->toArray());

                if ($recurData->sessr_repeat_week_days != '') {
                    $eventRepeatWeekdays = json_decode($recurData->sessr_repeat_week_days);
                } else {
                    $eventRepeatWeekdays = [];
                }

                // dd($eventRepeatWeekdays);
                // $event = StaffEventSingleService::withTrashed()->where('sess_business_id', Session::get('businessId'))->where('sess_sessr_id', $recurData->sessr_id)->orderBy('sess_date','asc')->whereNull('deleted_at')->first();

                $event = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime', 'sess_end_datetime', 'sess_notes', 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason', 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->where('sess_business_id', Session::get('businessId'))->where('sess_sessr_id', $recurData->sessr_id)->orderBy('sess_date', 'desc')->whereNull('deleted_at')->first();

                // $eventStartDate = StaffEventSingleService::withTrashed()->where('sess_business_id', Session::get('businessId'))->where('sess_sessr_id', $recurData->sessr_id)->orderBy('sess_date','asc')->whereNull('deleted_at')->pluck('sess_date')->first();

                $eventStartDate = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime', 'sess_end_datetime', 'sess_notes', 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason', 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->where('sess_business_id', Session::get('businessId'))->where('sess_sessr_id', $recurData->sessr_id)->orderBy('sess_date', 'asc')->pluck('sess_date')->first();

                // dd( $event->toArray());
                if ($eventStartDate) {
                    if ($recurData->sessr_repeat_end == 'After') {
                        $dates = $this->calcRepeatsDateAfterCase(['eventDate' => $event->sess_date, 'eventRepeatEndAfterOccur' => $recurData->sessr_repeat_end_after_occur, 'eventRepeat' => $recurData->sessr_repeat, 'eventRepeatInterval' => $recurData->sessr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->sessr_repeat_end == 'On') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $event->sess_date, 'eventRepeatEndOnDate' => $recurData->sessr_repeat_end_on_date, 'eventRepeat' => $recurData->sessr_repeat, 'eventRepeatInterval' => $recurData->sessr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->sessr_repeat_end == 'Never') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $event->sess_date, 'eventRepeatEndOnDate' => $insertRepeatUpto, 'eventRepeat' => $recurData->sessr_repeat, 'eventRepeatInterval' => $recurData->sessr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    }
                }

                if (count($dates) && count($event)) {
                    $clientMembershipLimit = collect();
                    foreach ($dates as $date) {
                        if ($date > $event->sess_date) {

                            $existService = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime', 'sess_end_datetime', 'sess_notes', 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason', 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->where('sess_business_id', Session::get('businessId'))->where('sess_sessr_id', $recurData->sessr_id)->where('sess_service_id', $recurData->sessr_service_id)->whereDate('sess_date', '=', $date)->whereNull('deleted_at')->first();

                            if (!count($existService)) {
                                // dd('create');
                                $membership = $this->satisfyMembershipRestrictions($recurData->sessr_client_id, ['event_type' => 'service', 'event_id' => $recurData->sessr_service_id, 'event_date' => $date], $clientMembershipLimit);

                                if ($membership['satisfy'] && $event->sess_with_invoice == 0 && $event->sess_epic_credit == 0) {
                                    $newServiceEvent                  = $event->replicate();
                                    $newServiceEvent->sess_date       = $date;
                                    $newServiceEvent->sess_staff_id   = $recurData->sessr_staff_id;
                                    $newServiceEvent->sess_service_id = $recurData->sessr_service_id;
                                    $newServiceEvent->sess_client_id  = $recurData->sessr_client_id;
                                    $newServiceEvent->sess_duration   = $recurData->sessr_duration;
                                    $newServiceEvent->sess_price      = $recurData->sessr_price;
                                    $newServiceEvent->sess_time       = $recurData->sessr_start_time;
                                    //$newServiceEvent->sess_with_invoice = $recurData->sessr_with_invoice;
                                    $newServiceEvent->sess_booking_status    = $recurData->sessr_booking_status;
                                    $newServiceEvent->sess_auto_expire       = $recurData->sessr_auto_expire;
                                    $newServiceEvent->sess_client_attendance = 'Booked';
                                    $startAndEndDatetime                     = $this->calcStartAndEndDatetime(['startTime' => $recurData->sessr_start_time, 'startDate' => $date, 'duration' => $recurData->sessr_duration]);

                                    $newServiceEvent->sess_start_datetime      = $startAndEndDatetime['startDatetime'];
                                    $newServiceEvent->sess_end_datetime        = $startAndEndDatetime['endDatetime'];
                                    $newServiceEvent->sess_payment             = 0;
                                    $newServiceEvent->sess_event_log           = 'Client booked with membership';
                                    $newServiceEvent->sess_action_performed_by = getLoggedUserName();
                                    $newServiceEvent->save();

                                     $logText = 'Service( Id:'.$newServiceEvent->sess_id.', Date:'.$date.') added for future recurrence';
                                    setInfoLog($logText, $newServiceEvent->sess_id);
                                    /* Link resources to sibling area */
                                    $repeatingEventAreaData = array();
                                    if ($recurData->sessr_area_id != '') {
                                        $areas = explode(',', $recurData->sessr_area_id);
                                        if (count($areas)) {
                                            foreach ($areas as $area) {
                                                $repeatingEventAreaData[] = array('sessa_business_id' => $recurData->sessr_business_id, 'sessa_sess_id' => $newServiceEvent->sess_id, 'sessa_la_id' => $area);
                                            }
                                        }
                                    }

                                    /* Link resources to sibling service */
                                    $repeatingEventResourcesData = array();
                                    if ($recurData->sessr_resources != '') {
                                        $resources = json_decode($recurData->sessr_resources, true);
                                        if (count($resources)) {
                                            foreach ($resources as $resource => $item) {
                                                $timestamp                     = createTimestamp();
                                                $repeatingEventResourcesData[] = ['serc_event_id' => $newServiceEvent->sess_id, 'serc_event_type' => 'App\StaffEventSingleService', 'serc_res_id' => $resource, 'serc_item_quantity' => $item, 'created_at' => $timestamp, 'updated_at' => $timestamp];
                                            }
                                        }
                                    }

                                    /* Save sibling service area */
                                    if (count($repeatingEventAreaData)) {
                                        DB::table('staff_event_single_service_areas')->insert($repeatingEventAreaData);
                                    }

                                    /* Delete Resources service and insert new resources service */
                                    StaffEventResource::where('serc_event_id', $newServiceEvent->sess_id)->where('serc_event_type', 'App\StaffEventSingleService')->forcedelete();
                                    if (count($repeatingEventResourcesData)) {
                                        StaffEventResource::insert($repeatingEventResourcesData);
                                    }

                                    /* Link client with invoice */
                                    if (!$newServiceEvent->sess_with_invoice && !$newServiceEvent->sess_epic_credit) {
                                        # Set info log
                                        setInfoLog('Client membership limit updated on service booked with membership ', $recurData->sessr_client_id);

                                        $membershipLimit = $this->updateClientMembershipLimitLocaly($membership['clientMembLimit'], $recurData->sessr_client_id, ['type' => 'service', 'action' => 'add', 'eventId' => $recurData->sessr_service_id, 'date' => $date, 'limit_type' => $membership['limit_type']]);

                                        $clientMembershipLimit = $membershipLimit;
                                    }
                                }
                            }
                        }
                    }

                    // update limit
                    if (count($clientMembershipLimit)) {
                        $clientMembershipLimit->save();
                    }

                }

            }
        }
        // dd('kjhg');
    }

    /**
     * Filter and insert Busy Time repeat data
     *
     * @param Collection/Class Request
     * @return void
     */
    protected function neverEndBusyTimeRepeats($request)
    {
        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'create-staff-event-appointment')) {
            return false;
        }

        if (isUserType(['Staff']) && $request->has('areaId') && $request->areaId != 'all') {
            $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->areaId, 'staffId' => Auth::user()->account_id]);
        } else {
            $isAreaLinkedToStaff = true;
        }

        if (!$isAreaLinkedToStaff) {
            return false;
        }

        if ($request->has('repeatId')) {
            $repeatEventId = (int) $request->repeatId;
        }elseif ($request->has('eventId') && $request->eventId != null) {
            $busyTimeData = StaffEventBusy::select('seb_sebr_id')->where('seb_id',$request->eventId)->first();
            if($busyTimeData){
                $repeatEventId = $busyTimeData->seb_sebr_id;
            }
            else{
                $repeatEventId = 0;
            }
        } else {
            $repeatEventId = 0;
        }

        $insertRepeatUpto = $request->insertRepeatUpto ? new Carbon($request->insertRepeatUpto) : null;
        // $insertRepeatUpto =  new Carbon("2020-06-30");
        $insertRepeatUpto = date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 365 day"));
        $insertRepeatUpto =  new Carbon($insertRepeatUpto);
        if($repeatEventId != 0){
            $this->createRecurrenceBusyTime($repeatEventId, $insertRepeatUpto);
        }
        // $this->createRecurrenceSingleService(159, $insertRepeatUpto);
        // $this->createRecurrenceSingleService($repeatEventId, $insertRepeatUpto ? $insertRepeatUpto->addYears('1')->toDateString() : '2019-06-30');
    }

    /**
     * Create repeat/recurrence Busy Time
     *
     * @param int reapetEventId
     * @param date insertRepeatUpto
     * @return void
     */
    protected function createRecurrenceBusyTime($reapetEventId, $insertRepeatUpto)
    {
        // dd($reapetEventId);
        if ($reapetEventId != 0) {
            $repeatEvent = StaffEventBusyRepeat::where('sebr_id', $reapetEventId)->whereNull('deleted_at')->get();
        } else {
            $repeatEvent = StaffEventBusyRepeat::where('sebr_business_id', Session::get('businessId'))->whereNull('deleted_at')->get();
        }
        $dates = array();
        if (count($repeatEvent)) {
            foreach ($repeatEvent as $recurData) {
                if ($recurData->sebr_repeat_week_days != '') {
                    $eventRepeatWeekdays = json_decode($recurData->sebr_repeat_week_days);
                } else {
                    $eventRepeatWeekdays = [];
                }

                $event = StaffEventBusy::where('seb_business_id', Session::get('businessId'))->where('seb_sebr_id', $recurData->sebr_id)->orderBy('seb_date', 'desc')->whereNull('deleted_at')->first();

                $eventStartDate = StaffEventBusy::where('seb_business_id', Session::get('businessId'))->where('seb_sebr_id', $recurData->sebr_id)->orderBy('seb_date', 'asc')->pluck('seb_date')->first();
                if ($eventStartDate) {
                    if ($recurData->sebr_repeat_end == 'After') {
                        $dates = $this->calcRepeatsDateAfterCase(['eventDate' => $event->seb_date, 'eventRepeatEndAfterOccur' => $recurData->sebr_repeat_end_after_occur, 'eventRepeat' => $recurData->sebr_repeat, 'eventRepeatInterval' => $recurData->sebr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->sebr_repeat_end == 'On') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $event->seb_date, 'eventRepeatEndOnDate' => $recurData->sebr_repeat_end_on_date, 'eventRepeat' => $recurData->sebr_repeat, 'eventRepeatInterval' => $recurData->sebr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->sebr_repeat_end == 'Never') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $event->seb_date, 'eventRepeatEndOnDate' => $insertRepeatUpto, 'eventRepeat' => $recurData->sebr_repeat, 'eventRepeatInterval' => $recurData->sebr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    }
                }

                if (count($dates) && count($event)) {
                    foreach ($dates as $date) {
                        if ($date > $event->seb_date) {

                            $existService = StaffEventBusy::where('seb_business_id', Session::get('businessId'))->where('seb_sebr_id', $recurData->sebr_id)->whereDate('seb_date', '=', $date)->whereNull('deleted_at')->first();

                            if (!count($existService)) {
                                $newServiceEvent                  = $event->replicate();
                                $newServiceEvent->seb_date       = $date;
                                // $newServiceEvent->seb_staff_id   = $recurData->sebr_staff_id;
                                $newServiceEvent->seb_duration   = $recurData->sebr_duration;
                                $newServiceEvent->seb_time       = $recurData->sebr_start_time;
                                $startAndEndDatetime                     = $this->calcStartAndEndDatetime(['startTime' => $recurData->sebr_start_time, 'startDate' => $date, 'duration' => $recurData->sebr_duration]);

                                $newServiceEvent->seb_start_datetime      = $startAndEndDatetime['startDatetime'];
                                $newServiceEvent->seb_end_datetime        = $startAndEndDatetime['endDatetime'];
                                $newServiceEvent->save();
                                $staffId = explode(',', $recurData->sebr_staff_id);
                                $staffdata = array();
                                foreach($staffId as $staff){
                                    $data_business = array($staff => array('sebs_business_id' => Session::get('businessId')));                
                                    $staffdata = $staffdata + $data_business;   

                                }
                                $newServiceEvent->staffWithTrashed()->sync($staffdata);
    
                                    $logText = 'BusyTime( Id:'.$newServiceEvent->seb_id.', Date:'.$date.') added for future recurrence';
                                setInfoLog($logText, $newServiceEvent->seb_id);
                                
                            }

                        }
                    }

                }

            }
        }
    }

    /**
     * Filter and insert Task repeat data
     *
     * @param Collection/Class Request
     * @return void
     */
    protected function neverEndTaskRepeats($insertRepeatUpto = '')
    {
        $businessId = Session::get('businessId');
        if (!$insertRepeatUpto) {
            $now              = new Carbon();
            $insertRepeatUpto = $now->addMonth()->toDateString();
        }

        $taskRepeats = TaskRepeat::where('tr_business_id', $businessId)->get();
        if (count($taskRepeats)) {
            foreach ($taskRepeats as $taskRepeat) {
                $dates = array();

                $task = Task::withTrashed()->where('task_business_id', $businessId)->where('task_tr_id', $taskRepeat->tr_id)->orderBy('task_due_date', 'desc')->first();
                if (count($task)) {
                    $eventDate            = new Carbon($task->task_due_date);
                    $eventRepeatEndOnDate = new Carbon($insertRepeatUpto);

                    while ($eventDate->lte($eventRepeatEndOnDate)) {
                        $param = ['eventDate' => $eventDate, 'eventRepeat' => $taskRepeat->tr_repeat, 'repeatIntv' => $taskRepeat->tr_repeat_interval];

                        if ($taskRepeat->tr_repeat == 'Weekly') {
                            $param['eventRepeatWeekdays'] = json_decode($taskRepeat->tr_repeat_week_days);
                        }

                        $dates[] = $this->calcRepeatsDate($param);
                    }

                    if (count($dates)) {
                        foreach ($dates as $date) {
                            if ($date > $task->task_due_date) {
                                $existTask = Task::withTrashed()->where('task_business_id', $businessId)->where('task_tr_id', $taskRepeat->tr_id)->where('task_due_date', $date)->first();
                                if (!count($existTask)) {
                                    $newTask                   = $task->replicate();
                                    $newTask->task_tr_id       = $taskRepeat->tr_id;
                                    $newTask->task_user_id     = $taskRepeat->tr_task_user;
                                    $newTask->task_business_id = $taskRepeat->tr_business_id;
                                    $newTask->task_due_date    = $date;
                                    $newTask->task_category    = $taskRepeat->tr_task_category;
                                    $newTask->task_due_time    = $taskRepeat->tr_due_time;
                                    $newTask->task_type        = $taskRepeat->tr_task_type;
                                    $newTask->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function neverEndAppointmentRepeats($request)
    {
        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'create-staff-event-appointment'))
        /*if($request->ajax())
        return false;
        else
        abort(404);*/
        {
            return false;
        }

        if (isUserType(['Staff']) && $request->has('areaId') && $request->areaId != 'all') {
            $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->areaId, 'staffId' => Auth::user()->account_id]);
        } else {
            $isAreaLinkedToStaff = true;
        }

        if (!$isAreaLinkedToStaff) {
            return false;
        }

        //DB::enableQueryLog();
        $colToSel   = "se_id, se_area_id, se_staff_id, se_client_id, se_date, se_booking_status, se_booking_status_confirm, se_auto_expire, se_auto_expire_datetime, se_notes, se_start_time, se_start_datetime, se_end_datetime, se_duration, se_parent_id, ses_service_id, ses_time, ses_price, ses_duration, ser_event_type, ser_repeat, ser_repeat_interval, ser_repeat_week_days"; //, se_total_dur, se_end_time
        $fromTable  = "staff_events INNER JOIN staff_event_services ON ses_staff_event_id = se_id INNER JOIN staff_event_repeats ON (ser_event_id = se_id AND ser_event_type = 'App\\\StaffEvent')";
        $conditions = "se_business_id = " . Session::get('businessId') . " AND ser_repeat_end = 'Never' AND ser_child_count = 0 AND se_date <= '$request->insertRepeatUpto' AND staff_events.deleted_at IS NULL";

        if (isUserType(['Staff'])) {
            $conditions .= " AND se_staff_id = " . Auth::user()->account_id;
        } else if ($request->has('staffId') && $request->staffId != 'all' && $request->staffId != 'all-ros') {
            $conditions .= " AND se_staff_id = $request->staffId";
        }

        if ($request->has('areaId') && $request->areaId != 'all') {
            $conditions .= " AND se_area_id = $request->areaId";
        }

        //dd($conditions);

        $neverEndEvents = DB::select(DB::raw("(SELECT neverEndChildEvents.* FROM (SELECT $colToSel FROM $fromTable WHERE $conditions AND se_parent_id != 0 ORDER BY se_date DESC LIMIT 18446744073709551615) as neverEndChildEvents GROUP BY neverEndChildEvents.se_parent_id) UNION (SELECT $colToSel FROM $fromTable WHERE $conditions AND se_parent_id = 0)"));

        //dd($neverEndEvents);
        //dd(DB::getQueryLog());
        //dd($neverEndEvents->count());
        if (count($neverEndEvents)) {
            foreach ($neverEndEvents as $neverEndEvent) {
                //dd($neverEndEvent);
                $dates                = [];
                $eventDate            = new Carbon($neverEndEvent->se_date);
                $eventRepeatEndOnDate = new Carbon($request->insertRepeatUpto);
                while ($eventDate->lte($eventRepeatEndOnDate)) {
                    $param = ['eventDate' => $eventDate, 'eventRepeat' => $neverEndEvent->ser_repeat, 'repeatIntv' => $neverEndEvent->ser_repeat_interval];

                    if ($neverEndEvent->ser_repeat == 'Weekly') {
                        $param['eventRepeatWeekdays'] = json_decode($neverEndEvent->ser_repeat_week_days);
                    }

                    $dates[] = $this->calcRepeatsDate($param);
                }

                //dd($dates);
                $this->getRepeatsDate($dates);
                array_splice($dates, 0, 1);
                //dd($dates);

                if (count($dates)) {
                    $repeatingEventService = $repeatingEventRepeatData = [];
                    foreach ($dates as $date) {
                        $newEvent                    = new StaffEvent;
                        $newEvent->se_business_id    = Session::get('businessId');
                        $newEvent->se_area_id        = $neverEndEvent->se_area_id;
                        $newEvent->se_staff_id       = $neverEndEvent->se_staff_id;
                        $newEvent->se_client_id      = $neverEndEvent->se_client_id;
                        $newEvent->se_date           = $date;
                        $newEvent->se_start_datetime = $this->calcStartDatetime(['startTime' => $neverEndEvent->se_start_time, 'startDate' => $date]);
                        $newEvent->se_end_datetime   = $this->calcEndDatetimeFromDuration(['duration' => $neverEndEvent->se_duration, 'startDatetime' => $newEvent->se_start_datetime]);
                        $newEvent->se_duration       = $neverEndEvent->se_duration;
                        //$newEvent->se_end_datetime = $this->calcEndDatetime(['startTime' => $neverEndEvent->se_start_time, 'startDate' => $date, 'duration' => $neverEndEvent->se_total_dur]);
                        $newEvent->se_booking_status         = $neverEndEvent->se_booking_status;
                        $newEvent->se_booking_status_confirm = $neverEndEvent->se_booking_status_confirm;
                        $newEvent->se_auto_expire            = $neverEndEvent->se_auto_expire;
                        $newEvent->se_auto_expire_datetime   = $neverEndEvent->se_auto_expire_datetime;
                        $newEvent->se_notes                  = $neverEndEvent->se_notes;
                        $newEvent->se_start_time             = $neverEndEvent->se_start_time;
                        //$newEvent->se_total_dur = $neverEndEvent->se_total_dur;
                        //$newEvent->se_end_time = $neverEndEvent->se_end_time;

                        if (!$neverEndEvent->se_parent_id) {
                            $newEvent->se_parent_id = $neverEndEvent->se_id;
                        } else {
                            $newEvent->se_parent_id = $neverEndEvent->se_parent_id;
                        }

                        $newEvent->se_is_repeating = 1;

                        /*if(isUserType(['Staff']))
                        $newEvent->save();
                        else*/
                        Auth::user()->eventAppointments()->save($newEvent);

                        $timestamp                  = createTimestamp();
                        $repeatingEventRepeatData[] = ['ser_event_id' => $newEvent->se_id, 'ser_event_type' => $neverEndEvent->ser_event_type, 'ser_repeat' => $neverEndEvent->ser_repeat, 'ser_repeat_interval' => $neverEndEvent->ser_repeat_interval, 'ser_repeat_end' => 'Never', 'ser_repeat_week_days' => $neverEndEvent->ser_repeat_week_days, 'created_at' => $timestamp, 'updated_at' => $timestamp];

                        $timestamp               = createTimestamp();
                        $repeatingEventService[] = ['ses_staff_event_id' => $newEvent->se_id, 'ses_service_id' => $neverEndEvent->ses_service_id, 'ses_time' => $neverEndEvent->ses_time, 'ses_price' => $neverEndEvent->ses_price, 'ses_duration' => $neverEndEvent->ses_duration, 'created_at' => $timestamp, 'updated_at' => $timestamp];

                        /*$newEventService = new StaffEventService;
                    $newEventService->ses_service_id = $neverEndEvent->ses_service_id;
                    $newEventService->ses_time = $neverEndEvent->ses_time;
                    $newEventService->ses_price = $neverEndEvent->ses_price;
                    $newEventService->ses_duration = $neverEndEvent->ses_duration;
                    $newEvent->eventServices()->save($newEventService);

                    $newEventRepeat = new StaffEventRepeat;
                    $newEventRepeat->ser_repeat = $neverEndEvent->ser_repeat;
                    $newEventRepeat->ser_repeat_interval = $neverEndEvent->ser_repeat_interval;
                    $newEventRepeat->ser_repeat_end = 'Never';
                    $newEventRepeat->ser_repeat_week_days = $neverEndEvent->ser_repeat_week_days;
                    $newEvent->repeat()->save($newEventRepeat);*/
                    }

                    if (count($repeatingEventRepeatData)) {
                        DB::table('staff_event_repeats')->insert($repeatingEventRepeatData);
                    }

                    if (count($repeatingEventService)) {
                        DB::table('staff_event_services')->insert($repeatingEventService);
                    }

                    if (!$neverEndEvent->se_parent_id) {
                        $eventId = $neverEndEvent->se_id;
                    } else {
                        $eventId = $neverEndEvent->se_parent_id;
                    }

                    StaffEventRepeat::where('ser_event_id', $eventId)->increment('ser_child_count', count($dates));
                }
            }
        }
    }

    protected function calcRepeatsDate($eventData)
    {
        $date = '';
        //dd($eventData);
        if ($eventData['eventRepeat'] == 'Weekly') {
            $day = $eventData['eventDate']->format('D');
            if (isset($eventData['eventRepeatWeekdays']) && $eventData['eventRepeatWeekdays'] != null && in_array($day, $eventData['eventRepeatWeekdays'])) {
                $date = $eventData['eventDate']->copy()->format('Y-m-d');
            }

        } else {
            $date = $eventData['eventDate']->copy()->format('Y-m-d');
            //print_r( $eventData );
        }

        if ($eventData['eventRepeat'] == 'Daily') {
            $eventData['eventDate']->addDays($eventData['repeatIntv']);
        } else if ($eventData['eventRepeat'] == 'Monthly') {
            $eventData['eventDate']->addMonths($eventData['repeatIntv']);
        } else if ($eventData['eventRepeat'] == 'Weekly') {
            $weekend = new Carbon($eventData['eventDate']);
            $weekend = $weekend->endOfWeek()->startOfDay();

            if ($weekend->eq($eventData['eventDate'])) {
                $eventData['eventDate']->addWeeks($eventData['repeatIntv'])->startOfWeek();
            } else {
                $eventData['eventDate']->addDay();
            }

        }
        return $date;
    }

    protected function getRepeatsDate(&$dates)
    {
        if (count($dates)) {
            $dates = array_filter($dates);
            $dates = array_unique($dates);
        }
    }

    protected function delAssociatedEvents($data)
    {
        if ($data['eventType'] == 'class') {
            $model      = 'App\StaffEventClass';
            $idCol      = 'sec_id';
            $delFlagcol = 'sec_deleted_in_chain';
        } else if ($data['eventType'] == 'appointment') {
            $model      = 'App\StaffEvent';
            $idCol      = 'se_id';
            $delFlagcol = 'se_deleted_in_chain';
        } else if ($data['eventType'] == 'single-service') {
            $model      = 'App\StaffEventSingleService';
            $idCol      = 'sess_id';
            $delFlagcol = 'sess_deleted_in_chain';
        } else if ($data['eventType'] == 'task') {
            $model      = 'App\Task';
            $idCol      = 'id';
            $delFlagcol = 'task_deleted_in_chain';
        }

        $events = [];
        if ($data['parentEventId']) {
            $events = $model::SiblingEvents(['parentEventId' => $data['parentEventId'], 'eventDate' => $data['eventDate'], 'eventId' => $data['eventId']])->get();
        } else {
            if ($data['eventType'] == 'single-service') {
                $type = 'service';
            } else {
                $type = $data['eventType'];
            }

            $oldParId = getOldParId($data['eventId'], $type);
            if ($oldParId && $oldParId == -1) {
                $childIds = getChilds($data['eventId'], $type);
                //dd(Session::get('childClass'));
                //dd($childIds);
                if (count($childIds)) {
                    $events = $model::find($childIds);
                }

            } else {
                $events = $model::ChildEvents($data['eventId'])->get();
            }

        }
        //dd($events);

        //if($events->count()){
        if (count($events)) {
            $ids = $skipData = [];
            foreach ($events as $event) {
                $ids[] = $event->$idCol;
                $event->delete();

                if ($data['parentEventId']) {
                    //$skipData[] = ['parentId'=>$event->sec_parent_id, 'eventType' => $model, 'startDatetime'=>$event->sec_start_datetime, 'endDatetime'=>$event->sec_end_datetime];
                    if ($data['eventType'] == 'class') {
                        $skipData[] = ['parentId' => $event->sec_parent_id, 'eventType' => $model, 'startDatetime' => $event->sec_start_datetime, 'endDatetime' => $event->sec_end_datetime];
                    } else if ($data['eventType'] == 'single-service') {
                        $skipData[] = ['parentId' => $event->sess_parent_id, 'eventType' => $model, 'startDatetime' => $event->sess_start_datetime, 'endDatetime' => $event->sess_end_datetime];
                    } else if ($data['eventType'] == 'task') {
                        $skipData[] = ['parentId' => $event->task_parent_id, 'eventType' => $model, 'startDatetime' => $event->task_due_date, 'endDatetime' => $event->task_due_date];
                    }
                }
            }

            if (count($ids)) {
                $model::onlyTrashed()->whereIn($idCol, $ids)->update(array($delFlagcol => 1));
            }

            if (count($skipData)) {
                $this->saveEventSkips($skipData, true);
            }

        }
    }

    protected function delReccurData($event)
    {
        $repeat = $event->repeat->first();
        if ($repeat) {
            $repeat->forceDelete();
        }

    }

    protected function resetEventReccur($event, $bookingType)
    {
        $this->delReccurData($event);
        $this->unsetEventReccurence($event, $bookingType);
    }

    protected function ifEventDetailsUpdated($oldModel, $newModel, $nonComparingFields = [])
    {
        $nonComparingFields[] = 'created_at';
        $nonComparingFields[] = 'updated_at';
        $nonComparingFields[] = 'deleted_at';
        $nonComparingFields[] = 'repeat';

        $oldModel = array_except($oldModel->toArray(), $nonComparingFields);
        $newModel = array_except($newModel->toArray(), $nonComparingFields);

        return count(array_diff_assoc($oldModel, $newModel));
    }

    protected function ifEventRepeatUpdated($oldModel, $newModel)
    {
        if ($oldModel->ser_repeat != $newModel->eventRepeat) {
            return true;
        }

        if ($newModel->eventRepeat == 'Daily' || $newModel->eventRepeat == 'Weekly' || $newModel->eventRepeat == 'Monthly') {
            if ($oldModel->ser_repeat_interval != $newModel->eventRepeatInterval || $oldModel->ser_repeat_end != $newModel->eventRepeatEnd) {
                return true;
            }

            if ($newModel->eventRepeat == 'Weekly') {
                $newWeekdays = $newModel->eventRepeatWeekdays;
                $oldWeekdays = json_decode($oldModel->ser_repeat_week_days);

                if (is_array($newWeekdays) && is_array($oldWeekdays)) {
                    if (count($newWeekdays) > count($oldWeekdays)) {
                        if (count(array_diff($newWeekdays, $oldWeekdays))) {
                            return true;
                        }

                    } else {
                        if (count(array_diff($oldWeekdays, $newWeekdays))) {
                            return true;
                        }

                    }
                } else if ((!is_array($newWeekdays) && is_array($oldWeekdays)) || (is_array($newWeekdays) && !is_array($oldWeekdays))) {
                    return true;
                }

            }

            if ($newModel->eventRepeatEnd == 'After' && $oldModel->ser_repeat_end_after_occur != $newModel->eventRepeatEndAfterOccur) {
                return true;
            }

            if ($newModel->eventRepeatEnd == 'On' && $oldModel->ser_repeat_end_on_date != $newModel->eventRepeatEndOnDate) {
                return true;
            }

        }
        return false;
    }

    protected function calcEventDate($request, $currDate)
    {
        $eventNewDate = $currDate;

        if ($request->has('eventRepeatWeekdays')) {
            $eventDate = Carbon::parse($currDate);
            $eventDay  = $eventDate->format('D');

            if (!in_array($eventDay, $request->eventRepeatWeekdays)) {
                $eventRepeatWeekdays = array_intersect($this->weekDaysArr, $request->eventRepeatWeekdays);
                $eventDayIdx         = array_search($eventDay, $this->weekDaysArr);

                $nearestDayIdx = 0;
                for ($i = $eventDayIdx + 1; $i < count($this->weekDaysArr); $i++) {
                    if (array_key_exists($i, $eventRepeatWeekdays)) {
                        $nearestDayIdx = $i;
                        break;
                    }
                }
                if (!$nearestDayIdx) {
                    reset($eventRepeatWeekdays);
                    $nearestDayIdx = key($eventRepeatWeekdays);
                }

                $daysToAdd = $nearestDayIdx - $eventDayIdx;
                if ($daysToAdd < 0) {
                    $daysToAdd = $daysToAdd + count($this->weekDaysArr);
                }

                $eventNewDate = $eventDate->addDays($daysToAdd)->format('Y-m-d');
            }
        }

        return $eventNewDate;
    }

    /*protected function calcEndTime($data){
    $timestamp = strtotime($data['startTime']) + ($data['duration']*60);
    return date('H:i:s', $timestamp);
    }*/

    protected function calcEndDatetime($data)
    {
        $timestamp = strtotime($data['startDate'] . ' ' . $data['startTime']) + ($data['duration'] * 60);
        return date('Y-m-d H:i:s', $timestamp);
    }

    protected function calcStartDatetime($data)
    {
        $timestamp = strtotime($data['startDate'] . ' ' . $data['startTime']);
        return date('Y-m-d H:i:s', $timestamp);
    }

    protected function datetimeToTime($datetime)
    {
        $timestamp = strtotime($datetime);
        return date('H:i:s', $timestamp);
    }

    /**
     * Insert skip record
     *
     * @param array $data Data to insert
     * @param int $bulk Whether to insert data in bulk
     */
    protected function saveEventSkips($data, $bulk = false)
    {
        if ($bulk && count($data)) {
            $insData = [];
            $i       = 0;
            foreach ($data as $arr) {
                $timestamp   = createTimestamp();
                $insData[$i] = ['sk_parent_id' => $arr['parentId'], 'sk_event_type' => $arr['eventType']/*, 'sk_event_date'=>$arr['date']*/, 'sk_start_datetime' => $arr['startDatetime'], 'sk_end_datetime' => $arr['endDatetime'], 'created_at' => $timestamp, 'updated_at' => $timestamp];

                $i++;
            }
            StaffEventSkip::insert($insData);
        } else {
            $skip                = new StaffEventSkip;
            $skip->sk_parent_id  = $data['parentId'];
            $skip->sk_event_type = $data['eventType'];
            //$skip->sk_event_date = $data['date'];
            $skip->sk_start_datetime = $data['startDatetime'];
            $skip->sk_end_datetime   = $data['endDatetime'];
            $skip->save();
        }
    }

    protected function prepareDataForClashingEvents($data, $eventType = '')
    {
        $preparedData = ['appointmentData' => $data, 'classData' => $data, 'busyTimeData' => $data];

        if (array_key_exists('eventId', $data)) {
            /*if($eventType != 'appointment')
            $preparedData['appointmentData'] = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];

            if($eventType != 'class')
            $preparedData['classData'] = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];

            if($eventType != 'busyTime')
            $preparedData['busyTimeData'] = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];*/
            if ($eventType == 'appointment') {
                unset($preparedData['classData']['eventId']);
                unset($preparedData['busyTimeData']['eventId']);
            } else if ($eventType == 'class') {
                unset($preparedData['appointmentData']['eventId']);
                unset($preparedData['busyTimeData']['eventId']);
                unset($preparedData['appointmentData']['classId']);
                unset($preparedData['busyTimeData']['classId']);
            } else if ($eventType == 'busyTime') {
                unset($preparedData['classData']['eventId']);
                unset($preparedData['appointmentData']['eventId']);
            }
        }

        return $preparedData;
    }

    protected function isAreaBusy($data, $eventType = '')
    {
        $date         = date('Y-m-d', strtotime($data['startDatetime']));
        $preparedData = $this->prepareDataForClashingEvents($data, $eventType);

        //DB::enableQueryLog();
        if (StaffEventBusy::select('seb_id', 'seb_user_id', 'seb_business_id', 'seb_area_id', 'seb_staff_id', 'seb_date', 'seb_time', 'seb_duration', 'seb_end_time', 'seb_start_datetime', 'seb_end_datetime', 'seb_desc', 'seb_deny_booking', 'created_at', 'updated_at', 'deleted_at')->OfBusiness()->where('seb_area_id', $data['areaId'])
            ->whereNull('deleted_at')
            ->where('seb_date', $date)
            ->clashingEvents($preparedData['busyTimeData'])
            ->count()

            /*|| StaffEvent::where('se_area_id', $data['areaId'])
            ->clashingEvents($preparedData['appointmentData'])
            ->count()

            || StaffEventClass::where('sec_area_id', $data['areaId'])
            ->clashingEvents($preparedData['classData'])
            ->count()){*/
            || StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime', 'sess_end_datetime', 'sess_notes', 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason', 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->OfBusiness()->OfArea($data['areaId'])->whereNull('deleted_at')->where('sess_date', $date)
            ->clashingEvents($preparedData['appointmentData'])
            ->count()

            || StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id', 'sec_class_id', 'sec_duration', 'sec_capacity', 'sec_price', 'sec_notes', 'sec_date', 'sec_time', 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->/*whereHas('areas', function($query) use ($data){
            $query->where('seca_la_id', $data['areaId']);
            })*/whereNull('deleted_at')->where('sec_date', $date)->
            OfBusiness()->OfArea($data['areaId'])
            ->clashingEvents($preparedData['classData'])
            ->count()) {
            return true;
        }
        //dd(DB::getQueryLog());
    }

    protected function isStaffBusy($data, $eventType = '')
    {
        $date      = date('Y-m-d', strtotime($data['startDatetime']));
        $startTime = $this->datetimeToTime($data['startDatetime']);
        $endTime   = $this->datetimeToTime($data['endDatetime']);

        if (DB::table('hours')->where('hr_entity_id', $data['staffId'])
            ->where('hr_entity_type', 'staff')
            ->where('hr_day', $data['day'])
            ->where(function ($q) use ($date) {
                $q->where(function ($query) use ($date) {
                    $query->where('hr_edit_date', $date);
                })
                ->orWhereNull('hr_edit_date');
            })
            ->where('hr_start_time', '<=', $startTime)
            ->where('hr_end_time', '>=', $endTime)
            ->whereNull('deleted_at')
            ->count()) {

            $preparedData = $this->prepareDataForClashingEvents($data, $eventType);

            if (StaffEventBusy::select('seb_id', 'seb_user_id', 'seb_business_id', 'seb_area_id', 'seb_staff_id', 'seb_date', 'seb_time', 'seb_duration', 'seb_end_time', 'seb_start_datetime', 'seb_end_datetime', 'seb_desc', 'seb_deny_booking', 'created_at', 'updated_at', 'deleted_at')->OfBusiness()->where('seb_staff_id', $data['staffId'])->whereNull('deleted_at')
                ->where('seb_date', $date)->clashingEvents($preparedData['busyTimeData'])
                ->count()

                /*|| StaffEvent::where('se_staff_id', $data['staffId'])
                ->clashingEvents($preparedData['appointmentData'])
                ->count()*/

                || StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime', 'sess_end_datetime', 'sess_notes', 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason', 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->where('sess_date', $date)->OfBusiness() //where('sec_staff_id', $data['staffId'])
                ->OfStaff($data['staffId'])
                ->clashingEvents($preparedData['appointmentData'])
                ->count()

                || StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id', 'sec_class_id', 'sec_duration', 'sec_capacity', 'sec_price', 'sec_notes', 'sec_date', 'sec_time', 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->where('sec_date', $date)->OfBusiness() //where('sec_staff_id', $data['staffId'])
                ->OfStaff($data['staffId'])
                ->clashingEvents($preparedData['classData'])
                ->count()) {

                return true;
            }

            return false;
        }

        return true;
    }

    protected function isClassBusy($data, $eventType = '')
    {
        $date      = date('Y-m-d', strtotime($data['startDatetime']));
        $startTime = $this->datetimeToTime($data['startDatetime']);
        $endTime   = $this->datetimeToTime($data['endDatetime']);
        $preparedData = $this->prepareDataForClashingEvents($data, $eventType);

        if (StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id', 'sec_class_id', 'sec_duration', 'sec_capacity', 'sec_price', 'sec_notes', 'sec_date', 'sec_time', 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->where('sec_date', $date)->OfBusiness()
                ->clashingEvents($preparedData['classData'])
                ->count()) {

            return true;
        }

        return false;
    }

    protected function prepareEventRecurDdOpt()
    {
        $eventRepeatIntervalOpt = ['' => '-- Select --'];
        for ($intv = 1; $intv <= 31; $intv++) {
            $eventRepeatIntervalOpt[$intv] = $intv;
        }

        return $eventRepeatIntervalOpt;
    }

    protected function isAreaLinkedToStaff($data)
    {
        return DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->where('as_la_id', $data['areaId'])->where('as_staff_id', $data['staffId'])->whereNull('deleted_at')->count();
    }

    /**
     * Get envent for listing
     *
     * @param collection client
     * @return
     */
    protected function eventsListForOverview($entity)
    {
        /* start: Insert never ending events (Commented By Aniket G.)*/
        // $now = new Carbon();
        // $monthEndDate = $now->endOfMonth()->toDateString();
        // $eventRepeatRequest = new Request;
        // $eventRepeatRequest['insertRepeatUpto'] = $monthEndDate;
        // $this->neverEndSingleServiceRepeats($eventRepeatRequest);
        // $this->neverEndClassRepeats($eventRepeatRequest);
        /* end: Insert never ending events */

        $modalLocsAreas = $eventRepeatIntervalOpt = [];
        $pastEvents     = $latestPastEvent     = $latestPastEventInMembership     = $futureEvents     = $oldestFutureEvent     = $oldestFutureEventInMembership     = collect();
        if (isUserType(['Staff']) || Auth::user()->hasPermission(Auth::user(), 'list-staff-event-appointment')) {

            /* start: Fetching loc-areas */
            $data           = $this->locAreasForEvents();
            $modalLocsAreas = $data['locsAreas'];
            /* end: Fetching loc-areas */

            /* start: Preparing recurrence dropdown options */
            $eventRepeatIntervalOpt = $this->prepareEventRecurDdOpt();
            /* end: Preparing recurrence dropdown options */

            $clientMember = 0;
            if (isUserType(['Admin'])) {
                $clientMember = $entity->membership($entity->id);
                if ($clientMember) {
                    $clientMember = $clientMember->id;
                }

            }
            /* start: Fetching past events */
            $pastAppointments = $entity->pastAppointments;
            $pastClasses      = $entity->pastClasses;

            if ($pastAppointments->count() && $pastClasses->count()) {
                $pastEvents = $pastAppointments->merge($pastClasses);
                $pastEvents = $pastEvents->sort(function ($firstEvent, $secondEvent) {
                    if ($firstEvent->eventDate === $secondEvent->eventDate) {
                        if ($firstEvent->eventTime === $secondEvent->eventTime) {
                            return 0;
                        }

                        return $firstEvent->eventTime < $secondEvent->eventTime ? 1 : -1;
                    }
                    return $firstEvent->eventDate < $secondEvent->eventDate ? 1 : -1;
                });
            } else if ($pastAppointments->count()) {
                $pastEvents = $pastAppointments;
            } else if ($pastClasses->count()) {
                $pastEvents = $pastClasses;
            }

            /* end: Fetching past events */

            /* start: Fetching recent past event */
            if ($pastEvents->count()) {
                $latestPastEvent = $pastEvents->filter(function ($pastEvent) {
                    $model = class_basename($pastEvent);
                    if (isUserType(['Staff'])) {
                        return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null && $pastEvent->pivot->deleted_at == null) || ($model == 'StaffEventSingleService' && $pastEvent->deleted_at == null && $pastEvent->sess_booking_status == 'Confirmed');
                    } else {
                        return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null && $pastEvent->pivot->deleted_at == null && $pastEvent->pivot->secc_client_status != 'Waiting' && (!$pastEvent->pivot->secc_epic_credit || $pastEvent->pivot->secc_client_attendance == 'Did not show')) || ($model == 'StaffEventSingleService' && ($pastEvent->deleted_at == null || $pastEvent->sess_if_make_up) && $pastEvent->sess_booking_status == 'Confirmed');
                    }

                })->first();

                if ($clientMember) {
                    $latestPastEventInMembership = $pastEvents->filter(function ($pastEvent) use ($clientMember) {
                        $model = class_basename($pastEvent);
                        return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null && $pastEvent->pivot->deleted_at == null/*&& $pastEvent->pivot->secc_cmid == $clientMember*/ && $pastEvent->pivot->secc_client_status != 'Waiting' && !$pastEvent->pivot->secc_if_make_up_created && (!$pastEvent->pivot->secc_epic_credit || $pastEvent->pivot->secc_client_attendance == 'Did not show')) || ($model == 'StaffEventSingleService' && $pastEvent->deleted_at == null/*&& $pastEvent->sess_if_make_up*//*&& $pastEvent->sess_cmid == $clientMember*/ && $pastEvent->sess_booking_status == 'Confirmed' && !$pastEvent->sess_if_maked_up);
                    })->first();
                }
            }
            /* end: Fetching recent past event */

            /* start: Fetching fututre events */
            $futureAppointments = $entity->futureAppointments;
            $futureClasses      = $entity->futureClasses;
            if ($futureAppointments->count() && $futureClasses->count()) {
                $futureEvents = $futureAppointments->merge($futureClasses);
                $futureEvents = $futureEvents->sort(function ($firstEvent, $secondEvent) {
                    if ($firstEvent->eventDate === $secondEvent->eventDate) {
                        if ($firstEvent->eventTime === $secondEvent->eventTime) {
                            return 0;
                        }

                        return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
                    }
                    return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
                });
            } else if ($futureAppointments->count()) {
                $futureEvents = $futureAppointments;
            } else if ($futureClasses->count()) {
                $futureEvents = $futureClasses;
            }

            /* end: Fetching fututre events */

            /* start: Fetching latest future event */
            if ($futureEvents->count()) {
                $oldestFutureEvent = $futureEvents->filter(function ($futureEvent) {
                    $model = class_basename($futureEvent);
                    if (isUserType(['Staff'])) {
                        return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null && $futureEvent->sess_booking_status == 'Confirmed' && $futureEvent->sess_client_attendance == 'Booked');
                    } else {
                        return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null && !$futureEvent->pivot->secc_epic_credit) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null && $futureEvent->sess_booking_status == 'Confirmed' && !$futureEvent->sess_if_make_up);
                    }

                })->first();

                if ($clientMember) {
                    $oldestFutureEventInMembership = $futureEvents->filter(function ($futureEvent) use ($clientMember) {
                        $model = class_basename($futureEvent);
                        return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null/*&& $futureEvent->pivot->secc_cmid == $clientMember */ && !$futureEvent->pivot->secc_epic_credit) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null/*&& $futureEvent->sess_cmid == $clientMember*/ && $futureEvent->sess_booking_status == 'Confirmed' && $futureEvent->sess_client_attendance == 'Booked' && !$futureEvent->sess_if_make_up);
                    })->first();
                }
            }
            /* end: Fetching latest future event */
        }

        return compact('pastEvents', 'latestPastEvent', 'latestPastEventInMembership', 'futureEvents', 'oldestFutureEvent', 'oldestFutureEventInMembership', 'modalLocsAreas', 'eventRepeatIntervalOpt');
    }

    protected function haltScript($request)
    {
        if ($request->ajax()) {
            return [];
        } else {
            abort(404);
        }

    }

    protected function calcEndDatetimeFromDuration($data)
    {
        $carbon = Carbon::parse($data['startDatetime']);
        return $carbon->addMinutes($data['duration'])->toDateTimeString();
    }

    protected function calcEventDuartion($data)
    {
        $startDatetime = Carbon::parse($data['startDatetime']);
        $endDatetime   = Carbon::parse($data['endDatetime']);
        return $startDatetime->diffInMinutes($endDatetime); //->format('%H:%i:%s');
    }

    protected function calcStartAndEndDatetime($param)
    {
        $data = ['startTime' => $param['startTime'], 'startDate' => $param['startDate']];

        $result                  = [];
        $result['startDatetime'] = $this->calcStartDatetime(['startTime' => $data['startTime'], 'startDate' => $data['startDate']]);

        if (array_key_exists("startTimeForEnd", $param)) {
            $data['startTime'] = $param['startTimeForEnd'];
        }

        $data['duration']      = $param['duration'];
        $result['endDatetime'] = $this->calcEndDatetime($data);
        $result['endDate']     = date("Y-m-d", strtotime($result['endDatetime']));

        return $result;
    }

    /**
     * Check if client satisfy the membership restrictions
     *
     * @param int $clientId Client ID
     * @param array $eventData ['event_type','event_id','event_date']
     * @return array
     */
    protected function satisfyMembershipRestrictions($clientId, $eventData, $clientMembershipLimit = '')
    { 
        $isMembError = false;
        if ($clientMembershipLimit == '') {
            $clientMembershipLimit = collect();
        }


        // $clientMember = ClientMember::where('cm_client_id', $clientId)->orderBy('id','desc')->first();

        $clientMember = ClientMember::where('cm_client_id', $clientId)->where('cm_status', 'Active')->orderBy('id', 'desc')->whereNull('deleted_at')->first();
        // $clientMember = $client->membership($clientId);

        $client = Clients::find($clientId);

        if (!count($clientMember) || ($client && !in_array($client->account_status, ['Active', 'Contra']))) {
            $isMembError = true;
            return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => 'membership_doesnot_exit');
        }

        if (!$isMembError && $clientMember->cm_status != 'Active') {
            $isMembError = true;
            $failReas    = 'membership_' . strtolower($clientMember->cm_status);
            return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => $failReas);
        }

        if (!$isMembError) {
            // dd($clientMember);
            if ($eventData['event_type'] == 'class') {
                // check membership for class
                # Get membership details
                $membership = MemberShip::select('id', 'me_membership_label')
                    ->where('id', $clientMember->cm_membership_id)
                    ->with('classmember')
                    ->first();
                # Membership classes lists
                if ($membership && $membership->classmember->count()) {
                    foreach ($membership->classmember as $value) {
                        $classes[$value->cl_id] = $value->cl_name;
                    }

                }
                $session_limits = json_decode($clientMember->cm_session_limit, 1);
                $sessionId= $eventData['event_id'];

               
                // $classes = json_decode($clientMember->cm_classes, 1);

                // if (!array_key_exists($eventData['event_id'], $classes)) {
                //     $isMembError = true;
                //     return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => 'class_doesnot_exist_in_membership');
                // }
                if (!$isMembError) {
                    if(array_key_exists($eventData['event_id'], $classes)){
                       
                        if ($clientMember->cm_class_limit == 'unlimited') {
                            return array('satisfy' => true, 'clientMembId' => $clientMember->id);
                        } elseif ($clientMember->cm_class_limit == 'limited') {
                           
                            if ($clientMember->cm_class_limit_type == 'every_week') {
                                $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'classes_weekly', $clientMember->cm_class_limit_length, $eventData['event_date'], $clientMembershipLimit);
                                if ($clientMembEvent['status']) {
                                    return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_week', 'clientMembLimit' => $clientMembEvent['clientMembLimit']);
                                } else {
                                    if(count($session_limits)){
                                    foreach($session_limits as $key => $value){
                                        $clasData = Clas::OfBusiness()->where('cl_clcat_id', $key)->pluck('cl_id')->toArray();
                                       
                                        if(in_array($sessionId, $clasData)){
                                            $clasType = ClassCat::OfBusiness()->where('clcat_id',$key)->pluck('clcat_value')->toArray();
                                            $carbonDate    = Carbon::createFromFormat('Y-m-d', $eventData['event_date']);
                                            $weekStartDate = $carbonDate->copy()->startOfWeek();
                                            $weekEndDate   = $carbonDate->copy()->endOfWeek();
                                            $staffEventClassCount =  StaffEventClass::whereBetween('sec_date',[$weekStartDate,  $weekEndDate])->whereHas('clas.cat', function($q) use($key){
                                                $q->where('clcat_id',$key);
                                            })->whereHas('clientsRaw', function($q) use($clientId){ $q->where('secc_client_id', $clientId)->where('secc_class_extra',1)->where(function($query){
                                                $query->whereNull('staff_event_class_clients.deleted_at')
                                                ->orWhere(function($qu){
                                                    $qu->where('secc_if_make_up_created',1)
                                                    ->where('staff_event_class_clients.deleted_at','!=',null);
                                                });
                                            });
                                            })->count();
                                            if($staffEventClassCount < $value['limit']){
                                              
                                                return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_week', 'clientMembLimit' => $clientMembEvent['clientMembLimit'],'type' => 'classExtra');
                                                      
                                            }else{
                                                return array('satisfy' => false, 'clientMembId' => $clientMember->id,  'failReas' => $clientMembEvent['message']);
                                            }
                                           
                                        }
                                    }
                                    return array('satisfy' => false, 'clientMembId' => $clientMember->id, 'failReas' => $clientMembEvent['message']);
                                   }else{
                                    return array('satisfy' => false, 'clientMembId' => $clientMember->id, 'failReas' => $clientMembEvent['message']);
                                   }
                                }

                            } elseif ($clientMember->cm_class_limit_type == 'every_month') {
                                $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'classes_monthly', $clientMember->cm_class_limit_length, $eventData['event_date'], $clientMembershipLimit);
                                if ($clientMembEvent['status']) {
                                    return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_month', 'clientMembLimit' => $clientMembEvent['clientMembLimit']);
                                } else {
                                    if(count($session_limits)){
                                        foreach($session_limits as $key => $value){
                                            $clasData = Clas::OfBusiness()->where('cl_clcat_id', $key)->pluck('cl_id')->toArray();
                                           
                                            if(in_array($sessionId, $clasData)){
                                                $clasType = ClassCat::OfBusiness()->where('clcat_id',$key)->pluck('clcat_value')->toArray();
                                                $carbonDate    = Carbon::createFromFormat('Y-m-d', $eventData['event_date']);
                                                $monthStartDate = $carbonDate->copy()->startOfMonth();
                                                $monthEndDate   = $carbonDate->copy()->endOfMonth();
                                                $staffEventClassCount =  StaffEventClass::whereBetween('sec_date',[$monthStartDate,  $monthEndDate])->whereHas('clas.cat', function($q) use($key){
                                                    $q->where('clcat_id',$key);
                                                })->whereHas('clientsRaw', function($q) use($clientId){ $q->where('secc_client_id', $clientId)->where('secc_class_extra',1)->where(function($query){
                                                    $query->whereNull('staff_event_class_clients.deleted_at')
                                                    ->orWhere(function($qu){
                                                        $qu->where('secc_if_make_up_created',1)
                                                        ->where('staff_event_class_clients.deleted_at','!=',null);
                                                    });
                                                });
                                                })->count();
                                                if($staffEventClassCount < $value['limit']){
                                                  
                                                    return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_month', 'clientMembLimit' => $clientMembEvent['clientMembLimit'],'type' => 'classExtra');
                                                          
                                                }else{
                                                    return array('satisfy' => false, 'clientMembId' => $clientMember->id,  'failReas' => $clientMembEvent['message']);
                                                }
                                               
                                            }
                                              }
                                              return array('satisfy' => false, 'clientMembId' => $clientMember->id, 'failReas' => $clientMembEvent['message']);
                                    }else{
                                        return array('satisfy' => false, 'clientMembId' => $clientMember->id, 'failReas' => $clientMembEvent['message']);
                                       }
                                }

                            }elseif ($clientMember->cm_class_limit_type == 'every_fortnight') {
                                $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'classes_fortnight', $clientMember->cm_class_limit_length, $eventData['event_date'], $clientMembershipLimit);
                                if ($clientMembEvent['status']) {
                                    return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_fortnight', 'clientMembLimit' => $clientMembEvent['clientMembLimit']);
                                } else {
                                    $carbonDate    = Carbon::createFromFormat('Y-m-d', $eventData['event_date']);
                                    $year =   $carbonDate->copy()->format('Y');
                                    $weekDate = $carbonDate->copy();
                                    // dd($weekDate);
                        
                                    $weekNo = $weekDate->weekOfYear;
                                    $weekRemainder = $weekNo % 2;
                                    if($weekRemainder == 1){
                                        $startFortnightWeek = $carbonDate->copy()->startOfWeek();
                                        $currentDate = Carbon::now(); 
                                        $currentDate->setISODate($year, $weekNo+1); 
                                        $endFortnightWeek = $currentDate->endOfWeek(); 
                                      
                                    }else{
                                        $currentDate = Carbon::now();
                                        $currentDate->setISODate($year, $weekNo-1);
                                        $startFortnightWeek = $currentDate->startOfWeek();
                                        $endFortnightWeek   = $carbonDate->copy()->endOfWeek();
                                    }
                                    if(count($session_limits)){
                                        foreach($session_limits as $key => $value){
                                            $clasData = Clas::OfBusiness()->where('cl_clcat_id', $key)->pluck('cl_id')->toArray();
                                           
                                            if(in_array($sessionId, $clasData)){
                                                $clasType = ClassCat::OfBusiness()->where('clcat_id',$key)->pluck('clcat_value')->toArray();
                                                $staffEventClassCount =  StaffEventClass::whereBetween('sec_date',[$startFortnightWeek,  $endFortnightWeek])->whereHas('clas.cat', function($q) use($key){
                                                    $q->where('clcat_id',$key);
                                                })->whereHas('clientsRaw', function($q) use($clientId){ $q->where('secc_client_id', $clientId)->where('secc_class_extra',1)->where(function($query){
                                                    $query->whereNull('staff_event_class_clients.deleted_at')
                                                    ->orWhere(function($qu){
                                                        $qu->where('secc_if_make_up_created',1)
                                                        ->where('staff_event_class_clients.deleted_at','!=',null);
                                                    });
                                                });
                                                })->count();
                                                if($staffEventClassCount < $value['limit']){
                                                  
                                                    return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_fortnight', 'clientMembLimit' => $clientMembEvent['clientMembLimit'],'type' => 'classExtra');
                                                          
                                                }else{
                                                    return array('satisfy' => false, 'clientMembId' => $clientMember->id,  'failReas' => $clientMembEvent['message']);
                                                }
                                               
                                            }
                                        }
                                        return array('satisfy' => false, 'clientMembId' => $clientMember->id, 'failReas' => $clientMembEvent['message']);
                                       }else{
                                        return array('satisfy' => false, 'clientMembId' => $clientMember->id, 'failReas' => $clientMembEvent['message']);
                                       }
                                }
        
                            }
                        }
                    }else{
                        foreach($session_limits as $key => $value){
                           $clasData = Clas::OfBusiness()->where('cl_clcat_id', $key)->pluck('cl_id')->toArray();
                           if(in_array($sessionId, $clasData)){
                                 if($value['limit_type'] == 'every_week'){
                                    $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'classes_weekly', $value['limit'], $eventData['event_date'],$clientMembershipLimit, $sessionId); 
                                    if ($clientMembEvent['status']) {
                                        return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_week', 'clientMembLimit' => $clientMembEvent['clientMembLimit'], 'type' => 'classExtra');
                                    } else {
                                        return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => $clientMembEvent['message']);
                                    }
                                 } elseif ($value['limit_type'] == 'every_month') {
                                    $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'classes_monthly', $value['limit'], $eventData['event_date'], $clientMembershipLimit, $sessionId);
                                    if ($clientMembEvent['status']) {
                                        return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_month', 'clientMembLimit' => $clientMembEvent['clientMembLimit']);
                                    } else {
                                        return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => $clientMembEvent['message']);
                                    }
            
                                }elseif ($value['limit_type'] == 'every_fortnight') {
                                    $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'classes_fortnight', $value['limit'], $eventData['event_date'], $clientMembershipLimit, $sessionId);
                                    if ($clientMembEvent['status']) {
                                        return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_fortnight', 'clientMembLimit' => $clientMembEvent['clientMembLimit']);
                                    } else {
                                        return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => $clientMembEvent['message']);
                                    }
            
                                }
                           }
                        }
                        return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => 'condition not satisfy');
                        
                        
                    }
                }

            } elseif ($eventData['event_type'] == 'service') {
                // check membership for service
                $service_limits = json_decode($clientMember->cm_services_limit, 1);
                $serviceId      = $eventData['event_id'];
                if (count($service_limits) && array_key_exists($serviceId, $service_limits) && array_key_exists('limit', $service_limits[$serviceId]) && array_key_exists('limit_type', $service_limits[$serviceId])) {
                    $s_limit      = $service_limits[$serviceId]['limit'];
                    $s_limit_type = $service_limits[$serviceId]['limit_type'];

                    if ($s_limit_type == 'every_week') {
                        $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'service_weekly', $s_limit, $eventData['event_date'], $clientMembershipLimit, $serviceId);
                        // dd($clientMemsbEvent);
                        if ($clientMembEvent['status']) {
                            return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_week', 'clientMembLimit' => $clientMembEvent['clientMembLimit']);
                        } else {
                            return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => $clientMembEvent['message']);
                        }

                    } elseif ($s_limit_type == 'every_month') {
                        $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'service_monthly', $s_limit, $eventData['event_date'], $clientMembershipLimit, $serviceId);
                        if ($clientMembEvent['status']) {
                            return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_month', 'clientMembLimit' => $clientMembEvent['clientMembLimit']);
                        } else {
                            return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => $clientMembEvent['message']);
                        }

                    } elseif ($s_limit_type == 'every_fortnight') {
                        $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'service_fortnight', $s_limit, $eventData['event_date'], $clientMembershipLimit, $serviceId);
                        if ($clientMembEvent['status']) {
                            return array('satisfy' => true, 'clientMembId' => $clientMember->id, 'limit_type' => 'every_fortnight', 'clientMembLimit' => $clientMembEvent['clientMembLimit']);
                        } else {
                            return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => $clientMembEvent['message']);
                        }

                    }
                } else {
                    return array('satisfy' => false, 'clientMembId' => 0, 'failReas' => 'limit_doesnot_exist');
                }
            }
        }
    }

    /**
     * check how many events booked in this membership
     * @param string Type (service/classes_monthly/classes_weekly)
     * @param int Event Limit
     * @param date Event date
     * @return array
     */
    protected function isClientMembEventSatisfy($clientId, $limit_type, $limit, $eventDate, $clientMembEvent, $serviceId = 0)
    {
       
        if (!count($clientMembEvent)) {
            $clientMembEvent = ClientMemberLimit::where('cme_client_id', $clientId)->first();
        }
        if (count($clientMembEvent)) {
            $carbonDate    = Carbon::createFromFormat('Y-m-d', $eventDate);
            $eventDate     = $carbonDate->copy();
            $weekStartDate = $carbonDate->copy()->startOfWeek();
            $weekEndDate   = $carbonDate->copy()->endOfWeek();
            $monthStartDate = $carbonDate->copy()->startOfMonth();
            $monthEndDate   = $carbonDate->copy()->endOfMonth();
            $year =   $carbonDate->copy()->format('Y');
            $weekDate = $carbonDate->copy();
            // dd($weekDate);

            $weekNo = $weekDate->weekOfYear;
            $weekRemainder = $weekNo % 2;
            if($weekRemainder == 1){
                $startFortnightWeek = $carbonDate->copy()->startOfWeek();
                $currentDate = Carbon::now(); 
                $currentDate->setISODate($year, $weekNo+1); 
                $endFortnightWeek =$currentDate->endOfWeek(); 
              
            }else{
                $currentDate = Carbon::now();
                $currentDate->setISODate($year, $weekNo-1);
                $startFortnightWeek = $currentDate->startOfWeek();
                $endFortnightWeek   = $carbonDate->copy()->endOfWeek();
            }

           
            $eventyear = (($weekStartDate->year < $weekEndDate->year) && ($limit_type == 'service_weekly' || $limit_type == 'classes_weekly')) ? $weekStartDate->year : $eventDate->year;

            if ($serviceId != 0) {

              
                if ($limit_type == 'service_weekly') {
                    $weekDate = $carbonDate->copy();
                    // dd($weekDate);

                    $weekNo = $weekDate->weekOfYear;
                    // dd($weekNo);
                    if ($clientMembEvent->cme_services_weekly != '') {
                        $weeklyService = json_decode($clientMembEvent->cme_services_weekly, 1);

                        // dd($limit);

                        if (array_key_exists($serviceId, $weeklyService) && array_key_exists($eventyear, $weeklyService[$serviceId]) && array_key_exists($weekNo, $weeklyService[$serviceId][$eventyear])) {

                            if ($weeklyService[$serviceId][$eventyear][$weekNo] < $limit) {
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            } else {
                                return array('status' => false, 'message' => 'limit_exceeded', 'cmeId' => $clientMembEvent->id);
                            }

                        } else{
                            if($limit > 0){
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            }else{
                                return array('status' => false, 'message' => 'limit not exist');
                            }
                        }
                    }else{
                        if($limit > 0){
                            return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                        }else{
                            return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                        }
                    }
                } elseif ($limit_type == 'service_monthly') {
                    $monthDate = $carbonDate->copy();
                    $monthNo   = $monthDate->month;
                    if ($clientMembEvent->cme_services_monthly != '') {
                        $monthlyService = json_decode($clientMembEvent->cme_services_monthly, 1);
                        if (array_key_exists($serviceId, $monthlyService) && array_key_exists($eventyear, $monthlyService[$serviceId]) && array_key_exists($monthNo, $monthlyService[$serviceId][$eventyear])) {
                            if ($monthlyService[$serviceId][$eventyear][$monthNo] < $limit) {
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            } else {
                                return array('status' => false, 'message' => 'limit_exceeded', 'cmeId' => $clientMembEvent->id);
                            }

                        } else{
                            if($limit > 0){
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            }else{
                                return array('status' => false, 'message' => 'limit not exist');
                            }
                        }
                    }else{
                        if($limit > 0){
                            return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                        }else{
                            return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                        }
                    }
                } elseif ($limit_type == 'service_fortnight') {
                    $fortnightDate = $carbonDate->copy();
                    $fortnightNo   = $this->getEvenWeekNumb($fortnightDate);
                    if ($clientMembEvent->cme_services_fortnight != '') {
                        $fortnightClasses = json_decode($clientMembEvent->cme_services_fortnight, 1);
                        if (array_key_exists($serviceId, $fortnightClasses) && array_key_exists($eventyear, $fortnightClasses[$serviceId]) && array_key_exists($fortnightNo, $fortnightClasses[$serviceId][$eventyear])) {
                            if ($fortnightClasses[$serviceId][$eventyear][$fortnightNo] < $limit) {
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            } else {
                                return array('status' => false, 'message' => 'limit_exceeded', 'cmeId' => $clientMembEvent->id);
                            }

                        } else{
                            if($limit > 0){
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            }else{
                                return array('status' => false, 'message' => 'limit not exist');
                            }
                        }
                    }else{
                        if($limit > 0){
                            return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                        }else{
                            return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                        }
                    }
                }else if ($limit_type == 'classes_weekly') {
                 
                    $staffEventClassCount =  StaffEventClass::where('sec_class_id', $serviceId)->whereBetween('sec_date',[$weekStartDate,  $weekEndDate])->whereHas('clientsRaw', function($q) use($clientId){ $q->where('secc_client_id', $clientId)->where(function($query){
                        $query->whereNull('staff_event_class_clients.deleted_at')
                        ->orWhere(function($qu){
                            $qu->where('secc_if_make_up_created',1)
                            ->where('staff_event_class_clients.deleted_at','!=',null);
                        });
                    });
                    })->count();
                    
                    if($staffEventClassCount < $limit){
                        return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                              
                    }else{
                        return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                    }
                }else if ($limit_type == 'classes_monthly') {
                    $staffEventClassCount =  StaffEventClass::where('sec_class_id', $serviceId)->whereBetween('sec_date',[$monthStartDate,  $monthEndDate])->whereHas('clientsRaw', function($q) use($clientId){ $q->where('secc_client_id', $clientId)->where(function($query){
                        $query->whereNull('staff_event_class_clients.deleted_at')
                        ->orWhere(function($qu){
                            $qu->where('secc_if_make_up_created',1)
                            ->where('staff_event_class_clients.deleted_at','!=',null);
                        });
                    });
                    })->count();
                    // dd($staffEventClassCount);
                    if($staffEventClassCount < $limit){
                        return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                              
                    }else{
                        return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                    }
                }else if ($limit_type == 'classes_fortnight') {
                    $staffEventClassCount =  StaffEventClass::where('sec_class_id', $serviceId)->whereBetween('sec_date',[$startFortnightWeek,  $endFortnightWeek])->whereHas('clientsRaw', function($q) use($clientId){ $q->where('secc_client_id', $clientId)->where(function($query){
                        $query->whereNull('staff_event_class_clients.deleted_at')
                        ->orWhere(function($qu){
                            $qu->where('secc_if_make_up_created',1)
                            ->where('staff_event_class_clients.deleted_at','!=',null);
                        });
                    });
                    })->count();
                    // dd($staffEventClassCount);
                    if($staffEventClassCount < $limit){
                        return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                              
                    }else{
                        return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                    }
                }
            } else {
                if ($limit_type == 'classes_weekly') {
                    $weekDate = $carbonDate->copy();
                    $weekNo   = $weekDate->weekOfYear;
                    if ($clientMembEvent->cme_classes_weekly != '') {
                        $weeklyClasses = json_decode($clientMembEvent->cme_classes_weekly, 1);
                        if (array_key_exists($eventyear, $weeklyClasses) && array_key_exists($weekNo, $weeklyClasses[$eventyear])) {
                            if ($weeklyClasses[$eventyear][$weekNo] < $limit) {
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            } else {
                                return array('status' => false, 'message' => 'limit_exceeded', 'cmeId' => $clientMembEvent->id);
                            }

                        } /*else
                    return array('status'=>false,'message'=>'Membership not exist','cmeId'=>$clientMembEvent->id);*/
                    }else{
                        if($limit > 0){
                            return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                        }else{
                            return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                        }
                    }
                } elseif ($limit_type == 'classes_monthly') {
                    $monthDate = $carbonDate->copy();
                    $monthNo   = $monthDate->month;
                    if ($clientMembEvent->cme_classes_monthly != '') {
                        $monthlyClasses = json_decode($clientMembEvent->cme_classes_monthly, 1);
                        if (array_key_exists($eventyear, $monthlyClasses) && array_key_exists($monthNo, $monthlyClasses[$eventyear])) {
                            if ($monthlyClasses[$eventyear][$monthNo] < $limit) {
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            } else {
                                return array('status' => false, 'message' => 'limit_exceeded', 'cmeId' => $clientMembEvent->id);
                            }

                        }else{
                            if($limit > 0){
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            }else{
                                return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                            }
                        } /* else
                    return array('status'=>false,'message'=>'Membership not exist','cmeId'=>$clientMembEvent->id);*/
                    }else{
                        if($limit > 0){
                            return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                        }else{
                            return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                        }
                    }
                }else if ($limit_type == 'classes_fortnight') {
                    $fortnightDate = $carbonDate->copy();
                    $fortnightNo   = $this->getEvenWeekNumb($fortnightDate);
                   
                    if ($clientMembEvent->cme_classes_fortnight != '') {
                        $fortnightClasses = json_decode($clientMembEvent->cme_classes_fortnight, 1);
                        if (array_key_exists($eventyear, $fortnightClasses) && array_key_exists($fortnightNo, $fortnightClasses[$eventyear])) {
                            
                            if ($fortnightClasses[$eventyear][$fortnightNo] < $limit) {
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            } else {
                               return array('status' => false, 'message' => 'limit_exceeded', 'cmeId' => $clientMembEvent->id);
                            }

                        } else{
                            if($limit > 0){
                                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                            }else{
                                return array('status' => false, 'message' => 'limit not exist');
                            }
                        }
                    }else{
                        if($limit > 0){
                            return array('status' => true, 'clientMembLimit' => $clientMembEvent);
                        }else{
                            return array('status' => false, 'clientMembLimit' => $clientMembEvent);
                        }
                    }
                }
            }
        }else{
            if($limit > 0){
                return array('status' => true, 'clientMembLimit' => $clientMembEvent);
            }else{
                return array('status' => false, 'clientMembLimit' => $clientMembEvent);
            }
        }
        
    }

    /**
     * Check if client satisfy LDC class restrictions
     * 
     * @param int client id
     * @param int event id
     * @return bool 
     */
    protected function isSatisfyLdcRestriction($clientId,$eventId,$mode="",$type="",$optionalData = []){
        $isSatisfy = false;
        
        $ldcSessionId = Clients::where('id',$clientId)->where('ldc_status',1)->select('ldc_session_id')->first();
        $ldcData = Ldc::where('ldc_id',$ldcSessionId['ldc_session_id'])->first();
        if($type == 'class'){
            $classData = StaffEventClass::where('sec_id',$eventId)->select('sec_class_id','sec_date')->first();
            $ldcStartDate = Carbon::parse($ldcData['ldc_start_date'])->format('Y-m-d H:i:s');
            $ldcEndDate = Carbon::parse($ldcData['ldc_end_date'])->format('Y-m-d H:i:s');
            $classSecDate = Carbon::parse($classData['sec_date'])->format('Y-m-d H:i:s');
            if(($classSecDate >= $ldcStartDate) && ($classSecDate <= $ldcEndDate)){
                $class = Clas::with('cat')->where('cl_id',$classData['sec_class_id'])->first();
                $ldcClass = json_decode($ldcData->ldc_sessions,1);
                $catType = $class->cat->clcat_id;
            
                if(array_key_exists($catType,$ldcClass)){
                    if($mode == 'reschedule'){
                        $isSatisfy = true;
                    }else if($ldcClass[$catType]['limit_type'] == 'every_week'){                
                        $carbonDate    = Carbon::createFromFormat('Y-m-d', $classData['sec_date']);
                        $weekStartDate = $carbonDate->copy()->startOfWeek();
                        $weekEndDate   = $carbonDate->copy()->endOfWeek();
                        $staffEventClassCount =  StaffEventClass::join('staff_event_class_clients','staff_event_classes.sec_id','=','staff_event_class_clients.secc_sec_id')->orderBy('sec_date','desc')->whereBetween('sec_date',[$weekStartDate,  $weekEndDate])->where('secc_client_id', $clientId)->where('is_ldc',1)->where(function($query){
                            $query->whereNull('staff_event_class_clients.deleted_at')
                            ->orWhere(function($qu){
                                $qu->where('secc_if_make_up_created',1)
                                ->where('staff_event_class_clients.deleted_at','!=',null);
                            });
                        })->whereHas('clas.cat', function($q) use($catType){
                            $q->where('clcat_id',$catType);
                        })->count();
                       
                        if($staffEventClassCount < $ldcClass[$catType]['limit']){
                                 $isSatisfy = true;                            
                        }
                    // $eventDate = StaffEventClass::where('sec_id',$eventId)->pluck('sec_date')->first();
                    // $eventDateTime = new Carbon($eventDate);
                    // $weekStartDate = $eventDateTime->startOfWeek()->format('Y-m-d');
                    // $weekEndDate = $eventDateTime->endOfWeek()->format('Y-m-d');
                    // $client = Clients::find($clientId);
                    // if($client->ldc_status == Clients::LDC_ACTIVE){
                    //     $clientMember = ClientMember::where('cm_client_id', $clientId)->where('cm_status', 'Active')->orderBy('id', 'desc')->whereNull('deleted_at')->first();
                    //     if($clientMember){
                    //         if($mode == 'reschedule'){
                    //             $isSatisfy = true;
                    //         }else{
                    //             $ldcClassCount = $client->eventClassesWithoutTrashed()->whereBetween('sec_date',[$weekStartDate,$weekEndDate])->where('staff_event_class_clients.is_ldc',1)->count();
                    //             if($ldcClassCount < Clients::ALLOWED_LDC_CLASS){
                    //                 $isSatisfy = true;
                    //             }
                    //         }
                    //     }
                    // }
                    }else if($ldcClass[$catType]['limit_type'] == 'every_month'){
                        
                            $carbonDate    = Carbon::createFromFormat('Y-m-d', $classData['sec_date']);
                            $monthStartDate = $carbonDate->copy()->startOfMonth();
                            $monthEndDate   = $carbonDate->copy()->endOfMonth();

                            $staffEventClassCount =  StaffEventClass::join('staff_event_class_clients','staff_event_classes.sec_id','=','staff_event_class_clients.secc_sec_id')->orderBy('sec_date','desc')->whereBetween('sec_date',[$monthStartDate,  $monthEndDate])->where('secc_client_id', $clientId)->where('is_ldc',1)->where(function($query){
                                $query->whereNull('staff_event_class_clients.deleted_at')
                                ->orWhere(function($qu){
                                    $qu->where('secc_if_make_up_created',1)
                                    ->where('staff_event_class_clients.deleted_at','!=',null);
                                });
                            })->whereHas('clas.cat', function($q) use($catType){
                                $q->where('clcat_id',$catType);
                            })->count();
                            if($staffEventClassCount < $ldcClass[$catType]['limit']){
                                $isSatisfy = true;                                      
                            }
                        
                    }else if($ldcClass[$catType]['limit_type'] == 'every_fortnight'){
                        $carbonDate    = Carbon::createFromFormat('Y-m-d', $classData['sec_date']);
                        $year =   $carbonDate->copy()->format('Y');
                        $weekDate = $carbonDate->copy();
                        // dd($weekDate);
            
                        $weekNo = $weekDate->weekOfYear;
                        $weekRemainder = $weekNo % 2;
                        if($weekRemainder == 1){
                            $startFortnightWeek = $carbonDate->copy()->startOfWeek();
                            $currentDate = Carbon::now(); 
                            $currentDate->setISODate($year, $weekNo+1); 
                            $endFortnightWeek = $currentDate->endOfWeek();
                        }else{
                            $currentDate = Carbon::now();
                            $currentDate->setISODate($year, $weekNo-1);
                            $startFortnightWeek = $currentDate->startOfWeek();
                            $endFortnightWeek   = $carbonDate->copy()->endOfWeek();
                        }

                       
                        $staffEventClassCount =  StaffEventClass::join('staff_event_class_clients','staff_event_classes.sec_id','=','staff_event_class_clients.secc_sec_id')->orderBy('sec_date','desc')->where('secc_client_id', $clientId)->where('is_ldc',1)->where(function($query){
                                $query->whereNull('staff_event_class_clients.deleted_at')
                                ->orWhere(function($qu){
                                    $qu->where('secc_if_make_up_created',1)
                                    ->where('staff_event_class_clients.deleted_at','!=',null);
                                });
                        
                            })->whereHas('clas.cat', function($q) use($catType){
                                $q->where('clcat_id',$catType);
                            })->count();
                            if($staffEventClassCount < $ldcClass[$catType]['limit']){
                            $isSatisfy = true;                                      
                            }
                        
                        
                    }
                }
            }
        } 
        else if($type == 'service') {
            // check membership for service
            $serviceData = StaffEventSingleService::where('sess_id',$eventId)->select('sess_date','sess_service_id')->first();
            if($serviceData == null){
                $serviceData = $optionalData;
            }
            $service_limits = json_decode($ldcData->ldc_services, 1);
            $serviceId      =  $serviceData['sess_service_id'];
            if($mode == 'reschedule'){
                $isSatisfy = true;
            }else{
                if (count($service_limits) && array_key_exists($serviceId, $service_limits)) {
                    $s_limit      = $service_limits[$serviceId]['limit'];
                    $s_limit_type = $service_limits[$serviceId]['limit_type'];
                    if ($s_limit_type == 'every_week') {
                        $carbonDate    = Carbon::createFromFormat('Y-m-d', $serviceData['sess_date']);
                            $weekStartDate = $carbonDate->copy()->startOfWeek();
                            $weekEndDate   = $carbonDate->copy()->endOfWeek();
                            $staffEventServiceCount =  StaffEventSingleService::orderBy('sess_date','desc')->whereBetween('sess_date',[$weekStartDate,  $weekEndDate])->where('sess_client_id',$clientId)->where('sess_service_id',$serviceId)->where('is_ldc',1)->count();
                            if($staffEventServiceCount < $s_limit ){
                            $isSatisfy = true;                              
                            }
                    } else if($s_limit_type == 'every_month'){
                        $carbonDate    = Carbon::createFromFormat('Y-m-d',$serviceData['sess_date']);
                        $monthStartDate = $carbonDate->copy()->startOfMonth();
                        $monthEndDate   = $carbonDate->copy()->endOfMonth();
                        $staffEventServiceCount =  StaffEventSingleService::orderBy('sess_date','desc')->whereBetween('sess_date',[$monthStartDate,  $monthEndDate])->where('sess_client_id',$clientId)->where('is_ldc',1)->count();
                        if($staffEventServiceCount < $s_limit ){
                            $isSatisfy = true;                              
                        }   
                    }else if($s_limit_type == 'every_fortnight'){
                        $carbonDate    = Carbon::createFromFormat('Y-m-d', $serviceData['sess_date']);
                        $year =   $carbonDate->copy()->format('Y');
                        $weekDate = $carbonDate->copy();
                        // dd($weekDate);
            
                        $weekNo = $weekDate->weekOfYear;
                        $weekRemainder = $weekNo % 2;
                        if($weekRemainder == 1){
                            $startFortnightWeek = $carbonDate->copy()->startOfWeek();
                            $currentDate = Carbon::now(); 
                            $currentDate->setISODate($year, $weekNo+1); 
                            $endFortnightWeek = $currentDate->endOfWeek();
                        }else{
                            $currentDate = Carbon::now();
                            $currentDate->setISODate($year, $weekNo-1);
                            $startFortnightWeek = $currentDate->startOfWeek();
                            $endFortnightWeek   = $carbonDate->copy()->endOfWeek();
                        }
                        $staffEventServiceCount =  StaffEventSingleService::orderBy('sess_date','desc')->whereBetween('sess_date',[$startFortnightWeek,  $endFortnightWeek])->where('sess_client_id',$clientId)->where('is_ldc',1)->count();
                        if($staffEventServiceCount < $s_limit ){
                            $isSatisfy = true;                           
                        }   
                    }
                } 
            }
      }
        return $isSatisfy;
    }


    /**
     * update client membership according to event booked
     * @param Array $clientIds[]
     * @param Array $eventData ['type','action','event_date', 'eventId']
     * @return true/false
     */
    protected function updateClientMembershipLimit($clientId, $dates, $eventData)
    {
        $isError      = true;
        $eventDates   = $this->getEventDates($dates);
        $updatedData  = array();
        $insertedData = array();
        $action       = $eventData['action'];

        if (!array_key_exists('limit_type', $eventData)) {
            $clientMember = ClientMember::where('cm_client_id', $clientId)->select('cm_client_id', 'cm_class_limit_type', 'cm_status', 'cm_services_limit', 'cm_classes')->orderBy('id', 'desc')->first();

            if (count($clientMember)) {
                if ($clientMember['cm_status'] == 'Active') {
                    if ($eventData['type'] == 'class') {
                        $eventInMembership = json_decode($clientMember['cm_classes'], 1);
                        if (count($eventInMembership) /*&& array_key_exists($eventData['eventId'], $eventInMembership)*/) {
                            $limitType = $clientMember['cm_class_limit_type'];
                            $isError   = false;
                        }
                    } elseif ($eventData['type'] == 'service') {
                        $serviceId         = $eventData['eventId'];
                        $eventInMembership = json_decode($clientMember['cm_services_limit'], 1);
                        if (count($eventInMembership) && array_key_exists($serviceId, $eventInMembership) && array_key_exists('limit_type', $eventInMembership[$serviceId])) {
                            $limitType = $eventInMembership[$serviceId]['limit_type'];
                            $isError   = false;
                        }
                    }
                }
            }
        } else {
            $limitType = $eventData['limit_type'];
            $isError   = false;
        }

        if (!$isError) {
            $clientMemberLimit = ClientMemberLimit::where('cme_client_id', $clientId)->first();
            foreach ($eventDates as $eventDate) {
                if ((!count($clientMemberLimit)) && $action == 'add') {
                    $weeks     = array();
                    $months    = array();
                    $fortnight = array();

                    $clientMemberLimit                = new ClientMemberLimit;
                    $clientMemberLimit->cme_client_id = $clientId;

                    if ($eventData['type'] == 'service') {
                        // new record for only service
                        $serviceId = $eventData['eventId']; //service id
                        if ($limitType == 'every_week') {
                            $eventDateCarbon = new Carbon($eventDate);
                            $weekDateData    = $this->getDateDetails($eventDate, 'weekly');
                            $weekNo          = $weekDateData['index'];
                            $eventyear       = $weekDateData['year'];
                            $eventMonth      = $eventDateCarbon->month;
                            $eventyear       = $eventMonth == 12 && $weekNo == 1 ? $weekDateData['year'] + 1 : $weekDateData['year'];

                            $weeks[$serviceId][$eventyear][$weekNo] = 1;
                            $clientMemberLimit->cme_services_weekly = json_encode($weeks);
                        } elseif ($limitType == 'every_month') {
                            $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                            $monthNo       = $monthDateData['index'];
                            $eventyear     = $monthDateData['year'];

                            $months[$serviceId][$eventyear][$monthNo] = 1;
                            $clientMemberLimit->cme_services_monthly  = json_encode($months);
                        } elseif ($limitType == 'every_fortnight') {
                            $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                            $fortnightNo   = $fortnightDate['index'];
                            $eventyear     = $fortnightDate['year'];

                            $fortnight[$serviceId][$eventyear][$fortnightNo] = 1;
                            $clientMemberLimit->cme_services_fortnight       = json_encode($fortnight);
                        }
                    } elseif ($eventData['type'] == 'class') {
                        // new record for only class
                        if ($limitType == 'every_week') {
                            $eventDateCarbon = new Carbon($eventDate);
                            $weekDateData    = $this->getDateDetails($eventDate, 'weekly');
                            $weekNo          = $weekDateData['index'];
                            $eventyear       = $weekDateData['year'];
                            $eventMonth      = $eventDateCarbon->month;
                            $eventyear       = $eventMonth == 12 && $weekNo == 1 ? $weekDateData['year'] + 1 : $weekDateData['year'];

                            $weeks[$eventyear][$weekNo]            = 1;
                            $clientMemberLimit->cme_classes_weekly = json_encode($weeks);
                        } elseif ($limitType == 'every_month') {
                            $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                            $monthNo       = $monthDateData['index'];
                            $eventyear     = $monthDateData['year'];

                            $months[$eventyear][$monthNo]           = 1;
                            $clientMemberLimit->cme_classes_monthly = json_encode($months);
                        }elseif ($limitType == 'every_fortnight') {
                            $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                            $fortnightNo   = $fortnightDate['index'];
                            $eventyear     = $fortnightDate['year'];

                            $fortnight[$eventyear][$fortnightNo] = 1;
                        
                            $clientMemberLimit->cme_classes_fortnight       = json_encode($fortnight);
                        }
                    }

                    if (count($clientMemberLimit)) {
                        $clientMemberLimit->save();
                    }

                } else {
                    $existData = array();
                    $newData   = array();
                    if ($eventData['type'] == 'service') {
                        // for only service
                        $serviceId = $eventData['eventId']; // service id
                        if ($limitType == 'every_week') {
                            $eventDateCarbon = new Carbon($eventDate);
                            $weekDateData    = $this->getDateDetails($eventDate, 'weekly');
                            $weekNo          = $weekDateData['index'];
                            $eventyear       = $weekDateData['year'];
                            $eventMonth      = $eventDateCarbon->month;
                            $eventyear       = $eventMonth == 12 && $weekNo == 1 ? $weekDateData['year'] + 1 : $weekDateData['year'];

                            if (isset($clientMemberLimit->cme_services_weekly) && $clientMemberLimit->cme_services_weekly != '') {
                                $existData = json_decode($clientMemberLimit->cme_services_weekly, 1);
                                if (array_key_exists($serviceId, $existData) && array_key_exists($eventyear, $existData[$serviceId]) && array_key_exists($weekNo, $existData[$serviceId][$eventyear])) {
                                    if ($action == 'add') {
                                        $existData[$serviceId][$eventyear][$weekNo] = $existData[$serviceId][$eventyear][$weekNo] + 1;
                                    } elseif ($existData[$serviceId][$eventyear][$weekNo] > 0) {
                                        $existData[$serviceId][$eventyear][$weekNo] = $existData[$serviceId][$eventyear][$weekNo] - 1;
                                    }

                                } elseif ($action == 'add') {
                                    $existData[$serviceId][$eventyear][$weekNo] = 1;
                                }
                                $clientMemberLimit->cme_services_weekly = json_encode($existData);
                            } elseif ($action == 'add') {
                                $newData[$serviceId][$eventyear][$weekNo] = 1;
                                $clientMemberLimit->cme_services_weekly   = json_encode($newData);
                            }
                        } elseif ($limitType == 'every_month') {
                            $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                            $monthNo       = $monthDateData['index'];
                            $eventyear     = $monthDateData['year'];

                            if (isset($clientMemberLimit->cme_services_monthly) && $clientMemberLimit->cme_services_monthly != '') {
                                $existData = json_decode($clientMemberLimit->cme_services_monthly, 1);
                                if (array_key_exists($serviceId, $existData) && array_key_exists($eventyear, $existData[$serviceId]) && array_key_exists($monthNo, $existData[$serviceId][$eventyear])) {
                                    if ($action == 'add') {
                                        $existData[$serviceId][$eventyear][$monthNo] = $existData[$serviceId][$eventyear][$monthNo] + 1;
                                    } elseif ($existData[$serviceId][$eventyear][$monthNo] > 0) {
                                        $existData[$serviceId][$eventyear][$monthNo] = $existData[$serviceId][$eventyear][$monthNo] - 1;
                                    }

                                } elseif ($action == 'add') {
                                    $existData[$serviceId][$eventyear][$monthNo] = 1;
                                }
                                $clientMemberLimit->cme_services_monthly = json_encode($existData);
                            } elseif ($action == 'add') {
                                $newData[$serviceId][$eventyear][$monthNo] = 1;
                                $clientMemberLimit->cme_services_monthly   = json_encode($newData);
                            }
                        } elseif ($limitType == 'every_fortnight') {
                            $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                            $fortnightNo   = $fortnightDate['index'];
                            $eventyear     = $fortnightDate['year'];

                            if (isset($clientMemberLimit->cme_services_fortnight) && $clientMemberLimit->cme_services_fortnight != '') {
                                $existData = json_decode($clientMemberLimit->cme_services_fortnight, 1);
                                if (array_key_exists($serviceId, $existData) && array_key_exists($eventyear, $existData[$serviceId]) && array_key_exists($fortnightNo, $existData[$serviceId][$eventyear])) {
                                    if ($action == 'add') {
                                        $existData[$serviceId][$eventyear][$fortnightNo] = $existData[$serviceId][$eventyear][$fortnightNo] + 1;
                                    } elseif ($existData[$serviceId][$eventyear][$fortnightNo] > 0) {
                                        $existData[$serviceId][$eventyear][$fortnightNo] = $existData[$serviceId][$eventyear][$fortnightNo] - 1;
                                    }

                                } elseif ($action == 'add') {
                                    $existData[$serviceId][$eventyear][$fortnightNo] = 1;
                                }
                                $clientMemberLimit->cme_services_fortnight = json_encode($existData);
                            } elseif ($action == 'add') {
                                $newData[$serviceId][$eventyear][$fortnightNo] = 1;
                                $clientMemberLimit->cme_services_fortnight     = json_encode($newData);
                            }
                        }
                    } elseif ($eventData['type'] == 'class') {
                        // for only class
                        if ($limitType == 'every_week') {
                            $eventDateCarbon = new Carbon($eventDate);
                            $weekDateData    = $this->getDateDetails($eventDate, 'weekly');
                            $weekNo          = $weekDateData['index'];
                            $eventMonth      = $eventDateCarbon->month;
                            $eventyear       = $eventMonth == 12 && $weekNo == 1 ? $weekDateData['year'] + 1 : $weekDateData['year'];

                            if (isset($clientMemberLimit->cme_classes_weekly) && $clientMemberLimit->cme_classes_weekly != '') {
                                $existData = json_decode($clientMemberLimit->cme_classes_weekly, 1);
                                if (array_key_exists($eventyear, $existData) && array_key_exists($weekNo, $existData[$eventyear])) {
                                    if ($action == 'add') {
                                        $existData[$eventyear][$weekNo] = $existData[$eventyear][$weekNo] + 1;
                                    } elseif ($existData[$eventyear][$weekNo] > 0) {
                                        $existData[$eventyear][$weekNo] = $existData[$eventyear][$weekNo] - 1;
                                    }

                                } elseif ($action == 'add') {
                                    $existData[$eventyear][$weekNo] = 1;
                                }
                                $clientMemberLimit->cme_classes_weekly = json_encode($existData);
                            } elseif ($action == 'add') {
                                $newData[$eventyear][$weekNo]          = 1;
                                $clientMemberLimit->cme_classes_weekly = json_encode($newData);
                            }
                        } elseif ($limitType == 'every_month') {
                            $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                            $monthNo       = $monthDateData['index'];
                            $eventyear     = $monthDateData['year'];

                            if (isset($clientMemberLimit->cme_classes_monthly) && $clientMemberLimit->cme_classes_monthly != '') {
                                $existData = json_decode($clientMemberLimit->cme_classes_monthly, 1);
                                if (array_key_exists($eventyear, $existData) && array_key_exists($monthNo, $existData[$eventyear])) {
                                    if ($action == 'add') {
                                        $existData[$eventyear][$monthNo] = $existData[$eventyear][$monthNo] + 1;
                                    } elseif ($existData[$eventyear][$monthNo] > 0) {
                                        $existData[$eventyear][$monthNo] = $existData[$eventyear][$monthNo] - 1;
                                    }

                                } elseif ($action == 'add') {
                                    $existData[$eventyear][$monthNo] = 1;
                                }
                                $clientMemberLimit->cme_classes_monthly = json_encode($existData);
                            } elseif ($action == 'add') {
                                $newData[$eventyear][$monthNo]          = 1;
                                $clientMemberLimit->cme_classes_monthly = json_encode($newData);
                            }
                        }elseif ($limitType == 'every_fortnight') {
                            $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                            $fortnightNo   = $fortnightDate['index'];
                            $eventyear     = $fortnightDate['year'];

                            if (isset($clientMemberLimit->cme_classes_fortnight) && $clientMemberLimit->cme_classes_fortnight != '') {
                                $existData = json_decode($clientMemberLimit->cme_classes_fortnight, 1);
                                
                                if (array_key_exists($eventyear, $existData) && array_key_exists($fortnightNo, $existData[$eventyear])) {
                                    if ($action == 'add') {
                                        $existData[$eventyear][$fortnightNo] = $existData[$eventyear][$fortnightNo] + 1;
                                    } elseif ($existData[$eventyear][$fortnightNo] > 0) {
                                        $existData[$eventyear][$fortnightNo] = $existData[$eventyear][$fortnightNo] - 1;
                                    }

                                } elseif ($action == 'add') {
                                    $existData[$eventyear][$fortnightNo] = 1;
                                }
                                $clientMemberLimit->cme_classes_fortnight = json_encode($existData);
                            } elseif ($action == 'add') {
                                $newData[$eventyear][$fortnightNo] = 1;
                                $clientMemberLimit->cme_classes_fortnight     = json_encode($newData);
                            }
                        }
                    }
                    if (count($clientMemberLimit)) {
                        $clientMemberLimit->save();
                    }

                }
            }

            // if (count($clientMemberLimit)) {
            //     $clientMemberLimit->save();
            // }

            $clientMemLimit = ClientMemberLimit::where('cme_client_id', $clientId)->first();
            $years          = [2018, 2019, 2020, 2021, 2022, 2023, 2024];
            if ($clientMemLimit) {
                $weeklyClasses_limit = json_decode($clientMemLimit->cme_classes_weekly, 1);
                foreach ($years as $year) {
                    if ($weeklyClasses_limit && !array_key_exists($year, $weeklyClasses_limit)) {
                        $weeklyClasses_limit[$year] = [];
                    }

                }
                // dd($weeklyClasses_limit);
                if ($weeklyClasses_limit) {
                    for ($i = 1; $i <= 53; $i++) {
                        $allWeek[$i] = 0;
                    }

                    foreach ($weeklyClasses_limit as $key => $value1) {
                        $insideArray[$key] = $value1 + $allWeek;
                    }

                    $clientMemLimit->cme_classes_weekly = json_encode($insideArray);
                    $clientMemLimit->save();
                }
            }
        }
    }

    /**
     * update client membership according to event booked
     * @param Array $clientIds[]
     * @param Array $eventData ['type','action','event_date', 'eventId','limit_type']
     * @return true/false
     */
    protected function updateClientMembershipLimitLocaly($clientMemberLimit, $clientId, $eventData)
    {
        $isError = true;
        $eventDate = Carbon::createFromFormat('Y-m-d', $eventData['date']);
        $action    = $eventData['action'];
        $limitType = $eventData['limit_type'];
        // dd($clientMemberLimit);
        if ((!count($clientMemberLimit)) && $action == 'add') {
            $weeks     = array();
            $months    = array();
            $fortnight = array();

            $clientMemberLimit                = new ClientMemberLimit;
            $clientMemberLimit->cme_client_id = $clientId;

            if ($eventData['type'] == 'service') {
                // new record for only service
                $serviceId = $eventData['eventId']; //service id
                if ($limitType == 'every_week') {
                    $weekDateData = $this->getDateDetails($eventDate, 'weekly');
                    $weekNo       = $weekDateData['index'];
                    $eventyear    = $weekDateData['year'];

                    $weeks[$serviceId][$eventyear][$weekNo] = 1;
                    $clientMemberLimit->cme_services_weekly = json_encode($weeks);
                } elseif ($limitType == 'every_month') {
                    $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                    $monthNo       = $monthDateData['index'];
                    $eventyear     = $monthDateData['year'];

                    $months[$serviceId][$eventyear][$monthNo] = 1;
                    $clientMemberLimit->cme_services_monthly  = json_encode($months);
                } elseif ($limitType == 'every_fortnight') {
                    $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                    $fortnightNo   = $fortnightDate['index'];
                    $eventyear     = $fortnightDate['year'];

                    $fortnight[$serviceId][$eventyear][$fortnightNo] = 1;
                    $clientMemberLimit->cme_services_fortnight       = json_encode($fortnight);
                }
            } else if ($eventData['type'] == 'class') {
                // new record for only class
                if ($limitType == 'every_week') {
                    $weekDateData = $this->getDateDetails($eventDate, 'weekly');
                    $weekNo       = $weekDateData['index'];
                    // $eventyear = $weekDateData['year'];

                    $weekDate      = $eventDate->copy();
                    $weekStartDate = $weekDate->copy()->startOfWeek();
                    $weekEndDate   = $weekDate->copy()->endOfWeek();
                    $eventyear     = ($weekStartDate->year < $weekEndDate->year) ? $weekEndDate->year : $weekDateData['year'];

                    $weeks[$eventyear][$weekNo]            = 1;
                    $clientMemberLimit->cme_classes_weekly = json_encode($weeks);
                } elseif ($limitType == 'every_month') {
                    $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                    $monthNo       = $monthDateData['index'];
                    $eventyear     = $monthDateData['year'];

                    $months[$eventyear][$monthNo]           = 1;
                    $clientMemberLimit->cme_classes_monthly = json_encode($months);
                }elseif ($limitType == 'every_fortnight') {
                    $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                    $fortnightNo   = $fortnightDate['index'];
                    $eventyear     = $fortnightDate['year'];
                    
                    $fortnight[$eventyear][$fortnightNo] = 1;
                    $clientMemberLimit->cme_classes_fortnight = json_encode($fortnight);
                }
            }
            //$clientMemberLimit->save();
        } else {
            $existData = array();
            $newData   = array();
            if ($eventData['type'] == 'service') {
                // for only service
                $serviceId = $eventData['eventId']; // service id
                if ($limitType == 'every_week') {
                    $weekDateData = $this->getDateDetails($eventDate, 'weekly');
                    $weekNo       = $weekDateData['index'];
                    $eventyear    = $weekDateData['year'];

                    if ($clientMemberLimit->cme_services_weekly != '') {
                        $existData = json_decode($clientMemberLimit->cme_services_weekly, 1);
                        if (array_key_exists($serviceId, $existData) && array_key_exists($eventyear, $existData[$serviceId]) && array_key_exists($weekNo, $existData[$serviceId][$eventyear])) {
                            if ($action == 'add') {
                                $existData[$serviceId][$eventyear][$weekNo] = $existData[$serviceId][$eventyear][$weekNo] + 1;
                            } elseif ($existData[$serviceId][$eventyear][$weekNo] > 0) {
                                $existData[$serviceId][$eventyear][$weekNo] -= 1;
                            }

                        } elseif ($action == 'add') {
                            $existData[$serviceId][$eventyear][$weekNo] = 1;
                        }
                        $clientMemberLimit->cme_services_weekly = json_encode($existData);
                    } elseif ($action == 'add') {
                        $newData[$serviceId][$eventyear][$weekNo] = 1;
                        $clientMemberLimit->cme_services_weekly   = json_encode($newData);
                    }
                } elseif ($limitType == 'every_month') {
                    $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                    $monthNo       = $monthDateData['index'];
                    $eventyear     = $monthDateData['year'];

                    if ($clientMemberLimit->cme_services_monthly != '') {
                        $existData = json_decode($clientMemberLimit->cme_services_monthly, 1);
                        if (array_key_exists($serviceId, $existData) && array_key_exists($eventyear, $existData[$serviceId]) && array_key_exists($monthNo, $existData[$serviceId][$eventyear])) {
                            if ($action == 'add') {
                                $existData[$serviceId][$eventyear][$monthNo] = $existData[$serviceId][$eventyear][$monthNo] + 1;
                            } elseif ($existData[$serviceId][$eventyear][$monthNo] > 0) {
                                $existData[$serviceId][$eventyear][$monthNo] -= 1;
                            }

                        } elseif ($action == 'add') {
                            $existData[$serviceId][$eventyear][$monthNo] = 1;
                        }
                        $clientMemberLimit->cme_services_monthly = json_encode($existData);
                    } elseif ($action == 'add') {
                        $newData[$serviceId][$eventyear][$monthNo] = 1;
                        $clientMemberLimit->cme_services_monthly   = json_encode($newData);
                    }
                } elseif ($limitType == 'every_fortnight') {
                    $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                    $fortnightNo   = $fortnightDate['index'];
                    $eventyear     = $fortnightDate['year'];

                    if ($clientMemberLimit->cme_services_fortnight != '') {
                        $existData = json_decode($clientMemberLimit->cme_services_fortnight, 1);
                        if (array_key_exists($serviceId, $existData) && array_key_exists($eventyear, $existData[$serviceId]) && array_key_exists($fortnightNo, $existData[$serviceId][$eventyear])) {
                            if ($action == 'add') {
                                $existData[$serviceId][$eventyear][$fortnightNo] = $existData[$serviceId][$eventyear][$fortnightNo] + 1;
                            } elseif ($existData[$serviceId][$eventyear][$fortnightNo] > 0) {
                                $existData[$serviceId][$eventyear][$fortnightNo] -= 1;
                            }

                        } elseif ($action == 'add') {
                            $existData[$serviceId][$eventyear][$fortnightNo] = 1;
                        }
                        $clientMemberLimit->cme_services_fortnight = json_encode($existData);
                    } elseif ($action == 'add') {
                        $newData[$serviceId][$eventyear][$fortnightNo] = 1;
                        $clientMemberLimit->cme_services_fortnight     = json_encode($newData);
                    }
                }
            } elseif ($eventData['type'] == 'class') {
                // for only class
                if ($limitType == 'every_week') {
                    $weekDateData = $this->getDateDetails($eventDate, 'weekly');
                    $weekNo       = $weekDateData['index'];
                    // $eventyear = $weekDateData['year'];
                    $weekDate      = $eventDate->copy();
                    $weekStartDate = $weekDate->copy()->startOfWeek();
                    $weekEndDate   = $weekDate->copy()->endOfWeek();
                    $eventyear     = ($weekStartDate->year < $weekEndDate->year) ? $weekStartDate->year : $weekDateData['year'];

                    if ($clientMemberLimit->cme_classes_weekly != '') {
                        $existData = json_decode($clientMemberLimit->cme_classes_weekly, 1);
                        if (array_key_exists($eventyear, $existData) && array_key_exists($weekNo, $existData[$eventyear])) {
                            if ($action == 'add') {
                                $existData[$eventyear][$weekNo] += 1;
                            } elseif ($existData[$eventyear][$weekNo] > 0) {
                                $existData[$eventyear][$weekNo] -= 1;
                            }

                        } elseif ($action == 'add') {
                            $existData[$eventyear][$weekNo] = 1;
                        }
                        $clientMemberLimit->cme_classes_weekly = json_encode($existData);
                    } elseif ($action == 'add') {
                        $newData[$eventyear][$weekNo]          = 1;
                        $clientMemberLimit->cme_classes_weekly = json_encode($newData);
                    }
                } elseif ($limitType == 'every_month') {
                    $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                    $monthNo       = $monthDateData['index'];
                    $eventyear     = $monthDateData['year'];

                    if ($clientMemberLimit->cme_classes_monthly != '') {
                        $existData = json_decode($clientMemberLimit->cme_classes_monthly, 1);
                        if (array_key_exists($eventyear, $existData) && array_key_exists($monthNo, $existData[$eventyear])) {
                            if ($action == 'add') {
                                $existData[$eventyear][$monthNo] += 1;
                            } elseif ($existData[$eventyear][$monthNo] > 0) {
                                $existData[$eventyear][$monthNo] -= 1;
                            }

                        } elseif ($action == 'add') {
                            $existData[$eventyear][$monthNo] = 1;
                        }
                        $clientMemberLimit->cme_classes_monthly = json_encode($existData);
                    } elseif ($action == 'add') {
                        $newData[$eventyear][$monthNo]          = 1;
                        $clientMemberLimit->cme_classes_monthly = json_encode($newData);
                    }
                }elseif ($limitType == 'every_fortnight') {
                    $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                    $fortnightNo   = $fortnightDate['index'];
                    $eventyear     = $fortnightDate['year'];

                    if ($clientMemberLimit->cme_classes_fortnight != '') {
                        $existData = json_decode($clientMemberLimit->cme_classes_fortnight, 1);
                        if (array_key_exists($eventyear, $existData) && array_key_exists($fortnightNo, $existData[$eventyear])) {
                            if ($action == 'add') {
                                $existData[$eventyear][$fortnightNo] = $existData[$eventyear][$fortnightNo] + 1;
                            } elseif ($existData[$eventyear][$fortnightNo] > 0) {
                                $existData[$eventyear][$fortnightNo] -= 1;
                            }

                        } elseif ($action == 'add') {
                            $existData[$eventyear][$fortnightNo] = 1;
                        }
                        $clientMemberLimit->cme_classes_fortnight = json_encode($existData);
                    } elseif ($action == 'add') {
                        $newData[$eventyear][$fortnightNo] = 1;
                        $clientMemberLimit->cme_classes_fortnight     = json_encode($newData);
                    }
                }
            }
        }

        if (count($clientMemberLimit)) {
            $clientMemberLimit->save();
        }

        $clientMemLimit = ClientMemberLimit::where('cme_client_id', $clientId)->first();
        $years          = [2018, 2019, 2020, 2021, 2022, 2023, 2024];
        if ($clientMemLimit) {
            $weeklyClasses_limit = json_decode($clientMemLimit->cme_classes_weekly, 1);
            foreach ($years as $year) {
                if ($weeklyClasses_limit && !array_key_exists($year, $weeklyClasses_limit)) {
                    $weeklyClasses_limit[$year] = [];
                }

            }
            // dd($weeklyClasses_limit);
            if ($weeklyClasses_limit) {
                for ($i = 1; $i <= 53; $i++) {
                    $allWeek[$i] = 0;
                }

                foreach ($weeklyClasses_limit as $key => $value1) {
                    $insideArray[$key] = $value1 + $allWeek;
                }

                $clientMemLimit->cme_classes_weekly = json_encode($insideArray);
                $clientMemLimit->save();
            }
        }

        return $clientMemberLimit;
    }

    /**
     * Event updated dates
     *
     * @param Array $dates
     * @return Array $eventDates
     */
    protected function getEventDates($dates)
    {
        $eventDates = array();
        foreach ($dates as $date) {
            if (gettype($date) == 'string') {
                $eventDates[] = Carbon::createFromFormat('Y-m-d', $date);
            } else {
                $eventDates[] = $date;
            }

        }

        return $eventDates;
    }

    /**
     * Date for indexing
     *
     * @param Carbon Date $dates
     * @param String $limitType
     * @return Array $dateDetails['year','month/week/fortnightly']
     */
    protected function getDateDetails($date, $limitType)
    {
        $carbonDate          = $date->copy();
        $dateDetails         = array();
        $dateDetails['year'] = $carbonDate->year;
        if ($limitType == 'weekly') {
            $weekDate             = $carbonDate->copy();
            $dateDetails['index'] = $weekDate->weekOfYear;
        } elseif ($limitType == 'monthly') {
            $monthDate            = $carbonDate->copy();
            $dateDetails['index'] = $monthDate->month;
        } elseif ($limitType == 'fortnightly') {
            $fortnightDate    = $carbonDate->copy();
            $fortnightMonthNo = $fortnightDate->weekOfYear;
            if ($fortnightMonthNo % 2 == 0) {
                $dateDetails['index'] = $fortnightMonthNo;
            } else {
                $dateDetails['index'] = $fortnightMonthNo + 1;
            }

        }
        return $dateDetails;
    }

    /**
     * Get monthno_weekno for fortnight option
     * @param Date
     * @return string monthno_weekno
     */
    protected function getEvenWeekNumb($date)
    {
        $fortnightDate    = $date->copy();
        $fortnightMonthNo = $fortnightDate->weekOfYear;
        if ($fortnightMonthNo % 2 == 0) {
            return $fortnightMonthNo;
        } else {
            return $fortnightMonthNo + 1;
        }

    }

    /**
     * Reset all memebrship of given client
     *
     * @param Int ClientId
     * @return void
     */
    protected function membershipLimitReset($clientId)
    {
        $client = Clients::where('id', $clientId)->first();
        if (count($client)) {
            $updatedLimit = collect();
            $existLimit   = ClientMemberLimit::where('cme_client_id', $clientId)->first();

            if (count($existLimit)) {
                ClientMemberLimit::where('cme_client_id', $clientId)->forcedelete();
                # Set info log
                setInfoLog('Client future membership limit reset to empty', $clientId);
            }

            $clientMember = Clients::paidMembership($clientId);
            if ($clientMember && $clientMember->cm_services_limit != '') {
                $serviceEvent = StaffEventSingleService::withTrashed()->where('sess_client_id', $clientId)->get();

                if (count($serviceEvent)) {
                    foreach ($serviceEvent as $service) {
                        $serviceLimit = json_decode($clientMember->cm_services_limit, 1);
                        // if($service->sess_with_invoice == 0 && array_key_exists($service->sess_service_id, $serviceLimit)){
                        if ($service->deleted_at != null && $service->sess_cmid != 0 && ($service->sess_if_make_up == 1 || ($service->sess_event_log && strripos($service->sess_event_log, 'epic credit'))) && array_key_exists($service->sess_service_id, $serviceLimit)) {
                            $limit_type   = $serviceLimit[$service->sess_service_id]['limit_type'];
                            $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'service', 'action' => 'add', 'date' => $service->sess_date, 'eventId' => $service->sess_service_id, 'limit_type' => $limit_type]);

                        } else if ($service->deleted_at == null && $service->sess_cmid != 0 && array_key_exists($service->sess_service_id, $serviceLimit)) {
                            $limit_type   = $serviceLimit[$service->sess_service_id]['limit_type'];
                            $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'service', 'action' => 'add', 'date' => $service->sess_date, 'eventId' => $service->sess_service_id, 'limit_type' => $limit_type]);
                        }
                    }
                }
            }

            if ($clientMember && $clientMember->cm_classes != '') {
                $classEvent = $client->eventClasses()->get();

                if (count($classEvent)) {
                    foreach ($classEvent as $cls) {
                        $classLimit = json_decode($clientMember->cm_classes, 1);
                        if ($cls->pivot->deleted_at != null && $cls->pivot->secc_cmid != 0 && ($cls->pivot->secc_if_make_up_created == 1 || ($cls->pivot->secc_event_log && strripos($cls->pivot->secc_event_log, 'epic credit'))) && array_key_exists($cls->sec_class_id, $classLimit)) {
                            $limit_type   = $clientMember->cm_class_limit_type;
                            $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'class', 'action' => 'add', 'date' => $cls->sec_date, 'eventId' => $cls->sec_class_id, 'limit_type' => $limit_type]);

                        } else if ($cls->pivot->deleted_at == null && $cls->pivot->secc_cmid != 0 && array_key_exists($cls->sec_class_id, $classLimit)) {
                            $limit_type   = $clientMember->cm_class_limit_type;
                            $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'class', 'action' => 'add', 'date' => $cls->sec_date, 'eventId' => $cls->sec_class_id, 'limit_type' => $limit_type]);
                        }
                    }
                }
            }
            if (count($updatedLimit)) {
                $updatedLimit->save();
            }

            # Set info log
            setInfoLog('Client membership limit updated', $clientId);

            $clientMemLimit = ClientMemberLimit::where('cme_client_id', $clientId)->first();
            $years          = [2018, 2019, 2020, 2021, 2022, 2023, 2024];
            if ($clientMemLimit) {
                $weeklyClasses_limit = json_decode($clientMemLimit->cme_classes_weekly, 1);
                foreach ($years as $year) {
                    if ($weeklyClasses_limit && !array_key_exists($year, $weeklyClasses_limit)) {
                        $weeklyClasses_limit[$year] = [];
                    }

                }
                // dd($weeklyClasses_limit);
                if ($weeklyClasses_limit) {
                    for ($i = 1; $i <= 53; $i++) {
                        $allWeek[$i] = 0;
                    }

                    foreach ($weeklyClasses_limit as $key => $value1) {
                        $insideArray[$key] = $value1 + $allWeek;
                    }

                    $clientMemLimit->cme_classes_weekly = json_encode($insideArray);
                    $clientMemLimit->save();
                }
            }
        }
    }

    protected function countBookedSessions($data)
    {
        $bookedSessions = StaffEventSingleService::OfBusiness()
            ->where('sess_date', '>=', $data['membershipStartDate'])
            ->where('sess_date', '<=', $data['membershipStartDate'])
            ->where('sess_cmid', $data['clientMembId'])
            ->where('sess_service_id', $data['eventId'])
            ->where('sess_client_id', $data['clientId'])
            ->count();
        return $bookedSessions;
    }

    protected function countBookedClasses($data)
    {
        if (array_key_exists('cm_class_limit_type', $data)) {
            if ($data['cm_class_limit_type'] == 'every_week') {
                //If limit is weekly
                $startDate = $data['eventDate']->copy();
                $startDate->startOfWeek(); //Get start date of the event's date's week

                $endDate = $data['eventDate']->copy();
                $endDate->endOfWeek(); //Get end date of the event's date's week
            } else if ($data['cm_class_limit_type'] == 'every_month') {
                //If limit is monthly
                $startDate = $data['eventDate']->copy();
                $startDate->startOfMonth(); //Get start date of the event's date's month

                $endDate = $data['eventDate']->copy();
                $endDate->endOfMonth(); //Get end date of the event's date's month
            }

            if ($startDate->lt($data['membershipStartDate'])) //If start date is prior than membership start date
            {
                $startDate = $data['membershipStartDate'];
            }
            //Set start date to membership start date
            $startDate = $startDate->toDateString();

            if ($endDate->gt($data['membershipendDate'])) //If end date is after than membership end date
            {
                $endDate = $data['membershipendDate'];
            }
            //Set end date to membership end date
            $endDate = $endDate->toDateString();
        } else {
            $startDate = $data['membershipStartDate']->toDateString();
            $endDate   = $data['membershipendDate']->toDateString();
        }

        //DB::enableQueryLog();
        //Counting classes booked for the given client in the calculate date range
        $bookedClasses = StaffEventClass::whereHas('clients', function ($query) use ($data) {
            $query->where('secc_client_id', $data['clientId'])
            //->where('secc_if_make_up_created', 0)
                ->where(function ($query) use ($data) {
                    $query->where('secc_cmid', $data['clientMembId'])
                        ->orWhere('secc_with_invoice', 0);
                });

            /*->where(function($query){
        $query->where('secc_if_make_up', 0)
        ->orWhere('secc_client_attendance', 'Did not show');
        })*/
        })
            ->OfBusiness()
            ->where('sec_date', '>=', $startDate)
            ->where('sec_date', '<=', $endDate)
            ->where('sec_id', '!=', $data['eventId'])
        /*->where(function($query){
        $query->where('secc_client_status', 'Confirm')
        ->orWhere(function($query){
        $query->where('sec_start_datetime', '>', (new Carbon())->toDateTimeString());
        });

        })*/
            ->count();
        //dd(DB::getQueryLog());

        return $bookedClasses;
    }

    protected function getBookedServicesInMebership($data)
    {

        $startDate = $data['membershipStartDate']->toDateString();
        $endDate   = $data['membershipendDate']->toDateString();

        //Counting classes booked for the given client in the calculate date range
        $bookedServices = StaffEventSingleService::OfBusiness()
            ->where('sess_client_id', $data['clientId'])
            ->where('sess_cmid', $data['clientMembId'])
            ->where('sess_date', '>=', $startDate)
            ->where('sess_date', '<=', $endDate)
            ->whereIn('sess_id', array($data['eventIds']))
            ->where('sess_client_attendance', '!=', 'Did not show')
            ->groupBy('sess_service_id');

        return $bookedServices;
    }

    protected function linkResources($request)
    {
        $formData = $request->all();
        //dd($request->eventId);
        ksort($formData);
        $newResources = $newItem = [];
        foreach ($formData as $key => $value) {
            if (strpos($key, 'newResources') !== false) {
                $newResources[] = $value;
            } else if (strpos($key, 'newItem') !== false) {
                $newItem[] = $value;
            }

        }

        if ($request->kase == "edit") {
            StaffEventResource::where('serc_event_id', $request->eventId)->where('serc_event_type', $request->eventType)->forcedelete();
        }

        if (count($newResources) && count($newItem)) {
            $records = [];
            for ($i = 0; $i < count($newItem); $i++) {
                $timestamp = createTimestamp();
                $records[] = ['serc_event_id' => $request->eventId, 'serc_event_type' => $request->eventType, 'serc_res_id' => $newResources[$i], 'serc_item_quantity' => $newItem[$i], 'created_at' => $timestamp, 'updated_at' => $timestamp];
            }
            if (count($records)) {
                StaffEventResource::insert($records);
            }
        }
    }

    protected function deleteStaffEventSingleServices()
    {
        StaffEventSingleService::OfBusiness()->where('sess_client_check', '<>', 1)->where('sess_client_id', 0)->delete();
    }

    protected function unsetEventReccurence($event, $bookingType)
    {
        if ($bookingType == 'service') {
            $event->sess_is_repeating = 0;
            if ($event->sess_parent_id) {
                $event->sess_parent_id = 0;
            }

        } else if ($bookingType == 'class') {
            $event->sec_is_repeating = 0;
            if ($event->sec_parent_id) {
                $event->sec_parent_id = 0;
            }

        } else if ($bookingType == 'dashboard') {
            $event->is_repeating = 0;
            if ($event->task_parent_id) {
                $event->task_parent_id = 0;
            }

        }
        $event->save();
    }

    protected function getBookingsFromChain($eventParentId, $eventId = 0, $bookingType)
    {
        if ($bookingType == 'service') {
            if (!$eventId) {
                $relatedEvents = StaffEventSingleService::where('sess_parent_id', $eventParentId)->orWhere('sess_id', $eventParentId)->orderBy('sess_date', 'DESC')->get();
            } else {
                $relatedEvents = StaffEventSingleService::where(function ($query) use ($eventParentId) {
                    $query->where('sess_parent_id', $eventParentId)->orWhere('sess_id', $eventParentId);
                })
                    ->where('sess_id', '!=', $eventId)
                    ->orderBy('sess_date', 'DESC')
                    ->get();
            }
        } else if ($bookingType == 'class') {
            if (!$eventId) {
                $relatedEvents = StaffEventClass::where('sec_parent_id', $eventParentId)->orWhere('sec_id', $eventParentId)->orderBy('sec_date', 'DESC')->get();
            } else {
                $relatedEvents = StaffEventClass::where(function ($query) use ($eventParentId) {
                    $query->where('sec_parent_id', $eventParentId)->orWhere('sec_id', $eventParentId);
                })
                    ->where('sec_id', '!=', $eventId)
                    ->orderBy('sec_date', 'DESC')->get();
            }
        }
        return $relatedEvents;
    }

    protected function saveClassSkips($data)
    {
        $data['eventType'] = 'App\StaffEventClass';
        $this->saveEventSkips($data);
    }

    protected function saveServiceSkips($data)
    {
        $data['eventType'] = 'App\StaffEventSingleService';
        $this->saveEventSkips($data);
    }

    protected function updateThisCaseForClass($parentId, $eventId, $data)
    {
        if ($parentId) {
            //If booking has parent
            //Decrease end after of all the events in the chain
            /* $parentRepeat = StaffEventRepeat::ofClass()->where('ser_event_id', $parentId)->first();
            $this->decreRepeat($parentRepeat);*/
            $parentRepeat = StaffEventRepeat::ofClass()->where('ser_event_id', $parentId)->first();
            $parentRepeat->ser_child_count--;
            $parentRepeat->save();

            if ($parentRepeat->ser_repeat_end == 'After') {
                $relatedEvents = $this->getBookingsFromChain($parentId, $eventId, 'class');
                if ($relatedEvents->count()) {
                    $eventIds    = $relatedEvents->pluck('sec_id')->toArray();
                    $repeatTable = (new StaffEventRepeat)->getTable();
                    DB::table($repeatTable)->where('ser_event_type', 'App\StaffEventClass')->whereIn('ser_event_id', $eventIds)->update(['ser_repeat_end_after_occur' => --$parentRepeat->ser_repeat_end_after_occur]);
                }
            }

            $this->saveClassSkips(['parentId' => $parentId, 'startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']]);
        } else {
            //If booking is itself parent
            $associatedEvents = [];
            if (array_key_exists('storeChilds', $data) && $data['storeChilds'] == 'true') {
                $associatedEvents = StaffEventClass::ChildEvents($eventId)->orderBy('sec_date')->get();
            } else {
                $oldParId = getOldParId($eventId, 'class');
                if ($oldParId && $oldParId == -1) {
                    $childIds = getChilds($eventId, 'class');
                    if (count($childIds)) {
                        $associatedEvents = StaffEventClass::orderBy('sec_date')->find($childIds);
                    }

                } else {
                    $associatedEvents = StaffEventClass::ChildEvents($eventId)->orderBy('sec_date')->get();
                }
            }

            if (count($associatedEvents)) {
                //if($associatedEvents->count()){
                $nextAssociatedEvent                = $associatedEvents->first();
                $nextAssociatedEvent->sec_parent_id = 0;
                $nextAssociatedEvent->save();

                StaffEventClass::ChildEvents($eventId)->update(['sec_parent_id' => $nextAssociatedEvent->sec_id]);

                $nextRepeat                  = $nextAssociatedEvent->repeat->first();
                $nextRepeat->ser_child_count = $associatedEvents->count() - 1;
                $nextRepeat->save();

                $childIds = $associatedEvents->pluck('sec_id')->toArray();
                if ($nextRepeat->ser_repeat_end == 'After') {
                    $repeatTable = (new StaffEventRepeat)->getTable();
                    DB::table($repeatTable)->where('ser_event_type', 'App\StaffEventClass')->whereIn('ser_event_id', $childIds)->update(['ser_repeat_end_after_occur' => --$nextRepeat->ser_repeat_end_after_occur]);
                }

                StaffEventSkip::ofClass()->where('sk_parent_id', $eventId)->update(['sk_parent_id' => $nextAssociatedEvent->sec_id]);
            } else {
                $childIds = [];
            }

            if (array_key_exists('storeChilds', $data) && $data['storeChilds'] == 'true') {
                if (Session::has('childClass')) {
                    $sessionVal = Session::get('childClass');
                } else {
                    $sessionVal = [];
                }

                $sessionVal['class-' . $eventId] = $childIds;
                Session::put('childClass', $sessionVal);
            }
        }
    }

    protected function unlinkServiceFromReccurenceChain($event, $data)
    {
        if (array_key_exists('parentId', $data)) {
            $eventParentId = $data['parentId'];
        } else {
            $eventParentId = $event->sess_parent_id;
        }

        if ((array_key_exists('breakFromChain', $data) && $data['breakFromChain']) || !array_key_exists('breakFromChain', $data)) {
            $this->resetEventReccur($event, 'service');
        }

        if (!array_key_exists('storeChilds', $data)) {
            $data['storeChilds'] = 'false';
        }

        $this->updateThisCaseForService($eventParentId, $event->sess_id, ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime'], 'storeChilds' => $data['storeChilds']]);
        /*if($eventParentId){
    //StaffEventRepeat::ofSingleService()->where('ser_event_id', $eventParentId)->decrement('ser_child_count');
    $parentRepeat = StaffEventRepeat::ofSingleService()->where('ser_event_id', $eventParentId)->first();
    $this->decreRepeat($parentRepeat);

    $this->saveServiceSkips(['parentId'=>$eventParentId, 'startDatetime'=>$data['startDatetime'], 'endDatetime'=>$data['endDatetime']]);
    }
    else
    $this->changeParent($event->sess_id);*/
    }

    protected function updateThisCaseForService($parentId, $eventId, $data)
    {
        if ($parentId) {
            /*$parentRepeat = StaffEventRepeat::ofSingleService()->where('ser_event_id', $parentId)->first();
            $this->decreRepeat($parentRepeat);*/
            $parentRepeat = StaffEventRepeat::ofSingleService()->where('ser_event_id', $parentId)->first();
            $parentRepeat->ser_child_count--;
            $parentRepeat->save();

            if ($parentRepeat->ser_repeat_end == 'After') {
                $relatedEvents = $this->getBookingsFromChain($parentId, $eventId, 'service');
                if ($relatedEvents->count()) {
                    $eventIds    = $relatedEvents->pluck('sess_id')->toArray();
                    $repeatTable = (new StaffEventRepeat)->getTable();
                    DB::table($repeatTable)->where('ser_event_type', 'App\StaffEventSingleService')->whereIn('ser_event_id', $eventIds)->update(['ser_repeat_end_after_occur' => --$parentRepeat->ser_repeat_end_after_occur]);
                }
            }

            $this->saveServiceSkips(['parentId' => $parentId/*, 'date'=>$eventClass->sec_date*/, 'startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']]);
        } else {
            $associatedEvents = [];
            if (array_key_exists('storeChilds', $data) && $data['storeChilds'] == 'true') {
                $associatedEvents = StaffEventSingleService::ChildEvents($eventId)->orderBy('sess_date')->get();
            } else {
                $oldParId = getOldParId($eventId, 'service');
                if ($oldParId && $oldParId == -1) {
                    $childIds = getChilds($eventId, 'service');
                    if (count($childIds)) {
                        $associatedEvents = StaffEventSingleService::orderBy('sess_date')->find($childIds);
                    }

                } else {
                    $associatedEvents = StaffEventSingleService::ChildEvents($eventId)->orderBy('sess_date')->get();
                }
            }

            //if($associatedEvents->count()){
            if (count($associatedEvents)) {
                $nextAssociatedEvent                 = $associatedEvents->first();
                $nextAssociatedEvent->sess_parent_id = 0;
                $nextAssociatedEvent->save();

                StaffEventSingleService::ChildEvents($eventId)->update(['sess_parent_id' => $nextAssociatedEvent->sess_id]);

                $nextRepeat                  = $nextAssociatedEvent->repeat->first();
                $nextRepeat->ser_child_count = $associatedEvents->count() - 1;
                $nextRepeat->save();

                $childIds = $associatedEvents->pluck('sess_id')->toArray();
                if ($nextRepeat->ser_repeat_end == 'After') {
                    $repeatTable = (new StaffEventRepeat)->getTable();
                    DB::table($repeatTable)->where('ser_event_type', 'App\StaffEventSingleService')->whereIn('ser_event_id', $childIds)->update(['ser_repeat_end_after_occur' => --$nextRepeat->ser_repeat_end_after_occur]);
                }

                StaffEventSkip::ofSingleService()->where('sk_parent_id', $eventId)->update(['sk_parent_id' => $nextAssociatedEvent->sess_id]);
            } else {
                $childIds = [];
            }

            if (array_key_exists('storeChilds', $data) && $data['storeChilds'] == 'true') {
                if (Session::has('childService')) {
                    $sessionVal = Session::get('childService');
                } else {
                    $sessionVal = [];
                }

                $sessionVal['service-' . $eventId] = $childIds;
                Session::put('childService', $sessionVal);
            }

            /*$nextAssociatedEvent = StaffEventSingleService::ChildEvents($eventId)->orderBy('sess_date')->first();
        $nextAssociatedEvent->sess_parent_id = 0;
        $nextAssociatedEvent->save();

        $associatedEventCount = StaffEventSingleService::ChildEvents($eventId)->update(['sess_parent_id' => $nextAssociatedEvent->sess_id]);

        $nextRepeat = $nextAssociatedEvent->repeat->first();
        $this->decreRepeat($nextRepeat, $associatedEventCount);

        StaffEventSkip::ofSingleService()->where('sk_parent_id', $eventId)->update(['sk_parent_id'=>$nextAssociatedEvent->sess_id]);*/
        }
    }

    /**
     * Check if given booking is part of indiv in sales process
     *
     * @param int $id Booking record id
     * @param string $date Booking date
     * @param Collection $client Client record whose sales process has to check
     *
     */
    protected function isBookingIndivBooking($id, $date, $client)
    {
        //return false;
        $isSalesProcEvent = false;

        if (!$client->trashed() && isClientInSalesProcess($client->consultation_date) && $client->IndivEnabledCount) {
            //Client not deleted and Client is still in sales process
            $eventDate        = new Carbon($date);
            $consultationDate = new Carbon($client->consultation_date);
            if ($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))) {
                //Event booking date is not less than Consultation date and not greater than Consultation end date(Consultation date + 15 days)
                $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('book_indiv');
                if ($this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)) {
                    //Sales process has been book benchmark already
                    $totalBookings = StaffEventSingleService::indivBookings($client->id, $client->consultation_date/*, 0*/, 'sess_id');
                    if (count($totalBookings)) {
                        $totalBookings = array_slice($totalBookings, 0, $client->IndivEnabledCount);
                        $idx           = array_search($id, $totalBookings);
                        if ($idx !== false) {
                            $indivBookingSteps = indivBookingSteps();
                            $isSalesProcEvent  = $indivBookingSteps[$idx];
                        }
                        /*if(in_array($id, $totalBookings))
                    $isSalesProcEvent = true;*/
                    }
                }
            }
        }

        return $isSalesProcEvent;
    }

    /**
     * Calculate staff payment per event
     * @param booking type(class/service) Event id
     * @return total payment
     **/
    protected function calcStaffEventPayment($type, $eventId, $attendence = '')
    {
        $total = 0;
        // Calculate staff payment for service
        if ($type == 'service') {
            $event = StaffEventSingleService::with('staff')->find($eventId);
            if (count($event) && count($event->staff)) {
                if (($attendence == 'Attended') || ($attendence == 'Did not show' && $event->staff->per_session_pay_for == '1')) {
                    if ($event->staff->per_session_base_rate != null) {
                        $total += $event->staff->per_session_base_rate;
                    }

                } else {
                    $total = 0;
                }
                //save total in sess_payment
                $event->update(['sess_payment' => $total]);
            }
        }
        // Calculate staff payment for service
        elseif ($type == 'class') {
            $event = StaffEventClass::with('staff')->find($eventId);
            if (count($event) && count($event->staff)) {

                if ($event->staff->per_session_pay_for == '1') {
                    $noOfClient = $event->clients->count();
                } else {
                    $noOfClient = $event->attendedClient->count();
                }

                //Calculate payment according to Per Session Rate Options
                if ($event->staff->per_session_rate_options == 'base_rate') {
                    if ($event->staff->per_session_base_rate != null) {
                        if ($noOfClient) {
                            $total += $event->staff->per_session_base_rate * $noOfClient;
                        }

                    }
                } elseif ($event->staff->per_session_rate_options == 'base_rate_attendees') {
                    $total_attendees_payment = 0;
                    $attendees               = $event->staff->attendees()->get();
                    if ($attendees->count()) {
                        foreach ($attendees as $value) {
                            if ($noOfClient >= $value->sa_per_session_attendees && $noOfClient <= $value->sa_per_session_attendeeto) {
                                $total_attendees_payment += $noOfClient * $value->sa_per_session_price;
                            } elseif ($event->staff->per_session_base_rate != null) {
                                $total_attendees_payment += $noOfClient * $event->staff->per_session_base_rate;
                            }

                        }
                    }
                    $total += $total_attendees_payment;
                } else {
                    $total_attendees_payment = 0;
                    $attendees               = $event->staff->attendees()->get();
                    if ($attendees->count()) {
                        foreach ($attendees as $value) {
                            if ($noOfClient >= $value->per_session_tier && $noOfClient <= $value->per_session_tierto) {
                                $total_attendees_payment += $value->per_session_tierprice;
                            } elseif ($event->staff->per_session_base_rate != null) {
                                $total_attendees_payment += $noOfClient * $event->staff->per_session_base_rate;
                            }

                        }
                    }
                    $total += $total_attendees_payment;
                }
                //Save total in sec_payment
                $event->update(['sec_payment' => $total]);
            }
        }
        return $total;
    }

    /**
     * Calculate staff payment with date range
     * @param Staff id, startdate, enddate
     * @return total payment
     **/
    protected function calcStaffPaymentHourly($staffId, $firstDate = '', $lastDate = '')
    {
        $total       = 0.0;
        $total_hours = 0;
        $staff       = Staff::find($staffId);
        if (count($staff)) {
            $query = \App\StaffAttendence::select('id', 'sa_start_time', 'sa_end_time', 'edited_start_time', 'edited_end_time', 'sa_status')->where('sa_staff_id', $staffId)->where('sa_status', '<>', 'unattended');

            if ($firstDate && $lastDate) {
                $start = dateStringToDbDate($firstDate)->toDateString();
                $end   = dateStringToDbDate($lastDate)->toDateString();
                $query->where('sa_date', '>=', $start)->where('sa_date', '<=', $end);
            }
            if ($lastDate) {
                $end = dateStringToDbDate($lastDate)->toDateString();
                $query->where('sa_date', '<=', $end);
            } else {
                $date = Carbon::now()->toDateString();
                $query->where('sa_date', '<=', $date);
            }
            $staff_attendence = $query->get();
            if ($staff_attendence->count()) {
                foreach ($staff_attendence as $attendence) {
                    if ($attendence->edited_start_time != null && $attendence->edited_end_time != null) {
                        $total_hours += ((strtotime($attendence->edited_end_time) - strtotime($attendence->edited_start_time)) / 3600);
                    } else {
                        $total_hours += ((strtotime($attendence->sa_end_time) - strtotime($attendence->sa_start_time)) / 3600);
                    }

                }
            }

            if ($staff->hourly_rate != null && $staff->hourly_rate && $total_hours) {
                $total = $total_hours * $staff->hourly_rate;
            }

        }
        return $total;
    }

    protected function generateEventRepeat($dates, $event, $repeat, $data)
    {

        $recreate = $data['recreate'];
        $oldDate  = $data['oldDate'];

        //$oldAssociatedEventsId = $oldAssociatedEventsRepeatId = [];
        if ($recreate) {
            if (!$event->task_parent_id) {
                $oldAssociatedEvents = Task::with('repeat')->ChildEvents($event->id)->orderBy('task_due_date')->get();
            } else {
                $oldAssociatedEvents = Task::with('repeat')->SiblingEvents(['parentEventId' => $event->task_parent_id, 'eventDate' => $oldDate, 'eventId' => $event->id])->orderBy('task_due_date')->get();
            }

        }
        if (count($dates)) {
            $event->is_repeating = 1;
            $event->save();
            array_splice($dates, 0, 1);

            if (count($dates)) {
                ///$event->load('areas');
                /* $repeatingEventAreaData = */$repeatingEventRepeatData = $logData = [];
                $index                                                   = 0;

                $prevDateInLoop = '';
                foreach ($dates as $date) {
                    if (!$prevDateInLoop || $date != $prevDateInLoop) {
                        $prevDateInLoop = $date;

                        $newEvent                = $event->replicate();
                        $newEvent->task_due_date = $date;
                        //$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $event->sess_time, 'startDate' => $date, 'duration' => $event->sess_duration]);
                        //$newEvent->sess_start_datetime = $startAndEndDatetime['startDatetime'];
                        //$newEvent->sess_end_datetime = $startAndEndDatetime['endDatetime'];
                        $newEvent->task_parent_id = $event->id;

                        if ($recreate && count($oldAssociatedEvents) > $index) {
                            $oldAssociatedEvent = $oldAssociatedEvents[$index];

                            $oldAssociatedEvent->forceDelete();

                            $newEvent->id          = $oldAssociatedEvent->id;
                            $newEvent->task_status = $oldAssociatedEvent->task_status;
                            //$newEvent->sess_client_attendance = $oldAssociatedEvent->sess_client_attendance;
                            //$newEvent->sess_booking_status = $oldAssociatedEvent->sess_booking_status;
                            //$newEvent->sess_auto_expire = $oldAssociatedEvent->sess_auto_expire;
                            //$newEvent->sess_auto_expire_datetime = $oldAssociatedEvent->sess_auto_expire_datetime;
                            $newEvent->created_at = $oldAssociatedEvent->created_at;
                        }
                        $newEvent->save();

                        /*if($recreate)
                        $logtext = 'Update case.';
                        else
                        $logtext = 'Create case.';
                        $this->log(['entity'=>'Task', 'sourceFile'=>'NewDashboardController.php', 'sourceFunc'=>'generateEventRepeat', 'action'=>'Generating tasks repeat', 'text'=>$logtext.' Source task Id = '.$event->id]);*/

                        ///foreach($event->areas as $area)
                        /// $repeatingEventAreaData[] = ['sessa_business_id' => $area->pivot->sessa_business_id, 'sessa_sess_id' => $newEvent->sess_id, 'sessa_la_id' => $area->pivot->sessa_la_id];

                        $timestamp                  = createTimestamp();
                        $repeatingEventRepeatData[] = ['ser_event_id' => $newEvent->id, 'ser_event_type' => $repeat->ser_event_type, 'ser_repeat' => $repeat->ser_repeat, 'ser_repeat_interval' => $repeat->ser_repeat_interval, 'ser_repeat_end' => $repeat->ser_repeat_end, 'ser_repeat_end_after_occur' => $repeat->ser_repeat_end_after_occur, 'ser_repeat_end_on_date' => $repeat->ser_repeat_end_on_date, 'ser_repeat_week_days' => $repeat->ser_repeat_week_days, 'created_at' => $timestamp, 'updated_at' => $timestamp];

                        if ($recreate) {
                            $logtext = 'Update case.';
                        } else {
                            $logtext = 'Create case.';
                        }

                        $timestamp = createTimestamp();
                        $logData[] = ['entity' => 'Task', 'sourceFile' => 'NewDashboardController.php', 'sourceFunc' => 'generateEventRepeat', 'action' => 'Generating tasks repeat', 'text' => $logtext . '|| Source task = ' . $event . '|| Child task = ' . $newEvent, 'created_at' => $timestamp, 'updated_at' => $timestamp];

                        $index++;
                    }
                }

                ///if(count($repeatingEventAreaData))
                ///  DB::table('staff_event_single_service_areas')->insert($repeatingEventAreaData);

                if (count($repeatingEventRepeatData)) {
                    DB::table('staff_event_repeats')->insert($repeatingEventRepeatData);
                }

                $this->log($logData, true);

                $repeat->ser_child_count = count($dates);
                $repeat->save();

                if ($recreate && count($oldAssociatedEvents) > count($dates)) {
                    for ($index; $index < count($oldAssociatedEvents); $index++) {
                        $oldAssociatedEvents[$index]->forceDelete();
                    }
                }

            } else if ($recreate && count($oldAssociatedEvents)) {
                foreach ($oldAssociatedEvents as $oldAssociatedEvent) {
                    $oldAssociatedEvent->forceDelete();
                }
            }

        } else if ($recreate && count($oldAssociatedEvents)) {
            foreach ($oldAssociatedEvents as $oldAssociatedEvent) {
                $oldAssociatedEvent->forceDelete();
            }
        }

        /*if(count($oldAssociatedEventsId))
    StaffEventRepeat::oftask()->whereIn('ser_event_id', $oldAssociatedEventsId)->forceDelete();
    if(count($oldAssociatedEventsRepeatId))
    StaffEventRepeat::oftask()->whereIn('ser_id', $oldAssociatedEventsRepeatId)->forceDelete();*/
    }

    /**
     * Create task and set reminder
     * @param task and date
     * @return void
     **/
    protected function setTaskReminder($date, $task)
    {
        $isError      = true;
        $businessId   = Session::get('businessId');
        $timestamp    = Carbon::now();
        $reminderDate = $date . ' ' . $task['taskDueTime'];
        $reminder     = Carbon::parse($reminderDate)->toDateTimeString();
        $taskDueDate  = Carbon::parse($date)->toDateString();
        $authId       = Auth::id();
        $taskData     = array('task_due_date' => $taskDueDate, 'task_due_time' => $task['taskDueTime']);
        $remindData   = array('tr_is_set' => 1, 'tr_hours' => $task['remindBeforHour'], 'tr_datetime' => $reminder);

        $taskColect = Task::where('task_client_id', $task['clientId'])->where('task_type', 'birthday')->first();
        if (count($taskColect)) {
            $taskId = $taskColect->id;
            if ($taskColect->update($taskData)) {
                TaskReminder::where('tr_task_id', $taskId)->update($remindData);
            }
        } else {
            $categoryId                   = TaskCategory::where('t_cat_user_id', 0)->where('t_cat_business_id', 0)->where('t_cat_name', 'Birthday')->pluck('id')->first();
            $taskData['task_business_id'] = $businessId;
            $taskData['task_category']    = $categoryId;
            $taskData['task_name']        = $task['taskName'];
            $taskData['task_type']        = 'birthday';
            $taskData['task_user_id']     = $authId;
            $taskData['task_note']        = $task['taskNote'];
            $taskData['task_client_id']   = $task['clientId'];
            $taskData['created_at']       = $timestamp;
            $taskData['updated_at']       = $timestamp;
            if ($taskId = Task::insertGetId($taskData)) {
                $remindData['tr_task_id'] = $taskId;
                $remindData['created_at'] = $timestamp;
                TaskReminder::insert($remindData);
                $calendEndDate = Carbon::now()->addMonth();
                $data          = array('eventRepeat' => 'Monthly', 'eventRepeatInterval' => 12, 'eventRepeatEnd' => 'Never', 'calendEndDate' => $calendEndDate);
                $this->storeEventRepeatTask($data, $taskId);
            }
        }
    }

    /**
     *
     *
     */
    protected function storeEventRepeatTask($data, $taskId, $recreate = false)
    {
        $task                                  = Task::find($taskId);
        $user                                  = Auth::user();
        $taskRepeat                            = new TaskRepeat;
        $taskRepeat->tr_business_id            = Session::get('businessId');
        $taskRepeat->tr_repeat                 = $data['eventRepeat'];
        $taskRepeat->tr_repeat_interval        = $data['eventRepeatInterval'];
        $taskRepeat->tr_repeat_end             = $data['eventRepeatEnd'];
        $taskRepeat->tr_repeat_end_after_occur = 0;
        $taskRepeat->tr_child_count            = 0;
        $taskRepeat->tr_task_user              = $user->id;
        $taskRepeat->tr_task_name              = $user->name . ' ' . $user->last_name;
        $taskRepeat->tr_task_category          = $task->task_category;
        $taskRepeat->tr_due_time               = Carbon::parse($task->task_due_date)->format('H:i:s');
        $taskRepeat->tr_task_type              = 'Birthday';
        $taskRepeat->save();

        $task->update(['task_tr_id' => $taskRepeat->tr_id]);

        $this->neverEndTaskRepeats();
    }

    /**
     * Task update/store
     *
     * @param
     * @return
     */
    protected function createTaskRepeat($request, $task, $taskDueDate)
    {
        $user                           = Auth::user();
        $taskRepeat                     = new TaskRepeat;
        $taskRepeat->tr_business_id     = Session::get('businessId');
        $taskRepeat->tr_repeat          = $request->eventRepeat;
        $taskRepeat->tr_repeat_interval = $request->eventRepeatInterval;
        $taskRepeat->tr_repeat_end      = $request->eventRepeatEnd;

        if ($request->eventRepeat == 'Weekly') {
            $taskRepeat->tr_repeat_week_days = json_encode($request->eventRepeatWeekdays);
        } else {
            $taskRepeat->tr_repeat_week_days = '';
        }

        if ($request->eventRepeatEnd == 'After') {
            $taskRepeat->tr_repeat_end_after_occur = $request->eventRepeatEndAfterOccur;
        } elseif ($request->eventRepeatEnd == 'On') {
            $taskRepeat->tr_repeat_end_after_occur = 0;
            $taskRepeat->tr_repeat_end_on_date     = $request->eventRepeatEndOnDate;
        } elseif ($request->eventRepeatEnd == 'Never') {
            $taskRepeat->tr_repeat_end_after_occur = 0;
        }
        $taskRepeat->tr_child_count   = 0;
        $taskRepeat->tr_task_user     = $user->id;
        $taskRepeat->tr_task_name     = $user->name . ' ' . $user->last_name;
        $taskRepeat->tr_task_category = $request->taskCategory;
        $taskRepeat->tr_due_time      = Carbon::parse($taskDueDate)->format('H:i:s');
        $taskRepeat->tr_task_type     = $task->task_type;
        if ($taskRepeat->save()) {
            return $taskRepeat->tr_id;
        } else {
            return 0;
        }

    }

    /**
     * Delete event associate invoice
     * @param
     * @return
     **/
    protected function deleteEventInvoice($eventId, $clientId = 0, $eventType, $isEpicCredit = false)
    {
        $invoices = Invoice::where('inv_client_id', $clientId)
            ->whereHas('invoiceitem', function ($query) use ($eventId, $eventType) {
                $query->where('inp_product_id', $eventId)
                    ->where('inp_type', $eventType);
            })->get();

        if ($invoices->count()) {
            foreach ($invoices as $invoice) {
                if ($isEpicCredit) {
                    // delete invoice when epic credit issue from caledar
                    if ($invoice->invoiceitem->count()) {
                        foreach ($invoice->invoiceitem as $invItem) {
                            $invItem->deleted_at = createTimestamp();
                            $invItem->save();
                        }
                    }
                    $invoice->deleted_at = createTimestamp();
                    $invoice->save();
                } else {
                    // delete invoice when event delete
                    $invoice->delete();
                }
            }
        }
    }

    /**
     * Check event has invoice or not
     *
     * @param event id, event type
     * @return boolean
     **/
    protected function isEventHasInvoice($eventId, $clientId = 0, $eventType)
    {

        $invoiceIds = InvoiceItems::select('inp_invoice_id')->where('inp_product_id', $eventId)->where('inp_type', $eventType)->get()->toArray();

        $invoices = Invoice::whereIn('inv_id', $invoiceIds)->where('inv_client_id', $clientId)->get();

        if ($invoices->count()) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Update effective price of class/service
     *
     * @param event id, event type
     * @return
     **/
    protected function updateEffectivePrice($id, $effectiveData)
    {
        $businessId     = Session::get('businessId');
        $timestamp      = Carbon::now();
        $effectivePrice = $effectiveData['price'];
        $isClass        = false;

        if ($effectiveData['type'] == 'service') {
            $type  = 'service';
            $query = StaffEventSingleService::where('sess_service_id', $id)->where('sess_date', '>=', $effectiveData['date']);
            $query->update(['sess_price' => $effectivePrice]);
            $eventIds = $query->where('sess_with_invoice', 1)->pluck('sess_id')->toArray();
        } elseif ($effectiveData['type'] == 'class') {
            $type    = 'class';
            $isClass = true;
            $query   = StaffEventClass::where('sec_class_id', $id)->where('sec_date', '>=', $effectiveData['date']);
            $query->update(['sec_price' => $effectivePrice]);
            $eventIds       = $query->pluck('sec_id')->toArray();
            $eventClientIds = $this->getAllClassClient($eventIds);
        } elseif ($effectiveData['type'] == 'classEvent') {
            $type           = 'class';
            $isClass        = true;
            $eventIds       = $effectiveData['targetEventids'];
            $eventClientIds = $this->getAllClassClient($eventIds);
        } elseif ($effectiveData['type'] == 'serviceEvent') {
            $type     = 'service';
            $eventIds = $effectiveData['targetEventids'];
        }

        $invoiceUpdate = array();
        $existInv      = array();
        if (count($eventIds) && (($isClass && count($eventClientIds) || !$isClass))) {
            // for existing invoice item update..
            $invoiceItems = InvoiceItems::whereIn('inp_product_id', $eventIds)->where('inp_type', $type)->get();
            if ($invoiceItems->count()) {
                foreach ($invoiceItems as $item) {
                    $taxValue = 0;
                    if ($item->inp_tax_type == 'Including') {
                        $tax = \App\MemberShipTax::where('mtax_business_id', $businessId)->select('id', 'mtax_label', 'mtax_rate')->where('mtax_label', $item->inp_tax)->first();
                        if (count($tax)) {
                            $taxValue = $effectivePrice - ($effectivePrice / (1 + ($tax->mtax_rate / 100)));
                        }

                        $unitPrice    = $effectivePrice - $taxValue;
                        $totalWithTax = $effectivePrice;
                    } elseif ($item->inp_tax_type == 'Excluding') {
                        $tax = \App\MemberShipTax::where('mtax_business_id', $businessId)->select('id', 'mtax_label', 'mtax_rate')->where('mtax_label', $item->inp_tax)->first();
                        if (count($tax)) {
                            $taxValue = $effectivePrice * ($tax->mtax_rate / 100);
                        }

                        $unitPrice    = $effectivePrice;
                        $totalWithTax = $effectivePrice + $taxValue;
                    } else {
                        $unitPrice    = $effectivePrice;
                        $totalWithTax = $effectivePrice;
                    }

                    if (in_array($item->inp_invoice_id, $existInv)) {
                        $totalAmount += $totalWithTax;
                        $taxAmount += $taxValue;
                        $invoiceUpdate[$item->inp_invoice_id] = array('inv_total' => $totalAmount, 'inv_incl_tax' => $taxAmount);
                    } else {
                        $totalAmount                          = $totalWithTax;
                        $taxAmount                            = $taxValue;
                        $invoiceUpdate[$item->inp_invoice_id] = array('inv_total' => $totalAmount, 'inv_incl_tax' => $taxAmount);
                        $existInv[]                           = $item->inp_invoice_id;
                    }

                    //update item
                    $item->inp_price = $unitPrice;
                    $item->inp_total = $totalWithTax;
                    $item->save();
                }
            }
        }
        $paymentIds    = array();
        $paymentUpdate = array();
        $epicBalance   = array();
        if (count($invoiceUpdate)) {
            // for existing invoice update..
            if ($isClass) {
                $invoices = Invoice::whereIn('inv_id', $existInv)->whereIn('inv_client_id', $eventClientIds)->get();
            } else {
                $invoices = Invoice::whereIn('inv_id', $existInv)->get();
            }

            if ($invoices->count()) {
                foreach ($invoices as $invoice) {
                    if ($invoice->inv_status == 'Paid') {
                        if ($invoice->inv_total < $invoiceUpdate[$invoice->inv_id]['inv_total']) {
                            $invoice->inv_status = 'Unpaid';
                        } elseif ($invoice->inv_total > $invoiceUpdate[$invoice->inv_id]['inv_total']) {
                            $newTotal                                  = $invoiceUpdate[$invoice->inv_id]['inv_total'];
                            $paymentUpdate[$invoice->inv_id]['amount'] = $newTotal;
                            $epicBalance[$invoice->inv_client_id]      = ($invoice->inv_total - $newTotal);
                            $paymentIds[]                              = $invoice->inv_id;
                        }
                    }

                    //update invoice
                    $invoice->inv_total    = $invoiceUpdate[$invoice->inv_id]['inv_total'];
                    $invoice->inv_incl_tax = $invoiceUpdate[$invoice->inv_id]['inv_incl_tax'];

                    $invoice->save();
                }
            }
        }

        if (count($paymentIds)) {
            $exist    = array();
            $payments = \App\Payment::whereIn('pay_invoice_id', $paymentIds)->orderBy('pay_id', 'desc')->get();
            if ($payments->count()) {
                foreach ($payments as $payment) {
                    if (!in_array($payment->pay_invoice_id, $exist)) {
                        $exist[] = $payment->pay_invoice_id;
                        $amount  = $paymentUpdate[$payment->pay_invoice_id]['amount'];

                        $payment->pay_total_invoice_amount = $amount;
                        $payment->pay_amount               = $amount;
                        $payment->pay_outstanding_amount   = 0;
                        $payment->save();
                    }
                }
            }
        }

        $clientIds = array();
        if (count($epicBalance)) {
            $makeup   = array();
            $user     = Auth::user();
            $username = ucfirst($user->name . ' ' . $user->last_name);
            foreach ($epicBalance as $clientId => $amount) {
                $makeup[]    = array('makeup_client_id' => $clientId, 'makeup_amount' => $amount, 'makeup_purpose' => 'invoice_amount', 'makeup_user_id' => $user->id, 'makeup_user_name' => $username, 'created_at' => $timestamp, 'updated_at' => $timestamp);
                $clientIds[] = $clientId;
            }
            if (count($makeup)) {
                \App\Makeup::insert($makeup);
            }

        }

        $unqClients = array_unique($clientIds);
        if (count($unqClients)) {
            $clients = Clients::whereIn('id', $unqClients)->get();
            if ($clients->count()) {
                foreach ($clients as $client) {
                    $epic_bal                    = $client->makeups()->sum('makeup_amount');
                    $client->epic_credit_balance = $epic_bal;
                    $client->save();
                }
            }
        }
    }

    /**
     * Get client ids of class
     *
     * @param
     * @return
     **/
    protected function getAllClassClient($eventIds)
    {
        if (count($eventIds)) {
            $secClients = DB::table('staff_event_class_clients')->whereIn('secc_sec_id', $eventIds)->where('secc_with_invoice', 1)->pluck('secc_client_id');
            return array_unique($secClients);
        }
        return [];
    }

    /**
     * Make resources data formated for recurrence table
     *
     * @param Request $request
     * @return String resource
     */
    protected function resourceRecurData($request)
    {
        $formData = $request->all();
        ksort($formData);
        $newResources = $newItem = $resources = [];
        foreach ($formData as $key => $value) {
            if (strpos($key, 'newResources') !== false) {
                $newResources[] = $value;
            } else if (strpos($key, 'newItem') !== false) {
                $newItem[] = $value;
            }

        }
        if (count($newResources) && count($newItem)) {
            for ($i = 0; $i < count($newItem); $i++) {
                $resources[$newResources[$i]] = $newItem[$i];
            }
        }
        if (count($resources)) {
            return json_encode($resources);
        } else {
            return '';
        }

    }

    /**
     * Create invoice for class or service
     *
     * @param $client, $event
     * @return Array $response
     */
    protected function raiseAnInvoice($eventClass, $clientId = 0, $isEpicInvoice = false, $reducedRate = 0, $businessId = '', $invoiceAmount = '', $source = '')
    {
        $disData = $eventClass->getItemDescData();
        if (count($disData)) {
            $invoiceData                = [];
            $invoiceData['locationId']  = $disData['locationId'];
            $invoiceData['productName'] = $disData['itemDesc'];
            if ($isEpicInvoice) {
                $invoiceData['status'] = 'Paid';
                //$invoiceData['from'] = 'event';
                $invoiceData['paymentType'] = 'EPIC Credit';
            } else {
                $invoiceData['status']      = 'Unpaid';
                $invoiceData['paymentType'] = 'Direct Debit';
            }

            if ($eventClass->getTable() == 'staff_event_single_services') {
                $invoiceData['dueDate'] = $eventClass->sess_date;
                $invoiceData['appointment_date_time'] = $eventClass->sess_start_datetime;
                $invoiceData['staffId'] = $eventClass->sess_staff_id;
                // $invoiceData['price'] = $invoiceAmount ? $invoiceAmount : $eventClass->sess_price;
                $invoiceData['price']           = $eventClass->sess_price;
                $invoiceData['productId']       = $eventClass->sess_id;
                $invoiceData['clientId']        = $eventClass->sess_client_id;
                $invoiceData['taxType']         = 'including';
                $invoiceData['type']            = 'service';
                $invoiceData['paidUsingCredit'] = $invoiceAmount ? number_format($eventClass->sess_price - $invoiceAmount, 2) : 0;
                $link                           = 'calendar-new?mevid=' . $eventClass->sess_id . '&mevtype=service';
                $linkDate                       = dbDateToDateTimeString(new Carbon($eventClass->sess_start_datetime));
            } else {
                $invoiceData['appointment_date_time'] = $eventClass->sec_start_datetime;
                $invoiceData['dueDate']         = $eventClass->sec_date;
                $invoiceData['staffId']         = $eventClass->sec_staff_id;
                $invoiceData['price']           = $reducedRate ? $reducedRate : $eventClass->sec_price;
                $invoiceData['productId']       = $eventClass->sec_id;
                $invoiceData['clientId']        = $clientId;
                $invoiceData['taxType']         = 'including';
                $invoiceData['type']            = 'class';
                $invoiceData['paidUsingCredit'] = $invoiceAmount ? number_format($eventClass->sec_price - $invoiceAmount, 2) : 0;
                $link                           = 'calendar-new?mevid=' . $eventClass->sec_id . '&mevtype=class';
                $linkDate                       = dbDateToDateTimeString(new Carbon($eventClass->sec_start_datetime));
            }

            $invoiceData['eventLink'] = 'EPIC Credit for <a href="' . url('dashboard/' . $link) . '">' . $linkDate . '</a> ' . $invoiceData['type'];
            $invResponse = $this->autoCreateInvoice($invoiceData, $businessId, $source);

            return $invResponse;
        }
        return [];
    }

    /**
     * Reset/resedule Single service event
     *
     * @param $event
     * @return
     */
    protected function resetSingleServiceRepeat($event, $startAndEndDatetime)
    {
        if ($event->sess_sessr_id != 0) {
            // dd($event->toArray());
            $eventRecurence = $event->repeat()->first();
            // dd($eventRecurence->toArray());
            if (count($eventRecurence)) {
                $eventRecurData['sessr_start_time'] = Carbon::parse($startAndEndDatetime['startDatetime'])->format('H:i:s');
                $eventRecurData['sessr_end_time']   = Carbon::parse($startAndEndDatetime['endDatetime'])->format('H:i:s');
                if ($eventRecurence->sessr_repeat == 'Weekly') {
                    $days                                     = [Carbon::parse($startAndEndDatetime['startDatetime'])->format('D')];
                    $eventRecurData['sessr_repeat_week_days'] = json_encode($days);
                }

                $eventRecurence->update($eventRecurData);
                StaffEventSingleService::where('sess_sessr_id', $event->sess_sessr_id)->whereDate('sess_date', '>', $event->sess_date)->forcedelete();

                /*Reset Memebership limit*/
                $this->membershipLimitReset($event->sess_client_id);
            }
        }
    }


    protected function resetBusyTimeRepeat($event, $startAndEndDatetime)
    {
        if ($event->seb_id != 0) {
            // dd($event->toArray());
            $eventRecurence = $event->repeat()->first();
            // dd($eventRecurence->toArray());
            if (count($eventRecurence)) {
                $eventRecurData['sebr_start_time'] = Carbon::parse($startAndEndDatetime['startDatetime'])->format('H:i:s');
                $eventRecurData['sebr_end_time']   = Carbon::parse($startAndEndDatetime['endDatetime'])->format('H:i:s');
                if ($eventRecurence->sebr_repeat == 'Weekly') {
                    $days                                     = [Carbon::parse($startAndEndDatetime['startDatetime'])->format('D')];
                    $eventRecurData['sebr_repeat_week_days'] = json_encode($days);
                }

                $eventRecurence->update($eventRecurData);
                StaffEventBusy::where('seb_sebr_id',  $eventRecurence->sebr_id)->whereDate('seb_date', '>', $event->seb_date)->forcedelete();

                /*Reset Memebership limit*/
                // $this->membershipLimitReset($event->sess_client_id);
            }
        }
    }

    protected function createRecurrenceClassByCron($reapetEventId, $insertRepeatUpto)
    {
        
        $insertRepeatUpto = new Carbon($insertRepeatUpto);
        if ($reapetEventId != 0) {
            // $repeatEvent = StaffEventClassRepeat::where('secr_id', $reapetEventId)->get();
            $repeatEvent = StaffEventClassRepeat::select('secr_id', 'secr_business_id', 'secr_repeat', 'secr_repeat_interval', 'secr_repeat_end', 'secr_repeat_end_after_occur', 'secr_repeat_end_on_date', 'secr_repeat_week_days', 'secr_child_count', 'secr_area_id', 'secr_client_id', 'secr_resources', 'secr_staff_id', 'secr_start_time', 'secr_end_time', 'secr_class_id', 'secr_duration', 'secr_capacity', 'secr_price', 'created_at', 'updated_at', 'deleted_at')->where('secr_id', $reapetEventId)->whereNull('deleted_at')->get();
        } else {
            $repeatEvent = StaffEventClassRepeat::select('secr_id', 'secr_business_id', 'secr_repeat', 'secr_repeat_interval', 'secr_repeat_end', 'secr_repeat_end_after_occur', 'secr_repeat_end_on_date', 'secr_repeat_week_days', 'secr_child_count', 'secr_area_id', 'secr_client_id', 'secr_resources', 'secr_staff_id', 'secr_start_time', 'secr_end_time', 'secr_class_id', 'secr_duration', 'secr_capacity', 'secr_price', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->get();
        }

        //dd($repeatEvent->toArray());
        $dates = array();
        if (count($repeatEvent)) {
            foreach ($repeatEvent as $recurData) {
                $dates = array();

                if ($recurData->secr_repeat_week_days != '') {
                    $eventRepeatWeekdays = json_decode($recurData->secr_repeat_week_days);
                } else {
                    $eventRepeatWeekdays = [];
                }              
                $event = StaffEventClass::where('sec_secr_id', $recurData->secr_id)->orderBy('sec_date','desc')->first();

                $eventStartDate = StaffEventClass::where('sec_secr_id', $recurData->secr_id)->orderBy('sec_date', 'asc')->pluck('sec_date')->first();

                // dd($event->toArray());
                if ($eventStartDate) {
                    if ($recurData->secr_repeat_end == 'After') {
                        $dates = $this->calcRepeatsDateAfterCase(['eventDate' => $eventStartDate, 'eventRepeatEndAfterOccur' => $recurData->secr_repeat_end_after_occur, 'eventRepeat' => $recurData->secr_repeat, 'eventRepeatInterval' => $recurData->secr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->secr_repeat_end == 'On') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $eventStartDate, 'eventRepeatEndOnDate' => $recurData->secr_repeat_end_on_date, 'eventRepeat' => $recurData->secr_repeat, 'eventRepeatInterval' => $recurData->secr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->secr_repeat_end == 'Never') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $eventStartDate, 'eventRepeatEndOnDate' => $insertRepeatUpto, 'eventRepeat' => $recurData->secr_repeat, 'eventRepeatInterval' => $recurData->secr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    }
                }

                /* Get only future dates */
                $dates = array_filter($dates, function ($value) use ($event){
                    return $value > /*date('Y-m-d')*/ $event->sec_date;
                });
                $dates = array_values($dates);

              
                if (!empty($dates) && !empty($event)) {
                    foreach ($dates as $date) {
                        $existClassEvent = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id', 'sec_class_id', 'sec_duration', 'sec_capacity', 'sec_price', 'sec_notes', 'sec_date', 'sec_time', 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_secr_id', $recurData->secr_id)->where('sec_class_id', $recurData->secr_class_id)->whereDate('sec_date', '=', $date)->first();
                        
                        $classDay = date('l', strtotime($date));
                        $eventStartDatetime = new Carbon($date.' '.$recurData->secr_start_time);
                        $eventEndDatetime   = new carbon($date.' '.$recurData->secr_end_time);

                        # Check if areas are busy at specified hours.
                        if ($recurData->secr_area_id) 
                            $busyAreaIds = $this->areAreasBusy(['areaId' => [$recurData->secr_area_id], 'startDatetime' => $eventStartDatetime->toDateTimeString(), 'endDatetime' => $eventEndDatetime->toDateTimeString()], 'class');
                        
                        # Check if staff is busy at specified hours.
                        if ($recurData->secr_staff_id) 
                            $staffBusy = $this->isStaffBusy(['staffId' => $recurData->secr_staff_id, 'day' => $classDay, 'startDatetime' =>  $eventStartDatetime->toDateTimeString(), 'endDatetime' => $eventEndDatetime->toDateTimeString()], 'class');

                        # Check if class is busy at specified hours.
                        if($recurData->secr_class_id) 
                            $classBusy = $this->isClassBusy(['classId' => $recurData->secr_class_id, 'startDatetime' => $eventStartDatetime->toDateTimeString(), 'endDatetime' => $eventEndDatetime->toDateTimeString()]);
            
                        // dd($existClassEvent->toArray());
                        if (empty($existClassEvent) && !$staffBusy && count($busyAreaIds) == 0 && !$classBusy) {
                            /* Class event not exist create and add clients */
                            $newClassEvent               = $event->replicate();
                            $newClassEvent->sec_date     = $date;
                            $newClassEvent->sec_staff_id = $recurData->secr_staff_id;
                            $newClassEvent->sec_class_id = $recurData->secr_class_id;
                            $newClassEvent->sec_duration = $recurData->secr_duration;
                            $newClassEvent->sec_capacity = $recurData->secr_capacity;
                            $newClassEvent->sec_price    = $recurData->secr_price;
                            $newClassEvent->sec_time     = $recurData->secr_start_time;

                            $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $recurData->secr_start_time, 'startDate' => $date, 'duration' => $recurData->secr_duration]);

                            $newClassEvent->sec_start_datetime = $startAndEndDatetime['startDatetime'];
                            $newClassEvent->sec_end_datetime   = $startAndEndDatetime['endDatetime'];
                            $newClassEvent->sec_payment        = 0;
                            $newClassEvent->save();

                            $logText = 'Class( Id:'.$newClassEvent->sec_id.', Date:'.$date.') created by cron.';
                            setInfoLog($logText, $newClassEvent->sec_id);
                            /* Link area to sibling class */
                            $repeatingEventAreaData = array();
                            if ($recurData->secr_area_id != '') {
                                $areas = explode(',', $recurData->secr_area_id);
                                if (count($areas) > 0) {
                                    foreach ($areas as $area) {
                                        $repeatingEventAreaData[] = array('seca_business_id' => $recurData->secr_business_id, 'seca_sec_id' => $newClassEvent->sec_id, 'seca_la_id' => $area);
                                    }
                                }
                            }

                            /* Link resources to sibling class */
                            $repeatingEventResourcesData = array();
                            if ($recurData->secr_resources != '') {
                                $resources = json_decode($recurData->secr_resources, true);
                                if (count($resources) > 0) {
                                    foreach ($resources as $resource => $item) {
                                        $timestamp                     = createTimestamp();
                                        $repeatingEventResourcesData[] = ['serc_event_id' => $newClassEvent->sec_id, 'serc_event_type' => 'App\StaffEventClass', 'serc_res_id' => $resource, 'serc_item_quantity' => $item, 'created_at' => $timestamp, 'updated_at' => $timestamp];
                                    }
                                }
                            }

                            /* Link Clients to sibling class */
                            $repeatingEventClients = array();
                            $clientsAddedCount     = 0;
                            if ($recurData->secr_client_id != '') {

                                $clients = json_decode($recurData->secr_client_id, true);

                                if (!empty($clients)) {
                                    foreach ($clients as $client_id => $clientRecur) {
                                        $timestamp     = createTimestamp();
                                        $busyClientIds = [];
                                        $membership    = $this->satisfyMembershipRestrictions($client_id, ['event_type' => 'class', 'event_id' => $newClassEvent->sec_class_id, 'event_date' => $date]);
                                        $isInvoice     = false;
                                        $cmid          = $membership['clientMembId'];

                                        $busyClientIds = $this->isClientBusy(['clientId' => [$client_id], 'startDatetime' => $newClassEvent->sec_start_datetime, 'endDatetime' => $newClassEvent->sec_end_datetime]);

                                        if (count($busyClientIds) == 0) {
                                            if ($membership['satisfy']) {
                                                $data = array('secc_sec_id' => $newClassEvent->sec_id, 'secc_client_id' => $client_id, 'secc_if_recur' => 1, 'secc_cmid' => $cmid, 'secc_with_invoice' => 0, 'secc_epic_credit' => 0, 'created_at' => $timestamp, 'updated_at' => $timestamp);

                                                if ($clientsAddedCount >= $recurData->secr_capacity) {
                                                    $data['secc_client_status'] = 'Waiting';
                                                } else {
                                                    $data['secc_client_status'] = 'Confirm';
                                                }

                                                $data['secc_event_log']           = 'Client booked with membership';
                                                $data['secc_action_performed_by'] = getLoggedUserName();

                                                $clientsAddedCount++;

                                                $repeatingEventClients[] = $data;

                                                if ($membership['satisfy']) {
                                                    # Set info log
                                                    setInfoLog('Client membership limit updated on class booked with membership by cron ', $client_id);

                                                    $this->updateClientMembershipLimit($client_id, [$date], ['type' => 'class', 'action' => 'add', 'eventId' => $newClassEvent->sec_class_id, 'limit_type' => $membership['limit_type']]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            /* Save class area */
                            if (!empty($repeatingEventAreaData)) {
                                DB::table('staff_event_class_areas')->insert($repeatingEventAreaData);
                            }

                            /* Save class client */
                            if (!empty($repeatingEventClients)) {
                                DB::table('staff_event_class_clients')->insert($repeatingEventClients);
                            }

                            /* Delete Resources class and insert new resources class */
                            StaffEventResource::where('serc_event_id', $newClassEvent->sec_id)->where('serc_event_type', 'App\StaffEventClass')->forcedelete();
                            if (!empty($repeatingEventResourcesData)) {
                                StaffEventResource::insert($repeatingEventResourcesData);
                            }
                        } 
                    }
                }

            }
        }
    } 
    protected function createRecurrenceSingleServiceByCron($reapetEventId, $insertRepeatUpto)
    {
        $insertRepeatUpto = new Carbon($insertRepeatUpto);

        if ($reapetEventId != 0) {
            $repeatEvent = StaffEventSingleServiceRepeat::select('sessr_id', 'sessr_business_id', 'sessr_repeat', 'sessr_repeat_interval', 'sessr_repeat_end', 'sessr_repeat_end_after_occur', 'sessr_repeat_end_on_date', 'sessr_repeat_week_days', 'sessr_child_count', 'sessr_area_id', 'sessr_resources', 'sessr_client_id', 'sessr_staff_id', 'sessr_start_time', 'sessr_end_time', 'sessr_service_id', 'sessr_duration', 'sessr_price', 'sessr_with_invoice', 'sessr_booking_status', 'sessr_auto_expire', 'created_at', 'updated_at', 'deleted_at')->where('sessr_id', $reapetEventId)->whereNull('deleted_at')->get();
        } else {
            $repeatEvent = StaffEventSingleServiceRepeat::select('sessr_id', 'sessr_business_id', 'sessr_repeat', 'sessr_repeat_interval', 'sessr_repeat_end', 'sessr_repeat_end_after_occur', 'sessr_repeat_end_on_date', 'sessr_repeat_week_days', 'sessr_child_count', 'sessr_area_id', 'sessr_resources', 'sessr_client_id', 'sessr_staff_id', 'sessr_start_time', 'sessr_end_time', 'sessr_service_id', 'sessr_duration', 'sessr_price', 'sessr_with_invoice', 'sessr_booking_status', 'sessr_auto_expire', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->get();
        }

        //dd($repeatEvent->toArray());

        $dates = array();
        if (count($repeatEvent)) {
            foreach ($repeatEvent as $recurData) {
                 //dd($recurData->toArray());

                if ($recurData->sessr_repeat_week_days != '') {
                    $eventRepeatWeekdays = json_decode($recurData->sessr_repeat_week_days);
                } else {
                    $eventRepeatWeekdays = [];
                }
          

                $event = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime', 'sess_end_datetime', 'sess_notes', 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason', 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->where('sess_sessr_id', $recurData->sessr_id)->orderBy('sess_date', 'desc')->whereNull('deleted_at')->first();

              
                $eventStartDate = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime', 'sess_end_datetime', 'sess_notes', 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason', 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->where('sess_sessr_id', $recurData->sessr_id)->orderBy('sess_date', 'asc')->pluck('sess_date')->first();

                // dd( $event->toArray());
                if ($eventStartDate) {
                    if ($recurData->sessr_repeat_end == 'After') {
                        $dates = $this->calcRepeatsDateAfterCase(['eventDate' => $event->sess_date, 'eventRepeatEndAfterOccur' => $recurData->sessr_repeat_end_after_occur, 'eventRepeat' => $recurData->sessr_repeat, 'eventRepeatInterval' => $recurData->sessr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->sessr_repeat_end == 'On') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $event->sess_date, 'eventRepeatEndOnDate' => $recurData->sessr_repeat_end_on_date, 'eventRepeat' => $recurData->sessr_repeat, 'eventRepeatInterval' => $recurData->sessr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    } elseif ($recurData->sessr_repeat_end == 'Never') {
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $event->sess_date, 'eventRepeatEndOnDate' => $insertRepeatUpto, 'eventRepeat' => $recurData->sessr_repeat, 'eventRepeatInterval' => $recurData->sessr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                    }
                }

                if (count($dates) && count($event)) {
                    $clientMembershipLimit = collect();
                    foreach ($dates as $date) {
                        if ($date > $event->sess_date) {

                            $existService = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime', 'sess_end_datetime', 'sess_notes', 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason', 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->where('sess_sessr_id', $recurData->sessr_id)->where('sess_service_id', $recurData->sessr_service_id)->whereDate('sess_date', '=', $date)->whereNull('deleted_at')->first();

                            if (!count($existService)) {
                                // dd('create');
                                $membership = $this->satisfyMembershipRestrictions($recurData->sessr_client_id, ['event_type' => 'service', 'event_id' => $recurData->sessr_service_id, 'event_date' => $date], $clientMembershipLimit);

                                if ($membership['satisfy'] && $event->sess_with_invoice == 0 && $event->sess_epic_credit == 0) {
                                    $newServiceEvent                  = $event->replicate();
                                    $newServiceEvent->sess_date       = $date;
                                    $newServiceEvent->sess_staff_id   = $recurData->sessr_staff_id;
                                    $newServiceEvent->sess_service_id = $recurData->sessr_service_id;
                                    $newServiceEvent->sess_client_id  = $recurData->sessr_client_id;
                                    $newServiceEvent->sess_duration   = $recurData->sessr_duration;
                                    $newServiceEvent->sess_price      = $recurData->sessr_price;
                                    $newServiceEvent->sess_time       = $recurData->sessr_start_time;
                                    //$newServiceEvent->sess_with_invoice = $recurData->sessr_with_invoice;
                                    $newServiceEvent->sess_booking_status    = $recurData->sessr_booking_status;
                                    $newServiceEvent->sess_auto_expire       = $recurData->sessr_auto_expire;
                                    $newServiceEvent->sess_client_attendance = 'Booked';
                                    $startAndEndDatetime                     = $this->calcStartAndEndDatetime(['startTime' => $recurData->sessr_start_time, 'startDate' => $date, 'duration' => $recurData->sessr_duration]);

                                    $newServiceEvent->sess_start_datetime      = $startAndEndDatetime['startDatetime'];
                                    $newServiceEvent->sess_end_datetime        = $startAndEndDatetime['endDatetime'];
                                    $newServiceEvent->sess_payment             = 0;
                                    $newServiceEvent->sess_event_log           = 'Client booked with membership';
                                    $newServiceEvent->sess_action_performed_by = getLoggedUserName();
                                    $newServiceEvent->save();

                                    $logText = 'Service( Id:'.$newServiceEvent->sess_id.', Date:'.$date.') created by cron.';
                                    setInfoLog($logText, $newServiceEvent->sess_id);
                                    /* Link resources to sibling area */
                                    $repeatingEventAreaData = array();
                                    if ($recurData->sessr_area_id != '') {
                                        $areas = explode(',', $recurData->sessr_area_id);
                                        if (count($areas)) {
                                            foreach ($areas as $area) {
                                                $repeatingEventAreaData[] = array('sessa_business_id' => $recurData->sessr_business_id, 'sessa_sess_id' => $newServiceEvent->sess_id, 'sessa_la_id' => $area);
                                            }
                                        }
                                    }

                                    /* Link resources to sibling service */
                                    $repeatingEventResourcesData = array();
                                    if ($recurData->sessr_resources != '') {
                                        $resources = json_decode($recurData->sessr_resources, true);
                                        if (count($resources)) {
                                            foreach ($resources as $resource => $item) {
                                                $timestamp                     = createTimestamp();
                                                $repeatingEventResourcesData[] = ['serc_event_id' => $newServiceEvent->sess_id, 'serc_event_type' => 'App\StaffEventSingleService', 'serc_res_id' => $resource, 'serc_item_quantity' => $item, 'created_at' => $timestamp, 'updated_at' => $timestamp];
                                            }
                                        }
                                    }

                                    /* Save sibling service area */
                                    if (count($repeatingEventAreaData)) {
                                        DB::table('staff_event_single_service_areas')->insert($repeatingEventAreaData);
                                    }

                                    /* Delete Resources service and insert new resources service */
                                    StaffEventResource::where('serc_event_id', $newServiceEvent->sess_id)->where('serc_event_type', 'App\StaffEventSingleService')->forcedelete();
                                    if (count($repeatingEventResourcesData)) {
                                        StaffEventResource::insert($repeatingEventResourcesData);
                                    }

                                    /* Link client with invoice */
                                    if (!$newServiceEvent->sess_with_invoice && !$newServiceEvent->sess_epic_credit) {
                                        # Set info log
                                        setInfoLog('Client membership limit updated on service booked with membership by cron', $recurData->sessr_client_id);

                                        $membershipLimit = $this->updateClientMembershipLimitLocaly($membership['clientMembLimit'], $recurData->sessr_client_id, ['type' => 'service', 'action' => 'add', 'eventId' => $recurData->sessr_service_id, 'date' => $date, 'limit_type' => $membership['limit_type']]);

                                        $clientMembershipLimit = $membershipLimit;
                                    }
                                }
                            }
                        }
                    }

                    // update limit
                    if (count($clientMembershipLimit)) {
                        $clientMembershipLimit->save();
                    }

                }

            }
        }      
    }
}
