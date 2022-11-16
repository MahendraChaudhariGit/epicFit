<?php

namespace App\Http\Controllers\Result;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Session;
use Auth;
use DB;
use App\ClientMenu;
use App\Result\User;
use App\Clients;
use App\Invoice;
use App\GoalBuddy;
use App\Benchmarks;
use App\AbClientPlan;
use App\StaffEventClass;
use App\AbClientPlanDate;
use App\StaffEventSingleService;
use App\PersonalMeasurement;
use App\PersonalStatistic;

use App\Http\Traits\Result\HelperTrait;
use App\Http\Traits\StaffEventsTrait;

class DashboardController extends Controller {
    use HelperTrait, StaffEventsTrait;

    /**
     * Show dashboard
     * @param null
     * @return dashboard view
     **/
    public function show(){
    	if(!Session::has('businessId')){
    		Auth::logout();
	        Session::flush();
	        return redirect('login/');
    	}

    	/* client details*/
    	$businessId = Auth::user()->business_id;
    	$clientId = Auth::user()->account_id;
        $userId = Auth::id();



    	/* Client Details */
		$client = Clients::with('parq')->find($clientId);
        $parq = $client->parq;

        /* Appointment */
        $date = Carbon::now()->toDateString();
        $appointments = $this->getAppointmentByDate($client, $date);

        /* Invoices */
        $all_invoices = Invoice::where('inv_business_id', $businessId)->where('inv_client_id', $clientId)->orderBy('inv_id','desc')->get();
        $paid_invoice = $all_invoices->where('inv_status', 'Paid')->take(5);
        $unpaid_invoice = $all_invoices->where('inv_status', 'Unpaid')->take(5);

        /* Benchmark */
        $benchmarks = Benchmarks::where('client_id', $clientId)->orderBy('id','desc')->take(10)->get();

        /* Goals */
        $goals = $this->getGoalBodyByDate($clientId, $date);

        /* Activities */
        $activities = $this->getActivitiesByDate($clientId, $date);

        //dd($appointments);
         $clientSelectedMenus = [];

         
        $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];

        /* Forms Completation */
        $forms = DB::table('parqs')->where('client_id', '=', $clientId)->first();

        $duration = 6;
        $bodypart = 'height';
        $personal_measurement = PersonalMeasurement::select('id','event_date')->orderBy('event_date','asc')->orderBy('id','asc')->where('client_id',Auth::User()->account_id);
        
        if($duration == 6){
            $stepSize =  1;
            $pastSixThreeMonths = date("Y-m-d", strtotime("first day of -5 months"));
            $startOfTheMonth =  $pastSixThreeMonths;
            $Newdate =  Carbon::now()->format('Y-m-d');
            $currentMonth = date("Y-m-d");
            $endOfTheMonth =  $currentMonth;
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastSixThreeMonths , $currentMonth])->get()->toArray();
           $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
        }
        if($bodypart == 'height'){
            $get_last_data = PersonalMeasurement::select('event_date','height','heightUnit')->where('height','>',0)->whereIn('id',$event_id)->orderBy('event_date','desc')->limit(1)->first();
            $get_data = PersonalMeasurement::select('event_date','height','heightUnit')->where('height','>',0)->where('client_id',Auth::user()->account_id)->limit(1)->first();
            $personal_measurement = PersonalMeasurement::select('event_date','height','heightUnit')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            if(isset($get_data)){
                foreach($personal_measurement as $value){
                    if($value['height'] > 0){
                        if(isset($get_last_data) && $get_last_data->heightUnit == 'inches'){
                            if($value['heightUnit'] == 'cm'){
                                $data[] = ['date' => $value['event_date'],'value'=>(number_format((float)($value['height']*0.393701), 2, '.', ''))];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['height']];
                            }
                        }else{
                            if($value['heightUnit'] == 'inches'){
                                $data[] = ['date' => $value['event_date'],'value'=>(number_format((float)($value['height']/0.393701), 2, '.', ''))];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['height']];
                            }
                        }
                        
                    }

                }
                $height_unit = isset($get_last_data) ? ($get_last_data['heightUnit'] == 'inches' ? 'inches' : 'cm') : 'cm';
            }else{
                $clients = Clients::with(['parq'=>function($q){
                    $q->select('client_id','height','heightUnit');
                }])->find(Auth::User()->account_id);
                $get_last_data = $clients->parq;
                // dd($get_last_data);

                if(isset($get_last_data) && $get_last_data->heightUnit != 'Metric'){
                    if($get_last_data->heightUnit == 'Metric'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>(number_format((float)($get_last_data->height*0.393701), 2, '.', ''))];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->height];
                    }
                }else{
                    if($get_last_data->heightUnit == 'inches'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>(number_format((float)($get_last_data->height/0.393701), 2, '.', ''))];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->height];
                    }
                }
                $height_unit = isset($get_last_data) ? ($get_last_data->heightUnit != 'Metric' ? 'inches' : 'cm') : 'cm';
            }
            
            $body_part = 'Height';
        }

// dd($data,$body_part,$bodypart,$duration,$weight_unit,$height_unit,$startOfTheMonth,$stepSize,$endOfTheMonth);

            
        return view('Result.dashboard', compact('unpaid_invoice','paid_invoice','benchmarks','appointments','activities','goals','clientSelectedMenus','forms',
        'data','body_part','bodypart','duration','weight_unit','height_unit','startOfTheMonth','stepSize','endOfTheMonth'));
    }

    /**
     * Get appointment, activities and goal data throught ajax
     * @param date, section name
     * @return response
     **/
    public function getAppSectionData(Request $request){
        $response = array();
        $businessId = Auth::user()->business_id;
        $clientId = Auth::user()->account_id;
        $userid = Auth::id();
        $date = $request->date;
        $section_name = $request->section_name;

        if($section_name == 'appointments'){
            $client = Clients::find($clientId);
            $response['appointments'] = $this->getAppointmentByDate($client, $date);
        }
        elseif($section_name == 'activities'){
            $response['activities'] = $this->getActivitiesByDate($clientId, $date);
        }
        elseif($section_name == 'goals'){
           $response['goals']= $this->getGoalBodyByDate($userid, $date); 
        }
        elseif($section_name == 'all'){
            $client = Clients::find($clientId);
            $response['appointments'] = $this->getAppointmentByDate($client, $date);
            $response['activities'] = $this->getActivitiesByDate($clientId, $date);
            $response['goals'] = $this->getGoalBodyByDate($userid, $date); 
        }

        return json_encode($response);
    }

    /**
     * Fatch activities accourding to date and current client
     * @param clientId, date
     * @return activities array 
     **/
    protected function getActivitiesByDate($clientId, $date){
        $activities = array();
        $businessId = Auth::user()->business_id;
        $palnIds = AbClientPlanDate::whereDate('plan_start_date','=', $date)->distinct()->pluck('client_plan_id')->toArray();
        $plans = AbClientPlan::where('businessId', $businessId)
                                    ->where('clientId', $clientId)
                                    ->whereIn('id',$palnIds)
                                    ->orderBy('id','desc')
                                    ->select('id','name','discription')
                                    ->take(10)
                                    ->get();
        if($plans->count()){
            foreach ($plans as $plan) {
               $activities[] = array('date'=>dbDateToDateString($date), 'desc'=>$plan->name); 
            }
        }

        return $activities;
    }

    /**
     * Fatch Goal body accourding to date and current client
     * @param clientId, date
     * @return goal body array 
     **/
    protected function getGoalBodyByDate($clientId, $date){
        //$date = '2018-04-25';
        $goalbody = array();
        // $goals = GoalBuddy::with('goalBuddyMilestones')->where('gb_client_id', $clientId)->whereDate('gb_due_date','=', $date)->orderBy('id','desc')->take(10)->get();
        $goals = GoalBuddy::with('goalBuddyMilestones')->where('gb_client_id', $clientId)->orderBy('id','desc')->get();

        if($goals->count()){
            $percenteg = 0; 
            foreach ($goals as $goal) {
                $milestonesNo=$goal->goalBuddyMilestones->count();
                if($milestonesNo > 0 )
                    $completedMilestone = 0;
                    foreach($goal->goalBuddyMilestones as $milestone){
                        if($milestone->gb_milestones_status){
                            $completedMilestone = $completedMilestone + 1;
                        }
                    }
                    $percenteg =sprintf ("%.2f", ($completedMilestone/$milestonesNo) * 100); ;

                $goalbody[] = array('name'=>$goal->gb_goal_name,'due_date'=>dbDateToDateString($goal->gb_due_date),'progress_name'=>'Milestone','per'=>$percenteg); 
            }
        }
        return $goalbody;
    }

    /**
     * Fatch all class and service acording to date and current client
     * @param Client calss object, Date
     * @return appointment array
     **/
    protected function getAppointmentByDate($clientObj, $date){
    	$appointments = collect();
    	$services = $clientObj->clientEventService($date)->get()->toArray();
    	$classes = $clientObj->clientEventClasses($date)->get()->toArray();
    	$appointments = array();
        if(count($classes)){
            foreach ($classes as $cls) {
                $desc = $cls['clas_with_trashed']['cl_name'].' with '.$cls['staff_with_trashed']['first_name'].' '.$cls['staff_with_trashed']['last_name'].' at '.dbTimeToTimeString($cls['eventTime']);
                $appointments[] = array('date'=>dbDateToDateString($cls['eventDate']),'desc'=>$desc,'type'=>'class','dateTime' =>$cls['eventDate'].' '.$cls['eventTime'] );   
            }
        }
        if(count($services)){
            foreach ($services as $service) {
                if($service['service_with_trashed']['one_on_one_name'] != '')
                    $serviceName = $service['service_with_trashed']['one_on_one_name'];
                elseif($service['service_with_trashed']['team_name'] != '')
                    $serviceName = $service['service_with_trashed']['team_name'];
                else
                    $serviceName = '';

               $desc = $serviceName.' with '.$service['staff_with_trashed']['first_name'].' '.$service['staff_with_trashed']['last_name'].' at '.dbTimeToTimeString($service['eventTime']);
                $appointments[] = array('date'=>dbDateToDateString($service['eventDate']),'desc'=>$desc,'type'=>'service','dateTime' =>$service['eventDate'].' '.$service['eventTime'] );    
            }
        }
       
        usort($appointments, function ($item1, $item2) {
            return $item1['dateTime'] <=> $item2['dateTime'];
        });
        // dd($appointments);
		return $appointments;
    }
}
