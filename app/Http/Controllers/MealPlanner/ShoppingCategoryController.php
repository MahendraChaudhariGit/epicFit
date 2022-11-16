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

use App\MpShoppingCategory;
use App\MealPlanner\PersonMealLog;


class ShoppingCategoryController extends Controller {

    /**
     * Retrive Shopping Category list.
     *
     *@param   No
     * @return View with param
     */

    public function index(){
        $shopcat = MpShoppingCategory::orderBy('shopping_order', 'ASC')->paginate(20);
        return view('mealplanner.shopping-category.index', compact('shopcat'));
    }


    /**
     * Retrive Create Shopping Category.
     *
     *@param   No
     * @return View
     */

    public function create(){

        return view('mealplanner.shopping-category.create');
    }


    /**
     * Store Shopping Category info.
     *
     *@param  Request $request
     * @return data
     */

    public function store(Request $request){

        $shoppingcat = new MpShoppingCategory;

        $data = $request->all();

        $shoppingcat->created_date = Carbon::now();

        foreach ($data as $key => $value) {
            if( $data[$key] != ''){
        
                $shoppingcat->$key = $data[$key];
            }
        }

        $shoppingcat->save();

        return $data;
    }


    /**
     * Retrive particular Shopping Category info.
     *
     * @param  CategoryId $categoryid
     * @return mealinfo
     */

    public function edit($categoryid){

        $shopCategory = MpShoppingCategory::where('id',$categoryid)->first();
        //dd($mealsCategory);
        return view('mealplanner.shopping-category.edit', compact('shopCategory'));
    }


    /**
     * update particular Shopping category.
     *
     * @param  Request $request
     * @return data
     */

    public function update(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            if( $data[$key] != ''){
        
                $name[$key] = $data[$key];
            }
        }

        $cat= MpShoppingCategory::where('id', $data['record_id'])->first();
        if(count($cat)){
            $cat->update($name);
        }
    
        return $data;
    }


    /**
     * delete particular Shopping Category.
     *
     * @param  Request $request
     * @return Response
     */

    public function delete($id){
        $shoppingcat = MpShoppingCategory::find($id);
    
        if(count($shoppingcat))
            $cat->delete();
        
        return redirect()->back()->with('message', 'success|Shopping Category has been deleted successfully.');
    }

}
