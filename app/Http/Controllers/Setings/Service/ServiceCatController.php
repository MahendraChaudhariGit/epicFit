<?php
namespace App\Http\Controllers\Setings\Service;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ServiceCat;
use App\ServiceType;
use Illuminate\Http\Request;
use Session;

class ServiceCatController extends Controller{
    public function index(){
    	$return = [];

    	$cats = ServiceCat::OfBusiness()->select('sc_id', 'sc_value')->get();
    	if($cats->count()){

    		foreach($cats as $cat){
    			$return[] = ['id'=>$cat->sc_id, 'name'=>$cat->sc_value];
    		}
    	}

    	return json_encode($return);
    }

    public function destroy($id){
    	$cat = ServiceCat::find($id);
    	if($cat){
    		$cat->delete();
    		return $id;
    	}
    	return 'error';
    }

    public function save(Request $request){
        
        if($request->entityId != ''){
            $serviceCat=ServiceCat::find($request->entityId); 
            $serviceCat->sc_value=$request->text;
            if($serviceCat->save())
                return $serviceCat->sc_id;
        }
        else{
            $serviceCat=new ServiceCat; 
            $serviceCat->sc_business_id= Session::get('businessId');
            $serviceCat->sc_value=$request->text;
            if($serviceCat->save())
                return $serviceCat->sc_id;   
        }
    	return 'error';
    }

    /*
    *@Start 
    * Manage service type 
    *
    */
    public function typeIndex(){
        $return = [];

        $cats = ServiceType::OfBusiness()->select('st_id', 'st_value')->get();
        if($cats->count()){
            foreach($cats as $cat){
                $return[] = ['id'=>$cat->st_id, 'name'=>$cat->st_value];
            }
        }

        return json_encode($return);
    }

    public function typeDestroy($id){
        $cat = ServiceType::find($id);
        if($cat){
            $cat->delete();
            return $id;
        }
        return 'error';
    }

    public function typeSave(Request $request){
        
        if($request->entityId != ''){
            $serviceCat=ServiceType::find($request->entityId); 
            $serviceCat->st_value=$request->text;
            if($serviceCat->save())
                return $serviceCat->st_id;
        }
        else{
            $serviceCat=new ServiceType; 
            $serviceCat->st_business_id= Session::get('businessId');
            $serviceCat->st_value=$request->text;
            if($serviceCat->save())
                return $serviceCat->st_id;   
        }
        return 'error';
    }
}