<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FtSetup extends Model
{

	protected $table = 'ft_setup';

	protected $fillable = [
		'business_id' ,

		// Your Tax Preference
		'business_type',
		'is_gst_registered',

		// Setup Expenses section 
		'setup_expenses' ,
		'se_calculated' ,
		'se_gst_excl' ,
		'se_gst_paid' ,
		'se_est_capital' ,
		'se_add_capital_req' ,
		'se_repayment_monthly_period_capital_req' ,
		'se_interest_rate_fr_loan' ,
		'se_interest' ,
		'se_total' ,
		'se_monthly_repayment' ,

		// Business Expenses section 
		'business_expenses' ,
		'be_calculated' , 
		'be_gst_excl' , 
		'be_gst_paid' , 
		'be_loan_repayment_amt' , 
		'be_total_exp_per_mnth' , 
		'be_profit_req_after_tax' , 
		'be_tax_payable' , 
		'be_gst_paid_per_annum' , 
		'be_total_exp_per_annum' , 
		'be_profit_after_tax' , 

		// Living Expenses section 
		'living_expenses' ,
		'le_calculated' , 
		'le_gst_excl' , 
		'le_gst_paid' , 

		// partnership
		'partnership_total',
		'partnership_expenses',
		'no_of_partner',

		// Session Required section 
		'session_rate'	,
		'session_req',
		'ann_working_weeks',
		'weekly_sess_req',
		'no_of_clients',
		'session_spots_req',
		'avg_sess_pweek_pclient',
		'clients_req_to_break_even',
		'average_client_spend',
		'estimated_client_cap',

		// Client Required section 
		'client_active_current',
		'session_req_per_week',
		'clients_req_6_per_session',
		'timeframe_capicity',
		'projected_cons_conv_rate',
		'consultations_req',
		'contact_conv_rate',
		'leads_req',
		'avg_lead_gen_req_per_week_fr_52',
		'avg_consult_week',
		'avg_singed_client_week',

	];
}
