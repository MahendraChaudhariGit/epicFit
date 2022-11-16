start: Class Modal -->
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
					{!! Form::hidden('eventId') !!}
					{!! Form::hidden('isRepeating') !!}
					{!! Form::hidden('targetEvents') !!}
	            	<div class="tabbable">
						<ul id="classTabs" class="nav nav-tabs">
							<li class="active">
								<a href="#classDetails" data-toggle="tab">
									<i class="fa fa-calendar"></i> Details
								</a>
							</li>
						<li>
								<a href="#classReccur" data-toggle="tab">
									<i class="fa fa-refresh"></i> Recurrence
								</a>
							</li>
							<!--<li>
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
									<i class="fa fa-list-alt"></i> History (<span></span>)
								</a>
							</li>
							<li>
								<a href="#classAttendance" data-toggle="tab">
									<i class="fa fa-list"></i>  Attendance
								</a>
							</li>-->
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="classDetails">
								<div class="row">
									<div class="col-md-6">
					                    <fieldset class="padding-15">
					                        <legend>
					                            General &nbsp;&nbsp;&nbsp;&nbsp;
					                        </legend>
					                        <div class="form-group set-group">
	                                            {!! Form::label('modalLocArea', 'Location - Area', ['class' => 'strong']) !!}
	                                            <div class="set-group-disp"><span></span> <!-- {{ HTML::link('#', 'change') }} --></div>
	                                            {!! Form::select('modalLocArea', $modalLocsAreas, null, ['class' => 'form-control loc-area-dd onchange-set-neutral']) !!}
	                                        </div>
	                                        <div class="form-group set-group">
	                                            {!! Form::label('staff', 'Staff', ['class' => 'strong']) !!}
	                                            <div class="set-group-disp"><span></span> {{ HTML::link('#', 'change') }}</div>
	                                            {!! Form::select('staff', [], null, ['class' => 'form-control']) !!}
	                                        </div>
	                                        <div class="form-group"><!--classTimeGroup-->
	                                            {!! Form::label(null, 'Date *', ['class' => 'strong']) !!}
	                                            <div class="clearfix moveErrMsg">
	                                            	<div class="pull-left">
	                                            		<span class="eventDateDisp"></span> 
	                                            		at 
	                                            	</div>
	                                            	<div class="input-group datetimepicker eventTime"><!--classTime-->
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
	                                            {!! Form::label(null, 'Clients', ['class' => 'strong']) !!}
												<p><a href="#" id="show-clients-tab"><span class="linkedclients-text"></span> >></a></p>
												<div class="progress progress-striped progress-sm">
													<div class="progress-bar progress-bar-success" role="progressbar">
													</div>
												</div>
	                                        </div>
					                    </fieldset>
	                        		</div>
	                        		<div class="col-md-6">
					                    <fieldset class="padding-15">
					                        <legend>
					                            Class &nbsp;&nbsp;&nbsp;&nbsp;
					                        </legend>
					                        <div class="form-group">
	                                            {!! Form::label('staffClass', 'Class *', ['class' => 'strong']) !!}
	                                            {!! Form::select('staffClass', [], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
	                                        </div>
	                                        <div class="form-group">
	                                            {!! Form::label('classDur', 'Duration *', ['class' => 'strong']) !!}
	                                            {!! Form::select('classDur', ['' => '-- Select --', '5' => '5 min', '10' => '10 min', '15' => '15 min', '20' => '20 min', '25' => '25 min', '30' => '30 min', '35' => '35 min', '40' => '40 min', '45' => '45 min', '50' => '50 min', '55' => '55 min', '60' => '60 min'], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
	                                        </div>
	                                        <div class="form-group">
				                                {!! Form::label('classCap', 'Capacity *', ['class' => 'strong']) !!}
				                                {!! Form::number('classCap', null, ['class' => 'form-control numericField', 'required']) !!}
				                            </div>
				                            <div class="form-group">
                            					{!! Form::label('classPrice', 'Price *', ['class' => 'strong']) !!}
                                				{!! Form::text('classPrice', null, ['class' => 'form-control price-field', 'required']) !!}
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
				                    </div>

				                    <div class="eventRepeatFields">
				                    	<div class="form-group">
					                        {!! Form::label('eventRepeatInterval', 'Repeat every *', ['class' => 'strong']) !!}
					                        <div>
					                        	{!! Form::select('eventRepeatInterval', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-94p onchange-set-neutral', 'required']) !!} 
					                        	<span class="eventRepeatIntervalUnit">days</span>
					                        </div>
					                    </div>

					                    <div class="form-group">
					                    	{!! Form::label(null, 'Ends *', ['class' => 'strong']) !!}
						                    <div class="moveErrMsg no-error-labels">
							                    <div class="radio clip-radio radio-primary">
							                        <input type="radio" name="eventRepeatEnd" id="classEventRepeatEndAfter" value="After">
							                        <label for="classEventRepeatEndAfter">
							                            After
							                        </label>
							                        {!! Form::select('eventRepeatEndAfterOccur', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-120 onchange-set-neutral']) !!}
							                        occurrences
							                    </div>
							                    <div class="radio clip-radio radio-primary">
							                        <input type="radio" name="eventRepeatEnd" id="classEventRepeatEndOn" value="On">
							                        <label for="classEventRepeatEndOn">
							                            On
							                        </label>
							                        {!! Form::text('eventRepeatEndOnDate', null, ['class' => 'form-control mw-120 inlineBlckDisp eventDatepicker onchange-set-neutral', 'autocomplete' => 'off']) !!}
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
						                		<div class="checkbox clip-check check-primary checkbox-inline m-b-0">
													<input id="classEventRepeatWeekdays0" value="Mon" type="checkbox">
													<label for="classEventRepeatWeekdays0"> Mon </label>
												</div>

						                    	<div class="checkbox clip-check check-primary checkbox-inline m-b-0">
													<input id="classEventRepeatWeekdays1" value="Tue" type="checkbox">
													<label for="classEventRepeatWeekdays1"> Tue </label>
												</div>

												<div class="checkbox clip-check check-primary checkbox-inline m-b-0">
													<input id="classEventRepeatWeekdays2" value="Wed" type="checkbox">
													<label for="classEventRepeatWeekdays2"> Wed </label>
												</div>

												<div class="checkbox clip-check check-primary checkbox-inline m-b-0">
													<input id="classEventRepeatWeekdays3" value="Thu" type="checkbox">
													<label for="classEventRepeatWeekdays3"> Thu </label>
												</div>

												<div class="checkbox clip-check check-primary checkbox-inline m-b-0">
													<input id="classEventRepeatWeekdays4" value="Fri" type="checkbox">
													<label for="classEventRepeatWeekdays4"> Fri </label>
												</div>

												<div class="checkbox clip-check check-primary checkbox-inline m-b-0">
													<input id="classEventRepeatWeekdays5" value="Sat" type="checkbox">
													<label for="classEventRepeatWeekdays5"> Sat </label>
												</div>

												<div class="checkbox clip-check check-primary checkbox-inline m-b-0">
													<input id="classEventRepeatWeekdays6" value="Sun" type="checkbox">
													<label for="classEventRepeatWeekdays6"> Sun </label>
												</div>
						                    </div>
						                    <span class="help-block m-t-0"></span>
						                </div>
				                    </div>     
								</fieldset>
							</div>
							<div class="tab-pane fade" id="classClients">
								<div class="row">
									<div class="col-md-4 m-t-20">
										<h5 class="clearfix">
											<div class="pull-left m-t-10 linkedclients-text"></div>
											<a class="btn btn-primary pull-right" href="#" id="resetClientlinkForm">
												<i class="glyphicon glyphicon-plus"></i>
											</a>
										</h5>
				                        <div class="list-group" id="linkedclientList">
										</div>
	                        		</div>
	                        		<div class="col-md-8">
					                    <fieldset class="padding-15 client-form">
					                        <legend>
					                            Client Details &nbsp;&nbsp;&nbsp;&nbsp;
					                        </legend>
					                        <div class="alert alert-danger hidden new-client-req-msg">
							                    At least one field is required out of Email address and Phone number.
							                </div>
							                {!! Form::hidden('isExistingClient') !!}
					                        <div class="form-group">
					                        	{!! Form::label('clientName', 'Full Name *', ['class' => 'strong']) !!}
					                        	@if( !isset($subview))
					                        		{!! Form::text('clientName', null, ['class' => 'form-control']) !!}
					                        	@endif
					                        	{!! Form::text(null, null, ['class' => 'form-control clientList', 'autocomplete' => 'off']) !!}
				                        		{!! Form::hidden('clientId') !!}
				                        		@if(!isset($subview))
					                        		<div class="checkbox clip-check check-primary m-b-0 m-t-5">
		                                            	{!! Form::checkbox('isNewClient', '1', null, ['id' => 'classIsNewClient']) !!}
				                                        <label for="classIsNewClient" class="no-error-label">
				                                            <strong>New client?</strong>
				                                        </label>
				                                    </div>
			                                    @endif
	                                        </div>
	                                        <div class="form-group">
	                                			{!! Form::label('clientEmail', 'Email address *', ['class' => 'strong']) !!}
	                            				{!! Form::email('clientEmail', null, ['class' => 'form-control clientDetails']) !!}
	                            			</div>
	                                        <div class="form-group">
	                                			{!! Form::label('clientNumb', 'Phone number *', ['class' => 'strong ']) !!}
	                            				{!! Form::tel('clientNumb', null, ['class' => 'form-control countryCode numericField clientDetails', 'maxlength' => '16', 'minlength' => '5']) !!}
	                        				</div>
	                        				<div class="form-group">
												{!! Form::label('clientNote', 'Notes', ['class' => 'strong']) !!}
												{!! Form::textarea('clientNote', null, ['class' => 'form-control textarea']) !!}
								            </div>
								            <div class="form-group">
												<div class="checkbox clip-check check-primary m-b-0">
													{!! Form::checkbox('isReducedRate', '1', null, ['id' => 'isReducedRate', 'class' => 'disableable']) !!}
													<label for="isReducedRate">
														<strong>Reduced rate session?</strong>
													</label>
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
								            <a class="btn btn-success pull-left" href="#" id="linkClientClass">
												<i class="fa fa-plus"></i>
												Add to class
											</a>
											<a class="btn btn-success pull-left m-r-10" href="#" id="confirmClient">
												<i class="fa fa-check-square-o"></i>
												Confirm Client
											</a>
											<a class="btn btn-red pull-left" href="#" id="unlinkClientClass">
												<i class="glyphicon glyphicon-trash"></i>
												Remove from class
											</a>
								            <!--<a class="btn btn-red pull-left m-r-10" href="#">
												<i class="glyphicon glyphicon-trash"></i>
												Remove from class
											</a>
											<a class="btn btn-success pull-left m-r-10" href="#">
												<i class="fa fa-plus"></i>
												Add to class
											</a>
											<a class="btn btn-red pull-left" href="#">
												<i class="glyphicon glyphicon-trash"></i>
												Clear
											</a>-->
					                    </fieldset>
	                        		</div>
								</div>	
							</div>
							<div class="tab-pane fade" id="classNotes">
								<div class="form-group">
									{!! Form::label('classNote', 'Class notes', ['class' => 'strong']) !!}
									{!! Form::textarea('classNote', null, ['class' => 'form-control textarea']) !!}
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
            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            	<button type="button" class="btn btn-primary submit">Save</button>
            </div>
    	</div>
    </div>
</div>
<!-- end: Class Modal