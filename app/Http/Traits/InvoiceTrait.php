<?php
namespace App\Http\Traits;
use Carbon\Carbon;
use App\SalesToolsInvoice;
use App\SalesToolsInvoicePaymentTypes;
use App\MemberShipTax;
use App\SalesToolsDiscount;
use Session;
use Auth;

trait InvoiceTrait {
	/**
	 * get tax data
	 * @param taxAppliedId
	 * @return Array tax data
	**/
 	protected function getTax($taxAppliedId){
    	$data = MemberShipTax::where('mtax_business_id',Session::get('businessId'))->select('id','mtax_label','mtax_rate')->get();

    	$response = array('taxdata'=>array(),'alltax'=>array());
    	if($data->count()){
    		if($taxAppliedId)
    			$response['taxdata'] = $data->where('id', $taxAppliedId)->first();
    		else 
    			$response['taxdata'] = $data->first();

    		$response['alltax'] = $data->toArray();
    	}
    	return $response;
	}
    

	/**
	 * get Discount 
	 * @param void
	 * @return array discount
	**/
	protected function getDiscount(){
		$discount = [];
    	$data = SalesToolsDiscount::with('tax')->where('std_business_id',Session::get('businessId'))->get();
	  	if($data->count()){
	  		$i=0;
		  	foreach ($data as $value) {
		  		$discount[$i]['id']= $value->std_id;
		  		$discount[$i]['name']= $value->std_name;
		  		$discount[$i]['type']= $value->std_type;
		  		$discount[$i]['price']= $value->std_value;
		  		if($value->tax){
			  		$discount[$i]['disTax']= $value->tax->mtax_label;
			  		$discount[$i]['disTaxRate']= $value->tax->mtax_rate;
			  	}else{
			  		$discount[$i]['disTax'] = 'N/A';
			  		$discount[$i]['disTaxRate'] = 0;	
			  	}
		  		$i++;
		  	}
		} 	
    	return $discount;
	}
}