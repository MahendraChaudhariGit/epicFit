<?php
namespace App\Http\Controllers\Setings\Location;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Business;
use App\Location;
use App\LocationArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\LocationAreaTrait;
use \stdClass;
use App\Http\Traits\StaffEventsTrait;
use Session;
use App\Http\Traits\HelperTrait;
use DB;
use Input;
use App\Staff;
use App\Http\Traits\ClosedDateTrait;
use App\Http\Traits\ClientTrait;

class LocationAreasController extends Controller{
	use LocationAreaTrait, HelperTrait, StaffEventsTrait, ClosedDateTrait, ClientTrait;

    private $cookieSlug = 'area';

	public function index(Request $request,$locationid){
        $location = Location::findOrFailLoc($locationid);
        
		if(/*!Session::has('businessId') || */!Auth::user()->hasPermission(Auth::user(), 'list-location-area'))
            abort(404);

        $areaLocationStaff = [];
        $search = $request->get('search');

        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        //$allLocationAreas = LocationArea::with('staffs')->join('locations', 'la_location_id', '=', 'id')->where('id', $locationid)->where('business_id', Session::get('businessId'))->select('la_id', 'la_logo', 'la_name', 'location_training_area')->paginate($length);
		//$allLocationAreas = Location::find($locationid)->areas;
        if($search)
            $allLocationAreas = $location->areas()->where('la_name', 'like', "%$search%")->paginate($length);
        else
            $allLocationAreas = $location->areas()->paginate($length);
        $locName = $location->location_training_area;

        if($allLocationAreas->count()){
    		foreach($allLocationAreas as $allLocationArea){
    			$areaStaffs = $allLocationArea->staffs;
    			if($areaStaffs->count()){
    				$i = 0;
    				foreach($areaStaffs as $areaStaff){
    					$areaLocationStaff[$allLocationArea['la_id']][$i] = ucwords($areaStaff->first_name . ' ' . $areaStaff->last_name);
    					$i++;
    				}
    			}
    		}
        }
        asort($areaLocationStaff);
       //dd($allLocationAreas);
		return view('Settings.location.index_area', compact('allLocationAreas', 'locName','areaLocationStaff'));
    }
	
	public function uploadFile(Request $request){
        //$loc = LocationArea::findArea($request->id);
        //if($loc && Auth::user()->hasPermission(Auth::user(), 'edit-location-area')){
        $loc = LocationArea::find($request->id);
        if($loc){
		  //$locationAreaId = (int)$request->id;
            //$loc = LocationArea::find($locationAreaId);
            $loc->update(array('la_logo' => $request->photoName));
            return url('/uploads/thumb_'.$request->photoName);
        }
        return '';
    }
	public function show($id){
        $locationArea = LocationArea::findOrFailArea($id);
        
		if(!Auth::user()->hasPermission(Auth::user(), 'view-location-area'))
            abort(404);

        $locationAreaStaff = array();

		//$locationArea = LocationArea::findOrFail($id);
		$areaStaffs = $locationArea->staffs;
		if($areaStaffs)
			foreach($areaStaffs as $areaStaff)
				$locationAreaStaff[] = ucwords($areaStaff->first_name . ' ' . $areaStaff->last_name);

        asort($locationAreaStaff);
		return view('Settings.location.show_area',  compact('locationArea','locationAreaStaff'));
    }

    public function commonStaffs(Request $request){
        $stff = [];
        /*$areasId = [];

        if($request->has('locId')){
            $areasToLink = LocationArea::where('la_location_id', $request->locId)->select('la_id')->get();
            if(count($areasToLink)){
                foreach($areasToLink as $area)
                    $areasId[] = $area->la_id;
            }
        }
        else if($request->has('areasId'))
            $areasId = $request->areasId;*/
        

        if(count($request->areasId)){
            $staffs = Staff::OfBusiness()
                            ->whereHas('areas', function ($query) use ($request){
                                $query->whereIn('as_la_id', $request->areasId)
                                        ->selectRaw('as_staff_id, count(1) as count')
                                        ->groupBy('as_staff_id')
                                        ->having('count', '>=', count($request->areasId));
                            })
                            ->get();
            
            if($staffs->count()){
                $i = 0;
                foreach($staffs->sortBy('first_name') as $staff){
                    $stff[$i]['id'] = $staff->id;
                    $stff[$i]['name'] = ucwords($staff->fullName);
                    $i++;
                }
            }
        }
        return json_encode($stff);
    }

    public function getStaffs(Request $request){
        $stff = [];

    	if(isset($request->startDate) && Auth::user()->hasPermission(Auth::user(), 'list-staff')){
    		//$area = LocationArea::find($request->areaId);
            $area = LocationArea::findArea($request->areaId);
	        if($area){
	            $staffs = $area->staffs;
	            if($staffs->count()){
	            	foreach($staffs as $staff){
	            		$staffActivityData = new stdClass();
        				$staffActivityData->staffId = $staff->id;
        				$staffActivityData->areaId = $request->areaId;
        				$staffActivityData->startDate = $request->startDate;

        				if(isset($request->day)){
        					$staffActivityData->day = $request->day;
        					if($this->staffHasDayActivity($staffActivityData))
        						$stff[$staff->id] = ucwords($staff->first_name. ' '.$staff->last_name);
        				}
        				else if(isset($request->endDate)){
        					$staffActivityData->endDate = $request->endDate;
        					if($this->staffHasWeekActivity($staffActivityData))
        						$stff[$staff->id] = ucwords($staff->first_name. ' '.$staff->last_name);
        				}
	            	}
	            }
	        }
            asort($stff);
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
        if(Auth::user()->hasPermission(Auth::user(), 'list-staff')){
            if($request->areaId == 'all'){
                $idsObj = DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->whereNull('deleted_at')->distinct()->select('as_la_id', 'as_staff_id')->get();
                if(count($idsObj)){
                    foreach($idsObj as $idObj){
                        if($hasRostStaffs)
                            break;

                        $hasRostStaffs = $this->hasRosteredStaffsLogic($request, ['staffId' => $idObj->as_staff_id, 'areaId' => $idObj->as_la_id]);
                    }
                }
            }
            else{
        		$area = LocationArea::findArea($request->areaId);
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
        }
    	return $hasRostStaffs;
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
        $area = LocationArea::findOrFailArea($id);
        if(!Auth::user()->hasPermission(Auth::user(), 'edit-location-area'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');

        //$area = LocationArea::find($id);
        //if($area){
            $area->hours = LocationArea::getHours($area->la_id);
            if(count($area->hours))
                $area->hours = json_encode($area->hours);

            $business = Business::with('locations', 'staffs')->find(Session::get('businessId'));
            $businessId = $business->id;

            $locs = array('' => '-- Select --');
            if($business->locations->count())
                foreach($business->locations->sortBy('location_training_area') as $location)
                    $locs[$location->id] = $location->location_training_area;

            $stff = array();
            if($business->staffs->count())			
				foreach($business->staffs as $staff)
					$stff[$staff->id] = ucwords($staff->first_name.' '.$staff->last_name);
            asort($stff);
            $aresStaffs = $area->staffs->pluck('id')->toArray();

          

            return view('Settings.location.edit', compact('area', 'businessId', 'locs', 'stff', 'aresStaffs'));
        //}
    }

    public function update($id, Request $request){
        $isError = false;
        $msg = [];

        $area = LocationArea::findArea($id, $request->businessId);

        if(!$area || !Auth::user()->hasPermission(Auth::user(), 'edit-location-area')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            //$area = LocationArea::find($id);
            //if($area){
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
                $linkedDeletedStaffIds = LocationArea::pivotStaffsTrashedOnly($area->la_id);
                if(count($linkedDeletedStaffIds))
                    foreach($linkedDeletedStaffIds as $linkedDeletedStaffId)
                        $attachedStaff[$linkedDeletedStaffId->as_staff_id] = ['as_business_id' => Session::get('businessId')];
                //dd($attachedStaff);
				$area->staffs()->sync($attachedStaff);
				$this->setWorkingHours($request, ['mode' => 'edit', 'entityType' => 'area', 'entityId' => $id]);

                $msg['status'] = 'updated';
            //}
        }
        return json_encode($msg);
    }

    public function create($id){
        //dd($id);
         //$area = Location::find($id);
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-location-area'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');
        //if(count($area)){
        $locationHour='';
         $locationHour = Location::getLocHours($id);
            /*if(count($locationHour))
                $locationHour = json_encode($locationHour);*/
         //}
            //dd( $loactionHour);
         /*if(Location::ifLocExist($id))
            return Location::getLocHours($id);
        return [];*/
         
        $business = Business::with('locations', 'staffs')->find(Session::get('businessId'));
        $businessId = $business->id;

        $locs = array('' => '-- Select --');
        if($business->locations->count())
            foreach($business->locations->sortBy('location_training_area') as $location)
                $locs[$location->id] = $location->location_training_area;

        $stff = array();
        if($business->staffs->count())          
            foreach($business->staffs as $staff)
                $stff[$staff->id] = ucwords($staff->first_name.' '.$staff->last_name);

        asort($stff);
        $entityType = 'area';
        $location_id=$id;

        /*if(count($area)){
            $aresStaffs = $area->staffs->pluck('id')->toArray();
         }   */


        
        return view('Settings.location.edit', compact('locationHour','businessId', 'locs', 'stff', 'entityType','location_id'/*,'aresStaffs'*/));
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

    public function destroy($id){
        $locationArea = LocationArea::findOrFailArea($id);

        if(!isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'delete-location-area'))
            abort(404);
        
        $locationArea->delete();
        
        return redirect()->back()->with('message', 'success|Area has been deleted successfully.');
    }
}