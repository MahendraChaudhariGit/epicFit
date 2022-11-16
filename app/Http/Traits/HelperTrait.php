<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use DB;
use File;
use Session;
use App\Log;
use Carbon\Carbon;
use App\Business;
use App\StaffEventClass;
use App\StaffEventSingleService;
use App\Clients;
use DateTime;
use DateTimeZone;
use App\Service;
use App\TaskCategory;
use App\Task;
use Auth;
use App\Contact;
use App\Invoice;
use App\SalesToolsInvoice;
use App\MemberShipTax;
use App\InvoiceItems;
use App\Payment;
use App\Makeup;
use App\Category;
use App\ProductSize;
use App\SalesToolsInvoicePaymentTypes;

trait HelperTrait{
    public function getStates($countryCode){
        if(!$countryCode || $countryCode == 'undefined')
            return ['' => '-- Select --'];
        
        return ['' => '-- Select --']+\Country::getStateLists($countryCode);
    }

    protected function ifEmailAvailable($data = array()){
    	if(!array_key_exists('email', $data) || !$data['email'] || !array_key_exists('entity', $data) || !$data['entity'])
    		return true;

    	switch($data['entity']){
            case "business":
                $model = 'App\Business';
                $emailCol = 'email';
                $idCol = 'id';
                break;
            /*case "location": 
                $model = 'App\Location';
                $emailCol = 'email';
                $idCol = 'id';
                break;
            case "staff": 
                $model = 'App\Staff';
                $emailCol = 'email';
                $idCol = 'id';
                break;
		    case "client": 
		        $model = 'App\Clients';
		        $emailCol = 'email';
		        $idCol = 'id';
		        break;
		    case "contact":
		        $model = 'App\Contact'; 
		        $emailCol = 'email';
		        $idCol = 'id';
		        break;*/
            case "user":
                $model = 'App\Models\Access\User\User';
                $emailCol = 'email';
                $idCol = 'id';
                break;
		    default:
		        return true;
		}

    	if(array_key_exists('id', $data) && $data['id'])
    		$emailCount =  $model::where($emailCol, $data['email'])->where($idCol, '<>', $data['id'])->count();
    	else
    		$emailCount = $model::where($emailCol, $data['email'])->count();

        if($emailCount > 0)
            return false;
    
        return true;
    }

    protected function ifEmailAvailableInSameBusiness($data = array()){
        if(!array_key_exists('email', $data) || !$data['email'] || !array_key_exists('entity', $data) || !$data['entity'])
            return true;

        switch($data['entity']){
            case "location":
                $model = 'App\Location';
                /*$emailCol = 'email';
                $idCol = 'id';*/
                break;
            case "staff":
                $model = 'App\Staff';
                /*$emailCol = 'email';
                $idCol = 'id';*/
                break;
            case "client":
                $model = 'App\Clients';
                /*$emailCol = 'email';
                $idCol = 'id';*/
                break;
            case "contact":
                $model = 'App\Contact'; 
                /*$emailCol = 'email';
                $idCol = 'id';*/
                break;
            case "admin":
                $model = 'App\Models\Access\User\User';
                /*$emailCol = 'email';
                $idCol = 'id';*/
                break;
            default:

                return true;
        }

        if(array_key_exists('businessId', $data) && $data['businessId'])
            $businessId = $data['businessId'];
        else
            $businessId = Session::get('businessId');

        $query =  $model::where('business_id', $businessId)->where(/*$emailCol*/'email', $data['email']);
        if($data['entity'] == 'admin')
            $query->where('account_type', 'Admin');
        if(array_key_exists('id', $data) && $data['id'])
            $query->where(/*$idCol*/'id', '<>', $data['id']);//->count();
        $emailCount = $query->count();

        if($emailCount > 0)
            return false;
    
        return true;
    }

    protected function ifPhoneExistInSameBusiness($data = array()){
        if(!array_key_exists('numb', $data) || !$data['numb'] || !array_key_exists('entity', $data) || !$data['entity'])
            return false;

        switch($data['entity']){
            case "location":
                $model = 'App\Location';
                $numbCol = 'phone';
                break;
            case "staff":
                $model = 'App\Staff';
                $numbCol = 'phone';
                break;
            case "client":
                $model = 'App\Clients';
                $numbCol = 'phonenumber';
                break;
            case "contact":
                $model = 'App\Contact'; 
                $numbCol = 'phone';
                break;
            case "admin":
                $model = 'App\Models\Access\User\User';
                $numbCol = 'telephone';
                break;
            default:
                return true;
        }

        if(array_key_exists('businessId', $data) && $data['businessId'])
            $businessId = $data['businessId'];
        else
            $businessId = Session::get('businessId');

        $query =  $model::where('business_id', $businessId)->where($numbCol, $data['numb']);
        if($data['entity'] == 'admin')
            $query->where('account_type', 'Admin');
        if(array_key_exists('id', $data) && $data['id'])
            $query->where('id', '<>', $data['id']);
        return $query->exists();
    }

  /* protected function setWorkingHours(Request $request, $data){   
        if($data['mode'] == 'edit'){
            DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_entity_id', $data['entityId'])->whereNotNull('hr_edit_date')->whereNull('deleted_at')->delete();
        }
        
        $input = $request->all();
        ksort($input);
        $flag=true;
        $monday_start = $monday_end = $tuesday_start = $tuesday_end = $wednesday_start = $wednesday_end = $thursday_start = $thursday_end = $friday_start = $friday_end = $saturday_start = $saturday_end = $sunday_start = $sunday_end = [];
        if($request->exists('monday')){
            $flag=false;
            foreach($input as $key => $value){
                if(strpos($key, 'monday_start') !== false)
                    $monday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'monday_end') !== false)
                    $monday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('tuesday')){
            $flag=false;
            foreach($input as $key => $value){
                if(strpos($key, 'tuesday_start') !== false)
                    $tuesday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'tuesday_end') !== false)
                    $tuesday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('wednesday')){
            $flag=false;
            foreach($input as $key => $value){
                if(strpos($key, 'wednesday_start') !== false)
                    $wednesday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'wednesday_end') !== false)
                    $wednesday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('thursday')){
            $flag=false;
            foreach($input as $key => $value){
                if(strpos($key, 'thursday_start') !== false)
                    $thursday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'thursday_end') !== false)
                    $thursday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('friday')){
            $flag=false;
            foreach($input as $key => $value){
                if(strpos($key, 'friday_start') !== false)
                    $friday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'friday_end') !== false)
                    $friday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('saturday')){
            $flag=false;
            foreach($input as $key => $value){
                if(strpos($key, 'saturday_start') !== false)
                    $saturday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'saturday_end') !== false)
                    $saturday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('sunday')){
            $flag=false;
            foreach($input as $key => $value){
                if(strpos($key, 'sunday_start') !== false)
                    $sunday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'sunday_end') !== false)
                    $sunday_end[] = timeStringToDbTime($value);
            }   
        }

        $insertData = $returnData = array();
        $dateVal=$request->calStartDate;
        
        if($flag){
            $dayName=date('l',strtotime($dateVal));
            $dt=date('Y-m-d',strtotime($dateVal));
            $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => $dayName, 'hr_start_time' => NULL, 'hr_end_time' => NULL, 'hr_entity_type' => $data['entityType'],'hr_edit_date'=>$dt);
        }
        else{
            /* Start: store date days wise in array*/
            /*$dayWithDate=[];
            for($i = 0; $i < 7; $i++){
                $dayName=strtolower(date('l',strtotime($dateVal)));
                $dayWithDate[$dayName]=date('Y-m-d',strtotime($dateVal));
                $dateVal=date('Y-m-d', strtotime("$dateVal +1 day"));
            }*/
            /* End: store date days wise in array*/
            /*if(count($monday_start)){
                for($i=0; $i<count($monday_start); $i++){
                    $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_edit_date'=>$dayWithDate['monday']);
                    $returnData[] = array('hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i]);
                }
            }
            if(count($tuesday_start)){
                for($i=0; $i<count($tuesday_start); $i++){
                    $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_edit_date'=>$dayWithDate['tuesday']);
                    $returnData[] = array('hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i]);
                }
            }
            if(count($wednesday_start)){
                for($i=0; $i<count($wednesday_start); $i++){
                    $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_edit_date'=>$dayWithDate['wednesday']);
                    $returnData[] = array('hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i]);
                }
            }
            if(count($thursday_start)){
                for($i=0; $i<count($thursday_start); $i++){
                    $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_edit_date'=>$dayWithDate['thursday']);
                    $returnData[] = array('hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i]);
                }
            }
            if(count($friday_start)){
                for($i=0; $i<count($friday_start); $i++){
                    $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_edit_date'=>$dayWithDate['friday']);
                    $returnData[] = array('hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i]);
                }
            }
            if(count($saturday_start)){
                for($i=0; $i<count($saturday_start); $i++){
                    $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_edit_date'=>$dayWithDate['saturday']);
                    $returnData[] = array('hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i]);
                }
            }
            if(count($sunday_start)){
                for($i=0; $i<count($sunday_start); $i++){
                    $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_edit_date'=>$dayWithDate['sunday']);
                    $returnData[] = array('hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i]);
                }
            }
        }
        if(DB::table('hours')->insert($insertData))
            return json_encode([
                'status' => 'updated',
                'hoursData' => $returnData
            ]);
        else
            return json_encode([
                'status' => 'error'
            ]);
    }*/
    protected function setWorkingHours(Request $request, $data){ 
        // if($data['mode'] == 'edit'){
        //     DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
        // }
        
        $input = $request->all();
      
        ksort($input);
        $monday_start = $monday_end = $tuesday_start = $tuesday_end = $wednesday_start = $wednesday_end = $thursday_start = $thursday_end = $friday_start = $friday_end = $saturday_start = $saturday_end = $sunday_start = $sunday_end = [];
        $startDate = Carbon::now();
        DB::table('staff_attendences')->where('sa_staff_id', $data['entityId'])->whereDate('sa_date','>=',$startDate)->whereNull('deleted_at')->delete();

        if($request->exists('monday')){
            foreach($input as $key => $value){
                if(strpos($key, 'monday_start') !== false)
                    $monday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'monday_end') !== false)
                    $monday_end[] = timeStringToDbTime($value);
            }   
        }else{
            if($data['mode'] == 'edit'){
                 DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Monday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
               
            }
        }

        if($request->exists('tuesday')){
            foreach($input as $key => $value){
                if(strpos($key, 'tuesday_start') !== false)
                    $tuesday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'tuesday_end') !== false)
                    $tuesday_end[] = timeStringToDbTime($value);
            }   
        }else{
            if($data['mode'] == 'edit'){
                DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Tuesday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
            }
        }

        if($request->exists('wednesday')){
            foreach($input as $key => $value){
                if(strpos($key, 'wednesday_start') !== false)
                    $wednesday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'wednesday_end') !== false)
                    $wednesday_end[] = timeStringToDbTime($value);
            }   
        }else{
            if($data['mode'] == 'edit'){
                DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Wednesday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
            }
        }

        if($request->exists('thursday')){
            foreach($input as $key => $value){
                if(strpos($key, 'thursday_start') !== false)
                    $thursday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'thursday_end') !== false)
                    $thursday_end[] = timeStringToDbTime($value);
            }   
        }else{
            if($data['mode'] == 'edit'){
                DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Thursday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
            }
        }

        if($request->exists('friday')){
            foreach($input as $key => $value){
                if(strpos($key, 'friday_start') !== false)
                    $friday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'friday_end') !== false)
                    $friday_end[] = timeStringToDbTime($value);
            }   
        }else{
            if($data['mode'] == 'edit'){
                DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Friday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
            }
        }

        if($request->exists('saturday')){
            foreach($input as $key => $value){
                if(strpos($key, 'saturday_start') !== false)
                    $saturday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'saturday_end') !== false)
                    $saturday_end[] = timeStringToDbTime($value);
            }   
        }else{
            if($data['mode'] == 'edit'){
                DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Saturday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
            }
        }

        if($request->exists('sunday')){
            foreach($input as $key => $value){
                if(strpos($key, 'sunday_start') !== false)
                    $sunday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'sunday_end') !== false)
                    $sunday_end[] = timeStringToDbTime($value);
            }   
        }else{
            if($data['mode'] == 'edit'){
                DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Sunday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
            }
        }
        
        $insertData = $returnData = array();
        // $msg = [];
        if(count($monday_start)){
            if(!$this->array_has_dupes($monday_start,$monday_end)){
                if($data['mode'] == 'edit'){
                    DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Monday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
                }
                for($i=0; $i<count($monday_start); $i++){
                    $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_entity_number'=>$i);
                    $returnData[] = array('hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i],'hr_entity_number'=>$i);
                }
            }else{
                return ([
                    'status' => 'error',
                    'msg' => 'Monday  time should not be overlapped.'
                    
                ]);
            }
        }
        if(count($tuesday_start)){
            if(!$this->array_has_dupes($tuesday_start,$tuesday_end)){
                if($data['mode'] == 'edit'){
                    DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Tuesday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
                }
                for($i=0; $i<count($tuesday_start); $i++){
                    $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_entity_number'=>$i);
                    $returnData[] = array('hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i],'hr_entity_number'=>$i);
                }
            }else{
                return ([
                    'status' => 'error',
                    'msg' => 'Tuesday  time should not be overlapped.'
                    
                ]);
            }
        }
        if(count($wednesday_start)){
            if(!$this->array_has_dupes($wednesday_start,$wednesday_end)){
                if($data['mode'] == 'edit'){
                    DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Wednesday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
                }
            for($i=0; $i<count($wednesday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_entity_number'=>$i);
                $returnData[] = array('hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i],'hr_entity_number'=>$i);
            }
        }else{
            return ([
                'status' => 'error',
                'msg' => 'Wednesday  time should not be overlapped.'
                
            ]);
        }
        }
        if(count($thursday_start)){
            if(!$this->array_has_dupes($thursday_start,$thursday_end)){
                if($data['mode'] == 'edit'){
                    DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Thursday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
                }
            for($i=0; $i<count($thursday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_entity_number'=>$i);
                $returnData[] = array('hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i],'hr_entity_number'=>$i);
            }
        }else{
            return ([
                'status' => 'error',
                'msg' => 'Thursday  time should not be overlapped.'
                
            ]);
        }
        }
        if(count($friday_start)){
            if(!$this->array_has_dupes($friday_start,$friday_end)){
                if($data['mode'] == 'edit'){
                    DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Friday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
                }
            for($i=0; $i<count($friday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_entity_number'=>$i);
                $returnData[] = array('hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i],'hr_entity_number'=>$i);
            }
        }else{
            return ([
                'status' => 'error',
                'msg' => 'Friday time should not be overlapped.'
                
            ]);
        }
        }
        if(count($saturday_start)){
            if(!$this->array_has_dupes($saturday_start,$saturday_end)){
                if($data['mode'] == 'edit'){
                    DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Saturday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
                }
            for($i=0; $i<count($saturday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_entity_number'=>$i);
                $returnData[] = array('hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i],'hr_entity_number'=>$i);
            }
        }else{
            return ([
                'status' => 'error',
                'msg' => 'Saturday  time should not be overlapped.'
                
            ]);
        }
        }
        if(count($sunday_start)){
            if(!$this->array_has_dupes($sunday_start,$sunday_end)){
                if($data['mode'] == 'edit'){
                    DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_day','Sunday')->where('hr_entity_id', $data['entityId'])->whereNull('deleted_at')->delete();
                }
            for($i=0; $i<count($sunday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i], 'hr_entity_type' => $data['entityType'],'hr_entity_number'=>$i);
                $returnData[] = array('hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i],'hr_entity_number'=>$i);
            }
        }else{
            return ([
                'status' => 'error',
                'msg' => 'Sunday time should not be overlapped.'
                
            ]);
        }
        }
    
        if(DB::table('hours')->insert($insertData))
            return json_encode([
                'status' => 'updated',
                'hoursData' => $returnData
            ]);
        else
            return json_encode([
                'status' => 'error'
            ]);
    }

    protected function array_has_dupes($startArray,$endArray) {
        $rangeArray = [];
        // streamline per @Felix
    //    $newArray = [];
       foreach($startArray as $key => $data){
        $rangeArray[$key]['start'] = Carbon::parse($data);

       }
       foreach($endArray as $key => $data){
        $rangeArray[$key]['end'] = Carbon::parse($data);

       }
       $array = $this->checkOverlapInDateRanges($rangeArray);
       if(count($array)){
          return true;
       }else{
          return false;
       }
     }

     protected function checkOverlapInDateRanges($ranges) {
        $overlapp = [];
        for($i = 0; $i < count($ranges); $i++){
            for($j= ($i + 1); $j < count($ranges); $j++){
    
                $start = \Carbon\Carbon::parse($ranges[$j]['start']);
                $end = \Carbon\Carbon::parse($ranges[$j]['end']);
    
                $start_first = \Carbon\Carbon::parse($ranges[$i]['start']);
                $end_first = \Carbon\Carbon::parse($ranges[$i]['end']);
    
                if(\Carbon\Carbon::parse($ranges[$i]['start'])->between($start, $end) || \Carbon\Carbon::parse($ranges[$i]['end'])->between($start, $end)){
                    $overlapp[] = $ranges[$j];
                    break;
                }
                if(\Carbon\Carbon::parse($ranges[$j]['start'])->between($start_first, $end_first) || \Carbon\Carbon::parse($ranges[$j]['end'])->between($start_first, $end_first)){
                    $overlapp[] = $ranges[$j];
                    break;
                }
            }
        }
        return $overlapp;
    }

    protected function emptyFileuploadPluginUploadDir(){
        File::cleanDirectory($this->getFileuploadPluginUploadDirPath());
    }

    protected function getFileuploadPluginUploadDirPath(){
        return public_path().'/assets/plugins/jquery-file-upload2/server/php/files/';
    }

    protected function calcTable_lengthCookieName($cookieSlug){
        $cookieNames = $this->calcCookieName($cookieSlug);
        if(count($cookieNames) && array_key_exists("tableLength", $cookieNames))
            return $cookieNames['tableLength'];

        return '';
    }

    protected function calcCookieName($cookieSlug){
        $cookieNames = [];
        switch ($cookieSlug){
            case "location" :
                $cookieNames['tableLength'] = "location-list-table-length";
            break;
            case "area" :
                $cookieNames['tableLength'] = "area-list-table-length";
            break;
            case "staff" :
                $cookieNames['tableLength'] = "staff-list-table-length";
            break;
            case "service" :
                $cookieNames['tableLength'] = "service-list-table-length";
            break;
            case "class" :
                $cookieNames['tableLength'] = "class-list-table-length";
            break;
            case "product" :
                $cookieNames['tableLength'] = "product-list-table-length";
            break;
            case "client" :
                $cookieNames['tableLength'] = "client-list-table-length";
                //$cookieNames['recordIndex'] = "client-list-record-index";
            break;
            case "contact" :
                $cookieNames['tableLength'] = "contact-list-table-length";
            break;
            case "goal" :
                $cookieNames['tableLength'] = "goal-list-table-length";
            break;
            case "habit" :
                $cookieNames['tableLength'] = "habit-list-table-length";
            break;
            case "membership" :
                $cookieNames['tableLength'] = "membership-list-table-length";
            break;
            case "sales-tools-discount" :
                $cookieNames['tableLength'] = "sales-tools-discount-list-table-length";
            break;
            case "closed-date" :
                $cookieNames['tableLength'] = "closed-date-list-table-length";
            break;
            case "resource" :
                $cookieNames['tableLength'] = "resource-list-table-length";
            break;
            case "admin" :
                $cookieNames['tableLength'] = "admin-list-table-length";
            break;
            case "invoice" :
                $cookieNames['tableLength'] = "invoice-list-table-length";
            break;
            case "exercise" :
                $cookieNames['tableLength'] = "exercise-list-table-length";
            break;
            case "libraryPrograms" :
                $cookieNames['tableLength'] = "library-program-list-table-length";
            break;
            case "generatePrograms" :
                $cookieNames['tableLength'] = "genrate-program-list-table-length";
            break;
            case "mealplanner" :
                $cookieNames['tableLength'] = "mealplanner-list-table-length";
            break;
            case "foodplanner":
                $cookieNames['tableLength'] = "foodplanner-list-table-length";
            break;
             case "mealcategory":
                $cookieNames['tableLength'] = "mealcategory-list-table-length";
            break;
             case "videos":
                $cookieNames['tableLength'] = "videos-list-table-length";
            break;
        }
        return $cookieNames;
    }

    protected function getTableLengthFromCookie($cookieSlug){
        $length = 10;

        $cookieName = $this->calcTable_lengthCookieName($cookieSlug);
        //dd(isset($cookieName));
        if($cookieName && isset($_COOKIE[$cookieName]))
            $length = (int) $_COOKIE[$cookieName];
        
        return $length;
    }

    /*protected function callNeverEndEventRepeats(){
        $now = new Carbon();
        $monthEndDate = $now->endOfMonth()->toDateString();
        $eventRepeatRequest = new Request;
        $eventRepeatRequest['insertRepeatUpto'] = $monthEndDate;
        $this->neverEndAppointmentRepeats($eventRepeatRequest);
        $this->neverEndClassRepeats($eventRepeatRequest);
    }*/

    protected function log($data, $bulk = false){
        if($bulk){
            if(count($data)){
                $insData = [];
                $i = 0;
                foreach($data as $arr){
                    $insData[$i] = ['created_at'=>$arr['created_at'], 'updated_at'=>$arr['updated_at']];

                    if(array_key_exists('entity', $arr))
                        $insData[$i]['l_entity'] = $arr['entity'];

                    if(array_key_exists('sourceFile', $arr))
                        $insData[$i]['l_source_file'] = $arr['sourceFile'];

                    if(array_key_exists('sourceFunc', $arr))
                        $insData[$i]['l_source_func'] = $arr['sourceFunc'];

                    if(array_key_exists('action', $arr))
                        $insData[$i]['l_action'] = $arr['action'];

                    if(array_key_exists('text', $arr))
                        $insData[$i]['l_text'] = $arr['text'];

                    $i++;
                }
                Log::insert($insData);
            }
        }
        else{
            $log = new Log;
            if(array_key_exists('entity', $data))
                $log->l_entity = $data['entity'];

            if(array_key_exists('sourceFile', $data))
                $log->l_source_file = $data['sourceFile'];

            if(array_key_exists('sourceFunc', $data))
                $log->l_source_func = $data['sourceFunc'];

            if(array_key_exists('action', $data))
                $log->l_action = $data['action'];

            if(array_key_exists('text', $data))
                $log->l_text = $data['text'];
            
            $log->save();
        }
    }

    protected function setTimeZone(){
        if(Session::has('timeZone') && Session::get('timeZone') != ""){
            /*$tz = Session::get('timeZone');
            date_default_timezone_set($tz);
            config(['app.timezone' => $tz]);*/
            //env('APP_TIMEZONE', $tz);
            //DB::statement("SET time_zone='America/New_York'");
            
            //$diffTime=Carbon::now($tz)->diff(Carbon::now('Greenwich Mean Time'));
            /*$offset = timezone_offset_get( new DateTimeZone($tz), new DateTime());
            $dbTZ=sprintf( "%s%02d:%02d", ( $offset >= 0 ) ? '+' : '-', abs( $offset / 3600 ), abs( $offset % 3600 ) );*/
            /*$appTZ=new DateTimeZone($tz);
            $utcTZ=new DateTimeZone("UTC");

            $appTime=new DateTime("now", $appTZ);
            $utcTime=new DateTime("now", $utcTZ);

            $offset=timezone_offset_get( $appTZ, $utcTime);
            $dbTZ=sprintf( "%s%02d:%02d", ( $offset >= 0 ) ? '+' : '-', abs( $offset / 3600 ), abs( $offset % 3600 ) );
            
            config(['database.connections.mysql.timezone' => $dbTZ]);*/
            /*$offset = round($appTZ->getOffset($utcTime) / 3600);
            $dbTZ=sprintf( "%s%02d:%02d", ( $offset >= 0 ) ? '+' : '-', abs( $offset / 3600 ), abs( $offset % 3600 ) );
            $dbTZ= $hourOffset > 0
                            ? "+" . str_pad($hourOffset, 2, '0', STR_PAD_LEFT) . ":00"
                            : "-" . str_pad($hourOffset, 2, '0', STR_PAD_LEFT) . ":00";*/
           // echo config('database.connections.mysql.timezone');
            
        }
    }

    
    /*protected function updateThisOnlyClass($classBooking, $breakFromChain = true){
        $data = json_decode($classBooking->sec_break_recur, 1);
        $classBooking->sec_last_neverend = 0;
        $classBooking->sec_break_recur = '';
        $classBooking->save();

        //$eventParentId = $classBooking->sec_parent_id;
        if($breakFromChain)
            $this->resetEventReccur($classBooking, 'class');

        $this->updateThisCaseForClass($data['parentId'], $classBooking->sec_id, ['startDatetime'=>$data['startDatetime'], 'endDatetime'=>$data['endDatetime']]);
    }*/

    /*protected function updateThisOnlyService($serviceBooking, $breakFromChain = true){
        $data = json_decode($serviceBooking->sess_break_recur, 1);
        $serviceBooking->sess_break_recur = '';
        $serviceBooking->save();

        $this->unlinkServiceFromReccurenceChain($serviceBooking, ['parentId'=>$data['parentId'], 'startDatetime'=>$data['startDatetime'], 'endDatetime'=>$data['endDatetime'], 'breakFromChain'=>$breakFromChain]);
    }*/

    /*protected function updateThisOnlyBookings(){
        $classBookings = StaffEventClass::ofBusiness()->where('sec_break_recur', '!=', '')->get();
        if($classBookings->count()){
            foreach($classBookings as $classBooking){
                $this->updateThisOnlyClass($classBooking);
            }
        }

        $serviceBookings = StaffEventSingleService::ofBusiness()->where('sess_break_recur', '!=', '')->get();
        if($serviceBookings->count()){
            foreach($serviceBookings as $serviceBooking){
                $this->updateThisOnlyClass($updateThisOnlyService);
            }
        }
    }*/

    /*
     * Clear old parent id of bookings that was stored temporarily
     *
     */
    /*protected function clearBookingOldPar(){
        StaffEventClass::ofBusiness()->where('sec_old_parent_id', '!=', 0)->update(['sec_old_parent_id'=>0]);
        StaffEventSingleService::ofBusiness()->where('sess_old_parent_id', '!=', 0)->update(['sess_old_parent_id'=>0]);
    }*/
                                    
    /* Start: Calculate and save the epic trits */
    protected function setEpicBalance($id){
        $client=Clients::find($id);
        if($client){
            $epic_bal=$client->makeups()->sum('makeup_amount');
            $client->epic_credit_balance=$epic_bal;
            $client->save();
            return $epic_bal;
        }
        return 0;
    }
    /* End: Calculate and save the epic trits */

    /**
     * Get services id to which given client is booked
     *
     * @param int $clientId Client ID
     * @param int $skipService Service to skip
     *
     * @return array Services ID linked to client
     */
    protected function getServiceBookedToClient($clientId, $skipService = 0){
        $query = StaffEventSingleService::ofBusiness()->where('sess_client_id', $clientId)->where('sess_client_attendance', '!=', 'Did not show');
        if($skipService)
            $query->where('sess_service_id', '!=', $skipService);
        $linkedServices = $query->select('sess_service_id')->get();

        if($linkedServices->count()){
            $linkedServices = $linkedServices->pluck('sess_service_id')->toArray();
            $linkedServices = array_unique($linkedServices);
        }
        else
            $linkedServices = [];

        return $linkedServices;
    }

    /**
     * Get sales process steps number that are booked
     *
     * @param int $clientId Client ID
     * @param string $currentSalesStep Current sales step of client
     *
     * @return json Steps Booked
     */
    protected function getStepsBooked($clientId, $currentSalesStep){
        $stepsBooked = [];
        //$saleProcessBenchmarkDetails = calcSalesProcessRelatedStatus('book_benchmark');
        //if($currentSalesStep < $saleProcessBenchmarkDetails['saleProcessStepNumb']){ //If sales process is less than book benchmark
            $linkedServices = $this->getServiceBookedToClient($clientId);
            if(count($linkedServices)){
                $stepsBooked = Service::OfBusiness()
                                        ->complOnly()
                                        ->where('for_sales_process_step', '!=', 0)
                                        ->whereIn('id', $linkedServices)
                                        ->select('for_sales_process_step')
                                        ->get();
                if($stepsBooked->count())
                    $stepsBooked = $stepsBooked->pluck('for_sales_process_step')->toArray();
            }
        //}
        $stepsBooked = json_encode($stepsBooked);
        return $stepsBooked;
    }

    /* Start: Calculate age for clients. diffInYears*/
    protected function calcAge($dob){
        if($dob && $dob != '0000-00-00'){
            $currDate= Carbon::now();
            $dbDate= new Carbon($dob);
            $cage=$currDate->diffInYears($dbDate);
            if($cage >= 0)
                return $cage;
        }
        return 0;    
    } 
    /* End: Calculate age for clients. */  

    /**
     * Get preffered name of contact
     *
     * @param int $contId Contact ID
     *
     * @return string Contact Name
     */
    protected function getContactName($contId){
        $contact = Contact::withTrashed()->find($contId);
        if($contact)
            return $contact->name;
        return '';
    }

    /*protected function categoryTask($catId=0, $duedate = '', $ownerId = 0){
        if(!$duedate)
            $duedate = Carbon::now()->toDateString();

        if(!$catId){
            $commonCategory = TaskCategory::where('t_cat_user_id',0)->where('t_cat_business_id',0)->select('id')->first();
            $commonCategoryId = $commonCategory->id;

            $query = Task::with('completer','reminders')->OfTasks($duedate);

            $query->where(function($q) use($commonCategoryId){
                        $q->where('task_category','!=',$commonCategoryId)
                          ->orWhere(function($qr) use($commonCategoryId){
                                $qr->where('task_category',$commonCategoryId)->where('task_user_id',Auth::id()); 
                        });
            });

            return $query->get();
        } 
        else{
            $Category = TaskCategory::where('id',$catId)->first();

            if(($Category->t_cat_user_id == 0) && ($Category->t_cat_business_id == 0)){
                $authId = ($ownerId)?$ownerId:Auth::id();
                return Task::with('completer', 'reminders')->where('task_category',$catId)->where('task_user_id',$authId)->OfTasks($duedate)->get(); 
            }
            else
                return Task::with('completer', 'reminders')->where('task_category',$catId)->OfTasks($duedate)->get();
        }
    }*/

    /*protected function upcomingTasksTimestamp(){
        $upcomingTasksTimestamp = [];
        $tasks = $this->categoryTask();
        if($tasks->count()){
            $tasks = $tasks->where('completed_by', 0);
            if($tasks->count()){
                foreach($tasks as $task){
                    $taskDatetime = strtotime($task->task_due_date.' '.$task->task_due_time);
                    $upcomingTasksTimestamp[$taskDatetime][] = $task->id;

                    if($task->reminders->count()){    
                        $reminder = $task->reminders->first();
                        $remiderDatetime = strtotime($reminder->tr_datetime);
                        $upcomingTasksTimestamp[$remiderDatetime][] = $task->id;
                    }
                }
                ksort($upcomingTasksTimestamp);
            }
        }
        return json_encode($upcomingTasksTimestamp);
    }*/

    /**
     * set next invoice id
     *
     * @param no any input
     * 
     * @param save max+1 id from this business 
     */ 
    protected function setNextInvoiceId(){
        $salesTools = SalesToolsInvoice::where('sti_business_id',Session::get('businessId'))->first();
        if(count($salesTools)){
            if($salesTools->sti_next_invoice_id_set == 0){
                $max = Invoice::withTrashed()->where('inv_business_id',Session::get('businessId'))->max('inv_invoice_no');
                if($max){
                    $salesTools->sti_next_invoice_number = $max+1;
                    $salesTools->sti_next_invoice_id_set = 1;
                    $salesTools->save(); 
                }    
            }
        }   
    }
    

    /**
     * Create Invoice
     * @param all invoice data in array
     * @param get invoiceData array it hold dueDate, clientId, locatioId, status, productName, staffId, price, type, productId, taxType
     * @return return 0 or save data 
     */ 
    protected function autoCreateInvoice($invoiceData, $businessid = null, $source = ''){
        $isError = false;
        $purpose = 'invoice_amount';

        /* check if event invoice exist return in */
        if($invoiceData['type'] == 'class' || $invoiceData['type'] == 'service'){
            $invIds = Invoice::OfClient($invoiceData['clientId'])->whereNull('deleted_at')->pluck('inv_id')->toArray();
            $invItmes = InvoiceItems::whereIn('inp_invoice_id', $invIds)->where('inp_type', $invoiceData['type'])->where('inp_product_id', $invoiceData['productId'])->whereNull('deleted_at')->orderBy('inp_id', 'desc')->first();
            
            if($invItmes){
                $invItmes->inp_paid_using_epic_credit = isset($invoiceData['paidUsingCredit']) ? $invoiceData['paidUsingCredit'] : 0;
                $invItmes->save();

                $isError = true;
                return array('status'=>'invoice_exist', 'invoiceId'=>$invItmes->inp_invoice_id);
            }
            $purpose = $invoiceData['type'];
        }

        if(!$isError && count($invoiceData)){
            $isPayment = true;
            $isEpicCashUpdate = true;
            $businessid = $businessid ? $businessid : Session::get('businessId');

            $salestoolsinvoiceObj = SalesToolsInvoice::where('sti_business_id',$businessid)->first();
           
            if($salestoolsinvoiceObj){
                $taxLabel = 'N/A';
                $taxValue = 0;

                if($invoiceData['taxType']=='including'){
                    if(count($salestoolsinvoiceObj)){
                        $tax = MemberShipTax::where('mtax_business_id',Session::get('businessId'))->select('id','mtax_label','mtax_rate')->where('id',$salestoolsinvoiceObj->sti_override)->first();
                        if(count($tax)){
                            $taxLabel = $tax->mtax_label;
                            $taxValue = $invoiceData['price']-($invoiceData['price']/(1+($tax->mtax_rate/100)));
                        }
                    }

                    $unitPrice = $invoiceData['price'] - $taxValue;
                    $totalWithTax = $invoiceData['price'];
                    $taxtype = 'Including';
                }
                elseif($invoiceData['taxType']=='excluding'){
                    if(count($salestoolsinvoiceObj)){
                        $tax = MemberShipTax::where('mtax_business_id',Session::get('businessId'))->select('id','mtax_label','mtax_rate')->where('id',$salestoolsinvoiceObj->sti_override)->first();
                        if(count($tax)){
                            $taxLabel = $tax->mtax_label;
                            $taxValue = $invoiceData['price']*($tax->mtax_rate/100);
                        }
                    }

                    $unitPrice = $invoiceData['price'];
                    $totalWithTax = $invoiceData['price'] + $taxValue;
                    $taxtype = 'Excluding';
                }else{
                    $unitPrice = $invoiceData['price'];
                    $totalWithTax = $invoiceData['price'];
                    $taxtype = 'N/A';
                }
                
                if($invoiceData['paymentType'] == 'EPIC Credit'){
                    if(array_key_exists('from', $invoiceData)){
                        $paymentStatus = $invoiceData['status'];
                        $payAmount = $totalWithTax;
                        $remainingAmount = 0;
                        $isPayment = true;
                        $isEpicCashUpdate = false;
                    }
                    else{
                        $epicCreditAmount = Clients::where('id',$invoiceData['clientId'])->pluck('epic_credit_balance')->first();
                        if($epicCreditAmount <= 0){
                            $paymentStatus = 'Unpaid';
                            $isPayment = false;
                        }
                        elseif($totalWithTax <= $epicCreditAmount){
                            $payAmount = $totalWithTax;
                            $remainingAmount = 0;
                            $paymentStatus = 'Paid';
                            $epicCreditRemAmount = $payAmount;
                        }else{
                            $payAmount = $epicCreditAmount ;
                            $remainingAmount = $totalWithTax - $epicCreditAmount;
                            $paymentStatus = 'Unpaid';
                            $epicCreditRemAmount = $payAmount;
                        }
                    }
                } 
                elseif($invoiceData['status'] == 'Paid'){
                    $payAmount = $totalWithTax;
                    $remainingAmount = 0;
                    $paymentStatus = 'Paid';
                }
                else{
                    $paymentStatus = $invoiceData['status'];
                    $isPayment = false;
                }
                $invoiceObj = new Invoice();
                $invoiceObj->inv_invoice_date = Carbon::now()->toDateString();
                $invoiceObj->inv_due_date = $invoiceData['dueDate'];
                $invoiceObj->appointment_date_time = $invoiceData['appointment_date_time'] != null ? $invoiceData['appointment_date_time'] : null;
                $invoiceObj->inv_client_id = $invoiceData['clientId'];
                $invoiceObj->inv_business_id = $businessid;
                $invoiceObj->inv_notes = '';
                $invoiceObj->inv_ref = '';
                $invoiceObj->inv_area_id = $invoiceData['locationId'];
                $invoiceObj->inv_total = $totalWithTax;
                $invoiceObj->inv_incl_tax = $taxValue;
                $invoiceObj->inv_status = $paymentStatus;
                $invoiceObj->inv_appointment_status = '';

                $invoiceNo = $salestoolsinvoiceObj->sti_next_invoice_number;
                $invoiceObj->inv_invoice_no = $invoiceNo;
                if($invoiceObj->save()){
                    $invoiceNo++;
                    $salestoolsinvoiceObj->sti_next_invoice_number = $invoiceNo;
                    $salestoolsinvoiceObj->save();
                    $lastInsertId = $invoiceObj->inv_id;
                    $timestamp = Carbon::now();
                    $insertData = array('inp_invoice_id' => $lastInsertId,'inp_item_desc' =>$invoiceData['productName'] ,'inp_staff_id'=>$invoiceData['staffId'],'inp_price'=>$unitPrice,'inp_quantity'=>1,'inp_tax'=>$taxLabel ,'inp_total'=>$totalWithTax,'inp_type'=>$invoiceData['type'],'inp_product_id'=>$invoiceData['productId'],'created_at' => $timestamp, 'updated_at' => $timestamp,'inp_tax_type'=>$taxtype, 'inp_paid_using_epic_credit' => isset($invoiceData['paidUsingCredit']) ? $invoiceData['paidUsingCredit'] : 0); 
                    if(count($insertData)){
                        if(InvoiceItems::insert($insertData)){
                            if($isPayment){
                                $paymentData['totalAmount'] = $totalWithTax;
                                $paymentData['payAmount'] = $payAmount;
                                $paymentData['outStandingAmount'] = $remainingAmount;
                                $paymentData['paymentType'] = $invoiceData['paymentType'];
                                $isPaymentComplete = $this->autoInvoicePayment($lastInsertId, $paymentData);
                                if($isPaymentComplete && $invoiceData['paymentType'] == 'EPIC Credit' && $isEpicCashUpdate){
                                    $eventLink = '';
                                    if(array_key_exists('eventLink',$invoiceData))
                                        $eventLink = $invoiceData['eventLink']; 
                                    $this->updateEpicCredit($invoiceData['clientId'], $epicCreditRemAmount, $purpose, $eventLink, 0, $source);
                                }
                            }
                            return array('status'=>'new_invoice', 'invoiceId'=>$lastInsertId);
                        }
                    }    
                }
            }
        }    
        return array('status'=>'error', 'invoiceId'=>0);
    }
    protected function autoInvoicePayment($lastInsertId, $paymentData){
        if($lastInsertId && count($paymentData)){
            $paymentObj = new Payment;
            $paymentObj->pay_invoice_id = $lastInsertId;
            $paymentObj->pay_total_invoice_amount = $paymentData['totalAmount'];
            $paymentObj->pay_amount = $paymentData['payAmount'];
            $paymentObj->pay_outstanding_amount = $paymentData['outStandingAmount'];
            $paymentObj->pay_confirm_date = Carbon::now()->toDateString();
            $paymentObj->pay_processed_by = Auth::id() ? Auth::id() : 11;
            $paymentObj->pay_ref = '';
            $paymentObj->pay_type = $paymentData['paymentType'];
            if($paymentObj->save()){
                return true;
            }
        }
        return false;
    }
    protected function updateEpicCredit($clientId, $amount, $purpose, $extraInfo = "", $notesId=0, $source = null){
        $clientDetails = Clients::find($clientId);
        $clientName = (isset($clientDetails->first_name) ? $clientDetails->first_name: '').' '.(isset($clientDetails->last_name) ? $clientDetails->last_name: '');
        if($clientId){
            $makeup = new Makeup;
            $makeup->makeup_client_id = $clientId;
            $makeup->makeup_amount= -($amount);
            $makeup->makeup_purpose = $purpose;
            $makeup->makeup_user_id = $source == 'epicfitStudios' ? $clientDetails->id : $makeup->UserInformation['id']  ; 
            $makeup->makeup_user_name = $source == 'epicfitStudios' ? $clientName : $makeup->UserInformation['name'];
            $makeup->makeup_extra = $extraInfo;
            if( $notesId )
                $makeup->makeup_notes_id = $notesId;
            
            if($makeup->save())
                $this->setEpicBalance($clientId);
        }
    }  

    protected function salesProcessEventCompleted($event, $clientId/*, $prevAttendance*/,$salesProcessQuery = false){
        $client = Clients::find($clientId);
        if(isClientInSalesProcess($client->consultation_date)){
            $salesProcessCurrentDetails = calcSalesProcessRelatedStatus($event->sess_sale_process_status);
            if(array_key_exists('clientStatus', $salesProcessCurrentDetails)){
                $salesProcessCurrentDetails = calcSalesProcessRelatedStatus($salesProcessCurrentDetails['saleProcessStepNumb']-1);
                $event->sess_sale_process_status = $salesProcessCurrentDetails['salesProcessType'];
            }
            $saleProcessNextStepDetails = calcSalesProcessRelatedStatus($salesProcessCurrentDetails['saleProcessStepNumb']+1);
            if(!array_key_exists('dependantStep', $saleProcessNextStepDetails) || $this->isDependantStepComp($saleProcessNextStepDetails['dependantStep'], $clientId, $client->SaleProcessEnabledSteps) ||  $salesProcessQuery){

                $event->sess_sale_process_status = $saleProcessNextStepDetails['salesProcessType'];
                $event->save();

                if($this->isStepEnabled($salesProcessCurrentDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps) && $this->isStepEnabled($saleProcessNextStepDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps)){
                    
                    $this->saveSalesProgress(['clientId'=>$clientId, 'stepNumb'=>$saleProcessNextStepDetails['saleProcessStepNumb']]);

                    if(!$this->checkFutureSalesProgress($event->sess_sale_process_status, $clientId, $client->SaleProcessEnabledAttendSteps)){
                        $nextStep = $this->getNextAttendStep($saleProcessNextStepDetails['saleProcessStepNumb'], $client);
                        $nextStepDetails = calcSalesProcessRelatedStatus($nextStep);
                        $client->account_status = preventActiveContraOverwrite($client->account_status, $nextStepDetails['clientPrevStatus']);

                        //$client->account_status = $saleProcessNextStepDetails['clientStatus'];
                    }
                    if($salesProcessCurrentDetails['salesProcessType'] == 'book_consult'){
                        $client->consultation_date = $event->sess_date;
                    }
                    $client->save();

                    $this->linkCompletedBooking($salesProcessCurrentDetails['salesProcessType'], $client);
                    /*if($salesProcessCurrentDetails['salesProcessType'] == 'book_consult'){
                        $bookBenchmarkAttended = StaffEventSingleService::OfClientAndInSalesProcessAndClientAttendanceIs(['clientId' => $clientId, 'saleProcessStatus' => ['book_benchmark'], 'attendance' => 'Attended'])->first();
                        if($bookBenchmarkAttended)
                            $this->salesProcessEventCompleted($bookBenchmarkAttended, $clientId);
                    }
                    else if($salesProcessCurrentDetails['salesProcessType'] == 'book_benchmark')
                        //$this->manageTeamSalesProcess($client, 0, '', 0, true);
                        $this->manageSessionSalesProcess($client);*/
                }
            }
        }
        return;

        $salesProcessGivenStatus = $event->sess_sale_process_status;

        /*if($salesProcessGivenStatus == 'book_team'){
            $event->sess_sale_process_status = 'teamed';
            $event->save();

            $teamedCount = StaffEventSingleService::teamedCount($clientId);
            if($teamedCount == 3){
                //If all three book team has been marked as attended then move sales process to next step
                $saleProcessTeamedDetails = calcSalesProcessRelatedStatus('teamed');
                $client = Clients::find($clientId);
                $client->account_status = $saleProcessTeamedDetails['clientStatus'];
                $clientOldSaleProcessStep = $clients->sale_process_step;
                $client->sale_process_step = $saleProcessTeamedDetails['saleProcessStepNumb'];
                $client->save();

                $salesProcessHistory = ['clientId'=>$clientId, 'eventId'=>$event->sess_id, 'toType'=>$saleProcessTeamedDetails['salesProcessType'], 'toStep'=>$saleProcessTeamedDetails['saleProcessStepNumb'], 'fromStep'=>$clientOldSaleProcessStep];
                $this->saveSalesProcess($salesProcessHistory);
            }
            else{
                $teamBookedCount = StaffEventSingleService::OfClientAndInSalesProcess($clientId, ['book_team'])->count();
                if(($teamBookedCount + $teamedCount) < 3){
                    //If sales process has been modified by changing client status
                    $client = Clients::find($clientId);
                    $clientOldSaleProcessStep = $client->sale_process_step;
                    if($prevAttendance == 'Did not show'){
                        //If previous attendance was 'Did not show' then move a step forward in sales process
                        $client->sale_process_step++;
                    }
                    $saleProcessBookTeamDetails = calcSalesProcessRelatedStatus('book_team');
                    if($client->sale_process_step == $saleProcessBookTeamDetails['saleProcessStepNumb']+2){
                        //If 'Book Team 3' was booked then mark sales process as 'teamed'
                        $saleProcessTeamedDetails = calcSalesProcessRelatedStatus('teamed');
                        $client->account_status = $saleProcessTeamedDetails['clientStatus'];
                        $client->sale_process_step = $saleProcessTeamedDetails['saleProcessStepNumb'];
                    }
                    $client->save();

                    if($clientOldSaleProcessStep != $client->sale_process_step){
                        $clientUpdatedSalesProcess = calcSalesProcessRelatedStatus($client->sale_process_step);
                        $salesProcessHistory = ['clientId'=>$clientId, 'eventId'=>$event->sess_id, 'toType'=>$clientUpdatedSalesProcess['salesProcessType'], 'toStep'=>$clientUpdatedSalesProcess['saleProcessStepNumb'], 'fromStep'=>$clientOldSaleProcessStep];
                        $this->saveSalesProcess($salesProcessHistory);
                    }
                }
            }

            //$this->manageTeamSalesProcessOfClient($clientId, $event->sess_id);
        }
        else*/ 

        if($salesProcessGivenStatus == 'book_consult' || $salesProcessGivenStatus == 'book_benchmark'){
            $client = Clients::find($clientId);
            if(/*($salesProcessGivenStatus == 'book_consult' && $client->consultation_date == null) || isClientInSalesProcess($client->consultation_date)*/$this->isClientInSalesProcess($salesProcessGivenStatus, $client->consultation_date)){
                $salesProcessCurrentDetails = calcSalesProcessRelatedStatus($salesProcessGivenStatus);
                $saleProcessNextStepDetails = calcSalesProcessRelatedStatus($salesProcessCurrentDetails['saleProcessStepNumb']+1);

                $event->sess_sale_process_status = $saleProcessNextStepDetails['salesProcessType'];
                $event->save();

                $clientOldSaleProcessStep = $client->sale_process_step;
                $salesProcessHistory = ['clientId'=>$clientId, 'eventId'=>$event->sess_id, 'fromStep'=>$clientOldSaleProcessStep];

                if($salesProcessGivenStatus == 'book_benchmark'){
                    /*$totalBookings = StaffEventClass::teamBookings($clientId);
                    if(count($totalBookings)){
                        //If there are team bookings
                        $bookings = array_count_values($totalBookings);
                        if(array_key_exists("Attended",$bookings) && $bookings['Attended'] >= 3){
                            //If teamed event are at least 3 and sale process is below teamed step then upgrade sales process to teamed
                            $saleProcessTeamedDetails = calcSalesProcessRelatedStatus('teamed');
                            $client->account_status = $saleProcessTeamedDetails['clientStatus'];
                            $client->sale_process_step = $saleProcessTeamedDetails['saleProcessStepNumb'];
                            $client->save();

                            //$salesProcessHistory = ['clientId'=>$clientId, 'eventId'=>$eventId, 'toType'=>$saleProcessTeamedDetails['salesProcessType'], 'toStep'=>$saleProcessTeamedDetails['saleProcessStepNumb'], 'fromStep'=>$clientOldSaleProcessStep];
                            $salesProcessHistory['toType'] = $saleProcessTeamedDetails['salesProcessType'];
                            $salesProcessHistory['toStep'] = $saleProcessTeamedDetails['saleProcessStepNumb'];
                            $this->saveSalesProcess($salesProcessHistory);
                            return;
                        }
                        else if(array_key_exists("Booked",$bookings) && $bookings['Booked']){
                            $bookTeam = count($totalBookings);
                            if($bookTeam > 3)
                                $bookTeam = 3;

                            $saleProcessTeamedDetails = calcSalesProcessRelatedStatus('teamed');
                            $saleProcessBookTeamPrevStepDetails = calcSalesProcessRelatedStatus($saleProcessTeamedDetails['clientPrevStatus']);
                            $client->account_status = $saleProcessTeamedDetails['clientPrevStatus'];
                            $client->sale_process_step = $saleProcessBookTeamPrevStepDetails['saleProcessStepNumb']+$bookTeam;
                            $client->save();

                            $clientUpdatedSalesProcess = calcSalesProcessRelatedStatus($client->sale_process_step);
                            $salesProcessHistory['toType'] = $clientUpdatedSalesProcess['salesProcessType'];
                            $salesProcessHistory['toStep'] = $clientUpdatedSalesProcess['saleProcessStepNumb'];
                            $this->saveSalesProcess($salesProcessHistory);
                            return;
                        }
                    }*/
                    /*$data = $this->manageTeamSalesProcess($client);
                    if(count($data)){
                        $salesProcessHistory['toType'] = $data['toType'];
                        $salesProcessHistory['toStep'] = $data['toStep'];
                        $this->saveSalesProcess($salesProcessHistory);
                        return;
                    }*/
                    $data = $this->manageTeamSalesProcess($client);
                    if(count($data)){
                        $salesProcessHistory['toType'] = $data['toType'];
                        $salesProcessHistory['toStep'] = $data['toStep'];
                        $salesProcessHistory['reason'] = 'Benchmark service booking marked as attended';
                        $this->saveSalesProcess($salesProcessHistory);
                        return;
                    }
                }
                
                $client->account_status = $saleProcessNextStepDetails['clientStatus'];
                $client->sale_process_step = $saleProcessNextStepDetails['saleProcessStepNumb'];
                if($salesProcessGivenStatus == 'book_consult')
                    $client->consultation_date = $event->sess_date;
                $client->save();

                $salesProcessHistory['toType'] = $saleProcessNextStepDetails['salesProcessType'];
                $salesProcessHistory['toStep'] = $saleProcessNextStepDetails['saleProcessStepNumb'];
                $salesProcessHistory['reason'] = 'Service booking marked as attended';
                $this->saveSalesProcess($salesProcessHistory);
            }
        }
    }

    protected function linkCompletedBooking($curStep, $client){
        if(is_int($client))
            $clientId = $client;
        else
            $clientId = $client->id;

        if($curStep == 'contact'){
            $bookConsultAttended = StaffEventSingleService::OfClientAndInSalesProcessAndClientAttendanceIs(['clientId' => $clientId, 'saleProcessStatus' => ['book_consult'], 'attendance' => 'Attended'])->first();
            if($bookConsultAttended)
                $this->salesProcessEventCompleted($bookConsultAttended, $clientId);
        }
        if($curStep == 'book_consult' || $curStep == 'consulted'){
            $bookBenchmarkAttended = StaffEventSingleService::OfClientAndInSalesProcessAndClientAttendanceIs(['clientId' => $clientId, 'saleProcessStatus' => ['book_benchmark'], 'attendance' => 'Attended'])->first();
            if($bookBenchmarkAttended)
                $this->salesProcessEventCompleted($bookBenchmarkAttended, $clientId);
        }
        else if($curStep == 'book_benchmark' || $curStep == 'benchmarked'){
            //$this->manageTeamSalesProcess($client, 0, '', 0, true);
            if(is_int($client))
                $client = Clients::find($client);
            $this->manageSessionSalesProcess($client);
        }
    }


    /**
     * Get all product category for form
     * @param 
     * @return product category
    **/
    protected function getProductCat(){
        $categories= Category::select('cat_id','cat_name','cat_parent_id')->where('cat_business_id',Session::get('businessId'))->get();
        $return = [];
        $pro_cat = [];
        $parentCat=[];
        if($categories->count()){
            foreach($categories as $category){
                if($category->cat_parent_id == 0){
                    /*$pro_cat[$category->cat_id] = $category->cat_name;*/
                    $parentCat[$category->cat_id] = ucfirst($category->cat_name);
                }
                else{
                    $catName = $categories->where('cat_id',$category->cat_parent_id)->pluck('cat_name')->first();
                    $pro_cat[$category->cat_id] = ucfirst($catName.' -> '.$category->cat_name);
                }
            }
            asort($parentCat);
            asort($pro_cat);
        }
        return array('pro_cat' => $pro_cat,'parentCat' => $parentCat);
    }

    /**
     * Get all product size for form
     * @param void
     * @return product size
    **/
    protected function getProductSize(){
        $proSize=[];
        $proSizes = ProductSize::select('id','name','gender')->where('business_id',Session::get('businessId'))->get();
        if($proSizes->count()){
            foreach ($proSizes->sortBy('name') as $size) {
                $proSize[$size->id] = $size->name.' ('.$size->gender.')';
            }
        }
        return $proSize;
    }


    /**
     * Get all Payment type 
     * @param void
     * @return Payment type 
    **/
    protected function getPaymentType(){
        $data = SalesToolsInvoicePaymentTypes::where('stipt_business_id',Session::get('businessId'))->select('stipt_id','stipt_business_id','stipt_payment_types')->get();
        if(count($data)){
            return $data;
        }else{
            $data = SalesToolsInvoicePaymentTypes::where('stipt_business_id','0')->select('stipt_id','stipt_business_id','stipt_payment_types')->get();
            return $data;
        }
    }

 
}