<?php
namespace App\Http\Controllers\Setings\Membership;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\IncomeCategory;
use Illuminate\Http\Request;
use Session;

class IncomeCatController  extends Controller{
    public function index(){
    	$return = [];

    	$cats = IncomeCategory::OfBusiness()->select('id', 'category_name')->get();
    	if($cats->count()){

    		foreach($cats as $cat){
    			$return[] = ['id'=>$cat->id, 'name'=>$cat->category_name];
    		}
    	}

    	return json_encode($return);
    }

    public function destroy($id){
    	$cat = IncomeCategory::find($id);
    	if($cat){
    		$cat->delete();
    		return $id;
    	}
    	return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $IncomeCategory=IncomeCategory::find($request->entityId); 
            $IncomeCategory->category_name=$request->text;
            if($IncomeCategory->save())
                return $IncomeCategory->id;
        }
        else{
            $IncomeCategory=new IncomeCategory; 
            $IncomeCategory->business_id= Session::get('businessId');
            $IncomeCategory->category_name=$request->text;
            if($IncomeCategory->save())
                return $IncomeCategory->id;   
        }
    	return 'error';
    }
}