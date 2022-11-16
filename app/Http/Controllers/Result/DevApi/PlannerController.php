<?php

namespace App\Http\Controllers\DevApi;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Clients;
use Carbon\Carbon;
use App\Category;
use DB;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\ActivityPlans;
use App\Exercise;
use App\AbPlanWorkout;
use App\AbWorkout;
use App\AbWorkoutExercise;
use App\AbFavorateExercise;
use App\AbClientPlan;
use App\AbPlans;
use \stdClass;


class PlannerController extends Controller {

    public function GetGeneratedPlans(Request $request) { 	
    	$businessId = Auth::user()->business_id;
        $clientId = Auth::user()->account_id;
        $where = array(
			'clientId' => $clientId,
			'businessId' => $businessId
		);

        $GeneratedPlans = AbClientPlan::where($where)->orderBy('id', 'DESC')->limit(5)->get();
        // $query->limit($perPage)->offset(($pageNumber-1)*$perPage);

        $plan_list = array();
        $response_arr = array();
        foreach ($GeneratedPlans as $key=>$plan) {
			$plan_list [$key]['ProgramName'] = $plan['name'];
			$plan_list [$key]['UserGeneratedPlanID'] = $plan['id'];
			$plan_list [$key]['PlanGenerationDate'] = $plan['created_at']->timestamp;
			$plan_list [$key]['RefDistanceMeters'] = $plan['refDistanceMeters'];
        }
        $response_arr['MessageId'] = 0;
        $response_arr['Plans'] = $plan_list;
        
        return json_encode($response_arr);
    }

	/*	
		http://api.onesportevent.com/DevApi/Planner/GetPlan?
		(mendetory)
		Gender=2&GetPreWritten=false
		(opttional)
		Plan=7&DaysOfWeek=1111111&Height=115&Weight=60&Age=11&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&Email=tester%40epicstride.com&Habit=3&Experience=100&
		Intensity=100&Method=1&WeeksToExercise=11&TimePerWeek=88&FixedProgramId=21

	    WHAT EQUIPMENT DO YOU HAVE
	    	medhod
			    1=GYM 
			    2= FREE WEIGHTS
			    3= BODY WEIGHT
			    4= SWISS BALL
	*/

    public function GetPlan(Request $request) {
    	$day_array = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    	$full_day_array = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
		$curr_day = date('l');
		$next_day = date('l', strtotime(' +1 day'));

		$experience_level = [1=>'experienceHigh',2=>'experienceMedium',3=>'experienceLow',];
		$response = array();

		$plan_id = (!empty(\Request::get('Plan')))? \Request::get('Plan'): null;
		$Gender = (!empty(\Request::get('Gender')))? \Request::get('Gender'): null;
		$Height = (!empty(\Request::get('Height')))? \Request::get('Height'): null;
		$Weight = (!empty(\Request::get('Weight')))? \Request::get('Weight'): null;
		$Age = (!empty(\Request::get('Age')))? \Request::get('Age'): null;
		$Email = (!empty(\Request::get('Email')))? \Request::get('Email'): null;
		$Habit = (!empty(\Request::get('Habit')))? \Request::get('Habit'): 0;
		$Intensity = (!empty(\Request::get('Intensity')))? \Request::get('Intensity'): 0;
		$Method = (!empty(\Request::get('Method')))? \Request::get('Method'): null;
		$WeeksToExercise = (!empty(\Request::get('WeeksToExercise')))? \Request::get('WeeksToExercise'): 0;
		$TimePerWeek = (!empty(\Request::get('TimePerWeek')))? \Request::get('TimePerWeek'): 0;
		$ScheduleType = (!empty(\Request::get('ScheduleType')))? \Request::get('ScheduleType'): 0;
		$FixedProgramId = (!empty(\Request::get('FixedProgramId')))? \Request::get('FixedProgramId'): null;
		$DaysOfWeek = (!empty(\Request::get('DaysOfWeek')))? \Request::get('DaysOfWeek'): null;
		$SessionGuid = (!empty(\Request::get('SessionGuid')))? \Request::get('SessionGuid'): null;
		$GetPreWritten = (!empty(\Request::get('GetPreWritten')))? \Request::get('GetPreWritten'): false;
		$Experience = (!empty(\Request::get('Experience')))? \Request::get('Experience'): 0;
		
		$where = [];
		if(!empty(\Request::get('Plan'))){
			$where['plan_id'] = \Request::get('Plan');
		}
		if(!empty(\Request::get('Gender'))){
			if(\Request::get('GetPreWritten') == 'true'){
				$gender = array(\Request::get('Gender'),0);	
			}
			else{
				$gender = array(\Request::get('Gender'));
			}
		}
		if(!empty(\Request::get('Habit'))){
			$where[$experience_level[\Request::get('Habit')]] = 'true';
			// $where['habit'] = \Request::get('Habit');
		}	
		if(!empty(\Request::get('Intensity'))){
			$where['intensity'] = \Request::get('Intensity');
		}
		if(!empty(\Request::get('Method'))){
			$where['method'] = \Request::get('Method');
		}
		if(!empty(\Request::get('FixedProgramId'))){
			$where['id'] = \Request::get('FixedProgramId');
		}
		if(!empty(\Request::get('GetPreWritten') && (\Request::get('GetPreWritten')) == 'true')){
			$where['plan_type'] = 2; // 2 = library plans
		}
		if(!empty(\Request::get('Experience'))){
			$where['experience'] = \Request::get('Experience');
		}


		$response = [];
		$response['MessageId'] = 0;
    	$response['Mode'] = ($GetPreWritten == 'true') ? 'Prewritten' : 'Dynamic';
		$prog_details_list = AbPlans::with('workouts')->where($where)->whereIn('p_gender',$gender)->get();	

		if($GetPreWritten == 'false'){
			$response['Response']['Message'] = null;
			$response['Response']['Plans'] = [];
		}
		if((!is_null($Method) && (($plan_id == 1) || ($plan_id == 2) || ($plan_id == 3))) || $GetPreWritten == 'true'){
			if(count($prog_details_list) > 0){  
	        	if(empty($WeeksToExercise) && empty($TimePerWeek)  && $GetPreWritten == 'false'){
					$response['Response']['Message'] = "We have tried our best to plan your schedule.However we can not meet your time limit.Please increase your time or decrease the intensity";
				}
	        	$workout_detail = array();
	        	$SubGroups = array();
	        	foreach ($prog_details_list as $prog_key => $prog_details) {	
	        		if($GetPreWritten == 'true'){
	        			$response["Response"][$prog_key]["FixedProgramId"]=$prog_details['id'];
						$response["Response"][$prog_key]["ProgramName"]=$prog_details['name'];
						$response["Response"][$prog_key]["ProgramDesc"]=$prog_details['description'];
						$response["Response"][$prog_key]["Gender"]=$prog_details['p_gender'];
						$response["Response"][$prog_key]["ExperienceLow"]=$prog_details['experienceLow'];
						$response["Response"][$prog_key]["ExperienceMedium"]=$prog_details['experienceMedium'];
						$response["Response"][$prog_key]["ExperienceHigh"]=$prog_details['experienceHigh'];
						$response["Response"][$prog_key]["DefaultWeeks"]=$prog_details['defaultWeeks'];
						$response["Response"][$prog_key]["Image"]=$prog_details['image'];
						$response["Response"][$prog_key]["DayPattern"]=$prog_details['dayPattern'];
						$response["Response"][$prog_key]["IsRepeatedForWeeks"]=$prog_details['isRepeatedForWeeks'];
						$response["Response"][$prog_key]["IsPaidProgram"]=$prog_details['isPaidProgram'];
						$response["Response"][$prog_key]["IsPlatformProgram"]=$prog_details['isPlatformProgram'];
						$response["Response"][$prog_key]["Snippet"]=$prog_details['snippet'];
						$response["Response"][$prog_key]["PersonID"]=1;
						$response["Response"][$prog_key]["DateChanged"]=$prog_details["updated_at"]->timestamp;
	        		}
	        		else{
	        			foreach($prog_details['workouts'] as $WEkey => $WEvalue){
							$exer_details = AbWorkoutExercise::with('exercises')->where('workout_id',$WEvalue['pivot']['id'])->get();
							if($exer_details){
								foreach ($exer_details as $ex_k => $ex_value) {
									$itme2 = (($WEvalue['name'] == 'warm up') || ($WEvalue['name'] == 'cool down')) ? $ex_value['estimatedTime'].":00" :$ex_value['sets']." x 1 set";

									$workout_detail[$ex_k]["Item1"]= $ex_value['exercises']['exerciseDesc'];
									$workout_detail[$ex_k]["Item2"]= $itme2;
								}
							}

							$SubGroups[] = array(
								'Key' => $WEvalue['name'],
								'Value' => $workout_detail
							);

						}
	        		}
	        	}
				if($GetPreWritten == 'false'){
		        	$needle = "1";
					$lastPos = 0;
					$positions = array();
					// dd($DaysOfWeek);
					while (($lastPos = strpos($DaysOfWeek, $needle, $lastPos))!== false) {
					    $positions[] = $lastPos;
					    $lastPos = $lastPos + strlen($needle);
					}
					$selected_days = array_intersect_key($full_day_array, array_flip($positions));

					// dd($next_day);
					$day_name = '';
					// dd($selected_days);
					foreach ($selected_days as $Dkey => $Dvalue) {
						$day_name = ($Dvalue == $curr_day) ? 'Today' : (($Dvalue == $next_day) ? 'Tomorrow': $Dvalue) ;
						$response['Response']['Plans'][$day_name]['SubGroups'] = $SubGroups;
					}
				}
			}
		}	
		$response["PlanType"]= [
				"Gender"=> $Gender,
				"Plan"=> $plan_id,
				"Habit"=> $Habit,
				"Method"=> $Method,
				"Intensity"=> $Intensity,
				"Experience"=> $Experience,
				"ScheduleType"=> $ScheduleType,
				"TimePerWeek"=> $TimePerWeek,
				"DaysOfWeek"=> $DaysOfWeek,
				"Weight"=> $Weight,
				"Height"=> $Height,
				"Age"=> $Age,
				"WeeksToExercise"=> $WeeksToExercise,
				"TimeOnClient"=> null,
				"Email"=> $Email,
				"SessionGuid"=> $SessionGuid,
				"FixedProgramId"=> $FixedProgramId,
				"GetPreWritten"=> $GetPreWritten
		];
		return json_encode($response);
    }

	/*public function GetPlan(Request $request) {
		$day_array = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
		$response = array();
		$plan_id = \Request::get('Plan');
		$Gender = (!empty(\Request::get('Gender')))? \Request::get('Gender'): null;
		$DaysOfWeek = (!empty(\Request::get('DaysOfWeek')))? \Request::get('DaysOfWeek'): null;
		$Height = (!empty(\Request::get('Height')))? \Request::get('Height'): null;
		$Weight = (!empty(\Request::get('Weight')))? \Request::get('Weight'): null;
		$Age = (!empty(\Request::get('Age')))? \Request::get('Age'): null;
		$SessionGuid = (!empty(\Request::get('SessionGuid')))? \Request::get('SessionGuid'): null;
		$Email = (!empty(\Request::get('Email')))? \Request::get('Email'): null;
		$GetPreWritten = (!empty(\Request::get('GetPreWritten')))? \Request::get('GetPreWritten'): false;
		$Habit = (!empty(\Request::get('Habit')))? \Request::get('Habit'): null;
		$Experience = (!empty(\Request::get('Experience')))? \Request::get('Experience'): null;
		$Intensity = (!empty(\Request::get('Intensity')))? \Request::get('Intensity'): null;
		$Method = (!empty(\Request::get('Method')))? \Request::get('Method'): null;
		$WeeksToExercise = (!empty(\Request::get('WeeksToExercise')))? \Request::get('WeeksToExercise'): null;
		$TimePerWeek = (!empty(\Request::get('TimePerWeek')))? \Request::get('TimePerWeek'): null;
		$ScheduleType = (!empty(\Request::get('ScheduleType')))? \Request::get('ScheduleType'): null;
		$FixedProgramId = (!empty(\Request::get('FixedProgramId')))? \Request::get('FixedProgramId'): null;
		
		$response = [];
		if($plan_details = AbClientPlan::find($plan_id)){
			$p_id = $plan_details['fixedProgramId'];
			$prog_details = AbPlans::with('workouts')->find($p_id);	
			
			if($prog_details){
	        	$workout_detail = array();
	        	$SubGroups = array();

	        	$response['MessageId'] = 0;
	        	$response['Mode'] = "Dynamic";
	        	// dd($prog_details['workouts']);
	        	foreach($prog_details['workouts'] as $WEkey => $WEvalue){
					$exer_details = AbWorkoutExercise::with('exercises')->where('workout_id',$WEvalue['pivot']['id'])->get();
					// dd($exer_details);
					if($exer_details){
						foreach ($exer_details as $ex_k => $ex_value) {
							$itme2 = (($WEvalue['name'] == 'warm up') || ($WEvalue['name'] == 'cool down')) ? $ex_value['estimatedTime'].":00" :$ex_value['sets']." x 1 set";

							// dd($WEvalue['name']);
							$workout_detail[$ex_k]["Item1"]= $ex_value['exercises']['exerciseDesc'];
							$workout_detail[$ex_k]["Item2"]= $itme2;
						}
					}

					$SubGroups[] = array(
						'Key' => $WEvalue['name'],
						'Value' => $workout_detail
					);
				}
			}

			// dd($SubGroups);
			$needle = "1";
			$lastPos = 0;
			$positions = array();

			// while (($lastPos = strpos($plan_details['daysOfWeek'], $needle, $lastPos))!== false) {
			while (($lastPos = strpos($DaysOfWeek, $needle, $lastPos))!== false) {
			    $positions[] = $lastPos;
			    $lastPos = $lastPos + strlen($needle);
			}
			$selected_days = array_intersect_key($day_array, array_flip($positions));
			$response['Response']['Message'] = null;
			$curr_day = date('D');
			$day_name = '';
			foreach ($selected_days as $Dkey => $Dvalue) {
				$day_name = ($Dvalue == $curr_day) ? 'Today' : $Dvalue;
				$response['Response']['Plans'][$day_name]['SubGroups'] = $SubGroups;
			}
			// dd($selected_days);
		}

		$response["PlanType"]= [
				"Gender"=> $Gender,
				"Plan"=> $plan_id,
				"Habit"=> $Habit,
				"Method"=> $Method,
				"Intensity"=> $Intensity,
				"Experience"=> $Experience,
				"ScheduleType"=> $ScheduleType,
				"TimePerWeek"=> $TimePerWeek,
				"DaysOfWeek"=> $DaysOfWeek,
				"Weight"=> $Weight,
				"Height"=> $Height,
				"Age"=> $Age,
				"WeeksToExercise"=> $WeeksToExercise,
				"TimeOnClient"=> "10-Sept-2017 15:29",
				"Email"=> $Email,
				"SessionGuid"=> $SessionGuid,
				"FixedProgramId"=> $FixedProgramId,
				"GetPreWritten"=> $GetPreWritten
		];
		
		// echo "<pre>";print_r($response);exit;
		return json_encode($response);
		return '{
			"MessageId": 0,
			"Mode": "Dynamic",
			"Response": {
				"Message": null,
				"Plans": {
					"Today": {
						"SubGroups": [
							{
								"Key": "Warm Up",
								"Value": [
									{
										"Item1": "Stationary Bike",
										"Item2": "05:00"
									}
								]
							},
							{
								"Key": "Exercises",
								"Value": [
									{
										"Item1": "Leg Press",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Crunch",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Tricep Extension",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Ball Hammie Curl",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Lateral Pull Down - Machine",
										"Item2": "10 x 1 set"
									}
								]
							},
							{
								"Key": "Cool Down",
								"Value": [
									{
										"Item1": "Stretches - General",
										"Item2": "05:00"
									}
								]
							}
						]
					},
					"Monday": {
						"SubGroups": [
							{
								"Key": "Warm Up",
								"Value": [
									{
										"Item1": "Cross Trainer",
										"Item2": "05:00"
									}
								]
							},
							{
								"Key": "Exercises",
								"Value": [
									{
										"Item1": "Lunges - On Spot",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Roman Chair - Knees Tuck",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Lateral Raise",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Row - Machine",
										"Item2": "10 x 1 set"
									}
								]
							},
							{
								"Key": "Cool Down",
								"Value": [
									{
										"Item1": "Stretches - General",
										"Item2": "05:00"
									}
								]
							}
						]
					},
					"Wednesday": {
						"SubGroups": [
							{
								"Key": "Warm Up",
								"Value": [
									{
										"Item1": "Treadmill",
										"Item2": "05:00"
									}
								]
							},
							{
								"Key": "Exercises",
								"Value": [
									{
										"Item1": "Leg Press",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Crunch",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Tricep Extension",
										"Item2": "10 x 1 set"
									},
									{
										"Item1": "Ball Hammie Curl",
										"Item2": "10 x 1 set"
									}
								]
							},
							{
								"Key": "Cool Down",
								"Value": [
									{
										"Item1": "Stretches - General",
										"Item2": "05:00"
									}
								]
							}
						]
					}
				}
			},
			"PlanType": {
				"Gender": 1,
				"Plan": 1,
				"Habit": 2,
				"Method": 1,
				"Intensity": 300,
				"Experience": 300,
				"ScheduleType": 2,
				"TimePerWeek": 60,
				"DaysOfWeek": "0101010",
				"Weight": 62,
				"Height": 120,
				"Age": 12,
				"WeeksToExercise": 12,
				"TimeOnClient": "10-Sept-2017 15:29",
				"Email": "tester@epicstride.com",
				"SessionGuid": "d95a16d3-3a54-4598-a55a-af4abc086a29",
				"FixedProgramId": null,
				"GetPreWritten": false
			}
		}';
    } */
    
	public function SavePlan(Request $request) {
		$prog_details = AbPlans::with('workouts')->find(\Request::get('FixedProgramId'));
		$plan_unique_id = $prog_details['plan_unique_id'];
		$where['plan_unique_id'] = $plan_unique_id;
		$FixedProgramId = \Request::get('FixedProgramId');
		if(!empty($FixedProgramId)){
			$where['id'] = $FixedProgramId;
		}

		$program_detail = ActivityPlans::
	    select(DB::raw('max(id) as id,plan_unique_id,name,description,snippet,updated_at,defaultWeeks,defaultWeeks,dayPattern,p_gender,experienceLow,experienceMedium,experienceHigh,image'))
	    ->groupBy('plan_unique_id')
	    ->where($where)
	    ->orderBy('id', 'desc')
	    ->orderBy('id', 'desc')
	    ->get();

		$plan_name_arr = [0=>'default',1=>'increase strength',2=>'weight loss/tone',3=>'general health',6=>'library'];
		// $plan_name_arr[7] = $program_detail[0]['name'];
		$businessId = Auth::user()->business_id;
		$clientId = Auth::user()->account_id;
		$plan_id = \Request::get('Plan');
		$plan_name = '';
		/*if(array_key_exists($plan_id, $plan_name_arr)){
			$plan_name = $plan_name_arr[$plan_id];
		}
		else{
			$plan_name = $plan_name_arr[0];
		}
		$plan_name = $plan_name." program generated on ".Carbon::now()->format('d-M-Y H:i');*/

		$plan_name = $program_detail[0]['name']." program generated on ".Carbon::now()->format('d-M-Y H:i');
	    // dd($program_detail[0]['id']);
	    $FixedProgramId = $program_detail[0]['id'];	
		$c_plan = array();
		$c_plan['clientId'] = $clientId;	
		$c_plan['businessId'] = $businessId;	
		$c_plan['plan'] = $plan_id;	
		$c_plan['name'] = $plan_name;	
		$c_plan['weeksToExercise'] = \Request::get('WeeksToExercise');	
		$c_plan['daysOfWeek'] = \Request::get('DaysOfWeek');	
		$c_plan['getPreWritten'] = \Request::get('GetPreWritten');	
		$c_plan['sessionGuid'] = \Request::get('SessionGuid');	
		$c_plan['email'] = \Request::get('Email');	
		$c_plan['method'] = (!empty(\Request::get('Email')) ? \Request::get('Email') : null);	
		$c_plan['intensity'] = (!empty(\Request::get('Intensity')) ? \Request::get('Intensity') : null);	
		$c_plan['experience'] = (!empty(\Request::get('Experience')) ? \Request::get('Experience') : null);	
		$c_plan['timePerWeek'] = (!empty(\Request::get('TimePerWeek')) ? \Request::get('TimePerWeek') : null);	
		$c_plan['habit'] = 2;	
		$c_plan['scheduleType'] = 2;	
		$c_plan['height'] = (!empty(\Request::get('Height')) ? \Request::get('Height') : null);	
		$c_plan['weight'] = (!empty(\Request::get('Weight')) ? \Request::get('Weight') : null);	
		$c_plan['gender'] = 2;	
		$c_plan['age'] = (!empty(\Request::get('Age')) ? \Request::get('Age') : 0);	
		$c_plan['fixedProgramId'] = (!empty($FixedProgramId) ? $FixedProgramId : $plan_id); //default is plan no. need to change	
		$c_plan['custom'] = 1;	
		$c_plan['active'] = 1;	
		$c_plan['created_at'] = Carbon::now();	
		$c_plan['updated_at'] = Carbon::now();	

		$response = array();

		$up_where['businessId'] = $businessId;
		$up_where['clientId'] = $clientId;
		// $up_where['plan'] = 7; //custom plan id

		$up_field['active'] = 0;
		
		$cl_count = AbClientPlan::where($up_where)->count();
		
		$success = 0;
		if($cl_count == 0){
			if($plan_id = AbClientPlan::insertGetId($c_plan)){
				$success = 1;
			}
		}
		else{
			if((AbClientPlan::where($up_where)->update($up_field)) && ($plan_id = AbClientPlan::insertGetId($c_plan))){
				$success = 1;
			}
		}

		if($success == 1){
			$response["MessageId"] = 0;
			$response["Message"] = "Custom plan saved successfully";
			$response["Response"] = "done";
			$response["PlanType"] = array(
				"Gender"=> 2,
				"Plan"=> $plan_id,
				"Habit"=> 2,
				"Method"=> 1,
				"Intensity"=> 300,
				"Experience"=> 300,
				"ScheduleType"=> 2,
				"TimePerWeek"=> 60,
				"DaysOfWeek"=> "1100000",
				"Weight"=> 0,
				"Height"=> 0,
				"Age"=> 0,
				"WeeksToExercise"=> 12,
				"TimeOnClient"=> Carbon::now(),
				"Email"=> "tester@epicstride.com",
				"SessionGuid"=> "d95a16d3-3a54-4598-a55a-af4abc086a29",
				"FixedProgramId"=> $FixedProgramId,
				"GetPreWritten"=> false
			);	
		}
		return json_encode($response);

		// echo '\\192.168.0.50\websites\result\temp\fitness-planner-ajax\saving designer.txt';
		// echo '\\192.168.0.50\websites\result\temp\fitness-planner-ajax\saving generator.txt';
		// echo '\\192.168.0.50\websites\result\temp\fitness-planner-ajax\saving library.txt';
    }
    

	/*http://api.onesportevent.com/DevApi/Planner/GetGeneratedPlanDetail?jsoncallback=jQuery111305554412296538541_1489573333598&userGeneratedPlanId=13312&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489573333600
	*/

	public function GetGeneratedPlanDetail(Request $request) {
		$day_array = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
		$response = array();
		$plan_id = \Request::get('userGeneratedPlanId');
		
		$response = [];
		if($plan_details = AbClientPlan::find($plan_id)){
			$p_id = $plan_details['fixedProgramId'];
			$prog_details = AbPlans::with('workouts')->find($p_id);	
			
			if($prog_details){
	        	$workout_detail = array();
	        	$SubGroups = array();

	        	$response['MessageId'] = 0;
	        	// dd($prog_details['workouts']);
	        	foreach($prog_details['workouts'] as $WEkey => $WEvalue){
					$exer_details = AbWorkoutExercise::with('exercises')->where('workout_id',$WEvalue['pivot']['id'])->get();
					// dd($exer_details);
					if(count($exer_details) > 0){
						foreach ($exer_details as $ex_k => $ex_value) {
							$itme2 = (($WEvalue['name'] == 'warm up') || ($WEvalue['name'] == 'cool down')) ? $ex_value['estimatedTime'].":00" :$ex_value['sets']." x 1 set";

							// dd($WEvalue['name']);
							$workout_detail[$ex_k]["Item1"]= $ex_value['exercises']['exerciseDesc'];
							$workout_detail[$ex_k]["Item2"]= $itme2;
						}
						$SubGroups[] = array(
							'Key' => $WEvalue['name'],
							'Value' => $workout_detail
						);
					}	
				}
			}

			$needle = "1";
			$lastPos = 0;
			$positions = array();

			while (($lastPos = strpos($plan_details['daysOfWeek'], $needle, $lastPos))!== false) {
			    $positions[] = $lastPos;
			    $lastPos = $lastPos + strlen($needle);
			}
			$selected_days = array_intersect_key($day_array, array_flip($positions));
			foreach ($selected_days as $Dkey => $Dvalue) {
				$response['Plans'][$Dvalue]['SubGroups'] = $SubGroups;
			}
		}
		return json_encode($response);
	}
	    
	

	/*http://api.onesportevent.com/DevApi/CustomPlan/GetUsersPlans?jsoncallback=jQuery1113007200950278247542_1489728196422&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489728196425*/

	public function GetUsersPlans(Request $request) {
		$businessId = Auth::user()->business_id;
		$clientId = Auth::user()->account_id;

		$p_detail = array();
		$response = array();
		$response['MessageId'] = 0;

		$where = array(
			'created_by' => $clientId,
			'businessId' => $businessId
		);

		// DB::enableQueryLog();
		// $program_list = ActivityPlans::where($where)->orderBy('id', 'DESC')->groupBy('plan_unique_id')->get();

		$program_list = ActivityPlans::
	    select(DB::raw('max(id) as id,name,description,snippet,updated_at,defaultWeeks,dayPattern,p_gender,experienceLow,experienceMedium,experienceHigh,image'))
	    ->groupBy('plan_unique_id')
	    ->where('plan_type',3)
	    ->where($where)
	    ->orderBy('id', 'desc')
	    ->get();

		// dd(DB::getQueryLog());
		if(count($program_list) > 0){
			foreach ($program_list as $key => $value) {
				$p_detail[$key]["FixedProgramId"]= $value["id"];
				$p_detail[$key]["ProgramName"]= $value["name"];
				$p_detail[$key]["ProgramDesc"]= $value["description"];
				$p_detail[$key]["Snippet"]= $value["snippet"];
				$p_detail[$key]["DateChanged"]= '/Date('.$value["updated_at"]->timestamp.'000)/';
				// $p_detail[$key]["DateChanged"]= '/Date(1489726980000)/';
				$p_detail[$key]["DefaultWeeks"]= $value["defaultWeeks"];
				$p_detail[$key]["DayPattern"]= $value["dayPattern"];
				$p_detail[$key]["Gender"]= $value["p_gender"];
				$p_detail[$key]["ExperienceLow"]= $value["experienceLow"];
				$p_detail[$key]["ExperienceMedium"]= $value["experienceMedium"];
				$p_detail[$key]["ExperienceHigh"]= $value["experienceHigh"];
				$p_detail[$key]["Image"]= $value["image"];
			}
			$response['Plans'] = $p_detail;
		}
		return json_encode($response);		
	}
	 

	public function CreateProgramCopy(Request $request) {
		$businessId = Auth::user()->business_id;
	    $clientId = Auth::user()->account_id;
	    $p_id = \Request::get('fixedProgramId');

	    $response = array();
	    $response['MessageId'] = 0;
		$plan_details = AbPlans::with('workouts')->find($p_id);	

		if($plan_details){
        	$c_program = array();
			$c_program['businessId'] = $businessId;
			$c_program['plan_id'] = $plan_details['plan_id'];
			$c_program['plan_type'] = $plan_details['plan_type'];
			$c_program['plan_unique_id'] = $plan_details['plan_unique_id'];
			$c_program['name'] = $plan_details['name'];
			$c_program['description'] = $plan_details['description'];
			$c_program['snippet'] = $plan_details['snippet'];
			$c_program['defaultWeeks'] = $plan_details['defaultWeeks'];
			$c_program['created_by'] = $clientId;
			$c_program['created_at'] = Carbon::now();
			$c_program['updated_at'] = Carbon::now();
			// DB::enableQueryLog();
			if($pro_id = ActivityPlans::insertGetId($c_program)){
				// $program_detail = ActivityPlans::find($pro_id);
				$p_detail = array();
				$p_detail["FixedProgramId"]= $pro_id;
				$p_detail["ProgramName"]= $plan_details["name"];
				$p_detail["ProgramDesc"]= $plan_details["description"];
				$p_detail["Gender"]= $plan_details["p_gender"];
				$p_detail["ExperienceLow"]= $plan_details["experienceLow"];
				$p_detail["ExperienceMedium"]= $plan_details["experienceMedium"];
				$p_detail["ExperienceHigh"]= $plan_details["experienceHigh"];
				$p_detail["DefaultWeeks"]= $plan_details["defaultWeeks"];
				$p_detail["Image"]= $plan_details["image"];
				$p_detail["DayPattern"]= $plan_details["dayPattern"];
				$p_detail["IsRepeatedForWeeks"]= $plan_details["isRepeatedForWeeks"];
				$p_detail["IsPaidProgram"]= $plan_details["isPaidProgram"];
				$p_detail["IsPlatformProgram"]= $plan_details["isPlatformProgram"];
				$p_detail["Snippet"]= $plan_details["snippet"];
				$p_detail["PersonID"]= $plan_details["created_by"];
				$p_detail["DateChanged"]= $plan_details["updated_at"]->timestamp;

				$response['Program'] = $p_detail;
				$exer_details  = [];

				foreach($plan_details['workouts'] as $ex_k => $ex_v){
					// dd($ex_v['id']);

					$plan_work = array();
					$plan_work['business_id'] = $businessId;
					$plan_work['plan_id'] = $pro_id;
					$plan_work['workout_id'] = $ex_v['id'];
					$plan_work['created_at'] = Carbon::now();
					$plan_work['updated_at'] = Carbon::now();
					// dd($plan_work);
					if($plan_work_id = AbPlanWorkout::insertGetId($plan_work)){
						$exer_details = AbWorkoutExercise::with('exercises')->where('workout_id',$ex_v['pivot']['id'])->get();
					
						if($exer_details){
							foreach ($exer_details as $key_ex => $value_ex) {
								// dd($value_ex['weekIndex']);
								$exercise_plan = array();
								$exercise_plan['business_id'] = $businessId;
								$exercise_plan['workout_id'] = $plan_work_id;
								$exercise_plan['exercise_id'] = $value_ex['exercise_id'];
								$exercise_plan['WeekIndex'] = $value_ex['weekIndex'];
								$exercise_plan['DayIndex'] = $value_ex['dayIndex'];
								$exercise_plan['Sets'] = $value_ex['sets'];
								$exercise_plan['Priority'] = $value_ex['priority'];
								$exercise_plan['RepOrSeconds'] = $value_ex['repOrSeconds'];
								$exercise_plan['Resistance'] = $value_ex['resistance'];
								$exercise_plan['RestSeconds'] = $value_ex['restSeconds'];
								$exercise_plan['TempoDesc'] = $value_ex['tempoDesc'];
								$exercise_plan['TempoTiming'] = $value_ex['tempoTiming'];
								$exercise_plan['EstimatedTime'] = $value_ex['estimatedTime'];
								$exercise_plan['created_at'] = Carbon::now();
								$exercise_plan['updated_at'] = Carbon::now();
								// dd($exercise_plan);
								$work_exercise_id = AbWorkoutExercise::insertGetId($exercise_plan);
								/*if($work_exercise_id = AbWorkoutExercise::insertGetId($exercise_plan)){

								}*/
							}
						}
					}
				}
			}
        }
		return json_encode($response);
    }



	/*http://api.onesportevent.com/DevApi/CustomPlan/CreateProgram?jsoncallback=jQuery1113007200950278247542_1489728196422&name=q&description=&snippet=&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489728196429
	*/

	public function CreateProgram(Request $request) {
		$businessId = Auth::user()->business_id;
	    $clientId = Auth::user()->account_id;	
	    $unique_id  = substr(md5(uniqid(mt_rand(), true)), 0, 8);

	    $response = array();
	    $response['MessageId'] = 0;

		$c_program = array();
		$c_program['businessId'] = $businessId;
		$c_program['plan_unique_id'] = $unique_id;
		$c_program['plan_id'] = 7;
		$c_program['plan_type'] = 3;
		$c_program['p_gender'] = 2;
		$c_program['name'] = \Request::get('name');
		$c_program['description'] = \Request::get('description');
		$c_program['snippet'] = \Request::get('snippet');
		$c_program['defaultWeeks'] = 12;
		$c_program['created_by'] = $clientId;
		$c_program['created_at'] = Carbon::now();
		$c_program['updated_at'] = Carbon::now();

		// DB::enableQueryLog();
		if($pro_id = ActivityPlans::insertGetId($c_program)){
		// dd(DB::getQueryLog());
			$program_detail = ActivityPlans::find($pro_id);
			// dd($program_detail);
			$p_detail = array();
			$p_detail["FixedProgramId"]= $program_detail["id"];
			$p_detail["ProgramName"]= $program_detail["name"];
			$p_detail["ProgramDesc"]= $program_detail["description"];
			$p_detail["Gender"]= $program_detail["p_gender"];
			$p_detail["ExperienceLow"]= $program_detail["experienceLow"];
			$p_detail["ExperienceMedium"]= $program_detail["experienceMedium"];
			$p_detail["ExperienceHigh"]= $program_detail["experienceHigh"];
			$p_detail["DefaultWeeks"]= $program_detail["defaultWeeks"];
			$p_detail["Image"]= $program_detail["image"];
			$p_detail["DayPattern"]= $program_detail["dayPattern"];
			$p_detail["IsRepeatedForWeeks"]= $program_detail["isRepeatedForWeeks"];
			$p_detail["IsPaidProgram"]= $program_detail["isPaidProgram"];
			$p_detail["IsPlatformProgram"]= $program_detail["isPlatformProgram"];
			$p_detail["Snippet"]= $program_detail["snippet"];
			$p_detail["PersonID"]= $program_detail["created_by"];
			$p_detail["DateChanged"]= $program_detail["updated_at"]->timestamp;

			$response['Program'] = $p_detail;

		}
		return json_encode($response);
    }
    


	/*http://api.onesportevent.com/DevApi/CustomPlan/GetUsersPlanDetail?jsoncallback=jQuery1113007200950278247542_1489728196422&fixedProgramId=468&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489728196434*/

	public function GetUsersPlanDetail(Request $request) {
		$response = array();
        $p_id = \Request::get('fixedProgramId');
        $plan_details = AbPlans::with('workouts')->find($p_id);	
		
		if($plan_details){
        	$workout_list = array();
			$workout_detail = array();
        	$response['MessageId'] = 0;
        	foreach($plan_details['workouts'] as $WEkey => $WEvalue){
				$exer_details = AbWorkoutExercise::with('exercises')->where('workout_id',$WEvalue['pivot']['id'])->get();
				if($exer_details){
					foreach ($exer_details as $ex_k => $ex_value) {
						$workout_detail["WeekIndex"]= $ex_value['weekIndex'];
						$workout_detail["DayIndex"]= $ex_value['dayIndex'];
						$workout_detail["WorkOut"]= $WEvalue['name'];
						$workout_detail["Priority"]= $ex_value['priority'];
						$workout_detail["ExerciseTypeID"]= $ex_value['exercise_id'];
						$workout_detail["ExerciseDesc"]= $ex_value['exercises']['exerciseDesc'];
						$workout_detail["Sets"]= $ex_value['sets'];
						$workout_detail["RepOrSeconds"]= $ex_value['repOrSeconds'];
						$workout_detail["TempoDesc"]= $ex_value['tempoDesc'];
						$workout_detail["TempoTiming"]= $ex_value['tempoTiming'];
						$workout_detail["RestSeconds"]= $ex_value['restSeconds'];
						$workout_detail["IsReps"]= $ex_value['exercises']['isReps'];
						$workout_detail["HasWeight"]= $ex_value['exercises']['hasWeight'];
						$workout_detail["FixedProgramExerciseID"]= $ex_value['id'];
						$workout_detail["FixedProgramID"]= $p_id;
						$workout_detail["EstimatedTime"]= $ex_value['estimatedTime'];
						$workout_detail["Resistance"]= $ex_value['resistance'];
						$workout_detail["Image"]= array(
							"ResourceName" => "test4.jpg",
							"ResourceTypeCD"=> "I"
						);
						$workout_list[] = $workout_detail;
					}
				}	
			}
			$response['Exercises'] = $workout_list;
        }
		return json_encode($response);
    }




	/*http://api.onesportevent.com/DevApi/CustomPlan/UpdateProgram?jsoncallback=jQuery1113007200950278247542_1489728196422&name=aaaaaaaa&description=des&snippet=&fixedProgramId=460&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489728196437
	*/ 

	public function UpdateProgram(Request $request) {
		$up_array = array();
		$response = array();

		$response['MessageId'] = 0;
		$p_name = \Request::get('name');
		$id = \Request::get('fixedProgramId');
		$up_array['name'] =	$p_name;
		$up_array['description'] =	\Request::get('description');
		$up_array['snippet'] =	\Request::get('snippet');

		if((!empty($id)) && (!empty($p_name))){
			if(ActivityPlans::where('id', $id)->update($up_array)){
				$response['MessageDesc'] = "Program Updated";
			}
		}
		return json_encode($response);
		/*return '{
			"MessageId": 0,
			"MessageDesc": "Program Updated"
		}';*/
    }
    


    /*
	http://api.onesportevent.com/DevApi/CustomPlan/UpdateExercise?jsoncallback=jQuery1113007200950278247542_1489728196422&jsonExercise=%7B%22FixedProgramExerciseID%22%3A5461%2C%22FixedProgramID%22%3A460%2C%22ExerciseTypeID%22%3A150%2C%22WorkOut%22%3A%22warm+up%22%2C%22Sets%22%3A%221%22%2C%22RepOrSeconds%22%3A%2230%22%2C%22Resistance%22%3A%22%22%2C%22RestSeconds%22%3A%2260%22%2C%22TempoTiming%22%3A%22%22%2C%22EstimatedTime%22%3A%2290%22%2C%22WeekIndex%22%3A1%2C%22DayIndex%22%3A1%2C%22Priority%22%3A1%2C%22TempoDesc%22%3A%22%22%7D&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489728196438
    */

	public function UpdateExercise(Request $request) {
		$get_val = json_decode(\Request::get('jsonExercise'));
		$businessId = Auth::user()->business_id;
		$response = array();
		
		$ex_id = $get_val->FixedProgramExerciseID;
		$exercise_up = array();
		$exercise_up['WeekIndex'] = $get_val->WeekIndex;
		$exercise_up['DayIndex'] = $get_val->DayIndex;
		$exercise_up['Sets'] = $get_val->Sets;
		$exercise_up['Priority'] = $get_val->Priority;
		$exercise_up['RepOrSeconds'] = $get_val->RepOrSeconds;
		$exercise_up['Resistance'] = $get_val->Resistance;
		$exercise_up['RestSeconds'] = $get_val->RestSeconds;
		$exercise_up['TempoDesc'] = $get_val->TempoDesc;
		$exercise_up['TempoTiming'] = $get_val->TempoTiming;
		$exercise_up['EstimatedTime'] = $get_val->EstimatedTime;
		// $exercise_up['created_at'] = Carbon::now();
		$exercise_up['updated_at'] = Carbon::now();	

		if($work_exercise_id = AbWorkoutExercise::where('id',$ex_id)->update($exercise_up)){
			$where['businessId'] = $businessId;
			$where['exerciseTypeID'] = $get_val->ExerciseTypeID;
			// DB::enableQueryLog();
			$exercises = Exercise::with('resources','favourite')->where($where)->get();
			$workout_detail = AbWorkout::where('name',$get_val->WorkOut)->get();	
			// dd(DB::getQueryLog());
			$workout_id =$workout_detail[0]['id'];
			// dd($exercises[0]['exerciseDesc']);
			
			$response["MessageId"] = 0;
			$response["Message"] = "Updated exercise";
			$response["UpdatedExercise"] = array(
				"FixedProgramExerciseID"=> $ex_id,
				"FixedProgramID"=> $get_val->FixedProgramID,
				"WeekIndex"=> $get_val->WeekIndex,
				"DayIndex"=> $get_val->DayIndex,
				"WorkOut"=> $get_val->WorkOut,
				"Exercise"=> "",
				"Sets"=> $get_val->Sets,
				"Priority"=> $get_val->Priority,
				"RepOrSeconds"=> $get_val->RepOrSeconds,
				"Resistance"=> $get_val->Resistance,
				"RestSeconds"=> $get_val->RestSeconds,
				"TempoDesc"=> $get_val->TempoDesc,
				"TempoTiming"=> $get_val->TempoTiming,
				"EstimatedTime"=> $get_val->EstimatedTime,
				"ExerciseTypeID"=> $get_val->ExerciseTypeID,
				"ExerciseType"=> array(
					"ExerciseCat"=> [],
					"ExerciseEquipment"=> [],
					"ExerciseResources"=> 
					[
						[				
							"ExerciseResourceID"=> 104,
							"ExerciseTypeID"=> $get_val->ExerciseTypeID,
							"ResourceName"=> "test4.jpg",
							"ResourceTypeCD"=> "I"
						]
					],
					"MuscleAreas"=> [],
					"PersonFavourites"=> [
						[
							"PersonID"=> 1,
							"ExerciseTypeId"=> $get_val->ExerciseTypeID
						],
						[
							"PersonID"=> 47849,
							"ExerciseTypeId"=> $get_val->ExerciseTypeID
						],
						[
							"PersonID"=> 48060,
							"ExerciseTypeId"=> $get_val->ExerciseTypeID
						]
					],
					"ExerciseTypeID"=> $get_val->ExerciseTypeID,
					"ActivityTypeID"=> $workout_id,
					"ExerciseDesc"=> $exercises[0]['exerciseDesc'],
					"EstimatedMETS"=> $exercises[0]['estimatedMETS'],
					"IsReps"=> $exercises[0]['isReps'],
					"HasWeight"=> $exercises[0]['hasWeight'],
					"Explanation"=> $exercises[0]['explanation'],
					"DifficultyLevel"=> $exercises[0]['hasWeight'],
					"ProgressionLevel"=> 0,
					"ExerciseGroupID"=> $exercises[0]['exerciseGroupID']
				),
			"DayOfWeekIndex"=> 0
			);
		}
		return json_encode($response);
    }
    
	
	/*
	http://api.onesportevent.com/DevApi/CustomPlan/RemoveExerciseFromProgram?jsoncallback=jQuery1113007200950278247542_1489728196422&fixedProgramExerciseID=5461&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489728196440
	*/


	public function RemoveExerciseFromProgram(Request $request) {
        $businessId = Auth::user()->business_id;
        $clientId = Auth::user()->account_id;
        $id = \Request::get('fixedProgramExerciseID');
        $ex_array['id'] = $id;
        
        if(AbWorkoutExercise::where($ex_array)->delete()){
        	return '{
				"MessageId": 0,
				"Message": "Removed exercise",
				"Removed": '.$id.'
			}';	
        }     
    }


    public function RemoveWorkoutWithExercise(Request $request) { 
        $plan_id = \Request::get('fixedProgramExerciseID');
		$prog_details = AbPlans::with('workouts')->find($plan_id);
		$plan_unique_id = $prog_details['plan_unique_id'];

		$program_detail = ActivityPlans::
		select(DB::raw('max(id) as id,plan_unique_id,name,description,snippet,updated_at,defaultWeeks,defaultWeeks,dayPattern,p_gender,experienceLow,experienceMedium,experienceHigh,image'))
		->groupBy('plan_unique_id')
		->where('plan_unique_id',$plan_unique_id)
		->orderBy('id', 'desc')
		->orderBy('id', 'desc')
		->get();

		$plan_id = $program_detail[0]['id'];
        $businessId = Auth::user()->business_id;
        // $clientId = Auth::user()->account_id;
        $workout = \Request::get('workout');
        $workout_detail = AbWorkout::where('name',$workout)->get();	
		$workout_id =$workout_detail[0]['id'];

		$where['business_id'] = $businessId;
		$where['plan_id'] = $plan_id;
		$where['workout_id'] = $workout_id;

		// DB::enableQueryLog();
		$work_ex_id = AbPlanWorkout::where($where)->first();
		// dd($work_ex_id);
		if ($work_ex_id = AbPlanWorkout::where($where)->first()) {
		   $w_id = $work_ex_id->id;
			DB::transaction(function() use ($w_id) {
				AbWorkoutExercise::where('workout_id',$w_id)->delete();
				AbPlanWorkout::where('id',$w_id)->delete();
				return '{
					"MessageId": 0,
					"Message": "Removed workout",
					"Removed": '.$w_id.'
				}';	
			});
		}
		return 'no record found';
    }



    /*
	http://api.onesportevent.com/DevApi/CustomPlan/AddExerciseToProgram?jsoncallback=jQuery1113007200950278247542_1489728196422&jsonExercise=%7B%22FixedProgramID%22%3A460%2C%22WorkOut%22%3A%22warm+up%22%2C%22ExerciseTypeID%22%3A%22150%22%2C%22WeekIndex%22%3A1%2C%22DayIndex%22%3A1%2C%22Sets%22%3A1%2C%22Priority%22%3A1%2C%22RepOrSeconds%22%3A30%2C%22Resistance%22%3A%22%22%2C%22RestSeconds%22%3A60%2C%22TempoDesc%22%3A%22%22%2C%22TempoTiming%22%3A%22%22%2C%22EstimatedTime%22%3A60%7D&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489728196443
    */

	public function AddExerciseToProgram(Request $request) {   
		$get_val = json_decode(\Request::get('jsonExercise'));
		$businessId = Auth::user()->business_id;
		$response = array();

		$workout_detail = AbWorkout::where('name',$get_val->WorkOut)->get();	
		$workout_id =$workout_detail[0]['id'];

		//$FixedProgramId = \Request::get('FixedProgramId');
		$FixedProgramId = $get_val->FixedProgramID;
		$prog_details = AbPlans::with('workouts')->find($get_val->FixedProgramID);

		$plan_unique_id = $prog_details['plan_unique_id'];
		$program_detail = ActivityPlans::
	    select(DB::raw('max(id) as id,plan_unique_id,name,description,snippet,updated_at,defaultWeeks,defaultWeeks,dayPattern,p_gender,experienceLow,experienceMedium,experienceHigh,image'))
	    ->groupBy('plan_unique_id')
	    ->where('plan_unique_id',$plan_unique_id)
	    ->orderBy('id', 'desc')
	    ->get();

	    $get_val->FixedProgramID = $program_detail[0]['id'];
		$p_w_were = array();
		$p_w_were['business_id'] = $businessId;
		$p_w_were['plan_id'] = $get_val->FixedProgramID;
		$p_w_were['workout_id'] = $workout_id;
		
		// DB::enableQueryLog();
		$plan_work_detail = AbPlanWorkout::where($p_w_were)->get();
		// dd(DB::getQueryLog());
		if(count($plan_work_detail) > 0){
			$plan_work_id = $plan_work_detail[0]['id'];
		}
		else{
			// dd('insert');
			$plan_work = array();
			$plan_work['business_id'] = $businessId;
			$plan_work['plan_id'] = $get_val->FixedProgramID;
			$plan_work['workout_id'] = $workout_id;
			$plan_work['created_at'] = Carbon::now();
			$plan_work['updated_at'] = Carbon::now();

			$plan_work_id = AbPlanWorkout::insertGetId($plan_work);
		}
		
		if($plan_work_id){
			$exercise_plan = array();
			$exercise_plan['business_id'] = $businessId;
			$exercise_plan['workout_id'] = $plan_work_id;
			$exercise_plan['exercise_id'] = $get_val->ExerciseTypeID;
			$exercise_plan['WeekIndex'] = $get_val->WeekIndex;
			$exercise_plan['DayIndex'] = $get_val->DayIndex;
			$exercise_plan['Sets'] = $get_val->Sets;
			$exercise_plan['Priority'] = $get_val->Priority;
			$exercise_plan['RepOrSeconds'] = $get_val->RepOrSeconds;
			$exercise_plan['Resistance'] = $get_val->Resistance;
			$exercise_plan['RestSeconds'] = $get_val->RestSeconds;
			$exercise_plan['TempoDesc'] = $get_val->TempoDesc;
			$exercise_plan['TempoTiming'] = $get_val->TempoTiming;
			$exercise_plan['EstimatedTime'] = $get_val->EstimatedTime;
			$exercise_plan['created_at'] = Carbon::now();
			$exercise_plan['updated_at'] = Carbon::now();
			if($work_exercise_id = AbWorkoutExercise::insertGetId($exercise_plan)){
			    $where['businessId'] = $businessId;
			    $where['exerciseTypeID'] = $get_val->ExerciseTypeID;

		        // DB::enableQueryLog();
				$exercises = Exercise::with('resources','favourite')->where($where)->get();
				// dd($exercises[0]['exerciseDesc']);
				// dd($exercises);
				$response["MessageId"] = 0;
				$response["Message"] = "Added exercise";
				$response["NewExercise"] = array(
					"FixedProgramExerciseID"=> $work_exercise_id,
					"FixedProgramID"=> $get_val->FixedProgramID,
					"WeekIndex"=> $get_val->WeekIndex,
					"DayIndex"=> $get_val->DayIndex,
					"WorkOut"=> $get_val->WorkOut,
					"Exercise"=> "",
					"Sets"=> $get_val->Sets,
					"Priority"=> $get_val->Priority,
					"RepOrSeconds"=> $get_val->RepOrSeconds,
					"Resistance"=> $get_val->Resistance,
					"RestSeconds"=> $get_val->RestSeconds,
					"TempoDesc"=> $get_val->TempoDesc,
					"TempoTiming"=> $get_val->TempoTiming,
					"EstimatedTime"=> $get_val->EstimatedTime,
					"ExerciseTypeID"=> $get_val->ExerciseTypeID,
					"ExerciseType"=> array(
						"ExerciseCat"=> [],
						"ExerciseEquipment"=> [],
						"ExerciseResources"=> 
						[
							[				
								"ExerciseResourceID"=> 104,
								"ExerciseTypeID"=> $get_val->ExerciseTypeID,
								"ResourceName"=> "test4.jpg",
								"ResourceTypeCD"=> "I"
							]
						]
						,
						"MuscleAreas"=> [],
						"PersonFavourites"=> [
							[
								"PersonID"=> 1,
								"ExerciseTypeId"=> $get_val->ExerciseTypeID
							],
							[
								"PersonID"=> 47849,
								"ExerciseTypeId"=> $get_val->ExerciseTypeID
							],
							[
								"PersonID"=> 48060,
								"ExerciseTypeId"=> $get_val->ExerciseTypeID
							]
						],
						"ExerciseTypeID"=> $get_val->ExerciseTypeID,
						"ActivityTypeID"=> $workout_id,
						"ExerciseDesc"=> $exercises[0]['exerciseDesc'],
						"EstimatedMETS"=> $exercises[0]['estimatedMETS'],
						"IsReps"=> $exercises[0]['isReps'],
						"HasWeight"=> $exercises[0]['hasWeight'],
						"Explanation"=> $exercises[0]['explanation'],
						"DifficultyLevel"=> $exercises[0]['hasWeight'],
						"ProgressionLevel"=> 0,
						"ExerciseGroupID"=> $exercises[0]['exerciseGroupID']
					),
				"DayOfWeekIndex"=> 0
				);
			}
		}
		return json_encode($response);
	}
	 


    /*
	http://api.onesportevent.com/DevApi/CustomPlan/RemoveFavExercise?jsoncallback=jQuery1113007200950278247542_1489728196422&exerciseTypeId=150&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489728196445

    */


	public function RemoveFavExercise(Request $request) {
		$businessId = Auth::user()->business_id;
        $clientId = Auth::user()->account_id;

        $fav_arr['client_id'] = $clientId;
        $fav_arr['business_id'] = $businessId;
        $fav_arr['exercise_id'] = \Request::get('exerciseTypeId');
        // $fav_arr['created_at'] = Carbon::now();
        // $fav_arr['updated_at'] = Carbon::now();
        // DB::enableQueryLog();
        if(AbFavorateExercise::where($fav_arr)->delete()){	
        	// dd(DB::getQueryLog());
        	return '{
				"MessageId": 0,
				"Message": "Removed favroute"
			}';
        }
        return 'No Favorate found';
    }
    


    /*
	http://api.onesportevent.com/DevApi/CustomPlan/AddFavExercise?jsoncallback=jQuery1113007200950278247542_1489728196422&exerciseTypeId=69&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489728196448
    */

	public function AddFavExercise(Request $request) {
		$businessId = Auth::user()->business_id;
        $clientId = Auth::user()->account_id;
        
        $fav_arr['client_id'] = $clientId;
        $fav_arr['business_id'] = $businessId;
        $fav_arr['exercise_id'] = \Request::get('exerciseTypeId');
        $fav_arr['created_at'] = Carbon::now();
        $fav_arr['updated_at'] = Carbon::now();
        
        if($fav_id = AbFavorateExercise::insertGetId($fav_arr)){
        	return '{
				"MessageId": 0,
				"Message": "Added favroute"
			}';
        }  
    }
    

    /*
	http://api.onesportevent.com/DevApi/CustomPlan/SearchExercises?jsoncallback=jQuery111308211281927041928_1489731563022&keyWords=&category=&equipment=&ability=&bodypart=&myFavourites=false&perPage=10&pageNumber=1&email=tester%40epicstride.com&SessionGuid=d95a16d3-3a54-4598-a55a-af4abc086a29&_=1489731563033
    */

	public function SearchExercises(Request $request) {
		$response = array();
		$excer_list = array();

		$businessId = Auth::user()->business_id;
        // $clientId = Auth::user()->account_id;
        $myfav = \Request::get('myFavourites');
		$keyWords = \Request::get('keyWords');

		$category = \Request::get('category');
		$equipment = \Request::get('equipment');
		$ability = \Request::get('ability');
		$bodypart = \Request::get('bodypart');
		$movement_type = \Request::get('movement_type');
		$movement_pattern = \Request::get('movement_pattern');
		$pageNumber = (!empty(\Request::get('pageNumber'))) ? \Request::get('pageNumber'):1;
		$perPage = (!empty(\Request::get('perPage'))) ? \Request::get('perPage'):10;

		$where['businessId'] = $businessId;
        // DB::enableQueryLog();
		$query = Exercise::with('resources','favourite','exeimages')->where($where);	
		if($myfav == 'true'){	
			$query->whereHas('favourite',function($q) { $q->where('ab_favourite_exercise.deleted_at',null); });	
		}
		if(!empty($keyWords)){
			$query->where('exerciseDesc', 'like', '%'.$keyWords.'%');
		}

		if(!empty($category)){
			$query->where('category', $category);
		}

		if(!empty($equipment)){
			$query->where('equipment', $equipment);
		}

		if(!empty($ability)){
			$query->where('ability', $ability);
		}

		if(!empty($bodypart)){
			$query->where('bodypart', $bodypart);
		}

		if(!empty($movement_type)){
			$query->where('movement_type', $movement_type);
		}

		if(!empty($movement_pattern)){
			$query->where('movement_pattern', $movement_pattern);
		}

		$query->limit($perPage)->offset(($pageNumber-1)*$perPage);
		$exercises = $query->get();
		// dd(DB::getQueryLog());
		// dd($exercises);
		if(count($exercises) > 0){
			$response['MessageId'] = 0;
			foreach ($exercises as $key => $value) {
				$excer_list[$key]["ExerciseDesc"] = $value["exerciseDesc"];
				$excer_list[$key]["EstimatedMETS"] = $value["estimatedMETS"];
				$excer_list[$key]["HasWeight"] = $value["hasWeight"];
				$excer_list[$key]["IsReps"] = $value["isReps"];
				$excer_list[$key]["ExerciseTypeID"] = $value["exerciseTypeID"];
				$excer_list[$key]["ExerciseGroupID"] = $value["exerciseGroupID"];
				$excer_list[$key]["DifficultyLevel"] = $value["difficultyLevel"];
				$excer_list[$key]["IsFav"] = (is_null($value->favourite)) ? false:true;
				$resource_arr = array();
				if(count($value->resources) > 0 ){
					foreach ($value->resources as $r_key => $r_value) {
						$resource_arr[$r_key]["ExerciseResourceID"] = $r_value["id"];
						$resource_arr[$r_key]["ResourceName"] = $r_value["resourceName"];
						$resource_arr[$r_key]["ResourceTypeCD"] = $r_value["ResourceTypeCd"];
					}
					$excer_list[$key]["Resources"] = $resource_arr;	
				}
				$img=[];
				if(count($value->exeimages) > 0){
					$i=0;
					foreach ($value->exeimages as $image) {
						$img[] = $image->aei_image_name;
						if(!$i)
							$excer_list[$key]["img"] = $image->aei_image_name;
						$i++;
					}
					$excer_list[$key]["Image"] = $img;
				}
			}
			$response['Exercises'] = $excer_list;
		}
		// dd($response);
		return json_encode($response);
    }

}
