<?php
namespace App\Http\Controllers\ActivityBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\AbExerciseMovementPattern;


class MovementController extends Controller{
    public function index(){
        $return = [];

        $items = AbExerciseMovementPattern::select('id', 'pattern_name')->get();
        if($items->count()){
            foreach($items as $item){
                $return[] = ['id'=>$item->id, 'name'=>$item->pattern_name];
            }
        }

        return json_encode($return);
    }

    public function destroy($id){
        $item = AbExerciseMovementPattern::find($id);
        if($item){
            $item->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $item = AbExerciseMovementPattern::find($request->entityId); 
            $item->pattern_name=$request->text;
            if($item->save())
                return $item->id;
        }
        else{
            $item = new AbExerciseMovementPattern; 
            $item->pattern_name=$request->text;
            if($item->save())
                return $item->id;   
        }
        return 'error';
    }

}