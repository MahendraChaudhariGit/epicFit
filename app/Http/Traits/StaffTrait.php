<?php
/* 
REQUIREMENT: 
HelperTrait for (getEditFormData())
StaffEventsTrait for (getHoursFromTrait())
*/
namespace App\Http\Traits;

use App\Staff;
use Session;
use App\Business;
use App\UserType;
use File;
use DB;
use stdClass;
use App\LocationArea;
use Carbon\Carbon;
use Auth;
use Cache;

trait StaffTrait{
    protected function getOverviewData($id){
        //$staff = Staff::find($id);
        $staff = Staff::findStaff($id);
        if($staff){
            //$countries = \Country::getCountryLists();
            $staff->stateName = \Country::getStateName($staff->country, $staff->state);
            
        return ['staff' => $staff/*, 'countries' => $countries*/];
    }
    
    return [];
}

protected function getEditFormData($data){
    if(!array_key_exists('staff', $data))
    //$staff = Staff::find($data['id']);
    $staff = Staff::findStaff($data['id']);
    else
    $staff = $data['staff'];
    $areas = $staff->areas;
    $staffAreas = [];
    foreach ($areas as $area) {
        $staffAreas[] = $area->la_id;
    }
    if($staff){
        
        if($staff->biography){
            $this->emptyFileuploadPluginUploadDir();
            
            $uploadedFilePath = $this->getUploadPath().$staff->biography;
            $pluginUploadFilePath = $this->getFileuploadPluginUploadDirPath().$staff->biography;
            File::copy($uploadedFilePath , $pluginUploadFilePath);
        }
        
        $business = Business::with('completedServices', 'classes','sessionrole','commissionrole','commissionsource','incomecategory','locations.areas')->find(Session::get('businessId'));
        $businessId = $business->id;
        
        $serv = array();
        if($business->completedServices->count()){
            foreach($business->completedServices as $service){
                // if($service->is_completed==1)
                //{  
                    if($service->category == 1) // TEAM
                    $serv[$service->id] = ucfirst($service->team_name);
                    else if($service->category == 2) // 1 on 1
                    $serv[$service->id] = ucfirst($service->one_on_one_name);
                    // }
                }
                asort($serv);
            }
            
            $staffServices = Staff::getServices(['staff'=>$staff, 'business'=>$business]);
            
            $clses = array();
            if($business->classes->count()){
                foreach($business->classes as $class)
                $clses[$class->cl_id] = ucfirst($class->cl_name);
                asort($clses);
            }
            
            $staffClassess = $staff->classes;
            $staffClasses = $staffClassess->pluck('cl_id')->toArray();
            
            $staffSessionServices =$staff->sessionservicestaff->pluck('id')->toArray();
            
            $staffSessionCategory = array();
            if($staffClassess->count()){
                foreach($staffClassess as $sclass){
                    if($sclass->pivot['cst_per_session_enable'] == 1)
                    $staffSessionCategory[] = $sclass->pivot['cst_cl_id'];
                }
            }
            
            $sessionrole = array();
            if($business->sessionrole->count()){
                foreach($business->sessionrole as $role)
                $sessionrole[$role->id] = ucfirst($role->sr_value);
                asort($sessionrole);
            }
            $staffSessionRole = $staff->sessionrolestaff->pluck('id')->toArray();    
            
            $commissionrole = array();
            if($business->commissionrole->count()){
                foreach($business->commissionrole as $crole)
                $commissionrole[$crole->id] = ucfirst($crole->cr_value);
                asort($commissionrole);
            }
            $staffCommissionRole = $staff->commissionRoleStaff->pluck('id')->toArray();
            
            $commissionsource = array();
            if($business->commissionsource->count()){
                foreach($business->commissionsource as $csource)
                $commissionsource[$csource->id] = ucfirst($csource->cr_value);
                asort($commissionsource);
            }
            $staffCommissionSource = $staff->commissionSourcestaff->pluck('id')->toArray();    
            
            
            $commissioncategory = array();
            if($business->incomecategory->count()){
                foreach($business->incomecategory as $ccategory)
                $commissioncategory[$ccategory->id] = ucfirst($ccategory->category_name);
                asort($commissioncategory);
            }         
            $staffCommissionCategory = $staff->commissionCategorystaff->pluck('id')->toArray();
            
            $staffAttendeeArr=$staff->attendees;
            
            //dd($staffAttendeeArr);
            $permTypes = UserType::all();
            $permTyp = array('' => '-- Select --');
            if($permTypes->count())
            foreach($permTypes as $permType)
            $permTyp[$permType->ut_id] = ucfirst($permType->ut_name);
            asort($permTyp);
            
            $country = ['' => '-- Select --'] + \Country::getCountryLists();
            
            $states = $this->getStates($staff->country);
            
            $time_zone = ['' => '-- Select --'] + \TimeZone::getTimeZone();
            
            return compact('staff','business', 'businessId', 'serv', 'clses', 'permTyp', 'country', 'states', 'time_zone', 'staffServices', 'staffClasses','sessionrole','staffSessionRole','staffSessionServices','commissionrole','staffCommissionRole','commissionsource','staffCommissionSource','commissioncategory','staffCommissionCategory','staffSessionCategory','staffAttendeeArr','staffAreas');
        }
        
        return [];
    }
    
    protected function getUploadPath(){
        return public_path().'/uploads/';
    }
    
    protected function getHoursFromTrait($request){
        
        //dd($request->all());
        if($request->has('editStartDate') && $request->has('editEndDate'))
        $param = ['startDate'=>$request->editStartDate, 'endDate'=>$request->editEndDate];
        else
        $param = [];
        
        $newHours =   Cache::remember('staffHours', 60, function() use ($request,  $param) {
            $hrs = [];
            if(isUserType(['Staff']) && ($request->staffId == 'all' || $request->staffId == 'all-ros'))
            $hrs = Staff::getStaffHours(Auth::user()->account_id, '', $param);
            else if(Session::has('ifBussHasStaffs') && $request->staffId && $request->areaId /*&& isUserType(['Admin'])*/){
                if($request->staffId != 'all' && $request->staffId != 'all-ros' && Staff::ifstaffExist($request->staffId))
                $hrs = Staff::getStaffHours($request->staffId, '', $param);
                
                else if(Session::has('ifBussHasLocations') && ($request->staffId == 'all' || $request->staffId == 'all-ros')){
                    if($request->areaId == 'all'){
                        $idsObj = DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->whereNull('deleted_at')->distinct()->select('as_la_id', 'as_staff_id')->get();
                        if(count($idsObj)){
                            $staffCovered = [];
                            foreach($idsObj as $idObj){
                                if(!in_array($idObj->as_staff_id, $staffCovered)){
                                    if($request->staffId == 'all'){
                                        $hours = Staff::getStaffHours($idObj->as_staff_id, '', $param);  
                                        foreach($hours as $hour)
                                        $hrs[] = $hour;
                                        $staffCovered[] = $idObj->as_staff_id;
                                    }
                                    else{
                                        $hours = [];
                                        $staffActivityData = new stdClass();
                                        $staffActivityData->staffId = $idObj->as_staff_id;
                                        $staffActivityData->areaId = $idObj->as_la_id;
                                        $staffActivityData->startDate = $request->startDate;
                                        
                                        if(isset($request->day)){
                                            $staffActivityData->day = $request->day;
                                            if($this->staffHasDayActivity($staffActivityData))
                                            $hours = Staff::getStaffHours($idObj->as_staff_id, $request->day, $param);
                                        }
                                        else if(isset($request->endDate)){
                                            $staffActivityData->endDate = $request->endDate;
                                            if($this->staffHasWeekActivity($staffActivityData))
                                            $hours = Staff::getStaffHours($idObj->as_staff_id, '', $param);
                                        }
                                        
                                        if(count($hours)){
                                            $staffCovered[] = $idObj->as_staff_id;
                                            foreach($hours as $hour)
                                            $hrs[] = $hour;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else{
                        $area = LocationArea::findArea($request->areaId);
                        if($area){
                            $staffs = $area->staffs;
                            if($staffs->count()){
                                foreach($staffs as $staff){
                                    if($request->staffId == 'all'){
                                        $hours = Staff::getStaffHours($staff->id, '', $param);  
                                        foreach($hours as $hour)
                                        $hrs[] = $hour;
                                    }
                                    else{
                                        $hours = [];
                                        $staffActivityData = new stdClass();
                                        $staffActivityData->staffId = $staff->id;
                                        $staffActivityData->areaId = $request->areaId;
                                        $staffActivityData->startDate = $request->startDate;
                                        
                                        if(isset($request->day)){
                                            $staffActivityData->day = $request->day;
                                            if($this->staffHasDayActivity($staffActivityData))
                                            $hours = Staff::getStaffHours($staff->id, $request->day, $param);
                                        }
                                        else if(isset($request->endDate)){
                                            $staffActivityData->endDate = $request->endDate;
                                            if($this->staffHasWeekActivity($staffActivityData))
                                            $hours = Staff::getStaffHours($staff->id, '', $param);
                                        }
                                        
                                        if(count($hours))
                                        foreach($hours as $hour)
                                        $hrs[] = $hour;
                                    }
                                }
                            }
                        }
                    }
                }   
            }
            
            return $hrs;
        });
        return $newHours;
    }
    
    /**
    * Mark given areas as favorite for the given staff. It automatically remove its last favorited areas, if any. 
    *
    * @param integer $staffId Staff ID
    * @param array $areasId Areas ID
    * @param date $expiryDate Expiry date of favorite
    * 
    */ 
    protected function changeFavArea($staffId, $areasId, $expiryDate){
        DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->where('as_staff_id', $staffId)->whereNull('deleted_at')->whereNotNull('as_fav_expiry')->update(['as_fav_expiry' => null]);
        
        DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->where('as_staff_id', $staffId)->whereIn('as_la_id', $areasId)->whereNull('deleted_at')->update(['as_fav_expiry' => $expiryDate]);
    }
    
    /**
    * Remove favorited areas that met their expiry date.
    * 
    */ 
    protected function deleteExpiredFav(){
        $now = new Carbon();
        $todayDate = $now->toDateString();
        
        DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->where('as_fav_expiry', '<', $todayDate)->whereNull('deleted_at')->update(['as_fav_expiry' => null]);
    }
    
    
    /**
    * get all working staff id
    * @param
    * @return
    **/
    protected function getWorkingStaffs($ids, $staffData){
        //DB::enableQueryLog();
        $query = DB::table('hours');
        if($staffData['type'] == 'class')
        $query->join('class_staffs', 'hr_entity_id', '=', 'cst_staff_id')
        ->whereIn('cst_cl_id', $ids);
        
        elseif($staffData['type'] == 'service')
        $query->whereIn('hr_entity_id', $ids);
        
        $query->where('hr_entity_type', 'staff')
        ->where('hr_day', $staffData['day'])
        ->where('hr_start_time', '<=', $staffData['startTime'])
        ->where('hr_end_time', '>=', $staffData['endTime'])
        ->whereNull('hours.deleted_at');
        
        if(isUserType(['Staff'])){
            $query->whereIn('hr_entity_id', [Auth::user()->pk]);
        }
        
        $allStaffsId = $query->distinct()
        ->select('hr_entity_id')
        ->pluck('hr_entity_id')->toArray();
        
        $leaveStaffIds = DB::table('hours')
        ->where('hr_entity_type', 'staff')
        ->whereNull('deleted_at')
        ->where('hr_day', $staffData['day'])
        ->where('hr_edit_date', $staffData['date'])
        ->where('hr_start_time','=','hr_end_time')
        ->pluck('hr_entity_id')->toArray();
        
        
        $workingStaffsId = array_diff($allStaffsId, $leaveStaffIds);
    
        //dd(DB::getQueryLog());
        
        return $workingStaffsId;
    }
}