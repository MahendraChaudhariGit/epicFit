<?php
namespace App\Http\Controllers;

use App\AbWorkout;
use App\Client;
use App\ClientAccountStatusGraph;

//use Input;
use App\ClientMember;
use App\ClientNote;
use App\Clients;
use App\Contact;
use App\Models\PipelineProcess\PipelineProcessTask;
use App\PostureImage;
use App\MeasurementFile;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Http\Controllers\Controller;
use App\Http\Traits\ActivityBuilderTrait;
use App\Http\Traits\CalendarSettingTrait;
use App\Http\Traits\ClientNoteTrait;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\ClosedDateTrait;
use App\Http\Traits\GoalBuddyTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\LocationAreaTrait;
use App\Http\Traits\SalesProcessProgressTrait;
use App\Http\Traits\SalesProcessTrait;
use App\Http\Traits\StaffEventClassTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\StaffEventTrait;
use App\Http\Traits\TestTrait;
use App\NutritionalJournal;
use App\SleepQuestionnaire;
use App\ChronotypeSurvey;
use App\Invoice;
use App\InvoiceItems;
use App\Makeup;
use App\MemberShip;
use App\Movement;
use App\MovementStepSetup;
use App\Parq;
use App\SalesProcessProgress;
use App\Service;
use App\Staff;
use App\StaffEventClass;
use App\StaffEventSingleService;
use App\Task;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Input;
use Session;
use Validator;
use App\SalesToolsInvoice;
use App\SalesToolsInvoicePaymentTypes;
use App\MemberShipTax;
use App\SalesToolsDiscount;
use App\Http\Traits\InvoiceTrait;
use App\ClientMenu;
use App\Http\Traits\StaffEventHistoryTrait;
use App\Business;
use App\UserLimit;
use App\CalendarSetting;
use App\Console\Commands\UpdateClientMembership;
use App\Models\Access\User\User;
use App\Result\Models\MeasurementGalleryImage;
use App\Result\Models\MeasurementBeforeAfterImage;
use App\Result\Models\TempProgressPhoto;
use App\Result\Models\FinalProgressPhoto;
use App\Http\Requests\Progress\ProgressPhotoValidation;
use App\PersonalMeasurement;

class ClientsController extends Controller
{
    use ClientTrait, HelperTrait, LocationAreaTrait, StaffEventTrait, TestTrait, GoalBuddyTrait, StaffEventsTrait, SalesProcessTrait, CalendarSettingTrait, ClosedDateTrait, ClientNoteTrait, /*StaffEventClassTrait,*/ SalesProcessProgressTrait, ActivityBuilderTrait, InvoiceTrait;

    private $cookieSlug = 'client';
    private $clientInvoiceCookieSlug = 'client_invoice';
    
    public function index(Request $request,$filter = '')
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-client')) {
            abort(404);
        }

        /* Set url in session */
        setPrevousUrl(url()->full());

        $allClients = array();
        $search     = $request->get('search');

        //if(Session::has('businessId')){
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        $allClients = Clients::OfBusiness();

//        if($request->has('my-client') && $request->get('my-client') == auth()->user()->id){
//            $pipelineProcessTasks = PipelineProcessTask::whereNotNull('column_id')->where('original_user_id', auth()->user()->id)->orderBy('id','desc')->pluck('content')->toArray();
//            $allClients = $allClients->whereIn('id',$pipelineProcessTasks);
//        }
//        dd('else');
        if (!$filter) {

            if($request->has('my-client') && $request->get('my-client') == auth()->user()->id){
                $pipelineProcessTasks = PipelineProcessTask::whereNotNull('column_id')->where('original_user_id', auth()->user()->id)->orderBy('id','desc')->pluck('content')->toArray();
                $allClients = $allClients->whereIn('id',$pipelineProcessTasks);
            }
            //$allClients = Clients::where('business_id', Session::get('businessId'))->paginate($length);
            if ($search)
            //$allClients = Clients::OfBusiness()->where('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->paginate($length);
            {
                $allClients = $allClients->havingString($search)->with('makeups', 'memberships')->paginate($length);
            } else {
                $allClients = $allClients->with('makeups', 'memberships')->paginate($length);
            }

        } else {
            if($request->has('my-client') && $request->get('my-client') == auth()->user()->id){
                $pipelineProcessTasks = PipelineProcessTask::whereNotNull('column_id')->where('original_user_id', auth()->user()->id)->orderBy('id','desc')->pluck('content')->toArray();
                $allClients = $allClients->whereIn('id',$pipelineProcessTasks);
            }
            //$allClients = Clients::where('business_id', Session::get('businessId'))->where('account_status', $this->getStatusForbackend($filter))->paginate($length);
            if ($search) {
                $allClients = $allClients->with('makeups', 'memberships')->havingStatus($this->getStatusForbackend($filter))->havingString($search)->paginate($length);
            }

            //$allClients = $allClients->where('account_status', $this->getStatusForbackend($filter))->where('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%")->orWhere('email', 'like', "%$search%")->paginate($length);
            else
            //$allClients = $allClients->where('account_status', $this->getStatusForbackend($filter))->paginate($length);
            {
                $allClients = $allClients->with('makeups', 'memberships')->havingStatus($this->getStatusForbackend($filter))->paginate($length);
            }

        }

        if ($allClients->count()) {
            $noRiskFactor = $allClients->where('risk_factor', '');
            if ($noRiskFactor->count()) {
                foreach ($noRiskFactor as $client) {
                    $client->risk_factor = $client->RiskFactorr;
                    $client->save();
                }
            }
        }

        $allMemberShipData = $this->allMemberShipData();
        $paymenttype       = $this->getPaymentType();

        //$client->makeups()->sum('makeup_amount');
        /*if(!$filter)
        $allClients = Business::find(Session::get('businessId'))->clients;
        else
        $allClients = Business::find(Session::get('businessId'))->clients->where('account_status', $this->getStatusForbackend($filter));
        }*/
        //}

//        dd($allClients);
        //if(Input::has('search'))
        //dd(Input::get('search'));
        //else
        //dd('i');
        //dd($allClients);
        return view('clients.index', compact('allClients', 'filter', 'allMemberShipData', 'paymenttype'));
    }

    /*protected function getStatusForbackend($status, $reverse = false){
    $statuses = array('active' => 'active', 'inactive' => 'inactive', 'pending' => 'pending', 'lead' => 'Lead', 'pre-consultation' => 'Pre-Consultation', 'pre-benchmarking' => 'Pre-Benchmarking', 'pre-training' => 'Pre-Training', 'on-hold' => 'On Hold', 'active-lead' => 'Active Lead', 'inactive-lead' => 'Inactive Lead');

    if($reverse)
    return array_search($status, $statuses);
    else
    return $statuses[$status];
    }*/


    public function csvExport(Request $request){
        $name = $request['status'];
        $fileName =  $name.'.csv';
        if($request['status'] == 'all'){
            $allClients = Clients::select('id','firstname','lastname','email','phonenumber','risk_factor','epic_credit_balance','account_status')
                    ->with('memberships')
                    // ->orderBy('id','DESC')
                    ->where('deleted_at',null)
                    ->OfBusiness()
                    ->get();
        }else{
            $allClients = Clients::select('id','firstname','lastname','email','phonenumber','risk_factor','epic_credit_balance','account_status')
                    ->with('memberships')
                //  ->where('id', 2475)
                    // ->orderBy('id','DESC')
                    ->where('account_status', $name )
                    ->where('deleted_at',null)
                    ->OfBusiness()
                    ->get();
            }
             $headers = array(
                 "Content-type"        => "text/csv",
                 "Content-Disposition" => "attachment; filename=$fileName",
                 "Pragma"              => "no-cache",
                 "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                 "Expires"             => "0"
             );
             $columns = array('First Name', 'Last Name', 'Email', 'Phone Number', 'Risk Factor','Epic Credit', 'Membership');
     
             $callback = function() use($allClients, $columns) {
                 $file = fopen('php://output', 'w');
                 fputcsv($file, $columns);
     
                 foreach ($allClients as $client) {
                     $row['f_name']  = $client->firstname;
                     $row['l_name']    = $client->lastname;
                     $row['email']    = $client->email;
                     $row['phone_number']  = $client->phonenumber;
                     $row['risk_factor']  = $client->risk_factor;
                     $row['epic_credit']  = $client->epic_credit_balance;
                     $row['membership']  =  strip_tags($client->clientMembershipType($client->id));
     
                     fputcsv($file, array($row['f_name'], $row['l_name'], $row['email'], $row['phone_number'], $row['risk_factor'],$row['epic_credit'], $row['membership']));
                 }
     
                 fclose($file);
             };
     
             return response()->stream($callback, 200, $headers);
         }

    public function uploadFile(Request $request)
    {
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

    public function changeStatus( /*$clientId, $status*/Request $request)
    {
        $status   = $request->newStatus;
        $clientId = $request->clientId;

        $client = Clients::findOrFailClient($clientId);

        if (!Auth::user()->hasPermission(Auth::user(), 'edit-client')) {
            abort(404);
        }

        //$client = Clients::find($clientId);
        //$clientOldStatus = $client->account_status;
        //$clientOldSaleProcessStep = $client->sale_process_step;
        $clientOldStatus = $client->account_status;
        $clientNewStatus = $this->getStatusForbackend($status);

        $client->update(array('account_status' => $clientNewStatus));

        $clientStatusGraph                 = new ClientAccountStatusGraph();
        $clientStatusGraph->business_id    = $client->business_id;
        $clientStatusGraph->client_id      = $client->id;
        $clientStatusGraph->account_status = $clientNewStatus;
        $clientStatusGraph->save();

        //$this->processSalesProcessOnStatusChange($client, ['clientOldStatus' => $clientOldStatus/*, 'clientOldSaleProcessStep' => $clientOldSaleProcessStep*/, 'clientNewStatus' => $clientNewStatus]);
        $this->processSalesProcessOnStatusChange($client, $clientOldStatus, $clientNewStatus, 'client list');

        /* Client status on-hold and inactive delete membership */
        # Remove from all future classes
        if ($status == 'on-hold' || $status == 'inactive') {
            $eventsListData = $this->eventsListForOverview($client);
            $futureEvents   = $eventsListData['futureEvents'];

            $futureEvents = $futureEvents->filter(function ($futureEvent) {
                $model = class_basename($futureEvent);
                return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null);
            }); 

            if (count($futureEvents)) {
                foreach ($futureEvents as $futureEvent) {
                    $epicCashAmount = 0;
                    $model          = class_basename($futureEvent);
                    if ($model == 'StaffEventClass') {
                        /*Get invoice related to event class*/
                        $invoice = Invoice::where('inv_client_id', $client->id)
                            ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                $query->where('inp_product_id', $futureEvent->sec_id)
                                    ->where('inp_type', 'class');
                            })->first();

                        /*Delete unpaid invoices*/
                        if ($invoice) 
                            $invoice->delete();

                        /* Remove event class clients*/
                       $updateValue =  DB::table('staff_event_class_clients')
                            ->where('secc_sec_id', $futureEvent->sec_id)
                            ->where('secc_client_id', $client->id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on account status changed to ' . $clientNewStatus . '.', 'secc_action_performed_by' => getLoggedUserName()]);

                        /* remove client from class event table */
                        if ($futureEvent->sec_secr_id != 0) {
                            $repeat         = $futureEvent->repeat()->first();
                            $removeClientId = $client->id;
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

                        // Confirm Waiting Clients
                        $clients_to_auto_confirmId = [];
                        $confirmed_clientsCount    = 0;
                        foreach ($futureEvent->clientsOldestFirst as $clientsOldestFirst) {
                            if ($clientsOldestFirst->id != $client->id) {
                                if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                    $confirmed_clientsCount++;
                                } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $futureEvent->sec_capacity) {
                                    $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                    $confirmed_clientsCount++;
                                }
                            }
                        }
                        if (count($clients_to_auto_confirmId)) {
                            DB::table('staff_event_class_clients')->where('secc_sec_id', $futureEvent->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                        }

                        // Add log    
                        if($updateValue){
                            setInfoLog('staff_event_class_clients',  $futureEvent->sec_id);   
                        }else{
                            setInfoLog('something wrong in class query',  $futureEvent->sec_id);   
                        }           
                        
                        //End 

                    } else if ($model == 'StaffEventSingleService') {
                        /*Get invoice related to event service*/
                        $invoice = Invoice::where('inv_client_id', $client->id)
                            ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                $query->where('inp_product_id', $futureEvent->sess_id)
                                    ->where('inp_type', 'service');
                            })->first();

                        /*Delete unpaid invoices*/
                        if ($invoice) 
                            $invoice->delete();

                        /* Remove event service clients*/
                        $updateValue = StaffEventSingleService::where('sess_id', $futureEvent->sess_id)
                            ->where('sess_client_id', $client->id)
                            ->update(['deleted_at' => createTimestamp(), 'sess_event_log' => 'Delete on account status changed to ' . $clientNewStatus . '.', 'sess_action_performed_by' => getLoggedUserName()]);
                        /* Manage repeat data */
                        $repeat = $futureEvent->repeat()->first();
                        $previousEvent = StaffEventSingleService::whereDate('sess_date', '<', $futureEvent->sess_date)->where('sess_sessr_id', $repeat['sessr_id'])->orderBy('sess_id', 'desc')->first();
                        if (count($previousEvent)) {
                            if($repeat->sessr_repeat_end_on_date == null){
                                $repeat->sessr_repeat_end             = 'On';
                                $repeat->sessr_repeat_end_after_occur = 0;
                                $repeat->sessr_repeat_end_on_date     = $previousEvent->sess_date;
                                $repeat->update();
                            }
                        } else {
                            $repeat->delete();
                        }
                        
                        // Add log      
                         if($updateValue){
                            setInfoLog('StaffEventSingleService',  $futureEvent->sess_id);   
                         }else{
                            setInfoLog('something wrong in service query',  $futureEvent->sess_id);   
                         }                                                   
                        //End 
                    }

                    $additionalHistoryText = 'while account status changed from '.$clientOldStatus.' to '.$clientNewStatus;
                    
                    # Creating history text
                    $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                    if ($historyText) {
                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureEvent]);
                    }
                }
            }
            # Set info log
            setInfoLog('Client membership limit reset to empty on account status changed from '.$clientOldStatus.' to '.$clientNewStatus,  $client->id);
            // $this->membershipLimitResetOnMembershipChange($client->id);
            $this->membershipLimitReset($client->id);
        }

        //client membership histroy
        $doneBy = "";
        if(Auth::check()) {
            if(Auth::user()->name)
                $doneBy .= Auth::user()->name;
            if(Auth::user()->last_name) 
                $doneBy .= ' '.Auth::user()->last_name;
        } else {
        $doneBy = 'SYSTEM';
        }
        
        $historytext = 'Membership status changed from '.$clientOldStatus.' to '.$clientNewStatus.' by '.$doneBy;
        DB::table('Cient_membership_history')->insertGetId(
            [
            'client_id' => $client->id, 
            'history' => $historytext,
            'created_at' =>date('Y-m-d H:i:s'),
            'updated_at' =>date('Y-m-d')
            ]
        );

        /* Delete Membership on client on-hold and inactive */
        $this->setMembershipDelete($clientNewStatus, $clientId);

        $msg['status'] = 'succsess';
        //$root=$request->currenturl;

        /*return redirect('clients')->with('message', 'success|Status has been changed successfully.');*/
        return json_encode($msg);
    }

    public function save(Request $request)
    {      
        $isError = false;
        $msg     = [];
        $notIn = ['mailinator','yopmail'];
        if (in_array(explode('.',explode('@',$request->email)[1])[0],$notIn))
        {
            $msg['status'] = 'error';
            $msg['errorData'][] = array('emailExist' => 'Please use your genuine email ids.');
            $isError = true;
        //    return redirect()->back()->with('flash_danger','Mailinator and Yopmail email not excepted here.');
        }
        if (($request->businessId && $request->businessId != Session::get('businessId')) || !Auth::user()->hasPermission(Auth::user(), 'create-client')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }
        $busnsData = Business::with('user')->find(Session::get('businessId'));
        $userLimitId = $busnsData->user->user_limit_id;
        $userLimitData = UserLimit::find($userLimitId);
        $counClients = Clients::where('business_id',Session::get('businessId'))->havingStatus('Active')->count();
        if($userLimitData && $counClients >= $userLimitData->maximum_users)
        {
            $isError = true;
            $msg['status']      = 'error';
            $msg['errorData'][] = array('maximumLimit' => 'Maximum Limit of User creation has been reached!');
        }
        if (!$isError) {
            $inputtedEmail = ($request->email) ? $request->email : $request->clientEmail;
            if (!$this->ifEmailAvailableInSameBusiness(['email' => $inputtedEmail, 'entity' => 'client'])) {
                $msg['status']      = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError            = true;
            }

            if ($this->ifPhoneExistInSameBusiness(['numb' => $request->numb, 'entity' => 'client'])) {
                $msg['status']      = 'error';
                $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
                $isError            = true;
            }

            if (!$isError) {
                if ($request->businessId != '') {
                    $parq = new Parq;

                    $dob = prepareDob($request->year, $request->month, $request->day);
                    $age = $this->calcAge($dob);

                    if (isset($request->goalHealthWellness) && $request->goalHealthWellness != '') {
                        $goals = groupValsToSingleVal($request->goalHealthWellness);
                    } else {
                        $goals = '';
                    }

                    $clientNewStatus = $this->getStatusForbackend($request->client_status);

                    $salesProcessDetails = calcSalesProcessRelatedStatus($clientNewStatus);
                    // dd($salesProcessDetails);
                    $insertData = array('business_id' => $request->businessId, 'firstname' => $request->first_name, 'lastname' => $request->last_name, 'phonenumber' => $request->numb, 'email' => $request->email, 'birthday' => $dob, 'age' => $age, 'account_status' => $clientNewStatus, 'is_bookbench_on' => 1);
                    if (isset($request->gender)) {
                        $insertData['gender'] = $request->gender;
                    }

                    if ($request->login_with_email) {
                        $insertData['login_with_email'] = $request->login_with_email;
                    }

                    $referral_name = '';
                    $referencewhere = '';
                    $otherName = '';

                    if (isset($request->referrer)) {
                        if ($request->referrer == 'mediapromotions' || $request->referrer == 'onlinesocial') {
                           $referencewhere = $request->referencewhere;
                        } elseif ($request->referrer == 'socialmedia') {
                            $otherName = $request->otherName;
                        } else {
                            if ($request->referralNetwork == 'Client') {
                                $referringClient = Clients::withTrashed()->find($request->clientId);
                                if ($referringClient) {
                                    $referral_name = $referringClient->firstname . ' ' . $referringClient->lastname;
                                }
                    
                            } else if ($request->referralNetwork == 'Staff') {
                                $staff = Staff::withTrashed()->find($request->staffId);
                                if ($staff) {
                                    $referral_name = $staff->first_name . ' ' . $staff->last_name;
                                }
                    
                            } else if ($request->referralNetwork == 'Professional network') {
                                $contact = Contact::withTrashed()->find($request->proId);
                                if ($contact)
                                {
                                    $referral_name = $contact->name;
                                }
                    
                            }
                        }
                    }
                    
                    if (isset($request->email_to_client)) {
                        $insertData['email_to_client']  = $request->email_to_client;
                        
                        $client_email = \Config::get('env-data.client_mail');
                        $to = $client_email;
                        $username = 'carlyle';
                        $subject  = "Details";
                        if($request->login_with_email == 1){
                            $allow = "Yes";
                        }else{
                            $allow= "No";
                        }
                        $message = $this->getMessage($request->all(),$goals,$allow,$referral_name,$referencewhere,$otherName);

                        $this->sendMail($username , $to , $message , $subject);
                    }else{
                        $insertData['email_to_client']  = 0;
                    }
                    /*$saleProcessConsultedDetails = calcSalesProcessRelatedStatus('consulted');
                    if($salesProcessDetails['saleProcessStepNumb'] > $saleProcessConsultedDetails['saleProcessStepNumb'])
                    $insertData['consultation_date'] = Carbon::now()->toDateString();*/

                    $addedClient = Clients::create($insertData);
                    Session::put('ifBussHasClients', true);
                    /****** Default Sales Settings *********/
                    $calendarData = CalendarSetting::where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
                    if($clients->sale_process_setts == null || $clients->sale_process_setts == ''){
                        $addedClient->sale_process_setts = $calendarData->sales_process_settings;
                    }

                    /* Create Birthday reminder for this client */
                    if ($dob != '') {
                        $currentYear = Carbon::now()->year;
                        $date        = $currentYear . '-' . $request->month . '-' . $request->day;
                        $taskName    = ucwords($insertData['firstname'] . ' ' . $insertData['lastname']) . ' Birthday';
                        $this->setTaskReminder($date, ['taskName' => $taskName, 'taskDueTime' => '09:00:00', 'taskNote' => '', 'remindBeforHour' => 1, 'clientId' => $addedClient->id]);
                    }

                    /* Start: create notes for this clint*/
                    if ($request->client_notes != '') {
                        $note_Id = $this->createNotes($request->client_notes, $addedClient->id, 'general', 'Added from create client');
                        $addedClient->update(['note_id' => $note_Id]);
                    }
                    /* End: create notes for this clint*/
                    /*$salesProcessHistory = ['clientId'=>$addedClient->id, 'toType'=>$salesProcessDetails['salesProcessType'], 'toStep'=>$salesProcessDetails['saleProcessStepNumb'], 'action'=>'upgrade', 'reason'=>'Client account created'];
                    $this->saveSalesProcess($salesProcessHistory);*/

                    /*if($salesProcessDetails['salesProcessType']){
                    $salesAttendanceSteps = salesAttendanceSteps();
                    $endKey = array_search($salesProcessDetails['salesProcessType'], $salesAttendanceSteps);
                    $salesAttendanceAddSteps = array_slice($salesAttendanceSteps, 0, $endKey+1);
                    if(count($salesAttendanceAddSteps)){
                    foreach($salesAttendanceAddSteps as $slug){
                    $thisDetails = calcSalesProcessRelatedStatus($slug);
                    $this->saveSalesProgress(['clientId'=>$addedClient->id, 'stepNumb'=>$thisDetails['saleProcessStepNumb']]); //Adding record in progress
                    }
                    $this->manageTeamSalesProcess($addedClient);
                    }
                    }*/
                    $this->processSalesProcessOnStatusChange($addedClient, '', $addedClient->account_status, 'Client account created');
                    $addedClient->update(['sale_process_setts' => null]);
                    $insertData = array('firstName' => $request->first_name, 'lastName' => $request->last_name, 'contactNo' => $request->numb, 'email' => $request->email, 'dob' => $dob);

                    if ($dob) {
                        $insertData['age'] = $this->calcAge($dob);
                    }

                    if (isset($request->gender)) {
                        $insertData['gender'] = $request->gender;
                    }

                    if ($goals) {
                        $insertData['goalHealthWellness'] = $goals;
                    }

                    if (isset($request->referralNetwork)) {
                        //dd($request->all());
                        $insertData['referralNetwork'] = $request->referralNetwork;
                        $insertData['referralId']      = $request->referralId;
                        $insertData['ref_Name']      = $request->referralName;

                    }
                    if (isset($request->referrer)) {
                        $insertData['hearUs'] = $request->referrer;
                        if ($request->referrer == 'mediapromotions' || $request->referrer == 'onlinesocial') {
                            $insertData['referencewhere'] = $request->referencewhere;
                            $insertData['referrerother']  = '';
                        } elseif ($request->referrer == 'socialmedia') {
                            $insertData['referencewhere'] = '';
                            $insertData['referrerother']  = $request->otherName;
                        } else {
                            $insertData['referencewhere'] = '';
                            $insertData['referrerother']  = '';
                        }

                    } else {
                        $insertData['hearUs']         = '';
                        $insertData['referencewhere'] = '';
                        $insertData['referrerother']  = '';
                    }

                     
                    
                    // dd('hi');
                    /*if($request->client_membership!=''){
                    $this->subscribeMembership( $addedClient->id, $request->client_membership, 'manual' );
                    }*/

                    //if(Auth::user()->hasPermission(Auth::user(), 'create-parq'))
                    $addedClient->parq()->create($insertData);

                    //$addedClient->risk_factor=$addedClient->RiskFactor;
                    $addedClient->update(['risk_factor' => $addedClient->RiskFactorr]);

                    // Save Default Client Menues
                    $defaulteMnues = ['parq','calendar_settings','invoice','benchmark'];
                    ClientMenu::create([
                        'client_id' => $addedClient->id,
                        'menues' => implode(',',$defaulteMnues),
                    ]);

                    if ($request->login_with_email) {
                        $this->callStoreUser(['name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'accountId' => $addedClient->id, 'password' => $request->clientNewPwd]);
                    }

                    $msg['status']   = 'added';
                    $msg['insertId'] = $addedClient->id;
                } else {
                    $insertId = $this->quickSaveClient($request);
                    if ($insertId) {
                        $msg['status']   = 'added';
                        $msg['insertId'] = $insertId;
                    }
                }
            }
        }
        return json_encode($msg);
    }

    public function sendMail($username , $to , $message , $subject){
        $username = $username;
       	$subject  = $subject;
        $to = $to;
        $message =  $message;

        $mail = new PHPMailer(true);
        try {
            //$mail->isSMTP(); // tell to use smtp
            $mail->CharSet = "utf-8"; // set charset to utf8
            $mail->Host = 'epictrainer.com';
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;
            $mail->Port = 2525; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'webmaster@epictrainer.com';
            $mail->Password = 'S[WlD3]Tf4*K';
            $mail->setFrom("noreply@epictrainer.com", "EPIC Trainer Team");
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            $mail->addAddress($to, $username);
            $mail->SMTPOptions= array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );
         $result =  $mail->send();
        // dd($result);
        } catch (phpmailerException $e) {
            dd($e);
            //return redirect($this->redirectPath());
        } catch (Exception $e) {
            dd($e);
            //return redirect($this->redirectPath());
        }  
    }

    public function getMessage($request, $goals, $allow, $referral_name,$referencewhere,$otherName){
            return view('Settings.client.send_mail',compact('request','goals','allow','referral_name','referencewhere','otherName'));
        }

    /*protected function setDateToRenwPeriod($givenDateCarb, $givenDate, $renwPeriod){
    if($givenDate < $renwPeriod){
    $addDays = $renwPeriod - $givenDate;
    $givenDateCarb->addDays($addDays);
    }
    else if($givenDate > $renwPeriod){
    $subtDays = $givenDate - $renwPeriod;
    $givenDateCarb->subDays($subtDays);
    }
    }*/

    protected function callStoreUser($data)
    {
        $this->storeUser(['name' => $data['name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'password' => $data['password']/*str_random(10)*/, 'userTypeId' => 0, 'businessId' => Session::get('businessId'), 'accountId' => $data['accountId'], 'type' => 'Client']);
    }

    public function coClients(Request $request)
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-client')) {
            if ($request->ajax()) {
                return [];
            } else {
                abort(404);
            }

        }

        //if(Session::has('businessId'))
        //$clients = Business::find(Session::get('businessId'))->clients()->where('id', '!=', $request->id)->get();
        //else
        //return [];

        $clients = Clients::OfBusiness()->where('id', '!=', $request->id)->get();

        return $this->prepareClientsList($clients);
    }

    public function allClients(Request $request)
    {
        return $this->allClientsFromTrait($request);
    }

    /**
     * Display client details
     *
     * @param Client id
     * @return show blade
     *
     */
    public function show(Request $request,$id)
    {
        if (!Auth::user()->hasPermission(Auth::user(), 'view-client')) {
            abort(404);
        }
        $invoiceTablelength = null;
        $c_id = $id;
        if(!($request->has('tab'))){
            $activeTab = 'overview';
        }else{
            $activeTab =  $request['tab'];
        }
        $countries  = \Country::getCountryLists();
        $currencies = \Currency::$currencies;
        $timezones  = \TimeZone::getTimeZone();

        $clients = Clients::findOrFailClient($id);
        $age = $this->calcAge($clients->birthday);
        $parq       = $clients->parq;
        $parq->update(['age' => $age]);
        if ($parq->dob != '0000-00-00') {
            $carbonDob        = Carbon::createFromFormat('Y-m-d', $parq->dob);
            $overviewDob      = $carbonDob->format('d M, Y');
            $parq->birthYear  = $carbonDob->year;
            $parq->birthMonth = $carbonDob->month;
            $parq->birthDay   = $carbonDob->day;
            $age              = $this->calcAge($parq->dob) . ' Year(s)';
        } else {
            $age         = '';
            $overviewDob = $parq->birthYear = $parq->birthMonth = $parq->birthDay = '';
        }
        $calendSettings           = $this->getCalendSettings();
        $calendarSettingVal       = $calendSettings['settings'];

      if(!($request->has('tab')) || $request['tab'] == 'membership' || $request['tab'] == 'appointments'){
        /****** Default Sales Settings *********/
        $paidInvoice = Invoice::with('invoiceitem')->where('inv_business_id',Session::get('businessId'))->where('inv_client_id', $c_id)->whereHas('invoiceitem',function($query){
            $query->where('inp_type','membership');
        })->orderBy('inv_id','DESC')->get();
        $lastPaidAmount = 0.0;
            foreach ($paidInvoice as $value) {
                if(count($value->payment()->get())){
                    $lastPaidAmount = $value->payment()->orderBy('pay_id','DESC')->pluck('pay_amount')->first();
                    break;
                }
            }
            $calendarData = CalendarSetting::where('cs_business_id',Session::get('businessId'))->select('sales_process_settings')->first();
            if($clients->sale_process_setts == null || $clients->sale_process_setts == ''){
                $clients->sale_process_setts = $calendarData->sales_process_settings;
                $this->salesProcSettingsUpdate($id,$calendarData->sales_process_settings);
                $clients->refresh();
                $clients->sale_process_setts = $calendarData->sales_process_settings;
            }

        
            $notify = $request->get('notify');
            if (isset($notify) && $notify) {
                $clients->activity = 1;
                $clients->save();
            }

        // if (!array_key_exists('order', $clients->SaleProcessSett) || (!count($clients->SaleProcessSett['order'] && ($clients->SaleProcessSett['indivCount'] || $clients->SaleProcessSett['teamCount'])))) {
        //     $temp                        = $clients->SaleProcessSett;
        //     $temp['order']               = json_decode('[{"id":"team-1"},{"id":"team-2"},{"id":"team-3"}]', 1);
        //     $temp['session']             = [6, 11, 7, 23, 8, 24];
        //     $clients->sale_process_setts = json_encode($temp);
        //     $clients->save();
        // }

            if (!$clients->risk_factor) {
                $clients->risk_factor = $clients->RiskFactorr;
                $clients->save();
            }

    
        $membershipClasses  = $membershipServices  = '';
        $membershipData = $clients->membership($clients->id);
        if(($membershipData->cm_number <= $membershipData->cm_discount_dur || $membershipData->cm_discount_dur == -1)&& $membershipData->cm_disc_per_class_amnt == null ){
         $memberShipUnitPrice = $this->perClassPrice($membershipData->cm_class_limit_length,$membershipData->cm_discounted_amount);
         ClientMember::where('id',$membershipData->id)->update(['cm_disc_per_class_amnt'=>$memberShipUnitPrice]);
          
        }
        $selectedMemberShip = $clients->membership($clients->id);
        $allMemberShipData  = $this->allMemberShipData();
       
       
        
        $nextMemberShip     = $clients->nextMembership($clients->id);
        $activeMemb         = $endDateReaching         = $dueDateReaching         = 0;


        //have to show the date when membership was assigned to client, not the current membership renewal date
        if ($selectedMemberShip) {

            $initialSubscriptionDate = ClientMember::where('cm_client_id', $id)
                ->where('cm_membership_id', $selectedMemberShip->cm_membership_id)
                ->orderBy('cm_start_date', 'asc')
                ->pluck('cm_start_date')
                ->first();

            # Get membership related classes
            $membership = MemberShip::where('id', $selectedMemberShip->cm_membership_id)
                ->with('classmember')
                ->first()
                ->toArray();

            if (count($membership) && count($membership['classmember'])) {
                $membershipClasses = [];
                foreach ($membership['classmember'] as $clsMember) {
                    $membershipClasses[] = '<a href="' . route('classes.show', (isset($clsMember['cl_id']) ? $clsMember['cl_id'] : '')) . '">' . (isset($clsMember['cl_name']) ? ucwords($clsMember['cl_name']) : '') . '</a>';
                }

                $membershipClasses = implode(', ', $membershipClasses);
            }

            // if($selectedMemberShip->cm_classes){
            //     $classes = json_decode( $selectedMemberShip->cm_classes, true);
            //     $membershipClasses = [];
            //     foreach($classes as $id => $name)
            //         $membershipClasses[] = '<a href="'.route('classes.show', $id).'">'.$name.'</a>';
            //     $membershipClasses = implode(', ', $membershipClasses);
            // }

            if ($selectedMemberShip->cm_services_limit) {
                $services           = json_decode($selectedMemberShip->cm_services_limit, true);
                $membershipServices = [];
                foreach ($services as $id => $value) {
                    $membershipServices[] = '<a href="' . route('services.show', $id) . '">' . Service::getServiceName($id) . '</a>';
                }

                $membershipServices = implode(', ', $membershipServices);
            }

            if ($selectedMemberShip->cm_status == 'Active' || $selectedMemberShip->cm_status == 'On Hold') {
                $activeMemb = $selectedMemberShip;

                if (count($allMemberShipData)) {
                    $allMemberShipData[$activeMemb->cm_membership_id]['length']     = $activeMemb->cm_validity_length;
                    $allMemberShipData[$activeMemb->cm_membership_id]['lengthUnit'] = $activeMemb->cm_validity_type;
                }

                if ($activeMemb->cm_status == 'Active') {
                    $now = new Carbon();

                    $endDateCarb = new Carbon($activeMemb->cm_end_date);
                    if ($endDateCarb->gt($now)) {
                        $prev5thDay = $endDateCarb->subDays(4);
                        if ($prev5thDay->lte($now)) {
                            if ($activeMemb->cm_auto_renewal == 'on') {
                                $endDateReaching = 1;
                            }
                            //Will be renewed
                            else {
                                $endDateReaching = -1;
                            }
                            //Will expire
                        }
                    }

                    $dueDateCarb = new Carbon($activeMemb->cm_due_date);
                    if ($dueDateCarb->gt($now)) {
                        $prev5thDay = $dueDateCarb->subDays(4);
                        if ($prev5thDay->lte($now)) {
                            $dueDateReaching = 1;
                        }

                    }
                }
            }

        }

        // get membership history data 
        $membershipHistoryData = DB::table('Cient_membership_history')->where('client_id', $clients->id)->orderBy('id', 'desc')->get();
        //
        $membershipHistory = ClientMember::where('cm_client_id', $clients->id)->latest()->get();
        $membershipRemoveHistory = ClientMember::withTrashed()->where('cm_client_id', $clients->id)->where('cm_status','Removed')->latest()->first();

        $paymenttype       = $this->getPaymentType();

        $this->deleteExpiringAspirantsEvents();

        $eventsListData = $this->eventsListForOverview($clients);
       // dd($clients->futureAppointments->toArray());
        $pastEvents      = $eventsListData['pastEvents'];
        $latestPastEvent = $eventsListData['latestPastEvent'];
        $latestPastEventInMembership = $eventsListData['latestPastEventInMembership'];
        // $latestPastEventInMembership = $eventsListData['latestPastEvent'];
        $futureEvents                = $eventsListData['futureEvents'];
        $oldestFutureEvent           = $eventsListData['oldestFutureEvent'];
        $oldestFutureEventInMembership = $eventsListData['oldestFutureEventInMembership'];
        // $oldestFutureEventInMembership = $eventsListData['oldestFutureEvent'];
        $modalLocsAreas                = $eventsListData['modalLocsAreas'];
        $eventRepeatIntervalOpt        = $eventsListData['eventRepeatIntervalOpt'];
        $makeUpCount                   = $clients->makeup_session_count;

         
        $lastLimitCount = 0;
        $nextLimitCount = 0;

        if (count($pastEvents) && $selectedMemberShip) {
            $type = $selectedMemberShip->cm_class_limit_type;
            $cmid = $selectedMemberShip->id;

            $lastLimitData = [];
            $startDate     = $endDate     = '';
            if ($type == "every_week") {
                $startDate = (new Carbon())->startOfWeek()->toDateString();
                $endDate   = (new Carbon())->toDateString();
            } elseif ($type == "every_month") {
                $startDate = (new Carbon())->startOfMonth()->toDateString();
                $endDate   = (new Carbon())->toDateString();
            }

            if ($startDate && $endDate) {
                $lastLimitData = $pastEvents->filter(function ($pastEvent) use ($cmid, $startDate, $endDate) {
                    $model = class_basename($pastEvent);
                    return ($model == 'StaffEventClass' && $pastEvent->deleted_at == null && $pastEvent->pivot->deleted_at == null && ($pastEvent->pivot->secc_client_status != 'Waiting') && $pastEvent->pivot->secc_cmid != 0 && $pastEvent->pivot->secc_epic_credit != 1 && $pastEvent->pivot->secc_with_invoice != 1 && $pastEvent->sec_date >= $startDate && $pastEvent->sec_date <= $endDate) || ($model == 'StaffEventSingleService' && $pastEvent->deleted_at == null && ($pastEvent->deleted_at == null || $pastEvent->sess_if_make_up) && $pastEvent->sess_booking_status == 'Confirmed' && $pastEvent->sess_date >= $startDate && $pastEvent->sess_date <= $endDate && $pastEvent->sess_cmid != 0 && $pastEvent->sess_epic_credit != 1 && $pastEvent->sess_with_invoice != 1);
                });

                $lastLimitCount = count($lastLimitData);
            }
        }

        if (count($futureEvents) && $selectedMemberShip) {
            $nextLimitData = [];
            $type          = $selectedMemberShip->cm_class_limit_type;
            $cmid          = $selectedMemberShip->id;
            $startDate     = $endDate     = '';
            if ($type == "every_week") {
                $startDate = (new Carbon())->toDateString();
                $endDate   = (new Carbon())->endOfWeek()->toDateString();
            } elseif ($type == "every_month") {
                $startDate = (new Carbon())->toDateString();
                $endDate   = (new Carbon())->endOfMonth()->toDateString();
            }

            if ($startDate && $endDate) {
                $nextLimitData = $futureEvents->filter(function ($futureEvent) use ($cmid, $startDate, $endDate) {
                    $model = class_basename($futureEvent);
                    return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null && $futureEvent->sec_date >= $startDate && $futureEvent->sec_date <= $endDate && $futureEvent->pivot->secc_cmid != 0 && $futureEvent->pivot->secc_epic_credit != 1 && $futureEvent->pivot->secc_with_invoice != 1) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null && $futureEvent->sess_booking_status == 'Confirmed' && $futureEvent->sess_client_attendance == 'Booked' && $futureEvent->sess_date > $startDate && $futureEvent->sess_date <= $endDate && $futureEvent->sess_cmid != 0 && $futureEvent->sess_epic_credit != 1 && $futureEvent->sess_with_invoice != 1);
                });

                $nextLimitCount = count($nextLimitData);
            }
        }

        $allClientArray = array();

        $selectedMembrshipDueDate = $selectedMemberShip && $selectedMemberShip->cm_status == 'Active' ? new Carbon($selectedMemberShip->cm_due_date) : '';

        /* Start: client future recuring classes */
        $futureClasses                = $clients->futureClasses;
        $futureRecureClassesProRate   = collect();
        $futureRecureClassesNextCycle = collect();
        $today                        = Carbon::now();

        $futureClasses = $futureClasses->filter(function ($value) {
            return $value->deleted_at == null && $value->pivot->deleted_at == null && $value->pivot->secc_if_recur == 1 && $value->pivot->secc_cmid != 0;
        });
        
        $recuringEvents = array_unique(array_column($futureClasses->toArray(), 'sec_secr_id'));
        // dd($recuringEvents);
        /* For membership update with pro rate */
        foreach ($recuringEvents as $recuringEvent) {
            $futureRecureClassesProRate[] = $futureClasses->where('sec_secr_id', $recuringEvent)->first();
        }

        // dd($futureRecureClassesProRate);
        /* Removing null values from recure classes with pro rate */
        $futureRecureClassesProRate = $futureRecureClassesProRate->filter(function ($value, $key) {
            return $value != null;
        });

        if (count($futureRecureClassesProRate)) {
            /* Sort future classes*/
            $futureRecureClassesProRate = $futureRecureClassesProRate->sort(function ($firstEvent, $secondEvent) {
                if ($firstEvent->eventDate === $secondEvent->eventDate) {
                    if ($firstEvent->eventTime === $secondEvent->eventTime) {
                        return 0;
                    }

                    return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
                }
                return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
            });
        }

        /* For membership update with next cycle */
        if ($selectedMembrshipDueDate) {
            $futureClasses = $futureClasses->filter(function ($value) use ($selectedMembrshipDueDate) {
                return $value->sec_date >= $selectedMembrshipDueDate->toDateString();
            });
        }

        foreach ($recuringEvents as $recuringEvent) {
            $futureRecureClassesNextCycle[] = $futureClasses->where('sec_secr_id', $recuringEvent)->first();
        }

        /* Removing null values from recure classes with next cycle */
        $futureRecureClassesNextCycle = $futureRecureClassesNextCycle->filter(function ($value, $key) {
            return $value != null;
        });

        if (count($futureRecureClassesNextCycle)) {
            /* Sort future classes*/
            $futureRecureClassesNextCycle = $futureRecureClassesNextCycle->sort(function ($firstEvent, $secondEvent) {
                if ($firstEvent->eventDate === $secondEvent->eventDate) {
                    if ($firstEvent->eventTime === $secondEvent->eventTime) {
                        return 0;
                    }

                    return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
                }
                return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
            });
        }
        /* End: client future recuring classes */
    
        $limitCount = $lastLimitCount + $nextLimitCount;
        // dd($nextLimitCount);
        $defaultAndComplServCount = Service::defaultAndComplCount();
       
        $serviceCancelReasons     = $calendSettings['cancelReasons'];

        $parq->isReferenceDeleted        = false;
        $clients->account_status_backend = $this->getStatusForbackend($clients->account_status, true);
        
    }
    if($parq->heightUnit == 'Imperial'){
        $height = explode('-',$parq->height);
        $parq->height_imperial_ft = $height[0];
        $parq->height_imperial_inch = $height[1];
    }else{
        $parq->height_metric = $parq->height;
    }

    if($parq->weightUnit == 'Imperial'){
        $parq->weight_imperial = $parq->weight;
    }else{
        $parq->weight_metric = $parq->weight;
    }
    if($request['tab'] == 'posture'){
        $dailyLog= PersonalMeasurement::where('client_id',$id)->latest()->first();
        if($dailyLog){
            $parq->weightUnit=$dailyLog->weightUnit;
            if($dailyLog->heightUnit == 'cm'){
                $parq->heightUnit ='Metric';
                $parq->height =$dailyLog->height;

            }else{
                $parq->heightUnit ='Imperial';
                // now find the number of feet...
                $feet = floor($dailyLog->height/12);

                // ..and then inches
                $inches = ($dailyLog->height%12);
                $parq->height =$inches.'-'.$feet;

            }
           
            $parq->weight=$dailyLog->weight;  
        }
    }

    if($request['tab'] == 'assess_progress'){
        $nutritional_journal = NutritionalJournal::where('client_id',$id)->first();
        $sleep_questionnaire = SleepQuestionnaire::where('client_id',$id)->first();
        $chronotype_survey = ChronotypeSurvey::where('client_id',$id)->first();
        $progress_image = FinalProgressPhoto::where('client_id',$id)->orderBy('id','desc')->get();
        if ($parq->referralNetwork == 'Client') {
            $client = Clients::withTrashed()->find($parq->referralId);
            if ($client != null) {
                $parq->clientName = $client->firstname . ' ' . $client->lastname;
                $parq->clientId   = $parq->referralId;
                if ($client->trashed()) {
                    $parq->isReferenceDeleted = true;
                }

            }
        } else if ($parq->referralNetwork == 'Staff') {
            $staff = Staff::withTrashed()->find($parq->referralId);
            if ($staff != null) {
                $parq->staffName = $staff->first_name . ' ' . $staff->last_name;
                $parq->staffId   = $parq->referralId;
                if ($staff->trashed()) {
                    $parq->isReferenceDeleted = true;
                }

            }
        } else if ($parq->referralNetwork == 'Professional network') {
            $contact = Contact::withTrashed()->find($parq->referralId);
            if ($contact != null) {
                $parq->proName = $contact->name;
                $parq->proId   = $parq->referralId;
                if ($contact->trashed()) {
                    $parq->isReferenceDeleted = true;
                }

            }
        }
        

        if ($parq->addrState) {
            $parq->stateName = \Country::getStateName($parq->country, $parq->addrState);
        }
        

        $parq->paIntensity = explode(',', $parq->paIntensity);

         $parq->headInjury = isset($parq->headInjury)&& $parq->headInjury != '' ? explode(',', $parq->headInjury):[];
        $parq->neckInjury = isset($parq->neckInjury)&& $parq->neckInjury !='' ?explode(',', $parq->neckInjury):[];
        $parq->shoulderInjury = isset($parq->shoulderInjury)&& $parq->shoulderInjury !='' ?explode(',', $parq->shoulderInjury):[];
        $parq->armInjury = isset($parq->armInjury)&& $parq->armInjury !='' ?explode(',', $parq->armInjury):[];
        $parq->handInjury = isset($parq->handInjury)&& $parq->handInjury !='' ?explode(',', $parq->handInjury):[];
        $parq->backInjury = isset($parq->backInjury)&& $parq->backInjury !='' ?explode(',', $parq->backInjury):[];
        $parq->hipInjury = isset($parq->hipInjury)&& $parq->hipInjury !='' ?explode(',', $parq->hipInjury):[];
        $parq->legInjury = isset($parq->legInjury)&& $parq->legInjury !='' ?explode(',', $parq->legInjury):[];
        $parq->footInjury = isset($parq->footInjury)&& $parq->footInjury !='' ?explode(',', $parq->footInjury):[];

        $parq->goalHealthWellnessRaw = $parq->goalHealthWellness;
        $parq->goalHealthWellness    = explode(',', $parq->goalHealthWellness);

        $parq->headImprove = isset($parq->headImprove)&& $parq->headImprove !='' ?explode(',', $parq->headImprove):[];
        $parq->neckImprove = isset($parq->neckImprove)&& $parq->neckImprove !='' ?explode(',', $parq->neckImprove):[];
        $parq->footImprove = isset($parq->footImprove)&& $parq->footImprove !='' ?explode(',', $parq->footImprove):[];
        $parq->legImprove = isset($parq->legImprove)&& $parq->legImprove !='' ?explode(',', $parq->legImprove):[];
        $parq->handImprove = isset($parq->handImprove)&& $parq->handImprove !='' ?explode(',', $parq->handImprove):[];
        $parq->backImprove = isset($parq->backImprove)&& $parq->backImprove !='' ?explode(',', $parq->backImprove):[];
        $parq->hipImprove = isset($parq->hipImprove)&& $parq->hipImprove !='' ?explode(',', $parq->hipImprove):[];
        $parq->hamstringsImprove = isset($parq->hamstringsImprove)&& $parq->hamstringsImprove !='' ?explode(',', $parq->hamstringsImprove):[];
        $parq->shouldersImprove = isset($parq->shouldersImprove)&& $parq->shouldersImprove !='' ?explode(',', $parq->shouldersImprove):[];
        $parq->armsImprove = isset($parq->armsImprove)&& $parq->armsImprove !='' ?explode(',', $parq->armsImprove):[];
        $parq->calvesImprove = isset($parq->calvesImprove)&& $parq->calvesImprove !='' ?explode(',', $parq->calvesImprove):[];
        
        $parq->quadsImprove = isset($parq->quadsImprove)&& $parq->quadsImprove !='' ?explode(',', $parq->quadsImprove):[];
        
        $parq->lifestyleImprove  = explode(',', $parq->lifestyleImprove);
        $parq->goalWantTobe      = explode(',', $parq->goalWantTobe);
        $parq->goalWantfeel      = explode(',', $parq->goalWantfeel);
        $parq->goalWantHave      = explode(',', $parq->goalWantHave);
        $parq->motivationImprove = explode(',', $parq->motivationImprove);
    }

        // $goalBuddyListData = $this->goalListing(['id' => $c_id]);
        // $goalListData      = $goalBuddyListData['goals'];
        // $goalDetailsData   = $goalBuddyListData['goalDetails'];
        // $allBusinessClient = $this->clientDetails();
        // $allClientArray    = $allBusinessClient['clientArray'];
        // Session::put('clientId', $clients->id);

        // /* Get all notes from clientNotes tabel use with ClientNote model */
        if (isUserEligible(['Admin'], 'view-client-notes')) {
            $allNotes = ClientNote::where('cn_client_id', $c_id)->where('cn_notes', '<>', '') /*->where('cn_user_id',Auth::id())*/->latest()->orderBy('cn_id', 'desc')->get();
        } else {
            $allNotes = [];
        }
        
        if($request['tab'] == 'notes'){
            $allnotesCat = DB::table('notes_category')->select('nc_name', 'nc_slug')
                ->where('nc_business_id', Session::get('businessId'))
                ->whereNull('deleted_at')
                ->get();
            $notesCat = [];
            if (count($allnotesCat)) {
                foreach ($allnotesCat as $value) {
                    $notesCat[$value->nc_slug] = $value->nc_name;
                }
            }
            $measurement_data = MeasurementFile::where('client_id',$clients->id)->orderBy('id','desc')->get();
        }
        if (count($allNotes)) {
            $noteArray = $allNotes->take(5);
        } else {
            $noteArray = [];
        }

        // /* Get makup data from makup table */
        if($request['tab'] == 'makeup'){
            $allMakeup = Makeup::where('makeup_client_id', $c_id)->orderBy('makeup_id', 'desc');
            if($request->search == 'search'){
                $credit_debit = $request->credit_debit;
                $classes_services = $request->classes_services;
                if(!empty($request->credit_debit) && $request->credit_debit !=''){
                    if($request->credit_debit == 'credit'){
                        $allMakeup = $allMakeup->where('makeup_amount','>',0);
                    }else{
                        $allMakeup = $allMakeup->where('makeup_amount','<=',0);
                    }
                }
                if(!empty($request->classes_services) && $request->classes_services != ''){
                    if($request->classes_services == 'class'){
                        $allMakeup = $allMakeup->where('makeup_purpose','class');
                    }elseif($request->classes_services == 'service'){
                        $allMakeup = $allMakeup->where('makeup_purpose','service');
                    }else{
                        $allMakeup = $allMakeup->where('makeup_purpose','manual');
                    }
                }
            }else{
                $credit_debit = '';
                $classes_services = '';
            }
            $allMakeup = $allMakeup->get();
        }

            $stepsBooked = $this->getStepsBooked($clients->id, $clients->sale_process_step);
        if($request['tab'] == 'movements'){

            $movementData = Movement::where('move_client_id', $c_id)->orderBy('id','desc')->get();

            $moveSteps    = MovementStepSetup::where('mss_client_id', $c_id)->pluck('mss_step_name')->first();
            $movementStep = [];
            if ($moveSteps) {
                $movementStep = json_decode($moveSteps);
            }
       }
       if($request['tab'] == 'activity-plan'){
        $exerciseData = $this->getExercisesOptions();
        $abWorkouts = ["" => "--Select--"]+AbWorkout::pluck('desc','id')->toArray();

       }

        // /* Get Data for Client Invoice list */
        if($request['tab'] == 'invoice'){
            $businessId  = Session::get('businessId');
            $totalAmount = 0;
            $totalPaid   = 0;
            $totalAmount = Invoice::where('inv_business_id', $businessId)->where('inv_client_id', $c_id)->sum('inv_total');
            $totalPaid   = Invoice::where('inv_business_id', $businessId)->where('inv_client_id', $c_id)->where('inv_status', 'Paid')->sum('inv_total');
    
            /* Set url in session */
            $currUrl = explode('/', url()->full());
            $invslug = $currUrl[count($currUrl) - 1];
            $fullUrl = url()->full();
        

            if ($invslug != '#invoices') 
                $fullUrl .= '#invoices';

            setPrevousUrl($fullUrl);
            $cookieName = 'invoice-list-status-filter';

            if(isset($_COOKIE[$cookieName]))
                $status = $_COOKIE[$cookieName];
            else
                $status = '';

            if (isset($_COOKIE['dueEndDate']) && isset($_COOKIE['dueSatrtDate'])) {
                $startDueDate = $_COOKIE['dueSatrtDate'];
                $endDueDate   = $_COOKIE['dueEndDate'];
            } else {
                $startDueDate = 'null';
                $endDueDate   = 'null';
            }

            if (isset($_COOKIE['clientInvoicePaginateLength']))
                $invoiceTablelength = (int)$_COOKIE['clientInvoicePaginateLength'];
            else 
                $invoiceTablelength = (int)$this->getTableLengthFromCookie($this->clientInvoiceCookieSlug);

            $query = Invoice::where('inv_business_id', $businessId)->where('inv_client_id', $c_id);
            if ($startDueDate != 'null' && $endDueDate != 'null') 
                $query->where('inv_due_date', '>=', $startDueDate)->where('inv_due_date', '<=', $endDueDate);
            if($status)
                $query->where('inv_status', $status);
            
            $allInvoices = $query->orderBy('inv_invoice_date', 'desc')->paginate($invoiceTablelength);
            $allInvoiceList = $query->get();
           
            /*If invoice filetr due start date and end date is not empty and result is empty then go to previous page.*/
            if($allInvoices->isEmpty()) {
                $fullUrl = url()->full();
                $invoiceUrl = strripos($fullUrl, '?') ? explode('?', $fullUrl )[0] : null;
                // if ($invoiceUrl) {
                //     $invoiceUrl .= '#invoices';
                //     return redirect($invoiceUrl);
                // }  
            }

        

            /* Start: invoice filters and create new invoice modal */
            $salestoolsinvoice = SalesToolsInvoice::where('sti_business_id',Session::get('businessId'))->first();
            if(count($salestoolsinvoice))
                $taxAppliedId = $salestoolsinvoice->sti_override;
            else
                $taxAppliedId = 0;

            $tax_all_data = $this->getTax($taxAppliedId);
            $taxdata = $tax_all_data['taxdata'];
            $alltax  = $tax_all_data['alltax'];
            $paymenttype = $this->getPaymentType();

            $loginUserInfo = Auth::user();
            $userInfo[$loginUserInfo->id] = $loginUserInfo->name;
        }
                    $discount = $this->getDiscount();

        /* End: invoice filters and create new invoice modal */

        /* Get all menus options */
        if($request['tab'] == 'settings'){
            $allMenuOptions = DB::table('menus')->select('menu_value', 'menu_name')->get();
            /* Get selected menus */
            $selectedMenu = $clients->clientMenu()->select('menues')->pluck('menues')->first();
            $selectedMenuOptions = $selectedMenu ? explode(',', $selectedMenu) : [];
        }
        // dd($clients->SalesSessionOrder);

        // if($request['tab'] == 'galleryTab'){
        //     $gallery = MeasurementGalleryImage::where('uploaded_by',$clients->id)->orderBy('id','desc')->get();
        // }
        // if($request['tab'] == 'BeforeAfter'){
        //     $before_after = MeasurementBeforeAfterImage::where('uploaded_by',$clients->id)->orderBy('id','desc')->get();
        // }

        
        if($request['tab'] == 'progress'){
            $progress = FinalProgressPhoto::where('client_id',$clients->id)->orderBy('id','desc')->get();
        }
        if($request['tab'] == 'posture'){
            $postures = PostureImage::where('client_id',$clients->id)->orderBy('id','desc')->get()->toArray();
        }
       
        if (!($parq->waiverTerms && hasPermission('create-parq'))) {
            return view('client_newprofile', compact('overviewDob', 'age', 'countries', 'timezones', 'currencies', 'clients', 'clinics', 'clientClinics', 'sessions', 'parq', 'pastEvents', 'latestPastEvent', 'latestPastEventInMembership', 'futureEvents', 'oldestFutureEvent', 'oldestFutureEventInMembership', 'modalLocsAreas', 'eventRepeatIntervalOpt', 'noteArray', 'makeUpCount', 'goalListData', 'goalDetailsData', 'allClientArray', 'allMemberShipData', 'selectedMemberShip', 'activeMemb', 'nextMemberShip', 'endDateReaching', 'dueDateReaching', 'defaultAndComplServCount', 'membershipClasses', 'membershipServices', 'membershipHistory', 'serviceCancelReasons', 'allNotes', 'calendarSettingVal', 'limitCount', 'allMakeup', 'stepsBooked', 'notesCat', 'movementStep', 'movementData', 'exerciseData', 'paymenttype', 'allInvoices', 'totalAmount', 'totalPaid', 'initialSubscriptionDate', 'futureRecureClassesProRate', 'futureRecureClassesNextCycle', 'taxdata', 'discount', 'alltax', 'paymenttype', 'userInfo', 'selectedMenuOptions',  'allMenuOptions' , 'membershipHistoryData','calendarData','abWorkouts','lastPaidAmount','gallery','before_after','activeTab','progress','membershipRemoveHistory','nutritional_journal','sleep_questionnaire','progress_image','chronotype_survey','credit_debit','classes_services','postures','measurement_data'));
        } else {
            return view('view_client_newprofile', compact('overviewDob', 'age', 'countries', 'timezones', 'currencies', 'clients', 'clinics', 'clientClinics', 'sessions', 'parq', 'pastEvents', 'latestPastEvent', 'latestPastEventInMembership', 'futureEvents', 'oldestFutureEvent', 'oldestFutureEventInMembership', 'modalLocsAreas', 'eventRepeatIntervalOpt', 'noteArray', 'makeUpCount', 'goalListData', 'goalDetailsData', 'allClientArray', 'allMemberShipData', 'selectedMemberShip', 'activeMemb', 'nextMemberShip', 'endDateReaching', 'dueDateReaching', 'defaultAndComplServCount', 'membershipClasses', 'membershipServices', 'membershipHistory', 'serviceCancelReasons', 'allNotes', 'calendarSettingVal', 'limitCount', 'allMakeup', 'stepsBooked', 'notesCat', 'movementStep', 'movementData', 'exerciseData', 'paymenttype', 'allInvoices', 'totalAmount', 'totalPaid', 'initialSubscriptionDate', 'futureRecureClassesProRate', 'futureRecureClassesNextCycle', 'taxdata', 'discount', 'alltax', 'paymenttype', 'userInfo', 'selectedMenuOptions',  'allMenuOptions','membershipHistoryData','calendarData','abWorkouts','lastPaidAmount','gallery','before_after','activeTab','progress','membershipRemoveHistory','nutritional_journal','sleep_questionnaire','progress_image','chronotype_survey','credit_debit','classes_services','postures','measurement_data'));
        }
    }

    public function showBenchmarks($id)
    {
        //$clients = Clients::findOrFail($id);
        $clients = Clients::findOrFailClient($id)->select('id', 'client_id', 'nps_manual_time', 'nps_day', 'nps_time_hour', 'nps_time_min', 'nps_automatic_time');
        return view('showClientsBenchmarks', compact('clients'));
    }

    public function updateField(Request $request)
    {
        $client = Clients::findClient($request->entityId);
        $prelogin_with_email = $client->login_with_email;
              

        if ($request->entityProperty == 'membStatus') {
            if (!$client || !isUserEligible(['Admin'], 'manage-client-membership')) {
                if ($request->ajax()) {
                    return [];
                } else {
                    abort(404);
                }

            }
        } else {
            if (!$client || !Auth::user()->hasPermission(Auth::user(), 'edit-client')) {
                if ($request->ajax()) {
                    return [];
                } else {
                    abort(404);
                }

            }
        }

        // dd($request->all());
        //$client = Clients::find($request->entityId);
        //$parq = $client->parq;
        $clientData = array();
        $parqData   = array();

        //$clientData = array('risk_factor' => Clients::calculateRiskFactor());

        if ($request->entityProperty == 'firstName') {
            $value      = $request->firstName;
            $clientData = array('firstname' => $value);
            $parqData   = array('firstName' => $value);
            if($client->login_with_email){
                $userData['name'] = $value;
            }
        } else if ($request->entityProperty == 'lastName') {
            $value      = $request->lastName;
            $clientData = array('lastname' => $value);
            $parqData   = array('lastName' => $value);
            if($client->login_with_email){
                $userData['last_name'] = $value;
            }
        } else if ($request->entityProperty == 'accStatus') {

            $clientOldStatus = $client->account_status;
            $value           = $request->accStatus;

            $clientNewStatus = $this->getStatusForbackend($request->accStatus);

            $clientStatusGraph                 = new ClientAccountStatusGraph();
            $clientStatusGraph->business_id    = $client->business_id;
            $clientStatusGraph->client_id      = $client->id;
            $clientStatusGraph->account_status = $clientNewStatus;
            $clientStatusGraph->save();

            # Remove from all future classes
            if ($value == 'on-hold' || $value == 'inactive') {
                $eventsListData = $this->eventsListForOverview($client);
                $futureEvents   = $eventsListData['futureEvents'];

                $futureEvents = $futureEvents->filter(function ($futureEvent) {
                    $model = class_basename($futureEvent);
                    return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null);
                }); 

                if (count($futureEvents)) {
                    foreach ($futureEvents as $futureEvent) {
                        $epicCashAmount = 0;
                        $model          = class_basename($futureEvent);
                        if ($model == 'StaffEventClass') {
                            /*Get invoice related to event class*/
                            $invoice = Invoice::where('inv_client_id', $client->id)
                                ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                    $query->where('inp_product_id', $futureEvent->sec_id)
                                        ->where('inp_type', 'class');
                                })->first();

                            /*Delete unpaid invoices*/
                            if ($invoice) 
                                $invoice->delete();

                            /* Remove event class clients*/
                           $updateValue =  DB::table('staff_event_class_clients')
                                ->where('secc_sec_id', $futureEvent->sec_id)
                                ->where('secc_client_id', $client->id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on account status changed to ' . $value . '.', 'secc_action_performed_by' => getLoggedUserName()]);
                            
                            /* remove client from class event table */
                            if ($futureEvent->sec_secr_id != 0) {
                                $repeat         = $futureEvent->repeat()->first();
                                $removeClientId = $client->id;
                                if (count($repeat) && $repeat->secr_client_id != '') {
                                    $clientsReccur = json_decode($repeat->secr_client_id, true);
                                    if (count($clientsReccur)) {
                                        foreach ($clientsReccur as $key => $recurrClassData) {
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

                            // Confirm Waiting Clients
                            $clients_to_auto_confirmId = [];
                            $confirmed_clientsCount    = 0;
                            foreach ($futureEvent->clientsOldestFirst as $clientsOldestFirst) {
                                if ($clientsOldestFirst->id != $client->id) {
                                    if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                        $confirmed_clientsCount++;
                                    } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $futureEvent->sec_capacity) {
                                        $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                        $confirmed_clientsCount++;
                                    }
                                }
                            }
                            if (count($clients_to_auto_confirmId)) {
                                DB::table('staff_event_class_clients')->where('secc_sec_id', $futureEvent->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                            }

                            // Add log    
                            if($updateValue){
                                setInfoLog('staff_event_class_clients',  $futureEvent->sec_id);   
                            }else{
                                setInfoLog('something wrong in class query',  $futureEvent->sec_id);   
                            }           
                            
                            //End 

                        } else if ($model == 'StaffEventSingleService') {
                            /*Get invoice related to event service*/
                            $invoice = Invoice::where('inv_client_id', $client->id)
                                ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                    $query->where('inp_product_id', $futureEvent->sess_id)
                                        ->where('inp_type', 'service');
                                })->first();

                            /*Delete unpaid invoices*/
                            if ($invoice) 
                                $invoice->delete();

                            /* Remove event service clients*/
                          $updateValue = StaffEventSingleService::where('sess_id', $futureEvent->sess_id)
                                ->where('sess_client_id', $client->id)
                                ->update(['deleted_at' => createTimestamp(), 'sess_event_log' => 'Delete on account status changed to ' . $value . '.', 'sess_action_performed_by' => getLoggedUserName()]);
                            
                            /* Manage repeat data */
                            $repeat = $futureEvent->repeat()->first();
                            $previousEvent = StaffEventSingleService::whereDate('sess_date', '<', $futureEvent->sess_date)->where('sess_sessr_id', $repeat['sessr_id'])->orderBy('sess_id', 'desc')->first();
                            if (count($previousEvent)) {
                                if($repeat->sessr_repeat_end_on_date == null){
                                    $repeat->sessr_repeat_end             = 'On';
                                    $repeat->sessr_repeat_end_after_occur = 0;
                                    $repeat->sessr_repeat_end_on_date     = $previousEvent->sess_date;
                                    $repeat->update();
                                }
                            } else {
                                $repeat->delete();
                            }
                            // Add log      
                             if($updateValue){
                                setInfoLog('StaffEventSingleService',  $futureEvent->sess_id);   
                             }else{
                                setInfoLog('something wrong in service query',  $futureEvent->sess_id);   
                             }                                                   
                            //End 
                        }

                        $additionalHistoryText = 'while account status changed from '.$clientOldStatus.' to '.$value;
                        
                        # Creating history text
                        $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                        if ($historyText) {
                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureEvent]);
                        }
                    }
                }
                # Set info log
                setInfoLog('Client membership limit reset to empty on account status changed from '.$clientOldStatus.' to '.$value,  $client->id);
                // $this->membershipLimitResetOnMembershipChange($client->id);
                // $this->membershipLimitReset($client->id);
            }

            //Reset Client MemberShip Limit
            $this->membershipLimitReset($client->id);

            // if($value == 'active')
            //     $this->membershipLimitResetOnMembershipChange($client->id);

             //client membership histroy
                $doneBy = "";
                if(Auth::check()) {
                    if(Auth::user()->name)
                        $doneBy .= Auth::user()->name;
                    if(Auth::user()->last_name) 
                        $doneBy .= ' '.Auth::user()->last_name;
                } else {
                $doneBy = 'SYSTEM';
                }
                
                $historytext = 'Membership status changed from '.$clientOldStatus.' to '.$value.' by '.$doneBy;
                DB::table('Cient_membership_history')->insertGetId(
                    [
                    'client_id' => $client->id, 
                    'history' => $historytext,
                    'created_at' =>date('Y-m-d H:i:s'),
                    'updated_at' =>date('Y-m-d')
                    ]
                );
                
                //end
            /* Delete Membership on client on-hold and inactive */
            $this->setMembershipDelete($request->accStatus, $client->id);

            $clientData = array('account_status' => $clientNewStatus);
            $value      = $request->accStatus . '|' . $clientData['account_status'];

        } else if ($request->entityProperty == 'gender') {
            $value      = $request->gender;
            $clientData = array('gender' => $value);
            $parqData   = array('gender' => $value);
        } else if ($request->entityProperty == 'goals') {
            if ($request->goals != '') {
                $value = groupValsToSingleVal($request->goals);
            } else {
                $value = '';
            }

            $parqData = array('goalHealthWellness' => $value);
        } else if ($request->entityProperty == 'dob') {
            /*if($request->day != '' && $request->month != '' && $request->year != '')
            $value = $parq->setDob($request->year, $request->month, $request->day);
            else
            $value = '';*/
            $value = prepareDob($request->year, $request->month, $request->day);

            $parqData        = array('dob' => $value);
            $clientData      = array('birthday' => $value);
            $age             = $this->calcAge($value);
            $parqData['age'] = $age;

            /* Create Birthday reminder for this client */
            if ($value != '') {
                $currentYear = Carbon::now()->year;
                $date        = $currentYear . '-' . $request->month . '-' . $request->day;
                $taskName    = ucwords($client->firstname . ' ' . $client->lastname) . ' Birthday';
                $this->setTaskReminder($date, ['taskName' => $taskName, 'taskDueTime' => '09:00:00', 'taskNote' => '', 'remindBeforHour' => 1, 'clientId' => $client->id]);
            }
        } else if ($request->entityProperty == 'email') {
            if (!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'client', 'id' => $request->entityId])) {
                return json_encode([
                    'status'  => 'emailExistError',
                    'message' => 'This email is already in use',
                ]);
            }

            $value      = $request->email;
            $clientData = array('email' => $value);
            $parqData   = array('email' => $value);
            if($client->login_with_email){
                $userData['email'] = $value;
            }
        } else if ($request->entityProperty == 'phone') {
            if ($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'client', 'id' => $request->entityId])) {
                return json_encode([
                    'status'  => 'numbExistError',
                    'message' => 'This phone number is already in use',
                ]);
            }

            $value      = $request->phone;
            $clientData = array('phonenumber' => $value);
            $parqData   = array('contactNo' => $value);
        } else if ($request->entityProperty == 'occupation') {
            $value      = $request->occupation;
            $clientData = array('occupation' => $value);
            $parqData   = array('occupation' => $value);
        } else if ($request->entityProperty == 'membStatus') {
            $value = $request->membStatus;
            // dd($value);
            # Remove from all future classes
            if ($value == 'On Hold' || $value == 'Inactive') {
                $eventsListData = $this->eventsListForOverview($client);
                $futureEvents   = $eventsListData['futureEvents'];

                $futureEvents = $futureEvents->filter(function ($futureEvent) {
                    $model = class_basename($futureEvent);
                    return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null);
                });

                if (count($futureEvents)) {
                    foreach ($futureEvents as $futureEvent) {
                        $epicCashAmount = 0;
                        $model          = class_basename($futureEvent);
                        if ($model == 'StaffEventClass') {
                            /*Get invoice related to event class*/
                            $invoice = Invoice::where('inv_client_id', $client->id)
                                ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                    $query->where('inp_product_id', $futureEvent->sec_id)
                                        ->where('inp_type', 'class');
                                })->first();

                            /*Delete invoices*/
                            if ($invoice) 
                                $invoice->delete();

                            /* Remove event class clients*/
                            DB::table('staff_event_class_clients')
                                ->where('secc_sec_id', $futureEvent->sec_id)
                                ->where('secc_client_id', $client->id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on membership status changed to ' . $value . '.', 'secc_action_performed_by' => getLoggedUserName()]);
                            /* remove client from class event table */
                            if ($futureEvent->sec_secr_id != 0) {
                                $repeat         = $futureEvent->repeat()->first();
                                $removeClientId = $client->id;
                                if (count($repeat) && $repeat->secr_client_id != '') {
                                    $clientsReccur = json_decode($repeat->secr_client_id, true);
                                    if (count($clientsReccur)) {
                                        foreach ($clientsReccur as $key => $recurrData) {
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

                            // Confirm Waiting Clients
                            $clients_to_auto_confirmId = [];
                            $confirmed_clientsCount    = 0;
                            foreach ($futureEvent->clientsOldestFirst as $clientsOldestFirst) {
                                if ($clientsOldestFirst->id != $client->id) {
                                    if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                        $confirmed_clientsCount++;
                                    } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $futureEvent->sec_capacity) {
                                        $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                        $confirmed_clientsCount++;
                                    }
                                }
                            }
                            if (count($clients_to_auto_confirmId)) {
                                DB::table('staff_event_class_clients')->where('secc_sec_id', $futureEvent->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                            }

                        } else if ($model == 'StaffEventSingleService') {
                            /*Get invoice related to event service*/
                            $invoice = Invoice::where('inv_client_id', $client->id)
                                ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                    $query->where('inp_product_id', $futureEvent->sess_id)
                                        ->where('inp_type', 'service');
                                })->first();

                            /*Delete unpaid invoices*/
                            if ($invoice) 
                                $invoice->delete();

                            /* Remove event service clients*/
                            StaffEventSingleService::where('sess_id', $futureEvent->sess_id)
                                ->where('sess_client_id', $client->id)
                                ->update(['deleted_at' => createTimestamp(), 'sess_event_log' => 'Delete on memebrship status changed to ' . $value . '.', 'sess_action_performed_by' => getLoggedUserName()]);
                            /* Manage repeat data */
                            $repeat = $futureEvent->repeat()->first();
                            $previousEvent = StaffEventSingleService::whereDate('sess_date', '<', $futureEvent->sess_date)->where('sess_sessr_id', $repeat['sessr_id'])->orderBy('sess_id', 'desc')->first();
                            if (count($previousEvent)) {
                                if($repeat->sessr_repeat_end_on_date == null){
                                    $repeat->sessr_repeat_end             = 'On';
                                    $repeat->sessr_repeat_end_after_occur = 0;
                                    $repeat->sessr_repeat_end_on_date     = $previousEvent->sess_date;
                                    $repeat->update();
                                }
                            } else {
                                $repeat->delete();
                            }
                            
                        }

                         $additionalHistoryText = 'while membership status changed to '.$value;
                        
                        # Creating history text
                        $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                        if ($historyText) {
                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureEvent]);
                        }
                    }
                }

                # Set info log
                setInfoLog('Client membership limit reset to empty on membership status changed to '.$value,  $client->id);
                // $this->membershipLimitResetOnMembershipChange($client->id);
                $this->membershipLimitReset($client->id);
            }
            //client membership histroy
            $doneBy = "";
            if(Auth::check()) {
                if(Auth::user()->name)
                    $doneBy .= Auth::user()->name;
                if(Auth::user()->last_name) 
                    $doneBy .= ' '.Auth::user()->last_name;
            } else {
            $doneBy = 'SYSTEM';
            }
            
            $historytext = 'Membership status changed to '.$value.' by '.$doneBy;
            DB::table('Cient_membership_history')->insertGetId(
                [
                    'client_id' => $client->id, 
                    'history' => $historytext,
                    'created_at' =>date('Y-m-d H:i:s'),
                    'updated_at' =>date('Y-m-d')
                ]
            );
            
            //end
            $currMemb = $client->membership($client->id);
            if ($currMemb) {
                $currMemb->cm_status = $value;
                $currMemb->save();
            }

            if ($value == 'Active') {
                // $this->membershipLimitResetOnMembershipChange($client->id);
                //dd(1);
                $this->membershipLimitReset($client->id);
            }

        } else if ($request->entityProperty == 'consultation') {
            /*$value = $request->consultationDate;
            $dt = Carbon::parse($request->consultationDate);

            if($client->consul_exp_type != null){
            if($client->consul_exp_type == 'day')
            $exp_date = $dt->addDays($client->consul_exp_duration)->toDateString();

            if($client->consul_exp_type == 'week')
            $exp_date = $dt->addWeeks($client->consul_exp_duration)->toDateString();

            if($client->consul_exp_type == 'month')
            $exp_date = $dt->addMonths($client->consul_exp_duration)->toDateString();
            }
            else
            $exp_date = $request->consultationDate;

            $clientData = array('consultation_date' => $value,'consul_exp_date'=>$exp_date); */
            // dd($this->calcConsExpir($request->consultationDate, $client->consul_exp_duration, $client->consul_exp_type));
            $clientData = array('consultation_date' => $request->consultationDate, 'consul_exp_date' => $this->calcConsExpir($request->consultationDate, $client->consul_exp_duration, $client->consul_exp_type));
        } else if ($request->entityProperty == 'referralNetwork') {
            $value                       = '';
            $parqData['referralNetwork'] = $request->referralNetwork;
            $parqData['referralId']      = $request->referralId;
            $parqData['ref_Name']      = $request->referralName;
            $parqData['hearUs']          = $request->referrer;
            if ($parqData['hearUs'] == "onlinesocial" || $parqData['hearUs'] == "mediapromotions") {
                $parqData['referencewhere'] = $request->referencewhere;
                $parqData['referrerother']  = "";
            } else if ($parqData['hearUs'] == "socialmedia") {
                $parqData['referrerother']  = $request->otherName;
                $parqData['referencewhere'] = "";
            }
        } else if ($request->entityProperty == 'consultExpDuration') {
            $value      = '';
            $clientData = array('consul_exp_date' => $request->cun_exp_date, 'consul_exp_duration' => $request->duration, 'consul_exp_type' => $request->durationType);
        } else if ($request->entityProperty == 'sales-process-date') {
            $value = $request->salesProcessDate;
            $dt    = Carbon::parse($request->salesProcessDate)->toDateTimeString();

            if ($request->stepNumber == 3) {
                $clientData = array('consultation_date' => $dt, 'consul_exp_date' => $this->calcConsExpir($dt, $client->consul_exp_duration, $client->consul_exp_type));
            } else {
                SalesProcessProgress::where('spp_client_id', $request->entityId)->where('spp_step_numb', $request->stepNumber)->update(['spp_comp_date' => $dt]);
            }

        }else if ($request->entityProperty == 'ldcStatus') {
            $value = $request->ldcStatus;
            $clientData['ldc_status'] = $request->ldcStatus;
            $clientData['ldc_session_id'] = $request->ldc_list;
            if($request->ldcStatus == Clients::LDC_INACTIVE){
                $eventsListData = $this->eventsListForOverview($client);
                $futureEvents   = $eventsListData['futureEvents'];

                $futureEvents = $futureEvents->filter(function ($futureEvent) {
                    $model = class_basename($futureEvent);
                    return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null && $futureEvent->pivot->is_ldc == 1);
                });
                if (count($futureEvents)) {
                    foreach ($futureEvents as $futureEvent) {
                        $model          = class_basename($futureEvent);
                        if ($model == 'StaffEventClass') {
                            /* Remove event class clients*/
                            $updateValue =  DB::table('staff_event_class_clients')
                                ->where('secc_sec_id', $futureEvent->sec_id)
                                ->where('secc_client_id', $client->id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on LDC status changed to Inactive.', 'secc_action_performed_by' => getLoggedUserName()]);
                            // Confirm Waiting Clients
                            $clients_to_auto_confirmId = [];
                            $confirmed_clientsCount    = 0;
                            foreach ($futureEvent->clientsOldestFirst as $clientsOldestFirst) {
                                if ($clientsOldestFirst->id != $client->id) {
                                    if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                        $confirmed_clientsCount++;
                                    } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $futureEvent->sec_capacity) {
                                        $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                        $confirmed_clientsCount++;
                                    }
                                }
                            }
                            if (count($clients_to_auto_confirmId)) {
                                DB::table('staff_event_class_clients')->where('secc_sec_id', $futureEvent->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                            }

                            // Add log    
                            if($updateValue){
                                setInfoLog('staff_event_class_clients',  $futureEvent->sec_id);   
                            }else{
                                setInfoLog('something wrong in class query',  $futureEvent->sec_id);   
                            }
                        }
                        $additionalHistoryText = 'while LDC status changed from Active to Inactive.';
                        
                        # Creating history text
                        $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                        if ($historyText) {
                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureEvent]);
                        }
                    }
                }
            }
        }
        else if ($request->has('login_with_email')) {
            if (isset($request->login_with_email) && $request->login_with_email) {
                    $client->login_with_email = $request->login_with_email;
                } else {
                    $client->login_with_email = 0;
                }
            if(!$prelogin_with_email && $request->login_with_email)
            {
            
                    $this->callStoreUser(['name' => $client->firstname, 'last_name' => $client->lastname, 'email' => $client->email, 'accountId' => $request->entityId, 'password' => $request->clientNewPwd]);
                }
                 else if($prelogin_with_email) {
                    if (!$request->login_with_email) {
                        $user = $client->user;
                        if ($user) {
                            $user->forceDelete();
                        }

                    } else if ($request->login_with_email) {

                        $this->entityLogin_tableRecordUpdate(['entity' => $client, 'firstName' => $client->firstname, 'lastName' => $client->lastname,'email' => $client->email , 'password' => $request->clientNewPwd]);
                    }

                }
        } 

       


        /*else if($request->entityProperty == 'membershipOption'){
        $memberShip = MemberShip::with('classmember', 'servicemember')->where('id', $request->membershipOption)->get()->first();
        $addMemberShiplient = $this->subscribeMembership( $client->id, $memberShip, 'manual' );
        $value = $request->membershipOption.'|'.$memberShip->me_membership_label;
        }*/
        //$clientData = array('risk_factor' => $client->RiskFactor);

        if (count($clientData)) {
            $client->update($clientData);
        }
        //if(count($parqData) && (isSuperUser() || Auth::user()->hasPermission(Auth::user(), 'edit-parq')))
        if (count($parqData) && hasPermission('edit-parq'))
        //$parq->update($parqData);
        {
            $client->parq->update($parqData);

        }
        
        $client->update(['risk_factor' => $client->RiskFactorr]);

        if(isset($userData) && count($userData)){
            $client->user->update($userData);
        }
            
        if ($request->entityProperty == 'accStatus' && $clientOldStatus != $clientNewStatus) {
            //$data = $this->processSalesProcessOnStatusChange($client, ['clientOldStatus' => $clientOldStatus, 'clientNewStatus' => $clientNewStatus]);
            $data = $this->processSalesProcessOnStatusChange($client, $clientOldStatus, $clientNewStatus, 'realtime edit');
            if (count($data)) {
                return json_encode([
                    'status'             => 'updated',
                    'value'              => $value,
                    'consultationDate'   => $data['consultationDate'],
                    'oldSaleProcessStep' => $data['oldSaleProcessStep'],
                    'stepCompleted'      => $data['newSaleProcessStep'],
                    'action'             => $data['action'],
                    'salesProcessDate'   => $data['salesProcessDate'],
                    'stepsBooked'        => $this->getStepsBooked($client->id, $client->sale_process_step),
                ]);
            }
        }

        return json_encode([
            'status' => 'updated',
            'value'  => $value,
        ]);
    }

    public function edit($id)
    {
        $client = Clients::findOrFailClient($id);
        //dd($client->parq);

        if (!Auth::user()->hasPermission(Auth::user(), 'edit-client')) {
            abort(404);
        }

        //if(!Session::has('businessId'))
        //return redirect('settings/business/create');

        //$client = Clients::with('parq')->find($id);
        //if($client){
        //$business = Business::with('locations')->find(Session::get('businessId'));
        //$businessId = $business->id;
        $businessId = Session::get('businessId');

        $client->account_status_backend = $this->getStatusForbackend($client->account_status, true);

        if ($client->birthday != '0000-00-00') {
            $carbonDob          = Carbon::createFromFormat('Y-m-d', $client->birthday);
            $client->birthYear  = $carbonDob->year;
            $client->birthMonth = $carbonDob->month;
            $client->birthDay   = $carbonDob->day;
        } else {
            $client->birthYear = $client->birthMonth = $client->birthDay = '';
        }

        $client->goalHealthWellness = explode(',', $client->parq->goalHealthWellness);
        if ($client->parq->referralNetwork == 'Client') {
            $referringClient = Clients::withTrashed()->find($client->parq->referralId);
            if ($referringClient) {
                $client->parq->referralName = $referringClient->firstname . ' ' . $referringClient->lastname;
            }

        } else if ($client->parq->referralNetwork == 'Staff') {
            $staff = Staff::withTrashed()->find($client->parq->referralId);
            if ($staff) {
                $client->parq->referralName = $staff->first_name . ' ' . $staff->last_name;
            }

        } else if ($client->parq->referralNetwork == 'Professional network') {
            $contact = Contact::withTrashed()->find($client->parq->referralId);
            if ($contact)
            //$client->parq->referralName = $contact->contact_name;
            {
                $client->parq->referralName = $contact->name;
            }

        }
        /*$allMemberShipData=['' => '-- Select --'];
        $memberShip=MemberShip::where('me_business_id', Session::get('businessId'))->get();
        foreach ($memberShip as $mValue) {
        $allMemberShipData[$mValue['id']]=$mValue->me_membership_label;

        }

        $selectedMemberShip=ClientMember::where('cm_client_id', $client->id)->select('cm_membership_id')->latest()->first();*/
        //dd($client->note);
        // $client->notes = $client->note->
        if (!$client->login_with_email) {
            $pwd = genPwd();
        }

        $allMemberShipData  = $this->allMemberShipData();
        $activeMemb         = 0;
        $selectedMemberShip = $client->membership($id);
        if ($selectedMemberShip && ($selectedMemberShip->cm_status == 'Active' || $selectedMemberShip->cm_status == 'On Hold')) {
            $activeMemb = $selectedMemberShip;
            if (count($allMemberShipData)) {
                $allMemberShipData[$activeMemb->cm_membership_id]['length']     = $activeMemb->cm_validity_length;
                $allMemberShipData[$activeMemb->cm_membership_id]['lengthUnit'] = $activeMemb->cm_validity_type;
            }
        }

        $paymenttype = $this->getPaymentType();

        return view('Settings.client.edit', compact('client', 'businessId', 'pwd', 'allMemberShipData', 'paymenttype', 'activeMemb' /*,'allMemberShipData','selectedMemberShip'*/));
        //}
    }

    public function update($id, Request $request)
    {    
        $isError = false;
        $msg     = [];
        $historyText      = '';
        $alertHistoryText = '';
        $notIn = ['mailinator','yopmail'];
        if (in_array(explode('.',explode('@',$request->email)[1])[0],$notIn))
        {
            $msg['status'] = 'error';
            $msg['errorData'][] = array('emailExist' => 'Please use your genuine email ids.');
            $isError = true;
        //    return redirect()->back()->with('flash_danger','Mailinator and Yopmail email not excepted here.');
        }
        $client = Clients::findClient($id, $request->businessId);
        //dd( $client);

        if (!$client || !Auth::user()->hasPermission(Auth::user(), 'edit-client')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            if (!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'client', 'id' => $id])) {
                $msg['status']      = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError            = true;
            }

            if ($this->ifPhoneExistInSameBusiness(['numb' => $request->numb, 'entity' => 'client', 'id' => $id])) {
                $msg['status']      = 'error';
                $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
                $isError            = true;
            }

            if (!$isError) {
                $clientNewStatus = '';
                $clientOldStatus = $client->account_status;
                if ($request->has('client_status')) {
                    $clientNewStatus = $this->getStatusForbackend($request->client_status);
                } else {
                    $clientNewStatus = $clientOldStatus;
                }

                /* Client status on-hold and inactive delete membership */
                # Remove from all future classes
                if ($request->client_status == 'on-hold' || $request->client_status == 'inactive') {
                    $eventsListData = $this->eventsListForOverview($client);
                    $futureEvents   = $eventsListData['futureEvents'];

                    $futureEvents = $futureEvents->filter(function ($futureEvent) {
                        $model = class_basename($futureEvent);
                        return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null);
                    }); 

                    if (count($futureEvents)) {
                        foreach ($futureEvents as $futureEvent) {
                            $epicCashAmount = 0;
                            $model          = class_basename($futureEvent);
                            if ($model == 'StaffEventClass') {
                                /*Get invoice related to event class*/
                                $invoice = Invoice::where('inv_client_id', $client->id)
                                    ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                        $query->where('inp_product_id', $futureEvent->sec_id)
                                            ->where('inp_type', 'class');
                                    })->first();

                                /*Delete unpaid invoices*/
                                if ($invoice) 
                                    $invoice->delete();

                                /* Remove event class clients*/
                               $updateValue =  DB::table('staff_event_class_clients')
                                    ->where('secc_sec_id', $futureEvent->sec_id)
                                    ->where('secc_client_id', $client->id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on account status changed to ' . $clientNewStatus . '.', 'secc_action_performed_by' => getLoggedUserName()]);

                                /* remove client from class event table */
                                if ($futureEvent->sec_secr_id != 0) {
                                    $repeat         = $futureEvent->repeat()->first();
                                    $removeClientId = $client->id;
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

                                // Confirm Waiting Clients
                                $clients_to_auto_confirmId = [];
                                $confirmed_clientsCount    = 0;
                                foreach ($futureEvent->clientsOldestFirst as $clientsOldestFirst) {
                                    if ($clientsOldestFirst->id != $client->id) {
                                        if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                            $confirmed_clientsCount++;
                                        } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $futureEvent->sec_capacity) {
                                            $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                            $confirmed_clientsCount++;
                                        }
                                    }
                                }
                                if (count($clients_to_auto_confirmId)) {
                                    DB::table('staff_event_class_clients')->where('secc_sec_id', $futureEvent->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                                }

                                // Add log    
                                if($updateValue){
                                    setInfoLog('staff_event_class_clients',  $futureEvent->sec_id);   
                                }else{
                                    setInfoLog('something wrong in class query',  $futureEvent->sec_id);   
                                }           
                                
                                //End 

                            } else if ($model == 'StaffEventSingleService') {
                                /*Get invoice related to event service*/
                                $invoice = Invoice::where('inv_client_id', $client->id)
                                    ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                        $query->where('inp_product_id', $futureEvent->sess_id)
                                            ->where('inp_type', 'service');
                                    })->first();

                                /*Delete unpaid invoices*/
                                if ($invoice) 
                                    $invoice->delete();

                                /* Remove event service clients*/
                                $updateValue = StaffEventSingleService::where('sess_id', $futureEvent->sess_id)
                                    ->where('sess_client_id', $client->id)
                                    ->update(['deleted_at' => createTimestamp(), 'sess_event_log' => 'Delete on account status changed to ' . $clientNewStatus . '.', 'sess_action_performed_by' => getLoggedUserName()]);
                                /* Manage repeat data */
                                $repeat = $futureEvent->repeat()->first();
                                $previousEvent = StaffEventSingleService::whereDate('sess_date', '<', $futureEvent->sess_date)->where('sess_sessr_id', $repeat['sessr_id'])->orderBy('sess_id', 'desc')->first();
                                if (count($previousEvent)) {
                                    if($repeat->sessr_repeat_end_on_date == null){
                                        $repeat->sessr_repeat_end             = 'On';
                                        $repeat->sessr_repeat_end_after_occur = 0;
                                        $repeat->sessr_repeat_end_on_date     = $previousEvent->sess_date;
                                        $repeat->update();
                                    }
                                } else {
                                    $repeat->delete();
                                }
                                
                                // Add log      
                                 if($updateValue){
                                    setInfoLog('StaffEventSingleService',  $futureEvent->sess_id);   
                                 }else{
                                    setInfoLog('something wrong in service query',  $futureEvent->sess_id);   
                                 }                                                   
                                //End 
                            }

                            $additionalHistoryText = 'while account status changed from '.$clientOldStatus.' to '.$clientNewStatus;
                            
                            # Creating history text
                            $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                            if ($historyText) {
                                $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureEvent]);
                            }
                        }
                    }
                    # Set info log
                    setInfoLog('Client membership limit reset to empty on account status changed from '.$clientOldStatus.' to '.$clientNewStatus,  $client->id);
                    // $this->membershipLimitResetOnMembershipChange($client->id);
                    $this->membershipLimitReset($client->id);
                }

                //client membership histroy
                $doneBy = "";
                if(Auth::check()) {
                    if(Auth::user()->name)
                        $doneBy .= Auth::user()->name;
                    if(Auth::user()->last_name) 
                        $doneBy .= ' '.Auth::user()->last_name;
                } else {
                $doneBy = 'SYSTEM';
                }
                
                $historytext = 'Membership status changed from '.$clientOldStatus.' to '.$clientNewStatus.' by '.$doneBy;
                DB::table('Cient_membership_history')->insertGetId(
                    [
                    'client_id' => $client->id, 
                    'history' => $historytext,
                    'created_at' =>date('Y-m-d H:i:s'),
                    'updated_at' =>date('Y-m-d')
                    ]
                );


                $this->setMembershipDelete($clientNewStatus, $client->id);

                $client->firstname = $request->first_name;
                $client->lastname  = $request->last_name;
                //$client->account_status = $this->getStatusForbackend($request->client_status);
                $client->account_status = $clientNewStatus;
                $client->email          = $request->email;
                $client->phonenumber    = $request->numb;
                /*$client->notes = $request->client_notes;*/

                if (isset($request->gender)) {
                    $client->gender = $request->gender;
                } else {
                    $client->gender = '';
                }

                $prelogin_with_email = $client->login_with_email;
                if (isset($request->login_with_email) && $request->login_with_email) {
                    $client->login_with_email = $request->login_with_email;
                } else {
                    $client->login_with_email = 0;
                }

                $dob  = '';
                $parq = $client->parq;
                if ($parq) {
                    /*if($request->day != '' && $request->month != '' && $request->year != '')
                    $dob = $parq->setDob($request->year, $request->month, $request->day);*/
                    $dob = prepareDob($request->year, $request->month, $request->day);
                    $age = $this->calcAge($dob);
                    //if(Auth::user()->hasPermission(Auth::user(), 'edit-parq')){
                    //if(hasPermission('edit-parq')){
                    $parq->firstName = $request->first_name;
                    $parq->lastName  = $request->last_name;
                    $parq->dob       = $dob;
                    $parq->age       = $age;
                    $parq->email     = $request->email;
                    $parq->contactNo = $request->numb;

                    if (isset($request->gender)) {
                        $parq->gender = $request->gender;
                    } else {
                        $parq->gender = '';
                    }

                    if (isset($request->goalHealthWellness) && $request->goalHealthWellness != '') {
                        $parq->goalHealthWellness = groupValsToSingleVal($request->goalHealthWellness);
                    } else {
                        $parq->goalHealthWellness = '';
                    }
                   
                    if (isset($request->referralNetwork)) {
                        $parq->referralNetwork = $request->referralNetwork;
                        $parq->referralId      = $request->referralId;
                    } else {
                        $parq->referralNetwork = '';
                        $parq->referralId      = 0;
                    }
                    if (isset($request->referrer)) {
                        $parq->hearUs = $request->referrer;
                        if ($request->referrer == 'mediapromotions' || $request->referrer == 'onlinesocial') {
                            $parq->referencewhere = $request->referencewhere;
                            $parq->referrerother  = '';
                        } elseif ($request->referrer == 'socialmedia') {
                            $parq->referencewhere = '';
                            $parq->referrerother  = $request->otherName;
                        } else {
                            $parq->referencewhere = '';
                            $parq->referrerother  = '';
                        }

                    } else {
                        $parq->hearUs         = '';
                        $parq->referencewhere = '';
                        $parq->referrerother  = '';
                    }

                    $parq->save();
                    //}
                }
                $referral_name = '';
                $referencewhere = '';
                $otherName = '';
                if (isset($request->referrer)) {
                    if ($request->referrer == 'mediapromotions' || $request->referrer == 'onlinesocial') {
                       $referencewhere = $request->referencewhere;
                    } elseif ($request->referrer == 'socialmedia') {
                        $otherName = $request->otherName;
                    } else {
                        if ($request->referralNetwork == 'Client') {
                            $referringClient = Clients::withTrashed()->find($request->clientId);
                            if ($referringClient) {
                                $referral_name = $referringClient->firstname . ' ' . $referringClient->lastname;
                            }
                
                        } else if ($request->referralNetwork == 'Staff') {
                            $staff = Staff::withTrashed()->find($request->staffId);
                            if ($staff) {
                                $referral_name = $staff->first_name . ' ' . $staff->last_name;
                            }
                
                        } else if ($request->referralNetwork == 'Professional network') {
                            $contact = Contact::withTrashed()->find($request->proId);
                            if ($contact)
                            {
                                $referral_name = $contact->name;
                            }
                
                        }
                    }
                }
                
                if (isset($request->email_to_client) && $request->email_to_client) {
                    $client->email_to_client = $request->email_to_client;
                    $client_email = \Config::get('env-data.client_mail');
                    $to = $client_email;
                    $username = 'carlyle';
                    $subject  = "Details";
                    if($request->login_with_email == 1){
                        $allow = "Yes";
                    }else{
                        $allow= "No";
                    }
                    $message = $this->getMessage($request->all(),$parq->goalHealthWellness,$allow,$referral_name,$referencewhere,$otherName);
                    $this->sendMail($username , $to , $message , $subject);
                } else {
                    $client->email_to_client = 0;
                }

                $client->birthday    = $dob;
                $client->risk_factor = $client->RiskFactorr;

                /* Create Birthday reminder for this client */
                if ($dob != '') {
                    $currentYear = Carbon::now()->year;
                    $date        = $currentYear . '-' . $request->month . '-' . $request->day;
                    $taskName    = ucwords($client->firstname . ' ' . $client->lastname) . ' Birthday';
                    $this->setTaskReminder($date, ['taskName' => $taskName, 'taskDueTime' => '09:00:00', 'taskNote' => '', 'remindBeforHour' => 1, 'clientId' => $client->id]);
                }

                $client->save();

                $clientStatusGraph                 = new ClientAccountStatusGraph();
                $clientStatusGraph->business_id    = $client->business_id;
                $clientStatusGraph->client_id      = $client->id;
                $clientStatusGraph->account_status = $clientNewStatus;
                $clientStatusGraph->save();

                /*Start: Update clint note--*/
                if ($request->client_notes != '') {
                    if ($client->note_id != 0) {

                        /*$clientNote=ClientNote::where('cn_client_id',$client->note_id)->where('cn_user_id',Auth::id())->first();
                        if($clientNote){*/
                        //$clientNote->cn_notes=$request->client_notes;
                        $client->note()->update(['cn_notes' => $request->client_notes, 'cn_source' => 'Added from client update']);
                        /*$clientNote->save();
                    }*/
                    } else {
                        $note_Id = $this->createNotes($request->client_notes, $id, 'general', 'Added from client update');
                        $client->update(['note_id' => $note_Id]);
                    }
                }
                /*Start: Update clint note--*/
                /*if($request->client_membership!=''){
                $timestamp = createTimestamp();
                $insertData = array('cm_client_id' => $client->id, 'cm_membership_id' => $request->client_membership, 'cm_membership_start_date' => $timestamp );
                ClientMember::create($insertData);
                }*/

                if (!$prelogin_with_email && $request->login_with_email) {
                    $this->callStoreUser(['name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'accountId' => $id, 'password' => $request->clientNewPwd]);
                } else if ($prelogin_with_email) {
                    if (!$request->login_with_email) {
                        $user = $client->user;
                        if ($user) {
                            $user->forceDelete();
                        }

                    } else if ($request->login_with_email) {
                        $this->entityLogin_tableRecordUpdate(['entity' => $client, 'firstName' => $request->first_name, 'lastName' => $request->last_name,'email' => $request->email , 'password' => $request->clientNewPwd]);
                    }

                }

                if ($clientOldStatus != $clientNewStatus)
                //$this->processSalesProcessOnStatusChange($client, ['clientOldStatus' => $clientOldStatus, 'clientNewStatus' => $clientNewStatus]);
                {
                    $this->processSalesProcessOnStatusChange($client, $clientOldStatus, $clientNewStatus, 'client edit');
                }

                $msg['status'] = 'updated';
            }
            //}
        }
        return json_encode($msg);
    }

    public function create(Request $request)
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-client')) {
            abort(404);
        }

        //if(!Session::has('businessId'))
        //return redirect('settings/business/create');

        //$business = Business::with('locations')->find(Session::get('businessId'));
        //$businessId = $business->id;
        $businessId = Session::get('businessId');
        /*$allMemberShipData=['' => '-- Select --'];
        $memberShip=MemberShip::where('me_business_id', Session::get('businessId'))->get();
        foreach ($memberShip as $mValue) {
        $allMemberShipData[$mValue['id']]=$mValue->me_membership_label;

        }*/
        $busnsData = Business::with('user.usersLimit')->find($businessId);
        $isError = false;
        $msg = '';
        if($busnsData->user->usersLimit != null){
            $userLimit = $busnsData->user->usersLimit->maximum_users;
            $counClients = Clients::where('business_id',Session::get('businessId'))->havingStatus('Active')->count();
            if($counClients >= $userLimit)
            {
                $isError = true;
                $msg      = 'Maximum Limit of User creation has been reached!';
            }
        }
        
        if ($request->has('subview')) {
            $subview = true;
        }

        $pwd = genPwd();

        return view('Settings.client.edit', compact('businessId' /*,'allMemberShipData'*/, 'subview', 'pwd','isError','msg'));
    }

    public function destroy($id)
    {
        $client = Clients::findOrFailClient($id);

        if (!isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'delete-client')) {
            abort(404);
        }

        $client->delete();

        return redirect()->back()->with('message', 'success|Client has been deleted successfully.');
        //route('clients')
    }

    public function priceEmailed(Request $request)
    {
        $msg    = [];
        $client = Clients::findClient($request->clientId);
        if ($client) {
            if (isClientInSalesProcess($client->consultation_date)) {
                $stepNumb                  = (int) $request->stepNumb;
                $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($stepNumb);
                $client->account_status    = preventActiveContraOverwrite($client->account_status, $salesProcessRelatedStatus['clientPrevStatus']);
                $client->save();

                $msg['salesProcessDate'] = $this->saveSalesProgress(['clientId' => $request->clientId, 'stepNumb' => $stepNumb]);
                $msg['status']           = 'updated';
            }
            $msg['changeStatus'] = $this->getStatusForbackend($client->account_status, true) . '|' . $client->account_status;
            /*$stepNumb = (int) $request->stepNumb;
        $salesProcessRelatedStatus = calcSalesProcessRelatedStatus($stepNumb);
        if(isClientInSalesProcess($client->consultation_date) && statusMatchSalesStatus($client->account_status, $salesProcessRelatedStatus['clientPrevStatus'])){
        $clientOldSaleProcessStep = $client->sale_process_step;
        $client->sale_process_step = $stepNumb;
        $client->save();

        $salesProcessHistory = ['clientId'=>$client->id, 'toType'=>$salesProcessRelatedStatus['salesProcessType'], 'toStep'=>$salesProcessRelatedStatus['saleProcessStepNumb'], 'fromStep'=>$clientOldSaleProcessStep, 'reason'=>'Email pricing completed'];

        $msg['salesProcessDate'] = $this->saveSalesProcess($salesProcessHistory);
        $msg['status'] = 'updated';
        }*/
        }
        return json_encode($msg);
    }

    public function updateSalesProcess(Request $request)
    {
        $msg    = [];
        $client = Clients::findClient($request->clientId);
        if ($client) {
            $request->stepNumb         = (int) $request->stepNumb;
            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus((int) $request->stepNumb);
            if ($request->action == 'complete') {
                $sessionSteps = sessionSteps();
                $data         = ['clientId' => $request->clientId, 'stepNumb' => $request->stepNumb];
                if (in_array($request->stepNumb, $sessionSteps)) {
                    $data['manual'] = 1;
                }

                $msg['salesProcessDate'] = $this->saveSalesProgress($data);

                $salesBookingSteps = salesBookingSteps();
                if (in_array($salesProcessRelatedStatus['salesProcessType'], $salesBookingSteps)) {
                    //Booking step
                    if ($request->stepNumb == 2 && $client->consultation_date == null && !$this->isStepComp($request->stepNumb + 1, $request->clientId, $client->SaleProcessEnabledSteps)) { //Book consultation step and consultation date is not set and consulted is not completed, set consultation date
                        $client->consultation_date = Carbon::now()->toDateString();
                        $client->save();
                    }
                } else {
                    //Attendance Step
                    if ($salesProcessRelatedStatus['salesProcessType'] != 'contact' && $client->consultation_date == null) {
                        $client->consultation_date = Carbon::now()->toDateString();
                    }
                    if (!in_array($request->stepNumb, $sessionSteps)) {
                        if (!$this->checkFutureSalesProgress($salesProcessRelatedStatus['salesProcessType'], $request->clientId, $client->SaleProcessEnabledSteps)) {
                            $nextStep               = $this->getNextAttendStep($request->stepNumb, $client);
                            $nextStepDetails        = calcSalesProcessRelatedStatus($nextStep);
                            $client->account_status = preventActiveContraOverwrite($client->account_status, $nextStepDetails['clientPrevStatus']);
                        }
                        $client->save();

                        if ($salesProcessRelatedStatus['salesProcessType'] != 'benchmarked') {
                            $this->linkCompletedBooking($salesProcessRelatedStatus['salesProcessType'], $client);
                        }

                    } else {
                        $session  = $client->SaleProcessSett['session'];
                        $lastStep = $session[count($session) - 1];
                        if ($lastStep == $request->stepNumb) {
                            $nextStepDetails        = calcSalesProcessRelatedStatus('email_price');
                            // $client->account_status = preventActiveContraOverwrite($client->account_status, $nextStepDetails['clientPrevStatus']);
                        }
                        $client->save();
                    }
                }
                if(!$request->has('completeMark') && !$request->skipManageSales = 1)
                {
                $this->manageSessionSalesProcess($client);
                }
                $msg['consultationDate'] = $client->consultation_date;
                $msg['status']           = 'updated';
                $msg['changeStatus']     = $this->getStatusForbackend($client->account_status, true) . '|' . $client->account_status;
            } else if ($request->action == 'incomplete') {
                /*$sessionSteps = sessionSteps();
                if(!in_array($request->stepNumb, $sessionSteps)){*/
                if (!$request->has('stepType')) {
                    $this->deleteSalesProgress($request->stepNumb, $request->clientId); //Deleting record from progress

                    $salesBookingSteps = salesBookingSteps();
                    $attendStepDetails = [];
                    if (in_array($salesProcessRelatedStatus['salesProcessType'], $salesBookingSteps)) {
                        //Booking step
                        if ($request->stepNumb == 2) //Book consultation step
                        {
                            $client->consultation_date = null;
                        }

                        if ($request->stepNumb == 2 || $request->stepNumb == 4) {
                            $attendStep = $request->stepNumb + 1;
                            $this->deleteSalesProgress($attendStep, $request->clientId); //Deleting record from progress

                            $attendStepDetails = calcSalesProcessRelatedStatus($attendStep);
                        }
                    } else {
                        $attendStepDetails = $salesProcessRelatedStatus;
                        //manage consulation date for consulted step
                    }

                    if (count($attendStepDetails) && !$this->checkFutureSalesProgress($attendStepDetails['salesProcessType'], $request->clientId, $client->SaleProcessEnabledSteps)) {
                        $client->account_status = preventActiveContraOverwrite($client->account_status, $this->getAttendClientStatus($attendStepDetails['salesProcessType'], $client));
                    }

                    $client->save();
                } else {
                    if ($request->stepType == 'book') {
                        //Booking step
                        if ($request->bookType == 'team') { //Team step
                            $bookingSteps = teamBookingSteps();
                            $attendSteps  = teamAttendSteps();
                        } else if ($request->bookType == 'indiv') {
                            //Individual step
                            $bookingSteps = indivBookingSteps();
                            $attendSteps  = indivAttendSteps();
                        }

                        SalesProcessProgress::where('spp_client_id', $request->clientId)->whereIn('spp_step_numb', $bookingSteps)->where('spp_comp_manual', 1)->orderBy('spp_step_numb', 'desc')->limit(1)->update(['spp_comp_manual' => 0]); //Updating latest manual record to 0

                        $this->deleteSalesProgress($request->stepNumb, $request->clientId); //Deleting record from progress

                        $idx        = array_search($request->stepNumb, $bookingSteps);
                        $attendStep = $attendSteps[$idx];
                        if ($this->isStepEnabled($attendStep, $client->SaleProcessEnabledAttendSteps) && $this->isStepComp($attendStep, $request->clientId, $client->SaleProcessEnabledSteps)) {
                            //Its correspondence attendance step is enabled and completed

                            SalesProcessProgress::where('spp_client_id', $request->clientId)->whereIn('spp_step_numb', $attendSteps)->where('spp_comp_manual', 1)->orderBy('spp_step_numb', 'desc')->limit(1)->forceDelete(); //Deleting latest manual attendance record
                        }
                    } else if ($request->stepType == 'attend') {
                        //Attendance step
                        if ($request->bookType == 'team') { //Team step
                            $attendSteps = teamAttendSteps();
                        } else if ($request->bookType == 'indiv') {
                            //Individual step
                            $attendSteps = indivAttendSteps();
                        }

                        SalesProcessProgress::where('spp_client_id', $request->clientId)->whereIn('spp_step_numb', $attendSteps)->where('spp_comp_manual', 1)->orderBy('spp_step_numb', 'desc')->limit(1)->update(['spp_comp_manual' => 0]); //Updating latest manual record to 0

                        $this->deleteSalesProgress($request->stepNumb, $request->clientId); //Deleting record from progress
                    }
                }

                $msg['status']       = 'updated';
                $msg['changeStatus'] = $this->getStatusForbackend($client->account_status, true) . '|' . $client->account_status;
            }
        }
        return json_encode($msg);
    }

    public function makeupNetamount(Request $request, $id)
    {
     
        $msg              = [];
        $msg['netamount'] = $this->setEpicBalance($id);
        if($request->has('eventId') && $request->bookingType == 'class'){
           $staffEventClassClient = DB::table('staff_event_class_clients')->where('secc_sec_id', $request->eventId)->where('secc_client_id',$id)->whereNull('deleted_at')->first();
           $event       = StaffEventClass::OfBusiness()->find($request->eventId);
           if($staffEventClassClient->secc_cmid != 0){
              $clientMemb = Clients::paidMembership($id);
              if($clientMemb->cm_number <= $clientMemb->cm_discount_dur || $clientMemb->cm_discount_dur == -1){
                  if($clientMemb->cm_disc_per_class_amnt == null){
                    if($clientMemb->cm_per_clas_amnt == '' || $clientMemb->cm_per_clas_amnt == null || $clientMemb->cm_per_clas_amnt == '0.00'){
                     
                        $classPrice = $event->sec_price;
                            $this->updateMembershipClassPrice($clientMemb->id,  $classPrice );
                        }else{
                         $classPrice = $clientMemb->cm_per_clas_amnt;
                        }
                  }else{
                    $classPrice = $clientMemb->cm_disc_per_class_amnt;
                  }
              }else{
                if($clientMemb->cm_per_clas_amnt == '' || $clientMemb->cm_per_clas_amnt == null || $clientMemb->cm_per_clas_amnt == '0.00'){
                     
                    $classPrice = $event->sec_price;
                        $this->updateMembershipClassPrice($clientMemb->id,  $classPrice );
                    }else{
                     $classPrice = $clientMemb->cm_per_clas_amnt;
                    }
              }
            
              if($staffEventClassClient->secc_class_extra == 1){
                $sessionData = json_decode($clientMemb->cm_session_limit, 1);
                $staffEventClass = StaffEventClass::with('clas.cat')->where('sec_id',$staffEventClassClient->secc_sec_id)->first();
                $catId = $staffEventClass->clas->cat->clcat_id;
                if($sessionData[$catId]['discount_price_type'] == 'sessionUnitPrice' && $sessionData[$catId]['discount_type'] == 'fixed'){
                    $classPrice = $sessionData[$catId]['discount_amount'];
                }else if($sessionData[$catId]['discount_price_type'] == 'sessionUnitPrice' && $sessionData[$catId]['discount_type'] == 'percent'){
                    $classPrice = $sessionData[$catId]['sessionDiscountPerData'];
                }else{
                $classPrice = $sessionData[$catId]['mem_unit_price'];
                }
              }
              $msg['price'] = $classPrice;
           }
        }
    
             if($request->has('eventId') && $request->bookingType == 'service'){
                    $clientDetails = Clients::paidMembership($id);
                            if($clientDetails){
                               

                                $service_limits = json_decode($clientDetails->cm_services_limit, 1);
                                $eventClass = StaffEventSingleService::OfBusiness()->find($request->eventId);
                                $serviceId = $eventClass->sess_service_id;
                             
                               if($eventClass->sess_cmid != 0){
                                    if( $service_limits[$serviceId]['discount_price_type'] == 'serviceUnitPrice' &&  $service_limits[$serviceId]['discount_type'] == 'fixed'){
                                        $servicePrice = $service_limits[$serviceId]['discount_amount'];
                                    }else if( $service_limits[$serviceId]['discount_price_type'] == 'serviceUnitPrice' &&  $service_limits[$serviceId]['discount_type'] == 'percent'){
                                        $servicePrice =  $service_limits[$serviceId]['serviceDiscountPerData'];
                                    }else{
                                        $servicePrice = $service_limits[$serviceId]['mem_unit_price'];
                                    }
                                }else{
                                    $servicePrice = $service_limits[$serviceId]['mem_unit_price'];
                                }
                               
                            }
                            $msg['price'] = $servicePrice;
             }
           
        /*$client = Clients::findClient($id);
        $msg['netamount']=$client->makeups()->sum('makeup_amount'); */
        $msg['status'] = 'success';

        return json_encode($msg);
    }

    public function raiseMakeUp(Request $request)
    {
        $isError         = false;
        $msg             = [];
        $raiseMakeupData = [];
        $client          = Clients::findClient($request->clientId);
        if (!$client || !Auth::user()->hasPermission(Auth::user(), 'edit-client')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if (!$isError) {
            $raiseMakeupData['clientId'] = $request->clientId;
            $raiseMakeupData['notes']    = $request->notes;
            $raiseMakeupData['purpose']  = $request->purpose;
            $raiseMakeupData['action']   = $request->action;
            $raiseMakeupData['amount']   = $request->amount;

            $msg['netamount'] = $this->raiseMakeupSave($raiseMakeupData);
            $msg['status']    = 'success';
        }
        return json_encode($msg);
    }
    protected function raiseMakeupSave($raiseMakeupData)
    {
        $makeup                   = new Makeup;
        $makeup->makeup_client_id = $raiseMakeupData['clientId'];
        if ($raiseMakeupData['action'] == 'fall') {
            $makeup->makeup_amount = -($raiseMakeupData['amount']);
            $action                = 'dropped.';
        } elseif ($raiseMakeupData['action'] == 'raise') {
            $makeup->makeup_amount = $raiseMakeupData['amount'];
            $action                = 'raised.';
        }
        $notesId = $this->createNotes($raiseMakeupData['notes'], $raiseMakeupData['clientId'], 'makeup', 'Added from EPIC credit tab', 'epic credit ' . $action);
        if ($notesId) {
            $makeup->makeup_notes_id = $notesId;
        }

        $makeup->makeup_purpose   = $raiseMakeupData['purpose'];
        $makeup->makeup_user_id   = $makeup->UserInformation['id'];
        $makeup->makeup_user_name = $makeup->UserInformation['name'];
        $string = $raiseMakeupData['action']=='fall'?'Deducted to EPIC Credit':'Added to EPIC Credit';
        if($raiseMakeupData['purpose'] == 'class')
        {
            $makeup->makeup_extra = 'Class Makeup | '.$string;
        }
        elseif ($raiseMakeupData['purpose'] == 'service') 
        {
            $makeup->makeup_extra = 'Service Makeup | '.$string;
        }
        else
        {
             $makeup->makeup_extra = 'Manually by admin | '.$string;
        } 
        $makeup->save();

        $epicBal = $this->setEpicBalance($raiseMakeupData['clientId']);
        return $epicBal;

    }

    /*protected function getMakeUpCount($eventCollections){
    return $eventCollections->filter(function($event){
    $model = class_basename($event);
    return ($model == 'StaffEventClass' && $event->pivot->secc_if_make_up === 1 && $event->pivot->secc_if_make_up_created !== 1 );
    })->count();
    }*/

    public function storeByApi(Request $request)
    {
        $businessId = \Request::get('businessId');
        $status     = 'pending';
        $validator  = Validator::make($request->all(), [
            'firstName' => 'required',
            //'status'=>'required',
            'goals'     => 'required',
            'email'     => 'bail|required_without:phone|email',
            'phone'     => 'bail|required_without:email|regex:/[0-9]/',
        ]);
        $validator->after(function ($validator) use ($request, $businessId) {
            /*if(!$validator->errors()->has('status') && !$this->getStatusForbackend($request->status))
            $validator->errors()->add('status', 'Invalid value');*/

            if ($request->email && !$validator->errors()->has('email') && !$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'client', 'businessId' => $businessId])) {
                $validator->errors()->add('email', 'This email is already in use');
            }

            if ($request->phone && !$validator->errors()->has('phone') && $this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'client', 'businessId' => $businessId])) {
                $validator->errors()->add('phone', 'This phone number is already in use');
            }

        });

        if ($validator->fails()) {
            return json_encode(['code' => '400', 'message' => 'Required data is not upto the mark', 'error' => $validator->errors()]);
        }

        $client                     = new Clients;
        $client->business_id        = $businessId;
        $client->firstname          = $request->firstName;
        $client->create_source      = 'efp';
        $client->account_status     = $this->getStatusForbackend($status);
        $client->sale_process_setts = json_encode(['steps' => ['4', '5', '18', '11'], 'teamCount' => '3', 'indivCount' => '', 'order' => json_decode('[{"id":"team-1"},{"id":"team-2"},{"id":"team-3"}]', 1), 'session' => [6, 11, 7, 23, 8, 24]]);
        //$client->fixed_sales = 1;
        $client->is_bookbench_on = 1;
        if ($request->has('lastName')) {
            $client->lastname = $request->lastName;
        }

        if ($request->has('email')) {
            $client->email = $request->email;
        }

        if ($request->has('phone')) {
            $client->phonenumber = $request->phone;
        }

        if ($request->has('notes')) {
            $client->notes = $request->notes;
        }

        $clientNewStatus           = $this->getStatusForbackend($status);
        $salesProcessDetails       = calcSalesProcessRelatedStatus($clientNewStatus);
        $client->sale_process_step = $salesProcessDetails['saleProcessStepNumb'];

        /*$saleProcessConsultedDetails = calcSalesProcessRelatedStatus('consulted');
        if($salesProcessDetails['saleProcessStepNumb'] > $saleProcessConsultedDetails['saleProcessStepNumb'])
        $client->consultation_date = Carbon::now()->toDateString();*/

        //$client->risk_factor=Clients::calculateRiskFactor();
        $client->save();
        Session::put('ifBussHasClients', true);

        $salesProcessHistory = ['clientId' => $client->id, 'toType' => $salesProcessDetails['salesProcessType'], 'toStep' => $salesProcessDetails['saleProcessStepNumb'], 'action' => 'upgrade', 'reason' => 'Client account created'];
        $this->saveSalesProcess($salesProcessHistory);

        /*if($salesProcessDetails['salesProcessType']){
        $salesAttendanceSteps = salesAttendanceSteps();
        $endKey = array_search($salesProcessDetails['salesProcessType'], $salesAttendanceSteps);
        $salesAttendanceAddSteps = array_slice($salesAttendanceSteps, 0, $endKey+1);
        if(count($salesAttendanceAddSteps)){
        foreach($salesAttendanceAddSteps as $slug){
        $thisDetails = calcSalesProcessRelatedStatus($slug);
        $this->saveSalesProgress(['clientId'=>$client->id, 'stepNumb'=>$thisDetails['saleProcessStepNumb']]); //Adding record in progress
        }
        $this->manageTeamSalesProcess($client);
        }
        }*/
        $this->processSalesProcessOnStatusChange($client, '', $client->account_status, 'Client account created');

        $parq            = new Parq;
        $parq->firstName = $request->firstName;
        if ($request->has('lastName')) {
            $parq->lastName = $request->lastName;
        }

        if ($request->has('email')) {
            $parq->email = $request->email;
        }

        if ($request->has('phone')) {
            $parq->contactNo = $request->phone;
        }

        //print_r($request->goals);
        if ($request->has('apiClient')) {
            $parq->hearUs         = 'onlinesocial';
            $notes                = $request->notes;
            $tempref              = explode(':', $notes);
            $parq->referencewhere = $tempref[1];
        }

        if ($request->has('goals')) {
            $parq->goalHealthWellness = groupValsToSingleVal(str_replace(", ", ",", $request->goals));
        }

        $client->parq()->save($parq);

        $client->update(['risk_factor' => $client->RiskFactorr]);

        if ($businessId == 9) {
            $insertData = array('task_business_id' => $businessId, 'task_name' => "EPIC Signup: " . $request->firstName . " " . $request->lastName, 'task_due_date' => date('Y-m-d', time() + 86400), 'task_category' => 40, 'task_due_time' => "09:30:00", 'task_user_id' => 11);
            $addedtask  = Task::create($insertData);
        }

        if ($request->has('apiClient')) {
            /*info@epicfit.net*/
            $mail = mail("info@epicfit.net", "New member on epicfit.co.nz", "Name: " . $request->firstName . " " . $request->lastName . "\r\n" . "Phone Number: " . $request->phone . "\r\n" . "Email Address: " . $request->email . "\r\n" . "Notes: " . $request->notes . "\r\n" . "Goals: " . $request->goals, "From: noreply@epicfitstudios.com");
        }
        $defaulteMnues = ['parq','calendar_settings','invoice','benchmark'];
        ClientMenu::create([
            'client_id' =>$client->id,
            'menues' => implode(',',$defaulteMnues),
        ]);

        return json_encode(['code' => '200', 'message' => 'Details have been submitted Successfully!']);
    }

    public function printAppointments()
    {
        return view('includes.partials.events_list_print');
    }

    public function updateMembership(Request $request)
    {
        $isError         = false;
        $isCreateInvoice = true;
        if (!Session::has('businessId') || !isUserEligible(['Admin'], 'manage-client-membership')) {
            if ($request->ajax()) {
                $isError = true;
            } else {
                abort(404);
            }

        }

        if ($request->ajax()) {
            $msg    = [];
            $client = Clients::findClient($request->clientId);
            if (!$client) {
                $msg['status']      = 'error';
                $msg['errorData'][] = array('invalidRecord' => 'This client doesn\'t exist');
                $isError            = true;
            }
        }

        if (!$isError) {
            $memberShip = MemberShip::OfBusiness()->find($request->membership);

            if (!$memberShip) {
                $msg['status']      = 'error';
                $msg['errorData'][] = array('invalidRecord' => 'This membership doesn\'t exist');
                $isError            = true;
            } else {
                $prevMemb = $currMemb = $client->membership($client->id); //Get current membership

                if ($currMemb && $currMemb->cm_membership_id == $request->membership && !in_array($currMemb->cm_status, ['Expired', 'Removed'])) {
                    $request->request->add(['updateOpt' => 1]);

                }
            }
            if (!$isError) {
                $clientMember = new ClientMember;
                if ($request->has('updateOpt')) {
                    //Update case of client-membership
                    if ($request->updateOpt == 1) { //Effect immediately
                        if ($currMemb) { //If membership exists then mark it as expired
                            $currMemb->cm_status = 'Expired';
                            $currMemb->save();
                        }
                    } else if ($request->updateOpt == 2) {
                        //Effect on next cycle
                        $clientMember = $client->nextMembership($client->id);
                        if (!$clientMember) {
                            //If next membership does not exist
                            $clientMember            = new ClientMember;
                            $clientMember->cm_status = 'Next';
                        }
                    }

                } else if ($request->clientMembId) {
                    //Update case of commencement date
                    $currMemb = $client->membership($client->id);
                    if ($currMemb->cm_parent_id) {
                        ClientMember::where('id', $currMemb->cm_parent_id)->orWhere('cm_parent_id', $currMemb->cm_parent_id)->delete();
                    } else {
                        $currMemb->delete();
                    }

                    $isCreateInvoice = false;
                }
                // dd($currMemb,count($mem_limit),$currMemb->cm_membership_id != $request->membership);
                // if ($currMemb && $currMemb->cm_membership_id != $request->membership && $currMemb->cm_services_limit != '[]' && $currMemb->cm_services_limit != null ) {
                //     $services     = [];
                // }else{
                //     $services     = $request->services;
                // }

                // if ($currMemb && $currMemb->cm_membership_id != $request->membership && $currMemb->cm_session_limit != '[]' && $currMemb->cm_session_limit != null) {
                //     $sessions     = [];
                // }else{
                //     $sessions     = $request->session;
                // }

                # Set info log
                setInfoLog('Client membership updated',  $client->id);

                /* Service allowed */
                $services     = $request->services;
                $mem_services = array();
                $mem_limit    = array();
                $mem_type     = array();
                $mem_discount_type = array();
                $mem_discount_amount = array();
                $mem_discount_price = array();
                $serviceDiscountTypeData = array();
                $serviceDiscountPerData = array(); 
                $mem_unit_price = array();
                if (count($services)) {
                    ksort($services);
                    foreach ($services as $key => $value) {
                        if (strpos($key, 'mem_service') !== false) {
                            $mem_services[] = $value;
                        } else if (strpos($key, 'mem_limit') !== false) {
                            $mem_limit[] = (int) $value;
                        } else if (strpos($key, 'mem_type') !== false) {
                            $mem_type[] = $value;
                        } else if (strpos($key, 'mem_price') !== false) {
                            $mem_price[] = (float) $value;
                        }else if (strpos($key, 'mem_discount_type') !== false) {
                            $mem_discount_type[] = $value;
                        }else if (strpos($key, 'mem_discount_amount') !== false) {
                            $mem_discount_amount[] = $value;
                        }else if (strpos($key, 'mem_discount_price') !== false) {
                            $mem_discount_price[] = $value;
                        }else if (strpos($key, 'mem_unit_price') !== false) {
                            $mem_unit_price[] = $value;
                        }else if (strpos($key, 'serviceDiscountPerData') !== false) {
                            $serviceDiscountPerData[] = $value;
                        } else if (strpos($key, 'serviceDiscountTypeData') !== false) {
                            $serviceDiscountTypeData[] = $value;
                        }

                    }
                }
                if (count($mem_services) && count($mem_limit) && count($mem_type)) {
                    for ($i = 0; $i < count($mem_services); $i++) {
                        $services_limit[$mem_services[$i]] = array('limit' => $mem_limit[$i], 'limit_type' => $mem_type[$i], 'price' => $mem_price[$i], 'discount_type' =>$mem_discount_type[$i], 'discount_amount' => $mem_discount_amount[$i], 'discount_price_type' => $mem_discount_price[$i], 'mem_unit_price' => $mem_unit_price[$i],'serviceDiscountPerData' => $serviceDiscountPerData[$i],'service_discount_percentage' => $serviceDiscountTypeData[$i]);
                    }
                } else {
                    $services_limit = [];
                }

                $clientMember->cm_services_limit = json_encode($services_limit);


                   /* sessions allowed */
                   $sessions     = $request->session;
                   $mem_session = array();
                   $session_mem_limit    = array();
                   $session_mem_type     = array();
                   $session_mem_price = array();
                   $session_mem_discount_type = array();
                   $session_mem_discount_amount = array();
                   $session_mem_discount_price = array();
                   $session_mem_unit_price = array();
                   $sessionDiscountPerData = array();
                   $discountTypeData = array();
                   if (count($sessions)) {
                       ksort($sessions);
                       foreach ($sessions as $key => $value) {
                           if (strpos($key, 'mem_session') !== false) {
                               $mem_session[] = $value;
                           } else if (strpos($key, 'session_mem_limit') !== false) {
                               $session_mem_limit[] = (int) $value;
                           } else if (strpos($key, 'session_mem_type') !== false) {
                               $session_mem_type[] = $value;
                           } else if (strpos($key, 'session_mem_price') !== false) {
                               $session_mem_price[] = (float) $value;
                           }else if (strpos($key, 'session_mem_discount_type') !== false) {
                               $session_mem_discount_type[] = $value;
                           }else if (strpos($key, 'session_mem_discount_amount') !== false) {
                               $session_mem_discount_amount[] = $value;
                           }else if (strpos($key, 'session_mem_discount_price') !== false) {
                            $session_mem_discount_price[] = $value;
                           }else if (strpos($key, 'session_mem_unit_price') !== false) {
                               $session_mem_unit_price[] = $value;
                           }else if (strpos($key, 'sessionDiscountPerData') !== false) {
                            $sessionDiscountPerData[] = $value;
                        }else if (strpos($key, 'discountTypeData') !== false) {
                            $discountTypeData[] = $value;
                        }
   
                       }
                   }
            
                   if (count($mem_session) && count($session_mem_limit) && count($session_mem_type)) {
                       for ($i = 0; $i < count($mem_session); $i++) {
                           $sessions_limit[$mem_session[$i]] = array('limit' => $session_mem_limit[$i], 'limit_type' => $session_mem_type[$i], 'price' => $session_mem_price[$i], 'discount_type' =>$session_mem_discount_type[$i], 'discount_amount' => $session_mem_discount_amount[$i], 'discount_price_type' => $session_mem_discount_price[$i], 'mem_unit_price' => $session_mem_unit_price[$i], 'sessionDiscountPerData' => $sessionDiscountPerData[$i], 'session_discount_percentage' => $discountTypeData[$i]);
                       }
                   } else {
                       $sessions_limit = [];
                   }
                   $clientMember->cm_session_limit = json_encode($sessions_limit);

                /* Classes allowed */
                $classes                  = $memberShip->classmember->pluck('cl_name', 'cl_id')->toArray();
                $clientMember->cm_classes = json_encode($classes);
                $clientMember->cm_client_id          = $request->clientId;
                $clientMember->cm_membership_id      = $request->membership;
                $clientMember->cm_label              = $memberShip->me_membership_label;
                $clientMember->cm_validity_length    = $memberShip->me_validity_length;
                $clientMember->cm_validity_type      = $memberShip->me_validity_type;
                $clientMember->cm_class_limit        = $memberShip->me_class_limit;
                $clientMember->cm_class_limit_length = $memberShip->me_class_limit_length;
                $clientMember->cm_class_limit_type   = $memberShip->me_class_limit_type;
                $clientMember->cm_auto_renewal       = $memberShip->me_auto_renewal;
                $clientMember->cm_pay_plan           = $request->payPlan;
                $clientMember->cm_prorate            = ($memberShip->me_prorate && $memberShip->me_prorate != null) ? $memberShip->me_prorate : 0;
                $clientMember->cm_start_date         = $request->membStartDate;
                $clientMember->cm_enrollment_limit   = $memberShip->me_enrollment_limit;
                $clientMember->cm_subscription_type  = 'manual';
                $clientMember->cm_original_price = $request->totalOriginal;

                $chargeSignupFeeAlways = $memberShip->me_change_signup_fee && $memberShip->me_signup_fee;

                /* start: Renewal Amoumt */
                // $taxPerc = $memberShip::calcTotalTax($memberShip);
                $clientMember->cm_renw_amount = $chargeSignupFeeAlways ? $request->totalAmount + $memberShip->me_signup_fee : $request->totalAmount;
                // $clientMember->cm_renw_amount = $this->applyTaxes($taxPerc, $chargeSignupFeeAlways ? $request->totalAmount + $memberShip->me_signup_fee : $request->totalAmount);
                /* end: Renewal Amoumt */

                /* start: Signup fee */
                if (!$chargeSignupFeeAlways) {
                    //Save signup fee separately only if it's to be charged once
                    $clientMember->cm_signup_fee = $memberShip->me_signup_fee;
                }
                /* end: Signup fee */

                /* start: Due date */
                // $clientMember->cm_due_date = $this->calcDueDateWithoutProrate($request->payPlan, $request->membStartDate);
                $today = Carbon::now();
                if ($prevMemb) {
                    if($request->updateOpt == 2){
                        $clientMember->cm_due_date = $this->calcDueDateWithoutProrate($request->payPlan, $prevMemb->cm_due_date);
                    }else{
                        $clientMember->cm_due_date = $this->calcDueDateWithoutProrate($request->payPlan, $request->membStartDate);
                    }
                    // if ($prevMemb->cm_due_date <= $today->toDateString()) {
                    //     $clientMember->cm_due_date = $this->calcDueDateWithoutProrate($request->payPlan, $prevMemb->cm_due_date);
                    // } else {
                    //     $clientMember->cm_due_date = $prevMemb->cm_due_date;
                    // }
                  
                } else {
                    $clientMember->cm_due_date = $this->calcDueDateWithoutProrate($request->payPlan, $request->membStartDate);
                }

                // $clientMember->cm_end_date = $request->membEndDate;
                $membStartDate             = new Carbon($request->membStartDate);
                $membEndDate               = $membStartDate->copy()->addYears(5);
                $clientMember->cm_end_date = $membEndDate->toDateString();
                /* end: Due date */
                if($request->has('discMembAmtType')){
                    $disAmount = $request->totalAmount;
                    if($request->discMembAmtType == 'total'){
                        if($request->discMembType == 'fixed'){
                            $clientMember->cm_discounted_amount =  $request->discMembAmt;
                            $clientMember->cm_disc_per_class_amnt = $this->perClassPrice($memberShip->me_class_limit_length,$request->discMembAmt);
                        }else if($request->discMembType == 'percent'){
                            $clientMember->cm_discounted_amount =  $request->discMembAmt;
                            $clientMember->cm_disc_percentage = $request->discMembPercentage;
                            $clientMember->cm_disc_per_class_amnt = $this->perClassPrice($memberShip->me_class_limit_length,$request->discMembAmt);
                        }
                    }else if($request->discMembAmtType == 'unit'){
                        if($request->discMembType == 'fixed'){
                            $clientMember->cm_discounted_amount =  $request->discMembAmt;
                            $clientMember->cm_disc_per_class_amnt = $request->discMembUnitFixed;
                        }else if($request->discMembType == 'percent'){
                            $clientMember->cm_discounted_amount =  $request->discMembAmt;
                            $clientMember->cm_disc_percentage = $request->discMembPercentage;
                            $clientMember->cm_disc_per_class_amnt = $request->discMemUnitPrice;
                        }
                    }  
                }else{
                    $disAmount = 0;
                }
                
                /* start: EMI */
                $emiData              = $this->calcEmi($clientMember, $request->membStartDate, $clientMember->cm_due_date, ['discAmt' => $disAmount, 'membEndDate' => $request->membEndDate]);
                $clientMember->cm_emi = $emiData['emi'];
                // $clientMember->cm_per_clas_amnt = $this->perClassPrice($memberShip->me_class_limit_length,$request->membTotal);
                $clientMember->cm_per_clas_amnt = $request->memUnitOriginalPrice;
               

                $clientMember->data = json_encode(['amnt' => $emiData['amnt'], 'paid' => 1]);
                /* end: EMI */

                if ($disAmount) {
                  
                    $clientMember->cm_discount_type = $request->discMembType;
                    $clientMember->cm_disc_amnt_type = $request->discMembAmtType;
                    

                }

                if ($request->has('discDur')) {
                    $clientMember->cm_discount_dur = $request->discDur;
                }

                /* payment option */
                $clientMember->cm_payment_option = $request->payById;
                if ($request->has('updateOpt') && $request->updateOpt == 1) {
                    //Update case of client-membership and Effect immediately

                    /* start: Adjusting remaing amount to emi of new client-membership */
                    $clientMembrId = $currMemb->id;
                    $invoice       = \App\Invoice::where('inv_business_id', Session::get('businessId'))
                        ->where('inv_client_id', $request->clientId)
                        ->whereHas('invoiceitem', function ($query) use ($clientMembrId) {
                            $query->where('inp_product_id', $clientMembrId)
                                ->where('inp_type', 'membership');
                        })
                        ->first();
                    $fromDate        = Carbon::parse($currMemb->cm_start_date);
                    $toDate          = Carbon::tomorrow();
                    $isUnpaidInvoice = false;
                    $isPaidInvoice   = false;
                    $invoiceGoesTo   = '';

                    if (count($invoice)) {
                        if ($fromDate->lt($toDate)) {
                            $days      = $fromDate->diffInDays($toDate);
                            $invAmount = (int) $invoice->inv_total;
                          
                            if ($currMemb->cm_validity_type == 'year') {
                                $perDayAmount = ($invAmount / 365);
                            } elseif ($currMemb->cm_validity_type == 'month') {
                                $perDayAmount = (($invAmount * 12) / 365);
                            } elseif ($currMemb->cm_validity_type == 'week') {
                                $perDayAmount = (($invAmount * 52) / 365);
                            } elseif ($currMemb->cm_validity_type == 'day') {
                                $perDayAmount = $invAmount;
                            }
                            $clientMember->cm_number = 1;
                            $remaingAmount = round(($invAmount - ($perDayAmount * $days)), 2);
                            $nextEmiAmount = $this->nextEmi($clientMember, $clientMember->cm_number,$request->totalAmount);
                    

                           
                            if ($invoice->inv_status == 'Paid') {
                                 if ($nextEmiAmount < $remaingAmount) {
                                    $clientMember->cm_next_emi = $nextEmiAmount;
                                    $adjustedEmiAmount         = $remaingAmount - $nextEmiAmount;
                                    $this->raiseMakeupSave(['clientId' => $request->clientId, 'notes' => '', 'action' => 'raise', 'amount' => $adjustedEmiAmount, 'purpose' => 'memb_ship_adj']);
                                    $invoiceGoesTo = 'downgrade';
                                } else {
                                    $clientMember->cm_next_emi = $nextEmiAmount - $remaingAmount;
                                    $invoiceGoesTo             = 'upgrade';
                                    $adjustedEmiAmount         = $remaingAmount;
                                }

                                $isPaidInvoice   = true;
                                $isCreateInvoice = false;
                            } else {
                                $clientMember->cm_next_emi = $nextEmiAmount;
                                $isUnpaidInvoice           = true;
                            }
                        } else {
                            $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1, $request->totalAmount);
                            $isUnpaidInvoice           = true;
                        }
                        $isCreateInvoice = false;
                    } else {
                        $isCreateInvoice           = true;
                        $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1, $request->totalAmount);
                    }
                    $clientMember->save();
                    if ($isUnpaidInvoice) {
                        $disc = 'Assign ' . $clientMember->cm_label . ' on ' . Carbon::now()->format('D, d M Y');
                        $this->updateMembInvoice($invoice, ['total' => $clientMember->cm_emi, 'currentMembrId' => $clientMember->id, 'existMembrId' => $clientMembrId, 'taxPerc' => $taxPerc, 'dueDate' => $clientMember->cm_due_date, 'disc' => $disc]);
                    }
                    if ($isPaidInvoice) {
                        $this->newInvItemInOldInvoice($invoice, $clientMember->cm_emi, $clientMembrId, $invoiceGoesTo, $adjustedEmiAmount, $taxPerc);
                    }

                    /* end: Adjusting remaing amount to emi of new client-membership */

                    ClientMember::where('cm_client_id', $client->id)->where('cm_status', 'Next')->delete();
                    $clientMember = $this->manageClientMemb($clientMember);

                    /* Reset membership limit */
                    // $this->updateFutureBookingsMembership($prevMemb, $request->clientId, $clientMember->id, $clientMember->cm_start_date, $request->has('deleteRecureClasses') ? $request->deleteRecureClasses : [], $request->updateOpt);
                    if($request->has('type') && $request->type == 'service'){
                        $this->updateFutureBookingsMembership($clientMember, $request->clientId, $clientMember->id, $clientMember->cm_start_date,[], $request->updateOpt,$request->has('deleteRecureClasses') ? $request->deleteRecureClasses : [], $request->serviceDay);
                    }else{
                        $this->updateFutureBookingsMembership($clientMember, $request->clientId, $clientMember->id, $clientMember->cm_start_date, $request->has('deleteRecureClasses') ? $request->deleteRecureClasses : [], $request->updateOpt);
                    }

                } else if (!$request->has('updateOpt') && $request->clientMembId) {
                    //Update case of commencement date

                    $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1, $request->totalAmount);
                    $clientMember->save();

                    $clientMember = $this->manageClientMemb($clientMember);

                    /* Reset membership limit */
                    // $this->updateFutureBookingsMembership($prevMemb, $request->clientId, $clientMember->id, $clientMember->cm_start_date, $request->has('deleteRecureClasses') ? $request->deleteRecureClasses : [], $request->updateOpt);
                    $this->updateFutureBookingsMembership($clientMember, $request->clientId, $clientMember->id, $clientMember->cm_start_date, $request->has('deleteRecureClasses') ? $request->deleteRecureClasses : [], $request->updateOpt);

                    /* Reset membership limit */
                    // $this->membershipLimitResetOnMembershipChange($clientMember->id);
                } else {
                    $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1, $request->totalAmount);
                    $clientMember->save();
                    /* Reset membership limit */
                    // $this->updateFutureBookingsMembership($prevMemb, $request->clientId, $clientMember->id, $clientMember->cm_start_date, $request->has('deleteRecureClasses') ? $request->deleteRecureClasses : [], $request->updateOpt);
                    $this->updateFutureBookingsMembership($clientMember, $request->clientId, $clientMember->id, $clientMember->cm_start_date, $request->has('deleteRecureClasses') ? $request->deleteRecureClasses : [], $request->updateOpt);
                }

                /* Start: Create Invoice For membership */
                if ($isCreateInvoice) {
                    $invoiceData                = [];
                    $invoiceData['dueDate']     = $clientMember->cm_due_date;
                    $invoiceData['clientId']    = $request->clientId;
                    $invoiceData['locationId']  = 0;
                    $invoiceData['status']      = 'Unpaid';
                    $invoiceData['productName'] = 'Assign ' . $clientMember->cm_label . ' on ' . Carbon::now()->format('D, d M Y');
                    $invoiceData['staffId']     = 0;
                    $invoiceData['taxType']     = 'including';
                    $invoiceData['price']       = $clientMember->cm_emi;
                    $invoiceData['type']        = 'membership';
                    $invoiceData['productId']   = $clientMember->id;
                    $invoiceData['paymentType'] = $request->payBy;
                    $this->autoCreateInvoice($invoiceData);
                }
                /* End: Create Invoice For membership */

                $msg['status'] = 'updated';
            }
        }

        if ($request->ajax()) {
            return json_encode($msg);
        } else {
            if ($isError) {
                abort(404);
            } else {
                return redirect()->route('clients.show', ['id' => $request->clientId]);
            }

        }
    }

    /**
     * Apply tax over the price
     *
     * @param int $taxPerc Tax percentage
     * @param int $amount Original price
     *
     * @return int Price after the tax
     */
    protected function applyTaxes($taxPerc, $amount)
    {
        if ($taxPerc && $amount) {
            $tax = ($amount * $taxPerc) / 100;
            $amount += $tax;
        }
        return $amount;
    }

    public function deleteMembership($id, Request $request)
    {
        $isError = false;
        if (!isUserEligible(['Admin'], 'manage-client-membership')) {
            $isError = true;
        }

        $msg    = [];
        $client = Clients::findClient($id);
        if (!$client) {
            $msg['status']      = 'error';
            $msg['errorData'][] = array('invalidRecord' => 'This client doesn\'t exist');
            $isError            = true;
        }

        if (!$isError) {
            if ($request->has('preventepic') && $request->preventepic == 'yes') {
                $client->memberships()->delete();
                $msg['status'] = 'deleted';
            } else {
                $activeMemebship = $client->paidMembership($id);
                // dd($activeMemebship);
                if (count($activeMemebship)) {
                    $response = $this->onDelMembReturnAmount($id, $activeMemebship);
                    // dd($response);
                    if ($response['isError']) {
                        $isError           = true;
                        $msg['status']     = $response['status'];
                        $msg['amount']     = $response['amount'];
                        $msg['epicCredit'] = $client->epic_credit_balance;
                    } else {
                        $isError = false;
                    }
                }

                if (!$isError) {
                     # Set info log
                    setInfoLog('Client membership cancelled',  $client->id);
                    $doneBy = "";
                    if(Auth::check()) {
                        if(Auth::user()->name)
                            $doneBy .= Auth::user()->name;
                        if(Auth::user()->last_name) 
                            $doneBy .= ' '.Auth::user()->last_name;
                    } else {
                    $doneBy = 'SYSTEM';
                    }
                    // $client->paidMembership($client->id)->update(['cm_status' => 'Removed','cm_cancelled_by' => $doneBy]);
                    $clientData = ClientMember::where('cm_client_id', $client->id)->where('cm_status', '!=', 'Next')->latest()->first();
                    if($clientData){
                        $clientData->update(['cm_status' => 'Removed','cm_cancelled_by' => $doneBy]);
                    }

                    $client->memberships()->delete();
                    $eventsListData = $this->eventsListForOverview($client);
                    $futureEvents   = $eventsListData['futureEvents'];

                    $futureEvents = $futureEvents->filter(function ($futureEvent) {
                        $model = class_basename($futureEvent);
                        return ($model == 'StaffEventClass' && $futureEvent->deleted_at == null && $futureEvent->pivot->deleted_at == null) || ($model == 'StaffEventSingleService' && $futureEvent->deleted_at == null);
                    });

                    if (count($futureEvents)) {

                        foreach ($futureEvents as $futureEvent) {
                            $epicCashAmount = 0;
                            $model          = class_basename($futureEvent);
                            if ($model == 'StaffEventClass') {
                                /*Get invoice related to event class*/
                                $invoice = Invoice::where('inv_client_id', $client->id)
                                    ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                        $query->where('inp_product_id', $futureEvent->sec_id)
                                            ->where('inp_type', 'class');
                                    })->first();

                                /*Delete unpaid invoices*/
                                if ($invoice) 
                                    $invoice->delete();

                                /* Remove event class clients*/
                                DB::table('staff_event_class_clients')
                                    ->where('secc_sec_id', $futureEvent->sec_id)
                                    ->where('secc_client_id', $client->id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on membership cancellation', 'secc_action_performed_by' => getLoggedUserName()]);

                            } else if ($model == 'StaffEventSingleService') {
                                /*Get invoice related to event service*/
                                $invoice = Invoice::where('inv_client_id', $client->id)
                                    ->whereHas('invoiceitem', function ($query) use ($request, $futureEvent) {
                                        $query->where('inp_product_id', $futureEvent->sess_id)
                                            ->where('inp_type', 'service');
                                    })->first();
                                    
                                /*Delete unpaid invoices*/
                                if ($invoice) 
                                    $invoice->delete();

                                /* Remove event service clients*/
                                StaffEventSingleService::where('sess_id', $futureEvent->sess_id)
                                    ->where('sess_client_id', $client->id)
                                    ->update(['deleted_at' => createTimestamp(), 'sess_event_log' => 'Deleted on membership cancellation', 'sess_action_performed_by' => getLoggedUserName()]);
                            }
                            $additionalHistoryText = 'while membership cancellation';
                        
                            # Creating history text
                            $historyText = $this->eventclassClientHistory(['clients' => [$client], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                            if ($historyText) {
                                $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureEvent]);
                            }
                        }
                    }

                    // $this->membershipLimitResetOnMembershipChange($client->id);
                    $this->membershipLimitReset($client->id);
                    $msg['status'] = 'deleted';
                }
              
            }
        }

        return json_encode($msg);
    }

    /**
     *
     */
    public function setMembershipEpic(Request $request)
    {
        $isError = false;
        $msg     = [];
        $client  = Clients::findClient($request->clientId);
        if (count($client)) {
            $notesId = 0;
            if ($request->notes != '') {
                $notesId = $this->createNotes($request->notes, $client->id, 'makeup', ' Delete memebership.');
            }

            $remaingAmount = $request->amount;
            $this->updateEpicCredit($client->id, 0 - $remaingAmount, 'memb_ship_adj', $extraInfo = "Memebrship cancle", $notesId);

            $client->memberships()->delete();
            $msg['status'] = 'added';
        }
        return json_encode($msg);
    }

    private function subscribeMembership($clientId, $memberShip, $subscriptionType, $extra = [])
    {
        $memberShip = is_int($memberShip) ? MemberShip::with('classmember', 'servicemember')->where('id', $membershipId)->get()->first() : $memberShip;
        $dt         = Carbon::now();
        $timestamp  = $dt->toDateTimeString();
        switch ($memberShip->me_validity_type) {
            case 'day':$dt->addDays($memberShip->me_validity_length);
                break;
            case 'week':$dt->addWeeks($memberShip->me_validity_length);
                break;
            case 'month':$dt->addMonths($memberShip->me_validity_length);
                break;
            case 'year':$dt->addYears($memberShip->me_validity_length);
                break;
        }
        $classes            = $memberShip->classmember->pluck('cl_name', 'cl_id')->toArray();
        $servicesCollection = $memberShip->servicemember;
        $services           = array();
        //dd($servicesCollection);
        foreach ($servicesCollection as $ser) {
            $services[$ser->id] = $ser->category == 1 ? $ser->team_name : $ser->one_on_one_name;
        }
        //['every_month' => 'Every month','single_payment'=>'Single payment','1st_15th'=>'1st and 15th','10th_25th'=>'10th and 25th','1week'=>'Every 1 Week','4week'=>'Every 4 Week','2month'=>'Every 2 Months','4month'=>'Every 4 Months']
        $ddt      = Carbon::now();
        $renwPlan = array_key_exists('renwPlan', $extra) ? $extra['renwPlan'] : $memberShip->me_installment_plan;
        //$renwPeriod = array_key_exists('renwPeriod', $extra)?$extra['renwPeriod']:0;
        switch ($renwPlan) {
            case 'single_payment':
                $ddt = null;
                break;
            case 'every_month':
            case 'every_months':
                /*$todayDay = $ddt->day;
                $this->setDateToRenwPeriod($ddt, $todayDay, $renwPeriod);
                $todayDay = $ddt->day;
                $ddt->addMonth( 1 );
                $newDay = $ddt->day;
                if($todayDay != $newDay){
                $newYear = $ddt->year;
                $newMonth = --$ddt->month;
                $newDay = $todayDay - $newDay;
                $ddt = new Carbon("$newYear-$newMonth-$newDay");
                }*/
                break;
            case '1st_15th':
                if ($ddt->day < 15) {
                    $ddt->day = 15;
                } else {
                    $ddt->addMonth(1);
                    $ddt->day = 1;
                }
                break;
            case '10th_25th':$ddt->day = $ddt->day < 10 ? 10 : 25;
                break;
            case '1week':
            case '4week':
                $ddt->addWeek(substr($renwPlan, 0, 1));
                break;
            case '2month':
            case '4month':
                $ddt->addMonth(substr($renwPlan, 0, 1));
                break;
            case 'every_week':
                /*$dayOfWeek = $ddt->dayOfWeek;
                if(!$dayOfWeek)
                $dayOfWeek = 7;//Fixing for sunday. Carbon return 0 but we consider 7 for sunday
                $this->setDateToRenwPeriod($ddt, $dayOfWeek, $renwPeriod);
                $ddt->addWeek( 1 );*/
                break;
        }
        $insertData = array('cm_client_id' => $clientId, 'cm_membership_id' => $memberShip->id, 'cm_label' => $memberShip->me_membership_label, 'cm_validity_length' => $memberShip->me_validity_length, 'cm_validity_type' => $memberShip->me_validity_type, 'cm_class_limit' => $memberShip->me_class_limit, 'cm_class_limit_length' => $memberShip->me_class_limit_length, 'cm_class_limit_type' => $memberShip->me_class_limit_type, 'cm_auto_renewal' => $memberShip->me_auto_renewal, 'cm_renw_plan' => $renwPlan, 'cm_renw_amount' => $memberShip->me_installment_amt/*, 'cm_total_amount' => $memberShip->membership_totaltax ? $memberShip->membership_totaltax : $memberShip->me_installment_amt*//*, 'cm_renw_date'=>$renwPeriod*/, 'cm_services' => json_encode($services), 'cm_classes' => json_encode($classes), 'cm_enrollment_limit' => $memberShip->me_enrollment_limit, 'cm_start_date' => $timestamp, 'cm_end_date' => $dt->toDateTimeString(), 'cm_subscription_type' => $subscriptionType);
        if ($ddt) {
            $insertData['cm_due_date'] = $ddt->toDateTimeString();
        }
        return ClientMember::create($insertData);
    }

    public function salesProcSettings($id, Request $request)
    {
        // dd($request->all());
        $client = Clients::findOrFailClient($id);

        if (!Auth::user()->hasPermission(Auth::user(), 'edit-client')) {
            abort(404);
        }

        $data                    = ['steps' => [], 'teamCount' => '', 'indivCount' => '', 'order' => json_decode($request->salesNestable, 1), 'session' => []];
        $client->is_bookbench_on = 0;
        if ($request->has('saleStepGen')) {
            $data['steps'] = $request->saleStepGen;
            if (in_array(4, $request->saleStepGen)) {
                $client->is_bookbench_on = 1;
            }

        }

        if ($request->has('consultExpDate')) {
            $consultExpDate          = new Carbon($request->consultExpDate);
            $client->consul_exp_date = $consultExpDate->toDateString();
        }

        if ($request->has('saleStepSession')) {
            if (in_array('bookTeam', $request->saleStepSession) && $request->teamNumb) {
                $data['teamCount'] = $request->teamNumb;
            }

            if (in_array('bookIndiv', $request->saleStepSession) && $request->indivNumb) {
                $data['indivCount'] = $request->indivNumb;
            }
        }
        if (count($data['order'])) {
            $session    = [];
            $team       = teamBookingSteps();
            $teamed     = teamAttendSteps();
            $indiv      = indivBookingSteps();
            $indived    = indivAttendSteps();
            $teamCount  = 0;
            $indivCount = 0;

            foreach ($data['order'] as $arr) {
                $value = explode('-', $arr['id']);
                if ($value[0] == 'team') {
                    $session[] = $team[$teamCount];
                    if (in_array($teamed[0], $data['steps'])) {
                        $session[] = $teamed[$teamCount];
                    }

                    $teamCount++;
                } else if ($value[0] == 'indiv') {
                    $session[] = $indiv[$indivCount];
                    if (in_array($indived[0], $data['steps'])) {
                        $session[] = $indived[$indivCount];
                    }

                    $indivCount++;
                }
            }
            $data['session'] = $session;
        }
        $client->sale_process_setts = json_encode($data);
        $client->save();

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
            $newStatus              = preventActiveContraOverwrite($clientOldStatus, $newStatus);
            $client->account_status = $newStatus;
            $client->save();
            $this->processSalesProcessOnStatusChange($client, $clientOldStatus, $newStatus, 'Sales settings changed');
        }

        return redirect()->route('clients.show', $id);
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

    protected function allMemberShipData()
    {
        $allMemberShipData = [];

        if (isUserEligible(['Admin'], 'list-membership')) {
            $memberShip = MemberShip::with('categorymember')->where('me_business_id', Session::get('businessId'))->get();
            $memCat     = [];
            foreach ($memberShip as $mValue) {
                if ($mValue->categorymember->count()) {
                    $memCat = $mValue->categorymember->pluck('mc_category_value')->toArray();
                }
                $tax = isset($mValue->membership_totaltax)?$mValue->membership_totaltax:0;
                $effectivePrice = $mValue->me_installment_amt + ($mValue->me_installment_amt * $tax)/100;
                $allMemberShipData[$mValue['id']] = ['name' => $mValue->me_membership_label, 'length' => $mValue->me_validity_length, 'lengthUnit' => $mValue->me_validity_type, 'instAmount' => $mValue->me_unit_amt == null ? 0 :$mValue->me_unit_amt, 'membershipTax' => $tax, 'memCatgory' => $memCat, 'memUnitPrice' =>isset($mValue->me_unit_amt)?$this->perClassPrice($mValue->me_class_limit_length,$mValue->me_unit_amt):0,'classLimit' => $mValue->me_class_limit_length];
            }
        }
        return $allMemberShipData;
    }

    /*public function updateReferredBy($id, Request $request){
    $msg=[];
    $msg['status']='error';
    $parq=Parq::where('client_id',$id)->first();
    if($parq->count()){
    $parq->referralNetwork = $request->newreferralNetwork;
    if($request->clientId)
    $parq->referralId = $request->referralId;
    elseif($request->staffId)
    $parq->referralId = $request->referralId;
    elseif($request->proId)
    $parq->referralId = $request->referralId;

    if($parq->save()){
    $msg['status']='updated';
    //$msg['']=
    }
    }
    return json_encode($msg);
    }*/

    /*public function editDateOfSalesProcess(Request $request){
    dd('check ok');
    }*/

    protected function calcConsExpir($consultDate, $expDur, $expType)
    {
        $dt = Carbon::parse($consultDate);

        if ($expType != null) {
            if ($expType == 'day') {
                $exp_date = $dt->addDays($expDur)->toDateString();
            }

            if ($expType == 'week') {
                $exp_date = $dt->addWeeks($expDur)->toDateString();
            }

            if ($expType == 'month') {
                $exp_date = $dt->addMonths($expDur)->toDateString();
            }

        } else {
            $exp_date = $consultDate;
        }

        return $exp_date;
    }

    /**
     * Get all memebrship service detail
     *
     * @param membership id
     * @return membership details
     */
    public function getMembService(Request $request)
    {
        // dd($request->all());
        $services         = array();
        $pivotData        = array();
        $services_limit   = array();
        $clientMemberData = array();
        $clientId         = $request->clientId;
        $membId           = $request->membershipId;

        // $clientMember = ClientMember::where('cm_client_id',$clientId)->orderBy('id','desc')->first();
        $clientMember = ClientMember::where('cm_client_id', $clientId)
            ->orderBy('id', 'desc')
            ->where('cm_status', 'Active')
            ->first();

        if (count($clientMember)) {
            if ($clientMember->cm_status == 'Active' && $clientMember->cm_membership_id == $membId) {
                if ($clientMember->cm_services_limit != '') {
                    $services_limit = json_decode($clientMember->cm_services_limit, true);
                }
            }
        }

        $membership = MemberShip::find($membId);
        if (count($membership) && count($membership->serviceMemberWithPivot)) {
            foreach ($membership->serviceMemberWithPivot as $servWithPivot) {
                /*if($servWithPivot->category == 1) // TEAM
                $services[$servWithPivot->id] = ucfirst($servWithPivot->team_name);
                else if($servWithPivot->category == 2) // 1 on 1
                $services[$servWithPivot->id] = ucfirst($servWithPivot->one_on_one_name);*/

                $pivotData[$servWithPivot->id]['limit']      = $servWithPivot['pivot']->sme_service_limit;
                $pivotData[$servWithPivot->id]['limit_type'] = $servWithPivot['pivot']->sme_service_limit_type;
            }
        }

        $serivesesData = Service::where('business_id', Session::get('businessId'))->select('id', 'category', 'team_name', 'one_on_one_name', 'team_price', 'one_on_one_price')->get();
        if ($serivesesData->count()) {
            foreach ($serivesesData as $serivesData) {
                if ($serivesData->category == 1) {
                    // TEAM
                    $services[$serivesData->id]['name']  = ucfirst($serivesData->team_name);
                    $services[$serivesData->id]['price'] = $serivesData->team_price;
                } else if ($serivesData->category == 2) {
                    // 1 on 1
                    $services[$serivesData->id]['name']  = ucfirst($serivesData->one_on_one_name);
                    $services[$serivesData->id]['price'] = $serivesData->one_on_one_price;
                }

                if (isset($services_limit[$serivesData->id]) && isset($services_limit[$serivesData->id]['price'])) {
                    $services[$serivesData->id]['editedPrice'] = $services_limit[$serivesData->id]['price'];
                } else {
                    $services[$serivesData->id]['editedPrice'] = 0;
                }

            }
        }

        if (count($services_limit)) {
            $extraData = $services_limit;
        } else {
            $extraData = $pivotData;
        }

        $response = array('service' => $services, 'extradata' => $extraData);

        return json_encode($response);
    }

    /**
     * Update existing membership invoice.
     *
     * @param Object $invoiceObj, Array $invUpdateData['total','currentMembrId','existMembrId','taxPerc','dueDate','disc']
     * @return
     */
    protected function updateMembInvoice($invoiceObj, $invUpdateData)
    {
        $invItemObj = $invoiceObj->invoiceitem()->where('inp_product_id', $invUpdateData['existMembrId'])->where('inp_type', 'membership')->orderBy('inp_id', 'asc')->first();

        $tax                      = round((($invUpdateData['taxPerc'] * $invUpdateData['total']) / 100), 2);
        $invoiceObj->inv_total    = $invUpdateData['total'];
        $invoiceObj->inv_incl_tax = $tax;
        $invoiceObj->inv_due_date = $invUpdateData['dueDate'];
        $invoiceObj->save();

        $invItemObj->inp_product_id = $invUpdateData['currentMembrId'];
        $invItemObj->inp_price      = $invUpdateData['total'] - $tax;
        $invItemObj->inp_total      = $invUpdateData['total'];
        if (array_key_exists('disc', $invUpdateData)) {
            $invItemObj->inp_item_desc = $invUpdateData['disc'];
        }

        $invItemObj->save();
    }

    /**
     * Add more membership item in client membership
     *
     * @param
     * @return
     */
    protected function newInvItemInOldInvoice($invoiceObj, $total, $existMembrId, $invoiceGoesTo, $adjustedEmiAmount, $taxPerc)
    {

        $tax                      = round((($taxPerc * $total) / 100), 2);
        $oldinvItemObj            = $invoiceObj->invoiceitem()->where('inp_product_id', $existMembrId)->where('inp_type', 'membership')->orderBy('inp_id', 'asc')->first();
        $oldinvItemObj->inp_price = ($total - $adjustedEmiAmount) - $tax;
        $oldinvItemObj->inp_total = ($total - $adjustedEmiAmount);
        $oldinvItemObj->save();

        $invItemObj          = $oldinvItemObj->replicate();
        $invItemObj->inp_tax = 'N/A';
        if ($invoiceGoesTo == 'upgrade') {
            $invItemObj->inp_item_desc = 'Memebrship upgrade adjustment' . ' on ' . Carbon::now()->format('D, d M Y');
            $invItemObj->inp_total     = $adjustedEmiAmount;
            $invItemObj->inp_price     = $adjustedEmiAmount;
            $invoiceObj->inv_status    = 'Unpaid';
        } else {
            $invItemObj->inp_item_desc = 'Memebrship downgrade adjustment' . ' on ' . Carbon::now()->format('D, d M Y');
            $invItemObj->inp_total     = -($adjustedEmiAmount);
            $invItemObj->inp_price     = -($adjustedEmiAmount);
        }

        $invItemObj->save();

        $invoiceObj->inv_total    = $total;
        $invoiceObj->inv_incl_tax = $tax;
        $invoiceObj->save();
    }

    /**
     * On delete membership return remening amount in epic credit
     *
     * @param Object ClientMembership class
     * @return Array $response[]
     */
    protected function onDelMembReturnAmount($clientId, $activeMemebship, $isSetEpic = false)
    {
        $response      = array('isError' => false);
        $clientMembrId = $activeMemebship->id;
        $invoice       = \App\Invoice::where('inv_business_id', Session::get('businessId'))
            ->where('inv_client_id', $clientId)
            ->whereHas('invoiceitem', function ($query) use ($clientMembrId) {
                $query->where('inp_product_id', $clientMembrId)
                    ->where('inp_type', 'membership');
            })
            ->first();

        $fromDate = Carbon::parse($activeMemebship->cm_start_date);
        $toDate   = Carbon::tomorrow();
        if (count($invoice) && $fromDate->lt($toDate)) {
            $days   = $fromDate->diffInDays($toDate);
            $amount = $invoice->inv_total;
            if ($activeMemebship->cm_validity_type == 'year') {
                $perDayAmount = ($amount / 365);
            } elseif ($activeMemebship->cm_validity_type == 'month') {
                $perDayAmount = (($amount * 12) / 365);
            } elseif ($activeMemebship->cm_validity_type == 'week') {
                $perDayAmount = (($amount * 52) / 365);
            } elseif ($activeMemebship->cm_validity_type == 'day') {
                $perDayAmount = $amount;
            }

            $remaingAmount = $amount - ($perDayAmount * $days);
            if ($invoice->inv_status == 'Paid') {
                $response['isError'] = true;
                $response['status']  = 'epicBal';
                $response['amount']  = round($remaingAmount, 2);
                if ($isSetEpic) {
                    $amount  = $response['amount'];
                    $purpose = 'memb_ship_adj';
                    $this->updateEpicCredit($clientId, -($amount), $purpose);
                }
            } else {
                /*$memberShip = MemberShip::OfBusiness()->find($activeMemebship->cm_membership_id);
                $taxPerc = $memberShip::calcTotalTax($memberShip);
                $this->updateMembInvoice($invoice, $remaingAmount, $activeMemebship->id, $activeMemebship->id, $taxPerc, '');*/
                $response['isError'] = false;
            }
        }
        return $response;
    }

    /**
     * On Client status change set membership inactive
     *
     * @param String $accStatus
     * @param Int $clientId
     *
     * @return void
     */
    protected function setMembershipDelete($accStatus, $clientId)
    {
        $clientMember = ClientMember::where('cm_client_id', $clientId)->orderBy('id', 'desc')->first();
        if (count($clientMember)) {
            if ($accStatus == 'on-hold' || $accStatus == 'On Hold') {
                $clientMember->update(['cm_status' => 'On Hold']);
            } elseif ($accStatus == 'inactive' || $accStatus == 'Inactive') {
                $this->onDelMembReturnAmount($clientId, $clientMember, true);
                $clientMember->delete();
            }
        }
    }

    /**
     * Get client event invoice
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function getClientEventInvoice(Request $request)
    {
        $isWithInvoice = 0;
        if($request->eventType == 'class') 
            $isWithInvoice = DB::table('staff_event_class_clients')
                            ->select('secc_with_invoice')
                            ->whereNull('deleted_at')
                            ->where('secc_sec_id', $request->eventId)
                            ->where('secc_client_id', $request->clientId)
                            ->first()
                            ->secc_with_invoice;           
        else if($request->eventType == 'service')
            $isWithInvoice = StaffEventSingleService::whereNull('deleted_at')
                            ->select('sess_with_invoice')
                            ->where('sess_id', $request->eventId)
                            ->where('sess_client_id', $request->clientId)
                            ->first()
                            ->sess_with_invoice;  
                  
        $invoices = Invoice::where('inv_client_id', $request->clientId)
            ->whereHas('invoiceitem', function ($query) use ($request) {
                $query->where('inp_product_id', $request->eventId)
                    ->where('inp_type', $request->eventType);
            })->first();

        if ($invoices) {
            $invoices = $invoices->toArray();

            $epicCreditUsed = InvoiceItems::where('inp_invoice_id', $invoices['inv_id'])->sum('inp_paid_using_epic_credit');

            $invoices += array('inv_credit_used' => $epicCreditUsed);

            return json_encode(['status' => 'success', 'invoice' => $invoices]);
        } else 
            return json_encode(['status' => 'failed', 'invoice' => []]);

    }

    /**
     * Check client satisfied membership or not
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function isClientMembershipSatisfy(Request $request)
    {
        if ($request->eventType == 'class') {
            $eventDate    = StaffEventClass::where('sec_id', $request->eventId)->pluck('sec_date')->first();
            $eventClassId = StaffEventClass::where('sec_id', $request->eventId)->pluck('sec_class_id')->first();
        } else {
            $eventDate    = StaffEventSingleService::where('sess_id', $request->eventId)->pluck('sess_date')->first();
            $eventClassId = StaffEventSingleService::where('sess_id', $request->eventId)->pluck('sess_service_id')->first();
        }
        $clientMembership = $this->satisfyMembershipRestrictions($request->clientId, ['event_type' => $request->eventType, 'event_id' => $eventClassId, 'event_date' => $eventDate]);
        return json_encode(['success' => true, 'satisfy' => $clientMembership['satisfy']]);
    }

    /**
     * Get membership id
     *
     * @param Number $clientMembershipId
     *
     * @return JSON
     */
    public function getMembershipId($clientMembershipId)
    {
        $membershipId = ClientMember::where('id', $clientMembershipId)->pluck('cm_membership_id')->first();

        return ['status' => $membershipId ? true : false, 'membershipId' => $membershipId ? $membershipId : ''];
    }

    /**
     * Get membership details
     *
     * @param Number $membershipId
     *
     * @return JSON
     */
    public function getMembershipDetails($membershipId)
    {
        $membershipDetails = MemberShip::findOrFail($membershipId);

        return ['status' => $membershipDetails ? true : false, 'membership' => $membershipDetails ? $membershipDetails : []];

    }

    /**
     * Get client future recuring classes
     *
     * @param Number $clientId
     *
     * @return JSON
     */
    public function getFutureRecureClasses($clientId)
    {
        $isError             = false;
        $today               = Carbon::now();
        $client              = Clients::findClient($clientId);
        $futureClasses       = $client->futureClasses;
        $futureRecureClasses = [];

        if (!$futureClasses) {
            $isError = true;
            return ['status' => false, 'futureRecureClasses' => []];
        }

        if (!$isError) {
            $futureClasses = $futureClasses->sort(function ($firstEvent, $secondEvent) {
                if ($firstEvent->eventDate === $secondEvent->eventDate) {
                    if ($firstEvent->eventTime === $secondEvent->eventTime) {
                        return 0;
                    }

                    return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
                }
                return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
            });

            $futureClasses = $futureClasses->filter(function ($value) {
                return  $value->deleted_at == null && $value->pivot->deleted_at == null && $value->pivot->secc_if_recur == 1 && $value->pivot->secc_cmid != 0;
            });

            $recuringEvents = array_unique(array_column($futureClasses->toArray(), 'sec_secr_id'));

            foreach ($recuringEvents as $recuringEvent) {
                $futureRecureClasses[] = $futureClasses->where('sec_secr_id', $recuringEvent)->first();
            }
            return ['status' => $futureRecureClasses ? true : false, 'futureRecureClasses' => $futureRecureClasses ? $futureRecureClasses : []];
        }
    }

    /**
     * Get client event booking details
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function getClientEventBookingDetails(Request $request)
    {
        $response = [];

        if ($request->clientId && $request->eventId && $request->eventType) {
            if ($request->eventType == 'class') {
                $clientBookingDetails = DB::table('staff_event_class_clients')->whereNull('deleted_at')->where('secc_sec_id', $request->eventId)->where('secc_client_id', $request->clientId)->first();
            } else if ($request->eventType == 'service') {
                $clientBookingDetails = StaffEventSingleService::whereNull('deleted_at')->where('sess_id', $request->eventId)->where('sess_client_id', $request->clientId)->first();
            }

            if ($clientBookingDetails) {
                $response['status']         = true;
                $response['bookingDetails'] = $clientBookingDetails;
            } else {
                $response['status']         = false;
                $response['bookingDetails'] = [];
            }

        } else {
            $response['status']         = false;
            $response['bookingDetails'] = [];
        }

        return json_encode($response);
    }

    /**
     * get tax data
     * @param taxAppliedId
     * @return Array tax data
    **/
    protected function getTax($taxAppliedId){
        $data = MemberShipTax::where('mtax_business_id',Session::get('businessId'))->select('id','mtax_label','mtax_rate')->get();

        $response = array('taxdata'=>array(),'alltax'=>array());
        if($data->count()){
            if($taxAppliedId)
                $response['taxdata'] = $data->where('id', $taxAppliedId)->first();
            else 
                $response['taxdata'] = $data->first();

            $response['alltax'] = $data->toArray();
        }
        return $response;
    }

    /**
     * Save client menues
     * @param Request request
    **/
    public function saveMenues($clientId, Request $request) {
        $prevSelectedMenus = ClientMenu::where('client_id', $clientId)->first();
        $selectedMenus = $request->has('menuOptions') ? implode(',', $request->menuOptions) : '';

        if($prevSelectedMenus) {
            $prevSelectedMenus->menues = $selectedMenus;
            $prevSelectedMenus->save();
        } else {
            $clientMenu = new ClientMenu;
            $clientMenu->client_id =  $clientId;
            $clientMenu->menues      = $selectedMenus;
            $clientMenu->save();
        }

        return redirect()->back();
    }

    /**
     * Show access restricted view
     * @return View
    **/
    public function accessRestricted() {
        return view('Result.access_restricted');
    }

    /**
     * Check if Client Can book in LDC class or not
     * 
     * @param Request 
     * @return response json
     */
    public function isClientLdcSatisfy(Request $request){
        $clientId = $request->clientId;
        $eventId = $request->eventId;
        $type = $request->eventType;
        $isSatisfy = $this->isSatisfyLdcRestriction($clientId,$eventId,'',$type);
             $responseData = [
            'status' => 'ok',
            'isSatisfy' => $isSatisfy
        ];
        return response()->json($responseData);
        
    }

    public function operateAsClient($id){
        $client = Clients::where('id',$id)->first();
        if($client->login_with_email){
            $business = Business::where('id',session()->get('businessId'))->first();
            if($business){
                if(session()->has('operateAsClient') && session()->get('operateAsClient')){
                    session(['operateAsClient' => true,'operateClientId' => $id]);
                    $response = [
                        'status' => 'ok',
                        'operateAsClient' => "yes",
                        'bussName' => $business->cp_web_url
                    ];
                    return response()->json($response);
                }else{
                    session(['operateAsClient' => true,'operateClientId' => $id]);
                    $response = [
                        'status' => 'ok',
                        'operateAsClient' => "no",
                        'bussName' => $business->cp_web_url
                    ];
                    return response()->json($response);
                }
            }else{
                $response = [
                    'status' => 'error',
                    'message' => 'Something went wrong'
                ];
                return response()->json($response);
            }
            
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Client is not allowed to login'
            ];
            return response()->json($response);
        }
    }

    /**
     * Activity Plan
     */
    public function createActvityPlan($id){
        $client = Clients::find($id);
        $calendar_settings = CalendarSetting::where('cs_business_id',Session::get('businessId'))->whereIn('cs_client_id',array(0,$id))->orderBy('id', 'DESC')->first()->toArray();
        $exerciseData = $this->getExercisesOptions();
        return view('ActivityPlan.calendar',compact('calendar_settings','client','exerciseData'));
    }

    public function perClassPrice($length, $amnt)
    {
        // // $returnData = [];
        // $classesInWeek = $length;
        // if ($type == 'every_month') {
        //     $classesInWeek = round($classesInWeek / 4.33);
        // }

        // $weekCost             = 0;
        // // $classesAllowedInMemb = $classesAllowedInPlan = $classesInWeek;
        // switch ($validity) {
        //     case 'day':
        //         $weekCost             = $amnt * 7;
        //         // $classesAllowedInMemb = 1;
        //         break;
        //     case 'month':
        //         $weekCost             = $amnt / 4.33333333; 
        //         // $classesAllowedInMemb = round($classesInWeek * 4.33333333);
        //         break;
        //     case 'year':
        //         $weekCost             = $amnt / 52;
        //         // $classesAllowedInMemb = $classesInWeek * 52;
        //         break;
        //     default:
        //         $weekCost = $amnt;
        //         break;
        // }
        // // $classesAllowedInMemb *= $clientMember->cm_validity_length;
        $price = ($amnt * 12)/52;
        $returnData = sprintf('%0.2f',$price/$length);
        return $returnData;
    }

    public function getFutureRecureSessions(Request $request){
        $isError             = false;
        $today               = Carbon::now();
        $futureRecureSessions = [];
        $clients = Clients::findOrFailClient($request->clientId);
         $clientMemb = Clients::paidMembership($request->clientId);
         if($request->sessionType == 'class'){
         $sessionData = json_decode($clientMemb->cm_session_limit, 1);
         $sessionLimit = $sessionData[$request->classTypeId]['limit'];
           if($sessionLimit > $request->limit){
            $futureSession  = $clients->futureExtraSessions;
            if (!$futureSession) {
                $isError = true;
                return ['status' => false, 'futureRecureClasses' => []];
            }
            if (!$isError) {
                $futureSession = $futureSession->sort(function ($firstEvent, $secondEvent) {
                    if ($firstEvent->eventDate === $secondEvent->eventDate) {
                        if ($firstEvent->eventTime === $secondEvent->eventTime) {
                            return 0;
                        }
    
                        return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
                    }
                    return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
                });
    
                $futureSession = $futureSession->filter(function ($value) {
                    return  $value->deleted_at == null && $value->pivot->deleted_at == null && $value->pivot->secc_if_recur == 1 && $value->pivot->secc_cmid != 0;
                });
    
                $recuringEvents = array_unique(array_column($futureSession->toArray(), 'sec_secr_id'));
                
                foreach ($recuringEvents as $recuringEvent) {
                    $sessionExtraData = $futureSession->where('sec_secr_id', $recuringEvent)->first();
                        if($request->type == 'every_week'){
                            $carbonDate    = Carbon::createFromFormat('Y-m-d', $sessionExtraData->sec_date);
                            $weekStartDate = $carbonDate->copy()->startOfWeek();
                            $weekEndDate   = $carbonDate->copy()->endOfWeek();
                            $staffEventClassCount =  StaffEventClass::whereBetween('sec_date',[$weekStartDate,  $weekEndDate])->whereHas('clas.cat', function($q) use($request){
                                $q->where('clcat_id',$request->classTypeId);
                            })->whereHas('clientsRaw', function($q) use($request){ $q->where('secc_client_id', $request->clientId)->where('secc_class_extra',1)->where(function($query){
                            $query->whereNull('staff_event_class_clients.deleted_at')
                            ->orWhere(function($qu){
                                $qu->where('secc_if_make_up_created',1)
                                ->where('staff_event_class_clients.deleted_at','!=',null);
                                });
                            });
                            })->count();
                            if($staffEventClassCount > $request->limit){
                                $futureRecureSessions[] = $sessionExtraData;
                            }
                        }else if($request->type == 'every_month'){
                            $carbonDate    = Carbon::createFromFormat('Y-m-d', $sessionExtraData->sec_date);
                            $monthStartDate = $carbonDate->copy()->startOfMonth();
                            $monthEndDate   = $carbonDate->copy()->endOfMonth();
                            $staffEventClassCount =  StaffEventClass::whereBetween('sec_date',[$monthStartDate,  $monthEndDate])->whereHas('clas.cat', function($q) use($request){
                                $q->where('clcat_id',$request->classTypeId);
                            })->whereHas('clientsRaw', function($q) use($request){ $q->where('secc_client_id', $request->clientId)->where('secc_class_extra',1)->where(function($query){
                                $query->whereNull('staff_event_class_clients.deleted_at')
                                ->orWhere(function($qu){
                                    $qu->where('secc_if_make_up_created',1)
                                    ->where('staff_event_class_clients.deleted_at','!=',null);
                                });
                            });
                            })->count();
                            if($staffEventClassCount > $request->limit){
                                $futureRecureSessions[] = $sessionExtraData;
                            }
                        }else{
                            $carbonDate    = Carbon::createFromFormat('Y-m-d', $sessionExtraData->sec_date);
                            $year =   $carbonDate->copy()->format('Y');
                            $weekDate = $carbonDate->copy();
                            // dd($weekDate);
                
                            $weekNo = $weekDate->weekOfYear;
                            $weekRemainder = $weekNo % 2;
                            if($weekRemainder == 1){
                                $startFortnightWeek = $carbonDate->copy()->startOfWeek();
                                $currentDate = Carbon::now(); 
                                $currentDate->setISODate($year, $weekNo+1); 
                                $endFortnightWeek = $currentDate->endOfWeek(); 
                            
                            }else{
                                $currentDate = Carbon::now();
                                $currentDate->setISODate($year, $weekNo-1);
                                $startFortnightWeek = $currentDate->startOfWeek();
                                $endFortnightWeek   = $carbonDate->copy()->endOfWeek();
                            }
                            $staffEventClassCount =  StaffEventClass::whereBetween('sec_date',[$startFortnightWeek,  $endFortnightWeek])->whereHas('clas.cat', function($q) use($request){
                                $q->where('clcat_id',$request->classTypeId);
                            })->whereHas('clientsRaw', function($q) use($request){ $q->where('secc_client_id', $request->clientId)->where('secc_class_extra',1)->where(function($query){
                                $query->whereNull('staff_event_class_clients.deleted_at')
                                ->orWhere(function($qu){
                                    $qu->where('secc_if_make_up_created',1)
                                    ->where('staff_event_class_clients.deleted_at','!=',null);
                                });
                            });
                            })->count();
                            if($staffEventClassCount > $request->limit){
                                $futureRecureSessions[] = $sessionExtraData;
                            }
                        }
                    
                  
                }
             
                return ['status' => $futureRecureSessions ? true : false, 'futureRecureSessions' => $futureRecureSessions ? $futureRecureSessions : [], 'type' => 'class'];
            }

           }  
        }else if($request->sessionType == 'service'){
            $sessionData = json_decode($clientMemb->cm_services_limit, 1);
            $sessionLimit = $sessionData[$request->classTypeId]['limit'];
            $now = new Carbon();
            $date = setLocalToBusinessTimeZone($now);    
              if($sessionLimit > $request->limit){
               $futureSession  = $clients->events()->with('service')->where('sess_start_datetime', '>=', $date->toDateTimeString())->where('sess_service_id', $request->classTypeId)->get();
               if (!$futureSession) {
                   $isError = true;
                   return ['status' => false, 'futureRecureClasses' => []];
               }
               if (!$isError) {
                   $futureSession = $futureSession->sort(function ($firstEvent, $secondEvent) {
                       if ($firstEvent->eventDate === $secondEvent->eventDate) {
                           if ($firstEvent->eventTime === $secondEvent->eventTime) {
                               return 0;
                           }
       
                           return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
                       }
                       return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
                   });
                   $futureSession = $futureSession->filter(function ($value) {
                       return  $value->deleted_at == null && $value->sess_sessr_id != 0 && $value->sess_cmid != 0;
                   });
                   $recuringEvents = array_unique(array_column($futureSession->toArray(), 'sess_sessr_id'));
                   
                   foreach ($recuringEvents as $recuringEvent) {
                    
                    // $day = Carbon::parse($recuringEvent->sess_start_datetime)->format('D');
                    // $futureRecureSessions[] = StaffEventSingleService::where('sess_sessr_id',$recuringEvent)->whereRaw('WEEKDAY(staff_event_single_services.sess_start_datetime) = 1')->first();
                       $futureRecureSessions[] = $futureSession->where('sess_sessr_id', $recuringEvent)->first();
                           
                      
                   }
                   return ['status' => $futureRecureSessions ? true : false, 'futureRecureSessions' => $futureRecureSessions ? $futureRecureSessions : [], 'type' => 'service'];
               }
   
              }  
        }
         
    }
    public function addGalleryImage(Request $request)
    {
        $images_name_array = explode(',',$request->images_name);
        $mesaurment_gallery = new MeasurementGalleryImage();
        if($request->hasfile('images') && !empty($images_name_array))
        {
            foreach($request->file('images') as $key => $image)
            {
                $file_name = $request->client_id.time().rand(10,100).'.'.$image->extension();
                $image->move(public_path().'/result/gallery-images/', $file_name);  
                $data['image'] = $file_name;
                $data['image_name'] = $images_name_array[$key];
                $data['uploaded_by'] = $request->client_id;
                $mesaurment_gallery->create($data);
            }
        }
        if($mesaurment_gallery)
        {
            // return redirect(route())->with('message','Gallery images uploaded successfully!!');
            return redirect()->route('clients.show', [
            'id' => $request->client_id
            ])->with('message','Gallery images uploaded successfully!!');
        }
    }

    public function addBeforeAfter(Request $request)
    {
        if($request->before_after_id)
        {
            $mesaurment_before_after = MeasurementBeforeAfterImage::where('id',$request->before_after_id)->first();
        }
        else
        {
            $mesaurment_before_after = new MeasurementBeforeAfterImage();
        }

        if($request->before_image_capture)
        {
            $before_image = $this->getFileContent($request->before_image_capture,'capture',$request->client_id);
        }
        if($request->after_image_capture)
        {
            $after_image = $this->getFileContent($request->after_image_capture,'capture',$request->client_id);
        }

        if(isset($request->before_image))
        {
            $before_image = $this->getFileContent($request->before_image,'upload',$request->client_id);
        }
        if(isset($request->after_image))
        {
            $after_image = $this->getFileContent($request->after_image,'upload',$request->client_id);   
        }
        $mesaurment_before_after->title = $request->title;
        $mesaurment_before_after->before_image = $before_image ? $before_image : $mesaurment_before_after->before_image;
        $mesaurment_before_after->after_image = $after_image ? $after_image : $mesaurment_before_after->after_image;
        $mesaurment_before_after->uploaded_by = $request->client_id;
        $mesaurment_before_after->save();
        if($mesaurment_before_after)
        {
            return redirect()->route('clients.show', [
            'id' => $request->client_id
            ])->with('message',' Record saved successfully!!');
        }
    }

    public function deleteBeforeAfter(Request $request)
    {
        $is_exist_before_after = MeasurementBeforeAfterImage::where('id',$request->before_after_id)->first();
        if($is_exist_before_after)
        {
            @unlink(public_path().'/result/before-after-images/'.$is_exist_before_after->after_image );
            @unlink(public_path().'/result/before-after-images/'.$is_exist_before_after->before_image );
            $is_exist_before_after->delete();
            return response()->json([
            'message'=>'Deleted successfully!!',
            'status'=> true
            ]);
        }
    }

    private function getFileContent($uploaded_file,$upload_type,$client_id)
    {
        if($upload_type == 'capture')
        {
            // $folderPath = public_path().'/result/before-after-images/';
            // $image_parts = explode(";base64,",$uploaded_file);
            // $image_type_aux = explode("image/", $image_parts[0]);
            // $image_type = $image_type_aux[1];
            // $image_base64 = base64_decode($image_parts[1]);
            // $file_name = $client_id.time().rand(10,100).'.'.$image_type;
            // $file = $folderPath . $file_name;
            // file_put_contents($file, $image_base64);
            $file_name = $client_id.time().rand(101,200).'.'.$uploaded_file->extension();
            $uploaded_file->move(public_path().'/result/before-after-images/', $file_name); 
        }
        else
        {
            $file_name = $client_id.time().rand(101,200).'.'.$uploaded_file->extension();
            $uploaded_file->move(public_path().'/result/before-after-images/', $file_name);  
        }
        return $file_name;
    }

    public function addProgressForm(Request $request,$client_id)
    {
        return view('progress',compact('client_id'));
    }

    public function saveTempProgress(Request $request)
    {
        if(isset($request->is_exist) && $request->is_exist == 'yes')
        {
            $temp_progress = TempProgressPhoto::where(['id'=>$request->id,'client_id'=>$request->client_id])->first();
            @unlink(public_path().'/result/temp-progress-photos/'.$temp_progress->image );
        }
        elseif (isset($request->remove_uploaded_photo) && $request->remove_uploaded_photo == 'yes') 
        {
            $progress_photos = TempProgressPhoto::where(['client_id'=>$request->client_id])->get()->toArray();
            foreach ($progress_photos as $key => $value) 
            {
                $progress_photo = TempProgressPhoto::where(['client_id'=>$value['client_id']])->first();
                @unlink(public_path().'/result/temp-progress-photos/'.$value['image']);
                $progress_photo->delete();  
            }
            return response()->json([
            'message'=>'files removed successfully!!',
            'status'=> true,
            'data' => null
            ]);

        }
        elseif (isset($request->delete_preview_img) && $request->delete_preview_img == 'yes') 
        {
            $progress_photo = TempProgressPhoto::where(['id'=>$request->id,'client_id'=>$request->client_id])->first();
            @unlink(public_path().'/result/temp-progress-photos/'.$progress_photo->image);
            $progress_photo->delete();  
            $get_temp_progress = TempProgressPhoto::where('client_id',$request->client_id)->get()->toArray();
            return response()->json([
            'message'=>'files removed successfully!!',
            'status'=> true,
            'data' => $get_temp_progress
            ]);

        }
        else
        {
            $temp_progress = new TempProgressPhoto();
        }

        if($request->file('file'))
        {
            $file = $request->file('file');
            $file_name = $request->client_id.time().rand(101,200).'.'.$file->extension();
         
            $file->move(public_path().'/result/temp-progress-photos/', $file_name); 
            $filename = public_path().'/result/temp-progress-photos/'.$file_name;
            $this->correctImageOrientation($filename); 
             
        }

        $temp_progress->client_id = $request->client_id;
        $temp_progress->image = $file_name;
        $temp_progress->image_type = $request->image_type ? $request->image_type : $temp_progress->image_type ;
        $temp_progress->pose_type = $request->pose_type ? $request->pose_type : $temp_progress->pose_type;
        $temp_progress->date = $request->date ? $request->date : $temp_progress->date;
        $temp_progress->save();

        $get_temp_progress = TempProgressPhoto::where('client_id',$request->client_id)->get()->toArray();
        return response()->json([
            'message'=>'files uploaded successfully!!',
            'status'=> true,
            'data' => $get_temp_progress
            ]);
    }

    public  function checkProgressPhotoExist(Request $request)
    {
        if($request->image_type != 'other')
        {
            $is_exist_progress_photo = TempProgressPhoto::where(['client_id'=>$request->client_id,'image_type'=>$request->image_type,'pose_type'=>$request->pose_type])->first();
            if($is_exist_progress_photo)
            {
                return response()->json([
                'message'=>'Photo exist',
                'status'=> true,
                'data'=> ['pose_type'=>ucfirst($request->pose_type),'date'=>date('d-m-Y',strtotime($is_exist_progress_photo->date)),'id'=>$is_exist_progress_photo->id]
                ]);
            }
            else
            {
                return response()->json([
                'message'=>'Photo not exist',
                'status'=> false,
                ]);
            }
        }
        else
        {
            return response()->json([
            'message'=>'Photo not exist',
            'status'=> false,
            ]);
        }
    }

    public function saveProgress(ProgressPhotoValidation $request)
    {
        $temp_destination= public_path('result/temp-progress-photos');
        $orginal_destination= public_path('result/final-progress-photos');
        $temp_progress_photos = TempProgressPhoto::where('client_id',$request->client_id)->get()->toArray();
        if(!empty($temp_progress_photos))
        {
            $gallery_id = strtotime(date('Y-m-d H:i:s'));
            foreach ($temp_progress_photos as $key => $value) 
            {
                $final_progress_photos = new FinalProgressPhoto();
                $temp_image = $temp_destination.'/'.$value['image'];
                rename( $temp_image,$orginal_destination.'/'.basename($value['image']) );
                $final_progress_photos->client_id = $value['client_id'];
                $final_progress_photos->title = $request->title;
                $final_progress_photos->only_admin_manage = isset($request->only_admin_manage) ? $request->only_admin_manage : 'no' ;
                $final_progress_photos->gallery_id = $gallery_id;
                $final_progress_photos->image = $value['image'];
                $final_progress_photos->image_type = $value['image_type'];
                $final_progress_photos->pose_type = $value['pose_type'];
                $final_progress_photos->date = $value['date'];
                $final_progress_photos->save();
                TempProgressPhoto::where('id',$value['id'])->delete();
            }
            return redirect()->route('clients.show', [
            'id' => $request->client_id
            ])->with('message','Record saved successfully!!!');
            
        }
    }

    public function deleteGalleryImage(Request $request)
    {
        if($request->id && $request->image)
        {
            FinalProgressPhoto::where('id',$request->id)->delete();
            @unlink(public_path().'/result/final-progress-photos/'.$request->image);
            return response()->json([
            'message'=>'Image deleted successfully !!',
            'status'=> true,
            ]);
        }
    }

    public function downloadGalleryImage(Request $request,$id,$client_name)
    {
        if($id)
        {
            $final_progress  = FinalProgressPhoto::where('id',$request->id)->first();
            $mime = substr($final_progress->image, strpos($final_progress->image, ".") + 1);  
            $path = public_path(). '/result/final-progress-photos/'. $final_progress->image;
            return response()->download($path, $client_name.'.'.$final_progress->title.'.'.$final_progress->pose_type.'.'.$mime);
        }
    }

    public function showGallery(Request $request,$gallery_id)
    {
        if(isset($request->ajax_show_gallery) && $request->ajax_show_gallery == 'yes')
        {
            /*$temp_created_gallery = FinalProgressPhoto::where('gallery_id',$request->gallery_id)->where('status','temp_created')->get()->toArray();*/
            $temp_created_gallery = FinalProgressPhoto::where('gallery_id',$request->gallery_id)->get()->toArray();
            if(!empty($temp_created_gallery))
            {
                foreach ($temp_created_gallery as $key => $value) 
                {
                    $temp_created_image = FinalProgressPhoto::where('id',$value['id'])->first();
                    if($value['status'] == 'temp_created')
                    {
                        @unlink(public_path().'/result/final-progress-photos/'.$temp_created_image->image);
                        $temp_created_image->delete();    
                    }
                    elseif ($value['replaced_image'] != NULL) 
                    {
                        @unlink(public_path().'/result/final-progress-photos/'.$temp_created_image->image);
                       $temp_created_image->image = $temp_created_image->replaced_image;
                       $temp_created_image->replaced_image = NULL;
                       $temp_created_image->save();
                    }
                }
            }
            FinalProgressPhoto::where('gallery_id',$request->gallery_id)->update(['status'=> NULL]);
            $gallery = FinalProgressPhoto::where('gallery_id',$request->gallery_id)->get()->toArray();
            return response()->json([
            'message'=>'Data get successfully!!',
            'status'=> true,
            'data' => $gallery
            ]);
        } 
        elseif (isset($request->temp_remove_all_photos) && $request->temp_remove_all_photos == 'yes' ) 
        {
            // FinalProgressPhoto::where(['gallery_id'=>$gallery_id,'client_id'=>$request->client_id])->update(['status'=>'temp_deleted']);

            $gallery = FinalProgressPhoto::where(['gallery_id'=>$gallery_id,'client_id'=>$request->client_id])->get()->toArray();
            if(!empty($gallery))
            {
                foreach ($gallery as $key => $value) 
                {
                    if($value['status'] == 'temp_created')
                    {
                        @unlink(public_path().'/result/final-progress-photos/'.$value['image']);
                        FinalProgressPhoto::where('id',$value['id'])->delete();
                    }
                    else
                    {
                        FinalProgressPhoto::where('id',$value['id'])->update(['status'=>'temp_deleted']);
                    }
                }
            }
            return response()->json([
            'message'=>'files removed successfully!!',
            'status'=> true,
            'data' => null
            ]);
        } 
        elseif (isset($request->temp_created) && $request->temp_created == 'yes' ) 
        {
            if($request->file('file'))
            {
                $file = $request->file('file');
                $file_name = $request->client_id.time().rand(101,200).'.'.$file->extension();
                $file->move(public_path().'/result/final-progress-photos/', $file_name);   
            }
            $gallery = new FinalProgressPhoto();
            $gallery->client_id = $request->client_id;
            $gallery->image = $file_name;
            $gallery->image_type = $request->image_type;
            $gallery->pose_type = $request->pose_type;
            $gallery->date = date('Y-m-d',strtotime($request->date)) ;
            $gallery->title = $request->title ;
            $gallery->gallery_id = $request->gallery_id ;
            $gallery->status = 'temp_created';
            $gallery->save();

            $get_gallery =  FinalProgressPhoto::where(['gallery_id'=>$request->gallery_id,'client_id'=>$request->client_id])->where('status',NULL)->orWhere('status','temp_created')->get()->toArray();
            return response()->json([
            'message'=>'files removed successfully!!',
            'status'=> true,
            'data' => $get_gallery
            ]);
        } 
        else if(isset($request->is_exist) && $request->is_exist == 'yes')
        {
            if($request->file('file'))
            {
                $file = $request->file('file');
                $file_name = $request->client_id.time().rand(101,200).'.'.$file->extension();
                $file->move(public_path().'/result/final-progress-photos/', $file_name);   
            }
            $exist_gallery_photo = FinalProgressPhoto::where(['id'=>$request->id,'client_id'=>$request->client_id,'gallery_id'=>$gallery_id])->first();
            $exist_gallery_photo->replaced_image = $exist_gallery_photo->image;
            $exist_gallery_photo->image = $file_name;
            $exist_gallery_photo->save();
            $get_gallery =  FinalProgressPhoto::where(['gallery_id'=>$gallery_id,'client_id'=>$request->client_id])->where('status',NULL)->orWhere('status','temp_created')->get()->toArray();
            return response()->json([
                'message'=>'files uploaded successfully!!',
                'status'=> true,
                'data' => $get_gallery
                ]);
        } 
        elseif (isset($request->delete_preview_img) && $request->delete_preview_img == 'yes') 
        {
            $gallery_photo = FinalProgressPhoto::where(['id'=>$request->id,'client_id'=>$request->client_id,'gallery_id'=>$gallery_id])->first();
            if($gallery_photo->status == 'temp_created')
            {
                @unlink(public_path().'/result/final-progress-photos/'.$gallery_photo->image);
                $gallery_photo->delete();
            }
            else
            {
                $gallery_photo->status = 'temp_deleted' ;
                $gallery_photo->save();
            }
            $get_gallery =  FinalProgressPhoto::where(['gallery_id'=>$gallery_id,'client_id'=>$request->client_id])->where('status',NULL)->orWhere('status','temp_created')->get()->toArray(); 
            return response()->json([
            'message'=>'files removed successfully!!',
            'status'=> true,
            'data' => $get_gallery
            ]);

        }
        else
        {
           $gallery = FinalProgressPhoto::where('gallery_id',$gallery_id)->first();
           return view('gallery',compact('gallery'));    
        }
    }

    public function checkGalleryPhotoExist(Request $request)
    {
        if($request->image_type != 'other')
        {
            $is_exist_gallery_photo = FinalProgressPhoto::where(['client_id'=>$request->client_id,'image_type'=>$request->image_type,'pose_type'=>$request->pose_type,'gallery_id'=>$request->gallery_id])->orderBy('id','desc')->first();
            // dd($is_exist_gallery_photo);
            if($is_exist_gallery_photo && $is_exist_gallery_photo->status != 'temp_deleted')
            {
                return response()->json([
                'message'=>'Photo exist',
                'status'=> true,
                'data'=> ['pose_type'=>ucfirst($request->pose_type),'date'=>date('d-m-Y',strtotime($is_exist_gallery_photo->date)),'id'=>$is_exist_gallery_photo->id]
                ]);
            }
            else
            {
                return response()->json([
                'message'=>'Photo not exist',
                'status'=> false,
                ]);
            }
        }
        else
        {
            return response()->json([
            'message'=>'Photo not exist',
            'status'=> false,
            ]);
        }
    }

    public function editGallery(Request $request)
    {
        $final_gallery_photos = FinalProgressPhoto::where(['client_id'=>$request->client_id,'gallery_id'=>$request->gallery_id])->get()->toArray();
        if(!empty($final_gallery_photos))
        {
            foreach ($final_gallery_photos as $key => $value) 
            {
                if($value['status'] == 'temp_deleted')
                {
                    @unlink(public_path().'/result/final-progress-photos/'.$value['image']);
                    FinalProgressPhoto::where('id',$value['id'])->delete();
                }
                else
                {
                    if ($value['status'] == 'temp_created') 
                    {
                        FinalProgressPhoto::where('id',$value['id'])->update(['status'=>NULL]);
                    }
                    elseif ($value['replaced_image'] != null) 
                    {
                        FinalProgressPhoto::where('id',$value['id'])->update(['replaced_image'=>NULL]);
                    }

                    $final_gallery_photo = FinalProgressPhoto::where('id',$value['id'])->first();
                    $final_gallery_photo->only_admin_manage = isset($request->only_admin_manage) ? $request->only_admin_manage : 'no' ;
                    $final_gallery_photo->title = $request->title;
                    $final_gallery_photo->image_type = $request->image_type;
                    // $final_gallery_photo->pose_type = $request->pose_type;
                    $final_gallery_photo->date = date('Y-m-d',strtotime($request->date));
                    $final_gallery_photo->save();

                }
            }
            return redirect()->route('clients.show', [
            'id' => $request->client_id
            ])->with('message','Record saved successfully!!!');
            
        }
    }
    public function updateMembershipClassPrice($id, $amount){
        ClientMember::where('id',$id)->update(['cm_per_clas_amnt'=>$amount]);
    }
    public function correctImageOrientation($filename){
        if (function_exists('exif_read_data')) {
            $exif = exif_read_data($filename);
            if($exif && isset($exif['Orientation'])) {
              $orientation = $exif['Orientation'];
              if($orientation != 1){
                $img = imagecreatefromjpeg($filename);
                $deg = 0;
                switch ($orientation) {
                  case 3:
                    $deg = 180;
                    break;
                  case 6:
                    $deg = 270;
                    break;
                  case 8:
                    $deg = 90;
                    break;
                }
                if ($deg) {
                  $img = imagerotate($img, $deg, 0);        
                }
                // then rewrite the rotated image back to the disk as $filename 
                imagejpeg($img, $filename, 95);
              } // if there is some rotation necessary
            } // if have the exif orientation info
        } // if function exists 
    }

    public function storeNutritionalJournal(Request $request){
        //    dd($request->all());
            $update = NutritionalJournal::where('client_id',$request->client_id)->first();
            $activity_lavel = '';
            $weight = '';
            $prepare_own_meals = '';
            if(count($request->activity_lavel) > 0){
                $activity_lavel = implode(',',$request->activity_lavel);
            }
            if(count($request->weight) > 0){
                $weight = implode(',',$request->weight);
            }
            if(count($request->prepare_own_meals) > 0){
                $prepare_own_meals = implode(',',$request->prepare_own_meals);
            }
    
            $request->merge([
                'client_id' => $request->client_id,
                'activity_lavel' => $activity_lavel,
                'weight' => $weight,
                'prepare_own_meals' => $prepare_own_meals,
    
            ]);
            if($update){
                $update = $update->update($request->all());
            }else{
                $save = NutritionalJournal::create($request->all());
            }
            
            return redirect()->back();
       } 

       public function storeSleepQuestionnaire(Request $request){
        //    dd($request->all());
            $update = SleepQuestionnaire::where('client_id',$request->client_id)->first();
            if($update){
                $update = $update->update($request->all());
            }else{
                $save = SleepQuestionnaire::create($request->all());
            }
            return redirect()->back();
       } 

       public function storeChronotypeSurvey(Request $request){
        //    dd($request->all());
            $update = ChronotypeSurvey::where('client_id',$request->client_id)->first();
            if($update){
                $update = $update->update($request->all());
            }else{
                $save = ChronotypeSurvey::create($request->all());
            }
            return redirect()->back();
       } 

       public function saveUnit(Request $request){
        $client = Clients::where('id',$request->client_id)->first();
        if(isset($client)){
            $client->update(['unit'=>$request->unit_data]);
            return response()->json(true);
        }
    
    }
}
