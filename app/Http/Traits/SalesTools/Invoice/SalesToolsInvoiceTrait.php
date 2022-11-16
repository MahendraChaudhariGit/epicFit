<?php
namespace App\Http\Traits\SalesTools\Invoice;
use Session;
use App\SalesToolsInvoice;

trait SalesToolsInvoiceTrait{
	/**
     * Insert sales tools invoice 
     *
     * @return model 
     */
	protected function createInvoice(){
		$salestoolsinvoice = new SalesToolsInvoice;
        $salestoolsinvoice->sti_business_id = Session::get('businessId');
        $salestoolsinvoice->sti_payment_terms = 'Immediately';
        $salestoolsinvoice->sti_title = 'Invoice title';
        $salestoolsinvoice->sti_next_invoice_number = 1;
        $salestoolsinvoice->save();
        //Session::put('ifBussHasSalesToolsInvoice', true);

		return $salestoolsinvoice;
	}
}
?>