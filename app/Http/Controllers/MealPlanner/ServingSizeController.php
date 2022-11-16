<?php

namespace App\Http\Controllers\MealPlanner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;


use App\MpServingSize;

class ServingSizeController extends Controller {
    public function index(){
        $return = [];

        $servs = MpServingSize::select('id', 'name','size','tags','quantity','range','units')->where('isEdit', 0)->get();
        if($servs->count()){
            foreach($servs as $serv){
                $return[] = ['id'=>$serv->id, 'name'=>$serv->name, 'size'=>$serv->size, 'quantity'=>$serv->quantity, 'other'=>$serv->range, 'tags'=>$serv->tags,'units'=>$serv->units];
            }
        }
        return json_encode($return);
    }

    public function destroy($id){
        $serv = MpServingSize::find($id);
        if($serv){
            $serv->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        if($request->entityId != ''){
            $serv = MpServingSize::find($request->entityId); 
            $serv->name = $request->text;
            $serv->size = $request->size;
            $serv->quantity = $request->quantity;
            if($request->units != '')
                $serv->units = $request->units;
            else
                $serv->units = '';

            if($request->other != '')
                $serv->range = $request->other;
            else
                $serv->range = '';

            if($request->tags != '')
                $serv->tags = $request->tags;
            else
                $serv->tags = strtolower($request->text);

            if($serv->save())
                return $serv->id;
        }
        else{
            $serv = new MpServingSize; 
            $serv->name = $request->text;
            $serv->size = $request->size;
            $serv->quantity = $request->quantity;
            if($request->units != '')
                $serv->units = $request->units;
            else
                $serv->units = '';

            if($request->other != '')
                $serv->range = $request->other;
            else
                $serv->range = '';

            if($request->tags != '')
                $serv->tags = $request->tags.','.strtolower($request->text);
            else
                $serv->tags = strtolower($request->text);
            
            if($serv->save())
                return $serv->id;   
        }
        return 'error';
    }
}
