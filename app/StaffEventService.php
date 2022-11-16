<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class StaffEventService extends Model{
    use SoftDeletes;
	protected $table = 'staff_event_services';
	protected $primaryKey = 'ses_id';


    public function event(){
        return $this->belongsTo('App\StaffEvent', 'ses_staff_event_id');
    }

    public function service(){
        return $this->belongsTo('App\Service', 'ses_service_id');
    }

    public function serviceWithTrashed(){
        return $this->service()->withTrashed();
    }

    public function scopeFutureEventServices($query, $serviceId){
        $now = new Carbon();
        return $query->where('staff_event_services.ses_service_id', $serviceId)
                    ->whereHas('event', function($q) use($now){
                        $q->where('se_start_datetime', '>=', $now->toDateTimeString());
                      });
    }

    /*public static function destroyEventServices($eventId){
        $table = (new StaffEventService)->getTable();
        DB::table($table)->where('ses_staff_event_id', $eventId)->update(array('deleted_at' => createTimestamp()));
    }*/

    static function storeEventServices($input, $eventId){
        ksort($input);
        
        $serviceName = $serviceTime = $serviceDur = $servicePrice = $serviceIsDeleted = [];
        foreach($input as $key => $value){
            if(strpos($key, 'serviceName') !== false)
                $serviceName[] = $value;
            
            else if(strpos($key, 'serviceTime') !== false)
                $serviceTime[] = timeStringToDbTime($value);

            else if(strpos($key, 'serviceDur') !== false)
                $serviceDur[] = $value;

            else if(strpos($key, 'servicePrice') !== false)
                $servicePrice[] = $value;

            else if(strpos($key, 'serviceIsDeleted') !== false)
                $serviceIsDeleted[] = $value;
        }  
        $insertData = array();
        if(count($serviceName)){
            for($i=0; $i<count($serviceName); $i++){
                if($serviceName[$i] != ''){
                    $timestamp = createTimestamp();
                    if($serviceIsDeleted[$i] == 'true')
                        $deletedAt = $timestamp;
                    else
                        $deletedAt = null;
                    $insertData[] = array('ses_staff_event_id' => $eventId, 'ses_service_id' => $serviceName[$i], 'ses_time' => $serviceTime[$i], 'ses_price' => $servicePrice[$i], 'ses_duration' => $serviceDur[$i], 'created_at' => $timestamp, 'updated_at' => $timestamp, 'deleted_at' => $deletedAt);
                }
            }
        }
        /*if(count($insertData))
            return DB::table('staff_event_services')->insert($insertData);

        return false;*/

        if(count($insertData) && DB::table('staff_event_services')->insert($insertData))
            return count($insertData);

        return 0;
    }
}
