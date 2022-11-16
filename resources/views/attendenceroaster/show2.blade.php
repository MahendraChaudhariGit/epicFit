@extends('layouts.app')
@section('required-styles-for-this-page')

<!-- Start:start: Full Calendar -->  
   {!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
<!-- End: start: Full Calendar -->

{{-- <!-- Start: NEW timepicker css -->  
    {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
<!-- End: NEW timepicker css --> --}}
 <!-- Start: NEW datetimepicker css -->
 {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') !!}
 {!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css') !!}
 <!-- End: NEW datetimepicker css -->

<!-- start: Bootstrap Select Master -->
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
<!-- end: Bootstrap Select Master -->

{{-- <!-- start: Bootstrap datepicker -->
	{!! Html::style('assets/plugins/datepicker/css/datepicker.css') !!}
<!-- end: Bootstrap datepicker --> --}}

{!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
<style type="text/css">
	@media(max-width: 767px){
		.time-slot{
			width:100px !important;
			margin-left: auto !important;
			margin-right: auto !important; 
		}
		.popover-title{
			font-size: 11px;
			padding: 8px 3px;
			width: 76px;
			text-align: center;
		}
		#tablediv .popover-content > a {
			padding: 10px 3px;
			font-size: 11px;
		}
		.popover.left {
			margin-left: -5px;
		}
	}
	.monthcolumn{
		width: 100% !important
	}
</style>
@stop()

@section('page-title')
@stop

@section('content')
<?php  
	if(isset($_GET['date']))
		$urldate = $_GET['date'];
	else if(isset($_GET['date1']))
		$urldate = $_GET['date1']; 
	else
		$urldate = setLocalToBusinessTimeZone(\Carbon\Carbon::now())->format('Y-m-d');
	
	$today = setLocalToBusinessTimeZone(\Carbon\Carbon::now())->format('Y-m-d');	
?>

<input type="hidden" name="hiddenpermission" value="{{ $isValidEditPermission }}">	
<div class="fc-toolbar" id="calendar">
	@if( isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'create-staff-attendence'))
	<div class="fc-left">
		<button class="btn btn-primary dropdown-toggle epic-tooltip" data-toggle="modal" data-target="#addAttendence" data-placement="top" title="Add Staff Attendence">
			<i class="fa fa-plus"></i>
		</button>
	</div>
	@endif

	<div class="fc-right"><div class="fc-button-group"><button type="button" class="fc-agendaDay-button fc-button fc-state-default fc-corner-left">Day</button><button type="button" class="fc-agendaWeek-button fc-button fc-state-default fc-state-active">Week</button><button type="button" class="fc-agendaMonth-button fc-button fc-state-default fc-corner-right">Month</button></div></div>
	<div class="fc-center"><div class="fc-date-picker-btn"><button type="button" class="fc-prev-button fc-button fc-state-default">
	<span class="fc-icon fc-icon-left-single-arrow"></span>
	</button><button type="button" class="fc-today-button fc-button fc-state-default active">Today</button><input id="datepicker" type="hidden" name="dateHolder"><button type="button" class="fc-titleDatepicker-button fc-button fc-state-default only-for-roster-btn" name="btnbtn">Thu, 9 May, 2099</button><button type="button" class="fc-next-button fc-button fc-state-default ">
		<span class="fc-icon fc-icon-right-single-arrow"></span>
	</button></div>
	</div>
</div>
<div class="outer-roster-attendence">
    <div class="inner-roster-attendence ">
        <table class="table table-bordered table-hover" id="roaastertable">
		<input type="hidden" name="hoursdata" value="{{ json_encode($hourlist) }}">
		<input type="hidden" name="hiddendate" value="{{ $date1 }}">
		<thead>
			<tr style="height:38px;">
				<th class="headcol" style="border-top:1px solid #ccc; top:0; height: 38px !important;">Staff </th>
				<th class="rightborder dayheader m-l-0 p-l-0" id="firstTimeSlot">12:00am </th>
				<th class="rightborder p-x-0 dayheader" ><span class="th-8"> 8:00am </span></th>
				<th class="clearfix  p-x-0 dayheader"> <span class="pull-left th-4">4:00pm</span> <span class="pull-right hidden">12:00am</span></th>
				<th class="weekheader" style="width:115px">Sunday</th>
				<th class="weekheader" style="width:115px">Monday</th>
				<th class="weekheader" style="width:115px">Tuesday</th>
				<th class="weekheader" style="width:115px">Wednesday</th>
				<th class="weekheader" style="width:115px">Thursday</th>
				<th class="weekheader" style="width:115px">Friday</th>
				<th class="clearfix weekheader" style="width:115px">Saturday</th>
				<?php for($i=1;$i<=31;$i++){ ?>
					<th class="monthheader" style="width:30px;">{{ $i }}</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php     
			//$profile_viewer_uid = $_COOKIE['columnWidth'];
			$totalmin = 1440;
			$totalwidth = 900;
			$widthpermin = $totalwidth/$totalmin;
			?>
			
			@foreach($allStaffs as $allStaff)
				<tr class="tr-body">
					<td class="headcol font-9" style="min-height: 38px;"> 
						<?php if( isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-staff')){
            			?>
						<a href="{{ route('staffs.show', $allStaff->id) }}">  
						<?php } ?>
						{{  $allStaff->getFullNameAttribute() }}</a>
					</td>
					<td colspan="3" id="{{ $allStaff->id }}" class="p-x-0 m-l-0 daycolumn" style="height:38px !important;">
					</td>
					<td colspan="7" id="{{ $allStaff->id }}" class="p-x-0 weekcolumn" style="height:38px !important;width:100%;">
					<table style="width:100%"><tr>
					<td class="Sunday weektask" style="width:115px"></td>
					<td class="Monday weektask" style="width:115px"></td>
					<td class="Tuesday weektask" style="width:115px"></td>
					<td class="Wednesday weektask" style="width:115px"></td>
					<td class="Thursday weektask" style="width:115px"></td>
					<td class="Friday weektask" style="width:115px"></td>
					<td class="Saturday weektask" style="width:115px"></td>
					</tr></table>
					</td>

					<td colspan="31" id="{{ $allStaff->id }}" class="p-x-0 monthcolumn" style="padding-top: 0px; padding-bottom: 0px;min-width:100% !important;max-width: 100%;width:100% !important;">
						<table class="table table-hover table-bg" style="margin: 0">
							<tr class="tr-month">
								<?php for($i=1;$i<=31;$i++){ ?>
									<td style="" class="monthtask {{$i}}"></td>
								<?php } ?>
							</tr>
						</table>
					</td>

				</tr>
			@endforeach
		
		</tbody>
	</table>
    </div>
</div>        

<div id="tablediv">
</div>

<div class="modal fade" id="editHours" tabindex="-1" role="dialog" aria-labelledby="Working Hours Modal" aria-hidden="false" style=""><div class="modal-backdrop fade in" style="height: 694px;"></div>
	    <div class="modal-dialog">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">×</span>
	                </button>
	                <h4 class="modal-title">Edit Hours</h4>
	            </div>
	            {!! Form::open(['url' => 'dashboard/task', 'role' => 'form', 'id' =>'attendenceForm']) !!}
	            <input type="hidden" name="hiddenStaffId" value="">
	            <div class="modal-body bg-white"> 
	            	
	            	<div class="row">
	            		<div class="col-md-12">
			            	<div class="form-group">
		                    	{!! Form::label('StartTime', 'Start Time *', ['class' => 'strong']) !!}
		                    	<div class="input-group bootstrap-timepicker timepicker">
				                                <input name="start_time" class="form-control input-sm timepicker1" data-default-time="6:00 AM" type="text" required>    
				                                <span class="input-group-addon">
				                                    <span class="glyphicon glyphicon-time"></span>
				                                </span>
				                </div>
		                    </div>

		                    <div class="form-group">
		                    	{!! Form::label('EndTime', 'End Time *', ['class' => 'strong']) !!}
		                    	<div class="input-group bootstrap-timepicker timepicker">
		                            <input name="end_time" class="form-control input-sm timepicker1" data-default-time="7:00 PM" type="text" required>
				                    <span class="input-group-addon">
				                        <span class="glyphicon glyphicon-time"></span>
				                    </span>
				                </div>
		                    </div>

			            	<div class="form-group">
								<label for="classNote" class="strong">Notes</label>
								<textarea class="form-control textarea" id="classNote" cols="50" rows="10"></textarea>
							</div> 
						</div>
					</div>       
	        	</div>
	    		<div class="modal-footer">
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            	 {!! Form::submit('Submit', ['class' => 'btn btn-primary' , 'id' => 'submitBtn']) !!}
	            </div>
	            {!! Form::close() !!}
	    	</div>
	    </div>
</div>

<!-- Start: Add Attendence Modal -->
<div class="modal fade" id="addAttendence" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Add Hours</h4>
            </div>
            <div class="modal-body bg-white"> 
            	{!! Form::open(['url' => '', 'role' => 'form', 'id' =>'attendenceAddForm']) !!}
            	<div class="row">
            		<div class="col-md-12">
            			<div class="form-group">
	                    	{!! Form::label('staff', 'Staff *', ['class' => 'strong']) !!}
	                    	{!! Form::select('staff', isset($staffs)?$staffs:[] , null, ['class' => 'form-control onchange-set-neutral', 'required', 'data-title'=>'-- Select --']) !!}
	                    </div>
	                    <div class="form-group">
							{!! Form::label('date', 'Date *', ['class' => 'strong']) !!}
							{!! Form::text('date', null, ['class' => 'form-control rosterDatepicker onchange-set-neutral' ,'required' => 'required' ,'autocomplete'=>'off','readonly']) !!}
						</div> 
		            	<div class="form-group">
	                    	{!! Form::label('StartTime', 'Start Time *', ['class' => 'strong']) !!}
	                    	<div class="input-group bootstrap-timepicker timepicker">
                                <input name="start_time" class="form-control input-sm timepicker1" data-default-time="6:00 AM" type="text" required>    
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
			                </div>
	                    </div>
	                    <div class="form-group">
	                    	{!! Form::label('EndTime', 'End Time *', ['class' => 'strong']) !!}
	                    	<div class="input-group bootstrap-timepicker timepicker">
	                            <input name="end_time" class="form-control input-sm timepicker1" data-default-time="7:00 PM" type="text" required>
			                    <span class="input-group-addon">
			                        <span class="glyphicon glyphicon-time"></span>
			                    </span>
			                </div>
	                    </div>

		            	<div class="form-group">
							<label for="attendNote" class="strong">Notes</label>
							<textarea name="notes" class="form-control textarea" id="attendNote" cols="50" rows="10"></textarea>
						</div> 
					</div>
				</div>
				{!! Form::close() !!}       
        	</div>

    		<div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            	<button type="button" class="btn btn-primary" id="submitNewAttend">Submit</button>
            </div>
    	</div>
    </div>
</div>
<!-- End: Add Attendence modal -->

@stop

@section('required-script-for-this-page')
<!-- Start:  NEW datetimepicker js -->
{!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!}
<!-- End: NEW datetimepicker js --> 
{!! Html::script('assets/js/jquery-ui.min.js?v='.time()) !!}

<!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
<!-- end: Bootstrap Select Master -->
	
<!-- {!! Html::script('vendor/moment/moment.min.js') !!}
{!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
{!! Html::script('assets/js/set-moment-timezone.js?v='.time()) !!}  -->

{!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js?v='.time()) !!}

<!-- Start:  NEW timepicker js -->
   {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js?v='.time()) !!} 
<!-- End: NEW timepicker js -->

<!-- start: Bootstrap daterangepicker -->
    {!! Html::script('assets/plugins/bootstrap-daterangepicker/daterangepicker.js?v='.time()) !!}
<!-- end: Bootstrap daterangepicker -->

<!-- start: jquery validation -->
   {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
<!-- end: jquery validation -->

{!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}

{!! Html::script('assets/js/helper.js?v='.time()) !!}

{!! Html::script('assets/js/newroster2.js?v='.time()) !!}



<script>


var loggedInUser = {
        //type: '{{ Session::get('userType') }}',
        type: '{{ Auth::user()->account_type }}',
        id: {{ Auth::user()->account_id }},
        userId: {{ Auth::id() }},
        name: '{{ Auth::user()->fullName }}'
};

var shownPopover = [];
var calPopupHelper = $('#calPopupHelper');
var popoverContainer = $('#tablediv');

var addEventPopoverOptt = {
        placement: function(context, source){
				var pos = $(source).position(),
					leftSpace = pos.left,
					rightSpace = popoverContainer.width()-(leftSpace+$(source).width()),
					topSpace = pos.top,
					popoverWidth = 200,
					popoverHeight = 165;

				return 'left';
				/*if(rightSpace > popoverWidth)
					return 'right';
				else if(leftSpace > popoverWidth)
					return 'left';
				else if(topSpace > popoverHeight)
					return 'top';			
				else
					return 'bottom'	;*/
			}, 
        html: true,
        content: "<a href='#' class='btn-block attendedclass p-10'><i class='fa fa-check'></i> Attended</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block unattendedclass p-10'><i class='fa fa-thumbs-down'></i> Unattended</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block editAttendence p-10'><i class='fa fa-edit'></i> Edit</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block delAttendence p-10'><span style='color:#d9534f'><i class='fa fa-trash'></i> Delete</span></a>",
        container: popoverContainer,
        title: "<strong></strong>",
        trigger: 'manual'
    };

function dbTimeToTimeString(dbTime){
	return moment(dbTime, 'HH:mm:ss').format("h:mm A")
}

$(document).ready(function(){
	setRowHeight();
	$('.epic-tooltip').tooltip();
	$('.rosterDatepicker').datepicker({autoclose:true, dateFormat:"D, d M yy"/*, minDate: new Date()*/});
	//$('.rosterDatepicker').val(moment().format("ddd, D MMM YYYY"));
	
})
$('.timepicker1').bootstrapMaterialDatePicker({ date: false,
     shortTime: true,
     twelvehour: true,
     format: 'HH:mm a'});

</script>
@stop