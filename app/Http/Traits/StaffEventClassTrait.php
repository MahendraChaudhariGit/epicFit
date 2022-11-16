<?php
namespace App\Http\Traits;

use App\Http\Requests;    
//use App\Http\Traits\StaffEventsTrait;
//use Auth;
use DB;
use Carbon\Carbon;
use App\LocationArea;
use App\StaffEventBusy;
use App\StaffEventSingleService;
use App\Clients;
use Session;
use Mail;
use App\Staff;
use App\Business;
use App\Clas;
use \stdClass;
use App\Service;

trait StaffEventClassTrait{
    //use StaffEventsTrait;

    protected function getCountOfAreasLinkedToStaff($data){        
        return DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->whereIn('as_la_id', $data['areaId'])->where('as_staff_id', $data['staffId'])->whereNull('deleted_at')->count();
    }

    /*protected function calcEventDate($request, $currDate){
        $eventNewDate = $currDate;
         
        if($request->has('eventRepeatWeekdays')){
            $eventDate = Carbon::parse($currDate);
            $eventDay = $eventDate->format('D');

            if(!in_array($eventDay, $request->eventRepeatWeekdays)){
                $eventRepeatWeekdays = array_intersect($this->weekDaysArr, $request->eventRepeatWeekdays);
                $eventDayIdx = array_search($eventDay, $this->weekDaysArr);
                
                $nearestDayIdx = 0;
                for($i=$eventDayIdx+1; $i<count($this->weekDaysArr); $i++){
                    if(array_key_exists($i, $eventRepeatWeekdays)){
                        $nearestDayIdx = $i;
                        break;
                    }
                }
                if(!$nearestDayIdx){
                    reset($eventRepeatWeekdays);
                    $nearestDayIdx = key($eventRepeatWeekdays);
                }

                $daysToAdd = $nearestDayIdx - $eventDayIdx;
                if($daysToAdd < 0)
                    $daysToAdd = $daysToAdd + count($this->weekDaysArr);

                $eventNewDate = $eventDate->addDays($daysToAdd)->format('Y-m-d');
            }
        }

        return $eventNewDate;
    }*/

    protected function areAreasBusy($data, $bookingType = ''){
        $preparedData = $this->prepareDataForClashingEvents($data, $bookingType);
        //$eventData = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];

        $eventAppoint = LocationArea::whereHas('events', function($query) use ($preparedData){
                                            $query->clashingEvents($preparedData['appointmentData']);
                                    })
                                    ->whereIn('la_id', $data['areaId'])
                                    ->select('la_id')
                                    ->pluck('la_id')
                                    ->toArray();                                  

        $eventBusy = StaffEventBusy::whereIn('seb_area_id', $data['areaId'])
                                  ->clashingEvents($preparedData['busyTimeData'])
                                  ->select('seb_area_id')
                                  ->pluck('seb_area_id')
                                  ->toArray();

        $eventClass = LocationArea::whereHas('eventClassess', function($query) use ($preparedData){
                                            $query->clashingEvents($preparedData['classData']);
                                    })
                                    ->whereIn('la_id', $data['areaId'])
                                    ->select('la_id')
                                    ->pluck('la_id')
                                    ->toArray();

        if(count($eventAppoint) && count($eventClass) && count($eventBusy))
            return array_unique(array_merge($eventAppoint, $eventClass, $eventBusy), SORT_NUMERIC);  
        else if(count($eventAppoint))                             
            return $eventAppoint;                  
        else if(count($eventClass))                             
            return $eventClass;  
        else if(count($eventBusy))                             
            return $eventBusy;  
        
        return []; 
    }

    protected function isClientBusy($data, $bookingType = ''){
        $preparedData = $this->prepareDataForClashingEvents($data, $bookingType);
        // dd($preparedData);
        //$eventAppointData = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];

        $eventAppoint = StaffEventSingleService::whereIn('sess_client_id', $data['clientId'])
                                                ->clashingEvents($preparedData['appointmentData'])
                                                ->select('sess_client_id')
                                                ->pluck('sess_client_id')
                                                ->toArray();                                 
                                                
        /* $eventClass = Clients::whereHas('eventClasses', function($query) use ($preparedData){*/
        $eventClass = Clients::whereIn('id', $data['clientId'])
                                ->whereHas('eventClassesWithoutTrashed', function($query) use ($preparedData){
                                    $query->clashingEvents($preparedData['classData']);
                               })
                             ->select('id')
                             ->pluck('id')
                             ->toArray();

        if(count($eventAppoint) && count($eventClass))
            return array_unique(array_merge($eventAppoint, $eventClass), SORT_NUMERIC);  
        else if(count($eventAppoint))                             
            return $eventAppoint;                  
        else if(count($eventClass))                             
            return $eventClass;  
        
        return [];                       
    }

    protected function sendClientEventBookingEmail($action, $request, $clients, $bookingType, $businessId = ''){
        $datetime = $request->eventDateTimeEmail;
        $subject = ($action == 'confirm')?'is confirmed':'has been cancelled';
        /*$locArea = LocationArea::with(array('staffsWithTrashed' => function($query) use($request){
                                        $query->where('as_staff_id', $request->staff);
                                    }, 'classesWithTrashed' => function($query) use($request){
                                        $query->where('ac_cl_id', $request->staffClass);
                                    }, 'locationWithTrashed.business'))
                                ->withTrashed()
                                ->find($request->modalLocArea);
        $locDetails = new stdClass();
        $locDetails->area = $locArea->la_name;
        $locDetails->name = $locArea->locationWithTrashed->location_training_area;
        $locDetails->addr1 = $locArea->locationWithTrashed->address_line_one;
        $locDetails->addr2 = $locArea->locationWithTrashed->address_line_two;
        $locDetails->city = $locArea->locationWithTrashed->city;
        $locDetails->state = \Country::getStateName($locArea->locationWithTrashed->country, $locArea->locationWithTrashed->state);
        $countries = \Country::getCountryLists();
        $locDetails->country = $countries[$locArea->locationWithTrashed->country];

        $className = $locArea->classesWithTrashed[0]->cl_name;
        $staffName = $locArea->staffsWithTrashed[0]->first_name.' '.$locArea->staffsWithTrashed[0]->last_name;
        $businessName = $locArea->locationWithTrashed->business->trading_name;*/

        $locAreas = LocationArea::with('locationWithTrashed')
                                ->withTrashed()
                                ->find($request->modalLocArea);
        $i = 0;
        $locDetails = [];
        $countries = \Country::getCountryLists();
        foreach($locAreas as $locArea){
            $locDetails[$i]['area'] = $locArea->la_name;
            $locDetails[$i]['name'] = $locArea->locationWithTrashed->location_training_area;
            $locDetails[$i]['addr1'] = $locArea->locationWithTrashed->address_line_one;
            $locDetails[$i]['addr2'] = $locArea->locationWithTrashed->address_line_two;
            $locDetails[$i]['city'] = $locArea->locationWithTrashed->city;
            $locDetails[$i]['state'] = \Country::getStateName($locArea->locationWithTrashed->country, $locArea->locationWithTrashed->state);
            $locDetails[$i]['country'] = $countries[$locArea->locationWithTrashed->country];
            $i++;
        }

        if($bookingType == 'class')
            $className = Clas::withTrashed()->find($request->staffClass)->cl_name;
        else if($bookingType == 'service')
            $className = Service::withTrashed()->find($request->staffservice)->Name;

        $staffName = Staff::withTrashed()->find($request->staff)->FullName;
        $businessName = isset(Business::find(isset($businessId)? $businessId : Session::get('businessId'))->trading_name) ? Business::find(isset($businessId)? $businessId : Session::get('businessId'))->trading_name : '';

        $historyText = '';

        foreach($clients as $client){
            if($client->email){
                $clientDetails = new stdClass();
                $clientDetails->name = $client->firstname.' '.$client->lastname;
                $clientDetails->email = $client->email;
                $clientDetails->number = $client->phonenumber;

                Mail::send('calendar-new.client_event_booking_email', compact('clientDetails', 'action', 'datetime', 'locDetails', 'businessName', 'className', 'staffName', 'bookingType'), function($message) use($clientDetails, $request, $subject){
                    $message->to($clientDetails->email, $clientDetails->name)->subject(app_name().': Your booking on '.$request->eventDateTimeEmail.' '.$subject);
                });

                if($action == 'confirm')
                    $historyText .= '  Client confirmation email successfully sent to '.$client->email.'|';
                else
                    $historyText .= '  Client cancellation email successfully sent to '.$client->email.'|';
            }
        }
        return $historyText;
    }

    protected function eventclassAreaHistory($data){
        $text = '';

        $subText = $this->calcHistoryTextFromAction($data['action']);

        foreach($data['areas'] as $area)
            $text .= $area->la_name.$subText.'|';
        
        return $text;
    }

    protected function eventclassClientHistory($data){
        $text = '';
        if(!array_key_exists('additional', $data))
            $data['additional'] = "";

        $subText = $this->calcHistoryTextFromAction($data['action'], $data['additional'], array_key_exists('isRecureClient', $data) ? $data['isRecureClient'] : '');
        // dd($data);
        foreach($data['clients'] as $client)
            $text .= $client->firstname.' '.$client->lastname.$subText.'|';
        
        return $text;
    }

    protected function manageAreasLinkage($event, $areasId){
        $historyText = '';

        $allPrevAreas = $event->areas;  
        if($allPrevAreas->count())
            $allPrevAreasId = $allPrevAreas->pluck('la_id')->toArray();
        else
            $allPrevAreasId = [];


        if(count($allPrevAreasId) && count($areasId))
            $removedAreasId = array_diff($allPrevAreasId, $areasId);   
        else if(count($allPrevAreasId) && !count($areasId))
            $removedAreasId = $allPrevAreasId;
        else
            $removedAreasId = [];
        if(count($removedAreasId)){
            $removedAreas = $allPrevAreas->filter(function($value, $key) use ($removedAreasId){
                return in_array($value->la_id, $removedAreasId);
            });

            $event->areas()->detach($removedAreasId);
            $historyText .= $this->eventclassAreaHistory(['areas' => $removedAreas, 'action' => 'remove']);
        }


        if(count($allPrevAreasId) && count($areasId))
            $addedAreasId = array_diff($areasId, $allPrevAreasId); 
        else if(!count($allPrevAreasId) && count($areasId))
            $addedAreasId = $areasId;
        else
            $addedAreasId = [];
        if(count($addedAreasId)){
            $attachedArea = [];

            $eventTable = $event->getTable();
            if($eventTable == 'staff_event_classes')
                $bussCol = 'seca_business_id';
            else if($eventTable == 'staff_event_single_services')
                $bussCol = 'sessa_business_id';

            foreach($areasId as $areaId)
                if(in_array($areaId, $addedAreasId))
                    $attachedArea[$areaId] = [$bussCol => Session::get('businessId')]; 
            if(count($attachedArea)){
                $addedAreas = LocationArea::find($addedAreasId);
                $event->areas()->attach($attachedArea); 
                $historyText .= $this->eventclassAreaHistory(['areas' => $addedAreas, 'action' => 'add']);
            }
        }

        return ['history' => $historyText, 'prevAreas' => $allPrevAreasId];
    }

    protected function ifEventUpdated($oldEntityIds, $newEntityIds){
        if(count($oldEntityIds) != count($newEntityIds))
            return true;

        return count(array_diff($oldEntityIds, $newEntityIds));        
    }

    protected function copyDeletedClassClients($colsVal, $makeup = false){
        if(is_array($colsVal)){
            $where = "secc_sec_id = $colsVal[0] and secc_client_id in (".implode(',', $colsVal[1]).")";
            if($makeup)
                $where .= " and secc_epic_credit = 1";
        }
        else{
            $where = "(secc_sec_id, secc_client_id";
            if($makeup)
                $where .= ", secc_epic_credit";
            $where .= ") in ($colsVal)";
        }
        //DB::statement("(INSERT INTO deleted_event_class_clients (`secc_id`, `secc_sec_id`, `secc_client_id`, `secc_notes`, `secc_reduce_rate_session`, `secc_if_recur`, `secc_if_make_up`, `secc_if_make_up_created`, `secc_is_make_up_client`, `secc_client_attendance`, `secc_client_status`, `created_at`, `updated_at`, `deleted_at`) SELECT `secc_id`, `secc_sec_id`, `secc_client_id`, `secc_notes`, `secc_reduce_rate_session`, `secc_if_recur`, `secc_if_make_up`, `secc_if_make_up_created`, `secc_is_make_up_client`, `secc_client_attendance`, `secc_client_status`, `created_at`, `updated_at`, `deleted_at` FROM staff_event_class_clients where ($whereCols) in ($colsVal))");
        DB::statement("INSERT INTO deleted_event_class_clients (`secc_id`, `secc_sec_id`, `secc_client_id`, `secc_notes`, `secc_epic_credit`, `secc_reduce_rate_session`, `secc_reduce_rate`, `secc_if_recur`, `secc_if_make_up`, `secc_if_make_up_created`, `secc_client_attendance`, `secc_client_status`, `secc_invoice_status`, `created_at`, `updated_at`, `deleted_at`) SELECT `secc_id`, `secc_sec_id`, `secc_client_id`, `secc_notes`, `secc_epic_credit`, `secc_reduce_rate_session`, `secc_reduce_rate`, `secc_if_recur`, `secc_if_make_up`, `secc_if_make_up_created`, `secc_client_attendance`, `secc_client_status`, `secc_invoice_status`, `created_at`, `updated_at`, `deleted_at` FROM staff_event_class_clients where $where");
    }

    /*protected function decreRepeat($repeat, $childCount = -1){
        if($repeat->ser_repeat_end_after_occur)
            $repeat->ser_repeat_end_after_occur--;

        if($childCount < 0)
            $repeat->ser_child_count--;
        else
            $repeat->ser_child_count = $childCount;

        $repeat->save();
    }*/

}