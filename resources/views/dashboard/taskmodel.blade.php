<!--Start: Rapido Add Task Model-->
<div class="modal fade" id="addtask" role="dialog" tabindex="-1" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Task</h4>
            </div>
            <div class="modal-body bg-white">
            
                {!! Form::open(['url' => 'dashboard/task', 'role' => 'form', 'id' =>'taskForm']) !!}
                <input type="hidden" name="taskFormId" value="">
                <input type="hidden" name="taskRepeat" value="">
                <!-- <input type="hidden" name="authId" value=""> -->
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="padding-15"> <!-- class="client-form" -->
                            <legend>
                                Task &nbsp;&nbsp;&nbsp;&nbsp;
                            </legend>
                            <div class="form-group">
                                {!! Form::label('taskName', 'Task Name *', ['class' => 'strong']) !!}
                                {!! Form::text('taskName', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('taskDueDate', 'Task Due Date *', ['class' => 'strong']) !!}
                                <div class="row">
                                    <div class="col-md-8">
                                        {!! Form::text('taskDueDate', null, ['class' => 'form-control eventDatepicker onchange-set-neutral', 'autocomplete' => 'off', 'required', 'id'=> 'taskDueDate']) !!}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="taskDueTime" class="form-control  no-clear timepicker1" data-default-time="9:30 AM" id="taskDueTime" required="required">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <!-- <div class="form-group">
                                {!! Form::label('taskDueDate', 'Task Due Date *', ['class' => 'strong']) !!}
                                {!! Form::text('taskDueDate', null, ['class' => 'form-control eventDatepicker onchange-set-neutral', 'autocomplete' => 'off', 'required', 'id'=> 'taskDueDate']) !!}
                            </div>
                            {!! Form::label('taskDueTime', 'Task Due Time *', ['class' => 'strong']) !!}
                            <div class="form-group input-group bootstrap-timepicker timepicker">    
                                <input type="text" name="taskDueTime" class="form-control input-sm no-clear timepicker1" data-default-time="9:30 AM" id="taskDueTime" >
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                            </div> -->

                            <div class="form-group">
                            {!! Form::label('taskCategory', 'Task Category *', ['class' => 'strong']) !!}
                            {!! Form::select('taskCategory', $tc , null, ['class' => 'form-control onchange-set-neutral','required' => 'required', 'id'=>'taskcategoryid']) !!}
                            </div>
                            
                           <!-- <div class="input-group bootstrap-timepicker timepicker">
                                {!! Form::text('task_due_time', null, ['class' => 'form-control input-sm no-clear timepicker1','data-default-time' => '9:30 AM']) !!}
                                <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                            </div>-->

                            
                            <!--<label for="ventRepeat" class="strong">Repeat</label>
                             <input class="mw-100 eventDatepicker onchange-set-neutral hasDatepicker rapidoDatePicker" autocomplete="off" required type="text"> -->

                        <div class="form-group form-inline">
                            <div class="checkbox clip-check m-b-0 check-primary">
                                <input type="checkbox" class="onchange-set-neutral" id="remindercheck" name="reminder" value="1">
                                <label for="remindercheck" class="m-r-0 no-error-label">
                                    <strong>Remind Me Before</strong> 
                                </label>
                                {!! Form::select('reminderVal', ["" => "-- Select --", 1 => '1 hour', 2 => '2 hours', 3 => '3 hours', 4 => '4 hours', 5 => '5 hours', 6 => '6 hours',7 => '7 hours',8 => '8 hours',9 => '9 hours',10 => '10 hours',11 => '11 hours', 12 => '12 hours', 13 => '13 hours', 14 => '14 hours', 15 => '15 hours', 16 => '16 hours',17 => '17 hours',18 => '18 hours', 19 => '19 hours', 20 => '20 hours', 21 => '21 hours', 22 => '22 hours', 23 => '23 hours', 24 => '24 hours'] , null, ['class' => 'mw-100 onchange-set-neutral', 'id'=>'remindercheckid','disabled']) !!}
                                <span class="help-block m-y-0" style="display: none;"></span>
                            </div>
                        </div>

                        <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" value="Fri" id="rcheck">
                                <label for="rcheck"><strong>Repeat </strong></label>
                                </div>
                        </div>                                                
                        </fieldset>

                        <fieldset class="padding-15 event-reccur" id="recurrencefield"><!-- class="client-form" -->
                            <legend>
                                Recurrence &nbsp;&nbsp;&nbsp;&nbsp;
                            </legend>
                            
                            <!--<div>-->
                                <div class="form-group">
                                    {!! Form::label('eventRepeat', 'Repeat', ['class' => 'strong']) !!}
                                    {!! Form::select('eventRepeat', ['' => '-- Select --', 'None' => 'None', 'Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly'], null, ['class' => 'form-control', 'id' => 'eventrepeatid']) !!}
                                </div>

                                <div class="eventRepeatFields">
                                        <div class="form-group">
                                            {!! Form::label('eventRepeatInterval', 'Repeat every *', ['class' => 'strong']) !!}
                                            <div>
                                                {!! Form::select('eventRepeatInterval', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-92p onchange-set-neutral', 'required', 'id' => 'eventrepeatintervalid']) !!} 
                                                <span class="eventRepeatIntervalUnit">days</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label(null, 'Ends *', ['class' => 'strong']) !!}
                                            <div class="moveErrMsg no-error-labels">
                                                <div class="radio clip-radio radio-primary">
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndAfter" value="After">
                                                    <label for="appointEventRepeatEndAfter">
                                                        After
                                                    </label>
                                                    {!! Form::select('eventRepeatEndAfterOccur', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-120 onchange-set-neutral', 'id' => 'eventrepeatendafteroccurid']) !!}
                                                    occurrences
                                                </div>
                                                <div class="radio clip-radio radio-primary">
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndOn" value="On">
                                                    <label for="appointEventRepeatEndOn">
                                                        On
                                                    </label>
                                                    {!! Form::text('eventRepeatEndOnDate', null, ['class' => 'form-control mw-120 inlineBlckDisp eventDatepicker onchange-set-neutral', 'autocomplete' => 'off']) !!}
                                                </div>
                                                <div class="radio clip-radio radio-primary m-b-0">
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndNever" value="Never">
                                                    <label for="appointEventRepeatEndNever">
                                                        Never
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="help-block placeErrMsg m-t-0"></span>
                                            <div class="eventRepeatWeekdays no-error-labels">
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays0" value="Mon" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays0"> Mon </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays1" value="Tue" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays1"> Tue </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays2" value="Wed" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays2"> Wed </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays3" value="Thu" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays3"> Thu </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays4" value="Fri" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays4"> Fri </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays5" value="Sat" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays5"> Sat </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays6" value="Sun" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays6"> Sun </label>
                                                </div>
                                            </div>
                                            <span class="help-block m-t-0"></span>
                                        </div>
                                </div>       
                            <!--</div>--> 
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">Close
                </button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                {!! Form::submit('Submit', ['class' => 'btn btn-primary' , 'id' => 'taskbtn']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>