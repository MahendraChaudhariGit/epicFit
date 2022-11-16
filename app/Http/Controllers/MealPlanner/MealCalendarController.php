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

use App\Http\Traits\HelperTrait;
use App\Http\Traits\MpPlannerTrait;
use App\Http\Traits\CalendarSettingTrait;
use App\ClientMenu;
use App\MpClientMealplanIngrediant;
use App\MpMealCategory;
use Redirect;
use View;
use App\{MpMealIngredientSet, ShoppingList,MainCategory, SubCategory};

class MealCalendarController extends Controller {
    use HelperTrait, MpPlannerTrait, CalendarSettingTrait;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $clientSelectedMenus = [];
        if(Auth::user()->account_type == 'Client') {
            $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
            $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
 
            if(!in_array('meal_planner', $clientSelectedMenus))
              Redirect::to('access-restricted')->send();
        }    
    }
    
    /**
     * Show meal calendar 
     *
     * @param 
     * @return 
    */
    public function show(){
        $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
        if(isset($selectedMenus) && !in_array('meal_planner', explode(',', $selectedMenus))){
            return redirect('access-restricted');
        }
        $calendar_settings = $this->getCalendSettingsForClient(Auth::user()->account_id);
        $mealsCategory = $this->mealCategory();
        $mealsCategoryArr = MpMealCategory::pluck('id', 'name')->toArray();
        return view('mealplanner.meal-calendar', compact('calendar_settings','mealsCategory','mealsCategoryArr'));
    }

    /**
     * Store meal event 
     *
     * @param 
     * @return 
    */
    public function store(Request $request){
        // dd($request->all());
        $msg['status'] = 'error';
        if($request->has('isCustom') && $request->isCustom){
            $clientMealplan = new MpClientMealplan;
            $clientMealplan->client_id = Auth::User()->account_id;
            $clientMealplan->event_id = 0;
            $clientMealplan->event_type = "Meal";
            $clientMealplan->event_date = $request->eventDate;
            $clientMealplan->event_meal_category = $request->catId;
            if($request->isSnack)
                $clientMealplan->snack_type = $request->snackType;
            $clientMealplan->recipe_name = $request->recipeName;
            $clientMealplan->ingredients = $request->ingredients;
            $clientMealplan->quantity = $request->quantity;
            $clientMealplan->serving_size = $request->serving_size;
            $clientMealplan->is_custom = $request->isCustom;
        }else{
            $clientMealplan = new MpClientMealplan;
            $clientMealplan->client_id = Auth::User()->account_id;
            $clientMealplan->event_id = $request->id;
            $clientMealplan->event_type = $request->type;
            $clientMealplan->event_date = $request->date;
            $clientMealplan->event_meal_category = $request->cat;
            $clientMealplan->snack_type = $request->snackType;
        }
        if($clientMealplan->save()) {
                $meal_ingredient_list = MpMealIngredientSet::where('mp_meal_id',$request->id)
                        ->get();  
                   
                $startDate = date('Y-m-01'); // hard-coded '01' for first day
                $endDate  = date('Y-m-t');            
            // ShoppingList::where('mp_meal_id',$request->id)->where('purchased_date',NULL)->forceDelete();
            foreach($meal_ingredient_list as $key => $meal_ingredient){  
                    $title[0]['recName'] = $request['title'];
                    $title[0]['quantity'] = $meal_ingredient->qty;
                    $mealRecipe = json_encode($title);
                    $insertData = array(
                            'mp_meal_id' => $request->id,
                            'client_id' =>  Auth::User()->account_id,
                            'rec_name' =>  $meal_ingredient->item,
                            'quantity' => $meal_ingredient->qty,
                            'start_date'=>$startDate ,
                            'end_date'=> $endDate,
                            'meal_recipe_name'=>$mealRecipe,
                    );
            ShoppingList::create( $insertData );
            }
         $msg['status'] = 'success';

        }
        return json_encode($msg);
    }

    /**
     * Update meal event 
     *
     * @param 
     * @return 
    */
    public function update(Request $request){
        $msg['status'] = 'error';

        $clientMealplan = MpClientMealplan::find($request->eventId);
        $clientMealplan->event_id = $request->id;
        $clientMealplan->event_type = $request->type;
        $clientMealplan->event_date = $request->date;
        if($request->has('meal_category'))
            $clientMealplan->event_meal_category = $request->meal_category;

        if($clientMealplan->update())
            $msg['status'] = 'success';

        return json_encode($msg);
    }



    /**
     * Show meal calendar 
     *
     * @param 
     * @return 
    */
    public function getEvents(Request $request){
        $response = array();
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $events = MpClientMealplan::whereClientId(Auth::User()->account_id)->whereDate('event_date','>=', $startDate)->whereDate('event_date','<=', $endDate)->orderBy('snack_type')->get();
        if($events->count()){
            $i = 0;
            foreach ($events as  $event) {
                if(isset($event->meal) || $event->food){
                    $response[$i]['eventid'] = $event->id;
                    $response[$i]['type'] = $event->event_type;
                    $response[$i]['catid'] = $event->event_meal_category;
                    $response[$i]['catType'] = $event->category->where('id',$event->event_meal_category)->pluck('name')->first();
                    $response[$i]['id'] = $event->event_id;
                    $response[$i]['snackType'] = $event->snack_type;
                    if($event->event_meal_category != 0){
                        $response[$i]['startDatetime'] = $this->getStartAndEndDate($event->event_date, $event->category->name,$event->snack_type);
                    }
                    else
                        $response[$i]['startDatetime'] = $this->getStartAndEndDate($event->event_date, '',$event->snack_type);
                    
                    if($event->event_type == 'Meal')
                        $response[$i]['title'] = $event->meal->name;
                    elseif($event->event_type == 'Food')
                        $response[$i]['title'] = $event->food->name; 
                }else if($event->is_custom){
                    $response[$i]['eventid'] = $event->id;
                    $response[$i]['type'] = $event->event_type;
                    $response[$i]['catid'] = $event->event_meal_category;
                    $response[$i]['catType'] = $event->category->where('id',$event->event_meal_category)->pluck('name')->first();
                    $response[$i]['id'] = $event->event_id;
                    $response[$i]['snackType'] = $event->snack_type;
                    if($event->event_meal_category != 0){
                        $response[$i]['startDatetime'] = $this->getStartAndEndDate($event->event_date, $event->category->name,$event->snack_type);
                    }
                    else
                        $response[$i]['startDatetime'] = $this->getStartAndEndDate($event->event_date, '',$event->snack_type);
                    
                    if($event->event_type == 'Meal')
                        $response[$i]['title'] = $event->recipe_name;
                    elseif($event->event_type == 'Food')
                        $response[$i]['title'] = $event->recipe_name;
                }
                $i++;
            }
        }
        return json_encode($response);
    }

    /**
     * Get meal time according to breakfast/lunch
     * 
     * @param String Category name
     * @return Array start date and end date
     */
    protected function getStartAndEndDate($date, $cat,$snackType){
        switch ($cat) {
            /* 6am to 10am*/
            case "breakfast":
            case "Breakfast": 
                $time = "09:00:00";
                break;

            /* 10am up to 2pm */
            case 'brunch':
            case 'Brunch':
                $time = "10:00:00";
                break;

            /* Around 11am */
            case 'Elevenses':
            case 'elevenses':
            case 'Snack':
            case 'snack':
                if($snackType == MpClientMealplan::MORNING_SNACK)
                    $time = "11:00:00";
                elseif($snackType == MpClientMealplan::EVENING_SNACK)
                    $time = "16:00:00";
                elseif($snackType == MpClientMealplan::NIGHT_SNACK)
                    $time = "22:00:00";
                else
                    $time = "11:00:00";
                break;

            /* noon or 1pm*/
            case 'lunch':
            case 'Lunch':
                $time = "13:00:00";
                break;

            /* Around 4pm */
            case 'tea':
            case 'Tea':
                $time = "16:00:00";
                break;

            /*6pm-7pm*/
            case 'supper':
            case 'Supper':
                $time = "18:00:00";
                break;

            /* 7pm-9pm */
            case 'dinner':
            case 'Dinner':
                $time = "20:00:00";
                break;

            default:
                $time = "10:00:00";
        }

        $carbonDate = Carbon::parse($date . $time);
        return $carbonDate->format("Y-m-d H:i:s");
    }

    /**
     * Get meal plan for edit
     *
     * @param
     * @return
     */
 
    public function edit($id){
        $data = array('status'=>'error');        
        $event = MpClientMealplan::find($id);
        
        if($event->count()){
            $data['id'] = $event->id;  
            if($event->is_custom == 2)
            { 
                $data['name'] =$event->recipe_name;
                $data['ingredients'] = $event->ingredients;
                $data['quantity'] = $event->quantity;
                $data['serves'] = $event->serving_size;
                $data['is_custom'] = $event->is_custom;
                $data['catName'] = $event->category()->pluck('name')->first();
                $data['snackType'] = $event->snack_type;
                if(isset($event->nutritional_time)){
                    $data['time'] = $event->nutritional_time;
                }
                if(isset($event->hunger_rate)){
                    $data['hunger_rate'] = $event->hunger_rate;
                }
                if(isset($event->activity_label)){
                    $data['activity_label'] = $event->activity_label;
                }
                if(isset($event->general_notes)){
                    $data['general_notes'] = $event->general_notes;
                }
                if(isset($event->meal_rating)){
                    $data['meal_rating'] = $event->meal_rating;
                }
                if(isset($event->enjoyed_meal)){
                    $data['enjoyed_meal'] = $event->enjoyed_meal;
                }
                if(isset($event->image)){
                    $data['img'] = $event->image; 
                }
                $data['ingrediantData'] = MpClientMealplanIngrediant::where('mpn_client_mealplan_id',$event->id)->get()->toArray();
            }elseif($event->is_custom == 1){
                $data['name'] =$event->recipe_name;
                $data['ingredients'] = $event->ingredients;
                $data['quantity'] = $event->quantity;
                $data['serves'] = $event->serving_size;
                $data['is_custom'] = $event->is_custom;
                $data['catName'] = $event->category()->pluck('name')->first();
                $data['snackType'] = $event->snack_type;
            }
            else if($event->event_type == 'Meal'){
                $meals = $event->meal;
                $data['name'] = $meals->name;
                $data['serving_id'] = $meals->serving_id;
                $data['serving_name'] = $meals->serving_id;
                $data['serves'] = $meals->serves;
                $data['tips'] = $meals->tips;
                $data['description'] = $meals->description;
                $data['method'] = $meals->method;
                $data['ingredients'] = $meals->ingredients;
                // $data['time'] = $meals->time;
                    /*  */
                $prep_time = json_decode($meals['time']);
                $cook_time = json_decode($meals['cook_time']);
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
            
                $data['total_hrs'] = $total_hrs;
                $data['total_mins'] = $total_min;
                
                /*  */
                
                $data['tags'] = $meals->tags->pluck('mp_tag_name')->toArray();

                $image = $meals->mealimages->first();
                if(count($image))
                    $data['img'] = $image->mmi_img_name; 
                else
                    $data['img'] = '';

                /* nutrational data */
                $data['nutrInfo'] = json_decode($meals->nutritional_information);
                // $data['nutrInfo'] = [];
                // $foods = $meals->foods;
                // if(count($foods)){
                //     $data['nutrInfo'] = $this->getNutritionalInfo($foods);
                // }
                $meals->mealIngredientSetPart1;
                $meals->mealIngredientSetPart2;
                $meals->mealPreparationPart1;
                $meals->mealPreparationPart2;
                $html = View::make('includes.partials.calendar-modal', compact('meals'));
                $data['html'] = $html->render();
            }
            else{
                $foods = $event->food;
                $data['name'] = $foods->name;
                $data['serving_id'] = $foods->serving_size;
                $data['serving_name'] = $foods->serving_size;
                $data['description'] = $foods->description;
                $data['serves'] = '';
                $data['tips'] = '';
                $data['category_id'] = ''; 
                $data['category_name'] = '';
                $data['method'] = '';
                $data['ingredients'] = '';
                $data['time'] = '';
                
                $data['tags'] = [];

                $data['img'] = $foods->food_img;

                /* nutrational data */
                $data['nutrInfo'] = $this->getNutritionalInfoFirst($foods);      
            }

            $data['category_id'] = $event->event_meal_category; 
            $data['category_name'] = $event->event_meal_category; 
            $data['date'] = $event->event_date;
            $data['type'] = $event->event_type;

            $data['status'] = 'success';
        }

        return response()->json($data);
        
    }

    /**
     * Get meal list
     *
     * @param
     * @return
     */
    public function getMealList(Request $request){
        $response = array('status'=>'error'); 
        $data = array();
        
        // $query = MpMeals::with('mealIngredientSetPart1')
        //           ->where('business_id', Session::get('businessId'))
        //           ->where('listing_status',0)
        //           ->active();
        // if($request->has('text')){
        //     $text = $request->text;
        //     $meals = $query->where('name','like',"%$text%");

        // }
        $query = MpMeals::active()->orderBy('id', 'DESC')
                ->where('business_id', Session::get('businessId'))
                ->where('listing_status',0);

       
        if($request->has('category_type')){
            $categoryType = $request->get('category_type');
            $query->wherehas('categories',function($query) use($categoryType){
                         $query->where('name','like',"%$categoryType%");
                  });              
         }

        // if($request->has('category_type')){
        //     $category_type = $request->get('category_type');
        //     $recipe_cat = MpMealCategory::select('id','name')->where('name','like',"%$categoryType%")->first(); 
        //     $id = $recipe_cat['id'];
        //     $query->wherehas('mealMealCat',function($q) use( $id){
        //             $q->where('cat_id',$id);
        //     });
        // }

         /*  */
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
        $recipe_cat_name;
        if($request->has('recipe_tags') && count($request->recipe_tags) > 0){
            $recipe_tags = $request->get('recipe_tags');
            $recipe_cat_name = MpMealCategory::select('id','name')->whereIn('id', $recipe_tags)->get();
            $query->wherehas('mealMealCat',function($query) use($recipe_tags){
                $query->whereIn('cat_id', $recipe_tags);
            });
        }

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
               $query->wherehas('mealMealIngredientSet',function($query) use($include_list, $exclude_id_array){
                    $query->whereIn('item', $include_list)         
                          ->whereNotIn('mp_meal_id', $exclude_id_array);
                });              
            }   
        }



        $meals = $query->get();
       
        if($meals->count()){
            $i = 0;
            foreach ($meals as $meal){
                $data[$i]['id'] = $meal->id;
                $data[$i]['name'] = $meal->name;
                $data[$i]['description'] = $meal->description;
                if($meal->categories)
                    $data[$i]['cat'] = $meal->categories()->pluck('name')->toArray();
                else
                    $data[$i]['cat'] = '';

                $image = $meal->mealimages->first();
                if(count($image))
                    $data[$i]['img'] = $image->mmi_img_name; 
                else
                    $data[$i]['img'] = '';

                $i++;
             }
          } 
       
          $new_data =[];
        if($request->has('search') && $request->search != ""){ 
                foreach($data as $val){
                    foreach($val['cat'] as $cat){
                        if($cat== $request->get('category_type'))
                            array_push($new_data,$val);
                            break;
                    }
                }
                $data = $new_data;
            }
      
        
        // if(count($data)){
            $response['status'] = 'success';
            $response['data'] = $data;
            $main_cat_name = ['MEAL','POPULAR'];
            $main_cat = MainCategory::with('subCategory')->whereNotIn('name',$main_cat_name)->get();
            $recipe_category= MpMealCategory::select('id','name')->get();
            // C:\xampp\htdocs\epicfitlaravelv6\resources\views\includes\partials\filter_tag.blade.php
            $html = View::make('includes.partials.filter_popup_mob_calendar', compact('main_cat','recipe_category','tags','recipe_tags','include_list','exclude_list'));
            $response['mob_html'] = $html->render();
            $html = View::make('includes.partials.filter_tag', compact('sub_cat_name','recipe_cat_name'));
            $response['tag'] = $html->render();
            $html = View::make('includes.partials.filter_popup_calendar', compact('main_cat','recipe_category','tags','recipe_tags','include_list','exclude_list'));
            $response['html'] = $html->render();
        // }
        return response()->json($response);
    }

    /**
     * Get Food list
     *
     * @param
     * @return
     */
    public function getFoodList(Request $request){
        $response = array('status'=>'error'); 
        $data = array();      
        $query = MpFoods::where('business_id', Session::get('businessId'));
        if($request->has('text')){
            $text = $request->text;
            $meals = $query->where('name','like',"%$text%");
        }
        $foods = $query->get();
        if($foods->count()){
            $i = 0;
            foreach ($foods as $food){
                $data[$i]['id'] = $food->id;
                $data[$i]['name'] = $food->name;
                $data[$i]['cat'] = '';
                $data[$i]['img'] = $food->food_img;;

                $i++;
            }
        } 
        if(count($data)){
            $response['status'] = 'success';
            $response['data'] = $data;
        }

        return response()->json($response);
    }

    /**
     * Get meal Deatils
     *
     * @param
     * @return
     */
    public function getMeal($id){
        $data = array('status'=>'error');        
        $meals = MpMeals::find($id);
        if($meals->count()){ 
            $data['id'] = $meals->id;
            $data['type'] = "Meal";  
            $data['name'] = $meals->name;
            $data['category_id'] = $meals->category_id; 
            $data['category_name'] = $meals->category->name;
            $data['serving_id'] = $meals->serving_id;
            $data['serving_name'] = $meals->serving_id;
            $data['serves'] = $meals->serves;
            $data['tips'] = $meals->tips;
            $data['description'] = $meals->description;
            $data['method'] = $meals->method;
            $data['ingredients'] = $meals->ingredients;
            $data['time'] = $meals->time;
            $data['cook_time'] = $meals->cook_time;
           
            /*  */
            $prep_time = json_decode($data['time']);
            $cook_time = json_decode($data['cook_time']);
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
           
            $data['total_hrs'] = $total_hrs;
            $data['total_mins'] = $total_min;
            /*  */
            $data['tags'] = $meals->tags->pluck('mp_tag_name')->toArray();

            $image = $meals->mealimages->first();
            if(count($image))
                $data['img'] = $image->mmi_img_name; 
            else
                $data['img'] = '';

            /* nutrational data */
            $data['nutrInfo'] = [];
            $foods = $meals->foods;
            if(count($foods)){
                $data['nutrInfo'] = $this->getNutritionalInfo($foods);
            }else{
                $data['nutrInfo'] = json_decode($meals->nutritional_information);
            }
            $meals->mealIngredientSetPart1;
            $meals->mealIngredientSetPart2;
            $meals->mealPreparationPart1;
            $meals->mealPreparationPart2;
            $html = View::make('includes.partials.calendar-modal', compact('meals'));
            $data['html'] = $html->render();
            $data['status'] = 'success';
        }
        return response()->json($data);
    }

    /**
     * Get Food Deatils
     *
     * @param
     * @return
     */
    public function getFood($id){
        $data = array('status'=>'error');        
        $foods = MpFoods::find($id);
        if($foods->count()){  
            $data['name'] = $foods->name;
            $data['serving_id'] = $foods->serving_size;
            $data['serving_name'] = $foods->serving_size;
            $data['description'] = $foods->description;
            $data['serves'] = '';
            $data['tips'] = '';
            $data['category_id'] = ''; 
            $data['category_name'] = '';
            $data['method'] = '';
            $data['ingredients'] = '';
            $data['time'] = '';
            
            $data['tags'] = [];

            $data['img'] = $foods->food_img;

            /* nutrational data */
            $data['nutrInfo'] = $this->getNutritionalInfoFirst($foods);
            
            $data['status'] = 'success';
        }
        return response()->json($data);
    }


    /**
     * Delete Calendar Event
     *
     * @param
     * @return
     */

    public function deleteEvent(Request $request){
        $requestData = $request->all();

        try{
            $mpClientMealplan = MpClientMealplan::whereId($requestData['id'])->where('event_type',$requestData['type'])->first();
            $dateCarbon = Carbon::now();
            $date = $dateCarbon->toDateString();
            // if($mpClientMealplan->event_date < $date){
            //     $data = [
            //         'status' => 'error',
            //         'message' =>'Event is in past. You can not delete this event.'
            //     ];
            //     return response()->json($data);
            // }else{
                $mpClientMealplan->delete();
                $data = [
                    'status' => 'ok',
                    'message' =>'Event deleted successfully'
                ];
                return response()->json($data);
            // }
        }catch(\Throwable $e){
            $data = [
                'status' => 'error',
                'message' =>'Something went wrong'
            ];
            return response()->json($data);
        }
    }

}
