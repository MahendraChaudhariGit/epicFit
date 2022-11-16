<?php

namespace App\Http\Traits\Result;

use Illuminate\Http\Request;
use DB;
use Auth;
use File;
use Session;
use App\StaffEventSingleService;
use App\Service;
use Carbon\Carbon;
use App\SalesToolsInvoice;
use App\MemberShipTax;
use App\Invoice;
use App\InvoiceItems;
use App\Payment;
use App\Makeup;
use App\Clients;

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
            case "location":
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
		        break;
            case "user":
                $model = 'App\User';
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
            case "staff":
                $model = 'App\Staff';
                $emailCol = 'email';
                $idCol = 'id';
                break;
            default:
                return true;
        }

        if(array_key_exists('id', $data) && $data['id'])
            $emailCount = $model::where('business_id', Auth::user()->business_id)->where($emailCol, $data['email'])->where($idCol, '<>', $data['id'])->count();
        else
            $emailCount = $model::where('business_id', Auth::user()->business_id)->where($emailCol, $data['email'])->count();

        if($emailCount > 0)
            return false;
    
        return true;
    }

    protected function setWorkingHours(Request $request, $data){ 
        if($data['mode'] == 'edit')
            DB::table('hours')->where('hr_entity_type', $data['entityType'])->where('hr_entity_id', $data['entityId'])->delete();

        $input = $request->all();
        ksort($input);

        $monday_start = $monday_end = $tuesday_start = $tuesday_end = $wednesday_start = $wednesday_end = $thursday_start = $thursday_end = $friday_start = $friday_end = $saturday_start = $saturday_end = $sunday_start = $sunday_end = [];
        if($request->exists('monday')){
            foreach($input as $key => $value){
                if(strpos($key, 'monday_start') !== false)
                    $monday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'monday_end') !== false)
                    $monday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('tuesday')){
            foreach($input as $key => $value){
                if(strpos($key, 'tuesday_start') !== false)
                    $tuesday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'tuesday_end') !== false)
                    $tuesday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('wednesday')){
            foreach($input as $key => $value){
                if(strpos($key, 'wednesday_start') !== false)
                    $wednesday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'wednesday_end') !== false)
                    $wednesday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('thursday')){
            foreach($input as $key => $value){
                if(strpos($key, 'thursday_start') !== false)
                    $thursday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'thursday_end') !== false)
                    $thursday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('friday')){
            foreach($input as $key => $value){
                if(strpos($key, 'friday_start') !== false)
                    $friday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'friday_end') !== false)
                    $friday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('saturday')){
            foreach($input as $key => $value){
                if(strpos($key, 'saturday_start') !== false)
                    $saturday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'saturday_end') !== false)
                    $saturday_end[] = timeStringToDbTime($value);
            }   
        }
        if($request->exists('sunday')){
            foreach($input as $key => $value){
                if(strpos($key, 'sunday_start') !== false)
                    $sunday_start[] = timeStringToDbTime($value);
                
                else if(strpos($key, 'sunday_end') !== false)
                    $sunday_end[] = timeStringToDbTime($value);
            }   
        }

        $insertData = $returnData = array();
        if(count($monday_start)){
            for($i=0; $i<count($monday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i], 'hr_entity_type' => $data['entityType']);
                $returnData[] = array('hr_day' => 'Monday', 'hr_start_time' => $monday_start[$i], 'hr_end_time' => $monday_end[$i]);
            }
        }
        if(count($tuesday_start)){
            for($i=0; $i<count($tuesday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i], 'hr_entity_type' => $data['entityType']);
                $returnData[] = array('hr_day' => 'Tuesday', 'hr_start_time' => $tuesday_start[$i], 'hr_end_time' => $tuesday_end[$i]);
            }
        }
        if(count($wednesday_start)){
            for($i=0; $i<count($wednesday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i], 'hr_entity_type' => $data['entityType']);
                $returnData[] = array('hr_day' => 'Wednesday', 'hr_start_time' => $wednesday_start[$i], 'hr_end_time' => $wednesday_end[$i]);
            }
        }
        if(count($thursday_start)){
            for($i=0; $i<count($thursday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i], 'hr_entity_type' => $data['entityType']);
                $returnData[] = array('hr_day' => 'Thursday', 'hr_start_time' => $thursday_start[$i], 'hr_end_time' => $thursday_end[$i]);
            }
        }
        if(count($friday_start)){
            for($i=0; $i<count($friday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i], 'hr_entity_type' => $data['entityType']);
                $returnData[] = array('hr_day' => 'Friday', 'hr_start_time' => $friday_start[$i], 'hr_end_time' => $friday_end[$i]);
            }
        }
        if(count($saturday_start)){
            for($i=0; $i<count($saturday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i], 'hr_entity_type' => $data['entityType']);
                $returnData[] = array('hr_day' => 'Saturday', 'hr_start_time' => $saturday_start[$i], 'hr_end_time' => $saturday_end[$i]);
            }
        }
        if(count($sunday_start)){
            for($i=0; $i<count($sunday_start); $i++){
                $insertData[] = array('hr_entity_id' => $data['entityId'], 'hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i], 'hr_entity_type' => $data['entityType']);
                $returnData[] = array('hr_day' => 'Sunday', 'hr_start_time' => $sunday_start[$i], 'hr_end_time' => $sunday_end[$i]);
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

    protected function emptyFileuploadPluginUploadDir(){
        File::cleanDirectory($this->getFileuploadPluginUploadDirPath());
    }

    protected function getFileuploadPluginUploadDirPath(){
        return public_path().'/assets/plugins/jquery-file-upload2/server/php/files/';
    }

    protected function callNeverEndEventRepeats(){
        $now = new Carbon();
        $monthEndDate = $now->endOfMonth()->toDateString();
        $eventRepeatRequest = new Request;
        $eventRepeatRequest['insertRepeatUpto'] = $monthEndDate;
        //$this->neverEndAppointmentRepeats($eventRepeatRequest);
        $this->neverEndClassRepeats($eventRepeatRequest);
    }

    /**
     * Get sales process steps number that are booked
     * @param int $clientId Client ID
     * @param string $currentSalesStep Current sales step of client
     * @return json Steps Booked
     */
    protected function getStepsBooked($clientId, $currentSalesStep){
        $stepsBooked = [];
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
        $stepsBooked = json_encode($stepsBooked);
        return $stepsBooked;
    }


    /**
     * Get services id to which given client is booked
     * @param int $clientId Client ID
     * @param int $skipService Service to skip
     * @return array Services ID linked to client
     */
    protected function getServiceBookedToClient($clientId, $skipService = 0){
        $query = StaffEventSingleService::ofBusiness()->where('sess_client_id', $clientId);
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
     * Calculate age
     * @param dob
     * @return age
    **/
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

    /**
     * Get Client status for backend
     * @param status, reverse
     * @return status
    **/
    protected function getStatusForbackend($status, $reverse = false) {
        $statuses = array('active' => 'active', 'inactive' => 'inactive', 'pending' => 'pending', 'lead' => 'Lead', 'pre-consultation' => 'Pre-Consultation', 'pre-benchmarking' => 'Pre-Benchmarking', 'pre-training' => 'Pre-Training', 'on-hold' => 'On Hold', 'active-lead' => 'Active Lead', 'inactive-lead' => 'Inactive Lead');

        if ($reverse)
            return array_search($status, $statuses);
        else
            return $statuses[$status];
    }

    /** 
     * Auto create invoice
     * @param invoice data
     * @return last inserted id
    **/
    protected function autoCreateInvoice($invoiceData){
        if(count($invoiceData)){
            $isPayment = true;
            $isEpicCashUpdate = true;
            $businessid = $invoiceData['businessId'];
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

                $invoiceObj = new Invoice;
                $invoiceObj->inv_invoice_date = Carbon::now()->toDateString();
                $invoiceObj->inv_due_date = $invoiceData['dueDate'];
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
                    $insertData = array('inp_invoice_id' => $lastInsertId,'inp_item_desc' =>$invoiceData['productName'] ,'inp_staff_id'=>$invoiceData['staffId'],'inp_price'=>$unitPrice,'inp_quantity'=>1,'inp_tax'=>$taxLabel ,'inp_total'=>$totalWithTax,'inp_type'=>$invoiceData['type'],'inp_product_id'=>$invoiceData['productId'],'created_at' => $timestamp, 'updated_at' => $timestamp,'inp_tax_type'=>$taxtype); 
                    if(count($insertData)){
                        if(InvoiceItems::insert($insertData)){
                            if($isPayment){
                                $paymentData['totalAmount'] = $totalWithTax;
                                $paymentData['payAmount'] = $payAmount;
                                $paymentData['outStandingAmount'] = $remainingAmount;
                                $paymentData['paymentType'] = $invoiceData['paymentType'];
                                $isPaymentComplete = $this->autoInvoicePayment($lastInsertId, $paymentData);
                                if($isPaymentComplete && $invoiceData['paymentType'] == 'EPIC Credit' && $isEpicCashUpdate)
                                    $this->updateEpicCredit($invoiceData['clientId'], $epicCreditRemAmount, 'invoice_amount');
                            }
                            return $lastInsertId;
                        }
                    }    
                }
            }
        }    
        return 0;
    }

    /** 
     * Auto invoice payment
     * @param lastInsertId, payment data
     * @return true/false
    **/
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

    /** 
     * update epic credit
     * @param clientId, amount and purpose
     * @return void
    **/
    protected function updateEpicCredit($clientId, $amount, $purpose){
        if($clientId){
            $makeup=new Makeup;
            $makeup->makeup_client_id = $clientId;
            $makeup->makeup_amount= -($amount);
            $makeup->makeup_purpose=$purpose;
            $makeup->makeup_user_id = $makeup->UserInformation['id']; 
            $makeup->makeup_user_name = $makeup->UserInformation['name'];
            if($makeup->save())
                $this->setEpicBalance($clientId);
        }
    } 


    /** 
     * set epic balance of client
     * @param client id
     * @return current epic balance
    **/
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
}