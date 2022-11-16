<?php
namespace App\Http\Controllers\Setings\Business;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BusinessType;
use Illuminate\Http\Request;
use Session;
use Auth;
class BusinessTypeController extends Controller{
    public function index(){
    	$return = [];

    	$types = BusinessType::where(function($query){
                                     $query->where('bt_user_id', Auth::id());
                                     /*$query->orWhere('bt_user_id', 0);*/
                                 })
                                ->select('bt_id', 'bt_value')
                                ->get();
        if($types->count()){
    		foreach($types as $type){
    			$return[] = ['id'=>$type->bt_id, 'name'=>$type->bt_value];
    		}
    	}

    	return json_encode($return);
    }

    public function destroy($id){
    	$cat = BusinessType::find($id);
    	if($cat){
    		$cat->delete();
    		return $id;
    	}
    	return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $serviceCat=BusinessType::find($request->entityId); 
            $serviceCat->bt_value=$request->text;
            if($serviceCat->save())
                return $serviceCat->bt_id;
        }
        else{
            $serviceCat=new BusinessType; 
            $serviceCat->bt_user_id= Auth::id();
            $serviceCat->bt_value=$request->text;
            if($serviceCat->save())
                return $serviceCat->bt_id;   
        }
    	return 'error';
    }

}