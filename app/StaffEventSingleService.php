<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use DB;
use Carbon\Carbon;
use App\Service;
use App\SalesProcessProgress;
//use App\SalesProcess;

class StaffEventSingleService extends Model{
    use SoftDeletes;
	protected $table = 'staff_event_single_services';
	protected $primaryKey = 'sess_id';
    protected $fillable = ['sess_service_id', 'sess_duration', 'sess_price', 'sess_time', 'sess_staff_id', 'sess_start_datetime', 'sess_end_datetime','sess_invoice_status','sess_payment','sess_event_log','
    deleted_at','sess_cmid', 'sess_action_performed_by','is_ldc','sales_step_number'];


    /*
    **start: ACCESSOR
    */
      public function getEventDateCarbonAttribute(){
            return setLocalToBusinessTimeZone(new Carbon($this->sess_date));
        }

        public function getEventStartTimeCarbonAttribute(){
            return new Carbon($this->sess_time);
        }
    /*
    **end: ACCESSOR
    */


    /*
    **start: SCOPES
    */
        public function scopeClashingEvents($query, $data){
            if(array_key_exists('eventId', $data))
                return $query->where('sess_id', '<>', $data['eventId'])
                                ->clashingEventsHelper($data);
            else                         
                return $query->clashingEventsHelper($data);
        }
        public function scopeClashingEventsHelper($query, $data){
            return $query/*->where('sess_booking_status', 'Confirmed')*/
                            ->where(function($query) use ($data){
                                    $query->where(function($q) use ($data){
                                                $q->where('sess_start_datetime', '>=', $data['startDatetime'])
                                                    ->where('sess_start_datetime', '<', $data['endDatetime']);
                                    })
                                    ->orWhere(function($q) use ($data){
                                            $q->where('sess_start_datetime', '<=', $data['startDatetime'])
                                                ->where('sess_end_datetime', '>', $data['startDatetime']);
                                    });
                             });
        }

        public function scopeOfAreaAndStaffAndDatedBetween($query, $request){
            //return $query->OfAreaAndStaff($request)->whereBetween('sess_date', array($request->startDate, $request->endDate));
            return $query->OfAreaAndStaff($request)->DatedBetween($request->startDate, $request->endDate);
        }

        public function scopeOfAreaAndStaffAndDated($query, $request){
            return $query->OfAreaAndStaff($request)->where('sess_date', $request->startDate);
        }

        public function scopeDatedBetween($query, $startDate, $endDate){
            return $query->whereBetween('sess_date', array($startDate, $endDate));
        }
        
        public function scopeOfAreaAndStaff($query, $request){
            return $query->OfStaff($request->staffId)->OfArea($request->areaId);
        }

        public function scopeOfArea($query, $areaId){
            return $query->whereHas('areas', function($query) use($areaId){
                                $query->whereIn('sessa_la_id', [$areaId]);
                            });
        }

        public function scopeOfStaff($query, $staffId){
            return $query->where('sess_staff_id', $staffId);
        }
        
        public function scopeOfStaffs($query, $staffsId){
            return $query->whereIn('sess_staff_id', $staffsId);
        }

        public function scopeOfClient($query, $clientId){
             return $query->where('sess_client_id', $clientId);
        }

        public function scopeChildEvents($query, $eventId){
            return $query->where('sess_parent_id', $eventId);
        }

        public function scopeSiblingEvents($query, $data){
            return $query->ChildEvents($data['parentEventId'])->where('sess_date', '>', $data['eventDate'])->where('sess_id', '!=', $data['eventId']);
        }

        public function scopeOfBusiness($query){
            return $query->where('sess_business_id', Session::get('businessId'));
        }

        public function scopeOfClientAndInSalesProcess($query, $clientId, $saleProcessStatus){
            return $query->OfBusiness()->where('sess_client_id', $clientId)->whereIn('sess_sale_process_status', $saleProcessStatus);
        }

        public function scopeOfClientAndInSalesProcessAndClientAttendanceIs($query, $data){
            return $query->OfClientAndInSalesProcess($data['clientId'], $data['saleProcessStatus'])->where('sess_client_attendance', $data['attendance']);
        }
    /*
    **end: SCOPES
    */


    /*
    **start: RELATIONS
    */
        public function areasWithTrashed(){
            return $this->belongsToMany('App\LocationArea', 'staff_event_single_service_areas', 'sessa_sess_id', 'sessa_la_id')
                        ->withPivot('sessa_business_id');
        }

        public function areas(){
            return $this->areasWithTrashed()->whereNull('staff_event_single_service_areas.deleted_at');
        }

        public function locationAndAreasWithTrashed(){
            return $this->areasWithTrashed()->with('locationWithTrashed')->withTrashed();
        }
        
        public function histories(){
            return $this->morphMany('App\StaffEventHistory', 'historyable', 'seh_event_type', 'seh_event_id');
        }

        public function historiesWithTrashed(){
            return $this->histories()->withTrashed();
        }

        public function repeat(){
            return $this->belongsTo('App\StaffEventSingleServiceRepeat', 'sess_sessr_id');
        }

        public function repeatWithTrashed(){
            return $this->repeat()->withTrashed();
        }

        public function client(){
            return $this->belongsTo('App\Clients', 'sess_client_id');
        }

        public function clientWithTrashed(){
            return $this->client()->withTrashed();
        }

        public function user(){
            return $this->belongsTo('App\Models\Access\User\User', 'sess_user_id');
        }

        public function userWithTrashed(){
            return $this->user()->withTrashed();
        }

        public function staff(){
            return $this->belongsTo('App\Staff', 'sess_staff_id');
        }

        public function staffWithTrashed(){
            return $this->staff()->withTrashed();
        }

        public function service(){
            return $this->belongsTo('App\Service', 'sess_service_id');
        }

        public function serviceWithTrashed(){
            return $this->service()->withTrashed();
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
    
    public function skips(){
        return $this->morphMany('App\StaffEventSkip', 'skipable', 'sk_event_type', 'sk_parent_id');
    }
    /*
    **end: RELATIONS
    */


    /*
    **start: FUNCTIONS
    */
        /*static function teamedCount($clientId){
            return StaffEventSingleService::OfClientAndInSalesProcess($clientId, ['teamed'])->count();
        }*/

        public function getItemDescData(){
            $returnData=[];
            //if($this->count()){
            $staffData = $this->staff;
            $staffName = $staffData->first_name.' '.$staffData->last_name;
            $serviceName= Service::ofBusiness()->find($this->sess_service_id)->name;
            foreach ($this->locationAndAreasWithTrashed as $area) {
                 $returnData['locationId'] = $area->locationWithTrashed->id;
                $locationName = $area->locationWithTrashed->location_training_area;
            }

           /* $time = $eventClass->sec_duration."minutes";
            $timestamp = strtotime($eventClass->sec_date." +".$time);
            $endTime = date("g:i A", $timestamp);*/

            $returnData['itemDesc'] = $serviceName.' with '. $staffName .' on '. date('D, d M Y',strtotime($this->sess_date)); //  .' at Location '. $locationName .' from ' . date('g:i A',strtotime($eventClass->sess_time)). ' to ' .date('g:i A',strtotime($eventClass->sess_end_datetime));
            
            //}
            return $returnData;  
        }

        static function deleteUnattendedSalesProcess($data){
            StaffEventSingleService::OfClientAndInSalesProcessAndClientAttendanceIs(['clientId' => $data['clientId'], 'saleProcessStatus' => $data['saleProcessStatus'], 'attendance' => 'Did not show'])->delete(); //$data['attendance']
        }

        /*static function clashingBookings($data){
            $validServices = Service::OfBusiness()->where('location', $data['locId'])->pluck('id')->toArray();
            $query = StaffEventSingleService::OfBusiness()->whereIn('sess_service_id', $validServices)->where('sess_start_datetime', '<=', $data['endDatetime'])->where('sess_end_datetime', '>=', $data['startDatetime']);
            if(array_key_exists('eventId', $data))
                $query->where('sess_id', '<>', $data['eventId']);
          
            $clashingEventServ = $query->select('sess_id')->get();
            if($clashingEventServ->count())
                $clashingEventServ = $clashingEventServ->pluck('sess_id')->toArray();

            return $clashingEventServ;
        }*/

        static function indivBookings($clientId, $consultationDate/*, $eventToSkip = 0*/, $col = 'sess_client_attendance'){
            $param = ($col == 'sess_client_attendance')?'attendance':$col;
            $response = SalesProcessProgress::manualCompSteps($clientId, 'indiv', $param);
            $indiv = $response['book'];
            $indived = $response['attend'];

            $serv = Service::ofBusiness()->ComplOnly()->where('for_sales_process_step', 0)->select('id')->get();

            if($serv->count())
                $servId = $serv->pluck('id')->toArray();
            else
                $servId = [];
            $servBookings = StaffEventSingleService::where(function($query){
                                                        $query->has('repeat', '<', 1)
                                                              ->orWhereHas('repeat',function($q){
                                                                    // $q->where('ser_repeat','None');
                                                                    $q->where('sessr_repeat','None');
                                                        });
                                                    })
                                                    ->OfBusiness()
                                                    ->where('sess_booking_status', 'Confirmed')
                                                    ->where('sess_client_id', $clientId)
                                                    ->where('sess_date', '>=', $consultationDate)
                                                    ->whereRaw("DATEDIFF(sess_date, '$consultationDate') < 15")
                                                    ->where('sess_client_attendance', '!=', 'Did not show')
                                                    ->whereIn('sess_service_id', $servId)
                                                    //->where('sess_id', '!=', $eventToSkip)
                                                    ->select($col)
                                                    ->orderBy('sess_date')
                                                    ->get();
            if($servBookings->count()){
                $db = $servBookings->pluck($col)->toArray();
                $result = array_merge($indiv, $db);
                if($col == 'sess_client_attendance'){
                    $indivedCount = count($indived);
                    if($indivedCount){
                        foreach($result as $key=>$attendance){
                            if(!$indivedCount)
                                break;
                            if($attendance == 'Booked'){
                                $result[$key] = 'Attended';
                                $indivedCount--;
                            }
                        }
                    }
                }
                return $result;
            }
            return $indiv;                                                          
        }
    /*
    **end: FUNCTIONS
    */


    /*
    **start: EVENTS
    */
        protected static function boot(){
            parent::boot();
            static::deleting(function($event){
                if($event->forceDeleting){
                    $event->histories()->forceDelete();
                    //$event->repeat()->forceDelete();
                    $event->resources()->forceDelete();
                    if(!$event->sess_parent_id && $event->sess_is_repeating){
                        $event->skips()->forceDelete();
                    }
                    DB::table('staff_event_single_service_areas')->where('sessa_sess_id', $event->sess_id)->delete();
                    /*if($event->sess_sale_process_status != null && $event->sess_sale_process_status != 'book_team')
                        SalesProcess::where('sp_entity_id', $event->sess_id)->OfType([$event->sess_sale_process_status])->forceDelete();*/
                } 
                else{
                    //dd($event);
                    $event->histories()->delete();
                    //$event->repeat()->delete();
                    $event->resources()->delete();
                    if(!$event->sess_parent_id && $event->sess_is_repeating){
                        $event->skips()->delete();
                    }
                    DB::table('staff_event_single_service_areas')->where('sessa_sess_id', $event->sess_id)->update(array('deleted_at' => createTimestamp()));
                    /*if($event->sess_sale_process_status != null && $event->sess_sale_process_status != 'book_team')
                        SalesProcess::where('sp_entity_id', $event->sess_id)->OfType([$event->sess_sale_process_status])->delete();*/
                }

                // Delete invoice
                $invoiceIds = \App\InvoiceItems::select('inp_invoice_id')->where('inp_product_id', $event->sess_id)->where('inp_type', 'appointment')->get()->toArray();

                $invoices = \App\Invoice::whereIn('inv_id',$invoiceIds)->get();
                if($invoices->count()){
                    foreach ($invoices as $invoice) {
                        $invoice->delete();
                    }
                } 
            });
        }
    /*
    **END: EVENTS
    */
}
