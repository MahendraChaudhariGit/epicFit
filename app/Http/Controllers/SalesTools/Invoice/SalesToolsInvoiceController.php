<?php
namespace App\Http\Controllers\SalesTools\Invoice;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SalesToolsInvoice;
use Illuminate\Http\Request;
use App\Location;
use Session;
use App\SalesToolsInvoicePaymentTypes;
use App\MemberShipTax;
use App\Http\Traits\SalesTools\Discount\DiscountTrait;
use App\SalesToolsDiscount;
use App\Http\Traits\SalesTools\Invoice\SalesToolsInvoiceTrait;
use App\Http\Traits\HelperTrait;

class SalesToolsInvoiceController extends Controller{
    use DiscountTrait, SalesToolsInvoiceTrait, HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        if(!Session::has('businessId'))
            abort(404);

        $salestoolsinvoice = SalesToolsInvoice::paginate(15);

        return view('SalesTools.Invoice.index', compact('salestoolsinvoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(){
        if(!Session::has('businessId'))
            abort(404);

        $locationn = Location::ofBusiness()->select('id','sales_invoice_pdf_format','location_training_area')->get();
        $paymentdata = $this->getPaymentType();
        $taxdataa = $this->membershiptax();
        foreach($taxdataa->sortBy('mtax_label') as $taxdattaa)
            $taxesdataarray[$taxdattaa->id] = $taxdattaa->mtax_label;
        
        return view('SalesTools.Invoice.create',compact('locationn','paymentdata','taxdataa','taxesdataarray'));
    }

    
    protected function membershiptax(){
        $data = MemberShipTax::where('mtax_business_id',Session::get('businessId'))->select('id','mtax_business_id','mtax_label','mtax_rate')->get();
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request){
        if(!Session::has('businessId'))
            abort(404);

        $isError = false;
        if($request->ajax())
            $msg = [];

        if(!$isError){
            $salestoolsinvoice = new SalesToolsInvoice;
            $salestoolsinvoice->sti_business_id = Session::get('businessId');
            $salestoolsinvoice->sti_payment_terms = $request->payTerms;
			$salestoolsinvoice->sti_title = $request->invTitle;
			$salestoolsinvoice->sti_registration_no = $request->bussReg;
			$salestoolsinvoice->sti_registration_type = $request->bussRegType;
			$salestoolsinvoice->sti_payment_notes = $request->payInst;
			$salestoolsinvoice->sti_next_invoice_number = $request->nxtInvNum;
			$salestoolsinvoice->sti_hide_business = $request->hideBusiness;
			$salestoolsinvoice->sti_hide_payment = $request->hidePayment;
			$salestoolsinvoice->sti_override = $request->overrideAll;
            $salestoolsinvoice->payment_company_name = $request->payment_company_name;
            $salestoolsinvoice->payment_bank = $request->payment_bank;
            $salestoolsinvoice->payment_country = $request->payment_country;
            $salestoolsinvoice->bank_account_number = $request->bank_account_number;
            $salestoolsinvoice->buss_gst = $request->buss_gst;
            $salestoolsinvoice->query_contact_name = $request->query_contact_name;
            $salestoolsinvoice->query_contact_phone = $request->query_contact_phone;
            $salestoolsinvoice->query_contact_email = $request->query_contact_email;

            if($salestoolsinvoice->sti_hide_business == null)
                $salestoolsinvoice->sti_hide_payment = 0;
            if($salestoolsinvoice->sti_hide_payment == null)
                $salestoolsinvoice->sti_hide_payment = 0;

            $dataforlocation = new Location;	
            $dataforlocation->location_training_area = '';
            $dataforlocation->sales_invoice_pdf_format = '';

            $dataforpaymenttype = new SalesToolsInvoicePaymentTypes;
            $dataforpaymenttype->stipt_payment_types = '';
            $dataforpaymenttype->stipt_business_id = '';

            if($salestoolsinvoice->save()){
                if($request->ajax()){
                    //Session::put('ifBussHasSalesToolsInvoice', true);
                    $msg['status'] = 'added';
                    $msg['message'] = displayAlert('success|SalesToolsInvoice has been saved successfully.');
                }
                else
                    Session::flash('flash_message', 'SalesToolsInvoice has been saved successfully.');
            }
        }

        if($request->ajax())
            return json_encode($msg);
        else{
            if($isError)
                abort(404);
            else
                return redirect('salestoolsinvoice');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id){
        if(!Session::has('businessId'))
            abort(404);
        $salestoolsinvoice = SalesToolsInvoice::findOrFail($id);

        return view('SalesTools/Invoice.salestoolsinvoice.show', compact('salestoolsinvoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit(){
        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-invoiceandtax'))
            abort(404);
        $salestoolsinvoice = SalesToolsInvoice::where('sti_business_id',Session::get('businessId'))->first();
       /* if(!$salestoolsinvoice)
            $salestoolsinvoice = $this->createInvoice();*/
       
        //$locationn = Location::ofBusiness()->select('id','sales_invoice_pdf_format','location_training_area')->get();
        $paymentdata = $this->getPaymentType();
        $taxdataa = $this->membershiptax();
        $taxesinuse = $this->taxesInUse();
        $countries = ['' => '-- Select --'] + \Country::getCountryLists();
        $taxesdataarray = [];
        foreach($taxdataa->sortBy('mtax_label') as $taxdattaa)
            $taxesdataarray[$taxdattaa->id] = $taxdattaa->mtax_label;

        return view('SalesTools.Invoice.edit',compact('salestoolsinvoice'/*,'locationn'*/,'paymentdata','taxdataa','taxesdataarray','taxesinuse','countries'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request){
        $isError = false;
        if(!Session::has('businessId') || !isUserEligible(['Admin'], 'edit-invoiceandtax')){
            if($request->ajax())
                $isError = true;
            else
                abort(404);
        }

        if(!$isError){
            if($request->ajax()){
                $msg = [];
                $salestoolsinvoice = SalesToolsInvoice::find($id);
                if(!$salestoolsinvoice){
                    $msg['status'] = 'error';
                    $msg['errorData'][] = array('invalidRecord' => 'This salestoolsinvoice doesn\'t exist');
                    $isError = true;
                }
            }
            else{
                $salestoolsinvoice = SalesToolsInvoice::findOrFail($id);
                /*if($salestoolsinvoice->sti_next_invoice_number <= $request->nxtInvNum)
                    $isError = true; */  
            }

            if(!$isError){
                if($request->has('overrideexist')){
                    
                    //$salestoolsinvoice->sti_override = $request->overrideall;
                    SalesToolsDiscount::where('std_business_id',Session::get('businessId'))->update(['std_tax'=>$request->overrideall]);
                }
                else{
                    //$salestoolsinvoice->sti_business_id = Session::get('businessId');
                    $salestoolsinvoice->sti_payment_terms = $request->payTerms;
                    $salestoolsinvoice->sti_title = $request->invTitle;
                    $salestoolsinvoice->sti_registration_no = $request->bussReg;
                    $salestoolsinvoice->sti_registration_type = $request->bussRegType;
                    $salestoolsinvoice->sti_payment_notes = $request->payInst;
                    $salestoolsinvoice->sti_next_invoice_number = $request->nxtInvNum;
                    $salestoolsinvoice->sti_hide_business = $request->hideBusiness;
                    $salestoolsinvoice->sti_hide_payment = $request->hidePayment;
                    $salestoolsinvoice->sti_override = $request->overrideAll;
                    $salestoolsinvoice->payment_company_name = $request->payment_company_name;
                    $salestoolsinvoice->payment_bank = $request->payment_bank;
                    $salestoolsinvoice->payment_country = $request->payment_country;
                    $salestoolsinvoice->bank_account_number = $request->bank_account_number;
                    $salestoolsinvoice->buss_gst = $request->buss_gst;
                    $salestoolsinvoice->query_contact_name = $request->query_contact_name;
                    $salestoolsinvoice->query_contact_phone = $request->query_contact_phone;
                    $salestoolsinvoice->query_contact_email = $request->query_contact_email;

                    if($salestoolsinvoice->sti_hide_business == null)
                        $salestoolsinvoice->sti_hide_payment = 0;
                    if($salestoolsinvoice->sti_hide_payment == null)
                        $salestoolsinvoice->sti_hide_payment = 0;



                    $formData =$request->all();
                    ksort($formData);
                    $taxName = $taxNameId = $taxRate = $newTaxName = $newTaxRate = [];
                    foreach($formData as $key => $value){
                        if(strpos($key, 'taxName') !== false)
                            $taxName[] = $value;
                        else if(strpos($key, 'taxid') !== false)
                            $taxNameId[] = $value;
                        else if(strpos($key, 'taxRate') !== false)
                            $taxRate[] = $value;
                        else if(strpos($key, 'newTaxName') !== false)
                            $newTaxName[] = $value;
                        else if(strpos($key, 'newTaxRate') !== false)
                            $newTaxRate[] = $value;
                    }
                    //dd($taxName);
                    // if(count($paymentType) && count($paymentTypeId)){
                    //     for($i=0;$i<count($paymentType);$i++){
                    //     SalesToolsInvoicePaymentTypes::where('stipt_id',$paymentTypeId[$i])->where('stipt_business_id',Session::get('businessId'))->update(['stipt_payment_types'=>$paymentType[$i]]);
                    //     }
                    // }

                    SalesToolsInvoicePaymentTypes::where('stipt_business_id',Session::get('businessId'))->delete();
                    foreach($request->payment_type as $item){
                        $timestamp = createTimestamp();
                        $paymentTypesData[] = [
                                    'stipt_payment_types' => $item,
                                    'stipt_business_id' => Session::get('businessId'),
                                    'created_at' => $timestamp,
                                    'updated_at' => $timestamp
                                ];
                    }
                    if(count($paymentTypesData)){
                        SalesToolsInvoicePaymentTypes::insert($paymentTypesData);
                    }
                    if(count($taxName) && count($taxNameId) && count($taxRate)){
                        for($i=0;$i<count($taxName);$i++){
                        MemberShipTax::where('id',$taxNameId[$i])->where('mtax_business_id',Session::get('businessId'))->update(['mtax_label'=>$taxName[$i] ,'mtax_rate'=>$taxRate[$i]]);
                        }
                    }

                    MemberShipTax::whereNotIn('id',$taxNameId )->where('mtax_business_id',Session::get('businessId'))->delete();

                    if(count($newTaxName) && count($newTaxRate)){
                        $newtaxArray =[];
                        for($i=0;$i<count($newTaxName);$i++){
                            $timestamp = createTimestamp();
                            $newtaxArray[] = ['mtax_label'=>$newTaxName[$i],'mtax_rate'=>$newTaxRate[$i],'mtax_business_id'=>Session::get('businessId'),'created_at'=>$timestamp,'updated_at'=>$timestamp];  
                        }
                        if(count($newtaxArray))
                        MemberShipTax::insert($newtaxArray);
                    }

                    $salestoolsinvoice->save(); 
                    Session::put('message', 'success|Invoice has been saved successfully.');
                }
                    if($request->ajax()){
                        $msg['status'] = 'updated';
                        //$msg['message'] = displayAlert('success|Invoice has been saved successfully.');
                    }
                    else
                        Session::flash('flash_message', 'SalesToolsInvoice has been saved successfully.');
                    //return redirect()->route('salestools.invoice.edit')->with('message', 'success|Invoice has been saved successfully.');
                }
            }

        if($request->ajax())
            return json_encode($msg);
        else{
            if($isError)
                abort(404);
            else
                return redirect('salestoolsinvoice');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
     public function destroy($id){
       if(!Session::has('businessId') || !isUserEligible(['Admin'], 'delete-'))
            abort(404);
        SalesToolsInvoice::destroy($id);

        Session::flash('flash_message', 'SalesToolsInvoice deleted!');

        return redirect('salestoolsinvoice');
    }

}
