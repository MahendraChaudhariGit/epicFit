<?php
namespace App\Http\Controllers\Result;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use \stdClass;
use App\StaffEventBusy;
use App\Parq;
use Session;
use App\StaffEventSingleService;
use App\StaffEventClass;
use App\Staff;
use Auth;
use Clas;
use DB;
use App\Clients;

use App\Http\Traits\StaffEventTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\ClosedDateTrait;

class Helper extends Controller{
    use StaffEventsTrait, StaffEventTrait,ClosedDateTrait;

    /**
     * Get country lists
     *
     * @return array
     */
    public function getCountries() {

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

    /**
     * Uplode file
     *
     * @param File
     * @return response
     */
    public function uploadFile(Request $request){
        if($request->hasFile('fileToUpload')) {
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
            if($extension == 'jpg')
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

    /**
     * Destroy file
     *
     * @param File
     * @return response
     */
    public function destroyFile(Request $request){
        @unlink(public_path().'/uploads/'.$request->photoName);
    }

    /**
     * Destroy file
     *
     * @param No image
     * @return DP url
     */
    public function noimageSrc(Request $request){
        return dpSrc('', $request->gender);
    }

    /**
     * Destroy file
     *
     * @param File
     * @return response
     */
    public function allClientEvents(Request $request){ 
        $isAreaLinkedToStaff = true;
        $evnts = [];
        $eventData = new stdClass();
        $staffEvents = collect();
        $index = 0;

        if($isAreaLinkedToStaff){
            if(in_array("single-service", $request->eventType)){
                $this->deleteExpiringAspirantsEvents();

                if($request->has('insertRepeatUpto') && $request->isInsertClassService == 'true')
                    $this->neverEndSingleServiceRepeats($request);
                
                if($request->areaId == 'all'){
                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by','is_ldc', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('service', 'client')->OfBusiness()->OfClient(Auth::user()->account_id)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }
                    else{
                       $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by','is_ldc', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('service', 'client', 'staff')->OfClient(Auth::user()->account_id)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }
                }
                else{
                    $eventData->areaId = $request->areaId;
                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by','is_ldc', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('service', 'client', 'staff')->OfArea($request->areaId)->OfClient(Auth::user()->account_id)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }
                    else{
                        $eventData->staffId = Auth::user()->account_id;
                        $staffEvents = StaffEventSingleService::select('sess_id', 'sess_sessr_id', 'sess_user_id', 'sess_business_id', 'sess_date', 'sess_time', 'sess_start_datetime' , 'sess_end_datetime' , 'sess_notes' , 'sess_staff_id', 'sess_client_id', 'sess_cmid', 'sess_with_invoice', 'sess_client_notes', 'sess_client_attendance', 'sess_booking_status', 'sess_auto_expire', 'sess_auto_expire_datetime', 'sess_service_id', 'sess_duration', 'sess_price', 'sess_cancel_reason' , 'sess_epic_credit', 'sess_if_make_up', 'sess_if_maked_up', 'sess_is_make_up', 'sess_client_deleted', 'sess_sale_process_status', 'sess_client_check', 'sess_payment', 'sess_event_log', 'sess_action_performed_by','is_ldc', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('service', 'client')->OfClient(Auth::user()->account_id)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }
                }
                
                if(count($staffEvents)){
                    foreach($staffEvents as $staffEvent){
                        $staffName=Staff::find($staffEvent->sess_staff_id);
                        if(count($staffName))
                            $evnts[$index]['staffName']=$staffName->first_name." ".$staffName->last_name;
                        else
                            $evnts[$index]['staffName'] = '';

                        $evnts[$index]['type'] = 'single-service';
                        $evnts[$index]['id'] = $staffEvent->sess_id;
                        $evnts[$index]['isStaffDeleted'] = $staffEvent->staffWithTrashed->trashed();
                        $evnts[$index]['title'] = $staffEvent->service->name;
                        $evnts[$index]['startDatetime'] = $staffEvent->sess_start_datetime;
                        $evnts[$index]['endDatetime'] = $staffEvent->sess_end_datetime;
                        $evnts[$index]['serviceName'] = $staffEvent->service->name;
                        $evnts[$index]['price'] = $staffEvent->sess_price;
                        $evnts[$index]['date'] = $staffEvent->sess_date;
                        $evnts[$index]['appointStatusOpt'] = $staffEvent->sess_booking_status;
                        $evnts[$index]['appointNote'] = $staffEvent->sess_notes;
                        $evnts[$index]['serviceColor'] = $staffEvent->service->color;
                        $evnts[$index]['logo'] = isset($staffEvent->service->logo)&& $staffEvent->service->logo != ''?asset('uploads/'.$staffEvent->service->logo):asset('profiles/noimage.gif');
                        if($staffEvent->sess_sessr_id != 0)
                            $evnts[$index]['isRepeating'] = 1;
                        else
                            $evnts[$index]['isRepeating'] = 0;

                        $evnts[$index]['clients'][0]['attendance'] = $staffEvent->sess_client_attendance;
                        $evnts[$index]['isLdc'] = $staffEvent->is_ldc;
                        $index++;
                    }
                }
            }

            if(in_array("class", $request->eventType)){
                
                if($request->has('insertRepeatUpto') && $request->isInsertClassService == 'true') 
                    $this->neverEndClassRepeats($request);

                if($request->areaId == 'all'){
                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        if($request->has('eventStatus') && $request->eventStatus == 'active'){
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('clients','clas')->whereBetween('sec_date', [$request->getEventsFrom, $request->getEventsUpto])->OfBusiness()->whereHas('clients', function ($query) {
                                return $query->where('staff_event_class_clients.secc_client_id',Auth::user()->account_id);})->active()->get();
                        }
                        else{
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('clients','clas')->whereBetween('sec_date', [$request->getEventsFrom, $request->getEventsUpto])->OfBusiness()->whereHas('clients', function ($query) {
                                return $query->where('staff_event_class_clients.secc_client_id',Auth::user()->account_id);})->get();
                        }
                    }
                    else{
                        if($request->has('eventStatus') && $request->eventStatus == 'active')
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('clients','clas')->whereBetween('sec_date', [$request->getEventsFrom, $request->getEventsUpto])->OfBusiness()->whereHas('clients', function ($query) {
                                return $query->where('staff_event_class_clients.secc_client_id',Auth::user()->account_id);})->active()->get();
                        else                    
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('clients','clas')->whereBetween('sec_date', [$request->getEventsFrom, $request->getEventsUpto])->OfBusiness()->whereHas('clients', function ($query) {
                                return $query->where('staff_event_class_clients.secc_client_id',Auth::user()->account_id);})->get();
                    }
                }
                else{

                    $eventData->areaId = $request->areaId;

                     // $staffEvents = StaffEventClass::with('clients','clasWithTrashed')->whereBetween('sec_date', [$request->getEventsFrom, $request->getEventsUpto])->OfBusiness()->whereHas('clients', function ($query) {
                     //            return $query->where('staff_event_class_clients.secc_client_id',Auth::user()->account_id);})->get();

                    if($request->staffId == 'all' || $request->staffId == 'all-ros'){
                        if($request->has('eventStatus') && $request->eventStatus == 'active')
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('clients','clas')->whereBetween('sec_date', [$request->getEventsFrom, $request->getEventsUpto])->OfBusiness()->whereHas('clients', function ($query) {
                                return $query->where('staff_event_class_clients.secc_client_id',Auth::user()->account_id);})->OfArea($request->areaId)->active()->get();
                        else
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('clients','clas')->whereBetween('sec_date', [$request->getEventsFrom, $request->getEventsUpto])->OfBusiness()->whereHas('clients', function ($query) {
                                return $query->where('staff_event_class_clients.secc_client_id',Auth::user()->account_id);})->OfArea($request->areaId)->get();
                    }
                    else{
                        $eventData->staffId = $request->staffId;
                        if($request->has('eventStatus') && $request->eventStatus == 'active') {
                            $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('clients','clas')->whereBetween('sec_date', [$request->getEventsFrom, $request->getEventsUpto])->OfBusiness()->whereHas('clients', function ($query) {
                                return $query->where('staff_event_class_clients.secc_client_id',Auth::user()->account_id);})->OfAreaAndStaff($eventData)->active()->get();
                        }
                        else 
                        {
                            // $staffEvents = StaffEventClass::select('sec_id', 'sec_secr_id', 'sec_user_id', 'sec_business_id', 'sec_staff_id' , 'sec_class_id' , 'sec_duration', 'sec_capacity', 'sec_price' , 'sec_notes', 'sec_date' , 'sec_time' , 'sec_start_datetime', 'sec_end_datetime', 'sec_payment', 'created_at', 'updated_at', 'deleted_at')->whereNull('deleted_at')->with('clients','clas')->whereBetween('sec_date', [$request->getEventsFrom, $request->getEventsUpto])->OfBusiness()->whereHas('clients', function ($query) {
                            //     return $query->where('staff_event_class_clients.secc_client_id',Auth::user()->account_id);})->get();                
                      
                            $startDate = $request->getEventsFrom;
                            $endDate = $request->getEventsUpto;
                            $staffEvents = Clients::with(['eventClassesWithTrashed' => function($q) use ($startDate ,$endDate){
                            $q->whereBetween('sec_date', [$startDate, $endDate])->whereNull('staff_event_classes.deleted_at')->whereNull('staff_event_class_clients.deleted_at');
                            }])->where('id',Auth::user()->account_id)->first();
                            $staffEvents = $staffEvents->eventClassesWithTrashed;                           
                        }
                    }
                }

                if(count($staffEvents)){
                    foreach($staffEvents->sortBy('sec_date') as $staffEvent){
                        $staffName = Staff::find($staffEvent->sec_staff_id);
                        if(count($staffName))
                            $evnts[$index]['staffName']=$staffName->first_name." ".$staffName->last_name;
                        else
                            $evnts[$index]['staffName']= '';
                      
                        $evnts[$index]['type'] = 'class';
                        $evnts[$index]['id'] = $staffEvent->sec_id;
                        $evnts[$index]['date'] = $staffEvent->sec_date;
                        $evnts[$index]['startDatetime'] = $staffEvent->sec_start_datetime;
                        $evnts[$index]['endDatetime'] = $staffEvent->sec_end_datetime;
                        $evnts[$index]['title'] = $staffEvent->clas->cl_name;
                        $evnts[$index]['price'] = $staffEvent->sec_price;
                        $evnts[$index]['color'] = $staffEvent->clas->cl_colour;
                        $evnts[$index]['isStaffDeleted'] = $staffEvent->staffWithTrashed->trashed();
                        $evnts[$index]['capacity'] = $staffEvent->sec_capacity;
                        $evnts[$index]['notes'] = $staffEvent->sec_notes;
                        $evnts[$index]['isLdc'] = $staffEvent->pivot->is_ldc;
                        $evnts[$index]['logo'] = isset($staffEvent->clas->cl_logo) && $staffEvent->clas->cl_logo != ''?asset('uploads/'.$staffEvent->clas->cl_logo):asset('profiles/noimage.gif');
                        if($staffEvent->sec_secr_id != 0)
                            $evnts[$index]['isRepeating'] = 1;
                        else
                            $evnts[$index]['isRepeating'] = 0;

                        $i = 0;
                        foreach($staffEvent->clients as $client){
                            $evnts[$index]['clients'][$i]['name'] = $client->firstname.' '.$client->lastname;
                            $i++;
                        }
                        $evnts[$index]['clientsCount'] = $staffEvent->clients->count();
                        $index++;
                    }
                }
            }
        }
        usort($evnts, function ($item1, $item2) {
            return $item1['date'] <=> $item2['date'];
        });
        return json_encode($evnts);
    }


    function calcSalesProcessRelatedStatus($stepNumb/* = 0, $prevStatus = ''*/){
        $return = [];
        if($stepNumb === 1 || $stepNumb == 'contact' || $stepNumb == 'Pre-Consultation'/* || $prevStatus == 'Pending'*/){
            //$return['clientPrevStatus'] = ['Pending', 'Active Lead', 'Inactive Lead'];
            $return['clientPrevStatus'] = 'Pending';
            $return['clientStatus'] = 'Pre-Consultation';
            $return['salesProcessType'] = 'contact';
            $return['saleProcessStepNumb'] = 1;
            $return['statusDependingStep'] = 3;
        }
        else if($stepNumb === 2 || $stepNumb == 'book_consult'){
            $return['clientPrevStatus'] = 'Pre-Consultation';
            $return['salesProcessType'] = 'book_consult';
            $return['saleProcessStepNumb'] = 2;
            $return['dependantStep'] = 1;
        }
        else if($stepNumb === 3 || $stepNumb == 'consulted' || $stepNumb == 'Pre-Benchmarking'/* || $prevStatus == 'Pre-Consultation'*/){
            $return['clientPrevStatus'] = 'Pre-Consultation';
            $return['clientStatus'] = 'Pre-Benchmarking';
            $return['salesProcessType'] = 'consulted';
            $return['saleProcessStepNumb'] = 3;
            $return['statusDependingStep'] = 5;
        }
        else if($stepNumb === 4 || $stepNumb == 'book_benchmark'){
            $return['clientPrevStatus'] = 'Pre-Benchmarking';
            $return['salesProcessType'] = 'book_benchmark';
            $return['saleProcessStepNumb'] = 4;
            $return['dependantStep'] = 2;
        }
        else if($stepNumb === 5 || $stepNumb == 'benchmarked' || $stepNumb == 'Pre-Training'/* || $prevStatus == 'Pre-Benchmarking'*/){
            $return['clientPrevStatus'] = 'Pre-Benchmarking';
            $return['clientStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'benchmarked';
            $return['saleProcessStepNumb'] = 5;
            $return['dependantStep'] = 3;
            $return['statusDependingStep'] = 11;//[11,17];
        }
        else if($stepNumb === 6 || $stepNumb == 'book_team'){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_team';
            $return['saleProcessStepNumb'] = 6;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === 7){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_team';
            $return['saleProcessStepNumb'] = 7;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === 8){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_team';
            $return['saleProcessStepNumb'] = 8;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === 9){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_team';
            $return['saleProcessStepNumb'] = 9;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === 10){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_team';
            $return['saleProcessStepNumb'] = 10;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === /*9*/11 || $stepNumb == 'teamed' || $stepNumb == 'Active'/* || $prevStatus == 'Pre-Training'*/){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['clientStatus'] = 'Active';
            $return['salesProcessType'] = 'teamed';
            $return['saleProcessStepNumb'] = /*9*/11;
            $return['dependantStep'] = 5;
            $return['statusDependingStep'] = 18;
        }
        else if($stepNumb === 12 || $stepNumb == 'book_indiv'){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_indiv';
            $return['saleProcessStepNumb'] = 12;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === 13 || $stepNumb == 'book_indiv'){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_indiv';
            $return['saleProcessStepNumb'] = 13;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === 14 || $stepNumb == 'book_indiv'){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_indiv';
            $return['saleProcessStepNumb'] = 14;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === 15 || $stepNumb == 'book_indiv'){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_indiv';
            $return['saleProcessStepNumb'] = 15;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === 16 || $stepNumb == 'book_indiv'){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['salesProcessType'] = 'book_indiv';
            $return['saleProcessStepNumb'] = 16;
            $return['dependantStep'] = 4;
        }
        else if($stepNumb === 17 || $stepNumb == 'indiv'){
            $return['clientPrevStatus'] = 'Pre-Training';
            $return['clientStatus'] = 'Active';
            $return['salesProcessType'] = 'indiv';
            $return['saleProcessStepNumb'] = 17;
            $return['dependantStep'] = 5;
            $return['statusDependingStep'] = 18;
        }
        else if($stepNumb === /*10*/18 || $stepNumb == 'email_price'){
            $return['clientPrevStatus'] = 'Active';
            $return['salesProcessType'] = 'email_price';
            $return['saleProcessStepNumb'] = /*10*/18;
        }
        else{
            if($stepNumb == 'Pending')
                $return['statusDependingStep'] = 1;
            $return['clientPrevStatus'] = '';
            $return['clientStatus'] = '';
            $return['salesProcessType'] = '';
            $return['saleProcessStepNumb'] = 0;
        }

        /*if(count($return)){
            $salesProcessTypes = salesProcessTypes();
            $return['salesProcessType'] = $salesProcessTypes[$stepNumb-1];
        }*/

        return $return;
    }

    public function allEventsTiming(Request $request){
        $staffEvents = StaffEventClass::with('clients','clas','staff')
                                        ->whereNull('deleted_at')
                                        ->whereDate('sec_date', $request->eventDate)
                                        ->OfBusiness()
                                        ->orderBy('sec_start_datetime')
                                        ->get();
        $clientId = Auth::user()->account_id;
        $data = array();
        foreach($staffEvents as $event){
            if(count($event->clas) && count($event->staff)){
                $clientsId = array();
                $isClientAlreadyBooked = false;
                if(count($event->clients)){
                    $clientsId = $event->clients->pluck('id')->toArray();
                }
                if(count($clientsId)){
                    $isClientAlreadyBooked = in_array($clientId,$clientsId);
                }
                $data[] = [
                    'secId' => $event->sec_id,
                    'secDate' => $event->sec_date,
                    'startDatetime' => $event->sec_start_datetime,
                    'name' => $event->clas->cl_name,
                    'classLogo' => isset($event->clas->cl_logo) && $event->clas->cl_logo != ''?asset('uploads/'.$event->clas->cl_logo):asset('profiles/noimage.gif'),
                    'startDatetime' => $event->sec_start_datetime,
                    'endDateTime' => $event->sec_end_datetime,
                    'trainerName' => $event->staff->first_name." ".$event->staff->last_name,
                    'bookedClients' => count($event->clients),
                    'capacity' => $event->sec_capacity,
                    'isClientAlreadyBooked' => $isClientAlreadyBooked,
                ];
            }
        }
        $responseData = [
            'status' => 'ok',
            'data' => $data
        ];
        return response()->json($responseData);
    }

    /**
     * Get Client Epic Credit Balance
     * @param 
     * @return response
     */
    public function fetchClientEpicBalance(){
        $clientId = Auth::User()->account_id;
        $client = Clients::find($clientId);
        $epicCreditBalance = 0;
        if($client){
            $epicCreditBalance = $client->epic_credit_balance;
        }
        return $epicCreditBalance;
    }


}
