@extends('Result.masters.app')

@section('page-title')
    Invoices list
    <div class="invoice-price-heading">
        Total amount: ${{ ($totalAmount)?$totalAmount:0 }}<!-- <span id="showTotalAmount"></span> --><br>
        Total paid: ${{ ($totalPaid)?$totalPaid:0 }}<!-- <span id="showTotalPaid"></span> --><br>
        Total outstanding: ${{ $totalAmount - $totalPaid }}<!-- <span id="showTotalOutstanding"> --></span>
    </div> 
@stop
@section('before-styles-end')
    {!! HTML::style('result/css/invoice.css?v='.time()) !!} 
    {!! HTML::style('result/plugins/select2/select2.css') !!} 
    {!! Html::style('result/plugins/DataTables/media/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! HTML::style('result/css/custom.css?v='.time()) !!}  
@stop()
@section('content')
<!-- <a class="btn btn-primary pull-right" href="#" data-toggle="modal" data-target="#invoiceModal" id ="create-invoice"><i class="ti-plus"></i> Create Invoice</a> -->
{!! displayAlert()!!}
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="row">
                <div class="col-md-12"> 
                    @include('includes.partials.datatable_header', ['extra' => '<div><label>Show <select class="mw-120" id="invStatusFilt" data-cookie-name="invoice-list-status-filter"><option value="">Paid & Unpaid</option><option value="Paid">Paid</option><option value="Unpaid">Unpaid</option></select></label></div><div class="dueDate-filter"><p>Due date between</p><div class="input-group input-daterange"><input type="text" class="form-control dueStartDate" value=""><div class="input-group-addon">to</div><input type="text" class="form-control dueEndDate" value=""></div></div>'])
                </div>
            </div>
            <div class="panel-body table-responsive">
                <table class="table table-striped table-bordered table-hover m-t-10" id="client-datatable">
                    <thead>
                        <tr>
                            <th class="center">Invoice #</th>
                            <th>Location</th>
                            <th>Status </th>
                            <th>Appointment date & time</th>
                            <th>Invoice date</th>
                            <th>Due date</th>
                            <th>Amount </th>
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
                                {{ $invoices->loc->location_training_area ?? '' }}

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
                                    {{ dbDateToDateString($invoices['appointment_date_time'], 'dateString') }}
                                    {{ dbTimeToTimeString($invoices['appointment_date_time'], 'dateString') }}
                                {{--@else--}}
                                    {{--{{ dbDateToDateString($invoices['inv_invoice_date'], 'dateString') }}--}}
                                @endif
                            </td>
                        	<td>
                                <?php print_r(date('D, d M Y',strtotime($invoices['inv_invoice_date']))); ?>
                            </td>
                           	<td>
                                <?php print_r(date('D, d M Y',strtotime($invoices['inv_due_date']))); ?>
                            </td>
                            <td>${{ $invoices['inv_total'] ?? '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- start: Paging Links -->
                @include('includes.partials.paging', ['entity' => $allInvoices])
                <!-- end: Paging Links -->
            </div>
        </div>
    </div>
</div>

@stop
@section('required-script')
    {!! Html::script('result/js/jquery-ui.min.js') !!}
    {!! Html::script('result/plugins/moment/moment.min.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/dataTableDateSort.js') !!}
    {!! Html::script('result/plugins/DataTables/media/js/dataTables.bootstrap.min.js') !!}
    {!! Html::script('result/plugins/bootstrap3-typeahead/js/bootstrap3-typeahead.min.js') !!}
    {!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    {!! HTML::script('result/plugins/select2/select2.js') !!} 
    {!! HTML::script('result/js/invoice.js?v='.time()) !!} 
    {!! HTML::script('result/js/helper.js?v='.time()) !!} 
    

    <script>
        $(document).ready(function(){
            $.fn.dataTable.moment('ddd, D MMM YYYY');
            // $('#client-datatable').dataTable({"searching": false, "paging": false, "info": false });
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
            })

            if($.cookie('invoice_length'))
                $('select#datatableLengthDd').val($.cookie('invoice_length'));
            $('select#datatableLengthDd').selectpicker('refresh');
            $('select#datatableLengthDd').change(function(){
                var length = $('select#datatableLengthDd option:selected').val();
                $.cookie('invoice_length', length);
                location.reload(true);
            })  
        });
    </script>
@stop