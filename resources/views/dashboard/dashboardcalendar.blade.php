@extends('layouts.app')

@section('required-styles-for-this-page')

{!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}

{!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}

<!-- start: Sweet alert css -->
   {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
    <!-- end: Sweet alert css -->

	<!-- Start: NEW timepicker css -->  
    {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
    <!-- End: NEW timepicker css -->

@stop

@section('page-title')
	   To-Do List Calendar
@stop

@section('content')
		

	<!-- start: Calendar Jumper -->
	<div class="btn-group calJumper">
		<a class="btn btn-primary btn-o dropdown-toggle hidden" data-toggle="dropdown" href="#">
			<i class="fa fa-angle-double-left"></i>
		</a>
		<ul role="menu" class="dropdown-menu dropdown-light">
			<li>
				<a href="#" data-jump-amount="1" data-jump-unit="weeks">
					1 week
				</a>
			</li>
			<li>
				<a href="#" data-jump-amount="2" data-jump-unit="weeks">
					2 weeks
				</a>
			</li>
			<li>
				<a href="#" data-jump-amount="3" data-jump-unit="weeks">
					3 weeks
				</a>
			</li>
			<li>
				<a href="#" data-jump-amount="4" data-jump-unit="weeks">
					4 weeks
				</a>
			</li>
			<li>
				<a href="#" data-jump-amount="5" data-jump-unit="weeks">
					5 weeks
				</a>
			</li>
			<li>
				<a href="#" data-jump-amount="6" data-jump-unit="weeks">
					6 weeks
				</a>
			</li>
			<li>
				<a href="#" data-jump-amount="7" data-jump-unit="weeks">
					7 weeks
				</a>
			</li>
			<li>
				<a href="#" data-jump-amount="8" data-jump-unit="weeks">
					8 weeks
				</a>
			</li>
			<li>
				<a href="#" data-jump-amount="6" data-jump-unit="months">
					6 months
				</a>
			</li>
			<li>
				<a href="#" data-jump-amount="1" data-jump-unit="years">
					1 year
				</a>
			</li>
		</ul>
	</div>
	<!-- end: Calendar Jumper -->

	<div id='calendar' class="todocalendar"></div>

	<!-- start: Filter dropdown -->
	{!! Form::select('type[]', $taskcategories, 'all', ['class' => 'form-control toolBarDd m-l-0 categ-filter-cal todoselect', 'autocomplete' => 'off','id'=>'categorylist','multiple' ,'data-categtype'=>'1']) !!}
	<!-- end: Filter dropdown -->

	{!! Form::hidden('selectedDatetime') !!}
	@include('dashboard.taskmodel')

	

@stop

@section('required-script-for-this-page')
	{!! Html::script('assets/js/jquery-ui.min.js') !!}

    <!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
	{!! Html::script('assets/js/set-moment-timezone.js') !!}  -->

    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}

    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!} 

    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}

    {!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}

    {!! Html::script('assets/js/helper.js') !!}

    {!! Html::script('assets/js/recurrence.js') !!}

    {!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}

	<!-- start: Index jquery -->
    {!! Html::script('assets/js/index.js') !!}
    <!-- end: Index jquery -->

   
<script>	
var bladeType = "DashboardCalendar";
var calendarstatusvalue;
 calendarstatusvalue=0;
var loggedInUser = {
        //type: '{{ Session::get('userType') }}',
        type: '{{ Auth::user()->account_type }}',
        id: {{ Auth::user()->account_id }},
        userId: {{ Auth::id() }},
        name: '{{ Auth::user()->fullName }}'
};

var currentUserId = loggedInUser.userId;
var shownPopover = [];
var calPopupHelper = $('#calPopupHelper');
var popoverContainer = $('#calendar');

var deleteReccurEventPopoverOpt = {
        placement: 'left',
        html: true,
        content: "Would you like to cancel only this task,<br> or this and all following tasks in the<br> series?<a class='btn btn-default btn-block delete-event' href='#' data-target-event='future'>This and future</a> <a class='btn btn-default btn-block delete-event' href='#' data-target-event='this'>This only</a>",
        container: popoverContainer,
        title: "<strong>Cancel recurring task?</strong>",
        trigger: 'manual'
    };

var editReccurEventPopoverOpt = {
        placement: 'left',
        html: true,
        content: "Would you like to change only this task,<br> or this and all following tasks in the<br> series?<a class='btn btn-default btn-block update-event' href='#' data-target-event='future'>This and future</a> <a class='btn btn-default btn-block update-event' href='#' data-target-event='this'>This only</a>",
        container: popoverContainer,
        title:"<strong>Edit recurring task?</strong>",
        trigger: 'manual'
};

$(document).ready(function(){
   initCustomValidator();

   $("#taskFilter").datepicker({
        showOn: 'button',
        buttonText: moment().format('D MMM YYYY'),
        buttonImageOnly: false,
        dateFormat:"d M yy",
        
        onSelect: function( newText ){
            //$('#taskFilterSection img').attr('alt',newText);
            $('.ui-datepicker-trigger').text(newText);
            var dropDownName=$('.rapidodd').text();
            var triggerChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
            $(triggerChange).trigger('click');
        }
    });

   $('.ui-datepicker-trigger').addClass('btn btn-sm btn-transparent-white');
   
});    
</script>
	{!! Html::script('assets/js/dashboard-calendar.js?v='.time()) !!}

	{!! Html::script('assets/js/new-dashboard.js?v='.time()) !!}

@stop