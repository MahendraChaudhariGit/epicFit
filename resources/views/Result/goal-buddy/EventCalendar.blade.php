@extends('masters.app')

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
{!! Html::style('plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
<!-- end: Bootstrap Select Master -->

<!-- start: Bootstrap timepicker -->
<!--{!! Html::style('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}-->
<!-- end: Bootstrap timepicker -->

<!-- Start: NEW timepicker css -->
{!! Html::style('css/bootstrap-timepicker.min.css') !!}
<!-- End: NEW timepicker css -->

<!-- start: Full Calendar -->
{!! Html::style('plugins/fullcalendar-2.9.1/fullcalendar.min.css') !!}
<!-- end: Full Calendar -->

<!-- start: Sweet Alert -->
{!! Html::style('plugins/sweetalert/sweet-alert.css') !!} 
<!-- end: Sweet Alert -->

{!! Html::style('css/custom.css?v='.time()) !!}

@stop()

@if(!isset($subview))
  @section('title')
     Calendar jkdhfjkah
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

  @if(!isset($subview))
    <!-- start: Appoinment Cancel Modal -->
    @include('partials.appointment_cancel_modal', ['reasons' => $reasons])
    <!-- end: Appoinment Cancel Modal -->
    <!-- start: HIDDEN FIELD FOR DATA FATCH CALENDAR SETTING -->
      <input type='hidden' name='calendarSettingVal' value ='{{ json_encode(array(
      "id" => 53,
      "cs_first_day" => "3",
      "cs_start_time" => "07:00:00",
      "cs_business_id" => 30,
      "cs_intervals" => "15",
      "cs_view" => "monthly",
      "cs_display_calendar" => 1,
      "cs_initial_status" => "Pencilled-In",
      "cs_add_company_name" => 1,
      "cs_allow_appointments" => 0,
      "cs_receive_email_summary" => 1,
      "created_at" => "2017-06-05 05:05:31",
      "updated_at" => "2017-06-05 12:38:10",
      "deleted_at" => null)) }}' >

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
  @include('partials.class_modal_client', ['modalLocsAreas' => $modalLocsAreas, 'eventRepeatIntervalOpt' => $eventRepeatIntervalOpt])
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
            Add Booking
          </a>
        </li>
       <!-- <li>
          <a href="#" class="eventAdd" data-target-modal="busyModal">
            SignUp for a class
          </a>
        </li>-->
     
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

  @if(isset($cl))
    {!! Form::hidden('defaultClient', htmlentities($cl), ['autocomplete' => 'off']) !!}
  @else
    {!! Form::hidden('clientsDetails', htmlentities($clients), ['autocomplete' => 'off']) !!}
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

@section('required-script')
  {!! Html::script('js/jquery-ui.min.js?v='.time()) !!}

  {!! Html::script('plugins/moment/moment.min.js') !!}

    <!-- start: jquery validation -->
   {!! Html::script('plugins/jquery-validation/jquery.validate.min.js') !!}
    <!-- end: jquery validation -->

    <!-- start: Bootstrap Select Master -->
    {!! Html::script('plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
    <!-- end: Bootstrap Select Master -->

    <!-- start: Bootstrap timepicker -->
   {!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
    <!-- end: Bootstrap timepicker -->
    
    <!-- Start:  NEW timepicker js -->
    {!! Html::script('js/bootstrap-timepicker.js') !!}
    <!-- End: NEW timepicker js --> 

    <script src="http://192.168.0.50/crm/public/assets/js/main.js"></script>


    <!-- start: Country Code Selector -->
    {!! Html::script('js/utils.js?v='.time()) !!}
    {!! Html::script('js/intlTelInput.js?v='.time()) !!}
    <!-- end: Country Code Selector -->

    <!-- start: Bootstrap Typeahead -->
    {!! Html::script('js/bootstrap3-typeahead.min.js') !!}
    <!-- end: Bootstrap Typeahead -->

    <!-- start: Full Calendar -->
    {!! Html::script('plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
    <!-- end: Full Calendar -->

    <!-- start: Sweet Alert -->
   {!! Html::script('plugins/sweetalert/sweet-alert.min.js') !!} 
    <!-- end: Sweet Alert -->

    {!! Html::script('js/helper.js?v='.time()) !!}

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
    {!! Html::script('js/events-client.js?v='.time()) !!}
    <!-- end: Events -->

    <!-- start: Full Calendar Custom Script -->
    {!! Html::script('js/calendar.js?v='.time()) !!}
    <!-- end: Full Calendar Custom Script -->
@stop()

@section('script-handler-for-this-page')
@stop()

@section('script-after-page-handler')
@stop()