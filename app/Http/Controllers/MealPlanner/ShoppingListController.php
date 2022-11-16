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

use App\MpClientMealplan;
use App\ShoppingList;

class ShoppingListController extends Controller {

    /**
     * Retrive Shopping Category list.
     *
     * @param String  
     * @return Ingrediant list
     */
   

    public function index(Request $request){
        $clientId = Auth::user()->account_id;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $view = $request->view;
        $shoppinglists = array();
        $foods = array();

    //     $events = MpClientMealplan::whereDate('event_date','>=', $startDate)->whereDate('event_date','<', $endDate)->where('client_id',$clientId)->get();
     
    //     if($events->count()){
    //         $i = 0;
    //         foreach ($events as  $event) {
    //             $meal = $event->meal;
            
    //             $ingList =  preg_split('/\r\n|\r|\n/',$meal->ingredients);
                
    //            foreach($ingList as $key =>$value){
                  
    //              $newList = strip_tags($value);
    //              $removeTags = trim($newList);
               
    //              $removeSpace= trim(preg_replace("/\s|&nbsp;/",' ',$removeTags));
    //              if($removeSpace != ' ' && $removeSpace != ''){
    //              $shoppinglists[$meal->name][] = $removeSpace;
    //              }
                
    //            }
              
    //         }
    //     }
    //     $ingList = [];
    // //     $result = array();
    // //     foreach($shoppinglists as $key => $meal){
    // //         foreach($meal as $ingr){
    // //         $result[$key][metaphone( $ingr, 2)][] = $ingr;
    // //         }
         
    // //     }
        
    // //     $newResult = [];
    // //     foreach($result as $key =>$data){
    // //         foreach($data as $value){
    // //             $newResult[$key][] =  $value;
    // //         }

    // //     }
    // //     $recUpdateData =[];
    // //     $newRecUpdateData =[];
    // //    foreach($newResult as $key => $rec){
    // //        foreach($rec as $value){
    // //            foreach($value as $data)
    // //            {
    // //             $recUpdateData[$key][] = $data;
    // //            }
         
    // //        }
    // //     //    $newRecUpdateData[$key][] = $recUpdateData;
    // //    }
    // //    dd($recUpdateData);
    //     foreach($shoppinglists as $key => $meal){
    //         foreach($meal as $ingr){
    //             $data =explode(' ', $ingr, 2);
    //             $ingList[$key][] =  $data;
    //         }
    //     }
    //     $shoppingData = [];
    //     $recipeList = [];
   
    //     foreach($ingList as $key => $recipe){
    //        foreach($recipe as $value){
          
    //         if (strpos($value[0], 'g') !== false) {
    //             $shoppingData[$value[1]]['quantity'] = (array_key_exists($value[1],$shoppingData)? $shoppingData[$value[1]]['quantity']+$value[0]."g":$value[0]);
    //             $shoppingData[$value[1]]['recipe'][] = ['recName' => $key, 'quantity' =>$value[0] ];
    //         }
    //         else if (!is_numeric($value[0][0])) {
    //             $shoppingData[$value[0].' '.$value[1]]['quantity'] = '-';
    //             $shoppingData[$value[0].' '.$value[1]]['recipe'][] = ['recName' => $key, 'quantity' =>'-' ];
             
    //         }
    //         else{
    //             $value[0] = $this->fractionToDecimal($value[0]);
    //         $shoppingData[$value[1]]['quantity'] = array_key_exists($value[1],$shoppingData)?sprintf("%.2f",$shoppingData[$value[1]]['quantity']+$value[0]):$value[0];
    //         $shoppingData[$value[1]]['recipe'][] = ['recName' => $key, 'quantity' =>$value[0]];
    //         }
    //     }
           
    //     }
    

    //     foreach($shoppingData as $key => $value){
    //     //    dd($value['recipe']);
    //         $checkData = ShoppingList::where('client_id',$clientId)->where('rec_name',$key)->where('start_date',$startDate)->where('end_date', $endDate)->whereNull('deleted_at')->first();
    //         $mealRecipe = json_encode($value['recipe']);
    //         // dd( $mealRecipe);
    //         if(!$checkData){
           
    //          $insertData = array('client_id' => $clientId, 'rec_name' => $key, 'quantity' =>$value['quantity'],'start_date'=>$startDate ,'end_date'=> $endDate,'meal_recipe_name'=>$mealRecipe, 'created_at' => now());
    //             ShoppingList::create( $insertData );
    //         }else{
    //             $checkData->update(['quantity'=>$value['quantity'],'meal_recipe_name'=>$mealRecipe]);

    //         }
    //     }
       $onlyShoppingList = ShoppingList::where('client_id',$clientId)
                          ->where('start_date',$startDate)
                          ->where('end_date', $endDate)
                          ->whereNull('deleted_at')
                          ->whereNull('purchased_date')
                          ->get();
        $shoppingNewData = ShoppingList::where('client_id',$clientId)->where('start_date',$startDate)->where('end_date', $endDate)->whereNull('deleted_at')->get();
        return view('mealplanner.shopping.index', compact('shoppingNewData','startDate','endDate','clientId','onlyShoppingList'));
       
    }
    public function update(Request $request){
        foreach($request->shoppingData as $key => $value){
           $checkData = ShoppingList::where('client_id',$request->clientId)
                        ->where('rec_name',$value['name'])
                        ->where('start_date',$request->startDate)
                        ->where('end_date', $request->endDate)
                        ->where('id', $value['shopping_id'])   // add new for detail page                
                        ->whereNull('deleted_at')
                        ->first();
   
           if( $checkData ){
            // if (strpos($value['quantity'], 'g') !== false) {
            //     $value['quantity'] = $value['quantity'];
            // }else{
            //     $value['quantity'] = $this->fractionToDecimal($value['quantity']);
            // }
           $checkData->update(['puchased_quan'=> $value['quantity'], 'purchased_date' =>now()]);
           }
        //    else{
        //     $insertData = array('client_id' => $request->clientId, 'rec_name' => $value['name'], 'quantity' => $value['quantity'],'puchased_quan'=> $value['quantity'],'start_date'=>$request->startDate ,'end_date'=> $request->endDate, 'created_at' => now());
        //         ShoppingList::create( $insertData );
        //    }
        }
        $msg = 'Quantity Successfully Updated';
        return $msg;
            
    }

    public function fractionToDecimal($fraction) 
        {
            // Split fraction into whole number and fraction components
            preg_match('/^(?P<whole>\d+)?\s?((?P<numerator>\d+)\/(?P<denominator>\d+))?$/', $fraction, $components);

            // Extract whole number, numerator, and denominator components
            $whole = $components['whole'] ?: 0;
            $numerator = $components['numerator'] ?: 0;
            $denominator = $components['denominator'] ?: 0;

            // Create decimal value
            $decimal = $whole;
            $numerator && $denominator && $decimal += ($numerator/$denominator);

            return $decimal;
        }
      
   
        public function convert_decimal_to_fraction($fraction) {
            $base = floor($fraction);
            $fraction -= $base;
            if( $fraction == 0 ) return $base;
            list($ignore, $numerator) = preg_split('/\./', $fraction, 2);
            $denominator = pow(10, strlen($numerator));
            $gcd =$this->gcd($numerator, $denominator);
            $fraction = ($numerator / $gcd) . '/' . ($denominator / $gcd);
            if( $base > 0 ) {
              return $base . ' ' . $fraction;
            } else {
              return $fraction;
            }        
          }
        public function gcd($a,$b) {
            return ($a % $b) ? $this->gcd($b,$a % $b) : $b;
          }


      /* shopping delete */
     public function deleteShopping(Request $request){
         $delete = ShoppingList::whereIn('id',$request->id)->delete();
          if($delete){
            $response['status'] = 'success';
            return \Response::json($response); 
          }
     }

}
