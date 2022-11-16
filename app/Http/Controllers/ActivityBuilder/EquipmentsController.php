<?php
namespace App\Http\Controllers\ActivityBuilder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\AbEquipment;


class EquipmentsController extends Controller{
    public function index(){
        $return = [];

        $items = AbEquipment::select('id', 'eq_name')->get();
        if($items->count()){

            foreach($items as $item){
                $return[] = ['id'=>$item->id, 'name'=>$item->eq_name];
            }
        }

        return json_encode($return);
    }

    public function destroy($id){
        $item = AbEquipment::find($id);
        if($item){
            $item->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $item = AbEquipment::find($request->entityId); 
            $item->eq_name=$request->text;
            if($item->save())
                return $item->id;
        }
        else{
            $item = new AbEquipment; 
            $item->eq_name=$request->text;
            if($item->save())
                return $item->id;   
        }
        return 'error';
    }

}