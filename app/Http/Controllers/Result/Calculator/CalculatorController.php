<?php

namespace App\Http\Controllers\Result\Calculator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Result\Calculators\AdvancedRestingMetabolismCalculator;
use App\Result\Calculators\BasalMetabolismRateCalculator;
use App\Result\Calculators\BodyFatNavyCalculator;
use App\Result\Calculators\BodyFatYmcaCalculator;
use App\Result\Calculators\BodyMassIndexCalculator;
use App\Result\Calculators\CalorieBreakdownCalculator;
use App\Result\Calculators\DailyMetabolismCalculator;
use App\Result\Calculators\FullBodyAnalysisCalculator;
use App\Result\Calculators\IdealWeightCalculator;
use App\Result\Calculators\LeanBodyMassCalculator;
use App\Result\Calculators\RestingMetabolismCalculator;
use App\Result\Calculators\TargetHeartRateCalculator;
use App\Result\Calculators\WaistHipRatioCalculator;
use App\Result\Models\AdvancedRestingMetabolism;
use App\Result\Models\BasalMetabolismRate;
use App\Result\Models\BodyFatNavy;
use App\Result\Models\BodyFatYmca;
use App\Result\Models\BodyMassIndex;
use App\Result\Models\CalorieBreakdown;
use App\Result\Models\DailyMetabolism;
use App\Result\Models\FullBodyAnalysis;
use App\Result\Models\IdealWeight;
use App\Result\Models\LeanBodyMass;
use App\Result\Models\RestingMetabolism;
use App\Result\Models\TargetHeartRate;
use App\Result\Models\WaistHipRatio;
use App\ClientMenu;
use App\Session as AppSession;
use Redirect;
use Session;


class CalculatorController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     */

    public function __construct()
    {
        $clientSelectedMenus = [];

        $this->middleware(function ($request, $next){
            if(Auth::user()->account_type == 'Client') {
                $selectedMenus = ClientMenu::where('client_id', Auth::user()->account_id)->pluck('menues')->first();
                $clientSelectedMenus = $selectedMenus ? explode(',', $selectedMenus) : [];
     
                if(!in_array('calculator', $clientSelectedMenus)){
                  Redirect::to('access-restricted')->send();
                }else{
                    return $next($request);
                }
            }    
        });
       
    }
    
    public function index(){

        return view('Result.calculators.index');
    }

    public function bodyMassIndexCalculator($type=null)
    {
        if($type != null){

            $clientData = BodyMassIndex::where('client_id', Auth::user()->account_id)->where('type', $type)->orderBy('id', 'DESC')->first();

            return $clientData;

        }
        else {

           $clientData = BodyMassIndex::where('client_id', Auth::user()->account_id)->where('type','metric' )->orderBy('id', 'DESC')->first();
        
            return view('Result.calculators.body-mass-index', ['clientData' => $clientData]);
        }

        
    }


    public function storeBodyMassIndexCalculation(Request $request)
    {
        $bodyMassIndex = new BodyMassIndex;

        $bodyMassIndexCalculator = new BodyMassIndexCalculator;

        $data = $request->all();

        $bodyMassIndex->client_id = Auth::user()->account_id;
        $bodyMassIndex->type = $data['type'];

        if ($data['type'] === 'metric') {
            $result = $bodyMassIndexCalculator->metric($data['height-m'], $data['weight-m']);
            $bodyMassIndex->weight = $data['weight-m'];
            $bodyMassIndex->height_ft = $data['height-m'];

        } else {
            $result = $bodyMassIndexCalculator->imperial($data['height-i-ft'], $data['height-i-in'], $data['weight-i']);

            $bodyMassIndex->weight = $data['weight-i'];
            $bodyMassIndex->height_ft = $data['height-i-ft'];
            $bodyMassIndex->height_in = $data['height-i-in'];
            //$bodyMassIndex->height = $bodyMassIndexCalculator->calculateHeight($data['height-i-ft'],
                //$data['height-i-in']);
        }
        $bodyMassIndex->bmi           = $result['bmi'];
        $bodyMassIndex->clasification = $result['classification'];
        $bodyMassIndex->weight_renge  = $result['weight_range'];


        $bodyMassIndex->save();

        return $result;
    }

    public function updateBodyMassIndexCalculation(Request $request)
    {
        $bodyMassIndex = new BodyMassIndex;

        $bodyMassIndexCalculator = new BodyMassIndexCalculator;

        $data = $request->all();

        $bodyMassIndex->type = $data['type'];

        if ($data['type'] === 'metric') {
            $result = $bodyMassIndexCalculator->metric($data['height-m'], $data['weight-m']);

            $bodyMassIndex->weight = $data['weight-m'];
            $bodyMassIndex->height_ft = $data['height-m'];
        } else {
            $result = $bodyMassIndexCalculator->imperial($data['height-i-ft'], $data['height-i-in'], $data['weight-i']);

            $bodyMassIndex->weight = $data['weight-i'];
            $bodyMassIndex->height_ft = $data['height-i-ft'];
           // $bodyMassIndex->height = $bodyMassIndexCalculator->calculateHeight($data['height-i-ft'],
               // $data['height-i-in']);
        }
        $result['status']= 'updated';

        $bmi= BodyMassIndex::where('id', $data['record_id'])->first();
        if(count($bmi)){
            $bmi->update(array(
                'type' => $data['type'],
                'weight' => $bodyMassIndex->weight,              
                'height_in' =>$data['height-i-in'],
                'height_ft' => $bodyMassIndex->height_ft,
                'bmi' => $result['bmi'],
                'clasification' => $result['classification'],
                'weight_renge' => $result['weight_range']
            ));
        }
    
        return $result;
    }



    public function basalMetabolismRateCalculator($type=null,$gender=null,$equation_type=null)
    {
        if($type != null && $gender != null && $equation_type != null){

            $clientData = BasalMetabolismRate::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('gender', '=',$gender )->where('equation_type', '=',$equation_type )->orderBy('id', 'DESC')->first();

              return $clientData;

        }
        else {

           $clientData = BasalMetabolismRate::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('gender', '=','male' )->where('equation_type', '=','mior' )->orderBy('id', 'DESC')->first();

            return view('Result.calculators.basal-metabolism-rate', ['clientData' => $clientData]);
        }


        
    }

    public function storeBasalMetabolismRateCalculation(Request $request)
    {
        $basalMetabolismRate = new BasalMetabolismRate;

        $basalMetabolismRateCalculator = new BasalMetabolismRateCalculator;

        $data = $request->all();


        $basalMetabolismRateCalculator->age      = $data['age'];
        $basalMetabolismRateCalculator->gender   = $data['gender'];
        $basalMetabolismRateCalculator->equation = $data['equation'];

        $basalMetabolismRate->client_id = Auth::user()->account_id;
        $basalMetabolismRate->type     = $data['type'];
        $basalMetabolismRate->equation_type = $data['equation'];
        $basalMetabolismRate->gender   = $data['gender'];
        $basalMetabolismRate->age      = $data['age'];

        if ($data['type'] === 'metric') {
            $result = $basalMetabolismRateCalculator->metric($data['height-m'], $data['weight-m']);

            $basalMetabolismRate->weight = $data['weight-m'];
            $basalMetabolismRate->height_ft = $data['height-m'];
        } else {
            $result = $basalMetabolismRateCalculator->imperial($data['height-i-ft'], $data['height-i-in'],
                $data['weight-i']);

            $basalMetabolismRate->weight = $data['weight-i'];
            $basalMetabolismRate->height_ft = $data['height-i-ft'];
            $basalMetabolismRate->height_in = $data['height-i-in'];
            /*$basalMetabolismRate->height = $basalMetabolismRateCalculator->calculateHeight($data['height-i-ft'],
                $data['height-i-in']);*/
        }

        $basalMetabolismRate->brm = $result['brm'];

        $basalMetabolismRate->save();

        return $result;
    }

    public function updateBasalMetabolismRateCalculation(Request $request)
    {

        $basalMetabolismRateCalculator = new BasalMetabolismRateCalculator;

        $data = $request->all();


        if ($data['type'] === 'metric') {
            $result = $basalMetabolismRateCalculator->metric($data['height-m'], $data['weight-m']);

            $result['weight'] = $data['weight-m'];
            $result['height_ft'] = $data['height-m'];
        } else {
            $result = $basalMetabolismRateCalculator->imperial($data['height-i-ft'], $data['height-i-in'],
                $data['weight-i']);

            $result['weight'] = $data['weight-i'];
            $result['height_ft'] = $data['height-i-ft'];
        }
        $result['status']= 'updated';

        $brm= BasalMetabolismRate::where('id', $data['record_id'])->first();
        if(count($brm)){
            $brm->update(array(
                'type' => $data['type'],
                'gender' => $data['gender'],
                'equation_type' => $data['equation'],
                'age' => $data['age'],
                'weight' => $result['weight'],              
                'height_in' =>$data['height-i-in'],
                'height_ft' => $result['height_ft'],
                'brm' => $result['brm']
            ));
        }

        return $result;
    }

    public function targetHeartRateCalculator($goal=null)
    {
        if($goal != null){

            $clientData = TargetHeartRate::where('client_id', '=', Auth::user()->account_id)->where('goal', '=',$goal )->orderBy('id', 'DESC')->first();

             return $clientData;

        }
        else {

            $clientData = TargetHeartRate::where('client_id', '=', Auth::user()->account_id)->where('goal', '=','get-fit' )->orderBy('id', 'DESC')->first();

            return view('Result.calculators.target-heart-rate', ['clientData' => $clientData]);
        }
        
    }

    
    public function storeTargetHeartRateCalculation(Request $request)
    {
        $targetHeartRate = new TargetHeartRate;

        $targetHeartRateCalculator = new TargetHeartRateCalculator;

        $data = $request->all();

        $result = $targetHeartRateCalculator->calculate($data['goal'], $data['age'], $data['rhra']);

        $targetHeartRate->client_id = Auth::user()->account_id;
        $targetHeartRate->goal   = $data['goal'];
        $targetHeartRate->age    = $data['age'];
        $targetHeartRate->rhra   = $data['rhra'];
        $targetHeartRate->bpml   = $result['bpml'];
        $targetHeartRate->bpmh   = $result['bpmh'];
        $targetHeartRate->mhr    = $result['mhr'];
        $targetHeartRate->bptsl  = $result['bptsl'];
        $targetHeartRate->bptsh  = $result['bptsh'];
        $targetHeartRate->mhrits = $result['mhrits'];

        $targetHeartRate->save();

        return $result;
        
    }

    public function updateTargetHeartRateCalculation(Request $request)
    {
       // $targetHeartRate = new TargetHeartRate;

        $targetHeartRateCalculator = new TargetHeartRateCalculator;

        $data = $request->all();

        $result = $targetHeartRateCalculator->calculate($data['goal'], $data['age'], $data['rhra']);
        $result['status']= 'updated';

        $brm= TargetHeartRate::where('id', $data['record_id'])->first();
            if(count($brm)){
                $brm->update(array(
                    'goal' => $data['goal'],
                    'age' => $data['age'],
                    'rhra' => $data['rhra'],
                    'bpml' => $result['bpml'],
                    'bpmh' => $result['bpmh'],              
                    'mhr'  => $result['mhr'],
                    'bptsl' => $result['bptsl'],
                    'bptsh' => $result['bptsh'],
                    'mhrits' => $result['mhrits']
                ));
            }
        return $result;
        
    }

    public function idealWeightCalculator($type=null,$gender=null)
    {
        if($type != null  && $gender != null){

            $clientData = IdealWeight::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('gender', '=',$gender)->orderBy('id', 'DESC')->first();

            return $clientData;

        }
        else {

            $clientData = IdealWeight::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('gender', '=','male' )->orderBy('id', 'DESC')->first();

            return view('Result.calculators.ideal-weight', ['clientData' => $clientData]);
        }
        
    }


    public function storeIdealWeightCalculation(Request $request)
    {
        $idealWeight = new IdealWeight;

        $idealWeightCalculator = new IdealWeightCalculator;

        $data = $request->all();

        $idealWeight->client_id = Auth::user()->account_id;
        $idealWeight->type   = $data['type'];
        $idealWeight->gender = $data['gender'];

        if ($data['type'] === 'metric') {
            $result = $idealWeightCalculator->metric($data['gender'], $data['height-m']);

            $idealWeight->height_ft = $data['height-m'];
        } else {
            $result = $idealWeightCalculator->imperial($data['gender'], $data['height-i-ft'], $data['height-i-in']);

            //$idealWeight->height = $idealWeightCalculator->calculateHeight($data['height-i-ft'], $data['height-i-in']);
            $idealWeight->height_ft = $data['height-i-ft'];
            $idealWeight->height_in = $data['height-i-in'];
        }

        $idealWeight->ideal_weight = $result['iw'];

        $idealWeight->save();

        return $result;
    }

    public function updateIdealWeightCalculation(Request $request)
    {

        $idealWeightCalculator = new IdealWeightCalculator;

        $data = $request->all();

        if ($data['type'] === 'metric') {
            $result = $idealWeightCalculator->metric($data['gender'], $data['height-m']);

            $result['height_ft'] = $data['height-m'];
        } else {
            $result = $idealWeightCalculator->imperial($data['gender'], $data['height-i-ft'], $data['height-i-in']);

            //$idealWeight->height = $idealWeightCalculator->calculateHeight($data['height-i-ft'], $data['height-i-in']);
            $result['height_ft'] = $data['height-i-ft'];
        }

        $result['status']= 'updated';
        $brm= IdealWeight::where('id', $data['record_id'])->first();
            if(count($brm)){
                $brm->update(array(
                    'type' => $data['type'],
                    'gender' => $data['gender'],
                    'height_ft' => $result['height_ft'],
                    'height_in' => $data['height-i-in'],            
                    'ideal_weight'  => $result['iw']
                ));
            }

        return $result;
    }


    public function calorieBreakdownCalculator($gender=null)
    {
         if($gender != null){

            $clientData = CalorieBreakdown::where('client_id', '=', Auth::user()->account_id)->where('gender', '=',$gender )->orderBy('id', 'DESC')->first();
            return $clientData;

         }
         else{
            $clientData = CalorieBreakdown::where('client_id', '=', Auth::user()->account_id)->where('gender', '=','male' )->orderBy('id', 'DESC')->first();
            return view('Result.calculators.calorie-breakdown', ['clientData' => $clientData]);
         }

        
    }

    public function storeCalorieBreakdownCalculation(Request $request)
    {
        $calorieBreakdown = new CalorieBreakdown;

        $calorieBreakdownCalculator = new CalorieBreakdownCalculator;

        $data = $request->all();

        $result = $calorieBreakdownCalculator->calculate($data['gender'], $data['age'], $data['calorie']);

        $calorieBreakdown->client_id = Auth::user()->account_id;
        $calorieBreakdown->gender        = $data['gender'];
        $calorieBreakdown->age           = $data['age'];
        $calorieBreakdown->calorie       = $data['calorie'];
        $calorieBreakdown->fatl          = $result['fatl'];
        $calorieBreakdown->fath          = $result['fath'];
        $calorieBreakdown->proteinl      = $result['proteinl'];
        $calorieBreakdown->proteinh      = $result['proteinh'];
        $calorieBreakdown->fiber         = $result['fiber'];
        $calorieBreakdown->sugar         = $result['sugar'];
        $calorieBreakdown->carbohydratel = $result['carbohydratel'];
        $calorieBreakdown->carbohydrateh = $result['carbohydrateh'];

        $calorieBreakdown->save();

        return $result;
    }

    public function updateCalorieBreakdownCalculation(Request $request)
    {

        $calorieBreakdownCalculator = new CalorieBreakdownCalculator;

        $data = $request->all();

        $result = $calorieBreakdownCalculator->calculate($data['gender'], $data['age'], $data['calorie']);
        $result['status']= 'updated';

        $brm= CalorieBreakdown::where('id', $data['record_id'])->first();
            if(count($brm)){
                $brm->update(array(
                    'gender' => $data['gender'],
                    'age' => $data['age'],
                    'calorie' => $data['calorie'],
                    'fatl' => $result['fatl'],            
                    'fath'  => $result['fath'],
                    'proteinl' => $result['proteinl'],
                    'proteinh' => $result['proteinh'],
                    'fiber' => $result['fiber'],            
                    'sugar'  => $result['sugar'],
                    'carbohydratel' => $result['carbohydratel'],            
                    'carbohydrateh'  => $result['carbohydrateh'],

                ));
            }
        return $result;
    }

    public function restingMetabolismCalculator($type=null,$unittype=null)
    {
        if($type != null && $unittype != null){
          //  dd($type);
        $clientData = RestingMetabolism::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('lmi_type', '=',$unittype)->orderBy('id', 'DESC')->first();
            
            return $clientData;

         }
         else{
            $clientData = RestingMetabolism::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('lmi_type', '=','percent' )->orderBy('id', 'DESC')->first();
            return view('Result.calculators.resting-metabolism', ['clientData' => $clientData]);
         }

    }

    public function storeRestingMetabolismCalculation(Request $request)
    {
        $restingMetabolism = new RestingMetabolism;

        $restingMetabolismCalculator = new RestingMetabolismCalculator;

        $data = $request->all();
        $restingMetabolism->client_id = Auth::user()->account_id;
        $restingMetabolism->type = $data['type'];

        if ($data['type'] === 'metric') {
            $result = $restingMetabolismCalculator->metric($data['weight-m'], $data['mass-m'], $data['unit-type-m']);

            $restingMetabolism->weight   = $data['weight-m'];
            $restingMetabolism->lmi      = $data['mass-m'];
            $restingMetabolism->lmi_type = $data['unit-type-m'];
        } else {
            $result = $restingMetabolismCalculator->imperial($data['weight-i'], $data['mass-i'], $data['unit-type-i']);

            $restingMetabolism->weight   = $data['weight-i'];
            $restingMetabolism->lmi      = $data['mass-i'];
            $restingMetabolism->lmi_type = $data['unit-type-i'];
        }

        $restingMetabolism->rm  = $result['rm'];
        $restingMetabolism->lm  = $result['lm'];
        $restingMetabolism->lmp = $result['lmp'];
        $restingMetabolism->fm  = $result['fm'];
        $restingMetabolism->fmp = $result['fmp'];

        $restingMetabolism->save();

        return $result;
    }

    public function updateRestingMetabolismCalculation(Request $request)
    {

        $restingMetabolismCalculator = new RestingMetabolismCalculator;

        $data = $request->all();

        if ($data['type'] === 'metric') {
            $result = $restingMetabolismCalculator->metric($data['weight-m'], $data['mass-m'], $data['unit-type-m']);

            $result['weight']    = $data['weight-m'];
            $result['lmi']       = $data['mass-m'];
            $result['lmi_type']  = $data['unit-type-m'];
        } else {
            $result = $restingMetabolismCalculator->imperial($data['weight-i'], $data['mass-i'], $data['unit-type-i']);

            $result['weight']   = $data['weight-i'];
            $result['lmi']      = $data['mass-i'];
            $result['lmi_type'] = $data['unit-type-i'];
        }

        $result['status']= 'updated';

        $brm= RestingMetabolism::where('id', $data['record_id'])->first();
        if(count($brm)){
            $brm->update(array(
                'type' => $data['type'],
                'weight' => $result['weight'],
                'lmi' => $result['lmi'],
                'lmi_type' => $result['lmi_type'],            
                'rm'  => $result['rm'],
                'lm' => $result['lm'],
                'lmp' => $result['lmp'],
                'fm' => $result['fm'],            
                'fmp'  => $result['fmp']
            ));
        }

        return $result;
    }


    public function advancedRestingMetabolismCalculator($type=null,$gender=null)
    {
        if($type != null && $gender != null){
          //  dd($type);
        $clientData = AdvancedRestingMetabolism::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('gender', '=',$gender)->orderBy('id', 'DESC')->first();
            
            return $clientData;
         }
         else
         {
            $clientData = AdvancedRestingMetabolism::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('gender', '=','male' )->orderBy('id', 'DESC')->first();

            return view('Result.calculators.advanced-resting-metabolism', ['clientData' => $clientData]);
         }

    }

    public function storeAdvancedRestingMetabolismCalculation(Request $request)
    {
        $advancedRestingMetabolism = new AdvancedRestingMetabolism;

        $advancedRestingMetabolismCalculator = new AdvancedRestingMetabolismCalculator;

        $data = $request->all();

        $advancedRestingMetabolism->client_id = Auth::user()->account_id;
        $advancedRestingMetabolismCalculator->gender = $data['gender'];
        $advancedRestingMetabolismCalculator->age    = $data['age'];

        $advancedRestingMetabolism->type   = $data['type'];
        $advancedRestingMetabolism->gender = $data['gender'];
        $advancedRestingMetabolism->age    = $data['age'];

        if ($data['type'] === 'metric') {
            $result = $advancedRestingMetabolismCalculator->metric($data['weight-m'], $data['height-m']);

            $advancedRestingMetabolism->weight = $data['weight-m'];
            $advancedRestingMetabolism->height_ft = $data['height-m'];
        } else {
            $result = $advancedRestingMetabolismCalculator->imperial($data['weight-i'], $data['height-i-ft'], $data['height-i-in']);

            $advancedRestingMetabolism->weight = $data['weight-i'];
            $advancedRestingMetabolism->height_ft = $data['height-i-ft'];
            $advancedRestingMetabolism->height_in = $data['height-i-in'];
           // $advancedRestingMetabolism->height = $advancedRestingMetabolismCalculator->calculateHeight($data['height-i-ft'], $data['height-i-in']);
        }
       // dd($result);
        $advancedRestingMetabolism->rm = $result['arm'];

        $advancedRestingMetabolism->save();

        return $result;
    }

    public function updateAdvancedRestingMetabolismCalculation(Request $request)
    {
        $advancedRestingMetabolismCalculator = new AdvancedRestingMetabolismCalculator;

        $data = $request->all();

        $advancedRestingMetabolismCalculator->gender = $data['gender'];
        $advancedRestingMetabolismCalculator->age    = $data['age'];

        if ($data['type'] === 'metric') {
            $result = $advancedRestingMetabolismCalculator->metric($data['weight-m'], $data['height-m']);

            $result['weight']     = $data['weight-m'];
            $result['height_ft']  = $data['height-m'];
            //$result['height_in']  = '';
        } else {
            $result = $advancedRestingMetabolismCalculator->imperial($data['weight-i'], $data['height-i-ft'], $data['height-i-in']);

            $result['weight']    = $data['weight-i'];
            $result['height_ft'] = $data['height-i-ft'];
           // $result['height_in'] = $data['height-i-in'];
           // $advancedRestingMetabolism->height = $advancedRestingMetabolismCalculator->calculateHeight($data['height-i-ft'], $data['height-i-in']);
        }
        $result['status']= 'updated';

        $brm= AdvancedRestingMetabolism::where('id', $data['record_id'])->first();
        if(count($brm)){
            $brm->update(array(
                'type' => $data['type'],
                'gender' => $data['gender'],
                'age' => $data['age'],
                'weight' => $result['weight'],            
                'height_ft'  => $result['height_ft'],
                'height_in' => $data['height-i-in'],
                'rm' => $result['arm']
            ));
        }

        return $result;
    }

    public function dailyMetabolismCalculator($type=null,$gender=null,$activity=null)
    {
        if($type != null && $gender != null && $activity != null){
          //  dd($type);
        $clientData = DailyMetabolism::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('gender', '=',$gender)->where('activity', '=',$activity)->orderBy('id', 'DESC')->first();
            
            return $clientData;
         }
         else
         {
            $clientData = DailyMetabolism::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('gender', '=','male' )->where('activity', '=','sedentary')->orderBy('id', 'DESC')->first();

            return view('Result.calculators.daily-metabolism', ['clientData' => $clientData]);
         }
    }

    public function storeDailyMetabolismCalculation(Request $request)
    {
        $dailyMetabolism = new DailyMetabolism;

        $dailyMetabolismCalculator = new DailyMetabolismCalculator;

        $data = $request->all();

        $dailyMetabolism->client_id = Auth::user()->account_id;
        $dailyMetabolismCalculator->gender   = $data['gender'];
        $dailyMetabolismCalculator->activity = $data['activity'];
        $dailyMetabolismCalculator->age      = $data['age'];

        $dailyMetabolism->type     = $data['type'];
        $dailyMetabolism->gender   = $data['gender'];
        $dailyMetabolism->activity = $data['activity'];
        $dailyMetabolism->age      = $data['age'];

        if ($data['type'] === 'metric') {
            $result = $dailyMetabolismCalculator->metric($data['weight-m'], $data['height-m']);

            $dailyMetabolism->weight = $data['weight-m'];
            $dailyMetabolism->height_ft = $data['height-m'];
        } else {

            $result = $dailyMetabolismCalculator->imperial($data['weight-i'], $data['height-i-ft'],
                $data['height-i-in']);

            $dailyMetabolism->weight = $data['weight-i'];
            $dailyMetabolism->height_ft = $data['height-i-ft'];
            $dailyMetabolism->height_in = $data['height-i-in'];
            /*$dailyMetabolism->height = $dailyMetabolismCalculator->calculateHeight($data['height-i-ft'],
                $data['height-i-in']);*/
        }

        $dailyMetabolism->aam   = $result['aam'];
        $dailyMetabolism->aamph = $result['aamph'];
        $dailyMetabolism->arm   = $result['arm'];

        $dailyMetabolism->save();

        return $result;
    }

    public function updateDailyMetabolismCalculation(Request $request)
    {
        $dailyMetabolismCalculator = new DailyMetabolismCalculator;

        $data = $request->all();

        $dailyMetabolismCalculator->gender   = $data['gender'];
        $dailyMetabolismCalculator->activity = $data['activity'];
        $dailyMetabolismCalculator->age      = $data['age'];


        if ($data['type'] === 'metric') {
            $result = $dailyMetabolismCalculator->metric($data['weight-m'], $data['height-m']);

            $result['weight'] = $data['weight-m'];
            $result['height_ft'] = $data['height-m'];
        } else {

            $result = $dailyMetabolismCalculator->imperial($data['weight-i'], $data['height-i-ft'],
                $data['height-i-in']);

            $result['weight']       = $data['weight-i'];
            $result['height_ft']    = $data['height-i-ft'];
            /*$dailyMetabolism->height = $dailyMetabolismCalculator->calculateHeight($data['height-i-ft'],
                $data['height-i-in']);*/
        }

        $result['status'] = 'updated';

        $brm= DailyMetabolism::where('id', $data['record_id'])->first();
        if(count($brm)){
            $brm->update(array(
                'type' => $data['type'],
                'gender' => $data['gender'],
                'activity' => $data['activity'],
                'age' => $data['age'],            
                'weight'  => $result['weight'],
                'height_ft' => $result['height_ft'],
                'height_in' => $data['height-i-ft'],
                'aam'  => $result['aam'],
                'aamph' => $result['aamph'],
                'arm' => $result['arm']
            ));
        }

        return $result;
    }


    public function bodyFatNavyCalculator($type=null,$gender=null)
    {
        if($type != null && $gender != null){
          //  dd($type);
        $clientData = BodyFatNavy::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('gender', '=',$gender)->orderBy('id', 'DESC')->first();
            
            return $clientData;
         }
         else
         {
            $clientData = BodyFatNavy::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('gender', '=','male' )->orderBy('id', 'DESC')->first();

            return view('Result.calculators.body-fat-navy', ['clientData' => $clientData]);
         }

    }

    public function storeBodyFatNavyCalculation(Request $request)
    {
        $bodyFatNavy = new BodyFatNavy;

        $bodyFatNavyCalculator = new BodyFatNavyCalculator;

        $data = $request->all();

        $bodyFatNavyCalculator->gender = $data['gender'];
        $bodyFatNavyCalculator->waist  = $data['waist'];
        $bodyFatNavyCalculator->neck   = $data['neck'];
        $bodyFatNavyCalculator->hip    = $data['hip'];

        $bodyFatNavy->client_id = Auth::user()->account_id;
        $bodyFatNavy->type   = $data['type'];
        $bodyFatNavy->gender = $data['gender'];
        $bodyFatNavy->waist  = $data['waist'];
        $bodyFatNavy->neck   = $data['neck'];
        $bodyFatNavy->hip    = $data['hip'];

        if ($data['type'] === 'metric') {
            $result = $bodyFatNavyCalculator->metric($data['weight-m'], $data['height-m']);

            $bodyFatNavy->weight = $data['weight-m'];
            $bodyFatNavy->height_ft = $data['height-m'];
        } else {
            $result = $bodyFatNavyCalculator->imperial($data['weight-i'], $data['height-i-ft'],
                $data['height-i-in']);

            $bodyFatNavy->weight = $data['weight-i'];
            $bodyFatNavy->height_ft =  $data['height-i-ft'];
            $bodyFatNavy->height_in = $data['height-i-in'];
            /*$bodyFatNavy->height = $bodyFatNavyCalculator->calculateHeight($data['height-i-ft'],
                $data['height-i-in']);*/
        }

        $bodyFatNavy->bf  = $result['bf'];
        $bodyFatNavy->fm  = $result['fm'];
        $bodyFatNavy->lm  = $result['lm'];
        $bodyFatNavy->bfc = $result['bfc'];
      //  dd( $result['bfc']);
        $bodyFatNavy->save();

        return $result;
    }

    public function updateBodyFatNavyCalculation(Request $request)
    {

        $bodyFatNavyCalculator = new BodyFatNavyCalculator;

        $data = $request->all();

        $bodyFatNavyCalculator->gender = $data['gender'];
        $bodyFatNavyCalculator->waist  = $data['waist'];
        $bodyFatNavyCalculator->neck   = $data['neck'];
        $bodyFatNavyCalculator->hip    = $data['hip'];


        if ($data['type'] === 'metric') {
            $result = $bodyFatNavyCalculator->metric($data['weight-m'], $data['height-m']);

            $result['weight'] = $data['weight-m'];
            $result['height_ft'] = $data['height-m'];
        } else {
            $result = $bodyFatNavyCalculator->imperial($data['weight-i'], $data['height-i-ft'],
                $data['height-i-in']);

            $result['weight']  = $data['weight-i'];
            $result['height_ft'] =  $data['height-i-ft'];
            /*$bodyFatNavy->height = $bodyFatNavyCalculator->calculateHeight($data['height-i-ft'],
                $data['height-i-in']);*/
        }

        $result['status']= 'updated';

        $brm= BodyFatNavy::where('id', $data['record_id'])->first();
        if(count($brm)){
            $brm->update(array(
                'type' => $data['type'],
                'gender' => $data['gender'],
                'waist' => $data['waist'],
                'neck' => $data['neck'],            
                'hip'  => $data['hip'],
                'weight'  => $result['weight'],
                'height_ft' => $result['height_ft'],
                'height_in' => $data['height-i-in'],
                'bf'  => $result['bf'],
                'fm' => $result['fm'],
                'lm' => $result['lm'],
                'bfc' => $result['bfc']
            ));
        }
//dd($result);
        return $result;
    }


    public function bodyFatYmcaCalculator($type=null,$gender=null)
    {
        if($type != null && $gender != null){
          //  dd($type);
        $clientData = BodyFatYmca::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('gender', '=',$gender)->orderBy('id', 'DESC')->first();
            
            return $clientData;
         }
         else
         {
            $clientData = BodyFatYmca::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('gender', '=','male' )->orderBy('id', 'DESC')->first();

            return view('Result.calculators.body-fat-ymca', ['clientData' => $clientData]);
         }
    }

    public function storeBodyFatYmcaCalculation(Request $request)
    {
        $bodyFatYmca = new BodyFatYmca;

        $bodyFatYmcaCalculator = new BodyFatYmcaCalculator;

        $data = $request->all();

        $bodyFatYmcaCalculator->gender = $data['gender'];
        $bodyFatYmcaCalculator->waist  = $data['waist'];

        $bodyFatYmca->client_id = Auth::user()->account_id;
        $bodyFatYmca->type   = $data['type'];
        $bodyFatYmca->gender = $data['gender'];
        $bodyFatYmca->waist  = $data['waist'];

        if ($data['type'] === 'metric') {
            $result = $bodyFatYmcaCalculator->metric($data['weight-m']);

            $bodyFatYmca->weight = $data['weight-m'];
        } else {
            $result = $bodyFatYmcaCalculator->imperial($data['weight-i']);

            $bodyFatYmca->weight = $data['weight-i'];
        }

        $bodyFatYmca->bf  = $result['bf'];
        $bodyFatYmca->fm  = $result['fm'];
        $bodyFatYmca->lm  = $result['lm'];
        $bodyFatYmca->bfc = $result['bfc'];

        $bodyFatYmca->save();

        return $result;
    }

    public function updateBodyFatYmcaCalculation(Request $request)
    {

        $bodyFatYmcaCalculator = new BodyFatYmcaCalculator;

        $data = $request->all();

        $bodyFatYmcaCalculator->gender = $data['gender'];
        $bodyFatYmcaCalculator->waist  = $data['waist'];

        if ($data['type'] === 'metric') {
            $result = $bodyFatYmcaCalculator->metric($data['weight-m']);

            $result['weight'] = $data['weight-m'];
        } else {
            $result = $bodyFatYmcaCalculator->imperial($data['weight-i']);

            $result['weight'] = $data['weight-i'];
        }

        $result['status']= 'updated';

        $brm= BodyFatYmca::where('id', $data['record_id'])->first();
        if(count($brm)){
            $brm->update(array(
                'type' => $data['type'],
                'gender' => $data['gender'],
                'waist' => $data['waist'],
                'weight'  => $result['weight'],
                'bf'  => $result['bf'],
                'fm' => $result['fm'],
                'lm' => $result['lm'],
                'bfc' => $result['bfc']
            ));
        }

        return $result;
    }

    public function leanBodyMassCalculator($type=null,$gender=null)
    {
        if($type != null && $gender != null){
          //  dd($type);
        $clientData = LeanBodyMass::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('gender', '=',$gender)->orderBy('id', 'DESC')->first();
            
            return $clientData;
         }
         else
         {
            $clientData = LeanBodyMass::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('gender', '=','male' )->orderBy('id', 'DESC')->first();

            return view('Result.calculators.lean-body-mass', ['clientData' => $clientData]);
         }
    }

    public function storeLeanBodyMassCalculation(Request $request)
    {
        $leanBodyMass = new LeanBodyMass;

        $leanBodyMassCalculator = new LeanBodyMassCalculator;

        $data = $request->all();

        $leanBodyMassCalculator->gender = $data['gender'];

        $leanBodyMass->client_id = Auth::user()->account_id;
        $leanBodyMass->type   = $data['type'];
        $leanBodyMass->gender = $data['gender'];

        if ($data['type'] === 'metric') {
            $result = $leanBodyMassCalculator->metric($data['weight-m'], $data['height-m']);

            $leanBodyMass->weight = $data['weight-m'];
            $leanBodyMass->height_ft = $data['height-m'];
        } else {
            $result = $leanBodyMassCalculator->imperial($data['weight-i'], $data['height-i-ft'], $data['height-i-in']);

            $leanBodyMass->weight = $data['weight-i'];
            $leanBodyMass->height_ft = $data['height-i-ft'];
            $leanBodyMass->height_in = $data['height-i-in'];
            /*$leanBodyMass->height = $leanBodyMassCalculator->calculateHeight($data['height-i-ft'],
                $data['height-i-in']);*/
        }

        $leanBodyMass->lm  = $result['lm'];
        $leanBodyMass->lmp = $result['lmp'];
        $leanBodyMass->fm  = $result['fm'];
        $leanBodyMass->fmp = $result['fmp'];
        $leanBodyMass->save();

        return $result;
    }

    public function updateLeanBodyMassCalculation(Request $request)
    {

        $leanBodyMassCalculator = new LeanBodyMassCalculator;

        $data = $request->all();

        $leanBodyMassCalculator->gender = $data['gender'];

        if ($data['type'] === 'metric') {
            $result = $leanBodyMassCalculator->metric($data['weight-m'], $data['height-m']);

            $result['weight']     = $data['weight-m'];
            $result['height_ft']  =  $data['height-m'];
        } else {
            $result = $leanBodyMassCalculator->imperial($data['weight-i'], $data['height-i-ft'], $data['height-i-in']);

            $result['weight'] = $data['weight-i'];
            $result['height_ft'] = $data['height-i-ft'];
        }

        $result['status']= 'updated';

        $brm = LeanBodyMass::where('id', $data['record_id'])->first();
        if(count($brm)){
            $brm->update(array(
                'type' => $data['type'],
                'gender' => $data['gender'],
                'height_ft' => $result['height_ft'],
                'weight'  => $result['weight'],
                'height_in'  => $data['height-i-in'],
                'fm' => $result['fm'],
                'fmp' => $result['lm'],
                'lm' => $result['fmp'],
                'lmp' => $result['lmp']
            ));
        }

        return $result;
    }

    public function waistHipRatioCalculator($type=null,$gender=null)
    {
        if($type != null && $gender != null){
          //  dd($type);
        $clientData = WaistHipRatio::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('gender', '=',$gender)->orderBy('id', 'DESC')->first();
            
            return $clientData;
         }
         else
         {
            $clientData = WaistHipRatio::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('gender', '=','male' )->orderBy('id', 'DESC')->first();

            return view('Result.calculators.waist-hip-ratio', ['clientData' => $clientData]);
         }
    }

    public function storeWaistHipRatioCalculation(Request $request)
    {
        $waistHipRatio = new WaistHipRatio;

        $waistHipRatioCalculator = new WaistHipRatioCalculator;

        $data = $request->all();

        $waistHipRatioCalculator->gender = $data['gender'];
        $waistHipRatioCalculator->waist  = $data['waist'];
        $waistHipRatioCalculator->hip    = $data['hip'];

        $result = $waistHipRatioCalculator->calculate();

        $waistHipRatio->client_id = Auth::user()->account_id;
        $waistHipRatio->type           = $data['type'];
        $waistHipRatio->gender         = $data['gender'];
        $waistHipRatio->waist          = $data['waist'];
        $waistHipRatio->hip            = $data['hip'];
        $waistHipRatio->ratio          = $result['ratio'];
        $waistHipRatio->bs             = $result['bs'];
        $waistHipRatio->interpretation = $result['interpretation'];

        $waistHipRatio->save();

        return $result;
    }

    public function updateWaistHipRatioCalculation(Request $request)
    {

        $waistHipRatioCalculator = new WaistHipRatioCalculator;

        $data = $request->all();

        $waistHipRatioCalculator->gender = $data['gender'];
        $waistHipRatioCalculator->waist  = $data['waist'];
        $waistHipRatioCalculator->hip    = $data['hip'];

        $result = $waistHipRatioCalculator->calculate();
        $result['status']= 'updated';

        $brm = WaistHipRatio::where('id', $data['record_id'])->first();
        if(count($brm)){
            $brm->update(array(
                'type' => $data['type'],
                'gender' => $data['gender'],
                'waist' => $data['waist'],
                'hip'  => $data['hip'],
                'ratio'  => $result['ratio'],
                'bs' => $result['bs'],
                'interpretation' => $result['interpretation']
            ));
        }

        return $result;
    }

    public function fullBodyAnalysisCalculator($type=null,$gender=null,$activity=null,$goal=null)
    {
        if($type != null && $gender != null){
          //  dd($type);
        $clientData = FullBodyAnalysis::where('client_id', '=', Auth::user()->account_id)->where('type', '=',$type )->where('gender', '=',$gender)->where('activity', '=',$activity )->where('goal', '=',$goal)->orderBy('id', 'DESC')->first();
            
            return $clientData;
         }
         else
         {
            $clientData = FullBodyAnalysis::where('client_id', '=', Auth::user()->account_id)->where('type', '=','metric' )->where('gender', '=','male' )->where('activity', '=','sedentary' )->where('goal', '=','get-fit')->orderBy('id', 'DESC')->first();

            return view('Result.calculators.full-body-analysis', ['clientData' => $clientData]);
         }
    }

    public function storeFullBodyAnalysisCalculation(Request $request)
    {
        $fullBodyAnalysis = new FullBodyAnalysis;

        $fullBodyAnalysisCalculator = new FullBodyAnalysisCalculator;

        $data = $request->all();

        $fullBodyAnalysisCalculator->gender   = $data['gender'];
        $fullBodyAnalysisCalculator->age      = $data['age'];
        $fullBodyAnalysisCalculator->rhra     = $data['rhra'];
        $fullBodyAnalysisCalculator->waist    = $data['waist'];
        $fullBodyAnalysisCalculator->hip      = $data['hip'];
        $fullBodyAnalysisCalculator->elbow    = $data['elbow'];
        $fullBodyAnalysisCalculator->activity = $data['activity'];
        $fullBodyAnalysisCalculator->goal     = $data['goal'];

        $fullBodyAnalysis->client_id = Auth::user()->account_id;
        $fullBodyAnalysis->type     = $data['type'];
        $fullBodyAnalysis->gender   = $data['gender'];
        $fullBodyAnalysis->age      = $data['age'];
        $fullBodyAnalysis->rhra     = $data['rhra'];
        $fullBodyAnalysis->waist    = $data['waist'];
        $fullBodyAnalysis->hip      = $data['hip'];
        $fullBodyAnalysis->elbow    = $data['elbow'];
        $fullBodyAnalysis->activity = $data['activity'];
        $fullBodyAnalysis->goal     = $data['goal'];

        if ($data['type'] === 'metric') {
            $result = $fullBodyAnalysisCalculator->metric($data['weight-m'], $data['height-m']);

            $fullBodyAnalysis->weight = $data['weight-m'];
            $fullBodyAnalysis->height_ft = $data['height-m'];
        } else {
            $result = $fullBodyAnalysisCalculator->imperial($data['weight-i'], $data['height-i-ft'],
                $data['height-i-in']);

            $fullBodyAnalysis->weight = $data['weight-i'];
            $fullBodyAnalysis->height_ft = $data['height-i-ft'];
            $fullBodyAnalysis->height_in = $data['height-i-in'];
            /*$fullBodyAnalysis->height = $fullBodyAnalysisCalculator->calculateHeight($data['height-i-ft'],
                $data['height-i-in']);*/
        }

        $fullBodyAnalysis->bmi            = $result['body_mass_index']['bmi'];
        $fullBodyAnalysis->classification = $result['body_mass_index']['classification'];
        $fullBodyAnalysis->weight_range   = $result['body_mass_index']['weight_range'];
        $fullBodyAnalysis->ratio          = $result['waist_hip_ratio']['ratio'];
        $fullBodyAnalysis->bs             = $result['waist_hip_ratio']['bs'];
        $fullBodyAnalysis->interpretation = $result['waist_hip_ratio']['interpretation'];
        $fullBodyAnalysis->ideal_weight   = $result['ideal_weight']['iw'];
        $fullBodyAnalysis->lm             = $result['lean_body_mass']['lm'];
        $fullBodyAnalysis->lmp            = $result['lean_body_mass']['lmp'];
        $fullBodyAnalysis->fm             = $result['lean_body_mass']['fm'];
        $fullBodyAnalysis->fmp            = $result['lean_body_mass']['fmp'];
        $fullBodyAnalysis->aam            = $result['daily_metabolism']['aam'];
        $fullBodyAnalysis->aamph          = $result['daily_metabolism']['aamph'];
        $fullBodyAnalysis->arm            = $result['daily_metabolism']['arm'];
        $fullBodyAnalysis->bpml           = $result['target_heart_rate']['bpml'];
        $fullBodyAnalysis->bpmh           = $result['target_heart_rate']['bpmh'];
        $fullBodyAnalysis->mhr            = $result['target_heart_rate']['mhr'];
        $fullBodyAnalysis->bptsl          = $result['target_heart_rate']['bptsl'];
        $fullBodyAnalysis->bptsh          = $result['target_heart_rate']['bptsh'];
        $fullBodyAnalysis->mhrits         = $result['target_heart_rate']['mhrits'];

        $fullBodyAnalysis->save();

        return $result;
    }

    public function updateFullBodyAnalysisCalculation(Request $request)
    {
        $fullBodyAnalysisCalculator = new FullBodyAnalysisCalculator;

        $data = $request->all();

        $fullBodyAnalysisCalculator->gender   = $data['gender'];
        $fullBodyAnalysisCalculator->age      = $data['age'];
        $fullBodyAnalysisCalculator->rhra     = $data['rhra'];
        $fullBodyAnalysisCalculator->waist    = $data['waist'];
        $fullBodyAnalysisCalculator->hip      = $data['hip'];
        $fullBodyAnalysisCalculator->elbow    = $data['elbow'];
        $fullBodyAnalysisCalculator->activity = $data['activity'];
        $fullBodyAnalysisCalculator->goal     = $data['goal'];


        if ($data['type'] === 'metric') {
            $result = $fullBodyAnalysisCalculator->metric($data['weight-m'], $data['height-m']);

            $result['weight']     = $data['weight-m'];
            $result['height_ft']  =  $data['height-m'];
        } else {
            $result = $fullBodyAnalysisCalculator->imperial($data['weight-i'], $data['height-i-ft'],
                $data['height-i-in']);

            $result['weight']     = $data['weight-im'];
            $result['height_ft']  =  $data['height-i-ft'];
            /*$fullBodyAnalysis->height = $fullBodyAnalysisCalculator->calculateHeight($data['height-i-ft'],
                $data['height-i-in']);*/
        }

        $result['status']= 'updated';

        $brm = FullBodyAnalysis::where('id', $data['record_id'])->first();
        if(count($brm)){
            $brm->update(array(
                'type' => $data['type'],
                'gender' => $data['gender'],
                'age' => $data['age'],
                'rhra'  => $data['rhra'],
                'waist'  => $data['waist'],
                'hip' => $data['hip'],
                'elbow' => $data['elbow'],
                'activity' => $data['activity'],
                'goal' => $data['goal'],
                'weight' => $result['weight'] ,
                'height_ft'  => $result['height_ft'],
                'height_in'  => $data['height-i-in'],
                'bmi' => $result['body_mass_index']['bmi'],
                'classification' => $result['body_mass_index']['classification'],
                'weight_range' => $result['body_mass_index']['weight_range'],
                'ratio' => $result['waist_hip_ratio']['ratio'],
                'bs' => $result['waist_hip_ratio']['bs'],
                'interpretation' => $result['waist_hip_ratio']['interpretation'],
                'ideal_weight'  => $result['ideal_weight']['iw'],
                'lm'  => $result['lean_body_mass']['lm'],
                'lmp' => $result['lean_body_mass']['lmp'],
                'fm' => $result['lean_body_mass']['fm'],
                'fmp' => $result['lean_body_mass']['fmp'],
                'aam' => $result['daily_metabolism']['aam'],
                'aamph'  => $result['daily_metabolism']['aamph'],
                'arm'  => $result['daily_metabolism']['arm'],
                'bpml' => $result['target_heart_rate']['bpml'],
                'bpmh' => $result['target_heart_rate']['bpmh'],
                'mhr' => $result['target_heart_rate']['mhr'],
                'bptsl'  => $result['target_heart_rate']['bptsl'],
                'bptsh'  => $result['target_heart_rate']['bptsh'],
                'mhrits' => $result['target_heart_rate']['mhrits']
            ));
        }

        return $result;
    }
    
}
