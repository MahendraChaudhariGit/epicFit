<?php
namespace App\Http\Controllers\ActivityBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\AbExerciseType;


class ExerciseTypeController extends Controller{
    public function index(){
        $return = [];

        $items = AbExerciseType::select('id', 'type_name')->get();
        if($items->count()){
            foreach($items as $item){
                $return[] = ['id'=>$item->id, 'name'=>$item->type_name];
            }
        }

        return json_encode($return);
    }

    public function destroy($id){
        $item = AbExerciseType::find($id);
        if($item){
            $item->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $item = AbExerciseType::find($request->entityId); 
            $item->type_name=$request->text;
            if($item->save())
                return $item->id;
        }
        else{
            $item = new AbExerciseType; 
            $item->type_name=$request->text;
            if($item->save())
                return $item->id;   
        }
        return 'error';
    }

}