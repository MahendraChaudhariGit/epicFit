@extends('layouts.app')

@section('required-styles-for-this-page')

{!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}

{!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}

@stop

@section('page-title')
	   Report

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

	<div id='calendar'></div>

	<!-- <div class="well m-t-15"><h4 class="panel-title"> Total Working Hours: <span class="text-bold" id="totalhours">  </span> </h4> </div> -->


	<!-- start: Filter dropdown -->
	{!! Form::select('type',isset($staff)?$staff:[' -- Select -- '], 'all', ['class' => 'form-control toolBarDd m-l-0 staff-filter-cal', 'autocomplete' => 'off','id'=>'stafflist']) !!}
	<!-- end: Filter dropdown -->
	
	
@stop

@section('required-script-for-this-page')
	{!! Html::script('assets/js/jquery-ui.min.js') !!}

    <!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
	{!! Html::script('assets/js/set-moment-timezone.js') !!}  -->

    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}

    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}

    {!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js?v='.time()) !!}

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

<script>	
    var loggedInUser = {
			//type: '{{ Session::get('userType') }}',
			type: '{{ Auth::user()->account_type }}',
			id: {{ Auth::user()->account_id }},
			userId: {{ Auth::id() }},
			name: '{{ Auth::user()->fullName }}'
		},
		popoverContainer = $('#calendar');


		
</script>

	{!! Html::script('assets/js/report.js?v='.time()) !!}

@stop