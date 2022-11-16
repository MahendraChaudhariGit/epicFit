<?php 
namespace App\Http\Controllers\Result;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Clients;
use App\HydrationJournal;
use App\PersonalDiary;
use App\SleepJournal;
use App\Benchmarks;
use App\Parq;
use App\PersonalMeasurement;
use App\PersonalStatistic;
use App\MpClientMealplan;
use App\MpClientMealplanIngrediant;
use App\ClientMenu;
use Auth;
use DB;
use Carbon\Carbon;
use Throwable;

class PersonalStatisticController extends Controller {

    /**
     * Store Statistics Data
     * 
     * @param Request $request
     * @return response
     */
    public function storeWeight(Request $request)
    {
        $requestData = $request->all();
        $clientId = Auth::User()->account_id;
        $personalMeasurement = PersonalMeasurement::where('client_id', $clientId)->orderBy('id', 'DESC')->first();
        if($personalMeasurement)
        {
            $personalMeasurement->update(['weight' => $requestData['weight']]);
            $personalMeasurement->update(['weightUnit' => $requestData['weightUnit']]);
        }
        else
        {
            $personalMeasurements =PersonalMeasurement::create([
                 'client_id' => $clientId,
                 'event_date' => $requestData['event_date'],
                                   
                'weight' => $requestData['weight'],
                'weightUnit' => $requestData['weightUnit']]);
        }
        
        
        $response = [
                'status' => 'ok',
                'message' => 'Data saved successfully'
            ];
        return response()->json($response);
        
    }

    public function storeSleepData(Request $request)
    {
        $requestData = $request->all();
        $clientId = Auth::User()->account_id;
         DB::beginTransaction();
        $dbStatus = true;
         $sleepJournal = SleepJournal::updateOrCreate([
                'client_id' => $clientId,
                'event_date' => $requestData['event_date']
                ],['go_to_bed' => $requestData['go_to_bed'],'go_to_sleep' => $requestData['go_to_sleep'],'wake_up' => $requestData['wake_up'],'morning_woke_up' => $requestData['morning_woke_up'],'end_of_day' => $requestData['end_of_day'],'general_notes' => $requestData['general_notes']]);
                     if(!$sleepJournal){
                                $dbStatus = false;
                                      
                                     }
         if($dbStatus)
        {
                 DB::commit();
                  $response = [
                     'status' => 'ok',
                     'message' => 'Data saved successfully'
                                 ];
            return response()->json($response);
        }
        else{
            DB::rollback();
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong'
            ];
            return response()->json($response);
        }
    }

    public function storeData(Request $request){
        $requestData = $request->all();
        $clientId = Auth::User()->account_id;
        DB::beginTransaction();
        $dbStatus = true;
        try{
           if(isset($requestData['diaryData'])){
                // dd($requestData['diaryData']);
                $personalDiary = PersonalDiary::updateOrCreate([
                                    'client_id' => $clientId,
                                    'event_date' => $requestData['event_date']
                ],['content' => $requestData['diaryData']['content'],'stress_rate' => $requestData['diaryData']['stress_rate'],'humidity' => $requestData['diaryData']['humidity'],'temperature' => $requestData['diaryData']['temp']]);

                if(!$personalDiary){
                    $dbStatus = false;
                }
            }
            if(isset($requestData['measurementsData'])){
                $personalMeasurements = PersonalMeasurement::where('client_id',$clientId)->orderBy('id','DESC')->first();
                $measurementsData = $requestData['measurementsData'];
                $createMeasureData = false;
                if($personalMeasurements){
                    if($personalMeasurements->event_date != $requestData['event_date'] || $personalMeasurements->height != $measurementsData['height'] || $personalMeasurements->chest != $measurementsData['chest'] || $personalMeasurements->neck != $measurementsData['neck'] || $personalMeasurements->bicep_r != $measurementsData['bicep_r'] || $personalMeasurements->bicep_l != $measurementsData['bicep_l'] || $personalMeasurements->forearm_r != $measurementsData['forearm_r'] || $personalMeasurements->forearm_l != $measurementsData['forearm_l'] || $personalMeasurements->waist != $measurementsData['waist'] || $personalMeasurements->hip != $measurementsData['hip'] || $personalMeasurements->thigh_r != $measurementsData['thigh_r'] || $personalMeasurements->thigh_l != $measurementsData['thigh_l'] || $personalMeasurements->calf_r != $measurementsData['calf_r'] || $personalMeasurements->calf_l != $measurementsData['calf_l'] || $personalMeasurements->weight != $measurementsData['weight'] || $personalMeasurements->weightUnit != $measurementsData['weightUnit'] || $personalMeasurements->heightUnit != $measurementsData['heightUnit']){
                        $createMeasureData = true;
                    }
                }
                if(!$personalMeasurements || $createMeasureData){

                    $clientMeasurement = PersonalMeasurement::create([
                        'client_id' => $clientId,
                        'event_date' => $requestData['event_date'],
                        'height' => sprintf('%1.3f',$measurementsData['height']),
                        'chest' => sprintf('%1.3f',$measurementsData['chest']),
                        'neck' => sprintf('%1.3f',$measurementsData['neck']),
                        'bicep_r' => sprintf('%1.3f',$measurementsData['bicep_r']),
                        'bicep_l' => sprintf('%1.3f',$measurementsData['bicep_l']),
                        'forearm_r' => sprintf('%1.3f',$measurementsData['forearm_r']),
                        'forearm_l' => sprintf('%1.3f',$measurementsData['forearm_l']),
                        'waist' => sprintf('%1.3f',$measurementsData['waist']),
                        'hip' => sprintf('%1.3f',$measurementsData['hip']),
                        'thigh_r' => sprintf('%1.3f',$measurementsData['thigh_r']),
                        'thigh_l' => sprintf('%1.3f',$measurementsData['thigh_l']),
                        'calf_r' => sprintf('%1.3f',$measurementsData['calf_r']),
                        'calf_l' => sprintf('%1.3f',$measurementsData['calf_l']),
                        'weight' => sprintf('%1.3f',$measurementsData['weight']),
                        'weightUnit' => $measurementsData['weightUnit'],
                        'heightUnit' => $measurementsData['heightUnit'],
                        'updated_date' => Carbon::now()->format('Y-m-d')
                    ]);
                    if(!$clientMeasurement){
                        $dbStatus = false;
                    }
                }
            }
           if(isset($requestData['statisticsData'])){
                $personalStatistics = PersonalStatistic::where('client_id',$clientId)->orderBy('id','DESC')->first();
                 $statisticsData = $requestData['statisticsData'];
 
               $createStatesData = false;
                if($personalStatistics){
                    if($personalStatistics->event_date != $requestData['event_date'] || $personalStatistics->bfp_kg != $statisticsData['bfp_kg'] || $personalStatistics->smm_kg != $statisticsData['smm_kg'] || $personalStatistics->bmr_kg != $statisticsData['bmr_kg'] || $personalStatistics->bmi_kg != $statisticsData['bmi_kg']|| $personalStatistics->sleep_kg != $statisticsData['sleep_kg']|| $personalStatistics->h_w_ratio != $statisticsData['h_w_ratio'] || $personalStatistics->vis_eat_kg != $statisticsData['vis_eat_kg'] || $personalStatistics->pulsed_kg != $statisticsData['pulsed_kg'] || $personalStatistics->bp_mm != $statisticsData['bp_mm']|| $personalStatistics->bp_hg != $statisticsData['bp_hg'] || $personalStatistics->extra_input != $statisticsData['extra_input']){
                        $createStatesData = true;
                    }

                }
                if(!$personalStatistics || $createStatesData){
 
                   $clientStatistics = PersonalStatistic::create([
                   'client_id' => $clientId,
                   'event_date' => $requestData['event_date'],
                    'bfp_kg' => sprintf('%1.3f',$statisticsData['bfp_kg']),
                    'smm_kg' => sprintf('%1.3f',$statisticsData['smm_kg']),
                    'bmr_kg' => round($statisticsData['bmr_kg']),
                    'bmi_kg' => sprintf('%1.3f',$statisticsData['bmi_kg']),
                    'sleep_kg' => sprintf('%1.3f',$statisticsData['sleep_kg']),
              
                    'h_w_ratio' => sprintf('%1.2f',$statisticsData['h_w_ratio']),
                    'vis_eat_kg' => round($statisticsData['vis_eat_kg']),
                    'pulsed_kg' => round($statisticsData['pulsed_kg']),
                    'bp_mm' => round($statisticsData['bp_mm']),
                    'bp_hg' => round($statisticsData['bp_hg']),
                    'extra_input' => $statisticsData['extra_input']

                    ]);
                    if(!$clientStatistics){
                        $dbStatus = false;
                    }
                
            }
        }
        }catch(\ Throwable $e){
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            return response()->json($response);
        }
        if($dbStatus){
            DB::commit();
            $response = [
                'status' => 'ok',
                'message' => 'Data saved successfully'
            ];
            return response()->json($response);
        }
        else{
            DB::rollback();
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong'
            ];
            return response()->json($response);
        }
    }

    public function personalStastic($bodypart){
        $get_menus = ClientMenu::where('client_id',Auth::user()->account_id)->first();
        if(isset($get_menus) && !in_array("measurement", explode(',',$get_menus->menues))){
            return redirect('access-restricted');
        }

        $duration = 1;
        $personal_statistic = PersonalStatistic::select('id','event_date')->orderBy('event_date','asc')->orderBy('id','asc')->where('client_id',Auth::User()->account_id);
        // if($duration == 1){
            $stepSize =  1;
            $startOfTheMonth = date('Y-m-01');
            $endOfTheMonth = date('Y-m-t');
            $personal_statistic = $personal_statistic->whereBetween('event_date',[date('Y-m-01') , date('Y-m-d')])->get()->toArray();
            
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }

            if($bodypart == 'bfp'){
                $personal_statistic = PersonalStatistic::select('event_date','bfp_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
                $data = [];
                foreach($personal_statistic as $value){
                    if($value['bfp_kg'] > 0){
                        $data[] = ['date' => $value['event_date'],'value'=>$value['bfp_kg']];
                    }
    
                }
                $body_part = 'BFP';
                $label_suffix = '%';
                // dd($data);
            }
            if($bodypart == 'smm'){
                $personal_statistic = PersonalStatistic::select('event_date','smm_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
                $data = [];
                foreach($personal_statistic as $value){
                    if($value['smm_kg'] > 0){
                        $data[] = ['date' => $value['event_date'],'value'=>$value['smm_kg']];
                    }
                    
                }
                $body_part = 'SMM';
                $label_suffix = 'kg';
            }
            if($bodypart == 'bmr'){
                $personal_statistic = PersonalStatistic::select('event_date','bmr_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
                $data = [];
                foreach($personal_statistic as $value){
                    if($value['bmr_kg'] > 0){
                        $data[] = ['date' => $value['event_date'],'value'=>$value['bmr_kg']];
                    }
                    
                }
                $body_part = 'BMR';
                $label_suffix = 'KCal';
            }
            if($bodypart == 'bmi'){
                $personal_statistic = PersonalStatistic::select('event_date','bmi_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
                $data = [];
                foreach($personal_statistic as $value){
                    if($value['bmi_kg'] > 0){
                        $data[] = ['date' => $value['event_date'],'value'=>$value['bmi_kg']];
                    }
                    
                }
                $body_part = 'BMI';
                $label_suffix = 'kg/m2';
            }
            if($bodypart == 'bfm'){
                $personal_statistic = PersonalStatistic::select('event_date','sleep_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
                $data = [];
                foreach($personal_statistic as $value){
                    if($value['sleep_kg'] > 0){
                        $data[] = ['date' => $value['event_date'],'value'=>$value['sleep_kg']];
                    }
                    
                }
                $body_part = 'BFM';
                $label_suffix = 'kg';
            }
            if($bodypart == 'hw'){
                $personal_statistic = PersonalStatistic::select('event_date','h_w_ratio')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
                $data = [];
                foreach($personal_statistic as $value){
                    if($value['h_w_ratio'] > 0){
                        $data[] = ['date' => $value['event_date'],'value'=>$value['h_w_ratio']];
                    }
                    
                }
                $body_part = 'H/W Ratio';
                $label_suffix = '';
            }
            if($bodypart == 'vis_fat'){
                $personal_statistic = PersonalStatistic::select('event_date','vis_eat_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
                $data = [];
                foreach($personal_statistic as $value){
                    if($value['vis_eat_kg'] > 0){
                        $data[] = ['date' => $value['event_date'],'value'=>$value['vis_eat_kg']];
                    }
                    
                }
                $body_part = 'Vis Fat';
                $label_suffix = 'kg';
            }
            if($bodypart == 'pulse'){
                $personal_statistic = PersonalStatistic::select('event_date','pulsed_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
                $data = [];
                foreach($personal_statistic as $value){
                    if($value['pulsed_kg'] > 0){
                        $data[] = ['date' => $value['event_date'],'value'=>$value['pulsed_kg']];
                    }
                    
                }
                $body_part = 'Pulse';
                $label_suffix = 'bpm';
            }
            if($bodypart == 'bp'){
                $personal_statistic = PersonalStatistic::select('event_date','bp_mm','bp_hg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
                $data = [];
                $data1 = [];
                foreach($personal_statistic as $value){
                    if($value['bp_mm'] > 0){
                        $data[] = ['date' => $value['event_date'],'bp_mm'=>$value['bp_mm'],'bp_hg'=>$value['bp_hg']];
                    }
                    
                }
                $body_part = 'Blood Pressure';
                $label_suffix = 'mmHg';
            }

            return view('Result.personal-stastic',compact('data','body_part','bodypart','duration','label_suffix','startOfTheMonth','stepSize','endOfTheMonth'));
    }

    public function filterPersonalStastic($bodypart,$duration){
        $personal_statistic = PersonalStatistic::select('id','event_date')->orderBy('event_date','asc')->orderBy('id','asc')->where('client_id',Auth::User()->account_id);
        if($duration == 1){
            $stepSize =  1;
            $startOfTheMonth = date('Y-m-01');
            $endOfTheMonth = date('Y-m-t');
            $personal_statistic = $personal_statistic->whereBetween('event_date',[date('Y-m-01') , date('Y-m-d')])->get()->toArray();
            // dd($personal_statistic->get()->toArray());
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
        }
        if($duration == 3){
            $pastThreeMonths = date("Y-m-d", strtotime("first day of -2 months"));
            $currentMonth = date("Y-m-d");
            $startOfTheMonth =  $pastThreeMonths;
            $endOfTheMonth =  date("Y-m-t");
            $stepSize =  1;
           
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pastThreeMonths , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }
        if($duration == 6){
            $stepSize =  1;
            $pastSixThreeMonths = date("Y-m-d", strtotime("first day of -5 months"));
            $startOfTheMonth =  $pastSixThreeMonths;
            $Newdate =  Carbon::now()->format('Y-m-d');
            $currentMonth = date("Y-m-d");
            $endOfTheMonth =  date("Y-m-t");
            // dd($pastThreeMonths,$currentMonth);
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pastSixThreeMonths , $currentMonth])->get()->toArray();
           $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }
        if($duration == 12){
            $stepSize =  2;
            $pasttwelveMonths = date("Y-m-d", strtotime("first day of -11 months"));
            $startOfTheMonth =  $pasttwelveMonths;
            $Newdate =  Carbon::now()->format('Y-m-d');
            $currentMonth = date("Y-m-d");
            $endOfTheMonth =  date("Y-m-t");
            // dd($pasttwelveMonths,$currentMonth);
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pasttwelveMonths , $currentMonth])->get()->toArray();
           $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }
        if($duration == 24){
            $stepSize =  6;
            $pasttwoyears = date("Y-m-d", strtotime("first day of -23 months"));
            $startOfTheMonth =  $pasttwoyears;
            $Newdate =  Carbon::now()->format('Y-m-d');
            $currentMonth = date("Y-m-d");
            $endOfTheMonth =  date("Y-m-t");
            // dd($pasttwoyears,$currentMonth);
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pasttwoyears , $currentMonth])->get()->toArray();
           $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }
        if($duration == 36){
            $stepSize =  6;
            $pastThreeyears = date("Y-m-d", strtotime("first day of -35 months"));
            $startOfTheMonth =  $pastThreeyears;
            $Newdate =  Carbon::now()->format('Y-m-d');
            $currentMonth = date("Y-m-d");
            $endOfTheMonth =  date("Y-m-t");
            // dd($pastThreeyears,$currentMonth);
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pastThreeyears , $currentMonth])->get()->toArray();
           $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }
        
        if($bodypart == 'bfp'){
            $personal_statistic = PersonalStatistic::select('event_date','bfp_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['bfp_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bfp_kg']];
                }

            }
            $body_part = 'BFP';
            $label_suffix = '%';
            // dd($data);
        }
        if($bodypart == 'smm'){
            $personal_statistic = PersonalStatistic::select('event_date','smm_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['smm_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['smm_kg']];
                }
                
            }
            $body_part = 'SMM';
            $label_suffix = 'kg';
        }
        if($bodypart == 'bmr'){
            $personal_statistic = PersonalStatistic::select('event_date','bmr_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['bmr_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bmr_kg']];
                }
                
            }
            $body_part = 'BMR';
            $label_suffix = 'KCal';
        }
        if($bodypart == 'bmi'){
            $personal_statistic = PersonalStatistic::select('event_date','bmi_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['bmi_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bmi_kg']];
                }
                
            }
            $body_part = 'BMI';
            $label_suffix = 'kg/m2';
        }
        if($bodypart == 'bfm'){
            $personal_statistic = PersonalStatistic::select('event_date','sleep_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['sleep_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['sleep_kg']];
                }
                
            }
            $body_part = 'BFM';
            $label_suffix = 'kg';
        }
        if($bodypart == 'hw'){
            $personal_statistic = PersonalStatistic::select('event_date','h_w_ratio')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['h_w_ratio'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['h_w_ratio']];
                }
                
            }
            $body_part = 'H/W Ratio';
            $label_suffix = '';
        }
        if($bodypart == 'vis_fat'){
            $personal_statistic = PersonalStatistic::select('event_date','vis_eat_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['vis_eat_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['vis_eat_kg']];
                }
                
            }
            $body_part = 'Vis Fat';
            $label_suffix = 'kg';
        }
        if($bodypart == 'pulse'){
            $personal_statistic = PersonalStatistic::select('event_date','pulsed_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['pulsed_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['pulsed_kg']];
                }
                
            }
            $body_part = 'Pulse';
            $label_suffix = 'bpm';
        }
        if($bodypart == 'bp'){
            $personal_statistic = PersonalStatistic::select('event_date','bp_mm','bp_hg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            $data1 = [];
            foreach($personal_statistic as $value){
                if($value['bp_mm'] > 0){
                    $data[] = ['date' => $value['event_date'],'bp_mm'=>$value['bp_mm'],'bp_hg'=>$value['bp_hg']];
                }
                
            }
            $body_part = 'Blood Pressure';
            $label_suffix = 'mmHg';
        }
        
        $pastMonth = date("Y-m-d");
        $viewCalendar = date('M Y',strtotime(date("Y-m-d")));
        if(count($data) > 0){
            return view('Result.filter-personal-stastic',compact('data','body_part','bodypart','duration','pastMonth','viewCalendar','label_suffix','startOfTheMonth','stepSize','endOfTheMonth'));
        }else{
            return view('Result.no-graph',compact('pastMonth','viewCalendar'));
        }
    }

    public function filterPersonalStasticGraph(Request $request){
        // dd($request->all());
        $bodypart = $request->bodypart;
        $duration = $request->duration;
        $personal_statistic = PersonalStatistic::select('id','event_date')->orderBy('event_date','asc')->orderBy('id','asc')->where('client_id',Auth::User()->account_id);
        if($duration == 1){
            $stepSize = 1;
            if($request->month){
                if($request->status == 'previous'){
                    $pastOneMonths = date("Y-m-d", strtotime("$request->month first day of -1 months"));
                    $currentMonth = date('Y-m-t', strtotime($pastOneMonths));
                    $startOfTheMonth =  $pastOneMonths;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $pastOneMonths = date("Y-m-d", strtotime("$request->month first day of +1 months"));
                    $currentMonth = date('Y-m-t', strtotime($pastOneMonths));
                    $startOfTheMonth =  $pastOneMonths;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $pastOneMonths;
                $viewCalendar = date('M Y',strtotime($pastOneMonths));
            }
            // dd($pastOneMonths,$currentMonth);
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pastOneMonths , $currentMonth])->get()->toArray();
          $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd(date('M Y',strtotime($pastOneMonths)));
            
        }
        
        if($duration == 3){
            $stepSize =  1;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -3 months"));
                    $pastThreeMonths = date('Y-m-d', strtotime("$currentMonth first day of -2 months"));
                    $startOfTheMonth =  $pastThreeMonths;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +3 months"));
                    $pastThreeMonths = date('Y-m-d', strtotime("$currentMonth first day of -2 months"));
                    $startOfTheMonth =  $pastThreeMonths;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
                
            }
            
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pastThreeMonths , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }
        if($duration == 6){
            $stepSize =  1;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -6 months"));
                    $pastSixThreeMonths = date('Y-m-d', strtotime("$currentMonth first day of -5 months"));
                    $startOfTheMonth =  $pastSixThreeMonths;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +6 months"));
                    $pastSixThreeMonths = date('Y-m-d', strtotime("$currentMonth first day of -5 months"));
                    $startOfTheMonth =  $pastSixThreeMonths;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
            }
            
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pastSixThreeMonths , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }
        if($duration == 12){
            $stepSize =  2;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -12 months"));
                    $pasttwelveMonths = date('Y-m-d', strtotime("$currentMonth first day of -11 months"));
                    $startOfTheMonth =  $pasttwelveMonths;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +12 months"));
                    $pasttwelveMonths = date('Y-m-d', strtotime("$currentMonth first day of -11 months"));
                    $startOfTheMonth =  $pasttwelveMonths;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
            }
            
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pasttwelveMonths , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }
        if($duration == 24){
            $stepSize =  6;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -24 months"));
                    $pasttwoyears = date('Y-m-d', strtotime("$currentMonth first day of -23 months"));
                    $startOfTheMonth =  $pasttwoyears;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +24 months"));
                    $pasttwoyears = date('Y-m-d', strtotime("$currentMonth first day of -23 months"));
                    $startOfTheMonth =  $pasttwoyears;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
            }

            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pasttwoyears , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }
        if($duration == 36){
            $stepSize =  6;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -36 months"));
                    $pastThreeyears = date('Y-m-d', strtotime("$currentMonth first day of -35 months"));
                    $startOfTheMonth =  $pastThreeyears;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +36 months"));
                    $pastThreeyears = date('Y-m-d', strtotime("$currentMonth first day of -35 months"));
                    $startOfTheMonth =  $pastThreeyears;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
            }
            
            $personal_statistic = $personal_statistic->whereBetween('event_date',[$pastThreeyears , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_statistic as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_statistic->get()->toArray());
        }

        if($bodypart == 'bfp'){
            $personal_statistic = PersonalStatistic::select('event_date','bfp_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['bfp_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bfp_kg']];
                }

            }
            $body_part = 'BFP';
            $label_suffix = '%';
            // dd($data);
        }
        if($bodypart == 'smm'){
            $personal_statistic = PersonalStatistic::select('event_date','smm_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['smm_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['smm_kg']];
                }
                
            }
            $body_part = 'SMM';
            $label_suffix = 'kg';
        }
        if($bodypart == 'bmr'){
            $personal_statistic = PersonalStatistic::select('event_date','bmr_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['bmr_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bmr_kg']];
                }
                
            }
            $body_part = 'BMR';
            $label_suffix = 'KCal';
        }
        if($bodypart == 'bmi'){
            $personal_statistic = PersonalStatistic::select('event_date','bmi_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['bmi_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bmi_kg']];
                }
                
            }
            $body_part = 'BMI';
            $label_suffix = 'kg/m2';
        }
        if($bodypart == 'bfm'){
            $personal_statistic = PersonalStatistic::select('event_date','sleep_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['sleep_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['sleep_kg']];
                }
                
            }
            $body_part = 'BFM';
            $label_suffix = 'kg';
        }
        if($bodypart == 'hw'){
            $personal_statistic = PersonalStatistic::select('event_date','h_w_ratio')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['h_w_ratio'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['h_w_ratio']];
                }
                
            }
            $body_part = 'H/W Ratio';
            $label_suffix = '';
        }
        if($bodypart == 'vis_fat'){
            $personal_statistic = PersonalStatistic::select('event_date','vis_eat_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['vis_eat_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['vis_eat_kg']];
                }
                
            }
            $body_part = 'Vis Fat';
            $label_suffix = 'kg';
        }
        if($bodypart == 'pulse'){
            $personal_statistic = PersonalStatistic::select('event_date','pulsed_kg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_statistic as $value){
                if($value['pulsed_kg'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['pulsed_kg']];
                }
                
            }
            $body_part = 'Pulse';
            $label_suffix = 'bpm';
        }
        if($bodypart == 'bp'){
            $personal_statistic = PersonalStatistic::select('event_date','bp_mm','bp_hg')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            $data1 = [];
            foreach($personal_statistic as $value){
                if($value['bp_mm'] > 0){
                    $data[] = ['date' => $value['event_date'],'bp_mm'=>$value['bp_mm'],'bp_hg'=>$value['bp_hg']];
                }
                
            }
            $body_part = 'Blood Pressure';
            $label_suffix = 'mmHg';
        }
     
        
        if(count($data) > 0){
            return view('Result.filter-personal-stastic',compact('data','body_part','bodypart','duration','pastMonth','viewCalendar','label_suffix','startOfTheMonth','stepSize','endOfTheMonth'));
        }else{
            return view('Result.no-graph',compact('pastMonth','viewCalendar'));
        }

        
    }

    public function bodyMeasurement($bodypart){
        $get_menus = ClientMenu::where('client_id',Auth::user()->account_id)->first();
        if(isset($get_menus) && !in_array("measurement", explode(',',$get_menus->menues))){
            return redirect('access-restricted');
        }
        // dd(date('Y-m-1') , date('Y-m-d'));
        $duration = 1;
        $personal_measurement = PersonalMeasurement::select('id','event_date')->orderBy('event_date','asc')->orderBy('id','asc')->where('client_id',Auth::User()->account_id);
        // if($duration == 1){
            $stepSize =  1;
            $startOfTheMonth = date('Y-m-01');
            $endOfTheMonth = date('Y-m-t');
            $personal_measurement = $personal_measurement->whereBetween('event_date',[date('Y-m-01') , date('Y-m-d')])->get()->toArray();
            // dd($personal_measurement);
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
        // }
        // if($duration == 3){
        //     $pastThreeMonths = date("Y-m-d", strtotime("first day of -2 months"));
        //     $currentMonth = date("Y-m-d");
        //     $startOfTheMonth =  $pastThreeMonths;
        //     $endOfTheMonth =  $currentMonth;
        //     $stepSize =  1;
           
        //     $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastThreeMonths , $currentMonth])->get()->toArray();
        //    $previous_event_date = [];
        //     $event_id = [];
        //     foreach($personal_measurement as $val){
                
        //         if(in_array($val['event_date'], $previous_event_date)){
        //             array_pop($event_id);
        //             array_push($event_id, $val['id']);
        //         }else{
        //             $event_id[] = $val['id'];
        //         }
        //         $previous_event_date[] = $val['event_date'];
        //     }
        // }
        // if($duration == 6){
        //     $stepSize =  1;
        //     $pastSixThreeMonths = date("Y-m-d", strtotime("first day of -5 months"));
        //     $startOfTheMonth =  $pastSixThreeMonths;
        //     $Newdate =  Carbon::now()->format('Y-m-d');
        //     $currentMonth = date("Y-m-d");
        //     $endOfTheMonth =  $currentMonth;
        //     $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastSixThreeMonths , $currentMonth])->get()->toArray();
        //    $previous_event_date = [];
        //     $event_id = [];
        //     foreach($personal_measurement as $val){
                
        //         if(in_array($val['event_date'], $previous_event_date)){
        //             array_pop($event_id);
        //             array_push($event_id, $val['id']);
        //         }else{
        //             $event_id[] = $val['id'];
        //         }
        //         $previous_event_date[] = $val['event_date'];
        //     }
        // }
        // if($duration == 12){
        //     $stepSize =  2;
        //     $pasttwelveMonths = date("Y-m-d", strtotime("first day of -11 months"));
        //     $startOfTheMonth =  $pasttwelveMonths;
        //     $Newdate =  Carbon::now()->format('Y-m-d');
        //     $currentMonth = date("Y-m-d");
        //     $endOfTheMonth =  $currentMonth;
        //     $personal_measurement = $personal_measurement->whereBetween('event_date',[$pasttwelveMonths , $currentMonth])->get()->toArray();
        //    $previous_event_date = [];
        //     $event_id = [];
        //     foreach($personal_measurement as $val){
                
        //         if(in_array($val['event_date'], $previous_event_date)){
        //             array_pop($event_id);
        //             array_push($event_id, $val['id']);
        //         }else{
        //             $event_id[] = $val['id'];
        //         }
        //         $previous_event_date[] = $val['event_date'];
        //     }
        // }
        // if($duration == 24){
        //     $stepSize =  6;
        //     $pasttwoyears = date("Y-m-d", strtotime("first day of -23 months"));
        //     $startOfTheMonth =  $pasttwoyears;
        //     $Newdate =  Carbon::now()->format('Y-m-d');
        //     $currentMonth = date("Y-m-d");
        //     $endOfTheMonth =  $currentMonth;
        //     $personal_measurement = $personal_measurement->whereBetween('event_date',[$pasttwoyears , $currentMonth])->get()->toArray();
        //    $previous_event_date = [];
        //     $event_id = [];
        //     foreach($personal_measurement as $val){
                
        //         if(in_array($val['event_date'], $previous_event_date)){
        //             array_pop($event_id);
        //             array_push($event_id, $val['id']);
        //         }else{
        //             $event_id[] = $val['id'];
        //         }
        //         $previous_event_date[] = $val['event_date'];
        //     }
        // }
        // if($duration == 36){
        //     $stepSize =  6;
        //     $pastThreeyears = date("Y-m-d", strtotime("first day of -35 months"));
        //     $startOfTheMonth =  $pastThreeyears;
        //     $Newdate =  Carbon::now()->format('Y-m-d');
        //     $currentMonth = date("Y-m-d");
        //     $endOfTheMonth =  $currentMonth;
        //     $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastThreeyears , $currentMonth])->get()->toArray();
        //    $previous_event_date = [];
        //     $event_id = [];
        //     foreach($personal_measurement as $val){
                
        //         if(in_array($val['event_date'], $previous_event_date)){
        //             array_pop($event_id);
        //             array_push($event_id, $val['id']);
        //         }else{
        //             $event_id[] = $val['id'];
        //         }
        //         $previous_event_date[] = $val['event_date'];
        //     }
        // }
        if($bodypart == 'height'){
            $get_last_data = PersonalMeasurement::select('event_date','height','heightUnit')->where('height','>',0)->whereIn('id',$event_id)->orderBy('event_date','desc')->limit(1)->first();
            $get_data = PersonalMeasurement::select('event_date','height','heightUnit')->where('height','>',0)->where('client_id',Auth::user()->account_id)->limit(1)->first();
            $personal_measurement = PersonalMeasurement::select('event_date','height','heightUnit')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            if(isset($get_data)){
                foreach($personal_measurement as $value){
                    if($value['height'] > 0){
                        if(isset($get_last_data) && $get_last_data->heightUnit == 'inches'){
                            if($value['heightUnit'] == 'cm'){
                                $data[] = ['date' => $value['event_date'],'value'=>(number_format((float)($value['height']*0.393701), 2, '.', ''))];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['height']];
                            }
                        }else{
                            if($value['heightUnit'] == 'inches'){
                                $data[] = ['date' => $value['event_date'],'value'=>(number_format((float)($value['height']/0.393701), 2, '.', ''))];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['height']];
                            }
                        }
                        
                    }

                }
                $height_unit = isset($get_last_data) ? ($get_last_data['heightUnit'] == 'inches' ? 'inches' : 'cm') : 'cm';
            }else{
                $clients = Clients::with(['parq'=>function($q){
                    $q->select('client_id','height','heightUnit');
                }])->find(Auth::User()->account_id);
                $get_last_data = $clients->parq;
                // dd($get_last_data);

                if(isset($get_last_data) && $get_last_data->heightUnit != 'Metric'){
                    if($get_last_data->heightUnit == 'Metric'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>(number_format((float)($get_last_data->height*0.393701), 2, '.', ''))];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->height];
                    }
                }else{
                    if($get_last_data->heightUnit == 'inches'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>(number_format((float)($get_last_data->height/0.393701), 2, '.', ''))];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->height];
                    }
                }
                $height_unit = isset($get_last_data) ? ($get_last_data->heightUnit != 'Metric' ? 'inches' : 'cm') : 'cm';
            }
            
            $body_part = 'Height';
        }
        if($bodypart == 'chest'){
            $personal_measurement = PersonalMeasurement::select('event_date','chest')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['chest'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['chest']];
                }

            }
            $body_part = 'Chest';
        }
        if($bodypart == 'neck'){
            $personal_measurement = PersonalMeasurement::select('event_date','neck')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['neck'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['neck']];
                }
                
            }
            $body_part = 'Neck';
        }
        if($bodypart == 'bicepR'){
            $personal_measurement = PersonalMeasurement::select('event_date','bicep_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['bicep_r'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bicep_r']];
                }
                
            }
            $body_part = 'Bicep R';
        }
        if($bodypart == 'bicepL'){
            $personal_measurement = PersonalMeasurement::select('event_date','bicep_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['bicep_l'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bicep_l']];
                }
                
            }
            $body_part = 'Bicep L';
        }
        if($bodypart == 'forearmR'){
            $personal_measurement = PersonalMeasurement::select('event_date','forearm_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['forearm_r'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['forearm_r']];
                }
                
            }
            $body_part = 'Forearm R';
        }
        if($bodypart == 'forearmL'){
            $personal_measurement = PersonalMeasurement::select('event_date','forearm_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['forearm_l'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['forearm_l']];
                }
                
            }
            $body_part = 'Forearm L';
        }
        if($bodypart == 'abdomen'){
            $personal_measurement = PersonalMeasurement::select('event_date','waist')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['waist'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['waist']];
                }
                
            }
            $body_part = 'Waist';
        }
        if($bodypart == 'hip'){
            $personal_measurement = PersonalMeasurement::select('event_date','hip')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['hip'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['hip']];
                }
                
            }
            $body_part = 'Hip';
        }
        if($bodypart == 'thighR'){
            $personal_measurement = PersonalMeasurement::select('event_date','thigh_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['thigh_r'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['thigh_r']];
                }
                
            }
            $body_part = 'Thigh R';
        }
        if($bodypart == 'thighL'){
            $personal_measurement = PersonalMeasurement::select('event_date','thigh_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['thigh_l'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['thigh_l']];
                }
                
            }
            $body_part = 'Thigh L';
        }
        if($bodypart == 'calfR'){
            $personal_measurement = PersonalMeasurement::select('event_date','calf_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['calf_r'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['calf_r']];
                }
                
            }
            $body_part = 'Calf R';
        }
        if($bodypart == 'calfL'){
            $personal_measurement = PersonalMeasurement::select('event_date','calf_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['calf_l'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['calf_l']];
                }
                
            }
            $body_part = 'Calf L';
        }
        if($bodypart == 'weight'){
            $get_last_data = PersonalMeasurement::select('event_date','weight','weightUnit')->where('weight','>',0)->whereIn('id',$event_id)->orderBy('event_date','desc')->limit(1)->first();
            $get_data = PersonalMeasurement::select('event_date','weight','weightUnit')->where('weight','>',0)->where('client_id',Auth::user()->account_id)->limit(1)->first();
            $personal_measurement = PersonalMeasurement::select('event_date','weight','weightUnit')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            if(isset($get_data)){
                foreach($personal_measurement as $value){
                    if($value['weight'] > 0){
                        if(isset($get_last_data) && $get_last_data->weightUnit == 'Imperial'){
                            if($value['weightUnit'] == 'Metric'){
                                $data[] = ['date' => $value['event_date'],'value'=>number_format((float)($value['weight']*2.2046226218), 2, '.', '')];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['weight']];
                            }
                        }else{
                            if($value['weightUnit'] == 'Imperial'){
                                $data[] = ['date' => $value['event_date'],'value'=>number_format((float)($value['weight']/2.2046226218), 2, '.', '')];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['weight']];
                            }
                        }
                    }
                }
            }else{
                $clients = Clients::with(['parq'=>function($q){
                    $q->select('client_id','weight','weightUnit');
                }])->find(Auth::User()->account_id);
                $get_last_data = $clients->parq;
                
                if(isset($get_last_data) && $get_last_data->weightUnit == 'Imperial'){
                    if($value['weightUnit'] == 'Metric'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>number_format((float)($get_last_data->weight*2.2046226218), 2, '.', '')];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->weight];
                    }
                }else{
                    if($value['weightUnit'] == 'Imperial'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>number_format((float)($get_last_data->weight/2.2046226218), 2, '.', '')];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->weight];
                    }
                }
            }
           
            $weight_unit = isset($get_last_data) ? ($get_last_data->weightUnit == 'Imperial' ? 'pound' : 'kg') : 'kg';
            
            $body_part = 'Weight';
        }
        
        return view('Result.body-measurement',compact('data','body_part','bodypart','duration','weight_unit','height_unit','startOfTheMonth','stepSize','endOfTheMonth'));
    }

    public function filterBodyMeasurement($bodypart,$duration){
        // dd($bodypart,$duration);
        $personal_measurement = PersonalMeasurement::select('id','event_date')->orderBy('event_date','asc')->orderBy('id','asc')->where('client_id',Auth::User()->account_id);
        if($duration == 1){
            $stepSize =  1;
            $startOfTheMonth = date('Y-m-01');
            $endOfTheMonth = date('Y-m-t');
            $personal_measurement = $personal_measurement->whereBetween('event_date',[date('Y-m-01') , date('Y-m-d')])->get()->toArray();
            // dd($personal_measurement->get()->toArray());
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
        }
        if($duration == 3){
            $pastThreeMonths = date("Y-m-d", strtotime("first day of -2 months"));
            $currentMonth = date("Y-m-d");
            $startOfTheMonth =  $pastThreeMonths;
            $endOfTheMonth =  date("Y-m-t");
            $stepSize =  1;
           
            // $xaxisLable = [$pastThreeMonths,date("Y-m-d",strtotime("-1 months")),$currentMonth];
            // dd($xaxisLable);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastThreeMonths , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($duration == 6){
            $stepSize =  1;
            $pastSixThreeMonths = date("Y-m-d", strtotime("first day of -5 months"));
            $startOfTheMonth =  $pastSixThreeMonths;
            $Newdate =  Carbon::now()->format('Y-m-d');
            $currentMonth = date("Y-m-d");
            $endOfTheMonth =  date("Y-m-t");
            // dd($pastThreeMonths,$currentMonth);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastSixThreeMonths , $currentMonth])->get()->toArray();
           $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($duration == 12){
            $stepSize =  2;
            $pasttwelveMonths = date("Y-m-d", strtotime("first day of -11 months"));
            $startOfTheMonth =  $pasttwelveMonths;
            $Newdate =  Carbon::now()->format('Y-m-d');
            $currentMonth = date("Y-m-d");
            $endOfTheMonth =  date("Y-m-t");
            // dd($pasttwelveMonths,$currentMonth);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pasttwelveMonths , $currentMonth])->get()->toArray();
           $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($duration == 24){
            $stepSize =  6;
            $pasttwoyears = date("Y-m-d", strtotime("first day of -23 months"));
            $startOfTheMonth =  $pasttwoyears;
            $Newdate =  Carbon::now()->format('Y-m-d');
            $currentMonth = date("Y-m-d");
            $endOfTheMonth =  date("Y-m-t");
            // dd($pasttwoyears,$currentMonth);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pasttwoyears , $currentMonth])->get()->toArray();
           $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($duration == 36){
            $stepSize =  6;
            $pastThreeyears = date("Y-m-d", strtotime("first day of -35 months"));
            $startOfTheMonth =  $pastThreeyears;
            $Newdate =  Carbon::now()->format('Y-m-d');
            $currentMonth = date("Y-m-d");
            $endOfTheMonth =  date("Y-m-t");
            // dd($pastThreeyears,$currentMonth);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastThreeyears , $currentMonth])->get()->toArray();
           $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($bodypart == 'height'){
            $get_last_data = PersonalMeasurement::select('event_date','height','heightUnit')->where('height','>',0)->whereIn('id',$event_id)->orderBy('event_date','desc')->limit(1)->first();
            $get_data = PersonalMeasurement::select('event_date','height','heightUnit')->where('height','>',0)->where('client_id',Auth::user()->account_id)->limit(1)->first();
            $personal_measurement = PersonalMeasurement::select('event_date','height','heightUnit')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            if(isset($get_data)){
                foreach($personal_measurement as $value){
                    if($value['height'] > 0){
                        if(isset($get_last_data) && $get_last_data->heightUnit == 'inches'){
                            if($value['heightUnit'] == 'cm'){
                                $data[] = ['date' => $value['event_date'],'value'=>(number_format((float)($value['height']*0.393701), 2, '.', ''))];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['height']];
                            }
                        }else{
                            if($value['heightUnit'] == 'inches'){
                                $data[] = ['date' => $value['event_date'],'value'=>(number_format((float)($value['height']/0.393701), 2, '.', ''))];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['height']];
                            }
                        }
                        
                    }

                }
                $height_unit = isset($get_last_data) ? ($get_last_data['heightUnit'] == 'inches' ? 'inches' : 'cm') : 'cm';
            }else{
                $clients = Clients::with(['parq'=>function($q){
                    $q->select('client_id','height','heightUnit');
                }])->find(Auth::User()->account_id);
                $get_last_data = $clients->parq;
                // dd($get_last_data);

                if(isset($get_last_data) && $get_last_data->heightUnit != 'Metric'){
                    if($get_last_data->heightUnit == 'Metric'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>(number_format((float)($get_last_data->height*0.393701), 2, '.', ''))];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->height];
                    }
                }else{
                    if($get_last_data->heightUnit == 'inches'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>(number_format((float)($get_last_data->height/0.393701), 2, '.', ''))];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->height];
                    }
                }
                $height_unit = isset($get_last_data) ? ($get_last_data->heightUnit != 'Metric' ? 'inches' : 'cm') : 'cm';
            }
            $body_part = 'Height';
        }
        if($bodypart == 'chest'){
            $personal_measurement = PersonalMeasurement::select('event_date','chest')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['chest'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['chest']];
                }

            }
            $body_part = 'Chest';
        }
        if($bodypart == 'neck'){
            $personal_measurement = PersonalMeasurement::select('event_date','neck')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['neck'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['neck']];
                }
                
            }
            $body_part = 'Neck';
        }
        if($bodypart == 'bicepR'){
            $personal_measurement = PersonalMeasurement::select('event_date','bicep_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['bicep_r'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bicep_r']];
                }
                
            }
            $body_part = 'Bicep R';
        }
        if($bodypart == 'bicepL'){
            $personal_measurement = PersonalMeasurement::select('event_date','bicep_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['bicep_l'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['bicep_l']];
                }
                
            }
            $body_part = 'Bicep L';
        }
        if($bodypart == 'forearmR'){
            $personal_measurement = PersonalMeasurement::select('event_date','forearm_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['forearm_r'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['forearm_r']];
                }
                
            }
            $body_part = 'Forearm R';
        }
        if($bodypart == 'forearmL'){
            $personal_measurement = PersonalMeasurement::select('event_date','forearm_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['forearm_l'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['forearm_l']];
                }
                
            }
            $body_part = 'Forearm L';
        }
        if($bodypart == 'abdomen'){
            $personal_measurement = PersonalMeasurement::select('event_date','waist')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['waist'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['waist']];
                }
                
            }
            $body_part = 'Waist';
        }
        if($bodypart == 'hip'){
            $personal_measurement = PersonalMeasurement::select('event_date','hip')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['hip'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['hip']];
                }
                
            }
            $body_part = 'Hip';
        }
        if($bodypart == 'thighR'){
            $personal_measurement = PersonalMeasurement::select('event_date','thigh_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['thigh_r'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['thigh_r']];
                }
                
            }
            $body_part = 'Thigh R';
        }
        if($bodypart == 'thighL'){
            $personal_measurement = PersonalMeasurement::select('event_date','thigh_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['thigh_l'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['thigh_l']];
                }
                
            }
            $body_part = 'Thigh L';
        }
        if($bodypart == 'calfR'){
            $personal_measurement = PersonalMeasurement::select('event_date','calf_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['calf_r'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['calf_r']];
                }
                
            }
            $body_part = 'Calf R';
        }
        if($bodypart == 'calfL'){
            $personal_measurement = PersonalMeasurement::select('event_date','calf_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['calf_l'] > 0){
                    $data[] = ['date' => $value['event_date'],'value'=>$value['calf_l']];
                }
                
            }
            $body_part = 'Calf L';
        }
        if($bodypart == 'weight'){
            $get_last_data = PersonalMeasurement::select('event_date','weight','weightUnit')->where('weight','>',0)->whereIn('id',$event_id)->orderBy('event_date','desc')->limit(1)->first();
            $get_data = PersonalMeasurement::select('event_date','weight','weightUnit')->where('weight','>',0)->where('client_id',Auth::user()->account_id)->limit(1)->first();
            $personal_measurement = PersonalMeasurement::select('event_date','weight','weightUnit')->whereIn('id',$event_id)->where('weight','>',0)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            if(isset($get_data)){
                foreach($personal_measurement as $value){
                    if($value['weight'] > 0){
                        if(isset($get_last_data) && $get_last_data->weightUnit == 'Imperial'){
                            if($value['weightUnit'] == 'Metric'){
                                $data[] = ['date' => $value['event_date'],'value'=>number_format((float)($value['weight']*2.2046226218), 2, '.', '')];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['weight']];
                            }
                        }else{
                            if($value['weightUnit'] == 'Imperial'){
                                $data[] = ['date' => $value['event_date'],'value'=>number_format((float)($value['weight']/2.2046226218), 2, '.', '')];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['weight']];
                            }
                        }
                    }
                }
            }else{
                $clients = Clients::with(['parq'=>function($q){
                    $q->select('client_id','weight','weightUnit');
                }])->find(Auth::User()->account_id);
                $get_last_data = $clients->parq;
               
                if(isset($get_last_data) && $get_last_data->weightUnit == 'Imperial'){
                    if($value['weightUnit'] == 'Metric'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>number_format((float)($get_last_data->weight*2.2046226218), 2, '.', '')];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->weight];
                    }
                }else{
                    if($value['weightUnit'] == 'Imperial'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>number_format((float)($get_last_data->weight/2.2046226218), 2, '.', '')];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->weight];
                    }
                }
            }
           
            $weight_unit = isset($get_last_data) ? ($get_last_data->weightUnit == 'Imperial' ? 'pound' : 'kg') : 'kg';
            
            $body_part = 'Weight';
        }
        $pastMonth = date("Y-m-d");
        $viewCalendar = date('M Y',strtotime(date("Y-m-d")));
        if(count($data) > 0){
            return view('Result.filter-body-measurement',compact('data','body_part','bodypart','duration','pastMonth','viewCalendar','height_unit','weight_unit','startOfTheMonth','endOfTheMonth','stepSize'));
        }else{
            return view('Result.no-graph',compact('pastMonth','viewCalendar'));
        }
    }

    public function filterBodyMeasurementGraph(Request $request){
        // dd($request->all());
        $bodypart = $request->bodypart;
        $duration = $request->duration;
        $personal_measurement = PersonalMeasurement::select('id','event_date')->orderBy('event_date','asc')->orderBy('id','asc')->where('client_id',Auth::User()->account_id);
        if($duration == 1){
            $stepSize = 1;
            if($request->month){
                if($request->status == 'previous'){
                    $pastOneMonths = date("Y-m-d", strtotime("$request->month first day of -1 months"));
                    $currentMonth = date('Y-m-t', strtotime($pastOneMonths));
                    $startOfTheMonth =  $pastOneMonths;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $pastOneMonths = date("Y-m-d", strtotime("$request->month first day of +1 months"));
                    $currentMonth = date('Y-m-t', strtotime($pastOneMonths));
                    $startOfTheMonth =  $pastOneMonths;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $pastOneMonths;
                $viewCalendar = date('M Y',strtotime($pastOneMonths));
            }
            // dd($pastOneMonths,$currentMonth);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastOneMonths , $currentMonth])->get()->toArray();
          $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd(date('M Y',strtotime($pastOneMonths)));
            
        }
        
        if($duration == 3){
            $stepSize =  1;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -3 months"));
                    $pastThreeMonths = date('Y-m-d', strtotime("$currentMonth first day of -2 months"));
                    $startOfTheMonth =  $pastThreeMonths;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +3 months"));
                    $pastThreeMonths = date('Y-m-d', strtotime("$currentMonth first day of -2 months"));
                    $startOfTheMonth =  $pastThreeMonths;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
                
            }
            
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastThreeMonths , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($duration == 6){
            $stepSize =  1;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -6 months"));
                    $pastSixThreeMonths = date('Y-m-d', strtotime("$currentMonth first day of -5 months"));
                    $startOfTheMonth =  $pastSixThreeMonths;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +6 months"));
                    $pastSixThreeMonths = date('Y-m-d', strtotime("$currentMonth first day of -5 months"));
                    $startOfTheMonth =  $pastSixThreeMonths;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
            }
            // $pastSixThreeMonths = date("Y-m-d", strtotime("$request->month first day of -6 months"));
            // $currentMonth = date("Y-m-d");
            // dd($pastSixThreeMonths,$currentMonth);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastSixThreeMonths , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($duration == 12){
            $stepSize =  2;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -12 months"));
                    $pasttwelveMonths = date('Y-m-d', strtotime("$currentMonth first day of -11 months"));
                    $startOfTheMonth =  $pasttwelveMonths;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +12 months"));
                    $pasttwelveMonths = date('Y-m-d', strtotime("$currentMonth first day of -11 months"));
                    $startOfTheMonth =  $pasttwelveMonths;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
            }
            // $pasttwelveMonths = date("Y-m-d", strtotime("$request->month first day of -12 months"));
            // $currentMonth = date("Y-m-d");
            // dd($pasttwelveMonths,$currentMonth);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pasttwelveMonths , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($duration == 24){
            $stepSize =  6;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -24 months"));
                    $pasttwoyears = date('Y-m-d', strtotime("$currentMonth first day of -23 months"));
                    $startOfTheMonth =  $pasttwoyears;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +24 months"));
                    $pasttwoyears = date('Y-m-d', strtotime("$currentMonth first day of -23 months"));
                    $startOfTheMonth =  $pasttwoyears;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
            }
            // $pasttwoyears = date("Y-m-d", strtotime("$request->month first day of -24 months"));
            // $currentMonth = date("Y-m-d");
            // dd($pasttwoyears,$currentMonth);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pasttwoyears , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($duration == 36){
            $stepSize =  6;
            if($request->month){
                if($request->status == 'previous'){
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of -36 months"));
                    $pastThreeyears = date('Y-m-d', strtotime("$currentMonth first day of -35 months"));
                    $startOfTheMonth =  $pastThreeyears;
                    $endOfTheMonth = $currentMonth;
                }else{
                    $currentMonth = date("Y-m-t", strtotime("$request->month first day of +36 months"));
                    $pastThreeyears = date('Y-m-d', strtotime("$currentMonth first day of -35 months"));
                    $startOfTheMonth =  $pastThreeyears;
                    $endOfTheMonth = $currentMonth;
                }
                $pastMonth = $currentMonth;
                $viewCalendar = date('M Y',strtotime($currentMonth));
            }
            // $pastThreeyears = date("Y-m-d", strtotime("$request->month first day of -36 months"));
            // $currentMonth = date("Y-m-d");
            // dd($pastThreeyears,$currentMonth);
            $personal_measurement = $personal_measurement->whereBetween('event_date',[$pastThreeyears , $currentMonth])->get()->toArray();
            $previous_event_date = [];
            $event_id = [];
            foreach($personal_measurement as $val){
                
                if(in_array($val['event_date'], $previous_event_date)){
                    array_pop($event_id);
                    array_push($event_id, $val['id']);
                }else{
                    $event_id[] = $val['id'];
                }
                $previous_event_date[] = $val['event_date'];
            }
            // dd($personal_measurement->get()->toArray());
        }
        if($bodypart == 'height'){
            $get_last_data = PersonalMeasurement::select('event_date','height','heightUnit')->where('height','>',0)->whereIn('id',$event_id)->orderBy('event_date','desc')->limit(1)->first();
            $get_data = PersonalMeasurement::select('event_date','height','heightUnit')->where('height','>',0)->where('client_id',Auth::user()->account_id)->limit(1)->first();
            // dd($get_data);
            $personal_measurement = PersonalMeasurement::select('event_date','height','heightUnit')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            if(isset($get_data)){
                foreach($personal_measurement as $value){
                    if($value['height'] > 0){
                        if(isset($get_last_data) && $get_last_data->heightUnit == 'inches'){
                            if($value['heightUnit'] == 'cm'){
                                $data[] = ['date' => $value['event_date'],'value'=>(number_format((float)($value['height']*0.393701), 2, '.', ''))];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['height']];
                            }
                        }else{
                            if($value['heightUnit'] == 'inches'){
                                $data[] = ['date' => $value['event_date'],'value'=>(number_format((float)($value['height']/0.393701), 2, '.', ''))];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['height']];
                            }
                        }
                        
                    }

                }
                $height_unit = isset($get_last_data) ? ($get_last_data['heightUnit'] == 'inches' ? 'inches' : 'cm') : 'cm';
            }else{
                $clients = Clients::with(['parq'=>function($q){
                    $q->select('client_id','height','heightUnit');
                }])->find(Auth::User()->account_id);
                $get_last_data = $clients->parq;
                // dd($get_last_data);

                if(isset($get_last_data) && $get_last_data->heightUnit != 'Metric'){
                    if($get_last_data->heightUnit == 'Metric'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>(number_format((float)($get_last_data->height*0.393701), 2, '.', ''))];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->height];
                    }
                }else{
                    if($get_last_data->heightUnit == 'inches'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>(number_format((float)($get_last_data->height/0.393701), 2, '.', ''))];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->height];
                    }
                }
                $height_unit = isset($get_last_data) ? ($get_last_data->heightUnit != 'Metric' ? 'inches' : 'cm') : 'cm';
            }
            $body_part = 'Height';
        }
        if($bodypart == 'chest'){
            $personal_measurement = PersonalMeasurement::select('event_date','chest')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['chest'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['chest']];
                }
            }
            $body_part = 'Chest';
        }
        if($bodypart == 'neck'){
            $personal_measurement = PersonalMeasurement::select('event_date','neck')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['neck'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['neck']];
                }
            }
            $body_part = 'Neck';
        }
        if($bodypart == 'bicepR'){
            $personal_measurement = PersonalMeasurement::select('event_date','bicep_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['bicep_r'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['bicep_r']];
                }
            }
            $body_part = 'Bicep R';
        }
        if($bodypart == 'bicepL'){
            $personal_measurement = PersonalMeasurement::select('event_date','bicep_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['bicep_l'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['bicep_l']];
                }
            }
            $body_part = 'Bicep L';
        }
        if($bodypart == 'forearmR'){
            $personal_measurement = PersonalMeasurement::select('event_date','forearm_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['forearm_r'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['forearm_r']];
                }
            }
            $body_part = 'Forearm R';
        }
        if($bodypart == 'forearmL'){
            $personal_measurement = PersonalMeasurement::select('event_date','forearm_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['forearm_l'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['forearm_l']];
                }
            }
            $body_part = 'Forearm L';
        }
        if($bodypart == 'abdomen'){
            $personal_measurement = PersonalMeasurement::select('event_date','waist')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['waist'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['waist']];
                }
            }
            $body_part = 'Waist';
        }
        if($bodypart == 'hip'){
            $personal_measurement = PersonalMeasurement::select('event_date','hip')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['hip'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['hip']];
                }
            }
            $body_part = 'Hip';
        }
        if($bodypart == 'thighR'){
            $personal_measurement = PersonalMeasurement::select('event_date','thigh_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['thigh_r'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['thigh_r']];
                }
            }
            $body_part = 'Thigh R';
        }
        if($bodypart == 'thighL'){
            $personal_measurement = PersonalMeasurement::select('event_date','thigh_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['thigh_l'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['thigh_l']];
                }
            }
            $body_part = 'Thigh L';
        }
        if($bodypart == 'calfR'){
            $personal_measurement = PersonalMeasurement::select('event_date','calf_r')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['calf_r'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['calf_r']];
                }
            }
            $body_part = 'Calf R';
        }
        if($bodypart == 'calfL'){
            $personal_measurement = PersonalMeasurement::select('event_date','calf_l')->whereIn('id',$event_id)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            foreach($personal_measurement as $value){
                if($value['calf_l'] > 0){
                $data[] = ['date' => $value['event_date'],'value'=>$value['calf_l']];
                }
            }
            $body_part = 'Calf L';
        }
        if($bodypart == 'weight'){
            
            $get_last_data = PersonalMeasurement::select('event_date','weight','weightUnit')->where('weight','>',0)->whereIn('id',$event_id)->orderBy('event_date','desc')->limit(1)->first();
            $get_data = PersonalMeasurement::select('event_date','weight','weightUnit')->where('weight','>',0)->where('client_id',Auth::user()->account_id)->limit(1)->first();
            $personal_measurement = PersonalMeasurement::select('event_date','weight','weightUnit')->whereIn('id',$event_id)->where('weight','>',0)->orderBy('event_date','asc')->get()->toArray();
            $data = [];
            if(isset($get_data)){
                foreach($personal_measurement as $value){
                    if($value['weight'] > 0){
                        if(isset($get_last_data) && $get_last_data->weightUnit == 'Imperial'){
                            if($value['weightUnit'] == 'Metric'){
                                $data[] = ['date' => $value['event_date'],'value'=>number_format((float)($value['weight']*2.2046226218), 2, '.', '')];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['weight']];
                            }
                        }else{
                            if($value['weightUnit'] == 'Imperial'){
                                $data[] = ['date' => $value['event_date'],'value'=>number_format((float)($value['weight']/2.2046226218), 2, '.', '')];
                            }else{
                                $data[] = ['date' => $value['event_date'],'value'=>$value['weight']];
                            }
                        }
                    }
                }
            }else{
                $clients = Clients::with(['parq'=>function($q){
                    $q->select('client_id','weight','weightUnit');
                }])->find(Auth::User()->account_id);
                $get_last_data = $clients->parq;
               
                if(isset($get_last_data) && $get_last_data->weightUnit == 'Imperial'){
                    if($value['weightUnit'] == 'Metric'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>number_format((float)($get_last_data->weight*2.2046226218), 2, '.', '')];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->weight];
                    }
                }else{
                    if($value['weightUnit'] == 'Imperial'){
                        $data[] = ['date' => date("Y-m-d"),'value'=>number_format((float)($get_last_data->weight/2.2046226218), 2, '.', '')];
                    }else{
                        $data[] = ['date' => date("Y-m-d"),'value'=>$get_last_data->weight];
                    }
                }
            }
           
            $weight_unit = isset($get_last_data) ? ($get_last_data->weightUnit == 'Imperial' ? 'pound' : 'kg') : 'kg';
            
            $body_part = 'Weight';
        } 
        
        if(count($data) > 0){
            return view('Result.filter-body-measurement',compact('data','body_part','bodypart','duration','pastMonth','viewCalendar','height_unit','weight_unit','startOfTheMonth','endOfTheMonth','stepSize'));
        }else{
            return view('Result.no-graph',compact('pastMonth','viewCalendar'));
        }

        
    }

    /**
     * Get Statistics Data
     * 
     * @param
     * @return $response
     */
    public function getData(Request $request){
        $clientId = Auth::User()->account_id;
        $eventDate = $request->eventDate;
        $diaryData = [];
        $diary = PersonalDiary::where('client_id',$clientId)->where('event_date',$eventDate)->first();
        if($diary){
            $diaryData = $diary->toArray();

        }

        $measurementData = [];
        $measurement = PersonalMeasurement::where('client_id',$clientId)->whereDate('event_date','<=',$eventDate)->orderBy('event_date','DESC')->orderBy('id','DESC')->first();
        if($measurement){
           $measurementData = $measurement->toArray();
        }
        $statisticsData = [];
        $statistics = PersonalStatistic::where('client_id',$clientId)->whereDate('event_date','<=',$eventDate)->orderBy('event_date','DESC')->orderBy('id','DESC')->first();

        if($statistics){
            $statisticsData = $statistics->toArray();
        }
        $sleepData = [];
        $sleepJournal = SleepJournal::where('client_id',$clientId)->where('event_date',$eventDate)->first();
        if($sleepJournal){
            $sleepData = $sleepJournal->toArray();

        }

        $hydrationJournalData = [];
        $weightUnit =null;
        $hydrationJournal = HydrationJournal::where('client_id',$clientId)->where('event_date',$eventDate)->get();
        $personalMeasurement = PersonalMeasurement::where('client_id', $clientId)->orderBy('event_date','DESC')->orderBy('id', 'DESC')->select('weight','weightUnit')->first();

        $weight = 0;
        
        if($personalMeasurement && $personalMeasurement->weight > 0){
            $weight = $personalMeasurement['weight'];
            $weightUnit = $personalMeasurement['weightUnit'];

        }else{
            $benchmarks = Benchmarks::where('client_id', $clientId)->orderBy('id', 'DESC')->select('weight')->first();
            if($benchmarks && $benchmarks->weight > 0){
                $weight = $benchmarks['weight'];
            }
            else{
                $parq_weight = Parq::where('client_id', $clientId)->orderBy('id', 'DESC')->select('weight','weightUnit')->first();
                if($parq_weight && $parq_weight->weight > 0){
                    $weight = $parq_weight['weight'];
                     $weightUnit = $parq_weight['weightUnit'];
                }   
            }
        }

        if($weightUnit == 'Imperial'){
            $weightConvert = $weight/2.2046226218;
            $requiredVolume = $weightConvert * 0.04;

        }
        else{
             $requiredVolume = $weight * 0.04;

        }

        
        $hydrationJournalData['required_amount'] = sprintf('%1.1f',$requiredVolume);
        $hydrationJournalData['consumed'] = 0;
        $hydrationJournalData['liquidType'] = 0;
        $hydrationJournalData['consumedHistory'] = [];
        if(count($hydrationJournal)){
            $consumed = $hydrationJournal->sum('drank');
            $hydrationJournalData['consumed'] = $consumed;
            foreach($hydrationJournal as $item){

                if($item->drink_type == 1)
                {

                    $item->drink_type = 'Water';
                }
                if($item->drink_type == 2)
                {
                    $item->drink_type = 'Coffee';
                }
                if($item->drink_type == 3)
                {
                    $item->drink_type = 'Tea';
                }
                if($item->drink_type == 4)
                {
                    $item->drink_type = 'Juice';
                }
                if($item->drink_type == 5)
                {
                    $item->drink_type = 'Soda';
                }
                if($item->drink_type == 6)
                {
                    $item->drink_type = 'Milk';
                }
                if($item->drink_type == 7)
                {
                    $item->drink_type = 'Alcohal';
                }
                if($item->drink_type == 8)
                {
                    $item->drink_type = 'Sports Drinks';
                }

                $hydrationJournalData['consumedHistory'][] = [
                    'volume' => $item->drank,
                    'time' => $item->time,
                    'liquidType' =>$item->drink_type,
                    'id' => $item->id
                ];
            }
        }

        $response = [
            'diaryData' => $diaryData,
            'measurementData' => $measurementData,
            'statisticsData' => $statisticsData,
            'sleepData' => $sleepData,
            'hydrationJournalData' => $hydrationJournalData,
            'weight' => $weight
        ];
        return response()->json($response);
    }

    /**
     * Save Nutritional Journal Data
     */
    public function saveNutritionalData(Request $request){
        try{
            $clientMealplan = new MpClientMealplan;
            $clientMealplan->client_id = Auth::User()->account_id;
            $clientMealplan->event_id = 0;
            $clientMealplan->event_type = "Meal";
            $clientMealplan->event_date = $request->eventDate;
            $clientMealplan->event_meal_category = $request->cat_id;
            if($request->isSnack)
                $clientMealplan->snack_type = $request->snackType;
            $clientMealplan->recipe_name = $request->recipeName;
            $clientMealplan->serving_size = $request->servingSize;
            $clientMealplan->is_custom = $request->is_custom;
            $clientMealplan->time_opt = $request->time_opt;
            $clientMealplan->nutritional_time = $request->nutritionalTime;
            $clientMealplan->hunger_rate = $request->hungerRate;
            $clientMealplan->activity_label = $request->activityLabel;
            $clientMealplan->activity_label = $request->generalNotes;
            $clientMealplan->image = $request->clickedImage;
            $clientMealplan->meal_rating = $request->mealRating;
            $clientMealplan->general_notes = $request->generalNotes;
            $clientMealplan->enjoyed_meal = $request->mealEnjoyed;
            $clientMealplan->save();
            $ingrediantData = $request->ingQuantityData;
            $insertData = [];
            foreach($ingrediantData as $item){
                $insertData[] = [
                    'mpn_client_mealplan_id' => $clientMealplan->id,
                    'ingrediant' => $item['ingredient'],
                    'quantity' => $item['quantity']
                ];
            }
            MpClientMealplanIngrediant::insert($insertData);
            $response = [
                'status' => 'ok',
            ];
        }catch (\ Throwable $e){
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return response()->json($response);
    }

    /**
     * Save Hydration Data
     */
    public function saveHydrationData(Request $request){
        $requestData = $request->all();
        $clientId = Auth::User()->account_id;
        try{
            HydrationJournal::create([
                'client_id' => $clientId,
                'event_date' => $requestData['event_date'],
                'drink_type' => $requestData['liquidType'],
                'time' => $requestData['time'],
                'drank' => $requestData['drank'],
                'add_text' => $requestData['hydrationText']
            ]);
            $response = [
                'status' => 'ok',
                'message' => 'Data saved successfully'
            ];
        }catch(\ Throwable $e){
            $response = [
                'status' => 'ok',
                'message' => $e->getMessage()
            ];
        }
        return response()->json($response);
    }


    public function updateHydrationData(Request $request){
       
        $requestData = $request->all();
        $id = $request->id;
       
        $clientId = Auth::User()->account_id;
        $HydrationJournal = HydrationJournal::where('client_id', $clientId)->where('id', $id)->first();

            if($HydrationJournal)
        {
            $HydrationJournal->update(['drink_type' => $requestData['liquidType']]);
            $HydrationJournal->update(['drank' => $requestData['drank']]);
            $HydrationJournal->update(['time' => $requestData['time']]);
        }
        
            $response = [
                'status' => 'ok',
                'message' => 'Data saved successfully'
            ];
      
        return response()->json($response);
    }
}