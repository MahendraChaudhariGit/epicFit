<?php
$extraField=[];
$extraField=array(
'clientId'=>$clients->id
);
$startDueDate = isset($_COOKIE['dueSatrtDate']) && $_COOKIE['dueSatrtDate'] != 'null' ? $_COOKIE['dueSatrtDate'] : '';
$endDueDate   = isset($_COOKIE['dueEndDate']) && $_COOKIE['dueSatrtDate'] != 'null' ? $_COOKIE['dueEndDate'] : '';
?>

@include('includes.partials.delete_form',['extraFields'=>$extraField])
<input type="hidden" name="tax-rat" class="tax-rat-cls" value="{{isset($taxdata) && count($taxdata)?$taxdata->mtax_rate:0 }}">
<input type="hidden" name="tax-label" class="tax-label-cls" value="{{isset($taxdata) && count($taxdata)?$taxdata->mtax_label:'' }}">
<div class="row">
	<div class="col-xs-12 col-sm-6 col-md-6">
		<div class="page-header">
			<h1>Invoices list</h1>
			<div class="invoice-price-heading">
				Total amount: ${{ ($totalAmount)?$totalAmount:0 }}<br>
				Total paid: ${{ ($totalPaid)?$totalPaid:0 }}<br>
				Total outstanding: ${{ $totalAmount - $totalPaid }}</span>
			</div>
		</div>	
	</div>
	<div class="col-xs-12 col-sm-6 col-md-6">
       	<a class="btn btn-primary pull-right create-invoice" href="#"><i class="ti-plus"></i> Create Invoice</a> 
    </div> 
</div>
<div class="row">
    <!-- start: Datatable Header -->
    @include('includes.partials.datatable_header', ['extra' => '<div class="only-for-tab-1"><span>Show </span><select class="search-bar-select" id="invStatusFilt" data-cookie-name="invoice-list-status-filter"><option value="">Paid & Unpaid</option><option value="Paid">Paid</option><option value="Unpaid">Unpaid</option></select></div>', 'source' => 'client-profile-invoice'])
    <!-- end: Datatable Header -->
</div>
<br>
<div class="row"> 
	<div class="col-xs-12 col-sm-4">
		<div class="dueDate-filter"><p>Due date between</p><div class="input-group input-daterange"><input type="text" class="form-control dueStartDate" value="{{ $startDueDate ? date('D, d M Y',strtotime($startDueDate)) : '' }}"><div class="input-group-addon" style="background-color: #253746;
    border: 1px solid #253746;">to</div><input type="text" class="form-control dueEndDate" value="{{ $endDueDate ? date('D, d M Y',strtotime($endDueDate)) : '' }}"></div></div>
	</div>
	
</div>
<table class="table table-striped table-bordered table-hover m-t-10" id="benchmark-datatable123">
  <thead>
	<tr>
		<th class="center">Invoice #</th>
		<th>Location</th>
		<th>Status </th>
		<th>Appointment date & time</th>
		<th>Invoice date</th>
		<th>Due date</th>
		<th>Amount </th>
		<th>Action</th>
	</tr>
</thead>
  <tbody>
	@foreach($allInvoices as $key=>$invoices)
		<?php $clientName =  $invoices->client['firstname'].'-'. $invoices->client['lastname']; ?>
		<tr>
			<td class="center">
				{{ $invoices['inv_id'] ?? '' }}
			</td>
			<td>
				{{ $invoices->locationData($invoices->inv_area_id) }}

			</td>
			<td>
				<span class="label-wrapper">
				<?php if($invoices['inv_status'] == 'Paid'){ ?>
					<span class="label label-success">{{ $invoices['inv_status'] ?? ' ' }}</span>
					<?php } else { ?>
					<span class="label label-warning">{{ $invoices['inv_status'] ?? ' ' }}</span>
					<?php } ?>

				</span>
			 </td>
			<td>
				@if($invoices['appointment_date_time'])
					{{ dbDateToDateString($invoices['appointment_date_time'], 'dateString') }} {{ dbTimeToTimeString($invoices['appointment_date_time'], 'dateString') }}
				{{--@else--}}
					{{--{{ dbDateToDateString($invoices['inv_invoice_date'], 'dateString') }}--}}
				@endif
			</td>
			<td>
				{{ dbDateToDateString($invoices['inv_invoice_date'], 'dateString') }}
			</td>
			<td>
				{{ dbDateToDateString($invoices['inv_due_date'], 'dateString') }}
			</td>
			<td>${{ $invoices['inv_total'] ?? '' }}</td>
			<td class="center">
                        <div>
                        	<a href="{{ route('invoices.show', $invoices['inv_id']) }}" class="btn btn-xs btn-default tooltips" data-placement="top" data-original-title="View" ><i class="fa fa-share text-primary"></i></a>
                            

                            <a class="btn btn-xs btn-default tooltips delLink" href="{{ route('invoices.destroy', $invoices['inv_id']) }}" data-placement="top" data-original-title="Delete" data-entity="invoice">
                                <i class="fa fa-trash-o text-primary"></i>
                            </a>
                        </div>
                    </td>
		</tr>
		@endforeach
	</tbody>
</table>
<!-- start: Paging Links -->
@include('includes.partials.paging', ['entity' => $allInvoices])
<!-- end: Paging Links -->

<script>
	var cookieSlug = "client_invoice";

	$(document).ready(function() {
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
			// console.log('hii');
            // reloadPageWithTab('#invoices');
        });

        // $.cookie('dueSatrtDate', null);
        // $.cookie('dueEndDate', null);

		$('.dueEndDate').on('change', function(){
			$(this).closest('.dueDate-filter').find('.error-daterange').remove();
			var dueSatrtDate = dateStringToDbDate($('.dueStartDate').val());
			var dueEndDate = dateStringToDbDate($(this).val());
			if(dueSatrtDate && dueSatrtDate <= dueEndDate){
				$.cookie('dueSatrtDate', null);
        		$.cookie('dueEndDate', null);
				$.cookie('dueSatrtDate', dueSatrtDate);
				$.cookie('dueEndDate', dueEndDate);
				// console.log('hii');
				// reloadPageWithTab('#invoices');
			}
			else{
				$(this).closest('.dueDate-filter').append('<span class="error-daterange">Please select valid date range </span>');
			}
		});

		$('#datatableLengthDd').on('change', function() {
			$.cookie('clientInvoicePaginateLength', null);
        	$.cookie('clientInvoicePaginateLength', $(this).val());
			// console.log('hii');
        	// reloadPageWithTab('#invoices');
        });
 
	});

	$(document).ready(function(){
		$.fn.dataTable.moment('ddd, D MMM YYYY');
		// $('#benchmark-datatable123').dataTable({"searching": false, "paging": false, "info": false });   
	});
</script>  


