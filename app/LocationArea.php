<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class LocationArea extends Model{
    use SoftDeletes;
	protected $table = 'location_areas';
	protected $primaryKey = 'la_id';
	protected $fillable = ['la_logo'];
	
    public function location(){
        return $this->belongsTo('App\Location', 'la_location_id');
    }

    public function locationWithTrashed(){
        return $this->location()->withTrashed();
    }

    public function events(){
        //return $this->hasMany('App\StaffEvent', 'se_area_id');
        return $this->belongsToMany('App\StaffEventSingleService', 'staff_event_single_service_areas', 'sessa_la_id', 'sessa_sess_id')
                    ->whereNull('staff_event_single_service_areas.deleted_at');
    }

    public function busyTime(){
        return $this->hasMany('App\StaffEventBusy', 'seb_area_id');
    }

    /*public function eventClasses(){
        return $this->hasMany('App\StaffEventClass', 'sec_area_id');
    }*/

    public function eventClassess(){
        return $this->belongsToMany('App\StaffEventClass', 'staff_event_class_areas', 'seca_la_id', 'seca_sec_id')
                    ->whereNull('staff_event_class_areas.deleted_at');;
    }

    /*public function eventSingleServices(){
        return $this->belongsToMany('App\StaffEventSingleService', 'staff_event_single_service_areas', 'sessa_la_id', 'sessa_sess_id')
                    ->whereNull('staff_event_single_service_areas.deleted_at');
    }*/

    public function staffs(){
        return $this->belongsToMany('App\Staff', 'area_staffs', 'as_la_id', 'as_staff_id')->withPivot('as_business_id');
    }

    public function staffsWithTrashed(){
        return $this->staffs()->withTrashed();
        //return $this->staffs()->get();
    }

    static function pivotStaffsTrashedOnly($areaId){
        return DB::table('area_staffs')->where('as_business_id', Session::get('businessId'))->where('as_la_id', $areaId)->whereNotNull('deleted_at')->select('as_staff_id')->get();
    }

    public function classes(){
        return $this->belongsToMany('App\Clas', 'area_classes', 'ac_la_id', 'ac_cl_id');
    }

    public function classesWithTrashed(){
        return $this->classes()->withTrashed();
    }

    static function getHours($areaId){
        return DB::table('hours')->where('hr_entity_id', $areaId)
                                ->where('hr_entity_type', 'area')
                                ->whereNull('deleted_at')
                                ->select('hr_day', 'hr_start_time', 'hr_end_time')
                                ->get();
    }

    /*public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->where('business_id', $bussId);
    }*/

    /*static function findLoc($locId, $bussId = 0){
        return Location::OfBusiness($bussId)->find($locId);
    }*/

    public function scopeOfBusiness($query, $bussId = 0, $trashed = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        if($trashed){
            $query->withTrashed()->join('locations', 'la_location_id', '=', 'id')->where('business_id', $bussId);
        }
        else
            $query->join('locations', 'la_location_id', '=', 'id')->where('business_id', $bussId)->whereNull('locations.deleted_at');
    }

    static function findArea($areaId, $bussId = 0){
        return LocationArea::OfBusiness($bussId)->find($areaId);
    }

    static function findAreaWithTrashed($areaId, $bussId = 0){
         return LocationArea::OfBusiness($bussId, 1)->find($areaId);
    }

    static function findOrFailArea($areaId, $bussId = 0){
        return LocationArea::OfBusiness($bussId)->findOrFail($areaId);
    }

    static function ifAreaExist($areaId, $bussId = 0){
        $ifLocExist = LocationArea::OfBusiness($bussId)->where('la_id', $areaId)->count();
        if($ifLocExist)
            return true;
        return false;
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($area){
            /* Deleting working hours */
            DB::table('hours')->where('hr_entity_id', $area->la_id)
                            ->where('hr_entity_type', 'area')
                            ->update(array('deleted_at' => createTimestamp()));

            /* Deleting linked areas */                        
            DB::table('area_staffs')->where('as_la_id', $area->la_id)
                                    ->update(array('deleted_at' => createTimestamp()));

            /* Deleting linked classes */                        
            DB::table('area_classes')->where('ac_la_id', $area->la_id)
                                    ->update(array('deleted_at' => createTimestamp()));

            /* Deleting linkage with event-class */                        
            DB::table('staff_event_class_areas')->where('seca_la_id', $area->la_id)
                                                ->update(array('deleted_at' => createTimestamp()));

            /* Deleting linkage with event-single-service */                        
            DB::table('staff_event_single_service_areas')->where('sessa_la_id', $area->la_id)
                                                        ->update(array('deleted_at' => createTimestamp()));                                    
        });
        static::deleted(function(){
            if(!LocationArea::OfBusiness()->exists())
                Session::forget('ifBussHasAreas');
        });
    }
}
