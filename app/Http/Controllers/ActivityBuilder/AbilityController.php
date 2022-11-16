<?php
namespace App\Http\Controllers\ActivityBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\AbAbility;


class AbilityController extends Controller{
    public function index(){
        $return = [];

        $items = AbAbility::select('id', 'name')->get();
        if($items->count()){
            foreach($items as $item){
                $return[] = ['id'=>$item->id, 'name'=>$item->name];
            }
        }

        return json_encode($return);
    }

    public function destroy($id){
        $item = AbAbility::find($id);
        if($item){
            $item->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $item = AbAbility::find($request->entityId); 
            $item->name=$request->text;
            if($item->save())
                return $item->id;
        }
        else{
            $item = new AbAbility; 
            $item->name=$request->text;
            if($item->save())
                return $item->id;   
        }
        return 'error';
    }

}