@extends('layouts.app')

@section('required-styles-for-this-page')
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
@stop

@section('page-title')
	Invoices and taxes
@stop()

@section('content')
    {!! Form::open(['url' => '', 'role' => 'form', 'id' =>'invoiceForm','class'=>'container-fluid container-fullw bg-white scrollToTop']) !!}
    {!! Form::hidden('invoiceId', $salestoolsinvoice->sti_id , ['class' => 'no-clear']) !!}
        {!! displayAlert('', true)!!}
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <fieldset class="padding-15 ">
                            <legend>
                                Invoice Settings 
                            </legend>
                            <div class="form-group">
                                {!! Form::label('payTerms', 'Payment terms for invoices *', ['class' => 'strong']) !!}
                               
                                {!! Form::select('payTerms', ['C.O.D' => 'Cash on Delivery','Immediately' => 'Immediately', '7' => 'Within 7 days', '14' => 'Within 14 days', '21' => 'Within 21 days', '30' => 'Within 30 days', '20' => '20th of the following month'], isset($salestoolsinvoice)?$salestoolsinvoice->sti_payment_terms:null, ['class' => 'form-control','required' => 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('invTitle', 'Invoice title', ['class' => 'strong']) !!}
                                
                                {!! Form::text('invTitle', isset($salestoolsinvoice)?$salestoolsinvoice->sti_title:null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('bussReg', 'Business registration number', ['class' => 'strong']) !!}
                                
                                {!! Form::text('bussReg',isset($salestoolsinvoice)?$salestoolsinvoice->sti_registration_no:null , ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('bussRegType', 'Business registration type', ['class' => 'strong']) !!}
                               
                                {!! Form::text('bussRegType', isset($salestoolsinvoice)?$salestoolsinvoice->sti_registration_type:null, ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('payInst', 'Payment instructions / notes', ['class' => 'strong']) !!}
        
                                {!! Form::textarea('payInst', isset($salestoolsinvoice)?$salestoolsinvoice->sti_payment_notes:null, ['class' => 'form-control textarea']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('nxtInvNum', 'Next invoice number *', ['class' => 'strong']) !!}
                                
                                <!-- {!! Form::number('nxtInvNum', isset($salestoolsinvoice)?$salestoolsinvoice->sti_next_invoice_number:null, ['class' => 'form-control numericField','required' => 'required']) !!} -->
                                <input type="number" name="nxtInvNum" value="{{ isset($salestoolsinvoice)?$salestoolsinvoice->sti_next_invoice_number:null }}" class="form-control numericField" required="required" min="{{ isset($salestoolsinvoice)?$salestoolsinvoice->sti_next_invoice_number:1 }}">
                                
                            </div>
							<div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                <input type="checkbox" name="hideBusiness" id="hideBusiness" value="1" {{ isset($salestoolsinvoice) && $salestoolsinvoice->sti_hide_business?'checked':'' }}>
                                <label for="hideBusiness">
                                    <strong>Hide business name, address, and logo</strong>
                                    <span class="epic-tooltip" data-toggle="tooltip" title="This is applicable only on pdf mode."><i class="fa fa-question-circle"></i></span>
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                <input type="checkbox" name="hidePayment" id="hidePayment" value="1" {{ isset($salestoolsinvoice) && $salestoolsinvoice->sti_hide_payment?'checked':'' }}>
                                <label for="hidePayment">
                                    <strong>Hide payment history on invoices</strong>
                                    <span class="epic-tooltip" data-toggle="tooltip" title="This is applicable only on pdf mode."><i class="fa fa-question-circle"></i></span>
                                </label>
                            </div>
                        </fieldset>
                        <fieldset class="padding-15 ">
                            <legend>Payment Details </legend>
                            <div class="form-group">
                                <label class="strong">Company Name</label>
                                <input type="text" name="payment_company_name" class="form-control" value="{{isset($salestoolsinvoice)?$salestoolsinvoice->payment_company_name:''}}" required="required">
                            </div>
                            <div class="form-group">
                                <label class="strong">Bank</label>
                                <input type="text" name="payment_bank" value="{{isset($salestoolsinvoice)?$salestoolsinvoice->payment_bank:''}}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="strong">Country</label>
                                <select class="form-control" name="payment_country" required>
                                    @foreach($countries as $key => $country)
                                    <option value="{{ $key}}" {{isset($salestoolsinvoice) && $salestoolsinvoice->payment_country == $key?'selected':''}}>{{$country}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="strong">Bank Account Number</label>
                                <input type="text" name="bank_account_number" value="{{isset($salestoolsinvoice)?$salestoolsinvoice->bank_account_number:''}}" class="form-control" required>
                            </div>
                        </fieldset>
                        
                    </div>
                    <div class="col-md-6 col-sm-6">
                    	<fieldset class="padding-15">
                        	<legend>
                                Taxes
                            </legend>
                            <div class="row m-b-5">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="bussGst" class="strong">Business GST number</label>
                                        <input class="form-control" name="buss_gst" value="{{isset($salestoolsinvoice)?$salestoolsinvoice->buss_gst:''}}" type="text" value="" id="bussGst" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-b-5">
                                <div class="col-md-4 col-xs-4">
                                    <strong>Tax Name</strong>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <strong>Rate</strong>
                                </div>
                                <div class="col-md-2 col-xs-2">
                                    <strong>Usage</strong>
                                </div>
                                 <div class="col-md-2 col-xs-2">
                                   <a class="btn btn-xs btn-primary" href="#" id="add-taxes-row"><i class=" fa fa-plus fa fa-white"></i></a> 
                                </div>
                            </div>
                            <!-- start  -->
                            <div class="row m-b-5 hidden taxes-box-area ">
                                <div class="form-group col-md-4 col-xs-4">
                                    {!! Form::text('name_text',null, ['class' => 'form-control onblur-event']) !!}
                                </div>
                                <div class="form-group col-md-4 col-xs-4">
                                    <div class="clearfix moveErrMsg">
                                        <div class="input-group">
                                            {!! Form::text('name_rate',null , ['class' => 'form-control no-clear price-field onblur-event']) !!} 
                                            <div class="input-group-addon"><i class="">%</i></div>
                                        </div>
                                    </div>
                                    <span class="help-block placeErrMsg"></span>
                                </div>
                                 <!-- <div class="form-group col-md-2 ">
                                    
                                 </div>  --> 
                                
                                <div class="form-group col-md-4 col-xs-4">
                                   <a class="btn btn-xs btn-red pull-right m-r-25  remove-payment-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
                                </div>
                            
                            </div>

                           <div id="taxes-row-add">
                            <?php $i=0; ?>
                             @foreach($taxdataa as $taxdata)
                            <div class="row m-b-5">
                             <input type="hidden" name="taxid{{$i}}" value="{{ $taxdata->id }}">
                                <div class="form-group col-md-4 col-xs-4">
                                    {!! Form::text('taxName'.$i,isset($taxdata)?$taxdata-> mtax_label:null, ['class' => 'form-control onblur-event','required' => 'required' ,'data-pre-val' => $taxdata-> mtax_label]) !!}
                                </div>
                                <div class="form-group col-md-4 col-xs-4">
                                    <div class="clearfix moveErrMsg">
                                        <div class="input-group">
                                        {!! Form::text('taxRate'.$i, isset($taxdata)?$taxdata->mtax_rate:null , ['class' => 'form-control no-clear price-field onblur-event', 'required' => 'required','data-pre-val' => $taxdata-> mtax_label]) !!} 
                                        <span class="input-group-addon"><i class="">%</i></span>
                                        </div>
                                    </div>
                                     <span class="help-block placeErrMsg"></span>
                                </div>  
                                <div class="form-group col-md-2 col-xs-2 taxusuage">
                                    <a class="font-14 pull-left" data-content="1 &nbsp; Service<br> 2 &nbsp; Class <br> 2 &nbsp; Products <br> 0  &nbsp; Packages <br> 2 &nbsp;  Discounts" data-placement="top" data-toggle="popover" data-trigger="hover" data-title="<strong>{{ $taxdata->mtax_label}} usuage</strong>" data-html="true">
                                    6 times
                                    </a>
                                </div>
                                @if(!in_array($taxdata->id, $taxesinuse ))
                                <div class="form-group col-md-2 col-xs-2">
                                   <a class="btn btn-xs btn-red remove-payment-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
                                </div> 
                                @endif
                            </div>
                            <?php $i++; ?>
                            @endforeach
                            </div>
                            <!-- end -->

                           <p><strong>Override all of your services, classes, discounts, products and packages with one tax rate:</strong>
                            </p>
                            <div class="sucMes hidden"></div>
                            <div class="row">
                                <div class="form-group col-md-10 col-xs-9">
                                    {!! Form::select('overrideAll',$taxesdataarray,isset($salestoolsinvoice)?$salestoolsinvoice->sti_override:null, ['class'=>'form-control createbox-hide','required'=>'required','data-title'=>'-- Select --']) !!}
                                </div>
                                <div class="col-md-1 col-xs-2 p-l-0 m-l-0">
                                <button class="btn btn-primary createbox-hide" id="applybtn">Apply</button>
                            </div>
                            </div>


                        </fieldset>
                        <fieldset class="padding-15">
                        	<legend>
                                Payment Types 
                                 <a id="add-payment-row" class="btn btn-xs btn-primary" href="#"><i class=" fa fa-plus fa fa-white"></i></a>
                            </legend>
                            @if($paymentdata->count())
                            @foreach ($paymentdata as $payment)
                            <div class="row payment-type">
                             <div class="col-md-11 col-xs-11">
                              <div class="form-group">
                               {!! Form::text('payment_type[]', $payment->stipt_payment_types, ['class' => 'form-control']) !!}
                              </div>
                             </div>
                             <div class="col-md-1 col-xs-1 p-l-0">
                                <a class="btn btn-xs btn-red remove-payment-row" href="#"><i class="fa fa-times fa fa-white"></i></a>
                             </div> 
                            </div>
                            @endforeach
                            @endif
                            <div id="payment-box-add"></div>
                        </fieldset>
                        <fieldset class="padding-15">
                            <legend>Direct all enquiries to</legend>
                            <div class="form-group">
                                <label class="strong">Contact Name</label>
                                <input type="text" name="query_contact_name" value="{{isset($salestoolsinvoice)?$salestoolsinvoice->query_contact_name:''}}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="strong">Contact Telephone Number</label>
                                <input type="text" name="query_contact_phone" value="{{isset($salestoolsinvoice)?$salestoolsinvoice->query_contact_phone:''}}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="strong">Contact Email Address</label>
                                <input type="text" name="query_contact_email" value="{{isset($salestoolsinvoice)?$salestoolsinvoice->query_contact_email:''}}" class="form-control" required>
                            </div>
                        </fieldset>
                    </div>
                </div>

            	<div class="row">
        			<div class="col-sm-12">
            			<div class="form-group text-right">
                			<button class="btn btn-primary btn-wide btn-add-more-form submitinvoice">
                                <i class="fa fa-edit"></i> Update Invoice
                            </button>
                        </div>
        			</div>
    			</div>
        {!! Form::close() !!}


@stop

@section('required-script-for-this-page')
    
    {!! Html::script('assets/js/jquery-ui.min.js?v='.time()) !!} 

    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}

    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}

    <!-- start: Moment Library -->
    <!-- {!! Html::script('vendor/moment/moment.min.js') !!} -->
    <!-- end: Moment Library -->

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    {!! Html::script('assets/js/sales-invoice.js?v='.time()) !!}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


@stop