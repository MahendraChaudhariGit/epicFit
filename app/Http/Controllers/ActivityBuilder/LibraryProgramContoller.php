<?php

namespace App\Http\Controllers\ActivityBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Session;
use Input;
use Auth;

use App\Http\Traits\ActivityBuilderTrait;
use App\Http\Traits\HelperTrait;

use App\AbPlanWorkout;
use App\AbWorkout;
use App\AbClientPlan;
use App\AbClientPlanDate;
use App\AbClientPlanProgram;
use App\AbPlans;
use App\AbPlanWorkoutExercise;
use App\AbPlanWorkoutExerciseSet;
use App\ClientPlanPhase;
use App\PlanMultiPhaseWorkout;
use App\PlanMultiPhaseWorkoutExercise;
use App\PlanMultiPhaseWorkoutExerciseSet;
use Throwable;

class LibraryProgramContoller extends Controller
{
    use HelperTrait, ActivityBuilderTrait;

    /** Set cookie for filter */
    private $cookieSlug = 'libraryPrograms';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-library-program'))
            abort(404);

        $libraryPrograms = array();
        $search = $request->get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        if ($search) {
            $libraryPrograms = AbClientPlan::where('businessId', Session::get('businessId'))
                ->where('clientId', 0)
                ->where('plan_type', 6)
                ->where(function ($query) use ($search) {
                    $query->orWhere('name', 'like', "%$search%");
                })
                ->paginate($length);
        } else
            $libraryPrograms = AbClientPlan::where('businessId', Session::get('businessId'))->where('clientId', 0)->where('plan_type', 6)->paginate($length);

        return view('ActivityBuilder.LibraryProgram.index', compact('libraryPrograms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-library-program'))
            abort(404);

        $exerciseData = $this->getExercisesOptions();
        return view('ActivityBuilder.LibraryProgram.edit', compact('exerciseData'));
    }

    /**
     * Store a newly created resource in storage.
     * @param 
     * @return Response
     **/
    public function store(Request $request)
    {
        // dd($request->all());
        $msg['status'] = 'error';
        $businessId = Session::get('businessId');
        $unique_id  = substr(md5(uniqid(mt_rand(), true)), 0, 8);
        $program = array();
        $data = $request->all();
        ksort($data);
        $program['businessId'] = $businessId;
        $program['clientId'] = 0;
        $program['name'] = $data['name'];
        $program['discription'] = $data['discription'];
        $program['plan_type'] = 6; // FOR LIBRARY PROGRAM SINGLE PHASE ONLY 
        $program['plan_unique_id'] = $unique_id;
        $program['weeksToExercise'] = 12;
        $program['daysOfWeek'] = '1111111';
        $program['getPreWritten'] = 'true';
        $program['status'] = 'incomplete';
        $program['habit'] = $data['habit'];
        $program['equipment'] = $data['equipment'];


        if ($data['genderAdmin'] == 'Male')
            $program['gender'] = 2;
        elseif ($data['genderAdmin'] == 'Female')
            $program['gender'] = 1;
        elseif ($data['genderAdmin'] == 'Unisex')
            $program['gender'] = 3;

        if ($data['programImage'] != '')
            $program['image'] = $data['programImage'];
        else
            $program['image'] = $data['prePhotoName'];

        $timestamp = Carbon::now();
        $program['created_at'] = $timestamp;
        $program['updated_at'] = $timestamp;

        if ($pid = AbClientPlan::insertGetId($program)) {
            $msg['status'] = 'added';
            $msg['id'] = $pid;
        }

        return json_encode($msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'edit-library-program'))
            abort(404);

        $libraryProgram = AbClientPlan::find($id);
        $exerciseData = $this->getExercisesOptions();
        return view('ActivityBuilder.LibraryProgram.edit', compact('libraryProgram', 'exerciseData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $msg['status'] = 'error';
        $program = array();
        $data = $request->all();
        ksort($data);
        $program['name'] = $data['name'];
        $program['discription'] = $data['discription'];
        $program['getPreWritten'] = 'true';
        $program['habit'] = $data['habit'];
        $program['equipment'] = $data['equipment'];

        if ($data['genderAdmin'] == 'Male')
            $program['gender'] = 2;
        elseif ($data['genderAdmin'] == 'Female')
            $program['gender'] = 1;

        if ($data['programImage'] != '')
            $program['image'] = $data['programImage'];
        else
            $program['image'] = $data['prePhotoName'];

        $timestamp = Carbon::now();
        $program['updated_at'] = $timestamp;

        if (AbClientPlan::where('id', $id)->update($program)) {
            $msg['status'] = 'updated';
            $msg['id'] = $id;
        }

        return json_encode($msg);
    }

    /**
     * Remove the specified exercise from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'delete-library-program'))
            abort(404);

        $library_program = AbClientPlan::find($id);
        $library_program->delete();
        return redirect()->back()->with('message', 'success|Program has been deleted successfully.');
    }

    /**
     * Remove the specified exercise from storage.
     *
     * @param  no any thing
     *
     * @return workouts
     */
    protected function workouts()
    {
        $workouts = AbWorkout::get();
        $workout = array('' => ' -- Select -- ');
        if ($workouts->count()) {
            foreach ($workouts as $value) {
                $workout[$value->id] = $value->name;
            }
        }
        return $workout;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createMultiPhase()
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-library-program'))
            abort(404);

        $exerciseData = $this->getExercisesOptions();
        return view('ActivityBuilder.LibraryProgram.MultiPhase.edit', compact('exerciseData'));
    }

    /**
     * Multi Phase Program
     */

    public function indexMultiPhase(Request $request)
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-library-program'))
            abort(404);
        $libraryPrograms = array();
        $search = $request->get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        if ($search) {
            $libraryPrograms = AbClientPlan::where('businessId', Session::get('businessId'))
                ->where('clientId', 0)
                ->where('plan_type', 9)
                ->where(function ($query) use ($search) {
                    $query->orWhere('name', 'like', "%$search%");
                })
                ->where('status','complete')
                ->paginate($length);
        } else
            $libraryPrograms = AbClientPlan::where('businessId', Session::get('businessId'))->where('clientId', 0)->where('plan_type', 9)->where('status','complete')->paginate($length);

        return view('ActivityBuilder.LibraryProgram.MultiPhase.index', compact('libraryPrograms'));
    }

    public function storeMultiPhase(Request $request)
    {
        $msg['status'] = 'error';
        $businessId = Session::get('businessId');
        $unique_id  = substr(md5(uniqid(mt_rand(), true)), 0, 8);
        $program = array();
        $data = $request->all();
        ksort($data);
        $program['businessId'] = $businessId;
        $program['clientId'] = 0;
        $program['name'] = $data['name'];
        $program['discription'] = $data['discription'];
        $program['plan_type'] = 9; // FOR LIBRARY PROGRAM MULTI PHASE ONLY 
        $program['plan_unique_id'] = $unique_id;
        $program['getPreWritten'] = 'true';
        $program['status'] = 'incomplete';
        $program['habit'] = $data['habit'];
        $program['equipment'] = $data['equipment'];


        if ($data['genderAdmin'] == 'Male')
            $program['gender'] = 2;
        elseif ($data['genderAdmin'] == 'Female')
            $program['gender'] = 1;
        elseif ($data['genderAdmin'] == 'Unisex')
            $program['gender'] = 3;

        if ($data['programImage'] != '')
            $program['image'] = $data['programImage'];
        else
            $program['image'] = $data['prePhotoName'];

        $timestamp = Carbon::now();
        $program['created_at'] = $timestamp;
        $program['updated_at'] = $timestamp;

        if ($pid = AbClientPlan::insertGetId($program)) {
            $msg['status'] = 'added';
            $msg['id'] = $pid;
        }

        return json_encode($msg);
    }

    public function editMultiPhase($id)
    {
        if (!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'edit-library-program'))
            abort(404);
        $libraryProgram = AbClientPlan::find($id);
        $exerciseData = $this->getExercisesOptions();
        return view('ActivityBuilder.LibraryProgram.MultiPhase.edit', compact('libraryProgram', 'exerciseData'));
    }

    public function multiPhaseUpdate(Request $request,$id){
        $msg['status'] = 'error';
        $program = array();
        $data = $request->all();
        ksort($data);
        $program['name'] = $data['name'];
        $program['discription'] = $data['discription'];
        $program['getPreWritten'] = 'true';
        $program['habit'] = $data['habit'];
        $program['equipment'] = $data['equipment'];

        if ($data['genderAdmin'] == 'Male')
            $program['gender'] = 2;
        elseif ($data['genderAdmin'] == 'Female')
            $program['gender'] = 1;

        if ($data['programImage'] != '')
            $program['image'] = $data['programImage'];
        else
            $program['image'] = $data['prePhotoName'];

        $timestamp = Carbon::now();
        $program['updated_at'] = $timestamp;

        if (AbClientPlan::where('id', $id)->update($program)) {
            $clientPlan = AbClientPlan::where('id',$id)->first()->toArray();
            $msg['status'] = 'updated';
            $msg['id'] = $id;
            $msg['clientPlan'] = $clientPlan;
        }

        return json_encode($msg);  
    }

    public function getProgramDetails(Request $request)
    {
        $progrmId = $request->progrmId;
        $clientPlanId = $request->clientPlanId;
        $timestamp = Carbon::now();
        $businessId = Session::get('businessId');
        $program_detail = AbClientPlan::with('workouts')->where('id', $progrmId)->first();
        try {
            if (count($program_detail)) {
                $data = [
                    'client_plan_id' => $clientPlanId,
                    'program_type' => $request->programType,
                    'title' => $program_detail->name,
                    'description' => $program_detail->description,
                    'status' => 0
                ];
                $clientPlanProgram = AbClientPlanProgram::create($data);
                if (count($program_detail['workouts'])) {
                    foreach ($program_detail['workouts'] as $key => $workout) {
                        $workoutId = $workout['pivot']['workout_id'];
                        $plan_work_id = PlanMultiPhaseWorkout::insertGetId(['business_id' => $businessId, 'plan_program_id' => $clientPlanProgram->id, 'workout_id' => $workoutId,'order_no' => $workout['pivot']['order'], 'created_at' => $timestamp, 'updated_at' => $timestamp]);
                        $planWorkExe = AbPlanWorkoutExercise::where('client_plan_workout', $workout['pivot']['id'])->get();
                        if ($planWorkExe->count()) {
                            foreach ($planWorkExe as $key => $workoutExe) {
                                $planMultiPhaseWorkOutExercise = PlanMultiPhaseWorkoutExercise::create([
                                    'plan_multi_phase_workout_id' => $plan_work_id,
                                    'exercise_id' => $workoutExe->exercise_id,
                                    'type' => $workoutExe->type,
                                    'is_rest' => $workoutExe->is_rest,
                                    'exe_order' => $workoutExe->exe_order
                                ]);
                                $exeSetsData = AbPlanWorkoutExerciseSet::where('ab_plan_workout_exercise_id', $workoutExe->id)->get();
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
            $response = [
                'status' => 'ok',
                'clientProgramId' => $clientPlanProgram->id,
                'title' => $clientPlanProgram->title
            ];
            return response()->json($response);
        } catch (\Throwable $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            return response()->json($response);
        }
    }

    public function createProgram(Request $request)
    {
        $data = [
            'client_plan_id' => $request->clientPlan,
            'program_type' => $request->programType,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 0
        ];
        try {
            $clientPlanProgram = AbClientPlanProgram::create($data);
            $response = [
                'status' => 'ok',
                'clientPlanProgramId' => $clientPlanProgram->id
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return response()->json($response);
    }

    public function UpdateProgram(Request $request)
    {
        try {
            $clientPlanProgram = AbClientPlanProgram::where('id', $request->id)->first();
            $clientPlanProgram->update([
                'status' => 1
            ]);
            $order = $request->order;
			foreach ($order as $value) {
				$client_workouts = PlanMultiPhaseWorkout::where(['plan_program_id' => $clientPlanProgram->id])->where('workout_id', $value['workout_id'])->update([
					'order_no' => $value['order']
				]);
			}
			$orderExercise = $request->orderExercise;
			foreach ($orderExercise as $key => $item) {
                PlanMultiPhaseWorkoutExercise::where('id',$item['planWorkoutExerciseId'])->update([
                    'exe_order' => $item['order']
                ]);
            }
            $response = [
                'status' => 'ok',
                'title' => $clientPlanProgram->title
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return response()->json($response);
    }

    public function planPreview(Request $request)
    {
        try {
            ClientPlanPhase::where('client_plan_id',$request->clientPlanId)->delete();
            $data = $request->data;
            foreach ($data as $phaseData) {
                foreach ($phaseData['weekData'] as $weekData) {
                    foreach ($weekData['daysData'] as $dayData) {
                        foreach ($dayData['sessionData'] as $sessionData) {
                            ClientPlanPhase::create([
                                'client_plan_id' => $request->clientPlanId,
                                'phase_no' => $phaseData['phaseNo'],
                                'week_no' => $weekData['weekNo'],
                                'day_no' => $dayData['dayNo'],
                                'day' => $dayData['day'],
                                'session_no' => $sessionData['sessionNo'],
                                'program_id' => $sessionData['sessionProgramId']
                            ]);
                        }
                    }
                }
            }
            $response = [
                'status' => 'ok'
            ];
        } catch (\Throwable $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return response()->json($response);
    }

    public function updatePlan(Request $request){
        $clientPlanId = $request->id;
        if($request->has('startDate')){
            $startDate = $request->startDate;
            $updateData['start_date'] = $request->startDate;
        }
        if($request->has('status')){
            $updateData['status'] = $request->status;
        }
        if($request->has('dayOption')){
            $updateData['dayOption'] = $request->dayOption;
        }
        try{
            AbClientPlan::where('id',$clientPlanId)->update($updateData);
            if($request->has('insertCalendar') && $request->insertCalendar == true){
                $this->activityCalenderDataInsert($clientPlanId,$startDate);
            }
            $response = [
                'status' => 'ok',
            ];
        }catch(\Throwable $e){
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return response()->json($response);
    }

    public function activityCalenderDataInsert($clientPlanId,$startDate){
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

    public function getPhaseData(Request $request){
        $clientPlan = AbClientPlan::with('clientPlanPhases')->where('id',$request->id)->first();
        $data = [];
        if($clientPlan){
            foreach ($clientPlan->clientPlanPhases as $value) {
                $data[$value->phase_no][$value->week_no][$value->day_no][$value->session_no] = [
                    'is_session_program' => 1,
                    'programId' => $value->program_id,
                    'title' => $value->planProgram->title
                ];
                $data[$value->phase_no][$value->week_no][$value->day_no]['day'] = $value->day;
            }
            $response = [
                'status' => 'ok',
                'data' => $data
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Plan not exist'
            ];
        }
        return response()->json($response);
    }
}
