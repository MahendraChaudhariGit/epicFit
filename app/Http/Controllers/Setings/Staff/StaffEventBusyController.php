<?php
namespace App\Http\Controllers\Setings\Staff;

use App\Clients;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \stdClass;
use App\StaffEventBusy;
use Auth;
use Session;
use App\LocationArea;
//use DB;
use App\Http\Traits\StaffEventsTrait;
use App\Staff;
use App\StaffEventBusyRepeat;
use Carbon\Carbon;
use App\ClosedDate;
use App\Http\Traits\ClosedDateTrait;
use App\Session as AppSession;

class StaffEventBusyController extends Controller{
    use StaffEventsTrait,ClosedDateTrait;

    public function store(Request $request){
    
        $isError = false;
        $msg = [];
        $businessId = Session::get('businessId');

        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'create-staff-event-busy')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        if(!$isError){
            if(isUserType(['Staff']) && Auth::user()->account_id != $request->staff)
                $isError = true;
            else if(!isUserType(['Staff']) && !Staff::ifstaffExist($request->staff, $businessId)) {
                // dd($request->all());
                $isError = true;
                $msg['status'] = 'error';
                $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                return json_encode($msg);
            }
            if(!$isError){
                $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->modalLocArea, 'staffId' => $request->staff]);

                if($isAreaLinkedToStaff){
                    $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->busyTime, 'startDate' => $request->busyDate, 'duration' => $request->busyDur]);
                    $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                    $eventEndDatetime = $startAndEndDatetime['endDatetime'];
                    
                    
                    if($this->isAreaBusy(['areaId' => $request->modalLocArea, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime])){
                        $msg['status'] = 'error';
                        $msg['errorData'][] = array('workingHourUnavail' => 'This area is busy at specified hours!');
                        $isError = true;
                        return json_encode($msg);
                    }
                    foreach($request->staff as $staffid){
                        if($this->isStaffBusy(['staffId' => $staffid, 'day' => $request->busyDay, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime])){

                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                            $isError = true;
                            return json_encode($msg);
                        }
                    }
                    if(!$isError){
                        $eventBusy = new StaffEventBusy;
                        $eventBusy->seb_area_id = $request->modalLocArea;
                        $eventBusy->seb_date = $request->busyDate;
                        $eventBusy->seb_time = $request->busyTime;
                        $eventBusy->seb_duration = $request->busyDur;
                        $eventBusy->seb_start_datetime = $eventStartDatetime;
                        $eventBusy->seb_end_datetime = $eventEndDatetime;
                        $eventBusy->seb_desc = $request->busyDesc;
                        $eventBusy->seb_business_id = Session::get('businessId');
                        if($request->has('busyDenyBook'))
                            $eventBusy->seb_deny_booking = $request->busyDenyBook;

                        Auth::user()->busyTime()->save($eventBusy);
                        $staffdata = array();
                        foreach ($request->staff as $staff) {
                        $data_business = array($staff => array('sebs_business_id' => $businessId ) );                
                        $staffdata = $staffdata + $data_business;    
                        }
                        $eventBusy->staffWithTrashed()->sync($staffdata);
                        $msg['eventId'] = $eventBusy->seb_id;
                        $msg['type'] = 'busy';
                        $msg['status'] = 'added';
                        $msg['message'] = displayAlert('success|Busy Time has been saved successfully.');
                    }
                }else{
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('workingHourUnavail' => 'This area is not linked to staff at specified hours!');
                    $isError = true;
                    return json_encode($msg);
                }
            }
        }
        return json_encode($msg);
    }

    public function index(Request $request){
        if($request->areaId != 'all' && !LocationArea::ifAreaExist($request->areaId))
            $this->haltScript($request);

        if($request->staffId != 'all' && $request->staffId != 'all-ros' && !Staff::ifstaffExist($request->staffId))
            $this->haltScript($request);

        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-event-busy'))
            $this->haltScript($request);

        $evnts = [];
        $isAreaLinkedToStaff = true;
        if(isUserType(['Staff'])){
            $request->staffId = Auth::user()->account_id;
            if($request->areaId != 'all')
                $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->areaId, 'staffId' => $request->staffId]);
        }            

        if($isAreaLinkedToStaff){
            $staffEvents = collect();
            $eventBusyData = new stdClass();

            if($request->areaId == 'all'){
                if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                    $staffEvents = StaffEventBusy::with(/*'areaWithTrashed',*/ 'staffWithTrashed')->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    /*if($request->staffId == 'all')
                        $staffEvents = StaffEventBusy::with(/*'areaWithTrashed',* 'staffWithTrashed')->OfBusiness()->get();
                    else{
                        $idsObj = DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->distinct()->select('as_la_id', 'as_staff_id')->get();
                        if(count($idsObj)){
                            $eventBusyData->startDate = $request->startDate;
                            if(isset($request->endDate))
                                $eventBusyData->endDate = $request->endDate;

                            foreach($idsObj as $idObj){
                                $eventBusyData->staffId = $idObj->as_staff_id;
                                $eventBusyData->areaId = $idObj->as_la_id;
                                
                                if(isset($request->endDate))
                                    $events = StaffEventBusy::with(/*'areaWithTrashed',* 'staffWithTrashed')->OfAreaAndStaffAndDatedBetween($eventBusyData)->get();
                                else
                                    $events = StaffEventBusy::with(/*'areaWithTrashed',* 'staffWithTrashed')->OfAreaAndStaffAndDated($eventBusyData)->get();

                                if($events->count())
                                    foreach($events as $event)
                                        $staffEvents->push($event);
                            }
                        }
                    }*/
                }
                else
                    $staffEvents = StaffEventBusy::with(/*'areaWithTrashed',*/ 'staffWithTrashed')->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
            }
            else{
                $eventBusyData->areaId = $request->areaId;

                if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                    $staffEvents = StaffEventBusy::with(/*'areaWithTrashed',*/ 'staffWithTrashed')->where('seb_area_id', $request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    /*if($request->staffId == 'all')
                        $staffEvents = StaffEventBusy::with(/*'areaWithTrashed',* 'staffWithTrashed')->where('seb_area_id', $request->areaId)->get();
                    else{
                        $area = LocationArea::find($request->areaId);
                        if($area){
                            $staffs = $area->staffsWithTrashed;
                            if($staffs->count()){
                                $eventBusyData->startDate = $request->startDate;
                                if(isset($request->endDate))
                                    $eventBusyData->endDate = $request->endDate;

                                foreach($staffs as $staff){
                                    $eventBusyData->staffId = $staff->id;
                                    if(isset($request->endDate))
                                        $events = StaffEventBusy::with(/*'areaWithTrashed',* 'staffWithTrashed')->OfAreaAndStaffAndDatedBetween($eventBusyData)->get();
                                    else
                                        $events = StaffEventBusy::with(/*'areaWithTrashed',* 'staffWithTrashed')->OfAreaAndStaffAndDated($eventBusyData)->get();

                                    if($events->count())
                                        foreach($events as $event)
                                            $staffEvents->push($event);
                                }
                            }
                        }
                    }*/
                }
                else{
                    $eventBusyData->staffId = $request->staffId;
                    $staffEvents = StaffEventBusy::with(/*'areaWithTrashed',*/ 'staffWithTrashed')->OfAreaAndStaff($eventBusyData)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                }
            }

            if(count($staffEvents)){
                $index = 0;
                $ifAreaHasStaffs = [];
                foreach($staffEvents as $staffEvent){
                    $evnts[$index]['id'] = $staffEvent->seb_id;
                    $evnts[$index]['description'] = $staffEvent->seb_desc;
                    $evnts[$index]['startDatetime'] = $staffEvent->seb_start_datetime;
                    $evnts[$index]['endDatetime'] = $staffEvent->seb_end_datetime;
                    $evnts[$index]['date'] = $staffEvent->seb_date;
                    $evnts[$index]['isStaffDeleted'] = $staffEvent->staffWithTrashed->trashed();

                    /*$area = $staffEvent->areaWithTrashed;
                    $evnts[$index]['areaId'] = $staffEvent->seb_area_id;
                    $evnts[$index]['isAreaDeleted'] = $area->trashed();
                    if(!array_key_exists($area->la_id, $ifAreaHasStaffs))
                        $ifAreaHasStaffs[$area->la_id] = $area->staffs()->exists();
                    $evnts[$index]['ifAreaHasStaffs'] = $ifAreaHasStaffs[$area->la_id];

                    $evnts[$index]['staffId'] = $staffEvent->seb_staff_id;
                    $evnts[$index]['staffName'] = $staffEvent->staffWithTrashed->fullName;
                    
                    $evnts[$index]['startTime'] = $staffEvent->seb_time;
                    $evnts[$index]['duration'] = $staffEvent->seb_duration;
                    $evnts[$index]['denyBooking'] = $staffEvent->seb_deny_booking;
                    $evnts[$index]['userId'] = $staffEvent->seb_user_id;*/
                    $index++;
                }
            }
        }
        return json_encode($evnts);
    }

    public function show($eventId, Request $request){
        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'view-staff-event-busy'))
            $this->haltScript($request);

        $event = [];

        if(isUserType(['Staff']))
            $staffEvent = StaffEventBusy::with('areaWithTrashed', 'staffWithTrashed')->withTrashed()->OfStaff(Auth::user()->account_id)->find($eventId);
        else
            $staffEvent = StaffEventBusy::with('areaWithTrashed', 'staffWithTrashed')->withTrashed()->OfBusiness()->find($eventId); 

        if($staffEvent){
            $event['id'] = $staffEvent->seb_id;
            $event['description'] = $staffEvent->seb_desc;
            $event['date'] = $staffEvent->seb_date;
            $event['isStaffDeleted'] = false;

            $area = $staffEvent->areaWithTrashed;
            $event['areaId'] = $staffEvent->seb_area_id;
            $event['isAreaDeleted'] = $area->trashed();
            $event['ifAreaHasStaffs'] = $area->staffs()->exists();
          
            $i = 0;
            foreach ($staffEvent->staffWithTrashed as $staff) {
                $event['staff'][$i]['id']            = $staff->id;
                $event['staff'][$i]['staffName']   = $staff->first_name. ' ' .$staff->last_name;
                $event['staff'][$i]['isStaffDeleted'] = $staff->trashed();
                $event['staffId'] = $staff->sebs_staff_id;

                $i++;
            }
            // $event['staffName'] = $staffEvent->staffWithTrashed->fullName;
            // $event['staffId'] = $staffEvent->seb_staff_id;

            $event['startTime'] = $staffEvent->seb_time;
            $event['duration'] = $staffEvent->seb_duration;        
            $event['denyBooking'] = $staffEvent->seb_deny_booking;
            $event['userId'] = $staffEvent->seb_user_id;
            $event['appointStatusOpt'] = $staffEvent->busy_status;

            if ($staffEvent->seb_sebr_id != 0) {
                $event['isRepeating'] = 1;
            } else {
                $event['isRepeating'] = 0;
                $event['eventRepeat'] = 'None';
            }
            if ($staffEvent->repeat()->count()) {
                $repeat                            = $staffEvent->repeat()->first();
                $event['eventRepeat']              = $repeat->sebr_repeat;
                $event['eventRepeatInterval']      = $repeat->sebr_repeat_interval;
                $event['eventRepeatEnd']           = $repeat->sebr_repeat_end;
                $event['eventRepeatEndAfterOccur'] = $repeat->sebr_repeat_end_after_occur;
                $event['eventRepeatEndOnDate']     = $repeat->sebr_repeat_end_on_date;
                $event['eventRepeatWeekDays']      = $repeat->sebr_repeat_week_days;
            }
        }
        return json_encode($event);
    }

    public function update(Request $request){
        $isError = false;
        $msg = [];

        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-busy')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        
        if(!$isError){                
            if(isUserType(['Staff']) && !in_array(Auth::user()->account_id,$request->staff))
                $isError = true;
            else if(!isUserType(['Staff'])){
                foreach($request->staff as $staff){
                    if(!Staff::ifstaffExist($staff)){
                        $isError = true;
                    }
                }
            }
            if(!$isError){
                $isAreaLinkedToStaff = true;
                foreach($request->staff as $staff){
                    $response = $this->isAreaLinkedToStaff(['areaId' => $request->modalLocArea, 'staffId' => $staff/*$staffId*/]);
                    if($response <= 0){
                        $isAreaLinkedToStaff = false;
                    }
                }
                if($isAreaLinkedToStaff){
                    if($request->step == 'busytime'){
                        //$eventEndTime = $this->calcEndTime(['startTime' => $request->busyTime, 'duration' => $request->busyDur]);

                        /*$eventStartDatetime = $this->calcStartDatetime(['startTime' => $request->busyTime, 'startDate' => $request->busyDate]);
                        $eventEndDatetime = $this->calcEndDatetime(['startTime' => $request->busyTime, 'startDate' => $request->busyDate, 'duration' => $request->busyDur]);*/
                        $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->busyTime, 'startDate' => $request->busyDate, 'duration' => $request->busyDur]);
                        $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                        $eventEndDatetime = $startAndEndDatetime['endDatetime'];

                        if($this->isAreaBusy(['eventId' => $request->eventId, 'areaId' => $request->modalLocArea/*, 'date' => $request->busyDate, 'startTime' => $request->busyTime, 'endTime' => $eventEndTime*/, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'busyTime')){
                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => 'This area is busy at specified hours!');
                            $isError = true;
                        }
                        else {
                            foreach($request->staff as $staff){
                                if($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $staff, 'day' => $request->busyDay, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'busyTime')){
                                    $msg['status'] = 'error';
                                    $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                                    $isError = true;
                                }
                            }
                        }
                    }
                    if(!$isError){
                        if(isUserType(['Staff']))
                            $eventBusy = StaffEventBusy::OfStaff(Auth::user()->account_id)->find($request->eventId);
                        else
                            $eventBusy = StaffEventBusy::OfBusiness()->find($request->eventId);
                        if($request->step =='busytime'){
                            if($eventBusy){
                                $eventBusy->seb_area_id = $request->modalLocArea;
                                // $eventBusy->seb_staff_id = $request->staff;
                                $eventBusy->seb_date = $request->busyDate;
                                $eventBusy->seb_time = $request->busyTime;
                                $eventBusy->seb_duration = $request->busyDur;
                                //$eventBusy->seb_end_time = $this->calcEndTime(['startTime' => $request->busyTime, 'duration' => $request->busyDur]);
                                //$eventBusy->seb_end_time = $eventEndTime;
                                $eventBusy->seb_start_datetime = $eventStartDatetime;
                                $eventBusy->seb_end_datetime = $eventEndDatetime;
                                $eventBusy->seb_desc = $request->busyDesc;
                                
                                if($request->has('busyDenyBook'))
                                    $eventBusy->seb_deny_booking = $request->busyDenyBook;
                                else
                                    $eventBusy->seb_deny_booking = 0;
    
                                $eventBusy->save();
    
                                $msg['status'] = 'updated';
                                $msg['message'] = displayAlert('success|Busy Time has been saved successfully.');
                            }
                        } else if ($request->step == 'busyReccurs' && $request->eventRepeat != '') {
                            if ($request->eventRepeat == 'None') {
                                if ($eventBusy->seb_sebr_id != 0) {
                                    $oldEventRecurence = $eventBusy->repeat()->first();
                                    if (count($oldEventRecurence)) {
                                        StaffEventBusy::where('seb_sebr_id', $oldEventRecurence['sebr_id'])->whereDate('seb_date', '>', $eventBusy->seb_date)->forcedelete();
                                        $oldEventRecurence->delete();
                                    }
                                }
                            } else {
                                // repeat is daily, weekely, monthaly
                                if ($eventBusy->seb_sebr_id != 0) {
                                    $oldEventRecurence = $eventBusy->repeat()->first();
                                }
                                if ($eventBusy->seb_sebr_id != 0 && count($oldEventRecurence)) {
                                    $newEventRecurence = $oldEventRecurence->replicate();
                                    StaffEventBusy::where('seb_sebr_id', $oldEventRecurence['sebr_id'])->whereDate('seb_date', '>', $eventBusy->seb_date)->forcedelete();

                                    $oldEventRecurence->delete();

                                    $noOfChild = StaffEventBusy::where('seb_sebr_id', $oldEventRecurence['sebr_id'])->count() - 1;
                                } else {
                                    $newEventRecurence                    = new StaffEventBusyRepeat;
                                    $newEventRecurence->sebr_business_id = Session::get('businessId');
                                    $noOfChild                            = 0;
                                }

                                $newEventRecurence->sebr_repeat          = $request->eventRepeat;
                                $newEventRecurence->sebr_repeat_interval = $request->eventRepeatInterval;
                                $newEventRecurence->sebr_child_count     = $noOfChild;
                                $newEventRecurence->sebr_staff_id        = implode(',',$request->staff);
                                $newEventRecurence->sebr_start_time      = Carbon::parse($eventBusy->seb_start_datetime)->format('H:i:s');
                                $newEventRecurence->sebr_end_time        = Carbon::parse($eventBusy->seb_end_datetime)->format('H:i:s');
                                $newEventRecurence->sebr_duration        = $eventBusy->seb_duration;
                                $newEventRecurence->sebr_repeat_end      = $request->busyRepeatEnd;

                                if ($request->eventRepeat == 'Weekly') {
                                    $newEventRecurence->sebr_repeat_week_days = json_encode($request->busyRepeatWeekdays);
                                } else {
                                    $newEventRecurence->sebr_repeat_week_days = '';
                                }

                                if ($request->eventRepeatEnd == 'After') {
                                    $newEventRecurence->sebr_repeat_end_after_occur = $request->eventRepeatEndAfterOccur;
                                    $newEventRecurence->sebr_repeat_end_on_date     = null;
                                } elseif ($request->eventRepeatEnd == 'On') {
                                    $newEventRecurence->sebr_repeat_end_after_occur = 0;
                                    $newEventRecurence->sebr_repeat_end_on_date     = $request->eventRepeatEndOnDate;
                                } elseif ($request->eventRepeatEnd == 'Never') {
                                    $newEventRecurence->sebr_repeat_end_after_occur = 0;
                                    $newEventRecurence->sebr_repeat_end_on_date     = null;
                                }
                                $newEventRecurence->sebr_area_id = $eventBusy->seb_area_id;
                                $newEventRecurence->save();
                                $eventBusy->seb_sebr_id = $newEventRecurence->sebr_id;
                                $msg['repeatId'] = $newEventRecurence->sebr_id;
                            }
                           

                            // dd($newEventRecurence->toArray());
                           
                            $eventBusy->update();
                            $msg['status'] = 'updated';
                            $msg['message'] = displayAlert('success|Busy Time has been saved successfully.');
                            
                        }
                    }
                }
            }
        }
        return json_encode($msg);
    }

    public function destroy(Request $request){
        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'delete-staff-event-busy'))
            $this->haltScript($request);
        /*{
            if($request->ajax())
                return [];
            else
                abort(404);
        }*/

        $msg = [];

        if(isUserType(['Staff']))
            $event = StaffEventBusy::OfStaff(Auth::user()->account_id)->find($request->eventId);
        else
            $event = StaffEventBusy::OfBusiness()->find($request->eventId);
        if($event && $request->targetEvents == 'this'){
            $event->delete(); 
        }else{
          $repeatId = $event->seb_sebr_id;
          StaffEventBusy::OfBusiness()->where('seb_sebr_id',$repeatId)->where('seb_date','>',$event->seb_date)->delete();
          $event->delete(); 
        }
    
        $msg['status'] = 'deleted';
        $msg['message'] = displayAlert('success|Busy Time has been cancelled.');
        return json_encode($msg);
    }

    public function changeDate(Request $request){
        $staffEvent = StaffEventBusy::find($request->eventId);
        $staffEvent->seb_date = $request->date;
        $staffEvent->seb_start_datetime = $staffEvent->seb_date.' '.$staffEvent->seb_time;

        $endDatetime = explode(' ', $staffEvent->seb_end_datetime);
        $staffEvent->seb_end_datetime = $staffEvent->seb_date.' '.$endDatetime[1];

        $staffEvent->save();
        dd('done');
    }

    public function reschedule(Request $request)
    {
        // dd($request->all());
        $isError = false;
        $msg     = [];

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-appointment')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $event = StaffEventBusy::OfStaff(Auth::user()->account_id)->find($request->eventId);
            } else {
                $event = StaffEventBusy::OfBusiness()->find($request->eventId);
            }

            if ($event) {
                $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $request->date, 'duration' => $event->seb_duration]);
                if (ClosedDate::ifOverlapping($request->date, $startAndEndDatetime['endDate'])) {
                    $msg['status']      = 'error';
                    $msg['errorData'][] = array('businessClosed' => '');
                    $isError            = true;
                }
                if (!$isError) {
                    
                    $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                    $eventEndDatetime   = $startAndEndDatetime['endDatetime'];
                    
                    if ( true) {
                        $eventDate = new Carbon($request->date);
                        // $areas       = $event->seb_area_id;
                        $areasId     =  $event->seb_area_id;
                        $busyAreaIds = $this->isAreaBusy(['areaId' => $request->eventId, 'areaId' => $areasId, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime]);
                        
                        if (count($busyAreaIds)) {
                            
                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => 'This area is busy at specified hours!');
                            $isError            = true;
                        } else if ($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $event->seb_staff_id, 'day' => $eventDate->format('l'), 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment')) {

                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|' . staffBusyMsg('Chosen staff is')));
                            $isError            = true;
                        } else {
                            // $busyClientIds = $this->isClientBusy(['eventId' => $request->eventId, 'clientId' => [$event->sess_client_id], 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');

                            // if (count($busyClientIds)) {
                            //     $msg['status']      = 'error';
                            //     $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|Chosen client is busy at specified hours!'));
                            //     $isError            = true;
                            // }
                        }
                    }
                    if (!$isError) {
                        $oldStartDatetime = $event->seb_start_datetime;
                        $oldEndDatetime   = $event->seb_end_datetime;

                        $eventStartDatetimeCarb = new Carbon($eventStartDatetime);
                        
                        $event->seb_date           = $request->date;
                        $event->seb_time           = $request->time;
                        $event->seb_start_datetime = $eventStartDatetime;
                        $event->seb_end_datetime   = $eventEndDatetime;

                        if ($event->update()) {

                            $client = Clients::find($event->seb_id);
                            

                            if ($request->has('targetEvents')) {
                                if ($request->targetEvents == 'future') {
                                 $this->resetBusyTimeRepeat($event, $startAndEndDatetime);
                                }
                           
                            }

                           
                            $event->refresh();
                           

                            $origEventTimestamp = strtotime($oldStartDatetime);
                            
                            $historyText = 'Rescheduled from ' . date('l', $origEventTimestamp) . ' ' . date('d/m/y', $origEventTimestamp) . ' @' . date('H:i A', $origEventTimestamp) . ' by ' . Auth::user()->name . ' ' . Auth::user()->last_name;

                            

                            $msg['status'] = 'updated';
                            $msg['eventId'] = $request->eventId;
                            $message       = displayAlert('success|Busy Time has been rescheduled successfully.');

                            

                            $msg['message'] = $message;
                        }
                    }
                }

        }
        return json_encode($msg);
    }
    }

    public function statusupdate(Request $request){
        $status = $request->busyValue;
        StaffEventBusy::where('seb_id', $request->eventId)->update(['busy_status' =>$status]);
        $msg['status'] = 'updated';
        $msg['message'] = displayAlert('success|Busy Status has set successfully.');
        return json_encode($msg);
    }
    
    public function updateDataField(Request $request){
        $isError = false;
        $msg = [];
        $staffEventBusy = StaffEventBusy::OfBusiness()->find($request->eventId);
        if($request->field == 'time' || $request->field == 'duration'){
            $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $staffEventBusy->seb_date, 'duration' => $request->duration]);
            $updataData=[];
            $eventStartDatetime = $startAndEndDatetime['startDatetime'];
            $eventEndDatetime = $startAndEndDatetime['endDatetime'];
            if($this->isAreaBusy(['eventId' => $request->eventId, 'areaId' => $staffEventBusy->seb_area_id, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'busyTime')){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('workingHourUnavail' => 'This area is busy at specified hours!');
                $isError = true;
            }else {
                $staffs = $staffEventBusy->staffWithTrashed;
                foreach($staffs as $staff){
                    $day = Carbon::parse($eventStartDatetime)->format('l');
                    if($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $staff, 'day' => $day, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'busyTime')){
                        $name = $staff->first_name.' '.$staff->last_name;
                        $msg['status'] = 'error';
                        $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg('',$name));
                        $isError = true;
                    }
                }
            }
            if(!$isError){
                $updateData['seb_start_datetime'] =  $eventStartDatetime;
                $updateData['seb_end_datetime'] = $eventEndDatetime;
                $updateData['seb_time'] = Carbon::parse($request->time)->format('H:i:s');
                if($request->field == 'duration'){
                    $updateData['seb_duration'] = $request->duration;
                }
        
                $staffEventBusy->update($updateData);
                if($request->targetEvent == 'future'){
                    $repeatId = $staffEventBusy->seb_sebr_id;
                    $staffBusyRepeat = StaffEventBusyRepeat::find($repeatId);
                    $staffBusyRepeat->sebr_start_time        = Carbon::parse($staffEventBusy->seb_start_datetime)->format('H:i:s');
                    $staffBusyRepeat->sebr_end_time        = Carbon::parse($staffEventBusy->seb_end_datetime)->format('H:i:s');
                    if($request->field == 'duration'){
                        $staffBusyRepeat->sebr_duration = $request->duration;
                    }
                    $staffBusyRepeat->save();
                    $staffEventBusyData = StaffEventBusy::OfBusiness()->where('seb_sebr_id',$repeatId)->get();
                        foreach($staffEventBusyData as $busyData){
                        $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $busyData->seb_date, 'duration' => $request->duration]);
                        $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                        $eventEndDatetime = $startAndEndDatetime['endDatetime'];
                        $updateData['seb_start_datetime'] =  $eventStartDatetime;
                        $updateData['seb_end_datetime'] = $eventEndDatetime;
                        $updateData['seb_time'] = Carbon::parse($request->time)->format('H:i:s');
                        if($request->field == 'duration'){
                            $updateData['seb_duration'] = $request->duration;
                        }
                        $busyData->update($updateData);
    
                        }
    
                }
                $msg['status'] = 'updated';
            }
        }else if($request->field == 'staff'){
            if(isUserType(['Staff']) && !in_array(Auth::user()->account_id,$request->staffName))
                $isError = true;
            else if(!isUserType(['Staff'])){
                foreach($request->staffName as $staff){
                    if(!Staff::ifstaffExist($staff)){
                        $isError = true;
                    }
                }
            }
            $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $staffEventBusy->seb_time, 'startDate' => $staffEventBusy->seb_date, 'duration' => $staffEventBusy->seb_duration]);
            $eventStartDatetime = $startAndEndDatetime['startDatetime'];
            $eventEndDatetime = $startAndEndDatetime['endDatetime'];
            foreach($request->staffName as $staff){
                $day = Carbon::parse($eventStartDatetime)->format('l');
                if($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $staff, 'day' => $day, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'busyTime')){
                    $staffData = Staff::where('id',$staff)->first();
                    $name =  $staffData->first_name.' '. $staffData->last_name;
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg('', $name));
                    $isError = true;
                }
            }
            if(!$isError){
                $staffdata = array();
                foreach ($request->staffName as $staff) {
                    $data_business = array($staff => array('sebs_business_id' => Session::get('businessId') ) );                
                    $staffdata = $staffdata + $data_business;    
                }
                $staffEventBusy->staffWithTrashed()->sync($staffdata);
    
                if($request->targetEvent == 'future'){
                    $repeatId = $staffEventBusy->seb_sebr_id;
                    $staffBusyRepeat = StaffEventBusyRepeat::find($repeatId);

                    $staffBusyRepeat->sebr_staff_id = implode(',',$request->staffName);
                    $staffBusyRepeat->save();
                    
                    $futureBusyData = StaffEventBusy::ofBusiness()->where('seb_sebr_id',$staffBusyRepeat->sebr_id)->get();
                    foreach($futureBusyData as $busy){
                        $staffdata = array();
                        foreach($request->staffName as $staffId){
                            $data_business = array($staffId => array('sebs_business_id' => Session::get('businessId')));                
                            $staffdata = $staffdata + $data_business; 
                        }
                        $busy->staffWithTrashed()->sync($staffdata);
                    }
                }
                $staffChange = $staffEventBusy->staffWithTrashed;
                $staffChangedata = [];
                $i = 0;
                foreach($staffChange as $changeStaff){
                    $staffChangedata[$i]['staffName'] = $changeStaff->first_name.' '.$changeStaff->last_name;
                    $staffChangedata[$i]['id'] = $changeStaff->id;
                    $i = $i + 1;
                }
                $msg['staff'] = $staffChangedata;
                $msg['status'] = 'updated';
            }
        }
        return json_encode($msg);
    }
}