<?php

namespace App\Http\Controllers\Setings\Location;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Business;
use App\Location;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;

class LocationController extends Controller{
	
	public function index(){
        if(!Auth::user()->hasPermission(Auth::user(), 'list-location'))
            return redirect('unauthorized');
		$allLocations = array();
        if(Session::has('businessId'))
			$allLocations = Business::find(Session::get('businessId'))->locations;
		return view('Settings.location.index', compact('allLocations'));
    }
	
	public function uploadFile(Request $request){
		$locationId = (int)$request->id;
        $loc = Location::find($locationId);
        $loc->update(array('logo' => $request->photoName));
        return url('/uploads/thumb_'.$request->photoName);
    }
	public function show($id){
    	if(!Auth::user()->hasPermission(Auth::user(), 'view-location'))
            return redirect('unauthorized');

		$serviceStaffvalue = array();
		$serviceAreaValue = array();

		$location = Location::findOrFail($id);
		$countries = \Country::getCountryLists();
		$location->stateName = \Country::getStateName($location->country, $location->state);
		return view('Settings.location.show', compact('location','countries'));
    }
	public function getHours($locId){
		return Location::getLocHours($locId);
	}

	public function getAreas($locId){
		return Location::getLocAreas($locId);
	}
	public function update($id, Request $request){
		$this->validate($request, ['name' => 'required', 'sku_id' => 'required', 'description' => 'required', 'logo' => 'required', 'sale_price' => 'required', 'tax' => 'required', 'stock_location' => 'required', 'stock_level' => 'required', 'history' => 'required', 'cost_price' => 'required', 'stock_note' => 'required', ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        Session::flash('flash_message', 'Product updated!');

        return redirect('settings/product');
    }
	
	public function subview(Request $request){
		if(!Auth::user()->hasPermission(Auth::user(), 'create-location'))
            return redirect('unauthorized');

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
	}
	
    public function store(Request $request){	
		if($request->venue == 'Area'){
			$isError = false;
            $msg = [];

            if(!Auth::user()->hasPermission(Auth::user(), 'create-location-area')){
	            if($request->ajax())
	                $isError = true;
	            else
	                return redirect('unauthorized');
	        }
            
            if(!$isError){
				$insertData = array('la_location_id' => $request->location, 'la_name' => $request->areaName, 'la_logo' => $request->areaLogo, 'created_at' => 'now()', 'updated_at' => 'now()');
				if($request->stuff_selection && $request->stuff_selection != '')
					$insertData['la_staff'] = implode(',',$request->stuff_selection);
				$areaId = DB::table('location_areas')->insertGetId($insertData);
				$input = $request->all();
				ksort($input);
				$monday_start = $monday_end = $tuesday_start = $tuesday_end = $wednesday_start = $wednesday_end = $thursday_start = $thursday_end = $friday_start = $friday_end = $saturday_start = $saturday_end = $sunday_start = $sunday_end = [];
				if($request->exists('monday')){
					foreach($input as $key => $value){
						if(strpos($key, 'monday_start') !== false)
							$monday_start[] = $value;
						
						else if(strpos($key, 'monday_end') !== false)
							$monday_end[] = $value;
					}	
				}
				if($request->exists('tuesday')){
					foreach($input as $key => $value){
						if(strpos($key, 'tuesday_start') !== false)
							$tuesday_start[] = $value;
						
						else if(strpos($key, 'tuesday_end') !== false)
							$tuesday_end[] = $value;
					}	
				}
				if($request->exists('wednesday')){
					foreach($input as $key => $value){
						if(strpos($key, 'wednesday_start') !== false)
							$wednesday_start[] = $value;
						
						else if(strpos($key, 'wednesday_end') !== false)
							$wednesday_end[] = $value;
					}	
				}
				if($request->exists('thursday')){
					foreach($input as $key => $value){
						if(strpos($key, 'thursday_start') !== false)
							$thursday_start[] = $value;
						
						else if(strpos($key, 'thursday_end') !== false)
							$thursday_end[] = $value;
					}	
				}
				if($request->exists('friday')){
					foreach($input as $key => $value){
						if(strpos($key, 'friday_start') !== false)
							$friday_start[] = $value;
						
						else if(strpos($key, 'friday_end') !== false)
							$friday_end[] = $value;
					}	
				}
				if($request->exists('saturday')){
					foreach($input as $key => $value){
						if(strpos($key, 'saturday_start') !== false)
							$saturday_start[] = $value;
						
						else if(strpos($key, 'saturday_end') !== false)
							$saturday_end[] = $value;
					}	
				}
				if($request->exists('sunday')){
					foreach($input as $key => $value){
						if(strpos($key, 'sunday_start') !== false)
							$sunday_start[] = $value;
						
						else if(strpos($key, 'sunday_end') !== false)
							$sunday_end[] = $value;
					}	
				}
				$insertData = array();
				if(count($monday_start)){
					for($i=0; $i<count($monday_start); $i++){
						$insertData[] = array('hr_entity_id' => $areaId, 'hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($tuesday_start)){
					for($i=0; $i<count($tuesday_start); $i++){
						$insertData[] = array('hr_entity_id' => $areaId, 'hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($wednesday_start)){
					for($i=0; $i<count($wednesday_start); $i++){
						$insertData[] = array('hr_entity_id' => $areaId, 'hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($thursday_start)){
					for($i=0; $i<count($thursday_start); $i++){
						$insertData[] = array('hr_entity_id' => $areaId, 'hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($friday_start)){
					for($i=0; $i<count($friday_start); $i++){
						$insertData[] = array('hr_entity_id' => $areaId, 'hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($saturday_start)){
					for($i=0; $i<count($saturday_start); $i++){
						$insertData[] = array('hr_entity_id' => $areaId, 'hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($sunday_start)){
					for($i=0; $i<count($sunday_start); $i++){
						$insertData[] = array('hr_entity_id' => $areaId, 'hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i], 'hr_entity_type' => 'area');
					}
				}
				if(count($insertData))
					DB::table('hours')->insert($insertData);

				$msg['status'] = 'added';
				//return json_encode($msg);
			}
			return json_encode($msg);
		}
		else{
            $isError = false;
            $msg = [];

            if(!Auth::user()->hasPermission(Auth::user(), 'create-location')){
	            if($request->ajax())
	                $isError = true;
	            else
	                return redirect('unauthorized');
	        }
            
            if(!$isError){
            	$email = Location::where('email', '=', $request->email)->count();
	            if($email > 0){
	            	$msg['status'] = 'error';
	            	$msg['errorData'][] = array('emailExist' => 'This email is already in use!');
	            	$isError = true;
	            }
	            $locNameExist = Location::where('location_training_area', '=', $request->location_training_area)->count();
	            if($locNameExist > 0) {
	            	$msg['status'] = 'error';
	            	$msg['errorData'][] = array('locNameExist' => 'This location name is already in use!');
	            	$isError = true;
	            }
	            
	            if(!$isError){
					$insertData = array('location_training_area' => $request->location_training_area, 'logo' => $request->locationLogo, 'website' => $request->website, 'facebook' => $request->facebook, 'email' => $request->email, 'phone' => $request->phone, 'city' => $request->city, 'country' => $request->country, 'state' => $request->state, 'postal_code' => $request->postal_code, 'time_zone' => $request->time_zone);
					
					if($request->fixed_location == 'Fixed Location'){
						$insertData['fixed_location'] = 1;
						$insertData['address_line_one'] = $request->address_line_one;
						$insertData['address_line_two'] = $request->address_line_two;
					}
					if($request->disp_location_web)
						$insertData['disp_location_web'] = $request->disp_location_web;
					if($request->disp_location_online)
						$insertData['disp_location_online'] = $request->disp_location_online;
					
					$business = Business::find($request->businessId);
					$addedLocation = $business->locations()->create($insertData);
					Session::put('ifBussHasLocations' , true); 
					$input = $request->all();
					ksort($input);
					$monday_start = $monday_end = $tuesday_start = $tuesday_end = $wednesday_start = $wednesday_end = $thursday_start = $thursday_end = $friday_start = $friday_end = $saturday_start = $saturday_end = $sunday_start = $sunday_end = [];
					if($request->exists('monday')){
						foreach($input as $key => $value){
							if(strpos($key, 'monday_start') !== false)
								$monday_start[] = $value;
							
							else if(strpos($key, 'monday_end') !== false)
								$monday_end[] = $value;
						}	
					}
					if($request->exists('tuesday')){
						foreach($input as $key => $value){
							if(strpos($key, 'tuesday_start') !== false)
								$tuesday_start[] = $value;
							
							else if(strpos($key, 'tuesday_end') !== false)
								$tuesday_end[] = $value;
						}	
					}
					if($request->exists('wednesday')){
						foreach($input as $key => $value){
							if(strpos($key, 'wednesday_start') !== false)
								$wednesday_start[] = $value;
							
							else if(strpos($key, 'wednesday_end') !== false)
								$wednesday_end[] = $value;
						}	
					}
					if($request->exists('thursday')){
						foreach($input as $key => $value){
							if(strpos($key, 'thursday_start') !== false)
								$thursday_start[] = $value;
							
							else if(strpos($key, 'thursday_end') !== false)
								$thursday_end[] = $value;
						}	
					}
					if($request->exists('friday')){
						foreach($input as $key => $value){
							if(strpos($key, 'friday_start') !== false)
								$friday_start[] = $value;
							
							else if(strpos($key, 'friday_end') !== false)
								$friday_end[] = $value;
						}	
					}
					if($request->exists('saturday')){
						foreach($input as $key => $value){
							if(strpos($key, 'saturday_start') !== false)
								$saturday_start[] = $value;
							
							else if(strpos($key, 'saturday_end') !== false)
								$saturday_end[] = $value;
						}	
					}
					if($request->exists('sunday')){
						foreach($input as $key => $value){
							if(strpos($key, 'sunday_start') !== false)
								$sunday_start[] = $value;
							
							else if(strpos($key, 'sunday_end') !== false)
								$sunday_end[] = $value;
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
						DB::table('hours')->insert($insertData);

					$msg['status'] = 'added';
	            	$msg['insertId'] = $addedLocation->id;
		        }
	        }
			return json_encode($msg);
		}
    }
}