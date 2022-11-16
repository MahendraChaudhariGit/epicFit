var public_url = $('meta[name="public_url"]').attr('content');
var calendar = $('#full-calendar');
var toDay = moment().format("YYYY-MM-DD");
var headerHeight = calendar.closest(".app-content").find('header').height();

$(document).ready(function(){
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
			// defaultView: 'basicWeek',
			defaultView: (Main.isMobileFn()) ? 'basicDay' : 'basicWeek',
			firstDay: 1,
			viewRender: function(view, element){
				getCalEvents();

			},
			//events: demoCalendar,
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
					event.color = '#DDD8D8';
					eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-habit " data-entity="habit"   data-original-title="delete" data-habit-id="'+event.actionTypeId+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'" data-total-task="'+event.total+'" data-completed-task="'+event.completed+'"></a>'; 
					//  eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-habit " data-entity="habit"   data-original-title="delete" data-habit-id="'+event.id+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'" data-total-task="'+event.total+'" data-completed-task="'+event.completed+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-habit"  data-original-title="Edit" data-habit-id="'+event.id+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>'; 

					// eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-habit " data-entity="habit"   data-original-title="delete" data-habit-id="'+event.actionTypeId+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'" data-total-task="'+event.total+'" data-completed-task="'+event.completed+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-habit"  data-original-title="Edit" data-habit-id="'+event.actionTypeId+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>'; 

				}else if(event.type == 'goal'){
					event.color = '#FBDB0C';
					eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-goal" data-entity="goal"  data-original-title="delete" data-goal-id="'+event.id+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'" data-total-milestone="'+event.total+'" data-completed-milestone="'+event.completed+'"></a>';
					// eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-goal" data-entity="goal"  data-original-title="delete" data-goal-id="'+event.id+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'" data-total-milestone="'+event.total+'" data-completed-milestone="'+event.completed+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-goal"  data-original-title="Edit" data-goal-id="'+event.id+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>'; 

				}else if(event.type == 'milestone'){
					event.color = '#66CCCC';
					eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-milestone" data-entity="milestone"  data-original-title="delete" data-milestone-id="'+event.id+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'"></a>'; 
					// eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-milestone" data-entity="milestone"  data-original-title="delete" data-milestone-id="'+event.id+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-milestone"  data-original-title="Edit" data-milestone-id="'+event.id+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>'; 

				}
				else if(event.type == 'task'){
					event.color = '#FF642B';
					eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-task" data-entity="task"  data-original-title="delete" data-task-id="'+event.actionTypeId+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'"></a>';
					// eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-task" data-entity="task"  data-original-title="delete" data-task-id="'+event.id+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-task"  data-original-title="Edit" data-task-id="'+event.id+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>';

					// eventHtml +='<a class="pull-right tooltips calendar-action cal-delete-task" data-entity="task"  data-original-title="delete" data-task-id="'+event.actionTypeId+'" data-parent-id="'+event.actionParentId+'" data-due-date="'+event.actionEventDate+'" data-status="'+event.actionTypeStatus+'"><i class="fa fa-times" style="color:#ff4401;"></i></a><a class="pull-right tooltips calendar-action cal-edit-task"  data-original-title="Edit" data-task-id="'+event.actionTypeId+'" ><i class="fa fa-pencil" style="color:#ff4401;"></i></a>';
				}

				eventHtml +='<div class="checkbox check-primary checkbox-inline cal-checkbox" data-event-date="'+event.actionEventDate+'" data-event-type="'+event.type+'"><input id="'+event.actionTypeId+'" value="1" type="checkbox" ';

				if(event.actionTypeStatus==1){
					eventHtml +='checked=""';
				}else if(event.actionTypeStatus==1) {
					eventHtml +='checked="checked"';
				}

				if((event.actionEventDate > toDay) || (event.type == 'habit') || ((event.type == 'goal'))){
					eventHtml +=' disabled="disabled" ';
					eventHtml += 'style="margin-left: -5px;">';
				} else {
					eventHtml += 'style="margin-left: -5px;">';
				}

				eventHtml +='<label for="'+event.actionTypeId+'">&nbsp;</label></div>';

				eventHtml +='<a data-toggle="modal" data-target="#'+event.type+'-modal" class="listing-'+event.type+'-name" data="'+event.id+'" data-parent-id="'+event.actionTypeId+'" data-event-type="'+event.type+'"><span class="cal-list-titel">'+event.title+'</span></a>';
				
				var fontColor = invertColor(event.color);
				contentElem.css({"border-top":event.color,"color":fontColor});
				contentElem.css({"background-color":event.color});
				contentElem.append(eventHtml);
			},
			eventAfterAllRender: function(view){
				stickyFilter();
				/* Intergrate location-area into calendar */
				calendar.find('.fc-toolbar').addClass('fc-custom-toolbar');
				if(!calendar.find('.goalbuddy-eventtype-dd').length){
					var dg = $('.goalbuddy-goal-dd').eq(0);
					dg.appendTo(calendar.find('.fc-right'));
					var dd = $('.goalbuddy-event-dd').eq(0);
					dd.appendTo(calendar.find('.fc-right'));
				}

				$('.fc-event-container a[data-entity=habit').each(function(){
					var totalTask = $(this).data('total-task'),
					completedTask = $(this).data('completed-task');
				
					habitProgress($(this), completedTask, totalTask);
				});


				$('.fc-event-container a[data-entity=goal').each(function(){
					var totalMilestone = $(this).data('total-milestone'),
					completedMilestone = $(this).data('completed-milestone');
					goalProgress($(this), completedMilestone, totalMilestone);
				});

				if(self==top){
					$(window).scroll(function(){
						stickyFilter(); 
					});
				}
                else{ //Subview mode
                	$(window.parent).scroll(function(){
                		stickyFilter(); 
                	});
                }
            },

            selectable: true,
            selectHelper: true,
            eventOrder: "actionOrder",
        });

	$(document).on('change', 'select.goalbuddy-event-dd', getCalEvents);
	$(document).on('change', 'select.goalbuddy-goal-dd', getCalEvents);

});
	/* initialize the calendar
	-----------------------------------------------------------------*/
	function invertColor(hex){
		function hexdec (hexString) {
			hexString = (hexString + '').replace(/[^a-f0-9]/gi, '')
			return parseInt(hexString, 16)
		}
		var r = 0.2126*hexdec(hex.substr(1,2)); //Converting to rgb and multiplying luminance
		var g = 0.7152*hexdec(hex.substr(3,2));
		var b = 0.0722*hexdec(hex.substr(5,2));
		return (r+g+b) < 100 ? '#ffffff' : '#000000';
	}

		/* start: Calculate and change background color of habit
		Param:
			habit: habit element
			completedTask: Number of tasks completed
			totalTask: Total number of tasks
		Return:
			Null
			*/	
			function habitProgress(habit, completedTask = 1, totalTask = 1){
				console.log('habitProgress', habit, completedTask, totalTask);
				var habitCont = habit.parent(),
				colorwidth= (completedTask/totalTask)*100 | 0;
				var backGroundColor = '-moz-linear-gradient(left,'+'#7CFC00'+', '+'#7CFC00'+' '+ colorwidth +'%, #DDD8D8 1%)';
				habitCont.css({"background": backGroundColor});

				var backGroundColor = '-webkit-linear-gradient(left,'+'#7CFC00'+', '+'#7CFC00'+' '+ colorwidth +'%, #DDD8D8 1%)';
				habitCont.css({"background": backGroundColor});
				habit.data('completed-task', completedTask);
			}		 
			/* end: Calculate and change background color of habit */


		/* start: Calculate and change background color of goal
		Param:
			goal: goal element
			completedMilestone: Number of milestones completed
			totalMilestone: Total number of milestone
		Return:
			Null
			*/	
			function goalProgress(goal, completedMilestone = 1, totalMilestone = 1){
				var goalCont = goal.parent(),
				colorwidth= (completedMilestone/totalMilestone)*100;
				var backGroundColor = '-moz-linear-gradient(left,'+'#FBDB0C'+', '+'#FBDB0C'+' '+ colorwidth +'%, #FDF19D 1%)';
				goalCont.css({"background": backGroundColor});

				var backGroundColor = '-webkit-linear-gradient(left,'+'#FBDB0C'+', '+'#FBDB0C'+' '+ colorwidth +'%, #FDF19D 1%)';
				goalCont.css({"background": backGroundColor});
				goal.data('completed-milestone', completedMilestone);
			}		 
			/* end: Calculate and change background color of habit */

			function getCalEvents(){
				calendar.fullCalendar('removeEvents');
				var formData = {};
				formData['startDate'] = getCalendStartDate();
				formData['endDate'] = getCalendEndDate();
				formData['eventType'] =$(".goalbuddy-event-dd option:selected").val();
				formData['goalId'] =$(".goalbuddy-goal-dd option:selected").val();


				$.get(public_url+'showgoalactivity', formData,function(data){
					var events = [];
					var actionType;
					var actionId;
					if(data.length){
						var i=1;
						$.each(data, function(key, val){
							var eventStart = val.eventDueDate,
							eventEnd = val.eventDueDate,
							total = val.subEventTotal,
							completed = val.subEventCompleted;

							if(val.eventType == 'goal') 
								actionId=val.goalId; 
							else if(val.eventType == 'milestone') 
								actionId=val.milestoneId; 	
							else if(val.eventType == 'habit') 
								actionId=val.habitId; 
							else if(val.eventType == 'task') 
								actionId=val.taskId; 

							events.push({
								'total': total,
								'completed': completed,
								'type':val.eventType, 
								'id':actionId, 
								'start':eventStart, 
								'end':eventEnd, 
								'title':val.eventTitel, 
								'actionTypeId':val.eventId, 
								'actionTypeStatus':val.eventStatus, 
								'actionEventDate':val.eventDueDate, 
								'actionOrder':i,
								'actionParentId':val.parentId,}) - 1;
							i++;	
						});

					}

					if(events.length && events[0] != '')
						calendar.fullCalendar('addEventSource', events);
				},'json');
			}

			function getCalendRawStartDate(){

				return calendar.fullCalendar('getView').start;
			}

			function getCalendStartDate(){

				return getCalendRawStartDate().format('YYYY-MM-DD');
			}

			function getCalendEndDate(){
				return calendar.fullCalendar('getView').end.format('YYYY-MM-DD');
			}





/// edit habit 
$(document).on( 'click', '.cal-edit-habit', function(e) {
	e.preventDefault();
	var habitId = $(this).data('habit-id');
	window.location.href = public_url+'goal-buddy/edithabit/'+habitId;
});

/// edit goal 
$(document).on( 'click', '.cal-edit-goal', function(e) {
	e.preventDefault();
	var goalId = $(this).data('goal-id');
	window.location.href = public_url+'goal-buddy/editgoal/'+goalId;
});

 /// edit task 
 $(document).on( 'click', '.cal-edit-task', function(e) {
 	e.preventDefault();
 	var taskId = $(this).data('task-id');
 	window.location.href = public_url+'goal-buddy/edittask/'+taskId;
 }); 
  /// edit milestone 
  $(document).on( 'click', '.cal-edit-milestone', function(e) {
  	e.preventDefault();

  	var milestoneId = $(this).data('milestone-id');
  	window.location.href = public_url+'goal-buddy/editmilestone/'+milestoneId;

  });    


  $( document ).on( 'click', '.cal-delete-habit', function(e) {
  	e.preventDefault();
  	var habitId = $(this).data('habit-id');
  	var entity = $(this).data('entity');
  	var actionType='calender';
  	var eventUrl='goal-buddy/deletehabit';
  	goalConfirmDelete(habitId,entity,eventUrl,actionType)
  });


  $( document ).on( 'click', '.cal-delete-goal', function(e) {
  	e.preventDefault();
  	var goalId = $(this).data('goal-id');
  	var entity = $(this).data('entity');
  	var eventUrl ='goal-buddy/deletegoal';
  	var actionType='calender';
  	goalConfirmDelete(goalId,entity,eventUrl,actionType)
  }); 



  $( document ).on( 'click', '.cal-delete-task', function(e) {
  	e.preventDefault();
  	var $this=$(this);
  	var taskId = $this.data('task-id');
  	var entity = $this.data('entity');
  	var eventUrl='goal-buddy/deletetask';
  	var actionType='calender';
  	goalConfirmDelete(taskId,entity,eventUrl,actionType)
  });

  $( document ).on( 'click', '.cal-delete-milestone', function(e) {
  	e.preventDefault();
  	var $this=$(this);
  	var milestoneId = $this.data('milestone-id');
  	var entity = $this.data('entity');
  	var eventUrl='goal-buddy/deletemilestones';
  	var actionType='calender';
  	goalConfirmDelete(milestoneId,entity,eventUrl,actionType)
  });

  // Status change
  $(document).on( 'change', '.cal-checkbox input[type="checkbox"]', function(e) {
  	e.preventDefault();
  	var eventId = $(this).prop('id');
  	var checked = $(this).is(":checked");
  	var eventDate =$(this).parent().data('event-date');
  	var eventType =$(this).parent().data('event-type');
  	var actionStatus;

  	if(checked) actionStatus = 1;
  	else actionStatus = 0;
  	if(eventDate <= toDay){
  		$.ajax({
  			url: public_url+'manage-status',
  			method: "POST",
  			data: {'actionStatus':actionStatus,'eventId':eventId, 'eventType':eventType},
  			success: function(data){
  				console.log('pinki', data);
  				var data = JSON.parse(data);
  				if(data.status == "true"){
  					if(data.eventType == 'task') {
  						var habitCbx = $("input[id="+data.updateEventId+"]"),
  						habitId = habitCbx.closest('div').closest('a').find('div.fc-content > a').attr('data-parent-id'),
  						habit = $(document).find('a[data-habit-id="'+habitId+'"]'),
  						completedTask = habit.data('completed-task'),
  						totalTask = habit.data('total-task');
                        console.log('habitCbx', habitCbx);
						console.log('habitId', habitId);
						console.log('habit', habit);
						console.log('completedTask',completedTask);
						console.log('totalTask', totalTask);
  						if(checked){
  							$("input[id="+eventId+"]").prop('checked', true);

  							completedTask++;
  							if(completedTask == totalTask)
  								habitCbx.prop('checked', true);
  						}
  						else{
  							completedTask--;
  							habitCbx.prop('checked', false);
  						}

  						habitProgress(habit, completedTask, totalTask);					  	
  					}
  					else if(data.eventType == 'milestone') {
  						var goalCbx = $("input[id="+data.updateEventId+"]"),
  						goalId = goalCbx.closest('div').closest('a').find('div.fc-content > a').attr('data-parent-id'),
  						goal = $(document).find('a[data-goal-id="'+goalId+'"]'),
  						completedMilestone = goal.data('completed-milestone') ,
  						totalMilestone = goal.data('total-milestone');
						  console.log('goalCbx', goalCbx);
						  console.log('goalId', goalId);
						  console.log('goal', goal);
						  console.log('completedMilestone',completedMilestone);
						  console.log('totalMilestone', totalMilestone);

  						if(checked){
  							$("input[id="+eventId+"]").prop('checked', true);

  							completedMilestone++;
  							if(completedTask == totalMilestone)
  								goalCbx.prop('checked', true);
  						}
  						else{
  							completedMilestone--;
  							goalCbx.prop('checked', false);
  						}
  						goalProgress(goal, completedMilestone, totalMilestone);	
  					}
  				}
  			}
  		});	
  	}
  });


  $(document).on('mouseover', '.fc-content', function () {
  	$(this).find(".calendar-action").show();
  }).on('mouseleave', '.fc-content', function () {
  	$(this).find(".calendar-action").hide();
  });




  /*custom calendar*/

  $(document).ready(function() {
  	$('#event-calendar').fullCalendar({
  		header: {
  			left: 'prev,next today',
  			center: 'title',
  			right: 'month,agendaWeek,agendaDay'
  		},
  		defaultDate: '2016-06-12',
  		selectable: true,
  		selectHelper: true,
  		select: function(start, end) {
  			var title = prompt('Event Title:');
  			var eventData;
  			if (title) {
  				eventData = {
  					title: title,
  					start: start,
  					end: end
  				};
					$('#event-calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
				}
				$('#event-calendar').fullCalendar('unselect');
			},
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [
			{
				title: 'All Day Event',
				start: '2016-06-01'
			},
			{
				title: 'Long Event',
				start: '2016-06-07',
				end: '2016-06-10'
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: '2016-06-09T16:00:00'
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: '2016-06-16T16:00:00'
			},
			{
				title: 'Conference',
				start: '2016-06-11',
				end: '2016-06-13'
			},
			{
				title: 'Meeting',
				start: '2016-06-12T10:30:00',
				end: '2016-06-12T12:30:00'
			},
			{
				title: 'Lunch',
				start: '2016-06-12T12:00:00'
			},
			{
				title: 'Meeting',
				start: '2016-06-12T14:30:00'
			},
			{
				title: 'Happy Hour',
				start: '2016-06-12T17:30:00'
			},
			{
				title: 'Dinner',
				start: '2016-06-12T20:00:00'
			},
			{
				title: 'Birthday Party',
				start: '2016-06-13T07:00:00'
			},
			{
				title: 'Click for Google',
				url: 'http://google.com/',
				start: '2016-06-28'
			}
			]
		});

  });

  function stickyFilter(){
  	if(self==top){
  		var scrolled = $(window).scrollTop(),
  		scrollable = $('#page-title').height();
  	}
        else{ //Subview mode
        	var scrolled = $(window.parent).scrollTop(),
        	scrollable = parent.$('#page-title').height()+40;

        	headerHeight = scrolled-scrollable
        }
        var filterBar = calendar.children('.fc-toolbar');

        if(scrolled > scrollable) //Stick filter
        	filterBar.addClass('fixed').css({top:headerHeight});
        else
        	filterBar.removeClass('fixed');

        var Diff = $(window.parent).width() - calendar.width();
        if(Diff != 110){
        	filterBar.width(calendar.width());
        }
    }
