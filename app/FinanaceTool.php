<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinanaceTool extends Model
{
    protected $table = 'ft_company_finances';
    
    protected $fillable = [
	    'business_id',
	    'tax_type',
	    'is_gst_registered',
	    'gst_no',
	    'gst_percentage',
	    'setup_expenses',
	    'setup_exp_calculated',
	    'setup_exp_est',
	    'setup_exp_gst_incl',
	    'business_expenses',
	    'business_exp_calculated',
	    'business_exp_est',
	    'business_exp_gst_incl',
	    'living_expenses',
	    'living_exp_calculated',
	    'living_exp_est',
	    'living_exp_gst_incl',
	    'turnover_req',
	    'profit_req',
	    'gst',
	    'company_tax',
	    'personal_tax',
	    'turnover_and_taxes',
	    'eqv_full_sess_cor',
	    'eqv_full_sess_req_for_tot_inc',
	    'ann_working_weeks',
	    'weekly_sess_req',
	    'avg_sess_pweek_pclient',
	    'no_of_clients',
	    'cap_req_first_three_months',
	    'presonal_capital_available',
	    'total_client_req',
	    'projected_conv_rate',
	    'consultations_req',
	    'leads_req',
	    'avg_lead_gen_req_per_week_twlv',
	    'clients_req_eqv_full_sess_cor',
		'clients_req_eqv_full_sess_req_for_tot_inc',
		'clients_req_ann_working_weeks',
		'clients_req_weekly_sess_req',
		'clients_req_no_of_clients',
		'clients_req_avg_sess_pweek_pclient',
		'session_spots_req',
		'clients_req_to_break_even',
		'average_client_spend',
		'client_session_spots_req',
		'client_clients_req_to_break_even',
		'client_average_client_spend',
		'cashflow_forecast_time',
		'monthly_business_cost',
		'monthly_personal_cost',
		'total_monthly_expense',
		'cashflow_setup_expense',
		'setup_loan_repayment_period_month_time',
		'setup_loan_repayment_per_month_amt',
		'loan_req',
		'monthly_req',
		'partnership_tax',
		'lead_gen_session_req_per_week',
		'lead_gen_timeframe_capicity',
		'lead_gen_contact_conv_rate',
		'lead_gen_avg_consult_week',
		'lead_gen_avg_singed_client_week',
    ];
}