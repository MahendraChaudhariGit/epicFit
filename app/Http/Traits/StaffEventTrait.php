<?php
namespace App\Http\Traits;

//use App\Http\Requests;    
//use App\StaffEvent;
use App\StaffEventSingleService;
use DB;
//use App\StaffEventClass;
//use App\Http\Traits\StaffEventsTrait;
//use Auth;

trait StaffEventTrait{
    //use StaffEventsTrait;

    /*protected function deleteExpiringAspirantsEvents(){
        if(Auth::user()->hasPermission(Auth::user(), 'delete-staff-event-appointment')){
            $events = StaffEvent::OfBusiness()->where('se_booking_status', 'Pencilled-In')->where('se_auto_expire_datetime', '!=', '0000-00-00 00:00:00')->whereRaw('se_auto_expire_datetime <= now()')->pluck('se_id');
            if($events->count()){
                $events->each(function($event){
                    $request = new Request;
                    $request['eventId'] = $event;
                    $this->destroy($request);
                }); 
            }   
        }
    }*/

    protected function deleteExpiringAspirantsEvents(){
        /*$eventsId = StaffEvent::OfBusiness()->where('se_booking_status', 'Pencilled-In')->where('se_auto_expire_datetime', '!=', '0000-00-00 00:00:00')->whereRaw('se_auto_expire_datetime <= now()')->pluck('se_id');
        if($eventsId->count()){
            $eventsId->each(function($eventId){
                StaffEvent::find($eventId)->delete();
            }); 
        }   */
        $eventsId = StaffEventSingleService::OfBusiness()->where('sess_booking_status', 'Pencilled-In')/*->where('sess_auto_expire_datetime', '!=', '0000-00-00 00:00:00')*/->whereRaw('sess_auto_expire_datetime <= now()')->pluck('sess_id');
        if($eventsId->count()){
            $eventsId->each(function($eventId){
                StaffEventSingleService::find($eventId)->delete();
            }); 
        } 
    }

    /**
     * Delete sales process(associated events and log) of a client from the step specified 
     *
     * @param integer $stepIndex Index of the step
     * @param integer $clientId Client ID
     * 
     */ 
    protected function deleteAheadedSalesProcess($stepIndex, $clientId, $consultationDate){
        $salesProcessTypes = salesProcessTypes();
        $temp = array_slice($salesProcessTypes, $stepIndex);
        if(count($temp)){
            StaffEventSingleService::OfClientAndInSalesProcess($clientId, $temp)->delete();
            //SalesProcess::OfClientAndOfType($clientId, $temp)->delete();
        }

        if($consultationDate != null){
            $saleProcessTeamedDetails = calcSalesProcessRelatedStatus('teamed');
            if($stepIndex < $saleProcessTeamedDetails['saleProcessStepNumb']){
                /*StaffEventClass::from( 'staff_event_classes as a' )->join('staff_event_class_clients as b', 'b.secc_sec_id', '=', 'a.sec_id')   
                               ->TeamBooking($clientId, $consultationDate)
                               ->update(['b.updated_at' => createTimestamp(), 'b.deleted_at' => createTimestamp()]);*/
                return DB::table('staff_event_class_clients')->join('staff_event_classes', 'secc_sec_id', '=', 'sec_id')
                                                             ->where('secc_client_id', $clientId)
                                                             ->where('secc_if_recur', 0)
                                                             ->whereNull('staff_event_class_clients.deleted_at')
                                                             ->whereNull('staff_event_classes.deleted_at')
                                                             ->where('sec_date', '>=', $consultationDate)
                                                             ->whereRaw("DATEDIFF(sec_date, '$consultationDate') < 15")
                                                             ->delete();
                                                             //->update(['staff_event_class_clients.deleted_at' => createTimestamp()]);                                                   
            }
        }
    }
}