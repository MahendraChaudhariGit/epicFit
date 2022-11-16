<?php
namespace App\Http\Controllers\Result\SignupClass;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use \stdClass;
use App\StaffEventBusy;
use App\Parq;
use Session;
use App\StaffEventSingleService;
use App\StaffEventClass;
use App\Staff;
use App\Clients;
use Auth;
use App\Clas;
use DB;
use App\LocationArea;
use Carbon\Carbon;
use App\Http\Traits\Result\ClientEventsTrait;
use App\Http\Traits\StaffEventTrait;

class ClassForSignupController extends Controller{
    use ClientEventsTrait, StaffEventTrait;

    /**
     * Fatch all class for signup according to fillter
     * @param Request
     * @return event
     */
    public function all(Request $request){
        $evnts = [];
        $index = 0;
        $clientId = Auth::user()->account_id;
        if($request->eventType=='signupClass'){
            $checkifmakeup = Clients::find(Auth::user()->account_id);
            $membership = Clients::paidMembership($clientId);
            if(!empty($membership->cm_classes)){
                $member_classes = array_keys(json_decode($membership->cm_classes,true));
            }
            $evnts[$index]['ifClientMakeupEligible'] = 0;
            $now = Carbon::now()->toDateTimeString();

            if($request->insertRepeatUpto){
                $classes = StaffEventClass::has('staff')->with('clients')->whereHas('clasWithTrashed',function($q){
                    $q->where('cl_book_online', 1);
                })->OfBusiness()->DatedBetween($request->currentDate, $request->maxDate)->where('sec_start_datetime', '>', $now)->get();  
            }
            if(count($classes)){
                foreach($classes as $class){
                    if($class->sec_capacity > count($class->clients)){
                        $staffName = Staff::find($class->sec_staff_id);
                        $evnts[$index]['staffName']=$staffName->first_name." ".$staffName->last_name;
                        $evnts[$index]['type'] = 'classSignup';
                        $evnts[$index]['id'] = $class->sec_id;
                        $evnts[$index]['date'] = $class->sec_date;
                        $evnts[$index]['startDatetime'] = $class->sec_start_datetime;
                        $evnts[$index]['endDatetime'] = $class->sec_end_datetime;
                        $evnts[$index]['title'] = $class->clasWithTrashed->cl_name;
                        $evnts[$index]['logo'] = isset($class->clasWithTrashed->cl_logo) && $class->clasWithTrashed->cl_logo != ''?asset('uploads/'.$class->clasWithTrashed->cl_logo):asset('profiles/noimage.gif');
                        $evnts[$index]['price'] = $class->sec_price;
                        $evnts[$index]['color'] = $class->clasWithTrashed->cl_colour;
                        $evnts[$index]['isStaffDeleted'] = $class->staffWithTrashed->trashed();
                        $evnts[$index]['capacity'] = $class->sec_capacity;
                        if($class->sec_secr_id != 0)
                            $evnts[$index]['isRepeating'] = 1;
                        else
                            $evnts[$index]['isRepeating'] = 0;
                            
                        $evnts[$index]['notes'] = $class->sec_notes;
                        $i = 0;
                        foreach($class->clients as $client){
                            $evnts[$index]['clients'][$i]['name'] = $client->firstname.' '.$client->lastname;
                            $i++;
                        }
                        $evnts[$index]['clientsCount'] = $class->clients->count();
                        $index++; 
                    }
                }
            }           
        }
        return json_encode($evnts);
    }


}
