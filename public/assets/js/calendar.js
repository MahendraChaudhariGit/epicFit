if(!$('#noRescoureFound').length){
	var public_url = $('meta[name="public_url"]').attr('content'),
	calendarSettings = JSON.parse($('input[name=calendarSettingVal]').val()),
	calendar = popoverContainer,
	recurrAppointReschecModal = $('#recurrAppointReschecModal'),
	busyModal = $('#busyModal'),
	creditModal = $("#creditModal"),
	workingHrsModal = $("#workingHrsModal"),
	dateSelMsg = $('#dateSelMsg'),
	noRostStaff = $('#noRostStaff'),
	staffFilterCalend = $('select.staff-filter-cal'),
	areaDdCalend = $('select.toolBarDd.loc-area-dd'),

	addEventDateSelMode = false,
	resheduleAppointDateSelMode = false,
	bookNextAppointDateSelMode = false,
	changeEventDateDateSelMode = false,
	resheduleAppointFromOverview = false,
	rescheduledClient = '',
	staffFilterDdTriggered = false,
	calendarFirstTime = true,
	isVisible = false,
	isClassMarge = false,
		//serviceReschduled = false,

		//scrollPos = 0,
		areaDdCalendLastVal = 0,
		staffFilterCalendLastVal = 0,
		scriptSource = 'calendar',
		headerHeight = calendar.closest(".app-content").find('header').height(),
		week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		showBooking = {},
		addEventPopoverOpt = {
			html: true,
			//content: "<a href='#' class='btn-block appointAddLnk'><i class='fa fa-calendar'></i> Add service</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block classAddLnk'><i class='fa fa-bullhorn'></i> Add class</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block busyAddLnk'><i class='fa fa-ban'></i> Add busy time</a>",
			content: "<a href='#' class='btn-block bookAddLnk'><i class='fa fa-calendar'></i> Add booking</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block busyAddLnk'><i class='fa fa-ban'></i> Add busy time</a>",
			placement: function(context, source){
				var pos = $(source).position(),
				leftSpace = pos.left,
				rightSpace = calendar.width()-(leftSpace+$(source).width()),
				topSpace = pos.top,
				popoverWidth = 200,
				popoverHeight = 165;

				if(rightSpace > popoverWidth)
					return 'right';
				else if(leftSpace > popoverWidth)
					return 'left';
				else if(topSpace > popoverHeight)
					return 'top';			
				else
					return 'bottom';
				
			}, 
			container: calendar,
			title: function(){
				if($(this).text())
					var head = $(this).text();
				else
					var head = '9:00am';	

				return "<center><strong>"+head+"</strong></center>"
			},
			trigger: 'manual'
		},
		editEventPopoverOpt = {
			html: true,
			content: function(){
				var $this = $(this),
				autoExpireDatetime = $this.find('input[name="autoExpireDatetime"]').val(),
				summary = '',
				eventDate = $this.find('input[name="date"]').val(),
				startTime = calendar.find('.calendEvent.appointment-'+$this.find('input[name="eventId"]').val()+':first input[name="startTime"]').val(),
				timeString = dbTimeToTimeString(startTime),
				eventDatetime = eventDate+' '+timeString;
				
				if(!isSameOrBefore(eventDatetime)){
					if(isUserType(['Staff']) && $this.find('input[name="userId"]').val() != loggedInUser.userId){
						var editOrView = 'View',
						reschedOpt = '',
						cancelOpt = '',
						options = '';
					}
					else{
						var editOrView = 'Edit',
						reschedOpt = ($this.find('input[name="isStaffDeleted"]').val() == 'true')?'':'<a class="btn btn-primary btn-sm appointReschedule" href="#"><i class="fa fa-calendar"></i> Reschedule</a>',
						cancelOpt = '<a href="#" class="pull-left m-l-10" data-toggle="modal" data-target="#appointCancelModal"><i class="fa fa-trash"></i> Cancel</a>';

						if($this.find('input[name="appointStatusOpt"]').val() == 'Confirmed'){
							var appointStatusConfirm = $this.find('input[name="appointStatusConfirm"]').val(),
							options = '<div class="btn-toolbar m-t-10"><div class="btn-group" role="group"><a class="btn btn-default btn-sm appointStatusChange '+(appointStatusConfirm == 'Completed'?'active':'')+'" href="#" data-new-status="Completed"><i class="fa fa-check"></i> Completed</a><a class="btn btn-default btn-sm appointStatusChange '+(appointStatusConfirm == 'Did not show'?'active':'')+'" href="#" data-new-status="Did not show"><i class="fa fa-thumbs-down"></i> Did not show</a></div></div>';
						}
						else{
							var options = '<div class="btn-toolbar m-t-10"><a class="btn btn-sm btn-success appointStatusChange" href="#" data-new-status="Not started" data-confirm="true"><i class="fa fa-check"></i> Confirm</a> <a class="btn btn-sm btn-red appointDeleteForce" href="#"><i class="fa fa-close"></i> Decline</a></div>';
						}
					}
				}
				else{
					var editOrView = 'View',
					reschedOpt = '',
					cancelOpt = '',
					options = '';				
				}

				if($this.find('input[name="appointStatusOpt"]').val() != 'Confirmed' && autoExpireDatetime != '0000-00-00 00:00:00'){
					var expireMoment = moment(autoExpireDatetime);
					summary = '<p><i class="fa fa-bomb"></i> Expires ';

					if(!moment().isSame(expireMoment, 'day'))
						summary += expireMoment.format('[on] D MMM [at] h:mma');
					else
						summary+= expireMoment.format('[at] h:mma [today]');
				}

				if($this.find('input[name="isClientDeleted"]').val() != 'true')
					var bookNextOpt = '<a class="btn btn-primary btn-sm appointBookNext" href="#"><i class="fa fa-refresh"></i> Book next</a>';
				else
					var bookNextOpt = '';

				return '<div class="eventPopover"><p><i class="fa fa-gear"></i> '+$this.find('input[name="serviceName"]').val()+' - $'+$this.find('input[name="price"]').val()+'</p><p><i class="fa fa-user"></i> '+$this.find('input[name="staffName"]').val()+' </p><p><i class="fa fa-clock-o"></i> '+$this.find('.eventTimeRange').text()+'</p>'+summary+'<div class="clearfix"><div class="btn-toolbar pull-left"><div class="btn-group" role="group"><a class="btn btn-primary btn-sm appointEdit" href="#"><i class="fa fa-edit"></i> '+editOrView+' </a>'+reschedOpt+bookNextOpt+'</div></div>'+cancelOpt+'</div>'+options+'</div>';
			},
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
				return '<strong>'+$(this).find('input[name="clientName"]').val()+'</strong>';
			}
		};

		$(document).ready(function(){
			eventObj = $('#eventHtml-div');
			if(getUrlParamFromUrl("resheduleAppointFromOverview")=='true'||getUrlParamFromUrl("resheduleAppointFromOverview"))
				resheduleAppointDateSelMode = true;
			var h = Math.floor((calendarSettings.cs_intervals*4) / 60),
			m = (calendarSettings.cs_intervals*4) % 60,
			intervels = h+':'+m;

			/* start: Initialize the calendar Default view as per settings*/
			if(calendarSettings.cs_view == 'weekly')
				var agendaVal = 'agendaWeek';
			else if(calendarSettings.cs_view == 'daily')
				var agendaVal = 'agendaDay';
			else
				var agendaVal = 'month';
			/* end: Initialize the calendar Default view as per settings*/

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

				defaultView: (Main.isMobileFn())?'agendaDay':agendaVal,

				/* Indicate Current Time */
				nowIndicator: true, 

				allDaySlot: false,

				firstDay: calendarSettings.cs_first_day,

				/* Control interval between time slot in week view and day view */
			//slotLabelInterval: '01:00',
			slotLabelInterval: intervels,
			//slotDuration: '00:15:00',
			slotDuration: '00:'+calendarSettings.cs_intervals+':00',
			slotLabelFormat: 'h:mma',

			/* Default initial time */
			//scrollTime:(calendarSettings.cs_start_time==null)?'14:00:00':calendarSettings.cs_start_time,

			/* Calendar Events */
			editable: true,
			eventClick: function(calEvent, jsEvent, view){
				if(salesProcessType(true) != 'service' && !isCreateServiceMakeUpMode()){
					var eventType = $(this).find('input[name="type"]').val();

					if(eventType == 'appointment'){
						eventObj = $(this);
						var sameEvents = calendar.find('.calendEvent.appointment-'+calEvent.id);
						eventServices = [];
						if(sameEvents.length){
							sameEvents.each(function(){
								$this = $(this)
								eventServices.push({id:$this.find('input[name="serviceId"]').val(), name:$this.find('input[name="serviceName"]').val(), time:$this.find('input[name="startTime"]').val(), duration:$this.find('input[name="duration"]').val(), price:$this.find('input[name="price"]').val(), isDeleted:$this.find('input[name="isServiceDeleted"]').val()});
							})
							eventServices.sort(sortServices);							
						}
						calPopupHelper.removeClass('hidden');
					}
					else{
						var $this = $(this),
						eventId = $this.find('input[name="eventId"]').val();
						
						if(eventType == 'class' && isClassMarge){
							eventObj = $this;
							var fromEventId = classModal.find('input[name="eventId"]').val();
							toEventId = eventId
							// marge classes 
							margeClass(fromEventId, toEventId);
						}
						else if(eventType == 'class' ){
							/*$.get(public_url+'staffevents/classes/'+eventId, function(data){
								var eventHtml = '<input type="hidden" name="type" value="class"><input type="hidden" name="eventId" value="'+eventId+'"><input type="hidden" name="date" value="'+data.date+'"><input type="hidden" name="startTime" value="'+data.startTime+'"><input type="hidden" name="duration" value="'+data.duration+'"><input type="hidden" name="userId" value="'+data.userId+'"><input type="hidden" name="staffId" value="'+data.staffId+'"><input type="hidden" name="staffName" value="'+data.staffName+'"><input type="hidden" name="isStaffDeleted" value="'+data.isStaffDeleted+'"><input type="hidden" name="classId" value="'+data.classId+'"><input type="hidden" name="capacity" value="'+data.capacity+'"><input type="hidden" name="price" value="'+data.price+'"><input type="hidden" name="className" value="'+data.className+'"><input type="hidden" name="notes" value="'+data.notes+'"><input type="hidden" name="eventRepeat" value="'+data.eventRepeat+'"><input type="hidden" name="eventRepeatInterval" value="'+data.eventRepeatInterval+'"><input type="hidden" name="eventRepeatEnd" value="'+data.eventRepeatEnd+'"><input type="hidden" name="eventRepeatEndAfterOccur" value="'+data.eventRepeatEndAfterOccur+'"><input type="hidden" name="eventRepeatEndOnDate" value="'+data.eventRepeatEndOnDate+'"><input type="hidden" name="isRepeating" value="'+data.isRepeating+'"><input type="hidden" name="isClassDeleted" value="'+data.isClassDeleted+'">';

								if(data.eventRepeatWeekDays)
									var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
								else
									var eventRepeatWeekDays = [];
								$.each(eventRepeatWeekDays, function(key, val){
									eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\''+val+'\'>';						
								});
								
								for(var i in data.resources)
									eventHtml += '<input type=\'hidden\' name=\'resource[]\' value=\''+JSON.stringify(data.resources[i])+'\'>';

								for(var i in data.clients){
									eventHtml += '<input type="hidden" name="client[]" value="'+escQuotes(JSON.stringify(data.clients[i]))+'">';
								}
										
								for(var i in data.areas)
									eventHtml += '<input type="hidden" name="area[]" value="'+escQuotes(JSON.stringify(data.areas[i]))+'">';		

								for(var i in data.histories)
									eventHtml += '<input type="hidden" name="history[]" value="'+escQuotes(JSON.stringify(data.histories[i]))+'">';		

								$this.find('input').remove();
								$this.find('.calendEvent').prepend(eventHtml);
								eventObj = $this;

								
							},'json');*/
							getBookingDetails(eventId, 'class', function(eventHtml){
								$this.find('input').remove();
								$this.find('.calendEvent').prepend(eventHtml);
								eventObj = $this;
								if(isRemoveTeamMode()){
									var removeStepTypee = removeStepType();
									if(removeStepTypee == 'book'){
										swal({
											title: 'Are you sure to remove from this booking?',
											allowOutsideClick: true,
											showCancelButton: true,
											confirmButtonText: 'Yes',
											confirmButtonColor: '#ff4401',
											cancelButtonText: "No"
										}, 
										function(isConfirm){
											if(isConfirm){
												toggleWaitShield("show", 'unlinkClient');
												var formData = {};
												formData['step'] = 'removeClient';
												formData['clientId'] = defaultClientDetails.id;
												formData['eventId'] = eventObj.find('input[name="eventId"]').val();
												formData['ifMakeUp'] = '';
												formData['ifRecur'] = '';
												formData['targetEvents'] = '';

												$.post(public_url+'staffevents/classes/edit', formData, function(resp){
													var resp = JSON.parse(resp);
													if(resp.status == "added")
														parent.location.reload(true);
													
													toggleWaitShield("hide", 'unlinkClient');
												});
											}
										});
									}
									else{
										removeAttend([defaultClientDetails.id]);
									}
								}
								else if(rescheduleClientsCount()){
									if(rescheduleClients[Object.keys(rescheduleClients)[0]].ifRecur && eventObj.find('input[name="isRepeating"]').val() == 1){
										$('#recurrClassClientReschecModal').modal('show');
									}
									else{
										rescheduleClass_client();
									}
								}
								else if(isCreateMakeUpMode()){
									var formData = {};

									formData['eventId'] = eventObj.find('input[name="eventId"]').val();
									formData['clientId'] = defaultClientDetails.id;
									formData['origEventId'] = parent.makeUpEventId;
									$.post(public_url+'staffevents/classes/makeup-client', formData, function(data){
										var data = JSON.parse(data);
										if(data.status == "added")
											parent.reloadPageWithAppointmentTab();
										else if(data.status == "clientBusy"){											
											showNotific(data.message);
											subviewScrollToTop();
										}
									});
								}
								else if(isSalesProcessMode()){
									var capacity = parseInt(eventObj.find('input[name="capacity"]').val() | 0);
									var linkedClientsCount = parseInt(eventObj.find('input[name="linkedClientsCount"]').val() | 0);

									if( typeof capacity == undefined || typeof linkedClientsCount == undefined || capacity <= linkedClientsCount)
										classOverBookConfirmSwal(bookTeam)
									else 
										bookTeam();
								}
								else{
									showClassModal('edit');
									resetAllModes();
								}
							});
						}
						else if(eventType == 'single-service'){
							getBookingDetails(eventId, 'service', function(eventHtml){
								$this.find('input').remove();
								$this.find('.calendEvent').prepend(eventHtml);
								eventObj = $this;

								if(isRemoveTeamMode()){
									var removeStepTypee = removeStepType();
									if(removeStepTypee == 'book'){
										swal({
											title: 'Are you sure to remove this booking?',
											allowOutsideClick: true,
											showCancelButton: true,
											confirmButtonText: 'Yes',
											confirmButtonColor: '#ff4401',
											cancelButtonText: "No"
										}, 
										function(isConfirm){
											if(isConfirm){
												toggleWaitShield("show", 'deleteService');

												deleteAppoint(true, '', function(){
													parent.location.reload(true);
													toggleWaitShield("hide", 'deleteService');
												})
											}
										});
									}
									else
										removeAttend()
								}
								else {
									showClassModal('edit');
								}
							})
							/*$.get(public_url+'staffevents/single-service/'+eventId, function(data){
								var eventHtml = '<input type="hidden" name="type" value="single-service"><input type="hidden" name="eventId" value="'+eventId+'"><input type="hidden" name="date" value="'+data.date+'"><input type="hidden" name="startTime" value="'+data.startTime+'"><input type="hidden" name="duration" value="'+data.duration+'"><input type="hidden" name="userId" value="'+data.userId+'"><input type="hidden" name="staffId" value="'+data.staffId+'"><input type="hidden" name="staffName" value="'+data.staffName+'"><input type="hidden" name="isStaffDeleted" value="'+data.isStaffDeleted+'"><input type="hidden" name="price" value="'+data.price+'"><input type="hidden" name="notes" value="'+data.notes+'"><input type="hidden" name="eventRepeat" value="'+data.eventRepeat+'"><input type="hidden" name="eventRepeatInterval" value="'+data.eventRepeatInterval+'"><input type="hidden" name="eventRepeatEnd" value="'+data.eventRepeatEnd+'"><input type="hidden" name="eventRepeatEndAfterOccur" value="'+data.eventRepeatEndAfterOccur+'"><input type="hidden" name="eventRepeatEndOnDate" value="'+data.eventRepeatEndOnDate+'"><input type="hidden" name="isRepeating" value="'+data.isRepeating+'"><input type="hidden" name="appointStatusOpt" value="'+data.appointStatusOpt+'"><input type="hidden" name="autoExpireAppointDur" value="'+data.autoExpireAppointDur+'"><input type="hidden" name="autoExpireDatetime" value="'+data.autoExpireDatetime+'"><input type="hidden" name="serviceName" value="'+data.serviceName+'"><input type="hidden" name="serviceId" value="'+data.serviceId+'"><input type="hidden" name="isServiceDeleted" value="'+data.isServiceDeleted+'"><input type="hidden" name="salesProcessEvent" value="'+data.salesProcessEvent+'"><input type="hidden" name="consultationDate" value="'+data.consultationDate+'">';

								if(data.eventRepeatWeekDays)
									var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
								else
									var eventRepeatWeekDays = [];
								$.each(eventRepeatWeekDays, function(key, val){
									eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\''+val+'\'>';						
								});
								
								for(var i in data.resources)
									eventHtml += '<input type=\'hidden\' name=\'resource[]\' value=\''+JSON.stringify(data.resources[i])+'\'>';
								
								for(var i in data.clients)
									eventHtml += '<input type="hidden" name="client[]" value="'+escQuotes(JSON.stringify(data.clients[i]))+'">';
										
								for(var i in data.areas)
									eventHtml += '<input type="hidden" name="area[]" value="'+escQuotes(JSON.stringify(data.areas[i]))+'">';		

								for(var i in data.histories)
									eventHtml += '<input type="hidden" name="history[]" value="'+escQuotes(JSON.stringify(data.histories[i]))+'">';		

								$this.find('input').remove();
								$this.find('.calendEvent').prepend(eventHtml);
								eventObj = $this;

								showClassModal('edit');
							},'json');*/
						}
						else{
							$.get(public_url+'staffevents/busy-time/'+eventId, function(data){
								var eventHtml = '<input type="hidden" name="type" value="busy"><input type="hidden" name="eventId" value="'+eventId+'"><input type="hidden" name="description" value="'+data.description+'"><input type="hidden" name="denyBooking" value="'+data.denyBooking+'"><input type="hidden" name="ifAreaHasStaffs" value="'+data.ifAreaHasStaffs+'"><input type="hidden" name="areaId" value="'+data.areaId+'"><input type="hidden" name="isAreaDeleted" value="'+data.isAreaDeleted+'"><input type="hidden" name="date" value="'+data.date+'"><input type="hidden" name="startTime" value="'+data.startTime+'"><input type="hidden" name="duration" value="'+data.duration+'"><input type="hidden" name="userId" value="'+data.userId+'"><input type="hidden" name="eventRepeat" value="' + data.eventRepeat + '"><input type="hidden" name="eventRepeatInterval" value="' + data.eventRepeatInterval + '"><input type="hidden" name="eventRepeatEnd" value="' + data.eventRepeatEnd + '"><input type="hidden" name="eventRepeatEndAfterOccur" value="' + data.eventRepeatEndAfterOccur + '"><input type="hidden" name="eventRepeatEndOnDate" value="' + data.eventRepeatEndOnDate + '"><input type="hidden" name="isRepeating" value="' + data.isRepeating + '"><input type="hidden" name="appointStatusOpt" value="' + data.appointStatusOpt + '">';
								if (data.eventRepeatWeekDays != 'null' && data.eventRepeatWeekDays != null && data.eventRepeatWeekDays != undefined && data.eventRepeatWeekDays != '') var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
								else var eventRepeatWeekDays = [];
								$.each(eventRepeatWeekDays, function(key, val) {
									eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\'' + val + '\'>';
								});	
								for (var i in data.staff)
				
								eventHtml += '<input type="hidden" name="staff[]" value="' + escQuotes(JSON.stringify(data.staff[i])) + '">';

								$this.find('input').remove();
								$this.find('.calendEvent').prepend(eventHtml);
								eventObj = $this;

								showBusyModal('edit');
							},'json');
						}
					}

					
					if(!rescheduleClientsCount())
						resetAllModes();
				}
			},
			eventRender: function(event, element, view){
				var contentElem = element.find(".fc-content"),
				eventHtml = '<input type="hidden" name="type" value="'+event.type+'"><input type="hidden" name="eventId" value="'+event.id+'">',
				iconHtml = '';

				if(event.type == 'appointment'){
					eventHtml += '<input type="hidden" name="date" value="'+event.date+'"><input type="hidden" name="startTime" value="'+event.startTime+'"><input type="hidden" name="duration" value="'+event.duration+'"><input type="hidden" name="staffId" value="'+event.staffId+'"><input type="hidden" name="userId" value="'+event.userId+'"><input type="hidden" name="staffName" value="'+event.staffName+'"><input type="hidden" name="isStaffDeleted" value="'+event.isStaffDeleted+'"><input type="hidden" name="serviceName" value="'+event.serviceName+'"><input type="hidden" name="appointStatusOpt" value="'+event.appointStatusOpt+'"><input type="hidden" name="appointStatusConfirm" value="'+event.appointStatusConfirm+'"><input type="hidden" name="autoExpireAppointDur" value="'+event.autoExpireAppointDur+'"><input type="hidden" name="autoExpireDatetime" value="'+event.autoExpireDatetime+'"><input type="hidden" name="clientName" value="'+event.title+'"><input type="hidden" name="clientId" value="'+event.clientId+'"><input type="hidden" name="clientEmail" value="'+event.clientEmail+'"><input type="hidden" name="clientNumb" value="'+event.clientNumb+'"><input type="hidden" name="serviceId" value="'+event.serviceId+'"><input type="hidden" name="price" value="'+event.price+'"><input type="hidden" name="locAreaName" value="'+event.locAreaName+'"><input type="hidden" name="notes" value="'+event.notes+'"><input type="hidden" name="eventRepeat" value="'+event.eventRepeat+'"><input type="hidden" name="eventRepeatInterval" value="'+event.eventRepeatInterval+'"><input type="hidden" name="eventRepeatEnd" value="'+event.eventRepeatEnd+'"><input type="hidden" name="eventRepeatEndAfterOccur" value="'+event.eventRepeatEndAfterOccur+'"><input type="hidden" name="eventRepeatEndOnDate" value="'+event.eventRepeatEndOnDate+'"><input type="hidden" name="isRepeating" value="'+event.isRepeating+'"><input type="hidden" name="isServiceDeleted" value="'+event.isServiceDeleted+'"><input type="hidden" name="isClientDeleted" value="'+event.isClientDeleted+'"><input type="hidden" name="ifAreaHasStaffs" value="'+event.ifAreaHasStaffs+'"><input type="hidden" name="areaId" value="'+event.areaId+'"><input type="hidden" name="isAreaDeleted" value="'+event.isAreaDeleted+'">'; //<input type="hidden" name="userName" value="'+event.userName+'">

					for(var i in event.eventRepeatWeekDays)
						eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\''+(event.eventRepeatWeekDays[i])+'\'>';

					for(var i in event.histories)
						eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\''+JSON.stringify(event.histories[i])+'\'>';

					if(event.isRepeating == 1)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Appointemnt"><i class="fa fa-retweet"></i></span>';
					
					eventStatusIcon = '';
					if(event.appointStatusConfirm == 'Arrived')
						var eventStatusIcon = 'fa fa-sign-in';
					else if(event.appointStatusConfirm == 'Completed')
						var eventStatusIcon = 'fa fa-check';
					else if(event.appointStatusConfirm == 'Did not show')
						var eventStatusIcon = 'fa fa-thumbs-down';
					if(eventStatusIcon)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+event.appointStatusConfirm+'"><i class="'+eventStatusIcon+'"></i></span>';

					if(event.notes)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Comment or note"><i class="fa fa-comment"></i></span>';
					
					if(iconHtml)
						eventHtml += '<div class="eventStatusIcon">'+iconHtml+'</div>';
				}
				else if(event.type == 'class'){
					/*eventHtml += '<input type="hidden" name="classId" value="'+event.classId+'"><input type="hidden" name="capacity" value="'+event.capacity+'"><input type="hidden" name="price" value="'+event.price+'"><input type="hidden" name="className" value="'+event.title+'"><input type="hidden" name="notes" value="'+event.notes+'"><input type="hidden" name="eventRepeat" value="'+event.eventRepeat+'"><input type="hidden" name="eventRepeatInterval" value="'+event.eventRepeatInterval+'"><input type="hidden" name="eventRepeatEnd" value="'+event.eventRepeatEnd+'"><input type="hidden" name="eventRepeatEndAfterOccur" value="'+event.eventRepeatEndAfterOccur+'"><input type="hidden" name="eventRepeatEndOnDate" value="'+event.eventRepeatEndOnDate+'"><input type="hidden" name="isRepeating" value="'+event.isRepeating+'"><input type="hidden" name="isClassDeleted" value="'+event.isClassDeleted+'">'; //<input type="hidden" name="userName" <input type="hidden" name="locAreaName" value="'+event.locAreaName+'">value="'+event.userName+'">

					for(var i in event.eventRepeatWeekDays)
						eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\''+(event.eventRepeatWeekDays[i])+'\'>';
					
					var linkedClientsCount = 0;
					for(var i in event.clients){
						linkedClientsCount++;
						eventHtml += '<input type=\'hidden\' name=\'client[]\' value=\''+JSON.stringify(event.clients[i])+'\'>';
					}

					for(var i in event.areas)
						eventHtml += '<input type=\'hidden\' name=\'area[]\' value=\''+JSON.stringify(event.areas[i])+'\'>';

					for(var i in event.histories)
						eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\''+JSON.stringify(event.histories[i])+'\'>';
					
					if(!event.capacity || event.capacity < linkedClientsCount)
						var remainingCapacity = 0;
					else
						var remainingCapacity = event.capacity - linkedClientsCount;*/

					var clientNamesForTooltip = '';
					for(var i in event.clients){
						if(!event.clients[i].isClientDeleted)
							clientNamesForTooltip += event.clients[i].name+'<br>';
					}
					if(clientNamesForTooltip)
						contentElem.attr('title', clientNamesForTooltip).tooltipster({'contentAsHTML':true});

					if(!event.capacity || event.capacity < event.clientsCount)
						var remainingCapacity = 0;
					else
						var remainingCapacity = event.capacity - event.clientsCount;
					if(event.isRepeating == 1)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Class"><i class="fa fa-retweet"></i></span>';
					
					if(event.notes)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Comment or note"><i class="fa fa-comment"></i></span>';
					
					if(iconHtml)
						eventHtml += '<div class="eventStatusIcon">'+iconHtml+'</div>';
				}
				else if(event.type == 'single-service'){
					/*eventHtml += '<input type="hidden" name="price" value="'+event.price+'"><input type="hidden" name="notes" value="'+event.notes+'"><input type="hidden" name="eventRepeat" value="'+event.eventRepeat+'"><input type="hidden" name="eventRepeatInterval" value="'+event.eventRepeatInterval+'"><input type="hidden" name="eventRepeatEnd" value="'+event.eventRepeatEnd+'"><input type="hidden" name="eventRepeatEndAfterOccur" value="'+event.eventRepeatEndAfterOccur+'"><input type="hidden" name="eventRepeatEndOnDate" value="'+event.eventRepeatEndOnDate+'"><input type="hidden" name="isRepeating" value="'+event.isRepeating+'"><input type="hidden" name="appointStatusOpt" value="'+event.appointStatusOpt+'"><input type="hidden" name="autoExpireAppointDur" value="'+event.autoExpireAppointDur+'"><input type="hidden" name="autoExpireDatetime" value="'+event.autoExpireDatetime+'"><input type="hidden" name="serviceName" value="'+event.serviceName+'"><input type="hidden" name="serviceId" value="'+event.serviceId+'"><input type="hidden" name="isServiceDeleted" value="'+event.isServiceDeleted+'"><input type="hidden" name="salesProcessEvent" value="'+event.salesProcessEvent+'"><input type="hidden" name="consultationDate" value="'+event.consultationDate+'">';


					for(var i in event.eventRepeatWeekDays)
						eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\''+(event.eventRepeatWeekDays[i])+'\'>';
					
					for(var i in event.clients){
						eventHtml += '<input type=\'hidden\' name=\'client[]\' value=\''+JSON.stringify(event.clients[i])+'\'>';
						var clientAttendance = event.clients[i].attendance;
					}

					for(var i in event.areas)
						eventHtml += '<input type=\'hidden\' name=\'area[]\' value=\''+JSON.stringify(event.areas[i])+'\'>';

					for(var i in event.histories)
						eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\''+JSON.stringify(event.histories[i])+'\'>';
					*/
					for(var i in event.clients)
						var clientAttendance = event.clients[i].attendance;
					if(clientAttendance == 'Attended')
						var eventStatusIcon = 'fa fa-check';
					else if(clientAttendance == 'Did not show')
						var eventStatusIcon = 'fa fa-thumbs-down';
					else
						var eventStatusIcon = '';
					if(eventStatusIcon)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="'+clientAttendance+'"><i class="'+eventStatusIcon+'"></i></span>';

					if(event.isRepeating == 1 && event.isMakeUp != 1 && event.isWithInvoice != 1)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Service"><i class="fa fa-retweet"></i></span>';

					if(event.isMakeUp == 1)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Make Up"><i class="fa fa-code-fork"></i></span>';

					//if(event.isEpicCredit == 1)
					if(event.epicCreditBal)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="EPIC Credit - $'+event.epicCreditBal+'"><i class="fa fa-money"></i></span>';

					if(event.isWithInvoice)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Invoiced"><i class="fa fa-bank"></i></span>';


					if(!event.epicCreditBal && !event.isWithInvoice)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Membership"><i class="fas fa-user-shield"></i></span>';

					if(event.notes)
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Comment or note"><i class="fa fa-comment"></i></span>';

					if(event.isIndiv != 'null' && event.isIndiv && event.isIndiv != 'false')
						iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Individual"><i class="fa fa-user"></i></span>';

					if(iconHtml)
						eventHtml += '<div class="eventStatusIcon">'+iconHtml+'</div>';
				}
				/*else
				eventHtml += '<input type="hidden" name="description" value="'+event.description+'"><input type="hidden" name="denyBooking" value="'+event.denyBooking+'"><input type="hidden" name="ifAreaHasStaffs" value="'+event.ifAreaHasStaffs+'"><input type="hidden" name="areaId" value="'+event.areaId+'"><input type="hidden" name="isAreaDeleted" value="'+event.isAreaDeleted+'">';*/

				/*if(event.isStaffDeleted)
					var eventTitle = '<del>'+event.title+'</del>';
					else*/
						if(event.type == 'single-service' && event.clients[0].isClientDeleted)
							var eventTitle = '<del>'+event.title+'</del>';
						else
							var eventTitle = event.title;

						if(view.type == 'agendaDay'){
							eventHtml +=   
							'<strong>' +
						//eventTitle +
						moment(event.start).format("h:mm A") + ' - '+ moment(event.end).format("h:mm A") +
						'</strong> ';

						if(event.type == 'appointment' || event.type == 'single-service'){
						eventHtml += '<small>' +//<span class="eventTimeRange">
							//moment(event.start).format("h:mm A") + ' - '+ moment(event.end).format("h:mm A") + 
							eventTitle +
						'</small><br/>'+//</span>
						event.serviceName+' - $'+event.price;
					}
					else if(event.type == 'class'){
						eventHtml += '<small>&nbsp;&nbsp;' +
							//moment(event.start).format("h:mm A") + 
							eventTitle +
							'</small><br/>'+
							/*linkedClientsCount*/event.clientsCount+' booked/'+remainingCapacity+' available - $'+
							event.price;
						}
						else{
							eventHtml += '<small>&nbsp;&nbsp;' +
							moment(event.start).format("h:mm A") + 
							'</small>';
						}
					}
					else if(view.type == 'agendaWeek'){ 
						eventHtml +=     
						'<strong>' +
						//eventTitle +
						moment(event.start).format("h:mm A") + ' - '+ moment(event.end).format("h:mm A") +
						'</strong>';

						if(event.type == 'appointment' || event.type == 'single-service'){
							eventHtml += '<br/>' +
						'<small>' +//<span class="eventTimeRange">
							//moment(event.start).format("h:mm A") + ' - '+ moment(event.end).format("h:mm A") + 
							eventTitle +
						'</small><br/>'+//</span>
						event.serviceName+' - $'+event.price;
					}
					else if(event.type == 'class'){
						eventHtml += '<small>&nbsp;&nbsp;' +
							//moment(event.start).format("h:mm A") + 
							eventTitle +
							'</small><br/>'+
							/*linkedClientsCount*/event.clientsCount+' booked/'+remainingCapacity+' available -<br>$'+
							event.price;
						}
						else{
							eventHtml += '<small>&nbsp;&nbsp;' +
							moment(event.start).format("h:mm A") + 
							'</small>';
						}
					}
					else if(view.type == 'month'){
						eventHtml +=      
						'<strong><span class="eventTimeRange">' +
						moment(event.start).format("h:mm A") + ' - '+ moment(event.end).format("h:mm A") + 
						'</span></strong><br/>' +
						eventTitle;
					}

					contentElem.addClass('calendEvent').html(eventHtml);

					if(event.type == 'appointment'){
						if(event.appointStatusOpt == 'Pencilled-In')
							event.color = '#7e8085';

						contentElem.addClass('appointment-'+event.id)
						if(!isSalesProcessMode())
							element.popover(editEventPopoverOpt)
					}
					else if(event.type == 'single-service'){
						if(event.appointStatusOpt == 'Pencilled-In')
							event.color = '#7e8085';
					}
					var fontColor = invertColor(event.color);
					if(event.type == 'class'){
					//var colorwidth= (linkedClientsCount/event.capacity)*100;
					var colorwidth= (event.clientsCount/event.capacity)*100;
					if(colorwidth<20) fontColor='#000000';
					var backGroundColor = '-moz-linear-gradient(left,'+event.color+', '+event.color+' '+ colorwidth +'%, #ddd 1%)';
					contentElem.css({"background": backGroundColor});
					var backGroundColor = '-webkit-linear-gradient(left,'+event.color+', '+event.color+' '+ colorwidth +'%, #ddd 1%)';
					contentElem.css({"background": backGroundColor});
				}
				else
					contentElem.css({"background-color":event.color});

				contentElem.css({"border-top":event.color,"color":fontColor});
				if(iconHtml)
					contentElem.find('.epic-tooltip').tooltipster();

				element.addClass('calendEventWrap').attr('id', event.type+'-'+event.id);
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

				if(!calendarFirstTime && findStaffId() != 'all-ros'){
					getStaffEvents(0, true, 0, false);
					//getStaffHours();
				}
				/*else
				calendarFirstTime = false;*/

				if(view.type == 'agendaDay'){
					dayviewcolText(element);

					slotsDispTime(element, 'day', view.intervalStart);

					markFreeDay(element.find('.fc-bg td.fc-day'))

					element.find('.fc-slot-time').popover(addEventPopoverOpt)

					//markWorkingSlot(element);
					getHoursDataAndMarkSloat();
					refreshCalendForRostStaff();
					//setTimeout(function(){ scrollToTimeslot(element); }, 100);
				}
				else if(view.type == 'agendaWeek'){
					weekviewcolLink(element);

					slotsDispTime(element, 'week');

					markFreeDay(element.find('.fc-bg td.fc-day'));

					element.find('.fc-slot-time').popover(addEventPopoverOpt)

					//markWorkingSlot(element);
					getHoursDataAndMarkSloat();
					refreshCalendForRostStaff();
					//setTimeout(function(){ scrollToTimeslot(element); }, 100);
					
				}
				else if(view.type == 'month'){
					element.find('.fc-day').popover(addEventPopoverOpt)
					if(!calendarFirstTime)
						getHoursDataAndMarkSloat();
						//markWorkingDay(element)

						var todayCell = element.find('.fc-today');
						if(todayCell.length){
							todayCell.addClass('today');
						}

					/*var todayCell = element.find('.fc-today');
					if(todayCell.length){
						todayCell.addClass('today');
						$('html, body').animate({
		                    scrollTop: todayCell.offset().top
		                },0);
		            }*/
		        }
				//calendarFirstTime = false;
			},
			eventAfterAllRender: function(view){
				stickyFilter();
				/*var leftPos=$('#calendar').offset();
				$('#calendar').children('fc-tool');*/
				/* Event handler to open appointment modal in add mode */
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

				/*if(calendarFirstTime){
					if(view.type == 'month'){
						var todayCell = $('.fc-month-view .fc-today');
						if(todayCell.length){
							todayCell.addClass('today');
							//alert('ok')
							setTimeout(function(){
								scrollToTop(todayCell);
								/*$('html, body').animate({
				                    scrollTop: todayCell.offset().top
				                },0);*
				            }, 500);
						}
					}
					else if(view.type == 'agendaWeek'){
						setTimeout(function(){ scrollToTimeslot($('.fc-agendaWeek-view')); }, 100);
					}
					else if(view.type == 'agendaDay'){
						setTimeout(function(){ scrollToTimeslot($('.fc-agendaDay-view')); }, 100);
					}
				}*/

				var titleDatepicker = calendar.find('.fc-titleDatepicker-button');
				titleDatepicker.html(calendar.find('h2').text());

				titleDatepicker.closest('div').addClass('fc-date-picker-btn custom-calendar-width');
				titleDatepicker.closest('.fc-center').addClass('custom-parent');

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

				/* Intergrate event adder into calendar */
				if(!calendar.find('.addBtnGroup').length)
					$('.addBtnGroup').appendTo(calendar.find('.fc-left'))

				/* Intergrate sale creator into calendar */
				if(!calendar.find('.saleBtnGroup').length)
					$('.saleBtnGroup').appendTo(calendar.find('.fc-left'))

				/* Intergrate location-area into calendar */
				if(!calendar.find('.loc-area-dd').length){
					var dd = $('.toolBarDd.loc-area-dd').eq(0);
					if(isSalesProcessMode() || isCreateServiceMakeUpMode())
						dd.addClass('m-l-0')
					
					dd.appendTo(calendar.find('.fc-left'))
				}

				/* Intergrate filter into calendar */
				if(!calendar.find('.a').length)
					$('.staff-filter-cal').eq(0).appendTo(calendar.find('.fc-left'))

				/*$("body, html").animate({ 
					scrollTop:scrollPos
				}, 200);*/

				// open event from makeup list
				if(typeof makeup_eventid != 'undefined' && typeof makeup_eventtype != 'undefined' && makeup_eventtype != '' && makeup_eventid != '' && isOpenMakeupEvent){
					if(typeof makeup_eventdate != 'undefined' && makeup_eventdate != ''){
						var date = moment(makeup_eventdate);
						calendar.fullCalendar('gotoDate', date);
					}

					if(makeup_eventtype == 'class')
						var custom_event_btn = calendar.find('#class-'+makeup_eventid);
					else if(makeup_eventtype == 'service')
						var custom_event_btn = calendar.find('#single-service-'+makeup_eventid);

					if(custom_event_btn.length > 0){
						custom_event_btn.trigger('click');
						isOpenMakeupEvent = false;
					}
				}
			}
		});

	/* Initializing jquery validation */
	initCustomValidator();

	/* Initializing 'select a date' alert notification */
	if(isSalesProcessMode() || isCreateServiceMakeUpMode())
		dateSelMsg.addClass('subviewMode')
	else{
			/*var headElem = calendar.closest(".app-content").find('header');
			dateSelMsg.height(headElem.height()-20);*/
			dateSelMsg.height(headerHeight-20);
		}
		dateSelMsg.find('a').click(function(e){
			resetEnableDisableDateFields();
			if(rescheduleClientsCount() || resheduleAppointDateSelMode){
				showClassModal('add');
				areaDdCalend.selectpicker('val', areaDdCalendLastVal);
				getAreaStaffs(areaDdCalend, staffFilterCalendLastVal);
			}
			else if(isClassMarge){
				isClassMarge = false;
				areaDdCalend.selectpicker('val', areaDdCalendLastVal);
				getAreaStaffs(areaDdCalend, staffFilterCalendLastVal);
			}
			
			resetAllModes(e);
		})

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

		/* Event handler to open appointment modal in edit mode */
		$('body').on('click', '.appointEdit', function(e){
			e.preventDefault();
			showAppointmentModal('edit');
		});

		/* Event handler to cancel appointment */
		$('body').on('click', '.appointDeleteForce', function(e){
			e.preventDefault();
			calPopupHelper.trigger("click");
			deleteAppoint(true);
		});

		/* Event handler to resechedule appointment */
		$('body').on('click', '.appointReschedule', function(e){
			resheduleAppointDateSelMode = true;
			openSelDateNotif('Choose a new time for this service.', e);
		});

		$('body').on('click', '.busyAppointReschedule', function(e){
			resheduleAppointDateSelMode = true;
			$(this).closest('.modal').modal('hide');
			busyModal.modal('hide');
			openSelDateNotif('Choose a busy time.', e);
		});



		/* Event handler to open appointment modal in book appointment again mode */
		$('body').on('click', '.appointBookNext', function(e){
			bookNextAppointDateSelMode = true;
			openSelDateNotif('Choose a time for the next service.', e);
		});

		/* Event handler to change appointment status */
		$('body').on('click', '.appointStatusChange', function(e){
			e.preventDefault();
			var $this = $(this);
			if(!$this.hasClass('active')){
				calPopupHelper.trigger("click");
				changeAppointStatus($this.data('new-status'), $this.data('confirm'));
			}
		});

		/* Event handler to change event date from modals */
		$('.eventDateChange').click(function(e){
			//var modal = $(this).closest('.modal');
			changeEventDateDateSelMode = true;
			
			$(this).closest('.modal').modal('hide');

			//modal.modal('hide');
			$('input[name="changeSessionTime"]').val(classModal.find('input[name="eventTime"]').val());
			if(eventModal == 'appointModal')
				openSelDateNotif('Choose a new time for this service.', e);
			else if(eventModal == 'classModal'){
				openSelDateNotif('Choose a new time for this booking.', e);
	
				//if(isSalesProcessEvent() && $.inArray(eventObj.find('input[name="salesProcessEvent"]').val(), ['4', '6']) !== -1 ){
					if(isSalesProcessEvent()){
						var stepNumb = parseInt(eventObj.find('input[name="salesProcessEvent"]').val(), 10);
						if(!isNaN(stepNumb)){
							if(stepNumb == 4 || $.inArray(stepNumb, indivBookingSteps()) > -1 || $.inArray(stepNumb, teamBookingSteps()) > -1){
								setEnableDisableDateFields();
								markWorkingHelper();
							}
						}
					}
				}
				else
					openSelDateNotif('Choose a new time for this busy-time.', e);

			//getStaffHours(modal.find('select[name="staff"]').val());
		});

		/* Event handler to add new event */
		$('body').on('click', '.eventAdd', function(e){
			eventModal = $(this).data('target-modal');
			addEventDateSelMode = true;

			if(eventModal == 'appointModal')
				openSelDateNotif('Choose a time for the new service.', e);
			else if(eventModal == 'classModal')
				//openSelDateNotif('Choose a time for the new Class.', e);
			openSelDateNotif('Choose a time for the new booking.', e);
			else
				openSelDateNotif('Choose a time for the Busy Time.', e);
		});

		/* Event handler to open appointment modal in add mode */
		$('body').on('click', '.appointAddLnk', function(e){
			e.preventDefault();
			showAppointmentModal('add');
		});

		/* Event handler to open class modal in add mode */
		$('body').on('click', '.classAddLnk', function(e){
			e.preventDefault();
			showClassModal('add');
		});

		/* Event handler to open class modal in add mode */
		$('body').on('click', '.bookAddLnk', function(e){
			e.preventDefault();
			showClassModal('add');
		});

		/* Event handler to open class modal in add mode */
		$('body').on('click', '.busyAddLnk', function(e){
			e.preventDefault();
			showBusyModal('add');
		});

		classModal.on('hide.bs.modal', function(){
			var eventId = classModal.find('input[name="eventId"]').val();
			if(!reloadEvents && getBookingType() == 'service' && isModalModeEdit() && isServiceFresh()) 
				reloadEvents = true;

			if(reloadEvents) {
				if(window.location.href.includes('client')) 
					reloadPageWithAppointmentTab();
				else {
					getStaffEvents(0, true,0,true,false,eventId);
					reloadEvents = true;
				}
			}
		});

		busyModal.on('hide.bs.modal', function(){
			calPopupHelper.trigger("click");
		});

		$('body').on("click", '.reschedule-event', function(e){
			e.preventDefault();

			var targetEvents = $(this).data('target-event');
			rescheduleAppointment(recurrAppointReschecModal.find('input[name="date"]').val(), recurrAppointReschecModal.find('input[name="time"]').val(), targetEvents);
		});

		$('body').on("click", '.reschedule-class_client', function(e){
			e.preventDefault();
			rescheduleClass_client($(this).data('target-event'));
		});

		/* Submitting busy modal data */
		$(".submit", busyModal).click(function(){
			var formData = {},
			form = busyModal.find('form');
			busyTabId = busyModal.find('ul.nav li.active a').attr('href');
			activeTab = busyTabId.substring(1);
			eventRepeat = form.find('select[name="eventRepeat"]').val();


			var isFormValid = form.valid();
			if(activeTab == 'busyReccurs'){
				var busyRepeatWeekdaysGroup = form.find('.busyRepeatWeekdays'),
				busyRepeatWeekdays = busyRepeatWeekdaysGroup.find('input[type="checkbox"]:checked');
				if (!busyRepeatWeekdays.length && eventRepeat == 'Weekly') {
					isFormValid = false;
					setFieldInvalid(busyRepeatWeekdaysGroup.closest('.form-group'), 'Please select reccurence days.', busyRepeatWeekdaysGroup.next())
				}
			}else{
				var areaDd = form.find('select[name="modalLocArea"]');
				if(isFieldMarkedAsDeleted(areaDd)){
				isFormValid = false;
				setFieldInvalid(areaDd.closest('.form-group'), 'Please select new area.')
				}
			}
			moveErrorMsg(busyModal);
			if(isFormValid){
				toggleWaitShield('show','busySubmit');
				form.find(':input').each(function(){
					formData[$(this).attr('name')] = $(this).val()
				})
				formData['staff'] = [];
				if(formData['eventId'] != null && formData['eventId'] != ''){
					form.find('.staffList').each(function(){
						formData['staff'].push($(this).data('staff-id'));
					})
				}else{
					form.find('input[name="staffName"]:checked').each(function(){
						formData['staff'].push($(this).val());
					});
				}				
				var eventDate = setEventDate(busyModal, 'submit');
				formData['step'] = activeTab;

				if (activeTab == 'busyReccurs') {
					formData['calendEndDate'] = getCalendEndDatePriorDate();
					if (formData['eventRepeat'] == 'Daily' || formData['eventRepeat'] == 'Weekly' || formData['eventRepeat'] == 'Monthly') {
						formData['eventRepeatEnd'] = form.find('input[name="busyEventRepeatEnd"]:checked').val();
						if (formData['eventRepeatEnd'] == 'On') formData['eventRepeatEndOnDate'] = moment(formData['eventRepeatEndOnDate'], 'D MMM YYYY').format("YYYY-MM-DD");
						/*else if(formData['eventRepeatEnd'] == 'Never')
							formData['calendEndDate'] = getCalendEndDatePriorDate();*/ //getCalendEndDate();
						if (eventRepeat == 'Weekly') {
							var temp = [],
							busyRepeatWeekdays = form.find('.busyRepeatWeekdays input[type="checkbox"]:checked');
							busyRepeatWeekdays.each(function() {
								temp.push($(this).val());
							})
							formData['busyRepeatWeekdays'] = temp;	
						}
					}
				}else{
					formData['busyDate'] = eventDate.date;
					formData['busyDay'] = eventDate.day;

					formData['busyTime'] = timeStringToDbTime(formData['eventTime']);

					formData['busyDenyBook'] = form.find("input[name='busyDenyBook']:checked").val();
				}
				if(formData['eventId'] != null && formData['eventId'] != '')
					ajaxAction = public_url+'staffevents/busy-time/edit';
				else
					ajaxAction = public_url+'staffevents/busy-time/create';
				$.post(ajaxAction, formData, function(data){
					var data = JSON.parse(data);
					if(data.status == "added"){
						busyModal.modal('hide');
						showBooking.id = data.eventId
						showBooking.type = data.type
						switchTabTo('busyReccurs', busyModal)
						getStaffEvents();
						if(data.message != undefined){
							showNotific(data.message);
						}
						// getStaffEvents();
						showNotific(data.message);
					}else if(data.status == 'updated'){
						
						if(data.repeatId != undefined && data.repeatId != ''){
							reloadEvents = false;
							getStaffEvents(0,true, data.repeatId,false,false,null,false,true);
						}else{
							reloadEvents = false;
							getStaffEvents();
							
						}
						if(data.message != undefined){
							showNotific(data.message);
						}
						busyModal.modal('hide');
						showNotific(data.message);
					}else if(data.status == "error"){
						$.each(data.errorData, function(key, val){
							$.each(this, function(errorType, message){
								if(errorType == "workingHourUnavail" )
									var field = form.find("span.eventDateDisp"); 								

								setFieldInvalid(field.closest('.form-group'), message)
								moveErrorMsg(classModal);
							});							
						});
					}
					toggleWaitShield('hide','busySubmit');
				});
			}
		});

		/* Event handler to Toggle credit expiry date */
		$('input[name="creditExpireNever"]').change(function(){
			var creditExpire = $('input[name="creditExpire"]')

			if($(this).is(":checked")){
				creditExpire.prop('disabled', true)
				creditExpire.val('');
				setFieldNeutral($(this))
			}
			else
				creditExpire.prop('disabled', false)
		})

		/* Resetting credit modal form on opening */
		creditModal.on('show.bs.modal', function(event){
			resetAllModes();
			clearForm($(event.target).find('form'));
			$(event.target).find('input[name="creditExpireNever"]').trigger('change');
			fetchClientsForTypeahead('update');
		});

		/* Submitting credit modal data */
		$(".submit", creditModal).click(function(){
			var formData = {},
			form = creditModal.find('form'),
			isFormValid = form.valid();

			if(!validateTypeaheadClient(form))
				isFormValid = false;

			var creditExpireNever = form.find("input[name='creditExpireNever']:checked").val(),
			creditExpire = $('input[name="creditExpire"]');
			if(creditExpireNever == null && creditExpire.val() == ''){
				isFormValid = false;
				setFieldInvalid(creditExpire.closest('.form-group'), 'This field is required.');
			}

			if(isFormValid){
				form.find(':input').each(function(){
					formData[$(this).attr('name')] = $(this).val()
				})

				formData['creditExpireNever'] = creditExpireNever;
				if(formData['creditExpireNever'] == null)
					formData['creditExpire'] = moment(formData['creditExpire']).format("YYYY-MM-DD")

				$.post(public_url+'client-credits', formData, function(data){
					var data = JSON.parse(data);
					if(data.status == "added"){
						creditModal.modal('hide');
						showNotific(data.message);
					}
				});
			}
		});

		/* Event handler to populate events and working hours based on the staff */
		$('body').on('change', 'select.staff-filter-cal', function(){
			staffFilterCal($(this).val());
			$(document).ajaxStop(function () {
				setTimeout(function(){ // Timeout
					$('html, body').animate({
						scrollTop: $('td.fc-today:first').offset().top -200
					}, 0);
				},2000);
			  });
		})

		/* Event handler to populate staffs based on the area */
		/*if(isRescheduleClientMode())
			enterReschecduleClientMode();
			else*/ if(isCreateMakeUpMode() || isCreateServiceMakeUpMode()){
				areaDdCalend.selectpicker('val', 'all');
				areaDdCalend.trigger('change');
			}
			else if(areaDdCalend.val())
			//getAreaStaffs(areaDdCalend);
		staffFilterCalend.trigger('change');

		noRostStaff.find('a').click(function(e){
			e.preventDefault();

			staffFilterCalend.val('all');
			staffFilterCalend.selectpicker('refresh');
			staffFilterCalend.trigger('change');
		})

		/* Popup helper */
		calPopupHelper.height($(document).height());
		calPopupHelper.click(function(){
			calendar.find('.calendEventWrap').popover('hide');

			/*if(calendar.find('.fc-month-view').length)
				//calendar.find('.fc-day').popover('hide');
			
			else if(calendar.find('.fc-agendaWeek-view').length || calendar.find('.fc-agendaDay-view').length)
				//calendar.find('.fc-slot-time').popover('hide');*/

			resetEnableDisableDateFields();

			if(isSalesProcessMode() || isCreateServiceMakeUpMode() || addEventDateSelMode || bookNextAppointDateSelMode || resheduleAppointDateSelMode || changeEventDateDateSelMode){
				setTimeout(function(){ 
					calendar.find('.highlightCell').removeClass('highlightCell');
					$('.popover').popover('hide');
				}, 500);
			}
			else{
				calendar.find('.highlightCell').removeClass('highlightCell');
				$('.popover').popover('hide');
			}

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
		$('body').on('click', '#calendar .fc-agendaWeek-view .fc-day-header a:not(".editHrs")', function(e){
			e.preventDefault();
			
			calendar.fullCalendar('changeView', 'agendaDay')
			calendar.fullCalendar('gotoDate', $(this).parent().data('date'))
		})

		/* Event handler to switch to week view from day view */
		$('body').on('click', '#calendar .fc-agendaDay-view .fc-day-header a:not(".editHrs")', function(e){
			e.preventDefault();
			calendar.fullCalendar('changeView', 'agendaWeek')
		})

		/* Event handler to open working hours modal */
		/*$('body').on('click', '#calendar a.editHrs', function(e){
			e.preventDefault();
			workingHrsModal.modal('show');	
		})*/

		workingHrsModal.find('.attendenceDatepicker').datepicker({autoclose:true, dateFormat:"D, d M yy"});
		$('body').on('click', '#calendar a.editHrs', function(e){
			e.preventDefault();
			toggleWaitShield('show');
			var $this = $(this),
			formData =  {},
			date = $this.closest('.fc-day-header').data('date');

			if(moment(date) > moment()){
				$('button[data-modal-button-action="reset"]', workingHrsModal).prop('disabled', false);
				$('button[data-modal-button-action="submit"]', workingHrsModal).prop('disabled', false);
			}
			else{
				$('button[data-modal-button-action="reset"]', workingHrsModal).prop('disabled', true);
				$('button[data-modal-button-action="submit"]', workingHrsModal).prop('disabled', true);
			}

			formData['staffId'] = findStaffId();
			formData['date'] = date;
			formData['day'] = moment(date).format('dddd');

			if(formData['staffId'] != 'all' && formData['staffId'] != 'all-ros'){
				$.get(public_url+'staffs/editHours', formData, function(data){
					populateNewHour(data, formData['staffId']);
					//populateHours(data, formData['staffId']);
					workingHrsModal.find('input[name="start_attend_date"]').val(dbDateToDateString(date));
					workingHrsModal.modal('show');
					toggleWaitShield('hide');
				},'json');	
			}
			
		})

		/* Start: hide working modal */
		workingHrsModal.on('hide.bs.modal', function(){
			workingHrsModal.find('.new-row').remove();
			workingHrsModal.find('input[name="upto_attend_date"]').val('');
			workingHrsModal.find('input[name="staffAttend"]').prop("checked", true);
			workingHrsModal.find('#toggel-field-id').show();
		})
		/* End: hide working modal */

		/* Start: toggel leave and working radio */
		$('input[name="staffAttend"]', workingHrsModal).change(function(){
			var $field = workingHrsModal.find('#toggel-field-id'),
			$value = workingHrsModal.find('input[name="staffAttend"]:checked').val();

			if($value == 'leave')
				$field.hide();
			else
				$field.show();
		})
		/* Start: toggel leave and working radio */

		/* Start: Reset Staff attendence */
		$('button[data-modal-button-action="reset"]', workingHrsModal).click(function(){
			toggleWaitShield('show','staffWorkingReset');
			var formData = {},
			form = workingHrsModal.find('form');

			formData['staffId'] = 	form.find('input[name="staffId"]').val();
			formData['date'] = 	form.find('input[name="start_attend_date"]').val();
			$.ajax({
				url: public_url+'staffs/editHours/reset/'+formData['staffId'],
				method: "POST",
				data: formData,
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "updated"){
						//workingHrsModal.modal('hide');
					}
					getHoursDataAndMarkSloat();
					workingHrsModal.modal('hide');
					toggleWaitShield('hide','staffWorkingReset');
				}
			});
		})
		/* End: Reset Staff attendence */

		/* Start: Saving working hours modal data */
		$('button[data-modal-button-action="submit"]', workingHrsModal).click(function(){
			var formData = {},
			form = workingHrsModal.find('form'),
			isFormValid = form.valid();
			
			if(isFormValid){
				toggleWaitShield('show','staffWorkingSave');
				//formData['action_type'] = workingHrsModal.find('input[name="staffAttend"]:checked').val();
				$.each(form.find(':input').serializeArray(), function(i, field){
					if(field.value != '')
						formData[field.name] = field.value; 
				})
				
				$.ajax({
					url: public_url+'staffs/editHours/'+formData['staffId'],
					method: "POST",
					data: formData,
					success: function(data){
						var data = JSON.parse(data);
						if(data.status == "updated"){
							getHoursDataAndMarkSloat();
							workingHrsModal.modal('hide');
							//setStaffHours(data.hoursData)
						}
						toggleWaitShield('hide','staffWorkingSave');
					}
				});
			}
		});
		/*$('button[data-modal-button-action="submit"]', workingHrsModal).click(function(){
			var formData = {},
				form = workingHrsModal.find('form');
			
			form.find(':input').each(function(){
				var type = this.type,
					$this = $(this);

				if(type != 'checkbox' || (type == 'checkbox' && $this.is(':checked')))
					formData[$this.attr('name')] = $this.val();
			})
			
			//formData['calStartDate']=getCalendStartDate();

			$.ajax({
				url: public_url+'staff-hours/'+formData['staffId'],
				method: "POST",
				data: formData,
				success: function(data){
					var data = JSON.parse(data);
					if(data.status == "updated"){
						workingHrsModal.modal('hide');
						//markWorkingHelper(data.hoursData)
						setStaffHours(data.hoursData)
					}
				}
			});
		});*/

		/* Event handler for 'Add new time button' in working hours modal */
		$('body').on('click', '.btn-add-new-time', function(e){
			e.preventDefault();
			formRowsCreated++;

			var self = $(this),
			parCont = self.closest('div'),
			appendDiv = workingHrsModal.find('.time-clone-row').eq(0),
			timeRow = appendDiv.clone(),
			day = timeRow.find('input[type="checkbox"]').attr('name');
			timeRow.removeClass('hidden');
			timeRow.addClass('new-row');
			timeRow.find('.start_time').attr('name','new_start'+formRowsCreated);
			timeRow.find('.end_time').attr('name','new_end'+formRowsCreated);
			timeRow.find('.btn-add-new-time').remove();
			timeRow.find('.row').append('<div class="col-xs-3"><button class="btn btn-xs btn-danger btn-add-new-time-cancel" type="button">Remove</button></div>');
			appendDiv.closest('.form-group').append(timeRow);
			workingHrsModal.find('.timepicker1').timepicker();
			/*var startTimeField = timeRow.find('input[name="'+day+'_start0"]');
			startTimeField.prop('name', day+'_start'+formRowsCreated);
			workingHoursTimePicker(startTimeField);

			var endTimeField = timeRow.find('input[name="'+day+'_end0"]');
			endTimeField.prop('name', day+'_end'+formRowsCreated);
			workingHoursTimePicker(endTimeField);
			
			addNewTimeHelper(timeRow, parCont);*/

		});
		$('body').on('click', '.btn-add-new-time-cancel', function(e){
			$(this).closest('.row').remove();
		});

		$('body').on('change', '.showHours', function(e){
			toggleHours($(this));
		});

		/* Start: Class marge */
		$('body').on('click', '#classModal .marge-cls', function(e){
			e.preventDefault();
			isClassMarge = true;
			enterMergeClassMode();
		})
		/* End: Class marge*/
		$('.showHours').trigger('change');
	});


	/* Function to populate hours in working hours modal based on staff */
	function populateHours(data, staffId){
		$('.showHours', workingHrsModal).each(function(index, elem){
			var parCont = $(elem).closest('.clearfix');

			parCont.find('.row:not(:first)').remove();
			
			var checkbox = parCont.find('input[type="checkbox"]'),
			day = checkbox.attr('name');

			if(checkbox.is(':checked')){
				checkbox.prop('checked', false);
				toggleHours($(elem));
			}
		});

		$('input[name="staffId"]', workingHrsModal).val(staffId);
		var daysCovered = [];
		$.each(data, function(key, value){
			workingHrsModal.find('#'+value.hr_day+'-field').removeClass('hidden');
			var day = (value.hr_day).toLowerCase(),
			checkbox = $('input[name="'+day+'"]', workingHrsModal),
			timeRow = checkbox.closest('.row'),
			startTime = dbTimeToTimeString(value.hr_start_time),
			endTime = dbTimeToTimeString(value.hr_end_time);

			if($.inArray(day, daysCovered) == -1){
				daysCovered.push(day);
				
				checkbox.prop('checked', true);
				toggleHours(checkbox);

				setFieldForTimepicker(timeRow.find('input[name="'+day+'_start0"]'), startTime)
				setFieldForTimepicker(timeRow.find('input[name="'+day+'_end0"]'), endTime)
			}
			else{
				formRowsCreated++;

				var parCont = checkbox.closest('.clearfix'),
				timeRow = timeRow.clone(),
				startTimeField = timeRow.find('input[name="'+day+'_start0"]'),
				endTimeField = timeRow.find('input[name="'+day+'_end0"]');

				setFieldForTimepicker(startTimeField, startTime)
				setFieldForTimepicker(endTimeField, endTime)
				startTimeField.prop('name', day+'_start'+formRowsCreated);
				endTimeField.prop('name', day+'_end'+formRowsCreated);
				
				addNewTimeHelper(timeRow, parCont);
			}
		});
	}
	/* Helper function for 'Add new time button' event handler */
	function addNewTimeHelper(timeRow, parCont){
		timeRow.addClass('remove-hidden');
		timeRow.find('.checkbox').remove();
		timeRow = timeRow.append('<div class="col-xs-3"><button class="btn btn-xs btn-danger btn-add-new-time-cancel" type="button">Remove</button></div>');
		parCont.append(timeRow);
	}
	/* Function for toggling hours in working hours modal */
	function toggleHours(self){
		var parCont = self.closest('.clearfix');
		var notWork = parCont.find('.col-xs-10')
		var showHoursElem = parCont.find('.showHoursElem')
		var timeRow = parCont.find('.row:not(:first)');
		if(self.is(':checked')){
			notWork.addClass('hidden');
			showHoursElem.removeClass('hidden');
			timeRow.removeClass('hidden');
		}
		else{
			timeRow.addClass('hidden');
			showHoursElem.addClass('hidden');
			notWork.removeClass('hidden');
		}
	}

	/* Function to get all working hour on change view */
	function getHoursDataAndMarkSloat(){
		var formData = {};
		if(getCalendarView() != 'month'){
			formData['editStartDate'] = dateStringToDbDate(moment());
			formData['editEndDate'] = getCalendEndDate();
		}
		formData['staffId'] = findStaffId();
		formData['areaId'] = findAreaId();

		if(formData['areaId'] == null){
			parent.reloadPageWithAppointmentTab();
			formData['areaId'] = 'all';
		}

		$.get(public_url+'dashboard/total/hours', formData, function(data){
			setStaffHours(data)

		},'json');

	}

	/* Function to mark working time slot in week and day view */
	function markWorkingSlot(element){
		var hoursData = $('input[name="workingHours"]').val().split('|');
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

		var eventEligibleSlot = element.find('.fc-slot-time.working').filter(function(){
			var $this = $(this),
			eventDate = getClickedDate($this, 'not month'),
			eventTime = $this.closest('tr').data('time');
			now = moment();

			if(resheduleAppointDateSelMode || moment(eventDate+' '+eventTime, 'YYYY-MM-DD HH:mm:ss').isSameOrAfter(now))
				return $this;
			else
				$this.removeClass('working')
		});
		eventEligibleSlot.click(function(){
			dateClicked($(this), getClickedDate($(this), 'not month'))
		});
	}
	/* Function to mark working day in month view */
	function markWorkingDay(element){
		var hoursData = $('input[name="workingHours"]').val().split('|'),
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

		/*var closedDates = $('input[name="closedDates"]').val();
		if(closedDates){
			closedDates = closedDates.split(',');
			var eventNotEligibleCell = element.find('.fc-day:not(.notWorking)').filter(function(){
				var $this = $(this),
					eventDate = $this.data('date');

				if($.inArray(eventDate, closedDates) !== -1)
					return $this;
			});
			eventNotEligibleCell.addClass('notWorking')
		}*/
		closedDay(element);

		var eventEligibleCell = element.find('.fc-day:not(.notWorking)').filter(function(){
			var $this = $(this),
			now = moment().format('YYYY-MM-DD');

			if(resheduleAppointDateSelMode || moment($this.data('date')).isSameOrAfter(now))
				return $this;
			else
				$this.addClass('notWorking')
		});
		eventEligibleCell.click(function(){
			dateClicked($(this), getClickedDate($(this), 'month'));
		});
	}
	/* Function to highlight free day */
	function markFreeDay($elem){
		$elem.addClass('notWorking');
	}

	function enableDateFrom(currView){
		var enableDateFromField = $('input[name="enableDateFrom"]'),
		enableDateToField = $('input[name="enableDateTo"]'),
		enableDateUpto = '';

		if(enableDateFromField.length && enableDateToField.length){
			var enableDateFrom = enableDateFromField.val();
			enableDateUpto = enableDateToField.val();

			if(enableDateFrom && enableDateFrom != null && !enableDateUpto){
				var enableDatePeriodField = $('input[name="enableDatePeriod"]');
				if(enableDatePeriodField.length)
					enableDateUpto = calcEnableDateUpto(enableDateFrom, enableDatePeriodField.val());
			}

			enableDisableDateHelper(currView, enableDateFrom, 'enable-from');
		}
		
		return enableDateUpto;
	}

	function calcEnableDateUpto(enableDateFrom, enableDatePeriod){
		if(typeof enableDateFrom == 'undefined')
			enableDateFrom = $('input[name="enableDateFrom"]').val();

		if(typeof enableDatePeriod == 'undefined')
			enableDatePeriod = $('input[name="enableDatePeriod"]').val();

		return moment(enableDateFrom).add(enableDatePeriod, 'day').format('YYYY-MM-DD');
	}

	function resetEnableDisableDateFields(){
		if(isSalesProcessEvent() && (resheduleAppointDateSelMode || changeEventDateDateSelMode)){
			$('input[name="enableDateFrom"]').val('')
			$('input[name="enableDatePeriod"]').val('')

			if(changeEventDateDateSelMode)
				markWorkingHelper();
		}
	}

	function setEnableDisableDateFields(){
		$('input[name="enableDateFrom"]').val(eventObj.find('input[name="consultationDate"]').val())
		$('input[name="enableDatePeriod"]').val('15')
	}

	function disableDateFrom(currView, enableDateUpto){
		if(typeof enableDateUpto == 'undefined' || !enableDateUpto){
			var enableDateUptoField = $('input[name="enableDateUpto"]');
			if(enableDateUptoField.length)
				enableDateUpto = enableDateUptoField.val();
			else
				enableDateUpto = '';
		}
		if(enableDateUpto)
			enableDisableDateHelper(currView, enableDateUpto, 'disable-from');
	}

	function enableDisableDateHelper(currView, dateCheckAgainst, kase){
		var viewKase = detectCalendView(currView);
		if(viewKase == 'month'){
			//Month view
			var selector = '.fc-day:not(.notWorking)';
		}
		else if(viewKase == 'week' || viewKase == 'day'){
			//Week || Day view
			var selector = '.fc-slot-time.working';
		}

		var eventNotEligibleCell = currView.find(selector).filter(function(){
			var $this = $(this);

			if(viewKase == 'month')
				var eventDate = $this.data('date');
			else if(viewKase == 'week' || viewKase == 'day')
				var eventDate = getClickedDate($this, 'not month');

			if(kase == 'enable-from' && moment(eventDate).isBefore(dateCheckAgainst))
				return $this;
			else if(kase == 'disable-from' && moment(eventDate).isSameOrAfter(dateCheckAgainst))
				return $this;
		});
		if(viewKase == 'month')
			eventNotEligibleCell.addClass('notWorking')
		else if(viewKase == 'week' || viewKase == 'day')
			eventNotEligibleCell.removeClass('working')
	}

	function closedDay(currView){
		var closedDates = $('input[name="closedDates"]').val();
		if(closedDates){
			var viewKase = detectCalendView(currView);
			if(viewKase == 'month'){
				//Month view
				var selector = '.fc-day:not(.notWorking)';
			}
			else if(viewKase == 'week' || viewKase == 'day'){
				//Week || Day view
				var selector = '.fc-slot-time.working';
			}

			closedDates = closedDates.split(',');
			var eventNotEligibleCell = currView.find(selector).filter(function(){
				var $this = $(this);

				if(viewKase == 'month')
					var eventDate = $this.data('date');
				else if(viewKase == 'week' || viewKase == 'day')
					var eventDate = getClickedDate($this, 'not month');

				if($.inArray(eventDate, closedDates) !== -1)
					return $this;
			});
			if(viewKase == 'month')
				eventNotEligibleCell.addClass('notWorking')
			else if(viewKase == 'week' || viewKase == 'day')
				eventNotEligibleCell.removeClass('working')
		}
	}

	function detectCalendView(currView){
		if(currView.hasClass('fc-month-view')){
			//Month view
			return 'month';
		}
		else if(currView.hasClass('fc-agendaWeek-view')){
			//Week view
			return 'week';
		}
		else if(currView.hasClass('fc-agendaDay-view')){
			//Day view
			return 'day';
		}
	}

	/* Function to display time in each main time slot of view */
	function slotsDispTime(element, view, tstamp){
		var newWeek = week.slice();
		if(calendarSettings.cs_first_day > -1){
			var removedDays = newWeek.splice(calendarSettings.cs_first_day);
			newWeek = removedDays.concat(newWeek);
		}

		var rows = $(element).find('.fc-slats tr');

		rows.each(function(index, elem){
			var $elem = $(elem),
			text = moment($elem.data("time"), ["HH:mm:ss"]).format("h:mma");

			if(view == 'week'){
				var html = '<div class="fc-slot-times">';
				$.each(newWeek, function(key, val){
					html += '<div class="fc-slot-time" data-day="'+val+'">'+text+'</div>';
				})
				html += '</div>';
				$elem.children(":last").html(html);
				//$elem.children(":last").html('<div class="fc-slot-times"><div class="fc-slot-time" data-day="Monday">'+text+'</div><div class="fc-slot-time" data-day="Tuesday">'+text+'</div><div class="fc-slot-time" data-day="Wednesday">'+text+'</div><div class="fc-slot-time" data-day="Thursday">'+text+'</div><div class="fc-slot-time" data-day="Friday">'+text+'</div><div class="fc-slot-time" data-day="Saturday">'+text+'</div><div class="fc-slot-time" data-day="Sunday">'+text+'</div></div>');
			}
			else if(view == 'day')
				$elem.children(":last").html('<div class="fc-slot-times"><div class="fc-slot-time" data-day="'+tstamp.format('dddd')+'">'+text+'</div>');
		})
	}

	/* Function to change column text of day view */
	function dayviewcolText(currView){
		if(!findStaffId())
			var selStaff = 'All Staff';
		else{
			if(isUserType(['Admin']))
				var selStaff = $('select.staff-filter-cal option:selected').text();
			else if(isUserType(['Staff']))
				var selStaff = loggedInUser.name;
		}

		coltextHelperFunc(currView.find('.fc-day-header'), selStaff)
	}
	/* Function to convert text into link for week view and highlight today date header */
	function weekviewcolLink(currView){
		var momentt = moment(),
		todayDate = momentt.format('YYYY')+'-'+momentt.format('MM')+'-'+momentt.format('DD'),
		header = currView.find('.fc-day-header');

		header.each(function(index, elem){
			var $elem = $(elem);
			if(todayDate == $elem.data('date')) {
				$elem.addClass('today');
			}
			coltextHelperFunc($elem, $elem.html())
		})
	}
	function coltextHelperFunc($elem, text){
		$elem.html('<a href="#" class="epic-tooltip" data-toggle="tooltip" rel="tooltip" title="'+text+'">'+text+'</a> <a href="#" class="editHrs epic-tooltip" data-toggle="tooltip" rel="tooltip" title="Edit working hours"><i class="fa fa-clock-o"></i></a>');
		toggleEditHrsLnk();
		$elem.find('.epic-tooltip').tooltipster();
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

	/* Function to populate events based on the staff */
	function getStaffEvents(staffId, ifInsertNeverEndingEventsRepeat, repeatId, isInsertClassService = true,isShowUnavailableStaffView= false, eventId = null,isReload = false,isInsertBusyTime=false,isShowUnavailableStaffViewService= false){
		var alerts = calendar.children('.alert');
		if(alerts.length)
			var alertSpace = (alerts.outerHeight(true))*alerts.length;
		else
			var alertSpace = 0;
		var scrollPos = $(window).scrollTop()-alertSpace;
		toggleWaitShield("show", 'showCalendEvents');
		calendar.fullCalendar('removeEvents');

		var formData = {};

		if(typeof staffId == 'undefined' || staffId == null || staffId == 0)
			staffId = findStaffId();

		
		if(typeof ifInsertNeverEndingEventsRepeat != 'undefined' && ifInsertNeverEndingEventsRepeat != null && ifInsertNeverEndingEventsRepeat){
			// if(!isRescheduleClientMode() && window.location.href.includes('client'))
			if(!rescheduleClientsCount()  && window.location.href.includes('client')){
				var tabId =parent.$('ul#myTab4 li.active a').attr('id');
                if(tabId == 'panel_overview_tab'){
					formData['insertRepeatUpto'] = getCalendEndDatePriorDate();
				}else{
				formData['insertRepeatUpto'] = moment().format("YYYY-MM-") + moment().daysInMonth();
				}
			}
			else
				formData['insertRepeatUpto'] = getCalendEndDatePriorDate();
			
			if(typeof repeatId != 'undefined' && repeatId != null && repeatId != 0)
				formData['repeatId'] = repeatId;
		}

		if(!deleteServiceMode && !changeEventDateDateSelMode && !resheduleAppointDateSelMode && !rescheduleClientsCount())
			formData['clearSession'] = 'true';
		else
			formData['clearSession'] = 'false';

		if(changeEventDateDateSelMode)
			formData['delService'] = 'false';
		else
			formData['delService'] = 'true';

		formData['eventType'] = ['class'];
		formData['staffId'] = staffId;
		formData['isInsertClassService'] = isInsertClassService;
		formData['areaId'] = findAreaId();
		
		if(!rescheduleClientsCount() && window.location.href.includes('client')){
			var tabId =parent.$('ul#myTab4 li.active a').attr('id');
                if(tabId == 'panel_overview_tab'){
					formData['getEventsFrom'] = getCalendStartDate();
				}else{
			     formData['getEventsFrom'] = moment().format("YYYY-MM-01");
				}
		}
		else
			formData['getEventsFrom'] = getCalendStartDate();

		if(!rescheduleClientsCount() && window.location.href.includes('client')){
			var tabId =parent.$('ul#myTab4 li.active a').attr('id');
                if(tabId == 'panel_overview_tab'){
					formData['getEventsUpto'] = getCalendEndDatePriorDate();
				}else{
					formData['getEventsUpto'] = moment().format("YYYY-MM-") + moment().daysInMonth();
				}
			
		}
		else
			formData['getEventsUpto'] = getCalendEndDatePriorDate();

		if(staffId == 'all-ros'){
			formData['startDate'] = getCalendStartDate();
			if(!ifDayView())
				formData['endDate'] = getCalendEndDate();
		}

		if(isRemoveTeamMode()){
			if(removeBookType() == 'submitBookingindiv')
				formData['eventType'] = ['single-service'];
			
			formData['removeStepType'] = removeStepType();
			formData['clientId'] = defaultClientDetails.id;
		}
		else if(isCreateMakeUpMode())
			formData['eventStatus'] = 'active';
		else if(salesProcessType() == 'class'){
			formData['getEventsFrom'] = $('input[name="enableDateFrom"]').val();
			let enableDateUpto = $('input[name="enableDateTo"]').val();
			
			if(!enableDateUpto)
				enableDateUpto = calcEnableDateUpto(formData['getEventsFrom']);

			formData['getEventsUpto'] = enableDateUpto;
		}
		else if(!rescheduleClientsCount())	
			formData['eventType'].push('single-service', 'busy-time');

		/*if(!rescheduleClientsCount() && !isCreateMakeUpMode() && salesProcessType() != 'class'){
			/*$.get(public_url+'staffevents/appointments', formData, function(data){
				var events = [];
				if(data.length){
					$.each(data, function(val, text){
						var eventStart = moment(this.date+' '+this.startTime, '"YYYY-MM-DD H:mm A').format("YYYY-MM-DD HH:mm:ss"),
							eventEnd = moment(eventStart).add(this.duration, 'm').format("YYYY-MM-DD HH:mm:ss");

						var pushedIdx = events.push({type:'appointment', id:this.id, title:this.title, start:eventStart, end:eventEnd, serviceName:this.serviceName, price:this.price, date:this.date, appointStatusOpt:this.appointStatusOpt, appointStatusConfirm:this.appointStatusConfirm, autoExpireAppointDur:this.autoExpireAppointDur, autoExpireDatetime:this.autoExpireDatetime, clientId:this.clientId, clientEmail:this.clientEmail, clientNumb:this.clientNumb, isClientDeleted:this.isClientDeleted, startTime:this.startTime, duration:this.duration, notes:this.appointNote, staffId:this.staffId, staffName:this.staffName, isStaffDeleted:this.isStaffDeleted, areaId:this.areaId, isAreaDeleted:this.isAreaDeleted, locAreaName:this.locAreaName, userId:this.userId, serviceId:this.serviceId, color:this.serviceColor, eventRepeat:this.eventRepeat, eventRepeatInterval:this.eventRepeatInterval, eventRepeatEnd:this.eventRepeatEnd, eventRepeatEndAfterOccur:this.eventRepeatEndAfterOccur, eventRepeatEndOnDate:this.eventRepeatEndOnDate, isRepeating:this.isRepeating, eventRepeatWeekDays:[], histories:[], isServiceDeleted:this.isServiceDeleted, ifAreaHasStaffs:this.ifAreaHasStaffs}) - 1; //, userName:this.userName

						if(this.eventRepeatWeekDays)
							events[pushedIdx].eventRepeatWeekDays = JSON.parse(this.eventRepeatWeekDays);

						for(var i in this.histories)
							events[pushedIdx].histories.push(this.histories[i])	
					});
				}
				if(events.length && events[0] != '')
					calendar.fullCalendar('addEventSource', events);
			},'json');*
			formData['eventType'].push('single-service', 'busy-time');

			/*$.get(public_url+'staffevents/busy-time', formData, function(data){
				var events = [];
				if(data.length){
					$.each(data, function(val, text){
						/*var eventStart = moment(this.startDatetime),
							eventEnd = moment(this.endDatetime),
							title = (this.description?this.description:'Busy');

						events.push({type:'busy', id:this.id, title:title, start:eventStart, end:eventEnd, date:this.date, startTime:this.startTime, duration:this.duration, description:this.description, staffId:this.staffId, staffName:this.staffName, isStaffDeleted:this.isStaffDeleted, areaId:this.areaId, isAreaDeleted:this.isAreaDeleted, color:'#96A6A6', denyBooking:this.denyBooking, userId:this.userId, ifAreaHasStaffs:this.ifAreaHasStaffs});*

						var eventStart = moment(this.startDatetime),
							eventEnd = moment(this.endDatetime),
							title = (this.description?this.description:'Busy');

						events.push({type:'busy', id:this.id, title:title, start:eventStart, end:eventEnd, date:this.date, isStaffDeleted:this.isStaffDeleted, color:'#96A6A6'});
					});
				}
				if(events.length && events[0] != '')
					calendar.fullCalendar('addEventSource', events);
			},'json');*/

			/*$.get(public_url+'staffevents/single-service', formData, function(data){
				var events = [];
				if(data.length){
					$.each(data, function(val, text){
						/*var eventStart = moment(this.startDatetime),
							eventEnd = moment(this.endDatetime);

						var pushedIdx = events.push({type:'single-service', id:this.id, title:this.title, start:eventStart, end:eventEnd, price:this.price, date:this.date, startTime:this.startTime, duration:this.duration, notes:this.appointNote, staffId:this.staffId, staffName:this.staffName, userId:this.userId, color:this.serviceColor, eventRepeat:this.eventRepeat, eventRepeatInterval:this.eventRepeatInterval, eventRepeatEnd:this.eventRepeatEnd, eventRepeatEndAfterOccur:this.eventRepeatEndAfterOccur, eventRepeatEndOnDate:this.eventRepeatEndOnDate, isRepeating:this.isRepeating, isStaffDeleted:this.isStaffDeleted, serviceId:this.serviceId, serviceName:this.serviceName, isServiceDeleted:this.isServiceDeleted, appointStatusOpt:this.appointStatusOpt, autoExpireAppointDur:this.autoExpireAppointDur, autoExpireDatetime:this.autoExpireDatetime, salesProcessEvent:this.salesProcessEvent, consultationDate:this.consultationDate, eventRepeatWeekDays:[], clients:[], areas:[], histories:[]}) - 1;
						if(this.eventRepeatWeekDays)
							events[pushedIdx].eventRepeatWeekDays = JSON.parse(this.eventRepeatWeekDays);

						for(var i in this.clients)
							events[pushedIdx].clients.push(this.clients[i])

						for(var i in this.areas)
							events[pushedIdx].areas.push(this.areas[i])			

						for(var i in this.histories)
							events[pushedIdx].histories.push(this.histories[i])*	
						var eventStart = moment(this.startDatetime),
							eventEnd = moment(this.endDatetime);

						var pushedIdx = events.push({type:'single-service', id:this.id, title:this.title, start:eventStart, end:eventEnd, price:this.price, date:this.date, isStaffDeleted:this.isStaffDeleted, serviceName:this.serviceName, appointStatusOpt:this.appointStatusOpt, notes:this.appointNote, color:this.serviceColor, isRepeating:this.isRepeating, clients:[]}) - 1;

						for(var i in this.clients)
							events[pushedIdx].clients.push(this.clients[i])
					});
				}
				if(events.length && events[0] != '')
					calendar.fullCalendar('addEventSource', events);
			},'json');*
		}
		else if(isCreateMakeUpMode())
			formData['eventStatus'] = 'active';*/
		if(isClassMarge){
			formData['getEventsFrom'] = moment().format('YYYY-MM-DD');
			formData['clearSession'] = 'false';
			formData['eventType'] = [];
			formData['eventType'] = ['class'];
			formData['action'] = 'merge';
			formData['filterEventId'] = classModal.find('input[name="eventId"]').val();
		}
		formData['isShowUnavailableStaffView'] = isShowUnavailableStaffView;
		formData['isShowUnavailableStaffViewService'] = isShowUnavailableStaffViewService;
		formData['eventId'] = eventId;
		formData['isInsertBusyTime'] = isInsertBusyTime;
		$.get(public_url+'staff-events', formData, function(data){
			var events = [];
			if(data.length && (!resheduleAppointDateSelMode || getBookingType() == 'class')){
				$.each(data, function(){
					var eventStart = moment(this.startDatetime),
					eventEnd = moment(this.endDatetime);

					if(this.type == 'busy-time'){
						var title = (this.description?this.description:'Busy');

						events.push({type:'busy', id:this.id, title:title, start:eventStart, end:eventEnd, date:this.date, isStaffDeleted:this.isStaffDeleted, color:'#96A6A6'});
					}
					else if(this.type == 'single-service'){
						var pushedIdx = events.push({type:'single-service', id:this.id, title:this.title, start:eventStart, end:eventEnd, price:this.price, date:this.date, isStaffDeleted:this.isStaffDeleted, serviceName:this.serviceName, appointStatusOpt:this.appointStatusOpt, notes:this.appointNote, color:this.serviceColor, isRepeating:this.isRepeating, isMakeUp:this.isMakeUp/*, isEpicCredit:this.isEpicCredit*/, epicCreditBal:formatEpicBal(this), isIndiv:this.isIndiv, clients:[], isWithInvoice:this.withInvoice}) - 1;

						for(var i in this.clients)
							events[pushedIdx].clients.push(this.clients[i])
					}
					else if(this.type == 'class'){
						var pushedIdx = events.push({type:'class', id:this.id, title:this.title, start:eventStart, end:eventEnd, price:this.price, date:this.date, isStaffDeleted:this.isStaffDeleted, color:this.color, capacity:this.capacity, isRepeating:this.isRepeating, notes:this.notes, clientsCount:this.clientsCount, clients:[]}) - 1;

						for(var i in this.clients)
							events[pushedIdx].clients.push(this.clients[i])
					}
				});
			}

			if(events.length && events[0] != ''){
				calendar.fullCalendar('addEventSource', events);

				/*if(serviceReschduled){
					serviceReschduled = false;
					$('#'+eventObj.find('input[name="type"]').val()+'-'+eventObj.find('input[name="eventId"]').val()).trigger('click')
				}*/
				if(!$.isEmptyObject(showBooking)){
					if(showBooking.type == 'service')
						showBooking.type = 'single-service'	

					$('#'+showBooking.type+'-'+showBooking.id).trigger('click')
					showBooking = {};
				}
			}
			
			toggleWaitShield("hide", 'showCalendEvents');

			if(calendarFirstTime /*|| typeof scroll != 'undefined'*/){
				calendarFirstTime = false;
				//scrollToToday();
				var monthCell = $('.fc-month-view .fc-today');
				if(monthCell.length){
					scrollToTop(monthCell);
				}
				else{
					var weekView = $('.fc-view.fc-agendaWeek-view');
					if(weekView.length)
						scrollToTimeslot(weekView);
					else{
						var dayView = $('.fc-view.fc-agendaDay-view');
						if(dayView.length)
							scrollToTimeslot(dayView); 
					}
				}
			}
			else{
				var alerts = calendar.children('.alert');
				if(alerts.length)
					var alertSpace = (alerts.outerHeight(true))*alerts.length;
				else
					var alertSpace = 0;
				$("body, html").animate({ 
					scrollTop:scrollPos+alertSpace
				}, 200);
				/*setTimeout(function(){ 
					$(window).scrollTop(scrollPos+alertSpace); 
				}, 100);*/
			}
			if(!rescheduleClientsCount() && !resheduleAppointDateSelMode  && window.location.href.includes('client') && isReload){
				parent.reloadPageWithAppointmentTab();
			}
		},'json');


		/*$.get(public_url+'staffevents/classes', formData, function(data){
			var events = [];
			if(data.length){
				$.each(data, function(val, text){
					/*var eventStart = moment(this.startDatetime),
						eventEnd = moment(this.endDatetime);

					var pushedIdx = events.push({type:'class', id:this.id, title:this.title, start:eventStart, end:eventEnd, price:this.price, date:this.date, startTime:this.startTime, duration:this.duration, notes:this.notes, staffId:this.staffId, staffName:this.staffName, userId:this.userId, color:this.color, classId:this.classId, capacity:this.capacity, eventRepeat:this.eventRepeat, eventRepeatInterval:this.eventRepeatInterval, eventRepeatEnd:this.eventRepeatEnd, eventRepeatEndAfterOccur:this.eventRepeatEndAfterOccur, eventRepeatEndOnDate:this.eventRepeatEndOnDate, isRepeating:this.isRepeating, isClassDeleted:this.isClassDeleted, isStaffDeleted:this.isStaffDeleted, eventRepeatWeekDays:[], clients:[], areas:[], histories:[]}) - 1; //, userName:this.userName, , locAreaName:this.locAreaNameifAreaHasStaffs:this.ifAreaHasStaffs, areaId:this.areaId, isAreaDeleted:this.isAreaDeleted

					if(this.eventRepeatWeekDays)
						events[pushedIdx].eventRepeatWeekDays = JSON.parse(this.eventRepeatWeekDays);

					for(var i in this.clients)
						events[pushedIdx].clients.push(this.clients[i])

					for(var i in this.areas)
						events[pushedIdx].areas.push(this.areas[i])			

					for(var i in this.histories)
						events[pushedIdx].histories.push(this.histories[i])	*
					var eventStart = moment(this.startDatetime),
						eventEnd = moment(this.endDatetime);

					var pushedIdx = events.push({type:'class', id:this.id, title:this.title, start:eventStart, end:eventEnd, price:this.price, date:this.date, isStaffDeleted:this.isStaffDeleted, color:this.color, capacity:this.capacity, isRepeating:this.isRepeating, notes:this.notes, clientsCount:this.clientsCount, clients:[]}) - 1;

					for(var i in this.clients)
						events[pushedIdx].clients.push(this.clients[i])
				});
			}
			if(events.length && events[0] != '')
				calendar.fullCalendar('addEventSource', events);
		},'json');*/
	}

	/* Function to fetch working days of staff */
	function getStaffHours(staffId){
		//toggleWaitShield('show','busyTime');
		var formData = {};

		if(staffId == null)
			staffId = findStaffId();

		formData['staffId'] = staffId;
		formData['areaId'] = findAreaId();
		/*formData['editStartDate'] = getCalendStartDate();
		formData['editEndDate'] = getCalendEndDatePriorDate();*/
		//formData['pageName']='calendar';


		if(staffId == 'all-ros'){
			formData['startDate'] = getCalendStartDate();
			if(ifDayView())
				formData['day'] = getCalendStartDay();
			else
				formData['endDate'] = getCalendEndDate();
		}
		else{
			if(getCalendarView() != 'month'){
				formData['editStartDate'] = dateStringToDbDate(moment());
				formData['editEndDate'] = getCalendEndDate();
			}
		}
		
		$.get(public_url+'staffs/hours', formData, function(data){
			/*if(staffId != 'all' && staffId != 'all-ros')
			populateHours(data, staffId);*/

			//markWorkingHelper(data)
			setStaffHours(data)
			//toggleWaitShield('hide','busyTime');
		},'json');
	}
	function setStaffHours(data){
		if(typeof data == 'undefined')
			data = JSON.parse($('input[name="workingHours"]').val())
		
		var convertedData = '';

		if(data.length){
			$.each(data, function(val, text){
				if(convertedData)
					convertedData += '|';
				convertedData += this.hr_start_time+','+this.hr_end_time+','+this.hr_day;
			});
		}

		$('input[name="workingHours"]').val(convertedData);

		markWorkingHelper();
	}
	function markWorkingHelper(/*data*/){
		var //convertedData = '',
		dayView = $('.fc-agendaDay-view'),
		weekView = $('.fc-agendaWeek-view'),
		monthView = $('.fc-month-view');

		/*if(data.length){
			$.each(data, function(val, text){
				if(convertedData)
					convertedData += '|';
				convertedData += this.hr_start_time+','+this.hr_end_time+','+this.hr_day;
			});
		}

		$('input[name="workingHours"]').val(convertedData);*/

		if(dayView.length)
			markWorkingSlot(dayView);
		else if(weekView.length)
			markWorkingSlot(weekView);
		else if(monthView.length)
			markWorkingDay(monthView);
	}

	/* Function to toggle edit hours link */
	function toggleEditHrsLnk(staffId){
		if(staffId == null)
			staffId = findStaffId();

		var editHrs = calendar.find(' a.editHrs');

		if(!staffId || staffId == 'all' || staffId == 'all-ros')
			editHrs.hide();
		else
			editHrs.show();
	}

	/* Show Appointemnt Modal */
	function showAppointmentModal(mode){
		eventModal = appointModal.prop('id');
		switchTabTo('appointDetails', appointModal);
		blockTimepickerChangeEvent = true;

		appointModal.find('input[name="serviceTime0"]').trigger('blur');

		if(changeEventDateDateSelMode){
			setEventDate(appointModal, 'add');
			setEventdayAsRepeatWeekDay(appointModal);

			getStaffsBasedOnServices();
			getAreasBasedOnServices();
		}
		else{
			var //modalLocAreaDd = appointModal.find('select[name="modalLocArea"]'),
			ifAutoExpireAppoint = appointModal.find('input[name="ifAutoExpireAppoint"]'),
			appointHist = appointModal.find('a[href="#appointHist"]'),
				appointStatusSelectable = appointModal.find('.appoint_status_selectable')/*,
				areaSetGroupDisp = modalLocAreaDd.closest('.set-group').find('.set-group-disp'),
				areaSetGroupDispSpan = areaSetGroupDisp.find('span')*/;

				clearForm(appointModal.find('form'));

				ifAutoExpireAppoint.trigger('change');

				appointModal.find('input[name="isNewClient"]').trigger('change');

			//appointModal.find('select[name="staff"]').data('new-instance', 'true').data('services-new-instance', 'true')

			if(mode == 'add'){
				eventObj = {};

				/*setModalLocAreaDdVal(modalLocAreaDd);
				areaSetGroupDispSpan.removeClass('text-danger');	
				toggleDeletedMarkOnField(modalLocAreaDd, 0)

				getAreaStaffs(modalLocAreaDd);*/

				setEventDate(appointModal, 'add');
				setFieldForTimepicker(appointModal.find('input[name="serviceTime0"]'), moment($('input[name="selectedDatetime"]').val()).format("h:mm A"))

				getServices();

				pastCurrentMode = false;

				appointHist.hide();
				
				toggleAppointStatusFields(appointStatusSelectable, 'Confirmed');

				if(!$.isEmptyObject(defaultClientDetails)){
					var clientField = appointModal.find('.clientList');
					clientField.val(defaultClientDetails.name);
					populateClientDetails(defaultClientDetails, clientField)
				}

				resetEventReccur(appointModal);
			}
			else if(mode == 'edit'){
				/*if(eventObj.find('input[name="isAreaDeleted"]').val() == 'true' || eventObj.find('input[name="ifAreaHasStaffs"]').val() == 'false'){
					areaSetGroupDispSpan.addClass('text-danger');
					toggleDeletedMarkOnField(modalLocAreaDd, eventObj.find('input[name="areaId"]').val())
				}
				else{
					modalLocAreaDd.val(eventObj.find('input[name="areaId"]').val());
					areaSetGroupDispSpan.removeClass('text-danger');
					toggleDeletedMarkOnField(modalLocAreaDd, 0)	
				}
				getAreaStaffs(modalLocAreaDd, eventObj.find('input[name="staffId"]').val());*/

				
				if(bookNextAppointDateSelMode){
					bookNextAppointMode = true;
					setEventDate(appointModal, 'add');

					mode = 'add';
					setPastCurrentModeFlag($('input[name="selectedDatetime"]').val())

					appointHist.hide();
					resetEventReccur(appointModal);
				}
				else{
					setEventDate(appointModal, 'edit');

					var timeString = dbTimeToTimeString(eventServices[0].time);
					setPastCurrentModeFlag(eventObj.find('input[name="date"]').val()+' '+timeString);

					getServices(true);

					appointModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());

					appointModal.find('input[name="isRepeating"]').val(eventObj.find('input[name="isRepeating"]').val());

					populateEventReccur(appointModal)

					populateEventHistory(appointModal)
				}

				var eventAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val()
				toggleAppointStatusFields(appointStatusSelectable, eventAppointStatusOpt);
				if(eventAppointStatusOpt == 'Confirmed')
					appointModal.find('select[name="appointStatusConfirm"]').val(eventObj.find('input[name="appointStatusConfirm"]').val());
				else{
					var eventAutoExpireDatetime = eventObj.find('input[name="autoExpireDatetime"]').val(),
					eventAutoExpireAppointDur = eventObj.find('input[name="autoExpireAppointDur"]').val();

					if(eventAutoExpireDatetime != '0000-00-00 00:00:00' || eventAutoExpireAppointDur != ''){
						var autoExpireAppointDur = appointModal.find('select[name="autoExpireAppointDur"]'),
						autoExpireAppointDurDate = appointModal.find('input[name="autoExpireAppointDurDate"]'),
						autoExpireAppointDurTime = appointModal.find('input[name="autoExpireAppointDurTime"]');

						ifAutoExpireAppoint.prop('checked', true);
						ifAutoExpireAppoint.trigger('change');

						if(eventAutoExpireAppointDur == ''){
							autoExpireAppointDur.val('Custom');
							autoExpireAppointDurDate.val(moment(eventAutoExpireDatetime).format("D MMM YYYY"));
							autoExpireAppointDurTime.val(moment(eventAutoExpireDatetime).format("h:mm A"));
						}
						else
							autoExpireAppointDur.val(eventAutoExpireAppointDur);
						autoExpireAppointDur.trigger('change');
					}	
				}

				var clientField = appointModal.find('.clientList');
				clientField.val(eventObj.find('input[name="clientName"]').val());
				clientData = {};
				clientData['id'] = eventObj.find('input[name="clientId"]').val();
				clientData['phone'] = eventObj.find('input[name="clientNumb"]').val();
				clientData['email'] = eventObj.find('input[name="clientEmail"]').val();
				populateClientDetails(clientData, clientField);

				appointModal.find('textarea[name="appointNote"]').val(eventObj.find('input[name="notes"]').val());
			}
			setGroupInit();
			/*if(bookNextAppointDateSelMode || mode == 'edit'){
				if(eventObj.find('input[name="isAreaDeleted"]').val() == 'true')
					areaSetGroupDispSpan.text('This area has been deleted')
				else if(eventObj.find('input[name="ifAreaHasStaffs"]').val() == 'false')
					areaSetGroupDispSpan.text("This area doesn't have any staff")
			}*/
			
			appointModal.find('select').selectpicker('refresh');
			toggleEventModalUiByTimeMode(appointModal, mode);
		}
		blockTimepickerChangeEvent = false;
		appointModal.modal('show');
	}

	/* Show Class Modal */
	function showClassModal(mode){
		/* Start: code for Disabled delete button */
		$('#classModal').find('.delete-prompt').attr('disabled', true);
		/* End: code for Disabled delete button */

		/* start: code for enable done button */
		$('#classModal').find('.submitBooking').prop('disabled', false);
		/* End: code for enable done button' */

		eventModal = classModal.prop('id');

		var serviceClients =[];
		var clientData = {};
		var isWithInvoice = 0, 
		isWithEpicCredit = 0;

		// Check if eventObj is not empty
		if(eventObj != null && eventObj != undefined && eventObj != {} && !$.isEmptyObject(eventObj)) {
			isWithInvoice = eventObj.find('input[name="isInvoice"]').val();
			isWithEpicCredit = eventObj.find('input[name="isClientMakeup"]').val();
		}

		blockTimepickerChangeEvent = true;
		if(!rescheduleClientsCount() && !resheduleAppointDateSelMode){
			if(changeEventDateDateSelMode){
				setEventDate(classModal, 'add');
				setEventTime(classModal);
				setEventdayAsRepeatWeekDay(classModal);
				setRepeatEndDate(classModal)

				getStaffsAndAreas('', function(){
					saveFieldPrompt('date');
				})
				/*var bookingType = getBookingType();
				if(bookingType == 'class'){
					getStaffsAndAreas('class', function(){
						saveFieldPrompt('date');
					})
					
				}
				else if(bookingType == 'service'){
					getStaffsAndAreas('service')
				}*/
				//getStaffsAndAreas()
				//getResources();

				toggleAttendanceTab('hide');
			}
			else{
				var selectFirstClient = false;
				clearNotific(classModal.find('.tab-pane'));
				clientLinkOrder = 0;

				if(rescheduledClient){
					showNotific(rescheduledClient, classModal.find('.tab-pane:not("#classHist")'));
					switchTabTo('classClients', classModal);
					rescheduledClient = '';
				}
				else
					switchTabTo('classDetails', classModal);

				classClients = {};
				clearForm(classModal.find('form'));
				clearClientForm(classModal.find('.client-form'), false);
				//classModal.find('input[name="isNewClient"]').trigger('change');
				classModal.find('div#linkedclientList').html('');
				toggleAttendanceTab('hide')
				classModal.find('div#classAttendanceList').html('');
				classModal.find('div#waitingLinkedclientList').html('');
				classModal.find('div#waitingClassAttendanceList').html('');
				classModal.find('.waiting-clients-tab').empty();
                classModal.find('.waitingClient').empty();
				$('#resource-form .resource-clone-class:not(.hidden)').remove();

				//toggleTab(classModal, 'classReccur', 'show');
				//toggleTab(classModal, 'classClients', 'show');
				//setBookingUiMode('create')
				/*toggleTab(classModal, 'classReccur', 'hide');
				toggleTab(classModal, 'classClients', 'hide');
				toggleTab(classModal, 'classNotes', 'hide');
				toggleTab(classModal, 'classHist', 'hide');
				$('button#nextTab', classModal).hide();*/

				if(mode == 'add'){
					eventObj = {};

					setEventDate(classModal, 'add');
					setEventTime(classModal);
					classModal.find('.event-time-span').html('09:00 AM');
					pastCurrentMode = false;

					//classModal.find('a[href="#classHist"]').hide();

					resetEventReccur(classModal);

					//classModal.find('input[name="ifAutoExpireAppoint"]').trigger('change');
					toggleAutoExpireFields();
					//$("#appointStatusSelectable").selectable("enable");
					enableAppointStatuselectable();
					//toggleAppointStatusFields('Confirmed');
					toggleAppointStatusFields(calendarSettings.cs_initial_status);
					$("#bookTypeSelectable").selectable("enable");
					if($('input[name="ifBussHasServices"]').val() == 'true' && $('input[name="ifBussHasClasses"]').val() == 'false'){
						var bookingTypeAdd = 'service';
					}else if($('input[name="ifBussHasServices"]').val() == 'true' && $('input[name="ifBussHasClasses"]').val() == 'true' && (isSalesProcessMode() || isCreateServiceMakeUpMode())){
						var bookingTypeAdd = 'service';
					}else{
						var bookingTypeAdd = 'class';
					}
					if(!isSalesProcessMode() && !isCreateServiceMakeUpMode() && bookingTypeAdd == 'class'){
						/*getClasses(false, function(){
							getStaffsAndAreas();
							getResources();
						});*/
						getClasses(false, getStaffsAndAreas);
						toggleEventOverBookAlert('hide');
						setBookingType('class');
						if($('input[name="ifBussHasServices"]').val() == 'false'){
							disableBookTypeSelectable();
						}
						getServices();
					}
					else{
						/*getServices('', function(){
							getStaffsAndAreas();
							getResources();
						});*/
						getServices('', getStaffsAndAreas);
						setBookingType(bookingTypeAdd);
						disableBookTypeSelectable();

						//toggleTab(classModal, 'classReccur', 'hide');
						//toggleTab(classModal, 'classClients', 'hide');
					}

					if(!$.isEmptyObject(defaultClientDetails)){
						var data = {id:defaultClientDetails.id, name:defaultClientDetails.name, phone:defaultClientDetails.phone, email:defaultClientDetails.email, accStatus:defaultClientDetails.accStatus, isExisting:1, note:''};
						updateLinkedclientList(data);
						selectFirstClient = true;
					}
					updateBookedclientQuickInfo();
				}
				else if(mode == 'edit'){
					setEventDate(classModal, 'edit');
					var timeString = dbTimeToTimeString(eventObj.find('input[name="startTime"]').val());
					setFieldForTimepicker(classModal.find('input[name="eventTime"]'), timeString);
					classModal.find('.event-time-span').html(timeString);
					materialTimePicker($('.new-timepicker-btn'), timeString);

					setPastCurrentModeFlag(eventObj.find('input[name="date"]').val()+' '+timeString);

					classModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());
					classModal.find('input[name="isRepeating"]').val(eventObj.find('input[name="isRepeating"]').val());

					populateEventReccur(classModal)
					populateEventHistory(classModal)
					classModal.find('textarea[name="classNote"]').val(eventObj.find('input[name="notes"]').val());
					var bookType = eventObj.find("input[name='type']").val();
				

                     
					eventObj.find("input[name='client[]']").each(function(){
						// var clientData = JSON.parse(/*decodeURI*/($(this).val()));
						clientData = JSON.parse(/*decodeURI*/($(this).val()));
						clientData.isExisting = 1;
						if(bookType == 'single-service'){
							clientData.isEpicCredit =  parseInt(eventObj.find('input[name="isEpicCredit"]').val());
							clientData.isInvoice = parseInt(eventObj.find('input[name="isInvoice"]').val());
							clientData.ifRecur = parseInt(eventObj.find('input[name="isRepeating"]').val());
						 }
						serviceClients.push(clientData);
						updateLinkedclientList(clientData);
					});
					if(classModal.find('div#linkedclientList > a[data-deleted="1"]').length)
						showNotific(prepareAlert('warning', 'Marked clients have been deleted from the business.'), classModal.find('div#classClients'));

					var eventType = eventObj.find("input[name='type']").val();
					if(eventType == 'class'){
						setBookingType('class');

						/*getClasses(eventObj.find('input[name="classId"]').val(), function(){
							getStaffsAndAreas();
							getResources();
						});*/
						getClasses(eventObj.find('input[name="classId"]').val(), getStaffsAndAreas);
					}
					else if(eventType == 'single-service'){
						selectFirstClient = true;
						var ifAutoExpireAppoint = classModal.find('input[name="ifAutoExpireAppoint"]')
						//ifAutoExpireAppoint.trigger('change');
						toggleAutoExpireFields(ifAutoExpireAppoint);
						setBookingType('service');
						updateBookedclientQuickInfo();

						//$("#appointStatusSelectable").selectable("enable");

						/*getServices(eventObj.find('input[name="serviceId"]').val(), function(){
							getStaffsAndAreas();
							getResources();
						});*/
						getServices(eventObj.find('input[name="serviceId"]').val(), getStaffsAndAreas);

						enableAppointStatuselectable();
						var eventAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val();
						toggleAppointStatusFields(eventAppointStatusOpt);
						if(eventAppointStatusOpt == 'Pencilled-In'){
							var eventAutoExpireDatetime = eventObj.find('input[name="autoExpireDatetime"]').val(),
							eventAutoExpireAppointDur = eventObj.find('input[name="autoExpireAppointDur"]').val();

							if((eventAutoExpireDatetime != 'null' && eventAutoExpireDatetime != null) || eventAutoExpireAppointDur){
								var autoExpireAppointDur = classModal.find('select[name="autoExpireAppointDur"]'),
								autoExpireAppointDurDate = classModal.find('input[name="autoExpireAppointDurDate"]'),
								autoExpireAppointDurTime = classModal.find('input[name="autoExpireAppointDurTime"]');

								ifAutoExpireAppoint.prop('checked', true);
								//ifAutoExpireAppoint.trigger('change');
								toggleAutoExpireFields(ifAutoExpireAppoint);

								if(!eventAutoExpireAppointDur){
									autoExpireAppointDur.val('Custom');
									autoExpireAppointDurDate.val(moment(eventAutoExpireDatetime).format("D MMM YYYY"));
									autoExpireAppointDurTime.val(moment(eventAutoExpireDatetime).format("h:mm A"));
								}
								else
									autoExpireAppointDur.val(eventAutoExpireAppointDur);
								//autoExpireAppointDur.trigger('change');
								toggleManualExpireFields(autoExpireAppointDur)
							}	
						}

						//if(isSalesProcessEvent()){
							//disableAppointStatuselectable();
							//toggleTab(classModal, 'classReccur', 'hide');
							//toggleTab(classModal, 'classClients', 'hide');
						//}
					}
					disableBookTypeSelectable();

					if(classModal.find('ul.nav a[href="#classAttendance"]').css('display') != 'none'){
						setTimeout(function(){
							switchTabTo('classAttendance', classModal);
						}, 500);
					}

					//initDirty();
				}
				setGroupInit();
				classModal.find('select').selectpicker('refresh');

				if(selectFirstClient)
					selectLatestLinkedClient();
				else{
					var bookingType = getBookingType();
					if(bookingType == 'class')
						toggleClassClientLnks('create');
					else if(bookingType == 'service')
						toggleClassClientLnks('create', true);
				}

				/*if(isSalesProcessEvent())
				classModal.find('a#unlinkClientClass').hide();*/

				toggleEventModalUiByTimeMode(classModal, mode);
				toggleSubmitBtn();
			}
		}
		else {
			leaveAttendanceTab();
		}

		blockTimepickerChangeEvent = false;
		toggelMargeButton(mode);


		if(getBookingType() == 'service' && serviceClients != null && mode == 'edit' &&
			serviceClients != undefined && serviceClients != [] && serviceClients.length > 0)
			toggleTab(classModal, 'classReccur', 'show');

		// Hide recurrence tab if service is created with invoice/epic credit
		if(getBookingType() == 'service' && mode == 'edit' && (isWithInvoice == 1 || isWithEpicCredit == 1))
			toggleTab(classModal, 'classReccur', 'hide');

		/*Start : Disable change time and date for class without clients*/
		var timePickerElement = classModal.find('.event-date-timepicker');
		var timePickerClone =  classModal.find('.event-date-clone');
		
		if(mode == 'edit' && getBookingType() == 'class') {
			if(Object.keys(classClients).length == 0 ) {
				timePickerElement.removeClass('hidden');
				timePickerClone.addClass('hidden');
				classModal.find('.event-class-date-change').removeClass('hidden');
			} else {
				timePickerElement.addClass('hidden');
				timePickerClone.removeClass('hidden');
				classModal.find('.event-class-date-change').addClass('hidden');
			}
		} else {
			timePickerElement.removeClass('hidden');
			timePickerClone.addClass('hidden');
			classModal.find('.event-class-date-change').removeClass('hidden');
		}
		/*End : Disable change time and date for class without clients*/
		classModal.modal('show');
		/* Start: code for Enable delete button */
		//var totalClients=$('#classModal').find('div#linkedclientList').children().length;
		//disabledEnabledCancleClsBtn();
		/* End: code for Enable delete button */
		//dirtyForm.init(classModal.find('#classDetails'))
	}

	/* Show Busy Modal */
	function showBusyModal(mode){
		eventModal = busyModal.prop('id');
		// var timeString = dbTimeToTimeString(eventObj.find('input[name="startTime"]').val());
		// setPastCurrentModeFlag(eventObj.find('input[name="date"]').val()+' '+timeString);
		if(changeEventDateDateSelMode){
			setEventDate(busyModal, 'add');
			setEventTime(busyModal);
		}
		else{
			var modalLocAreaDd = busyModal.find('select[name="modalLocArea"]'),
			areaSetGroupDisp = modalLocAreaDd.closest('.set-group').find('.set-group-disp'),
			areaSetGroupDispSpan = areaSetGroupDisp.find('span');

			clearForm(busyModal.find('form'));

			busyModal.find('select[name="staff"]').data('new-instance', 'true')
			if(mode == 'add'){
				eventObj = {};
				busyModal.find('input[name="busyDur"]').val(0);
				setModalLocAreaDdVal(modalLocAreaDd);
				areaSetGroupDispSpan.removeClass('text-danger');	
				toggleDeletedMarkOnField(modalLocAreaDd, 0)	

				// getAreaStaffs(modalLocAreaDd);
				$.get(public_url + 'staffs/all', function(data) {
					var data = JSON.parse(data),
					options = "";
						$.each(data, function(key, value) {
							options += '<option value="' + value.id + '">' + value.name+ '</option>';
						});
						busyModal.find('select[name="staff"]').empty().html(options);
						busyModal.find('select[name="staff"]').selectpicker('refresh');

				});

				setEventDate(busyModal, 'add');
				setEventTime(busyModal);

				pastCurrentMode = false;
				busyModal.find('.staffDiv').show();	
				var tab = busyModal.find('ul.nav a[href="#busyReccurs"]');
				statusTab = busyModal.find('ul.nav a[href="#busyAttendance"]');
				busyTab = busyModal.find('ul.nav a[href="#busytime"]');
				switchTabTo('busytime', busyModal);
				tab.hide();
				busyTab.show();
				statusTab.hide();
				busyModal.find('.availableStaff').hide();
				busyModal.find('.selected_staff_list').closest('.form-group').hide();
			
			}
			else if(mode == 'edit'){
				busyModal.find('.staffDiv').hide();
				busyModal.find('.availableStaff').hide();				
				var tab = busyModal.find('ul.nav a[href="#busyReccurs"]');
				statusTab = busyModal.find('ul.nav a[href="#busyAttendance"]');
				isRepeating = eventObj.find('input[name="isRepeating"]').val();
				busyModal.find('input[name="isRepeating"]').val(isRepeating);
				busyStatus = eventObj.find('input[name="appointStatusOpt"]').val();
				setEventdayAsRepeatWeekDay(busyModal);
				var staffHtml = "";
				eventObj.find('input[name="staff[]"]').each(function(){
					var data= JSON.parse($(this).val());
					staffHtml += `<div class="staffList" data-staff-id="${data.id}">${data.staffName}</div>`;
				
				});
				busyModal.find('.selected_staff_list').empty().append(staffHtml).closest('.form-group').show();
				$('#busyAttendanceList').find('input[type="radio"]').each(function(){
					busyStatusVal = $(this).val();
					$(this).parent('label').removeClass('active');
                   if(busyStatus == busyStatusVal){
					   $(this).parent('label').addClass('active');
					   $(this).prop('checked',true);
				   }
				});
				tab.show();
				statusTab.show();

				busyModal.find('input[name="busyDur"]').val(eventObj.find('input[name="duration"]').val());
				if(eventObj.find('input[name="isAreaDeleted"]').val() == 'true' || eventObj.find('input[name="ifAreaHasStaffs"]').val() == 'false'){
					areaSetGroupDispSpan.addClass('text-danger');
					toggleDeletedMarkOnField(modalLocAreaDd, eventObj.find('input[name="areaId"]').val())
				}
				else{
					modalLocAreaDd.val(eventObj.find('input[name="areaId"]').val());
					areaSetGroupDispSpan.removeClass('text-danger');
					toggleDeletedMarkOnField(modalLocAreaDd, 0);
					modalLocAreaDd.selectpicker('refresh').trigger('change');
				}
				getAreaStaffs(modalLocAreaDd, eventObj.find('input[name="staffId"]').val());

				setEventDate(busyModal, 'edit');
				var timeString = dbTimeToTimeString(eventObj.find('input[name="startTime"]').val());
				setFieldForTimepicker(busyModal.find('input[name="eventTime"]'), timeString)

				setPastCurrentModeFlag(eventObj.find('input[name="date"]').val()+' '+timeString);

				busyModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());
				busyModal.find('textarea[name="busyDesc"]').val(eventObj.find('input[name="description"]').val());

				var denyBooking = eventObj.find('input[name="denyBooking"]').val();
				if(denyBooking == 1)
					busyModal.find('input[name="busyDenyBook"]').prop('checked', true);
				
				populateEventReccur(busyModal)
			}
			setGroupInit();
			if(mode == 'edit'){
				if(eventObj.find('input[name="isAreaDeleted"]').val() == 'true')
					areaSetGroupDispSpan.text('This area has been deleted')
				else if(eventObj.find('input[name="ifAreaHasStaffs"]').val() == 'false')
					areaSetGroupDispSpan.text("This area doesn't have staff")
			}
			busyModal.find('select').selectpicker('refresh');
			toggleEventModalUiByTimeMode(busyModal, mode);
		}
		busyModal.modal('show');
	}

	function dateClicked(elem, date){
		if(rescheduleClientsCount() || isCreateMakeUpMode() || salesProcessType(true) == 'class' || isClassMarge)
			return false;
		
		if(isVisible){
			$('.highlightCell').each(function() {
				$(this).removeClass('highlightCell');
			});
			$('.popover').each(function() {
				$(this).popover('hide');
			});
		}

		elem.popover('show');

		var td=elem.closest('td'), popElem = $('#'+td.attr('aria-describedby')), arr=popElem.find('.arrow');
		arr.css('top', '50%');
		popElem.css('top', td.offset().top-220);

		/*var currentTop = parseFloat(popElem.css('top')),
			dtHeight = elem.closest('td').height(),
			diff = (elem.closest('tr').offset().top * 25) / 100,
			fromTop = (elem.closest('tr').offset().top - diff);

	    if(dtHeight > 350){
	    	popElem.css({top: fromTop + 'px',});
	    }*/
	    
	    isVisible = true;
	    elem.closest('td').addClass('highlightCell');
		var changeSessionTime = $('input[name="changeSessionTime"]').val();
		if(changeSessionTime != '' && changeSessionTime != undefined){
			time = moment(changeSessionTime, 'HH:mm a').format('hh:mma');
			var datetime = date+' '+time;
		}else{
			var time = elem.closest('tr').data('time');

			if(time == null || time == '')
				var datetime = date+' 09:00am';
			else
				var datetime = date+' '+time;
		}
		$('input[name="selectedDatetime"]').val(datetime);
	    elem.addClass('highlightCell')
		calPopupHelper.removeClass('hidden')
	    if(addEventDateSelMode || bookNextAppointDateSelMode || changeEventDateDateSelMode){
	    	calPopupHelper.trigger("click");
	    	setTimeout(function(){ 
	    		if(addEventDateSelMode || changeEventDateDateSelMode){
	    			if(eventModal == 'appointModal')
	    				showAppointmentModal('add');
	    			else if(eventModal == 'classModal')
	    				showClassModal('add');
	    			else
	    				showBusyModal('add');
	    		}
	    		else if(bookNextAppointDateSelMode)
	    			showAppointmentModal('edit');

	    		resetAllModes();
	    	}, 500);
	    }
	    else if(resheduleAppointDateSelMode){
			/*if(isSalesProcessEvent()){
				$('input[name="enableDateFrom"]').val('')
				$('input[name="enableDatePeriod"]').val('')
			}*/
			//resetAllModes();
			calPopupHelper.trigger("click");

			var isRepeating = eventObj.find('input[name="isRepeating"]').val();

			if(isRepeating == 1){
				recurrAppointReschecModal.find('input[name="date"]').val(date);
				recurrAppointReschecModal.find('input[name="time"]').val(time);
				recurrAppointReschecModal.modal('show');
			}
			else
				rescheduleAppointment(date, time)
		}
		else if(isSalesProcessMode() || isCreateServiceMakeUpMode()){
			calPopupHelper.trigger("click");
			setTimeout(function(){ 
				//showAppointmentModal('add');
				showClassModal('add');
			}, 500);
		}
	}

	/* Function to close 'select a date' alert notification */
	function resetAllModes(e){
		if(e != null && e != '')
			e.preventDefault();
		closeSelDateNotif();
		addEventDateSelMode = false;
		resheduleAppointDateSelMode = false;
		bookNextAppointDateSelMode = false;
		changeEventDateDateSelMode = false;
		rescheduleClients = {};
		$('input[name="changeSessionTime"]').val('');
	}

	function closeSelDateNotif(){
		dateSelMsg.addClass('hidden');
	}

	/* Function to open 'select a date' alert notification */
	function openSelDateNotif(msg, e){
		if(e != null && e != '')
			e.preventDefault();
		dateSelMsg.removeClass('hidden');
		dateSelMsg.find('span').text(msg);
		calPopupHelper.trigger("click");
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

	function changeAppointStatus(status, ifChangeToConfirm){
		var formData = {};

		formData['eventId'] = eventObj.find('input[name="eventId"]').val();
		formData['appointStatusConfirm'] = status;
		formData['ifChangeToConfirm'] = ifChangeToConfirm;

		if(ifChangeToConfirm != null && ifChangeToConfirm)
			formData['historyText'] = 'Booking status changed from Pencilled-In to Confirmed|';
		else{
			if(status == 'Did not show')
				formData['historyText'] = 'Client did not show|';
			else
				formData['historyText'] = ' Booking marked as completed|';
		}

		$.post(public_url+'staffevents/appointments/change-status', formData, function(data){
			var data = JSON.parse(data);
			if(data.status == "updated"){
				getStaffEvents();
			}
			else if(data.status == "error"){
				$.each(data.errorData, function(key, val){
					$.each(this, function(errorType, message){
						if(errorType == "workingHourUnavail" )
							showNotific(message);
					});							
				});
			}
		});	
	}

	function submitCancelAppointmentModalSuccess(ifForceDelete, data){
		if(!ifForceDelete){
			showNotific(data.message);
			appointCancelModal.modal('hide');
		}
		getStaffEvents();
	}
	

	function rescheduleAppointment(date, time, targetEvents){
		var formData = {};
		formData['eventId'] = eventObj.find('input[name="eventId"]').val();
		formData['date'] = date;
		/*if(eventObj.find('input[name="appointStatusOpt"]').val() == 'Pencilled-In' && eventObj.find('input[name="autoExpireAppointDur"]').val() != '')
		formData['autoExpireDatetime'] = moment().add(eventObj.find('input[name="autoExpireAppointDur"]').val(), 'hours').format("YYYY-MM-DD H:mm:ss")*/

		var oldAppointdate = eventObj.find('input[name="date"]').val(),
		oldAppointTime = eventObj.find('input[name="startTime"]').val();
		if(time == null || time == '')
			formData['time'] = oldAppointTime;
		else
			formData['time'] = time;

		if(oldAppointdate != formData['date'] || oldAppointTime != formData['time'])
			formData['historyText'] = oldAppointdate+' '+oldAppointTime;
			// formData['historyText'] = 'Reschedule from '+dbDateToDateString(oldAppointdate)+' '+dbTimeToTimeString(oldAppointTime)+' to '+dbDateToDateString(formData['date'])+' '+dbTimeToTimeString(formData['time'])+'|';

			$.each($('#resource-form').find(':input').serializeArray(), function(i, obj){
				formData[obj.name] = obj.value
			});

			if(targetEvents != null){
				formData['targetEvents'] = targetEvents;
				if(targetEvents == 'future'){
					formData['eventRepeat'] = eventObj.find('input[name="eventRepeat"]').val();
					formData['eventRepeatInterval'] = eventObj.find('input[name="eventRepeatInterval"]').val()
					formData['eventRepeatEnd'] = eventObj.find('input[name="eventRepeatEnd"]').val()
					formData['eventRepeatEndAfterOccur'] = eventObj.find('input[name="eventRepeatEndAfterOccur"]').val()

					var prevVal = eventObj.find('input[name="eventRepeatEndOnDate"]').val();
					if(prevVal != 'null'){
						var dateMoment = moment(formData['date']);
						if(moment(prevVal).isBefore(dateMoment))
							formData['eventRepeatEndOnDate'] = formData['date'];
					}
					else
						formData['eventRepeatEndOnDate'] = prevVal;

					if(formData['eventRepeat'] == 'Weekly')
						formData['eventRepeatWeekdays'] = [moment(formData['date']).format("ddd")];

					if(formData['eventRepeatEnd'] == 'Never')
						formData['calendEndDate'] = getCalendEndDate();
				}
			}

           if(eventModal == "busyModal"){
           $.post(public_url+'staffevents/busy-time/reschedule',formData, function(data){
				var data = JSON.parse(data);
				if(data.status == "updated"){
					resetAllModes();
					getStaffEvents(0, true,0,true,false,data.eventId,true,true);
					
				}else if(data.status == "error"){
					$.each(data.errorData, function(key, val){
						$.each(this, function(errorType, message){
							if(errorType == "workingHourUnavail"){
								// showNotific(message);
								swal({
									type: "error",
									title:"Error",
									text: message,
									html:true,
									allowOutsideClick: true,
									showCancelButton: false,
									confirmButtonText: 'OK',
									confirmButtonColor: '#ff4401',
								});
							}
							
						});							
					});
				}

		   });
		}else{
		//$.post(public_url+'staffevents/appointments/reschedule', formData, function(data){
			$.post(public_url+'staffevents/single-service/reschedule', formData, function(data){
				var data = JSON.parse(data);
				if(data.status == "updated"){
					resetAllModes();
					if(getUrlParamFromUrl("resheduleAppointFromOverview")=='true' || getUrlParamFromUrl("resheduleAppointFromOverview")){
						resetAllModes();
						getStaffEvents(0, true,0,true,false,data.eventId,true);
					}
					else{
						showBooking.id = formData['eventId'];
						showBooking.type = eventObj.find('input[name="type"]').val();
						showNotific(data.message);
						areaDdCalend.selectpicker('val', areaDdCalendLastVal);
						getAreaStaffs(areaDdCalend, staffFilterCalendLastVal);
					}
				}
				else if(data.status == "error"){
					$.each(data.errorData, function(key, val){
						$.each(this, function(errorType, message){
							if(errorType == "workingHourUnavail"/* || errorType == "resourceUnavail" || errorType == "resourceDel"*/){
								// showNotific(message);
								swal({
									type: "error",
									title: message,
									html:true,
									allowOutsideClick: true,
									showCancelButton: false,
									confirmButtonText: 'OK',
									confirmButtonColor: '#ff4401',
								});
							}
							else if(errorType == "businessClosed"){
								// showNotific(prepareNotific('error', 'Business is closed on specified date'));
								swal({
									type: "error",
									title: "Business is closed on specified date",
									allowOutsideClick: true,
									showCancelButton: false,
									confirmButtonText: 'OK',
									confirmButtonColor: '#ff4401',
								});
							}
						});							
					});
				}
			});
			}
		}

		function prepareCalendBody(staffId){
			if(staffId == null)
				staffId = findStaffId();

			var monthBtn = calendar.find('.fc-month-button');

			if(staffId == 'all-ros'){
				if(calendar.fullCalendar('getView').type == 'month')
					calendar.fullCalendar('changeView', 'agendaDay');
				monthBtn.hide();
				ifAreaHasRostStaff();
			}
			else{
				calendar.find('.fc-view-container').show();
				noRostStaff.addClass('hidden');
				monthBtn.show();
				getStaffEvents(staffId, true, 0, false);
			//getStaffHours(staffId);
			if(!calendarFirstTime)
				getStaffHours(staffId);
			else{
				setStaffHours();
				//calendarFirstTime = false;
			}
		}
	}

	function ifAreaHasRostStaff(){
		var formData = {};
		formData['areaId'] = findAreaId();
		if(formData['areaId'] == null)
			formData['areaId'] = 'all';
		formData['startDate'] = getCalendStartDate();
		if(ifDayView())
			formData['day'] = getCalendStartDay();
		else
			formData['endDate'] = getCalendEndDate();	

		formData['insertRepeatUpto'] = getCalendEndDatePriorDate();

		$.get(public_url+'areas/has-rostered-staffs', formData, function(data){
			if(data){
				calendar.find('.fc-view-container').show();
				noRostStaff.addClass('hidden');
				getStaffEvents(0, true);
				getStaffHours();
			}
			else{
				calendar.find('.fc-view-container').hide();
				noRostStaff.removeClass('hidden');
				noRostStaff.find('span').text(calendar.find('h2').text());
			}
		},'json');
	}

	function ifDayView(){
		return (calendar.fullCalendar('getView').type == 'agendaDay')?true:false;
	}
	function getCalendarView(){
		return calendar.fullCalendar('getView').type;
	}

	function getCalendRawStartDate(){
		return calendar.fullCalendar('getView').start;
	}

	function getCalendStartDay(){
		return getCalendRawStartDate().format('dddd');
	}

	function getCalendStartDate(){
		return getCalendRawStartDate().format('YYYY-MM-DD');
	}

	function getCalendRawEndDate(){
		return calendar.fullCalendar('getView').end;
	}

	function getCalendEndDate(){
		return getCalendRawEndDate().format('YYYY-MM-DD');
	}

	function getCalendEndDatePriorDate(){
		var endDate = getCalendRawEndDate().clone();
		return endDate.subtract(1, 'days').format('YYYY-MM-DD');
	}

	function refreshCalendForRostStaff(){
		if(!staffFilterDdTriggered && staffFilterCalend.val() == 'all-ros')
			staffFilterCalend.trigger('change');
	}

	function staffFilterCal(selVal){
		staffFilterDdTriggered = true;

		var dayView = $('.fc-agendaDay-view');

		if(dayView.length)
			dayviewcolText(dayView);
		else
			toggleEditHrsLnk(selVal);

		prepareCalendBody(selVal);
		staffFilterDdTriggered = false;
	}

	function submitAppointmentModalSuccess(data){
		if(isSalesProcessMode()){
			if('changeStatus' in data)
				parent.realTimeUpdate('accStatus', data.changeStatus);	
			if('stepCompleted' in data){
				parent.closeContactNoteSubview();
				parent.fetchStepAndMarkComplete(data.stepCompleted);	
			}
			
			parent.closeSubview();
		}
		else{
			if(data.newClient && $.isEmptyObject(defaultClientDetails))
				fetchClientsForTypeahead('update');
			appointModal.modal('hide');
			getStaffEvents();
			showNotific(data.message);
		}
	}

	function enterReschecduleClientMode(calledFrom = ''){
		if(!isRescheduleClientMode()){
			reloadEvents = false;
			areaDdCalendLastVal = findAreaId();
			staffFilterCalendLastVal = findStaffId();
			classModal.modal('hide');

			var bookingType = getBookingType();
			if(bookingType == 'class'){
				openSelDateNotif('Choose a class to reschedule the client(s).');
				var staff = 'all';
				clientForm = classModal.find('.client-form');
				var isLdc = clientForm.find('input[name="isLdc"]');
				if(isLdc){
					var eventDate = moment(eventObj.find('input[name="date"]').val()).format('YYYY-MM-DD');
					var enableDateFrom = moment(eventDate).startOf('isoWeek').format('YYYY-MM-DD');
					var enableDateUpto = moment(eventDate).endOf('isoWeek').add(1,'days').format('YYYY-MM-DD');
					currView = $('.fc-view');
					enableDisableDateHelper(currView, enableDateFrom, 'enable-from');
					enableDisableDateHelper(currView, enableDateUpto, 'disable-from');
				}
				if(calledFrom == 'client-profile') {
					reloadEvents = false;
					classModal.modal('hide');
					rescheduleClientMode = true;
					openSubview('calendar', calendarSubviewConds()+'&resheduleAppointFromOverview=true&eventId='+serviceEventId);
				}
			}
			else if(bookingType == 'service'){
				resheduleAppointDateSelMode = true;
				openSelDateNotif('Choose a new time for this service.');
				var staff = eventObj.find('input[name="staffId"]').val();
				var isLdc = clientForm.find('input[name="isLdc"]');
				if(isLdc){
					var eventDate = moment(eventObj.find('input[name="date"]').val()).format('YYYY-MM-DD');
					var enableDateFrom = moment(eventDate).startOf('isoWeek').format('YYYY-MM-DD');
					var enableDateUpto = moment(eventDate).endOf('isoWeek').add(1,'days').format('YYYY-MM-DD');
					currView = $('.fc-view');
					enableDisableDateHelper(currView, enableDateFrom, 'enable-from');
					enableDisableDateHelper(currView, enableDateUpto, 'disable-from');
				}
				if(isSalesProcessEvent()){
					var stepNumb = parseInt(eventObj.find('input[name="salesProcessEvent"]').val(), 10);
					if(!isNaN(stepNumb)){
						if(stepNumb == 4 || stepNumb == 5 || $.inArray(stepNumb, sessionSteps()) > -1){
							setEnableDisableDateFields();
						}
					}
				}	

				if(calledFrom == 'client-profile') {
					getAreaStaffs(areaDdCalend, staff);
					openSubview('calendar', calendarSubviewConds()+'&resheduleAppointFromOverview=true&eventId='+serviceEventId);
				}
			}

			// scrollToToday();
		}
		areaDdCalend.selectpicker('val', 'all');

		if(!window.location.href.includes('client')){// Client Profile
			setTimeout(function(){ // Timeout
				$('html, body').animate({
					scrollTop: $('td.fc-today:first').offset().top 
				}, 1500);
			},2000);
		}
	}

	function enterMergeClassMode(){
		reloadEvents = false;
		areaDdCalendLastVal = findAreaId();
		staffFilterCalendLastVal = findStaffId();
		classModal.modal('hide');
		openSelDateNotif('Choose a class to merge.');

		areaDdCalend.selectpicker('val', 'all');
		getAreaStaffs(areaDdCalend, 'all');	
	}

	function submitClassModalSuccess(data){
		if(data.type == 'service' && isSalesProcessMode())
			afterBookingSalesProcess(data);
		else if(data.type == 'service' && isCreateServiceMakeUpMode())
			parent.reloadPageWithAppointmentTab();
		else{
			//fetchClientsForTypeahead('update');
			reloadEvents = false;
			classModal.modal('hide');
			if('type' in data){
				showBooking.id = data.eventId
				showBooking.type = data.type
				if(getBookingType() == 'class')
					isClassFresh = true;
			}

			getStaffEvents(0,true, data.repeatId);
			if(data.message != undefined){
				showNotific(data.message);
			}
			//$('#'+data.type+'-'+data.eventId).trigger('click')
			if('salesProcessError' in data && data.salesProcessError)
				showNotific(prepareNotific('warning', data.salesProcessError));
		}
	}

	function afterBookingSalesProcess(data){
		/*if('stepsBooked' in data)
			parent.setStepsBooked(data.stepsBooked);	
		if('changeStatus' in data)
			parent.realTimeUpdate('accStatus', data.changeStatus);	
		if('stepCompleted' in data){
			parent.closeContactNoteSubview();
			if('salesProcessDate' in data)
				var completedOn = data.salesProcessDate;
			else
				var completedOn = '';
			if('bookingDate' in data)
				var bookingDate = data.bookingDate;
			else
				var bookingDate = '';

			parent.fetchStepAndMarkComplete(data.stepCompleted, completedOn, '', bookingDate);	
		}
		
		parent.closeSubview();*/
		parent.location.reload(true);
	}

	function setModalLocAreaDdVal(modalLocAreaDd){
		var areaDdCalendVal = findAreaId();
		
		if(areaDdCalendVal == 'all')
			modalLocAreaDd.val(modalLocAreaDd.find('option:first').val());
		else	
			modalLocAreaDd.val(areaDdCalendVal);
	}

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

	function cancelEventSuccess(data){
		if(eventModal == 'classModal')
			classModal.modal('hide');
		else
			busyModal.modal('hide');

		getStaffEvents();
		showNotific(data.message);
	}

	function scrollToTimeslot(element){
		setTimeout(function(){
			if(calendarSettings.cs_start_time != null)
				var todaySlot = element.find('tr[data-time="'+calendarSettings.cs_start_time+'"]');
			else
				var todaySlot = element.find('.fc-now-indicator');
			if(todaySlot.length){
				if(!isSalesProcessMode() && isCreateServiceMakeUpMode())
					var headHeight = calendar.closest(".app-content").find('header').height();
				else
					var headHeight = 0;

				$('html, body').animate({
					scrollTop: (todaySlot.offset().top - headHeight)
				},0);
			}
		}, 100);
	}

	function rescheduleClass_client(targetEvents){
		toggleWaitShield("show", 'reschClient');
		var ifContinue = true;
		clientForm = classModal.find('.client-form');
		var isLdc = clientForm.find('input[name="isLdc"]').val();
		if(isLdc == '1'){
			var eventDate = moment(eventObj.find('input[name="date"]').val()).format('YYYY-MM-DD');
			var origEventDate = moment(classModal.find('.eventDateDisp').text()).format('YYYY-MM-DD');
			var dateFrom = moment(origEventDate).startOf('isoWeek').format('YYYY-MM-DD');
			var dateUpto = moment(origEventDate).endOf('isoWeek').format('YYYY-MM-DD');
			if(eventDate < dateFrom || eventDate > dateUpto){
				ifContinue = false;
			}
		}
		if(ifContinue){
			var formData = {};
			formData['eventId'] = eventObj.find('input[name="eventId"]').val();
			formData['classClients'] = rescheduleClients;
			formData['origEventId'] = classModal.find('input[name="eventId"]').val();
			if(typeof targetEvents != 'undefined')
				formData['targetEvents'] = targetEvents;
			else
				formData['targetEvents'] = '';		

			$.post(public_url+'staffevents/classes/reschedule-client', formData, function(data){
				var data = JSON.parse(data);
				if(data.status == "added"){
					/*var clientId = Object.keys(rescheduleClients)[0],
					tabs = classModal.find('.tab-pane:not("#classHist")');*/
					
					if(isRescheduleClientMode()){
						var clientId = Object.keys(rescheduleClients)[0]/*,
						tabs = classModal.find('.tab-pane:not("#classHist")')*/;

						//parent.showNotific(data.message, tabs);

						/*parent.showNotific(data.message, classModal.find('.tab-pane:not("#classHist")'));
						parent.unlinkClientClass(clientId);
						classModal.modal('show');
						parent.leaveAttendanceTab();
						parent.reloadEvents = true;
						resetAllModes();
						parent.closeSubview();*/
						parent.reloadPageWithAppointmentTab();
					}
					else{
						
						/*showNotific(data.message, tabs);
						unlinkClientClass(clientId);
						showClassModal('add');*/
						resetAllModes();
						rescheduledClient = data.message;
						//rescheduledClient = true;
						//showNotific(data.message, tabs);
						showBooking.id = formData['eventId'];
						showBooking.type = 'class';
						areaDdCalend.selectpicker('val', areaDdCalendLastVal);
						getAreaStaffs(areaDdCalend, staffFilterCalendLastVal);
					}
				}
				else if(data.status == "clientBusy" || data.status == "ldcRestrictionUnsatisfy"){
					showNotific(data.message);

					if(isRescheduleClientMode())
						subviewScrollToTop();

					swal({
						type: "warning",
						title: data.orgMessage,
						allowOutsideClick: true,
						showCancelButton: false,
						confirmButtonText: 'OK',
						confirmButtonColor: '#ff4401',
					}); 
				}
				toggleWaitShield("hide", 'reschClient');
			});
		}else{
			toggleWaitShield("hide", 'reschClient');
		}
	}

	function subviewScrollToTop(){
		parent.scrollToTop(parent.$('#iframe'));
	}

	function stickyFilter(){
		if(self==top){
			var scrolled = $(window).scrollTop(),
			scrollable = $('#page-title').height();
		}
		else{ //Subview mode

			var scrolled = $(window.parent).scrollTop(),
			scrollable = parent.$('#page-title').height();

			headerHeight = scrolled-scrollable - 57;
		}

		var filterBar = calendar.children('.fc-toolbar');
		var reschedulePopup = $('#recurrClassClientReschecModal');

		if(scrolled > scrollable) {//Stick filter
			filterBar.addClass('fixed').css({top:headerHeight});
			reschedulePopup.addClass('fixed').css({top:headerHeight});
		}
		else
			filterBar.removeClass('fixed')

		filterBar.width(calendar.width()-20)
	}

	function bookTeam(){
		var formData = {};
		formData['eventId'] = eventObj.find('input[name="eventId"]').val();
		formData['clientId'] = parent.$('#clientId').val();
		if(formData['clientId'] == '' || formData['clientId'] == undefined){
			formData['clientId']=idClient;
			console.log(formData['clientId']);
		}
		formData['salesProcessStepNumb'] = parent.salesProcessStepNumb;
		console.log(formData);
		$.post(public_url+'staffevents/classes/book-team', formData, function(data){
			var data = JSON.parse(data);
			console.log(data);
			if(data.status == "clientBusy" || data.status == "membershipUnsatisfy"){
				// showNotific(data.message);
				// subviewScrollToTop();
				swal({
					title: data.newMessage,
					allowOutsideClick: true,
					showCancelButton: false,
					confirmButtonText: 'Okay',
					confirmButtonColor: '#ff4401',
					html:true,
					cancelButtonText: "No"
				}, 
				function(isConfirm){
					swal.close();
				})
			}
			else
				afterBookingSalesProcess(data);
		});	
	}

	function removeAttend(clientId){
		swal({
			title: 'Are you sure to change attendance?',
			allowOutsideClick: true,
			showCancelButton: true,
			confirmButtonText: 'Yes',
			confirmButtonColor: '#ff4401',
			cancelButtonText: "No"
		}, 
		function(isConfirm){
			if(isConfirm){
				toggleWaitShield('show', 'changeAttend');

				var formData = {};
				formData['eventId'] = eventObj.find('input[name="eventId"]').val();
				formData['clientAttendance'] = 'Booked';
				formData['clientIfMakeUp'] = '';
				formData['makeupNotes'] = '';

				if(typeof clientId != 'undefined'){ //Class
					formData['clientId'] = [defaultClientDetails.id];
					var ajaxAction = 'classes/past-update';
				}
				else{ //Service
					var ajaxAction = 'single-service/change-attendance';
				}

				$.post(public_url+'staffevents/'+ajaxAction, formData, function(data){
					var data = JSON.parse(data);
					if(data.status == "added"){
						parent.location.reload(true);
					}
					toggleWaitShield('hide', 'changeAttend');
				});
			}
		});
	}

	/*function getBookingDetails(eventId, bookingType, callback){
		if(typeof bookingType == 'undefined' || !bookingType)
			var bookingType = getBookingType();

		if(bookingType == 'class')
			var ajaxAction = 'classes';
		else if(bookingType == 'service')
			var ajaxAction = 'single-service';

		$.get(public_url+'staffevents/'+ajaxAction+'/'+eventId, function(data){
			if(bookingType == 'class'){
				var eventHtml = '<input type="hidden" name="type" value="class"><input type="hidden" name="eventId" value="'+eventId+'"><input type="hidden" name="date" value="'+data.date+'"><input type="hidden" name="startTime" value="'+data.startTime+'"><input type="hidden" name="duration" value="'+data.duration+'"><input type="hidden" name="userId" value="'+data.userId+'"><input type="hidden" name="staffId" value="'+data.staffId+'"><input type="hidden" name="staffName" value="'+data.staffName+'"><input type="hidden" name="isStaffDeleted" value="'+data.isStaffDeleted+'"><input type="hidden" name="classId" value="'+data.classId+'"><input type="hidden" name="capacity" value="'+data.capacity+'"><input type="hidden" name="price" value="'+data.price+'"><input type="hidden" name="className" value="'+data.className+'"><input type="hidden" name="notes" value="'+data.notes+'"><input type="hidden" name="eventRepeat" value="'+data.eventRepeat+'"><input type="hidden" name="eventRepeatInterval" value="'+data.eventRepeatInterval+'"><input type="hidden" name="eventRepeatEnd" value="'+data.eventRepeatEnd+'"><input type="hidden" name="eventRepeatEndAfterOccur" value="'+data.eventRepeatEndAfterOccur+'"><input type="hidden" name="eventRepeatEndOnDate" value="'+data.eventRepeatEndOnDate+'"><input type="hidden" name="isRepeating" value="'+data.isRepeating+'"><input type="hidden" name="isClassDeleted" value="'+data.isClassDeleted+'">';
			}
			else if(bookingType == 'service'){
				var eventHtml = '<input type="hidden" name="type" value="single-service"><input type="hidden" name="eventId" value="'+eventId+'"><input type="hidden" name="date" value="'+data.date+'"><input type="hidden" name="startTime" value="'+data.startTime+'"><input type="hidden" name="duration" value="'+data.duration+'"><input type="hidden" name="userId" value="'+data.userId+'"><input type="hidden" name="staffId" value="'+data.staffId+'"><input type="hidden" name="staffName" value="'+data.staffName+'"><input type="hidden" name="isStaffDeleted" value="'+data.isStaffDeleted+'"><input type="hidden" name="price" value="'+data.price+'"><input type="hidden" name="notes" value="'+data.notes+'"><input type="hidden" name="eventRepeat" value="'+data.eventRepeat+'"><input type="hidden" name="eventRepeatInterval" value="'+data.eventRepeatInterval+'"><input type="hidden" name="eventRepeatEnd" value="'+data.eventRepeatEnd+'"><input type="hidden" name="eventRepeatEndAfterOccur" value="'+data.eventRepeatEndAfterOccur+'"><input type="hidden" name="eventRepeatEndOnDate" value="'+data.eventRepeatEndOnDate+'"><input type="hidden" name="isRepeating" value="'+data.isRepeating+'"><input type="hidden" name="appointStatusOpt" value="'+data.appointStatusOpt+'"><input type="hidden" name="autoExpireAppointDur" value="'+data.autoExpireAppointDur+'"><input type="hidden" name="autoExpireDatetime" value="'+data.autoExpireDatetime+'"><input type="hidden" name="serviceName" value="'+data.serviceName+'"><input type="hidden" name="serviceId" value="'+data.serviceId+'"><input type="hidden" name="isServiceDeleted" value="'+data.isServiceDeleted+'"><input type="hidden" name="salesProcessEvent" value="'+data.salesProcessEvent+'"><input type="hidden" name="consultationDate" value="'+data.consultationDate+'">';
			}

			if(data.eventRepeatWeekDays)
				var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
			else
				var eventRepeatWeekDays = [];
			$.each(eventRepeatWeekDays, function(key, val){
				eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\''+val+'\'>';						
			});
			
			for(var i in data.resources)
				eventHtml += '<input type=\'hidden\' name=\'resource[]\' value=\''+JSON.stringify(data.resources[i])+'\'>';

			for(var i in data.clients){
				//eventHtml += '<input type=\'hidden\' name=\'client[]\' value=\''+encodeURI(JSON.stringify(data.clients[i]))+'\'>';
				//eventHtml += '<input type="hidden" name="client[]" value="'+encodeURI(JSON.stringify(data.clients[i]))+'">';
				eventHtml += '<input type="hidden" name="client[]" value="'+/*(JSON.stringify(data.clients[i]).replace(/'/g, "&#39;")).replace(/"/g, "&#34;")*escQuotes(JSON.stringify(data.clients[i]))+'">';
			}
					
			for(var i in data.areas)
				//eventHtml += '<input type=\'hidden\' name=\'area[]\' value=\''+JSON.stringify(data.areas[i])+'\'>';
				eventHtml += '<input type="hidden" name="area[]" value="'+escQuotes(JSON.stringify(data.areas[i]))+'">';		

			for(var i in data.histories)
				//eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\''+JSON.stringify(data.histories[i])+'\'>';	
				eventHtml += '<input type="hidden" name="history[]" value="'+escQuotes(JSON.stringify(data.histories[i]))+'">';		


			if(typeof callback != 'undefined')
				callback(eventHtml);
		},'json');
	}*/


	/*function scrollToToday(){
		var monthCell = $('.fc-month-view .fc-today');
		if(monthCell.length){
			scrollToTop(monthCell);
		}
		else{
			var weekView = $('.fc-view.fc-agendaWeek-view');
			if(weekView.length)
				scrollToTimeslot(weekView);
			else{
				var dayView = $('.fc-view.fc-agendaDay-view');
				if(dayView.length)
					scrollToTimeslot(dayView); 
			}
		} 
	}*/
}

/* Start: populate new hour */
function populateNewHour(data, staff){
	var cloneRow = workingHrsModal.find('.time-clone-row').eq(0),
	appenDiv = cloneRow.closest('.form-group'),
	count = 0;

	workingHrsModal.find('input[name="staffId"]').val(staff);
	if(data.length > 0){
		$.each(data, function(i, value){
			row =  cloneRow.clone(),
			row.removeClass('hidden');
			row.addClass('new-row');
			if(count != 0){
				row.find('.btn-add-new-time').remove();
				row.find('.row').append('<div class="col-xs-3"><button class="btn btn-xs btn-danger btn-add-new-time-cancel" type="button">Remove</button></div>');
			}
			row.find('.start_time').attr('name','old_start'+count).val(dbTimeToTimeString(value.hr_start_time));
			row.find('.end_time').attr('name','old_end'+count).val(dbTimeToTimeString(value.hr_end_time));
			appenDiv.append(row);
			count++;
		})
	}
	else{
		row =  cloneRow.clone(),
		row.removeClass('hidden');
		row.addClass('new-row');
		if(count != 0){
			row.find('.btn-add-new-time').remove();
			row.find('.row').append('<div class="col-xs-3"><button class="btn btn-xs btn-danger btn-add-new-time-cancel" type="button">Remove</button></div>');
		}
		row.find('.start_time').attr('name','old_start0').val(dbTimeToTimeString('06:00:00'));
		row.find('.end_time').attr('name','old_end0').val(dbTimeToTimeString('19:00:00'));
		appenDiv.append(row);
		
		workingHrsModal.find('#mark_as_leave').prop("checked", true);
		workingHrsModal.find('#toggel-field-id').hide();
	}
	workingHrsModal.find('.timepicker1').timepicker();
} 
/* End populate new hour */


function getUrlParamFromUrl(oParam){
	var urlParams = decodeURIComponent(location.href),
	urlParamsArr = urlParams.split('&');

	for(var i=0; i<urlParamsArr.length; i++){
		var param = urlParamsArr[i].split('=');
		if(param[0] == oParam)
			return param[1] === undefined?true:param[1];
	}
}

function get_hostname(url) {
    var m = url.match(/^http:\/\/[^/]+/);
    return m ? m[0] : null;
}

$('input[name="busyRepeatEnd"]').change(function() {
	var $this = $(this),
	selVal = $this.val(),
	module = $this.closest('.event-reccur'),
	eventRepeatEndAfterOccurDd = module.find('select[name="busyRepeatEndAfterOccur"]'),
	eventRepeatEndOnDate = module.find('input[name="busyRepeatEndOnDate"]');
	if (selVal == 'After') {
		eventRepeatEndAfterOccurDd.prop({
			'disabled': false,
			'required': true
		})
		eventRepeatEndOnDate.prop('disabled', true);
		setFieldNeutral(eventRepeatEndOnDate)
	} else if (selVal == 'On') {
		eventRepeatEndAfterOccurDd.prop('disabled', true);
		setFieldNeutral(eventRepeatEndAfterOccurDd)
		eventRepeatEndOnDate.prop({
			'disabled': false,
			'required': true
		})
	} else {
		eventRepeatEndAfterOccurDd.prop('disabled', true);
		eventRepeatEndOnDate.prop('disabled', true);
		setFieldNeutral(eventRepeatEndAfterOccurDd)
		setFieldNeutral(eventRepeatEndOnDate)
	}
	eventRepeatEndAfterOccurDd.selectpicker('refresh')
});

$('.openDeleteBusyModal').click(function(){
	isRepeating = eventObj.find('input[name="isRepeating"]').val();
	if(isRepeating == 1){
		$('#delmodal').modal('hide');
		$('#busyModal').modal('hide');
		$('#openBusyDelmodal').modal('show');
		
	}else{
		$('#delmodal').modal('hide');
		$('#busyModal').modal('hide');
		$('.delete-event[data-target-event="this"]').click();
	}

})

$('.busyStatus').on('click',function(){
	var formData = {};
	formData['eventId'] = busyModal.find('input[name="eventId"]').val();
	formData['busyValue']= $(this).find('input[type="radio"]').val();
	ajaxAction = public_url+'staffevents/busy-time/status';
	  $.post(ajaxAction, formData, function(data){
		var data = JSON.parse(data);
		if(data.status == 'updated'){
			swal('Status Updated Successfully');
		}
	  });
})

$('#busyModal').on('hide.bs.modal', function(){
	var eventId = busyModal.find('input[name="eventId"]').val();
	switchTabTo('busytime', busyModal);
	 if(reloadEvents) {
		 
		  getStaffEvents(0, true,0,true,false,eventId);
			reloadEvents = false;
		}
	
});

$('.staffChange').on('click',function(){
    busyModal.find('.staffDiv').show();
});