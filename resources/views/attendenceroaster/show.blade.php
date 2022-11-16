@extends('layouts.app')
@section('required-styles-for-this-page')

<!-- Start:start: Full Calendar -->  
   {!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
<!-- End: start: Full Calendar -->

<!-- Start: NEW timepicker css -->  
    {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
<!-- End: NEW timepicker css -->

@stop()

@section('page-title')
@stop

@section('content')
<?php  
	if(isset($_GET['date']))
		$urldate = $_GET['date']; 
	else $urldate = date('Y-m-d');
?>

<input type="hidden" name="hiddenpermission" value="{{ $isValidEditPermission }}">	
<div class="fc-toolbar">	
			<button type="button" class="fc-prev-button fc-button fc-state-default">
				<span class="fc-icon fc-icon-left-single-arrow"></span>
			</button><button type="button" class="fc-today-button fc-button fc-state-default active">Today</button><input id="datepicker" type="hidden"><button type="button" class="fc-titleDatepicker-button fc-button fc-state-default btn-wide" name="btnbtn">Thu, 18 May, 2017</button><button type="button" class="fc-next-button fc-button fc-state-default <?php echo ($urldate == date('Y-m-d'))?'hidden':''; ?> ">
				<span class="fc-icon fc-icon-right-single-arrow"></span>
			</button>
</div>
<div class="table-responsive">
	<table class="table table-bordered table-hover" id="roastertable">
		<input type="hidden" name="hoursdata" value="{{ json_encode($hourlist) }}">
		<input type="hidden" name="hiddendate" value="{{ $date }}">
		<thead>
			<tr>
				<th class="w16">Staff </th>
				<th class="rightborder w28 p-x-0" id="firstTimeSlot">12:00am </th>
				<th class="rightborder w28 p-x-0" ><span class="th-8"> 8:00am </span></th>
				<th class="clearfix w28 p-x-0"> <span class="pull-left th-4">4:00pm</span> <span class="pull-right">12:00am</span></th>
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
				<tr>
					<td> 
						<?php if( isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'view-staff')){
            			?>
						<a href="{{ route('staffs.show', $allStaff->id) }}">  
						<?php } ?>
						{{  $allStaff->getFullNameAttribute() }}</a>
					</td>
					<td colspan="3" id="{{ $allStaff->id }}" class="p-x-0">
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
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
	            <div class="modal-body"> 
	            	
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
	
@stop

@section('required-script-for-this-page')

{!! Html::script('assets/js/jquery-ui.min.js?v='.time()) !!}

<!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
    <!-- end: Bootstrap Select Master -->
	
<!-- {!! Html::script('vendor/moment/moment.min.js') !!}
{!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
{!! Html::script('assets/js/set-moment-timezone.jsv='.time()) !!}  -->

{!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}

<!-- Start:  NEW timepicker js -->
   {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!} 
<!-- End: NEW timepicker js -->
<!-- start: jquery validation -->
   {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
<!-- end: jquery validation -->

{!! Html::script('assets/js/helper.js?v='.time()) !!}

{!! Html::script('assets/js/attendence-roaster.js?v='.time()) !!}


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

				if(rightSpace > popoverWidth)
					return 'right';
				else if(leftSpace > popoverWidth)
					return 'left';
				else if(topSpace > popoverHeight)
					return 'top';			
				else
					return 'bottom'	;
			}, 
        html: true,
        content: "<a href='#' class='btn-block attendedclass p-10'><i class='fa fa-check'></i> Attended</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block unattendedclass p-10'><i class='fa fa-thumbs-down'></i> Unattended</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block editclass p-10' data-target='#editHours' data-toggle='modal'><i class='fa fa-edit'></i> Edit</a>",
        container: popoverContainer,
        title: "<strong></strong>",
        trigger: 'manual'
    };

function dbTimeToTimeString(dbTime){
	return moment(dbTime, 'HH:mm:ss').format("h:mm A")
}


</script>
@stop