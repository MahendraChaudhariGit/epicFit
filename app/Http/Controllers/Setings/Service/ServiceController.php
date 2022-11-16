<?php
namespace App\Http\Controllers\Setings\Service;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Business;
use App\Service;
use App\Staff;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Http\Traits\HelperTrait;
use App\StaffEventService;
use Input;
use App\Http\Traits\StaffEventsTrait;
use App\StaffEventBusy;
use App\StaffEventSingleService;
use App\StaffEventClass;
use App\LocationArea;
//use App\Resource;
use App\StaffEvent;
use App\Http\Traits\ServiceResourceTrait;
use App\Http\Traits\ResourceTrait;
//use App\ServiceResources;
use App\Http\Traits\StaffEventResourceTrait;
//use App\StaffEventResource;
use App\Http\Traits\StaffTrait;
use App\CalendarSetting;

class ServiceController extends Controller{
	use HelperTrait, StaffTrait, StaffEventsTrait,ServiceResourceTrait, ResourceTrait, StaffEventResourceTrait;

	private $cookieSlug = 'service';

    public function index(Request $request){
    	if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-service'))
            abort(404);

        //if(Session::has('businessId')){
        	$services = array();
        	$search = $request->get('search');

        	$length = $this->getTableLengthFromCookie($this->cookieSlug);
            //$services = Service::with('locationn')->where('business_id', Session::get('businessId'))->join('service_cats', 'sc_id', '=', 'category')->select('service_cats.sc_value', 'services.*')->paginate($length);
            if($search)
    			$services = Service::with('locationn')->OfBusiness()->where(function($query) use ($search){$query->orWhere('one_on_one_name', 'like', "%$search%")->orWhere('team_name', 'like', "%$search%");})->joinCats()->paginate($length);
    		else
            	$services = Service::with('locationn')->OfBusiness()->joinCats()/*join('service_cats', 'sc_id', '=', 'category')->select('service_cats.sc_value', 'services.*')*/->paginate($length);
        	//$services = Service::with('locationn')->where('business_id', Session::get('businessId'))->join('service_cats', 'sc_id', '=', 'category')->select('service_cats.sc_value', 'services.*')->get();
        //}

       	return view('Settings.service.service.index', compact('services'));
    }

    public function all(){
        /*if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-service'))
            abort(404);

        $services = Service::OfBusiness()
        					->complOnly()
                            ->where(function($query){
                                $query->where('one_on_one_staffs', '<>', '')
                                      ->orWhere('team_staffs', '<>', '');
                            })
                            ->get(array('id', 'one_on_one_staffs', 'one_on_one_name', 'one_on_one_duration', 'one_on_one_price', 'team_staffs', 'team_name', 'team_duration', 'team_price'));*/

        $services = [];
        if(isUserType(['Staff'])){
        	$services = Staff::getServices(['staffId' => Auth::user()->pk, 'complOnly' => 1]);
            if(count($services))
            	$services = Service::select('id', 'one_on_one_name', 'one_on_one_duration', 'one_on_one_price', 'team_name', 'team_duration', 'team_price', 'for_sales_process_step')->find($services); //, 'one_on_one_staffs', 'team_staffs'
        }
        else if(eligibleAccounts(['Admin']) || Session::get('hostname') == 'result'){
        	$services = Service::OfBusiness()
    					->complOnly()
                        ->where(function($query){
                            $query->where('one_on_one_staffs', '<>', '')
                                  ->orWhere('team_staffs', '<>', '');
                        })
                        ->get(array('id', 'one_on_one_name', 'one_on_one_duration', 'one_on_one_price', 'team_name', 'team_duration', 'team_price', 'for_sales_process_step')); //, 'one_on_one_staffs' //, 'team_staffs'
        }

        
        return $this->prepareServiceList($services);
	}
	
	public function allServiceByCondition(Request $request){
		$services = [];
        	$services = Service::OfBusiness()
    					->complOnly()
                        ->where(function($query){
                            $query->where('one_on_one_staffs', '<>', '')
                                  ->orWhere('team_staffs', '<>', '');
                        })
                        ->get(array('id', 'one_on_one_name', 'one_on_one_duration', 'one_on_one_price','one_on_one_tax', 'team_name', 'team_duration', 'team_price','team_tax', 'for_sales_process_step')); //, 'one_on_one_staffs' //, 'team_staffs'
        return $this->prepareServiceList($services);
	}

    public function getOfClient($clientId, Request $request){
    	/*$linkedServices = StaffEventSingleService::ofBusiness()->where('sess_client_id', $clientId)->where('sess_service_id', '!=', $request->bookedService)->select('sess_service_id')->get();
    	if($linkedServices->count()){
    		$linkedServices = $linkedServices->pluck('sess_service_id')->toArray();
    		$linkedServices = array_unique($linkedServices);
    	}
    	else
    		$linkedServices = [];*/
    	$linkedServices = $this->getServiceBookedToClient($clientId, $request->bookedService);

    	$services = [];
    	$services = Service::OfBusiness()
							->complOnly()
			                ->where(function($query){
			                    $query->where('one_on_one_staffs', '<>', '')
			                          ->orWhere('team_staffs', '<>', '');
			                })
			                ->where(function($query) use($linkedServices){
			                    $query->where('for_sales_process_step', 0)
			                          ->orWhereNotIn('id', $linkedServices);
			                })
			                ->get(array('id', 'one_on_one_name', 'one_on_one_duration', 'one_on_one_price', 'team_name', 'team_duration', 'team_price', 'for_sales_process_step'));
        
        return $this->prepareServiceList($services);
    }

    public function getOfBooking($bookingId){
    	$service = Service::whereHas('eventsWithTrashed', function($query) use($bookingId){
								$query->where('sess_id', $bookingId);
							})
    						->get();  

    	return $this->prepareServiceList($service);  						
    }

    public function getOfSalesProcessStep($step){
        if(Session::get('hostname') == 'crm')
            if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-service'))
                abort(404);

        //if($step >=2 && $step <= 5){
        	if(in_array($step, indivBookingSteps()) || in_array($step, indivAttendSteps()))
        		$step = 0;
        	else if($step%2 == 1)
        		$step--;
        	
	        $services = Service::OfBusiness()
	        					->complOnly()
	        					->where('for_sales_process_step', $step)
	        					->where(function($query){
		                            $query->where('one_on_one_staffs', '<>', '')
		                                  ->orWhere('team_staffs', '<>', '');
		                        })
	                            ->get(array('id', 'one_on_one_name', 'one_on_one_duration', 'one_on_one_price', 'team_name', 'team_duration', 'team_price', 'for_sales_process_step')); //, 'one_on_one_staffs'  , 'team_staffs'
		/*}
		else if($step >= 6 && $step <= 9){
			$services = Service::OfBusiness()
        					->complOnly()
        					->where('team_staffs', '<>', '')
        					->where('category', 1)
                            ->get(array('id', 'team_name', 'team_duration', 'team_price')); //, 'team_staffs'
		}*/
		return $this->prepareServiceList($services);                               
    }

    protected function prepareServiceList($services){
    	$serv = [];
    	if($services->count()){
            $i = 0;
            foreach($services as $service){
            	$serv[$i]['id'] = $service->id;
            	$serv[$i]['forSalesProcessStep'] = $service->for_sales_process_step;

            	/*if($service->one_on_one_staffs != ''){
	                $serv[$i]['name'] = $service->one_on_one_name;
	                //$serv[$i]['duration'] = $service->one_on_one_duration;
	                //$serv[$i]['price'] = $service->one_on_one_price;
	            }
	            else if($service->team_staffs != ''){
	            	$serv[$i]['name'] = $service->team_name;
	                //$serv[$i]['duration'] = $service->team_duration;
	                //$serv[$i]['price'] = $service->team_price;
	            }*/

	            if($service->one_on_one_name != '')
	            	$serv[$i]['name'] = $service->one_on_one_name;
	            else if($service->team_name != '')
	            	$serv[$i]['name'] = $service->team_name;

	            if($service->one_on_one_duration != '')
	            	$serv[$i]['duration'] = $service->one_on_one_duration;
	            else if($service->team_duration != '')
	            	$serv[$i]['duration'] = $service->team_duration;

	            if($service->one_on_one_price != '')
	            	$serv[$i]['price'] = $service->one_on_one_price;
	            else if($service->team_price != '')
					$serv[$i]['price'] = $service->team_price;
					
				if($service->one_on_one_tax != '')
	            	$serv[$i]['tax'] = $service->one_on_one_tax;
	            else if($service->team_tax != '')
	            	$serv[$i]['tax'] = $service->team_tax;

                $i++;
            }
        }
        if(Session::get('hostname') == 'result'){
            $calendarSettingVal = CalendarSetting::where('cs_business_id',Session::get('businessId'))->first()->toArray();
            $serv['initial_status']['cs_initial_status'] = $calendarSettingVal['cs_initial_status'];
        }
        return json_encode($serv);
    }

    public function freeStaffs2($id, Request $request){
        $staffsId = $stff = [];

        if(isUserType(['Staff'])){
        	$services = Staff::getServices(['staffId' => Auth::user()->pk, 'complOnly' => 1]);
        	if(count($services) && in_array($id, $services))
        		$staffsId = [Auth::user()->pk];
        }
        else if(eligibleAccounts(['Admin']) || Session::get('hostname') == 'result'){
        	$service = Service::OfBusiness()->select(['one_on_one_staffs', 'team_staffs'])->find($id);
        	if($service){
        		if($service->one_on_one_staffs)
        			$staffsId = explode(',', $service->one_on_one_staffs);
        		else if($service->team_staffs)
        			$staffsId = explode(',', $service->team_staffs);
        	}
        }

        if(count($staffsId)){
            /*if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-staff')){*/
    		$date = $request->date;
    		$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $date, 'duration' => $request->duration]);

			$eventStartDatetime = $startAndEndDatetime['startDatetime'];
            $eventEndDatetime = $startAndEndDatetime['endDatetime'];
            $startTime = $this->datetimeToTime($eventStartDatetime);
            $endTime = $this->datetimeToTime($eventEndDatetime);

            $workingStaffsId = $this->getWorkingStaffs($staffsId, ['type'=>'service','day'=>$request->day,'date'=>$date, 'startTime'=>$startTime, 'endTime'=>$endTime]);

            if(count($workingStaffsId)){
                $data = ['startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime];
                
				$busyStaffs = StaffEventBusy::clashingEvents($data)->get();
				$busyStaffsId =[];
				foreach($busyStaffs as $key){
					$busyStaffsId = array_merge($busyStaffsId,$key->staff->pluck('id')->toArray());
				 }
                if(count($busyStaffsId))
                    $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);
				 
                if(count($workingStaffsId)){
                	if($request->has('eventId'))
						$data['eventId'] = $request->eventId;
                    $busyStaffsId = StaffEventSingleService:://whereIn('sess_staff_id', $workingStaffsId)
                    										OfStaffs($workingStaffsId)
				                                            ->clashingEvents($data)
				                                            ->select('sess_staff_id')
				                                            ->pluck('sess_staff_id')
				                                            ->toArray();        
                    if(count($busyStaffsId))
                        $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  
                        
                    if(count($workingStaffsId)){
                        $busyStaffsId = StaffEventClass:://whereIn('sec_staff_id', $workingStaffsId)
                        								OfStaffs($workingStaffsId)
		                                                ->clashingEvents($data)
		                                                ->select('sec_staff_id')
		                                                ->pluck('sec_staff_id')
		                                                ->toArray();       
                        if(count($busyStaffsId))
                            $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  

                        if(count($workingStaffsId)){
                            $staffs = Staff::select('id', 'first_name', 'last_name')->find($workingStaffsId);

                            if($staffs->count())
                                foreach($staffs as $staff)
                                	$stff[$staff->id] = ['name'=>$staff->fullName];
                                    //$stff[$staff->id] = $staff->first_name. ' '.$staff->last_name;
                        }                                                           
                    }                                                           
                }
            }                        
	    }
        return $stff;
    }

    public function freeAreas2($id, Request $request){
    	//DB::enableQueryLog();
        $locsAreas = $areasId = [];

        $service = Service::OfBusiness()->select('srvc_la_id')->find($id);
        if($service)
        	$areasId = explode(',', $service->srvc_la_id);

        if(isUserType(['Staff'])){
            $areas = LocationArea::whereHas('staffs', function($query){
                                        $query->where('id', Auth::user()->pk);
                                  })
                                  ->OfBusiness()
                                  ->distinct()
                                  ->select('la_id')
                                  ->pluck('la_id')
                                  ->toArray();

            $areasId = array_intersect($areas, $areasId);
        }

        /*if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-location-area')){
        	$service = Service::OfBusiness()->select('srvc_la_id')->find($id);
        	if($service){
        		$areasId = explode(',', $service->srvc_la_id);*/
        	if(count($areasId)){
        		$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $request->date, 'duration' => $request->duration]);
				$eventStartDatetime = $startAndEndDatetime['startDatetime'];
				$eventEndDatetime = $startAndEndDatetime['endDatetime'];
				$startTime = $this->datetimeToTime($eventStartDatetime);
	            $endTime = $this->datetimeToTime($eventEndDatetime);
	        	$workingAreasId = DB::table('hours')
										->where('hr_entity_type', 'area')
	                             		->whereIn('hr_entity_id', $areasId)
	                             		->where('hr_day', $request->day)
	                             		->where('hr_start_time', '<=', $startTime)
	                             		->where('hr_end_time', '>=', $endTime)
	                             		->whereNull('deleted_at')
	                             		->distinct()
                                        ->select('hr_entity_id')
                                        ->pluck('hr_entity_id')
                                        ->toArray();
                if(count($workingAreasId)){
	                $data = ['startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime];
	                
	                $busyAreasId = StaffEventBusy::whereIn('seb_area_id', $workingAreasId)
	                                            ->clashingEvents($data)
	                                            ->select('seb_area_id')
	                                            ->pluck('seb_area_id')
	                                            ->toArray();
	                if(count($busyAreasId))
	                    $workingAreasId = array_diff($workingAreasId, $busyAreasId);

	                if(count($workingAreasId)){
	                	if($request->has('eventId'))
	                    	$data['eventId'] = $request->eventId;
	                    $busyAreasId = LocationArea::whereHas('events', function($query) use ($data){
                                                            $query->clashingEvents($data);
														})
														->whereIn('la_id', $workingAreasId)
														->select('la_id')
														->pluck('la_id')
														->toArray();
	                    if(count($busyAreasId))
	                        $workingAreasId = array_diff($workingAreasId, $busyAreasId);  
	                        
	                    if(count($workingAreasId)){
	                        $busyAreasId = LocationArea::whereHas('eventClassess', function($query) use ($data){
                                                            $query->clashingEvents($data);
														})
														->whereIn('la_id', $workingAreasId)
														->select('la_id')
														->pluck('la_id')
														->toArray();    
	                        if(count($busyAreasId))
	                            $workingAreasId = array_diff($workingAreasId, $busyAreasId);  

	                        if(count($workingAreasId)){
	                        	$areas = LocationArea::with('location')->find($workingAreasId);

	                            if($areas->count()){
	                                foreach($areas as $area)
	                                    $locsAreas[$area->la_id] = $area->location->location_training_area.' - '.$area->la_name;
	                            }
	                        }                                                           
	                    }                                                           
	                }
	            }                        
        	}
        //}
        //dd(DB::getQueryLog());
        return $locsAreas;
    }

    public function freeStaffs(Request $request){
        $stff = [];
        if((isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-staff')) || Session::get('hostname') == 'result'){
        	$staffs = Service::OfBusiness()->select(['one_on_one_staffs', 'team_staffs'])->find($request->services)->pluck('one_on_one_staffs', 'team_staffs');
        	if($staffs->count()){
        		$staffsId = [];
        		foreach($staffs as $key => $value){
        			if($key)
        				$staffsId[] = explode(',', $key);
        				//$staffsId[] = $key;
        			else if($value)
        				//$staffsId[] = $value;
        				$staffsId[] = explode(',', $value);
        		}	
	        	if(count($staffsId) > 1)
	        		$staffsId = call_user_func_array('array_intersect', $staffsId);
	        	else
	        		$staffsId = $staffsId[0];
	        	

        		$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->serviceTime, 'startTimeForEnd' => $request->lastServiceTime, 'startDate' => $request->date, 'duration' => $request->duration]);
				
                $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                $eventEndDatetime = $startAndEndDatetime['endDatetime'];
                $startTime = $this->datetimeToTime($eventStartDatetime);
                $endTime = $this->datetimeToTime($eventEndDatetime);

                $workingStaffsId = $this->getWorkingStaffs($staffsId, ['type'=>'service','day'=>$request->day,'date'=>$request->date, 'startTime'=>$startTime, 'endTime'=>$endTime]);
	        	
                if(count($workingStaffsId)){
	                $data = ['startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime];
	                
	                $busyStaffsId = StaffEventBusy::whereIn('seb_staff_id', $workingStaffsId)
	                                            ->clashingEvents($data)
	                                            ->select('seb_staff_id')
	                                            ->pluck('seb_staff_id')
	                                            ->toArray();
	                if(count($busyStaffsId))
	                    $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);

	                if(count($workingStaffsId)){
	                    $busyStaffsId = StaffEvent::whereIn('se_staff_id', $workingStaffsId)
	                                            ->clashingEvents($data)
	                                            ->select('se_staff_id')
	                                            ->pluck('se_staff_id')
	                                            ->toArray();        
	                    if(count($busyStaffsId))
	                        $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  
	                        
	                    if(count($workingStaffsId)){
	                        if($request->has('eventId'))
	                            $data['eventId'] = $request->eventId;
	                        $busyStaffsId = StaffEventClass:://whereIn('sec_staff_id', $workingStaffsId)
	                        								OfStaffs($workingStaffsId)
			                                                ->clashingEvents($data)
			                                                ->select('sec_staff_id')
			                                                ->pluck('sec_staff_id')
			                                                ->toArray();       
	                        if(count($busyStaffsId))
	                            $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  

	                        if(count($workingStaffsId)){
	                            $staffs = Staff::find($workingStaffsId);

	                            if($staffs->count())
	                                foreach($staffs as $staff)
	                                    $stff[$staff->id] = $staff->first_name. ' '.$staff->last_name;
	                        }                                                           
	                    }                                                           
	                }
	            }                        
        	}
        }

        return $stff;
    }

    public function freeAreas(Request $request){
        //DB::enableQueryLog();
        $locsAreas = [];
        if((isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-location-area')) || Session::get('hostname') == 'result'){
        	$areas = Service::OfBusiness()->select('srvc_la_id')->find($request->services)->pluck('srvc_la_id')->toArray();
        	if(count($areas)){
        		$areasId = [];
	        	foreach($areas as $area)
	        		$areasId[] = explode(',', $area);
	        	if(count($areasId) > 1)
	        		$areasId = call_user_func_array('array_intersect', $areasId);
	        	else
	        		$areasId = $areasId[0];
        		
        		$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->serviceTime, 'startTimeForEnd' => $request->lastServiceTime, 'startDate' => $request->date, 'duration' => $request->duration]);
				$eventStartDatetime = $startAndEndDatetime['startDatetime'];
				$eventEndDatetime = $startAndEndDatetime['endDatetime'];
				$startTime = $this->datetimeToTime($eventStartDatetime);
	            $endTime = $this->datetimeToTime($eventEndDatetime);
	        	$workingAreasId = DB::table('hours')
										->where('hr_entity_type', 'area')
	                             		->whereIn('hr_entity_id', $areasId)
	                             		->where('hr_day', $request->day)
	                             		->where('hr_start_time', '<=', $startTime)
	                             		->where('hr_end_time', '>=', $endTime)
	                             		->whereNull('deleted_at')
	                             		->distinct()
                                        ->select('hr_entity_id')
                                        ->pluck('hr_entity_id');
                if(count($workingAreasId)){
	                $data = ['startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime];
	                
	                $busyAreasId = StaffEventBusy::whereIn('seb_area_id', $workingAreasId)
	                                            ->clashingEvents($data)
	                                            ->select('seb_area_id')
	                                            ->pluck('seb_area_id')
	                                            ->toArray();
	                if(count($busyAreasId))
	                    $workingAreasId = array_diff($workingAreasId, $busyAreasId);

	                if(count($workingAreasId)){
	                    $busyAreasId = StaffEvent::whereIn('se_area_id', $workingAreasId)
	                                            ->clashingEvents($data)
	                                            ->select('se_area_id')
	                                            ->pluck('se_area_id')
	                                            ->toArray();        
	                    if(count($busyAreasId))
	                        $workingAreasId = array_diff($workingAreasId, $busyAreasId);  
	                        
	                    if(count($workingAreasId)){
	                        if($request->has('eventId'))
	                            $data['eventId'] = $request->eventId;
	                        $busyAreasId = LocationArea::whereHas('eventClassess', function($query) use ($data){
                                                            $query->clashingEvents($data);
														})
														->whereIn('la_id', $workingAreasId)
														->select('la_id')
														->pluck('la_id')
														->toArray();    
	                        if(count($busyAreasId))
	                            $workingAreasId = array_diff($workingAreasId, $busyAreasId);  

	                        if(count($workingAreasId)){
	                        	$areas = LocationArea::with('location')->find($workingAreasId);

	                            if($areas->count()){
	                                foreach($areas as $area)
	                                    $locsAreas[$area->la_id] = $area->location->location_training_area.' - '.$area->la_name;
	                            }
	                        }                                                           
	                    }                                                           
	                }
	            }                        
        	}
        }

        return $locsAreas;
    }

    public function uploadFile(Request $request){
    	//$service = Service::findService($request->id);
    	//if($service && Auth::user()->hasPermission(Auth::user(), 'edit-service')){
    	$service = Service::find($request->id);
    	if($service){
	        //$serviceId = (int)$request->id;
	        //$service = Service::find($serviceId);
	        if($service->category == 2) //one on one
	        	$service->update(array('one_on_one_training_logo' => $request->photoName));
	        
	        else if($service->category == 1) //team training
	        	$service->update(array('team_training_logo' => $request->photoName));
	        
	        return url('/uploads/thumb_'.$request->photoName);
	    }
	    return '';
    }

    /*public function subview(Request $request){
    	if(!Auth::user()->hasPermission(Auth::user(), 'create-service'))
            abort(404);

        if(isset($request->bussId) && $request->bussId != ''){
            $businessId =  $request->bussId;
            $serviceTypes = ['' => '-- Select --'] + Service::getServiceTypes($businessId);
            $serviceCats = ['' => '-- Select --'] + Service::getServiceCats($businessId);

            $business = Business::findOrFail($businessId);
            $locations = $business->locations;
			$locs = array('' => '-- Select --');
			foreach($locations as $location)
				$locs[$location->id] = $location->location_training_area;

			$staffs = $business->staffs;
			$stff = array();
			foreach($staffs as $staff)
				$stff[$staff->id] = $staff->first_name.' '.$staff->last_name;

            return view('Settings.service.service.subview_form', compact('businessId', 'serviceTypes', 'serviceCats', 'locs', 'stff'));
        }
    }*/

    public function store(Request $request){
    	$isError = false;
        $msg = [];

        if($request->businessId != Session::get('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-service')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){		
			$insertData = array('business_id' => $request->businessId, 'type' => $request->type, 'category' => $request->category, 'location' => $request->location, 'is_completed' => 1);
			
			if(isset($request->area) && $request->area != '')
	            $insertData['srvc_la_id'] = implode(',',$request->area);

			if($request->catText == '1 on 1'){
				$insertData['one_on_one_name'] = $request->one_on_one_name;
				$insertData['one_on_one_description'] = $request->one_on_one_description;
				$insertData['one_on_one_training_logo'] = $request->oneOnoneLogo;
				$insertData['one_on_one_colour'] = $request->one_on_one_colour;
				if($request->one_on_one_call_client_online)
					$insertData['one_on_one_call_client_online'] = $request->one_on_one_call_client_online;
				$insertData['one_on_one_price'] = $request->one_on_one_price;
				$insertData['one_on_one_duration'] = $request->one_on_one_duration_h * 60 + $request->one_on_one_duration_m;
				//$insertData['one_on_one_capacity'] = $request->one_on_one_capacity;
				$insertData['one_on_one_tax'] = $request->one_on_one_tax;
				if($request->one_on_one_staffs && $request->one_on_one_staffs != '')
					$insertData['one_on_one_staffs'] = implode(',',$request->one_on_one_staffs);
			}
			else if($request->catText == 'TEAM Training'){
				$insertData['team_name'] = $request->team_name;
				$insertData['team_description'] = $request->team_description;
				$insertData['team_training_logo'] = $request->teamLogo;
				$insertData['team_colour'] = $request->team_colour;
				if($request->team_can_book_online)
					$insertData['team_can_book_online'] = $request->team_can_book_online;
				$insertData['team_price'] = $request->team_price;
				$insertData['team_duration'] = $request->team_duration_h*60 + $request->team_duration_m;
				//$insertData['team_capacity'] = $request->team_capacity;
				$insertData['team_tax'] = $request->team_tax;
				if($request->team_staffs && $request->team_staffs != '')
					$insertData['team_staffs'] = implode(',',$request->team_staffs);
			}
				
			//$business = Business::find($request->businessId);
			//$addedService = $business->services()->create($insertData);
			

			$addedService = Service::create($insertData);
			Session::put('ifBussHasServices' , true); 
			$this->storeResources($addedService->id,$request,'create','App\Service');

			$msg['status'] = 'added';
            $msg['insertId'] = $addedService->id;
        }

        return json_encode($msg);
    }
	
	public function typeSave(Request $request){
		if(!Auth::user()->hasPermission(Auth::user(), 'create-service-type')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }

		$this->validate($request, ['value' => 'required']);
		$serviceType = trim($request->value);
		$canInsert = false;
		$serviceTypes = Service::getServiceTypes($request->ownerId);
		if(!empty($serviceTypes)){
			$serviceTypesTemp = array_values($serviceTypes);
			if(!in_array(strtolower($serviceType), array_map('strtolower', $serviceTypesTemp)))
				$canInsert = true;
		}
		else
			$canInsert = true;
		if($canInsert){
			$insId = DB::table('service_types')->insertGetId(
				['st_value' => $serviceType, 'st_business_id' => $request->ownerId, 'created_at' => 'now()', 'updated_at' => 'now()']
			);
			return $insId;
		}
		else return '0';
    }
	
	public function catSave(Request $request){
		if(!Auth::user()->hasPermission(Auth::user(), 'create-service-category')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }

		$this->validate($request, ['value' => 'required']);
		$serviceCat = trim($request->value);
		$canInsert = false;
		$serviceCats = Service::getserviceCats($request->ownerId);
		if(!empty($serviceCats)){
			$serviceCatsTemp = array_values($serviceCats);
			if(!in_array(strtolower($serviceCat), array_map('strtolower', $serviceCatsTemp)))
				$canInsert = true;
		}
		else
			$canInsert = true;
		if($canInsert){
			$insId = DB::table('service_cats')->insertGetId(
				['sc_value' => $serviceCat, 'sc_business_id' => $request->ownerId, 'created_at' => 'now()', 'updated_at' => 'now()']
			);
			return $insId;
		}
		else return '0';
    }

    public function show($id){
    	$service = Service::findOrFailService($id);

        if(!Auth::user()->hasPermission(Auth::user(), 'view-service'))
            abort(404);

        $serviceStaffvalue = array();
		$serviceAreaValue = array();

		//$service = Service::findOrFail($id);
		$serviceType =  Service::getServiceTypes($service->type,'true');
		$serviceLocation = Location::getLocation($service->location);
		$serviceCatogory = Service::getServiceCats($service->category,'true');
		$serviceArea = Service::getAreasById($service->srvc_la_id);
		if($service->one_on_one_staffs)
			$serviceStaff = Staff::getStaff($service->one_on_one_staffs);
		else if($service->team_staffs)
			$serviceStaff = Staff::getStaff($service->team_staffs);	
		else
			$serviceStaff = [];

		//dd($serviceStaff);
		
		foreach($serviceStaff as $staff)
			$serviceStaffvalue[] = $staff->first_name . ' ' . $staff->last_name;
		
		foreach($serviceArea as $area)
			$serviceAreaValue[] = $area->la_name;

		 
		return view('Settings.service.service.show', compact('service','serviceType','serviceLocation','serviceCatogory','serviceAreaValue','serviceStaffvalue'));
    }

    public function edit($id){
    	$service = Service::findOrFailService($id);
        $one_on_one_duration = $service->one_on_one_duration;
        $service->one_on_one_duration_h = intdiv($one_on_one_duration, 60);
        $service->one_on_one_duration_m = $one_on_one_duration - ($service->one_on_one_duration_h * 60);
        $service->team_duration_h = intdiv($service->team_duration, 60);
        $service->team_duration_m = $service->team_duration - ($service->team_duration_h * 60);
        // $service->one_on_one_duration_h = $one_on_one_duration
    	//dd("edit");
        if(!Auth::user()->hasPermission(Auth::user(), 'edit-service'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');

        //$service = Service::find($id);
        //if($service){
            $business = Business::with('locations', 'staffs')->find(Session::get('businessId'));//, 'classCats'
            $businessId = $business->id;

            $locs = array('' => '-- Select --');
            if($business->locations->count()){
                foreach($business->locations as $location)
                    $locs[$location->id] = ucfirst($location->location_training_area);
                asort($locs);
            }

            /*$stff = array();
            if($business->staffs->count())			
				foreach($business->staffs as $staff)
					$stff[$staff->id] = $staff->first_name.' '.$staff->last_name;*/

		    $serviceTypes = Service::getServiceTypes($businessId);
		    asort($serviceTypes);

		    $serviceCats = Service::getServiceCats($businessId);
	         //asort($serviceCats); 
			// dd($service);
		    $res = $this->resourceData($service->location,false);                
    		$newres = $service->resources;
    		//dd($newres);
              
            return view('Settings.service.edit', compact('service', 'businessId', 'locs', 'serviceTypes', 'serviceCats','res','newres'));//, 'stff'
        //}
    }

    /*public function resourceData($locationId, $ifAjax=true){
    	$res = Resource::select('res_name','id')
	                          ->whereHas('items', function($query) use ($locationId){$query           ->where('ri_location',$locationId);})
	                    	 ->with(array('items' => function($query) use($locationId){
                                                    $query->where('ri_location', $locationId);}))
		                    ->where('res_business_id',Session::get('businessId'))->get();
		//dd($res);
		if($ifAjax)
			return json_encode($res);
		else
			return $res;

    }*/

    public function update($id, Request $request){
    	$isError = false;
        $msg = [];
        //dd($request->all());
        $service = Service::findService($request->id, $request->businessId);

        if(!$service || !Auth::user()->hasPermission(Auth::user(), 'edit-service')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
          
        if(!$isError){
        	//dd($request->category);
            //$service = Service::find($id);
            //if($service){
            	$prevCategory = $service->category;

	            $service->type = $request->type;
	            $service->category = $request->category;
	            $service->location = $request->location;
	            $service->is_completed = 1;

	            if(isset($request->area) && $request->area != '')
	            	$service->srvc_la_id = implode(',', $request->area);
	            else
	            	$service->srvc_la_id = '';

	            if($request->catText == '1 on 1'){
					$service->one_on_one_name = $request->one_on_one_name;
					$service->one_on_one_description = $request->one_on_one_description;
					$service->one_on_one_training_logo = $request->oneOnoneLogo;
					$service->one_on_one_colour = $request->one_on_one_colour;
					if($request->one_on_one_call_client_online)
						$service->one_on_one_call_client_online = $request->one_on_one_call_client_online;
					else
                    	$service->one_on_one_call_client_online = 0;
                    $service->one_on_one_duration = $request->one_on_one_duration_h*60 + $request->one_on_one_duration_m;
                    //$service->one_on_one_capacity = $request->one_on_one_capacity;
                    if(isset($request->one_on_one_staffs) && $request->one_on_one_staffs != '')
						$service->one_on_one_staffs = implode(',', $request->one_on_one_staffs);
					else
	            		$service->one_on_one_staffs = '';
					$service->one_on_one_price = $request->one_on_one_price;
					$effectivePrice = $request->one_on_one_price; 
					$service->one_on_one_tax = $request->one_on_one_tax;
				}
				else if($request->catText == 'TEAM Training'){
					$service->team_name = $request->team_name;
					$service->team_description = $request->team_description;
					$service->team_training_logo = $request->teamLogo;
					$service->team_colour = $request->team_colour;
					if($request->team_can_book_online)
						$service->team_can_book_online = $request->team_can_book_online;
					else
                    	$service->team_can_book_online = 0;
                    $service->team_duration = $request->team_duration_h *60 + $request->team_duration_m;
					//$service->team_capacity = $request->team_capacity;
					if(isset($request->team_staffs) && $request->team_staffs != '')
						$service->team_staffs = implode(',',$request->team_staffs);
					else
	            		$service->team_staffs = '';
					$service->team_price = $request->team_price;
					$effectivePrice = $request->team_price; 
					$service->team_tax = $request->team_tax;
				}
				if(($prevCategory ==  1 ||  $prevCategory ==  2) && $prevCategory != $request->category)
					$this->resetPrevCatFields($prevCategory, $service);

	            $service->save();
	            $this->storeResources($id,$request,'edit','App\Service');

	            if($request->has('effectiveDate') && $request->effectiveDate != ""){
	            	$effectiveData = array();
                    $effectiveData['date'] = $request->effectiveDate;
                    $effectiveData['price'] = $effectivePrice;
                    $effectiveData['type'] = 'service';
	            	$this->updateEffectivePrice($id, $effectiveData);
	            }
	            	

	              /*$formData =$request->all();
	                    ksort($formData);
	                    $resource=$item = $newResource = $newItem= [];
	                    foreach($formData as $key => $value){ 
                          if(strpos($key, 'resources') !== false)
                            $resource[] = $value;
                          if(strpos($key, 'items') !== false)
                            $item[] = $value;
	                    } 

                         ServiceResources::where('sr_entity_id',$id)->where('sr_business_id',Session::get('businessId'))->delete();

	                    if(count($resource) && count($item)){
	                        $newresArray =[];
                            for($i=0;$i<count($resource);$i++){
                            $timestamp = createTimestamp();
                            $newresArray[] = ['sr_business_id'=>Session::get('businessId'),'sr_entity_id'=>$id,'sr_entity_type'=>'service','sr_res_id'=>$resource[$i],'sr_item_quantity'=>$item[$i],'created_at'=>$timestamp,'updated_at'=>$timestamp];  
                        }
                        if(count($newresArray))
                            ServiceResources::insert($newresArray);
	                            
	                        }
	                */
                    

	            $msg['status'] = 'updated';
                
            //}
        }
        return json_encode($msg);
    }

    protected function resetPrevCatFields($prevCategory, $service){
		if($prevCategory == 1){
			$service->team_name = $service->team_description = $service->team_training_logo = $service->team_colour = $service->team_duration = /*$service->team_capacity =*/ $service->team_staffs = $service->team_price = $service->team_tax = '';
			$service->team_can_book_online = 0;
		}
		else if($prevCategory == 2){
			$service->one_on_one_name = $service->one_on_one_description = $service->one_on_one_training_logo = $service->one_on_one_colour = $service->one_on_one_duration = /*$service->one_on_one_capacity =*/ $service->one_on_one_staffs = $service->one_on_one_price = $service->one_on_one_tax = '';
			$service->one_on_one_call_client_online = 0;
		}
	}

    public function destroy($id){
    	$service = Service::findOrFailService($id);

        if($service->is_default || !isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'delete-service'))
            abort(404);
        
        $service->delete();
        return redirect()->back()->with('message', 'success|Service has been deleted successfully.');
        //route('services')
    }

    public function checkAssociation($id){
        $service = Service::findService($id);
        if($service && $service->futureEvents()->exists())
            return 1;

        return 0;
    }

    public function create(Request $request){

        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-service'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');

        //dd($service);
        $business = Business::with('locations', 'staffs')->find(Session::get('businessId'));//, 'classCats'
        $businessId = $business->id;

        $locs = array('' => '-- Select --');
        if($business->locations->count()){
            foreach($business->locations as $location)
                $locs[$location->id] = ucfirst($location->location_training_area);
            asort($locs);
        }

        /*$stff = array();
        if($business->staffs->count())			
			foreach($business->staffs as $staff)
				$stff[$staff->id] = $staff->first_name.' '.$staff->last_name;*/

	    $serviceTypes = ['' => '-- Select --'] + Service::getServiceTypes($businessId);
	    asort($serviceTypes);

	    $serviceCats = ['' => '-- Select --'] + Service::getServiceCats($businessId);
	    //asort($serviceCats);

	    $entityType = 'service';

	    if($request->has('subview'))
        	$subview = true;
        $res = $newres = [];
        return view('Settings.service.edit', compact('businessId', 'locs', 'serviceTypes', 'serviceCats', 'entityType', 'subview','res','newres'));//, 'stff'
        
    }

    public function freeResources($id, Request $request){
    	/* Getting linked resources */
        /*$linkedResources = ServiceResources::with(array('resource' => function($query){
                                                $query->select('id', 'res_name');
                                             }))
                                             ->OfService($id)
                                             ->select('sr_res_id', 'sr_item_quantity')
                                             ->get();*/
		$loc = Service::OfBusiness()->select('location')->find($id)->location;                                             
		//$linkedResources = Service::linkedResources($id, $loc);
		$linkedResources = Service::linkedResources($id);
        $return = [];
        if($linkedResources->count()){ //If there are resources linked
            /*$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $request->date, 'duration' => $request->duration]);
            $eventStartDatetime = $startAndEndDatetime['startDatetime'];
            $eventEndDatetime = $startAndEndDatetime['endDatetime'];

            //Getting clashing class bookings
            $clashingEventclas = StaffEventClass::OfBusiness()->where('sec_start_datetime', '<=', $eventEndDatetime)->where('sec_end_datetime', '>=', $eventStartDatetime)->select('sec_id')->get();
            if($clashingEventclas->count())
                $clashingEventclas = $clashingEventclas->pluck('sec_id')->toArray();

            //Getting clashing service bookings
            $query = StaffEventSingleService::OfBusiness()->where('sess_start_datetime', '<=', $eventEndDatetime)->where('sess_end_datetime', '>=', $eventStartDatetime);
	        if($request->has('eventId'))
	            $query->where('sess_id', '<>', $request->eventId);
	        $clashingEventServ = $query->select('sess_id')->get();
	        if($clashingEventServ->count())
	            $clashingEventServ = $clashingEventServ->pluck('sess_id')->toArray();

            $resId = $linkedResources->pluck('resource.id')->toArray();
            //DB::enableQueryLog();
            if(count($clashingEventclas) || count($clashingEventServ)){
	            // Getting resources in use along with quantity over the event's time frame
	            $query = StaffEventResource::whereIn('serc_res_id', $resId);
	            if(!count($clashingEventclas))
	            	$query->where('serc_event_type', '!=', 'App\StaffEventClass');
	            else if(!count($clashingEventServ))
	            	$query->where('serc_event_type', '!=', 'App\StaffEventSingleService');

	            $usedRes = $query->where(function($query) use($clashingEventclas, $clashingEventServ){
		                                if(count($clashingEventclas)){
		                                    $query->where(function($query) use($clashingEventclas){
		                                        $query->where('serc_event_type', 'App\StaffEventClass')->whereIn('serc_event_id', $clashingEventclas);
		                                    });
		                                }

		                                if(count($clashingEventServ)){
		                                    $query->orWhere(function($query) use($clashingEventServ){
		                                        $query->where('serc_event_type', 'App\StaffEventSingleService')->whereIn('serc_event_id', $clashingEventServ);
		                                    });
		                                }
		                            })
		                            //->select('serc_res_id', 'serc_item_quantity')
	            					->selectRaw('serc_res_id, sum(serc_item_quantity) as serc_item_quantity')
                                    ->groupBy('serc_res_id')
		                            ->get();


	            /*$usedRes = StaffEventResource::whereIn('serc_res_id', $resId)
	                                        ->where(function($query) use($clashingEventclas, $clashingEventServ){
	                                            if(count($clashingEventclas)){
	                                                $query->where(function($query) use($clashingEventclas){
	                                                    $query->where('serc_event_type', 'App\StaffEventClass')->whereIn('serc_event_id', $clashingEventclas);
	                                                });
	                                            }
	                                            else
	                                            	$query->where('serc_event_type', '!=', 'App\StaffEventClass');

	                                            if(count($clashingEventServ)){
	                                                $query->orWhere(function($query) use($clashingEventServ){
	                                                    $query->where('serc_event_type', 'App\StaffEventSingleService')->whereIn('serc_event_id', $clashingEventServ);
	                                                });
	                                            }
	                                            else
	                                            	$query->where('serc_event_type', '!=', 'App\StaffEventClass');
	                                        })
	                                        ->select('serc_res_id', 'serc_item_quantity')
	                                        ->get();*
	            if($usedRes->count())
	                $usedRes = $usedRes->pluck('serc_item_quantity', 'serc_res_id')->toArray();
	        }
	        else
	        	$usedRes = [];*/
	        $resId = $linkedResources->pluck('resourceWithTrashed.id')->toArray();
	        $data = ['time'=>$request->time, 'date'=>$request->date, 'duration'=>$request->duration, 'resId'=>$resId, 'eventType'=>'service', 'locId'=>$loc];
	        if($request->has('eventId'))
	            $data['eventId'] = $request->eventId;
	        $usedRes = $this->ifResourcesInUse($data);

            foreach($linkedResources as $linkedResource){
            	$totalQuant = $linkedResource->resourceWithTrashed->itemsWithTrashed->count();
            	$remainResQuan = $totalQuant;
                if($totalQuant && count($usedRes) && array_key_exists($linkedResource->resourceWithTrashed->id, $usedRes)){
                    //If resource is in use then calculate free quantity if that resource
                    //$remainResQuan = (int) $linkedResource->sr_item_quantity - $usedRes[$linkedResource->resource->id];
                    $usedQuant = $usedRes[$linkedResource->resourceWithTrashed->id];
                    $remainResQuan = $totalQuant - $usedQuant;
                }
                /*else
                    //$remainResQuan = $linkedResource->sr_item_quantity;
                    $remainResQuan = $totalQuant;*/
                    /*if($linkedResource->sr_res_id == 68)
                    dd($remainResQuan);*/
                    //$remainResQuan = 0;

                //if(!$totalQuant || $remainResQuan > 0)
                $arr = ['resName' => $linkedResource->resourceWithTrashed->res_name, 'itemQuant' => $remainResQuan, 'defaultQuant' => $linkedResource->sr_item_quantity, 'isDeleted' => false];
                if($linkedResource->resourceWithTrashed->trashed())
                	$arr['isDeleted'] = 'resource';
                else if($linkedResource->trashed())
                	$arr['isDeleted'] = 'link';
				$return[$linkedResource->resourceWithTrashed->id] = $arr;
            }
        }
        return $return;
    }

    /* Start: fatch all expried task */
    public function getExpiredTask(Request $request){
    	$id = $request->taskIds;
    	//$data = StaffEventSingleService::with('staff','service')->whereIn('sess_id',$id)->get();
    	$data = StaffEventSingleService::with(array('staff'=>function($query){
    											$query->select('id','first_name','last_name');
    										},'service'=>function($q){
    											$q->select('id','one_on_one_name','team_name');
    										}))
    									->select('sess_id','sess_staff_id','sess_service_id','sess_date','sess_time','sess_booking_status')
    									->whereIn('sess_id',$id)
    									->get();
    									
    	if($data->count())
    		return json_encode($data);

    }
    /* End: fatch all expried task */

    public function updateField(Request $request){ 
    	$service = Service::findService($request->entityId);

    	if(!$service || !hasPermission('edit-service')){
            if($request->ajax())
                return [];
            else
                abort(404);
        }

        if($request->entityProperty == 'color'){
        	if($service->category == 2) //one on one
        		$service->one_on_one_colour = $request->color;
        	else if($service->category == 1) //team training
        		$service->team_colour = $request->color;

            $service->save();
		}
	    
		return json_encode([
            'status' => 'updated'
        ]);
	}
}