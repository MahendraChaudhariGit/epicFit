<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use Carbon\Carbon;

class StaffEvent extends Model{
    use SoftDeletes;
	protected $table = 'staff_events';
	protected $primaryKey = 'se_id';

    public function getEventDateCarbonAttribute(){
        return setLocalToBusinessTimeZone(new Carbon($this->se_date));
    }

    public function getEventStartTimeCarbonAttribute(){
        return setLocalToBusinessTimeZone(new Carbon($this->se_start_time));
    }

    public function user(){
        return $this->belongsTo('App\Models\Access\User\User', 'se_user_id');
    }

    public function userWithTrashed(){
        return $this->user()->withTrashed();
    }

    public function staff(){
        return $this->belongsTo('App\Staff', 'se_staff_id');
    }

    public function staffWithTrashed(){
        return $this->staff()->withTrashed();
    }

    public function client(){
        return $this->belongsTo('App\Clients', 'se_client_id');
    }

    public function clientWithTrashed(){
        return $this->client()->withTrashed();
    }

    public function area(){
        return $this->belongsTo('App\LocationArea', 'se_area_id');
    }

    public function locationAndAreaWithTrashed(){
        return $this->area()->with('locationWithTrashed')->withTrashed();
    }

    public function eventServices(){
        return $this->hasMany('App\StaffEventService', 'ses_staff_event_id');
    }

    public function eventServicesWithTrashed(){
        return $this->eventServices()->withTrashed()->orderBy('ses_staff_event_id')->orderBy('deleted_at')->orderBy('created_at');
    }

    /*public function servicesWithTrashed(){
        return $this->eventServices()->with('serviceWithTrashed')->withTrashed();
    }*/
    public function servicesWithTrashed(){
        return $this->eventServicesWithTrashed()->with('serviceWithTrashed');
    } 

    public function scopeClashingEvents($query, $data){
        if(array_key_exists('eventId', $data))
            return $query->where('se_id', '<>', $data['eventId'])
                         ->where('se_booking_status', 'Confirmed')
                         ->where(function($query) use ($data){
                            $query->where(function($q) use ($data){
                                        $q->where('se_start_datetime', '>=', $data['startDatetime'])
                                          ->where('se_start_datetime', '<', $data['endDatetime']);
                                    })
                                    ->orWhere(function($q) use ($data){
                                        $q->where('se_start_datetime', '<=', $data['startDatetime'])
                                          ->where('se_end_datetime', '>', $data['startDatetime']);
                                    });
                         });
                         /*->where('se_date', $data['date'])
                         ->where(function($query) use ($data){
                            $query->where(function($q) use ($data){
                                        $q->where('se_start_time', '>=', $data['startTime'])
                                          ->where('se_start_time', '<', $data['endTime']);
                                    })
                                    ->orWhere(function($q) use ($data){
                                        $q->where('se_start_time', '<=', $data['startTime'])
                                          ->where('se_end_time', '>', $data['startTime']);
                                    });
                         });*/
        else                         
            return $query->where('se_booking_status', 'Confirmed')
                         ->where(function($query) use ($data){
                            $query->where(function($q) use ($data){
                                        $q->where('se_start_datetime', '>=', $data['startDatetime'])
                                          ->where('se_start_datetime', '<', $data['endDatetime']);
                                    })
                                    ->orWhere(function($q) use ($data){
                                        $q->where('se_start_datetime', '<=', $data['startDatetime'])
                                          ->where('se_end_datetime', '>', $data['startDatetime']);
                                    });
                         });
                         /*->where('se_date', $data['date'])
                         ->where(function($query) use ($data){
                            $query->where(function($q) use ($data){
                                        $q->where('se_start_time', '>=', $data['startTime'])
                                          ->where('se_start_time', '<', $data['endTime']);
                                    })
                                    ->orWhere(function($q) use ($data){
                                        $q->where('se_start_time', '<=', $data['startTime'])
                                          ->where('se_end_time', '>', $data['startTime']);
                                    });
                         });*/
    }

    public function scopeOfBusiness($query){
        return $query->where('se_business_id', Session::get('businessId'));
    }

    public function scopeOfAreaAndStaffAndDatedBetween($query, $request){
        return $query->OfAreaAndStaff($request)->whereBetween('se_date', array($request->startDate, $request->endDate));
    }

    public function scopeOfAreaAndStaffAndDated($query, $request){
        return $query->OfAreaAndStaff($request)->where('se_date', $request->startDate);
    }
    
    public function scopeOfAreaAndStaff($query, $request){
        return $query->where('se_staff_id', $request->staffId)->where('se_area_id', $request->areaId);
    }

    public function scopeChildEvents($query, $eventId){
        return $query->where('se_parent_id', $eventId);
    }

    public function scopeSiblingEvents($query, $data){
        return $query->ChildEvents($data['parentEventId'])->where('se_date', '>', $data['eventDate'])->where('se_id', '!=', $data['eventId']);
    }

    public function histories(){
        return $this->morphMany('App\StaffEventHistory', 'historyable', 'seh_event_type', 'seh_event_id');
    }

    public function historiesWithTrashed(){
        return $this->histories()->withTrashed();
    }

    public function repeat(){
        return $this->morphMany('App\StaffEventRepeat', 'repeatable', 'ser_event_type', 'ser_event_id');
    }

    public function repeatWithTrashed(){
        return $this->repeat()->withTrashed();
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($event){
            if($event->forceDeleting){
                $event->eventServices()->forceDelete();
                $event->histories()->forceDelete();
                $event->repeat()->forceDelete();
            } 
            else{
                $event->eventServices()->delete();
                $event->histories()->delete();
                $event->repeat()->delete();
            }
        });
    }
}
