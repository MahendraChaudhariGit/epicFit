<?php
namespace App\Http\Traits;
use Session;
use App\CalendarSettingsCancelReasons;
use App\CalendarSetting;

trait CalendarSettingTrait{
    /**
     * Insert Calendar settings
     *
     * @return model 
     */
    protected function createCalendarSettings(){
        $calendarsetting = new CalendarSetting;
        $calendarsetting->cs_business_id = Session::get('businessId');
        $calendarsetting->cs_first_day = '1';
        $calendarsetting->cs_start_time = '09:00:00'; 
        $calendarsetting->cs_intervals = '15';
        $calendarsetting->cs_view = 'weekly';
        $calendarsetting->cs_initial_status = "confirmed";
        $calendarsetting->cs_initial_status_consultation = "confirmed";
        $calendarsetting->cs_initial_status_benchmarking = "confirmed";
        $calendarsetting->cs_allow_appointments = 1;
        $calendarsetting->cs_reduced_rate = 10;
        $calendarsetting->sales_process_settings ='{"steps":["4","5","18"],"teamCount":"","indivCount":"","order":[{"id":"team-1"},{"id":"indiv-1"}],"session":[6,12]}';
        $calendarsetting->save();
         $cs_id=$calendarsetting->id;
       // $calendarreason=CalendarSettingsCancelReasons::where('cscr_business_id',Session::get('businessId'))->first();
        $reason = array('Did not specify','Other commitments','Not necessary now','Did not show','Appointment made in error' );
        $newReasonRow=[];
        $calendarreason=new CalendarSettingsCancelReasons;
        foreach ($reason as $value) {
          $timestamp = createTimestamp();
          $newReasonRow[] = ['cscr_reason'=>$value/*,'cscr_business_id'=>Session::get('businessId')*/,'cscr_id'=>$cs_id,'created_at'=>$timestamp,'updated_at'=>$timestamp];  
        }
        if(count($newReasonRow))
                    CalendarSettingsCancelReasons::insert($newReasonRow);

        return $calendarsetting;
    }

    protected function getCalendSettings(){
      $record = CalendarSetting::with('reason')->where('cs_business_id', Session::get('businessId'))->where('cs_client_id',0)->select('id', 'cs_allow_appointments', 'cs_intervals', 'cs_view', 'cs_first_day', 'cs_initial_status', 'cs_start_time', 'cs_reduced_rate','cs_initial_status_consultation','cs_initial_status_benchmarking')->first();
      $settings = ['cs_allow_appointments'=>$record->cs_allow_appointments, 'cs_intervals'=>$record->cs_intervals, 'cs_view'=>$record->cs_view, 'cs_first_day'=>$record->cs_first_day, 'cs_initial_status'=>$record->cs_initial_status,'cs_initial_status_consultation'=>$record->cs_initial_status_consultation,'cs_initial_status_benchmarking'=>$record->cs_initial_status_benchmarking, 'cs_start_time'=>$record->cs_start_time, 'cs_reduced_rate'=>$record->cs_reduced_rate];

      $cancelReasons = [''=>'-- Select --'];
      $reasons = $record->reason;
      if($reasons->count()){
        foreach($reasons as $reason){
          $cancelReasons[$reason->cscr_reason] = $reason->cscr_reason;
        }
      }
      
      return compact('settings', 'cancelReasons');
    }

    /*protected function getCancelReasons($settingId = 0){
    if($settingId==0)
    {
        $res=CalendarSettingsCancelReasons::whereHas('calSetting', function($q){ $q->where('cs_business_id',Session::get('businessId'));})->get();

    }
    else{
         $res=CalendarSettingsCancelReasons::where('cscr_id',$settingId)->get();
    }
        
           $reasons = [''=>'-- Select --'];
          if($res->count())
          {
              //$r=$res->reason->pluck('cscr_reason')->toArray();
              $r=$res->pluck('cscr_reason')->toArray();
              if($r)
              {
                   foreach ($r as $value) {
                    $reasons[$value]=$value;
                     // $reasons[$value->]=$value->cscr_reason; 
                   }
              }     
          }

          return $reasons;
    }*/

    /**
     * Calendar setting for client 
     *
     * @param int clientid
     * @return Array Calendar setting
     */
    protected function getCalendSettingsForClient($clientid){
      $record = CalendarSetting::with('reason')->where('cs_business_id', Session::get('businessId'))->where('cs_client_id',$clientid)->select('id', 'cs_allow_appointments', 'cs_intervals', 'cs_view', 'cs_first_day', 'cs_initial_status', 'cs_start_time', 'cs_reduced_rate','cs_initial_status_consultation','cs_initial_status_benchmarking')->first();
      
      if(!count($record)){
        $record = CalendarSetting::with('reason')->where('cs_business_id', Session::get('businessId'))->select('id', 'cs_allow_appointments', 'cs_intervals', 'cs_view', 'cs_first_day', 'cs_initial_status', 'cs_start_time', 'cs_reduced_rate','cs_initial_status_consultation','cs_initial_status_benchmarking')->first();
      }
      
      $settings = ['cs_allow_appointments'=>$record->cs_allow_appointments, 'cs_intervals'=>$record->cs_intervals, 'cs_view'=>$record->cs_view, 'cs_first_day'=>$record->cs_first_day, 'cs_initial_status'=>$record->cs_initial_status,'cs_initial_status_consultation'=>$record->cs_initial_status_consultation,'cs_initial_status_benchmarking'=>$record->cs_initial_status_benchmarking, 'cs_start_time'=>$record->cs_start_time, 'cs_reduced_rate'=>$record->cs_reduced_rate];

      $cancelReasons = [''=>'-- Select --'];
      $reasons = $record->reason;
      if($reasons->count()){
        foreach($reasons as $reason){
          $cancelReasons[$reason->cscr_reason] = $reason->cscr_reason;
        }
      }
      return compact('settings', 'cancelReasons');
    }
    

}