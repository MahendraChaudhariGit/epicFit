<?php
namespace App\Http\Controllers\MealPlanner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;


use App\MpMealCategory;

class MealCategoryController extends Controller {
    public function index(){
        $return = [];

        $maelCats = MpMealCategory::select('id', 'name','desc')->get();
        if($maelCats->count()){
            foreach($maelCats as $maelCat){
                $return[] = ['id'=>$maelCat->id, 'name'=>$maelCat->name, 'desc'=>$maelCat->desc];
            }
        }
        return json_encode($return);
    }

    public function destroy($id){
        $mealCat = MpMealCategory::find($id);
        if($mealCat){
            $mealCat->delete();
            return $id;
        }
        return 'error';
    }

    public function save(Request $request){
        if($request->entityId != ''){
            $mealCat=MpMealCategory::find($request->entityId); 
            $mealCat->name = $request->text;
            //$mealCat->desc = $request->desc;
            if($mealCat->save())
                return $mealCat->id;
        }
        else{
            $mealCat=new MpMealCategory; 
            $mealCat->name=$request->text;
            //$mealCat->desc=$request->desc;
            if($mealCat->save())
                return $mealCat->id;   
        }
        return 'error';
    }
}

