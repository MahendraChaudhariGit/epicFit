<?php
/* 
REQUIREMENT: 
    StaffEventsTrait for (resourcesInUse())
*/
namespace App\Http\Traits;
use App\StaffEventClass;
use App\StaffEventSingleService;
use App\StaffEventResource;
use App\Clas;
use App\Service;

trait StaffEventResourceTrait{
    protected function ifResourcesInUse($data){
        $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $data['time'], 'startDate' => $data['date'], 'duration' => $data['duration']]);
        $eventStartDatetime = $startAndEndDatetime['startDatetime'];
        $eventEndDatetime = $startAndEndDatetime['endDatetime'];
        $clashingBookingData = ['endDatetime' => $eventEndDatetime, 'startDatetime' => $eventStartDatetime, 'locId' => $data['locId']];            

        //Getting clashing class bookings
        /*if(array_key_exists('eventId', $data) && $data['eventType'] == 'class')
            $clashingBookingData['eventId'] = $data['eventId'];
        $clashingEventclas = StaffEventClass::clashingBookings($clashingBookingData);

        //Getting clashing service bookings*/
        if(array_key_exists('eventId', $data))
            $clashingBookingData['eventId'] = $data['eventId'];
        $clashingEvents = $this->clashingBookings($clashingBookingData);

        if(count($clashingEvents['classes']) || count($clashingEvents['services'])){
            // Getting resources in use along with quantity over the event's time frame
            $usedRes = StaffEventResource::whereIn('serc_res_id', $data['resId'])
                        ->where(function($query) use ($clashingEvents){
                            $query->where(function($query) use ($clashingEvents){
                                if(count($clashingEvents['classes'])){
                                    $query->where('serc_event_type', 'App\StaffEventClass')
                                    ->whereIn('serc_event_id', $clashingEvents['classes']);
                                }
                            })
                            ->orWhere(function($query) use ($clashingEvents){
                                if(count($clashingEvents['services'])){
                                    $query->where('serc_event_type', 'App\StaffEventSingleService')
                                        ->whereIn('serc_event_id', $clashingEvents['services']);
                                }
                            });
                        })
                        ->selectRaw('serc_res_id, sum(serc_item_quantity) as serc_item_quantity')
                        ->groupBy('serc_res_id')
                        ->get();
            //whereIn('serc_res_id', $data['resId']);
            /*if(!count($clashingEventclas))
                $query->where('serc_event_type', '!=', 'App\StaffEventClass');
            else if(!count($clashingEventServ))
                $query->where('serc_event_type', '!=', 'App\StaffEventSingleService');

            $usedRes = $query->where(function($query) use($clashingEvents){

                                    if(count($clashingEventclas)){
                                        $query->where(function($query) use($clashingEventclas){
                                            $query->where('serc_event_type', 'App\StaffEventClass')->whereIn('serc_event_id', $clashingEventclas);
                                        });
                                    }

                                    if(count($clashingEventServ)){
                                        $query->orWhere(function($query) use($clashingEventServ){
                                            $query->where('serc_event_type', 'App\StaffEventSingleService')->whereIn('serc_event_id', $clashingEventServ);
                                        });
                                    }
                                })
                                ->selectRaw('serc_res_id, sum(serc_item_quantity) as serc_item_quantity')
                                ->groupBy('serc_res_id')
                                ->get();*/

            if($usedRes->count())
                $usedRes = $usedRes->pluck('serc_item_quantity', 'serc_res_id')->toArray();
        }
        else
            $usedRes = [];

        return $usedRes;
    }

    protected function clashingBookings( $data ){
        $validClasses = Clas::OfBusiness()->where('cl_location_id', $data['locId'])->pluck('cl_id')->toArray();
        $query = StaffEventClass::OfBusiness()->whereIn('sec_class_id', $validClasses)->where('sec_start_datetime', '<=', $data['endDatetime'])->where('sec_end_datetime', '>=', $data['startDatetime']);
        if(array_key_exists('eventId', $data))
            $query->where('sec_id', '<>', $data['eventId']);
          
        $clashingEventclas = $query->select('sec_id')->get();
        if($clashingEventclas->count())
            $clashingEventclas = $clashingEventclas->pluck('sec_id')->toArray();


        $validServices = Service::OfBusiness()->where('location', $data['locId'])->pluck('id')->toArray();
            $query = StaffEventSingleService::OfBusiness()->whereIn('sess_service_id', $validServices)->where('sess_start_datetime', '<=', $data['endDatetime'])->where('sess_end_datetime', '>=', $data['startDatetime']);
        if(array_key_exists('eventId', $data))
            $query->where('sess_id', '<>', $data['eventId']);
          
        $clashingEventServ = $query->select('sess_id')->get();
        if($clashingEventServ->count())
            $clashingEventServ = $clashingEventServ->pluck('sess_id')->toArray();

        return ['classes'=>$clashingEventclas, 'services'=>$clashingEventServ];
    }
}