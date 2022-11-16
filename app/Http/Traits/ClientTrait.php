<?php
/*
REQUIREMENT:
SalesProcessTrait for (processSalesProcessOnStatusChange(), manageTeamSalesProcess(), manageTeamSalesProcessHelper())
StaffEventsTrait for (updateFutureBookingsMembership())
StaffEventTrait for (processSalesProcessOnStatusChange())
StaffEventClassTrait for (updateFutureBookingsMembership())
ClosedDateTrait for (updateFutureBookingsMembership())
HelperTrait for (updateFutureBookingsMembership())
 */
namespace App\Http\Traits;

use App\Clas;
use App\ClassCat;
use App\ClientMember;
use App\ClientMemberLimit;
use App\Clients;
use App\MemberShip;
use App\SalesProcessProgress;
use App\StaffEventClass;
use App\StaffEventSingleService;
use Carbon\Carbon;
use DB;
use Session;
use App\Http\Traits\StaffEventHistoryTrait;

trait ClientTrait
{
    use StaffEventHistoryTrait;

    protected function quickSaveClient($request)
    {
        $name       = explode(' ', $request->clientName);
        $insertData = array('business_id' => Session::get('businessId'), 'firstname' => $name[0], 'phonenumber' => $request->clientNumb, 'email' => $request->clientEmail, 'sale_process_setts' => json_encode(['steps' => ['4', '5', '18', '11'], 'teamCount' => '3', 'indivCount' => '', 'order' => json_decode('[{"id":"team-1"},{"id":"team-2"},{"id":"team-3"}]', 1)]) /*, 'fixed_sales'=>1*/, 'is_bookbench_on' => 1);

        if (count($name) > 1) {
            $insertData['lastname'] = $name[1];
        }

        $addedClient = Clients::create($insertData);
        Session::put('ifBussHasClients', true);

        $insertData = array('firstName' => $name[0], 'contactNo' => $request->clientNumb, 'email' => $request->clientEmail);
        if (count($name) > 1) {
            $insertData['lastName'] = $name[1];
        }

        $addedClient->parq()->create($insertData);

        return $addedClient->id;
    }

    protected function getStatusForbackend($status, $reverse = false)
    {
        $statuses = clientStatuses();

        if ($reverse) {
            return array_search($status, $statuses);
        } else {
            if (array_key_exists($status, $statuses)) {
                return $statuses[$status];
            }

            return false;
        }
    }

    protected function processSalesProcessOnStatusChange($client, $clientOldStatus, $clientNewStatus, $source)
    {
        $return = [];
        if ($clientNewStatus != 'On Hold' && $clientNewStatus != 'Inactive') {

            $clientNewSaleProcessDetails = calcSalesProcessRelatedStatus($clientNewStatus);
            $continue                    = false;
            if (!array_key_exists('statusDependingStep', $clientNewSaleProcessDetails) || (!is_array($clientNewSaleProcessDetails['statusDependingStep']) && $this->isStepEnabled($clientNewSaleProcessDetails['statusDependingStep'], $client->SaleProcessEnabledSteps))) {
                $continue = true;
            } else if (array_key_exists('statusDependingStep', $clientNewSaleProcessDetails) && is_array($clientNewSaleProcessDetails['statusDependingStep'])) {
                foreach ($clientNewSaleProcessDetails['statusDependingStep'] as $step) {
                    if ($this->isStepEnabled($step, $client->SaleProcessEnabledSteps)) {
                        $continue = true;
                        break;
                    }
                }
            }

            if ($continue) {
                /* Removing future attendance steps */
                $salesAttendanceSteps = salesAttendanceSteps();
                if (!$clientNewSaleProcessDetails['salesProcessType']) {
                    $startKey = -1;
                } else {
                    $startKey = array_search($clientNewSaleProcessDetails['salesProcessType'], $salesAttendanceSteps);
                }

                $salesAttendanceRemSteps = array_slice($salesAttendanceSteps, $startKey + 1);
                if (count($salesAttendanceRemSteps)) {
                    $deleteStep = [];
                    foreach ($salesAttendanceRemSteps as $slug) {
                        $thisDetails = calcSalesProcessRelatedStatus($slug);

                        if (is_array($thisDetails['saleProcessStepNumb']) && $clientNewStatus != 'Pre-Training') {
                            foreach ($thisDetails['saleProcessStepNumb'] as $step) {
                                if ($this->isStepEnabled($step, $client->SaleProcessEnabledSteps)) {
                                    $deleteStep[] = $step;
                                }

                            }
                        } else if (!is_array($thisDetails['saleProcessStepNumb']) && $this->isStepEnabled($thisDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps)) {
                            $deleteStep[] = $thisDetails['saleProcessStepNumb'];
                        }

                    }
                    if (count($deleteStep)) {
                        $this->deleteSalesProgress($deleteStep, $client->id);
                    }
                    //Deleting record from progress
                }
                /* Removing future attendance steps */

                /* Adding past steps */
                if ($clientNewSaleProcessDetails['salesProcessType']) {
                    $salesProcessTypes = salesProcessTypes();
                    $startKey          = array_search($clientNewSaleProcessDetails['salesProcessType'], $salesProcessTypes);
                    for ($i = $startKey; $i >= 0; $i--) {
                        $slug        = $salesProcessTypes[$i];
                        $thisDetails = calcSalesProcessRelatedStatus($slug);

                        if (is_array($thisDetails['saleProcessStepNumb'])) {
                            foreach ($thisDetails['saleProcessStepNumb'] as $step) {
                                if ($this->isStepEnabled($step, $client->SaleProcessEnabledSteps) && !$this->isStepComp($step, $client->id, $client->SaleProcessEnabledSteps)) {
                                    $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => $step, 'manual' => 1]);
                                }
                                //Adding record in progress
                            }
                        } else if ($this->isStepEnabled($thisDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps) && !$this->isStepComp($thisDetails['saleProcessStepNumb'], $client->id, $client->SaleProcessEnabledSteps)) {
                            $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => $thisDetails['saleProcessStepNumb']]); //Adding record in progress
                        }

                        if ($slug == 'consulted' && $client->consultation_date == null) {
                            $client->consultation_date = Carbon::now()->toDateString();
                            $client->save();
                        }
                    }
                }
                /* Adding past steps */

                if ($clientNewStatus == 'Pre-Training') {
                    $this->manageSessionSalesProcess($client);
                }

                $return['newSaleProcessStep'] = 1; //$clientNewSaleProcessDetails['saleProcessStepNumb'];
                $return['salesProcessDate']   = '';
                $return['consultationDate']   = $client->consultation_date;
                $return['oldSaleProcessStep'] = 1; //$clientOldSaleProcessDetails['saleProcessStepNumb'];
                $return['action']             = 'equal';
            }

            /*if(!array_key_exists('statusDependingStep', $clientNewSaleProcessDetails) || $this->isStepEnabled($clientNewSaleProcessDetails['statusDependingStep'], $client->SaleProcessEnabledSteps)){

        $clientOldSaleProcessDetails = calcSalesProcessRelatedStatus($clientOldStatus);
        if($clientOldSaleProcessDetails['saleProcessStepNumb'] < $clientNewSaleProcessDetails['saleProcessStepNumb']){
        //Upgrade
        if(array_key_exists('statusDependingStep', $clientOldSaleProcessDetails) && !$this->isStepEnabled($clientOldSaleProcessDetails['statusDependingStep'], $client->SaleProcessEnabledSteps))
        $clientOldSaleProcessDetails = calcSalesProcessRelatedStatus('');

        $salesProcessTypes = salesProcessTypes();
        if($clientOldSaleProcessDetails['salesProcessType'])
        $startKey = array_search($clientOldSaleProcessDetails['salesProcessType'], $salesProcessTypes);
        else
        $startKey = 0;

        if($clientNewSaleProcessDetails['salesProcessType'] == 'teamed')
        $newSalesType = 'email_price';
        else
        $newSalesType = $clientNewSaleProcessDetails['salesProcessType'];
        $endKey = array_search($newSalesType, $salesProcessTypes);
        $salesAttendanceAddSteps = array_slice($salesProcessTypes, $startKey, $endKey+1);
        if(count($salesAttendanceAddSteps)){
        foreach($salesAttendanceAddSteps as $slug){
        $thisDetails = calcSalesProcessRelatedStatus($slug);
        if($this->isStepEnabled($thisDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps)){
        if($slug == 'book_team'){
        $saleProcessActiveDetails = calcSalesProcessRelatedStatus('Active');
        $this->saveTeamProgress($client, $saleProcessActiveDetails['saleProcessStepNumb']);
        }
        else{
        $this->saveSalesProgress(['clientId'=>$client->id, 'stepNumb'=>$thisDetails['saleProcessStepNumb']]); //Adding record in progress

        if($slug == 'consulted'){
        $client->consultation_date = Carbon::now()->toDateString();
        $client->save();
        }
        }
        }
        }
        }

        $salesAttendanceSteps = salesAttendanceSteps();
        $startKey = array_search($newSalesType, $salesAttendanceSteps);
        $salesAttendanceRemSteps = array_slice($salesAttendanceSteps, $startKey+1);
        if(count($salesAttendanceRemSteps)){
        foreach($salesAttendanceRemSteps as $slug){
        $thisDetails = calcSalesProcessRelatedStatus($slug);
        if($this->isStepEnabled($thisDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps))
        $this->deleteSalesProgress($thisDetails['saleProcessStepNumb'], $client->id); //Deleting record from progress
        }
        }

        if($clientNewStatus == 'Pre-Training')
        $this->manageTeamSalesProcess($client, 0, '', 0, true);

        $return['action'] = 'upgrade';
        }
        else if($clientOldSaleProcessDetails['saleProcessStepNumb'] > $clientNewSaleProcessDetails['saleProcessStepNumb']){
        //Downgrade
        if(array_key_exists('statusDependingStep', $clientOldSaleProcessDetails) && !$this->isStepEnabled($clientOldSaleProcessDetails['statusDependingStep'], $client->SaleProcessEnabledSteps))
        $clientOldSaleProcessDetails = calcSalesProcessRelatedStatus(18);

        $salesAttendanceSteps = array_reverse(salesAttendanceSteps());
        if($clientOldSaleProcessDetails['salesProcessType'] == 'teamed')
        $oldSalesType = 'email_price';
        else
        $oldSalesType = $clientOldSaleProcessDetails['salesProcessType'];
        $startKey = array_search($oldSalesType, $salesAttendanceSteps);
        if($clientNewSaleProcessDetails['salesProcessType']){
        $endKey = array_search($clientNewSaleProcessDetails['salesProcessType'], $salesAttendanceSteps);
        $length = $endKey - $startKey;
        $salesAttendanceRemSteps = array_slice($salesAttendanceSteps, $startKey, $length);
        }
        else
        $salesAttendanceRemSteps = array_slice($salesAttendanceSteps, $startKey);

        if(count($salesAttendanceRemSteps)){
        foreach($salesAttendanceRemSteps as $slug){
        $thisDetails = calcSalesProcessRelatedStatus($slug);
        if($this->isStepEnabled($thisDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps))
        $this->deleteSalesProgress($thisDetails['saleProcessStepNumb'], $client->id); //Deleting record from progress
        }
        }

        $return['action'] = 'downgrade';
        }
        else
        $return['action'] = 'equal';

        $return['newSaleProcessStep'] = $clientNewSaleProcessDetails['saleProcessStepNumb'];
        $return['salesProcessDate'] = '';
        $return['consultationDate'] = $client->consultation_date;
        $return['oldSaleProcessStep'] = $clientOldSaleProcessDetails['saleProcessStepNumb'];
        }*/
        }
        return $return;
    }

    protected function allClientsFromTrait($request)
    {
        if (!Session::has('businessId')) {
            if ($request->ajax()) {
                return [];
            } else {
                abort(404);
            }

        }

        $query = Clients::OfBusiness();
        if ($request->has('forSalesProcess')) {
            if (!$request->forSalesProcess || $request->forSalesProcess == 'book_team') {
                $saleProcessBookTeamDetails = calcSalesProcessRelatedStatus('book_team');
                $dependantStep              = $saleProcessBookTeamDetails['dependantStep'];

                $query->where(function ($query) use ($dependantStep) {
                    $query->where(function ($query) use ($dependantStep) {
                        $query->where('is_bookbench_on', 1)
                            ->whereHas('salesProgress', function ($query) use ($dependantStep) {
                                $query->where('spp_step_numb', $dependantStep);
                            });
                    })
                        ->orWhere(function ($query) {
                            $query->where('is_bookbench_on', 0)
                                ->whereHas('salesProgress', function ($query) {
                                    $query->where('spp_step_numb', 2);
                                });
                        });
                });

                //$query->whereIn('account_status', ['Pre-Training', 'Active', 'Contra']);
            } else if ($request->forSalesProcess == 2 || $request->forSalesProcess == 4) {
                $forSalesProcess = (int) $request->forSalesProcess;
                $query->whereHas('salesProgress', function ($query) use ($forSalesProcess) {
                    $query->where('spp_step_numb', $forSalesProcess);
                }, '<', 1);
                if ($forSalesProcess == 4) {
                    $saleProcessBookBenchDetails = calcSalesProcessRelatedStatus($forSalesProcess);
                    $dependantStep               = $saleProcessBookBenchDetails['dependantStep'];
                    $query->where('is_bookbench_on', 1)
                        ->whereHas('salesProgress', function ($query) use ($dependantStep) {
                            $query->where('spp_step_numb', $dependantStep);
                        });
                }

                /*$service = Service::ofBusiness()->where('for_sales_process_step', $request->forSalesProcess)->first();
            $bookedClients = StaffEventSingleService::ofBusiness()->where('sess_service_id', $service->id)->select('sess_client_id')->get();
            if($bookedClients->count()){
            $bookedClients = $bookedClients->pluck('sess_client_id')->toArray();
            $bookedClients = array_unique($bookedClients);
            $query->whereNotIn('id', $bookedClients);
            }

            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus((int) $request->forSalesProcess);
            if($request->forSalesProcess == 2)
            $query->whereIn('account_status', [$salesProcessRelatedStatus['clientPrevStatus'], 'Pending']);//, 'Active Lead', 'Inactive Lead'
            else if($request->forSalesProcess == 4)
            $query->where('account_status', $salesProcessRelatedStatus['clientPrevStatus']);*/
            }
        }
        $clients = $query->get();

        $clientsList = $this->prepareClientsList($clients);

        return $clientsList;
    }

    protected function prepareClientsList($clients)
    {
        $index = 0;
        $cl    = array();
        foreach ($clients as $client) {
            $cl[$index]['id']        = $client->id;
            $cl[$index]['name']      = $client->firstname . ' ' . $client->lastname;
            $cl[$index]['email']     = $client->email;
            $cl[$index]['phone']     = $client->phonenumber;
            $cl[$index]['epicCash']  = $client->epic_credit_balance;
            $cl[$index]['accStatus'] = $client->account_status;
            $index++;
        }
        return json_encode($cl);
    }

    protected function manageSessionSalesProcess($client, $eventToSkip = 0/*, $stopIfActive = false*/, $isAttend = false)
    {
        if (isClientInSalesProcess($client->consultation_date, $client->consul_exp_date)) {
            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('book_team');
            if (($client->TeamEnabledCount || $client->IndivEnabledCount) && ($this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps) || $isAttend)) {
                //If sales process has book benchmark complete already
                if ($client->TeamEnabledCount) {
                    $classBookings = StaffEventClass::teamBookings($client->id, $client->consultation_date, $eventToSkip, 'secc_client_attendance', $client->consul_exp_date);
                } else {
                    $classBookings = [];
                }

                $servBookings = [];
                if ($client->IndivEnabledCount) {
                    $servBookings = StaffEventSingleService::indivBookings($client->id, $client->consultation_date);
                }

                $session = $client->SaleProcessSett['session'];
                if (count($session)) {
                    /*if(!$stopIfActive || ($client->account_status != 'Active' && $client->account_status != 'Contra'))
                    $this->deleteSalesProgress($session, $client->id); //Deleting record from progress*/

                    /*$compSteps = [];
                    $teamI = 0;
                    $indivI = 0;
                    $teamBookingSteps = teamBookingSteps();
                    $indivBookingSteps = indivBookingSteps();
                    $teamAttendSteps = teamAttendSteps();
                    $indivAttendSteps = indivAttendSteps();
                    $prevBookingStep = 0;
                    $prevAttendStep = 0;
                    $lastAttendStep = 0;
                    foreach($session as $step){
                    if(in_array($step, $teamBookingSteps) && (!$prevBookingStep || in_array($prevBookingStep, $compSteps))){
                    $prevBookingStep = $step;
                    $teamI++;
                    if(count($classBookings) >= $teamI){
                    $compSteps[] = $step;
                    if(!$this->isStepComp($step, $client->id, $client->SaleProcessEnabledSteps))
                    $this->saveSalesProgress(['clientId'=>$client->id, 'stepNumb'=>$step]); //Adding record in progress
                    }
                    }
                    else if(in_array($step, $indivBookingSteps) && (!$prevBookingStep || in_array($prevBookingStep, $compSteps))){
                    $prevBookingStep = $step;
                    $indivI++;
                    if(count($servBookings) >= $indivI){
                    $compSteps[] = $step;
                    if(!$this->isStepComp($step, $client->id, $client->SaleProcessEnabledSteps))
                    $this->saveSalesProgress(['clientId'=>$client->id, 'stepNumb'=>$step]); //Adding record in progress
                    }
                    }
                    else if(in_array($step, $teamAttendSteps)){
                    $lastAttendStep = $step;

                    $continue = false;
                    if(!$prevAttendStep){
                    $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('teamed');
                    if($this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)){
                    $continue = true;
                    }
                    }
                    else if($prevAttendStep && in_array($prevAttendStep, $compSteps)){
                    $continue = true;
                    }

                    if($continue){
                    $prevAttendStep = $step;
                    if($teamI && count($classBookings) >= $teamI && $classBookings[$teamI-1] == 'Attended'){
                    $compSteps[] = $step;
                    if(!$this->isStepComp($step, $client->id, $client->SaleProcessEnabledSteps))
                    $this->saveSalesProgress(['clientId'=>$client->id, 'stepNumb'=>$step]); //Adding record in progress
                    }
                    }
                    }
                    else if(in_array($step, $indivAttendSteps)){
                    $lastAttendStep = $step;

                    $continue = false;
                    if(!$prevAttendStep){
                    $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('indiv');
                    if($this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)){
                    $continue = true;
                    }
                    }
                    else if($prevAttendStep && in_array($prevAttendStep, $compSteps)){
                    $continue = true;
                    }

                    if($continue){
                    $prevAttendStep = $step;
                    if($indivI && count($servBookings) >= $indivI && $servBookings[$indivI-1] == 'Attended'){
                    $compSteps[] = $step;
                    if(!$this->isStepComp($step, $client->id, $client->SaleProcessEnabledSteps))
                    $this->saveSalesProgress(['clientId'=>$client->id, 'stepNumb'=>$step]); //Adding record in progress
                    }
                    }
                    }
                    }*/

                    $classAttended = array_count_values($classBookings);
                    if (array_key_exists('Attended', $classAttended)) {
                        $classAttended = $classAttended['Attended'];
                    } else {
                        $classAttended = 0;
                    }

                    $serviceAttended = array_count_values($servBookings);
                    if (array_key_exists('Attended', $serviceAttended)) {
                        $serviceAttended = $serviceAttended['Attended'];
                    } else {
                        $serviceAttended = 0;
                    }

                    $compSteps         = [];
                    $teamBookingSteps  = teamBookingSteps();
                    $teamAttendSteps   = teamAttendSteps();
                    $indivBookingSteps = indivBookingSteps();
                    $indivAttendSteps  = indivAttendSteps();
                    $prevBookingStep   = 0;
                    $prevAttendStep    = 0;
                    $teamI             = 0;
                    $teamedI           = 0;
                    $indivI            = 0;
                    $indivedI          = 0;
                    $lastAttendStep    = 0;
                    foreach ($session as $step) {
                        if (in_array($step, $teamBookingSteps)) {
                            if (!$prevBookingStep || in_array($prevBookingStep, $compSteps) || $isAttend) {
                                //Prev Step completed
                                $teamI++;

                                if (count($classBookings) >= $teamI) {
                                    $compSteps[] = $step;
                                    if (!$this->isStepComp($step, $client->id, $client->SaleProcessEnabledSteps)) {
                                        $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => $step]);
                                    }
                                    //Adding record in progress
                                }
                            }
                            $prevBookingStep = $step;
                        } else if (in_array($step, $indivBookingSteps)) {
                            if (!$prevBookingStep || in_array($prevBookingStep, $compSteps) || $isAttend) {
                                //Prev Step completed
                                $indivI++;

                                if (count($servBookings) >= $indivI) {
                                    $compSteps[] = $step;
                                    if (!$this->isStepComp($step, $client->id, $client->SaleProcessEnabledSteps)) {
                                        $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => $step]);
                                        
                                    }
                                    //Adding record in progress
                                }
                            }
                            $prevBookingStep = $step;
                        } else if (in_array($step, $teamAttendSteps)) {
                            $lastAttendStep = $step;

                            $continue = false;
                            if (!$prevAttendStep) {

                                $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('teamed');
                                if ($this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps) || $isAttend) {
                                    $continue = true;
                                }

                            } else if (in_array($prevAttendStep, $compSteps)) {
                                $continue = true;
                            }

                            if ($continue) {
                                $teamedI++;
                                if ($classAttended >= $teamedI) {
                                    $compSteps[] = $step;
                                    if (!$this->isStepComp($step, $client->id, $client->SaleProcessEnabledSteps)) {
                                        $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => $step]);
                                    }
                                    //Adding record in progress
                                }
                            }
                            $prevAttendStep = $step;
                        } else if (in_array($step, $indivAttendSteps)) {
                            $lastAttendStep = $step;

                            $continue = false;
                            if (!$prevAttendStep) {
                                $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('indiv');
                                if ($this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps) || $isAttend) {
                                    $continue = true;
                                }

                            } else if (in_array($prevAttendStep, $compSteps)) {
                                $continue = true;
                            }

                            if ($continue) {
                                $indivedI++;
                                if ($serviceAttended >= $indivedI) {
                                    $compSteps[] = $step;
                                    if (!$this->isStepComp($step, $client->id, $client->SaleProcessEnabledSteps)) {
                                        $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => $step]);
                                    }
                                    //Adding record in progress
                                }
                            }
                            $prevAttendStep = $step;
                        }
                    }

                    if (count($compSteps)) {
                        $manualComp = SalesProcessProgress::manualCompStepsNumb($client->id);

                        if (count($manualComp)) {
                            $newManualSteps = [];

                            $manualTeamBookingSteps = array_intersect($teamBookingSteps, $manualComp);
                            if (count($manualTeamBookingSteps)) {
                                $teamBookedSteps = array_intersect($teamBookingSteps, $compSteps);
                                if (count($teamBookedSteps)) {
                                    $newManualSteps = array_splice($teamBookedSteps, 0, count($manualTeamBookingSteps));
                                }

                            }

                            $manualIndivBookingSteps = array_intersect($indivBookingSteps, $manualComp);
                            if (count($manualIndivBookingSteps)) {
                                $indivBookedSteps = array_intersect($indivBookingSteps, $compSteps);
                                if (count($indivBookedSteps)) {
                                    $indivBookedSteps = array_splice($indivBookedSteps, 0, count($manualIndivBookingSteps));
                                    $newManualSteps   = array_merge($newManualSteps, $indivBookedSteps);
                                }
                            }

                            $manualTeamAttendSteps = array_intersect($teamAttendSteps, $manualComp);
                            if (count($manualTeamAttendSteps)) {
                                $teamedSteps = array_intersect($teamAttendSteps, $compSteps);
                                if (count($teamedSteps)) {
                                    $teamedSteps    = array_splice($teamedSteps, 0, count($manualTeamAttendSteps));
                                    $newManualSteps = array_merge($newManualSteps, $teamedSteps);
                                }
                            }

                            $manualIndivAttendSteps = array_intersect($indivAttendSteps, $manualComp);
                            if (count($manualIndivAttendSteps)) {
                                $indivedSteps = array_intersect($indivAttendSteps, $compSteps);
                                if (count($indivedSteps)) {
                                    $indivedSteps   = array_splice($indivedSteps, 0, count($manualIndivAttendSteps));
                                    $newManualSteps = array_merge($newManualSteps, $indivedSteps);
                                }
                            }

                            if (count($newManualSteps)) {
                                SalesProcessProgress::where('spp_client_id', $client->id)->whereIn('spp_step_numb', $compSteps)->update(['spp_comp_manual' => 0]);

                                SalesProcessProgress::where('spp_client_id', $client->id)->whereIn('spp_step_numb', $newManualSteps)->update(['spp_comp_manual' => 1]);
                            }
                        }
                    }

                    if ( /*!$stopIfActive || (*/$client->account_status != 'Active' && $client->account_status != 'Contra' /*)*/) {
                        $delSteps = array_diff($session, $compSteps);
                        if (count($delSteps)) {
                            $this->deleteSalesProgress($delSteps, $client->id);
                        }
                        //Deleting record from progress
                    }

                    if ($lastAttendStep) {
                        $saleProcessActiveDetails = calcSalesProcessRelatedStatus($lastAttendStep);
                        if ($this->isStepComp($lastAttendStep, $client->id, $client->SaleProcessEnabledSteps)) {
                            //change to active
                            // $client->account_status = preventActiveContraOverwrite($client->account_status, $saleProcessActiveDetails['clientStatus']);
                            $client->save();
                        } else if ( /*!$stopIfActive || (*/$client->account_status != 'Active' && $client->account_status != 'Contra' /*)*/) {
                            /*$salesAttendanceSteps = salesAttendanceSteps();
                            $idx = array_search('teamed', $salesAttendanceSteps);
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

                            $client->account_status = preventActiveContraOverwrite($client->account_status, $setAttendance);*/
                            // $client->account_status = preventActiveContraOverwrite($client->account_status, $this->getAttendClientStatus('teamed', $client));
                            $client->save();
                        }
                    }
                }
            }
        }
    }

    protected function manageTeamSalesProcess($client, $eventId = 0, $saveHistory = '', $eventToSkip = 0, $stopIfActive = false)
    {
        if (1 == 2 && isClientInSalesProcess($client->consultation_date)) {
            $salesProcessRelatedStatus = calcSalesProcessRelatedStatus('book_team');
            if ($client->TeamEnabledCount && $this->isDependantStepComp($salesProcessRelatedStatus['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)) {
                //If sales process has been benchmarked already
                $saleProcessActiveDetails = calcSalesProcessRelatedStatus('Active');
                $totalBookings            = StaffEventClass::teamBookings($client->id, $client->consultation_date, $eventToSkip);
                $bookings                 = array_count_values($totalBookings);
                if ($this->isStepEnabled($saleProcessActiveDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps) && $this->isStepComp($saleProcessActiveDetails['saleProcessStepNumb'], $client->id, $client->SaleProcessEnabledSteps)) {
                    //If sales process has been teamed already
                    if (!$stopIfActive && (!array_key_exists("Attended", $bookings) || $bookings['Attended'] < $client->TeamEnabledCount)) {
                        if (!$this->checkFutureSalesProgress('teamed', $client->id, $client->SaleProcessEnabledSteps)) {
                            $client->account_status = clientStatusPrevSales($saleProcessActiveDetails);
                            $client->save();
                        }
                        return $this->manageTeamSalesProcessHelper($client, $eventId, $totalBookings, $saveHistory);
                    }
                } else {
                    //If sales process yet to be teamed
                    if (array_key_exists("Attended", $bookings) && $bookings['Attended'] >= $client->TeamEnabledCount) { //If attended bookings are at least 3 then upgrade sales process to teamed

                        if ($this->isStepEnabled($saleProcessActiveDetails['saleProcessStepNumb'], $client->SaleProcessEnabledSteps) && $this->isDependantStepComp($saleProcessActiveDetails['dependantStep'], $client->id, $client->SaleProcessEnabledSteps)) {
                            $this->saveTeamProgress($client, $saleProcessActiveDetails['saleProcessStepNumb']);

                            if (!$this->checkFutureSalesProgress('teamed', $client->id, $client->SaleProcessEnabledSteps)) {
                                $client->account_status = $saleProcessActiveDetails['clientStatus'];
                                $client->save();
                            }
                        }
                    } else {
                        return $this->manageTeamSalesProcessHelper($client, $eventId, $totalBookings, $saveHistory);
                    }
                }
            }
        }

        if ($saveHistory) {
            return false;
        } else {
            return [];
        }

    }

    protected function manageTeamSalesProcessHelper($client, $eventId = 0, $totalBookings, $saveHistory = '')
    {
        //If there are no attended bookings or are less than 3 then mark 'book team' steps of sales process completed according to the number of team booked
        $teamBookings = count($totalBookings);
        if ($teamBookings > $client->TeamEnabledCount) {
            $teamBookings = $client->TeamEnabledCount;
        }

        $saleProcessBookTeamDetails = calcSalesProcessRelatedStatus('book_team');
        $teamStepNumb               = $saleProcessBookTeamDetails['saleProcessStepNumb'];
        $this->saveTeamProgress($client, $teamStepNumb + $teamBookings - 1);
    }

    protected function saveTeamProgress($client, $saveUpto)
    {
        $saleProcessBookTeamDetails = calcSalesProcessRelatedStatus('book_team');
        $teamStepNumb               = $saleProcessBookTeamDetails['saleProcessStepNumb'];
        for ($i = 1; $i <= $client->TeamEnabledCount; $i++) {
            $this->deleteSalesProgress($teamStepNumb, $client->id);
            $teamStepNumb++;
        }

        $teamStepNumb = $saleProcessBookTeamDetails['saleProcessStepNumb'];
        for ($i = $teamStepNumb; $i <= $saveUpto; $i++) {
            $this->saveSalesProgress(['clientId' => $client->id, 'stepNumb' => $i]);
        }
    }

    /**
     * Calculate due date
     *
     * @param string $payPlan Payment Plan
     * @param date $startDate Start date of billing cycle
     *
     * @return date Due date
     */
    protected function calcDueDateWithoutProrate($payPlan, $startDate)
    {
        $startDateCarb = new Carbon($startDate);
        $plans         = memberShipPayPlans();
       
        $plan          = $plans[$payPlan];
        switch ($plan['unit']) {
            case 'week':
            case 'weeks':
                $startDateCarb->addWeeks($plan['amount']);
                break;
            case 'month':
            case 'months':
                $startDateCarb->addMonths($plan['amount']);
                break;
            case 'year':
                $startDateCarb->addYears($plan['amount']);
                break;
        }
        return $startDateCarb->toDateString();
    }

    /**
     * Calculate difference betyween two dates in specified units
     *
     * @param date $startDate First date
     * @param date $secondDate Second date
     * @param string $type Difference unit, i.e., week, month etc
     *
     * @return int Difference
     */
    protected function calcDiffBtwDate($firstDate, $secondDate, $type)
    {
        $firstDateCarb  = new Carbon($firstDate);
        $secondDateCarb = new Carbon($secondDate);

        if ($type == 'day') //If membership length is in days
        {
            return $firstDateCarb->diffInDays($secondDateCarb);
        } else if ($type == 'week') //If membership length is in weeks
        {
            return $firstDateCarb->diffInWeeks($secondDateCarb);
        } else if ($type == 'month') //If membership length is in months
        {
            return $firstDateCarb->diffInMonths($secondDateCarb);
        } else if ($type == 'year') //If membership length is in years
        {
            return $firstDateCarb->diffInYears($secondDateCarb);
        }

        return 0;
    }

    /**
     * Calculate EMI and class bookings allowed in chosen plan.
     *
     * @param collection $clientMember Client-Membership record
     * @param carbon $startDate Carbon instance of first date
     * @param carbon $dueDate Carbon instance of due date
     * @param array $data
     *
     * @return array EMI and class bookings allowed in chosen plan and various other data
     */
    protected function calcEmi($clientMember, $startDate, $dueDate, $data)
    {
        $returnData = [];

        if ($data['discAmt'] != 0) {
            $amnt = $data['discAmt'];
        } else if($clientMember->cm_original_price != null && $clientMember->cm_original_price != 0){
            $amnt = $clientMember->cm_signup_fee + $clientMember->cm_original_price;
        }else{
            $amnt = $clientMember->cm_signup_fee + $clientMember->cm_renw_amount;
        }

        $returnData['amnt'] = $amnt;
        
        // $classesInWeek = $clientMember->cm_class_limit_length;
        // if ($clientMember->cm_class_limit_type == 'every_month') {
        //     $classesInWeek = round($classesInWeek / 4.33);
        // }

        // $weekCost             = 0;
        // $classesAllowedInMemb = $classesAllowedInPlan = $classesInWeek;
        // switch ($clientMember->cm_validity_type) {
        //     case 'day':
        //         $weekCost             = $amnt * 7;
        //         $classesAllowedInMemb = 1;
        //         break;
        //     case 'month':
        //         $weekCost             = $amnt / 4.33333333; 
        //         $classesAllowedInMemb = round($classesInWeek * 4.33333333);
        //         break;
        //     case 'year':
        //         $weekCost             = $amnt / 52;
        //         $classesAllowedInMemb = $classesInWeek * 52;
        //         break;
        //     default:
        //         $weekCost = $amnt;
        //         break;
        // }
        // $classesAllowedInMemb *= $clientMember->cm_validity_length;

        // $emi = $weekCost;
        // switch ($clientMember->cm_pay_plan) {
        //     case 'fortnight':
        //         $emi *= 2;
        //         $classesAllowedInPlan = $classesInWeek * 2;
        //         break;
        //     case 'month':
        //         $emi                  = $emi * 4.33333333;
        //         $classesAllowedInPlan = round($classesInWeek * 4.33333333);
        //         break;
        //     case '3month':
        //         $emi *= 13;
        //         $classesAllowedInPlan = $classesInWeek * 13;
        //         break;
        //     case '6month':
        //         $emi *= 26;
        //         $classesAllowedInPlan = $classesInWeek * 26;
        //         break;
        //     case 'year':
        //         $emi *= 52;
        //         $classesAllowedInPlan = $classesInWeek * 52;
        //         break;
        // }
        $emi = 0;
        switch ($clientMember->cm_pay_plan) {
            case 'week':
                $emi = $amnt * 12/52 ;
                break;
            case 'fortnight':
                $emi = $amnt * 12/26 ;
                break;
            case 'month':
                $emi = $amnt;
                break;
            case '3month':
                $emi = $amnt * 3;
                break;
            case '6month':
                $emi = $amnt * 6;
                break;
            case 'year':
                $emi = $amnt * 12;
                break;
        }
        $returnData['emi']                  = round($emi, 2);
        return $returnData;
    }

    /**
     * Calculate EMI and class bookings allowed in chosen plan.
     *
     * @param collection $clientMember Client-Membership record
     * @param carbon $startDate Carbon instance of first date
     * @param carbon $dueDate Carbon instance of due date
     * @param array $data
     *
     * @return array EMI and class bookings allowed in chosen plan and various other data
     */
    protected function classPerAmount($clientMember, $startDate, $dueDate, $data)
    {
        $returnData = [];
        $amnt = $data['membTotal'];
        $returnData['amnt'] = $amnt;
        
        $classesInWeek = $clientMember->cm_class_limit_length;
        if ($clientMember->cm_class_limit_type == 'every_month') {
            $classesInWeek = round($classesInWeek / 4.33);
        }

        $weekCost             = 0;
        $classesAllowedInMemb = $classesAllowedInPlan = $classesInWeek;
        switch ($clientMember->cm_validity_type) {
            case 'day':
                $weekCost             = $amnt * 7;
                $classesAllowedInMemb = 1;
                break;
            case 'month':
                $weekCost             = $amnt / 4.33333333; 
                $classesAllowedInMemb = round($classesInWeek * 4.33333333);
                break;
            case 'year':
                $weekCost             = $amnt / 52;
                $classesAllowedInMemb = $classesInWeek * 52;
                break;
            default:
                $weekCost = $amnt;
                break;
        }
        $classesAllowedInMemb *= $clientMember->cm_validity_length;

        $emi = $weekCost;
        switch ($clientMember->cm_pay_plan) {
            case 'fortnight':
                $emi *= 2;
                $classesAllowedInPlan = $classesInWeek * 2;
                break;
            case 'month':
                $emi                  = $emi * 4.33333333;
                $classesAllowedInPlan = round($classesInWeek * 4.33333333);
                break;
            case '3month':
                $emi *= 13;
                $classesAllowedInPlan = $classesInWeek * 13;
                break;
            case '6month':
                $emi *= 26;
                $classesAllowedInPlan = $classesInWeek * 26;
                break;
            case 'year':
                $emi *= 52;
                $classesAllowedInPlan = $classesInWeek * 52;
                break;
        }
      
       
       
        $returnData = $weekCost  / $classesInWeek;
        return $returnData;
    }


    /**
     * Get Next EMI
     *
     * @param collection $clientMember Client-Membership record
     *
     * @return decimal Next EMI
     */
    protected function nextEmi($clientMember, $emisPaid = 1, $totalAmount = 0)
    {
        #TODO - first check if membership is being changed from next
        // $membership = Membership::where('id', $clientMember->cm_membership_id)->first();
        if($totalAmount != 0){
             $discountAmount = $totalAmount;
        }
        //if not next emi calculate depending on discounted amount duration setting
        $emiData = $this->calcEmi($clientMember, $clientMember->cm_due_date, $clientMember->cm_due_date, ['discAmt' => $discountAmount && ($clientMember->cm_discount_dur == -1 || $emisPaid < $clientMember->cm_discount_dur) ? $discountAmount : 0]);
        return $emiData['emi'];
    }

    /**
     * Update membership id of bookings
     *
     * @param collection $memb Old membership
     * @param int $clientId Client id
     * @param int $newMembId New membership id
     * @param date $insertRepeatUpto Date upto which insert never ending bookings. Optional.
     *
     */
    protected function updateFutureBookingsMembership($memb, $clientId, $newMembId, $insertRepeatUpto = 0, $deleteRecureClasses = [], $updateOpt = '', $deleteRecureServices = [], $serviceDay= []) 
    {

        $clientMembDueDate = $memb ? new Carbon($memb->cm_due_date) : null;

        /* Reset membership limit */
        $this->membershipLimitResetOnMembershipChange($clientId);
        /* Get clients details */
        // $clients = Clients::findOrFailClient($clientId);
        $clients = Clients::find($clientId);
        $clientMembershipLimit = ClientMemberLimit::where('cme_client_id', $clientId)->first();
       
        $futureServ = $clients->futureAppointments;

        /* Sort future services */
        $futureServ = $futureServ->sort(function ($firstEvent, $secondEvent) {
            if ($firstEvent->eventDate === $secondEvent->eventDate) {
                if ($firstEvent->eventTime === $secondEvent->eventTime) {
                    return 0;
                }
                return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
            }
            return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
        });

        if ($futureServ->count()) {
            // $servMembershipLimit = collect();
            // $servMembershipLimit = ClientMemberLimit::find($clientId);
            foreach ($futureServ as $service) {
                // if($service->sess_with_invoice == 0){
                if($service->deleted_at != null && $service->sess_cmid != 0 && ($service->sess_if_make_up == 1 || ($service->sess_event_log && strripos($service->sess_event_log, 'epic credit')))) {
                    $serviceLimit = json_decode($memb->cm_services_limit, 1);
                    $limit_type   = $serviceLimit[$service->sess_service_id]['limit_type'];
                    $servMembership = $this->updateClientMembershipLimitLocaly($clientMembershipLimit, $clientId, ['type' => 'service', 'action' => 'add', 'eventId' => $service->sess_service_id, 'date' => $service->sess_date, 'limit_type' => $limit_type]);

                    $clientMembershipLimit = $servMembership;
                    // $servMembershipLimit->save();

                } else if ($service->deleted_at == null && $service->sess_cmid != 0) {
                    if(count($deleteRecureServices)){
                        $day = Carbon::parse($service->sess_start_datetime)->format('D');
                        
                        if(in_array($service->sess_service_id,$deleteRecureServices) && in_array($day, $serviceDay)){
                            $additionalHistoryText = 'while changing membership';
                        
                            # Creating history text
                            $historyText = $this->eventclassClientHistory(['clients' => [$clients], 'action' => 'remove', 'additional' => $additionalHistoryText]);
    
                            if ($historyText) {
                                $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $service]);
                            }
    
                            StaffEventSingleService::where('sess_id', $service->sess_id)->where('sess_client_id', $clientId)->update(['deleted_at' => createTimestamp(), 'sess_event_log' => 'Deleted on new membership apply', 'sess_action_performed_by' => getLoggedUserName()]);
                            /* Manage repeat data */
                            $repeat = $service->repeat()->first();
                            $previousEvent = StaffEventSingleService::whereDate('sess_date', '<', $service->sess_date)->where('sess_sessr_id', $repeat['sessr_id'])->orderBy('sess_id', 'desc')->first();
                            if (count($previousEvent) && $repeat) {
                                $daysRepeat = json_decode($repeat->sessr_repeat_week_days);
                                if($repeat->sessr_repeat_end_on_date == null){
                                    $repeat->sessr_repeat_end             = 'On';
                                    $repeat->sessr_repeat_end_after_occur = 0;
                                    $repeat->sessr_repeat_end_on_date     = $previousEvent->sess_date;
                                    
                                    $repeat->update();
                                }
                            }else{
                                $daysRepeat = json_decode($repeat->sessr_repeat_week_days);
                                if(count($daysRepeat) > 1  && $repeat){
                                    if($repeat->sessr_repeat_end_on_date == null){
                                    $repeat->sessr_repeat_end             = 'On';
                                    $repeat->sessr_repeat_end_after_occur = 0;
                                    $repeat->sessr_repeat_end_on_date     = $previousEvent->sess_date;
                                    
                                    $repeat->update();
                                    }
                                }else{
                                    if($repeat)
                                    $repeat->delete();
                                }
                            } 
                        }

                    }else{
                         $membership = $this->satisfyMembershipRestrictions($clientId, ['event_type' => 'service', 'event_id' => $service->sess_service_id, 'event_date' => $service->sess_date], $clientMembershipLimit);

                        if ($membership['satisfy']) {
                            StaffEventSingleService::where('sess_id', $service->sess_id)->update(['sess_cmid' => $newMembId]);

                            $clientMembershipLimit = $this->updateClientMembershipLimitLocaly($membership['clientMembLimit'], $clientId, ['type' => 'service', 'action' => 'add', 'eventId' => $service->sess_service_id, 'date' => $service->sess_date, 'limit_type' => $membership['limit_type']]);

                            $clientMembershipLimit = $servMembership;
                            // $servMembershipLimit->save();
                        } else {
                            $additionalHistoryText = 'while changing membership';

                            # Creating history text
                            $historyText = $this->eventclassClientHistory(['clients' => [$clients], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                            if ($historyText) {
                                $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $service]);
                            }

                            StaffEventSingleService::where('sess_id', $service->sess_id)->where('sess_client_id', $clientId)->update(['deleted_at' => createTimestamp(), 'sess_event_log' => 'Deleted on new membership apply', 'sess_action_performed_by' => getLoggedUserName()]);
                            /* Manage repeat data */
                            $repeat = $service->repeat()->first();
                            $previousEvent = StaffEventSingleService::whereDate('sess_date', '<', $service->sess_date)->where('sess_sessr_id', $repeat['sessr_id'])->orderBy('sess_id', 'desc')->first();
                            if (count($previousEvent)) {
                                if($repeat->sessr_repeat_end_on_date == null){
                                    $repeat->sessr_repeat_end             = 'On';
                                    $repeat->sessr_repeat_end_after_occur = 0;
                                    $repeat->sessr_repeat_end_on_date     = $previousEvent->sess_date;
                                    $repeat->update();
                                }
                            } else {
                                if($repeat)
                                $repeat->delete();
                            }
                        }
                    }
                }

                // if (count($servMembershipLimit)) 
                //     $servMembershipLimit->save();
            }
        }
        /* end: Update membership id for future service bookings */

        /* start: Managing client in future class bookings */
        $membChangeableClasses = $clients->futureClasses;

        /* Sort future classes*/
        $membChangeableClasses = $membChangeableClasses->sort(function ($firstEvent, $secondEvent) {
            if ($firstEvent->eventDate === $secondEvent->eventDate) {
                if ($firstEvent->eventTime === $secondEvent->eventTime) {
                    return 0;
                }

                return $firstEvent->eventTime < $secondEvent->eventTime ? -1 : 1;
            }
            return $firstEvent->eventDate < $secondEvent->eventDate ? -1 : 1;
        });

        if ($membChangeableClasses->count()) {
            // $clientMembershipLimit = collect();
            // $clientMembershipLimit = ClientMemberLimit::find($clientId);

            foreach ($membChangeableClasses as $futureClas) {
                if($futureClas->pivot->deleted_at != null && $futureClas->pivot->secc_cmid != 0 && ($futureClas->pivot->secc_if_make_up_created == 1 || ($futureClas->pivot->secc_event_log && strripos($futureClas->pivot->secc_event_log, 'epic credit')))) {

                    $classLimit = json_decode($memb->cm_classes, 1);
                    $limit_type   = $memb->cm_class_limit_type;

                    $membershipLimit = $this->updateClientMembershipLimitLocaly($clientMembershipLimit, $clientId, ['type' => 'class', 'action' => 'add', 'eventId' => $futureClas->sec_class_id, 'date' => $futureClas->sec_date, 'limit_type' => $limit_type]);
                    $clientMembershipLimit = $membershipLimit;
                    // $clientMembershipLimit->save();

                } else if($futureClas->pivot->deleted_at == null && $futureClas->pivot->secc_cmid != 0) {
                    if ($updateOpt == 1 && $futureClas->pivot->secc_if_recur == 1 && (count($deleteRecureClasses) && in_array($futureClas->sec_secr_id, $deleteRecureClasses))) {

                        $additionalHistoryText = 'while changing membership';
                        
                        # Creating history text
                        $historyText = $this->eventclassClientHistory(['clients' => [$clients], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                        if ($historyText) {
                                $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureClas]);
                        }

                        /* For membership update with pro rate */
                        DB::table('staff_event_class_clients')->where('secc_client_id', $clientId)->where('secc_sec_id', $futureClas->sec_id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on new membership apply.', 'secc_action_performed_by' => getLoggedUserName()]);
                        /* remove client from class event table */
                        if ($futureClas->sec_secr_id != 0) {
                            $repeat         = $futureClas->repeat()->first();
                            $removeClientId = $clientId;
                            if (count($repeat) && $repeat->secr_client_id != '') {
                                $clientsReccur = json_decode($repeat->secr_client_id, true);
                                if (count($clientsReccur)) {
                                    foreach ($clientsReccur as $key => $value) {
                                        if ($key == $removeClientId) {
                                            unset($clientsReccur[$key]);
                                        }
                                    }
                                }
                                if (count($clientsReccur)) {
                                    $repeat->secr_client_id = json_encode($clientsReccur);
                                } else {
                                    $repeat->secr_client_id = '';
                                }
                                $repeat->save();
                            }
                        }
                        // Confirm Waiting Clients
                        $clients_to_auto_confirmId = [];
                        $confirmed_clientsCount    = 0;
                        foreach ($futureClas->clientsOldestFirst as $clientsOldestFirst) {
                            if ($clientsOldestFirst->id != $clientId) {
                                if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                    $confirmed_clientsCount++;
                                } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $futureClas->sec_capacity) {
                                    $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                    $confirmed_clientsCount++;
                                }
                            }
                        }
                        if (count($clients_to_auto_confirmId)) {
                            DB::table('staff_event_class_clients')->where('secc_sec_id', $futureClas->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                        }

                    } else if ($updateOpt == 2 && $clientMembDueDate && $futureClas->sec_date >= $clientMembDueDate->toDateString() && $futureClas->pivot->secc_if_recur == 1 && (count($deleteRecureClasses) && in_array($futureClas->sec_secr_id, $deleteRecureClasses))) {

                        $additionalHistoryText = 'while changing membership';
                        
                        # Creating history text
                        $historyText = $this->eventclassClientHistory(['clients' => [$clients], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                        if ($historyText) {
                                $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureClas]);
                        }

                        /* For membership update with next cycle*/
                        DB::table('staff_event_class_clients')->where('secc_client_id', $clientId)->where('secc_sec_id', $futureClas->sec_id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on new membership apply.', 'secc_action_performed_by' => getLoggedUserName()]);
                        /* remove client from class event table */
                        if ($futureClas->sec_secr_id != 0) {
                            $repeat         = $futureClas->repeat()->first();
                            $removeClientId = $clientId;
                            if (count($repeat) && $repeat->secr_client_id != '') {
                                $clientsReccur = json_decode($repeat->secr_client_id, true);
                                if (count($clientsReccur)) {
                                    foreach ($clientsReccur as $key => $value) {
                                        if ($key == $removeClientId) {
                                            unset($clientsReccur[$key]);
                                        }
                                    }
                                }
                                if (count($clientsReccur)) {
                                    $repeat->secr_client_id = json_encode($clientsReccur);
                                } else {
                                    $repeat->secr_client_id = '';
                                }
                                $repeat->save();
                            }
                        }
                        // Confirm Waiting Clients
                        $clients_to_auto_confirmId = [];
                        $confirmed_clientsCount    = 0;
                        foreach ($futureClas->clientsOldestFirst as $clientsOldestFirst) {
                            if ($clientsOldestFirst->id != $clientId) {
                                if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                    $confirmed_clientsCount++;
                                } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $futureClas->sec_capacity) {
                                    $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                    $confirmed_clientsCount++;
                                }
                            }
                        }
                        if (count($clients_to_auto_confirmId)) {
                            DB::table('staff_event_class_clients')->where('secc_sec_id', $futureClas->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                        }

                    } else {
                        $membership = $this->satisfyMembershipRestrictions($clientId, [
                            'event_type' => 'class', 'event_id' => $futureClas->sec_class_id, 'event_date' => $futureClas->sec_date], $clientMembershipLimit);

                        if ($membership['satisfy']) {
                            //Membership restrictions get satisfied
                            $updateMemb[]    = $futureClas->sec_id;
                            $membershipLimit = $this->updateClientMembershipLimitLocaly($membership['clientMembLimit'], $clientId, ['type' => 'class', 'action' => 'add', 'eventId' => $futureClas->sec_class_id, 'date' => $futureClas->sec_date, 'limit_type' => $membership['limit_type']]);

                            $clientMembershipLimit = $membershipLimit;

                            DB::table('staff_event_class_clients')->where('secc_client_id', $clientId)->where('secc_sec_id', $futureClas->sec_id)->update(array('secc_cmid' => $newMembId, 'updated_at' => createTimestamp()));
                        } else {
                            if( $futureClas->pivot->secc_class_extra == 1){
                                $clientMember = ClientMember::where('cm_client_id', $clientId)->where('cm_status', 'Active')->orderBy('id', 'desc')->whereNull('deleted_at')->first();
                           
                                $session_limits = json_decode($clientMember->cm_session_limit, 1);
                                $ifDelete = false;
                                if(count($session_limits)){
                                    foreach($session_limits as $key => $value){
                                        $clasData = Clas::OfBusiness()->where('cl_clcat_id', $key)->pluck('cl_id')->toArray();
                                        $sessionId = $futureClas->sec_class_id;
                                        if(in_array($sessionId, $clasData)){
                                            if($value['limit_type'] == 'every_week'){
                                                $carbonDate    = Carbon::createFromFormat('Y-m-d', $futureClas->sec_date);
                                                $weekStartDate = $carbonDate->copy()->startOfWeek();
                                                $weekEndDate   = $carbonDate->copy()->endOfWeek();
                                                $staffEventClassCount =  StaffEventClass::whereBetween('sec_date',[$weekStartDate,  $weekEndDate])->whereHas('clas.cat', function($q) use($key){
                                                    $q->where('clcat_id',$key);
                                                })->whereHas('clientsRaw', function($q) use($clientId){ $q->where('secc_client_id', $clientId)->where('secc_class_extra',1)->where(function($query){
                                                $query->whereNull('staff_event_class_clients.deleted_at')
                                                ->orWhere(function($qu){
                                                    $qu->where('secc_if_make_up_created',1)
                                                    ->where('staff_event_class_clients.deleted_at','!=',null);
                                                    });
                                                });
                                                })->count();
                                                if($staffEventClassCount > $value['limit']){
                                                $ifDelete = true;
                                                }
                                            }else if($value['limit_type'] == 'every_month'){
                                                $carbonDate    = Carbon::createFromFormat('Y-m-d', $futureClas->sec_date);
                                                $monthStartDate = $carbonDate->copy()->startOfMonth();
                                                $monthEndDate   = $carbonDate->copy()->endOfMonth();
                                                $staffEventClassCount =  StaffEventClass::whereBetween('sec_date',[$monthStartDate,  $monthEndDate])->whereHas('clas.cat', function($q) use($key){
                                                    $q->where('clcat_id',$key);
                                                })->whereHas('clientsRaw', function($q) use($clientId){ $q->where('secc_client_id', $clientId)->where('secc_class_extra',1)->where(function($query){
                                                    $query->whereNull('staff_event_class_clients.deleted_at')
                                                    ->orWhere(function($qu){
                                                        $qu->where('secc_if_make_up_created',1)
                                                        ->where('staff_event_class_clients.deleted_at','!=',null);
                                                    });
                                                });
                                                })->count();
                                                if($staffEventClassCount > $value['limit']){
                                                    $ifDelete = true;
                                                }
                                            }else{
                                                $carbonDate    = Carbon::createFromFormat('Y-m-d', $futureClas->sec_date);
                                                $year =   $carbonDate->copy()->format('Y');
                                                $weekDate = $carbonDate->copy();
                                                // dd($weekDate);
                                    
                                                $weekNo = $weekDate->weekOfYear;
                                                $weekRemainder = $weekNo % 2;
                                                if($weekRemainder == 1){
                                                    $startFortnightWeek = $carbonDate->copy()->startOfWeek();
                                                    $currentDate = Carbon::now(); 
                                                    $currentDate->setISODate($year, $weekNo+1); 
                                                    $endFortnightWeek = $currentDate->endOfWeek(); 
                                                
                                                }else{
                                                    $currentDate = Carbon::now();
                                                    $currentDate->setISODate($year, $weekNo-1);
                                                    $startFortnightWeek = $currentDate->startOfWeek();
                                                    $endFortnightWeek   = $carbonDate->copy()->endOfWeek();
                                                }
                                                $staffEventClassCount =  StaffEventClass::whereBetween('sec_date',[$startFortnightWeek,  $endFortnightWeek])->whereHas('clas.cat', function($q) use($key){
                                                    $q->where('clcat_id',$key);
                                                })->whereHas('clientsRaw', function($q) use($clientId){ $q->where('secc_client_id', $clientId)->where('secc_class_extra',1)->where(function($query){
                                                    $query->whereNull('staff_event_class_clients.deleted_at')
                                                    ->orWhere(function($qu){
                                                        $qu->where('secc_if_make_up_created',1)
                                                        ->where('staff_event_class_clients.deleted_at','!=',null);
                                                    });
                                                });
                                                })->count();
                                                if($staffEventClassCount > $value['limit']){
                                                    $ifDelete = true;
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    $ifDelete = true;
                                }
                                if($ifDelete){
                                    $additionalHistoryText = 'while changing membership';
                                
                                    # Creating history text
                                    $historyText = $this->eventclassClientHistory(['clients' => [$clients], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                                    if ($historyText) {
                                            $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureClas]);
                                    }

                                    DB::table('staff_event_class_clients')->where('secc_client_id', $clientId)->where('secc_sec_id', $futureClas->sec_id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on new membership apply.', 'secc_action_performed_by' => getLoggedUserName()]);
                                    /* remove client from class event table */
                                    if ($futureClas->sec_secr_id != 0) {
                                        $repeat         = $futureClas->repeat()->first();
                                        $removeClientId = $clientId;
                                        if (count($repeat) && $repeat->secr_client_id != '') {
                                            $clientsReccur = json_decode($repeat->secr_client_id, true);
                                            if (count($clientsReccur)) {
                                                foreach ($clientsReccur as $key => $value) {
                                                    if ($key == $removeClientId) {
                                                        unset($clientsReccur[$key]);
                                                    }
                                                }
                                            }
                                            if (count($clientsReccur)) {
                                                $repeat->secr_client_id = json_encode($clientsReccur);
                                            } else {
                                                $repeat->secr_client_id = '';
                                            }
                                            $repeat->save();
                                        }
                                    }
                                    // Confirm Waiting Clients
                                    $clients_to_auto_confirmId = [];
                                    $confirmed_clientsCount    = 0;
                                    foreach ($futureClas->clientsOldestFirst as $clientsOldestFirst) {
                                        if ($clientsOldestFirst->id != $clientId) {
                                            if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                                $confirmed_clientsCount++;
                                            } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $futureClas->sec_capacity) {
                                                $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                                $confirmed_clientsCount++;
                                            }
                                        }
                                    }
                                    if (count($clients_to_auto_confirmId)) {
                                        DB::table('staff_event_class_clients')->where('secc_sec_id', $futureClas->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                                    }
                                }
                            }else{
                                $additionalHistoryText = 'while changing membership';
                            
                                # Creating history text
                                $historyText = $this->eventclassClientHistory(['clients' => [$clients], 'action' => 'remove', 'additional' => $additionalHistoryText]);

                                if ($historyText) {
                                        $this->ammendHistory(['text' => rtrim($historyText, "|"), 'event' => $futureClas]);
                                }

                                DB::table('staff_event_class_clients')->where('secc_client_id', $clientId)->where('secc_sec_id', $futureClas->sec_id)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on new membership apply.', 'secc_action_performed_by' => getLoggedUserName()]);
                                /* remove client from class event table */
                                if ($futureClas->sec_secr_id != 0) {
                                    $repeat         = $futureClas->repeat()->first();
                                    $removeClientId = $clientId;
                                    if (count($repeat) && $repeat->secr_client_id != '') {
                                        $clientsReccur = json_decode($repeat->secr_client_id, true);
                                        if (count($clientsReccur)) {
                                            foreach ($clientsReccur as $key => $value) {
                                                if ($key == $removeClientId) {
                                                    unset($clientsReccur[$key]);
                                                }
                                            }
                                        }
                                        if (count($clientsReccur)) {
                                            $repeat->secr_client_id = json_encode($clientsReccur);
                                        } else {
                                            $repeat->secr_client_id = '';
                                        }
                                        $repeat->save();
                                    }
                                }
                                // Confirm Waiting Clients
                                $clients_to_auto_confirmId = [];
                                $confirmed_clientsCount    = 0;
                                foreach ($futureClas->clientsOldestFirst as $clientsOldestFirst) {
                                    if ($clientsOldestFirst->id != $clientId) {
                                        if ($clientsOldestFirst->pivot->secc_client_status == 'Confirm') {
                                            $confirmed_clientsCount++;
                                        } else if ($clientsOldestFirst->pivot->secc_client_status == 'Waiting' && $confirmed_clientsCount < $futureClas->sec_capacity) {
                                            $clients_to_auto_confirmId[] = $clientsOldestFirst->id;
                                            $confirmed_clientsCount++;
                                        }
                                    }
                                }
                                if (count($clients_to_auto_confirmId)) {
                                    DB::table('staff_event_class_clients')->where('secc_sec_id', $futureClas->sec_id)->whereIn('secc_client_id', $clients_to_auto_confirmId)->update(array('secc_client_status' => 'Confirm', 'updated_at' => createTimestamp()));
                                }
                            }
                        }
                    }
                }
            }
        }
        /* end: Managing client in future class bookings */

        if (count($clientMembershipLimit)) 
            $clientMembershipLimit->save();

        $clientMemLimit = ClientMemberLimit::where('cme_client_id', $clientId)->first();
        $years          = [2018, 2019, 2020, 2021, 2022, 2023, 2024];
        if ($clientMemLimit) {
            $weeklyClasses_limit = json_decode($clientMemLimit->cme_classes_weekly, 1);
            foreach ($years as $year) {
                if ($weeklyClasses_limit && !array_key_exists($year, $weeklyClasses_limit)) {
                    $weeklyClasses_limit[$year] = [];
                }

            }
            if ($weeklyClasses_limit) {
                for ($i = 1; $i <= 53; $i++) {
                    $allWeek[$i] = 0;
                }

                foreach ($weeklyClasses_limit as $key => $value1) {
                    $insideArray[$key] = $value1 + $allWeek;
                }

                $clientMemLimit->cme_classes_weekly = json_encode($insideArray);
                $clientMemLimit->save();
            }
        }

        # Set info log
        setInfoLog('Client future membership limit updated',  $clientId);
    }

    /**
     * Reset all memebrship of given client
     *
     * @param Int ClientId
     * @return void
     */
    protected function membershipLimitResetOnMembershipChange($clientId)
    {
        $today  = Carbon::now()->toDateString();
        $client = Clients::where('id', $clientId)->first();
        // dd($client);
        if (count($client)) {
           $updatedLimit = collect();
           $existLimit   = ClientMemberLimit::where('cme_client_id', $clientId)->first();
            // dd($existLimit->toArray());
            if (count($existLimit)) {
                ClientMemberLimit::where('cme_client_id', $clientId)->forcedelete();
                # Set info log
                setInfoLog('Client membership limit reset to empty', $clientId);
            }

            $clientMember = Clients::paidMembership($clientId);
            // dd($clientMember);
            // if($clientMember && $clientMember->cm_services_limit != ''){
            if ($clientMember && $clientMember->cm_services_limit != '' && $clientMember->cm_services_limit != '[]') {
                $serviceEvent = StaffEventSingleService::where('sess_client_id', $clientId)->whereDate('sess_date', '<', $today)/*->whereNull('deleted_at')*/->get();

                if (count($serviceEvent)) {
                    foreach ($serviceEvent as $service) {
                        $serviceLimit = json_decode($clientMember->cm_services_limit, 1);
                        // if($service->sess_with_invoice == 0 && array_key_exists($service->sess_service_id, $serviceLimit)){
                        if ($service->deleted_at != null && $service->sess_cmid != 0 && array_key_exists($service->sess_service_id, $serviceLimit) && ($service->sess_if_make_up == 1 || ($service->sess_event_log && strripos($service->sess_event_log, 'epic credit')))) {

                            $limit_type   = $serviceLimit[$service->sess_service_id]['limit_type'];
                            $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'service', 'action' => 'add', 'date' => $service->sess_date, 'eventId' => $service->sess_service_id, 'limit_type' => $limit_type]);

                        } else if ($service->deleted_at == null && $service->sess_cmid != 0 && array_key_exists($service->sess_service_id, $serviceLimit)) {

                            $limit_type   = $serviceLimit[$service->sess_service_id]['limit_type'];
                            $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'service', 'action' => 'add', 'date' => $service->sess_date, 'eventId' => $service->sess_service_id, 'limit_type' => $limit_type]);
                        }
                    }
                }
            }

            if ($clientMember && $clientMember->cm_classes != '') {
                # Get membership related classes
                $membership = MemberShip::where('id', $clientMember->cm_membership_id)
                    ->select('id', 'me_business_id', 'me_membership_label')
                    ->with('classmember')
                    ->first();

                if ($membership && $membership->classmember->count()) {
                    $membershipClasses = [];
                    foreach ($membership->classmember as $clsMember) {
                        $membershipClasses[$clsMember->cl_id] = $clsMember->cl_name;
                    }

                    // $membershipClasses = implode(', ', $membershipClasses);
                }

                // $classEvent = $client->eventClasses()->whereDate('sec_start_datetime', '<' , $today)->get();
                $classEvent = $client->eventClasses()->whereDate('sec_start_datetime', '<', $today)->whereNull('staff_event_classes.deleted_at')/*->whereNull('staff_event_class_clients.deleted_at')*/->get();

                if (count($classEvent)) {
                    foreach ($classEvent as $cls) {
                        // $classLimit = json_decode($clientMember->cm_classes, 1);
                        $classLimit = $membershipClasses;
                        // if($cls->pivot->secc_with_invoice == 0  && array_key_exists($cls->sec_class_id, $classLimit)){
                        if ($cls->pivot->deleted_at != null && $cls->pivot->secc_cmid != 0 && array_key_exists($cls->sec_class_id, $classLimit) && ($cls->pivot->secc_if_make_up_created == 1 || ($cls->pivot->secc_event_log && strripos($cls->pivot->secc_event_log, 'epic credit')))) {

                            $limit_type   = $clientMember->cm_class_limit_type;
                            $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'class', 'action' => 'add', 'date' => $cls->sec_date, 'eventId' => $cls->sec_class_id, 'limit_type' => $limit_type]);

                        } else if ($cls->pivot->deleted_at == null && $cls->pivot->secc_cmid != 0 && array_key_exists($cls->sec_class_id, $classLimit)) {

                            $limit_type   = $clientMember->cm_class_limit_type;
                            $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'class', 'action' => 'add', 'date' => $cls->sec_date, 'eventId' => $cls->sec_class_id, 'limit_type' => $limit_type]);
                        }
                    }
                }
                // $updatedLimit->save();
            }

            if (count($updatedLimit)) {
                $updatedLimit->save();
            }

            $clientMemLimit = ClientMemberLimit::where('cme_client_id', $clientId)->first();
            $years          = [2018, 2019, 2020, 2021, 2022, 2023, 2024];
            if ($clientMemLimit) {
                $weeklyClasses_limit = json_decode($clientMemLimit->cme_classes_weekly, 1);
                foreach ($years as $year) {
                    if ($weeklyClasses_limit && !array_key_exists($year, $weeklyClasses_limit)) {
                        $weeklyClasses_limit[$year] = [];
                    }

                }
                // dd($weeklyClasses_limit);
                if ($weeklyClasses_limit) {
                    for ($i = 1; $i <= 53; $i++) {
                        $allWeek[$i] = 0;
                    }

                    foreach ($weeklyClasses_limit as $key => $value1) {
                        $insideArray[$key] = $value1 + $allWeek;
                    }

                    $clientMemLimit->cme_classes_weekly = json_encode($insideArray);
                    $clientMemLimit->save();
                }
            }
        }

        # Set info log
        setInfoLog('Client past membership limit updated', $clientId);
    }

    /**
     * Return histroy text according to action
     * @param
     * @return
     */
    protected function calcHistoryTextFromAction($action, $additional = "", $isClientRecure = "")
    {
        if ($action == 'add') {
            if ($isClientRecure && $isClientRecure == true) {
                return ' was added recurring to class.';
            } else {
                return ' was added to the class' . ($additional ? " $additional" : "") . '.';
            }

        } else {
            return ' was removed from the class' . ($additional ? " $additional" : "") . '.';
        }

    }

    /**
     * Calculate number of allowed classes in a membership between two dates
     *
     * @param collection $clientMember Client-Membership record
     * @param carbon $firstDateCarb Carbon instance of first date
     * @param carbon $secondDateCarb Carbon instance of second date
     *
     * @return int Allowed classes
     */
    protected function calcAllowedClasses($clientMember, $firstDateCarb, $secondDateCarb)
    {
        if ($clientMember->cm_class_limit_type == 'every_week') //If limit is weekly
        {
            $membPeriod = $firstDateCarb->diffInWeeks($secondDateCarb);
        } else if ($clientMember->cm_class_limit_type == 'every_month') //If limit is monthly
        {
            $membPeriod = $firstDateCarb->diffInMonths($secondDateCarb);
        }

        if (!$membPeriod) {
            $membPeriod = 1;
        }

        $classLimit = $clientMember->cm_class_limit_length * $membPeriod;

        return $classLimit;
        //return min($classLimit, /*$clientMember->cm_enrollment_limit*/1);
    }

    /**
     * Manage due date and end date cycle of client-membership
     *
     * @param collection $selectedMemberShip Client-Membership record
     *
     * @return int Allowed classes
     */
    protected function manageClientMemb($selectedMemberShip)
    {
        $endDateCarb = new Carbon($selectedMemberShip->cm_end_date);
        $dueDateCarb = new Carbon($selectedMemberShip->cm_due_date);
        if ($dueDateCarb->lte($endDateCarb)) {
            //Due date comes first than end date
            $smallKase = 'dueDate';
            $smallDate = $selectedMemberShip->cm_due_date;
        } else {
            //Membership expires first
            $smallKase = 'endDate';
            $smallDate = $selectedMemberShip->cm_end_date;
        }
        if ($smallDate->lte(new Carbon())) {
            if ($smallKase == 'dueDate') {
                $selectedMemberShip = $this->dueDateExpired($selectedMemberShip);
            } else {
                $selectedMemberShip = $this->endDateExpired($selectedMemberShip);
            }
        }

        return $selectedMemberShip;
    }

    protected function dueDateExpired($membership, $useNextMemb = true)
    {
        if ($membership->cm_status == 'Active' || $membership->cm_status == 'Expired') {
            $nextMemb = $useNextMemb ? Clients::nextMembership($membership->cm_client_id) : $useNextMemb;
            if ($nextMemb) {
                //If next membership exist
                $this->updateFutureBookingsMembership( /*$membership->futureAppointments, $membership->membChangeableClasses*/$membership, $membership->cm_client_id, $nextMemb->id, $nextMemb->cm_start_date);
                $membership->cm_status = 'Expired';
                $membership->save();

                $nextMemb->cm_status = 'Active';
                $nextMemb->save();

                //$membership = $this->manageClientMemb($nextMemb);
            } else {
                $oldDueDate              = $membership->cm_due_date;
                $membership->cm_due_date = $this->calcDueDateWithoutProrate($membership->cm_pay_plan, $oldDueDate);

                $oldData = json_decode($membership->data, 1);
                $emiData = $this->calcEmi($membership, $oldDueDate, $membership->cm_due_date, ['discAmt' => array_key_exists('discAmt', $oldData) && $oldData['discAmt'] && $oldData['paid'] < $membership->cm_discount_dur ? $membership->cm_discounted_amount : 0]);
                ++$oldData['paid'];
                $oldData['classesAllowedInPlan'] = $emiData['classesAllowedInPlan'];
                $membership->data                = json_encode($oldData);

                $membership->cm_emi      = $emiData['emi'];
                $membership->cm_next_emi = $this->nextEmi($membership, $oldData['paid'] - 1);

                $membership->cm_status = 'Active';
                $membership->save();

                //$membership = $this->manageClientMemb($membership);

                /* Start: Create Invoice For membership */
                $invoiceData                = [];
                $invoiceData['dueDate']     = $membership->cm_start_date;
                $invoiceData['clientId']    = $membership->cm_client_id;
                $invoiceData['locationId']  = 0;
                $invoiceData['status']      = 'Unpaid';
                $invoiceData['productName'] = 'Renewal ' . $membership->cm_label . ' on ' . Carbon::now()->format('D, d M Y');
                $invoiceData['staffId']     = 0;
                $invoiceData['taxType']     = 'including';
                $invoiceData['price']       = $membership->cm_emi;
                $invoiceData['type']        = 'membership';
                $invoiceData['paymentType'] = 'Direct Debit';
                $invoiceData['productId']   = $membership->id;
                $this->autoCreateInvoice($invoiceData);
                /* End: Create Invoice For membership   */
            }
            $membership = $this->manageClientMemb($membership);
        }
        return $membership;
    }

    /**
     * Calculate end date of membership from a given date
     *
     * @param date $startDate Start date
     * @param string $validType Validity type, i.e., week, month etc
     * @param string $validLen Validity length, i.e., 1, 2 etc
     *
     * @return date End date
     */
    protected function calcEndDate($startDate, $validType, $validLen)
    {
        $startDateCarb = new Carbon($startDate);
        switch ($validType) {
            case 'day':
                $startDateCarb->addDays($validLen);
                break;
            case 'week':
                $startDateCarb->addWeeks($validLen);
                break;
            case 'month':
                $startDateCarb->addMonths($validLen);
                break;
            case 'year':
                $startDateCarb->addYears($validLen);
                break;
        }
        return $startDateCarb->subDays(1)->toDateString();
    }

    protected function endDateExpired($membership)
    {
        if (($membership->cm_status == 'Active' || $membership->cm_status == 'Expired') && $membership->cm_auto_renewal == 'on') {
            $nextMemb = Clients::nextMembership($membership->cm_client_id);
            //if(){ //If next membership exist
            $newMemberShip            = $nextMemb ? $nextMemb : $membership->replicate();
            $newMemberShip->cm_status = 'Active';
            $startDateCarb            = new Carbon($membership->cm_end_date);
            $startDateCarb->addDays(1);
            $newMemberShip->cm_start_date = $startDateCarb->toDateString();
            if (!$membership->cm_parent_id) {
                $newMemberShip->cm_parent_id = $membership->id;
            }

            $newMemberShip->cm_end_date = $this->calcEndDate($newMemberShip->cm_start_date, $newMemberShip->cm_validity_type, $newMemberShip->cm_validity_length);

            $oldData             = json_decode($membership->data, 1);
            $oldData['paid']     = 0;
            $newMemberShip->data = json_encode($oldData);

            $newMemberShip->save();

            $newMemberShipId  = $newMemberShip->id;
            $insertRepeatUpto = $newMemberShip->cm_start_date;
        } else {
            $newMemberShipId = $insertRepeatUpto = 0;
        }

        $this->updateFutureBookingsMembership($membership, $membership->cm_client_id, $newMemberShipId, $insertRepeatUpto);
        $membership->cm_status = 'Expired';
        $membership->save();

        if ($newMemberShipId) {
            $newMemberShip = $this->manageClientMemb($newMemberShip, false);
        }

        return ($newMemberShipId) ? $newMemberShip : $membership;
    }

    /**
     * Update client memebrship class and future event
     *
     * @param
     * @return
     */
    protected function updateFutureMembershipClass($removedClassIds, $memberShipId)
    {
        // echo('<pre>');
        // print_r($removedClassIds);
        // echo('</pre>');
        $eventUpdateData = array();
        $cmid            = array();
        $date            = Carbon::now()->toDateString();
        $memberShip      = \App\MemberShip::find($memberShipId);

        if ($memberShip) {
            $classes       = $memberShip->classmember->pluck('cl_name', 'cl_id')->toArray();
            $clientMembers = \App\ClientMember::where('cm_membership_id', $memberShipId)->where('cm_status', 'Active')->get();
            if ($clientMembers->count()) {
                foreach ($clientMembers as $clientMember) {
                    $cmid[]                                       = $clientMember->id;
                    $eventUpdateData[$clientMember->cm_client_id] = array('limit_type' => $clientMember->cm_class_limit_type);
                    $clientMember->cm_classes                     = json_encode($classes);
                    $clientMember->save();
                }
            }
        }

        if (count($removedClassIds) && count($eventUpdateData)) {
            DB::enableQueryLog();
            $clientIds    = array_keys($eventUpdateData);
            $eventClasses = StaffEventClass::whereDate('sec_date', '>', $date)
                ->whereIn('sec_class_id', $removedClassIds)
                ->select('sec_id', 'sec_date', 'sec_secr_id')
                ->whereHas('clients', function ($query) use ($cmid, $clientIds) {
                    $query->whereIn('staff_event_class_clients.secc_cmid', $cmid)
                        ->whereIn('staff_event_class_clients.secc_client_id', $clientIds);
                })
                ->get();

            if (count($eventClasses)) {
                $classIds          = array();
                $eventDates        = array();
                $updateExistRepeat = array();
                foreach ($eventClasses as $eventClass) {
                    $classIds[]   = $eventClass->sec_id;
                    $eventDates[] = $eventClass->sec_date;
                    if ($eventClass->sec_secr_id != 0 && !in_array($eventClass->sec_secr_id, $updateExistRepeat)) {
                        $updateExistRepeat[] = $eventClass->sec_secr_id;
                        $repeat              = $eventClass->repeat()->first();
                        if (count($repeat) && $repeat->secr_client_id != '') {
                            $clientsReccur = json_decode($repeat->secr_client_id, true);
                            if (count($clientsReccur)) {
                                foreach ($clientsReccur as $key => $value) {
                                    if (in_array($key, $clientIds)) {
                                        unset($clientsReccur[$key]);
                                    }

                                }
                            }
                            if (count($clientsReccur)) {
                                $repeat->secr_client_id = json_encode($clientsReccur);
                            } else {
                                $repeat->secr_client_id = '';
                            }

                            $repeat->save();
                        }
                    }
                    $removeClients    = $eventClass->clients()->whereIn('id', $clientIds)->get()->toArray();
                    $alertHistoryText = $this->classClientRemoveHistory(['clients' => $removeClients, 'action' => 'remove', 'additional' => 'memebrship update']);
                    $this->ammendHistory(['text' => rtrim($alertHistoryText, "|"), 'event' => $eventClass]);
                }

                DB::table('staff_event_class_clients')->whereIn('secc_sec_id', $classIds)->whereIn('secc_cmid', $cmid)->whereIn('secc_client_id', $clientIds)->update(['deleted_at' => createTimestamp(), 'secc_event_log' => 'Deleted on memebrship update.']);

                if (count($eventDates)) {
                    $this->reduceFutureEventMembership($eventUpdateData, $eventDates);
                }
            }
        }
    }

    /**
     * Update memebership limit
     *
     * @param Array $eventUpdateData[clientId=>array(limit_type=>every_week/every_month)]
     * @param Array $eventDates[dates]
     * @return void
     */
    protected function reduceFutureEventMembership($eventUpdateData, $eventDates)
    {
        $eventDates = $this->getEventMemberDates($eventDates);
        foreach ($eventUpdateData as $key => $eventUpdate) {
            $clientMemberLimit = \App\ClientMemberLimit::where('cme_client_id', $key)->first();
            foreach ($eventDates as $eventDate) {
                if ($eventUpdate['limit_type'] == 'every_week') {
                    $weekDateData = $this->getMemberDateDetails($eventDate, 'weekly');
                    $weekNo       = $weekDateData['index'];
                    $eventyear    = $weekDateData['year'];

                    if ($clientMemberLimit->cme_classes_weekly != '') {
                        $existData = json_decode($clientMemberLimit->cme_classes_weekly, 1);
                        if (array_key_exists($eventyear, $existData) && array_key_exists($weekNo, $existData[$eventyear])) {
                            if ($existData[$eventyear][$weekNo] > 0) {
                                $existData[$eventyear][$weekNo] = $existData[$eventyear][$weekNo] - 1;
                            }

                        }
                        $clientMemberLimit->cme_classes_weekly = json_encode($existData);
                    }
                } elseif ($eventUpdate['limit_type'] == 'every_month') {
                    $monthDateData = $this->getEventMemberDates($eventDate, 'monthly');
                    $monthNo       = $monthDateData['index'];
                    $eventyear     = $monthDateData['year'];

                    if ($clientMemberLimit->cme_classes_monthly != '') {
                        $existData = json_decode($clientMemberLimit->cme_classes_monthly, 1);
                        if (array_key_exists($eventyear, $existData) && array_key_exists($monthNo, $existData[$eventyear])) {
                            if ($existData[$eventyear][$monthNo] > 0) {
                                $existData[$eventyear][$monthNo] = $existData[$eventyear][$monthNo] - 1;
                            }

                        }
                        $clientMemberLimit->cme_classes_monthly = json_encode($existData);
                    }
                }
            }
            $clientMemberLimit->save();
        }
    }

    /**
     * Event updated dates
     *
     * @param Array $dates
     * @return Array $eventDates
     */
    protected function getEventMemberDates($dates)
    {
        $eventDates = array();
        foreach ($dates as $date) {
            if (gettype($date) == 'string') {
                $eventDates[] = Carbon::createFromFormat('Y-m-d', $date);
            } else {
                $eventDates[] = $date;
            }

        }

        return $eventDates;
    }

    /**
     * Date for indexing
     *
     * @param Carbon Date $dates
     * @param String $limitType
     * @return Array $dateDetails['year','month/week/fortnightly']
     */
    protected function getMemberDateDetails($date, $limitType)
    {
        $carbonDate          = $date->copy();
        $dateDetails         = array();
        $dateDetails['year'] = $carbonDate->year;
        if ($limitType == 'weekly') {
            $weekDate             = $carbonDate->copy();
            $dateDetails['index'] = $weekDate->weekOfYear;
        } elseif ($limitType == 'monthly') {
            $monthDate            = $carbonDate->copy();
            $dateDetails['index'] = $monthDate->month;
        } elseif ($limitType == 'fortnightly') {
            $fortnightDate    = $carbonDate->copy();
            $fortnightMonthNo = $fortnightDate->weekOfYear;
            if ($fortnightMonthNo % 2 == 0) {
                $dateDetails['index'] = $fortnightMonthNo;
            } else {
                $dateDetails['index'] = $fortnightMonthNo + 1;
            }

        }
        return $dateDetails;
    }

    /**
     * Create client remove history
     *
     * @param Array $data['additional','clients obj']
     * @return String client remove history text
     */
    protected function classClientRemoveHistory($data)
    {
        $text = '';
        if (!array_key_exists('additional', $data)) {
            $data['additional'] = "";
        }

        $subText = ' was removed from the class ' . $data['additional'] . '.';

        foreach ($data['clients'] as $client) {
            $text .= $client['firstname'] . ' ' . $client['lastname'] . $subText . '|';
        }

        return $text;
    }

    /**
     * Calculate service price according to membership validity
     *
     *
     *
     */
    protected function servicePriceAccOfType($membValidityType, $limitType, $cost)
    {
        if ($membValidityType == 'day') {
            switch ($limitType) {
                case 'every_month':
                    $cost = ($cost * 12) / 365;
                    break;
                case 'every_fortnight':
                    $cost = ($cost * 24.5) / 365;
                    break;
                case 'every_week':
                    $cost = ($cost / 7);
            }
        } else if ($membValidityType == 'week') {
            switch ($limitType) {
                case 'every_month':
                    $cost = $cost / 4.33;
                    break;
                case 'every_fortnight':
                    $cost = $cost / 2;
                    break;
                case 'every_week':
                    $cost = $cost;
            }
        } else if ($membValidityType == 'month') {
            switch ($limitType) {
                case 'every_month':
                    $cost = $cost;
                    break;
                case 'every_fortnight':
                    $cost = $cost * 2;
                    break;
                case 'every_week':
                    $cost = $cost * 4.33;
            }
        } else if ($membValidityType == 'year') {
            switch ($limitType) {
                case 'every_month':
                    $cost = $cost * 12;
                    break;
                case 'every_fortnight':
                    $cost = $cost * 24.5;
                    break;
                case 'every_week':
                    $cost = $cost * 52;
            }
        }
        return round($cost, 2);
    }

    /**
     *
     *
     *
     */
    protected function getServiceUnitPrice($serviceIds)
    {
        $serivesesData = \App\Service::whereIn('id', $serviceIds)->select('id', 'category', 'team_name', 'one_on_one_name', 'team_price', 'one_on_one_price')->get();
        $services      = array();
        if ($serivesesData->count()) {
            foreach ($serivesesData as $serivesData) {
                if ($serivesData->category == 1) {
                    // TEAM
                    $services[$serivesData->id] = $serivesData->team_price;
                } else if ($serivesData->category == 2) {
                    // 1 on 1
                    $services[$serivesData->id] = $serivesData->one_on_one_price;
                }
            }
        }
        return $services;
    }
}
