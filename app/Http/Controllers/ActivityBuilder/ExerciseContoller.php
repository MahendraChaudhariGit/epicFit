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

use App\AbExerciseMovementPattern;
use App\AbWorkoutExercise;
use App\AbExerciseImage;
use App\AbExerciseVideo;
use App\AbWorkout;
use App\Exercise;
use App\AbMuscleGroup;



class ExerciseContoller extends Controller{
    use HelperTrait, ActivityBuilderTrait;
    private $cookieSlug = 'exercise';
    /**
     * Display a listing of the resource.
     * @param void
     * @return Response
    **/
    public function index(Request $request){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-exercise'))
            abort(404);
        $exercises = array();
        $search = $request->get('search');
            $length = $this->getTableLengthFromCookie($this->cookieSlug);
        // $length = 10;
            if($search){
                $muscleGrpIds = AbMuscleGroup::where('name','like',"%$search%")->pluck('id')->toArray();
                 $exercises = Exercise::with('exevideos')->where('businessId',Session::get('businessId'))
                                      ->where(function($query) use($search,$muscleGrpIds){
                                            $query->orWhere('exerciseDesc', 'like', "%$search%")
                                                ->orWhere('name','like',"%$search%")
                                                ->orWhere('sub_heading','like',"%$search%")
                                                ->orWhere('alsoname','like',"%$search%s")
                                                ->orWhere('muscles','like',"%$search%")
                                                ->orWhere('benifits','like',"%$search%")
                                                ->orWhere('cues','like',"%$search%")
                                                ->orWhere('movement_desc','like',"%$search%")
                                                ->orWhere('common_mistekes','like',"%$search%")
                                                ->orWhere('progress','like',"%$search%")
                                                ->orWhere(function($query) use($muscleGrpIds){
                                                    foreach($muscleGrpIds as $id){
                                                        $query->whereRaw("FIND_IN_SET('".$id."',bodypart)");
                                                    }
                                                })
                                                ->orWhere(function($query) use($search){
                                                    $movTypeId = Exercise::movementTypeByName($search);
                                                    $query->where('movement_type',$movTypeId);
                                                })
                                                ->orWhereHas('exerciseEquipment', function($query) use ($search){
                                                    $query->where('eq_name', 'like', "%$search%");
                                                })
                                                ->orWhereHas('exerciseAbility',function($query) use($search){
                                                    $query->where('name', 'like', "%$search%");
                                                })
                                                ->orwherehas('type',function($query) use($search){
                                                    $query->where('type_name', 'like', "%$search%");
                                                })
                                                ->orwherehas('exerciseMovPattern',function($query) use($search){
                                                    $query->where('pattern_name', 'like', "%$search%");
                                                });
                                      })
                                      ->paginate($length);
            }
            else
                $exercises = Exercise::with('exevideos')->where('businessId',Session::get('businessId'))->paginate($length);
            $exerciseData = $this->getExercisesOptions();
         $muscleGrpIds = AbMuscleGroup::where('name','like',"%$search%")->pluck('id')->toArray();
            

        /*$exercises = Exercise::where('businessId',Session::get('businessId'))->orderBy('id','desc')->get();*/
        return view('ActivityBuilder.index', compact('exercises','exerciseData'));
    }

    /**
     * Validate Exercise name
     */
    public function validateName(Request $request){
        $name = $request->name;
        if(isset($request->exerciseId) && $request->exerciseId != ""){
            $ifNameExist = Exercise::where('id','!=',$request->exerciseId)->where('name',$name)->exists();
        }else{
            $ifNameExist = Exercise::where('name',$name)->exists();
        }
        $response = [
            'ifNameExist' => $ifNameExist
        ];
        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     * @param void
     * @return Response
    **/
    public function create(){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-exercise'))
            abort(404);

        $data = $this->getExercisesOptions();
        return view('ActivityBuilder.edit', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     * @param input field data
     * @return Response
    **/
    public function store(Request $request){
        $isError = true; 
        $businessId = Session::get('businessId');
        $exercises = new Exercise;
        $data = $request->all();
        ksort($data);
        // dd($data);
        if(count($data)){
            $exercises->businessId = $businessId;
            $exercises->name = $data['name'];
            $exercises->sub_heading = $data['sub_heading'];
            $exercises->alsoname = $data['alsoname'];
            $exercises->equipment = $data['equipment'];
            $exercises->ability = $data['ability'];
            $exercises->bodypart = implode(',',$data['bodypart']);
            $exercises->exerciseTypeID = $data['exerciseTypeID'];
            $exercises->exerciseDesc = $data['exerciseDesc'];
            $exercises->movement_load = $data['movement_load'];

            $exercises->movement_pattern = $data['movement_pattern'];
            $exercises->movement_type = $data['movement_type'];
            $exercises->listing_status = $data["listing_status"];
           $exercises->equipmentextra = implode(',',$data['equipmentextra']);
            $exercises->patternExtra = implode(',',$data['patternExtra']);


            
            if(array_key_exists('muscles', $data))
                $exercises->muscles = $data['muscles'];
            if(array_key_exists('benifits', $data))
                $exercises->benifits = $data['benifits'];
            if(array_key_exists('cues', $data))
                $exercises->cues = $data['cues'];
            if(array_key_exists('movement_desc', $data))
                $exercises->movement_desc = $data['movement_desc'];
            if(array_key_exists('common_mistekes', $data))
                $exercises->common_mistekes = $data['common_mistekes'];
            if(array_key_exists('progress', $data))
                $exercises->progress = $data['progress'];

            $img=[];
            foreach ($data as $key => $value) {
                if(strpos($key, 'expic') !== false)
                    $img[] = $value;
                elseif((strpos($key, 'exercisePicture') !== false) && $value != '')
                    $img[] = $value;
            }

            if(count($img)){
                if($exercises->save()){
                    $id = $exercises->id;
                    $exerciseImg = [];
                    foreach ($img as  $value) {
                        $timestamp = Carbon::now();
                        $exerciseImg[] = ['aei_exercise_id'=>$id, 'aei_image_name'=>$value, 'created_at'=>$timestamp, 'updated_at'=>$timestamp];  
                    }
                    if(AbExerciseImage::insert($exerciseImg)){
                        /*AbWorkoutExercise::where('exercise_id',$id)->forceDelete();
                        if($this->storeExercisesWorkouts($id, $data))*/
                        if($this->workoutWithExercise($id))
                            $isError = false; 
                    }
                }
            }

             
// dd($data);
            if(!empty($data['exerciseVideo']) || !empty($data['thumbnailProgram'])){
                // dd(AbExerciseVideo::all());
                if($exercises->save()){
                    $id = $exercises->id;
                    $timestamp = Carbon::now();
                    $exerciseVideo[] = ['aei_exercise_id'=>$id, 'aei_video_name'=>$data['exerciseVideo'],'thumbnail_program' => $data['thumbnailProgram'],'type' =>1, 'created_at'=>$timestamp, 'updated_at'=>$timestamp];  

                    if(AbExerciseVideo::insert($exerciseVideo)){
                        /*AbWorkoutExercise::where('exercise_id',$id)->forceDelete();
                        if($this->storeExercisesWorkouts($id, $data))*/
                        // if($this->workoutWithExercise($id))
                        //     $isError = false; 
                    }
                }
            }
             if(!empty($data['tutorialVideo'])){
                // dd(AbExerciseVideo::all());
                if($exercises->save()){
                    $id = $exercises->id;
                    $timestamp = Carbon::now();
                    $tutorialVideo[] = ['aei_exercise_id'=>$id, 'aei_video_name'=>$data['tutorialVideo'],'type'=> 0 ,'created_at'=>$timestamp, 'updated_at'=>$timestamp];  

                    if(AbExerciseVideo::insert($tutorialVideo)){
                        /*AbWorkoutExercise::where('exercise_id',$id)->forceDelete();
                        if($this->storeExercisesWorkouts($id, $data))*/
                        // if($this->workoutWithExercise($id))
                        //     $isError = false; 
                    }
                }
            }
        }
        if($isError){
            $msg['status'] = 'error';
            return json_encode($msg);
        }
        else{
            $msg['status'] = 'added';
            return json_encode($msg);
        }
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return Response
    **/
    public function show($id){

        $exercise = Exercise::with('exeimages')->where('id',$id)->first();

        return view('ActivityBuilder.show', compact('exercise'));  
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return Response
    **/
    public function edit($id){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'edit-exercise'))
            abort(404);

        $data = $this->getExercisesOptions();
        $workouts = AbWorkout::select('id','name')->get();
        $exercises = Exercise::with('exeimages','exevideos')->where('id',$id)->first();
        $programVideo = AbExerciseVideo::where('aei_exercise_id',$id)->where('type',1)->first();
        $tutorialVideo = AbExerciseVideo::where('aei_exercise_id',$id)->where('type',0)->first();


        $workoutData = [];
        foreach ($workouts as $workout) {
           $workoutData[$workout->id] = $workout->name;
        }

        return view('ActivityBuilder.edit', compact('exercises','data','programVideo','tutorialVideo'));
    }


    public function clone($id){
        if(!Session::has('businessId'))
            abort(404);

        $data = $this->getExercisesOptions();
        $workouts = AbWorkout::select('id','name')->get();
        $exercises = Exercise::with('exeimages','exevideos')->where('id',$id)->first();

        $workoutData = [];
        foreach ($workouts as $workout) {
           $workoutData[$workout->id] = $workout->name;
        }

        return view('ActivityBuilder.clone', compact('exercises','data'));
    }


    /**
     * Update the specified resource in storage.
     * @param  int  $id
     * @return Response
    **/
    public function update($id, Request $request){
        $isError = true;
        $exercises = Exercise::find($id);
        $businessId = Session::get('businessId');

        $data = $request->all();
        ksort($data);
        if(count($data)){
            $exercises->name             = $data['name'];
            $exercises->sub_heading      = $data['sub_heading'];
            $exercises->alsoname         = $data['alsoname'];
            $exercises->equipment        = $data['equipment'];
            $exercises->ability          = $data['ability'];
            $exercises->bodypart         = implode(',',$data['bodypart']);
            $exercises->exerciseTypeID   = $data['exerciseTypeID'];
            $exercises->exerciseDesc     = $data['exerciseDesc'];
            $exercises->movement_load    = $data['movement_load'];
            $exercises->movement_pattern = $data['movement_pattern'];
            $exercises->movement_type    = $data['movement_type'];
            $exercises->listing_status   = $data["listing_status"];
            $exercises->equipmentextra   = implode(',',$data['equipmentextra']);
            $exercises->patternExtra     = implode(',',$data['patternExtra']);

            
            if(array_key_exists('muscles', $data))
                $exercises->muscles = $data['muscles'];
            if(array_key_exists('benifits', $data))
                $exercises->benifits = $data['benifits'];
            if(array_key_exists('cues', $data))
                $exercises->cues = $data['cues'];
            if(array_key_exists('movement_desc', $data))
                $exercises->movement_desc = $data['movement_desc'];
            if(array_key_exists('common_mistekes', $data))
                $exercises->common_mistekes = $data['common_mistekes'];
            if(array_key_exists('progress', $data))
                $exercises->progress = $data['progress'];

            $img=[];
            foreach ($data as $key => $value) {
                if(strpos($key, 'expic') !== false)
                    $img[] = $value;
                elseif((strpos($key, 'exercisePicture') !== false) && $value != '')
                    $img[] = $value;
            }
            if(count($img)){
                if($exercises->save()){
                    AbExerciseImage::where('aei_exercise_id',$id)->forceDelete();
                    $exerciseImg = [];
                    foreach ($img as  $value) {
                        $timestamp = Carbon::now();
                        $exerciseImg[] = ['aei_exercise_id'=>$id, 'aei_image_name'=>$value, 'created_at'=>$timestamp, 'updated_at'=>$timestamp];  
                    }
                    if(AbExerciseImage::insert($exerciseImg)){
                        //update exercise workout
                        AbWorkoutExercise::where('exercise_id',$id)->forceDelete();
                        /*if($this->storeExercisesWorkouts($id, $data))*/
                        if($this->workoutWithExercise($id))
                            $isError = false; 
                    }
                }
            }

        
            if(isset($data['exerciseVideo']) || !empty($data['thumbnailProgram'])){
                // dd(AbExerciseVideo::all());
                if($exercises->save()){
                    $id = $exercises->id;
                    $timestamp = Carbon::now();
                    AbExerciseVideo::where('aei_exercise_id',$id)->where('type',1)->forceDelete();
                    if($data['exerciseVideo'] != ""){
                        $exe = AbExerciseVideo::create(['aei_exercise_id'=>$id, 'aei_video_name'=>$data['exerciseVideo'],'thumbnail_program' => $data['thumbnailProgram'],'type' =>1, 'created_at'=>$timestamp, 'updated_at'=>$timestamp]);
                    }else if($data['thumbnailProgram'] != ""){
                        $exe = AbExerciseVideo::create(['aei_exercise_id'=>$id,'thumbnail_program' => $data['thumbnailProgram'],'type' =>1, 'created_at'=>$timestamp, 'updated_at'=>$timestamp]);
                    }
                    else{
                        AbExerciseVideo::where('aei_exercise_id',$exercises->id)->where('type',1)->update([
                            'thumbnail_program' => $data['thumbnailProgram']
                        ]);
                    }

  
                }
            }else{
                AbExerciseVideo::where('aei_exercise_id',$exercises->id)->where('type','1')->update([
                    'thumbnail_program' => $data['thumbnailProgram']
                ]);
            }
            
            if(isset($data['tutorialVideo'])){
                // dd(AbExerciseVideo::all());
                if($exercises->save()){
                    $id = $exercises->id;
                    $timestamp = Carbon::now();
                    AbExerciseVideo::where('aei_exercise_id',$id)->where('type', 0)->forceDelete();
                    
                    if($data['tutorialVideo'] != ''){
                        AbExerciseVideo::create(['aei_exercise_id'=>$id, 'aei_video_name'=>$data['tutorialVideo'],'type'=> 0 ,'created_at'=>$timestamp, 'updated_at'=>$timestamp]);
                    }
                }
            }
        }
        if($isError){
            $msg['status'] = 'error';
            return json_encode($msg);
        }
        else{
            $msg['status'] = 'added';
            return json_encode($msg);
        }
    }

    /**
     * Remove the specified exercise from storage.
     * @param  int  $id
     * @return Response
     */
    public function destroy($id){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'delete-exercise'))
            abort(404);

        $exercise = Exercise::find($id);
        $exercise->delete();
        return redirect()->back()->with('message', 'success|Exercise has been deleted successfully.');
    }


    /** 
     * File uplode
     * @param file
     * @return response
    **/
    public function uploadFile(Request $request){
        $exeImage = AbExerciseImage::find($request->id);
        if(count($exeImage)){
            $exeImage->update(array('aei_image_name' => $request->photoName));
            return url('/uploads/thumb_'.$request->photoName);
        }
        return '';
    }

}
