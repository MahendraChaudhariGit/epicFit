<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductSize;
use App\Clients;
use App\Staff;
use App\LocationArea;
use Carbon\Carbon;
use App\Category;
use App\ProductReviews;
use DB;
use Auth;
use Session;
use App\StaffEvent;
use App\StaffEventClass;
use App\Clas;
use App\Http\Traits\EncryptDecryptTrait;
use App\User;
use App\Result\ApiUser;
use App\Makeup;
use App\Http\Traits\ApiTrait;
use App\Http\Traits\StaffEventHistoryTrait;
use App\Http\Traits\StaffEventClassTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\StaffEventsTrait;
use App\SalesToolsInvoice;
use \stdClass;
use App\Invoice;
use App\InvoiceItems;
use App\Business;
use App\StaffEventSingleService;

class EpicfitStudiosAPIController extends Controller
{
    use  EncryptDecryptTrait, StaffEventsTrait, StaffEventHistoryTrait, HelperTrait/*, StaffEventClassTrait*/;
    
    //Set cookie and public url
    private $cookieSlug = 'client';
    //public $CRM_URL='http://192.168.225.50/crm/public/';
    public $CRM_URL = 'http://crm.epictrainer.com/';
    
    /*
    API: Get business access pass
    type:get
    parameter
    1:accessPass ex. oky
    
    http://192.168.0.50/api/get_access_pass?&business_web_url=alpha1
    */
    public function get_business_access_pass(Request $request) {
        $webUrl = \Request::get('business_web_url');
        $businessDetails = Business::where('cp_web_url', $webUrl)->first();
        
        if(empty($businessDetails)) {
            $businessDetails = Business::where('cp_web_url', 'alpha1')->first();
        }
        return json_encode(['status' => true,'access_pass' => $businessDetails->api_key]);
    }
    
    /*
    API: list all class under given business
    type:get
    parameter
    1:accessPass ex. oky
    2:class_date ex. 2017-07-16
    3:staff_id (optional) ex. 2
    4:area_id (optional) ex. 2
    http://192.168.0.50/api/classes?class_date=2017-07-22&staff_id=&area_id=&class_id=&accessPass=oky
    
    */
    
    public function list_classes(Request $request) {
        // dd($request->all());
        $businessData = Business::whereApiKey($request->accessPass)->first();
        if($businessData){
            $businessId = $businessData->id;
            $where = array();
            $whereCL = array();
            $showOnlyAvailableClasses = $request->has('only_availble_classes')? $request['only_availble_classes'] : false;
            
            if (!$request->has('class_date')) {
                return json_encode(['code' => '401', 'message' => 'Please provide Date For Class Search.']);
            }
            
            $date = \Request::get('class_date');
            $where['sec_date'] = $date;
            $where['sec_business_id'] = $businessId;
            
            if ($request->has('staff_id') && $request->get('staff_id') != '') {
                $staff_id = \Request::get('staff_id');
                $where['sec_staff_id'] = $staff_id;
            }
            if ($request->has('area_id') && $request->get('area_id') != '') {
                $area_id = \Request::get('area_id');
                $where['sec_area_id'] = $area_id;
            }
            if ($request->has('class_id') && $request->get('class_id') != '') {
                $class_id = \Request::get('class_id');
                $whereCL['sec_class_id'] = $class_id;
                
            }
            $whereCL['cl_book_online'] = 1;

            $classes = StaffEventClass::has('areas')->has('staff')
            // ->with('clasWithTrashed','clients', 'locationAndAreas')
            ->with('clasWithTrashed','clientsWithTrashed', 'locationAndAreasWithTrashed', 'staffWithTrashed')
            ->where($where)
            ->whereHas('clasWithTrashed',function($q) use($whereCL){ $q->where($whereCL); })
            ->orderBy('sec_start_datetime', 'ASC')
            ->get();
        }
        $class_list = array();
        if (!empty($classes) && $classes && count($classes)) {
            $index = 0;
            if($showOnlyAvailableClasses) {
                foreach ($classes as $class) {
                    $area_array = array();
                    if ($class->sec_capacity > count($class->clients)) {
                        $staffName = Staff::find($class->sec_staff_id);
                        if($class && count($class->locationAndAreas)) {
                            foreach ($class->locationAndAreas as $val) {
                                $area_array[][$val->la_id] = $val->la_name;
                            }
                        }
                        $class_list[$index]['class_start'] = isset($class->sec_start_datetime) ? $class->sec_start_datetime : '';
                        $class_list[$index]['class_end'] = isset($class->sec_end_datetime ) ? $class->sec_end_datetime : '';
                        $class_list[$index]['area'] = $area_array;
                        $class_list[$index]['location_id'] = isset($class->locationAndAreasWithTrashed) ? $class->locationAndAreasWithTrashed[0]->location->id : '';
                        $class_list[$index]['location_name'] = isset($class->locationAndAreasWithTrashed[0]->location->location_training_area) ? $class->locationAndAreasWithTrashed[0]->location->location_training_area : '';
                        $class_list[$index]['staffName'] = $staffName->first_name . " " . $staffName->last_name;
                        $class_list[$index]['id'] = $class->sec_id;
                        $class_list[$index]['title'] = $class->clasWithTrashed->cl_name;
                        $class_list[$index]['price'] = $class->sec_price;
                        
                        
                        $index++;
                    }
                }
                
            } else {
                $staffName = Staff::find($class->sec_staff_id);
                if($class && count($class->locationAndAreas)) {
                    foreach ($class->locationAndAreas as $val) {
                        $area_array[][$val->la_id] = $val->la_name;
                    }
                }
                $class_list[$index]['class_start'] = isset($class->sec_start_datetime) ? $class->sec_start_datetime : '';
                $class_list[$index]['class_end'] = isset($class->sec_end_datetime ) ? $class->sec_end_datetime : '';
                $class_list[$index]['area'] = $area_array;
                $class_list[$index]['location_id'] = isset($class->locationAndAreasWithTrashed) ? $class->locationAndAreasWithTrashed[0]->location->id : '';
                $class_list[$index]['location_name'] = isset($class->locationAndAreasWithTrashed[0]->location->location_training_area) ? $class->locationAndAreasWithTrashed[0]->location->location_training_area : '';
                $class_list[$index]['staffName'] = $staffName->first_name . " " . $staffName->last_name;
                $class_list[$index]['id'] = $class->sec_id;
                $class_list[$index]['title'] = $class->clasWithTrashed->cl_name;
                $class_list[$index]['price'] = $class->sec_price;
                
                
                $index++;
            }
            
        } else {
            return json_encode(['code' => '401', 'message' => 'No Classes for queried values.']);
        }
        return json_encode($class_list);
    }
    
    /*
    API: list all staff and location area under given business
    type:get
    parameter
    1:accessPass ex. oky
    
    http://192.168.0.50/api/loc_area_staff?&accessPass=oky
    */
    public function load_loc_area_staff(Request $request) {
        $businessId = \Request::get('businessId');
        // $locationArea = LocationArea::OfBusiness($businessId)->get();
        // $locationArea = LocationArea::all();
        // $locArea_array = array();
        
        // if (count($locationArea)) {
            //     foreach ($locationArea as $val) {
                //         $locArea_array[] = array('id' => $val->la_id, 'loc_area' => $val->location_training_area . "-" . $val->la_name);
                //     }
                // }

        $staffs = Staff::OfBusiness($businessId)->get();
                // $staffs = Staff::all();
        $staff_array = array();
        if (count($staffs)) {
            foreach ($staffs as $val) {
                $staff_array[] = array('id' => $val->id, 'staffName' => $val->first_name . " " . $val->last_name);
            }
        }

        $classes = Clas::OfBusiness($businessId)->get();
                // $classes = Clas::all();
        $class_array = array();
        if (count($classes)) {
            foreach ($classes as $val) {
                $class_array[] = array('id' => $val->cl_id, 'className' => $val->cl_name);
            }
        }

        $final_result = array();
                // $final_result['location_area'] = $locArea_array;
        $final_result['staff'] = $staff_array;
        $final_result['class'] = $class_array;

                // if ((count($locArea_array) == 0) && (count($staff_array) == 0) && (count($class_array) == 0)) {
        if ((count($staff_array) == 0) && (count($class_array) == 0)) {
            return json_encode(['code' => '401', 'message' => 'No results for queried values.']);
        }
                    //echo "<pre>";print_r($final_result);exit;
        return json_encode($final_result);
    }


                /*  API: Authenticate Shop user 
                type: post
                parameter
                1: accessPass ex - oky
                3: username
                4: pass
                url = http://192.168.225.50/api/login_api_user?accessPass=oky&username=NN9OnztcA9zL4iZMV1CMDBzJJEqQcTWkTJHKw7pfCKk&pass=NN9OnztcA9zL4iZMV1CMDBzJJEqQcTWkTJHKw7pfCKk;
                */
                public function login_api_user(Request $request){
                    $response = array();
                    $isError = true;
                    $businessId = \Request::get('businessId');
                    $user = \Request::get('username');
                    $pass = \Request::get('pass');
                    // $username = $this->encryptDecryptText($user, 'dencrypt');
                    // $password = $this->encryptDecryptText($pass, 'dencrypt');
                    $username =  $user;
                    $password = $pass;
                    
                    if(Auth::attempt(['email' => $username, 'password' => $password,'business_id'=>$businessId,'account_type'=>'Client'])){
                        $user = Auth::user();
                        $id = $user->id;
                        $accountId = $user->account_id;
                        $data = ApiUser::where('user_id', '=', $id)->first();
                        if (count($data)) {
                            $response['token'] = $data->token;
                            $isError = false; 
                        }
                        else{
                            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$^*()?";
                            $token = substr( str_shuffle( $chars ), 0, 20 );
                            $timestamp = Carbon::now();
                            if(ApiUser::insert(['user_id'=>$id,'user_account_id'=>$accountId,'token'=>$token,'created_at'=>$timestamp,'updated_at'=>$timestamp])){
                                $isError = false;
                                $response['token'] = $token;
                            }
                        }
                        $response['fname'] = $user->name;
                        $response['lname'] = $user->last_name;
                        $response['url'] = url($this->CRM_URL.'uploads/');
                        $response['success'] = 1 ;
                        if($user->profile_picture != '')
                            $response['image'] = 'thumb_'.$user->profile_picture;
                        else
                            $response['image'] = ''; 
                    }
                    
                    if(!$isError)
                        return json_encode($response);
                    else
                        return json_encode(['code' => '401', 'message' => 'User id and password does not match.']);
                }
                
                /**
                * API: class details 
                * check epic credit also
                * type:get
                * @param
                * 1:accessPass ex. oky
                * 2:classId ex. 1
                * 3:token ex. xyz
                * @return class details
                http://192.168.225.50/api/class_detail?accessPass=oky&token=0VUHRcEoLSw6&1vPz3@Z&classId=4607;
                **/
                public function class_detail(Request $request) {
                    $response = array();
                    $epicCredit = 0;
                    
                    if($request->has('token'))
                        $token = $request['token'];
                    else if($request->has('token_split'))
                        $token = str_replace(',', '#', $request['token_split']);
                    else 
                        $token = '';
                    
                    $accountId = ApiUser::where('token',$token)
                    ->pluck('user_account_id')
                    ->first();
                    
                    if($accountId){
                        if($request->has('classId')){
                            $cls_id = \Request::get('classId');
                            $class = StaffEventClass::where('sec_id',$cls_id)
                            // ->with('clasWithTrashed','clients','locationAndAreas')
                            ->with('clasWithTrashed','clientsWithTrashed', 'locationAndAreasWithTrashed', 'staffWithTrashed')
                            ->first();
                            
                            if(count($class)){
                                $clientData = Clients::where('id',$accountId)->first();
                                if(count($clientData))
                                    $epicCredit = $clientData->epic_credit_balance;
                                
                                $staffName = Staff::find($class->sec_staff_id);
                                
                                foreach ($class->locationAndAreasWithTrashed as $val) {
                                    $area_array[][$val->la_id] = $val->la_name;
                                }
                                
                                $response['class_start'] = dbDateToDateString(new Carbon($class->sec_start_datetime));   
                                $response['class_end'] = dbDateToDateString(new Carbon($class->sec_end_datetime));
                                $time = new Carbon($class->sec_time);
                                $response['time'] = $time->format('h:i A');
                                $response['duration'] = $class->sec_duration;
                                $response['capacity'] = $class->sec_capacity;
                                $response['area'] = count($area_array) ? $area_array : [];
                                $response['location_id'] = isset($class->locationAndAreasWithTrashed) ? $class->locationAndAreasWithTrashed[0]->location->id : '';
                                $response['location_name'] = isset($class->locationAndAreasWithTrashed) ? $class->locationAndAreasWithTrashed[0]->location->location_training_area : '';
                                $response['staffName'] = $staffName->first_name . " " . $staffName->last_name;
                                $response['title'] = $class->clasWithTrashed->cl_name;
                                $response['price'] = $class->sec_price;
                                $response['success'] = 1;
                                if($epicCredit >= $class->sec_price)
                                    $response['isCredit'] = 'true';
                                else
                                    $response['isCredit'] = 'false';
                            }
                        }
                    }
                    if(count($response))
                        return json_encode($response);
                    else
                        return json_encode(['code' => '401', 'message' => 'No class detail available.']); 
                }
                
                /**
                * API: class Booked 
                * booking class throught API
                * type:get
                * @param
                * 1:accessPass ex. oky
                * 2:classId ex. 1
                * 3:token ex. xyz
                * @return booked message and error message
                http://192.168.225.50/api/class_booked?accessPass=oky&token=LSJy2M3gXC(Pw0c$pliG&classId=5956;
                **/
                public function class_booked(Request $request) {
                    $isEpicInvoice = $ifEpicCashChecked = 0;
                    $msg = [];
                    $isError = false;
                    $businessId = \Request::get('businessId');
                    $eventId = \Request::get('classId');
                    $new_epic_cash_value_check = 0;

                    if($request->has('token'))
                        $token = $request['token'];
                    else if($request->has('token_split'))
                        $token = str_replace(',', '#', $request['token_split']);
                    else 
                        $token = '';
                    
                    $useecash = \Request::get('useec');
                    $clientId = ApiUser::where('token', $token)->pluck('user_account_id')->first();
                    $userId = ApiUser::where('token', $token)->pluck('user_id')->first();

                    if(!$clientId){
                        $isError = true;
                        return json_encode(['status' => 'error', 'message' => 'Invalid token.']);
                    }

                    $client = Clients::where('business_id', $businessId)->where('id', $clientId)->first();

                    if(!count($client)){
                        $isError = true;
                        return json_encode(['status' => 'error','message' =>'Client not found.']);
                    }

                    // if($client && $client->account_status != 'Active' && $client->account_status != 'Active Lead')
                    if($client && $client->account_status != 'Active' && $client->account_status != 'Contra') {
                        $isError = true;
                        $msg['status'] = 'error';
                        $msg['errorData'][] = array('notActive' => displayAlert("warning| Your account is not active."));
                    }

                    $eventClass = StaffEventClass::where('sec_business_id',$businessId)->where('sec_id',$eventId)->first();

                    // dd($eventClass->toArray());
                    if(count($eventClass)) {

                        $currDateTime = setLocalToBusinessTimeZone(Carbon::now(),'', $businessId)->toDateTimeString();
                        $currDate = date_format(setLocalToBusinessTimeZone(Carbon::now(),'', $businessId), 'Y-m-d');

                        if($eventClass->sec_date <= $currDate){
                            $isError = true;
                            $msg['status'] = 'error';
                            $msg['message'] = "You can book only future class.";

                            return json_encode($msg);
                        }
                        // dd('kjh');
                        $membership = $this->satisfyMembershipRestrictions($clientId, ['event_type'=>'class','event_id'=>$eventClass->sec_class_id, 'event_date'=>$eventClass->sec_date]);
                        // dd("ssssdsd");
                        // dd($membership);
                        if(!$membership['satisfy']) {
                            if($client->epic_credit_balance > 0){
                                if($client->epic_credit_balance >= $eventClass->sec_price) {
                                    $ifEpicCashChecked = 1;
                                    $isEpicInvoice = 0;
                                } else {
                                    $ifEpicCashChecked = 1;
                                    $isEpicInvoice = 1;
                                }
                            } else {
                                $ifEpicCashChecked = 0;
                                $isEpicInvoice = 1;
                            }

                        }
                        /* Check is this client busy  or client membership*/
                        // dd('$isClientBusy');
                        $isClientBusy = $this->isClientBusy(['eventId' => $eventId, 'clientId' => $client->id, 'startDatetime' => $eventClass->sec_start_datetime, 'endDatetime' => $eventClass->sec_end_datetime, 'classId' => $eventClass->sec_class_id], 'class');
                        if($isClientBusy || count($eventClass->isClientExistInEvent($client->id))){
                            $isError = true;
                            $msg['status'] = 'error';
                            $msg['message'] = "You can not book class because you are busy at specified hours.";
                        }
                        
                        if(!$isError){
                            // dd('kjhg');
                            $attachClient = [];
                            if($ifEpicCashChecked){
                                $new_epic_cash_value_check = $client->epic_credit_balance - $eventClass->sec_price;

                                if(($new_epic_cash_value_check < 0)){
                                    $ifEpicCashChecked = 1;
                                    $isEpicInvoice = 1;

                                    $attachClient['secc_with_invoice'] = 1;
                                    $attachClient['secc_epic_credit'] = 1;
                                    $attachClient['secc_event_log'] = 'Booked with epic credit and invoice';
                                    $attachClient['secc_action_performed_by'] = getLoggedUserName();
                                    
                                    # Create makeup
                                    $classEventMakeup = new Makeup();
                                    $classEventMakeup->makeup_user_id = $userId;
                                    $classEventMakeup->makeup_user_name = (isset($client->firstname) ? $client->firstname : '').' '.(isset($client->lastname) ? $client->lastname : '');
                                    $classEventMakeup->makeup_client_id = $clientId;
                                    $classEventMakeup->makeup_notes_id = 0;
                                    $classEventMakeup->makeup_purpose = 'class';
                                    $classEventMakeup->makeup_session_count = 0;
                                    $classEventMakeup->makeup_amount = -($client->epic_credit_balance);
                                    $classEventMakeup->makeup_extra = 'EPIC Credit for <a href="http://localhost:8000/dashboard/calendar-new?mevid='.$eventId.'&mevtype=class">'.date('D, d M Y H:i A', strtotime($eventClass->sec_start_datetime)).'</a> class';
                                    $classEventMakeup->makeup_total_amount = 0.00;
                                    $classEventMakeup->save();

                                    $this->setEpicBalance($client->id);

                                    # Raise invoice for remaining amount
                                    $invResponse = $this->raiseAnInvoice($eventClass, $client->id, false, 0, $businessId, abs($new_epic_cash_value_check));

                                    /* Start: Invoice partially paid using epic credit*/
                                    $paymentData['totalAmount'] = $eventClass->sec_price;
                                    $paymentData['payAmount'] = $client->epic_credit_balance;
                                    $paymentData['outStandingAmount'] = $eventClass->sec_price - $client->epic_credit_balance;
                                    $paymentData['paymentType'] = 'EPIC Credit';
                                    $isPaymentComplete = $this->autoInvoicePayment($invResponse['invoiceId'], $paymentData);
                                    /* End: Invoice partially paid using epic credit*/
                             } else {
                                $ifEpicCashChecked = 1;
                                $attachClient['secc_epic_credit'] = 1;
                                $attachClient['secc_with_invoice'] = 0;
                                $attachClient['secc_event_log'] = 'Booked with epic credit';
                                $attachClient['secc_action_performed_by'] = getLoggedUserName();

                               # Raise invoice for remaining amount
                                $invResponse = $this->raiseAnInvoice($eventClass, $client->id, true, 0, $businessId, $eventClass->sec_price, 'epicfitStudios');
                            }
                        }

                        if($isEpicInvoice && $ifEpicCashChecked == 0){
                            $invResponse = $this->raiseAnInvoice($eventClass, $client->id, false, 0, $businessId);
                            if($invResponse['status'] == 'error') {
                             $isError = true;
                             $msg['status'] = 'error';
                             $msg['message'] = 'Unable to create invoice for class booking.'; 
                         }
                     }

                     if(!$isError){
                        if($eventClass->clients->count() <= $eventClass->sec_capacity){
                            $attachClient['secc_client_status'] = 'Confirm';
                            if($membership['satisfy']) {
                                $attachClient['secc_cmid'] = $membership['clientMembId'];
                                $attachClient['secc_event_log'] = 'Booked with membership';
                                $attachClient['secc_action_performed_by'] = getLoggedUserName(); 
                            } else if($isEpicInvoice) {
                                $attachClient['secc_with_invoice'] = 1;
                                $attachClient['secc_cmid'] = 0;
                                $attachClient['secc_event_log'] = 'Booked with invoice';
                                $attachClient['secc_action_performed_by'] = getLoggedUserName(); 
                            }

                            $attachClients['secc_if_recur']  = 0;
                            if(count($attachClient))
                                $eventClass->clients()->attach($client->id, $attachClient);

                                /* Update client membership limit */
                            if(isset($membership) && $membership['satisfy']) {
                                $attachClient['secc_epic_credit'] = 0;
                                $attachClient['secc_with_invoice'] = 0;

                                # Set info log
                                setInfoLog('EPICFIT STUDIOS: Client membership limit updated on class booked with membership',  $client->id);

                                $this->updateClientMembershipLimit($client->id,[$eventClass->sec_date],['type'=>'class','action'=>'add','eventId'=>$eventClass->sec_class_id, 'limit_type'=>$membership['limit_type']]);
                            }

                            $additionalHistoryText = '';
                            if($ifEpicCashChecked && $isEpicInvoice) 
                                $$additionalHistoryText = 'with invoice and epic credit';
                            else if($ifEpicCashChecked) 
                             $additionalHistoryText = 'with epic credit';
                             else if($isEpicInvoice) 
                                $additionalHistoryText = 'with invoice';


                        $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'add', 'additional' => $additionalHistoryText]);

                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $eventClass, 'name' => $client->firstname.' '.$client->lastname ]);

                        $dataForEmail = new stdClass();
                        $dataForEmail->eventDateTimeEmail = dbDateToDateTimeString(Carbon::createFromFormat('Y-m-d H:i:s', $eventClass->sec_start_datetime));
                        $dataForEmail->modalLocArea = $eventClass->areas->pluck('la_id')->toArray();
                        $dataForEmail->staffClass = $eventClass->sec_class_id;
                        $dataForEmail->staff = $eventClass->sec_staff_id;
                        $dataForEmail->bookType='Test';
                        $alertHistoryText = $this->sendClientClassBookingEmail('confirm', $dataForEmail, [$client], $businessId);

                        if($ifEpicCashChecked && $isEpicInvoice) {
                            $msg['status'] = 'added';
                            $msg['message'] = "Your class is booked with Epic Credit and Invoice.<br> Epic Credit Used: $".number_format($eventClass->sec_price - abs($new_epic_cash_value_check), 2)."<br>Invoice Amount: $".number_format(abs($new_epic_cash_value_check), 2) ."<br> You need to pay invoice to start the class.";
                        } else if($ifEpicCashChecked) {
                            $msg['status'] = 'added';
                            $msg['message'] = "You have been booked successfully using Epic Credit <br> Class Price: $".number_format($eventClass->sec_price, 2)."<br> Remaining Epic Credit: $".number_format($new_epic_cash_value_check, 2);
                        } else if($isEpicInvoice) {
                            $msg['status'] = 'added';
                            $msg['message'] = "Your class is booked with invoice. <br> You need to pay the invoice to start the class.";
                        } else {
                            $msg['status'] = 'added';
                            $msg['message'] = 'You have been booked class successfully.'; 
                        } 
                    }
                    else{
                        $isError = true;
                        $msg['status'] = 'error';
                        $msg['message'] = "The class is full and so can not be booked."; 
                    }
                }
            }
        }
            // dd($msg);
        return json_encode($msg);

    }

    public function getLinkedAreasId($eventClass){
        $areas = $eventClass->areas;  
        if($areas->count())
            return $areas->pluck('la_id')->toArray();
        return [];
    }

    public function sendClientClassBookingEmail($action, $request, $clients, $businessId){
        return $this->sendClientEventBookingEmail($action, $request, $clients, 'class', $businessId);
    }

    public function isBookingSalesProcBooking($id, $date, $client){
        $isSalesProcEvent = false;
        if(!$client->trashed() && !$client->pivot->secc_if_recur && $client->pivot->secc_client_attendance != 'Did not show' && isClientInSalesProcess($client->consultation_date) && $client->TeamEnabledCount){ 
            $eventDate = new Carbon($date);
            $consultationDate = new Carbon($client->consultation_date); 
            if($eventDate->gte($consultationDate) && $eventDate->lt($consultationDate->addDays(15))){ 
                $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('book_team');
                if($this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)){ 
                    $totalBookings = StaffEventClass::teamBookings($client->id, $client->consultation_date, 0, 'sec_id');
                    if(count($totalBookings)){
                        $totalBookings = array_slice($totalBookings,0,$client->TeamEnabledCount);
                        if(in_array($id, $totalBookings))
                            $isSalesProcEvent = true;
                    }
                }
            }
        }
        return $isSalesProcEvent;
    }

    public function markClass_clientAsMakeUpCreated($makeUpClientsId){
        if(count($makeUpClientsId)){
            $classClientRecs = DB::select(DB::raw("(SELECT classClientRec.* FROM (SELECT secc_id, secc_client_id FROM staff_event_class_clients WHERE secc_client_id in (".implode(',', $makeUpClientsId).") AND secc_if_make_up = 1 AND secc_if_make_up_created = 0 ORDER BY secc_sec_id LIMIT 18446744073709551615) as classClientRec GROUP BY classClientRec.secc_client_id) "));

            $doneMakeUpOfClients = [];
            if(count($classClientRecs)){
                $makeUpEventsId = [];
                foreach($classClientRecs as $classClientRec){
                    $makeUpEventsId[] = $classClientRec->secc_id;
                    $doneMakeUpOfClients[] = $classClientRec->secc_client_id;
                }
                DB::table('staff_event_class_clients')->whereIn('secc_id', $makeUpEventsId)->update(['secc_if_make_up_created' => 1, 'updated_at' => createTimestamp()]);
            }

            $decMakeUpCountOfClients = [];
            if(count($doneMakeUpOfClients))
                $decMakeUpCountOfClients = array_diff($makeUpClientsId, $doneMakeUpOfClients);
            else
                $decMakeUpCountOfClients = $makeUpClientsId;

            if(count($decMakeUpCountOfClients))
                Clients::whereIn('id', $decMakeUpCountOfClients)->decrement('makeup_session_count');
        }
    }

    public function deleteAdded_clientsMakeUpFromSameEvent($eventId, $clientsId){
        $this->copyDeletedClassClients([$eventId, $clientsId], 1);
        DB::table('staff_event_class_clients')->where('secc_sec_id', $eventId)->whereIn('secc_client_id', $clientsId)->where('secc_if_make_up', 1)->delete();
    }


    public function copyDeletedClassClients($colsVal, $makeup = false){
        if(is_array($colsVal)){
            $where = "secc_sec_id = $colsVal[0] and secc_client_id in (".implode(',', $colsVal[1]).")";
            if($makeup)
                $where .= " and secc_if_make_up = 1";
        }
        else{
            $where = "(secc_sec_id, secc_client_id";
            if($makeup)
                $where .= ", secc_if_make_up";
            $where .= ") in ($colsVal)";
        }

        DB::statement("INSERT INTO deleted_event_class_clients (`secc_id`, `secc_sec_id`, `secc_client_id`, `secc_notes`, `secc_reduce_rate_session`, `secc_if_recur`, `secc_if_make_up`, `secc_if_make_up_created`, `secc_is_make_up_client`, `secc_client_attendance`, `secc_client_status`, `created_at`, `updated_at`, `deleted_at`) SELECT `secc_id`, `secc_sec_id`, `secc_client_id`, `secc_notes`, `secc_reduce_rate_session`, `secc_if_recur`, `secc_if_make_up`, `secc_if_make_up_created`, `secc_is_make_up_client`, `secc_client_attendance`, `secc_client_status`, `created_at`, `updated_at`, `deleted_at` FROM staff_event_class_clients where $where");
    }


    public function getItemDescData($eventClass){
        $returnData=[];
        if($eventClass->count()){
            $staffData = $eventClass->staff;
            if(count($staffData))
                $staffName = $staffData->first_name.' '.$staffData->last_name;
            else
                $staffName = '';
            $classname= Clas::where('cl_id',$eventClass->sec_class_id)->pluck('cl_name')->first();
            foreach ($eventClass->locationAndAreasWithTrashed as $area) {
                $returnData['locationId'] = $area->locationWithTrashed->id;
                $locationName = $area->locationWithTrashed->location_training_area;
            }
            $returnData['itemDesc'] = $classname.'(Class) with '. $staffName .' on '. date('D, d M Y',strtotime($eventClass->sec_date));

        }
        return $returnData;  
    }

                    /**
                    * API: Invoice for product 
                    * type:get
                    * @param
                    * 1:accessPass ex. oky
                    * 2:product. 
                    * 3:token ex. xyz
                    * @return generate message and error message
                    http://192.168.225.50/api/product_invoice_genrate?accessPass=oky&token=PU3($zjSc!7evm^sg2)K&productdata={"138":"4","143":"3"};
                    **/
                    public function product_invoice_genrate(Request $request){
                        $isError = true;
                        $timestamp = Carbon::now();
                        $businessid = \Request::get('businessId');
                        $token = \Request::get('token');
                        $clientId = ApiUser::where('token',$token)->pluck('user_account_id')->first();
                        if(!$clientId){
                            $isError = true;
                            return json_encode(['code' => '401', 'message' => 'Invalid token.']);
                        }
                        
                        $productData = json_decode(\Request::get('productdata'), true);
                        ksort($productData);
                        if(!count($productData)){
                            $isError = true;
                            return json_encode(['code' => '401', 'message' => 'Invalid token.']);
                        }
                        else{
                            $productIds = array();
                            $productQuant = array();
                            foreach ($productData as $key => $value) {
                                $productIds[] = $key; 
                                $productQuant[] = $value;    
                            }
                            if(count($productIds))
                                $products = Product::select('id','name','sale_price','tax','salesTax','cost_price')->where('business_id', $businessid)->whereIn('id',$productIds)->orderBy('id','asc')->get();
                            
                            if($products->count()){
                                $insertedInvoice = array();
                                $insertedItem = array();
                                $itemData = array();
                                $total_amount = 0;
                                $total_tax_amount = 0;
                                $salestoolsinvoiceObj = SalesToolsInvoice::where('sti_business_id',$businessid)->first();
                                $invoiceNo = $salestoolsinvoiceObj->sti_next_invoice_number;
                                foreach ($products as $product) {
                                    $taxArray = $this->getTaxData($businessid, $salestoolsinvoiceObj, $product->salesTax, $product->sale_price);
                                    
                                    /* For invoice table  total amount and tax*/
                                    $total_amount += round(($taxArray['totalWithTax'] * $productData[$product->id]), 2);
                                    $total_tax_amount += round(($taxArray['taxValue'] * $productData[$product->id]), 2);
                                    
                                    /* For invoice item table */
                                    $itemData['inp_item_desc'] = $product->name;
                                    $itemData['inp_staff_id'] = 0;
                                    $itemData['inp_price'] = round($taxArray['unitPrice'], 2);
                                    $itemData['inp_quantity'] = $productData[$product->id];
                                    $itemData['inp_tax'] =$taxArray['taxLabel'];
                                    $itemData['inp_total'] = round(($taxArray['totalWithTax'] * $productData[$product->id]), 2);
                                    $itemData['inp_type'] = 'product';
                                    $itemData['inp_product_id'] = $product->id;
                                    $itemData['inp_tax_type'] = $taxArray['taxtype'];
                                    $itemData['created_at'] = $timestamp;
                                    $itemData['updated_at'] = $timestamp;
                                    
                                    $insertedItem[] = $itemData;
                                }
                                
                                /* For invoice table */
                                $insertedInvoice['inv_invoice_date'] = Carbon::now()->toDateString();
                                $insertedInvoice['inv_due_date'] = Carbon::now()->toDateString();
                                $insertedInvoice['inv_client_id'] = $clientId;
                                $insertedInvoice['inv_business_id'] = $businessid;
                                $insertedInvoice['inv_notes'] = '';
                                $insertedInvoice['inv_ref'] = '';
                                $insertedInvoice['inv_area_id'] = 0;
                                $insertedInvoice['inv_total'] = $total_amount;
                                $insertedInvoice['inv_incl_tax'] = $total_tax_amount;
                                $insertedInvoice['inv_status'] = 'Unpaid';
                                $insertedInvoice['inv_appointment_status'] = '';
                                $insertedInvoice['inv_invoice_no'] = $invoiceNo;
                                $insertedInvoice['created_at'] = $timestamp;
                                $insertedInvoice['updated_at'] = $timestamp;
                                
                                $invoiceNo++;
                                $salestoolsinvoiceObj->sti_next_invoice_number = $invoiceNo;
                                $salestoolsinvoiceObj->save();
                                
                                /* Save invoice */
                                if(count($insertedInvoice))
                                    $invInsertId = Invoice::insertGetId($insertedInvoice);
                                
                                if(count($insertedItem) && $invInsertId){
                                    $insertedData = array();
                                    foreach ($insertedItem as $data)
                                        $insertedData[] = array_merge($data, array('inp_invoice_id'=>$invInsertId));
                                    
                                    /* Save invoice Item */
                                    if(InvoiceItems::insert($insertedData))
                                        $isError = false;  
                                }
                            }
                        }
                        
                        if(!$isError)
                            return json_encode(['code' => '200', 'message' => 'Invoice generated.']);   
                        else
                            return json_encode(['code' => '401', 'message' => 'No data for queried values.']);
                        
                    }
                    
                    /*
                    API: list all products under given business with filters
                    type:get
                    parameter
                    1:accessPass ex. oky
                    2:featured ex. 1
                    3:onsale ex. 1
                    4:limit ex. 1
                    
                    http://192.168.0.50/api/products?accessPass=oky&featured=1&onsale=1&best_seller=1&limit=2
                    */
                    public function list_products(Request $request) {
                        $businessId = \Request::get('businessId');
                        $where = array();
                        $order_field = 'id';
                        $limit = 10000;
                        if ($request->has('featured') && ($request->get('featured') == 1)) {
                            $featured = \Request::get('featured');
                            $where['featured'] = $featured;
                        }
                        if ($request->has('onsale') && ($request->get('onsale') == 1)) {
                            $onsale = \Request::get('onsale');
                            $where['sale_on'] = $onsale;
                        }
                        if ($request->has('best_seller') && ($request->get('best_seller') == 1)) {
                            $order_field = 'sold_unit';
                        }
                        if ($request->has('limit')) {
                            $limit = \Request::get('limit');
                        }
                        
                        //DB::enableQueryLog();
                        $allProducts = Product::with('categories','reviews')->where('business_id',$businessId)->where($where)->orderBy($order_field, 'DESC')->limit($limit)->get();
                        //dd(DB::getQueryLog());
                        $products=[];

                        $product_list = array();
                        if(count($allProducts)) {
                            $index = 0;
                            foreach ($allProducts as $product) {
                                    //dd($product);
                                $catName=[];
                                foreach ($product->categories as $key => $value) {
                                    $catName[]=$value['cat_name'];
                                }



                                $product_list[$index]['id'] = $product['id'];
                                $product_list[$index]['name'] = $product['name'];
                                $product_list[$index]['cat'] = $catName;
                                $product_list[$index]['url'] = url($this->CRM_URL.'uploads/');
                                $product_list[$index]['img'] = $product['logo'];
                                $product_list[$index]['s_price'] = $product['sale_price'];
                                $product_list[$index]['c_price'] = $product['cost_price'];
                                $product_list[$index]['v_count'] = $product['view_count'];
                                $product_list[$index]['sold_unit'] = $product['sold_unit'];
                                $product_list[$index]['sale_on'] = $product['sale_on'];
                                $product_list[$index]['v_count'] = $product['view_count'];

                                $index++;
                            }
                        } else {
                            return json_encode(['code' => '401', 'message' => 'No Products for queried values.']);
                        }
                        return json_encode($product_list);
                    }

                            /*
                            API: list all products under given business with filters in sigle request
                            type:get
                            parameter
                            1:accessPass ex. oky
                            2:ftr     featured=1
                            3:sale    onsale=1
                            4:bs      best_seller=1
                            5:tr      top_rated=1
                            6:na      new_Arrivals=1
                            7:rw      reviews=1
                            8:cat     categories=1
                            8:c_id     category id =1
                            8:c_lim     category limit=1
                            8:c_ftr     category wise featured=1
                            8:f_cat     category wise featured=1
                            
                            http://192.168.225.50/api/shop_products?accessPass=oky&ftr=4&sale=4&bs=4&tr=4&na=4&rw=4&cat=4&c_id=2&c_lim=4&c_ftr=4&f_cat=2
                            
                            */
                            public function list_products_for_shop(Request $request) {
                                $businessId = \Request::get('businessId');
                                
                                $shop_products = array();
                                $where = array();
                                $order_field = 'id';
                                $limit = 10000;
                                
                                // for parent category filter...
                                $allCatogory = Category::where('cat_business_id', $businessId)->select('cat_id','cat_name','cat_slug')->get();
                                
                                if ($request->has('ftr')) {
                                    $f_limit = \Request::get('ftr');
                                    $featured_Products = Product::with(['categories'=>function ($query) {
                                        $query->where('cat_parent_id', '<>', 0);
                                    }])
                                    ->where('business_id',$businessId)
                                    ->where('featured',1)
                                    ->orderBy($order_field, 'DESC')
                                    ->limit($f_limit)
                                    ->get();
                                    $featured_products=[];
                                    $featured_product_list = array();
                                    if (count($featured_Products)) {
                                        $index_f = 0;
                                        $featured_catName = [];
                                        foreach ($featured_Products as $featured_product) {
                                            $i=$j=0;
                                            foreach ($featured_product->categories as $key => $value) {
                                                if($value['cat_parent_id'] != 0){
                                                    $parentCat = $allCatogory->where('cat_id',$value['cat_parent_id'])->first()->toArray();
                                                    $featured_catName['cat'][$i]['catid'] = $value['cat_parent_id'] ;
                                                    $featured_catName['cat'][$i]['catname'] = $parentCat['cat_name'];
                                                    $featured_catName['cat'][$i]['cat_slug'] = $parentCat['cat_slug'];
                                                    $featured_catName['cat'][$i]['subcatid'] = $value['cat_id'] ;
                                                    $featured_catName['cat'][$i]['subcatname'] = $value['cat_name'];
                                                    $featured_catName['cat'][$i]['subcat_slug'] = $value['cat_slug'];
                                                }
                                            }
                                            
                                            if(count($featured_catName)){
                                                $featured_product_list[$index_f]['id'] = $featured_product['id'];
                                                $featured_product_list[$index_f]['slug'] = $featured_product['pro_slug'];
                                                $featured_product_list[$index_f]['name'] = $featured_product['name'];
                                                $featured_product_list[$index_f]['category'] = $featured_catName;
                                                $featured_product_list[$index_f]['url'] = url($this->CRM_URL.'uploads/');
                                                $featured_product_list[$index_f]['img'] = $featured_product['logo'];
                                                $featured_product_list[$index_f]['s_price'] = $featured_product['sale_price'];
                                                $featured_product_list[$index_f]['c_price'] = $featured_product['cost_price'];
                                                $index_f++;
                                            }    
                                        }
                                        $shop_products['featured'] = $featured_product_list;
                                    }
                                }
                                
                                if ($request->has('f_cat')) {
                                    $fcat_limit = \Request::get('f_cat'); 
                                    $featured_cat = Product::with(['categories'=>function ($query) {
                                        $query->where('cat_parent_id', '>', 0);
                                    }])
                                    ->where('featured',1)
                                    ->where('business_id',$businessId)
                                    /*->whereHas('categories',function($q) 
                                    { $q->whereNotNull('pc_category_id'); })*/
                                    ->orderBy($order_field, 'DESC')
                                    ->get();
                                    
                                    $featured_cat_list = array();
                                    $f_cat_list = array();
                                    
                                    if (count($featured_cat)) {
                                        foreach ($featured_cat as $featured_c_val) {
                                            //$ind = 0;
                                            $p_temp = array();
                                            $p_temp['id'] = $featured_c_val['id'];
                                            $p_temp['slug'] = $featured_c_val['pro_slug'];
                                            $p_temp['name'] = $featured_c_val['name'];
                                            $p_temp['url'] = url($this->CRM_URL.'uploads/');
                                            $p_temp['img'] = $featured_c_val['logo'];
                                            $p_temp['s_price'] = $featured_c_val['sale_price'];
                                            $p_temp['c_price'] = $featured_c_val['cost_price'];
                                            
                                            foreach($featured_c_val->categories as $k => $cat_val){
                                                if($cat_val['cat_parent_id'] != 0){
                                                    $parentCat = $allCatogory->where('cat_id',$cat_val['cat_parent_id'])->first()->toArray();
                                                    $temp_cat = array();
                                                    $temp_cat['id'] = $cat_val['cat_id'];
                                                    $temp_cat['subcat_slug'] = $cat_val['cat_slug'];
                                                    $temp_cat['name'] = $parentCat['cat_name'];
                                                    $temp_cat['cat_slug'] = $parentCat['cat_slug'];
                                                    $temp_cat['subcat'] = $cat_val['cat_name'];
                                                    $temp_cat['url'] = url($this->CRM_URL.'uploads/');
                                                    $temp_cat['img'] = $cat_val['cat_image'];
                                                    $temp_cat['parentId'] = $cat_val['cat_parent_id'];
                                                    //if($fcat_limit > 0) 
                                                    if(!array_key_exists($cat_val['cat_parent_id'], $featured_cat_list))
                                                    {
                                                        $temp_cat['prod'] = [];
                                                        $featured_cat_list[$cat_val['cat_parent_id']] = $temp_cat;
                                                    }
                                                    if(count($featured_cat_list[$cat_val['cat_parent_id']]['prod']) < $fcat_limit && !array_key_exists($p_temp['id'], $featured_cat_list[$cat_val['cat_parent_id']]['prod']))
                                                    {
                                                        $featured_cat_list[$cat_val['cat_parent_id']]['prod'][$p_temp['id']] = $p_temp;
                                                    }     
                                                }  
                                            }
                                            //$ind++;
                                        }
                                        $shop_products['f_cat'] = array_splice($featured_cat_list, 3);
                                    }
                                }
                                
                                if ($request->has('sale')) {
                                    $sale_limit = \Request::get('sale');
                                    
                                    $sale_Products = Product::with(['categories'=>function ($query) {
                                        $query->where('cat_parent_id', '<>', 0);
                                    }])
                                    ->where('business_id',$businessId)
                                    ->where('sale_on',1)
                                    ->orderBy($order_field, 'DESC')
                                    ->limit($sale_limit)
                                    ->get();
                                    $sale_products=[];
                                    $sale_product_list = array();
                                    if (count($sale_Products)) {
                                        $index_s = 0;
                                        foreach ($sale_Products as $sale_product) {
                                            $sale_catName=[];
                                            foreach ($sale_product->categories as $key => $value) {
                                                if($value['cat_parent_id'] != 0){
                                                    $parentCat = $allCatogory->where('cat_id',$value['cat_parent_id'])->first()->toArray();
                                                    $sale_catName['cat'][$i]['catid'] = $value['cat_parent_id'] ;
                                                    $sale_catName['cat'][$i]['catname'] = $parentCat['cat_name'];
                                                    $sale_catName['cat'][$i]['cat_slug'] = $parentCat['cat_slug'];
                                                    $sale_catName['cat'][$i]['subcatid'] = $value['cat_id'] ;
                                                    $sale_catName['cat'][$i]['subcatname'] = $value['cat_name'];
                                                    $sale_catName['cat'][$i]['subcat_slug'] = $value['cat_slug'];
                                                }
                                            }
                                            if(count($sale_catName)){
                                                $sale_product_list[$index_s]['id'] = $sale_product['id'];
                                                $sale_product_list[$index_s]['slug'] = $sale_product['pro_slug'];
                                                $sale_product_list[$index_s]['name'] = $sale_product['name'];
                                                $sale_product_list[$index_s]['category'] = $sale_catName;
                                                $sale_product_list[$index_s]['url'] = url($this->CRM_URL.'uploads/');
                                                $sale_product_list[$index_s]['img'] = $sale_product['logo'];
                                                $sale_product_list[$index_s]['s_price'] = $sale_product['sale_price'];
                                                $sale_product_list[$index_s]['c_price'] = $sale_product['cost_price'];
                                                $sale_product_list[$index_s]['sale_on'] = $sale_product['sale_on'];
                                                $index_s++;
                                            }
                                        }
                                        
                                        $shop_products['sale'] = $sale_product_list;
                                    }
                                }        
                                
                                if ($request->has('bs')) {
                                    $order_field = 'sold_unit';
                                    $bestSeller_limit = \Request::get('bs');
                                    
                                    $topSale_Products = Product::with(['categories'=>function ($query) {
                                        $query->where('cat_parent_id', '<>', 0);
                                    }])
                                    ->where('business_id',$businessId)
                                    ->orderBy($order_field, 'DESC')
                                    ->limit($bestSeller_limit)
                                    ->get();
                                    $topSale_products=[];
                                    $topSale_product_list = array();
                                    if (count($topSale_Products)) {
                                        $index_ts = 0;
                                        foreach ($topSale_Products as $topSale_product) {
                                            $topSale_catName=[];
                                            foreach ($topSale_product->categories as $key => $value) {
                                                if($value['cat_parent_id'] != 0){
                                                    $parentCat = $allCatogory->where('cat_id',$value['cat_parent_id'])->first()->toArray();
                                                    $topSale_catName['cat'][$i]['catid'] = $value['cat_parent_id'] ;
                                                    $topSale_catName['cat'][$i]['catname'] = $parentCat['cat_name'];
                                                    $topSale_catName['cat'][$i]['cat_slug'] = $parentCat['cat_slug'];
                                                    $topSale_catName['cat'][$i]['subcatid'] = $value['cat_id'] ;
                                                    $topSale_catName['cat'][$i]['subcatname'] = $value['cat_name'];
                                                    $topSale_catName['cat'][$i]['subcat_slug'] = $value['cat_slug'];
                                                }
                                            }
                                            if(count($topSale_catName)){
                                                $topSale_product_list[$index_ts]['id'] = $topSale_product['id'];
                                                $topSale_product_list[$index_ts]['slug'] = $topSale_product['pro_slug'];
                                                $topSale_product_list[$index_ts]['name'] = $topSale_product['name'];
                                                $topSale_product_list[$index_ts]['category'] = $topSale_catName;
                                                $topSale_product_list[$index_ts]['url'] = url($this->CRM_URL.'uploads/');
                                                $topSale_product_list[$index_ts]['img'] = $topSale_product['logo'];
                                                $topSale_product_list[$index_ts]['s_price'] = $topSale_product['sale_price'];
                                                $topSale_product_list[$index_ts]['c_price'] = $topSale_product['cost_price'];
                                                $topSale_product_list[$index_ts]['sold_unit'] = $topSale_product['sold_unit'];
                                                $index_ts++;
                                            }
                                        }
                                        $shop_products['best_seller'] = $topSale_product_list;
                                    }
                                } 
                                
                                if ($request->has('tr')) {
                                    $order_field = 'review_rating';
                                    $top_rated_limit = \Request::get('tr');
                                    //DB::enableQueryLog();
                                    $top_rated_Products = Product::with(['categories'=>function ($query) {
                                        $query->where('cat_parent_id', '<>', 0);
                                    }])
                                    ->where('business_id',$businessId)
                                    ->orderBy($order_field, 'DESC')
                                    ->limit($top_rated_limit)
                                    ->get();
                                    $top_rated_products=[];
                                    $top_rated_product_list = array();
                                    if (count($top_rated_Products)) {
                                        $index_tr = 0;
                                        foreach ($top_rated_Products as $top_rated_product) {
                                            $top_rated_catName=[];
                                            foreach ($top_rated_product->categories as $key => $value) {
                                                if($value['cat_parent_id'] != 0){
                                                    $parentCat = $allCatogory->where('cat_id',$value['cat_parent_id'])->first()->toArray();
                                                    $top_rated_catName['cat'][$i]['catid'] = $value['cat_parent_id'] ;
                                                    $top_rated_catName['cat'][$i]['catname'] = $parentCat['cat_name'];
                                                    $top_rated_catName['cat'][$i]['cat_slug'] = $parentCat['cat_slug'];
                                                    $top_rated_catName['cat'][$i]['subcatid'] = $value['cat_id'] ;
                                                    $top_rated_catName['cat'][$i]['subcatname'] = $value['cat_name'];
                                                    $top_rated_catName['cat'][$i]['subcat_slug'] = $value['cat_slug'];
                                                }
                                            }
                                            if(count($top_rated_catName)){
                                                $top_rated_product_list[$index_tr]['id'] = $top_rated_product['id'];
                                                $top_rated_product_list[$index_tr]['slug'] = $top_rated_product['pro_slug'];
                                                $top_rated_product_list[$index_tr]['name'] = $top_rated_product['name'];
                                                $top_rated_product_list[$index_tr]['category'] = $top_rated_catName;
                                                $top_rated_product_list[$index_tr]['url'] = url($this->CRM_URL.'uploads/');
                                                $top_rated_product_list[$index_tr]['img'] = $top_rated_product['logo'];
                                                $top_rated_product_list[$index_tr]['s_price'] = $top_rated_product['sale_price'];
                                                $top_rated_product_list[$index_tr]['rating'] = $top_rated_product['review_rating'];
                                                $top_rated_product_list[$index_tr]['c_price'] = $top_rated_product['cost_price'];
                                                $index_tr++;
                                            }
                                        }    
                                        $shop_products['top_rated'] = $top_rated_product_list;
                                    }
                                }
                                
                                if($request->has('na')) {
                                    $new_Arrivals_limit = \Request::get('na');
                                    $allProducts = Product::with(['categories'=>function ($query) {
                                        $query->where('cat_parent_id', '<>', 0);
                                    }])
                                    ->where('business_id',$businessId)
                                    ->orderBy('id', 'DESC')
                                    ->limit($new_Arrivals_limit)
                                    ->get();
                                    
                                    $products=[];
                                    $product_list = array();
                                    if (count($allProducts)) {
                                        $index = 0;
                                        foreach ($allProducts as $product) {
                                            $catName=[];
                                            foreach ($product->categories as $key => $value) {
                                                if($value['cat_parent_id'] != 0){
                                                    $parentCat = $allCatogory->where('cat_id',$value['cat_parent_id'])->first()->toArray();
                                                    $catName['cat'][$i]['catid'] = $value['cat_parent_id'] ;
                                                    $catName['cat'][$i]['catname'] = $parentCat['cat_name'];
                                                    $catName['cat'][$i]['cat_slug'] = $parentCat['cat_slug'];
                                                    $catName['cat'][$i]['subcatid'] = $value['cat_id'] ;
                                                    $catName['cat'][$i]['subcatname'] = $value['cat_name'];
                                                    $catName['cat'][$i]['subcat_slug'] = $value['cat_slug'];
                                                }
                                            }
                                            if(count($catName)){
                                                $product_list[$index]['id'] = $product['id'];
                                                $product_list[$index]['slug'] = $product['pro_slug'];
                                                $product_list[$index]['name'] = $product['name'];
                                                $product_list[$index]['category'] = $catName;
                                                $product_list[$index]['url'] = url($this->CRM_URL.'uploads/');
                                                $product_list[$index]['img'] = $product['logo'];
                                                $product_list[$index]['s_price'] = $product['sale_price'];
                                                $product_list[$index]['c_price'] = $product['cost_price'];
                                                $index++;
                                            }
                                        }
                                        $shop_products['new_arrival'] = $product_list;
                                    }
                                }
                                
                                if ($request->has('rw')) { 
                                    $reviews_limit = \Request::get('rw');
                                    $reviews = ProductReviews::with('reviews')->where('business_id',$businessId)->orderBy('id', 'DESC')->limit($reviews_limit)->get();
                                    
                                    if (count($reviews)) {
                                        $review_list = array();
                                        $index_r = 0;
                                        foreach ($reviews as $review) {
                                            $review_list[$index_r]['id'] = $review['id'];
                                            $review_list[$index_r]['rating'] = $review['pro_rating'];
                                            $review_list[$index_r]['id'] = $review['pro_id'];
                                            $review_list[$index_r]['name'] = $review->reviews->name;
                                            $review_list[$index_r]['s_price'] = $review->reviews->sale_price;
                                            $review_list[$index_r]['c_price'] = $review->reviews->cost_price;
                                            $review_list[$index_r]['url'] = url($this->CRM_URL.'uploads/');
                                            $review_list[$index_r]['img'] = $review->reviews->logo;
                                            $review_list[$index_r]['title'] = $review['review_title'];
                                            $index_r++;
                                        }
                                        $shop_products['review'] = $review_list;
                                    }
                                }
                                
                                if($request->has('cat')) {       
                                    $categories_limit = \Request::get('cat');
                                    $categories = Category::where('cat_business_id',$businessId)->where('cat_parent_id',0)->orderBy('cat_id', 'DESC')->limit($categories_limit)->get();
                                    
                                    if(count($categories)){
                                        $category_list = array();
                                        $index_c = 0;
                                        foreach ($categories as $categor) {
                                            $category_list[$index_c]['id'] = $categor['cat_id'];
                                            $category_list[$index_c]['cat_slug'] = $categor['cat_slug'];
                                            $category_list[$index_c]['name'] = $categor['cat_name'];
                                            $category_list[$index_c]['sub'] = $categor['cat_sub_title'];
                                            $category_list[$index_c]['url'] = url($this->CRM_URL.'uploads/');
                                            $category_list[$index_c]['img'] = $categor['cat_image'];
                                            $index_c++;
                                        }
                                        $shop_products['category'] = $category_list;
                                    }
                                }
                                
                                if ($request->has('c_id')) {
                                    $catId[] = $request->get('c_id');  
                                    // $categories_limit = \Request::get('cat');
                                    if ($request->has('c_lim')) {
                                        $cat_limit = \Request::get('c_lim');
                                    }
                                    else{
                                        $cat_limit = $limit;
                                    }
                                    $c_where = array();
                                    $c_where['business_id'] = $businessId;
                                    if ($request->has('c_ftr') && ($request->get('c_ftr') == 1)) {
                                        $c_where['featured'] = 1;
                                    }
                                    
                                    $categories_pro = Product::with('categories')->where($c_where)->whereHas('categories',function($q) use($catId){ $q->whereIn('pc_category_id',$catId); })->orderBy($order_field, 'DESC')->limit($cat_limit)->get();
                                    
                                    if(count($categories_pro))
                                    {
                                        $categorypro_list = array();
                                        $index_cp = 0;
                                        foreach ($categories_pro as $categorypro) {

                                            $catNameP=[];
                                            foreach ($categorypro->categories as $key => $value) {
                                                $catNameP[]=$value['cat_name'];
                                            }
                                            
                                            $categorypro_list[$index_cp]['id'] = $categorypro['id'];
                                            $categorypro_list[$index_cp]['slug'] = $categorypro['pro_slug'];
                                            $categorypro_list[$index_cp]['name'] = $categorypro['name'];
                                            $categorypro_list[$index_cp]['cat'] = $catNameP;
                                            $categorypro_list[$index_cp]['url'] = url($this->CRM_URL.'uploads/');
                                            $categorypro_list[$index_cp]['img'] = $categorypro['logo'];
                                            $categorypro_list[$index_cp]['s_price'] = $categorypro['sale_price'];
                                            $categorypro_list[$index_cp]['c_price'] = $categorypro['cost_price'];
                                            $index_cp++;
                                        }
                                        $shop_products['c_prod'] = $categorypro_list;
                                    }
                                }
                                if(count($shop_products))
                                    return json_encode($shop_products);    
                                else 
                                    return json_encode(['code' => '401', 'message' => 'No data for queried values.']);
                            }
                            
                            /*
                            API: product detail of given business and id
                            type:get
                            parameter
                            1:accessPass ex. oky
                            2:product_id ex. 1
                            3:limit ex. 2
                            
                            http://192.168.225.50/api/product_detail?accessPass=oky&product_id=1&limit=2
                            */
                            public function product_detail(Request $request) {
                                $businessId = \Request::get('businessId');
                                //$productId = \Request::get('product_id');
                                $productSlug = \Request::get('product');
                                //$product = Product::with('categories','reviews')->find($productId);
                                $product = Product::with('categories','reviews')->where('pro_slug', $productSlug)->first();
                                $products_detail=[];
                                $product_rating = 0;
                                $limit = 100;
                                if(count($product)){
                                    Product::whereId($product->id)->increment('view_count');
                                    
                                    // for parent category filter...
                                    $allCatogory = Category::where('cat_business_id', $businessId)->select('cat_id','cat_name','cat_slug')->get();
                                    
                                    $catId=[];
                                    $catName=[];
                                    $p_catname =[];
                                    if(count($product->categories)){
                                        $i=0; 
                                        
                                        foreach ($product->categories as $key => $value) {
                                            $parentCat = $allCatogory->where('cat_id',$value['cat_parent_id'])->first()->toArray();
                                            if($value['cat_parent_id'] != 0){ 
                                                $catName[$i]['name']=$value['cat_name'];
                                                $catName[$i]['cat_id']=$value['cat_id'];
                                                $catName[$i]['cat_slug']=$value['cat_slug'];
                                                $catId[]=$value['cat_id'];
                                                $p_catname[$i]['parentCatName'] = $parentCat['cat_name'];
                                                $p_catname[$i]['parentCatSlug']=$parentCat['cat_slug'];
                                                $p_catname[$i]['cat_id']=$value['cat_parent_id'];
                                                $i++;
                                            }
                                        }
                                        if ($request->has('limit')) {
                                            $limit = \Request::get('limit');
                                        }
                                        
                                        $allProducts = Product::with('categories')->where('business_id',$businessId)->whereHas('categories',function($q) use($catId){ $q->whereIn('pc_category_id',$catId); })->orderBy('id', 'DESC')->limit($limit)->get();
                                        $related_products = array();    
                                        if(count($allProducts)){
                                            foreach ($allProducts as $Pkey => $Pvalue) {
                                                if($Pvalue->id != $product->id){
                                                    $r_cat_arr = array();
                                                    foreach ($Pvalue->categories as $r_key => $r_value) {
                                                        $r_cat_arr[$r_key]['c_id'] = $r_value['cat_id'];
                                                        $r_cat_arr[$r_key]['c_name'] = $r_value['cat_name'];
                                                        $r_cat_arr[$r_key]['c_slug'] = $r_value['cat_slug'];
                                                        $r_cat_arr[$r_key]['c_url'] = url($this->CRM_URL.'uploads/');
                                                        $r_cat_arr[$r_key]['c_img'] = $r_value['cat_image'];
                                                    }
                                                    
                                                    $related_products[$Pkey]['id']=$Pvalue->id;
                                                    $related_products[$Pkey]['slug']=$Pvalue->pro_slug;
                                                    $related_products[$Pkey]['name']=$Pvalue->name;
                                                    $related_products[$Pkey]['cat']=$r_cat_arr;
                                                    $related_products[$Pkey]['r_url']=url($this->CRM_URL.'uploads/');
                                                    $related_products[$Pkey]['r_img']=$Pvalue->logo;
                                                    $related_products[$Pkey]['s_price']=$Pvalue->sale_price;
                                                }
                                            }  
                                        }
                                        $products_detail['related_products']=$related_products;
                                        
                                    }
                                    if(count($product->reviews)){
                                        $reviewName=[];
                                        $index = 0;
                                        $reviewed_by = '';
                                        foreach ($product->reviews as $key => $value) {
                                            // dd($value);
                                            $r_client = Clients::find($value['user_id']);
                                            // dd($r_client);
                                            $reviewName[$key]['id']=$value['id'];
                                            $reviewName[$key]['rating']=$value['pro_rating'];
                                            $product_rating+=$value['pro_rating'];
                                            if($value['pro_rating'])
                                                $index++;    
                                            $reviewName[$key]['r_title']=$value['review_title'];
                                            $reviewName[$key]['r_desc']=$value['review_description'];
                                            $reviewName[$key]['r_by']=$r_client['firstname'].' '.$r_client['lastname'];
                                            $reviewName[$key]['r_created']= (is_null($value['created_at'])) ? $value['created_at'] : setLocalToBusinessTimeZone($value['created_at']); 
                                            $reviewName[$key]['r_updated']= (is_null($value['updated_at'])) ? $value['updated_at'] : setLocalToBusinessTimeZone($value['updated_at']); 
                                            // $reviewName[$key]['r_updated']=$value['updated_at']->format('Y-m-d H:m:s');      
                                        }
                                        $products_detail['final_rating'] = ($product_rating/$index);
                                        $products_detail['total_review'] = count($reviewName);
                                        $products_detail['review']=$reviewName;
                                        $products_detail['p_review_count']=count($reviewName);
                                    }
                                    
                                    $products_detail['id']=$product['id'];
                                    $products_detail['slug']=$product['pro_slug'];
                                    $products_detail['name']=$product['name'];
                                    $products_detail['sku_id']=$product['sku_id'];
                                    $products_detail['cat']=$catName;
                                    $products_detail['pcat']=$p_catname;
                                    
                                    $products_detail['p_desc']=$product['description'];
                                    $products_detail['pro_url']=url($this->CRM_URL.'uploads/');
                                    $products_detail['pro_img']=$product['logo'];
                                    $products_detail['s_price']=$product['sale_price'];
                                    $products_detail['c_price']=$product['cost_price'];
                                    $products_detail['tax']= $product['tax'];
                                    $products_detail['stock_note']=$product['stock_note'];
                                    $products_detail['stock_lvl']=$product['stock_level'];
                                    $products_detail['sale']=$product['sale_on'];
                                    $products_detail['featured']=$product['featured'];
                                    $products_detail['v_count']=$product['view_count'];
                                    $products_detail['color']= $product['pro_color'];
                                    $sizes = explode(',', $product['pro_size']);
                                    $products_detail['size'] = ProductSize::whereIn('id',$sizes)->select('name','gender')->get()->toArray();
                                    
                                }
                                else {
                                    return json_encode(['code' => '401', 'message' => 'No Products for queried values.']);
                                }
                                // echo "<pre>";print_r($products_detail);exit;
                                return json_encode($products_detail);
                            }
                            
                            
                            /*
                            API: list all products under given business
                            type:get
                            parameter
                            1:accessPass ex. oky
                            http://192.168.225.50/api/product_category?accessPass=oky
                            */
                            public function list_product_category(Request $request) {
                                $businessId = \Request::get('businessId');
                                $categories= Category::select('cat_id','cat_name','cat_parent_id','cat_slug')->where('cat_business_id',$businessId)->get();
                                $category=[];
                                foreach ($categories as $cat) {
                                    $category[]=array("cat_id" => $cat['cat_id'], "cat_value" =>$cat['cat_name'], "cat_slug"=>$cat['cat_slug']);
                                }
                                return json_encode($category);
                            }
                            
                            /*
                            API: list all product reviews under given business
                            type:get
                            parameter
                            1:accessPass ex. oky
                            2:review_id(optional) ex. 1
                            3:product_id(optional) ex. 1
                            http://192.168.225.50/api/product_review?accessPass=oky&review_id=2&product_id=1
                            
                            */
                            public function list_product_review(Request $request) {
                                $businessId = \Request::get('businessId');
                                $where = array();
                                if ($request->has('review_id')) {
                                    $review_id = \Request::get('review_id');
                                    $where['id'] = $review_id;
                                }
                                if ($request->has('product_id')) {
                                    $product_id = \Request::get('product_id');
                                    $where['pro_id'] = $product_id;
                                }
                                
                                $productreviews= ProductReviews::select()->where($where)->orderBy('id', 'DESC')->get();
                                $reiviews=[];
                                if(count($productreviews))
                                {
                                    foreach ($productreviews as $review) {         
                                        $reiviews[]=array(
                                            'id'=>$review->id,
                                            'user_id'=>$review->user_id,
                                            'pro_rating'=>$review->pro_rating,
                                            'pro_id'=>$review->pro_id,
                                            'title'=>$review->review_title,
                                            'desc'=>$review->review_description,
                                            'updated_at'=>setLocalToBusinessTimeZone($review->updated_at)->format('Y-m-d'),
                                            'created_at'=>setLocalToBusinessTimeZone($review->created_at)->format('Y-m-d')
                                        );
                                    }
                                    //echo "<pre>";print_r($reiviews);exit;
                                    return json_encode($reiviews);
                                }
                                else {
                                    return json_encode(['code' => '401', 'message' => 'No Products Reviews for queried values.']);
                                }  
                            }
                            
                            
                            /*  API: List all product which belongs to sub category
                            type: get
                            parameter
                            1: accessPass ex - oky
                            2: limit ex - limit(optional)
                            3: category id ex - cat_id(required)
                            4: na_limit (New Arival limit) - na_limit = 4
                            url = http://192.168.225.50/api/subcategory_by_category?accessPass=oky&cat_id=40&na_limit=4&limit=10;
                            
                            */
                            public function list_subcategory_by_category(Request $request) {
                                $businessId = \Request::get('businessId');
                                $isError = false;
                                $subcategories = array();
                                $flag = true;
                                
                                if ($request->has('cat_slug')) {
                                    //$catId = \Request::get('cat_id');
                                    $catSlug = \Request::get('cat_slug');
                                    if($request->has('limit'))
                                        $limit = \Request::get('limit');
                                    else
                                        $limit = 10;
                                    
                                    $parent_cat = Category::where('cat_slug', $catSlug)->where('cat_business_id',$businessId)->select('cat_id','cat_name','cat_image','cat_sub_title','cat_slug')->first()->toArray();
                                    
                                    $parent_cat_id = $parent_cat['cat_id'];
                                    $subcategory = array();
                                    if(count($parent_cat)){
                                        $subcategory['cat_name'] = $parent_cat['cat_name'];
                                        $subcategory['cat_slug'] = $parent_cat['cat_slug'];
                                        $subcategory['cat_url'] = url($this->CRM_URL.'uploads/');
                                        $subcategory['cat_img_name'] = $parent_cat['cat_image'];
                                        $subcategory['cat_sub_title'] = $parent_cat['cat_sub_title'];
                                        
                                        $categories = Category::where('cat_parent_id',$parent_cat_id)->limit($limit)->get();
                                        if($categories->count()){
                                            $j = 0;
                                            foreach ($categories as $key => $value) {
                                                $subcategory['subCat'][$j]['subcat_id'] = $value->cat_id;
                                                $subcategory['subCat'][$j]['subcat_slug'] = $value->cat_slug;
                                                $subcategory['subCat'][$j]['subcat_name'] = $value->cat_name;
                                                $subcategory['subCat'][$j]['subcat_subtitle'] = $value->cat_sub_title;
                                                $subcategory['subCat'][$j]['subcat_url'] = url($this->CRM_URL.'uploads/');
                                                $subcategory['subCat'][$j]['subcat_img_name'] = $value->cat_image;
                                                $j++;
                                            }
                                        }
                                        else{
                                            $isError = true;
                                        }
                                        $subcategories['subcategories'] = $subcategory;  
                                    }
                                    else{
                                        $isError = true;
                                    }
                                    $na_products = array();
                                    if($request->has('na_limit') && count($parent_cat)){
                                        $na_limit = \Request::get('na_limit');
                                        
                                        $na_categories = Category::with(['products' => function ($query) {
                                            $query->orderBy('id', 'desc');
                                        }])
                                        ->where('cat_parent_id',$parent_cat_id)
                                        ->get();
                                        
                                        if($na_categories->count()){
                                            $j = 0;
                                            foreach ($na_categories as $key => $value) {
                                                /*$na_products['subCat'][$j]['subcat_name'] = $value->cat_name;
                                                $na_products['subCat'][$j]['subcat_img'] = url($this->CRM_URL.'uploads/'.$value->cat_image);*/
                                                $i=0;
                                                foreach ($value->products as $index => $product) {
                                                    if($i < $na_limit){
                                                        /*$na_products['subCat'][$j]['prod'][$i]['id'] = $product->id;
                                                        $na_products['subCat'][$j]['prod'][$i]['name'] = $product->name;
                                                        $na_products['subCat'][$j]['prod'][$i]['img'] = url($this->CRM_URL.'uploads/'.$product->logo);
                                                        $na_products['subCat'][$j]['prod'][$i]['s_price'] = $product->sale_price;
                                                        $na_products['subCat'][$j]['prod'][$i]['c_price'] = $product->cost_price;*/
                                                        $na_products['prod'][$i]['id'] = $product->id;
                                                        $na_products['prod'][$i]['slug'] = $product->pro_slug;
                                                        $na_products['prod'][$i]['name'] = $product->name;
                                                        $na_products['prod'][$i]['pro_url'] = url($this->CRM_URL.'uploads/');
                                                        $na_products['prod'][$i]['img_name'] = $product->logo;
                                                        $na_products['prod'][$i]['s_price'] = $product->sale_price;
                                                        $na_products['prod'][$i]['c_price'] = $product->cost_price;
                                                    }
                                                    $i++; 
                                                }
                                                $j++;
                                            }
                                        }
                                        else{
                                            $isError = true;
                                        }  
                                    }
                                    $subcategories['na_product'] = $na_products;
                                    
                                }  
                                
                                //dd($categories);
                                //dd($allproducts);
                                if($isError)
                                    return json_encode(['code' => '401', 'message' => 'No data for queried values.']);
                                else
                                    return json_encode($subcategories);    
                            }
                            
                            /*  API: List all product which belongs to sub category
                            type: get
                            parameter
                            1: accessPass ex - oky
                            2: limit ex - limit(optional)
                            3: category id ex - cat_id(required)
                            4: na_limit (New Arival limit) - na_limit = 4
                            url = http://192.168.225.50/api/product_by_subcategory?accessPass=oky&subcat_slug=subcat_slug&order=name&limit=10&page=1;
                            
                            */
                            public function product_by_subcategory(Request $request) {
                                $businessId = \Request::get('businessId');
                                $isError = false;
                                $products = array();
                                
                                if ($request->has('subcat_slug')) {
                                    //$subcat = \Request::get('subcat_id');
                                    $subcatSlug = \Request::get('subcat_slug');
                                    if($request->has('limit'))
                                        $limit = \Request::get('limit');
                                    else
                                        $limit = 10;
                                    
                                    if($request->has('page'))
                                        $page = \Request::get('page');
                                    else
                                        $page = 1;
                                    
                                    $skip = $limit * ($page-1);
                                    
                                    if($request->has('order')){
                                        if($request->order == 'name'){
                                            $sortType = 'asc';
                                            $order = 'name';
                                        }
                                        elseif($request->order == 'priceasc'){
                                            $sortType = 'asc';
                                            $order = 'sale_price';
                                        }
                                        elseif($request->order == 'pricedesc'){
                                            $sortType = 'desc';
                                            $order = 'sale_price';
                                        }
                                        elseif($request->order == 'popularity'){
                                            $sortType = 'desc';
                                            $order = 'sold_unit';
                                        }
                                        elseif($request->order == 'rating'){
                                            $sortType = 'desc';
                                            $order = 'review_rating';
                                        }
                                        /*elseif($request->order == 'date')
                                        $order = 'created_at';*/
                                    }
                                    else{
                                        $sortType = 'desc';
                                        $order = 'id';
                                    }
                                    
                                    $allProduct = Category::with('products')->where('cat_slug', $subcatSlug)->first();
                                    if(count($allProduct))
                                        $products['total'] = $allProduct->products->count();
                                    
                                    $subcategory = Category::with(['products' => function ($query) use($order,$sortType, $skip, $limit) {
                                        $query->orderBy($order, $sortType);
                                        $query->skip($skip)->take($limit);
                                    }])
                                    ->where('cat_slug',$subcatSlug)
                                    ->first();
                                    
                                    if(count($subcategory) && count($subcategory->products)){
                                        $i = 0;
                                        $products['subcat_name'] = $subcategory->cat_name;
                                        foreach ($subcategory->products as $index => $product) {
                                            $products['prod'][$i]['id'] = $product->id;
                                            $products['prod'][$i]['slug'] = $product->pro_slug;
                                            $products['prod'][$i]['description'] = $product->description;
                                            $products['prod'][$i]['name'] = $product->name;
                                            $products['prod'][$i]['pro_url'] = url($this->CRM_URL.'uploads/');
                                            $products['prod'][$i]['pro_img'] = $product->logo;
                                            $products['prod'][$i]['s_price'] = $product->sale_price;
                                            $products['prod'][$i]['c_price'] = $product->cost_price;
                                            $i++;
                                        }   
                                    }
                                    else{
                                        $isError = true;
                                    }
                                }
                                else{
                                    $isError = true;
                                }  
                                
                                
                                if($isError)
                                    return json_encode(['code' => '401', 'message' => 'No data for queried values.']);
                                else
                                    return json_encode($products);    
                            }
                            
                            /*  API: List all product which belongs to sub category
                            type: post
                            parameter
                            1: accessPass ex - oky
                            3: data  - post
                            url = http://192.168.225.50/api/save_product_review?accessPass=oky&data=;
                            
                            */
                            public function save_product_review(Request $request){
                                dd('ok');
                            }
                            
                            
                            /**
                            * helper function for api
                            **/
                            protected function eventclassClientHistory($data){
                                $text = '';
                                $subText = '';
                                // dd($data);
                                if(!array_key_exists('additional', $data))
                                    $data['additional'] = "";

                                $additional = $data['additional'];
                                if($data['action'] == 'add') 
                                    $subText = ' was added to the class'.($additional?" $additional" : "").'.';
                                else
                                    $subText = ' was removed from the class'.($additional?" $additional" : "").'.';

                                foreach($data['clients'] as $client)
                                    $text .= $client->firstname.' '.$client->lastname.$subText.'|';

                                return $text;
                            }
                            
                            public function isClientBusy($data){ 
                                $eventClassData = ['startDatetime' => $data['startDatetime'], 'endDatetime' => $data['endDatetime']];

                                $clientClass = StaffEventClass::with(['clients' => function($query) use ($data){
                                    $query->where('secc_client_id', $data['clientId']);
                                }])
                                ->clashingEvents($eventClassData)
                                ->where('sec_class_id', $data['classId'])
                                ->first();
                                if(StaffEvent::where('se_client_id', $data['clientId'])
                                    ->clashingEvents($data)
                                    ->count() || count($clientClass->clients)){
                                    return true;
                            }
                            return false;
                        }

                            /*  API: List all product which belongs to sub category
                            type: post
                            parameter
                            1: accessPass ex - oky
                            3: data  - post
                            url = http://192.168.225.50/api/client_login?accessPass=oky&token=new;
                            
                            */
                            public function client_login(Request $request){
                                $business = Business::where('api_key', $request->accessPass)
                                ->select('id', 'cp_web_url')
                                ->first();

                                $isError = false;
                                if($request->has('token'))
                                    $token = $request['token'];
                                else if($request->has('token_split'))
                                    $token = str_replace(',', '#', $request['token_split']);
                                else 
                                    $token = '';

                                $clientId = ApiUser::where('token', $token)->pluck('user_account_id')->first();

                                if(!$clientId){
                                    $isError = true;
                                    return json_encode(['code' => '401', 'message' => 'Invalid token.']);
                                }
                                
                                $client = Clients::where('business_id', $business->id)->where('id',$clientId)->first();

                                if(!count($client)){
                                    $isError = true;
                                    return json_encode(['code' => '401','message' =>'Client not found.']);
                                }

                                $user = User::where('account_id', $client->id)
                                ->where('account_type', 'Client')
                                ->where('business_id', $business->id)
                                ->first();

                                if(!count($user)){
                                    $isError = true;
                                    return json_encode(['code' => '401','message' =>'Login details not provided.']);
                                }

                                $epicfitStudioLoggedUser = DB::table('epicfitstudio_login')
                                ->where('business_id', $request->businessId)
                                ->where('client_id', $request->clientId)
                                ->first();

                                if(empty($epicfitStudioLoggedUser))
                                    $epicfitUserLoggedDetails = DB::table('epicfitstudio_login')->insert([
                                        'business_id'=>  $business->id,
                                        'client_id' => $client->id,
                                        'user_data' => json_encode($user->toArray()),
                                    ]);

                                if(!$isError)
                                    return json_encode(['code' => '200', 'message' =>'Login in successful.', 'redirectUrl' => $request['redirectUrl'].$business->cp_web_url, 'user_details' =>  $user ]);
                                else
                                    return json_encode(['code' => '200', 'message' =>'Unable to login']);
                            }

                        }
