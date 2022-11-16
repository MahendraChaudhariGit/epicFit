<?php
namespace App\Http\Traits;

use App\SalesProcess;

trait SalesProcessTrait{
	/**
	 * Save sales process history
	 *
	 * @param array $data Value of the columns
	 */
    protected function saveSalesProcess($data){        
        //SalesProcess::where('sp_client_id', $data['clientId'])->where('sp_step', '>=', $data['toStep'])->delete();
        
        $saleProcess = new SalesProcess;

        $saleProcess->sp_client_id = $data['clientId']; //Client ID

        if(array_key_exists('eventId', $data))
        	$saleProcess->sp_entity_id = $data['eventId']; //ID of the event/contact note associated with the sales process

        /*if(array_key_exists('compDate', $data))
            $saleProcess->sp_comp_date = $data['compDate']; //Date on which step got completed
        else
            $saleProcess->sp_comp_date = createTimestamp();*/
        
        /*if(array_key_exists('fromType', $data))
        	$saleProcess->sp_from_type = $data['fromType']; //Step name from which the sales process jumped

        if(array_key_exists('fromStep', $data))
        	$saleProcess->sp_from_step = $data['fromStep'];//Step number from which the sales process jumped

        $saleProcess->sp_to_type = $data['toType'];//Step name to which the sales process jumped
        $saleProcess->sp_to_step = $data['toStep'];//Step number to which the sales process jumped*/

        $saleProcess->sp_type = $data['toType'];//Step name to which the sales process jumped
        $saleProcess->sp_step = $data['toStep'];//Step number to which the sales process jumped
        $saleProcess->sp_reason = $data['reason'];//Reason of this change in sales process
        
        //Whether sales process upgrade or downgrade
        if(array_key_exists('action', $data))
            $saleProcess->sp_action = $data['action'];
        else{
            if(!$data['fromStep'] || $data['fromStep'] < $data['toStep'])
                $saleProcess->sp_action = 'upgrade';
            else
                $saleProcess->sp_action = 'downgrade';
        }
        $saleProcess->save();
        
        return $saleProcess->CompletedOn;
    }
}