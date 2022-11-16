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



class ExerciseContoller extends Controller{
    use HelperTrait, ActivityBuilderTrait;
    /**
     * Display a listing of the resource.
     * @param void
     * @return Response
    **/
    public function index(){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'list-exercise'))
            abort(404);

        $exercises = array();
        $search = Input::get('search');
            //$length = $this->getTableLengthFromCookie($this->cookieSlug);
        $length = 10;
            if($search){
                 $exercises = Exercise::where('businessId',Session::get('businessId'))
                                      ->where(function($query) use($search){
                                            $query->orWhere('exerciseDesc', 'like', "%$search%");
                                                  /*->orWhere('sku_id', 'like', "%$search%")
                                                  ->orWhereHas('categories', function($query) use ($search){
                                                        $query->where('cat_name', 'like', "%$search%");
                                                  });*/
                                      })
                                      ->paginate($length);
            }
            else
                $exercises = Exercise::where('businessId',Session::get('businessId'))->paginate($length);
        $exerciseData = $this->getExercisesOptions();
        /*$exercises = Exercise::where('businessId',Session::get('businessId'))->orderBy('id','desc')->get();*/
        return view('ActivityBuilder.index', compact('exercises','exerciseData'));
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
            $exercises->alsoname = $data['alsoname'];
            $exercises->equipment = $data['equipment'];
            $exercises->ability = $data['ability'];
            $exercises->bodypart = implode(',',$data['bodypart']);
            $exercises->exerciseTypeID = $data['exerciseTypeID'];
            $exercises->exerciseDesc = $data['exerciseDesc'];
            $exercises->movement_pattern = $data['movement_pattern'];
            $exercises->movement_type = $data['movement_type'];
            
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
            if(!empty($data['exerciseVideo'])){
                // dd(AbExerciseVideo::all());
                if($exercises->save()){
                    $id = $exercises->id;
                    $timestamp = Carbon::now();
                    $exerciseVideo[] = ['aei_exercise_id'=>$id, 'aei_video_name'=>$data['exerciseVideo'], 'created_at'=>$timestamp, 'updated_at'=>$timestamp];  
                    if(AbExerciseVideo::insert($exerciseVideo)){
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
        $exercises = Exercise::with('exeimages')->where('id',$id)->first();

        $workoutData = [];
        foreach ($workouts as $workout) {
           $workoutData[$workout->id] = $workout->name;
        }

        return view('ActivityBuilder.edit', compact('exercises','data'));
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $id
     * @return Response
    **/
    public function update($id, Request $request){
            
            //     dd($data['exerciseVideo']);
            // if(!empty($data['exerciseVideo'])){
            // }
        $isError = true;
        $exercises = Exercise::find($id);
        $businessId = Session::get('businessId');

        $data = $request->all();
        ksort($data);
        if(count($data)){
            $exercises->name             = $data['name'];
            $exercises->alsoname         = $data['alsoname'];
            $exercises->equipment        = $data['equipment'];
            $exercises->ability          = $data['ability'];
            $exercises->bodypart         = implode(',',$data['bodypart']);
            $exercises->exerciseTypeID   = $data['exerciseTypeID'];
            $exercises->exerciseDesc     = $data['exerciseDesc'];
            $exercises->movement_pattern = $data['movement_pattern'];
            $exercises->movement_type    = $data['movement_type'];
            
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

            if(!empty($data['exerciseVideo'])){
                // dd(AbExerciseVideo::all());
                if($exercises->save()){
                    $id = $exercises->id;
                    $timestamp = Carbon::now();
                    $exerciseVideo[] = ['aei_exercise_id'=>$id, 'aei_video_name'=>$data['exerciseVideo'], 'created_at'=>$timestamp, 'updated_at'=>$timestamp];  
                        AbExerciseVideo::where('aei_exercise_id',$id)->forceDelete();
                    if(AbExerciseVideo::insert($exerciseVideo)){

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
