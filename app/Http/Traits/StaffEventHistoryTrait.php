<?php
namespace App\Http\Traits;

/*use App\StaffEventClass;
use App\StaffEvent;
use App\StaffEventBusy;
use App\Staff;
use \stdClass;*/
use App\StaffEventHistory;
use App\StaffEventHistoryLog;
use Auth;
//use App\StaffEventClass;

trait StaffEventHistoryTrait{
    protected function newHistory($request){
        $event = $request['event'];
        $history = new StaffEventHistory();
        $history->seh_text = $request['eventType'].' created from calendar';
        $history->seh_name = Auth::user()->fullName;
        $history->seh_type = 'new';
        $event->histories()->save($history);

        $log = new StaffEventHistoryLog();
        $log->sehl_seh_id = $history->seh_id;
        $log->sehl_field = $event->getKeyName();
        $log->sehl_new_val = $event->getKey();
        $history->logs()->save($log);
    }

    protected function ammendHistory($request){
        if($request['text'] != ''){
            if(isset($request['name']))
            {
                $name = $request['name'];
            }
            elseif (Auth::check())
            {
                $name = Auth::user()->fullName;
            }
            else
            {
                $name = 'System';
            }
            $event = $request['event'];
            $history = new StaffEventHistory();
            $history->seh_text = $request['text'];
            $history->seh_name = $name;
            $history->seh_type = 'ammend';
            $event->histories()->save($history);
        }

        /*$log = new StaffEventHistoryLog();
        $log->sehl_seh_id = $history->seh_id;
        $log->sehl_field = $event->getKeyName();
        $log->sehl_new_val = $event->getKey();
        $history->logs()->save($log);*/
    }

    protected function alertHistory($request){
        if($request['text'] != ''){
            $event = $request['event'];
            $history = new StaffEventHistory();
            $history->seh_text = $request['text'];
            $history->seh_type = 'alert';
            $event->histories()->save($history);
        }
    }
}