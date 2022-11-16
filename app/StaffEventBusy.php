<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class StaffEventBusy extends Model{
    use SoftDeletes;
    protected $table = 'staff_event_busy';
    protected $primaryKey = 'seb_id';
    protected $guarded = [];

    /*
    **start: RELATIONS
    */
      public function areaWithTrashed(){
          return $this->belongsTo('App\LocationArea', 'seb_area_id')->withTrashed();
      }

      public function staffWithTrashed(){
        return $this->belongsToMany('App\Staff', 'staff_event_busy_staff', 'sebs_seb_id', 'sebs_staff_id')
                    ->withPivot('sebs_business_id');
    }

    public function staff(){
      return $this->staffWithTrashed()->whereNull('staff_event_busy_staff.deleted_at');
  }

      // public function staff(){
      //     return $this->belongsTo('App\Staff', 'seb_staff_id');
      // }

      // public function staffWithTrashed(){
      //     return $this->staff()->withTrashed();
      // }

      public function repeat(){
          return $this->belongsTo('App\StaffEventBusyRepeat', 'seb_sebr_id');
      }
    /*
    **end: RELATIONS
    */


    /*
    **start: SCOPES
    */
      public function scopeClashingEvents($query, $data){
          if(array_key_exists('eventId', $data))
              return $query->where('seb_id', '<>', $data['eventId'])
                           //->where('seb_date', $data['date'])
                           ->where('seb_deny_booking', 1)
                           ->where(function($query) use ($data){
                              $query->where(function($q) use ($data){
                                          $q->where('seb_start_datetime', '>=', $data['startDatetime'])
                                            ->where('seb_start_datetime', '<', $data['endDatetime']);
                                      })
                                      ->orWhere(function($q) use ($data){
                                          $q->where('seb_start_datetime', '<=', $data['startDatetime'])
                                            ->where('seb_end_datetime', '>', $data['startDatetime']);
                                      });
                           });
                           /*->where(function($query) use ($data){
                              $query->where(function($q) use ($data){
                                          $q->where('seb_time', '>=', $data['startTime'])
                                            ->where('seb_time', '<', $data['endTime']);
                                      })
                                      ->orWhere(function($q) use ($data){
                                          $q->where('seb_time', '<=', $data['startTime'])
                                            ->where('seb_end_time', '>', $data['startTime']);
                                      });
                           });*/
          else                         
              return $query//->where('seb_date', $data['date'])
                           ->where('seb_deny_booking', 1)
                           ->where(function($query) use ($data){
                              $query->where(function($q) use ($data){
                                          $q->where('seb_start_datetime', '>=', $data['startDatetime'])
                                            ->where('seb_start_datetime', '<', $data['endDatetime']);
                                      })
                                      ->orWhere(function($q) use ($data){
                                          $q->where('seb_start_datetime', '<=', $data['startDatetime'])
                                            ->where('seb_end_datetime', '>', $data['startDatetime']);
                                      });
                           });
                           /*->where(function($query) use ($data){
                              $query->where(function($q) use ($data){
                                          $q->where('seb_time', '>=', $data['startTime'])
                                            ->where('seb_time', '<', $data['endTime']);
                                      })
                                      ->orWhere(function($q) use ($data){
                                          $q->where('seb_time', '<=', $data['startTime'])
                                            ->where('seb_end_time', '>', $data['startTime']);
                                      });
                           });*/
      }

      public function scopeOfBusiness($query){
          return $query->where('seb_business_id', Session::get('businessId'));
      }

      public function scopeOfAreaAndStaffAndDatedBetween($query, $request){
          //return $query->OfAreaAndStaff($request)->whereBetween('seb_date', array($request->startDate, $request->endDate));
        return $query->OfAreaAndStaff($request)->DatedBetween($request->startDate, $request->endDate);
      }

      public function scopeOfAreaAndStaffAndDated($query, $request){
          return $query->OfAreaAndStaff($request)->where('seb_date', $request->startDate);
      }

      public function scopeOfAreaAndStaff($query, $request){
          return $query->OfStaff($request->staffId)->where('seb_area_id', $request->areaId);
      }

      public function scopeOfStaff($query, $staffId){
        return $query->whereHas('staff',function($q) use($staffId){
          $q->where('staff.id',$staffId);
      });
      }

      public function scopeDatedBetween($query, $startDate, $endDate){
        return $query->whereBetween('seb_date', array($startDate, $endDate));
      }
    /*
    **end: SCOPES
    */
}
