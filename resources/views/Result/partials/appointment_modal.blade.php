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
                    {!! Form::hidden('eventId') !!}
                    {!! Form::hidden('isRepeating') !!}
                    {!! Form::hidden('targetEvents') !!}
                    <div class="tabbable">
                        <ul id="appointTabs" class="nav nav-tabs">
                            <li class="active">
                                <a href="#appointDetails" data-toggle="tab">
                                    <i class="fa fa-calendar"></i> Details
                                </a>
                            </li>
                            <li>
                                <a href="#appointReccur" data-toggle="tab">
                                    <i class="fa fa-refresh"></i> Recurrence
                                </a>
                            </li>
                            <li>
                                <a href="#appointNotes" data-toggle="tab">
                                    <i class="fa fa-pencil"></i> Notes
                                </a>
                            </li>
                            <li>
                                <a href="#appointHist" data-toggle="tab">
                                    <i class="fa fa-list-alt"></i> History (<span></span>)
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="appointDetails">
                                <div class="row services">
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
                                                    {!! Form::hidden('serviceIsDeleted0', false) !!}
                                                    <div class="col-md-4 delMsgPar form-group m-b-0 onchange-set-neutral">
                                                        {!! Form::select('serviceName0', [], null, ['class' => 'form-control serviceName', 'required']) !!}
                                                    </div>
                                                    <div class="col-md-3 form-group m-b-0">
                                                        <div class="input-group bootstrap-timepicker timepicker"><!-- datetimepicker-->
                                                            {!! Form::text('serviceTime0', null, ['class' => 'form-control timepicker1', 'autocomplete' => 'off', 'data-default-time'=>'7:00 AM', 'required']) !!}
                                                            <span class="input-group-addon">
                                                            <span class="glyphicon glyphicon-time"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 form-group m-b-0 onchange-set-neutral">
                                                        {!! Form::select('serviceDur0', ['' => '-- Select --', '5' => '5 min', '10' => '10 min', '15' => '15 min', '20' => '20 min', '25' => '25 min', '30' => '30 min', '35' => '35 min', '40' => '40 min', '45' => '45 min', '50' => '50 min', '55' => '55 min', '60' => '60 min'], null, ['class' => 'form-control serviceDur', 'required','disabled']) !!}
                                                    </div>
                                                    <div class="col-md-2 form-group m-b-0">
                                                        {!! Form::text('servicePrice0', null, ['class' => 'form-control price-field servicePrice', 'required','disabled']) !!}
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <fieldset class="padding-15">
                                            <legend>
                                                General &nbsp;&nbsp;&nbsp;&nbsp;
                                            </legend>
                                            <div class="form-group">
                                                {!! Form::label(null, 'Date', ['class' => 'strong']) !!}
                                                <div><span class="eventDateDisp"></span> {{ HTML::link('#', 'change', ['class' => 'eventDateChange']) }}</div>
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="form-group set-group">
                                                {!! Form::label('staff', 'Staff', ['class' => 'strong']) !!}
                                                <div class="set-group-disp"><span></span> {{ HTML::link('#', 'change') }}</div>
                                                {!! Form::select('staff', [], null, ['class' => 'form-control']) !!}
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="form-group set-group">
                                                {!! Form::label('modalLocArea', 'Location - Area', ['class' => 'strong']) !!}
                                                <div class="set-group-disp"><span></span> {{ HTML::link('#', 'change') }}</div>
                                                {!! Form::select('modalLocArea', [], null, ['class' => 'form-control loc-area-dd onchange-set-neutral temp', 'required']) !!}<!--$modalLocsAreas-->
                                                <span class="help-block"></span>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-md-6">
                                        <fieldset class="padding-15 client-form">
                                            <legend>
                                                Client &nbsp;&nbsp;&nbsp;&nbsp;
                                            </legend>
                                            <div class="alert alert-danger hidden new-client-req-msg">
                                                At least one field is required out of Email address and Phone number.
                                            </div>
                                            <div class="form-group">
                                                {{--@if(Auth::user()->hasPermission(Auth::user(), 'create-client') && !isset($subview))--}}
                                                    {!! Form::text('clientName', null, ['class' => 'form-control']) !!}
                                                {{--@endif--}}
                                                {!! Form::text(null, null, ['class' => 'form-control clientList', 'autocomplete' => 'off']) !!}
                                                {!! Form::hidden('clientId') !!}
                                                {{--@if(Auth::user()->hasPermission(Auth::user(), 'create-client') && !isset($subview))--}}
                                                    <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                                        {!! Form::checkbox('isNewClient', '1', null, ['id' => 'isNewClient']) !!}
                                                        <label for="isNewClient" class="no-error-label">
                                                            <strong>New client?</strong>
                                                        </label>
                                                    </div>
                                                {{--@endif--}}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('clientEmail', 'Email address *', ['class' => 'strong']) !!}
                                                {!! Form::email('clientEmail', null, ['class' => 'form-control clientDetails']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('clientNumb', 'Phone number *', ['class' => 'strong ']) !!}
                                                {!! Form::tel('clientNumb', null, ['class' => 'form-control countryCode numericField clientDetails', 'maxlength' => '16', 'minlength' => '5']) !!}
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label(null, 'Booking status *', ['class' => 'strong']) !!}
                                            <div class="row">
                                                <ul class="appoint_status_selectable">
                                                    <li class="col-xs-6 ui-widget-content">Pencilled-In</li>
                                                    <li class="col-xs-6 ui-widget-content ui-selected">Confirmed</li>
                                                    {!! Form::hidden('appointStatusOpt', 'Confirmed') !!}
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="form-group appoint_status_confirm">
                                            {!! Form::select('appointStatusConfirm', ['' => '-- Select --', 'Not started' => 'Not started', 'Arrived' => 'Arrived', 'Started' => 'Started', 'Completed' => 'Completed', 'Did not show' => 'Did not show'], null, ['class' => 'form-control onchange-set-neutral']) !!}
                                        </div>
                                        <div class="form-group appoint_status_pending">
                                            <div class="checkbox clip-check check-primary m-b-0 moveErrMsg">
                                                {!! Form::checkbox('ifAutoExpireAppoint', '1', null, ['id' => 'ifAutoExpireAppoint', 'class' => 'onchange-set-neutral']) !!}
                                                <label for="ifAutoExpireAppoint" class="m-r-0 no-error-label">
                                                    <strong>Auto-expire at set time</strong>
                                                </label>
                                                <span class="autoExpireAppoint">
                                                    {!! Form::select('autoExpireAppointDur', ['' => '-- Select --', 'Custom' => 'Custom', '2' => '2 hours', '3' => '3 hours', '6' => '6 hours', '12' => '12 Hours', '24' => '24 hours', '48' => '48 Hours', '72' => '72 Hours', '168' => '1 week'], null, ['class' => 'mw-100 onchange-set-neutral']) !!}
                                                    <strong>from now</strong>
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
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="appointReccur">
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
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndAfter" value="After">
                                                    <label for="appointEventRepeatEndAfter">
                                                        After
                                                    </label>
                                                    {!! Form::select('eventRepeatEndAfterOccur', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-120 onchange-set-neutral']) !!}
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
                                </fieldset>
                            </div>
                            <div class="tab-pane fade" id="appointNotes">
                                <div class="form-group">
                                    {!! Form::label('appointNote', 'Booking notes', ['class' => 'strong']) !!}
                                    {!! Form::textarea('appointNote', null, ['class' => 'form-control textarea']) !!}
                                </div>
                            </div>
                            <div class="tab-pane fade event-history" id="appointHist">
                                <!--<hr class="m-t-0 m-b-10">
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
                                </p>-->
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer clearfix">
                <a class="btn btn-red pull-left" href="#" data-toggle="modal" data-target="#appointCancelModal" data-dismiss="modal">
                    <i class="glyphicon glyphicon-trash"></i>
                    Cancel service
                </a>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary submit">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- end: Appoinment Modal -->