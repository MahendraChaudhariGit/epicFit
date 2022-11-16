<?php
/* 
REQUIREMENT: 
    LocationAreaTrait(eventsListForOverview())
*/
namespace App\Http\Traits\Result;

use Illuminate\Http\Request;
use Carbon\Carbon;

use \stdClass;
use DB;
use Auth;
use Session;

use App\Result\Staff;
use App\Result\Clients;
use App\Result\StaffEvent;
use App\Result\ClientMember;
use App\Result\StaffEventBusy;
use App\Result\StaffEventClass;
use App\Result\StaffEventRepeat;
use App\Result\ClientMemberLimit;
use App\Result\StaffEventService;
use App\Result\StaffEventResource;
use App\Result\StaffEventSingleService;



trait ClientEventsTrait{
    
    private $weekDaysArr = [0 => 'Mon', 1 => 'Tue', 2 => 'Wed', 3 => 'Thu', 4 => 'Fri', 5 => 'Sat', 6 => 'Sun'];

    protected function staffHasDayActivity($request){
        $eventData = new stdClass();
        $eventData->areaId = $request->areaId;
        $eventData->staffId = $request->staffId;
        $eventData->startDate = $request->startDate;

        if(StaffEventClass::OfAreaAndStaffAndDated($eventData)->get()->count() || StaffEvent::OfAreaAndStaffAndDated($eventData)->get()->count() || StaffEventBusy::OfAreaAndStaffAndDated($eventData)->get()->count() || count(Staff::getStaffHours($request->staffId, $request->day)))
            return true;

        return false;
    }

    protected function staffHasWeekActivity($request){
        $eventData = new stdClass();
        $eventData->areaId = $request->areaId;
        $eventData->staffId = $request->staffId;
        $eventData->startDate = $request->startDate;
        $eventData->endDate = $request->endDate;

        if(StaffEventClass::OfAreaAndStaffAndDatedBetween($eventData)->get()->count() || StaffEvent::OfAreaAndStaffAndDatedBetween($eventData)->get()->count() || StaffEventBusy::OfAreaAndStaffAndDatedBetween($eventData)->get()->count() || count(Staff::getStaffHours($request->staffId)))
            return true;

        return false;
    }

    protected function calcRepeatsDateOnOrNeverCase($data){        
        $dates = [];
        $eventDate = new Carbon($data['eventDate']);
        $eventRepeatEndOnDate = new Carbon($data['eventRepeatEndOnDate']);
        while($eventDate->lte($eventRepeatEndOnDate)){
            $param = ['eventDate' => $eventDate, 'eventRepeat' => $data['eventRepeat'], 'repeatIntv' => $data['eventRepeatInterval']];
            
            if($data['eventRepeat'] == 'Weekly')
                $param['eventRepeatWeekdays'] = $data['eventRepeatWeekdays'];
            
            $dates[] = $this->calcRepeatsDate($param);
        }

        $this->getRepeatsDate($dates);
        return $dates;
    }

   /**
     * Check if client satisfy the membership restrictions
     *
     * @param int $clientId Client ID
     * @param array $eventData ['event_type','event_id','event_date']
     * @return array
     */
    protected function satisfyMembershipRestrictions($clientId, $eventData, $clientMembershipLimit = ''){
        $isMembError = false;
        if($clientMembershipLimit == '')
            $clientMembershipLimit = collect();

        $clientMember = ClientMember::where('cm_client_id', $clientId)->orderBy('id','desc')->first();

        if(!count($clientMember)){
            $isMembError = true;
            return array('satisfy'=>false,'clientMembId'=>0, 'failReas'=>'membership_doesnot_exit');
        }

        if(!$isMembError && $clientMember->cm_status != 'Active'){
            $isMembError = true;
            $failReas = 'membership_'.strtolower($clientMember->cm_status);
            return array('satisfy'=>false, 'clientMembId'=>0, 'failReas'=>$failReas);  
        }

        if(!$isMembError){
            if($eventData['event_type'] == 'class'){ // check membership for class
                $classes = json_decode($clientMember->cm_classes, 1);
                if(!array_key_exists($eventData['event_id'], $classes)){
                    $isMembError = true;
                    return array('satisfy'=>false, 'clientMembId'=>0, 'failReas'=>'class_doesnot_exist_in_membership'); 
                }

                if(!$isMembError){
                    if($clientMember->cm_class_limit == 'unlimited'){
                       return array('satisfy'=>true,  'clientMembId'=>$clientMember->id); 
                    }
                    elseif($clientMember->cm_class_limit == 'limited'){
                        if($clientMember->cm_class_limit_type == 'every_week'){
                            $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'classes_weekly', $clientMember->cm_class_limit_length, $eventData['event_date'], $clientMembershipLimit);
                            if($clientMembEvent['status'])
                                return array('satisfy'=>true, 'clientMembId'=>$clientMember->id, 'limit_type'=>'every_week','clientMembLimit'=>$clientMembEvent['clientMembLimit']);
                            else
                                return array('satisfy'=>false, 'clientMembId'=>0, 'failReas'=>$clientMembEvent['message']); 
                        }
                        elseif($clientMember->cm_class_limit_type == 'every_month'){
                            $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'classes_monthly', $clientMember->cm_class_limit_length, $eventData['event_date'], $clientMembershipLimit);
                            if($clientMembEvent['status'])
                                return array('satisfy'=>true, 'clientMembId'=>$clientMember->id, 'limit_type'=>'every_month','clientMembLimit'=>$clientMembEvent['clientMembLimit']);
                            else
                                return array('satisfy'=>false, 'clientMembId'=>0, 'failReas'=>$clientMembEvent['message']);
                        }

                    }
                }
            }
            elseif($eventData['event_type'] == 'service'){ // check membership for service
                $service_limits = json_decode($clientMember->cm_services_limit, 1);
                $serviceId = $eventData['event_id'];
                if(count($service_limits) && array_key_exists($serviceId, $service_limits) && array_key_exists('limit', $service_limits[$serviceId]) && array_key_exists('limit_type', $service_limits[$serviceId])){
                    $s_limit = $service_limits[$serviceId]['limit'];
                    $s_limit_type = $service_limits[$serviceId]['limit_type'];
                    
                    if($s_limit_type == 'every_week'){
                        $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'service_weekly', $s_limit, $eventData['event_date'], $clientMembershipLimit, $serviceId);
                        if($clientMembEvent['status'])
                            return array('satisfy'=>true, 'clientMembId'=>$clientMember->id, 'limit_type'=>'every_week','clientMembLimit'=>$clientMembEvent['clientMembLimit']);
                        else
                            return array('satisfy'=>false, 'clientMembId'=>0, 'failReas'=>$clientMembEvent['message']);
                    }
                    elseif($s_limit_type == 'every_month'){
                        $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'service_monthly', $s_limit, $eventData['event_date'], $clientMembershipLimit, $serviceId);
                        if($clientMembEvent['status'])
                            return array('satisfy'=>true,  'clientMembId'=>$clientMember->id, 'limit_type'=>'every_month','clientMembLimit'=>$clientMembEvent['clientMembLimit']);
                        else
                            return array('satisfy'=>false, 'clientMembId'=>0, 'failReas'=>$clientMembEvent['message']); 
                    }
                    elseif($s_limit_type == 'every_fortnight'){
                        $clientMembEvent = $this->isClientMembEventSatisfy($clientId, 'service_fortnight', $s_limit, $eventData['event_date'], $clientMembershipLimit, $serviceId);
                        if($clientMembEvent['status'])
                            return array('satisfy'=>true,  'clientMembId'=>$clientMember->id, 'limit_type'=>'every_fortnight','clientMembLimit'=>$clientMembEvent['clientMembLimit']);
                        else
                            return array('satisfy'=>false, 'clientMembId'=>0, 'failReas'=>$clientMembEvent['message']); 
                    }
                }
                else{
                    return array('satisfy'=>false, 'clientMembId'=>0, 'failReas'=>'limit_doesnot_exist'); 
                }
            }
        }  
    }


    /**
     * check how many events booked in this membership 
     * @param string Type (service/classes_monthly/classes_weekly)
     * @param int Event Limit
     * @param date Event date
     * @return array
     */
    protected function isClientMembEventSatisfy($clientId, $limit_type, $limit, $eventDate, $clientMembEvent, $serviceId = 0){
        if(!count($clientMembEvent))
            $clientMembEvent = ClientMemberLimit::where('cme_client_id', $clientId)->first();

        if(count($clientMembEvent)){
            $carbonDate = Carbon::createFromFormat('Y-m-d', $eventDate);
            $eventDate = $carbonDate->copy();
            $eventyear = $eventDate->year;
            if($serviceId != 0){
                if($limit_type == 'service_weekly'){
                    $weekDate = $carbonDate->copy();
                    $weekNo = $weekDate->weekOfYear; 
                    if($clientMembEvent->cme_services_weekly != ''){
                        $weeklyService = json_decode($clientMembEvent->cme_services_weekly, 1);
                        if(array_key_exists($serviceId, $weeklyService) && array_key_exists($eventyear, $weeklyService[$serviceId]) && array_key_exists($weekNo, $weeklyService[$serviceId][$eventyear])){
                            if($weeklyService[$serviceId][$eventyear][$weekNo] < $limit)
                                return array('status'=>true,'clientMembLimit'=>$clientMembEvent); 
                            else
                                return array('status'=>false,'message'=>'limit_exceeded','cmeId'=>$clientMembEvent->id); 
                        }
                    }
                }
                elseif($limit_type == 'service_monthly'){
                    $monthDate = $carbonDate->copy();
                    $monthNo = $monthDate->month;
                    if($clientMembEvent->cme_services_monthly != ''){
                        $monthlyService = json_decode($clientMembEvent->cme_services_monthly, 1);
                        if(array_key_exists($serviceId, $monthlyService) && array_key_exists($eventyear, $monthlyService[$serviceId]) && array_key_exists($monthNo, $monthlyService[$serviceId][$eventyear])){
                            if($monthlyService[$serviceId][$eventyear][$monthNo] < $limit)
                                return array('status'=>true,'clientMembLimit'=>$clientMembEvent); 
                            else
                                return array('status'=>false,'message'=>'limit_exceeded','cmeId'=>$clientMembEvent->id); 
                        }
                    }
                }
                elseif($limit_type == 'service_fortnight'){
                    $fortnightDate = $carbonDate->copy();
                    $fortnightNo = $this->getEvenWeekNumb($fortnightDate);
                    if($clientMembEvent->cme_services_fortnight != ''){
                        $fortnightClasses = json_decode($clientMembEvent->cme_services_fortnight, 1);
                        if(array_key_exists($serviceId, $fortnightClasses) && array_key_exists($eventyear, $fortnightClasses[$serviceId]) && array_key_exists($fortnightNo, $fortnightClasses[$serviceId][$eventyear])){
                            if($fortnightClasses[$serviceId][$eventyear][$fortnightNo] < $limit)
                                return array('status'=>true,'clientMembLimit'=>$clientMembEvent); 
                            else
                                return array('status'=>false,'message'=>'limit_exceeded','cmeId'=>$clientMembEvent->id); 
                        }
                    }
                }  
            }
            else{
                if($limit_type == 'classes_weekly'){
                    $weekDate = $carbonDate->copy();
                    $weekNo = $weekDate->weekOfYear; 
                    if($clientMembEvent->cme_classes_weekly != ''){
                        $weeklyClasses = json_decode($clientMembEvent->cme_classes_weekly, 1);
                        if(array_key_exists($eventyear,$weeklyClasses) && array_key_exists($weekNo,$weeklyClasses[$eventyear])){
                            if($weeklyClasses[$eventyear][$weekNo] < $limit)
                                return array('status'=>true,'clientMembLimit'=>$clientMembEvent); 
                            else
                                return array('status'=>false,'message'=>'limit_exceeded','cmeId'=>$clientMembEvent->id); 
                        }
                    }
                }
                elseif($limit_type == 'classes_monthly'){
                    $monthDate = $carbonDate->copy();
                    $monthNo = $monthDate->month;
                    if($clientMembEvent->cme_classes_monthly != ''){
                        $monthlyClasses = json_decode($clientMembEvent->cme_classes_monthly, 1);
                        if(array_key_exists($eventyear,$monthlyClasses) && array_key_exists($monthNo,$monthlyClasses[$eventyear])){
                            if($monthlyClasses[$eventyear][$monthNo] < $limit)
                                return array('status'=>true,'clientMembLimit'=>$clientMembEvent); 
                            else
                                return array('status'=>false,'message'=>'limit_exceeded','cmeId'=>$clientMembEvent->id); 
                        }  
                    }
                }
            }
        }
        
        return array('status'=>true,'clientMembLimit'=>$clientMembEvent);
    }


    /**
     * update client membership according to event booked
     * @param Array $clientIds[]
     * @param Array $eventData ['type','action','event_date', 'eventId']
     * @return true/false
     */
    protected function updateClientMembershipLimit($clientId, $dates, $eventData){
        $isError = true;
        $eventDates = $this->getEventDates($dates);
        $updatedData = array();
        $insertedData = array();
        $action = $eventData['action'];

        if(!array_key_exists('limit_type', $eventData)){
            $clientMember = ClientMember::where('cm_client_id', $clientId)->select('cm_client_id','cm_class_limit_type','cm_status', 'cm_services_limit', 'cm_classes')->orderBy('id','desc')->first();

            if(count($clientMember)){
                if($clientMember['cm_status'] == 'Active'){
                    if($eventData['type'] == 'class'){
                        $eventInMembership = json_decode($clientMember['cm_classes'], 1);
                        if(count($eventInMembership) /*&& array_key_exists($eventData['eventId'], $eventInMembership)*/){
                            $limitType = $clientMember['cm_class_limit_type'];
                            $isError = false;
                        }
                    }
                    elseif($eventData['type'] == 'service'){
                        $serviceId = $eventData['eventId'];
                        $eventInMembership = json_decode($clientMember['cm_services_limit'], 1);
                        if(count($eventInMembership) && array_key_exists($serviceId, $eventInMembership) && array_key_exists('limit_type', $eventInMembership[$serviceId])){
                            $limitType = $eventInMembership[$serviceId]['limit_type'];
                            $isError = false;
                        }
                    }
                }
            }
        }
        else{
            $limitType = $eventData['limit_type'];
            $isError = false;
        }
        
        if(!$isError){
            $clientMemberLimit = ClientMemberLimit::where('cme_client_id', $clientId)->first();
            foreach ($eventDates as  $eventDate) {
                if((!count($clientMemberLimit)) && $action == 'add'){
                    $weeks = array();
                    $months = array();
                    $fortnight = array();

                    $clientMemberLimit = new ClientMemberLimit;
                    $clientMemberLimit->cme_client_id = $clientId;

                    if($eventData['type'] == 'service'){ // new record for only service
                        $serviceId = $eventData['eventId']; //service id
                        if($limitType == 'every_week'){
                            $weekDateData = $this->getDateDetails($eventDate, 'weekly');
                            $weekNo = $weekDateData['index'];
                            $eventyear = $weekDateData['year'];

                            $weeks[$serviceId][$eventyear][$weekNo] = 1;
                            $clientMemberLimit->cme_services_weekly = json_encode($weeks); 
                        }
                        elseif($limitType == 'every_month'){
                            $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                            $monthNo = $monthDateData['index'];
                            $eventyear = $monthDateData['year'];

                            $months[$serviceId][$eventyear][$monthNo] = 1;
                            $clientMemberLimit->cme_services_monthly = json_encode($months); 
                        }
                        elseif($limitType == 'every_fortnight'){
                            $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                            $fortnightNo = $fortnightDate['index'];
                            $eventyear = $fortnightDate['year'];

                            $fortnight[$serviceId][$eventyear][$fortnightNo] = 1;
                            $clientMemberLimit->cme_services_fortnight = json_encode($fortnight); 
                        }
                    }
                    elseif($eventData['type'] == 'class'){ // new record for only class
                        if($limitType == 'every_week'){
                            $weekDateData = $this->getDateDetails($eventDate, 'weekly');
                            $weekNo = $weekDateData['index'];
                            $eventyear = $weekDateData['year'];

                            $weeks[$eventyear][$weekNo] = 1;
                            $clientMemberLimit->cme_classes_weekly = json_encode($weeks);
                        }
                        elseif($limitType == 'every_month'){
                            $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                            $monthNo = $monthDateData['index'];
                            $eventyear = $monthDateData['year'];

                            $months[$eventyear][$monthNo] = 1;
                            $clientMemberLimit->cme_classes_monthly = json_encode($months);
                        }
                    }
                    //$clientMemberLimit->save();
                }
                else{
                    $existData = array();
                    $newData = array();
                    if($eventData['type'] == 'service'){ // for only service
                        $serviceId = $eventData['eventId']; // service id
                        if($limitType == 'every_week'){
                            $weekDateData = $this->getDateDetails($eventDate, 'weekly');
                            $weekNo = $weekDateData['index'];
                            $eventyear = $weekDateData['year'];
                            if($clientMemberLimit->cme_services_weekly != ''){
                                $existData = json_decode($clientMemberLimit->cme_services_weekly, 1);
                                if(array_key_exists($serviceId, $existData) && array_key_exists($eventyear, $existData[$serviceId]) && array_key_exists($weekNo, $existData[$serviceId][$eventyear])){
                                    if($action == 'add')
                                        $existData[$serviceId][$eventyear][$weekNo] = $existData[$serviceId][$eventyear][$weekNo] + 1;
                                    elseif($existData[$serviceId][$eventyear][$weekNo] > 0)
                                        $existData[$serviceId][$eventyear][$weekNo] = $existData[$serviceId][$eventyear][$weekNo] - 1;
                                }
                                elseif($action == 'add'){
                                    $existData[$serviceId][$eventyear][$weekNo] =  1;
                                }
                                $clientMemberLimit->cme_services_weekly = json_encode($existData);
                            }
                            elseif($action == 'add'){
                                $newData[$serviceId][$eventyear][$weekNo] = 1;
                                $clientMemberLimit->cme_services_weekly = json_encode($newData);
                            }
                        }
                        elseif($limitType == 'every_month'){
                            $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                            $monthNo = $monthDateData['index'];
                            $eventyear = $monthDateData['year'];

                            if($clientMemberLimit->cme_services_monthly != ''){
                                $existData = json_decode($clientMemberLimit->cme_services_monthly, 1);
                                if(array_key_exists($serviceId, $existData) && array_key_exists($eventyear, $existData[$serviceId]) && array_key_exists($monthNo, $existData[$serviceId][$eventyear])){
                                    if($action == 'add')
                                        $existData[$serviceId][$eventyear][$monthNo] = $existData[$serviceId][$eventyear][$monthNo] + 1;
                                    elseif($existData[$serviceId][$eventyear][$monthNo] > 0)
                                        $existData[$serviceId][$eventyear][$monthNo] = $existData[$serviceId][$eventyear][$monthNo] - 1; 
                                }
                                elseif($action == 'add'){
                                    $existData[$serviceId][$eventyear][$monthNo] =  1;
                                }
                                $clientMemberLimit->cme_services_monthly = json_encode($existData);
                            }
                            elseif($action == 'add'){
                                $newData[$serviceId][$eventyear][$monthNo] = 1;
                                $clientMemberLimit->cme_services_monthly = json_encode($newData);
                            }
                        }
                        elseif($limitType == 'every_fortnight'){
                            $fortnightDate = $this->getDateDetails($eventDate, 'fortnightly');
                            $fortnightNo = $fortnightDate['index'];
                            $eventyear = $fortnightDate['year'];

                            if($clientMemberLimit->cme_services_fortnight != ''){
                                $existData = json_decode($clientMemberLimit->cme_services_fortnight, 1);
                                if(array_key_exists($serviceId, $existData) && array_key_exists($eventyear, $existData[$serviceId]) && array_key_exists($fortnightNo, $existData[$serviceId][$eventyear])){
                                    if($action == 'add')
                                        $existData[$serviceId][$eventyear][$fortnightNo] = $existData[$serviceId][$eventyear][$fortnightNo] + 1;
                                    elseif($existData[$serviceId][$eventyear][$fortnightNo] > 0)
                                        $existData[$serviceId][$eventyear][$fortnightNo] = $existData[$serviceId][$eventyear][$fortnightNo] - 1; 
                                }
                                elseif($action == 'add'){
                                    $existData[$serviceId][$eventyear][$fortnightNo] =  1;
                                }
                                $clientMemberLimit->cme_services_fortnight = json_encode($existData);
                            }
                            elseif($action == 'add'){
                                $newData[$serviceId][$eventyear][$fortnightNo] = 1;
                                $clientMemberLimit->cme_services_fortnight = json_encode($newData);
                            }
                        }
                    }
                    elseif($eventData['type'] == 'class'){ // for only class
                        if($limitType == 'every_week'){
                            $weekDateData = $this->getDateDetails($eventDate, 'weekly');
                            $weekNo = $weekDateData['index'];
                            $eventyear = $weekDateData['year'];

                            if($clientMemberLimit->cme_classes_weekly != ''){
                                $existData = json_decode($clientMemberLimit->cme_classes_weekly, 1);
                                if(array_key_exists($eventyear,$existData) && array_key_exists($weekNo,$existData[$eventyear])){
                                    if($action == 'add')
                                        $existData[$eventyear][$weekNo] = $existData[$eventyear][$weekNo] + 1;
                                    elseif($existData[$eventyear][$weekNo] > 0)
                                        $existData[$eventyear][$weekNo] = $existData[$eventyear][$weekNo] - 1;
                                }
                                elseif($action == 'add'){
                                    $existData[$eventyear][$weekNo] = 1;
                                }
                                $clientMemberLimit->cme_classes_weekly= json_encode($existData);
                            }
                            elseif($action == 'add'){
                                $newData[$eventyear][$weekNo] = 1;
                                $clientMemberLimit->cme_classes_weekly= json_encode($newData);
                            }
                        }
                        elseif($limitType == 'every_month'){
                            $monthDateData = $this->getDateDetails($eventDate, 'monthly');
                            $monthNo = $monthDateData['index'];
                            $eventyear = $monthDateData['year'];

                            if($clientMemberLimit->cme_classes_monthly != ''){
                                $existData = json_decode($clientMemberLimit->cme_classes_monthly, 1);
                                if(array_key_exists($eventyear, $existData) && array_key_exists($monthNo, $existData[$eventyear])){
                                    if($action == 'add')
                                        $existData[$eventyear][$monthNo] = $existData[$eventyear][$monthNo] + 1;
                                    elseif($existData[$eventyear][$monthNo] > 0)
                                        $existData[$eventyear][$monthNo] = $existData[$eventyear][$monthNo] - 1;
                                }
                                elseif($action == 'add'){
                                    $existData[$eventyear][$monthNo] = 1; 
                                }
                                $clientMemberLimit->cme_classes_monthly = json_encode($existData);
                            }
                            elseif($action == 'add'){
                                $newData[$eventyear][$monthNo] = 1;
                                $clientMemberLimit->cme_classes_monthly = json_encode($newData);
                            }
                        }
                    }
                }
            }
            
            if(count($clientMemberLimit))
                $clientMemberLimit->save();
        }
    }


    /**
     * Date for indexing 
     *
     * @param Carbon Date $dates
     * @param String $limitType
     * @return Array $dateDetails['year','month/week/fortnightly']
     */
    protected function getDateDetails($date, $limitType){
        $carbonDate = $date->copy();
        $dateDetails = array();
        $dateDetails['year'] = $carbonDate->year; 
        if($limitType == 'weekly'){
            $weekDate = $carbonDate->copy();
            $dateDetails['index'] = $weekDate->weekOfYear;
        }
        elseif($limitType == 'monthly'){
            $monthDate = $carbonDate->copy();
            $dateDetails['index'] = $monthDate->month; 
        }
        elseif($limitType == 'fortnightly'){
            $fortnightDate = $carbonDate->copy(); 
            $fortnightMonthNo = $fortnightDate->weekOfYear;
            if($fortnightMonthNo % 2 == 0)
                 $dateDetails['index'] = $fortnightMonthNo;
            else
                 $dateDetails['index'] = $fortnightMonthNo+1; 
        }
        return $dateDetails;
    }

    
    /**
     * Get monthno_weekno for fortnight option
     * @param Date
     * @return string monthno_weekno
     */
    protected function getEvenWeekNumb($date){
        $fortnightDate = $date->copy();
        $fortnightMonthNo = $fortnightDate->weekOfYear;
        if($fortnightMonthNo % 2 == 0){
            return $fortnightMonthNo;
        }
        else{
            return $fortnightMonthNo+1;
        }

    }

    protected function calcRepeatsDateAfterCase($data){
        $dates = [];
        $eventDate = new Carbon($data['eventDate']);
        for($i=0; $i<$data['eventRepeatEndAfterOccur'];){
            $param = ['eventDate' => $eventDate, 'eventRepeat' => $data['eventRepeat'], 'repeatIntv' => $data['eventRepeatInterval']];
            
            if($data['eventRepeat'] == 'Weekly')
                $param['eventRepeatWeekdays'] = $data['eventRepeatWeekdays'];
            
            $repeatDate = $this->calcRepeatsDate($param);
            if($repeatDate){
                $dates[] = $repeatDate;
                $i++;
            }
        }

        $this->getRepeatsDate($dates);

        return $dates;
    }

    protected function storeEventrepeatData($request, $event, $recreate = false/*, $isParent = true*/){
        //dd($request->all());
        /*if($request->eventRepeat){
            $ifContinue = true;

            if($request->eventRepeat == 'Weekly' && !$request->has('eventRepeatWeekdays'))
                $ifContinue = false;

            if($ifContinue){*/
        if(!$request->eventRepeat || ($request->eventRepeat == 'Weekly' && !$request->has('eventRepeatWeekdays')))
            return false;

        /*if($request->eventRepeat){
            $ifContinue = true;
    
            if($request->eventRepeat == 'Weekly' && !$request->has('eventRepeatWeekdays'))
                $ifContinue = false;

            if($ifContinue){*/
                $repeat = new StaffEventRepeat();
                $dates = [];
                $repeat->ser_repeat = $request->eventRepeat;

                if($request->eventRepeat == 'Daily' || $request->eventRepeat == 'Weekly' || $request->eventRepeat == 'Monthly'){
                    $repeat->ser_repeat_interval = $request->eventRepeatInterval;
                    $repeat->ser_repeat_end = $request->eventRepeatEnd;

                    if($request->has('eventRepeatWeekdays'))
                        $repeat->ser_repeat_week_days = json_encode($request->eventRepeatWeekdays);

                    $eventTable = $event->getTable();
                    if($eventTable == 'staff_event_classes')
                        $eventDate = $event->sec_date;
                    else if($eventTable == 'staff_events')
                        $eventDate = $event->se_date;
                    else if($eventTable == 'staff_event_single_services')
                        $eventDate = $event->sess_date;

                    if($request->eventRepeatEnd == 'After'){
                        $repeat->ser_repeat_end_after_occur = $request->eventRepeatEndAfterOccur;

                        /*$eventDate = new Carbon($eventDate);
                        for($i=0; $i<$request->eventRepeatEndAfterOccur;){
                            $param = ['eventDate' => $eventDate, 'eventRepeat' => $request->eventRepeat, 'repeatIntv' => $request->eventRepeatInterval];
                            
                            if($request->eventRepeat == 'Weekly')
                                $param['eventRepeatWeekdays'] = $request->eventRepeatWeekdays;
                            
                            $repeatDate = $this->calcRepeatsDate($param);
                            if($repeatDate){
                                $dates[] = $repeatDate;
                                $i++;
                            }
                        }

                        $this->getRepeatsDate($dates);*/
                        $dates = $this->calcRepeatsDateAfterCase(['eventDate'=>$eventDate, 'eventRepeatEndAfterOccur'=>$request->eventRepeatEndAfterOccur, 'eventRepeat'=>$request->eventRepeat, 'eventRepeatInterval'=>$request->eventRepeatInterval, 'eventRepeatWeekdays'=>$request->eventRepeatWeekdays]);
                    }
                    else if($request->eventRepeatEnd == 'On'){
                        $repeat->ser_repeat_end_after_occur = 0;
                        $repeat->ser_repeat_end_on_date = $request->eventRepeatEndOnDate;

                        /*$eventDate = new Carbon($eventDate);
                        $eventRepeatEndOnDate = new Carbon($request->eventRepeatEndOnDate);
                        while($eventDate->lte($eventRepeatEndOnDate)){
                            $param = ['eventDate' => $eventDate, 'eventRepeat' => $request->eventRepeat, 'repeatIntv' => $request->eventRepeatInterval];
                            
                            if($request->eventRepeat == 'Weekly')
                                $param['eventRepeatWeekdays'] = $request->eventRepeatWeekdays;
                            
                            $dates[] = $this->calcRepeatsDate($param);
                        }

                        $this->getRepeatsDate($dates);*/
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate'=>$eventDate, 'eventRepeatEndOnDate'=>$request->eventRepeatEndOnDate, 'eventRepeat'=>$request->eventRepeat, 'eventRepeatInterval'=>$request->eventRepeatInterval, 'eventRepeatWeekdays'=>$request->eventRepeatWeekdays]);
                    }
                    else if($request->eventRepeatEnd == 'Never'){
                        $repeat->ser_repeat_end_after_occur = 0;
                        /*$eventDate = new Carbon($eventDate);
                        $eventRepeatEndOnDate = new Carbon($request->calendEndDate);
                        while($eventDate->lte($eventRepeatEndOnDate)){
                            $param = ['eventDate' => $eventDate, 'eventRepeat' => $request->eventRepeat, 'repeatIntv' => $request->eventRepeatInterval];
                            
                            if($request->eventRepeat == 'Weekly')
                                $param['eventRepeatWeekdays'] = $request->eventRepeatWeekdays;
                            
                            $dates[] = $this->calcRepeatsDate($param);
                        }

                        $this->getRepeatsDate($dates);*/
                        $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate'=>$eventDate, 'eventRepeatEndOnDate'=>$request->calendEndDate, 'eventRepeat'=>$request->eventRepeat, 'eventRepeatInterval'=>$request->eventRepeatInterval, 'eventRepeatWeekdays'=>$request->eventRepeatWeekdays]);
                    }
                }
                //dd($repeat);
                $event->repeat()->save($repeat);

                /*if(count($dates)){
                    $event->se_is_repeating = 1;
                    $event->save();
                }*/
                //dd($dates);
                //$this->generateEventRepeat($dates, $event, $repeat, $recreate);
               
               if (($key = array_search($request->max_date, $dates)) !== false) {
                    $dates=array_slice($dates,0,$key+1,true);     
                }

                //dd($dates);

                $this->generateEventRepeat($dates, $event, $repeat, ['recreate' => $recreate, 'oldDate' => $request->oldDate]);
            //}
        //}
    }

    protected function calcRepeatsDate($eventData){
        $date = '';

        if($eventData['eventRepeat'] == 'Weekly'){
            $day = $eventData['eventDate']->format('D');
            if($eventData['eventRepeatWeekdays'] != null && in_array($day, $eventData['eventRepeatWeekdays']))
                $date = $eventData['eventDate']->copy()->format('Y-m-d');
        }
        else
            $date = $eventData['eventDate']->copy()->format('Y-m-d');

        if($eventData['eventRepeat'] == 'Daily')
            $eventData['eventDate']->addDays($eventData['repeatIntv']);
        else if($eventData['eventRepeat'] == 'Monthly')
            $eventData['eventDate']->addMonths($eventData['repeatIntv']);
        else if($eventData['eventRepeat'] == 'Weekly'){
            $weekend = new Carbon($eventData['eventDate']);
            $weekend = $weekend->endOfWeek()->startOfDay();

            if($weekend->eq($eventData['eventDate']))
               $eventData['eventDate']->addWeeks($eventData['repeatIntv'])->startOfWeek();
            else
                $eventData['eventDate']->addDay();
        }
        return $date;
    }

    protected function getRepeatsDate(&$dates){
        if(count($dates))
            $dates = array_filter($dates);
    }

    protected function delAssociatedEvents($data){
        if($data['eventType'] == 'class'){
            $model = 'App\StaffEventClass';
            $idCol = 'sec_id';
            $delFlagcol = 'sec_deleted_in_chain';
        }
        else if($data['eventType'] == 'appointment'){
            $model = 'App\StaffEvent';
            $idCol = 'se_id';
            $delFlagcol = 'se_deleted_in_chain';
        }

        if($data['parentEventId'])
            $events = $model::SiblingEvents(['parentEventId' => $data['parentEventId'], 'eventDate' => $data['eventDate'], 'eventId' => $data['eventId']])->get();
        else
            $events = $model::ChildEvents($data['eventId'])->get();

        if($events->count()){
            $ids = [];
            foreach($events as $event){
                $ids[] = $event->$idCol;
                $event->delete();
            }

            if(count($ids))
                $model::onlyTrashed()->whereIn($idCol, $ids)->update(array($delFlagcol => 1));
        }
    }

    protected function delReccurData($event){
        $repeat = $event->repeat->first();
        if($repeat)
            $repeat->forceDelete();
    }

    protected function resetEventReccur($event){
        $this->delReccurData($event);
        $this->unsetEventReccurence($event);
    }

    protected function resetEventReccurClass($event){
        $this->delReccurData($event);
        $this->unsetEventReccurence($event);
    }


    protected function ifEventDetailsUpdated($oldModel, $newModel, $nonComparingFields = []){
        $nonComparingFields[] = 'created_at';
        $nonComparingFields[] = 'updated_at';
        $nonComparingFields[] = 'deleted_at';
        $nonComparingFields[] = 'repeat';

        $oldModel = array_except($oldModel->toArray(), $nonComparingFields);
        $newModel = array_except($newModel->toArray(), $nonComparingFields);

        return count(array_diff($oldModel, $newModel));        
    }

    protected function ifEventRepeatUpdated($oldModel, $newModel){
        //dd($oldModel);
       //die;
        if($oldModel->ser_repeat != $newModel->eventRepeat)
            return true;

        /*if($oldModel->ser_repeat != $newModel->eventRepeat)
            return true;*/
        
        if($newModel->eventRepeat == 'Daily' || $newModel->eventRepeat == 'Weekly' || $newModel->eventRepeat == 'Monthly'){
            if($oldModel->ser_repeat_interval != $newModel->eventRepeatInterval || $oldModel->ser_repeat_end != $newModel->eventRepeatEnd)
                return true;

            if($newModel->eventRepeat == 'Weekly'){
                $newWeekdays = $newModel->eventRepeatWeekdays;
                $oldWeekdays = json_decode($oldModel->ser_repeat_week_days);

                if(is_array($newWeekdays) && is_array($oldWeekdays)) {
                    if(count($newWeekdays) > count($oldWeekdays)){
                        if(count(array_diff($newWeekdays, $oldWeekdays)))
                            return true;
                    } 
                    else{
                        if(count(array_diff($oldWeekdays, $newWeekdays)))
                            return true;
                    }
                }
                else if((!is_array($newWeekdays) && is_array($oldWeekdays)) || (is_array($newWeekdays) && !is_array($oldWeekdays)))
                    return true;
            }

            if($newModel->eventRepeatEnd == 'After' && $oldModel->ser_repeat_end_after_occur != $newModel->eventRepeatEndAfterOccur)
                return true;
            
            if($newModel->eventRepeatEnd == 'On' && $oldModel->ser_repeat_end_on_date != $newModel->eventRepeatEndOnDate)
                return true;   
        }
        return false;
    }


    protected function calcEventDate($request, $currDate){
        $eventNewDate = $currDate;
         
        if($request->has('eventRepeatWeekdays')){
            $eventDate = Carbon::parse($currDate);
            $eventDay = $eventDate->format('D');

            if(!in_array($eventDay, $request->eventRepeatWeekdays)){
                $eventRepeatWeekdays = array_intersect($this->weekDaysArr, $request->eventRepeatWeekdays);
                $eventDayIdx = array_search($eventDay, $this->weekDaysArr);
                
                $nearestDayIdx = 0;
                for($i=$eventDayIdx+1; $i<count($this->weekDaysArr); $i++){
                    if(array_key_exists($i, $eventRepeatWeekdays)){
                        $nearestDayIdx = $i;
                        break;
                    }
                }
                if(!$nearestDayIdx){
                    reset($eventRepeatWeekdays);
                    $nearestDayIdx = key($eventRepeatWeekdays);
                }

                $daysToAdd = $nearestDayIdx - $eventDayIdx;
                if($daysToAdd < 0)
                    $daysToAdd = $daysToAdd + count($this->weekDaysArr);

                $eventNewDate = $eventDate->addDays($daysToAdd)->format('Y-m-d');
            }
        }

        return $eventNewDate;
    }

    protected function calcEndTime($data){
        $timestamp = strtotime($data['startTime']) + ($data['duration']*60);
        return date('H:i:s', $timestamp);
    }

    /*protected function prepareDataForClashingEvents($data, $eventType = ''){
        $preparedData = ['appointmentData' => $data, 'classData' => $data, 'busyTimeData' => $data];

        if(array_key_exists('eventId', $data)){
            if($eventType != 'appointment')
                $preparedData['appointmentData'] = ['date' => $data['date'], 'startTime' => $data['startTime'], 'endTime' => $data['endTime']];

            if($eventType != 'class')
                $preparedData['classData'] = ['date' => $data['date'], 'startTime' => $data['startTime'], 'endTime' => $data['endTime']];

            if($eventType != 'busyTime')
                $preparedData['busyTimeData'] = ['date' => $data['date'], 'startTime' => $data['startTime'], 'endTime' => $data['endTime']];
        }

        return $preparedData;
    }*/

    protected function prepareDataForClashingEvents($data, $eventType = ''){
        $preparedData = ['appointmentData' => $data, 'classData' => $data, 'busyTimeData' => $data];

        if(array_key_exists('eventId', $data)){
            /*if($eventType != 'appointment')
                $preparedData['appointmentData'] = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];

            if($eventType != 'class')
                $preparedData['classData'] = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];

            if($eventType != 'busyTime')
                $preparedData['busyTimeData'] = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];*/
            if($eventType == 'appointment'){
                unset($preparedData['classData']['eventId']);
                unset($preparedData['busyTimeData']['eventId']);
            }
            else if($eventType == 'class'){
                unset($preparedData['appointment']['eventId']);
                unset($preparedData['busyTimeData']['eventId']);
            }
            else if($eventType == 'busyTime'){
                unset($preparedData['classData']['eventId']);
                unset($preparedData['appointment']['eventId']);
            }
        }

        return $preparedData;
    }


    protected function isAreaBusy($data, $eventType = ''){
        $preparedData = $this->prepareDataForClashingEvents($data, $eventType);

        if(StaffEventBusy::where('seb_area_id', $data['areaId'])
                         ->clashingEvents($preparedData['busyTimeData'])
                         ->count()

            || StaffEvent::where('se_area_id', $data['areaId'])
                         ->clashingEvents($preparedData['appointmentData'])
                         ->count()

            || StaffEventClass::where('sec_area_id', $data['areaId'])
                              ->clashingEvents($preparedData['classData'])
                              ->count()){
            return true;
        }
    }

    protected function isStaffBusy($data, $eventType = ''){
        $startTime = $this->datetimeToTime($data['startDatetime']);
        $endTime = $this->datetimeToTime($data['endDatetime']);
        if(DB::table('hours')->where('hr_entity_type', 'staff')
                             ->where('hr_entity_id', $data['staffId'])
                             ->where('hr_day', $data['day'])
                             ->where('hr_start_time', '<=', $startTime)
                             ->where('hr_end_time', '>=', $endTime)
                             ->count()){

            $preparedData = $this->prepareDataForClashingEvents($data, $eventType);

            if(StaffEventBusy::where('seb_staff_id', $data['staffId'])
                             ->clashingEvents($preparedData['busyTimeData'])
                             ->count()

                || StaffEvent::where('se_staff_id', $data['staffId'])
                             ->clashingEvents($preparedData['appointmentData'])
                             ->count()

                || StaffEventClass::where('sec_staff_id', $data['staffId'])
                                  ->clashingEvents($preparedData['classData'])
                                  ->count()){
                return true;
            }

            return false;
        }
        
        return true;
    }

    protected function prepareEventRecurDdOpt(){
        $eventRepeatIntervalOpt = ['' => '-- Select --'];
        for($intv = 1; $intv <= 31; $intv++)
            $eventRepeatIntervalOpt[$intv] = $intv;

        return $eventRepeatIntervalOpt;
    }

    protected function isAreaLinkedToStaff($data){
        return DB::table('area_staffs')->where('as_la_id', $data['areaId'])->where('as_staff_id', $data['staffId'])->count();
    }

    protected function eventsListForOverview($entity){
        /* start: Insert never ending events */
        $now = new Carbon();
        $monthEndDate = $now->endOfMonth()->toDateString();
        $eventRepeatRequest = new Request;
        $eventRepeatRequest['insertRepeatUpto'] = $monthEndDate;
        //$this->neverEndAppointmentRepeats($eventRepeatRequest);
        //$this->neverEndSingleServiceRepeats($eventRepeatRequest);
        //$this->neverEndClassRepeats($eventRepeatRequest);
        /* end: Insert never ending events */

        $modalLocsAreas = $eventRepeatIntervalOpt = [];
        $pastEvents = $oldestFutureEventInMembership = $latestPastEventInMembership =  $latestPastEvent = $futureEvents = $oldestFutureEvent = collect();
       // if(isUserType(['Staff']) || Auth::user()->hasPermission(Auth::user(), 'list-staff-event-appointment')){
            /* start: Fetching loc-areas */
            $data = $this->locAreasForEvents();
            $modalLocsAreas = $data['locsAreas'];
            /* end: Fetching loc-areas */

            /* start: Preparing recurrence dropdown options */
            $eventRepeatIntervalOpt = $this->prepareEventRecurDdOpt();
            /* end: Preparing recurrence dropdown options */
            
            
            $clientMember = 0;
        //            if(isUserType(['Admin'])){
                $clientMember = $entity->membership($entity->id);
                if($clientMember)
                    $clientMember = $clientMember->id;
        //            }

            /* start: Fetching past events */
            $pastAppointments = $entity->pastAppointments;
            $pastClasses = $entity->pastClasses;
            if($pastAppointments->count() && $pastClasses->count()){
                $pastEvents = $pastAppointments->merge($pastClasses);
                $pastEvents = $pastEvents->sort(function ($firstEvent, $secondEvent){
                    if($firstEvent->eventDate === $secondEvent->eventDate){
                        if($firstEvent->eventTime === $secondEvent->eventTime)
                            return 0;
                    
                        return $firstEvent->eventTime < $secondEvent->eventTime ? 1 : -1;
                    } 
                    return $firstEvent->eventDate < $secondEvent->eventDate ? 1 : -1;
                });
            }
            else if($pastAppointments->count())
                $pastEvents = $pastAppointments;
            else if($pastClasses->count())
                $pastEvents = $pastClasses;
            /* end: Fetching past events */

            /* start: Fetching recent past event */
            if($pastEvents->count()){
                $latestPastEvent = $pastEvents->filter(function($pastEvent){
                    $model = class_basename($pastEvent);
                    if(isUserType(['Staff']))
                        /*return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null) || ($model == 'StaffEvent' && $pastEvent->deleted_at == null && $pastEvent->se_booking_status == 'Confirmed');
                    else    
                        return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null && $pastEvent->pivot->secc_client_status != 'Waiting' && (!$pastEvent->pivot->secc_if_make_up || $pastEvent->pivot->secc_if_make_up && $pastEvent->pivot->secc_client_attendance == 'Did not show')) || ($model == 'StaffEvent' && $pastEvent->deleted_at == null && $pastEvent->se_booking_status == 'Confirmed');*/
                        return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null) || ($model == 'StaffEventSingleService' && $pastEvent->deleted_at == null && $pastEvent->sess_booking_status == 'Confirmed');
                    else    
                        return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null && $pastEvent->pivot->secc_client_status != 'Waiting' && (!$pastEvent->pivot->secc_if_make_up || $pastEvent->pivot->secc_if_make_up && $pastEvent->pivot->secc_client_attendance == 'Did not show')) || ($model == 'StaffEventSingleService' && $pastEvent->deleted_at == null && $pastEvent->sess_booking_status == 'Confirmed');
                })->first();
                
                if($clientMember){
                    $latestPastEventInMembership = $pastEvents->filter(function($pastEvent) use($clientMember){
                        $model = class_basename($pastEvent);
                        return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null && $pastEvent->pivot->secc_cmid == $clientMember && $pastEvent->pivot->secc_client_status != 'Waiting' && !$pastEvent->pivot->secc_if_make_up_created && (!$pastEvent->pivot->secc_if_make_up || $pastEvent->pivot->secc_client_attendance == 'Did not show')) || ($model == 'StaffEventSingleService' && $pastEvent->deleted_at == null && $pastEvent->sess_cmid == $clientMember && $pastEvent->sess_booking_status == 'Confirmed');
                    })->first();
                }
            }
            /* end: Fetching recent past event */

            /* start: Fetching fututre events */
            $futureAppointments = $entity->futureAppointments;
            $futureClasses = $entity->futureClasses;
            if($futureAppointments->count() && $futureClasses->count()){
                $futureEvents = $futureAppointments->merge($futureClasses);
                $futureEvents = $futureEvents->sort(function ($firstEvent, $secondEvent){
                    if($firstEvent->eventDate === $secondEvent->eventDate){
                        if($firstEvent->eventTime === $secondEvent->eventTime)
                            return 0;
                    
                        return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
                    } 
                    return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
                });
            }
            else if($futureAppointments->count())
                $futureEvents = $futureAppointments;
            else if($futureClasses->count())
                $futureEvents = $futureClasses;
            /* end: Fetching fututre events */

            /* start: Fetching latest future event */
            if($futureEvents->count()){
                $oldestFutureEvent = $futureEvents->filter(function($futureEvent){
                    $model = class_basename($futureEvent);
                    if(isUserType(['Staff']))
                        /*return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null) || ($model == 'StaffEvent' && $futureEvent->deleted_at == null && $futureEvent->se_booking_status == 'Confirmed' && $futureEvent->se_booking_status_confirm == 'Not started');
                    else
                        return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->secc_client_status != 'Waiting' && !$futureEvent->pivot->secc_if_make_up) || ($model == 'StaffEvent' && $futureEvent->deleted_at == null && $futureEvent->se_booking_status == 'Confirmed' && $futureEvent->se_booking_status_confirm == 'Not started');*/
                        return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null) || (/*$model == 'StaffEvent' && $futureEvent->deleted_at == null && $futureEvent->se_booking_status == 'Confirmed' && $futureEvent->se_booking_status_confirm == 'Not started'*/$model == 'StaffEventSingleService' && $futureEvent->deleted_at == null && $futureEvent->sess_booking_status == 'Confirmed' && $futureEvent->sess_client_attendance == 'Booked');
                    else
                        return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->secc_client_status != 'Waiting' && !$futureEvent->pivot->secc_if_make_up) || (/*$model == 'StaffEvent' && $futureEvent->deleted_at == null && $futureEvent->se_booking_status == 'Confirmed' && $futureEvent->se_booking_status_confirm == 'Not started'*/$model == 'StaffEventSingleService' && $futureEvent->deleted_at == null && $futureEvent->sess_booking_status == 'Confirmed' && $futureEvent->sess_client_attendance == 'Booked');
                })->first();
                
                if($clientMember){
                    $oldestFutureEventInMembership = $futureEvents->filter(function($futureEvent) use($clientMember){
                        $model = class_basename($futureEvent);
                        return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->secc_cmid == $clientMember /*&& $futureEvent->pivot->secc_client_status != 'Waiting'*/ && !$futureEvent->pivot->secc_if_make_up) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null && $futureEvent->sess_cmid == $clientMember && $futureEvent->sess_booking_status == 'Confirmed' && $futureEvent->sess_client_attendance == 'Booked');
                    })->first();
                }
            }
            /* end: Fetching latest future event */
       // }

        return compact('oldestFutureEventInMembership','latestPastEventInMembership','pastEvents', 'latestPastEvent', 'futureEvents', 'oldestFutureEvent', 'modalLocsAreas', 'eventRepeatIntervalOpt');
    }

    protected function calcStartAndEndDatetime($param){
        $data = ['startTime' => $param['startTime'], 'startDate' => $param['startDate']];
        
        $result = [];
        $result['startDatetime'] = $this->calcStartDatetime(['startTime' => $data['startTime'], 'startDate' => $data['startDate']]);

        if(array_key_exists("startTimeForEnd", $param))
            $data['startTime'] = $param['startTimeForEnd'];
        $data['duration'] = $param['duration'];
        $result['endDatetime'] = $this->calcEndDatetime($data);
        $result['endDate'] = date("Y-m-d", strtotime($result['endDatetime']));

        return $result;
    }



    protected function calcStartDatetime($data){
        $timestamp = strtotime($data['startDate'].' '.$data['startTime']);
        return date('Y-m-d H:i:s', $timestamp);
    }


    protected function calcEndDatetime($data){
        $timestamp = strtotime($data['startDate'].' '.$data['startTime']) + ($data['duration']*60);
        return date('Y-m-d H:i:s', $timestamp);
    }

    protected function datetimeToTime($datetime){
        $timestamp = strtotime($datetime);
        return date('H:i:s', $timestamp);
    }



    protected function linkResources($request){
        $formData = $request->all();
        ksort($formData);
        $newResources = $newItem = [];
        foreach($formData as $key => $value){
            if(strpos($key, 'newResources') !== false)
                $newResources[] = $value;
            else if(strpos($key, 'newItem') !== false)
                $newItem[] = $value;  
        }

        if($request->kase == "edit")
            StaffEventResource::where('serc_event_id', $request->eventId)->where('serc_event_type', $request->eventType)->forcedelete();

        if(count($newResources) && count($newItem)){
            $records =[];
            for($i=0; $i<count($newItem); $i++){
                $timestamp = createTimestamp();
                $records[] = ['serc_event_id'=>$request->eventId, 'serc_event_type'=>$request->eventType, 'serc_res_id'=>$newResources[$i], 'serc_item_quantity'=>$newItem[$i], 'created_at'=>$timestamp, 'updated_at'=>$timestamp];  
            }
            if(count($records))
                StaffEventResource::insert($records);
        }
    }

    protected function calcDiffBtwDate($firstDate, $secondDate, $type){
        $firstDateCarb = new Carbon($firstDate);
        $secondDateCarb = new Carbon($secondDate);

        if($type == 'day') //If membership length is in days
            return $firstDateCarb->diffInDays($secondDateCarb);
        else if($type == 'week') //If membership length is in weeks
            return $firstDateCarb->diffInWeeks($secondDateCarb);
        else if($type == 'month') //If membership length is in months
            return $firstDateCarb->diffInMonths($secondDateCarb);
        else if($type == 'year') //If membership length is in years
            return $firstDateCarb->diffInYears($secondDateCarb);
        return 0;
    }


    protected function calcEndDate($startDate, $validType, $validLen){
        $startDateCarb = new Carbon($startDate);
        switch($validType){
            case 'day':
                $startDateCarb->addDays($validLen);
                break;
            case 'week':
                $startDateCarb->addWeeks($validLen);
                break;
            case 'month':
                $startDateCarb->addMonths($validLen);
                break;
            case 'year':
                $startDateCarb->addYears($validLen);
                break;
        }
        return $startDateCarb->toDateString();
    }

    protected function endDateExpired($membership){
        if($membership->cm_status == 'Paid' && $membership->cm_auto_renewal == 'on'){
            $newMemberShip = $membership->replicate();
            $newMemberShip->cm_start_date = $membership->cm_end_date;
            if(!$membership->cm_parent_id)
                $newMemberShip->cm_parent_id = $membership->id;

            /*$startDateCarb = new Carbon($newMemberShip->cm_start_date);
            switch($newMemberShip->cm_validity_type){
                case 'day':
                    $startDateCarb->addDays($newMemberShip->cm_validity_length);
                    break;
                case 'week':
                    $startDateCarb->addWeeks($newMemberShip->cm_validity_length);
                    break;
                case 'month':
                    $startDateCarb->addMonths($newMemberShip->cm_validity_length);
                    break;
                case 'year':
                    $startDateCarb->addYears($newMemberShip->cm_validity_length);
                    break;
            }
            $newMemberShip->cm_end_date = $startDateCarb->toDateString();*/
            $newMemberShip->cm_end_date = $this->calcEndDate($newMemberShip->cm_start_date, $newMemberShip->cm_validity_type, $newMemberShip->cm_validity_length);

            $oldData = json_decode($membership->data, 1);
            $oldData['paid'] = 0;
            $newMemberShip->data = json_encode($oldData);

            $newMemberShip->save();

            $newMemberShipId = $newMemberShip->id;
            $insertRepeatUpto = $newMemberShip->cm_start_date;
        }
        else{
            $newMemberShipId = $insertRepeatUpto = 0;
        }

        $this->updateFutureBookingsMembership(/*$membership->futureAppointments, $membership->membChangeableClasses*/$membership, $membership->cm_client_id, $newMemberShipId, $insertRepeatUpto);
        $membership->cm_status = 'Expired'; 
        $membership->save(); 

        if($newMemberShipId){
            $newMemberShip = $this->manageClientMemb($newMemberShip);
        }

        return ($newMemberShipId)?$newMemberShip:$membership;
    }



    protected function countBookedClasses($data){
        if(array_key_exists('cm_class_limit_type', $data)){
            if($data['cm_class_limit_type'] == 'every_week'){ //If limit is weekly
                $startDate = $data['eventDate']->copy();
                $startDate->startOfWeek(); //Get start date of the event's date's week
                
                $endDate = $data['eventDate']->copy();
                $endDate->endOfWeek(); //Get end date of the event's date's week
            }
            else if($data['cm_class_limit_type'] == 'every_month'){ //If limit is monthly
                $startDate = $data['eventDate']->copy();
                $startDate->startOfMonth(); //Get start date of the event's date's month

                $endDate = $data['eventDate']->copy();
                $endDate->endOfMonth(); //Get end date of the event's date's month
            }
            
            if($startDate->lt($data['membershipStartDate'])) //If start date is prior than membership start date
                $startDate = $data['membershipStartDate']; //Set start date to membership start date
            $startDate = $startDate->toDateString();

            if($endDate->gt($data['membershipendDate'])) //If end date is after than membership end date
                $endDate = $data['membershipendDate']; //Set end date to membership end date
            $endDate = $endDate->toDateString();
        }
        else{
            $startDate = $data['membershipStartDate']->toDateString();
            $endDate = $data['membershipendDate']->toDateString();
        }

        //Counting classes booked for the given client in the calculate date range
        $bookedClasses = StaffEventClass::whereHas('clients', function($query) use($data){
                                                $query->where('secc_client_id', $data['clientId'])
                                                      //->where('secc_if_make_up_created', 0)
                                                      ->where('secc_cmid', $data['clientMembId'])
                                                      /*->where(function($query){
                                                            $query->where('secc_if_make_up', 0)
                                                                  ->orWhere('secc_client_attendance', 'Did not show');   
                                                      })*/;
                                         })
                                        ->OfBusiness()
                                        ->where('sec_date', '>=', $startDate)
                                        ->where('sec_date', '<=', $endDate)
                                        ->where('sec_id', '!=', $data['eventId'])
                                        /*->where(function($query){
                                            $query->where('secc_client_status', 'Confirm')
                                                  ->orWhere(function($query){
                                                        $query->where('sec_start_datetime', '>', (new Carbon())->toDateTimeString());
                                                    });
                                                    
                                        })*/
                                        ->count();

        return $bookedClasses;
    }


    /**
     * Delete event associate invoice
     * @param 
     * @return 
    **/
    protected function deleteEventInvoice($eventId, $clientId = 0, $eventType){
        // Delete invoice
        $invoiceIds = \App\InvoiceItems::select('inp_invoice_id')->where('inp_product_id', $eventId)->where('inp_type', $eventType)->get()->toArray();

        $invoices = \App\Invoice::whereIn('inv_id',$invoiceIds)->where('inv_client_id',$clientId)->get();

        if($invoices->count()){
            foreach ($invoices as $invoice) {
                $invoice->delete();
            }
        }
    }
}