<?php
namespace App\Http\Controllers\Setings\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use App\ClassCat;

class ClassCatController extends Controller{
    public function store(Request $request){
        if(!Auth::user()->hasPermission(Auth::user(), 'create-class-category')){
            if($request->ajax())
                return '0';
            else
                abort(404);
        }

        $classCat = new ClassCat;
        $classCats = $classCat->where('clcat_business_id', $request->ownerId)->get();
        foreach($classCats as $classCatt)
            if($classCatt->clcat_value == $request->value)
                return '0';

        $classCat->clcat_value = $request->value;
        $classCat->clcat_business_id = $request->ownerId;

        $classCat->save();
        return $classCat->clcat_id;        
    }

    public function index(){
        $return = [];

        $cats = ClassCat::OfBusiness()->select('clcat_id', 'clcat_value','clcat_price')->get();
        //$cats = ClassCat::with('business')->select('clcat_id', 'clcat_value')->get();
        if($cats->count()){

            foreach($cats as $cat){
                if($cat->clcat_price == null){
                    $cat->clcat_price = 0;
                }
                $return[] = ['id'=>$cat->clcat_id, 'name'=>$cat->clcat_value, 'price' =>$cat->clcat_price ];
            }
        }

        return json_encode($return);
    }

    public function destroy($id){
        $cat = ClassCat::find($id);
        if($cat){
            $cat->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        if($request->entityId != ''){
            $serviceCat=ClassCat::find($request->entityId); 
            $serviceCat->clcat_value=$request->text;
            $serviceCat->clcat_price=$request->price;
            if($serviceCat->save())
                return $serviceCat->clcat_id;
        }
        else{
            $serviceCat=new ClassCat; 
            $serviceCat->clcat_business_id= Session::get('businessId');
            $serviceCat->clcat_value=$request->text;
            $serviceCat->clcat_price=$request->price;
            if($serviceCat->save())
                return $serviceCat->clcat_id;   
        }
        return 'error';
    }

}