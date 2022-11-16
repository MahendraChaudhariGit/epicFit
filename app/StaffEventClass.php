<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Session;
use Carbon\Carbon;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\SalesProcessTrait;
use App\Http\Traits\SalesProcessProgressTrait;

class StaffEventClass extends Model{
    use SoftDeletes, ClientTrait, SalesProcessTrait, SalesProcessProgressTrait;
    
    protected $table = 'staff_event_classes';
    protected $primaryKey = 'sec_id';
    protected $fillable = ['sec_class_id', 'sec_secr_id', 'sec_duration', 'sec_capacity', 'sec_price', 'sec_staff_id', 'sec_start_datetime', 'sec_end_datetime','sec_payment','sec_time','sec_notes','sec_date','sec_time'];

    public $DELETE_LINKED_CLIENTS = true;

    /*
    **start: ACCESSOR
    */
    public function getEventDateCarbonAttribute(){
        return setLocalToBusinessTimeZone(new Carbon($this->sec_date));
    }

    public function getEventStartTimeCarbonAttribute(){
        return new Carbon($this->sec_time);
    }
    /*
    **end: ACCESSOR
    */


    public function clientsOldestFirst(){
        return $this->clients()->orderBy('staff_event_class_clients.created_at');
    }

    public function clientsRaw(){
        return $this->belongsToMany('App\Clients', 'staff_event_class_clients', 'secc_sec_id', 'secc_client_id')
        ->withPivot('secc_notes', 'secc_epic_credit', 'secc_reduce_rate_session', 'secc_reduce_rate', 'secc_if_recur', 'secc_client_attendance', 'secc_client_status', 'secc_if_make_up_created', 'secc_id', 'secc_cmid', 'secc_invoice_status', 'secc_with_invoice', 'deleted_at', 'secc_event_log', 'secc_action_performed_by','is_ldc', 'secc_class_extra','sales_step_number')
        ->withTimestamps();
    }
    public function clientsWithTrashed(){
        return $this->clientsRaw()
        ->where(function($query){
            $query->whereNull('staff_event_class_clients.deleted_at')
            ->orWhere('secc_client_deleted', 1);
        })
        ->withTrashed();
    }
    public function clientsWithTrashedWithPivotTrashed(){
        return $this->clientsRaw()
        ->where('secc_epic_credit', 0)
        ->withTrashed();
    }

    public function clients(){
        return $this->clientsRaw()->whereNull('staff_event_class_clients.deleted_at');
        // return $this->clientsRaw();
    }



    public function teamSalesProcessClients(){
        return $this->clients()->where('secc_if_recur', 0)->where('secc_client_attendance', '!=', 'Did not show')->whereNotNull('consultation_date');
    }

    public function attendedClient(){
        return $this->clients()->where('secc_client_attendance', 'Attended');
    }

    public function clas(){
        return $this->belongsTo('App\Clas', 'sec_class_id');
    }

    public function clasWithTrashed(){
        return $this->clas()->withTrashed();
    }

    public function isClientExistInEvent($clientId){
        return $this->clients()->where('id', $clientId)->pluck('id')->toArray();  
    }
    
    public function scopeClashingEvents($query, $data){

        if(array_key_exists('eventId', $data) && array_key_exists('classId', $data))
            return $query->where('sec_id', '!=', $data['eventId'])
            ->where('sec_class_id', $data['classId'])
            ->where(function($query) use ($data){
                $query->where(function($q) use ($data){
                    $q->where('sec_start_datetime', '>=', $data['startDatetime'])
                    ->where('sec_start_datetime', '<', $data['endDatetime']);
                })
                ->orWhere(function($q) use ($data){
                    $q->where('sec_start_datetime', '<=', $data['startDatetime'])
                    ->where('sec_end_datetime', '>', $data['startDatetime']);
                });
            });
        if(array_key_exists('eventId', $data))
            return $query->where('sec_id', '!=', $data['eventId'])
            ->where(function($query) use ($data){
                $query->where(function($q) use ($data){
                    $q->where('sec_start_datetime', '>=', $data['startDatetime'])
                    ->where('sec_start_datetime', '<', $data['endDatetime']);
                })
                ->orWhere(function($q) use ($data){
                    $q->where('sec_start_datetime', '<=', $data['startDatetime'])
                    ->where('sec_end_datetime', '>', $data['startDatetime']);
                });
            });
        else if(array_key_exists('classId', $data))
            return $query->where('sec_class_id', $data['classId'])
            ->where(function($query) use ($data){
                $query->where(function($q) use ($data){
                    $q->where('sec_start_datetime', '>=', $data['startDatetime'])
                    ->where('sec_start_datetime', '<', $data['endDatetime']);
                })
                ->orWhere(function($q) use ($data){
                    $q->where('sec_start_datetime', '<=', $data['startDatetime'])
                    ->where('sec_end_datetime', '>', $data['startDatetime']);
                });
            });
        else
            return $query->where(function($q) use ($data){
                $q->where('sec_start_datetime', '>=', $data['startDatetime'])
                ->where('sec_start_datetime', '<', $data['endDatetime']);
            })
            ->orWhere(function($q) use ($data){
                $q->where('sec_start_datetime', '<=', $data['startDatetime'])
                ->where('sec_end_datetime', '>', $data['startDatetime']);
            });
    }

    public function scopeActive($query){
        $now = new Carbon();
        return $query->where('sec_end_datetime', '>=', $now->toDateTimeString());
    }

    public function scopeOfBusiness($query){
        return $query->where('sec_business_id', Session::get('businessId'));
    }

    public function scopeDatedBetween($query, $startDate, $endDate){
        return $query->whereBetween('sec_date', array($startDate, $endDate));
    }


    public function scopeOfAreaAndStaffAndDatedBetween($query, $request){
        return $query->OfAreaAndStaff($request)->DatedBetween($request->startDate, $request->endDate);
    }

    public function scopeOfAreaAndStaffAndDated($query, $request){
        return $query->OfAreaAndStaff($request)->where('sec_date', $request->startDate);
    }

    public function scopeOfAreaAndStaff($query, $request){
        return $query->OfStaff($request->staffId)->OfArea($request->areaId);
    }

    public function scopeOfStaff($query, $staffId){
        return $query->where('sec_staff_id', $staffId);
    }

    public function scopeOfStaffs($query, $staffsId){
        return $query->whereIn('sec_staff_id', $staffsId);
    }

    public function scopeOfArea($query, $areaId){
        return $query->whereHas('areas', function($query) use($areaId){
            $query->whereIn('seca_la_id', [$areaId]);
        });
    }

    public function scopeSiblingEvents($query, $data){
        return $query->where('sec_secr_id', '=', $data['repeatId'])->where('sec_start_datetime', '>', $data['eventDate']."23:59:59");
    }

    public function scopeTeamBooking($query, $clientId, $consultationDate, $consultationExpDate = ''){
        $query = $query->where('secc_if_recur', 0)
        ->where('secc_client_id', $clientId)
        ->where('sec_date', '>=', $consultationDate)
        // ->whereRaw("DATEDIFF(sec_date, '$consultationDate') < 15")
        ->whereNull('staff_event_class_clients.deleted_at');

        if($consultationExpDate) 
           $query->where('sec_date', '<', $consultationExpDate);
        else
           $query->whereRaw("DATEDIFF(sec_date, '$consultationDate') < 15") ;

        return $query;
    }

   public function histories(){
    return $this->morphMany('App\StaffEventHistory', 'historyable', 'seh_event_type', 'seh_event_id');
}

public function historiesWithTrashed(){
    return $this->histories()->withTrashed();
}

public function resources(){
    return $this->morphMany('App\StaffEventResource', 'resourceble', 'serc_event_type', 'serc_event_id');
}

public function resourcesWithTrashed(){
    return $this->resources()->withTrashed();
}

public function existResourceRecurData(){
    $resources = $this->resources()->get();
    $resourcesData = array();
    if(count($resources)){
        foreach ($resources as $resource) {
            $resourcesData[$resource->serc_res_id] = $resource->serc_item_quantity;
        }
    }
    if(count($resourcesData))
        return json_encode($resourcesData);
    else
        return '';
}

public function existClientRecurData(){
    $clients = $this->clients;
    $clientsData = array();
    if(count($clients)){
        foreach ($clients as $client) {
            $clientsData[$client->id] = array('with_invoice'=>$client->pivot->secc_with_invoice, 'with_credit'=>$client->pivot->secc_epic_credit);
        }
    }
    if(count($clientsData))
        return json_encode($clientsData);
    else
        return '';
}

public function staff(){
    return $this->belongsTo('App\Staff', 'sec_staff_id');
}

public function staffWithTrashed(){
    return $this->staff()->withTrashed();
}


public function areas(){
    return $this->areasWithTrashed()->whereNull('staff_event_class_areas.deleted_at');
}

public function areasWithTrashed(){
    return $this->belongsToMany('App\LocationArea', 'staff_event_class_areas', 'seca_sec_id', 'seca_la_id')
    ->withPivot('seca_business_id');
}

public function locationAndAreasWithTrashed(){
    return $this->areasWithTrashed()->with('locationWithTrashed')->withTrashed();
}

public function user(){
    return $this->belongsTo('App\Models\Access\User\User', 'sec_user_id');
}

public function userWithTrashed(){
    return $this->user()->withTrashed();
}

public function repeat(){
    return $this->belongsTo('App\StaffEventClassRepeat', 'sec_secr_id', 'secr_id');
}

public function repeatWithTrashed(){
    return $this->repeat()->withTrashed();
}

public function getItemDescData(){
    $returnData=[];
    $staffData = $this->staff;
    if(count($staffData))
        $staffName = $staffData->first_name.' '.$staffData->last_name;
    else
        $staffName = '';
    $classname= Clas::where('cl_id',$this->sec_class_id)->pluck('cl_name')->first();
    foreach ($this->locationAndAreasWithTrashed as $area) {
       $returnData['locationId'] = $area->locationWithTrashed->id;
       $locationName = $area->locationWithTrashed->location_training_area;
   }

       /* $time = $eventClass->sec_duration."minutes";
        $timestamp = strtotime($eventClass->sec_date." +".$time);
        $endTime = date("g:i A", $timestamp);*/

        $returnData['itemDesc'] = $classname.'(Class) with '. $staffName .' on '. date('D, d M Y',strtotime($this->sec_date)); //  .' at Location '. $locationName .' from ' . date('g:i A',strtotime($eventClass->sec_time)). ' to ' .date('g:i A',strtotime($eventClass->sec_end_datetime));
        
        return $returnData;  
    }
    

    static function teamBookings($clientId, $consultationDate, $eventToSkip = 0, $col = 'secc_client_attendance', $consultationExpDate = ''){
      $param = ($col == 'secc_client_attendance')?'attendance':$col;
      $response = SalesProcessProgress::manualCompSteps($clientId, 'team', $param);
      $team = $response['book'];
      $teamed = $response['attend'];

      $bookings = StaffEventClass::join('staff_event_class_clients', 'secc_sec_id', '=', 'sec_id')   
      ->TeamBooking($clientId, $consultationDate, $consultationExpDate)
      ->where('sec_id', '!=', $eventToSkip)
      ->where('secc_client_attendance', '!=', 'Did not show')
      ->select($col)
      ->orderBy('sec_start_datetime')
      ->get();
      if($bookings->count()){
        $db = $bookings->pluck($col)->toArray();
        $result = array_merge($team, $db);
        if($col == 'secc_client_attendance'){
            $teamedCount = count($teamed);
            if($teamedCount){
                foreach($result as $key=>$attendance){
                    if(!$teamedCount)
                        break;
                    if($attendance == 'Booked'){
                        $result[$key] = 'Booked';
                        $teamedCount--;
                    }
                }
            }
        }
        return $result;
    }
    return $team;                                                                         
}

    /*static function clashingBookings($data){
      $validClasses = Clas::OfBusiness()->where('cl_location_id', $data['locId'])->pluck('cl_id')->toArray();
      $query = StaffEventClass::OfBusiness()->whereIn('sec_class_id', $validClasses)->where('sec_start_datetime', '<=', $data['endDatetime'])->where('sec_end_datetime', '>=', $data['startDatetime']);
      if(array_key_exists('eventId', $data))
        $query->where('sec_id', '<>', $data['eventId']);
      
      $clashingEventclas = $query->select('sec_id')->get();
      if($clashingEventclas->count())
          $clashingEventclas = $clashingEventclas->pluck('sec_id')->toArray();

      return $clashingEventclas;
  }*/

  protected static function boot(){
    parent::boot();
    static::deleting(function($event){
        if($event->forceDeleting){
            $event->histories()->forceDelete();
            $event->resources()->forceDelete();
            DB::table('staff_event_class_areas')->where('seca_sec_id', $event->sec_id)->forceDelete();
            if($event->DELETE_LINKED_CLIENTS){
                DB::table('staff_event_class_clients')->where('secc_sec_id', $event->sec_id)->forceDelete();
            }
        } 
        else{
            $event->histories()->delete();
            $event->resources()->delete();
            DB::table('staff_event_class_areas')->where('seca_sec_id', $event->sec_id)->update(array('deleted_at' => createTimestamp()));
            if($event->DELETE_LINKED_CLIENTS){
                $teamSalesProcessClients = $event->teamSalesProcessClients;
                if($teamSalesProcessClients->count()){
                    foreach($teamSalesProcessClients as $teamSalesProcessClient){
                    $event->manageSessionSalesProcess($teamSalesProcessClient, $event->sec_id/*, true*/);
                }    
            }
            DB::table('staff_event_class_clients')->where('secc_sec_id', $event->sec_id)->update(array('deleted_at' => createTimestamp()));
            DB::table('class_clients_rejected')->where('ccr_sec_id', $event->sec_id)->update(array('deleted_at' => createTimestamp()));
        }
    }
});
}
}
