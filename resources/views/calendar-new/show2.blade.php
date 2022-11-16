@extends('layouts.app')
@section('meta_description')
@stop()

@section('meta_author')
@stop()

@section('meta')
@stop()

@section('before-styles-end')
@stop()

@section('required-styles-for-this-page')
<!-- start: Bootstrap Select Master -->
{!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Bootstrap timepicker -->
{!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
<!-- end: Bootstrap timepicker -->

<!-- start: Full Calendar -->
{!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
<!-- end: Full Calendar -->

<!-- start: Sweet Alert -->
{!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
<!-- end: Sweet Alert -->
@stop()

@if(!isset($subview))
	@section('page-title')
	   Calendar
	@stop
@endif	

@section('content')
	@if(!count($locsAreas) || !$ifServicesExit || !$ifClassesExit)
    <div class="well well-sm" id="noRescoureFound"> 
    	<h4>We're still missing some information on your business:</h4>
    	<ul class="lh-22">
	    	@if(!count($locsAreas))
	    		<li class="text-danger">There are no Areas found that are linked to {{ calendarErrMsg() }}.</li>
	    	@endif

	    	@if(!$ifServicesExit)
	    		<li class="text-danger">There are no Services found that are linked to {{ calendarErrMsg() }}.</li>
	    	@endif

	    	@if(!$ifClassesExit)
	    		<li class="text-danger">There are no Classes found that are linked to {{ calendarErrMsg() }}.</li>
	    	@endif
	    </ul>
    </div>
    @else
    <!-- Notification message -->
	<div id="dateSelMsg" class="center clearfix hidden">
		{{ HTML::link('#', '&times;', array('class' => 'pull-right')) }}
		<span></span>
	</div>

	<!-- start: Appoinment Model -->
	@include('includes.partials.appointment_modal', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
	<!-- end: Appoinment Model -->

	<!-- start: Appoinment Cancel Modal -->
	@include('includes.partials.appointment_cancel_modal')
	<!-- end: Appoinment Cancel Modal -->

	<!-- start: Recurring Appoinment Reschedule Modal -->
	<div class="modal fade" id="recurrAppointReschecModal" role="dialog">
	    <div class="modal-dialog">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close m-t--10" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body bg-white text-center">
	            	{!! Form::open(['url' => '', 'role' => 'form']) !!}
	            		{!! Form::hidden('date') !!}
	            		{!! Form::hidden('time') !!}
	            		<p>
		            		Would you like to apply this change to this event only,
		            		<br>
		            		or the current and future events in the series? 
						</p>
						<a class='btn btn-primary m-r-10 reschedule-event' href='#' data-target-event='this' data-dismiss="modal">This only</a>
						<a class='btn btn-primary reschedule-event' href='#' data-target-event='future' data-dismiss="modal">This and future</a> 
					{!! Form::close() !!}	
	        	</div>
	    		<div class="modal-footer">
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            </div>
	    	</div>
	    </div>
	</div>
	<!-- end: Recurring Appoinment Reschedule Modal -->

	<!-- start: Recurring Class Client Reschedule Modal -->
	<div class="modal fade" id="recurrClassClientReschecModal" role="dialog">
	    <div class="modal-dialog">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <!--<button type="button" class="close m-t--10" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	                </button>-->
	            </div>
	            <div class="modal-body bg-white text-center">
            		<p>
	            		Would you like to apply this change to this event only,
	            		<br>
	            		or the current and future events in the series? 
					</p>
					<a class='btn btn-primary m-r-10 reschedule-class_client' href='#' data-target-event='this' data-dismiss="modal">This only</a>
					<a class='btn btn-primary reschedule-class_client' href='#' data-target-event='future' data-dismiss="modal">This and future</a> 
	        	</div>
	    		<div class="modal-footer">
	            	<!--<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>-->
	            </div>
	    	</div>
	    </div>
	</div>
	<!-- end: Recurring Class Client Reschedule Modal -->

	<!-- start: Class Modal -->
	@include('includes.partials.class_modal', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
	<!-- end: Class Modal -->

	<!-- start: Busy Modal -->
	<div class="modal fade" id="busyModal" role="dialog">
	    <div class="modal-dialog">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close m-t--10" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body bg-white">
	            	{!! Form::open(['url' => '', 'role' => 'form']) !!}
						{!! Form::hidden('eventId') !!}
						<div class="row">
							<div class="col-md-12">
								<fieldset class="padding-15 client-form">
			                        <legend>
			                            Busy Time &nbsp;&nbsp;&nbsp;&nbsp;
			                        </legend>
						         	<div class="form-group set-group">
                                        {!! Form::label('modalLocArea', 'Location - Area', ['class' => 'strong']) !!}
                                        <div class="set-group-disp"><span></span> {{ HTML::link('#', 'change') }}</div>
                                        {!! Form::select('modalLocArea', $modalLocsAreas, null, ['class' => 'form-control loc-area-dd onchange-set-neutral']) !!}
                                    </div>
                                    <div class="form-group set-group">
                                        {!! Form::label('staff', 'Staff', ['class' => 'strong']) !!}
                                        <div class="set-group-disp"><span></span> {{ HTML::link('#', 'change') }}</div>
                                        {!! Form::select('staff', [], null, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label(null, 'Date *', ['class' => 'strong']) !!}
                                        <div class="clearfix moveErrMsg">
                                        	<div class="pull-left">
                                        		<span class="eventDateDisp"></span> 
                                        		at 
                                        	</div>
                                        	<div class="input-group datetimepicker eventTime">
											  	{!! Form::text('eventTime', null, ['class' => 'form-control', 'autocomplete' => 'off', 'required']) !!}
											 	<span class="input-group-addon">
											 		<span class="glyphicon glyphicon-time"></span>
											 	</span>
											</div>
											{{ HTML::link('#', 'change', ['class' => 'eventDateChange pull-left']) }}
                                        </div>
                                        <span class="help-block placeErrMsg"></span>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('busyDur', 'Duration *', ['class' => 'strong']) !!}
                                        {!! Form::select('busyDur', ['' => '-- Select --', '5' => '5 min', '10' => '10 min', '15' => '15 min', '20' => '20 min', '25' => '25 min', '30' => '30 min', '35' => '35 min', '40' => '40 min', '45' => '45 min', '50' => '50 min', '55' => '55 min', '60' => '60 min'], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
                                    </div>
				                    <div class="form-group">
										{!! Form::label('busyDesc', 'Description', ['class' => 'strong']) !!}
										{!! Form::textarea('busyDesc', null, ['class' => 'form-control textarea']) !!}
										<div class="checkbox clip-check check-primary m-b-0 m-t-5">
		                                    {!! Form::checkbox('busyDenyBook', '1', null, ['id' => 'busyDenyBook']) !!}
		                                    <label for="busyDenyBook" class="no-error-label">
		                                        <strong>Prevent online bookings during this time?</strong>
		                                    </label>
		                                </div>
						            </div>               
			                    </fieldset>
							</div>
						</div>
					{!! Form::close() !!}
	        	</div>
	    		<div class="modal-footer clearfix">
	    			<a class="btn btn-red pull-left delete-prompt" href="#">
						<i class="glyphicon glyphicon-trash"></i>
						Delete
					</a>
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            	<button type="button" class="btn btn-primary submit">Save</button>
	            </div>
	    	</div>
	    </div>
	</div>
	<!-- end: Busy Modal -->

	@if(isUserType(['Admin']))
	<!-- start: Credit Modal -->
	<div class="modal fade" id="creditModal" role="dialog">
	    <div class="modal-dialog">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close m-t--10" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body bg-white">
	            	{!! Form::open(['url' => '', 'role' => 'form']) !!}
		            	<div class="row">
							<div class="col-md-12">
								<fieldset class="padding-15 client-form">
			                        <legend>
			                            Credit &nbsp;&nbsp;&nbsp;&nbsp;
			                        </legend>
						         	<div class="form-group">
				                        {!! Form::label('creditAmount', 'Amount *', ['class' => 'strong']) !!}
				                        {!! Form::text('creditAmount', null, ['class' => 'form-control price-field', 'required']) !!}
				                    </div>
				                    <div class="form-group">
				                        {!! Form::label(null, 'Client *', ['class' => 'strong']) !!}
				                        {!! Form::text(null, null, ['class' => 'form-control clientList', 'autocomplete' => 'off', 'required']) !!}
				                        {!! Form::hidden('clientId') !!}
				                    </div>
				                    <div class="form-group">
										{!! Form::label('creditExpire', 'Expires *', ['class' => 'strong']) !!}
										{!! Form::text('creditExpire', null, ['class' => 'form-control eventDatepicker onchange-set-neutral', 'required', 'autocomplete' => 'off', 'readonly']) !!}
										<span class="help-block"></span>
	                                    <div class="checkbox clip-check check-primary m-b-0 m-t-5">
	                                    	{!! Form::checkbox('creditExpireNever', '1', 1, ['id' => 'creditExpireNever', 'data-default-state' => 'checked']) !!}
	                                        <label for="creditExpireNever" class="no-error-label">
	                                            <strong>Never</strong>
	                                        </label>
	                                    </div>
						            </div>
				                    <div class="form-group">
										{!! Form::label('creditReason', ' Reason for credit', ['class' => 'strong']) !!}
										{!! Form::textarea('creditReason', null, ['class' => 'form-control textarea']) !!}
						            </div>               
			                    </fieldset>
							</div>
						</div>
					{!! Form::close() !!}
	        	</div>
	    		<div class="modal-footer clearfix">
	    			<a class="font-14 pull-left" data-content="Once the credit has been paid for, raise an invoice and click the credit icon." data-placement="right" data-toggle="popover" data-trigger="hover" data-title="<strong>Redeeming credit</strong>" data-html="true">
	    				<i class="fa fa-question-circle" href="#"></i> How to redeem?
	    			</a>
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            	<button type="button" class="btn btn-primary submit">Save and pay</button>
	            </div>
	    	</div>
	    </div>
	</div>
	<!-- end: Credit Modal -->
	@endif

	<!-- start: Working hours Modal -->
	@include('calendar-new.staff-working-hour-modal')
	<!-- end: Working hours Modal -->

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

	@if(!isset($subview))
		<!-- start: Add Button -->
		<div class="btn-group addBtnGroup">
			<a class="btn btn-primary dropdown-toggle epic-tooltip" data-toggle="dropdown" href="#" rel="tooltip" title="Add events &amp; clients">
				<i class="fa fa-plus"></i>
			</a>
			<ul role="menu" class="dropdown-menu dropdown-light">
				<li>
					<a href="#" class="eventAdd" data-target-modal="appointModal">
						Appointment
					</a>
				</li>
				<li>
					<a href="#" class="eventAdd" data-target-modal="classModal">
						Class
					</a>
				</li>
				<li>
					<a href="#" class="eventAdd" data-target-modal="busyModal">
						Busy Time
					</a>
				</li>
				@if(Auth::user()->hasPermission(Auth::user(), 'create-client'))
					@if(Session::has('businessId'))
					<li>
						<a href="{{ route('clients.create') }}">
							Client
						</a>
					</li>
					@else
					<li>
						<a href="{{ url('settings/business/create') }}">
							Client
						</a>
					</li>
					@endif
				@endif
			</ul>
		</div>
		<!-- end: Add Button -->

		@if(isUserType(['Admin']))
			<!-- start: Sale Button -->
			<a class="btn btn-primary epic-tooltip saleBtnGroup" href="#" data-toggle="modal" data-target="#creditModal" rel="tooltip" title="Issue credit">
				<i class="fa fa-tag"></i>
			</a>
			<!--<div class="btn-group saleBtnGroup">
				<a class="btn btn-primary dropdown-toggle epic-tooltip" data-toggle="dropdown" href="#" rel="tooltip" title="Issue credit">
					<i class="fa fa-tag"></i>
				</a>
				<ul role="menu" class="dropdown-menu dropdown-light">
					<li>
						<a href="#">
							Product
						</a>
					</li>
					<li>
						<a href="#" data-toggle="modal" data-target="#creditModal">
							Credit
						</a>
					</li>
				</ul>
			</div>-->
			<!-- end: Sale Button -->
		@endif
	@else
		{!! Form::hidden('eventHtml', $eventObj) !!}
	@endif

	<!-- start: Filter dropdown -->
	{!! Form::select('type', $locsAreas, null, ['class' => 'form-control toolBarDd loc-area-dd']) !!}
	{!! Form::select('type', [], null, ['class' => 'form-control toolBarDd staff-filter-cal']) !!} <!--$stff-->
	<!-- end: Filter dropdown -->

	{!! Form::hidden('workingHours') !!}   
	{!! Form::hidden('selectedDatetime') !!}  

	@if(isset($cl))
		{!! Form::hidden('defaultClient', htmlentities($cl)) !!}
	@endif

    <div id='calendar'></div>
    <div class="well well-sm hidden center" id="noRostStaff"> 
    	<h4>No staff are rostered on to work during <span></span></h4>
    	<p>
    		<a href="#">View all staff</a>
    		, or assign staff members.
    	</p>
    </div>
    @if(isset($subview))
    	<div class="text-right m-t-20">
	        <button class="btn btn-default closeSubView" type="button">
	            Close
	        </button>
	    </div>
    @endif
	@endif
@stop

@section('required-script-for-this-page')
	{!! Html::script('assets/js/jquery-ui.min.js') !!}

   <!--  {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
	{!! Html::script('assets/js/set-moment-timezone.js?v='.time()) !!}  -->
     
    <!-- start: jquery validation -->
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
    <!-- end: jquery validation -->

    <!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Bootstrap timepicker -->
    {!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
    <!-- end: Bootstrap timepicker -->

    <!-- start: Country Code Selector -->
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js') !!}
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js') !!}
    <!-- end: Country Code Selector -->

    <!-- start: Bootstrap Typeahead -->
    {!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}
    <!-- end: Bootstrap Typeahead -->

    <!-- start: Full Calendar -->
    {!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
    <!-- end: Full Calendar -->

    <!-- start: Sweet Alert -->
    {!! Html::script('vendor/sweetalert/sweet-alert.min.js') !!}
    <!-- end: Sweet Alert -->

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    <!-- start: Events -->
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
    {!! Html::script('assets/js/events2.js') !!}
    <!-- end: Events -->

    <!-- start: Full Calendar Custom Script -->
    {!! Html::script('assets/js/calendar.js?v='.time()) !!}
    <!-- end: Full Calendar Custom Script -->
@stop()

@section('script-handler-for-this-page')
@stop()

@section('script-after-page-handler')
@stop()