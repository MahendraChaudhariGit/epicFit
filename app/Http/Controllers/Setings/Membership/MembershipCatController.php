<?php
namespace App\Http\Controllers\Setings\Membership;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MemberShipCategory;
use Illuminate\Http\Request;
use Session;

class MembershipCatController extends Controller{
    public function index(){
    	$return = [];

    	$cats = MemberShipCategory::OfBusiness()->select('id', 'mc_category_value')->get();
    	if($cats->count()){

    		foreach($cats as $cat){
    			$return[] = ['id'=>$cat->id, 'name'=>$cat->mc_category_value];
    		}
    	}

    	return json_encode($return);
    }

    public function destroy($id){
    	$cat = MemberShipCategory::find($id);
    	if($cat){
    		$cat->delete();
    		return $id;
    	}
    	return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $MemberShipCategory=MemberShipCategory::find($request->entityId); 
            $MemberShipCategory->mc_category_value=$request->text;
            if($MemberShipCategory->save())
                return $MemberShipCategory->id;
        }
        else{
            $MemberShipCategory=new MemberShipCategory; 
            $MemberShipCategory->mc_businesses_id= Session::get('businessId');
            $MemberShipCategory->mc_category_value=$request->text;
            if($MemberShipCategory->save())
                return $MemberShipCategory->id;   
        }
    	return 'error';
    }
}