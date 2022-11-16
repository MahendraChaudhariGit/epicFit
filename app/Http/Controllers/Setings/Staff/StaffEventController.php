<?php
namespace App\Http\Controllers\Setings\Staff;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\LocationArea;
use Illuminate\Http\Request;
use App\StaffEvent;
use App\StaffEventService;
use Auth;
use DB;
use Session;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\StaffEventHistoryTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\StaffEventTrait;
use \stdClass;
use App\StaffEventRepeat;
use App\StaffEventClass;
use Carbon\Carbon;
use App\SalesProcess;
use App\Clients;
use App\Staff;

class StaffEventController extends Controller{
    use ClientTrait, StaffEventHistoryTrait, HelperTrait, StaffEventTrait;
    
    public function index(Request $request){
        if($request->areaId != 'all' && !LocationArea::ifAreaExist($request->areaId))
            $this->haltScript($request);

        if($request->staffId != 'all' && $request->staffId != 'all-ros' && !Staff::ifstaffExist($request->staffId))
            $this->haltScript($request);

        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-event-appointment'))
            $this->haltScript($request);
        /*{
            if($request->ajax())
                return [];
            else
                abort(404);
        }*/

        $this->deleteExpiringAspirantsEvents();

        $evnts = [];
        $isAreaLinkedToStaff = true;
        if(isUserType(['Staff'])){
            $request->staffId = Auth::user()->account_id;
            if($request->areaId != 'all')
                $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->areaId, 'staffId' => $request->staffId]);
        }            

        if($isAreaLinkedToStaff){
            /*if($request->has('insertRepeatUpto'))
                $this->neverEndAppointmentRepeats($request);*/
            
            $staffEvents = collect();
            $eventAppointData = new stdClass();

            if($request->areaId == 'all'){
                if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                    if($request->staffId == 'all')
                        $staffEvents = StaffEvent::with('eventServicesWithTrashed', 'clientWithTrashed', 'histories', 'user', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeat')->OfBusiness()->get();
                    else{
                        $idsObj = DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))/*->whereNull('deleted_at')*/->distinct()->select('as_la_id', 'as_staff_id')->get();
                        if(count($idsObj)){
                            $eventAppointData->startDate = $request->startDate;
                            if(isset($request->endDate))
                                $eventAppointData->endDate = $request->endDate;

                            foreach($idsObj as $idObj){
                                $eventAppointData->staffId = $idObj->as_staff_id;
                                $eventAppointData->areaId = $idObj->as_la_id;

                                if(isset($request->endDate))
                                    $events = StaffEvent::with('eventServicesWithTrashed', 'clientWithTrashed', 'histories', 'user', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeat')->OfAreaAndStaffAndDatedBetween($eventAppointData)->get();
                                else
                                    $events = StaffEvent::with('eventServicesWithTrashed', 'clientWithTrashed', 'histories', 'user', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeat')->OfAreaAndStaffAndDated($eventAppointData)->get();

                                if($events->count())
                                    foreach($events as $event)
                                        $staffEvents->push($event);
                            }
                        }
                    }
                }
                else
                    $staffEvents = StaffEvent::with('eventServicesWithTrashed', 'clientWithTrashed', 'histories', 'user', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeat')->where('se_staff_id', $request->staffId)->get();
            }
            else{
                $eventAppointData->areaId = $request->areaId;

                if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                    if($request->staffId == 'all')
                        $staffEvents = StaffEvent::with('eventServicesWithTrashed', 'clientWithTrashed', 'histories', 'user', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeat')->where('se_area_id', $request->areaId)->get();
                    else{
                        $area = LocationArea::find($request->areaId);
                        if($area){
                            $staffs = $area->staffsWithTrashed;
                            if($staffs->count()){
                                $eventAppointData->startDate = $request->startDate;
                                if(isset($request->endDate))
                                    $eventAppointData->endDate = $request->endDate;

                                foreach($staffs as $staff){
                                    $eventAppointData->staffId = $staff->id;

                                    if(isset($request->endDate))
                                        $events = StaffEvent::with('eventServicesWithTrashed', 'clientWithTrashed', 'histories', 'user', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeat')->OfAreaAndStaffAndDatedBetween($eventAppointData)->get();
                                    else
                                        $events = StaffEvent::with('eventServicesWithTrashed', 'clientWithTrashed', 'histories', 'user', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeat')->OfAreaAndStaffAndDated($eventAppointData)->get();

                                    if($events->count())
                                        foreach($events as $event)
                                            $staffEvents->push($event);
                                }
                            }
                        }
                    }
                }
                else{
                    $eventAppointData->staffId = $request->staffId;
                    $staffEvents = StaffEvent::with('eventServicesWithTrashed', 'clientWithTrashed', 'histories', 'user', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeat')->OfAreaAndStaff($eventAppointData)->get();
                }
            }
            //dd(DB::getQueryLog());
            if(count($staffEvents)){
                $index = 0;
                $i = 0;
                $ifAreaHasStaffs = [];
                foreach($staffEvents as $staffEvent){
                    $area = $staffEvent->locationAndAreaWithTrashed;
                    if(!array_key_exists($area->la_id, $ifAreaHasStaffs))
                        $ifAreaHasStaffs[$area->la_id] = $area->staffs()->exists();
                    
                    foreach($staffEvent->eventServicesWithTrashed as $eventService){
                        $evnts[$index]['id'] = $staffEvent->se_id;
                        $evnts[$index]['date'] = $staffEvent->se_date;
                        $evnts[$index]['appointStatusOpt'] = $staffEvent->se_booking_status;
                        $evnts[$index]['appointStatusConfirm'] = $staffEvent->se_booking_status_confirm;
                        $evnts[$index]['autoExpireAppointDur'] = $staffEvent->se_auto_expire;
                        $evnts[$index]['autoExpireDatetime'] = $staffEvent->se_auto_expire_datetime;
                        $evnts[$index]['appointNote'] = $staffEvent->se_notes;
                        $evnts[$index]['isRepeating'] = $staffEvent->se_is_repeating;
                        $evnts[$index]['userId'] = $staffEvent->se_user_id;

                        /*if($staffEvent->se_user_id)
                            $evnts[$index]['userName'] = $staffEvent->user->fullName;
                        else
                            $evnts[$index]['userName'] = $staffEvent->staff->fullName;*/

                        //$evnts[$index]['locAreaName'] = $staffEvent->area->location->location_training_area.' - '.$staffEvent->area->la_name;
                        $evnts[$index]['areaId'] = $staffEvent->se_area_id;
                        $evnts[$index]['locAreaName'] = $area->locationWithTrashed->location_training_area.' - '.$area->la_name;
                        $evnts[$index]['isAreaDeleted'] = $area->trashed();
                        $evnts[$index]['ifAreaHasStaffs'] = $ifAreaHasStaffs[$area->la_id];

                        //$evnts[$index]['staffName'] = $staffEvent->staff->fullName;
                        $evnts[$index]['staffId'] = $staffEvent->se_staff_id;
                        $evnts[$index]['staffName'] = $staffEvent->staffWithTrashed->fullName;
                        $evnts[$index]['isStaffDeleted'] = $staffEvent->staffWithTrashed->trashed();

                        $client = $staffEvent->clientWithTrashed;
                        $evnts[$index]['title'] = $client->firstname.' '.$client->lastname;
                        $evnts[$index]['clientId'] = $client->id;
                        $evnts[$index]['clientEmail'] = $client->email;
                        $evnts[$index]['clientNumb'] = $client->phonenumber;
                        $evnts[$index]['isClientDeleted'] = $client->trashed();

                        $evnts[$index]['serviceId'] = $eventService->ses_service_id;
                        $evnts[$index]['startTime'] = $eventService->ses_time;
                        $evnts[$index]['duration'] = $eventService->ses_duration;
                        $evnts[$index]['serviceName'] = $eventService->serviceWithTrashed->name;
                        $evnts[$index]['serviceColor'] = $eventService->serviceWithTrashed->color;
                        $evnts[$index]['isServiceDeleted'] = $eventService->serviceWithTrashed->trashed();
                        $evnts[$index]['price'] = $eventService->ses_price;

                        if($staffEvent->repeat->count()){
                            $repeat = $staffEvent->repeat->first();
                            $evnts[$index]['eventRepeat'] = $repeat->ser_repeat;
                            $evnts[$index]['eventRepeatInterval'] = $repeat->ser_repeat_interval;
                            $evnts[$index]['eventRepeatEnd'] = $repeat->ser_repeat_end;
                            $evnts[$index]['eventRepeatEndAfterOccur'] = $repeat->ser_repeat_end_after_occur;
                            $evnts[$index]['eventRepeatEndOnDate'] = $repeat->ser_repeat_end_on_date;
                            $evnts[$index]['eventRepeatWeekDays'] = $repeat->ser_repeat_week_days;
                        }

                        $i = 0;
                        $histories = $staffEvent->histories->sortByDesc('created_at');
                        foreach($histories as $history){
                            $evnts[$index]['histories'][$i]['text'] = $history->seh_text;
                            $evnts[$index]['histories'][$i]['ownerName'] = $history->seh_name;
                            $evnts[$index]['histories'][$i]['date'] = setLocalToBusinessTimeZone($history->created_at)->toDateString();
                            $evnts[$index]['histories'][$i]['time'] = setLocalToBusinessTimeZone($history->created_at)->toTimeString();
                            $evnts[$index]['histories'][$i]['type'] = $history->seh_type;
                            $i++;
                        }

                        $index++;
                    }
                }
            }
        }
        //dd($evnts);
        return json_encode($evnts);
    }

    public function show($eventId, Request $request){
        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'view-staff-event-appointment'))
            $this->haltScript($request);
        /*{
            if($request->ajax())
                return [];
            else
                abort(404);
        }*/

        $event = [];
        if(isUserType(['Staff']))
            $staffEvent = StaffEvent::with('servicesWithTrashed', 'clientWithTrashed', 'historiesWithTrashed', 'userWithTrashed', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeatWithTrashed')->withTrashed()->where('se_staff_id', Auth::user()->account_id)->find($eventId);
        else
            $staffEvent = StaffEvent::with('servicesWithTrashed', 'clientWithTrashed', 'historiesWithTrashed', 'userWithTrashed', 'locationAndAreaWithTrashed', 'staffWithTrashed', 'repeatWithTrashed')->withTrashed()->OfBusiness()->find($eventId);

        if($staffEvent){
            /*if($staffEvent->se_user_id)
                $event['userName'] = $staffEvent->userWithTrashed->fullName;
            else
                $event['userName'] = $staffEvent->staffWithTrashed->fullName;*/
            //$event['userName'] = $staffEvent->userWithTrashed->name.' '.$staffEvent->userWithTrashed->last_name;
            $event['userId'] = $staffEvent->se_user_id;
            
            $area = $staffEvent->locationAndAreaWithTrashed;
            $event['locAreaName'] = $area->locationWithTrashed->location_training_area.' - '.$area->la_name;
            $event['areaId'] = $staffEvent->se_area_id;
            $event['isAreaDeleted'] = $area->trashed();
            $event['ifAreaHasStaffs'] = $area->staffs()->exists();

            $event['staffName'] = $staffEvent->staffWithTrashed->fullName;
            $event['staffId'] = $staffEvent->se_staff_id;
            $event['isStaffDeleted'] = $staffEvent->staffWithTrashed->trashed();

            $client = $staffEvent->clientWithTrashed;
            $event['clientName'] = $client->fullName;
            $event['clientId'] = $client->id;
            $event['clientEmail'] = $client->email;
            $event['clientNumb'] = $client->phonenumber;

            $event['date'] = $staffEvent->se_date;
            $event['appointStatusOpt'] = $staffEvent->se_booking_status;
            $event['appointStatusConfirm'] = $staffEvent->se_booking_status_confirm;
            $event['autoExpireAppointDur'] = $staffEvent->se_auto_expire;
            $event['autoExpireDatetime'] = $staffEvent->se_auto_expire_datetime;
            $event['appointNote'] = $staffEvent->se_notes;
            $event['startTime'] = $staffEvent->se_start_time;
            $event['isRepeating'] = $staffEvent->se_is_repeating;

            if($staffEvent->repeatWithTrashed->count()){
                $repeat = $staffEvent->repeatWithTrashed->first();
                $event['eventRepeat'] = $repeat->ser_repeat;
                $event['eventRepeatInterval'] = $repeat->ser_repeat_interval;
                $event['eventRepeatEnd'] = $repeat->ser_repeat_end;
                $event['eventRepeatEndAfterOccur'] = $repeat->ser_repeat_end_after_occur;
                $event['eventRepeatEndOnDate'] = $repeat->ser_repeat_end_on_date;
                $event['eventRepeatWeekDays'] = $repeat->ser_repeat_week_days;
            }

            $i = 0;
            foreach($staffEvent->servicesWithTrashed as $eventService){
                $event['services'][$i]['id'] = $eventService->ses_service_id;
                $event['services'][$i]['serviceName'] = $eventService->serviceWithTrashed->name;
                $event['services'][$i]['isServiceDeleted'] = $eventService->serviceWithTrashed->trashed();
                $event['services'][$i]['startTime'] = $eventService->ses_time;
                $event['services'][$i]['duration'] = $eventService->ses_duration;
                $event['services'][$i]['price'] = $eventService->ses_price;
                $i++;
            }

            $i = 0;
            $histories = $staffEvent->historiesWithTrashed->sortByDesc('created_at');
            foreach($histories as $history){
                $event['histories'][$i]['text'] = $history->seh_text;
                $event['histories'][$i]['ownerName'] = $history->seh_name;
                $event['histories'][$i]['date'] = setLocalToBusinessTimeZone($history->created_at)->toDateString();
                $event['histories'][$i]['time'] = setLocalToBusinessTimeZone($history->created_at)->toTimeString();
                $event['histories'][$i]['type'] = $history->seh_type;
                $i++;
            }
        }
        return json_encode($event);
    }

    public function setStatus(Request $request){
        $isError = false;
        $msg = [];

        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-appointment')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            if(isUserType(['Staff']))
                $event = StaffEvent::where('se_staff_id', Auth::user()->account_id)->find($request->eventId);
            else
                $event = StaffEvent::OfBusiness()->find($request->eventId);

            if($event){
                if($request->ifChangeToConfirm == 'true'){ 
                    $repeat = $event->repeat->first();

                    if(!$repeat || $repeat->ser_repeat == 'None'){
                        $eventDate = new Carbon($event->se_date);

                        if($this->isAreaBusy(['eventId' => $request->eventId, 'areaId' => $event->se_area_id, 'startDatetime' => $event->se_start_datetime, 'endDatetime' => $event->se_end_datetime/*, 'date' => $event->se_date, 'startTime' => $event->se_start_time, 'endTime' => $event->se_end_time*/], 'appointment')){
                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|Chosen area is busy at specified hours!'));
                            $isError = true;
                        }
                        else if($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $event->se_staff_id, 'day' => $eventDate->format('l'), 'startDatetime' => $event->se_start_datetime, 'endDatetime' => $event->se_end_datetime/*, 'date' => $event->se_date, 'startTime' => $event->se_start_time, 'endTime' => $event->se_end_time*/], 'appointment')){

                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|'.staffBusyMsg('Chosen staff is')));
                            $isError = true;
                        }
                        else if($this->isClientBusy(['eventId' => $request->eventId, 'clientId' => $event->se_client_id, 'startDatetime' => $event->se_start_datetime, 'endDatetime' => $event->se_end_datetime/*, 'date' => $event->se_date, 'startTime' => $event->se_start_time, 'endTime' => $event->se_end_time*/])){

                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|Chosen client is busy at specified hours!'));
                            $isError = true;
                        }
                    }
                }

                if(!$isError){
                    $event->se_booking_status_confirm = $request->appointStatusConfirm;
                    if($request->ifChangeToConfirm == 'true'){
                        $event->se_booking_status = 'Confirmed';
                        $event->se_auto_expire = '';
                        $event->se_auto_expire_datetime = '';
                    }
                    else{
                        if($event->se_booking_status_confirm == 'Completed' && in_array($event->se_sale_process_status, ['book_consult', 'book_benchmark', 'book_team'])){
                            /*if($event->se_sale_process_status == 'book_consult')
                                $saleProcessStep = 3;
                            else if($event->se_sale_process_status == 'book_benchmark')
                                $saleProcessStep = 5;
                            else if($event->se_sale_process_status == 'book_team')
                                $saleProcessStep = 7;

                            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($saleProcessStep);

                            $event->se_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
                            $event->save();

                            $client = Clients::find($event->se_client_id);
                            $client->account_status = $salesProcessRelatedStatus['clientStatus'];
                            $client->sale_process_step = $saleProcessStep;
                            $client->save();

                            $saleProcess = new SalesProcess;
                            $saleProcess->sp_client_id = $event->se_client_id;
                            $saleProcess->sp_entity_id = $event->se_id;
                            $saleProcess->sp_type = $salesProcessRelatedStatus['salesProcessType'];
                            $saleProcess->save();*/
                            $this->salesProcessEventCompleted($event, $event->se_client_id);
                        }
                        else if($event->se_booking_status_confirm == 'Did not show' && $event->se_sale_process_status != null){
                            /*if($event->se_sale_process_status == 'book_consult' || $event->se_sale_process_status == 'consulted'){
                                $eventPrevSaleProcessStatus = 'book_consult';
                                $saleProcessStep = 1;
                            }
                            else if($event->se_sale_process_status == 'book_benchmark' || $event->se_sale_process_status == 'benchmarked'){
                                $eventPrevSaleProcessStatus = 'book_benchmark';
                                $saleProcessStep = 3;
                            }
                            else if($event->se_sale_process_status == 'book_team' || $event->se_sale_process_status == 'teamed'){
                                $eventPrevSaleProcessStatus = 'book_team';
                                $saleProcessStep = 5;
                            }
                            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($saleProcessStep);

                            $salesProcessTypes = salesProcessTypes();
                            $key = array_search($eventPrevSaleProcessStatus, $salesProcessTypes);
                            if($key !== false && $key+1 != count($eventPrevSaleProcessStatus))
                                $temp = array_slice($salesProcessTypes, $key);
                            else
                                $temp = [];

                            $event->se_sale_process_status = null;
                            $event->save();
                            if(count($temp)){
                                $temp2 = array_slice($temp, 1);
                                if(count($temp2))
                                    StaffEvent::where('se_client_id', $event->se_client_id)->whereIn('se_sale_process_status', $temp2)->update(array('se_sale_process_status' => null));
                            }

                            $client = Clients::find($event->se_client_id);
                            $client->account_status = $salesProcessRelatedStatus['clientStatus'];
                            $client->sale_process_step = $saleProcessStep;
                            $client->save();

                            if(count($temp))
                                SalesProcess::where('sp_client_id', $event->se_client_id)->whereIn('sp_type', $temp)->delete();
                            else
                                SalesProcess::where('sp_client_id', $event->se_client_id)->where('sp_type', $eventPrevSaleProcessStatus)->delete();*/
                            $this->salesProcessEventIgnore($event, $event->se_client_id);
                        }
                    }
                    
                    if($event->update()){
                        $historyText = $request->historyText;
                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $event]);

                        $msg['status'] = 'updated';
                    }
                }
            }
        }
        return json_encode($msg);
    }

    public function reschedule(Request $request){
        $isError = false;
        $msg = [];

        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-appointment')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            if(isUserType(['Staff']))
                $event = StaffEvent::where('se_staff_id', Auth::user()->account_id)->find($request->eventId);
            else
                $event = StaffEvent::OfBusiness()->find($request->eventId);

            if($event){
                $eventStartDatetime = $this->calcStartDatetime(['startTime' => $event->se_start_time, 'startDate' => $request->date]);
                $eventEndDatetime = $this->calcEndDatetimeFromDuration(['duration' => $event->se_duration, 'startDatetime' => $eventStartDatetime]);
                if($event->se_booking_status == 'Confirmed'){ 
                    if(!$request->has('targetEvents')){
                        //$eventEndTime = $this->calcEndTime(['startTime' => $request->time, 'duration' => $event->se_total_dur]);
                        $eventDate = new Carbon($request->date);

                        /*$eventStartDatetime = $this->calcStartDatetime(['startTime' => $request->time, 'startDate' => $request->date]);
                        $eventEndDatetime = $this->calcEndDatetime(['startTime' => $request->time, 'startDate' => $request->date, 'duration' => $event->se_total_dur]);*/
                        //$carbon = Carbon::parse($eventStartDatetime);
                        //$eventEndDatetime = $carbon->addMinutes($event->se_duration)->toDateTimeString();  
                        //$end = 
                        //dd($eventStartDatetime);
                        /*$lastService = StaffEventService::where('ses_staff_event_id', $request->eventId)->orderBy('created_at', 'desc')->orderBy('ses_id', 'desc')->first();
                        $eventEndDatetime = $this->calcEndDatetime(['startTime' => $lastService->ses_time, 'startDate' => $request->date, 'duration' => $lastService->ses_duration]);*/

                        if($this->isAreaBusy(['eventId' => $request->eventId, 'areaId' => $event->se_area_id, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime/*, 'date' => $request->date, 'startTime' => $request->time, 'endTime' => $eventEndTime*/], 'appointment')){

                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|Chosen area is busy at specified hours!'));
                            $isError = true;
                        }
                        else if($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $event->se_staff_id, 'day' => $eventDate->format('l'), 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime/*, 'date' => $request->date, 'startTime' => $request->time, 'endTime' => $eventEndTime*/], 'appointment')){
                            
                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|'.staffBusyMsg('Chosen staff is')));
                            $isError = true;
                        }
                        else if($this->isClientBusy(['eventId' => $request->eventId, 'clientId' => $event->se_client_id, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime/*, 'date' => $request->date, 'startTime' => $request->time, 'endTime' => $eventEndTime*/])){

                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|Chosen client is busy at specified hours!'));
                            $isError = true;
                        }
                    }
                }

                if(!$isError){
                    $event->se_date = $request->date;
                    //$event->se_start_datetime = $this->calcStartDatetime(['startTime' => $event->se_start_time, 'startDate' => $request->date]);
                    //$event->se_end_datetime = $this->calcEndDatetime(['startTime' => $event->se_start_time, 'startDate' => $request->date, 'duration' => $event->se_total_dur]);
                    $event->se_start_datetime = $eventStartDatetime;
                    $event->se_end_datetime = $eventEndDatetime;
                    if($request->autoExpireDatetime != null)
                        $event->se_auto_expire_datetime = $request->autoExpireDatetime;
                    
                    if($event->update()){
                        if($request->has('targetEvents')){
                            if($request->targetEvents == 'future'){
                                if($event->se_parent_id){
                                    $this->delReccurData($event);
                                    
                                    $this->storeEventrepeatData($request, $event, true);

                                    $eventParentId = $event->se_parent_id;
                                    $event->se_parent_id = 0;
                                    $event->save();

                                    $this->haltPrevRelatedEventsReccur($eventParentId);
                                }
                                else{
                                    $this->delReccurData($event);
                                    $this->storeEventrepeatData($request, $event, true);
                                }
                            }   
                            else
                                $this->unlinkFromReccurenceChain($event);
                        }

                        $historyText = $request->historyText;
                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $event]);

                        $msg['status'] = 'updated';
                        $msg['message'] = displayAlert('success|Service has been rescheduled successfully.');
                    }
                }
            }  
        }
        return json_encode($msg);
    }

    public function update(Request $request){
        $isError = false;
        $msg = [];

        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-appointment')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            if(isUserType(['Staff']) && Auth::user()->account_id != $request->staff)
                $isError = true;
            else if(!isUserType(['Staff']) && !Staff::ifstaffExist($request->staff))
                $isError = true;

            if(!$isError){
                /*if(isUserType(['Staff'])){
                    $request->staff = Auth::user()->account_id;
                    $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->modalLocArea, 'staffId' => $request->staff]);
                }
                else
                    $isAreaLinkedToStaff = true;*/
                $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->modalLocArea, 'staffId' => $request->staff/*$staffId*/]);

                if($isAreaLinkedToStaff){
                    $clientId = $this->getClientId($request);
                    if($clientId == 'emailExist'){
                        $msg['status'] = 'error';
                        $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                        $isError = true;
                    }
                    else if(!$clientId)
                        $isError = true;

                    //$eventStartTime = timeStringToDbTime($request->serviceTime0);
                    //$eventEndTime = $this->calcEndTime(['startTime' => $eventStartTime, 'duration' => $request->totalDur]);

                    $startDate = $this->calcEventDate($request, $request->date);
                    $dataForStartAndEndDatetime = $this->calcDataForStartAndEndDatetime($request->all());
                    $eventStartTime = timeStringToDbTime($dataForStartAndEndDatetime['firstServiceStartTime']);
                    if(count($dataForStartAndEndDatetime)){
                        /*$eventStartDatetime = $this->calcStartDatetime(['startTime' => $dataForStartAndEndDatetime['firstServiceStartTime'], 'startDate' => $startDate]);
                        $eventEndDatetime = $this->calcEndDatetime(['startTime' => $dataForStartAndEndDatetime['lastServiceStartTime'], 'startDate' => $startDate, 'duration' => $dataForStartAndEndDatetime['lastServiceDur']]);*/
                        $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $dataForStartAndEndDatetime['firstServiceStartTime'], 'startTimeForEnd' => $dataForStartAndEndDatetime['lastServiceStartTime'], 'startDate' => $startDate, 'duration' => $dataForStartAndEndDatetime['lastServiceDur']]);
                        $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                        $eventEndDatetime = $startAndEndDatetime['endDatetime'];
                    }
                    else
                        $eventStartDatetime = $eventEndDatetime = null;
                    //$eventStartDatetime = $this->calcStartDatetime(['startTime' => $eventStartTime, 'startDate' => $startDate]);
                    //$eventEndDatetime = $this->calcEndDatetime(['startTime' => $eventStartTime, 'startDate' => $startDate, 'duration' => $request->totalDur]);
                    if($request->appointStatusOpt == 'Confirmed' && ($request->eventRepeat == 'None' || !$request->eventRepeat)){
                        if($this->isAreaBusy(['eventId' => $request->eventId, 'areaId' => $request->modalLocArea/*, 'date' => $request->date, 'startTime' => $eventStartTime, 'endTime' => $eventEndTime*/, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment')){

                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => 'This area is busy at specified hours!');
                            $isError = true;
                        }
                        else if($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $request->staff, 'day' => $request->day/*, 'date' => $request->date, 'startTime' => $eventStartTime, 'endTime' => $eventEndTime*/, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment')){
                            
                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                            $isError = true;
                        }
                        else if(!$request->isNewClient && $this->isClientBusy(['eventId' => $request->eventId, 'clientId' => $clientId/*, 'date' => $request->date, 'startTime' => $eventStartTime, 'endTime' => $eventEndTime*/, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime])){
                            
                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => 'This client is busy at specified hours!');
                            $isError = true;
                        }
                    }

                    if(!$isError){
                        if(isUserType(['Staff']))
                            $event = StaffEvent::with('eventServices')->where('se_staff_id', Auth::user()->account_id)->find($request->eventId);
                        else
                            $event = StaffEvent::with('eventServices')->OfBusiness()->find($request->eventId);

                        if($event){
                            $eventOld = $event->replicate();
                            $eventServicesOld = $event->eventServices;
                            $eventRepeatOld = $event->repeat->first();
                            $event->se_area_id = $request->modalLocArea;
                            $event->se_staff_id = $request->staff;
                            $event->se_client_id = $clientId;
                            $event->se_booking_status = $request->appointStatusOpt;
                            $event->se_notes = $request->appointNote;
                            $event->se_start_time = $eventStartTime;
                            //$event->se_total_dur = $request->totalDur;
                            //$event->se_end_time = $eventEndTime;
                            $event->se_start_datetime = $eventStartDatetime;
                            $event->se_end_datetime = $eventEndDatetime;
                            $event->se_duration = $this->calcEventDuartion(['endDatetime' => $eventEndDatetime, 'startDatetime' => $eventStartDatetime]);

                            if($request->appointStatusOpt == 'Confirmed'){
                                $event->se_booking_status_confirm = $request->appointStatusConfirm;
                                $event->se_auto_expire = $event->se_auto_expire_datetime = '';
                            }
                            else{
                                $event->se_booking_status_confirm = '';
                                if($request->ifAutoExpireAppoint == 1){
                                    if($request->autoExpireAppointDur == 'Custom')
                                        $event->se_auto_expire = '';
                                    else
                                        $event->se_auto_expire = $request->autoExpireAppointDur;

                                    $event->se_auto_expire_datetime = $request->autoExpireDatetime;
                                }
                                else
                                    $event->se_auto_expire = $event->se_auto_expire_datetime = '';
                            }

                            //$event->se_date = $request->date;
                            //$event->se_date = $this->calcEventDate($request, $request->date);
                            $event->se_date = $startDate;

                            $event->update();
                            $request->oldDate = $eventOld->se_date;
                            
                            $event->eventServices()->forceDelete();
                            //DB::table('staff_event_services')->where('ses_staff_event_id', $request->eventId)->delete();
                            //if(StaffEventService::storeEventServices($request->all(), $event->se_id)){
                            $servicesCount = StaffEventService::storeEventServices($request->all(), $event->se_id);
                            if($servicesCount){
                                if($servicesCount == 1){
                                    if($request->targetEvents == 'future' && ($this->ifEventDetailsUpdated($eventOld, $event, ['se_booking_status', 'se_booking_status_confirm', 'se_auto_expire', 'se_auto_expire_datetime', 'se_parent_id', 'se_is_repeating', 'event_services']) || $this->ifEventServiceUpdated($eventServicesOld, $request) || $this->ifEventRepeatUpdated($eventRepeatOld, $request))){
                                        if($request->eventRepeat){
                                            if($request->eventRepeat == 'Daily' || $request->eventRepeat == 'Weekly' || $request->eventRepeat == 'Monthly'){
                                                if($event->se_parent_id){
                                                    $this->delReccurData($event);
                                                    
                                                    $this->storeEventrepeatData($request, $event, true/*, false*/);

                                                    $eventParentId = $event->se_parent_id;
                                                    $event->se_parent_id = 0;
                                                    $event->save();

                                                    $this->haltPrevRelatedEventsReccur($eventParentId);

                                                }
                                                else{
                                                    $this->delReccurData($event);
                                                    $this->storeEventrepeatData($request, $event, true);
                                                }
                                            }
                                            else{
                                                $this->delAssociatedAppointments(['parentEventId' => $event->se_parent_id, 'eventDate' => $request->oldDate, 'eventId' => $event->se_id]);

                                                $repeat = $event->repeat->first();
                                                $repeat->ser_repeat = $request->eventRepeat;
                                                $repeat->ser_repeat_interval = $repeat->ser_repeat_end_after_occur = $repeat->ser_child_count = 0;
                                                $repeat->ser_repeat_end = $repeat->ser_repeat_end_on_date = $repeat->ser_repeat_week_days = null;
                                                $repeat->save();

                                                $eventParentId = $event->se_parent_id;
                                                $this->unsetEventReccurence($event);

                                                if($eventParentId)
                                                    $this->haltPrevRelatedEventsReccur($eventParentId);
                                            }
                                        }
                                        else{
                                            $this->delAssociatedAppointments(['parentEventId' =>$event->se_parent_id, 'eventDate' =>$request->oldDate, 'eventId' =>$event->se_id]);

                                            $eventParentId = $event->se_parent_id;
                                            $this->resetEventReccur($event);

                                            if($eventParentId)
                                                $this->haltPrevRelatedEventsReccur($eventParentId);
                                        }
                                    }
                                    else if($request->targetEvents == 'this')
                                        $this->unlinkFromReccurenceChain($event);
                                    else if(!$request->targetEvents){
                                        if($eventRepeatOld && $eventRepeatOld->ser_repeat == 'None'){
                                            if(!$request->eventRepeat)
                                                $this->resetEventReccur($event);
                                            else if($request->eventRepeat != 'None'){
                                                $this->delReccurData($event);
                                                $this->storeEventrepeatData($request, $event);
                                            }
                                        }
                                        else
                                            $this->storeEventrepeatData($request, $event);
                                    }
                                }   
                                else if($eventRepeatOld)
                                    $this->unlinkFromReccurenceChain($event);

                                if($eventOld->se_booking_status_confirm != $event->se_booking_status_confirm){
                                    if(in_array($event->se_booking_status_confirm, ['Not started', 'Arrived', 'Started']) && in_array($event->se_sale_process_status, ['consulted', 'benchmarked', 'teamed'])){
                                        $eventPrevSaleProcessStatus = $event->se_sale_process_status;

                                        $salesProcessTypes = salesProcessTypes();
                                        $key = array_search($eventPrevSaleProcessStatus, $salesProcessTypes);
                                        if($key !== false && $key+1 != count($eventPrevSaleProcessStatus))
                                            $temp = array_slice($salesProcessTypes, $key);
                                        else
                                            $temp = [];

                                        if($eventPrevSaleProcessStatus == 'consulted')
                                            $saleProcessStep = 2;
                                        else if($eventPrevSaleProcessStatus == 'benchmarked')
                                            $saleProcessStep = 4;
                                        else if($eventPrevSaleProcessStatus == 'teamed')
                                            $saleProcessStep = 6;
                                        $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($saleProcessStep);

                                        $event->se_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
                                        $event->save();
                                        if(count($temp)){
                                            $temp2 = array_slice($temp, 1);
                                            if(count($temp2))
                                                StaffEvent::where('se_client_id', $clientId)->whereIn('se_sale_process_status', $temp2)->update(array('se_sale_process_status' => null));
                                        }

                                        $client = Clients::find($clientId);
                                        $client->account_status = $salesProcessRelatedStatus['clientPrevStatus'];
                                        $client->sale_process_step = $saleProcessStep;
                                        $client->save();
                                        
                                        if(count($temp))
                                            SalesProcess::where('sp_client_id', $clientId)->whereIn('sp_type', $temp)->delete();
                                        else
                                            SalesProcess::where('sp_client_id', $clientId)->where('sp_type', $eventPrevSaleProcessStatus)->delete();
                                    }
                                    else if($event->se_booking_status_confirm == 'Completed' && in_array($event->se_sale_process_status, ['book_consult', 'book_benchmark', 'book_team'])){
                                        /*if($event->se_sale_process_status == 'book_consult')
                                            $saleProcessStep = 3;
                                        else if($event->se_sale_process_status == 'book_benchmark')
                                            $saleProcessStep = 5;
                                        else if($event->se_sale_process_status == 'book_team')
                                            $saleProcessStep = 7;

                                        $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($saleProcessStep);

                                        $event->se_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
                                        $event->save();

                                        $client = Clients::find($clientId);
                                        $client->account_status = $salesProcessRelatedStatus['clientStatus'];
                                        $client->sale_process_step = $saleProcessStep;
                                        $client->save();

                                        $saleProcess = new SalesProcess;
                                        $saleProcess->sp_client_id = $clientId;
                                        $saleProcess->sp_entity_id = $event->se_id;
                                        $saleProcess->sp_type = $salesProcessRelatedStatus['salesProcessType'];
                                        $saleProcess->save();*/
                                        $this->salesProcessEventCompleted($event, $clientId);
                                    }
                                    else if((!$event->se_booking_status_confirm || $event->se_booking_status_confirm == 'Did not show') && $event->se_sale_process_status != null){
                                        /*if($event->se_sale_process_status == 'book_consult' || $event->se_sale_process_status == 'consulted'){
                                            $eventPrevSaleProcessStatus = 'book_consult';
                                            $saleProcessStep = 1;
                                        }
                                        else if($event->se_sale_process_status == 'book_benchmark' || $event->se_sale_process_status == 'benchmarked'){
                                            $eventPrevSaleProcessStatus = 'book_benchmark';
                                            $saleProcessStep = 3;
                                        }
                                        else if($event->se_sale_process_status == 'book_team' || $event->se_sale_process_status == 'teamed'){
                                            $eventPrevSaleProcessStatus = 'book_team';
                                            $saleProcessStep = 5;
                                        }
                                        $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($saleProcessStep);

                                        $salesProcessTypes = salesProcessTypes();
                                        $key = array_search($eventPrevSaleProcessStatus, $salesProcessTypes);
                                        if($key !== false && $key+1 != count($eventPrevSaleProcessStatus))
                                            $temp = array_slice($salesProcessTypes, $key);
                                        else
                                            $temp = [];

                                        $event->se_sale_process_status = null;
                                        $event->save();
                                        if(count($temp)){
                                            $temp2 = array_slice($temp, 1);
                                            if(count($temp2))
                                                StaffEvent::where('se_client_id', $clientId)->whereIn('se_sale_process_status', $temp2)->update(array('se_sale_process_status' => null));
                                        }

                                        $client = Clients::find($clientId);
                                        $client->account_status = $salesProcessRelatedStatus['clientStatus'];
                                        $client->sale_process_step = $saleProcessStep;
                                        $client->save();

                                        if(count($temp))
                                            SalesProcess::where('sp_client_id', $clientId)->whereIn('sp_type', $temp)->delete();
                                        else
                                            SalesProcess::where('sp_client_id', $clientId)->where('sp_type', $eventPrevSaleProcessStatus)->delete();*/
                                        $this->salesProcessEventIgnore($event, $clientId);
                                    }
                                }

                                $historyText = $request->historyText;
                                $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $event]);

                                $msg['status'] = 'added';
                                $msg['message'] = displayAlert('success|Service has been saved successfully.');
                            }
                        }
                    }
                }
            }
        }
        return json_encode($msg);
    }

    public function store(Request $request){
        $isError = false;
        $msg = [];

        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'create-staff-event-appointment')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        
        if(!$isError){
            if(isUserType(['Staff']) && Auth::user()->account_id != $request->staff)
                $isError = true;
            else if(!isUserType(['Staff']) && !Staff::ifstaffExist($request->staff))
                $isError = true;

            if(!$isError){
                /*if(isUserType(['Staff'])){
                    $request->staff = Auth::user()->account_id;
                    $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->modalLocArea, 'staffId' => $request->staff]);
                }
                else
                    $isAreaLinkedToStaff = true;*/
                $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->modalLocArea, 'staffId' => $request->staff]);

                if($isAreaLinkedToStaff){
                    $clientId = $this->getClientId($request);
                    if($clientId == 'emailExist'){
                        $msg['status'] = 'error';
                        $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                        $isError = true;
                    }
                    else if(!$clientId)
                        $isError = true;
                    
                    //$eventStartTime = timeStringToDbTime($request->serviceTime0);
                    //$eventEndTime = $this->calcEndTime(['startTime' => $eventStartTime, 'duration' => $request->totalDur]);

                    $startDate = $this->calcEventDate($request, $request->date);
                    $dataForStartAndEndDatetime = $this->calcDataForStartAndEndDatetime($request->all());
                    $eventStartTime = timeStringToDbTime($dataForStartAndEndDatetime['firstServiceStartTime']);
                    if(count($dataForStartAndEndDatetime)){
                        /*$eventStartDatetime = $this->calcStartDatetime(['startTime' => $dataForStartAndEndDatetime['firstServiceStartTime'], 'startDate' => $startDate]);
                        $eventEndDatetime = $this->calcEndDatetime(['startTime' => $dataForStartAndEndDatetime['lastServiceStartTime'], 'startDate' => $startDate, 'duration' => $dataForStartAndEndDatetime['lastServiceDur']]);*/
                        $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $dataForStartAndEndDatetime['firstServiceStartTime'], 'startTimeForEnd' => $dataForStartAndEndDatetime['lastServiceStartTime'], 'startDate' => $startDate, 'duration' => $dataForStartAndEndDatetime['lastServiceDur']]);
                        $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                        $eventEndDatetime = $startAndEndDatetime['endDatetime'];
                    }
                    else
                        $eventStartDatetime = $eventEndDatetime = null;
                    //dd($eventStartDatetime);
                    //$eventStartDatetime = $this->calcStartDatetime(['startTime' => $eventStartTime, 'startDate' => $startDate]);
                    //$eventEndDatetime = $this->calcEndDatetime(['startTime' => $eventStartTime, 'startDate' => $startDate, 'duration' => $request->totalDur]);
                    if($request->appointStatusOpt == 'Confirmed' && ($request->eventRepeat == 'None' || !$request->eventRepeat)){
                        if($this->isAreaBusy(['areaId' => $request->modalLocArea, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime/*, 'date' => $request->date, 'startTime' => $eventStartTime, 'endTime' => $eventEndTime*/])){
                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => 'This area is busy at specified hours!');
                            $isError = true;
                        }
                        else if($this->isStaffBusy(['staffId' => $request->staff, 'day' => $request->day, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime/*, 'date' => $request->date, 'startTime' => $eventStartTime, 'endTime' => $eventEndTime*/])){

                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                            $isError = true;
                        }
                        else if(!$request->isNewClient && $this->isClientBusy(['clientId' => $clientId, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime/*, 'date' => $request->date, 'startTime' => $eventStartTime, 'endTime' => $eventEndTime*/])){
                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => 'This client is busy at specified hours!');
                            $isError = true;
                        }
                    }

                    if(!$isError){
                        $event = new StaffEvent;
                        $event->se_area_id = $request->modalLocArea;
                        $event->se_staff_id = $request->staff;
                        $event->se_client_id = $clientId;
                        $event->se_booking_status = $request->appointStatusOpt;
                        $event->se_notes = $request->appointNote;
        				$event->se_business_id = Session::get('businessId');
                        $event->se_start_time = $eventStartTime;
                        //$event->se_total_dur = $request->totalDur;
                        //$event->se_end_time = $eventEndTime;
                        $event->se_start_datetime = $eventStartDatetime;
                        $event->se_end_datetime = $eventEndDatetime;
                        $event->se_duration = $this->calcEventDuartion(['endDatetime' => $eventEndDatetime, 'startDatetime' => $eventStartDatetime]);

                        if($request->appointStatusOpt == 'Confirmed'){
                            $event->se_booking_status_confirm = $request->appointStatusConfirm;
                            $event->se_auto_expire = $event->se_auto_expire_datetime = '';
                        }
                        else{
                            $event->se_booking_status_confirm = '';
                            if($request->ifAutoExpireAppoint == 1){
                                if($request->autoExpireAppointDur == 'Custom')
                                    $event->se_auto_expire = '';
                                else
                                    $event->se_auto_expire = $request->autoExpireAppointDur;

                                $event->se_auto_expire_datetime = $request->autoExpireDatetime;
                            }
                            else
                                $event->se_auto_expire = $event->se_auto_expire_datetime = '';
                        }

                        //$event->se_date = $request->date;
                        //$event->se_date = $this->calcEventDate($request, $request->date);
                        $event->se_date = $startDate;

                        /*if(isUserType(['Staff']))
                            $event->save();
                        else*/
                            Auth::user()->eventAppointments()->save($event);

                        /*if($request->has('salesProcessType')){
                            $client = Clients::find($clientId);
                            if($client){
                                if($client->account_status == 'Pre-Consultation'){
                                    if($event->se_booking_status_confirm == 'Not started'){
                                        $clientStatus = 'Pre-Benchmarking';
                                        $saleProcessStep = 2;
                                        $salesProcessType = $request->salesProcessType;
                                    }
                                    else if($event->se_booking_status_confirm == 'Arrived'){
                                        $clientStatus = 'Pre-Training';
                                        $saleProcessStep = 3;
                                        $salesProcessType = 'consulted';
                                    }

                                    $client->account_status = $clientStatus;
                                    $client->sale_process_step = $saleProcessStep;
                                    $client->save();

                                    $saleProcess = new SalesProcess;
                                    $saleProcess->sp_client_id = $clientId;
                                    $saleProcess->sp_entity_id = $event->se_id;
                                    $saleProcess->sp_type = $request->salesProcessType;
                                    $saleProcess->save();

                                    $msg['changeStatus'] = $this->getStatusForbackend($clientStatus, true).'|'.$status;
                                }
                            }
                        }*/
                        $servicesCount = StaffEventService::storeEventServices($request->all(), $event->se_id);
                        if($servicesCount){
                            if($servicesCount == 1){
                                $this->storeEventrepeatData($request, $event);
                            }

                            if($request->has('salesProcessStepNumb') && $event->se_booking_status_confirm && $event->se_booking_status_confirm != 'Did not show'){
                                $client = Clients::find($clientId);
                                if($client){
                                    $salesProcessRelatedStatus = calcSalesProcessRelatedStatus((int) $request->salesProcessStepNumb);
                                    if($client->account_status == $salesProcessRelatedStatus['clientPrevStatus']){
                                        $processSalesProcess = false;
                                        if(in_array($event->se_booking_status_confirm, ['Not started', 'Arrived', 'Started'])){
                                            $processSalesProcess = true;
                                            //$clientStatus = $salesProcessRelatedStatus['clientStatus'];
                                            $saleProcessStep = $request->salesProcessStepNumb;
                                            //$salesProcessType = $salesProcessRelatedStatus['salesProcessType'];
                                        }
                                        else if($event->se_booking_status_confirm == 'Completed'){
                                            $processSalesProcess = true;
                                            $saleProcessStep = $request->salesProcessStepNumb+1;
                                            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($saleProcessStep);

                                            //$clientStatus = $salesProcessRelatedStatus['clientStatus'];
                                            //$salesProcessType = $salesProcessRelatedStatus['salesProcessType'];
                                        }

                                        if($processSalesProcess){
                                            if(array_key_exists('clientStatus', $salesProcessRelatedStatus))
                                                $clientStatus = $salesProcessRelatedStatus['clientStatus'];
                                            else
                                                $clientStatus = $client->account_status;
                                            //$salesProcessType = $salesProcessRelatedStatus['salesProcessType'];

                                            $event->se_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
                                            $event->save();

                                            $client->account_status = $clientStatus;
                                            $client->sale_process_step = $saleProcessStep;
                                            $client->save();

                                            $saleProcess = new SalesProcess;
                                            $saleProcess->sp_client_id = $clientId;
                                            $saleProcess->sp_entity_id = $event->se_id;
                                            $saleProcess->sp_type = $salesProcessRelatedStatus['salesProcessType'];//$salesProcessType;
                                            $saleProcess->save();

                                            $msg['changeStatus'] = $this->getStatusForbackend($clientStatus, true).'|'.$clientStatus;
                                            $msg['stepCompleted'] = $saleProcessStep;
                                        }
                                    }
                                }
                            }

                            $this->newHistory(['eventType' => 'Appointment', 'event' => $event]);

                            $msg['status'] = 'added';
                            $msg['message'] = displayAlert('success|Service has been saved successfully.');
                        }
                        else
                            $event->forceDelete();
                    }
                }
            }
        }
        return json_encode($msg);
    }

    protected function getClientId($request){
        if($request->isNewClient == 1){
            if(!Auth::user()->hasPermission(Auth::user(), 'create-client'))
                return false;
                
            //if(!$this->ifEmailAvailable(['email' => $request->clientEmail, 'entity' => 'client']))
            if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->clientEmail, 'entity' => 'client']))
                return 'emailExist';

            return $this->quickSaveClient($request);
        }
        else
            return $request->clientId;
    }

    protected function generateEventRepeat($dates, $event, $repeat, $data){
        $recreate = $data['recreate'];
        $oldDate = $data['oldDate'];

        $oldAssociatedEventsId = $oldAssociatedEventsRepeatId = [];

        if($recreate){
            if(!$event->se_parent_id)
                $oldAssociatedEvents = StaffEvent::with('repeat')->ChildEvents($event->se_id)->orderBy('se_date')->get();
            else
                $oldAssociatedEvents = StaffEvent::with('repeat')->SiblingEvents(['parentEventId' => $event->se_parent_id, 'eventDate' => $oldDate, 'eventId' => $event->se_id])->orderBy('se_date')->get();
        }

        if(count($dates)){
            $event->se_is_repeating = 1;
            $event->save();
            array_splice($dates, 0, 1);
            
            if(count($dates)){
                $services = StaffEventService::where('ses_staff_event_id', $event->se_id)->get();
                $repeatingEventServices = $repeatingEventRepeatData = [];

                $index = 0;
                foreach($dates as $date){
                    $newEvent = $event->replicate();
                    $newEvent->se_date = $date;
                    $newEvent->se_start_datetime = $this->calcStartDatetime(['startTime' => $event->se_start_time, 'startDate' => $date]);
                    //$carbon = Carbon::parse($newEvent->se_start_datetime);
                    //$eventEndDatetime = $carbon->addMinutes($event->se_duration)->toDateTimeString(); 
                    $newEvent->se_end_datetime = $this->calcEndDatetimeFromDuration(['duration' => $event->se_duration, 'startDatetime' => $newEvent->se_start_datetime]);

                    /*$temp = $services;
                    $lastService = $temp->sortByDesc('created_at')->sortByDesc('ses_id')->first();
                    dd($lastService);
                    $eventEndDatetime = $this->calcEndDatetime(['startTime' => $lastService->ses_time, 'startDate' => $date, 'duration' => $lastService->ses_duration]);
                    //$newEvent->se_start_datetime = $this->calcStartDatetime(['startTime' => $event->se_start_time, 'startDate' => $date]);
                    //$newEvent->se_end_datetime = $this->calcEndDatetime(['startTime' => $event->se_start_time, 'startDate' => $date, 'duration' => $event->se_total_dur]);*/
                    $newEvent->se_parent_id = $event->se_id;

                    if($recreate && $oldAssociatedEvents->count() > $index){
                        $oldAssociatedEvent = $oldAssociatedEvents[$index];

                        $oldAssociatedEvent->forceDelete();

                        $newEvent->se_id = $oldAssociatedEvent->se_id;
                        $newEvent->se_booking_status = $oldAssociatedEvent->se_booking_status;
                        $newEvent->se_booking_status_confirm = $oldAssociatedEvent->se_booking_status_confirm;
                        $newEvent->se_auto_expire = $oldAssociatedEvent->se_auto_expire;
                        $newEvent->se_auto_expire_datetime = $oldAssociatedEvent->se_auto_expire_datetime;
                        $newEvent->created_at = $oldAssociatedEvent->created_at;
                    }
                    $newEvent->save();

                    $timestamp = createTimestamp();
                    $repeatingEventRepeatData[] = ['ser_event_id' => $newEvent->se_id, 'ser_event_type' => $repeat->ser_event_type, 'ser_repeat' => $repeat->ser_repeat, 'ser_repeat_interval' => $repeat->ser_repeat_interval, 'ser_repeat_end' => $repeat->ser_repeat_end, 'ser_repeat_end_after_occur' => $repeat->ser_repeat_end_after_occur, 'ser_repeat_end_on_date' => $repeat->ser_repeat_end_on_date, 'ser_repeat_week_days' => $repeat->ser_repeat_week_days, 'created_at' => $timestamp, 'updated_at' => $timestamp];
                    
                    if(count($services)){
                        foreach($services as $service){
                            $timestamp = createTimestamp();
                            $repeatingEventServices[] = array('ses_staff_event_id' => $newEvent->se_id, 'ses_service_id' => $service->ses_service_id, 'ses_time' => $service->ses_time, 'ses_price' => $service->ses_price, 'ses_duration' => $service->ses_duration, 'created_at' => $timestamp, 'updated_at' => $timestamp);
                        }
                    }

                    $index++;
                }

                if(count($repeatingEventRepeatData))
                    DB::table('staff_event_repeats')->insert($repeatingEventRepeatData);

                if(count($repeatingEventServices))
                    DB::table('staff_event_services')->insert($repeatingEventServices);

                $repeat->ser_child_count = count($dates);
                $repeat->save();

                if($recreate && $oldAssociatedEvents->count() > count($dates)){
                    for($index; $index<count($oldAssociatedEvents); $index++){
                        $oldAssociatedEvent = $oldAssociatedEvents[$index];
                        $oldAssociatedEvent->forceDelete();
                    }
                }
            }
            else if($recreate && $oldAssociatedEvents->count())
                foreach($oldAssociatedEvents as $oldAssociatedEvent)
                    $oldAssociatedEvent->forceDelete();
        }
        else if($recreate && $oldAssociatedEvents->count())
            foreach($oldAssociatedEvents as $oldAssociatedEvent)
                $oldAssociatedEvent->forceDelete();

        if(count($oldAssociatedEventsId))
            StaffEventRepeat::whereIn('ser_event_id', $oldAssociatedEventsId)->forceDelete();
        if(count($oldAssociatedEventsRepeatId))
            StaffEventRepeat::whereIn('ser_id', $oldAssociatedEventsRepeatId)->forceDelete();
    }

    protected function unsetEventReccurence($event){
        $event->se_is_repeating = 0;
        if($event->se_parent_id)
            $event->se_parent_id = 0;
        $event->save();
    }

    protected function unlinkFromReccurenceChain($event){
        $eventParentId = $event->se_parent_id;
        $this->resetEventReccur($event);
        if($eventParentId)
            StaffEventRepeat::where('ser_event_id', $eventParentId)->decrement('ser_child_count');
    }

    protected function ifEventServiceUpdated($oldModel, $newModel){
        if($oldModel->count() > 1)
            return true;

        $oldModel = $oldModel->first();
        if($oldModel->ses_service_id != $newModel->serviceName0)
            return true;

        if($oldModel->ses_time != timeStringToDbTime($newModel->serviceTime0))
            return true;

        if($oldModel->ses_duration != $newModel->serviceDur0)
            return true;

        if($oldModel->ses_price != $newModel->servicePrice0)
            return true;

        return false;       
    }

    protected function haltPrevRelatedEventsReccur($eventParentId){
        $previousRelatedEvents = StaffEvent::where('se_parent_id', $eventParentId)->orWhere('se_id', $eventParentId)->orderBy('se_date', 'DESC')->get();
        if($previousRelatedEvents->count()){
            $latestEventDate = $previousRelatedEvents->first()->se_date;

            StaffEventRepeat::where('ser_event_id', $eventParentId)->update(['ser_child_count' => $previousRelatedEvents->count() -1]);

            $eventIds = $previousRelatedEvents->pluck('se_id')->toArray();

            $repeatTable = (new StaffEventRepeat)->getTable();
            DB::table($repeatTable)->whereIn('ser_event_id', $eventIds)->update(['ser_repeat_end' => 'ON', 'ser_repeat_end_after_occur' => 0, 'ser_repeat_end_on_date' => $latestEventDate]);
        }
    }

    protected function isClientBusy($data){ 
        //$eventClassData = ['date' => $data['date'], 'startTime' => $data['startTime'], 'endTime' => $data['endTime']];
        $eventClassData = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];

        if(StaffEvent::where('se_client_id', $data['clientId'])
                     ->clashingEvents($data)
                     ->count()

            || StaffEventClass::whereHas('clients', function($query) use ($data){
                                    $query->where('secc_client_id', $data['clientId']);
                                })
                              ->clashingEvents($eventClassData)
                              ->count()){
            return true;
        }
    }

    public function destroyAppointment(Request $request){
        $isError = false;
        $msg = [];

        if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'delete-staff-event-appointment')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            if(isUserType(['Staff']))
                $event = StaffEvent::where('se_staff_id', Auth::user()->account_id)->find($request->eventId);
            else
                $event = StaffEvent::OfBusiness()->find($request->eventId);

            if($event){
                //StaffEventService::destroyEventServices($request->eventId);
                //eventServices
                if($request->has('cancelReas')){
                    $event->se_cancel_reason = $request->cancelReas;
                    $event->update();

                    if($event->se_sale_process_status != null)
                        $this->salesProcessEventIgnore($event, $event->se_client_id);

                    if($request->has('targetEvents') && $request->targetEvents == 'future')
                        $this->delAssociatedAppointments(['parentEventId' =>$event->se_parent_id, 'eventDate' =>$event->se_date, 'eventId' =>$request->eventId]);
                }
                $event->delete();

                $msg['status'] = 'deleted';
                $msg['message'] = displayAlert('success|Service has been cancelled.');
            }
        }
        return json_encode($msg);
    }

    protected function delAssociatedAppointments($data){
        $data['eventType'] = 'appointment';
        $this->delAssociatedEvents($data);
    }

    protected function salesProcessEventCompleted($event, $clientId){
        if($event->se_sale_process_status == 'book_consult')
            $saleProcessStep = 3;
        else if($event->se_sale_process_status == 'book_benchmark')
            $saleProcessStep = 5;
        else if($event->se_sale_process_status == 'book_team')
            $saleProcessStep = 7;

        $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($saleProcessStep);

        $event->se_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
        $event->save();

        $client = Clients::find($clientId);
        $client->account_status = $salesProcessRelatedStatus['clientStatus'];
        $client->sale_process_step = $saleProcessStep;
        $client->save();

        $saleProcess = new SalesProcess;
        $saleProcess->sp_client_id = $clientId;
        $saleProcess->sp_entity_id = $event->se_id;
        $saleProcess->sp_type = $salesProcessRelatedStatus['salesProcessType'];
        $saleProcess->save();
    }

    protected function salesProcessEventIgnore($event, $clientId){
        if($event->se_sale_process_status == 'book_consult' || $event->se_sale_process_status == 'consulted'){
            $eventPrevSaleProcessStatus = 'book_consult';
            $saleProcessStep = 1;
        }
        else if($event->se_sale_process_status == 'book_benchmark' || $event->se_sale_process_status == 'benchmarked'){
            $eventPrevSaleProcessStatus = 'book_benchmark';
            $saleProcessStep = 3;
        }
        else if($event->se_sale_process_status == 'book_team' || $event->se_sale_process_status == 'teamed'){
            $eventPrevSaleProcessStatus = 'book_team';
            $saleProcessStep = 5;
        }
        $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($saleProcessStep);

        $salesProcessTypes = salesProcessTypes();
        $key = array_search($eventPrevSaleProcessStatus, $salesProcessTypes);
        if($key !== false && $key+1 != count($eventPrevSaleProcessStatus))
            $temp = array_slice($salesProcessTypes, $key);
        else
            $temp = [];

        $event->se_sale_process_status = null;
        $event->save();
        if(count($temp)){
            $temp2 = array_slice($temp, 1);
            if(count($temp2))
                StaffEvent::where('se_client_id', $clientId)->whereIn('se_sale_process_status', $temp2)->update(array('se_sale_process_status' => null));
        }

        $client = Clients::find($clientId);
        $client->account_status = $salesProcessRelatedStatus['clientStatus'];
        $client->sale_process_step = $saleProcessStep;
        $client->save();

        if(count($temp))
            SalesProcess::where('sp_client_id', $clientId)->whereIn('sp_type', $temp)->delete();
        else
            SalesProcess::where('sp_client_id', $clientId)->where('sp_type', $eventPrevSaleProcessStatus)->delete();
    }

    protected function calcDataForStartAndEndDatetime($input){
        $serviceTime = $data = [];
        foreach($input as $key => $value){
            if(strpos($key, 'serviceTime') !== false)
                $serviceTime[(int) str_replace("serviceTime", "", $key)] = $value;
        }

        if(count($serviceTime)){
            ksort($serviceTime);
            reset($serviceTime);

            $data['firstServiceStartTime'] = current($serviceTime);
            $serviceTime = array_reverse($serviceTime, true);
            foreach($serviceTime as $key => $value){
                if($input['serviceIsDeleted'.$key] == 'false'){
                    $data['lastServiceStartTime'] = $serviceTime[$key];
                    $data['lastServiceDur'] = $input['serviceDur'.$key];
                    break;
                }
            }
            //$data['lastServiceStartTime'] = end($serviceTime);
            //$key = key($serviceTime);
            //$data['lastServiceDur'] = $input['serviceDur'.$key];
        }
        return $data;
    }

    /*protected function calcEventDuartion($data){
        $startDatetime = Carbon::parse($data['startDatetime']);
        $endDatetime = Carbon::parse($data['endDatetime']);
        return $startDatetime->diffInMinutes($endDatetime);//->format('%H:%i:%s');
    }*/
}