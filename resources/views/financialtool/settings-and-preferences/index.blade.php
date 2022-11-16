@extends('blank')
@section('page-title')
<span>Epic process</span>

@stop
@section('required-styles')
    {!! Html::style('assets/css/custom.css?v='.time()) !!}
@stop

@section('content')
   
<div class="panel panel-white">
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
        <h5 class="panel-title">
            <span class="icon-group-left">
                <i class="fa fa-ellipsis-v"></i>
            </span>
            Settings & Preferences
            <span class="icon-group-right">
                <a class="btn btn-xs pull-right" href="#" data-toggle="modal"
                data-target="#configModal">
                    <i class="fa fa-wrench"></i>
                </a>
                <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="epic-process">
                    <i class="fa fa-chevron-down"></i>
                </a>
            </span>
        </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">
        <button class="btn btn-primary m-l-15 delete-data">Delete Data </button>
        <div class="clearfix"></div>
        @include('financialtool.settings-and-preferences.country-selection')
        @include('financialtool.settings-and-preferences.timeframe-selection')
        <div class="clearfix"></div>
        @include('financialtool.settings-and-preferences.sole-trader-tax')
        @include('financialtool.settings-and-preferences.partnership-tax')
        <div class="clearfix"></div>
        @include('financialtool.settings-and-preferences.company-tax')
        @include('financialtool.settings-and-preferences.gst-tax')
    </div>
    <!-- end: PANEL BODY -->

</div>
@stop
@section('script')
    {!! Html::script('assets/plugins/bootstrap3-typeahead.min.js?v='.time()) !!}
    {!! Html::script('assets/js/helper.js?v='.time()) !!}
    {!! Html::script('assets/js/settings-preferences.js?v='.time()) !!}

   <script type="text/javascript">
        var recordId = "{!! auth()->user()->business_id !!}";
        $('#financial_time_frame').change(function(){
            var financial_time_frame = $(this).val();
            $.ajax({
                method : "GET" , 
                url : "{{ route('ajax.updateFinancialTimeFrame') }}" ,
                data : { financial_time_frame : financial_time_frame } ,
                success :  function(res) {
                    if(res.status == 'success')
                    { 
                        localStorage.clear();
                    }
                    swal(''+res.msg+'');

                },
            });
        });
        
        $(document).on('click','.delete-data',function(){
            swal({
                title: "Are you sure you want to delete Financial Tool data?",
                text: "This action cannot be undone.",
                type: "error",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonClass: 'btn-danger waves-effect waves-light',
                confirmButtonText: 'Yes,delete it!'
               }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        type: 'get',
                        url : "{{ route('ajaxDeleteData') }}" ,
                        success:function(response){
                            if (response.status == 'success'){
                                swal('Deleted', response.msg,"success");
                                localStorage.clear();
                                location.reload();
                            }else{
                                swal('Whoops!', response.msg, "error");
                            }
                        }
                    });
                }
            });
        });
   </script>

@stop()
@section('script-handler-for-this-page')
@stop()