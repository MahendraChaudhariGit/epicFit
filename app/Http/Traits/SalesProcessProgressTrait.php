<?php
namespace App\Http\Traits;

use App\SalesProcessProgress;
use App\Clients;
use App\StaffEventSingleService;

trait SalesProcessProgressTrait{
	/**
	 * Save sales process progress
	 *
	 * @param array $data Value of the columns
	 */
    protected function saveSalesProgress($data){    
        if($data['stepNumb']){
            $this->deleteSalesProgress($data['stepNumb'], $data['clientId']); //Delete step record if already exist

            $saleProcess = new SalesProcessProgress;

            $saleProcess->spp_client_id = $data['clientId']; //Client ID
            $saleProcess->spp_step_numb = $data['stepNumb']; //Step Number

            if(array_key_exists('eventId', $data))
            	$saleProcess->spp_booking_id = $data['eventId']; //ID of the event/contact note associated with the sales process

            if(array_key_exists('manual', $data))
                $saleProcess->spp_comp_manual = $data['manual']; //Whether manual completed?

            if(array_key_exists('compDatetime', $data))
                $saleProcess->spp_comp_date = $data['compDatetime']; //Datetime on which step got completed
            else
                $saleProcess->spp_comp_date = createTimestamp();
            
            $saleProcess->save();
            
            return $saleProcess->CompletedOn;
        }
    }

    protected function deleteSalesProgress($step = 0, $clientId){
        $query = SalesProcessProgress::where('spp_client_id', $clientId);
        $continue = true;
        if(is_array($step))
            $query->whereIn('spp_step_numb', $step);
        else if($step)
            $query->where('spp_step_numb', $step);
        else
            $continue = false;
        
        if($query->get()->count()) {
            foreach ($query->get() as $service)
                StaffEventSingleService::where('sess_id', $service->spp_booking_id)->forceDelete();
        }
        
        if($continue)
            $query->forceDelete();
    }

    /**
     * Calculate dependant step
     *
     * @param int $dependantStep Dependant step of the original step
     * @param array $enabledSteps Client's sales process enabled step
     *
     * @return int
     */
    protected function getDependantSep($dependantStep, $enabledSteps){
        $step = 0;
        $dependantStep = (int) $dependantStep;
        if($this->isStepEnabled($dependantStep, $enabledSteps)){ //Given step exist in the enable steps
            $step = $dependantStep;
        }
        else{
            //Given step is not enable for sales process. Getting step on which given step is dependant.
            $stepDetails = calcSalesProcessRelatedStatus($dependantStep);
            if(array_key_exists('dependantStep', $stepDetails)){ //Given step is dependant on another step. If not then we shall return true
                return $this->getDependantSep($stepDetails['dependantStep'], $enabledSteps);
            }
        }

        return $step;
    }

    /**
     * Check whether dependant step is completed or not
     *
     * @param int $dependantStep Dependant step of the original step
     * @param int $clientId Client PK
     * @param array $enabledSteps Client's sales process enabled step
     *
     * @return boolean
     */
    protected function isDependantStepComp($dependantStep, $clientId, $enabledSteps = false){
        if($enabledSteps == false)
            $enabledSteps = $this->getEnabledSteps($clientId);

        $stepToCheck = $this->getDependantSep($dependantStep, $enabledSteps);
        if($stepToCheck)
            return $this->isStepCompInternal($stepToCheck, $clientId);
        return true;

        /*$stepToCheck = [];
        if(!is_array($step))
            $step = [$step];
        foreach($step as $s){
            if(in_array($s, $enabledSteps))
                $stepToCheck[] = $s;
        }

        if(count($stepToCheck))
            return SalesProcessProgress::where('spp_client_id', $clientId)->whereIn('spp_step_numb', $stepToCheck)->exists();
            return true;*/

        /*$query = SalesProcessProgress::where('spp_client_id', $clientId);
        if(is_array($step))
            $query->whereIn('spp_step_numb', $step);
        else
            $query->where('spp_step_numb', $step);
            return $query->exists();*/
        }


        protected function isStepComp($step, $clientId, $enabledSteps = false){
            if($enabledSteps == false)
                $enabledSteps = $this->getEnabledSteps($clientId);

        if($this->isStepEnabled($step, $enabledSteps)){ //Given step exist in the enable steps
            return $this->isStepCompInternal($step, $clientId);
        }
        return true;
    }    

    /**
     * Check if given step is completed or not
     *
     * @param mixed $step Step to check
     * @param int $clientId Client PK
     *
     * @return boolean
     */
    protected function isStepCompInternal($step, $clientId){
        $query = SalesProcessProgress::where('spp_client_id', $clientId);
        if(is_array($step))
            $query->whereIn('spp_step_numb', $step);
        else
            $query->where('spp_step_numb', $step);
        return $query->exists();
    }

    /**
     * Check if any of the future step of sales process is complete
     *
     * @param string $stepSlug Slug of step whose future steps have to checked
     * @param int $clientId Client PK
     * @param array $enabledSteps Client's sales process enabled step
     *
     * @return boolean
     */
    protected function checkFutureSalesProgress($stepSlug, $clientId, $enabledSteps = false){
        $salesAttendanceSteps = salesAttendanceSteps();
        $key = array_search($stepSlug, $salesAttendanceSteps);
        $salesAttendanceNextSteps = array_slice($salesAttendanceSteps, $key+1);
        $nextStepsNumb = [];

        if($enabledSteps == false)
            $enabledSteps = $this->getEnabledSteps($clientId);
        
        foreach($salesAttendanceNextSteps as $step){
            $stepDetails = calcSalesProcessRelatedStatus($step);
            if(is_array($stepDetails['saleProcessStepNumb'])){
                foreach($stepDetails['saleProcessStepNumb'] as $step){
                    if($this->isStepEnabled($step, $enabledSteps))
                        $nextStepsNumb[] = $step;
                }
            }
            else if($this->isStepEnabled($stepDetails['saleProcessStepNumb'], $enabledSteps))
                $nextStepsNumb[] = $stepDetails['saleProcessStepNumb'];
        }

        //If sales process has advanced, i.e., changing consultation attendance and benchamrk attendance exist then do not change client status
        if(!count($nextStepsNumb) || !$this->isStepCompInternal($nextStepsNumb, $clientId)) //Sales process has not advanced
        return false;
        return true;
    }

    /**
     * Get enabled sales process steps of specific client
     *
     * @param int $clientId Client PK
     *
     * @return array
     */
    protected function getEnabledSteps($clientId){
        $client = Clients::find($clientId);
        $enabledSteps = $client->SaleProcessEnabledSteps;
        return $enabledSteps;
    }

    /**
     * Check whether given step is enabled in sales process or not
     *
     * @param int $step step to check
     * @param mixed $enabledSteps Client's sales process enabled step/Client ID
     *
     * @return boolean
     */
    protected function isStepEnabled($step, $enabledSteps){
        if(!is_array($enabledSteps))
            $enabledSteps = $this->getEnabledSteps($enabledSteps);

        if(in_array($step, $enabledSteps)) //Given step exist in the enable steps
        return true;
        return false;
    }

    /**
     * Get next enabled attendance step
     *
     * @param int $currStep Current attendance step
     * @param mixed $client Client record/Client ID
     *
     * @return int
     */
    protected function getNextAttendStep($currStep, $client){
        if($currStep == 18)
            return $currStep;

        if(is_int($client))
            $client = Clients::find($client);
        
        /*$teamAttendSteps = teamAttendSteps();
        $indivBookingSteps = indivBookingSteps();
        if($currStep == 5){
            $session = $client->SalesSessionOrder;
            if(count($session)){
                $session = explode('-', $session[0]); 
                if($session[0] == 'team')
                    return $teamAttendSteps[0];
                else if($session[0] == 'indiv')
                    return $indivBookingSteps[0];
            }
        }
        else if(in_array($currStep, $teamAttendSteps)){
            $key = array_search($currStep, $teamAttendSteps);
            $i = 0;
            $posInOrder = 0;
            $session = $client->SalesSessionOrder;
            foreach($session as $idx=>$step){
                if(preg_match("/\bteam\b/i", $step)){
                    $posInOrder = $idx;
                    $i++;
                    if($i>$key)
                        break;
                }
            }
            if(count($session) == $posInOrder+1)
                return $this->getNextAttendStep(18, $client);
            
            $nextIdx = $posInOrder+1;
            $nextStep = $session[$posInOrder+1];
            echo $session[$posInOrder+1];
            dd($posInOrder);
            
        }*/

        $teamAttendSteps = teamAttendSteps();
        $indivAttendSteps = indivAttendSteps();
        if($currStep == 5){
            $session = $client->SaleProcessSett['session'];
            if(count($session)){
                $teamedEnabled = $this->isStepEnabled($teamAttendSteps[0], $client->SaleProcessEnabledAttendSteps);
                $indivedEnabled = $this->isStepEnabled($indivAttendSteps[0], $client->SaleProcessEnabledAttendSteps);
                if($teamedEnabled && $indivedEnabled)
                    return $session[1];
                else if($teamedEnabled)
                    return $teamAttendSteps[0];
                else if($indivedEnabled)
                    return $indivAttendSteps[0];
            }
        }
        else if(in_array($currStep, $teamAttendSteps) || in_array($currStep, $indivAttendSteps)){
            $session = $client->SaleProcessSett['session'];
            if(count($session)){
                $key = array_search($currStep, $session);
                for($i=$key+1; $i<count($session);$i++){
                    if(in_array($session[$i], $teamAttendSteps) || in_array($session[$i], $indivAttendSteps))
                        return $session[$i];
                }
                return $this->getNextAttendStep(18, $client);
            }
            return 0;
        }

        $steps = $client->SaleProcessEnabledAttendSteps;
        $nextStep = 'email_price';
        foreach($steps as $step){
            if($step > $currStep){
                $nextStep = $step;
                break;
            }
        }
        return $nextStep;

        /*if(!is_array($enabledAttendSteps)){
            $client = Clients::find($enabledAttendSteps);
            $steps = $client->SaleProcessEnabledAttendSteps;
        }
        else
            $steps = $enabledAttendSteps;
        
        $nextStep = 'email_price';
        foreach($steps as $step){
            if($step > $currStep){
                $nextStep = $step;
                break;
            }
        }
        return $nextStep;*/
    }

    /**
     * Get client status based on the last completed attendance step
     *
     * @param string $stepSlug Slug of step whose previous steps have to checked
     * @param Collection $client Client record
     *
     * @return string
     */
    protected function getAttendClientStatus($stepSlug, $client){
        $salesAttendanceSteps = salesAttendanceSteps();
        $idx = array_search($stepSlug, $salesAttendanceSteps);
        $setAttendance = 'Pending';
        for($i=$idx-1; $i>=0; $i--){
            $stepDetails = calcSalesProcessRelatedStatus($salesAttendanceSteps[$i]);
            if($this->isStepEnabled($stepDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps) && $this->isStepComp($stepDetails['saleProcessStepNumb'], $client->id, $client->SaleProcessEnabledSteps)){

                $nextStep = $this->getNextAttendStep($stepDetails['saleProcessStepNumb'], $client);
                $nextStepDetails = calcSalesProcessRelatedStatus($nextStep);
                $setAttendance = $nextStepDetails['clientPrevStatus'];
                break;
            }
        }

        return $setAttendance;
    }

    /*protected function isTeamBookingEnabled(){
        
    }

    protected function getTeamEnableCount(){

    }*/
}