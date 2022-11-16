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
<!--{!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}-->
<!-- end: Bootstrap timepicker -->

<!-- Start: Old timepicker css -->
{{-- {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!} --}}
<!-- End: Old timepicker css -->

<!-- Start: NEW datetimepicker css -->
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') !!}
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css') !!}
<!-- End: NEW datetimepicker css -->

<!-- start: Full Calendar -->
{!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
<!-- end: Full Calendar -->

<!-- start: Sweet Alert -->
{!! Html::style('vendor/sweetalert/sweet-alert.css') !!}
<!-- end: Sweet Alert -->

<style type="text/css">
.calendertopbtn.fixed {
	position: fixed;
	top: 70px;
	z-index: 9999;
}
.calendertopServicebtn.fixed {
	position: fixed;
	top: 70px;
	z-index: 9999;
	margin-left:219px;
}
#calendar .fixed{
	padding-top: 50px;
}
.fc-head.sticky {
	position: fixed;
	top: 160px;
	background: #fff;
	z-index: 999;
	max-width: 1060px;
}
@media (max-width: 767px){
	.fc-head.sticky {
		position: static;
	}
}
#recurrClassClientReschecModal{
	bottom: auto;
	}
#recurrClassClientReschecModal:before{
	display: none;
}
</style>
@stop()


@if(!isset($subview))
@section('page-title')
Calendar
@stop
@endif	

@section('content')
<button style="margin-bottom: 10px;" class="btn btn-success calendertopbtn" id="loadCalendarWithUnavailableStaff">Classes without trainers / area</button>
<button style="margin-bottom: 10px;" class="btn btn-success calendertopServicebtn" id="loadCalendarWithUnavailableStaffService">Services without trainers / area</button>
@if(!count($locsAreas) || !($ifServicesExit || $ifClassesExit))
<div class="well well-sm" id="noRescoureFound"> 
	<h4>We're still missing some information on your business:</h4>
	<ul class="lh-22">
		@if(!count($locsAreas))
		<li class="text-danger">No Areas found that are linked to {{ calendarErrMsg() }}.</li>
		@endif

		@if(!($ifServicesExit || $ifClassesExit))
		<li class="text-danger">No Services/Classes found that are linked to {{ calendarErrMsg() }} or any area.</li>
		@endif
	</ul>
</div>
@else
<!-- Notification message -->
<div id="dateSelMsg" class="center clearfix hidden">
	{{ HTML::link('#', '&times;', array('class' => 'pull-right')) }}
	<span></span>
</div>


<!-- start: Appoinment Cancel Modal -->
@include('includes.partials.appointment_cancel_modal', ['reasons' => $reasons])
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
<div id="eventHtml-div">
	{!! $eventObj !!}
</div>
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
						{!! Form::hidden('isRepeating') !!}
						{!! Form::hidden('targetEvents') !!}

						<div class="row">
							<div class="col-md-12">
								<div class="tabbable">
									<ul id="classTabs" class="nav nav-tabs">
										<li class="active">
											<a href="#busytime" data-toggle="tab">
												<i class="fa fa-clock"></i>  Busy Time
											</a>
										</li>
										<li>
											<a href="#busyReccurs" data-toggle="tab">
												<i class="fa fa-refresh"></i> Recurrence
											</a>
										</li>
										<li>
											<a href="#busyAttendance" data-toggle="tab">
												<i class="fa fa-list"></i>  Attendance
											</a>
										</li>
									</ul> 
									<div class="tab-content">
										<div class="tab-pane fade in active" id="busytime">
											<fieldset class="padding-15 client-form">
												<legend>
													Busy Time &nbsp;&nbsp;&nbsp;&nbsp;
												</legend>
												<div class="form-group set-group">
													{!! Form::label('modalLocArea-busy', 'Location - Area', ['class' => 'strong']) !!}
													<div class="set-group-disp"><span></span>
														<a href="javascript:void(0)">change</a>
														</div>
													{!! Form::select('modalLocArea', $modalLocsAreas, null, ['class' => 'form-control loc-area-dd onchange-set-neutral','id'=>'modalLocArea-busy']) !!}
													<span class="help-block"></span>
												</div>
												<div class="form-group set-group staffDiv">
													{!! Form::label('staff-busy', 'Staff', ['class' => 'strong']) !!}
													{{-- <div class="set-group-disp"><span></span> {{ HTML::link('#', 'change') }}</div> --}}
													{!! Form::select('staff', [], null, ['class' => 'form-control', 'multiple'=>'multiple', 'data-actions-box'=>'true','id'=>'staff-busy']) !!}
												</div>
												<div class="form-group">
													<label for="" class="strong">Selected Staffs</label>
													{{-- {{ HTML::link('javascript:void(0)', 'change', ['class' => 'staffChange']) }} --}}
													<a href="javascript:void(0)" class="staffChange">change</a>
													<div class="selected_staff_list">
														
													</div>
												</div>
												<div class="form-group availableStaff">
													<label for="" class="strong">Available Staffs *</label>
													<div class="available_staff_list">
													</div>
												</div>

												<div class="form-group">
													{!! Form::label(null, 'Date *', ['class' => 'strong']) !!}
													<div class="clearfix moveErrMsg">
														<div class="pull-left">
															<span class="eventDateDisp"></span> 
															at 
														</div>
														<div class="input-group bootstrap-timepicker timepicker eventTime">
															{!! Form::text('eventTime', null, ['class' => 'form-control timepicker1 busy-time-timepicker', 'autocomplete' => 'off', 'required']) !!}
															<span class="input-group-addon">
																<a class="busy-timepicker-btn event-date-timepicker" href="javascript:void(0)"><span class="glyphicon glyphicon-time" style="color: white"></span></a>
														</div>
														{{ HTML::link('#', 'change', ['class' => 'eventDateChange pull-left']) }}
													</div>
													<span class="help-block placeErrMsg"></span>
												</div>
												
												<div class="form-group">
													{!! Form::label('busyDur', 'Duration *', ['class' => 'strong']) !!}
													
													    <div class="input-group col-md-6">
														<input id="busyDur" type="text" class="form-control" name="busyDur" required>
														<span class="input-group-addon">Min</span>
													    </div>
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
									<div class="tab-pane fade" id="busyReccurs">
									<fieldset class="padding-15 event-reccur">
										<legend>
				                            Recurrence Details &nbsp;&nbsp;&nbsp;&nbsp;
				                        </legend>
							         	<div class="form-group">
					                        {!! Form::label('eventRepeat', 'Repeat', ['class' => 'strong']) !!}
					                        {!! Form::select('eventRepeat', ['' => '-- Select --', 'None' => 'None', 'Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly'], null, ['class' => 'form-control']) !!}
					                        <div class="line"></div>
					                    </div>

										<div class="eventRepeatFields">
					                    	<div class="form-group">
						                        {!! Form::label('eventRepeatInterval', 'Repeat every *', ['class' => 'strong']) !!}
						                        <div>
						                        	{!! Form::select('eventRepeatInterval', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-94p onchange-set-neutral', 'required']) !!} 
						                        	<span class="eventRepeatIntervalUnit">days</span>
						                        	<div class="line"></div>
						                        </div>
						                    </div>

						                    <div class="form-group append-week-class">
						                    	{!! Form::label(null, 'Ends *', ['class' => 'strong']) !!}
							                    <div class="moveErrMsg no-error-labels">
								                    <div class="radio clip-radio radio-primary">
								                        <input type="radio" name="busyRepeatEnd" id="busyEventRepeatEndAfter" value="After">
								                        <label for="busyEventRepeatEndAfter">
								                            After
								                        </label>
								                        {!! Form::select('eventRepeatEndAfterOccur', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-120 onchange-set-neutral']) !!}
								                        occurrences
								                        <div class="line"></div>
								                    </div>
								                    <div class="radio clip-radio radio-primary">
								                        <input type="radio" name="busyRepeatEnd" id="busyEventRepeatEndOn" value="On">
								                        <label for="busyEventRepeatEndOn">
								                            On
								                        </label>
								                        <!-- {!! Form::text('busyRepeatEndOnDate', null, ['class' => 'form-control mw-120 inlineBlckDisp eventDatepicker onchange-set-neutral', 'autocomplete' => 'off']) !!} -->
								                        <input type="text" name="busyRepeatEndOnDate" class="form-control event-date-btn mw-120 inlineBlckDisp onchange-set-neutral" value="">
								                        <div class="line"></div>
								                    </div>
								                    <div class="radio clip-radio radio-primary m-b-0">
								                        <input type="radio" name="busyRepeatEnd" id="busyEventRepeatEndNever" value="Never">
								                        <label for="busyEventRepeatEndNever">
								                            Never
								                        </label>
								                    </div>
								                </div>
												<span class="help-block placeErrMsg m-t-0"></span>
												
											

							                	<div class="busyRepeatWeekdays no-error-labels">
													
							                
							                    </div>
							                    <span class="help-block m-t-0"></span>
							                </div>
					                    </div>        
									</fieldset>
								</div>
								<div class="tab-pane fade" id="busyAttendance">
									<p>
										Set the attendance status  
										 <a href="#">mark all as attended</a>.
									</p>
									<hr class="m-t-0">
									<div id="busyAttendanceList" class="row">
										<div class="col-sm-12">
											<div class="btn-group" data-toggle="buttons" data-prev-val="">
												<label class="btn btn-default busyStatus">
													<input type="radio" autocomplete="off" value="complete">Complete
												</label>
												<label class="btn btn-default busyStatus">
													<input type="radio" autocomplete="off" value="incomplete">Incomplete
												</label>
												<label class="btn btn-default busyAppointReschedule"><input type="radio" autocomplete="off" value="" >Reschedule</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						</div>
						</div>
						{!! Form::close() !!}
					</div>
					<div class="modal-footer clearfix">
					<!-- 	<a class="btn btn-red pull-left delete-prompt" data-toggle="modal" data-target="#delmodal"> -->
							<a class="btn btn-red pull-left" data-toggle="modal" data-target="#delmodal">
							<i class="glyphicon glyphicon-trash"></i>
							Delete
						</a>
						<button type="button" class="btn btn-default" data-dismiss="modal"><span>Cancel</span></button>
						<button type="button" class="btn btn-primary submit">Save</button>
					</div>
				</div>
			</div>
		</div>
		<!-- end: Busy Modal -->


<!-- reschedule busy modal -->
<div class="modal fade" id="busyReschecModal" role="dialog">
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
<!-- end reschedule busy modal  --> 

<!-- delete modal -->
<div class="modal fade" id="openBusyDelmodal" role="dialog">
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
				<a class='btn btn-primary m-r-10 delete-event' href='#' data-target-event='this' data-dismiss="modal">This only</a>
				<a class='btn btn-primary delete-event' href='#' data-target-event='future' data-dismiss="modal">This and future</a> 
				{!! Form::close() !!}	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<!-- end delete modal -->

<!-- delete modal-->
<div class="modal fade" id="delmodal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content bg-white">
			<div class="modal-body text-center">
				<h2>What do you want to do with this client?</h2>
				<a class="btn btn-primary m-r-10 busyAppointReschedule" href="#">Reschedule</a>
				<button class="btn cancel openDeleteBusyModal" tabindex="2" style="display: inline-block;">Just delete it</button>
			</div>
		</div>
	</div>
</div>
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


		<!-- start: Class Modal -->
		@include('includes.partials.class_modal', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
		<!-- end: Class Modal -->
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
				<!--<li>
					<a href="#" class="eventAdd" data-target-modal="appointModal">
						Service
					</a>
				</li>
				<li>
					<a href="#" class="eventAdd" data-target-modal="classModal">
						Class
					</a>
				</li>-->
				<li>
					<a href="#" class="eventAdd" data-target-modal="classModal">
						Booking
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
			@endif

			<!-- start: Filter dropdown -->
			{!! Form::select('type', $locsAreas, null, ['class' => 'form-control toolBarDd loc-area-dd', 'autocomplete' => 'off']) !!}
			{!! Form::select('type', $staffs, 'all', ['class' => 'form-control toolBarDd staff-filter-cal', 'autocomplete' => 'off']) !!} <!--$stff-->
			<!-- end: Filter dropdown -->

			{!! Form::hidden('workingHours', json_encode($staffHours), ['autocomplete' => 'off']) !!} 
			{!! Form::hidden('selectedDatetime') !!}  
			{!! Form::hidden('changeSessionTime') !!}    

			@if(isset($cl))
			{!! Form::hidden('defaultClient', htmlentities($cl), ['autocomplete' => 'off']) !!}
			@else
			{!! Form::hidden('clientsDetails', htmlentities($clients), ['autocomplete' => 'off']) !!}
			@endif

			<!-- start: HIDDEN FIELD FOR DATA FATCH CALENDAR SETTING -->
			<input type='hidden' name='calendarSettingVal' value ='{{ json_encode($calendarSettingVal) }}' >
			<!-- end: HIDDEN FIELD FOR DATA FATCH CALENDAR SETTING -->

			<?php
			if(!isset($enableDateFrom))
				$enableDateFrom = ''; 
			?>
			{!! Form::hidden('enableDateFrom', $enableDateFrom, ['autocomplete' => 'off']) !!}

			<?php
			if(!isset($enableDatePeriod))
				$enableDatePeriod = ''; 
			?>
			{!! Form::hidden('enableDatePeriod', $enableDatePeriod, ['autocomplete' => 'off']) !!}

			<?php
			if(!isset($enableDateTo))
				$enableDateTo = ''; 
			?>
			{!! Form::hidden('enableDateTo', $enableDateTo, ['autocomplete' => 'off']) !!}

			<?php
			if(!isset($removeStepType))
				$removeStepType = $removeBookType = ''; 
			?>
			{!! Form::hidden('removeStepType', $removeStepType, ['autocomplete' => 'off']) !!}
			{!! Form::hidden('removeBookType', $removeBookType, ['autocomplete' => 'off']) !!}

			{!! Form::hidden('closedDates', $closedDates, ['autocomplete' => 'off']) !!}

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

    <!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('assets/js/set-moment-timezone.js?v='.time()) !!} -->

    <!-- start: jquery validation -->
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}
    <!-- end: jquery validation -->

    <!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Bootstrap timepicker -->
    <!--{!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}-->
    <!-- end: Bootstrap timepicker -->
    
    <!-- Start:  Old timepicker js -->
    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
    <!-- End: Old timepicker js --> 

    <!-- Start:  NEW datetimepicker js -->
    {!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!}
    <!-- End: NEW datetimepicker js --> 

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

    <!-- start: Dirty Form -->
    {!! Html::script('assets/js/dirty-form.js?v='.time()) !!}
    <!-- end: Dirty Form -->

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    <!-- start: Events -->
    <script>	
    	var loggedInUser = {
			//type: '{{ Session::get('userType') }}',
			type: '{{ Auth::user()->account_type }}',
			id: '{{ Auth::user()->account_id }}',
			userId: '{{ Auth::id() }}',
			name: '{{ Auth::user()->fullName }}'
		},
		popoverContainer = $('#calendar');
		
	</script>

	<script src="{{asset('assets/js/events.js?v='.time())}}"></script>
	<!-- end: Events -->

	<!-- start: Full Calendar Custom Script -->
	<script src="{{asset('assets/js/calendar.js?v='.time())}}"></script>

	<!-- end: Full Calendar Custom Script -->   

	<script>
		var isOpenMakeupEvent = true;
		makeup_eventid = '{{ isset($makeup_event_id)?$makeup_event_id:"" }}',
		makeup_eventtype = '{{ isset($makeup_event_type)?$makeup_event_type:"" }}',
		makeup_eventdate = '{{ isset($makeup_event_date)?$makeup_event_date:""}}';
	</script>
	<script type="text/javascript">
		$('body').on('click','#loadCalendarWithUnavailableStaff',function(){
			var isShowUnavailableStaffView = true;
			getStaffEvents('all', true, 0, false,isShowUnavailableStaffView,false);
		});
		$(window).scroll(function(){
			if ($(this).scrollTop() >120) {
				$('.calendertopbtn').addClass('fixed');
			} else {
				$('.calendertopbtn').removeClass('fixed');
			}
		});
		$(window).scroll(function() {
			if ($(this).scrollTop() > 200){  
				$('.alt-header').show();
			}
			else{
				$('.alt-header').hide();
			}
		});
		
		$('document').ready(function(){
			$('.fc-toolbar').append('<table class="alt-header" style="display:none;"><thead class="fc-head"><tr><td class="fc-head-container fc-widget-header"><div class="fc-row fc-widget-header"><table><thead><tr><th class="fc-day-header fc-widget-header fc-mon">Mon</th><th class="fc-day-header fc-widget-header fc-tue">Tue</th><th class="fc-day-header fc-widget-header fc-wed">Wed</th><th class="fc-day-header fc-widget-header fc-thu">Thu</th><th class="fc-day-header fc-widget-header fc-fri">Fri</th><th class="fc-day-header fc-widget-header fc-sat">Sat</th><th class="fc-day-header fc-widget-header fc-sun">Sun</th></tr></thead></table></div></td></tr></thead></table>');
		})
		
	</script> 
	<script type="text/javascript">
		$('body').on('click','#loadCalendarWithUnavailableStaffService',function(){
			var isShowUnavailableStaffViewService = true;
			getStaffEvents('all', true, 0, false,false,null,false,false,isShowUnavailableStaffViewService);
		});
		$(window).scroll(function(){
			if ($(this).scrollTop() >120) {
				$('.calendertopServicebtn').addClass('fixed');
			} else {
				$('.calendertopServicebtn').removeClass('fixed');
			}
		});
	// When the modal is shown, we want a fixed body
document.body.style.position = 'fixed';
document.body.style.top = `-${window.scrollY}px`;

// When the modal is hidden, we want to remain at the top of the scroll position
document.body.style.position = '';
document.body.style.top = '';
		
		// $('document').ready(function(){
		// 	$('.fc-toolbar').append('<table class="alt-header" style="display:none;"><thead class="fc-head"><tr><td class="fc-head-container fc-widget-header"><div class="fc-row fc-widget-header"><table><thead><tr><th class="fc-day-header fc-widget-header fc-mon">Mon</th><th class="fc-day-header fc-widget-header fc-tue">Tue</th><th class="fc-day-header fc-widget-header fc-wed">Wed</th><th class="fc-day-header fc-widget-header fc-thu">Thu</th><th class="fc-day-header fc-widget-header fc-fri">Fri</th><th class="fc-day-header fc-widget-header fc-sat">Sat</th><th class="fc-day-header fc-widget-header fc-sun">Sun</th></tr></thead></table></div></td></tr></thead></table>');
		// })
		
	</script> 
	@stop()

	@section('script-handler-for-this-page')
	@stop()

	@section('script-after-page-handler')
	@stop()