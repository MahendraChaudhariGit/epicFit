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
    Invoices list <br>
    <div class="invoice-price-heading col-sm-6">
        Total amount: ${{ $totalAmount }}<br>
        Total paid: ${{ $totalPaid }}<br>
        Total outstanding: ${{ $totalAmount - $totalPaid }}</span>
    </div>
    <div class="col-sm-6">
       <a class="btn btn-primary pull-right create-invoice" href="#"><i class="ti-plus"></i> Create Invoice</a> 
    </div> 
@stop

@section('content')
{!! displayAlert()!!}

<?php $extraField = [];
    $extraField = array('from'=>'index');
?>

<!-- Start: Delete form -->
@include('includes.partials.delete_form',['extraFields'=>$extraField])
<!-- End: Delete form -->

<input type="hidden" name="payment-terms" value="{{ isset($payterm)?$payterm:'' }}" >
<input type="hidden" name="tax-rat" class="tax-rat-cls" value="{{isset($taxdata) && count($taxdata)?$taxdata->mtax_rate:0 }}">
<input type="hidden" name="tax-label" class="tax-label-cls" value="{{isset($taxdata) && count($taxdata)?$taxdata->mtax_label:'' }}">
<input type="hidden" name="discount-data" class="discount-data-cls" value='<?php echo json_encode($discount); ?>'>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <!-- start: Datatable Header -->
            @include('includes.partials.datatable_header', ['extra' => '<div class="only-for-tab-1"><span>Show </span><select class="search-bar-select" id="invStatusFilt" data-cookie-name="invoice-list-status-filter"><option value="">Paid & Unpaid</option><option value="Paid">Paid</option><option value="Unpaid">Unpaid</option></select></div>'])
            <!-- end: Datatable Header -->
        </div>
        <br>
        <div class="row">
            <div class="col-md-4">
                <label>Due date</label>
                <div class="dueDate-filter only-for-tab-2"><div class="input-group input-daterange"><input type="text" class="form-control dueStartDate" value="" readonly="readonly" placeholder="From"><div class="input-group-addon">to</div><input type="text" class="form-control dueEndDate" value="" readonly="readonly" placeholder="To"></div></div> 
            </div>
        </div>
        <table class="table table-striped table-bordered table-hover m-t-10" id="view-invoice-datatable"> 
            <thead>
                <tr>
                    <th class="center">Invoice #</th>
                    <th>Client Name</th>
                    <th>Location</th>
                    <th>Status </th>
                    <th>Invoice date</th>
                    <th>Due date</th>
                    <th class="hidden-xs">Ref</th>
                    <th>Amount </th>
                   	<th class="center " data-orderable="false">Action</th>
                </tr>
            </thead>
            <tbody>
            @if(count($allInvoices))
            @foreach($allInvoices as $key=>$invoices)
                <?php $clientName = $invoices->clientWithTrashed ? $invoices->clientWithTrashed['firstname'].' '. $invoices->clientWithTrashed['lastname']:$invoices->inv_client_name; ?>
                <tr>
                    <td class="center">
                        <a href="{{ route('invoices.show', $invoices['inv_id']) }}">
                        {!! (int) $invoices['inv_invoice_no'] !!}
                        </a>
                    </td>
                    <td>
                        @if($invoices->clientWithTrashed['deleted_at'] == null )
                        <a href="{{	$invoices->clientWithTrashed ? route('clients.show', $invoices['inv_client_id']) : '#'}}">
                            {{ $clientName ?? '----' }} <br>
                        </a>
                        @else
                            {{ $clientName ?? '----' }} <br>
                        @endif
                    </td>
                    <td class="">
                        {{ $invoices->locationData($invoices->inv_area_id)  }}
                    </td>
                    <td>
                        <span class="label-wrapper">
                        <?php if($invoices['inv_status'] == 'Paid'){ ?>
							<span class="label label-success">{{ $invoices['inv_status'] ?? '' }}</span>
							<?php } else { ?>
							<span class="label label-warning">{{ $invoices['inv_status'] ?? '' }}</span>
							<?php } ?>

						</span>
                    </td>
                	<td>
                        {{ dbDateToDateString($invoices['inv_invoice_date'], 'dateString') }}
                    </td>
                   	<td>
                        {{ dbDateToDateString($invoices['inv_due_date'], 'dateString') }}
                    </td>
                    <td class="hidden-xs">
                        {{ $invoices['inv_ref'] ?? '' }}
                    </td>
                    <td>
                        ${{ $invoices['inv_total'] ?? '' }}
                    </td>

                    <td class="center">
                        <div>
                        	<a href="{{ route('invoices.show', $invoices['inv_id']) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a>
                            

                            <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('invoices.destroy', $invoices['inv_id']) }}" data-placement="top" data-original-title="Delete" data-entity="invoice">
                                <i class="fa fa-trash-o text-primary"></i>
                            </a>

                            <a class="btn btn-xs btn-default tooltips make-payment" href="#" data-placement="top" data-original-title="Payment" data-invoice-id="{{ $invoices['inv_id'] }}">
                                <i class="fa fa-dollar text-primary"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            @endif
        </table>

        <!-- start: Paging Links -->
        @include('includes.partials.paging', ['entity' => $allInvoices])
        <!-- end: Paging Links -->
    </div>
</div>
<!--Start: create invoice Modal  -->
@include('includes.partials.invoice_modal', ['alltax'=>$alltax,'paymenttype'=>$paymenttype,'userInfo'=>$userInfo,'termsOfSale'=>$salestoolsinvoice->sti_payment_terms,'country' => $countries,'invoices'=>null]) 
<!--Start: create invoice Modal  -->

<!-- Start: Payment modal -->
@include('includes.partials.payment_modal', ['paymenttype'=>$paymenttype,'userInfo'=>$userInfo])
<!-- End:  Payment Modal -->

@stop
@section('script')
{!! Html::script('assets/plugins/bootstrap3-typeahead.min.js?v='.time()) !!}    
{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/invoice.js?v='.time()) !!}

<script>
    var cookieSlug = "invoice";
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#view-invoice-datatable').dataTable({"searching": false, "paging": false, "info": false });

    $(document).ready(function(){
        $.cookie('dueSatrtDate', null);
        $.cookie('dueEndDate', null);
        $('.dueStartDate').datepicker({autoclose:true, dateFormat:"D, d M yy"});
        $('.dueEndDate').datepicker({autoclose:true, dateFormat:"D, d M yy"});

        var filterDd = $('select#invStatusFilt'),
            filterCookieName = filterDd.data('cookie-name'),
            length = $.cookie(filterCookieName);
      
        filterDd.val(length).selectpicker('refresh')
        filterDd.change(function(){
            $.cookie(filterCookieName, $(this).val());
            location.reload(true);
        })

        $.cookie('dueSatrtDate', null);
        $.cookie('dueEndDate', null);
        $('.dueEndDate').on('change', function(){
            $(this).closest('.dueDate-filter').find('.error-daterange').remove();
            var dueSatrtDate = dateStringToDbDate($('.dueStartDate').val());
            var dueEndDate = dateStringToDbDate($(this).val());
            if(dueSatrtDate && dueSatrtDate <= dueEndDate){
                $.cookie('dueSatrtDate', dueSatrtDate);
                $.cookie('dueEndDate', dueEndDate);
                location.reload(true);
            }
            else{
                $(this).closest('.dueDate-filter').append('<span class="error-daterange">Please select valid date range </span>');
            }
        }); 
    });
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI9fgvBgIW52M1jvW5rWQ9LOSdweGy8kg&libraries=places&callback=initAutocomplete"
    async defer></script>
@stop