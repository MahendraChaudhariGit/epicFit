@php
$clientName = null;
if(isset($clientDetails) && $clientDetails) {
    $clientName = isset($clientDetails->firstname) ? $clientDetails->firstname : '';
    $clientName .= ' ';
    $clientName .= isset($clientDetails->lastname) ? $clientDetails->lastname : '';
}else{
    $clientName = $invoices['client_name'];
}
@endphp
<div class="modal fade" id="invoiceModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                 <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body bg-white">
            	{!! Form::open(['url' => '', 'role' => 'form', 'id' =>'invoice-form']) !!}
                {!! Form::hidden('invoiceId') !!}
                <div class="errorMsg"></div>
				<div class ="row">
                    <div class="form-group col-md-6">
                       {!! Form::label('location-area', 'Location', ['class' => 'strong']) !!}
                       {!! Form::select("location-area", [], null, ['class' => 'form-control onchange-set-neutral', 'id'=>'locationDD']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        <label class="strong">Staff</label>
                        <select class="form-control staff-name onchange-set-neutral" name="staffId">
                        </select>
                    </div>
                </div>
                <div class ="row">
                    <div class="form-group col-md-6">
                        <div class="row">
                            <div class="col-md-3">
                                {!! Form::label('clientName', 'Invoice to', ['class' => 'strong']) !!}
                            </div>
                            <div class="col-md-6">
                                <div class="checkbox clip-check check-primary m-b-0 m-t-0">
                                    <input type="checkbox" name="client_offline" id="client-offline" value="1" class="no-clear" {{isset($invoices) && $invoices['client_id'] == 0?'checked':''}}>
                                    <label for="client-offline" class="m-r-0"><strong>Offline</strong></label>
                                </div>
                            </div>
                        </div>
                        
                        {!! Form::text('clientName', $clientName ? $clientName : '', ['class' => 'form-control clientList onchange-set-neutral','required']) !!}
                        {!! Form::hidden('clientId', isset($clientDetails) && $clientDetails  ? $clientDetails->id : '') !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('invoiceDate', 'Invoice date ', ['class' => 'strong']) !!}
                        {!! Form::text('invoiceDate', null, ['class' => 'form-control','readonly' =>'true']) !!}

                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('dueDate', 'Due date ', ['class' => 'strong']) !!}
                        {!! Form::text('dueDate', null, ['class' => 'form-control','readonly' =>'true']) !!}
                    </div>
                </div>
                <div class ="row">
                    <div class=" col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="strong">Term of Sale</label>
                                    {!! Form::select('termsOfSale', ['' => '--Select--','C.O.D' => 'Cash on Delivery','Immediately' => 'Immediately', '7' => 'Within 7 days', '14' => 'Within 14 days', '21' => 'Within 21 days', '30' => 'Within 30 days', '20' => '20th of the following month'], isset($termsOfSale)?$termsOfSale:null, ['class' => 'form-control onchange-set-neutral','required' => 'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="strong">Delevery Details</label>
                                    <select class="form-control onchange-set-neutral" name="delivery_type" id="deliveryType" tabindex="-98">
                                        <option value="">-- Select --</option>
                                        <option value="collected">Collected</option>
                                        <option value="delivery">Delivery</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6 hideShippFields">
                        <label class="strong">Contact Person</label>
                        <input type="text" class="form-control" name="contact_person">
                    </div>
                </div>
                <div class ="row hideShippFields">
                    <div class="form-group col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Shipping Adress </h5>
                                <div class="shipping_checkbox">
                                    <input type="checkbox" name="use_existing" id="UseExisting">
                                    <label for="UseExisting">Use Existing</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="strong">Address Line 1</label>
                        <input type="text" class="form-control" name="address_line_one" id="autocomplete2" onFocus='geolocate()' autocomplete= 'off'>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="strong">Address Line 2</label>
                        <input type="text" class="form-control" name="address_line_two">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="strong">City</label>
                        <input type="text" class="form-control" name="city">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="strong">Country</label>
                        {!! Form::select('country', $country, null, ['class' => 'form-control countries onchange-set-neutral', 'required' => 'required','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        <label class="strong">State</label>
                        {!! Form::select('state',['' => '-- Select --'],null, ['class' => 'form-control states onchange-set-neutral', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group col-md-6">
                        <label class="strong">Postal Code</label>
                        <input type="text" class="form-control" name="postal_code">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="strong">Shipped Via</label>
                        <input type="text" class="form-control" name="shipped_via">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="strong">Our Order Number</label>
                        <input type="text" class="form-control" name="order_number">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="strong">Clients Order Number</label>
                        <input type="text" class="form-control" name="cust_order_number">
                    </div>
                </div>
                
                <div class="invoice-responsive">
                    {{-- <div class="row" style="width:860px">
                        <div class="inv-col-3 first-inv-row">
                            <strong>Item / description </strong>
                        </div>
                        <div class="inv-col-3 custom-inv-row">
                            <strong>Staff</strong>
                        </div>
                        <div class="inv-col-1 custom-inv-row">
                            <strong>Tax</strong>
                        </div>
                        <div class="inv-col-1 custom-inv-row">
                            <strong>Quantity</strong>
                        </div>
                        <div class="inv-col-2 custom-inv-row">
                            <strong>Unit price</strong>
                        </div>
                        <div class="inv-col-2 custom-inv-row">
                            <strong>Total</strong>
                        </div>
                        <div class="inv-col-1 custom-inv-row">
                            <strong>Discount</strong>
                        </div>
                       <div class="inv-col-1 custom-inv-row">
                       </div>
     				</div> --}}
                    <div class="" id="appendRow">
                        <div class="row m-t-5 clone-row hidden" id="">
                            <div class="col-md-6">
                                <div class="inv-group">
                                    {!! Form::label('productName', 'Item / description ', ['class' => 'strong']) !!}
                                    {!! Form::textarea('productName', null, ['class' => 'form-control product-name product-populate leave-cls']) !!}
                                    {!! Form::hidden('productId', null, ['class'=>'product-id leave-cls']) !!}
                                    {!! Form::hidden('productType', null, ['class'=>'product-type leave-cls']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="inv-group">
                                            {!! Form::label('quantity', 'Quantity ', ['class' => 'strong']) !!}
                                            {!! Form::text('quantity', null, ['class' => 'form-control numericField quantity leave-cls']) !!}
                                            {!! Form::hidden('quantityStock', null, ['class'=>'quantity-stock leave-cls']) !!}
                                         </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="inv-group">
                                            {!! Form::label('unitPrice', 'Unit price', ['class' => 'strong']) !!}
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <span class="fa fa-dollar"></span>
                                                </span>
                                                {!! Form::text('unitPrice', null, ['class' => 'form-control price-field leave-cls']) !!}
                                                {!! Form::hidden('preUnitPrice', null, ['class' => 'leave-cls pre-unit-price']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="inv-group">
                                            {!! Form::label('taxType', 'Tax', ['class' => 'strong']) !!}
                                            <div class="tax-name-group">
                                                <select name="taxName" class="form-control inv-set-neutral tax-name leave-cls ">
                                                    <option value="" data-tax="">Select</option>
                                                    @if(isset($alltax) && count($alltax))
                                                        @foreach($alltax as $value)
                                                            <option value="{{$value['mtax_label']}}" data-tax="{{$value['mtax_rate']}}">{{$value['mtax_label']}}</option>
                                                        @endforeach
                                                    @endif
                                                    <option value="N/A" data-tax="0">N/A</option>
                                                </select>
                                            </div>
                                            {!! Form::hidden('taxType', null, ['class'=>'tax-type leave-cls']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="inv-group">
                                            {!! Form::label('totalPrice', 'Total', ['class' => 'strong']) !!}
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <span class="fa fa-dollar"></span>
                                                </span>
                                                {!! Form::text('totalPrice', null, ['class' => 'form-control leave-cls', 'readonly' => 'true']) !!}
                                                {!! Form::hidden('hiddenTotalPrice', null, ['class'=>'hidden-totalPrice leave-cls']) !!}
                                                 {!! Form::hidden('hiddenGstPrice', null, ['class'=>'hidden-gstPrice leave-cls']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="inv-group inv-col-1 custom-inv-row">
            				        <a class="applyDiscount" name="discountRow" data-toggle="popover" >
            						  <i class="m-t-10 fa fa-scissors fa-lg fa-fw apply-discount"></i>
                                    </a>
                                </div>
                                <div class="inv-group inv-col-1 custom-inv-row">
                         			<a class="btn btn-primary btn-xs m-t-5 removeProduct" href="#">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                </div>
                            </div>
        				</div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">                        
                            <a class="btn btn-xs btn-success add-custom-item tip-init" href="#">
                                <i class="glyphicon glyphicon-plus"></i>Add Custom
                            </a>
                            <a class="btn btn-xs btn-success add-product tip-init add-new-item" href="#">
                                <i class="glyphicon glyphicon-plus"></i>Add product
                            </a>
                            <a class="btn btn-xs btn-success add-class tip-init add-class-item" href="#">
                                <i class="glyphicon glyphicon-plus"></i>Add classes
                            </a>
                            <a class="btn btn-xs btn-success add-service tip-init add-service-item" href="#">
                                <i class="glyphicon glyphicon-plus"></i>Add services
                            </a>
                        </div>
                    </div>
                </div>
                <hr />

                <div class="row price-section">
                    <div class="col-md-6">
                        
                    </div>
                    <div class="col-md-6 price">
                        <div class="row custom-hr">
                            <div class="col-md-6">
                                {!! Form::label('Includes tax of:', 'Includes tax of:', ['class' => 'strong']) !!}
                            </div>
                            <div class="col-md-6">
                                $<span id="tax-value">0</span>
                                {!! Form::hidden('totalTaxAmount') !!}
                            </div>
                        </div>
                        <div class="row custom-hr">    
                            <div class="col-md-6">
                                {!! Form::label('Total:', 'Total:', ['class' => 'strong']) !!}                     
                            </div>
                            <div class="col-md-6">
                                {!! Form::hidden('totalAmount') !!} 
                                $<span id="total-value">0</span>   
                            </div>
                        </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href ="#" class ="btn btn-xs btn-success notes-link <?php echo $hide1; ?>">Add notes and/or refrences.</a>
                        <a href="#" class="btn btn-xs btn-success make-payment-btn">Make payment.</a>
                        {{-- <a class="btn btn-xs btn-success add-custom-item tip-init" href="#">
                            <i class="glyphicon glyphicon-plus"></i>Add Custom
                        </a>
                        <a class="btn btn-xs btn-success add-product tip-init add-new-item" href="#">
                            <i class="glyphicon glyphicon-plus"></i>Add product
                         </a> --}}
                    </div>
                </div>
                <div class="row">
                    <div class ="col-md-6">
                        <?php 
                            if(isset($invoice) && $invoice['note'] != ''){
                                $hide1 = 'hidden';
                                $hide2 = '';
                            } 
                            else{
                                $hide1 = '';
                                $hide2 = 'hidden';
                            }
                            
                        ?>
                        
						<div class ="row notes <?php echo $hide2; ?>">
                            <div class="form-group col-md-6">
                                {!! Form::label('notes', 'Notes', ['class' => 'strong']) !!}
                                {!! Form::text('notes', isset($invoice['note'])?$invoice['note']:'', ['class' => 'form-control']) !!}
                               
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('reference', 'Reference', ['class' => 'strong']) !!}
                                {!! Form::text('reference', isset($invoice['ref'])?$invoice['ref']:'', ['class' => 'form-control']) !!}
                           </div>
                        </div>
                    </div>
					<div class="checkbox clip-check check-primary col-md-6 hidden" id="appointment-comp-checkbox">
                        <input type="checkbox" name="appointment-status" value="1" id="isComplete" <?php //if(isset($invoice['status']) && $invoice['status'] != '') echo 'checked'; ?> >
                        <label for="isComplete" class="no-error-label">
                            <strong>Mark appointment as completed</strong>
                            <a class="font-14" data-content="This will mark past service booking as attended." data-placement="top" data-toggle="popover" data-trigger="hover" data-original-title="" title="">
                                <i class="fa fa-info-circle" href="#"></i>
                            </a>
                        </label>
					</div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        
                        <div class="inv-payment-fields hidden">
                            <div class="form-group">
                                {!! Form::label('paymAmount', 'Enter amount *', ['class' => 'strong']) !!}
                                {!! Form::text('paymAmount',null , ['class' => 'form-control price-field', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('paymDate', 'Confirm payment date *', ['class' => 'strong']) !!}
                               {!! Form::text('paymentDate', null, ['class' => 'form-control','readonly' =>'true']) !!}  
                            </div> 
                            <div class="form-group">
                                {!! Form::label(null, 'Choose payment type *', ['class' => 'strong']) !!}
                                <select name="payment_type" id="payment-type" class="form-control" required="required">
                                    @if(count($paymenttype))
                                        @foreach($paymenttype as $value)
                                            <option value="{{ $value->stipt_payment_types }}">{{ $value->stipt_payment_types }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="hidden" style="color:#5cb85c;">EPIC Credit:<strong id="client-epic-credit"></strong></div>
                            </div>
                            <div class="form-group " >
                                {!! Form::label('paymProcess', 'Payment processed by *', ['class' => 'strong']) !!}
                                <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" title="Choose the staff member or administrator that processed the payment and this will be recorded on the Transaction Summary report. The dropdown will automatically default to the logged-in staff member or administrator."><i class="fa fa-question-circle"></i></span>
                                {!! Form::select('paymProcess', isset($userInfo)?$userInfo:[], Auth::user()->id, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer clearfix">
                <button type="button" class="btn btn-default close-event-btn" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="invoice-submit-btn">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- End: Invoice modal