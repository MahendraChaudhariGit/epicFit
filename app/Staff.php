<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Session;
use App\Location;
use Carbon\Carbon;
use App\Service;
use App\StaffEventBusy;
use App\LocationArea;

class Staff extends Model{
    use SoftDeletes;

    protected $table = 'staff';
	protected $fillable = ['business_id', 'first_name', 'last_name', 'gender', 'date_of_birth', 'job_title', 'ut_id', 'biography', 'profile_picture', 'login_with_email', 'clients_book_staff', 'website', 'facebook', 'email', 'phone', 'fixed_location', 'address_line_one', 'address_line_two', 'city', 'country', 'state', 'postal_code', 'time_zone', 'disp_location_web', 'disp_location_online', 'hourly_payment_label', 'hourly_rate', 'hourly_start_date', 'hourly_end_date','commission_payment','commission_rate','commission_tax','commission_date_range_start','commission_date_range_end','per_session_rate_options','per_session_payment_label','per_session_base_rate','per_session_pay_for','per_session_start_date','per_session_end_date'];

    public function setHourlyRateAttribute($rate){
        $this->attributes['hourly_rate'] = $rate?$rate:null;
    }

    public function setHourlyStartDateAttribute($date){
        $this->attributes['hourly_start_date'] = $date?dateStringToDbDate($date):null;
    }

    public function setHourlyEndDateAttribute($date){
        $this->attributes['hourly_end_date'] = $date?dateStringToDbDate($date):null;
    }

    public function setPerSessionStartDateAttribute($date){
        $this->attributes['per_session_start_date'] = $date?dateStringToDbDate($date):null;
    }

    public function setPerSessionEndDateAttribute($date){
        $this->attributes['per_session_end_date'] = $date?dateStringToDbDate($date):null;
    }

    public function setCommissionDateRangeStartAttribute($date){
        $this->attributes['commission_date_range_start'] = $date?dateStringToDbDate($date):null;
    }

    public function setCommissionDateRangeEndAttribute($date){
        $this->attributes['commission_date_range_end'] = $date?dateStringToDbDate($date):null;
    }

    public function getHourlyStartDateAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }

    public function getHourlyEndDateAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }

    public function getPerSessionStartDateAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }

    public function getPerSessionEndDateAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }

    public function getCommissionDateRangeStartAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }

    public function getCommissionDateRangeEndAttribute($value){
        if($value != null)
            return dbDateToDateString(Carbon::createFromFormat('Y-m-d', $value));
        return '';
    }

    public function getFullNameAttribute(){
        return $this->first_name.' '.$this->last_name;
    }

    public function getOverviewDobAttribute(){
        if($this->date_of_birth != '0000-00-00')
            return dbDateToDateString($this->carbonDob);
        return '';
    }

    public function getCarbonDobAttribute(){
        return Carbon::createFromFormat('Y-m-d', $this->date_of_birth);
    }

    public function getBirthDayAttribute(){
        if($this->date_of_birth != '0000-00-00')
            return $this->carbonDob->day;
        return '';
    }

    public function getBirthYearAttribute(){
        if($this->date_of_birth != '0000-00-00')
            return $this->carbonDob->year;
        return '';
    }

    public function getBirthMonthAttribute(){
        if($this->date_of_birth != '0000-00-00')
            return $this->carbonDob->month;
        return '';
    }

    public function getHoursAttribute(){
        //$hours = $this->getHours($this->id);
        /*$hours = DB::table('hours')->where('hr_entity_id', $this->id)
                                ->where('hr_entity_type', 'staff')
                                ->whereNull('deleted_at')
                                ->select('hr_day', 'hr_start_time', 'hr_end_time')
                                ->get();*/
        $hours = $this->getStaffHours($this->id);

        if(count($hours))
            return json_encode($hours);
        return [];
    }

    /*public function user(){
        return $this->hasOne('App\Models\Access\User\User', 'account_id')->whereAccountType('Staff');
    }*/
    public function user(){
        return $this->hasOne('App\Models\Access\User\User', 'account_id')->where('account_type','!=','Client');
    }
	
	public function business(){
        return $this->belongsTo('App\Business');
    }

    /*public function futureBusyTimes(){
        $now = new Carbon();
        return $this->hasMany('App\StaffEventBusy', 'seb_staff_id')->where('seb_start_datetime', '>=', $now->toDateTimeString());
    }*/

    public function events(){
        //return $this->hasMany('App\StaffEvent', 'se_staff_id');
        return $this->hasMany('App\StaffEventSingleService', 'sess_staff_id');
    }

    public function attendences(){
        return $this->hasMany('App\StaffAttendence', 'sa_staff_id');
    }

    /*private function appointmentsList(){
        return $this->events()
                    ->with('servicesWithTrashed')
                    ->withTrashed()
                    ->where('se_deleted_in_chain', 0)
                    ->select('se_id', 'se_date', 'se_date as eventDate', 'se_is_repeating', 'deleted_at', 'se_booking_status', 'se_booking_status_confirm', 'se_start_time', 'se_start_time as eventTime');
    }

    public function pastAppointments(){   
        $now = new Carbon();              
        return $this->appointmentsList()
                    ->where(function($query) use ($now){
                            $query->where('se_date', '<', $now->toDateString())
                                  ->orWhere(function($query) use ($now){
                                        $query->where('se_date', '=', $now->toDateString())
                                              ->where('se_start_time', '<=', $now->toTimeString());
                                    });
                      })
                    ->orderBy('se_date','DESC')
                    ->orderBy('se_start_time','DESC');
    }

    public function futureAppointments(){        
        $now = new Carbon();              
        return $this->appointmentsList()
                    ->where(function($query) use ($now){
                            $query->where('se_date', '>', $now->toDateString())
                                  ->orWhere(function($query) use ($now){
                                        $query->where('se_date', '=', $now->toDateString())
                                              ->where('se_start_time', '>', $now->toTimeString());
                                    });
                      })
                    ->orderBy('se_date')
                    ->orderBy('se_start_time');
    }*/
    private function appointmentsList(){
        return $this->events()
                    ->with('serviceWithTrashed')
                    ->withTrashed()
                    /*->where('sess_deleted_in_chain', 0)*/
                    ->select('sess_id', 'sess_date', 'sess_date as eventDate', 'sess_epic_credit', 'sess_sessr_id', 'deleted_at', 'sess_booking_status', 'sess_service_id', 'sess_time', 'sess_time as eventTime', 'sess_client_attendance','sess_payment');
    }

    public function pastAppointments(){   
        $now = new Carbon();              
        return $this->appointmentsList()
                    ->where(function($query) use ($now){
                            $query->where('sess_date', '<', $now->toDateString())
                                  ->orWhere(function($query) use ($now){
                                        $query->where('sess_date', '=', $now->toDateString())
                                              ->where('sess_time', '<=', $now->toTimeString());
                                    });
                      })
                    ->orderBy('sess_date','DESC')
                    ->orderBy('sess_time','DESC');
    }

    public function futureAppointments(){        
        $now = new Carbon();              
        return $this->appointmentsList()
                    ->where(function($query) use ($now){
                            $query->where('sess_date', '>', $now->toDateString())
                                  ->orWhere(function($query) use ($now){
                                        $query->where('sess_date', '=', $now->toDateString())
                                              ->where('sess_time', '>', $now->toTimeString());
                                    });
                      })
                    ->orderBy('sess_date')
                    ->orderBy('sess_time');
    }

    public function eventClasses(){
        return $this->hasMany('App\StaffEventClass', 'sec_staff_id');
    }

    private function eventClassesList(){
        return $this->eventClasses()
                    ->with(array('clasWithTrashed' => function($query){ 
                            $query->select('cl_id', 'cl_name', 'cl_colour');
                      }/*, 'clientsWithTrashed'*/)) 
                    ->withTrashed()
                    /*->where('sec_deleted_in_chain', 0)*/
                    ->select('sec_id', 'sec_date', 'sec_date as eventDate', 'sec_secr_id', 'staff_event_classes.deleted_at'/*, 'sec_staff_id'*/, 'sec_class_id', 'sec_time', 'sec_time as eventTime','sec_payment');
    }

    public function pastClasses(){   
        $now = new Carbon();              
        return $this->eventClassesList()
                    ->where(function($query) use ($now){
                            $query->where('sec_date', '<', $now->toDateString())
                                  ->orWhere(function($query) use ($now){
                                        $query->where('sec_date', '=', $now->toDateString())
                                              ->where('sec_time', '<=', $now->toTimeString());
                                    });
                      })
                    ->orderBy('sec_date','DESC')
                    ->orderBy('sec_time','DESC');
    }

    public function futureClasses(){   
        $now = new Carbon();              
        return $this->eventClassesList()
                    ->where(function($query) use ($now){
                            $query->where('sec_date', '>', $now->toDateString())
                                  ->orWhere(function($query) use ($now){
                                        $query->where('sec_date', '=', $now->toDateString())
                                              ->where('sec_time', '>', $now->toTimeString());
                                    });
                      })
                    ->orderBy('sec_date')
                    ->orderBy('sec_time');
    }

    public function staffEventPaymet(){
        $total = 0;
        $staffService = $this->pastAppointments()->get();
        
        if(count($staffService)){
            foreach ($staffService as  $service) {
                 $total += $service->sess_payment;
            }
        }
        $staffClass = $this->pastClasses()->get();
        if(count($staffClass)){
            foreach ($staffClass as  $cls) {
                 $total += $cls->sec_payment;
            }
        }
        return $total;
    }

    public function classes(){
        return $this->belongsToMany('App\Clas', 'class_staffs', 'cst_staff_id', 'cst_cl_id')->withPivot('cst_per_session_enable');
    }

    static function pivotClassesTrashedOnly($staffId){
        return DB::table('class_staffs')->where('cst_staff_id', $staffId)->whereNotNull('deleted_at')->select('cst_cl_id')->get();
    }

    static function pivotClassesTrashedSessionPriceOnly($staffId , $classId){
        return DB::table('class_staffs')->where('cst_staff_id', $staffId)->whereIn('cst_cl_id', $classId)->select('cst_id')->get();
    }
    public function sessionrolestaff(){
        return $this->belongsToMany('App\SessionRole', 'sessionrole_staffs', 'srs_staff_id','srs_role_id');
    }
    static function pivotSessionRoleTrashedOnly($staffId){
        return DB::table('sessionrole_staffs')->where('srs_staff_id', $staffId)->select('srs_role_id')->get();
    }
///session service
    public function sessionservicestaff(){
        return $this->belongsToMany('App\Service', 'service_staff', 'sst_staff_id','sst_service_id');
    }
    static function pivotSessionServiceTrashedOnly($staffId){
        return DB::table('service_staff')->where('sst_staff_id', $staffId)->select('sst_service_id')->get();
    }

 ///commission role
    public function commissionRoleStaff(){
        return $this->belongsToMany('App\CommissionRole', 'commissionrole_staff', 'crs_staff_id','crs_role_id');
    }
    static function pivotCommissionRoleTrashedOnly($staffId){
        return DB::table('commissionrole_staff')->where('crs_staff_id', $staffId)->select('crs_role_id')->get();
    }   

 ///commission category
    public function commissionCategorystaff(){
        return $this->belongsToMany('App\CommissionCategory', 'comm_category_staff', 'ccs_staff_id','ccs_category_id');
    }
    static function pivotCommissionCategoryTrashedOnly($staffId){
        return DB::table('comm_category_staff')->where('ccs_staff_id', $staffId)->select('ccs_category_id')->get();
    }  

 ///commission source
    public function commissionSourcestaff(){
        return $this->belongsToMany('App\CommissionSource', 'comm_source_staff', 'css_staff_id','css_source_id');
    }
    static function pivotCommissionSourceTrashedOnly($staffId){
        return DB::table('comm_source_staff')->where('css_staff_id', $staffId)->select('css_source_id')->get();
    }
 ///attendees
    public function attendees(){
        return $this->hasMany('App\StaffAttendee', 'sa_staff_id');
    }

    public function areas(){
        return $this->belongsToMany('App\LocationArea', 'area_staffs', 'as_staff_id', 'as_la_id');
    }

    public function favAreas(){
        $now = new Carbon();
        $todayDate = $now->toDateString();
        return $this->areas()->where('as_fav_expiry', '>=', $todayDate);
    }
	
	public function invoiceItems(){
        return $this->hasMany('App\InvoiceItems','inp_staff_id');
    }

    /*static function getHours($staffId){
        return DB::table('hours')->where('hr_entity_id', $staffId)->where('hr_entity_type', 'staff')->select('hr_day', 'hr_start_time', 'hr_end_time')->get();
    }*/

    static function staffHasArea($staffId){
        return DB::table('area_staffs')->where('as_staff_id', $staffId)->whereNull('deleted_at')->count();
    }

    /*static function getLocsAreas($staffId){
        $locsAreas = array();
        $staff = Staff::find($staffId);
        
        if($staff && Session::has('businessId')){
            $bussLocs = $staff->business->locations;
            if($bussLocs){
                $i = 0;
                foreach($bussLocs as $bussLoc){
                    $areas = Location::find($bussLoc->id)->areas;
                    if($areas){
                        foreach($areas as $area){
                            $areaStaffId = explode(',', $area->la_staff);
                            if(in_array($staffId, $areaStaffId)){
                                $locsAreas[$i]['locName'] = $bussLoc->location_training_area;
                                $locsAreas[$i]['areaId'] = $area->la_id;
                                $locsAreas[$i]['areaName'] = $area->la_name;
                                $i++;
                            }
                        }
                    }
                }
            }
        }
        return $locsAreas;    
    }*/

    static function getServices($data = array()){
        $serv = array();

        $staff = '';
        if(!array_key_exists('staff', $data) && $data['staffId'])
            $staff = Staff::findStaff($data['staffId']);
        else if(array_key_exists('staff', $data))
            $staff = $data['staff'];

        if($staff){
            $business = '';
            if(!array_key_exists('business', $data) && Session::has('businessId'))
                $business = Business::find(Session::get('businessId'));
            else if(array_key_exists('business', $data))
                $business = $data['business'];

            if($business){
                if(array_key_exists('complOnly', $data) && $data['complOnly'])
                    $services = $business->completedServices;
                else
                    $services = $business->services;
                if($services->count()){
                    foreach($services as $service){
                        if($service->one_on_one_staffs != ''){
                            $serviceStaffId = explode(',', $service->one_on_one_staffs);
                            if(in_array($staff->id, $serviceStaffId))
                                $serv[] = $service->id;
                        }
                        else if($service->team_staffs != ''){
                            $serviceStaffId = explode(',', $service->team_staffs);
                            if(in_array($staff->id, $serviceStaffId))
                                $serv[] = $service->id;
                        }
                    }
                }
            }
        }
        return $serv;
    }

    static function getServicesByArea($request){
        $serv = array();
        if(Session::has('businessId')){
            $staff = Staff::findStaff($request->staffId);
            //dd($staff);
            //$staff = Staff::find($request->staffId);
            if($staff){
                $area = LocationArea::ifAreaExist($request->areaId);
                if($area){
                    /*$services = $staff->business->services;
                    if($services){
                        $i = 0;
                        foreach($services as $service){
                            if($service->one_on_one_staffs != ''){
                                $serviceStaffId = explode(',', $service->one_on_one_staffs);
                                if(in_array($staffId, $serviceStaffId)){
                                    $serv[$i]['id'] = $service->id;
                                    $serv[$i]['name'] = $service->one_on_one_name;
                                    $serv[$i]['duration'] = $service->one_on_one_duration;
                                    $serv[$i]['price'] = $service->one_on_one_price;
                                    $i++;
                                }
                            }
                            else if($service->team_staffs != ''){
                                $serviceStaffId = explode(',', $service->team_staffs);
                                if(in_array($staffId, $serviceStaffId)){
                                    $serv[$i]['id'] = $service->id;
                                    $serv[$i]['name'] = $service->team_name;
                                    $serv[$i]['duration'] = $service->team_duration;
                                    $serv[$i]['price'] = $service->team_price;
                                    $i++;
                                }
                            }
                            
                        }
                    }*/
                    //DB::enableQueryLog();
                    $services = //DB::table('services')
                                Service::OfBusiness()//where('business_id', Session::get('businessId'))
                                //->where('is_completed', 1)
                                ->complOnly()
                                    ->where(function($query){
                                        $query->where('one_on_one_staffs', '<>', '')
                                              ->orWhere('team_staffs', '<>', '');
                                    })
                                    ->get(array('id', 'srvc_la_id', 'one_on_one_name', 'one_on_one_staffs', 'one_on_one_duration', 'one_on_one_price', 'team_name', 'team_staffs', 'team_duration', 'team_price'));

                                    //DB::enableQueryLog();
                                    //dd(DB::getQueryLog());

                    $i = 0;
                    foreach($services as $service){
                        $serviceArea = explode(',', $service->srvc_la_id);
                        if($service->one_on_one_staffs != ''){
                            $serviceStaffId = explode(',', $service->one_on_one_staffs);
                            if(in_array($request->staffId, $serviceStaffId) && in_array($request->areaId, $serviceArea)){
                                $serv[$i]['id'] = $service->id;
                                $serv[$i]['name'] = $service->one_on_one_name;
                                $serv[$i]['duration'] = $service->one_on_one_duration;
                                $serv[$i]['price'] = $service->one_on_one_price;
                                $i++;
                            }
                            
                        }
                        else if($service->team_staffs != ''){
                            $serviceStaffId = explode(',', $service->team_staffs);
                            if(in_array($request->staffId, $serviceStaffId) && in_array($request->areaId, $serviceArea)){
                                $serv[$i]['id'] = $service->id;
                                $serv[$i]['name'] = $service->team_name;
                                $serv[$i]['duration'] = $service->team_duration;
                                $serv[$i]['price'] = $service->team_price;
                                $i++;
                            }
                        }
                    }
                }
            }
        }
        return $serv;
    }

    static function getStaffHours($staffId, $day = '', $editedHours=[]){   
        $query = DB::table('hours')->where('hr_entity_id', $staffId)
                ->where('hr_entity_type', 'staff')
                ->whereNull('deleted_at')
                ->select('hr_day','hr_start_time','hr_end_time','hr_edit_date','hr_entity_id');
            if($day)
                $query->where('hr_day', $day);
            if(count($editedHours)){
                $startDate=Carbon::createFromFormat('Y-m-d',$editedHours['startDate'])->toDateString();
                $endDate=Carbon::createFromFormat('Y-m-d', $editedHours['endDate'])->toDateString();

                $query->where(function($q) use ($startDate,$endDate){
                            $q->where(function($query) use ($startDate,$endDate){
                                $query->where('hr_edit_date','>=',$startDate)
                                      ->where('hr_edit_date','<=',$endDate);
                                })->orWhereNull('hr_edit_date');
                            });
            }
            else{
                $query->whereNull('hr_edit_date');
            }
            $getQuery=$query->orderBy('hr_edit_date','desc')->get();

            if(count($editedHours)){
                $staffHours=[];
                $flag=false;
                if(count($getQuery)){
                    foreach ($getQuery as $value) {
                        if($value->hr_start_time != $value->hr_end_time){
                            if($value->hr_edit_date != null && $value->hr_start_time != null){
                                $staffHours[]=$value;
                                $flag=true;
                            }
                            else if($value->hr_edit_date != null && $value->hr_start_time == null){
                                $flag=true;
                                break;
                            }
                            else if(!$flag){
                                $staffHours[]=$value; 
                            } 
                            else
                                break; 
                        }
                    }
                    
                    return $staffHours; 
                }
            }
            return $getQuery;
    }

	static function getStaff($staffId){
	   //return DB::table('staff')->whereIn('id', explode(',', $staffId))->get(array('first_name','last_name'));
        return Staff::findStaff(explode(',', $staffId));
	}

    public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->where('business_id', $bussId);
    }

    static function findStaff($staffId, $bussId = 0){
        return Staff::OfBusiness($bussId)->find($staffId);
    }

    static function findOrFailStaff($staffId, $bussId = 0){
        return Staff::OfBusiness($bussId)->findOrFail($staffId);
    }

    static function ifstaffExist($staffId, $bussId = 0){
        $ifstaffExist = Staff::OfBusiness($bussId)->where('id', $staffId)->count();
        if($ifstaffExist)
            return true;
        return false;
    }

    public function type(){
        return $this->belongsTo('App\UserType', 'ut_id');
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($staff){
            /* Deleting future busy time */
            $now = new Carbon();
            StaffEventBusy::where('seb_start_datetime', '>=', $now->toDateTimeString())->where('seb_staff_id', $staff->id)->delete();

            /* Deleting working hours */
            DB::table('hours')->where('hr_entity_id', $staff->id)
                            ->where('hr_entity_type', 'staff')
                            ->update(array('deleted_at' => createTimestamp()));

            /* Deleting linked areas */                        
            DB::table('area_staffs')->where('as_staff_id', $staff->id)
                                    ->update(array('deleted_at' => createTimestamp()));

            /* Deleting linked classes */                        
            DB::table('class_staffs')->where('cst_staff_id', $staff->id)
                                    ->update(array('deleted_at' => createTimestamp()));
            
            /* Deleting login credentials */
            if($staff->login_with_email)
                $staff->user()->delete();
        });
        static::deleted(function(){
            if(!Staff::OfBusiness()->exists())
                Session::forget('ifBussHasStaffs');
        });
    }
}
