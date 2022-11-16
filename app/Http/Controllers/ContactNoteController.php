<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactNotes;
use App\Clients;
use Auth;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\ContactNoteTrait;
use App\Http\Traits\ClientNoteTrait;
use App\Http\Traits\SalesProcessTrait;
use App\Task;
use App\TaskCategory;
use App\ClientNote;
use Session;

class ContactNoteController extends Controller{
	use ClientTrait, ContactNoteTrait, SalesProcessTrait,ClientNoteTrait;

    public function addContactNote(Request $request){
    	$isError = false;
        $msg = [];

    	$client = Clients::findClient($request->clientId);
    	if($client){
			/*$data = array(
				'user_id' => Auth::id(),
				'client_id' => $request->clientId,
				'status' => $request->status,
				'notes' => ($request->note)?$request->note:null
			);
			if($request->callback)
				$data['callback'] = $request->callback;
			$createdContact = ContactNotes::create($data);
			$contactId = $createdContact->id;
               
			*/
              //dd($request->all());
			$createdContact = $this->storeContactNote(['clientId' => $request->clientId, 'status' => $request->status, 'note' => $request->note, 'callback' => $request->cbkdate]);

			$this->createNotes($request->note, $request->clientId, 'contact');

			//create task code goes here
			if($request->cbkdate){
				$personalCategory = TaskCategory::where('t_cat_user_id',0)
                                      ->where('t_cat_business_id',0)->select('id')->first();
        		$personalCategoryId = $personalCategory->id;
        		Task::create(['task_user_id'=>Auth::id(),'task_business_id'=>Session::get('businessId'),'task_name'=>$request->status,'task_due_date'=>$request->cbkdate,'task_category'=>$personalCategoryId,'task_due_time'=>'9:00:00','task_status'=>$request->note]);
			}


			$updateClient = false;
			if($request->status == 'contacted'){
				$salesProcessRelatedStatus = calcSalesProcessRelatedStatus(1);
				//if($client->account_status == $salesProcessRelatedStatus['clientPrevStatus']){
				if(statusMatchSalesStatus($client->account_status, $salesProcessRelatedStatus['clientPrevStatus'])){
					$updateClient = true;
					$client->account_status = $salesProcessRelatedStatus['clientStatus'];
					$clientOldSaleProcessStep = $client->sale_process_step;
					$client->sale_process_step = $salesProcessRelatedStatus['saleProcessStepNumb'];

					$salesProcessHistory = ['clientId'=>$request->clientId, 'eventId'=>$createdContact['id'], 'toType'=>$salesProcessRelatedStatus['salesProcessType'], 'toStep'=>$salesProcessRelatedStatus['saleProcessStepNumb'], 'action'=>'upgrade', 'reason'=>'Contact made'];
					/*if($clientOldSaleProcessStep){
						$clientPrevSalesProcess = calcSalesProcessRelatedStatus($clientOldSaleProcessStep);
						$salesProcessHistory['fromType'] = $clientPrevSalesProcess['salesProcessType'];
						$salesProcessHistory['fromStep'] = $clientPrevSalesProcess['saleProcessStepNumb'];
					}*/
					$msg['salesProcessDate'] = $this->saveSalesProcess($salesProcessHistory);

					$msg['changeStatus'] = $this->getStatusForbackend($salesProcessRelatedStatus['clientStatus'], true).'|'.$salesProcessRelatedStatus['clientStatus'];
				}
			}

			if($request->gender){
				$updateClient = true;
				$client->gender = $request->gender;

				if(hasPermission('edit-parq')){
					$parq = $client->parq;
					$parq->update(array('gender' => $request->gender));
				}
			}
			if($updateClient)
				$client->save();

			$msg['status'] = 'added';
			$msg['insertId'] = $createdContact['id'];
			$msg['createdDatetimeUi'] = $createdContact['createdDatetimeUi'];
			$msg['createdDatetime'] = $createdContact['createdDatetime'];
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

	        if($request->notestype != "")
	        	$notestype=$request->notestype;
	        else
	        	$notestype='general';

	        $notes=$this->createNotes($request->note, $request->clientId, $notestype);

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
	        if($clientnote->save()){
	            $msg['status'] = 'updated';                             
	        }
	        else{
	        	$msg['status'] = 'error';
	        }
	    }
	  echo json_encode($msg);  	
 	}

 	public function destroy($id){ 
		$notes = ClientNote::findOrFail($id);
        $notes->delete();
        
        return redirect()->back()->with('message', 'success|Notes has been deleted successfully.');
    }
}
