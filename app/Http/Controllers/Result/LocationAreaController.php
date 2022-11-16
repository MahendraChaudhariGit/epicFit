<?php
namespace App\Http\Controllers\Result;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Business;
use App\Location;
use App\LocationArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\Result\LocationAreaTrait;
use \stdClass;
use App\Http\Traits\StaffEventsTrait;
use Session;
use App\Http\Traits\Result\HelperTrait;
use DB;

use App\Http\Traits\ClosedDateTrait;

class LocationAreaController extends Controller{
	use LocationAreaTrait, HelperTrait, StaffEventsTrait, ClosedDateTrait;
	public function index($locationid){
		$areaLocationStaff = [];
		/*if(!Auth::user()->hasPermission(Auth::user(), 'list-location-area'))
            abort(404);*/

		$allLocationAreas = Location::find($locationid)->areas;
        if($allLocationAreas->count()){
    		foreach($allLocationAreas as $allLocationArea){
    			$areaStaffs = $allLocationArea->staffs;
    			if($areaStaffs->count()){
    				$i = 0;
    				foreach($areaStaffs as $areaStaff){
    					$areaLocationStaff[$allLocationArea['la_id']][$i] = $areaStaff->first_name . ' ' . $areaStaff->last_name;
    					$i++;
    				}
    			}
    		}
        }
		return view('Settings.location.index_area', compact('allLocationAreas','areaLocationStaff'));
    }
	
	public function uploadFile(Request $request){
		$locationAreaId = (int)$request->id;
        $loc = LocationArea::find($locationAreaId);
		$loc->update(array('la_logo' => $request->photoName));
        return url('/uploads/thumb_'.$request->photoName);
    }
	public function show($id){
		$locationAreaStaff = array();
		/*if(!Auth::user()->hasPermission(Auth::user(), 'view-location-area'))
            abort(404);*/

		$locationArea = LocationArea::findOrFail($id);
		$areaStaffs = $locationArea->staffs;
		if($areaStaffs)
			foreach($areaStaffs as $areaStaff)
				$locationAreaStaff[] = $areaStaff->first_name . ' ' . $areaStaff->last_name;

		return view('Settings.location.show_area',  compact('locationArea','locationAreaStaff'));
    }

    public function getStaffs(Request $request){

        $stff = [];

         //dd($request->startDate);

    	if(isset($request->startDate) ){

    		$area = LocationArea::find($request->areaId);
	        if($area){
	            $staffs = $area->staffs;
	            if($staffs){
	            	foreach($staffs as $staff){
	            		$staffActivityData = new stdClass();
        				$staffActivityData->staffId = $staff->id;
        				$staffActivityData->areaId = $request->areaId;
        				$staffActivityData->startDate = $request->startDate;

        				if(isset($request->day)){
        					$staffActivityData->day = $request->day;
        					if($this->staffHasDayActivity($staffActivityData))
        						$stff[$staff->id] = $staff->first_name. ' '.$staff->last_name;
        				}
        				else if(isset($request->endDate)){
        					$staffActivityData->endDate = $request->endDate;
        					if($this->staffHasWeekActivity($staffActivityData))
        						$stff[$staff->id] = $staff->first_name. ' '.$staff->last_name;
        				}
	            	}
	            }
	        }
    	}
    	else if(!isset($request->startDate))
    		$stff = $this->staffs($request->areaId);

    	return json_encode($stff);
    }

    public function hasRosteredStaffs(Request $request){
        //$this->neverEndAppointmentRepeats($request);
        $this->neverEndSingleServiceRepeats($request);
        $this->neverEndClassRepeats($request);

		$hasRostStaffs = 0;
        //if(Auth::user()->hasPermission(Auth::user(), 'list-staff')){
            if($request->areaId == 'all'){
                $idsObj = DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->distinct()->select('as_la_id', 'as_staff_id')->get();
                if(count($idsObj)){
                    foreach($idsObj as $idObj){
                        if($hasRostStaffs)
                            break;

                        $hasRostStaffs = $this->hasRosteredStaffsLogic($request, ['staffId' => $idObj->as_staff_id, 'areaId' => $idObj->as_la_id]);
                    }
                }
            }
            else{
        		$area = LocationArea::find($request->areaId);
        	    if($area){
        	        $staffs = $area->staffs;
        	        if($staffs->count()){
        	        	foreach($staffs as $staff){
        	        		if($hasRostStaffs)
        	        			break;

                            $hasRostStaffs = $this->hasRosteredStaffsLogic($request, ['staffId' => $staff->id, 'areaId' => $request->areaId]);
        	            }
        	        }
        	    }
            }
        //}
    	return $hasRostStaffs;
    }

     protected function neverEndSingleServiceRepeats($request){
        /*if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'create-staff-event-appointment'))
            return false;*/

        if(isUserType(['Staff']) && $request->has('areaId') && $request->areaId != 'all')
            $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->areaId, 'staffId' => Auth::user()->account_id]);
        else
            $isAreaLinkedToStaff = true;
        if(!$isAreaLinkedToStaff)
            return false;

        $colToSel = "sess_id, sess_user_id, sess_staff_id, sess_client_id, sess_date, sess_time, sess_booking_status, sess_auto_expire, sess_auto_expire_datetime, sess_notes, sess_start_datetime, sess_end_datetime, sess_parent_id, sess_service_id, sess_price, sess_duration, ser_event_type, ser_repeat, ser_repeat_interval, ser_repeat_week_days";
        $fromTable = "staff_event_single_services INNER JOIN staff_event_repeats ON (ser_event_id = sess_id AND ser_event_type = 'App\\\StaffEventSingleService')";
        $conditions = "sess_business_id = ".Session::get('businessId')." AND ser_repeat_end = 'Never' AND ser_child_count = 0 AND sess_date <= '$request->insertRepeatUpto' AND staff_event_single_services.deleted_at IS NULL AND staff_event_single_services.sess_client_id=".Auth::user()->account_id;

        if(isUserType(['Staff']))
            $conditions .= " AND sess_staff_id = ".Auth::user()->account_id;
        else if($request->has('staffId') && $request->staffId != 'all' && $request->staffId != 'all-ros')
            $conditions .= " AND sess_staff_id = $request->staffId";

        if($request->has('areaId') && $request->areaId != 'all'){
            $fromTable .= " INNER JOIN staff_event_single_service_areas ON sess_id = sessa_sess_id";
            $conditions .= " AND sessa_la_id = $request->areaId";
        }
        DB::enableQueryLog();
        $neverEndEvents = DB::select(DB::raw("(SELECT neverEndChildEvents.* FROM (SELECT $colToSel FROM $fromTable WHERE $conditions AND sess_parent_id != 0 ORDER BY sess_date DESC LIMIT 18446744073709551615) as neverEndChildEvents GROUP BY neverEndChildEvents.sess_parent_id) UNION (SELECT $colToSel FROM $fromTable WHERE $conditions AND sess_parent_id = 0)"));

       //dd(DB::getQueryLog());
       //dd($neverEndEvents);
        
        if(count($neverEndEvents)){
            foreach($neverEndEvents as $neverEndEvent){
                $dates = [];
                $eventDate = new Carbon($neverEndEvent->sess_date);
                $eventRepeatEndOnDate = new Carbon($request->insertRepeatUpto);
                while($eventDate->lte($eventRepeatEndOnDate)){
                    $param = ['eventDate' => $eventDate, 'eventRepeat' => $neverEndEvent->ser_repeat, 'repeatIntv' => $neverEndEvent->ser_repeat_interval];
                    
                    if($neverEndEvent->ser_repeat == 'Weekly')
                        $param['eventRepeatWeekdays'] = json_decode($neverEndEvent->ser_repeat_week_days);
                    
                    $dates[] = $this->calcRepeatsDate($param);
                }

                $this->getRepeatsDate($dates);
                array_splice($dates, 0, 1);

                $areas = [];
                if(count($dates)){
                    $closedDates = explode(',', $this->getClosedDates());
                    if(!count($areas))
                        $areas = DB::table('staff_event_single_service_areas')->where('sessa_sess_id', $neverEndEvent->sess_id)->whereNull('deleted_at')->select('sessa_business_id', 'sessa_la_id')->get();

                    $validDates = $repeatingEventAreaData = $repeatingEventRepeatData = [];
                    foreach($dates as $date){
                        if(!count($closedDates) || !in_array($date, $closedDates)){
                            $newEvent = new StaffEventSingleService;
                            $newEvent->sess_user_id = $neverEndEvent->sess_user_id;
                            $newEvent->sess_business_id = Session::get('businessId');
                            $newEvent->sess_date = $date;
                            $newEvent->sess_time = $neverEndEvent->sess_time;

                            $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $neverEndEvent->sess_time, 'startDate' => $date, 'duration' => $neverEndEvent->sess_duration]);
                            $newEvent->sess_start_datetime = $startAndEndDatetime['startDatetime'];
                            $newEvent->sess_end_datetime = $startAndEndDatetime['endDatetime'];

                            $newEvent->sess_notes = $neverEndEvent->sess_notes;
                            $newEvent->sess_staff_id = $neverEndEvent->sess_staff_id;
                            $newEvent->sess_client_id = $neverEndEvent->sess_client_id;
                            $newEvent->sess_booking_status = $neverEndEvent->sess_booking_status;
                            $newEvent->sess_auto_expire = $neverEndEvent->sess_auto_expire;
                            $newEvent->sess_auto_expire_datetime = $neverEndEvent->sess_auto_expire_datetime;
                            $newEvent->sess_service_id = $neverEndEvent->sess_service_id;
                            $newEvent->sess_duration = $neverEndEvent->sess_duration;
                            $newEvent->sess_price = $neverEndEvent->sess_price;

                            if(!$neverEndEvent->sess_parent_id)
                                $newEvent->sess_parent_id = $neverEndEvent->sess_id;
                            else
                                $newEvent->sess_parent_id = $neverEndEvent->sess_parent_id;

                            $newEvent->sess_is_repeating = 1;
                            
                            $newEvent->save();
                            //Auth::user()->eventAppointments()->save($newEvent);
                            $validDates[] = $date;

                            if(count($areas)){
                                foreach($areas as $area)
                                    $repeatingEventAreaData[] = ['sessa_business_id' => $area->sessa_business_id, 'sessa_sess_id' => $newEvent->sess_id, 'sessa_la_id' => $area->sessa_la_id];
                            }

                            $timestamp = createTimestamp();
                            $repeatingEventRepeatData[] = ['ser_event_id' => $newEvent->sess_id, 'ser_event_type' => $neverEndEvent->ser_event_type, 'ser_repeat' => $neverEndEvent->ser_repeat, 'ser_repeat_interval' => $neverEndEvent->ser_repeat_interval, 'ser_repeat_end' => 'Never', 'ser_repeat_week_days' => $neverEndEvent->ser_repeat_week_days, 'created_at' => $timestamp, 'updated_at' => $timestamp];
                        }
                    }

                    if(count($repeatingEventAreaData))
                        DB::table('staff_event_single_service_areas')->insert($repeatingEventAreaData);

                    if(count($repeatingEventRepeatData))
                        DB::table('staff_event_repeats')->insert($repeatingEventRepeatData);

                    if(!$neverEndEvent->sess_parent_id)
                        $eventId = $neverEndEvent->sess_id;
                    else
                        $eventId = $neverEndEvent->sess_parent_id;
                    
                    //StaffEventRepeat::ofSingleService()->where('ser_event_id', $eventId)->increment('ser_child_count', count($dates));
                    StaffEventRepeat::ofSingleService()->where('ser_event_id', $eventId)->increment('ser_child_count', count($validDates));
                }
            }
        }
    }
    protected function hasRosteredStaffsLogic($request, $data){
        $staffActivityData = new stdClass();
        $staffActivityData->staffId = $data['staffId'];
        $staffActivityData->areaId = $data['areaId'];
        $staffActivityData->startDate = $request->startDate;

        if(isset($request->day)){
            $staffActivityData->day = $request->day;
            if($this->staffHasDayActivity($staffActivityData))
                return 1;
        }
        else if(isset($request->endDate)){
            $staffActivityData->endDate = $request->endDate;
            if($this->staffHasWeekActivity($staffActivityData))
                return 1;
        }

        return 0;
    }

    public function edit($id){
        /*if(!Auth::user()->hasPermission(Auth::user(), 'edit-location-area'))
            abort(404);*/

        if(!Session::has('businessId'))
            return redirect('settings/business/create');

        $area = LocationArea::find($id);
        if($area){
            $area->hours = LocationArea::getHours($area->la_id);
            if(count($area->hours))
                $area->hours = json_encode($area->hours);

            $business = Business::with('locations', 'staffs')->find(Session::get('businessId'));
            $businessId = $business->id;

            $locs = array('' => '-- Select --');
            if($business->locations->count())
                foreach($business->locations as $location)
                    $locs[$location->id] = $location->location_training_area;

            $stff = array();
            if($business->staffs->count())			
				foreach($business->staffs as $staff)
					$stff[$staff->id] = $staff->first_name.' '.$staff->last_name;

            $aresStaffs = $area->staffs->pluck('id')->toArray();

            
            return view('Settings.location.edit', compact('area', 'businessId', 'locs', 'stff', 'aresStaffs'));
        }
    }

    public function update($id, Request $request){
        $isError = false;
        $msg = [];

       /* if(!Auth::user()->hasPermission(Auth::user(), 'edit-location-area')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }*/

        if(!$isError){
            $area = LocationArea::find($id);
            if($area){
                $area->la_location_id = $request->location;
                $area->la_name = $request->areaName;
                $area->la_logo = $request->areaLogo;
                $area->save();

                if($request->stuff_selection == '')
                    $attachedStaff = [];
                else
                  foreach($request->stuff_selection as $staffIds){
						$attachedStaff[$staffIds] = ['as_business_id' => Session::get('businessId')];	
				   }
				$area->staffs()->sync($attachedStaff);
				$this->setWorkingHours($request, ['mode' => 'edit', 'entityType' => 'area', 'entityId' => $id]);

                $msg['status'] = 'updated';
            }
        }
        return json_encode($msg);
    }

    public function create(){
     /*   if(!Auth::user()->hasPermission(Auth::user(), 'edit-location-area'))
            abort(404);*/

        if(!Session::has('businessId'))
            return redirect('settings/business/create');

        $business = Business::with('locations', 'staffs')->find(Session::get('businessId'));
        $businessId = $business->id;

        $locs = array('' => '-- Select --');
        if($business->locations->count())
            foreach($business->locations as $location)
                $locs[$location->id] = $location->location_training_area;

        $stff = array();
        if($business->staffs->count())          
            foreach($business->staffs as $staff)
                $stff[$staff->id] = $staff->first_name.' '.$staff->last_name;

        $entityType = 'area';
        
        return view('Settings.location.edit', compact('businessId', 'locs', 'stff', 'entityType'));
    }

    /*public function store(Request $request){   
        if($request->venue == 'Area'){
            $isError = false;
            $msg = [];

            if(!Auth::user()->hasPermission(Auth::user(), 'create-location-area')){
                if($request->ajax())
                    $isError = true;
                else
                    abort(404);
            }
            
            if(!$isError){
                $area = new LocationArea;
                $area->la_location_id = $request->location;
                $area->la_name = $request->areaName;
                $area->la_logo = $request->areaLogo;
                $area->save();

                if($request->stuff_selection != '')
                    $area->staffs()->attach($request->stuff_selection);   

                $this->setWorkingHours($request, ['mode' => 'add', 'entityType' => 'area', 'entityId' => $area->la_id]);

                $msg['status'] = 'added';
            }
            return json_encode($msg);
        }
    }*/
}