<?php
namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Session;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\LocationArea;

class Location extends Model{
    use SoftDeletes;

    protected $table = 'locations';
    protected $fillable = ['business_id', 'location_training_area', 'logo', 'website', 'facebook', 'email', 'phone', 'fixed_location', 'address_line_one', 'address_line_two', 'city', 'country', 'state', 'postal_code', 'time_zone', 'disp_location_web', 'disp_location_online'];

	
	public function business(){
        return $this->belongsTo('App\Business');
    }

    public function areas(){
        return $this->hasMany('App\LocationArea', 'la_location_id');
    }

    public function areasWithTrashed(){
        return $this->areas()->withTrashed();
    }

    public function products(){
        return $this->hasMany('App\Product', 'stock_location');
    }

    public function productsWithTrashed(){
        return $this->products()->withTrashed();
    }
	
	static function getLocHours($locId){
		return DB::table('hours')->where('hr_entity_id', $locId)
                                ->where('hr_entity_type', 'location')
                                ->whereNull('deleted_at')
                                ->select('hr_day', 'hr_start_time', 'hr_end_time')
                                ->get();
	}

    static function getLocAreas($locId){
        //return DB::table('location_areas')->where('la_location_id', $locId)->lists('la_name', 'la_id');
        return LocationArea::where('la_location_id', $locId)->pluck('la_name', 'la_id');
    }
	
    static function getLocation($locId){
		//return DB::table('locations')->where('id', $locId)->value('location_training_area');
        return Location::where('id', $locId)->value('location_training_area');
	}

    public function scopeHasName($query, $locName, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');

        return $query->where('business_id', $bussId)->where('location_training_area', $locName);
    }

    public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');
        return $query->where('business_id', $bussId);
    }

    static function findLoc($locId, $bussId = 0){
        return Location::OfBusiness($bussId)->find($locId);
    }

    static function findOrFailLoc($locId, $bussId = 0){
        return Location::OfBusiness($bussId)->findOrFail($locId);
    }

    static function ifLocExist($locId, $bussId = 0){
        $ifLocExist = Location::OfBusiness($bussId)->where('id', $locId)->count();
        if($ifLocExist)
            return true;
        return false;
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($location){
            /* Deleting working hours */
            DB::table('hours')->where('hr_entity_id', $location->id)
                            ->where('hr_entity_type', 'location')
                            ->update(array('deleted_at' => createTimestamp()));

            /* Deleting areas */
            if($location->areas->count())
                foreach($location->areas as $area)
                    $area->delete(); 
        });
        static::deleted(function(){
            if(!Location::OfBusiness()->exists())
                Session::forget('ifBussHasLocations');
        });
    }
	/*
	public function user()
    {
        return $this->belongsTo('App\Models\Access\User');
    }
	
	static function getLocations($userId){
		return DB::table('locations')->where('user_id', $userId)->lists('location_training_area', 'id');
	}
	
	static function getStaffs($userId){
		return DB::table('staff')->select(DB::raw('CONCAT(first_name, " ", last_name) AS staffName'), 'id')->where('user_id', $userId)->lists('staffName','id');
	}*/
}
