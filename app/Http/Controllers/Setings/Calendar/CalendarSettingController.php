<?php
namespace App\Http\Controllers\Setings\Calendar;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Location;
use Session;
use DB;
use Auth;
use App\CalendarSettingsCancelReasons;
use App\CalendarSetting;
use App\Http\Traits\CalendarSettingTrait;
use App\ClientMember;
use App\ClientMenu;
use Redirect;
use App\StaffEventClass;
use DateTime;
use DateTimeZone;
use App\Business;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CalendarSettingController extends Controller{
    use CalendarSettingTrait;
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $clientSelectedMenus = [];
        if(Auth::user()->account_type == 'Client') {
            $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
 
            if(!in_array('calendar_settings', $clientSelectedMenus))
              Redirect::to('access-restricted')->send();
        }    
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
   

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit(){
        $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        if(isset($selectedMenus) && !in_array('calendar_settings', explode(',', $selectedMenus))){
            return redirect('access-restricted');
        }
        if(Session::get('hostname') == 'result'){
            $client_id = Auth::user()->account_id;    
            $allcaledarVal = CalendarSetting::where('cs_business_id',Session::get('businessId'))->whereIn('cs_client_id',array(0,$client_id))->orderBy('id', 'DESC')->first();
            return view('Result.calendar.edit', compact('allcaledarVal','client_id')); 
        }
        else{
            if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-calendar-setting'))
                abort(404);        

            $allcaledarVal = CalendarSetting::where('cs_business_id',Session::get('businessId'))->where('cs_client_id',0)->first();
            if($allcaledarVal->sales_process_settings == null){
              $allcaledarVal->update(['sales_process_settings' => '{"steps":["4","5","18"],"teamCount":"","indivCount":"","order":[{"id":"team-1"},{"id":"indiv-1"}],"session":[6,12]}']);
            }
            $allcaledarVal->refresh();
            $salesProcessSettings = json_decode($allcaledarVal->sales_process_settings);
            return view('Settings.calendar.edit', compact('allcaledarVal','salesProcessSettings'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request){
        // dd($request->all());
        $isError = false;
        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-calendar-setting')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }
        if($request->ajax())
            $msg = [];
        if(!$isError){
             $calendarsetting = CalendarSetting::find($id);
             if($calendarsetting){
                //$calendarsetting->cs_business_id = Session::get('businessId');
                $calendarsetting->cs_first_day = $request->firstDay;
                $calendarsetting->cs_intervals = $request->calendarInterval;
                $calendarsetting->cs_view = $request->view;
                $calendarsetting->cs_display_calendar = $request->disp_calendar;
                $calendarsetting->cs_initial_status = $request->initial_status;
                $calendarsetting->cs_initial_status_consultation = $request->initial_status_consultation;
                $calendarsetting->cs_initial_status_benchmarking = $request->initial_status_benchmarking;
                $calendarsetting->cs_add_company_name = $request->add_company_name;
                $calendarsetting->cs_allow_appointments = $request->allow_appointments;
                $calendarsetting->cs_receive_email_summary = $request->receive_email_summary;
                $calendarsetting->cs_reduced_rate = $request->redRate;
                $calendarsetting->cs_booking_active = $request->futExpi;
                if($request->useCurrentTime!=null){
                  $calendarsetting->cs_use_current=1;
                  $calendarsetting->cs_start_time=null;
                }
                else{
                  $calendarsetting->cs_use_current=0;
                  $calendarsetting->cs_start_time = $request->startTime;
                }
                
                /***** Start: Default Sales Process Settings *******/
                $saleNestable = [];
                if(in_array('bookTeam', $request->saleStepSession) || in_array('bookIndiv', $request->saleStepSession)){
                  $saleNestable = json_decode($request->salesNestable,1);
                }
                $data = ['steps' => [], 'teamCount' => '', 'indivCount' => '', 'order' => $saleNestable, 'session' => []];
                if ($request->has('saleStepGen')) {
                    $data['steps'] = $request->saleStepGen;
                }
                if ($request->has('saleStepSession')) {
                    if (in_array('bookTeam', $request->saleStepSession) && $request->teamNumb){
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
                $calendarsetting->sales_process_settings = json_encode($data);
                /******* End: Default Sales Process Settings *******/ 

                    if($calendarsetting->save()){
                         $cs_id=$calendarsetting->id;
                      
                         $formData =$request->all();
                            ksort($formData);
                        //dd($formData);
                        $reason = [];
                        foreach($formData as $key => $value){ 
                            if(strpos($key, 'reason') !== false)
                            {
                                $timestamp = createTimestamp();
                                $reason[] = ['cscr_reason'=>$value/*,'cscr_business_id'=>Session::get('businessId')*/,'cscr_id'=>$cs_id,'created_at'=>$timestamp,'updated_at'=>$timestamp];
                            }
                           // else if(strpos($key, 'resId') !== false)
                              //  $reasonId[] = $value;
                            
                            /*else if(strpos($key, 'newReason') !== false){
                                $timestamp = createTimestamp();
                                $newReason[] = ['cscr_reason'=>$value,'cscr_business_id'=>Session::get('businessId'),'cscr_id'=>$cs_id,'created_at'=>$timestamp,'updated_at'=>$timestamp];
                                }*/
                            }
                              //dd($newReasion);
                            //CalendarSettingsCancelReasons::whereNotIn('cscr_id',$cs_id )->where('cscr_business_id',Session::get('businessId'))->delete();
                           
                            $calendarsetting->reason()->forceDelete();

                            /*if(count($reasion) && count($reasionId)){
                            for($i=0;$i<count($reasion);$i++){
                            CalendarSettingsCancelReasons::where('id',$reasionId[$i])->where('cscr_business_id',Session::get('businessId'))->update(['cscr_reason'=>$reasion[$i]]);
                               }
                             } */ 
      
                            /*if(count($newReason))
                            CalendarSettingsCancelReasons::insert($newReason);
                            */
                             if(count($reason))
                                CalendarSettingsCancelReasons::insert($reason); 
                                Session::put('message', 'success|Data has been updated successfully.');   
                                if($request->ajax()){
                                    //Session::put('ifBussHasSalesToolsInvoice', true);
                                    $msg['status'] = 'updated';
                                   // $msg['message'] = displayAlert('success|SalesToolsInvoice has been saved successfully.');
                                }
                                else
                                    Session::flash('flash_message', 'Data has been update successfully.');
                                   
                    }

              }
        }
        if($request->ajax())
            return json_encode($msg);
        else{
             if($isError)
                abort(404);
             else
                return redirect('calendar.edit');
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function resultUpdate($id, Request $request){
        $isError = false;
        if($request->ajax())
            $msg = [];
        if(!$isError){
            $calendarsetting = CalendarSetting::where(array('cs_client_id'=>$id,'cs_business_id'=>Session::get('businessId')))->get();
             
                $cal_setting = array();
                $cal_setting['cs_client_id'] = $id;
                $cal_setting['cs_first_day'] = $request->firstDay;
                if($request->useCurrentTime!=null){
                  $cal_setting['cs_start_time']=null;
                  $cal_setting['cs_use_current']=1;
                  
                }
                else{
                  $cal_setting['cs_start_time'] = $request->startTime;
                  $cal_setting['cs_use_current']=0;
                    
                }
                $cal_setting['cs_business_id'] = Session::get('businessId');
                $cal_setting['cs_intervals'] = $request->calendarInterval;
                $cal_setting['cs_view'] = $request->view;
                $cal_setting['cs_display_calendar'] = (is_null($request->disp_calendar)) ? 0 : $request->disp_calendar ;
                $cal_setting['cs_initial_status'] = $request->initial_status;
                $cal_setting['cs_add_company_name'] = (is_null($request->add_company_name)) ? 0 : $request->add_company_name ;
                $cal_setting['cs_allow_appointments'] = $request->allow_appointments;
                $cal_setting['cs_receive_email_summary'] = (is_null($request->receive_email_summary)) ? 0 : $request->receive_email_summary ;
                $timestamp = createTimestamp();

                // value end

             if(!$calendarsetting->isEmpty())
             {
                //update start
                // dd('update');
                $cs_id = $calendarsetting[0]['id'];

                $cal_setting['updated_at'] = $timestamp;
                // DB::enableQueryLog();
                CalendarSetting::where('cs_client_id', $cs_id)->update($cal_setting);
                // dd(DB::getQueryLog());

                if(CalendarSetting::where('id', $cs_id)->update($cal_setting))
                {
                    $msg['status'] = 'updated';
                }
                else
                {
                    $msg['status'] = 'update error';
                }

                //update end
                
             }
             else
             {
                // insert

                $cal_setting['created_at'] = $timestamp;
                $cal_setting['updated_at'] = $timestamp;
                $cal_setting['deleted_at'] = null;
                

                //dd($calendarsetting);
                if(CalendarSetting::insert($cal_setting))
                {
                    $msg['status'] = 'updated';
                }
                else
                {
                    $msg['status'] = 'insert error';
                }
                
             }
             
             
        }
        if($request->ajax())
            return json_encode($msg);
        else{
             if($isError)
                abort(404);
             else
                return redirect('calendar.edit');
            }
    }

    public function sendAppointmentSummary(){
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('Pacific/Auckland'));
        $dateTime = $dt->format('Y-m-d');
        $businesses = Business::with(['staffs.eventClasses'=>function($query) use($dateTime){
            $query->where('sec_date',$dateTime);
        },'staffs.events'=>function($query) use($dateTime){
            $query->where('sess_date',$dateTime);
        },'calendarSetting','staffs.eventClasses.clas','staffs.events.service'])->get();
        foreach ($businesses as $business) {
            if($business->calendarSetting->cs_receive_email_summary == '1'){
                $details = [];
                $i = 0;
                foreach ($business->staffs as $staff) {
                    $details[$i]['name'] = $staff->first_name." ".$staff->last_name;
                    $details[$i]['email'] = $staff->email;
                    $classes = $staff->eventClasses;
                    $j = 0;
                    foreach($classes as $class){
                        $details[$i]['classes'][$j]['name'] = $class->clas->cl_name;
                        $details[$i]['classes'][$j]['start_time'] = date('h:i A',strtotime($class->sec_start_datetime));
                        $details[$i]['classes'][$j]['end_time'] = date('h:i A',strtotime($class->sec_end_datetime));
                        $details[$i]['classes'][$j]['duration'] = $class->clas->cl_duration;
                       $j = $j + 1; 
                    }
                    $services = $staff->events;
                    $k = 0;
                    foreach($services as $service){
                        $details[$i]['services'][$j]['name'] = $service->service->one_on_one_name;
                        $details[$i]['services'][$j]['start_time'] = date('h:i A',strtotime($service->sess_start_datetime));
                        $details[$i]['services'][$j]['end_time'] = date('h:i A',strtotime($service->sess_end_datetime));
                        $details[$i]['services'][$j]['duration'] = $service->service->one_on_one_duration;
                       $j = $j + 1; 
                    }
        
                    $i = $i + 1;
                }
                foreach ($details as $detail) {
                    $classesHtml = '';
                    if(count($detail['classes'])){
                        $classesHtml = "<h1>Class Details</h1><table>";
                        foreach ($detail['classes'] as $classData) {
                            $classesHtml = $classesHtml."<tr><td><strong>Name:</strong></td><td>".$classData['name']."</td></tr><tr><td><strong>Time:</strong></td><td>".$classData['start_time']." - ".$classData['end_time']."</td></tr><tr><td><strong>Duration:</strong></td><td>".$classData['duration']." min</td></tr>";
                        }
                        $classesHtml = $classesHtml."</table>";
                    }
                    $serviceHtml = '';
                    if(count($detail['services'])){
                        $serviceHtml = "<h2>Service Details</h2><table>";
                        foreach ($detail['services'] as $serviceData) {
                            $serviceHtml = $serviceHtml."<tr><td><strong>Name:</strong></td><td>".$serviceData['name']."</td></tr><tr><td><strong>Time:</strong></td><td>".$serviceData['start_time']." - ".$serviceData['end_time']."</td></tr><tr><td><strong>Duration:</strong></td><td>".$serviceData['duration']." min</td></tr>";
                        }
                        $serviceHtml = $serviceHtml."</table>";
                    }
                    $message = "<!DOCTYPE html>
                            <html lang='en-US'>
                            <head>
                            </head>
                            <body>
                                <h2>EPICFIT Appointment Detail Summary for ".$dt->format('d/m/Y')." </h2>
                                <div>
                                    <p>Following are the details of Appointments:</p>";
                    if(isset($classesHtml)){
                       $message = $message.$classesHtml; 
                    }
                    if(isset($serviceHtml)){
                        $message = $message.$serviceHtml; 
                    }
                    $message = $message."</div></body></html>";
                    if(count($detail['classes']) || count($detail['services'])){
                      $this->sendSummaryEmail($detail,$message);
                    }
                }
            }
        }
    }

     protected function sendSummaryEmail($detail,$message){
        // this is temporary mail code which is written in core php.
        $username = $detail['name'];
        $to = $detail['email'];
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
            $mail->Subject = "EPICFIT Appointment Summary";
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

}
