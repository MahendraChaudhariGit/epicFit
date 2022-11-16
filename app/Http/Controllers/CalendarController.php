<?php
namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Session;
//use App\Business;
use App\Clients;
use App\Http\Traits\LocationAreaTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\StaffTrait;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\CalendarSettingTrait;
use App\Http\Traits\ClosedDateTrait;
//use App\CalendarSetting;
//use App\StaffEventClass;
//use App\ClosedDate;
use Carbon\Carbon;
//use App\Staff;
//use \stdClass;
use App\Http\Traits\SalesProcessProgressTrait;

//use DB;
use App\StaffEventClass;
use App\StaffEventSingleService;
use Cache;

class CalendarController extends Controller{
    use LocationAreaTrait, StaffEventsTrait, StaffTrait, ClientTrait, CalendarSettingTrait, ClosedDateTrait, SalesProcessProgressTrait;
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/
    
    public function index(){
        if(!Auth::user()->hasPermission(Auth::user(), 'view-backend'))
            abort(404);
        
        $events = Auth::user()->events()->get()->toArray();

        $formated_events = [];
        
        $i = 0;
        foreach($events as $event ) {
            $formated_events[$i]['id'] = $event['id'];
            $formated_events[$i]['title'] = strtoupper(str_replace('data-', '', $event['item_code']));
            
            $timestamp = strtotime('last Saturday 0 hour 0 minute 0 second');
            
            $timestamp = strtotime('+1 day '.$event['time_zone_offset'].' minutes', $timestamp);
            
            $formated_events[$i]['start'] = strtotime("+ ".$event['day']." days ".$event['hour']." hours ".$event['minute']." minutes", $timestamp);
            $formated_events[$i]['end'] = strtotime("+ ".$event['end_day']." days ".$event['end_hour']." hours ".$event['end_minute']." minutes", $timestamp);
            $formated_events[$i]['className'] = $event['item_code'];
            $formated_events[$i]['allDay'] = false;
            
            $i++;
        }
        
        $formated_events = json_encode($formated_events);
        if( $events != null ) {
            $half_hour_rate = $events[0]['half_hour_rate'];
        } else {
            $half_hour_rate = 0;
        }
        
        
        return view('calendar', compact('formated_events', 'half_hour_rate'));
    }
    
    public function store(){
        if(isset($_POST['saveEvents'])) {
            $half_hour_rate = $_POST['halfHourRate'];
            $events = json_decode($_POST['allEvents']);
            
            Auth::user()->events()->delete();
            
            foreach($events as $event ) {
                $saveEvent = new Event();
                
                $saveEvent->day = $event->day;
                $saveEvent->hour = $event->hour;
                $saveEvent->minute = $event->minute;
                
                $saveEvent->end_day = $event->endDay;
                $saveEvent->end_hour = $event->endHour;
                $saveEvent->end_minute = $event->endMinute;
                
                $saveEvent->item_code = $event->itemCode[0];
                $saveEvent->time_zone_offset = $event->timeZoneOffset;
                $saveEvent->half_hour_rate = $half_hour_rate;
                
                Auth::user()->events()->save($saveEvent);
                
            }
            echo "ok";
            exit;
        }
        return false;
    }
    
    public function indexNew(Request $request){
        $data =  Cache::remember('data', 60, function() use ($request) {
            return $this->locAreasForEvents();
        });
     
        $locsAreas = $data['locsAreas'];
        $ifClassesExit = $data['ifClassesExit'];
        $ifServicesExit = $data['ifServicesExit'];
        
        
        $staffs = ['all-ros' => 'All rostered staff', 'all' => 'All staff'] + $this->staffs('all');
        
        $calendSettings =  /*Cache::remember('calendSettings', 60, function() {*/
             $this->getCalendSettings();
        /*});*/
        
        // dd( $calendSettings);
        $calendarSettingVal = $calendSettings['settings'];
        $reasons = $calendSettings['cancelReasons'];
        
        $staffHoursRequest = new Request;
        if($calendarSettingVal['cs_view'] == 'monthly'){
            $staffHoursRequest->query->add(['staffId' => 'all', 'areaId' => 'all']);
        }
        else{
            $today = Carbon::now();
            $startDate = $today->copy()->toDateString();
            $endDate = $today->copy()->endOfWeek()->toDateString();
            $staffHoursRequest->query->add(['staffId' => 'all', 'areaId' => 'all','editStartDate'=>$startDate,'editEndDate'=>$endDate]);
        }
        
        $staffHours =  $this->getHoursFromTrait($staffHoursRequest);
        
        $modalLocsAreas = $locsAreas;
        if(count($locsAreas)) {
            $locsAreas =  ['all' => 'All Locations'] + $locsAreas;
        }
        
        $eventRepeatIntervalOpt =  Cache::remember('eventRepeatIntervalOpt', 60, function() {
            return $this->prepareEventRecurDdOpt();
        });
        
        $eventObj = '';
        if($request->has('subview')){
            $subview = true;
            if($request->clientId){
                $client = Clients::findClient($request->clientId);
                
                if($client){
                    $cl['id'] = $client->id;
                    $cl['name'] = $client->firstname.' '.$client->lastname;
                    $cl['email'] = $client->email;
                    $cl['phone'] = $client->phonenumber;
                    $cl['accStatus'] = $client->account_status;
                    if($request->has('consultationRestriction'))
                        $enableDateFrom = $client->consultation_date;

                    if($request->has('consultationRestriction'))
                        $enableDateTo = $client->consul_exp_date;
                    $cl = json_encode($cl);
                    
                    if($request->has('removeStepType')){
                        $removeStepType = $request->removeStepType;
                        $removeBookType = $request->removeBookType;
                    }
                }
            }
            // get html....
            if($request->has('eventId'))
                $eventObj = $this->getSingleServiceHtml($request->eventId);
        }
        if(!isset($cl)){
            $clientDetailsRequest = new Request;
            $clientDetailsRequest->query->add(['calendar' => true]);
            $clients = $this->allClientsFromTrait($clientDetailsRequest);
        }
        
        if($request->has('enableDatePeriod'))
            $enableDatePeriod = $request->enableDatePeriod;
        
        $closedDates =  Cache::remember('closedDates', 60, function() {
            return $this->getClosedDates();
        });
        
        $makeup_event_id = '';
        $makeup_event_type = '';
        $makeup_event_date = Carbon::now()->toDateString();
        if($request->has('mevid') && $request->has('mevtype')){
            $makeup_event_id = (int)$request->mevid;
            $makeup_event_type = $request->mevtype;
            if($makeup_event_type == 'class')
                $makeup_event_date = StaffEventClass::where('sec_id', $makeup_event_id)->pluck('sec_date')->first();
        }
        
        return view('calendar-new.show', compact('modalLocsAreas', 'locsAreas', 'ifClassesExit', 'ifServicesExit', 'eventRepeatIntervalOpt', 'subview', 'cl', 'enableDateFrom','enableDateTo', 'enableDatePeriod', 'staffs', 'staffHours', 'clients','reasons','calendarSettingVal', 'closedDates', 'removeStepType', 'removeBookType', 'makeup_event_id','makeup_event_type','makeup_event_date','eventObj'));
    }
    
    
    /**
    * Get staff total hours
    * @param
    * @return
    **/
    public function getTotalHours(Request $request){
        $staffHours = Cache::remember('staffHours', 60, function() use ($request) {
            return $this->getHoursFromTrait($request);
        });
        return json_encode($staffHours);
    }
    
    /*public function subview($clientId){
        //if(!Auth::user()->hasPermission(Auth::user(), 'view-calender'))
        //abort(404);
        
        $locsAreas = $this->locArea();
        
        $client = Clients::find($clientId);
        if($client){
            $cl['id'] = $client->id;
            $cl['name'] = $client->firstname.' '.$client->lastname;
            $cl['email'] = $client->email;
            $cl['phone'] = $client->phonenumber;
            $cl = json_encode($cl);
        }
        else
        $cl = '';
        
        return view('calendar-new.subview_show', compact('locsAreas', 'cl'));
    }*/
    
    public function getSingleServiceHtml($eventId){
        if(isUserType(['Staff']))
        $staffEvent = StaffEventSingleService::with('serviceWithTrashed', 'clientWithTrashed', 'historiesWithTrashed', 'locationAndAreasWithTrashed', 'staffWithTrashed', 'repeatWithTrashed')->withTrashed()->OfStaff(Auth::user()->account_id)->find($eventId); //, 'userWithTrashed'
    else
        $staffEvent = StaffEventSingleService::with('serviceWithTrashed', 'clientWithTrashed', 'historiesWithTrashed', 'locationAndAreasWithTrashed', 'staffWithTrashed', 'repeatWithTrashed', 'resourcesWithTrashed')->withTrashed()->OfBusiness()->find($eventId); //, 'userWithTrashed'
    $eventHtml = '';
    if($staffEvent){
        $eventHtml .= '<input type="hidden" name="type" value="single-service"><input type="hidden" name="eventId" value="'.$staffEvent->sess_id.'"><input type="hidden" name="date" value="'.$staffEvent->sess_date.'"><input type="hidden" name="startTime" value="'.$staffEvent->sess_time.'"><input type="hidden" name="duration" value="'.$staffEvent->sess_duration.'"><input type="hidden" name="userId" value="'.$staffEvent->sess_user_id.'"><input type="hidden" name="staffId" value="'.$staffEvent->sess_staff_id.'"><input type="hidden" name="staffName" value="'.$staffEvent->staffWithTrashed->fullName.'"><input type="hidden" name="isStaffDeleted" value="'.$staffEvent->staffWithTrashed->trashed().'"><input type="hidden" name="price" value="'.$staffEvent->sess_price.'"><input type="hidden" name="notes" value="'.$staffEvent->sess_notes.'"><input type="hidden" name="isRepeating" value="'.($staffEvent->sess_sessr_id?1:0).'">
        <input type="hidden" name="appointStatusOpt" value="'.$staffEvent->sess_booking_status.'">
        <input type="hidden" name="autoExpireAppointDur" value="'.$staffEvent->sess_auto_expire.'">
        <input type="hidden" name="autoExpireDatetime" value="'.$staffEvent->sess_auto_expire_datetime.'">
        <input type="hidden" name="serviceName" value="'.$staffEvent->serviceWithTrashed->name.'">
        <input type="hidden" name="serviceId" value="'.$staffEvent->sess_service_id.'">
        <input type="hidden" name="forSalesProcessStep" value="'.$staffEvent->serviceWithTrashed->for_sales_process_step.'">
        <input type="hidden" name="isServiceDeleted" value="'.$staffEvent->serviceWithTrashed->trashed().'">
        <input type="hidden" name="justCreated" value="'.$staffEvent->sess_client_check.'"> 
        <input type="hidden" name="isInvoice" value="'.$staffEvent->sess_with_invoice.'">
        <input type="hidden" name="isClientMakeup" value="'.$staffEvent->sess_epic_credit.'">';

        if($staffEvent->clientWithTrashed)
            $eventHtml .= '<input type="hidden" name="consultationDate" value="'.$staffEvent->clientWithTrashed->consultation_date.'">';
        $salesProcessEvent = $this->saleProcessStatusToStepNumb($staffEvent->sess_sale_process_status);
        if(!$salesProcessEvent && $staffEvent->clientWithTrashed){
            $eventProcess = $this->isBookingIndivBooking($staffEvent->sess_id, $staffEvent->sess_date, $staffEvent->clientWithTrashed);
            $eventHtml .= '<input type="hidden" name="salesProcessEvent" value="'.$eventProcess.'">';
        }
        else
            $eventHtml .= '<input type="hidden" name="salesProcessEvent" value="'.$salesProcessEvent.'">';

        if($staffEvent->repeat()->count()){
            $repeat = $staffEvent->repeat()->first();
            $eventHtml .= '<input type="hidden" name="eventRepeat" value="'.$repeat->sessr_repeat.'">
            <input type="hidden" name="eventRepeatInterval" value="'.$repeat->sessr_repeat_interval.'">
            <input type="hidden" name="eventRepeatEnd" value="'.$repeat->sessr_repeat_end.'">
            <input type="hidden" name="eventRepeatEndAfterOccur" value="'.$repeat->sessr_repeat_end_after_occur.'">
            <input type="hidden" name="eventRepeatEndOnDate" value="'.$repeat->sessr_repeat_end_on_date.'">';
            if($repeat->sessr_repeat_week_days)
                $eventRepeatWeekDays = explode(',', $repeat->sessr_repeat_week_days);
            else
                $eventRepeatWeekDays = [];

            foreach ($eventRepeatWeekDays as $key => $value) {
                $eventHtml .= '<input type="hidden" name="eventRepeatWeekDays[]" value="'.$value.'">';
            }
        }
        if($staffEvent->clientWithTrashed){
            $event['clients'][0]['id'] = $staffEvent->clientWithTrashed->id;
            $event['clients'][0]['name'] = $staffEvent->clientWithTrashed->fullName;
            $event['clients'][0]['accStatus']=$staffEvent->clientWithTrashed->account_status;
            $event['clients'][0]['phone'] = $staffEvent->clientWithTrashed->phonenumber;
            $event['clients'][0]['email'] = $staffEvent->clientWithTrashed->email;
            $event['clients'][0]['note'] = $staffEvent->sess_client_notes;
            $event['clients'][0]['attendance'] = $staffEvent->sess_client_attendance;
            $event['clients'][0]['photo'] = $staffEvent->clientWithTrashed->profilepic;
            $event['clients'][0]['isClientDeleted'] = $staffEvent->clientWithTrashed->trashed();


            $eventHtml .= '<input type="hidden" name="client[]" value="'.implode(' ', $event['clients'][0]).'">';
        } 
    }
    return $eventHtml;
}

protected function saleProcessStatusToStepNumb($status){
    if($status != null && $status){
        $salesProcessCurrentDetails = calcSalesProcessRelatedStatus($status);
        return $salesProcessCurrentDetails['saleProcessStepNumb'];
    }
    return $status;
}
}
