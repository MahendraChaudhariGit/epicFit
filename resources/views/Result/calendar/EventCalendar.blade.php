@extends('Result.masters.app')

@section('meta_description')
@stop()

@section('meta_author')
@stop()

@section('meta')
@stop()

@section('before-styles-end')
@stop()

@section('required-styles')
<!-- start: Bootstrap Select Master -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Bootstrap timepicker -->
<!--{!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}-->
<!-- end: Bootstrap timepicker -->

<!-- Start: NEW timepicker css -->
{!! Html::style('result/css/bootstrap-timepicker.min.css') !!}
<!-- End: NEW timepicker css -->

<!-- start: Full Calendar -->
{!! Html::style('result/plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
<!-- end: Full Calendar -->

<!-- start: Sweet Alert -->
{!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!} 
<!-- end: Sweet Alert -->

{!! Html::style('result/css/custom.css?v='.time()) !!}
<style>
@media (max-width:767px){
  div#app {
    overflow: inherit;
  }
  .sweet-alert {
    position: absolute;
    margin-bottom: 50px;
  }
}
</style>
@stop()

@if(!isset($subview))
@section('title')
Calendar
@stop
@endif  

@section('content')
<input class="userId" type="hidden" name="userId" value="{{Auth::User()->account_id}}">
<div id="calPopupHelper" class="hidden"></div>
<div id="waitingShield" class="hidden text-center">
  <div>
    <i class="fa fa-circle-o-notch"></i>
  </div>
</div>
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
<!-- Notification message abhi-->
<div id="dateSelMsg" class="center clearfix hidden">
  {{ HTML::link('#', '&times;', array('class' => 'pull-right')) }}
  <span></span>
</div>

@if(!isset($subview))
<!-- start: Appoinment Cancel Modal -->
@include('Result.partials.appointment_cancel_modal')
<!-- end: Appoinment Cancel Modal -->
<!-- start: HIDDEN FIELD FOR DATA FATCH CALENDAR SETTING -->
<input type='hidden' name='calendarSettingVal' value ='{{ json_encode($calendarSettingVal) }}' >
<input type='hidden' id='edit_time_limit' name='edit_time_limit' value ='{{ $edit_time_limit }}' >

<!-- end: HIDDEN FIELD FOR DATA FATCH CALENDAR SETTING -->

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
                            <span class="help-block"></span>
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
                              <div class="input-group bootstrap-timepicker timepicker eventTime">
                                {!! Form::text('eventTime', null, ['class' => 'form-control timepicker1', 'autocomplete' => 'off', 'required']) !!}
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

            {{--@if(isUserType(['Admin']))--}}
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
            {{--@endif--}}
            @endif

            <!-- start: Class Modal -->
            @include('Result.partials.class_modal_client', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
            <!-- end: Class Modal -->

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
                              <!--<div class='input-group date datetimepicker'>-->
                                <!--<input type='text' name='monday_start0' class="form-control input-sm" value="6:00 AM"/>-->
                                <div class="input-group bootstrap-timepicker timepicker">
                                  <input type="text" name="monday_start0" class="form-control input-sm timepicker1" data-default-time="6:00 AM" >    
                                  <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                  </span>
                                </div>
                              </div>
                              <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                              <div class="col-xs-4 no-padding showHoursElem">
                                <!--<div class='input-group date datetimepicker'>-->
                                  <!--<input type='text' name='monday_end0' value="7:00 PM" class="form-control input-sm" />-->
                                  <div class="input-group bootstrap-timepicker timepicker">
                                    <input type="text" name="monday_end0" class="form-control input-sm timepicker1" data-default-time="7:00 PM" >
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
                                  <!--<div class='input-group date datetimepicker' id=''>-->
                                    <!--<input type='text' name='tuesday_start0' value="6:00 AM" class="form-control input-sm" />-->
                                    <div class="input-group bootstrap-timepicker timepicker">
                                      <input type="text" name="tuesday_start0" class="form-control input-sm timepicker1" data-default-time="6:00 AM" >
                                      <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                      </span>
                                    </div>
                                  </div>
                                  <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                  <div class="col-xs-4 no-padding showHoursElem">
                                    <!--<div class='input-group date datetimepicker'>-->
                                      <!--<input type='text' name='tuesday_end0' value="7:00 PM" class="form-control input-sm" />-->
                                      <div class="input-group bootstrap-timepicker timepicker">
                                        <input type="text" name="tuesday_end0" class="form-control input-sm timepicker1" data-default-time="7:00 PM" >
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
                                        <!--<div class='input-group date datetimepicker' id=''>
                                          <input type='text' name='wednesday_start0' value="6:00 AM" class="form-control input-sm" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="wednesday_start0" class="form-control input-sm timepicker1" data-default-time="6:00 AM" >
                                            <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                          </div>
                                        </div>
                                        <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                        <div class="col-xs-4 no-padding showHoursElem">
                                        <!--<div class='input-group date datetimepicker'>
                                          <input type='text' name='wednesday_end0' value="7:00 PM" class="form-control input-sm" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="wednesday_end0" class="form-control input-sm timepicker1" data-default-time="7:00 PM" >
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
                                        <!--<div class='input-group date datetimepicker' id=''>
                                          <input type='text' name='thursday_start0' value="6:00 AM" class="form-control input-sm" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="thursday_start0" class="form-control input-sm timepicker1" data-default-time="6:00 AM" >    
                                            <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                          </div>
                                        </div>
                                        <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                        <div class="col-xs-4 no-padding showHoursElem">
                                        <!--<div class='input-group date datetimepicker'>
                                          <input type='text' name='thursday_end0' value="7:00 PM" class="form-control input-sm" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="thursday_end0" class="form-control input-sm timepicker1" data-default-time="7:00 PM" >
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
                                        <!--<div class='input-group date datetimepicker' id=''>
                                          <input type='text' name='friday_start0' value="6:00 AM" class="form-control input-sm" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="friday_start0" class="form-control input-sm timepicker1" data-default-time="6:00 AM" >    
                                            <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                          </div>
                                        </div>
                                        <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                        <div class="col-xs-4 no-padding showHoursElem">
                                        <!--<div class='input-group date datetimepicker'>
                                          <input type='text' name='friday_end0' value="7:00 PM" class="form-control input-sm" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="friday_end0" class="form-control input-sm timepicker1" data-default-time="7:00 PM" >
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
                                        <!--<div class='input-group date datetimepicker' id=''>
                                          <input type='text' name='saturday_start0' class="form-control input-sm" value="6:00 AM" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="saturday_start0" class="form-control input-sm timepicker1" data-default-time="6:00 AM" >
                                            <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                          </div>
                                        </div>
                                        <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                        <div class="col-xs-4 no-padding showHoursElem">
                                        <!--<div class='input-group date datetimepicker'>
                                          <input type='text' name='saturday_end0' class="form-control input-sm" value="7:00 PM" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="saturday_end0" class="form-control input-sm timepicker1" data-default-time="7:00 PM" >    
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
                                        <!--<div class='input-group date datetimepicker' id=''>
                                          <input type='text' name='sunday_start0' class="form-control input-sm" value="6:00 AM" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="sunday_start0" class="form-control input-sm timepicker1" data-default-time="6:00 AM" >
                                            <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                          </div>
                                        </div>
                                        <div class="col-xs-2 no-padding text-center text-bold showHoursElem">&#95;&#95;&#95;&#95;</div>
                                        <div class="col-xs-4 no-padding showHoursElem">
                                        <!--<div class='input-group date datetimepicker'>
                                          <input type='text' name='sunday_end0' class="form-control input-sm" value="7:00 PM" />-->
                                          <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="sunday_end0" class="form-control input-sm timepicker1" data-default-time="7:00 PM" >
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

                        @if(!isset($subview))
                        <!-- start: Add Button -->
                        <input type="hidden" name="url_p1" id="url_p1" value="{{Request::segment(3)}}">
                        <input type="hidden" name="url_p2" id="url_p2" value="{{Request::segment(4)}}">
                        <input type="hidden" name="client_user_id" id="client_user_id" value="<?php echo Auth::user()->account_id;?>">
                        <div class="btn-group addBtnGroup">
                          <a class="btn btn-primary dropdown-toggle epic-tooltip" data-toggle="dropdown" href="#" rel="tooltip" title="Add Booking">Book In My Session
                            <i class="fa fa-plus"></i>
                          </a>
                          <!--        <a href="#" onclick="show_signup_modal_by_url(4545,'2017-08-23')">Signup {{Request::segment(3)}} {{Request::segment(4)}}</a>-->
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
            Add Booking
          </a>
        </li>
        <li>
          <a href="#" class="eventAdd" data-target-modal="signupModal">
            Signup in a Class
          </a>
        </li>

     <!--
          @if(Session::has('businessId'))
          <li>
            <a href="">
              Client
            </a>
          </li>
          @else
          <li>
            <a href="">
              Client
            </a>
          </li>
          @endif
        -->
      </ul>
    </div>
    <!-- end: Add Button -->

    {{--@if(isUserType(['Admin']))--}}
    <!-- start: Sale Button -->
      <!--<a class="btn btn-primary epic-tooltip saleBtnGroup" href="#" data-toggle="modal" data-target="#creditModal" rel="tooltip" title="Issue credit">
        <i class="fa fa-tag"></i>
      </a>-->
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
      {{--@endif--}}
      @endif

      <!-- start: Filter dropdown -->

      {{--dd($locsAreas,$staffs)--}}
      <div style="display: none;"> 
        {!! Form::select('type', ['all'=>'All Locations'], null, ['class' => 'form-control toolBarDd loc-area-dd hidden', 'autocomplete' => 'off']) !!}
        {!! Form::select('type', ["all" => "All staff"], 'all', ['class' => 'form-control toolBarDd staff-filter-cal hidden', 'autocomplete' => 'off']) !!} 
      </div> <!--$stff-->
      <!-- end: Filter dropdown -->

      {!! Form::hidden('workingHours', json_encode($staffHours), ['autocomplete' => 'off']) !!}   
      {!! Form::hidden('selectedDatetime') !!}  

      {{--dd($clients)--}}
      @if(isset($cl))
      {!! Form::hidden('defaultClient', htmlentities($cl), ['autocomplete' => 'off','id' => 'defaultClient']) !!}
      @else
      {!! Form::hidden('defaultClient', htmlentities($clients), ['autocomplete' => 'off','id' => 'defaultClient']) !!}
      @endif


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
      {!! Form::hidden('closedDates', $closedDates, ['autocomplete' => 'off']) !!}
      <div id='calendar' class="hidemobilecalender newcalender"></div>
      <div class="mycredit_mobile">
        <strong>My Epic Credit:</strong>
        <span>${{$availableCreditBalance}}</span>
      </div>
      <div class="mobilecalenderdesign">
        <div id="resSessionMsg" class="center clearfix hidden">
          {{ HTML::link('#', '&times;', array('class' => 'pull-right')) }}
          <span></span>
        </div>
        <div class="dashboardcalender">
          <h2 class="dateheading">
            <span class="eventDay">{{\Carbon\Carbon::now()->format("D")}}</span> 
            <strong><span class="eventDate">{{\Carbon\Carbon::now()->format("d")}}</span> <span class="eventMonth">{{\Carbon\Carbon::now()->format("M")}}</span></strong>
          </h2>
          <input type="hidden" name="currentMonthYear" value="{{\Carbon\Carbon::now()->format('Y, M')}}">
          <ul id="classTabs" class="nav nav-tabs">
            <li class="active sectionheading">
              <a href="#mysession" id="mySession" data-toggle="tab">
                My Sessions
              </a>
            </li>
            <li>
              <a href="#Booksession" id="bookSession" data-toggle="tab">
                Book a session
              </a>
            </li>
            <li>
              <a id="bookService" href="#Bookservice" data-toggle="tab">
                Book a service
              </a>
            </li>
            <li class="resService" style="width:100%">
              <a id="resService" class="hidden" href="#ResService" data-toggle="tab">
                Reschedule Service
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade in active" id="mysession">
              <div class="text-center">
                <div class="leftarrowbtn"></div>
                <h2 class="dateheading2"><strong class="eventFullMonth"> {{\Carbon\Carbon::now()->format("F")}}</strong> <span class="eventDate" id="eventYear">{{\Carbon\Carbon::now()->format("Y")}}</span></h2>
                <div class="rightarrowbtn"></div>
              </div>
              <div class="datasection withcalender">
                <ul class="session-data">              
                </ul>
              </div>
            </div>

            <!-- Start:Book Session -->
            <div class="tab-pane fade" id="Booksession">
              <div id="errorBox"></div>
              <div class="book_session">
                <div class="top_date">
                  <div class="leftarrowbtn"></div>
                  <div class="timesession timesessionAm active">
                    AM
                  </div>
                  <div class="session-date-calender">
                    <div class="input-group sessionCalender">
                      <input type="hidden" name="currentEventDate" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                      <span class="input-group-addon fulldate"></span>
                    </div>
                  </div>
                  <div class="timesession timesessionPm">
                    PM
                  </div>
                  <div class="rightarrowbtn"></div>
                </div>
                <ul id="eventTimingsAm" class="listingdata"></ul>
                <ul id="eventTimingsPm" class="listingdata" style="display:none"></ul>
                <div id="sessionDetails" class="listingdata" style="display:none"></div>
              </div>
            </div>
            <!-- End:Book Session -->

            <div class="tab-pane fade" id="Bookservice">
              <div class="book_services">
                <div class="service_top_date">
                  <div class="left-arrow-btn"></div>
                  <div class="service-fulldate">
                    <div class="input-group serviceDateChange">
                      <input class="form-control"  autocomplete="off" required="" name="eventServiceDate" type="hidden">
                      <span class="input-group-addon serEventDay"></span>
                    </div>
                  </div>
                  <div class="right-arrow-btn"></div>
                </div>
                <fieldset class="padding-15" id="serviceFieldset">
                  <legend>Service &nbsp;</legend>
                  <div id="errorBoxSer"></div>                  
                  <div class="form-group">
                    <label for="staffservice" class="strong">Service *</label>
                    <div class="btn-group bootstrap-select form-control">
                      <select class="form-control" required="required" id="services" name="services">
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="strong">Duration *</label>
                    <p data-service-duration="" id="durupMobile"></p>                      
                  </div>
                  <div class="form-group priupdiv">
                    <label for="servicePrice" class="strong">Price *</label>
                    <p data-service-price="" id="priupMobile"></p>
                  </div> 
                  <div class="form-group">
                    <label for="" class="strong">Booking status *</label>
                    <p data-init-status="" id="iniStatMobile"></p>                    
                  </div>
                </fieldset>
                <fieldset class="padding-15 serGeneralSec" style="display:none">
                  <legend>General </legend>
                  <div class="form-group set-group primary-form-group">
                    {!! Form::label('staff', 'Staff', ['class' => 'strong']) !!}
                    {{-- <div class="set-group-disp"><span></span> {{ HTML::link('#', 'change') }}</div> --}}
                    {!! Form::select('staff', [], null, ['class' => 'form-control','multiple'=>'multiple','data-actions-box' => "true"]) !!}
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group">
                    <label for="" class="strong">Time *</label>
                    <div class="clearfix moveErrMsg">
                      <div id="timepickerMob"></div>
                      <input class="form-control timepickerMobInput" autocomplete="off" required="" name="eventTime" type="hidden" val="09:00:00">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="" class="strong">Available Staffs *(Select one)</label>
                    <div class="service_staff_check">
                    </div>
                  </div>
                  <div class="form-group set-group">
                    {!! Form::label('modalLocArea', 'Location - Area', ['class' => 'strong']) !!}
                    <div class="set-group-disp"><span></span>
                      {!! Form::select('modalLocArea', [], null, ['class' => 'form-control onchange-set-neutral temp hidden', 'multiple', 'required']) !!}
                      <span class="help-block"></span>
                    </div>
                  </fieldset>
                  <button type="button" class="btn btn-primary submit" name="service_submit" id="serviceSubmit">Save</button>
                </div>
              </div>

              <!-- Start:Reschedule Service -->
              <div class="tab-pane fade" id="ResService">
                <div id="errorBoxRes"></div>
                <div class="res_service">
                  <ul class="listingdata listingdataService">

                  </ul>
                </div>
              </div>
              <!-- End:Reschedule Service -->
            </div>

          </div>
        </div>

        <!-- Start:Event action Popup -->
        <div class="mobile_calender_model">
          <div class="modal_inner_box">
            <div class="crossround"></div>
            <div class="content_box">
              <input type="hidden" name="ppEventId" value="">
              <input type="hidden" name="ppEventType" value="">
              <input type="hidden" name="isLdc" value="0">
              <input type="hidden" name="eventDate" value="">
              <h3>What do you want to do?</h3>
              <button class="bgyello poppupAction" id="resMobAction">Reschedule</button>
              <button class="bgyello poppupAction" id="creditMobAction">Add to EPIC credit</button>
              <br>
              <button class="bggray poppupAction" id="deleteMobAction">Just delete it</button>
            </div>
            <div class="messaagetext hidden" id="ppError"></div>
          </div>
        </div>
        <!-- End:Event action Popup -->

        <!-- Start:Mobile Event Detail Modal -->
        <div class="class_schdule_model">
          <div class="modelinner_box">
            <div class="crossround"></div>
            <div class="topheader">
              <ul>
                <li class="active"><i class="fa fa-calendar"></i> Details</li>
              </ul>
            </div>
            <div class="class_details">
              <input type="hidden" name="mobEventId" value="">
              <input type="hidden" name="mobEventType" value="">
              <input type="hidden" name="isLdc" value="0">
              <input type="hidden" name="eventDate" value="">
              <div class="messaagetext hidden" id="evError"></div>
              <fieldset>
                <legend class="evType"></legend>
                <div class="yellotext evType"></div>
                <h4 id="evClassName"></h4>
                <div class="yellotext">Duration</div>
                <h4 id="evDuration"></h4>
              </fieldset>
              <fieldset>
                <legend>General</legend>
                <div class="yellotext">Staff</div>
                <h4 id="evStaff"></h4>
                <div class="yellotext">Location</div>
                <h4 id="evLocation"></h4>
              </fieldset>
            </div>
            <div class="model_footer">
              <button class="bggray" id="evReschedule">Reschedule</button>
              <button class="bggray" id="evAddEpicCredit">Add to EPIC credit</button>
              <button class="bgyello" id="evJustDeleteIt">Just delete it</button>
              <div class="cancel_btn">Cancel</div>
            </div>
          </div>
        </div>
        <!-- End:Mobile Event Detail Modal -->
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

@section('required-script')
<script type="text/javascript">
  $('body').on('click','.mobile_calender_model .crossround', function(){
    $('.mobile_calender_model').hide();
  });

  // $('body').on('click','.data_box', function(){
  //   $('.class_schdule_model').fadeIn();
  // });
  $('body').on('click','.class_schdule_model .crossround', function(){
    $('.class_schdule_model').fadeOut();
  });
</script>
{!! Html::script('result/js/jquery-ui.min.js') !!}

{!! Html::script('result/plugins/moment/moment.min.js') !!}

<!-- start: jquery validation -->
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
<!-- end: jquery validation -->

<!-- start: Bootstrap Select Master -->
{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Bootstrap timepicker -->
{!! Html::script('result/js/bootstrap-datetimepicker.min.js') !!}
<!-- end: Bootstrap timepicker -->

<!-- Start:  NEW timepicker js -->
{!! Html::script('result/js/bootstrap-timepicker.js') !!}
<!-- End: NEW timepicker js --> 


{!! Html::script('result/js/main-client.js?v='.time()) !!}

<!-- start: Country Code Selector -->
{!! Html::script('result/js/utils.js?v='.time()) !!}
{!! Html::script('result/js/intlTelInput.js?v='.time()) !!}
<!-- end: Country Code Selector -->

<!-- start: Bootstrap Typeahead -->
{!! Html::script('result/js/bootstrap3-typeahead.min.js?v='.time()) !!}
<!-- end: Bootstrap Typeahead -->

<!-- start: Full Calendar -->
{!! Html::script('result/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
<!-- end: Full Calendar -->

<!-- start: Sweet Alert -->
{!! Html::script('result/plugins/sweetalert/sweet-alert.min.js?v='.time()) !!} 
<!-- end: Sweet Alert -->

{!! Html::script('result/js/helper.js?v='.time()) !!}

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
  {!! Html::script('result/js/events-client.js?v='.time()) !!}
  <!-- end: Events -->

  <!-- start: Full Calendar Custom Script -->
  {!! Html::script('result/js/calendar.js?v='.time()) !!}
  {{-- <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script> --}}
  {!! Html::script('result/js/hammer.min.js?v='.time()) !!}
  {!! Html::script('result/js/calendar-mobile.js?v='.time()) !!}
  <!-- end: Full Calendar Custom Script -->


  <script>

//    singup modal start
$(document).ready(function(){
  var url_p1 = $('#url_p1').val();
  var url_p2 = $('#url_p2').val();
  if(url_p1 && url_p2)
  {
//        alert(url_p1 +' <<>> '+ url_p2);

if(url_p1 == 'edit_service')
{
            // console.log(url_p1 +' <<>> '+ url_p2);
            //show_booking_modal_by_url(url_p2);
          }
          else
          {
            show_signup_modal_by_url(url_p1,url_p2);
          }

        }
      });

//    singup modal end

</script>
<script>

  $('document').ready(function(){
    $('thead.fc-head').addClass('sticky-header');
  })
  $(window).scroll(function() {
    if ($(this).scrollTop() > 200){  
      $('.alt-header').show();
    }
    else{
      $('.alt-header').hide();
    }
  });

  $('document').ready(function(){
   $('.fc-toolbar').append('<table class="alt-header" style="display:none;"><thead class="fc-head" style="margin-right: 8px;"><tr><td class="fc-head-container fc-widget-header"><div class="fc-row fc-widget-header"><table><thead><tr><th class="fc-day-header fc-widget-header fc-mon">Mon</th><th class="fc-day-header fc-widget-header fc-tue">Tue</th><th class="fc-day-header fc-widget-header fc-wed">Wed</th><th class="fc-day-header fc-widget-header fc-thu">Thu</th><th class="fc-day-header fc-widget-header fc-fri">Fri</th><th class="fc-day-header fc-widget-header fc-sat">Sat</th><th class="fc-day-header fc-widget-header fc-sun">Sun</th></tr></thead></table></div></td></tr></thead></table>');
 })

</script>




@stop()


@section('script-handler-for-this-page')
@stop()

@section('script-after-page-handler')
@stop()


