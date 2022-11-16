<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\User;
use App\Clients;
use App\Parq;
use Carbon\Carbon;
use App\StaffEventSingleService;
use App\StaffEventClass;
use App\Task;
use App\Product;
use App\Category;
use App\ClientNote;
use App\ClientMember;
use App\Makeup;
use App\GoalBuddy;
use App\GoalBuddyHabit;
use App\GoalBuddyMilestones;
use App\GoalBuddyTask;
use App\GoalBuddyUpdate;

use App\Http\Traits\ClientTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\ClosedDateTrait;
use App\Http\Traits\StaffEventClassTrait;
use DB;
use Auth;
use App;

class TestingController extends Controller {
	use ClientTrait, StaffEventsTrait, HelperTrait, ClosedDateTrait, StaffEventClassTrait;
    
	/**
	 * Filter plan from ab_client_plan table
	 * @param filter value
	 * @return all plan data 
	**/
    public function index($id, Request $request) {
    	if($request->has('setage') && $request->setage == 'yes'){
    		$clients = Clients::where('business_id', Session::get('businessId'))->get();
    		$parqData = [];
    		$upParqId = [];
    		$totalClient = $clients->count();
    		$i = 0;
    		if($totalClient){
	    		foreach ($clients as $client) {
	    			$parqData['dob'] = $client->birthday;
	    			$parqData['age'] = $this->calcClientAge($client->birthday);
	    			$parqData['updated_at'] = Carbon::now();
	    			if($client->parq->update($parqData)){
	    				$upParqId[] = $client->parq->id;
	    				$i++;
	    			}
	    		}
	    	}
	    	if($i == $totalClient && $i != 0){
	    		echo "Updated parq table and id is<br>";
	    		print_r($upParqId);
	    	}
	    	else
	    		echo "some Database error.";

    	}
    	else{
    		$client = Clients::findOrFail($id);
    		$riskfactor = $client->RiskFactorr;
    		dd($riskfactor);
    	}
	}

	/**
	 * Calculate age for client
	 * @param dob
	 * @param age
	**/
    protected function calcClientAge($dob){
        if($dob && $dob != '0000-00-00'){
            $currDate= Carbon::now();
            $dbDate= new Carbon($dob);
            $cage=$currDate->diffInYears($dbDate);
            if($cage >= 0)
                return $cage;
        }
        return 0;    
    } 
    
	
	public function staffPaymentHourly(){
		$endDate = Carbon::now();
		$this->calcStaffPaymentHourly($id,'',$endDate);
	}

	/**
	 * Insert all event staff payment through API
	 * @param type(service/class)
	 * @return meaagag
	**/
	public function staffPaymentEvent(Request $request){
		$isError = true;
		if($request->has('type'))
			$type = $request->type;
		else
			abort(404);
		if($type == 'service'){
			$eventService = StaffEventSingleService::where('sess_business_id', Session::get('businessId'))->get();
			if($eventService->count()){
				foreach ($eventService as $service) {
					if($service->sess_payment == 0 || $service->sess_payment == '')
						if($this->calcStaffEventPayment('service', $service->sess_id, $service->sess_client_attendance))
							$isError = false;
					//break;
				}
			}
		}
		else if($type == 'class'){
			$eventClass = StaffEventClass::where('sec_business_id', Session::get('businessId'))->get();
			if($eventClass->count()){
				foreach ($eventClass as $cls) {
					if($cls->sec_payment == 0 || $cls->sec_payment == '')
						if($this->calcStaffEventPayment('class', $cls->sec_id))
							$isError = false;
					//break;
				}
			}
		}
		if(!$isError)
            return json_encode(['code' => '200', 'message' => 'All event updated.']);
        else
            return json_encode(['code' => '401', 'message' => 'No any event for update']);
	}


	/**
	 * Insert client birthday event(reminder)
	 * @param 
	 * @return 
	**/
	public function setClientBirthDay(){
		$isError = true;
		set_time_limit(0);
		$businessId = Session::get('businessId');
		$clients = Clients::where('business_id', $businessId)->where('birthday','<>', '0000-00-00')->get();
	
		if($clients->count()){
			foreach ($clients as $client) {
				if($client->birthday != ''){
					$taskColect = Task::where('task_client_id', $client->id)->where('task_type','birthday')->first();
					if(!count($taskColect)){
						$currentYear = Carbon::now()->year;
						$dbDate = Carbon::parse($client->birthday);
						$date = $currentYear.'-'.$dbDate->month.'-'.$dbDate->day;
						
						$taskName = ucwords($client->firstname.' '.$client->lastname).' Birthday';
						$this->setTaskReminder($date, ['taskName'=>$taskName,'taskDueTime'=>'09:00:00','taskNote'=>'','remindBeforHour'=>1,'clientId'=>$client->id]);
						$isError = false;
					}
				}
			}
		}
		else{
			return json_encode(['code'=>501,'message'=>'Client not found']);
		}

		if(!$isError)
			return json_encode(['code'=>200,'message'=>'update successfully']);
		else
			return json_encode(['code'=>501,'message'=>'no any update available']);
		
	}


	/**
	 * test membership renewal
	 * @param 
	 * @return 
	**/
	public function checkMembership(Request $request){
		return;
		 $membs = DB::select("select orderedMemb.* from (select memb.id, memb.cm_client_id, memb.cm_status from client_membership as memb inner join clients on clients.id = cm_client_id where business_id = ".Session::get('businessId')." and memb.cm_client_id = ".$request->id." and (cm_end_date < now() or cm_due_date < now()) and cm_status != 'Next' and memb.deleted_at is null and clients.deleted_at is null order by memb.created_at desc LIMIT 18446744073709551615) as orderedMemb group by orderedMemb.cm_client_id");
                $ids = [];
                if(count($membs)){
                    foreach($membs as $memb){
                        if($memb->cm_status != 'Removed')
                            $ids[] = $memb->id;
                    }

                    $membs = ClientMember::find($ids);
                    foreach($membs as $memb){
                        $this->manageClientMemb($memb);
                    }
                }
	}


	/**
	 * set product slug
	 * @param
	 * @return
	**/
	public function setProductSlug(){
		$isError = true;
		$products = Product::where('business_id', Session::get('businessId'))->get();
		if($products->count()){
			foreach ($products as $product) {
				if($product->pro_slug == ""){
					$product->update(['pro_slug'=>$this->makeSlug($product->name)]);
					$isError = false;
				}
			}
		}
		if($isError)
			return json_encode(['code'=>501,'message'=>'No update']);
		else
			return json_encode(['code'=>200,'message'=>'update']);
	}


	/**
     * Create slug for product name
     * @param text
     * @return slug
    **/
    protected function makeSlug($text){
        $text = str_slug($text,'-');
        
        if (empty($text)) {
            $timestamp=time();
            return 'na-'.$timestamp;
        }
        if(is_numeric($text)){
            $text = 'na-'.$text;
        }
        $check=Product::where('business_id',Session::get('businessId'))->where('pro_slug',$text)->exists();
        if($check){
            $text= $text.'-'.time();
        }
        
      return $text;
    }



	/**
	 * set category slug
	 * @param
	 * @return
	**/
	public function setCategorySlug(){
		$isError = true;
		$category = Category::where('cat_business_id', Session::get('businessId'))->get();
		if($category->count()){
			foreach ($category as $cat) {
				if($cat->cat_slug == ""){
					$cat->update(['cat_slug'=>$this->makeCatSlug($cat->cat_name)]);
					$isError = false;
				}
			}
		}
		if($isError)
			return json_encode(['code'=>501,'message'=>'No update']);
		else
			return json_encode(['code'=>200,'message'=>'update']);
	}


	/**
     * Create slug for category
     * @param text
     * @return slug
    **/
    protected function makeCatSlug($text){
        $text = str_slug($text,'-');
        
        if (empty($text)) {
            $timestamp=time();
            return 'na-'.$timestamp;
        }
        if(is_numeric($text)){
            $text = 'na-'.$text;
        }
        $check=Category::where('cat_business_id',Session::get('businessId'))->where('cat_slug',$text)->exists();
        if($check){
            $text= $text.'-'.time();
        }
        
      return $text;
    }


    /**
     * Copy Extra Data From ClientNotes To Makeup table
     * @param text
     * @return slug
    **/
    public function copyExtraDataFromClientNotesToMakeup(){
    	$isError = true;
    	$where = array('cn_user_id'=> Auth::id(),'cn_type'=>'makeup');
    	$notes = ClientNote::where($where)->get();
    	$makeupData = array();
    	if($notes->count()){
	    	foreach ($notes as $note) {
	    		if($note->cn_extra != ''){
	    			$extra = explode('>', $note->cn_extra);
	    			if(count($extra) > 1){
		    			$date = substr($extra[1],0,-3);
		    			$newStr = substr($extra[0],0,-1);
		    			$clsses = explode('/', $newStr);
		    			$classData = $clsses[count($clsses)-1];
		    			$makeupData[$note->cn_id] = array('date'=>$date,'class'=>$classData);
		    		}
	    		}
	    	}
    	}
    	/**/
    	$extraDatacheck = array();
    	if(count($makeupData)){
    		$notesIds = array_keys($makeupData);
    		$makeups = Makeup::whereIn('makeup_notes_id', $notesIds)->where('makeup_purpose','class')->get();
    		if(count($makeups)){
    			foreach ($makeups as $makeup) {
    				if(strpos( $makeupData[$makeup->makeup_notes_id]['class'], 'calendar-new?mevid' ) !== false){
    					$link = $makeupData[$makeup->makeup_notes_id]['class'];
    				}
    				else{
    					$link = 'calendar-new?mevid='.$makeupData[$makeup->makeup_notes_id]['class'].'&mevtype=class';
    				}

    				$extraData = 'EPIC Credit added for <a href="'.url('dashboard/'.$link).'">'.$makeupData[$makeup->makeup_notes_id]['date'].'</a> class';

    				$makeup->makeup_extra = $extraData;
    				$makeup->save();
    				$isError = false;
    			}
    		}
    	}

    	if(!$isError)
    		return json_encode(['code'=>'200','message'=>'Success.']);
    	else
    		return json_encode(['code'=>'500','message'=>'Something went wrong.']);
    }


    /**
     * Copy Extra Data From ClientNotes To Makeup table
     * @param text
     * @return slug
     */
    public function setClientIdInGoalBuddy(){
    	$GoalBuddy = GoalBuddy::where('gb_user_id','<>', 0)/*->where('gb_client_id', 0)*/->get();
    	$goalbodyData = array();
    	if($GoalBuddy->count()){
    		foreach ($GoalBuddy as $goalbuddy) {
    			$client_id = User::where('id', $goalbuddy->gb_user_id)->pluck('account_id')->first();
    			if($client_id){
    				$goalbodyData[$goalbuddy->id] = $client_id;
    				$goalbuddy->gb_client_id = $client_id;
    				$goalbuddy->save(); 
    			}
    		}
    	}

    	if(count($goalbodyData)){
    		$gbIds = array_keys($goalbodyData);
    		$goalBuddyHabit = GoalBuddyHabit::whereIn('goal_id', $gbIds)->where('gb_client_id', 0)->get();
    		if($goalBuddyHabit->count()){
    			foreach ($goalBuddyHabit as $habits) {
    				$habits->gb_client_id = $goalbodyData[$habits->goal_id];
    				$habits->save();
    			}
    		}
    		$goalBuddyMilestone = GoalBuddyMilestones::whereIn('goal_id', $gbIds)->where('gb_client_id', 0)->get();
    		if($goalBuddyMilestone->count()){
    			foreach ($goalBuddyMilestone as $milestone) {
    				$milestone->gb_client_id = $goalbodyData[$milestone->goal_id];
    				$milestone->save();
    			}
    		}
    		$goalBuddyTask = GoalBuddyTask::whereIn('goal_id', $gbIds)->where('gb_client_id', 0)->get();
    		if($goalBuddyTask->count()){
    			foreach ($goalBuddyTask as $tasks) {
    				$tasks->gb_client_id = $goalbodyData[$tasks->goal_id];
    				$tasks->save();
    			}
    		}
    		$goalBuddyUpade = GoalBuddyUpdate::whereIn('goal_id', $gbIds)->where('gb_client_id', 0)->get();
    		if($goalBuddyUpade->count()){
    			foreach ($goalBuddyUpade as $gbupdate) {
    				$gbupdate->gb_client_id = $goalbodyData[$gbupdate->goal_id];
    				$gbupdate->save();
    			}
    		}
    		return json_encode(['code'=>'200', 'message'=>'updated']);
    	}
    	return json_encode(['code'=>'401', 'message'=>'somthing went wrong']);
    }


    /**
     * Test membership
     *
     *
     */
    public function membershipTest(){

    	$clientId = 297;
    	$eventData = ['event_type'=>'service','event_id'=>115,'event_date'=>'2018-07-03'];
    	//$resp1 = $this->satisfyMembershipRestrictions($clientId, $eventData);
    	
    	$limtUpdate = ['type'=>'service','action'=>'sub', 'eventId'=>115,'limit_type'=>'every_week'];
    	/*if($resp1['satisfy']){*/
	    	$resp2 = $this->updateClientMembershipLimit($clientId, ['2018-07-03','2018-07-04','2018-07-12','2018-07-20','2018-07-28'], $limtUpdate);
	    	dd($resp2);
	    /*}*/
	    dd($resp1);
    }


    /**
     * Membership update
     * @param String $type
     * @return response
     * URL http://10.5.50.121/crm/public/update-membership-limit
     */
    public function updateClientMemberLimit(Request $request){
        set_time_limit(0);
        $updatedClient = array();
        $eventIds = array();
        if($request->has('clientId'))
            $clients = Clients::where('id', $request->clientId)->get();
        else
            $clients = Clients::where('business_id', Session::get('businessId'))->get();
        if(count($clients)){
            foreach ($clients as $client) {
                $updatedLimit = collect();
                $existLimit = \App\ClientMemberLimit::where('cme_client_id', $client->id)->first();
                if(count($existLimit))
                    \App\ClientMemberLimit::where('cme_client_id', $client->id)->forcedelete();

                $clientMember = Clients::paidMembership($client->id);
                if($clientMember && $clientMember->cm_services_limit != ''){
                    $serviceEvent = StaffEventSingleService::select('sess_id','sess_cmid','sess_date','sess_service_id','sess_with_invoice')->where('sess_client_id', $client->id)->get();
                    if(count($serviceEvent)){
                        foreach ($serviceEvent as $service) {
                            $serviceLimit = json_decode($clientMember->cm_services_limit, 1);
                            if($service->sess_with_invoice == 0 && array_key_exists($service->sess_service_id, $serviceLimit)){
                                $limit_type = $serviceLimit[$service->sess_service_id]['limit_type'];
                                $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit,$client->id , ['type'=>'service','action'=>'add','date'=>$service->sess_date, 'eventId'=>$service->sess_service_id,'limit_type'=>$limit_type]);
                                $eventIds['service'][] = $service->sess_id;
                            }
                        }
                    }
                }

                if($clientMember && $clientMember->cm_classes != ''){
                    $classEvent = $client->eventClasses()->get();
                    if(count($classEvent)){
                        foreach ($classEvent as $cls) {
                            if($cls->pivot->secc_with_invoice == 0){
                                $limit_type = $clientMember->cm_class_limit_type; 
                                $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit,$client->id , ['type'=>'class','action'=>'add','date'=>$cls->sec_date, 'eventId'=>$cls->sec_class_id,'limit_type'=>$limit_type]);
                                $eventIds['clsass'][] = $cls->sec_id;
                            }
                        }
                    }
                }

                if(count($updatedLimit)){
                   $updatedLimit->save();
                   $updatedClient[] = $client->id; 
                }
            }
        }

        print_r($updatedClient);
        dd($eventIds);
    }


    /**
     * Update Event area
     * @param String $type
     * @return response
     * URL http://10.5.50.121/crm/public/update-event-area?type=service/class
     */
    public function updateEventArea(Request $request){
        set_time_limit(0);
        $isError = true;
        $type = $request->type;
        if($type == 'class'){
            $classRepeats = \App\StaffEventClassRepeat::with('siblingEvents')->get();
            if(count($classRepeats)){
                foreach ($classRepeats as $classRepeat) {
                    $repeatingEventAreaData = [];
                    if(count($classRepeat->siblingEvents)){
                        foreach ($classRepeat->siblingEvents as  $classEvent) {
                            DB::table('staff_event_class_areas')->where('seca_sec_id', $classEvent->sec_id)->delete();
                            if($classRepeat->secr_area_id != ''){
                                $areas = explode(',', $classRepeat->secr_area_id);
                                if(count($areas)){
                                    foreach ($areas as $area) {
                                        $repeatingEventAreaData[] = array('seca_business_id' => $classRepeat->secr_business_id, 'seca_sec_id' => $classEvent->sec_id, 'seca_la_id' => (int)$area);
                                    }
                                }
                            }
                        }
                        if(count($repeatingEventAreaData)){
                            DB::table('staff_event_class_areas')->insert($repeatingEventAreaData);
                            $isError = false;
                        }
                    }
                }
            }
        }
        elseif($type == 'service'){
            $serviceRepeats = \App\StaffEventSingleServiceRepeat::with('siblingEvents')->get();
            if(count($serviceRepeats)){
                foreach ($serviceRepeats as $serviceRepeat) {
                    $serviceRepeatingEventAreaData = [];
                    if(count($serviceRepeat->siblingEvents)){
                        foreach ($serviceRepeat->siblingEvents as  $serviceEvent) {
                            DB::table('staff_event_single_service_areas')->where('sessa_sess_id', $serviceEvent->sess_id)->delete();
                            if($serviceRepeat->sessr_area_id != ''){
                                $areas = explode(',', $serviceRepeat->sessr_area_id);
                                if(count($areas)){
                                    foreach ($areas as $area) {
                                        $serviceRepeatingEventAreaData[] = array('sessa_business_id' => $serviceRepeat->sessr_business_id, 'sessa_sess_id' => $serviceEvent->sess_id, 'sessa_la_id' => (int)$area);
                                    }
                                }
                            }
                        }
                        if(count($serviceRepeatingEventAreaData)){
                            DB::table('staff_event_single_service_areas')->insert($serviceRepeatingEventAreaData);
                            $isError = false;
                        }
                    }
                }
            }
        }
        if(!$isError)
            dd('Updated');
        else
            dd('Error');
    }


    /**
     * Update Event client recurrence
     * @param void
     * @return response
     * URL http://10.5.50.121/crm/public/update-event-recurr-client
     // TODO: need to correct function as done by Pawan
    public function updateEventRecurrClient(){
        set_time_limit(0);
        $isError = true;
        $eventIds = array();
        $staffEventClasses = StaffEventClass::where('sec_business_id', Session::get('businessId'))->groupBy('sec_secr_id')->orderBy('sec_id','asc')->get();

        if($staffEventClasses){
            foreach ($staffEventClasses as $staffEventClass) {
                $clientReccurr = array();
                if(count($staffEventClass->clients)){
                    foreach ($staffEventClass->clients as $client) {
                        $clientReccurr[$client->pivot->secc_client_id] = array('with_invoice'=>$client->pivot->secc_with_invoice, 'with_credit'=>$client->pivot->secc_epic_credit);
                    }
                }
                if(count($clientReccurr))
                    $clientReccurr = json_encode($clientReccurr);
                else
                    $clientReccurr = '';
              
                if(\App\StaffEventClassRepeat::where('secr_id', $staffEventClass->sec_secr_id)->update(['secr_client_id'=>$clientReccurr])){
                    $isError = false;
                    $eventIds[] = $staffEventClass->sec_id;
                }

            }
        }

        if(!$isError){
            echo "Updated event is";
            dd($eventIds);
        }
        else
            dd('somthing went wrong.');
    }*/


    /**
     * Update Event client in client table with the help of recurence table
     * @param void
     * @return response
     * URL http://10.5.50.121/crm/public/update-event-client
     */
    public function updateEventClient(){
        set_time_limit(0);
        $isError = true;
        $eventIds = array();
        $repeatingEventClients = array();
        $staffEventClasses = StaffEventClass::whereDate('sec_date', '>','2018-07-10')->get();

        if($staffEventClasses){
            foreach ($staffEventClasses as $staffEventClass) {
                $timestamp = createTimestamp();
                $classClient = array();
                if(count($staffEventClass->clientsRaw)){
                    foreach ($staffEventClass->clientsRaw as $client) {
                        $classClient[] = $client->pivot->secc_client_id; 
                    }
                }
                $clientsAddedCount = count($classClient);
                $clientReccurr = array();
                $recurrData = \App\StaffEventClassRepeat::where('secr_id', $staffEventClass->sec_secr_id)->orderBy('secr_id', 'desc')->select('secr_id','secr_client_id')->first();
                if(count($recurrData)){
                    if($recurrData->secr_client_id != ''){
                        $clientReccurr = json_decode($recurrData->secr_client_id, true);
                        foreach ($clientReccurr as $key=>$value) {
                            if($value['with_invoice'] == 1 || $value['with_credit'] == 1){
                                $cmid = 0;
                            }
                            else{
                                $cmidData = Clients::paidMembership($key);
                                if($cmidData)
                                    $cmid = $cmidData->id;
                                else
                                    $cmid = 0;
                            }

                            if(!in_array($key, $classClient)){
                                $data = array('secc_sec_id' => $staffEventClass->sec_id, 'secc_client_id' =>$key , 'secc_if_recur' => 1,'secc_cmid'=>$cmid,'secc_with_invoice'=>$value['with_invoice'],'secc_epic_credit'=>$value['with_credit'], 'created_at' => $timestamp, 'updated_at' => $timestamp);

                                if($clientsAddedCount >= $staffEventClass->sec_capacity)
                                    $data['secc_client_status'] = 'Waiting';
                                else
                                    $data['secc_client_status'] = 'Confirm';
                                $clientsAddedCount++;
                                
                                $repeatingEventClients[] = $data;
                            }
                        }
                    }
                }
            }
        }

        if(count($repeatingEventClients)){
            DB::table('staff_event_class_clients')->insert($repeatingEventClients);
            dd($repeatingEventClients);
        }
        else
            dd('clients allready updated.');
    }


    /**
     * Update Event client in client table with the help of recurence table
     * @param void
     * @return response
     * URL http://10.5.50.121/crm/public/event-client-invoice
     */
    public function updateEventClientInvoice(){
       
        $eventRecurrs = \App\StaffEventClassRepeat::get();
        foreach ($eventRecurrs as $eventRecurr) {
            if($eventRecurr->secr_client_id != ''){
                $futureRecurEvent =  StaffEventClass::where('sec_secr_id',$eventRecurr->secr_id)/*->whereDate('sec_date','>','2018-07-11')*/->orderBy('sec_id','asc')->first();
                if(count($futureRecurEvent)){
                    $recurrClients = json_decode($eventRecurr->secr_client_id, true);
                    if(count($recurrClients)){
                        foreach ($recurrClients as $clientId=>$data) {
                            if($data['with_invoice'] == 1 || $data[] == 1){
                                $productId = $futureRecurEvent->sec_id;
                                $clientInvoice = \App\Invoice::where('inv_client_id', $clientId)
                                                            ->whereHas('invoiceitem', function($query) use ($productId){
                                                                $query->where('inp_product_id', $productId)
                                                                      ->where('inp_type', 'class');
                                                            })->get();
                                dd($clientInvoice);
                            }
                        }
                    }
                }
            }
        }
        
    }

}
