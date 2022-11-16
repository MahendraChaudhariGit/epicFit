<!--Start: Payment Add Modal -->
<div class="modal fade" id="paymentAddModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close m-t--10" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Apply payment to Invoice </h4>
            </div>
            <div class="modal-body bg-white">
                <div class="alert alert-danger hidden" id="overflow-msg">
                    
                </div>
                <div class="alert alert-info">
                    <h4>Invoice #<span id="inv-id"></span></h4>
                    The invoice total is </strong> <span id="inv-total-text"></span> </strong>   and the amount outstanding is <strong><span id="inv-due-text"></span></strong> 
                </div>
            
                {!! Form::open(['url' => '', 'role' => 'form','id' => 'make-payment']) !!}
                {!! Form::hidden('invoiceId') !!}
                {!! Form::hidden('invoiceAmount') !!}
                {!! Form::hidden('totalinvoiceAmount') !!}
                {!! Form::hidden('paymentMethod') !!}
                 <div class="form-group">
                        {!! Form::label(null, 'Choose payment type', ['class' => 'strong']) !!}
                        <div class="btn-toolbar">
                            <div class="btn-group" role="group">
                                @if(count($paymenttype))
                                    @foreach($paymenttype as $value)
                                        @if($value->stipt_payment_types == 'Direct Debit')
                                            <a class="btn btn-default active payment-method pay-btn default" href="#nonaccount-fields">
                                                {{ $value->stipt_payment_types }}
                                            </a>
                                        @else
                                            <a class="btn btn-default payment-method pay-btn" href="#nonaccount-fields">
                                                {{ $value->stipt_payment_types }}
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="payment-fields" id="nonaccount-fields">
                        <div class="form-group pay-amount">
                            {!! Form::label('paymAmount', 'Enter amount *', ['class' => 'strong']) !!}
                            {!! Form::text('paymAmount',null , ['class' => 'form-control price-field', 'required']) !!}
                            <!-- </div> -->
                        </div>
                        <div class="form-group">
                            {!! Form::label('paymentDate', 'Confirm payment date *', ['class' => 'strong']) !!}
                            {!! Form::text('paymentDate', null, ['class' => 'form-control','readonly' =>'true']) !!}
                        </div>
                        <div class="form-group " >
                            {!! Form::label('paymProcess', 'Payment processed by *', ['class' => 'strong']) !!}
                            <span class="epic-tooltip" rel="tooltip" data-toggle="tooltip" title="Choose the staff member or administrator that processed the payment and this will be recorded on the Transaction Summary report. The dropdown will automatically default to the logged-in staff member or administrator."><i class="fa fa-question-circle"></i></span>
                            {!! Form::select('paymProcess', array_merge([''=>'-- Select --'],$userInfo), [], ['class' => 'form-control onchange-set-neutral', 'required']) !!}
                        </div>
                        <div class="form-group pay-ref">
                            {!! Form::label('paymReference', 'Reference(optional)', ['class' => 'strong']) !!}
                            {!! Form::text('paymReference', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                        
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-wide" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-wide " id="payment-submit">Save</button>
            </div>
        </div>
    </div>
</div>
<!--End: Payment Add Modal -->