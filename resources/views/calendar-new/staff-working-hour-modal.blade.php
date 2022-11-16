<div class="modal fade" id="workingHrsModal" tabindex="-1" role="dialog" aria-labelledby="Working Hours Modal" aria-hidden="true">
	    <div class="modal-dialog">
	    	<div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	                </button>
	                <h4 class="modal-title">Edit Working Hours</h4>
	            </div>
	            <div class="modal-body bg-white">
	            	{!! Form::open(['url' => '', 'role' => 'form']) !!}
	            		{!! Form::hidden('staffId') !!}
	            		<div class="row">
		            		<div class="col-xs-6">
			            		<div class="form-group">
			            			{!! Form::label('start_attend_date', 'Date ', ['class' => 'strong']) !!}
			            			{!! Form::text('start_attend_date', null, ['class' => 'form-control attendenceDatepicker onchange-set-neutral', 'required'=>'required', 'autocomplete' => 'off', 'readonly']) !!}
			            		</div>
		            		</div>
		            		<div class="col-xs-6">
			            		<div class="form-group">
			            			{!! Form::label('upto_attend_date', 'Up to ', ['class' => 'strong']) !!}
			            			{!! Form::text('upto_attend_date', null, ['class' => 'form-control attendenceDatepicker onchange-set-neutral', 'autocomplete' => 'off', 'readonly']) !!}
			            		</div>
		            		</div>
	            		</div>
	            		<div class="form-group">
	            			<!-- {!! Form::label('staffAttend', 'Select', ['class' => 'strong']) !!} -->
	            			<div class="radio clip-radio radio-primary m-b-0">
			                    <input type="radio" name="staffAttend" id="mark_as_leave" value="leave" class="radioError">
			                    <label for="mark_as_leave">
			                        Mark as a leave
			                    </label>
			                    <input type="radio" name="staffAttend" id="working_hour" value="working" class="radioError" checked>
			                    <label for="working_hour">
			                        Working Hours
			                    </label>
			                </div>
	            		</div>
	            		<div id="toggel-field-id">
			            	<div class="form-group" id="time-clone-container">
			                    <div class="clearfix hidden time-clone-row">
			                        <a href="#" class="btn-add-new-time margin-left-5 m-t-10 pull-right showHoursElem">+ Add new time</a>
			                        <div class="row margin-top-5">
			                            <div class="col-xs-9 no-padding">
			                                <div class="col-xs-4 no-padding showHoursElem" style="margin-left: 15px;">
			                                    <div class="input-group bootstrap-timepicker timepicker">
			                                    	<input type="text" name="" class="form-control input-sm timepicker1 start_time" data-default-time="6:00 AM">    
			                                        <span class="input-group-addon">
			                                            <span class="glyphicon glyphicon-time"></span>
			                                        </span>
			                                    </div>
			                                </div>
			                                <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
			                                <div class="col-xs-4 no-padding showHoursElem">
			                                    <div class="input-group bootstrap-timepicker timepicker">
	                                    			<input type="text" name="" class="form-control input-sm timepicker1 end_time" data-default-time="7:00 PM" >
			                                        <span class="input-group-addon">
			                                            <span class="glyphicon glyphicon-time"></span>
			                                        </span>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
			                </div>
		            	</div>
		            {!! Form::close() !!}
	        	</div>
	    		<div class="modal-footer">
	    			<button type="button" class="btn btn-red pull-left " data-modal-button-action="reset">Reset</button>
	            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	            	<button type="button" class="btn btn-primary" data-modal-button-action="submit">Submit</button>
	            </div>
	    	</div>
	    </div>
	</div>
