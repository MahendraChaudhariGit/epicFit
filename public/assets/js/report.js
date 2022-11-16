var public_url = $('meta[name="public_url"]').attr('content'),
		calendar = popoverContainer;
		calendarFirstTime = true;

$(document).ready(function(){
		/* Initialize the calendar */
		calendar.fullCalendar({
			/* Set custom buttons for header */
			customButtons: {
				add: {
					text: ''
				},
				filter: {
					text: ''
				},
				titleDatepicker: {
					text: '',
					click: function(){
						$('#datepicker').datepicker("show");
					}
				},
				jumpBack: {
					icon: 'left-double-arrow',
					click: function(e){
						e.stopPropagation();
						$("#jumpBack .calJumper > a").dropdown("toggle");
					}
				},
				jumpforw: {
					icon: 'right-double-arrow',
					click: function(e){
						e.stopPropagation();
						$("#jumpforw .calJumper > a").dropdown("toggle");
					}
				}
			},

			/* Set header */
			header: {
				left: '', 
				center: 'prev,jumpBack,today,title,titleDatepicker,jumpforw,next',
				right: 'agendaDay,agendaWeek,month'
			},

			/* Set format and text for header and buttons */
			buttonText: {
				today: 'Today',
				day: 'Day',
				week: 'Week',
				month: 'Month'
			},

			defaultView: (Main.isMobileFn())?'agendaDay':'agendaWeek',

			/* Indicate Current Time */
			nowIndicator: true, 

			allDaySlot: false,

			firstDay: 1,

			/* Control interval between time slot in week view and day view */
			slotLabelInterval: '01:00',
			slotDuration: '00:15:00',
			slotLabelFormat: 'h:mma',

			/* Default initial time */
			scrollTime: '09:00:00',

			/* Calendar Events */
			editable: false,
			eventClick: function(calEvent, jsEvent, view){
			
			},
			eventRender: function(event, element, view){ 
				var contentElem = element.find(".fc-content"),
				eventHtml = '',
				iconHtml = '';
				
				if(event.status == "attended")
						iconHtml += '<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Attended"><i class="fa fa-check"></i></span>';
				else if(event.status == "unattended")
						iconHtml += '<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Not attended"><i class="fa fa-thumbs-down"></i></span>';

				else if(event.status == "edited")
						iconHtml += '<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+event.notes+'"><i class="fa fa-edit"></i></span>';

				if(iconHtml)
						eventHtml += '<div class="eventStatusIcon">'+iconHtml+'</div>';

				var eventTitle = event.title;
				
					eventHtml +=      
					'<strong>' +
						moment(event.start).format("h:mm A") + ' - '+ moment(event.end).format("h:mm A") + 
					'</strong><br/>';

				contentElem.addClass('calendEvent colorclass').html(eventHtml);

				if(iconHtml)
					contentElem.find('.epic-tooltip').tooltipster({maxWidth:280});

				element.addClass('calendEventWrap');
			},

			/* Set format for header and column in views */
			views: {
				month: { 
					titleFormat: 'MMM YYYY'
				},
				agendaWeek: { 
					titleFormat: 'MMM D, YYYY',
					columnFormat: 'ddd D MMM'
				},
				agendaDay: {
					titleFormat: 'ddd, MMM D, YYYY'
				}
			},

			viewRender: function(view, element){
				ifToday(view);

				//alert("abc");
				var datepicker = calendar.find('#datepicker');
				if(datepicker.length)
					datepicker.val(view.intervalStart.format('MM')+'/'+view.intervalStart.format('DD')+'/'+view.intervalStart.format('YYYY'));

				//console.log(datepicker.val());

				if(!calendarFirstTime)
					getAjaxData();
					
				if(view.type == 'agendaDay'){
					dayviewcolText(element);

					slotsDispTime(element, 'day', view.intervalStart);
					
				}
				else if(view.type == 'agendaWeek'){
					weekviewcolLink(element);

					slotsDispTime(element, 'week');
				}

				else if(view.type == 'month'){
					element.find('.fc-today').addClass('today');
				}
				
			},
			eventAfterAllRender: function(view){
				
				var titleDatepicker = calendar.find('.fc-titleDatepicker-button');
				titleDatepicker.html(calendar.find('h2').text());

				titleDatepicker.closest('div').addClass('fc-date-picker-btn');

				/*if(!titleDatepicker.hasClass('btn-wide'))
					titleDatepicker.addClass('btn-wide')*/

				if(calendar.find('.fc-toolbar .well').length <= 0){
					var html = '<div class="well mnh-75"><h4 class="calendar-panel-title text-left"> Total Projected Hours: <span class="text-bold" id="totalProjectedhours">  </span> <br>Total Working Hours: <span class="text-bold" id="totalhours">  </span> <br> Total Working Payment: <span class="text-bold" id="totalStaffPayment"></h4> </div>';
					calendar.find('.fc-toolbar').append(html);
				}

				/* Intergrate datepicker into calendar and Initialize the datepicker */
				if(!calendar.find('#datepicker').length){
					$('<input type="hidden" id="datepicker">').insertBefore(titleDatepicker);
					$('#datepicker').datepicker({
						numberOfMonths: 2,
						onSelect: function (dateText, inst){
							calendar.fullCalendar('gotoDate', new Date(dateText));
						}
					});
				}

				/* Intergrate calendar jumper into calendar */
				if(!calendar.find('.calJumper').length){
					var jumper = $(".calJumper");
					var jumperHtml = jumper.prop('outerHTML');
					$('<div id="jumpBack">'+jumperHtml+'</div>').insertBefore(".fc-jumpBack-button");
					$('<div id="jumpforw">'+jumperHtml+'</div>').insertBefore(".fc-jumpforw-button");
					jumper.remove();
					$(".fc-jumpBack-button").attr({
						"rel": "tooltip",
						"title": "Jump back"
					}).addClass('epic-tooltip')
					$(".fc-jumpforw-button").attr({
						"rel": "tooltip",
						"title": "Jump forward"
					}).addClass('epic-tooltip')
				}

				/* Intergrate filter into calendar */
				if(!calendar.find('.a').length)
					$('.staff-filter-cal').eq(0).appendTo(calendar.find('.fc-left'))	
			}
		});

		/* Initializing jquery validation */
		initCustomValidator();

		/* Event handler to jump calendar from jumper */
		$('body').on('click', '.calJumper .dropdown-menu a', function(e){
			e.preventDefault();
			var jumpAmount = $(this).data('jump-amount'),
				jumpUnit = $(this).data('jump-unit'),
				operation = $(this).closest('.calJumper').parent().attr('id'),
				datepicker = calendar.find('#datepicker'),
				datepickerVal = datepicker.val();

			if(datepicker.length && datepickerVal != '' && datepickerVal != null)
				var momentt = moment(datepickerVal, "MM/DD/YYYY");
			else
				var momentt = moment();
			
			if(operation == 'jumpforw')
				calendar.fullCalendar('gotoDate', momentt.add(jumpAmount, jumpUnit))
			else if(operation == 'jumpBack')
				calendar.fullCalendar('gotoDate', momentt.subtract(jumpAmount, jumpUnit))
		})

		/* Event handler to switch to day view from week view */
		$('body').on('click', '#calendar .fc-agendaWeek-view .fc-day-header a', function(e){
			e.preventDefault();
			
			calendar.fullCalendar('changeView', 'agendaDay')
			calendar.fullCalendar('gotoDate', $(this).parent().data('date'))
		})

		/* Event handler to switch to week view from day view */
		$('body').on('click', '#calendar .fc-agendaDay-view .fc-day-header a', function(e){
			e.preventDefault();
			calendar.fullCalendar('changeView', 'agendaWeek')
		})

		getAjaxData();
		calendarFirstTime = false;

});

function getAjaxData(){

		calendar.fullCalendar('removeEvents');
		var totalHours = 0;
		var totalMinutes = 0;
		var totalProjectedHours = 0;
		var totalProjectedMins = 0;
		
		
	    //var month = $('.fc-titleDatepicker-button').text().split(' ');
	    datepicker = calendar.find('#datepicker').val();

	    
	    //console.log(datepicker);
	    var formData = {};
	   	formData['staffId'] = $('select#stafflist').val();
	   	formData['view'] =	calendar.fullCalendar('getView').name;

	   	if(datepicker)
	    	formData['staffMonth'] = moment(datepicker).format("YYYY-MM-DD")//month[0];
	    $.get(public_url+'settings/business/staffs/report/'+formData['staffId'], formData, function(response){
	        var data = JSON.parse(response),
	    		events = [],
	    		hourly_rate = (data.hourlyRate!=null?data.hourlyRate:0),
	    		event_payment = (data.eventPayment!=null?data.eventPayment:0),
	    		totalHourRate = 0;

	    	if(data.attendence.length){
	    		$.each(data.attendence, function(key,value){
	    			//console.log(value);
	    			var editedOrExistingStartTime = (value.editedStartTime==null?value.startTime:value.editedStartTime);
					var editedOrExistingEndTime = (value.editedEndTime==null?value.endTime:value.editedEndTime);

					var realstartTime=moment(editedOrExistingStartTime, "HH:mm:ss");
					var realendTime=moment(editedOrExistingEndTime, "HH:mm:ss");
					var duration = moment.duration(realendTime.diff(realstartTime));
					totalProjectedHours += duration.hours(); 
					totalProjectedMins += duration.minutes();
					
					if(value.status == 'attended' || value.status == 'edited'){
						totalHours += duration.hours(); 
						totalMinutes += duration.minutes();
                    }
	    			events.push({id:value.recordId, title:editedOrExistingStartTime+' - '+editedOrExistingEndTime, start:value.staffDate+' '+editedOrExistingStartTime, end:value.staffDate+' '+editedOrExistingEndTime, date:value.staffDate, notes:value.notes, editedstarttime:value.editedStartTime, editedendtime:value.editedEndTime, status:value.status,starttime:value.startTime, endtime:value.endTime});

	    		});
			}
			console.log(totalProjectedHours,totalProjectedMins,totalHours, totalMinutes);
	    	if(events.length && events[0] != '')
					calendar.fullCalendar('addEventSource', events);

			if(totalProjectedMins >= 60){
				var remainder = totalProjectedMins%60;
				var quotient = (totalProjectedMins - remainder)/60;
				totalProjectedHours = totalProjectedHours+quotient;
				//totalHours = totalHours+(totalMinutes/60);
				totalProjectedMins = remainder;
			}
			
			if(totalMinutes >= 60){
				var remainder = totalMinutes%60;
				var quotient = (totalMinutes - remainder)/60;
				totalHours = totalHours+quotient;
				//totalHours = totalHours+(totalMinutes/60);
				totalMinutes = remainder;
			}
			var payHour = totalHours + (totalMinutes/60);
			totalHourRate = (parseFloat(payHour) * parseFloat(hourly_rate)) + event_payment;
			console.log(totalHourRate,hourly_rate,event_payment);
			$('#totalProjectedhours').html(totalProjectedHours+" Hours "+ totalProjectedMins+" Minutes " );
			$('#totalhours').html(totalHours+" Hours "+ totalMinutes+" Minutes " );
			$('#totalStaffPayment').html('$' + totalHourRate);


			
	    });
	    //formData['getEventsFrom'] = getCalendStartDate();
		//formData['getEventsUpto'] = getCalendEndDatePriorDate();
}



/* Function to convert text into link for week view and highlight today date header */
function weekviewcolLink(currView){
		var momentt = moment(),
			todayDate = momentt.format('YYYY')+'-'+momentt.format('MM')+'-'+momentt.format('DD'),
			header = currView.find('.fc-day-header');

		header.each(function(index, elem){
			var $elem = $(elem);
			if(todayDate == $elem.data('date'))
				$elem.addClass('today');
			coltextHelperFunc($elem, $elem.html())
		})
	}

/* Function to display time in each main time slot of view */
function slotsDispTime(element, view, tstamp){
		var rows = $(element).find('.fc-slats tr');

		rows.each(function(index, elem){
			var $elem = $(elem),
				text = moment($elem.data("time"), ["HH:mm:ss"]).format("h:mma");

			if(view == 'week')
				$elem.children(":last").html('<div class="fc-slot-times"><div class="fc-slot-time" data-day="Monday">'+text+'</div><div class="fc-slot-time" data-day="Tuesday">'+text+'</div><div class="fc-slot-time" data-day="Wednesday">'+text+'</div><div class="fc-slot-time" data-day="Thursday">'+text+'</div><div class="fc-slot-time" data-day="Friday">'+text+'</div><div class="fc-slot-time" data-day="Saturday">'+text+'</div><div class="fc-slot-time" data-day="Sunday">'+text+'</div></div>');
			else if(view == 'day')
				$elem.children(":last").html('<div class="fc-slot-times"><div class="fc-slot-time" data-day="'+tstamp.format('dddd')+'">'+text+'</div>');
		})
	}

function coltextHelperFunc($elem, text){
		$elem.html('<a href="#">'+text+'</a>');
		//$elem.find('.epic-tooltip').tooltipster();
	}

/* Function to check if current view contains today date and toggle today button */
function ifToday(view){
		var startTimestamp = view.intervalStart.format('x'),
			emdTimestamp = view.intervalEnd.format('x'),
			currentTimestamp = moment().format('x'),
			todayBtn = calendar.find('.fc-today-button');

		if(currentTimestamp >= startTimestamp && currentTimestamp <= emdTimestamp)
			todayBtn.addClass('active');
		else
			todayBtn.removeClass('active');
	}

/* Function to change column text of day view */
function dayviewcolText(currView){

		var selStaff = $('select.staff-filter-cal option:selected').text();
		coltextHelperFunc(currView.find('.fc-day-header'), selStaff)
	}

function getCalendRawStartDate(){
		return calendar.fullCalendar('getView').start;
	}

function getCalendStartDate(){
		return getCalendRawStartDate().format('YYYY-MM-DD');
	}

function getCalendRawEndDate(){
		return calendar.fullCalendar('getView').end;
	}

function getCalendEndDatePriorDate(){
		var endDate = getCalendRawEndDate().clone();
		return endDate.subtract(1, 'days').format('YYYY-MM-DD');
	}


$('body').on('change', 'select#stafflist', function(){
	
	//var staffid = $(this).val();
	getAjaxData();
	//console.log($('select#stafflist').val());


});

