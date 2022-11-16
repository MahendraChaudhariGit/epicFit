<?php
namespace App\Http\Controllers\Setings\Service;
use App\ClientMember;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Clas;
use App\ClassCat;
use Session;
use App\MemberShip;
use App\Business;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\ClientTrait;
use DB;
use Input;
use App\Http\Traits\StaffEventsTrait;
use App\StaffEventBusy;
use App\StaffEventClass;
use App\Staff;
use App\LocationArea;
use App\StaffEventSingleService;
//use Carbon\Carbon;
use App\Http\Traits\StaffTrait;
use App\Resource;
use App\Http\Traits\ServiceResourceTrait;
use App\Http\Traits\ResourceTrait;
use App\ServiceResources;
//use App\StaffEventResource;
use App\Http\Traits\StaffEventResourceTrait;

//use App\StaffEvent;

class ClassController extends Controller{
    use HelperTrait, StaffEventsTrait, StaffTrait,ServiceResourceTrait, ResourceTrait, StaffEventResourceTrait, ClientTrait;
    
    private $cookieSlug = 'class';
    
    public function index(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-class'))
        abort(404);
        
        $classes = array();
        $search = $request->get('search');
        //if(Session::has('businessId')){
            $length = $this->getTableLengthFromCookie($this->cookieSlug);
            //$classes = Clas::where('cl_business_id', Session::get('businessId'))->paginate($length)cl_location_id;
            if($search)
            $classes = Clas::with('location')->OfBusiness()->where('cl_name', 'like', "%$search%")->paginate($length);
            else
            $classes = Clas::with('location')->OfBusiness()->paginate($length);
            //$classes = Business::find(Session::get('businessId'))->classes;
            //}
            
            return view('Settings.service.class.index', compact('classes'));
        }
        
        public function all(){
            /*if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-class'))
            abort(404);*/
            
            $cls = [];
            if(isUserType(['Staff'])){
                $classes = Clas::whereHas('staffs', function($query){
                    $query->where('id', Auth::user()->pk);
                })
                ->OfBusiness()
                ->get();
            }
            else if(eligibleAccounts(['Admin']) || Session::get('hostname') == 'result'){
                $classes = Clas::OfBusiness()->has('staffs')->get();
            }
            if($classes->count()){
                $i = 0;
                foreach($classes as $class){
                    $cls[$i]['id'] = $class->cl_id;
                    $cls[$i]['name'] = $class->cl_name;
                    $cls[$i]['duration'] = $class->cl_duration;
                    $cls[$i]['capacity'] = $class->cl_capacity;
                    $cls[$i]['price'] = $class->cl_price;
                    $i++;
                }
            }
            return json_encode($cls);
        }

        public function allClassesByCondition(Request $request){
            $cls = [];
            $query = Clas::OfBusiness();
            
            if($request->has('area_id')){
                $query->where('cl_location_id',$request->area_id);
            }
            $classes = $query->get();
             if($classes->count()){
                $i = 0;
                foreach($classes as $class){
                        $cls[$i]['id'] = $class->cl_id;
                        $cls[$i]['name'] = $class->cl_name;
                        $cls[$i]['duration'] = $class->cl_duration;
                        $cls[$i]['capacity'] = $class->cl_capacity;
                        $cls[$i]['price'] = $class->cl_price;
                        $cls[$i]['tax'] = $class->cl_tax;
                        $i++;
                }
            }
       
            return json_encode($cls);
        }
        
        public function show($id){
            $class = Clas::findOrFailClass($id);
            
            if(!Auth::user()->hasPermission(Auth::user(), 'view-class'))
            abort(404);
            
            //$class = Clas::find($id);
            
            $classStaffs = $class->staffs;
            $clStaffs = [];
            foreach($classStaffs as $classStaff)
            $clStaffs[] = $classStaff->first_name.' '.$classStaff->last_name;
            
            return view('Settings.service.class.show', compact('class', 'clStaffs'));
        }
        
        public function store(Request $request){
            if($request->businessId != Session::get('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-class')){
                if($request->ajax())
                return '0';
                else
                abort(404);
            }
            
            $class = new Clas;
            $class->cl_business_id = $request->businessId;
            $class->cl_clcat_id = $request->classCat;
            $class->cl_location_id = $request->classLoc;
            $class->cl_name = $request->className;
            $class->cl_description = $request->classDescription;
            $class->cl_colour = $request->classColour;
            $class->cl_logo = $request->classLogo;
            if($request->bookOnline)
            $class->cl_book_online = $request->bookOnline;
            $class->cl_duration = $request->classDuration;
            $class->cl_capacity = $request->classCapacity;
            $class->cl_price = $request->classPrice;
            $class->cl_tax = $request->classTax; 
            if($request->classIfPadding){
                $class->cl_pad_before = $request->classPadBefore;
                $class->cl_proc_time = $request->classProcesTime;
            }
            
            $class->save();
            $this->storeResources($class->cl_id,$request,'create','App\Clas');
            
            if($request->classStaffs != '')
            $class->staffs()->attach($request->classStaffs);
            
            if($request->classAreas != '')
            $class->areas()->attach($request->classAreas);

             # Add membership
             if($request->classMembership && count($request->classMembership)) {
                    foreach ($request->classMembership as $membershipId) {
                        $membership = MemberShip::find($membershipId);
                        $membership->classmember()->attach($class->cl_id); 
                    }
            }
            
            Session::put('ifBussHasClasses' , true);  
            
            $msg['status'] = 'added';
            $msg['insertId'] = $class->cl_id;
            return json_encode($msg);       
        }
        
        public function freeStaffs($id, Request $request){
            $stff = [];
            //if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-staff')){
                $date = $request->date;
                $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $date, 'duration' => $request->duration]);
                
                $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                $eventEndDatetime = $startAndEndDatetime['endDatetime'];
                $startTime = $this->datetimeToTime($eventStartDatetime);
                $endTime = $this->datetimeToTime($eventEndDatetime);
                
                $workingStaffsId = $this->getWorkingStaffs([$id], ['type'=>'class','day'=>$request->day,'date'=>$date, 'startTime'=>$startTime, 'endTime'=>$endTime]);
                
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
                                        $stff[$staff->id] = ['name'=>$staff->fullName, 'favAreas'=>implode(',', $staff->favAreas->pluck('la_id')->toArray())];
                                    }
                                }
                            }                                                           
                        }                                                           
                    }
                }   
                return $stff;
            }
            
            public function freeAreas($id, Request $request){
                $locsAreas = [];
                //if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-location-area')){
                    $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $request->date, 'duration' => $request->duration]);
                    $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                    $eventEndDatetime = $startAndEndDatetime['endDatetime'];
                    
                    $startTime = $this->datetimeToTime($eventStartDatetime);
                    $endTime = $this->datetimeToTime($eventEndDatetime);
                    /*$workingAreasId = DB::table('hours')->join('area_classes', 'hr_entity_id', '=', 'ac_la_id')
                    ->where('ac_cl_id', $id)
                    
                    ->where('hr_entity_type', 'area')
                    ->where('hr_day', $request->day)
                    ->where('hr_start_time', '<=', $startTime)
                    ->where('hr_end_time', '>=', $endTime)
                    ->whereNull('hours.deleted_at')
                    
                    ->distinct()
                    ->select('hr_entity_id')
                    ->pluck('hr_entity_id');*/
                    $query = DB::table('hours')->join('area_classes', 'hr_entity_id', '=', 'ac_la_id')
                    ->where('ac_cl_id', $id)
                    
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
                    ->pluck('hr_entity_id')->toArray();
                    
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
                            /*$busyAreasId = StaffEvent::whereIn('se_area_id', $workingAreasId)
                            ->clashingEvents($data)
                            ->select('se_area_id')
                            ->pluck('se_area_id')
                            ->toArray();        */
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
                    //}
                    return $locsAreas;
                }
                
                /*public function classesByAreaStaff(Request $request){
                    $cls = [];
                    $areaId = $request->areaId;
                    $staffId = $request->staffId;
                    
                    //DB::enableQueryLog();
                    $classes = Clas::OfBusiness()
                    ->whereHas('areas', function ($query) use ($areaId){
                        $query->whereIn('ac_la_id', $areaId)
                        ->selectRaw('ac_cl_id, count(1) as count')
                        ->groupBy('ac_cl_id')
                        ->having('count', '>=', count($areaId));
                    })
                    ->whereHas('staffs', function ($query) use ($staffId){
                        $query->where('cst_staff_id', $staffId);
                    })
                    ->get();
                    
                    /*
                    $temp = DB::table('area_classes')->join('classes', 'ac_cl_id', '=', 'cl_id')->whereIn('ac_la_id', $areaId)->selectRaw('cl_name, ac_cl_id, count(1) as count')->groupBy('ac_cl_id')->having('count', '>=', count($areaId))->get();        
                    $classes = Clas::OfBusiness()
                    ->whereHas('areas', function ($query) use ($areaId){
                        //$query->where('ac_la_id', $areaId);
                        $query->whereIn('ac_la_id', $areaId);
                    })
                    ->whereHas('staffs', function ($query) use ($staffId) {
                        $query->where('cst_staff_id', $staffId);
                    })
                    ->get();
                    */
                    //dd(DB::getQueryLog());
                    
                    /*if($classes->count()){
                        $i = 0;
                        foreach($classes as $class){
                            $cls[$i]['id'] = $class->cl_id;
                            $cls[$i]['name'] = $class->cl_name;
                            $cls[$i]['duration'] = $class->cl_duration;
                            $cls[$i]['capacity'] = $class->cl_capacity;
                            $cls[$i]['price'] = $class->cl_price;
                            /*$j = 0;
                            foreach($class->areas as $area){
                                $cls[$i]['area'][$j] = $area->la_id;
                                $j++;
                            }*/
                            /*$i++;
                        }
                    }
                    return json_encode($cls);
                }*/
                
                public function edit($id){
                    
                    $class = Clas::findOrFailClass($id);
                    
                    # memberships already having class
                    $clsMemberships = [];
                    $classMembership = DB::table('class_membership')
                    ->select('cm_member_id')
                    ->where('cm_cl_id', $id)
                    ->get();
                    
                    if($classMembership && count($classMembership)){
                        foreach($classMembership as $mem)
                        $clsMemberships[] = $mem->cm_member_id;
                        asort($clsMemberships);
                    }
                    
                    // dd( $clsMemberships);
                    if(!Auth::user()->hasPermission(Auth::user(), 'edit-class'))
                    abort(404);
                    
                    //if(!Session::has('businessId'))
                    //return redirect('settings/business/create');
                    
                    //class = Clas::find($id);
                    //if($class){
                        $business = Business::with('locations', 'staffs', 'classCats')->find(Session::get('businessId'));
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
                        
                        $clsCat = [];
                        if($business->classCats->count()){
                            foreach($business->classCats as $classCat){
                                if($classCat->clcat_price == null){
                                    $classCat->clcat_price = 0;
                                }
                            $clsCat[$classCat->clcat_id] = ['name' => ucfirst($classCat->clcat_value), 'price' => $classCat->clcat_price];
                           
                            }
                        }
                        
                        $classAreas = $class->areas->pluck('la_id')->toArray();
                        
                        $classStaffs = $class->staffs->pluck('id')->toArray();
                        
                        $res = $this->resourceData($class->cl_location_id,false);
                        $newres = $class->resources;
                        
                        # Get all membership list
                        $allMemberships = [];
                        $memberships = MemberShip::select('me_membership_label', 'id')
                        ->where('me_business_id', Session::get('businessId'))
                        ->get();
                        
                        if($memberships && count($memberships)) {
                            foreach($memberships as $mem)
                            $allMemberships[$mem->id] = ucfirst($mem->me_membership_label);
                            // $allMemberships[] = [
                                //     'mem_label' => ucfirst($mem->me_membership_label),
                                //     'mem_id' => $mem->id
                                // ];
                                asort($allMemberships);
                            }
                            
                            // dd( $allMemberships);
                            //dd($newres);
                            return view('Settings.service.edit', compact('class', 'businessId', 'locs', 'classAreas', 'classStaffs', 'clsCat','res','newres', 'allMemberships', 'clsMemberships'));//, 'stff'
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
                                // dd($request->all());
                                $isError = false;
                                $msg = [];
                                
                                $class = Clas::findClass($id, $request->businessId);
                                
                                if(!$class || !Auth::user()->hasPermission(Auth::user(), 'edit-class')){
                                    if($request->ajax())
                                    return '0';
                                    else
                                    abort(404);
                                }
                                
                                if(!$isError){
                                    //$class = Clas::find($id);
                                    //if($class){
                                        $class->cl_clcat_id = $request->classCat;
                                        $class->cl_location_id = $request->classLoc;
                                        $class->cl_name = $request->className;
                                        $class->cl_description = $request->classDescription;
                                        $class->cl_colour = $request->classColour;
                                        $class->cl_logo = $request->classLogo;
                                        if($request->bookOnline)
                                        $class->cl_book_online = $request->bookOnline;
                                        else
                                        $class->cl_book_online = 0;
                                        $class->cl_duration = $request->classDuration;
                                        $class->cl_capacity = $request->classCapacity;
                                        $class->cl_price = $request->classPrice;
                                        $class->cl_tax = $request->classTax; 
                                        if($request->classIfPadding){
                                            $class->cl_pad_before = $request->classPadBefore;
                                            $class->cl_proc_time = $request->classProcesTime;
                                        }
                                        else
                                        $class->cl_pad_before = $class->cl_proc_time = 0;
                                        
                                        $class->save();
                                        
                                        if($request->classAreas == '')
                                        $classAreas = [];
                                        else
                                        $classAreas = $request->classAreas;
                                        $linkedDeletedAreaIds = Clas::pivotAreasTrashedOnly($class->cl_id);
                                        if(count($linkedDeletedAreaIds))
                                        foreach($linkedDeletedAreaIds as $linkedDeletedAreaId)
                                        $classAreas[] = $linkedDeletedAreaId->ac_la_id;
                                        $class->areas()->sync($classAreas);
                                        
                                        if($request->classStaffs == '')
                                        $classStaffs = [];
                                        else
                                        $classStaffs = $request->classStaffs;
                                        $linkedDeletedStaffIds = Clas::pivotStaffsTrashedOnly($class->cl_id);
                                        if(count($linkedDeletedStaffIds))
                                        foreach($linkedDeletedStaffIds as $linkedDeletedStaffId)
                                        $classStaffs[] = $linkedDeletedStaffId->cst_staff_id;
                                        $class->staffs()->sync($classStaffs);
                                        
                                        //dd($request);
                                        
                                        $this->storeResources($id,$request,'edit','App\Clas');
                                        
                                        if($request->has('effectiveDate') && $request->effectiveDate != ""){
                                            $effectiveData = array();
                                            $effectiveData['date'] = $request->effectiveDate;
                                            $effectiveData['price'] = $request->classPrice;
                                            $effectiveData['type'] = 'class';
                                            $this->updateEffectivePrice($id, $effectiveData);
                                        }
                                        
                                        # Manage Membership
                                        if($request->classMembership && count($request->classMembership)) {
                                            $membershipExist = DB::table('class_membership')->where('cm_cl_id', $id)->pluck('cm_member_id');
                                            
                                            # Membership removed
                                            $removedMembership = array_diff($membershipExist, $request->classMembership);
                                       
                                            if($removedMembership && count($removedMembership)) {
                                                foreach ($removedMembership as $membershipId) {
                                                    $this->updateFutureMembershipClass([$id], $membershipId); 
                                                    
                                                    # Delete class membership 
                                                    $classMemResult = DB::table('class_membership')
                                                    ->where('cm_member_id', $membershipId)
                                                    ->where('cm_cl_id', $id)
                                                    ->delete();
                                                }  
                                            }
                                            
                                            # Membership newly added
                                            $addedMembership = array_diff($request->classMembership, $membershipExist);
                                            
                                            if($addedMembership && count($addedMembership)) {
                                                foreach ($addedMembership as $membershipId) {
                                                    $membership = MemberShip::find($membershipId);

                                                    # Add new membership
                                                    $membership->classmember()->attach($id); 
                                                }
                                            }
                                        }
                                        
                                        $msg['status'] = 'updated';
                                        //}
                                    }
                                   
                                    return json_encode($msg);       
                                }

                                public function uploadFile(Request $request){
                                    //$service = Service::findService($request->id);
                                    //if($service && Auth::user()->hasPermission(Auth::user(), 'edit-service')){
                                    $class = Clas::find($request->id);
                                    if($class){
                                        $class->update(['cl_logo' => $request->photoName]);
                                        
                                        return url('/uploads/thumb_'.$request->photoName);
                                    }
                                    return '';
                                }
                                
                                public function create(Request $request){
                                    
                                    if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-class'))
                                    abort(404);
                                    
                                    //if(!Session::has('businessId'))
                                    //return redirect('settings/business/create');
                                    
                                    $business = Business::with('locations', 'staffs', 'classCats')->find(Session::get('businessId'));
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
                                    
                                    $clsCat = [];
                                    if($business->classCats->count()){
                                        foreach($business->classCats as $classCat){
                                            if($classCat->clcat_price == null){
                                                $classCat->clcat_price = 0;
                                            }
                                        $clsCat[$classCat->clcat_id] = ['name' => ucfirst($classCat->clcat_value), 'price' => $classCat->clcat_price];
                                       
                                        }
                                        asort($clsCat);
                                    }
                                    
                                    $entityType = 'class';  
                                    if($request->has('subview'))
                                    $subview = true;  
                                    $res = $newres = [];
                                    
                                    # Get all membership list
                                    $allMemberships = [];
                                    $memberships = MemberShip::select('me_membership_label', 'id')
                                    ->where('me_business_id', Session::get('businessId'))
                                    ->get();
                                    
                                    if($memberships && count($memberships)) {
                                        foreach($memberships as $mem)
                                        $allMemberships[$mem->id] = ucfirst($mem->me_membership_label);
                                        asort($allMemberships);
                                    }
                                    
                                    return view('Settings.service.edit', compact('businessId', 'locs', 'clsCat', 'entityType', 'subview','res','newres', 'allMemberships'));//, 'stff'
                                }
                                
                                public function destroy($id){
                                    $class = Clas::findOrFailClass($id);
                                    
                                    if(!isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'delete-class'))
                                    abort(404);
                                    
                                    //$class = Clas::find($id);
                                    //if($class)
                                    $class->delete();
                                    
                                    return redirect()->back()->with('message', 'success|Class has been deleted successfully.');
                                    //route('classes')
                                }
                                
                                public function checkAssociation($id){
                                    $class = Clas::findClass($id);
                                    if($class && $class->futureEventClasses()->exists())
                                    return 1;
                                    
                                    return 0;
                                }
                                
                                public function freeResources($id, Request $request){
                                    /* Getting linked resources */
                                    //DB::enableQueryLog();
                                    $loc = Clas::OfBusiness()->select('cl_location_id')->withTrashed()->find($id)->cl_location_id;
                                    /*$linkedResources = ServiceResources::with(array('resource' => function($query){
                                        $query->select('id', 'res_name');
                                    }, 'resource.items' => function($query) use($loc){
                                        $query->where('ri_location', $loc)->select('ri_id');
                                    })
                                    )
                                    ->OfClas($id)
                                    ->select('sr_res_id', 'sr_item_quantity')
                                    ->get();*/
                                    $linkedResources = ServiceResources::withResource($loc)
                                    ->OfClas($id)
                                    //->withTrashed()
                                    ->get();  
                                    
                                    $return = [];
                                    if($linkedResources->count()){ //If there are resources linked
                                        //dd(DB::getQueryLog());
                                        //dd($linkedResources);
                                        /*$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $request->date, 'duration' => $request->duration]);
                                        $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                                        $eventEndDatetime = $startAndEndDatetime['endDatetime'];
                                        
                                        //Getting clashing class bookings
                                        $query = StaffEventClass::OfBusiness()->where('sec_start_datetime', '<=', $eventEndDatetime)->where('sec_end_datetime', '>=', $eventStartDatetime);
                                        if($request->has('eventId'))
                                        $query->where('sec_id', '<>', $request->eventId);
                                        $clashingEventclas = $query->select('sec_id')->get();
                                        if($clashingEventclas->count())
                                        $clashingEventclas = $clashingEventclas->pluck('sec_id')->toArray();
                                        
                                        //Getting clashing service bookings
                                        $clashingEventServ = StaffEventSingleService::OfBusiness()->where('sess_start_datetime', '<=', $eventEndDatetime)->where('sess_end_datetime', '>=', $eventStartDatetime)->select('sess_id')->get();
                                        if($clashingEventServ->count())
                                        $clashingEventServ = $clashingEventServ->pluck('sess_id')->toArray();
                                        
                                        
                                        $resId = $linkedResources->pluck('resource.id')->toArray();
                                        if(count($clashingEventclas) || count($clashingEventServ)){
                                            //$usedRes = StaffEventResource::whereIn('serc_res_id', $resId)->select('serc_res_id', 'serc_item_quantity')->get();
                                            //DB::enableQueryLog();
                                            // Getting resources in use along with quantity over the event's time frame
                                            $usedRes = StaffEventResource::whereIn('serc_res_id', $resId)
                                            ->where(function($query) use($clashingEventclas, $clashingEventServ){
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
                                            ->select('serc_res_id', 'serc_item_quantity')
                                            ->get();
                                            //dd(DB::getQueryLog());
                                            if($usedRes->count())
                                            $usedRes = $usedRes->pluck('serc_item_quantity', 'serc_res_id')->toArray();
                                            
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
                                            if($usedRes->count())
                                            $usedRes = $usedRes->pluck('serc_item_quantity', 'serc_res_id')->toArray();
                                        }
                                        else
                                        $usedRes = [];*/
                                        
                                        $resId = $linkedResources->pluck('resourceWithTrashed.id')->toArray();
                                        $data = ['time'=>$request->time, 'date'=>$request->date, 'duration'=>$request->duration, 'resId'=>$resId, 'eventType'=>'class', 'locId'=>$loc];
                                        if($request->has('eventId'))
                                        $data['eventId'] = $request->eventId;
                                        $usedRes = $this->ifResourcesInUse($data);
                                        //dd($linkedResources);
                                        foreach($linkedResources as $linkedResource){
                                            $totalQuant = $linkedResource->resourceWithTrashed->itemsWithTrashed->count();
                                            $remainResQuan = $totalQuant;
                                            if($totalQuant && count($usedRes) && array_key_exists($linkedResource->resourceWithTrashed->id, $usedRes)){
                                                //If resource is in use then calculate free quantity if that resource
                                                //$remainResQuan = (int) $linkedResource->sr_item_quantity - $usedRes[$linkedResource->resource->id];
                                                $usedQuant = $usedRes[$linkedResource->resourceWithTrashed->id];
                                                $remainResQuan = $totalQuant - $usedQuant;
                                                //}
                                                //else
                                                //$remainResQuan = $linkedResource->sr_item_quantity;
                                            }
                                            /*if($linkedResource->sr_res_id == 74)
                                            dd($linkedResource->resourceWithTrashed);*/
                                            //if(!$totalQuant || $remainResQuan > 0)
                                            //$return[$linkedResource->resourceWithTrashed->id] = ['resName' => $linkedResource->resourceWithTrashed->res_name, 'itemQuant' => $remainResQuan, 'defaultQuant' => $linkedResource->sr_item_quantity, 'isDeleted' => $linkedResource->trashed()];
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
                                
                                public function updateField(Request $request){ 
                                    $class = Clas::findClass($request->entityId);
                                    
                                    if(!$class || !hasPermission('edit-class')){
                                        if($request->ajax())
                                        return [];
                                        else
                                        abort(404);
                                    }
                                    
                                    if($request->entityProperty == 'color'){
                                        $class->cl_colour = $request->color;
                                        $class->save();
                                    }
                                    
                                    return json_encode([
                                        'status' => 'updated'
                                        ]);
                                    }
   public function allClassType(Request $request){
        $clsCat = [];
        $classTypes = ClassCat::OfBusiness()->get();
        if($request->has('membershipId') && $request->has('clientId')){
            $clientId         = $request->clientId;
            $membId           = $request->membershipId;
            $clientMember = ClientMember::where('cm_client_id', $clientId)
            ->orderBy('id', 'desc')
            ->where('cm_status', 'Active')
            ->first();
        if (count($clientMember)) {
            if ($clientMember->cm_status == 'Active' && $clientMember->cm_membership_id == $membId) {
                if ($clientMember->cm_class_limit != '') {
                    $session_limit = json_decode($clientMember->cm_session_limit, true);
                }
            }
        }

    }
       if($classTypes->count()){
        $i = 0;
        foreach($classTypes as $classCat){
                $clsCat[$i]['id'] = $classCat->clcat_id;
                $clsCat[$i]['name'] = $classCat->clcat_value;
                $clsCat[$i]['price'] = isset($classCat->clcat_price) ?$classCat->clcat_price:0 ;
                if (isset($session_limit[$classCat->clcat_id]) && isset($session_limit[$classCat->clcat_id]['price'])) {
                    $clsCat[$i]['editedPrice'] = $session_limit[$classCat->clcat_id]['price'];
                } else {
                    $clsCat[$i]['editedPrice'] = 0;
                }
                $i++;
        }
            if (count($session_limit)) {
                $extraData = $session_limit;
            }else{
                $extraData = 0;
            }
        $response = array('session' =>$clsCat, 'sessionData' => $extraData);
    }

    return json_encode($response);
   }
}