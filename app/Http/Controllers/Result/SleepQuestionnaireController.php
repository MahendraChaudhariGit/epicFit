<?php

namespace App\Http\Controllers\Result;

use Illuminate\Http\Request;
use App\Result\User;
use App\Clients;
use App\SleepQuestionnaire;
use App\ChronotypeSurvey;
use Carbon\Carbon;
use Session;
use DB;
use Auth;
use Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Traits\Result\HelperTrait;
use App\Http\Traits\Result\ClientEventsTrait;
use App\Http\Traits\LocationAreaTrait;
use App\Http\Traits\StaffEventTrait;
use Talk;
use App\Result\Models\MeasurementGalleryImage;
use App\Result\Models\MeasurementBeforeAfterImage;
use App\Result\Models\FinalProgressPhoto;

class SleepQuestionnaireController extends Controller {
    
    public function sleepQuestionnaire(){
        $sleep_questionnaire = SleepQuestionnaire::where('client_id',Auth::User()->account_id)->first();
        if(isset($sleep_questionnaire)){
            return view('main-sleep-questions',compact('sleep_questionnaire'));
        }else{
            $client = Clients::find(Auth::User()->account_id);
            $age = Carbon::parse($client->birthday)->age;
  
            return view('sleep-questions',compact('client','age'));
        }
        
    }
    public function saveSleepQuestionnaire(Request $request){
        // dd($request->all());
        $sleep_questionnaire = SleepQuestionnaire::where('client_id',Auth::User()->account_id)->first();
        if(isset($sleep_questionnaire)){
            $save = $sleep_questionnaire->update($request->all());
        }else{
            $request->merge([
                'client_id' => Auth::User()->account_id,
                'date' => date("Y-m-d",strtotime($request->date1))
            ]);
            $save = SleepQuestionnaire::create($request->all());
        }
        return redirect('/sleep');
    }

    public function chronotypeSurvey(){
        $chronotype_survey = ChronotypeSurvey::where('client_id',Auth::User()->account_id)->first();
        if(isset($chronotype_survey)){
            return view('main-chronotype-survey',compact('chronotype_survey'));
        }else{
            return view('chronotype-survey');
        }
        
    }
    public function saveChronotypeSurvey(Request $request){
        // dd($request->all());
        $chronotype_survey = ChronotypeSurvey::where('client_id',Auth::User()->account_id)->first();
        if(isset($chronotype_survey)){
            $save = $chronotype_survey->update($request->all());
        }else{
            $request->merge([
                'client_id' => Auth::User()->account_id,
                'date' => date("Y-m-d",strtotime($request->date1))
            ]);
            $save = ChronotypeSurvey::create($request->all());
        }
        return redirect('/chronotype-survey');
    }
}