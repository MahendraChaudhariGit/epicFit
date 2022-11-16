<!-- start: Class Modal -->
<style type="text/css">
	/*#classModal .form-control{
		border: 1px solid #c5c5c5 !important;
        padding-top: 0px;
	}*/
	#classModal .prog-row{
		display: flex;
	}
	#classModal .prog-col{
    flex-basis: 0;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
    width: initial !important;
	}
</style>
<div class="modal fade" id="classModal" role="dialog">
    <div class="modal-dialog modal-lg">
    	<div class="modal-content">
            <div class="modal-header">
            	<div class="col-md-12">
	              {{--   <button type="button" class="close m-t--10 classCloseCls" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
	            </div>
	            <div class="col-md-12">
	            	<button type="button" class="close m-t--10 classCloseCls" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            	<span style="font-size: 3em;">
	            		<strong class="class-modal-headline">BOOKING</strong> MODALs
	            	</span>
	            </div>
            </div>
            <div class="subviewPar">
	            <div id="clientSubview" class="subview">
					<iframe></iframe><!--id="iframe"-->
				</div>
	            <div class="modal-body">
	            	@php
	            	$ifBussHasClasses = session()->has('ifBussHasClasses') && session()->get('ifBussHasClasses') == true?"true":"false";
	            	$ifBussHasServices = session()->has('ifBussHasServices') && session()->get('ifBussHasServices') == true?"true":"false";
	            	@endphp
	            	{!! Form::open(['url' => '', 'role' => 'form','id'=>'class-form']) !!}
						{!! Form::hidden('eventId') !!}
						{!! Form::hidden('isRepeating') !!}
						{!! Form::hidden('targetEvents') !!}
						{!! Form::hidden('ifBussHasClasses',$ifBussHasClasses, ['class' => 'no-clear']) !!}
						{!! Form::hidden('ifBussHasServices',$ifBussHasServices, ['class' => 'no-clear']) !!}
		            	<div class="tabbable">
							<ul id="classTabs" class="nav nav-tabs">
								<li class="active">
									<a href="#classDetails" data-toggle="tab">
										<i class="fa fa-calendar"></i> Details
									</a>
								</li>
								{{-- <li>
									<a href="#classReccur" data-toggle="tab">
										<i class="fa fa-refresh"></i> Recurrence
									</a>
								</li> --}}
								<li>
									<a href="#classClients" data-toggle="tab">
										<i class="fa fa-user"></i> Clients
									</a>
								</li>
								<li>
									<a href="#classReccur" data-toggle="tab">
										<i class="fa fa-refresh"></i> Recurrence
									</a>
								</li>
								<li>
									<a href="#classNotes" data-toggle="tab">
										<i class="fa fa-pencil"></i> Notes
									</a>
								</li>
								<li>
									<a href="#classHist" data-toggle="tab">
										<i class="fa fa-list-alt"></i> History (<span></span>)
									</a>
								</li>
								<li>
									<a href="#classAttendance" data-toggle="tab">
										<i class="fa fa-list"></i>  Attendance
									</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade in active" id="classDetails">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group m-b-0">
	                                            {!! Form::label(null, 'Booking Type', ['class' => 'strong']) !!}
	                                            <a class="font-14" data-content="Switching booking type will reset the filled information." data-placement="right" data-toggle="popover" data-trigger="hover">
								    				<i class="fa fa-info-circle" href="#"></i>
								    			</a>
	                                            <ul class="clearfix p-l-0" id="bookTypeSelectable">
						                            <li class="col-xs-6 ui-widget-content ui-selected" data-val="class">Class</li>
						                            <li class="col-xs-6 ui-widget-content" data-val="service">Service</li>
							                    </ul>
							                    {!! Form::hidden('bookType', 'class', ['class' => 'no-clear']) !!}
	                                        </div>
										</div>
									</div>
									<div class="row">
		                        		<div class="col-md-6">
						                    <fieldset class="padding-15" id="classFieldset">
						                        <legend>
						                            Class &nbsp;&nbsp;&nbsp;&nbsp;
						                        </legend>
						                        <div class="form-group delMsgPar"><!--moveErrMsg-->
		                                            {!! Form::label('staffClass', 'Class *', ['class' => 'strong']) !!}
		                                            <a class="font-14" data-content="Changing class might change the filled information." data-placement="right" data-toggle="popover" data-trigger="hover">
									    				<i class="fa fa-info-circle" href="#"></i>
									    			</a>
		                                            {!! Form::select('staffClass', [], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
		                                            <div class="line"></div>
		                                        </div>
		                                        <div class="form-group">
		                                            {!! Form::label('classDur', 'Duration *', ['class' => 'strong']) !!}
		                                            <a class="font-14" data-content="Changing duration might change the filled information." data-placement="right" data-toggle="popover" data-trigger="hover">
									    				<i class="fa fa-info-circle" href="#"></i>
									    			</a>
		                                            {!! Form::select('classDur', ['' => '-- Select --', '5' => '5 min', '10' => '10 min', '15' => '15 min', '20' => '20 min', '25' => '25 min', '30' => '30 min', '35' => '35 min', '40' => '40 min', '45' => '45 min', '50' => '50 min', '55' => '55 min', '60' => '01 hour 00 min','65'=>'01 hour 05 min','70'=>'01 hour 10 min','75'=>'01 hour 15 min','80'=>'01 hour 20 min','85'=>'01 hour 25 min','90'=>'01 hour 30 min','95'=>'01 hour 35 min','100'=>'01 hour 40 min','105'=>'01 hour 45 min','110'=>'01 hour 50 min','115'=>'01 hour 55 min','120'=>'02 hour 00 min'], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
		                                            <div class="line"></div>
		                                        </div>
		                                        <div class="form-group">
					                                {!! Form::label('classCap', 'Capacity *', ['class' => 'strong']) !!}
					                                {!! Form::number('classCap', null, ['class' => 'form-control numericField', 'required', 'min' => 1]) !!}
					                                <div class="line"></div>
					                            </div>
					                            <div class="form-group">
	                            					{!! Form::label('classPrice', 'Price *', ['class' => 'strong']) !!}
	                                				{!! Form::text('classPrice', null, ['class' => 'form-control price-field', 'required']) !!}
	                                				<div class="line"></div>
	                        					</div>
						                    </fieldset>
						                    <fieldset class="padding-15" id="serviceFieldset">
						                        <legend>
						                            Service &nbsp;&nbsp;&nbsp;&nbsp;
						                        </legend>
						                        <div class="form-group delMsgPar">
		                                            {!! Form::label('staffservice', 'Service *', ['class' => 'strong']) !!}
		                                            <a class="font-14" data-content="Changing service might change the filled information." data-placement="right" data-toggle="popover" data-trigger="hover">
									    				<i class="fa fa-info-circle" href="#"></i>
									    			</a>
		                                            {!! Form::select('staffservice', [], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
		                                            <div class="line"></div>
		                                        </div>
		                                        <div class="form-group">
		                                            {!! Form::label('serviceDur', 'Duration *', ['class' => 'strong']) !!}
		                                            <a class="font-14" data-content="Changing duration might change the filled information." data-placement="right" data-toggle="popover" data-trigger="hover">
									    				<i class="fa fa-info-circle" href="#"></i>
									    			</a>
		                                            {!! Form::select('serviceDur', ['' => '-- Select --', '5' => '5 min', '10' => '10 min', '15' => '15 min', '20' => '20 min', '25' => '25 min', '30' => '30 min', '35' => '35 min', '40' => '40 min', '45' => '45 min', '50' => '50 min', '55' => '55 min', '60' => '01 hour 00 min','65'=>'01 hour 05 min','70'=>'01 hour 10 min','75'=>'01 hour 15 min','80'=>'01 hour 20 min','85'=>'01 hour 25 min','90'=>'01 hour 30 min','95'=>'01 hour 35 min','100'=>'01 hour 40 min','105'=>'01 hour 45 min','110'=>'01 hour 50 min','115'=>'01 hour 55 min','120'=>'02 hour 00 min'], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
		                                            <div class="line"></div>
		                                        </div>
					                            <div class="form-group">
	                            					{!! Form::label('servicePrice', 'Price *', ['class' => 'strong']) !!}
	                                				{!! Form::text('servicePrice', null, ['class' => 'form-control price-field', 'required']) !!}
	                                				<div class="line"></div>
	                        					</div>
						                        <div class="form-group">
						                        	{!! Form::label(null, 'Booking status *', ['class' => 'strong']) !!}
		                                            <ul class="clearfix p-l-0" id="appointStatusSelectable"><!--appoint_status_selectable-->
		                                                <li class="col-xs-6 ui-widget-content" data-val="Pencilled-In">Pencilled-In</li>
		                                                <li class="col-xs-6 ui-widget-content ui-selected" data-val="Confirmed">Confirmed</li>
		                                                {!! Form::hidden('appointStatusOpt', 'Confirmed') !!}
		                                            </ul>                                        
		                                        </div>
		                                        <div class="form-group" id="appointStatusPending"><!--appoint_status_pending-->
		                                            <div class="checkbox clip-check check-primary m-b-0 moveErrMsg">
		                                                {!! Form::checkbox('ifAutoExpireAppoint', '1', null, ['id' => 'ifAutoExpireAppoint', 'class' => 'onchange-set-neutral']) !!}
		                                                <label for="ifAutoExpireAppoint" class="m-r-0 no-error-label">
		                                                    <strong>Auto-expire at set time</strong>
		                                                </label>
		                                                <span class="autoExpireAppoint">
		                                                    {!! Form::select('autoExpireAppointDur', ['' => '-- Select --', 'Custom' => 'Custom', '2' => '2 hours', '3' => '3 hours', '6' => '6 hours', '12' => '12 Hours', '24' => '24 hours', '48' => '48 Hours', '72' => '72 Hours', '168' => '1 week'], null, ['class' => 'mw-100 onchange-set-neutral']) !!}
		                                                    <strong>before booking time</strong>
		                                                </span>
		                                                <div class="autoExpireAppointDurCustom clearfix m-t-10">
		                                                    {!! Form::text('autoExpireAppointDurDate', null, ['class' => 'form-control mw-47p pull-left eventDatepicker', 'autocomplete' => 'off', 'readonly']) !!}
		                                                    <div class="input-group bootstrap-timepicker timepicker mw-50p pull-left m-l-10"><!-- datetimepicker-->
		                                                        {!! Form::text('autoExpireAppointDurTime', null, ['class' => 'form-control timepicker1', 'autocomplete' => 'off']) !!}
		                                                        <span class="input-group-addon">
		                                                            <span class="glyphicon glyphicon-time"></span>
		                                                        </span>
		                                                    </div>
		                                                </div>
		                                            </div>
		                                            <span class="help-block placeErrMsg"></span>
		                                        </div>
						                    </fieldset>
						                    <!-- start: Resource fieldset -->
									          @include('includes.partials.service_resource', ['res' => [], 'newres' => []])
									       <!-- end: Resource fieldset -->
		                        		</div>
		                        		<div class="col-md-6">
						                    <fieldset class="padding-15">
						                        <legend>
						                            General &nbsp;&nbsp;&nbsp;&nbsp;
						                        </legend>
						                        <div class="form-group"><!--classTimeGroup-->
		                                            {!! Form::label(null, 'Date *', ['class' => 'strong']) !!}
		                                            <a class="font-14" data-content="Changing date/time might change the filled information." data-placement="right" data-toggle="popover" data-trigger="hover">
									    				<i class="fa fa-info-circle" href="#"></i>
									    			</a>
		                                            <div class="clearfix moveErrMsg">
		                                            	<div class="pull-left">
		                                            		<span class="eventDateDisp"></span> 
		                                            		at 
		                                            	</div>
		                                            	<div class="bootstrap-timepicker timepicker eventTime"><!--classTime datetimepicker-->
														  	{!! Form::hidden('eventTime', null, ['class' => 'form-control timepicker1', 'autocomplete' => 'off', 'required']) !!}
														  	<span class="event-time-span">09:00 AM</span>
														  	<a href="javascript:void(0)" class="fa fa-clock-o fa-2x m-l-10 m-r-5 new-timepicker-btn event-date-timepicker"></a>
														  	<span class="fa fa-clock-o fa-2x hidden event-date-clone"></span>
														</div>
														{{ HTML::link('#', 'Change', ['class' => 'eventDateChange pull-left event-class-date-change']) }}
		                                            </div>
		                                            <span class="help-block placeErrMsg"></span>
		                                        </div>
		                                        <div class="form-group set-group primary-form-group"><!--m-b-5 space-if-error-->
		                                            {!! Form::label('staff', 'Staff', ['class' => 'strong']) !!}
		                                            <a class="font-14" data-content="Changing staff might change the filled information." data-placement="right" data-toggle="popover" data-trigger="hover" id="staffTooltip">
									    				<i class="fa fa-info-circle" href="#"></i>
									    			</a>
		                                            <div class="set-group-disp"><span></span> {{ HTML::link('#', 'change') }}</div>
		                                            {!! Form::select('staff', [], null, ['class' => 'form-control']) !!}
		                                            <!-- <span class="help-block"></span> -->
		                                            <span class="help-block staffErrMsg"></span>
		                                        </div>
		                                        <div class="form-group form-inline sibling-form-group">
		                                        	<div class="checkbox clip-check check-primary">
		                                            	{!! Form::checkbox('ifMarkFav', '1', null, ['id' => 'ifMarkFav', 'class' => 'onchange-set-neutral']) !!}
								                        <label for="ifMarkFav" class="m-r-0 no-error-label">
								                            <strong>Associate areas with staff till</strong> 
								                        </label>
								                        {!! Form::hidden('markFavTill', null, ['class' => 'form-control eventDatepicker onchange-set-neutral w155', 'autocomplete' => 'off', 'readonly']) !!}
								                        <span class="m-l-5 event-date-span hidden"></span>
								                        <a href="javascript:void(0)" class="m-l-10 new-eventDatepicker"><i class="fa fa-calendar fa-2x"></i></a>
								                        <span class="help-block m-y-0"></span>
								                    </div>
		                                        </div>
						                        <div class="form-group set-group"><!--set-group-->
		                                            {!! Form::label('modalLocArea', 'Location - Area', ['class' => 'strong']) !!}
		                                            <div class="set-group-disp"><span></span> {{ HTML::link('#', 'change') }}</div>
		                                            {!! Form::select('modalLocArea', [], null, ['class' => 'form-control onchange-set-neutral temp', 'multiple', 'required']) !!} <!--, $modalLocsAreas loc-area-dd -->
		                                            <!-- <span class="help-block"></span> -->
		                                            <span class="help-block areaErrMsg" style="display: none;"></span>
		                                        </div>
		                                        <div class="form-group">
		                                            {!! Form::label(null, 'Clients', ['class' => 'strong']) !!}
													<p>
														<a href="#" class="show-clients-tab"></a>
													</p>
													
													<!--class="linkedclients-text"-->
													<!-- <div class="progress progress-striped progress-sm">
														<div class="progress-bar progress-bar-success" role="progressbar">
														</div>
													</div> -->
													<div class="row">
														<div class="col-md-12" id="show-client-prog">
															<div class="prog-row"> 
																
															</div>
														</div>
													</div>
		                                        </div>
						                    </fieldset>
		                        		</div>
									</div>
								</div>
								<div class="tab-pane fade" id="classReccur">
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
								                        <input type="radio" name="eventRepeatEnd" id="classEventRepeatEndAfter" value="After">
								                        <label for="classEventRepeatEndAfter">
								                            After
								                        </label>
								                        {!! Form::select('eventRepeatEndAfterOccur', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-120 onchange-set-neutral']) !!}
								                        occurrences
								                        <div class="line"></div>
								                    </div>
								                    <div class="radio clip-radio radio-primary">
								                        <input type="radio" name="eventRepeatEnd" id="classEventRepeatEndOn" value="On">
								                        <label for="classEventRepeatEndOn">
								                            On
								                        </label>
								                        <!-- {!! Form::text('eventRepeatEndOnDate', null, ['class' => 'form-control mw-120 inlineBlckDisp eventDatepicker onchange-set-neutral', 'autocomplete' => 'off']) !!} -->
								                        <input type="text" name="eventRepeatEndOnDate" class="form-control event-date-btn mw-120 inlineBlckDisp onchange-set-neutral" value="">
								                        <div class="line"></div>
								                    </div>
								                    <div class="radio clip-radio radio-primary m-b-0">
								                        <input type="radio" name="eventRepeatEnd" id="classEventRepeatEndNever" value="Never">
								                        <label for="classEventRepeatEndNever">
								                            Never
								                        </label>
								                    </div>
								                </div>
							                	<span class="help-block placeErrMsg m-t-0"></span>
							                	<div class="eventRepeatWeekdays no-error-labels">
							                		

							                    	
							                    </div>
							                    <span class="help-block m-t-0"></span>
							                </div>
					                    </div>     
									</fieldset>
								</div>
								<div class="tab-pane fade" id="classClients">
									<div class="row">
									  <div class="alertMsg"></div>
										<div class="col-md-4 m-t-20">
											<h5 class="clearfix">
												<div class="pull-left m-t-10 show-clients-tab"></div><!--linkedclients-text-->
												<a class="btn btn-primary pull-right" href="#" id="resetClientlinkForm">
													<i class="glyphicon glyphicon-plus"></i>
												</a>
											</h5>
					                        <div class="list-group" id="linkedclientList">
											</div>
											<div>
												<h5 class="clearfix">
											<div class="pull-left m-t-10 waiting-clients-tab"></div>
										</h5>

										<div class="list-group" id="waitingLinkedclientList">
										</div>
											</div>
										
										</div>
										
		                        		<div class="col-md-8">
						                    <fieldset class="padding-15 client-form">
						                        <legend>
						                            Client Details &nbsp;&nbsp;&nbsp;&nbsp;
						                        </legend>
						                        <!--<div class="alert alert-danger hidden new-client-req-msg">
								                    At least one field is required out of Email address and Phone number.
								                </div>-->
								                {!! Form::hidden('isExistingClient') !!}
								                {!! Form::hidden('accStatus') !!}
												{!! Form::hidden('forceAdd') !!}
												<input type="hidden" name="isLdc" value="0" />
						                        <div class="form-group clearfix">
						                        	{!! Form::label('clientName', 'Full Name *', ['class' => 'strong']) !!}
						                        	<a href="#" class="pull-right callClientSubview" data-target-subview="client" data-subview="clientSubview">+ Add New Client</a>
						                        	<!--@if(Auth::user()->hasPermission(Auth::user(), 'create-client') && !isset($subview))
						                        		{!! Form::text('clientName', null, ['class' => 'form-control']) !!}
						                        	@endif-->
						                        	{!! Form::text('clientName', null, ['class' => 'form-control clientList noneditable', 'autocomplete' => 'off']) !!}
						                        	<div class="line"></div>
					                        		{!! Form::hidden('clientId') !!}
					                        		<!--@if(Auth::user()->hasPermission(Auth::user(), 'create-client') && !isset($subview))
						                        		<div class="checkbox clip-check check-primary m-b-0 m-t-5">
			                                            	{!! Form::checkbox('isNewClient', '1', null, ['id' => 'classIsNewClient']) !!}
					                                        <label for="classIsNewClient" class="no-error-label">
					                                            <strong>New client?</strong>
					                                        </label>
					                                    </div>
				                                    @endif-->
		                                        </div>
		                                        <div class="form-group">
		                                			{!! Form::label('clientEmail', 'Email address *', ['class' => 'strong']) !!}
		                            				{!! Form::email('clientEmail', null, ['class' => 'form-control', 'readonly']) !!} <!--clientDetails-->
		                            				<div class="line"></div>
		                            			</div>
		                                        <div class="form-group">
		                                			{!! Form::label('clientNumb', 'Phone number *', ['class' => 'strong ']) !!}
		                            				{!! Form::tel('clientNumb', null, ['class' => 'form-control countryCode', 'readonly']) !!}<!--clientDetails  numericField   , 'maxlength' => '16', 'minlength' => '5'-->
		                            				<div class="line"></div>
		                        				</div>
		                        				<div class="form-group">
													{!! Form::label('clientNote', 'Notes', ['class' => 'strong']) !!}
													{!! Form::textarea('clientNote', null, ['class' => 'form-control textarea noneditable']) !!}
													<div class="line"></div>
									            </div>
									            <div id="classClientsBtns">
										            <div class="form-group">
														<div class="checkbox clip-check check-primary m-b-0">
															{!! Form::checkbox('isReducedRate', '1', null, ['id' => 'isReducedRate', 'class' => 'disableable onchange-set-neutral']) !!}
															<label for="isReducedRate" class="m-r-0">
																<strong>Reduced rate session?</strong>
															</label>
															{!! Form::text('reducedRate', null, ['class' => 'form-control price-field disableable mw-80 inlineBlckDisp']) !!}
															<div class="line"></div>
														</div>
													</div>
													<div class="form-group">
														<div class="checkbox clip-check check-primary m-b-0">
															{!! Form::checkbox('ifRecur', '1', null, ['id' => 'ifRecur', 'class' => 'disableable']) !!}
															<label for="ifRecur">
																<strong>Recur client if event recurs?</strong>
															</label>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="checkbox clip-check check-primary m-b-0">
														{!! Form::checkbox('isCreatingMakeUpSession', '1', null, ['id' => 'isCreatingMakeUpSession', 'class' => 'disableable']) !!}
														<label for="isCreatingMakeUpSession">
															{{-- <strong>Use EPIC Credit?</strong> --}}
															<strong>Use EPIC Credit / Invoice?</strong>
														</label>
													</div>
												</div>
									            <a class="btn btn-success pull-left" href="#" id="linkClientClass">
													<i class="fa fa-plus"></i>
													Add to <span class="bookingTypeName">class</span>
												</a>
												<!-- <a class="btn btn-success pull-left m-r-10" href="#" id="confirmClient">
													<i class="fa fa-check-square-o"></i>
													Confirm Client
												</a> -->
												<a class="btn btn-red pull-left" href="#" id="unlinkClientClass">
													<i class="glyphicon glyphicon-trash"></i>
													Remove from <span class="bookingTypeName">class</span>
												</a>
						                    </fieldset>
		                        		</div>
									</div>
									 <span class="help-block m-t-0"></span>	
								</div>
								<div class="tab-pane fade" id="classNotes">
									<div class="form-group">
										<label for="classNote" class="strong"><span class="bookingTypeName capitalize">Class</span> notes</label>
										{!! Form::textarea('classNote', null, ['class' => 'form-control textarea']) !!}
										<div class="line"></div>
						            </div>
								</div>
								<div class="tab-pane fade event-history" id="classHist">
								</div>
								<div class="tab-pane fade" id="classAttendance">
									<p>
										Set the attendance status of clients individually below or 
									 	<a href="#">mark all as attended</a>.
									</p>
									<hr class="m-t-0">
									<div id="classAttendanceList">
									</div>
									<div class="row m-t-2">
										<div class="col-md-6">
											<label><span class="waitingClient"></span></label>
											{{-- <input type="text" name="" class="form-control" placeholder="Linda battle"> --}}
											<div id="waitingClassAttendanceList">
											</div>
										</div>
									</div>
									
									
								</div>
							</div>
						</div>
					{!! Form::close() !!}
	        	</div>
	    		<div class="modal-footer clearfix">
	    			<a class="btn btn-red pull-left delete-prompt" href="#">
						<i class="glyphicon glyphicon-trash"></i>
						Cancel class
					</a>
					<a class="btn btn-primary pull-left marge-cls" href="#">
						<i class="glyphicon glyphicon-compressed"></i>
						Merge class
					</a>
					<a class="btn btn-red pull-left" href="#" id="delService"><!-- data-dismiss="modal"  data-toggle="modal" data-target="#appointCancelModal" -->
	                    <i class="glyphicon glyphicon-trash"></i>
	                    Cancel service
	                </a>
	            	<button type="button" class="btn btn-default" data-dismiss="modal"><span>Cancel</span></button>
	            	<button class="btn btn-default btn-o" id="nextTab"> 
						Next <i class="fa fa-arrow-circle-right"></i> 
					</button>
	            	<button type="button" class="btn btn-primary submitBooking">Save</button>
	            </div>
            </div>
    	</div>
    </div>
</div>
<!-- end: Class Modal -->

<!-- start: Make-up notes Modal -->
@include('includes/partials/makeup_modal')
 <!-- <div class="modal fade" id="makeUpNotesModal" role="dialog">
	<div class="modal-dialog">   
      	<div class="modal-content">
        	<div class="modal-header">
          		<button type="button" class="close" data-dismiss="modal">&times;</button>
          		<h4 class="modal-title">Make up notes</h4>
        	</div>
        	<div class="modal-body bg-white">
        		{!! Form::hidden('clientId') !!}
        		{!! Form::hidden('callback') !!}
            	<div class="form-group">
					{!! Form::textarea('makeupNotes', null, ['class' => 'form-control textarea']) !!}
            	</div>
        	</div>
        	<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            	<button class="btn btn-primary submit">
              		Create Make up
            	</button>
        	</div>  
		</div>
	</div>      
</div>  -->
<!-- end: Make-up notes Modal -->
