<?php

namespace App\Http\Controllers;

use App\FtTaxPreference;
use App\FtTaxSlab;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Traits\HelperTrait;
use App\User;
use App\Business;
use Auth;
use Carbon\Carbon;
use Session;
use DB;
use App\FinanaceTool;
use Talk;
use App\FtSetup;
use App\FtPartnership;

class FinanceController extends Controller {

    use HelperTrait;

    public function showFinancialTools1(){
        $businessId = auth()->user()->business_id;
        $financeData = FtSetup::where('business_id','=',$businessId)->first();
        $settingPrefData = FtTaxPreference::where('business_id','=',$businessId)->where('tax_type','=','gst')->first();
        $timeframe = FtTaxPreference::where('business_id','=',$businessId)->first();
        $ftPartnership = FtPartnership::where('business_id','=',$businessId)->get();
        $ftPartnershipData['profit_percentage'] = [];
        $ftPartnershipData['invested_amount'] = [];
        $ftPartnershipData['excl_gst'] = [];
        $ftPartnershipData['gst_paid'] = [];
        foreach($ftPartnership as $key => $partnership){
           $ftPartnershipData['profit_percentage'][] = $partnership->profit_percentage;
           $ftPartnershipData['invested_amount'][] = $partnership->invested_amount;
           $ftPartnershipData['excl_gst'][] = $partnership->excl_gst;
           $ftPartnershipData['gst_paid'][] = $partnership->gst_paid;
        }
        return view('financialtoolnew.index',compact('financeData','settingPrefData','timeframe','ftPartnershipData'));
    }

    public function saveExpenses(Request $request){
        $inputs['tax_type'] = ($request->tax_type) ? $request->tax_type : '';
        $inputs['is_gst_registered'] = $request->is_gst_registered == '1' ? 1 : 0;
        $inputs['gst_no'] = isset($request->gst_no) ? $request->gst_no : '';
        $inputs['gst_percentage'] = isset($request->gst_percentage) ? $request->gst_percentage : 0;
        $inputs['setup_expenses'] = $request->setup_expenses;
        $inputs['setup_exp_calculated'] = ($request->setup_exp_calculated) ? $request->setup_exp_calculated : 0;
        $inputs['setup_exp_est'] = $request->setup_exp_est;
        $inputs['setup_exp_gst_incl'] = $request->setup_exp_gst_incl;
        // $inputs['setup_exp_gst_excl'] = $request->setup_exp_gst_excl;
        $inputs['business_expenses'] = $request->business_expenses;
        $inputs['business_exp_calculated'] = ($request->business_exp_calculated) ? $request->business_exp_calculated : 0;
        $inputs['business_exp_est'] = $request->business_exp_est;
        $inputs['business_exp_gst_incl'] = $request->business_exp_gst_incl;
        // $inputs['business_exp_gst_excl'] = $request->business_exp_gst_excl;
        $inputs['living_expenses'] = $request->living_expenses;
        $inputs['living_exp_calculated'] = ($request->living_exp_calculated) ? $request->living_exp_calculated : 0;
        $inputs['living_exp_est'] = $request->living_exp_est;
        $inputs['living_exp_gst_incl'] = $request->living_exp_gst_incl;
        // $inputs['living_exp_gst_excl'] = $request->living_exp_gst_excl;
        $inputs['partnership_expenses'] = $request->partnership_expenses;
        $inputs['business_id']= !empty(auth()->user()->business_id) ? auth()->user()->business_id : 0;

        // $partnershipInput['profit_percentage'] = $request->profit_percentage;
        // $partnershipInput['invested_amount'] = $request->invested_amount;
        // $partnershipInput['inc_gst'] = $request->inc_gst;
        // $partnershipInput'exc_gst'] = $request->exc_gst;

        // check if exist, update otherwise
        $isFinanceExists = FinanaceTool::where('business_id','=',$inputs['business_id'])->first();
        if(!empty($isFinanceExists)){
            if ($isFinanceExists->update($inputs)) {
                return response()->json([ 'status' => 200, 'msg' => 'Expenses saved successfully!',
                                          'data'=>['id'=>$isFinanceExists->id]], 200);
            }
        }
        // create if new
        $finance = FinanaceTool::create($inputs);
        if (!empty($finance->id)) {
            return response()->json([ 'status' => 200, 'msg' => 'Expenses saved successfully!',
                                      'data'=>['id'=>$finance->id]], 200);
        }
        return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
    }

    public function saveBusinessStructure(Request $request){
	    $inputs['business_type'] = ($request->business_type) ? $request->business_type : '';
	    $inputs['is_gst_registered'] = $request->is_gst_registered == '1' ? 1 : 0;
        $inputs['gst_no'] = isset($request->gst_no) ? $request->gst_no : '';
	    $inputs['gst_percentage'] = isset($request->gst_percentage) ? $request->gst_percentage : 0;
    	$inputs['business_id']= !empty(auth()->user()->business_id) ? auth()->user()->business_id : 0;

	    // check if exist, update otherwise
    	$isFinanceExists = FtSetup::where('business_id','=',$inputs['business_id'])->first();
        if(!empty($isFinanceExists)){
	        if ($isFinanceExists->update($inputs)) {
	        	return response()->json([ 'status' => 200, 'msg' => 'Expenses saved successfully!',
		                                  'data'=>['id'=>$isFinanceExists->id]], 200);
	        }
        }
        // create if new
    	$finance = FtSetup::create($inputs);
	    if (!empty($finance->id)) {
	    	return response()->json([ 'status' => 200, 'msg' => 'Expenses saved successfully!',
		                              'data'=>['id'=>$finance->id]], 200);
	    }
	    return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
    }

	public function saveSetupExp(Request $request,$id){
		if(empty($id)){
			$id = !empty(auth()->user()->business_id) ? auth()->user()->business_id : 0;
		}
		$finance = FtSetup::where('business_id','=',$id)->first();
		if(empty($finance)){
			return response()->json([ 'status' => 404, 'msg'=> 'Record not found!','data'=>null], 200);
		}
		// FIelds 

        $inputs['setup_expenses'] = $request->setup_expenses;

        $inputs['se_calculated'] =  $request->se_calculated;
        $inputs['se_gst_excl'] =  $request->se_gst_excl;
        $inputs['se_gst_paid'] =  $request->se_gst_paid;
        $inputs['se_est_capital'] =  $request->se_est_capital;
        $inputs['se_add_capital_req'] =  $request->se_add_capital_req;
        $inputs['se_repayment_monthly_period_capital_req'] =  $request->se_repayment_monthly_period_capital_req;
        $inputs['se_interest_rate_fr_loan'] =  $request->se_interest_rate_fr_loan;
        $inputs['se_interest'] =  $request->se_interest;
        $inputs['se_total'] =  $request->se_total;
        $inputs['se_monthly_repayment'] =  $request->se_monthly_repayment;


		if ($finance->update($inputs)) {
			return response()->json([ 'status' => 200, 'msg' => 'saveSetupExp saved successfully!',
			                          'data'=>['id'=>$finance->business_id]], 200);
		}
		return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
	}

	public function saveOperationExp(Request $request,$id)
	{
		if(empty($id)){
			$id = !empty(auth()->user()->business_id)? auth()->user()->business_id : 0;
		}
		$finance = FtSetup::where('business_id','=',$id)->first();
		if(empty($finance)){
			return response()->json([ 'status' => 404, 'msg'=> 'Record not found!','data'=>null], 200);
		}
        // Fields

              // business_expenses
        $inputs['business_expenses'] = $request->business_expenses;
        $inputs['be_calculated'] = $request->be_calculated;
        $inputs['be_gst_excl'] = $request->be_gst_excl;
        $inputs['be_gst_paid'] = $request->be_gst_paid;
        $inputs['be_loan_repayment_amt'] = $request->be_loan_repayment_amt;
        $inputs['be_total_exp_per_mnth'] = $request->be_total_exp_per_mnth;
        $inputs['be_profit_req_after_tax'] = $request->be_profit_req_after_tax;
        $inputs['be_tax_payable'] = $request->be_tax_payable;
        $inputs['be_gst_paid_per_annum'] = $request->be_gst_paid_per_annum;
        $inputs['be_total_exp_per_annum'] = $request->be_total_exp_per_annum;
        $inputs['be_profit_after_tax'] = $request->be_profit_after_tax;

        // living_expense
        $inputs['living_expenses'] = $request->living_expenses;
        $inputs['le_calculated']   =   $request->le_calculated;
        $inputs['le_gst_excl']     =   $request->le_gst_excl;
        $inputs['le_gst_paid']     =   $request->le_gst_paid;

        // Partnership
        $inputs['partnership_expenses'] = $request->partnership_expenses;
        $partnership_total = 0;
        if(!empty($request->profit_percentage) && $request->business_type == 'partnership') // If partnership only
        {
            if(FtPartnership::where('business_id',auth()->user()->business_id)->count() > 0)
            {
                FtPartnership::where('business_id',auth()->user()->business_id)->delete();
            }
            foreach($request->profit_percentage as $key => $val)
            {
                $data['business_id'] = !empty(auth()->user()->business_id) ? auth()->user()->business_id : 0;
                $data['profit_percentage'] = $val;
                $data['invested_amount'] = $request->invested_amount[$key];
                $data['excl_gst'] = $request->gst_excl[$key];
                $data['gst_paid'] = $request->gst_paid[$key];
                $partnership_total +=  $request->invested_amount[$key];
                FtPartnership::create($data);
            }
        }
        
        $inputs['partnership_total'] = $partnership_total;
        $inputs['no_of_partner'] = $request->no_of_partner;


	    if ($finance->update($inputs)) {
	    	return response()->json([ 'status' => 200, 'msg' => 'saveOperationExp successfully!',
		                              'data'=>['id'=>$finance->business_id]], 200);
	    }
		return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
	}

	public function saveSaleProjection(Request $request,$id)
	{
		if(empty($id)){
			$id = !empty(auth()->user()->business_id)? auth()->user()->business_id : 0;
		}
		$finance = FtSetup::where('business_id','=',$id)->first();
		if(empty($finance)){
			return response()->json([ 'status' => 404, 'msg'=> 'Record not found!','data'=>null], 200);
		}
		
        // Session Required section 
        $inputs['session_rate']   = $request->session_rate;
        $inputs['session_req'] = $request->session_req;
        $inputs['ann_working_weeks'] = $request->ann_working_weeks;
        $inputs['weekly_sess_req'] = $request->weekly_sess_req;
        $inputs['no_of_clients'] = $request->no_of_clients;
        $inputs['session_spots_req'] = $request->session_spots_req;
        $inputs['avg_sess_pweek_pclient'] = $request->avg_sess_pweek_pclient;
        $inputs['clients_req_to_break_even'] = $request->clients_req_to_break_even;
        $inputs['average_client_spend'] = $request->average_client_spend;
        $inputs['estimated_client_cap'] = $request->estimated_client_cap;

        // Client Required section 
        $inputs['client_active_current'] = $request->client_active_current;
        $inputs['session_req_per_week'] = $request->session_req_per_week;
        $inputs['clients_req_6_per_session'] = $request->clients_req_6_per_session;
        $inputs['timeframe_capicity'] = $request->timeframe_capicity;
        $inputs['projected_cons_conv_rate'] = $request->projected_cons_conv_rate;
        $inputs['consultations_req'] = $request->consultations_req;
        $inputs['contact_conv_rate'] = $request->contact_conv_rate;
        $inputs['leads_req'] = $request->leads_req;
        $inputs['avg_lead_gen_req_per_week_fr_52'] = $request->avg_lead_gen_req_per_week_fr_52;
        $inputs['avg_consult_week'] = $request->avg_consult_week;
        $inputs['avg_singed_client_week'] = $request->avg_singed_client_week;

	    if ($finance->update($inputs)) {
	    	return response()->json([ 'status' => 200, 'msg' => 'saveSaleProjection saved successfully!',
		                              'data'=>['id'=>$finance->business_id]], 200);
	    }
	    return response()->json([ 'status' => 201, 'msg'=> 'Something went wrong!','data'=>null], 200);
	}

	/* Settings and preferences */

	public function showSettingsAndPreferences()
	{
        $businessId = auth()->user()->business_id;
        $ftPref = FtTaxPreference::where('business_id',auth()->user()->business_id)->get();
		$timeFrame = FtTaxPreference::where('business_id',auth()->user()->business_id)->first();
		$companyTaxes = collect($ftPref)->filter(function ($value, $key) {
			return $value->tax_category == 'company';
		});
		$partnershipTaxes = collect($ftPref)->filter(function ($value, $key) {
			return $value->tax_category == 'partnership';
		});
		$soleTraderTaxes = collect($ftPref)->filter(function ($value, $key) {
            return $value->tax_category == 'sole-trader';
        });
        $gstTaxes = collect($ftPref)->filter(function ($value, $key) {
			return $value->tax_category == 'gst';
		});
		return view('financialtool.settings-and-preferences.index',
			compact(['companyTaxes','partnershipTaxes','soleTraderTaxes','gstTaxes','timeFrame']));
	}

	public function saveSettingsAndPreferences(Request $request)
	{
        $businessId = auth()->user()->business_id;
		$inputs = $request->only('tax_category','tax_type','tax_code','tax_amount','country');
        $inputs['tax_name'] = $request->tax_type == 'other' ? $request->tax_name : null;
		$inputs['business_id'] = $businessId;
		try{
			$taxPreference = FtTaxPreference::create($inputs);
            
			if (!empty($request->income_tax)) {
				$income_tax = [];
				foreach ($request->income_tax as $incomeTax) {
					$income_tax[] = [
						'tax_preference_id' => $taxPreference->id,
						'from_amount'       => !empty($incomeTax['cfrom_amount']) ? $incomeTax['cfrom_amount'] : 0,
						'to_amount'         => !empty($incomeTax['cto_amount']) ? $incomeTax['cto_amount'] : 0,
						'tax_percentages'   => !empty($incomeTax['ctax_percentages']) ? $incomeTax['ctax_percentages'] : 0
					];
				}
				if(!FtTaxSlab::insert($income_tax)){
					return response()->json(['status'=>'error','msg'=>'Whoops! Something went wrong.']);
				}
			}
			if(!$taxPreference->id){
				return response()->json(['status'=>'error','msg'=>'Whoops! Something went wrong.']);
			}
			$msg = !empty($inputs['tax_category']) ? ucfirst($inputs['tax_category']).' Tax saved successfully!'
				: 'Tax saved successfully!';
			return response()->json(['status'=>'success','msg'=>$msg]);
		}
		catch (\Exception $e){
			return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
		}
	}

    public function editSettingsAndPreferences($id)
    {
        try{
            $taxPreference = FtTaxPreference::with('slabs')
                ->where('id','=',$id)->firstOrFail();
            return response()->json(['status'=>'success','data'=>$taxPreference]);
        }
        catch (\Exception $e){
            return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
        }
    }

	public function updateSettingsAndPreferences($id,Request $request)
	{
		try{
			$taxPreference = FtTaxPreference::where('id','=',$id)->firstOrFail();

			$inputs = $request->only('tax_category','tax_type','tax_code','tax_amount','country');
			$inputs['tax_name'] = $request->tax_type == 'other' ? $request->tax_name : null;

			if(!$taxPreference->update($inputs)){
                return response()->json(['status'=>'error','msg'=>'Whoops! Something went wrong.']);
            }

            if (!empty($request->income_tax)) {
				$income_tax_new = [];
				$update_slabs = [];
				foreach ($request->income_tax as $incomeTax) {
					if($incomeTax['slab_id'] == 0){
						$income_tax_new[] = [
							'tax_preference_id' => $taxPreference->id,
							'from_amount'       => !empty($incomeTax['cfrom_amount']) ? $incomeTax['cfrom_amount'] : 0,
							'to_amount'         => !empty($incomeTax['cto_amount']) ? $incomeTax['cto_amount'] : 0,
							'tax_percentages'   => !empty($incomeTax['ctax_percentages']) ? $incomeTax['ctax_percentages'] : 0
						];
					}else{
						$update_slabs[] = $incomeTax['slab_id'];
						$update = FtTaxSlab::where('id','=',$incomeTax['slab_id'])->update([
							'tax_preference_id' => $taxPreference->id,
							'from_amount'       => !empty($incomeTax['cfrom_amount']) ? $incomeTax['cfrom_amount'] : 0,
							'to_amount'         => !empty($incomeTax['cto_amount']) ? $incomeTax['cto_amount'] : 0,
							'tax_percentages'   => !empty($incomeTax['ctax_percentages']) ? $incomeTax['ctax_percentages'] : 0
						]);
					}
				}
				FtTaxSlab::whereNotIn('id',$update_slabs)->where('tax_preference_id','=',$id)->delete();
				if(!FtTaxSlab::insert($income_tax_new)){
					return response()->json(['status'=>'error','msg'=>'Whoops! Something went wrong.']);
				}
			}
			$msg = !empty($inputs['tax_category']) ? ucfirst($inputs['tax_category']).' Tax updated successfully!'
				: 'Tax updated successfully!';
			return response()->json(['status'=>'success','msg'=>$msg]);
		}
		catch (\Exception $e){
			return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
		}
	}

	public function deleteSettingsAndPreferences($id)
	{
		try{
			$taxPreference = FtTaxPreference::where('id','=',$id)->firstOrFail();
			FtTaxSlab::where('tax_preference_id','=',$id)->delete();
			if(!$taxPreference->delete()){
				return response()->json(['status'=>'error','msg'=>'Record not deleted!']);
			}
		}
		catch (\Exception $e){
			return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
		}
		return response()->json(['status'=>'success','msg'=>'Record deleted successfully']);
	}

    public function cloneTax($id) {

        try{
            $taxPreference = FtTaxPreference::where('id','=',$id)->with('slabs')->firstOrFail();
            if(!$taxPreference){
                return response()->json(['status'=>'error','msg'=>'Record not found!']);
            }
            $inputs = [];
            if(!empty($taxPreference->tax_category)) {
                if($taxPreference->tax_category == 'sole-trader') {
                    $inputs['tax_category'] = 'partnership';
                } elseif($taxPreference->tax_category == 'partnership') {
                    $inputs['tax_category'] = 'sole-trader';
                }
            }

            $inputs['tax_type'] = $taxPreference->tax_type;
            $inputs['tax_amount'] = $taxPreference->tax_amount;
            $inputs['tax_code'] = $taxPreference->tax_code;
            $inputs['tax_name'] = $taxPreference->tax_name;
            $inputs['country'] = $taxPreference->country;
            $inputs['business_id'] = auth()->user()->business_id;

            $cloneFtTaxPreference = FtTaxPreference::create($inputs);
            
            // clone tax slab
            $slabInput = [];
            if(!empty($taxPreference->slabs)) {
                foreach ($taxPreference->slabs as $key => $taxSlab) {
                    $slabInput["tax_preference_id"] = $cloneFtTaxPreference->id; 
                    $slabInput["from_amount"] = $taxSlab->from_amount;
                    $slabInput["to_amount"] = $taxSlab->to_amount;
                    $slabInput["tax_percentages"] = $taxSlab->tax_percentages;
                    FtTaxSlab::create($slabInput);
                }
            }
        }
        catch (\Exception $e){
            return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>'success','msg'=>'Record clone successfully']);

    }

    public function getSlab(Request $request)
    {
        try{
               $amount = $request->amount;
               $tax_category = $request->tax_category;

               $taxPreference = FtTaxPreference::where('business_id',auth()->user()->business_id)->where('tax_category',$tax_category)->first();
               if(!$taxPreference){
                    return response()->json(['status'=>'success','amount'=> 0 , 'tax_amount' => 0]);
               }

               $ftTaxSlabs  = FtTaxSlab::where('tax_preference_id',$taxPreference->id)->get();

               $taxes = [];
               foreach($ftTaxSlabs as $key => $ftTaxSlab) {
                $taxes[] = [
                             'lower' => $ftTaxSlab->from_amount ,
                             'upper' => $ftTaxSlab->to_amount ,
                             'tax' => $ftTaxSlab->tax_percentages
                           ];
               }

               $band_top = [];
               $band_rate = [];

                foreach($taxes as $key => $tax){
                    $band_top[$key+1] = $tax['upper'];
                    $band_rate[$key+1] = $tax['tax'] / 100;
                }

                $band_top_new = $band_top;

                array_pop($band_top_new);

                $final = FtTaxSlab::finalCalculations($band_top_new, $band_rate, $amount);

                $total_tax_paid = 0;

                if(count($final) > 0){
                    foreach($final as $tax){
                        $total_tax_paid += $tax;
                    }
                }

                $gstInclAmount = $amount + $total_tax_paid;

                $total_tax_paid = number_format($total_tax_paid , 2 , '.' , '');
                $gstInclAmount = number_format($gstInclAmount , 2 , '.' , '');

            return response()->json(['status'=>'success','amount'=>$gstInclAmount , 'tax_amount' => $total_tax_paid]);
        }
        catch (\Exception $e){
            return response()->json(['status'=>'error','msg'=> $e->getMessage()]);
        }
    }

    public function ajaxDeleteData()
    {
        try {
            FtSetup::where('business_id',auth()->user()->business_id)->delete();
            FtPartnership::where('business_id',auth()->user()->business_id)->delete();
            return response()->json(['status'=>'success','msg'=> 'Financial Setup data deleted']);
        } catch (\Exception $e) {
            return response()->json(['status'=>'error','msg'=>$e->getMessage()]);
        }
    }

    public function ajaxupdateFinancialTimeFrame(Request $request)
    {
        try{
            if(empty($request->financial_time_frame))
            {
                return response()->json(['status'=>'success', 'msg'=> 'Invalid Request'], 200);
            }
            //  Update All Financial Time Frame
            $preferences = FtTaxPreference::where('business_id', auth()->user()->business_id)->first();
            $preferences->financial_time_frame = $request->financial_time_frame;
            $preferences->save();
            return response()->json(['status'=>'success', 'msg'=> ''.ucfirst($request->financial_time_frame).' Financial Timeframe set successfully'], 200);            
        }catch(\Exception $e) {
            return response()->json(['status'=>'errors', 'msg'=> $e->getMessage()], 401);
        }
    }
}
