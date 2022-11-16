<?php
namespace App\Http\Controllers\Result\Activity;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ActivityBuilderTrait;
use Session;
use App\Parq;
use App\Benchmarks;
use App\PersonalMeasurement;
use App\Clients;
use Carbon\Carbon;
use App\AbClientPlan;
use App\CalendarSetting;
use App\AbClientExercises;
use App\AbClientExerciseSet;
use App\AbPlanWorkoutExercise;
use App\ClientMenu;
use App\AbWorkout;
use App\MpMealCategory;
use App\AbClientPlanProgram;
use Redirect;
use App\AbClientPlanDate;
use App\AbClientPlanWorkout;
use App\AbExerciseVideo;
use App\ClientPlanPhase;
use App\Ldc;
use App\PlanMultiPhaseWorkout;
use App\PlanMultiPhaseWorkoutExercise;
use App\PlanMultiPhaseWorkoutExerciseSet;

class CalendarController extends Controller{
    use ActivityBuilderTrait;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $clientSelectedMenus = [];
        if(Auth::user()->account_type == 'Client') {
            $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
 
            if(!in_array('activity_calendar', $clientSelectedMenus))
              Redirect::to('access-restricted')->send();
        }    
    }
    
    public function show(){
        $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        if(isset($selectedMenus) && !in_array('activity_calendar', explode(',', $selectedMenus))){
            return redirect('access-restricted');
        }
        $client_id = Auth::user()->account_id;
        $parq = Parq::whereHas('client', function($query){
                    $query->where('business_id', Session::get('businessId'));
                  })
                  ->where('client_id', $client_id)
                  ->first();
        $calendar_settings = CalendarSetting::where('cs_business_id',Session::get('businessId'))->whereIn('cs_client_id',array(0,$client_id))->orderBy('id', 'DESC')->first()->toArray();

        $exerciseData = $this->getExercisesOptions();
        $abWorkouts = ["" => "--Select--"]+AbWorkout::pluck('desc','id')->toArray();
        $mealsCategoryArr = MpMealCategory::pluck('id', 'name')->toArray();
        $plans = AbClientPlan::where('clientId',$client_id)->where('status','complete')->where(function($q){
            $q->where('dayOption','2')
            ->orWhere('start_date', '0000-00-00');
        })->get();
        $index = 0;
        foreach($plans as $plan)
        {
            $data[$index]['id'] = $plan->id;
            $data[$index]['title'] = $plan->name;
            $data[$index]['dayOption'] = $plan->dayOption;
            $data[$index]['noOfDaysInWeek'] = $plan->noOfDaysInWeek;
            $data[$index]['start_date'] = $plan->start_date;
            $data[$index]['planType'] = $plan->plan_type;
            $data[$index]['weeksToExercise'] = $plan->weeksToExercise;
            $phaseData = $plan->clientPlanPhases;
            $datas = [];
            if(count($phaseData)){
                foreach ($phaseData as $value) {
                    $datas[$value->phase_no][$value->week_no][$value->day_no] = [
                        'day_no' => $value->day_no
                    ];
                }
            }
            $data[$index]['phaseData'] = json_encode($datas);
            $index++;
        }
      
        return view('Result.activity.calendar', compact('calendar_settings','exerciseData','parq','abWorkouts','mealsCategoryArr','data'));
    }


    /** 
     * Get datewise plan
     * @param
     * @return response
    **/
    public function getPlanDateWise(Request $request){
        if($request->has('clientId')){
            $clientId = $request->clientId;
        }else{
            $clientId = Auth::user()->account_id;
        }
        $evnts = [];
        $where = array(
                'clientId' => $clientId,
                'businessId' => Session::get('businessId'),
                'status' =>'complete'
                );
        $clientData = Clients::where('id',$clientId)->where('ldc_status',1)->first();
        if(count($clientData)){
            if($clientData->ldc_session_id != null){
                $pdf =[];
                $ldcData = Ldc::where('ldc_id',$clientData->ldc_session_id)->first();
                $ldcPdfData = json_decode($ldcData->ldc_pdf);
                foreach($ldcPdfData as $key =>$value){
                    $pdf[] = [
                        'fileName' => $value->file_name,
                        'startDate' => $value->pdfStartDate,
                        'originalName'=>$value->original_name
                    ];
                }
            }
        }
        $plans = AbClientPlan::with('plandates')->where($where)->get();
        $index = 0;
        foreach ($plans as $plan) {
            foreach ($plan->plandates as $value) {
                $evnts[$index]['type'] = 'client-plan';
                $evnts[$index]['id'] = $plan->id;
                $evnts[$index]['title'] = $plan->name;
                $evnts[$index]['dateid'] = $value->id;
                $evnts[$index]['startDatetime'] = $value->plan_start_date;
                $evnts[$index]['endDatetime'] = $value->plan_end_date;
                $index++;
            }
        }
        foreach($pdf as $ldc){
            $evnts[$index]['type'] = 'client-pdf';
            $evnts[$index]['title'] = $ldc['originalName'];
            $evnts[$index]['startDatetime'] =Carbon::parse($ldc['startDate'])->format('Y-m-d H:i:s');
            $evnts[$index]['pdf'] =$ldc['fileName'];
            $evnts[$index]['endDatetime'] = Carbon::parse($ldc['startDate'])->addHours(1)->format('Y-m-d H:i:s');
            $index++;
        }
        return json_encode($evnts);
    }



    /** 
     * Get datewise plan detail
     * @param clint plan id, clint plan date id
     * @return response
    **/
    public function getPlanDetailDateWise(Request $request){
        $response = array();
        $workout_data = array();
        $isActivityVideo = 0;
        $isTypeVideo = 0; 
        $businessId =  Session::get('businessId');
        $clientId = Auth::user()->account_id;
        $clientPlanId = (int)$request->clientPlanId;
        $eventDateId = (int)$request->eventDateId;

        $where = ['business_id'=>$businessId, 'client_id'=>$clientId, 'client_plan_id' => $clientPlanId, 'date_id' => $eventDateId];
        $planDate = AbClientPlanDate::where('id',$eventDateId)->first();
        if($planDate->program_id != 0){
            $clientPlan = AbClientPlanProgram::where('id',$planDate->program_id)->first();
            $isTypeVideo = $clientPlan->is_video;
            $clientExe = AbClientExercises::with('workout','exercise')->where($where)->orderBy('workout_order','asc')->orderBy('exe_order','asc')->get();
            if($clientExe->count()){
                $abClientPlanWorkouts = PlanMultiPhaseWorkout::with('workout')->where('plan_program_id',$planDate->program_id)->orderBy('order_no','asc')->get();
              
                $checkData = array();
                $exercise_data = array();
                foreach ($clientExe as $key => $value) {
                    $name = $value->workout->name;
                    if($value->type == AbClientExercises::EXERCISE){
                        $exercise_data['Name'] = count($value->exercise)?$value->exercise->name:'Rest';
                        $exercise_data['ExeId'] = count($value->exercise)?$value->exercise->id:0;
                        $exercise_data['EstimatedTime'] = $value->estimatedTime;
                        $exercise_data['VideoUrl'] = AbExerciseVideo::where('aei_exercise_id',$value->exercise->id)->where('type','1')->pluck('aei_video_name')->first();
                        $exercise_data['exercise_sets'] = isset($value->exerciseSets)?$value->exerciseSets->toArray():array();
                         $exercise_data['exe_order'] = $value->exe_order;
                    }else{
                        $exercise_data['Name'] = $value->actvityVideo->title;
                        $exercise_data['ExeId'] = $value->actvityVideo->id;
                        $exercise_data['VideoUrl'] = $value->actvityVideo->video;
                        $exercise_data['EstimatedTime'] = $value->actvityVideo->video_duration;
                        $exercise_data['MovementData'] = count($value->actvityVideo->videoMovements)?$value->actvityVideo->videoMovements()->select('name','time')->get()->toArray():[];
                    }
                    $exercise_data['Type'] = $value->type;
                    $exercise_data['Sets'] = $value->sets;
                    $exercise_data['Repetition'] = $value->repetition;
                    $exercise_data['Resistance'] = $value->resistance;
                    $exercise_data['TempoDesc'] = $value->tempoDesc;
                    $exercise_data['RestSeconds'] = $value->restSeconds;
                    $exercise_data['isRest'] = $value->is_rest;
                    $exercise_data['ClientExeId'] = $value->id;

                    if(in_array($name, $checkData)){
                        $index = count($workout_data[$name]);
                        $workout_data[$name][$index] = $exercise_data;
                    }
                    else{
                        $index = 0;
                        $checkData[] = $name;
                        $workout_data[$name][$index] = $exercise_data;
                    }   
                }
            }
            else{
               $clientPlan = AbClientPlanProgram::with(['workouts' => function ($q) {
                                $q->orderBy('order_no', 'asc');
                            }])->find($planDate->program_id);
               $abClientPlanWorkouts = PlanMultiPhaseWorkout::with('workout')->where('plan_program_id',$planDate->program_id)->orderBy('order_no','asc')->get();  
                if($clientPlan){
                    $checkData = array();
                    $exercise_data = array();
                    foreach ($clientPlan['workouts'] as $workoutKey => $workout) {
                        $name = $workout['name'];
                        $exercises = PlanMultiPhaseWorkoutExercise::with('exercies')->where('plan_multi_phase_workout_id',$workout['pivot']['id'])->orderBy('exe_order','asc')->first();
                        if($exercises->type == AbPlanWorkoutExercise::EXERCISE){
                            if($exercises->exercies || $exercises->is_rest){
                                $exercise_data['Name'] = count($exercises->exercies)?$exercises->exercies->name:'Rest';
                                $exercise_data['ExeId'] = count($exercises->exercies)?$exercises->exercies->id:0;
                                $exercise_data['EstimatedTime'] = $exercises->estimatedTime;
                                $exercise_data['VideoUrl'] = AbExerciseVideo::where('aei_exercise_id',$exercises->exercies->id)->where('type','1')->pluck('aei_video_name')->first();
                                $exercise_data['Type'] = $exercises->type;
                                $exercise_data['Sets'] = $exercises->sets;
                                $exercise_data['Repetition'] = $exercises->repetition;
                                $exercise_data['Resistance'] = $exercises->resistance;
                                $exercise_data['TempoDesc'] = $exercises->tempoDesc;
                                $exercise_data['RestSeconds'] = $exercises->restSeconds;
                                $exercise_data['exe_order'] = $exercises->exe_order;
                                $exercise_data['isRest'] = $exercises->is_rest;
        
                                $isertedData['sets'] = $exercises->sets;
                                $isertedData['repetition'] = $exercises->repetition;
                                $isertedData['estimatedTime'] = $exercises->estimatedTime;
                                $isertedData['resistance'] = $exercises->resistance;
                                $isertedData['tempoDesc'] = $exercises->tempoDesc;
                                $isertedData['restSeconds'] = $exercises->restSeconds;
                                $isertedData['client_id'] = $clientId;
                                $isertedData['business_id'] = $businessId;
                                $isertedData['date_id'] = $eventDateId;
                                $isertedData['client_plan_id'] = $clientPlanId;
                                $isertedData['exercise_id'] = $exercise_data['ExeId'];
                                $isertedData['workout_id'] = $workout['id'];
                                $isertedData['status'] = 'incomplete';
                                $isertedData['type'] = $exercises->type;
                                $isertedData['workout_order'] = $workout['pivot']['order_no'];
                                $isertedData['exe_order'] = $exercises->exe_order;
                                $isertedData['is_rest'] = $exercises->is_rest;
                                $exercise_data['ClientExeId'] = AbClientExercises::insertGetId($isertedData);
    
                                foreach($exercises->exerciseSets as $sets){
                                    $data = [
                                        'ab_client_exercise_id' => $exercise_data['ClientExeId'],
                                        'sets' => $sets->sets,
                                        'repetition' => $sets->repetition,
                                        'resistance' => $sets->resistance,
                                        'restSeconds' => $sets->restSeconds,
                                        'tempoDesc' => $sets->tempoDesc,
                                        'estimatedTime' => $sets->estimatedTime
                                    ];
                                    AbClientExerciseSet::create($data);
                                }
                                $clientExerciseNew = AbClientExercises::find($exercise_data['ClientExeId']);
                                $exercise_data['exercise_sets'] = isset($clientExerciseNew->exerciseSets)?$clientExerciseNew->exerciseSets->toArray():array();
                                if(in_array($name, $checkData)){
                                    $index = count($workout_data[$name]);;
                                    $workout_data[$name][$index] = $exercise_data;
                                }
                                else{
                                    $index = 0;
                                    $checkData[] = $name;
                                    $workout_data[$name][$index] = $exercise_data;
                                }
                            }
                        }elseif($exercises->type == AbPlanWorkoutExercise::VIDEO){
                            if($exercises->actvityVideo || $exercises->is_rest){
                                $exercise_data['Name'] = count($exercises->actvityVideo)?$exercises->actvityVideo->title:'Rest';
                                $exercise_data['ExeId'] = count($exercises->actvityVideo)?$exercises->actvityVideo->id:0;
                                $exercise_data['VideoUrl'] = $exercises->actvityVideo->video;
                                $exercise_data['EstimatedTime'] = $exercises->actvityVideo->video_duration;
                                $exercise_data['MovementData'] = count($exercises->actvityVideo->videoMovements)?$exercises->actvityVideo->videoMovements()->select('name','time')->get()->toArray():[];
                                $exercise_data['Type'] = $exercises->type;
                                $exercise_data['Sets'] = $exercises->sets;
                                $exercise_data['Repetition'] = $exercises->repetition;
                                $exercise_data['Resistance'] = $exercises->resistance;
                                $exercise_data['TempoDesc'] = $exercises->tempoDesc;
                                $exercise_data['RestSeconds'] = $exercises->restSeconds;
                                $exercise_data['isRest'] = $exercises->is_rest;
        
                                $isertedData['sets'] = $exercises->sets;
                                $isertedData['repetition'] = $exercises->repetition;
                                $isertedData['estimatedTime'] = $exercises->estimatedTime;
                                $isertedData['resistance'] = $exercises->resistance;
                                $isertedData['tempoDesc'] = $exercises->tempoDesc;
                                $isertedData['restSeconds'] = $exercises->restSeconds;
                                $isertedData['client_id'] = $clientId;
                                $isertedData['business_id'] = $businessId;
                                $isertedData['date_id'] = $eventDateId;
                                $isertedData['client_plan_id'] = $clientPlanId;
                                $isertedData['exercise_id'] = $exercise_data['ExeId'];
                                $isertedData['workout_id'] = $workout['id'];
                                $isertedData['status'] = 'incomplete';
                                $isertedData['type'] = $exercises->type;
                                $insertedData['is_rest'] = $exercises->is_rest;
        
                                $exercise_data['ClientExeId'] = AbClientExercises::insertGetId($isertedData);
        
                                if(in_array($name, $checkData)){
                                    $index = count($workout_data[$name]);;
                                    $workout_data[$name][$index] = $exercise_data;
                                }
                                else{
                                    $index = 0;
                                    $checkData[] = $name;
                                    $workout_data[$name][$index] = $exercise_data;
                                }
                            }
                        }
                    }
                }
            }

         
            if(count($workout_data) || count($videoData)){
                $sortedWorkoutData = array();
                foreach($workout_data as $key => $data){
                    usort($data, function($a,$b){
                        return $a['exe_order'] - $b['exe_order'];
                    });
                    $sortedWorkoutData[$key] = $data;
                }
                $response['Exercise'] = $sortedWorkoutData;
                $response['isActivityVideo'] = $isActivityVideo;
                if(isset($noOfWeek)){
                    $response['noOfWeek'] = $noOfWeek;
                }
                $response['isVideoExercise'] = $isTypeVideo;
                if($isActivityVideo){
                    $response['activityVideo'] = $videoData;
                }
                if(count($abClientPlanWorkouts)){
                    foreach($abClientPlanWorkouts as $workout){
                        $response['workoutData'][] = [
                            'name' => $workout->workout->name,
                            'order' => $workout->order_no
                        ];
                    }  
                }
                $response['workoutData'] = array_map("unserialize", array_unique(array_map("serialize", $response['workoutData'])));
                $keys = array_column($response['workoutData'], 'order');
                array_multisort($keys, SORT_DESC, $response['workoutData']);
                $response['Status'] ='success';
            }
            else{
                $response['Status'] ='error';
            }
        }else{
            $clientPlan = AbClientPlan::where('id',$clientPlanId)->first();
            $isTypeVideo = $clientPlan->is_video;
            $clientExe = AbClientExercises::with('workout','exercise')->where($where)->orderBy('workout_order','asc')->orderBy('exe_order','asc')->get();
            if($clientExe->count()){
                $abClientPlanWorkouts = AbClientPlanWorkout::with('workout')->where('client_plan_id',$clientPlanId)->orderBy('order','asc')->get();
              
                $checkData = array();
                $exercise_data = array();
                foreach ($clientExe as $key => $value) {
                    $name = $value->workout->name;
                    if($value->type == AbClientExercises::EXERCISE){
                        $exercise_data['Name'] = count($value->exercise)?$value->exercise->name:'Rest';
                        $exercise_data['ExeId'] = count($value->exercise)?$value->exercise->id:0;
                        $exercise_data['EstimatedTime'] = $value->estimatedTime;
                        $exercise_data['VideoUrl'] = AbExerciseVideo::where('aei_exercise_id',$value->exercise->id)->where('type','1')->pluck('aei_video_name')->first();
                        $exercise_data['exercise_sets'] = isset($value->exerciseSets)?$value->exerciseSets->toArray():array();
                         $exercise_data['exe_order'] = $value->exe_order;
                    }else{
                        $exercise_data['Name'] = $value->actvityVideo->title;
                        $exercise_data['ExeId'] = $value->actvityVideo->id;
                        $exercise_data['VideoUrl'] = $value->actvityVideo->video;
                        $exercise_data['EstimatedTime'] = $value->actvityVideo->video_duration;
                        $exercise_data['MovementData'] = count($value->actvityVideo->videoMovements)?$value->actvityVideo->videoMovements()->select('name','time')->get()->toArray():[];
                    }
                    $exercise_data['Type'] = $value->type;
                    $exercise_data['Sets'] = $value->sets;
                    $exercise_data['Repetition'] = $value->repetition;
                    $exercise_data['Resistance'] = $value->resistance;
                    $exercise_data['TempoDesc'] = $value->tempoDesc;
                    $exercise_data['RestSeconds'] = $value->restSeconds;
                    $exercise_data['isRest'] = $value->is_rest;
                    $exercise_data['ClientExeId'] = $value->id;

                    if(in_array($name, $checkData)){
                        $index = count($workout_data[$name]);
                        $workout_data[$name][$index] = $exercise_data;
                    }
                    else{
                        $index = 0;
                        $checkData[] = $name;
                        $workout_data[$name][$index] = $exercise_data;
                    }   
                }
            }
            else{
               $clientPlan = AbClientPlan::with(['workouts' => function ($q) {
                                $q->orderBy('order', 'asc');
                            }])->find($clientPlanId);
               $abClientPlanWorkouts = AbClientPlanWorkout::with('workout')->where('client_plan_id',$clientPlanId)->orderBy('order','asc')->get();
                $noOfWeek = $clientPlan->weeksToExercise;   
                if($clientPlan){
                    if($clientPlan->plan_type == 8){
                        $videoData = [];
                        $isActivityVideo = 1;
                        $videoData = [
                            'Name' => $clientPlan->name,
                            'video' => $clientPlan->activityVideo->video
                        ]; 
                    }else{
                        $checkData = array();
                        $exercise_data = array();
                        foreach ($clientPlan['workouts'] as $workoutKey => $workout) {
                            $name = $workout['name'];
                            $exercises = AbPlanWorkoutExercise::with('exercies')->where('client_plan_workout',$workout['pivot']['id'])->orderBy('exe_order','asc')->first();
                            if($exercises->type == AbPlanWorkoutExercise::EXERCISE){
                                if($exercises->exercies || $exercises->is_rest){
                                    $exercise_data['Name'] = count($exercises->exercies)?$exercises->exercies->name:'Rest';
                                    $exercise_data['ExeId'] = count($exercises->exercies)?$exercises->exercies->id:0;
                                    $exercise_data['EstimatedTime'] = $exercises->estimatedTime;
                                    $exercise_data['VideoUrl'] = AbExerciseVideo::where('aei_exercise_id',$exercises->exercies->id)->where('type','1')->pluck('aei_video_name')->first();
                                    $exercise_data['Type'] = $exercises->type;
                                    $exercise_data['Sets'] = $exercises->sets;
                                    $exercise_data['Repetition'] = $exercises->repetition;
                                    $exercise_data['Resistance'] = $exercises->resistance;
                                    $exercise_data['TempoDesc'] = $exercises->tempoDesc;
                                    $exercise_data['RestSeconds'] = $exercises->restSeconds;
                                    $exercise_data['exe_order'] = $exercises->exe_order;
                                    $exercise_data['isRest'] = $exercises->is_rest;
            
                                    $isertedData['sets'] = $exercises->sets;
                                    $isertedData['repetition'] = $exercises->repetition;
                                    $isertedData['estimatedTime'] = $exercises->estimatedTime;
                                    $isertedData['resistance'] = $exercises->resistance;
                                    $isertedData['tempoDesc'] = $exercises->tempoDesc;
                                    $isertedData['restSeconds'] = $exercises->restSeconds;
                                    $isertedData['client_id'] = $clientId;
                                    $isertedData['business_id'] = $businessId;
                                    $isertedData['date_id'] = $eventDateId;
                                    $isertedData['client_plan_id'] = $clientPlanId;
                                    $isertedData['exercise_id'] = $exercise_data['ExeId'];
                                    $isertedData['workout_id'] = $workout['id'];
                                    $isertedData['status'] = 'incomplete';
                                    $isertedData['type'] = $exercises->type;
                                    $isertedData['workout_order'] = $workout['pivot']['order'];
                                    $isertedData['exe_order'] = $exercises->exe_order;
                                    $isertedData['is_rest'] = $exercises->is_rest;
                                    $exercise_data['ClientExeId'] = AbClientExercises::insertGetId($isertedData);
        
                                    foreach($exercises->exerciseSets as $sets){
                                        $data = [
                                            'ab_client_exercise_id' => $exercise_data['ClientExeId'],
                                            'sets' => $sets->sets,
                                            'repetition' => $sets->repetition,
                                            'resistance' => $sets->resistance,
                                            'restSeconds' => $sets->restSeconds,
                                            'tempoDesc' => $sets->tempoDesc,
                                            'estimatedTime' => $sets->estimatedTime
                                        ];
                                        AbClientExerciseSet::create($data);
                                    }
                                    $clientExerciseNew = AbClientExercises::find($exercise_data['ClientExeId']);
                                    $exercise_data['exercise_sets'] = isset($clientExerciseNew->exerciseSets)?$clientExerciseNew->exerciseSets->toArray():array();
                                    if(in_array($name, $checkData)){
                                        $index = count($workout_data[$name]);;
                                        $workout_data[$name][$index] = $exercise_data;
                                    }
                                    else{
                                        $index = 0;
                                        $checkData[] = $name;
                                        $workout_data[$name][$index] = $exercise_data;
                                    }
                                }
                            }elseif($exercises->type == AbPlanWorkoutExercise::VIDEO){
                                if($exercises->actvityVideo || $exercises->is_rest){
                                    $exercise_data['Name'] = count($exercises->actvityVideo)?$exercises->actvityVideo->title:'Rest';
                                    $exercise_data['ExeId'] = count($exercises->actvityVideo)?$exercises->actvityVideo->id:0;
                                    $exercise_data['VideoUrl'] = $exercises->actvityVideo->video;
                                    $exercise_data['EstimatedTime'] = $exercises->actvityVideo->video_duration;
                                    $exercise_data['MovementData'] = count($exercises->actvityVideo->videoMovements)?$exercises->actvityVideo->videoMovements()->select('name','time')->get()->toArray():[];
                                    $exercise_data['Type'] = $exercises->type;
                                    $exercise_data['Sets'] = $exercises->sets;
                                    $exercise_data['Repetition'] = $exercises->repetition;
                                    $exercise_data['Resistance'] = $exercises->resistance;
                                    $exercise_data['TempoDesc'] = $exercises->tempoDesc;
                                    $exercise_data['RestSeconds'] = $exercises->restSeconds;
                                    $exercise_data['isRest'] = $exercises->is_rest;
            
                                    $isertedData['sets'] = $exercises->sets;
                                    $isertedData['repetition'] = $exercises->repetition;
                                    $isertedData['estimatedTime'] = $exercises->estimatedTime;
                                    $isertedData['resistance'] = $exercises->resistance;
                                    $isertedData['tempoDesc'] = $exercises->tempoDesc;
                                    $isertedData['restSeconds'] = $exercises->restSeconds;
                                    $isertedData['client_id'] = $clientId;
                                    $isertedData['business_id'] = $businessId;
                                    $isertedData['date_id'] = $eventDateId;
                                    $isertedData['client_plan_id'] = $clientPlanId;
                                    $isertedData['exercise_id'] = $exercise_data['ExeId'];
                                    $isertedData['workout_id'] = $workout['id'];
                                    $isertedData['status'] = 'incomplete';
                                    $isertedData['type'] = $exercises->type;
                                    $insertedData['is_rest'] = $exercises->is_rest;
            
                                    $exercise_data['ClientExeId'] = AbClientExercises::insertGetId($isertedData);
            
                                    if(in_array($name, $checkData)){
                                        $index = count($workout_data[$name]);;
                                        $workout_data[$name][$index] = $exercise_data;
                                    }
                                    else{
                                        $index = 0;
                                        $checkData[] = $name;
                                        $workout_data[$name][$index] = $exercise_data;
                                    }
                                }
                            }
                        }       
                    }
                }
            }

         
            if(count($workout_data) || count($videoData)){
                $sortedWorkoutData = array();
                foreach($workout_data as $key => $data){
                    usort($data, function($a,$b){
                        return $a['exe_order'] - $b['exe_order'];
                    });
                    $sortedWorkoutData[$key] = $data;
                }
                $response['Exercise'] = $sortedWorkoutData;
                $response['isActivityVideo'] = $isActivityVideo;
                if(isset($noOfWeek)){
                    $response['noOfWeek'] = $noOfWeek;
                }
                $response['isVideoExercise'] = $isTypeVideo;
                if($isActivityVideo){
                    $response['activityVideo'] = $videoData;
                }
                if(count($abClientPlanWorkouts)){
                    foreach($abClientPlanWorkouts as $workout){
                        $response['workoutData'][] = [
                            'name' => $workout->workout->name,
                            'order' => $workout->order
                        ];
                    }  
                }
                $response['workoutData'] = array_map("unserialize", array_unique(array_map("serialize", $response['workoutData'])));
                $keys = array_column($response['workoutData'], 'order');
                array_multisort($keys, SORT_DESC, $response['workoutData']);
                $response['Status'] ='success';
            }
            else{
                $response['Status'] ='error';
            }
        }

        return json_encode($response);
    }


    /**
     * Client plan save from calendar
     * @param
     * @return
    **/
    public function clientPlanEdit(Request $request){
        $response['status'] = 'error';
        $storeData = array();
        $timestamp = Carbon::now();
        $data = $request->all();
        ksort($data);
        $updatedData = array();
        if(count($data)){
            $status = $data['status'];
            foreach ($data as $key => $value) {
                if($key != 'status'){
                    $setData = [
                        'sets' => $value['exercSets'],
                        'repetition' => $value['exercReps'],
                        'resistance' => $value['exercResist'],
                        'restSeconds' => $value['exercRest'],
                        'tempoDesc' => $value['exercTempo'],
                        'estimatedTime' => $value['exercDur']
                    ];
                    if(isset($value['clientExeSetId']) && $value['clientExeSetId'] != ''){
                        AbClientExerciseSet::where('id',$value['clientExeSetId'])->update($setData);
                    }else{
                        $setData['ab_client_exercise_id'] = $value['clientExeId'];
                        AbClientExerciseSet::create($setData);
                    }
                    AbClientExercises::where('id', $value['clientExeSetId'])->update(['status' =>$status,'created_at'=>$timestamp,'updated_at'=>$timestamp]); 
                }
            }
        }
        $response['status'] ='success';
        return json_encode($response);
    } 


    /** 
     * Add exercise 
     * @param
     * @return
    **/
    public function addExercise(Request $request){
        $response['status'] = 'error';
        $timestamp = Carbon::now();
        $where = array('client_id'=>Auth::user()->account_id, 'client_plan_id'=>(int)$request->plan_id,'date_id'=>(int)$request->date_id, 'workout_id'=>(int)$request->workout_id,'exercise_id'=>(int)$request->exerice_id);

        $exercise = AbClientExercises::where($where)->first();
        if(!count($exercise)){
            $where['business_id'] = Session::get('businessId');
            $where['status'] = 'incomplete';
            if($request->isVideo){
                $where['type'] = 2;
            }
            $where['created_at'] = $timestamp;
            $where['updated_at'] = $timestamp;
            if($id = AbClientExercises::insertGetId($where)){
               $response['status'] = 'success';
               $response['record'] = 'new';
            }
        }
        else{
            $response['status'] = 'success';
            $response['record'] = 'old'; 
        }

        return json_encode($response);
    }


    /**
     * Delete clint plan from calendar
     * @param
     * @return
    **/
    public function clientPlanDelete(Request $request){
        $response['status'] = 'error';
        $id = (int)$request->clientExeId;
        $clientExe = AbClientExercises::findOrFail($id);
        if($clientExe){
            $clientExe->delete();
            $response['status'] = 'success'; 
        }

        return json_encode($response);
    }

    public function removeExercise(Request $request){
        $requestData = $request->all();
        if($requestData['targetEvents'] == 'this'){
            AbClientPlanDate::whereId($requestData['dateId'])->delete();
        }elseif($requestData['targetEvents'] == 'future'){
            $planDate = AbClientPlanDate::whereId($requestData['dateId'])->first();
            $eventDate = $planDate->plan_start_date;
            AbClientPlanDate::where('client_plan_id',$requestData['clientplan_id'])->WhereDate('plan_start_date','>=',$eventDate)->delete();
            // $clientPlan = AbClientPlan::where('id',$requestData['clientplan_id'])->first();
            // $noOfWeek = $clientPlan->weeksToExercise;
            // $date = Carbon::parse($eventDate, 'UTC');
            // AbClientPlanDate::whereId($requestData['dateId'])->delete();
            // for($i=0;$i<$noOfWeek;$i++){
            //     $newDate = $date->addDays(7)->format('Y-m-d'); 
            //     AbClientPlanDate::where('client_plan_id',$requestData['clientplan_id'])->WhereDate('plan_start_date',$newDate)->delete();
            // }
        }
        $responseData = [
            'status' => 'success'
        ];
        return response()->json($responseData);
    }
    public function daysInWeek(Request $request)
    { 
        $id = $request->id;
        $clientPlan = AbClientPlan::where('id',$id)->first();
        $sameDaysAcrossProgram = $request->sameDaysAcrossProgram;
        $weekDayPattern = $request->weekDayPattern;
        if($clientPlan){
            try{
                if($clientPlan->plan_type == 9){
                    $clientPlan->update([
                        'dayOption' => 1
                    ]);
                    $data = $request->data;
                    foreach($data as $item){
                        ClientPlanPhase::where('client_plan_id',$id)->where('phase_no',$item['phase'])->where('week_no',$item['week'])->where('day_no',$item['dayNo'])->update([
                            'day' => $item['day']
                        ]);
                    }
                    $this->activityCalenderMultiPhaseDataInsert($id,$clientPlan->start_date);
                }else{
                    $daysInWeek= $request->days;
                    if($request->has('days')){
                        $weekData['daysOfWeek'] = $request->days;
                    }
                    $weekData['start_date'] = $request->startDate;
                    $weekData['dayOption'] = 1;
                    $weekData['noOfDaysInWeek'] = null;
                    AbClientPlan::where('id',$id)->update($weekData);
                    $program_detail = AbClientPlan::with('workouts')->where('id',$id)->first();
                    if($sameDaysAcrossProgram){
                        $this->activityCalenderDataInsert($id, $program_detail->weeksToExercise, $program_detail->daysOfWeek, $program_detail->estimatedTime,$program_detail->start_date);
                    }else{
                        $insertedData = array();
                        $increase = 0;
                        foreach ($weekDayPattern as $key => $pattern) {
                            $days = str_split($pattern, 1);
                            $time = 60;
                            $totalSelectedDays = 0;
                            foreach($days as $day){
                                if($day == '1'){
                                    $totalSelectedDays = $totalSelectedDays + 1;
                                }
                            }
                            $totalProgamCount = $totalSelectedDays;
                            $count = 1;
                            $timestamp = Carbon::now();
                            $currentDate = $weekData['start_date'];
                            for ($j=0; $j < count($days); $j++) {
                                if($count <= $totalProgamCount) {
                                    if($days[$j] == '1'){
                                        $st = $this->calDateByWeek($key + $increase, $j ,$currentDate);
                                        $et = $this->calDateByWeek($key + $increase, $j ,$currentDate);
                                        if($st < $currentDate){
                                            $st = $this->calDateByWeek($key + 1, $j ,$currentDate);
                                            $et = $this->calDateByWeek($key + 1, $j ,$currentDate);
                                            $increase = 1;
                                        }
                                        $et = $et->addMinutes($time);
                                        $insertedData[] = ['client_plan_id'=>$id, 'plan_start_date'=>$st, 'plan_end_date'=>$et, 'created_at'=>$timestamp, 'updated_at'=>$timestamp];
                                        $count = $count + 1;
                                    }
                                }
                            }
                        }
                        if(count($insertedData)){
                            AbClientPlanDate::where('client_plan_id', $id)->forcedelete();
                            AbClientPlanDate::insert($insertedData);
                        }
                    }
                }
                $responseData = [
                    'status' => 'success'
                ];
            }catch(\Throwable $e){
                $responseData = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        }else{
            $responseData = [
                'status' => 'error'
            ];
        }
        return response()->json($responseData);
    }

    protected function activityCalenderDataInsert($planId, $weeks, $dayPattern, $time,$startDate){
        $days = str_split($dayPattern, 1);
        if($time == 0)
            $time = 60;

        $totalSelectedDays = 0;
        foreach($days as $day){
            if($day == '1'){
                $totalSelectedDays = $totalSelectedDays + 1;
            }
        }
        $totalProgamCount = $weeks * $totalSelectedDays;
        $count = 1;
        if($weeks > 0){
            $insertedData = array();
            $timestamp = Carbon::now();
            $currentDate =$startDate;
            for ($i=0; $i <= $weeks; $i++) {
                for ($j=0; $j < count($days); $j++) {
                    if($count <= $totalProgamCount) {
                        if($days[$j] == '1'){
                            $st = $this->calDateByWeek($i, $j ,$currentDate);
                            $et = $this->calDateByWeek($i, $j ,$currentDate);
                            if($st >= $currentDate){
                                $et = $et->addMinutes($time);
                                $insertedData[] = ['client_plan_id'=>$planId, 'plan_start_date'=>$st, 'plan_end_date'=>$et, 'created_at'=>$timestamp, 'updated_at'=>$timestamp];
                                $count = $count + 1;
                            }
                        }
                    }
                }

                
            }
            if(count($insertedData)){
                AbClientPlanDate::where('client_plan_id', $planId)->forcedelete();
                if(AbClientPlanDate::insert($insertedData))
                    return true;
            }
        }
        return false;
    }

    public function activityCalenderMultiPhaseDataInsert($clientPlanId,$startDate){
        $abClientPlan = AbClientPlan::with('clientPlanPhases')->where('id',$clientPlanId)->first();
        if($abClientPlan->dayOption == 1){
            $clientPlanPhases = $abClientPlan->clientPlanPhases;
            foreach ($abClientPlan->clientPlanPhases as $value) {
                $data[$value->phase_no][$value->week_no][$value->day_no][$value->session_no] = [
                    'is_session_program' => 1,
                    'programId' => $value->program_id,
                    'title' => $value->planProgram->title
                ];
                $data[$value->phase_no][$value->week_no][$value->day_no]['day'] = $value->day;
            }
            $dayArr = [
                'mon' => 0,
                'tue' => 1,
                'wed' => 2,
                'thu' => 3,
                'fri' => 4,
                'sat' => 5,
                'sun' => 6, 
            ];
            $time = 60;
            $date = Carbon::parse($startDate);
            $currentDate = Carbon::now();
            $timestamp = Carbon::now();
            $weekNo = 0;
            foreach($data as $key => $item){
                foreach($item as $week){
                    foreach($week as $day){
                        $date = Carbon::parse($startDate);
                        $date = $date->addWeeks($weekNo);
                        $date = $date->startOfWeek();
                        $date = $date->addDays($dayArr[$day['day']]);
                        $dateEnd = Carbon::parse($startDate);
                        $dateEnd = $dateEnd->addWeeks($weekNo);
                        $dateEnd = $dateEnd->startOfWeek();
                        $dateEnd = $dateEnd->addDays($dayArr[$day['day']]);
                        $dateEnd = $dateEnd->addMinutes($time);
                        if($date >= Carbon::parse($startDate)){
                            foreach($day as $session){
                                if(is_array($session)){
                                    $insertedData[] = ['client_plan_id' => $abClientPlan->id,'program_id' => $session['programId'], 'plan_start_date' => $date, 'plan_end_date' => $dateEnd, 'created_at' => $timestamp, 'updated_at' => $timestamp];
                                }
                            }
                        }else{
                            $weekNo = $weekNo + 1;
                            $date = Carbon::parse($startDate);
                            $date = $date->addWeeks($weekNo);
                            $date = $date->startOfWeek();
                            $date = $date->addDays($dayArr[$day['day']]);
                            $dateEnd = Carbon::parse($startDate);
                            $dateEnd = $dateEnd->addWeeks($weekNo);
                            $dateEnd = $dateEnd->startOfWeek();
                            $dateEnd = $dateEnd->addDays($dayArr[$day['day']]);
                            $dateEnd = $dateEnd->addMinutes($time);
                            foreach($day as $session){
                                if(is_array($session)){
                                    $insertedData[] = ['client_plan_id' => $abClientPlan->id,'program_id' => $session['programId'], 'plan_start_date' => $date, 'plan_end_date' => $dateEnd, 'created_at' => $timestamp, 'updated_at' => $timestamp];
                                }
                            } 
                        }
                    }
                    $weekNo = $weekNo + 1;
                }
            }
            if (count($insertedData)) {
                AbClientPlanDate::where('client_plan_id', $abClientPlan->id)->forcedelete();
                if (AbClientPlanDate::insert($insertedData))
                    return true;
            }else{
                return false;
            }
        }  
    }
    public function calDateByWeek($week, $day ,$currentDate){
        $date = Carbon::parse($currentDate);
        $date = $date->addWeeks($week);
        $date = $date->startOfWeek();
        $date = $date->addDays($day);
        return $date;
    }


}
