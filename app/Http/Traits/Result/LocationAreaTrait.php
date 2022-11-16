<?php
/* 
REQUIREMENT: 
    ClientEventsTrait(staffs())
*/
namespace App\Http\Traits\Result;

use Session;
use App\Business;
use App\Location;
use App\LocationArea;
use DB;
use App\Staff;
use \stdClass;
use Auth;

trait LocationAreaTrait {    
    protected function location(){
        $locs = [];
        if(Session::has('businessId')){
            $business = Business::find(Auth::user()->business_id);
            if($business){
                $locations = $business->locations;
                if($locations->count()){
                    foreach($locations as $location){
                        $locs[$location->id] = $location->location_training_area;
                    }
                } 
            }
        }
        return $locs;
    }

protected function locArea($trash = false){
        $locsAreas = [];
        if(Session::has('businessId')){
            $business = Business::find(Auth::user()->business_id);
            if($business){
                $locations = $business->locations;
                if($locations->count()){
                    foreach($locations as $location){
                        if($trash)
                            $areas = $location->areasWithTrashed;
                        else
                            $areas = $location->areas;
                        
                        if($areas->count())
                            foreach($areas as $area)
                                if($area->staffs->count())
                                    $locsAreas[$area->la_id] = $location->location_training_area.' - '.$area->la_name;
                    }
                } 
            }
        }
        return $locsAreas;
    }


    protected function ifServicesExit($data){
        $areaStaffData = new stdClass();
        $areaStaffData->staffId = $data['staffId'];
        $areaStaffData->areaId = $data['areaId'];
        if(count(Staff::getServicesByArea($areaStaffData)))
            return true;
        return false;
    }

    protected function locAreasForEvents(){
        $ifClassesExit = $ifServicesExit = false;
        $locsAreas = /*$defaultStaffDetails = */[];

        /*if(Session::has('businessId')){
            $business = Business::with('locations.areas.staffs')->find(Session::get('businessId'));
            if($business && $business->locations->count()){
                foreach($business->locations as $location){*/
        if(Session::has('ifBussHasLocations') && Session::has('ifBussHasStaffs') && Session::has('ifBussHasServices') && Session::has('ifBussHasClasses')){
           /* if(isUserType(['Staff'])){
                $staff = Staff::with('areas.location')->find(Auth::user()->account_id);
                if($staff->areas->count()){
                    //$defaultStaffDetails['name'] = $staff->fullName;
                    foreach($staff->areas as $area){
                        $locsAreas[$area->la_id] = $area->location->location_training_area.' - '.$area->la_name;
                        if(!$ifClassesExit || !$ifServicesExit){
                            if(!$ifClassesExit && $staff->classes()->exists())
                               $ifClassesExit = true;

                            if(!$ifServicesExit)
                                $ifServicesExit = $this->ifServicesExit(['staffId' => $staff->id, 'areaId' => $area->la_id]);
                        }
                    }
                }
            }
            else  if(isUserType(['Admin'])){    */            
                $locations = Location::with('areas.staffs')->where('business_id', Session::get('businessId'))->get();
                if($locations->count()){
                    foreach($locations as $location){
                        if($location->areas->count()){
                            foreach($location->areas as $area){
                                if($area->staffs->count()){
                                    $locsAreas[$area->la_id] = $location->location_training_area.' - '.$area->la_name;
                                    if(!$ifClassesExit || !$ifServicesExit){
                                        foreach($area->staffs as $staff){
                                            if(!$ifClassesExit && $staff->classes->count())
                                               $ifClassesExit = true;

                                            if(!$ifServicesExit)
                                                $ifServicesExit = $this->ifServicesExit(['staffId' => $staff->id, 'areaId' => $area->la_id]);
                                            /*{
                                                $areaStaffData = new stdClass();
                                                $areaStaffData->staffId = $staff->id;
                                                $areaStaffData->areaId = $area->la_id;
                                                if(count(Staff::getServicesByArea($areaStaffData)))
                                                    $ifServicesExit = true;
                                            }*/
                                        }
                                    }
                                }
                            }
                        }
                    }
                }            
            //}
        }

        return ['locsAreas' => $locsAreas, 'ifClassesExit' => $ifClassesExit, 'ifServicesExit' => $ifServicesExit/*, 'defaultStaffDetails' => $defaultStaffDetails*/];
    }

    protected function staffs($areaId){
        $stff = [];
        $staffs = collect();

        if($areaId == 'all'){
             //if(Auth::user()->hasPermission(Auth::user(), 'list-staff')){
                $staffIdsObj = DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->distinct()->select('as_staff_id')->get();
                if(count($staffIdsObj)){
                    $staffIds = [];
                    foreach($staffIdsObj as $staffIdObj)
                        $staffIds[] = $staffIdObj->as_staff_id;

                    $staffs = Staff::find($staffIds);
                }
            // }
            /*else if(isUserType(['Staff']) && Staff::staffHasArea(Auth::user()->account_id))
                $stff[Auth::user()->account_id] = Auth::user()->name. ' '.Auth::user()->last_name;*/
        }
        else{
            //if(Auth::user()->hasPermission(Auth::user(), 'list-staff')){
                $area = LocationArea::find($areaId);
                if($area)
                    $staffs = $area->staffs;
            //}
            /*else if(isUserType(['Staff']) && $this->isAreaLinkedToStaff(['areaId' => $areaId, 'staffId' => Auth::user()->account_id]))
                $stff[Auth::user()->account_id] = Auth::user()->name. ' '.Auth::user()->last_name;*/
        }

        if($staffs->count())
            foreach($staffs as $staff)
                $stff[$staff->id] = $staff->first_name. ' '.$staff->last_name;
            
        return $stff;
    }
}