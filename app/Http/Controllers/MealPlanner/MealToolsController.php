<?php

namespace App\Http\Controllers\MealPlanner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Session;
use Auth;
use DB;

use App\MpMeals;
use App\MpFoods;
use App\MpClientMealplan;
use App\MpMealimages;
use App\MpServingSize;

use App\Http\Traits\HelperTrait;
use App\Http\Traits\MpPlannerTrait;


class MealToolsController extends Controller {
    use HelperTrait, MpPlannerTrait;
    
    /**
     * Show meal calendar 
     *
     * @param 
     * @return 
    */
    public function show(){
        return view('mealplanner.tools.edit');
    }

    /**
     * Get Nutrition data
     *
     * @param
     * @return
     */
    public function nutritionInfo(Request $request){
        $isError = false;
        $response = array('status'=>'error');
        $servId = 0;
        $params = $request->all();
        $foodName = array();
        $index = 0;
        foreach ($params as $key=>$param) {
            $orgName = $param['name'];
            $namePice = explode(",", trim($param['name']));

            $paramArray =  explode(" ", trim($namePice[0]));
            $servId = $param['servid'];

            if(count($paramArray) >= 3){ 
                $size = $paramArray[0];
                if(!$servId)
                    $name = $paramArray[1];
                
                $food = '';
                for ($i=2; $i < count($paramArray); $i++) {
                    $food .= $paramArray[$i].' ';
                }

                $search = "%".trim($food)."%";
                DB::enableQueryLog();
                // get food and serving size from db */

                if(!$servId){
                    $servId = MpServingSize::whereRaw("FIND_IN_SET('$name', tags)")
                                             ->orWhere("name", $name)
                                             ->pluck('id')
                                             ->first();
                }

                if($servId){
                    $foodData = MpFoods::whereRaw("FIND_IN_SET('$servId', serving_size_child)")
                            ->orWhere("serving_size", $servId)
                            ->where('name','like',"$search")
                            ->first();
                }

                
                if($servId && count($foodData) && $size > 0){
                    if($servId)
                        $dbSize = MpServingSize::find($servId);
                    else
                        $dbSize = $foodData->servingsize()->first();

                    $unitSize = ($dbSize->size / $size); 
                    $response['food'][$index] = array_merge(['display_name'=>$orgName,'key'=>$key], $this->getFoodData($foodData, $unitSize, $size, $servId));
                }
                else{
                    $isError = true;
                    $response['food'][$index] = ['msg'=>'Food Not Found. Please Edit.','display_name'=>$orgName,'key'=>$key];
                }
            }
            else{
                $isError = true;
                $response['food'][$index] = ['msg'=>'Food Not Found. Please Edit.','display_name'=>$orgName,'key'=>$key];
            }

            $index++;
        }

        $response['sevrSize'] = $this->getServSize();
        if($isError)
            $response['status'] = 'error';
        else
            $response['status'] = 'success';

        return json_encode($response);
    }


    /**
     * Get
     * 
     * @param
     * @return
    */
    protected function getFoodData($value, $unitSize, $size, $servId){
        $nutrInfo = array();
        $nutrInfo['id'] = $value->id;
        $nutrInfo['name'] = $value->name;
        if($servId)
            $nutrInfo['serving_id'] = $servId;
        else
            $nutrInfo['serving_id'] = $value->servingsize->id;

        $nutrInfo['serving_size'] = $size;
        $nutrInfo['energy'] = ceil(($value->energ_kcal / $unitSize));
        $nutrInfo['protein'] = ($value->protein / $unitSize);          
        $nutrInfo['carbohydrate'] = ($value->carbohydrate / $unitSize);
        $nutrInfo['fiber'] = ($value->fiber / $unitSize);
        $nutrInfo['sugar'] = ($value->sugar / $unitSize);
        $nutrInfo['calcium'] = ($value->calcium / $unitSize);
        $nutrInfo['iron'] = ($value->iron / $unitSize);
        $nutrInfo['potassium'] = ($value->potassium  / $unitSize);
        $nutrInfo['vit_dmcg'] = ($value->vit_dmcg  / $unitSize);
        $nutrInfo['fa_sat'] = ($value->fa_sat / $unitSize);
        $nutrInfo['fa_mono'] = ($value->fa_mono / $unitSize);
        $nutrInfo['fa_poly'] = ($value->fa_poly / $unitSize);
        $nutrInfo['cholestrl'] = ($value->cholestrl / $unitSize);
        $nutrInfo['sodium'] = ($value->sodium / $unitSize);
        return $nutrInfo;
    } 

    /**
     * Get Existing food data 
     *
     * @param
     * @return
    */
    public function getFood(Request $request){
        $response = array();
        $name = $request->text;
        $foods = MpFoods::select('id','name')->where('name','like',"%$name%")->orderBy('id', 'asc')->get();
         if($foods->count()) 
            foreach ($foods as $key => $value)
                $data[$key] = $foods[$key];
        
        return response()->json($data);
    }

    /**
     * Get Serving size 
     * 
     * @param
     * @return string 
    **/
    protected function getServSize(){
        $servingSize = MpServingSize::all();
        $servSize = array();
        if(count($servingSize)){
            foreach ($servingSize as $serving) {
                $servSize[$serving->id] = $serving->name.' '.$serving->range.' '.$serving->quantity.$serving->units;
            } 
        }
        return $servSize;
    }


}
