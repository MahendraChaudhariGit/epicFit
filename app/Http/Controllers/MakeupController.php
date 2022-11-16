<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ClientNoteTrait;
use App\Clients;
use App\Makeup;
use App\ClientNote;
use DB;
use Auth;
use Session;

class MakeupController extends Controller{
    use ClientNoteTrait;
    public function getNotes($id){
        
        $notes=ClientNote::select('cn_notes','cn_source')->with('makeup')
                          ->whereHas('makeup',function($query) use ($id){
                            $query->where('makeup_id',$id);
                          })->first();

        return json_encode($notes);
     }

    public function update($id,Request $request){
    	$isError = false;
        $msg = [];
        $client = Clients::findClient($request->clientId);
        if(!$client || !Auth::user()->hasPermission(Auth::user(), 'edit-client')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            $notesId='';
	        $makeup = Makeup::with('notes')->find($id); 
            if($makeup->notes!=''){
                 $makeup->notes->cn_notes=$request->notes;
                 $makeup->notes->save();
            }
            else{
                 $notesId=$this->createNotes($request->notes, $client->id, 'makeup', $request->raiseMakeUp.' epic credit raised.');
            }
                
            $makeup->makeup_client_id=$request->clientId;
            $makeup->makeup_session_count=$request->raiseMakeUp;
            $makeup->makeup_amount=$request->amount;
            if($notesId !=''){
               $makeup->makeup_notes_id=$notesId;
            }
            $makeup->makeup_user_id = $makeup->UserInformation['id'];
            $makeup->makeup_user_name = $makeup->UserInformation['name'];
            $makeup->save(); 
             if($this->setMakeupSessionCount($client->id))
                   $msg['status'] = 'updated';
             
        }
        return json_encode($msg);

    }
    
   public function destroy($id, Request $request){
       
        $root=route('clients.show',$request->clientId).'#makeup';
        $makeup = Makeup::findOrFail($id);
        $makeup->delete();
        if($makeup->makeup_notes_id != 0){
            $notes=ClientNote::findOrFail($makeup->makeup_notes_id);
            $notes->delete();
        }
        $this->setMakeupSessionCount($request->clientId);
       
        return redirect($root);
    }

}    
    	