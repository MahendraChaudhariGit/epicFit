<?php
namespace App\Http\Controllers\Setings\Location;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Business;
use App\Location;
use App\LocationArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;
use App\Http\Traits\HelperTrait;
use Input;
//use Redirect;

class LocationControllerNew extends Controller{
	use HelperTrait;

	private $cookieSlug = 'location';

	public function index(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-location'))
            abort(404);

		$allLocations = array();
		$search = $request->get('search');
        //if(Session::has('businessId')){
    	$length = $this->getTableLengthFromCookie($this->cookieSlug);

    	if($search)
    		$allLocations = Location::OfBusiness()->where(function($query) use ($search){$query->orWhere('location_training_area', 'like', "%$search%")->orWhere('email', 'like', "%$search%");})->paginate($length);
    	else
    		$allLocations = Location::OfBusiness()->paginate($length);
			//$allLocations = Business::find(Session::get('businessId'))->locations;
        //}
        
		return view('Settings.location.index', compact('allLocations'));
    }
	
	public function uploadFile(Request $request){
		//$locationId = (int)$request->id;
		//if($locationId){
			//$loc = Location::find($locationId);
			//$loc = Location::OfBusiness()->find($locationId);
		/*$loc = Location::findLoc($request->id);
		if($loc && Auth::user()->hasPermission(Auth::user(), 'edit-location')){*/
		$loc = Location::find($request->id);
		if($loc){
			$loc->update(array('logo' => $request->photoName));
    		return url('/uploads/thumb_'.$request->photoName);
		}
		//}
        return '';
    }

	public function show($id){
		$location = Location::findOrFailLoc($id);

    	if(!Auth::user()->hasPermission(Auth::user(), 'view-location'))
            abort(404);

		//$serviceStaffvalue = array();
		//$serviceAreaValue = array();

		//$location = Location::OfBusiness()->findOrFail($id);
		//$location = Location::findOrFailLoc($id);
		$countries = \Country::getCountryLists();
		$location->stateName = \Country::getStateName($location->country, $location->state);
		return view('Settings.location.show', compact('location','countries'));
    }

	public function getHours($locId){
		if(Location::ifLocExist($locId))
			return Location::getLocHours($locId);
		return [];
	}

	public function getAreas($locId){
		if(Location::ifLocExist($locId))
			return Location::getLocAreas($locId);
		return [];
	}
	
	/*public function subview(Request $request){
		if(!Auth::user()->hasPermission(Auth::user(), 'create-location'))
            abort(404);

		if(isset($request->bussId) && $request->bussId != ''){
			$businessId =  $request->bussId;

			$business = Business::findOrFail($businessId);
	        $savedData = array();
	        if($business->venue_location){
	            $savedData['address_line_one'] = $business->address_line_one;
	            $savedData['address_line_two'] = $business->address_line_two;
	            $savedData['city'] = $business->city;
	            $savedData['country'] = $business->country;
	            $savedData['state'] = $business->state;
	            $savedData['postal_code'] = $business->postal_code;
	            $savedData['time_zone'] = $business->time_zone;
	        }
			$locations = $business->locations;
			$locs = array('' => '-- Select --');
			foreach($locations as $location)
				$locs[$location->id] = $location->location_training_area;
	        $staffs = $business->staffs;
			$stff = array();
			foreach($staffs as $staff)
				$stff[$staff->id] = $staff->first_name.' '.$staff->last_name;

	        $time_zone = ['' => '-- Select --'] + \TimeZone::getTimeZone();
	        $country = ['' => '-- Select --'] + \Country::getCountryLists();

	        return view('Settings.location.subview_form', compact('businessId', 'savedData', 'locs', 'stff', 'country', 'time_zone'));
		}
	}*/
	
    public function store(Request $request){
		if($request->venue == 'Area'){
			$isError = false;
            $msg = [];

            if(!Location::ifLocExist($request->location, $request->businessId) || !Auth::user()->hasPermission(Auth::user(), 'create-location-area')){
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
		        $addedArea=$area->save();
				if($request->stuff_selection != ''){
					foreach($request->stuff_selection as $staffIds)
						$attachedStaff[$staffIds] = ['as_business_id' => Session::get('businessId')];	
					
					$area->staffs()->attach($attachedStaff); 
				}
				/*$input = $request->all();
				ksort($input);
				$monday_start = $monday_end = $tuesday_start = $tuesday_end = $wednesday_start = $wednesday_end = $thursday_start = $thursday_end = $friday_start = $friday_end = $saturday_start = $saturday_end = $sunday_start = $sunday_end = [];
				if($request->exists('monday')){
					foreach($input as $key => $value){
						if(strpos($key, 'monday_start') !== false)
							$monday_start[] = timeStringToDbTime($value);
						
						else if(strpos($key, 'monday_end') !== false)
							$monday_end[] = timeStringToDbTime($value);
					}	
				}
				if($request->exists('tuesday')){
					foreach($input as $key => $value){
						if(strpos($key, 'tuesday_start') !== false)
							$tuesday_start[] = timeStringToDbTime($value);
						
						else if(strpos($key, 'tuesday_end') !== false)
							$tuesday_end[] = timeStringToDbTime($value);
					}	
				}
				if($request->exists('wednesday')){
					foreach($input as $key => $value){
						if(strpos($key, 'wednesday_start') !== false)
							$wednesday_start[] = timeStringToDbTime($value);
						
						else if(strpos($key, 'wednesday_end') !== false)
							$wednesday_end[] = timeStringToDbTime($value);
					}	
				}
				if($request->exists('thursday')){
					foreach($input as $key => $value){
						if(strpos($key, 'thursday_start') !== false)
							$thursday_start[] = timeStringToDbTime($value);
						
						else if(strpos($key, 'thursday_end') !== false)
							$thursday_end[] = timeStringToDbTime($value);
					}	
				}
				if($request->exists('friday')){
					foreach($input as $key => $value){
						if(strpos($key, 'friday_start') !== false)
							$friday_start[] = timeStringToDbTime($value);
						
						else if(strpos($key, 'friday_end') !== false)
							$friday_end[] = timeStringToDbTime($value);
					}	
				}
				if($request->exists('saturday')){
					foreach($input as $key => $value){
						if(strpos($key, 'saturday_start') !== false)
							$saturday_start[] = timeStringToDbTime($value);
						
						else if(strpos($key, 'saturday_end') !== false)
							$saturday_end[] = timeStringToDbTime($value);
					}	
				}
				if($request->exists('sunday')){
					foreach($input as $key => $value){
						if(strpos($key, 'sunday_start') !== false)
							$sunday_start[] = timeStringToDbTime($value);
						
						else if(strpos($key, 'sunday_end') !== false)
							$sunday_end[] = timeStringToDbTime($value);
					}	
				}
				$insertData = array();
				if(count($monday_start)){
					for($i=0; $i<count($monday_start); $i++){
						$insertData[] = array('hr_entity_id' => $area->la_id, 'hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($tuesday_start)){
					for($i=0; $i<count($tuesday_start); $i++){
						$insertData[] = array('hr_entity_id' => $area->la_id, 'hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($wednesday_start)){
					for($i=0; $i<count($wednesday_start); $i++){
						$insertData[] = array('hr_entity_id' => $area->la_id, 'hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($thursday_start)){
					for($i=0; $i<count($thursday_start); $i++){
						$insertData[] = array('hr_entity_id' => $area->la_id, 'hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($friday_start)){
					for($i=0; $i<count($friday_start); $i++){
						$insertData[] = array('hr_entity_id' => $area->la_id, 'hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($saturday_start)){
					for($i=0; $i<count($saturday_start); $i++){
						$insertData[] = array('hr_entity_id' => $area->la_id, 'hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($sunday_start)){
					for($i=0; $i<count($sunday_start); $i++){
						$insertData[] = array('hr_entity_id' => $area->la_id, 'hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($insertData))
					DB::table('hours')->insert($insertData);*/
				$this->setWorkingHours($request, ['mode' => 'add', 'entityType' => 'area', 'entityId' => $area->la_id]);

				$msg['status'] = 'added';
				$msg['insertId'] = $area->la_id;
				Session::put('ifBussHasAreas' , true); 
			}
			
			return json_encode($msg);
		}
		else{
            $isError = false;
            $msg = [];
            
            if($request->businessId != Session::get('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-location')){
	            if($request->ajax())
	                $isError = true;
	            else
	                abort(404);
	        }
            
            if(!$isError){
	            /*if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'location'])){
	                $msg['status'] = 'error';
	                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
	                $isError = true;
	            }*/
	            if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'location'])){
	                $msg['status'] = 'error';
	                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
	                $isError = true;
	            }
	            $locNameExist = Location::HasName($request->location_training_area)->count();
	            if($locNameExist > 0) {
	            	$msg['status'] = 'error';
	            	$msg['errorData'][] = array('locNameExist' => 'This location name is already in use!');
	            	$isError = true;
	            }
	            if($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'location'])){
	                $msg['status'] = 'error';
	                $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
	                $isError = true;
	            }
	            
	            if(!$isError){
					$insertData = array('business_id' => $request->businessId, 'location_training_area' => $request->location_training_area, 'logo' => $request->locationLogo, 'website' => $request->website, 'facebook' => $request->facebook, 'email' => $request->email, 'phone' => $request->phone, 'city' => $request->city, 'country' => $request->country, 'state' => $request->state, 'postal_code' => $request->postal_code, 'time_zone' => $request->time_zone);
					
					if($request->fixed_location == 'Fixed Location'){
						$insertData['fixed_location'] = 1;
						$insertData['address_line_one'] = $request->address_line_one;
						$insertData['address_line_two'] = $request->address_line_two;
					}
					if($request->disp_location_web)
						$insertData['disp_location_web'] = $request->disp_location_web;
					if($request->disp_location_online)
						$insertData['disp_location_online'] = $request->disp_location_online;
					
					//$business = Business::find($request->businessId);
					//$addedLocation = $business->locations()->create($insertData);
					$addedLocation = Location::create($insertData);
					Session::put('ifBussHasLocations' , true); 

					/*$input = $request->all();
					ksort($input);
					$monday_start = $monday_end = $tuesday_start = $tuesday_end = $wednesday_start = $wednesday_end = $thursday_start = $thursday_end = $friday_start = $friday_end = $saturday_start = $saturday_end = $sunday_start = $sunday_end = [];
					if($request->exists('monday')){
						foreach($input as $key => $value){
							if(strpos($key, 'monday_start') !== false)
								$monday_start[] = timeStringToDbTime($value);
							
							else if(strpos($key, 'monday_end') !== false)
								$monday_end[] = timeStringToDbTime($value);
						}	
					}
					if($request->exists('tuesday')){
						foreach($input as $key => $value){
							if(strpos($key, 'tuesday_start') !== false)
								$tuesday_start[] = timeStringToDbTime($value);
							
							else if(strpos($key, 'tuesday_end') !== false)
								$tuesday_end[] = timeStringToDbTime($value);
						}	
					}
					if($request->exists('wednesday')){
						foreach($input as $key => $value){
							if(strpos($key, 'wednesday_start') !== false)
								$wednesday_start[] = timeStringToDbTime($value);
							
							else if(strpos($key, 'wednesday_end') !== false)
								$wednesday_end[] = timeStringToDbTime($value);
						}	
					}
					if($request->exists('thursday')){
						foreach($input as $key => $value){
							if(strpos($key, 'thursday_start') !== false)
								$thursday_start[] = timeStringToDbTime($value);
							
							else if(strpos($key, 'thursday_end') !== false)
								$thursday_end[] = timeStringToDbTime($value);
						}	
					}
					if($request->exists('friday')){
						foreach($input as $key => $value){
							if(strpos($key, 'friday_start') !== false)
								$friday_start[] = timeStringToDbTime($value);
							
							else if(strpos($key, 'friday_end') !== false)
								$friday_end[] = timeStringToDbTime($value);
						}	
					}
					if($request->exists('saturday')){
						foreach($input as $key => $value){
							if(strpos($key, 'saturday_start') !== false)
								$saturday_start[] = timeStringToDbTime($value);
							
							else if(strpos($key, 'saturday_end') !== false)
								$saturday_end[] = timeStringToDbTime($value);
						}	
					}
					if($request->exists('sunday')){
						foreach($input as $key => $value){
							if(strpos($key, 'sunday_start') !== false)
								$sunday_start[] = timeStringToDbTime($value);
							
							else if(strpos($key, 'sunday_end') !== false)
								$sunday_end[] = timeStringToDbTime($value);
						}	
					}
					$insertData = array();
					if(count($monday_start)){
						for($i=0; $i<count($monday_start); $i++){
							$insertData[] = array('hr_entity_id' => $addedLocation->id, 'hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i], 'hr_entity_type' => 'location');
						}
					}
					if(count($tuesday_start)){
						for($i=0; $i<count($tuesday_start); $i++){
							$insertData[] = array('hr_entity_id' => $addedLocation->id, 'hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i], 'hr_entity_type' => 'location');
						}
					}
					if(count($wednesday_start)){
						for($i=0; $i<count($wednesday_start); $i++){
							$insertData[] = array('hr_entity_id' => $addedLocation->id, 'hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i], 'hr_entity_type' => 'location');
						}
					}
					if(count($thursday_start)){
						for($i=0; $i<count($thursday_start); $i++){
							$insertData[] = array('hr_entity_id' => $addedLocation->id, 'hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i], 'hr_entity_type' => 'location');
						}
					}
					if(count($friday_start)){
						for($i=0; $i<count($friday_start); $i++){
							$insertData[] = array('hr_entity_id' => $addedLocation->id, 'hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i], 'hr_entity_type' => 'location');
						}
					}
					if(count($saturday_start)){
						for($i=0; $i<count($saturday_start); $i++){
							$insertData[] = array('hr_entity_id' => $addedLocation->id, 'hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i], 'hr_entity_type' => 'location');
						}
					}
					if(count($sunday_start)){
						for($i=0; $i<count($sunday_start); $i++){
							$insertData[] = array('hr_entity_id' => $addedLocation->id, 'hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i], 'hr_entity_type' => 'location');
						}
					}
					if(count($insertData))
						DB::table('hours')->insert($insertData);*/
					$this->setWorkingHours($request, ['mode' => 'add', 'entityType' => 'location', 'entityId' => $addedLocation->id]);

					$msg['status'] = 'added';
	            	$msg['insertId'] = $addedLocation->id;
		        }
	        }
		    
			return json_encode($msg);
		}
    }

    public function edit($id){
    	$location = Location::findOrFailLoc($id);

        if(!Auth::user()->hasPermission(Auth::user(), 'edit-location'))
            abort(404);

        //if(!Session::has('businessId'))
           // return redirect('settings/business/create');

        //$location = Location::OfBusiness()->findOrFail($id);
        
        $location->hours = Location::getLocHours($location->id);
        if(count($location->hours))
            $location->hours = json_encode($location->hours);

        //$business = Business::find(Session::get('businessId'));
        //$businessId = $business->id;
        $businessId = Session::get('businessId');

        $country = ['' => '-- Select --'] + \Country::getCountryLists();

        $states = $this->getStates($location->country);

        $time_zone = ['' => '-- Select --'] + \TimeZone::getTimeZone();
        
        return view('Settings.location.edit', compact('location', 'businessId', 'country', 'states', 'time_zone'));
    }

    public function update($id, Request $request){
        $isError = false;
        $msg = [];

        $location = Location::findLoc($id, $request->businessId);

        if(!$location || !Auth::user()->hasPermission(Auth::user(), 'edit-location')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            //$location = Location::OfBusiness()->find($id);
            //if($location){
                /*if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'location', 'id' => $id])){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                    $isError = true;
                }*/
                if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'location', 'id' => $id])){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                    $isError = true;
                }

                if($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'location', 'id' => $id])){
	                $msg['status'] = 'error';
	                $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
	                $isError = true;
	            }

                //$locNameExist = Location::where('location_training_area', $request->location_training_area)->where('id', '<>', $id)->count();
                $locNameExist = Location::HasName($request->location_training_area)->where('id', '<>', $id)->count();
	            if($locNameExist > 0) {
	            	$msg['status'] = 'error';
	            	$msg['errorData'][] = array('locNameExist' => 'This location name is already in use!');
	            	$isError = true;
	            }

                if(!$isError){
                	$location->location_training_area = $request->location_training_area;
                    $location->logo = $request->locationLogo;
                    $location->website = $request->website;
                    $location->facebook = $request->facebook;
                    $location->email = $request->email;
                    $location->phone = $request->phone;
                    if($request->fixed_location == 'Fixed Location'){
                        $location->fixed_location = 1;
                        $location->address_line_one = $request->address_line_one;
                        $location->address_line_two = $request->address_line_two;
                    }
                    else{
                        $location->fixed_location = 0;
                        $location->address_line_one = $location->address_line_two = '';
                    }
                    $location->city = $request->city;
                    $location->country = $request->country;
                    $location->state = $request->state;
                    $location->postal_code = $request->postal_code;
                    $location->time_zone = $request->time_zone;
					if(isset($request->disp_location_web) && $request->disp_location_web)
                        $location->disp_location_web = $request->disp_location_web;
                    else
                        $location->disp_location_web = 0;
                    if(isset($request->disp_location_online) && $request->disp_location_online)
                        $location->disp_location_online = $request->disp_location_online;
                    else
                        $location->disp_location_online = 0;
					
					$location->save();

                    $this->setWorkingHours($request, ['mode' => 'edit', 'entityType' => 'location', 'entityId' => $id]);

                    $msg['status'] = 'updated';
                }
            //}
        }
        return json_encode($msg);
    }

    public function create(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-location'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');

        $business = Business::with('locations')->find(Session::get('businessId'));
        $businessId = $business->id;

        $bussAddr = array();
        if(!count($business->locations)){
        	if($business->venue_location){
	            $bussAddr['address_line_one'] = $business->address_line_one;
	            $bussAddr['address_line_two'] = $business->address_line_two;
	            $bussAddr['city'] = $business->city;
	            $bussAddr['country'] = $business->country;
	            $bussAddr['state'] = $business->state;
	            $bussAddr['postal_code'] = $business->postal_code;
	            $bussAddr['time_zone'] = $business->time_zone;
	        }
        }

        $country = ['' => '-- Select --'] + \Country::getCountryLists();

        $time_zone = ['' => '-- Select --'] + \TimeZone::getTimeZone();

        $entityType = 'location';

        if($request->has('subview'))
        	$subview = true;
        
        return view('Settings.location.edit', compact('businessId', 'country', 'time_zone', 'bussAddr', 'entityType', 'subview'));        
    }

    public function destroy($id){
        $location = Location::findOrFailLoc($id);

        if(!isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'delete-location'))
            abort(404);
        
        $location->delete();
        
        return redirect()->back()->with('message', 'success|Location has been deleted successfully.');
        //route('locations')
    }
}