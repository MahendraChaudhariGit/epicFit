<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;
use App\StaffEvent;
use Carbon\Carbon;
use App\StaffEventService;
//use App\Http\Traits\StaffEventTrait;
use App\Http\Traits\StaffEventsTrait;
use App\LocationArea;
use App\ServiceResources;

class Service extends Model{
    use SoftDeletes, StaffEventsTrait;//, StaffEventTrait;
    protected $table = 'services';
    protected $fillable = ['business_id', 'type', 'category', 'location', 'srvc_la_id', 'one_on_one_name', 'one_on_one_description', 'one_on_one_training_logo', 'one_on_one_colour', 'one_on_one_call_client_online', 'one_on_one_duration'/*, 'one_on_one_capacity'*/, 'one_on_one_staffs', 'one_on_one_price', 'one_on_one_tax', 'team_name', 'team_description', 'team_training_logo', 'team_colour', 'team_can_book_online', 'team_duration'/*, 'team_capacity'*/, 'team_staffs', 'team_price', 'team_tax','is_completed','is_default'];
    
    public function getLogoAttribute(){
        if($this->category == 2)
            return $this->one_on_one_training_logo;
        else if($this->category == 1)
            return $this->team_training_logo;
        else
            return '';
    }

    public function getNameAttribute(){
        if($this->category == 2)
            return $this->one_on_one_name;
        else if($this->category == 1)
            return $this->team_name;
        else
            return '';
    }

    public function getColorAttribute(){
        if($this->category == 2)
            return $this->one_on_one_colour;
        else if($this->category == 1)
            return $this->team_colour;
        else
            return '';
    }

    public function getPriceAttribute(){
        if($this->category == 2)
            return $this->one_on_one_price;
        else if($this->category == 1)
            return $this->team_price;
        else
            return '';
    }

    static function getServiceTypes($businessId = 0, $getParticular = false){
        if($getParticular)
            return DB::table('service_types')->where('st_id', $businessId)->whereNull('deleted_at')->value('st_value');
        else{
            if(!$businessId)
                return DB::table('service_types')->where('st_business_id', 0)->whereNull('deleted_at')->pluck('st_value', 'st_id')->toArray();
            else
                return DB::table('service_types')->whereIn('st_business_id', [0, $businessId])->whereNull('deleted_at')->pluck('st_value', 'st_id')->toArray();
        }   
    }
    
    static function getServiceCats($businessId = 0, $getParticular = false){
		if($getParticular)
            return DB::table('service_cats')->where('sc_id', $businessId)->whereNull('deleted_at')->value('sc_value');
        else{
			if(!$businessId)
				return DB::table('service_cats')->where('sc_business_id', 0)->whereNull('deleted_at')->pluck('sc_value', 'sc_id')->toArray();
			else
				return DB::table('service_cats')->whereIn('sc_business_id', [0, $businessId])->whereNull('deleted_at')->pluck('sc_value', 'sc_id')->toArray();
		}
    }

    static function getServiceName($id){
        $service = Service::select('id','category','one_on_one_name','team_name')->find($id);
        if(count($service)){
            if($service->category == 1) // TEAM
                return ucfirst($service->team_name);
            else if($service->category == 2) // 1 on 1
                return ucfirst($service->one_on_one_name);
        }
        return '';
    }

    public function eventService(){
        return $this->hasMany('App\StaffEventService', 'ses_service_id');
    }

	public function business(){
        return $this->belongsTo('App\Business');
    }

    public function locationn(){
        return $this->belongsTo('App\Location', 'location');
    }

	static function getAreasById($areaId){
		//return DB::table('location_areas')->whereIn('la_id', explode(',',$areaId))->get(array('la_name'));
        return LocationArea::whereIn('la_id', explode(',',$areaId))->get(array('la_name'));
	}

    static function updateStaff($serviceIds, $staffId){
        foreach($serviceIds as $serviceId){
            $service = Service::find($serviceId);
            if($service){
                if($service->category == 1){    // TEAM
                    $staff = $service->team_staffs;
                    $col = 'team_staffs';
                }
                else if($service->category == 2){ // 1 on 1
                    $staff = $service->one_on_one_staffs;
                    $col = 'one_on_one_staffs';
                }

                if($staff)
                    $staff .= ','.$staffId;
                else
                    $staff = $staffId;

                $service->update(array($col => $staff));
            }
        }
    }

    static function defaultAndComplCount(){
        return Service::ofBusiness()/*->where('is_completed', 1)*/->complOnly()->where('is_default', 1)->count();
    }

    public function scopeComplOnly($query){
        return $query->where('is_completed', 1);
    }

    /*public function scopeDefaultAndCompl($query){
        return $query->where('is_completed', 1)->where('is_default', 1);
    }*/
    public function resources(){
        return $this->morphMany('App\ServiceResources', 'resorcesable', 'sr_entity_type', 'sr_entity_id');
    }


    public function scopeJoinCats($query){
        return $query->leftJoin('service_cats', 'sc_id', '=', 'category')/*->whereNull('service_cats.deleted_at')*/->select('service_cats.sc_value', 'service_cats.deleted_at as catDeletedAt', 'services.*');
    }

    public function scopeOfBusiness($query, $bussId = 0){
        if(!$bussId)
            $bussId = Session::get('businessId');

        return $query->where('business_id', $bussId);
    }

    static function findService($serviceId, $bussId = 0){
        return Service::OfBusiness($bussId)->find($serviceId);
    }

    static function findOrFailService($serviceId, $bussId = 0){
        return Service::OfBusiness($bussId)->findOrFail($serviceId);
    }

    static function linkedResources($id){
        /*return ServiceResources::with(array('resource' => function($query){
                                    $query->select('id', 'res_name');
                                 }))
                                 ->OfService($id)
                                 ->select('sr_res_id', 'sr_item_quantity')
                                 ->get();*/
        $locId = Service::OfBusiness()->select('location')->withTrashed()->find($id)->location;   
        return ServiceResources::withResource($locId)
                                 ->OfService($id)
                                 ->withTrashed()
                                 ->get();                                   
    }

    public function events(){
        return $this->hasMany('App\StaffEventSingleService', 'sess_service_id');
    }

    public function eventsWithTrashed(){
        return $this->events()->withTrashed();
    }

    public function futureEvents(){
        $now = new Carbon();
        return $this->events()->where('sess_start_datetime', '>=', $now->toDateTimeString());
    }

    protected static function boot(){
        parent::boot();
        static::deleting(function($service){
            ServiceResources::/*where('sr_entity_id',$clas->id)->where('sr_business_id',Session::get('businessId'))->where('sr_entity_type','App\Clas')*/OfService($service->id)->delete();

            /*$service_resource=ServiceResources::where('sr_entity_id',$service->id)->where('sr_business_id',Session::get('businessId'))->where('sr_entity_type','App\Service');
               $service_resource->delete();*/
            //$now = new Carbon();
            /*StaffEventService::where('staff_event_services.ses_service_id', $service->id)
                            ->whereHas('event', function($q) use($now){
                                $q->where('se_start_datetime', '>=', $now->toDateTimeString());
                              })
                            ->delete();*/
            /*DB::enableQueryLog();
            $appointmentIds = [93, 94];
            $appointments = StaffEvent::with(array('eventServices' => function($query){
                                            $query->orderBy('created_at');
                                       }))
                                    ->find($appointmentIds);

            foreach($appointments as $appointment){
                $firstService = $appointment->eventServices->first();
                $eventStartDatetime = $service->calcStartDatetime(['startTime' => $firstService->ses_time, 'startDate' => $appointment->se_date]);

                $lastService = $appointment->eventServices->last();
                $eventEndDatetime = $service->calcEndDatetime(['startTime' => $lastService->ses_time, 'startDate' => $appointment->se_date, 'duration' => $lastService->ses_duration]);

                $appointment->se_start_datetime = $eventStartDatetime;
                $appointment->se_end_datetime = $eventEndDatetime;
                $appointment->save();
            }
            dd(DB::getQueryLog());
            dd($appointments);*/
            /*$futureEventServices = StaffEventService::with('event')
                                ->futureEventServices($service->id)
                                ->get();

            if($futureEventServices->count()){
                $eventServiceIds = $appointmentIds = [];

                foreach($futureEventServices as $futureEventService){
                    $eventServiceIds[] = $futureEventService->ses_id;
                    $appointmentIds[] = $futureEventService->event->se_id;
                }
                StaffEventService::destroy($eventServiceIds);

                $appointmentIds = array_unique($appointmentIds);

                $appointmentsWithNoService = StaffEvent::has('eventServices', '<', 1)->get();
                if($appointmentsWithNoService->count()){
                    $appointmentsWithNoServiceIds = [];
                    foreach($appointmentsWithNoService as $appointmentWithNoService){
                        $appointmentsWithNoServiceIds[] = $appointmentWithNoService->se_id;
                        $appointmentWithNoService->delete();
                    }
                    $appointmentIds = array_diff($appointmentIds, $appointmentsWithNoServiceIds);

                    $appointments = StaffEvent::with(array('eventServices' => function($query){
                                                    $query->orderBy('created_at');
                                               }))
                                            ->find($appointmentIds);

                    foreach($appointments as $appointment){
                        $firstService = $appointment->eventServices->first();
                        $eventStartDatetime = $service->calcStartDatetime(['startTime' => $firstService->ses_time, 'startDate' => $appointment->se_date]);
                        
                        $lastService = $appointment->eventServices->last();
                        $eventEndDatetime = $service->calcEndDatetime(['startTime' => $lastService->ses_time, 'startDate' => $appointment->se_date, 'duration' => $lastService->ses_duration]);

                        $appointment->se_start_datetime = $eventStartDatetime;
                        $appointment->se_end_datetime = $eventEndDatetime;
                        $appointment->se_start_time = $firstService->ses_time;
                        $appointment->se_duration = $service->calcEventDuartion(['endDatetime' => $eventEndDatetime, 'startDatetime' => $eventStartDatetime]);
                        $appointment->save();
                    }
                }
            }
            */
            foreach($service->futureEvents as $event)
                $event->delete();

            // Delete invoice
            $invoiceIds = \App\InvoiceItems::select('inp_invoice_id')->where('inp_product_id', $service->id)->where('inp_type', 'service')->get()->toArray();

            $invoices = \App\Invoice::whereIn('inv_id',$invoiceIds)->get();
            if($invoices->count()){
                foreach ($invoices as $invoice) {
                    $invoice->delete();
                }
            }
        });
        static::deleted(function(){
            if(!Service::OfBusiness()->exists())
                Session::forget('ifBussHasServices');
        });
    }
}
