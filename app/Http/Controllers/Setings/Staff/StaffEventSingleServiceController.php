<?php
namespace App\Http\Controllers\Setings\Staff;

use App\CalendarSetting;
use App\ClientMember;
use App\Clients;
use App\ClosedDate;
use App\Http\Controllers\Controller;
use App\Http\Traits\ClientNoteTrait;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\ClosedDateTrait;
use App\Http\Traits\ContactNoteTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\SalesProcessProgressTrait;
use App\Http\Traits\SalesProcessTrait;
use App\Http\Traits\StaffEventClassTrait;
// use App\Http\Traits\StaffEventHistoryTrait;
use App\Http\Traits\StaffEventResourceTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\StaffEventTrait;
//use App\SalesProcess;
use App\Invoice;
use App\InvoiceItems;
use App\LocationArea;
use App\Makeup;
use App\SalesProcessProgress;
use App\Service;
use App\Staff;
use App\StaffEventRepeat;
use App\StaffEventSingleService;
use App\StaffEventSingleServiceRepeat;
use App\User;
use App\StaffEventBusy;
use App\StaffEventClass;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;
use \stdClass;
use App\Http\Traits\StaffTrait;

class StaffEventSingleServiceController extends Controller
{
    use StaffEventsTrait,StaffTrait, /*StaffEventHistoryTrait, *//*StaffEventClassTrait,*/ StaffEventTrait, ContactNoteTrait, SalesProcessTrait, ClientTrait, ClosedDateTrait, StaffEventResourceTrait, HelperTrait, ClientNoteTrait, SalesProcessProgressTrait;

    /**
     * Index
     * @param
     * @return
     */
    public function index(Request $request)
    {
        if ($request->areaId != 'all' && !LocationArea::ifAreaExist($request->areaId)) {
            $this->haltScript($request);
        }

        if ($request->staffId != 'all' && $request->staffId != 'all-ros' && !Staff::ifstaffExist($request->staffId)) {
            $this->haltScript($request);
        }

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-event-appointment')) {
            $this->haltScript($request);
        }

        $this->deleteExpiringAspirantsEvents();

        $evnts               = [];
        $isAreaLinkedToStaff = true;
        if (isUserType(['Staff'])) {
            $request->staffId = Auth::user()->account_id;
            if ($request->areaId != 'all') {
                $isAreaLinkedToStaff = $this->isAreaLinkedToStaff(['areaId' => $request->areaId, 'staffId' => $request->staffId]);
            }

        }

        if ($isAreaLinkedToStaff) {
            if ($request->has('insertRepeatUpto')) {
                $this->neverEndSingleServiceRepeats($request);
            }

            $staffEvents    = collect();
            $eventClassData = new stdClass();

            if ($request->areaId == 'all') {
                if ($request->staffId == 'all' || $request->staffId == 'all-ros') {
                    $staffEvents = StaffEventSingleService::with('serviceWithTrashed', 'client', 'staffWithTrashed')->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                } else {
                    $staffEvents = StaffEventSingleService::with('serviceWithTrashed', 'client', 'staffWithTrashed')->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                }
            } else {
                $eventClassData->areaId = $request->areaId;

                if ($request->staffId == 'all' || $request->staffId == 'all-ros') {
                    $staffEvents = StaffEventSingleService::with('serviceWithTrashed', 'client', 'staffWithTrashed')->OfArea($request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                } else {
                    $eventClassData->staffId = $request->staffId;
                    $staffEvents             = StaffEventSingleService::with('serviceWithTrashed', 'client', 'staffWithTrashed')->OfAreaAndStaff($eventClassData)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                }
            }

            if (count($staffEvents)) {
                $index = 0;
                foreach ($staffEvents as $staffEvent) {
                    $evnts[$index]['id']               = $staffEvent->sess_id;
                    $evnts[$index]['isStaffDeleted']   = $staffEvent->staffWithTrashed->trashed();
                    $evnts[$index]['title']            = $staffEvent->client->FullName;
                    $evnts[$index]['startDatetime']    = $staffEvent->sess_start_datetime;
                    $evnts[$index]['endDatetime']      = $staffEvent->sess_end_datetime;
                    $evnts[$index]['serviceName']      = $staffEvent->serviceWithTrashed->name;
                    $evnts[$index]['price']            = $staffEvent->sess_price;
                    $evnts[$index]['date']             = $staffEvent->sess_date;
                    $evnts[$index]['appointStatusOpt'] = $staffEvent->sess_booking_status;
                    $evnts[$index]['appointNote']      = $staffEvent->sess_notes;
                    $evnts[$index]['serviceColor']     = $staffEvent->serviceWithTrashed->color;

                    if ($staffEvent->sess_sessr_id != 0) {
                        $evnts[$index]['isRepeating'] = 1;
                    } else {
                        $evnts[$index]['isRepeating'] = 0;
                    }

                    $evnts[$index]['clients'][0]['attendance'] = $staffEvent->sess_client_attendance;
                    $index++;
                }
            }
        }

        return json_encode($evnts);
    }

    /**
     * Display data in service modal
     * @param
     * @return
     */
    public function show($eventId, Request $request)
    {
        if (Session::get('hostname') == 'crm') {
            if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'view-staff-event-appointment')) {
                $this->haltScript($request);
            }

        }

        $event = [];
        if (isUserType(['Staff'])) {
            $staffEvent = StaffEventSingleService::with('serviceWithTrashed', 'clientWithTrashed', 'historiesWithTrashed', 'locationAndAreasWithTrashed', 'staffWithTrashed', 'repeatWithTrashed')->withTrashed()->OfStaff(Auth::user()->account_id)->find($eventId);
        }
        //, 'userWithTrashed'
        else {
            $staffEvent = StaffEventSingleService::with('serviceWithTrashed', 'clientWithTrashed', 'historiesWithTrashed', 'locationAndAreasWithTrashed', 'staffWithTrashed', 'repeatWithTrashed', 'resourcesWithTrashed')->withTrashed()->OfBusiness()->find($eventId);
        }
        //, 'userWithTrashed'
        if ($staffEvent) {
            $event['id']                   = $staffEvent->sess_id;
            $event['date']                 = $staffEvent->sess_date;
            $event['startTime']            = $staffEvent->sess_time;
            $event['notes']                = $staffEvent->sess_notes;
            $event['startDatetime']        = $staffEvent->sess_start_datetime;
            $event['endDatetime']          = $staffEvent->sess_end_datetime;
            $event['userId']               = $staffEvent->sess_user_id;
            $event['appointStatusOpt']     = $staffEvent->sess_booking_status;
            $event['autoExpireAppointDur'] = $staffEvent->sess_auto_expire;
            $event['autoExpireDatetime']   = $staffEvent->sess_auto_expire_datetime;
            $event['salesProcessEvent']    = $this->saleProcessStatusToStepNumb($staffEvent->sess_sale_process_status);
            $event['membershipId']         = $staffEvent->sess_cmid;
            $event['isInvoice']            = $staffEvent->sess_with_invoice;
            $event['isEpicCredit']         = $staffEvent->sess_epic_credit;
            if (!$event['salesProcessEvent'] && $staffEvent->clientWithTrashed) {
                $event['salesProcessEvent'] = $this->isBookingIndivBooking($staffEvent->sess_id, $staffEvent->sess_date, $staffEvent->clientWithTrashed);
            }

            $event['justCreated']    = $staffEvent->sess_client_check;
            $event['isClientMakeup'] = $staffEvent->sess_epic_credit;

            if ($staffEvent->sess_sessr_id != 0) {
                $event['isRepeating'] = 1;
            } else {
                $event['isRepeating'] = 0;
            }

            if ($staffEvent->clientWithTrashed) {
                $event['consultationDate'] = $staffEvent->clientWithTrashed->consultation_date;
            }

            $i = 0;
            foreach ($staffEvent->locationAndAreasWithTrashed as $area) {
                $event['areas'][$i]['id']            = $area->la_id;
                $event['areas'][$i]['locAreaName']   = $area->locationWithTrashed->location_training_area . ' - ' . $area->la_name;
                $event['areas'][$i]['isAreaDeleted'] = $area->trashed();
                $i++;
            }

            $event['staffName']      = $staffEvent->staffWithTrashed->fullName;
            $event['staffId']        = $staffEvent->sess_staff_id;
            $event['isStaffDeleted'] = $staffEvent->staffWithTrashed->trashed();

            $event['serviceId']           = $staffEvent->sess_service_id;
            $event['duration']            = $staffEvent->sess_duration;
            $event['serviceName']         = $staffEvent->serviceWithTrashed->name;
            $event['serviceColor']        = $staffEvent->serviceWithTrashed->color;
            $event['forSalesProcessStep'] = $staffEvent->serviceWithTrashed->for_sales_process_step;
            $event['isServiceDeleted']    = $staffEvent->serviceWithTrashed->trashed();
            $event['price']               = $staffEvent->sess_price; 
            $event['isLdc']               = $staffEvent->is_ldc;

            if ($staffEvent->repeat()->count()) {
                $repeat                            = $staffEvent->repeat()->first();
                $event['eventRepeat']              = $repeat->sessr_repeat;
                $event['eventRepeatInterval']      = $repeat->sessr_repeat_interval;
                $event['eventRepeatEnd']           = $repeat->sessr_repeat_end;
                $event['eventRepeatEndAfterOccur'] = $repeat->sessr_repeat_end_after_occur;
                $event['eventRepeatEndOnDate']     = $repeat->sessr_repeat_end_on_date;
                $event['eventRepeatWeekDays']      = $repeat->sessr_repeat_week_days;
            }
            if ($staffEvent->clientWithTrashed) {
                $event['clients'][0]['id']              = $staffEvent->clientWithTrashed->id;
                $event['clients'][0]['name']            = $staffEvent->clientWithTrashed->fullName;
                $event['clients'][0]['accStatus']       = $staffEvent->clientWithTrashed->account_status;
                $event['clients'][0]['phone']           = $staffEvent->clientWithTrashed->phonenumber;
                $event['clients'][0]['email']           = $staffEvent->clientWithTrashed->email;
                $event['clients'][0]['note']            = $staffEvent->sess_client_notes;
                $event['clients'][0]['isLdc']            = $staffEvent->is_ldc;
                $event['clients'][0]['attendance']      = $staffEvent->sess_client_attendance;
                $event['clients'][0]['photo']           = $staffEvent->clientWithTrashed->profilepic;
                $event['clients'][0]['isClientDeleted'] = $staffEvent->clientWithTrashed->trashed();
            }

            $i         = 0;
            $histories = $staffEvent->historiesWithTrashed->sortByDesc('created_at');
            foreach ($histories as $history) {
                $event['histories'][$i]['text']      = $history->seh_text;
                $event['histories'][$i]['ownerName'] = $history->seh_name;
                $event['histories'][$i]['date']      = setLocalToBusinessTimeZone($history->created_at)->toDateString();
                $event['histories'][$i]['time']      = setLocalToBusinessTimeZone($history->created_at)->toTimeString();
                $event['histories'][$i]['type']      = $history->seh_type;
                $i++;
            }

            if ($staffEvent->resourcesWithTrashed->count()) {
                $i = 0;
                foreach ($staffEvent->resourcesWithTrashed as $resource) {
                    //$event['resources'][$i]['name'] = $resource->resourceWithTrashed->res_name;
                    $event['resources'][$i]['id']       = $resource->serc_res_id;
                    $event['resources'][$i]['quantity'] = $resource->serc_item_quantity;
                    $i++;
                }
            }

            if ($staffEvent->sess_client_check) {
                $staffEvent->sess_client_check = 0;
                $staffEvent->save();
            }
        }

        return json_encode($event);
    }

    /**
     * Store service data
     * @param
     * @return
     */
    public function store(Request $request)
    {  
        $isError = false;
        $msg     = [];
        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'create-staff-event-appointment')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }
        if($request->has('newClassClient') && $request->newClassClient != ''){
          $request->classClients = $request->newClassClient;
        }
        if (!$isError) {
            if (isUserType(['Staff']) && Auth::user()->account_id != $request->staff) {
                $isError = true;
            } else if (!isUserType(['Staff']) && !Staff::ifstaffExist($request->staff)) {
                $isError = true;
            }

            if (!$isError) {
                $countOfAreasLinkedToStaff = $this->getCountOfAreasLinkedToStaff(['areaId' => $request->modalLocArea != ''?$request->modalLocArea:array(), 'staffId' => $request->staff]);
                if ($countOfAreasLinkedToStaff == count($request->modalLocArea)) {
                    $isAreaLinkedToStaff = true;
                } else {
                    $isAreaLinkedToStaff = false;
                }

                if ($isAreaLinkedToStaff) {
                    $startDate           = $this->calcEventDate($request, $request->classDate);
                    $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->classTime, 'startDate' => $startDate, 'duration' => $request->serviceDur]);
                    if (ClosedDate::ifOverlapping($startDate, $startAndEndDatetime['endDate'])) {
                        $msg['status']      = 'error';
                        $msg['errorData'][] = array('businessClosed' => '');
                        $isError            = true;
                    }

                    if (!$isError) {
                        $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                        $eventEndDatetime   = $startAndEndDatetime['endDatetime'];

                        //if($request->appointStatusOpt == 'Confirmed'){
                        $busyAreaIds = $this->areAreasBusy(['areaId' => $request->modalLocArea, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime]);
                        if (count($busyAreaIds)) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('areaUnavail' => $busyAreaIds);
                            $isError            = true;
                        } else if ($this->isStaffBusy(['staffId' => $request->staff, 'day' => $request->classDay, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime])) {

                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                            $isError            = true;
                        } else if (count($request->classClients)) {
                            foreach ($request->classClients as $key => $value) {
                                $clientIdsToCheck[] = $key;
                            }
                            $busyClientIds = $this->isClientBusy(['clientId' => $clientIdsToCheck, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime]);

                            if (count($busyClientIds)) {
                                $msg['status']      = 'error';
                                $msg['errorData'][] = array('clientUnavail' => $busyClientIds);
                                $isError            = true;
                            }
                        }
                        //}

                        if (!$isError) {
                            $eventClass                      = new StaffEventSingleService;
                            $eventClass->sess_business_id    = Session::get('businessId');
                            $eventClass->sess_date           = $startDate;
                            $eventClass->sess_time           = $request->classTime;
                            $eventClass->sess_start_datetime = $eventStartDatetime;
                            $eventClass->sess_end_datetime   = $eventEndDatetime;
                            //$eventClass->sess_notes = $request->classNote;
                            $eventClass->sess_staff_id = $request->staff;

                            $alertHistoryText = '';
                            if ( /*$request->has('salesProcessStepNumb') &&*/count($request->classClients)) {
                                foreach ($request->classClients as $key => $value) {
                                    $eventClass->sess_client_id    = $key;
                                    $eventClass->sess_client_notes = $value['note'];
                                }

                                $clients          = Clients::find($eventClass->sess_client_id);
                                $alertHistoryText = $this->sendClientServiceBookingEmail('confirm', $request, $clients);
                            } else {
                                $eventClass->sess_client_check = 1;
                            }

                            $clientDetails = Clients::paidMembership($eventClass->sess_client_id);
                            if ($clientDetails) {
                                $eventClass->sess_cmid = $clientDetails->id;
                            }

                            $eventClass->sess_booking_status = $request->appointStatusOpt;
                            if ($request->appointStatusOpt == 'Pencilled-In' && $request->ifAutoExpireAppoint == 1) {
                                if ($request->autoExpireAppointDur != 'Custom') {
                                    $eventClass->sess_auto_expire = $request->autoExpireAppointDur;
                                }

                                $eventClass->sess_auto_expire_datetime = $request->autoExpireDatetime;
                            }

                            $eventClass->sess_service_id = $request->staffservice;
                            $eventClass->sess_duration   = $request->serviceDur;
                            $eventClass->sess_price      = $request->servicePrice;
                            if ($request->has('salesProcessStepNumb')) {
                            $eventClass->sales_step_number = $request->salesProcessStepNumb;
                            }
                            if ($request->has('origEventId')) {
                                $eventClass->sess_is_make_up = 1;
                            }

                            Auth::user()->eventAppointments()->save($eventClass);

                            $logText = 'Service( Id:'.$eventClass->sess_id.', Date:'.$startDate.') added Manually';
                            setInfoLog($logText, $eventClass->sess_id);

                            $this->linkEventServiceResource($request, $eventClass->sess_id, 'add');

                            $this->newHistory(['eventType' => 'Service', 'event' => $eventClass]);
                            if ($alertHistoryText) {
                                $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);
                            }

                            $attachedArea = [];
                            foreach ($request->modalLocArea as $areaId) {
                                $attachedArea[$areaId] = ['sessa_business_id' => Session::get('businessId')];
                            }

                            if (count($attachedArea)) {
                                $eventClass->areas()->attach($attachedArea);
                            }

                            //$this->storeEventrepeatData($request, $eventClass);

                            if ($request->has('salesProcessStepNumb')) {
                                if (count($request->classClients) && $clients && (!$request->eventRepeat || $request->eventRepeat == 'None')) {
                                    //$eventClass->sess_booking_status == 'Confirmed' &&
       
                                    if (in_array($request->salesProcessStepNumb, teamBookingSteps()) || in_array($request->salesProcessStepNumb, indivBookingSteps())) {
                                        $this->manageSessionSalesProcess($clients);

                                        $msg['changeStatus']  = $this->getStatusForbackend($clients->account_status, true) . '|' . $clients->account_status;
                                        $msg['stepCompleted'] = $request->salesProcessStepNumb;
                                        $msg['bookingDate']   = dbDateToDateString($eventClass->EventDateCarbon);
                                    } else {
                                        $canLink = true;
                                        if ($request->salesProcessStepNumb == 4) {
                                            $canLink = true;
                                            if ($clients->consultation_date != null) {
                                                //Sales process has been consulted
                                                $eventDate        = new Carbon($eventClass->sess_date);
                                                $consultationDate = new Carbon($clients->consultation_date);
                                                if ($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))) {
                                                    //Event booking date is not less than Consultation date and not greater than Consultation end date(Consultation date + 15 days)
                                                    $canLink = true;
                                                }
                                            }
                                        }

                                        $salesProcessRelatedStatus = calcSalesProcessRelatedStatus((int) $request->salesProcessStepNumb);
                                       
                                        if ($canLink && $this->isStepEnabled($request->salesProcessStepNumb, $clients->SaleProcessEnabledSteps) && (($request->salesProcessStepNumb == 2 && $clients->account_status == 'Pending') || $request->salesProcessStepNumb == 4 || $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $eventClass->sess_client_id, $clients->SaleProcessEnabledSteps))) {

                                            /*if(statusMatchSalesStatus($clients->account_status, $salesProcessRelatedStatus['clientPrevStatus']) || ($request->salesProcessStepNumb == 2 && ($clients->account_status == 'Pending' /*|| $clients->account_status == 'Active Lead' || $clients->account_status == 'Inactive Lead'*))){*/
                                             
                                            $eventClass->sess_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
                                            $eventClass->save();

                                            if ($request->salesProcessStepNumb == 2) {
                                                if ( /*$request->salesProcessStepNumb == 2 && (*/$clients->account_status == 'Pending' /*|| $clients->account_status == 'Active Lead' || $clients->account_status == 'Inactive Lead')*/) {
                                                    $this->storeContactNote(['clientId' => $eventClass->sess_client_id, 'status' => 'contacted', 'contactResult' => 'book']);
                                                    $clients->account_status = clientStatusPrevSales($salesProcessRelatedStatus);
                                                    $this->saveSalesProgress(['clientId' => $clients->id, 'stepNumb' => 1]);
                                                }
                                                $clients->consultation_date = $eventClass->sess_date;
                                            }

                                            $clientOldSaleProcessStep   = $clients->sale_process_step;
                                            $clients->sale_process_step = $request->salesProcessStepNumb;
                                            $clients->save();

                                            if ($request->salesProcessStepNumb == 4) {
                                                $this->manageSessionSalesProcess($clients);
                                            }

                                            $salesProcessHistory = ['clientId' => $clients->id, 'eventId' => $eventClass->sess_id, 'toType' => $salesProcessRelatedStatus['salesProcessType'], 'toStep' => $salesProcessRelatedStatus['saleProcessStepNumb'], 'fromStep' => $clientOldSaleProcessStep, 'reason' => 'Service booked from sales process'];
                                            /*$msg['salesProcessDate'] =*/$this->saveSalesProcess($salesProcessHistory);

                                            $msg['salesProcessDate'] = $this->saveSalesProgress(['clientId' => $clients->id, 'stepNumb' => $request->salesProcessStepNumb, 'eventId' => $eventClass->sess_id]);

                                            StaffEventSingleService::deleteUnattendedSalesProcess(['clientId' => $eventClass->sess_client_id, 'saleProcessStatus' => [$salesProcessRelatedStatus['salesProcessType']]]);

                                            $msg['changeStatus']  = $this->getStatusForbackend($clients->account_status, true) . '|' . $clients->account_status;
                                            $msg['stepCompleted'] = $request->salesProcessStepNumb;
                                            $msg['bookingDate']   = dbDateToDateString($eventClass->EventDateCarbon);
                                          
                                           
                                        }
                                    }
                                }
                                $msg['stepsBooked'] = $this->getStepsBooked($clients->id, $clients->sale_process_step);
                            } else if ($request->has('origEventId')) {
                                StaffEventSingleService::withTrashed()->where('sess_id', $request->origEventId)->update(['sess_if_maked_up' => 1]);
                            }

                            $msg['status']  = 'added';
                            $msg['eventId'] = $eventClass->sess_id;
                            // $msg['message'] = displayAlert('success|Service has been saved successfully.');
                        }
                    }
                } else {
                    $msg['status']      = 'error';
                    $msg['errorData'][] = array('areaDoesnotLinkedToStaff' => $request->modalLocArea);
                    $isError            = true;
                }
            }

        }
        return json_encode($msg);
    }

    /**
     * Update service data
     * @param
     * @return
     */
    public function update(Request $request)
    { 
        $isError                  = $eventClass                  = $isEpicInvoice                  = false;
        $msg                      = [];
        $satisfyMembershipClients = [];
        $isEpicInvoiceAndCredit   = 0;
        $isBookedWithInvoice      = 0;
        $isBookedWithCredit       = 0;
        $remainingEpicCredit      = 0;

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-appointment')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }
        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $eventClass = StaffEventSingleService::OfStaff(Auth::user()->account_id)->find($request->eventId);
            } else {
                $eventClass = StaffEventSingleService::OfBusiness()->find($request->eventId);
            }
            if (count($eventClass)) {
                if ($request->step == 'classDetails') {

                    /*if ($request->field == 'duration' || $request->field == 'date' || $request->field == 'time') {*/
                    if ($request->field == 'duration' || $request->field == 'date' || $request->field == 'time' || $request->field == 'service') {
                        $startDate           = $this->calcEventDate($request, $request->classDate);
                        $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->classTime, 'startDate' => $startDate, 'duration' => $request->serviceDur]);
                        if (ClosedDate::ifOverlapping($startDate, $startAndEndDatetime['endDate'])) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('businessClosed' => '');
                            $isError            = true;
                        } else {
                            $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                            $eventEndDatetime   = $startAndEndDatetime['endDatetime'];
                        }
                    } else if ($request->field == 'status' || $request->field == 'staff' || $request->field == 'area' || $request->field == 'service') {
                        $eventStartDatetime = $eventClass->sess_start_datetime;
                        $eventEndDatetime   = $eventClass->sess_end_datetime;

                    }

                    if (!$isError) {
                        if(!$request->modalLocArea && !$request->staff) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('areaStaffUnavail' => []);
                            $isError            = true;

                            return json_encode($msg);
                        }

                        if(!$request->modalLocArea) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('areaUnavail' => []);
                            $isError            = true;

                             return json_encode($msg);
                        }

                        if(!$request->staff) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('staffUnavail' => []);
                            $isError            = true;

                            return json_encode($msg);
                        }

                        if (($request->field == 'duration' || $request->field == 'area' || $request->field == 'service' || $request->field == 'date' || $request->field == 'time' || $request->field == 'status') && $request->modalLocArea) {
                            $busyAreaIds = $this->areAreasBusy(['eventId' => $request->eventId, 'areaId' => $request->modalLocArea, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');
                            if (count($busyAreaIds)) {
                                $msg['status']      = 'error';
                                $msg['errorData'][] = array('areaUnavail' => $busyAreaIds);
                                $isError            = true;
                            }
                        }

                        if (($request->field == 'duration' || $request->field == 'staff' || $request->field == 'service' || $request->field == 'date' || $request->field == 'time' || $request->field == 'status') && $request->staff) {
                            if ($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $request->staff, 'day' => $request->classDay, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment')) {

                                $msg['status']      = 'error';
                                $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                                $isError            = true;
                            }
                        }
                    }
                } else if ($request->step == 'classClients') {
                    if ($eventClass) {
                        $checkMembershipRestriction = false;
                        $satisfyMembershipClients   = 0;
                        $lowEpicBalanceClients      = [];
                        $salesProgress = 0;
                      
                        foreach ($request->classClients as $key => $value) {
                            $clientIdsToCheck[] = $key;
                            $client             = Clients::find($key);
                            $salesProgressData=  SalesProcessProgress::where('spp_client_id', $key)->orderBy('spp_id','desc')->where('spp_comp_manual',0)->first();
                            // $client->salesProgress
                            $salesProcessStepNumb =$salesProgressData['spp_step_numb'] + 1;
                            

                            
                            $calendarData =  CalendarSetting::where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
                     

                            if($client->sale_process_setts == null || $client->sale_process_setts == ''){
                                $client->sale_process_setts = $calendarData->sales_process_settings;
                                $this->salesProcSettingsUpdate($key,$calendarData->sales_process_settings);
                                $client->refresh();
                             
                                $client->sale_process_setts = $calendarData->sales_process_settings;
                            }
                        
                            $clientStatus = json_decode($client->sale_process_setts,1);
                           if($client && in_array($client->account_status, ['Pre-Training', 'Pre-Benchmarking','Pre-Consultation']) &&   $salesProcessStepNumb == '2'){
                                $salesProgress = 1;

                            }else if($client && in_array($client->account_status, ['Pre-Training', 'Pre-Benchmarking','Pre-Consultation']) &&  in_array('4',$clientStatus['steps'])){
                                $salesProgress = 1;

                            }else if($client && in_array($client->account_status, ['Pre-Training', 'Pre-Benchmarking','Pre-Consultation']) &&  (in_array('17',$clientStatus['steps']))){
                                $indivCount = 0;
                                $indiv = indivBookingSteps();
                                $indived = indivAttendSteps();
                           
                            
                             foreach($client->SalesSessionOrder as $order){
                                $value = explode('-', $order); 
                                if($value[0] == 'indiv'){
                                    $stepNumb = $indiv[$indivCount];
                                    $attendNumb = $indived[$indivCount];
                                    $indivCount++;
                                
                                    if(!SalesProcessProgress::where('spp_client_id',  $key)->orderBy('spp_id','desc')->where('spp_step_numb',$attendNumb)->exists()){
                                        if(!SalesProcessProgress::where('spp_client_id',  $key)->orderBy('spp_id','desc')->where('spp_step_numb',$stepNumb)->exists()){
                                            $salesProcessStepNumb = $stepNumb;
                                            break;
                                        }
                                    }
                                    
                                   
                                }
                             }
                                $salesProgress = 1;
                               

                            }else if ($client && !in_array($client->account_status, ['Active', 'Contra']) && $client->epic_credit_balance < $eventClass->sess_price) {
                                if(!in_array($client->account_status, ['Pre-Training', 'Pre-Benchmarking','Pre-Consultation'])){
                                    $lowEpicBalanceClients[$key] = $client->epic_credit_balance;
                                }else{
                                    if($value['forceAdd'] != 'yes'){
                                        $lowEpicBalanceClients[$key] = $client->epic_credit_balance;
                                    }
                                }
                            } 
                            $checkMembershipRestriction = !(array_key_exists('forceAdd', $value) && $value['forceAdd']) && !(array_key_exists('isCreatingMakeUpSession', $value) && $value['isCreatingMakeUpSession']);
                        }

                        $busyClientIds = $this->isClientBusy(['eventId' => $request->eventId, 'clientId' => $clientIdsToCheck, 'startDatetime' => $eventClass->sess_start_datetime, 'endDatetime' => $eventClass->sess_end_datetime], 'appointment');
                       if (count($lowEpicBalanceClients) && $salesProgress == 0) {
                            $msg['status']       = 'error';
                            $msg['errorData'][]  = array('lowBalance' => 'You don\'t have sufficient credit balance available in your account.');
                            $msg['unsatisyReas'] = 'low_epic_balance';
                            $isError             = true;

                            return json_encode($msg);
                        } else if (count($busyClientIds) && $salesProgress == 0) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('clientUnavail' => $busyClientIds);
                            $isError            = true;
                        }
                     if ($checkMembershipRestriction && $value['forceAdd'] != 'yes' && !$value['isReducedRate'] && $salesProgress == 0) {

                            $membership = $this->satisfyMembershipRestrictions($clientIdsToCheck[0], ['event_type' => 'service', 'event_id' => $eventClass->sess_service_id, 'event_date' => $eventClass->sess_date]);

                            if ($membership['satisfy']) {
                                $satisfyMembershipClients = $clientIdsToCheck[0];
                            }else if(isset($value['isLdc']) && $value['isLdc'] == 1){
                                $satisfyMembershipClients = $clientIdsToCheck[0];
                            } else {
                                $msg['status']       = 'error';
                                $msg['errorData'][]  = array('membershipUnsatisfy' => $clientIdsToCheck[0]);
                                $msg['unsatisyReas'] = "limit_exceeded";
                                $isError             = true;
                            }
                        }
                    }
                }

                if (!$isError) {
                    $alertHistoryText = $historyText = '';
                    $eventRecurData   = $targetEventData   = [];

                    if ($request->step == 'classDetails') {
                        $prevStartDateTime = $eventClass->sess_start_datetime;
                        if ($request->field == 'status') {
                            $eventClass->sess_booking_status = $request->appointStatusOpt;
                            if ($request->appointStatusOpt == 'Confirmed') {
                                $eventClass->sess_auto_expire                 = '';
                                $eventClass->sess_auto_expire_datetime        = null;
                                $targetEventData['sess_auto_expire']          = '';
                                $targetEventData['sess_auto_expire_datetime'] = null;
                                $eventRecurData['sessr_auto_expire']          = '';
                            }
                        }

                        if ($request->field == 'autoExpire' || $request->field == 'autoExpireClr') {
                            $eventClass->sess_auto_expire                 = '';
                            $eventClass->sess_auto_expire_datetime        = null;
                            $targetEventData['sess_auto_expire']          = '';
                            $targetEventData['sess_auto_expire_datetime'] = null;
                            $eventRecurData['sessr_auto_expire']          = '';

                            if ($request->ifAutoExpireAppoint == 1) {
                                if ($request->autoExpireAppointDur != 'Custom') {
                                    $eventClass->sess_auto_expire        = $request->autoExpireAppointDur;
                                    $targetEventData['sess_auto_expire'] = $request->autoExpireAppointDur;
                                    $eventRecurData['sessr_auto_expire'] = $request->autoExpireAppointDur;
                                }

                                $eventClass->sess_auto_expire_datetime        = $request->autoExpireDatetime;
                                $targetEventData['sess_auto_expire_datetime'] = $request->autoExpireDatetime;
                            }
                        }

                        if ($request->field == 'service') {
                            $eventClass->sess_service_id        = $request->staffservice;
                            $targetEventData['sess_service_id'] = $request->staffservice;
                            $eventRecurData['sessr_service_id'] = $request->staffservice;
                        }

                        if ($request->field == 'service' || $request->field == 'duration') {
                            if ($request->serviceDur) {
                                $eventClass->sess_duration        = $request->serviceDur;
                                $targetEventData['sess_duration'] = $request->serviceDur;
                                $eventRecurData['sessr_duration'] = $request->serviceDur;
                            }
                        }

                        if ($request->field == 'service' || $request->field == 'price') {
                            if ($request->servicePrice != '') {
                                $eventClass->sess_price        = $request->servicePrice;
                                $targetEventData['sess_price'] = $request->servicePrice;
                                $eventRecurData['sessr_price'] = $request->servicePrice;
                                $efectiveEventIds[]            = $eventClass->sess_id;
                            }
                        }

                        if ($request->field == 'date' || $request->field == 'time' || $request->field == 'service') {
                            $eventClass->sess_date        = $startDate;
                            $eventClass->sess_time        = $request->classTime;
                            $targetEventData['sess_time'] = $request->classTime;

                            if ($eventClass->sess_auto_expire && $eventClass->sess_auto_expire_datetime != null) {
                                $eventClass->sess_auto_expire_datetime = $request->autoExpireDatetime;
                            }
                        }

                        /* if ($request->field == 'date' || $request->field == 'time' || $request->field == 'duration' ) {*/
                        if ($request->field == 'date' || $request->field == 'time' || $request->field == 'duration' || $request->field == 'service') {
                            $eventClass->sess_start_datetime    = $eventStartDatetime;
                            $eventClass->sess_end_datetime      = $eventEndDatetime;
                            $eventRecurData['sessr_start_time'] = Carbon::parse($eventStartDatetime)->format('H:i:s');
                            $eventRecurData['sessr_end_time']   = Carbon::parse($eventEndDatetime)->format('H:i:s');
                            $eventRecurence = $eventClass->repeat()->first();
                            if ($eventRecurence->sessr_repeat == 'Weekly') {
                                $days                                     = [Carbon::parse($eventStartDatetime)->format('D')];
                                $eventRecurData['sessr_repeat_week_days'] = json_encode($days);
                            }
                        }

                        if (($request->field == 'duration' || $request->field == 'staff' || $request->field == 'service' || $request->field == 'date' || $request->field == 'time') && $request->staff && $request->staff != $eventClass->sec_staff_id) {
                            $eventClass->sess_staff_id        = $request->staff;
                            $eventRecurData['sessr_staff_id'] = $eventClass->sess_staff_id;
                            $targetEventData['sess_staff_id'] = $eventClass->sess_staff_id;
                        }

                        $existArea = $eventClass->areas()->pluck('sessa_la_id')->toArray();
                        if (($request->field == 'duration' || $request->field == 'area' || $request->field == 'service' || $request->field == 'date' || $request->field == 'time') && $request->modalLocArea && (!custom_arr_comp($request->modalLocArea, $existArea))) {

                            /* start:Area manage section */
                            $response = $this->manageAreasLinkage($eventClass, $request->modalLocArea);
                            $historyText .= $response['history'];
                            /* end:Area manage section */
                            $eventRecurData['sessr_area_id'] = implode(',', $request->modalLocArea);
                            $targetEventData['areaId']       = $request->modalLocArea;
                        }

                        if ($request->field == 'duration' || $request->field == 'service' || $request->field == 'date' || $request->field == 'time' || $request->field == 'resource' || $request->field == 'resourceRemove') {
                            $this->linkEventServiceResource($request, $eventClass->sess_id, 'edit');
                            $targetEventData['resource'] = 'edit';
                        }

                        $eventClass->update();
                        $historyText .= $request->historyText;

                        /* if event is this and future */
                        if ($request->has('targetEvents') && $request->targetEvents == 'future') {
                            /* Save Recurence updated data */
                            if ($eventClass->sess_sessr_id != 0) {
                                $eventRecurence = $eventClass->repeat()->first();
                                if (count($eventRecurence)) {
                                    if ($request->field == 'date' || (Carbon::parse($eventStartDatetime)->format('D') != Carbon::parse($prevStartDateTime)->format('D'))) {
                                        if ($eventRecurence->sessr_repeat == 'Weekly') {
                                            $days = Carbon::parse($eventStartDatetime)->format('D');
                                            $prevDay = Carbon::parse($prevStartDateTime)->format('D');
                                            $repeatWeekDays = json_decode($eventRecurence->sessr_repeat_week_days);
                                            $key = array_search($prevDay, $repeatWeekDays);
                                            if (false !== $key) {
                                                unset($repeatWeekDays[$key]);
                                            }
                                            $totalDays = array_merge($repeatWeekDays, [$days]);
                                            $eventRecurData['sessr_repeat_week_days'] = json_encode($totalDays);
                                        }
                                    }
                                    if (isset($targetEventData['resource'])) {
                                        $eventRecurData['sessr_resources'] = $this->resourceRecurData($request);
                                    }

                                    if (count($eventRecurData)) {
                                        $eventRecurence->update($eventRecurData);
                                    }
                                    if ($request->field == 'date' || (Carbon::parse($eventStartDatetime)->format('D') != Carbon::parse($prevStartDateTime)->format('D'))) {
                                        StaffEventSingleService::where('sess_sessr_id', $eventClass->sess_sessr_id)->whereDate('sess_date', '>', $eventClass->sess_date)->forcedelete();
                                        /*Reset Memebership limit*/
                                        $this->membershipLimitReset($eventClass->sess_client_id);
                                    }
                                }

                                $associatedEvents = StaffEventSingleService::where('sess_sessr_id', $eventClass->sess_sessr_id)->whereDate('sess_date', '>', $eventClass->sess_date)->get();

                                if ($associatedEvents->count()) {
                                    
                                    foreach ($associatedEvents as $associatedEvent) {
                                        if (isset($targetEventData['areaId']) && count($targetEventData['areaId'])) {
                                            $this->manageAreasLinkage($associatedEvent, $targetEventData['areaId']);
                                        }

                                        if (isset($targetEventData['resource'])) {
                                            $this->linkEventServiceResource($request, $associatedEvent->sess_id, 'edit');
                                        }

                                        if (isset($targetEventData['sess_time'])) {
                                            $startEndtimeStamp                      = $this->calcStartAndEndDatetime(['startTime' => $targetEventData['sess_time'], 'startDate' => $associatedEvent->sess_date, 'duration' => $associatedEvent->sess_duration]);
                                            $targetEventData['sess_start_datetime'] = $startEndtimeStamp['startDatetime'];
                                            $targetEventData['sess_end_datetime']   = $startEndtimeStamp['endDatetime'];
                                        }

                                        if (isset($targetEventData['sess_duration'])) {
                                            $startEndtimeStamp                      = $this->calcStartAndEndDatetime(['startTime' => $associatedEvent->sess_time, 'startDate' => $associatedEvent->sess_date, 'duration' => $targetEventData['sess_duration']]);
                                            $targetEventData['sess_start_datetime'] = $startEndtimeStamp['startDatetime'];
                                            $targetEventData['sess_end_datetime']   = $startEndtimeStamp['endDatetime'];
                                        }
                                        if (count($targetEventData)) {
                                            $associatedEvent->update($targetEventData);
                                        }

                                        /* invoice update */
                                        $efectiveEventIds[] = $associatedEvent->sess_id;
                                    }
                                }
                            }
                        }

                        /* Update this and future event price in invoice*/
                        if (($request->field == 'service' || $request->field == 'price') && $request->servicePrice != '') {
                            $effectiveData                   = array();
                            $effectiveData['targetEventids'] = $efectiveEventIds;
                            $effectiveData['date']           = $request->classDate;
                            $effectiveData['price']          = $request->servicePrice;
                            $effectiveData['type']           = 'serviceEvent';
                            $this->updateEffectivePrice($eventClass->sec_id, $effectiveData);
                        }
                    } else if ($request->step == 'classReccur' && $request->eventRepeat != '') {
                        // dd('Recure');
                        // dd($eventClass->toArray());
                        if ($request->eventRepeat == 'None') {
                            // repeat is none
                            if ($eventClass->sess_sessr_id != 0) {
                                $oldEventRecurence = $eventClass->repeat()->first();
                                if (count($oldEventRecurence)) {
                                    StaffEventSingleService::where('sess_sessr_id', $oldEventRecurence['sessr_id'])->whereDate('sess_date', '>', $eventClass->sess_date)->forcedelete();
                                    $oldEventRecurence->delete();
                                }
                            }
                        } else {
                            // repeat is daily, weekely, monthaly
                            if ($eventClass->sess_sessr_id != 0) {
                                $oldEventRecurence = $eventClass->repeat()->first();
                            }

                            //dd( $oldEventRecurence->toArray());
                            if ($eventClass->sess_sessr_id != 0 && count($oldEventRecurence)) {
                                $newEventRecurence = $oldEventRecurence->replicate();
                               //  dd($newEventRecurence->toArray());
                                StaffEventSingleService::where('sess_sessr_id', $oldEventRecurence['sessr_id'])->whereDate('sess_date', '>', $eventClass->sess_date)->forcedelete();

                                /*Reset Memebership limit*/
                                $this->membershipLimitReset($eventClass->sess_client_id);

                                $oldEventRecurence->delete();

                                $noOfChild = StaffEventSingleService::where('sess_sessr_id', $oldEventRecurence['sessr_id'])->count() - 1;
                            } else {
                                $newEventRecurence                    = new StaffEventSingleServiceRepeat;
                                $newEventRecurence->sessr_business_id = Session::get('businessId');
                                $noOfChild                            = 0;
                            }
                            $clientDetails = Clients::paidMembership($eventClass->sess_client_id);
                            if($clientDetails){
                                $service_limits = json_decode($clientDetails->cm_services_limit, 1);
                                if(array_key_exists($eventClass->sess_service_id, $service_limits))
                                    $eventClass->sess_price = $service_limits[$eventClass->sess_service_id]['mem_unit_price'];
                            }
                            $newEventRecurence->sessr_repeat          = $request->eventRepeat;
                            $newEventRecurence->sessr_repeat_interval = $request->eventRepeatInterval;
                            $newEventRecurence->sessr_child_count     = $noOfChild;
                            $newEventRecurence->sessr_staff_id        = $eventClass->sess_staff_id;
                            $newEventRecurence->sessr_start_time      = Carbon::parse($eventClass->sess_start_datetime)->format('H:i:s');
                            $newEventRecurence->sessr_end_time        = Carbon::parse($eventClass->sess_end_datetime)->format('H:i:s');
                            $newEventRecurence->sessr_service_id      = $eventClass->sess_service_id;
                            $newEventRecurence->sessr_duration        = $eventClass->sess_duration;
                            $newEventRecurence->sessr_price           = $eventClass->sess_price;
                            $newEventRecurence->sessr_repeat_end      = $request->eventRepeatEnd;
                            $newEventRecurence->sessr_client_id       = $eventClass->sess_client_id;
                            $newEventRecurence->sessr_with_invoice    = $eventClass->sess_with_invoice;
                            $newEventRecurence->sessr_booking_status  = $eventClass->sess_booking_status;
                            $newEventRecurence->sessr_auto_expire     = $eventClass->sess_auto_expire;
                            $newEventRecurence->sessr_resources       = $eventClass->existResourceRecurData();

                            if ($request->eventRepeat == 'Weekly') {
                                $newEventRecurence->sessr_repeat_week_days = json_encode($request->eventRepeatWeekdays);
                            } else {
                                $newEventRecurence->sessr_repeat_week_days = '';
                            }

                            if ($request->eventRepeatEnd == 'After') {
                                $newEventRecurence->sessr_repeat_end_after_occur = $request->eventRepeatEndAfterOccur;
                                $newEventRecurence->sessr_repeat_end_on_date     = null;
                            } elseif ($request->eventRepeatEnd == 'On') {
                                $newEventRecurence->sessr_repeat_end_after_occur = 0;
                                $newEventRecurence->sessr_repeat_end_on_date     = $request->eventRepeatEndOnDate;
                            } elseif ($request->eventRepeatEnd == 'Never') {
                                $newEventRecurence->sessr_repeat_end_after_occur = 0;
                                $newEventRecurence->sessr_repeat_end_on_date     = null;
                            }

                            $eventsAreas = $eventClass->areas->pluck('la_id')->toArray();
                            if (count($eventsAreas)) {
                                $newEventRecurence->sessr_area_id = implode(',', $eventsAreas);
                            } else {
                                $newEventRecurence->sessr_area_id = '';
                            }
                            $newEventRecurence->save();
                        }
                        //dd($newEventRecurence->toArray());
                       

                        // dd($newEventRecurence->toArray());
                        $eventClass->sess_sessr_id = $newEventRecurence->sessr_id;
                        $eventClass->update();
                        $msg['repeatId'] = $newEventRecurence->sessr_id;
                    } else if ($request->step == 'classClients') {
                        foreach ($request->classClients as $key => $value) {
                            $isEpicInvoice     = $isEpicCredit     = 0;
                            $remainingClassAmt = 0;

                            $eventClass->sess_client_id    = $key;
                            $eventClass->sess_client_notes = $value['note'];

                            $client = Clients::find($key);
                            if ($client && !in_array($client->account_status, ['Active', 'Contra'])) {
                                /* Start: For Pending,Active lead,Inactive lead, Pre cons,Pre bench, Pre train, In active, On hold clients */
                                /* Start: For Pending,Active lead,Inactive lead, Pre cons,Pre bench, Pre train, In active, On hold clients */
                             
                                if($client && in_array($client->account_status, ['Pre-Training', 'Pre-Benchmarking','Pre-Consultation']) && $salesProgress == 1){
                                    if (count($request->classClients) && $client && (!$request->eventRepeat || $request->eventRepeat == 'None')) {
                                        //$eventClass->sess_booking_status == 'Confirmed' &&
                                        $eventClass->sales_step_number = $salesProcessStepNumb;
                                        if (in_array($salesProcessStepNumb, teamBookingSteps()) || in_array($salesProcessStepNumb, indivBookingSteps())) {
                                         
                                            $this->manageSessionSalesProcess($client);
    
                                            $msg['changeStatus']  = $this->getStatusForbackend($client->account_status, true) . '|' . $client->account_status;
                                            $msg['stepCompleted'] = $salesProcessStepNumb;
                                            $msg['bookingDate']   = dbDateToDateString($eventClass->EventDateCarbon);
                                        } else {
                                            $canLink = true;
                                            if ($salesProcessStepNumb == 4) {
                                                $canLink = false;
                                                if ($client->consultation_date != null) {
                                                    //Sales process has been consulted
                                                    $eventDate        = new Carbon($eventClass->sess_date);
                                                    $consultationDate = new Carbon($client->consultation_date);
                                                    if ($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))) {
                                                        //Event booking date is not less than Consultation date and not greater than Consultation end date(Consultation date + 15 days)
                                                        $canLink = true;
                                                    }
                                                }
                                            }
    
                                            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus((int) $salesProcessStepNumb);
                                            
                                            if ($canLink && $this->isStepEnabled($salesProcessStepNumb, $client->SaleProcessEnabledSteps) && (($salesProcessStepNumb == 2 && $client->account_status == 'Pending') || $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $eventClass->sess_client_id, $client->SaleProcessEnabledSteps))) {
    
                                                /*if(statusMatchSalesStatus($clients->account_status, $salesProcessRelatedStatus['clientPrevStatus']) || ($request->salesProcessStepNumb == 2 && ($clients->account_status == 'Pending' /*|| $clients->account_status == 'Active Lead' || $clients->account_status == 'Inactive Lead'*))){*/
                                                
                                                  
                                                $eventClass->sess_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
                                                $eventClass->save();
                                               
                                                if ($salesProcessStepNumb == 2) {
                                                    if ( /*$request->salesProcessStepNumb == 2 && (*/$client->account_status == 'Pending' /*|| $clients->account_status == 'Active Lead' || $clients->account_status == 'Inactive Lead')*/) {
                                                        $this->storeContactNote(['clientId' => $eventClass->sess_client_id, 'status' => 'contacted', 'contactResult' => 'book']);
                                                        $client->account_status = clientStatusPrevSales($salesProcessRelatedStatus);
                                                        $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => 1]);
                                                    }else{
                                                    $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => 2]);
                                                    }
                                                    $client->consultation_date = $eventClass->sess_date;
                                                }
    
                                                $clientOldSaleProcessStep   = $client->sale_process_step;
                                                $client->sale_process_step = $salesProcessStepNumb;
                                                $client->save();
                                               
                                                if ($salesProcessStepNumb == 4) {
                                                    $this->manageSessionSalesProcess($client);
                                                }
                                             
                                                $salesProcessHistory = ['clientId' => $client->id, 'eventId' => $eventClass->sess_id, 'toType' => $salesProcessRelatedStatus['salesProcessType'], 'toStep' => $salesProcessRelatedStatus['saleProcessStepNumb'], 'fromStep' => $clientOldSaleProcessStep, 'reason' => 'Service booked from sales process'];
                                                // dd( $salesProcessRelatedStatus['saleProcessStepNumb'], $clientOldSaleProcessStep,$salesProcessRelatedStatus['salesProcessType']);

                                                // $msg['salesProcessDate'] =$this->saveSalesProcess($salesProcessHistory);
                                                // $msg['salesProcessDate'] = $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => $salesProcessStepNumb, 'eventId' => $eventClass->sess_id]);
                                               
                                                StaffEventSingleService::deleteUnattendedSalesProcess(['clientId' => $eventClass->sess_client_id, 'saleProcessStatus' => [$salesProcessRelatedStatus['salesProcessType']]]);
                                             
                                                $msg['changeStatus']  = $this->getStatusForbackend($client->account_status, true) . '|' . $client->account_status;
                                                $msg['stepCompleted'] = $salesProcessStepNumb;
                                                $msg['bookingDate']   = dbDateToDateString($eventClass->EventDateCarbon);
                                               
                                            }
                                        }
                                    }
                                   
                                    $msg['stepsBooked'] = $this->getStepsBooked($client->id, $client->sale_process_step);
                                  
                                    
                                }else if($client && in_array($client->account_status, ['Pre-Training', 'Pre-Benchmarking','Pre-Consultation'])){
                                    if (($value['isCreatingMakeUpSession'] || $value['forceAdd'] == 'yes') && $client->epic_credit_balance > 0) {
                                        # Get user id
                                        $userId = User::where('account_id', $key)
                                            ->pluck('id')
                                            ->first();

                                        if ($client->epic_credit_balance < $eventClass->sess_price) {

                                            $isEpicInvoice          = 1;
                                            $isEpicCredit           = 1;
                                            $isEpicInvoiceAndCredit = 1;

                                            $eventClass->sess_with_invoice = 1;
                                            $eventClass->sess_epic_credit  = 1;
                                            $eventClass->sess_event_log = 'Booked with epic credit and invoice';
                                            $eventClass->sess_action_performed_by = getLoggedUserName();

                                            $remainingClassAmt = $eventClass->sess_price - $client->epic_credit_balance;

                                            $classEventMakeup                       = new Makeup();
                                            $classEventMakeup->makeup_user_id       = $classEventMakeup->UserInformation['id'];
                                            $classEventMakeup->makeup_user_name     = $classEventMakeup->UserInformation['name'];
                                            $classEventMakeup->makeup_client_id     = $key;
                                            $classEventMakeup->makeup_notes_id      = 0;
                                            $classEventMakeup->makeup_purpose       = 'service';
                                            $classEventMakeup->makeup_session_count = 0;
                                            $classEventMakeup->makeup_amount        = -($client->epic_credit_balance);
                                            $classEventMakeup->makeup_extra         = 'EPIC Credit for <a href="' . url('/') . '/dashboard/calendar-new?mevid=' . $eventClass->sess_id . '&mevtype=service">' . date('D, d M Y H:i A', strtotime($eventClass->sess_start_datetime)) . '</a> service';
                                            $classEventMakeup->makeup_total_amount  = 0.00;
                                            $classEventMakeup->save();

                                            $this->setEpicBalance($client->id);

                                            $invResponse = $this->raiseAnInvoice($eventClass, $client->id, false, 0, '', abs($remainingClassAmt));

                                            /* Start: Invoice partially paid using epic credit*/
                                            $paymentData['totalAmount'] = $eventClass->sess_price;
                                            $paymentData['payAmount'] = $client->epic_credit_balance;
                                            $paymentData['outStandingAmount'] = $eventClass->sess_price - $client->epic_credit_balance;
                                            $paymentData['paymentType'] = 'EPIC Credit';
                                            $isPaymentComplete = $this->autoInvoicePayment($invResponse['invoiceId'], $paymentData);
                                            /* End: Invoice partially paid using epic credit*/
                                        } else {
                                            $eventClass->sess_with_invoice = 0;
                                            $eventClass->sess_epic_credit  = 1;
                                            $eventClass->sess_event_log = 'Booked with epic credit';
                                            $eventClass->sess_action_performed_by = getLoggedUserName();

                                            $isEpicInvoice                 = 0;
                                            $msg['isClientMakeup']         = false;
                                            $isEpicCredit                  = 1;
                                            $isBookedWithCredit            = 1;
                                            $remainingEpicCredit = $client->epic_credit_balance - $eventClass->sess_price;

                                            /* Raise invoice for service booked with epic credit.*/
                                            $invResponse = $this->raiseAnInvoice($eventClass, $client->id, true, 0, '', $eventClass->sess_price);
                                        }
                                    } else {
                                        $isBookedWithInvoice = 1;

                                        $invResponse = $this->raiseAnInvoice($eventClass, $key, false);
                                        if ($invResponse['status'] == 'invoice_exist') {
                                            $msg['invExistId'] = $invResponse['invoiceId'];
                                        } elseif ($invResponse['status'] == 'new_invoice') {
                                            $msg['invDue'] = $eventClass->sess_date;
                                        }
                                        $eventClass->sess_with_invoice = 1;
                                        $eventClass->sess_epic_credit  = 0;
                                        $eventClass->sess_event_log = 'Booked with invoice';
                                        $eventClass->sess_action_performed_by = getLoggedUserName();
                                    }
                                }else{
                                    $eventClass->sess_with_invoice = 0;
                                    $eventClass->sess_epic_credit  = 1;
                                    $isEpicInvoice                 = 0;
                                    $msg['isClientMakeup']         = false;
                                    $isEpicCredit                  = 1;
                                    $isBookedWithCredit            = 1;
                                    $remainingEpicCredit = $client->epic_credit_balance - $eventClass->sess_price;

                                    /* Raise invoice for service booked with epic credit.*/
                                    $invResponse = $this->raiseAnInvoice($eventClass, $client->id, true, 0, '', $eventClass->sess_price);
                                }
                            } else {
                                /* Start: For Contra, Active clients */
                                $membership = $this->satisfyMembershipRestrictions($clientIdsToCheck[0], ['event_type' => 'service', 'event_id' => $eventClass->sess_service_id, 'event_date' => $eventClass->sess_date]);

                                if (!$membership['satisfy'] || $value['forceAdd'] == 'yes' && !$value['isReducedRate']) {
                                    if(isset($value['isLdc']) && $value['isLdc'] == 1){
                                        $clientDetails = Clients::paidMembership($eventClass->sess_client_id);
                                        $eventClass->sess_cmid         = $clientDetails->id;
                                        $eventClass->sess_with_invoice = 0;
                                        $eventClass->sess_epic_credit  = 0;
                                        $eventClass->is_ldc  = 1;
                                        $eventClass->sess_event_log = 'Booked with epic credit and invoice';
                                        $eventClass->sess_action_performed_by = getLoggedUserName();
                                    }else{                                    
                                        $client = Clients::where('id', $key)
                                        ->select('id', 'firstname', 'lastname', 'epic_credit_balance')
                                        ->first();
                                    // $value['ifRecur'] == 0 &&
                                    if (($value['isCreatingMakeUpSession'] || $value['forceAdd'] == 'yes') && $client->epic_credit_balance > 0) {
                                        # Get user id
                                        $userId = User::where('account_id', $key)
                                            ->pluck('id')
                                            ->first();

                                        if ($client->epic_credit_balance < $eventClass->sess_price) {

                                            $isEpicInvoice          = 1;
                                            $isEpicCredit           = 1;
                                            $isEpicInvoiceAndCredit = 1;

                                            $eventClass->sess_with_invoice = 1;
                                            $eventClass->sess_epic_credit  = 1;
                                            $eventClass->sess_event_log = 'Booked with epic credit and invoice';
                                            $eventClass->sess_action_performed_by = getLoggedUserName();

                                            $remainingClassAmt = $eventClass->sess_price - $client->epic_credit_balance;

                                            $classEventMakeup                       = new Makeup();
                                            $classEventMakeup->makeup_user_id       = $classEventMakeup->UserInformation['id'];
                                            $classEventMakeup->makeup_user_name     = $classEventMakeup->UserInformation['name'];
                                            $classEventMakeup->makeup_client_id     = $key;
                                            $classEventMakeup->makeup_notes_id      = 0;
                                            $classEventMakeup->makeup_purpose       = 'service';
                                            $classEventMakeup->makeup_session_count = 0;
                                            $classEventMakeup->makeup_amount        = -($client->epic_credit_balance);
                                            $classEventMakeup->makeup_extra         = 'EPIC Credit for <a href="' . url('/') . '/dashboard/calendar-new?mevid=' . $eventClass->sess_id . '&mevtype=service">' . date('D, d M Y H:i A', strtotime($eventClass->sess_start_datetime)) . '</a> service';
                                            $classEventMakeup->makeup_total_amount  = 0.00;
                                            $classEventMakeup->save();

                                            $this->setEpicBalance($client->id);

                                            $invResponse = $this->raiseAnInvoice($eventClass, $client->id, false, 0, '', abs($remainingClassAmt));

                                            /* Start: Invoice partially paid using epic credit*/
                                            $paymentData['totalAmount'] = $eventClass->sess_price;
                                            $paymentData['payAmount'] = $client->epic_credit_balance;
                                            $paymentData['outStandingAmount'] = $eventClass->sess_price - $client->epic_credit_balance;
                                            $paymentData['paymentType'] = 'EPIC Credit';
                                            $isPaymentComplete = $this->autoInvoicePayment($invResponse['invoiceId'], $paymentData);
                                            /* End: Invoice partially paid using epic credit*/
                                        } else {
                                            $eventClass->sess_with_invoice = 0;
                                            $eventClass->sess_epic_credit  = 1;
                                            $eventClass->sess_event_log = 'Booked with epic credit';
                                            $eventClass->sess_action_performed_by = getLoggedUserName();

                                            $isEpicInvoice                 = 0;
                                            $msg['isClientMakeup']         = false;
                                            $isEpicCredit                  = 1;
                                            $isBookedWithCredit            = 1;
                                            $remainingEpicCredit = $client->epic_credit_balance - $eventClass->sess_price;

                                            /* Raise invoice for service booked with epic credit.*/
                                            $invResponse = $this->raiseAnInvoice($eventClass, $client->id, true, 0, '', $eventClass->sess_price);
                                        }
                                    } else {
                                        $isBookedWithInvoice = 1;

                                        $invResponse = $this->raiseAnInvoice($eventClass, $key, false);
                                        if ($invResponse['status'] == 'invoice_exist') {
                                            $msg['invExistId'] = $invResponse['invoiceId'];
                                        } elseif ($invResponse['status'] == 'new_invoice') {
                                            $msg['invDue'] = $eventClass->sess_date;
                                        }
                                        $eventClass->sess_with_invoice = 1;
                                        $eventClass->sess_epic_credit  = 0;
                                        $eventClass->sess_event_log = 'Booked with invoice';
                                        $eventClass->sess_action_performed_by = getLoggedUserName();
                                    }
                                }
                                } else {
                                    $clientDetails = Clients::paidMembership($eventClass->sess_client_id);
                                    if($clientDetails){
                                        $service_limits = json_decode($clientDetails->cm_services_limit, 1);
                                        if(array_key_exists($eventClass->sess_service_id, $service_limits))
                                            $eventClass->sess_price = $service_limits[$eventClass->sess_service_id]['mem_unit_price'];
                                    }
                                    if ($clientDetails) {
                                        $eventClass->sess_cmid         = $clientDetails->id;
                                        $eventClass->sess_with_invoice = 0;
                                        $eventClass->sess_epic_credit  = 0;
                                        $eventClass->sess_event_log = 'Booked with membership';
                                        $eventClass->sess_action_performed_by = getLoggedUserName();
                                    }
                                }
                            }
                        }
                    //  dd($eventClass);
                        $eventClass->update();
                        $clients   = Clients::find($eventClass->sess_client_id);
                      
                        $salesStep = $this->canLinkSalesProcess($eventClass, $clients);
                      
                        /* Update Client memmbership limit */
                        if ($satisfyMembershipClients) {
                            # Set info log
                            setInfoLog('Client membership limit updated on service booked with membership ',  $key);
                            $this->updateClientMembershipLimit($satisfyMembershipClients, [$eventClass->sess_date], ['type' => 'service', 'action' => 'add', 'eventId' => $eventClass->sess_service_id, 'limit_type' => $membership['limit_type']]);
                        }

                        $additionalHistoryText = '';
                        if ($isEpicInvoiceAndCredit) {
                            $additionalHistoryText = ' with invoice and epic credit';
                        } else if ($isBookedWithCredit) {
                            $additionalHistoryText = ' with epic credit';
                        } else if ($isBookedWithInvoice) {
                            $additionalHistoryText = ' with invoice';
                        }else if (isset($value['isLdc']) && $value['isLdc'] == 1){
                                $additionalHistoryText = ' with LDC Class';
                         }

                        
                         $historyText .= $this->eventclassClientHistory(['clients' => [$client], 'action' => 'add', 'additional' => $additionalHistoryText]);

                        $dataForEmail                     = new stdClass();
                        $dataForEmail->eventDateTimeEmail = dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $eventClass->sess_start_datetime));
                        $allPrevAreas                     = $eventClass->areas;
                        if ($allPrevAreas->count()) {
                            $allPrevAreasId = $allPrevAreas->pluck('la_id')->toArray();
                        } else {
                            $allPrevAreasId = [];
                        }

                        $dataForEmail->modalLocArea = $allPrevAreasId;
                        $dataForEmail->staffservice = $eventClass->sess_service_id;
                        $dataForEmail->staff        = $eventClass->sess_staff_id;
                        $alertHistoryText .= $this->sendClientServiceBookingEmail('confirm', $dataForEmail, $clients);

                        $msg['clientStatus'] = 'Confirm';
                      
                        if ($salesStep && $salesProcessStepNumb != 4) {
                            $this->linkSalesProcess($eventClass, $clients, $salesStep);
                        }

                    } else if ($request->step == 'classNotes') {
                        $eventClass->sess_notes = $request->classNote;
                        $eventClass->update();
                    }
                    if ($historyText) {
                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);
                    }

                    if ($alertHistoryText) {
                        $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);
                    }
                    if($salesProgress == 1){
                        $msg['status']  = 'added';
                        $msg['message'] = "Your service is booked with Sales Process.";
                    }
                    else if ($isEpicInvoiceAndCredit) {
                        $msg['status']  = 'added';
                        $msg['message'] = "Your service is booked with Epic Credit and Invoice.\n Epic Credit Used: $" . number_format($eventClass->sess_price - $remainingClassAmt, 2) . "\nInvoice Amount: $" . number_format($remainingClassAmt, 2) . "\n You need to pay invoice to start the service.";
                    } else if ($isBookedWithCredit) {
                        $msg['status']  = 'added';
                        $msg['message'] = "Service has been booked successfully using epic credit \n Service Price: $" . number_format($eventClass->sess_price, 2) . "\n Remaining Epic Credit: $" . number_format($remainingEpicCredit, 2);
                    } else if ($isBookedWithInvoice) {
                        $msg['status']  = 'added';
                        $msg['message'] = "Your service is booked with invoice. \n You need to pay the invoice to start the service.";
                    } else if (isset($value['isLdc']) && $value['isLdc'] == 1){
                        $msg['status']  = 'added';
                        $msg['message'] = "Your Service is booked with LDC.";
                    }else {
                        $msg['status']  = 'added';
                        $msg['message'] = 'Service has been saved successfully.';
                    }
                }
            }
        }
        return json_encode($msg);
    }

    /**
     * Service Destroy
     *
     * @param
     * @return Response
     */
    public function destroy(Request $request)
    {
        $isError = false;
        $additionalHistoryText = '';
        $msg     = [];
        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'delete-staff-event-appointment')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }
        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $event = StaffEventSingleService::OfStaff(Auth::user()->account_id)->find($request->eventId);
            } else {
                $event = StaffEventSingleService::OfBusiness()->find($request->eventId);
            }
            if (!$event) {
                $msg['status']       = 'error';
                $msg['errorData'][]  = array('clientNotFount' => $request->clientId);
                $msg['unsatisyReas'] = 'client_not_found';
                $isError             = true;
                return json_encode($msg);
            }
            // dd( $event->toArray() );
            if ($event) {
                if ($request->targetEvents != 'future' && $request->has('refund_resource') && $request->refund_resource == 'yes' && ($event->sess_with_invoice == 1 || $event->sess_epic_credit == 1)) {
                    $request['clientIfMakeUp'] = 1;
                    $request['purpose']        = 'service';
                    $request['makeupNotes']    = '';
                }

                // dd($request->all());
                if ($request->has('cancelReas')) {
                    $event->sess_cancel_reason = $request->cancelReas;
                    $event->update();

                    if ($request->has('targetEvents')) {
                        $repeat = $event->repeat()->first();
                        if ($request->targetEvents == 'future' && count($repeat)) {
                            /* Delete Associated Event */
                            $associatedEvents = StaffEventSingleService::whereDate('sess_date', '>', $event->sess_date)->where('sess_sessr_id', $repeat['sessr_id'])->get();

                            // dd($associatedEvents->toArray());
                            if ($associatedEvents->count()) {
                                foreach ($associatedEvents as $associatedEvent) {
                                    $additionalHistoryText = '';
                                    /* Update membership limit */
                                    if($request->has('isLdc') && $request->isLdc == 1){
                                        $additionalHistoryText = 'Deleted with membership LDC';
                                    }
                                    else if ($request->has('refund_resource') && $request->refund_resource == 'yes') {
                                        $this->updateClientMembershipLimit([$associatedEvent->sess_client_id], [$associatedEvent->sess_date], ['type' => 'service', 'action' => 'remove', 'eventId' => $associatedEvent->sess_service_id]);
                                    }

                                    if($associatedEvent->sess_cmid != 0) {
                                        $associatedEvent->sess_event_log = 'Cancelled with membership';
                                        $additionalHistoryText = ' cancelled with membership.';
                                    } else if($associatedEvent->sess_epic_credit && $associatedEvent->sess_with_invoice) {
                                        $associatedEvent->sess_event_log = 'Cancelled with epic credit and invoice';
                                        $additionalHistoryText = ' cancelled with epic credit and invoice.';
                                    } else if($associatedEvent->sess_epic_credit) {
                                        $associatedEvent->sess_event_log = 'Cancelled with epic credit';
                                        $additionalHistoryText = ' cancelled with epic credit.';
                                    } else if($associatedEvent->sess_with_invoice) {
                                        $associatedEvent->sess_event_log = 'Cancelled with invoice';
                                        $additionalHistoryText = ' cancelled with invoice.';
                                    }

                                    $associatedEvent->sess_action_performed_by = getLoggedUserName();
                                    $associatedEvent->deleted_at = createTimestamp();
                                    $associatedEvent->save();
                                    $logText = 'Service( Id:'.$associatedEvent->sess_id.', Date:'.$associatedEvent->sess_date.') deleted for future recurrence';
                                    setInfoLog($logText, $associatedEvent->sess_id);

                                    $historyText = 'Service ' . $additionalHistoryText;
                                    $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $associatedEvent]);
                                }
                            }

                            /* Manage repeat data */
                            $previousEvent = StaffEventSingleService::whereDate('sess_date', '<', $event->sess_date)->where('sess_sessr_id', $repeat['sessr_id'])->orderBy('sess_id', 'desc')->first();
                            if (count($previousEvent)) {
                                $repeat->sessr_repeat_end             = 'On';
                                $repeat->sessr_repeat_end_after_occur = 0;
                                $repeat->sessr_repeat_end_on_date     = $previousEvent->sess_date;
                                $repeat->update();
                            } else {
                                $repeat->delete();
                            }
                        }
                    }
                }

                if ($request->clientIfMakeUp) {
                    // $event->sess_epic_credit = 0;
                    $event->sess_is_make_up = 0;
                    $event->sess_if_make_up = 1;
                    $event->sess_event_log = 'Cancelled with epic credit';
                    $event->save();
                    $additionalHistoryText = ' cancelled with epic credit.';
                } else {
                    if($event->sess_cmid != 0) {
                        $event->sess_event_log = 'Cancelled with membership';
                        $additionalHistoryText = ' cancelled with membership.';
                    }else if($request->isLdc == 1){
                        $additionalHistoryText = 'Deleted with membership LDC';
                         }
                    else if($event->sess_epic_credit && $event->sess_with_invoice) {
                        $event->sess_event_log = 'Cancelled with epic credit and invoice';
                        $additionalHistoryText = ' cancelled with epic credit and invoice.';
                    } else if($event->sess_epic_credit) {
                        $event->sess_event_log = 'Cancelled with epic credit';
                        $additionalHistoryText = ' cancelled with epic credit.';
                    } else if($event->sess_with_invoice) {
                        $event->sess_event_log = 'Cancelled with invoice';
                        $additionalHistoryText = ' cancelled with invoice.';
                    }
                }

                # Get invoice details
                $invoice = Invoice::where('inv_client_id', $event->sess_client_id)
                    ->whereHas('invoiceitem', function ($query) use ($request) {
                        $query->where('inp_product_id', $request->eventId)
                            ->where('inp_type', 'service');
                    })->first();

                /* Reset client memebrship */
                if ($request->has('refund_resource') && $request->refund_resource == 'yes' && $event->sess_cmid != 0 && !$request->clientIfMakeUp) {
                    # Set info log
                    setInfoLog('Client membership limit updated on service cancelled with membership ',  $event->sess_client_id);
                    $this->updateClientMembershipLimit([$event->sess_client_id], [$event->sess_date], ['type' => 'service', 'action' => 'remove', 'eventId' => $event->sess_service_id]);
                }

                $clientIdForMemb = $event->sess_client_id;

                // $event->delete();
                $event->sess_action_performed_by = getLoggedUserName();
                $event->deleted_at = createTimestamp();
                $event->save();

                $saleProcessStepNumb = $this->saleProcessStatusToStepNumb($event->sess_sale_process_status);
                $client              = Clients::find($event->sess_client_id);

                if ($saleProcessStepNumb && $saleProcessStepNumb != null) {

                    if ($client->account_status != 'Active' && $client->account_status != 'Contra' && isClientInSalesProcess($client->consultation_date) && $this->isStepEnabled($saleProcessStepNumb, $client->SaleProcessEnabledSteps)) {

                        if ($saleProcessStepNumb == 2) {
                            $client->consultation_date = null;
                        }

                        $client->save();

                        $this->deleteSalesProgress($saleProcessStepNumb, $client->id);
                    }
                    //}
                } else {
                    $this->manageSessionSalesProcess($client);
                }

                $notesId = 0;
                if ($request->makeupNotes) {
                    $startDatetime = dbDateToDateTimeString(new Carbon($event->sess_start_datetime));
                    $notesId       = $this->createNotes($request->makeupNotes, $client->id, 'makeup', 'Added while generating EPIC Credit on service booking' /*, $notesLink*/);
                }

                if ($request->has('purpose') && ($request->clientIfMakeUp && $request->amount > 0 || ($request->has('refund_resource') && $request->has('refund_resource') == 'yes' && ($event->sess_with_invoice == 1 || $event->sess_epic_credit == 1)))) {

                    if ($invoice && $event->sess_with_invoice == 1 && $invoice->inv_status == 'Unpaid') {
                        $epicCreditUsed = InvoiceItems::where('inp_invoice_id', $invoice->inv_id)->sum('inp_paid_using_epic_credit');
                        if ($epicCreditUsed > 0) {
                            $extraInfo = 'EPIC Credit for <a href="' . url('/') . '/dashboard/calendar-new?mevid=' . $event->sess_id . '&mevtype=service">' . date('D, d M Y H:i A', strtotime($event->sess_start_datetime)) . '</a> service';
                            $this->updateEpicCredit($client->id, 0 - $epicCreditUsed, $request->purpose, $extraInfo , $notesId);
                        }
                    } else {
                        $extraInfo = 'EPIC Credit for <a href="' . url('/') . '/dashboard/calendar-new?mevid=' . $event->sess_id . '&mevtype=service">' . date('D, d M Y H:i A', strtotime($event->sess_start_datetime)) . '</a> service';
                        $this->updateEpicCredit($client->id, 0 - $event->sess_price, $request->purpose, $extraInfo , $notesId);
                    }
                }

                if ($event->sess_with_invoice == 1 && $invoice && (($request->has('invoice_delete') && $request->invoice_delete == 'yes_delete')|| ($invoice->inv_status == 'Unpaid' && $request->has('clientIfMakeUp') && $request->clientIfMakeUp) || ($invoice->inv_status == 'Paid' && $request->has('clientIfMakeUp') && $request->clientIfMakeUp))) 
                     $this->deleteEventInvoice($event->sess_id, $event->sess_client_id, 'service', true);

                if($event->sess_with_invoice == 0 && $event->sess_epic_credit == 1 && $invoice && ($invoice->inv_status == 'Paid' && $request->has('clientIfMakeUp') && $request->clientIfMakeUp)) 
                    $this->deleteEventInvoice($event->sess_id, $event->sess_client_id, 'service', true);

                $msg['status']  = 'deleted';
                $msg['message'] = displayAlert('success|Service has been cancelled.');

            }

            $logText = 'Service( Id:'.$event->sess_id.', Date:'.$event->sess_date.') deleted manually';
            setInfoLog($logText, $event->sess_id);
            /* Start: create history for cancelling service */
            $historyText = 'Service ' . $additionalHistoryText;
            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $event]);
            /* End: create history for cancelling service */
        }
        return json_encode($msg);
    }

    public function changeAttendance(Request $request)
    {
        // dd($request->all());
        $isError = false;
        $msg     = [];

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-appointment')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $eventClass = StaffEventSingleService::OfStaff(Auth::user()->account_id)
                    ->find($request->eventId);
            } else {
                $eventClass = StaffEventSingleService::OfBusiness()
                    ->find($request->eventId);
            }

            if ($eventClass) {
                $prevAttendance = $eventClass->sess_client_attendance;
                // dd($eventClass->sess_client_attendance != $request->clientAttendance || $request->attendMark == 1,$request->all());
                if ($eventClass->sess_client_attendance != $request->clientAttendance || $request->attendMark == 1) {
                    $eventClass->sess_client_attendance = $request->clientAttendance;
                    if ($request->clientIfMakeUp) {
                        if ($eventClass->sess_is_repeating) {
                            $eventParentId = $eventClass->sess_parent_id;
                            $date          = $eventClass->sess_date;
                            $oldParId      = getOldParId($eventClass->sess_id, 'service');
                            if ($oldParId) {
                                $eventParentId = ($oldParId == -1) ? 0 : $oldParId;
                                $date          = getOldDate($eventClass->sess_id, 'service');
                            }
                            $this->updateThisCaseForService($eventParentId, $eventClass->sess_id, ['startDatetime' => $eventClass->sess_start_datetime, 'endDatetime' => $eventClass->sess_end_datetime]);
                        }
                        //$eventClass->deleted_at = createTimestamp();

                        $eventClass->sess_epic_credit = 0;
                        $eventClass->sess_is_make_up  = 0;
                        $eventClass->sess_if_make_up  = 1;
                    } else if ($eventClass->sess_with_invoice && $request->clientAttendance == 'Attended') {
                        $existingInvoice = Invoice::join('invoice_items', 'inv_id', '=', 'inp_invoice_id')->select('inv_id')->where('inv_client_id', $eventClass->sess_client_id)->where('inp_type', 'service')->where('inp_product_id', $eventClass->sess_id)->first();
                        if (!$existingInvoice) {
                            $this->raiseAnInvoice($eventClass);
                        }
                    }
                    $eventClass->save();
                    // dd($eventClass->toArray());

                    if ($request->clientIfMakeUp) {
                        $eventClass->delete();
                    }

                    $client = Clients::find($eventClass->sess_client_id);
                    if ($client->account_status != 'Active' && $client->account_status != 'Contra') {
                        foreach($client->SalesSessionOrder as $order){
                            $dataSales = explode('-', $order); 
                        }
                        $arry = [
                            '2' => '3',
                            '4' => '5',
                            '6' => '11',
                            '7' => '23',
                            '8' => '24',
                            '9' => '25',
                            '10' => '26',
                            '12' => '17',
                            '13' =>'19',
                            '14' => '20',
                            '15' => '21',
                            '16' =>'22'
                        ];
                        if ($eventClass->sess_sale_process_status != null || $dataSales[0] == 'indiv') {
                            if ($eventClass->sess_client_attendance == 'Attended') {
                                $markAtten = 0;
                                $salesProcessQuery = false;
                                $msg['salesProcessEvent'] = $this->saleProcessStatusToStepNumb($eventClass->sess_sale_process_status);
                                $array =[1,3,5,11,23,24,25,26,17,19,20,21,22];
                                if(in_array($msg['salesProcessEvent'],$array)){
                                   $salesProcessQuery = true;
                                }
                                $this->salesProcessEventCompleted($eventClass, $eventClass->sess_client_id/*, $prevAttendance*/, $salesProcessQuery);
                                
                                $calendarData =  CalendarSetting::where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
                                $stepDataSales = [];
                                $salesProgressStepData= SalesProcessProgress::where('spp_client_id',$eventClass->sess_client_id)->get();
                                foreach( $salesProgressStepData as $value){
                                    $stepDataSales[] = $value->spp_step_numb;
                                }
                             
                                foreach($stepDataSales as $steps){
                                    if($arry[$steps] != null){
                                        $step = $arry[$steps];
                                        if(!in_array($step, $stepDataSales )){
                                            $attenStep = $step;
                                            $bookStep= $steps;
                                            $markAtten= 1;
                                            $currentDate = Carbon::now();
                                            if($steps == 2 || $steps == 4 || $steps == 12 || $steps == 13 || $steps == 14 || $steps == 15 || $steps == 16){
                                                $serviceDate =  DB::table('staff_event_single_services')->select('sess_date')->where('sess_client_id',$eventClass->sess_client_id)->where('sales_step_number', $steps)->whereNull('deleted_at')->first();
                                                if($serviceDate)
								                $salesDate = dbDateToDateString($serviceDate->sess_date);
                                            }else if($steps == 6 || $steps == 7 || $steps == 8 || $steps == 9 || $steps == 10 ){
                                                $classId =  DB::table('staff_event_class_clients')->select('secc_sec_id')->where('secc_client_id',$eventClass->sess_client_id)->where('sales_step_number', $steps)->whereNull('deleted_at')->first();
                                                $class = DB::table('staff_event_classes')->select('sec_date')->where('sec_id',$classId->secc_sec_id)->first();
                                                        if($class)
                                                            $salesDate = dbDateToDateString($class->sec_date);

                                            }

                                            if($currentDate > $salesDate){
                                                $markAtten= 0;
                                            }
                                            break;
    
                                        }
                                    }
                                }
                               $salesProgressData= SalesProcessProgress::where('spp_client_id',$eventClass->sess_client_id)->orderBy('spp_id','desc')->first();
                               $salesProcessNumb =$salesProgressData['spp_step_numb'] + 1;
                                if($client->sale_process_setts == null || $client->sale_process_setts == ''){
                                    $client->sale_process_setts = $calendarData->sales_process_settings;
                                    $this->salesProcSettingsUpdate($eventClass->sess_client_id,$calendarData->sales_process_settings);
                                    $client->refresh();
                                    $client->sale_process_setts = $calendarData->sales_process_settings;
                                }
                                
                                $clientStatus = json_decode($client->sale_process_setts,1);
                                 if($client && (in_array($client->account_status, ['Pre-Benchmarking']) || in_array($client->account_status, ['Pre-Consultation'])) &&  (in_array('4',$clientStatus['steps']) && ($salesProcessNumb == 4 || $salesProcessNumb == 5)) && !in_array('4',$stepDataSales)){
                                    $msg['salesProcessStepNumber'] = 4;
                                    $msg['salesProcessStepName'] = 'Book Benchmark?';
                                }else if(in_array('4',$stepDataSales) && !in_array('5',$stepDataSales) &&  $markAtten == 1){
                                    $msg['salesAttendStep'] = 5;
                                    $msg['salesProcessStepName'] = 'Benchmark';
                                }else if(in_array($client->account_status, ['Pre-Training']) || in_array($client->account_status, ['Pre-Benchmarking']) || in_array($client->account_status, ['Pre-Consultation'])){
                                    $salesOrder = $client->SalesSessionOrder;
                                    if(count($salesOrder)){
                                        $value = explode('-', $salesOrder[0]);
                                        if($markAtten= 1 && ($attenStep == 11 || $attenStep == 23 || $attenStep == 24 || $attenStep == 25 || $attenStep == 26)){
                                            $msg['salesAttendStep'] = $attenStep;
                                            $msg['salesProcessEvent'] = $bookStep;
                                            if($attenStep == 11){
                                                $msg['salesProcessStepName'] = 'T.E.A.M 1';
                                            }else if($attenStep == 23){
                                                $msg['salesProcessStepName'] = 'T.E.A.M 2';
                                            }else if($attenStep == 24){
                                                $msg['salesProcessStepName'] = 'T.E.A.M 3';
                                            }else if($attenStep == 25){
                                                $msg['salesProcessStepName'] = 'T.E.A.M 4';
                                            }else if($attenStep == 26){
                                                $msg['salesProcessStepName'] = 'T.E.A.M 5';
                                            }
                                            
                                        }else if($markAtten= 1 && ($attenStep == 17 || $attenStep == 19 || $attenStep == 20 || $attenStep == 21 || $attenStep == 22)){
                                            $msg['salesAttendStep'] = $attenStep;
                                            $msg['salesProcessEvent'] = $bookStep;
                                            if($attenStep == 17){
                                                $msg['salesProcessStepName'] = 'Individual 1';
                                            }else if($attenStep == 19){
                                                $msg['salesProcessStepName'] = 'Individual 2';
                                            }else if($attenStep == 20){
                                                $msg['salesProcessStepName'] = 'Individual 3';
                                            }else if($attenStep == 21){
                                                $msg['salesProcessStepName'] = 'Individual 4';
                                            }else if($attenStep == 22){
                                                $msg['salesProcessStepName'] = 'Individual 5';
                                            }
                                         
                                        }else if($value[0] == 'team'){
                                            $teamCount = 0;
                                            $team = teamBookingSteps();
                                            $teamed = teamAttendSteps();
                                         
                                            $attendNumb = $teamed[$teamCount];
                                          
                                            foreach($client->SalesSessionOrder as $order){
                                                $value = explode('-', $order); 
                                                if($value[0] == 'team'){
                                                    $stepNumb = $team[$teamCount];
                                                    // $attendNumb = $teamed[$teamCount];
                                                    $teamCount++;
                                                    if(!SalesProcessProgress::where('spp_client_id', $eventClass->sess_client_id)->orderBy('spp_id','desc')->where('spp_step_numb',$attendNumb)->exists()){
                                                        if(!SalesProcessProgress::where('spp_client_id', $eventClass->sess_client_id)->orderBy('spp_id','desc')->where('spp_step_numb',$stepNumb)->exists()){
                                                            $msg['salesProcessStepNumber'] = $stepNumb;
                                                            $msg['salesProcessStepName'] = 'Book Team?';
                                                            break;
                                                        }
                                                    }
                                                    
                                                   }
                                             }
                                        }else if($value[0] == 'indiv'){
                                            $indivCount = 0;
                                            $indiv = indivBookingSteps();
                                            $indived = indivAttendSteps();
                                       
                                        
                                         foreach($client->SalesSessionOrder as $order){
                                            $value = explode('-', $order); 
                                            if($value[0] == 'indiv'){
                                                $stepNumb = $indiv[$indivCount];
                                                $attendNumb = $indived[$indivCount];
                                                $indivCount++;
                                                if(!SalesProcessProgress::where('spp_client_id', $eventClass->sess_client_id)->orderBy('spp_id','desc')->where('spp_step_numb',$attendNumb)->exists()){
                                                    if(!SalesProcessProgress::where('spp_client_id', $eventClass->sess_client_id)->orderBy('spp_id','desc')->where('spp_step_numb',$stepNumb)->exists()){
                                                        $msg['salesProcessStepNumber'] = $stepNumb;
                                                        $msg['salesProcessStepName'] = 'Book Indiviual?';
                                                        break;
                                                    }
                                                }
                                               
                                                
                                            }
                                         }
                                        }
                                    }
                                
                                   
                                  
                                }
                            } else if (($eventClass->sess_client_attendance == 'Booked' || $eventClass->sess_client_attendance == 'Did not show') && $prevAttendance == 'Attended') {
                                $this->salesProcessEventIgnore($eventClass, $eventClass->sess_client_id/*, $prevAttendance*/);
                            }

                            if ($eventClass->sess_client_attendance == 'Did not show') {
                                # Get Sales process values
                                $salesProcess = SalesProcessProgress::where('spp_client_id', $eventClass->sess_client_id)->where('spp_booking_id', $eventClass->sess_id)->first();

                                if ($salesProcess) {
                                    # Delete Sales process progress details
                                    $attendStep        = $salesProcess->spp_step_numb + 1;
                                    $attendStepDetails = calcSalesProcessRelatedStatus($attendStep);
                                    $salesProcess->forceDelete();
                                }

                                # Delete staff single service
                                // StaffEventSingleService::OfStaff(Auth::user()->account_id)
                                // ->where('sess_id', $eventClass->sess_id)->forceDelete();
                                $eventClass->forceDelete();
                            }
                        } else {
                            // dd('else');
                            $this->manageSessionSalesProcess($client);
                        }
                    }
                   
                    // $client->salesProgress

                    // $msg['salesProcessStepNumber'] = $this->saleProcessStatusToStepNumb($eventClass->sess_sale_process_status);
                    if (!$msg['salesProcessEvent']) {
                        $msg['salesProcessEvent'] = $this->isBookingIndivBooking($eventClass->sess_id, $eventClass->sess_date, $client);
                    }
                    if( $markAtten == 1){
                        $msg['salesProcessEvent'] =$bookStep;
                    }
// dd($eventClass->sales_step_number,in_array($step,$arry) && $request->clientAttendance == 'Booked');
                    if($arry[$eventClass->sales_step_number] != null){
                        $step = $arry[$eventClass->sales_step_number];
                     }
                    if(in_array($step,$arry) && $request->clientAttendance == 'Booked'){
                            
                        $this->deleteSalesProgress($step,$eventClass->sess_client_id); 

                    }
                    /*if($client->account_status != 'Active'){
                    if($eventClass->sess_client_attendance == 'Booked' && $eventClass->sess_sale_process_status != null)
                    $this->salesProcessEventIgnore($eventClass, $eventClass->sess_client_id, $prevAttendance/*, 1*);
                    else if($eventClass->sess_client_attendance == 'Attended'){
                    $this->salesProcessEventCompleted($eventClass, $eventClass->sess_client_id, $prevAttendance);
                    }
                    else if($eventClass->sess_client_attendance == 'Did not show' && $eventClass->sess_sale_process_status != null /*&& $request->breakSalesProcess == 'true'*)
                    $this->salesProcessEventIgnore($eventClass, $eventClass->sess_client_id, $prevAttendance/*, 2*);
                    }*/

                    $notesId = 0;
                    if ($request->makeupNotes) {
                        $startDatetime = dbDateToDateTimeString(new Carbon($eventClass->sess_start_datetime));

                        /*$notesLink = $noteslink = 'EPIC Credit added for <a href="'.url('dashboard/calendar-new?mevid='.$eventClass->sess_id.'&mevtype=service').'">'.$startDatetime.'</a> service';*/

                        $notesId = $this->createNotes($request->makeupNotes, $client->id, 'makeup', 'Added while generating EPIC Credit on service booking' /*, $notesLink*/); //A make up created for
                    }
                    if ($request->has('purpose') && $request->amount > 0) {
                        $this->updateEpicCredit($client->id, 0 - $request->amount, $request->purpose, "", $notesId);
                    }

                    $historyText = '';
                    $client      = $eventClass->clientWithTrashed;
                    if ($request->clientAttendance == 'Attended') {
                        $historyText = $client->firstname . ' ' . $client->lastname . ' was marked as attending the service|';
                    } else if ($request->clientAttendance == 'Did not show') {
                        $historyText = $client->firstname . ' ' . $client->lastname . ' was marked as not attending the service|';
                    }

                    if ($historyText) {
                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);
                    }

                    $this->calcStaffEventPayment('service', $eventClass->sess_id, $request->clientAttendance);

                    //create invoice for next sibling event, if it is setup with invoice
                    if ($eventClass->sess_secr_id && $eventClass->sess_with_invoice) {
                        $nextClass = StaffEventSingleService::where('sess_sessr_id', $eventClass->sess_sessr_id)->whereDate('sess_date', '>', $eventClass->sess_date)->orderBy('sec_date')->first();
                        //StaffEventClass::whereDate('sec_date','>',$eventClass->sec_date)->where('sec_secr_id', $eventClass->sec_secr_id)->orderBy('sec_date')->first();
                        $existingInvoice = Invoice::join('invoice_items', 'inv_id', '=', 'inp_invoice_id')->select('inv_id')->where('inv_client_id', $nextClass->sess_client_id)->where('inp_type', 'service')->where('inp_product_id', $nextClass->sess_id)->first();
                        $amount          = $nextClass->sess_price; //$client->pivot->secc_reduce_rate ? $client->pivot->secc_reduce_rate :
                        if ($nextClass && !$existingInvoice && $amount) {
                            $this->raiseAnInvoice($nextClass, 0, $nextClass->sess_epic_credit);
                        }
                    }
                    $msg['status'] = 'added';
                }
            }
        }
        return json_encode($msg);
    }

    public function reschedule(Request $request)
    {
        // dd($request->all());
        $isError = false;
        $msg     = [];

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-appointment')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $event = StaffEventSingleService::OfStaff(Auth::user()->account_id)->find($request->eventId);
            } else {
                $event = StaffEventSingleService::OfBusiness()->find($request->eventId);
            }

            if ($event) {
                $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->time, 'startDate' => $request->date, 'duration' => $event->sess_duration]);
                if (ClosedDate::ifOverlapping($request->date, $startAndEndDatetime['endDate'])) {
                    $msg['status']      = 'error';
                    $msg['errorData'][] = array('businessClosed' => '');
                    $isError            = true;
                }

                if (!$isError) {
                    $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                    $eventEndDatetime   = $startAndEndDatetime['endDatetime'];

                    if ( true) {
                        $eventDate = new Carbon($request->date);

                        $areas       = $event->areas;
                        $areasId     = $areas->pluck('la_id')->toArray();
                        $busyAreaIds = $this->areAreasBusy(['eventId' => $request->eventId, 'areaId' => $areasId, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');
                        // dd($busyAreaIds);
                        if(isset($event->is_ldc) && $event->is_ldc == 1){
                            $isSatisfy = $this->isSatisfyLdcRestriction($event->sess_client_id,$request->eventId,'reschedule','service');
                            if(!$isSatisfy){
                                $isError           = true;
                                $msg['status']     = 'ldcRestrictionUnsatisfy';
                                $msg['errorData'][] = array('ldcRestriction' => displayAlert('error| could not be rescheduled because LDC restriction unsatisfied'));

                            }
                        }else{                       
                        //      if (count($busyAreaIds)) {
                        //     $areasName = $areas->whereIn('la_id', $busyAreaIds)->pluck('la_name')->toArray();

                        //     $msg['status']      = 'error';
                        //     $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|' . implode(', ', $areasName) . ' are busy at specified hours!'));
                        //     $isError            = true;
                        // } else if ($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $event->sess_staff_id, 'day' => $eventDate->format('l'), 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment')) {

                        //     $msg['status']      = 'error';
                        //     $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|' . staffBusyMsg('Chosen staff is')));
                        //     $isError            = true;
                        // } else {
                            $busyClientIds = $this->isClientBusy(['eventId' => $request->eventId, 'clientId' => [$event->sess_client_id], 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');

                            if (count($busyClientIds)) {
                                $msg['status']      = 'error';
                                $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|Chosen client is busy at specified hours!'));
                                $isError            = true;
                            }
                        
                    }
                   }
                    if (!$isError) {
                        $oldStartDatetime = $event->sess_start_datetime;
                        $oldEndDatetime   = $event->sess_end_datetime;

                        $eventStartDatetimeCarb = new Carbon($eventStartDatetime);
                        if ($eventStartDatetimeCarb->gt(Carbon::now())) {
                            $event->sess_client_attendance = 'Booked';
                        }

                        $event->sess_date           = $request->date;
                        $event->sess_time           = $request->time;
                        $event->sess_start_datetime = $eventStartDatetime;
                        $event->sess_end_datetime   = $eventEndDatetime;
                        // $event->sess_client_check   = 1;

                        /*$clientDetails = Clients::paidMembership($event->sess_client_id);
                        if($clientDetails)
                        $event->sess_cmid = $clientDetails->id;*/

                        if ($event->update()) {
                            $this->linkEventServiceResource($request, $event->sess_id, 'edit');

                            $client = Clients::find($event->sess_client_id);
                            if ($client->account_status != 'Active' && $client->account_status != 'Contra' && $event->sess_sale_process_status == 'book_consult' && isClientInSalesProcess($client->consultation_date)) {
                                $client->consultation_date = $event->sess_date;
                                $client->save();
                            }
                            /*$client = Clients::find($event->sess_client_id);
                            if($client->account_status != 'Active')
                            $this->salesProcessEventIgnore($event, $event->sess_client_id, 1, 'Service booking rescheduled');*/

                            if ($request->has('targetEvents')) {
                                if ($request->targetEvents == 'future') {
                                    $this->resetSingleServiceRepeat($event, $startAndEndDatetime);
                                }else{
                                    $event->sess_sessr_id = 0;
                                    $event->update();
                                }
                                /*else{

                            //$this->unlinkFromReccurenceChain($event); //FIX THIS SEND OLD DATETIME
                            $this->unlinkServiceFromReccurenceChain($event, ['startDatetime'=>$oldStartDatetime, 'endDatetime'=>$oldEndDatetime]);
                            }*/
                            }

                            // $historyText = $request->historyText;
                            // $origEventTimestamp = strtotime($request->historyText);
                            $event->refresh();
                            $withInvoice = $event->sess_with_invoice;
                            if ($withInvoice) {
                                /*Fetch invoice id of old event session*/
                                $invoice = Invoice::where('inv_client_id', $client->id)
                                    ->whereHas('invoiceitem', function ($query) use($event){
                                        $query->where('inp_product_id', $event->sess_id)
                                            ->where('inp_type', 'service');
                                    })->first();
                                if ($invoice) {
                                    /* Save invoice new due date */
                                    $invoice->inv_due_date = date('Y-m-d', strtotime($request->date));
                                    $invoice->save();

                                    $invoiceItem = InvoiceItems::where('inp_invoice_id', $invoice->inv_id)->whereNull('deleted_at')->first();

                                    if ($invoiceItem) {
                                        /* Get staff name of event */
                                        $eventStaff = $event->staff()->first();
                                        $staffName  = (isset($eventStaff->first_name) ? $eventStaff->first_name : '') . ' ' . (isset($eventStaff->first_name) ? $eventStaff->last_name : '');

                                        $invoiceItem->inp_staff_id   = $event->sess_staff_id;
                                        $eventServiceData = $event->getItemDescData();

                                        $invoiceItem->inp_item_desc  = $eventServiceData['itemDesc'];
                                        $invoiceItem->inp_product_id = $event->sess_id;
                                        $invoiceItem->save();
                                    }
                                }

                            }

                            $origEventTimestamp = strtotime($oldStartDatetime);
                            
                            $historyText = 'Rescheduled from ' . date('l', $origEventTimestamp) . ' ' . date('d/m/y', $origEventTimestamp) . ' @' . date('H:i A', $origEventTimestamp) . ' by ' . Auth::user()->name . ' ' . Auth::user()->last_name;

                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $event]);

                            $msg['status'] = 'updated';
                            $msg['eventId'] = $request->eventId;
                            $message       = displayAlert('success|Service has been rescheduled successfully.');

                            $linkedResources = $event->resources;
                            if ($linkedResources->count()) {
                                $resId   = $linkedResources->pluck('serc_res_id')->toArray();
                                $data    = ['time' => $event->sess_time, 'date' => $request->date, 'duration' => $event->sess_duration, 'resId' => $resId, 'eventType' => 'service', 'eventId' => $request->eventId];
                                $usedRes = $this->ifResourcesInUse($data);

                                $serviceResources = Service::linkedResources($event->sess_service_id);
                                $delRes           = [];
                                $busyRes          = [];
                                $unlinkRes        = [];
                                foreach ($linkedResources as $linkedResource) {
                                    $serviceResource = $serviceResources->where('sr_res_id', $linkedResource->serc_res_id)->first();
                                    if ($serviceResource->resourceWithTrashed->trashed()) {
                                        $delRes[] = $serviceResource->resourceWithTrashed->res_name;
                                    } else if ($serviceResource->trashed()) {
                                        $unlinkRes[] = $serviceResource->resourceWithTrashed->res_name;
                                    } else {
                                        $totalQuant    = $serviceResource->resourceWithTrashed->itemsWithTrashed->count();
                                        $remainResQuan = $totalQuant;
                                        if ($totalQuant && count($usedRes) && array_key_exists($linkedResource->serc_res_id, $usedRes)) {
                                            $usedQuant     = $usedRes[$linkedResource->serc_res_id];
                                            $remainResQuan = $totalQuant - $usedQuant;
                                        }
                                        if ($remainResQuan < $linkedResource->serc_item_quantity) {
                                            $busyRes[] = $serviceResource->resourceWithTrashed->res_name;
                                        }

                                    }
                                }
                                if (count($delRes)) {
                                    /*$msg['status'] = 'error';
                                    $msg['errorData'][] = array('resourceDel' => displayAlert('error|'.implode(', ', $delRes).' have been deleted!'));
                                    $isError = true;*/
                                    $message .= displayAlert('error|' . implode(', ', $delRes) . ' had been deleted!');
                                }

                                if (count($unlinkRes)) {
                                    /*$msg['status'] = 'error';
                                    $msg['errorData'][] = array('resourceDel' => displayAlert('error|'.implode(', ', $delRes).' have been deleted!'));
                                    $isError = true;*/
                                    $message .= displayAlert('error|' . implode(', ', $unlinkRes) . ' had been unlinked with service!');
                                }

                                if (count($busyRes)) {
                                    /*$msg['status'] = 'error';
                                    $msg['errorData'][] = array('resourceUnavail' => displayAlert('error|'.implode(', ', $busyRes).' are unavailable at specified hours!'));
                                    $isError = true;*/
                                    $message .= displayAlert('error|' . implode(', ', $busyRes) . ' are unavailable at specified hours!');
                                }

                                /*if(count($usedRes)){
                            $serviceResources = Service::linkedResources($event->sess_service_id);
                            $busyRes = [];
                            foreach($usedRes as $usedId => $usedQuant){
                            $linkedResource = $linkedResources->where('serc_res_id', $usedId)->first();
                            $totalUsedQuant = (int) $linkedResource->serc_item_quantity + $usedQuant;

                            $serviceResource = $serviceResources->where('sr_res_id', $usedId)->first();
                            //$totalQuant = (int) $serviceResource->sr_item_quantity;
                            $totalQuant = $serviceResource->resource->items->count();
                            if($totalUsedQuant > $totalQuant)
                            $busyRes[] = $serviceResource->resource->res_name;
                            }
                            if(count($busyRes)){
                            $msg['status'] = 'error';
                            $msg['errorData'][] = array('resourceUnavail' => displayAlert('error|'.implode(', ', $busyRes).' are unavailable at specified hours!'));
                            $isError = true;
                            }
                            }*/
                            }

                            $msg['message'] = $message;
                        }
                    }
                }
            }
        }
        return json_encode($msg);
    }

    protected function sendClientServiceBookingEmail($action, $request, $clients)
    {
        return $this->sendClientEventBookingEmail($action, $request, [$clients], 'service');
    }

    protected function calcHistoryTextFromAction($action, $additional = "")
    {
        if ($action == 'add') {
            return ' was added to the service' . ($additional ? " $additional" : "") . '.';
        } else {
            return ' was removed from the service' . ($additional ? " $additional" : "") . '.';
        }

    }

    protected function haltPrevRelatedEventsReccur($eventParentId, $eventId = 0)
    {
        /*if(!$eventId)
        $previousRelatedEvents = StaffEventSingleService::where('sess_parent_id', $eventParentId)->orWhere('sess_id', $eventParentId)->orderBy('sess_date', 'DESC')->get();
        else{
        $previousRelatedEvents = StaffEventSingleService::where(function($query) use ($eventParentId){
        $query->where('sess_parent_id', $eventParentId)->orWhere('sess_id', $eventParentId);
        })
        ->where('sess_id', '!=', $eventId)
        ->orderBy('sess_date', 'DESC')
        ->get();
        }*/

        $previousRelatedEvents = $this->getBookingsFromChain($eventParentId, $eventId, 'service');
        if ($previousRelatedEvents->count()) {
            $latestEventDate = $previousRelatedEvents->first()->sess_date;

            StaffEventRepeat::ofSingleService()->where('ser_event_id', $eventParentId)->update(['ser_child_count' => $previousRelatedEvents->count() - 1]);

            $eventIds = $previousRelatedEvents->pluck('sess_id')->toArray();

            $repeatTable = (new StaffEventRepeat)->getTable();
            DB::table($repeatTable)->where('ser_event_type', 'App\StaffEventSingleService')->whereIn('ser_event_id', $eventIds)->update(['ser_repeat_end' => 'ON', 'ser_repeat_end_after_occur' => 0, 'ser_repeat_end_on_date' => $latestEventDate]);
        }
    }

    protected function delAssociatedServices($data)
    {
        $data['eventType'] = 'single-service';
        $this->delAssociatedEvents($data);
    }

    protected function salesProcessEventIgnore($event, $clientId/*, $prevAttendance, $levelDownInAttended, $reason = ''*/)
    {
        /*if($event->sess_sale_process_status == 'teamed' || $event->sess_sale_process_status == 'book_team'){
        $client = Clients::find($clientId);
        $clientOldSaleProcessStep = $client->sale_process_step;
        $saleProcessTeamedDetails = calcSalesProcessRelatedStatus('teamed');
        if($levelDownInAttended == 2){
        //Did not show case
        if($client->sale_process_step >= $saleProcessTeamedDetails['saleProcessStepNumb']){
        //If sales process was marked as 'teamed' or greater than that then downgrade sales process to 'book_team'
        $client->account_status = $saleProcessTeamedDetails['clientPrevStatus'];
        $client->sale_process_step = $saleProcessTeamedDetails['saleProcessStepNumb']-2;
        }
        else{
        //move sales process a step back
        $client->sale_process_step--;
        }
        $client->save();

        $this->deleteAheadedSalesProcess($client->sale_process_step+1, $clientId);
        }
        else{
        //Booked Case
        if($event->sess_sale_process_status == 'teamed'){
        //Atendace changed from 'Attended' to 'Booked'
        if($client->sale_process_step >= $saleProcessTeamedDetails['saleProcessStepNumb']){
        //If sales process was marked as 'teamed' or greater than that then downgrade sales process to 'Book Team 3'
        $client->account_status = $saleProcessTeamedDetails['clientPrevStatus'];
        $client->sale_process_step = $saleProcessTeamedDetails['saleProcessStepNumb']-1;
        $client->save();
        }

        $this->deleteAheadedSalesProcess($client->sale_process_step+1, $clientId);
        }
        else{
        //Attendance changed from 'Did not show' to 'Booked'
        $client->sale_process_step++; // Move a steo forward in sales process

        $saleProcessNextStepDetails = calcSalesProcessRelatedStatus($client->sale_process_step);
        if(array_key_exists("clientStatus", $saleProcessNextStepDetails)){
        //If sales process was marked less than 'Book Team 1' then upgrade sales process to it
        $client->account_status = $saleProcessNextStepDetails['clientStatus'];
        }

        $client->save();
        }
        }

        if($event->sess_sale_process_status == 'teamed'){
        //Resetting teamed to book_team in event
        $saleProcessTeamedDetails = calcSalesProcessRelatedStatus($event->sess_sale_process_status);
        $saleProcessBookTeamDetails = calcSalesProcessRelatedStatus($saleProcessTeamedDetails['saleProcessStepNumb']-1);
        $event->sess_sale_process_status = $saleProcessBookTeamDetails['salesProcessType'];
        $event->save();
        }

        if($clientOldSaleProcessStep != $client->sale_process_step){
        $clientUpdatedSalesProcess = calcSalesProcessRelatedStatus($client->sale_process_step);
        $salesProcessHistory = ['clientId'=>$clientId, 'eventId'=>$event->sess_id, 'toType'=>$clientUpdatedSalesProcess['salesProcessType'], 'toStep'=>$clientUpdatedSalesProcess['saleProcessStepNumb'], 'fromStep'=>$clientOldSaleProcessStep];
        $this->saveSalesProcess($salesProcessHistory);
        }

        //$this->manageTeamSalesProcessOfClient($clientId, $event->sess_id);
        }
        else{*/
        $client = Clients::find($clientId);
        //if($this->isClientInSalesProcess($event->sess_sale_process_status, $client->consultation_date)){
        if (isClientInSalesProcess($client->consultation_date)) {
            //if($prevAttendance == 'Attended'){
            /*$salesAttendanceSteps = salesAttendanceSteps();
            $key = array_search($event->sess_sale_process_status, $salesAttendanceSteps);
            $salesAttendanceNextSteps = array_slice($salesAttendanceSteps, $key);
            $stepsToDelete = [];
            foreach($salesAttendanceNextSteps as $step){
            $stepDetails = calcSalesProcessRelatedStatus($step);
            $stepsToDelete[] = $stepDetails['saleProcessStepNumb'];
            }
            $this->deleteSalesProgress($stepsToDelete, $clientId, false);

            $salesProcessCurrentDetails = calcSalesProcessRelatedStatus($event->sess_sale_process_status);*/

            $salesProcessCurrentDetails = calcSalesProcessRelatedStatus($event->sess_sale_process_status);
            if (!array_key_exists('clientStatus', $salesProcessCurrentDetails)) {
                $salesProcessCurrentDetails      = calcSalesProcessRelatedStatus($salesProcessCurrentDetails['saleProcessStepNumb'] + 1);
                $event->sess_sale_process_status = $salesProcessCurrentDetails['salesProcessType'];
            }
            if ($this->isStepEnabled($salesProcessCurrentDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps)) {
                $this->deleteSalesProgress($salesProcessCurrentDetails['saleProcessStepNumb'], $clientId); //Deleting record from progress

                /*$salesAttendanceSteps = salesAttendanceSteps();
                $key = array_search($event->sess_sale_process_status, $salesAttendanceSteps);
                $salesAttendanceNextSteps = array_slice($salesAttendanceSteps, $key+1);
                $nextStepsNumb = [];
                foreach($salesAttendanceNextSteps as $step){
                $stepDetails = calcSalesProcessRelatedStatus($step);
                $nextStepsNumb[] = $stepDetails['saleProcessStepNumb'];
                }
                if(!count($nextStepsNumb) || !$this->checkSalesProgress($nextStepsNumb, $clientId)){ //If sales process has not advanced, i.e., changing consultation attendance and benchamrk attendance exist then do not change client status
                $client->account_status = clientStatusPrevSales($salesProcessCurrentDetails);
                }*/
                if (!$this->checkFutureSalesProgress($event->sess_sale_process_status, $clientId, $client->SaleProcessEnabledSteps)) {
                    $client->account_status = preventActiveContraOverwrite($client->account_status, $this->getAttendClientStatus($event->sess_sale_process_status, $client));
                    //$client->account_status = preventActiveContraOverwrite($client->account_status, clientStatusPrevSales($salesProcessCurrentDetails));
                }
                if ($event->sess_sale_process_status == 'consulted') {
                    $client->consultation_date = $event->sess_date;
                }

                $client->save();

                /*if($event->sess_sale_process_status == 'consulted')
            StaffEventSingleService::OfClientAndInSalesProcess($clientId, ['benchmarked'])->update(['sess_sale_process_status'=>'book_benchmark']);*/
            }
            $saleProcessPrevStepDetails      = calcSalesProcessRelatedStatus($salesProcessCurrentDetails['saleProcessStepNumb'] - 1);
            $event->sess_sale_process_status = $saleProcessPrevStepDetails['salesProcessType'];
            $event->save();
            //}

            /*$salesProcessCurrentDetails = calcSalesProcessRelatedStatus($event->sess_sale_process_status);

        if(array_key_exists("clientStatus", $salesProcessCurrentDetails)){
        //Event booked and attendence was marked as attended
        $levelDown = $levelDownInAttended;
        }
        else{
        //Event booked only
        $levelDown = $levelDownInAttended-1;
        }

        if($levelDown == $levelDownInAttended){
        $client->account_status = clientStatusPrevSales($salesProcessCurrentDetails);
        if($salesProcessCurrentDetails['salesProcessType'] == 'consulted')
        $client->consultation_date = null;
        }
        $clientOldSaleProcessStep = $client->sale_process_step;
        $client->sale_process_step = $salesProcessCurrentDetails['saleProcessStepNumb']-$levelDown;
        $client->save();

        if($levelDown == $levelDownInAttended){
        $saleProcessPrevStepDetails = calcSalesProcessRelatedStatus($salesProcessCurrentDetails['saleProcessStepNumb']-1);
        $event->sess_sale_process_status = $saleProcessPrevStepDetails['salesProcessType'];
        $event->save();
        }

        $clientUpdatedSalesProcess = calcSalesProcessRelatedStatus($client->sale_process_step);

        if(!$reason)
        $reason = 'Service booking marked as '.($levelDownInAttended == 2?'did not show':'booked');
        $salesProcessHistory = ['clientId'=>$clientId, 'eventId'=>$event->sess_id, 'toType'=>$clientUpdatedSalesProcess['salesProcessType'], 'toStep'=>$clientUpdatedSalesProcess['saleProcessStepNumb'], 'fromStep'=>$clientOldSaleProcessStep, 'reason'=>$reason];
        $this->saveSalesProcess($salesProcessHistory);

        $this->deleteAheadedSalesProcess($client->sale_process_step+1, $clientId, $client->consultation_date);*/
        }
        //}
    }

    protected function saleProcessStatusToStepNumb($status)
    {
        if ($status != null && $status) {
            $salesProcessCurrentDetails = calcSalesProcessRelatedStatus($status);
            return $salesProcessCurrentDetails['saleProcessStepNumb'];
        }
        return $status;
    }

    protected function linkEventServiceResource($request, $eventId, $kase)
    {
        $request->eventId   = $eventId;
        $request->eventType = 'App\StaffEventSingleService';
        $request->kase      = $kase;
        $this->linkResources($request);
    }

    protected function isClientInSalesProcess($eventSaleStatus, $consultDate)
    {
        if ($eventSaleStatus == 'book_consult' && $consultDate == null) {
            return true;
        }

        return isClientInSalesProcess($consultDate);
    }

    /**
     * Check whether booking and client can be linked in sales process
     *
     * @param Collection $eventClass Service booking
     * @param Collection $client Client
     *
     * @return int Sales process step number
     */
    protected function canLinkSalesProcess($eventClass, $client)
    {
        $step = false;

        if ($eventClass->sess_booking_status == 'Confirmed') {
            //If booking is confirmed, not pencilled-in
            $eventClass->load('repeat');
            $repeat = $eventClass->repeat()->first();
            if (!$repeat || $repeat->sessr_repeat == 'None') {
                //If booking is not repeating
                $service = Service::find($eventClass->sess_service_id);
                if ($service->for_sales_process_step) {
                    //If choosed service is part of sales process
                    $canLink = true;
                    if ($service->for_sales_process_step == 4) {
                        //If choosed service is benchmark
                        $canLink = false;
                        if ($client->consultation_date != null) {
                            //Sales process has been consulted
                            $eventDate        = new Carbon($eventClass->sess_date);
                            $consultationDate = new Carbon($client->consultation_date);
                            if ($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))) {
                                //Event booking date is not less than Consultation date and not greater than Consultation end date(Consultation date + 15 days)
                                $canLink = true;
                            }
                        }
                    }
                    if ($canLink && $this->isStepEnabled($service->for_sales_process_step, $client->SaleProcessEnabledSteps)) {
                        $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($service->for_sales_process_step);
                        if (($service->for_sales_process_step == 2 && $client->account_status == 'Pending') || $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $eventClass->sess_client_id, $client->SaleProcessEnabledSteps)) {
                            /*if(statusMatchSalesStatus($client->account_status, $salesProcessRelatedStatus['clientPrevStatus']) || ($service->for_sales_process_step == 2 && ($client->account_status == 'Pending' /*|| $client->account_status == 'Active Lead' || $client->account_status == 'Inactive Lead'*))){*///If client account status match the sales process required status

                            $step = $service->for_sales_process_step;
                        }
                    }
                } else {
                    $this->manageSessionSalesProcess($client);
                }

            }
        }

        return $step;
    }

    /**
     * Link booking and client in sales process
     *
     * @param Collection $eventClass Service booking
     * @param Collection $client Client
     * @param int Sales process step number
     *
     * @return array
     */
    protected function linkSalesProcess($eventClass, $client, $salesStep)
    {
        $salesProcessRelatedStatus            = calcSalesProcessRelatedStatus($salesStep);
        $eventClass->sess_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
        $eventClass->save();

        if ($salesStep == 2) {
            if ( /*$salesStep == 2 && (*/$client->account_status == 'Pending' /*|| $client->account_status == 'Active Lead' || $client->account_status == 'Inactive Lead')*/) {
                $this->storeContactNote(['clientId' => $eventClass->sess_client_id, 'status' => 'contacted']);
                $client->account_status = clientStatusPrevSales($salesProcessRelatedStatus);
                $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => 1]);
            }
            $client->consultation_date = $eventClass->sess_date;
        }
        $clientOldSaleProcessStep  = $client->sale_process_step;
        $client->sale_process_step = $salesStep;
        $client->save();

        $salesProcessHistory = ['clientId' => $eventClass->sess_client_id, 'eventId' => $eventClass->sess_id, 'toType' => $salesProcessRelatedStatus['salesProcessType'], 'toStep' => $salesProcessRelatedStatus['saleProcessStepNumb'], 'fromStep' => $clientOldSaleProcessStep, 'reason' => 'Service booked from calendar'];
        /*$msg['salesProcessDate'] =*/$this->saveSalesProcess($salesProcessHistory);

        $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => $salesStep, 'eventId' => $eventClass->sess_id]);

        StaffEventSingleService::deleteUnattendedSalesProcess(['clientId' => $eventClass->sess_client_id, 'saleProcessStatus' => [$salesProcessRelatedStatus['salesProcessType']]]);
    }

    public function changeDate(Request $request)
    {
        $staffEvent                      = StaffEventSingleService::find($request->eventId);
        $staffEvent->sess_date           = $request->date;
        $staffEvent->sess_start_datetime = $staffEvent->sess_date . ' ' . $staffEvent->sess_time;

        $endDatetime                   = explode(' ', $staffEvent->sess_end_datetime);
        $staffEvent->sess_end_datetime = $staffEvent->sess_date . ' ' . $endDatetime[1];

        $staffEvent->save();
        dd('done');
    }

    /** ------------------------------------------------------ **/
    /* For clints event (only for result)
    /** ------------------------------------------------------ **/

    /**
     * Create client new appointment from result
     *
     * @param Request
     * @return response
     */
    public function clientServiceStore(Request $request)
    {
        $logedUser                 = Auth::user();
        $userId                    = Auth::user()->id;
        $clientId                  = $logedUser->account_id;
        $businessId                = $logedUser->business_id;
        $client                    = Clients::OfBusiness()->find($clientId);
        $startDate                 = $this->calcEventDate($request, $request->classDate);
        $membership                = $this->satisfyMembershipRestrictions($clientId, ['event_type' => 'service', 'event_id' => $request->staffservice, 'event_date' => $startDate]);
        $isError                   = false;
        $isEpicInvoice             = 0;
        $ifEpicCashChecked         = 0;
        $new_epic_cash_value_check = 0;
        $isLdc                     = 0;
         $forceAdd                  = true;
        $msg                       = $satisfyMembershipClients                       = [];

        if ($client && $client->account_status != 'Active' && $client->account_status != 'Contra') {
            $isError            = true;
            $msg['status']      = 'error';
            $msg['errorData'][] = array('notApproved' => displayAlert("warning| Your account is not approved."));

        } else if (!$membership['satisfy']) {
        $optionalData = [
            'sess_date' => $request->classDate,
            'sess_service_id' => $request->staffservice
        ];
        $isSatisfyLdc = $this->isSatisfyLdcRestriction($clientId,$request->eventId,'','service',$optionalData);
        if(!$isSatisfyLdc){          
              if (($request->has('isEpicCash') && $request->isEpicCash) || ($client->epic_credit_balance > 0)) {
                if ($client->epic_credit_balance >= $request['servicePrice']) {
                    $ifEpicCashChecked = 1;
                    $isEpicInvoice     = 0;
                } else {
                    $ifEpicCashChecked = 1;
                    $isEpicInvoice     = 1;
                }

            } else {
                $ifEpicCashChecked = 0;
                $isEpicInvoice     = 1;
            }
        }else{
            $isLdc = 1;
        }      
      } else if ($membership['satisfy']) {
            $ifEpicCashChecked          = 0;
            $isEpicInvoice              = 0;
            $satisfyMembershipClients[] = $clientId;
        }

        $service_detail                = Service::find($request->staffservice);
        $request->salesProcessStepNumb = $service_detail['for_sales_process_step'];
        if (!Staff::ifstaffExist($request->staff)) {
            $isError            = true;
            $msg['status']      = 'error';
            $msg['errorData'][] = array('staffUnavail' => displayAlert("error| This staff does not exist."));
        } elseif ($request->modalLocArea == '') {
            $isError            = true;
            $msg['status']      = 'error';
            $msg['errorData'][] = array('areaUnavail' => displayAlert("error| This area does not exist."));
        }

        if (!$isError) {
            $areaIds                   = array_map('intval', $request->modalLocArea);
            $staffId                   = (int) $request->staff;
            $countOfAreasLinkedToStaff = $this->getCountOfAreasLinkedToStaff(['areaId' => $areaIds, 'staffId' => $staffId]);

            if ($countOfAreasLinkedToStaff == count($request->modalLocArea)) {
                $isAreaLinkedToStaff = true;
            } else {
                $isAreaLinkedToStaff = false;
            }

            if ($isAreaLinkedToStaff) {
                $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->classTime, 'startDate' => $startDate, 'duration' => $request->serviceDur]);

                $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                $eventEndDatetime   = $startAndEndDatetime['endDatetime'];

                $eventClass = new StaffEventSingleService;

                /* service can't book within next 24 hours */
                $nxt_24 = date("Y-m-d H:m:s", (strtotime(date("Y-m-d H:m:s")) + (60 * 60 * 24)));

                if ($startAndEndDatetime['startDatetime'] < $nxt_24) {
                    $isError            = true;
                    $msg['status']      = 'error';
                    $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|Bookings are not allowed before ' . $nxt_24));
                } else if (ClosedDate::ifOverlapping($startDate, $startAndEndDatetime['endDate'])) {
                    $isError            = true;
                    $msg['status']      = 'error';
                    $msg['errorData'][] = array('businessClosed' => displayAlert('error|Business is closed on specified date'));
                }
                if (!$isError) {
                    if ($request->appointStatusOpt == 'Confirmed' && ($request->eventRepeat == 'None' || !$request->eventRepeat)) {
                        $busyAreaIds = $this->areAreasBusy(['areaId' => $request->modalLocArea, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime]);

                        if (count($busyAreaIds)) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('areaUnavail' => $busyAreaIds);
                            $isError            = true;
                        } else if ($this->isStaffBusy(['staffId' => $request->staff, 'day' => $request->classDay, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime])) {

                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                            $isError            = true;
                        }
                    }

                    if (count($request->classClients)) {
                        foreach ($request->classClients as $key => $value) {
                            $clientIdsToCheck[]            = $key;
                            $eventClass->sess_client_id    = $key;
                            $eventClass->sess_client_notes = $value['note'];
                        }
                        $clients          = Clients::find($clientIdsToCheck[0]);
                        $alertHistoryText = $this->sendClientServiceBookingEmail('confirm', $request, $clients);

                        $busyClientIds = $this->isClientBusy(['clientId' => $clientIdsToCheck, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');

                        if (count($busyClientIds)) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => displayAlert('error|You can not book this service because you are busy at specified hours.'));
                            $isError            = true;
                        }
                    } else if ((empty($request->salesProcessStepNumb) || $request->salesProcessStepNumb == 0) /*&& !$forceAdd*/) {
                        $membership = $this->satisfyMembershipRestrictions($clientIdsToCheck[0], ['event_type' => 'service', 'event_id' => $request->staffservice, 'event_date' => $startDate]);
                        // dd($membership);
                        if ($membership['satisfy']) {
                            $satisfyMembershipClients[] = $clientIdsToCheck[0];
                        }else if($isLdc){
                            $satisfyMembershipClients = $clientId;
                        } else {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('membershipUnsatisfy' => '');
                            $isError            = true;
                        }
                    }
                }

                if (!$isError) {
                    $eventClass->sess_business_id    = $businessId;
                    $eventClass->sess_date           = $startDate;
                    $eventClass->sess_time           = $request->classTime;
                    $eventClass->sess_start_datetime = $eventStartDatetime;
                    $eventClass->sess_end_datetime   = $eventEndDatetime;
                    $eventClass->sess_notes          = $request->classNote;
                    $eventClass->sess_staff_id       = $request->staff;
                    //$eventClass->sess_create_source = 'result';
                    $eventClass->sess_booking_status       = 'Pencilled-In';
                    $eventClass->sess_auto_expire          = 24;
                    $eventClass->sess_auto_expire_datetime = date("Y-m-d H:m:s", (strtotime($eventStartDatetime) - (60 * 60 * 24)));
                    $eventClass->sess_service_id           = $request->staffservice;
                    $eventClass->sess_duration             = $request->serviceDur;
                    $eventClass->sess_price                = $request->servicePrice;

                    /* Epic */
                    if ($ifEpicCashChecked == 1 && $isEpicInvoice == 1) {
                        $eventClass->sess_epic_credit  = 1;
                        $eventClass->sess_with_invoice = 1;
                        $eventClass->sess_event_log = 'Booked with epic credit and invoice';
                        $eventClass->sess_action_performed_by = getLoggedUserName();
                        $msg['isClientMakeup']         = true;
                    } else if ($ifEpicCashChecked == 1 && $isEpicInvoice == 0) {
                        $eventClass->sess_epic_credit  = 1;
                        $eventClass->sess_with_invoice = 0;
                        $eventClass->sess_event_log = 'Booked with epic credit';
                        $eventClass->sess_action_performed_by = getLoggedUserName();
                        $msg['isClientMakeup']         = true;
                    } else if ($isEpicInvoice == 1 && $ifEpicCashChecked == 0) {
                        $eventClass->sess_with_invoice = 1;
                        $eventClass->sess_epic_credit  = 0;
                        $eventClass->sess_event_log = 'Booked with invoice';
                        $eventClass->sess_action_performed_by = getLoggedUserName();
                    }else if($isLdc){
                        $clientDetails = Clients::paidMembership($clientId);
                        $eventClass->sess_cmid = $clientDetails->id;
                        $eventClass->is_ldc = $isLdc;
                        $eventClass->sess_event_log = 'Booked with membership LDC';
                        $eventClass->sess_action_performed_by = getLoggedUserName();
                    } else if($membership['satisfy']) {
                        $clientDetails = Clients::paidMembership($clientId);
                        $eventClass->sess_cmid = $clientDetails->id;
                        $eventClass->sess_event_log = 'Booked with membership';
                        $eventClass->sess_action_performed_by = getLoggedUserName();
                    }

                    Auth::user()->eventAppointments()->save($eventClass);

                    $this->newHistory(['eventType' => 'Service', 'event' => $eventClass]);
                    $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);

                    $attachedArea = [];
                    foreach ($request->modalLocArea as $areaId) {
                        $attachedArea[$areaId] = ['sessa_business_id' => Session::get('businessId')];
                    }

                    if (count($attachedArea)) {
                        $eventClass->areas()->attach($attachedArea);
                    }

                    /* Update client membership limit */
                    if (count($satisfyMembershipClients)) {
                        $eventClass->sess_epic_credit  = 0;
                        $eventClass->sess_with_invoice = 0;

                        # Set info log
                        setInfoLog('Client membership limit updated on service booked with membership ',  $clientId);

                        $this->updateClientMembershipLimit($clientId, [$eventClass->sess_date], ['type' => 'service', 'action' => 'add', 'eventId' => $eventClass->sess_service_id, 'limit_type' => $membership['limit_type']]);
                    }else if($isLdc){
                        $eventClass->sess_epic_credit  = 0;
                        $eventClass->sess_with_invoice = 0;
                    }

                    $this->storeEventrepeatData($request, $eventClass);
                    if (!empty($request->salesProcessStepNumb) && ($request->salesProcessStepNumb > 0)) {
                        if (($eventClass->sess_booking_status == 'Confirmed' || $eventClass->sess_booking_status == 'Pencilled-In') && count($request->classClients) && $clients && (!$request->eventRepeat || $request->eventRepeat == 'None')) {
                            $canLink = true;
                            if ($request->salesProcessStepNumb == 4) {
                                $canLink = false;
                                if ($clients->consultation_date != null) {
                                    //Sales process has been consulted
                                    $eventDate        = new Carbon($eventClass->sess_date);
                                    $consultationDate = new Carbon($clients->consultation_date);
                                    if ($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))) {
                                        //Event booking date is not less than Consultation date and not greater than Consultation end date(Consultation date + 15 days)
                                        $canLink = true;
                                    }
                                }
                            }
                            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus((int) $request->salesProcessStepNumb);
                            $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $eventClass->sess_client_id, $clients->SaleProcessEnabledSteps);
                            if ($canLink && $this->isStepEnabled($request->salesProcessStepNumb, $clients->SaleProcessEnabledSteps) && (($request->salesProcessStepNumb == 2 && $clients->account_status == 'Pending') || $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $eventClass->sess_client_id, $clients->SaleProcessEnabledSteps))) {

                                $eventClass->sess_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
                                $eventClass->save();

                                if ($request->salesProcessStepNumb == 2) {
                                    if ( /*$request->salesProcessStepNumb == 2 && (*/$clients->account_status == 'Pending' /*|| $clients->account_status == 'Active Lead' || $clients->account_status == 'Inactive Lead')*/) {
                                        $this->storeContactNote(['clientId' => $eventClass->sess_client_id, 'status' => 'contacted', 'contactResult' => 'book']);
                                        $clients->account_status = clientStatusPrevSales($salesProcessRelatedStatus);
                                        $this->saveSalesProgress(['clientId' => $clients->id, 'stepNumb' => 1]);
                                    }
                                    $clients->consultation_date = $eventClass->sess_date;
                                }

                                $clientOldSaleProcessStep   = $clients->sale_process_step;
                                $clients->sale_process_step = $request->salesProcessStepNumb;
                                $clients->save();

                                $salesProcessHistory = ['clientId' => $clients->id, 'eventId' => $eventClass->sess_id, 'toType' => $salesProcessRelatedStatus['salesProcessType'], 'toStep' => $salesProcessRelatedStatus['saleProcessStepNumb'], 'fromStep' => $clientOldSaleProcessStep, 'reason' => 'Service booked from sales process'];

                                $this->saveSalesProcess($salesProcessHistory);

                                $msg['salesProcessDate'] = $this->saveSalesProgress(['clientId' => $clients->id, 'stepNumb' => $request->salesProcessStepNumb, 'eventId' => $eventClass->sess_id]);

                                StaffEventSingleService::deleteUnattendedSalesProcess(['clientId' => $eventClass->sess_client_id, 'saleProcessStatus' => [$salesProcessRelatedStatus['salesProcessType']]]);

                                $msg['changeStatus']  = $this->getStatusForbackend($clients->account_status, true) . '|' . $clients->account_status;
                                $msg['stepCompleted'] = $request->salesProcessStepNumb;
                                $msg['bookingDate']   = dbDateToDateString($eventClass->EventDateCarbon);
                            }

                        }
                        $msg['stepsBooked'] = $this->getStepsBooked($clients->id, $clients->sale_process_step);
                    }

                    if ($ifEpicCashChecked) {
                        $new_epic_cash_value_check = $client->epic_credit_balance - $request['servicePrice'];

                        if (($new_epic_cash_value_check < 0)) {
                            $ifEpicCashChecked = 1;
                            $isEpicInvoice     = 1;

                            # Create makeup
                            $classEventMakeup                       = new Makeup();
                            $classEventMakeup->makeup_user_id       = $userId;
                            $classEventMakeup->makeup_user_name     = (isset($client->firstname) ? $client->firstname : '') . ' ' . (isset($client->lastname) ? $client->lastname : '');
                            $classEventMakeup->makeup_client_id     = $client->id;
                            $classEventMakeup->makeup_notes_id      = 0;
                            $classEventMakeup->makeup_purpose       = 'service';
                            $classEventMakeup->makeup_session_count = 0;
                            $classEventMakeup->makeup_amount        = -($client->epic_credit_balance);
                            $classEventMakeup->makeup_extra         = 'EPIC Credit for <a href="' . url('/') . '/dashboard/calendar-new?mevid=' . $eventClass->sess_id . '&mevtype=service">' . date('D, d M Y H:i A', strtotime($request['eventDateTimeEmail'])) . '</a> service';
                            $classEventMakeup->makeup_total_amount  = 0.00;
                            $classEventMakeup->save();

                            $this->setEpicBalance($client->id);

                            $invResponse = $this->raiseAnInvoice($eventClass, $client->id, false, 0, '', abs($new_epic_cash_value_check));

                            /* Start: Invoice partially paid using epic credit*/
                            $paymentData['totalAmount'] = $request['servicePrice'];
                            $paymentData['payAmount'] = $client->epic_credit_balance;
                            $paymentData['outStandingAmount'] = $request['servicePrice'] - $client->epic_credit_balance;
                            $paymentData['paymentType'] = 'EPIC Credit';
                            $isPaymentComplete = $this->autoInvoicePayment($invResponse['invoiceId'], $paymentData);
                            /* End: Invoice partially paid using epic credit*/
                            $isError     = false;
                        } else {
                            $ifEpicCashChecked = 1;
                            $isEpicInvoice     = 0;
                            $invResponse = $this->raiseAnInvoice($eventClass, $client->id, true, 0, '', $request['servicePrice']);
                            $isError = false ;
                        }
                    }

                    if ($isEpicInvoice == 1 && $ifEpicCashChecked == 0) {
                        $invResponse = $this->raiseAnInvoice($eventClass, $key, false);

                        if ($invResponse['status'] != 'error') {
                            if ($invResponse['status'] == 'invoice_exist') {
                                $msg['invExistId'] = $invResponse['invoiceId'];
                            } elseif ($invResponse['status'] == 'new_invoice') {
                                $msg['invDue'] = $eventClass->sess_date;
                            }
                        } else {
                            $isError = true;
                        }
                    }

                    $additionalHistoryText = '';
                    if ($ifEpicCashChecked && $isEpicInvoice) {
                        $additionalHistoryText = ' with invoice and epic credit';
                    } else if ($ifEpicCashChecked) {
                        $additionalHistoryText = ' with epic credit';
                    } else if ($isEpicInvoice) {
                        $additionalHistoryText = ' with invoice';  
                    } else if(!$membership['satisfy'] && $isLdc){
                            $additionalHistoryText = ' with LDC';
                    }

                    $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'add', 'additional' => $additionalHistoryText]);

                    if ($historyText) {
                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);
                    }

                    if ($ifEpicCashChecked && $isEpicInvoice) {
                        $msg['status']  = 'added';
                        $msg['message'] = "Your service is booked with Epic Credit and Invoice.\n Epic Credit Used: $" . number_format($eventClass->sess_price - abs($new_epic_cash_value_check), 2) . "\nInvoice Amount: $" . number_format(abs($new_epic_cash_value_check), 2) . "\n You need to pay invoice to start the service.";
                    } else if ($ifEpicCashChecked) {
                        $msg['status']  = 'added';
                        $msg['message'] = "Service have been booked successfully using epic credit \n Service Price: $" . number_format($request['servicePrice'], 2) . "\n Remaining Epic Credit: $" . number_format($new_epic_cash_value_check, 2);
                    } else if ($isEpicInvoice) {
                        $msg['status']  = 'added';
                        $msg['message'] = "Your service is booked with invoice. \n You need to pay the invoice to start the service.";
                    }else if(!$membership['satisfy'] && $isLdc){
                        $msg['status']  = 'added';
                        $msg['message'] = "Your service is booked with LDC.";
                    } else {
                        $msg['status']  = 'added';
                        $msg['message'] = 'Service has been saved successfully.';
                    }
                }
            } else {
                $msg['status']      = 'error';
                $msg['errorData'][] = array('areaNotLink' => "This staff does not link to area");
                $isError            = true;
            }
        }
        return json_encode($msg);
    }

    /**
     * Check condition for book service
     */
    public function alertForServiceBooking(Request $request)
    {
        $logedUser                 = Auth::user();
        $userId                    = Auth::user()->id;
        $clientId                  = $logedUser->account_id;
        $businessId                = $logedUser->business_id;
        $client                    = Clients::OfBusiness()->find($clientId);
        $startDate                 = $this->calcEventDate($request, $request->classDate);
        $membership                = $this->satisfyMembershipRestrictions($clientId, ['event_type' => 'service', 'event_id' => $request->staffservice, 'event_date' => $startDate]);
        $isError                   = false;
        $isEpicInvoice             = 0;
        $ifEpicCashChecked         = 0;
        $new_epic_cash_value_check = 0;
        $isLdc                     = 0;
        $forceAdd                  = true;
        $msg                       = $satisfyMembershipClients                       = [];

        if ($client && $client->account_status != 'Active' && $client->account_status != 'Contra') {
            $isError            = true;
            $msg['status']      = 'error';
            $msg['message']         = "Your account is not approved.";

        } else if (!$membership['satisfy']) {
            $optionalData = [
                'sess_date' => $request->classDate,
                'sess_service_id' => $request->staffservice
            ];
            $isSatisfyLdc = $this->isSatisfyLdcRestriction($clientId,$request->eventId,'','service',$optionalData);
            if(!$isSatisfyLdc){        
            if (($request->has('isEpicCash') && $request->isEpicCash) || ($client->epic_credit_balance > 0)) {
                if ($client->epic_credit_balance >= $request['servicePrice']) {
                    $ifEpicCashChecked = 1;
                    $isEpicInvoice     = 0;
                } else {
                    $ifEpicCashChecked = 1;
                    $isEpicInvoice     = 1;
                }

            } else {
                $ifEpicCashChecked = 0;
                $isEpicInvoice     = 1;
            }
            }else{
                    $isLdc = 1;
            } 
         } else if ($membership['satisfy']) {
            $ifEpicCashChecked          = 0;
            $isEpicInvoice              = 0;
            $satisfyMembershipClients[] = $clientId;
        }

        $service_detail                = Service::find($request->staffservice);
        $request->salesProcessStepNumb = $service_detail['for_sales_process_step'];
        if (!Staff::ifstaffExist($request->staff)) {
            $isError            = true;
            $msg['status']      = 'error';
            $msg['message']     = "This staff does not exist.";
        } elseif ($request->modalLocArea == '') {
            $isError            = true;
            $msg['status']      = 'error';
            $msg['message']     = "This area does not exist.";
        }

        if (!$isError) {
            $areaIds                   = array_map('intval', $request->modalLocArea);
            $staffId                   = (int) $request->staff;
            $countOfAreasLinkedToStaff = $this->getCountOfAreasLinkedToStaff(['areaId' => $areaIds, 'staffId' => $staffId]);

            if ($countOfAreasLinkedToStaff == count($request->modalLocArea)) {
                $isAreaLinkedToStaff = true;
            } else {
                $isAreaLinkedToStaff = false;
            }

            if ($isAreaLinkedToStaff) {
                $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->classTime, 'startDate' => $startDate, 'duration' => $request->serviceDur]);

                $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                $eventEndDatetime   = $startAndEndDatetime['endDatetime'];
                /* service can't book within next 24 hours */
                $nxt_24 = date("Y-m-d H:m:s", (strtotime(date("Y-m-d H:m:s")) + (60 * 60 * 24)));

                if ($startAndEndDatetime['startDatetime'] < $nxt_24) {
                    $isError            = true;
                    $msg['status']      = 'error';
                    $msg['message']     = 'Bookings are not allowed before ' . $nxt_24;
                } else if (ClosedDate::ifOverlapping($startDate, $startAndEndDatetime['endDate'])) {
                    $isError            = true;
                    $msg['status']      = 'error';
                    $msg['message']     = 'Business is closed on specified date';
                }
                if (!$isError) {
                    if ($request->appointStatusOpt == 'Confirmed' && ($request->eventRepeat == 'None' || !$request->eventRepeat)) {
                        $busyAreaIds = $this->areAreasBusy(['areaId' => $request->modalLocArea, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime]);

                        if (count($busyAreaIds)) {
                            $msg['status']      = 'error';
                            $msg['message']     = "Area is Unavilable";
                            $isError            = true;
                        } else if ($this->isStaffBusy(['staffId' => $request->staff, 'day' => $request->classDay, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime])) {

                            $msg['status']      = 'error';
                            $msg['message']     = "Staff is busy at this particular hour";
                            $isError            = true;
                        }
                    }

                    if (count($request->classClients)) {
                        foreach ($request->classClients as $key => $value) {
                            $clientIdsToCheck[]            = $key;
                        }
                        $clients          = Clients::find($clientIdsToCheck[0]);
                        $busyClientIds = $this->isClientBusy(['clientId' => $clientIdsToCheck, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');

                        if (count($busyClientIds)) {
                            $msg['status']      = 'error';
                            $msg['message']     = "You can not book this service because you are busy at specified hours.";
                            $isError            = true;
                        }
                    } else if ((empty($request->salesProcessStepNumb) || $request->salesProcessStepNumb == 0) /*&& !$forceAdd*/) {
                        $membership = $this->satisfyMembershipRestrictions($clientId, ['event_type' => 'service', 'event_id' => $request->staffservice, 'event_date' => $startDate]);
                        // dd($membership);
                        if ($membership['satisfy']) {
                            $satisfyMembershipClients[] = $clientId;
                        }else {
                            $msg['status']      = 'error';
                            $msg['message']     = "Client membership restrictions not satisfy";
                            $isError            = true;
                        }
                    }
                }

                if (!$isError) {
                    /* Epic */
                    $new_epic_cash_value_check = $client->epic_credit_balance - $request['servicePrice'];
                    if ($ifEpicCashChecked == 1 && $isEpicInvoice == 1) {
                        $msg['status']  = 'ok';
                        $msg['message'] = "Your service will be booked with Epic Credit and Invoice.\n Epic Credit Used: $" . number_format($request->servicePrice - abs($new_epic_cash_value_check), 2) . "\nInvoice Amount: $" . number_format(abs($new_epic_cash_value_check), 2) . "\n You need to pay invoice to start the service.";
                    } else if ($ifEpicCashChecked == 1 && $isEpicInvoice == 0) {
                        $msg['status']  = 'ok';
                        $msg['message'] = "Your service will be booked with epic credit \n Service Price: $" . number_format($request['servicePrice'], 2) . "\n Remaining Epic Credit: $" . number_format($new_epic_cash_value_check, 2);
                    } else if ($isEpicInvoice == 1 && $ifEpicCashChecked == 0) {
                        $msg['status']  = 'ok';
                        $msg['message'] = "Your service will be booked with invoice. \nInvoice Amount: $" . number_format(abs($request->servicePrice), 2) . "\n You need to pay the invoice to start the service.";
                    }else if($isLdc){
                        $msg['status']      = 'ok';
                        $msg['message']     = "Your Service will be Booked with membership LDC";
                    } else if($membership['satisfy']) {
                        $msg['status']  = 'ok';
                        $msg['message'] = "Your service will be booked with membership";
                    }
                }
            } else {
                $msg['status']      = 'error';
                $msg['message']     = "This staff does not link to area";
                $isError            = true;
            }
        }
        return json_encode($msg);
    }

    /**
     * Delete client appointment from result
     *
     * @param
     * @return
     */
    /**
     * Remove service from client
     * @param Request
     * @return Response
     */
    public function appointmentDestroy(Request $request)
    {
        $isError = $isEpicCreated = false;
        $msg     = [];
        $event   = StaffEventSingleService::OfBusiness()->find($request->eventId);
        $additionalHistoryText = '';

        # Get invoice details
        $invoice = Invoice::where('inv_client_id', $event->sess_client_id)
            ->whereHas('invoiceitem', function ($query) use ($request) {
                $query->where('inp_product_id', $request->eventId)
                    ->where('inp_type', 'service');
            })->first();

        if (!$event) {
            $msg['status']       = 'error';
            $msg['errorData'][]  = array('clientNotFount' => $request->clientId);
            $msg['unsatisyReas'] = 'client_not_found';
            $isError             = true;

            return json_encode($msg);
        }

        if (count($event)) {
            if($request->has('isEpicCash') && $request->isEpicCash == 'yes'){
            if ($event->sess_epic_credit == 1 && $event->sess_with_invoice == 0) {
                $makeup                   = new Makeup;
                $makeup->makeup_client_id = $event->sess_client_id;
                $makeup->makeup_amount    = $event->sess_price;
                $makeup->makeup_notes_id  = 0;
                $makeup->makeup_purpose   = 'service';
                $makeup->makeup_extra     = 'EPIC Credit for <a href="https://epictrainer.com/dashboard/calendar-new?mevid=' . $event->sess_id . '&mevtype=service">' . date('D, d M Y H:i A', strtotime($event->sess_start_datetime)) . '</a> service';
                $makeup->makeup_user_id   = $makeup->UserInformation['id'];
                $makeup->makeup_user_name = $makeup->UserInformation['name'];
                $makeup->save();
                $event->sess_is_make_up = 0;
                $event->sess_if_make_up = 1;

                /* Delete linked paid invoice */
                if ($invoice && $invoice->inv_status == 'Paid') {
                    $this->deleteEventInvoice($event->sess_id, $event->sess_client_id, 'service', true);
                }

            } else if ($event->sess_epic_credit == 1 && $event->sess_with_invoice == 1 && $invoice) {
                # Epic credit used in invoice
                $epicCreditUsed = InvoiceItems::where('inp_invoice_id', $invoice->inv_id)->sum('inp_paid_using_epic_credit');

                $makeup                   = new Makeup;
                $makeup->makeup_client_id = $event->sess_client_id;
                $makeup->makeup_amount    = $invoice->inv_status == 'Paid' ? $event->sess_price : $epicCreditUsed;
                $makeup->makeup_notes_id  = 0;
                $makeup->makeup_purpose   = 'service';
                $makeup->makeup_extra     =  'EPIC Credit for <a href="https://epictrainer.com/dashboard/calendar-new?mevid=' . $event->sess_id . '&mevtype=service">' . date('D, d M Y H:i A', strtotime($event->sess_start_datetime)) . '</a> service';
                $makeup->makeup_user_id   = $makeup->UserInformation['id'];
                $makeup->makeup_user_name = $makeup->UserInformation['name'];
                $makeup->save();
                $event->sess_is_make_up = 0;
                $event->sess_if_make_up = 1;
            }else if($event->sess_cmid != 0){
                $clientDetails = Clients::paidMembership($event->sess_client_id);
                $makeup                   = new Makeup;
                $makeup->makeup_client_id = $event->sess_client_id;
                if($clientDetails){
                    $service_limits = json_decode($clientDetails->cm_services_limit, 1);
                    $serviceId = $event->sess_service_id;
                    if($service_limits[$serviceId]['discount_price_type'] == 'serviceUnitPrice' &&  $service_limits[$serviceId]['discount_type'] == 'fixed'){
                        $makeup->makeup_amount = $service_limits[$serviceId]['discount_amount'];
                    }else if( $service_limits[$serviceId]['discount_price_type'] == 'serviceUnitPrice' &&  $service_limits[$serviceId]['discount_type'] == 'percent'){
                        $makeup->makeup_amount =  $service_limits[$serviceId]['serviceDiscountPerData'];
                    }else{
                        $makeup->makeup_amount    = $event->sess_price;
                    }
                   
                }
                $makeup->makeup_notes_id  = 0;
                $makeup->makeup_purpose   = 'service';
                $makeup->makeup_extra     =  'EPIC Credit for <a href="https://epictrainer.com/dashboard/calendar-new?mevid=' . $event->sess_id . '&mevtype=service">' . date('D, d M Y H:i A', strtotime($event->sess_start_datetime)) . '</a> service';
                $makeup->makeup_user_id   = $makeup->UserInformation['id'];
                $makeup->makeup_user_name = $makeup->UserInformation['name'];
                $makeup->save();
                $event->sess_is_make_up = 0;
                $event->sess_if_make_up = 1;
            }
            $this->setEpicBalance($event->sess_client_id);

        }else{
            if($event->is_ldc == 0){
                # Set info log
                if($event->sess_cmid != 0) 
                setInfoLog('Client membership limit updated on service cancelled  ',  $event->sess_client_id);
            }
        }

            /* Delete linked invoice */
            if ($invoice) 
                $this->deleteEventInvoice($event->sess_id, $event->sess_client_id, 'service', true);

            # Set info log
          
            // $event->delete();
            $logMessage = '';

            if($event->sess_cmid != 0 && $request->isEpicCash != 'yes') {
                if($event->is_ldc){
                    $logMessage = 'Cancelled with membership LDC';
                    $additionalHistoryText = 'cancelled with membership LDC';
                }else{
                    $logMessage = 'Cancelled with membership';
                    $additionalHistoryText = 'cancelled with membership';
                }
            } else if($event->sess_with_invoice && $event->sess_epic_credit && $request->isEpicCash == 'yes') {
                $logMessage = 'Cancelled with epic credit and invoice';
                $additionalHistoryText = 'cancelled with epic credit and invoice';
            } else if($event->sess_with_invoice && $request->isEpicCash != 'yes') {
                $logMessage = 'Cancelled with invoice';
                $additionalHistoryText = 'cancelled with invoice';
            } else if($event->sess_epic_credit && $request->isEpicCash == 'yes') {
                $logMessage = 'Cancelled with epic credit';
                $additionalHistoryText = 'cancelled with epic credit';
            }else if($event->sess_epic_credit && $request->isEpicCash != 'yes'){
                $logMessage = 'Cancelled without epic credit';
                $additionalHistoryText = 'cancelled without epic credit';
            }

            $event->sess_event_log = $logMessage;
            $event->sess_action_performed_by = getLoggedUserName();
            $event->deleted_at = createTimestamp();
            $event->save();
            
            if($event->sess_cmid != 0 && $request->isEpicCash != 'yes') {
                if($event->is_ldc == 0){
                    $this->membershipLimitReset($event->sess_client_id);
                }
            }


            $saleProcessStepNumb = $this->saleProcessStatusToStepNumb($event->sess_sale_process_status);
            if ($saleProcessStepNumb != null) {
                /*if($event->sess_sale_process_status == 'book_team')
                $this->manageTeamSalesProcessOfClient($event->sess_client_id, $event->sess_id);
                else{*/
                $client = Clients::find($event->sess_client_id);
                if ( /*($event->sess_sale_process_status == 'book_consult' && $client->consultation_date == null) || isClientInSalesProcess($client->consultation_date)*/$client->account_status != 'Active' && $client->account_status != 'Contra' && /*$this->isClientInSalesProcess($event->sess_sale_process_status, $client->consultation_date)*/isClientInSalesProcess($client->consultation_date) && $this->isStepEnabled($saleProcessStepNumb, $client->SaleProcessEnabledSteps)) {

                    /*$prevStepNumb = $saleProcessStepNumb-1;
                    if($this->checkSalesProgress($prevStepNumb, $event->sess_client_id)){
                    $newSalesProcess = calcSalesProcessRelatedStatus($prevStepNumb);
                    $client->account_status = $newSalesProcess['clientStatus'];

                    $salesProcessHistory = ['clientId'=>$event->sess_client_id, 'eventId'=>$event->sess_id, 'toType'=>$newSalesProcess['salesProcessType'], 'toStep'=>$newSalesProcess['saleProcessStepNumb'], 'action'=>'downgrade', 'reason'=>'Service booking deleted'];
                    $this->saveSalesProcess($salesProcessHistory);
                    }*/
                    if ($saleProcessStepNumb == 2) {
                        $client->consultation_date = null;
                    }

                    //$client->sale_process_step = $prevStepNumb;
                    $client->save();

                    $this->deleteSalesProgress($saleProcessStepNumb, $client->id);
                }
            }

            /* Start: create history for cancelling service */
            if($additionalHistoryText)
                $historyText = 'Service ' . $additionalHistoryText;
            else 
                $historyText = 'Service cancelled.';
            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $event]);
            /* End: create history for cancelling service */

            $respMessg = 'Service has been cancelled.';
            if($request->has('isEpicCash') && $request->isEpicCash == 'yes'){
                $respMessg = "Service has been cancelled.\nYour Epic Credit has been credited with $".$makeup->makeup_amount;
            }

            $msg['status']  = 'deleted';
            $msg['message'] = $respMessg;
        }
        return json_encode($msg);
    }

    public function appointmentDestroyMobile(Request $request)
    {
        $isError = $isEpicCreated = false;
        $msg     = [];
        $event   = StaffEventSingleService::OfBusiness()->find($request->eventId);
        $additionalHistoryText = '';

        # Get invoice details
        $invoice = Invoice::where('inv_client_id', $event->sess_client_id)
            ->whereHas('invoiceitem', function ($query) use ($request) {
                $query->where('inp_product_id', $request->eventId)
                    ->where('inp_type', 'service');
            })->first();

        if (!$event) {
            $msg['status']       = 'error';
            $msg['errorData'][]  = array('clientNotFount' => $request->clientId);
            $msg['unsatisyReas'] = 'client_not_found';
            $isError             = true;

            return json_encode($msg);
        }

        if (count($event)) {
            if($request->has('isEpicCash') && $request->isEpicCash == 'yes'){
                if ($event->sess_epic_credit == 1 && $event->sess_with_invoice == 0) {
                    $makeup                   = new Makeup;
                    $makeup->makeup_client_id = $event->sess_client_id;
                    $makeup->makeup_amount    = $event->sess_price;
                    $makeup->makeup_notes_id  = 0;
                    $makeup->makeup_purpose   = 'service';
                    $makeup->makeup_user_id   = $makeup->UserInformation['id'];
                    $makeup->makeup_user_name = $makeup->UserInformation['name'];
                    $makeup->save();
                    $event->sess_is_make_up = 0;
                    $event->sess_if_make_up = 1;
    
                    /* Delete linked paid invoice */
                    if ($invoice && $invoice->inv_status == 'Paid') {
                        $this->deleteEventInvoice($event->sess_id, $event->sess_client_id, 'service', true);
                    }
    
                } else if ($event->sess_epic_credit == 1 && $event->sess_with_invoice == 1 && $invoice) {
                    # Epic credit used in invoice
                    $epicCreditUsed = InvoiceItems::where('inp_invoice_id', $invoice->inv_id)->sum('inp_paid_using_epic_credit');
    
                    $makeup                   = new Makeup;
                    $makeup->makeup_client_id = $event->sess_client_id;
                    $makeup->makeup_amount    = $invoice->inv_status == 'Paid' ? $event->sess_price : $epicCreditUsed;
                    $makeup->makeup_notes_id  = 0;
                    $makeup->makeup_purpose   = 'service';
                    $makeup->makeup_user_id   = $makeup->UserInformation['id'];
                    $makeup->makeup_user_name = $makeup->UserInformation['name'];
                    $makeup->save();
                    $event->sess_is_make_up = 0;
                    $event->sess_if_make_up = 1;
                }else if($event->sess_cmid != 0){
                    $clientDetails = Clients::paidMembership($event->sess_client_id);
                    $makeup                   = new Makeup;
                    $makeup->makeup_client_id = $event->sess_client_id;
                    if($clientDetails){
                        $service_limits = json_decode($clientDetails->cm_services_limit, 1);
                        $serviceId = $event->sess_service_id;
                        if($service_limits[$serviceId]['discount_price_type'] == 'serviceUnitPrice' &&  $service_limits[$serviceId]['discount_type'] == 'fixed'){
                            $makeup->makeup_amount = $service_limits[$serviceId]['discount_amount'];
                        }else if( $service_limits[$serviceId]['discount_price_type'] == 'serviceUnitPrice' &&  $service_limits[$serviceId]['discount_type'] == 'percent'){
                            $makeup->makeup_amount =  $service_limits[$serviceId]['serviceDiscountPerData'];
                        }else{
                            $makeup->makeup_amount    = $service_limits[$serviceId]['mem_unit_price'];
                        }
                    
                    }
                    // $makeup->makeup_amount    = $event->sess_price;
                    $makeup->makeup_notes_id  = 0;
                    $makeup->makeup_purpose   = 'service';
                    $makeup->makeup_user_id   = $makeup->UserInformation['id'];
                    $makeup->makeup_user_name = $makeup->UserInformation['name'];
                    $makeup->save();
                    $event->sess_is_make_up = 0;
                    $event->sess_if_make_up = 1;
                }
    
                $this->setEpicBalance($event->sess_client_id);
            }else{
                if($event->is_ldc == 0){
                    # Set info log
                    if($event->sess_cmid != 0) 
                    setInfoLog('Client membership limit updated on service cancelled  ',  $event->sess_client_id);
                }
            }

            /* Delete linked invoice */
            if ($invoice) 
                $this->deleteEventInvoice($event->sess_id, $event->sess_client_id, 'service', true);

            // $event->delete();
            $logMessage = '';
            if($event->sess_cmid != 0 && $request->isEpicCash != 'yes') {
                if($event->is_ldc){
                    $logMessage = 'Cancelled with membership LDC';
                    $additionalHistoryText = 'cancelled with membership LDC';
                }else{
                    $logMessage = 'Cancelled with membership';
                    $additionalHistoryText = 'cancelled with membership';
                }
            } else if($event->sess_with_invoice && $event->sess_epic_credit && $request->isEpicCash == 'yes') {
                $logMessage = 'Cancelled with epic credit and invoice';
                $additionalHistoryText = 'cancelled with epic credit and invoice';
            } else if($event->sess_with_invoice && $request->isEpicCash != 'yes') {
                $logMessage = 'Cancelled with invoice';
                $additionalHistoryText = 'cancelled with invoice';
            } else if($event->sess_epic_credit && $request->isEpicCash == 'yes') {
                $logMessage = 'Cancelled with epic credit';
                $additionalHistoryText = 'cancelled with epic credit';
            }else if($event->sess_epic_credit && $request->isEpicCash != 'yes'){
                $logMessage = 'Cancelled without epic credit';
                $additionalHistoryText = 'cancelled without epic credit';
            }

            $event->sess_event_log = $logMessage;
            $event->sess_action_performed_by = getLoggedUserName();
            $event->deleted_at = createTimestamp();
            $event->save();

            /* Memebership reset */
            if($event->sess_cmid != 0 && $request->isEpicCash != 'yes') {
                if($event->is_ldc == 0){
                    $this->membershipLimitReset($event->sess_client_id);
                }
            }

            $saleProcessStepNumb = $this->saleProcessStatusToStepNumb($event->sess_sale_process_status);
            if ($saleProcessStepNumb != null) {
                /*if($event->sess_sale_process_status == 'book_team')
                $this->manageTeamSalesProcessOfClient($event->sess_client_id, $event->sess_id);
                else{*/
                $client = Clients::find($event->sess_client_id);
                if ( /*($event->sess_sale_process_status == 'book_consult' && $client->consultation_date == null) || isClientInSalesProcess($client->consultation_date)*/$client->account_status != 'Active' && $client->account_status != 'Contra' && /*$this->isClientInSalesProcess($event->sess_sale_process_status, $client->consultation_date)*/isClientInSalesProcess($client->consultation_date) && $this->isStepEnabled($saleProcessStepNumb, $client->SaleProcessEnabledSteps)) {

                    /*$prevStepNumb = $saleProcessStepNumb-1;
                    if($this->checkSalesProgress($prevStepNumb, $event->sess_client_id)){
                    $newSalesProcess = calcSalesProcessRelatedStatus($prevStepNumb);
                    $client->account_status = $newSalesProcess['clientStatus'];

                    $salesProcessHistory = ['clientId'=>$event->sess_client_id, 'eventId'=>$event->sess_id, 'toType'=>$newSalesProcess['salesProcessType'], 'toStep'=>$newSalesProcess['saleProcessStepNumb'], 'action'=>'downgrade', 'reason'=>'Service booking deleted'];
                    $this->saveSalesProcess($salesProcessHistory);
                    }*/
                    if ($saleProcessStepNumb == 2) {
                        $client->consultation_date = null;
                    }

                    //$client->sale_process_step = $prevStepNumb;
                    $client->save();

                    $this->deleteSalesProgress($saleProcessStepNumb, $client->id);
                }
            }

            /* Start: create history for cancelling service */
            if($additionalHistoryText)
                $historyText = 'Service ' . $additionalHistoryText;
            else 
                $historyText = 'Service cancelled.';
            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $event]);
            /* End: create history for cancelling service */

            $respMessg = 'Service has been cancelled.';
            if($request->has('isEpicCash') && $request->isEpicCash == 'yes'){
                $respMessg = "Service has been cancelled.\nYour Epic Credit has been credited with $".$makeup->makeup_amount;
            }

            $msg['status']  = 'deleted';
            $msg['message'] = $respMessg;
        }
        return json_encode($msg);
    }

    /**
     * Update client appointment
     *
     * @param
     * @return
     */
    public function appointmentUpdate(Request $request)
    {
        // dd($request->all());
        $isError = false;
        $msg     = [];

        if ($request->serviceId) {
            $service_detail                = Service::find($request->serviceId);
            $request->salesProcessStepNumb = $service_detail['for_sales_process_step'];
        }

        $current_choosedtime = Carbon::now()->toDateTimeString();
        if ($request->initial_choosed_time < $current_choosedtime) {
            $msg['status']      = 'error';
            $msg['errorData'][] = array('notAllowedPassedTime' => true);
            $isError            = true;
        }

        if (!$isError) {
            $countOfAreasLinkedToStaff = $this->getCountOfAreasLinkedToStaff(['areaId' => $request->modalLocArea, 'staffId' => $request->staff]);

            if ($countOfAreasLinkedToStaff == count($request->modalLocArea)) {
                $isAreaLinkedToStaff = true;
            } else {
                $isAreaLinkedToStaff = false;
            }

            if ($isAreaLinkedToStaff) {
                $startDate           = $this->calcEventDate($request, $request->classDate);
                $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->classTime, 'startDate' => $startDate, 'duration' => $request->serviceDur]);
                $eventStartDatetime  = $startAndEndDatetime['startDatetime'];
                $eventEndDatetime    = $startAndEndDatetime['endDatetime'];

                if ($request->appointStatusOpt == 'Confirmed' && ($request->eventRepeat == 'None' || !$request->eventRepeat)) {
                    $busyAreaIds = $this->areAreasBusy(['eventId' => $request->eventId, 'areaId' => $request->modalLocArea, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');
                    if (count($busyAreaIds)) {
                        $msg['status']      = 'error';
                        $msg['errorData'][] = array('areaUnavail' => $busyAreaIds);
                        $isError            = true;
                    } elseif ($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $request->staff, 'day' => $request->classDay, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment')) {

                        $msg['status']      = 'error';
                        $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                        $isError            = true;
                    } elseif (count($request->classClients)) {
                        foreach ($request->classClients as $key => $value) {
                            $clientIdsToCheck[] = $key;
                        }

                        $busyClientIds = $this->isClientBusy(['eventId' => $request->eventId, 'clientId' => $clientIdsToCheck, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');

                        if (count($busyClientIds)) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('clientUnavail' => $busyClientIds);
                            $isError            = true;
                        }
                    }
                }

                if (!$isError) {
                    $eventClass = StaffEventSingleService::OfBusiness()->find($request->eventId);
                    if (count($eventClass)) {
                        $eventClassOld = $eventClass->replicate();
                        if ($eventClass->sess_sessr_id != 0) {
                            $eventClassRepeatOld = $eventClass->repeat->first();
                        }

                        $historyText      = $request->historyText;
                        $alertHistoryText = '';

                        $eventClass->sess_date           = $startDate;
                        $eventClass->sess_time           = $request->classTime;
                        $eventClass->sess_start_datetime = $eventStartDatetime;
                        $eventClass->sess_end_datetime   = $eventEndDatetime;
                        $eventClass->sess_notes          = $request->classNote;
                        $eventClass->sess_staff_id       = $request->staff;

                        if (count($request->classClients)) {
                            foreach ($request->classClients as $key => $value) {
                                if ($eventClass->sess_client_id != $key) {
                                    //Client changed
                                    $clients       = Clients::find([$eventClass->sess_client_id, $key]);
                                    $removedClient = $clients->where('id', $eventClass->sess_client_id)->first();
                                    $historyText .= $this->eventclassClientHistory(['clients' => [$removedClient], 'action' => 'remove']);
                                    $dataForEmail                     = new stdClass();
                                    $dataForEmail->eventDateTimeEmail = dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $eventClassOld->sess_start_datetime));

                                    $allPrevAreas = $eventClass->areas;
                                    if ($allPrevAreas->count()) {
                                        $allPrevAreasId = $allPrevAreas->pluck('la_id')->toArray();
                                    } else {
                                        $allPrevAreasId = [];
                                    }

                                    $dataForEmail->modalLocArea = $allPrevAreasId;
                                    $dataForEmail->staffservice = $eventClassOld->sess_service_id;
                                    $dataForEmail->staff        = $eventClassOld->sess_staff_id;
                                    $alertHistoryText .= $this->sendClientServiceBookingEmail('cancel', $dataForEmail, $removedClient);

                                    $eventClass->sess_client_id    = $key;
                                    $eventClass->sess_client_notes = $value['note'];
                                    $addedClient                   = $clients->where('id', $key)->first();
                                    $historyText .= $this->eventclassClientHistory(['clients' => [$addedClient], 'action' => 'add']);
                                    $alertHistoryText .= $this->sendClientServiceBookingEmail('confirm', $request, $addedClient);
                                } else {
                                    $clients = Clients::find($eventClass->sess_client_id);
                                }

                            }
                        }

                        $eventClass->sess_booking_status       = 'Pencilled-In';
                        $eventClass->sess_auto_expire          = '';
                        $eventClass->sess_auto_expire_datetime = null;
                        if ($eventClass->sess_booking_status == 'Pencilled-In' && $request->ifAutoExpireAppoint == 1) {
                            if ($request->autoExpireAppointDur != 'Custom') {
                                $eventClass->sess_auto_expire = $request->autoExpireAppointDur;
                            }

                            $eventClass->sess_auto_expire_datetime = $request->autoExpireDatetime;
                        }

                        $eventClass->sess_service_id           = $request->staffservice;
                        $eventClass->sess_duration             = $request->serviceDur;
                        $eventClass->sess_price                = $request->servicePrice;
                        $eventClass->sess_auto_expire          = 24;
                        $eventClass->sess_auto_expire_datetime = date("Y-m-d H:m:s", (strtotime($eventStartDatetime) - (60 * 60 * 24)));

                        $eventClass->update();

                        if (!empty($request->salesProcessStepNumb) && $request->salesProcessStepNumb > 0) {
                            if (($eventClass->sess_booking_status == 'Confirmed') && count($request->classClients) && $clients && (!$request->eventRepeat || $request->eventRepeat == 'None')) {

                                $canLink = true;
                                if ($request->salesProcessStepNumb == 4) {
                                    $canLink = false;

                                    if ($clients->consultation_date != null) {
                                        //Sales process has been consulted
                                        $eventDate        = new Carbon($eventClass->sess_date);
                                        $consultationDate = new Carbon($clients->consultation_date);
                                        if ($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))) {
                                            //Event booking date is not less than Consultation date and not greater than Consultation end date(Consultation date + 15 days)
                                            $canLink = true;
                                        }
                                    }
                                }

                                $salesProcessRelatedStatus = calcSalesProcessRelatedStatus((int) $request->salesProcessStepNumb);
                                // dd($salesProcessRelatedStatus);
                                $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $eventClass->sess_client_id, $clients->SaleProcessEnabledSteps);
                                if ($canLink && $this->isStepEnabled($request->salesProcessStepNumb, $clients->SaleProcessEnabledSteps) && (($request->salesProcessStepNumb == 2 && $clients->account_status == 'Pending') || $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $eventClass->sess_client_id, $clients->SaleProcessEnabledSteps))) {

                                    /*if(statusMatchSalesStatus($clients->account_status, $salesProcessRelatedStatus['clientPrevStatus']) || ($request->salesProcessStepNumb == 2 && ($clients->account_status == 'Pending' /*|| $clients->account_status == 'Active Lead' || $clients->account_status == 'Inactive Lead'*))){*/
                                    $eventClass->sess_sale_process_status = $salesProcessRelatedStatus['salesProcessType'];
                                    DB::enableQueryLog();

                                    $eventClass->save();
                                    // dd(DB::getQueryLog());
                                    if ($request->salesProcessStepNumb == 2) {
                                        if ( /*$request->salesProcessStepNumb == 2 && (*/$clients->account_status == 'Pending' /*|| $clients->account_status == 'Active Lead' || $clients->account_status == 'Inactive Lead')*/) {
                                            $this->storeContactNote(['clientId' => $eventClass->sess_client_id, 'status' => 'contacted', 'contactResult' => 'book']);
                                            $clients->account_status = clientStatusPrevSales($salesProcessRelatedStatus);
                                            $this->saveSalesProgress(['clientId' => $clients->id, 'stepNumb' => 1]);
                                        }
                                        $clients->consultation_date = $eventClass->sess_date;
                                    }

                                    $clientOldSaleProcessStep   = $clients->sale_process_step;
                                    $clients->sale_process_step = $request->salesProcessStepNumb;
                                    $clients->save();

                                    $salesProcessHistory = ['clientId' => $clients->id, 'eventId' => $eventClass->sess_id, 'toType' => $salesProcessRelatedStatus['salesProcessType'], 'toStep' => $salesProcessRelatedStatus['saleProcessStepNumb'], 'fromStep' => $clientOldSaleProcessStep, 'reason' => 'Service booked from sales process'];
                                    // echo 'abhi <br>';
                                    // dd($salesProcessHistory);

                                    /*$msg['salesProcessDate'] =*/$this->saveSalesProcess($salesProcessHistory);

                                    $msg['salesProcessDate'] = $this->saveSalesProgress(['clientId' => $clients->id, 'stepNumb' => $request->salesProcessStepNumb, 'eventId' => $eventClass->sess_id]);

                                    StaffEventSingleService::deleteUnattendedSalesProcess(['clientId' => $eventClass->sess_client_id, 'saleProcessStatus' => [$salesProcessRelatedStatus['salesProcessType']]]);

                                    $msg['changeStatus']  = $this->getStatusForbackend($clients->account_status, true) . '|' . $clients->account_status;
                                    $msg['stepCompleted'] = $request->salesProcessStepNumb;
                                    $msg['bookingDate']   = dbDateToDateString($eventClass->EventDateCarbon);
                                }

                            }
                            // dd('fffs');
                            $msg['stepsBooked'] = $this->getStepsBooked($clients->id, $clients->sale_process_step);
                        }

                        $request->oldDate = $eventClassOld->sess_date;
                        $response         = $this->manageAreasLinkage($eventClass, $request->modalLocArea);
                        $allPrevAreasId   = $response['prevAreas'];
                        $historyText .= $response['history'];
                        /* end:Area manage section */

                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);
                        $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);

                        /* Memebership reset */
                        $this->membershipLimitReset($eventClass->sess_client_id);
                        $msg['status']  = 'added';
                        $msg['message'] = 'Service has been saved successfully.';
                    }
                }
            } else {
                $msg['status']      = 'error';
                $msg['errorData'][] = array('error' => "This staff does not link to area");
                $isError            = true;
            }
        }

        return json_encode($msg);
    }

    /**
     * Update client appointment
     *
     * @param
     * @return
     */
    public function appointmentUpdateMobile(Request $request)
    {
        // dd($request->all());
        $isError = false;
        $msg     = [];
        $eventClass = StaffEventSingleService::with('areas')->OfBusiness()->find($request->eventId);
        if($eventClass){
            $currentDateTime = Carbon::now()->toDateTimeString();
            $newDateTime = $request->classDate.' '.$eventClass->sess_time;
            if ($newDateTime < $currentDateTime) {
                $msg['status']      = 'error';
                $msg['errorData'][] = array('notAllowedPassedTime' => displayAlert("warning| Choose another booking time."));
                $isError            = true;
            }
            $datas = [
                'eventId' => $request->eventId,
                'service_id' => $eventClass->sess_service_id,
                'start_time' => $eventClass->sess_time,
                'start_date' => $request->classDate,
                'duration' => $eventClass->sess_duration
            ];
            $freeAreas = $this->freeAreas($datas);
            $freeStaffs = $this->freeStaffs($datas);
            $eventStaff = $eventClass->sess_staff_id;
            $eventAreas = [];
            if(count($eventClass->areas)){
                $eventAreas = $eventClass->areas->pluck('la_id')->toArray();
            }
            if(!in_array($eventStaff,$freeStaffs)){
                $msg['status']      = 'error';
                $msg['errorData'][] = array('staffUnavail' => displayAlert("warning| Staff is not available."));
                $isError            = true;
            }
            if(count(array_diff($eventAreas, $freeAreas))){
                $msg['status']      = 'error';
                $msg['errorData'][] = array('areaUnavail' => displayAlert("warning| Area is not available."));
                $isError            = true;
            }
            if (!$isError) {
                $countOfAreasLinkedToStaff = $this->getCountOfAreasLinkedToStaff(['areaId' => $eventAreas, 'staffId' => $eventStaff]);
    
                if ($countOfAreasLinkedToStaff == count($eventAreas)) {
                    $isAreaLinkedToStaff = true;
                } else {
                    $isAreaLinkedToStaff = false;
                }
    
                if ($isAreaLinkedToStaff) {
                    $startDate           = $request->classDate;
                    $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $eventClass->sess_time, 'startDate' => $startDate, 'duration' => $eventClass->sess_duration]);
                    $eventStartDatetime  = $startAndEndDatetime['startDatetime'];
                    $eventEndDatetime    = $startAndEndDatetime['endDatetime'];
    
                    if ($eventClass->sess_booking_status == 'Confirmed' &&  $eventClass->sess_sessr_id == 0) {
                        $busyAreaIds = $this->areAreasBusy(['eventId' => $request->eventId, 'areaId' => $eventAreas, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');
                        if (count($busyAreaIds)) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('areaUnavail' => displayAlert("warning| Area is not available."));
                            $isError            = true;
                        } elseif ($this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $eventStaff, 'day' => Carbon::parse($startDate)->format('l'), 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment')) {
    
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                            $isError            = true;
                        } elseif ($eventClass->sess_client_id) {
                            $clientIdsToCheck[] = $eventClass->sess_client_id;
                            $busyClientIds = $this->isClientBusy(['eventId' => $request->eventId, 'clientId' => $clientIdsToCheck, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'appointment');
    
                            if (count($busyClientIds)) {
                                $msg['status']      = 'error';
                                $msg['errorData'][] = array('clientUnavail' => $busyClientIds);
                                $isError            = true;
                            }
                        }
                    }
    
                    if (!$isError) {
                        if (count($eventClass)) {
                            $eventClassOld = $eventClass->replicate();
                            if ($eventClass->sess_sessr_id != 0) {
                                $eventClassRepeatOld = $eventClass->repeat->first();
                            }
    
                            $historyText      = "Reschedule from ".Carbon::parse($eventClassOld->sess_start_datetime)->format('D, d M Y h:i A')." to ".Carbon::parse($newDateTime)->format('D, d M Y h:i A')."|";
                            $alertHistoryText = '';
    
                            $eventClass->sess_date           = $startDate;
                            $eventClass->sess_time           = $eventClassOld->sess_time;
                            $eventClass->sess_start_datetime = $eventStartDatetime;
                            $eventClass->sess_end_datetime   = $eventEndDatetime;
                            $eventClass->sess_notes          = "";
                            $eventClass->sess_staff_id       = $eventClassOld->sess_staff_id;
    
                            if ($eventClass->sess_client_id) {
                                $clients = Clients::find($eventClass->sess_client_id);
                            }
    
                            $eventClass->sess_booking_status       = 'Pencilled-In';
                            $eventClass->sess_service_id           = $eventClassOld->sess_service_id;
                            $eventClass->sess_duration             = $eventClassOld->sess_duration;
                            $eventClass->sess_price                = $eventClassOld->sess_price;
                            $eventClass->sess_auto_expire          = 24;
                            $eventClass->sess_sessr_id          = 0;
                            $eventClass->sess_auto_expire_datetime = date("Y-m-d H:m:s", (strtotime($eventStartDatetime) - (60 * 60 * 24)));
    
                            $eventClass->update();
                            $oldDate = $eventClassOld->sess_date;
                            $response         = $this->manageAreasLinkage($eventClass, $eventAreas);
                            $allPrevAreasId   = $response['prevAreas'];
                            $historyText .= $response['history'];
                            /* end:Area manage section */
    
                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);
                            $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);
    
                            /* Memebership reset */
                            $this->membershipLimitReset($eventClass->sess_client_id);
                            $msg['status']  = 'added';
                            $msg['message'] = 'Service has been saved successfully.';
                        }
                    }
                } else {
                    $msg['status']      = 'error';
                    $msg['errorData'][] = array('error' => displayAlert("warning| This staff does not link to area"));
                    $isError            = true;
                }
            }
        }else{
            $msg['status']      = 'error';
            $msg['errorData'][] = array('eventNotExist' => displayAlert("warning| This event no longer exists."));
            $isError            = true;
        }
        return json_encode($msg);
    }

    public function freeAreas($datas){
        $locsAreas = $areasId = [];
        $service = Service::OfBusiness()->select('srvc_la_id')->find($datas['service_id']);
        if($service)
        	$areasId = explode(',', $service->srvc_la_id);

        if(isUserType(['Staff'])){
            $areas = LocationArea::whereHas('staffs', function($query){
                                        $query->where('id', Auth::user()->pk);
                                  })
                                  ->OfBusiness()
                                  ->distinct()
                                  ->select('la_id')
                                  ->pluck('la_id')
                                  ->toArray();

            $areasId = array_intersect($areas, $areasId);
        }
        if(count($areasId)){
            $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $datas['start_time'], 'startDate' => $datas['start_date'], 'duration' => $datas['duration']]);
            $eventStartDatetime = $startAndEndDatetime['startDatetime'];
            $eventEndDatetime = $startAndEndDatetime['endDatetime'];
            $startTime = $this->datetimeToTime($eventStartDatetime);
            $endTime = $this->datetimeToTime($eventEndDatetime);
            $workingAreasId = DB::table('hours')
                                    ->where('hr_entity_type', 'area')
                                    ->whereIn('hr_entity_id', $areasId)
                                    ->where('hr_day', Carbon::parse($startAndEndDatetime['startDate'])->format('l'))
                                    ->where('hr_start_time', '<=', $startTime)
                                    ->where('hr_end_time', '>=', $endTime)
                                    ->whereNull('deleted_at')
                                    ->distinct()
                                    ->select('hr_entity_id')
                                    ->pluck('hr_entity_id')
                                    ->toArray();
            if(count($workingAreasId)){
                $data = ['startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime];
                
                $busyAreasId = StaffEventBusy::whereIn('seb_area_id', $workingAreasId)
                                            ->clashingEvents($data)
                                            ->select('seb_area_id')
                                            ->pluck('seb_area_id')
                                            ->toArray();
                if(count($busyAreasId))
                    $workingAreasId = array_diff($workingAreasId, $busyAreasId);

                if(count($workingAreasId)){
                    $data['eventId'] = $datas['eventId'];
                    $busyAreasId = LocationArea::whereHas('events', function($query) use ($data){
                                                        $query->clashingEvents($data);
                                                    })
                                                    ->whereIn('la_id', $workingAreasId)
                                                    ->select('la_id')
                                                    ->pluck('la_id')
                                                    ->toArray();
                    if(count($busyAreasId))
                        $workingAreasId = array_diff($workingAreasId, $busyAreasId);  
                        
                    if(count($workingAreasId)){
                        $busyAreasId = LocationArea::whereHas('eventClassess', function($query) use ($data){
                                                        $query->clashingEvents($data);
                                                    })
                                                    ->whereIn('la_id', $workingAreasId)
                                                    ->select('la_id')
                                                    ->pluck('la_id')
                                                    ->toArray();    
                        if(count($busyAreasId))
                            $workingAreasId = array_diff($workingAreasId, $busyAreasId);  

                        if(count($workingAreasId)){
                            $areas = LocationArea::with('location')->find($workingAreasId);

                            if($areas->count()){
                                foreach($areas as $area)
                                    $locsAreas[] = $area->la_id;
                            }
                        }                                                           
                    }                                                           
                }
            }                        
        }
        return $locsAreas;
    }

    public function freeStaffs($datas){
        $staffsId = $stff = [];

        if(isUserType(['Staff'])){
        	$services = Staff::getServices(['staffId' => Auth::user()->pk, 'complOnly' => 1]);
        	if(count($services) && in_array($datas['service_id'], $services))
        		$staffsId = [Auth::user()->pk];
        }
        else if(eligibleAccounts(['Admin']) || Session::get('hostname') == 'result'){
        	$service = Service::OfBusiness()->select(['one_on_one_staffs', 'team_staffs'])->find($datas['service_id']);
        	if($service){
        		if($service->one_on_one_staffs)
        			$staffsId = explode(',', $service->one_on_one_staffs);
        		else if($service->team_staffs)
        			$staffsId = explode(',', $service->team_staffs);
        	}
        }

        if(count($staffsId)){
            /*if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-staff')){*/
    		$date = $datas['start_date'];
    		$startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $datas['start_time'], 'startDate' => $datas['start_date'], 'duration' => $datas['duration']]);

			$eventStartDatetime = $startAndEndDatetime['startDatetime'];
            $eventEndDatetime = $startAndEndDatetime['endDatetime'];
            $startTime = $this->datetimeToTime($eventStartDatetime);
            $endTime = $this->datetimeToTime($eventEndDatetime);

            $workingStaffsId = $this->getWorkingStaffs($staffsId, ['type'=>'service','day'=>Carbon::parse($startAndEndDatetime['startDate'])->format('l'),'date'=>$date, 'startTime'=>$startTime, 'endTime'=>$endTime]);

            if(count($workingStaffsId)){
                $data = ['startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime];
                
                $busyStaffsId = StaffEventBusy::whereIn('seb_staff_id', $workingStaffsId)
                                            ->clashingEvents($data)
                                            ->select('seb_staff_id')
                                            ->pluck('seb_staff_id')
                                            ->toArray();
                if(count($busyStaffsId))
                    $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);

                if(count($workingStaffsId)){
					$data['eventId'] = $datas['eventId'];
                    $busyStaffsId = StaffEventSingleService:://whereIn('sess_staff_id', $workingStaffsId)
                    										OfStaffs($workingStaffsId)
				                                            ->clashingEvents($data)
				                                            ->select('sess_staff_id')
				                                            ->pluck('sess_staff_id')
				                                            ->toArray();        
                    if(count($busyStaffsId))
                        $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  
                        
                    if(count($workingStaffsId)){
                        $busyStaffsId = StaffEventClass:://whereIn('sec_staff_id', $workingStaffsId)
                        								OfStaffs($workingStaffsId)
		                                                ->clashingEvents($data)
		                                                ->select('sec_staff_id')
		                                                ->pluck('sec_staff_id')
		                                                ->toArray();       
                        if(count($busyStaffsId))
                            $workingStaffsId = array_diff($workingStaffsId, $busyStaffsId);  

                        if(count($workingStaffsId)){
                            $staffs = Staff::select('id', 'first_name', 'last_name')->find($workingStaffsId);

                            if($staffs->count())
                                foreach($staffs as $staff)
                                	$stff[] = $staff->id;
                        }                                                           
                    }                                                           
                }
            }                        
	    }
        return $stff;
    }
    public function salesProcSettingsUpdate($id,$settData)
    {
        $client = Clients::findOrFailClient($id);
        $data = json_decode($settData,1);
        $client->is_bookbench_on = 0;
        if (in_array(4, $data['steps'])) {
            $client->is_bookbench_on = 1;
        }
        $client->save();
        $client->sale_process_setts = $settData;
        $salesAttendanceSteps    = salesAttendanceSteps();
        $newStatus               = '';
        $disabledAttendanceSteps = [];
        foreach ($salesAttendanceSteps as $slug) {
            if ($slug == 'teamed') {
                $teamAttendSteps  = teamAttendSteps();
                $indivAttendSteps = indivAttendSteps();
                $teamedEnabled    = $this->isStepEnabled($teamAttendSteps[0], $client->SaleProcessEnabledAttendSteps);
                $indivedEnabled   = $this->isStepEnabled($indivAttendSteps[0], $client->SaleProcessEnabledAttendSteps);

                if (!$teamedEnabled && !$indivedEnabled) {
                    //Neither team nor indiv is disabled
                    $thisDetails = calcSalesProcessRelatedStatus($slug);

                    if (!array_key_exists('dependantStep', $thisDetails) || $this->isDependantStepComp($thisDetails['dependantStep'], $id, $client->SaleProcessEnabledSteps)) {
                        //Its dependant step is completed
                        $newStatus = (array_key_exists('clientStatus', $thisDetails)) ? $thisDetails['clientStatus'] : $thisDetails['clientPrevStatus'];
                    } else {
                        break;
                    }

                } else if ($teamedEnabled && $indivedEnabled) {
                    //Team and indiv both are enabled
                    $lastIdx = count($data['session']) - 1;
                    $step    = $data['session'][$lastIdx];
                    if ($this->isStepComp($step, $id, $client->SaleProcessEnabledSteps)) {
                        //Step is  complete
                        $thisDetails = calcSalesProcessRelatedStatus($slug);
                        $newStatus   = $thisDetails['clientStatus'];
                    } else {
                        break;
                    }

                } else if ($teamedEnabled) {
                    $step = 0;
                    for ($i = count($data['session']) - 1; $i >= 0; $i--) {
                        if (in_array($data['session'][$i], $teamAttendSteps)) {
                            $step = $data['session'][$i]; //Team attendance Last step
                            break;
                        }
                    }
                    if ($this->isStepComp($step, $id, $client->SaleProcessEnabledSteps)) {
                        //Step is  complete
                        $thisDetails = calcSalesProcessRelatedStatus($slug);
                        $newStatus   = $thisDetails['clientStatus'];
                    } else {
                        break;
                    }

                } else if ($indivedEnabled) {
                    $step = 0;
                    for ($i = count($data['session']) - 1; $i >= 0; $i--) {
                        if (in_array($data['session'][$i], $indivAttendSteps)) {
                            $step = $data['session'][$i]; //Indiv attendance Last step
                            break;
                        }
                    }
                    if ($this->isStepComp($step, $id, $client->SaleProcessEnabledSteps)) {
                        //Step is  complete
                        $thisDetails = calcSalesProcessRelatedStatus($slug);
                        $newStatus   = $thisDetails['clientStatus'];
                    } else {
                        break;
                    }

                }
            } else if ($slug == 'indiv') {
                continue;
            } else {
                $thisDetails = calcSalesProcessRelatedStatus($slug);

                if (!in_array($thisDetails['saleProcessStepNumb'], $client->SaleProcessEnabledAttendSteps)) {
                    //Step is disabled
                    if (!array_key_exists('dependantStep', $thisDetails) || $this->isDependantStepComp($thisDetails['dependantStep'], $id, $client->SaleProcessEnabledSteps)) { //Its dependant step is completed
                        $newStatus = (array_key_exists('clientStatus', $thisDetails)) ? $thisDetails['clientStatus'] : $thisDetails['clientPrevStatus'];
                    } else {
                        break;
                    }

                } else {
                    //Step is enabled
                    if ($this->isStepComp($thisDetails['saleProcessStepNumb'], $id, $client->SaleProcessEnabledSteps)) //Step is  complete
                    {
                        $newStatus = $thisDetails['clientStatus'];
                    } else {
                        break;
                    }

                }
            }
        }

        if ($newStatus) {
            $clientOldStatus        = $client->account_status;
            // $newStatus              = preventActiveContraOverwrite($clientOldStatus, $newStatus);
            // $client->account_status = $newStatus;
            $client->save();
            // $this->processSalesProcessOnStatusChange($client, $clientOldStatus, $newStatus, 'Sales settings changed');
        }
        $client->update(['sale_process_setts'=> null]);
        return true;
    }

}
