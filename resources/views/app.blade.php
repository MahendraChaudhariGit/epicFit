<!DOCTYPE html>
<!-- Template Name: Clip-One - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.4 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- start: HEAD --><head>
		<title>@yield('title', app_name())</title>
		<!-- start: META -->
		<meta charset="utf-8" />
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<meta name="public_url" content="{{ asset('') }}">
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">

		<!-- CSRF TOKEN FOR SENDING AJAX REQUEST -->


		<!-- end: META -->
		<!-- start: MAIN CSS -->
		{!! Html::style('vendor/bootstrap/css/bootstrap.min.css?v='.time()) !!}
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
		{!! Html::style('assets/fonts/style.css?v='.time()) !!}
		{!! Html::style('vendor/themify-icons/themify-icons.min.css?v='.time()) !!}
		{!! Html::style('vendor/animate.css/animate.min.css?v='.time(), ['media' => 'screen']) !!}
		{!! Html::style('vendor/perfect-scrollbar/perfect-scrollbar.min.css?v='.time(), ['media' => 'screen']) !!}
		{!! Html::style('vendor/switchery/switchery.min.css?v='.time(), ['media' => 'screen']) !!}
		{!! Html::style('vendor/tooltipster-master/tooltipster.css?v='.time()) !!}
		{!! Html::style('vendor/jquery-ui/jquery-ui-1.10.1.custom.css?v='.time()) !!}
		<!-- end: MAIN CSS -->

		<!-- start: CLIP-TWO CSS -->
		{!! Html::style('assets/css/styles-orange.css?v='.time()) !!}
		{!! Html::style('assets/css/clip-two/main-navigation.css?v='.time()) !!}
		{!! Html::style('assets/css/themes/theme-orange.css?v='.time()) !!}
		{!! Html::style('assets/css/custom-style.css?v='.time()) !!}
		<!-- end: CLIP-TWO CSS -->

		<!--<link rel="stylesheet" href="{{ asset('co/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/font-awesome/css/font-awesome.min.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/fonts/style.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/css/main.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/css/main-responsive.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/iCheck/skins/all.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css') }}">-->

		
    <link rel="stylesheet" href="{{ asset('co/assets/css/theme_light.css') }}" type="text/css" id="skin_color">
		<link rel="stylesheet" href="{{ asset('co/assets/css/print.css') }}" type="text/css" media="print"/>
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/fullcalendar/fullcalendar/fullcalendar.css') }}">
		<!--<link rel="stylesheet" href="{{ asset('co/assets/plugins/datepicker/css/datepicker.css') }}">-->
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/select2/select2.css') }}" />
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/DataTables/media/css/DT_bootstrap.css') }}" />
        
        <!--{!! Html::style('vendor/themify-icons/themify-icons.min.css') !!}-->
		<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />

		@yield('style')

		<!--[if IE 7]>
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/font-awesome/css/font-awesome-ie7.min.css') }}">
		<![endif]-->

		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->

		<link rel="shortcut icon" href="favicon.ico" />

		<style>

		</style>

		<!--<style type="text/css">
		.swMain > ul li > a.selected .stepNumber {
    		border-color: #ff4401;/*e85801 */
		}
		.swMain > ul li > a.selected:before, .swMain li > a.done:before {
    		border-color: #ff4401;
		}
		.swMain ul li > a.done .stepNumber {
    		border-color: #ff4401;
    		background-color: #ff4401;
    	}
		ul.main-navigation-menu > li.active > a {
    		background: #ff4401 !important;
		}
		.nav > li.dropdown .dropdown-toggle .badge {
    		background-color: #ff4401;
    		border: none;
		}
		ul.main-navigation-menu > li a > i {
		    color: #ff4401;
		    font-weight: normal;
		}
		ul.sub-menu li a{
			font-size:13px !important
		}
		.badge-new {
    		background-color: #ff4401;
		}

		.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    		color: #555555;
		}
		.form-control:focus {
    		border-color: #ddd;
    		box-shadow: none;
    	}
    	.pagination>li>a, .pagination>li>span {
		    color: #555555;
		}
    	.pagination>.active>a, .pagination>.active>span, .pagination>.active>a:hover, .pagination>.active>span:hover, .pagination>.active>a:focus, .pagination>.active>span:focus {
		    background-color: #555555;
		    border-color:#555555;

		}
		.pagination>li>a:hover, .pagination>li>span:hover, .pagination>li>a:focus, .pagination>li>span:focus {
    		color: #555555;
		}
    	@media (max-width: 767px){
    		#bodyImg {display: none;}
    		ul.epic-mobile-tab li{
    			width:100%;
    		}
    		.filter-clients {
    			margin-top: 15px;
    			width: 100%;
    			border-radius: 0px;
    		}
    		.navbar-tools {
    			margin-left: -45px;
    		}
    		/*
			.main-container {
		    	margin-top: 150px;
			}*/
		}
		@media (min-width: 768px){
			.form-horizontal #wizard .control-label {
				text-align: left;
			}
		}
		</style>-->

		{!! Html::script('vendor/jquery/jquery.min.js') !!}
        <!--<script src="http://192.168.0.50/crm-old-dont-use/public/co/assets/plugins/jQuery-lib/2.0.3/jquery.min.js"></script>-->
	</head>
	<!-- end: HEAD -->
	<!-- start: BODY -->
	<body>
		<div id="app" class="app-sidebar-closed a-s-c">
			<!-- side bar -->
			@include('layouts.includes.sidebar')

			<!-- start: APP CONTENT -->
			<div class="app-content">

				<!-- top nav bar -->
				@include('layouts.includes.navbar')

				<!-- start: MAIN CONTETN -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						@include('layouts.includes.page_title')

						@include('includes.partials.messages')

						@yield('content')
					</div>
				</div>
				<!-- end: MAIN CONTETN -->

			</div>
			<!-- end: APP CONTENT -->
			@include('layouts.includes.footer')


			<!--Start: Modal for upcoming task reminder -->
           		@include('includes.partials.reminder_modal');
        	<!--End: Modal for upcoming task reminder -->

        	<!-- Start: Modal for expried service -->
	           @include('includes.partials.expired_service_modal')
	        <!-- Start: Modal for expried service -->    

			
		</div>

		<div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title">Panel Configuration</h4>
					</div>
					<div class="modal-body">
						Here will be a configuration form
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
						<button type="button" class="btn btn-primary">
							Save changes
						</button>
					</div>
				</div>
			</div>
		</div>

		

		{!! Html::script('vendor/bootstrap/js/bootstrap.js?v='.time()) !!}
		{!! Html::script('vendor/jquery-cookie/jquery.cookie.js?v='.time()) !!}
		{!! Html::script('vendor/perfect-scrollbar/perfect-scrollbar.min.js?v='.time()) !!}
		{!! Html::script('vendor/switchery/switchery.min.js?v='.time()) !!}
		<!--
		{!! Html::script('vendor/bootstrap/js/tethr.js') !!}
		{!! Html::script('vendor/modernizr/modernizr.js') !!}
		{!! Html::script('vendor/selectFx/classie.js') !!}
		{!! Html::script('vendor/selectFx/selectFx.js') !!}
		-->

		{!! Html::script('assets/js/main.js?v='.time()) !!}
		
		
		<!-- <script src="{{ asset('co/assets/js/moment.js') }}"></script> -->
		{!! Html::script('vendor/moment/moment.min.js?v='.time()) !!}
		{!! Html::script('vendor/moment/moment-timezone-with-data.js?v='.time()) !!}
		{!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/moment-range/2.2.0/moment-range.min.js') !!}
		
		<script src="{{ asset('co/assets/js/jquery-ui.min.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/blockUI/jquery.blockUI.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/iCheck/jquery.icheck.min.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js').'?v='.time() }}"></script>
		<!--<script src="{{ asset('co/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js') }}"></script>-->
		<script src="{{ asset('co/assets/plugins/less/less-1.5.0.min.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js').'?v='.time() }}"></script>
		<!--<script src="{{ asset('co/assets/js/main.js') }}"></script>-->


		<script src="{{ asset('co/assets/plugins/jquery-validation/dist/jquery.validate.min.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/js/form-wizard.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/fullcalendar/fullcalendar/fullcalendar.js').'?v='.time() }}"></script>
		<!--<script src="{{ asset('co/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>-->
		<script src="{{ asset('co/assets/plugins/select2/select2.min.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/DataTables/media/js/jquery.dataTables.min.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/DataTables/media/js/DT_bootstrap.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/flot/jquery.flot.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/flot/jquery.flot.pie.js').'?v='.time() }}"></script>
		<!--<script src="{{ asset('co/assets/plugins/flot/jquery.flot.resize.min.js') }}"></script>-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.resize.min.js"></script>
		<script src="{{ asset('co/assets/plugins/jquery.sparkline/jquery.sparkline.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js').'?v='.time() }}"></script>
		<script src="{{ asset('co/assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js').'?v='.time() }}"></script>

		<script>
			var currTimeZone= '<?php echo (Session::has('timeZone'))?Session::get('timeZone'):''; ?>';

			jQuery(document).ready(function() {
				Main.init();

				var runFullCalendar = function () {
					/**
					 |----------------------------------------------------------------------------
					 | 01. initialize the calendar
					 |----------------------------------------------------------------------------
					 */
					var $modal = $('#event-management');
					$('#event-categories div.event-category').each(function () {
						// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
						// it doesn't need to have a start or end
						var eventObject = {
							title: $.trim($(this).text()) // use the element's text as the event title
						};
						// store the Event Object in the DOM element so we can get to it later
						$(this).data('eventObject', eventObject);
						// make the event draggable using jQuery UI
						$(this).draggable({
							zIndex: 999,
							revert: true, // will cause the event to go back to its
							revertDuration: 50 //  original position after the drag
						});
					});

					var date = new Date();
					var d = date.getDate();
					var m = date.getMonth();
					var y = date.getFullYear();

					var form = '';
					var calendar = $('.calendar').fullCalendar({
						buttonText: {
							prev: '<i class="fa fa-chevron-left"></i>',
							next: '<i class="fa fa-chevron-right"></i>'
						},
						header: {
							//left: 'prev,next today',
							left: null,
							center: 'title',
							//right: 'month,agendaWeek,agendaDay',
							right: null
						},
						defaultView: 'agendaWeek',  // Default view agenda-week
						firstHour: 6,
						minTime: 6,
						maxTime: 20,
						defaultEventMinutes: 30,
						slotMinutes: 30,
						allDaySlot: true,
						timezone: 'UTC',
						axisFormat: 'hh(:mm) TT',
						events: JSON.parse('<?= $formated_events; ?>'),
						editable: true,
						droppable: true, // this allows things to be dropped onto the calendar !!!
						drop: function (date, allDay) { // this function is called when something is dropped
							// retrieve the dropped element's stored Event Object
							var originalEventObject = $(this).data('eventObject');
							var $categoryClass = $(this).attr('data-class');
							// we need to copy it, so that multiple events don't have a reference to the same object
							var copiedEventObject = $.extend({}, originalEventObject);
							// assign it the date that was reported
							copiedEventObject.start = date;
							copiedEventObject.allDay = allDay;
							if ($categoryClass) {
								copiedEventObject['className'] = [$categoryClass];
								if( copiedEventObject['className'] == 'data-pts') {
									var halfHourRate = $('.half-hourly-rate').val().trim();
									copiedEventObject.title = copiedEventObject.title;
								}
							}
							// render the event on the calendar
							// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
							$('.calendar').fullCalendar('renderEvent', copiedEventObject, true);

							// update calendar events
							updateCalendarEvents('.calendar');

							// is the "remove after drop" checkbox checked?
							if ($('#drop-remove').is(':checked')) {
								// if so, remove the element from the "Draggable Events" list
								$(this).remove();
								updateCalendarEvents('.calendar');
							}
						},
						selectable: true,
						selectHelper: true,
						select: function (start, end, allDay) {
							$modal.modal({
								backdrop: 'static'
							});
							form = $("<form></form>");
							form.append("<div class='row'></div>");
							form.find(".row").append("<div class='col-md-8'><div class='form-group'><label class='control-label'>Select an event</label><select class='form-control' name='category'></select></div></div><div class='col-md-4'><div class='form-group'><label class='control-label'>Select event length</label><select class='form-control' name='event-length'></select></div></div>").find("select[name='category']").append("<option value='data-pts'>PTS</option>").append("<option value='data-baf'>BAF</option>").append("<option value='data-bcm'>BCM</option>").append("<option value='data-ots'>OTS</option>").append("<option value='data-rar'>RAR</option>");
							form.find("select[name='event-length']").append("<option value='0'>--Select length--</option>").append("<option value='30'>00:30 minutes</option>").append("<option value='60'>01:00 hour</option>").append("<option value='90'>01:30 hours</option>").append("<option value='120'>02:00 hours</option>").append("<option value='150'> 02:30 hours</option>").append("<option value='180'>03:00 hours</option>").append("<option value='210'>03:30 hours</option>").append("<option value='240'>04:00 hours</option>").append("<option value='270'>04:30 hours</option>").append("<option value='300'>05:00 hours</option>").append("<option value='330'>05:30 hours</option>").append("<option value='360'>06:00 hours</option>").append("<option value='390'>06:30 hours</option>").append("<option value='420'>07:00 hours</option>").append("<option value='450'>07:30 hours</option>").append("<option value='480'>08:00 hours</option>").append("<option value='510'>08:30 hours</option>").append("<option value='540'>09:00 hours</option>").append("<option value='570'>09:30 hours</option>").append("<option value='600'>10:00 hours</option>").append("<option value='630'>10:30 hours</option>").append("<option value='660'>11:00 hours</option>").append("<option value='690'>11:30 hours</option>").append("<option value='720'>12:00 hours</option>").append("<option value='750'>12:30 hours</option>").append("<option value='780'>13:00 hours</option>").append("<option value='810'>13:30 hours</option>").append("<option value='840'>14:00 hours</option>").append("<option value='870'>14:30 hours</option>").append("<option value='900'>15:00 hours</option>").append("<option value='930'>15:30 hours</option>").append("<option value='960'>16:00 hours</option>").append("<option value='990'>16:30 hours</option>").append("<option value='1020'>17:00 hours</option>").append("<option value='1050'>17:30 hours</option>").append("<option value='1080'>18:00 hours</option>").append("<option value='1110'>18:30 hours</option>").append("<option value='1140'>19:00 hours</option>").append("<option value='1170'>19:30 hours</option>").append("<option value='1200'>20:00 hours</option>").append("<option value='1230'>20:30 hours</option>").append("<option value='1260'>21:00 hours</option>").append("<option value='1290'>21:30 hours</option>").append("<option value='1320'>22:00 hours</option>").append("<option value='1350'>22:30 hours</option>").append("<option value='1380'>23:00 hours</option>").append("<option value='1410'>23:30 hours</option>").append("<option value='1440'>24:00 hours</option>");
							$modal.find('.remove-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function () {
								form.submit();
							});
							$modal.find('form').on('submit', function () {
								var evtLength = form.find("select[name='event-length'] option:checked").val().trim();
								evtLength = parseInt(evtLength);
								var title = form.find("select[name='category'] option:checked").val();
								title = title.replace('data-', '').toUpperCase().trim();
								var $categoryClass = form.find("select[name='category'] option:checked").val();

								var startTime = new Date(start).getTime();
								// Get end date from model drop down
								if( evtLength != NaN && evtLength != 0) {
									var endTime = startTime + ((evtLength * 60) * 1000);
									endTime = new Date(endTime);
								}

								if (title !== null) {
									calendar.fullCalendar('renderEvent', {
												title: title,
												start: start,
												end: endTime,
												allDay: allDay,
												className: $categoryClass
											}, true // make the event "stick"
									);

								}
								$modal.modal('hide');
								// update calendar events
								updateCalendarEvents('.calendar');

								return false;
							});
							calendar.fullCalendar('unselect');
						},
						eventClick: function (calEvent, jsEvent, view) {
							var form = $("<form></form>");
							form.append("<label>Select an event</label>");
							form.append("<div class='container'>" +
									"<div class='row'>" +
									"<div class='col-sm-6'><select class='form-control' name='update-category'></select></div>" +
									"<div class='col-sm-4'><select class='form-control' name='update-event-length'</select></div>" +
									"" +
									"</div></div>").find("select[name='update-category']").append("<option value='data-pts'>PTS</option>").append("<option value='data-baf'>BAF</option>").append("<option value='data-bcm'>BCM</option>").append("<option value='data-ots'>OTS</option>").append("<option value='data-rar'>RAR</option>");
							form.find("select[name='update-event-length']").append("<option value='0'>--Select length--</option>").append("<option value='30'>00:30 minutes</option>").append("<option value='60'>01:00 hour</option>").append("<option value='90'>01:30 hours</option>").append("<option value='120'>02:00 hours</option>").append("<option value='150'> 02:30 hours</option>").append("<option value='180'>03:00 hours</option>").append("<option value='210'>03:30 hours</option>").append("<option value='240'>04:00 hours</option>").append("<option value='270'>04:30 hours</option>").append("<option value='300'>05:00 hours</option>").append("<option value='330'>05:30 hours</option>").append("<option value='360'>06:00 hours</option>").append("<option value='390'>06:30 hours</option>").append("<option value='420'>07:00 hours</option>").append("<option value='450'>07:30 hours</option>").append("<option value='480'>08:00 hours</option>").append("<option value='510'>08:30 hours</option>").append("<option value='540'>09:00 hours</option>").append("<option value='570'>09:30 hours</option>").append("<option value='600'>10:00 hours</option>").append("<option value='630'>10:30 hours</option>").append("<option value='660'>11:00 hours</option>").append("<option value='690'>11:30 hours</option>").append("<option value='720'>12:00 hours</option>").append("<option value='750'>12:30 hours</option>").append("<option value='780'>13:00 hours</option>").append("<option value='810'>13:30 hours</option>").append("<option value='840'>14:00 hours</option>").append("<option value='870'>14:30 hours</option>").append("<option value='900'>15:00 hours</option>").append("<option value='930'>15:30 hours</option>").append("<option value='960'>16:00 hours</option>").append("<option value='990'>16:30 hours</option>").append("<option value='1020'>17:00 hours</option>").append("<option value='1050'>17:30 hours</option>").append("<option value='1080'>18:00 hours</option>").append("<option value='1110'>18:30 hours</option>").append("<option value='1140'>19:00 hours</option>").append("<option value='1170'>19:30 hours</option>").append("<option value='1200'>20:00 hours</option>").append("<option value='1230'>20:30 hours</option>").append("<option value='1260'>21:00 hours</option>").append("<option value='1290'>21:30 hours</option>").append("<option value='1320'>22:00 hours</option>").append("<option value='1350'>22:30 hours</option>").append("<option value='1380'>23:00 hours</option>").append("<option value='1410'>23:30 hours</option>").append("<option value='1440'>24:00 hours</option>").closest('.row').append("<div class='col-sm-2'><button type='submit' class='btn btn-success'><i class='fa fa-check'></i> Save</button></div>").closest('.container').css('border', 'none');
							$modal.modal({
								backdrop: 'static'
							});
							$modal.find('.remove-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.remove-event').unbind('click').click(function () {
								calendar.fullCalendar('removeEvents', function (ev) {
									return (ev._id == calEvent._id);
								});
								$modal.modal('hide');
								updateCalendarEvents('.calendar');
							});
							$modal.find('form').on('submit', function () {
								var className = form.find("select[name='update-category'] option:checked").val(),
										title = className.replace('data-', '').toUpperCase().trim();
								var updateEvtLength = form.find("select[name='update-event-length'] option:checked").val().trim();
								updateEvtLength = parseInt(updateEvtLength);

								calEvent.title = title;
								calEvent.className = className;

								var startTime = new Date(calEvent.start).getTime();
								// Get end date from model drop down
								if( updateEvtLength != NaN && updateEvtLength != 0) {
									var endTime = startTime + ((updateEvtLength * 60) * 1000);
									endTime = new Date(endTime);
								}
								calEvent.end = endTime;

								calendar.fullCalendar('updateEvent', calEvent);
								$modal.modal('hide');
								// update calendar events
								updateCalendarEvents('.calendar');
								return false;
							});
						},
						eventResize: function(event) {
							updateCalendarEvents('.calendar');
							calendar.fullCalendar('updateEvent', event);
						}

					});
				};

				var moment = require('moment');

				//TableData.init();
				FormWizard.init();

				runFullCalendar();

				$('.client-delete').on('click', function(e) {
					var client_id = $(this).attr('data-client-delete');
					if (client_id.length > 0) {

					};
				});

				$('#calendarDatepicker').datepicker();
				$('#calendarDatepicker').on("changeDate", function() {
					$('#myCalendarDatepicker').val(
							$('#datepicker').datepicker('getFormattedDate')
					);
				});
				//Index.init();
				jQuery("#mcfTosmc").click(function () {
					jQuery("#smc").toggle();
					jQuery("#mcf").toggle();
				});
			});
	

		var loggedInUser = {
	        //type: '{{ Session::get('userType') }}',
	        type: '{{ Auth::user()->account_type }}',
	        id: {{ Auth::user()->account_id }},
	        userId: {{ Auth::id() }},
	        name: '{{ Auth::user()->fullName }}'
    	};
    	

    		$(document).ready(function(){
                var businessExist= '<?php echo Session::has('businessId'); ?>';
                if(businessExist){
                    getUpcomingTasks();
                    getExpriedService();
                }
            })
		</script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/vue/1.0.15/vue.js"></script>
		{!! Html::script('assets/js/set-moment-timezone.js') !!} 
		@yield('script')
		<!-- <script type="text/javascript">
			var d = new Date();
			var n = d.getTime();
			$('script').each(function() {
				var attrSrc = $(this).attr("src");
				if(attrSrc != undefined && attrSrc != ''){
					var src = $.trim($(this).attr('src'));
					$(this).attr('src', src+'?v='+n);
				}
			});
		</script> -->
	</body>
</html>