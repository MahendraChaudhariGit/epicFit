<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Auth;
use Session;
use App\Business;
use App\StaffEventSingleService;
use App\Clients;
use Carbon\Carbon;
use DB;
use App\StaffEventClass;
use App\SalesProcessProgress;
use App\ClientMemberLimit;
use App\MemberShip;
use App\ClientMember;
use App\Http\Traits\ClientTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\StaffEventsTrait;

class UpdateClientMembership extends Command
{
     use ClientTrait, StaffEventsTrait, HelperTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memb:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update client membership when at before due date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        # Get all clients
        $remainingClients = Session::has('clientsRemaining') ? Session::get('clientsRemaining') : [];

        // $clients = !empty($remainingClients) ? $remainingClients  : Clients::pluck('id')->toArray();

        $clients = [1898];
        
        if(empty($remainingClients))
            Session::put('clientsRemaining', $clients);

        if(!empty($clients)) {
            foreach ($clients as $clientId) {
                $isCreateInvoice = false;
                $updateOpt = 1;
                $client = Clients::find($clientId);

                # Get current membership
                $prevMemb = $currMemb = $client->membership($client->id);
                // dd($prevMemb->toArray());

                if($currMemb && ($currMemb->cm_status == 'Expired' || Carbon::now()->toDateString() >= $currMemb->cm_due_date )) {

                    // if(!in_array($currMemb->cm_status, ['Expired', 'Removed']))
                    //     $updateOpt = 1;

                    $memberShip = MemberShip::where('id', $currMemb->cm_membership_id)->first();
                    $currentTime = Carbon::now();
                    $membStartDate = new Carbon($prevMemb->cm_start_date);
                    $membEndDate = $membStartDate->copy()->addYears(5);

                    // if($updateOpt == 1)  {
                        # Update membership
                        $currMemb->cm_status = 'Expired';
                        $currMemb->save();
                    // }

                    if($currMemb->cm_parent_id) 
                        ClientMember::where('id', $currMemb->cm_parent_id)->orWhere('cm_parent_id', $currMemb->cm_parent_id)->delete();
                    else 
                        $currMemb->delete();

                    $isCreateInvoice = false;

                    # Create new client membership
                    $clientMember = new ClientMember;
                    $clientMember->cm_services_limit = $prevMemb->cm_services_limit;

                    # Update cm membership classes
                    $classes = $memberShip->classmember->pluck('cl_name', 'cl_id')->toArray();
                    $clientMember->cm_classes = json_encode($classes);
                    $clientMember->cm_client_id = $prevMemb->cm_client_id;
                    $clientMember->cm_membership_id = $prevMemb->cm_membership_id;
                    $clientMember->cm_label = $prevMemb->cm_label;
                    $clientMember->cm_validity_length = $prevMemb->cm_validity_length;
                    $clientMember->cm_validity_type = $prevMemb->cm_validity_type;
                    $clientMember->cm_class_limit = $prevMemb->cm_class_limit;
                    $clientMember->cm_class_limit_length = $prevMemb->cm_class_limit_length;
                    $clientMember->cm_class_limit_type = $prevMemb->cm_class_limit_type;
                    $clientMember->cm_auto_renewal = $prevMemb->cm_auto_renewal;
                    $clientMember->cm_pay_plan = $prevMemb->cm_pay_plan;
                    $clientMember->cm_prorate = $prevMemb->cm_prorate;
                    $clientMember->cm_start_date = $membStartDate->toDateTimeString();
                    $clientMember->cm_enrollment_limit = $prevMemb->me_enrollment_limit;
                    $clientMember->cm_subscription_type = 'manual';

                    /* start: Renewal Amount */
                    $clientMember->cm_renw_amount = $prevMemb->cm_renw_amount;
                    /* end: Renewal Amount */

                    $clientMember->cm_signup_fee = $prevMemb->cm_signup_fee;

                    $clientMember->cm_due_date = $this->calcDueDate($prevMemb->cm_pay_plan, $prevMemb->cm_due_date);
                    $clientMember->cm_end_date = $membEndDate->toDateTimeString();

                    $clientMember->cm_emi = $prevMemb->cm_emi;
                    $clientMember->data = $prevMemb->data;
                    $taxPerc = $memberShip::calcTotalTax($memberShip);
                    $clientMember->cm_discounted_amount = $prevMemb->cm_discounted_amount;
                    $clientMember->cm_discount_dur = $prevMemb->cm_discount_dur;
                    $clientMember->cm_payment_option = $prevMemb->cm_payment_option;
                    // dd($clientMember);
                    if($updateOpt == 1){ 
                    #Update case of client-membership and Effect immediately

                        /* start: Adjusting remaing amount to emi of new client-membership */
                        // $clientMembrId = $currMemb->id;
                        $clientMembrId = $clientMember->id;
                        
                        $invoice = \App\Invoice::where('inv_business_id', Session::get('businessId'))
                        ->where('inv_client_id', $clientId)
                        ->whereHas('invoiceitem', function($query) use($clientMembrId){
                            $query->where('inp_product_id', $clientMembrId)
                            ->where('inp_type', 'membership');
                        })
                        ->first();

                        // $fromDate = Carbon::parse($currMemb->cm_start_date);
                        $fromDate = Carbon::parse($clientMember->cm_start_date);
                        $toDate = Carbon::tomorrow();
                        $isUnpaidInvoice = false;
                        $isPaidInvoice = false;
                        $invoiceGoesTo = '';
                        if(count($invoice)){
                            if($fromDate->lt($toDate)){
                                $days = $fromDate->diffInDays($toDate);
                                $invAmount = (int)$invoice->inv_total;
                                // if($currMemb->cm_validity_type == 'year')
                                if($clientMember->cm_validity_type == 'year')
                                    $perDayAmount = ($invAmount / 365);
                                // elseif($currMemb->cm_validity_type == 'month')
                                elseif($clientMember->cm_validity_type == 'month')
                                    $perDayAmount = (($invAmount * 12) / 365);
                                // elseif($currMemb->cm_validity_type == 'week')
                                elseif($clientMember->cm_validity_type == 'week')
                                    $perDayAmount = (($invAmount * 52) / 365);
                                // elseif($currMemb->cm_validity_type == 'day')
                                elseif($clientMember->cm_validity_type == 'day')
                                    $perDayAmount = $invAmount;

                                $remaingAmount = round(($invAmount - ($perDayAmount * $days)), 2);
                                $nextEmiAmount = $this->nextEmi($clientMember, 1);
                                if($invoice->inv_status == 'Paid'){
                                    if($nextEmiAmount < $remaingAmount){
                                        $clientMember->cm_next_emi = $nextEmiAmount;
                                        $adjustedEmiAmount = $remaingAmount - $nextEmiAmount;
                                        $this->raiseMakeupSave(['clientId'=>$clientId,'notes'=>'','action'=>'raise','amount'=>$adjustedEmiAmount,'purpose'=>'memb_ship_adj']);
                                        $invoiceGoesTo = 'downgrade';
                                    }
                                    else{
                                        $clientMember->cm_next_emi = $nextEmiAmount - $remaingAmount;
                                        $invoiceGoesTo = 'upgrade';
                                        $adjustedEmiAmount = $remaingAmount;
                                    }

                                    $isPaidInvoice = true;
                                    $isCreateInvoice = false;
                                }
                                else{
                                    $clientMember->cm_next_emi = $nextEmiAmount;
                                    $isUnpaidInvoice = true;
                                }
                            }
                            else{
                                $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1);
                                $isUnpaidInvoice = true;
                            }
                            $isCreateInvoice = false;
                        }
                        else{
                            $isCreateInvoice = true;
                            $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1);
                        }   

                        $clientMember->save();
                        // dd($clientMember);


                        if($isUnpaidInvoice){
                            $disc = 'Assign '.$clientMember->cm_label.' on '.Carbon::now()->format('D, d M Y');
                            $this->updateMembInvoice($invoice, ['total'=>$clientMember->cm_next_emi, 'currentMembrId'=>$clientMember->id, 'existMembrId'=>$clientMembrId, 'taxPerc'=>$taxPerc, 'dueDate'=>$membStartDate,'disc'=>$disc]);
                        }
                        if($isPaidInvoice){
                            $this->newInvItemInOldInvoice($invoice, $clientMember->cm_next_emi, $clientMembrId, $invoiceGoesTo, $adjustedEmiAmount, $taxPerc);
                        }

                        /* end: Adjusting remaing amount to emi of new client-membership */

                        // ClientMember::where('cm_client_id', $client->id)->where('cm_status', 'Next')->delete();
                        $clientMember = $this->manageClientMemb($clientMember);

                        // $this->updateFutureBookingsMembership($currMemb, $clientId, $clientMember->id, $clientMember->cm_start_date);
                        $this->updateFutureBookingsMembership($clientMember, $clientId, $clientMember->id, $clientMember->cm_start_date);
                    } else {
                        $clientMember->cm_next_emi = $this->nextEmi($clientMember, 1);
                        $clientMember->save();
                    }

                    // dd($isCreateInvoice);
                    /* Start: Create Invoice For membership */
                    if($isCreateInvoice){
                        $invoiceData=[];
                        $invoiceData['dueDate'] = $membStartDate;
                        $invoiceData['clientId'] = $clientId;
                        $invoiceData['locationId'] = 0;
                        $invoiceData['status'] = 'Unpaid';
                        $invoiceData['productName']='Assign '.$clientMember->cm_label.' on '.Carbon::now()->format('D, d M Y');
                        $invoiceData['staffId'] = 0;
                        $invoiceData['taxType']='including';
                        $invoiceData['price']= $clientMember->cm_emi;
                        $invoiceData['type']='membership';
                        $invoiceData['productId'] = $clientMember->id;
                        $invoiceData['paymentType'] = 'Cash';
                        $this->autoCreateInvoice($invoiceData); 
                    }
                    $response[$clientId]['message'] = 'Membership updated successfully.';
                    $response[$clientId]['success'] = 'true';
                    $response[$clientId]['clientName'] = (isset($client->firstname)?$client->firstname: '').' '.(isset($client->lastname)?$client->lastname: '');
                } else {
                    $response[$clientId]['message'] = 'Current membership not available.';
                    $response[$clientId]['success'] = 'false';
                    $response[$clientId]['clientName'] = (isset($client->firstname)?$client->firstname: '').' '.(isset($client->lastname)?$client->lastname: '');
                }

                array_shift($clients);
                Session::put('clientsRemaining', $clients);
            }

        } else {
            $response['message'] = 'No clients found';
            $response['success'] = 'false';
        }
    }

     /**
     * Calculate.
     *
     * @param  $payPlan, $startDate 
     * @return
     */
    public function calcDueDate($payPlan , $prevDueDate) {
        $dueDate = '';

        switch($payPlan) {
            case 'week':
            $currentDate = strtotime(date('Y-m-d'));
            $membStartDate = strtotime($prevDueDate);
                $difference        = $currentDate - $membStartDate; // Difference in seconds
                $weekDiff = date('d', $membStartDate) >= date('d', $currentDate) ? floor($difference / 604800) : floor($difference / 604800) + 1 ;
                $calculatedWeek = "+".$weekDiff." weeks";
                $effectiveDate = date('Y-m-d', strtotime($prevDueDate.' '.$calculatedWeek));
                $dueDate = $effectiveDate;
                break;
                case 'fortnight':
                $currentDate = strtotime(date('Y-m-d'));
                $membStartDate = strtotime($prevDueDate);
                $difference        = $currentDate - $membStartDate; // Difference in seconds
                $weekDiff = date('d', $membStartDate) >= date('d', $currentDate) ? floor($difference / (604800 * 2)) : floor($difference / (604800 * 2)) + 1 ;
                $calculatedWeek = "+".($weekDiff * 2)." weeks";
                $effectiveDate = date('Y-m-d', strtotime($prevDueDate.' '.$calculatedWeek));
                $dueDate = $effectiveDate;
                break;
                case 'month':
                $currentDate = strtotime(date('Y-m-d'));
                $membStartDate = strtotime($prevDueDate);
                $currentYear = date('Y', $currentDate);
                $membStartYear = date('Y', $membStartDate);
                $currentMonth = date('m', $currentDate);
                $membStartMonth = date('m', $membStartDate);
                $monthDiff = date('d', $membStartDate) >= date('d', $currentDate) ? (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) : (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) + 1;
                $calculatedMonth = "+".$monthDiff." months";

                $effectiveDate = date('Y-m-d', strtotime($calculatedMonth, strtotime($prevDueDate)));
                $dueDate = $effectiveDate;
                break;
                case '3month':
                $currentDate = strtotime(date('Y-m-d'));
                $membStartDate = strtotime($prevDueDate);
                $currentYear = date('Y', $currentDate);
                $membStartYear = date('Y', $membStartDate);
                $currentMonth = date('m', $currentDate);
                $membStartMonth = date('m', $membStartDate);
                $monthDiff =  date('d', $membStartDate) >= date('d', $currentDate) ? (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) :( ($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) + 3;
                $calculatedMonth = "+".$monthDiff." months";

                $effectiveDate = date('Y-m-d', strtotime($calculatedMonth, strtotime($prevDueDate)));
                $dueDate = $effectiveDate;
                break;
                case '6month':
                $currentDate = strtotime(date('Y-m-d'));
                $membStartDate = strtotime($prevDueDate);
                $currentYear = date('Y', $currentDate);
                $membStartYear = date('Y', $membStartDate);
                $currentMonth = date('m', $currentDate);
                $membStartMonth = date('m', $membStartDate);
                $monthDiff = date('d', $membStartDate) >= date('d', $currentDate) ? (($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) :(($currentYear - $membStartYear) * 12) + ($currentMonth - $membStartMonth) + 6;
                $calculatedMonth = "+".$monthDiff." months";

                $effectiveDate = date('Y-m-d', strtotime($calculatedMonth, strtotime($prevDueDate)));
                $dueDate = $effectiveDate;
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
    protected function updateMembInvoice($invoiceObj, $invUpdateData){
        $invItemObj = $invoiceObj->invoiceitem()->where('inp_product_id', $invUpdateData['existMembrId'])->where('inp_type','membership')->orderBy('inp_id','asc')->first();

        $tax = round((($invUpdateData['taxPerc'] * $invUpdateData['total']) / 100), 2);
        $invoiceObj->inv_total = $invUpdateData['total'];
        $invoiceObj->inv_incl_tax = $tax;
        $invoiceObj->inv_due_date = $invUpdateData['dueDate'];
        $invoiceObj->save();

        $invItemObj->inp_product_id = $invUpdateData['currentMembrId'];
        $invItemObj->inp_price = $invUpdateData['total'] - $tax;
        $invItemObj->inp_total = $invUpdateData['total'];
        if(array_key_exists('disc', $invUpdateData))
            $invItemObj->inp_item_desc = $invUpdateData['disc']; 
        $invItemObj->save();
    }


    /**
     * Add more membership item in client membership
     *
     * @param
     * @return
     */
    protected function newInvItemInOldInvoice($invoiceObj, $total, $existMembrId, $invoiceGoesTo, $adjustedEmiAmount, $taxPerc){

        $tax = round((($taxPerc * $total) / 100), 2);
        $oldinvItemObj = $invoiceObj->invoiceitem()->where('inp_product_id', $existMembrId)->where('inp_type','membership')->orderBy('inp_id','asc')->first();
        $oldinvItemObj->inp_price = ($total - $adjustedEmiAmount) - $tax;
        $oldinvItemObj->inp_total = ($total - $adjustedEmiAmount);
        $oldinvItemObj->save();

        $invItemObj = $oldinvItemObj->replicate();
        $invItemObj->inp_tax = 'N/A';
        if($invoiceGoesTo == 'upgrade'){
            $invItemObj->inp_item_desc = 'Memebrship upgrade adjustment'.' on '.Carbon::now()->format('D, d M Y'); 
            $invItemObj->inp_total = $adjustedEmiAmount;
            $invItemObj->inp_price = $adjustedEmiAmount;
            $invoiceObj->inv_status = 'Unpaid';
        }
        else{
            $invItemObj->inp_item_desc = 'Memebrship downgrade adjustment'.' on '.Carbon::now()->format('D, d M Y'); 
            $invItemObj->inp_total = -($adjustedEmiAmount);
            $invItemObj->inp_price = -($adjustedEmiAmount);
        }

        $invItemObj->save();

        $invoiceObj->inv_total = $total;
        $invoiceObj->inv_incl_tax = $tax;
        $invoiceObj->save();
    }
}
