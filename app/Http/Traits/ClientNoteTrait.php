<?php
namespace App\Http\Traits;

use App\ClientNote;
use App\Makeup;
use App\Clients;
use Auth;

trait ClientNoteTrait{
    /**
     * Save notes
     *
     * @param string $notes Notes to save
     * @param integer $clientId Client ID
     * @param string $type Notes category
     * @param string $extraText Extra information. Optional
     * 
     */ 
    protected function createNotes($notes, $clientId, $type, $source = '', $extraText = ''){
        if($notes){
            $clientNote = new ClientNote;
            $clientNote->cn_client_id = $clientId;
            $clientNote->cn_user_id = Auth::id();
            $clientNote->cn_type = $type;
            $clientNote->cn_notes = $notes;
            if($source)
                $clientNote->cn_source = $source;
            if($extraText)
                $clientNote->cn_extra = $extraText;
            $clientNote->save();

            return $clientNote->cn_id;
        }
        return 0;
    }
    /*protected function setMakeupSessionCount($clientId){
         $totalMakeupSessionCount=Makeup::where('makeup_client_id',$clientId)
                                             ->sum('makeup_total_amount');
                                            
            $client = Clients::findClient($clientId);                              
                $client->makeup_session_count=$totalMakeupSessionCount;
                $client->save();
                
    }*/
}