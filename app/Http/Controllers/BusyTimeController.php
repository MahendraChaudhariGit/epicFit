<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Business;
use App\Service;
use App\Staff;
use App\Location;
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
use App\Clas;
use App\Http\Traits\LocationAreaTrait;

class BusyTimeController extends Controller
{
	use HelperTrait, StaffTrait, StaffEventsTrait,ServiceResourceTrait, ResourceTrait, StaffEventResourceTrait, LocationAreaTrait;

	public function freeAreas(Request $request)
	{
		// dd($request->all());
		$locsAreas = $areasId = $serviceLocAreas = $classLocAreas = [];

		/* Start: service areas */
		$service = Service::OfBusiness()->whereNull('deleted_at')->pluck('srvc_la_id');
		// dd($service); 
		if(count($service)) {
			foreach ($service as $value) {
				if($value) {
					foreach (explode(',', $value) as $value1) 
						$areasId[] = $value1;
				}
			}
		}

		$areasId = array_unique($areasId);
		// dd($areasId);

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
			->pluck('hr_entity_id')->toArray();
			if(count($workingAreasId)){
				// dd($workingAreasId);
				$data = ['startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime];
				// dd($data);
				$busyAreasId = StaffEventBusy::whereIn('seb_area_id', $workingAreasId)->where('seb_business_id',Session::get('businessId'))
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
					->OfBusiness()
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
						->OfBusiness()
						->whereIn('la_id', $workingAreasId)
						->select('la_id')
						->pluck('la_id')
						->toArray();  
						if(count($busyAreasId))
							$workingAreasId = array_diff($workingAreasId, $busyAreasId); 
						if(count($workingAreasId)){
							$areas = LocationArea::with('location')->OfBusiness()->whereIn('la_id', $workingAreasId)->get();
							if($areas->count()){
								foreach($areas as $area)
									$locsAreas[$area->la_id] = $area->location_training_area.' - '.$area->la_name;
							}
						}                                                           
					}                                                           
				}
			}                        
		}
		$serviceLocAreas = $locsAreas;
		/* End: service areas */

		/* Start: service areas */
		$locsAreas = [];
		$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $request->date, 'duration' => $request->duration]);
		$eventStartDatetime = $startAndEndDatetime['startDatetime'];
		$eventEndDatetime = $startAndEndDatetime['endDatetime'];

		$startTime = $this->datetimeToTime($eventStartDatetime);
		$endTime = $this->datetimeToTime($eventEndDatetime);
		$query = DB::table('hours')->join('area_classes', 'hr_entity_id', '=', 'ac_la_id')
		// ->where('ac_cl_id', $id)
		->where('hr_entity_type', 'area')
		->where('hr_day', $request->day)
		->where('hr_start_time', '<=', $startTime)
		->where('hr_end_time', '>=', $endTime)
		->whereNull('hours.deleted_at');

		if(isUserType(['Staff'])){
			$areas = LocationArea::whereHas('staffs', function($query){
				$query->where('id', Auth::user()->pk);
			})
			->OfBusiness()
			->distinct()
			->select('la_id')
			->pluck('la_id')
			->toArray();

			$query->whereIn('hr_entity_id', $areas);
		}
		$workingAreasId = $query->distinct()
		->select('hr_entity_id')
		->pluck('hr_entity_id');

		// dd($workingAreasId);

		if(count($workingAreasId)){
			$data = ['startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime];

			// dd($data);

			$busyAreasId = StaffEventBusy::whereIn('seb_area_id', $workingAreasId)->where('seb_business_id',Session::get('businessId'))
			->clashingEvents($data)
			->select('seb_area_id')
			->pluck('seb_area_id')
			->toArray();
			if(count($busyAreasId))
				$workingAreasId = array_diff($workingAreasId, $busyAreasId);

			if(count($workingAreasId)){
				$busyAreasId = LocationArea::whereHas('events', function($query) use ($data){
					$query->clashingEvents($data);
				})
				->OfBusiness()
				->whereIn('la_id', $workingAreasId)
				->select('la_id')
				->pluck('la_id')
				->toArray();
				if(count($busyAreasId))
					$workingAreasId = array_diff($workingAreasId, $busyAreasId);  

				if(count($workingAreasId)){
					if($request->has('eventId'))
						$data['eventId'] = $request->eventId;
					$busyAreasId = LocationArea::whereHas('eventClassess', function($query) use ($data){
						$query->clashingEvents($data);
					})
					->OfBusiness()
					->whereIn('la_id', $workingAreasId)
					->select('la_id')
					->pluck('la_id')
					->toArray();
					if(count($busyAreasId))
						$workingAreasId = array_diff($workingAreasId, $busyAreasId); 

					if(count($workingAreasId)){
						$areas = LocationArea::OfBusiness()->with('location')->whereIn('la_id',$workingAreasId)->get();

						if($areas->count()){
							foreach($areas as $area)
							{
								$locsAreas[$area->la_id] = $area->location_training_area.' - '.$area->la_name;
							}
						}
					} 
				}

			}
		} 
		$classLocAreas =  $locsAreas;
		/* End: service areas */

		$locsAreas = $classLocAreas + $serviceLocAreas;
		
		return $locsAreas;
	}

	public function freeStaffs(Request $request)
	{
    	// dd($request->all());
		$staffsId = $stff = $serviceStaffIds = $classStaffIds = [];

		/* Start: Busy service staff */
		if(isUserType(['Staff'])){
			$services = Staff::getServices(['staffId' => Auth::user()->pk, 'complOnly' => 1]);

			if(count($services) && in_array($id, $services))
				$staffsId = [Auth::user()->pk];
		}        
		else if(eligibleAccounts(['Admin']) || Session::get('hostname') == 'result'){
			$services = Service::OfBusiness()->select(['one_on_one_staffs', 'team_staffs'])->get();

			if(count($services->toArray())){
				foreach ($services as $service) {
					if($service->one_on_one_staffs) {
						foreach (explode(',', $service->one_on_one_staffs) as $staffIdValue)
							$staffsId[] = $staffIdValue;
					}
					else if($service->team_staffs) {
						foreach (explode(',', $service->team_staffs) as $staffIdValue)
							$staffsId[] = $staffIdValue;
					}
				}	
			}
		}

		// dd($staffsId);
		$staffsId = array_unique($staffsId);

		if(count($staffsId)){
			$date = $request->date;
			$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $date, 'duration' => $request->duration]);

			$eventStartDatetime = $startAndEndDatetime['startDatetime'];
			$eventEndDatetime = $startAndEndDatetime['endDatetime'];
			$startTime = $this->datetimeToTime($eventStartDatetime);
			$endTime = $this->datetimeToTime($eventEndDatetime);

			$workingStaffsId = $this->getWorkingStaffs($staffsId, ['type'=>'service','day'=>$request->day,'date'=>$date, 'startTime'=>$startTime, 'endTime'=>$endTime]);

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
            $serviceStaffIds =  $stff;
            /* End: Busy service staff */

            /* Start: Busy classes staff */
            $stff = [];
            $workingStaffsId = [];
            $date = $request->date;
            $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $date, 'duration' => $request->duration]);

            $eventStartDatetime = $startAndEndDatetime['startDatetime'];
            $eventEndDatetime = $startAndEndDatetime['endDatetime'];
            $startTime = $this->datetimeToTime($eventStartDatetime);
            $endTime = $this->datetimeToTime($eventEndDatetime);

            $classes = Clas::ofBusiness()->whereNull('deleted_at')->pluck('cl_id');
            // dd($classes);

            foreach ($classes as $classId) {
            	$dataStaffIds = $this->getWorkingStaffs([$classId], ['type'=>'class','day'=>$request->day,'date'=>$date, 'startTime'=>$startTime, 'endTime'=>$endTime]);
            	if(count($dataStaffIds)) {
            		foreach ($dataStaffIds as $value) 
            			$workingStaffsId[] = $value;
            	}
            }
            
            $workingStaffsId = array_unique($workingStaffsId);

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
            		$busyStaffsId = StaffEventSingleService::OfStaffs($workingStaffsId)
            		->clashingEvents($data)
            		->select('sess_staff_id')
            		->pluck('sess_staff_id')
            		->toArray(); 

            		if(count($busyStaffsId))
            			$workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  

            		if(count($workingStaffsId)){
            			if($request->has('eventId'))
            				$data['eventId'] = $request->eventId;
            			$busyStaffsId = StaffEventClass::OfStaffs($workingStaffsId)
            			->clashingEvents($data)
            			->select('sec_staff_id')
            			->pluck('sec_staff_id')
            			->toArray();       
            			if(count($busyStaffsId))
            				$workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  

            			if(count($workingStaffsId)){
                                //$this->deleteExpiredFav();

            				$staffs = Staff::with(array('favAreas' => function($query){ 
            					$query->select('la_id');
            				}))
            				->select('id', 'first_name', 'last_name')
            				->find($workingStaffsId);

            				if($staffs->count()){
            					foreach($staffs as $staff){
                                        //$stff[$staff->id] = $staff->fullName;
            						$stff[$staff->id] = ['name'=>$staff->fullName];
            					}
            				}
            			}                                                           
            		}                                                           
            	}
            }   

            $classStaffIds = $stff;
            /* End: Busy classes staff */

            $stff = [];
            // $stff = array_merge($classStaffIds, $serviceStaffIds);
            $stff = $classStaffIds +  $serviceStaffIds;
            $stff = array_unique($stff,SORT_REGULAR);

            return $stff;
	}
	
	public function getStaffsArea(Request $request){
        // $freeStaffData = [];
		// 	$freeStaff = $this->staffs($request->areaId);
		// 	foreach($freeStaff as $staffId => $staffName ){
		// 	$freeStaffData[$staffId] = ['name'=>$staffName];
		// 	}

			$staffsId = $stff = $serviceStaffIds = $classStaffIds = [];

			/* Start: Busy service staff */
			if(isUserType(['Staff'])){
				$services = Staff::getServices(['staffId' => Auth::user()->pk, 'complOnly' => 1]);
	
				if(count($services) && in_array($id, $services))
					$staffsId = [Auth::user()->pk];
			}        
			else if(eligibleAccounts(['Admin']) || Session::get('hostname') == 'result'){
				$services = Service::OfBusiness()->select(['one_on_one_staffs', 'team_staffs'])->get();
	
				if(count($services->toArray())){
					foreach ($services as $service) {
						if($service->one_on_one_staffs) {
							foreach (explode(',', $service->one_on_one_staffs) as $staffIdValue){
							 $staffId = Staff::with('areas')->where('id',$staffIdValue)->first();
								foreach($staffId->areas as $area){
									if($request->areaId == $area->la_id){
										$staffsId[] = $staffIdValue;
									}
								}
								
							}
						}
						else if($service->team_staffs) {
							foreach (explode(',', $service->team_staffs) as $staffIdValue){
							$staffId = Staff::with('areas')->where('id',$staffIdValue)->first();
								foreach($staffId->areas as $area){
									if($request->areaId == $area->la_id){
										$staffsId[] = $staffIdValue;
									}
								}
							}
						}
					}	
				}
			}
			$staffsId = array_unique($staffsId);
	
			if(count($staffsId)){
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
				$serviceStaffIds =  $stff;
				/* End: Busy service staff */
	
				/* Start: Busy classes staff */
				$stff = [];
				$workingStaffsId = [];
				$date = $request->date;
				$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $date, 'duration' => $request->duration]);
	
				$eventStartDatetime = $startAndEndDatetime['startDatetime'];
				$eventEndDatetime = $startAndEndDatetime['endDatetime'];
				$startTime = $this->datetimeToTime($eventStartDatetime);
				$endTime = $this->datetimeToTime($eventEndDatetime);
	
				$classes = Clas::ofBusiness()->whereNull('deleted_at')->pluck('cl_id');
				// dd($classes);
	
				foreach ($classes as $classId) {
					$dataStaffIds = $this->getWorkingStaffs([$classId], ['type'=>'class','day'=>$request->day,'date'=>$date, 'startTime'=>$startTime, 'endTime'=>$endTime]);
					if(count($dataStaffIds)) {
						foreach ($dataStaffIds as $value) {
							$staffId = Staff::with('areas')->where('id',$value)->first();
							foreach($staffId->areas as $area){
								if($request->areaId == $area->la_id){
									$workingStaffsId[] = $value;
								}
							}
						}
							
					}
				}
				
				$workingStaffsId = array_unique($workingStaffsId);
	
				if(count($workingStaffsId)){
					$data = ['startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime];
	
					$busyStaffs = StaffEventBusy::clashingEvents($data)->get();
					$busyStaffsId =[];
                    foreach($busyStaffs as $key){
                        $busyStaffsId = array_merge($busyStaffsId,$key->staff->pluck('id')->toArray());
					 }
                    if(count($busyStaffsId))
                    $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);
					// dd($busyStaffsId,  $workingStaffsId, $data); 
					if(count($workingStaffsId)){
						$busyStaffsId = StaffEventSingleService::OfStaffs($workingStaffsId)
						->clashingEvents($data)
						->select('sess_staff_id')
						->pluck('sess_staff_id')
						->toArray(); 
	
						if(count($busyStaffsId))
							$workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  
	
						if(count($workingStaffsId)){
							if($request->has('eventId'))
								$data['eventId'] = $request->eventId;
							$busyStaffsId = StaffEventClass::OfStaffs($workingStaffsId)
							->clashingEvents($data)
							->select('sec_staff_id')
							->pluck('sec_staff_id')
							->toArray();       
							if(count($busyStaffsId))
								$workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  
	
							if(count($workingStaffsId)){
									//$this->deleteExpiredFav();
	
								$staffs = Staff::with(array('favAreas' => function($query){ 
									$query->select('la_id');
								}))
								->select('id', 'first_name', 'last_name')
								->find($workingStaffsId);
	
								if($staffs->count()){
									foreach($staffs as $staff){
											//$stff[$staff->id] = $staff->fullName;
										$stff[$staff->id] = ['name'=>$staff->fullName];
									}
								}
							}                                                           
						}                                                           
					}
				}   
	
				$classStaffIds = $stff;
				/* End: Busy classes staff */
	
				$stff = [];
				$availableStaff = [];
				// $stff = array_merge($classStaffIds, $serviceStaffIds);
				$stff = $classStaffIds +  $serviceStaffIds;
				$stff = array_unique($stff,SORT_REGULAR);
				foreach($stff as $key => $item){
					if(in_array($key,$request->staffId)){
                         $availableStaff[$key] = $item; 
					}
				}
				return $availableStaff;

	}
   
}
