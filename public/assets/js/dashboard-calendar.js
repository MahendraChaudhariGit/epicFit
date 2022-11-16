var public_url = $('meta[name="public_url"]').attr('content'),
		calendar = popoverContainer;
		calendarFirstTime = true;
		editEventPopoverOpt = {
			html: true,
			content: function(context, source){
				
				var categname = $(this).data('categname'),
					taskname = $(this).data('taskname'),
					authId = $(this).data('authId'),
					taskduetime = $(this).data('taskduetime'),
					isrepeat = $(this).data('taskreccur'),
					taskid = $(this).data('id'),
					taskstatus = $(this).data('status'),
					completedby = $(this).data('completedby'),
					taskduedate = $(this).data('taskduedate'),
					taskcategory = $(this).data('taskcategory'),
					remindhours = $(this).data('remindhours'),
					remindset = $(this).data('remindset'),
					taskuserid = $(this).data('taskuserid');
					var complete,incomplete,notrequired,remindSection;
					complete = incomplete = notrequired ='';
					//console.log(taskstatus);

					if(completedby){
						if(taskstatus =="complete"){
							complete = 'active';
						}
						else if(taskstatus =="not required"){
							notrequired = 'active';
						}
					}
					else{
						incomplete = 'active';
					}

					if(remindset)
						remindSection = 'data-remindhours="'+remindhours+'"  data-remindcheckbox="'+remindset+'"';
					else
						remindSection = "data-remindhours=''  data-remindcheckbox=''";

					if(isUserType(['Admin']) || currentUserId==taskuserid)
						var editdeletesection = '<div class="btn-toolbar"><a class="btn btn-primary btn-sm pull-left edittask" data-toggle="modal" data-target="#addtask" href="#"><i class="fa fa-edit"></i> Edit</a>  '+((isrepeat==1)?'<a class="btn delete-prompt " href="#" data-original-title="" title=""><i class="fa fa-trash-o"></i> Cancel</a>':'<a class="btn delLink " data-entity="task" href="'+public_url+'dashboard/'+taskid+'" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask" > <i class="fa fa-trash-o"></i> Cancel</a>')+' </div>';
					else
						var editdeletesection = '';
				
				return '<div class="eventPopover" data-task-id='+taskid+' data-task-name="'+taskname+'" data-auth-id="'+authId+'" data-task-duedate="'+taskduedate+'" data-task-time="'+taskduetime+'" data-task-categ="'+taskcategory+'" data-task-repeat="'+isrepeat+'" '+remindSection+' ><p><i class="fa fa-gear"></i> '+ categname +'</p><p><i class="fa fa-clock-o"></i> '+taskduetime+'</p> '+editdeletesection+' <div class="btn-group m-t-10"><a class="btn btn-primary btn-o calendarstatus '+complete+'" data-taskstatus="complete"><i class="fa fa-check"></i> Complete</a><a class="btn btn-primary btn-o calendarstatus '+incomplete+'" data-taskstatus="incomplete"><i class="fa fa-thumbs-down"></i> Incomplete</a><a class="btn btn-primary btn-o calendarstatus '+notrequired+'" data-taskstatus="not required"><i class="fa fa-exclamation"></i> Not required</a></div></div>';

			},
			trigger: 'click',
			placement: function(context, source){
				if($(source).closest('.fc-view').hasClass('fc-agendaDay-view'))
					return 'top';

				var pos = $(source).offset(),
					rightSpace = (calendar.width()-(pos.left-calendar.offset().left)-$(source).width()),
					popoverWidth = 400;

				if(rightSpace > popoverWidth)
					return 'right';
				return 'left';
			}, 
			container: calendar,
			title: function(){
				return '<strong>'+$(this).data('taskname')+'<button type="button" class="close m-t--10 popoverclose" aria-label="Close"><span aria-hidden="true">X</span></button></strong>';
        
			}
		};

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
				
				//$(this).popover(editEventPopoverOpt)
				
				calPopupHelper.removeClass('hidden');
			},
			eventRender: function(event, element, view){ 
				var contentElem = element.find(".fc-content"),
				eventHtml = '',
				iconHtml = '';

				
				
				/*if(event.status == "attended")
						iconHtml += '<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Attended"><i class="fa fa-check"></i></span>';
				else if(event.status == "unattended")
						iconHtml += '<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Not attended"><i class="fa fa-thumbs-down"></i></span>';

				else if(event.status == "edited")
						iconHtml += '<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+dbTimeToTimeString(event.starttime)+' - '+dbTimeToTimeString(event.endtime)+'"><i class="fa fa-edit"></i></span>';
				*/
				//iconHtml += '<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Attended"></span>';

				

				if(event.status=="complete"){
					var iconclass='<i class="fa fa-check"></i>';
					var eventtitle ='completed'; 
				}
				else if(event.status=="not required"){
					var iconclass ='<i class="fa fa-exclamation"></i>';
					var eventtitle ='not required';
				}
				else{
					var iconclass ='<i class="fa fa-thumbs-down"></i>';
					var eventtitle ='incomplete';
				}

				iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+eventtitle+'">'+iconclass+'</span>';

				var eventreccur = event.reccur;
				if(eventreccur){
				iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring task"><i class="fa fa-retweet"></i></span>';
				}


				if(iconHtml)
						eventHtml += '<div class="eventStatusIcon">'+iconHtml+'</div>';


				contentElem.append(eventHtml+'<strong><span class="eventTimeRange">'+event.taskduetime+'</span></strong><br>'+event.taskname+'');

					/*eventHtml +=      
					'<span class="epic-tooltip tooltipclass m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title='+event.categname+'><strong>' + moment(event.start).format("h:mm A") + '</strong>'+reccurclass+'</span>'+iconclass+'<br/>'+event.taskname+'';*/

				contentElem.addClass('calendEvent colorclass');//.html(eventHtml);
				element.popover(editEventPopoverOpt);
				element.data('id',event.id);
				element.data('taskname',event.taskname);
				element.data('status',event.status);
				element.data('authId',event.authId);
				element.data('taskduedate',event.taskduedate);
				element.data('taskduetime',event.taskduetime);
				element.data('taskreccur',event.reccur);
				element.data('taskcategory',event.taskcategory);
				element.data('categname',event.categname);
				element.data('completedby',event.completedby);
				element.data('remindset',event.remindset);
				element.data('remindhours',event.remindhours);
				element.data('taskuserid',event.taskuserid);
				
				if(iconHtml)
					contentElem.find('.epic-tooltip').tooltipster();

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

				var datepicker = calendar.find('#datepicker');
				if(datepicker.length)
					datepicker.val(view.intervalStart.format('MM')+'/'+view.intervalStart.format('DD')+'/'+view.intervalStart.format('YYYY'));

				if(!calendarFirstTime)
					getAjaxData();
					
				if(view.type == 'agendaDay'){
					dayviewcolText(element);

					slotsDispTime(element, 'day', view.intervalStart);

					markFreeDay(element.find('.fc-bg td.fc-day'))

					markWorkingSlot(element);
					
				}
				else if(view.type == 'agendaWeek'){
					weekviewcolLink(element);

					slotsDispTime(element, 'week');

					markFreeDay(element.find('.fc-bg td.fc-day'));

					markWorkingSlot(element);


				}

				else if(view.type == 'month'){
					if(!calendarFirstTime)
						markWorkingDay(element)
					element.find('.fc-today').addClass('today');
				}
				
			},
			eventAfterAllRender: function(view){
				if(view.type == 'month'){
					$('.fc-content-skeleton tr > td').unbind('click.addEventPopover').bind('click.addEventPopover', function(event){
						if(event.target.nodeName == 'TD'){
							var $this = $(this),
								row = $this.closest('.fc-row');

							if($this.hasClass('fc-event-container')){
								var date = $this.find('input[name="date"]').val();
									cell = row.find('.fc-bg td[data-date="'+date+'"]')
							}
							else{
								var index = $this.index()
									cell = row.find('.fc-bg td:eq('+index+')');
							}

							cell.trigger("click");
						}
					});
				}
				var titleDatepicker = calendar.find('.fc-titleDatepicker-button');
				titleDatepicker.html(calendar.find('h2').text());

				/*if(!titleDatepicker.hasClass('btn-wide'))
					titleDatepicker.addClass('btn-wide')*/

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
					$('.categ-filter-cal').eq(0).appendTo(calendar.find('.fc-left'))	
			}
		});
		

		/* Initializing jquery validation */
		initCustomValidator();

		/* Popup helper */
		//calPopupHelper.height($(document).height());
		calPopupHelper.click(function(){
			calendar.find('.calendEventWrap').popover('hide');

		if(calendar.find('.fc-month-view').length)
				calendar.find('.fc-day').popover('hide');
			
			else if(calendar.find('.fc-agendaWeek-view').length || calendar.find('.fc-agendaDay-view').length)
				calendar.find('.fc-slot-time').popover('hide');

			//resetEnableDisableDateFields();

			/*if(isSalesProcessMode() || addEventDateSelMode || bookNextAppointDateSelMode || resheduleAppointDateSelMode || changeEventDateDateSelMode){
				setTimeout(function(){ 
					calendar.find('.highlightCell').removeClass('highlightCell');
				}, 500);
			}*/
			//else
				setTimeout(function(){ 
					calendar.find('.highlightCell').removeClass('highlightCell');
				}, 500);
				//calendar.find('.highlightCell').removeClass('highlightCell');
		})

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

		$('body').on('change', 'select#categorylist', function(e){
			var isOccured = $('select#categorylist').data('categtype');
			if(isOccured == 1){
				$('select#categorylist').data('categtype',2);
				$('select#categorylist').selectpicker('refresh');
			}
		})

		getAjaxData();
		calendarFirstTime = false;

		

});

function getAjaxData(){
	toggleWaitShield("show", 'listTask');
	calendar.fullCalendar('removeEvents');
	datepicker = calendar.find('#datepicker').val();
	var formData = {};
	var catid =[];
	var isOccured = $('select#categorylist').data('categtype');
	if(isOccured == 1){
		$('select#categorylist option').attr('selected','selected');
	}

	catid = $('select#categorylist').val();
	$('select#categorylist').selectpicker('refresh');
	formData['categId'] = catid;
	formData['view'] =	calendar.fullCalendar('getView').name;
	formData['getEventsFrom'] = getCalendStartDate();
	formData['getEventsUpto'] = getCalendEndDatePriorDate();
	//if(datepicker)
	    	//formData['categMonth'] = moment(datepicker).format("YYYY-MM-DD");

	$.get(public_url+'dashboardcalendar/'+formData['categId'], formData, function(response){
	        var data = JSON.parse(response);
	    	var events = [];
	    	if(data.length){
	    		$.each(data, function(key,value){
	    			var remindsetData,remindhoursData;
	    			if(value.reminders && value.reminders[0]){
	    				remindsetData = value.reminders[0].tr_is_set;
	    				remindhoursData = value.reminders[0].tr_hours;
	    			}
	    			else{
	    				remindsetData = remindhoursData = '';
	    			}

	    			var eventStartDateTime = moment(value.task_due_date+' '+value.task_due_time);
	    			//var eventStartDate = moment(value.task_due_date);
	    			var eventStartTime = moment(value.task_due_time,'hh:mm:ss').format('h:mm A');

	    			events.push({id:value.id, start:eventStartDateTime, status:value.task_status, starttime:value.task_due_time, endtime:value.task_due_time , taskname:value.task_name, authId:value.task_user_id, reccur:value.is_repeating, taskcategory:value.task_category ,taskduedate:value.task_due_date , taskduetime:eventStartTime,completedby:value.completed_by,categname:value.category_name.t_cat_name,remindset:remindsetData,remindhours:remindhoursData,taskuserid:value.task_user_id});
	    			
	    		});
	    		//console.log(events);
	    	}
	    	if(events.length && events[0] != '')
				calendar.fullCalendar('addEventSource', events);	

			toggleWaitShield("hide", 'listTask');

	});
};

/* Function to mark working time slot in week and day view */
function markWorkingSlot(element){
		/*var hoursData = $('input[name="workingHours"]').val().split('|');
		element.find('.fc-slot-time.working').removeClass('working').unbind('click');
		if(hoursData.length && hoursData[0] != ''){
			$.each(hoursData, function(val, text){
				var hourData = text.split(','),
					rows = $(element).find('.fc-slats tr[data-time="'+hourData[0]+'"]').nextUntil('tr[data-time="'+hourData[1]+'"]').andSelf().add('tr[data-time="'+hourData[1]+'"]');

				rows.find('.fc-slot-time[data-day="'+hourData[2]+'"]').addClass('working')
			});
		}
		
		var enableDateUpto = enableDateFrom(element);
		disableDateFrom(element, enableDateUpto);
		closedDay(element);
		*/
		var eventEligibleSlot = element.find('.fc-slot-time').filter(function(){
			var $this = $(this),
				eventDate = getClickedDate($this, 'not month'),
				eventTime = $this.closest('tr').data('time');
				now = moment();

				if(moment(eventDate+' '+eventTime, 'YYYY-MM-DD HH:mm:ss').isSameOrAfter(now)){
					$this.addClass('working');
					return $this;
				}
				else
					$this.removeClass('working')
		});
		eventEligibleSlot.click(function(){
			//alert("alert1");
			$('#addtask').modal('show');
			dateClicked($(this), getClickedDate($(this), 'not month'))
		});

}

/* Function to mark working day in month view */
function markWorkingDay(element){
		/*var hoursData = $('input[name="workingHours"]').val().split('|'),
			cells = element.find('.fc-day:not(.notWorking)'),
			workingDays = [];

		markFreeDay(cells);
		cells.unbind('click');
		
		if(hoursData.length && hoursData[0] != ''){
			$.each(hoursData, function(val, text){
				var hourData = text.split(','),
					hoursDay = hourData[2].substring(0,3).toLowerCase();
				if(jQuery.inArray(hoursDay, workingDays) < 0)
					workingDays.push(hoursDay);
			});

			if(workingDays.length && workingDays[0] != ''){
				$.each(workingDays, function(val, text){
					element.find('.fc-'+text).removeClass('notWorking')
				});
			}
		}

		var enableDateUpto = enableDateFrom(element);
		disableDateFrom(element, enableDateUpto);

		var closedDates = $('input[name="closedDates"]').val();
		if(closedDates){
			closedDates = closedDates.split(',');
			var eventNotEligibleCell = element.find('.fc-day:not(.notWorking)').filter(function(){
				var $this = $(this),
					eventDate = $this.data('date');

				if($.inArray(eventDate, closedDates) !== -1)
					return $this;
			});
			eventNotEligibleCell.addClass('notWorking')
		}
		closedDay(element); */
		//:not(.notWorking)
		var eventEligibleCell = element.find('.fc-day').filter(function(){
			var $this = $(this),
				now = moment().format('YYYY-MM-DD');

			if(moment($this.data('date')).isSameOrAfter(now))
				return $this;
			else
				$this.addClass('notWorking')
		});
		eventEligibleCell.click(function(){
			//alert("alert2");
			$('#addtask').modal('show');
			dateClicked($(this), getClickedDate($(this), 'month'));
		});
}

/* Function to fetch date of the calendar slot clicked */
function getClickedDate(elem, view){
		if(view == 'month')
			return elem.data('date');
		else{
			var index = (elem.index())+1;
			return elem.closest('.fc-view').find('th').eq(index).data('date');
		}
}

/* Function to highlight free day */
function markFreeDay($elem){
	$elem.addClass('notWorking');
}

function dateClicked(elem, date){
		
		var time = elem.closest('tr').data('time');

		if(time == null || time == '')
			var datetime = date+' 09:00am';
		else
			var datetime = date+' '+time;

		//console.log(time);
		//console.log(datetime);
		$('input[name="selectedDatetime"]').val(datetime);
		elem.addClass('highlightCell')
		calPopupHelper.removeClass('hidden')
		
			calPopupHelper.trigger("click");
			setTimeout(function(){ 
				/*if(addEventDateSelMode || changeEventDateDateSelMode){
					if(eventModal == 'appointModal')
						showAppointmentModal('add');
					else if(eventModal == 'classModal')
						showClassModal('add');
					else
						showBusyModal('add');
				}
				else if(bookNextAppointDateSelMode)
					showAppointmentModal('edit'); */
				//console.log("settimeout");
				$('#addtask').modal('show');

				//resetAllModes();

			}, 500);
		
		/*else if(resheduleAppointDateSelMode){
			if(isSalesProcessEvent()){
				$('input[name="enableDateFrom"]').val('')
				$('input[name="enableDatePeriod"]').val('')
			}
			resetAllModes();
			calPopupHelper.trigger("click");

			var isRepeating = eventObj.find('input[name="isRepeating"]').val();

			if(isRepeating == 1){
				recurrAppointReschecModal.find('input[name="date"]').val(date);
				recurrAppointReschecModal.find('input[name="time"]').val(time);
				recurrAppointReschecModal.modal('show');
			}
			else
				rescheduleAppointment(date, time)
		}*/
		/*else if(isSalesProcessMode()){
			calPopupHelper.trigger("click");
			setTimeout(function(){ 
				//showAppointmentModal('add');
				showClassModal('add');
			}, 500);
		}*/
}

//end of extra functions

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

		var selStaff = $('select.categ-filter-cal option:selected').text();
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


$('body').on('change', 'select#categorylist', function(){
	getAjaxData();
});

