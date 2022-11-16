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

use App\Contact;
use App\MpFoods;
use App\MpServingSize;
use App\MealPlanner\PersonMealLog;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\MpPlannerTrait;


class FoodPlannerController extends Controller {
    use HelperTrait, MpPlannerTrait;
    private $cookieSlug = 'foodplanner';


    /**
     * Retrive Food list.
     *
     * @param   No
     * @return View with param
     */
    public function index(Request $request){
        $business_id = Session::get('businessId');
        $search = $request->get('search');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);

        if($search){
            $foods = MpFoods::where('business_id', $business_id)
                        ->where(function($query) use($search){
                            $query->orWhere('name', 'like', "%$search%")
                                  ->orWhere('description', 'like', "%$search%");
                        })
                        ->orderBy('id', 'asc')
                        ->paginate($length);
        }
        else
            $foods = MpFoods::where('business_id', $business_id)->orderBy('id', 'asc')->paginate($length);

        if(Session::get('hostname') == 'crm')
            return view('mealplanner.food.index', compact('foods'));
        else
            return view('Result.mealplanner.food.index', compact('foods')); 
    }


    /**
     * Retrive Create Food.
     *
     *@param   No
     * @return View with param
     */
    public function create(){
        $servingSize = $this->getServingSize();
        if(Session::get('hostname') == 'crm')
            return view('mealplanner.food.create',compact('servingSize'));
        else
            return view('Result.mealplanner.food.create',compact('servingSize'));
    }


    /**
     * Store food info.
     *
     * @param  FoodRequest $request
     * @return data
     */
    public function store(Request $request){
        $msg['status'] = 'error';
        $data = $request->all(); 
        $mpFoods = new MpFoods;    
        $mpFoods->business_id = Session::get('businessId');
        foreach ($data as $key => $value) {
            if($key != 'food_id' && $key != '_token' && $key != 'pre_food_id' && $key != 'pre_serv_id')
                $mpFoods->$key = $value;  
        }

        /* Suppelier id */
        $supplierId = 0;
        if($request->has('supplier') && $request->supplier != ''){
            $supplierId = Contact::where('business_id', Session::get('businessId'))->where('contact_name', 'like', "%$request->supplier%")->pluck('id')->first();
        }
        $mpFoods->supplier_id = $supplierId;

        if($mpFoods->save())
            $msg['status'] = 'success';

        return json_encode($msg);
    }

    /**
     * Retrive particular food info.
     *
     * @param  FoodId $foodid
     * @return foodinfo
     */
    public function edit($foodid){
        $servingSize = $this->getServingSize();
        $foodInfo = MpFoods::findOrFail($foodid);
        
        if(Session::get('hostname') == 'crm')
            return view('mealplanner.food.edit', compact('foodInfo','servingSize'));
        else
            return view('Result.mealplanner.food.edit', compact('foodInfo','servingSize'));
    }

    /**
     * update particular Food.
     *
     * @param  FoodRequest $request
     * @return response
     */
    public function update($id, Request $request){
        $msg['status'] = 'error';
        $updatedData = array();
        $data = $request->all();    
        foreach ($data as $key => $value) {
            if($key != 'food_id' && $key != '_token' && $key != 'pre_food_id' && $key != 'pre_serv_id')
                $updatedData[$key] = $value;  
        }

        /* Suppelier id */
        $supplierId = 0;
        if($request->has('supplier') && $request->supplier != ''){
            $supplierId = Contact::where('business_id', Session::get('businessId'))->where('contact_name', 'like', "%$request->supplier%")->pluck('id')->first();
        }
        $updatedData['supplier_id'] = $supplierId;

        if(MpFoods::where('id', $id)->update($updatedData))
            $msg['status'] = 'success';

        return json_encode($msg);
    }

    /**
     * delete particular food.
     *
     * @param  FoodRequest $request
     * @return Response
     */
    public function delete($id){
        $food = MpFoods::find($id);
        if(count($food))
            $food->delete();
        return redirect()->back()->with('message', 'success|Food has been deleted successfully.');
    }


    /**
     * Get Existing food data 
     *
     * @param
     * @return
    */
    public function getFoodData(Request $request){
        $response = array();
        $name = $request->name;
        $serv_size = (int)$request->serv_size;
        $foods = MpFoods::where('serving_size', $serv_size)->where('name','like',"$name%")->orderBy('id', 'asc')->first();
        return json_encode($foods);
    }

}
