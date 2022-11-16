<!--start: Class Modal -->
<div class="modal fade" id="classModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close m-t--10" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="duration_val" id="duration_val" value="">    
                <input type="hidden" name="clientId" id="clientId" value="<?php echo Auth::user()->account_id;?>">    
                {!! Form::open(['url' => '', 'role' => 'form']) !!}
                {!! Form::hidden('eventId') !!}
                {!! Form::hidden('isRepeating') !!}
                {!! Form::hidden('targetEvents') !!}
                {!! Form::hidden('serviceId') !!}
                {!! Form::hidden('forceAdd') !!}
                
                <div class="tabbable">
                    <ul id="classTabs" class="nav nav-tabs">
                        <li class="active">
                            <a href="#classDetails" data-toggle="tab">
                                <i class="fa fa-calendar"></i> Details
                            </a>
                        </li>
                        <li class="hidden">
                            <a href="#classClients" data-toggle="tab">
                                <i class="fa fa-refresh"></i> Clients
                            </a>
                        </li>

                        <li class="hidden">
                            <a href="#classReccur" data-toggle="tab">
                                <i class="fa fa-refresh"></i> Recurrence
                            </a>
                        </li>

                        <li>
                            <a href="#classNotes" data-toggle="tab">
                                <i class="fa fa-pencil"></i> Notes
                            </a>
                        </li>
                        <!--<li>
                                <a href="#classHist" data-toggle="tab">
                                        <i class="fa fa-list-alt"></i> History (<span></span>)
                                </a>
                        </li>-->
                        <li>
                            <a href="#classAttendance" data-toggle="tab">
                                <i class="fa fa-list"></i>  Attendance
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="classDetails">
                            <div class="alert_new alert-warning hidden" id="show_error">
                                At least one field is required out of Email address and Phone number.
                            </div>    
                            <div class="row">
                                <div class="col-md-12 errorMsg"></div>
                                <div class="col-md-6">
                                    <div class="form-group m-b-0">
                                        {!! Form::label(null, '', ['class' => 'strong']) !!}
                                        <!--<a class="font-14" data-content="Switching booking type will reset the filled information." data-placement="right" data-toggle="popover" data-trigger="hover">
                                                                                    <i class="fa fa-info-circle" href="#"></i>
                                                                            </a>-->
                                        <ul class="clearfix p-l-0" id="bookTypeSelectable">
                                            <li class="col-xs-6 ui-widget-content ui-selected hidden" data-val="class">Class</li>
                                            <li class="col-xs-6 ui-widget-content hidden" data-val="service">Service</li>

                                            <!-- <li class="col-xs-6 ui-widget-content ui-selected" data-val="service" style="display: none;">Service</li>
                                           <li class="col-xs-6 ui-widget-content ui-selected" data-val="service">Service</li> -->
                                        </ul>
                                        {!! Form::hidden('bookType', 'service', ['class' => 'no-clear']) !!}

                                    </div>

                                    <!--<div class="row padding-15 padding-bottom-0">
                            <div class="row padding-15 padding-bottom-0">
                                {!! Form::label('venue', 'What type of location or training area *', ['class' => 'strong']) !!}
                                <span class="epic-tooltip" data-toggle="tooltip" title="This relates specifically to the training area, this may either be a location or a training area within a specific location if it has more that one training area in a location"><i class="fa fa-question-circle"></i></span>
                            </div>
                            <div class="row">
                                <ul class="selectable">
                                    @if(!isset($businessId) || isset($location) || (isset($entityType) && $entityType == 'location'))
                                        <li class="col-xs-6 ui-widget-content ui-selected">Location</li>
                                    @endif
                                    @if(!isset($businessId) || isset($area) || (isset($entityType) && $entityType == 'area'))
                                        <li class="col-xs-6 ui-widget-content {{ isset($area) || (isset($entityType) && $entityType == 'area')?'ui-selected':'' }}">Area</li>
                                    @endif
                                </ul>
                                <div class="form-group {{ $errors->has('venue') ? 'has-error' : ''}}">
                                    <div>
                                        {!! Form::text('venue', null, ['class' => 'form-control hide', 'id' => 'select-result', 'required' => 'required']) !!}
                                        {!! $errors->first('venue', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>-->
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="details_recur"> </div>
                                    <fieldset class="padding-15" id="classFieldset">
                                        <legend>
                                            Class &nbsp;&nbsp;&nbsp;&nbsp;
                                        </legend>
                                        <div class="form-group delMsgPar"><!--moveErrMsg-->
                                            {!! Form::label('staffClass', 'Class *', ['class' => 'strong']) !!}
                                            {!! Form::select('staffClass', [], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
                                            <!--<p class="m-b-0 text-danger"></p>
                                            <span class="help-block placeErrMsg"></span>-->
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
                                    <fieldset class="padding-15" id="serviceFieldset">
                                      <legend>
                                            Service &nbsp;&nbsp;&nbsp;&nbsp;
                                        </legend>  
                                    <div id="details_recur_service"></div>

                                        <div id="details_recur_render">
                                        
                                        <div class="form-group delMsgPar">


                                            {!! Form::label('staffservice', 'Service *', ['class' => 'strong']) !!}
                                            {!! Form::select('staffservice', [], null, ['class' => 'form-control onchange-set-neutral', 'required']) !!}
                                        </div>
                                        <div class="form-group serviceDurdiv">
                                            {!! Form::label('serviceDur', 'Duration *', ['class' => 'strong']) !!}
                                            <p id="durup"> </p>
                                            {!! Form::select('serviceDur', ['' => '-- Select --', '5' => '5 min', '10' => '10 min', '15' => '15 min', '20' => '20 min', '25' => '25 min', '30' => '30 min', '35' => '35 min', '40' => '40 min', '45' => '45 min', '50' => '50 min', '55' => '55 min', '60' => '60 min'], null, ['class' => 'form-control onchange-set-neutral hidden', 'required']) !!}
                                        </div>
                                        <div class="form-group priupdiv">
                                            {!! Form::label('servicePrice', 'Price *', ['class' => 'strong']) !!}
                                            <p id="priup"> </p>
                                            {!! Form::text('servicePrice', null, ['class' => 'form-control price-field hidden', 'required']) !!}
                                        </div>
                                         </div>
                                        <div class="form-group">
                                            {!! Form::label(null, 'Booking status *', ['class' => 'strong']) !!}
                                            <p id="ini-stat"> </p>
                                            <ul class="clearfix p-l-0 ui-selectable" id="appointStatusSelectable">
                                            <li class="col-xs-6 ui-widget-content ui-selectee" data-val="Pencilled-In">Pencilled-In</li>
                                            <li class="col-xs-6 ui-widget-content ui-selectee" data-val="Confirmed">Confirmed</li>
                                                {!! Form::hidden('appointStatusOpt', 'Pencilled-In',['id' => 'appointStatusOpt']) !!}
                                            </ul> 

                                        </div>
                                        <div class="form-group hidden" id="appointStatusPending"><!--appoint_status_pending-->
                                            <div class="checkbox clip-check check-primary m-b-0 moveErrMsg">
                                                {!! Form::checkbox('ifAutoExpireAppoint', '1', null, ['id' => 'ifAutoExpireAppoint', 'class' => 'onchange-set-neutral']) !!}
                                                <!--<label for="ifAutoExpireAppoint" class="m-r-0 no-error-label">
                                                    <strong>Auto-expire at set time</strong>
                                                </label>-->
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
                                    </fieldset>
                                </div>
                                <div class="col-md-6" id="generalTab">
                                    <fieldset class="padding-15">
                                        <legend>
                                            General &nbsp;&nbsp;&nbsp;&nbsp;
                                        </legend>
                                        <div class="form-group"><!--classTimeGroup-->
                                            {!! Form::label(null, 'Date *', ['class' => 'strong']) !!}
                                            <div class="clearfix moveErrMsg">
                                                <div class="pull-left">
                                                    <span id="resClassEveDate"> </span>
                                                    <span class="eventDateDisp"> at </span> 
                                                    {{-- <span id="at" class="hidden">at</span> --}}
                                                </div>
                                                <div class="input-group bootstrap-timepicker timepicker eventTime"><!--classTime datetimepicker-->
                                                    {{-- {!! Form::text('eventTime', null, ['class' => 'form-control timepicker1', 'autocomplete' => 'off', 'required']) !!} --}}
                                                    {!! Form::hidden('rescedule_flag',null,['id' => 'rescedule_flag']) !!}
                                                    {{-- <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span> --}}
                                                </div>
                                                {{ HTML::link('#', 'change', ['class' => 'eventDateChange pull-left']) }}
                                            </div>
                                            <span class="help-block placeErrMsg"></span>
                                        </div>
                                        <div class="form-group set-group primary-form-group"><!--m-b-5 space-if-error-->
                                            {!! Form::label('staff', 'Staff', ['class' => 'strong']) !!}
                                            <div class="set-group-disp set-group-disp-staff"><span></span></div>
                                            {!! Form::select('staff', [], null, ['class' => 'form-control onchange-set-neutral selectStaffDD','multiple'=>'multiple','data-actions-box' => "true"]) !!}
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="form-group setEventTime">
                                            <label for="" class="strong">Time *</label>
                                            <div class="clearfix moveErrMsg">
                                              <div id="timepickerDesk"></div>
                                              <input class="form-control timepickerDeskInput" autocomplete="off" required="" name="eventTime" type="hidden" val="09:00:00">
                                            </div>
                                          </div>
                                        <div class="form-group set-group primary-form-group availableStaff">
                                            <label for="" class="strong">Available Staffs *(Select one)</label>
                                            <div class="service_staff_check">
                                            </div>
                                        </div>
                                        <div class="form-group form-inline sibling-form-group">
                                            <div class="checkbox clip-check check-primary">
                                                {!! Form::checkbox('ifMarkFav', '1', null, ['id' => 'ifMarkFav', 'class' => 'onchange-set-neutral']) !!}
                                                <label for="ifMarkFav" class="m-r-0 no-error-label">
                                                    <strong>Associate areas with staff till</strong> 
                                                </label>
                                                {!! Form::text('markFavTill', null, ['class' => 'form-control eventDatepicker onchange-set-neutral', 'autocomplete' => 'off', 'readonly']) !!}
                                                <span class="help-block m-y-0"></span>
                                            </div>
                                        </div>
                                        <div class="form-group set-group"><!--set-group-->
                                            {!! Form::label('modalLocArea', 'Location - Area', ['class' => 'strong']) !!}
                                            <div class="set-group-disp"><span></span> <!-- {{ HTML::link('#', 'change') }} --></div>
                                            {!! Form::select('modalLocArea', [], null, ['class' => 'form-control onchange-set-neutral temp', 'multiple', 'required']) !!} <!--, $modalLocsAreas loc-area-dd -->
                                            <span class="help-block"></span>
                                        </div>
                                        <div class="form-group hidden">
                                            {!! Form::label(null, 'Clients', ['class' => 'strong']) !!}
                                            <p><a href="#" id="show-clients-tab"><span class="linkedclients-text"></span> >></a></p>
                                            <div class="progress progress-striped progress-sm">
                                                <div class="progress-bar progress-bar-success" role="progressbar">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div id="epic_cash_div_ser" class="form-group form-inline sibling-form-group hidden">
                                        <div class="checkbox clip-check check-primary">
                                            {!! Form::checkbox('isEpicCashSer', '1', null, ['id' => 'isEpicCashSer', 'class' => '']) !!}
                                            <label for="isEpicCashSer">
                                                <strong>Use EPIC Credit?</strong>
                                            </label>
                                            <p id="epic_cash_charge_ser"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="classReccur">
                            <div id="recur_recur"> </div>
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
                                            {{--@if(Auth::user()->hasPermission(Auth::user(), 'create-client') && !isset($subview))--}}


                                            {!! Form::text('clientName', Auth::user()->name, ['class' => 'form-control']) !!}
                                            {{--@endif--}}
                                            {!! Form::text(null, null, ['class' => 'form-control clientList', 'autocomplete' => 'off']) !!}
                                            {!! Form::hidden('clientId',Auth::user()->account_id) !!}
                                            {{--@if(Auth::user()->hasPermission(Auth::user(), 'create-client') && !isset($subview))--}}
                                            <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                                                {!! Form::checkbox('isNewClient', '1', null, ['id' => 'classIsNewClient']) !!}
                                                <label for="classIsNewClient" class="no-error-label">
                                                    <strong>New client?</strong>
                                                </label>
                                            </div>
                                            {{--@endif --}}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('clientEmail', 'Email address *', ['class' => 'strong']) !!}
                                            {!! Form::email('clientEmail', Auth::user()->email, ['class' => 'form-control clientDetails']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('clientNumb', 'Phone number *', ['class' => 'strong ']) !!}
                                            {!! Form::tel('clientNumb', Auth::user()->telephone, ['class' => 'form-control countryCode numericField clientDetails', 'maxlength' => '16', 'minlength' => '5']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('clientNote', 'Notes', ['class' => 'strong']) !!}
                                            {!! Form::textarea('clientNote', Auth::user()->resources, ['class' => 'form-control textarea']) !!}
                                        </div>
                                        <div id="classClientsBtns">
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
                                            <div class="form-group">
                                                <div class="checkbox clip-check check-primary m-b-0">
                                                    {!! Form::checkbox('isCreatingMakeUpSession', '1', null, ['id' => 'isCreatingMakeUpSession', 'class' => 'disableable']) !!}
                                                    <label for="isCreatingMakeUpSession">
                                                        <strong>If make up session?</strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="btn btn-success pull-left" href="#" id="linkClientClass">
                                            <i class="fa fa-plus"></i>
                                            Add to <span class="bookingTypeName">class</span>
                                        </a>
                                        <a class="btn btn-success pull-left m-r-10" href="#" id="confirmClient">
                                            <i class="fa fa-check-square-o"></i>
                                            Confirm Client
                                        </a>
                                        <!-- <a class="btn btn-red pull-left" href="#" id="unlinkClientClass">
                                            <i class="glyphicon glyphicon-trash"></i>
                                            Remove from <span class="bookingTypeName">class</span>
                                        </a> -->
                                    </fieldset>
                                </div>
                            </div>	
                        </div>
                        <div class="tab-pane fade" id="classNotes">
                            <div id="classNotes_recur"> </div>
                            <div class="form-group">
                                <label for="classNote" class="strong"><span class="bookingTypeName capitalize">Class</span> notes</label>
                                {!! Form::textarea('classNote', null, ['class' => 'form-control textarea']) !!}
                            </div>
                        </div>
                        <div class="tab-pane fade event-history" id="classHist">
                        </div>
                        <div class="tab-pane fade" id="classAttendance">
                                <!-- <p>
                                        Set the attendance status of clients individually below or 
                                        <a href="#">mark all as attended</a>.
                                </p> -->
                            <hr class="m-t-0">
                            <div id="classAttendanceList">
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer clearfix">
                <!-- <a class="btn btn-red pull-left delete-prompt" href="#">
                    <i class="glyphicon-glyphicon-pencil"></i>
                    Create Make Up
                </a> -->

                <a class="btn btn-red pull-left unlink-prompt" href="#" id="unlinkClientClass" data-is-ldc="0"  data-is-before-24="">
                    <i class="glyphicon glyphicon-trash"></i>
                    Remove from <span class="bookingTypeName">class</span>
                </a> 
<!--                <a class="btn btn-red pull-left delete-prompt" href="#">
                    <i class="glyphicon glyphicon-trash"></i>
                    Cancel Booking
                </a> -->
                <a class="btn btn-red pull-left delete-prompt-service" href="#" data-is-before-24="" data-client-makeup="" data-invoice="" data-membership="">
                    <i class="glyphicon glyphicon-trash"></i>
                    Cancel Booking
                </a>
                 <button class="btn btn-default" id="done" data-dismiss="modal" style="display: none;"> 
                    Done 
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-default hidden" id="nextTab" style="display: none;"> 
                    Next <i class="fa fa-arrow-circle-right"></i> 
                </button>

                <!-- <button class="btn btn-primary" id="resClassClient" data-target-modal="resClassClient" data-resevent-id=""> 
                    Reschedule 
                </button> -->

                <button type="button" class="btn btn-primary submit" name="service_submit" id="service_submit">Save</button>
                <a class='btn btn-default btn-block update-event hidden' href='#' data-target-event='this'>This only</a>
            </div>
        </div>
    </div>
</div>
<!-- end: Class Modal -->


<!--start: Class Signup Modal -->
<div class="modal fade" id="classSignupModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close m-t--10" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open(['url' => 'signup-class/create', 'role' => 'form','id'=>'signupClassForm']) !!}
                {!! Form::hidden('forceAdd') !!}
                <div class="tabbable">
                    <ul id="classTabs" class="nav nav-tabs">
                        <li class="active">
                            <a href="#classDetails" data-toggle="tab">
                                <i class="fa fa-calendar"></i> Details
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade in active">
                            <div class="row">
                                <div class="col-md-12 errorMag"></div>
                                <div class="col-md-6">
                                    <fieldset class="padding-15 client-form">
                                        <legend>
                                            Class &nbsp;&nbsp;&nbsp;&nbsp;
                                        </legend>
                                        <div class="form-group delMsgPar">
                                            <label class="strong"> Class </label>
                                            <p id="nmupclass"> </p>
                                        </div>
                                        <div class="form-group">  
                                            <label class="strong"> Duration </label>
                                            <p><span id="durupclass"> </span> mins</p> 
                                        </div>
                                        <div class="form-group">    
                                            <label class="strong"> Capacity </label>    
                                            <p id="capupclass"> </p> 
                                        </div>
                                        <div class="form-group">
                                            <label class="strong"> Price </label>
                                            <p>$ <span id="priupclass"> </span></p>
                                         
                                        </div>
                                        
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <fieldset class="padding-15">
                                        <legend>
                                            General &nbsp;&nbsp;&nbsp;&nbsp;
                                        </legend>
                                        <div class="form-group">
                                            <label class="strong"> Date </label>     
                                            <p id="dateAt"></p>
                                        </div>
                                        <div class="form-group">
                                            <label class="strong"> Staff </label>
                                            <p id="stupclass"> </p>                                       
                                        </div>
                                        <div class="form-group">
                                            <label class="strong"> Location </label>
                                            <p id="locupclass"> </p>                                          
                                        </div>
                                        <div class="form-group set-group primary-form-group hidden">
                                            <div class="checkbox clip-check check-primary m-b-0">
                                                {!! Form::checkbox('ifRecur', '1', null, ['id' => 'ifRecurr', 'class' => '']) !!}
                                                <label for="ifRecurr">
                                                    <strong>Recur if event recurs?</strong>
                                                </label>
                                            </div>
                                        </div>
                                        <div id="epic_cash_div" class="form-group form-inline sibling-form-group ">
                                            <div class="checkbox clip-check check-primary">
                                                {!! Form::checkbox('isEpicCash', '1', null, ['id' => 'isEpicCash', 'class' => '']) !!}
                                                <label for="isEpicCash">
                                                    <strong>Use EPIC Credit?</strong>
                                                </label>
                                                <p id="epic_cash_charge"></p>
                                            </div>
                                        </div> 
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer clearfix">

                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary submit-class-signup">Book</button>
            </div>
        </div>
    </div>
</div>

<!-- end: Class Signup Modal -->

<!-- start: Make-up notes Modal -->
<div class="modal fade" id="makeUpNotesModal" role="dialog">
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
                <button type="button" class="btn btn-default" data-dismiss="modal">done</button>
             
                <button class="btn btn-primary submit">
                    Create Make up
                </button>
            </div>  
        </div>
    </div>      
</div>
<!-- end: Make-up notes Modal