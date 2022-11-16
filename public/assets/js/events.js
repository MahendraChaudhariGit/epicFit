	var appointModal = $('#appointModal'),
	appointCancelModal = $('#appointCancelModal'),
	calPopupHelper = $('#calPopupHelper'),
	totalWidth = 0,
	formRowsCreated = 0,
	clientLinkOrder = 0,
	totalClientWidth = 0,
	pastCurrentMode = false,
	cancelledBooking = false,
	reloadEvents = false,
	bookNextAppointMode = false,
	notAvailAjax = false,
	blockTimepickerChangeEvent = false,
	deleteServiceMode = false,
	isClassFresh = false,
	eventModal = null,
	saveAreaTimeout = false,
	isSalesProcessBookStep = false,
	eventObj = {},
	eventServices = [],
	shownPopover = [],
	defaultClientDetails = {},
	staffDdTempOpts = {},
	idClient ='',
	clientClassData = '';
	clientSwalOpt = {
		title: "What do you want to do with this client?",
		showCancelButton: true,
		allowOutsideClick: true,
		html: true,
		showConfirmButton: false,
		customClass: 'classClientUnlinkAlert'
	},
	staffChangeSwalOpt = {
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#ff4401",
		confirmButtonText: "Still change",
		cancelButtonText: "Revert Staff",
		allowEscapeKey: false,
		html: true,
		customClass: 'staff-change-alert'
	},
	deleteEventPopoverOpt = {
		html: true,
		content: "<a class='btn btn-default m-r-10 cancel-delete-event' href='#'><i class='fa fa-times'></i> No</a> <a class='btn btn-red delete-event' href='#'><i class='glyphicon glyphicon-trash'></i> Yes</a>",
		container: popoverContainer,
		title: function() {
			if (eventModal == 'classModal') return "<strong>Cancel this class?</strong>"
				else return "<strong>Delete this busy time?</strong>"
			},
		trigger: 'manual'
	},
	deleteReccurEventPopoverOpt = {
		html: true,
		content: "Would you like to cancel only this event,<br> or this and all following events in the<br> series?<a class='btn btn-default btn-block delete-event' href='#' data-target-event='future'>This and future</a> <a class='btn btn-default btn-block delete-event' href='#' data-target-event='this'>This only</a>",
		container: popoverContainer,
		title: function() {
			if (eventModal == 'classModal') {
				var bookingType = getBookingType();
				if (bookingType == 'class') return "<strong>Cancel this class?</strong>"
					else if (bookingType == 'service') return "<strong>Cancel this service?</strong>"
				} else return "<strong>Cancel recurring booking?</strong>"
		},
		trigger: 'manual'
	},
	editReccurEventPopoverOpt = {
		placement: 'left',
		html: true,
		content: "Would you like to change only this event,<br> or this and all following events in the<br> series?<a class='btn btn-default btn-block update-event' href='#' data-target-event='future'>This and future</a> <a class='btn btn-default btn-block update-event' href='#' data-target-event='this'>This only</a>",
		container: popoverContainer,
		title: function() {
			if (eventModal == 'classModal') {
				var bookingType = getBookingType();
				if (bookingType == 'class') return "<strong>Edit recurring class?</strong>"
					else if (bookingType == 'service') return "<strong>Edit recurring service?</strong>"
				} else return "<strong>Edit recurring booking?</strong>"
		},
		trigger: 'manual'
	};
	if (isRescheduleClientMode()) {
		var classModal = parent.classModal,
		classClients = parent.classClients,
		rescheduleClients = parent.rescheduleClients;
	} else {
		var classModal = $('#classModal'),
		classClients = {},
		rescheduleClients = {};
	}
	var resechedule = "";
	$(document).ready(function() {
		/* start: Initialize Section */
		/* start: Preparing default client details, if any */
		var defaultClient = $('input[name="defaultClient"]');
		if (defaultClient.length) defaultClientDetails = jQuery.parseJSON(defaultClient.val());
		/* end: Preparing default client details, if any */
		/* start: Initialize clients typeahead */
		if (!$.isEmptyObject(defaultClientDetails)) initTypeahead([defaultClientDetails])
			else {
				var clientsDetails = $('input[name="clientsDetails"]');
				if (clientsDetails.length) {
					var clientsDetails = JSON.parse($('input[name="clientsDetails"]').val());
				initTypeahead(clientsDetails) //, 'create'
			} else fetchClientsForTypeahead('create');
		}
		/* end: Initialize clients typeahead */
		/* Start: New time picker */
		$('.new-timepicker-btn').bootstrapMaterialDatePicker({
			date: false,
			shortTime: true,
			format: 'hh:mm A',
			currentDate: '09:00 AM'
		}).change(function(e, date) {
			var time = date.format("hh:mm A"),
			formGroup = $(e.target).closest('.eventTime');
			formGroup.find('.event-time-span').text(time);
			formGroup.find('input[name="eventTime"]').val(time);
			if (!blockTimepickerChangeEvent && formGroup.length > 0) {
				getStaffsAndAreas('', function() {
					saveFieldPrompt('time');
				});
			}
		});
		/* End: New time picker */
		/* Start: New date picker */
		$('.new-eventDatepicker').bootstrapMaterialDatePicker({
			time: false,
			shortTime: true,
			format: 'YYYY-MM-DD'
		}).change(function(e, date) {
			var date = date.format("ddd, D MMM, YYYY"),
			formGroup = $(e.target).closest('.form-group')
			formGroup.find('input[name="markFavTill"]').val(date);
			formGroup.find('.event-date-span').removeClass('hidden').text(date);
		});
		/* Start: New date picker */
		/* Start: Busy model date picker */

		// $('.busy-time-timepicker').change(function() {
		// 	getStaffsAndAreas('busy', function() {
		// 		saveFieldPrompt('time');
		// 	});
		// });

		$('.busy-timepicker-btn').bootstrapMaterialDatePicker({
			date: false,
			shortTime: true,
			format: 'hh:mm A',
			currentDate: '09:00 AM'
		}).change(function(e, date) {
			var time = date.format("hh:mm A");
			busyModal.find('input[name="eventTime"]').val(time);
			var isRepeat = busyModal.find('input[name="isRepeating"]').val();
			
			getAreas('busy', function() {
				// saveFieldPrompt('time');
				getAvailableStaffs();
				if(isModalModeEdit(busyModal)){
					var text = `${isRepeat == 1 ?("<a class='btn btn-primary m-r-10 saveBusyField' href='#' data-target-event='future' data-field='time'>This and future</a>"): ''}<a class='btn btn-primary saveBusyField' href='#' data-target-event='this' data-field='time'>This only</a>`
					var label = 'time';
					/*else if(label == 'autoExpire')
					label = 'auto expire';*/
					swal({
						title: 'Update ' + label + '?',
						allowOutsideClick: true,
						html: true,
						text: text,
						showConfirmButton: false,
						customClass: 'busyClientUnlinkAlert'
					});
				}
			});
				
			
		});
		/* End: Busy model date picker */
		$('.event-date-btn').bootstrapMaterialDatePicker({
			time: false,
			shortTime: true,
			format: 'D MMM YYYY'
		});
		/* start: Initialize datepicker */
		$('.eventDatepicker').datepicker({
			autoclose: true,
			dateFormat: "d M yy",
			minDate: 0,
			startDate: new Date()
		});
		/* end: Initialize datepicker */
		/* start: Initialize calPopupHelper */
		calPopupHelper.click(function() {
			if (shownPopover.length !== 0) {
				shownPopover[0].popover('destroy');
				shownPopover = [];
			}
			$(this).addClass('hidden').removeClass('superior');
		})
		appointCancelModal.add(appointModal).add(classModal).on('hide.bs.modal', function() {
			calPopupHelper.trigger("click");
		});
		/* end: Initialize calPopupHelper */
		/* start: Append randam week acording to calendar setting in class model*/
		var weekName = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
		if (calendarSettings != undefined) {
			var j = (calendarSettings.cs_first_day),
			html = '';
			busyHtml = '';
			for (var i = 0; i < 7; i++) {
				if (j == 7) j = 0;
				html += '<div class="checkbox clip-check check-primary checkbox-inline m-b-0"><input id="classEventRepeatWeekdays' + j + '" value="' + weekName[j] + '" type="checkbox"><label for="classEventRepeatWeekdays' + j + '"> ' + weekName[j] + ' </label></div>';
				busyHtml += '<div class="checkbox clip-check check-primary checkbox-inline m-b-0"><input id="busyEventRepeatWeekdays' + j + '" value="' + weekName[j] + '" type="checkbox"><label for="busyEventRepeatWeekdays' + j + '"> ' + weekName[j] + ' </label></div>';
				j++;
				
			}
			$('.append-week-class').find('.eventRepeatWeekdays').append(html);
			$('#busyModal').find('.busyRepeatWeekdays').append(busyHtml);
		}
		/* end: Append randam week acording to calendar setting in class model */
		/* end: Initialize Section */
		/* start: Common Modules Section */
		/* Start: Toggel makeup/recur */
		$('#isCreatingMakeUpSession').change(function(e) {
			var recurrCheckBox = $('#ifRecur'),
			recurrFormGroup = recurrCheckBox.closest('.form-group');
			if (this.checked) {
				recurrCheckBox.prop('checked', false);
				recurrFormGroup.addClass('hidden');
			} else {
				if (recurrFormGroup.hasClass('hidden')) recurrFormGroup.removeClass('hidden');
			}
		});
		$('#ifRecur').change(function(e) {
			var epicCreditCheckBox = $('#isCreatingMakeUpSession'),
			epicCreditFormGroup = epicCreditCheckBox.closest('.form-group'),
			isReducedRate = $('#isReducedRate'),
			isReducedRateGroup = isReducedRate.closest('.form-group');
			if (this.checked) {
				epicCreditCheckBox.prop('checked', false);
				epicCreditFormGroup.addClass('hidden');
				isReducedRate.prop('checked', false);
				isReducedRateGroup.addClass('hidden');
			} else {
				if (epicCreditFormGroup.hasClass('hidden')) epicCreditFormGroup.removeClass('hidden');
				if (isReducedRateGroup.hasClass('hidden')) isReducedRateGroup.removeClass('hidden');
			}
		})
		/* End: Toggel makeup/recur */
		/* start: show staff, location-area selector on click */
		$('.set-group-disp a').click(function(e) {
			e.preventDefault();
			var $this = $(this),
			setGroup = $this.closest('.set-group'),
			setGroupDisp = setGroup.find('.set-group-disp'),
			dd = setGroup.find('select');
			setGroupDisp.hide().siblings().removeClass('hidden');
			dd.removeClass('hidden').prop('required', true);
			if (isFieldMarkedAsDeleted(dd)) {
				toggleDeletedMarkOnField(dd, 0)
				setFieldNeutral(dd);
				if (dd.attr('name') == 'modalLocArea') getAreaStaffs(dd);
			} else if (!isFieldMarkedAsAvail(dd)) {
				toggleAvailableMarkOnField(dd, 1);
				setFieldNeutral(dd);
				if (dd.val()) {
					var fieldName = dd.attr('name');
					if (fieldName == 'modalLocArea') fieldName = 'area';
					else if (fieldName == 'staff') fieldName = 'staff';
					saveFieldPrompt(fieldName)
				}
			}
		});
		/* end: show staff, location-area selector on click */
		/* start: Toggle event repeat fields */
		$('select[name="eventRepeat"]').change(function() {
			var $this = $(this),
			selVal = $this.val(),
			eventRepeatFields = $this.closest('.event-reccur').find('.eventRepeatFields'),
			eventRepeatIntervalDd = eventRepeatFields.find('select[name="eventRepeatInterval"]'),
			eventRepeatIntervalUnit = eventRepeatFields.find('.eventRepeatIntervalUnit'),
			eventRepeatWeekdays = eventRepeatFields.find('.eventRepeatWeekdays');
			busyRepeatWeekdays = eventRepeatFields.find('.busyRepeatWeekdays');
			busyRepeatWeekdays.hide();
			eventRepeatWeekdays.hide();
			setFieldNeutral(eventRepeatWeekdays)
			if (selVal == 'Daily' || selVal == 'Weekly' || selVal == 'Monthly') {
				eventRepeatFields.show();
				eventRepeatIntervalDd.prop('required', true)
				if (selVal == 'Daily') eventRepeatIntervalUnit.text('days')
					else if (selVal == 'Weekly') {
						eventRepeatWeekdays.show();
						busyRepeatWeekdays.show();
						eventRepeatIntervalUnit.text('weeks');
					} else eventRepeatIntervalUnit.text('months')
				} else {
					eventRepeatFields.hide();
					eventRepeatFields.find(':input').prop('required', false)
				}
			});
		/* end: Toggle event repeat fields */
		/* start: Neutral event repeat weekdays checkbox */
		$('.eventRepeatWeekdays input[type="checkbox"]').change(function() {
			setFieldNeutral($(this))
		});
		/* end: Neutral event repeat weekdays checkbox */
		/* start: Toggle event repeat end fields */
		$('input[name="eventRepeatEnd"]').change(function() {
			var $this = $(this),
			selVal = $this.val(),
			module = $this.closest('.event-reccur'),
			eventRepeatEndAfterOccurDd = module.find('select[name="eventRepeatEndAfterOccur"]'),
			eventRepeatEndOnDate = module.find('input[name="eventRepeatEndOnDate"]');
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
		/* end: Toggle event repeat end fields */

		/* start: Update recurring event */
		$('body').on("click", '.update-event', function(e) {
			e.preventDefault();
			var targetEvents = $(this).data('target-event')
			$('#' + eventModal).find('input[name="targetEvents"]').val(targetEvents)
			if (eventModal == 'classModal') submitClassModal();
			else submitAppointmentModal();
		});
		/* end: Update recurring event */
		
		/* start: Check if clients present in future recure classes */
		function checkClientsPresentInFutureRecureClasses(eventId) {
	        var isClientPresentInFutureClasses = false;
			$.ajax({
				url: public_url + 'staffevents/recure-classes/client-present/' + eventId,
				type: 'GET',
				data: {},
				async: false,
				success: function(response) {
					data = JSON.parse(response);
					isClientPresentInFutureClasses = data.client_present;
				}
			});

			return isClientPresentInFutureClasses;
		}
		/* end: Check if clients present in future recure classes */

		/* start: Delete recurring event */
		$('body').on("click", '.delete-event', function(e) {
			e.preventDefault();
			var targetEvents = $(this).data('target-event');
		
			if (eventModal == 'classModal' && getBookingType() == 'service') {
				deleteAppoint(false, targetEvents, '', 'selectClientAction');
			} else {
				var formData = {};
				formData['eventId'] = eventObj.find('input[name="eventId"]').val();
				var isClientPresentInFutureClasses = checkClientsPresentInFutureRecureClasses(formData['eventId']);
		
				if (targetEvents != null) 
					formData['targetEvents'] = targetEvents;
				if (eventModal == 'classModal') 
					var eventEntity = 'classes';
				else 
					var eventEntity = 'busy-time';
				if(formData['targetEvents'] == 'future' && isClientPresentInFutureClasses == true) {
					/* If clients are available in future recure classes */
					swal({
						title: 'This & Future classes can\'t be deleted due to clients available in future classes.',
						allowOutsideClick: false,
						showCancelButton: false,
						confirmButtonText: 'Okay',
						confirmButtonColor: '#ff4401',
						type: 'warning',
					});
				} else {
					/* If clients are not available in future recure classes */
					toggleWaitShield('show', 'eventDelCur');
					$.post(public_url + 'staffevents/' + eventEntity + '/delete', formData, function(data) {
						var data = JSON.parse(data);
						if (data.status == "deleted") {
							toggleWaitShield('hide', 'eventDelCur')
							reloadEvents = false;
							cancelEventSuccess(data);
							
						}
					});
				}
			}
		});
		/* end: Delete recurring event */
		/* start: Populate staffs based on the area */
		$('select.loc-area-dd').change(function() {
			var $this = $(this);
			console.log('ji');
			if (!$this.hasClass('toolBarDd')) toggleDeletedMarkOnField($this, 0)
			if(eventModal != 'busyModal'){
				getAreaStaffs($this);
				
			}
			$(document).ajaxStop(function () {
				setTimeout(function(){ // Timeout
					$('html, body').animate({
						scrollTop: $('td.fc-today:first').offset().top -200
					}, 0);
				},2000);
			  });
			  
			
		});
		/* end: Populate staffs based on the area */
		/* start: Populate services/classes based on the area */
		$('select[name="staff"]').change(function() {
			var $this = $(this);
			var fromBusyModal = '';
			toggleDeletedMarkOnField($this, 0)
			if ($this.parents('#busyModal').lenght == 1) {
				var areaDd = $('#busyModal').find('select[name="modalLocArea"]');
				if (areaDd.find('option').length) {
					var selectedStaffFavAreas = getFavAreas();
					if (selectedStaffFavAreas.length) {
						setAreas(selectedStaffFavAreas);
					}
				}
			} else if (getBookingType() == 'class') {
				var areaDd = classModal.find('select[name="modalLocArea"]');
				if (areaDd.find('option').length) {
					var selectedStaffFavAreas = getFavAreas();
					if (selectedStaffFavAreas.length) {
						setAreas(selectedStaffFavAreas);
					}
				}
			}
			saveFieldPrompt('staff');
		});
		/* end: Populate services/classes based on the area */
		/* start: Update area on change */
		$('select[name="modalLocArea"]').change(function() {
			getAvailableStaffs();	
			if (saveAreaTimeout) clearTimeout(saveAreaTimeout);
			saveAreaTimeout = setTimeout(function() {
				saveFieldPrompt('area');
			}, 1200);
		});
		/* end: Update area on change */
		/* start: Mark resource dropdown as available on change */
		$('body').on('change', 'select.resource-option', function(e) {
			toggleAvailableMarkOnField($(this), 1);
		});
		/* end: Mark resource dropdown as available on change */
		/* start: Mark resource quantity dropdown as available on change */
		$('body').on('change', 'select.item-op', function(e) {
			toggleAvailableMarkOnField($(this), 1);
		});
		/* end: Mark resource quantity dropdown as available on change */
		/* start: Check form is dirty on tab switch */
		classModal.find('.nav-tabs a').on('click', function(e) {
			if (!isModalModeEdit()) return false;
			if (!pastCurrentMode && !cancelledBooking && (!isUserType(['Staff']) || eventObj.find('input[name="userId"]').val() == loggedInUser.userId)) {
				var activeTab = getActiveTab();
				if ( /*activeTab == 'classDetails' || */ activeTab == 'classReccur' || activeTab == 'classNotes') {
					var tabId = $(this).attr('href');
					tabId = tabId.substring(1);
					if (tabId != activeTab) {
						var tab = classModal.find('#' + activeTab);
						if (dirtyForm.isDirty(tab)) {
							saveBookingPrompt(tabId, function() {
								/*if(activeTab == 'classDetails'){
									classModal.find('input[name="classCap"]').val(eventObj.find('input[name="capacity"]').val())
									updateBookedclientQuickInfo();
									toggleEventOverBookAlert();
								}*/
								switchTabTo(tabId, classModal);
							});
							return false;
						}
					}
				}
			}
		});
		/* end: Check form is dirty on tab switch */
		/* start: Init dirty form and show/hide submit button over tab if eligible */
		classModal.find('.nav-tabs a').on('shown.bs.tab', function(e) {
			toggleSubmitBtn();
		});
		/* end: Init dirty form and show/hide submit button over tab if eligible */
		/* start: Remove client from booking */
		$('body').on("click", '.removeClient', function(e) {
			e.preventDefault();
			var $this = $(this),
			targetEvents = $this.data('target-event')
			classModal.find('input[name="targetEvents"]').val(targetEvents)
			removeClientAjax($this.data('client-id'), 'selectClientAction');
		});
		/* end: Remove client from booking */

		/* start: Get staffs and areas list according to time */
		classModal.find('.timepicker1').on('changeTime.timepicker', function(e) {
			if (!blockTimepickerChangeEvent && $(this).parent().hasClass('eventTime')) {
				getStaffsAndAreas('', function() {
					saveFieldPrompt('time');
				})
			}
		});
		/* end: Get staffs and areas list according to time */

		/* start: Remove resource */
		$('body').on('click', '.remove-resourcelink-row', function(e) {
			saveFieldPrompt('resourceRemove');
		});
		/* end: Remove resource */
		/* start: Update/add resource */
		$('body').on('change', 'select.resource-option, select.item-op', function(e) {
			var $this = $(this),
			row = $this.closest('.row'),
			secondDd = row.find('select').not($this);
			if ($this.val() && secondDd.val() != '') saveFieldPrompt('resource');
		});
		/* end: Update/add resource */
		/* end: Common Modules Section */
		/* start: Appointment Section */
		/* start: Initialize selectable plugin */
		$(".appoint_status_selectable", appointModal).selectable({
			stop: function() {
				toggleAppointStatusFields($(this));
			}
		});
		/* end: Initialize selectable plugin */
		/* start: Toggle event auto expire fields */
		$('input[name="ifAutoExpireAppoint"]', appointModal).change(function() {
			var autoExpireAppoint = $('.autoExpireAppoint'),
			autoExpireAppointDurCustom = $('.autoExpireAppointDurCustom'),
			autoExpireAppointDurCustomFields = autoExpireAppointDurCustom.find('input'),
			dd = autoExpireAppoint.find('select');
			if ($(this).is(":checked")) {
				autoExpireAppoint.show();
				dd.val('');
				dd.prop('required', true)
				dd.selectpicker('refresh');
			} else {
				autoExpireAppoint.hide();
				dd.prop('required', false)
			}
			autoExpireAppointDurCustom.hide();
			autoExpireAppointDurCustomFields.prop('required', false)
		})
		/* end: Toggle event auto expire fields */
		/* start: Toggle manual date time fields for event auto expire */
		$('select[name="autoExpireAppointDur"]', appointModal).change(function() {
			var autoExpireAppoint = $('.autoExpireAppoint'),
			autoExpireAppointDurCustom = $('.autoExpireAppointDurCustom'),
			autoExpireAppointDurCustomFields = autoExpireAppointDurCustom.find('input');
			if ($(this).val() == 'Custom') {
				autoExpireAppointDurCustom.show();
				autoExpireAppointDurCustomFields.prop('required', true)
				autoExpireAppoint.hide();
			} else autoExpireAppointDurCustomFields.prop('required', false)
		})
		/* end: Toggle manual date time fields for event auto expire */
		/* start: Populate service fields based on service selected */
		$('body').on('change', '#appointModal select.serviceName', function() {
			var row = $(this).closest('.row'),
			selOpt = $(this).find(":selected");
			var price = selOpt.data('price');
			priceField = row.find('input.servicePrice');
			priceField.val(price);
			if (price) setFieldNeutral(priceField);
			var duration = selOpt.data('duration'),
			durationDd = row.find('select.serviceDur');
			durationDd.val(duration).trigger('change');
			//if(duration)
			//setFieldNeutral(durationDd);
			durationDd.selectpicker('refresh');
			row.find('input[type="hidden"]').val('false');
		})
		/* end: Populate service fields based on service selected */
		/* start: Time field neutral on blur */
		$('input[name="serviceTime0"]', appointModal).blur(function() {
			setFieldNeutral(appointModal.find('.eventDateDisp'));
		});
		/* end: Time field neutral on blur */
		/* start: check on blur if entered time is less then previous service endtime */
		$('body').on('blur', '#appointModal .services input.timepicker1', function() {
			var $this = $(this),
			row = $this.closest('.row');
			setFieldNeutral($this);
			var prevRow = row.prev(),
			eventDate = setEventDate(appointModal, 'submit'),
			date = eventDate.date,
			newTime = $this.val(),
			dur = row.find("select.serviceDur").val(),
			newDatetime = '',
			newEndDatetime = '';
			if (newTime) newDatetime = moment(date + ' ' + newTime, 'YYYY-MM-DD h:mm A').format('YYYY-MM-DD HH:mm:ss');
			if (dur && newDatetime) newEndDatetime = moment(newDatetime).add(dur, 'm').format("YYYY-MM-DD HH:mm:ss");
			if (prevRow.hasClass('m-t-5')) {
				var prevRowEndDatetime = '',
				prevRowDur = prevRow.find("select.serviceDur").val();
				if (prevRowDur) {
					var prevRowTime = prevRow.find('input.timepicker1').val(),
					prevRowDatetime = moment(date + ' ' + prevRowTime, 'YYYY-MM-DD h:mm A').format('YYYY-MM-DD HH:mm:ss');
					prevRowEndDatetime = moment(prevRowDatetime).add(prevRowDur, 'm').format("YYYY-MM-DD HH:mm:ss");
				}
				var changeTime = false;
				/* Check if new time is empty*/
				if (!newTime && prevRowEndDatetime) changeTime = true;
				else if (newTime && prevRowEndDatetime) {
					var isSameDay = true;
					/*  Check if new end datetime is jumping to new date */
					if (newEndDatetime) {
						isSameDay = moment(newEndDatetime).isSame(prevRowEndDatetime, 'day');
						if (!isSameDay) changeTime = true;
					}
					/*  Check if new new time is less than previous row end time */
					if (isSameDay) {
						var isInPast = moment(newDatetime).isBefore(prevRowEndDatetime);
						if (isInPast) changeTime = true;
					}
				}
				if (changeTime) setFieldForTimepicker($this, moment(prevRowEndDatetime).format("h:mm A"))
			} else if (newDatetime && newEndDatetime) {
				var isSameDay = moment(newDatetime).isSame(newEndDatetime, 'day');
				if (!isSameDay) {
					setFieldForTimepicker($this, '')
					return;
				}
			}
			if (newEndDatetime) {
				var nextRow = row.next();
				if (nextRow.length) {
					var nextRowTimeField = nextRow.find('input.timepicker1');
					nextRowTimeField.trigger('blur');
				}
			}
		});
		/* end:check on blur if entered time is less then previous service endtime */
		/* start: Submit appointment modal */
		$(".submit", appointModal).click(function() {
			var form = appointModal.find('form'),
			clientForm = appointModal.find('.client-form'),
			isFormValid = true;
			form.validate().settings.ignore = "input[type='hidden']";
			isFormValid = form.valid();
			if (!validateClientForm(clientForm, false)) isFormValid = false;
			var staffDd = form.find('select[name="staff"]');
			if (staffDd.valid() && !isFieldMarkedAsAvail(staffDd)) {
				isFormValid = false;
				setFieldInvalid(staffDd.closest('.form-group'), 'Please select new staff.')
			}
			var areaDd = form.find('select[name="modalLocArea"]');
			if (areaDd.valid() && !isFieldMarkedAsAvail(areaDd)) {
				isFormValid = false;
				setFieldInvalid(areaDd.closest('.form-group'), 'Please select new area.')
			}
			moveErrorMsg(appointModal);
			if (isFormValid) {
				var isRepeating = form.find('input[name="isRepeating"]').val()
				if (isRepeating == 1) showPopoverOverModal($(this), editReccurEventPopoverOpt);
				else submitAppointmentModal(form, clientForm);
			} else switchToErrorTab(form);
		});
		/* end: Submit appointment modal */
		/* start: to correct service start time if changed */
		$('body').on('change', '#appointModal select.serviceDur', function(e) {
			var $this = $(this),
			newDur = $this.val(),
			refetchStaffs = true;
			setFieldNeutral($this);
			if (newDur) {
				var row = $this.closest('.row'),
				timeField = row.find("input.timepicker1"),
				time = timeField.val();
				if (time) {
					var eventDate = setEventDate(appointModal, 'submit'),
					date = eventDate.date,
					datetime = moment(date + ' ' + time, 'YYYY-MM-DD h:mm A').format('YYYY-MM-DD HH:mm:ss'),
					newEndDatetime = moment(datetime).add(newDur, 'm').format("YYYY-MM-DD HH:mm:ss");
					isSameDay = moment(datetime).isSame(newEndDatetime, 'day');
					if (!isSameDay) {
						var maxDatetime = moment(date + ' 11:55 PM', 'YYYY-MM-DD h:mm A'),
						maxDur = maxDatetime.diff(datetime, 'minutes');
						$this.val(maxDur).selectpicker('refresh');
					}
					var nextRow = row.next();
					if (nextRow.length) {
						refetchStaffs = false;
						timeField.trigger('blur');
					}
				}
			}
			if (refetchStaffs) {
				getStaffsBasedOnServices();
				getAreasBasedOnServices();
			}
		});
		/* end: to correct service start time if changed */
		/* start: remove service row */
		$('body').on('click', '#appointModal .removeService', function(e) {
			e.preventDefault();
			removeServiceRow($(this));
			getStaffsBasedOnServices();
			getAreasBasedOnServices();
		});
		/* end: remove service row */
		/* start: add service row */
		$('body').on('click', '#appointModal .addMoreService', function(e) {
			e.preventDefault();
			formRowsCreated++;
			var $this = $(this),
			thisRow = $this.closest('.row'),
			parCont = $this.closest('.form-group'),
			serviceRow = thisRow.clone(),
			servicePrice = serviceRow.find('input.servicePrice'),
			datetimepickerField = serviceRow.find('input.timepicker1'),
			lastRow = thisRow.closest('.form-group').children().last();
			toggleRecurrTab('hide');
			serviceRow.find('.addMoreService').parent().html('<a class="btn btn-primary removeService" href="#"><i class="fa fa-remove"></i></a>');
			serviceRow.find('select').each(function(index, elem) {
				$elem = $(elem),
				$elem.parent().parent().html(elem);
				if ($elem.hasClass('serviceName')) {
					var ddName = 'serviceName';
					$elem.removeAttr('data-unique-id');
				} else if ($elem.hasClass('serviceDur')) var ddName = 'serviceDur';
				$(elem).prop('name', ddName + formRowsCreated);
			})
			serviceRow.find('select').selectpicker('refresh');
			servicePrice.prop('name', 'servicePrice' + formRowsCreated);
			servicePrice.val('');
			serviceRow.find('input[type="hidden"]').prop('name', 'serviceIsDeleted' + formRowsCreated).val('false');
			datetimepickerField.prop('name', 'serviceTime' + formRowsCreated);
			//Checking if service duration exist 
			var lastRowDurDd = lastRow.find("select.serviceDur"),
			lastRowDur = lastRowDurDd.val(),
			isValid = true;
			if (!lastRowDur) {
				setFieldInvalid(lastRowDurDd.closest('.form-group'), '');
				isValid = false;
			}
			//Checking if service time exist 
			var lastRowTimeField = lastRow.find('input.timepicker1'),
			lastRowTime = lastRowTimeField.val();
			if (!lastRowTime) {
				setFieldInvalid(lastRowTimeField.closest('.form-group'), '');
				isValid = false;
			}
			//Checking if end date is same as start date
			var eventDate = setEventDate(appointModal, 'submit');
			date = eventDate.date,
			eventStart = moment(date + ' ' + lastRowTime, '"YYYY-MM-DD h:mm A').format("YYYY-MM-DD HH:mm:ss"),
			eventEnd = moment(eventStart).add(lastRowDur, 'm').format("YYYY-MM-DD HH:mm:ss"),
			isSameDay = moment(eventStart).isSame(eventEnd, 'day');
			if (!isSameDay) isValid = false;
			if (!isValid) return false;
			setFieldForTimepicker(datetimepickerField, moment(eventEnd).format("h:mm A"))
			parCont.append(serviceRow);
		});
		/* end: add service row */
		appointModal.find('.services .timepicker1').on('changeTime.timepicker', function(e) {
			if (!blockTimepickerChangeEvent) {
				getStaffsBasedOnServices();
				getAreasBasedOnServices();
			}
		});
		/* end: Appointment Section */
		/* start: Class Section */
		/* start: Switch to next visible tab */
		$('button#nextTab', classModal).click(function(e) {
			var nextTab = $('ul#classTabs li.active').nextAll().has('a:visible').first();
			var nextTabId = nextTab.find('a').attr('href');
			var currentTab = getActiveTab();
			var tab_id = classModal.find('#' + currentTab);
			if (isModalModeEdit() && currentTab != '#classDetails' && dirtyForm.isDirty(tab_id)) {
				nextTab.children('a').trigger('click');
			} else {
				if (nextTab.length) nextTab.children('a').tab('show')
					else $('ul#classTabs li a:visible').first().tab('show')
				}
		});
		/* end: Switch to next visible tab */
		/* start: Populate class fields based on class selected */
		$('select[name="staffClass"]', classModal).change(function() {
			var $this = $(this),
			modal = $this.closest('.modal'),
			selOpt = $this.find(":selected"),
			duration = modal.find('select[name="classDur"]'),
			capacity = modal.find('input[name="classCap"]'),
			price = modal.find('input[name="classPrice"]');
			capacity.val(selOpt.data('capacity'));
			setFieldNeutral(capacity);
			updateBookedclientQuickInfo();
			price.val(selOpt.data('price'));
			setFieldNeutral(price);
			duration.val(selOpt.data('duration'));
			setFieldNeutral(duration);
			duration.selectpicker('refresh');
			getStaffsAndAreas('class', function() {
				saveFieldPrompt('class');
			})
		});
		/* end: Populate class fields based on class selected */
		/* start: Get staffs list according to duration */
		$('select[name="classDur"]', classModal).change(function() {
			getStaffsAndAreas('class', function() {
				saveFieldPrompt('duration');
			})
		});
		/* end: Get staffs list according to duration */
		/* start: Update booked client quick info on capacity change */
		$('input[name="classCap"]', classModal).change(function() {
			updateBookedclientQuickInfo();
			saveFieldPrompt('capacity');
		});
		/* end: Update booked client quick info on capacity change */
		/* start: Update price on change */
		$('input[name="classPrice"]', classModal).blur(function() {
			saveFieldPrompt('price');
		});
		/* end: Update price on change */
		/* start: Toggle reduced rate field */
		$('#isReducedRate', classModal).change(function() {
			toggleReduceRate();
			/** Reduce rate checked and hide recurence */
			ifRecur = $('#ifRecur'),
			ifRecurGroup = ifRecur.closest('.form-group');
			if (this.checked) {
				ifRecur.prop('checked', false);
				ifRecurGroup.addClass('hidden');
			} else {
				if (ifRecurGroup.hasClass('hidden')) ifRecurGroup.removeClass('hidden');
			}
		});
		/* end: Toggle reduced rate field */
		/* start: Populate linked client data */
		$('body').on("click", '#classModal div#waitingLinkedclientList> a,div#linkedclientList> a', function(e) {
			e.preventDefault();
			var $this = $(this),
			clientData = {};
			clientForm = classModal.find('.client-form'),
			clientField = clientForm.find('.clientList'),
			disableable = clientForm.find('.disableable'),
			isReducedRate = disableable.filter('input[name="isReducedRate"]'),
			reducedRate = clientForm.find('input[name="reducedRate"]'),
			ifRecur = disableable.filter('input[name="ifRecur"]'),
			isLdc = clientForm.find('input[name="isLdc"]');
			clientField.val($this.data('name'));
			clientForm.find('.callClientSubview').hide();
			clientForm.find('.noneditable').prop('readonly', true);
			disableable.prop('disabled', true);
			clientForm.find('textarea[name="clientNote"]').val($this.data('note'));
			if ($this.data('is-reduced-rate')) {
				isReducedRate.prop('checked', true);
				toggleReduceRate('show');
				reducedRate.val($this.data('reduced-rate'))
			} else {
				isReducedRate.prop('checked', false);
				toggleReduceRate('hide');
			}
			/*** Set is LDC value ***/
			if($this.data('is-ldc')){
				isLdc.val($this.data('is-ldc'));
			}else{
				isLdc.val(0);
			}
			if ($this.data('if-recur')) ifRecur.prop('checked', true);
			else ifRecur.prop('checked', false);
			clientData['id'] = $this.data('id');
			clientData['phone'] = $this.data('phone');
			clientData['email'] = $this.data('email');
			clientData['accStatus'] = $this.data('acc-status');
			populateClientDetails(clientData, clientField, 'old_client');
			$this.siblings().removeClass('active');
			$this.addClass('active');
			var bookingType = getBookingType()
			if (bookingType == 'class') toggleClassClientLnks('view');
			else if (bookingType == 'service') toggleClassClientLnks('view', true);
			resetMakeupRecurrField();
		});
		/* end: Populate linked client data */
		/* start: Reset client form to add new client */
		$('a#resetClientlinkForm', classModal).click(function(e) {
			e.preventDefault();
			clearClientForm(classModal.find('.client-form'), true);
			toggleClassClientLnks('create')
		});
		/* end: Reset client form to add new client */
		/* start: Open client tab */
		$('a.show-clients-tab', classModal).click(function(e) {
			e.preventDefault();
			if (isModalModeEdit()) switchTabTo('classClients', classModal);
		});
		/* end: Open client tab */
		/* start: Prompt to delete class */
		$('.delete-prompt').click(function(e) {
			e.preventDefault();
			//var isRepeating = $(this).closest('.modal').find('input[name="isRepeating"]').val()
			var isRepeating = eventObj.find('input[name="isRepeating"]').val()
			if (isRepeating == 1) 
				var delPopover = deleteReccurEventPopoverOpt;
			else 
				var delPopover = deleteEventPopoverOpt;

			showPopoverOverModal($(this), delPopover);
		});
		/* end: Prompt to delete class */
		/* start: Cancel class deletion */
		$('body').on("click", '.cancel-delete-event', function(e) {
			e.preventDefault();
			calPopupHelper.trigger("click");
		});
		/* end: Cancel class deletion */
		/* start: Link client class */
		classModal.find('a#linkClientClass').click(function(e) {
			e.preventDefault();
			clearNotific('clientsBusy');
			var clientForm = classModal.find('.client-form');
			if (validateClientForm(clientForm, true)) {
				var clientId = clientForm.find('input[name="clientId"]').val(),
				ifClientAlreadyLinked = false;
				if (linkedclientsCount()) {
					var linkedclient = classModal.find('div#linkedclientList > a[data-id="' + clientId + '"]');
					if (linkedclient.length) {
						ifClientAlreadyLinked = true;
						linkedclient.trigger('click');
					}
				}
				if (!ifClientAlreadyLinked) {
					classOverBookConfirm(function() {
						//var bookingType = getBookingType();
						//if(bookingType == 'class'){
						// var useEpicCashField = clientForm.find('input[name="isCreatingMakeUpSession"]');
						// if(useEpicCashField.is(':checked') && !isEpicCashBig(useEpicCashField.data('epic-cash'))){
						// 	swal({
						// 		title: 'This client doesn\'t have enough epic credit.',
						// 		allowOutsideClick: true,
						// 		showCancelButton: true,
						// 		confirmButtonText: 'Add with invoice',
						// 		confirmButtonColor: '#ff4401',
						// 		cancelButtonText: "Don't add"
						// 	}, 
						// 	function(isConfirm){
						// 		if(isConfirm){
						// 			useEpicCashField.prop('checked', false)
						// 			linkClientClassAjax(clientForm)
						// 		}
						// 	});	
						// 	return false;
						// }	
						//}
						linkClientClassAjax(clientForm);
					})
				}
			}
		})
		/* end: Link client class */
		/* start: Unlink client class */
		classModal.find('a#unlinkClientClass').click(function(e) {
			e.preventDefault();
			
			var clientId = classModal.find('input[name="clientId"]').val(),
			isExistingClient = classClients[clientId].isExisting,
			isWithInvoice = classClients[clientId].isInvoice;

			if (isUserType(['Staff'])) 
				removeClient(clientId);

			else if (isExistingClient && getBookingType() == 'class') { //&& !isUserType(['Staff'])
				if (classClients[clientId].accStatus == 'Active' || classClients[clientId].accStatus == 'Contra') {
					var bookingType = getBookingType();
					var eventId = classModal.find('input[name="eventId"]').val();
					var invoice = getClientEventInvoiceAmount(clientId, eventId, bookingType);
					var classprice = classModal.find('input[name="classPrice"]').val();
					// var isLdcClass = 
					// if(invoice != '' && invoice.inv_status != 'Paid' && (invoice.inv_total - classprice) == 0) 
					if (isWithInvoice == 1 && invoice != '' && invoice.inv_status != 'Paid' && invoice.inv_credit_used == 0) 
						var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='" + clientId + "' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a>" //Make up session
					else if(invoice != '' && invoice.inv_status != 'Paid' && invoice.inv_credit_used == 0)
					{
						var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='" + clientId + "' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a>" //Make up session
					}
					else if (classClients[clientId].isLdc) 
						var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='" + clientId + "' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a>" //Make up session
					else 
						var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='" + clientId + "' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a><a class='btn btn-primary' href='#' data-client-id='" + clientId + "' data-callback='removeClientAjax(" + clientId + ")' data-toggle='modal' data-target='#raiseMakeUpModel' data-modalpage='calendar'>Add EPIC Credit</a>" //Make up session
				} else 
				var text = "<a class='btn btn-primary w160 unlink-client-class-alert' href='#' data-client-id='" + clientId + "' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a>"
				swal({
					title: clientSwalOpt.title,
					showCancelButton: clientSwalOpt.showCancelButton,
					cancelButtonText: 'Just delete it',
					allowOutsideClick: clientSwalOpt.allowOutsideClick,
					html: clientSwalOpt.html,
					text: text,
					showConfirmButton: clientSwalOpt.showConfirmButton,
					customClass: clientSwalOpt.customClass,
					closeOnCancel: false
				}, function(isConfirm) {
					if (!isConfirm) {
						removeClient(clientId, 'selectClientAction');
					}
				});
			} else unlinkClientClass(clientId);
		});
		/* end: Unlink client class */
		/* start: Unlink client class alert */

		$('body').on('click', 'a.unlink-client-class-alert', function(e) {
			e.preventDefault();
            resechedule = $(this).attr('data-action');
			var $this = $(this),

			bookingType = getBookingType();
			if (window.location.href.includes('client')) {
				if (bookingType == 'class') {
					var clientId = $this.data('client-id');
					if ($this.data('action') == 'resechedule') {
						rescheduleClients[clientId] = {
							accStatus: classClients[clientId].accStatus,
							note: classClients[clientId].note,
							isReducedRate: classClients[clientId].isReducedRate,
							reducedRate: classClients[clientId].reducedRate,
							ifRecur: classClients[clientId].ifRecur,
							isLdc: classClients[clientId].isLdc,
							order: rescheduleClientsCount() + 1
						};
						enterReschecduleClientMode(window.location.href.includes('client') ? 'client-profile' : '');
					}
				} else if (bookingType == 'service') {
					enterReschecduleClientMode(window.location.href.includes('client') ? 'client-profile' : '');
				}
				var callback = $this.data('callback');
				if (callback && $this.data('action') != 'resechedule') eval(callback);
			}
			else {
				if (bookingType == 'class') {
					var clientId = $this.data('client-id');
					if ($this.data('action') == 'resechedule') {
						rescheduleClients[clientId] = {
							accStatus: classClients[clientId].accStatus,
							note: classClients[clientId].note,
							isReducedRate: classClients[clientId].isReducedRate,
							reducedRate: classClients[clientId].reducedRate,
							ifRecur: classClients[clientId].ifRecur,
							isLdc: classClients[clientId].isLdc,
							order: rescheduleClientsCount() + 1
						};
					}
				}
				var callback = $this.data('callback');
				if (callback) eval(callback);
			}
			// swal.close();
		});
		/* end: Unlink client class alert */
		/* Start: Raise makeup modal show event */
		$("#raiseMakeUpModel").on('show.bs.modal', function(evt) {
			swal.close();
			var elem = $(evt.relatedTarget),
			$this = $(this);
			var page = elem.data('modalpage');
			var clientId = elem.data('client-id');
			var eventId = classModal.find('input[name="eventId"]').val();
			var isWithInvoice = classClients != null && classClients != undefined && Object.keys(classClients).length != 0 ? classClients[clientId].isInvoice : 0;
			// console.log(classClients);
			if (typeof page != undefined && page == 'calendar') {
				$this.find('input[name="clientId"]').val(clientId);
				var bookingType = getBookingType();

				$.ajax({
					url: public_url + 'clients/makeup-netamount/' + clientId,
					type: 'GET',
					data: { eventId: eventId,
					bookingType: bookingType},
					success: function(response) {
						var data = JSON.parse(response);
						if (data.status == "success") {
							if (data.netamount == '' || data.netamount == null) {
								data.netamount = 0.00;
							}
							$this.find('#netamount').text(data.netamount);
							$this.find('[name="net_amount"]').val(data.netamount);
								if (bookingType == 'service') {
									if(data.price != undefined && data.price != '' && data.price != 0){
										price = data.price;
									}else{
									price = eventObj.find('input[name="price"]').val();
									}
								}else if (bookingType == 'class') {
									if (!isNaN(classClients[clientId].reducedRate) && classClients[clientId].reducedRate) {
										price = classClients[clientId].reducedRate;
										
									}else{
										if(data.price != undefined && data.price != '' && data.price != 0){
												price = data.price;
										}else{
												price = eventObj.find('input[name="price"]').val();
										}
									}
								}
							var amount = $this.find('[name="amount"]') /*.attr("disabled", true)*/ ;
							setFieldNeutral(amount);
							
							amount.val(invoice != undefined && invoice != null && invoice != '' && invoice.inv_status == 'Unpaid' ? invoice.inv_credit_used : price);	
						}
					},
				});
				var eventId = eventObj.find('input[name="eventId"]').val();
				var invoice = getClientEventInvoiceAmount(clientId, eventId, bookingType);
				var price = 0;
				var purpose_field = $this.find('[name="purpose"]');

				if (bookingType == 'service') {
					// price = eventObj.find('input[name="price"]').val();
					var purposeFiled = purpose_field.val('service').attr("disabled", true);
				} else if (bookingType == 'class') {
					// if (classClients[clientId].reducedRate) {
					// 	price = classClients[clientId].reducedRate;
					// } else {
					// 	// price = eventObj.find('input[name="price"]').val();
					// }
					var purposeFiled = purpose_field.val('class').attr("disabled", true);
				}
				purposeFiled.selectpicker('refresh');
				setFieldNeutral(purposeFiled);
				var callback = elem.data('callback');
				$this.find('input[name="callback"]').val(callback);
				$this.find('[name="notes"]').val('');
				// var amount = $this.find('[name="amount"]') /*.attr("disabled", true)*/ ;
				// setFieldNeutral(amount);
				// // amount.val(price - (invoice != null && invoice != '' && invoice.inv_status == 'Unpaid' ? invoice.inv_total : 0));
				// amount.val(invoice != undefined && invoice != null && invoice != '' && invoice.inv_status == 'Unpaid' ? invoice.inv_credit_used : price);
				$this.find('.checkbox').hide();
			}
		});
		/* End: Raise makeup modal show event*/
		/* Start: Raise makeup modal hide event*/
		$("#raiseMakeUpModel").on('hidden.bs.modal', function(evt) {
			$('#classModal:visible').length && $(document.body).addClass('modal-open');
			var modifiedRow = $('#modifiedRow');
			if (modifiedRow.length) {
				activateAttendanceRadio(modifiedRow.data('prev-val'), modifiedRow);
				modifiedRow.removeAttr('id')
			}
		});
		/* End: Raise makeup modal hide event*/
		/* Start: Raise makeup modal submit*/
		$('#raiseMakeUpModel .submit').click(function() {
			clearNotific('amountOverFlow');
			var modal = $('#raiseMakeUpModel');
			var callback = modal.find('[name="callback"]').val();
			if (typeof callback != 'undefined' && callback != '') {
				form = modal.find('form'),
				isFormValid = true;
				//var flag=false;
				isFormValid = form.valid();
				if (isFormValid) {
					$(this).prop('disabled', true);
					var clientId = form.find('input[name="clientId"]').val();
					var amountField = form.find('[name="amount"]');
					var amount = parseFloat(amountField.val());
					if (amount <= 0) {
						setFieldInvalid(amountField.closest('.form-group'), 'Please renter valid amount.');
						return false;
					}
					classClients[clientId].purpose = form.find('[name="purpose"]').val();
					classClients[clientId].amount = amount;
					classClients[clientId].ifMakeUp = 1;
					classClients[clientId].makeupNotes = form.find('textarea[name="notes"]').val();
					if (callback) eval(callback)
				}
		}
	})
		/* End: Raise makeup modal submit*/
		/* start: Change client attendance */
		$('body').on('change', '#classAttendanceList input', function(e) {
			var $this = $(this),
			clientId = $this.data('client-id'),
			clientNewAttendance = $this.val();
			$this.closest('.btn-group').attr('id', 'modifiedRow');
			if (clientNewAttendance == 'Did not show') {
				var isExistingClient = classClients[clientId].isExisting;
				if (isExistingClient && (!isUserType(['Staff']))) {
					var bookingType = getBookingType();
					if (bookingType == 'class') {
						if (classClients[clientId].accStatus == 'Active' || classClients[clientId].accStatus == 'Contra') var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='" + clientId + "' data-action='resechedule' data-callback='changeclientAttendance(" + clientId + ", \"" + clientNewAttendance + "\", " + true + ")'>Reschedule</a><a class='btn btn-primary' href='#' data-client-id='" + clientId + "' data-callback='changeclientAttendance(" + clientId + ", \"" + clientNewAttendance + "\")' data-toggle='modal' data-target='#raiseMakeUpModel' data-modalpage='calendar' >Add EPIC Credit</a>"
							else var text = "<a class='btn btn-primary w245 unlink-client-class-alert' href='#' data-client-id='" + clientId + "' data-action='resechedule' data-callback='changeclientAttendance(" + clientId + ", \"" + clientNewAttendance + "\", " + true + ")'>Reschedule</a>"
						} else if (bookingType == 'service') {
							if (!isSalesProcessEvent() && (classClients[clientId].accStatus == 'Active' || classClients[clientId].accStatus == 'Contra')) var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-callback='changeclientAttendance(" + clientId + ", \"" + clientNewAttendance + "\", " + true + ")'>Reschedule</a><a class='btn btn-primary' href='#' data-client-id='" + clientId + "' data-callback='changeclientAttendance(" + clientId + ", \"" + clientNewAttendance + "\")' data-toggle='modal' data-target='#raiseMakeUpModel' data-modalpage='calendar'>Add EPIC Credit</a>"
								else var text = "<a class='btn btn-primary w245 unlink-client-class-alert' href='#' data-callback='changeclientAttendance(" + clientId + ", \"" + clientNewAttendance + "\", " + true + ")'>Reschedule</a>"
							}
						swal({
							title: clientSwalOpt.title,
							showCancelButton: clientSwalOpt.showCancelButton,
							cancelButtonText: 'Just change attendance',
							allowOutsideClick: clientSwalOpt.allowOutsideClick,
							html: clientSwalOpt.html,
							text: text,
							showConfirmButton: clientSwalOpt.showConfirmButton,
							customClass: clientSwalOpt.customClass
						}, function(isConfirm) {
							if (!isConfirm) changeclientAttendance(clientId, clientNewAttendance);
						});
						$('.sweet-overlay').click(function() {
							activateAttendanceRadio(classClients[clientId].attendance, classModal.find('#eventClientAttendance-' + clientId));
							$(this).off('click');
						});
					} else changeclientAttendance(clientId, clientNewAttendance);
				} else changeclientAttendance(clientId, clientNewAttendance);
			});
		/* end: Change client attendance */
		/* start: Change all client attendace to attended */
		classModal.find('div#classAttendance a').click(function(e) {
			e.preventDefault();
			toggleWaitShield('show', 'changeAttendBulk');
			//fix controller
			var clientsId = [];
			var attendedInput = activateAttendanceRadio('Attended');
			attendedInput.each(function() {
				var $this = $(this),
				id = $this.data('client-id');
				changeclientAttendanceUi(id, $this.val());
				clientsId.push(id);
			})
			attendedInput.closest('.btn-group').data('prev-val', 'Attended')
			if (clientsId.length) {
				var formData = {};
				formData['eventId'] = classModal.find('input[name="eventId"]').val();
				formData['clientId'] = clientsId;
				formData['clientAttendance'] = 'Attended';
				//toggleWaitShield('show');

				$.post(public_url + 'staffevents/classes/past-update', formData, function(data) {
					var data = JSON.parse(data);
					if (data.status == "added") reloadEvents = true;
					if ('isSalesProcEvent' in data) {
						if(data.salesProcessStepNumber != undefined){
							swal({
								title: 'Do you want to '+data.salesProcessStepName,
								allowOutsideClick: true,
								showCancelButton: true,
								confirmButtonText: 'Yes',
								confirmButtonColor: '#ff4401',
								cancelButtonText: "No"
							}, 
							function(isConfirm){
								isSalesProcessBookStep = false;
								if(isConfirm){
									if(data.salesProcessStepName != undefined || data.salesProcessStepName != ''){
										if(data.salesProcessStepName == 'Book Team?'){
											enterSalesProcessBookMode(data.salesProcessStepNumber,clientsId,type="class",isSalesProcessBookStep = true);
										}else{
											enterSalesProcessBookMode(data.salesProcessStepNumber,clientsId,type="service",isSalesProcessBookStep = true);
										}
									}
									
									
								}
							});
						}else if(data.salesAttendStep){
							swal({
								title: "Do you want to mark step " +data.salesProcessStepName+" as complete?",
								allowOutsideClick: true,
								showCancelButton: true,
								confirmButtonText: 'Yes',
								confirmButtonColor: '#ff4401',
								cancelButtonText: "No"
							}, 
							function(isConfirm){
								if(isConfirm){
									var formData = {};
									formData['action'] = 'complete';
									formData['clientId'] = formData['clientId'];
									formData['stepNumb'] = data.salesAttendStep;
									formData['session'] = 'class';
									updateSalesStepAttendAjax(formData);
								}
							});
						}

						$.each(clientsId, function(key, value) {
							toggleSalesMark(value, data['isSalesProcEvent'][value])
						})
					}
					toggleWaitShield('hide', 'changeAttendBulk');
				});
			} else toggleWaitShield('hide', 'changeAttendBulk');
		});
		/* end: Change all client attendace to attended */
		/* start: Mark staff-area as favorite */
		$('input[name="ifMarkFav"]', classModal).change(function() {
			if ($(this).is(':checked') && classModal.find('input[name="markFavTill"]').val() != '') saveFieldPrompt('markFav');
		});
		$('input[name="markFavTill"]', classModal).change(function() {
			if (classModal.find('input[name="ifMarkFav"]').is(':checked') && $(this).val() != '') saveFieldPrompt('markFav');
		});
		/* end: Mark staff-area as favorite */
		/* start: Submit class modal */
		$('body').on('click', '.submitBooking', function(e) {
			e.preventDefault();

			var $this = $(this);
			
			if (hasData($this, 'action') && $this.data('action') == 'close') {
				classModal.modal('hide');
				return false;
			}
			var activeTab = getActiveTab();
			if (!hasData($this, 'target-tab') && isModalModeEdit() && eventObj.find('input[name="isRepeating"]').val() == 1 && activeTab == 'classReccur') {
				saveBookingPrompt();
				return false;
			}
			swal.close();
			var form = classModal.find('form'),
			isFormValid = true,
			bookingType = getBookingType();
			form.validate().settings.ignore = "input[type='hidden']";
			isFormValid = form.valid();
			if (activeTab == 'classDetails') {
				if (bookingType == 'class' && form.find("input[name='ifMarkFav']:checked").length) {
					var markFavTillField = classModal.find('input[name="markFavTill"]');
					if (!markFavTillField.val()) {
						isFormValid = false;
						setFieldInvalid(markFavTillField.closest('.form-group'), 'This field is required.')
					}
				}
				var staffDd = form.find('select[name="staff"]');
				if (staffDd.valid() && !isFieldMarkedAsAvail(staffDd)) {
					isFormValid = false;
					setFieldInvalid(staffDd.closest('.form-group'), 'Please select new staff.')
				}
				var areaDd = form.find('select[name="modalLocArea"]');
				if (areaDd.valid() && !isFieldMarkedAsAvail(areaDd)) {
					isFormValid = false;
					setFieldInvalid(areaDd.closest('.form-group'), 'Please select new area.')
				}
				if (!validateResourceForm()) isFormValid = false;
				var quantDd = $('#resource-form .resource-clone-class:not(.hidden) select.item-op');
				if (quantDd.length) {
					quantDd.each(function() {
						if (!isFieldMarkedAsAvail($(this))) {
							var formGroup = $(this).closest('.resItemLocDd2');
							setFieldInvalid(formGroup, 'Quantity changed', formGroup.children('.help-block'));
							isFormValid = false;
						}
					})
				}
			} else if (activeTab == 'classReccur') {
				var eventRepeat = form.find('select[name="eventRepeat"]').val();
				if (eventRepeat == 'Weekly') {
					var eventRepeatWeekdaysGroup = form.find('.eventRepeatWeekdays'),
					eventRepeatWeekdays = eventRepeatWeekdaysGroup.find('input[type="checkbox"]:checked');
					if (!eventRepeatWeekdays.length) {
						isFormValid = false;
						setFieldInvalid(eventRepeatWeekdaysGroup.closest('.form-group'), 'Please select reccurence days.', eventRepeatWeekdaysGroup.next())
					}
				}
			}
			moveErrorMsg(classModal);
			if (isFormValid) {
				if (Object.keys(eventObj).length) {
					var isRepeating = eventObj.find('input[name="isRepeating"]').val()
					if (isRepeating == 1) {
						var targetEvents = $this.data('target-event')
						classModal.find('input[name="targetEvents"]').val(targetEvents)
						//showPopoverOverModal($(this), editReccurEventPopoverOpt);
					}
				}
				submitClassModal(form, $this.data('target-tab'));
			}
		});
		/* end: Submit class modal */
		/* start: Save field */
		$('body').on('click', '.saveField', function(e) {
			e.preventDefault();
			swal.close();
			var targetEvents = $(this).data('target-event')
			classModal.find('input[name="targetEvents"]').val(targetEvents)
			saveField($(this).data('field'));
		});
		/* end: Save field */
		/* start: Open client form in subview */
		$('.callClientSubview').click(function(e) {
			e.preventDefault();
			var $this = $(this)
			openSubview($(this));
		});
		/* end: Open client form in subview */
		/* start: Hide client form subview */
		classModal.on('hide.bs.modal', function() {
			classModal.find('.event-date-span').addClass('hidden');
			idClient ='';
			closeSubview();
			resetMakeupRecurrField();
		});
		/* end: Hide client form subview */
		/* end: Class Section */
		/* start: Single service Section */
		/* start: Delete single-service */
		classModal.find('#delService').click(function(e) {
			e.preventDefault();
			/*deleteServiceMode = true;
			classModal.modal('hide');*/
			var clientId = classModal.find('input[name="clientId"]').val(),
			isExistingClient = classClients[clientId].isExisting;
			if (isExistingClient && classModal.find('input[name="appointStatusOpt"]').val() == 'Confirmed' && !isUserType(['Staff'])) {

				if (!isSalesProcessEvent() && (classClients[clientId].accStatus == 'Active' || classClients[clientId].accStatus == 'Contra')) {
					var bookingType = getBookingType();
					var eventId = classModal.find('input[name="eventId"]').val();
					var invoice = getClientEventInvoiceAmount(clientId, eventId, bookingType);
					var serviceprice = classModal.find('input[name="servicePrice"]').val();
					// if(invoice != '' && invoice.inv_status != 'Paid' && (invoice.inv_total - serviceprice) == 0) 
					if (invoice != '' && invoice.inv_status != 'Paid' && invoice.inv_credit_used == 0) 
						var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a>";
					else if (classClients[clientId].isLdc) 
						var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='" + clientId + "' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a>"
					else 
						var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a><a class='btn btn-primary' href='#' data-client-id='" + clientId + "' data-toggle='modal' data-target='#raiseMakeUpModel' data-modalpage='calendar' data-callback='addcreditDeleteAppoint(" + clientId + ")'>Add EPIC Credit</a>";
				} else 
				var text = "<a class='btn btn-primary w160 unlink-client-class-alert' href='#' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a>"
				swal({
					title: clientSwalOpt.title,
					showCancelButton: clientSwalOpt.showCancelButton,
					cancelButtonText: 'Just delete it',
					allowOutsideClick: clientSwalOpt.allowOutsideClick,
					html: clientSwalOpt.html,
					text: text,
					showConfirmButton: clientSwalOpt.showConfirmButton,
					customClass: clientSwalOpt.customClass,
					closeOnCancel: false
				}, function(isConfirm) {
					if (!isConfirm) 
						removeClient(clientId, 'selectClientAction');
				});
			} else removeClient(clientId)
		});
		/* end: Delete single-service */
		/* start: Toggle event auto expire fields */
		$('input[name="ifAutoExpireAppoint"]', classModal).change(function() {
			var $this = $(this);
			toggleAutoExpireFields($this);
			if (!$this.is(':checked')) saveFieldPrompt('autoExpireClr');
		})
		/* end: Toggle event auto expire fields */
		/* start: Initialize selectable plugin */
		$("#appointStatusSelectable").selectable({
			selected: function() {
				toggleAppointStatusFields();
				saveFieldPrompt('status');
			}
		});
		/* end: Initialize selectable plugin */
		/* start: Toggle manual date time fields for event auto expire */
		$('select[name="autoExpireAppointDur"]', classModal).change(function() {
			var val = toggleManualExpireFields($(this));
			if (val != 'Custom') saveFieldPrompt('autoExpire');
		})
		/* end: Toggle manual date time fields for event auto expire */
		/* start: Populate service fields based on service selected */
		$('select[name="staffservice"]', classModal).change(function() {
			var $this = $(this),
			modal = $this.closest('.modal'),
			selOpt = $this.find(":selected"),
			duration = modal.find('select[name="serviceDur"]'),
			price = modal.find('input[name="servicePrice"]');
			price.val(selOpt.data('price'));
			setFieldNeutral(price);
			duration.val(selOpt.data('duration'))
			setFieldNeutral(duration);
			duration.selectpicker('refresh');
			getStaffsAndAreas('service', function() {
				saveFieldPrompt('service');
			})
			if ($this.val()) {
				var forSalesProcessStep = selOpt.data('for-sales-step');
				if (!isModalModeEdit()) {
					if (forSalesProcessStep == 2) {
						toggleAppointStatusFields(calendarSettings.cs_initial_status_consultation);
						if (calendarSettings.cs_initial_status_consultation == 'Pencilled-In') {
							checkedPencilledinCheckbox();
						}
					} else if (forSalesProcessStep == 4) {
						toggleAppointStatusFields(calendarSettings.cs_initial_status_benchmarking);
						if (calendarSettings.cs_initial_status_benchmarking == 'Pencilled-In') {
							checkedPencilledinCheckbox();
						}
					} else toggleAppointStatusFields(calendarSettings.cs_initial_status);
				} else if (!linkedclientsCount()) {
					fetchClientsForTypeahead('update', forSalesProcessStep);
					clearForm(classModal.find('.client-form'));
				}
			}
		})
		/* end: Populate service fields based on service selected */
		/* start: Get staffs list according to duration */
		$('select[name="serviceDur"]', classModal).change(function() {
			getStaffsAndAreas('service', function() {
				saveFieldPrompt('duration');
			})
		});
		/* start: Get staffs list according to duration */
		/* start: Update price on change */
		$('input[name="servicePrice"]', classModal).blur(function() {
			saveFieldPrompt('price');
		});
		/* end: Update price on change */
		/* start: Populate services over appointment cancel modal before showing */
		appointCancelModal.on('show.bs.modal', function() {
			deleteServiceMode = false;
			if (eventObj.find('input[name="isServiceDeleted"]').val() != 'true') var services = '<li class="list-group-item">' + eventObj.find('input[name="serviceName"]').val() + ' with ' + eventObj.find('input[name="staffName"]').val() + ' on ' + moment(eventObj.find('input[name="date"]').val()).format("D MMM") + ' at ' + eventObj.find('input[name="startTime"]').val() + '</li>';
			else var services = '';
			var modal = $(this);
			modal.find('#services').html(services);
			clearForm(modal.find('form'));
			modal.find('select').selectpicker('refresh');
		});
		/* end: Populate services over appointment cancel modal before showing */
		
		/* start: Submit appointment cancel modal */
		$(".submit", appointCancelModal).click(function() {
			//eventModal = appointModal.prop('id');
			eventModal = classModal.prop('id');
			var form = appointCancelModal.find('form');
			if (form.valid()) {
				var isRepeating = eventObj.find('input[name="isRepeating"]').val();
				if (isRepeating == 1) 
					showPopoverOverModal($(this), deleteReccurEventPopoverOpt);
				else 
					deleteAppoint(false,'', '', 'selectClientAction');
			}
		});
		/* end: Submit appointment cancel modal */
		/* start: Initialize selectable plugin over booking type */
		$("#bookTypeSelectable").selectable({
			selected: function() {
				setBookingType();
				clientLinkOrder = 0;
				classClients = {};
				clearClientForm(classModal.find('.client-form'), true);
				classModal.find('div#linkedclientList').html('');
				updateBookedclientQuickInfo();
				toggleClassClientLnks('create')
				clearNotific('serviceClientError');
				getStaffsAndAreas();
				//getResources();
			}
		});
		/* end: Initialize selectable plugin over booking type */
		$('input[name="autoExpireAppointDurDate"], input[name="autoExpireAppointDurTime"]').change(function() {
			var $this = $(this),
			row = $this.closest('.autoExpireAppointDurCustom'),
			secondField = row.find('input').not($this);
			if ($this.val() && secondField.val() != '') saveFieldPrompt('autoExpire');
		});
		/* end: Single service Section */
	});
	/* start: Get client event invoice*/
	function getClientEventInvoiceAmount(clientId, eventId, bookingType) {
		var amount = 0.00;
		var data = '';
		$.ajax({
			url: public_url + 'clients-event-invoice?clientId=' + clientId + '&eventId=' + eventId + '&eventType=' + bookingType,
			type: 'GET',
			data: {},
			async: false,
			success: function(response) {
				data = JSON.parse(response);
				if (data.status == "success") {
					amount = data.invoice.inv_total;
					amount = amount.toString();
				}
			}
		});
		return data.invoice;
	}
	/* end: Get client event invoice*/
	/* start: Get client event booking details */
	function getClientBookingDetails(clientId, eventId, bookingType) {
		var bookingDetails = {};
		$.ajax({
			url: public_url + 'client/booking-details?clientId=' + clientId + '&eventId=' + eventId + '&eventType=' + bookingType,
			type: 'GET',
			data: {},
			async: false,
			success: function(response) {
				data = JSON.parse(response);
				if (data.status == true) bookingDetails = data.bookingDetails;
			}
		});
		return bookingDetails;
	}
	/* end: Get client event booking details */
	/* start: Populating client details */
	function populateClientDetails(clientDetails, field, clientType) {
		var modal = field.closest('.modal')
		modalId = modal.attr('id');
		field.siblings('input[name="clientId"]').val(clientDetails.id);
		if (modalId == 'appointModal' || modalId == 'classModal') {
			modal.find('input[name="clientEmail"]').val(clientDetails.email);
			modal.find('input[name="clientNumb"]').intlTelInput("setNumber", clientDetails.phone.toString())
			if (modalId == 'classModal') {
				if (clientType != '' && clientType == 'new_client' && getBookingType() == 'class') {
					var eventId = classModal.find('input[name=eventId]').val();
					var membership = isClientMembershipSatisfy(clientDetails.id, eventId);
					var ldcSatisfy = isClientLdcSatisfy(clientDetails.id, eventId);
					if ('accStatus' in clientDetails) modal.find('input[name="accStatus"]').val(clientDetails.accStatus);
					else modal.find('input[name="accStatus"]').val('');
					if (membership) {
						toggleIf_make_up_sessionOption('hide', epicCash);
						classModal.find('.client-form input[name="isReducedRate"]').closest('.form-group').hide();
						classModal.find('.client-form input[name="ifRecur"]').closest('.form-group').show();
						classModal.find('.client-form input[name="ifRecur"]').attr('checked', false);
					} else if(ldcSatisfy){
						toggleIf_make_up_sessionOption('hide', epicCash);
						classModal.find('.client-form input[name="isReducedRate"]').closest('.form-group').hide();
						classModal.find('.client-form input[name="ifRecur"]').closest('.form-group').hide();
						classModal.find('.client-form input[name="ifRecur"]').attr('checked', false);
						classModal.find('.client-form input[name="isLdc"]').val(1);
					}else {
						if ('epicCash' in clientDetails && clientDetails.epicCash) var epicCash = clientDetails.epicCash;
						else var epicCash = '0.00';
						if (epicCash > 0) toggleIf_make_up_sessionOption('show', epicCash);
						else toggleIf_make_up_sessionOption('hide', epicCash);
						if ('accStatus' in clientDetails && clientDetails.accStatus != 'active') {
							if ('accStatus' in clientDetails && clientDetails.accStatus != 'active' && clientDetails.accStatus != 'Active' && clientDetails.accStatus != 'Contra' && clientDetails.accStatus != 'contra') {
								toggleRecurclientCb('hide');
							} else toggleRecurclientCb('show');
						}
						classModal.find('.client-form input[name="ifRecur"]').closest('.form-group').hide();
					}
				} else {
					var eventId = classModal.find('input[name=eventId]').val();
					var membership = isClientMembershipSatisfy(clientDetails.id, eventId);
					var ldcResponse = isClientLdcSatisfy(clientDetails.id, eventId);
					
					if ('epicCash' in clientDetails && clientDetails.epicCash) var epicCash = clientDetails.epicCash;
					else var epicCash = '0.00';
					if(membership == false){
						if(ldcResponse){
							toggleIf_make_up_sessionOption('hide', epicCash);
							modal.find('.client-form input[name="isReducedRate"]').closest('.form-group').hide();
							modal.find('.client-form input[name="ifRecur"]').closest('.form-group').hide();
							modal.find('.client-form input[name="ifRecur"]').attr('checked', false);
							modal.find('.client-form input[name="isLdc"]').val(1);
						}else if(epicCash > 0){ 
							toggleIf_make_up_sessionOption('show', epicCash);

						}else toggleIf_make_up_sessionOption('hide', epicCash);
					}
					
					if ('accStatus' in clientDetails) modal.find('input[name="accStatus"]').val(clientDetails.accStatus);
						else modal.find('input[name="accStatus"]').val('');
					if ('accStatus' in clientDetails && clientDetails.accStatus != 'active') {
						if ('accStatus' in clientDetails && clientDetails.accStatus != 'active' && clientDetails.accStatus != 'Active' && clientDetails.accStatus != 'Contra' && clientDetails.accStatus != 'contra') {
							toggleRecurclientCb('hide');
						} else toggleRecurclientCb('show');
					}
				}
			}
		}
	}
	/* end: Populating client details */
	/* start: Cliet membership clients */
	function isClientMembershipSatisfy(clientId, eventId) {
		var membershipSatisfy = false;
		$.ajax({
			url: public_url + 'client-membership?clientId=' + clientId + '&eventId=' + eventId + '&eventType=' + getBookingType(),
			type: 'GET',
			data: {},
			async: false,
			success: function(response) {
				var data = JSON.parse(response);
				if (data.success == true) membershipSatisfy = data.satisfy;
			}
		});
		return membershipSatisfy;
	}
	/* end: Cliet membership clients */

	/* Start: Client LDC class status */
	function isClientLdcSatisfy(clientId,eventId){
		var ldcSatisfy = false;
		$.ajax({
			url: public_url + 'client-ldc?clientId=' + clientId + '&eventId=' + eventId + '&eventType=' + getBookingType(),
			type: 'GET',
			data: {},
			async: false,
			success: function(response) {
				ldcSatisfy = response.isSatisfy;
			}
		});
		return ldcSatisfy;
	}
	/* End: Client LDC class status */
	/* start: Populating client details */
	function addSubviewClient(clientData) {
		var clientField = classModal.find('.clientList');
		clientField.val(clientData.name);
		populateClientDetails(clientData, clientField, '');
		var bookingType = getBookingType();
		if (bookingType == 'class') fetchClientsForTypeahead('update', 'book_team');
		else if (bookingType == 'service') {
			if (isModalModeEdit() && !linkedclientsCount()) {
				var forSalesProcessStep = eventObj.find('input[name="forSalesProcessStep"]').val();
				fetchClientsForTypeahead('update', forSalesProcessStep);
			} else fetchClientsForTypeahead('update');
		}
	}
	/* end: Populating client details */
	/* start: Toggle recurrence tab in appointment modal */
	function toggleRecurrTab(action) {
		/*var tab = appointModal.find('ul.nav a[href="#appointReccur"]');

		if(action == 'show')
			tab.show();
		else
			tab.hide();*/
		toggleTab(appointModal, 'appointReccur', action);
	}
	/* end: Toggle recurrence tab in appointment modal */
	function toggleTab(modal, tabId, action) {
		var tab = modal.find('ul.nav a[href="#' + tabId + '"]');
		if (action == 'show') tab.show();
		else tab.hide();
	}
	/* start: Toggle appointment status fields */
	function toggleAppointStatusFields(setStatus) {
		if (typeof setStatus != 'undefined') {
			var selectableOpts = $("#appointStatusSelectable li");
			selectableOpts.removeClass("ui-selected").show();
			selectableOpts.filter('[data-val="' + setStatus + '"]')[0].className += " ui-selected";
		}
		var status = $('#appointStatusSelectable li.ui-selected').data('val')
		classModal.find('input[name="appointStatusOpt"]').val(status)
		if (status == 'Pencilled-In') {
			$('#appointStatusPending').show();
			var cb = $('input[name="ifAutoExpireAppoint"]', classModal)
			cb.prop('checked', false)
			toggleAutoExpireFields(cb);
		} else $('#appointStatusPending').hide();
	}
	/* end: Toggle appointment status fields */
	function enableAppointStatuselectable() {
		$("#appointStatusSelectable").selectable("enable");
	}

	function disableAppointStatuselectable() {
		disableSelectable('#appointStatusSelectable');
	}

	function disableSelectable(selectableId) {
		$(selectableId + ' li:not(.ui-selected)').hide()
		$(selectableId).selectable("disable");
	}

	function disableBookTypeSelectable() {
		disableSelectable('#bookTypeSelectable');
	}

	function setGroupInit() {
		$('.set-group').each(function() {
			var setGroup = $(this),
			dd = setGroup.find('select'),
			setGroupDisp = setGroup.find('.set-group-disp'),
			lnk = setGroupDisp.find('a');
			if(eventModal == 'busyModal' && $(this).hasClass('staffDiv')){
				$(this).find('.bootstrap-select').removeClass('hidden');
				dd.removeClass('hidden');
			}else{
			 dd.addClass('hidden');
			}
			dd.prop('required', false);
			setGroupDisp.show();
			setGroupDisp.find('span').text(dd.find('option[value="' + dd.val() + '"]').text());
			if (pastCurrentMode) lnk.hide();
			else lnk.show();
		})
	}
	/* start: Switch tab of modal */
	function switchTabTo(tab, modal) {
		modal.find('ul.nav a[href="#' + tab + '"]').tab('show');
	}
	/* end: Switch tab of modal */
	/* start: Get active tab */
	function getActiveTab() {
		var tabId = classModal.find('ul.nav li.active a').attr('href');
		return tabId.substring(1);
	}
	/* end: Get active tab */
	/* start: Set/get event date in various modes */
	function setEventDate(modal, mode) {
		var eventDateDisp = modal.find('.eventDateDisp');
		if (mode == 'add') {
			var selectedDatetimeMoment = moment($('input[name="selectedDatetime"]').val());
			eventDateDisp.text(selectedDatetimeMoment.format("ddd, D MMM YYYY"));
		} else if (mode == 'edit') eventDateDisp.text(moment(eventObj.find('input[name="date"]').val()).format("ddd, D MMM YYYY"));
		else if (mode == 'submit') {
			var eventDateDisp = moment(eventDateDisp.text(), 'ddd, D MMM YYYY'),
			returnData = {};
			returnData['date'] = eventDateDisp.format("YYYY-MM-DD");
			returnData['day'] = eventDateDisp.format("dddd");
			return returnData;
		}
	}
	/* end: Set/get event date in various modes */
	/* start: Reset event recurrence data */
	function resetEventReccur(modal) {
		var module = modal.find('.event-reccur');
		module.find('select[name="eventRepeat"]').change();
		//module.find('input[name="eventRepeatEndOnDate"]').val(moment().format('D MMM YYYY'))
		setRepeatEndDate(modal)
		module.find('input[name="eventRepeatEnd"][value="On"]').prop('checked', true).trigger('change');
		setEventdayAsRepeatWeekDay(modal);
	}
	/* end: Reset event recurrence data */
	function setRepeatEndDate(modal, date) {
		var field = modal.find('input[name="eventRepeatEndOnDate"]'),
		eventDate = setEventDate(modal, 'submit'),
		minDateMoment = moment(eventDate.date),
		minDate = minDateMoment.format('D MMM YYYY');
		field.datepicker("option", "minDate", minDate);
		if (typeof date == 'undefined') {
			var prevVal = field.val();
			if (!prevVal || moment(prevVal).isBefore(minDateMoment)) {
				date = moment(minDate).format('D MMM YYYY');
				field.val(date)
			}
		} else {
			date = moment(date).format('D MMM YYYY');
			field.val(date)
		}
	}
	/* start: Set event day as default repeat weekend day */
	function setEventdayAsRepeatWeekDay(modal) {
		var eventDate = setEventDate(modal, 'submit'),
		eventDay = eventDate.day.slice(0, 3);
		modalId = modal.attr("id")
		if(modalId == "busyModal"){
			eventRepeatWeekdays = modal.find('.busyRepeatWeekdays input[type="checkbox"]');
		}else{
		eventRepeatWeekdays = modal.find('.eventRepeatWeekdays input[type="checkbox"]');
		}
		eventRepeatWeekdays.prop('checked', false)
		eventRepeatWeekdays.filter('[value="' + eventDay + '"]').prop('checked', true)
	}
	/* start: Set event day as default repeat weekend day */
	/* start: Populate event recurrence data */
	function populateEventReccur(modal) {
		resetEventReccur(modal);
		var module = modal.find('.event-reccur'),
		modalId = modal.attr('id');
		savedEventRepeat = eventObj.find('input[name="eventRepeat"]').val(),
		savedEventRepeatEnd = eventObj.find('input[name="eventRepeatEnd"]').val();
		// console.log(savedEventRepeat);
		if (savedEventRepeat != null) {
			
			module.find('select[name="eventRepeat"]').val(savedEventRepeat).change();
			if (savedEventRepeat == 'Daily' || savedEventRepeat == 'Weekly' || savedEventRepeat == 'Monthly') {
				module.find('select[name="eventRepeatInterval"]').val(eventObj.find('input[name="eventRepeatInterval"]').val())
				if(modalId == 'busyModal')
					module.find('input[name="busyRepeatEnd"][value="' + savedEventRepeatEnd + '"]').prop('checked', true).trigger('change');
				else
					module.find('input[name="eventRepeatEnd"][value="' + savedEventRepeatEnd + '"]').prop('checked', true).trigger('change');
				if (savedEventRepeatEnd == 'After'){ 
					module.find('select[name="eventRepeatEndAfterOccur"]').val(eventObj.find('input[name="eventRepeatEndAfterOccur"]').val())
				}else if (savedEventRepeatEnd == 'On'){
					if(modalId == 'busyModal')
					setRepeatEndDate(modal, eventObj.find('input[name="busyRepeatEndOnDate"]').val())
					else
					setRepeatEndDate(modal, eventObj.find('input[name="eventRepeatEndOnDate"]').val())
				}
				//module.find('input[name="eventRepeatEndOnDate"]').val(moment(eventObj.find('input[name="eventRepeatEndOnDate"]').val(), 'YYYY-MM-DD').format("D MMM YYYY"))
			if (savedEventRepeat == 'Weekly') {
				if(modalId == 'busyModal')
				eventRepeatWeekdays = module.find('.busyRepeatWeekdays input[type="checkbox"]');
				else
				eventRepeatWeekdays = module.find('.eventRepeatWeekdays input[type="checkbox"]');
				eventObj.find("input[name='eventRepeatWeekDays[]']").each(function() {
					eventRepeatWeekdays.filter('[value="' + $(this).val() + '"]').prop('checked', true)
				})
			}
		}
	}
	}
	/* end: Populate event recurrence data */
	/* start: Populate event history */
	function populateEventHistory(modal) {
		var historyContent = '',
		historyElem = modal.find('.event-history'),
		historyTab = modal.find('a[href="#' + historyElem.prop('id') + '"]'),
		count = 0;
		eventObj.find("input[name='history[]']").each(function() {
			count++;
			var history = JSON.parse($(this).val());
			historyContent += '<hr class="m-t-0 m-b-10"><div class="font-15"><span class="label ';
			if (history.type == 'new') historyContent += 'label-success">New!';
			else if (history.type == 'ammend') historyContent += 'label-warning">Amended';
			else if (eventModal == 'classModal') historyContent += 'label-info">Alert';
			historyContent += '</span> ' + dbDateToDateString(history.date) + ' ' + dbTimeToTimeString(history.time);
			if (history.type != 'alert') historyContent += ' - by ' + history.ownerName;
			historyContent += '</div>';
			var historyText = history.text.split('|');
			$.each(historyText, function(key, value) {
				historyContent += '<p class="m-t-10">' + value + '</p>';
			});
		})
		historyElem.html(historyContent);
		historyTab.find('span').html(count);
		historyTab.show();
	}
	/* end: Populate event history */
	/* start: Toggle modal elements as per datetime */
	function toggleEventModalUiByTimeMode(modal, mode) {
		var modalId = modal.attr('id');
		if (modalId == 'classModal') {
			var tabs = modal.find('.tab-pane:not("#classHist")'),
			saveBtn = modal.find('button.submitBooking'),
			delBtn = modal.find('.modal-footer a'),
			closeBtn = modal.find('button.btn[data-dismiss="modal"]'),
			nextBtn = modal.find('#nextTab');
		} else if (modalId == 'busyModal') {
			var tabs = modal.find('form div.col-md-12'),
			saveBtn = modal.find('.modal-footer button.submit'),
			delBtn = modal.find('.modal-footer .delete-prompt'),
			closeBtn = modal.find('.btn[data-dismiss="modal"]');
		}
		var closeBtnTxt = closeBtn.find('span');
		//cancelledBooking = false;
		clearNotific('pastEvent');
		clearNotific('cancelledEvent');
		clearNotific('notOwner');
		delBtn.hide();
		closeBtn.show();
		closeBtnTxt.text('Cancel')
		saveBtn.show().text('Done');
		if (modalId == 'classModal') {
			closeBtn.hide();
			nextBtn.show();
			toggleTab(classModal, 'classReccur', 'show');
			toggleTab(classModal, 'classClients', 'show');
			toggleTab(classModal, 'classNotes', 'show');
			toggleTab(classModal, 'classHist', 'show');
		}
		if (mode == 'cancel') {
			//cancelledBooking = true;
			showNotific(prepareAlert('warning', "This event has been cancelled. You can not update any details", 'cancelledEvent'), tabs);
		} else if (pastCurrentMode) {
			if (modalId == 'classModal') {
				var bookingType = getBookingType()
				if (bookingType == 'class') {
					var message = "This event is in past. You can not update any details unless it is client related.";
					if (!isUserType(['Staff']) || (isUserType(['Staff']) && eventObj.find('input[name="userId"]').val() == loggedInUser.userId)) {
						delBtn.filter('.delete-prompt').show();
						disabledEnabledCancleClsBtn();
					}
				} else if (bookingType == 'service') {
					var message = "This event is in past. You can not update any details unless it is time related or client's attendance.";
					if (isSalesProcessEvent()) updateUiForSalesProcess();
				}
			} else {
				var message = "This event is in past. You can not update any details";
				closeBtnTxt.text('Close')
				saveBtn.hide();
			}
			showNotific(prepareAlert('warning', message, 'pastEvent'), tabs);
		} else if (mode == 'edit') {
			if (isUserType(['Staff']) && eventObj.find('input[name="userId"]').val() != loggedInUser.userId) {
				showNotific(prepareAlert('warning', "This event is not created by you. You can not update any details", 'notOwner'), tabs);
				if (modalId == 'busyModal') saveBtn.hide();
			} else {
				if (modalId == 'classModal') {
					var bookingType = getBookingType();
					if (bookingType == 'class') {
						delBtn.filter('.delete-prompt').show();
						disabledEnabledCancleClsBtn();
						if (isClassFresh) {
							switchTabTo('classReccur', classModal);
							isClassFresh = false;
						}
					} else if (bookingType == 'service') {
						if (calendarSettings.cs_allow_appointments == 1)
							//delBtn.filter('[data-target="#appointCancelModal"]').show();
						delBtn.filter('#delService').show();
						if (isSalesProcessEvent()) {
							updateUiForSalesProcess();
						} else if (isServiceFresh()) {
							toggleTab(classModal, 'classReccur', 'hide');
							toggleTab(classModal, 'classNotes', 'hide');
							switchTabTo('classClients', classModal);
						} else {
							var isClientMakeup = eventObj.find('input[name="isClientMakeup"]').val();
							if (isClientMakeup == 1) toggleTab(classModal, 'classReccur', 'hide');
						}
					}
				} else {
					delBtn.show();
					saveBtn.text('Save');
				}
			}
		} else if (mode == 'add') {
			closeBtn.show();
			if (modalId == 'classModal') {
				saveBtn.text('Next');
				nextBtn.hide();
				var bookingType = getBookingType();
				if (bookingType == 'class') {
					toggleTab(classModal, 'classClients', 'hide');
				} else if (bookingType == 'service') {
					toggleTab(classModal, 'classReccur', 'hide');
					if (isSalesProcessMode()) {
						toggleTab(classModal, 'classClients', 'hide');
						saveBtn.text('Save');
					}
				}
				//toggleTab(classModal, 'classReccur', 'hide');
				//toggleTab(classModal, 'classClients', 'hide');
				toggleTab(classModal, 'classNotes', 'hide');
				toggleTab(classModal, 'classHist', 'hide');
			} else saveBtn.text('Save');
		}
	}
	/* end: Toggle modal elements as per the mode it is in */
	/* start: Check if given datetime is less than or equal to current datetime */
	function isSameOrBefore(dateTime) {
		var now = moment();
		if (moment(dateTime, 'YYYY-MM-DD h:mm A').isSameOrBefore(now)) return true;
		else return false;
	}
	/* end: Check if given datetime is less than or equal to current datetime */
	/* start: Turn flag on given datetime is less than or equal to current datetime */
	function setPastCurrentModeFlag(datetime) {
		if (isSameOrBefore(datetime)) pastCurrentMode = true;
		else pastCurrentMode = false;
	}
	/* start: Turn flag on given datetime is less than or equal to current datetime */
	/* start: Prepare html for alert messages */
	function prepareAlert(type, message, alertGroup) {
		if (typeof alertGroup == 'undefined') alertGroup = '';
		return '<div class="alert alert-' + getAlertsColor(type) + ' ' + alertGroup + '">' + message + '</div>';
	}
	/* end: Prepare html for alert messages */
	/* start: Submit appointment modal data */
	function submitAppointmentModal(form, clientForm) {
		if (form == null) form = appointModal.find('form');
		if (clientForm == null) clientForm = appointModal.find('.client-form');
		var formData = {};
		form.find(':input').each(function() {
			formData[$(this).attr('name')] = $(this).val()
		})
		if (isSalesProcessMode()) formData['salesProcessStepNumb'] = parent.salesProcessStepNumb;
		var eventDate = setEventDate(appointModal, 'submit');
		formData['date'] = eventDate.date;
		formData['day'] = eventDate.day;
		formData['ifAutoExpireAppoint'] = form.find("input[name='ifAutoExpireAppoint']:checked").val()
		if (formData['ifAutoExpireAppoint'] == 1) {
			if (formData['autoExpireAppointDur'] == 'Custom') formData['autoExpireDatetime'] = moment(formData['autoExpireAppointDurDate'] + ' ' + formData['autoExpireAppointDurTime']).format("YYYY-MM-DD H:mm:ss");
			else formData['autoExpireDatetime'] = moment().add(formData['autoExpireAppointDur'], 'hours').format("YYYY-MM-DD H:mm:ss")
		}
	formData['isNewClient'] = form.find("input[name='isNewClient']:checked").val();
	formData['clientNumb'] = getClientFullNumb(clientForm);
	if (formData['eventRepeat'] == 'Daily' || formData['eventRepeat'] == 'Weekly' || formData['eventRepeat'] == 'Monthly') {
		formData['eventRepeatEnd'] = form.find('input[name="eventRepeatEnd"]:checked').val();
		if (formData['eventRepeatEnd'] == 'On') formData['eventRepeatEndOnDate'] = moment(formData['eventRepeatEndOnDate'], 'D MMM YYYY').format("YYYY-MM-DD");
		else if (formData['eventRepeatEnd'] == 'Never') formData['calendEndDate'] = getCalendEndDate();
		if (formData['eventRepeat'] == 'Weekly') {
			var temp = [],
			eventRepeatWeekdays = form.find('.eventRepeatWeekdays input[type="checkbox"]:checked');
			if (eventRepeatWeekdays.length) {
				eventRepeatWeekdays.each(function() {
					temp.push($(this).val());
				})
				formData['eventRepeatWeekdays'] = temp;
			}
		}
	}
	formData['historyText'] = '';
	if (formData['eventId'] != null && formData['eventId'] != '') {
		if (eventObj.find('input[name="areaId"]').val() != formData['modalLocArea']) formData['historyText'] += 'Area changed from ' + eventObj.find('input[name="locAreaName"]').val() + ' to ' + appointModal.find('select[name="modalLocArea"] option:selected').text() + '|';
		if (eventObj.find('input[name="staffId"]').val() != formData['staff']) formData['historyText'] += 'Staff changed from ' + eventObj.find('input[name="staffName"]').val() + ' to ' + appointModal.find('select[name="staff"] option:selected').text() + '|';
		var oldAppointdate = eventObj.find('input[name="date"]').val(),
		oldAppointTime = eventObj.find('input[name="startTime"]').val();
		if (oldAppointdate != formData['date'] || oldAppointTime != timeStringToDbTime(formData['serviceTime0'])) formData['historyText'] += 'Reschedule from ' + dbDateToDateString(oldAppointdate) + ' ' + dbTimeToTimeString(oldAppointTime) + ' to ' + appointModal.find('.eventDateDisp').text() + ' ' + formData['serviceTime0'] + '|';
		if (formData['isNewClient']) formData['historyText'] += 'Client changed from ' + eventObj.find('input[name="clientName"]').val() + ' to ' + formData['clientName'] + '|';
		else if (eventObj.find('input[name="clientId"]').val() != formData['clientId']) formData['historyText'] += 'Client changed from ' + eventObj.find('input[name="clientName"]').val() + ' to ' + clientForm.find('.clientList').val() + '|';
		oldAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val();
		if (oldAppointStatusOpt != formData['appointStatusOpt']) {
			formData['historyText'] += 'Booking status changed from ' + oldAppointStatusOpt + ' to ' + formData['appointStatusOpt'] + '|';
			if (formData['appointStatusOpt'] == 'Confirmed' && formData['appointStatusConfirm'] != 'Not started') formData['historyText'] += 'Confirmation status changed from Not started to ' + formData['appointStatusConfirm'] + '|';
		} else {
			oldAppointStatusConfirm = eventObj.find('input[name="appointStatusConfirm"]').val();
			if (oldAppointStatusOpt == 'Confirmed' && formData['appointStatusOpt'] == 'Confirmed' && oldAppointStatusConfirm != formData['appointStatusConfirm']) formData['historyText'] += 'Confirmation status changed from ' + oldAppointStatusConfirm + ' to ' + formData['appointStatusConfirm'] + '|';
		}
		var allServices = appointModal.find('select.serviceName');
		if (eventServices.length) {
			var i = 0;
			$.each(eventServices, function(key, value) {
				var service = allServices.filter("[data-unique-id='" + i + "']");
				if (service.length) {
					var serviceRow = service.closest('.row'),
					time = serviceRow.find('input.timepicker1'),
					duration = serviceRow.find('select.serviceDur'),
					price = serviceRow.find('input.servicePrice');
					if (this.id != service.val()) formData['historyText'] += 'Service' + (i + 1) + ' - product changed from ' + this.name + ' to ' + service.find('option:selected').text() + '|';
					if (this.time != timeStringToDbTime(time.val())) formData['historyText'] += 'Service' + (i + 1) + ' - service date changed from ' + appointModal.find('.eventDateDisp').text() + ' ' + dbTimeToTimeString(this.time) + ' to ' + appointModal.find('.eventDateDisp').text() + ' ' + time.val() + '|';
					if (this.duration != duration.val()) formData['historyText'] += 'Service' + (i + 1) + ' - service length changed from ' + this.duration + ' minutes to ' + duration.val() + ' minutes|';
					if (this.price != price.val()) formData['historyText'] += 'Service' + (i + 1) + ' - price changed from $' + this.price + ' to $' + price.val() + '|';
				} else formData['historyText'] += this.name + ' was removed from the service group|';
				i++;
			});
		}
		allServices.filter(':not([data-unique-id])').each(function() {
			formData['historyText'] += $(this).find('option:selected').text() + ' was added to the service group|';
		});
		ajaxAction = public_url + 'staffevents/appointments/edit';
	} else ajaxAction = public_url + 'staffevents/appointments/create';
	$.post(ajaxAction, formData, function(data) {
		var data = JSON.parse(data);
		if (data.status == "added") {
			if (formData['isNewClient']) data.newClient = true;
			else data.newClient = false;
			submitAppointmentModalSuccess(data)
		} else if (data.status == "error") {
			$.each(data.errorData, function(key, val) {
				$.each(this, function(errorType, message) {
					if (errorType == "emailExist") var field = $(form).find("input[name='clientEmail']");
					else if (errorType == "workingHourUnavail") var field = $(form).find("span.eventDateDisp");
					setFieldInvalid(field.closest('.form-group'), message)
				});
			});
			switchToErrorTab(form);
		}
	});
	}
	/* end:Submit appointment modal data */

	/* start:Submit appointment cancel modal data */
	function deleteAppoint(ifForceDelete, targetEvents, cb, source = '') {
		var formData = {};

		var bookingType = getBookingType();

		formData['eventId'] = eventObj.find('input[name="eventId"]').val();
		formData['clientId'] = classModal.find('input[name="clientId"]').val();
		
		if (!ifForceDelete) 
			formData['cancelReas'] = appointCancelModal.find('select[name="cancelReas"]').val();

		if (typeof targetEvents != 'undefined' && targetEvents != null) 
			formData['targetEvents'] = targetEvents;
		var invFlag = eventObj.find('.calendEvent').find('[name="isInvoice"]').val();
		
		if (typeof invFlag != 'undefined' && invFlag != '') 
			invFlag = invFlag.toString();
		else 
			invFlag = $('a.openClassModal').find('[name="isInvoice"]').val();
		if (invFlag == 1) 
			var isInvDel = true;
		else 
			var isInvDel = false;

		if(source == 'selectClientAction') {
			var eventInvoice = getClientEventInvoiceAmount(formData['clientId'], formData['eventId'], bookingType);
			var clientBookingDetails = getClientBookingDetails(formData['clientId'], formData['eventId'], bookingType);

			if (clientBookingDetails != [] && clientBookingDetails != null && clientBookingDetails != undefined) {

				if (clientBookingDetails.sess_with_invoice == 1 && clientBookingDetails.sess_epic_credit == 0 && eventInvoice != [] && eventInvoice != null && eventInvoice != undefined && eventInvoice.inv_status == 'Unpaid') {

					invoiceDelConfirm(isInvDel, function(isConfirm) {
						if (isConfirm) 
							formData['invoice_delete'] = 'yes_delete';
		
							deleteAppointAjax(formData, ifForceDelete);
					});

				} else {

					if (clientBookingDetails.sess_with_invoice == 0 && clientBookingDetails.sess_epic_credit == 0){
						if(clientBookingDetails.is_ldc == 1){
							action = false;
							message = 'Are you Sure to delete Ldc Service?';
							formData['isLdc'] = 1;
						}
						else
							message = "Do you want to return membership?";
					}
					else if (clientBookingDetails.sess_with_invoice == 1 && clientBookingDetails.sess_epic_credit == 1) 
						message = "Do you want to refund invoice amount and credit used?";
					else if (clientBookingDetails.sess_with_invoice == 1 && clientBookingDetails.sess_epic_credit == 0) 
						message = "Do you want to refund invoice amount?";
					else if (clientBookingDetails.sess_epic_credit == 1 && clientBookingDetails.sess_with_invoice == 0) 
						message = "Do you want to refund credit used?";

					removeClientActionCormfirmation(true, message, function(isConfirm) {
						if (isConfirm) 
							formData['refund_resource'] = 'yes';
						else 
							formData['refund_resource'] = 'no';

						deleteAppointAjax(formData, ifForceDelete);
					});
				}
			} else {
				swal({
					title: "Service is already cancelled.",
					allowOutsideClick: false,
					showCancelButton: false,
					confirmButtonText: 'Okay',
					confirmButtonColor: '#ff4401',
					type: 'warning',
				}, function(isConfirm) {
					if (isConfirm) {
						reloadEvents = true;
					}
				});
			}
		} else {
			deleteAppointAjax(formData, ifForceDelete);
		}
	}
	/* end:Submit appointment cancel modal data */

	/* start:Submit ajax appointment cancel modal data */
	function deleteAppointAjax(formData, ifForceDelete) {
		toggleWaitShield('show');
		$.post(public_url + 'staffevents/single-service/delete', formData, function(data) {

			var data = JSON.parse(data);
			$('#raiseMakeUpModelSubmit').prop('disabled', false);
			toggleWaitShield('hide');
			if (data.status == "deleted") {
				// location.reload(true);
				if (typeof cb == 'undefined') 
					submitCancelAppointmentModalSuccess(ifForceDelete, data);
				else 
					cb();
			} else if (data.status == 'error' && data.unsatisyReas == 'client_not_found') {
				swal({
					title: "Service is already cancelled.",
					allowOutsideClick: false,
					showCancelButton: false,
					confirmButtonText: 'Okay',
					confirmButtonColor: '#ff4401',
					type: 'warning',
				}, function(isConfirm) {
					if (isConfirm) {
						reloadEvents = true;
						appointCancelModal.modal('hide');
						classModal.modal('hide');
					}
				});
			}
		});
	}
	/* end:Submit ajax appointment cancel modal data */

	function addcreditDeleteAppoint(clientId) {
		var formData = {};
		formData['eventId'] = classModal.find('input[name="eventId"]').val();
		formData['clientIfMakeUp'] = classClients[clientId].ifMakeUp;
		formData['makeupNotes'] = classClients[clientId].makeupNotes;
		formData['purpose'] = classClients[clientId].purpose;
		formData['amount'] = classClients[clientId].amount;
		$.post(public_url + 'staffevents/single-service/delete', formData, function(data) {
			var data = JSON.parse(data);
			$('#raiseMakeUpModelSubmit').prop('disabled',false);
			if (data.status == "deleted") {
				reloadEvents = true;
				$("#raiseMakeUpModel").modal('hide');
				classModal.modal('hide');
			} else if (data.status == 'error' && data.unsatisyReas == 'client_not_found') {
				swal({
					title: "Service is already cancelled.",
					allowOutsideClick: false,
					showCancelButton: false,
					confirmButtonText: 'Okay',
					confirmButtonColor: '#ff4401',
					type: 'warning',
				}, function(isConfirm) {
					if (isConfirm) {
						reloadEvents = true;
						$("#raiseMakeUpModel").modal('hide');
						classModal.modal('hide');
					}
				});
			}
		});
	}
	/* end:Submit appointment cancel modal data */
	/* start: show popover over modal */
	function showPopoverOverModal(elem, popoverOpt) {
		elem.popover(popoverOpt).popover('show').data('bs.popover').tip().addClass('superior');
		calPopupHelper.removeClass('hidden').addClass('superior');
		shownPopover.push(elem)
	}
	/* end: show popover over modal */
	/* start: show popover over modal */
	function hidePopoverOverModal(elem, popoverOpt) {
		elem.popover(popoverOpt).popover('hide').data('bs.popover').tip().addClass('superior');
		calPopupHelper.addClass('hidden').addClass('superior');
		shownPopover.pop(elem)
	}
	/* end: show popover over modal */
	/* start: Validate client form */
	function validateClientForm(clientForm, ifCallValidation) {
		if (ifCallValidation) var isFormValid = clientForm.find(':input').valid();
		else var isFormValid = true;
		if (!validateTypeaheadClient(clientForm)) isFormValid = false;
		return isFormValid;
	}
	/* end: Validate client form */
	/* start: Validate typeahead client */
	function validateTypeaheadClient(clientForm) {
		var clientTypeaheadField = clientForm.find('.clientList'),
		clientIdField = clientForm.find("input[name='clientId']"),
		clientId = clientIdField.val(),
		formGroup = clientIdField.closest('.form-group');
		clientTypeaheadField.prop('required', true);
		if (!clientTypeaheadField.valid()) {
			clientTypeaheadField.prop('required', false);
			return false;
		}
		clientTypeaheadField.prop('required', false);
		if (clientTypeaheadField.val() != '' && (clientId == null || clientId == '' || clientId == 0)) {
			setFieldInvalid(formGroup, 'Please select a valid client.', clientIdField.prev())
			return false;
		}
		return true;
	}
	/* end: Validate typeahead client */
	/* start: Get client number along with country code */
	function getClientFullNumb(clientForm) {
		var phoneNumbField = clientForm.find('input[name="clientNumb"]');
		if (phoneNumbField.val() != '') {
			var selCountry = phoneNumbField.intlTelInput("getSelectedCountryData");
			if (Object.keys(selCountry).length) return phoneNumbField.intlTelInput("getNumber");
			else return phoneNumbField.val();
		}
		return '';
	}
	/* end: Get client number along with country code */
	/* start: Populate staffs based on the area */
	function getAreaStaffs(areaDd, defaultStaff) {
		if (isUserType(['Staff'])) {
			if (areaDd.hasClass('toolBarDd')) {
				staffFilterCalend.selectpicker('hide');
				staffFilterCal(loggedInUser.id);
			} else {
				var dd = areaDd.closest('form').find('select[name="staff"]');
				dd.html('<option value="' + loggedInUser.id + '">' + loggedInUser.name + '</option>').selectpicker('refresh');
				var setGroupDisp = dd.closest('.set-group').find('.set-group-disp');
				setGroupDisp.find('span').text(loggedInUser.name);
				setGroupDisp.find('a').hide();
				if (typeof defaultStaff == 'undefined') defaultServices = false;
				else defaultServices = true;
				if (eventModal == 'appointModal') getStaffServices(dd, defaultServices);
				else if (eventModal == 'classModal') getStaffClasses(dd, defaultServices);
			}
		} else if (isUserType(['Admin'])) {
			var formData = {};
			if (areaDd.hasClass('toolBarDd')) {
				formData['areaId'] = findAreaId();
			} else {
				var isAreaMarkedAsDeleted = isFieldMarkedAsDeleted(areaDd);
				if (isAreaMarkedAsDeleted) formData['areaId'] = isAreaMarkedAsDeleted;
				else formData['areaId'] = areaDd.val();
				var staffId = findStaffId();
				if (staffId == 'all-ros') {
					formData['startDate'] = getCalendStartDate();
					if (ifDayView()) formData['day'] = getCalendStartDay();
					else formData['endDate'] = getCalendEndDate();
				}
			}
			if (formData['areaId'] == null) {
				//parent.reloadPageWithAppointmentTab();
				formData['areaId'] = 'all';
			}
			$.get(public_url + 'areas/staffs', formData, function(data) {
				if (areaDd.hasClass('toolBarDd')) {
					var dd = staffFilterCalend,
					options = '<option value="all-ros">All rostered staff</option><option value="all" selected>All staff</option>';
					if (!$.isEmptyObject(data)) {
						$.each(data, function(key, value) {
							options += '<option value="' + key + '">' + value + '</option>';
						});
					}
					dd.html(options);
					if (typeof defaultStaff != 'undefined') dd.val(defaultStaff);
					dd.selectpicker('refresh');
					staffFilterCalend.trigger('change');
				} else {
					var dd = areaDd.closest('form').find('select[name="staff"]'),
					setGroupDisp = dd.closest('.set-group').find('.set-group-disp'),
					setGroupDispSpan = setGroupDisp.find('span');
					if (eventModal == 'busyModal') {
						var firstVal = '';
						var ids= [];
						$.each(data, function(key, value) {
							options += '<option value="' + key + '">' + value + '</option>';
							ids.push(parseInt(key, 10));
						});
						dd.html(options);
						var span = '';
						if(eventObj.length){
							
							var selectedStaff = [];
							eventObj.find("input[name='staff[]']").each(function() {
								var data = JSON.parse($(this).val());
								ifStaffExist = $.inArray(data.id, ids);
								if (ifStaffExist < 0 && dd.hasClass('hidden')) {
									//area list does not have selected area
									toggleAvailableMarkOnField(dd, 0);
									if (data.isStaffDeleted == true) {
										//Area has been deleted
										span += '<span class="text-danger">' + data.staffName + ' has been deleted</span><br>';
									} else span += '<span class="text-danger">' + data.staffName + ' is not available</span><br>';
								} else if (ifStaffExist >= 0) {
									selectedStaff.push(data.id);
									span += '<span>' + data.staffName + '</span><br>';
								}
							});
							span = span.slice(0, -4);
							var staffDdBusy = busyModal.find('select[name="staff"]');
							staffDdBusy.val(selectedStaff)
							staffSetGroupDisp = staffDdBusy.closest('.set-group').find('.set-group-disp');
							staffSetGroupDisp.find('span').remove()
							staffSetGroupDisp.find('br').remove()
							staffSetGroupDisp.prepend(span)
						}else{
							var firstOption = dd.find('option:first').val();
							
							dd.val(firstOption)
							span += '<span>' + dd.find('option:selected').text() + '</span><br>';
							if (span) span = span.slice(0, -4)
								else span = '<span class="text-danger">No staff found</span>';
							staffSetGroupDisp = dd.closest('.set-group').find('.set-group-disp');
							staffSetGroupDisp.find('span').remove()
							staffSetGroupDisp.find('br').remove()
							staffSetGroupDisp.prepend(span);	
						}
						dd.selectpicker('refresh');
						toggleDeletedMarkOnField(dd, defaultStaff)
						//setGroupDisp.find('a').hide();
					} else {
						if (typeof defaultStaff == 'undefined') {
							defaultServices = false;
							var staffId = findStaffId(),
							areaId = findAreaId();
							if (areaId && areaId != 'all' && staffId && staffId != 'all' && staffId != 'all-ros') defaultStaff = staffId;
						} else defaultServices = true;
						var options = '',
						newStaffs = [];
						if ( /*(eventModal == 'classModal' || eventModal == 'busyModal') &&*/ eventObj.length && isAreaMarkedAsDeleted) {
							var firstVal = eventObj.find('input[name="staffName"]').val();
							options += '<option value="' + defaultStaff + '">' + firstVal + '</option>';
						} else if (!$.isEmptyObject(data)) {
							var firstVal = '';
							$.each(data, function(key, value) {
								options += '<option value="' + key + '">' + value + '</option>';
								newStaffs.push(parseInt(key, 10));
								if (!firstVal) {
									if (typeof defaultStaff != 'undefined' && defaultStaff == key) firstVal = value;
									else if (typeof defaultStaff == 'undefined') firstVal = value;
								}
							});
						}
						//dd.html(options);
						if ( /*(eventModal == 'classModal' || eventModal == 'busyModal') &&*/ dd.data('new-instance') != 'true') {
							var
								/*selectedStaffObj = {},
								isCompatible = true,*/
								isStaffMarkedAsDeleted = isFieldMarkedAsDeleted(dd)
							/*,
							classDd = classModal.find('select[name="staffClass"]')*/
							;
							setFieldNeutral(dd);
							if (isAreaMarkedAsDeleted || isStaffMarkedAsDeleted) {
								dd.html(options);
								toggleDeletedMarkOnField(dd, 0)
								setGroupDispSpan.text(firstVal).removeClass('text-danger');
								dd.selectpicker('refresh');
								if (eventModal == 'appointModal') {
									dd.data('services-new-instance', 'true');
									getStaffServices(dd, defaultServices);
								} else if (eventModal == 'classModal') {
									classModal.find('select[name="staffClass"]').data('new-instance', 'true')
									getStaffClasses(dd, defaultServices);
								}
							} else {
								var selectedStaffObj = {},
								isCompatible = true,
									/*if(isStaffMarkedAsDeleted)
										var selectedStaff = parseInt(isStaffMarkedAsDeleted, 10);
										else*/
											/*var*/
										selectedStaff = parseInt(dd.val(), 10);
										if (selectedStaff) {
									/*if(isStaffMarkedAsDeleted)
										selectedStaffObj = {name:eventObj.find('input[name="staffName"]').val()};
										else*/
											selectedStaffObj = {
												name: dd.find('option:selected').text()
											};
											if ($.inArray(selectedStaff, newStaffs) == -1) isCompatible = false;
											else isCompatible = true;
											if (!isCompatible) {
												staffText = '<li class="list-group-item">' + selectedStaffObj.name + '</li>'
												swal({
													title: "This area doesn't provide following staffs:",
													text: staffText,
													type: staffChangeSwalOpt.type,
													showCancelButton: staffChangeSwalOpt.showCancelButton,
													confirmButtonColor: staffChangeSwalOpt.confirmButtonColor,
													confirmButtonText: staffChangeSwalOpt.confirmButtonText,
													cancelButtonText: 'Revert area',
													allowEscapeKey: staffChangeSwalOpt.allowEscapeKey,
													html: staffChangeSwalOpt.html,
													customClass: staffChangeSwalOpt.customClass
												}, function(isConfirm) {
													if (!isConfirm) {
												//alert('revert area')
												areaDd.val(areaDd.data('last-val')).selectpicker('refresh');
											} else {
												//alert('change staff')
												//areaDd.data('last-val', areaDd.val())
												if (eventModal == 'classModal' || eventModal == 'appointModal') staffDdTempOpts = {
													html: dd.html(),
													lastSelectedId: selectedStaff,
													lastSelectedText: selectedStaffObj.name
												};
												dd.html(options);
												toggleDeletedMarkOnField(dd, 0)
												setGroupDispSpan.text(firstVal).removeClass('text-danger');
												dd.selectpicker('refresh');
												//classDd.data('new-instance', 'true')
												if (eventModal == 'appointModal') getStaffServices(dd, defaultServices);
												else if (eventModal == 'classModal') getStaffClasses(dd, defaultServices);
											}
										});
											} else {
												if (eventModal == 'classModal' || eventModal == 'appointModal') staffDdTempOpts = {
													html: dd.html(),
													lastSelectedId: selectedStaff,
													lastSelectedText: selectedStaffObj.name
												};
												dd.html(options).val(selectedStaff);
												toggleDeletedMarkOnField(dd, 0)
												setGroupDispSpan.text(selectedStaffObj.name).removeClass('text-danger');
												dd.selectpicker('refresh');
										//classDd.data('new-instance', 'true')
										if (eventModal == 'appointModal') getStaffServices(dd, defaultServices);
										else if (eventModal == 'classModal') getStaffClasses(dd, defaultServices);
									}
								} else {
									/*areaDd.data('last-val', areaDd.val())
									dd.html(options);
									toggleDeletedMarkOnField(dd, 0)
									setGroupDispSpan.text(selectedStaffObj.name).removeClass('text-danger');
									dd.selectpicker('refresh');*/
								}
							}
						} else {
							dd.html(options);
							if ((eventModal == 'appointModal' || eventModal == 'classModal') && eventObj.length && eventObj.find('input[name="isStaffDeleted"]').val() == 'true') {
								setGroupDispSpan.text('This staff has been deleted').addClass('text-danger');
								toggleDeletedMarkOnField(dd, defaultStaff)
							} else {
								toggleDeletedMarkOnField(dd, 0)
								if (typeof defaultStaff != 'undefined') dd.val(defaultStaff);
								setGroupDispSpan.text(firstVal).removeClass('text-danger');
							}
							dd.selectpicker('refresh');
							if (eventModal == 'appointModal') getStaffServices(dd, defaultServices);
							else if (eventModal == 'classModal') getStaffClasses(dd, defaultServices);
						}
					}
					dd.data('new-instance', 'false')
				}
			}, 'json');
	}
	}
	/* end: Populate staffs based on the area */
	/* start: Get classes list */
	function getClasses(defaultClass, callback) {
		//if(isUserType(['Admin'])){
			var classDd = classModal.find('select[name="staffClass"]');
			if (typeof defaultClass == 'undefined' || !defaultClass) defaultClass = 0;
			else defaultClass = parseInt(defaultClass, 10);
			if (defaultClass && eventObj.find('input[name="isClassDeleted"]').val() == 'true') {
			//Class has been deleted
			toggleField_DelMsg({
				kase: 'trashed',
				field: classDd,
				entity: eventObj.find('input[name="className"]').val()
			});
			toggleAvailableMarkOnField(classDd, 0);
			classDd.html('').selectpicker('refresh');
			populateClassRelatedFields();
			//getStaffsAndAreas('class')
			if (typeof callback != 'undefined') callback('class');
		} else {
			$.get(public_url + 'settings/business/classes/all', function(data) {
				var options = '<option value="">-- Select --</option>';
				if (!$.isEmptyObject(data)) {
					$.each(data, function(key, value) {
						options += '<option value="' + value.id + '" data-duration="' + value.duration + '" data-capacity="' + value.capacity + '" data-price="' + value.price + '">' + value.name + '</option>';
					});
				}
				classDd.html(options);
				if (defaultClass) {
					//edit case
					classDd.val(defaultClass);
					populateClassRelatedFields();
				}
				toggleAvailableMarkOnField(classDd, 1);
				toggleField_DelMsg({
					kase: 'exist',
					field: classDd
				});
				classDd.selectpicker('refresh');
				//getStaffsAndAreas('class')
				if (typeof callback != 'undefined') callback('class');
			}, 'json');
		}
		//}
	}
	/* end: Get classes list */
	/* start: Get services list */
	function getServices(defaultService, callback) {
		//if(isUserType(['Admin'])){
			var serviceDd = classModal.find('select[name="staffservice"]');
			if (typeof defaultService == 'undefined' || !defaultService) defaultService = 0;
			else defaultService = parseInt(defaultService, 10);
			if (defaultService && eventObj.find('input[name="isServiceDeleted"]').val() == 'true') {
			//Service has been deleted
			toggleField_DelMsg({
				kase: 'trashed',
				field: serviceDd,
				entity: eventObj.find('input[name="serviceName"]').val()
			});
			toggleAvailableMarkOnField(serviceDd, 0);
			serviceDd.html('').selectpicker('refresh');
			populateServiceRelatedFields2();
			if (typeof callback != 'undefined') callback('service');
		}
		/*else if(defaultService && isSalesProcessEvent()){
			//Event belongs to sales process
			populateServices([{"id":eventObj.find('input[name="serviceId"]').val(), "name":eventObj.find('input[name="serviceName"]').val(), "duration":eventObj.find('input[name="duration"]').val(), "price":eventObj.find('input[name="price"]').val()}], defaultService, callback);
		}*/
		else {
			if (isSalesProcessMode()) var ajaxAction = 'sales-process/' + parent.salesProcessStepNumb;
			else if (isCreateServiceMakeUpMode()) var ajaxAction = 'single-service/' + parent.makeUpEventId;
			else if (defaultService && isSalesProcessEvent()) var ajaxAction = 'sales-process/' + eventObj.find('input[name="salesProcessEvent"]').val();
			else if (isModalModeEdit() && linkedclientsCount()) {
				var clientId = Object.keys(classClients)[0];
				var ajaxAction = 'client/' + clientId + '?bookedService=' + eventObj.find('input[name="serviceId"]').val();
			} else var ajaxAction = 'all';
			$.get(public_url + 'settings/business/services/' + ajaxAction, function(data) {
				populateServices(data, defaultService, callback);
			}, 'json');
		}
		//}
	}
	/* end: Get services list */
	/* start: Populate options in service dropdown */
	function populateServices(data, defaultService, callback) {
		var options = '<option value="">-- Select --</option>';
		if (!$.isEmptyObject(data)) {
			$.each(data, function(key, value) {
				options += '<option value="' + value.id + '" data-duration="' + value.duration + '" data-price="' + value.price + '" data-for-sales-step="' + value.forSalesProcessStep + '">' + value.name + '</option>';
			});
		}
		//var serviceDd = classModal.find('select[name="staffservice"]');
		var serviceDd = classModal.find('select[name="staffservice"]');
		serviceDd.html(options);
		if (isSalesProcessMode() || isCreateServiceMakeUpMode()) {
			var firstOpt = serviceDd.find('option[value!=""]:first');
			serviceDd.val(firstOpt.val());
			populateServiceRelatedFields2({
				duration: firstOpt.data('duration'),
				price: firstOpt.data('price')
			});
			var forSalesProcessStep = firstOpt.data('for-sales-step');
			if (forSalesProcessStep == 2) {
				/*toggleAppointStatusFields(calendarSettings.cs_initial_status_consultation);
				checkedPencilledinCheckbox();*/
				toggleAppointStatusFields(calendarSettings.cs_initial_status_consultation);
				if (calendarSettings.cs_initial_status_consultation == 'Pencilled-In') {
					checkedPencilledinCheckbox();
				}
			} else if (forSalesProcessStep == 4) {
				/*toggleAppointStatusFields(calendarSettings.cs_initial_status_benchmarking);
				checkedPencilledinCheckbox();*/
				toggleAppointStatusFields(calendarSettings.cs_initial_status_benchmarking);
				if (calendarSettings.cs_initial_status_benchmarking == 'Pencilled-In') {
					checkedPencilledinCheckbox();
				}
			}
		} else if (defaultService) {
			//edit case
			serviceDd.val(defaultService);
			populateServiceRelatedFields2();
		}
		toggleAvailableMarkOnField(serviceDd, 1);
		toggleField_DelMsg({
			kase: 'exist',
			field: serviceDd
		});
		serviceDd.selectpicker('refresh');
		if (typeof callback != 'undefined') callback('service');
	}
	/* end: Populate options in service dropdown */
	/* start: Get staff and area list based on the class/service */
	function getStaffsAndAreas(bookingType, callback) {
		if (bookingType != 'busy' && (typeof bookingType == 'undefined' || !bookingType)) {
			bookingType = getBookingType()
		}
		if (typeof callback == 'undefined') {
			getStaffs(bookingType, function() {
				getAreas(bookingType);
			});
			getResources(bookingType);
		} else {
			getStaffs(bookingType, function() {
				getAreas(bookingType, function() {
					if (bookingType == 'busy') callback();
					else {
						getResources(bookingType, function() {
							callback();
						});
					}
				});
			});
		}
	}
	/* end: Get staff and area list based on the class/service */
	/* start: Get staff list based on the class/service */
	function getStaffs(bookingType, callback) {
		if (bookingType == 'class') var entityDd = classModal.find('select[name="staffClass"]');
		else if (bookingType == 'service') var entityDd = classModal.find('select[name="staffservice"]');
		if (bookingType == 'busy') {
			var modalStaffDd = $('#busyModal').find('select[name="staff"]'),
			staffSetGroupDispSpan = modalStaffDd.closest('.set-group').find('.set-group-disp span');
		} else {
			var modalStaffDd = classModal.find('select[name="staff"]'),
			staffSetGroupDispSpan = modalStaffDd.closest('.set-group').find('.set-group-disp span');
		}
		if (!isFieldMarkedAsAvail(entityDd)) {
			//Entity has been deleted
			modalStaffDd.html('').selectpicker('refresh');
			var selectedStaffName = eventObj.find('input[name="staffName"]').val()
			if (eventObj.find('input[name="isStaffDeleted"]').val() == 'true') {
				//Staff has been deleted
				selectedStaffName += ' has been deleted';
			}
			//alert(selectedStaffName)
			/*setTimeout(function(){ 
				//staffSetGroupDispSpan.html(selectedStaffName).addClass('text-danger');
			}, 1400);*/
			staffSetGroupDispSpan.html(selectedStaffName).addClass('text-danger');
			if (typeof callback != 'undefined') callback();
		} else {
			staffSetGroupDispSpan.removeClass('text-danger');
			toggleAvailableMarkOnField(modalStaffDd, 1);
			setFieldNeutral(modalStaffDd);
			var entityVal = bookingType != 'busy' ? entityDd.val() : '';
			if (bookingType == 'class') var duration = classModal.find('select[name="classDur"]').val();
			else if (bookingType == 'busy') var duration = $('#busyModal').find('input[name="busyDur"]').val();
			else var duration = classModal.find('select[name="serviceDur"]').val();
			if ((entityVal && duration && bookingType != 'busy') || (duration && bookingType == 'busy')) {
				var formData = {},
				eventDate = setEventDate(bookingType == 'busy' ? $('#busyModal') : classModal, 'submit');
				formData['date'] = eventDate.date;
				formData['day'] = eventDate.day;
				formData['duration'] = duration;
				if(bookingType == 'busy'){
					formData['time'] = timeStringToDbTime(busyModal.find('input[name="eventTime"]').val());
				}else{
				formData['time'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
				}
				if (eventObj.length) formData['eventId'] = eventObj.find('input[name="eventId"]').val();
				if (bookingType == 'busy') var url = 'busy-time';
				else if (bookingType == 'class') var url = 'classes/';
				else if (bookingType == 'service') var url = 'services/';
				if (bookingType == 'busy') var customUrl = public_url + 'settings/business/' + url + '/free-staffs';
				else var customUrl = public_url + 'settings/business/' + url + entityVal + '/free-staffs';
				$.get(customUrl, formData, function(data) {
					populateStaffs(data, callback, bookingType)
				}, 'json');
			} else populateStaffs({}, callback)
		}
		//}
	}
	/* end: Get staff list based on the class/service */
	/* start: Populate options in staff dropdown */
	function populateStaffs(data, callback, bookingType = '') {
		var ids = [],
		options = '';
		if (typeof data != 'undefined' && !$.isEmptyObject(data)) {
			$.each(data, function(key, value) {
				options += '<option value="' + key + '" data-fav-areas="' + value.favAreas + '">' + value.name + '</option>';
				ids.push(key);
			});
		}
		var selectedStaff = 0,
		selectedStaffName = '',
		modalStaffDd = {},
		staffSetGroupDispSpan = {};
		if (bookingType == 'busy') {
			modalStaffDd = $('#busyModal').find('select[name="staff"]');
			staffSetGroupDispSpan = modalStaffDd.closest('.set-group').find('.set-group-disp span');
		} else {
			modalStaffDd = classModal.find('select[name="staff"]')
			staffSetGroupDispSpan = modalStaffDd.closest('.set-group').find('.set-group-disp span');
		}
		if (eventObj.length) var defaultStaff = eventObj.find('input[name="staffId"]').val();
		else var defaultStaff = 0;
		if (defaultStaff) {
			//edit case
			var ifStaffExist = $.inArray(defaultStaff, ids);
			if (ifStaffExist < 0 && modalStaffDd.hasClass('hidden')) {
				//staff list does not have selected staff
				staffSetGroupDispSpan.addClass('text-danger');
				toggleAvailableMarkOnField(modalStaffDd, 0);
				var staffName = eventObj.find('input[name="staffName"]').val()
				if (eventObj.find('input[name="isStaffDeleted"]').val() == 'true') {
					//Staff has been deleted
					selectedStaffName = staffName + ' has been deleted';
				} else selectedStaffName = staffName + ' is not available';
			} else if (ifStaffExist >= 0) selectedStaff = defaultStaff;
		} else {
			//add case
			var staffId = parseInt(findStaffId(), 10);
			if (staffId && staffId != 'all' && staffId != 'all-ros' && $.inArray(staffId, ids) >= 0) {
				//filter has staff selected and staff list have filter selected staff
				selectedStaff = staffId;
			}
		}
		modalStaffDd.html(options);
		if (selectedStaff) modalStaffDd.val(selectedStaff);
		if (!selectedStaffName) selectedStaffName = modalStaffDd.find('option:selected').text();
		if (!selectedStaffName) {
			selectedStaffName = 'No staff found'
			staffSetGroupDispSpan.addClass('text-danger');
		}
		staffSetGroupDispSpan.text(selectedStaffName);
		modalStaffDd.selectpicker('refresh');
		if (typeof callback != 'undefined') callback();
	}
	/* end: Populate options in staff dropdown */
	/* start: Get areas list according to class/service */
	function getAreas(bookingType, callback) {
		//if(isUserType(['Admin'])){
			if (bookingType == 'class') var entityDd = classModal.find('select[name="staffClass"]');
			else if (bookingType == 'service') var entityDd = classModal.find('select[name="staffservice"]');
			if(bookingType == 'busy')
				var areaDd = busyModal.find('select[name="modalLocArea"]');
			else
				var areaDd = classModal.find('select[name="modalLocArea"]')
		/*,
		areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp')*/
		;
		if (!isFieldMarkedAsAvail(entityDd)) {
			//Entity has been deleted
			areaDd.html('').selectpicker('refresh');
			var span = '';
			eventObj.find("input[name='area[]']").each(function() {
				var data = JSON.parse($(this).val());
				if (data.isAreaDeleted == true) {
					//Area has been deleted
					span += '<span class="text-danger">' + data.locAreaName + ' has been deleted</span><br>';
				} else span += '<span>' + data.locAreaName + '</span><br>';
			});
			span = span.slice(0, -4)
			/*var areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp');
			areaSetGroupDisp.find('span').remove()
			areaSetGroupDisp.find('br').remove()
			areaSetGroupDisp.prepend(span)*/
			showAreasText(span);
			if (typeof callback != 'undefined') callback();
		} else {
			/*areaSetGroupDisp.find('span').remove()
			areaSetGroupDisp.find('br').remove()*/
			toggleAvailableMarkOnField(areaDd, 1);
			setFieldNeutral(areaDd);
			var entityVal = bookingType != 'busy' ? entityDd.val() : '';
			if (bookingType == 'class') var duration = classModal.find('select[name="classDur"]').val();
			else if (bookingType == 'busy') var duration = $('#busyModal').find('input[name="busyDur"]').val();
			else var duration = classModal.find('select[name="serviceDur"]').val();
			if ((entityVal && duration && bookingType != 'busy') || (duration && bookingType == 'busy')) {
				var formData = {},
				eventDate = setEventDate(bookingType == 'busy' ? $('#busyModal') : classModal, 'submit');
				formData['date'] = eventDate.date;
				formData['day'] = eventDate.day;
				if(bookingType == 'busy'){
					formData['time'] = timeStringToDbTime(busyModal.find('input[name="eventTime"]').val());

				}else{
				formData['time'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
				}
				formData['duration'] = duration;
				if (eventObj.length) formData['eventId'] = eventObj.find('input[name="eventId"]').val();
				if (bookingType == 'busy') var url = 'busy-time';
				else if (bookingType == 'class') var url = 'classes/';
				else if (bookingType == 'service') var url = 'services/';
				if (bookingType == 'busy') var customUrl = public_url + 'settings/business/' + url + '/free-areas';
				else var customUrl = public_url + 'settings/business/' + url + entityVal + '/free-areas';
				$.get(customUrl, formData, function(data) {
					populateAreas(data, callback, bookingType);
				}, 'json');
			} else populateAreas({}, callback,bookingType);
		}
		//}
	}
	/* end: Get areas list according to class/service */
	/* start: Populate options in area dropdown */
	function populateAreas(data, callback, bookingType = '') {
		var ids = [],
		options = '';
		if (typeof data != 'undefined' && !$.isEmptyObject(data)) {
			$.each(data, function(key, value) {
				options += '<option value="' + key + '">' + value + '</option>';
				ids.push(parseInt(key, 10));
			});
		}
		var selectedAreas = [],
		span = '',
		areaDd = {};
		if (bookingType == 'busy') areaDd = $('#busyModal').find('select[name="modalLocArea"]');
		else areaDd = classModal.find('select[name="modalLocArea"]');
		if (eventObj.length) var isEditMode = 1;
		else var isEditMode = 0;
		if (isEditMode) {
			//edit case
			if(bookingType == 'busy'){
				var busyAreaId = parseInt(eventObj.find("input[name='areaId']").val());
				ifAreaExist = $.inArray(busyAreaId, ids);
				if (ifAreaExist < 0 && areaDd.hasClass('hidden')) {
					//area list does not have selected area
					toggleAvailableMarkOnField(areaDd, 0);
					if (eventObj.find("input[name='isAreaDeleted']").val() == true) {
						//Area has been deleted
						span += '<span class="text-danger">Area has been deleted</span><br>';
					} else span += '<span class="text-danger">Area is not available</span><br>';
				} else if (ifAreaExist >= 0){
					selectedAreas.push(busyAreaId);
				}
			}else{
				eventObj.find("input[name='area[]']").each(function() {
					var data = JSON.parse($(this).val()),
					ifAreaExist = $.inArray(data.id, ids);
					if (ifAreaExist < 0 && areaDd.hasClass('hidden')) {
						//area list does not have selected area
						toggleAvailableMarkOnField(areaDd, 0);
						if (data.isAreaDeleted == true) {
							//Area has been deleted
							span += '<span class="text-danger">' + data.locAreaName + ' has been deleted</span><br>';
						} else span += '<span class="text-danger">' + data.locAreaName + ' is not available</span><br>';
					} else if (ifAreaExist >= 0) selectedAreas.push(data.id)
				});
			}
		} else {
			//add case
			/*var selectedStaffFavAreas = classModal.find('select[name="staff"] option:selected').data('fav-areas');
			if(selectedStaffFavAreas){
				//Selected staff has favorited areas
				selectedStaffFavAreas = selectedStaffFavAreas.toString();
				selectedStaffFavAreas = selectedStaffFavAreas.split(',');
				$.each(selectedStaffFavAreas, function(key, value){
					value = parseInt(value, 10);
					if($.inArray(value, ids) >= 0)
						selectedAreas.push(value)
				});
			}*/
			/*var selectedStaffFavAreas = getFavAreas();
			if(selectedStaffFavAreas){
				$.each(selectedStaffFavAreas, function(key, value){
					value = parseInt(value, 10);
					if($.inArray(value, ids) >= 0)
						selectedAreas.push(value)
				});
			}*/
			selectedAreas = getFavAreas(ids);
			if (!selectedAreas.length) {
				//Selected staff doesn't have favorite areas in the current areas list
				var areaId = parseInt(findAreaId(), 10);
				if (areaId && areaId != 'all' && $.inArray(areaId, ids) >= 0) {
					//filter has area selected and area list have filter selected area
					selectedAreas.push(areaId)
				}
			}
		}
		areaDd.html(options);
		areaDd.selectpicker('refresh');
		/*if(!selectedAreas.length && options)
			selectedAreas.push(areaDd.find('option:first').val())
			areaDd.val(selectedAreas);*/
		/*if(selectedAreas.length)
			areaDd.val(selectedAreas);

		areaDd.find('option:selected').each(function(){
			span += '<span>'+$(this).text()+'</span><br>';
		});
		if(span)
			span = span.slice(0,-4)
		else
			span = '<span class="text-danger">No area found</span>';
			
		areaDd.closest('.set-group').find('.set-group-disp').prepend(span)*/
		if(bookingType != 'busy'){
			setAreas(selectedAreas, span);
		}else{
			$('#busyModal').find('select[name="modalLocArea"]').val(busyAreaId);
			span += '<span>' + areaDd.find('option:selected').text() + '</span><br>';
			if (span) span = span.slice(0, -4)
				else span = '<span class="text-danger">No area found</span>';
			areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp');
			areaSetGroupDisp.find('span').remove()
			areaSetGroupDisp.find('br').remove()
			areaSetGroupDisp.prepend(span);
			areaDd.selectpicker('refresh');
		}
		if (!selectedAreas.length && options) {
			var firstOption = areaDd.find('option:first').val();
			if (isEditMode) {
				areaDd.val(firstOption)
				areaDd.selectpicker('refresh');
			} else {
				if(bookingType == 'busy'){
					areaDd.val(firstOption)
					span += '<span>' + areaDd.find('option:selected').text() + '</span><br>';
					if (span) span = span.slice(0, -4)
						else span = '<span class="text-danger">No area found</span>';
					areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp');
					areaSetGroupDisp.find('span').remove()
					areaSetGroupDisp.find('br').remove()
					areaSetGroupDisp.prepend(span);
					areaDd.selectpicker('refresh');
				}else{
					setAreas([firstOption])
				}
			};
		}
		if (typeof callback != 'undefined') callback();
		//areaDd.selectpicker('refresh');
	}
	/* end: Populate options in area dropdown */
	function getFavAreas(areasId) {
		var selectedAreas = [],
		selectedStaffFavAreas = classModal.find('select[name="staff"] option:selected').data('fav-areas');
		if (selectedStaffFavAreas) {
			//Selected staff has favorited areas
			selectedStaffFavAreas = selectedStaffFavAreas.toString();
			selectedStaffFavAreas = selectedStaffFavAreas.split(',');
			if (typeof areasId == 'undefined') {
				areasId = [];
				classModal.find('select[name="modalLocArea"] option').each(function() {
					areasId.push(parseInt($(this).val(), 10))
				})
			}
			$.each(selectedStaffFavAreas, function(key, value) {
				value = parseInt(value, 10);
				if ($.inArray(value, areasId) >= 0) selectedAreas.push(value)
			});
		}
		return selectedAreas;
	}

	function setAreas(selectedAreas, span) {
		if (typeof span == 'undefined') span = '';
		var areaDd = classModal.find('select[name="modalLocArea"]');
		if (selectedAreas.length) areaDd.val(selectedAreas);
		areaDd.find('option:selected').each(function() {
			span += '<span>' + $(this).text() + '</span><br>';
		});
		if (span) span = span.slice(0, -4)
			else span = '<span class="text-danger">No area found</span>';
		showAreasText(span);
		areaDd.selectpicker('refresh');
		//areaDd.closest('.set-group').find('.set-group-disp').prepend(span)
	}

	function showAreasText(span) {
		var areaDd = classModal.find('select[name="modalLocArea"]'),
		areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp');
		areaSetGroupDisp.find('span').remove()
		areaSetGroupDisp.find('br').remove()
		areaSetGroupDisp.prepend(span)
	}
	/* start: Get linked resources list based on the class/service */
	function getResources(bookingType, callback) {
		if (typeof bookingType == 'undefined') bookingType = getBookingType();
		if (bookingType == 'class') var entityDd = classModal.find('select[name="staffClass"]');
		else if (bookingType == 'service') var entityDd = classModal.find('select[name="staffservice"]');
		if (!isFieldMarkedAsAvail(entityDd)) {
			//Entity has been deleted
			if (bookingType == 'class') var entityVal = eventObj.find('input[name="classId"]').val();
			else if (bookingType == 'service') var entityVal = eventObj.find('input[name="serviceId"]').val();
		} else var entityVal = bookingType != 'busy' ? entityDd.val() : '';
		if (bookingType == 'class') var duration = classModal.find('select[name="classDur"]').val();
		else if (bookingType == 'busy') var duration = $('#busyModal').find('input[name="busyDur"]').val();
		else var duration = classModal.find('select[name="serviceDur"]').val();
		if ((entityVal && duration && bookingType != 'busy') || (duration && bookingType == 'busy')) {
			var formData = {},
			eventDate = setEventDate(classModal, 'submit');
			formData['date'] = eventDate.date;
			formData['duration'] = duration;
			formData['time'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
			if (eventObj.length) formData['eventId'] = eventObj.find('input[name="eventId"]').val();
			if (bookingType == 'busy') var url = 'busy-time/';
			else if (bookingType == 'class') var url = 'classes/';
			else if (bookingType == 'service') var url = 'services/';
			if (bookingType == 'busy') var customUrl = public_url + 'settings/business/' + url + '/resources';
			else var customUrl = public_url + 'settings/business/' + url + entityVal + '/resources';
			$.get(customUrl, formData, function(data) {
				populateResources(data, callback);
			}, 'json');
		} else if (typeof callback != 'undefined') callback();
	}
	/* end: Get linked resources list based on the class/service */
	/* start: Populate resources */
	function populateResources(data, callback) {
		var options = "<option value=''>-- Select --</option>";
		if (typeof data != 'undefined' && !$.isEmptyObject(data)) {
			$.each(data, function(key, value) {
				options += '<option value="' + key + '" data-noofitem="' + value.itemQuant + '" data-default-quant="' + value.defaultQuant + '" data-is-deleted="' + value.isDeleted + '" class="' + ((value.isDeleted || !value.itemQuant) ? 'hidden' : '') + '">' + value.resName + '</option>';
			});
		}
		$('#resource-form .resource-clone-class:not(.hidden)').remove();
		var hiddenDd = $('#resource-form .resource-clone-class:eq(0) select.resource-option');
		hiddenDd.empty().append(options);
		if (eventObj.length) {
			var resourceForm = $('#resource-form');
			eventObj.find("input[name='resource[]']").each(function(key, value) {
				var resourceData = JSON.parse($(this).val()),
				resOpt = hiddenDd.find('option[value="' + resourceData.id + '"]');
				if (resOpt.length) {
					resourceForm.find('.add-resource-row').trigger('click');
					var itemQuant = resOpt.data('noofitem'),
					resDd = resourceForm.find('select.resource-option:last');
					resDd.val(resourceData.id);
					if (itemQuant < resourceData.quantity) resDd.find('option:selected').data('noofitem', resourceData.quantity)
						var isDeleted = resOpt.data('is-deleted');
					if (isDeleted) {
						toggleAvailableMarkOnField(resDd, 0);
						if (isDeleted == 'link') var message = 'Unlinked with service'
							else if (isDeleted == 'resource') var message = 'Resource deleted'
								setFieldInvalid(resDd.closest('.resItemLocDd'), message);
							resDd.data('is-deleted', isDeleted)
						} else toggleAvailableMarkOnField(resDd, 1);
						resDd.selectpicker('refresh')
						populateItemQuant(resDd)
						var quantDd = resourceForm.find('select.item-op:last');
						if (itemQuant < resourceData.quantity) {
							if (!itemQuant) itemQuant = '';
							quantDd.find('option[value="' + itemQuant + '"]').nextAll().addClass('hidden')
							if (isFieldMarkedAsAvail(resDd)) {
								setFieldInvalid(quantDd.closest('.resItemLocDd2'), 'Quantity changed');
								toggleAvailableMarkOnField(quantDd, 0);
							}
						} else toggleAvailableMarkOnField(quantDd, 1);
						quantDd.val(resourceData.quantity).selectpicker('refresh');
					}
				});
		}
		$('#resource-form ul.dropdown-menu a.hidden').parent().addClass('hidden')
		if (typeof callback != 'undefined') callback();
	}
	/* end: Populate resources */
	/* start: Get selected area id from the calendar */
	function findAreaId() {
		if (typeof areaDdCalend != 'undefined') return areaDdCalend.val();
		else if (typeof areaDdCalend == 'undefined' || areaDdCalend.val() == null) return 'all';
		return '';
	}
	/* end: Get selected area id from the calendar */
	/* start: Get selected staff id from the calendar */
	function findStaffId() {
		if (typeof staffFilterCalend != 'undefined') {
			if (isUserType(['Admin'])) return staffFilterCalend.val();
			else if (isUserType(['Staff'])) return loggedInUser.id;
		}
		return '';
	}
	/* end: Get selected staff id from the calendar */
	/* start: Update booked client quick info */
	function updateBookedclientQuickInfo() {
		var //infoElem = classModal.find('.linkedclients-text'),
		infoElem = classModal.find('.show-clients-tab'),
		linkedClientsCount = linkedclientsCount();
		progressBar = classModal.find('div.progress-bar'),
		waitingClients = classModal.find('div#waitingLinkedclientList > a[data-status="Waiting"]').length;

		capacity = getEventCapacity();
		if(waitingClients != 0 && waitingClients != undefined && waitingClients != ''){
			var waitingText = `${waitingClients} Clients waiting`;
			classModal.find('.waiting-clients-tab').text(waitingText);
				classModal.find('.waitingClient').text(waitingText);
		}
		/*var bookingType = getBookingType();
		if(bookingType == 'class')
			var capacity = classModal.find('input[name="classCap"]').val();
		else if(bookingType == 'service')
			var capacity = 1;*/
		var text = 'No clients booked';
		if (linkedClientsCount) text = linkedClientsCount + ' / ' + capacity + ' Clients booked';
		/*if(isModalModeEdit())
		text += ' >>';*/
		infoElem.html(text);
		if (capacity && capacity < linkedClientsCount) infoElem.not('span').addClass('text-danger');
		else infoElem.removeClass('text-danger');
		var progressField = classModal.find('#show-client-prog'),
		progRow = progressField.find('.prog-row');
		if (progRow.width() > 0) totalWidth = (progRow.width()) - ((2 * capacity) + 2);
		if (totalWidth > 0) totalClientWidth = totalWidth;
		var unitWidth = (totalClientWidth / capacity),
		progHtml = "";
		if (capacity) {
			if (capacity == 1) {
				progHtml += '<div class="prog-col active" style="width:99%">&nbsp;</div>';
			} else {
				for (var i = 1; i <= capacity; i++) {
					if (i <= linkedClientsCount) progHtml += '<div class="prog-col active" style="width:' + unitWidth + 'px">&nbsp;</div>';
					else progHtml += '<div class="prog-col inactive" style="width:' + unitWidth + 'px">&nbsp;</div>';
				}
			}
			progHtml += '<div style="clear: both;"></div>';
		}
		progRow.empty();
		progRow.append(progHtml);
		/*var progressPercent = 0;
		if(capacity && linkedClientsCount){
			if(capacity > linkedClientsCount)
				progressPercent = (linkedClientsCount*100)/capacity;
			else
				progressPercent = 100;
		}
		progressBar.width(progressPercent+'%');
		if(progressPercent == 100)
			progressBar.removeClass('progress-bar-success').addClass('progress-bar-danger')
		else
			progressBar.removeClass('progress-bar-danger').addClass('progress-bar-success')*/
	}
	/* end: Update booked client quick info */
	/* start: Count linked clients */
	function linkedclientsCount(ifConfirmedOnly, countDeleted) {
		var query = 'div#linkedclientList > a';
		if (typeof ifConfirmedOnly != 'undefined' && ifConfirmedOnly) query += '[data-status="Confirm"]';
		if (typeof countDeleted == 'undefined' || !countDeleted) query += '[data-deleted="0"]';
		return classModal.find(query).length;
		/*if(typeof ifConfirmedOnly != 'undefined')
			return classModal.find('div#linkedclientList > a[data-status="Confirm"][data-deleted="0"]').length;

			return classModal.find('div#linkedclientList > a[data-deleted="0"]').length;*/
		}
		/* end: Count linked clients */
		/* start: Count clients to reschedule */
		function rescheduleClientsCount() {
			return Object.keys(rescheduleClients).length;
		}
		/* end: Count clients to reschedule */
		/* start: Toggle client form links */
		function toggleClassClientLnks(mode, calcEligibility) {
			if (typeof calcEligibility != 'undefined') {
				if (!pastCurrentMode) toggleClassClientLnks(mode)
					else {
						// classModal.find('a#linkClientClass').hide(); //show button for add services in past date
						classModal.find('a#unlinkClientClass').hide();
						classModal.find('a#resetClientlinkForm').hide();
				//classModal.find('a#confirmClient').hide();
			}
		} else {
			if (cancelledBooking || (isUserType(['Staff']) && !$.isEmptyObject(eventObj) && eventObj.find('input[name="userId"]').val() != loggedInUser.userId)) {
				classModal.find('a#linkClientClass').hide();
				classModal.find('a#unlinkClientClass').hide();
				classModal.find('a#resetClientlinkForm').hide();
				//classModal.find('a#confirmClient').hide();
			} else {
				if (mode == 'create') {
					classModal.find('a#linkClientClass').show();
					classModal.find('a#unlinkClientClass').hide();
					classModal.find('a#resetClientlinkForm').hide();
					//classModal.find('a#confirmClient').hide();
				} else {
					classModal.find('a#linkClientClass').hide();
					var bookingType = getBookingType()
					if (bookingType == 'class') {
						classModal.find('a#resetClientlinkForm').show();
						classModal.find('a#unlinkClientClass').show();
						if (classModal.find('div#linkedclientList > a.active').data('deleted') == '1') classModal.find('a#unlinkClientClass').hide();
					} else if (bookingType == 'service') {
						classModal.find('a#resetClientlinkForm').hide();
						classModal.find('a#unlinkClientClass').hide();
						//classModal.find('a#confirmClient').hide();
						if (isSalesProcessEvent()) classModal.find('a#unlinkClientClass').hide();
					}
				}
			}
		}
		toggleIf_make_up_sessionOption('hide', '');
	}
	/* end: Toggle client form links */
	/* start: Reset client form */
	function clearClientForm(clientForm, ifResetForm) {
		//clientForm.find('div.checkbox.m-t-5').show();
		//clientForm.find(':input').prop('readonly', false);
		clientForm.find('.callClientSubview').show();
		clientForm.find('.noneditable').prop('readonly', false);
		clientForm.find('.disableable').prop('disabled', false);
		if (ifResetForm) {
			clearForm(clientForm);
			toggleReduceRate('hide')
			//clientForm.find('input[name="isNewClient"]').trigger('change');
		}
		classModal.find('div#linkedclientList > a').removeClass('active');
	}
	/* end: Reset client form */
	function getEventCapacity() {
		var bookingType = getBookingType();
		if (bookingType == 'class') {
			if (pastCurrentMode) return eventObj.find('input[name="capacity"]').val();
			return classModal.find('input[name="classCap"]').val();
		} else if (bookingType == 'service') return 1;
	}
	/* start: Check if linked clients exceed class capacity */
	function ifClientExceedEventCapacity(mode) {
		var capacity = getEventCapacity();
		if (mode == 'add') linkedClientsCount = linkedclientsCount();
		else linkedClientsCount = linkedclientsCount(true);
		if (!capacity || capacity <= linkedClientsCount) return true;
		return false;
	}
	/* end: Check if linked clients exceed class capacity */
	/* start: Confirm if to overbook class with clients */
	function classOverBookConfirm(callback) {
		if (ifClientExceedEventCapacity('add')) {
			classOverBookConfirmSwal(callback)
		} else callback();
	}

	function classOverBookConfirmSwal(callback) {
		swal({
			title: 'This class has reached its capacity',
			allowOutsideClick: true,
			showCancelButton: true,
			confirmButtonText: 'Still add',
			confirmButtonColor: '#ff4401',
			cancelButtonText: "Don't add"
		}, function(isConfirm) {
			if (isConfirm) callback();
		});
	}
	/* end: Confirm if to overbook class with clients */
	/* start: Send ajax to link client with booking */
	function linkClientClassAjax(clientForm) {
		toggleWaitShield("show", 'linkClient');
		var data = {
			id: clientForm.find('input[name="clientId"]').val(),
			name: clientForm.find('input.clientList').val(),
			phone: getClientFullNumb(clientForm),
			email: clientForm.find('input[name="clientEmail"]').val(),
			note: clientForm.find('textarea[name="clientNote"]').val(),
			isCreatingMakeUpSession: clientForm.find('input[name="isCreatingMakeUpSession"]:checked').val()
		};
		var bookingType = getBookingType();
		if (bookingType == 'class') {
			data.isEpicCredit = clientForm.find('input[name="isCreatingMakeUpSession"]:checked').val();
			data.isReducedRate = clientForm.find('input[name="isReducedRate"]:checked').val();
			data.reducedRate = parseFloat(clientForm.find('input[name="reducedRate"]').val()).toFixed(2);
			data.ifRecur = clientForm.find('input[name="ifRecur"]:checked').val();
			data.accStatus = clientForm.find('input[name="accStatus"]').val();
			data.isLdc = clientForm.find('input[name="isLdc"]').val();
			// data.forceAdd = clientForm.find('input[name="forceAdd"]').val();
			data.forceAdd = data.ifRecur == 1 ? '' : (data.isEpicCredit == 1 ? 'yes' : clientForm.find('input[name="forceAdd"]').val());
			var ajaxAction = 'classes/edit';
		} else if (bookingType == 'service') {
			
			var ajaxAction = 'single-service/edit';
			data.isLdc = clientForm.find('input[name="isLdc"]').val();
   			data.isEpicCredit = clientForm.find('input[name="isCreatingMakeUpSession"]:checked').val();
			data.forceAdd = data.isEpicCredit == 1 ? 'yes' : clientForm.find('input[name="forceAdd"]').val();
		}
		// console.log(data);
		var accountStatus = clientForm.find('input[name="accStatus"]').val();
		// console.log(accountStatus);
		var formattedData = prepareClientData(data);
		var classClients = {};
		classClients[data['id']] = {
			accStatus: formattedData['accStatus'],
			note: data['note'],
			isReducedRate: formattedData['isReducedRate'],
			reducedRate: formattedData['reducedRate'],
			ifRecur: formattedData['ifRecur'],
			attendance: formattedData['attendance'],
			isExisting: formattedData['isExisting'],
			ifMakeUp: 0,
			makeupNotes: '',
			isCreatingMakeUpSession: formattedData['isCreatingMakeUpSession'],
			status: formattedData['status'],
			forceAdd: formattedData['forceAdd'],
			order: clientLinkOrder,
			isLdc: formattedData['isLdc']
		};
		clientLinkOrder++;
		var formData = {},
		activeTab = getActiveTab();
		formData['step'] = activeTab;
		formData['classClients'] = classClients;
		formData['eventId'] = eventObj.find('input[name="eventId"]').val();
		$.post(public_url + 'staffevents/' + ajaxAction, formData, function(resp) {
			var resp = JSON.parse(resp);
			if (resp.status == "added") {
				reloadEvents = true;
				getBookingDetails(formData['eventId'], bookingType, function(eventHtml) {
					eventObj.find('input').remove();
					var elem = eventObj.find('.calendEvent');
					if (!elem.length) elem = eventObj;
					elem.prepend(eventHtml);
					eventObj.find('input[name="client[]"]').each(function(){
						clientData = JSON.parse($(this).val());
						if(data['id'] == clientData.id){
							data.isEpicCredit = clientData.isEpicCredit;
							data.isInvoice = clientData.isInvoice;
						}
					});
					if(bookingType == 'service'){
						data.isEpicCredit = parseInt(eventObj.find('input[name="isEpicCredit"]').val());
						data.isInvoice = parseInt(eventObj.find('input[name="isInvoice"]').val());
					}
					
					if (!isSalesProcessEvent()) {
						if (!resp.isClientMakeup) toggleTab(classModal, 'classReccur', 'show');
						toggleTab(classModal, 'classNotes', 'show');
					} else {
						updateUiForSalesProcess();
					}
					if (bookingType == 'service' && (data.isEpicCredit == 1 || data.forceAdd == 1 || data.forceAdd == 'yes')) {
						toggleTab(classModal, 'classReccur', 'hide');
					}
					data.isExisting = 1;
				data.status = resp.clientStatus;
				if ('isSalesProcEvent' in resp) data.isSalesProcEvent = resp['isSalesProcEvent'][data['id']];
				// console.log(data, clientForm);
				linkClientClass(data, clientForm)
				})
				
				if (formattedData['isCreatingMakeUpSession']) {
					fetchClientsForTypeahead('update', 'book_team');
				}
				swal({
					title: resp.message,
					text: '',
					type: "success",
					confirmButtonColor: "#ff4401",
					confirmButtonText: "Okay",
				});
				// if('invDue' in resp)
				// 	showClientBookedMsg(data.name, resp.invDue);
				// else if('invExistId' in resp)
				// 	showClientBookedMsg(data.name,'', resp.invExistId);
				// else
				// 	showClientBookedMsg(data.name);
				resetMakeupRecurrField();
			} else if (resp.status == "error") {
				$.each(resp.errorData, function() {
					$.each(this, function(errorType, message) {
						if (errorType == "membershipUnsatisfy" || errorType == "clientUnavail") {
							if (errorType == "membershipUnsatisfy") {
								//clientForm.find('input[name="ifRecur"]').prop('checked', false);
								swal({
									title: "This client doesn't meet membership restrictions.",
									allowOutsideClick: false,
									showCancelButton: true,
									confirmButtonText: accountStatus == 'Contra' || accountStatus == 'Active' ? 'Add with invoice / epic credit' : 'Add with epic credit',
									confirmButtonColor: '#ff4401',
									cancelButtonText: "Don't add"
								}, function(isConfirm) {
									if (isConfirm) {
										clientForm.find('input[name="forceAdd"]').val('yes');
										linkClientClassAjax(clientForm)
									} else showNotific(prepareNotific('error', 'This client doesn\'t meet membership restrictions!', 'clientsBusy'), classModal.find('div#classClients'));
								});
							} else showNotific(prepareNotific('error', 'This client is busy at specified hours!', 'clientsBusy'), classModal.find('div#classClients'));
						} else if (errorType == 'lowBalance') {
							if($.inArray(accountStatus, [ "Pre-Consultation", "Pre-Benchmarking", "Pre-Training"] )){
								swal({
									title: "This client doesn't meet membership restrictions.",
									allowOutsideClick: false,
									showCancelButton: true,
									confirmButtonText: 'Add with invoice / epic credit',
									confirmButtonColor: '#ff4401',
									cancelButtonText: "Don't add"
								}, function(isConfirm) {
									if (isConfirm) {
										clientForm.find('input[name="forceAdd"]').val('yes');
										linkClientClassAjax(clientForm)
									} else showNotific(prepareNotific('error', 'This client doesn\'t meet membership restrictions!', 'clientsBusy'), classModal.find('div#classClients'));
								});
							}else{
								swal({
									title: "You don't have sufficient credit balance available in your account.",
									allowOutsideClick: false,
									showCancelButton: false,
									confirmButtonText: 'Okay',
									confirmButtonColor: '#ff4401',
									type: 'warning',
									// cancelButtonText: "Okay"
								});
							}
						} else if (errorType == 'membershipUnsatisfyRec') {
							swal({
								title: "Client already booked recurring classes in future weeks.",
								allowOutsideClick: false,
								showCancelButton: false,
								confirmButtonText: 'Okay',
								confirmButtonColor: '#ff4401',
								type: 'warning',
								// cancelButtonText: "Okay"
							});
						}
					});
				});
			}
			toggleWaitShield("hide", 'linkClient');
		});
	}
	/* end: Send ajax to link client with booking */
	/* start: Link existing client with class */
	function linkClientClass(clientData, clientForm) {
		clearClientForm(clientForm, true);
		updateLinkedclientList(clientData);
		toggleEventOverBookAlert();
		bookingType = getBookingType();
		if (bookingType == 'class') toggleClassClientLnks('create')
			else if (bookingType == 'service') {
				clearNotific('serviceClientError');
				selectLatestLinkedClient()
			//toggleClassClientLnks('view')
		}
		updateBookedclientQuickInfo();
		//var totalClients=$('#classModal').find('div#linkedclientList').children().length;
		disabledEnabledCancleClsBtn();
	}
	/* end: Link existing client with class */
	/* start: Format and prepare client data */
	function prepareClientData(clientData) {
		var returnData = {};
		if (clientData['status'] == null) returnData['status'] = 'Confirm';
		else returnData['status'] = clientData['status'];
		if (clientData['isReducedRate'] == null) returnData['isReducedRate'] = 0;
		else returnData['isReducedRate'] = clientData['isReducedRate'];
		if ('reducedRate' in clientData && clientData['isReducedRate']) returnData['reducedRate'] = clientData['reducedRate'];
		else returnData['reducedRate'] = '';
		if (clientData['ifRecur'] == null) returnData['ifRecur'] = 0;
		else returnData['ifRecur'] = clientData['ifRecur'];
		if ('attendance' in clientData) returnData['attendance'] = clientData['attendance'];
		else returnData['attendance'] = 'Booked';
		if ('isExisting' in clientData) returnData['isExisting'] = 1;
		else returnData['isExisting'] = 0;
		if ('isCreatingMakeUpSession' in clientData && clientData['isCreatingMakeUpSession'] != null) returnData['isCreatingMakeUpSession'] = 1;
		else returnData['isCreatingMakeUpSession'] = 0;
		if ('accStatus' in clientData) returnData['accStatus'] = clientData['accStatus'];
		else returnData['accStatus'] = '';
		if ('forceAdd' in clientData) returnData['forceAdd'] = clientData['forceAdd'];
		else returnData['forceAdd'] = '';
		if ('isEpicCredit' in clientData) returnData['isEpicCredit'] = clientData['isEpicCredit'];
		else returnData['isEpicCredit'] = '';
		if ('isLdc' in clientData) returnData['isLdc'] = clientData['isLdc'];
		else returnData['isLdc'] = 0;
		/*if('epicCreditBal' in clientData){
			if(clientData['epicCreditBal'] != '' && clientData['epicCreditBal'] != 'null' && clientData['epicCreditBal'] != null)
				returnData['epicCreditBal'] = clientData['epicCreditBal'];
			else
				returnData['epicCreditBal'] = '0.00';
		}
		else
			returnData['epicCreditBal'] = '';*/
		returnData['epicCreditBal'] = formatEpicBal(clientData);
		return returnData;
	}
	/* end: Format and prepare client data */
	function formatEpicBal(data) {
		if ('epicCreditBal' in data) {
			if (data['epicCreditBal'] != '' && data['epicCreditBal'] != 'null' && data['epicCreditBal'] != null) return data['epicCreditBal'];
			return '0.00';
		}
		return '';
	}

	function selectLatestLinkedClient() {
		var clientLink = $('#linkedclientList > a:first');
		if (clientLink.length) clientLink.trigger('click')
			else toggleClassClientLnks('create', true)
		}
	/* start: Update linked client list */
	function updateLinkedclientList(clientData) {
		var data = prepareClientData(clientData);
		clientData['status'] = data['status'];
		clientData['isReducedRate'] = data['isReducedRate'];
		clientData['isEpicCredit'] = data['isEpicCredit'];
		var reducedRate = data['reducedRate'];
		clientData['ifRecur'] = data['ifRecur'];
		var clientAttendance = data['attendance'];
		var isExistingClient = data['isExisting'];
		var isCreatingMakeUpSession = data['isCreatingMakeUpSession'];
		var accStatus = data['accStatus'];
		if ('isMakeUp' in clientData && clientData['isMakeUp'] != null && clientData['isMakeUp']) var isMakeUp = 1;
		else var isMakeUp = 0;
		if ('isClientDeleted' in clientData && clientData['isClientDeleted'] != null && clientData['isClientDeleted']) var isClientDeleted = 1;
		else var isClientDeleted = 0;
		var clientAttendanceIcon = calcClientAttendanceIcon(clientAttendance);
		if ('isSalesProcEvent' in clientData && clientData['isSalesProcEvent']) var isSalesProcEvent = 1
			else var isSalesProcEvent = 0
				if ('epicCreditBal' in data) var epicCreditBal = data['epicCreditBal']
					else {
			//var clientData = eventObj.find('')
			var epicCreditBal = 0;
		}
		if(clientData['status'] == 'Waiting'){
			var waitingListItem
			 = "<a class='list-group-item" + (isClientDeleted ? ' list-group-item-warning' : '') + "' href='#' data-id='" + clientData['id'] + "' data-name='" + escSingleQuote(clientData['name']) + "' data-phone='" + clientData['phone'] + "' data-email='" + clientData['email'] + "' data-note='" + clientData['note'] + "' data-is-reduced-rate='" + clientData['isReducedRate'] + "' data-reduced-rate='" + reducedRate + "' data-if-recur='" + clientData['ifRecur'] + "' data-status='" + clientData['status'] + "' data-photo='" + clientData['photo'] + "' data-deleted='" + isClientDeleted + "' data-acc-status='" + accStatus + "' data-is-ldc='"+clientData['isLdc']+"'>";
			if (clientData['note']) waitingListItem
			 += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Has note'><i class='fa fa-comment'></i></span>";
			if (clientData['isReducedRate']) waitingListItem
			 += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Reduced Rate'><i class='fa fa-dollar'></i></span>";
			//if(clientData['isEpicCredit']){
				if (epicCreditBal) {
					waitingListItem
					 += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='EPIC Credit - $" + epicCreditBal + "'><i class='fa fa-money'></i></span>";
				} else if (clientData['isEpicCredit']) {
					waitingListItem
					 += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='EPIC Credit'><i class='fa fa-money'></i></span>";
				}
				if (clientData['ifRecur']) waitingListItem
				 += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Recurring'><i class='fa fa-retweet'></i></span>";
			// if(clientData['isInvoice'] || clientData['isEpicCredit'] || data['forceAdd'] == 'yes')
			if (clientData['isInvoice'] || data['forceAdd'] == 'yes') waitingListItem
			 += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Invoiced'><i class='fa fa-bank'></i></span>";
			if (clientData['status'] == 'Waiting') waitingListItem
			 += "<span class='badge transparentBadge epic-tooltip clientStatusIcon' rel='tooltip' data-toggle='tooltip' title='Waiting'><i class='fa fa-clock-o'></i></span>";
			if ( /*isCreatingMakeUpSession ||*/ isMakeUp) waitingListItem
			 += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Make Up'><i class='fa fa-code-fork'></i></span>";
			if (clientData['isInvoice'] == 0 && clientData['isEpicCredit'] == 0) waitingListItem
			 += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Membership'><i class='fas fa-user-shield'></i></span>";
			if (clientData['isLdc']) waitingListItem
			 += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='LDC Class'><img src='"+public_url+"assets/images/ldc-icon.png' /></span>";
			// waitingListItem
			//  += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Membership'><img class='membership-book-icon' src='"+get_hostname(document.URL)+"/assets/images/membership-icon.png"+"'></span>";
			waitingListItem
			 += "<span class='badge transparentBadge epic-tooltip clientAttendanceIcon' rel='tooltip' data-toggle='tooltip' title='" + (clientAttendanceIcon ? clientAttendance : '') + "'><i class='" + clientAttendanceIcon + "'></i></span><span class='badge transparentBadge epic-tooltip salesMark " + (isSalesProcEvent ? '' : 'hidden') + "' rel='tooltip' data-toggle='tooltip' title='Team'><i class='fa fa-group'></i></span>" + clientData['name'] + "</a>";
			classModal.find('div#waitingLinkedclientList').prepend(waitingListItem
				).children(':first-child').find('.epic-tooltip').tooltipster();
			classModal.find('div#waitingClassAttendanceList').prepend(waitingListItem
					).children(':first-child').find('.epic-tooltip').tooltipster();
					
		}else{
		var listItem = "<a class='list-group-item" + (isClientDeleted ? ' list-group-item-warning' : '') + "' href='#' data-id='" + clientData['id'] + "' data-name='" + escSingleQuote(clientData['name']) + "' data-phone='" + clientData['phone'] + "' data-email='" + clientData['email'] + "' data-note='" + clientData['note'] + "' data-is-reduced-rate='" + clientData['isReducedRate'] + "' data-reduced-rate='" + reducedRate + "' data-if-recur='" + clientData['ifRecur'] + "' data-status='" + clientData['status'] + "' data-photo='" + clientData['photo'] + "' data-deleted='" + isClientDeleted + "' data-acc-status='" + accStatus + "' data-is-ldc='"+clientData['isLdc']+"'>";
		if (clientData['note']) listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Has note'><i class='fa fa-comment'></i></span>";
		if (clientData['isReducedRate']) listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Reduced Rate'><i class='fa fa-dollar'></i></span>";
		//if(clientData['isEpicCredit']){
			if (epicCreditBal) {
				listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='EPIC Credit - $" + epicCreditBal + "'><i class='fa fa-money'></i></span>";
			} else if (clientData['isEpicCredit']) {
				listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='EPIC Credit'><i class='fa fa-money'></i></span>";
			}
			if (clientData['ifRecur']) listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Recurring'><i class='fa fa-retweet'></i></span>";
		// if(clientData['isInvoice'] || clientData['isEpicCredit'] || data['forceAdd'] == 'yes')
		if (clientData['isInvoice'] || data['forceAdd'] == 'yes') listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Invoiced'><i class='fa fa-bank'></i></span>";
		if (clientData['status'] == 'Waiting') listItem += "<span class='badge transparentBadge epic-tooltip clientStatusIcon' rel='tooltip' data-toggle='tooltip' title='Waiting'><i class='fa fa-clock-o'></i></span>";
		if ( /*isCreatingMakeUpSession ||*/ isMakeUp) listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Make Up'><i class='fa fa-code-fork'></i></span>";
		if (clientData['isInvoice'] == 0 && clientData['isEpicCredit'] == 0) listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Membership'><i class='fas fa-user-shield'></i></span>";
		if (clientData['isLdc']) listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='LDC Class'><img src='"+public_url+"assets/images/ldc-icon.png' /></span>";
		// listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Membership'><img class='membership-book-icon' src='"+get_hostname(document.URL)+"/assets/images/membership-icon.png"+"'></span>";
		listItem += "<span class='badge transparentBadge epic-tooltip clientAttendanceIcon' rel='tooltip' data-toggle='tooltip' title='" + (clientAttendanceIcon ? clientAttendance : '') + "'><i class='" + clientAttendanceIcon + "'></i></span><span class='badge transparentBadge epic-tooltip salesMark " + (isSalesProcEvent ? '' : 'hidden') + "' rel='tooltip' data-toggle='tooltip' title='Team'><i class='fa fa-group'></i></span>" + clientData['name'] + "</a>";
		classModal.find('div#linkedclientList').prepend(listItem).children(':first-child').find('.epic-tooltip').tooltipster();
		}
		if (isExistingClient && pastCurrentMode && clientData['status'] != 'Waiting') createClientAttendanceRow({
			id: clientData['id'],
			attendance: clientAttendance,
			name: clientData['name'],
			photo: clientData['photo'],
			isDeleted: isClientDeleted,
			accStatus: accStatus
		});
			toggleAttendanceTab();
			if (!isClientDeleted) {
				classClients[clientData['id']] = {
					accStatus: accStatus,
					note: clientData['note'],
					isReducedRate: clientData['isReducedRate'],
					reducedRate: clientData['reducedRate'],
					ifRecur: clientData['ifRecur'],
					attendance: clientAttendance,
					isExisting: 1 /*isExistingClient*/ ,
					ifMakeUp: 0,
					makeupNotes: '',
					isCreatingMakeUpSession: isCreatingMakeUpSession,
					status: clientData['status'],
					order: clientLinkOrder /*, isDeleted:clientData['isClientDeleted']*/ ,
					isInvoice: clientData['isInvoice'],
					isLdc:clientData['isLdc'],
				};
				clientLinkOrder++;
			
			}
		}
		/* end: Update linked client list */
		function createClientAttendanceRow(clientData) {
			if (clientData.isDeleted || cancelledBooking) var attendaceDisab = 'disabled';
			else var attendaceDisab = '';
		var attendanceRow = "<div class='row m-b-10' id='eventClientAttendance-" + clientData['id'] + "'><div class='col-sm-4'><img class='mw-50' src='" + dpSrc(clientData['photo']) + "'> " + clientData['name'] + "</div><div class='col-sm-6'><div class='btn-group' data-toggle='buttons' data-prev-val='" + clientData['attendance'] + "'><label class='btn btn-default " + attendaceDisab + "'><input type='radio' autocomplete='off' value='Booked' data-client-id='" + clientData['id'] + "' " + attendaceDisab + ">Booked</label><label class='btn btn-default " + attendaceDisab + "'><input type='radio' autocomplete='off' value='Attended' data-client-id='" + clientData['id'] + "' " + attendaceDisab + ">Attended</label><label class='btn btn-default " + attendaceDisab + "'><input type='radio' autocomplete='off' value='Did not show' data-client-id='" + clientData['id'] + "' " + attendaceDisab + ">Did not show</label></div></div>"; // data-acc-status='"+clientData['accStatus']+"'
		classModal.find('div#classAttendanceList').prepend(attendanceRow).children(':first-child').find('input[value="' + clientData['attendance'] + '"]').attr('checked', true).parent().addClass('active');
	}
	/* start: Determine client attendance icon */
	function calcClientAttendanceIcon(clientAttendance) {
		if (clientAttendance == 'Attended') return 'fa fa-check';
		else if (clientAttendance == 'Did not show') return 'fa fa-thumbs-down';
		else return '';
	}
	/* end: Determine client attendance icon */
	/* start: Toggle client attendace tab as per time mode */
	function toggleAttendanceTab(action) {
		if (typeof action == 'undefined') {
			if (!$.isEmptyObject(eventObj)) var eventType = eventObj.find('input[name="type"]').val();
			else var eventType = '';
			if (linkedclientsCount(true, true) && pastCurrentMode && (eventType == 'class' || (eventType == 'single-service' && scriptSource == 'calendar' && eventObj.find('input[name="appointStatusOpt"]').val() == 'Confirmed'))) {
				toggleAttendanceTab('show')
			} else toggleAttendanceTab('hide')
		} else {
			/*var tab = classModal.find('ul.nav a[href="#classAttendance"]');

			if(action == 'show')
				tab.show();
			else
				tab.hide();*/
			toggleTab(classModal, 'classAttendance', action);
		}
	}
	/* end: Toggle client attendace tab as per time mode */
	/* start: Unlink client class */
	function unlinkClientClass(clientId) {
		if (!classClients[clientId].ifMakeUp) $(classClients).removeProp(clientId);
		else if (classClients[clientId].amount) fetchClientsForTypeahead('update', 'book_team');
		classModal.find('div#linkedclientList > a[data-id="' + clientId + '"]').remove();
		classModal.find('div#eventClientAttendance-' + clientId).remove();
		toggleAttendanceTab()
		updateBookedclientQuickInfo();
		toggleEventOverBookAlert();
		if (typeof clientForm != 'undefined') clearClientForm(clientForm, true);
		toggleClassClientLnks('create')
		$("#raiseMakeUpModel").modal('hide');
		//$("#raiseMakeUpModel").modal('hide');
		//var totalClients=$('#classModal').find('div#linkedclientList').children().length;
		disabledEnabledCancleClsBtn()
	}
	/* end: Unlink client class */
	/* start: Change client attendance over UI */
	function changeclientAttendanceUi(clientId, clientNewAttendance) {
		var clientAttendanceIconHtml = $('#linkedclientList a[data-id="' + clientId + '"] span.clientAttendanceIcon');
		if (clientAttendanceIconHtml.length) {
			clientAttendanceIconHtml.tooltipster('content', clientNewAttendance).find('i').attr('class', calcClientAttendanceIcon(clientNewAttendance));
			classClients[clientId].attendance = clientNewAttendance;
		}
	}
	/* end: Change client attendance over UI */
	/* start: Toggle team sales process mark over client record tab */
	function toggleSalesMark(clientId, isSalesProcEvent) {
		var salesMark = $('#linkedclientList a[data-id="' + clientId + '"] span.salesMark');
		if (salesMark.length) {
			if (isSalesProcEvent) salesMark.removeClass('hidden')
				else salesMark.addClass('hidden')
			}
	}
	/* end: Toggle team sales process mark over client record tab */
	/* start: Change client attendance */
	function changeclientAttendance(clientId, clientNewAttendance, ifRescheduleClient,attendMark=0) {
		changeclientAttendanceUi(clientId, clientNewAttendance);
		var formData = {};
		formData['eventId'] = classModal.find('input[name="eventId"]').val();
		formData['clientAttendance'] = classClients[clientId].attendance;
		formData['clientIfMakeUp'] = classClients[clientId].ifMakeUp;
		formData['makeupNotes'] = classClients[clientId].makeupNotes;
		formData['purpose'] = classClients[clientId].purpose;
		formData['amount'] = classClients[clientId].amount;
		if(attendMark == 1){
			formData['attendMark'] =attendMark;
		}
		var time = moment().format('YYYY-MM-DD HH:mm:ss');
		var eventtime =classModal.find('input[name="eventTime"]').val();
		var currDateTime = classModal.find('.eventDateDisp').text()+ ' '+ eventtime;
		var eventDateTime =  moment(currDateTime).format('YYYY-MM-DD HH:mm:ss');
		// console.log(time,currDateTime,eventDateTime,clientNewAttendance);
		if(currDateTime > eventDateTime && resechedule == "resechedule" && clientNewAttendance == 'Did not show'){
            resechedule = "";
			if(loggedInUser.type == 'Admin' || loggedInUser.type == 'Super Admin'){
				swal({
					title: "The Event is in past. Still you want to reschedule?",
					allowOutsideClick: false,
					showCancelButton: true,
					confirmButtonText: 'Yes',
					confirmButtonColor: '#ff4401',
					cancelButtonText: "No"
				}, 
				function(isConfirm){
					if(isConfirm){
						toggleWaitShield('show', 'changeAttend');

						var bookingType = getBookingType();
						if (bookingType == 'class') {
							formData['clientId'] = [clientId];
							//formData['action'] = classClients[clientId].action;
							var ajaxAction = 'classes/past-update';
						} else if (bookingType == 'service') {
							/*if(isSalesProcessEvent()){
								if(typeof ifRescheduleClient != 'undefined' && ifRescheduleClient)
									formData['breakSalesProcess'] = false;
								else
									formData['breakSalesProcess'] = true;
							}*/
							var ajaxAction = 'single-service/change-attendance';
						}
						$.post(public_url + 'staffevents/' + ajaxAction, formData, function(data) {
							var data = JSON.parse(data);
							$('#raiseMakeUpModelSubmit').prop('disabled', false);
							if (data.status == "added") {
								reloadEvents = true;
								$('#modifiedRow').data('prev-val', clientNewAttendance).removeAttr('id')
								if (classClients[clientId].ifMakeUp) {
									//$("#raiseMakeUpModel").modal('hide');
									if (bookingType == 'class') {
										unlinkClientClass(clientId);
										$(classClients).removeProp(clientId);
										leaveAttendanceTab();
									} else if (bookingType == 'service') {
										$("#raiseMakeUpModel").modal('hide');
										classModal.modal('hide');
									}
								}
								if ('isSalesProcEvent' in data) toggleSalesMark(clientId, data['isSalesProcEvent'][clientId])
									if (bookingType == 'service') {
										if ('salesProcessEvent' in data && data.salesProcessEvent != 'null' && data.salesProcessEvent && data.salesProcessEvent != 'false') {
											updateUiForSalesProcess();
										} else {
											toggleTab(classModal, 'classReccur', 'show');
											toggleTab(classModal, 'classNotes', 'show');
										}
									}
									if (typeof ifRescheduleClient != 'undefined') enterReschecduleClientMode();
									if(data.salesProcessStepNumber){
										swal({
											title: 'Do you want to '+data.salesProcessStepName,
											allowOutsideClick: true,
											showCancelButton: true,
											confirmButtonText: 'Yes',
											confirmButtonColor: '#ff4401',
											cancelButtonText: "No"
										}, 
										function(isConfirm){
											isSalesProcessBookStep = false;
											if(isConfirm){
												clientClassData = classClients;
												if(data.salesProcessStepName != undefined || data.salesProcessStepName != ''){
												if(data.salesProcessStepName == 'Book Team?'){
													enterSalesProcessBookMode(data.salesProcessStepNumber,clientId,type="class",isSalesProcessBookStep = true);
												}else{
													enterSalesProcessBookMode(data.salesProcessStepNumber,clientId,type="service",isSalesProcessBookStep = true);
												}
												}
												
												
											}
										});
									}else if(data.salesAttendStep){
										swal({
											title: "Do you want to mark step " +data.salesProcessStepName+" as complete?",
											allowOutsideClick: true,
											showCancelButton: true,
											confirmButtonText: 'Yes',
											confirmButtonColor: '#ff4401',
											cancelButtonText: "No"
										}, 
										function(isConfirm){
											if(isConfirm){
												var formData = {};
												formData['action'] = 'complete';
												formData['clientId'] = clientId;
												formData['stepNumb'] = data.salesAttendStep;
												formData['session'] = 'service';
												updateSalesStepAttendAjax(formData);
											}
										});
									}
								}
								toggleWaitShield('hide', 'changeAttend');
						});
					}
				});
			}
			else{
				swal({
					title: "Unauthorised action",
					allowOutsideClick: true,
					showCancelButton: true,
					confirmButtonText: 'Yes',
					confirmButtonColor: '#ff4401',
					cancelButtonText: "No"
				}, 
				function(isConfirm){
                   swal.close();
				});
			}
		}else{
			console.log('else');
			toggleWaitShield('show', 'changeAttend');

			var bookingType = getBookingType();
			if (bookingType == 'class') {
				formData['clientId'] = [clientId];
				//formData['action'] = classClients[clientId].action;
				var ajaxAction = 'classes/past-update';
			} else if (bookingType == 'service') {
				/*if(isSalesProcessEvent()){
					if(typeof ifRescheduleClient != 'undefined' && ifRescheduleClient)
						formData['breakSalesProcess'] = false;
					else
						formData['breakSalesProcess'] = true;
				}*/
				var ajaxAction = 'single-service/change-attendance';
			}
			$.post(public_url + 'staffevents/' + ajaxAction, formData, function(data) {
				var data = JSON.parse(data);
				$('#raiseMakeUpModelSubmit').prop('disabled', false);
				if (data.status == "added") {
					reloadEvents = true;
					$('#modifiedRow').data('prev-val', clientNewAttendance).removeAttr('id')
					if (classClients[clientId].ifMakeUp) {
						//$("#raiseMakeUpModel").modal('hide');
						if (bookingType == 'class') {
							unlinkClientClass(clientId);
							$(classClients).removeProp(clientId);
							leaveAttendanceTab();
						} else if (bookingType == 'service') {
							$("#raiseMakeUpModel").modal('hide');
							classModal.modal('hide');
						}
					}
					if ('isSalesProcEvent' in data) toggleSalesMark(clientId, data['isSalesProcEvent'][clientId])
						if (bookingType == 'service') {
							if ('salesProcessEvent' in data && data.salesProcessEvent != 'null' && data.salesProcessEvent && data.salesProcessEvent != 'false') {
								updateUiForSalesProcess();
							} else {
								toggleTab(classModal, 'classReccur', 'show');
								toggleTab(classModal, 'classNotes', 'show');
							}
						}
						if (typeof ifRescheduleClient != 'undefined') enterReschecduleClientMode();
						if(data.salesProcessStepNumber){
							swal({
								title: 'Do you want to '+data.salesProcessStepName,
								allowOutsideClick: true,
								showCancelButton: true,
								confirmButtonText: 'Yes',
								confirmButtonColor: '#ff4401',
								cancelButtonText: "No"
							}, 
							function(isConfirm){
								isSalesProcessBookStep = false;
								if(isConfirm){
									clientClassData = classClients;
									if(data.salesProcessStepName != undefined || data.salesProcessStepName != ''){
									if(data.salesProcessStepName == 'Book Team?'){
										enterSalesProcessBookMode(data.salesProcessStepNumber,clientId,type="class",isSalesProcessBookStep = true);
									}else{
										enterSalesProcessBookMode(data.salesProcessStepNumber,clientId,type="service",isSalesProcessBookStep = true);
									}
									}
									
									
								}
							});
						}else if(data.salesAttendStep){
							swal({
								title: "Do you want to mark step " +data.salesProcessStepName+" as complete?",
								allowOutsideClick: true,
								showCancelButton: true,
								confirmButtonText: 'Yes',
								confirmButtonColor: '#ff4401',
								cancelButtonText: "No"
							}, 
							function(isConfirm){
								if(isConfirm){
									var formData = {};
									formData['action'] = 'complete';
									formData['clientId'] = clientId;
									formData['stepNumb'] = data.salesAttendStep;
									formData['session'] = 'service';
									updateSalesStepAttendAjax(formData);
								}
							});
						}
					}
					toggleWaitShield('hide', 'changeAttend');
			});
				swal.close();

		}
		
	}

	function updateSalesStepAttendAjax(formData){
		formData['skipManageSales'] = 1;

	if(formData['session'] = 'class'){
		$.post(public_url+'clients/sales-process/update', formData, function(data){
			var data = JSON.parse(data);
			if(data.status == "updated"){
				formData['skipManageSales'] = 0;
				var clientNewAttendance = 'Attended';
				var attendMark = 1;
				
				changeclientAttendance(formData['clientId'],clientNewAttendance,undefined,attendMark);
			}
		})
	}else if(formData['session'] = 'service'){
		$.post(public_url+'clients/sales-process/update', formData, function(data){
			var data = JSON.parse(data);
			if(data.status == "updated"){
				formData['skipManageSales'] = 0;
				var clientNewAttendance = 'Attended';
				var attendMark = 1;
				var skipManageSales = 1;
				changeclientAttendance(formData['clientId'],clientNewAttendance,undefined,attendMark);
			}
		})	
	}
	}
	
	/* end: Change client attendance */
	/* start: Toggle class over book alert */
	function toggleEventOverBookAlert(action) {
		if (typeof action == 'undefined') {
			if (ifClientExceedEventCapacity('add')) toggleEventOverBookAlert('show')
				else toggleEventOverBookAlert('hide')
			} else {
				clearNotific('eventOverBook');
				if (action == 'show') {
					var clientsTab = classModal.find('div#classClients');
					showNotific(prepareAlert('warning', "This " + getBookingType() + " has reached its capacity", 'eventOverBook'), clientsTab);
				}
			}
		}
		/* end: Toggle class over book alert */
		/* start: Set class time as per selected */
		function validateStaff(form) {
			var isFormValid = true;
			var staffDd = form.find('select[name="staff"]');
			if (!validateField(form, 'staff') || !isFieldMarkedAsAvail(staffDd)) {
				setFieldInvalid(staffDd.closest('.form-group'), 'Please select new staff.')
				isFormValid = false;
			}
			return isFormValid;
		}

		function validateArea(form) {
			var isFormValid = true;
			var areaDd = form.find('select[name="modalLocArea"]');
			if (!validateField(form, 'area') || !isFieldMarkedAsAvail(areaDd)) {
				isFormValid = false;
				setFieldInvalid(areaDd.closest('.form-group'), 'Please select new area.')
			}
			return isFormValid;
		}

		function validateField(form, fieldName) {
			if (fieldName == 'capacity') var field = form.find('input[name="classCap"]');
			else if (fieldName == 'price') {
				var bookingType = getBookingType();
				if (bookingType == 'class') var field = form.find('input[name="classPrice"]');
				else if (bookingType == 'service') var field = form.find('input[name="servicePrice"]');
			} else if (fieldName == 'duration') {
				var bookingType = getBookingType();
				if (bookingType == 'class') var field = form.find('select[name="classDur"]');
				else if (bookingType == 'service') var field = form.find('select[name="serviceDur"]');
			} else if (fieldName == 'class') var field = form.find('select[name="staffClass"]');
			else if (fieldName == 'service') var field = form.find('select[name="staffservice"]');
			else if (fieldName == 'staff') var field = form.find('select[name="staff"]');
			else if (fieldName == 'area') var field = form.find('select[name="modalLocArea"]');
			else if (fieldName == 'autoExpire') var field = form.find('select[name="autoExpireAppointDur"]');
			return field.valid();
		}
		/* start: Save field */
		function saveField(field) {
			var form = classModal.find('form'),
			isFormValid = true;
			if (field == 'class' || field == 'service' || field == 'duration' || field == 'capacity' || field == 'price' || field == 'autoExpire') {
				if (!validateField(form, field)) isFormValid = false;
			} else if (field == 'staff') {
				if (!validateStaff(form)) isFormValid = false;
			} else if (field == 'area') {
				if (!validateArea(form)) isFormValid = false;
			} else if (field == 'resource') {
				if (!validateResourceForm()) isFormValid = false;
				var quantDd = $('#resource-form .resource-clone-class:not(.hidden) select.item-op');
				if (quantDd.length) {
					quantDd.each(function() {
						if (!isFieldMarkedAsAvail($(this))) {
							var formGroup = $(this).closest('.resItemLocDd2');
							setFieldInvalid(formGroup, 'Quantity changed', formGroup.children('.help-block'));
							isFormValid = false;
						}
					})
				}
			}
			moveErrorMsg(classModal);
			if (isFormValid) {
				toggleWaitShield("show");
				var formData = {};
				form.find(':input').each(function() {
					formData[$(this).attr('name')] = $(this).val()
				})
				formData['step'] = getActiveTab();
				formData['calendEndDate'] = getCalendEndDatePriorDate();
				formData['field'] = field;
				var eventDate = setEventDate(classModal, 'submit');
				formData['classDate'] = eventDate.date;
				formData['classDay'] = eventDate.day;
				formData['classTime'] = timeStringToDbTime(formData['eventTime']);
				if (field != 'area' && !isFieldMarkedAsAvail(form.find('select[name="modalLocArea"]'))) {
					formData['modalLocArea'] = '';
				}
				if (field != 'staff' && !isFieldMarkedAsAvail(form.find('select[name="staff"]'))) {
					formData['staff'] = '';
				}
				var bookingType = getBookingType();
				if (bookingType == 'class') {
					formData['ifMarkFav'] = form.find("input[name='ifMarkFav']:checked").val();
					if (formData['ifMarkFav']) formData['markFavTill'] = moment(formData['markFavTill'], 'D MMM YYYY').format("YYYY-MM-DD");
				} else if (bookingType == 'service') {
					if (isSalesProcessMode()) formData['salesProcessStepNumb'] = parent.salesProcessStepNumb;
					formData['ifAutoExpireAppoint'] = form.find("input[name='ifAutoExpireAppoint']:checked").val()
					if (formData['ifAutoExpireAppoint'] == 1) {
						if (formData['autoExpireAppointDur'] == 'Custom') formData['autoExpireDatetime'] = moment(formData['autoExpireAppointDurDate'] + ' ' + formData['autoExpireAppointDurTime']).format("YYYY-MM-DD H:mm:ss");
						else {
							var bookingDatetime = moment(formData['classDate'] + ' ' + formData['classTime'], 'ddd, D MMM YYYY');
							var autoExpiredatetime = moment(formData['classDate'] + ' ' + formData['classTime']);
						//formData['autoExpireDatetime'] = moment().add(formData['autoExpireAppointDur'], 'hours').format("YYYY-MM-DD H:mm:ss")
						formData['autoExpireDatetime'] = autoExpiredatetime.subtract(formData['autoExpireAppointDur'], 'hours').format("YYYY-MM-DD H:mm:ss");
					}
				}
			}
			var isRepeating = eventObj.find('input[name="isRepeating"]').val()
			if (isRepeating == 1) formData['targetEvents'] = form.find('input[name="targetEvents"]').val();
			else formData['targetEvents'] = '';
			formData['historyText'] = '';
			if (field == 'date' || field == 'time') {
				var oldClassdate = eventObj.find('input[name="date"]').val(),
				oldClassTime = eventObj.find('input[name="startTime"]').val();
				if (oldClassdate != formData['classDate'] || oldClassTime != formData['classTime']) {
					var currDateTime = classModal.find('.eventDateDisp').text() + ' ' + formData['eventTime'];
					// formData['historyText'] += 'Reschedule from ' + dbDateToDateString(oldClassdate) + ' ' + dbTimeToTimeString(oldClassTime) + ' to ' + currDateTime + '|';
					formData['historyText'] += (bookingType == 'class' ? 'Class' : 'Service') + ' shifted from ' + dbDateToDateString(oldClassdate) + ' ' + dbTimeToTimeString(oldClassTime) + ' to ' + currDateTime + '|';
				}
			}
			if (field == 'class' || field == 'service' || field == 'duration' || field == 'date' || field == 'time' || field == 'staff') {
				if (formData['staff']) {
					if (eventObj.find('input[name="staffId"]').val() != formData['staff']) formData['historyText'] += 'Staff changed from ' + eventObj.find('input[name="staffName"]').val() + ' to ' + classModal.find('select[name="staff"] option:selected').text() + '|';
				}
			}
			if (bookingType == 'class') {
				if (field == 'class') {
					if (eventObj.find('input[name="classId"]').val() != formData['staffClass']) formData['historyText'] += 'Class changed from ' + eventObj.find('input[name="className"]').val() + ' to ' + classModal.find('select[name="staffClass"] option:selected').text() + '|';
				}
				if (field == 'class' || field == 'duration') {
					if (formData['classDur']) {
						var oldClassDur = eventObj.find('input[name="duration"]').val();
						if (oldClassDur != formData['classDur']) formData['historyText'] += 'Class duration changed from ' + oldClassDur + ' min to ' + formData['classDur'] + ' min|';
					}
				}
				if (field == 'class' || field == 'capacity') {
					if (formData['classCap']) {
						var oldClassCap = eventObj.find('input[name="capacity"]').val();
						if (oldClassCap != formData['classCap']) formData['historyText'] += 'Class capacity changed from ' + oldClassCap + ' to ' + formData['classCap'] + '|';
					}
				}
				if (field == 'class' || field == 'price') {
					if (formData['classPrice']) {
						var oldClassPrice = eventObj.find('input[name="price"]').val();
						if (oldClassPrice != formData['classPrice']) formData['historyText'] += 'Class price changed from $' + oldClassPrice + ' to $' + formData['classPrice'] + '|';
					}
				}
			} else if (bookingType == 'service') {
				if (field == 'status') {
					oldAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val();
					if (oldAppointStatusOpt != formData['appointStatusOpt']) {
						formData['historyText'] += 'Booking status changed from ' + oldAppointStatusOpt + ' to ' + formData['appointStatusOpt'] + '|';
					}
				}
				if (field == 'service') {
					if (eventObj.find('input[name="serviceId"]').val() != formData['staffservice']) formData['historyText'] += 'Service changed from ' + eventObj.find('input[name="serviceName"]').val() + ' to ' + classModal.find('select[name="staffservice"] option:selected').text() + '|';
				}
				if (field == 'service' || field == 'duration') {
					if (formData['serviceDur']) {
						var oldServiceDur = eventObj.find('input[name="duration"]').val();
						if (oldServiceDur != formData['serviceDur']) formData['historyText'] += 'Service duration changed from ' + oldServiceDur + ' min to ' + formData['serviceDur'] + ' min|';
					}
				}
				if (field == 'service' || field == 'price') {
					if (formData['servicePrice']) {
						var oldServicePrice = eventObj.find('input[name="price"]').val();
						if (oldServicePrice != formData['servicePrice']) formData['historyText'] += 'Service price changed from $' + oldServicePrice + ' to $' + formData['servicePrice'] + '|';
					}
				}
			}
			if (bookingType == 'class') 
				var ajaxAction = 'classes/edit';
			else if (bookingType == 'service') 
				var ajaxAction = 'single-service/edit';

			$.post(public_url + 'staffevents/' + ajaxAction, formData, function(data) {
				var data = JSON.parse(data);
				if (data.status == "added") {
					classModal.find('.submitBooking').prop('disabled', false);
					reloadEvents = true;
					var bookingType = getBookingType();
					getBookingDetails(formData['eventId'], bookingType, function(eventHtml) {
						eventObj.find('input').remove();
						var elem = eventObj.find('.calendEvent');
						if (!elem.length) elem = eventObj;
						elem.prepend(eventHtml);
						if (isSalesProcessEvent()) {
							updateUiForSalesProcess();
						}
					})
					if (bookingType == 'class' && 'newConfirmedClients' in data && data.newConfirmedClients.length) {
						$.each(data.newConfirmedClients, function(key, val) {
							confirmClientUi(val)
						});
					}
					if ('isSalesProcEvent' in data) {
						$.each(data.isSalesProcEvent, function(clientId, isIt) {
							toggleSalesMark(clientId, isIt)
						})
					}
					updateBookedclientQuickInfo();
					toggleEventOverBookAlert();
					if (pastCurrentMode && bookingType == 'service' && field == 'status') {
						if (formData['appointStatusOpt'] == 'Pencilled-In') {
							toggleTab(classModal, 'classAttendance', 'hide');
						} else if (formData['appointStatusOpt'] == 'Confirmed') {
							toggleTab(classModal, 'classAttendance', 'show');
						}
					}
					
				} else if(data.status == 'error') {
					classModal.find('.submitBooking').prop('disabled', true);
					$.each(data.errorData, function() {
							$.each(this, function(errorType, message) {
								var errorMessage = '';
								// if (errorType == "areaUnavail") {
								// 	var areaDd = form.find('select[name="modalLocArea"]')
								// 	setFieldInvalid(areaDd.closest('.form-group'), message)
								// } else
								if (errorType == "workingHourUnavail") {
									var staffDd = form.find('select[name="staff"]')
									setFieldInvalid(staffDd.closest('.form-group'), message)
								} else if (errorType == "businessClosed") {
									var field = form.find("span.eventDateDisp"),
									formGroup = field.closest('.form-group');
									setFieldInvalid(formGroup, 'Business is closed on specified date', formGroup.find('.placeErrMsg'))
								} else if (errorType == 'areaStaffUnavail') {
									errorMessage =  'Areas and Staffs are unavailable at specified hours.';
								} else if (errorType == 'staffUnavail') {
									errorMessage =  'Staffs are unavailable at specified hours.';
								} else if (errorType == 'areaUnavail') {
									errorMessage =  'Areas are unavailable at specified hours.';
								} else if (errorType == 'classUnavail') {
									errorMessage =  'Class not available at specified hours.';
								} else if (errorType == 'unableRecure') {
									errorMessage = message;
								} else {
									errorMessage != null && message != '' ? message : 'Unable to update class.';
								}

								if(errorType != "workingHourUnavail" || errorType != "businessClosed") {
									swal({
										title: errorMessage,
										allowOutsideClick: false,
										showCancelButton: false,
										confirmButtonText: 'Okay',
										confirmButtonColor: '#ff4401',
										type: 'warning',
									});
								}
							});
						});
				}
				toggleWaitShield("hide");
			});
		}
	}
	/* end: Save field */
	/* start: Submit class modal data */
	function submitClassModal(form, targetTabId) {
		toggleWaitShield("show", 'submitBooking');
		if (form == null) form = classModal.find('form');
		var formData = {},
		activeTab = getActiveTab();
		var bookingType = getBookingType();
		form.find(':input').each(function() {
			formData[$(this).attr('name')] = $(this).val()
		})
		if(formData['clientId'] == '' && idClient != ''){
			formData['clientId'] =	idClient;
		}
		if(formData['classClients'] == undefined && isSalesProcessBookStep ==  true){
		formData['newClassClient'] = clientClassData;
		// formData['classClients'] =formData['newClassClient'];
		}
		formData['step'] = activeTab;
		formData['calendEndDate'] = getCalendEndDatePriorDate();
		if (activeTab == 'classDetails') {
			var eventDate = setEventDate(classModal, 'submit');
			formData['classDate'] = eventDate.date;
			formData['classDay'] = eventDate.day;
			formData['classTime'] = timeStringToDbTime(formData['eventTime']);
			if (bookingType == 'class') {
				formData['ifMarkFav'] = form.find("input[name='ifMarkFav']:checked").val();
				if (formData['ifMarkFav']) formData['markFavTill'] = moment(formData['markFavTill'], 'D MMM YYYY').format("YYYY-MM-DD");
			} else if (bookingType == 'service') {
				if (isSalesProcessMode()) {
					formData['salesProcessStepNumb'] = parent.salesProcessStepNumb;
					formData['classClients'] = classClients;
				} else if (isCreateServiceMakeUpMode()) {
					formData['origEventId'] = parent.makeUpEventId;
					formData['classClients'] = classClients;
				}
				formData['ifAutoExpireAppoint'] = form.find("input[name='ifAutoExpireAppoint']:checked").val()
				if (formData['ifAutoExpireAppoint'] == 1) {
					if (formData['autoExpireAppointDur'] == 'Custom') formData['autoExpireDatetime'] = moment(formData['autoExpireAppointDurDate'] + ' ' + formData['autoExpireAppointDurTime']).format("YYYY-MM-DD H:mm:ss");
					else {
						var autoExpiredatetime = moment(formData['classDate'] + ' ' + formData['classTime']);
						//formData['autoExpireDatetime'] = moment().add(formData['autoExpireAppointDur'], 'hours').format("YYYY-MM-DD H:mm:ss")
						formData['autoExpireDatetime'] = autoExpiredatetime.subtract(formData['autoExpireAppointDur'], 'hours').format("YYYY-MM-DD H:mm:ss");
					}
				}
			}
		} else if (activeTab == 'classReccur') {
			if (formData['eventRepeat'] == 'Daily' || formData['eventRepeat'] == 'Weekly' || formData['eventRepeat'] == 'Monthly') {
				formData['eventRepeatEnd'] = form.find('input[name="eventRepeatEnd"]:checked').val();
				if (formData['eventRepeatEnd'] == 'On') formData['eventRepeatEndOnDate'] = moment(formData['eventRepeatEndOnDate'], 'D MMM YYYY').format("YYYY-MM-DD");
				/*else if(formData['eventRepeatEnd'] == 'Never')
					formData['calendEndDate'] = getCalendEndDatePriorDate();*/ //getCalendEndDate();
					if (formData['eventRepeat'] == 'Weekly') {
						var temp = [],
						eventRepeatWeekdays = form.find('.eventRepeatWeekdays input[type="checkbox"]:checked');
						if (eventRepeatWeekdays.length) {
							eventRepeatWeekdays.each(function() {
								temp.push($(this).val());
							})
							formData['eventRepeatWeekdays'] = temp;
						}
					}
				}
			}
			console.log(isModalModeEdit());
		//formData['eventDateTimeEmail'] = classModal.find('.eventDateDisp').text()+' '+formData['eventTime'];
		if (activeTab == 'classDetails' && isModalModeEdit() /*formData['eventId'] != null && formData['eventId'] != ''*/ ) {
			formData['historyText'] = '';
			if (eventObj.find('input[name="staffId"]').val() != formData['staff']) formData['historyText'] += 'Staff changed from ' + eventObj.find('input[name="staffName"]').val() + ' to ' + classModal.find('select[name="staff"] option:selected').text() + '|';
			var oldClassdate = eventObj.find('input[name="date"]').val(),
			oldClassTime = eventObj.find('input[name="startTime"]').val();
			if (oldClassdate != formData['classDate'] || oldClassTime != formData['classTime']) {
				//formData['historyText'] += 'Reschedule from '+dbDateToDateString(oldClassdate)+' '+dbTimeToTimeString(oldClassTime)+' to '+formData['eventDateTimeEmail']+'|';
				var currDateTime = classModal.find('.eventDateDisp').text() + ' ' + formData['eventTime'];
				formData['historyText'] += 'Reschedule from ' + dbDateToDateString(oldClassdate) + ' ' + dbTimeToTimeString(oldClassTime) + ' to ' + currDateTime + '|';
			}
			if (bookingType == 'class') {
				if (eventObj.find('input[name="classId"]').val() != formData['staffClass']) formData['historyText'] += 'Class changed from ' + eventObj.find('input[name="className"]').val() + ' to ' + classModal.find('select[name="staffClass"] option:selected').text() + '|';
				var oldClassDur = eventObj.find('input[name="duration"]').val();
				if (oldClassDur != formData['classDur']) formData['historyText'] += 'Class duration changed from ' + oldClassDur + ' min to ' + formData['classDur'] + ' min|';
				var oldClassCap = eventObj.find('input[name="capacity"]').val();
				if (oldClassCap != formData['classCap']) formData['historyText'] += 'Class capacity changed from ' + oldClassCap + ' to ' + formData['classCap'] + '|';
				var oldClassPrice = eventObj.find('input[name="price"]').val();
				if (oldClassPrice != formData['classPrice']) formData['historyText'] += 'Class price changed from $' + oldClassPrice + ' to $' + formData['classPrice'] + '|';
			} else if (bookingType == 'service') {
				oldAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val();
				if (oldAppointStatusOpt != formData['appointStatusOpt']) {
					formData['historyText'] += 'Booking status changed from ' + oldAppointStatusOpt + ' to ' + formData['appointStatusOpt'] + '|';
				}
				if (eventObj.find('input[name="serviceId"]').val() != formData['staffservice']) formData['historyText'] += 'Service changed from ' + eventObj.find('input[name="serviceName"]').val() + ' to ' + classModal.find('select[name="staffservice"] option:selected').text() + '|';
				var oldServiceDur = eventObj.find('input[name="duration"]').val();
				if (oldServiceDur != formData['serviceDur']) formData['historyText'] += 'Service duration changed from ' + oldServiceDur + ' min to ' + formData['serviceDur'] + ' min|';
				var oldServicePrice = eventObj.find('input[name="price"]').val();
				if (oldServicePrice != formData['servicePrice']) formData['historyText'] += 'Service price changed from $' + oldServicePrice + ' to $' + formData['servicePrice'] + '|';
			}
		}
		if (isModalModeEdit()) {
			if (bookingType == 'class') 
				var ajaxAction = 'classes/edit';
			else if (bookingType == 'service') 
				var ajaxAction = 'single-service/edit';
		} else {
			if (bookingType == 'class') 
				var ajaxAction = 'classes/create';
			else if (bookingType == 'service') 
				var ajaxAction = 'single-service/create';
		}
		$.post(public_url + 'staffevents/' + ajaxAction, formData, function(data) {
			var data = JSON.parse(data);
			if (data.status == "added") {
				if(isModalModeEdit()){
				var notes=classModal.find('textarea[name="classNote"]').val(eventObj.find('input[name="notes"]').val());
				}
				if(notes != '' && notes != undefined){
					swal({
						title: 'Data added successfully',
						allowOutsideClick: true,
						showCancelButton: false,
						confirmButtonText: 'Okay',
						confirmButtonColor: '#ff4401',
						html:true,
						cancelButtonText: "No"
					}, 
					function(isConfirm){
						swal.close();
						if (isModalModeEdit()) {
							if (typeof targetTabId != 'undefined' && targetTabId) {
								reloadEvents = true;
								getBookingDetails(formData['eventId'], bookingType, function(eventHtml) {
									eventObj.find('input').remove();
									var elem = eventObj.find('.calendEvent');
									if (!elem.length) elem = eventObj;
									elem.prepend(eventHtml);
									if (isSalesProcessEvent()) {
										updateUiForSalesProcess();
									}
								})
								if (bookingType == 'class' && 'newConfirmedClients' in data && data.newConfirmedClients.length) {
									$.each(data.newConfirmedClients, function(key, val) {
										confirmClientUi(val)
									});
								}
								updateBookedclientQuickInfo();
								toggleEventOverBookAlert();
								switchTabTo(targetTabId, classModal)
							} else submitClassModalSuccess(data);
						} else {
							data.type = bookingType;
							submitClassModalSuccess(data);
						}
						
						
					})
				}else if (isModalModeEdit()) {
					if (typeof targetTabId != 'undefined' && targetTabId) {
						reloadEvents = true;
						getBookingDetails(formData['eventId'], bookingType, function(eventHtml) {
							eventObj.find('input').remove();
							var elem = eventObj.find('.calendEvent');
							if (!elem.length) elem = eventObj;
							elem.prepend(eventHtml);
							if (isSalesProcessEvent()) {
								updateUiForSalesProcess();
							}
						})
						if (bookingType == 'class' && 'newConfirmedClients' in data && data.newConfirmedClients.length) {
							$.each(data.newConfirmedClients, function(key, val) {
								confirmClientUi(val)
							});
						}
						updateBookedclientQuickInfo();
						toggleEventOverBookAlert();
						switchTabTo(targetTabId, classModal)
					} else submitClassModalSuccess(data);
				} else {
					data.type = bookingType;
					submitClassModalSuccess(data);
				}
			} else if (data.status == "error") {
				$.each(data.errorData, function() {
					$.each(this, function(errorType, message) {
						if (errorType == "workingHourUnavail" || errorType == "businessClosed") {
							var field = form.find("span.eventDateDisp"),
							formGroup = field.closest('.form-group');
							setFieldInvalid(field.closest('.form-group'), message, formGroup.find('.placeErrMsg'))
						} else if (errorType == "staffNotLinkedToArea") {
							var field = form.find("span.staffErrMsg"),
							formGroup = field.closest('.form-group');
							setFieldInvalid(formGroup, 'Staff not linked to the location area specified', field);
						}else if (errorType == "areaDoesnotLinkedToStaff") {
							var field = form.find("span.areaErrMsg"),
							formGroup = field.closest('.form-group');
							setFieldInvalid(formGroup, 'Please Select Area', field);
						}else if (errorType == "clientUnavail") {
							showNotific(prepareNotific('error', 'Client is busy at specified hours'), classModal.find('div#classDetails'));
						} else if (errorType == "classUnavail") {
							showNotific(prepareNotific('error', 'Class is busy at specified hours.'), classModal.find('div#classDetails'));
						} else if (errorType == 'unableRecure') {
							swal({
								title: message,
								allowOutsideClick: false,
								showCancelButton: false,
								confirmButtonText: 'Okay',
								confirmButtonColor: '#ff4401',
								type: 'warning',
							});
						}
					});
				});
			}
			toggleWaitShield("hide", 'submitBooking');
		});
	}
	/* end: Submit class modal data */
	/* start: Initialize clients typeahead */
	function initTypeahead(data, mode) {
		var fields = $('.clientList');
		if (typeof mode != 'undefined' && mode == 'update') fields.typeahead("destroy");
		fields.typeahead({
			source: data,
			items: 'all',
			afterSelect: function(selection) {
				populateClientDetails(selection, $(this).prop('$element'), 'new_client');
			}
		})
	}
	/* end: Initialize clients typeahead */
	function updateUiForSalesProcess() {
		var serviceDd = $('select[name="staffservice"]', classModal),
		selServ = eventObj.find('input[name="serviceId"]').val();
		serviceDd.find('option:not([value=], [value=' + selServ + '])').remove();
		serviceDd.selectpicker('refresh');
		if (eventObj.find('input[name="appointStatusOpt"]').val() != 'Pencilled-In') disableAppointStatuselectable();
		toggleTab(classModal, 'classReccur', 'hide');
		toggleTab(classModal, 'classNotes', 'show');
	}

	function isSalesProcessEvent() {
		if (!$.isEmptyObject(eventObj) && getBookingType() == 'service') {
			var salesProcessEvent = eventObj.find('input[name="salesProcessEvent"]').val();
			if (salesProcessEvent != 'null' && salesProcessEvent && salesProcessEvent != 'false') return true;
		}
		return false;
	}
	/* start: Check if calendar subview is in remove session(team or indiv booking) Mode */
	function isRemoveTeamMode() {
		if ($('input[name="removeStepType"]').val()) return true;
		return false;
	}
	/* end: Check if calendar subview is in remove session(team or indiv booking) Mode */
	function removeStepType() {
		return $('input[name="removeStepType"]').val()
	}

	function removeBookType() {
		return $('input[name="removeBookType"]').val()
	}
	/* start: Check if calendar subview is in Sales Process Mode */
	function isSalesProcessMode() {
		if (typeof parent.salesProcessStepNumb != 'undefined' && parent.salesProcessStepNumb) return true;
		return false;
	}
	/* end: Check if calendar subview is in Sales Process Mode */
	/* start: Get type of Sales Process Mode */
	function salesProcessType(checkMode) {
		if (typeof checkMode != 'undefined') var salesMode = isSalesProcessMode();
		else var salesMode = true;
		if (salesMode) {
			if (parent.salesProcessStepNumb == 2 || parent.salesProcessStepNumb == 4 || $.inArray(parent.salesProcessStepNumb, indivBookingSteps()) > -1) return 'service';
			//else if(parent.salesProcessStepNumb == 6 || parent.salesProcessStepNumb == 7 || parent.salesProcessStepNumb == 8)
			else if ($.inArray(parent.salesProcessStepNumb, teamBookingSteps()) > -1) return 'class';
		}
		return '';
	}
	/* end: Check if calendar subview is in Sales Process Mode */
	/* start: Check if calendar subview is in client rescheduling Mode */
	function isRescheduleClientMode() {
		if (typeof parent.rescheduleClientMode != 'undefined' && parent.rescheduleClientMode) return true;
		return false;
	}
	/* end: Check if calendar subview is in client rescheduling Mode */
	/* start: Check if calendar subview is in create make up Mode */
	function isCreateMakeUpMode() {
		if (typeof parent.createMakeUpMode != 'undefined' && parent.createMakeUpMode) return true;
		return false;
	}
	/* end: Check if calendar subview is in create make up Mode */
	/* start: Check if calendar subview is in create service make up Mode */
	function isCreateServiceMakeUpMode() {
		if (typeof parent.createServiceMakeUpMode != 'undefined' && parent.createServiceMakeUpMode) return true;
		return false;
	}
	/* end: Check if calendar subview is in create service make up Mode */
	/* start: Leave attendace tab if not eligible to be displayed */
	function leaveAttendanceTab() {
		if (classModal.find('.tab-pane.active').attr('id') == 'classAttendance' && !linkedclientsCount(true, true)) switchTabTo('classDetails', classModal);
	}
	/* end: Leave attendace tab if not eligible to be displayed */
	/* start: Toggle if make up session checkbox */
	function toggleIf_make_up_sessionOption(action, epicCash) {
		var ifMakeUpSessionField = classModal.find('.client-form input[name="isCreatingMakeUpSession"]'),
		ifMakeUpSessionGroup = ifMakeUpSessionField.closest('.form-group'),
		isReducedRateField = classModal.find('.client-form input[name="isReducedRate"]'),
		isReducedRateGroup = isReducedRateField.closest('.form-group');
		if (typeof epicCash != 'undefined') ifMakeUpSessionField.data('epic-cash', epicCash);
		if (action == 'show') {
			ifMakeUpSessionGroup.show();
			isReducedRateGroup.hide();
			isReducedRateField.attr('checked', false);
			// isReducedRateField.prop('checked', false);														e);
			toggleReduceRate('hide');
		} else {
			ifMakeUpSessionGroup.hide();
			ifMakeUpSessionField.prop('checked', false);
			isReducedRateGroup.show();
			ifMakeUpSessionField.data('epic-cash', '');
		}
	}
	/* end: Toggle if make up session checkbox */
	/* start: Compare client available epic cadh with class price. */
	function isEpicCashBig(epicCash) {
		if (!isModalModeEdit()) return false;
		if (typeof epicCash == 'undefined') epicCash = classModal.find('#isCreatingMakeUpSession').data('epic-cash');
		var bookingPrice = parseFloat(eventObj.find('input[name="price"]').val());
		epicCash = parseFloat(epicCash);
		if (epicCash && epicCash >= bookingPrice) return true;
		return false;
	}
	/* end: Compare client available epic cadh with class price. */
	/* start: Toggle if recur client checkbox */
	function toggleRecurclientCb(action) {
		var cb = classModal.find('.client-form input[name="ifRecur"]'),
		formGroup = cb.closest('.form-group');
		if (action == 'show') formGroup.show();
		else if (action == 'hide') {
			formGroup.hide();
			cb.prop('checked', false);
		}
	}
	/* end: Toggle if recur client checkbox */
	/* start: Switch to tab that has error field */
	function switchToErrorTab(form) {
		var errorField = form.find('.has-error:first');
		//var errorField = form.find('.alertMsg');
		if (errorField.length) {
			var tabId = errorField.closest('.tab-pane').attr('id');
			form.find('.nav-tabs a[href="#' + tabId + '"]').tab('show')
		}
	}
	/* end: Switch to tab that has error field */
	/* start: Fetch clients for typeahead */
	function fetchClientsForTypeahead(mode, forSalesProcess) {
		if (typeof forSalesProcess == 'undefined') forSalesProcess = '';
		$.get(public_url + 'clients/all?calendar=true&forSalesProcess=' + forSalesProcess, function(data) {
			initTypeahead(data, mode)
		}, 'json');
	}
	/* end: Fetch clients for typeahead */
	/* start: Toggle between field and entity deleted message */
	function toggleField_DelMsg(data) {
		var delMsgPar = data.field.closest('.delMsgPar')
		msgElem = delMsgPar.children('p');
		if (data.kase == 'trashed' && !msgElem.length) {
			data.field.selectpicker('hide');
			delMsgPar.append('<p class="text-danger">' + data.entity + ' has been deleted</p>');
		} else if (data.kase == 'exist' && msgElem.length) {
			data.field.selectpicker('show');
			msgElem.remove()
		}
	}
	/* end: Toggle between field and entity deleted message */
	/* start: Populate field such as duration, capacity etc as per saved in DB */
	function populateClassRelatedFields() {
		var values = {
			duration: eventObj.find('input[name="duration"]').val(),
			capacity: eventObj.find('input[name="capacity"]').val(),
			price: eventObj.find('input[name="price"]').val()
		};
		classModal.find('select[name="classDur"]').val(values.duration).selectpicker('refresh');
		classModal.find('input[name="classCap"]').val(values.capacity);
		updateBookedclientQuickInfo();
		toggleEventOverBookAlert();
		classModal.find('input[name="classPrice"]').val(values.price);
	}
	/* end: Populate field such as duration, capacity etc as per saved in DB */
	/* start: Populate service related fields such as duration, price etc */
	function populateServiceRelatedFields2(values) {
		if (typeof values == 'undefined') var values = {
			duration: eventObj.find('input[name="duration"]').val(),
			price: eventObj.find('input[name="price"]').val()
		};
		classModal.find('select[name="serviceDur"]').val(values.duration).selectpicker('refresh');
		classModal.find('input[name="servicePrice"]').val(values.price);
	}
	/* end: Populate service related fields such as duration, price etc */
	/* start: mark field as deleted or active */
	function toggleDeletedMarkOnField(elem, isDeleted) {
		elem.data('is-deleted', isDeleted)
	}
	/* end: mark field as deleted or active */
	/* start: check if field is marked as deleted */
	function isFieldMarkedAsDeleted(elem) {
		return elem.data('is-deleted')
	}
	/* end: check if field is marked as deleted */
	/* start: mark field as available or vice-versa */
	function toggleAvailableMarkOnField(elem, isAvail) {
		elem.data('is-avail', isAvail)
	}
	/* end: mark field as available or vice-versa */
	/* start: check if field is marked as available */
	/*function isFieldMarkedAsAvail(elem){
		return elem.data('is-avail')
	}*/
	/* end: check if field is marked as available */
	/* start: Get booking type i.e. class or service */
	function getBookingType() {
		return classModal.find('input[name="bookType"]').val()
	}
	/* end: Get booking type i.e. class or service */
	/* start: Set booking type to class or service and make changes ovwer modal as per it */
	function setBookingType(setType) {
		if (typeof setType != 'undefined') {
			var selectableOpts = $("#bookTypeSelectable li");
			selectableOpts.removeClass("ui-selected").show();
			selectableOpts.filter('[data-val="' + setType + '"]')[0].className += " ui-selected";
		}
		var bookType = $('#bookTypeSelectable li.ui-selected').data('val')
		classModal.find('input[name="bookType"]').val(bookType)
		if (bookType == 'class') {
			$('#classFieldset').show().find(':input').attr('required', true);
			$('#classClientsBtns').show()
			$('#staffTooltip').show()
			toggleReduceRate('hide')
			$('.bookingTypeName').text('class')
			classModal.find('div#classAttendance > p').show()
			classModal.find("input[name='ifMarkFav']").closest('.form-group').show();
			$('#serviceFieldset').hide().find(':input[required]').attr('required', false);
			fetchClientsForTypeahead('update', 'book_team');
			if (!isModalModeEdit()) {
				toggleTab(classModal, 'classReccur', 'show');
				toggleTab(classModal, 'classClients', 'hide');
			}
		} else if (bookType == 'service') {
			$('#classFieldset').hide().find(':input[required]').attr('required', false);
			$('#classClientsBtns').hide()
			$('#staffTooltip').hide()
			//$('#confirmClient').hide();			
			classModal.find("input[name='ifMarkFav']").closest('.form-group').hide();
			var serviceFieldset = $('#serviceFieldset');
			serviceFieldset.show();
			serviceFieldset.find('select[name="staffservice"]').attr('required', true)
			serviceFieldset.find('select[name="serviceDur"]').attr('required', true)
			serviceFieldset.find('input[name="servicePrice"]').attr('required', true)
			$('.bookingTypeName').text('service')
			classModal.find('div#classAttendance > p').hide()
			if (serviceFieldset.find('input[name="appointStatusOpt"]').val() == 'Pencilled-In' && serviceFieldset.find('input[name="ifAutoExpireAppoint"]').is(":checked")) {
				var autoExpireAppointDur = serviceFieldset.find('select[name="autoExpireAppointDur"]');
				autoExpireAppointDur.attr('required', true)
				if (autoExpireAppointDur.val() == 'Custom') serviceFieldset.find('.autoExpireAppointDurCustom input').prop('required', true)
			}
		if (!isModalModeEdit()) {
			toggleTab(classModal, 'classReccur', 'hide');
			toggleTab(classModal, 'classClients', 'show');
		}
		if (isModalModeEdit() && !linkedclientsCount()) {
			var forSalesProcessStep = eventObj.find('input[name="forSalesProcessStep"]').val();
			fetchClientsForTypeahead('update', forSalesProcessStep);
		} else fetchClientsForTypeahead('update');
	}
	}
	/* start: Set booking type to class or service and make changes ovwer modal as per it */
	/* start: Get staffs list according to services selected */
	function getStaffsBasedOnServices() {
		if (isUserType(['Admin'])) {
			var data = getServiceDataForAjax();
			if (Object.keys(data).length) {
				var modalStaffDd = appointModal.find('select[name="staff"]'),
				staffSetGroupDispSpan = modalStaffDd.closest('.set-group').find('.set-group-disp span');
				staffSetGroupDispSpan.removeClass('text-danger');
				toggleAvailableMarkOnField(modalStaffDd, 1);
				setFieldNeutral(modalStaffDd);
				var formData = {},
				lastServiceRow = appointModal.find('.services .row:last'),
				eventDate = setEventDate(appointModal, 'submit');
				formData['date'] = eventDate.date;
				formData['day'] = eventDate.day;
				formData['services'] = data.selectedServices;
				formData['serviceTime'] = timeStringToDbTime(appointModal.find('input[name="serviceTime0"]').val());
				formData['lastServiceTime'] = timeStringToDbTime(data.lastServiceTime);
				formData['duration'] = data.lastDuration;
				$.get(public_url + 'settings/business/services/free-staffs', formData, function(data) {
					var selectedStaffName = '',
					options = '',
					ids = [];
					if (!$.isEmptyObject(data)) {
						$.each(data, function(key, value) {
							options += '<option value="' + key + '">' + value + '</option>';
							ids.push(key);
						});
					}
					var selectedStaff = 0;
					var staffId = findStaffId();
					if (staffId && staffId != 'all' && staffId != 'all-ros' && $.inArray(staffId, ids) >= 0) {
						//filter has staff selected and staff list have filter selected staff
						selectedStaff = staffId;
					}
					modalStaffDd.html(options);
					if (selectedStaff) modalStaffDd.val(selectedStaff);
					if (!selectedStaffName) selectedStaffName = modalStaffDd.find('option:selected').text();
					if (!selectedStaffName) {
						selectedStaffName = 'No staff found'
						staffSetGroupDispSpan.addClass('text-danger');
					}
					staffSetGroupDispSpan.text(selectedStaffName);
					modalStaffDd.selectpicker('refresh');
				});
			}
		}
	}
	/* end: Get staffs list according to services selected */
	/* start: Remove service rows */
	function removeServiceRow(removeBtn) {
		if (typeof removeBtn == 'undefined') removeBtn = appointModal.find('.removeService');
		removeBtn.each(function() {
			var $this = $(this);
			if ($this.closest('.form-group').children('.row.m-t-5').length - 1 == 1) toggleRecurrTab('show');
			$this.closest('.row').remove();
		})
	}
	/* end: Remove service rows */
	/* start: Get areas list according to services selected */
	function getAreasBasedOnServices() {
		//alert('function')
		if (isUserType(['Admin'])) {
			var data = getServiceDataForAjax();
			if (Object.keys(data).length) {
				var areaDd = appointModal.find('select[name="modalLocArea"]'),
					/*areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp'),
					modalStaffDd = appointModal.find('select[name="staff"]'),*/
					areaSetGroupDispSpan = areaDd.closest('.set-group').find('.set-group-disp span');
					areaSetGroupDispSpan.removeClass('text-danger');
					toggleAvailableMarkOnField(areaDd, 1);
					setFieldNeutral(areaDd);
					var formData = {},
					lastServiceRow = appointModal.find('.services .row:last'),
					eventDate = setEventDate(appointModal, 'submit');
					formData['date'] = eventDate.date;
					formData['day'] = eventDate.day;
					formData['services'] = data.selectedServices;
					formData['serviceTime'] = timeStringToDbTime(appointModal.find('input[name="serviceTime0"]').val());
					formData['lastServiceTime'] = timeStringToDbTime(data.lastServiceTime);
					formData['duration'] = data.lastDuration;
					$.get(public_url + 'settings/business/services/free-areas', formData, function(data) {
						var selectedAreaName = '',
						options = '',
						ids = [];
						if (!$.isEmptyObject(data)) {
							$.each(data, function(key, value) {
								options += '<option value="' + key + '">' + value + '</option>';
								ids.push(key);
							});
						}
						var selectedArea = 0;
						var areaId = findAreaId();
						if (areaId && areaId != 'all' && areaId != 'all-ros' && $.inArray(areaId, ids) >= 0) {
						//filter has area selected and area list have filter selected area
						selectedArea = areaId;
					}
					areaDd.html(options);
					if (selectedArea) areaDd.val(selectedArea);
					if (!selectedAreaName) selectedAreaName = areaDd.find('option:selected').text();
					if (!selectedAreaName) {
						selectedAreaName = 'No area found'
						areaSetGroupDispSpan.addClass('text-danger');
					}
					areaSetGroupDispSpan.text(selectedAreaName);
					areaDd.selectpicker('refresh');
				});
				}
			}
		}
		/* end: Get areas list according to services selected */
		/* start: Toggle reduced rate field */
		function toggleReduceRate(action) {
			var cb = $('#isReducedRate');
			if (typeof action == 'undefined') {
				if (cb.is(':checked')) action = 'show'
					else action = 'hide'
				}
		var reducedRateField = cb.siblings('input') //$('#reducedRate');
		if (action == 'show') {
			reducedRateField.show().prop('required', true).val(calendarSettings.cs_reduced_rate);
		} else reducedRateField.hide().prop('required', false)
	}
	/* end: Toggle reduced rate field */
	/* start: Check if booking modal is in edit mode */
	function isModalModeEdit(modal = undefined) {
		if(modal == undefined){
			modal = classModal;
		}
		var eventId = modal.find('input[name="eventId"]').val();
		if (eventId && eventId != null) return true;
		return false;
	}
	/* end: Check if booking modal is in edit mode or create mode */
	/* start: Check if booking modal is in edit mode */
	function isServiceFresh() {
		if (eventObj.find('input[name="justCreated"]').val() == 1) return true;
		return false;
	}
	/* end: Check if booking modal is in edit mode or create mode */
	/* start: Sweet alert for saving booking data */
	function saveBookingPrompt(targetTabId, callback) {
		if (typeof targetTabId == 'undefined') targetTabId = '';
		//Non Repeating Event
		var text = "<a class='btn btn-primary w116 submitBooking' href='#' data-target-tab='" + targetTabId + "'>Yes</a>"
		if (eventObj.find('input[name="isRepeating"]').val() == 1) {
			//Repeating Event
			var activeTab = getActiveTab();
			if ( /*activeTab == 'classDetails' || */ activeTab == 'classReccur')
				//var text = "<a class='btn btn-primary m-r-10 submitBooking' href='#' data-target-event='future' data-target-tab='"+targetTabId+"'>This and future</a><a class='btn btn-primary submitBooking' href='#' data-target-event='this' data-target-tab='"+targetTabId+"'>This only</a>" //update-event
				var text = "<a class='btn btn-primary submitBooking' href='#' data-target-event='future' data-target-tab='" + targetTabId + "'>This and future</a>" //update-event,m-r-10
			/*else if(activeTab == 'classReccur')
			var text = "<a class='btn btn-primary m-r-10 submitBooking' href='#' data-target-event='update-onw'>Update onwards</a><a class='btn btn-primary submitBooking' href='#' data-target-event='create-onw'>Create onwards</a>" //update-event*/
		}
		swal({
			title: 'Update Booking?',
			showCancelButton: true,
			html: true,
			text: text,
			showConfirmButton: false,
			customClass: 'classClientUnlinkAlert'
		}, function(isConfirm) {
			if (!isConfirm) {
				if (typeof callback != 'undefined') callback();
			}
		});
	}
	/* end: Sweet alert for saving booking data */
	/* start: Sweet alert for saving field individually */
	function saveFieldPrompt(field) {
		if ((!pastCurrentMode || ((field == 'time' || field == 'status') && getBookingType() == 'service')) && !cancelledBooking && isModalModeEdit()) {
			if (field != 'autoExpireClr' && field != 'autoExpire' && field != 'status' && field != 'markFav' && eventObj != null && eventObj != undefined && eventObj.find('input[name="isRepeating"]').val() == '1') {
				var text = "<a class='btn btn-primary m-r-10 saveField' href='#' data-target-event='future' data-field='" + field + "'>This and future</a><a class='btn btn-primary saveField' href='#' data-target-event='this' data-field='" + field + "'>This only</a>"
				var label = field;
				if (label == 'resourceRemove') label = 'resource';
				/*else if(label == 'autoExpire')
				label = 'auto expire';*/
				swal({
					title: 'Update ' + label + '?',
					allowOutsideClick: true,
					html: true,
					text: text,
					showConfirmButton: false,
					customClass: 'classClientUnlinkAlert'
				});
			} else saveField(field)
		}
	}
	/* end: Sweet alert for saving field individually */
	/* start: Remove client from booking */
	function removeClient(clientId, source = '') {
		var bookingType = getBookingType();
		if (bookingType == 'class') {
			if (eventObj.find('input[name="isRepeating"]').val() == 1 && classClients[clientId].ifRecur) {
				//Repeating Event
				var text = "<a class='btn btn-primary m-r-10 removeClient' href='#' data-target-event='future' data-client-id='" + clientId + "'>This and future</a><a class='btn btn-primary removeClient' href='#' data-target-event='this' data-client-id='" + clientId + "'>This only</a>";
				swal({
					title: 'Delete From?',
					showCancelButton: true,
					html: true,
					text: text,
					showConfirmButton: false,
					allowOutsideClick: true,
					customClass: 'classClientUnlinkAlert',
				});
			} else 
			removeClientAjax(clientId, source);

		} else if (bookingType == 'service') {
			deleteServiceMode = true;
			classModal.modal('hide');
			appointCancelModal.modal('show');
		}
	}

	function removeClientAjax(clientId, source = '') {
		var formData = {};
		var message = '';
		formData['step'] = 'removeClient';
		formData['clientId'] = clientId;
		formData['eventId'] = classModal.find('input[name="eventId"]').val();
		formData['ifMakeUp'] = classClients[clientId].ifMakeUp;
		//formData['action'] = classClients[clientId].action;
		formData['purpose'] = classClients[clientId].purpose;
		formData['amount'] = classClients[clientId].amount;
		formData['makeupNotes'] = classClients[clientId].makeupNotes;
		formData['ifRecur'] = classClients[clientId].ifRecur;
		formData['isLdc'] = classClients[clientId].isLdc;
		
		if (eventObj.find('input[name="isRepeating"]').val() == 1 && classClients[clientId].ifRecur) 
			formData['targetEvents'] = classModal.find('input[name="targetEvents"]').val();

		var bookingType = getBookingType();

		if (bookingType == 'class') 
			var ajaxAction = 'classes/edit';
		else if (bookingType == 'service') 
			var ajaxAction = 'single-service/edit';

		if (classClients[clientId].isInvoice || classClients[clientId].isInvoice == 'true') 
			var isClientInvoice = true;
		else 
			var isClientInvoice = false;

		if(source == 'selectClientAction') {
			var eventInvoice = getClientEventInvoiceAmount(formData['clientId'], formData['eventId'], bookingType);
			var clientBookingDetails = getClientBookingDetails(formData['clientId'], formData['eventId'], bookingType);

			if (clientBookingDetails != [] && clientBookingDetails != null && clientBookingDetails != undefined) {

				if (clientBookingDetails.secc_with_invoice == 1 && clientBookingDetails.secc_epic_credit == 0 && eventInvoice != [] && eventInvoice != null && eventInvoice != undefined && eventInvoice.inv_status == 'Unpaid') {

					if (isClientInvoice) {
						invoiceDelConfirm(true, function(isConfirm) {
							if (isConfirm) formData['delete_invoice'] = 'yes_delete';
							clientRemoveRequest(formData, ajaxAction, clientId);
						});
					} else {
						clientRemoveRequest(formData, ajaxAction, clientId);
					}

				} else {
					var action = true;
					if (clientBookingDetails.secc_with_invoice == 0 && clientBookingDetails.secc_epic_credit == 0){
						if(classClients[clientId].isLdc)
							action = false;
						else
							message = "Do you want to return membership?";
					}
					else if (clientBookingDetails.secc_with_invoice == 1 && clientBookingDetails.secc_epic_credit == 1) 
						message = "Do you want to refund invoice amount and credit used?";
					else if (clientBookingDetails.secc_with_invoice == 1 && clientBookingDetails.secc_epic_credit == 0) 
						message = "Do you want to refund invoice amount?";
					else if (clientBookingDetails.secc_epic_credit == 1 && clientBookingDetails.secc_with_invoice == 0) 
						message = "Do you want to refund credit used?";

					removeClientActionCormfirmation(action, message, function(isConfirm) {
						if (isConfirm) 
							formData['refund_resource'] = 'yes';
						else 
							formData['refund_resource'] = 'no';

						clientRemoveRequest(formData, ajaxAction, clientId);
					});
				}

			} else {
				swal({
					title: "Client is already removed from this session.",
					allowOutsideClick: false,
					showCancelButton: false,
					confirmButtonText: 'Okay',
					confirmButtonColor: '#ff4401',
					type: 'warning',
				}, function(isConfirm) {
					if (isConfirm) {
						reloadEvents = true;
						$("#raiseMakeUpModel").modal('hide');
						classModal.modal('hide');
					}
				});
			}
		} else {
			clientRemoveRequest(formData, ajaxAction, clientId);
		}
	}
	/* end: Remove client from booking */
	/* Start: remove client request */
	function clientRemoveRequest(formData, ajaxAction, clientId) {
		// ajax for remove client
		toggleWaitShield("show", 'unlinkClient');
		$.post(public_url + 'staffevents/' + ajaxAction, formData, function(resp) {
			var resp = JSON.parse(resp);
			$('#raiseMakeUpModelSubmit').prop('disabled', false);
			if (resp.status == "added") {
				swal.close();
				reloadEvents = true;
				unlinkClientClass(clientId);
				var bookingType = getBookingType();
				if (bookingType == 'class' && resp.newConfirmedClients.length) {
					$.each(resp.newConfirmedClients, function(key, val) {
						confirmClientUi(val)
					});
				}
				updateBookedclientQuickInfo();
				toggleEventOverBookAlert();
			} else if (resp.status == 'error' && resp.unsatisyReas == 'client_not_found') {
				swal({
					title: "Client is already removed from this session.",
					allowOutsideClick: false,
					showCancelButton: false,
					confirmButtonText: 'Okay',
					confirmButtonColor: '#ff4401',
					type: 'warning',
				}, function(isConfirm) {
					if (isConfirm) {
						reloadEvents = true;
						$("#raiseMakeUpModel").modal('hide');
						classModal.modal('hide');
					}
				});
			}
			toggleWaitShield("hide", 'unlinkClient');
		});
	}
	/* End: remove client request */
	/* start: Confirm client over UI */
	function confirmClientUi(clientId) {
		clientLnk = $('#linkedclientList a[data-id="' + clientId + '"]');
		classClients[clientId].status = 'Confirm';
		clientLnk.find('span.clientStatusIcon').remove();
		clientLnk.data('status', 'Confirm');
	}
	/* end: Confirm client over UI */
	/* start: Toggle event auto expire fields */
	function toggleAutoExpireFields(cb) {
		if (typeof cb == 'undefined') cb = classModal.find('input[name="ifAutoExpireAppoint"]');
		var autoExpireAppoint = $('.autoExpireAppoint'),
		autoExpireAppointDurCustom = $('.autoExpireAppointDurCustom'),
		autoExpireAppointDurCustomFields = autoExpireAppointDurCustom.find('input'),
		dd = autoExpireAppoint.find('select');
		if (cb.is(":checked")) {
			autoExpireAppoint.show();
			dd.val('');
			dd.prop('required', true)
			dd.selectpicker('refresh');
		} else {
			autoExpireAppoint.hide();
			dd.prop('required', false)
		}
		autoExpireAppointDurCustom.hide();
		autoExpireAppointDurCustomFields.prop('required', false)
	}
	/* end: Toggle event auto expire fields */
	/* start: Toggle event manual auto expire fields */
	function toggleManualExpireFields(dd) {
		if (typeof dd == 'undefined') dd = classModal.find('select[name="autoExpireAppointDur"]');
		var autoExpireAppoint = $('.autoExpireAppoint'),
		autoExpireAppointDurCustom = $('.autoExpireAppointDurCustom'),
		autoExpireAppointDurCustomFields = autoExpireAppointDurCustom.find('input'),
		val = dd.val();
		if (val == 'Custom') {
			autoExpireAppointDurCustom.show();
			autoExpireAppointDurCustomFields.prop('required', true)
			autoExpireAppoint.hide();
		} else {
			autoExpireAppointDurCustomFields.prop('required', false)
		}
		return val;
	}
	/* end: Toggle event manual auto expire fields */
	/* Start: This function disable/enable cancel class button */
	function disabledEnabledCancleClsBtn() {
		/*var totalClients = $('#classModal').find('div#linkedclientList').children().length;
		alert(totalClients);
		if(totalClients <= 0){
			$('#classModal').find('.delete-prompt').attr('disabled', false);
		}
		else{
			$('#classModal').find('.delete-prompt').attr('disabled', true);
		}*/
		var delBtn = $('#classModal .delete-prompt');
		if (!linkedclientsCount()) delBtn.attr('disabled', false);
		else delBtn.attr('disabled', true);
	}
	/* End: This function disable/enable cancel class button */
	function toggleSubmitBtn() {
		/*var submitBtn = classModal.find('button.submitBooking');
		submitBtn.hide()*/
		var submitBtn = classModal.find('button.submitBooking'),
		activeTab = getActiveTab();
		submitBtn.data('action', '');
		pastCurrentMode =false;
		if (cancelledBooking || pastCurrentMode || (isModalModeEdit() && ((isUserType(['Staff']) && eventObj.find('input[name="userId"]').val() != loggedInUser.userId) || (activeTab != 'classReccur' && activeTab != 'classNotes')))) {
			submitBtn.data('action', 'close')
		}
		if (!pastCurrentMode) {
			if (eventObj.length && isModalModeEdit()) { //Booking is in edit mode and in future 
				if ( /*activeTab == 'classDetails' || */ activeTab == 'classReccur' || activeTab == 'classNotes') {
					//submitBtn.show();
					dirtyForm.init(classModal.find('#' + activeTab))
				}
			}
			/*else
			submitBtn.show();*/
		}
	}

	function getBookingDetails(eventId, bookingType, callback) {
		if (typeof bookingType == 'undefined' || !bookingType) var bookingType = getBookingType();
		if (bookingType == 'class') var ajaxAction = 'classes';
		else if (bookingType == 'service') var ajaxAction = 'single-service';
		$.get(public_url + 'staffevents/' + ajaxAction + '/' + eventId, function(data) {
			if (bookingType == 'class') {
				var eventHtml = '<input type="hidden" name="type" value="class"><input type="hidden" name="eventId" value="' + eventId + '"><input type="hidden" name="date" value="' + data.date + '"><input type="hidden" name="startTime" value="' + data.startTime + '"><input type="hidden" name="duration" value="' + data.duration + '"><input type="hidden" name="userId" value="' + data.userId + '"><input type="hidden" name="staffId" value="' + data.staffId + '"><input type="hidden" name="staffName" value="' + data.staffName + '"><input type="hidden" name="isStaffDeleted" value="' + data.isStaffDeleted + '"><input type="hidden" name="classId" value="' + data.classId + '"><input type="hidden" name="capacity" value="' + data.capacity + '"><input type="hidden" name="price" value="' + data.price + '"><input type="hidden" name="className" value="' + data.className + '"><input type="hidden" name="notes" value="' + data.notes + '"><input type="hidden" name="eventRepeat" value="' + data.eventRepeat + '"><input type="hidden" name="eventRepeatInterval" value="' + data.eventRepeatInterval + '"><input type="hidden" name="eventRepeatEnd" value="' + data.eventRepeatEnd + '"><input type="hidden" name="eventRepeatEndAfterOccur" value="' + data.eventRepeatEndAfterOccur + '"><input type="hidden" name="eventRepeatEndOnDate" value="' + data.eventRepeatEndOnDate + '"><input type="hidden" name="isRepeating" value="' + data.isRepeating + '"><input type="hidden" name="isClassDeleted" value="' + data.isClassDeleted + '"><input type="hidden" name="linkedClientsCount" value="' + data.linkedClientsCount + '">';
			} else if (bookingType == 'service') {
				var eventHtml = '<input type="hidden" name="type" value="single-service"><input type="hidden" name="eventId" value="' + eventId + '"><input type="hidden" name="date" value="' + data.date + '"><input type="hidden" name="startTime" value="' + data.startTime + '"><input type="hidden" name="duration" value="' + data.duration + '"><input type="hidden" name="userId" value="' + data.userId + '"><input type="hidden" name="staffId" value="' + data.staffId + '"><input type="hidden" name="staffName" value="' + data.staffName + '"><input type="hidden" name="isStaffDeleted" value="' + data.isStaffDeleted + '"><input type="hidden" name="price" value="' + data.price + '"><input type="hidden" name="notes" value="' + data.notes + '"><input type="hidden" name="eventRepeat" value="' + data.eventRepeat + '"><input type="hidden" name="eventRepeatInterval" value="' + data.eventRepeatInterval + '"><input type="hidden" name="eventRepeatEnd" value="' + data.eventRepeatEnd + '"><input type="hidden" name="eventRepeatEndAfterOccur" value="' + data.eventRepeatEndAfterOccur + '"><input type="hidden" name="eventRepeatEndOnDate" value="' + data.eventRepeatEndOnDate + '"><input type="hidden" name="isRepeating" value="' + data.isRepeating + '"><input type="hidden" name="appointStatusOpt" value="' + data.appointStatusOpt + '"><input type="hidden" name="autoExpireAppointDur" value="' + data.autoExpireAppointDur + '"><input type="hidden" name="autoExpireDatetime" value="' + data.autoExpireDatetime + '"><input type="hidden" name="serviceName" value="' + data.serviceName + '"><input type="hidden" name="serviceId" value="' + data.serviceId + '"><input type="hidden" name="forSalesProcessStep" value="' + data.forSalesProcessStep + '"><input type="hidden" name="isServiceDeleted" value="' + data.isServiceDeleted + '"><input type="hidden" name="salesProcessEvent" value="' + data.salesProcessEvent + '"><input type="hidden" name="consultationDate" value="' + data.consultationDate + '"><input type="hidden" name="justCreated" value="' + data.justCreated + '"><input type="hidden" name="isEpicCredit" value="' + data.isEpicCredit + '"> <input type="hidden" name="isInvoice" value="' + data.isInvoice + '"><input type="hidden" name="isClientMakeup" value="' + data.isClientMakeup + '">';
			}
			if (data.eventRepeatWeekDays != 'null' && data.eventRepeatWeekDays != null && data.eventRepeatWeekDays != undefined && data.eventRepeatWeekDays != '') var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
			else var eventRepeatWeekDays = [];
			$.each(eventRepeatWeekDays, function(key, val) {
				eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\'' + val + '\'>';
			});
			for (var i in data.resources) eventHtml += '<input type=\'hidden\' name=\'resource[]\' value=\'' + JSON.stringify(data.resources[i]) + '\'>';
				for (var i in data.clients) {
				//eventHtml += '<input type=\'hidden\' name=\'client[]\' value=\''+encodeURI(JSON.stringify(data.clients[i]))+'\'>';
				//eventHtml += '<input type="hidden" name="client[]" value="'+encodeURI(JSON.stringify(data.clients[i]))+'">';
				eventHtml += '<input type="hidden" name="client[]" value="' + /*(JSON.stringify(data.clients[i]).replace(/'/g, "&#39;")).replace(/"/g, "&#34;")*/ escQuotes(JSON.stringify(data.clients[i])) + '">';
			}
			for (var i in data.areas)
				//eventHtml += '<input type=\'hidden\' name=\'area[]\' value=\''+JSON.stringify(data.areas[i])+'\'>';
			eventHtml += '<input type="hidden" name="area[]" value="' + escQuotes(JSON.stringify(data.areas[i])) + '">';
			for (var i in data.histories)
				//eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\''+JSON.stringify(data.histories[i])+'\'>';	
			eventHtml += '<input type="hidden" name="history[]" value="' + escQuotes(JSON.stringify(data.histories[i])) + '">';
			if (typeof callback != 'undefined') callback(eventHtml);
		}, 'json');
	}

	function getServiceDataForAjax() {
		var selectedServices = [],
		lastServiceTime = '',
		lastDuration = 0;
		appointModal.find('.services .row.m-t-5').each(function() {
			var $this = $(this),
			serviceName = $this.find('select.serviceName').val(),
			serviceTime = $this.find('input.timepicker1').val(),
			serviceDur = $this.find('select.serviceDur').val();
			if (serviceName && serviceTime && serviceDur) {
				selectedServices.push(serviceName);
				lastServiceTime = serviceTime;
				lastDuration = serviceDur;
			}
		});
		if (selectedServices.length) return {
			selectedServices: selectedServices,
			lastServiceTime: lastServiceTime,
			lastDuration: lastDuration
		};
		else return {};
	}

	function dpSrc(src, gend) {
		if (typeof src != 'undefined' && src) return public_url + 'uploads/thumb_' + src;
		else if (typeof gend != 'undefined' && gend) return public_url + 'profiles/' + toLowerCase(gend) + '.gif';
		else return public_url + 'profiles/noimage.gif';
	}

	function activateAttendanceRadio(attendance, attendanceRow) {
		if (typeof attendanceRow == 'undefined') attendanceRow = classModal.find('div#classAttendanceList');
		var inputs = attendanceRow.find('input:not(:disabled)'),
		attendanceInput = inputs.filter('input[value="' + attendance + '"]');
		attendanceRow.find('label:not(.disabled)').removeClass('active');
		inputs.attr('checked', false);
		attendanceInput.attr('checked', true).parent().addClass('active');
		return attendanceInput;
	}
	/*function escDoubleQuote(stringg){
		return stringg.replace(/"/g, "&#34;");
	}*/
	function escSingleQuote(stringg) {
		return stringg.replace(/'/g, "&#39;");
	}

	function escQuotes(string) {
		return (escSingleQuote(string)).replace(/"/g, "&#34;");
	}

	function checkedPencilledinCheckbox() {
		var cb = classModal.find('input[name="ifAutoExpireAppoint"]');
		cb.prop('checked', true)
		toggleAutoExpireFields(cb)
		$('select[name="autoExpireAppointDur"]', classModal).val('24').selectpicker('refresh');
	}
	/*
	**TEMP
	*/
	function getStaffsHavingArea(defaultStaff) {
		return;
		var modalStaffDd = classModal.find('select[name="staff"]'),
		staffSetGroupDisp = modalStaffDd.closest('.set-group').find('.set-group-disp'),
		staffSetGroupDispSpan = staffSetGroupDisp.find('span'),
		formData = {};
		if (typeof defaultStaff == 'undefined') var defaultClass = false;
		else var defaultClass = true;
		var eventDate = setEventDate(classModal, 'submit');
		formData['date'] = eventDate.date;
		formData['day'] = eventDate.day;
		formData['classTime'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
		if (eventObj.length) {
			formData['eventId'] = eventObj.find('input[name="eventId"]').val();
			if (notAvailAjax) {
				var availAreas = classModal.find('select[name="modalLocArea"] option[data-is-avail!="0"]:selected');
				if (availAreas.length) {
					formData['availAreas'] = []
					availAreas.each(function() {
						formData['availAreas'].push($(this).val())
					})
				}
				var classDd = classModal.find('select[name="staffClass"]');
				if (isFieldMarkedAsAvail(classDd)) formData['classId'] = classDd.val()
			}
	}
	staffSetGroupDispSpan.removeClass('text-danger');
		//modalStaffDd.data('is-avail', 1)	
		toggleAvailableMarkOnField(modalStaffDd, 1);
		setFieldNeutral(modalStaffDd);
		toggleDeletedMarkOnField(modalStaffDd, 0)
		if (isUserType(['Staff'])) {
			if (eventObj.length) formData['duration'] = eventObj.find('input[name="duration"]').val();
			$.get(public_url + 'settings/business/staffs/' + loggedInUser.id + '/is-busy', formData, function(data) {
				if (data == 'true') {
					//Staff is busy
					staffSetGroupDispSpan.addClass('text-danger');
					//modalStaffDd.data('is-avail', 0);
					toggleAvailableMarkOnField(modalStaffDd, 0);
					staffSetGroupDispSpan.text('You are not available');
				} else staffSetGroupDispSpan.text(loggedInUser.name);
				modalStaffDd.html('<option value="' + loggedInUser.id + '" selected>' + loggedInUser.name + '</option>').selectpicker('refresh');
				getAreasByStaff(modalStaffDd, defaultClass);
				staffSetGroupDisp.find('a').hide();
			}, 'json');
		} else if (isUserType(['Admin'])) {
			$.get(public_url + 'settings/business/staffs/free-and-having-area', formData, function(data) {
				var options = '',
				ids = [];
				if (!$.isEmptyObject(data)) {
					$.each(data, function(key, value) {
						options += '<option value="' + key + '">' + value + '</option>';
						ids.push(key);
					});
				}
				var selectedStaffName = '',
				selectedStaff = 0;
				if (typeof defaultStaff != 'undefined') {
					//edit case
					if ($.inArray(defaultStaff, ids) < 0) {
						//staff list does not have selected staff
						staffSetGroupDispSpan.addClass('text-danger');
						//modalStaffDd.data('is-avail', 0);
						toggleAvailableMarkOnField(modalStaffDd, 0);
						var staffName = eventObj.find('input[name="staffName"]').val()
						if (eventObj.find('input[name="isStaffDeleted"]').val() == 'true') {
							//Staff has been deleted
							selectedStaffName = staffName + ' has been deleted';
						} else selectedStaffName = staffName + ' is not available';
						options = '<option value="' + defaultStaff + '">' + staffName + '</option>';
					}
					selectedStaff = defaultStaff;
				} else {
					//add case
					var staffId = findStaffId();
					if (staffId && staffId != 'all' && staffId != 'all-ros' && $.inArray(staffId, ids) >= 0) {
						//filter has staff selected and staff list have filter selected staff
						selectedStaff = staffId;
					}
				}
				modalStaffDd.html(options);
				if (selectedStaff) modalStaffDd.val(selectedStaff);
				if (!selectedStaffName) selectedStaffName = modalStaffDd.find('option:selected').text();
				if (!selectedStaffName) {
					selectedStaffName = 'No staff found'
					staffSetGroupDispSpan.addClass('text-danger');
				}
				staffSetGroupDispSpan.text(selectedStaffName);
				modalStaffDd.selectpicker('refresh');
				if (!notAvailAjax) getAreasByStaff(modalStaffDd, defaultClass);
				notAvailAjax = false;
			}, 'json');
		}
	}

	function getAreasByStaff(staffDd, defaultClass) {
		return;
		var areaDd = classModal.find('select[name="modalLocArea"]'),
		areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp'),
		formData = {};
		if (typeof defaultClass == 'undefined') defaultClass = false
			var eventDate = setEventDate(classModal, 'submit');
		formData['date'] = eventDate.date;
		formData['day'] = eventDate.day;
		formData['classTime'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
		if (eventObj.length) {
			formData['eventId'] = eventObj.find('input[name="eventId"]').val();
			if (notAvailAjax) {
				if (isFieldMarkedAsAvail(staffDd) && staffDd.val()) formData['staffId'] = staffDd.val();
				var classDd = classModal.find('select[name="staffClass"]');
				if (isFieldMarkedAsAvail(classDd)) formData['classId'] = classDd.val()
			}
	}
	areaSetGroupDisp.find('span').remove()
	areaSetGroupDisp.find('br').remove()
	setFieldNeutral(areaDd);
	toggleDeletedMarkOnField(areaDd, 0)
	$.get(public_url + 'staff/' + staffDd.val() + '/location-areas', formData, function(data) {
		var options = '',
		ids = [];
		if (!$.isEmptyObject(data)) {
			$.each(data, function(key, value) {
				var areaId = value.areaId,
				name = value.locName + ' - ' + value.areaName;
				options += '<option value="' + areaId + '">' + name + '</option>';
				ids.push(areaId);
			});
		}
		var span = '',
		selectedAreas = []
			/*,
						selectedUnavailAreas = [],
						selectedDeletedAreas = []*/
						;
						if (defaultClass) {
				//edit case
				var temp = '',
				flag = false;
				eventObj.find("input[name='area[]']").each(function() {
					var data = JSON.parse($(this).val()),
					isAvail = 1,
					isDel = 0;
					if ($.inArray(data.id, ids) < 0) {
						//area list does not have selected area
						isAvail = 0;
						if (data.isAreaDeleted == true) {
							//Area has been deleted
							isDel = 1;
							//selectedDeletedAreas.push(data.id)
						}
						flag = true;
						/*else
						selectedUnavailAreas.push(data.id)*/
					}
					temp += '<option value="' + data.id + '" data-is-avail="' + isAvail + '" data-is-del="' + isDel + '">' + data.locAreaName + '</option>'
					selectedAreas.push(data.id)
				});
				//if(selectedUnavailAreas.length || selectedDeletedAreas.length)
				if (flag) options = temp;
			} else {
				//add case
				var areaId = findAreaId();
				if (areaId && areaId != 'all' && $.inArray(areaId, ids) >= 0) {
					//filter has area selected and area list have filter selected area
					selectedAreas.push(areaId)
				}
			}
			areaDd.html(options);
			if (!selectedAreas.length && options) selectedAreas.push(areaDd.find('option:first').val())
				areaDd.val(selectedAreas);
			areaDd.find('option:selected').each(function() {
				var $this = $(this)
				/*,
				val = parseInt($this.attr('value'), 10)*/
				;
				/*if(selectedDeletedAreas.length && $.inArray(val, selectedDeletedAreas) >= 0)
					span += '<span class="text-danger">'+$this.text()+' has been deleted</span><br>';
				else if(selectedUnavailAreas.length && $.inArray(val, selectedUnavailAreas) >= 0)
					span += '<span class="text-danger">'+$this.text()+' is not available</span><br>';*/
				if ($this.data('is-del') == '1') span += '<span class="text-danger">' + $this.text() + ' has been deleted</span><br>';
				else if ($this.data('is-avail') == '0') span += '<span class="text-danger">' + $this.text() + ' is not available</span><br>';
				else span += '<span>' + $this.text() + '</span><br>';
			});
			if (span) span = span.slice(0, -4)
				else span = '<span class="text-danger">No area found</span>';
			areaSetGroupDisp.prepend(span)
			areaDd.selectpicker('refresh');
			if (!notAvailAjax) getStaffClasses(staffDd, defaultClass);
			notAvailAjax = false;
		}, 'json');
	}
	/* start: Populate classes based on the staffs */
	function getStaffClasses(staffDd, defaultClass) {
		return;
		var classDd = classModal.find('select[name="staffClass"]'),
			//delMsgPar = classDd.closest('.form-group'),
			errorCont = classDd.closest('.form-group').children('.text-danger'),
			formData = {},
			options = '';
			if (typeof defaultClass != 'undefined' && defaultClass) defaultClass = parseInt(eventObj.find('input[name="classId"]').val(), 10);
			else defaultClass = 0;
			formData['staffId'] = staffDd.val();
			formData['areaId'] = classModal.find('select[name="modalLocArea"]').val();
		//classDd.selectpicker('show');
		//delMsgPar.children('span.text-danger').remove()
		errorCont.html('')
		toggleAvailableMarkOnField(classDd, 1);
		$.get(public_url + 'area-staff/classes', formData, function(data) {
			var ids = [];
			if (!$.isEmptyObject(data)) {
				$.each(data, function() {
					options += '<option value="' + this.id + '" data-duration="' + this.duration + '" data-capacity="' + this.capacity + '" data-price="' + this.price + '">' + this.name + '</option>';
					ids.push(this.id);
				});
			}
			var selectedClass = 0;
			if (defaultClass) {
				//edit case
				if ($.inArray(defaultClass, ids) < 0) {
					//class list does not have selected class
					var className = eventObj.find('input[name="className"]').val();
					if (eventObj.find('input[name="isClassDeleted"]').val() == 'true') {
						//Class has been deleted
						toggleAvailableMarkOnField(classDd, 0);
						//delMsgPar.append('<span class="text-danger">'+className+' has been deleted</span>');
						errorCont.html(className + ' has been deleted')
						//classDd.selectpicker('hide');
					} else if (isFieldMarkedAsAvail(staffDd) && !isAreaMarkedAsUnavail()) {
						toggleAvailableMarkOnField(classDd, 0);
						//delMsgPar.append('<span class="text-danger">'+className+' is not available</span>');
						errorCont.html(className + ' is not available')
						//classDd.selectpicker('hide');
					} else options = '<option value="' + defaultClass + '" data-duration="' + eventObj.find('input[name="duration"]').val() + '" data-capacity="' + eventObj.find('input[name="capacity"]').val() + '" data-price="' + eventObj.find('input[name="price"]').val() + '">' + className + '</option>';
				}
				populateClassRelatedFields();
				selectedClass = defaultClass;
			} else {
				//add case
			}
			classDd.html('<option value="">-- Select --</option>' + options);
			if (selectedClass) classDd.val(selectedClass);
			classDd.selectpicker('refresh');
		}, 'json');
	}
	/* end: Populate classes based on the staffs */
	function ifSendNotAvailAjax() {
		return;
		return (isUserType(['Admin']) && (!isFieldMarkedAsAvail(classModal.find('select[name="staff"]')) || !isFieldMarkedAsAvail(classModal.find('select[name="staffClass"]')) || isAreaMarkedAsUnavail()))
	}
	/* start: check if area field is marked as unavailable */
	function isAreaMarkedAsUnavail() {
		return;
		return (classModal.find('select[name="modalLocArea"] option[data-is-avail="0"]').length)
	}
	/* end: check if area field is marked as unavailable */
	/* start: Get staffs list according to class selected */
	function getStaffsBasedOnClass() {
		return;
		if (isUserType(['Admin'])) {
			var classDd = classModal.find('select[name="staffClass"]'),
			modalStaffDd = classModal.find('select[name="staff"]'),
				//selectedStaffName = '',
				/*staffSetGroupDisp = modalStaffDd.closest('.set-group').find('.set-group-disp'),
				staffSetGroupDispSpan = staffSetGroupDisp.find('span'),*/
				staffSetGroupDispSpan = modalStaffDd.closest('.set-group').find('.set-group-disp span')
			/*,
			options = ''*/
			;
			if (!isFieldMarkedAsAvail(classDd)) {
				//Class has been deleted
				//modalStaffDd.html(options).selectpicker('refresh');
				modalStaffDd.html('').selectpicker('refresh');
				var selectedStaffName = eventObj.find('input[name="staffName"]').val()
				if (eventObj.find('input[name="isStaffDeleted"]').val() == 'true') {
					//Staff has been deleted
					selectedStaffName += ' has been deleted';
				}
				staffSetGroupDispSpan.html(selectedStaffName).addClass('text-danger');
			} else {
				staffSetGroupDispSpan.removeClass('text-danger');
				toggleAvailableMarkOnField(modalStaffDd, 1);
				setFieldNeutral(modalStaffDd);
				var selectedClass = classDd.val(),
				duration = classModal.find('select[name="classDur"]').val();
				if (selectedClass && duration) {
					var formData = {},
					eventDate = setEventDate(classModal, 'submit');
					formData['date'] = eventDate.date;
					formData['day'] = eventDate.day;
					formData['classTime'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
					formData['duration'] = duration;
					if (eventObj.length)
						/*{
							var defaultStaff = eventObj.find('input[name="staffId"]').val();*/
							formData['eventId'] = eventObj.find('input[name="eventId"]').val();
					/*}
					else
						var defaultStaff = 0;*/
					$.get(public_url + 'settings/business/classes/' + selectedClass + '/free-staffs', formData, function(data) {
						populateStaffs(data)
					}, 'json');
				} else populateStaffs()
			}
		}
	}
	/* end: Get staffs list according to class selected */
	/* start: Get areas list according to class selected */
	function getAreasBasedOnClass() {
		return;
		if (isUserType(['Admin'])) {
			var classDd = classModal.find('select[name="staffClass"]'),
			areaDd = classModal.find('select[name="modalLocArea"]'),
			areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp')
			/*,
						options = '',
						span = ''*/
						;
						if (!isFieldMarkedAsAvail(classDd)) {
				//Class has been deleted
				//areaDd.html(options).selectpicker('refresh');
				areaDd.html('').selectpicker('refresh');
				var span = '';
				eventObj.find("input[name='area[]']").each(function() {
					var data = JSON.parse($(this).val());
					if (data.isAreaDeleted == true) {
						//Area has been deleted
						span += '<span class="text-danger">' + data.locAreaName + ' has been deleted</span><br>';
					} else span += '<span>' + data.locAreaName + '</span><br>';
				});
				span = span.slice(0, -4)
				areaSetGroupDisp.find('span').remove()
				areaSetGroupDisp.find('br').remove()
				areaSetGroupDisp.prepend(span)
			} else {
				areaSetGroupDisp.find('span').remove()
				areaSetGroupDisp.find('br').remove()
				toggleAvailableMarkOnField(areaDd, 1);
				setFieldNeutral(areaDd);
				var selectedClass = classDd.val(),
				duration = classModal.find('select[name="classDur"]').val();
				if (selectedClass && duration) {
					var formData = {},
					eventDate = setEventDate(classModal, 'submit');
					formData['date'] = eventDate.date;
					formData['day'] = eventDate.day;
					formData['classTime'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
					formData['duration'] = duration;
					if (eventObj.length)
						/*{
							var isEditMode = 1;*/
							formData['eventId'] = eventObj.find('input[name="eventId"]').val();
					/*}
					else
						var isEditMode = 0;*/
					$.get(public_url + 'settings/business/classes/' + selectedClass + '/free-areas', formData, function(data) {
						populateAreas(data);
						return;
						var ids = [];
						if (!$.isEmptyObject(data)) {
							$.each(data, function(key, value) {
								options += '<option value="' + key + '">' + value + '</option>';
								ids.push(parseInt(key, 10));
							});
						}
						var selectedAreas = [];
						if (isEditMode) {
							//edit case
							/*var temp = '',
							flag = false;*/
							eventObj.find("input[name='area[]']").each(function() {
								var data = JSON.parse($(this).val())
								/*,
																isAvail = 1,
																isDel = 0*/
																;
																if ($.inArray(data.id, ids) < 0) {
									//area list does not have selected area
									/*isAvail = 0;
									if(data.isAreaDeleted == true){
										//Area has been deleted
										isDel = 1;
									}
									flag = true;*/
									toggleAvailableMarkOnField(areaDd, 0);
									if (data.isAreaDeleted == true) {
										//Area has been deleted
										span += '<span class="text-danger">' + data.locAreaName + ' has been deleted</span><br>';
									} else span += '<span class="text-danger">' + data.locAreaName + ' is not available</span><br>';
								} else
									//temp += '<option value="'+data.id+'" data-is-avail="'+isAvail+'" data-is-del="'+isDel+'">'+data.locAreaName+'</option>'
									selectedAreas.push(data.id)
								});
							/*if(flag)
							options = temp;*/
						} else {
							//add case
							var areaId = findAreaId();
							if (areaId && areaId != 'all' && $.inArray(areaId, ids) >= 0) {
								//filter has area selected and area list have filter selected area
								selectedAreas.push(areaId)
							}
						}
						areaDd.html(options);
						if (!selectedAreas.length && options) selectedAreas.push(areaDd.find('option:first').val())
							areaDd.val(selectedAreas);
						areaDd.find('option:selected').each(function() {
							var $this = $(this);
							/*if($this.data('is-del') == '1')
								span += '<span class="text-danger">'+$this.text()+' has been deleted</span><br>';
							else if($this.data('is-avail') == '0')
								span += '<span class="text-danger">'+$this.text()+' is not available</span><br>';
							else*/
								span += '<span>' + $this.text() + '</span><br>';
						});
						if (span) span = span.slice(0, -4)
							else span = '<span class="text-danger">No area found</span>';
						areaSetGroupDisp.prepend(span)
						areaDd.selectpicker('refresh');
					}, 'json');
				} else populateAreas();
			}
		}
	}
	/* end: Get areas list according to class selected */
	/* start: Populate services based on the staffs */
	function getStaffServices(staffDd, defaultServices) {
		return;
		//if(1==1 /*|| !isFieldMarkedAsDeleted(staffDd)*/){
			var formData = {};
			var isStaffMarkedAsDeleted = isFieldMarkedAsDeleted(staffDd);
			if (isStaffMarkedAsDeleted) formData['staffId'] = isStaffMarkedAsDeleted;
			else formData['staffId'] = staffDd.val();
			var areaDd = appointModal.find('select[name="modalLocArea"]'),
			isAreaMarkedAsDeleted = isFieldMarkedAsDeleted(areaDd);
			if (isAreaMarkedAsDeleted) formData['areaId'] = isAreaMarkedAsDeleted;
			else formData['areaId'] = areaDd.val();
		//formData['areaId'] = staffDd.closest('form').find('select[name="modalLocArea"]').val();
		$.get(public_url + 'staffs/services', formData, function(data) {
			var //areaDd = appointModal.find('select[name="modalLocArea"]'),
			servicesDd = appointModal.find('select.serviceName'),
			selectedServices = [],
			selectedServicesObj = {},
			newServices = [],
			incompatibleServices = [],
			compatibleServices = [],
			options = '<option value="" selected>-- Select --</option>';
			if (staffDd.data('services-new-instance') != 'true') {
				servicesDd.each(function() {
					var $this = $(this),
					val = parseInt($this.val(), 10);
					if (val && $this.closest('.row ').find('input[type="hidden"]').val() == 'false' && $.inArray(val, selectedServices) === -1) {
						var row = $this.closest('.row');
						selectedServicesObj[val] = {
							name: $this.find('option:selected').text(),
							/*time:row.find('.datetimepicker input').val()*/
							time: row.find('input.timepicker1').val(),
							duration: row.find('select.serviceDur').val(),
							price: row.find('.servicePrice').val()
						};
						selectedServices.push(val);
					}
				})
			}
			staffDd.data('services-new-instance', 'false')
			if (data.length) {
				$.each(data, function(val, text) {
					newServices.push(this.id);
					options += '<option value="' + this.id + '" data-duration="' + this.duration + '" data-price="' + this.price + '">' + this.name + '</option>';
				});
			}
			if (selectedServices.length) {
				$.grep(selectedServices, function(el) {
					if ($.inArray(el, newServices) == -1) incompatibleServices.push(el);
					else compatibleServices.push(el);
				});
				if (incompatibleServices.length) {
					var servicesText = '';
					$.each(incompatibleServices, function(key, val) {
						servicesText += '<li class="list-group-item">' + selectedServicesObj[val].name + '</li>'
					});
					swal({
						title: "This staff doesn't provide following services:",
						text: servicesText,
						/*type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#ff4401",
						confirmButtonText: "Still change",
						cancelButtonText: "Revert Staff",
						allowEscapeKey: false,
						html: true,
						customClass: 'staff-change-alert'*/
						type: staffChangeSwalOpt.type,
						showCancelButton: staffChangeSwalOpt.showCancelButton,
						confirmButtonColor: staffChangeSwalOpt.confirmButtonColor,
						confirmButtonText: staffChangeSwalOpt.confirmButtonText,
						cancelButtonText: staffChangeSwalOpt.cancelButtonText,
						allowEscapeKey: staffChangeSwalOpt.allowEscapeKey,
						html: staffChangeSwalOpt.html,
						customClass: staffChangeSwalOpt.customClass
					}, function(isConfirm) {
						if (!isConfirm) {
							if (!$.isEmptyObject(staffDdTempOpts)) {
								areaDd.val(areaDd.data('last-val')).selectpicker('refresh');
								staffDd.html(staffDdTempOpts.html).val(staffDdTempOpts.lastSelectedId);
								staffDd.closest('.set-group').find('.set-group-disp span').text(staffDdTempOpts.lastSelectedText).removeClass('text-danger');
								staffDd.selectpicker('refresh');
								staffDdTempOpts = {};
							}
							staffDd.val(staffDd.data('last-val')).selectpicker('refresh');
						} else {
							populateServiceRelatedFields(staffDd, options, compatibleServices, selectedServicesObj, areaDd)
							/*staffDd.data('last-val', staffDd.val())
								appointModal.find('.removeService').trigger('click');
								var dd = $('select[name="serviceName0"]');
								dd.html(options).removeAttr('data-unique-id');

								if(compatibleServices.length){
									var createdIdx = 0,
										i = 0;

									$.each(compatibleServices, function(key, value){
										if(createdIdx){
											appointModal.find('.addMoreService').trigger('click');
											createdIdx = formRowsCreated;
										}

										var serviceNameDd = appointModal.find('select[name="serviceName'+createdIdx+'"]');
										serviceNameDd.attr('data-unique-id', i).val(value);
										toggleField_DelMsg({kase:'exist', field:serviceNameDd});

										appointModal.find('input[name="serviceTime'+createdIdx+'"]').val(selectedServicesObj[value].time);
										appointModal.find('select[name="serviceDur'+createdIdx+'"]').val(selectedServicesObj[value].duration);
										appointModal.find('input[name="servicePrice'+createdIdx+'"]').val(selectedServicesObj[value].price);
										appointModal.find('input[name="serviceIsDeleted'+createdIdx+'"]').val('false');
										if(!createdIdx)
											createdIdx++;
										i++;
									});
								}
								else{
									dd.trigger('change');
									toggleField_DelMsg({kase:'exist', field:dd});
								}
								appointModal.find('.services select').selectpicker('refresh');*/
							}
						});
				} else populateServiceRelatedFields(staffDd, options, compatibleServices, selectedServicesObj, areaDd)
			} else {
				areaDd.data('last-val', areaDd.val())
				staffDd.data('last-val', staffDd.val())
				//appointModal.find('.removeService').trigger('click');
				removeServiceRow()
				var dd = $('select[name="serviceName0"]');
				dd.html(options).removeAttr('data-unique-id');
				if (defaultServices != null && defaultServices && eventServices.length) {
					var createdIdx = 0,
					i = 0;
					$.each(eventServices, function(key, value) {
						if ((bookNextAppointMode && this.isDeleted != 'true' && !isStaffMarkedAsDeleted && !isAreaMarkedAsDeleted) || !bookNextAppointMode) {
							if (createdIdx) {
								appointModal.find('.addMoreService').trigger('click');
								createdIdx = formRowsCreated;
							}
							var serviceNameDd = appointModal.find('select[name="serviceName' + createdIdx + '"]');
							serviceNameDd.attr('data-unique-id', i);
							if (this.isDeleted == 'true' || isStaffMarkedAsDeleted || isAreaMarkedAsDeleted) {
								serviceNameDd.append('<option value="' + this.id + '" selected>' + this.name + '</option>')
								if (this.isDeleted == 'true') toggleField_DelMsg({
									kase: 'trashed',
									field: serviceNameDd,
									entity: this.name
								});
									else toggleField_DelMsg({
										kase: 'exist',
										field: serviceNameDd
									});
								} else {
									serviceNameDd.val(this.id);
									toggleField_DelMsg({
										kase: 'exist',
										field: serviceNameDd
									});
								}
							/*var timeField = appointModal.find('input[name="serviceTime'+createdIdx+'"]'),
								time = dbTimeToTimeString(this.time);
							timeField.val(time).data('default-time', time);
							workingHoursTimePicker(timeField)*/
							setFieldForTimepicker(appointModal.find('input[name="serviceTime' + createdIdx + '"]'), dbTimeToTimeString(this.time))
							appointModal.find('select[name="serviceDur' + createdIdx + '"]').val(this.duration);
							appointModal.find('input[name="servicePrice' + createdIdx + '"]').val(this.price);
							appointModal.find('input[name="serviceIsDeleted' + createdIdx + '"]').val(this.isDeleted);
							if (!createdIdx) createdIdx++;
							i++;
						}
					});
					bookNextAppointMode = false;
				} else {
					dd.trigger('change');
					toggleField_DelMsg({
						kase: 'exist',
						field: dd
					});
				}
				appointModal.find('.services select').selectpicker('refresh');
			}
		}, 'json');
		//}
	}
	/* end: Populate services based on the staffs */
	/* start: Populate service related fields such as duration, price etc */
	function populateServiceRelatedFields(staffDd, options, compatibleServices, selectedServicesObj, areaDd) {
		removeServiceRow();
		var dd = $('select[name="serviceName0"]');
		dd.html(options).removeAttr('data-unique-id');
		if (compatibleServices.length) {
			var createdIdx = 0,
			i = 0;
			$.each(compatibleServices, function(key, value) {
				if (createdIdx) {
					appointModal.find('.addMoreService').trigger('click');
					createdIdx = formRowsCreated;
				}
				var serviceNameDd = appointModal.find('select[name="serviceName' + createdIdx + '"]');
				serviceNameDd.attr('data-unique-id', i).val(value);
				toggleField_DelMsg({
					kase: 'exist',
					field: serviceNameDd
				});
				setFieldForTimepicker(appointModal.find('input[name="serviceTime' + createdIdx + '"]'), selectedServicesObj[value].time)
				appointModal.find('select[name="serviceDur' + createdIdx + '"]').val(selectedServicesObj[value].duration);
				appointModal.find('input[name="servicePrice' + createdIdx + '"]').val(selectedServicesObj[value].price);
				appointModal.find('input[name="serviceIsDeleted' + createdIdx + '"]').val('false');
				if (!createdIdx) createdIdx++;
				i++;
			});
		} else {
			dd.trigger('change');
			toggleField_DelMsg({
				kase: 'exist',
				field: dd
			});
		}
		appointModal.find('.services select').selectpicker('refresh');
	}
	/* end: Populate service related fields such as duration, price etc */
	/* Start: invoice delete confirmation */
	function invoiceDelConfirm(action, callback) {
		if (action) {
			swal({
				title: "Do you want to delete invoice also?",
				text: (typeof warningText != 'undefined' && warningText) ? warningText : '',
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d43f3a",
				confirmButtonText: "Yes, delete it!",
				//cancelButtonText: "No",
				allowOutsideClick: false,
				customClass: 'delete-alert'
			}, function(isConfirm) {
				if (isConfirm) callback(true);
				else callback(false);
			});
		} else {
			callback(false);
		}
	}
	/* End: invoice delete confirmation */
	/* Start: remove client action confirmation */
	function removeClientActionCormfirmation(action, message, callback) {
		if (action) {
			swal({
				title: message,
				text: (typeof warningText != 'undefined' && warningText) ? warningText : '',
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#d43f3a",
				confirmButtonText: "Yes",
				cancelButtonText: "No",
				allowOutsideClick: false
			}, function(isConfirm) {
				if (isConfirm) callback(true);
				else callback(false);
			});
		} else {
			callback(false);
		}
	}
	/* End: remove client action confirmation */
	/* Start: Marge two class */
	function margeClass(fromEventId, toEventId) {
		toggleWaitShield("show", 'margeClass');
		var formData = {
			'fromEventId': fromEventId,
			'toEventId': toEventId
		};
		if (fromEventId && toEventId) {
			$.post(public_url + 'staffevents/classes/marge', formData, function(response) {
				var data = JSON.parse(response);
				if (data.status == "success") {
					resetAllModes();
					isClassMarge = false;
					rescheduledClient = data.message;
					showBooking.id = formData['toEventId'];
					showBooking.type = 'class';
					areaDdCalend.selectpicker('val', areaDdCalendLastVal);
					getAreaStaffs(areaDdCalend, staffFilterCalendLastVal);
				} else if (data.status == "error") {
					openSelDateNotif('Choose a class to merge.');
					showNotific(data.message);
					window.scrollTo(0, 0);
				}
				toggleWaitShield("hide", 'margeClass');
			})
		}
	}
	/* End: Marge two class */
	/* Start: Toggel marge button */
	function toggelMargeButton(mode) {
		if (typeof eventObj.length != 'undefined' && (eventObj.find("input[name='client[]']")).length > 0 && getBookingType() == 'class') {
			classModal.find('.marge-cls').show();
		} else {
			classModal.find('.marge-cls').hide();
		}
	}
	/* End: Toggel marge button */
	/* Start: Reset makeup/recurrence check box */
	function resetMakeupRecurrField() {
		var reucrrGroup = $('#ifRecur').closest('.form-group'),
		makeupGroup = $('#isCreatingMakeUpSession').closest('.form-group');
		if (reucrrGroup.hasClass('hidden')) reucrrGroup.removeClass('hidden');
		if (makeupGroup.hasClass('hidden')) makeupGroup.removeClass('hidden');
	}
	/* End: Reset makeup/recurrence check box */

    $('body').on('change','#busyModal #busyDur',function(){
		getStaffsAndAreas('busy', function(){
			if(isModalModeEdit(busyModal)){
				var text = "<a class='btn btn-primary m-r-10 saveBusyField' href='#' data-target-event='future' data-field='duration'>This and future</a><a class='btn btn-primary saveBusyField' href='#' data-target-event='this' data-field='duration'>This only</a>"
				var label = 'duration';
				/*else if(label == 'autoExpire')
				label = 'auto expire';*/
				swal({
					title: 'Update ' + label + '?',
					allowOutsideClick: true,
					html: true,
					text: text,
					showConfirmButton: false,
					customClass: 'busyClientUnlinkAlert'
				});
			}

		})
	});

	$('body').on('change','#busyModal #staff-busy',function(){
		getAvailableStaffs();
		 //    if(isModalModeEdit(busyModal)){
		// 		var text = "<a class='btn btn-primary m-r-10 saveBusyField' href='#' data-target-event='future' data-field='staff'>This and future</a><a class='btn btn-primary saveBusyField' href='#' data-target-event='this' data-field='staff'>This only</a>"
		// 		var label = 'staff';
		// 		/*else if(label == 'autoExpire')
		// 		label = 'auto expire';*/
		// 		swal({
		// 			title: 'Update ' + label + '?',
		// 			allowOutsideClick: true,
		// 			html: true,
		// 			text: text,
		// 			showConfirmButton: false,
		// 			customClass: 'busyClientUnlinkAlert'
		// 		});
		// 	}

		
	});
	$('body').on('click','#busyModal .available_staff_list',function(){
		var isRepeat = busyModal.find('input[name="isRepeating"]').val();
		if(isModalModeEdit(busyModal)){
			var text = `${isRepeat == 1?("<a class='btn btn-primary m-r-10 saveBusyField thisandfuture' href='#' data-target-event='future' data-field='staff'>This and future</a>"):''}<a class='btn btn-primary saveBusyField' href='#' data-target-event='this' data-field='staff'>This only</a>`
			var label = 'staff';
			/*else if(label == 'autoExpire')
			label = 'auto expire';*/
			swal({
				title: 'Update ' + label + '?',
				allowOutsideClick: true,
				html: true,
				text: text,
				showConfirmButton: false,
				customClass: 'busyClientUnlinkAlert'
			});
		}
	})

	$('body').on('click', '.saveBusyField', function(e) {
		e.preventDefault();
		swal.close();
		var areaDd = busyModal.find('select[name="modalLocArea"]');
        isFormValid = true;
		if(isFieldMarkedAsDeleted(areaDd)){
				isFormValid = false;
		}
		formData = {};
		var targetEvents = $(this).data('target-event');
		field = $(this).data('field');
		if(field == 'time' || field == 'duration'){
			formData['time'] = busyModal.find('input[name="eventTime"]').val();
			formData['duration']= busyModal.find('#busyDur').val();
		}else if(field == 'staff'){
			formData['staffName'] = [];
			busyModal.find('input[name="staffName"]:checked').each(function(){
				formData['staffName'].push($(this).val());
			});
			if(formData['staffName'].length <= 0){
				isFormValid = false;
			}
		}
		busyTabid = busyModal.find('ul.nav li.active a').attr('href');
		formData['step']  = busyTabid.substring(1);
		formData['field'] = field;
		formData['targetEvent'] = targetEvents;
		formData['eventId'] = busyModal.find('input[name="eventId"]').val();
		busyModal.find('input[name="targetEvents"]').val(targetEvents);
		ajaxAction = `${public_url}staffevents/busy-time/update-data-field`;
        if(isFormValid){
		$.post(ajaxAction, formData, function(data) {
			var data = JSON.parse(data);
			if(data.status == 'updated'){
				if(data.staff != null){
					var staffHtml = "";
						$.each(data.staff, function(key, value){
						staffHtml += `<div class="staffList" data-staff-id="${value.id}">${value.staffName}</div>`;
					});
			busyModal.find('.selected_staff_list').empty().append(staffHtml).closest('.form-group').show();
				}
				reloadEvents = true;		
			}else{
				var mssg = '';
				$.each(data.errorData, function(key, val){
					$.each(this, function(errorType, message){
						if(errorType == "workingHourUnavail" ){
							mssg += message+'<br>';
						} 								
					});							
				});
				swal({
					title: 'Error',
					allowOutsideClick: true,
					html: true,
					text:mssg,
					showConfirmButton: true,
					customClass: 'busyClientUnlinkAlert'
				});
			}
		});
	    }
		// saveBusyField($(this));
	});
	function getAvailableStaffs(){
		var formData = {},
		staff = [];
		formData['areaId'] = busyModal.find('select[name="modalLocArea"]').val();
		formData['duration'] = busyModal.find('input[name="busyDur"]').val();
		eventDate = setEventDate(busyModal,'submit');
		formData['date'] = eventDate.date;
		formData['day'] = eventDate.day;
		formData['time'] = timeStringToDbTime(busyModal.find('input[name="eventTime"]').val());
		formData['eventId'] = busyModal.find('input[name="eventId"]').val();
         busyModal.find('select[name="staff"] option:selected').each(function(){
			  staff.push($(this).val());
		 })
		formData['staffId'] = staff;
		ajaxAction = `${public_url}settings/business/busy-time/get-available-staff`;
			$.post(ajaxAction, formData, function(data) {
				var staffListHtml = "";
				if(!jQuery.isEmptyObject(data)){

					$.each(data, function(key, value){
					staffListHtml +=`<div class="checkbox clip-check check-primary checkbox-inline m-b-0"><input type="checkbox" name="staffName" value="${key}" id="staff${key}"><label for="staff${key}">${value.name} </label></div>`;
				    });
			   }else{
				staffListHtml += `<div class="text-danger">No Staff available</div>`;
			    }
                     
				busyModal.find('.available_staff_list').empty().append(staffListHtml);
				
			});
			busyModal.find('.availableStaff').show();
	}
	
	function enterSalesProcessBookMode(stepNumb, id, type,isSalesProcessBookStep){
		if(!isRescheduleClientMode()){
			reloadEvents = false;
			areaDdCalendLastVal = findAreaId();
			staffFilterCalendLastVal = findStaffId();
			classModal.modal('hide');
			parent.salesProcessStepNumb = true;
			parent.salesProcessStepNumb =stepNumb;
			idClient = id;
			// var bookingType = getBookingType();
			if(type == 'class'){
				openSelDateNotif('Choose a class to book with Sales Process.');
				// bookTeam(idClient);
				
			}else if(type == 'service'){
			
				if(isSalesProcessEvent()){
					// var stepNumb = 4;
					openSelDateNotif('Choose a date for this service  to book with Sales Process.');
					// clientClassData = classClientsData;
					getServices('', getStaffsAndAreas);
					// setBookingType(bookingTypeAdd);
					disableBookTypeSelectable();
					// if(!isNaN(stepNumb)){
					// 	if(stepNumb == 4 || stepNumb == 5 || $.inArray(stepNumb, sessionSteps()) > -1){
					// 		setEnableDisableDateFields();
					// 	}
					// }
					// var ajaxAction = 'sales-process/' + stepNumb;
					// 			$.get(public_url + 'settings/business/services/' + ajaxAction, function(data) {
					// 				populateServices(data, defaultService, callback);
					// 			}, 'json');
				}	

				// if(calledFrom == 'client-profile') {
				// 	getAreaStaffs(areaDdCalend, staff);
				// 	openSubview('calendar', calendarSubviewConds()+'&resheduleAppointFromOverview=true&eventId='+serviceEventId);
				// }
			}
			if(!window.location.href.includes('client')){// Client Profile
				setTimeout(function(){ // Timeout
					$('html, body').animate({
						scrollTop: $('td.fc-today:first').offset().top 
					}, 1500);
				},2000);
			}
			// scrollToToday();
		}
	
	}