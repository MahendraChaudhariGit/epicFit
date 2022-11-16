var public_url = $('meta[name="public_url"]').attr('content'),
	calendar = $('#full-calendar'),
	clientId = 0,
	flag=0;

$(document).ready(function(){
	/* Get Current client id */
	clientId = $('#showcalendar').find('input[name="current-client-id"]').val();

	/* Start: Full calendar start */
	calendar.fullCalendar({
		buttonIcons: {
			prev: 'fa fa-chevron-left',
			next: 'fa fa-chevron-right'
		},
		header: {
			left: 'prev,next today',
			center: 'title',
			right: ''
		},
		defaultView: 'basicWeek',
		
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		droppable: true, // this allows things to be dropped onto the calendar !!!
		eventRender: function(event, element, view){
			var contentElem = element.find(".fc-content"),
			 	eventHtml = '';
		
           	$(element).find("span.fc-title").remove();
           	$(element).addClass('cal-event'+event.type);
           	$(element).addClass('delete-'+event.type+'-'+event.id);
												
			if(event.type == 'habit'){
				event.color = '#cfcfcf';
				eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-habit "   data-original-title="delete" data-habit-id="'+event.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-habit"  data-original-title="Edit" data-habit-id="'+event.id+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>'; 	
			}
			else if(event.type == 'goal'){
				event.color = '#3ec33e';
				eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-goal"  data-original-title="delete" data-goal-id="'+event.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-goal"  data-original-title="Edit" data-goal-id="'+event.id+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>'; 
			}
			else if(event.type == 'task'){
				event.color = '#FF7C4E';
				eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-task"  data-original-title="delete" data-task-id="'+event.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-task"  data-original-title="Edit" data-task-id="'+event.id+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>';	
			}
			else if(event.type == 'milestone'){
				event.color = '#FF788E';
				eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-milestone"  data-original-title="delete" data-milestone-id="'+event.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-milestone"  data-original-title="Edit" data-milestone-id="'+event.id+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>';	
			}
			eventHtml +='<div class="checkbox check-primary checkbox-inline cal-checkbox" data-event-date="'+event.actionEventDate+'" ><input id="'+event.actionTypeId+'" value="1" type="checkbox"';

			if(event.actionTypeStatus==1)
				eventHtml +='checked';

			if(event.actionEventDate > moment().format("YYYY-MM-DD"))
				eventHtml +='disabled="disabled"';
                
			eventHtml +=' style="margin: 4px 0px 0px -5px;"><label for="'+event.actionTypeId+'">&nbsp;</label></div><a data-toggle="modal" data-target="#'+event.type+'-modal" class="listing-'+event.type+'-name" data="'+event.id+'"><span class="cal-list-titel">'+event.actionName+'</span></a>';
            contentElem.css({"background-color":event.color});
            contentElem.append(eventHtml);
		},
		eventAfterAllRender: function(view){
			/* Intergrate location-area into calendar */
			if(!calendar.find('.goalbuddy-eventtype-dd').length){
				var dd = $('.goalbuddy-event-dd').eq(0);
				dd.appendTo(calendar.find('.fc-right'))
			}
			flag = 1;
			if(flag == 0){
				 $('#goal-buddy-panel').trigger('click');
				 flag++;
		    } 
		    calendar.find('.fc-today-button').addClass('today-goal-get');
		},
		viewRender: function(view, element){
			//console.log('view render');
		},
		selectable: true,
		selectHelper: true,	
	});
	/* End: Full calendar start */

	/* toggel calendar according to accordian */
	$('a[href="#panel_assess_progress"]').on('click', function (e) {
     	calendar.fullCalendar('render');
    });

	/* Randor full calendar on click accordion */
	$(document).on( 'click', '#goal-buddy-panel', function(){
		calendar.find('.fc-today-button').trigger('click');

		/* Call Calendar event function */
		getGoalBuddyEvent();
	})

	/* Delete habite */	
	$(document).on( 'click', '.cal-delete-habit', function() {
		var habitId = $(this).data('habit-id');
		// var currentRow = $(this).closest('.delete-habit-'+habitId);
		$.ajax({
	        url: public_url+'goal-buddy/deletehabit',
	        type: 'POST',
	        data: {'habitId':habitId},
	        success: function(response) {
	          var data = JSON.parse(response);
	            if(data.status == 'true'){
	            	 $('.delete-habit-'+habitId).remove();
	                //location.reload();
	          }  
	        }
	    });
	});

	/* Edit habit */ 
	$(document).on( 'click', '.cal-edit-habit', function(e) {
	    e.preventDefault();
	    var habitId = $(this).data('habit-id');
	    window.location.href = public_url+'goal-buddy/edithabit/'+habitId;
	});

	/* Edit goal */ 
	$(document).on( 'click', '.cal-edit-goal', function(e) {
	    e.preventDefault();
	    var goalId = $(this).data('goal-id');
	    window.location.href = public_url+'goal-buddy/editgoal/'+goalId;
	});

	/* Delete goal */ 
	$( document ).on( 'click', '.cal-delete-goal', function() {
	 	var goalId = $(this).data('goal-id');
	  	//var currentRow = $(this).closest('.delete-goal-'+goalId);
	   	$.ajax({
	        url: public_url+'goal-buddy/deletegoal',
	        type: 'POST',
	        data: {'eventId':goalId},
	        success: function(response) {
	          var data = JSON.parse(response);
	            if(data.status == 'true'){
	            	 $('.delete-goal-'+goalId).remove();
	               // location.reload();
	          }  
	        }
		});
	});

	 /* Edit Milestone */
	$(document).on( 'click', '.cal-edit-milestone', function(e) {
	    e.preventDefault();
	    var milestoneId = $(this).data('milestone-id');
	    window.location.href = public_url+'goal-buddy/editmilestone/'+milestoneId;
	}); 

	$( document ).on( 'click', '.cal-delete-milestone', function() {
	 	var milestoneId = $(this).data('milestone-id');
	   	$.ajax({
	        url: public_url+'goal-buddy/deletemilestones',
	        type: 'POST',
	        data: {'eventId':milestoneId},
	        success: function(response) {
	          var data = JSON.parse(response);
	            if(data.status == 'true'){
	            	 $('.delete-milestone-'+milestoneId).remove();
	                //location.reload();
	          }  
	        }
		});
	});

	/* Edit task */
	$(document).on( 'click', '.cal-edit-task', function(e) {
	    e.preventDefault();
	    var taskId = $(this).data('task-id');
	    window.location.href = public_url+'goal-buddy/edittask/'+taskId;
	}); 

	/* Delete task */
	$( document ).on( 'click', '.cal-delete-task', function() {
	 	var taskId = $(this).data('task-id');
	 	//var currentRow = $(this).closest('.delete-task-'+taskId);
	   	$.ajax({
	        url: public_url+'goal-buddy/deletetask',
	        type: 'POST',
	        data: {'taskId':taskId},
	        success: function(response) {
	          var data = JSON.parse(response);
	            if(data.status == 'true'){
	            	 $('.delete-task-'+taskId).remove();
	                //location.reload();
	          }  
	        }
		});
	});

	/* Status update */
	$(document).on( 'change', '.cal-checkbox input[type="checkbox"]', function(e) {
	e.preventDefault();
	var eventId = $(this).prop('id');
	var eventDate =$(this).parent().data('event-date');

	var checked = $(this).is(":checked");
	var actionStatus;

	if(checked) actionStatus = 1;
	else actionStatus = 0;

	if(eventDate <= toDay){
		$.ajax({
			url: public_url+'manage-status',
			method: "POST",
			data: {'actionStatus':actionStatus,'eventId':eventId},
			success: function(data){
				var data = JSON.parse(data);
				if(data.status == "true"){
				}
			}
		});	
	}
	});

	/* Toggel calendor action */
	$(document).on('mouseover', '.fc-content', function () {
	    $(this).find(".calendar-action").show();
	}).on('mouseleave', '.fc-content', function () {
	    $(this).find(".calendar-action").hide();
	});

	/* Select Goal buddy event */
	$(document).on('change', 'select.goalbuddy-event-dd', function(){
		var calEventCls='cal-event'+$(this).val();

		calendar.find('.fc-day-grid-event').hide();
		if(calEventCls == 'cal-eventall'){
			calendar.find('.fc-day-grid-event.cal-eventhabit').show();
			calendar.find('.fc-day-grid-event.cal-eventtask').show();
			calendar.find('.fc-day-grid-event.cal-eventgoal').show();	
		}      
		else if(calEventCls == 'cal-eventhabit'){
			calendar.find('.fc-day-grid-event.cal-eventhabit').show();
		}
		else if(calEventCls == 'cal-eventtask'){ 
			calendar.find('.fc-day-grid-event.cal-eventtask').show();	
		}
		else if(calEventCls == 'cal-eventgoal'){
			calendar.find('.fc-day-grid-event.cal-eventgoal').show();	
		}
	});


	/* Start: on click prev date button */
	$('body').on('click','.fc-prev-button', function(e){
		e.preventDefault();
		getGoalBuddyEvent();
	})
	/* End: on click prev date button */

	/* Start: on click next date button */
	$('body').on('click','.fc-next-button', function(e){
		e.preventDefault();
		getGoalBuddyEvent();
	})
	/* End: on click next date button */

	/* Start: on click today date button */
	$('body').on('click','.today-goal-get', function(e){
		e.preventDefault();
		getGoalBuddyEvent();
	})
	/* End: on click today date button */

});

/* Start: Get Goal buddy events from db */
function getGoalBuddyEvent(){
	calendar.fullCalendar('removeEvents');
	var formData = {};
	formData['startDate'] = dateStringToDbDate(getCalendarStartDate());
	formData['endDate'] = dateStringToDbDate(getCalendarEndDate());
	formData['eventType'] = getSeletctedEvent();
	formData['clientId'] = clientId;
	$.get(public_url+'showgoalactivity', formData, function(data){
		var events = [];
		var actionType;
		var actionId;
		if(data.length){
			$.each(data, function(key, val){
				var eventStart = val.eventDueDate,
					eventEnd = val.eventDueDate;

				if(val.milestoneId != 0) {actionType='milestone'; actionId=val.milestoneId; actionName=val.eventTitel}
				else if(val.habitId != 0) {actionType='habit'; actionId=val.habitId; actionName=val.eventTitel}
				else if(val.taskId != 0) {actionType='task'; actionId=val.taskId; actionName=val.eventTitel}
				else if(val.goalId != 0) {actionType='goal'; actionId=val.goalId; actionName=val.eventTitel}

				var pushedIdx = events.push({type:actionType, id:actionId, start:eventStart, end:eventEnd, actionName:actionName, actionTypeId:val.eventId, actionTypeStatus:val.eventStatus, actionEventDate:val.eventDueDate}) - 1;
			});
		}
		calendar.fullCalendar('addEventSource', events);
	},'json');
}
/* End: Get Goal buddy events from db */

/* Start: Get selected event type */
function getSeletctedEvent(){
	return calendar.find('select#eventTypeDD').val();
}
/* End: Get selected event type */

/* Start: Get calendar start date */
function getCalendarStartDate(){
	return calendar.fullCalendar('getView').start;
}
/* End: Get calendar start date */

/* Start: Get calendar end date */
function getCalendarEndDate(){
	return calendar.fullCalendar('getView').end;
}
/* End: Get calendar end date */



	