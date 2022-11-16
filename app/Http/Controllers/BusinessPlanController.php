<?php 
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
/*use DB;
use Auth;
use Input;
use Validator;*/
use App\BusinessPlan;
use Session;

class BusinessPlanController extends Controller{	
	public function index(){
		if(!Session::has('businessId'))
            abort(404);

        $businessplan = BusinessPlan::where('bp_business_id', Session::get('businessId'))->first();

        return view('businessplan.index', compact('businessplan'));
	}

	public function store(Request $request){
        $msg=[];
		if(!Session::has('businessId'))
            abort(404);
         //dd($request->all());
         if($request->has('businessplan_id') && $request->businessplan_id){
            $businessPlan=BusinessPlan::find($request->businessplan_id);
        }
        else{
            $businessPlan= new BusinessPlan;
            $businessPlan->bp_business_id= Session::get('businessId');
        }

        /*$businessplan_col_name=$request->col_name;
         switch ($businessplan_col_name) {
            case "company":
                $businessPlan->bp_company=$request->text_data;
                break;
            case "services_products":
                $businessPlan->bp_services_products=$request->text_data;
                break;
            case "market_analysis":
                $businessPlan->bp_market_analysis=$request->text_data;
                break;
            case "business_stratergy":
                $businessPlan->bp_business_stratergy=$request->text_data;
                break;
            case "management":
                $businessPlan->bp_management=$request->text_data;
                break;
            case "financial_plan":
                $businessPlan->bp_financial_plan=$request->text_data;
                break;
            
        }*/

        /*if($request->has('company'))
              $businessPlan->bp_company=$request->company;
        if($request->has('services_products'))
              $businessPlan->bp_services_products=$request->services_products;
        if($request->has('market_analysis'))
              $businessPlan->bp_market_analysis=$request->market_analysis;
        if($request->has('business_stratergy'))
              $businessPlan->bp_business_stratergy=$request->business_stratergy;
        if($request->has('management'))
              $businessPlan->bp_management=$request->management;
        if($request->has('financial_plan'))
              $businessPlan->bp_financial_plan=$request->financial_plan;*/


        $groupName=$request->group_name;
        switch ($groupName) {
            case "ex_summary":
                $businessPlan->bp_company=$request->company;
                $businessPlan->bp_services_products=$request->services_products;
                $businessPlan->bp_market_analysis=$request->market_analysis;
                $businessPlan->bp_business_stratergy=$request->business_stratergy;
                $businessPlan->bp_management=$request->management;
                $businessPlan->bp_financial_plan=$request->financial_plan;
                break;
            case "company_summary":
                $businessPlan->bp_company_ownership_location=$request->company_ownership_location;
                break;
            case "services_products":
                $businessPlan->bp_description=$request->description;
                $businessPlan->bp_features_benefits=$request->features_benefits;
                $businessPlan->bp_competitors=$request->competitors;
                $businessPlan->bp_competitive_advantage=$request->competitive_advantage;
                $businessPlan->bp_future_expansion=$request->future_expansion;
                break;
            case "market_analysis":
                $businessPlan->bp_niche_market=$request->niche_market;
                $businessPlan->bp_market_size=$request->market_size;
                $businessPlan->bp_current_trends=$request->current_trends;
                $businessPlan->bp_swot_analysis=$request->swot_analysis;
                break;
            case "ms_and_bi":
                $businessPlan->bp_business_philosophy=$request->business_philosophy;
                $businessPlan->bp_web_presence=$request->web_presence;
                $businessPlan->bp_marketing_strategy=$request->marketing_strategy;
                $businessPlan->bp_sales_strategy=$request->sales_strategy;
                $businessPlan->bp_strategic_alliances=$request->strategic_alliances;
                $businessPlan->bp_company_objectives_and_vision=$request->company_objectives_and_vision;
                $businessPlan->bp_exit_strategy=$request->exit_strategy;
                break;
            case "managament_summary":
                $businessPlan->bp_management_structure=$request->management_structure;
                break;
            case "financial_plan_group":
                $businessPlan->bp_startup_req_and_alloc_capital=$request->startup_req_and_alloc_capital;
                $businessPlan->bp_cash_flow_proj_and_bal_sheets=$request->cash_flow_proj_and_bal_sheets;
                $businessPlan->bp_assumptions=$request->assumptions;
                break;
            
        }
        if($businessPlan->save()){
           $msg['insertedId']=$businessPlan->bp_id; 
           $msg['status']='succsess'; 
        }
        else{
            $msg['status']='error'; 
        }

        return json_encode($msg); 
        //return view('businessplan.index', compact('businessplan'));
        
	}
}	