<?php

namespace App\Http\Controllers\Result;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Input;
use App\Clients;
use App\ContactNotes;
use App\Business;
use App\Staff;
use App\LocationArea;
use App\Contact;

use Carbon\Carbon;
use DB;
use Auth;
//use Crypt;
use App\Parq;
use App\MemberShip;
use App\ClientMember;
use Session;
//use Illuminate\Support\Facades\Redirect;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\LocationAreaTrait;
use App\Http\Traits\StaffEventTrait;
use App\Http\Traits\TestTrait;
use App\Http\Traits\GoalBuddyTrait;
use Illuminate\Http\Request;
use Input;
use App\Http\Traits\Result\ClientEventsTrait;
use App\Service;
//use App\Http\Traits\SalesProcessTrait;
use Validator;
use App\Http\Traits\CalendarSettingTrait;
use App\Http\Traits\ClientNoteTrait;
use App\ClientNote;
use App\StaffEventClass;
use App\Makeup;

class ClientsControllerResult extends Controller {

//	use ClientTrait, HelperTrait, LocationAreaTrait, StaffEventTrait, TestTrait, GoalBuddyTrait, StaffEventsTrait, SalesProcessTrait, CalendarSettingTrait, ClientNoteTrait;
    use ClientTrait,
        HelperTrait,
        LocationAreaTrait,
        StaffEventTrait,
        TestTrait,
        GoalBuddyTrait,
        ClientEventsTrait,
        CalendarSettingTrait;

    private $cookieSlug = 'client';

    /*
      API: list all staff and location area under given business
      type:get
      parameter
      1:businessId ex. 30

        http://192.168.0.50/result/public/api/loc_area_staff?businessId=30
     */

    public function load_loc_area_staff(Request $request) {
        if (!$request->has('businessId')) {
            return json_encode(['code' => '401', 'message' => 'Please provide Business Id.']);
        }
        //DB::enableQueryLog();
        $businessId = \Request::get('businessId');
        $locationArea = LocationArea::OfBusiness($businessId)->get();
        //dd(DB::getQueryLog());
        $locArea_array = array();
        if (count($locationArea)) {
            foreach ($locationArea as $val) {
                $locArea_array[] = array('id' => $val->la_id, 'loc_area' => $val->location_training_area . "-" . $val->la_name);
            }
        }
        
        $staffs = Staff::OfBusiness($businessId)->get();
        $staff_array = array();
        if (count($staffs)) {
            foreach ($staffs as $val) {
                $staff_array[] = array('id' => $val->id, 'staffName' => $val->first_name . " " . $val->last_name);
            }
        }
        $final_result = array();
        $final_result['location_area'] = $locArea_array;
        $final_result['staff'] = $staff_array;
        
        if ((count($locArea_array) == 0) && (count($staff_array) == 0)) {
                    return json_encode(['code' => '401', 'message' => 'No results for queried values.']);
        }
        //echo "<pre>";print_r($final_result);exit;
        return json_encode($final_result);
    }
    /*
      API: list all staff under given business
      type:get
      parameter
      1:businessId ex. 30

      http://192.168.0.50/result/public/api/load_staff?businessId=30
     */

//    public function load_staff(Request $request) {
//        //echo "<pre>";print_r($_GET);exit;
//        if (!$request->has('businessId')) {
//            return json_encode(['code' => '401', 'message' => 'Please provide Business Id.']);
//        }
//        //DB::enableQueryLog();
//        $businessId = \Request::get('businessId');
//        $staffs = Staff::OfBusiness($businessId)->get();
//        //dd(DB::getQueryLog());
//        $staff_array = array();
//        if (count($staffs)) {
//            foreach ($staffs as $val) {
//                $staff_array[] = array('id' => $val->id, 'staffName' => $val->first_name . " " . $val->last_name);
//            }
//        } else {
//            return json_encode(['code' => '401', 'message' => 'No results for queried values.']);
//        }
//        //echo "<pre>";print_r($staff_array);exit;
//        return json_encode($staff_array);
//    }

    /*
      API: list all class under given business
      type:post
      parameter
      1:businessId ex. 30
      2:class_date ex. 2017-07-16
      3:staff_id (optional) ex. 2
      4:area_id (optional) ex. 2

     */

    public function list_classes(Request $request) {

        if (!$request->has('businessId')) {
            return json_encode(['code' => '401', 'message' => 'Please provide Business Id.']);
        }
        $businessId = \Request::get('businessId');

        $where = array();
        if (!$request->has('class_date')) {
            return json_encode(['code' => '401', 'message' => 'Please provide Date For Class Search.']);
        }


        $date = \Request::get('class_date');
        $where['sec_date'] = $date;

        if ($request->has('staff_id')) {
            $staff_id = \Request::get('staff_id');
            $where['sec_staff_id'] = $staff_id;
        }
        if ($request->has('area_id')) {
            $area_id = \Request::get('area_id');
            $where['sec_area_id'] = $area_id;
        }

        //DB::enableQueryLog();

        $classes = StaffEventClass::has('areas')->has('staff')->with('clients', 'locationAndAreas')->OfBusinessApi($businessId)->where($where)->get();

        //echo "<pre>";print_r($classes);exit;
//        echo "<pre>";print_r(DB::getQueryLog());exit;

        $class_list = array();
        if (count($classes)) {
            $index = 0;
            foreach ($classes as $class) {
                $area_array = array();
                if ($class->sec_capacity > count($class->clients)) {
                    $staffName = Staff::find($class->sec_staff_id);
                    //$locationName = LocationArea::find($class->clasWithTrashed->cl_location_id);
                    //die($locationName.' aaaa');
                    //if($class->clasWithTrashed->cl_name == 'abhi class'){
//                        echo 'abhi '.$class->locationAndAreas;die;
//                        dd($class->locationAndAreas[0]->location->location_training_area);
                    //dd($class->locationAndAreas);
                    foreach ($class->locationAndAreas as $val) {
                        //dd($val);
                        $area_array[][$val->la_id] = $val->la_name;
                    }
                    //echo "<pre>";print_r($area_array);exit;
                    //echo "<pre>";print_r($area_array);exit;
                    //echo "<pre>";print_r($class->clasWithTrashed);exit;
                    // }
//                    echo "<pre>";print_r($staffName);exit;
//                    $class_list[$index]['date'] = $class->sec_date;
//                    $class_list[$index]['startDatetime'] = $class->sec_start_datetime;
//                    $class_list[$index]['endDatetime'] = $class->sec_end_datetime;
//                    $class_list[$index]['color'] = $class->clasWithTrashed->cl_colour;
//                    $class_list[$index]['staff_id'] = $class->sec_staff_id;
//                    $class_list[$index]['isStaffDeleted'] = $class->staffWithTrashed->trashed();
//                    $class_list[$index]['capacity'] = $class->sec_capacity;
//                    $class_list[$index]['isRepeating'] = $class->sec_is_repeating;
//                    $class_list[$index]['notes'] = $class->sec_notes;

                    $class_list[$index]['area'] = $area_array;
//                    $class_list[$index]['location_id'] = $class->clasWithTrashed->cl_location_id;
                    $class_list[$index]['location_id'] = $class->locationAndAreas[0]->location->id;
                    $class_list[$index]['location_name'] = $class->locationAndAreas[0]->location->location_training_area;
                    $class_list[$index]['staffName'] = $staffName->first_name . " " . $staffName->last_name;
                    $class_list[$index]['id'] = $class->sec_id;
                    $class_list[$index]['title'] = $class->clasWithTrashed->cl_name;
                    $class_list[$index]['price'] = $class->sec_price;


                    $index++;
                }
            }
        } else {
            return json_encode(['code' => '401', 'message' => 'No Classes for queried values.']);
        }
        return json_encode($class_list);
        //echo "<pre>";print_r($class_list);
    }

    public function index($filter = '') {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-client'))
            abort(404);

        $allClients = array();
        $search = Input::get('search');

        //if(Session::has('businessId')){
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        if (!$filter) {
            //$allClients = Clients::where('business_id', Session::get('businessId'))->paginate($length);
            if ($search)
            //$allClients = Clients::OfBusiness()->where('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->paginate($length);
                $allClients = Clients::OfBusiness()->havingString($search)->paginate($length);
            else
                $allClients = Clients::OfBusiness()->paginate($length);
        }
        else {
            //$allClients = Clients::where('business_id', Session::get('businessId'))->where('account_status', $this->getStatusForbackend($filter))->paginate($length);
            if ($search)
                $allClients = Clients::OfBusiness()->havingStatus($this->getStatusForbackend($filter))->havingString($search)->paginate($length);
            //$allClients = Clients::OfBusiness()->where('account_status', $this->getStatusForbackend($filter))->where('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->paginate($length);
            else
            //$allClients = Clients::OfBusiness()->where('account_status', $this->getStatusForbackend($filter))->paginate($length);	
                $allClients = Clients::OfBusiness()->havingStatus($this->getStatusForbackend($filter))->paginate($length);
        }
        /* if(!$filter)
          $allClients = Business::find(Session::get('businessId'))->clients;
          else
          $allClients = Business::find(Session::get('businessId'))->clients->where('account_status', $this->getStatusForbackend($filter));
          } */
        //} 
        //dd($allClients);
        //if(Input::has('search'))
        //dd(Input::get('search'));
        //else
        //dd('i');
        return view('clients.index', compact('allClients', 'filter'));
    }

    /* protected function getStatusForbackend($status, $reverse = false){
      $statuses = array('active' => 'active', 'inactive' => 'inactive', 'pending' => 'pending', 'lead' => 'Lead', 'pre-consultation' => 'Pre-Consultation', 'pre-benchmarking' => 'Pre-Benchmarking', 'pre-training' => 'Pre-Training', 'on-hold' => 'On Hold', 'active-lead' => 'Active Lead', 'inactive-lead' => 'Inactive Lead');

      if($reverse)
      return array_search($status, $statuses);
      else
      return $statuses[$status];
      } */

    public function uploadFile(Request $request) {
        //$client = Clients::findClient($request->id);
        //if($client && Auth::user()->hasPermission(Auth::user(), 'edit-client')){
        $client = Clients::find($request->id);
        if ($client) {
            //$clientId = (int)$request->id;
            //$client = Clients::find($clientId);
            $client->update(array('profilepic' => $request->photoName));
            return url('/uploads/thumb_' . $request->photoName);
        }
        return '';
    }

    public function changeStatus($clientId, $status) {
        $client = Clients::findOrFailClient($clientId);

        if (!Auth::user()->hasPermission(Auth::user(), 'edit-client'))
            abort(404);

        //$client = Clients::find($clientId);
        $clientOldStatus = $client->account_status;
        //$clientOldSaleProcessStep = $client->sale_process_step;
        $clientNewStatus = $this->getStatusForbackend($status);
        $client->update(array('account_status' => $clientNewStatus));

        $this->processSalesProcessOnStatusChange($client, ['clientOldStatus' => $clientOldStatus/* , 'clientOldSaleProcessStep' => $clientOldSaleProcessStep */, 'clientNewStatus' => $clientNewStatus]);

        return redirect('clients')->with('message', 'success|Status has been changed successfully.');
    }

    public function save(Request $request) {
        $isError = false;
        $msg = [];

        if (($request->businessId && $request->businessId != Session::get('businessId')) || !Auth::user()->hasPermission(Auth::user(), 'create-client')) {
            if ($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        if (!$isError) {
            $inputtedEmail = ($request->email) ? $request->email : $request->clientEmail;
            //if(!$this->ifEmailAvailable(['email' => $inputtedEmail, 'entity' => 'client'])){
            if (!$this->ifEmailAvailableInSameBusiness(['email' => $inputtedEmail, 'entity' => 'client'])) {
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }

            if (!$isError) {
                if ($request->businessId != '') {
                    $parq = new Parq;
                    /* if($request->day != '' && $request->month != '' && $request->year != '')
                      $dob = $parq->setDob($request->year, $request->month, $request->day);
                      else
                      $dob = ''; */
                    $dob = prepareDob($request->year, $request->month, $request->day);

                    if (isset($request->goalHealthWellness) && $request->goalHealthWellness != '')
                        $goals = groupValsToSingleVal($request->goalHealthWellness);
                    else
                        $goals = '';

                    $clientNewStatus = $this->getStatusForbackend($request->client_status);

                    $salesProcessDetails = calcSalesProcessRelatedStatus($clientNewStatus);
                    //$insertData = array('business_id' => $request->businessId, 'firstname' => $request->first_name, 'lastname' => $request->last_name, 'phonenumber' => $request->numb, 'email' => $request->email, 'birthday' => $dob, 'account_status' => $clientNewStatus, 'sale_process_step' => calcSalesProcessStepNumb($clientNewStatus), 'notes' => $request->client_notes);
                    $insertData = array('business_id' => $request->businessId, 'firstname' => $request->first_name, 'lastname' => $request->last_name, 'phonenumber' => $request->numb, 'email' => $request->email, 'birthday' => $dob, 'account_status' => $clientNewStatus, 'sale_process_step' => $salesProcessDetails['saleProcessStepNumb'], 'notes' => $request->client_notes);
                    if (isset($request->gender))
                        $insertData['gender'] = $request->gender;

                    if ($request->login_with_email)
                        $insertData['login_with_email'] = $request->login_with_email;

                    $saleProcessConsultedDetails = calcSalesProcessRelatedStatus('consulted');
                    if ($salesProcessDetails['saleProcessStepNumb'] > $saleProcessConsultedDetails['saleProcessStepNumb'])
                        $insertData['consultation_date'] = Carbon::now()->toDateString();

                    //$insertData['sale_process_step'] = calcSalesProcessStepNumb($request->client_status);
                    //$business = Business::find($request->businessId);
                    //$addedClient = $business->clients()->create($insertData);
                    $addedClient = Clients::create($insertData);
                    Session::put('ifBussHasClients', true);

                    $salesProcessHistory = ['clientId' => $addedClient->id, 'toType' => $salesProcessDetails['salesProcessType'], 'toStep' => $salesProcessDetails['saleProcessStepNumb']];
                    $this->saveSalesProcess($salesProcessHistory);

                    $insertData = array('firstName' => $request->first_name, 'lastName' => $request->last_name, 'contactNo' => $request->numb, 'email' => $request->email, 'dob' => $dob);

                    if ($dob)
                        $insertData['age'] = '';
                    if (isset($request->gender))
                        $insertData['gender'] = $request->gender;
                    if ($goals)
                        $insertData['goalHealthWellness'] = $goals;
                    if (isset($request->referralNetwork)) {
                        $insertData['referralNetwork'] = $request->referralNetwork;
                        $insertData['referralId'] = $request->referralId;
                    }

                    if ($request->client_membership != '') {
                        $this->subscribeMembership($addedClient->id, $request->client_membership, 'manual');
                    }

                    //if(Auth::user()->hasPermission(Auth::user(), 'create-parq'))
                    $addedClient->parq()->create($insertData);

                    if ($request->login_with_email)
                        $this->callStoreUser(['name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'accountId' => $addedClient->id]);

                    $msg['status'] = 'added';
                    $msg['insertId'] = $addedClient->id;
                }
                else {
                    $insertId = $this->quickSaveClient($request);
                    if ($insertId) {
                        $msg['status'] = 'added';
                        $msg['insertId'] = $insertId;
                    }
                }
            }
        }
        return json_encode($msg);
    }

    private function subscribeMembership($clientId, $memberShip, $subscriptionType) {
        $memberShip = is_int($memberShip) ? MemberShip::with('classmember', 'servicemember')->where('id', $membershipId)->get()->first() : $memberShip;
        $dt = Carbon::now();
        $timestamp = $dt->toDateTimeString();
        switch ($memberShip->me_validity_type) {
            case 'day': $dt->addDays($memberShip->me_validity_length);
                break;
            case 'week': $dt->addWeeks($memberShip->me_validity_length);
                break;
            case 'month': $dt->addMonths($memberShip->me_validity_length);
                break;
            case 'year': $dt->addYears($memberShip->me_validity_length);
                break;
        }
        $classes = $memberShip->classmember->pluck('cl_name', 'cl_id')->toArray();
        $servicesCollection = $memberShip->servicemember;
        $services = array();
        foreach ($servicesCollection as $ser) {
            $services[$ser->id] = $ser->category == 1 ? $ser->team_name : $ser->one_on_one_name;
        }
        //['every_month' => 'Every month','single_payment'=>'Single payment','1st_15th'=>'1st and 15th','10th_25th'=>'10th and 25th','1week'=>'Every 1 Week','4week'=>'Every 4 Week','2month'=>'Every 2 Months','4month'=>'Every 4 Months']
        $ddt = Carbon::now();
        switch ($memberShip->me_installment_plan) {
            case 'single_payment': $ddt = null;
                break;
            case 'every_month':
            case 'every_months':
                $ddt->addMonth(1);
                break;
            case '1st_15th':
                if ($ddt->day < 15)
                    $ddt->day = 15;
                else {
                    $ddt->addMonth(1);
                    $ddt->day = 1;
                }
                break;
            case '10th_25th': $ddt->day = $ddt->day < 10 ? 10 : 25;
                break;
            case '1week':
            case '4week':
                $ddt->addWeek(substr($memberShip->me_installment_plan, 0, 1));
                break;
            case '2month':
            case '4month':
                $ddt->addMonth(substr($memberShip->me_installment_plan, 0, 1));
                break;
        }
        $insertData = array('cm_client_id' => $clientId, 'cm_membership_id' => $memberShip->id, 'cm_label' => $memberShip->me_membership_label, 'cm_validity_length' => $memberShip->me_validity_length, 'cm_validity_type' => $memberShip->me_validity_type, 'cm_class_limit' => $memberShip->me_class_limit, 'cm_class_limit_length' => $memberShip->me_class_limit_length, 'cm_class_limit_type' => $memberShip->me_class_limit_type, 'cm_auto_renewal' => $memberShip->me_auto_renewal, 'cm_installment_plan' => $memberShip->me_installment_plan, 'cm_installment_amount' => $memberShip->me_installment_amt, 'cm_total_amount' => $memberShip->membership_totaltax ? $memberShip->membership_totaltax : $memberShip->me_installment_amt, 'cm_services' => json_encode($services), 'cm_classes' => json_encode($classes), 'cm_enrollment_limit' => $memberShip->me_enrollment_limit, 'cm_start_date' => $timestamp, 'cm_end_date' => $dt->toDateTimeString(), 'cm_subscription_type' => $subscriptionType);
        if ($ddt) {
            $insertData['cm_due_date'] = $ddt->toDateTimeString();
        }
        return ClientMember::create($insertData);
    }

    protected function callStoreUser($data) {
        $this->storeUser(['name' => $data['name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'password' => str_random(10), 'userTypeId' => 0, 'businessId' => Session::get('businessId'), 'accountId' => $data['accountId'], 'type' => 'Client']);
    }

    public function coClients(Request $request) {
        // if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-client')) {
        //     if ($request->ajax())
        //         return [];
        //     else
        //         abort(404);
        // }

        //if(Session::has('businessId'))
        //$clients = Business::find(Session::get('businessId'))->clients()->where('id', '!=', $request->id)->get();
        //else
        //return [];

        $clients = Clients::OfBusiness()->where('id', '!=', $request->id)->get();

        return $this->prepareClientsList($clients);
    }

    public function allClients(Request $request) {
        /* if(!Session::has('businessId')){
          if($request->ajax())
          return [];
          else
          abort(404);
          }

          $clients = Clients::OfBusiness()->get();

          $clientsList = $this->prepareClientsList($clients);

          if($request->has('calendar')){
          $clientsList = json_decode($clientsList);
          if(count($clientsList)){
          $clientsId = [];
          foreach($clientsList as $client){
          if($client->makeupCount)
          $client->makeUp = true;
          else
          $clientsId[] = $client->id;
          }

          if(count($clientsId)){
          $makeUpClientsId = DB::table('staff_event_class_clients')->whereIn('secc_client_id', $clientsId)->where('secc_if_make_up', 1)->where('secc_if_make_up_created', 0)->select('secc_client_id')->get();
          if(count($makeUpClientsId)){
          foreach($makeUpClientsId as $value){
          foreach($clientsList as $client){
          if($value->secc_client_id == $client->id){
          $client->makeUp = true;
          break;
          }
          }
          }
          }
          }
          }
          $clientsList = json_encode($clientsList);
          }

          return $clientsList; */
        return $this->allClientsFromTrait($request);
    }

    public function show($id) {
        $clients = Clients::findOrFailClient($id);

        if (!Auth::user()->hasPermission(Auth::user(), 'view-client'))
            abort(404);

        $countries = \Country::getCountryLists();
        $currencies = \Currency::$currencies;
        $timezones = \TimeZone::getTimeZone();
        $parq = $clients->parq;
        $allMemberShipData = [];

        //DB::enableQueryLog();

        $selectedMemberShip = ClientMember::where('cm_client_id', $clients->id)->latest()->first();

        $memberShip = MemberShip::where('me_business_id', Session::get('businessId'))->get();
        $membershipClasses = $membershipServices = '-';
        foreach ($memberShip as $mValue) {
            $allMemberShipData[$mValue['id']] = $mValue->me_membership_label;
        }
        if ($selectedMemberShip) {
            $membershipClasses = is_array($selectedMemberShip->cm_classes) ? implode('<br />', json_decode($selectedMemberShip->cm_classes, true)) : '-';
            $membershipServices = is_array($selectedMemberShip->cm_services) ? implode('<br />', json_decode($selectedMemberShip->cm_services, true)) : '-';
        }

        $membershipHistory = ClientMember::where('cm_client_id', $clients->id)->latest()->get();
        //dd( $membershipHistory );
        //dd(DB::getQueryLog()); orderBy('upload_time', 'desc')->take(5);
        //$noteArray = $clients->contactNotes()->whereNotNull('contact_notes.notes')->latest()->take(5)->get();
        //$noteArray=$clients->createNotes()->latest()->take(2)->get();
        $noteArray = ClientNote::latest()->orderBy('cn_id', 'desc')->where('cn_client_id', $id)->take(5)->get();
        $this->deleteExpiringAspirantsEvents();

        $eventsListData = $this->eventsListForOverview($clients);
        $pastEvents = $eventsListData['pastEvents'];
        $latestPastEvent = $eventsListData['latestPastEvent'];
        $futureEvents = $eventsListData['futureEvents'];
        $oldestFutureEvent = $eventsListData['oldestFutureEvent'];
        $modalLocsAreas = $eventsListData['modalLocsAreas'];
        $eventRepeatIntervalOpt = $eventsListData['eventRepeatIntervalOpt'];
        $makeUpCount = $clients->makeup_session_count;
        if (count($pastEvents))
            $makeUpCount += $this->getMakeUpCount($pastEvents);
        if (count($futureEvents))
            $makeUpCount += $this->getMakeUpCount($futureEvents);

        $defaultAndComplServCount = Service::defaultAndComplCount();
        $serviceCancelReasons = $this->getCancelReasons();

        $parq->isReferenceDeleted = false;
        $clients->account_status_backend = $this->getStatusForbackend($clients->account_status, true);
        if ($parq->referralNetwork == 'Client') {
            $client = Clients::withTrashed()->find($parq->referralId);
            if ($client != null) {
                $parq->clientName = $client->firstname . ' ' . $client->lastname;
                $parq->clientId = $parq->referralId;
                if ($client->trashed())
                    $parq->isReferenceDeleted = true;
            }
        }
        else if ($parq->referralNetwork == 'Staff') {
            $staff = Staff::withTrashed()->find($parq->referralId);
            if ($staff != null) {
                $parq->staffName = $staff->first_name . ' ' . $staff->last_name;
                $parq->staffId = $parq->referralId;
                if ($staff->trashed())
                    $parq->isReferenceDeleted = true;
            }
        }
        else if ($parq->referralNetwork == 'Professional network') {
            $contact = Contact::withTrashed()->find($parq->referralId);
            if ($contact != null) {
                $parq->proName = $contact->contact_name;
                $parq->proId = $parq->referralId;
                if ($contact->trashed())
                    $parq->isReferenceDeleted = true;
            }
        }
        if ($parq->dob != '0000-00-00') {
            $carbonDob = Carbon::createFromFormat('Y-m-d', $parq->dob);
            $overviewDob = $carbonDob->format('d M, Y');
            $parq->birthYear = $carbonDob->year;
            $parq->birthMonth = $carbonDob->month;
            $parq->birthDay = $carbonDob->day;
        } else
            $overviewDob = $parq->birthYear = $parq->birthMonth = $parq->birthDay = '';

        if ($parq->addrState)
            $parq->stateName = \Country::getStateName($parq->country, $parq->addrState);

        $parq->paIntensity = explode(',', $parq->paIntensity);

        $parq->headInjury = explode(',', $parq->headInjury);
        $parq->neckInjury = explode(',', $parq->neckInjury);
        $parq->shoulderInjury = explode(',', $parq->shoulderInjury);
        $parq->armInjury = explode(',', $parq->armInjury);
        $parq->handInjury = explode(',', $parq->handInjury);
        $parq->backInjury = explode(',', $parq->backInjury);
        $parq->hipInjury = explode(',', $parq->hipInjury);
        $parq->legInjury = explode(',', $parq->legInjury);
        $parq->footInjury = explode(',', $parq->footInjury);

        $parq->goalHealthWellnessRaw = $parq->goalHealthWellness;
        $parq->goalHealthWellness = explode(',', $parq->goalHealthWellness);

        $parq->headImprove = explode(',', $parq->headImprove);
        $parq->neckImprove = explode(',', $parq->neckImprove);
        $parq->footImprove = explode(',', $parq->footImprove);
        $parq->legImprove = explode(',', $parq->legImprove);
        $parq->handImprove = explode(',', $parq->handImprove);
        $parq->backImprove = explode(',', $parq->backImprove);
        $parq->hipImprove = explode(',', $parq->hipImprove);
        $parq->hamstringsImprove = explode(',', $parq->hamstringsImprove);
        $parq->shouldersImprove = explode(',', $parq->shouldersImprove);
        $parq->armsImprove = explode(',', $parq->armsImprove);
        $parq->calvesImprove = explode(',', $parq->calvesImprove);
        $parq->quadsImprove = explode(',', $parq->quadsImprove);
        $parq->lifestyleImprove = explode(',', $parq->lifestyleImprove);
        $parq->goalWantTobe = explode(',', $parq->goalWantTobe);
        $parq->goalWantfeel = explode(',', $parq->goalWantfeel);
        $parq->goalWantHave = explode(',', $parq->goalWantHave);
        $parq->motivationImprove = explode(',', $parq->motivationImprove);

        $goalBuddyListData = $this->goalListing(['id' => $id]);
        $goalListData = $goalBuddyListData['goals'];
        $goalDetailsData = $goalBuddyListData['goalDetails'];
        $allBusinessClient = $this->clientDetails();
        $allClientArray = $allBusinessClient['clientArray'];
        Session::put('clientId', $clients->id);

        // get all notes from clientNotes tabel use with ClientNote model
        $allNotes = ClientNote::select('cn_id', 'cn_client_id', 'cn_type', 'cn_notes', 'created_at')->where('cn_client_id', $id)->latest()->orderBy('cn_id', 'desc')->get();
        //if($parq->state == 'completed'){
        /* if(!isSuperUser() && ($parq->waiverTerms || !hasPermission('edit-parq'))){
          $parq->preferredTraingDays = json_decode($parq->preferredTraingDays, 1);
          return view('view_client_newprofile', compact('overviewDob', 'countries','timezones','currencies','clients','clinics','clientClinics','sessions','parq', 'pastEvents', 'latestPastEvent', 'futureEvents', 'oldestFutureEvent', 'modalLocsAreas', 'eventRepeatIntervalOpt','noteArray', 'makeUpCount', 'goalListData','goalDetailsData','allClientArray','allMemberShipData','selectedMemberShip', 'defaultAndComplServCount', 'membershipClasses', 'membershipServices', 'membershipHistory', 'serviceCancelReasons','allNotes'));
          }
          else
          return view('client_newprofile', compact('overviewDob', 'countries','timezones','currencies','clients','clinics','clientClinics','sessions','parq', 'pastEvents', 'latestPastEvent', 'futureEvents', 'oldestFutureEvent', 'modalLocsAreas', 'eventRepeatIntervalOpt','noteArray', 'makeUpCount', 'goalListData','goalDetailsData','allClientArray','allMemberShipData','selectedMemberShip', 'defaultAndComplServCount', 'membershipClasses', 'membershipServices', 'membershipHistory', 'serviceCancelReasons','allNotes')); */

        if (!$parq->waiverTerms && hasPermission('edit-parq'))
            return view('client_newprofile', compact('overviewDob', 'countries', 'timezones', 'currencies', 'clients', 'clinics', 'clientClinics', 'sessions', 'parq', 'pastEvents', 'latestPastEvent', 'futureEvents', 'oldestFutureEvent', 'modalLocsAreas', 'eventRepeatIntervalOpt', 'noteArray', 'makeUpCount', 'goalListData', 'goalDetailsData', 'allClientArray', 'allMemberShipData', 'selectedMemberShip', 'defaultAndComplServCount', 'membershipClasses', 'membershipServices', 'membershipHistory', 'serviceCancelReasons', 'allNotes'));
        else {
            //$parq->preferredTraingDays = json_encode($parq->preferredTraingDays, 1);
            //dd($parq->preferredTraingDays);
            return view('view_client_newprofile', compact('overviewDob', 'countries', 'timezones', 'currencies', 'clients', 'clinics', 'clientClinics', 'sessions', 'parq', 'pastEvents', 'latestPastEvent', 'futureEvents', 'oldestFutureEvent', 'modalLocsAreas', 'eventRepeatIntervalOpt', 'noteArray', 'makeUpCount', 'goalListData', 'goalDetailsData', 'allClientArray', 'allMemberShipData', 'selectedMemberShip', 'defaultAndComplServCount', 'membershipClasses', 'membershipServices', 'membershipHistory', 'serviceCancelReasons', 'allNotes'));
        }
    }

    public function showBenchmarks($id) {
        //$clients = Clients::findOrFail($id);
        $clients = Clients::findOrFailClient($id)->select('id', 'client_id', 'nps_manual_time', 'nps_day', 'nps_time_hour', 'nps_time_min', 'nps_automatic_time');
        return view('showClientsBenchmarks', compact('clients'));
    }

    public function updateField(Request $request) {
        $client = Clients::findClient($request->entityId);

        if (!$client || !Auth::user()->hasPermission(Auth::user(), 'edit-client')) {
            if ($request->ajax())
                return [];
            else
                abort(404);
        }

        //$client = Clients::find($request->entityId);
        //$parq = $client->parq;
        $clientData = array();
        $parqData = array();

        if ($request->entityProperty == 'firstName') {
            $value = $request->firstName;
            $clientData = array('firstname' => $value);
            $parqData = array('firstName' => $value);
        } else if ($request->entityProperty == 'lastName') {
            $value = $request->lastName;
            $clientData = array('lastname' => $value);
            $parqData = array('lastName' => $value);
        } else if ($request->entityProperty == 'accStatus') {
            $clientOldStatus = $client->account_status;
            $clientNewStatus = $this->getStatusForbackend($request->accStatus);

            $clientData = array('account_status' => $clientNewStatus/* $this->getStatusForbackend($request->accStatus) */);
            $value = $request->accStatus . '|' . $clientData['account_status'];
        } else if ($request->entityProperty == 'gender') {
            $value = $request->gender;
            $clientData = array('gender' => $value);
            $parqData = array('gender' => $value);
        } else if ($request->entityProperty == 'goals') {
            if ($request->goals != '')
                $value = groupValsToSingleVal($request->goals);
            else
                $value = '';

            $parqData = array('goalHealthWellness' => $value);
        }
        else if ($request->entityProperty == 'dob') {
            /* if($request->day != '' && $request->month != '' && $request->year != '')
              $value = $parq->setDob($request->year, $request->month, $request->day);
              else
              $value = ''; */
            $value = prepareDob($request->year, $request->month, $request->day);

            $parqData = array('dob' => $value);

            if ($value)
                $parqData['age'] = '';
        }
        else if ($request->entityProperty == 'email') {
            //if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'client', 'id' => $request->entityId])){
            if (!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'client', 'id' => $request->entityId])) {
                //if(!$this->ifEmailAvailable($request->email, $request->entityId)){
                return json_encode([
                    'status' => 'emailExistError',
                    'message' => 'This email is already in use'
                ]);
            }

            $value = $request->email;
            $clientData = array('email' => $value);
            $parqData = array('email' => $value);
        } else if ($request->entityProperty == 'phone') {
            $value = $request->phone;
            $clientData = array('phonenumber' => $value);
            $parqData = array('contactNo' => $value);
        } else if ($request->entityProperty == 'occupation') {
            $value = $request->occupation;
            $clientData = array('occupation' => $value);
            $parqData = array('occupation' => $value);
        } else if ($request->entityProperty == 'membershipOption') {
            $memberShip = MemberShip::with('classmember', 'servicemember')->where('id', $request->membershipOption)->get()->first();
            $addMemberShiplient = $this->subscribeMembership($client->id, $memberShip, 'manual');
            $value = $request->membershipOption . '|' . $memberShip->me_membership_label;
        }

        if (count($clientData))
            $client->update($clientData);
        //if(count($parqData) && (isSuperUser() || Auth::user()->hasPermission(Auth::user(), 'edit-parq')))
        if (count($parqData) && hasPermission('edit-parq'))
        //$parq->update($parqData);
            $client->parq->update($parqData);

        if ($request->entityProperty == 'accStatus' && $clientOldStatus != $clientNewStatus) {
            $data = $this->processSalesProcessOnStatusChange($client, ['clientOldStatus' => $clientOldStatus, 'clientNewStatus' => $clientNewStatus]);

            return json_encode([
                'status' => 'updated',
                'value' => $value,
                'consultationDate' => $data['consultationDate'],
                'oldSaleProcessStep' => $data['oldSaleProcessStep'],
                'stepCompleted' => $data['newSaleProcessStep'],
                'action' => $data['action']
            ]);
        } else {
            return json_encode([
                'status' => 'updated',
                'value' => $value
            ]);
        }
    }

    public function edit($id) {
        $client = Clients::findOrFailClient($id);
        //dd($client->parq);

        if (!Auth::user()->hasPermission(Auth::user(), 'edit-client'))
            abort(404);

        //if(!Session::has('businessId'))
        //return redirect('settings/business/create');
        //$client = Clients::with('parq')->find($id);
        //if($client){
        //$business = Business::with('locations')->find(Session::get('businessId'));
        //$businessId = $business->id;
        $businessId = Session::get('businessId');

        $client->account_status_backend = $this->getStatusForbackend($client->account_status, true);

        if ($client->birthday != '0000-00-00') {
            $carbonDob = Carbon::createFromFormat('Y-m-d', $client->birthday);
            $client->birthYear = $carbonDob->year;
            $client->birthMonth = $carbonDob->month;
            $client->birthDay = $carbonDob->day;
        } else
            $client->birthYear = $client->birthMonth = $client->birthDay = '';

        $client->goalHealthWellness = explode(',', $client->parq->goalHealthWellness);

        if ($client->parq->referralNetwork == 'Client') {
            $referringClient = Clients::withTrashed()->find($client->parq->referralId);
            if ($referringClient)
                $client->parq->referralName = $referringClient->firstname . ' ' . $referringClient->lastname;
        }
        else if ($client->parq->referralNetwork == 'Staff') {
            $staff = Staff::withTrashed()->find($client->parq->referralId);
            if ($staff)
                $client->parq->referralName = $staff->first_name . ' ' . $staff->last_name;
        }
        else if ($client->parq->referralNetwork == 'Professional network') {
            $contact = Contact::withTrashed()->find($client->parq->referralId);
            if ($contact)
                $client->parq->referralName = $contact->contact_name;
        }
        $allMemberShipData = ['' => '-- Select --'];
        $memberShip = MemberShip::where('me_business_id', Session::get('businessId'))->get();
        foreach ($memberShip as $mValue) {
            $allMemberShipData[$mValue['id']] = $mValue->me_membership_label;
        }

        $selectedMemberShip = ClientMember::where('cm_client_id', $client->id)->select('cm_membership_id')->latest()->first();


        return view('Settings.client.edit', compact('client', 'businessId', 'allMemberShipData', 'selectedMemberShip'));
        //}
    }

    public function update($id, Request $request) {
        $isError = false;
        $msg = [];

        $client = Clients::findClient($id, $request->businessId);

        if (!$client || !Auth::user()->hasPermission(Auth::user(), 'edit-client')) {
            if ($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if (!$isError) {
            //$client = Clients::find($id);
            //if($client){
            //if(!$this->ifEmailAvailable(['email' => $request->email, 'entity' => 'client', 'id' => $id])){
            if (!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'client', 'id' => $id])) {
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }

            if (!$isError) {
                $clientOldStatus = $client->account_status;
                if ($request->has('client_status'))
                    $clientNewStatus = $this->getStatusForbackend($request->client_status);
                else
                    $clientNewStatus = $clientOldStatus;

                $client->firstname = $request->first_name;
                $client->lastname = $request->last_name;
                //$client->account_status = $this->getStatusForbackend($request->client_status);
                $client->account_status = $clientNewStatus;
                $client->email = $request->email;
                $client->phonenumber = $request->numb;
                $client->notes = $request->client_notes;

                if (isset($request->gender))
                    $client->gender = $request->gender;
                else
                    $client->gender = '';

                $prelogin_with_email = $client->login_with_email;
                if (isset($request->login_with_email) && $request->login_with_email)
                    $client->login_with_email = $request->login_with_email;
                else
                    $client->login_with_email = 0;

                $dob = '';
                $parq = $client->parq;
                if ($parq) {
                    /* if($request->day != '' && $request->month != '' && $request->year != '')
                      $dob = $parq->setDob($request->year, $request->month, $request->day); */
                    $dob = prepareDob($request->year, $request->month, $request->day);

                    //if(Auth::user()->hasPermission(Auth::user(), 'edit-parq')){
                    //if(hasPermission('edit-parq')){
                    $parq->firstName = $request->first_name;
                    $parq->lastName = $request->last_name;
                    $parq->dob = $dob;
                    $parq->email = $request->email;
                    $parq->contactNo = $request->numb;

                    if (isset($request->gender))
                        $parq->gender = $request->gender;
                    else
                        $parq->gender = '';

                    if (isset($request->goalHealthWellness) && $request->goalHealthWellness != '')
                        $parq->goalHealthWellness = groupValsToSingleVal($request->goalHealthWellness);
                    else
                        $parq->goalHealthWellness = '';

                    if (isset($request->referralNetwork)) {
                        $parq->referralNetwork = $request->referralNetwork;
                        $parq->referralId = $request->referralId;
                    } else {
                        $parq->referralNetwork = '';
                        $parq->referralId = 0;
                    }

                    $parq->save();
                    //}
                }

                $client->birthday = $dob;
                $client->save();

                /* if($request->client_membership!=''){
                  $timestamp = createTimestamp();
                  $insertData = array('cm_client_id' => $client->id, 'cm_membership_id' => $request->client_membership, 'cm_membership_start_date' => $timestamp );
                  ClientMember::create($insertData);
                  } */

                if (!$prelogin_with_email && $request->login_with_email)
                    $this->callStoreUser(['name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'accountId' => $id]);
                else if ($prelogin_with_email) {
                    if (!$request->login_with_email) {
                        $user = $client->user;
                        if ($user)
                            $user->forceDelete();
                    }
                    else if ($request->login_with_email)
                        $this->entityLogin_tableRecordUpdate(['entity' => $client, 'firstName' => $request->first_name, 'lastName' => $request->last_name/* , 'permissionGroupId' => 0 */, 'password' => $request->clientNewPwd]);
                }

                if ($clientOldStatus != $clientNewStatus)
                    $this->processSalesProcessOnStatusChange($client, ['clientOldStatus' => $clientOldStatus, 'clientNewStatus' => $clientNewStatus]);

                $msg['status'] = 'updated';
            }
            //}
        }
        return json_encode($msg);
    }

    public function create(Request $request) {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-client'))
            abort(404);

        //if(!Session::has('businessId'))
        //return redirect('settings/business/create');
        //$business = Business::with('locations')->find(Session::get('businessId'));
        //$businessId = $business->id;
        $businessId = Session::get('businessId');
        $allMemberShipData = ['' => '-- Select --'];
        $memberShip = MemberShip::where('me_business_id', Session::get('businessId'))->get();
        foreach ($memberShip as $mValue) {
            $allMemberShipData[$mValue['id']] = $mValue->me_membership_label;
        }

        if ($request->has('subview'))
            $subview = true;

        return view('Settings.client.edit', compact('businessId', 'allMemberShipData', 'subview'));
    }

    public function destroy($id) {
        $client = Clients::findOrFailClient($id);

        if (!isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'delete-client'))
            abort(404);

        $client->delete();

        return redirect()->back()->with('message', 'success|Client has been deleted successfully.');
        //route('clients')
    }

    public function updateSalesProcess(Request $request) {
        $msg = [];
        $client = Clients::findClient($request->clientId);
        if ($client) {
            $stepNumb = (int) $request->stepNumb;
            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($stepNumb);
            if ($client->account_status == $salesProcessRelatedStatus['clientPrevStatus']) {
                $clientOldSaleProcessStep = $client->sale_process_step;
                $client->sale_process_step = $stepNumb;
                $client->save();

                $salesProcessHistory = ['clientId' => $client->id, 'toType' => $salesProcessRelatedStatus['salesProcessType'], 'toStep' => $salesProcessRelatedStatus['saleProcessStepNumb']];
                if ($clientOldSaleProcessStep) {
                    $clientPrevSalesProcess = calcSalesProcessRelatedStatus($clientOldSaleProcessStep);
                    $salesProcessHistory['fromType'] = $clientPrevSalesProcess['salesProcessType'];
                    $salesProcessHistory['fromStep'] = $clientPrevSalesProcess['saleProcessStepNumb'];
                }
                $this->saveSalesProcess($salesProcessHistory);

                $msg['status'] = 'updated';
            }
        }
        return json_encode($msg);
    }

    public function raiseMakeUp(Request $request){
		
		$isError = false;
        $msg = [];
        $raiseMakeupData=[];
        $client = Clients::findClient($request->clientId);
        if(!$client){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
         
        if(!$isError){
            
        	$raiseMakeupData['clientId']=$request->clientId;
//        	$raiseMakeupData['notes']=$request->notes;
//        	$raiseMakeupData['makeUpUnit']=$request->raiseMakeUp;
        	$raiseMakeupData['makeupRaise']='';
        	$raiseMakeupData['amount']=$request->amount;
//        echo "<pre>";print_r($raiseMakeupData);exit;	
             $this->raiseMakeupSave($raiseMakeupData);
            /*if($flag)
               $this->setMakeupSessionCount($client->id);*/
//            $msg['totalmakeup']=$client->makeups()->sum('makeup_session_count');
//            $msg['netamount']=$client->makeups()->sum('makeup_total_amount');   
            
            //dd($mag['totalmakeup']);
            $msg['status'] = 'success';

        }
        return json_encode($msg);
	}
	protected function raiseMakeupSave($raiseMakeupData){
		   
	        $makeup=new Makeup;
	        $makeup->makeup_client_id=$raiseMakeupData['clientId'];
	        if($raiseMakeupData['makeupRaise'] == 'yes'){
	        	$makeup->makeup_session_count=-($raiseMakeupData['makeUpUnit']);
		        $makeup->makeup_amount=-($raiseMakeupData['amount']);
		        $totalAmount=($raiseMakeupData['amount'] * $raiseMakeupData['makeUpUnit']);
		        $makeup->makeup_total_amount=-($totalAmount); 

		        $action = 'dropped.';
		        /*if($client->makeup_session_count > $totalAmount)
                     $flag=true;*/
	        }
	        else{
//		        $makeup->makeup_session_count=$raiseMakeupData['makeUpUnit'];
		        $makeup->makeup_amount=$raiseMakeupData['amount'];
//		        $makeup->makeup_total_amount=($raiseMakeupData['amount'] * $raiseMakeupData['makeUpUnit']);

		        $action = 'raised.';
		        /*$flag=true;*/
	        }
	        $makeup->makeup_user_id = $makeup->UserInformation['id']; 
            $makeup->makeup_user_name = $makeup->UserInformation['name'];
            $makeup->save();

	}

    protected function getMakeUpCount($eventCollections) {
        return $eventCollections->filter(function($event) {
                    $model = class_basename($event);
                    return ($model == 'StaffEventClass' && $event->pivot->secc_if_make_up === 1 && $event->pivot->secc_if_make_up_created !== 1 );
                })->count();
    }

    public function storeByApi(Request $request) {
        //return $request->goals;
        echo 'aaa ';
        exit;
        $businessId = \Request::get('businessId');
        $status = 'lead';

        $validator = Validator::make($request->all(), [
                    'firstName' => 'required',
                    //'status'=>'required',
                    'goals' => 'required',
                    'email' => 'bail|required_without:phone|email',
                    'phone' => 'bail|required_without:email|regex:/[0-9]{10}/'
        ]);
        $validator->after(function($validator) use ($request, $businessId) {
            /* if(!$validator->errors()->has('status') && !$this->getStatusForbackend($request->status))
              $validator->errors()->add('status', 'Invalid value'); */

            if ($request->email && !$validator->errors()->has('email') && !$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'client', 'businessId' => $businessId]))
                $validator->errors()->add('email', 'This email is already in use');
        });

        if ($validator->fails())
            return json_encode(['code' => '400', 'message' => 'Required data is not upto the mark', 'error' => $validator->errors()]);

        $client = new Clients;
        $client->business_id = $businessId;
        $client->firstname = $request->firstName;
        $client->create_source = 'efp';
        $client->account_status = $this->getStatusForbackend('lead');
        if ($request->has('lastName'))
            $client->lastname = $request->lastName;

        if ($request->has('email'))
            $client->email = $request->email;

        if ($request->has('phone'))
            $client->phonenumber = $request->phone;

        if ($request->has('notes'))
            $client->notes = $request->notes;

        $clientNewStatus = $this->getStatusForbackend($status);
        $salesProcessDetails = calcSalesProcessRelatedStatus($clientNewStatus);
        $client->sale_process_step = $salesProcessDetails['saleProcessStepNumb'];

        $saleProcessConsultedDetails = calcSalesProcessRelatedStatus('consulted');
        if ($salesProcessDetails['saleProcessStepNumb'] > $saleProcessConsultedDetails['saleProcessStepNumb'])
            $client->sale_process_step = Carbon::now()->toDateString();

        $client->save();
        Session::put('ifBussHasClients', true);

        $salesProcessHistory = ['clientId' => $client->id, 'toType' => $salesProcessDetails['salesProcessType'], 'toStep' => $salesProcessDetails['saleProcessStepNumb']];
        $this->saveSalesProcess($salesProcessHistory);

        $parq = new Parq;
        $parq->firstName = $request->firstName;
        if ($request->has('lastName'))
            $parq->lastName = $request->lastName;

        if ($request->has('email'))
            $parq->email = $request->email;

        if ($request->has('phone'))
            $parq->contactNo = $request->phone;

        //print_r($request->goals);

        if ($request->has('goals'))
            $parq->goalHealthWellness = groupValsToSingleVal($request->goals);
        $client->parq()->save($parq);

        return json_encode(['code' => '200', 'message' => 'Client has been created']);
    }

    /* public function printAppointments(){
      return view('includes.partials.events_list_print');
      } */
}
