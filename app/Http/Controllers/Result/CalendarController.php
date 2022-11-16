<?php
namespace App\Http\Controllers\Result;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use App\Event;
use App\Http\Traits\Result\LocationAreaTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\StaffTrait;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\CalendarSettingTrait;
use App\CalendarSetting;
use App\ClosedDate;
use App\Clients;
use Carbon\Carbon;
use App\Http\Traits\ClosedDateTrait;
use DB;

class CalendarController extends Controller{
    use LocationAreaTrait, StaffEventsTrait, StaffTrait, ClientTrait, CalendarSettingTrait,ClosedDateTrait;
    
    public function show_calendar(Request $request){
        
        $data = $this->locAreasForEvents();
        $locsAreas = $data['locsAreas'];
        $ifClassesExit = $data['ifClassesExit'];
        $ifServicesExit = $data['ifServicesExit'];

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
        $client = Clients::findClient(Auth::user()->account_id);
        $availableCreditBalance = $client->epic_credit_balance;
        if($request->has('subview')){

            $subview = true;
            if(Auth::user()->account_id){

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


        if($request->has('enableDatePeriod')){
            $enableDatePeriod = $request->enableDatePeriod;   
        }
            $client_id = Auth::user()->account_id;    
        // DB::enableQueryLog();
            $calendarSettingVal = CalendarSetting::where('cs_business_id',Session::get('businessId'))->whereIn('cs_client_id',array(0,$client_id))->orderBy('id', 'DESC')->first()->toArray();
            // DB::enableQueryLog();
            $calendar_booking_edit_time = CalendarSetting::where(array('cs_business_id'=> Session::get('businessId'),'cs_client_id' => 0))->orderBy('id', 'DESC')->first()->toArray();
            
            $edit_time_limit = $calendar_booking_edit_time['cs_booking_active'];
            $closedDates = $this->getClosedDates();

        return view('Result.calendar.EventCalendar', compact('modalLocsAreas', 'locsAreas', 'ifClassesExit', 'ifServicesExit', 'eventRepeatIntervalOpt','staffs', 'staffHours','clients','closedDates','calendarSettingVal','edit_time_limit','availableCreditBalance'));

        }
}
