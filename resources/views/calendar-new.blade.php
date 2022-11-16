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
{!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Bootstrap timepicker -->
{!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css?v='.time()) !!}
<!-- end: Bootstrap timepicker -->

<!-- start: Full Calendar -->
{!! Html::style('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.css?v='.time()) !!}
<!-- end: Full Calendar -->
@stop()

<style type="text/css">
    .modal-backdrop fade in {
        height: 100%;
    }
</style>

@section('page-title')
   Calendar
@stop

@section('content')
    <!-- Notification message -->
	<div id="dateSelMsg" class="center clearfix hidden">
		{{ HTML::link('#', '&times;', array('class' => 'pull-right')) }}
		<span></span>
	</div>

	<!-- start: Appoinment Modal -->
	<div class="modal fade" id="appointModal" role="dialog">
	    <div class="modal-dialog modal-lg">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close m-t--10" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
	            	{!! Form::open(['url' => '', 'role' => 'form']) !!}
						{!! Form::hidden('staffId') !!}
						{!! Form::hidden('eventId') !!}
		            	<div class="tabbable">
							<ul id="appointTabs" class="nav nav-tabs">
								<li class="active">
									<a href="#appointDetails" data-toggle="tab">
										<i class="fa fa-calendar"></i> Details
									</a>
								</li>
								<li>
									<a href="#appointNotes" data-toggle="tab">
										<i class="fa fa-pencil"></i> Notes
									</a>
								</li>
								<li>
									<a href="#appointHist" data-toggle="tab">
										<i class="fa fa-list-alt"></i> History (3)
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade in active" id="appointDetails">
									<div class="row">
										<div class="col-md-6">
						                    <fieldset class="padding-15">
						                        <legend>
						                            General &nbsp;&nbsp;&nbsp;&nbsp;
						                        </legend>
						                        <div class="form-group">
		                                            {!! Form::label('staffLocArea', 'Location - Area', ['class' => 'strong']) !!}
		                                            <div class="staffLocAreaDisp"><span></span> {{ HTML::link('#', 'change') }}</div>
		                                            {!! Form::select('staffLocArea', [], null, ['class' => 'form-control']) !!}
		                                        </div>
		                                        <div class="form-group">
		                                            {!! Form::label(null, 'Date', ['class' => 'strong']) !!}
		                                            <div><span class="appointDateDisp"></span> {{ HTML::link('#', 'change', ['class' => 'eventDateChange']) }}</div>
		                                        </div>
		                                        <div class="form-group">
		                                        	{!! Form::label(null, 'Booking status', ['class' => 'strong']) !!}
			                                        <div class="row">
		                                                <ul class="appoint_status_selectable">
		                                                    <li class="col-xs-6 ui-widget-content">Pencilled-In</li>
		                                                    <li class="col-xs-6 ui-widget-content ui-selected">Confirmed</li>
		                                                    {!! Form::hidden('appointStatusOpt', 'Confirmed') !!}
		                                                </ul>
		                                            </div>
		                                        </div>
		                                        <div class="form-group appoint_status_confirm">
	                                                {!! Form::select('appointStatusConfirm', ['' => '-- Select --', 'Not started' => 'Not started', 'Arrived' => 'Arrived', 'Started' => 'Started', 'Completed' => 'Completed', 'Did not show' => 'Did not show'], null, ['class' => 'form-control']) !!}
	                                            </div>
	                                            <div class="form-group appoint_status_pending">
	                                            	<div class="checkbox clip-check check-primary m-b-0">
		                                            	{!! Form::checkbox('ifAutoExpireAppoint', '1', null, ['id' => 'ifAutoExpireAppoint']) !!}
				                                        <label for="ifAutoExpireAppoint" class="m-r-0 no-error-label">
				                                            <strong>Auto-expire at set time</strong>
				                                        </label>
				                                        <span class="autoExpireAppoint">
					                                        {!! Form::select('autoExpireAppointDur', ['' => '-- Select --', 'Custom' => 'Custom', '2' => '2 hours', '3' => '3 hours', '6' => '6 hours', '12' => '12 Hours', '24' => '24 hours', '48' => '48 Hours', '72' => '72 Hours', '168' => '1 week'], null, ['class' => 'mw-100']) !!}
					                                        <strong>from now</strong>
					                                    </span>
					                                    <div class="autoExpireAppointDurCustom clearfix m-t-10">
					                                    	{!! Form::text('autoExpireAppointDurDate', null, ['class' => 'form-control mw-47p pull-left datepicker', 'autocomplete' => 'off', 'readonly']) !!}
					                                    	<div class="input-group datetimepicker mw-50p pull-left m-l-10">
															  	{!! Form::text('autoExpireAppointDurTime', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
															 	<span class="input-group-addon">
															 		<span class="glyphicon glyphicon-time"></span>
															 	</span>
															</div>
					                                    </div>
				                                    </div>
				                                    <span class="help-block appointStatusError"></span>
	                                            </div>
						                    </fieldset>
		                        		</div>
		                        		<div class="col-md-6">
						                    <fieldset class="padding-15">
						                        <legend>
						                            Client &nbsp;&nbsp;&nbsp;&nbsp;
						                        </legend>
						                        <div class="alert alert-danger hidden" id="newClientReqMsg">
								                    At least one field is required out of Email address and Phone number.
								                </div>
						                        <div class="form-group">
						                        	{!! Form::label('clientName', 'Full Name', ['class' => 'strong']) !!}
						                        	{!! Form::text('clientName', null, ['class' => 'form-control']) !!}
						                        	{!! Form::text(null, null, ['class' => 'form-control clientList', 'autocomplete' => 'off']) !!}
					                        		{!! Form::hidden('clientId') !!}
		                                            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
		                                            	{!! Form::checkbox('isNewClient', '1', null, ['id' => 'isNewClient']) !!}
				                                        <label for="isNewClient" class="no-error-label">
				                                            <strong>New client?</strong>
				                                        </label>
				                                    </div>
		                                        </div>
		                                        <div class="form-group">
		                                			{!! Form::label('clientEmail', 'Email address', ['class' => 'strong']) !!}
		                            				{!! Form::email('clientEmail', null, ['class' => 'form-control clientDetails']) !!}
		                            			</div>
		                                        <div class="form-group">
		                                			{!! Form::label('clientNumb', 'Phone number', ['class' => 'strong ']) !!}
		                            				{!! Form::tel('clientNumb', null, ['class' => 'form-control countryCode numericField clientDetails', 'maxlength' => '16', 'minlength' => '5']) !!}
		                        				</div>
						                    </fieldset>
		                        		</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<fieldset class="padding-15">
						                        <legend>
						                            Service &nbsp;&nbsp;&nbsp;&nbsp;
						                        </legend>
						                        <div class="form-group">
						                        	<div class="row">
						                        		<div class="col-md-4">
						                        			<strong>Select service</strong>
						                        		</div>
						                        		<div class="col-md-3">
						                        			<strong>Time</strong>
						                        		</div>
						                        		<div class="col-md-2">
						                        			<strong>Duration</strong>
						                        		</div>
						                        		<div class="col-md-2">
						                        			<strong>Price</strong>
						                        		</div>
						                        	</div>
						                        	<div class="row m-t-5">
						                        		<div class="col-md-4">
						                        			{!! Form::select('serviceName0', [], null, ['class' => 'form-control serviceName']) !!}
						                        		</div>
						                        		<div class="col-md-3">
						                        			<div class="input-group datetimepicker">
															  	{!! Form::text('serviceTime0', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
															 	<span class="input-group-addon">
															 		<span class="glyphicon glyphicon-time"></span>
															 	</span>
															</div>
						                        		</div>
						                        		<div class="col-md-2">
						                        			{!! Form::select('serviceDur0', ['' => '-- Select --', '5 min' => '5 min', '10 min' => '10 min', '15 min' => '15 min', '20 min' => '20 min', '25 min' => '25 min', '30 min' => '30 min', '35 min' => '35 min', '40 min' => '40 min', '45 min' => '45 min', '50 min' => '50 min', '55 min' => '55 min', '60 min' => '60 min'], null, ['class' => 'form-control serviceDur']) !!}
						                        		</div>
						                        		<div class="col-md-2">
						                        			{!! Form::tel('servicePrice0', null, ['class' => 'form-control numericField servicePrice']) !!}
						                        		</div>
						                        		<div class="col-md-1">
						                        			<a class="btn btn-primary addMoreService" href="#">
																<i class="glyphicon glyphicon-plus"></i>
															</a>
						                        		</div>
						                        	</div>
								                </div>
						                    </fieldset>
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="appointNotes">
									<div class="form-group">
										{!! Form::label('appointNote', 'Booking notes', ['class' => 'strong']) !!}
										{!! Form::textarea('appointNote', null, ['class' => 'form-control textarea']) !!}
						            </div>
								</div>
								<div class="tab-pane fade" id="appointHist">
									<hr class="m-t-0 m-b-10">
									<div class="font-15">
									 	<span class="label label-warning"> Warning</span>
										25 Aug 2016 12:32PM
										- by Gabe Kade
									</div>
									<p class="m-t-10">
									Credit card payment of $80.00 was applied to invoice
									</p>
									<hr class="m-t-0 m-b-10">
									<div class="font-15">
									 	<span class="label label-warning"> Warning</span>
										25 Aug 2016 12:32PM
										 - by Gabe Kade
									</div>
									<p class="m-t-10">
									Credit card payment of $80.00 was applied to invoice
									</p>
									<hr class="m-t-0 m-b-10">
									<div class="font-15">
									 	<span class="label label-success"> New!</span>
										25 Aug 2016 12:32PM
										 - by Gabe Kade
									</div>
									<p class="m-t-10">
									Credit card payment of $80.00 was applied to invoice
									</p>
									
								</div>
							</div>
						</div>
					{!! Form::close() !!}
	        	</div>
	    		<div class="modal-footer clearfix">
	    			<a class="btn btn-red pull-left appointDelete" href="#">
						<i class="glyphicon glyphicon-trash"></i>
						Cancel appointment
					</a>
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            	<button type="button" class="btn btn-primary submit">Save</button>
	            </div>
	    	</div>
	    </div>
	</div>
	<!-- end: Appoinment Modal -->

	<!-- start: Appoinment Cancel Modal -->
	<div class="modal fade" id="appointCancelModal" role="dialog">
	    <div class="modal-dialog">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	                <h4 class="modal-title">Cancel Appointment</h4>
	            </div>
	            <div class="modal-body bg-white">
	            	{!! Form::open(['url' => '', 'role' => 'form']) !!}
		            	<div class="row">
							<div class="col-md-12">
								<h4>The following services will be cancelled:</h4>
								<ul class="list-group" id="services">
								</ul>
								<div class="form-group">
									{!! Form::label('cancelReas', 'Reason for cancelattion', ['class' => 'strong']) !!}
									{!! Form::select('cancelReas', ['' => '-- Select --', 'Did not Specify' => 'Did not Specify', 'Other commitments' => 'Other commitments', 'Not necessary now' => 'Not necessary now', 'Did not show' => 'Did not show', 'Appointment made in error' => 'Appointment made in error', 'Other' => 'Other'], null, ['class' => 'form-control', 'required']) !!}
					            </div>
							</div>
						</div>
					{!! Form::close() !!}
	        	</div>
	    		<div class="modal-footer">
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            	<button type="button" class="btn btn-primary submit">Cancel Appointment</button>
	            </div>
	    	</div>
	    </div>
	</div>
	<!-- end: Appoinment Cancel Modal -->

	<!-- start: Class Modal -->
	<div class="modal fade" id="classModal" role="dialog">
	    <div class="modal-dialog modal-lg">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close m-t--10" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	            </div>
	            <div class="modal-body">
	            	{!! Form::open(['url' => '', 'role' => 'form']) !!}
	            		{!! Form::hidden('staffId') !!}
						{!! Form::hidden('eventId') !!}
		            	<div class="tabbable">
							<ul id="classTabs" class="nav nav-tabs">
								<li class="active">
									<a href="#classDetails" data-toggle="tab">
										<i class="fa fa-calendar"></i> Details
									</a>
								</li>
								<li class="active">
									<a href="#classClients" data-toggle="tab">
										<i class="fa fa-user"></i> Clients
									</a>
								</li>
								<li>
									<a href="#classNotes" data-toggle="tab">
										<i class="fa fa-pencil"></i> Notes
									</a>
								</li>
								<li>
									<a href="#classHist" data-toggle="tab">
										<i class="fa fa-list-alt"></i> History (3)
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade in active" id="classDetails">
									<div class="row">
										<div class="col-md-6">
						                    <fieldset class="padding-15">
						                        <legend>
						                            General &nbsp;&nbsp;&nbsp;&nbsp;
						                        </legend>
						                        <div class="form-group">
		                                            {!! Form::label('staffLocArea', 'Location - Area', ['class' => 'strong']) !!}
		                                            <div class="staffLocAreaDisp"><span></span> {{ HTML::link('#', 'change') }}</div>
		                                            {!! Form::select('staffLocArea', [], null, ['class' => 'form-control']) !!}
		                                        </div>
		                                        <div class="form-group">
		                                            {!! Form::label(null, 'Date', ['class' => 'strong']) !!}
		                                            <div><span class="appointDateDisp"></span> {{ HTML::link('#', 'change', ['class' => 'eventDateChange']) }}</div>
		                                        </div>
						                    </fieldset>
		                        		</div>
		                        		<div class="col-md-6">
						                    <fieldset class="padding-15">
						                        <legend>
						                            Class &nbsp;&nbsp;&nbsp;&nbsp;
						                        </legend>
						                    </fieldset>
		                        		</div>
									</div>
								</div>
								<div class="tab-pane fade" id="classClients">
									<hr class="m-t-0 m-b-10">
									<div class="font-15">
									 	<span class="label label-warning"> Warning</span>
										25 Aug 2016 12:32PM
										- by Gabe Kade
									</div>
									<p class="m-t-10">
									Credit card payment of $80.00 was applied to invoice
									</p>
									<hr class="m-t-0 m-b-10">
									<div class="font-15">
									 	<span class="label label-warning"> Warning</span>
										25 Aug 2016 12:32PM
										 - by Gabe Kade
									</div>
									<p class="m-t-10">
									Credit card payment of $80.00 was applied to invoice
									</p>
									<hr class="m-t-0 m-b-10">
									<div class="font-15">
									 	<span class="label label-success"> New!</span>
										25 Aug 2016 12:32PM
										 - by Gabe Kade
									</div>
									<p class="m-t-10">
									Credit card payment of $80.00 was applied to invoice
									</p>	
								</div>
								<div class="tab-pane fade" id="classNotes">
									<div class="form-group">
										{!! Form::label('appointNote', 'Class notes', ['class' => 'strong']) !!}
										{!! Form::textarea('appointNote', null, ['class' => 'form-control textarea']) !!}
						            </div>
								</div>
								<div class="tab-pane fade" id="classHist">
									<hr class="m-t-0 m-b-10">
									<div class="font-15">
									 	<span class="label label-warning"> Warning</span>
										25 Aug 2016 12:32PM
										- by Gabe Kade
									</div>
									<p class="m-t-10">
									Credit card payment of $80.00 was applied to invoice
									</p>
									<hr class="m-t-0 m-b-10">
									<div class="font-15">
									 	<span class="label label-warning"> Warning</span>
										25 Aug 2016 12:32PM
										 - by Gabe Kade
									</div>
									<p class="m-t-10">
									Credit card payment of $80.00 was applied to invoice
									</p>
									<hr class="m-t-0 m-b-10">
									<div class="font-15">
									 	<span class="label label-success"> New!</span>
										25 Aug 2016 12:32PM
										 - by Gabe Kade
									</div>
									<p class="m-t-10">
									Credit card payment of $80.00 was applied to invoice
									</p>	
								</div>
							</div>
						</div>
					{!! Form::close() !!}
	        	</div>
	    		<div class="modal-footer clearfix">
	    			<a class="btn btn-red pull-left appointDelete" href="#">
						<i class="glyphicon glyphicon-trash"></i>
						Cancel class
					</a>
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            	<button type="button" class="btn btn-primary submit">Save</button>
	            </div>
	    	</div>
	    </div>
	</div>
	<!-- end: Class Modal -->

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
								<fieldset class="padding-15">
			                        <legend>
			                            Credit &nbsp;&nbsp;&nbsp;&nbsp;
			                        </legend>
						         	<div class="form-group">
				                        {!! Form::label('creditAmount', 'Amount *', ['class' => 'strong']) !!}
				                        {!! Form::tel('creditAmount', null, ['class' => 'form-control numericField', 'required']) !!}
				                    </div>
				                    <div class="form-group">
				                        {!! Form::label(null, 'Client *', ['class' => 'strong']) !!}
				                        {!! Form::text(null, null, ['class' => 'form-control clientList', 'autocomplete' => 'off', 'required']) !!}
				                        {!! Form::hidden('clientId') !!}
				                    </div>
				                    <div class="form-group">
										{!! Form::label('creditExpire', 'Expires *', ['class' => 'strong']) !!}
										{!! Form::text('creditExpire', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off', 'readonly']) !!}
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
	    			<a class="font-14 epic-tooltip pull-left" rel="tooltip" data-toggle="tooltip" title="Once the credit has been paid for, raise an invoice and click the credit icon."><i class="fa fa-question-circle" href="#"></i> How to redeem?</a>
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            	<button type="button" class="btn btn-primary submit">Save and pay</button>
	            </div>
	    	</div>
	    </div>
	</div>
	<!-- end: Credit Modal -->

	<!-- start: Working hours Modal -->
	<div class="modal fade" id="workingHrsModal" tabindex="-1" role="dialog" aria-labelledby="Working Hours Modal" aria-hidden="true">
	    <div class="modal-dialog">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	                <h4 class="modal-title">Edit Working Hours</h4>
	            </div>
	            <div class="modal-body">
	            	{!! Form::open(['url' => '', 'role' => 'form']) !!}
	            		{!! Form::hidden('staffId') !!}
		            	<div class="form-group">
		                    {!! Form::label('monday_loc', 'Monday ', ['class' => 'strong']) !!}
		                    <div class="clearfix">
		                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
		                        <div class="row margin-top-5">
		                            <div class="col-xs-9 no-padding">
		                                <div class="col-xs-2">
		                                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
		                                        <input type="checkbox" name="monday" id="monday_loc" value="1" checked class="showHours">
		                                        <label for="monday_loc" class="m-r-0"></label>
		                                    </div>
		                                </div>
		                                <div class="col-xs-10 no-padding notWork">
											Not working on this day
		                                </div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker'>
		                                        <input type='text' name='monday_start0' class="form-control input-sm" value="6:00 AM"/>
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker'>
		                                        <input type='text' name='monday_end0' value="7:00 PM" class="form-control input-sm" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    {!! Form::label('tuesday_loc', 'Tuesday', ['class' => 'strong']) !!}
		                    <div class="clearfix">
		                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
		                        <div class="row margin-top-5">
		                            <div class="col-xs-9 no-padding">
		                                <div class="col-xs-2">
		                                	<div class="checkbox clip-check check-primary m-b-0 m-t-0">
		                                        <input type="checkbox" name="tuesday" id="tuesday_loc" value="1" checked class="showHours">
		                                        <label for="tuesday_loc" class="m-r-0"></label>
		                                    </div>
		                                </div>
		                                <div class="col-xs-10 no-padding notWork">
											Not working on this day
		                                </div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker' id=''>
		                                        <input type='text' name='tuesday_start0' value="6:00 AM" class="form-control input-sm" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker'>
		                                        <input type='text' name='tuesday_end0' value="7:00 PM" class="form-control input-sm" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    {!! Form::label('wednesday_loc', 'Wednesday', ['class' => 'strong']) !!}
		                    <div class="clearfix">
		                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
		                        <div class="row margin-top-5">
		                            <div class="col-xs-9 no-padding">
		                                <div class="col-xs-2">
		                                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
		                                        <input type="checkbox" name="wednesday" id="wednesday_loc" value="1" checked class="showHours">
		                                        <label for="wednesday_loc" class="m-r-0"></label>
		                                    </div>
		                                </div>
		                                <div class="col-xs-10 no-padding notWork">
											Not working on this day
		                                </div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker' id=''>
		                                        <input type='text' name='wednesday_start0' value="6:00 AM" class="form-control input-sm" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker'>
		                                        <input type='text' name='wednesday_end0' value="7:00 PM" class="form-control input-sm" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    {!! Form::label('thursday_loc', 'Thursday', ['class' => 'strong']) !!}
		                    <div class="clearfix">
		                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
		                        <div class="row margin-top-5">
		                            <div class="col-xs-9 no-padding">
		                                <div class="col-xs-2">
		                                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
		                                        <input type="checkbox" name="thursday" id="thursday_loc" value="1" checked class="showHours">
		                                        <label for="thursday_loc" class="m-r-0"></label>
		                                    </div>
		                                </div>
		                                <div class="col-xs-10 no-padding notWork">
											Not working on this day
		                                </div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker' id=''>
		                                        <input type='text' name='thursday_start0' value="6:00 AM" class="form-control input-sm" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker'>
		                                        <input type='text' name='thursday_end0' value="7:00 PM" class="form-control input-sm" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    {!! Form::label('friday_loc', 'Friday', ['class' => 'strong']) !!}
		                    <div class="clearfix">
		                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
		                        <div class="row margin-top-5">
		                            <div class="col-xs-9 no-padding">
		                                <div class="col-xs-2">
		                                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
		                                        <input type="checkbox" name="friday" id="friday_loc" value="1" checked class="showHours">
		                                        <label for="friday_loc" class="m-r-0"></label>
		                                    </div>
		                                </div>
		                                <div class="col-xs-10 no-padding notWork">
											Not working on this day
		                                </div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker' id=''>
		                                        <input type='text' name='friday_start0' value="6:00 AM" class="form-control input-sm" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker'>
		                                        <input type='text' name='friday_end0' value="7:00 PM" class="form-control input-sm" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    {!! Form::label('saturday_loc', 'Saturday', ['class' => 'strong']) !!}
		                    <div class="clearfix">
		                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
		                        <div class="row margin-top-5">
		                            <div class="col-xs-9 no-padding">
		                                <div class="col-xs-2">
		                                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
		                                        <input type="checkbox" name="saturday" id="saturday_loc" value="1" class="showHours">
		                                        <label for="saturday_loc" class="m-r-0"></label>
		                                    </div>
		                                </div>
		                                <div class="col-xs-10 no-padding notWork">
											Not working on this day
		                                </div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker' id=''>
		                                        <input type='text' name='saturday_start0' class="form-control input-sm" value="6:00 AM" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker'>
		                                        <input type='text' name='saturday_end0' class="form-control input-sm" value="7:00 PM" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                <div class="form-group">
		                    {!! Form::label('sunday_loc', 'Sunday', ['class' => 'strong']) !!}
		                    <div class="clearfix">
		                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
		                        <div class="row margin-top-5">
		                            <div class="col-xs-9 no-padding">
		                                <div class="col-xs-2">
		                                    <div class="checkbox clip-check check-primary m-b-0 m-t-0">
		                                        <input type="checkbox" name="sunday" id="sunday_loc" value="1" class="showHours">
		                                        <label for="sunday_loc" class="m-r-0"></label>
		                                    </div>
		                                </div>
		                                <div class="col-xs-10 no-padding notWork">
											Not working on this day
		                                </div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker' id=''>
		                                        <input type='text' name='sunday_start0' class="form-control input-sm" value="6:00 AM" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
		                                <div class="col-xs-4 no-padding showHoursElem">
		                                    <div class='input-group date datetimepicker'>
		                                        <input type='text' name='sunday_end0' class="form-control input-sm" value="7:00 PM" />
		                                        <span class="input-group-addon">
		                                            <span class="glyphicon glyphicon-time"></span>
		                                        </span>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            {!! Form::close() !!}
	        	</div>
	    		<div class="modal-footer">
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            	<button type="button" class="btn btn-primary" data-modal-button-action="submit">Ok</button>
	            </div>
	    	</div>
	    </div>
	</div>
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

	<!-- start: Add Button -->
	<div class="btn-group addBtnGroup">
		<a class="btn btn-primary dropdown-toggle epic-tooltip" data-toggle="dropdown" href="#" rel="tooltip" title="Add events &amp; clients">
			<i class="fa fa-plus"></i>
		</a>
		<ul role="menu" class="dropdown-menu dropdown-light">
			<li>
				<a href="#" class="appointAdd">
					Appointment
				</a>
			</li>
			<li>
				<a href="#">
					Busy Time
				</a>
			</li>
			@if(Session::has('businessId'))
			<li>
				<a href="{{ url('settings/business/'.Session::get('businessId').'/edit#6') }}">
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
		</ul>
	</div>
	<!-- end: Add Button -->

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

	<!-- start: Filter dropdown -->
	{!! Form::select('type', $stff, null, ['class' => 'form-control filterBtnGroup']) !!}
	<!-- end: Filter dropdown -->

	{!! Form::hidden('workingHours') !!}   
	{!! Form::hidden('selectedDatetime') !!}  
	{!! Form::hidden('userId', Auth::user()->id) !!}                       

    <div id='calendar'></div>
@stop

@section('required-script-for-this-page')
	{!! Html::script('assets/js/jquery-ui.min.js?v='.time()) !!}

    {!! Html::script('vendor/moment/moment.min.js?v='.time()) !!}

    <!-- start: jquery validation -->
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}
    <!-- end: jquery validation -->

    <!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Bootstrap timepicker -->
    {!! Html::script('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js?v='.time()) !!}
    <!-- end: Bootstrap timepicker -->

    <!-- start: Country Code Selector -->
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/utils.js?v='.time()) !!}
    {!! Html::script('assets/plugins/intl-tel-input-master/build/js/intlTelInput.js?v='.time()) !!}
    <!-- end: Country Code Selector -->

    <!-- start: Bootstrap Typeahead -->
    {!! Html::script('assets/plugins/bootstrap3-typeahead.min.js?v='.time()) !!}
    <!-- end: Bootstrap Typeahead -->

    <!-- start: Full Calendar -->
    {!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js?v='.time()) !!}
    <!-- end: Full Calendar -->

    {!! Html::script('assets/js/helper.js?v='.time()) !!}

    <!-- start: Full Calendar Custom Script -->
    {!! Html::script('assets/js/calendar.js?v='.time()) !!}
    <!-- end: Full Calendar Custom Script -->
@stop()

@section('script-handler-for-this-page')
@stop()

@section('script-after-page-handler')
@stop()