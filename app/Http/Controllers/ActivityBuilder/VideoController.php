<?php

namespace App\Http\Controllers\ActivityBuilder;

use App\AbWorkout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Session;
use Auth;
use App\ActivityVideo;
use App\ActivityVideoMovement;
use App\Http\Traits\HelperTrait;
use Throwable;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller{
    use HelperTrait;
    private $cookieSlug = 'videos';
    /**
     * Display a listing of the resource.
     * @param void
     * @return Response
    **/
    public function index(Request $request){
        $filter = $request->get('filter');
        $search = $request->get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        $abWorkouts = ["" => '--Select--']+AbWorkout::pluck('desc','id')->toArray();
        if($filter){
            $videos = ActivityVideo::with('workout')
                ->where('business_id',Session::get('businessId'))
                ->Where('workout_id', $filter)
                ->paginate($length);
        return view('ActivityBuilder.videos.index', compact('videos','abWorkouts'));  

        }
        if($search){
            $videos = ActivityVideo::with('workout')
                ->where('business_id',Session::get('businessId'))
                ->where(function($query) use ($search){
                    $query->orWhere('title','LIKE',"%$search%" )
                        ->orWhereHas('workout',function($q) use($search){
                            $q->where('desc','LIKE',"%$search%");
                        });
                })
                ->paginate($length);
        return view('ActivityBuilder.videos.index', compact('videos','abWorkouts'));
        }
        $videos = ActivityVideo::with('workout')->where('business_id',Session::get('businessId'))->paginate($length);
        return view('ActivityBuilder.videos.index', compact('videos','abWorkouts'));  
    }

    /**
     * Show the form for creating a new resource.
     * @param void
     * @return Response
    **/
    public function create(){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'create-exercise'))
            abort(404);
        $abWorkouts = ["" => '--Select--']+AbWorkout::pluck('desc','id')->toArray();
        return view('ActivityBuilder.videos.create',compact('abWorkouts'));
    }

    /**
     * Store a newly created resource in storage.
     * @param input field data
     * @return Response
    **/
    public function store(Request $request){
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'video' => 'required',
            'description' => 'required',
            'workout_id' => 'required'
        ]);
        if ($validation->fails()) {
            $response['status'] = 'error';
            $response['error'] = $validation->errors();
            return response()->json($response);
        }
        try{
            $activityVideo = ActivityVideo::create([
                                'title' => $request->title,
                                'description' => $request->description,
                                'video' => $request->video,
                                'workout_id' => $request->workout_id,
                                'video_duration' => $request->video_duration,
                                'thumbnail' => $request->thumbnail,
                                'business_id' => Session::get('businessId')
                            ]);
            $movementData = $request->movementData;
            $insertData = [];
            foreach($movementData as $item){
                $insertData[] = [
                    'activity_video_id' => $activityVideo->id,
                    'name' => $item['name'],
                    'time' => $item['time']
                ];
            }
            if(count($insertData)){
                ActivityVideoMovement::insert($insertData);
            }
            if($activityVideo){
                $response['status'] = 'ok';
                return response()->json($response);
            }else{
                $response['status'] = 'error';
                $response['error'] = 'Data not inserted';
                return response()->json($response);
            }           
        }catch(\ Throwable $e){
            $response['status'] = 'error';
            $response['error'] = $e->getMessage();
            return response()->json($response);
        }
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return Response
    **/
    public function show($id){

        $video = ActivityVideo::where('id',$id)->first();
        if($video){
            $response = [
                'status' => 'ok',
                'title' => $video->title,
                'videoUrl' => $video->video,
                'description' => $video->description,
                'movementData' => count($video->videoMovements)?$video->videoMovements()->select('name','time','id')->get()->toArray():[],
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Resource not exist'
            ];
        }

        return response()->json($response);  
    }

    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return Response
    **/
    public function edit($id){
        if(!Session::has('businessId') || !Auth::user()->hasPermission(Auth::user(), 'edit-exercise'))
            abort(404);
        $video = ActivityVideo::where('id',$id)->first();
        $abWorkouts = ["" => '--Select--']+AbWorkout::pluck('desc','id')->toArray();
        return view('ActivityBuilder.videos.edit', compact('video','abWorkouts'));
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $id
     * @return Response
    **/
    public function update($id, Request $request){
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'video' => 'required',
            'description' => 'required',
            'workout_id' => 'required'
        ]);
        if ($validation->fails()) {
            $response['status'] = 'error';
            $response['error'] = $validation->errors();
            return response()->json($response);
        }
        $activityVideo = ActivityVideo::find($id);
        try{
            $activityVideo->update([
                                'title' => $request->title,
                                'description' => $request->description,
                                'video' => $request->video,
                                'workout_id' => $request->workout_id,
                                'video_duration' => $request->video_duration,
                                'thumbnail' => $request->thumbnail,
                            ]);
            $movementData = $request->movementData;
            $insertData = [];
            foreach($movementData as $item){
                $insertData[] = [
                    'activity_video_id' => $activityVideo->id,
                    'name' => $item['name'],
                    'time' => $item['time']
                ];
            }
            // Delete Old Data
            ActivityVideoMovement::where('activity_video_id',$activityVideo->id)->delete();
            if(count($insertData)){
                ActivityVideoMovement::insert($insertData);
            }
            $response['status'] = 'ok';
            return response()->json($response);
        }catch(\ Throwable $e){
            $response['status'] = 'error';
            $response['error'] = $e->getMessage();
            return response()->json($response);
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

        $video = ActivityVideo::find($id);
        $video->delete();
        return redirect()->back()->with('message', 'success|Video has been deleted successfully.');
    }

}
