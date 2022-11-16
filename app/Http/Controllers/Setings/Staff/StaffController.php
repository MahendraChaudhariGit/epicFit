<?php
namespace App\Http\Controllers\Setings\Staff;
use DateTime;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Business;
use App\Staff;
use App\StaffAttendence;
use DB;
use App\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use App\LocationArea;
//use Illuminate\Support\Facades\Auth;
use Auth;
//use \stdClass;
use App\Http\Traits\StaffEventsTrait;
use App\UserType;
use App\Http\Traits\HelperTrait;
use File;
use App\Http\Traits\TestTrait;
//use App\Models\Access\User\User;
use App\Http\Traits\StaffTrait;
use Input;
use App\SessionRole;
use App\CommissionRole;
use App\CommissionCategory;
use App\CommissionSource;
use App\StaffAttendee;
use App\Location;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class StaffController extends Controller{
    use StaffEventsTrait, HelperTrait, TestTrait, StaffTrait;

    private $cookieSlug = 'staff';

    public function index(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-staff'))
            abort(404);

        $allStaffs = array();
        $search = $request->get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        if($search)
            $allStaffs = Staff::OfBusiness()->where(function($query) use ($search){$query->orWhere('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%")->orWhere('email', 'like', "%$search%");})->paginate($length);
        else
            $allStaffs = Staff::OfBusiness()->paginate($length);

        return view('Settings.staff.index', compact('allStaffs'));
    }

    public function allStaffs(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-staff')){
            if($request->ajax())
                return [];
            else
                abort(404);
        }

        $staffs = Staff::OfBusiness()->get();

        $index = 0;
        $stf = array();
        foreach($staffs as $staff){
            $stf[$index]['id'] = $staff->id;
            $stf[$index]['name'] = $staff->first_name.' '.$staff->last_name;
            $index++;
        }
        return json_encode($stf);
    }


    public function getServicesByArea(Request $request){
        return json_encode(Staff::getServicesByArea($request));
    }

	
    public function uploadFile(Request $request){
        $staff = Staff::find($request->id);
        //if($staff && (isUserType(['Staff']) || Auth::user()->hasPermission(Auth::user(), 'edit-staff'))){
        if($staff){
            //$staffId = (int)$request->id;
            //$staff = Staff::find($staffId);
            $staff->update(array('profile_picture' => $request->photoName));
            return url('/uploads/thumb_'.$request->photoName);
        }
        return '';
    }

    public function getHours(Request $request){
        return $this->getHoursFromTrait($request);
    }


    /**
     * Get edited data from hours table.
     * @param 
     * @return
    **/
    public function getEditHours(Request $request){

        $date = Carbon::createFromFormat('Y-m-d',$request->date)->toDateString();
        $day = $request->day;

        DB::enableQueryLog();
        $staffHours = DB::table('hours')
            ->where('hr_entity_id', $request->staffId)
            ->where('hr_entity_type', 'staff')
            ->where('hr_day', $day)
            ->where('hr_edit_date',$date)
            ->whereNull('deleted_at')
            ->select('hr_day','hr_start_time','hr_end_time','hr_edit_date')
            ->orderBy('hr_id','desc')
            ->get();

        if(!count($staffHours)){
            $staffHours = DB::table('hours')
            ->where('hr_entity_id', $request->staffId)
            ->where('hr_entity_type', 'staff')
            ->where('hr_day', $day)
            ->whereNull('hr_edit_date')
            ->whereNull('deleted_at')
            ->select('hr_day','hr_start_time','hr_end_time','hr_edit_date')
            ->orderBy('hr_id','desc')
            ->get();
        }
        
        $hoursData = array();
        if(count($staffHours)){
            $i = 0;
            foreach ($staffHours as $hours) {
                $hoursData[$i]['hr_day'] = $hours->hr_day;
                $hoursData[$i]['hr_start_time'] = $hours->hr_start_time;
                $hoursData[$i]['hr_end_time'] = $hours->hr_end_time;
                $hoursData[$i]['hr_edit_date'] = $hours->hr_edit_date;
                $i++;
            }
        }
       return json_encode($hoursData);     
    }


    /**
     * Set edited hour data in hour table
     * @param
     * @return
    **/
    public function setEditHours(Request $request){
        $response['status'] = 'error';
        $timestamp = Carbon::now();
        $insertData = array();
        $input = $request->all();
        ksort($input);

        $staffId = $input['staffId'];
        $entityType= 'staff';
        $editedDate = dateStringToDbDate($input['start_attend_date']);

        $dates = array();
        $days = array();
        if($request->has('upto_attend_date') && $input['upto_attend_date'] != ''){
            $start_date = dateStringToDbDate($input['start_attend_date']);
            $upto_date = dateStringToDbDate($input['upto_attend_date']);
            while ($start_date <= $upto_date) {
                $dates[] = $start_date->toDateString();
                $days[] = $start_date->format('l');
                $start_date->addDays(1);
            }
        }

        foreach($input as $key => $value){
            if(strpos($key, 'new_end') !== false || strpos($key, 'old_end') !== false)
                $end_time[] = timeStringToDbTime($value);
            
            else if(strpos($key, 'new_start') !== false || strpos($key, 'old_start') !== false)
                $start_time[] = timeStringToDbTime($value);

        }   

        if(count($dates)){
            DB::table('hours')->whereIn('hr_edit_date', $dates)->where('hr_entity_id', $staffId)->where('hr_entity_type','staff')->whereNull('deleted_at')->delete();

            $insertData = array();
            if($input['staffAttend'] == 'working'){
                for($i = 0; $i < count($dates); $i++) {
                    if(count($end_time) && count($start_time)){
                        for($j = 0; $j < count($end_time); $j++) {
                            $insertData[] = array('hr_entity_id' => $staffId, 'hr_entity_type'=>'staff','hr_edit_date'=>$dates[$i],'hr_day'=>$days[$i],'hr_start_time'=>$start_time[$j],'hr_end_time'=>$end_time[$j]);
                        }
                    }
                }
            }
            elseif($input['staffAttend'] == 'leave'){
               for($i = 0; $i < count($dates); $i++) {
                    $insertData[] = array('hr_entity_id' => $staffId, 'hr_entity_type'=>'staff','hr_edit_date'=>$dates[$i],'hr_day'=>$days[$i],'hr_start_time'=>'00:00:00','hr_end_time'=>'00:00:00'); 
                } 
            }
        }
        else{
            $delDate = dateStringToDbDate($input['start_attend_date'])->toDateString(); 
            DB::table('hours')->where('hr_edit_date', $delDate)->where('hr_entity_id', $staffId)->where('hr_entity_type','staff')->whereNull('deleted_at')->delete();

            $currDate = dateStringToDbDate($input['start_attend_date']);
            $currDays = dateStringToDbDate($input['start_attend_date'])->format('l');

            if($input['staffAttend'] == 'working'){
                if(count($end_time) && count($start_time)){
                    for($j = 0; $j < count($end_time); $j++) {
                        $insertData[] = array('hr_entity_id' => $staffId, 'hr_entity_type'=>'staff','hr_edit_date'=>$currDate,'hr_day'=>$currDays,'hr_start_time'=>$start_time[$j],'hr_end_time'=>$end_time[$j]);
                    }
                }
            }
            elseif($input['staffAttend'] == 'leave'){
                $insertData[] = array('hr_entity_id' => $staffId, 'hr_entity_type'=>'staff','hr_edit_date'=>$currDate,'hr_day'=>$currDays,'hr_start_time'=>'00:00:00','hr_end_time'=>'00:00:00'); 
            }
        } 

        if(count($insertData)){
            if(DB::table('hours')->insert($insertData))
                $response['status'] = 'updated';
        }
        return json_encode($response);
    }


    /**
     * Reset staff hour
     * @param
     * @return
    **/
    public function resetEditHours(Request $request){
        $response['status'] = 'error';
        $staffId = $request->staffId;
        $date = dateStringToDbDate($request->date)->toDateString();
        if(DB::table('hours')->where('hr_edit_date', $date)->where('hr_entity_id', $staffId)->where('hr_entity_type','staff')->whereNull('deleted_at')->delete())
            $response['status'] = 'updated';

        return json_encode($response);
    }

    public function setHours($staffId, Request $request){ 
        if(Staff::ifstaffExist($staffId))
            return $this->setWorkingHours($request, ['mode' => 'edit', 'entityType' => 'staff', 'entityId' => $staffId]);

        return json_encode(['status' => 'error']);
       
    }

    public function store(Request $request){
        $isError = false;
        $msg = [];
        $notIn = ['mailinator','yopmail'];
        if (in_array(explode('.',explode('@',$request->email)[1])[0],$notIn))
        {
            $msg['status'] = 'error';
            $msg['errorData'][] = array('emailExist' => 'Please use your genuine email ids.');
            $isError = true;
        //    return redirect()->back()->with('flash_danger','Mailinator and Yopmail email not excepted here.');
        }
        

        if($request->businessId != Session::get('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-staff')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        
        if(!$isError){
            if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'staff'])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                $isError = true;
            }
            if($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'staff'])){
                $msg['status'] = 'error';
                $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
                $isError = true;
            }
            if(!$isError){
        		$insertData = array('business_id' => $request->businessId, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'job_title' => $request->job_title, 'ut_id' => $request->staff_permissions, 'profile_picture' => $request->staffProfilePictureUpload, 'website' => $request->website, 'facebook' => $request->facebook, 'email' => $request->email, 'phone' => $request->phone, 'city' => $request->city, 'country' => $request->country, 'state' => $request->state, 'postal_code' => $request->postal_code, 'time_zone' => $request->time_zone, 'hourly_payment_label' => $request->hourly_payment_label, 'hourly_rate' => $request->hourly_rate, 'hourly_start_date' => $request->hourly_start_date, 'hourly_end_date' => $request->hourly_end_date, 'commission_payment'=> $request->commission_payment, 'commission_rate'=> $request->commission_rate, 'commission_tax'=> $request->commission_tax,'commission_date_range_start'=> $request->commission_date_range_start,
                    'commission_date_range_end'=> $request->commission_date_range_end, 'per_session_rate_options'=> $request->per_session_rate_options,'per_session_payment_label'=> $request->per_session_payment_label,'per_session_base_rate'=> $request->per_session_base_rate,'per_session_pay_for' => $request->per_session_pay_for,'per_session_start_date' => $request->per_session_start_date,'per_session_end_date' => $request->per_session_end_date );

                if($request->staffBiography)
                    $insertData['biography'] = $this->uploadBiography($request->staffBiography);                   

                $insertData['date_of_birth'] = prepareDob($request->year, $request->month, $request->day);

        		if(isset($request->gender))
                    $insertData['gender'] = $request->gender;
        		if($request->fixed_location == 'Fixed Location'){
        			$insertData['fixed_location'] = 1;
        			$insertData['address_line_one'] = $request->address_line_one;
        			$insertData['address_line_two'] = $request->address_line_two;
        		}
        		if($request->login_with_email)
        			$insertData['login_with_email'] = $request->login_with_email;
        		if($request->clients_book_staff)
        			$insertData['clients_book_staff'] = $request->clients_book_staff;
        		// if($request->disp_location_web)
        		// 	$insertData['disp_location_web'] = $request->disp_location_web;
        		// if($request->disp_location_online)
        		// 	$insertData['disp_location_online'] = $request->disp_location_online;		

        		//$business = Business::find($request->businessId);
        		//$addedStaff = $business->staffs()->create($insertData);
               // dd($insertData);
                $addedStaff = Staff::create($insertData);
                Session::put('ifBussHasStaffs', true);  

                if($request->staff_services != '')
                    Service::updateStaff($request->staff_services, $addedStaff->id);

                /*if($request->staffClasses != '')
                    $addedStaff->classes()->attach($request->staffClasses);*/

                /**** Start: Link Staff to Areas ********/
                if($request->staff_areas != ''){
                    foreach($request->staff_areas as $areaIds)
                        $areaToAttached[$areaIds] = ['as_business_id' => Session::get('businessId')];   
                    
                    $addedStaff->areas()->attach($areaToAttached); 
                }
                /**** End: Link Staff to Areas ********/

                $attachedArea = [];
                if($request->has('areasToLink')){
                    $areasToLink = explode(',', $request->areasToLink);
                    foreach($areasToLink as $area)
                        $attachedArea[$area] = ['as_business_id' => Session::get('businessId')];   
                }
                else if($request->has('locToLink')){
                    $areasToLink = LocationArea::where('la_location_id', $request->locToLink)->select('la_id')->get();
                    if(count($areasToLink)){
                        foreach($areasToLink as $area)
                            $attachedArea[$area->la_id] = ['as_business_id' => Session::get('businessId')]; 
                    }
                }
                if(count($attachedArea)){
                    $addedStaff->areas()->attach($attachedArea); 
                    $linkedWithArea = true;
                }
                else
                    $linkedWithArea = false;

                if($request->staffClasses == '')
                        $staffClasses = [];
                    else
                       $staffClasses = $request->staffClasses;

                     if($request->per_session_class == '')
                        $perSessionClass = [];
                    else
                        $perSessionClass = $request->per_session_class;    
                       
                      $staffMatchSessionClassesIds=array_intersect($staffClasses,$perSessionClass);
                      $staffUnmatchSessionClassesIds=array_diff($staffClasses,$perSessionClass);
                       $staffClassesId=[];
                      foreach($staffMatchSessionClassesIds as $staffClassId){
                        $staffClassesId[$staffClassId] = ['cst_per_session_enable' => 1];
                        }

                      foreach($staffUnmatchSessionClassesIds as $staffClassId){
                        $staffClassesId[$staffClassId] = ['cst_per_session_enable' => 0];
                        }

                 $addedStaff->classes()->attach($staffClassesId);

              ///save session role
                    if($request->per_session_role != '')
                        $addedStaff->sessionrolestaff()->attach($request->per_session_role);

              ///save session service
                    if($request->per_session_service != '')
                        $addedStaff->sessionservicestaff()->attach($request->per_session_service);  
                   
              ///save commission role
                 if($request->commission_role != '')
                        $addedStaff->commissionRoleStaff()->attach($request->commission_role);   

              ///save commission category
                 if($request->commission_income_category != '')
                     $addedStaff->commissionCategorystaff()->attach($request->commission_income_category);
                    
              ///save commission source
                 if($request->commission_referral_source != '')
                    $addedStaff->commissionSourcestaff()->attach($request->commission_referral_source);

                ///save session attendees
                   $allAttendeesData=$this->calcDataForStartAndEndDatetime($addedStaff->id,$request->all());    

                   
                /*$input = $request->all();
                ksort($input);
                $monday_start = $monday_end = $tuesday_start = $tuesday_end = $wednesday_start = $wednesday_end = $thursday_start = $thursday_end = $friday_start = $friday_end = $saturday_start = $saturday_end = $sunday_start = $sunday_end = [];
                if($request->exists('monday')){
                    foreach($input as $key => $value){
                        if(strpos($key, 'monday_start') !== false)
                            $monday_start[] = timeStringToDbTime($value);
                        
                        else if(strpos($key, 'monday_end') !== false)
                            $monday_end[] = timeStringToDbTime($value);
                    }   
                }
                if($request->exists('tuesday')){
                    foreach($input as $key => $value){
                        if(strpos($key, 'tuesday_start') !== false)
                            $tuesday_start[] = timeStringToDbTime($value);
                        
                        else if(strpos($key, 'tuesday_end') !== false)
                            $tuesday_end[] = timeStringToDbTime($value);
                    }   
                }
                if($request->exists('wednesday')){
                    foreach($input as $key => $value){
                        if(strpos($key, 'wednesday_start') !== false)
                            $wednesday_start[] = timeStringToDbTime($value);
                        
                        else if(strpos($key, 'wednesday_end') !== false)
                            $wednesday_end[] = timeStringToDbTime($value);
                    }   
                }
                if($request->exists('thursday')){
                    foreach($input as $key => $value){
                        if(strpos($key, 'thursday_start') !== false)
                            $thursday_start[] = timeStringToDbTime($value);
                        
                        else if(strpos($key, 'thursday_end') !== false)
                            $thursday_end[] = timeStringToDbTime($value);
                    }   
                }
                if($request->exists('friday')){
                    foreach($input as $key => $value){
                        if(strpos($key, 'friday_start') !== false)
                            $friday_start[] = timeStringToDbTime($value);
                        
                        else if(strpos($key, 'friday_end') !== false)
                            $friday_end[] = timeStringToDbTime($value);
                    }   
                }
                if($request->exists('saturday')){
                    foreach($input as $key => $value){
                        if(strpos($key, 'saturday_start') !== false)
                            $saturday_start[] = timeStringToDbTime($value);
                        
                        else if(strpos($key, 'saturday_end') !== false)
                            $saturday_end[] = timeStringToDbTime($value);
                    }   
                }
                if($request->exists('sunday')){
                    foreach($input as $key => $value){
                        if(strpos($key, 'sunday_start') !== false)
                            $sunday_start[] = timeStringToDbTime($value);
                        
                        else if(strpos($key, 'sunday_end') !== false)
                            $sunday_end[] = timeStringToDbTime($value);
                    }   
                }
                $insertData = array();
                if(count($monday_start)){
                    for($i=0; $i<count($monday_start); $i++){
                        $insertData[] = array('hr_entity_id' => $addedStaff->id, 'hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i], 'hr_entity_type' => 'staff');
                    }
                }
                if(count($tuesday_start)){
                    for($i=0; $i<count($tuesday_start); $i++){
                        $insertData[] = array('hr_entity_id' => $addedStaff->id, 'hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i], 'hr_entity_type' => 'staff');
                    }
                }
                if(count($wednesday_start)){
                    for($i=0; $i<count($wednesday_start); $i++){
                        $insertData[] = array('hr_entity_id' => $addedStaff->id, 'hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i], 'hr_entity_type' => 'staff');
                    }
                }
                if(count($thursday_start)){
                    for($i=0; $i<count($thursday_start); $i++){
                        $insertData[] = array('hr_entity_id' => $addedStaff->id, 'hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i], 'hr_entity_type' => 'staff');
                    }
                }
                if(count($friday_start)){
                    for($i=0; $i<count($friday_start); $i++){
                        $insertData[] = array('hr_entity_id' => $addedStaff->id, 'hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i], 'hr_entity_type' => 'staff');
                    }
                }
                if(count($saturday_start)){
                    for($i=0; $i<count($saturday_start); $i++){
                        $insertData[] = array('hr_entity_id' => $addedStaff->id, 'hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i], 'hr_entity_type' => 'staff');
                    }
                }
                if(count($sunday_start)){
                    for($i=0; $i<count($sunday_start); $i++){
                        $insertData[] = array('hr_entity_id' => $addedStaff->id, 'hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i], 'hr_entity_type' => 'staff');
                    }
                }
                if(count($insertData))
                    DB::table('hours')->insert($insertData);*/
                $this->setWorkingHours($request, ['mode' => 'add', 'entityType' => 'staff', 'entityId' => $addedStaff->id]);
                //$this->setHours($addedStaff->id, $request, 'add');

                if($request->login_with_email)
                    $userTypeData = UserType::where('ut_id',$request->staff_permissions)->first();
                    if($userTypeData && $userTypeData->ut_name == 'Staff')
                        $accountType = 'Staff';
                    else
                        $accountType = 'Admin';  
                    $this->callStoreUser(['name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'userTypeId' => $request->staff_permissions, 'accountId' => $addedStaff->id, 'password' => $request->staffNewPwdd,'accountType' => $accountType]);

                $msg['status'] = 'added';
                $msg['insertId'] = $addedStaff->id;
                $msg['linkedWithArea'] = $linkedWithArea;
                if($request->login_with_email){
                    $businessData = Business::find(Session::get('businessId'));
                    $message = "<!DOCTYPE html>
                        <html lang='en-US'>
                        <head>
                        </head>
                        <body>
                            <h2>EPICFIT Staff Access Details</h2>
                            <div>
                                Hi ".$addedStaff->first_name." ".$addedStaff->last_name.", <br>
                                <p>Following are the details of staff account for login:</p>
                                <table>
                                    <tr>
                                        <td><strong>Web Url:</strong></td>
                                        <td>".url('/')."/login/".$businessData->cp_web_url."</td>
                                    </tr> 
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>".$addedStaff->email."</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Password:</strong></td>
                                        <td>".$request->staffNewPwdd."</td>
                                    </tr>
                                </table>
                            </div>
                        </body>
                        </html>";
                    $this->sendMailToUser($addedStaff,$message);
                }
            }
        }
        return json_encode($msg);
    }

    /**
     * @param Temprory email send code in core php
     * @return void
     */
    protected function sendMailToUser($user,$message){
        $username = $user->first_name.' '.$user->last_name;
        $to = $user->email;
        $mail = new PHPMailer(true);
        try {
            //$mail->isSMTP(); // tell to use smtp
            $mail->CharSet = "utf-8"; // set charset to utf8
            $mail->Host = 'epictrainer.com';
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;
            $mail->Port = 25; // most likely something different for you. This is the mailtrap.io port i use for testing.
            $mail->Username = 'webmaster@epictrainer.com';
            $mail->Password = 'S[WlD3]Tf4*K';
            $mail->setFrom("noreply@epictrainer.com", "EPIC Trainer Team");
            $mail->Subject = "EPICFIT Staff Access Details";
            $mail->MsgHTML($message);
            $mail->addAddress($to, $username);
            $mail->SMTPOptions= array(
                                    'ssl' => array(
                                    'verify_peer' => false,
                                    'verify_peer_name' => false,
                                    'allow_self_signed' => true
                                    )
                                );

            $mail->send();
        } catch (phpmailerException $e) {
            dd($e);
            //return redirect($this->redirectPath());
        } catch (Exception $e) {
            dd($e);
            //return redirect($this->redirectPath());
        }  
    }

    public function show($id){        
        $data = $this->getOverviewData($id);
        //if(count($data)){
            if(!count($data) || !Auth::user()->hasPermission(Auth::user(), 'view-staff'))
                abort(404);
            
            /*$countries = \Country::getCountryLists();
            $staff = Staff::find($id);
            if($staff){
                $staff->stateName = \Country::getStateName($staff->country, $staff->state);

                return view('Settings.staff.show', compact('staff', 'countries'));
            }*/
            
                $staff = $data['staff'];

                $permTyp = array();
                if(isSuperUser()){
                    $permTypes = UserType::all();
                    if($permTypes->count())
                        foreach($permTypes as $permType)
                            $permTyp[$permType->ut_id] = ucfirst($permType->ut_name);
                }
                asort($permTyp);
                //$countries = $data['countries'];
                return view('Settings.staff.show', compact('staff', 'permTyp'/*, 'countries'*/));
            //}
        //}
    }

    public function edit($id){
            // dd($id);
        $data = $this->getEditFormData(['id' => $id]);
        if(!count($data) || !Auth::user()->hasPermission(Auth::user(), 'edit-staff'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');

        /*$staff = Staff::with('classes')->find($id);
        if($staff){
            if($staff->biography){
                $this->emptyFileuploadPluginUploadDir();

                $uploadedFilePath = $this->getUploadPath().$staff->biography;
                $pluginUploadFilePath = $this->getFileuploadPluginUploadDirPath().$staff->biography;
                File::copy($uploadedFilePath , $pluginUploadFilePath);
            }

            /*if($staff->date_of_birth != '0000-00-00'){
                //$carbonDob = Carbon::createFromFormat('Y-m-d', $staff->date_of_birth);
                $staff->birthYear = $staff->carbonDob->year;
                $staff->birthMonth = $staff->carbonDob->month;
                $staff->birthDay = $staff->carbonDob->day;
            }
            else
                $staff->birthYear = $staff->birthMonth = $staff->birthDay = '';*/

            /*$staff->hours = Staff::getHours($staff->id);
            if(count($staff->hours))
                $staff->hours = json_encode($staff->hours);*

            $business = Business::with('services', 'classes')->find(Session::get('businessId'));
            $businessId = $business->id;

            $serv = array();
            if($business->services->count()){
                foreach($business->services as $service){
                    if($service->category == 1) // TEAM
                        $serv[$service->id] = $service->team_name;
                    else if($service->category == 2) // 1 on 1
                        $serv[$service->id] = $service->one_on_one_name;
                }
            }

            $staffServices = Staff::getServices(['staff'=>$staff, 'business'=>$business]);
            //dd($staffServices);

            $clses = array();
            if($business->classes->count())
                foreach($business->classes as $class)
                    $clses[$class->cl_id] = $class->cl_name;

            $staffClasses = $staff->classes->pluck('cl_id')->toArray();
            
            $permTypes = UserType::all();
            $permTyp = array('' => '-- Select --');
            if($permTypes->count())
                foreach($permTypes as $permType)
                    $permTyp[$permType->ut_id] = $permType->ut_name;

            $country = ['' => '-- Select --'] + \Country::getCountryLists();

            $states = $this->getStates($staff->country);

            $time_zone = ['' => '-- Select --'] + \TimeZone::getTimeZone();
            
            return view('Settings.staff.edit', compact('staff', 'businessId', 'serv', 'clses', 'permTyp', 'country', 'states', 'time_zone', 'staffServices', 'staffClasses'));
        }*/
       // $data = $this->getEditFormData(['id' => $id]);
        //if(count($data)){
            $staff = $data['staff'];
            $businessId = $data['businessId'];
            $serv = $data['serv'];
            $clses = $data['clses'];
            $permTyp = $data['permTyp'];
            $country = $data['country'];
            $states = $data['states'];
            $time_zone = $data['time_zone'];
            $staffServices = $data['staffServices'];
            $staffClasses = $data['staffClasses'];
            $sessionRole =$data['sessionrole'];
            $staffSessionRole =$data['staffSessionRole'];
            $commissionRole =$data['commissionrole'];
            $staffCommissionRole =$data['staffCommissionRole'];
            $commissionSource =$data['commissionsource'];
            $staffCommissionSource =$data['staffCommissionSource'];
            $commissionCategory =$data['commissioncategory'];
            $staffCommissionCategory =$data['staffCommissionCategory'];
            $staffSessionServices=$data['staffSessionServices'];
            $staffSessionCategory=$data['staffSessionCategory']; 
            $staffAreas=$data['staffAreas'];
            $staffAttendeeArr =[];
            foreach ($data['staffAttendeeArr'] as $attendee) {
              $staffAttendeeArr[]=array('sa_id' =>$attendee->id,'sa_staff_id' =>$attendee->sa_staff_id, 'sa_type' =>$attendee->sa_type, 'sa_per_session_attendees' =>$attendee->sa_per_session_attendees, 'sa_per_session_attendeeto' =>$attendee->sa_per_session_attendeeto, 'sa_per_session_price' =>$attendee->sa_per_session_price,'per_session_tier' =>$attendee->per_session_tier, 'per_session_tierto' =>$attendee->per_session_tierto, 'per_session_tierprice' =>$attendee->per_session_tierprice, 'sa_attendee_order' =>$attendee->sa_attendee_order);
            }
            
            //if($serv->count() > 0)
            $sessionStaffService =array_intersect_key($serv, array_flip($staffServices));
           // if($clses->count() > 0)
            $sessionStaffClass =array_intersect_key($clses, array_flip($staffClasses));

            if(!$staff->login_with_email){                
                $pwd = genPwd();
            }
            $locationAreas = [];
            foreach($data['business']->locations as $location){
                foreach ($location->areas as $area) {
                    $locationAreas[$area->la_id] = $area->la_name;
                }
            }
            return view('Settings.staff.edit', compact('staff', 'businessId', 'serv', 'clses', 'permTyp', 'country', 'states', 'time_zone', 'staffServices', 'staffClasses', 'sessionStaffService','sessionStaffClass','sessionRole','staffSessionRole','commissionRole','staffCommissionRole','staffCommissionSource','commissionSource','commissionCategory','staffCommissionCategory','staffSessionServices','staffSessionCategory','staffAttendeeArr', 'pwd','staffAreas','locationAreas'));
        //}
    }

     public function update($id, Request $request){ 
        $isError = false;
        $msg = [];
        $notIn = ['mailinator','yopmail'];
        if (in_array(explode('.',explode('@',$request->email)[1])[0],$notIn))
        {
            $msg['status'] = 'error';
            $msg['errorData'][] = array('emailExist' => 'Please use your genuine email ids.');
            $isError = true;
        //    return redirect()->back()->with('flash_danger','Mailinator and Yopmail email not excepted here.');
        }
        if(isUserType(['Staff'])){
            $id = Auth::user()->account_id;
            $staff = Staff::find($id);
        }
        else
            $staff = Staff::findStaff($id, $request->businessId);
        if(!$staff || (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff'))){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }         
        if(!$isError){                
                if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'staff', 'id' => $id])){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('emailExist' => 'This email is already in use!');
                    $isError = true;
                }

                if($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'staff', 'id' => $id])){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('phoneExist' => 'This phone number is already in use!');
                    $isError = true;
                }

                if(!$isError){
                    $staff->first_name = $request->first_name;
                    $staff->last_name = $request->last_name;
                    if(isset($request->gender) && $request->gender)
                        $staff->gender = $request->gender;
                    $staff->date_of_birth = prepareDob($request->year, $request->month, $request->day);
                    $staff->job_title = $request->job_title;

                    if(!isUserType(['Staff']))
                        $staff->ut_id = $request->staff_permissions;

                    if($request->staffBiography){
                        if($request->staffBiography != $staff->biography){
                            $staff->biography = $this->uploadBiography($request->staffBiography, $staff->biography); 
                        }
                    }
                    else if($staff->biography){
                        $this->deleteBiography($staff->biography);
                        $staff->biography = '';
                    }

                    $staff->profile_picture = $request->staffProfilePictureUpload;

                    if(!isUserType(['Staff'])){
                        $prelogin_with_email = $staff->login_with_email;
                        if(isset($request->login_with_email) && $request->login_with_email)
                            $staff->login_with_email = $request->login_with_email;
                        else
                            $staff->login_with_email = 0;
                    }

                    if(isset($request->clients_book_staff) && $request->clients_book_staff)
                        $staff->clients_book_staff = $request->clients_book_staff;
                    else
                        $staff->clients_book_staff = 0;
                    $staff->website = $request->website;
                    $staff->facebook = $request->facebook;
                    $staff->email = $request->email;
                    $staff->phone = $request->phone;
                    if($request->fixed_location == 'Fixed Location'){
                        $staff->fixed_location = 1;
                        $staff->address_line_one = $request->address_line_one;
                        $staff->address_line_two = $request->address_line_two;
                    }
                    else{
                        $staff->fixed_location = 0;
                        $staff->address_line_one = $staff->address_line_two = '';
                    }
                    $staff->city = $request->city;
                    $staff->country = $request->country;
                    $staff->state = $request->state;
                    $staff->postal_code = $request->postal_code;
                    $staff->time_zone = $request->time_zone;
                    // if(isset($request->disp_location_web) && $request->disp_location_web)
                    //     $staff->disp_location_web = $request->disp_location_web;
                    // else
                    //     $staff->disp_location_web = 0;
                    // if(isset($request->disp_location_online) && $request->disp_location_online)
                    //     $staff->disp_location_online = $request->disp_location_online;
                    // else
                    //     $staff->disp_location_online = 0;

                    $staff->hourly_payment_label = $request->hourly_payment_label;
                    $staff->hourly_rate = $request->hourly_rate;
                    $staff->hourly_start_date = $request->hourly_start_date;
                    $staff->hourly_end_date = $request->hourly_end_date;

                    $staff->commission_payment= $request->commission_payment;
                    $staff->commission_rate= $request->commission_rate;
                    $staff->commission_tax= $request->commission_tax;
                    $staff->commission_date_range_start= $request->commission_date_range_start;
                    $staff->commission_date_range_end= $request->commission_date_range_end;

                    $staff->per_session_rate_options= $request->per_session_rate_options;
                    $staff->per_session_payment_label= $request->per_session_payment_label;
                    $staff->per_session_base_rate= $request->per_session_base_rate;
                    $staff->per_session_pay_for = $request->per_session_pay_for;
                    $staff->per_session_start_date = $request->per_session_start_date;
                    $staff->per_session_end_date = $request->per_session_end_date;

                    $staff->save();

                    /**** Start: Update Linked Areas ******/
                    $staffAreas = [];
                    foreach ($request->staff_areas as $area) {
                        $staffAreas[] = [
                            'as_la_id' => $area,
                            'as_business_id' => Session::get('businessId')
                        ];
                    }
                    $staff->areas()->sync($staffAreas);
                    /**** End: Update Linked Areas ******/

                    /*if($request->staffClasses == '')
                        $staffClasses = [];
                    else
                        $staffClasses = $request->staffClasses;
                    $linkedDeletedClassIds = Staff::pivotClassesTrashedOnly($staff->id);
                    if(count($linkedDeletedClassIds))
                        foreach($linkedDeletedClassIds as $linkedDeletedClassId)
                           $staffClasses[] = $linkedDeletedClassId->cst_cl_id;

                    $staff->classes()->sync($staffClasses);*/
                     $staffClassesId=[];
                    if($request->staffClasses == '')
                        $staffClasses = [];
                    else
                       $staffClasses = $request->staffClasses;

                     if($request->per_session_class == '')
                        $perSessionClass = [];
                    else
                        $perSessionClass = $request->per_session_class;    
                       
                      $staffMatchSessionClassesIds=array_intersect($staffClasses,$perSessionClass);
                      $staffUnmatchSessionClassesIds=array_diff($staffClasses,$perSessionClass);

                      foreach($staffMatchSessionClassesIds as $staffClassId){
                        $staffClassesId[$staffClassId] = ['cst_per_session_enable' => 1];
                        }

                      foreach($staffUnmatchSessionClassesIds as $staffClassId){
                        $staffClassesId[$staffClassId] = ['cst_per_session_enable' => 0];
                        }

                        $linkedDeletedClassIds = Staff::pivotClassesTrashedOnly($staff->id);
                    if(count($linkedDeletedClassIds))
                        foreach($linkedDeletedClassIds as $linkedDeletedClassId)
                           $staffClassesId[$linkedDeletedClassId->cst_cl_id] = ['cst_per_session_enable' => 0];

                    $staff->classes()->sync($staffClassesId);


              ///save session role
                    if($request->per_session_role == '')
                        $perSessionRole = [];
                    else
                        $perSessionRole = $request->per_session_role;
                  /*  $linkedDeletedSessionRoleIds = Staff::pivotSessionRoleTrashedOnly($staff->id);
                    if(count($linkedDeletedSessionRoleIds))
                        foreach($linkedDeletedSessionRoleIds as $linkedSessionRoleId)
                           $perSessionRole[] = $linkedSessionRoleId->srs_role_id;*/

                    $staff->sessionrolestaff()->sync($perSessionRole);

              ///save session service
                    if($request->per_session_service == '')
                        $perSessionService = [];
                    else
                        $perSessionService = $request->per_session_service;
                    /*$linkedDeletedSessionServiceIds = Staff::pivotSessionServiceTrashedOnly($staff->id);
                    if(count($linkedDeletedSessionServiceIds))
                        foreach($linkedDeletedSessionServiceIds as $linkedSessionServiceId)
                           $perSessionService[] = $linkedSessionServiceId->sst_service_id;*/

                    $staff->sessionservicestaff()->sync($perSessionService);      


          

           ///save commission role
                 if($request->commission_role == '')
                        $commissionRole = [];
                    else
                        $commissionRole = $request->commission_role;
                     /*  $linkedDeletedCommissionRoleIds = Staff::pivotCommissionRoleTrashedOnly($staff->id);
                    if(count($linkedDeletedCommissionRoleIds))
                        foreach($linkedDeletedCommissionRoleIds as $linkedCommissionRoleId)
                           $commissionRole[] = $linkedCommissionRoleId->crs_role_id;*/

                    $staff->commissionRoleStaff()->sync($commissionRole);    

          ///save commission category
                 if($request->commission_income_category == '')
                        $commissionCategory = [];
                    else
                        $commissionCategory = $request->commission_income_category;

                      /* $linkedCommissionCategoryIds = Staff::pivotCommissionCategoryTrashedOnly($staff->id);
                    if(count($linkedCommissionCategoryIds))
                        foreach($linkedCommissionCategoryIds as $linkedCommissionCategoryId)
                           $commissionCategory[] = $linkedCommissionCategoryId->ccs_category_id;*/
           
                    $staff->commissionCategorystaff()->sync($commissionCategory); 
                    
          ///save commission source
                 if($request->commission_referral_source == '')
                        $commissionSource = [];
                    else
                        $commissionSource = $request->commission_referral_source;
                       /*$linkedDeletedCommissionSourceIds = Staff::pivotCommissionSourceTrashedOnly($staff->id);
                    if(count($linkedDeletedCommissionSourceIds))
                        foreach($linkedDeletedCommissionSourceIds as $linkedCommissionSourceId)
                           $commissionSource[] = $linkedCommissionSourceId->css_source_id;*/

                    $staff->commissionSourcestaff()->sync($commissionSource);                         
                    

            ///save session attendees
                   $allAttendeesData=$this->calcDataForStartAndEndDatetime($staff->id,$request->all());

                //dd($allAttendeesData);

                  $hoursUpdate =  $this->setWorkingHours($request, ['mode' => 'edit', 'entityType' => 'staff', 'entityId' => $id]);
                  if($hoursUpdate['status'] == 'error'){
                    $msg['status'] = 'hourFailed';
                    $msg['msg'] = $hoursUpdate['msg'];
                    return json_encode($msg);
                  }
                    //$this->setHours($id, $request);
                    $userTypeData = UserType::where('ut_id',$request->staff_permissions)->first();
                    if($userTypeData && $userTypeData->ut_name == 'Staff')
                        $accountType = 'Staff';
                    else
                        $accountType = 'Admin';
                    if(!isUserType(['Staff'])){
                               
                            
                        if(!$prelogin_with_email && $request->login_with_email){
                           $x= $this->callStoreUser(['name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'userTypeId' => $request->staff_permissions, 'accountId' => $id, 'password' => $request->staffNewPwdd,'accountType'=>$accountType]);
                           
                        }
                        else if($prelogin_with_email){
                            if(!$request->login_with_email){
                                //$user = User::whereAccountId($id)->first();
                                $user = $staff->user;
                                if($user)
                                    $user->forceDelete();
                            }
                            else if($request->login_with_email){
                                /*$user = User::whereAccountId($id)->first();
                                if($user){
                                    $user->name = $request->first_name;
                                    $user->last_name = $request->last_name;
                                    $user->ut_id = $request->staff_permissions;
                                    if($request->staffNewPwd)
                                        $user->password = bcrypt($request->staffNewPwd);
                                    $user->save();
                                }*/

                                $this->entityLogin_tableRecordUpdate(['entity' => $staff, 'firstName' => $request->first_name, 'lastName' => $request->last_name, 'permissionGroupId' => $request->staff_permissions, 'password' => $request->staffNewPwdd,'accountType'=>$accountType]);
                            }
                        }
                    }
                    else  

                       $this->entityLogin_tableRecordUpdate(['entity' => $staff, 'firstName' => $request->first_name, 'lastName' => $request->last_name, 'password' => $request->staffNewPwdd,'accountType'=>$accountType]);
                    
                    if($request->prevServices)
                        $prevServices = explode(',', $request->prevServices);
                    else
                        $prevServices = [];

                    if(count($prevServices)){
                        if($request->staff_services != '')
                            $unlinkedServices = array_diff($prevServices, $request->staff_services);
                        else
                            $unlinkedServices = $prevServices;
                        if(count($unlinkedServices)){
                            $services = Service::find($unlinkedServices);
                            if($services->count()){
                                foreach($services as $service){
                                    if($service->category == 1){    // TEAM
                                        $serviceStaffs = $service->team_staffs;
                                        $col = 'team_staffs';
                                    }
                                    else if($service->category == 2){ // 1 on 1
                                        $serviceStaffs = $service->one_on_one_staffs;
                                        $col = 'one_on_one_staffs';
                                    }

                                    $serviceStaffs = explode(',', $serviceStaffs);
                                    if(count($serviceStaffs) > 1)
                                        $serviceStaffs = implode(',', array_diff($serviceStaffs, array($id)));
                                    else
                                        $serviceStaffs = '';

                                    $service->update(array($col => $serviceStaffs));
                                }
                            }
                        }

                        if($request->staff_services != ''){
                            $newLinkedServices = array_diff($request->staff_services, $prevServices);
                            if(count($newLinkedServices))
                                Service::updateStaff($newLinkedServices, $id);
                        }
                    }
                    else if($request->staff_services != '')
                        Service::updateStaff($request->staff_services, $id);                

                    $msg['status'] = 'updated';
                }
            //}
        }
        return json_encode($msg);
    }

    public function create(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-staff'))
            abort(404);

        //if(!Session::has('businessId'))
            //return redirect('settings/business/create');

        $business = Business::with('completedServices', 'classes','sessionrole','commissionrole','commissionsource','incomecategory','locations.areas')->find(Session::get('businessId'));
        $businessId = $business->id;
        $this->emptyFileuploadPluginUploadDir();

        $serv = array();
        if($business->completedServices->count()){
            foreach($business->completedServices as $service){
                //if($service->is_completed==1)
                // {   
                    if($service->category == 1) // TEAM
                        $serv[$service->id] = ucfirst($service->team_name);
                    else if($service->category == 2) // 1 on 1
                        $serv[$service->id] = ucfirst($service->one_on_one_name);
                // }   
            }
            asort($serv);
        }
       // $staffServices = Staff::getServices(['staff'=>$staff, 'business'=>$business]);

        $clses = array();
        if($business->classes->count()){
            foreach($business->classes as $class)
                $clses[$class->cl_id] = ucfirst($class->cl_name);
            asort($clses); 
        }

       // $staffClasses = $staff->classes->pluck('cl_id')->toArray();          

        $sessionRole = array();
        if($business->sessionrole->count()){
                foreach($business->sessionrole as $role)
                    $sessionRole[$role->id] = ucfirst($role->sr_value);
            asort($sessionRole);
        }
        
         $commissionRole = array();
            if($business->commissionrole->count()){
                foreach($business->commissionrole as $crole)
                    $commissionRole[$crole->id] = ucfirst($crole->cr_value);
                asort($commissionRole);  
            }
                    
         $commissionSource = array();
            if($business->commissionsource->count()){
                foreach($business->commissionsource as $csource)
                    $commissionSource[$csource->id] = ucfirst($csource->cr_value);
                asort($commissionSource);  
            }
        
         $commissionCategory = array();
            if($business->incomecategory->count()){
                foreach($business->incomecategory as $ccategory)
                    $commissionCategory[$ccategory->id] = ucfirst($ccategory->category_name);
                asort($commissionCategory);                             
            }



        
        $permTypes = UserType::all();
        $permTyp = array('' => '-- Select --');
        if($permTypes->count())
            foreach($permTypes as $permType)
                $permTyp[$permType->ut_id] = ucfirst($permType->ut_name);

        asort($permTyp);
           //$sessionStaffService =array_intersect_key($serv, array_flip($staffServices));
           //$sessionStaffClass =array_intersect_key($clses, array_flip($staffClasses));


        $country = ['' => '-- Select --'] + \Country::getCountryLists();

        $time_zone = ['' => '-- Select --'] + \TimeZone::getTimeZone();

        if($request->has('subview')){
            $subview = true;
            if($request->has('areasToLink'))
                $areasToLink = $request->areasToLink;
            else if($request->has('locToLink'))
                $locToLink = $request->locToLink;
        }

        $pwd = genPwd();
        $locationData = $business->locations()->first();
        $locHours = $locationData->getLocHours($locationData->id);
        $locationAreas = [];
        foreach($business->locations as $location){
            foreach ($location->areas as $area) {
                $locationAreas[$area->la_id] = $area->la_name;
            }
        }
        return view('Settings.staff.edit', compact('businessId', 'serv', 'clses', 'permTyp', 'country', 'time_zone', 'subview', 'areasToLink', 'locToLink','sessionRole','commissionRole','commissionSource','commissionCategory','sessionStaffService','sessionStaffClass', 'pwd','locHours','locationAreas'));
    }

    protected function uploadBiography($newFilename, $oldFileName = ''){
        $timestamp = md5(time().rand());
        $extension = strrchr($newFilename, ".");
        $name = $timestamp.$extension;
        $pluginUploadedFilePath = $this->getFileuploadPluginUploadDirPath().$newFilename;
        $uploadFilePath = $this->getUploadPath().$name;

        File::move($pluginUploadedFilePath, $uploadFilePath);

        if($oldFileName)
            $this->deleteBiography($oldFileName);

        return $name; 
    }

    protected function deleteBiography($fileName){
        File::delete($this->getUploadPath().$fileName);
    }

    protected function callStoreUser($data){   
        $this->storeUser(['name' => $data['name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'password' => $data['password']/*str_random(10)*/, 'userTypeId' => $data['userTypeId'], 'businessId' => Session::get('businessId'), 'accountId' => $data['accountId'], /*'type' => 'Staff'*/ 'type' => $data['accountType']]);
    }

    public function updateField(Request $request){  
        if(isUserType(['Staff'])){
            $request->entityId = Auth::user()->account_id;
            $staff = Staff::find($request->entityId);
        }
        else
            $staff = Staff::findStaff($request->entityId);

        if(!$staff || (!isUserType(['Staff']) && !Auth::user()->hasPermission(Auth::user(), 'edit-staff'))){
            if($request->ajax())
                return [];
            else
                abort(404);
        }

        if($request->entityProperty == 'email'){
            if(!$this->ifEmailAvailableInSameBusiness(['email' => $request->email, 'entity' => 'staff', 'id' => $request->entityId])){
                return json_encode([
                    'status' => 'emailExistError',
                    'message' => 'This email is already in use'
                ]);
            }

            $value = $request->email;
            $staff->email = $value;
            $staff->save();
        }
        else if($request->entityProperty == 'phone'){
            if($this->ifPhoneExistInSameBusiness(['numb' => $request->phone, 'entity' => 'staff', 'id' => $request->entityId])){
                return json_encode([
                    'status' => 'numbExistError',
                    'message' => 'This phone number is already in use'
                ]);
            }

            $value = $request->phone;
            $staff->phone = $value;
            $staff->save();
        }
        else if($request->entityProperty == 'gender'){
            $value = $request->gender;
            $staff->gender = $value;
            $staff->save();
        }
        else if($request->entityProperty == 'jobTitle'){
            $value = $request->jobTitle;
            $staff->job_title = $value;
            $staff->save();
        }
        else if($request->entityProperty == 'dob'){
            $value = prepareDob($request->year, $request->month, $request->day);
            if($value){
                $staff->date_of_birth = $value;
                $staff->save();
            }
        }
        else if($request->entityProperty == 'permGroup'){
            $staff->ut_id = $request->permGroup;
            $staff->save();

            $value = $request->permGroup.'|'.$staff->type->ut_name;

            if($staff->login_with_email)
                $this->entityLogin_tableRecordUpdate(['entity' => $staff, 'permissionGroupId' => $request->permGroup]);
        }

        return json_encode([
            'status' => 'updated',
            'value' => $value
        ]);
    }

    public function destroy($id){
        $staff = Staff::findOrFailStaff($id);

        if(!isUserType(['Admin']) || !Auth::user()->hasPermission(Auth::user(), 'delete-staff'))
            abort(404);
        
        $staff->delete();
        
        return redirect()->back()->with('message', 'success|Staff has been deleted successfully.');
        //route('staffs')
    }

    public function perSessionRoles(Request $request){
        if(!Auth::user()->hasPermission(Auth::user(), 'create-business-type')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }


         
        //$this->validate($request, ['value' => 'required']);
         $data = array();
         $data['sr_value'] = trim($request->value);
         $data['sr_businesses_id'] = Session::get('businessId');
        
         $addRole=SessionRole::create($data);
        
         if($addRole)
            return $addRole->id;
          else
             return '0';

    }

  public function commissionRoles(Request $request){

     if(!Auth::user()->hasPermission(Auth::user(), 'create-business-type')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }


        
        //$this->validate($request, ['value' => 'required']);
         $data = array();
         $data['cr_value'] = trim($request->value);
         $data['cr_businesses_id'] = Session::get('businessId');
        
         $addRole=CommissionRole::create($data);
        
         if($addRole)
            return $addRole->id;
          else
             return '0';

  }

    /*public function commissionCategory(Request $request){

     if(!Auth::user()->hasPermission(Auth::user(), 'create-business-type')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }
        
        //$this->validate($request, ['value' => 'required']);
         $data = array();
         $data['cc_value'] = trim($request->value);
         $data['cc_businesses_id'] = Session::get('businessId');
        
         $addCategory=CommissionCategory::create($data);
        
         if($addCategory)
            return $addCategory->id;
          else
             return '0';

  }*/

    public function commissionSource(Request $request){

     if(!Auth::user()->hasPermission(Auth::user(), 'create-business-type')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }


        
        //$this->validate($request, ['value' => 'required']);
         $data = array();
         $data['cr_value'] = trim($request->value);
         $data['cr_businesses_id'] = Session::get('businessId');
        
         $addSource=CommissionSource::create($data);
        
         if($addSource)
            return $addSource->id;
          else
             return '0';

  }

    protected function calcDataForStartAndEndDatetime($staffId,$input){
        $attendees = $data = $insertData=[];
        foreach($input as $key => $value){
            if(strpos($key, 'per_session_attendees') !== false && $value)
                $attendees[(int) str_replace("per_session_attendees", "", $key)] = $value;
            elseif(strpos($key, 'per_session_tier') !== false && $value)
                $sessionTier[(int) str_replace("per_session_tier", "", $key)] = $value;
        }

        StaffAttendee::where('sa_staff_id',$staffId)->delete();


        if(isset($attendees) && ($input['per_session_rate_options']=='base_rate_attendees')){
            ksort($attendees);
            reset($attendees);

           
            foreach($attendees as $key => $value){
                $timestamp = createTimestamp();
                $insertData[] = array('sa_staff_id' => $staffId, 'sa_type' => $input['per_session_rate_options'], 'sa_per_session_attendees' => $input['per_session_attendees'.$key], 'sa_per_session_attendeeto' => $input['per_session_attendeeto'.$key], 'sa_per_session_price' => $input['per_session_price'.$key], 'sa_attendee_order' => $key+1, 'created_at' => $timestamp, 'updated_at' => $timestamp);
              
            }
          if($insertData) 
           StaffAttendee::insert($insertData); 
          

        }elseif(isset($sessionTier) && ($input['per_session_rate_options']=='base_rate_tier')){
            ksort($sessionTier);
            reset($sessionTier);
            foreach($sessionTier as $key => $value){
                $timestamp = createTimestamp();
                     $insertData[] = array('sa_staff_id' => $staffId, 'sa_type' => $input['per_session_rate_options'], 'per_session_tier' => $input['per_session_tier'.$key], 'per_session_tierto' => $input['per_session_tierto'.$key], 'per_session_tierprice' => $input['per_session_tierprice'.$key], 'sa_attendee_order' => $key+1, 'created_at' => $timestamp, 'updated_at' => $timestamp);
 
            }
          if($insertData) 
           StaffAttendee::insert($insertData); 
        }
    }
   
    public function listattendences(Request $request){
        
        if( ( isUserType(['Admin']) || isUserType(['Staff']) ) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-attendence'))
            abort(404);

        /*$dateCarbon = new Carbon();
        $date = $dateCarbon->toDateString();
        $isRecordExist = StaffAttendence::where('sa_date',$date)->exists();
        if(!$isRecordExist)
            $this->hourToAttendence($dateCarbon);*/

        if($request->has('date')){
            $dateCarbon = new Carbon($request->date);
            $date = $request->date;
        }
        else{
            $dateCarbon = new Carbon();
            $date = $dateCarbon->toDateString();
        }
        
        $allStaffs = Staff::/*with(array('attendences' => function($query) use($date){ 
                                    $query->where('sa_date',$date);
                                }))
                        ->*/whereHas('attendences', function($q) use($date){
                                        $q->where('sa_date', $date);
                                    })
                        ->OfBusiness()
                        ->get();

        $entityId = $allStaffs->pluck('id')->toArray();
        $hourlist = StaffAttendence::select('sa_notes as notes','sa_staff_id as staffId', DB::raw('GROUP_CONCAT(id) as recordId,GROUP_CONCAT(sa_start_time) as startTime,GROUP_CONCAT(sa_end_time) as endTime,GROUP_CONCAT(edited_start_time) as editedStartTime,GROUP_CONCAT(edited_end_time) as editedEndTime,GROUP_CONCAT(sa_status) as status'))->whereIn('sa_staff_id',$entityId)->groupBy('sa_staff_id')->where('sa_date',$dateCarbon->toDateString())->get();
        //$hourlist = StaffAttendence::select('edited_start_time as editedStartTime','edited_end_time as editedEndTime','sa_status as status','sa_notes as notes','sa_staff_id as staffId', DB::raw('GROUP_CONCAT(id) as recordId,GROUP_CONCAT(sa_start_time) as startTime,GROUP_CONCAT(sa_end_time) as endTime'))->whereIn('sa_staff_id',$entityId)->groupBy('sa_staff_id')->where('sa_date',$dateCarbon->toDateString())->get();
        if( Auth::user()->hasPermission(Auth::user(), 'edit-staff-attendence') )
            $isValidEditPermission = 1;
        else 
            $isValidEditPermission = 0;
        
        return view('attendenceroaster.show', compact('allStaffs','hourlist', 'date','isValidEditPermission'));
        /*        
        $allStaffs = Staff::with(array('attendences' => function($query) use($date){ $query->where('sa_date',$date);
        }))->OfBusiness()->get();
        $entityId = $allStaffs->pluck('id')->toArray();
        //$day = $dateCarbon->format('l');
        $insertAllRecords = $this->hourToAttendence($entityId,$dateCarbon);
        */
    }

    protected function hourToAttendence($date){
        $day = $date->format('l');
        $gethourlist = DB::table("hours")->where('hr_entity_type','staff')->select('hr_entity_id as staffId', 'hr_start_time as startTime','hr_end_time as endTime')->whereNull('deleted_at')->where('hr_day',$day)->get();
        $date = $date->toDateString();
        $insertData = [];

        for($i=0;$i<count($gethourlist);$i++){
                $insertData[] = array('sa_staff_id' => $gethourlist[$i]->staffId, 'sa_start_time'=>$gethourlist[$i]->startTime, 'sa_end_time'=>$gethourlist[$i]->endTime, 'sa_date'=>$date ,'sa_status'=>'');
        }
        StaffAttendence::insert($insertData);
        /* $defaultServiceData = array(
                        array('business_id' => $addedBusiness->id, 'category' => 2, 'one_on_one_name' => 'Consultation', 'created_at' => $timestamp, 'updated_at' => $timestamp, 'is_default' => 1, 'for_sales_process_step' => 2),
                        array('business_id' => $addedBusiness->id, 'category' => 2, 'one_on_one_name' => 'Pre-Benchmarking', 'created_at' => $timestamp , 'updated_at' => $timestamp, 'is_default' => 1, 'for_sales_process_step' => 4)
                    );  */
    } 

    public function monthlyattendences(Request $request){
        if($request->has('date')){
            $dateCarbon = new Carbon($request->date);
            $date = $request->date;
        }
        else{
            $dateCarbon = new Carbon();
            $date = $dateCarbon->toDateString();
        }

       
        $allStaffs = Staff::/*with(array('attendences' => function($query) use($date){ 
                                    $query->where('sa_date',$date);
                                }))
                        ->*/whereHas('attendences', function($q) use($date){
                                        $q->whereNull('deleted_at');
                                    })
                        ->OfBusiness()
                        ->get();
        $staff = array();            
        if($allStaffs->count())
            foreach($allStaffs as $allstaff)
                $staff[$allstaff->id] = ucwords($allstaff->first_name. ' '.$allstaff->last_name);
        asort($staff);
        
        $entityId = $allStaffs->pluck('id')->toArray();

     

        return view('attendenceroaster.report',compact('staff','date','staffdata'));
    }

    public function getMonthlyAttendenceData(Request $request){
        if( ( isUserType(['Admin']) || isUserType(['Staff']) ) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-attendence')){
            if($request->ajax())
                return [];
            else
                abort(404);
        }

        if($request->has('staffMonth')){
            $startDate = $request->staffMonth;
            $carbon = new Carbon($startDate);
            if($request->view =="agendaDay"){
                $lastDate=$startDate;
            }
            elseif($request->view =="agendaWeek"){
                $lastDate=$carbon->endOfWeek()->toDateString(); 
            }
            else{
                $lastDate=$carbon->endOfMonth()->toDateString(); 
            }
        }
        else{
            if($request->view =="agendaDay"){
                 $lastDate=$startDate;
            }
            elseif($request->view =="agendaWeek"){
                $startDate = Carbon::now()->startOfWeek()->toDateString();
                $lastDate = Carbon::now()->endOfWeek()->toDateString();
            }
            else{
                $startDate = $carbon->startOfMonth()->toDateString();
                $lastDate = Carbon::now()->endOfMonth()->toDateString();
            }
        }

        if($request->view == 'month' || $request->view == 'agendaWeek' || $request->view =="agendaDay"){
            $startD = new Carbon($startDate);
            $LastD = new Carbon($lastDate);

            for($date = $startD->copy(); $date->lte($LastD); $date->addDay()) {
                $dateDay = $date->toDateString();
            
                $day = $date->format('l');
               
                $gethourlists = DB::table('hours')->where('hr_entity_type','staff')->where('hr_entity_id',$request->staffId)->select('hr_id','hr_entity_id', 'hr_start_time','hr_end_time','hr_edit_date','hr_entity_type','hr_entity_number')
                                    ->whereNull('deleted_at')->where('hr_day',$day)
                                    ->where(function($q) use ($date){
                                        $q->where(function($query) use ($date){
                                            $query->where('hr_edit_date','=',$date);
                                        })->orWhereNull('hr_edit_date');
                                    })
                                    ->where('hr_start_time','!=','hr_end_time')
                                    ->orderBy('hr_entity_id')
                                    ->orderBy('hr_edit_date','desc')
                                    ->get();
                           
                if(count($gethourlists)){
                 foreach($gethourlists as $gethourlist){
                   $staffAtten =  StaffAttendence::withTrashed()->where('sa_staff_id',$request->staffId)->whereDate('sa_date',$date)->where(function($q) use($gethourlist){
                    $q->where(function($query) use($gethourlist){
                        $query->where('sa_start_time',$gethourlist->hr_start_time)->where('sa_end_time',$gethourlist->hr_end_time);
                    })
                    ->orWhere(function($query) use($gethourlist){
                        $query->where('edited_start_time',$gethourlist->hr_start_time)->where('edited_end_time',$gethourlist->hr_end_time);
                        
                    });
                })->where('sa_entity_number',$gethourlist->hr_entity_number)->first();
                $currentDate = setLocalToBusinessTimeZone(Carbon::now());
           
                if(($staffAtten == null || $staffAtten == '') &&  Carbon::parse($date)->format('Y-m-d') >= $currentDate->format('Y-m-d')){
                        $insertData = array('sa_staff_id' =>$gethourlist->hr_entity_id, 'sa_start_time'=>$gethourlist->hr_start_time, 'sa_end_time'=>$gethourlist->hr_end_time,'edited_start_time'=>$gethourlist->hr_start_time,'edited_end_time'=>$gethourlist->hr_end_time, 'sa_date'=> $date->format('Y-m-d'), 'sa_status'=>'','sa_entity_number'=>$gethourlist->hr_entity_number);
                        $staffAttendence = StaffAttendence::create($insertData);
                        // $data = $gethourlist->toArray();
                        // $hoursData = json_encode($data);
                        setInfoLog("Created from report hours data  $date $gethourlist->hr_id",  $staffAttendence->id);
                    }
                  }
                }

                
                
               
            }
        }

        /*$staffdata = StaffAttendence::select('sa_notes as notes','id as recordId','sa_start_time as startTime','sa_end_time as endTime','edited_start_time as editedStartTime','edited_end_time as editedEndTime','sa_status as status','sa_date as staffDate')->where('sa_staff_id',$request->staffId)->where('sa_date','>=',$startDate)->where('sa_date','<=',$lastDate)->get();*/
 
        $staff = Staff::with(array('attendences'=>function($q) use($startDate, $lastDate){
                                    $q->where('sa_date','>=',$startDate);
                                    $q->where('sa_date','<=',$lastDate);
                                    $q->whereNotNull('sa_start_time');
                                    $q->whereRaw('sa_start_time <> sa_end_time');
                                }))
                                ->select('id','hourly_rate')
                                ->find($request->staffId);

        $data = [];
        $staffdata=[];
        $i = 0;
        if(count($staff)){
            $staffdata['hourlyRate'] = $staff->hourly_rate;
            $staffdata['eventPayment'] = $staff->staffEventPaymet();
            if($staff->attendences->count()){
                foreach ($staff->attendences as $attendence) {
                   $data[$i]['recordId'] = $attendence->id;
                   $data[$i]['notes'] = $attendence->sa_notes;
                   $data[$i]['startTime'] = $attendence->sa_start_time;
                   $data[$i]['endTime'] = $attendence->sa_end_time;
                   $data[$i]['editedStartTime'] = $attendence->edited_start_time;
                   $data[$i]['editedEndTime'] = $attendence->edited_end_time;
                   $data[$i]['status'] = $attendence->sa_status;
                   $data[$i]['staffDate'] = $attendence->sa_date;
                   $i++;
                }
                $staffdata['attendence'] = $data;
            }
        }  
    echo json_encode($staffdata);
    }

    public function newroster(Request $request){
        if( ( isUserType(['Admin']) || isUserType(['Staff']) ) && !Auth::user()->hasPermission(Auth::user(), 'list-staff-attendence'))
            abort(404);
       
        
        if($request->has('date1') && $request->has('date2') ){ 
            $dateCarbon1 = new Carbon($request->date1);
            $dateCarbon2 = new Carbon($request->date2);
            $date1 = $dateCarbon1->toDateString();
            $date2 = $dateCarbon2->toDateString();
        }
        else if($request->has('date')){
            $dateCarbon = new Carbon($request->date);
            $date = $dateCarbon->toDateString();
            $date1 = $date;
            $date2 = 0;
        }
        else{
            $dateCarbon = setLocalToBusinessTimeZone(Carbon::now());
            $date = $dateCarbon->toDateString();
            $date1 = $date;
            $date2 = 0;
        }
        
        // $allStaffs = Staff::whereHas('attendences', function($q) use($date1 ,$date2){
        //             if($date1 && $date2 ){ 
        //                 $q->where('sa_date','>=', $date1)->where('sa_date','<=', $date2);
        //             }
        //             else{
        //                 $q->where('sa_date','=', $date1);
        //             }
        //         })
        //             ->OfBusiness()
        //             ->get();

       

        $allStaffs = Staff::with(array('attendences' => function($query) use($date1 ,$date2){ 
            $query->whereBetween('sa_date', [$date1, $date2]);
        })) ->OfBusiness()
        ->get();

        $entityId = $allStaffs->pluck('id')->toArray();
        foreach($entityId as $value){
            if(($request->has('date1') && $request->has('date2'))){
                $startD = new Carbon($date1);
                $LastD = new Carbon($date2);
                for($date = $startD->copy(); $date->lte($LastD); $date->addDay()) {
                    $dateDay = $date->toDateString();
                    $day = $date->format('l');
                    $gethourlists = DB::table('hours')->where('hr_entity_type','staff')->where('hr_entity_id',$value)->select('hr_id','hr_entity_id', 'hr_start_time','hr_end_time','hr_edit_date','hr_entity_type','hr_entity_number')
                                        ->whereNull('deleted_at')->where('hr_day',$day)
                                        ->where(function($q) use ($date){
                                            $q->where(function($query) use ($date){
                                                $query->where('hr_edit_date','=',$date);
                                            })->orWhereNull('hr_edit_date');
                                        })
                                        ->where('hr_start_time','!=','hr_end_time')
                                        ->orderBy('hr_entity_id')
                                        ->orderBy('hr_edit_date','desc')
                                        ->get();
                    if(count($gethourlists)){
                     foreach($gethourlists as $gethourlist){
                       $staffAtten =  StaffAttendence::withTrashed()->where('sa_staff_id', $gethourlist->hr_entity_id)->whereDate('sa_date',$date)->where(function($q) use($gethourlist){
                        $q->where(function($query) use($gethourlist){
                            $query->where('sa_start_time',$gethourlist->hr_start_time)->where('sa_end_time',$gethourlist->hr_end_time);
                        })
                        ->orWhere(function($query) use($gethourlist){
                            $query->where('edited_start_time',$gethourlist->hr_start_time)->where('edited_end_time',$gethourlist->hr_end_time);
                            
                        });
                    })->where('sa_entity_number',$gethourlist->hr_entity_number)->first();
                    $currentDate = setLocalToBusinessTimeZone(Carbon::now());
                    if(($staffAtten == null || $staffAtten == '') &&   Carbon::parse($date)->format('Y-m-d') >= $currentDate->format('Y-m-d')){
                            $insertData = array('sa_staff_id' =>$gethourlist->hr_entity_id, 'sa_start_time'=>$gethourlist->hr_start_time, 'sa_end_time'=>$gethourlist->hr_end_time,'edited_start_time'=>$gethourlist->hr_start_time,'edited_end_time'=>$gethourlist->hr_end_time, 'sa_date'=> $date->format('Y-m-d'), 'sa_status'=>'','sa_entity_number'=>$gethourlist->hr_entity_number);
                            $staffAttendence = StaffAttendence::create($insertData);
                            
                            setInfoLog('Created from roaster',  $staffAttendence->id);
                        }
                      }
                    }
    
                    
                    
                   
                }
            }
        }
        DB::enableQueryLog();
        // $qry=StaffAttendence::select('sa_notes as notes','sa_staff_id as staffId','id as recordId','sa_start_time as startTime','sa_end_time as endTime','edited_start_time as editedStartTime','edited_end_time as editedEndTime','sa_status as status','sa_date as staffDate')->whereIn('sa_staff_id',$entityId)->groupBy('sa_staff_id');
        $qry = StaffAttendence::select('sa_notes as notes','sa_staff_id as staffId', DB::raw('GROUP_CONCAT(id) as recordId,GROUP_CONCAT(sa_start_time) as startTime,GROUP_CONCAT(sa_end_time) as endTime,GROUP_CONCAT(edited_start_time) as editedStartTime,GROUP_CONCAT(edited_end_time) as editedEndTime,GROUP_CONCAT(sa_status) as status,GROUP_CONCAT(sa_date) as staffDate'))->whereIn('sa_staff_id',$entityId)/*->whereNotNull('sa_start_time')->whereRaw('sa_start_time <> sa_end_time')*/->groupBy('sa_staff_id');

        if($date1 && $date2 ){
            $hourlist = $qry->where('sa_date','>=',$dateCarbon1->toDateString())->where('sa_date','<=',$dateCarbon2->toDateString())->orderBy('sa_status','desc')->get();
        }
        else{
            $hourlist = $qry->where('sa_date','=',$dateCarbon->toDateString())->orderBy('sa_status','desc')->get();
        }
        //dd(DB::getQueryLog());
        $newData =[];
        $record_id = $hourlist->pluck('recordId')->toArray();
        $i = 0;
        $staffAttendId = array();
        foreach ($record_id as $ids) {
            $editStart = [];
            $editEnd = [];
            $editStart = [];
            $startTime= [];
            $endTime = [];
            $staffDate = [];
            $status =[];
            $id =[];
           $sa_id = explode(',', $ids);
          $data = StaffAttendence::select('sa_notes','sa_staff_id','id','sa_start_time','sa_end_time','edited_start_time','edited_end_time','sa_status','sa_date')->whereIn('id', $sa_id)->orderBy('sa_status','desc')->get();
           foreach ($data as $value) {
            $startTime[] =$value->sa_start_time;
            $endTime[] =$value->sa_end_time;
            $editStart[] = $value->edited_start_time;
            $editEnd[] = $value->edited_end_time;
            $staffDate[] = $value->sa_date;
            $status[] = $value->sa_status;
            $id[] = $value->id;
            $staffId = $value->sa_staff_id;
            $notes = $value->sa_notes;

           }
           $newData[$i]['recordId'] = implode(',', $id);
           $newData[$i]['editedStartTime'] = implode(',', $editStart);
           $newData[$i]['editedEndTime'] = implode(',', $editEnd);
           $newData[$i]['startTime'] = implode(',', $startTime);
           $newData[$i]['endTime'] = implode(',', $endTime);
           $newData[$i]['staffDate'] = implode(',', $staffDate);
           $newData[$i]['status'] = implode(',', $status);
           $newData[$i]['staffId'] = $staffId;
           $newData[$i]['notes'] = $notes;

           $i++;
        }
       $hourlist = $newData;
    
        if( Auth::user()->hasPermission(Auth::user(), 'edit-staff-attendence') )
            $isValidEditPermission = 1;
        else 
            $isValidEditPermission = 0;

        $staffsDb = Staff::OfBusiness()->get();
        $staffs = array();
        if($staffsDb->count()){
            foreach ($staffsDb as $staffDb) {
               $staffs[$staffDb->id] = $staffDb->getFullNameAttribute();
            }
        }
        if($request->has('type'))
            echo json_encode($hourlist);
        else
            return view('attendenceroaster.show2', compact('allStaffs','hourlist', 'date1','isValidEditPermission','staffs'));
    }

}
