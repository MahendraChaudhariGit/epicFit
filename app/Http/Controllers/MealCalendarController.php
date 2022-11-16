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


class MealCalendarController extends Controller {
    use HelperTrait, MpPlannerTrait, CalendarSettingTrait;
    
    /**
     * Show meal calendar 
     *
     * @param 
     * @return 
    */
    public function show(){

        $calendar_settings = $this->getCalendSettingsForClient(Auth::user()->account_id);
        $mealsCategory = $this->mealCategory();
        return view('mealplanner.meal-calendar', compact('calendar_settings','mealsCategory'));
    }

    /**
     * Store meal event 
     *
     * @param 
     * @return 
    */
    public function store(Request $request){
        $msg['status'] = 'error';

        $clientMealplan = new MpClientMealplan;
        $clientMealplan->event_id = $request->id;
        $clientMealplan->event_type = $request->type;
        $clientMealplan->event_date = $request->date;
        $clientMealplan->event_meal_category = $request->cat;
        if($clientMealplan->save())
            $msg['status'] = 'success';

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

        $events = MpClientMealplan::whereDate('event_date','>=', $startDate)->whereDate('event_date','<=', $endDate)->get();
        if($events->count()){
            $i = 0;
            foreach ($events as  $event) {
                $response[$i]['eventid'] = $event->id;
                $response[$i]['type'] = $event->event_type;
                $response[$i]['catid'] = $event->event_meal_category;
                $response[$i]['id'] = $event->event_id;
                if($event->event_meal_category != 0){
                    $response[$i]['startDatetime'] = $this->getStartAndEndDate($event->event_date, $event->category->name);
                }
                else
                    $response[$i]['startDatetime'] = $this->getStartAndEndDate($event->event_date, '');
                
                if($event->event_type == 'Meal')
                    $response[$i]['title'] = $event->meal->name;
                elseif($event->event_type == 'Food')
                    $response[$i]['title'] = $event->food->name;

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
    protected function getStartAndEndDate($date, $cat){
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
        return $carbonDate->format("Y-m-d h:i:s");
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
            if($event->event_type == 'Meal'){
                $meals = $event->meal;
                $data['name'] = $meals->name;
                $data['serving_id'] = $meals->serving_id;
                $data['serving_name'] = $meals->serving_id;
                $data['serves'] = $meals->serves;
                $data['tips'] = $meals->tips;
                $data['description'] = $meals->description;
                $data['method'] = $meals->method;
                $data['ingredients'] = $meals->ingredients;
                $data['time'] = $meals->time;
                
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
                }

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
        
        $query = MpMeals::where('business_id', Session::get('businessId'));
        if($request->has('text')){
            $text = $request->text;
            $meals = $query->where('name','like',"%$text%");
        }

        $meals = $query->get();
        if($meals->count()){
            $i = 0;
            foreach ($meals as $meal){
                $data[$i]['id'] = $meal->id;
                $data[$i]['name'] = $meal->name;
 
                if($meal->category)
                    $data[$i]['cat'] = $meal->category->name;
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
        if(count($data)){
            $response['status'] = 'success';
            $response['data'] = $data;
        }

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
            }

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

}
