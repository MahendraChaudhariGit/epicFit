<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactNotes;
use App\Clients;
use Auth;
use DB;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\ContactNoteTrait;
use App\Http\Traits\ClientNoteTrait;
use App\Http\Traits\SalesProcessTrait;
use App\Http\Traits\SalesProcessProgressTrait;
use App\Http\Traits\HelperTrait;
use App\Task;
use App\TaskCategory;
use App\ClientNote;
use App\NotesCategory;
use Session;
use Carbon\Carbon;
use App\StaffEventSingleService;

class ContactNoteController2 extends Controller{
	use ClientTrait, ContactNoteTrait, SalesProcessTrait,ClientNoteTrait, SalesProcessProgressTrait, HelperTrait;

    public function addContactNote(Request $request){
    	$isError = false;
        $msg = [];

    	$client = Clients::findClient($request->clientId);
    	
    	if($client){
    		//Adding notes
    		$noteId = $this->createNotes($request->note, $request->clientId, 'contact', 'Added from initial contact form');

    		/* start: Saving contact details */
				if($request->has('cbkdate'))
					$taskDueDate=$request->cbkdate;
				else
					$taskDueDate = false;

				if($request->has('contactTime'))
					$taskDueTime=$request->contactTime;
				else
					$taskDueTime = false;

				if($request->has('contactMadeOpt'))
					$contactMadeOpt=$request->contactMadeOpt;
				else
					$contactMadeOpt = false;

				$returnCheck=false;
				if($request->has('status')){
					$createdContact = $this->storeContactNote(['clientId' => $request->clientId, 'status' => $request->status, 'contactResult' => $contactMadeOpt, 'note' => $request->note, 'callback' => $taskDueDate, 'callbackTime' => $taskDueTime]);
					$returnCheck=true;
				}
			/* end: Saving contact details */

			/* start: Saving callback task */
				if($taskDueDate && $taskDueTime){
					if($request->status=='messaged'){
						$name = 'Left message to '.$client->firstname.' '.$client->lastname;
					}
					elseif($request->status=='noanswer'){
						$name = $client->firstname.' '.$client->lastname.' did not answer';
					}
					else{
						$name = 'Contact made with '.$client->firstname.' '.$client->lastname;
					}

					$personalCategory = TaskCategory::where('t_cat_user_id',0)
		                                  ->where('t_cat_business_id',0)->select('id')->first();
		    		$personalCategoryId = $personalCategory->id;
		    		Task::create(['task_user_id'=>Auth::id(),'task_business_id'=>Session::get('businessId'),'task_name'=>$name,'task_due_date'=>$taskDueDate,'task_category'=>$personalCategoryId,'task_due_time'=>$taskDueTime,'task_client_id'=>$request->clientId]);
		    	}
	    	/* end: Saving callback task */

			//$updateClient = false;
			/*if($request->status == 'contacted'){
				$salesProcessRelatedStatus = calcSalesProcessRelatedStatus(1);
				if(statusMatchSalesStatus($client->account_status, $salesProcessRelatedStatus['clientPrevStatus'])){
					$updateClient = true;
					$client->account_status = $salesProcessRelatedStatus['clientStatus'];
					$clientOldSaleProcessStep = $client->sale_process_step;
					$client->sale_process_step = $salesProcessRelatedStatus['saleProcessStepNumb'];

					$salesProcessHistory = ['clientId'=>$request->clientId, 'eventId'=>$createdContact['id'], 'toType'=>$salesProcessRelatedStatus['salesProcessType'], 'toStep'=>$salesProcessRelatedStatus['saleProcessStepNumb'], 'action'=>'upgrade', 'reason'=>'Contact made'];
					$msg['salesProcessDate'] = $this->saveSalesProcess($salesProcessHistory);

					$msg['changeStatus'] = $this->getStatusForbackend($salesProcessRelatedStatus['clientStatus'], true).'|'.$salesProcessRelatedStatus['clientStatus'];
				}
			}*/

			/*if($request->gender){
				$updateClient = true;
				$client->gender = $request->gender;

				if(hasPermission('edit-parq')){
					$parq = $client->parq;
					$parq->update(array('gender' => $request->gender));
				}
			}*/

			if($contactMadeOpt == 'ni'){
				$clientOldStatus = $client->account_status;
				$clientNewStatus = $this->getStatusForbackend($request->clientStatus);
				$clientNewStatus = preventActiveContraOverwrite($clientOldStatus, $clientNewStatus);
				if($clientOldStatus != $clientNewStatus){
					$this->processSalesProcessOnStatusChange($client, $clientOldStatus, $clientNewStatus, 'contact step of sales process');

					//$updateClient = true;
					$client->account_status = $clientNewStatus;
					$client->save();
					$msg['changeStatus'] = $request->clientStatus.'|'.$clientNewStatus;
				}
			}
			else if($contactMadeOpt == 'book'){
				$salesProcessRelatedStatus = calcSalesProcessRelatedStatus('contact');
				if(statusMatchSalesStatus($client->account_status, $salesProcessRelatedStatus['clientPrevStatus'])){
					if(!$this->checkFutureSalesProgress($salesProcessRelatedStatus['salesProcessType'], $request->clientId, $client->SaleProcessEnabledAttendSteps)){
                        //$updateClient = true;
						$client->account_status = preventActiveContraOverwrite($client->account_status, $salesProcessRelatedStatus['clientStatus']);
						$client->sale_process_step = $salesProcessRelatedStatus['saleProcessStepNumb'];
						$client->save();
                    }

					$salesProcessHistory = ['clientId'=>$request->clientId, 'eventId'=>$createdContact['id'], 'toType'=>$salesProcessRelatedStatus['salesProcessType'], 'toStep'=>$salesProcessRelatedStatus['saleProcessStepNumb'], 'action'=>'upgrade', 'reason'=>'Contact made'];
					/*$msg['salesProcessDate'] = */$this->saveSalesProcess($salesProcessHistory);

					$msg['salesProcessDate'] = $this->saveSalesProgress(['clientId'=>$request->clientId, 'stepNumb'=>$salesProcessRelatedStatus['saleProcessStepNumb']]);

					$msg['changeStatus'] = $this->getStatusForbackend($salesProcessRelatedStatus['clientStatus'], true).'|'.$salesProcessRelatedStatus['clientStatus'];

					$this->linkCompletedBooking('contact', $client);
				}
			}

			/*if($updateClient){
				$client->save();
			}*/

			$msg['status'] = 'added';
			$msg['noteId'] = $noteId; 
			$msg['viewNotes'] = isUserEligible(['Admin'], 'view-client-notes');
			if($returnCheck){
				$msg['insertId'] = $createdContact['id'];
				//$msg['createdDatetimeUi'] = $createdContact['createdDatetimeUi'];
				$msg['createdDatetime'] = $createdContact['createdDatetime'];
			}	
		}
		return json_encode($msg);
  }

//create client notes......
	public function addClientNote(Request $request)
	{
		$isError = false;
		$msg=[]; 
	 
	    /*if(!$isError){
	        $clientnote=new ClientNote;
	        if($request->notestype != "")
	        	$clientnote->cn_type=$request->notestype;
	        else
	        	$clientnote->cn_type='general';

	        $clientnote->cn_notes=$request->note;
	        $clientnote->cn_client_id=$request->clientId;
	        $clientnote->cn_user_id=Auth::id();
	        if($clientnote->save()){
	            $msg['status'] = 'added';                             
	        }
	        else{
	        	 $msg['status'] = 'error';
	        }*/

	        if($request->notestype != ""){
	        	$notestype=$request->notestype;
	        	$source = 'Added manually from client notes tab';
	        }
	        else{
	        	$notestype='general';
	        	$source = 'Added manually from client overview tab';
	        }

	        $notes=$this->createNotes($request->note, $request->clientId, $notestype , $source);

	    	if($notes)
	    		$msg['status'] = 'added';
	    	else
	    		$msg['status'] = 'error';

	    return json_encode($msg);
 	} 

  	//edit clients notes
  	public function editClientNote($id, Request $request)
	{

		$isError = false;
		$msg=[]; 
	 
	    if(!$isError){
	        $clientnote=ClientNote::find($id);

	        $clientnote->cn_type=$request->notestype;
	        $clientnote->cn_notes=$request->note;
	        $clientnote->cn_client_id=$request->clientId;
	        $clientnote->cn_user_id=Auth::id();
	        //$clientnote->cn_source='Added manually from client notes tab';
	        if($clientnote->save()){
	            $msg['status'] = 'updated';                             
	        }
	        else{
	        	$msg['status'] = 'error';
	        }
	    }
	  return json_encode($msg);  	
 	}

 	/*
 	 store notes category 
 	*/
 	/*public function setNotesCategory(Request $request){
 		$msg=[];
 		$msg['status']='error';
 		
 		$timestamp= Carbon::now();
 		$data=array('nc_slug'=>$this->slugify($request->catName),'nc_name'=>$request->catName,'nc_business_id'=>Session::get('businessId'),'created_at'=>$timestamp,'updated_at'=>$timestamp);
 		if(NotesCategory::insert($data))
 			$msg['status']='success';
 		return json_encode($msg);
 	}*/ 
 	/*protected function slugify($text){
 		$text = str_slug($text,'-');
 		//$text = trim($text, '-');
 		
 		if (empty($text)) {
 			$timestamp=time();
	    	return 'na-'.$timestamp;
	    }
	    if(is_numeric($text)){
	    	$text = 'na-'.$text;
	    }
	    $check=NotesCategory::whereIn('nc_business_id',[Session::get('businessId'),0])->where('nc_slug',$text)->exists();
    	if($check){
    		$text= $text.'-'.time();
    	}
    	*/
	    
	    /*if($check || $text=='contact' || $text=='general' || $text=='makeup'){
    		$text= $text.time();
    	}*/
 		/*//replace degits 
	  $text=preg_replace('/[^A-Za-z]+/', '-', $text);
	  
	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, '-');

	  // remove duplicate -
	  $text = preg_replace('~-+~', '-', $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) {
	    return 'n-a';
	  }*/
/*
	  return $text;
	}*/
	/*
 	 fatech notes category 
 	*/
 	/*public function getNotesCategory(){
 		
 	}*/
 	
 	/* Start: show task modal notes set  */
 	public function setTaskNote($id,Request $request){
      $task = Task::find($id);
      $task->task_note = $request->note;
      if($task->save())
          return 'success';
      return 'error';     
    }
    /* End: show task modal notes set  */


 	public function destroy($id){
 		$msg=[];
 		$msg['status']='error';
		$notes = ClientNote::findOrFail($id);
        if($notes->delete())
        	$msg['status']='deleted';

        return json_encode($msg);  
        //return redirect()->back()->with('message', 'success|Notes has been deleted successfully.');
    }
}
