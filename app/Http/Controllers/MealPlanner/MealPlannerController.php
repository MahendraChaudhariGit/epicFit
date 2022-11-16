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
use App\ClientMenu;
use App\Staff;
use App\MpTags;
use App\MpMeals;
use App\MpMealFood;
use App\MpFoods;
use App\MpMealCategory;
use App\MpServingSize;
use App\MpMealimages;
use App\MealPlanner\PersonMealLog;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\MpPlannerTrait;
use PDF;
use View;
use Response;
use App\{MainCategory,SubCategory, MpMealMainCategory, MpIngredientTag, 
    MpMealIngredientSet,MpMealPreparation, MpMealEdamamIngredient, MpMealCat,ShoppingList,
     MpClientMealplan, Clients,RecipeReview,ReplyRecipeReview};
use Illuminate\Support\Facades\Validator;
use Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MealPlannerController extends Controller {
    use HelperTrait, MpPlannerTrait;
    private $cookieSlug = 'mealplanner';


    /**
     * Retrive Meal list.
     *
     * @param  Void
     * @return View with param
     */
    public function index(Request $request){
        $business_id = Session::get('businessId');
        $search = $request->get('search');
        $filter = $request->get('filter');
        $length = $this->getTableLengthFromCookie($this->cookieSlug);
        $keywords   = explode(',', $search);
        $ids = [];
            if($search){
                if(count($keywords)){
                    foreach ($keywords as $keyword) {
                        $keyword = trim($keyword);
                    $mealsQuery = MpMeals::with('categories','staff')
                    ->where('business_id', $business_id)
                    ->orderBy('id', 'DESC');
                       $mealsTeamp = $mealsQuery->where(function($query) use($keyword){
                                $query->orWhere('name', 'like', "%$keyword%")
                                    ->orWhere('description', 'like', "%$keyword%")
                                    ->orWhereHas('categories', function($query) use ($keyword){
                                            $query->where('name', 'like', "%$keyword%");
                                    })
                                    
                                    ->orWhereHas('staff',function($query) use ($keyword){

                                        $query->where(DB::raw('concat(first_name," ",last_name)'), 'like', "%$keyword%");
                                    });
                            })->get();

                           if(count($mealsTeamp)){
                        foreach ($mealsTeamp as $meal) {
                            $ids[] =  $meal->id;

                        }

                       }
                    }
                    $ids = array_unique($ids);
                    $meals = MpMeals::with('categories','staff')
                    ->where('business_id', $business_id)
                    ->orderBy('id', 'DESC')->whereIn('id',$ids)->paginate($length); 
                }
            }
            else if($filter){
                $mealsQuery = MpMeals::with('categories','staff')
                ->where('business_id', $business_id)
                ->orderBy('id', 'DESC');
                $meals = $mealsQuery->WhereHas('categories', function($query) use ($filter){
                                    $query->where('name', 'like', "%$filter%");
                            })->paginate($length);
            }
        else
            $meals = MpMeals::with('categories','staff')->where('business_id', $business_id)->orderBy('id', 'DESC')->paginate($length);
        $mealCategories = MpMealCategory::all();
        if(Session::get('hostname') == 'crm')
            return view('mealplanner.meal.index', compact('meals','mealCategories'));
        else
            return view('Result.mealplanner.meal.index', compact('meals'));
    }

    /**
     * Validate Meal name
     */
    public function validateName(Request $request){
        $mealName = $request->name;
        if(isset($request->mealId) && $request->mealId != ""){
            $ifNameExist = MpMeals::where('id','!=',$request->mealId)->where('name',$mealName)->exists();
        }else{
            $ifNameExist = MpMeals::where('name',$mealName)->exists();
        }
        $response = [
            'ifNameExist' => $ifNameExist
        ];
        return response()->json($response);
    }

    /**
     * Retrive Create Meal.
     *
     * @param   void
     * @return View with param
     */
    public function create(){
        $mealsCategory = $this->mealCategory();
        $servingSize = $this->getServingSize();
        $allStaffs = Staff::ofBusiness()->get();
        $staffs = array("" => "--Select--");
        // $main_cat = MainCategory::with('subCategory')->get();
        foreach ($allStaffs as $staff){
            $staffs[$staff->id]= $staff->fullName;
        }
        $not_add_main_cat = ['MEAL','POPULAR'];
        $main_cat = MainCategory::whereNotIn('name',$not_add_main_cat)->with('subCategory')->get();
        $measurement_array =  MpMeals::$ingredientMeasurement;
       //performing sort
            sort($measurement_array);
            $array_length = count($measurement_array);
            //array after sorting
            for($i=0;$i<$array_length;$i++)
            {
                $measurement_array[i];
            }
      
        if(Session::get('hostname') == 'crm')
            return view('mealplanner.meal.create', compact('mealsCategory','servingSize','staffs','main_cat','measurement_array'));
        else
            return view('Result.mealplanner.meal.create', compact('mealsCategory','servingSize'));
    }


    /**
     * Store meal info.
     *
     * @param  mealsRequest $request
     * @return data
     */
    public function store(Request $request){
        $msg['status'] = 'error';
        $data = $request->all();
    //    dd( $data);
        $prep_time = [];
        $prep_time['prep_hrs'] = $data['prep_time_hrs']?$data['prep_time_hrs']:0;
        $prep_time['prep_mins'] = $data['prep_time_mins']?$data['prep_time_mins']:0;
        $cook_time = [];
        $cook_time['cook_hrs'] = $data['cook_time_hrs']?$data['cook_time_hrs']:0;
        $cook_time['cook_mins'] = $data['cook_time_mins']?$data['cook_time_mins']:0;
        /* chart */
        $total_nutrient_kcal = [];
        $total_nutrient_kcal['total_energ_kcal'] = $data['total_energ_kcal']?$data['total_energ_kcal']:0;
        $total_nutrient_kcal['cal_from_protein'] = $data['cal_from_protein']?$data['cal_from_protein']:0;
        $total_nutrient_kcal['cal_from_fat'] = $data['cal_from_fat']?$data['cal_from_fat']:0;
        $total_nutrient_kcal['cal_from_carbs'] = $data['cal_from_carbs']?$data['cal_from_carbs']:0;
         /* chart */
        $meal = new MpMeals;
        $meal->business_id = Session::get('businessId');
        $meal->name = $data["name"];
        $meal->description = $data["description"];
        $meal->tips = $data["tips"];
        $meal->method = $data["method"];
        // $meal->ingredients = $data["ingredients"];
        $meal->ingredients = isset($data["ingr_textarea_1"])?$data["ingr_textarea_1"]:null;
        $meal->second_ingredients = isset($data["ingr_textarea_2"])?$data["ingr_textarea_2"]:null;

        $meal->staff_id = $data["staff_id"];
        $meal->listing_status = $data["listing_status"];
        $meal->nutritional_information = json_encode($data["nutritionalInfo"]);
        /*$meal->serving_id = $data["serving_id"];*/
        $meal->serves = $data["serves"];
        $meal->time = json_encode($prep_time); // prep time
        $meal->cook_time = json_encode($cook_time);
        $meal->nutrient_kcal = json_encode($total_nutrient_kcal);
        $meal->ingredient_set_no =  $data["reipe_format"];
        if($data['reipe_format']== 2 || $data['reipe_format']== 3){
            $ingredient_set_name = [];
            $ingredient_set_name['set_name_1'] = isset($data['ingredient_name_1'])?$data['ingredient_name_1']:null;
            $ingredient_set_name['set_name_2'] = isset($data['ingredient_name_2'])?$data['ingredient_name_2']:null;
            $meal->ingredient_set_name = json_encode($ingredient_set_name);
         } else {
            $meal->ingredient_set_name =null;
         }
        // if($request->has('time') && $request->time != '')
        //     $meal->time = $data["time"];
        // else
        //     $meal->time = '';

        // if($request->has('cook_time') && $request->cook_time != '')
        //     $meal->cook_time = $data["cook_time"];
        // else
        //     $meal->cook_time = '';    

        $img = array();
        foreach ($data as $key => $value) {
            if(strpos($key, 'mealpic') !== false)
                $img[] = $value;
            elseif((strpos($key, 'mealPicture') !== false) && $value != '')
                $img[] = $value;
        }

        $meal->save();

        /* Attach category */
        if(count($request->category_id))
            $meal->categories()->attach($request->category_id);
        
        /* Insert image */
        if(count($img)){
            $mealImages = [];
            foreach ($img as  $value) {
                $mealImages[] = ['mmi_meal_id'=>$meal->id, 'mmi_img_name'=>$value];  
            }
            if(MpMealimages::insert($mealImages))
                $msg['status'] = 'success';
        }

        /* Insert meal tags */
        if($data['tags'] != ''){
            $tags = json_decode($data['tags']);
            if(count($tags)){
                $mealTags = [];
                foreach ($tags as $tag) {
                    $mealTags [] = ['mp_id'=>$meal->id, 'mp_type'=>'meal','mp_tag_name'=>$tag];
                }
                MpTags::insert($mealTags);
            }
        }

        /* main category and sub category */
         $main_cat = $data['main_category_id'];
        foreach($main_cat as $key => $cat){
            foreach($cat as $sub_cat){
              $meal_main_cat = MpMealMainCategory::create([
                  'mp_meal_id'=> $meal->id,
                  'main_category_id'=> $key,
                  'sub_category_id'=>$sub_cat
              ]);
            }
         }

         if($data['ingredient_tags'] != ''){
            $ingredient_tags = json_decode($data['ingredient_tags']);
            if(count($ingredient_tags)){
                foreach ($ingredient_tags as $tag) {
                    $save_ingredient_tags = MpIngredientTag::create([
                        'mp_meal_id'=> $meal->id,
                        'name'=> $tag,
                    ]);
                 }
              }
         }

                 /* ingredient data */
                //  MpMealIngredientSet::where('mp_meal_id',$id)->forceDelete();
                 if(isset($data['ingredient_data_1'])){
                        foreach ($data['ingredient_data_1'] as $ingr_data_1) {
                            $save_ingredient_tags = MpMealIngredientSet::create([
                                'mp_meal_id'=> $meal->id,
                                'item'=> $ingr_data_1['item'],
                                'measurement'=>$ingr_data_1['measure'],
                                'qty'=>$ingr_data_1['qty'],
                                'part'=>1,
                            ]);
                         }  
                    }
            if( $data["reipe_format"] == 3 || $data["reipe_format"] == 2){
                 if(isset($data['ingredient_data_2'])){
                    foreach ($data['ingredient_data_2'] as $ingr_data_2) {           
                        $save_ingredient_tags = MpMealIngredientSet::create([
                            'mp_meal_id'=> $meal->id,
                            'item'=> $ingr_data_2['item'],
                            'measurement'=>$ingr_data_2['measure'],
                            'qty'=>$ingr_data_2['qty'],
                            'part'=>2,
                        ]);
                     }  
                  }
                }
            /*  */
            //  MpMealPreparation::where('mp_meal_id',$id)->forceDelete();
             if(isset($data['preparation_data_1'])){
                foreach ($data['preparation_data_1'] as $preparation_1) {   
         
                    $save_ingredient_tags = MpMealPreparation::create([
                        'mp_meal_id'=> $meal->id,
                        'description'=> $preparation_1['text'],
                        'part'=>1,
                    ]);
                 }  
              }
              if( $data["reipe_format"] == 3){
              if(isset($data['preparation_data_2'])){
                foreach ($data['preparation_data_2'] as $preparation_2) {           
                    $save_ingredient_tags = MpMealPreparation::create([
                        'mp_meal_id'=> $meal->id,
                        'description'=> $preparation_2['text'],
                        'part'=>2,
                    ]);
                 }  
              }
            }
            //   MpMealEdamamIngredient::where('mp_meal_id',$id)->forceDelete();
              if(isset($data['ingredient_table'])){
                foreach ($data['ingredient_table'] as $table_val) {           
                    $save_ingredient_tags = MpMealEdamamIngredient::create([
                        'mp_meal_id'=> $meal->id,
                        'item'=> $table_val['item'],
                        'qty'=>$table_val['qty'],
                        'unit'=>$table_val['measure'],
                        'calorie'=>$table_val['calories'],
                        'weight'=>$table_val['weight'],
                      
                    ]);
                 }  
              }

        return json_encode($msg);
    }

    /**
     * View meal info.
     *
     * @param int $id 
     * @return
     */
    public function show($id){
        $meals = $mpMeals = MpMeals::with('categories','foods','tags')->whereId($id)->first();
     
        if($mpMeals){
            $mealData = array();
            $mealData['name'] = strtoupper($mpMeals->name);
            $mealImg = $mpMeals->mealimages()->pluck('mmi_img_name')->first();
            $mealData['image'] = dpSrc($mealImg);
            $mealData['description'] = $mpMeals->description;
            $mealData['prepration_time'] = $mpMeals->time;
            $mealData['serving_size'] = $mpMeals->serves;
            $mealData['method'] = $mpMeals->method;
            $mealData['tips'] = $mpMeals->tips;
            $mealData['ingredients'] = $mpMeals->ingredients;
            /*  */
            $meals->mealIngredientSetPart1;
            $meals->mealIngredientSetPart2;
            $meals->mealPreparationPart1;
            $meals->mealPreparationPart2;
            $html = View::make('includes.partials.calendar-modal', compact('meals'));
            $mealData['html'] = $html->render();
        
            /*  */
            $prep_time = json_decode($mpMeals['time']);
            $cook_time = json_decode($mpMeals['cook_time']);
            $hrs = $prep_time->prep_hrs +  $cook_time->cook_hrs;  
            $mins = $prep_time->prep_mins +  $cook_time->cook_mins;   
            // convert min to hr 
            $conv_hours;
            $conv_min;
            if($mins > 59){
                $conv_hours = floor($mins / 60);
                $conv_min = $mins - ($conv_hours * 60); 
                if( $conv_hours){
                    $total_hrs =  $hrs +  (integer)$conv_hours ;
                }
                if($conv_min){
                    $total_min = (integer)  $conv_min;
                }
            } else{
                $total_min = (integer) $mins;
                $total_hrs = (integer) $hrs ;

            }
        
            $mealData['total_hrs'] = $total_hrs;
            $mealData['total_mins'] = $total_min;
            /*  */
            /*  */
            $mealRec = [];
            $recipeData = json_decode($mpMeals->nutritional_information,1);
            if($mpMeals->serves == ''){
                $mpMeals->serves = 1; 
            }
            foreach($recipeData as $key=> $value){
                $mealRec[$key] = sprintf("%.2f",$value/ $mpMeals->serves);
            }
            $mealData['nutritional_information'] = (object)$mealRec;
            return response()->json(['status' => 'ok','mealData'=>$mealData]);
        }
        return response()->json(['status'=> 'error']);
    }


    /**
     * Retrive particular meal info.
     *
     * @param  Int $id
     * @return meal detail
     */
    public function edit($id){

        $mealsCategory = $this->mealCategory();
        $servingSize = $this->getServingSize();
        $mealInfo = MpMeals::with('mealMainCategory','mealIngredient')->findOrFail($id);
        // dd(  $mealInfo);
        $ingredientSetPart1 = $mealInfo->mealIngredientSetPart1;
        $ingredientSetPart2 = $mealInfo->mealIngredientSetPart2;
        $preparationPart1 = $mealInfo->mealPreparationPart1;
        $preparationPart2 = $mealInfo->mealPreparationPart2;
        $edamamIngredient = $mealInfo->mealEdamamIngredient;
        // $mealInfo = MpMeals::with('mealMainCategory','mealIngredient','mealIngredientSetPart1',
        // 'mealPreparationPart1','mealIngredientSetPart2','mealPreparationPart2','mealEdamamIngredient')
        // ->findOrFail($id);

//  dd( $mealInfo);
        // $mealtags = $mealInfo->tags;
        // $tags = array();
        // if($mealtags->count()){
        //     foreach ($mealtags as  $mealtag) {
        //        $tags[] = $mealtag->mp_tag_name;
        //     }
        // }
        $meal_ingredient = $mealInfo->mealIngredient;
        $ingredient_tags = array();
        if($meal_ingredient->count()){
            foreach ($meal_ingredient as  $ingredient) {
               $ingredient_tags[] = $ingredient->name;
            }
        }

        $allStaffs = Staff::ofBusiness()->get();
        $staffs = array("" => "--Select--");
        foreach ($allStaffs as $staff){
            $staffs[$staff->id]= $staff->fullName;
        }
        /* Selected Categories */
        $not_add_main_cat = ['MEAL','POPULAR'];
        $mealSlctCat = $mealInfo->categories()->pluck('mpn_meal_categories.id')->toArray();
        $measurement_array =  MpMeals::$ingredientMeasurement;
         //performing sort
         sort($measurement_array);
         $array_length = count($measurement_array);
         //array after sorting
         for($i=0;$i<$array_length;$i++)
         {
             $measurement_array[i];
         }
   
        $main_cat = MainCategory::whereNotIn('name',$not_add_main_cat)->with('subCategory')->get();
        return view('mealplanner.meal.edit', compact('mealInfo','mealsCategory','servingSize','tags',
           'mealSlctCat','staffs','main_cat','ingredient_tags','measurement_array','ingredientSetPart1',
            'ingredientSetPart2','preparationPart1','preparationPart2','edamamIngredient'));
        }


    /**
     * update particular meal.
     *
     * @param  mealsRequest $request
     * @return data
     */

    public function update($id, Request $request){
        $msg['status'] = 'error';
        $data = $request->all();
        // dd(  $data);
        $prep_time = [];
        $prep_time['prep_hrs'] = $data['prep_time_hrs']?$data['prep_time_hrs']:0;
        $prep_time['prep_mins'] = $data['prep_time_mins']?$data['prep_time_mins']:0;
        $cook_time = [];
        $cook_time['cook_hrs'] = $data['cook_time_hrs']?$data['cook_time_hrs']:0;
        $cook_time['cook_mins'] = $data['cook_time_mins']?$data['cook_time_mins']:0;
        /* chart */
        $total_nutrient_kcal = [];
        $total_nutrient_kcal['total_energ_kcal'] = $data['total_energ_kcal']?$data['total_energ_kcal']:0;
        $total_nutrient_kcal['cal_from_protein'] = $data['cal_from_protein']?$data['cal_from_protein']:0;
        $total_nutrient_kcal['cal_from_fat'] = $data['cal_from_fat']?$data['cal_from_fat']:0;
        $total_nutrient_kcal['cal_from_carbs'] = $data['cal_from_carbs']?$data['cal_from_carbs']:0;
         /* chart */
        $meal = MpMeals::findOrFail($id);
        $meal->name = $data["name"];
        $meal->description = $data["description"];
        $meal->tips = $data["tips"];
        $meal->method = $data["method"];
        // $meal->ingredients = $data["ingredients"];
        $meal->ingredients = isset($data["ingr_textarea_1"])?$data["ingr_textarea_1"]:null;
        $meal->second_ingredients = isset($data["ingr_textarea_2"])?$data["ingr_textarea_2"]:null;
        $meal->staff_id = $data["staff_id"];
        /*$meal->serving_id = $data["serving_id"];*/
        $meal->serves = $data["serves"];
        $meal->listing_status = $data["listing_status"];
        $meal->nutritional_information = json_encode($data["nutritionalInfo"]);
        $meal->time = json_encode($prep_time); // prep time
        $meal->cook_time = json_encode($cook_time);
        $meal->nutrient_kcal = json_encode($total_nutrient_kcal);
        $meal->ingredient_set_no =  $data["reipe_format"];
        if($data['reipe_format']== 2 || $data['reipe_format']== 3){
            $ingredient_set_name = [];
            $ingredient_set_name['set_name_1'] = isset($data['ingredient_name_1'])?$data['ingredient_name_1']:null;
            $ingredient_set_name['set_name_2'] = isset($data['ingredient_name_2'])?$data['ingredient_name_2']:null;
            $meal->ingredient_set_name = json_encode($ingredient_set_name);
         } else {
            $meal->ingredient_set_name = null; 
         }
    
        // if($request->has('time') && $request->time != '')
        //     $meal->time = $data["time"];
        // else
        //     $meal->time = '';

        // if($request->has('cook_time') && $request->cook_time != '')
        //     $meal->cook_time = $data["cook_time"];
        // else
        //     $meal->cook_time = '';

        $img = array();
        foreach ($data as $key => $value) {
            if(strpos($key, 'mealpic') !== false)
                $img[] = $value;
            elseif((strpos($key, 'mealPicture') !== false) && $value != '')
                $img[] = $value;
        }
        $meal->update();

        /* Attach category */
        if(count($request->category_id))
            $meal->categories()->sync($request->category_id);
        
        /* Update image */
        if(count($img)){
            MpMealimages::where('mmi_meal_id',$id)->forceDelete();
            $mealImages = [];
            foreach ($img as  $value) {
                $mealImages[] = ['mmi_meal_id'=>$id, 'mmi_img_name'=>$value];  
            }
            if(MpMealimages::insert($mealImages))
                $msg['status'] = 'success';
        }

        /* Update meal tags name */
        MpTags::where('mp_id',$id)->where('mp_type','meal')->forceDelete();
        if($data['tags'] != ''){
            $tags = json_decode($data['tags']);
            if(count($tags)){
                $mealTags = [];
                foreach ($tags as $tag) {
                    $mealTags [] = ['mp_id'=>$id, 'mp_type'=>'meal','mp_tag_name'=>$tag];
                }
                MpTags::insert($mealTags);
            }
        }
        /* main category */
        $main_cat = $data['main_category_id'];
        if(count($main_cat) > 0){
            MpMealMainCategory::where('mp_meal_id', $id)->delete();
            foreach($main_cat as $key => $cat){
                foreach($cat as $sub_cat){
                  $meal_main_cat = MpMealMainCategory::create([
                      'mp_meal_id'=> $id,
                      'main_category_id'=> $key,
                      'sub_category_id'=>$sub_cat
                  ]);
                }
             }
        }
       
      /* main category */

        /* Update  Ingredient tags name */
        MpIngredientTag::where('mp_meal_id',$id)->forceDelete();
        if($data['ingredient_tags'] != ''){
            $ingredient_tags = json_decode($data['ingredient_tags']);
            if(count($ingredient_tags)){
                foreach ($ingredient_tags as $tag) {
                    $save_ingredient_tags = MpIngredientTag::create([
                        'mp_meal_id'=> $id,
                        'name'=> $tag,
                    ]);
                 }
              }
         }

         /* ingredient data */
         MpMealIngredientSet::where('mp_meal_id',$id)->forceDelete();
         if(isset($data['ingredient_data_1'])){
                foreach ($data['ingredient_data_1'] as $ingr_data_1) {
                    $save_ingredient_tags = MpMealIngredientSet::create([
                        'mp_meal_id'=> $id,
                        'item'=> $ingr_data_1['item'],
                        'measurement'=>$ingr_data_1['measure'],
                        'qty'=>$ingr_data_1['qty'],
                        'part'=>1,
                    ]);
                 }  
         }
     if( $data["reipe_format"] == 3 || $data["reipe_format"] == 2){
         if(isset($data['ingredient_data_2'])){
            foreach ($data['ingredient_data_2'] as $ingr_data_2) {           
                $save_ingredient_tags = MpMealIngredientSet::create([
                    'mp_meal_id'=> $id,
                    'item'=> $ingr_data_2['item'],
                    'measurement'=>$ingr_data_2['measure'],
                    'qty'=>$ingr_data_2['qty'],
                    'part'=>2,
                ]);
             }  
          }
      }
    /*  */
     MpMealPreparation::where('mp_meal_id',$id)->forceDelete();
     if(isset($data['preparation_data_1'])){
        foreach ($data['preparation_data_1'] as $preparation_1) {   
 
            $save_ingredient_tags = MpMealPreparation::create([
                'mp_meal_id'=> $id,
                'description'=> $preparation_1['text'],
                'part'=>1,
            ]);
         }  
      }
      if( $data["reipe_format"] == 3){
      if(isset($data['preparation_data_2'])){
        foreach ($data['preparation_data_2'] as $preparation_2) {           
            $save_ingredient_tags = MpMealPreparation::create([
                'mp_meal_id'=> $id,
                'description'=> $preparation_2['text'],
                'part'=>2,
            ]);
         }  
      }
    }
    //  if(isset($data['meal_preparation_1'])){
    //     foreach ($data['meal_preparation_1'] as $preparation_1) {           
    //         $save_ingredient_tags = MpMealPreparation::create([
    //             'mp_meal_id'=> $id,
    //             'description'=> $preparation_1,
    //             'part'=>1,
    //         ]);
    //      }  
    //   }
    //   if(isset($data['meal_preparation_2'])){
    //     foreach ($data['meal_preparation_2'] as $preparation_2) {           
    //         $save_ingredient_tags = MpMealPreparation::create([
    //             'mp_meal_id'=> $id,
    //             'description'=> $preparation_2,
    //             'part'=>2,
    //         ]);
    //      }  
    //   }
      MpMealEdamamIngredient::where('mp_meal_id',$id)->forceDelete();
      if(isset($data['ingredient_table'])){
        foreach ($data['ingredient_table'] as $table_val) {           
            $save_ingredient_tags = MpMealEdamamIngredient::create([
                'mp_meal_id'=> $id,
                'item'=> $table_val['item'],
                'qty'=>$table_val['qty'],
                'unit'=>$table_val['measure'],
                'calorie'=>$table_val['calories'],
                'weight'=>$table_val['weight'],
              
            ]);
         }  
      }
         /*  */
        return json_encode($msg);
    }


    /**
     * delete particular meal.
     *
     * @param  mealsRequest $request
     * @return Response
     */

    public function delete($id){
        $meal = MpMeals::find($id);
        if(count($meal))
            $meal->delete();

        return redirect()->back()->with('message', 'success|Meal has been deleted successfully.');
    }

    /** 
     * Remove image from file
     * @param image name
     * @return status
    **/
    public function removeImage(Request $request){
        $response['status'] = 'error';
        $imagePath = $request->preImg;
        $imageName = explode('_', $imagePath);

        if(MpMealimages::where('mmi_img_name', $imageName[1])->delete()){
            // remove copy file
            if(file_exists(public_path('/uploads/') .$imagePath)){
                unlink(public_path('/uploads/') . $imagePath);
                $response['status'] = 'success';
            }

            //remove origenal file
            if(file_exists(public_path('/uploads/') .$imageName[1])){
                unlink(public_path('/uploads/') . $imageName[1]);
                $response['status'] = 'success';
            }
            
        }
        else{
           $response['status'] = 'success'; 
        }
        return json_encode($response);
    }

    
    /**
     * Retrive Food Name list.
     *
     * @param   No
     * @return View with param
     */
    public function foodNameListings(Request $request){
        $data = array();
        $query = $request->get('query');        
        $posts = MpFoods::select('id','name','serving_size')->where('name','LIKE','%'.$query.'%')->get();
        if($posts->count()) 
            foreach ($posts as $key => $value)
                $data[$key] = $posts[$key];
        
        return response()->json($data);
    }

    /**
     * Download meal pdf file
     * 
     * @param int id
     * @return
     */
    public function download($id){
  
        $mpMeals = MpMeals::with('categories','foods','tags')->whereId($id)->first();
        if($mpMeals){
            $mealData = array();
            $mealData['name'] = strtoupper($mpMeals->name);
            $mealImg = $mpMeals->mealimages()->pluck('mmi_img_name')->first();
            $mealData['image'] = $mealImg;
            $mealData['description'] = $mpMeals->description;
            $mealData['prepration_time'] = $mpMeals->time;
            $mealData['serving_size'] = $mpMeals->serves;
            $mealData['method'] = $mpMeals->method;
            $mealData['tips'] = $mpMeals->tips;
            $mealData['ingredients'] = $mpMeals->ingredients;
            $mealData['nutritional_information'] = json_decode($mpMeals->nutritional_information);
            $mealData['ingredient_set_no'] = $mpMeals->ingredient_set_no;
            $mealData['ingredient_set_name'] = $mpMeals->ingredient_set_name;

            $mealData['mealIngredientSetPart1'] = $mpMeals->mealIngredientSetPart1;
            $mealData['mealIngredientSetPart2'] = $mpMeals->mealIngredientSetPart2;
            $mealData['mealPreparationPart1'] = $mpMeals->mealPreparationPart1;
            $mealData['mealPreparationPart2'] = $mpMeals->mealPreparationPart2;
            // $mealData['mealMainCategoryDetails'] = $mpMeals->mealMainCategoryDetails;
        
            /*  */
            $prep_time = json_decode($mpMeals['time']);
            $cook_time = json_decode($mpMeals['cook_time']);
            $hrs = $prep_time->prep_hrs +  $cook_time->cook_hrs;  
            $mins = $prep_time->prep_mins +  $cook_time->cook_mins;   
            // convert min to hr 
            $conv_hours;
            $conv_min;
            if($mins > 59){
                $conv_hours = floor($mins / 60);
                $conv_min = $mins - ($conv_hours * 60); 
                if( $conv_hours){
                    $total_hrs =  $hrs +  (integer)$conv_hours ;
                }
                if($conv_min){
                    $total_min = (integer)  $conv_min;
                }
            } else{
                $total_min = (integer) $mins;
                $total_hrs = (integer) $hrs ;

            }
        
            $mealData['total_hrs'] = $total_hrs;
            $mealData['total_mins'] = $total_min;
            /*  */
            $pdfName = 'testmeal.pdf';
            $pdf = PDF::loadView('mealplanner/meal/mealpdf', ['mealData' => $mealData]);
            return $pdf->download(strtolower(str_replace(' ','-',$mealData['name'])).'.pdf');
            // return redirect()->back();
        }
        return redirect()->back()->with('message', 'error|Something went wrong.');
    }

    /**
     * Page to show all Recipes
     * 
     * @param
     * @return view
     */

     public function allRecipe(Request $request){
        $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        if(isset($selectedMenus) && !in_array('recipes', explode(',', $selectedMenus))){
            return redirect('access-restricted');
        }
        $data = array();
        $query = MpMeals::active()->orderBy('id', 'DESC')
              ->where('business_id', Session::get('businessId'))
              ->where('listing_status',0);
      
        
        // if($request->has('filter')){
        //     $categoryType = $request->get('filter');
        //     $query->wherehas('categories',function($query) use($categoryType){
        //         $query->where('name','like',"%$categoryType%");
        //     });
        // }

        if($request->has('search') && $request->search != ""){
            
            $query->where('name','LIKE',"%".$request->search."%")
                  ->orWhere('description','LIKE',"%".$request->search."%")
                  ->orWhere('ingredients','LIKE',"%".$request->search."%");
        }
        $tags;
        $sub_cat_name;
        if($request->has('filter_tags') && count($request->filter_tags) > 0){
            $tags= $request->get('filter_tags');
            $sub_cat_name = SubCategory::select('id','name')->whereIn('id', $tags)->get();
            $query->wherehas('mealMainCategory',function($query) use($tags){
                $query->whereIn('sub_category_id', $tags);
            });
        }
        /* receip  */
        $recipe_tags;
        // $sub_cat_name;
        if($request->has('recipe_tags') && count($request->recipe_tags) > 0){
            $recipe_tags = $request->get('recipe_tags');
            $recipe_cat_name = MpMealCategory::select('id','name')->whereIn('id', $recipe_tags)->get();
            $query->wherehas('mealMealCat',function($query) use($recipe_tags){
                $query->whereIn('cat_id', $recipe_tags);
            });
        }
 
         /* receip  */

        $exclude_list;
        $exclude_id_array = [];
        if($request->has('exclude')){
            $exclude_list = array_unique(array_reverse(array_filter($request->get('exclude'))));
            if(count($exclude_list) > 0){
                /* exclude tag */
              $exclude_query = MpMeals::active()->orderBy('id', 'DESC')
                           ->where('business_id', Session::get('businessId'))
                           ->where('listing_status',0);
                           
            //   $exclude_query->wherehas('mealIngredientTag',function($exclude_query) use($exclude_list){
             $exclude_query->wherehas('mealMealIngredientSet',function($exclude_query) use($exclude_list){
                    $exclude_query->whereIn('item', $exclude_list);        
                });

              $query_array = $exclude_query->get();
              if(count($query_array) > 0){
                foreach($query_array as $new_array) {
                    $exclude_id_array[] = $new_array->id;
                 }
               } 
             /* end exclude tag */
            //    $query->wherehas('mealIngredientTag',function($query) use($exclude_list, $exclude_id_array){
              $query->wherehas('mealMealIngredientSet',function($query) use($exclude_list, $exclude_id_array){
                      $query->whereNotIn('item', $exclude_list)
                             ->whereNotIn('mp_meal_id', $exclude_id_array);       
              });  
            }    
        }
        $include_list;
        if($request->has('include')){
            $include_list= array_unique(array_reverse(array_filter($request->get('include'))));
            if(count($include_list) > 0){
                // $query->wherehas('mealIngredientTag',function($query) use($include_list, $exclude_id_array){
               $query->wherehas('mealMealIngredientSet',function($query) use($include_list, $exclude_id_array){
                    $query->whereIn('item', $include_list)         
                          ->whereNotIn('mp_meal_id', $exclude_id_array);
                });              
            }   
        }
             
        $meals = $query->paginate(12);      
        $mealCategories = MpMealCategory::select('name')->get();
        $main_cat_name = ['MEAL','POPULAR'];
        $main_cat = MainCategory::with('subCategory')->whereNotIn('name',$main_cat_name)->get();
        $recipe_category= MpMealCategory::select('id','name')->get();
        return view('Result.mealplanner.recipes',compact('meals','mealCategories','main_cat','tags',
            'sub_cat_name','include_list','exclude_list','recipe_category','recipe_cat_name','recipe_tags'));
    }

    function analyzeIngredients(Request $request){
        
        $curl = curl_init();
        // $request->request->add(['yield' => 'cut-hits']);
        $form_no = $request->form;
        $request->request->remove('form'); 
        $ingData = $request->all();
        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'https://api.edamam.com/api/nutrition-details?app_id=47379841&app_key=d28718060b8adfd39783ead254df7f92&perServing=true',
            CURLOPT_URL => 'https://api.edamam.com/api/nutrition-details?app_id=47379841&app_key=d28718060b8adfd39783ead254df7f92',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            // CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($ingData),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $jsonarr = json_decode($response, true);

        $curl_2 = curl_init();
        curl_setopt_array($curl_2, array(
            // CURLOPT_URL => 'https://api.edamam.com/api/nutrition-details?app_id=72be998f&app_key=6014b058fc8ab72c3fcf1634ec933364&perServing=true',
            CURLOPT_URL => 'https://api.edamam.com/api/nutrition-details?app_id=72be998f&app_key=6014b058fc8ab72c3fcf1634ec933364',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            // CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($ingData),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response_2 = curl_exec($curl_2);
        $jsonarr_2 = json_decode($response_2, true);
        $jsonarr['totalNutrientsKCal'] = $jsonarr_2['totalNutrientsKCal'];

        $ingr_val = $jsonarr['ingredients'];

        $final_ingr_val = [];
        foreach($ingr_val as $key => $ingr){
            if (count($ingr['parsed']) > 1) {
                $text = null;
            } else {
                $text = $ingr['text'];
            }
            foreach($ingr['parsed'] as $index => $final_val){
                if($text == null){
                    $text = isset( $final_val['quantity'])? $final_val['quantity']:''. isset($final_val['measure'])?$final_val['measure']:''.isset($final_val['food'])?$final_val['food']:'';
                }
               $ingr_data = $this->singleIngredients($text);
               $final_ingr_val[] = $ingr_data;
            }
        }
/*  */
// foreach($ingr_val as $key => $ingr){
//     if (count($ingr['parsed']) > 1) {
//         $text = null;
//     } else {
//         // $text = $ingr['text'];
//         $text = $ingr['parsed'][$key]['food'];
//     }
//     foreach($ingr['parsed'] as $index => $final_val){ 
//         if($text == null){
//             $text = $final_val['food'];
//             // $text =( isset( $final_val[$index]['quantity'])? $final_val[$index]['quantity']:''). (isset($final_val[$index]['measure'])?$final_val[$index]['measure']:'').(isset($final_val[$index]['food'])?$final_val[$index]['food']:'');
//         }
//        $ingr_data = $this->singleIngredients($text);
//        $final_ingr_val[] = $ingr_data;
//        $final_ingr_val['ingr']= $final_val;
//     }
// }

/*  */


        if($form_no == "ingredient-form-1"){
            $html = View::make('mealplanner.meal.ingredient-form', compact('final_ingr_val'));
            $jsonarr['html'] = $html->render();
        }
        if($form_no == "ingredient-form-2"){
            $html = View::make('mealplanner.meal.ingredient-2-form', compact('final_ingr_val'));
            $jsonarr['html'] = $html->render();
        }
     
        // return Response::json($response);

        return $jsonarr;
   }

//    public function singleIngredients(Request $request){
 public function singleIngredients($text){
    $curl = curl_init();
    $url = rawurlencode($text);
    // dd('https://api.edamam.com/api/food-database/v2/parser?app_id=7d96b6e0&app_key=2f016339d323a1ac559d818af75d7acd&category=generic-foods&category=packaged-foods&ingr='.$request->ingr, rawurlencode($request->ingr));
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.edamam.com/api/food-database/v2/parser?app_id=7d96b6e0&app_key=2f016339d323a1ac559d818af75d7acd&category=generic-foods&category=packaged-foods&ingr='.$url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => "POST",
        // CURLOPT_POSTFIELDS => json_encode($ingData),
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    $jsonarr = json_decode($response, true);
    $hints = [];
     foreach( $jsonarr['hints'] as $key => $hint){
        $hints[$key]['foodId'] = $hint['food']['foodId'];
        $hints[$key]['label'] = $hint['food']['label'];
        foreach( $hint['measures'] as $key1 => $measures){
            $hints[$key]['measureURIs'][]= $measures['uri'];
            $hints[$key]['measures'][]= $measures['label'];
        }
     }
     $jsonarr['hints'] =  $hints;
     return $jsonarr;
     
     /*  */
   }


   public function mainCategory(){
        //  $main_category_list = MainCategory::all();
        $main_category_list = MainCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('mealplanner.meal.category.index')->with( ['main_category_list' => $main_category_list] );
    }

    public function mainCategoryCreate(){
        return view('mealplanner.meal.category.create');
    }

   public function mainCategoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:main_categories',
        ]);

        if($validator->fails()){
            return redirect()->route('main-category.create')
                        ->withErrors($validator)
                        ->withInput();     
        }
        $main_category = MainCategory::create($request->all());
        if($main_category){
            return redirect()->route('main-category.index')->with('message', 'Record store successfully');          
        } else{
            $message = 'Oops something went wrong';
            return redirect()->back()->withErrors($message)->withInput();  
        }
    }

    public function mainCategoryFetch($id){
        $main_cat = MainCategory::find($id);
        if($main_cat){
            return view('mealplanner.meal.category.edit')->with( ['main_cat' => $main_cat] );      
        } else{
            $message = 'Record not found';
          return redirect()->back()->withErrors($message)->withInput();     
        }
    }

    public function mainCategoryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:main_categories,name,'.$id
        ]);

        if($validator->fails()){
            return redirect()->route('main-category.fetch',$id )
                        ->withErrors($validator)
                        ->withInput();     
        }
        $main_cat = MainCategory::find($id);
        if($main_cat){
           $cat_update = $main_cat->update($request->all());
            if( $cat_update ){
                return redirect()->route('main-category.index')->with('message','Record updated successfully');    
            } else{
                $message = 'Oops something went wrong';
                return redirect()->back()->withErrors($message)->withInput();  
            }
        } else{
            $message = 'Record not found';
          return redirect()->back()->withErrors($message)->withInput();     
        }
    }

    public function mainCategoryDelete($id)
    {
      $main_cat = MainCategory::find($id);
      if($main_cat){
          $delete = $main_cat->delete();
          return redirect()->back()->with('message','Record deleted successfully');
      } else{
          $message = 'Record not found';
        return redirect()->back()->withErrors($message)->withInput();     
      }
    }

    /* -----------------    sub category -------------------------------- */

    public function subCategory()
    {  
        $sub_category_list = SubCategory::orderBy('created_at', 'desc')->paginate(10);
        return view('mealplanner.meal.subcategory.index')->with( ['sub_category_list' => $sub_category_list] );
    }

    public function subCategoryCreate(){
        $main_cat_list = MainCategory::orderBy('created_at', 'desc')->get();
        return view('mealplanner.meal.subcategory.create')->with( ['main_cat_list' => $main_cat_list] );
    }

   public function subCategoryStore(Request $request)
    {
        $sub_category = SubCategory::create($request->all());
        if($sub_category){
            return redirect()->route('sub-category.index')->with('message', 'Record store successfully');          
        } else{
            $message = 'Oops something went wrong';
            return redirect()->back()->withErrors($message)->withInput();  
        }
    }

    public function subCategoryFetch($id){
        // $sub_cat = SubCategory::with('mainCategory')->find($id);
        $sub_cat = SubCategory::find($id);
        $main_cat_list = MainCategory::orderBy('created_at', 'desc')->get();
        if($sub_cat){
            return view('mealplanner.meal.subcategory.edit')->with( ['sub_cat' => $sub_cat,'main_cat_list' => $main_cat_list ] );      
        } else{
            $message = 'Record not found';
          return redirect()->back()->withErrors($message)->withInput();     
        }
    }

    public function subCategoryUpdate(Request $request, $id)
    {            
        $sub_cat = SubCategory::find($id);
        if($sub_cat){
           $cat_update = $sub_cat->update($request->all());
            if( $cat_update ){
                return redirect()->route('sub-category.index')->with('message','Record updated successfully');    
            } else{
                $message = 'Oops something went wrong';
                return redirect()->back()->withErrors($message)->withInput();  
            }
        } else{
            $message = 'Record not found';
          return redirect()->back()->withErrors($message)->withInput();     
        }
    }

    public function subCategoryDelete($id)
    {
      $sub_cat = SubCategory::find($id);
      if($sub_cat){
          $delete = $sub_cat->delete();
          return redirect()->back()->with('message','Record deleted successfully');
      } else{
          $message = 'Record not found';
        return redirect()->back()->withErrors($message)->withInput();     
      }
    }
    
    public function recipeDetail($id){
        $meal_detail = MpMeals::active()
                      ->orderBy('id', 'DESC')
                      ->where('business_id', Session::get('businessId'))
                      ->where('id', $id)
                      ->where('listing_status',0)
                      ->first();
       
         $meal_detail->mealIngredientSetPart1;
         $meal_detail->mealIngredientSetPart2;
         $meal_detail->mealPreparationPart1;
         $meal_detail->mealPreparationPart2;
         $meal_detail->mealMainCategoryDetails;
         $meal_detail->clientMealplan;
         $meal_detail->mealReview;
         $auth_user = Clients::select('id','firstname','lastname','profilepic')
                     ->where('id', Auth::user()->account_id)
                     ->first();
        $review_total = RecipeReview::where('meal_id', $id)->pluck('id');
        $reply_total = ReplyRecipeReview::whereIn('recipe_review_id',$review_total)->get()->count();
        $totalReview = count($review_total) +  $reply_total;
        $meal_detail->getRatingCount();
        //  dd(  $meal_detail->rating);
        $avgRating = 0;
        foreach($meal_detail->rating as $rating){
            $avgRating = $avgRating + $rating['star'];
        }
        $totalAvgRating = $avgRating/$meal_detail->getRatingCount();
        return view('Result.mealplanner.recipe-details', compact('meal_detail','auth_user','totalReview','totalAvgRating'));
   
    }
       
    public function searchFilterSuggestion($value){
        $query = MpMeals::active()->orderBy('id', 'DESC')
                ->where('business_id', Session::get('businessId'))
                ->where('listing_status',0);
        if($value){
            $query->where('name','LIKE',"%".$value."%")
                  ->orWhere('description','LIKE',"%".$value."%")
                  ->orWhere('ingredients','LIKE',"%".$value."%");
        }
        $meals = $query->paginate(5);
        $response = [];
        $response['status'] = 'success';
        $html = View::make('Result.mealplanner.recipe-search', compact('meals','value'));
        $response['search'] = $html->render();
        return Response::json($response);
    }

    public function searchFilterSuggestionCalendar( Request $request){
        $query = MpMeals::active()->orderBy('id', 'DESC')
                ->where('business_id', Session::get('businessId'))
                ->where('listing_status',0);
        if($request->category_type){
            $categoryType = $request->category_type;
            $query->wherehas('categories',function($query) use($categoryType){
                $query->where('name','like',"%$categoryType%");
            });
          }
         $value = $request->value;
        if($value){
            $query->where('name','LIKE',"%".$value."%")
                  ->orWhere('description','LIKE',"%".$value."%")
                  ->orWhere('ingredients','LIKE',"%".$value."%");
        }
        $meals = $query->paginate(5);
        $response = [];
        $response['status'] = 'success';
        $html = View::make('Result.mealplanner.recipe-search', compact('meals','value'));
        $response['search'] = $html->render();
        return Response::json($response);
    }

    /* edamam api */
    // function analyzeIngredients(Request $request){
    function nutritionData(Request $request){
        $curl = curl_init();
        $ingData = $request->all();
        curl_setopt_array($curl, array(
            // CURLOPT_URL => 'https://api.edamam.com/api/nutrition-details?app_id=47379841&app_key=d28718060b8adfd39783ead254df7f92',

            CURLOPT_URL => 'https://api.edamam.com/api/nutrition-data?app_id=47379841&app_key=d28718060b8adfd39783ead254df7f92&ingr=water',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_CUSTOMREQUEST => "POST",
            // CURLOPT_POSTFIELDS => json_encode($ingData),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
      
        $jsonarr = json_decode($response, true);

        return $jsonarr;
       }

       public function saveShoppingList(Request $request){
              $title = [];
              $clientId = Auth::user()->account_id;
              $startDate = date('Y-m-01'); // hard-coded '01' for first day
              $endDate  = date('Y-m-t');
              $data = $request->form_data;
              $old_serve = $data['old_serve'];
              $new_serve = $data['new_serve'];
            //   dd( $old_serve,     $new_serve);
              if($request->checked_length == 0){
                 $meal_ingredient_list = MpMealIngredientSet::where('mp_meal_id',$request->id)->get();
              } else{
                 $meal_ingredient_list = MpMealIngredientSet::whereIn('id',$request->ingr)
                            ->where('mp_meal_id',$request->id)
                            ->get();
              }
           ShoppingList::where('mp_meal_id',$request->id)->where('purchased_date',NULL)->forceDelete();
           foreach($meal_ingredient_list as $key => $meal_ingredient){  
               $title[0]['recName'] = $request->title;
               $title[0]['quantity'] = $meal_ingredient->qty;
               $mealRecipe = json_encode($title);
               if( $old_serve == $new_serve){
                 $insertData = array(
                    'mp_meal_id' =>$request->id,
                    'client_id' => $clientId,
                    'rec_name' =>  $meal_ingredient->item,
                    'quantity' => $meal_ingredient->qty,
                    'start_date'=>$startDate ,
                    'end_date'=> $endDate,
                    'meal_recipe_name'=>$mealRecipe,
                     );
               }else{
                $single_qty = $meal_ingredient->qty/$old_serve;
                $new_qty =  $single_qty*$new_serve;
                $insertData = array(
                    'mp_meal_id' =>$request->id,
                    'client_id' => $clientId,
                    'rec_name' =>  $meal_ingredient->item,
                    'quantity' => $new_qty,
                    'start_date'=>$startDate ,
                    'end_date'=> $endDate,
                    'meal_recipe_name'=>$mealRecipe,
                     );  
               }
             
               ShoppingList::create( $insertData );

           }
           $response = [];
           $response['status'] = 'success';
           return Response::json($response); 

       }

       public function shoppingList( Request $request){
      
            $msg['status'] = 'error';
            $req_data = $request->all();
            $client_id = Auth::User()->account_id;
         
            // $total_ingredient = MpMealIngredientSet::where('mp_meal_id',$req_data['data']['recipe_id'])
            //                      ->get();  
            //  if(count($meal_ingredient_list) == count($total_ingredient)){
            //   $shopping_btn = 0;
            //  }else{
            //     $shopping_btn = 1;  
            //  }
            // MpClientMealplan::where('client_id',$client_id)->where('event_id',$req_data['data']['recipe_id'])->forceDelete();
            $clientMealplan = new MpClientMealplan;
            $clientMealplan->client_id = $client_id;
            $clientMealplan->event_id = $req_data['data']['recipe_id'];
            $clientMealplan->event_type = 'Meal';
            $clientMealplan->event_date = $req_data['data']['meal_date'];
            $clientMealplan->event_meal_category = $req_data['data']['cat_id'];
            $clientMealplan->snack_type = $req_data['data']['snack_type'];
            // $clientMealplan->shopping_btn_status = $shopping_btn;
            if($clientMealplan->save()){
                if($req_data['btn_type'] == 'submit'){
                    $title = [];
                    $clientId = Auth::user()->account_id;
                    $startDate = date('Y-m-01'); // hard-coded '01' for first day
                    $endDate  = date('Y-m-t');
                $meal_ingredient_list = MpMealIngredientSet::whereIn('id',$req_data['ingr'])
                    ->where('mp_meal_id',$req_data['data']['recipe_id'])
                    ->get();                
                // ShoppingList::where('mp_meal_id',$req_data['data']['recipe_id'])->where('purchased_date',NULL)->forceDelete();
                foreach($meal_ingredient_list as $key => $meal_ingredient){  
                    $title[0]['recName'] = $req_data['data']['title'];
                    $title[0]['quantity'] = $meal_ingredient->qty;
                    $mealRecipe = json_encode($title);
                    $insertData = array(
                    'mp_meal_id' =>$req_data['data']['recipe_id'],
                    'client_id' => $client_id,
                    'rec_name' =>  $meal_ingredient->item,
                    'quantity' => $meal_ingredient->qty,
                    'start_date'=>$startDate ,
                    'end_date'=> $endDate,
                    'meal_recipe_name'=>$mealRecipe,
                    );
                    ShoppingList::create( $insertData );
                    }
              }
              $msg['status'] = 'success';
            }
             return Response::json($msg); 

       }

    public function emailIngredient(Request $request){
        $req_data = $request->formData;
        if($req_data['checked_length'] == 0){
            $meal_ingredient_list = MpMealIngredientSet::where('mp_meal_id',$req_data['id'])->get();
         } else{
            $meal_ingredient_list = MpMealIngredientSet::whereIn('id',$req_data['ingr'])
                       ->where('mp_meal_id',$req_data['id'])
                       ->get();
         }
        
        $title = $req_data['title'];
        $old_serve = $req_data['old_serve'];
        $new_serve = $req_data['new_serve'];
        $client_id = Auth::User()->account_id;
        $clients = Clients::select('id','firstname','email')->where('id', $client_id)->first();
        if($meal_ingredient_list){
            $username = $clients->firstname;
            $to = $clients->email;
            $subject  =  $title .': Ingredient shopping list';
            $message = view('includes.partials.email_ingredient', compact('meal_ingredient_list','title','clients','old_serve','new_serve'))->render();
            $mail = new PHPMailer(true);
           try {
               //$mail->isSMTP(); // tell to use smtp
               $mail->CharSet = "utf-8"; // set charset to utf8
               $mail->Host = 'epictrainer.com';
               $mail->SMTPAuth = false;
               $mail->SMTPSecure = false;
               $mail->Port = 2525; // most likely something different for you. This is the mailtrap.io port i use for testing.
               $mail->Username = 'webmaster@epictrainer.com';
               $mail->Password = 'S[WlD3]Tf4*K';
               $mail->setFrom("noreply@epictrainer.com", "EPIC Trainer Team");
               $mail->Subject = $subject;
               $mail->MsgHTML($message);
               $mail->addAddress($to, $username);
               $mail->SMTPOptions= array(
                   'ssl' => array(
                   'verify_peer' => false,
                   'verify_peer_name' => false,
                   'allow_self_signed' => true
                   )
               );
           $result =  $mail->send();
           $msg['status'] = 'success';
           } catch (phpmailerException $e) {
            $msg['status'] = 'error';
            $msg['msg'] = $e;
           } catch (Exception $e) {
            $msg['status'] = 'error';
            $msg['msg'] = $e;
           }

        }
        return Response::json($msg); 
    }

    /* end  */
}
