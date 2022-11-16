<?php
namespace App\Http\Controllers\Setings\Staff;

use App\CalendarSetting;
use App\Clas;
use App\ClientMember;
use App\Clients;
use App\ClosedDate;
use App\Http\Controllers\Controller;
use App\Http\Traits\ClientNoteTrait;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\ClosedDateTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\SalesProcessProgressTrait;
use App\Http\Traits\SalesProcessTrait;
// use App\Http\Traits\StaffEventHistoryTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\StaffTrait;
use App\Invoice;
use App\InvoiceItems;
use App\LocationArea;
use App\Makeup;
use App\Staff;
use App\StaffEventClass;
use App\StaffEventClassRepeat;
use App\StaffEventHistory;
use App\StaffEventRepeat;
use App\User;
use App\MemberShip;
use App\SalesProcessProgress;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;
use \stdClass;

class StaffEventClassController extends Controller
{
    private $removedRecurringClientsId = [];
    private $makedUpRecurringClientsId = [];

    use /*StaffEventHistoryTrait,*/ StaffEventsTrait, /*StaffEventClassTrait,*/StaffTrait, ClientNoteTrait, ClosedDateTrait, SalesProcessTrait, ClientTrait, HelperTrait, SalesProcessProgressTrait;

    /**
     * Index display all class event
     *
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

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-event-class')) {
            $this->haltScript($request);
        }

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
                $this->neverEndClassRepeats($request);
            }

            $staffEvents    = collect();
            $eventClassData = new stdClass();

            if ($request->areaId == 'all') {
                if ($request->staffId == 'all' || $request->staffId == 'all-ros') {
                    if ($request->has('eventStatus') && $request->eventStatus == 'active') {
                        $staffEvents = StaffEventClass::with('clasWithTrashed', 'clients', 'staffWithTrashed' /*, 'histories', 'user', 'locationAndAreasWithTrashed', 'repeat'*/)->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->active()->get();
                    } else {
                        $staffEvents = StaffEventClass::with('clasWithTrashed', 'clients', 'staffWithTrashed' /*, 'histories', 'user', 'locationAndAreasWithTrashed', 'repeat'*/)->OfBusiness()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }

                } else {
                    if ($request->has('eventStatus') && $request->eventStatus == 'active') {
                        $staffEvents = StaffEventClass::with('clasWithTrashed', 'clients', 'staffWithTrashed' /*, 'histories', 'user', 'locationAndAreasWithTrashed', 'repeat'*/)->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->active()->get();
                    } else {
                        $staffEvents = StaffEventClass::with('clasWithTrashed', 'clients', 'staffWithTrashed' /*, 'histories', 'user', 'locationAndAreasWithTrashed', 'repeat'*/)->OfStaff($request->staffId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }

                }
            } else {
                $eventClassData->areaId = $request->areaId;

                if ($request->staffId == 'all' || $request->staffId == 'all-ros') {
                    if ($request->has('eventStatus') && $request->eventStatus == 'active') {
                        $staffEvents = StaffEventClass::with('clasWithTrashed', 'clients', 'staffWithTrashed' /*, 'histories', 'user', 'locationAndAreasWithTrashed', 'repeat'*/)->OfArea($request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->active()->get();
                    } else {
                        $staffEvents = StaffEventClass::with('clasWithTrashed', 'clients', 'staffWithTrashed' /*, 'histories', 'user', 'locationAndAreasWithTrashed', 'repeat'*/)->OfArea($request->areaId)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }

                } else {
                    $eventClassData->staffId = $request->staffId;
                    if ($request->has('eventStatus') && $request->eventStatus == 'active') {
                        $staffEvents = StaffEventClass::with('clasWithTrashed', 'clients', 'staffWithTrashed' /*, 'histories', 'user', 'locationAndAreasWithTrashed', 'repeat'*/)->OfAreaAndStaff($eventClassData)->active()->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    } else {
                        $staffEvents = StaffEventClass::with('clasWithTrashed', 'clients', 'staffWithTrashed' /*, 'histories', 'user', 'locationAndAreasWithTrashed', 'repeat'*/)->OfAreaAndStaff($eventClassData)->DatedBetween($request->getEventsFrom, $request->getEventsUpto)->get();
                    }

                }
            }

            if (count($staffEvents)) {
                $index = 0;
                foreach ($staffEvents as $staffEvent) {
                    $evnts[$index]['id']             = $staffEvent->sec_id;
                    $evnts[$index]['date']           = $staffEvent->sec_date;
                    $evnts[$index]['startDatetime']  = $staffEvent->sec_start_datetime;
                    $evnts[$index]['endDatetime']    = $staffEvent->sec_end_datetime;
                    $evnts[$index]['title']          = $staffEvent->clasWithTrashed->cl_name;
                    $evnts[$index]['price']          = $staffEvent->sec_price;
                    $evnts[$index]['color']          = $staffEvent->clasWithTrashed->cl_colour;
                    $evnts[$index]['isStaffDeleted'] = $staffEvent->staffWithTrashed->trashed();
                    $evnts[$index]['capacity']       = $staffEvent->sec_capacity;
                    if ($staffEvent->sec_secr_id != 0) {
                        $evnts[$index]['isRepeating'] = 1;
                    } else {
                        $evnts[$index]['isRepeating'] = 0;
                    }

                    $evnts[$index]['notes'] = $staffEvent->sec_notes;
                    $i                      = 0;
                    foreach ($staffEvent->clients as $client) {
                        $evnts[$index]['clients'][$i]['name'] = $client->firstname . ' ' . $client->lastname;
                        $i++;
                    }
                    $evnts[$index]['clientsCount'] = $staffEvent->clients->count();

                    $index++;
                }
            }
        }

        return json_encode($evnts);
    }

    /**
     * Get data for class event modal
     *
     * @param  Event id, Form data
     * @return response
     */
    public function show($eventId, Request $request)
    {
        if (Session::get('hostname') == 'crm') {
            if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'view-staff-event-class')) {
                $this->haltScript($request);
            }

        }

        $event = [];
        if (isUserType(['Staff'])) {
            $staffEvent = StaffEventClass::with('clasWithTrashed', 'clientsWithTrashedWithPivotTrashed', 'historiesWithTrashed', 'locationAndAreasWithTrashed', 'staffWithTrashed', 'repeat', 'resourcesWithTrashed')->withTrashed()->OfStaff(Auth::user()->account_id)->find($eventId);
        }
        //clientsWithTrashed , 'userWithTrashed', 'resourcesWithTrashed.resourceWithTrashed'
        else {
            $staffEvent = StaffEventClass::with('clasWithTrashed', 'clientsWithTrashedWithPivotTrashed', 'historiesWithTrashed', 'locationAndAreasWithTrashed', 'staffWithTrashed', 'repeat', 'resourcesWithTrashed')->withTrashed()->OfBusiness()->find($eventId);
        }
        //clientsWithTrashed, 'userWithTrashed', 'resourcesWithTrashed.resourceWithTrashed'

        if ($staffEvent) {
            $event['id']            = $staffEvent->sec_id;
            $event['date']          = $staffEvent->sec_date;
            $event['startTime']     = $staffEvent->sec_time;
            $event['notes']         = $staffEvent->sec_notes;
            $event['startDatetime'] = $staffEvent->sec_start_datetime;
            $event['endDatetime']   = $staffEvent->sec_end_datetime;
            $event['userId']        = $staffEvent->sec_user_id;

            if ($staffEvent->sec_secr_id != 0) {
                $event['isRepeating'] = 1;
            } else {
                $event['isRepeating'] = 0;
            }

            $i = 0;
            foreach ($staffEvent->locationAndAreasWithTrashed as $area) {
                $event['areas'][$i]['id']            = $area->la_id;
                $event['areas'][$i]['locAreaName']   = $area->locationWithTrashed->location_training_area . ' - ' . $area->la_name;
                $event['areas'][$i]['isAreaDeleted'] = $area->trashed();
                $i++;
            }

            $event['staffName']      = $staffEvent->staffWithTrashed->fullName;
            $event['staffId']        = $staffEvent->sec_staff_id;
            $event['isStaffDeleted'] = $staffEvent->staffWithTrashed->trashed();

            $event['classId']        = $staffEvent->sec_class_id;
            $event['className']      = $staffEvent->clasWithTrashed->cl_name;
            $event['color']          = $staffEvent->clasWithTrashed->cl_colour;
            $event['isClassDeleted'] = $staffEvent->clasWithTrashed->trashed();
            $event['duration']       = $staffEvent->sec_duration;
            $event['capacity']       = $staffEvent->sec_capacity;
            $event['price']          = $staffEvent->sec_price;

            if ($staffEvent->repeat()->count()) {
                $repeat                            = $staffEvent->repeat()->first();
                $event['eventRepeat']              = $repeat->secr_repeat;
                $event['eventRepeatInterval']      = $repeat->secr_repeat_interval;
                $event['eventRepeatEnd']           = $repeat->secr_repeat_end;
                $event['eventRepeatEndAfterOccur'] = $repeat->secr_repeat_end_after_occur;
                $event['eventRepeatEndOnDate']     = $repeat->secr_repeat_end_on_date;
                $event['eventRepeatWeekDays']      = $repeat->secr_repeat_week_days;
            }

            $event['linkedClientsCount'] = 0;
            $i                           = 0;
            foreach ($staffEvent->clients as $client) {
                $event['clients'][$i]['id']            = $client->id;
                $event['clients'][$i]['name']          = $client->fullName;
                $event['clients'][$i]['phone']         = $client->phonenumber;
                $event['clients'][$i]['email']         = $client->email;
                $event['clients'][$i]['note']          = $client->pivot->secc_notes;
                $event['clients'][$i]['isReducedRate'] = $client->pivot->secc_reduce_rate_session;
                $event['clients'][$i]['ifRecur']       = $client->pivot->secc_if_recur;
                $event['clients'][$i]['attendance']    = $client->pivot->secc_client_attendance;
                $event['clients'][$i]['status']        = $client->pivot->secc_client_status;
                $event['clients'][$i]['photo']         = $client->profilepic;
                $event['clients'][$i]['accStatus']     = $client->account_status;
                $event['clients'][$i]['isLdc']         = $client->pivot->is_ldc;
                /*$event['clients'][$i]['isMakeUp'] = $client->pivot->secc_epic_credit;*/
                $event['clients'][$i]['isInvoice'] = $client->pivot->secc_with_invoice;
                /*$event['clients'][$i]['isInvoice'] = $this->isEventHasInvoice($staffEvent->sec_id, $client->id, 'class' );*/
                $event['clients'][$i]['isEpicCredit'] = $client->pivot->secc_epic_credit;
                if ($client->pivot->secc_epic_credit) {
                    $event['clients'][$i]['epicCreditBal'] = $this->setEpicBalance($client->id);
                }

                $event['clients'][$i]['isClientDeleted']  = $client->trashed();
                $event['clients'][$i]['consultationDate'] = $client->consultation_date;

                if (!$client->trashed()) {
                    $event['linkedClientsCount']++;
                }

                if ($client->pivot->secc_reduce_rate == null) {
                    $event['clients'][$i]['reducedRate'] = '';
                } else {
                    $event['clients'][$i]['reducedRate'] = $client->pivot->secc_reduce_rate;
                }

                $event['clients'][$i]['isSalesProcEvent'] = $this->isBookingSalesProcBooking($staffEvent->sec_id, $staffEvent->sec_date, $client);
                $i++;
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
        }
        return json_encode($event);
    }

    /**
     * Create Class
     * @param
     * @return
     */
    public function store(Request $request)
    {
       
        $isError = false;
        $msg     = [];

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'create-staff-event-class')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff']) && Auth::user()->account_id != $request->staff) {
                $isError = true;
            } else if (!isUserType(['Staff']) && !Staff::ifstaffExist($request->staff)) {
                $isError = true;
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
                    $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->classTime, 'startDate' => $startDate, 'duration' => $request->classDur]);
                    if (ClosedDate::ifOverlapping($startDate, $startAndEndDatetime['endDate'])) {
                        $msg['status']      = 'error';
                        $msg['errorData'][] = array('businessClosed' => 'Business is closed on specified date.');
                        $isError            = true;
                    }

                    if (!$isError) {
                        $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                        $eventEndDatetime   = $startAndEndDatetime['endDatetime'];

                        $busyAreaIds        = $this->areAreasBusy(['areaId' => $request->modalLocArea, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime]);
                        if (count($busyAreaIds)) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('areaUnavail' => $busyAreaIds);
                            $isError            = true;
                        } else if ($this->isStaffBusy(['staffId' => $request->staff, 'day' => $request->classDay, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime])) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                            $isError            = true;
                        } else if($this->isClassBusy(['classId' => $request->staffClass, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime])) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('classUnavail' => 'Class not available at specified time.');
                            $isError            = true;
                        }

                        if (!$isError) {
                            $eventClass                     = new StaffEventClass;
                            $eventClass->sec_business_id    = Session::get('businessId');
                            $eventClass->sec_user_id        = Auth::user()->id;
                            $eventClass->sec_staff_id       = $request->staff;
                            $eventClass->sec_time           = $request->classTime;
                            $eventClass->sec_class_id       = $request->staffClass;
                            $eventClass->sec_duration       = $request->classDur;
                            $eventClass->sec_capacity       = $request->classCap;
                            $eventClass->sec_price          = $request->classPrice;
                            $eventClass->sec_start_datetime = $eventStartDatetime;
                            $eventClass->sec_end_datetime   = $eventEndDatetime;
                            $eventClass->sec_date           = $startDate;

                            Auth::user()->eventClasses()->save($eventClass);

                            $this->newHistory(['eventType' => 'Class', 'event' => $eventClass]);

                            // Add Log
                            $logText = 'Class( Id:'.$eventClass->sec_id.', Date:'.$startDate.') added Manually';
                            setInfoLog($logText, $eventClass->sec_id);

                            $this->linkEventclassResource($request, $eventClass->sec_id, 'add');

                            $attachedArea = [];
                            foreach ($request->modalLocArea as $areaId) {
                                $attachedArea[$areaId] = ['seca_business_id' => Session::get('businessId')];
                            }

                            if (count($attachedArea)) {
                                $eventClass->areas()->attach($attachedArea);
                            }

                            if ($request->ifMarkFav) {
                                $this->changeFavArea($eventClass->sec_staff_id, $request->modalLocArea, $request->markFavTill);
                            }

                            $msg['status']  = 'added';
                            $msg['eventId'] = $eventClass->sec_id;
                            $msg['message'] = displayAlert('success|Class has been saved successfully.');
                        }
                    }
                } else {
                    $msg['status']      = 'error';
                    $msg['errorData'][] = array('staffNotLinkedToArea' => '');
                }
            }
        }
        // dd($msg);
        return json_encode($msg);
    }

    /**
     * Check client future recure event
     * @param $client, $eventDate
     * @return boolean
     */
    public function isClientFutureRecureEventExist($client, $eventDate,$type = '')
    {
        $futureEvents  = collect();
        $eventDate     = new Carbon($eventDate);
        $weekStartDate = $eventDate->startOfWeek()->format('Y-m-d');

        /* start: Fetching fututre events */
        if($type == 'classExtra'){
            $futureClasses = $client->eventClasses()->whereNull('staff_event_classes.deleted_at')->whereNull('staff_event_class_clients.deleted_at')->where('staff_event_class_clients.secc_class_extra',1)->where('sec_date', '>=', $weekStartDate)->get();
        }else{
            $futureClasses = $client->eventClasses()->whereNull('staff_event_classes.deleted_at')->whereNull('staff_event_class_clients.deleted_at')->where('staff_event_class_clients.secc_class_extra',0)->where('sec_date', '>=', $weekStartDate)->get();
        }
        if ($futureClasses->count()) {
            $futureEvents = $futureClasses;
            $futureEvents = $futureEvents->sort(function ($firstEvent, $secondEvent) {
              
                if ($firstEvent->eventDate === $secondEvent->eventDate) {
                    if ($firstEvent->eventTime === $secondEvent->eventTime) {
                        return 0;
                    }

                    return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
                }
                return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
            });
        } else if ($futureClasses->count()) {
            $futureEvents = $futureClasses;
        }

        /* end: Fetching fututre events */
        if ($futureEvents->count()) {
            foreach ($futureEvents as $events) {
                /*Check membership satisfied or not*/
                $membership = $this->satisfyMembershipRestrictions($client->id, ['event_type' => 'class', 'event_id' => $events->sec_class_id, 'event_date' => $events->sec_date]);
                if (!$membership['satisfy']) {
                    return true;
                }
            }
        }

        return false;
    }

     /**
     * Check if classes are able to recure in future
     * @param $newEventRecurence StaffEventClassRepeat Instance
     * @return boolean
     */
    public function isClassRecurePossible($recurData) {
        $response = [];
        $dates = array();
        $businessId = Session::get('businessId');
        $currentDate = Carbon::today();
        $insertRepeatUpto = $currentDate->addYears(1)->toDateString();

        if (count($recurData)) {
            if ($recurData->secr_repeat_week_days != '') {
                $eventRepeatWeekdays = json_decode($recurData->secr_repeat_week_days);
            } else {
                $eventRepeatWeekdays = [];
            }

            $event = StaffEventClass::withTrashed()->where('sec_business_id', $businessId)->where('sec_secr_id', $recurData->secr_id)->orderBy('sec_date','desc')->first();

            $eventStartDate = StaffEventClass::withTrashed()->where('sec_business_id', $businessId)->where('sec_secr_id', $recurData->secr_id)->orderBy('sec_date', 'asc')->pluck('sec_date')->first();

            if ($eventStartDate) {
                if ($recurData->secr_repeat_end == 'After') {
                    $dates = $this->calcRepeatsDateAfterCase(['eventDate' => $eventStartDate, 'eventRepeatEndAfterOccur' => $recurData->secr_repeat_end_after_occur, 'eventRepeat' => $recurData->secr_repeat, 'eventRepeatInterval' => $recurData->secr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                } elseif ($recurData->secr_repeat_end == 'On') {
                    $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $eventStartDate, 'eventRepeatEndOnDate' => $recurData->secr_repeat_end_on_date, 'eventRepeat' => $recurData->secr_repeat, 'eventRepeatInterval' => $recurData->secr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                } elseif ($recurData->secr_repeat_end == 'Never') {
                    $dates = $this->calcRepeatsDateOnOrNeverCase(['eventDate' => $eventStartDate, 'eventRepeatEndOnDate' => $insertRepeatUpto, 'eventRepeat' => $recurData->secr_repeat, 'eventRepeatInterval' => $recurData->secr_repeat_interval, 'eventRepeatWeekdays' => $eventRepeatWeekdays], true);
                }
            }

            /* Get only future dates */
            $dates = array_filter($dates, function ($value) use ($event){
                return $value > /*date('Y-m-d')*/ $event->sec_date;
            });
            $dates = array_values($dates);

            if (!empty($dates) && !empty($event)) {
                foreach ($dates as $date) {
                    $classDay = date('l', strtotime($date));
                    $eventStartDatetime = new Carbon($date.' '.$recurData->secr_start_time);
                    $eventEndDatetime   = new carbon($date.' '.$recurData->secr_end_time);

                    # Check if areas are busy at specified hours.
                    if ($recurData->secr_area_id) 
                        $busyAreaIds = $this->areAreasBusy(['areaId' => [$recurData->secr_area_id], 'startDatetime' => $eventStartDatetime->toDateTimeString(), 'endDatetime' => $eventEndDatetime->toDateTimeString()], 'class');
                        
                    # Check if staff is busy at specified hours.
                    if ($recurData->secr_staff_id) 
                        $staffBusy = $this->isStaffBusy(['staffId' => $recurData->secr_staff_id, 'day' => $classDay, 'startDatetime' =>  $eventStartDatetime->toDateTimeString(), 'endDatetime' => $eventEndDatetime->toDateTimeString()], 'class');

                    # Check if class is busy at specified hours.
                    if($recurData->secr_class_id) 
                        $classBusy = $this->isClassBusy(['classId' => $recurData->secr_class_id, 'startDatetime' => $eventStartDatetime->toDateTimeString(), 'endDatetime' => $eventEndDatetime->toDateTimeString()]);
                   

                    if($staffBusy || count($busyAreaIds) > 0 || $classBusy) {
                        $response['status'] = false;
                        if ($staffBusy) 
                            $response['message'] = 'Staff is busy at specified hours in future recurring.';
                        else if(count($busyAreaIds) > 0)
                            $response['message'] = 'Areas are busy at specified hours in future recurring.'; 
                        else if($classBusy) 
                            $response['message'] = 'Class is busy at specified hours in future recurring.';

                        return $response;
                    }
                }
            }
        }

        return array('status' => true, 'message' => 'Able to booke recurring class');
    }

    /**
     * Add Client in Existing Class
     * @param StaffEventClassRepeat Object
     */
    public function adjustMembershipOfRecurredClient($repeatId, $eventDate, $recureClients) {
        if($repeatId && $recureClients) {
            # Get recurred clients
            $clients = json_decode($recureClients, true);
            $clientIds = array_keys($clients);

            # Get recurred class events
            $associatedEvents = StaffEventClass::where('sec_secr_id', $repeatId)->whereDate('sec_date', '>=', $eventDate)->select('sec_id')->pluck('sec_id');

            if($associatedEvents && $clientIds) {
                foreach ($clientIds as $clientId) {
                    // DB::table('staff_event_class_clients')->where('secc_client_id', $clientId)->whereIn('secc_sec_id', $associatedEvents->toArray())->update(['deleted_at' => createTimestamp() , 'secc_event_log' => 'Removed while changing class recurrence', 'secc_action_performed_by' => getLoggedUserName()]);
                    foreach ($associatedEvents as $eventId) {
                        DB::table('staff_event_class_clients')->where('secc_client_id', $clientId)->where('secc_sec_id', $eventId)->whereNull('deleted_at')->update(['deleted_at' => createTimestamp() , 'secc_event_log' => 'Removed while changing class recurrence', 'secc_action_performed_by' => getLoggedUserName()]);
                    }
                    
                    $this->membershipLimitReset($clientId);
                }
            }
        }
    }

    /**
     * Add Client in Existing Class
     * @param Request Object
     * @return JSON Object
     */
    public function update(Request $request)
    { 
        // dd($request->all());
        $isError          = $eventClass          = false;
        $msg              = array();
        $eventRecurData   = array();
        $historyText      = '';
        $alertHistoryText = '';
        $isEpicInvoice    = false;
        $salesProgress = 0;
        $event = [];
        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-class')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $eventClass = StaffEventClass::OfStaff(Auth::user()->account_id)->find($request->eventId);
            } else {
                $eventClass = StaffEventClass::OfBusiness()->find($request->eventId);
            }

            // dd($eventClass->toArray);
            /* procced data step by step */
            if (count($eventClass)) {
                /* check event restrictions and through error response */
                if ($request->step == 'classDetails') {
                    if (!$request->staff || !$request->modalLocArea) {
                        $isError       = true;
                        $msg['status'] = 'error';
                        if (!$request->staff) {
                            $msg['errorData'][] = array('staffUnavail' => 'Staffs are not available for specified hours.');
                        } else {
                            $msg['errorData'][] = array('areaUnavail' => 'Areas are not available for specified hours.');
                        }

                        return json_encode($msg);
                    }

                    if (isUserType(['Staff']) && Auth::user()->account_id != $request->staff) {
                        $isError            = true;
                        $msg['status']      = 'error';
                        $msg['errorData'][] = array('staffUnavail' => 'Staffs are not available for specified hours.');
                        return json_encode($msg);
                    } else if (!isUserType(['Staff']) && !Staff::ifstaffExist($request->staff)) {
                        $isError            = true;
                        $msg['status']      = 'error';
                        $msg['errorData'][] = array('staffUnavail' => 'Staffs are not available for specified hours.');

                        return json_encode($msg);
                    }

                    $countOfAreasLinkedToStaff = $this->getCountOfAreasLinkedToStaff(['areaId' => $request->modalLocArea, 'staffId' => $request->staff]);

                    if ($countOfAreasLinkedToStaff == count($request->modalLocArea)) {
                        $isAreaLinkedToStaff = true;
                    } else {
                        $isAreaLinkedToStaff = false;
                    }

                    if (!$isAreaLinkedToStaff) {
                        $isError            = true;
                        $msg['status']      = 'error';
                        $msg['errorData'][] = array('staffNotLinkedToArea' => 'Staff not linked to the location area specified.');

                        return json_encode($msg);
                    }

                    if ($isAreaLinkedToStaff || $request->field == 'duration' || $request->field == 'date' || $request->field == 'time') {
                        $startDate           = $this->calcEventDate($request, $request->classDate);
                        $startAndEndDatetime = $this->calcStartAndEndDatetime(['startTime' => $request->classTime, 'startDate' => $startDate, 'duration' => $request->classDur]);
                        if (ClosedDate::ifOverlapping($startDate, $startAndEndDatetime['endDate'])) {
                            $msg['status']      = 'error';
                            $isError            = true;
                            $msg['errorData'][] = array('businessClosed' => 'Business is closed on specified date.');

                            return json_encode($msg);
                        } else {
                            $eventStartDatetime = $startAndEndDatetime['startDatetime'];
                            $eventEndDatetime   = $startAndEndDatetime['endDatetime'];
                        }
                    } else if ($request->field == 'staff' || $request->field == 'area' || $request->field == 'class') {
                        $eventStartDatetime = $eventClass->sec_start_datetime;
                        $eventEndDatetime   = $eventClass->sec_end_datetime;
                    }

                    # Check if area is busy at specified hours.
                    if (!$isError && ($request->field == 'duration' || $request->field == 'staff' || $request->field == 'area' || $request->field == 'class' || $request->field == 'date' || $request->field == 'time') && $request->modalLocArea) {
                        $busyAreaIds = $this->areAreasBusy(['eventId' => $request->eventId, 'areaId' => $request->modalLocArea, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'class');
                        if (count($busyAreaIds)) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('areaUnavail' => $busyAreaIds);
                            $isError            = true;
                            return json_encode($msg);
                        }
                    }

                    # Check if staff is busy at specified hours.
                    if (!$isError && ($request->field == 'duration' || $request->field == 'staff' || $request->field == 'class' || $request->field == 'date' || $request->field == 'time') && $request->staff) {
                        $staffBusy = $this->isStaffBusy(['eventId' => $request->eventId, 'staffId' => $request->staff, 'day' => $request->classDay, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime], 'class');
                        if ($staffBusy) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('workingHourUnavail' => staffBusyMsg());
                            $isError            = true;
                            return json_encode($msg);
                        }
                    }

                    # Check if class is busy at specified hours.
                    if(!$isError && ($request->field == 'duration' || $request->field == 'staff' || $request->field == 'class' || $request->field == 'date' || $request->field == 'time') && $request->staff) {
                        $classBusy = $this->isClassBusy(['eventId' => $request->eventId, 'classId' => $request->staffClass, 'startDatetime' => $eventStartDatetime, 'endDatetime' => $eventEndDatetime]);
                       
                        if($classBusy) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('classUnavail' => 'Class not available at specified hours.');
                            $isError            = true;
                            return json_encode($msg);
                        }
                    }

                } elseif ($request->step == 'classClients') {
                    $clientIdsToCheck         = [];
                    $clientMembIds            = $clientsUnsatisfyMembershipRestrictions            = $clientsUnsatisfyFutureRecure            = [];
                    $unsatisyReas             = '';
                    $satisfyMembershipClients = 0;
                   
                    $lowEpicBalanceClients    = [];
                    foreach ($request->classClients as $key => $value) {
                        $clientIdsToCheck[] = $key;
                        $client             = Clients::findOrFailClient($key);
                        $salesProgressData=  SalesProcessProgress::where('spp_client_id', $key)->orderBy('spp_id','desc')->where('spp_comp_manual',0)->first();
                        $salesProcessStepNumb =$salesProgressData['spp_step_numb'] + 1;
                        
                        $calendarData =  CalendarSetting::where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
                        if($client->sale_process_setts == null || $client->sale_process_setts == ''){
                            $client->sale_process_setts = $calendarData->sales_process_settings;
                            $this->salesProcSettingsUpdate($key,$calendarData->sales_process_settings);
                            $client->refresh();
                            $client->sale_process_setts = $calendarData->sales_process_settings;

                           
                        }
                      
                        $clientStatus = json_decode($client->sale_process_setts,1);
                        if ($client && !in_array($client->account_status, ['Active', 'Contra']) && $client->epic_credit_balance < $eventClass->sec_price) {
                            if(!in_array($client->account_status, ['Pre-Training', 'Pre-Benchmarking','Pre-Consultation'])){
                                $lowEpicBalanceClients[$key] = $client->epic_credit_balance;
                            }else if(in_array($client->account_status, ['Pre-Training', 'Pre-Benchmarking','Pre-Consultation']) && in_array('11',$clientStatus['steps'])){
                                $teamCount = 0;
                                $team = teamBookingSteps();
                                $teamed = teamAttendSteps();
                               
                                $salesProgress = 1;
                                $idClient = $key;
                                foreach($client->SalesSessionOrder as $order){
                                    $value = explode('-', $order); 
                                    if($value[0] == 'team'){
                                     
                                        $stepNumb = $team[$teamCount];
                                        $attendNumb = $teamed[$teamCount];
                                        $teamCount++;
                                       

                                        if(!SalesProcessProgress::where('spp_client_id', $key)->orderBy('spp_id','desc')->where('spp_step_numb',$attendNumb)->exists()){
                                            if(!SalesProcessProgress::where('spp_client_id', $key)->orderBy('spp_id','desc')->where('spp_step_numb',$stepNumb)->exists()){
                                                $salesProcessStepNumb = $stepNumb;
                                            break;
                                            }
                                        }
                                     
                                       
                                    }

                                    
                                 }
                                
                                
                            } else{
                                if($value['forceAdd'] != 'yes'){
                                    $lowEpicBalanceClients[$key] = $client->epic_credit_balance;
                                }
                            }
                        } else if ($value['ifRecur']) {
                            $clientMember = ClientMember::where('cm_client_id', $client->id)->where('cm_status', 'Active')->orderBy('id', 'desc')->whereNull('deleted_at')->first();
                            $membership = MemberShip::select('id', 'me_membership_label')
                            ->where('id', $clientMember->cm_membership_id)
                            ->with('classmember')
                            ->first();
                            if ($membership && $membership->classmember->count()) {
                                foreach ($membership->classmember as $value) {
                                    $classes[$value->cl_id] = $value->cl_name;
                                }
            
                            }
                            $session_limits = json_decode($clientMember->cm_session_limit, 1);
                            
                            if(array_key_exists($eventClass->sec_class_id,$classes)){
                                $membership = $this->satisfyMembershipRestrictions($key, ['event_type' => 'class', 'event_id' => $eventClass->sec_class_id, 'event_date' => $eventClass->sec_date]);
                                if($membership['status']){
                                $isClientFutureRecureEventExist = $this->isClientFutureRecureEventExist($client, $eventClass->sec_date);
                                }else{
                                    $isClientFutureRecureEventExist = $this->isClientFutureRecureEventExist($client, $eventClass->sec_date,'classExtra');
                                }
                                if ($isClientFutureRecureEventExist) {
                                    $clientsUnsatisfyFutureRecure[] = $client->id;
                                }
                                
                            }else{
                               $classCatId = $eventClass->clas->cat->clcat_id;
                               if(array_key_exists($classCatId,$session_limits)){
                                 $isClientFutureRecureEventExist = $this->isClientFutureRecureEventExist($client, $eventClass->sec_date,'classExtra');
                                if ($isClientFutureRecureEventExist) {
                                    $clientsUnsatisfyFutureRecure[] = $client->id;
                                }
                               }
                            }

                        } else if ($value['forceAdd'] != 'yes' && !$value['isReducedRate'] && !$value['isCreatingMakeUpSession'] && !$value['ifMakeUp']) {
                            $isSalesProcEvent = false;
                            if (!$value['ifRecur']) {
                                //Client is not recurring

                                if (isClientInSalesProcess($client->consultation_date, $client->consul_exp_date) && $client->TeamEnabledCount) {
                                    //Client is still in sales process
                                    $eventDate        = new Carbon($eventClass->sec_date);
                                    $consultationDate = new Carbon($client->consultation_date);
                                    if ($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))) {
                                        //Event booking date is not less than Consultation date and not greater than Consultation end date(Consultation date + 15 days)
                                        $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('book_team');
                                        if ($this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)) {
                                            //Sales process has been book benchmark already
                                            $totalBookings = StaffEventClass::teamBookings($client->id, $client->consultation_date);
                                            $teamBookings  = count($totalBookings);
                                            if ($teamBookings < $client->TeamEnabledCount) //Team booking count is less than 3
                                            {
                                                $isSalesProcEvent = true;
                                            }

                                        }
                                    }
                                }
                            }
                            if (!$isSalesProcEvent) {
                                // if(!$isSalesProcEvent || $value['ifRecur']){
                                $membership = $this->satisfyMembershipRestrictions($key, ['event_type' => 'class', 'event_id' => $eventClass->sec_class_id, 'event_date' => $eventClass->sec_date]);
                                $clientMembIds[$key] = $membership['clientMembId'];
                                if ($membership['satisfy']) {
                                    $satisfyMembershipClients = $key;
                                }else if(isset($value['isLdc']) && $value['isLdc'] == 1){
                                    $satisfyMembershipClients = $key;
                                }else {
                                    $clientsUnsatisfyMembershipRestrictions[] = $key;
                                    $unsatisyReas                             = $membership['failReas'];
                                }
                            }
                        }

                    }

                    // dd($clientsUnsatisfyMembershipRestrictions);
                    if($salesProgress == 1){
                        $isError            = false;
                        
                    }else if (count($lowEpicBalanceClients)) {
                        $msg['status']       = 'error';
                        $msg['errorData'][]  = array('lowBalance' => 'You don\'t have sufficient credit balance available in your account.');
                        $msg['unsatisyReas'] = 'low_epic_balance';
                        $isError             = true;
                        return json_encode($msg);
                    } else if (count($clientsUnsatisfyFutureRecure)) {
                        $msg['status']       = 'error';
                        $msg['errorData'][]  = array('membershipUnsatisfyRec' => 'Client already booked recurring classes in future weeks.');
                        $msg['unsatisyReas'] = 'not_reccure';
                        $isError             = true;
                        return json_encode($msg);
                    } else if (count($clientsUnsatisfyMembershipRestrictions)) {
                        $msg['status']       = 'error';
                        $msg['errorData'][]  = array('membershipUnsatisfy' => $clientsUnsatisfyMembershipRestrictions);
                        $msg['unsatisyReas'] = 'limit_exceeded';
                        $isError             = true;
                        return json_encode($msg);
                    } else {
                        $busyClientIds = $this->isClientBusy(['eventId' => $request->eventId, 'clientId' => $clientIdsToCheck, 'startDatetime' => $eventClass->sec_start_datetime, 'endDatetime' => $eventClass->sec_end_datetime], 'class');
                        if (count($busyClientIds)) {
                            $msg['status']      = 'error';
                            $msg['errorData'][] = array('clientUnavail' => $busyClientIds);
                            $isError            = true;
                            return json_encode($msg);
                        }
                    }
                }

                if (!$isError) {
                    if ($request->step == 'classDetails') {
                        // dd($request->all());
                        $eventRecurData        = array();
                        $targetEventData       = array();
                        $eventOldStartDatetime = $eventClass->sec_start_datetime;
                        $eventOldDate          = $eventClass->sec_date;

                        if ($request->field == 'class') {
                            $eventClass->sec_class_id        = toInt($request->staffClass);
                            $eventRecurData['secr_class_id'] = $eventClass->sec_class_id;
                            $targetEventData['sec_class_id'] = $eventClass->sec_class_id;
                        }

                        if ($request->field == 'class' || $request->field == 'duration') {
                            if ($request->classDur) {
                                $eventClass->sec_duration        = toInt($request->classDur);
                                $eventRecurData['secr_duration'] = $eventClass->sec_duration;
                                $targetEventData['sec_duration'] = $eventClass->sec_duration;
                            }
                        }

                        if ($request->field == 'class' || $request->field == 'capacity') {
                            if ($request->classCap) {
                                $eventClass->sec_capacity        = toInt($request->classCap);
                                $eventRecurData['secr_capacity'] = $eventClass->sec_capacity;
                                $targetEventData['sec_capacity'] = $eventClass->sec_capacity;

                                $allPrevClients = $eventClass->clients;
                                if (count($allPrevClients)) {
                                    $confirmed_clientsCount    = $allPrevClients->where('pivot.secc_client_status', 'Confirm')->count();
                                    $clients_to_auto_confirmId = $allPrevClients->where('pivot.secc_client_status', 'Waiting')->pluck('id')->toArray();

                                    /*Auto Confirm client when capacity change */
                                    $msg['newConfirmedClients'] = $this->confirmExisitingClients(['clients_to_auto_confirmId' => $clients_to_auto_confirmId, 'confirmed_clientsCount' => $confirmed_clientsCount, 'eventCapacity' => $eventClass->sec_capacity, 'eventId' => $eventClass->sec_id]);
                                }
                            }
                        }

                        if ($request->field == 'class' || $request->field == 'price') {
                            if ($request->classPrice != '') {
                                $eventClass->sec_price        = ($request->classPrice);
                                $eventRecurData['secr_price'] = $eventClass->sec_price;
                                $targetEventData['sec_price'] = $eventClass->sec_price;
                                $efectiveEventIds[]           = $eventClass->sec_id;
                            }
                        }

                        if ($request->field == 'date' || $request->field == 'time') {
                            $eventClass->sec_date        = $startDate;
                            $eventClass->sec_time        = $request->classTime;
                            $targetEventData['sec_time'] = $request->classTime;
                        }

                        if ($request->field == 'date' || $request->field == 'time' || $request->field == 'duration') {
                            $eventClass->sec_start_datetime    = $eventStartDatetime;
                            $eventClass->sec_end_datetime      = $eventEndDatetime;
                            $eventRecurData['secr_start_time'] = Carbon::parse($eventStartDatetime)->format('H:i:s');
                            $eventRecurData['secr_end_time']   = Carbon::parse($eventEndDatetime)->format('H:i:s');
                        }

                        if (($request->field == 'duration' || $request->field == 'staff' || $request->field == 'class' || $request->field == 'date' || $request->field == 'time') && $request->staff && $request->staff != $eventClass->sec_staff_id) {

                            $eventClass->sec_staff_id        = toInt($request->staff);
                            $eventRecurData['secr_staff_id'] = $eventClass->sec_staff_id;
                            $targetEventData['sec_staff_id'] = $eventClass->sec_staff_id;
                        }

                        $existArea = $eventClass->areas()->pluck('seca_la_id')->toArray();
                        if (($request->field == 'duration' || $request->field == 'staff' || $request->field == 'area' || $request->field == 'class' || $request->field == 'date' || $request->field == 'time') && $request->modalLocArea && (!custom_arr_comp($request->modalLocArea, $existArea))) {

                            /* start:Area manage section */
                            $response = $this->manageAreasLinkage($eventClass, $request->modalLocArea);
                            $historyText .= $response['history'];
                            /* end:Area manage section */
                            $eventRecurData['secr_area_id'] = implode(',', $request->modalLocArea);
                            $targetEventData['areaId']      = $request->modalLocArea;
                        }

                        if ($request->field == 'markFav' && $request->ifMarkFav) {
                            $this->changeFavArea($eventClass->sec_staff_id, $request->modalLocArea, $request->markFavTill);
                        }

                        if ($request->field == 'duration' || $request->field == 'class' || $request->field == 'date' || $request->field == 'time' || $request->field == 'resource' || $request->field == 'resourceRemove') {
                            $this->linkEventclassResource($request, $eventClass->sec_id, 'edit');
                            $targetEventData['resource'] = 'edit';
                        }

                        if (($request->field == 'time' || $request->field == 'date') && $request->historyText) {
                            $this->ammendHistory(['text' => rtrim($request->historyText, "|"), 'event' => $eventClass]);
                        }

                        /* update event */
                        $eventClass->update();

                        /* if event is this and future */
                        if ($request->has('targetEvents') && $request->targetEvents == 'future') {
                            /* Save Recurence updated data */
                            if ($eventClass->sec_secr_id != 0) {
                                $eventRecurence = $eventClass->repeat()->first();
                                if (count($eventRecurence)) {
                                    if (isset($targetEventData['resource'])) {
                                        $eventRecurData['secr_resources'] = $this->resourceRecurData($request);
                                    }

                                    if (count($eventRecurData)) {
                                        $eventRecurence->update($eventRecurData);
                                    }

                                }

                                $associatedEvents = StaffEventClass::where('sec_secr_id', $eventClass->sec_secr_id)->whereDate('sec_date', '>', $eventClass->sec_date)->get();

                                if ($associatedEvents->count()) {
                                    foreach ($associatedEvents as $associatedEvent) {
                                        if (isset($targetEventData['areaId']) && count($targetEventData['areaId'])) {
                                            $this->manageAreasLinkage($associatedEvent, $targetEventData['areaId']);
                                        }

                                        if (isset($targetEventData['resource'])) {
                                            $this->linkEventclassResource($request, $associatedEvent->sec_id, 'edit');
                                        }

                                        if (isset($targetEventData['sec_time'])) {
                                            $startEndtimeStamp                     = $this->calcStartAndEndDatetime(['startTime' => $targetEventData['sec_time'], 'startDate' => $associatedEvent->sec_date, 'duration' => $associatedEvent->sec_duration]);
                                            $targetEventData['sec_start_datetime'] = $startEndtimeStamp['startDatetime'];
                                            $targetEventData['sec_end_datetime']   = $startEndtimeStamp['endDatetime'];
                                        }

                                        if (isset($targetEventData['sec_duration'])) {
                                            $startEndtimeStamp                     = $this->calcStartAndEndDatetime(['startTime' => $associatedEvent->sec_time, 'startDate' => $associatedEvent->sec_date, 'duration' => $targetEventData['sec_duration']]);
                                            $targetEventData['sec_start_datetime'] = $startEndtimeStamp['startDatetime'];
                                            $targetEventData['sec_end_datetime']   = $startEndtimeStamp['endDatetime'];
                                        }
                                        $associatedEvent->update($targetEventData);
                                        if (isset($targetEventData['sec_capacity'])) {
                                            $allPrevClients = $associatedEvent->clients;
                                            if (count($allPrevClients)) {
                                                $confirmed_clientsCount    = $allPrevClients->where('pivot.secc_client_status', 'Confirm')->count();
                                                $clients_to_auto_confirmId = $allPrevClients->where('pivot.secc_client_status', 'Waiting')->pluck('id')->toArray();

                                                /*Auto Confirm client when capacity change */
                                                $this->confirmExisitingClients(['clients_to_auto_confirmId' => $clients_to_auto_confirmId, 'confirmed_clientsCount' => $confirmed_clientsCount, 'eventCapacity' => $associatedEvent->sec_capacity, 'eventId' => $associatedEvent->sec_id]);
                                            }
                                        }

                                        /* Additional data updation */
                                        $efectiveEventIds[] = $associatedEvent->sec_id;
                                    }
                                }

                                if ($request->field == 'date') {
                                    $deleteEvents = StaffEventClass::where('sec_secr_id', $eventClass->sec_secr_id)->whereDate('sec_date', '>', $eventClass->sec_date)->get();
                                    foreach ($deleteEvents as $deleteEvent) {
                                        $deleteEvent->forcedelete();
                                    }
                                }
                            }
                        }

                        /* Update this and future event price in invoice*/
                        if (($request->field == 'class' || $request->field == 'price') && $request->classPrice != '') {
                            $effectiveData                   = array();
                            $effectiveData['date']           = $request->classDate;
                            $effectiveData['price']          = $request->classPrice;
                            $effectiveData['type']           = 'classEvent';
                            $effectiveData['targetEventids'] = $efectiveEventIds;
                            $this->updateEffectivePrice($eventClass->sec_id, $effectiveData);
                        }
                    } else if ($request->step == 'classReccur' && $request->eventRepeat != '') {
                        
                        $oldRecurrenceDetails = collect();
                        if ($request->eventRepeat == 'None') {
                            // repeat is none
                            if ($eventClass->sec_secr_id != 0) {
                                $oldEventRecurence = $eventClass->repeat()->first();
                                if (count($oldEventRecurence)) {
                                    StaffEventClass::where('sec_secr_id', $oldEventRecurence['secr_id'])->whereDate('sec_date', '>', $eventClass->sec_date)->forcedelete();
                                    $oldEventRecurence->delete();
                                    $eventClass->update(array('sec_secr_id' => 0));
                                }
                            }
                        } else {
                            // repeat is daily, weekely, monthaly
                            if ($eventClass->sec_secr_id != 0) {
                                $oldRecurrenceDetails = $oldEventRecurence = $eventClass->repeat()->first();
                            }

                            // $this->adjustMembershipOfRecurredClient($oldEventRecurence->secr_id, $eventClass->sec_date, $eventClass->existClientRecurData());
                            // dd($oldRecurrenceDetails->toArray());
                            if ($eventClass->sec_secr_id != 0 && count($oldEventRecurence)) {
                                $newEventRecurence = $oldEventRecurence->replicate();
                                StaffEventClass::where('sec_secr_id', $oldEventRecurence['secr_id'])->whereDate('sec_date', '>', $eventClass->sec_date)->forcedelete();
                                // $oldEventRecurence->delete();
                                $noOfChild = StaffEventClass::where('sec_secr_id', $oldEventRecurence['secr_id'])->count() - 1;
                            } else {
                                $newEventRecurence                   = new StaffEventClassRepeat;
                                $newEventRecurence->secr_business_id = Session::get('businessId');
                                $noOfChild                           = 0;
                            }

                            $newEventRecurence->secr_repeat          = $request->eventRepeat;
                            $newEventRecurence->secr_repeat_interval = $request->eventRepeatInterval;
                            $newEventRecurence->secr_child_count     = $noOfChild;
                            $newEventRecurence->secr_staff_id        = $eventClass->sec_staff_id;
                            $newEventRecurence->secr_start_time      = Carbon::parse($eventClass->sec_start_datetime)->format('H:i:s');
                            $newEventRecurence->secr_end_time        = Carbon::parse($eventClass->sec_end_datetime)->format('H:i:s');
                            $newEventRecurence->secr_class_id        = $eventClass->sec_class_id;
                            $newEventRecurence->secr_duration        = $eventClass->sec_duration;
                            $newEventRecurence->secr_capacity        = $eventClass->sec_capacity;
                            $newEventRecurence->secr_price           = $eventClass->sec_price;
                            $newEventRecurence->secr_resources       = $eventClass->existResourceRecurData();
                            $newEventRecurence->secr_client_id       = $eventClass->existClientRecurData();
                            $newEventRecurence->secr_repeat_end      = $request->eventRepeatEnd;

                            if ($request->eventRepeat == 'Weekly') {
                                $newEventRecurence->secr_repeat_week_days = json_encode($request->eventRepeatWeekdays);
                            } else {
                                $newEventRecurence->secr_repeat_week_days = '';
                            }

                            if ($request->eventRepeatEnd == 'After') {
                                $newEventRecurence->secr_repeat_end_after_occur = $request->eventRepeatEndAfterOccur;
                            } elseif ($request->eventRepeatEnd == 'On') {
                                $newEventRecurence->secr_repeat_end_after_occur = 0;
                                $newEventRecurence->secr_repeat_end_on_date     = $request->eventRepeatEndOnDate;
                            } elseif ($request->eventRepeatEnd == 'Never') {
                                $newEventRecurence->secr_repeat_end_after_occur = 0;
                            }

                            $eventsAreas = $eventClass->areas->pluck('la_id')->toArray();
                            if (count($eventsAreas)) {
                                $newEventRecurence->secr_area_id = implode(',', $eventsAreas);
                            } else {
                                $newEventRecurence->secr_area_id = '';
                            }

                            $newEventRecurence->save();
                            $eventClass->sec_secr_id = $newEventRecurence->secr_id;
                            $eventClass->update();
                           
                            $classRecureResult = $this->isClassRecurePossible($newEventRecurence);

                            if($classRecureResult['status']) {
                                # If class, staff and areas are available then recurring 
                                $eventClass->sec_secr_id = $newEventRecurence->secr_id;
                                $eventClass->update();

                                if(isset($oldEventRecurence) && $oldEventRecurence) {
                                    $this->adjustMembershipOfRecurredClient($oldEventRecurence->secr_id, $eventClass->sec_date, $eventClass->existClientRecurData());
                                    $oldEventRecurence->delete();
                                }

                                $msg['repeatId'] = $newEventRecurence->secr_id;
                            } else {
                                # If class, staff and areas are not available then error 
                                $eventClass->sec_secr_id = isset($oldEventRecurence) && $oldEventRecurence ? $oldEventRecurence->secr_id : 0;
                                $eventClass->update();
                                $newEventRecurence->delete();

                                $msg['repeatId'] =  isset($oldEventRecurence) && $oldEventRecurence ? $oldEventRecurence->secr_id : 0;
                                $msg['status']      = 'error';
                                $msg['errorData'][] = array('unableRecure' => $classRecureResult['message']);
                                $isError            = true;

                                return json_encode($msg);
                            } 
                        }
                    } else if ($request->step == 'classClients') {                         
                        $attachClients = [];
                        $businessId    = Session::has('businessId') ? Session::get('businessId') : null;
                        // dd($businessId);
                        foreach ($request->classClients as $key => $value) {
                            $isEpicInvoice          = $isEpicCredit          = 0;
                            $remainingClassAmt      = 0;
                            $isEpicInvoiceAndCredit = 0;
                            $isBookedWithInvoice    = 0;
                            $isBookedWithCredit     = 0;
                            $remainingEpicCredit    = 0;

                            $client = Clients::select('id', 'account_status', 'epic_credit_balance')->find($key);

                            if ($client && !in_array($client->account_status, ['Active', 'Contra'])) {
                                /* Start: For Pending,Active lead,Inactive lead, Pre cons,Pre bench, Pre train, In active, On hold clients */
                         
                                if($client && in_array($client->account_status, ['Pre-Training', 'Pre-Benchmarking','Pre-Consultation'])){
                                    if (($value['isCreatingMakeUpSession'] || $value['forceAdd']) && $client->epic_credit_balance > 0) {

                                        $userId = User::where('account_id', $key)->pluck('id')->first();

                                        if ($client->epic_credit_balance < $eventClass->sec_price) {

                                            $isEpicInvoice          = 1;
                                            $isEpicCredit           = 1;
                                            $isEpicInvoiceAndCredit = 1;

                                            $attachClients[$key]['secc_with_invoice']        = 1;
                                            $attachClients[$key]['secc_epic_credit']         = 1;
                                            $attachClients[$key]['secc_event_log']           = 'Booked with epic credit and invoice';
                                            $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();

                                            $remainingClassAmt = $eventClass->sec_price - $client->epic_credit_balance;

                                            $classEventMakeup                       = new Makeup();
                                            $classEventMakeup->makeup_user_id       = $classEventMakeup->UserInformation['id'];
                                            $classEventMakeup->makeup_user_name     = $classEventMakeup->UserInformation['name'];
                                            $classEventMakeup->makeup_client_id     = $key;
                                            $classEventMakeup->makeup_notes_id      = 0;
                                            $classEventMakeup->makeup_purpose       = 'class';
                                            $classEventMakeup->makeup_session_count = 0;
                                            $classEventMakeup->makeup_amount        = -($client->epic_credit_balance);
                                            $classEventMakeup->makeup_extra         = 'EPIC Credit for <a href="' . url('/') . '/dashboard/calendar-new?mevid=' . $eventClass->sec_id . '&mevtype=class">' . date('D, d M Y H:i A', strtotime($eventClass->sec_start_datetime)) . '</a> class';
                                            $classEventMakeup->makeup_total_amount  = 0.00;
                                            $classEventMakeup->save();

                                            $this->setEpicBalance($client->id);

                                            $invResponse = $this->raiseAnInvoice($eventClass, $client->id, false, 0, '', abs($remainingClassAmt));

                                            /* Start: Invoice partially paid using epic credit */
                                            $paymentData['totalAmount']       = $eventClass->sec_price;
                                            $paymentData['payAmount']         = $client->epic_credit_balance;
                                            $paymentData['outStandingAmount'] = $eventClass->sec_price - $client->epic_credit_balance;
                                            $paymentData['paymentType']       = 'EPIC Credit';
                                            $isPaymentComplete                = $this->autoInvoicePayment($invResponse['invoiceId'], $paymentData);
                                            /* End: Invoice partially paid using epic credit*/

                                        } else {

                                            $attachClients[$key]['secc_epic_credit']         = 1;
                                            $attachClients[$key]['secc_with_invoice']        = 0;
                                            $attachClients[$key]['secc_event_log']           = 'Booked with epic credit';
                                            $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();
                                            $amount                                          = $eventClass->sec_price;

                                            $msg['isEpicCredit'] = 1;
                                            $isEpicInvoice       = 0;
                                            $isEpicCredit        = 1;
                                            $isBookedWithCredit  = 1;
                                            $remainingEpicCredit = $client->epic_credit_balance - $eventClass->sec_price;

                                            /* Raise invoice for class booked with epic credit.*/
                                            $invResponse = $this->raiseAnInvoice($eventClass, $client->id, true, 0, $businessId, $eventClass->sec_price);
                                        }
                                    }else if($salesProgress == 1){
                                        // $staffEvent = StaffEventClass::withTrashed()->OfBusiness()->find($request->eventId);
                                        $staffEvent = StaffEventClass::with('clasWithTrashed', 'clientsWithTrashedWithPivotTrashed', 'historiesWithTrashed', 'locationAndAreasWithTrashed', 'staffWithTrashed', 'repeat', 'resourcesWithTrashed')->withTrashed()->OfBusiness()->find($request->eventId);
                                         if ($staffEvent) {
                                        $event['id']            = $staffEvent->sec_id;
                                        $event['date']          = $staffEvent->sec_date;
                                        $event['startTime']     = $staffEvent->sec_time;
                                        $event['notes']         = $staffEvent->sec_notes;
                                        $event['startDatetime'] = $staffEvent->sec_start_datetime;
                                        $event['endDatetime']   = $staffEvent->sec_end_datetime;
                                        $event['userId']        = $staffEvent->sec_user_id;

                                        if ($staffEvent->sec_secr_id != 0) {
                                            $event['isRepeating'] = 1;
                                        } else {
                                            $event['isRepeating'] = 0;
                                        }

                                        $i = 0;
                                        foreach ($staffEvent->locationAndAreasWithTrashed as $area) {
                                            $event['areas'][$i]['id']            = $area->la_id;
                                            $event['areas'][$i]['locAreaName']   = $area->locationWithTrashed->location_training_area . ' - ' . $area->la_name;
                                            $event['areas'][$i]['isAreaDeleted'] = $area->trashed();
                                            $i++;
                                        }

                                        $event['staffName']      = $staffEvent->staffWithTrashed->fullName;
                                        $event['staffId']        = $staffEvent->sec_staff_id;
                                        $event['isStaffDeleted'] = $staffEvent->staffWithTrashed->trashed();

                                        $event['classId']        = $staffEvent->sec_class_id;
                                        $event['className']      = $staffEvent->clasWithTrashed->cl_name;
                                        $event['color']          = $staffEvent->clasWithTrashed->cl_colour;
                                        $event['isClassDeleted'] = $staffEvent->clasWithTrashed->trashed();
                                        $event['duration']       = $staffEvent->sec_duration;
                                        $event['capacity']       = $staffEvent->sec_capacity;
                                        $event['price']          = $staffEvent->sec_price;

                                        if ($staffEvent->repeat()->count()) {
                                            $repeat                            = $staffEvent->repeat()->first();
                                            $event['eventRepeat']              = $repeat->secr_repeat;
                                            $event['eventRepeatInterval']      = $repeat->secr_repeat_interval;
                                            $event['eventRepeatEnd']           = $repeat->secr_repeat_end;
                                            $event['eventRepeatEndAfterOccur'] = $repeat->secr_repeat_end_after_occur;
                                            $event['eventRepeatEndOnDate']     = $repeat->secr_repeat_end_on_date;
                                            $event['eventRepeatWeekDays']      = $repeat->secr_repeat_week_days;
                                        }

                                        $event['linkedClientsCount'] = 0;
                                        $i                           = 0;
                                        foreach ($staffEvent->clients as $client) {
                                            $event['clients'][$i]['id']            = $client->id;
                                            $event['clients'][$i]['name']          = $client->fullName;
                                            $event['clients'][$i]['phone']         = $client->phonenumber;
                                            $event['clients'][$i]['email']         = $client->email;
                                            $event['clients'][$i]['note']          = $client->pivot->secc_notes;
                                            $event['clients'][$i]['isReducedRate'] = $client->pivot->secc_reduce_rate_session;
                                            $event['clients'][$i]['ifRecur']       = $client->pivot->secc_if_recur;
                                            $event['clients'][$i]['attendance']    = $client->pivot->secc_client_attendance;
                                            $event['clients'][$i]['status']        = $client->pivot->secc_client_status;
                                            $event['clients'][$i]['photo']         = $client->profilepic;
                                            $event['clients'][$i]['accStatus']     = $client->account_status;
                                            $event['clients'][$i]['isLdc']         = $client->pivot->is_ldc;
                                            /*$event['clients'][$i]['isMakeUp'] = $client->pivot->secc_epic_credit;*/
                                            $event['clients'][$i]['isInvoice'] = $client->pivot->secc_with_invoice;
                                            /*$event['clients'][$i]['isInvoice'] = $this->isEventHasInvoice($staffEvent->sec_id, $client->id, 'class' );*/
                                            $event['clients'][$i]['isEpicCredit'] = $client->pivot->secc_epic_credit;
                                            if ($client->pivot->secc_epic_credit) {
                                                $event['clients'][$i]['epicCreditBal'] = $this->setEpicBalance($client->id);
                                            }

                                            $event['clients'][$i]['isClientDeleted']  = $client->trashed();
                                            $event['clients'][$i]['consultationDate'] = $client->consultation_date;
  
                                            if (!$client->trashed()) {
                                                $event['linkedClientsCount']++;
                                            }

                                            if ($client->pivot->secc_reduce_rate == null) {
                                                $event['clients'][$i]['reducedRate'] = '';
                                            } else {
                                                $event['clients'][$i]['reducedRate'] = $client->pivot->secc_reduce_rate;
                                            }

                                            $event['clients'][$i]['isSalesProcEvent'] = $this->isBookingSalesProcBooking($staffEvent->sec_id, $staffEvent->sec_date, $client);

                                            $i++;
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
                                            $attachClients[$key]['sales_step_number'] =$salesProcessStepNumb;
                                          
                                        }
                                       
                                    } else {

                                        $attachClients[$key]['secc_with_invoice']        = 1;
                                        $attachClients[$key]['secc_epic_credit']         = 0;
                                        $attachClients[$key]['secc_event_log']           = 'Booked with invoice';
                                        // $attachClients[$key]['secc_reduce_rate']         = $value['reducedRate'];
                                       
                                        $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();
                                         $isEpicInvoice       = 1;
                                        $isEpicCredit        = 0;
                                        $isBookedWithInvoice = 1;
                                    }
                                }else{
                                    $attachClients[$key]['secc_epic_credit']         = 1;
                                    $attachClients[$key]['secc_with_invoice']        = 0;
                                    $attachClients[$key]['secc_event_log']           = 'Booked with epic credit';
                                    $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();

                                    $amount = $eventClass->sec_price;

                                    $msg['isEpicCredit'] = 1;
                                    $isEpicInvoice       = 0;
                                    $isEpicCredit        = 1;
                                    $isBookedWithCredit  = 1;
                                    $remainingEpicCredit = $client->epic_credit_balance - $eventClass->sec_price;

                                    /* Raise invoice for class booked with epic credit.*/
                                    $invResponse = $this->raiseAnInvoice($eventClass, $client->id, true, 0, $businessId, $eventClass->sec_price);
                                }
                            } else {
                                /* Start: For Contra, Active clients */
                                $membership = $this->satisfyMembershipRestrictions($key, ['event_type' => 'class', 'event_id' => $eventClass->sec_class_id, 'event_date' => $eventClass->sec_date]);

                                if ($value['ifRecur'] == 0 && (!$membership['satisfy'] || $value['forceAdd'] == 'yes')) {
                                    if(isset($value['isLdc']) && $value['isLdc'] == 1){
                                        $attachClients[$key]['secc_cmid']         = $membership['clientMembId'];
                                        $attachClients[$key]['secc_with_invoice'] = 0;
                                        $attachClients[$key]['secc_epic_credit']  = 0;
                                        $attachClients[$key]['is_ldc']  = 1;
                                        $attachClients[$key]['secc_event_log']           ='Booked with LDC class';
                                        $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();
                                    }else{
                                        $client = Clients::where('id', $key)->select('id', 'firstname', 'lastname', 'epic_credit_balance')->first();

                                        if (($value['isCreatingMakeUpSession'] || $value['forceAdd']) && $client->epic_credit_balance > 0) {

                                            $userId = User::where('account_id', $key)->pluck('id')->first();

                                            if ($client->epic_credit_balance < $eventClass->sec_price) {

                                                $isEpicInvoice          = 1;
                                                $isEpicCredit           = 1;
                                                $isEpicInvoiceAndCredit = 1;

                                                $attachClients[$key]['secc_with_invoice']        = 1;
                                                $attachClients[$key]['secc_epic_credit']         = 1;
                                                $attachClients[$key]['secc_event_log']           = 'Booked with epic credit and invoice';
                                                $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();

                                                $remainingClassAmt = $eventClass->sec_price - $client->epic_credit_balance;

                                                $classEventMakeup                       = new Makeup();
                                                $classEventMakeup->makeup_user_id       = $classEventMakeup->UserInformation['id'];
                                                $classEventMakeup->makeup_user_name     = $classEventMakeup->UserInformation['name'];
                                                $classEventMakeup->makeup_client_id     = $key;
                                                $classEventMakeup->makeup_notes_id      = 0;
                                                $classEventMakeup->makeup_purpose       = 'class';
                                                $classEventMakeup->makeup_session_count = 0;
                                                $classEventMakeup->makeup_amount        = -($client->epic_credit_balance);
                                                $classEventMakeup->makeup_extra         = 'EPIC Credit for <a href="' . url('/') . '/dashboard/calendar-new?mevid=' . $eventClass->sec_id . '&mevtype=class">' . date('D, d M Y H:i A', strtotime($eventClass->sec_start_datetime)) . '</a> class';
                                                $classEventMakeup->makeup_total_amount  = 0.00;
                                                $classEventMakeup->save();

                                                $this->setEpicBalance($client->id);

                                                $invResponse = $this->raiseAnInvoice($eventClass, $client->id, false, 0, '', abs($remainingClassAmt));

                                                /* Start: Invoice partially paid using epic credit*/
                                                $paymentData['totalAmount']       = $eventClass->sec_price;
                                                $paymentData['payAmount']         = $client->epic_credit_balance;
                                                $paymentData['outStandingAmount'] = $eventClass->sec_price - $client->epic_credit_balance;
                                                $paymentData['paymentType']       = 'EPIC Credit';
                                                $isPaymentComplete                = $this->autoInvoicePayment($invResponse['invoiceId'], $paymentData);
                                                /* End: Invoice partially paid using epic credit*/

                                            } else {

                                                $attachClients[$key]['secc_epic_credit']         = 1;
                                                $attachClients[$key]['secc_with_invoice']        = 0;
                                                $attachClients[$key]['secc_event_log']           = 'Booked with epic credit';
                                                $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();
                                                $amount                                          = $eventClass->sec_price;

                                                $msg['isEpicCredit'] = 1;
                                                $isEpicInvoice       = 0;
                                                $isEpicCredit        = 1;
                                                $isBookedWithCredit  = 1;
                                                $remainingEpicCredit = $client->epic_credit_balance - $eventClass->sec_price;

                                                /* Raise invoice for class booked with epic credit.*/
                                                $invResponse = $this->raiseAnInvoice($eventClass, $client->id, true, 0, $businessId, $eventClass->sec_price);
                                            }
                                        } else {
                                            $attachClients[$key]['secc_with_invoice']        = 1;
                                            $attachClients[$key]['secc_epic_credit']         = 0;
                                            $attachClients[$key]['secc_event_log']           = 'Booked with invoice';
                                            $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();

                                            $isEpicInvoice       = 1;
                                            $isEpicCredit        = 0;
                                            $isBookedWithInvoice = 1;
                                        }
                                    }
                                } else {
                                    if ($membership['satisfy']) {
                                        $attachClients[$key]['secc_cmid']         = $membership['clientMembId'];
                                        $attachClients[$key]['secc_with_invoice'] = 0;
                                        $attachClients[$key]['secc_epic_credit']  = 0;

                                        $attachClients[$key]['secc_event_log']           = $value['ifRecur'] ? 'Booked with membership in recure' : 'Booked with membership';
                                        $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();
                                    }
                                }
                                if ($value['isReducedRate'] && !$value['isCreatingMakeUpSession']) {
                                    $attachClients[$key]['secc_reduce_rate']         = $value['reducedRate'];
                                    $attachClients[$key]['secc_with_invoice']        = 1;
                                    $isEpicInvoice                                   = 1;
                                    $attachClients[$key]['secc_epic_credit']         = 0;
                                    $attachClients[$key]['secc_event_log']           = 'Booked with invoice';
                                    $attachClients[$key]['secc_action_performed_by'] = getLoggedUserName();
                                    $isEpicCredit                                    = 0;
                                    $isBookedWithInvoice                             = 1;
                                } else {
                                    $attachClients[$key]['secc_reduce_rate'] = null;
                                }
                                // Add Log for add class
                                setInfoLog('Client add form particular class', $key);
                                // End
                                /* Increses membership limit */
                                if ($membership['satisfy'] && !$value['forceAdd']) {
                                    if ($value['ifRecur']) {
                                        # Set info log
                                        if(array_key_exists('type',$membership) && $membership['type'] == 'classExtra'){
                                            setInfoLog('Client membership limit updated on recure class booked with membership ', $key);
                                            $attachClients[$key]['secc_class_extra'] = 1;
                                            $clientMembershipLimit = collect();
                                        }else{
                                            setInfoLog('Client membership limit updated on recure class booked with membership ', $key);
                                            $clientMembershipLimit = collect();
                                          
                                            $membershipLimit       = $this->updateClientMembershipLimitLocaly($membership['clientMembLimit'], $key, ['type' => 'class', 'action' => 'add', 'eventId' => $eventClass->sec_class_id, 'date' => $eventClass->sec_date, 'limit_type' => $membership['limit_type']]);
                                            $clientMembershipLimit = $membershipLimit;
                                        }

                                        

                                        if (count($clientMembershipLimit)) {
                                            $clientMembershipLimit->save();
                                        }
                                    } else {
                                        # Set info log
                                        if(array_key_exists('type',$membership) && $membership['type'] == 'classExtra'){
                                            setInfoLog('Client membership limit updated on class booked with membership ', $key);
                                            $attachClients[$key]['secc_class_extra'] = 1;
                                        }else{
                                        setInfoLog('Client membership limit updated on class booked with membership ', $key);
                                        $this->updateClientMembershipLimit($satisfyMembershipClients, [$eventClass->sec_date], ['type' => 'class', 'action' => 'add', 'eventId' => $eventClass->sec_class_id, 'limit_type' => $membership['limit_type']]);
                                        }
                                    }
                                }

                            }

                            $confirmed_clientsCount = $eventClass->clients()->where('secc_client_status', 'Confirm')->count();
                            if ($confirmed_clientsCount >= $eventClass->sec_capacity) {
                                $attachClients[$key]['secc_client_status'] = 'Waiting';
                            } else {
                                $attachClients[$key]['secc_client_status'] = 'Confirm';
                                $confirmed_clientsCount++;
                            }
                            if ($value['ifRecur']) {
                                $attachClients[$key]['secc_if_recur'] = 1;
                            } else {
                                $attachClients[$key]['secc_if_recur'] = 0;
                            }
                      
                            // dd($attachClients);
                           
                            $eventClass->clients()->attach($attachClients);
                            /* Update event repeat client id */
                            if($salesProgress == 1){
                                $this->updateSalesStepData($request->eventId,(string)$idClient,(string)$salesProcessStepNumb);
                            }
                            if ($value['ifRecur'] && $eventClass->sec_secr_id != 0) {
                                $repeatClientIds   = [];
                                $eventRepeatClient = $eventClass->repeat()->first();
                                if (count($eventRepeatClient)) {
                                    $with_invoice = 0;
                                    $with_credit  = 0;

                                    // if($value['forceAdd'])
                                    //     $with_invoice = 1;

                                    if ($eventRepeatClient->secr_client_id != '') {
                                        $repeatClientIds       = json_decode($eventRepeatClient->secr_client_id, true);
                                        $repeatClientIds[$key] = array('with_invoice' => $with_invoice, 'with_credit' => $with_credit);
                                    } else {
                                        $repeatClientIds[$key] = array('with_invoice' => $with_invoice, 'with_credit' => $with_credit);
                                    }
                                    $eventRepeatClient->secr_client_id = json_encode($repeatClientIds);
                                    $eventRepeatClient->save();
                                }
                            }

                            // dd('kjhgfd');
                            $msg['clientStatus'] = $attachClients[$key]['secc_client_status'];
                            $addedClients        = $eventClass->clients()->where('secc_client_id', $key)->first();
                            // dd($addedClients);

                            $additionalHistoryText = '';

                            if ($value['ifRecur'] != 1) {
                                if ($isEpicInvoiceAndCredit) {
                                    $additionalHistoryText = ' with invoice and epic credit';
                                } else if ($isBookedWithCredit) {
                                    $additionalHistoryText = ' with epic credit';
                                } else if ($isBookedWithInvoice) {
                                    $additionalHistoryText = ' with invoice';
                                } else if (isset($value['isLdc']) && $value['isLdc'] == 1){
                                    $additionalHistoryText = ' with LDC Class';
                                }

                            }

                            $historyText = $this->eventclassClientHistory(['clients' => [$addedClients], 'action' => 'add', 'additional' => $additionalHistoryText, 'isRecureClient' => $value['ifRecur'] == 1]);

                            $dataForEmail                     = new stdClass();
                            $dataForEmail->eventDateTimeEmail = dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $eventClass->sec_start_datetime));
                            $dataForEmail->modalLocArea       = $this->getLinkedAreasId($eventClass);
                            $dataForEmail->staffClass         = $eventClass->sec_class_id;
                            $dataForEmail->staff              = $eventClass->sec_staff_id;
                            $alertHistoryText                 = $this->sendClientClassBookingEmail('confirm', $dataForEmail, [$addedClients]);

                            $msg['isSalesProcEvent'][$key] = $this->isBookingSalesProcBooking($eventClass->sec_id, $eventClass->sec_date, $addedClients);

                            if ($value['ifRecur']) {
                                if ($eventClass->sec_secr_id != 0) {
                                    $associatedEvents      = [];
                                    $membershipDates       = [];
                                    $classId               = 0;
                                    $limit_type            = '';
                                    $clientMembershipLimit = collect();
                                    $repeatEvent           = $eventClass->repeat()->first();

                                    if (count($repeatEvent)) {
                                        $associatedEvents = StaffEventClass::where('sec_secr_id', $repeatEvent['secr_id'])->whereDate('sec_date', '>=', $eventClass->sec_date)->get();
                                    }

                                    if (count($associatedEvents)) {
                                        $repeatingEventClients = $added_clientsMakeUpDeleteData = $updateClients = $membEvents = [];

                                        foreach ($associatedEvents as $associatedEvent) {
                                            $dateCarbon = new Carbon($associatedEvent->sec_date);

                                            $ifClientAlreadyExist = $associatedEvent->clients->where('id', $key)->first();
                                            if (!$ifClientAlreadyExist) {
                                                $with_invoice = 0;
                                                $with_credit  = 0;
                                                $membership = $this->satisfyMembershipRestrictions($key, ['event_type' => 'class', 'event_id' => $associatedEvent->sec_class_id, 'event_date' => $associatedEvent->sec_date], $clientMembershipLimit);
                                                $cmid       = $membership['clientMembId'];
                                                $isInvoice  = false;

                                                if ($membership['satisfy']/*|| $with_invoice*/) {
                                                    $timestamp = createTimestamp();
                                                    $data      = array('secc_sec_id' => $associatedEvent->sec_id, 'secc_client_id' => $key, 'secc_cmid' => $cmid, 'secc_if_recur' => 1, 'secc_with_invoice' => $with_invoice, 'secc_epic_credit' => $with_credit, 'created_at' => $timestamp, 'updated_at' => $timestamp);

                                                    if ($associatedEvent->clients->count() >= $associatedEvent->sec_capacity) {
                                                        $data['secc_client_status'] = 'Waiting';
                                                    } else {
                                                        $data['secc_client_status'] = 'Confirm';
                                                    }

                                                    $data['secc_action_performed_by'] = getLoggedUserName();
                                                    $data['secc_event_log']           = 'Booked with membership in recure';
                                                    if(array_key_exists('type',$membership) && $membership['type'] == 'classExtra'){
                                                        $data['secc_class_extra']  = 1;
                                                    }

                                                    $repeatingEventClients[] = $data;
                                                    if ($membership['satisfy']) {
                                                        $membershipLimit       = $this->updateClientMembershipLimitLocaly($membership['clientMembLimit'], $key, ['type' => 'class', 'action' => 'add', 'eventId' => $associatedEvent->sec_class_id, 'date' => $associatedEvent->sec_date, 'limit_type' => $membership['limit_type']]);
                                                        $clientMembershipLimit = $membershipLimit;
                                                    }
                                                }

                                            } else if (!$ifClientAlreadyExist->pivot->secc_if_recur) {
                                                $updateClients[] = $ifClientAlreadyExist->pivot->secc_id;
                                            }

                                        }

                                        if (count($clientMembershipLimit)) {
                                            $clientMembershipLimit->save();
                                        }

                                        if (count($repeatingEventClients)) {
                                            DB::table('staff_event_class_clients')->insert($repeatingEventClients);

                                        }
                                        if (count($updateClients)) {
                                            DB::table('staff_event_class_clients')->whereIn('secc_id', $updateClients)->update(['secc_if_recur' => 1, 'updated_at' => createTimestamp()]);
                                        }

                                    }
                                }
                            }
                            /* Start: Create invoice for class client booking */
                            if ($value['isReducedRate'] || $isBookedWithInvoice) {
                                $isEpicInvoice       = 1;
                                $isEpicCredit        = 0;
                                $isBookedWithInvoice = 1;
                                $invResponse         = $this->raiseAnInvoice($eventClass, $key, false, $value['reducedRate']);
                                if ($invResponse['status'] == 'invoice_exist') {
                                    $msg['invExistId'] = $invResponse['invoiceId'];
                                } elseif ($invResponse['status'] == 'new_invoice') {
                                    $msg['invDue'] = $eventClass->sec_date;
                                }
                            }
                            /* End: Create invoice for class client booking */

                            /* Some important updates After updating class event*/
                            if ($historyText) {
                                $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);
                            }

                            if ($alertHistoryText) {
                                $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);
                            }
                            if($salesProgress == 1){
                                if($isError){
                                    $msg['status']      = 'error';
                                    $msg['errorData'][] = array('clientUnavail' => $busyClientIds);
                                }else{
                                $msg['status']  = 'added';
                                $msg['message'] = "Your class is booked with sales process.";
                                }
                            } else if ($isEpicInvoiceAndCredit) {
                                $msg['status']  = 'added';
                                $msg['message'] = "Your class is booked with Epic Credit and Invoice.\n Epic Credit Used: $" . number_format($eventClass->sec_price - $remainingClassAmt, 2) . "\nInvoice Amount: $" . number_format($remainingClassAmt, 2) . "\n You need to pay invoice to start the class.";
                            } else if ($isBookedWithCredit) {
                                $msg['status']  = 'added';
                                $msg['message'] = "Class has been booked successfully using epic credit \n Class Price: $" . number_format($eventClass->sec_price, 2) . "\n Remaining Epic Credit: $" . number_format($remainingEpicCredit, 2);
                            } else if ($isBookedWithInvoice) {
                                $msg['status']  = 'added';
                                $msg['message'] = "Your class is booked with invoice. \n You need to pay the invoice to start the class.";
                            }else if (isset($value['isLdc']) && $value['isLdc'] == 1){
                                $msg['status']  = 'added';
                                $msg['message'] = "Your class is booked with LDC.";
                            } else {
                                $msg['status']  = 'added';
                                $msg['message'] = 'Class has been saved successfully.';
                            }
                        }

                        return json_encode($msg);
                    } elseif ($request->step == 'classNotes') {
                        $eventClass->sec_notes = $request->classNote;
                        $eventClass->update();
                    } elseif ($request->step == 'removeClient') {     
                        $invoice                 = [];
                        $removedClients          = Clients::find($request->clientId);
                        $membership              = Clients::paidMembership($request->clientId);
                        $eventClassDeletedClient = $eventClass->clients()->where('secc_client_id', $request->clientId)->first();

                        if (!$eventClassDeletedClient) {
                            $msg['status']       = 'error';
                            $msg['errorData'][]  = array('clientNotFount' => $request->clientId);
                            $msg['unsatisyReas'] = 'client_not_found';
                            $isError             = true;
                            return json_encode($msg);
                        }

                        if ($request->targetEvents != 'future' && $request->has('refund_resource') && $request->refund_resource == 'yes' && ($eventClassDeletedClient->pivot->secc_epic_credit == 1 || $eventClassDeletedClient->pivot->secc_with_invoice == 1)) {
                            $request['ifMakeUp']    = 1;
                            $request['purpose']     = 'class';
                            $request['makeupNotes'] = '';
                        }

                        if ($request->ifMakeUp) {
                            $eventClass->clients()->updateExistingPivot($request->clientId, ['secc_if_make_up_created' => 1, 'deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted manualy with epic credit', 'secc_action_performed_by' => getLoggedUserName()]);

                            $notesId = 0;
                            if ($request->makeupNotes) {
                                $notesId = $this->createNotes($request->makeupNotes, $request->clientId, 'makeup', 'Added while generating EPIC Credit on class booking', 'epic credit raise.');
                            }

                            if ($request->amount > 0 || ($request->has('refund_resource') && $request->has('refund_resource') == 'yes' && ($eventClassDeletedClient->pivot->secc_epic_credit == 1 || $eventClassDeletedClient->pivot->secc_with_invoice == 1))) {

                                $invoice = Invoice::where('inv_client_id', $request->clientId)
                                    ->whereHas('invoiceitem', function ($query) use ($request) {
                                        $query->where('inp_product_id', $request->eventId)
                                            ->where('inp_type', 'class');
                                    })->first();

                                $startDatetime = dbDateToDateTimeString(new Carbon($eventClass->sec_start_datetime));
                                $notesLink     = $noteslink     = 'EPIC Credit for <a href="' . url('dashboard/calendar-new?mevid=' . $eventClass->sec_id . '&mevtype=class') . '">' . $startDatetime . '</a> class';

                                if ($invoice && $eventClassDeletedClient->pivot->secc_with_invoice == 1) {
                                    if ($invoice->inv_status == 'Unpaid') {
                                        $epicCreditUsed = InvoiceItems::where('inp_invoice_id', $invoice->inv_id)->sum('inp_paid_using_epic_credit');

                                        if ($epicCreditUsed > 0) {
                                            $this->updateEpicCredit($request->clientId, 0 - $epicCreditUsed, $request->purpose, $noteslink, $notesId);
                                        }
                                    } else if ($invoice->inv_status == 'Paid') {
                                        $this->updateEpicCredit($request->clientId, 0 - $invoice->inv_total, $request->purpose, $noteslink, $notesId);
                                    }
                                } else {
                                    $this->updateEpicCredit($request->clientId, 0 - $request->amount, $request->purpose, $noteslink, $notesId);
                                }
                            }
                        } else {
                            $logMessage = '';
                            if ($eventClassDeletedClient->pivot->secc_cmid != 0) {
                                if($request->isLdc)
                                    $logMessage = 'Deleted manualy with LDC';
                                else
                                    $logMessage = 'Deleted manualy with membership';
                            }

                            if ($eventClassDeletedClient->pivot->secc_with_invoice && $eventClassDeletedClient->pivot->secc_epic_credit) {
                                $logMessage = 'Deleted manualy with epic credit and invoice';
                            }

                            if ($eventClassDeletedClient->pivot->secc_with_invoice) {
                                $logMessage = 'Deleted manualy with invoice';
                            }

                            if ($eventClassDeletedClient->pivot->secc_epic_credit) {
                                $logMessage = 'Deleted manualy with epic credit';
                            }

                            $eventClass->clients()->updateExistingPivot($request->clientId, ['secc_event_log' => $logMessage, 'deleted_at' => createTimestamp(), 'secc_action_performed_by' => getLoggedUserName()]);

                            $dataForEmail = new stdClass();

                            $dataForEmail->eventDateTimeEmail = dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $eventClass->sec_start_datetime));
                            $dataForEmail->modalLocArea       = $this->getLinkedAreasId($eventClass);
                            $dataForEmail->staffClass         = $eventClass->sec_class_id;
                            $dataForEmail->staff              = $eventClass->sec_staff_id;
                            $alertHistoryText                 = $this->sendClientClassBookingEmail('cancel', $dataForEmail, [$removedClients]);
                            if (!$request->ifRecur) {
                                $this->manageSessionSalesProcess($removedClients);
                            }

                        }
                        //Add Log for delete class
                        setInfoLog('Client delete form particular class', $request->clientId);
                        // End
                        $historyText = $this->eventclassClientHistory(['clients' => [$removedClients], 'action' => 'remove']);

                        $allPrevClients             = $eventClass->clients;
                        $confirmed_clientsCount     = $allPrevClients->where('pivot.secc_client_status', 'Confirm')->count();
                        $clients_to_auto_confirmId  = $allPrevClients->where('pivot.secc_client_status', 'Waiting')->pluck('id')->toArray();
                        $msg['newConfirmedClients'] = $this->confirmExisitingClients(['clients_to_auto_confirmId' => $clients_to_auto_confirmId, 'confirmed_clientsCount' => $confirmed_clientsCount, 'eventCapacity' => $eventClass->sec_capacity, 'eventId' => $eventClass->sec_id]);

                        if ($request->targetEvents == 'future') {
                            if ($eventClass->sec_secr_id != 0) {
                                $eventRepeats = $eventClass->repeat()->first();

                                if (count($eventRepeats)) {

                                    $associatedEvents = StaffEventClass::where('sec_secr_id', $eventRepeats['secr_id'])->whereDate('sec_date', '>=', $eventClass->sec_date)->whereNull('deleted_at')->get();

                                    if ($request->has('refund_resource') && $request->refund_resource == 'yes') {
                                        # Set info log
                                        setInfoLog('Client membership limit updated on recure class cancelled ', $request->clientId);
                                    }

                                    if (count($associatedEvents)) {
                                        $removeRecurringClients = [];
                                        $removeClientsEventIds  = [];
                                        $removeDates            = [];
                                        $classId                = 0;

                                        foreach ($associatedEvents as $associatedEvent) {
                                            $removeRecurringClients[] = "($associatedEvent->sec_id, $request->clientId)";
                                            $removeClientsEventIds[]  = $associatedEvent->sec_id;
                                            if ($associatedEvent->clients->count()) {
                                                $clients_to_auto_confirmId = [];
                                                $confirmed_clientsCount    = 0;

                                                $allPrevClientsId = $allPrevClients->pluck('id')->toArray();
                                                foreach ($associatedEvent->clients as $client) {
                                                    if ($client->id != $request->clientId) {

                                                        if ($client->pivot->secc_client_status == 'Confirm') {
                                                            $confirmed_clientsCount++;
                                                        } else if ($client->pivot->secc_client_status == 'Waiting') {
                                                            if ($confirmed_clientsCount < $associatedEvent->sec_capacity) {
                                                                $clients_to_auto_confirmId[] = $client->id;
                                                                $confirmed_clientsCount++;
                                                            } else {
                                                                break;
                                                            }

                                                        }
                                                    }
                                                }
                                                if (count($clients_to_auto_confirmId)) {
                                                    DB::table('staff_event_class_clients')->where('secc_sec_id', $associatedEvent->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                                                }
                                            }

                                            if ($request->has('refund_resource') && $request->refund_resource == 'yes') {
                                                $staffEventClassClient = DB::table('staff_event_class_clients')->where('secc_sec_id', $associatedEvent->sec_id)->where('secc_client_id', $request->clientId)->first();
                                                if($staffEventClassClient->secc_class_extra == 0){
                                                $this->updateClientMembershipLimit([$request->clientId], [$associatedEvent->sec_date], ['type' => 'class', 'action' => 'remove', 'eventId' => $associatedEvent->sec_class_id, 'limit_type' => $membership ? $membership->cm_class_limit_type : '']);
                                                }
                                            }
                                        }

                                        if (count($removeRecurringClients) && count($removeClientsEventIds)) {
                                            $removeRecurringClients = implode(',', $removeRecurringClients);
                                            $this->copyDeletedClassClients($removeRecurringClients);

                                            DB::table('staff_event_class_clients')->where('secc_client_id', $request->clientId)->whereIn('secc_sec_id', $removeClientsEventIds)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted recurring client manualy', 'secc_action_performed_by' => getLoggedUserName()]);
                                        }
                                    }
                                }
                            }
                        }

                        // if ($request->has('delete_invoice') || ($request->has('ifMakeUp') && $request->ifMakeUp)) {
                        $invoice = Invoice::where('inv_client_id', $request->clientId)
                            ->whereHas('invoiceitem', function ($query) use ($request) {
                                $query->where('inp_product_id', $request->eventId)
                                    ->where('inp_type', 'class');
                            })->first();

                        $isEpicDelete = false;

                        /* Start: delete invoice created by class booked using invoice or split payment*/
                        if ($eventClassDeletedClient->pivot->secc_with_invoice == 1 && $invoice && (($request->has('delete_invoice') && $request->delete_invoice == 'yes_delete') || ($request->has('ifMakeUp') && $request->ifMakeUp && $invoice->inv_status == 'Unpaid') || ($request->has('ifMakeUp') && $request->ifMakeUp && $invoice->inv_status == 'Paid'))) {
                            $this->deleteEventInvoice($eventClass->sec_id, $request->clientId, 'class', true);
                        }

                        /* End: delete invoice created by class booked using invoice or split payment*/

                        /* Start: delete paid invoice created by class booked using epic credit*/
                        if ($eventClassDeletedClient->pivot->secc_with_invoice == 0 && $eventClassDeletedClient->pivot->secc_epic_credit == 1 && $invoice && ($request->has('ifMakeUp') && $request->ifMakeUp && $invoice->inv_status == 'Paid')) {
                            $this->deleteEventInvoice($eventClass->sec_id, $request->clientId, 'class', true);
                        }

                        /* End: delete paid invoice created by class booked using epic credit*/

                        /* remove client from class event table */
                        if ($eventClass->sec_secr_id != 0  && $request->targetEvents == 'future') {
                            $repeat         = $eventClass->repeat()->first();
                            $removeClientId = (int) $request->clientId;
                            if (count($repeat) && $repeat->secr_client_id != '') {
                                $clientsReccur = json_decode($repeat->secr_client_id, true);
                                if (count($clientsReccur)) {
                                    foreach ($clientsReccur as $key => $value) {
                                        if ($key == $removeClientId) {
                                            unset($clientsReccur[$key]);
                                        }

                                    }
                                }
                                if (count($clientsReccur)) {
                                    $repeat->secr_client_id = json_encode($clientsReccur);
                                } else {
                                    $repeat->secr_client_id = '';
                                }

                                $repeat->save();
                            }
                        }

                        if ($request->has('refund_resource') && $request->refund_resource == 'yes' && $eventClassDeletedClient->pivot->secc_cmid != 0 && !$request->ifMakeUp && $request->targetEvents != 'future') {
                            # Set info log
                            setInfoLog('Client membership limit updated on class cancelled ', $request->clientId);
                            if($eventClassDeletedClient->pivot->secc_class_extra == 0){
                             $this->updateClientMembershipLimit([$request->clientId], [$eventClass->sec_date], ['type' => 'class', 'action' => 'remove', 'eventId' => $eventClass->sec_class_id, 'limit_type' => $membership ? $membership->cm_class_limit_type : '']);
                            }
                        }
                    }

                    /* Some important updates After updating class event*/
                    if ($historyText) {
                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);
                    }

                    if ($alertHistoryText) {
                        $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);
                    }

                    if ($request->step == 'classClients' || $request->step == 'removeClient' || ($request->step == 'classDetails' && ($request->field == 'date' || $request->field == 'time'))) {
                        $teamSalesProcessClients = $eventClass->teamSalesProcessClients;
                        if ($teamSalesProcessClients->count()) {
                            foreach ($teamSalesProcessClients as $teamSalesProcessClient) {
                                $this->manageSessionSalesProcess($teamSalesProcessClient);
                            }
                        }
                    }

                    /* Success Ajax send after all event data manupulating */
                    $msg['status']  = 'added';
                    $msg['message'] = displayAlert('success|Class has been saved successfully.');
                }
            }
        }
        // dd($msg);
        return json_encode($msg);
    }

    /**
     * Delete given class
     *
     * @param
     * @return
     */
    public function destroy(Request $request)
    {
        $isError = false;
        $msg     = [];

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'delete-staff-event-class')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $event = StaffEventClass::OfStaff(Auth::user()->account_id)->find($request->eventId);
            } else {
                $event = StaffEventClass::OfBusiness()->find($request->eventId);
            }

            if ($event) {
                if ($request->has('targetEvents')) {
                    $repeat = $event->repeat()->first();
                    if ($request->targetEvents == 'future' && count($repeat)) {
                        /* Delete Associated event */
                        $associatedEvents = StaffEventClass::whereDate('sec_date', '>', $event->sec_date)->where('sec_secr_id', $repeat['secr_id'])->get();
                        if ($associatedEvents->count()) {
                            foreach ($associatedEvents as $associatedEvent) {
                                $associatedEvent->delete();
                                $logText = 'Class( Id:'.$associatedEvent->sec_id.', Date:'.$associatedEvent->sec_date.') deleted for future recurrence';
                                setInfoLog($logText, $associatedEvent->sec_id);
                            }
                        }

                        /* Manage repeat data */
                        $previousEvent = StaffEventClass::whereDate('sec_date', '<', $event->sec_date)->where('sec_secr_id', $repeat['secr_id'])->orderBy('sec_id', 'desc')->first();
                        if (count($previousEvent)) {
                            $repeat->secr_repeat_end             = 'On';
                            $repeat->secr_repeat_end_after_occur = 0;
                            $repeat->secr_repeat_end_on_date     = $previousEvent->sec_date;
                            $repeat->update();
                        } else {
                            $repeat->delete();
                        }
                    }
                }

                $event->delete();
                $logText = 'Class( Id:'.$event->sec_id.', Date:'.$event->sec_date.') deleted manually';
                setInfoLog($logText, $event->sec_id);

                $msg['status']  = 'deleted';
                $msg['message'] = displayAlert('success|Class has been cancelled.');
            }
        }
        return json_encode($msg);
    }

    /**
     * Team book
     * @param
     * @return
     */
    public function bookTeam(Request $request)
    {
        // dd($request->all());
        $isError = false;
        $msg     = [];

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-class')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $eventClass = StaffEventClass::OfStaff(Auth::user()->account_id)
                    ->find($request->eventId);
            } else {
                $eventClass = StaffEventClass::OfBusiness()->find($request->eventId);
            }

            if ($eventClass) {
                $client = Clients::findClient($request->clientId);
                if ($client) {
                    $salesProcessRelatedStatus = calcSalesProcessRelatedStatus((int) $request->salesProcessStepNumb);
                    // dd( $salesProcessRelatedStatus);
                    //if($client->account_status == $salesProcessRelatedStatus['clientPrevStatus']){
                    if (isClientInSalesProcess($client->consultation_date, $client->consul_exp_date) && /*statusMatchSalesStatus($client->account_status, $salesProcessRelatedStatus['clientPrevStatus'])*/$this->isStepEnabled($request->salesProcessStepNumb, $client->SaleProcessEnabledSteps) && $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)) {

                        /*$membership = $this->satisfyMembershipRestrictions($request->clientId, $eventClass->sec_id, $eventClass->sec_date);
                        $clientMembId = $membership['clientMembId'];
                        if($membership['satisfy'] == 'false'){
                        $msg['status'] = 'membershipUnsatisfy';
                        $msg['message'] = displayAlert('warning|'.$client->fullName.' could not be booked because they don\'t meet membership restrictions.');
                        $isError = true;
                        }
                        else{*/
                        $isClientBusy = $this->isClientBusy(['clientId' => [$request->clientId], 'startDatetime' => $eventClass->sec_start_datetime, 'endDatetime' => $eventClass->sec_end_datetime]);
                        if (count($isClientBusy)) {
                            $msg['status']  = 'clientBusy';
                            $msg['message'] = displayAlert('warning|' . $client->fullName . ' could not be booked because they were busy at specified hours.');
                            $msg['newMessage'] =$client->fullName . ' could not be booked because they were busy at specified hours.';
                            $isError        = true;
                        }
                        //}
                        if (!$isError) {
                            //$attachClient = ['secc_cmid' => $clientMembId];
                            if ($eventClass->clients->count() >= $eventClass->sec_capacity) {
                                $attachClient = ['secc_client_status' => 'Waiting'];
                            } else {
                                $attachClient = ['secc_client_status' => 'Confirm'];
                                $attachClient = ['sales_step_number' => $request->salesProcessStepNumb];
                            }
                            
                            $eventClass->clients()->attach($request->clientId, $attachClient);
                            $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'add']);
                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);

                            $dataForEmail                     = new stdClass();
                            $dataForEmail->eventDateTimeEmail = dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $eventClass->sec_start_datetime));
                            $dataForEmail->modalLocArea       = $eventClass->areas->pluck('la_id')->toArray();
                            $dataForEmail->staffClass         = $eventClass->sec_class_id;
                            $dataForEmail->staff              = $eventClass->sec_staff_id;
                            $alertHistoryText                 = $this->sendClientClassBookingEmail('confirm', $dataForEmail, [$client]);
                            $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);

                            //$this->deleteAdded_clientsMakeUpFromSameEvent($eventClass->sec_id, [$request->clientId]);

                            $clientOldSaleProcessStep  = $client->sale_process_step;
                            $client->sale_process_step = $request->salesProcessStepNumb;
                            $client->save();

                            //$salesProcessHistory = ['clientId'=>$client->id, 'eventId'=>$request->eventId, 'toType'=>$salesProcessRelatedStatus['salesProcessType'], 'toStep'=>$salesProcessRelatedStatus['saleProcessStepNumb'], 'fromStep'=>$clientOldSaleProcessStep, 'reason'=>'Team booked from sales process'];
                            //$msg['salesProcessDate'] = $this->saveSalesProcess($salesProcessHistory);
                            //$this->saveSalesProcess($salesProcessHistory);

                            //$this->manageTeamSalesProcess($client, $eventClass->sec_id, 'Team booked from sales process', 0, true);
                            // dd('oiug');
                            $this->manageSessionSalesProcess($client);
                            //dd($client->epic_credit_balance);

                            $msg['salesProcessDate'] = dbDateToDateString($eventClass->EventDateCarbon);
                            $msg['changeStatus']     = $this->getStatusForbackend($client->account_status, true) . '|' . $client->account_status;
                            $msg['stepCompleted']    = $request->salesProcessStepNumb;
                        }
                    }
                }
            }
        }
        return json_encode($msg);
    }

    /**
     * Makeup Client
     * @param
     * @return
     */
    public function makeUpClient(Request $request)
    {

        $isError = false;
        $msg     = [];

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-class')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $eventClass = StaffEventClass:://where('sec_staff_id', Auth::user()->account_id)
                    OfStaff(Auth::user()->account_id)
                    ->find($request->eventId);
            } else {
                $eventClass = StaffEventClass::OfBusiness()->find($request->eventId);
            }

            if ($eventClass) {
                //$client = Clients::find($request->clientId);
                $client = Clients::findClient($request->clientId);
                if ($client) {
                    $isClientBusy = $this->isClientBusy(['clientId' => [$request->clientId], 'startDatetime' => $eventClass->sec_start_datetime, 'endDatetime' => $eventClass->sec_end_datetime/*, 'date' => $eventClass->sec_date, 'startTime' => $eventClass->sec_time, 'endTime' => $eventClass->sec_end_time*/]);
                    if (count($isClientBusy)) {
                        $msg['status']  = 'clientBusy';
                        $msg['message'] = displayAlert('warning|' . $client->fullName . ' could not be rescheduled because they were busy at specified hours.');
                    } else {
                        if ($request->origEventId && $request->origEventId != 0) {
                            $classClientRec = DB::table('staff_event_class_clients')->where('secc_sec_id', $request->origEventId)->where('secc_client_id', $request->clientId)->first();
                        } else {
                            $classClientRec = DB::table('staff_event_class_clients')->where('secc_client_id', $request->clientId)->where('secc_epic_credit', 1)->where('secc_if_make_up_created', 0)->orderBy('secc_sec_id') /*->oldest()*/->first();
                        }

                        //$attachClient = ['secc_is_make_up_client' => 1];
                        if ($classClientRec) {
                            $attachClient['secc_notes']               = $classClientRec->secc_notes;
                            $attachClient['secc_invoice_status']      = $classClientRec->secc_invoice_status;
                            $attachClient['secc_reduce_rate_session'] = $classClientRec->secc_reduce_rate_session;
                            $attachClient['secc_reduce_rate']         = $classClientRec->secc_reduce_rate;
                            //$attachClient['secc_if_recur'] = $classClientRec->secc_if_recur;
                        }
               
                        if ($attachClient['secc_reduce_rate_session']) {
                            $attachClient['secc_with_invoice'] = 1;
                        }

                        if ($eventClass->clients->count() >= $eventClass->sec_capacity) {
                            $attachClient['secc_client_status'] = 'Waiting';
                        } else {
                            $attachClient['secc_client_status'] = 'Confirm';
                        }

                        $eventClass->clients()->attach($request->clientId, $attachClient);
                        $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'add']);
                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);

                        //$this->deleteAdded_clientsMakeUpFromSameEvent($eventClass->sec_id, [$request->clientId]);

                        if ($classClientRec) {
                            DB::table('staff_event_class_clients')->where('secc_id', $classClientRec->secc_id)->update(['secc_if_make_up_created' => 1, 'deleted_at' => createTimestamp(), 'secc_event_log' => 'Delete makeup client']);
                        } else {
                            $client->makeup_session_count--;
                            $client->save();
                        }

                        $this->manageSessionSalesProcess($client);

                        $msg['status'] = 'added';
                    }
                }
            }
        }
        return json_encode($msg);
    }

    /**
     * Client reschedule one class to another class
     * @param
     * @return
     */
    public function rescheduleClient(Request $request)
    {
        $isError = false;
        $msg     = [];

        if (Session::get('hostname') == 'crm') {
            if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-class')) {
                if ($request->ajax()) {
                    $isError = true;
                } else {
                    abort(404);
                }

            }
        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $eventClass = StaffEventClass::/*where('sec_staff_id', Auth::user()->account_id)*/OfStaff(Auth::user()->account_id)->find($request->eventId);
            } else {
                $eventClass = StaffEventClass::OfBusiness()->find($request->eventId);
            }

            if (isUserType(['Staff'])) {
                $origEvent = StaffEventClass::with('clientsOldestFirst')
                    ->OfStaff(Auth::user()->account_id)
                    ->find($request->origEventId);
            } else {
                $origEvent = StaffEventClass::with('clientsOldestFirst')
                    ->OfBusiness()
                    ->find($request->origEventId);
            }

            if ($eventClass) {
                if (count($request->classClients)) {
                    $requestClient = $request->classClients;
                    $clientId      = array_keys($requestClient)[0];

                    $client = Clients::OfBusiness()->find($clientId);

                    /* For rescheduling single class */
                    if ($request->targetEvents == 'this' || !$request->targetEvents) {
                        $isClientBusy = $this->isClientBusy(['clientId' => [$clientId], 'startDatetime' => $eventClass->sec_start_datetime, 'endDatetime' => $eventClass->sec_end_datetime, 'eventId' => $request->origEventId ], 'class');
                        if (count($isClientBusy)) {
                            $isError           = true;
                            $msg['status']     = 'clientBusy';
                            $msg['message']    = displayAlert('warning|' . $client->fullName . ' could not be rescheduled because they were busy at specified hours.');
                            $msg['orgMessage'] = $client->fullName . ' could not be rescheduled because they were busy at specified hours.';

                            return json_encode($msg);
                        }
                        if(isset($requestClient[$clientId]['isLdc']) && $requestClient[$clientId]['isLdc'] == 1){
                            $isSatisfy = $this->isSatisfyLdcRestriction($clientId,$request->eventId,'reschedule','class');                            
                            if(!$isSatisfy){
                                $isError           = true;
                                $msg['status']     = 'ldcRestrictionUnsatisfy';
                                $msg['message']    = displayAlert('warning|' . $client->fullName . ' could not be rescheduled because LDC restriction unsatisfied.');
                                $msg['orgMessage'] = $client->fullName . ' could not be rescheduled because LDC restriction unsatisfied.';

                                return json_encode($msg);
                            }
                        }
                    } else if ($request->targetEvents == 'future' && $requestClient[$clientId]['ifRecur']) {
                        /* For rescheduling this and future classes */
                        $associatedEvents = [];
                        $repeat           = $eventClass->repeat()->first();
                        $orgRepeat           = $origEvent->repeat()->first();

                        if (count($repeat)) {
                            $associatedEvents = StaffEventClass::where('sec_secr_id', $repeat['secr_id'])->whereDate('sec_date', '>=', $eventClass->sec_date)->orderBy('sec_date')->get();
                        }

                        if (count($orgRepeat)) {
                            $orgAssociatedEvents = StaffEventClass::where('sec_secr_id', $orgRepeat['secr_id'])->whereDate('sec_date', '>=', $origEvent->sec_date)->orderBy('sec_date')->select('sec_id')->pluck('sec_id')->toArray();
                        }

                        // if (count($associatedEvents)) {
                        //     foreach ($associatedEvents as $key => $associatedEvent) {
                        //         if (count($orgAssociatedEvents) > $key && $associatedEvent->clientsOldestFirst->count()) {
                        //            $isClientBusy = $this->isClientBusy(['clientId' => [$clientId], 'startDatetime' => $associatedEvent->sec_start_datetime, 'endDatetime' => $associatedEvent->sec_end_datetime, 'eventId' => $orgAssociatedEvents[$key] ], 'class');

                        //             if (count($isClientBusy)) {
                        //                 $isError           = true;
                        //                 $msg['status']     = 'clientBusy';
                        //                 $msg['message']    = displayAlert('warning|' . $client->fullName . ' could not be rescheduled because they were busy at specified hours in future.');
                        //                 $msg['orgMessage'] = $client->fullName . ' could not be rescheduled because they were busy at specified hours in future.';

                        //                 return json_encode($msg);
                        //             }
                        //         }
                        //     }
                        // }
                    }

                    if (!$isError) {
                        $attachClient = ['secc_notes' => $requestClient[$clientId]['note'], 'secc_reduce_rate_session' => $requestClient[$clientId]['isReducedRate']/*, 'secc_skip_memb_count' => 1*//*, 'secc_if_recur' => $requestClient[$clientId]['ifRecur']*/];

                        if ($origEvent) {
                            $clientMembId = $origEvent->clientsOldestFirst->where('id', $clientId)->first()->pivot->secc_cmid;

                            if(isset($requestClient[$clientId]['isLdc']) && $requestClient[$clientId]['isLdc'] == 1){
                                $attachClient['is_ldc'] = 1;
                            }
                            if ($clientMembId) {
                                $attachClient['secc_cmid'] = $clientMembId;
                                
                                if(!isset($requestClient[$clientId]['isLdc']) || $requestClient[$clientId]['isLdc'] == 1){
                                    $this->updateClientMembershipLimit($clientId, [$eventClass->sec_date], ['type' => 'class', 'action' => 'add', 'eventId' => $eventClass->sec_class_id]);
                                }

                                $clientMemb = ClientMember::select('cm_end_date')->find($clientMembId);
                                // dd( $clientMemb->toArray());
                                if ($clientMemb) {
                                    $membershipendDate = new Carbon($clientMemb->cm_end_date);
                                    $eventDate         = new Carbon($eventClass->sec_date);
                                    if ($eventDate->gt($membershipendDate)) {
                                        $attachClient['secc_skip_memb_change'] = 1;
                                    }

                                }
                            } else {
                                $withEpicCredit = $origEvent->clientsOldestFirst->where('id', $clientId)->first()->pivot->secc_epic_credit;
                                if ($withEpicCredit) {
                                    $attachClient['secc_epic_credit'] = 1;
                                }

                                $withInvoice = $origEvent->clientsOldestFirst->where('id', $clientId)->first()->pivot->secc_with_invoice;

                                if ($withInvoice) {
                                    /*Fetch invoice id of old event session*/
                                    $invoice = Invoice::where('inv_client_id', $clientId)
                                        ->whereHas('invoiceitem', function ($query) use ($request) {
                                            $query->where('inp_product_id', $request->origEventId)
                                                ->where('inp_type', 'class');
                                        })->first();

                                    if ($invoice) {
                                        /* Save invoice new due date */
                                        $invoice->inv_due_date = date('Y-m-d', strtotime($eventClass->sec_date));
                                        $invoice->save();

                                        $invoiceItem = InvoiceItems::where('inp_invoice_id', $invoice->inv_id)->whereNull('deleted_at')->first();

                                        if ($invoiceItem) {
                                            /* Get staff name of event */
                                            $eventStaff = $eventClass->staff()->first();
                                            $staffName  = (isset($eventStaff->first_name) ? $eventStaff->first_name : '') . ' ' . (isset($eventStaff->first_name) ? $eventStaff->last_name : '');

                                            $invoiceItem->inp_staff_id   = $eventClass->sec_staff_id;
                                            $invoiceItem->inp_item_desc  = $eventClass->clas()->first()->cl_name . '(Class) with ' . $staffName . ' on ' . date('D, d M Y', strtotime($eventClass->sec_date));
                                            $invoiceItem->inp_product_id = $eventClass->sec_id;
                                            $invoiceItem->save();
                                        }

                                        $attachClient['secc_with_invoice'] = 1;
                                    }

                                }
                            }
                        }

                        if (!$requestClient[$clientId]['isReducedRate']) {
                            $attachClient['secc_reduce_rate'] = null;
                        } else {
                            $attachClient['secc_reduce_rate']  = $requestClient[$clientId]['reducedRate'];
                            $attachClient['secc_with_invoice'] = 1;
                        }
                         
                        if ($request->targetEvents == 'future') {
                            $attachClient['secc_if_recur'] = $requestClient[$clientId]['ifRecur'];
                        } else {
                            
                            $attachClient['secc_if_recur'] = 0;
                        }

                        

                        if ($eventClass->clients->count() >= $eventClass->sec_capacity) {
                            $attachClient['secc_client_status'] = 'Waiting';
                        } else {
                            $attachClient['secc_client_status'] = 'Confirm';
                        }

                        /*$clientMember = Clients::paidMembership($clientId);
                        if($clientMember)
                        $attachClient['secc_cmid'] = $clientMember->id;*/

                        $eventClass->clients()->attach($clientId, $attachClient);

                        $origEventTimestamp    = strtotime($origEvent->sec_start_datetime);
                        $additionalHistoryText = '. Rescheduled from ' . date('l', $origEventTimestamp) . ' ' . date('d/m/y', $origEventTimestamp) . ' @' . date('H:i a', $origEventTimestamp) . ' by ' . Auth::user()->name . ' ' . Auth::user()->last_name;

                        // $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'add']);
                        $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'add', 'additional' => $additionalHistoryText]);

                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);

                        $dataForEmail                     = new stdClass();
                        $dataForEmail->eventDateTimeEmail = dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $eventClass->sec_start_datetime));
                        $dataForEmail->modalLocArea       = $eventClass->areas->pluck('la_id')->toArray();
                        $dataForEmail->staffClass         = $eventClass->sec_class_id;
                        $dataForEmail->staff              = $eventClass->sec_staff_id;
                        $alertHistoryText                 = $this->sendClientClassBookingEmail('confirm', $dataForEmail, [$client]);
                        $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);

                        //$this->deleteAdded_clientsMakeUpFromSameEvent($eventClass->sec_id, [$clientId]);

                        if ($origEvent) {
                            $removeRecurringClient   = ["($origEvent->sec_id, $clientId)"];
                            $removeClientsEventIds[] = $origEvent->sec_id;
                            $removeDates             = [];
                            $receduleClient          = $origEvent->clients()->where('id', $clientId)->first();
                            if (count($receduleClient) && $receduleClient->pivot->secc_with_invoice == 0) {
                                if($receduleClient->pivot->is_ldc == 0){
                                    // $this->updateClientMembershipLimit($clientId, [$origEvent->sec_date], ['type' => 'class', 'action' => 'sub', 'eventId' => $origEvent->sec_class_id]);
                                }
                            }
                            if ($request->targetEvents == 'future' && $requestClient[$clientId]['ifRecur']) {
                                $associatedEvents = [];
                                $repeat           = $origEvent->repeat()->first();
                                if (count($repeat)) {
                                    $associatedEvents = StaffEventClass::where('sec_secr_id', $repeat['secr_id'])->whereDate('sec_date', '>', $origEvent->sec_date)->orderBy('sec_date')->get();
                                }

                                //if($associatedEvents->count()){
                                if (count($associatedEvents)) {
                                    foreach ($associatedEvents as $associatedEvent) {
                                        if ($associatedEvent->clientsOldestFirst->count()) {
                                            //foreach($attachClients as $clientId => $value){
                                            //if($value['secc_if_recur'])

                                            // if (!count($isClientBusy)) {
                                            $removeRecurringClient[] = "($associatedEvent->sec_id, $clientId)";
                                            $removeClientsEventIds[] = $associatedEvent->sec_id;
                                            $assocClient             = $associatedEvent->clients()->where('id', $clientId)->first();
                                            if (count($assocClient) && $assocClient->pivot->secc_with_invoice == 0) {
                                                $removeDates[] = $associatedEvent->sec_date;
                                                $classId       = $associatedEvent->sec_class_id;
                                            }

                                            $clients_to_auto_confirmId = [];
                                            $confirmed_clientsCount    = 0;
                                            foreach ($associatedEvent->clientsOldestFirst as $clientsOldestFirst) {
                                                if ($clientsOldestFirst->id != $clientId) {
                                                    if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                                        $confirmed_clientsCount++;
                                                    } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $associatedEvent->sec_capacity) {
                                                        $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                                        $confirmed_clientsCount++;
                                                    }
                                                }
                                            }
                                            if (count($clients_to_auto_confirmId)) {
                                                DB::table('staff_event_class_clients')->where('secc_sec_id', $associatedEvent->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                                            }
                                            // }
                                        }
                                    }

                                    if (count($removeDates)) {
                                        $this->updateClientMembershipLimit($clientId, $removeDates, ['type' => 'class', 'action' => 'sub', 'eventId' => $classId]);
                                    }

                                }
                            }
                            if (count($removeRecurringClient) && count($removeClientsEventIds)) {
                                $removeRecurringClient = implode(',', $removeRecurringClient);
                                //$this->copyDeletedClassClients('secc_sec_id, secc_client_id', $removeRecurringClient);
                                $this->copyDeletedClassClients($removeRecurringClient);
                                /*DB::delete('delete from staff_event_class_clients where (secc_sec_id, secc_client_id) in ('.$removeRecurringClient.')');*/
                                DB::table('staff_event_class_clients')->where('secc_client_id', $clientId)->whereIn('secc_sec_id', $removeClientsEventIds)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Delete recedule client']);
                            }

                             /* remove client from class event table */
                            if ($origEvent->sec_secr_id != 0 && $request->targetEvents == 'future' && $requestClient[$clientId]['ifRecur']) {
                                $repeat         = $origEvent->repeat()->first();
                                $removeClientId = $clientId;
                                if (count($repeat) && $repeat->secr_client_id != '') {
                                    $clientsReccur = json_decode($repeat->secr_client_id, true);
                                    if (count($clientsReccur)) {
                                        foreach ($clientsReccur as $key => $value) {
                                            if ($key == $removeClientId) {
                                                unset($clientsReccur[$key]);
                                            }

                                        }
                                    }
                                    if (count($clientsReccur)) {
                                        $repeat->secr_client_id = json_encode($clientsReccur);
                                    } else {
                                        $repeat->secr_client_id = '';
                                    }

                                    $repeat->save();
                                }
                            }

                            $clients_to_auto_confirmId = [];
                            $confirmed_clientsCount    = 0;
                            foreach ($origEvent->clientsOldestFirst as $clientsOldestFirst) {
                                if ($clientsOldestFirst->id != $clientId) {
                                    if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                        $confirmed_clientsCount++;
                                    } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $origEvent->sec_capacity) {
                                        $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                        $confirmed_clientsCount++;
                                    }
                                }
                            }
                            if (count($clients_to_auto_confirmId)) {
                                DB::table('staff_event_class_clients')->where('secc_sec_id', $origEvent->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                            }

                            //$clientForHistory[] = $origEvent->clientsOldestFirst->where('id', $clientId)->first();
                            $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'remove']);
                            /*if(!$request->staffClass)
                            $request->staffClass = $origEvent->sec_class_id;
                            $alertHistoryText = $this->sendClientEventBookingEmail('cancel', $request, [$client]);*/
                            $request->staffClass = $origEvent->sec_class_id;
                            $request->staff      = $origEvent->sec_staff_id;
                            //$request->modalLocArea = $origEvent->sec_area_id;
                            $request->modalLocArea       = $origEvent->areas->pluck('la_id')->toArray();
                            $request->eventDateTimeEmail = dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $origEvent->sec_start_datetime));
                            $alertHistoryText            = $this->sendClientClassBookingEmail('cancel', $request, [$client]);
                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $origEvent]);
                            $this->alertHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $origEvent]);
                        }

                        if ($request->targetEvents == 'future' && $requestClient[$clientId]['ifRecur']) {
                            $repeat = $eventClass->repeat()->first();
                            if (count($repeat)) {
                                $associatedEvents = StaffEventClass::where('sec_secr_id', $repeat['secr_id'])->whereDate('sec_date', '>', $eventClass->sec_date)->orderBy('sec_date')->get();

                                if ($associatedEvents->count()) {
                                    $repeatingEventClients = $added_clientsMakeUpDeleteData = $updateClients = $weeklyEvents = $monthlyEvents = $membEvents = $eventDates = [];
                                    $classId               = 0;
                                    $limit_type            = '';
                                    $clientMembershipLimit = collect();
                                    foreach ($associatedEvents as $associatedEvent) {
                                        $ifClientAlreadyExist = $associatedEvent->clients->where('id', $clientId)->first();
                                        if (!$ifClientAlreadyExist) {
                                            $membership = $this->satisfyMembershipRestrictions($clientId, ['event_type' => 'class', 'event_id' => $associatedEvent->sec_class_id, 'event_date' => $associatedEvent->sec_date], $clientMembershipLimit);

                                            if ($membership['satisfy']) {
                                                $timestamp = createTimestamp();
                                                $data      = array('secc_sec_id' => $associatedEvent->sec_id, 'secc_client_id' => $clientId, 'secc_cmid' => $membership['clientMembId'], 'secc_if_recur' => 1, 'created_at' => $timestamp, 'updated_at' => $timestamp);

                                                if ($associatedEvent->clients->count() >= $associatedEvent->sec_capacity) {
                                                    $data['secc_client_status'] = 'Waiting';
                                                } else {
                                                    $data['secc_client_status'] = 'Confirm';
                                                }

                                                $repeatingEventClients[] = $data;
                                                /*$eventDates[] = $associatedEvent->sec_date;
                                                $classId = $associatedEvent->sec_class_id;
                                                $limit_type = $membership['limit_type'];*/
                                                //$added_clientsMakeUpDeleteData[] = "($associatedEvent->sec_id, $clientId, 1)";
                                                $membershipLimit = $this->updateClientMembershipLimitLocaly($membership['clientMembLimit'], $clientId, ['type' => 'class', 'action' => 'add', 'eventId' => $associatedEvent->sec_class_id, 'date' => $associatedEvent->sec_date, 'limit_type' => $membership['limit_type']]);

                                                $clientMembershipLimit = $membershipLimit;
                                            }
                                        } else if (!$ifClientAlreadyExist->pivot->secc_if_recur) {
                                            $updateClients[] = $ifClientAlreadyExist->pivot->secc_id;
                                        }

                                    }

                                    if (count($clientMembershipLimit)) {
                                        $clientMembershipLimit->save();
                                    }

                                    if (count($repeatingEventClients)) {
                                        DB::table('staff_event_class_clients')->insert($repeatingEventClients);

                                        /*$added_clientsMakeUpDeleteData = implode(',', $added_clientsMakeUpDeleteData);
                                    $this->copyDeletedClassClients($added_clientsMakeUpDeleteData, 1);
                                    DB::delete('delete from staff_event_class_clients where (secc_sec_id, secc_client_id, secc_if_make_up) in ('.$added_clientsMakeUpDeleteData.')');*/
                                    }
                                    if (count($updateClients)) {
                                        DB::table('staff_event_class_clients')->whereIn('secc_id', $updateClients)->update(array('secc_if_recur' => 1, 'updated_at' => createTimestamp()));
                                    }

                                    /* Update event repeat client id */
                                    if ($request->targetEvents == 'future' && $requestClient[$clientId]['ifRecur'] && $eventClass->sec_secr_id != 0) {
                                        $repeatClientIds   = [];
                                        $eventRepeatClient = $eventClass->repeat()->first();
                                        if (count($eventRepeatClient)) {
                                            $with_invoice = 0;
                                            $with_credit  = 0;

                                            // if($value['forceAdd'])
                                            //     $with_invoice = 1;

                                            if ($eventRepeatClient->secr_client_id != '') {
                                                $repeatClientIds       = json_decode($eventRepeatClient->secr_client_id, true);
                                                $repeatClientIds[$clientId] = array('with_invoice' => $with_invoice, 'with_credit' => $with_credit);
                                            } else {
                                                $repeatClientIds[$clientId] = array('with_invoice' => $with_invoice, 'with_credit' => $with_credit);
                                            }
                                            $eventRepeatClient->secr_client_id = json_encode($repeatClientIds);
                                            $eventRepeatClient->save();
                                        }
                                    }

                                }
                            }
                        }

                        //if(!$requestClient[$clientId]['ifRecur'])
                        if (!$attachClient['secc_if_recur'])
                        //$this->manageTeamSalesProcess($client, $eventClass->sec_id);
                        //$this->manageTeamSalesProcess($client, $eventClass->sec_id, 'Client rescheduled from class booking', 0, true);
                        {
                            $this->manageSessionSalesProcess($client);
                        }

                        $msg['status']  = 'added';
                        $msg['message'] = displayAlert('success|Client(s) has been rescheduled successfully.');
                        $msg['orgMessage'] = 'Client(s) has been rescheduled successfully.';
                    }
                }
            }
        }
        return json_encode($msg);
    }

    /**
     *Book team for sales process from calendar directly
     *
     * @param resource $event Event record
     * @param resource $client Client record
     *
     */
    protected function bookTeamFromCalendar($event, $client)
    {
        $salesProcessError = '';
        if ($client->consultation_date != null) {
            //Sales process has been consulted
            $eventDate        = new Carbon($event->sec_date);
            $consultationDate = new Carbon($client->consultation_date);
            if ($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))) {
                //Event booking date is not less than Consultation date and not greater than Consultation end date(Consultation date + 15 days)
                /*$saleProcessBenchmarkedDetails = calcSalesProcessRelatedStatus('benchmarked');
                if($client->sale_process_step >= $saleProcessBenchmarkedDetails['saleProcessStepNumb']){
                //Sale process has been benchmarked
                $saleProcessTeamedDetails = calcSalesProcessRelatedStatus('teamed');
                if($client->sale_process_step < $saleProcessTeamedDetails['saleProcessStepNumb']){
                //Sale process has not been teamed yet
                $clientOldSaleProcessStep = $client->sale_process_step;
                $data = $this->manageTeamSalesProcess($client);
                if(count($data)){
                $salesProcessHistory = ['clientId'=>$client->id, 'eventId'=>$event->sec_id, 'fromStep'=>$clientOldSaleProcessStep, 'toType'=>$data['toType'], 'toStep'=>$data['toStep']];
                $this->saveSalesProcess($salesProcessHistory);
                }
                }
                }*/
                //$this->manageTeamSalesProcess($client, $event->sec_id);
                //$this->manageTeamSalesProcess($client, $event->sec_id, 'Class booked from calendar', 0, true);
                $this->manageSessionSalesProcess($client);
            }
            /*else
        $salesProcessError = "Booking date doesn't fall in consultation date range.";*/
        }
        /*else
        $salesProcessError = 'Sales Process has not been consulted yet.';*/

        return $salesProcessError;
    }

    /**
     * Return histroy text according to action
     * @param
     * @return
     */
    protected function calcHistoryTextFromAction($action, $additional = "", $isClientRecure = "")
    {
        if ($action == 'add') {
            if ($isClientRecure && $isClientRecure == true) {
                return ' was added recurring to class.';
            } else {
                return ' was added to the class' . ($additional ? " $additional" : "") . '.';
            }

        } else {
            return ' was removed from the class' . ($additional ? " $additional" : "") . '.';
        }

    }

    /**
     * Delete Associated Classes
     * @param
     * @return
     */
    protected function delAssociatedClasses($data)
    {
        $data['eventType'] = 'class';
        $this->delAssociatedEvents($data);
    }

    /**
     * Halt Prev Related Events Reccur
     * @param
     * @return
     */
    protected function haltPrevRelatedEventsReccur($eventParentId, $eventId = 0)
    {
        /*if(!$eventId)
        $previousRelatedEvents = StaffEventClass::where('sec_parent_id', $eventParentId)->orWhere('sec_id', $eventParentId)->orderBy('sec_date', 'DESC')->get();
        else{
        $previousRelatedEvents = StaffEventClass::where(function($query) use ($eventParentId){
        $query->where('sec_parent_id', $eventParentId)->orWhere('sec_id', $eventParentId);
        })
        ->where('sec_id', '!=', $eventId)
        ->orderBy('sec_date', 'DESC')->get();
        }*/
        $previousRelatedEvents = $this->getBookingsFromChain($eventParentId, $eventId, 'class');

        if ($previousRelatedEvents->count()) {
            $latestEventDate = $previousRelatedEvents->first()->sec_date;

            StaffEventRepeat::ofClass()->where('ser_event_id', $eventParentId)->update(['ser_child_count' => $previousRelatedEvents->count() - 1]);

            $eventIds = $previousRelatedEvents->pluck('sec_id')->toArray();

            $repeatTable = (new StaffEventRepeat)->getTable();
            DB::table($repeatTable)->where('ser_event_type', 'App\StaffEventClass')->whereIn('ser_event_id', $eventIds)->update(['ser_repeat_end' => 'ON', 'ser_repeat_end_after_occur' => 0, 'ser_repeat_end_on_date' => $latestEventDate]);
        }
    }

    protected function getLinkedAreasId($eventClass)
    {
        $areas = $eventClass->areas;
        if ($areas->count()) {
            return $areas->pluck('la_id')->toArray();
        }

        return [];
    }

    protected function sortClients($clients)
    {
        $temp = $clients;
        uasort($temp, function ($a, $b) {
            return $a['order'] - $b['order'];
        });
        return $temp;
    }

    protected function confirmExisitingClients($data)
    {
        if (count($data['clients_to_auto_confirmId']) && $data['confirmed_clientsCount'] < $data['eventCapacity']) {
            $spaceAvail = $data['eventCapacity'] - $data['confirmed_clientsCount'];
            if ($spaceAvail < count($data['clients_to_auto_confirmId'])) {
                $data['clients_to_auto_confirmId'] = array_slice($data['clients_to_auto_confirmId'], 0, $spaceAvail);
            }

            DB::table('staff_event_class_clients')->where('secc_sec_id', $data['eventId'])->whereIn('secc_client_id', $data['clients_to_auto_confirmId'])->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
            //return $data['confirmed_clientsCount'] + count($data['clients_to_auto_confirmId']);
            return $data['clients_to_auto_confirmId'];
        }
        return [];
        //return $data['confirmed_clientsCount'];
    }

    protected function sendClientClassBookingEmail($action, $request, $clients)
    {
        return $this->sendClientEventBookingEmail($action, $request, $clients, 'class');
    }

    protected function linkEventclassResource($request, $eventId, $kase)
    {
        $request->eventId   = $eventId;
        $request->eventType = 'App\StaffEventClass';
        $request->kase      = $kase;
        $this->linkResources($request);
    }

    /**
     * Check if given booking is part of team in sales process
     *
     * @param int $id Booking record Id
     * @param string $date Booking date
     * @param Collection $client Client record whose sales process has to check
     *
     */
    protected function isBookingSalesProcBooking($id, $date, $client)
    {
        $isSalesProcEvent = false;
        
        if (!$client->trashed() && !$client->pivot->secc_if_recur && $client->pivot->secc_client_attendance != 'Did not show' && isClientInSalesProcess($client->consultation_date, $client->consul_exp_date) && $client->TeamEnabledCount) {
            //Client not deleted and Client is not recurring and Client was not absent and Client is still in sales process
            $eventDate        = new Carbon($date);
            $consultationDate = new Carbon($client->consultation_date);
           
            if ($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))) {
                //Event booking date is not less than Consultation date and not greater than Consultation end date(Consultation date + 15 days)
                $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('book_team');
                if ($this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)) {
                    //Sales process has been book benchmark already
                    $totalBookings = StaffEventClass::teamBookings($client->id, $client->consultation_date, 0, 'sec_id');
                   
                    if (count($totalBookings)) {
                        $totalBookings = array_slice($totalBookings, 0, $client->TeamEnabledCount);
                        if (in_array($id, $totalBookings)) {
                            $isSalesProcEvent = true;
                        }

                    }
                }
            }
        }
        return $isSalesProcEvent;
    }

    public function changeDate(Request $request)
    {
        $staffEvent                     = StaffEventClass::find($request->eventId);
        $staffEvent->sec_date           = $request->date;
        $staffEvent->sec_start_datetime = $staffEvent->sec_date . ' ' . $staffEvent->sec_time;

        $endDatetime                  = explode(' ', $staffEvent->sec_end_datetime);
        $staffEvent->sec_end_datetime = $staffEvent->sec_date . ' ' . $endDatetime[1];

        $staffEvent->save();
    }

    /**
     * Classes Marge
     * @param
     * @return
     **/
    public function classesMarge(Request $request)
    {
        $isError   = false;
        $timestamp = Carbon::now();
        $msg       = [];

        $fromEventId = (int) $request->fromEventId;
        $toEventId   = (int) $request->toEventId;

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-class')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $fromEventClass = StaffEventClass::with('clientsOldestFirst', 'clients','clas')->OfStaff(Auth::user()->account_id)->find($fromEventId);
                $toEventClass   = StaffEventClass::with('clientsOldestFirst', 'clients')->OfStaff(Auth::user()->account_id)->find($toEventId);
            } else {
                $fromEventClass = StaffEventClass::with('clientsOldestFirst', 'clients','clas')->OfBusiness()->find($fromEventId);
                $toEventClass   = StaffEventClass::with('clientsOldestFirst', 'clients')->OfBusiness()->find($toEventId);
            }
            if ($fromEventClass) {
                /*if($fromEventClass->sec_class_id != $toEventClass->sec_class_id){
                $isError = true;
                $msg['status'] = 'error';
                $msg['message'] = displayAlert('warning|Class could not be merged because select class is not match.');
                }*/

                if (count($fromEventClass->clientsOldestFirst)) {
                    $clientIds = array();
                    foreach ($fromEventClass->clientsOldestFirst as $client) {
                        $clientIds[] = $client->id;
                    }
                    $isClientBusy = $this->isClientBusy(['clientId' => $clientIds, 'startDatetime' => $toEventClass->sec_start_datetime, 'endDatetime' => $toEventClass->sec_end_datetime, 'eventId' => $fromEventId]);

                    if (count($isClientBusy)) {
                        $isError        = true;
                        $msg['status']  = 'error';
                        $msg['message'] = displayAlert('warning|Class could not be merged because some client busy at specific hours.');
                    }

                    $fromClient  = $fromEventClass->clients->count();
                    $toClient    = $toEventClass->clients->count();
                    $totalClient = $fromClient + $toClient;
                    if ($toEventClass->sec_capacity < $totalClient) {
                        $isError        = true;
                        $msg['status']  = 'error';
                        $msg['message'] = displayAlert('warning|This class has reached its capacity.');
                    }

                    if (!$isError) {
                        $staffEventClassClients = DB::table('staff_event_class_clients')->where('secc_sec_id', $fromEventId)->get();
                        $mergeDataArray         = array();
                        if (count($staffEventClassClients)) {
                            foreach ($staffEventClassClients as $staffEventClassClient) {
                                $mergeData = $staffEventClassClient;
                                unset($mergeData->secc_id);
                                $mergeData->secc_sec_id = $toEventId;
                                $mergeData->updated_at  = $timestamp;
                                $mergeDataArray[]       = (array) $mergeData;
                            }
                        }

                        if (DB::table('staff_event_class_clients')->insert($mergeDataArray)) {
                            DB::table('staff_event_class_clients')->where('secc_sec_id', $fromEventId)->delete();

                            //notes copy
                            if ($fromEventClass->sec_notes != '') {
                                if ($toEventClass->sec_notes != '') {
                                    $toEventClass->sec_notes = $this->notesFormating($toEventClass->sec_notes, $fromEventClass->sec_notes);
                                } else {
                                    $toEventClass->sec_notes = $fromEventClass->sec_notes;
                                }

                                $toEventClass->save();
                            }
                            $isError = false;
                        } else {
                            $isError = true;
                        }

                        if (!$isError) {

                            //update invoice
                            $invoices = InvoiceItems::where('inp_product_id', $fromEventId)->where('inp_type', 'class')->update(['inp_product_id' => $toEventId]);

                            // upadte history
                            // $staffEventHistories = StaffEventHistory::where('seh_event_id', $fromEventId)->where('seh_event_type', 'App\StaffEventClass')->update(['seh_event_id' => $toEventId]);
                            $origEventTimestamp    = strtotime($fromEventClass->sec_start_datetime);
                            $staffEventHistories = StaffEventHistory::where('seh_event_id', $fromEventId)->where('seh_event_type', 'App\StaffEventClass')->get();
                            foreach ($staffEventHistories as $eventHistory) {
                                $text = $eventHistory->seh_text;
                                $eventHistory->update([
                                    'seh_text' => $text.'(Migrated from '.$fromEventClass->clas->cl_name.' ' . date('l', $origEventTimestamp) . ' ' . date('d/m/y', $origEventTimestamp) . ' @' . date('H:i a', $origEventTimestamp) .' by ' . Auth::user()->name . ' ' . Auth::user()->last_name.' )',
                                    'seh_event_id' => $toEventId
                                ]);
                            }

                            //create new history for merge...
                            $this->ammendHistory(['text' => 'Class merge', 'event' => $toEventClass]);

                            $additionalHistoryText = '. Merged from '.$fromEventClass->clas->cl_name.' ' . date('l', $origEventTimestamp) . ' ' . date('d/m/y', $origEventTimestamp) . ' @' . date('H:i a', $origEventTimestamp) . ' by ' . Auth::user()->name . ' ' . Auth::user()->last_name;

                            // $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'add']);
                            $clients = Clients::whereIn('id',$clientIds)->get();
                            $historyText = $this->eventclassClientHistory(['clients' => $clients, 'action' => 'add', 'additional' => $additionalHistoryText]);

                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $toEventClass]);

                            // delete event class.
                            $fromEventClass->deleted_at = $timestamp;
                            $fromEventClass->save();

                            $msg['status']  = 'success';
                            $msg['message'] = displayAlert('success|Class has been merged successfully.');
                        }
                    }
                }
            }
        }

        return json_encode($msg);
    }

    /**
     * Notes formating
     * @param string 1, string 2
     * @return formating string
     **/
    protected function notesFormating($string1, $string2)
    {
        return ($string1 . "\r\n\r\n" . "-------------" . "\r\n" . $string2);
    }

    /**
     * Modify client & class detail in past
     * @param
     * @return
     */
    public function updatePastClass(Request $request)
    {
        $isError = false;
        $msg     = [];
        $isAttend = false;

        if (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff-event-class')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $eventClass = StaffEventClass::with(array('clients' => function ($query) use ($request) {
                    $query->whereIn('clients.id', $request->clientId);
                }))
                    ->OfStaff(Auth::user()->account_id)
                    ->find($request->eventId);
            } else {
                $eventClass = StaffEventClass::with(array('clients' => function ($query) use ($request) {
                    $query->whereIn('clients.id', $request->clientId);
                }))
                    ->OfBusiness()
                    ->find($request->eventId);
            }
            if ($eventClass) {
                foreach ($eventClass->clients as $client) {
                    $updateData = [];
                    if ($client->pivot->secc_client_attendance != $request->clientAttendance || $request->attendMark == 1) {
                        $oldAttendance                        = $client->pivot->secc_client_attendance;
                        $updateData['secc_client_attendance'] = $request->clientAttendance;
                        if ($request->clientIfMakeUp) {
                            $updateData['secc_epic_credit'] = 0;
                            //$updateData['secc_if_make_up'] = 1;
                            $updateData['deleted_at'] = createTimestamp();

                        }

                        $eventClass->clients()->updateExistingPivot($client->id, $updateData);

                        // Confirm Waiting Clients
                        if ($request->clientIfMakeUp || $request->attendMark == 1) {
                            $clients_to_auto_confirmId = [];
                            $confirmed_clientsCount    = 0;
                            foreach ($eventClass->clientsOldestFirst as $clientsOldestFirst) {
                                if ($clientsOldestFirst->id != $client->id) {
                                    if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                        $confirmed_clientsCount++;
                                    } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $eventClass->sec_capacity) {
                                        $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                        $confirmed_clientsCount++;
                                    }
                                }
                            }
                            if (count($clients_to_auto_confirmId)) {
                                DB::table('staff_event_class_clients')->where('secc_sec_id', $eventClass->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                            }
                        }
                        $notesId = 0;
                        if ($request->makeupNotes) {

                            $notesId = $this->createNotes($request->makeupNotes, $client->id, 'makeup', 'Added while generating EPIC Credit on class booking', 'epic credit raise.'); //A make up created for
                        }
                        if ($request->has('purpose') && $request->amount > 0) {
                            $startDatetime = dbDateToDateTimeString(new Carbon($eventClass->sec_start_datetime));
                            $notesLink     = 'EPIC Credit added for <a href="' . url('dashboard/calendar-new?mevid=' . $eventClass->sec_id . '&mevtype=class') . '">' . $startDatetime . '</a> class';
                            $this->updateEpicCredit($client->id, 0 - $request->amount, $request->purpose, $notesLink, $notesId);
                        }
                        $historyText = '';
                        if ($request->clientIfMakeUp) {
                            $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'remove']);
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
                        if ($request->clientAttendance == 'Attended') {
                            $historyText .= $client->firstname . ' ' . $client->lastname . ' was marked as attending the class|';
                            if ($client->pivot->secc_with_invoice) {
                                $existingInvoice = Invoice::join('invoice_items', 'inv_id', '=', 'inp_invoice_id')->select('inv_id')->where('inv_client_id', $client->pivot->secc_client_id)->where('inp_type', 'class')->where('inp_product_id', $eventClass->sec_id)->first();
                                if (!$existingInvoice) {
                                    $this->raiseAnInvoice($eventClass, $client->pivot->secc_client_id, $client->pivot->secc_epic_credit, $client->pivot->secc_reduce_rate);
                                }
                            }
                            $calendarData =  CalendarSetting::where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
                            if($client->sale_process_setts == null || $client->sale_process_setts == ''){
                                $client->sale_process_setts = $calendarData->sales_process_settings;
                                $this->salesProcSettingsUpdate($client->pivot->secc_client_id,$calendarData->sales_process_settings);
                                $client->refresh();
                                $client->sale_process_setts = $calendarData->sales_process_settings;
                            }
                            $clientStatus = json_decode($client->sale_process_setts,1);
                            if($client && in_array($client->account_status, ['Pre-Training']) || in_array($client->account_status, ['Pre-Benchmarking']) || in_array($client->account_status, ['Pre-Consultation'])){
                                $indivCount = 0;
                                $markAtten= 0;
                                $indiv = indivBookingSteps();
                                $indived = indivAttendSteps();
                                $teamCount = 0;
                                $team = teamBookingSteps();
                                $teamed = teamAttendSteps();

                                $stepDataSales = [];
                                $salesProgressStepData= SalesProcessProgress::where('spp_client_id',$client->pivot->secc_client_id)->get();
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
                                                $serviceDate =  DB::table('staff_event_single_services')->select('sess_date')->where('sess_client_id',$client->pivot->secc_client_id)->where('sales_step_number', $steps)->whereNull('deleted_at')->first();
                                                if($serviceDate)
								                $salesDate = dbDateToDateString($serviceDate->sess_date);
                                            }else if($steps == 6 || $steps == 7 || $steps == 8 || $steps == 9 || $steps == 10 ){
                                                $classId =  DB::table('staff_event_class_clients')->select('secc_sec_id')->where('secc_client_id',$client->pivot->secc_client_id)->where('sales_step_number', $steps)->whereNull('deleted_at')->first();
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
                           
                       
                             foreach($client->SalesSessionOrder as $order){
                                $value = explode('-', $order); 
                                if($attenStep ==5 || $attenStep == 3){
                                    $isAttend = true;  
                                }
                                if($markAtten== 1 && ($attenStep == 11 || $attenStep == 23 || $attenStep == 24 || $attenStep == 25 || $attenStep == 26)){
                                    $msg['salesAttendStep'] = $attenStep;
                                    $msg['salesProcessEvent'] = $bookStep;
                               
                                    $isAttend = true;
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

                                    // $insertData = array('spp_client_id'=>$client->pivot->secc_client_id,'spp_step_numb'=>$attenStep,'spp_comp_date'=>now(),'spp_booking_id'=>0,'spp_comp_manual'=>0,'created_at'=>now(),'updated_at'=>now());
                                   
                                    // SalesProcessProgress::insert($insertData);
                                }else if($markAtten== 1 && ($attenStep == 17 || $attenStep == 19 || $attenStep == 20 || $attenStep == 21 || $attenStep == 22)){
                                    $msg['salesAttendStep'] = $attenStep;
                                    $msg['salesProcessEvent'] = $bookStep;
                                    $isAttend = true;
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
                                    $attendNumb = $teamed[$teamCount];
                                    $stepNumb = $team[$teamCount];
                                    $teamCount++;
                                    if(!SalesProcessProgress::where('spp_client_id', $client->pivot->secc_client_id)->orderBy('spp_id','desc')->where('spp_step_numb',$attendNumb)->exists()){
                                        if(!SalesProcessProgress::where('spp_client_id', $client->pivot->secc_client_id)->orderBy('spp_id','desc')->where('spp_step_numb',$stepNumb)->exists()){
                                            $msg['salesProcessStepNumber'] = $stepNumb;
                                            $msg['salesProcessStepName'] = 'Book Team?';
                                            break;
                                        }
                                    }
                                    
                                 
                                   
                                }else if($value[0] == 'indiv'){
                                    $stepNumb = $indiv[$indivCount];
                                    $attendNumb = $indived[$indivCount];
                                    $indivCount++;
                                    
                                    if(!SalesProcessProgress::where('spp_client_id',$client->pivot->secc_client_id)->orderBy('spp_id','desc')->where('spp_step_numb',$stepNumb)->exists()){
                                        $msg['salesProcessStepNumber'] = $stepNumb;
                                        $msg['salesProcessStepName'] = 'Book Individual?';
                                        break;
                                    }
                                    
                                   
                                }
                             }
        
                            }
                        } else if ($request->clientAttendance == 'Did not show') {
                            $historyText .= $client->firstname . ' ' . $client->lastname . ' was marked as not attending the class|';
                        }

                        if ($historyText) {
                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);
                        }

                        //  $checkStep = [11,23,24,25,26,17,19,20,21,22];
                         if($arry[$client->pivot->sales_step_number] != null){
                            $step = $arry[$client->pivot->sales_step_number];
                         }
                        if (!$client->pivot->secc_if_recur && !in_array($step,$arry)) {
                            $this->manageSessionSalesProcess($client, '',$isAttend);
                        }else if(in_array($step,$arry) && $request->clientAttendance == 'Booked'){
                            
                            $this->deleteSalesProgress($step, $client->pivot->secc_client_id); 

                        }

                        $client->pivot->secc_client_attendance = $request->clientAttendance;
                        $msg['isSalesProcEvent'][$client->id]  = $this->isBookingSalesProcBooking($eventClass->sec_id, $eventClass->sec_date, $client);

                        $this->calcStaffEventPayment('class', $eventClass->sec_id, $request->clientAttendance);

                        //create invoice for next sibling event, if it is setup with invoice
                        if ($eventClass->sec_secr_id && $client->pivot->secc_with_invoice && $client->pivot->secc_if_recur) {
                            $nextClass       = StaffEventClass::whereDate('sec_date', '>', $eventClass->sec_date)->where('sec_secr_id', $eventClass->sec_secr_id)->orderBy('sec_date')->first();
                            $existingInvoice = Invoice::join('invoice_items', 'inv_id', '=', 'inp_invoice_id')->select('inv_id')->where('inv_client_id', $client->pivot->secc_client_id)->where('inp_type', 'class')->where('inp_product_id', $nextClass->sec_id)->first();
                            $amount          = $nextClass->sec_price; //$client->pivot->secc_reduce_rate ? $client->pivot->secc_reduce_rate :
                            if ($nextClass && !$existingInvoice && $amount) {
                                $this->raiseAnInvoice($nextClass, $client->pivot->secc_client_id, $client->pivot->secc_epic_credit);
                            }

                        }

                        $msg['status'] = 'added';
                    }
                }
            }
        }
        return json_encode($msg);
    }

    /** ----------------------------------------------------------- **/
    /* For clints event (only for result)
    /** ----------------------------------------------------------- **/

    /**
     * Signup client in class
     * @param request
     * @return response
     */
    public function signupInClass(Request $request)
    {
        $isError                   = false;
        $isLdc                     = 0;
        $isEpicInvoice             = 0;
        $ifEpicCashChecked         = 0;
        $forceAdd                  = true;
        $msg                       = [];
        $new_epic_cash_value_check = 0;

        $userId   = Auth::user()->id;
        $clientId = Auth::user()->account_id;
        $client   = Clients::OfBusiness()->find($clientId);

        if ($client && $client->account_status != 'Active' && $client->account_status != 'Contra') {
            $isError            = true;
            $msg['status']      = 'error';
            $msg['errorData'][] = array('notApproved' => displayAlert("warning| Your account is Unapproved."));
        }

        $eventClass = StaffEventClass::OfBusiness()->find($request->eventId);
        $membership = $this->satisfyMembershipRestrictions($clientId, ['event_type' => 'class', 'event_id' => $eventClass->sec_class_id, 'event_date' => $eventClass->sec_date]);

        // dd($membership);
        if (!$membership['satisfy']) {
            $isSatisfyLdc = $this->isSatisfyLdcRestriction($clientId,$request->eventId,'','class');           
             if(!$isSatisfyLdc){
                if (($request->has('isEpicCash') && $request->isEpicCash) || ($client->epic_credit_balance > 0)) {
                    if ($client->epic_credit_balance >= $eventClass->sec_price) {
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
        }

        $clientId   = Auth::user()->account_id;
        $eventId    = $request->eventId;
        $client     = Clients::OfBusiness()->find($clientId);
        $eventClass = StaffEventClass::OfBusiness()->find($request->eventId);

        if (count($eventClass)) {
            /* Check is this client busy  or client membership*/
            $isClientBusy = $this->isClientBusy(['eventId' => $request->eventId, 'clientId' => [$clientId], 'startDatetime' => $eventClass->sec_start_datetime, 'endDatetime' => $eventClass->sec_end_datetime], 'class');

            if (count($isClientBusy) || count($eventClass->isClientExistInEvent($clientId))) {
                $isError            = true;
                $msg['status']      = 'error';
                $msg['errorData'][] = array('clientBusy' => displayAlert("warning| You can not book class because you are busy at specified hours."));
            }

            if (!$isError) {
                $attachClient = [];
                if ($ifEpicCashChecked) {
                    $new_epic_cash_value_check = $client->epic_credit_balance - $eventClass->sec_price;
                    if (($new_epic_cash_value_check < 0)) {
                        $ifEpicCashChecked = 1;
                        $isEpicInvoice     = 1;

                        $attachClient['secc_epic_credit']         = 1;
                        $attachClient['secc_with_invoice']        = 1;
                        $attachClient['secc_event_log']           = 'Booked with epic credit and invoice';
                        $attachClient['secc_action_performed_by'] = getLoggedUserName();

                        # Create makeup
                        $classEventMakeup                       = new Makeup();
                        $classEventMakeup->makeup_user_id       = $userId;
                        $classEventMakeup->makeup_user_name     = (isset($client->firstname) ? $client->firstname : '') . ' ' . (isset($client->lastname) ? $client->lastname : '');
                        $classEventMakeup->makeup_client_id     = $clientId;
                        $classEventMakeup->makeup_notes_id      = 0;
                        $classEventMakeup->makeup_purpose       = 'class';
                        $classEventMakeup->makeup_session_count = 0;
                        $classEventMakeup->makeup_amount        = -($client->epic_credit_balance);
                        $classEventMakeup->makeup_extra         = 'EPIC Credit for <a href="' . url('/') . '/dashboard/calendar-new?mevid=' . $eventId . '&mevtype=class">' . date('D, d M Y H:i A', strtotime($eventClass->sec_start_datetime)) . '</a> class';
                        $classEventMakeup->makeup_total_amount  = 0.00;
                        $classEventMakeup->save();

                        $this->setEpicBalance($client->id);

                        $invResponse = $this->raiseAnInvoice($eventClass, $clientId, false, 0, '', abs($new_epic_cash_value_check));

                        /* Start: Invoice partially paid using epic credit*/
                        $paymentData['totalAmount']       = $eventClass->sec_price;
                        $paymentData['payAmount']         = $client->epic_credit_balance;
                        $paymentData['outStandingAmount'] = $eventClass->sec_price - $client->epic_credit_balance;
                        $paymentData['paymentType']       = 'EPIC Credit';
                        $isPaymentComplete                = $this->autoInvoicePayment($invResponse['invoiceId'], $paymentData);
                        /* End: Invoice partially paid using epic credit*/
                    } else {
                        $ifEpicCashChecked                        = 1;
                        $isEpicInvoice                            = 0;
                        $attachClient['secc_epic_credit']         = 1;
                        $attachClient['secc_with_invoice']        = 0;
                        $attachClient['secc_event_log']           = 'Booked with epic credit';
                        $attachClient['secc_action_performed_by'] = getLoggedUserName();

                        /* Raise invoice for class booked with epic credit.*/
                        $invResponse = $this->raiseAnInvoice($eventClass, $clientId, true, 0, '', $eventClass->sec_price);
                    }
                }

                if ($isEpicInvoice && $ifEpicCashChecked == 0) {
                    $invResponse = $this->raiseAnInvoice($eventClass, $client->id, false);
                    if ($invResponse['status'] == 'error') {
                        $isError        = true;
                        $msg['status']  = 'error';
                        $msg['message'] = 'Unable to create invoice for booking a class.';
                    }
                } else if ($membership['satisfy']) {
                    if(array_key_exists('type',$membership) && $membership['type'] == 'classExtra'){
                        $attachClient['secc_class_extra'] = 1;
                    }
                    $attachClient['secc_cmid']                = $membership['clientMembId'];
                    $attachClient['secc_event_log']           = 'Booked with membership';
                    $attachClient['secc_action_performed_by'] = getLoggedUserName();
                }else if($isSatisfyLdc){
                    $attachClient['secc_cmid']                = $membership['clientMembId'];
                    $attachClient['is_ldc']                   = 1;
                    $attachClient['secc_event_log']           = 'Booked with membership LDC';
                    $attachClient['secc_action_performed_by'] = getLoggedUserName();
                }

                if (!$isError) {
                    if ($eventClass->clients->count() <= $eventClass->sec_capacity) {
                        $attachClient['secc_client_status'] = 'Confirm';
                        if (isset($membership) && $membership['satisfy']) {
                            $attachClient['secc_cmid']                = $membership['clientMembId'];
                            $attachClient['secc_event_log']           = 'Booked with membership';
                            $attachClient['secc_action_performed_by'] = getLoggedUserName();
                        }else if($isSatisfyLdc){
                            $attachClient['secc_cmid']                = $membership['clientMembId'];
                            $attachClient['is_ldc']                   = 1;
                            $attachClient['secc_event_log']           = 'Booked with membership LDC';
                            $attachClient['secc_action_performed_by'] = getLoggedUserName();
                        } else if ($isEpicInvoice) {
                            $attachClient['secc_with_invoice']        = 1;
                            $attachClient['secc_cmid']                = 0;
                            $attachClient['secc_event_log']           = 'Booked with invoice';
                            $attachClient['secc_action_performed_by'] = getLoggedUserName();
                        }

                        $attachClient['secc_if_recur'] = 0;

                        // dd($attachClient);
                        if (count($attachClient)) {
                            $eventClass->clients()->attach($clientId, $attachClient);
                        }

                        /* Update client membership limit */
                        if (isset($membership) && $membership['satisfy']) {
                            
                            $attachClient['secc_epic_credit'] = 0;
                            $attachClient['secc_epic_credit'] = 0;
                            if(!array_key_exists('type',$membership) || (array_key_exists('type',$membership) && $membership['type'] != 'classExtra')){
                                   # Set info log
                            setInfoLog('Client membership limit updated on class booked with membership ', $clientId);
                            $this->updateClientMembershipLimit($clientId, [$eventClass->sec_date], ['type' => 'class', 'action' => 'add', 'eventId' => $eventClass->sec_class_id, 'limit_type' => $membership['limit_type']]);
                                
                            }
                        }

                        $additionalHistoryText = '';
                        if ($ifEpicCashChecked && $isEpicInvoice) {
                            $additionalHistoryText = ' with invoice and epic credit';
                        } else if ($ifEpicCashChecked) {
                            $additionalHistoryText = ' with epic credit';
                        } else if ($isEpicInvoice) {
                            $additionalHistoryText = ' with invoice';
                        } else if ($isLdc){
                            $additionalHistoryText = ' with LDC';
                        }

                        $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'add', 'additional' => $additionalHistoryText]);

                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass]);

                        $dataForEmail                     = new stdClass();
                        $dataForEmail->eventDateTimeEmail = dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $eventClass->sec_start_datetime));
                        $dataForEmail->modalLocArea       = $eventClass->areas->pluck('la_id')->toArray();
                        $dataForEmail->staffClass         = $eventClass->sec_class_id;
                        $dataForEmail->staff              = $eventClass->sec_staff_id;
                        $dataForEmail->bookType           = $request->bookType;
                        $alertHistoryText                 = $this->sendClientClassBookingEmail('confirm', $dataForEmail, [$client]);

                        if ($ifEpicCashChecked && $isEpicInvoice) {
                            $msg['status']  = 'added';
                            $msg['message'] = "Your class is booked with Epic Credit and Invoice.\n Epic Credit Used: $" . number_format($eventClass->sec_price - abs($new_epic_cash_value_check), 2) . "\nInvoice Amount: $" . number_format(abs($new_epic_cash_value_check), 2) . "\n You need to pay invoice to start the class.";
                        } else if ($ifEpicCashChecked) {
                            $msg['status']  = 'added';
                            $msg['message'] = "You have been booked successfully using epic credit \n Class Price: $" . number_format($eventClass->sec_price, 2) . "\n Remaining Epic Credit: $" . number_format($new_epic_cash_value_check, 2);
                        } else if ($isEpicInvoice) {
                            $msg['status']  = 'added';
                            $msg['message'] = "Your class is booked with invoice. \n You need to pay the invoice to start the class.";
                        }else if ($isLdc) {
                            $msg['status']  = 'added';
                            $msg['message'] = "Your class is booked with LDC.";
                        } else {
                            $msg['status']  = 'added';
                            $msg['message'] = 'You have been booked class successfully.';
                        }

                    } else {
                        $isError            = true;
                        $msg['status']      = 'error';
                        $msg['errorData'][] = array('clientBusyClassBook' => displayAlert("error| The class is full and so can not be booked."));
                    }
                }
            }
        }

        return json_encode($msg);
    }

    /**
     * Alert Before booking Class
     */
    public function alertForClassBooking(Request $request)
    {
        $isError                   = false;
        $isLdc                     = 0;
        $isEpicInvoice             = 0;
        $ifEpicCashChecked         = 0;
        $forceAdd                  = true;
        $msg                       = [];
        $new_epic_cash_value_check = 0;

        $userId   = Auth::user()->id;
        $clientId = Auth::user()->account_id;
        $client   = Clients::OfBusiness()->find($clientId);

        if ($client && $client->account_status != 'Active' && $client->account_status != 'Contra') {
            $msg['status']      = 'error';
            $msg['message']     = "Your account is Unapproved.";
            return json_encode($msg);
        }else{
            $eventClass = StaffEventClass::OfBusiness()->find($request->eventId);
            if ($eventClass->clients->count() <= $eventClass->sec_capacity) {
                $membership = $this->satisfyMembershipRestrictions($clientId, ['event_type' => 'class', 'event_id' => $eventClass->sec_class_id, 'event_date' => $eventClass->sec_date]);
                if (!$membership['satisfy']) {
                    $isSatisfyLdc = $this->isSatisfyLdcRestriction($clientId,$request->eventId,'','class');
                    if(!$isSatisfyLdc){
                        if (($request->has('isEpicCash') && $request->isEpicCash) || ($client->epic_credit_balance > 0)) {
                            if ($client->epic_credit_balance >= $eventClass->sec_price) {
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
                }

                $clientId   = Auth::user()->account_id;
                $eventId    = $request->eventId;
                $client     = Clients::OfBusiness()->find($clientId);
                $eventClass = StaffEventClass::OfBusiness()->find($request->eventId);

                if (count($eventClass)) {
                    /* Check is this client busy  or client membership*/
                    $isClientBusy = $this->isClientBusy(['eventId' => $request->eventId, 'clientId' => [$clientId], 'startDatetime' => $eventClass->sec_start_datetime, 'endDatetime' => $eventClass->sec_end_datetime], 'class');

                    if (count($isClientBusy) || count($eventClass->isClientExistInEvent($clientId))) {
                        $isError            = true;
                        $msg['status']      = 'error';
                        $msg['message']     = "You can not book class because you are busy at specified hours.";
                        return json_encode($msg);
                    }else{
                        if ($membership['satisfy']) {
                            $msg['status']      = 'ok';
                            $msg['message']     = "Your class will be Booked with membership";
                            /* End: Invoice partially paid using epic credit*/
                            return json_encode($msg);
                        }else if($isSatisfyLdc){
                            $msg['status']      = 'ok';
                            $msg['message']     = "Your class will be Booked with membership LDC";
                            /* End: Invoice partially paid using epic credit*/
                            return json_encode($msg);
                        }else{
                            if ($ifEpicCashChecked) {
                                $new_epic_cash_value_check = $client->epic_credit_balance - $eventClass->sec_price;
                                if (($new_epic_cash_value_check < 0)) {
                                    $ifEpicCashChecked = 1;
                                    $isEpicInvoice     = 1;
                                    $msg['status']      = 'ok';
                                    $msg['message']     = "Your class will be booked with Epic Credit and Invoice.\n Epic Credit will be Used: $" . number_format($eventClass->sec_price - abs($new_epic_cash_value_check), 2) . "\nInvoice Amount: $" . number_format(abs($new_epic_cash_value_check), 2) . "\n You need to pay invoice to start the class.";
                                    return json_encode($msg);
                                } else {
                                    $ifEpicCashChecked = 1;
                                    $isEpicInvoice = 0;
                                    $msg['status']      = 'ok';
                                    $msg['message']     = "Your class will be booked with Epic Credit \n Class Price: $" . number_format($eventClass->sec_price, 2) . "\n Remaining Epic Credit: $" . number_format($new_epic_cash_value_check, 2);
                                    return json_encode($msg);
                                }
                            }else{
                                $ifEpicCashChecked = 0;
                                $isEpicInvoice = 1;
                                $msg['status']      = 'ok';
                                $msg['message']     = "Your class will be booked with invoice. \nInvoice Amount: $" . number_format(abs($eventClass->sec_price), 2) . "\n You need to pay invoice to start the class.";
                                return json_encode($msg);
                            }
                        }
                    }
                }else{
                    $msg['status']      = 'error';
                    $msg['message']     = "The class may be deleted";
                    return json_encode($msg);
                }
            }else{
                $isError            = true;
                $msg['status']      = 'error';
                $msg['message']     = "The class is full and so can not be booked";
                return json_encode($msg);
            }
        }

        return json_encode($msg);
    }

    /**
     * Unlink client from class event
     * @param $request (eventId, isEpicCash, clientId)
     * @return rsponse
     */
    public function removeClintFromClass(Request $request)
    {
        $isBefore24Hour = false;
        $eventDateNow = Carbon::now();
        $isError = $isEpicCreated = false;
        $msg     = [];
        if (!$isError) {
            $event                   = StaffEventClass::OfBusiness()->find($request->eventId);
            $membership              = Clients::paidMembership($request->clientId);
            $eventClassDeletedClient = $event->clients()->where('secc_client_id', $request->clientId)->first();

            if (!$eventClassDeletedClient) {
                $msg['status']       = 'error';
                $msg['errorData'][]  = array('clientNotFount' => $request->clientId);
                $msg['unsatisyReas'] = 'client_not_found';
                $isError             = true;
                return json_encode($msg);
            }
            $checkTime = date("Y-m-d H:i:s", strtotime("-1440 minutes", strtotime($event->sec_start_datetime))) < $eventDateNow;
            if($checkTime){
             $isBefore24Hour = true;
            }
            # Get invoice details
            $invoice = Invoice::where('inv_client_id', $request->clientId)
                ->whereHas('invoiceitem', function ($query) use ($request) {
                    $query->where('inp_product_id', $request->eventId)
                        ->where('inp_type', 'class');
                })->first();

            if (count($event)) {
                $ifContinue = true;
                if($request->isLdc != 1){
                    $ifContinue = date("Y-m-d H:i:s", strtotime("-1440 minutes", strtotime($event->sec_start_datetime))) > $request->eventDateNow;
                }
                if ($ifContinue) {
                    if ($request->has('isEpicCash') && $request->isEpicCash == 'yes') {
                        if ($invoice && $eventClassDeletedClient->pivot->secc_with_invoice == 1) {
                            if ($eventClassDeletedClient->pivot->secc_epic_credit == 1) {
                                /* Calculate credit used in invoice if with both epic credit and invoice*/
                                $epicCreditUsed = InvoiceItems::where('inp_invoice_id', $invoice->inv_id)->sum('inp_paid_using_epic_credit');

                                if ($epicCreditUsed > 0) {
                                    $makeup                   = new Makeup;
                                    $makeup->makeup_client_id = $request->clientId;
                                    $makeup->makeup_amount    = $invoice->inv_status == 'Paid' ? $event->sec_price : $epicCreditUsed;
                                    $makeup->makeup_purpose   = 'class';
                                    $makeup->makeup_extra = 'EPIC Credit for <a href="https://epictrainer.com/dashboard/calendar-new?mevid=' . $event->sec_id . '&mevtype=class">' . $event->sec_start_datetime . '</a> class';
                                    $makeup->makeup_user_id   = $makeup->UserInformation['id'];
                                    $makeup->makeup_user_name = $makeup->UserInformation['name'];
                                    $makeup->save();

                                    $this->setEpicBalance($request->clientId);
                                    $isEpicCreated = true;
                                }

                            } else if ($invoice->inv_status == 'Paid') {
                                /* Calculate credit used in invoice if with only invoice*/
                                $makeup                   = new Makeup;
                                $makeup->makeup_client_id = $request->clientId;
                                $makeup->makeup_amount    = $invoice->inv_total;
                                $makeup->makeup_purpose   = 'class';
                                $makeup->makeup_extra = 'EPIC Credit for <a href="https://epictrainer.com/dashboard/calendar-new?mevid=' . $event->sec_id . '&mevtype=class">' . $event->sec_start_datetime . '</a> class';
                                $makeup->makeup_user_id   = $makeup->UserInformation['id'];
                                $makeup->makeup_user_name = $makeup->UserInformation['name'];
                                $makeup->save();

                                $this->setEpicBalance($request->clientId);
                                $isEpicCreated = true;
                            }

                        } else {
                            /* Calculate credit used in invoice with membership*/
                            $makeup                   = new Makeup;
                            $makeup->makeup_client_id = $request->clientId;
                            $staffEventClassClient = DB::table('staff_event_class_clients')->where('secc_sec_id', $request->eventId)->where('secc_client_id',$request->clientId)->whereNull('deleted_at')->first();
                            $clientMemb = Clients::paidMembership($request->clientId);
                            if($clientMemb->cm_number <= $clientMemb->cm_discount_dur || $clientMemb->cm_discount_dur == -1){
                                if($clientMemb->cm_disc_per_class_amnt == null){
                                    if($clientMemb->cm_per_clas_amnt == '' || $clientMemb->cm_per_clas_amnt == null || $clientMemb->cm_per_clas_amnt == '0.00'){
                                        $makeup->makeup_amount    = $event->sec_price;
                                            $this->updateMembershipClassPrice($clientMemb->id,  $makeup->makeup_amount );
                                        
                                    }else{
                                        $makeup->makeup_amount  = $clientMemb->cm_per_clas_amnt; 
                                    }
                                }else{
                                    $makeup->makeup_amount  = $clientMemb->cm_disc_per_class_amnt;
                                }
                            }else{
                                if($clientMemb->cm_per_clas_amnt == '' || $clientMemb->cm_per_clas_amnt == null || $clientMemb->cm_per_clas_amnt == '0.00'){
                                    if($clientMemb->cm_discounted_amount == '' || $clientMemb->cm_discounted_amount == '0.00' || $clientMemb->cm_discounted_amount == null){
                                        $makeup->makeup_amount    = $event->sec_price;
                                        $this->updateMembershipClassPrice($clientMemb->id,  $makeup->makeup_amount );
                                    }else{
                                        $makeup->makeup_amount = $this->perClassPricehg($clientMemb->cm_class_limit_length,$clientMemb->cvbcvcounted_amount);
                                    }
                                }else{
                                $makeup->makeup_amount    = $clientMemb->cm_per_clas_amnt;
                                }
                                
                            }
                            if($staffEventClassClient->secc_class_extra == 1){
                                $sessionData = json_decode($clientMemb->cm_session_limit, 1);
                                $staffEventClass = StaffEventClass::with('clas.cat')->where('sec_id',$staffEventClassClient->secc_sec_id)->first();
                                $catId = $staffEventClass->clas->cat->clcat_id;
                                if($sessionData[$catId]['discount_price_type'] == 'sessionUnitPrice' && $sessionData[$catId]['discount_type'] == 'fixed'){
                                    $makeup->makeup_amount  = $sessionData[$catId]['discount_amount'];
                                }else if($sessionData[$catId]['discount_price_type'] == 'sessionUnitPrice' && $sessionData[$catId]['discount_type'] == 'percent'){
                                    $makeup->makeup_amount  = $sessionData[$catId]['sessionDiscountPerData'];
                                }else{
                                    $makeup->makeup_amount = $sessionData[$catId]['mem_unit_price'];
                                }
                              }
                            $makeup->makeup_purpose   = 'class';
                            $makeup->makeup_extra = 'EPIC Credit for <a href="https://epictrainer.com/dashboard/calendar-new?mevid=' . $event->sec_id . '&mevtype=class">' . $event->sec_start_datetime . '</a> class';
                            $makeup->makeup_user_id   = $makeup->UserInformation['id'];
                            $makeup->makeup_user_name = $makeup->UserInformation['name'];
                            $makeup->save();

                            $this->setEpicBalance($request->clientId);
                            $isEpicCreated = true;
                        }
                    }

                    $logMessage = '';
                    $additionalText = '';
                    if ($eventClassDeletedClient->pivot->secc_cmid != 0 && $request->isEpicCash != 'yes') {
                        if($request->isLdc)
                            $logMessage = 'Deleted with membership LDC';
                        else{
                            if(!$isBefore24Hour){
                                $logMessage = 'Deleted with membership';
                                $additionalText = 'with membership';
                            }else{
                                $logMessage = 'Deleted withOut membership';
                                $additionalText = '';
                            }
                        }
                    } else if ($eventClassDeletedClient->pivot->secc_epic_credit || ($eventClassDeletedClient->pivot->secc_cmid != 0 && $request->isEpicCash == 'yes')) {
                        $logMessage = 'Deleted with epic credit';
                        $additionalText = 'with epic credit';
                    } else if ($eventClassDeletedClient->pivot->secc_with_invoice) {
                        $logMessage = 'Deleted with invoice';
                    }

                    $historyText = $this->eventclassClientHistory(['clients' => [$eventClassDeletedClient], 'action' => 'remove','additional' => $additionalText]);
                    if ($historyText) {
                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $event]);
                    }

                    DB::table('staff_event_class_clients')
                        ->where('secc_sec_id', $request['eventId'])
                        ->where('secc_client_id', $request['clientId'])
                        ->update([
                            'deleted_at'               => createTimestamp(),
                            'secc_action_performed_by' => getLoggedUserName(),
                            'secc_event_log'           => $logMessage,
                        ]);

                    /* Update Client Membership Limit */
                    if ($eventClassDeletedClient->pivot->secc_cmid != 0 && $request->isEpicCash != 'yes') {
                        if($request->isLdc != 1){
                            # Set info log
                            if(!$isBefore24Hour){
                            setInfoLog('Client membership limit updated on class cancelled ', $request->clientId);

                            $this->updateClientMembershipLimit([$request->clientId], [$event->sec_date], ['type' => 'class', 'action' => 'remove', 'eventId' => $event->sec_class_id, 'limit_type' => $membership ? $membership->cm_class_limit_type : '']);
                            }
                        }
                    }

                    $invoiceDeleted = false;
                    if ($eventClassDeletedClient->pivot->secc_with_invoice == 1 && $eventClassDeletedClient->pivot->secc_epic_credit == 0 && $invoice && $invoice->inv_status == 'Unpaid') {
                        $this->deleteEventInvoice($event->sec_id, $request->clientId, 'class', $isEpicCreated);
                        $invoiceDeleted = true;
                    }

                    /* Start: Delete linked invoice(Class booked with invoice or split payment) */
                    if ($eventClassDeletedClient->pivot->secc_with_invoice == 1 && $eventClassDeletedClient->pivot->secc_epic_credit == 1 && $invoice && $invoice->inv_status == 'Unpaid' && ($request->has('isEpicCash') && $request->isEpicCash == 'yes')) {
                        $this->deleteEventInvoice($event->sec_id, $request->clientId, 'class', $isEpicCreated);
                        $invoiceDeleted = true;
                    }

                    /* End: Delete linked invoice(Class booked with invoice or split payment) */

                    // dd($eventClassDeletedClient->pivot->toArray());
                    /* Start:Delete invoice which are generated from class booked with epic credit */
                    if ($invoice && $invoice->inv_status == 'Paid' && ($request->has('isEpicCash') && $request->isEpicCash == 'yes')) {
                        $this->deleteEventInvoice($event->sec_id, $request->clientId, 'class', $isEpicCreated);
                        $invoiceDeleted = true;
                    }

                    /* End:Delete invoice which are generated from class booked with epic credit */
                    if($request->isLdc)
                        $respMssg = 'LDC Class has been cancelled';
                    else
                        $respMssg = 'Class has been cancelled';
                    if($request->has('isEpicCash') && $request->isEpicCash == 'yes'){
                        $respMssg = "Class has been cancelled.\nYour Epic Credit has been credited with $".$makeup->makeup_amount;
                    }
                    if($invoiceDeleted){
                        $respMssg = $respMssg."\nSession Invoice has been deleted.";
                    }
                    $msg['status']  = 'deleted';
                    $msg['message'] = $respMssg;
                }
            }
        }
        return json_encode($msg);
    }

    /**
     * Check if clients are available in future recure classes 
     * @param Integer $eventId 
     * @return JSON Object
     */
    public function checkClientsPresentInFutureRecureClasses($eventId) {
        $response  = [];
        $response['client_present'] = false;
        $response['success'] = true;
        $eventClass = StaffEventClass::where('sec_id', $eventId)->whereNull('deleted_at')->first();

        if($eventClass) {
            # Get recurred class events
            $associatedEvents = StaffEventClass::where('sec_secr_id', $eventClass->sec_secr_id)->whereDate('sec_date', '>=', $eventClass->sec_date)->whereNull('deleted_at')->get();

            if($associatedEvents) {
                foreach ($associatedEvents as $event) {
                    if($event->clients()->count() > 0) 
                        $response['client_present'] = true;

                }
            }
        }

        return json_encode($response);
    }

    public function perClassPrice($length, $amnt)
    {
        
        $price = ($amnt * 12)/52;
        $returnData = sprintf('%0.2f',$price/$length);
        return $returnData;
    }

    public function updateMembershipClassPrice($id, $amount){
        ClientMember::where('id',$id)->update(['cm_per_clas_amnt'=>$amount]);
    }

    public function updateSalesStepData($eventId,$clientId,$salesProcessStepNumb)
    {
        $isError = false;
        $msg     = [];

       

        if (!$isError) {
            if (isUserType(['Staff'])) {
                $eventClass = StaffEventClass::OfStaff(Auth::user()->account_id)
                    ->find($eventId);
            } else {
                $eventClass = StaffEventClass::OfBusiness()->find($eventId);
            }
          
            if ($eventClass) {
                $client = Clients::findClient($clientId);
                if ($client) {
                    $salesProcessRelatedStatus = calcSalesProcessRelatedStatus((int) $salesProcessStepNumb);
                    // dd( $salesProcessRelatedStatus);
                    //if($client->account_status == $salesProcessRelatedStatus['clientPrevStatus']){
                   
                    if (isClientInSalesProcess($client->consultation_date, $client->consul_exp_date) && /*statusMatchSalesStatus($client->account_status, $salesProcessRelatedStatus['clientPrevStatus'])*/$this->isStepEnabled($salesProcessStepNumb, $client->SaleProcessEnabledSteps) && $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)) {

                      
                    
                        if (!$isError) {
                          
                            $clientOldSaleProcessStep  = $client->sale_process_step;
                            $client->sale_process_step = $salesProcessStepNumb;
                            $client->save();

                            $this->manageSessionSalesProcess($client);
                           
                            $msg['salesProcessDate'] = dbDateToDateString($eventClass->EventDateCarbon);
                            $msg['changeStatus']     = $this->getStatusForbackend($client->account_status, true) . '|' . $client->account_status;
                            $msg['stepCompleted']    = $salesProcessStepNumb;
                        }
                    }
                }
            }
        }
        return json_encode($msg);
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
