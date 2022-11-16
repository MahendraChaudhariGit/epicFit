@extends('blank')
@section('page-title')
    <span data-realtime="firstName">{{ $clients->firstname }}</span> <span data-realtime="lastName">{{ $clients->lastname }}</span>
@stop
<style>
    .performed_by{
        color: #2f1d75 !important;
        font-size: 15px;
        margin-left:7px;
    }
    .text{
     color: #0a0a0a !important;
     font-size: 17px;
     margin-left:1px;
      margin-bottom: 10px;
    }
    .time{
        color: #1c23f3 !important;
        font-size: 14px;
    }
    #app .app-content{
    height: initial !important;
}
    </style>
    <!--date range picker-->

<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}" />
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="col-md-5">
            <form action="{{url('activities')}}">
            <label>Select Date : </label>
            <input type="text" name="datefilter" value="" autocomplete="off" />
            <button type="submit">Filter</button>
            <button type="button" id="cleardate">Clear</button>
            <br><br>
            </form>
        </div>

        
    </div>
    </div>
    <script type="text/javascript">
$(function() {

  $('input[name="datefilter"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear'
      }
  });

  $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });
  $('#cleardate').click(function(){
      location.reload();
      window.location.href = public_url+'activities';
  })

});
</script>
<div id="showNotification" class="ps-active-y p-l-10">
@foreach($historyData as $key => $data)

@php
if($data->seh_event_type == 'App\StaffEventClass'){
    $classData = \App\StaffEventClass::where('sec_id',$data->seh_event_id)->withTrashed()->first();
    $clasData = \App\Clas::where('cl_id',$classData->sec_class_id)->first();
    $classServiceName = $clasData->cl_name;
}else {
    $classData = \App\StaffEventSingleService::where('sess_id',$data->seh_event_id)->withTrashed()->first();
    $clasData = \App\Service::where('id',$classData->sess_service_id)->first();
    $classServiceName = $clasData->name;
}
$startTime = \Carbon\Carbon::parse($classData->sec_start_datetime);
$endTime = \Carbon\Carbon::parse($classData->sec_end_datetime);
$classDate = \Carbon\Carbon::parse($classData->sec_date);
@endphp
<div class="font-15">
     <span style="margin-left:2px;">{{$classServiceName }}</span>
     <span style="margin-left:2px;">{{$classDate->format('D, d M Y') }}</span>
     <span style="margin-left:2px;">{{$startTime->format('h:i A') .'-'. $endTime->format('h:i A') }}</span> 
</div>
<div class="text">{{$data->seh_text }}
@if($data->seh_name != 'NULL' && $data->seh_name != '')
    <span class="performed_by">By-{{ $data->seh_name }}</span>
@endif
<div>
   <span class='time'>{{ timeAgo($data->created_at)}}</span></div></div>
@endforeach
</div>
 {{ $historyData->appends($_GET)->links() }}
@endsection
@section('script')
<!-- start: Rating -->
{!! Html::script('assets/plugins/bootstrap-rating/bootstrap-rating.min.js?v='.time()) !!}
<!-- end: Rating -->
    
<!-- start - SUBVIEW JS -->
{!! Html::script('assets/plugins/summernote/dist/summernote.min.js?v='.time()) !!}
<!-- end - SUBVIEW JS -->

<script src="{{ asset('assets/plugins/bootstrap3-typeahead.min.js') }}" ></script>
<script src="{{ asset('assets/js/clients.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
@stop