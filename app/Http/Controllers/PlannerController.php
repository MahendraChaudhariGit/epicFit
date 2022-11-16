<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Session;
use App\Parq;
use App\AbPlans;
use App\Clients;
use App\Exercise;
use App\Category;
use App\AbWorkout;
use App\AbClientPlan;
use App\AbPlanWorkout;
use App\AbClientPlanDate;
use App\AbWorkoutExercise;
use App\AbFavorateExercise;
use App\AbClientPlanWorkout;
use App\AbClientPlanGenerate;
use App\AbClientPlanProgram;
use App\AbPlanWorkoutExercise;
use App\AbExerciseVideo;
use App\AbPlanWorkoutExerciseSet;
use App\ActivityVideo;
use App\Http\Traits\ActivityBuilderTrait;
use App\ClientMenu;
use App\ClientPlanPhase;
use App\PlanMultiPhaseWorkout;
use App\PlanMultiPhaseWorkoutExercise;
use App\PlanMultiPhaseWorkoutExerciseSet;
use Redirect;
use Throwable;

class PlannerController extends Controller
{
	use ActivityBuilderTrait;

	/**
	 * Instantiate a new UserController instance.
	 */
	public function __construct()
	{
		$clientSelectedMenus = [];
		if (Auth::user()->account_type == 'Client') {
			$selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
			$clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];

			if (!in_array('activity_planner', $clientSelectedMenus))
				Redirect::to('access-restricted')->send();
		}
	}

	/**
	 * Rander fitness tools
	 * @param
	 * @return parq and exercise data
	 **/
	public function show()
	{
		$selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        if(isset($selectedMenus) && !in_array('activity_planner', explode(',', $selectedMenus))){
            return redirect('access-restricted');
        }
		$clientId = Auth::user()->account_id;
		$parq = Parq::whereHas('client', function ($query) {
			$query->where('business_id', Session::get('businessId'));
		})
			->where('client_id', $clientId)
			->first();

		$exerciseData = $this->getExercisesOptions();
		$abWorkouts = ["" => "--Select--"] + AbWorkout::pluck('desc', 'id')->toArray();
		return view('Result.activity.fitness-tool', compact('parq', 'exerciseData', 'abWorkouts'));
	}


	/**
	 * Filter plan from ab_client_plan table
	 * @param filter value
	 * @return all plan data 
	 **/
	public function GetFilterPlan(Request $request)
	{
		$response['status'] = 'error';
		$data = array();
		$where = array('businessId' => Session::get('businessId'));

		$plan_type = (int) $request->plan_type;
		$where['plan_type'] = $plan_type;
		$where['status'] = 'complete';

		if ($request->has('gender'))
			$where['gender'] = (int) $request->gender;

		if ($request->has('ClientId'))
			$where['clientId'] = (int) $request->ClientId;
		else
			$where['clientId'] = 0;

		if ($request->has('habit'))
			$where['habit'] = (int) $request->habit;

		if ($request->has('equipment'))
			$check['equipment'] = (int) $request->equipment;
		if ($request->equipment == 6) {
			$prog_details_list = AbClientPlan::with('workouts')->where($where)->get();
		} else {
			$prog_details_list = AbClientPlan::with('workouts')->where($where)->where($check)->get();
		}
		if ($prog_details_list->count()) {
			foreach ($prog_details_list as $prog_key => $prog_details) {
				$data[$prog_key]["FixedProgramId"] = $prog_details['id'];
				$data[$prog_key]["ProgramName"] = $prog_details['name'];
				$data[$prog_key]["ProgramDesc"] = $prog_details['description'];
				$data[$prog_key]["Gender"] = $prog_details['gender'];
				$data[$prog_key]["ExperienceLow"] = $prog_details['experienceLow'];
				$data[$prog_key]["ExperienceMedium"] = $prog_details['experienceMedium'];
				$data[$prog_key]["ExperienceHigh"] = $prog_details['experienceHigh'];
				$data[$prog_key]["ExperienceRehb"] = $prog_details['experienceRehb'];
				$data[$prog_key]["DefaultWeeks"] = $prog_details['weeksToExercise'];
				$data[$prog_key]["Image"] = $prog_details['image'];
				$data[$prog_key]["DayPattern"] = $prog_details['daysOfWeek'];
				$data[$prog_key]["TimePerWeek"] = $prog_details['timePerWeek'];
				$data[$prog_key]["IsRepeatedForWeeks"] = $prog_details['isRepeatedForWeeks'];
				$data[$prog_key]["IsPaidProgram"] = $prog_details['isPaidProgram'];
				$data[$prog_key]["PersonID"] = 1;
				$data[$prog_key]["DateChanged"] = $prog_details["updated_at"]->timestamp;
				$data[$prog_key]["PlanType"] = $prog_details["plan_type"];
				$data[$prog_key]["dayOption"] = $prog_details["dayOption"];
				$data[$prog_key]['noOfDaysInWeek'] = $prog_details["noOfDaysInWeek"];
			}
			$response["plan"] = $data;
		}

		if (array_key_exists('plan', $response)) {
			$response['status'] = 'success';
		}

		return json_encode($response);
	}


	/** 
	 * get incomplete program
	 * @param client id
	 * @return response
	 **/
	public function GetUsersPlans(Request $request)
	{
		$businessId = Session::get('businessId');
		$clientId = (int) $request->Clientid;
		$where = array('clientId' => $clientId, 'businessId' => $businessId);

		if ($request->has('PlanType'))
			$where['plan_type'] = $request->PlanType;

		$p_detail = array();
		$response = array();
		$response['MessageId'] = 0;

		$program_list = AbClientPlan::where($where)->groupBy('plan_unique_id')->orderBy('id', 'desc')->get();

		if ($program_list->count()) {
			foreach ($program_list as $key => $value) {
				$p_detail[$key]["FixedProgramId"] = $value->id;
				$p_detail[$key]["ProgramName"] = $value->name;
				$p_detail[$key]["ProgramDesc"] = $value->discription;
				$p_detail[$key]["DateChanged"] = '/Date(' . $value->updated_at->timestamp . '000)/';
				$p_detail[$key]["DefaultWeeks"] = $value->defaultWeeks;
				$p_detail[$key]["DayPattern"] = $value->dayPattern;
				$p_detail[$key]["Gender"] = $value->gender;
				$p_detail[$key]["ExperienceLow"] = $value->experienceLow;
				$p_detail[$key]["ExperienceMedium"] = $value->experienceMedium;
				$p_detail[$key]["ExperienceHigh"] = $value->experienceHigh;
				$p_detail[$key]["ExperienceRehb"] = $value->experienceRehb;
				$p_detail[$key]["Image"] = $value->image;
				$p_detail[$key]["daysOfWeek"] = $value->daysOfWeek;
				$p_detail[$key]["dayOption"] = $value->dayOption;
				$p_detail[$key]['weeksToExercise'] = $value->weeksToExercise;
				$p_detail[$key]['noOfDaysInWeek'] = $value->noOfDaysInWeek;
			}
			$response['Plans'] = $p_detail;
		}
		return json_encode($response);
	}


	/** 
	 * Create client plan whith status
	 * client id 0 is library program if not 0 is client plan
	 * @param 
	 * @return
	 **/
	public function CreateProgram(Request $request)
	{
		$businessId = Session::get('businessId');
		$unique_id  = substr(md5(uniqid(mt_rand(), true)), 0, 8);
		$clientId = (int) $request->Clientid;

		if ($clientId == 0) {
			$program['image'] = $request->image;
		}

		$response = array();
		$program = array();
		$response['MessageId'] = 0;

		$program['businessId'] = $businessId;
		$program['clientId'] = $clientId;
		$program['plan_unique_id'] = $unique_id;
		$program['plan_type'] = 7; // FOR PROGRAM DESIGNER ONLY 
		$program['name'] = $request->name;
		$program['gender'] = (int) $request->Gender;
		$program['weeksToExercise'] = 12;
		$program['status'] = 'incomplete';
		$program['getPreWritten'] = 'false';
		$program['created_at'] = Carbon::now();
		$program['updated_at'] = Carbon::now();

		if ($pro_id = AbClientPlan::insertGetId($program)) {
			$p_detail = array();
			$p_detail["FixedProgramId"] = $pro_id;
			$p_detail["ProgramName"] = $program['name'];
			$p_detail["ProgramDesc"] = isset($program['description']) ? $program['description'] : '';
			$p_detail["Gender"] = $program['gender'];
			$p_detail["ExperienceLow"] = isset($program["experienceLow"]) ? $program["experienceLow"] : false;
			$p_detail["ExperienceMedium"] = isset($program["experienceMedium"]) ? $program["experienceMedium"] : false;
			$p_detail["ExperienceHigh"] = isset($program["experienceHigh"]) ? $program["experienceMedium"] : false;
			$p_detail["ExperienceRehab"] = isset($program["experienceRehab"]) ? $program["experienceRehab"] : false;
			$p_detail["DefaultWeeks"] = 12;
			$p_detail["Image"] = isset($program["image"]) ? $program["image"] : '';
			$p_detail["DayPattern"] = '';
			$p_detail["IsRepeatedForWeeks"] = '';
			$p_detail["IsPaidProgram"] = '';
			$p_detail["IsPlatformProgram"] = '';
			$p_detail["Snippet"] = '';
			$p_detail["PersonID"] = $clientId;
			$p_detail["DateChanged"] = $program["updated_at"]->timestamp;

			$response['Program'] = $p_detail;
		}
		return json_encode($response);
	}


	/** 
	 * Update plan
	 * @param plan id
	 * @return response
	 *
	 **/
	public function UpdateProgram(Request $request)
	{
		$up_array = array();
		$response['status'] = "error";
		$id = $request->progId;

		$clientPlan = AbClientPlan::find($id);
		if (count($clientPlan)) {
			$clientPlan->name = $request->progName;
			$clientPlan->discription = $request->progDesc;
			if ($clientPlan->save())
				$response['status'] = "success";
		}
		return json_encode($response);
	}


	/** 
	 * Update plan
	 * @param plan id
	 * @return response
	 *
	 **/
	public function RemoveProgram(Request $request)
	{
		$up_array = array();
		$response['status'] = "error";
		$id = $request->progId;

		if (AbClientPlan::find($id)->delete())
			$response['status'] = "success";

		return json_encode($response);
	}



	/**
	 * Get User plan detail
	 * @param user_plan_id
	 * @return all userplan data in response
	 **/
	public function GetUsersPlanDetail(Request $request)
	{
		$response['status'] = 'error';
		$client_plan_id = $request->fixedProgramId;
		$isMultiphase = $request->has('isMultiPhaseProgram') && $request->isMultiPhaseProgram;
		if($isMultiphase){
			$clientPlan = AbClientPlanProgram::with('workouts')->find($client_plan_id);
		}else{
			$clientPlan = AbClientPlan::with('workouts')->find($client_plan_id);
		}
		if($clientPlan->dayOption == 1){
			$days = str_split($clientPlan->daysOfWeek, 1);
			$response['days'] = $days;
			
		}
		$response['isVideo'] = $clientPlan->is_video;
		$response['daysOfWeek'] = $clientPlan->daysOfWeek;
		$response['dayOption'] = $clientPlan->dayOption;
		$response['weeksToExercise'] = $clientPlan->weeksToExercise;
		$response['startDate'] = $clientPlan->start_date;
		if ($clientPlan) {
			$workout_list = array();
			$workout_detail = array();
		
		foreach ($clientPlan['workouts'] as $WEkey => $WEvalue) {
				if($isMultiphase){
					$exer_details = PlanMultiPhaseWorkoutExercise::with('exercies')->where('plan_multi_phase_workout_id', $WEvalue['pivot']['id'])->orderBy('exe_order', 'ASC')->get();
				}else{
					$exer_details = AbPlanWorkoutExercise::with('exercies')->where('client_plan_workout', $WEvalue['pivot']['id'])->orderBy('exe_order', 'ASC')->get();
				}
				if ($exer_details->count()) {
					foreach ($exer_details as $ex_k => $ex_value) {
						if ($ex_value->type == AbPlanWorkoutExercise::EXERCISE) {
							$thumbnailProgram = '';
							$exeVideo = AbExerciseVideo::where('aei_exercise_id', $ex_value->exercise_id)->where('type', 1)->first();
							if ($exeVideo) {
								$thumbnailProgram = $exeVideo->thumbnail_program;
							}
							$workout_detail['noOfDaysInWeek'] = $clientPlan->noOfDaysInWeek;
							$workout_detail["thumbnail_program"] = $thumbnailProgram;
							$workout_detail["WeekIndex"] = $ex_value->weekIndex;
							$workout_detail["DayIndex"] = $ex_value->dayIndex;
							$workout_detail["WorkOut"] = $WEvalue['name'];
							$workout_detail["Priority"] = 1;
							$workout_detail["ExerciseTypeID"] = $ex_value->exercise_id;
							$workout_detail["ExerciseDesc"] = $ex_value->exercies->exerciseDesc;
							$workout_detail["sub_heading"] = $ex_value->exercies->sub_heading;

							$workout_detail["Name"] = $ex_value->exercies->name;
							$workout_detail["Sets"] = $ex_value->sets;
							$workout_detail["Repetition"] = $ex_value->repetition;
							$workout_detail["Resistance"] = $ex_value->resistance;
							$workout_detail["RepOrSeconds"] = 0;
							$workout_detail["TempoDesc"] = $ex_value->tempoDesc;
							$workout_detail["TempoTiming"] = 0;
							$workout_detail["RestSeconds"] = $ex_value->restSeconds;
							$workout_detail["IsReps"] = $ex_value->exercies->isReps;
							$workout_detail["HasWeight"] = $ex_value->exercies->hasWeight;
							$workout_detail["ExerciseID"] = $ex_value->exercies->id;
							$workout_detail["FixedProgramID"] = $client_plan_id;
							$workout_detail["EditWorkoutId"] = $ex_value->id;
							$workout_detail["EstimatedTime"] = $ex_value->estimatedTime;
							$workout_detail["planType"] = $ex_value->type;
							$workout_detail["isRest"] = $ex_value->is_rest;
							$workout_detail['exercise_sets'] = isset($ex_value->exerciseSets) ? $ex_value->exerciseSets->toArray() : array();
							$workout_detail["Image"] = array(
								"ResourceName" => "test4.jpg",
								"ResourceTypeCD" => "I"
							);
							$workout_detail["PlanType"] = $ex_value->plan_type;
							$workout_detail["order"] = $ex_value->exe_order;
						} else {

							$workout_detail["WeekIndex"] = $ex_value->weekIndex;
							$workout_detail["DayIndex"] = $ex_value->dayIndex;
							$workout_detail["WorkOut"] = $WEvalue['name'];
							$workout_detail["Priority"] = 1;
							$workout_detail["ExerciseTypeID"] = $ex_value->exercise_id;
							$workout_detail["ExerciseDesc"] = '';
							$workout_detail["Name"] = $ex_value->actvityVideo->title;
							$workout_detail["sub_heading"] = $ex_value->exercies->sub_heading;
							$workout_detail["Sets"] = $ex_value->sets;
							$workout_detail["Repetition"] = $ex_value->repetition;
							$workout_detail["Resistance"] = $ex_value->resistance;
							$workout_detail["RepOrSeconds"] = 0;
							$workout_detail["TempoDesc"] = $ex_value->tempoDesc;
							$workout_detail["TempoTiming"] = 0;
							$workout_detail["RestSeconds"] = $ex_value->restSeconds;
							$workout_detail["IsReps"] = $ex_value->exercies->isReps;
							$workout_detail["HasWeight"] = $ex_value->exercies->hasWeight;
							$workout_detail["ExerciseID"] = $ex_value->actvityVideo->id;
							$workout_detail["FixedProgramID"] = $client_plan_id;
							$workout_detail["EditWorkoutId"] = $ex_value->id;
							$workout_detail["EstimatedTime"] = $ex_value->actvityVideo->video_duration;
							$workout_detail["video"] = $ex_value->actvityVideo->video;
							$workout_detail["planType"] = $ex_value->type;
							$workout_detail["thumbnail"] = $ex_value->actvityVideo->thumbnail;
							$workout_detail["isRest"] = $ex_value->is_rest;
						}
						$workout_list[] = $workout_detail;
					}
				}
			}
			$keys = array_column($workout_list, 'order');
			array_multisort($keys, SORT_ASC, $workout_list);
			$response['Exercises'] = $workout_list;
			$response['workoutData'] = [];
			if(count($clientPlan['workouts'])){
				foreach($clientPlan['workouts'] as $workout){
					$response['workoutData'][] = [
						'name' => $workout->name,
						'order' => $workout->pivot->order
					];
				}  
			}
			$response['workoutData'] = array_map("unserialize", array_unique(array_map("serialize", $response['workoutData'])));
			$keys = array_column($response['workoutData'], 'order');
			array_multisort($keys, SORT_DESC, $response['workoutData']);
		}
		if (count($response['Exercises']))
			$response['status'] = 'success';
		return json_encode($response);
	}


	/** 
	 * Edit training segment
	 * @param ab_workout_exercise id
	 * @return response
	 **/
	public function EditTrainingSegment(Request $request)
	{
		$response['status'] = 'error';
		$data = [
			'sets' => $request->exercSets,
			'repetition' => $request->exercReps,
			'resistance' => $request->exercResist,
			'restSeconds' => $request->exercRest,
			'tempoDesc' => $request->exercTempo,
			'estimatedTime' => $request->exercDur
		];
		if ($request->has('workoutExerciseSetId') && isset($request->workoutExerciseSetId)) {
			if ($request->has('planType') && $request->planType == 9) {
				PlanMultiPhaseWorkoutExerciseSet::where('id', $request->workoutExerciseSetId)->update($data);
			} else {
				AbPlanWorkoutExerciseSet::where('id', $request->workoutExerciseSetId)->update($data);
			}
			$response['workoutId'] = $request->workoutExerciseSetId;
		} else {
			if ($request->has('planType') && $request->planType == 9) {
				$data['plan_multi_phase_workout_exercise_id'] = $request->editWorkoutId;
				$workout = PlanMultiPhaseWorkoutExerciseSet::create($data);
			} else {
				$data['ab_plan_workout_exercise_id'] = $request->editWorkoutId;
				$workout = AbPlanWorkoutExerciseSet::create($data);
			}
			$response['workoutId'] = $workout->id;
		}
		$response['status'] = 'success';
		return json_encode($response);
	}

	/**
	 * Delete Training Segemnts Sets
	 */
	public function DeleteTrainingSegment(Request $request)
	{
		if ($request->has('workoutExerciseSetId') && isset($request->workoutExerciseSetId)) {
			AbPlanWorkoutExerciseSet::where('id', $request->workoutExerciseSetId)->delete();
		}
		$response['status'] = 'success';
		return json_encode($response);
	}


	/**
	 * Add exercise to program
	 * @param jsone data
	 * @return response
	 **/
	public function AddExerciseToProgram(Request $request)
	{
		$response["status"] = 'error';
		$isError = true;
		$businessId = Session::get('businessId');
		$workoutName = $request->WorkOutName;
		$clientPlanId = $request->ClientPlanId;
		$exerciseId = $request->ExerciseId;
		$planType = $request->PlanType;
		$isRest = isset($request->isRest) ? $request->isRest : 0;
		$restSeconds = isset($request->restSeconds) ? $request->restSeconds : null;
		$workout_id = 0;
		$response = array();
		$time = Carbon::now();
		$workout_id = AbWorkout::where('name', $workoutName)->pluck('id')->first();
		if ($request->has('ClientPlanType') && $request->ClientPlanType == '9') {
			$insertedData = array('business_id' => $businessId, 'plan_program_id' => $clientPlanId, 'workout_id' => $workout_id, 'created_at' => $time, 'updated_at' => $time);
			if ($plan_work_id = PlanMultiPhaseWorkout::insertGetId($insertedData)) {
				$insertedData2 = array('plan_multi_phase_workout_id' => $plan_work_id, 'exercise_id' => $exerciseId, 'type' => $planType, 'is_rest' => $isRest, 'exe_order' => $request->exeOrder, 'created_at' => $time, 'updated_at' => $time);
				if ($planWorkExerciseId = PlanMultiPhaseWorkoutExercise::insertGetId($insertedData2)) {
					$this->setPlanWorkoutExerciseOrder($request->orderData,$request->ClientPlanType);
					if ($planType == AbPlanWorkoutExercise::EXERCISE) {
						$exercises = Exercise::with('resources', 'favourite')->where('id', $exerciseId)->first();
						AbClientPlanProgram::where('id', $clientPlanId)->update([
							'is_video' => false
						]);
						$isError = false;
					} elseif ($planType == AbPlanWorkoutExercise::VIDEO) {
						$exercises = ActivityVideo::with('workout')->where('id', $exerciseId)->first();
						AbClientPlanProgram::where('id', $clientPlanId)->update([
							'is_video' => true
						]);
						$isError = false;
					}
				}
			}

			$abClientPlan = AbClientPlanProgram::where('id', $clientPlanId)->first();
			$isVideo = $abClientPlan->is_video;

			$thumbnailProgram = '';
			$exeVideo = AbExerciseVideo::where('aei_exercise_id', $exerciseId)->where('type', 1)->first();
			if ($exeVideo) {
				$thumbnailProgram = $exeVideo->thumbnail_program;
			}

			if (!$isError) {
				$response["status"] = 'success';
				$response["planType"] = $planType;
				$response["isVideo"] = $isVideo;
				if ($planType == AbPlanWorkoutExercise::EXERCISE) {
					$response["Exercises"][0] = array(
						"ExerciseID" => $exercises->id,
						"sub_heading" => $exercises->sub_heading,

						"FixedProgramID" => $plan_work_id,
						"EditWorkoutId" => $planWorkExerciseId,
						"WorkOut" => $workoutName,
						"WeekIndex" => '',
						"DayIndex" => '',
						"Exercise" => "",
						"Sets" => '',
						"Priority" => 1,
						"RepOrSeconds" => 1,
						"Resistance" => '',
						"RestSeconds" => '',
						"Repetition" => '',
						"TempoDesc" => '',
						"TempoTiming" => 0,
						"EstimatedTime" => '',
						"thumbnail_program" => $thumbnailProgram,
						"ActivityTypeID" => $workout_id,
						"ExerciseDesc" => $exercises['exerciseDesc'],
						"Name" => $exercises['name'],
						"EstimatedMETS" => $exercises['estimatedMETS'],
						"IsReps" => false,
						"HasWeight" => $exercises['hasWeight'],
						"Explanation" => $exercises['explanation'],
						"DifficultyLevel" => $exercises['hasWeight'],
						"ProgressionLevel" => 0,
						"ExerciseGroupID" => $exercises['exerciseGroupID'],
						'planType' => $planType,
						'isRest' => $isRest,
						'exercise_sets' => isset($exercises->exerciseSets) ? $exercises->exerciseSets->toArray() : array(),
						"Image" => array(
							"ResourceName" => "test4.jpg",
							"ResourceTypeCD" => "I"
						)
					);
				} else if ($planType == AbPlanWorkoutExercise::VIDEO) {
					$response["Exercises"][0] = array(
						"ExerciseID" => $exercises->id,
						"FixedProgramID" => $plan_work_id,
						"EditWorkoutId" => $planWorkExerciseId,
						"WorkOut" => $workoutName,
						"ActivityTypeID" => $workout_id,
						"ExerciseID" => $exercises->id,
						"Name" => $exercises['title'],
						"EstimatedTime" => $exercises->video_duration,
						'workout_type' => $exercises->workout->desc,
						'workout_id' => $exercises->workout_id,
						'planType' => $planType,
						'video' => $exercises->video,
						'thumbnail' => $exercises->thumbnail,
						'isRest' => $isRest,
					);
				}
			}
		} else {
	
			$insertedData = array('business_id' => $businessId, 'client_plan_id' => $clientPlanId, 'workout_id' => $workout_id, 'created_at' => $time, 'updated_at' => $time);
			if ($plan_work_id = AbClientPlanWorkout::insertGetId($insertedData)) {
				$insertedData2 = array('client_plan_workout' => $plan_work_id, 'exercise_id' => $exerciseId, 'type' => $planType, 'is_rest' => $isRest, 'restSeconds' => $restSeconds, 'exe_order' => isset($request->exeOrder)?$request->exeOrder:0, 'created_at' => $time, 'updated_at' => $time);
				if ($planWorkExerciseId = AbPlanWorkoutExercise::insertGetId($insertedData2)) {
					$this->setPlanWorkoutExerciseOrder($request->orderData);
					if ($planType == AbPlanWorkoutExercise::EXERCISE) {
						$exercises = Exercise::with('resources', 'favourite')->where('id', $exerciseId)->first();
						AbClientPlan::where('id', $clientPlanId)->update([
							'is_video' => false
						]);
						$isError = false;
					} elseif ($planType == AbPlanWorkoutExercise::VIDEO) {
						$exercises = ActivityVideo::with('workout')->where('id', $exerciseId)->first();
						AbClientPlan::where('id', $clientPlanId)->update([
							'is_video' => true
						]);
						$isError = false;
					}
				}
			}


			$abClientPlan = AbClientPlan::where('id', $clientPlanId)->first();
			$isVideo = $abClientPlan->is_video;

			$thumbnailProgram = '';
			$exeVideo = AbExerciseVideo::where('aei_exercise_id', $exerciseId)->where('type', 1)->first();
			if ($exeVideo) {
				$thumbnailProgram = $exeVideo->thumbnail_program;
			}

			if (!$isError) {
				$response["status"] = 'success';
				$response["planType"] = $planType;
				$response["isVideo"] = $isVideo;
				if ($planType == AbPlanWorkoutExercise::EXERCISE) {
					$response["Exercises"][0] = array(
						"ExerciseID" => $exercises->id,
						"sub_heading" => $exercises->sub_heading,

						"FixedProgramID" => $plan_work_id,
						"EditWorkoutId" => $planWorkExerciseId,
						"WorkOut" => $workoutName,
						"WeekIndex" => '',
						"DayIndex" => '',
						"Exercise" => "",
						"Sets" => '',
						"Priority" => 1,
						"RepOrSeconds" => 1,
						"Resistance" => '',
						"RestSeconds" => '',
						"Repetition" => '',
						"TempoDesc" => '',
						"TempoTiming" => 0,
						"EstimatedTime" => '',
						"thumbnail_program" => $thumbnailProgram,
						"ActivityTypeID" => $workout_id,
						"ExerciseDesc" => $exercises['exerciseDesc'],
						"Name" => $exercises['name'],
						"EstimatedMETS" => $exercises['estimatedMETS'],
						"IsReps" => false,
						"HasWeight" => $exercises['hasWeight'],
						"Explanation" => $exercises['explanation'],
						"DifficultyLevel" => $exercises['hasWeight'],
						"ProgressionLevel" => 0,
						"ExerciseGroupID" => $exercises['exerciseGroupID'],
						'planType' => $planType,
						'isRest' => $isRest,
						'exercise_sets' => isset($exercises->exerciseSets) ? $exercises->exerciseSets->toArray() : array(),
						"Image" => array(
							"ResourceName" => "test4.jpg",
							"ResourceTypeCD" => "I"
						)
					);
				} else if ($planType == AbPlanWorkoutExercise::VIDEO) {
					$response["Exercises"][0] = array(
						"ExerciseID" => $exercises->id,
						"FixedProgramID" => $plan_work_id,
						"EditWorkoutId" => $planWorkExerciseId,
						"WorkOut" => $workoutName,
						"ActivityTypeID" => $workout_id,
						"ExerciseID" => $exercises->id,
						"Name" => $exercises['title'],
						"EstimatedTime" => $exercises->video_duration,
						'workout_type' => $exercises->workout->desc,
						'workout_id' => $exercises->workout_id,
						'planType' => $planType,
						'video' => $exercises->video,
						'thumbnail' => $exercises->thumbnail,
						'isRest' => $isRest,
					);
				}
			}
		}
		return json_encode($response);
	}


	/** 
	 * Add exercise to favouite
	 * @param Exercise id, clientId
	 * @return response
	 **/
	public function AddFavExercise(Request $request)
	{
		$response['status'] = 'error';
		$businessId = Session::get('businessId');
		$clientId = $request->Clientid;

		$fav_arr['client_id'] = $clientId;
		$fav_arr['business_id'] = $businessId;
		$fav_arr['exercise_id'] = $request->exerciseId;
		$fav_arr['created_at'] = Carbon::now();
		$fav_arr['updated_at'] = Carbon::now();

		if ($fav_id = AbFavorateExercise::insertGetId($fav_arr)) {
			$response['status'] = 'success';
		}
		return json_encode($response);
	}


	/** 
	 * Force Remove exercise to favouite
	 * @param Exercise id, clientId
	 * @return response
	 **/
	public function RemoveFavExercise(Request $request)
	{
		$response['status'] = 'error';

		$where['business_id'] = Session::get('businessId');
		$where['client_id'] = $request->Clientid;
		$where['exercise_id'] = $request->exerciseId;

		if (AbFavorateExercise::where($where)->forcedelete())
			$response['status'] = 'success';

		return json_encode($response);
	}


	/** 
	 * Search exercise with user parameter
	 * @param Input data
	 * @return response exercise
	 **/
	public function SearchExercises(Request $request)
	{
		$response = array();
		$excer_list = array();
		$pageNumber = (int) ((!empty(\Request::get('pageNumber'))) ? \Request::get('pageNumber') : 1);
		$perPage = (int) ((!empty(\Request::get('perPage'))) ? \Request::get('perPage') : 4);


		$query = Exercise::active()->with('resources', 'favourite', 'exeimages', 'workoutExercises')->where('businessId', Session::get('businessId'));

		if ($request->has('workoutId') && $request->workoutId && $request->workoutId != 0) {
			$workoutId = $request->workoutId;
			$query->whereHas('workoutExercises', function ($query) use ($workoutId) {
				$query->where('ab_workout_exercise.workout_id', $workoutId);
			});
		}

		if ($request->has('myFavourites') && $request->myFavourites == 'true')
			$query->whereHas('favourite', function ($q) use ($request) {
				$q->where('ab_favourite_exercise.client_id', $request->clientId)
					->where('ab_favourite_exercise.deleted_at', null);
			});

		if ($request->has('keyWords') && $request->keyWords) {
			$query->where('exerciseDesc', 'like', '%' . $request->keyWords . '%');
			$query->orWhere('name', 'like', '%' . $request->keyWords . '%');
		}

		if ($request->has('category') && $request->category)
			$query->where('exerciseTypeID', $request->category);

		if ($request->has('equipment') && $request->equipment)
			$query->where('equipment', $request->equipment);

		if ($request->has('ability') && $request->ability)
			$query->where('ability', $request->ability);

		if ($request->has('movement_type') && $request->movement_type)
			$query->where('movement_type', $request->movement_type);

		if ($request->has('movement_pattern') && $request->movement_pattern)
			$query->where('movement_pattern', $request->movement_pattern);

		if ($request->has('bodypart') && $request->bodypart) {
			$bodypart = (int) $request->bodypart;
			$query->whereRaw('FIND_IN_SET(?, bodypart)', [$bodypart]);
		}

		//$query->take(($pageNumber * $perPage));
		/*$query->skip(($pageNumber-1)*$perPage)->take($perPage);*/
		//$query->limit($perPage)->offset(($pageNumber-1)*$perPage);
		$exercises = $query->get();

		if (count($exercises)) {

			$response['MessageId'] = 0;
			foreach ($exercises as $key => $value) {
				$excer_list[$key]["id"] = $value["id"];
				$excer_list[$key]["name"] = $value["name"];
				$excer_list[$key]["sub_heading"] = $value["sub_heading"];
				$excer_list[$key]["ExerciseDesc"] = $value["exerciseDesc"];
				$excer_list[$key]["EstimatedMETS"] = $value["estimatedMETS"];
				$excer_list[$key]["HasWeight"] = $value["hasWeight"];
				$excer_list[$key]["IsReps"] = $value["isReps"];
				$excer_list[$key]["ExerciseTypeID"] = $value["exerciseTypeID"];
				$excer_list[$key]["ExerciseGroupID"] = $value["exerciseGroupID"];
				$excer_list[$key]["DifficultyLevel"] = $value["ability"];
				$isFav = false;
				if (count($value->favourite)) {
					foreach ($value->favourite as $data) {
						if ($data->client_id == $request->clientId) {
							$isFav = true;
						}
					}
				}
				$thumbnailProgram = '';
				$exeVideo = AbExerciseVideo::where('aei_exercise_id', $value["id"])->where('type', 1)->first();
				if ($exeVideo) {
					$thumbnailProgram = $exeVideo->thumbnail_program;
				}
				$excer_list[$key]["thumbnailProgram"] = $thumbnailProgram;
				$excer_list[$key]["IsFav"] = $isFav;
				$excer_list[$key]["img"] = $value->exeimages->pluck('aei_image_name')->first();
				$resource_arr = array();
				if (count($value->resources) > 0) {
					foreach ($value->resources as $r_key => $r_value) {
						$resource_arr[$r_key]["ExerciseResourceID"] = $r_value["id"];
						$resource_arr[$r_key]["ResourceName"] = $r_value["resourceName"];
						$resource_arr[$r_key]["ResourceTypeCD"] = $r_value["ResourceTypeCd"];
					}
					$excer_list[$key]["Resources"] = $resource_arr;
				}
			}
			$response['Exercises'] = $excer_list;
		}
		return json_encode($response);
	}

	public function SearchExercisesByKeywords(Request $request)
	{
		$response = array();
		$excer_list = array();
		$query = Exercise::active()->with('resources', 'favourite', 'exeimages', 'workoutExercises')->where('businessId', Session::get('businessId'));
		if ($request->has('keyWords') && $request->keyWords) {
			$query->where('exerciseDesc', 'like', '%' . $request->keyWords . '%');
			$query->orWhere('name', 'like', '%' . $request->keyWords . '%');
		}
		$exercises = $query->get();

		if (count($exercises)) {
			foreach ($exercises as $key => $value) {
				$excer_list[$key]["id"] = $value["id"];
				$excer_list[$key]["name"] = $value["name"];
			}
			$response = $excer_list;
		}
		return json_encode($response);
	}


	/** 
	 * Get exercise with exercise id
	 * @param exercise id
	 * @return response
	 **/
	public function GetExercisesById($id)
	{
		$response = [];
		$response['status'] = 'error';

		$exercise = Exercise::with('resources', 'favourite', 'exeimages')->find($id);
		$programVideo = AbExerciseVideo::where('aei_exercise_id', $id)->where('type', 1)->first();
		$tutorialVideo = AbExerciseVideo::where('aei_exercise_id', $id)->where('type', 0)->first();
		if ($exercise->count()) {
			$img = [];
			if (count($exercise->exeimages)) {
				foreach ($exercise->exeimages as $image) {
					$img[] = $image->aei_image_name;
				}
			}
			$program = null;
			if ($programVideo) {
				$program = $programVideo->aei_video_name;
			}
			$tutorial = null;
			if ($tutorialVideo) {
				$tutorial = $tutorialVideo->aei_video_name;
			}
			$res = [];
			if (count($exercise->resources) > 0) {
				foreach ($exercise->resources as $r_key => $r_value) {
					$res["ExerciseResourceID"] = $r_value["id"];
					$res["ResourceName"] = $r_value["resourceName"];
					$res["ResourceTypeCD"] = $r_value["ResourceTypeCd"];
				}
				$response["Resources"] = $res;
			}


			$response['Fev'] = $exercise->whereHas('favourite', function ($q) {
				$q->where('ab_favourite_exercise.deleted_at', null);
			});

			$response['exercise']['muscles'] = $exercise->muscles;
			$response['exercise']['benifits'] = $exercise->benifits;
			$response['exercise']['cues'] = $exercise->cues;
			$response['exercise']['movement_desc'] = $exercise->movement_desc;
			$response['exercise']['common_mistekes'] = $exercise->common_mistekes;
			$response['exercise']['progress'] = $exercise->progress;
			$response["Image"] = $img ? $img : [];
			$response["Program"] = $program ? $program : '';
			$response["Tutorial"] = $tutorial ? $tutorial : '';

			$response['status'] = 'success';
		}

		return json_encode($response);
	}


	/**
	 * Plan Preview
	 * @param days, weeks
	 * @return Preview data
	 **/
	public function PlanPreview(Request $request)
	{
		$isError = true;
		$response = array();
		$data = array();
		$day_array = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
		$plan_type = (int) $request->PlanType;
		$clientId = (int) $request->client_id;

		if ($plan_type == 5 && $clientId != 0) {
			$timestamp = Carbon::now();
			$businessId = Session::get('businessId');

			$ability = $request->habit;
			$purpose = $request->purpose;
			$equipment = $request->equipment;
			$current = $request->curr_phy_act;
			$previous = $request->prev_phy_act;
			$next = $request->next_phy_act;
			$intensity = $request->curr_intensity_phy_act;

			$query = AbClientPlanGenerate::with('exercise')->where('business_id', $businessId);
			$query->whereHas('exercise', function ($q) use ($ability) {
				$q->where('ability', $ability);
			});
			$query->whereRaw('FIND_IN_SET(?, purpose)', [$purpose]);
			$query->whereRaw('FIND_IN_SET(?, equipment)', [$equipment]);
			$query->whereRaw('FIND_IN_SET(?, curr_phy_act)', [$current]);
			$query->whereRaw('FIND_IN_SET(?, prev_phy_act)', [$previous]);
			$query->whereRaw('FIND_IN_SET(?, next_phy_act)', [$next]);
			$query->whereRaw('FIND_IN_SET(?, curr_intensity_phy_act)', [$intensity]);
			$filterExercises = $query->get();

			if ($filterExercises->count()) {
				$PWhere['plan_type'] = $plan_type;
				$PWhere['businessId'] = $businessId;
				$PWhere['clientId'] = 0;
				$PWhere['gender'] = (int) $request->gender;
				$PWhere['status'] = 'incomplete';

				$clientPlan = AbClientPlan::with('workouts')->where($PWhere)->first();
				if (count($clientPlan)) {
					$newRow = $clientPlan->replicate();
					$newRow->clientId = $clientId;
					$newRow->habit = $request->habit;
					$newRow->status = 'incomplete';
					$newRow->save();
					$newClientPlanId = $newRow->id;

					if (count($clientPlan['workouts'])) {
						$clintPlanWorkout = array();
						$exeId = array();
						$workoutId = array();
						foreach ($filterExercises as $filterExe) {
							$exeId[] = $filterExe->exercise->id;
							$workoutId[] = $filterExe->workout_id;
						}

						foreach ($clientPlan['workouts'] as $workout) {
							$clintPlanWorkout[] = $workout['pivot']['id'];
						}

						$plan_works = AbClientPlanWorkout::where(['business_id' => $businessId, 'client_plan_id' => $clientPlan->id])->whereIn('workout_id', $workoutId)->get();
						if ($plan_works->count()) {
							foreach ($plan_works as  $plan_work) {
								$newWork = $plan_work->replicate();
								$newWork->client_plan_id = $newClientPlanId;
								$newWork->save();
								$plan_work_id[] = $newWork->id;
							}
						}

						$planWorkExe = AbPlanWorkoutExercise::whereIn('client_plan_workout', $clintPlanWorkout)
							->whereIn('exercise_id', $exeId)
							->get();

						if ($planWorkExe->count()) {
							$i = 0;
							foreach ($planWorkExe as $workoutExe) {
								$newPlanWExe = $workoutExe->replicate();
								$newPlanWExe->client_plan_workout = $plan_work_id[$i];
								$newPlanWExe->save();
								$ClientPlanId = $newClientPlanId;
								$i++;
							}
						}
					}
				}
			}
		} else {
			//when plan type 7 and 6
			$ClientPlanId = (int) $request->ClientPlanId;
			$workoutId = $request->workoutId;
			$order = $request->Order;
			foreach ($order as $value) {
				$client_workouts = AbClientPlanWorkout::where(['client_plan_id' => $ClientPlanId])->where('workout_id', $value['workout_id'])->update([
					'order' => $value['order']
				]);
			}
			$orderExercise = $request->OrderExercise;
			$this->setPlanWorkoutExerciseOrder($orderExercise);
		}

		if (isset($ClientPlanId)) {
			$client_plan = AbClientPlan::with('workouts')->where('id', $ClientPlanId)->first();
			if (count($client_plan)) {
				$client_plan->weeksToExercise = (int) $request->WeeksToExercise;
				if ($request->dayOption == 1) {
					$client_plan->daysOfWeek = $request->DaysOfWeek;
					$client_plan->noOfDaysInWeek = null;
					$client_plan->dayOption = 1;
				} else {
					$client_plan->daysOfWeek = '0000000';
					$client_plan->noOfDaysInWeek = $request->daysInWeek;
					$client_plan->dayOption = 2;
				}
				if ($request->selectedStartDate != '') {
					$client_plan->start_date = $request->selectedStartDate;
				} else {
					$client_plan->start_date = 0;
				}
				if ($request->has('Method'))
					$client_plan->method = $request->Method;
				if ($request->has('TimePerWeek'))
					$client_plan->timePerWeek = (int) $request->TimePerWeek;
				if ($request->has('Height'))
					$client_plan->height = (int) $request->Height;
				if ($request->has('Weight'))
					$client_plan->weight = (int) $request->Weight;
				if ($request->has('Age'))
					$client_plan->age = (int) $request->Age;
				if ($request->has('TimePerWeek'))
					$client_plan->timePerWeek = (int) $request->TimePerWeek;
				if ($client_plan->save()) {

					if ($client_plan->dayOption == 1) {
						$needle = "1";
						$lastPos = 0;
						$positions = array();
						while (($lastPos = strpos($client_plan->daysOfWeek, $needle, $lastPos)) !== false) {
							$positions[] = $lastPos;
							$lastPos = $lastPos + strlen($needle);
						}
						$selected_days = array_intersect_key($day_array, array_flip($positions));
						if (count($selected_days)) {
							foreach ($selected_days as $day) {
								$checkData = [];
								$workout_data = [];
								foreach ($client_plan['workouts'] as $workoutKey => $workout) {
									$exercise_data = [];
									$name = $workout['desc'];
									$exercises = AbPlanWorkoutExercise::with('exercies')->where('client_plan_workout', $workout['pivot']['id'])->first();
									if (count($exercises)) {
										if ($exercises->type == AbPlanWorkoutExercise::EXERCISE) {
											$exercise_data['Name'] = count($exercises->exercies) ? $exercises->exercies->name : "REST";
											$exercise_data['sets'] = $exercises->sets;
											$exercise_data['repes'] = $exercises->repetition;
										} else {
											$exercise_data['Name'] = count($exercises->actvityVideo) ? $exercises->actvityVideo->title : "REST";
											$exercise_data['sets'] = $exercises->sets;
											$exercise_data['repes'] = $exercises->repetition;
										}

										if (in_array($name, $checkData)) {
											$index = count($workout_data[$name]);
											$workout_data[$name][$index] = $exercise_data;
										} else {
											$index = 0;
											$checkData[] = $name;
											$workout_data[$name][$index] = $exercise_data;
										}
									}
								}
								$data[$day] = $workout_data;
							}
						} else {

							$checkData = [];
							$workout_data = [];
							foreach ($client_plan['workouts'] as $workoutKey => $workout) {
								$exercise_data = [];
								$name = $workout['desc'];
								$exercises = AbPlanWorkoutExercise::with('exercies')->where('client_plan_workout', $workout['pivot']['id'])->first();
								if (count($exercises)) {
									if ($exercises->type == AbPlanWorkoutExercise::EXERCISE) {
										$exercise_data['Name'] = count($exercises->exercies) ? $exercises->exercies->name : "REST";
										$exercise_data['sets'] = $exercises->sets;
										$exercise_data['repes'] = $exercises->repetition;
									} else {
										$exercise_data['Name'] = count($exercises->actvityVideo) ? $exercises->actvityVideo->title : "REST";
										$exercise_data['sets'] = $exercises->sets;
										$exercise_data['repes'] = $exercises->repetition;
									}

									if (in_array($name, $checkData)) {
										$index = count($workout_data[$name]);
										$workout_data[$name][$index] = $exercise_data;
									} else {
										$index = 0;
										$checkData[] = $name;
										$workout_data[$name][$index] = $exercise_data;
									}
								}
							}
							$data['Day'] = $workout_data;
						}
					} else {
						for ($i = 1; $i <= $client_plan->noOfDaysInWeek; $i++) {
							$checkData = [];
							$workout_data = [];
							foreach ($client_plan['workouts'] as $workoutKey => $workout) {
								$exercise_data = [];
								$name = $workout['desc'];
								$exercises = AbPlanWorkoutExercise::with('exercies')->where('client_plan_workout', $workout['pivot']['id'])->first();
								if (count($exercises)) {
									if ($exercises->type == AbPlanWorkoutExercise::EXERCISE) {
										$exercise_data['Name'] = count($exercises->exercies) ? $exercises->exercies->name : "REST";
										$exercise_data['sets'] = $exercises->sets;
										$exercise_data['repes'] = $exercises->repetition;
									} else {
										$exercise_data['Name'] = count($exercises->actvityVideo) ? $exercises->actvityVideo->title : "REST";
										$exercise_data['sets'] = $exercises->sets;
										$exercise_data['repes'] = $exercises->repetition;
									}

									if (in_array($name, $checkData)) {
										$index = count($workout_data[$name]);
										$workout_data[$name][$index] = $exercise_data;
									} else {
										$index = 0;
										$checkData[] = $name;
										$workout_data[$name][$index] = $exercise_data;
									}
								}
							}
							$data['Day ' . $i] = $workout_data;
						}
					}
				}
			} elseif ($request->PlanType == 8 && $request->has('clientVideoId')) {
				$videoData = ActivityVideo::find($request->clientVideoId);
				if ($videoData) {
					$client_plan = new AbClientPlan;
					$client_plan->clientId = $clientId;
					$client_plan->businessId = session()->get('businessId');
					$client_plan->plan_type = $plan_type;
					$client_plan->name = $videoData->title;
					$client_plan->activity_video_id = $videoData->id;
					$client_plan->weeksToExercise = (int) $request->WeeksToExercise;
					$client_plan->daysOfWeek = $request->DaysOfWeek;
					if ($request->has('Method'))
						$client_plan->method = $request->Method;
					if ($request->has('TimePerWeek'))
						$client_plan->timePerWeek = (int) $request->TimePerWeek;
					if ($request->has('Height'))
						$client_plan->height = (int) $request->Height;
					if ($request->has('Weight'))
						$client_plan->weight = (int) $request->Weight;
					if ($request->has('Age'))
						$client_plan->age = (int) $request->Age;
					if ($request->has('TimePerWeek'))
						$client_plan->timePerWeek = (int) $request->TimePerWeek;


					if ($client_plan->save()) {
						$ClientPlanId = $client_plan->id;
						$needle = "1";
						$lastPos = 0;
						$positions = array();

						while (($lastPos = strpos($client_plan->daysOfWeek, $needle, $lastPos)) !== false) {
							$positions[] = $lastPos;
							$lastPos = $lastPos + strlen($needle);
						}
						$selected_days = array_intersect_key($day_array, array_flip($positions));
						if (count($selected_days)) {
							$workout_data = [];
							foreach ($selected_days as $day) {
								$workout_data['Activity-Video'] = [];
								$workout_data['Activity-Video'][] = ['Name' => $client_plan->name];
								$data[$day] = $workout_data;
							}
						}
					}
				}
			}
		}
		if (count($data)) {
			$response['status'] = 'success';
			$response['data'] = $data;
			$response['clientPlanId'] = $ClientPlanId;
		} else {
			$response['status'] = 'error';
		}

		return json_encode($response);
	}

	/**
	 * 
	 */
	public function replicateProgram(Request $request){
		$clientPlanId = $request->progrmId;
		$clientId = $request->clientId;
		$businessId = Session::get('businessId');
		$timestamp = Carbon::now();
		$program_detail = AbClientPlan::with('workouts')->where('id', $clientPlanId)->first();
		try{
			if($program_detail){
				$newRow = $program_detail->replicate();
				$newRow->clientId = $clientId;
				$newRow->custom = 1;
				$newRow->active = 1;
				$newRow->status = 'incomplete';
				$newRow->getPreWritten = 'false';
				$newRow->created_at = $timestamp;
				$newRow->updated_at = $timestamp;
				if ($newRow->save()) {
					$newPlanId = $newRow->id;
					if (count($program_detail['workouts'])) {
						$data = array();
						foreach ($program_detail['workouts'] as $key => $workout) {
							$workoutId = $workout['pivot']['workout_id'];
	
							$plan_work_id = AbClientPlanWorkout::insertGetId(['business_id' => $businessId, 'client_plan_id' => $newPlanId, 'workout_id' => $workoutId,'order' =>$workout['pivot']['order'], 'created_at' => $timestamp, 'updated_at' => $timestamp]);
	
							$planWorkExe = AbPlanWorkoutExercise::where('client_plan_workout', $workout['pivot']['id'])->get();
							if ($planWorkExe->count()) {
								foreach ($planWorkExe as $key => $workoutExe) {
									$newPlanWExe = $workoutExe->replicate();
									$newPlanWExe->client_plan_workout = $plan_work_id;
									if ($newPlanWExe->save()) {
										$exeSetsData = AbPlanWorkoutExerciseSet::where('ab_plan_workout_exercise_id', $workoutExe->id)->get();
										if (count($exeSetsData)) {
											foreach ($exeSetsData as $exeSetData) {
												AbPlanWorkoutExerciseSet::create([
													'ab_plan_workout_exercise_id' => $newPlanWExe->id,
													'sets' => $exeSetData->sets,
													'repetition' => $exeSetData->repetition,
													'estimatedTime' => $exeSetData->estimatedTime,
													'resistance' => $exeSetData->resistance,
													'tempoDesc' => $exeSetData->tempoDesc,
													'restSeconds' => $exeSetData->restSeconds,
												]);
											}
										}
									}
								}
							}
						}
					}
				}
				$response = [
					'status' => 'ok',
					'clientPlanId' => $newPlanId,
				];
			}else{
				$response = [
					'status' => 'error'
				];
			}
		}catch(\ Throwable $e){
			$response = [
				'status' => 'error',
				'message' => $e->getMessage()
			];
		}
		return json_encode($response);
	}

	/**
	 * Set Plan Workout Exercise Order
	 * 
	 * @param array $orderExercise
	 * @return 
	 */
	public function setPlanWorkoutExerciseOrder($orderExercise,$planType = 0)
	{
		foreach ($orderExercise as $key => $item) {
			if($planType == 9){
				PlanMultiPhaseWorkoutExercise::where('id',$item['planWorkoutExerciseId'])->update([
					'exe_order' => $item['order']
				]);
			}else{
				AbPlanWorkoutExercise::where('id', $item['planWorkoutExerciseId'])->update([
					'exe_order' => $item['order']
				]);
			}
		}
	}


	/** 
	 * Save final client plan.
	 * @param 
	 * @return response
	 **/
	public function SavePlan(Request $request)
	{
		$isError = true;
		$isClient = false;
		$response = array();
		$businessId = Session::get('businessId');

		$ClientPlanId = $request->ClientPlanId;
		$clientId = (int) $request->Clientid;
		if ($clientId != 0)
			$isClient = true;

		$PlanType = (int) $request->PlanType;
		$timestamp = Carbon::now();

		$program_detail = AbClientPlan::with('workouts')->where('id', $ClientPlanId)->first();
		if ($PlanType == 6 && $isClient && count($program_detail)) {
			$newRow = $program_detail;
			$newRow->clientId = $clientId;

			if ($isClient)
				$newRow->heading = $program_detail->name . " program generated on " . Carbon::now()->format('d-M-Y H:i');

			$newRow->custom = 1;
			$newRow->active = 1;
			$newRow->status = 'complete';
			$newRow->getPreWritten = 'false';
			$newRow->created_at = $timestamp;
			$newRow->updated_at = $timestamp;
			if ($newRow->save()) {
				$newPlanId = $newRow->id;
				if ($isClient && $newRow->start_date != 0) {
					$this->activityCalenderDataInsert($newPlanId, $newRow->weeksToExercise, $newRow->daysOfWeek, $newRow->estimatedTime, $newRow->start_date);
				}
				$isError = false;
			}
		} elseif (count($program_detail)) {
			if ($isClient) {
				$program_detail->heading = $program_detail->name . " program generated on " . Carbon::now()->format('d-M-Y H:i');
				$program_detail->getPreWritten = false;
				$program_detail->status = 'complete';
			}else{
				$program_detail->getPreWritten = true;
				$program_detail->status = 'complete';
			}

			$program_detail->custom = 1;
			$program_detail->active = 1;
			//$program_detail->created_at = $timestamp;	
			$program_detail->updated_at = $timestamp;
			if ($program_detail->update()) {
				$newPlanId = $program_detail->id;
				if ($isClient && $program_detail->start_date != 0) {
					$this->activityCalenderDataInsert($newPlanId, $program_detail->weeksToExercise, $program_detail->daysOfWeek, $program_detail->estimatedTime, $program_detail->start_date);
				}
				$isError = false;
			}
		}

		if (!$isError) {
			$response['status'] = 'success';
			$response['newClientPlanId'] = $newPlanId;
			$response['page'] = ($isClient) ? 'user' : 'admin';
		} else {
			$response['status'] = 'error';
		}
		return json_encode($response);
	}


	/**
	 * Remove Exercise
	 * @param exercise id
	 * @return Exercise id
	 **/
	public function RemoveExerciseFromProgram(Request $request)
	{
		$msg['status'] = 'error';
		if($request->has('plan_type') && $request->plan_type == '9'){
			$planWorkExe = (int) $request->planWorkoutExercise;	
			$plan_workout_exercise = PlanMultiPhaseWorkoutExercise::find($planWorkExe);
			if (count($plan_workout_exercise)) {
				$client_plan_id = $plan_workout_exercise->plan_multi_phase_workout_id;
				if ($plan_workout_exercise->delete()) {
					if (PlanMultiPhaseWorkout::find($client_plan_id)->delete()) {
						$msg['status'] = 'success';
						$msg['type'] = $plan_workout_exercise->type;
					}
				}
			}
		}
       else{
		$planWorkExe = (int) $request->planWorkoutExercise;
		$plan_workout_exercise = AbPlanWorkoutExercise::find($planWorkExe);

		if (count($plan_workout_exercise)) {
			$client_plan_id = $plan_workout_exercise->client_plan_workout;
			if ($plan_workout_exercise->delete()) {
				if (AbClientPlanWorkout::find($client_plan_id)->delete()) {
					$msg['status'] = 'success';
					$msg['type'] = $plan_workout_exercise->type;
				}
			}
		}
	}
		return json_encode($msg);
	}


	/**
	 * Add filter to generate program
	 * @param form data
	 * @return response
	 **/
	public function AddFilterToGenPlan(Request $request)
	{
		$msg['status'] = 'error';
		$timestamp = Carbon::now();
		$data = $request->all();
		$insertedData = array();
		$exerciseId = $data['exercise_id'];
		$businessId = Session::get('businessId');
		$workoutId = AbWorkout::where('name', $data['workout_name'])->pluck('id')->first();

		$insertedData['purpose'] = implode(',', $data['purpose']);
		$insertedData['equipment'] = implode(',', $data['equipment']);
		$insertedData['curr_phy_act'] = implode(',', $data['curr_phy_act']);
		$insertedData['prev_phy_act'] = implode(',', $data['prev_phy_act']);
		$insertedData['next_phy_act'] = implode(',', $data['next_phy_act']);
		$insertedData['curr_intensity_phy_act'] = implode(',', $data['curr_intensity_phy_act']);

		$insertedData['workout_id'] = $workoutId;
		$insertedData['business_id'] = $businessId;
		$insertedData['exercise_id'] = $exerciseId;
		$insertedData['created_at'] = $timestamp;
		$insertedData['updated_at'] = $timestamp;

		if (count($insertedData)) {
			if ($id = AbClientPlanGenerate::insertGetId($insertedData))
				$msg['status'] = 'success';
		}
		return json_encode($msg);
	}


	/** 
	 * Insert calender data in ab_client_plan_Date
	 * @param plan id, total exercise weeks, choose day
	 * @return boolean value(true/false)
	 **/
	protected function activityCalenderDataInsert($planId, $weeks, $dayPattern, $time, $startDate)
	{
		$days = str_split($dayPattern, 1);
		if ($time == 0)
			$time = 60;

		$totalSelectedDays = 0;
		foreach ($days as $day) {
			if ($day == '1') {
				$totalSelectedDays = $totalSelectedDays + 1;
			}
		}
		$totalProgamCount = $weeks * $totalSelectedDays;
		$count = 1;
		if ($weeks > 0) {
			$insertedData = array();
			$timestamp = Carbon::now();
			$currentDate = $startDate;
			for ($i = 0; $i <= $weeks; $i++) {
				for ($j = 0; $j < count($days); $j++) {
					if ($count <= $totalProgamCount) {
						if ($days[$j] == '1') {
							$st = $this->calDateByWeek($i, $j, $currentDate);
							$et = $this->calDateByWeek($i, $j, $currentDate);
							if ($st >= $currentDate) {
								$et = $et->addMinutes($time);
								$insertedData[] = ['client_plan_id' => $planId, 'plan_start_date' => $st, 'plan_end_date' => $et, 'created_at' => $timestamp, 'updated_at' => $timestamp];
								$count = $count + 1;
							}
						}
					}
				}
			}
			if (count($insertedData)) {
				AbClientPlanDate::where('client_plan_id', $planId)->forcedelete();
				if (AbClientPlanDate::insert($insertedData))
					return true;
			}
		}
		return false;
	}


	/** 
	 * calculate date 
	 * @param week day
	 * @return date
	 **/
	public function calDateByWeek($week, $day, $currentDate)
	{
		$date = Carbon::parse($currentDate);
		$date = $date->addWeeks($week);
		$date = $date->startOfWeek();
		$date = $date->addDays($day);
		return $date;
	}


	public function activityViedos(Request $request)
	{
		$businessId = Session::get('businessId');
		$where = ['business_id' => $businessId];
		if ($request->has('filter')) {
			$where['workout_id'] = $request->filter;
		}
		if ($request->has('keyword') && $request->keyword != '') {
			$activityVideos = ActivityVideo::where('title', 'LIKE', "%$request->keyword%")->where($where)->get();
		} else {
			$activityVideos = ActivityVideo::where($where)->get();
		}
		$response['videos'] = [];
		if (count($activityVideos)) {
			foreach ($activityVideos as $activityVideo) {
				$response['videos'][] = [
					'id' => $activityVideo->id,
					'title' => $activityVideo->title,
					'workout_type' => $activityVideo->workout->desc,
					'workout_id' => $activityVideo->workout_id,
					'video' => $activityVideo->video,
					'thumbnail' => $activityVideo->thumbnail,
					'video_duration' => $activityVideo->video_duration,
					'created_at' => $activityVideo->created_at->format('d-m-Y')
				];
			}
		}
		return json_encode($response);
	}

	public function exerciseType(Request $request)
	{
		$clientPlan = AbClientPlan::where('id', $request->clientPlanId)->first();
		$exerciseType = 0;
		if ($clientPlan->is_video == 1) {
			$exerciseType = 2;
		} elseif ($clientPlan->is_video == 0) {
			$exerciseType = 1;
		} elseif ($clientPlan->is_video == 2) {
			$exerciseType = 0;
		}
		return response()->json($exerciseType);
	}

	public function updateRest(Request $request)
	{
		AbPlanWorkoutExercise::where('id', $request->planWorkoutExeId)->update([
			'restSeconds' => $request->restSeconds
		]);
		$response = [
			'status' => 'ok'
		];
		return json_encode($response);
	}

	public function DeleteMultipleExercise(Request $request)
	{
		$exeIdToDelete = $request->exeIdToDelete;
		try {
			if ($request->has('ClientPlanType') && $request->ClientPlanType == '9'){
				$plan_workout_exercise = PlanMultiPhaseWorkoutExercise::whereIn('id', $exeIdToDelete)->get();
				if (count($plan_workout_exercise)) {
					foreach ($plan_workout_exercise as $value) {
						$client_plan_id = $value->plan_multi_phase_workout_id;
						if ($value->delete()) {
							PlanMultiPhaseWorkout::find($client_plan_id)->delete();
						}
					}
				}
			}
			else{
			$plan_workout_exercise = AbPlanWorkoutExercise::whereIn('id', $exeIdToDelete)->get();
			if (count($plan_workout_exercise)) {
				foreach ($plan_workout_exercise as $value) {
					$client_plan_id = $value->client_plan_workout;
					if ($value->delete()) {
						AbClientPlanWorkout::find($client_plan_id)->delete();
					}
				}
			}
		}
			$msg['status'] = 'success';
		} catch (\Throwable $e) {
			$msg['status'] = 'error';
			$msg['message'] = $e->getMessage();
		}
		return json_encode($msg);
	}

	public function clientMultiphaseProgram(Request $request){
		$libraryProgramId = $request->libraryProgramId;
        $clientId = $request->clientId;
        $timestamp = Carbon::now();
        $businessId = Session::get('businessId');
        $program_detail = AbClientPlan::where('id', $libraryProgramId)->first();
        try {
			$newRow = $program_detail->replicate();
			$newRow->clientId = $clientId;
			$newRow->custom = 1;
			$newRow->active = 0;
			$newRow->status = 'incomplete';
			$newRow->getPreWritten = 'false';
			$newRow->created_at = $timestamp;
			$newRow->updated_at = $timestamp;
			if($newRow->save()){
				if (count($program_detail->clientPlanPhases)) {
					foreach ($program_detail->clientPlanPhases as $phaseData) {
						$planProgram = $phaseData->planProgram;
						if(count($planProgram)){
							$data = [
								'client_plan_id' => $newRow->id,
								'program_type' => $planProgram->program_type,
								'title' => $planProgram->title,
								'description' => $planProgram->description,
								'is_video' => $planProgram->is_video,
								'status' => $planProgram->status
							];
							$newClientProgram = AbClientPlanProgram::create($data);
							$newPhaseData = [
								'client_plan_id' => $newRow->id,
								'program_id' => $newClientProgram->id,
								'phase_no' => $phaseData->phase_no,
								'week_no' => $phaseData->week_no,
								'day_no' => $phaseData->day_no,
								'day' => $phaseData->day,
								'session_no' =>$phaseData->session_no
							];
							ClientPlanPhase::create($newPhaseData);
							if (count($planProgram->workouts)) {
								foreach ($planProgram->workouts as $key => $workout) {
									$workoutId = $workout['pivot']['workout_id'];
									$plan_work_id = PlanMultiPhaseWorkout::insertGetId(['business_id' => $businessId, 'plan_program_id' => $newClientProgram->id, 'workout_id' => $workoutId,'order_no' => $workout['pivot']['order_no'], 'created_at' => $timestamp, 'updated_at' => $timestamp]);
									$planWorkExe = PlanMultiPhaseWorkoutExercise::where('plan_multi_phase_workout_id', $workout['pivot']['id'])->get();
									if ($planWorkExe->count()) {
										foreach ($planWorkExe as $key => $workoutExe) {
											$planMultiPhaseWorkOutExercise = PlanMultiPhaseWorkoutExercise::create([
												'plan_multi_phase_workout_id' => $plan_work_id,
												'exercise_id' => $workoutExe->exercise_id,
												'type' => $workoutExe->type,
												'is_rest' => $workoutExe->is_rest,
												'exe_order' => $workoutExe->exe_order
											]);
											$exeSetsData = PlanMultiPhaseWorkoutExerciseSet::where('plan_multi_phase_workout_exercise_id', $workoutExe->id)->get();
											if (count($exeSetsData)) {
												foreach ($exeSetsData as $exeSetData) {
													PlanMultiPhaseWorkoutExerciseSet::create([
														'plan_multi_phase_workout_exercise_id' => $planMultiPhaseWorkOutExercise->id,
														'sets' => $exeSetData->sets,
														'repetition' => $exeSetData->repetition,
														'estimatedTime' => $exeSetData->estimatedTime,
														'resistance' => $exeSetData->resistance,
														'tempoDesc' => $exeSetData->tempoDesc,
														'restSeconds' => $exeSetData->restSeconds,
													]);
												}
											}
										}
									}
								}
							}
						}
					}
				}
				$response = [
					'status' => 'ok',
					'clientProgramId' => $newRow->id,
					'clientPlan' => $newRow->toArray()
				];
				return response()->json($response);
			}
        } catch (\Throwable $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            return response()->json($response);
        }
	}
}
