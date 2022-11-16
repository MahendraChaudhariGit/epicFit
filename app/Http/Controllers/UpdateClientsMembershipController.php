<?php

namespace App\Http\Controllers;

use App\ClientMember;
use App\ClientMemberLimit;
use App\Clients;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\StaffEventsTrait;
use App\Invoice;
use App\InvoiceItems;
use App\MemberShip;
use App\StaffEventClass;
use App\StaffEventSingleService;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Session;
use Storage;
use DateTime;
use DateTimeZone;

class UpdateClientsMembershipController extends Controller
{
    use ClientTrait, StaffEventsTrait, HelperTrait;

    # Update membership limit of all clients
    public function updateMembership()
    {
        $response = [];

        # Get all clients
        $remainingClients = Session::has('clientsRemaining') ? Session::get('clientsRemaining') : [];

        $clients = !empty($remainingClients) ? $remainingClients : Clients::pluck('id')->toArray();

        $clients = [1847];

        // dd($clients);

        if (empty($remainingClients)) {
            Session::put('clientsRemaining', $clients);
        }

        if (!empty($clients)) {
            foreach ($clients as $clientId) {
                $isCreateInvoice = false;
                $updateOpt       = 0;
                $client          = Clients::find($clientId);

                # Get current membership
                $prevMemb = $currMemb = $client->membership($client->id);
                // dd($prevMemb->toArray());

                if ($currMemb) {

                    if (!in_array($currMemb->cm_status, ['Expired', 'Removed'])) {
                        $updateOpt = 1;
                    }

                    $memberShip    = MemberShip::where('id', $currMemb->cm_membership_id)->first();
                    $currentTime   = Carbon::now();
                    $membStartDate = new Carbon($prevMemb->cm_start_date);
                    $membEndDate   = $membStartDate->copy()->addYears(5);

                    if ($updateOpt == 1) {
                        # Update membership
                        $currMemb->cm_status = 'Expired';
                        $currMemb->save();
                    }

                    if ($currMemb->cm_parent_id) {
                        ClientMember::where('id', $currMemb->cm_parent_id)->orWhere('cm_parent_id', $currMemb->cm_parent_id)->delete();
                    } else {
                        $currMemb->delete();
                    }

                    $isCreateInvoice = false;

                    # Create new client membership
                    $clientMember                    = new ClientMember;
                    $clientMember->cm_services_limit = $prevMemb->cm_services_limit;

                    # Update cm membership classes
                    $classes                             = $memberShip->classmember->pluck('cl_name', 'cl_id')->toArray();
                    $clientMember->cm_classes            = json_encode($classes);
                    $clientMember->cm_client_id          = $prevMemb->cm_client_id;
                    $clientMember->cm_membership_id      = $prevMemb->cm_membership_id;
                    $clientMember->cm_label              = $prevMemb->cm_label;
                    $clientMember->cm_validity_length    = $prevMemb->cm_validity_length;
                    $clientMember->cm_validity_type      = $prevMemb->cm_validity_type;
                    $clientMember->cm_class_limit        = $prevMemb->cm_class_limit;
                    $clientMember->cm_class_limit_length = $prevMemb->cm_class_limit_length;
                    $clientMember->cm_class_limit_type   = $prevMemb->cm_class_limit_type;
                    $clientMember->cm_auto_renewal       = $prevMemb->cm_auto_renewal;
                    $clientMember->cm_pay_plan           = $prevMemb->cm_pay_plan;
                    $clientMember->cm_prorate            = $prevMemb->cm_prorate;
                    $clientMember->cm_start_date         = $membStartDate->toDateTimeString();
                    $clientMember->cm_enrollment_limit   = $prevMemb->me_enrollment_limit;
                    $clientMember->cm_subscription_type  = 'manual';

                    /* start: Renewal Amount */
                    $clientMember->cm_renw_amount = $prevMemb->cm_renw_amount;
                    /* end: Renewal Amount */

                    $clientMember->cm_signup_fee = $prevMemb->cm_signup_fee;

                    $clientMember->cm_due_date = $this->calcDueDate($prevMemb->cm_pay_plan, $prevMemb->cm_due_date);
                    $clientMember->cm_end_date = $membEndDate->toDateTimeString();

                    $clientMember->cm_emi               = $prevMemb->cm_emi;
                    $clientMember->data                 = $prevMemb->data;
                    $taxPerc                            = $memberShip::calcTotalTax($memberShip);
                    $clientMember->cm_discounted_amount = $prevMemb->cm_discounted_amount;
                    $clientMember->cm_discount_dur      = $prevMemb->cm_discount_dur;
                    $clientMember->cm_payment_option    = $prevMemb->cm_payment_option;

                    if ($updateOpt == 1) {
                        #Update case of client-membership and Effect immediately

                        /* start: Adjusting remaing amount to emi of new client-membership */
                        // $clientMembrId = $currMemb->id;
                        $clientMembrId = $clientMember->id;

                        $invoice = \App\Invoice::where('inv_business_id', Session::get('businessId'))
                            ->where('inv_client_id', $clientId)
                            ->whereHas('invoiceitem', function ($query) use ($clientMembrId) {
                                $query->where('inp_product_id', $clientMembrId)
                                    ->where('inp_type', 'membership');
                            })
                            ->first();

                        // $fromDate = Carbon::parse($currMemb->cm_start_date);
                        $fromDate        = Carbon::parse($clientMember->cm_start_date);
                        $toDate          = Carbon::tomorrow();
                        $isUnpaidInvoice = false;
                        $isPaidInvoice   = false;
                        $invoiceGoesTo   = '';
                        if (count($invoice)) {
                            if ($fromDate->lt($toDate)) {
                                $days      = $fromDate->diffInDays($toDate);
                                $invAmount = (int) $invoice->inv_total;
                                // if($currMemb->cm_validity_type == 'year')
                                if ($clientMember->cm_validity_type == 'year') {
                                    $perDayAmount = ($invAmount / 365);
                                }

                                // elseif($currMemb->cm_validity_type == 'month')
                                elseif ($clientMember->cm_validity_type == 'month') {
                                    $perDayAmount = (($invAmount * 12) / 365);
                                }

                                // elseif($currMemb->cm_validity_type == 'week')
                                elseif ($clientMember->cm_validity_type == 'week') {
                                    $perDayAmount = (($invAmount * 52) / 365);
                                }

                                // elseif($currMemb->cm_validity_type == 'day')
                                elseif ($clientMember->cm_validity_type == 'day') {
                                    $perDayAmount = $invAmount;
                                }

                                $remaingAmount = round(($invAmount - ($perDayAmount * $days)), 2);
                                $nextEmiAmount = $this->nextEmi($clientMember, $clientMember->cm_number);
                                if ($invoice->inv_status == 'Paid') {
                                    if ($nextEmiAmount < $remaingAmount) {
                                        $clientMember->cm_next_emi = $nextEmiAmount;
                                        $adjustedEmiAmount         = $remaingAmount - $nextEmiAmount;
                                        $this->raiseMakeupSave(['clientId' => $clientId, 'notes' => '', 'action' => 'raise', 'amount' => $adjustedEmiAmount, 'purpose' => 'memb_ship_adj']);
                                        $invoiceGoesTo = 'downgrade';
                                    } else {
                                        $clientMember->cm_next_emi = $nextEmiAmount - $remaingAmount;
                                        $invoiceGoesTo             = 'upgrade';
                                        $adjustedEmiAmount         = $remaingAmount;
                                    }

                                    $isPaidInvoice   = true;
                                    $isCreateInvoice = false;
                                } else {
                                    $clientMember->cm_next_emi = $nextEmiAmount;
                                    $isUnpaidInvoice           = true;
                                }
                            } else {
                                $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1);
                                $isUnpaidInvoice           = true;
                            }
                            $isCreateInvoice = false;
                        } else {
                            $isCreateInvoice           = true;
                            $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1);
                        }

                        $clientMember->save();

                        if ($isUnpaidInvoice) {
                            $disc = 'Assign ' . $clientMember->cm_label . ' on ' . Carbon::now()->format('D, d M Y');
                            $this->updateMembInvoice($invoice, ['total' => $clientMember->cm_next_emi, 'currentMembrId' => $clientMember->id, 'existMembrId' => $clientMembrId, 'taxPerc' => $taxPerc, 'dueDate' => $membStartDate, 'disc' => $disc]);
                        }
                        if ($isPaidInvoice) {
                            $this->newInvItemInOldInvoice($invoice, $clientMember->cm_next_emi, $clientMembrId, $invoiceGoesTo, $adjustedEmiAmount, $taxPerc);
                        }

                        /* end: Adjusting remaing amount to emi of new client-membership */

                        ClientMember::where('cm_client_id', $client->id)->where('cm_status', 'Next')->delete();
                        $clientMember = $this->manageClientMemb($clientMember);

                        // $this->updateFutureBookingsMembership($currMemb, $clientId, $clientMember->id, $clientMember->cm_start_date);
                        $this->updateFutureBookingsMembership($clientMember, $clientId, $clientMember->id, $clientMember->cm_start_date);
                    } else {
                        $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1);
                        $clientMember->save();
                    }

                    /* Start: Create Invoice For membership */
                    if ($isCreateInvoice) {
                        $invoiceData                = [];
                        $invoiceData['dueDate']     = $membStartDate;
                        $invoiceData['clientId']    = $clientId;
                        $invoiceData['locationId']  = 0;
                        $invoiceData['status']      = 'Unpaid';
                        $invoiceData['productName'] = 'Assign ' . $clientMember->cm_label . ' on ' . Carbon::now()->format('D, d M Y');
                        $invoiceData['staffId']     = 0;
                        $invoiceData['taxType']     = 'including';
                        $invoiceData['price']       = $clientMember->cm_emi;
                        $invoiceData['type']        = 'membership';
                        $invoiceData['productId']   = $clientMember->id;
                        $invoiceData['paymentType'] = 'Cash';
                        $this->autoCreateInvoice($invoiceData);
                    }
                    $response[$clientId]['message']    = 'Membership updated successfully.';
                    $response[$clientId]['success']    = 'true';
                    $response[$clientId]['clientName'] = (isset($client->firstname) ? $client->firstname : '') . ' ' . (isset($client->lastname) ? $client->lastname : '');
                } else {
                    $response[$clientId]['message']    = 'Current membership not available.';
                    $response[$clientId]['success']    = 'false';
                    $response[$clientId]['clientName'] = (isset($client->firstname) ? $client->firstname : '') . ' ' . (isset($client->lastname) ? $client->lastname : '');
                }

                array_shift($clients);
                Session::put('clientsRemaining', $clients);
            }

        } else {
            $response['message'] = 'No clients found';
            $response['success'] = 'false';
        }

        // return ($response);
        return view('dashboard.updatedmembshow', compact('response'));
    }

    /**
     * Calculate.
     *
     * @param  $payPlan, $startDate
     * @return
     */
    public function calcDueDate($payPlan, $prevDueDate)
    {
        $dueDate = '';

        switch ($payPlan) {
            case 'week':
                $currentDate    = strtotime(date('Y-m-d'));
                $membStartDate  = strtotime($prevDueDate);
                $difference     = $currentDate - $membStartDate; // Difference in seconds
                $weekDiff       = date('d', $membStartDate) >= date('d', $currentDate) ? floor($difference / 604800) : floor($difference / 604800) + 1;
                $calculatedWeek = "+" . $weekDiff . " weeks";
                $effectiveDate  = date('Y-m-d', strtotime($prevDueDate . ' ' . $calculatedWeek));
                $dueDate        = $effectiveDate;
                break;
            case 'fortnight':
                $currentDate    = strtotime(date('Y-m-d'));
                $membStartDate  = strtotime($prevDueDate);
                $difference     = $currentDate - $membStartDate; // Difference in seconds
                $weekDiff       = date('d', $membStartDate) >= date('d', $currentDate) ? floor($difference / (604800 * 2)) : floor($difference / (604800 * 2)) + 1;
                $calculatedWeek = "+" . ($weekDiff * 2) . " weeks";
                $effectiveDate  = date('Y-m-d', strtotime($prevDueDate . ' ' . $calculatedWeek));
                $dueDate        = $effectiveDate;
                break;
            case 'month':
                $currentDate     = strtotime(date('Y-m-d'));
                $membStartDate   = strtotime($prevDueDate);
                $currentYear     = date('Y', $currentDate);
                $membStartYear   = date('Y', $membStartDate);
                $currentMonth    = date('m', $currentDate);
                $membStartMonth  = date('m', $membStartDate);
                $monthDiff       = date('d', $membStartDate) >= date('d', $currentDate) ? (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) : (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) + 1;
                $calculatedMonth = "+" . $monthDiff . " months";

                $effectiveDate = date('Y-m-d', strtotime($calculatedMonth, strtotime($prevDueDate)));
                $dueDate       = $effectiveDate;
                break;
            case '3month':
                $currentDate     = strtotime(date('Y-m-d'));
                $membStartDate   = strtotime($prevDueDate);
                $currentYear     = date('Y', $currentDate);
                $membStartYear   = date('Y', $membStartDate);
                $currentMonth    = date('m', $currentDate);
                $membStartMonth  = date('m', $membStartDate);
                $monthDiff       = date('d', $membStartDate) >= date('d', $currentDate) ? (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) : (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) + 3;
                $calculatedMonth = "+" . $monthDiff . " months";

                $effectiveDate = date('Y-m-d', strtotime($calculatedMonth, strtotime($prevDueDate)));
                $dueDate       = $effectiveDate;
                break;
            case '6month':
                $currentDate     = strtotime(date('Y-m-d'));
                $membStartDate   = strtotime($prevDueDate);
                $currentYear     = date('Y', $currentDate);
                $membStartYear   = date('Y', $membStartDate);
                $currentMonth    = date('m', $currentDate);
                $membStartMonth  = date('m', $membStartDate);
                $monthDiff       = date('d', $membStartDate) >= date('d', $currentDate) ? (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) : (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) + 6;
                $calculatedMonth = "+" . $monthDiff . " months";

                $effectiveDate = date('Y-m-d', strtotime($calculatedMonth, strtotime($prevDueDate)));
                $dueDate       = $effectiveDate;
                break;
        }

        return $dueDate;
    }

    /**
     * Update existing membership invoice.
     *
     * @param Object $invoiceObj, Array $invUpdateData['total','currentMembrId','existMembrId','taxPerc','dueDate','disc']
     * @return
     */
    protected function updateMembInvoice($invoiceObj, $invUpdateData)
    {
        $invItemObj = $invoiceObj->invoiceitem()->where('inp_product_id', $invUpdateData['existMembrId'])->where('inp_type', 'membership')->orderBy('inp_id', 'asc')->first();

        $tax                      = round((($invUpdateData['taxPerc'] * $invUpdateData['total']) / 100), 2);
        $invoiceObj->inv_total    = $invUpdateData['total'];
        $invoiceObj->inv_incl_tax = $tax;
        $invoiceObj->inv_due_date = $invUpdateData['dueDate'];
        $invoiceObj->save();

        $invItemObj->inp_product_id = $invUpdateData['currentMembrId'];
        $invItemObj->inp_price      = $invUpdateData['total'] - $tax;
        $invItemObj->inp_total      = $invUpdateData['total'];
        if (array_key_exists('disc', $invUpdateData)) {
            $invItemObj->inp_item_desc = $invUpdateData['disc'];
        }

        $invItemObj->save();
    }

    /**
     * Add more membership item in client membership
     *
     * @param
     * @return
     */
    protected function newInvItemInOldInvoice($invoiceObj, $total, $existMembrId, $invoiceGoesTo, $adjustedEmiAmount, $taxPerc)
    {

        $tax                      = round((($taxPerc * $total) / 100), 2);
        $oldinvItemObj            = $invoiceObj->invoiceitem()->where('inp_product_id', $existMembrId)->where('inp_type', 'membership')->orderBy('inp_id', 'asc')->first();
        $oldinvItemObj->inp_price = ($total - $adjustedEmiAmount) - $tax;
        $oldinvItemObj->inp_total = ($total - $adjustedEmiAmount);
        $oldinvItemObj->save();

        $invItemObj          = $oldinvItemObj->replicate();
        $invItemObj->inp_tax = 'N/A';
        if ($invoiceGoesTo == 'upgrade') {
            $invItemObj->inp_item_desc = 'Memebrship upgrade adjustment' . ' on ' . Carbon::now()->format('D, d M Y');
            $invItemObj->inp_total     = $adjustedEmiAmount;
            $invItemObj->inp_price     = $adjustedEmiAmount;
            $invoiceObj->inv_status    = 'Unpaid';
        } else {
            $invItemObj->inp_item_desc = 'Memebrship downgrade adjustment' . ' on ' . Carbon::now()->format('D, d M Y');
            $invItemObj->inp_total     = -($adjustedEmiAmount);
            $invItemObj->inp_price     = -($adjustedEmiAmount);
        }

        $invItemObj->save();

        $invoiceObj->inv_total    = $total;
        $invoiceObj->inv_incl_tax = $tax;
        $invoiceObj->save();
    }

    // public function updateClientMembershipLimit(Request $request) {
    public function updateClientMembershipLimitDemo(Request $request)
    {
        $response = [];
        # Get all clients
        // $remainingClients = Session::has('clientsRemaining') ? Session::get('clientsRemaining') : [];

        $clients = Clients::select('id')->get();

        // $clients = [2030];

        if (empty($remainingClients)) {
            Session::put('clientsRemaining', $clients);
        }

        if (!empty($clients)) {
            foreach ($clients as $client) {
                $clientId = $client->id;
                $client = Clients::find($clientId);
                # Get current membership
                $clientMember = Clients::paidMembership($clientId);

                if ($clientMember) {
                    setInfoLog('Manually client membership limit updated', $clientId);
                    $updatedLimit = collect();
                    $existLimit   = ClientMemberLimit::where('cme_client_id', $clientId)->first();

                    if (count($existLimit)) {
                        ClientMemberLimit::where('cme_client_id', $clientId)->forcedelete();
                        # Set info log
                        setInfoLog('Client future membership limit reset to empty', $clientId);
                    }

                    if ($clientMember && $clientMember->cm_services_limit != '') {
                        $serviceEvent = StaffEventSingleService::withTrashed()->where('sess_client_id', $clientId)/*->whereNull('deleted_at')*/->get();

                        if (count($serviceEvent)) {
                            foreach ($serviceEvent as $service) {
                                $serviceLimit = json_decode($clientMember->cm_services_limit, 1);
                                // if($service->sess_with_invoice == 0 && array_key_exists($service->sess_service_id, $serviceLimit)){
                                if ($service->deleted_at != null && $service->sess_cmid != 0 && ($service->sess_if_make_up == 1 || ($service->sess_event_log && strripos($service->sess_event_log, 'epic credit'))) && array_key_exists($service->sess_service_id, $serviceLimit) ) {
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
                        $classEvent = $client->eventClasses()/*->whereNull('staff_event_classes.deleted_at')->whereNull('staff_event_class_clients.deleted_at')*/->get();

                        if (count($classEvent)) {
                            foreach ($classEvent as $cls) {
                                $classLimit = json_decode($clientMember->cm_classes, 1);
                   				if($cls->pivot->deleted_at != null && $cls->pivot->secc_cmid != 0 && ($cls->pivot->secc_if_make_up_created == 1 || ($cls->pivot->secc_event_log && strripos($cls->pivot->secc_event_log, 'epic credit'))) && array_key_exists($cls->sec_class_id, $classLimit)) {
                   					$limit_type   = $clientMember->cm_class_limit_type;
                                    $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'class', 'action' => 'add', 'date' => $cls->sec_date, 'eventId' => $cls->sec_class_id, 'limit_type' => $limit_type]);

                   				} else if ($cls->pivot->deleted_at == null && $cls->pivot->secc_cmid != 0 && array_key_exists($cls->sec_class_id, $classLimit)) {
                                    $limit_type   = $clientMember->cm_class_limit_type;
                                    $updatedLimit = $this->updateClientMembershipLimitLocaly($updatedLimit, $clientId, ['type' => 'class', 'action' => 'add', 'date' => $cls->sec_date, 'eventId' => $cls->sec_class_id, 'limit_type' => $limit_type]);
                                }
                            }
                        }
                    }
                    if (count($updatedLimit)) {
                        $updatedLimit->save();
                    }

                    # Set info log
                    setInfoLog('Client membership limit updated', $clientId);

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

                $response['message'] = 'Membership limit updated';
                $response['success'] = 'True';

                // array_shift($clients);
                // Session::put('clientsRemaining', $clients);
            }

        } else {
            $response['message'] = 'No clients found';
            $response['success'] = 'false';
        }

        return ($response);
    }

    public function getClientClasses()
    {
        // $clientId = '1995';
        $clientId     = '1890';
        $client       = Clients::find($clientId);
        $eventClasses = $client->eventClasses()->whereNull('staff_event_class_clients.deleted_at')->whereNull('staff_event_classes.deleted_at')->whereBetween('sec_date', ['2019-07-15', '2019-07-21'])->get();
        dd($eventClasses->toArray());
    }

    public function cronMembUpdate()
    {
        # Get all clients
        $clients = ClientMember::where('cm_due_date', Carbon::yesterday()->toDateString())->pluck('cm_client_id')->unique()->toArray();
        // ini_set('max_execution_time', 0); // 0 = Unlimited
        // $clients = Clients::pluck('id')->toArray();;
        // dd($clients);
        $response = '';

        if (!empty($clients)) {
            $response .= 'Executed Date ' . date('l, d M, Y, H:i A', time()) . " :\r\n\r\n";

            foreach ($clients as $clientId) {
                try {
                    $isCreateInvoice = false;
                    $updateOpt       = 1;
                    $client          = Clients::where('id', $clientId)->first();

                    if ($client) {
                        # Get current membership
                        $prevMemb    = $currMemb    = $client->membership($client->id);
                        $membDueDate = new Carbon($currMemb->cm_due_date);
                        if ($currMemb && ($currMemb->cm_status == 'Expired' || Carbon::yesterday()->toDateString() == $membDueDate->toDateString())) {

                            $nextMemb = Clients::nextMembership($currMemb->cm_client_id);
                            if ($nextMemb) {
                                //If next membership exist
                                $this->updateFutureBookingsMembership( /*$membership->futureAppointments, $membership->membChangeableClasses*/$currMemb, $currMemb->cm_client_id, $nextMemb->id, $nextMemb->cm_start_date);
                                $currMemb->cm_status = 'Expired';
                                $currMemb->save();

                                $nextMemb->cm_status = 'Active';
                                $nextMemb->save();

                                //$membership = $this->manageClientMemb($nextMemb);
                            }else{
                                $memberShip    = MemberShip::where('id', $currMemb->cm_membership_id)->first();
                                $currentTime   = Carbon::now();
                                $membStartDate = new Carbon($prevMemb->cm_start_date);
                                $membEndDate   = $membStartDate->copy()->addYears(5);
    
                                $currMemb->cm_status = 'Expired';
                                $currMemb->save();
    
                                $isCreateInvoice = false;
    
                                # Create new client membership
                                $clientMember = new ClientMember;
                                // print_r('here');
                                $clientMember->cm_services_limit = $prevMemb->cm_services_limit;
                                $clientMember->cm_session_limit = $prevMemb->cm_session_limit;
                                # Update cm membership classes
                                $classes                             = $memberShip->classmember->pluck('cl_name', 'cl_id')->toArray();
                                $clientMember->cm_classes            = json_encode($classes);
                                $clientMember->cm_client_id          = $prevMemb->cm_client_id;
                                $clientMember->cm_membership_id      = $prevMemb->cm_membership_id;
                                $clientMember->cm_label              = $prevMemb->cm_label;
                                $clientMember->cm_validity_length    = $prevMemb->cm_validity_length;
                                $clientMember->cm_validity_type      = $prevMemb->cm_validity_type;
                                $clientMember->cm_class_limit        = $prevMemb->cm_class_limit;
                                $clientMember->cm_class_limit_length = $prevMemb->cm_class_limit_length;
                                $clientMember->cm_class_limit_type   = $prevMemb->cm_class_limit_type;
                                $clientMember->cm_auto_renewal       = $prevMemb->cm_auto_renewal;
                                $clientMember->cm_pay_plan           = $prevMemb->cm_pay_plan;
                                $clientMember->cm_prorate            = $prevMemb->cm_prorate;
                                $clientMember->cm_start_date         = $membStartDate->toDateTimeString();
                                $clientMember->cm_enrollment_limit   = $prevMemb->me_enrollment_limit;
                                $clientMember->cm_discount_type   = $prevMemb->cm_discount_type;
                                $clientMember->cm_subscription_type  = 'manual';
                                $clientMember->cm_disc_per_class_amnt  = $prevMemb->cm_disc_per_class_amnt;
                                $clientMember->cm_disc_amnt_type  = $prevMemb->cm_disc_amnt_type;
                                $clientMember->cm_disc_percentage  = $prevMemb->cm_disc_percentage;
                                
                                /* start: Renewal Amount */
                                $clientMember->cm_renw_amount = $prevMemb->cm_renw_amount;
                                /* end: Renewal Amount */
    
                                $clientMember->cm_signup_fee = $prevMemb->cm_signup_fee;
    
                                $clientMember->cm_due_date = $this->calcDueDate($prevMemb->cm_pay_plan, $prevMemb->cm_due_date);
                                $clientMember->cm_end_date = $membEndDate->toDateString();
    
                                $clientMember->cm_emi               = $prevMemb->cm_emi;
                                $clientMember->data                 = $prevMemb->data;
                                $taxPerc                            = $memberShip::calcTotalTax($memberShip);
                                $clientMember->cm_discounted_amount = $prevMemb->cm_discounted_amount;
                                $clientMember->cm_discount_dur      = $prevMemb->cm_discount_dur;
                                $clientMember->cm_number      = $prevMemb->cm_number + 1;
                                $clientMember->cm_per_clas_amnt     = $prevMemb->cm_per_clas_amnt;
                                $clientMember->cm_payment_option    = $prevMemb->cm_payment_option;
                                $clientMember->cm_original_price = $prevMemb->cm_original_price;
                                if ($updateOpt == 1) {
                                    #Update case of client-membership and Effect immediately
                                    /* start: Adjusting remaing amount to emi of new client-membership */
                                    $clientMembrId = $prevMemb->id;
    
                                    $invoice = [];
                                    $invoice = \App\Invoice::where('inv_business_id', Session::get('businessId'))
                                        ->where('inv_client_id', $clientId)
                                        ->whereHas('invoiceitem', function ($query) use ($clientMembrId) {
                                            $query->where('inp_product_id', $clientMembrId)
                                                ->where('inp_type', 'membership');
                                        })
                                        ->first();
    
                                    $fromDate        = Carbon::parse($clientMember->cm_start_date);
                                    $toDate          = Carbon::tomorrow();
                                    $isUnpaidInvoice = false;
                                    $isPaidInvoice   = false;
                                    $invoiceGoesTo   = '';
                                    if (count($invoice)) {
                                        if ($fromDate->lt($toDate)) {
                                            $days      = $fromDate->diffInDays($toDate);
                                            $invAmount = (int) $invoice->inv_total;
                                            if ($clientMember->cm_validity_type == 'year') {
                                                $perDayAmount = ($invAmount / 365);
                                            } elseif ($clientMember->cm_validity_type == 'month') {
                                                $perDayAmount = (($invAmount * 12) / 365);
                                            } elseif ($clientMember->cm_validity_type == 'week') {
                                                $perDayAmount = (($invAmount * 52) / 365);
                                            } elseif ($clientMember->cm_validity_type == 'day') {
                                                $perDayAmount = $invAmount;
                                            }
    
                                            $remaingAmount = round(($invAmount - ($perDayAmount * $days)), 2);
                                            $nextEmiAmount = $this->nextEmi($clientMember, $clientMember->cm_number);
                                            if ($invoice->inv_status == 'Paid') {
                                                if ($nextEmiAmount < $remaingAmount) {
                                                    $clientMember->cm_next_emi = $nextEmiAmount;
                                                    $adjustedEmiAmount         = $remaingAmount - $nextEmiAmount;
                                                    $this->raiseMakeupSave(['clientId' => $clientId, 'notes' => '', 'action' => 'raise', 'amount' => $adjustedEmiAmount, 'purpose' => 'memb_ship_adj']);
                                                    $invoiceGoesTo = 'downgrade';
                                                } else {
                                                    $clientMember->cm_next_emi = $nextEmiAmount - $remaingAmount;
                                                    $invoiceGoesTo             = 'upgrade';
                                                    $adjustedEmiAmount         = $remaingAmount;
                                                }
    
                                                $isPaidInvoice   = true;
                                                $isCreateInvoice = false;
                                            } else {
                                                $clientMember->cm_next_emi = $nextEmiAmount;
                                                $isUnpaidInvoice           = true;
                                            }
                                        } else {
                                            $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1);
                                            $isUnpaidInvoice           = true;
                                        }
                                        $isCreateInvoice = false;
                                    } else {
                                        $isCreateInvoice           = true;
                                        $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1);
                                    }
    
                                    $clientMember->save();
                                    if ($isUnpaidInvoice) {
                                        $disc = 'Assign ' . $clientMember->cm_label . ' on ' . Carbon::now()->format('D, d M Y');
                                        $this->updateMembInvoice($invoice, ['total' => $clientMember->cm_next_emi, 'currentMembrId' => $clientMember->id, 'existMembrId' => $clientMembrId, 'taxPerc' => $taxPerc, 'dueDate' => $membStartDate, 'disc' => $disc]);
                                    }
                                    if ($isPaidInvoice) {
                                        $this->newInvItemInOldInvoice($invoice, $clientMember->cm_next_emi, $clientMembrId, $invoiceGoesTo, $adjustedEmiAmount, $taxPerc);
                                    }
    
                                    /* end: Adjusting remaing amount to emi of new client-membership */
    
                                    $clientMember = $this->manageClientMemb($clientMember);
                                    # Set info log
                                    setInfoLog('Client membership updated due to cron', $clientId);
    
                                    /* Reset past membership limit */
                                    // $this->updateFutureBookingsMembership($clientMember, $clientId, $clientMember->id, $clientMember->cm_start_date);
    
                                    /* Reset future membership limit */
                                    // $this->membershipLimitResetOnMembershipChange($clientId);
                                } else {
                                    $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1);
    
                                    $clientMember->save();
                                }
    
                                if ($currMemb->cm_parent_id) {
                                    ClientMember::where('id', $currMemb->cm_parent_id)->orWhere('cm_parent_id', $currMemb->cm_parent_id)->delete();
                                } else {
                                    $currMemb->delete();
                                }
    
                                /* Start: Create Invoice For membership */
                                if ($isCreateInvoice) {
                                    $invoiceData                = [];
                                    $invoiceData['dueDate']     = $membStartDate;
                                    $invoiceData['clientId']    = $clientId;
                                    $invoiceData['locationId']  = 0;
                                    $invoiceData['status']      = 'Unpaid';
                                    $invoiceData['productName'] = 'Assign ' . $clientMember->cm_label . ' on ' . Carbon::now()->format('D, d M Y');
                                    $invoiceData['staffId']     = 0;
                                    $invoiceData['taxType']     = 'including';
                                    $invoiceData['price']       = $clientMember->cm_emi;
                                    $invoiceData['type']        = 'membership';
                                    $invoiceData['productId']   = $clientMember->id;
                                    $invoiceData['paymentType'] = 'Cash';
                                    $this->autoCreateInvoice($invoiceData);
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    $response .= '[' . $clientId . '] ' . (isset($client->firstname) ? $client->firstname : '') . ' ' . (isset($client->lastname) ? $client->lastname : '') . "\r\n";
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                    $response .= '[' . $clientId . '] ' . (isset($client->firstname) ? $client->firstname : '') . ' ' . (isset($client->lastname) ? $client->lastname : '') . "\r\n";
                }
            }

            Storage::put('membership_details_' . time() . '.txt', $response);
        }
    }

    public function manageRecureWithInvoiceClients()
    {
        $client = Clients::find(2035);
        $eventsUpto  = [];
        $clientMember = Clients::paidMembership($client->id);
        if($clientMember) {
            $futureEvents = DB::table('staff_event_class_clients')->where('secc_client_id', 2035)->where('secc_if_recur', 1)->where('secc_cmid', 0)->whereNull('deleted_at')->update(['secc_cmid' => $clientMember->id]);
        }

       dd('Updated');
    }

    public function getClientsClassesData()
    {
        $clients  = Clients::get();
        $data = array();
        $sdata = array();
        $i = 1;
        $j = 1;
        foreach ($clients as $client) {
            $clientClasses = array();
            // if($client->id == '1917'){
            if(true){
                $dt = new DateTime("last saturday");
                $dt->setTimezone(new DateTimeZone('Pacific/Auckland'));
                $startDateTime = $dt->modify('+1 day')->format('Y-m-d H:i:s');
                $startYear = $dt->modify('+1 day')->format('Y');
                $week = $dt->modify('+1 day')->format("W");
                $dt_max = clone($dt);
                $endDateTime = $dt_max->modify('+6 days')->format('Y-m-d H:i:s');
                $endYear = $dt_max->modify('+6 days')->format('Y');
                $eventClasses = $client->eventClasses()->where('sec_start_datetime','>=','2019-10-28 00:00:00')->where('sec_end_datetime','<','2019-11-03 23:59:00')->get();
                // dd($eventClasses);
                if(count($eventClasses)){
                    foreach ($eventClasses as $class) {
                        if($class->deleted_at == null && $class->pivot->deleted_at == null && $class->pivot->secc_cmid != 0  && $class->pivot->secc_epic_credit == '0' && $class->pivot->secc_with_invoice == '0' && $class->pivot->secc_client_id == $client->id){
                            $clientClasses[] = $class->toArray();
                        }
                    }
                }

                $serviceEvent = StaffEventSingleService::where('sess_client_id', $client->id)->where('sess_cmid','!=',0)->where('sess_with_invoice',0)->where('sess_epic_credit',0)->where('sess_start_datetime','>=','2019-11-18 00:00:00')->where('sess_end_datetime','<','2019-11-24 23:59:00')->get();
                # Get current membership
                $clientMember = Clients::paidMembership($client->id);
                if($clientMember)
                {
                    $clientMemberLimit = ClientMemberLimit::where('cme_client_id', $client->id)->first();
                    if($clientMemberLimit)
                    {
                        $existData = json_decode($clientMemberLimit->cme_classes_weekly, 1);
                        $eventYear = ($startYear < $endYear) ? $endYear : $startYear;
                        $limit = $clientMember->cm_class_limit_length;
                        if(count($clientClasses) > $limit && $clientMember->cm_class_limit_type == 'every_week'){
                            $data[$i]['id'] = $client->id;
                            $data[$i]['Name'] = $client->firstname.' '.$client->lastname;
                            $data[$i]['classes'] = count($clientClasses);
                            $data[$i]['membership'] = $clientMember?$clientMember->cm_label:'';
                            $data[$i]['class-limit'] = $clientMember->cm_class_limit_length;
                            $i = $i +1 ;
                        }

                        if(count($serviceEvent))
                        {
                            $serviceDatas = json_decode($clientMember->cm_services_limit, 1);

                            foreach ($serviceDatas as $key => $value) {
                                $serviceEventCount = StaffEventSingleService::where('sess_client_id', $client->id)->where('sess_cmid','!=',0)->where('sess_with_invoice',0)->where('sess_epic_credit',0)->where('sess_start_datetime','>=','2019-11-18 00:00:00')->where('sess_end_datetime','<','2019-11-24 23:59:00')->where('sess_service_id',$key)->count();
                                $sexistData = json_decode($clientMemberLimit->cme_services_weekly, 1);
                                if($serviceEventCount > $value['limit'])
                                {
                                    $sdata[$j]['id'] = $client->id;
                                    $sdata[$j]['Name'] = $client->firstname.' '.$client->lastname;
                                    $sdata[$j]['services'] = $serviceEventCount;
                                    $sdata[$j]['serviceId'] = $key;
                                    $sdata[$j]['service-limit'] = $value['limit'];
                                    $sdata[$j]['booked-service-table-data'] =$sexistData[$key][$eventYear]['47'];
                                    $j= $j + 1;
                                }
                            }
                        }
                    }
                }
            }
        }
        dd($data,$sdata);
    }

    public function updateClientNextEmi(){
        $clients = Clients::select('id')->get();
        foreach ($clients as $client) {
            $memberShip = Clients::paidMembership($client->id);
            if($memberShip){
                if($memberShip->cm_discount_dur == -1){
                    if(Carbon::now() < $memberShip->cm_due_date){
                        $memberShip->cm_next_emi = $memberShip->cm_emi;
                        $memberShip->save();
                    }
                }
            }
        }
    }

    public function updateMembPrice(){

        $clientsData =Clients::whereBusinessId('55')->whereAccountStatus('Active')->get();
        foreach($clientsData as $value){
           $memberShipData = Clients::paidMembership($value->id);
             if($memberShipData){
                 if($memberShipData->cm_discount_dur != '' || $memberShipData->cm_discount_dur != 0){
                     $memberShip = MemberShip::with('categorymember')->where('me_business_id', Session::get('businessId'))->where('id',$memberShipData->cm_membership_id)->first();
                   
                     $memberShipNewData =[];
                        if($memberShipData->cm_disc_per_class_amnt == null){
                        $memberShipNewData = ['cm_disc_per_class_amnt'=>$memberShipData->cm_per_clas_amnt,'cm_per_clas_amnt' =>(isset($memberShip->me_unit_amt)?$this->perClassPrice($memberShip->me_class_limit_length,$memberShip->me_unit_amt):0)];
                        // dd($memberShipNewData);
                        $memberShipData->update($memberShipNewData);
                        setInfoLog('Client membership update', $memberShipData->id);
                    } 
               }
            }
        }


    }

    public function perClassPrice($length, $amnt)
    {
        
        $price = ($amnt * 12)/52;
        $returnData = sprintf('%0.2f',$price/$length);
        
        return $returnData;
    }

    public function makeList(){
        $clientsData =Clients::whereBusinessId('55')->where(function($query){
            $query->whereAccountStatus('Active')
            ->orWhere('account_status','Contra');
                  
            })->get();
        $membId = [];
        foreach($clientsData as $value){

            $membData = ClientMember::where('cm_client_id',$value->id)->where('cm_status','Active')->whereNull('deleted_at')->latest()->first();
            if($membData && $membData->cm_emi != $membData->cm_next_emi){
               if(abs($membData->cm_emi - $membData->cm_next_emi) < 2){
                // $membId[] = ['clientId'=>$membData->cm_client_id, 'name'=>$value->getFullNameAttribute(), 'emi' => $membData->cm_emi, 'next_emi' => $membData->cm_next_emi];
                $membData->update(['cm_next_emi'=>$membData->cm_emi]);
                setInfoLog('Client membership next emi pice updated', $membData->id);
                

               }
            
              

            }
        }
        dd('updated');
    }

    public function updateRenewAmount(){
        $clientsData =Clients::whereBusinessId('55')->where(function($query){
            $query->whereAccountStatus('Active')
            ->orWhere('account_status','Contra');
                  
            })->get();
        $membId = [];
     
        foreach($clientsData as $value){
           
            $membData = ClientMember::where('cm_client_id',$value->id)->where('cm_status','Active')->whereNull('deleted_at')->latest()->first();
            $membList = MemberShip::where('me_business_id',55)->where('id',$membData->cm_membership_id)->first();
        
            $gstPrice = $membList->me_unit_amt * 15/100;
            $membGstPrice = round($membList->me_unit_amt + $gstPrice,2);
         
            if($membData && $membData->cm_renw_amount == $membGstPrice && $membData->cm_renw_amount != 0){
             
                $invoiceData = Invoice::with('invoiceitem')->where('inv_business_id',55)->where('inv_client_id', $membData->cm_client_id)->whereHas('invoiceitem',function($query){
                    $query->where('inp_type','membership');
                })->orderBy('inv_id','DESC')->first();
                $membData->cm_renw_amount =$membList->me_unit_amt;
               
                $emiData              = $this->calcEmi(  $membData, $membData->cm_start_date, $membData->cm_due_date, ['discAmt' => $membData->cm_discounted_amount, 'membEndDate' => $membData->cm_end_date]);
                $membData->cm_emi = $emiData['emi'];
                $membId[] = ['clientId'=>$membData->cm_client_id,'renewAmount' => $membData->cm_renw_amount, 'membershipPrice' => $membList->me_unit_amt,'emi'=>$membData->cm_emi,'invoiceTotal' =>  $invoiceData->inv_total,'newInvTotal'=>$membData->cm_emi,'invId' => $invoiceData->inv_id ];
                $membData->update(['cm_renw_amount'=>$membList->me_unit_amt,'cm_emi'=>$membData->cm_emi  ,'cm_next_emi'=>$membData->cm_emi  ]);
                if($invoiceData->inv_id != null && $invoiceData->inv_id != ''){
                    $invoiceData->update(['inv_total'=>$membData->cm_emi]);
                   $invoiceItems =  InvoiceItems::where('inp_invoice_id',$invoiceData->inv_id)->first();
                   $invoiceItems->update(['inp_price'=>$membData->cm_emi,'inp_total'=>$membData->cm_emi]);
                    setInfoLog('Client membership invoice updated', $invoiceData->inv_id);
                    setInfoLog('Client membership invoice items updated',  $invoiceItems->inp_id);
                }
                setInfoLog('Client membership renew amount, emi, next emi updated', $membData->id);
            
            }
        }
        dd( $membId);
    }


    public function updateClientMembAmount(){
        $clientsData =Clients::whereBusinessId('55')->where(function($query){
            $query->whereAccountStatus('Active')
            ->orWhere('account_status','Contra');
                  
            })->get();
        $membId = [];
        foreach($clientsData as $value){
            $membData = ClientMember::where('cm_client_id',$value->id)->where('cm_status','Active')->where('cm_discounted_amount',0)->whereNull('deleted_at')->latest()->first();
     
            $membList = MemberShip::where('me_business_id',55)->where('id',$membData->cm_membership_id)->first();
            if($membData && $membList->me_unit_amt != $membData->cm_renw_amount && ($membData->cm_services_limit == null || $membData->cm_services_limit == '[]') && ($membData->cm_session_limit == null || $membData->cm_session_limit == '[]')){
                $invoiceData = Invoice::with('invoiceitem')->where('inv_business_id',55)->where('inv_client_id', $membData->cm_client_id)->whereHas('invoiceitem',function($query){
                    $query->where('inp_type','membership');
                })->orderBy('inv_id','DESC')->first();
            $membId[] = ['id'=>$membData->id,'discAmount' => $membData->cm_discounted_amount, 'renewAmount' => $membData->cm_renw_amount,'emi'=>$membData->cm_emi,'nextEmi'=>$membData->cm_next_emi,'originalMembPrice' =>$membList->me_unit_amt,'payPlan'=>$membData->cm_pay_plan,'service'=>$membData->cm_service_limit,'session'=>$membData->cm_session_limit];
            $membData->cm_renw_amount =$membList->me_unit_amt;
            $emiData              = $this->calcEmi(  $membData, $membData->cm_start_date, $membData->cm_due_date, ['discAmt' => $membData->cm_discounted_amount, 'membEndDate' => $membData->cm_end_date]);
                $membData->cm_emi = $emiData['emi'];
            $membData->update(['cm_renw_amount'=>$membList->me_unit_amt,'cm_emi'=>$membData->cm_emi  ,'cm_next_emi'=>$membData->cm_emi  ]);
            if($invoiceData->inv_id != null && $invoiceData->inv_id != ''){
                $invoiceData->update(['inv_total'=>$membData->cm_emi]);
               $invoiceItems =  InvoiceItems::where('inp_invoice_id',$invoiceData->inv_id)->first();
               $invoiceItems->update(['inp_price'=>$membData->cm_emi,'inp_total'=>$membData->cm_emi]);
                setInfoLog('Client membership invoice updated', $invoiceData->inv_id);
                setInfoLog('Client membership invoice items updated',  $invoiceItems->inp_id);
            }

            }
        }
        dd( $membId);
    }

    public function updateOriginalMembPrice(){
        $clientsData =Clients::whereBusinessId('55')->where(function($query){
            $query->whereAccountStatus('Active')
            ->orWhere('account_status','Contra');
                  
            })->get();
        $membId = [];
        foreach($clientsData as $value){
            $membData = ClientMember::where('cm_client_id',$value->id)->where('cm_status','Active')->whereNull('deleted_at')->latest()->first();
    
            $membList = MemberShip::where('me_business_id',55)->where('id',$membData->cm_membership_id)->first();
            if($membData->cm_discounted_amount != 0 && $membData->cm_discounted_amount != '' && $membData->cm_discount_dur != -1){
              
              if(($membData->cm_services_limit == null || $membData->cm_services_limit == '[]') &&($membData->cm_session_limit == null || $membData->cm_session_limit == '[]')){
                //   $priceWithoutDisc =  $membData->cm_renw_amount -$membData->cm_discounted_amount;
                //   $originalPrice = $priceWithoutDisc + $membList->me_unit_amt;

                // $membData->update(['cm_original_price'=>$membList->me_unit_amt]);
                $nextEmiAmount = $this->nextEmi($membData, $membData->cm_number,$membData->cm_discounted_amount);
                // dd( $nextEmiAmount,$membData->cm_number,$membData->cm_renw_amount);
                $membData->update(['cm_original_price'=>$membList->me_unit_amt,'cm_next_emi'=>$nextEmiAmount]); 
               $membId[] = ['id'=>$membData->id,'renewAmnt'=>$membData->cm_renw_amount,'discountedAmnt'=>$membData->cm_discounted_amount,'membRealPrice'=>$membList->me_unit_amt,'nextemi'=>$nextEmiAmount,'oldnextemi'=>$membData->cm_next_emi];
              }


            //   if(($membData->cm_services_limit != null && $membData->cm_services_limit != '[]') ||($membData->cm_session_limit != null && $membData->cm_session_limit != '[]')){
            //       $sessionServiceAmnt =  $membData->cm_renw_amount -$membData->cm_discounted_amount;
            //       $originalPrice = $sessionServiceAmnt + $membList->me_unit_amt;
            //    $membId[] = ['id'=>$membData->id,'renewAmnt'=>$membData->cm_renw_amount,'discountedAmnt'=>$membData->cm_discounted_amount,'membRealPrice'=>$membList->me_unit_amt,'sessionServiceAmnt'=>$sessionServiceAmnt,'originalPrice'=>$originalPrice];
            //   }
            }
        }
        dd( $membId);
    }


    public function updateNextEmiForDiscForever(){
        $clientsData =Clients::whereBusinessId('55')->where(function($query){
            $query->whereAccountStatus('Active')
            ->orWhere('account_status','Contra');
                  
            })->get();
        $membId = [];
        foreach($clientsData as $value){
            $membData = ClientMember::where('cm_client_id',$value->id)->where('cm_status','Active')->whereNull('deleted_at')->latest()->first();
            if( $membData->cm_discount_dur == -1){
                $nextEmiAmount = $this->nextEmi($membData, $membData->cm_number,$membData->cm_discounted_amount); 
                if($membData->cm_next_emi != $nextEmiAmount){
                    $membData->update(['cm_next_emi'=> $nextEmiAmount]);
                    $membId[] = ['id'=>$membData->id, 'cm_next_emi'=>$nextEmiAmount,'old_next_emi'=>$membData->cm_next_emi];
                   
                }
            }
        }

        dd($membId);
    }

    public function fetchClassDetail(){
        $clientsData =Clients::whereBusinessId('55')->where(function($query){
            $query->whereAccountStatus('Active')
            ->orWhere('account_status','Contra');
                  
            })->get();
            $staffEventClass = [];
            foreach($clientsData as $client){
                $id =$client->id;
                $data =  StaffEventClass::whereBetween('sec_date',['2020-12-28', '2021-01-03'])->whereHas('clientsRaw', function($q) use($id){ $q->where('secc_client_id', $id)->where('secc_cmid','!=',0)->whereNull('staff_event_class_clients.deleted_at');
                    
                })->get();
                if(count($data)){
                    $membData =  ClientMember::where('cm_class_limit_type','every_week')->where('cm_client_id', $id)->where('cm_status','Active')->whereNull('deleted_at')->latest()->first();
                    if(count($data) > $membData->cm_class_limit_length){
                        $staffEventClas[] = [
                            'id' => $id,
                            'Name' =>  $client->firstname.' '.$client->lastname,
                            'limit' => $membData->cm_class_limit_length,
                            'booked' => count($data)
                        ];
                    }
                }


            }

        
    //     $classClients = [];
    //     foreach( $staffEventClass as $class){
    //         $classClients [] = DB::table('staff_event_class_clients')->where('secc_sec_id',$class->sec_id)->whereNull('deleted_at')->latest()->first();

    //     }
    //     $clientMemb = [];
    //    foreach($classClients as $client){
    //     $clientMemb[] = ClientMember::select('cm_client_id','cm_class_limit_length')->where('cm_class_limit_type','every_week')->where('cm_client_id', $client->secc_client_id)->where('cm_status','Active')->whereNull('deleted_at')->latest()->first();
    //    }
dd( $staffEventClas);
    }

}
