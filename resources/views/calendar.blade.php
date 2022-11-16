@extends('app')

@section('style')
<link rel="stylesheet" href="{{ asset('co/assets/css/pages/calendar.css') }}" />
 <!-- start: Bootstrap Select Master -->
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
    <!-- end: Bootstrap Select Master -->
@stop

@section('page-title')
    Calendar
@stop

@section('content')
<!-- start: content row -->
<div class="row">
    <div class="col-md-12">
        <!-- start: CALENDAR PANEL -->
        <div class="panel panel-default"><!--id="epic-accordionn"-->
            <!-- start: PANEL HEADING -->
            <div class="panel-heading">
                <h5 class="panel-title">
                    <span class="icon-group-left">
                        <i class="fa fa-calendar"></i>
                    </span>
                    EPIC Diary
                    <span class="icon-group-right">
                        <a class="btn btn-xs panel-refresh pull-right" href="#">
                            <i class="fa fa-refresh"></i>
                        </a>
                        <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <a class="btn btn-xs pull-right panel-collapse" href="#">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </span>
                </h5>
            </div>
            <!-- start: PANEL HEADING -->
            <!-- start: PANEL BODY -->
            <div class="panel-body">
                <!-- start: CALENDAR HEADING ROW -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <h3 class="color-red" style="margin-top: 0px;">YOUR EPIC WEEKLY DIARY</h3>
                            </div> <!-- end col4 -->
                            <div class="col-md-4">
                                <p>Weekly Income: <span class="color-red underline">$</span><span id="weekly-total-income" class="color-red underline"></span></p>
                            </div> <!-- end col4 -->
                            <div class="col-md-4">
                                <p>Choose working hours and working days</p>
                            </div> <!-- end col4 -->
                        </div> <!-- end row -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="hourly-rate-div">
                                    <p>Choose half hourly rate:
                                        <span class="color-red underline half-hourly-rate-show">${{ $half_hour_rate }}</span>
                                        <span class="color-red underline half-hourly-edit-icon"> <i class="fa fa-pencil"></i></span>
                                    <span class="half-hourly-input-span"><input type="text" value="{{ $half_hour_rate }}" id="half-hourly-rate" class="half-hourly-rate" oninput="restrict('half-hourly-rate')">
                                        <span class="half-hourly-done-icon btn btn-success"> <i class="fa fa-check"></i></span>
                                    </span>
                                    </p>
                                </div><!-- end hourly-rate-div -->
                            </div> <!-- end col4 -->
                            <div class="col-md-4">
                                <p>Actual Hourly Rate: <span class="color-red">$<span id="actual-hourly-rate" class="color-red"></span></span></p>
                            </div> <!-- end col4 -->
                            <div class="col-md-4">
                                <p>Half hour days or full hour days</p>
                            </div> <!-- end col4 -->
                        </div> <!-- end row -->
                        {{--<div class="row">--}}
                            {{--<div class="col-md-4 form-inline select-start-input">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="start-time">Start Time</label>--}}
                                        {{--<select name="start-time" id="start-time" class="form-control">--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                            {{--</div> <!-- end col4 -->--}}

                            {{--<div class="col-md-4 form-inline">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="end-time">End Time</label>--}}
                                        {{--<select name="end-time" id="end-time" class="form-control">--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                            {{--</div><!-- end col-4 -->--}}
                        {{--</div><!-- end row -->--}}
                    </div>
                </div>
                <!-- end: CALENDAR HEADING ROW -->
                <br><br>
                <!-- start: CALENDAR CONTENT ROW -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <h4>Events</h4>
                            <div id="event-categories">
                                <div class="event-category label-pts ui-draggable" data-class="data-pts" style="position: relative;">
                                    <i class="fa fa-move"></i> <i class="fa fa-circle circle-pts"></i>
                                    PTS
                                </div>
                                <div class="event-category label-default label-baf ui-draggable" data-class="data-baf">
                                    <i class="fa fa-move"></i> <i class="fa fa-circle circle-baf"></i>
                                    BAF
                                </div>
                                <div class="event-category label-purple label-bcm ui-draggable" data-class="data-bcm">
                                    <i class="fa fa-move"></i> <i class="fa fa-circle circle-bcm"></i>
                                    BCM
                                </div>
                                <div class="event-category label-orange label-ots ui-draggable" data-class="data-ots">
                                    <i class="fa fa-move"></i> <i class="fa fa-circle circle-ots"></i>
                                    OTS
                                </div>
                                <div class="event-category label-yellow label-rar ui-draggable" data-class="data-rar">
                                    <i class="fa fa-move"></i> <i class="fa fa-circle circle-rar"></i>
                                    RAR
                                </div>
                            </div><!-- end event-categories -->
                        </div><!-- end row -->
                        <div class="row">
                            <div class="session-section table-weekly-diary-summery">
                                <div class="training-session training-pts">
                                    <h5>Personal training session</h5>
                                    <p>
                                        <span class="session-span-one">Pts</span>
                                        <span class="total-pts-hrs">47</span>
                                        <span class="session-span-three">Hours</span>
                                    </p>
                                </div><!-- end training-session -->
                                <div class="training-session">
                                    <h5>Business Admin & Finances</h5>
                                    <p>
                                        <span class="session-span-one">Baf</span>
                                        <span class="total-baf-hrs">10</span>
                                        <span class="session-span-three">Hours</span>
                                    </p>
                                </div><!-- end training-session -->
                                <div class="training-session">
                                    <h5>Business Communication & Marketing</h5>
                                    <p>
                                        <span class="session-span-one">Bcm</span>
                                        <span class="total-bcm-hrs">6</span>
                                        <span class="session-span-three">Hours</span>
                                    </p>
                                </div><!-- end training-session -->
                                <div class="training-session">
                                    <h5>Own Training Sessions</h5>
                                    <p>
                                        <span class="session-span-one">Ots</span>
                                        <span class="total-ots-hrs">3</span>
                                        <span class="session-span-three">Hours</span>
                                    </p>
                                </div><!-- end training-session -->
                                <div class="training-session">
                                    <h5>Rest and Recreation</h5>
                                    <p>
                                        <span class="session-span-one">Rar</span>
                                        <span class="total-rar-hrs">70.3</span>
                                        <span class="session-span-three">Hours</span>
                                    </p>
                                </div><!-- end training-session -->
                            </div><!-- end session-section -->
                        </div><!-- end row -->
                        <div class="row">
                            <h4>Basic</h4>
                            <div class="progress basic-progress">
                                <div class="progress-bar progress-bar-success progress-bar-pts" role="progressbar" aria-valuenow="40"
                                     aria-valuemin="0" aria-valuemax="100" style="width:40%">
                                </div>
                            </div>
                            <div class="progress basic-progress">
                                <div class="progress-bar progress-bar-info progress-bar-baf" role="progressbar" aria-valuenow="50"
                                     aria-valuemin="0" aria-valuemax="100" style="width:50%">
                                </div>
                            </div>
                            <div class="progress basic-progress">
                                <div class="progress-bar progress-bar-success progress-bar-bcm" role="progressbar" aria-valuenow="40"
                                     aria-valuemin="0" aria-valuemax="100" style="width:40%">
                                </div>
                            </div>
                            <div class="progress basic-progress">
                                <div class="progress-bar progress-bar-warning progress-bar-ots" role="progressbar" aria-valuenow="60"
                                     aria-valuemin="0" aria-valuemax="100" style="width:60%">
                                </div>
                            </div>
                            <div class="progress basic-progress">
                                <div class="progress-bar progress-bar-danger progress-bar-rar" role="progressbar" aria-valuenow="70"
                                     aria-valuemin="0" aria-valuemax="100" style="width:70%">
                                </div>
                            </div>
                        </div><!-- end row -->
                    </div> <!-- end col3 -->
                    <div class="col-md-9">
                        <div class="calendar"></div>
                        <button class="pull-right btn btn-success btn-save-events">Save</button>
                    </div> <!-- end col9 -->
                </div>
                <!-- end: CALENDAR CONTENT ROW -->
                <!-- start: CALENDAR FOOTER ROW -->
                <div class="row">
                    {{--<table class="col-md-8 table-weekly-diary-summery">--}}
                        {{--<tr>--}}
                            {{--<td>Personal Training Session</td>--}}
                            {{--<td>PTS</td>--}}
                            {{--<td class="total-pts-hrs">00</td>--}}
                            {{--<td>hrs.</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>Business Admin & Finances</td>--}}
                            {{--<td>BAF</td>--}}
                            {{--<td class="total-baf-hrs">00</td>--}}
                            {{--<td>hrs.</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>Business Communication & Marketing</td>--}}
                            {{--<td>BCM</td>--}}
                            {{--<td class="total-bcm-hrs">00</td>--}}
                            {{--<td>hrs.</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>Own Training Sessions</td>--}}
                            {{--<td>OTS</td>--}}
                            {{--<td class="total-ots-hrs">00</td>--}}
                            {{--<td>hrs.</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>Rest and Recreation</td>--}}
                            {{--<td>RAR</td>--}}
                            {{--<td class="total-rar-hrs">00</td>--}}
                            {{--<td>hrs.</td>--}}
                        {{--</tr>--}}
                    {{--</table>--}}
                </div>
                <!-- end: CALENDAR FOOTER ROW -->

            </div>
            <!-- start: PANEL BODY -->
        </div>
        <!-- start: CALENDAR PANEL -->
    </div>
</div>

<!-- MODAL FOR CALENDAR SELECT EVENTS -->
<div class="modal fade" id="event-management" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" aria-hidden="true" data-dismiss="modal" type="button"> × </button>
                    <h4 class="modal-title">Event Management</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">New Event Name</label>
                                    <input class="form-control" type="text" name="title" placeholder="Insert Event Name">
                                </div>
                            </div>
                            <div class="col-smm-6">
                                <div class="form-group">
                                    <label class="control-label">Select an event</label>
                                    <select class="form-control" name="category">
                                        <option value="data-pts">PTS</option>
                                        <option value="label-green">Home</option>
                                        <option value="label-purple">Holidays</option>
                                        <option value="label-orange">Party</option>
                                        <option value="label-yellow">Birthday</option>
                                        <option value="label-teal">Generic</option>
                                        <option value="label-beige">To Do</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light-grey" data-dismiss="modal" type="button"> Close </button>
                    <button class="btn btn-danger remove-event no-display" type="button" style="display:none;">
                        <i class="fa fa-trash-o"></i>
                        Delete Event
                    </button>
                    <button class="btn btn-success save-event" type="submit">
                        <i class="fa fa-check"></i>
                        Save
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@stop

@section('script')
    <script src="{{ asset('co/assets/js/pages/calendar.js').'?v='.time()}} }}" ></script>
    <!-- start: Moment Library -->
    <!-- {!! Html::script('vendor/moment/moment.min.js') !!} -->
    <!-- end: Moment Library -->
    <!-- start: Bootstrap Select Master -->




    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}

    <!-- end: Bootstrap Select Master -->
    <!-- start: helper js -->
    {!! Html::script('assets/js/helper.js') !!}
    <!-- end: helper js -->
    <script>
        /*$(document).ready(function() {
            var epicAccordianElem = $('#epic-accordion');
            epicAccordianElem.find('.panel-body').css({'display':'none'});

            epicAccordianElem.find('.panel-heading .fa-chevron-down', '.panel-heading .fa-chevron-up').on('click', function() {
                toggleSections(this);
            });
            toggleSections(epicAccordianElem.find('.panel-heading .fa-chevron-down')[0]);
        });

        function toggleSections(elem){
            var pclass = $(elem).attr('class');
            if(pclass == 'fa fa-chevron-up pull-right')
                $(elem).attr('class', 'fa fa-chevron-down pull-right');
            else if(pclass == 'fa fa-chevron-down pull-right')
                $(elem).attr('class', 'fa fa-chevron-up pull-right');
            var self = $(elem).closest('.panel-heading');
            self.closest('.panel').find('.panel-body').slideToggle(400);
        }*/
    </script>
@stop