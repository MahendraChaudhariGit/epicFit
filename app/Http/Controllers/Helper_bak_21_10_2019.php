<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

use \stdClass;
use App\StaffEventBusy;
use Auth;
use App\LocationArea;
use App\Staff;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\StaffEventTrait;
use App\StaffEventSingleService;
use App\StaffEventClass;
use DB;
use App\Http\Traits\ClosedDateTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\SalesProcessProgressTrait;
use Session;
use App\Clients;
//use App\Parq;
//use Input;

class Helper extends Controller{
    use StaffEventsTrait, StaffEventTrait, ClosedDateTrait, HelperTrait, ClientTrait, SalesProcessProgressTrait;

    /**
     * Get country lists
     *
     * @return array
     */
    public function getCountries(){
        return \Country::getCountryLists();
        exit;
    }

    /**
     * Get state lists for specific country
     *
     * @param $country_code
     * @return array
     */
    public function getStates($country_code) {
        if($country_code == "" || $country_code == 'undefined') {
            return '<option value="">No state has been found!</option>';
            exit;
        }
        return \Country::getStateLists($country_code);
        exit;
    }

    public function uploadFile(Request $request){
        if($request->has('api')){
            $file = base64_decode($request->file);
            $timestamp = md5(time().rand());
            $name = $timestamp.'.'.$request->img_ext;
            file_put_contents(public_path().'/uploads/'.$name, $file);
            return $name;
        }
        else if($request->hasFile('fileToUpload')) {
            $file = Input::file('fileToUpload');
            $timestamp = md5(time().rand());
            $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $name = $timestamp.'.'.$extension;
            $file->move(public_path().'/uploads/', $name);
            return $name;
        }
        else if($request->photoName){
            $iWidth = $request->w;
            $iHeight = $request->h;
            $uploadPath = public_path().'/uploads/';
            $temp = explode('.', $request->photoName);
            $extension = $temp[1];
            $extension = strtolower($extension);
            
            if($extension == 'jpg' || $extension == 'jpeg')
                $vImg = @imagecreatefromjpeg($uploadPath.$request->photoName);
            else if($extension == 'png')
                $vImg = @imagecreatefrompng($uploadPath.$request->photoName);
            else
                @unlink($uploadPath.$request->photoName);
                        
            $vDstImg = @imagecreatetruecolor($iWidth, $iHeight);
            if($request->widthScale && $request->widthScale != 'Infinity'){
                $x1 = (int)($request->x1*$request->widthScale);
                $w = (int)($request->w*$request->widthScale);
            }
            else{
                $x1 = (int)$request->x1;
                $w = (int)$request->w;
            }
            if($request->heightScale && $request->heightScale != 'Infinity'){
                $y1 = (int)($request->y1*$request->heightScale);
                $h = (int)($request->h*$request->heightScale);
            }
            else{
                $y1 = (int)$request->y1;
                $h = (int)$request->h;
            }
                
            imagecopyresampled($vDstImg, $vImg, 0, 0, $x1, $y1, $iWidth, $iHeight, $w, $h);
            imagejpeg($vDstImg, $uploadPath.'thumb_'.$request->photoName, 90);
            if($request->prePhotoName){
                @unlink($uploadPath.$request->prePhotoName);
                @unlink($uploadPath.'thumb_'.$request->prePhotoName);
            }

            return $request->photoName;
        }
    }

    public function destroyFile(Request $request){
        @unlink(public_path().'/uploads/'.$request->photoName);
    }

    public function noimageSrc(Request $request){
        return dpSrc('', $request->gender);
    }

    public function allStaffEventsUpdateByCron(){   

        //$insertRepeatUpto =  "2020-06-30";   
        $insertRepeatUpto =  date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 365 day"));
        //dd($insertRepeatUpto);
        $repeatEventId = 0;   
      
        $this->createRecurrenceClassByCron($repeatEventId, $insertRepeatUpto);   
         
        $this->createRecurrenceSingleServiceByCron($repeatEventId, $insertRepeatUpto);   
        
    }

    public function allStaffEvents(Request $request) {      
        $businessId = Session::get('businessId'); 
        
        if($request->areaId != 'all' && !LocationArea::ifAreaExist($request->areaId))
            $this->haltScript($request);

        if($request->staffId != 'all' && $request->staffId != 'all-ros' && !Staff::ifstaffExist($request->staffId))
            $this->haltScript($request);

        $isAreaLinkedToStaff = true;
        if(isUserType(['Staff'])){
            $request->staffId = Auth::user()->account_id;
            if($request->areaId != 'all')
                $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->areaId, 'staffId' => $request->staffId]);
        }

        $evnts = [];
        $eventData = new stdClass();
        $index = 0;

        if($isAreaLinkedToStaff){
            if(in_array("busy-time", $request->eventType)){
                if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-event-busy'))
                    $this->haltScript($request);  

                $staffEvents = collect();
                if($request->areaId == 'all'){
                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        $staffEvents = StaffEventBusy::select('seb_id', 'seb_user_id', 'seb_business_id', 'seb_area_id', 'seb_staff_id', 'seb_date', 'seb_time', 'seb_duration', 'seb_end_time', 'seb_start_datetime', 'seb_end_datetime', 'seb_desc', 'seb_deny_booking', 'created_at', 'updated_at', 'deleted_at')->with('staff')->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }
                    else
                        $staffEvents = StaffEventBusy::select('seb_id', 'seb_user_id', 'seb_business_id', 'seb_area_id', 'seb_staff_id', 'seb_date', 'seb_time', 'seb_duration', 'seb_end_time', 'seb_start_datetime', 'seb_end_datetime', 'seb_desc', 'seb_deny_booking', 'created_at', 'updated_at', 'deleted_at')->with('staff')->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                }
                else{
                    $eventData->areaId = $request->areaId;

                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        $staffEvents = StaffEventBusy::select('seb_id', 'seb_user_id', 'seb_business_id', 'seb_area_id', 'seb_staff_id', 'seb_date', 'seb_time', 'seb_duration', 'seb_end_time', 'seb_start_datetime', 'seb_end_datetime', 'seb_desc', 'seb_deny_booking', 'created_at', 'updated_at', 'deleted_at')->with('staff')->where('seb_area_id', $request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }
                    else{
                        $eventData->staffId = $request->staffId;
                        $staffEvents = StaffEventBusy::select('seb_id', 'seb_user_id', 'seb_business_id', 'seb_area_id', 'seb_staff_id', 'seb_date', 'seb_time', 'seb_duration', 'seb_end_time', 'seb_start_datetime', 'seb_end_datetime', 'seb_desc', 'seb_deny_booking', 'created_at', 'updated_at', 'deleted_at')->with('staff')->OfAreaAndStaff($eventData)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }
                }
                if(count($staffEvents)){
                    foreach($staffEvents as $staffEvent){
                        $evnts[$index]['type'] = 'busy-time';
                        $evnts[$index]['id'] = $staffEvent->seb_id;
                        $evnts[$index]['description'] = $staffEvent->seb_desc;
                        $evnts[$index]['startDatetime'] = $staffEvent->seb_start_datetime;
                        $evnts[$index]['endDatetime'] = $staffEvent->seb_end_datetime;
                        $evnts[$index]['date'] = $staffEvent->seb_date;
                        $evnts[$index]['isStaffDeleted'] = $staffEvent->staff->trashed();

                        $index++;
                    }
                }
            }

            /* Start: Clear old parent id of bookings */
                if($request->has('clearSession') && $request->clearSession == 'true'){
                    Session::forget('class');
                    Session::forget('classDate');
                    Session::forget('childClass');
                    Session::forget('service');
                    Session::forget('serviceDate');
                    Session::forget('childService');
                }
            /* End: Clear old parent id of bookings */

            if($request->has('removeStepType') && $request->removeStepType)
                $client = Clients::find($request->clientId);

            if(in_array("single-service", $request->eventType)){
                if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-event-appointment'))
                    $this->haltScript($request);

                /* Start: delete all service which not have client*/
                if($request->has('delService') && $request->delService == 'true')
                    $this->deleteStaffEventSingleServices(); 

                /* End: delete all service which not have client*/
                $this->deleteExpiringAspirantsEvents();

               /* if($request->has('insertRepeatUpto'))*/
               if($request->has('insertRepeatUpto') && $request->isInsertClassService == 'true')
                    $this->neverEndSingleServiceRepeats($request);

                if($request->has('removeStepType') && $request->removeStepType){
                    $indivBookings = [];
                    if($client/* && $client->IndivEnabledCount*/)
                        $indivBookings = StaffEventSingleService::indivBookings($client->id, $client->consultation_date, 'sess_id');
                }

                $staffEvents = collect();
                if($request->areaId == 'all'){
                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        if($request->has('removeStepType') && $request->removeStepType){
                            if(count($indivBookings)){
                                $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->with('service', 'client', 'staff')->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->find($indivBookings);
                            }
                        }
                        else{
                            $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->with('service', 'client', 'staff')->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->get();
                        }
                    }
                    else{
                        if($request->has('removeStepType') && $request->removeStepType){
                            if(count($indivBookings)){
                                $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->with('service', 'client', 'staff')->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->find($indivBookings);
                            }
                        }
                        else{
                            $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->with('service', 'client', 'staff')->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->get();
                        }
                    }
                }
                else{
                    $eventData->areaId = $request->areaId;

                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        if($request->has('removeStepType') && $request->removeStepType){
                            if(count($indivBookings)){
                                $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->with('service', 'client', 'staff')->OfArea($request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->find($indivBookings);
                            }
                        }
                        else{
                            $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->with('service', 'client', 'staff')->OfArea($request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->get();
                        }
                    }
                    else{
                        $eventData->staffId = $request->staffId;

                        if($request->has('removeStepType') && $request->removeStepType){
                            if(count($indivBookings)){
                                $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->with('service', 'client', 'staff')->OfAreaAndStaff($eventData)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->find($indivBookings);
                            }
                        }
                        else{
                            $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by', 'created_at', 'updated_at', 'deleted_at')->with('service', 'client', 'staff')->OfAreaAndStaff($eventData)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->get();
                        }
                    }
                }

                // dd($staffEvents->toArray());
                
                if(count($staffEvents)){
                    foreach($staffEvents as $staffEvent){
                        // dd($staffEvent->toArray());
                        if($request->has('removeStepType') && $request->removeStepType == 'attend' && $staffEvent->sess_client_attendance != 'Attended')
                            $continue = false;
                        else 
                            $continue = true;

                        if($continue){
                            $evnts[$index]['type'] = 'single-service';
                            $evnts[$index]['id'] = $staffEvent->sess_id;
                            $evnts[$index]['isStaffDeleted'] = $staffEvent->staff->trashed();
                            
                            if($staffEvent->clientWithTrashed)
                                $evnts[$index]['title'] = $staffEvent->client->FullName;
                            else
                                $evnts[$index]['title'] = '';

                            $evnts[$index]['startDatetime'] = $staffEvent->sess_start_datetime;
                            $evnts[$index]['endDatetime'] = $staffEvent->sess_end_datetime;
                            $evnts[$index]['serviceName'] = $staffEvent->service->name;
                            $evnts[$index]['price'] = $staffEvent->sess_price;
                            $evnts[$index]['date'] = $staffEvent->sess_date;
                            $evnts[$index]['appointStatusOpt'] = $staffEvent->sess_booking_status;
                            $evnts[$index]['appointNote'] = $staffEvent->sess_notes;
                            $evnts[$index]['serviceColor'] = $staffEvent->service->color;
                            if($staffEvent->sess_sessr_id != 0)
                                $evnts[$index]['isRepeating'] = 1;
                            else
                                $evnts[$index]['isRepeating'] = 0;
                            $evnts[$index]['isMakeUp'] = $staffEvent->sess_is_make_up;
                            if($staffEvent->sess_epic_credit && $staffEvent->client)
                                $evnts[$index]['epicCreditBal'] = $staffEvent->client->epic_credit_balance;

                            $evnts[$index]['withInvoice'] = $staffEvent->sess_with_invoice;
                            
                            $evnts[$index]['isIndiv'] = false;
                            $evnts[$index]['clients'][0]['attendance'] = $staffEvent->sess_client_attendance;
                            if($staffEvent->client)
                                $evnts[$index]['clients'][0]['isClientDeleted'] = $staffEvent->client->trashed();
                            else
                                $evnts[$index]['clients'][0]['isClientDeleted'] ='';

                            $index++;
                        }
                    }
                }
            }

            if(in_array("class", $request->eventType)){
                if(!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-event-class'))
                    $this->haltScript($request);

                /*if($request->has('insertRepeatUpto'))*/
                if($request->has('insertRepeatUpto') && $request->isInsertClassService == 'true')
                    $this->neverEndClassRepeats($request);

                if($request->has('removeStepType') && $request->removeStepType){
                    $teamBookings = [];
                    if($client/* && $client->TeamEnabledCount*/){
                        $teamBookings = StaffEventClass::teamBookings($client->id, $client->consultation_date, 0, 'sec_id');
                    }
                }

                $staffEvents = collect();
                if($request->areaId == 'all'){
                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        if($request->has('eventStatus') && $request->eventStatus == 'active'){
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->active()->whereNull('deleted_at')->get();
                        }
                        else if($request->has('removeStepType') && $request->removeStepType){
                            if(count($teamBookings)){
                                $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->find($teamBookings);
                            }
                        }
                        else if($request->has('action') && $request->action == 'merge'){
                            $filterData = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->where('sec_id',$request->filterEventId)->select('sec_class_id','sec_date','sec_time')->whereNull('deleted_at')->first()->toArray();
                            
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfBusiness()/*->where('sec_class_id', $filterData['sec_class_id'])*/->where('sec_id','<>', $request->filterEventId)->where('sec_date', $filterData['sec_date'])->where('sec_time', $filterData['sec_time'])->whereNull('deleted_at')->get();
                        }
                        else{
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->get();
                        }
                    }
                    else{
                        if($request->has('eventStatus') && $request->eventStatus == 'active'){
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->active()->get();
                        }
                        else if($request->has('removeStepType') && $request->removeStepType){
                            if(count($teamBookings)){
                                $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->find($teamBookings);
                            }
                        }
                        else{                
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->get();
                        }
                    }
                }
                else{
                    $eventData->areaId = $request->areaId;

                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        if($request->has('eventStatus') && $request->eventStatus == 'active'){
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfArea($request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->active()->whereNull('deleted_at')->get();
                        }
                        else if($request->has('removeStepType') && $request->removeStepType){
                            if(count($teamBookings)){
                                $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfArea($request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->find($teamBookings);
                            }
                        }
                        else{
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfArea($request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->get();
                        }
                    }
                    else{
                        $eventData->staffId = $request->staffId;

                        if($request->has('eventStatus') && $request->eventStatus == 'active'){
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfAreaAndStaff($eventData)->active()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->get();
                        }
                        else if($request->has('removeStepType') && $request->removeStepType){
                            if(count($teamBookings)){
                                $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfAreaAndStaff($eventData)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->find($teamBookings);
                            }
                        }
                        else{
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->where('sec_business_id', $businessId)->with('clas', 'clients', 'staff')->OfAreaAndStaff($eventData)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->whereNull('deleted_at')->get();
                        }
                    }
                }

                if(count($staffEvents)){
                    foreach($staffEvents as $staffEvent){
                        $continue = true;

                        if($request->has('removeStepType') && $request->removeStepType == 'attend'){
                            $clientRec = $staffEvent->clients->where('id', $client->id)->first();
                            if(!$clientRec || $clientRec->pivot->secc_client_attendance != 'Attended')
                                $continue = false;
                        }

                        if($continue){
                            $evnts[$index]['type'] = 'class';
                            $evnts[$index]['id'] = $staffEvent->sec_id;
                            $evnts[$index]['date'] = $staffEvent->sec_date;
                            $evnts[$index]['startDatetime'] = $staffEvent->sec_start_datetime;
                            $evnts[$index]['endDatetime'] = $staffEvent->sec_end_datetime;
                            $evnts[$index]['title'] = $staffEvent->clas->cl_name;
                            $evnts[$index]['price'] = $staffEvent->sec_price;
                            $evnts[$index]['color'] = $staffEvent->clas->cl_colour;
                            $evnts[$index]['isStaffDeleted'] = $staffEvent->staff->trashed();
                            $evnts[$index]['capacity'] = $staffEvent->sec_capacity;

                            if($staffEvent->sec_secr_id != 0)
                                $evnts[$index]['isRepeating'] = 1;
                            else
                                $evnts[$index]['isRepeating'] = 0;

                            $evnts[$index]['notes'] = $staffEvent->sec_notes;

                            $i = $clientsCount = 0;
                            foreach($staffEvent->clients as $client){
                                $evnts[$index]['clients'][$i]['name'] = $client->firstname.' '.$client->lastname;
                                $evnts[$index]['clients'][$i]['isClientDeleted'] = $client->trashed();
                                if(!$client->trashed())
                                    $clientsCount++;
                                $i++;
                            }
                            $evnts[$index]['clientsCount'] = $clientsCount;

                            $index++;
                        }
                    }
                }
            }
        }

        return json_encode($evnts);
    }

    public function previewAjax(){
        return view('ajax_preview');
    }


    /**
     * Remove existing photo
     * @param File
     * @return response
     */
    public function removePic(Request $request){
        $msg = array("status"=>"error");
        $entityId = (int)$request->entityId;
        $entityName = $request->entityName;
        if($request->photoName != '' && $entityId != '' && $entityName != ''){
            if($entityName == 'location')
                \App\Location::where('id', $entityId)->update(['logo'=>'']);

            if($entityName == 'area')
                \App\LocationArea::where('la_id', $entityId)->update(['la_logo'=>'']);

            if($entityName == 'service')
                \App\Service::where('id', $entityId)->update(['one_on_one_training_logo'=>'','team_training_logo'=>'']);

            $image = public_path('/uploads/') . $request->photoName;
            if(file_exists($image))
                @unlink($image);
            
            $msg["status"] = 'success';
        }
        return json_encode($msg);
    }
}
