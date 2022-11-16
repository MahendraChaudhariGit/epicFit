<?php
namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Services\Access\Traits\ConfirmUsers;
use App\Services\Access\Traits\UseSocialite;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Repositories\Frontend\User\UserContract;
use App\Services\Access\Traits\AuthenticatesAndRegistersUsers;

use Auth;
use App\Http\Traits\StaffTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\StaffEventTrait;
use App\Http\Traits\LocationAreaTrait;
use Illuminate\Http\Request;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\CalendarSettingTrait;
use App\Http\Traits\ClosedDateTrait;

class AuthController extends Controller{
    use AuthenticatesAndRegistersUsers, ConfirmUsers, ThrottlesLogins, UseSocialite, StaffTrait, HelperTrait, StaffEventTrait, LocationAreaTrait, StaffEventsTrait, CalendarSettingTrait, ClosedDateTrait;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    //protected $redirectTo = '/dashboard/calendar'; //administration/dashboard   

    /**
     * Where to redirect users after they logout
     *
     * @var string
     */
    protected $redirectAfterLogout = 'login';
    //protected $redirectAfterLogout = '/';

    public function __construct(UserContract $user){
        $this->user = $user;
    }

    public function show(){
        if(isUserType(['Staff'])){    
            $overViewData = $this->getOverviewData(Auth::user()->account_id);
            if(count($overViewData)){
                $staff = $overViewData['staff'];
                //$countries = $overViewData['countries'];

                $editFormData = $this->getEditFormData(['staff' => $staff]);
                if(count($editFormData)){
                    $businessId = $editFormData['businessId'];
                    $serv = $editFormData['serv'];
                    $clses = $editFormData['clses'];
                    $permTyp = $editFormData['permTyp'];
                    $country = $editFormData['country'];
                    $states = $editFormData['states'];
                    $time_zone = $editFormData['time_zone'];
                    $staffServices = $editFormData['staffServices'];
                    $staffClasses = $editFormData['staffClasses'];
                    $sessionRole =$editFormData['sessionrole'];
                    $staffSessionRole =$editFormData['staffSessionRole'];
                    $commissionRole =$editFormData['commissionrole'];
                    $staffCommissionRole =$editFormData['staffCommissionRole'];
                    $commissionSource =$editFormData['commissionsource'];
                    $staffCommissionSource =$editFormData['staffCommissionSource'];
                    $commissionCategory =$editFormData['commissioncategory'];
                    $staffCommissionCategory =$editFormData['staffCommissionCategory'];
                    $staffSessionServices=$editFormData['staffSessionServices'];
                    $staffSessionCategory=$editFormData['staffSessionCategory'];
                    $staffAttendeeArr =[];
                        foreach ($editFormData['staffAttendeeArr'] as $attendee) {
                          $staffAttendeeArr[]=array('sa_id' =>$attendee->id,'sa_staff_id' =>$attendee->sa_staff_id, 'sa_type' =>$attendee->sa_type, 'sa_per_session_attendees' =>$attendee->sa_per_session_attendees, 'sa_per_session_attendeeto' =>$attendee->sa_per_session_attendeeto, 'sa_per_session_price' =>$attendee->sa_per_session_price,'per_session_tier' =>$attendee->per_session_tier, 'per_session_tierto' =>$attendee->per_session_tierto, 'per_session_tierprice' =>$attendee->per_session_tierprice, 'sa_attendee_order' =>$attendee->sa_attendee_order);
                        }

                      //if($serv->count() > 0)
                        $sessionStaffService =array_intersect_key($serv, array_flip($staffServices));
                       // if($clses->count() > 0)
                        $sessionStaffClass =array_intersect_key($clses, array_flip($staffClasses));    

                    /* start: Delete auto-expire draft appointments */
                    $this->deleteExpiringAspirantsEvents();
                    /* end: Delete auto-expire draft appointments */


                    /*$this->deleteExpiringAspirantsEvents();

                    $this->callNeverEndEventRepeats();

                    $modalLocsAreas = $eventRepeatIntervalOpt = [];
                    $pastEvents = $latestPastEvent = $futureEvents = $oldestFutureEvent = collect();

                    $data = $this->locAreasForEvents();
                    $modalLocsAreas = $data['locsAreas'];
                    $eventRepeatIntervalOpt = $this->prepareEventRecurDdOpt();

                    $pastAppointments = $staff->pastAppointments;
                    $pastClasses = $staff->pastClasses;
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

                    //dd($pastClasses);
                    if($pastEvents->count()){
                        $latestPastEvent = $pastEvents->filter(function($pastEvent){
                            $model = class_basename($pastEvent);
                            return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null /*&& $pastEvent->pivot->secc_client_status != 'Waiting' && (!$pastEvent->pivot->secc_if_make_up || $pastEvent->pivot->secc_if_make_up && $pastEvent->pivot->secc_client_attendance == 'Did not show')*) || ($model == 'StaffEvent' && $pastEvent->deleted_at == null && $pastEvent->se_booking_status == 'Confirmed');
                        })->first();
                    }


                    $futureAppointments = $staff->futureAppointments;
                    $futureClasses = $staff->futureClasses;
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

                    if($futureEvents->count()){
                        $oldestFutureEvent = $futureEvents->filter(function($pastEvent){
                            $model = class_basename($pastEvent);
                            return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null /*&& $pastEvent->pivot->secc_client_status != 'Waiting' && !$pastEvent->pivot->secc_if_make_up*) || ($model == 'StaffEvent' && $pastEvent->deleted_at == null && $pastEvent->se_booking_status == 'Confirmed' && $pastEvent->se_booking_status_confirm == 'Not started');
                        })->first();
                    }
                    //dd($latestPastEvent);*/
                    $eventsListData = $this->eventsListForOverview($staff);
                    $pastEvents = $eventsListData['pastEvents'];
                    $latestPastEvent = $eventsListData['latestPastEvent'];
                    $futureEvents = $eventsListData['futureEvents'];
                    $oldestFutureEvent = $eventsListData['oldestFutureEvent'];
                    $modalLocsAreas = $eventsListData['modalLocsAreas'];
                    $eventRepeatIntervalOpt = $eventsListData['eventRepeatIntervalOpt'];  

                    //$reasons = $this->getCancelReasons();
                    $calendSettings = $this->getCalendSettings();
                    $calendarSettingVal = $calendSettings['settings'];
                    $reasons = $calendSettings['cancelReasons'];


                    return view('frontend.auth.profile', compact('staff'/*, 'countries'*/, 'businessId', 'serv', 'clses', 'permTyp', 'country', 'states', 'time_zone', 'staffServices', 'staffClasses', 'pastEvents', 'latestPastEvent', 'futureEvents', 'oldestFutureEvent', 'modalLocsAreas', 'eventRepeatIntervalOpt', 'sessionStaffService','sessionStaffClass','sessionRole','staffSessionRole','commissionRole','staffCommissionRole','staffCommissionSource','commissionSource','commissionCategory','staffCommissionCategory','staffSessionServices','staffSessionCategory','staffAttendeeArr', 'reasons', 'calendarSettingVal'));
                }
                //return view('frontend.auth.profile', compact('staff', 'countries'));
            }
        }
        else if(isUserType(['Admin'])){
            $user = Auth::user();
            return view('frontend.auth.profile', compact('user'));
        }
    }

    public function update(Request $request){
        $isError = false;
        $msg = [];

        $user = Auth::user();
        if($user){
            if(!$this->ifEmailAvailable(['email' => $request->adminEmail, 'entity' => 'user', 'id' => $user->id])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }

            if(!$isError){
                $user->name = $request->adminFname;
                $user->last_name = $request->adminLname;
                $user->email = $request->adminEmail;
                if($request->adminNewPwd)
                    $user->password = bcrypt($request->adminNewPwd);

                $user->save();
                $msg['status'] = 'updated';
            }
        }

        return json_encode($msg);
    }

    public function updateField(Request $request){                   
        $user = Auth::user();
        if($user){
            if($request->entityProperty == 'email'){
                if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'user', 'id' => $user->id])){
                    return json_encode([
                        'status' => 'emailExistError',
                        'message' => 'This email is already in use'
                    ]);
                }

                $value = $request->email;
                $user->email = $value;
                $user->save();
            }

            return json_encode([
                'status' => 'updated',
                'value' => $value
            ]);
        }
    }
}