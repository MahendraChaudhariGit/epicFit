@extends('blank')

@section('required-styles')
{!! HTML::style('assets/css/invoice.css?v='.time()) !!} 
<style>
    .pac-container {
        z-index:9999;
    }
</style> 
@stop()

@section('page-title')
{{ $invoices['location_name'] ?? '' }}
<span class="badge badge-inverse">{{ $invoices['area_name'] ?? '' }}</span>
@stop

@section('content')
{!! displayAlert()!!}

<?php $extraField = [];
$extraField = array('from'=>'view');
?>

<!-- Start: Delete form -->
@include('includes.partials.delete_form',['extraFields'=>$extraField])
<!-- End: Delete form -->

<div id="edit-data">
    <input type="hidden" name="payment-terms" value="{{isset($salestoolsinvoice)?$salestoolsinvoice->sti_payment_terms:''}}" >
    <input type="hidden" name="tax-rat" class="tax-rat-cls" value="{{isset($taxdata) && count($taxdata)?$taxdata->mtax_rate:0 }}">
    <input type="hidden" name="tax-label" class="tax-label-cls" value="{{isset($taxdata) && count($taxdata)?$taxdata->mtax_label:'' }}">
    <input type="hidden" name="discount-data" class="discount-data-cls" value='<?php echo json_encode($discount); ?>'>

    <input type="hidden" name="locationId"  value="{{ isset($invoices)?$invoices['loc_id']:'' }}">
    <input type="hidden" name="clientId"  value="{{ isset($invoices)?$invoices['client_id']:'' }}">
    <input type="hidden" name="clientName" value="{{ isset($invoices)?$invoices['client_name']:'' }}">
    <input type="hidden" name="invoice_date" value="{{ isset($invoices)?$invoices['invoice_date']:'' }}">
    <input type="hidden" name="due_date" value="{{ isset($invoices)?$invoices['due_date']:'' }}">
    <input type="hidden" name="invoice_id" value="{{ isset($invoices)?$invoices['invoice_id']:'' }}">

    <?php $invoiceVal = str_replace("'","",json_encode($invoiceItemsDetails)); ?>
    <input type ="hidden" name="invoiceData" value ='{!! $invoiceVal !!}' />
    <input type ="hidden" name="invoiceAllData" value ='{{json_encode($invoices)}}' />
</div>
<!-- Start: Show INVOICE details -->
<div class="row">
    <div class="col-md-12">
        <div class="invoice">
            <div class="row">
                <div class="col-md-12">
                    <div class="new_invoice_design">
                        <div class="mainleft">
                            <div class="logo">
                                <img src="{{dpSrc($businessData['logo'])}}">
                            </div>
                            <div class="top_data">
                                <div class="left_data">
                                    <h3>{{strtoupper($salestoolsinvoice->sti_title)}}</h3>
                                    <p>{{$invoices['client_name']}} <br>
                                        @if($invoices['addr1'] != '' && $invoices['addr1'] != null)
                                        {{$invoices['addr1']}} <br>
                                        @endif
                                        @if($invoices['addr2'] != '' && $invoices['addr2'] != null)
                                        {{$invoices['addr2']}} <br>
                                        @endif
                                        @if($invoices['city'] != '' && $invoices['city'] != null)
                                        {{$invoices['city']}} <br>
                                        @endif
                                        @if($invoices['state'] != '' && $invoices['state'] != null)
                                        {{$invoices['state']}} <br>
                                        @endif
                                        @if($invoices['country'] != '' && $invoices['country'] != null)
                                        {{$invoices['country']}} <br>
                                        @endif
                                        @if($invoices['postalCode'] != '' && $invoices['postalCode'] != null)
                                        {{$invoices['postalCode']}}
                                        @endif
                                    </p>
                                    <p>
                                        <strong>Note:</strong><br>
                                        @if($invoices['note'] != '' && $invoices['note'] != null)
                                        {{$invoices['note']}}
                                        @endif
                                    </p>
                                </div>
                                <div class="right_data">
                                    <p>
                                        <strong class="lefthd">Tax Invoice Number</strong> 
                                        <span class="righttext">#{{$invoices['invoice_no']}}</span>
                                    </p>
                                    <p>
                                        <strong class="lefthd">Invoice Date</strong>
                                        <span class="righttext">{{dbDateToDateString($invoices['invoice_date'])}}</span>
                                    </p>
                                    <p>
                                        <strong class="lefthd">Payment Due Date</strong>
                                        <span class="righttext">{{dbDateToDateString($invoices['due_date'])}}</span>
                                    </p>
                                    <p>
                                        <strong class="lefthd">Our Order Number</strong>
                                        <span class="righttext">{{$invoices['order_number']}}</span>
                                    </p>                                   
                                    <p>
                                        <strong class="lefthd">Your Order Number</strong>
                                        <span class="righttext">{{$invoices['cust_order_number']}}</span>
                                    </p>
                                    <p>
                                        <strong class="lefthd">Term</strong>
                                        <span class="righttext">{{termOfSale($invoices['termsOfSale'])}}</span>
                                    </p>
                                        
                                    <p><strong class="lefthd">Sale Rep</strong>
                                        <span class="righttext">
                                            {{$invoices['staffName']}}
                                        </span>
                                    </p>
                                    @if($invoices['delivery_type'] == 'delivery')
                                    <p>
                                        <strong class="lefthd">Shipped Via</strong>
                                        <span class="righttext">{{$invoices['shipped_via']}}</span>
                                    </p>
                                    @endif
                                    <p>
                                        <strong class="lefthd">Delivery Details</strong>
                                        <span class="righttext">{{ucfirst($invoices['delivery_type'])}}</span>
                                    </p>
                                    @if($invoices['delivery_type'] == 'delivery')
                                    <p>
                                        <strong class="lefthd">Contact</strong>
                                        <span class="righttext">{{$invoices['contact_person']}}</span>
                                    </p>
                                    @endif               
                                </div>
                            </div>
                            <div class="order_details">
                                <table  cellspacing="0" cellpadding="0">
                                    <tr>
                                        <th class="border_r">QTY</th>
                                        <th class="border_r">Description</th>
                                        <th class="border_r">Unit Price</th>
                                        <th>Amount</th>
                                    </tr>
                                    @foreach($invoiceItemsDetails as $key => $invoiceItem)
                                    <tr class="productlist">
                                        <td class=" border_tb border_r">{{$invoiceItem['quantity']}}</td>
                                        <td class=" border_tb border_r">{{$invoiceItem['desc']}}</td>
                                        <td class=" border_tb border_r">{{isset($invoiceItem['unit_price']) && $invoiceItem['unit_price'] < 0 ? '-$'.number_format(abs($invoiceItem['unit_price']), 2):'$'.number_format($invoiceItem['unit_price'], 2)}}</td>
                                        <td class=" border_tb">{{isset($invoiceItem['unit_price']) && $invoiceItem['unit_price'] < 0 ? '-$'.(number_format(abs($invoiceItem['unit_price']), 2))*$invoiceItem['quantity']:'$'.(number_format($invoiceItem['unit_price'], 2)*$invoiceItem['quantity'])}}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="productlist">
                                        <td class=" border_tb border_r">&nbsp;</td>
                                        <td class=" border_tb border_r">&nbsp;</td>
                                        <td class=" border_tb border_r">&nbsp;</td>
                                        <td class=" border_tb">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="border_b border_r"></td>
                                        <td class="border_b border_r"></td>
                                        <td class="border_b border_r" style="text-transform: uppercase;">Subtotal</td>
                                        <td class="border_b ">${{$invoices['total'] - $invoices['tax-amount']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="border_b border_r"></td>
                                        <td class="border_b border_r" style="text-transform: uppercase;">{{$taxdata['mtax_label']}}: {{$taxdata['mtax_rate']}}%</td>
                                        <td class="border_b border_r" style="text-transform: uppercase;">Tax</td>
                                        <td class="border_b ">${{$invoices['tax-amount']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="border_b border_r"></td>
                                        <td class="border_b border_r"></td>
                                        <td class="border_b border_r" style="text-transform: uppercase;">Additional</td>
                                        <td class="border_b ">$0</td>
                                    </tr>
                                    <tr>
                                        <td class="border_b border_r"></td>
                                        <td class="border_b border_r"></td>
                                        <td class="border_b border_r" style="text-transform: uppercase;">Total</td>
                                        <td class="border_b ">${{$invoices['total']}}</td>
                                    </tr>
                                    <tr>
                                        <td class="border_b border_r"></td>
                                        <td class="border_b border_r"></td>
                                        <td class="border_b border_r" style="text-transform: uppercase;">Amount Paid</td>
                                        <td class="border_b ">${{ $invoices['paid_amount'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="border_b border_r border_l" style="text-align: right;padding-right: 30px;text-transform: uppercase;">
                                            {{$salestoolsinvoice['sti_payment_notes']}}
                                        </td>
                                        <td class="">
                                            <strong>${{number_format($invoices['total'] - $invoices['paid_amount'], 2)}}</strong><br>
                                            <span style="width: 100%;text-align: center;">Pay This Amount</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="footer_detail">
                                <div class="leftside">
                                    <h3>Direct All Enquiries To:</h3>
                                    @if($salestoolsinvoice->query_contact_name != '' && $salestoolsinvoice->query_contact_name != null)
                                    <p>{{$salestoolsinvoice->query_contact_name}}</p>
                                    @endif
                                    @if($salestoolsinvoice->query_contact_phone != '' && $salestoolsinvoice->query_contact_phone != null)
                                    <p>{{$salestoolsinvoice->query_contact_phone}}</p>
                                    @endif
                                    @if($salestoolsinvoice->query_contact_email != '' && $salestoolsinvoice->query_contact_email != null)
                                    <p>{{$salestoolsinvoice->query_contact_email}}</p>
                                    @endif
                                </div>
                                <div class="rightside">
                                    <h3>Make All Payment To:</h3>
                                    @if($salestoolsinvoice->payment_company_name != '' && $salestoolsinvoice->payment_company_name != null)
                                    <p>{{$salestoolsinvoice->payment_company_name}}</p>
                                    @endif
                                    @if($salestoolsinvoice->payment_bank != '' && $salestoolsinvoice->payment_bank != null)
                                    <p>{{$salestoolsinvoice->payment_bank}}</p>
                                    @endif
                                    @if($salestoolsinvoice->bank_account_number != '' && $salestoolsinvoice->bank_account_number != null)
                                    <p>{{$salestoolsinvoice->bank_account_number}}</p>
                                    @endif
                                    @if($salestoolsinvoice->payment_country != '' && $salestoolsinvoice->payment_country != null)
                                    <p>{{Country::getCountryName($salestoolsinvoice->payment_country)}}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="bottom_footer">
                                <p>M: {{$businessData['phone']}} &nbsp; E: {{$businessData['email']}}</p>
                            <p>{{$businessData['trading_name']}} &nbsp; GST number: {{$salestoolsinvoice->buss_gst}}</p>
                            </div>
                        </div>
                        <div class="mainright">
                            <div class="verticletext">
                                <img src="{{asset('assets/images/righttext.jpg')}}">
                            </div>
                            <div class="location">
                                <p>
                                    <strong>{{$businessData['trading_name']}}</strong><br>
                                    @if($businessData['address_line_one'] != '' && $businessData['address_line_one'] != null)
                                    {{$businessData['address_line_one']}} <br>
                                    @endif
                                    @if($businessData['address_line_two'] != '' && $businessData['address_line_two'] != null)
                                    {{$businessData['address_line_two']}} <br>
                                    @endif
                                    @if($businessData['city'] != '' && $businessData['city'] != null)
                                    {{$businessData['city']}} <br>
                                    @endif
                                    @if($businessData['state'] != '' && $businessData['state'] != null)
                                    {{Country::getStateName($businessData['country'], $businessData['state'])}} <br>
                                    @endif
                                    @if($businessData['postal_code'] != '' && $businessData['postal_code'] != null)
                                    {{$businessData['postal_code']}} <br>
                                    @endif
                                    @if($businessData['country'] != '' && $businessData['country'] != null)
                                    {{Country::getCountryName($businessData['country'])}}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<div class="row m-t-20">
    <div class="col-sm-5">
        <h4>Payments:</h4>
        <ul class="list-group">

            @foreach($paymentDetails as $paymentInfo)
            <li class="list-group-item"><?php echo date('d M Y',strtotime($paymentInfo->pay_confirm_date));?> | ${{ $paymentInfo->pay_amount ?? '' }} |  {{ $paymentInfo->pay_type ?? '' }} | Ref: {{ $paymentInfo->pay_ref ?? '' }} | <a href="#" class="delete-payment" data-amount = {{ $paymentInfo->pay_amount ?? '' }} data-id = {{ $paymentInfo->pay_id ?? '' }} data-invoice = {{ $paymentInfo->pay_invoice_id ?? '' }}>Delete</a></li>
            @endforeach 
        </ul>
    </div>

    <div class="col-sm-4 col-sm-offset-3 pull-right">
        @if(count($emailLogs))
        <h4>Emails sent:</h4>
        <ul class="list-group">
            @foreach($emailLogs as $logs)
            <li class="list-group-item">Invoice copy sent on {!! $logs->emailSendDate !!} at {!! $logs->emailSendTime !!}  to  {{ $logs->iel_to_mail ?? '' }}</li>
            @endforeach 
        </ul>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-8">
        <a href="#" class="btn btn-primary edit-invoice invoice-xs-btn">
            <i class="fa fa-edit"></i>
            Edit
        </a>
        <?php if($invoices['payment_status'] != 'Paid') { ?>
        <a href="#" class="btn btn-success m-l-10 make-payment invoice-xs-btn" data-invoice-id="{{ isset($invoices)?$invoices['invoice_id']:'' }}">
            <i class="fa fa-check-square-o"></i>
            Apply Manual Payment
        </a>
        <?php } ?>
        <a href="#" class="btn btn-o btn-default invoice-xs-btn m-l-10" data-target="#emailInvModal" data-toggle="modal">
            <i class="fa fa-envelope"></i>
            Email Invoice 
        </a>
    <a href="{{route('invoice.downloadInvoice',['id'=>$invoices['invoice_id'],'v' => time()])}}" class="btn btn-o btn-default invoice-xs-btn m-l-10">
            <i class="fa fa-download"></i>
            Download Invoice 
        </a>
    </div>
    <div class="col-sm-6 col-md-4">
        <div class="text-right">
         <?php $url = getPrevousUrl(); 
         if ($url != '') {  ?>
         <a href="<?php echo $url; ?>" class="btn btn-primary invoice-xs-btn m-r-10"><i class="fa fa-arrow-left"></i>Back to invoice
         </a>
         <?php } else { ?>
         <a href="{{ route('invoices.view') }}" class="btn btn-primary invoice-xs-btn m-r-10"><i class="fa fa-arrow-left"></i> 
            Back to invoice list
        </a>
        <?php } ?>

        <a class="btn btn-danger invoice-xs-btn invoice-xs-btn-new-invoice tooltips delLink" href="{{ route('invoices.destroy', $invoices['invoice_id']) }}" data-placement="top" data-original-title="Delete" data-entity="invoice">
            <i class="fa fa-trash-o"></i>
            Delete
        </a>
    </div>
</div>
</div>
</div>
</div>
</div>
<!-- End: Show INVOICE details -->

<!--Start: Email Invoice Modal -->
<div class="modal fade" id="emailInvModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close invoice-email-modal-header" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-white">
                {!! Form::open(['url' => '', 'role' => 'form']) !!}
                <fieldset class="padding-15">

                    <legend>
                        Email Invoice &nbsp;&nbsp;&nbsp;&nbsp;
                    </legend>
                    <div class="form-group">
                        {!! Form::label('emailInvEmail', 'Email address to send invoice to *', ['class' => 'strong']) !!}
                        {!! Form::email('emailInvEmail',isset($invoices['email'])?$invoices['email']:null, ['class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('emailInvMsg', 'Enter a message', ['class' => 'strong']) !!}
                        {!! Form::textarea('emailInvMsg', null, ['class' => 'form-control textarea']) !!}
                    </div>
                </fieldset>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-wide" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-wide submit">
                    <i class="fa fa-envelope"></i>
                    Send
                </button>
                <input type ="hidden" id ="os-amount" value ="{{$invoices['total']}}"/>
                <input type ="hidden" id ="due-amount" value ="{{number_format($invoices['total'] - $invoices['paid_amount'], 2)}}"/>
                <input type ="hidden" id ="invoice-id" value =""/>
            </div>
        </div>
    </div>
</div>
<!--End: Email Invoice Modal -->

<!-- Start: invoice client appointmnet modal -->
@include('includes.partials.invoice_client_appointment_modal')
<!-- End: invoice client appointmnet modal -->

<!-- Start: Edit Invoice modal -->
@include('includes.partials.invoice_modal', ['alltax'=>$alltax,'invoice'=>$invoices,'country'=>$countries])
<!-- End:  Edit Invoice Modal -->

<!-- Start: Payment modal -->
@include('includes.partials.payment_modal', ['paymenttype'=>$paymenttype,'userInfo'=>$userInfo])
<!-- End:  Payment Modal -->

@stop

@section('script')
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js?v='.time()) !!}   
{!! Html::script('vendor/tooltipster-master/jquery.tooltipster.min.js?v='.time()) !!}
{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/invoice.js?v='.time()) !!}
<script>
   
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg&libraries=places&callback=initAutocomplete"
    async defer></script>
@stop