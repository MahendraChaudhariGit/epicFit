<?php
namespace App\Http\Traits\SalesTools\Discount;
use Session;
use App\SalesToolsDiscount;

trait DiscountTrait{
	protected function taxesInUse(){
		$taxrate = SalesToolsDiscount::where('std_business_id',Session::get('businessId'))->where('std_tax','!=',0)->select('std_tax')->groupBy('std_tax')->get()->pluck('std_tax')->toArray();
		return $taxrate;
	}
}

?>