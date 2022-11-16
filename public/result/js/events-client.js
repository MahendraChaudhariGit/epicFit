var appointModal = $('#appointModal'),
appointCancelModal = $('#appointCancelModal'),	
calPopupHelper = $('#calPopupHelper'),

formRowsCreated = 0,
clientLinkOrder = 0,
bookingMode = '';

pastCurrentMode = false,
reloadEvents = false,
bookNextAppointMode = false,
notAvailAjax = false,
blockTimepickerChangeEvent = false,

eventModal = null,
eventObj = {},
eventServices = [],
shownPopover = [],
defaultClientDetails = {},
staffDdTempOpts = {},
promtSerDel={status:false};

clientSwalOpt = {
	title: "What do you want to do?",
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
	title: function(){
		if(eventModal == 'classModal' && promtSerDel.status==false)
			return "<strong>Cancel this booking?</strong>"
		else
			return "<strong>Cancel this booking?</strong>"
			/*else
			return "<strong>Delete this busy time?</strong>"*/
		},
		trigger: 'manual'
	},
	deleteReccurEventPopoverOpt = {
		html: true,
		content: "<a class='btn btn-default m-r-10 cancel-delete-event' href='#'><i class='fa fa-times'></i> No</a> <a class='btn btn-red delete-event' href='#'><i class='glyphicon glyphicon-trash'></i> Yes</a>",
		container: popoverContainer,
		title: function(){
			if(eventModal == 'classModal'){
				var bookingType = getBookingType();
				if(bookingType == 'class')
					return "<strong>Cancel this booking?</strong>"
				else if(bookingType == 'service')
					return "<strong>Cancel this booking?</strong>"
			}
			else
				return "<strong>Cancel recurring booking?</strong>"
		},
		trigger: 'manual'
	},
	editReccurEventPopoverOpt = {
		placement: 'left',
		html: true,
		content: "Would you like to change only this event,<br> or this and all following events in the<br> series? <a class='btn btn-default btn-block update-event' href='#' data-target-event='this'>This only</a>",
		container: popoverContainer,
		title: function(){
			if(eventModal == 'classModal'){
				var bookingType = getBookingType();
				if(bookingType == 'class')
					return "<strong>Edit recurring class?</strong>"
				else if(bookingType == 'service')
					return "<strong>Edit recurring service?</strong>"
			}
			else
				return "<strong>Edit recurring booking?</strong>"
		},
		trigger: 'manual'
	};

	if(isRescheduleClientMode()){
		var classModal = parent.classModal,

		classClients = parent.classClients,
		rescheduleClients = parent.rescheduleClients;
	}
	else{
		var classModal = $('#classModal'),

		classClients = {},
		rescheduleClients = {};
	}

	$(document).ready(function(){
		/* start: Initialize Section */

		/* start: Preparing default client details, if any */
		var defaultClient = $('input[name="defaultClient"]');
		if(defaultClient.length)
			defaultClientDetails = jQuery.parseJSON(defaultClient.val());	
		/* end: Preparing default client details, if any */

		/* start: Initialize clients typeahead */
		if(!$.isEmptyObject(defaultClientDetails)){
			initTypeahead(defaultClientDetails)
		}	
		else{
			var clientsDetails = $('input[name="clientsDetails"]');
			if(clientsDetails.length){
				var clientsDetails = JSON.parse($('input[name="clientsDetails"]').val());	
			}
			else
				fetchClientsForTypeahead('create');
		}
		/* end: Initialize clients typeahead */

		/* start: Initialize datepicker */
		$('.eventDatepicker').datepicker({autoclose:true, dateFormat:"d M yy"/*, format:"d M yyyy"*/, minDate: 0, startDate: new Date()});
		/* end: Initialize datepicker */

		/* start: Initialize calPopupHelper */
		calPopupHelper.click(function(){
			if(shownPopover.length !== 0){
				shownPopover[0].popover('destroy');
				shownPopover = [];
			}
			$(this).addClass('hidden').removeClass('superior');
		})
		appointCancelModal.add(appointModal).add(classModal).on('hide.bs.modal', function(){
			calPopupHelper.trigger("click");
		});
		/* end: Initialize calPopupHelper */

		/* end: Initialize Section */

		/* Start: signup class click event */
		$(".submit-class-signup").click(function(){
			var modal = $(this).closest('.modal'),
			form = modal.find('#signupClassForm');
			classSignupAjax(form);
		});
		/* End: signup class click event */

		/* Start : signup class modal form clear on hide action*/
		$('#classSignupModal').on('hidden.bs.modal', function () {
			$(this).find('.errorMag').empty();
			clearForm($('#classSignupModal').find('form'));
		});
		/* Start : signup class modal form clear on hide action*/

		/* start: Common Modules Section */

		/* start: show staff, location-area selector on click */
		$('.set-group-disp a').click(function(e){
			e.preventDefault();
			var $this = $(this),
			setGroup = $this.closest('.set-group'),
			setGroupDisp = setGroup.find('.set-group-disp'),
			dd = setGroup.find('select');

			setGroupDisp.hide().siblings().removeClass('hidden');
			dd.removeClass('hidden').prop('required', true);

			if(isFieldMarkedAsDeleted(dd)){
				toggleDeletedMarkOnField(dd, 0)
				setFieldNeutral(dd);

				if(dd.attr('name') == 'modalLocArea')
					getAreaStaffs(dd);
			}
			else if(!isFieldMarkedAsAvail(dd)){
				toggleAvailableMarkOnField(dd, 1);
				setFieldNeutral(dd);
			}
		});
		/* end: show staff, location-area selector on click */

		/* start: Toggle new client fields */
		$('input[name="isNewClient"]').change(function(){
			var form = $(this).closest('form'),
			newClientField = form.find('input[name="clientName"]'),
			clientTypeaheadField = form.find('.clientList'),
			clientDetailsFields = form.find('.clientDetails');

			if($(this).is(":checked")){
				setFieldNeutral(newClientField);
				clientDetailsFields.prop('readonly', false);
				clientTypeaheadField.hide();
				newClientField.show().val(clientTypeaheadField.val())
				toggleIf_make_up_sessionOption('hide');
			}
			else{
				setFieldNeutral(clientTypeaheadField);
				clientDetailsFields.prop('readonly', true);
				clientTypeaheadField.show();
				newClientField.hide();
				form.find('div.new-client-req-msg').addClass('hidden');
			}
			clientDetailsFields.val('');
			clientTypeaheadField.val('');
			form.find('input[name="clientId"]').val('');
		})
		/* end: Toggle new client fields */

		/* start: Toggle event repeat fields */
		$('select[name="eventRepeat"]').change(function(){
			var $this = $(this),
			selVal = $this.val(),
			eventRepeatFields = $this.closest('.event-reccur').find('.eventRepeatFields'),
			eventRepeatIntervalDd = eventRepeatFields.find('select[name="eventRepeatInterval"]'),
			eventRepeatIntervalUnit = eventRepeatFields.find('.eventRepeatIntervalUnit'),
			eventRepeatWeekdays = eventRepeatFields.find('.eventRepeatWeekdays');

			eventRepeatWeekdays.hide();
			setFieldNeutral(eventRepeatWeekdays)
			if(selVal == 'Daily' || selVal == 'Weekly' || selVal == 'Monthly'){
				eventRepeatFields.show();
				eventRepeatIntervalDd.prop('required', true)

				if(selVal == 'Daily')
					eventRepeatIntervalUnit.text('days')
				else if(selVal == 'Weekly'){
					eventRepeatWeekdays.show();
					eventRepeatIntervalUnit.text('weeks');
				}
				else
					eventRepeatIntervalUnit.text('months')
			}
			else{
				eventRepeatFields.hide();
				eventRepeatFields.find(':input').prop('required', false)
			}
		});
		/* end: Toggle event repeat fields */

		/* start: Neutral event repeat weekdays checkbox */
		$('.eventRepeatWeekdays input[type="checkbox"]').change(function(){
			setFieldNeutral($(this))
		});
		/* end: Neutral event repeat weekdays checkbox */

		/* start: Toggle event repeat end fields */
		$('input[name="eventRepeatEnd"]').change(function(){
			var $this = $(this),
			selVal = $this.val(),
			module = $this.closest('.event-reccur'),
			eventRepeatEndAfterOccurDd = module.find('select[name="eventRepeatEndAfterOccur"]'),
			eventRepeatEndOnDate = module.find('input[name="eventRepeatEndOnDate"]');

			if(selVal == 'After'){
				eventRepeatEndAfterOccurDd.prop({'disabled':false, 'required':true})
				eventRepeatEndOnDate.prop('disabled', true);
				setFieldNeutral(eventRepeatEndOnDate)
			}
			else if(selVal == 'On'){
				eventRepeatEndAfterOccurDd.prop('disabled', true);
				setFieldNeutral(eventRepeatEndAfterOccurDd)
				eventRepeatEndOnDate.prop({'disabled':false, 'required':true})
			}
			else{
				eventRepeatEndAfterOccurDd.prop('disabled', true);
				eventRepeatEndOnDate.prop('disabled', true);
				setFieldNeutral(eventRepeatEndAfterOccurDd)
				setFieldNeutral(eventRepeatEndOnDate)
			}
			eventRepeatEndAfterOccurDd.selectpicker('refresh')
		});
		/* end: Toggle event repeat end fields */

		/* start: Update recurring event */
		$('body').on("click", '.update-event', function(e){
			e.preventDefault();
			var targetEvents = 'this';/*$(this).data('target-event')*/
			$('#'+eventModal).find('input[name="targetEvents"]').val(targetEvents)
			if(eventModal == 'classModal')
				submitClassModal();
			else
				submitAppointmentModal();
		});
		/* end: Update recurring event */

		/* start: Delete recurring event */
		$('body').on("click", '.delete-event', function(e){
			e.preventDefault();

			var targetEvents = 'this'

			/*$(this).data('target-event')*/
		/*if(eventModal == 'appointModal')
			deleteAppoint(false, targetEvents);
		else{
			var formData = {};
			formData['eventId'] = eventObj.find('input[name="eventId"]').val();
			if(targetEvents != null)
				formData['targetEvents'] = targetEvents;

			if(eventModal == 'classModal')
				var eventEntity = 'classes';
			else
				var eventEntity = 'busy-time';

			$.post(public_url+'staffevents/'+eventEntity+'/delete', formData, function(data){
				var data = JSON.parse(data);
				if(data.status == "deleted"){
					cancelEventSuccess(data);
				}
			});
		}*/
		if(eventModal == 'classModal' && getBookingType() == 'service'){
		var text= "";
		membership = classModal.find(".delete-prompt-service").data('membership');
		invoice = classModal.find(".delete-prompt-service").data('invoice');
		epicCredit =classModal.find(".delete-prompt-service").data('client-makeup');
		isLdc = classModal.find(".delete-prompt-service").data('isLdc');
		if((membership > 0 && isLdc == 0) || epicCredit == '1'){ 
		text = "<a class='btn btn-primary unlinkClientServiceWithEpicCash' href='#'  >Add to EPIC Credit</a>";
		}
			swal({
				title: clientSwalOpt.title,
				showCancelButton: clientSwalOpt.showCancelButton,
				cancelButtonText: 'Just delete it',
				allowOutsideClick: clientSwalOpt.allowOutsideClick,
				html: clientSwalOpt.html,
				text: text,
				showConfirmButton: clientSwalOpt.showConfirmButton,
				customClass: clientSwalOpt.customClass     
			}, 
			function(isConfirm){
				if(!isConfirm){
					deleteAppoint(false, targetEvents, 'no');

				}
				
			});
		}
		else{

			var formData = {};
			formData['eventId'] = eventObj.find('input[name="eventId"]').val();
			formData['amount'] = $('#classModal').find('p[id="price_mod"]').html();
			formData['clientId'] = $('#classModal').find('input[name="clientId"]').val();
			formData['purpose'] = 'class';
			formData['action'] = 'raise';



//			formData['eventDateNow']=moment().format('YYYY-MM-DD HH:mm:ss');
//			if(targetEvents != null)
//				formData['targetEvents'] = targetEvents;

if(eventModal == 'classModal')
	var eventEntity = 'classes';
else
	var eventEntity = 'busy-time';

				//console.log(formData)

//			$.post(public_url+'staffevents/'+eventEntity+'/delete', formData, function(data){
//				var data = JSON.parse(data);
//				if(data.status == "deleted"){
//					cancelEventSuccess(data);
//				}
//			});

$.post(public_url+'clients/raise-make-up', formData, function(data){


	var data = JSON.parse(data);

	if(data.status == "success"){

                    // showNotific(prepareNotific('success', 'Makeup created successfully.'));
                    showClientBookedMsg('Makeup created successfully.');
                    cancelEventSuccess(data);
                }
            });
}
});

		$('body').on("click",'.unlinkClientServiceWithEpicCash',function(e){
			var targetEvents = 'this';
			deleteAppoint(false, targetEvents, 'yes');
		});
		/* end: Delete recurring event */

		/* start: Populate staffs based on the area */
		$('select.loc-area-dd').change(function(){

			var $this = $('this');

			if(!$this.hasClass('toolBarDd'))
				toggleDeletedMarkOnField($this, 0)

			getAreaStaffs($this);
		});


/*
		var $loc_area = $('loc-area-dd');

		if(!$loc_area.hasClass('toolBarDd'))
			toggleDeletedMarkOnField($loc_area, 0)
		getAreaStaffs($loc_area);*/
		/* end: Populate staffs based on the area */

		/* start: Populate services/classes based on the area */
		$('select[name="staff"]').change(function(){
			var $this = $(this);
			toggleDeletedMarkOnField($this, 0)

			if(getBookingType() == 'class'){
				var areaDd = classModal.find('select[name="modalLocArea"]');
				if(areaDd.find('option').length){
					var selectedStaffFavAreas = getFavAreas();
					if(selectedStaffFavAreas.length)
						setAreas(selectedStaffFavAreas);
				}
			}
		});
		/* end: Populate services/classes based on the area */
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
		$('input[name="ifAutoExpireAppoint"]', appointModal).change(function(){
			var autoExpireAppoint = $('.autoExpireAppoint'),
			autoExpireAppointDurCustom = $('.autoExpireAppointDurCustom'),
			autoExpireAppointDurCustomFields = autoExpireAppointDurCustom.find('input'),
			dd = autoExpireAppoint.find('select');

			if($(this).is(":checked")){
				autoExpireAppoint.show();
				dd.val('');
				dd.prop('required', true)
				dd.selectpicker('refresh');
			}
			else{
				autoExpireAppoint.hide();
				dd.prop('required', false)
			}

			autoExpireAppointDurCustom.hide();
			autoExpireAppointDurCustomFields.prop('required', false)
		})
		/* end: Toggle event auto expire fields */

		/* start: Toggle manual date time fields for event auto expire */
		$('select[name="autoExpireAppointDur"]', appointModal).change(function(){
			var autoExpireAppoint = $('.autoExpireAppoint'),
			autoExpireAppointDurCustom = $('.autoExpireAppointDurCustom'),
			autoExpireAppointDurCustomFields = autoExpireAppointDurCustom.find('input');

			if($(this).val() == 'Custom'){
				autoExpireAppointDurCustom.show();
				autoExpireAppointDurCustomFields.prop('required', true)
				autoExpireAppoint.hide();
			}
			else
				autoExpireAppointDurCustomFields.prop('required', false)
		})
		/* end: Toggle manual date time fields for event auto expire */     

		/* start: Populate service fields based on service selected */
		$('body').on('change', '#appointModal select.serviceName', function(){
			var row = $(this).closest('.row'),
			selOpt = $(this).find(":selected");

			var price = selOpt.data('price');
			priceField = row.find('input.servicePrice');
			priceField.val(price);
			if(price)
				setFieldNeutral(priceField);

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
		$('input[name="serviceTime0"]', appointModal).blur(function(){
			setFieldNeutral(appointModal.find('.eventDateDisp'));
		});
		/* end: Time field neutral on blur */

		/* start: check on blur if entered time is less then previous service endtime */ 
		$('body').on('blur', '#appointModal .services input.timepicker1', function(){
			var $this = $(this),
			row =  $this.closest('.row');

			setFieldNeutral($this);

			var prevRow = row.prev(),
			eventDate = setEventDate(appointModal, 'submit'),
			date = eventDate.date,
			newTime = $this.val(),
			dur = row.find("select.serviceDur").val(),
			newDatetime = '',
			newEndDatetime = '';

			if(newTime)
				newDatetime = moment(date+' '+newTime, 'YYYY-MM-DD h:mm A').format('YYYY-MM-DD HH:mm:ss');
			if(dur && newDatetime)
				newEndDatetime = moment(newDatetime).add(dur, 'm').format("YYYY-MM-DD HH:mm:ss");

			if(prevRow.hasClass('m-t-5')){
				var prevRowEndDatetime = '',
				prevRowDur = prevRow.find("select.serviceDur").val();
				
				if(prevRowDur){
					var prevRowTime = prevRow.find('input.timepicker1').val(),
					prevRowDatetime = moment(date+' '+prevRowTime, 'YYYY-MM-DD h:mm A').format('YYYY-MM-DD HH:mm:ss');

					prevRowEndDatetime = moment(prevRowDatetime).add(prevRowDur, 'm').format("YYYY-MM-DD HH:mm:ss"); 
				}

				var changeTime = false;

				/* Check if new time is empty*/
				if(!newTime && prevRowEndDatetime)
					changeTime = true;
				else if(newTime && prevRowEndDatetime){
					var isSameDay = true;

					/*  Check if new end datetime is jumping to new date */
					if(newEndDatetime){
						isSameDay = moment(newEndDatetime).isSame(prevRowEndDatetime, 'day');
						if(!isSameDay)
							changeTime = true;
					}

					/*  Check if new new time is less than previous row end time */
					if(isSameDay){
						var isInPast = moment(newDatetime).isBefore(prevRowEndDatetime);
						if(isInPast)
							changeTime = true;
					}
				}

				if(changeTime)
					setFieldForTimepicker($this, moment(prevRowEndDatetime).format("h:mm A"))
			}
			else if(newDatetime && newEndDatetime){
				var isSameDay = moment(newDatetime).isSame(newEndDatetime, 'day');
				if(!isSameDay){
					setFieldForTimepicker($this, '')
					return;
				}
			}

			if(newEndDatetime){
				var nextRow = row.next();
				if(nextRow.length){
					var nextRowTimeField = nextRow.find('input.timepicker1');
					nextRowTimeField.trigger('blur');
				}
			}
		});
		/* end:check on blur if entered time is less then previous service endtime */

		/* start: Populate services over appointment cancel modal before showing */
	/*appointCancelModal.on('show.bs.modal', function(){
		var modal = $(this),
			services = '';

		if(eventServices.length){
			$.each(eventServices, function(val, text){
				if(this.isDeleted == 'false')
					services += '<li class="list-group-item">'+this.name+' with '+eventObj.find('input[name="staffName"]').val()+' on '+moment(eventObj.find('input[name="date"]').val()).format("D MMM")+' at '+this.time+'</li>';
			});
		}
		modal.find('#services').html(services);
		clearForm(modal.find('form'));
		modal.find('select').selectpicker('refresh');
	});*/
	/* end: Populate services over appointment cancel modal before showing */

	/* start: Submit appointment modal */
	$(".submit", appointModal).click(function(){
		var form = appointModal.find('form'),
		clientForm = appointModal.find('.client-form'),
		isFormValid = true;

		form.validate().settings.ignore = "input[type='hidden']";
		isFormValid = form.valid();

		if(!validateClientForm(clientForm, false))
			isFormValid = false;

		var staffDd = form.find('select[name="staff"]');
		if(staffDd.valid() && !isFieldMarkedAsAvail(staffDd)){
			isFormValid = false;
			setFieldInvalid(staffDd.closest('.form-group'), 'Please select new staff.')
		}

		var areaDd = form.find('select[name="modalLocArea"]');
		if(areaDd.valid() && !isFieldMarkedAsAvail(areaDd)){
			isFormValid = false;
			setFieldInvalid(areaDd.closest('.form-group'), 'Please select new area.')
		}

		/*var staffDd = form.find('select[name="staff"]');
		if(isFieldMarkedAsDeleted(staffDd)){
			isFormValid = false;
			setFieldInvalid(staffDd.closest('.form-group'), 'Please select new staff.')
		}

		var areaDd = form.find('select[name="modalLocArea"]');
		if(isFieldMarkedAsDeleted(areaDd)){
			isFormValid = false;
			setFieldInvalid(areaDd.closest('.form-group'), 'Please select new area.')
		}*/

		moveErrorMsg(appointModal);

		if(isFormValid){
			var isRepeating = form.find('input[name="isRepeating"]').val()
			if(isRepeating == 1){

				showPopoverOverModal($(this));
			}
			else
				submitAppointmentModal(form, clientForm);
		}
		else
			switchToErrorTab(form);
	});
	/* end: Submit appointment modal */
	
	/* start: Submit appointment cancel modal */	
	/*$(".submit", appointCancelModal).click(function(){
		eventModal = appointModal.prop('id');
		var form = appointCancelModal.find('form');

		if(form.valid()){
			var isRepeating = eventObj.find('input[name="isRepeating"]').val();

			if(isRepeating == 1)
				showPopoverOverModal($(this), deleteReccurEventPopoverOpt);
			else
				deleteAppoint(false);
		}
	});*/
	/* end: Submit appointment cancel modal */	

	/* start: to correct service start time if changed */
	$('body').on('change', '#appointModal select.serviceDur', function(e){
		var $this = $(this),
		newDur = $this.val(),
		refetchStaffs = true;

		setFieldNeutral($this);

		if(newDur){
			var row =  $this.closest('.row'),
			timeField = row.find("input.timepicker1"),
			time = timeField.val();

			if(time){
				var eventDate = setEventDate(appointModal, 'submit'),
				date = eventDate.date,
				datetime = moment(date+' '+time, 'YYYY-MM-DD h:mm A').format('YYYY-MM-DD HH:mm:ss'),
				newEndDatetime = moment(datetime).add(newDur, 'm').format("YYYY-MM-DD HH:mm:ss");

				isSameDay = moment(datetime).isSame(newEndDatetime, 'day');
				if(!isSameDay){
					var maxDatetime = moment(date+' 11:55 PM', 'YYYY-MM-DD h:mm A'),
					maxDur = maxDatetime.diff(datetime, 'minutes');

					$this.val(maxDur).selectpicker('refresh');
				}

				var nextRow = row.next();
				if(nextRow.length){
					refetchStaffs = false;
					timeField.trigger('blur');
				}
			}
		}
		if(refetchStaffs){
			getStaffsBasedOnServices();
			getAreasBasedOnServices();
		}
	});
	/* end: to correct service start time if changed */

	/* start: remove service row */
	$('body').on('click', '#appointModal .removeService', function(e){
		e.preventDefault();

		removeServiceRow($(this));
		getStaffsBasedOnServices();
		getAreasBasedOnServices();
	});
	/* end: remove service row */

	/* start: add service row */
	$('body').on('click', '#appointModal .addMoreService', function(e){
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
		serviceRow.find('select').each(function(index, elem){
			$elem = $(elem),
			$elem.parent().parent().html(elem);
			if($elem.hasClass('serviceName')){
				var ddName = 'serviceName';
				$elem.removeAttr('data-unique-id');
			}
			else if($elem.hasClass('serviceDur'))
				var ddName = 'serviceDur';
			$(elem).prop('name', ddName+formRowsCreated);
		})

		serviceRow.find('select').selectpicker('refresh');
		servicePrice.prop('name', 'servicePrice'+formRowsCreated);
		servicePrice.val('');
		serviceRow.find('input[type="hidden"]').prop('name', 'serviceIsDeleted'+formRowsCreated).val('false');
		datetimepickerField.prop('name', 'serviceTime'+formRowsCreated);

		//Checking if service duration exist 
		var lastRowDurDd = lastRow.find("select.serviceDur"),
		lastRowDur = lastRowDurDd.val(),
		isValid = true;
		if(!lastRowDur){
			setFieldInvalid(lastRowDurDd.closest('.form-group'), '');
			isValid = false;
		}

        //Checking if service time exist 
        var lastRowTimeField = lastRow.find('input.timepicker1'),
        lastRowTime = lastRowTimeField.val();
        if(!lastRowTime){
        	setFieldInvalid(lastRowTimeField.closest('.form-group'), '');
        	isValid = false;
        }

        //Checking if end date is same as start date
        var eventDate = setEventDate(appointModal, 'submit');
        date = eventDate.date,
        eventStart = moment(date+' '+lastRowTime, '"YYYY-MM-DD h:mm A').format("YYYY-MM-DD HH:mm:ss"),
        eventEnd = moment(eventStart).add(lastRowDur, 'm').format("YYYY-MM-DD HH:mm:ss"),
        isSameDay = moment(eventStart).isSame(eventEnd, 'day');
        if(!isSameDay)
        	isValid = false;

        if(!isValid)
        	return false;

        setFieldForTimepicker(datetimepickerField, moment(eventEnd).format("h:mm A"))
        
        parCont.append(serviceRow);	
    });	
	/* end: add service row */

	appointModal.find('.services .timepicker1').on('changeTime.timepicker', function(e){
		if(!blockTimepickerChangeEvent){
			getStaffsBasedOnServices();
			getAreasBasedOnServices();
		}
	});
	/* end: Appointment Section */


	/* start: Class Section */
	/* start: Switch to next visible tab */
	$('button#nextTab', classModal).click(function(e){
		var nextTab = $('ul#classTabs li.active').nextAll().has('a:visible').first();
		if(nextTab.length)
			nextTab.children('a').tab('show')
		else
			$('ul#classTabs li a:visible').first().tab('show')
	});
	/* end: Switch to next visible tab */

	/* start: Populate class fields based on class selected */
	$('select[name="staffClass"]', classModal).change(function(){
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

		getStaffsAndAreas('class')
	});
	/* end: Populate class fields based on class selected */

	/* start: Get staffs list according to duration */
	$('select[name="classDur"]', classModal).change(function(){
		getStaffsAndAreas('class')
	});
	/* end: Get staffs list according to duration */

	/* start: Update booked client quick info on capacity change */
	$('input[name="classCap"]', classModal).blur(updateBookedclientQuickInfo);
	/* end: Update booked client quick info on capacity change */

	classModal.find('.timepicker1').on('changeTime.timepicker', function(e){
		if(!blockTimepickerChangeEvent){
			classModal.find('#rescedule_flag').val(1);
			//alert('ff');
			getStaffsAndAreas()
		}
	});

	/* start: Populate linked client data */
	$('body').on("click", '#classModal div#linkedclientList > a', function(e){
		e.preventDefault();
		var $this = $(this),
		clientData = {};
		clientForm = classModal.find('.client-form'),
		clientField = clientForm.find('.clientList'),
		disableable = clientForm.find('.disableable'),
		isReducedRate = disableable.filter('input[name="isReducedRate"]'),
		ifRecur = disableable.filter('input[name="ifRecur"]');

		clientField.val($this.data('name'));
		clientForm.find('div.checkbox.m-t-5').hide();
		clientForm.find(':input').prop('readonly', true);
		disableable.prop('disabled', true);
		clientForm.find('textarea[name="clientNote"]').val($this.data('note'));
		if($this.data('is-reduced-rate'))
			isReducedRate.prop('checked', true);
		else
			isReducedRate.prop('checked', false);
		if($this.data('if-recur'))
			ifRecur.prop('checked', true);
		else
			ifRecur.prop('checked', false);
		clientData['id'] = $this.data('id');
		clientData['phone'] = $this.data('phone');
		clientData['email'] = $this.data('email');


		populateClientDetails(clientData, clientField);

		$this.siblings().removeClass('active');
		$this.addClass('active');

		if($this.data('status') == 'Waiting')
			classModal.find('a#confirmClient').show();
		else
			classModal.find('a#confirmClient').hide();

		var bookingType = getBookingType()
		if(bookingType == 'class')
			toggleClassClientLnks('view');
		else if(bookingType == 'service')
			toggleClassClientLnks('view', true);
	});
	/* end: Populate linked client data */

	/* start: Reset client form to add new client */
	$('a#resetClientlinkForm', classModal).click(function(e){
		e.preventDefault();
		clearClientForm(classModal.find('.client-form'), true);
		toggleClassClientLnks('create')
	});
	/* end: Reset client form to add new client */

	/* start: Confirm a waiting client */
	$('a#confirmClient', classModal).click(function(e){
		e.preventDefault();
		clearNotific('clientConfirmError');

		if(ifClientExceedEventCapacity('confirm'))
			showNotific(prepareNotific('warning', "Can't confirm client because class is full.", 'clientConfirmError'), classModal.find('div#classClients'));
		else{
			var clientId = classModal.find('input[name="clientId"]').val(),
			clientLnk = $('#linkedclientList a[data-id="'+clientId+'"]');

			classClients[clientId].status = 'Confirm';

			clientLnk.find('span.clientStatusIcon').remove();
			clientLnk.data('status', 'Confirm');

			updateBookedclientQuickInfo();

			//createClientAttendanceRow({id:clientId, attendance:'Booked', name:clientLnk.data('name'), photo:clientLnk.data('photo')});

			$(this).hide();
		}
	});
	/* end: Confirm a waiting client */

	/* start: Open client tab */
	$('#show-clients-tab', classModal).click(function(e){
		e.preventDefault();
		classModal.find('.nav-tabs a[href="#classClients"]').tab('show');
	});
	/* end: Open client tab */

	/* start: Prompt to delete class */
	$('.delete-prompt').click(function(e){
		e.preventDefault();
		var isRepeating = $(this).closest('.modal').find('input[name="isRepeating"]').val()

		promtSerDel['status']=false;

		if(isRepeating == 1)
			var delPopover = deleteReccurEventPopoverOpt;
		else
			var delPopover = deleteEventPopoverOpt;

		showPopoverOverModal($(this), delPopover);
	});
	/* end: Prompt to delete class */



	/* start: Prompt to delete class */
	$('.delete-prompt-service').click(function(e){
		e.preventDefault();
		var isRepeating = $(this).closest('.modal').find('input[name="isRepeating"]').val()
		
		promtSerDel['status']=true;

		if(isRepeating == 1)
			var delPopover = deleteReccurEventPopoverOpt;
		else
			var delPopover = deleteEventPopoverOpt;

		showPopoverOverModal($(this), delPopover);
	});
	/* end: Prompt to delete class */

	/* start: Cancel class deletion */
	$('body').on("click", '.cancel-delete-event', function(e){
		e.preventDefault();
		calPopupHelper.trigger("click");
	});
	/* end: Cancel class deletion */

	/* start: Link client class */
	classModal.find('a#linkClientClass').click(function(e){
		e.preventDefault();

		var clientForm = classModal.find('.client-form');

		if(validateClientForm(clientForm, true)){
			var isNewClient = clientForm.find('input[name="isNewClient"]').is(":checked");

			if(isNewClient){
				classOverBookConfirm(function(){
					linkNewclientClass(clientForm)
				})
			}
			else{
				
				var clientId = clientForm.find('input[name="clientId"]').val(),
				ifClientAlreadyLinked = false;

				if(linkedclientsCount()){
					var linkedclient = classModal.find('div#linkedclientList > a[data-id="'+clientId+'"]');
					if(linkedclient.length){
						ifClientAlreadyLinked = true;
						linkedclient.trigger('click');
					}
				}

				if(!ifClientAlreadyLinked){

					classOverBookConfirm(function(){
						var data = {id:clientId, name:clientForm.find('input.clientList').val(), phone:getClientFullNumb(clientForm), email:clientForm.find('input[name="clientEmail"]').val(), note:clientForm.find('textarea[name="clientNote"]').val()};

						/*var bookingType = getBookingType();
						if(bookingType == 'class'){*/
							if(getBookingType() == 'class'){
								data.isReducedRate = clientForm.find('input[name="isReducedRate"]:checked').val();
								data.ifRecur = clientForm.find('input[name="ifRecur"]:checked').val();
								data.isCreatingMakeUpSession = clientForm.find('input[name="isCreatingMakeUpSession"]:checked').val();
							}
						/*else if(bookingType == 'service'){
							data.isReducedRate = 0;
							data.ifRecur = 0;
						}*/

						//console.log('popultate');
						//console.log(data);

						linkClientClass(data, clientForm)
					})
				}
			}	
		}
	})
	/* end: Link client class */

	/* start: Unlink client class */
	classModal.find('a#unlinkClientClass').click(function(e){
		e.preventDefault();
		var clientId = classModal.find('input[name="clientId"]').val(),
		reseventId = classModal.find('input[name="eventId"]').val(),
		isExistingClient = classClients[clientId] && classClients[clientId] != null && classClients[clientId] != undefined ? classClients[clientId].isExisting : 0;

		if(isExistingClient && getBookingType() == 'class' && !isUserType(['Staff'])){

			var bookingType=getBookingType();
			var eventId = classModal.find('input[name="eventId"]').val();
			var invoice = getClientEventInvoiceAmount(clientId, eventId, bookingType);
			var classprice = classModal.find('input[name="classPrice"]').val();
			var isLdc = $(this).data('is-ldc');
			var eventDate = classModal.find('.eventDateDisp').text();
			// if(invoice != '' && invoice.inv_status != 'Paid' && (invoice.inv_total - classprice) == 0) 
			if(invoice != '' && invoice.inv_status != 'Paid' && invoice.inv_credit_used == 0) 
				var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='"+clientId+"' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a>";
			else if(isLdc){
				if(moment(eventDate).subtract(edit_time_limit, 'hours').format('YYYY-MM-DD HH:mm:ss')<moment().format('YYYY-MM-DD HH:mm:ss')){
					var text = "";
				}else{
					var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='"+clientId+"' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a>";
				}
			}else if($(this).data('is-before-24')){
				var text = "";
			}
			else 
				var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='"+clientId+"' data-action='resechedule' data-callback='enterReschecduleClientMode()'>Reschedule</a><a class='btn btn-primary unlinkClientClassWithEpicCash' href='#'  >Add to EPIC Credit</a>";

			swal({
				title: clientSwalOpt.title,
				showCancelButton: clientSwalOpt.showCancelButton,
				cancelButtonText: 'Just delete it',
				allowOutsideClick: clientSwalOpt.allowOutsideClick,
				html: clientSwalOpt.html,
				text: text,
				showConfirmButton: clientSwalOpt.showConfirmButton,
				customClass: clientSwalOpt.customClass     
			}, 
			function(isConfirm){
				if(!isConfirm)
					unlinkClientClass(clientId);
			});
		}
		else
			unlinkClientClass(clientId);
	});
	/* end: Unlink client class */

	/* start: Get client event invoice */
	function getClientEventInvoiceAmount(clientId, eventId, bookingType) {
		var amount = 0.00 ;
		var data = '';
		$.ajax({
			url : public_url+'clients-event-invoice?clientId='+clientId+'&eventId='+eventId+'&eventType='+bookingType,
			type : 'GET',
			data : {},
			async: false,
			success : function(response) {
				data = JSON.parse(response);
				if(data.status == "success"){
					amount = data.invoice.inv_total;
					amount = amount.toString();
				} 
			}
		});

			// return amount;
			return data.invoice;
		}
		/* end: Get client event invoice */

		$('body').on('click','a.unlinkClientClassWithEpicCash', function(e){
			e.preventDefault();
			var clientId = classModal.find('input[name="clientId"]').val();
			unlinkClientClass(clientId, true);
			swal.close();
		})

		/* start: Unlink client class alert */
		$('body').on('click', 'a.unlink-client-class-alert', function(e){
			e.preventDefault();
			var $this = $(this),
			bookingType = getBookingType();

			if(bookingType == 'class'){
				var clientId = $this.data('client-id');

				if($this.data('action') == 'resechedule'){
					rescheduleClients[clientId] = {note:classClients[clientId].note, isReducedRate:classClients[clientId].isReducedRate, ifRecur:classClients[clientId].ifRecur,isLdc:classClients[clientId].isLdc, order:rescheduleClientsCount()+1};
				}
			/*else
			classClients[clientId].ifMakeUp = 1;*/
		}

		var callback = $this.data('callback');
		if(callback)
			eval(callback)
		
		swal.close();
	});
		/* end: Unlink client class alert */

		$("#makeUpNotesModal").on('show.bs.modal', function(evt){
			swal.close();

			var sourceLnk = $(evt.relatedTarget),
			modal = $(this);

			var clientId = sourceLnk.data('client-id');
			modal.find('input[name="clientId"]').val(clientId);

			var callback = sourceLnk.data('callback');
			modal.find('input[name="callback"]').val(callback);

			modal.find('textarea[name="makeupNotes"]').val('');
		});

		$("#makeUpNotesModal").on('hidden.bs.modal', function(evt){
			$('#classModal:visible').length && $(document.body).addClass('modal-open');

			var modifiedRow = $('#modifiedRow');
			if(modifiedRow.length){
				activateAttendanceRadio(modifiedRow.data('prev-val'),  modifiedRow);
				modifiedRow.removeAttr('id')
			}
		});

		$('#makeUpNotesModal .submit').click(function(){
			var modal = $('#makeUpNotesModal');

			var clientId = modal.find('input[name="clientId"]').val();
			classClients[clientId].ifMakeUp = 1;
			classClients[clientId].makeupNotes = modal.find('textarea[name="makeupNotes"]').val();

			var callback = modal.find('input[name="callback"]').val();
			if(callback)
				eval(callback)
		})

		/* start: Change client attendance */
		$('body').on('change', '#classAttendanceList input', function(e){
			var $this = $(this),
			clientId = $this.data('client-id'),
			clientNewAttendance = $this.val();

			$this.closest('.btn-group').attr('id', 'modifiedRow');

			if(clientNewAttendance == 'Did not show'){
				var isExistingClient = classClients[clientId].isExisting;

				if(isExistingClient /*&& !classClients[clientId].isDeleted*/ && (!isUserType(['Staff']) /*|| isUserType(['Staff']) && eventObj.find('input[name="userId"]').val() == loggedInUser.userId*/)){
					var bookingType = getBookingType();
					if(bookingType == 'class'){
					//var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='"+clientId+"' data-action='resechedule' data-callback='changeclientAttendance("+clientId+", \""+clientNewAttendance+"\", "+true+")'>Reschedule</a><a class='btn btn-primary unlink-client-class-alert' href='#' data-client-id='"+clientId+"' data-action='makeup' data-callback='changeclientAttendance("+clientId+", \""+clientNewAttendance+"\")'>Make up session</a>"
					var text = "<a class='btn btn-primary m-r-10 unlink-client-class-alert' href='#' data-client-id='"+clientId+"' data-action='resechedule' data-callback='changeclientAttendance("+clientId+", \""+clientNewAttendance+"\", "+true+")'>Reschedule</a><a class='btn btn-primary' href='#' data-client-id='"+clientId+"' data-callback='changeclientAttendance("+clientId+", \""+clientNewAttendance+"\")' data-toggle='modal' data-target='#makeUpNotesModal'>Make up session</a>"
				}
				else if(bookingType == 'service'){
					var text = "<a class='btn btn-primary w245 unlink-client-class-alert' href='#' data-callback='changeclientAttendance("+clientId+", \""+clientNewAttendance+"\", "+true+")'>Reschedule</a>"
				}
				swal({
					title: clientSwalOpt.title,
					showCancelButton: clientSwalOpt.showCancelButton,
					cancelButtonText: 'Just change attendance',
					allowEscapeKey: false,
					html: clientSwalOpt.html,
					text: text,
					showConfirmButton: clientSwalOpt.showConfirmButton,
					customClass: clientSwalOpt.customClass
				}, 
				function(isConfirm){
					if(!isConfirm)
						changeclientAttendance(clientId, clientNewAttendance);
				});
			}
			else
				changeclientAttendance(clientId, clientNewAttendance);
		}
		else
			changeclientAttendance(clientId, clientNewAttendance);
	});
		/* end: Change client attendance */

		/* start: Change all client attendace to attended */
		classModal.find('div#classAttendance a').click(function(e){
			e.preventDefault();

		//fix controller
		var /*tabCnt = $(this).closest('div#classAttendance'),
			inputs = tabCnt.find('input'),
			attendedInput = inputs.filter('input[value="Attended"]'),*/
			clientsId = [];

			var attendedInput = activateAttendanceRadio('Attended');
		/*tabCnt.find('label').removeClass('active');
		inputs.attr('checked', false);
		attendedInput.attr('checked', true).parent().addClass('active');*/
		attendedInput.each(function(){
			var $this = $(this),
			id = $this.data('client-id');

			changeclientAttendanceUi(id, $this.val());
			clientsId.push(id);
		})
		attendedInput.closest('.btn-group').data('prev-val', 'Attended')

		var formData = {};
		formData['eventId'] = classModal.find('input[name="eventId"]').val();
		formData['clientId'] = clientsId;
		formData['clientAttendance'] = 'Attended';
		toggleWaitShield('show');
		$.post(public_url+'staffevents/classes/past-update', formData, function(data){
			toggleWaitShield('hide');
			var data = JSON.parse(data);
			if(data.status == "added")
				reloadEvents = true;
		});
	});
		/* end: Change all client attendace to attended */

		/* start: Submit class modal */
		$(".submit", classModal).click(function(){
			var form = classModal.find('form'),
			clientForm = classModal.find('.client-form'),
			isFormValid = true,
			bookingType = getBookingType();

			if(bookingType == 'class'){
				clearClientForm(clientForm, true);
				toggleClassClientLnks('create')
			}

			if(pastCurrentMode)
				isFormValid = true;
			else{
				form.validate().settings.ignore = "input[type='hidden']";
				isFormValid = form.valid();

				if(bookingType == 'class' && form.find("input[name='ifMarkFav']:checked").length){
					var markFavTillField = classModal.find('input[name="markFavTill"]'); 
					if(!markFavTillField.val()){
						isFormValid = false;
						setFieldInvalid(markFavTillField.closest('.form-group'), 'This field is required.')
					}
				}
				else if(bookingType == 'service' && !linkedclientsCount() /*&& !validateClientForm(clientForm, true)*/){
					clearNotific('serviceClientError');
					showNotific(prepareNotific('error', "Please add a client.", 'serviceClientError has-error'), classModal.find('div#classClients'));
					isFormValid = false;
				}

				var eventRepeat = form.find('select[name="eventRepeat"]').val();
				if(eventRepeat == 'Weekly'){
					var eventRepeatWeekdaysGroup = form.find('.eventRepeatWeekdays'),
					eventRepeatWeekdays = eventRepeatWeekdaysGroup.find('input[type="checkbox"]:checked');
					if(!eventRepeatWeekdays.length){
						isFormValid = false;
						setFieldInvalid(eventRepeatWeekdaysGroup.closest('.form-group'), 'Please select reccurence days.', eventRepeatWeekdaysGroup.next())
					}
				}

				var staffDd = form.find('select[name="staff"]');
				if(bookingType == 'service'){
					if(!staffDd.valid()){
						isFormValid = false;
						setFieldInvalid(staffDd.closest('.form-group'), 'Please select staff.')
					}
				}else{
					if(staffDd.valid() && !isFieldMarkedAsAvail(staffDd)){
						isFormValid = false;
						setFieldInvalid(staffDd.closest('.form-group'), 'Please select new staff.')
					}
				}
				
				if(staffDd.val() == '' || staffDd.val() == null){
					isFormValid = false;
					setFieldInvalid(staffDd.closest('.form-group'), 'Please select staff.')
				}
				
				if(form.find('input[name="serviceStaff"]:checked').length == 0 && bookingMode != 'changeEvent'){
					isFormValid = false;
					setFieldInvalid(form.find('input[name="serviceStaff"]').closest('.form-group'), 'Please select one of the available staff.')
				}

				var areaDd = form.find('select[name="modalLocArea"]');
				if(!areaDd.valid() && !isFieldMarkedAsAvail(areaDd)){
					isFormValid = false;
					setFieldInvalid(areaDd.closest('.form-group'), 'Please select new area.')
				}

				if(areaDd.val() == '' || areaDd.val() == null){
					isFormValid = false;
					setFieldInvalid(areaDd.closest('.form-group'), 'Please select area.')
				}
			}
			moveErrorMsg(classModal);
			clearNotific('clientsBusy');
			if(isFormValid){
				var isRepeating = form.find('input[name="isRepeating"]').val()
				if(isRepeating == 1){
					showPopoverOverModal($(this));
					$(".update-event").trigger("click");
				}
				else{
					submitClassModal(form);
				}
			}
			else{
				if(bookingType == 'class')
					switchToErrorTab(form);
				else if(bookingType == 'service'){
					setTimeout(function(){
						switchToErrorTab(form)
					}, 500)
				}
			}
		});
		/* end: Submit class modal */
		/* end: Class Section */

		/* start: Single service Section */
		/* start: Toggle event auto expire fields */
		$('input[name="ifAutoExpireAppoint"]', classModal).change(function(){
			var autoExpireAppoint = $('.autoExpireAppoint'),
			autoExpireAppointDurCustom = $('.autoExpireAppointDurCustom'),
			autoExpireAppointDurCustomFields = autoExpireAppointDurCustom.find('input'),
			dd = autoExpireAppoint.find('select');

			if($(this).is(":checked")){
				autoExpireAppoint.show();
				dd.val('');
				dd.prop('required', true)
				dd.selectpicker('refresh');
			}
			else{
				autoExpireAppoint.hide();
				dd.prop('required', false)
			}

			autoExpireAppointDurCustom.hide();
			autoExpireAppointDurCustomFields.prop('required', false)
		})
		/* end: Toggle event auto expire fields */

		/* start: Initialize selectable plugin */
		$("#appointStatusSelectable").selectable({
			/*stop*/selected: function() {
				toggleAppointStatusFields();
			}
		});
		/* end: Initialize selectable plugin */

		/* start: Toggle manual date time fields for event auto expire */
		$('select[name="autoExpireAppointDur"]', classModal).change(function(){
			var autoExpireAppoint = $('.autoExpireAppoint'),
			autoExpireAppointDurCustom = $('.autoExpireAppointDurCustom'),
			autoExpireAppointDurCustomFields = autoExpireAppointDurCustom.find('input');

			if($(this).val() == 'Custom'){
				autoExpireAppointDurCustom.show();
				autoExpireAppointDurCustomFields.prop('required', true)
				autoExpireAppoint.hide();
			}
			else
				autoExpireAppointDurCustomFields.prop('required', false)
		})
		/* end: Toggle manual date time fields for event auto expire */ 

		/* start: Populate service fields based on service selected */
		$('select[name="staffservice"]', classModal).change(function(){
			var $this = $(this),
			modal = $this.closest('.modal'),
			selOpt = $this.find(":selected"),
			duration = modal.find('select[name="serviceDur"]'),
			price = modal.find('input[name="servicePrice"]');
			var service_val = $('#staffservice option:selected').text();
			/*if(service_val == 'Consultation' || service_val == 'Pre-Benchmarking')
			{
				classModal.find("#ini-stat").html("Pencilled-In");
				classModal.find("#appointStatusOpt").val("Pencilled-In");
				
			}
			else
			{
				classModal.find("#ini-stat").html("Confirmed");
				classModal.find("#appointStatusOpt").val("Confirmed");
			}*/
			classModal.find("#ini-stat").html("Pencilled-In");
			classModal.find("#appointStatusOpt").val("Pencilled-In");
			price.val(selOpt.data('price'));
			classModal.find("#durup").html("");
			classModal.find("#priup").html("");
			classModal.find("#durup").html(selOpt.data('duration')+" mins");
			classModal.find("#priup").html("$ "+selOpt.data('price'));
			setFieldNeutral(price);
			duration.val(selOpt.data('duration'))
			setFieldNeutral(duration);
			duration.selectpicker('refresh');
			getStaffsAndAreas('service');
			// alert(selOpt.data('salesstep'));
			var d_client_s = JSON.parse($("#defaultClient").val());
			var epic_cash_s = d_client_s[0]['epic_cash'];
			// alert(typeof(parseInt(epic_cash_s))+ " MMM " +typeof(parseInt(selOpt.data('price'))));
			if(selOpt.data('salesstep') != 0)
			{
				// alert(selOpt.data('salesstep') + ' add hide');
				$('#epic_cash_div_ser').addClass('hidden');
			}
			else
			{
				if(parseInt(epic_cash_s) > parseInt(selOpt.data('price')))
				{
					$('#epic_cash_div_ser').removeClass('hidden');
				}
				else
				{
					$('#epic_cash_div_ser').addClass('hidden');   
				}
			}
			$('#classModal').find('#generalTab').show();
     	})
		/* end: Populate service fields based on service selected */

		/**
		 * 
		 */
		$('#classModal select[name="staff"]').change(function(){
			getAvailableStaffDesk('service');
		});

		function getAvailableStaffDesk(bookingType, callback){
			var classModal = $('#classModal');
			var formData = {};
			var entityDd = classModal.find('select[name="staffservice"]');
			eventDate = $('input[name="eventServiceDate"]').val();
			var entityVal = entityDd.val();
			var duration = classModal.find('#durupMobile').data('service-duration');
			formData['date'] = moment(classModal.find('.eventDateDisp').text(), 'ddd, D MMM YYYY').format("YYYY-MM-DD");
			formData['day'] = moment(classModal.find('.eventDateDisp').text(), 'ddd, D MMM YYYY').format("dddd");
			formData['duration'] = classModal.find('select[name="serviceDur"]').val();
			formData['time'] =  timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
			var url = 'services/';
			if(entityVal != '' && entityVal != undefined){
				toggleWaitShield('show');
				$.get(public_url+'settings/business/'+url+entityVal+'/free-staffs', formData, function(data){   
					toggleWaitShield('hide');
					var selectedStaffIds = [];
					$('#classModal select[name="staff"]').find('option:selected').each(function(){
						selectedStaffIds.push($(this).val());
					});
					classModal.find('.service_staff_check').empty();
					var staffListHtml = '';
					$.each(data, function(key, value){
						if($.inArray(key, selectedStaffIds) != '-1'){
							staffListHtml +='<input type="radio" name="serviceStaff" value="'+key+'" id="staffOption'+key+'"><label for="staffOption'+key+'">'+value.name+' </label>' 
						}
					});
					classModal.find('.service_staff_check').append(staffListHtml);
				},'json');
			}
		};

		$('#timepickerDesk').datetimepicker({
			format:'LT',
			inline:true,
			useCurrent:false,
		}).on('dp.change', function (e) {
			$('.timepickerDeskInput').val(e.date.format("HH:mm:ss"));
			getAvailableStaffDesk('service');
			getAreas('service');
		});

		/* start: Get staffs list according to duration */
		$('select[name="serviceDur"]', classModal).change(function(){
			getStaffsAndAreas('service')
		});
		/* start: Get staffs list according to duration */

		/* start: Populate services over appointment cancel modal before showing */
		appointCancelModal.on('show.bs.modal', function(){
			if(eventObj.find('input[name="isServiceDeleted"]').val() != 'true')
				var services = '<li class="list-group-item">'+eventObj.find('input[name="serviceName"]').val()+' with '+eventObj.find('input[name="staffName"]').val()+' on '+moment(eventObj.find('input[name="date"]').val()).format("D MMM")+' at '+eventObj.find('input[name="startTime"]').val()+'</li>';
			else
				var services = '';

			var modal = $(this);
			modal.find('#services').html(services);
			clearForm(modal.find('form'));
			modal.find('select').selectpicker('refresh');
		});
		/* end: Populate services over appointment cancel modal before showing */

		/* start: Submit appointment cancel modal */	
		$(".submit", appointCancelModal).click(function(){
		//eventModal = appointModal.prop('id');
		eventModal =  classModal.prop('id');
		var form = appointCancelModal.find('form');

		if(form.valid()){
			var isRepeating = eventObj.find('input[name="isRepeating"]').val();

			if(isRepeating == 1)
				showPopoverOverModal($(this), deleteReccurEventPopoverOpt);
			else
				deleteAppoint(false);
		}
	});
		/* end: Submit appointment cancel modal */

		/* start: Initialize selectable plugin over booking type */
	/*$("#bookTypeSelectable").selectable({
		selected:function(){
			setBookingType();

			clientLinkOrder = 0;
			classClients = {};
			clearClientForm(classModal.find('.client-form'), true);
			classModal.find('div#linkedclientList').html('');
			updateBookedclientQuickInfo();
			toggleClassClientLnks('create')
			clearNotific('serviceClientError');
	
			getStaffsAndAreas();
		}
	});*/
	/* end: Initialize selectable plugin over booking type */
	/* end: Single service Section */
});


/* Start: class Signup ajax */
function classSignupAjax(classForm){
	var formData = {};

	formData['eventType'] = 'signupClass';
	formData['eventId'] = signup_class_stat.eventId;
	formData['ifRecur'] = classForm.find("input[name='ifRecur']:checked").val();
	formData['makeupsession'] = classForm.find("input[name='makeupsession']:checked").val();
	formData['bookType'] = classForm.find("input[name='bookType']").val();
	formData['resClassClientId'] = classForm.find("#resClassClient").attr("data-resevent-id");
	formData['isEpicCash'] = classForm.find("input[name='isEpicCash']:checked").val();
	formData['forceAdd'] = classForm.find("input[name='forceAdd']").val();
	toggleWaitShield('show');
	$.post(public_url+'clientevents/classes/checkconditions', formData, function(response){
		toggleWaitShield('hide');
		if(response.status == 'ok'){
			swal({
				type: 'warning',
				title: 'Are you sure to continue?',
				showCancelButton: true,
				allowOutsideClick: false,
				text: response.message,
				showConfirmButton: true,
				confirmButtonColor: '#ff4401',
			}, 
			function(isConfirm){
				if(isConfirm){
					toggleWaitShield('show');
					$.post(public_url+'clientevents/classes/create', formData, function(data){
						toggleWaitShield('hide');
						var data = JSON.parse(data);
				
						if(data.status == "added"){
							data.type = formData.bookType;
							submitClassModalSuccess(data);
							showClientBookedMsg(data.message)
				
							clearForm(classForm);
				
							signup_class_stat['resonopen'] = true;
							getStaffEvents();
							setTimeout(function(){
								signup_class_stat['resonopen'] = false;
							}, 500);
						}
						else if(data.status == "error"){
							$.each(data.errorData, function(){
								$.each(this, function(errorType, message){
									if(errorType == 'membershipUnsatisfy'){
										//classForm.find('.errorMag').append(message);
										clientEventWithInvoice(function(confirm){
											if(confirm){
												classForm.find("input[name='forceAdd']").val('yes');
												classSignupAjax(classForm)
											}
											else{
												classForm.closest('.modal').modal('hide');
												showNotific(message);
											}	
										})
									}
									else if(errorType == 'eventAllReadyExist' || errorType == 'lowEpicCash' || errorType == 'clientBusyClassBook'){
										classForm.find('.errorMag').append(message);
									}
									else if(errorType == 'clientBusy' || errorType== 'clientBusyClassBook'){
										if(errorType == 'clientBusy')
											signup_class_stat['resonopen'] = true;
										else if(errorType== 'clientBusyClassBook')
											signup_class_stat['resonopen'] = false;
				
										signup_class_stat['eventType'] = 'signupClass';
										$("#dateSelMsg").removeClass('hidden');
										classForm.closest('.modal').modal('hide');
										showNotific(message);
									}
								});							
							});
							calPopupHelper.trigger("click");
							switchToErrorTab(classForm);
						}			
					});
				}else{
					classForm.closest('.modal').modal('hide');
				}
			});
		}else{
			swal({
				type: 'error',
				title: 'Error!',
				showCancelButton: false,
				allowOutsideClick: false,
				text: response.message,
				showConfirmButton: true,     
			}, 
			function(isConfirm){
				if(!isConfirm){
					classForm.closest('.modal').modal('hide');
				}
			});
		}
	},'json');
	
	classForm.find("input[name='forceAdd']").val('');
}
/* End: class Signup ajax */

/* Start: Invoice create confirmation */
function clientEventWithInvoice(callback){
	swal({
		title: "This client doesn't meet membership restrictions.",
		allowOutsideClick: false,
		showCancelButton: true,
		confirmButtonText: 'Add with invoice',
		confirmButtonColor: '#ff4401',
		cancelButtonText: "Don't add"
	}, 
	function(isConfirm){
		callback(isConfirm);	
	});	
}
/* End: Invoice create confirmation */

/* start: Populating client details */
function populateClientDetails(clientDetails, field){
	var modal = field.closest('.modal')
	modalId = modal.attr('id');

	field.siblings('input[name="clientId"]').val(clientDetails.id);

	if(modalId == 'appointModal' || modalId == 'classModal'){
		modal.find('input[name="clientEmail"]').val(clientDetails.email);
		modal.find('input[name="clientNumb"]').intlTelInput("setNumber", clientDetails.phone.toString())

		if(modalId == 'classModal'){
			if('makeUp' in clientDetails)
				toggleIf_make_up_sessionOption('show');
			else
				toggleIf_make_up_sessionOption('hide');
		}
	}
}	
/* end: Populating client details */

/* start: Toggle recurrence tab in appointment modal */
function toggleRecurrTab(action){
	/*var tab = appointModal.find('ul.nav a[href="#appointReccur"]');

	if(action == 'show')
		tab.show();
	else
		tab.hide();*/
	toggleTab(appointModal, 'appointReccur', action);
}
/* end: Toggle recurrence tab in appointment modal */

function toggleTab(modal, tabId, action){
	var tab = modal.find('ul.nav a[href="#'+tabId+'"]');

	if(action == 'show')
		tab.show();
	else
		tab.hide();
}

/* start: Toggle appointment status fields */
function toggleAppointStatusFields(setStatus){
	if(typeof setStatus != 'undefined'){
		var selectableOpts = $("#appointStatusSelectable li");
		selectableOpts.removeClass("ui-selected").show();
		selectableOpts.filter('[data-val="'+setStatus+'"]')[0].className += " ui-selected";
	}

	var status = $('#appointStatusSelectable li.ui-selected').data('val');
	classModal.find('input[name="appointStatusOpt"]').val(status)
	/*if(status == 'Pencilled-In')
		$('#appointStatusPending').show();
	else
		$('#appointStatusPending').hide();*/
}
/* end: Toggle appointment status fields */
function disableSelectable(selectableId){
	$(selectableId+' li:not(.ui-selected)').hide()
	//$(selectableId).selectable("disable");
}
function disableAppointStatuselectable(){
	disableSelectable('#appointStatusSelectable');
}



function disableBookTypeSelectable(){
	disableSelectable('#bookTypeSelectable');
}

/* start: Change error message placement */
function moveErrorMsg(modal){
	var moveErrMsgs = modal.find('.moveErrMsg');
	if(moveErrMsgs.length){
		moveErrMsgs.each(function(){
			var moveErrMsg = $(this),
			formGroup = moveErrMsg.closest('.form-group'),
			errors = moveErrMsg.find('.help-block'),
			errorMsg = '';

			errors.each(function(){
				var errorTxt = $(this).text();

				if(errorTxt != ''){
					formGroup.addClass('has-error');
					errorMsg = errorTxt;
					return false;
				}
			});
			formGroup.find('.placeErrMsg').html(errorMsg)
		})
	}
}
/* end: Change error message placement */

function setGroupInit(){
	$('.set-group').each(function(){
		var setGroup = $(this),
		dd = setGroup.find('select'),
		setGroupDisp = setGroup.find('.set-group-disp'),
		lnk = setGroupDisp.find('a');
		if(dd.attr('id') != 'staff'){
			dd.addClass('hidden');
		}
		dd.prop('required', false);
		setGroupDisp.show();

		setGroupDisp.find('span').text(dd.find('option[value="'+dd.val()+'"]').text());

		if(pastCurrentMode)
			lnk.hide();
		else
			lnk.show();
	})
}

/* start: Switch tab of modal */
function switchTabTo(tab, modal){
	modal.find('ul.nav a[href="#'+tab+'"]').tab('show');
}
/* end: Switch tab of modal */

/* start: Format database date to UI date */
function dbDateToDateString(dbDate){
	return moment(dbDate).format("ddd, D MMM YYYY")
}
/* start: Format database date to UI date */

/* start: Format database time to UI time */
/*function dbTimeToTimeString(dbTime){
	return moment(dbTime, 'HH:mm:ss').format("h:mm A")
}*/
/* end: Format database time to UI time */

/* start: Set/get event date in various modes */
function setEventDate(modal, mode){
	var eventDateDisp = modal.find('.eventDateDisp');
	//console.log('response : '+eventDateDisp);

	if(mode == 'add'){
		var selectedDatetimeMoment = moment($('input[name="selectedDatetime"]').val());

		eventDateDisp.text(selectedDatetimeMoment.format("ddd, D MMM YYYY"));
	}
	else if(mode == 'edit')
		eventDateDisp.text(moment(eventObj.find('input[name="date"]').val()).format("ddd, D MMM YYYY"));
	
	else if(mode == 'submit'){

		var eventDateDisp = moment(eventDateDisp.text(), 'ddd, D MMM YYYY'),
		returnData = {};

		returnData['date'] = eventDateDisp.format("YYYY-MM-DD");
		returnData['day'] = eventDateDisp.format("dddd");
		
		return returnData;
	}
}
/* end: Set/get event date in various modes */

/* start: Reset event recurrence data */
function resetEventReccur(modal){
	var module = modal.find('.event-reccur');

	module.find('select[name="eventRepeat"]').change();

	//module.find('input[name="eventRepeatEndOnDate"]').val(moment().format('D MMM YYYY'))
	setRepeatEndDate(modal)

	module.find('input[name="eventRepeatEnd"][value="On"]').prop('checked', true).trigger('change');

	setEventdayAsRepeatWeekDay(modal);
}
/* end: Reset event recurrence data */

function setRepeatEndDate(modal, date){
	var field = modal.find('input[name="eventRepeatEndOnDate"]'),
	eventDate = setEventDate(modal, 'submit'),
	minDateMoment = moment(eventDate.date),
	minDate = minDateMoment.format('D MMM YYYY');

	field.datepicker("option", "minDate", minDate);

	if(typeof date == 'undefined'){
		var prevVal = field.val();
		if(!prevVal || moment(prevVal).isBefore(minDateMoment)){
			date = minDate;
			field.val(date)
		}
	}
	else{
		date = moment(date).format('D MMM YYYY');
		field.val(date)	
	}
}

/* start: Set event day as default repeat weekend day */
function setEventdayAsRepeatWeekDay(modal){
	var eventDate = setEventDate(modal, 'submit'),
	eventDay = eventDate.day.slice(0,3),
	eventRepeatWeekdays = modal.find('.eventRepeatWeekdays input[type="checkbox"]');

	eventRepeatWeekdays.prop('checked', false)
	eventRepeatWeekdays.filter('[value="'+eventDay+'"]').prop('checked', true)
}
/* start: Set event day as default repeat weekend day */

/* start: Populate event recurrence data */
function populateEventReccur(modal){
	resetEventReccur(modal);

	var module = modal.find('.event-reccur'),
	savedEventRepeat = eventObj.find('input[name="eventRepeat"]').val(),
	savedEventRepeatEnd = eventObj.find('input[name="eventRepeatEnd"]').val();

	if(savedEventRepeat != null){
		module.find('select[name="eventRepeat"]').val(savedEventRepeat).change();

		if(savedEventRepeat == 'Daily' || savedEventRepeat == 'Weekly' || savedEventRepeat == 'Monthly'){
			module.find('select[name="eventRepeatInterval"]').val(eventObj.find('input[name="eventRepeatInterval"]').val())
			
			module.find('input[name="eventRepeatEnd"][value="'+savedEventRepeatEnd+'"]').prop('checked', true).trigger('change');
			if(savedEventRepeatEnd == 'After')
				module.find('select[name="eventRepeatEndAfterOccur"]').val(eventObj.find('input[name="eventRepeatEndAfterOccur"]').val())
			else if(savedEventRepeatEnd == 'On')
				setRepeatEndDate(modal, eventObj.find('input[name="eventRepeatEndOnDate"]').val())
				//module.find('input[name="eventRepeatEndOnDate"]').val(moment(eventObj.find('input[name="eventRepeatEndOnDate"]').val(), 'YYYY-MM-DD').format("D MMM YYYY"))

				if(savedEventRepeat == 'Weekly'){
					eventRepeatWeekdays = module.find('.eventRepeatWeekdays input[type="checkbox"]');

					eventObj.find("input[name='eventRepeatWeekDays[]']").each(function(){
						eventRepeatWeekdays.filter('[value="'+$(this).val()+'"]').prop('checked', true)
					})
				}
			}
		}
	}
	/* end: Populate event recurrence data */

	/* start: Populate event history */
	function populateEventHistory(modal){
		var historyContent = '',
		historyElem = modal.find('.event-history'),
		historyTab = modal.find('a[href="#'+historyElem.prop('id')+'"]'),
		count = 0;

		eventObj.find("input[name='history[]']").each(function(){
			count++;

		//sconsole.log($(this).val());

		var history = JSON.parse($(this).val());

		historyContent += '<hr class="m-t-0 m-b-10"><div class="font-15"><span class="label ';
		if(history.type == 'new') 
			historyContent += 'label-success">New!';
		else if(history.type == 'ammend') 
			historyContent += 'label-warning">Amended';
		else if(eventModal == 'classModal')
			historyContent += 'label-info">Alert';

		historyContent += '</span> '+dbDateToDateString(history.date)+' '+dbTimeToTimeString(history.time);
		if(history.type != 'alert') 
			historyContent += ' - by '+history.ownerName;

		historyContent += '</div>';

		var historyText = history.text.split('|');
		$.each(historyText, function(key, value){
			historyContent += '<p class="m-t-10">'+value+'</p>';
		});
	})

		historyElem.html(historyContent);
		historyTab.find('span').html(count);
		historyTab.show();
	}
	/* end: Populate event history */

	/* start: Toggle modal elements as per datetime */
	function toggleEventModalUiByTimeMode(modal, mode){
		var modalId = modal.attr('id');

		if(modalId == 'classModal'){
			var tabs = modal.find('.tab-pane:not("#classHist")'),
			saveBtn = modal.find('.modal-footer button.submit'),
			delBtn = modal.find('.modal-footer a');
			unlBtn = modal.find('.modal-footer .unlink-prompt');

		}
		else if(modalId == 'busyModal'){
			var tabs = modal.find('form div.col-md-12'),
			saveBtn = modal.find('.modal-footer button.submit'),
			delBtn = modal.find('.modal-footer .delete-prompt');
			unlBtn = modal.find('.modal-footer .unlink-prompt');

		}
		else if(modalId == 'appointModal'){
			var tabs = modal.find('.tab-pane:not("#appointHist")'),
			saveBtn = modal.find('.modal-footer button.submit'),
			delBtn = modal.find('.modal-footer .delete-prompt-service');
		}

		clearNotific('pastEvent');
		clearNotific('cancelledEvent');
		clearNotific('notOwner');
		saveBtn.show();
		delBtn.hide();
		unlBtn.show();


		if(mode == 'cancel'){
			showNotific(prepareAlert('warning', "This event has been cancelled. You can not update any details", 'cancelledEvent'), tabs);
			saveBtn.hide();
		}
		else if(pastCurrentMode){

			if(modalId == 'classModal'){
				var bookingType = getBookingType()
				if(bookingType == 'class'){
					var message = "This event is in past. You can not update any details unless it is client related.";
					if(!isUserType(['Staff']) || (isUserType(['Staff']) && eventObj.find('input[name="userId"]').val() == loggedInUser.userId))
					{
						delBtn.filter('.delete-prompt').show();
						unlBtn.filter('.unlink-prompt').show();
						$('.unlink-prompt').removeClass('hidden');
					}
				}
				else if(bookingType == 'service')
					var message = "This event is in past. You can not update any details.";
			}
			else
				var message = "This event is in past. You can not update any details.";
			showNotific(prepareAlert('warning', message, 'pastEvent'), tabs);

			if(modalId != 'classModal' || (modalId == 'classModal' && getBookingType() == 'service') || (isUserType(['Staff']) && eventObj.find('input[name="userId"]').val() != loggedInUser.userId))
				saveBtn.hide();
		}
		else if(mode == 'edit'){
			if(/*modalId != 'busyModal' &&*/ isUserType(['Staff']) && eventObj.find('input[name="userId"]').val() != loggedInUser.userId){
				showNotific(prepareAlert('warning', "This event is not created by you. You can not update any details", 'notOwner'), tabs);
				saveBtn.hide();
			}
			else{
			//console.log(calendarSettings)
			if(modalId == 'classModal'){
				var bookingType = getBookingType();
				if(bookingType == 'class')
				{
					delBtn.filter('.delete-prompt').show();
					// unlBtn.filter('.unlink-prompt').show();
					// $('.unlink-prompt').removeClass('hidden');

				}
				else if(bookingType == 'service' && calendarSettings.cs_allow_appointments==1)
					delBtn.filter('.delete-prompt-service').show();
			}
			else
				delBtn.show();
			unlBtn.show();

		}
	}
}
/* end: Toggle modal elements as per the mode it is in */

/* start: Check if given datetime is less than or equal to current datetime */
function isSameOrBefore(dateTime){
	var now = moment();

	if(moment(dateTime, 'YYYY-MM-DD h:mm A').isSameOrBefore(now))
		return true;
	else
		return false;
}
/* end: Check if given datetime is less than or equal to current datetime */

/* start: Turn flag on given datetime is less than or equal to current datetime */
function setPastCurrentModeFlag(datetime){
	if(isSameOrBefore(datetime))
		pastCurrentMode = true;
	else
		pastCurrentMode = false;
}
/* start: Turn flag on given datetime is less than or equal to current datetime */

/* start: Display notification messages */
function showNotific(content, elem){
	if(typeof elem == 'undefined'){
		elem = calendar;
		clearNotific(elem);
	}

	setTimeout(function(){ 
		elem.prepend(content);
	}, 400);
}
/* end: Display notification messages */

/* start: Clear notification messages */
function clearNotific(alertGroup){ 
	if(typeof alertGroup == 'string')
		$('body').find('.'+alertGroup).remove();
	else
		alertGroup.children('.alert').remove();
}
/* start: Clear notification messages */

/* start: Prepare html for alert messages */
function prepareAlert(type, message, alertGroup){
	if(typeof alertGroup == 'undefined')
		alertGroup = '';

	return '<div class="alert alert-'+getAlertsColor(type)+' '+alertGroup+'">'+message+'</div>';
}
/* end: Prepare html for alert messages */

/* start: Prepare html for notification messages */
function prepareNotific(type, message, alertGroup){
	if(typeof alertGroup == 'undefined')
		alertGroup = '';

	return '<div class="alert alert-'+getAlertsColor(type)+' '+alertGroup+'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+message+'</div>';
}
/* end: Prepare html for notification messages */

/* start: Determine alerts background color based on the type */
function getAlertsColor(type){
	if(type == 'error')
		return 'danger';
	if(type == 'success')
		return 'success';
	if(type == 'warning')
		return 'warning';
}
/* end: Determine alerts background color based on the type */

/* start: Submit appointment modal data */	
function submitAppointmentModal(form, clientForm){ alert('aaa');
if(form == null)
	form = appointModal.find('form');

if(clientForm == null)
	clientForm = appointModal.find('.client-form');

var formData = {};

form.find(':input').each(function(){
	formData[$(this).attr('name')] = $(this).val()
})

if(isSalesProcessMode())
	formData['salesProcessStepNumb'] = parent.salesProcessStepNumb;

var eventDate = setEventDate(appointModal, 'submit');
formData['date'] = eventDate.date;
formData['day'] = eventDate.day;

formData['ifAutoExpireAppoint'] = form.find("input[name='ifAutoExpireAppoint']:checked").val()
if(formData['ifAutoExpireAppoint'] == 1){
	if(formData['autoExpireAppointDur'] == 'Custom')
		formData['autoExpireDatetime'] = moment(formData['autoExpireAppointDurDate']+' '+formData['autoExpireAppointDurTime']).format("YYYY-MM-DD H:mm:ss");
	else
		formData['autoExpireDatetime'] = moment().add(formData['autoExpireAppointDur'], 'hours').format("YYYY-MM-DD H:mm:ss")
}

formData['isNewClient'] = form.find("input[name='isNewClient']:checked").val();
formData['clientNumb'] = getClientFullNumb(clientForm);

if(formData['eventRepeat'] == 'Daily' || formData['eventRepeat'] == 'Weekly' || formData['eventRepeat'] == 'Monthly'){
	formData['eventRepeatEnd'] = form.find('input[name="eventRepeatEnd"]:checked').val();

	if(formData['eventRepeatEnd'] == 'On')
		formData['eventRepeatEndOnDate'] = moment(formData['eventRepeatEndOnDate'], 'D MMM YYYY').format("YYYY-MM-DD");
	else if(formData['eventRepeatEnd'] == 'Never')
		formData['calendEndDate'] = getCalendEndDate();

	if(formData['eventRepeat'] == 'Weekly'){
		var temp = [],
		eventRepeatWeekdays = form.find('.eventRepeatWeekdays input[type="checkbox"]:checked');
		if(eventRepeatWeekdays.length){
			eventRepeatWeekdays.each(function(){
				temp.push($(this).val());
			})
			formData['eventRepeatWeekdays'] = temp;
		}
	}
}

formData['historyText'] = '';
if(formData['eventId'] != null && formData['eventId'] != ''){
	if(eventObj.find('input[name="areaId"]').val() != formData['modalLocArea'])
		formData['historyText'] += 'Area changed from '+eventObj.find('input[name="locAreaName"]').val()+' to '+appointModal.find('select[name="modalLocArea"] option:selected').text()+'|';

	if(eventObj.find('input[name="staffId"]').val() != formData['staff'])
		formData['historyText'] += 'Staff changed from '+eventObj.find('input[name="staffName"]').val()+' to '+appointModal.find('select[name="staff"] option:selected').text()+'|';

	var oldAppointdate = eventObj.find('input[name="date"]').val(),
	oldAppointTime = eventObj.find('input[name="startTime"]').val();
	if(oldAppointdate != formData['date'] || oldAppointTime != timeStringToDbTime(formData['serviceTime0']))
		formData['historyText'] += 'Reschedule from '+dbDateToDateString(oldAppointdate)+' '+dbTimeToTimeString(oldAppointTime)+' to '+appointModal.find('.eventDateDisp').text()+' '+formData['serviceTime0']+'|';

	if(formData['isNewClient'])
		formData['historyText'] += 'Client changed from '+eventObj.find('input[name="clientName"]').val()+' to '+formData['clientName']+'|';
	else if(eventObj.find('input[name="clientId"]').val() != formData['clientId'])
		formData['historyText'] += 'Client changed from '+eventObj.find('input[name="clientName"]').val()+' to '+clientForm.find('.clientList').val()+'|';

	oldAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val();
	if(oldAppointStatusOpt != formData['appointStatusOpt']){
		formData['historyText'] += 'Booking status changed from '+oldAppointStatusOpt+' to '+formData['appointStatusOpt']+'|';
		if(formData['appointStatusOpt'] == 'Confirmed' && formData['appointStatusConfirm'] != 'Not started')
			formData['historyText'] += 'Confirmation status changed from Not started to '+formData['appointStatusConfirm']+'|';
	}
	else{
		oldAppointStatusConfirm = eventObj.find('input[name="appointStatusConfirm"]').val();
		if(oldAppointStatusOpt == 'Confirmed' && formData['appointStatusOpt'] == 'Confirmed' && oldAppointStatusConfirm != formData['appointStatusConfirm'])
			formData['historyText'] += 'Confirmation status changed from '+oldAppointStatusConfirm+' to '+formData['appointStatusConfirm']+'|';
	}

	var allServices = appointModal.find('select.serviceName');
	if(eventServices.length){
		var i = 0;

		$.each(eventServices, function(key, value){
			var service = allServices.filter("[data-unique-id='"+i+"']");

			if(service.length){
				var serviceRow = service.closest('.row'),
				time = serviceRow.find('input.timepicker1'),
				duration = serviceRow.find('select.serviceDur'),
				price = serviceRow.find('input.servicePrice');

				if(this.id != service.val())
					formData['historyText'] += 'Service'+(i+1)+' - product changed from '+this.name+' to '+service.find('option:selected').text()+'|';

				if(this.time != timeStringToDbTime(time.val()))
					formData['historyText'] += 'Service'+(i+1)+' - service date changed from '+appointModal.find('.eventDateDisp').text()+' '+dbTimeToTimeString(this.time)+' to '+appointModal.find('.eventDateDisp').text()+' '+time.val()+'|';

				if(this.duration != duration.val())
					formData['historyText'] += 'Service'+(i+1)+' - service length changed from '+this.duration+' minutes to '+duration.val()+' minutes|';

				if(this.price != price.val())
					formData['historyText'] += 'Service'+(i+1)+' - price changed from $'+this.price+' to $'+price.val()+'|';
			}
			else
				formData['historyText'] += this.name+' was removed from the service group|';

			i++;
		});
	}
	allServices.filter(':not([data-unique-id])').each(function(){
		formData['historyText'] += $(this).find('option:selected').text()+' was added to the service group|';
	});

	ajaxAction = public_url+'staffevents/appointments/edit';
}
else
	ajaxAction = public_url+'staffevents/appointments/create';
toggleWaitShield('show');
$.post(ajaxAction, formData, function(data){
	toggleWaitShield('hide');
	var data = JSON.parse(data);
	if(data.status == "added"){
		if(formData['isNewClient'])
			data.newClient = true;
		else
			data.newClient = false;

		submitAppointmentModalSuccess(data)
	}
	else if(data.status == "error"){

		$.each(data.errorData, function(key, val){
					/*if(errorType == "workingHourUnavail"){
						var field = form.find("span.eventDateDisp"); 		
						setFieldInvalid(field.closest('.form-group'), 'message')
						moveErrorMsg(classModal);
					}
					else if(errorType == "businessClosed"){
						var field = form.find("span.eventDateDisp"),
							formGroup = field.closest('.form-group'); 		
						setFieldInvalid(formGroup, 'Business is closed on specified date.', formGroup.find('.placeErrMsg'))
					}*/
					if(errorType == "workingHourUnavail" || errorType == "businessClosed"){
						var field = form.find("span.eventDateDisp"),
						formGroup = field.closest('.form-group');
						if(errorType == "businessClosed"){

							message = 'Business is closed on specified date';
							setFieldInvalid(field.closest('.form-group'), message, formGroup.find('.placeErrMsg'))
						}	
						
					}
					else if(errorType == "areaUnavail"){
						/*var linkedclients = classModal.find('div#linkedclientList > a');
						$.each(this, function(key, clientId){
							var linkedClient = linkedclients.filter('[data-id="'+clientId+'"]');
							linkedClient.addClass('list-group-item-danger')
						});
						showNotific(prepareNotific('error', 'Marked clients are busy at specified hours!', 'clientsBusy'), classModal.find('div#classClients'));*/
					}
					else if(errorType == "membershipUnsatisfy" || errorType == "clientUnavail"){
						var linkedclients = classModal.find('div#linkedclientList > a');
						$.each(this, function(key, clientId){
							var linkedClient = linkedclients.filter('[data-id="'+clientId+'"]');
							linkedClient.addClass('list-group-item-danger')
						});
						if(errorType == "membershipUnsatisfy")
							var message = 'Marked clients doesn\'t meet membership restrictions!';
						else
							var message = 'You are busy at specified hours!';
						showNotific(prepareNotific('error', message, 'clientsBusy'), classModal.find('div#classDetails'));
					}
				});
		switchToErrorTab(form);
	}
});
}
/* end:Submit appointment modal data */

/* start:Submit appointment cancel modal data */
function deleteAppoint(ifForceDelete, targetEvents, isEpicCash = 'no'){
	var formData = {};
    formData['isEpicCash'] = isEpicCash;
	formData['eventId'] = eventObj.find('input[name="eventId"]').val();
	if(!ifForceDelete){
		if(targetEvents != null)
			formData['targetEvents'] = targetEvents;
		formData['cancelReas'] = appointCancelModal.find('select[name="cancelReas"]').val();
	}
	
	toggleWaitShield('show');
	$.post(public_url+'clientevents/single-service/delete', formData, function(data){
		toggleWaitShield('hide');
		var data = JSON.parse(data);

		if(data.status == "deleted"){
			submitCancelAppointmentModalSuccess(ifForceDelete, data);
			classModal.modal('toggle');
			showClientBookedMsg(data.message);
		}  else if(data.status == 'error' && data.unsatisyReas == 'client_not_found'){
				swal({
					title: "Service is already cancelled.",
					allowOutsideClick: false,
					showCancelButton: false,
					confirmButtonText: 'Okay',
					confirmButtonColor: '#ff4401',
					type : 'warning',
				}, function(isConfirm) {
					if(isConfirm) {
						reloadEvents = true;
						classModal.modal('hide');
					}
				});	
			}

	});
}
/* end:Submit appointment cancel modal data */

/* start: show popover over modal */
function showPopoverOverModal(elem, popoverOpt){
	elem.popover(popoverOpt).popover('show').data('bs.popover').tip().addClass('superior');
	calPopupHelper.removeClass('hidden').addClass('superior');
	shownPopover.push(elem)
}
/* end: show popover over modal */

/* start: Validate client form */
function validateClientForm(clientForm, ifCallValidation){
	if(ifCallValidation)
		var isFormValid = clientForm.find(':input').valid();
	else
		var isFormValid = true;

	var isNewClient = clientForm.find('input[name="isNewClient"]');

	if(isNewClient.is(":checked")){
		var newClientField = clientForm.find('input[name="clientName"]'),
		emailFieldVal = clientForm.find('input[name="clientEmail"]').val().trim(),
		phoneNumbFieldVal = clientForm.find('input[name="clientNumb"]').val().trim(),
		newClientReqMsg = clientForm.find('div.new-client-req-msg');

		newClientField.prop('required', true);
		if(!newClientField.valid())
			isFormValid = false;
		newClientField.prop('required', false);

		if(emailFieldVal == '' && phoneNumbFieldVal == ''){
			newClientReqMsg.removeClass('hidden');
			isFormValid = false;
		}
		else if(emailFieldVal != '' || phoneNumbFieldVal != '')
			newClientReqMsg.addClass('hidden');
	}
	else if(!validateTypeaheadClient(clientForm))
		isFormValid = false;

	return isFormValid;
}
/* end: Validate client form */

/* start: Validate typeahead client */
function validateTypeaheadClient(clientForm){
	var clientTypeaheadField = clientForm.find('.clientList'),
	clientIdField = clientForm.find("input[name='clientId']"),
	clientId = clientIdField.val(),
	formGroup = clientIdField.closest('.form-group');

	clientTypeaheadField.prop('required', true);
	if(!clientTypeaheadField.valid()){
		clientTypeaheadField.prop('required', false);
		return false;
	}
	clientTypeaheadField.prop('required', false);

	if(clientTypeaheadField.val() != '' && (clientId == null || clientId == '' || clientId == 0)){
		setFieldInvalid(formGroup, 'Please select a valid client.', clientIdField.prev())
		return false;
	}
	return true;
}	
/* end: Validate typeahead client */

/* start: Get client number along with country code */
function getClientFullNumb(clientForm){
	var phoneNumbField = clientForm.find('input[name="clientNumb"]');

	if(phoneNumbField.val() != ''){
		var selCountry = phoneNumbField.intlTelInput("getSelectedCountryData");
		if(Object.keys(selCountry).length)
			return phoneNumbField.intlTelInput("getNumber");
		else
			return phoneNumbField.val();
	}
	return '';
}
/* end: Get client number along with country code */

/* start: Populate staffs based on the area */
function getAreaStaffs(areaDd, defaultStaff){	
	if(isUserType(['Staff'])){
		if(areaDd.hasClass('toolBarDd')){
			staffFilterCalend.selectpicker('hide');
			staffFilterCal(loggedInUser.id);
		}
		else{
			var dd = areaDd.closest('form').find('select[name="staff"]');

			dd.html('<option value="'+loggedInUser.id+'">'+loggedInUser.name+'</option>').selectpicker('refresh');

			var setGroupDisp = dd.closest('.set-group').find('.set-group-disp');
			setGroupDisp.find('span').text(loggedInUser.name);
			setGroupDisp.find('a').hide();

			if(typeof defaultStaff == 'undefined')
				defaultServices = false;
			else
				defaultServices = true;

			if(eventModal == 'appointModal')
				getStaffServices(dd, defaultServices);
			else if(eventModal == 'classModal')
				getStaffClasses(dd, defaultServices);
		}
	}
	else if(isUserType(['Client'])){
		var formData = {};

		if(areaDd.hasClass('toolBarDd'))
			formData['areaId'] = findAreaId();
		else{
			var isAreaMarkedAsDeleted = isFieldMarkedAsDeleted(areaDd);
			if(isAreaMarkedAsDeleted)
				formData['areaId'] = isAreaMarkedAsDeleted; 
			else
				formData['areaId'] = areaDd.val();
			var staffId = findStaffId();
			if(staffId == 'all-ros'){
				formData['startDate'] = getCalendStartDate();
				if(ifDayView())
					formData['day'] = getCalendStartDay();
				else
					formData['endDate'] = getCalendEndDate();
			}
		}

		toggleWaitShield('show');
		$.get(public_url+'areas/staffs', formData, function(data){
			toggleWaitShield('hide');
			if(areaDd.hasClass('toolBarDd')){
				var dd = staffFilterCalend,
				options = '<option value="all-ros">All rostered staff</option><option value="all" selected>All staff</option>';

				if(!$.isEmptyObject(data)){
					$.each(data, function(key, value){
						options += '<option value="'+key+'">'+value+'</option>';
					});
				}
				dd.html(options);
				if(typeof defaultStaff != 'undefined')
					dd.val(defaultStaff);
				dd.selectpicker('refresh');

				staffFilterCalend.trigger('change');
			}
			else{
				var dd = areaDd.closest('form').find('select[name="staff"]'),
				setGroupDisp = dd.closest('.set-group').find('.set-group-disp'),
				setGroupDispSpan = setGroupDisp.find('span');

				if(eventModal == 'busyModal' && eventObj.length && eventObj.find('input[name="isStaffDeleted"]').val() == 'true'){
					setGroupDispSpan.text('This staff has been deleted').addClass('text-danger');
					toggleDeletedMarkOnField(dd, defaultStaff)
					//setGroupDisp.find('a').hide();
				}
				else{
					if(typeof defaultStaff == 'undefined'){
						defaultServices = false;
						var staffId = findStaffId(),
						areaId = findAreaId();
						if(areaId && areaId != 'all' && staffId && staffId != 'all' && staffId != 'all-ros')
							defaultStaff = staffId;
					}
					else
						defaultServices = true;

					var options = '',
					newStaffs = [];

					if(/*(eventModal == 'classModal' || eventModal == 'busyModal') &&*/ eventObj.length && isAreaMarkedAsDeleted){
						var firstVal = eventObj.find('input[name="staffName"]').val();
						options += '<option value="'+defaultStaff+'">'+firstVal+'</option>';	
					}
					else if(!$.isEmptyObject(data)){
						var firstVal = '';
						$.each(data, function(key, value){
							options += '<option value="'+key+'">'+value+'</option>';
							newStaffs.push(parseInt(key, 10));
							if(!firstVal){
								if(typeof defaultStaff != 'undefined' && defaultStaff == key)
									firstVal = value;
								else if(typeof defaultStaff == 'undefined')
									firstVal = value;
							}
						});
					}
					//dd.html(options);

					if(/*(eventModal == 'classModal' || eventModal == 'busyModal') &&*/ dd.data('new-instance') != 'true'){
						var /*selectedStaffObj = {},
						isCompatible = true,*/
							isStaffMarkedAsDeleted = isFieldMarkedAsDeleted(dd)/*,
							classDd = classModal.find('select[name="staffClass"]')*/;

							setFieldNeutral(dd);

							if(isAreaMarkedAsDeleted || isStaffMarkedAsDeleted){
								dd.html(options);
								toggleDeletedMarkOnField(dd, 0)
								setGroupDispSpan.text(firstVal).removeClass('text-danger');
								dd.selectpicker('refresh');

								if(eventModal == 'appointModal'){
									dd.data('services-new-instance', 'true');
									getStaffServices(dd, defaultServices);
								}
								else if(eventModal == 'classModal'){
									classModal.find('select[name="staffClass"]').data('new-instance', 'true')
									getStaffClasses(dd, defaultServices);
								}
							}
							else{
								var selectedStaffObj = {},
								isCompatible = true,
								/*if(isStaffMarkedAsDeleted)
									var selectedStaff = parseInt(isStaffMarkedAsDeleted, 10);
									else*/
										/*var*/ selectedStaff = parseInt(dd.val(), 10);

									if(selectedStaff){
									/*if(isStaffMarkedAsDeleted)
										selectedStaffObj = {name:eventObj.find('input[name="staffName"]').val()};
										else*/
											selectedStaffObj = {name:dd.find('option:selected').text()};

										if($.inArray(selectedStaff, newStaffs) == -1) 
											isCompatible = false;
										else
											isCompatible = true;

										if(!isCompatible){
											staffText = '<li class="list-group-item">'+selectedStaffObj.name+'</li>'

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
											}, 
											function(isConfirm){
												if(!isConfirm){
									    			//alert('revert area')
									    			areaDd.val(areaDd.data('last-val')).selectpicker('refresh');
									    		}
									    		else{
									    			//alert('change staff')
									    			//areaDd.data('last-val', areaDd.val())
									    			if(eventModal == 'classModal' || eventModal == 'appointModal')
									    				staffDdTempOpts = {html:dd.html(), lastSelectedId:selectedStaff, lastSelectedText:selectedStaffObj.name};

									    			dd.html(options);
									    			toggleDeletedMarkOnField(dd, 0)
									    			setGroupDispSpan.text(firstVal).removeClass('text-danger');
									    			dd.selectpicker('refresh');
									    			//classDd.data('new-instance', 'true')
									    			if(eventModal == 'appointModal')
									    				getStaffServices(dd, defaultServices);
									    			else if(eventModal == 'classModal')
									    				getStaffClasses(dd, defaultServices);
									    		}
									    	});
										}
										else{
											//alert('compatible')
											//areaDd.data('last-val', areaDd.val())
											if(eventModal == 'classModal' || eventModal == 'appointModal')											
												staffDdTempOpts = {html:dd.html(), lastSelectedId:selectedStaff, lastSelectedText:selectedStaffObj.name};

											dd.html(options).val(selectedStaff);
											toggleDeletedMarkOnField(dd, 0)
											setGroupDispSpan.text(selectedStaffObj.name).removeClass('text-danger');
											dd.selectpicker('refresh');
										//classDd.data('new-instance', 'true')
										if(eventModal == 'appointModal')
											getStaffServices(dd, defaultServices);
										else if(eventModal == 'classModal')
											getStaffClasses(dd, defaultServices);
									}
								}
								else{
									//console.log('no last selected staff')
									/*areaDd.data('last-val', areaDd.val())
									dd.html(options);
									toggleDeletedMarkOnField(dd, 0)
									setGroupDispSpan.text(selectedStaffObj.name).removeClass('text-danger');
									dd.selectpicker('refresh');*/
								}
							}
						}
						else{
							dd.html(options);

							if((eventModal == 'appointModal' || eventModal == 'classModal') && eventObj.length && eventObj.find('input[name="isStaffDeleted"]').val() == 'true'){
								setGroupDispSpan.text('This staff has been deleted').addClass('text-danger');
								toggleDeletedMarkOnField(dd, defaultStaff)
							}
							else{
								toggleDeletedMarkOnField(dd, 0)

								if(typeof defaultStaff != 'undefined')
									dd.val(defaultStaff);

								setGroupDispSpan.text(firstVal).removeClass('text-danger');
							}

							dd.selectpicker('refresh');

							if(eventModal == 'appointModal')
								getStaffServices(dd, defaultServices);
							else if(eventModal == 'classModal')
								getStaffClasses(dd, defaultServices);
						}
					}

					dd.data('new-instance', 'false')
				}
			},'json');
}
}
/* end: Populate staffs based on the area */

/* start: Get classes list */
function getClasses(defaultClass, callback){
	//if(isUserType(['Admin'])){
		var classDd = classModal.find('select[name="staffClass"]');

		if(typeof defaultClass == 'undefined' || !defaultClass)
			defaultClass = 0;
		else
			defaultClass = parseInt(defaultClass, 10);

		if(defaultClass && eventObj.find('input[name="isClassDeleted"]').val() == 'true'){
			//Class has been deleted
			toggleField_DelMsg({kase:'trashed', field:classDd, entity:eventObj.find('input[name="className"]').val()});

			toggleAvailableMarkOnField(classDd, 0);
			classDd.html('').selectpicker('refresh');

			populateClassRelatedFields();
			//getStaffsAndAreas('class')
			if(typeof callback != 'undefined')
				callback('class');
		}
		else{
			toggleWaitShield('show');
			$.get(public_url+'settings/business/classes/all', function(data){	
				toggleWaitShield('hide');
				var options = '<option value="">-- Select --</option>';

				if(!$.isEmptyObject(data)){
					$.each(data, function(key, value){
						options += '<option value="'+value.id+'" data-duration="'+value.duration+'" data-capacity="'+value.capacity+'" data-price="'+value.price+'">'+value.name+'</option>';
					});
				}

				classDd.html(options);
				if(defaultClass){
					//edit case
					classDd.val(defaultClass);
					populateClassRelatedFields();
				}

				toggleAvailableMarkOnField(classDd, 1);
				toggleField_DelMsg({kase:'exist', field:classDd});
				classDd.selectpicker('refresh');
				//getStaffsAndAreas('class')
				if(typeof callback != 'undefined')
					callback('class');
			},'json');
		}
	//}
}
/* end: Get classes list */

/* check date exist between two dates*/

function checkDateExistsBetTwo(checkdate)
{
	var initial_date= moment();
	var num_days=6;
		//var max_date=moment(initial_date).add('days', num_days).format('YYYY-MM-DD');
		//var current_date=moment().format('YYYY')+'-'+moment().format('MM')+'-'+moment().format('DD');
		var isLdc = classModal.find('a#unlinkClientClass').data('is-ldc');
		if(isLdc && rescheduleClientsCount()){
			var eventDate = classModal.find('.eventDateDisp').text();
			var dateTo = moment(eventDate).startOf('isoWeek').add('days', num_days).format('YYYY-MM-DD');
			var dateFrom = moment(eventDate).startOf('isoWeek').format('YYYY-MM-DD');
		}else{
			var dateFrom = moment().format('YYYY')+'-'+moment().format('MM')+'-'+moment().format('DD');
			var dateTo = moment(initial_date).add('days', num_days).format('YYYY-MM-DD');
		}
		var dateCheck = checkdate;

		//alert(dateCheck+dateFrom+dateTo)
		if(dateCheck >= dateFrom && dateCheck <= dateTo)
		{
			return true;
		}else
		{
			return false;
		}


	}
	/* start: Get services list */
	function getServices(defaultService, callback){
	//if(isUserType(['Admin'])){
		var serviceDd = classModal.find('select[name="staffservice"]');

		if(typeof defaultService == 'undefined' || !defaultService)
			defaultService = 0;
		else
			defaultService = parseInt(defaultService, 10);

		if(defaultService && eventObj.find('input[name="isServiceDeleted"]').val() == 'true'){
			//Service has been deleted
			toggleField_DelMsg({kase:'trashed', field:serviceDd, entity:eventObj.find('input[name="serviceName"]').val()});

			toggleAvailableMarkOnField(serviceDd, 0);
			serviceDd.html('').selectpicker('refresh');

			populateServiceRelatedFields2();
			if(typeof callback != 'undefined')
				callback('service');
		}
		/*else if(defaultService && isSalesProcessEvent()){
			//Event belongs to sales process
			populateServices([{"id":eventObj.find('input[name="serviceId"]').val(), "name":eventObj.find('input[name="serviceName"]').val(), "duration":eventObj.find('input[name="duration"]').val(), "price":eventObj.find('input[name="price"]').val()}], defaultService, callback);
		}*/
		else{
			
			if(isSalesProcessMode())
				var ajaxAction = 'sales-process/'+parent.salesProcessStepNumb;
			else if(defaultService && isSalesProcessEvent())
				var ajaxAction = 'sales-process/'+eventObj.find('input[name="salesProcessEvent"]').val();
			else
			{
				if(defaultService == 0)
				{
					var ajaxAction = 'all?mode=add';
				}
				else
				{
					var ajaxAction = 'all';	
				}
			}

			var eventDate = setEventDate(classModal, 'submit');

			if(checkDateExistsBetTwo(eventDate.date)==true)
			{
				toggleWaitShield('show');
				$.get(public_url+'settings/business/services/'+ajaxAction, function(data){	
					toggleWaitShield('hide');
					populateServices(data, defaultService, callback);
				},'json');
			}

			
		}
	//}
}
/* end: Get services list */

/* start: Populate options in service dropdown */
function populateServices(data, defaultService, callback){
	var options = '<option value="">-- Select --</option>';
	if(!$.isEmptyObject(data)){
		$.each(data, function(key, value){
			if(key != 'initial_status')
				options += '<option value="'+value.id+'" data-salesStep="'+value.sales_process_step+'" data-duration="'+value.duration+'" data-price="'+value.price+'">'+value.name+'</option>';

		});
	}
	$("#initial_status").hide();
	$("#initial_status").html(data.initial_status.cs_initial_status);
	// $("#ini-stat").html(data.initial_status.cs_initial_status);
	$("#ini-stat").html($("#ini-stat").html());
	$("#initial_status").attr("data-val"," ");
	$("#initial_status").attr("data-val",data.initial_status.cs_initial_status);

	// $("input[name='appointStatusOpt']").val(data.initial_status.cs_initial_status);

	var serviceDd = classModal.find('select[name="staffservice"]');
	serviceDd.html(options);

	if(isSalesProcessMode()){
		var firstOpt = serviceDd.find('option[value!=""]:first');
		serviceDd.val(firstOpt.val());
		populateServiceRelatedFields2({duration:firstOpt.data('duration'), price:firstOpt.data('price')});
	}
	else if(defaultService){
		//edit case
		serviceDd.val(defaultService);
		populateServiceRelatedFields2();
	}
	
	toggleAvailableMarkOnField(serviceDd, 1);
	toggleField_DelMsg({kase:'exist', field:serviceDd});
	serviceDd.selectpicker('refresh');	
	if(typeof callback != 'undefined')
		callback('service');
}
/* end: Populate options in service dropdown */

/* start: Get staff and area list based on the class/service */
function getStaffsAndAreas(bookingType){

	if(typeof bookingType == 'undefined')
		bookingType = getBookingType()

	if(bookingType == 'class'){
		getStaffs('class', function(){
			getAreas('class');
		});
		//getAreas('class');
	}
	else if(bookingType == 'service'){

		getAllStaffs('service');


		getAreas('service');
	}
}
/* end: Get staff and area list based on the class/service */





/* start: Get staff list based on the class/service */
function getStaffs(bookingType, callback){
	//if(isUserType(['Admin'])){

		if(bookingType == 'class')
			var entityDd = classModal.find('select[name="staffClass"]');
		else if(bookingType == 'service')
			var entityDd = classModal.find('select[name="staffservice"]');

		var modalStaffDd = classModal.find('select[name="staff"]'),
		staffSetGroupDispSpan = modalStaffDd.closest('.set-group').find('.set-group-disp span');

			//console.log(entityDd)


			if(!isFieldMarkedAsAvail(entityDd)){
			//Entity has been deleted
			modalStaffDd.html('').selectpicker('refresh');

			var selectedStaffName = eventObj.find('input[name="staffName"]').val();

			if(eventObj.find('input[name="isStaffDeleted"]').val() == 'true'){
				//Staff has been deleted
				selectedStaffName += ' has been deleted';
			}

			staffSetGroupDispSpan.html(selectedStaffName).addClass('text-danger');

			if(typeof callback != 'undefined')
				callback();


		}
		else{
			staffSetGroupDispSpan.removeClass('text-danger');
			toggleAvailableMarkOnField(modalStaffDd, 1);
			setFieldNeutral(modalStaffDd);

			var entityVal = entityDd.val();
			if(bookingType == 'class')
			{
				var duration = classModal.find('select[name="classDur"]').val();
				if(duration == '')
				{
					duration = eventObj.duration; 
				}
			}
			
			else
				var duration = classModal.find('select[name="serviceDur"]').val();


                         //alert(entityVal +'&&'+ duration);       
                         if(entityVal && duration){
                         	var formData = {},

                         	eventDate = setEventDate(classModal, 'submit');
					//console.log(eventDate);
					formData['date'] = eventDate.date;
					formData['day'] = eventDate.day;
					formData['duration'] = duration;
					formData['time'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
					if(eventObj.length)
						formData['eventId'] = eventObj.find('input[name="eventId"]').val();
					if(bookingType == 'class')
						var url = 'classes/';
					else if(bookingType == 'service')
						var url = 'services/';

				//console.log(eventDate.date);

				if(checkDateExistsBetTwo(eventDate.date)==true)
				{
					toggleWaitShield('show');
					$.get(public_url+'settings/business/'+url+entityVal+'/free-staffs', formData, function(data){	
						toggleWaitShield('hide');
//				http://192.168.0.50/result/public/settings/business/classes/14/free-staffs

populateStaffs(data, callback)
},'json');
				}


				
			}
			else
				populateStaffs({}, callback)
		}
	//}
}
/* end: Get staff list based on the class/service */


/* start: Populate options in staff dropdown */
function populateStaffs(data, callback){
	//console.log(data)

	var ids = [],
	options = '';
	if(typeof data != 'undefined' && !$.isEmptyObject(data)){
		$.each(data, function(key, value){
			options += '<option value="'+key+'" data-fav-areas="'+value.favAreas+'">'+value.name+'</option>';
			ids.push(key);
		});
	}
	
	var selectedStaff = 0,
	selectedStaffName = '',
	modalStaffDd = classModal.find('select[name="staff"]')
	staffSetGroupDispSpan = modalStaffDd.closest('.set-group').find('.set-group-disp span');

	if(eventObj.length)
		var defaultStaff = eventObj.find('input[name="staffId"]').val();
	else
		var defaultStaff = 0;
	if(defaultStaff){
		//edit case
		//console.log(ids)
		var ifStaffExist = $.inArray(defaultStaff, ids);
		//alert(ifStaffExist)
		if(ifStaffExist < 0 && modalStaffDd.hasClass('hidden')){
			//staff list does not have selected staff
			staffSetGroupDispSpan.addClass('text-danger');
			toggleAvailableMarkOnField(modalStaffDd, 0);

			var staffName = eventObj.find('input[name="staffName"]').val()
			if(eventObj.find('input[name="isStaffDeleted"]').val() == 'true'){
				//Staff has been deleted
				selectedStaffName = staffName+' has been deleted';
			}
			else
				selectedStaffName = staffName/*+' is not available'*/;			
		}
		else if(ifStaffExist >= 0)
			selectedStaff = defaultStaff;
	}
	else{
		//add case
		var staffId = parseInt(findStaffId(), 10);
		if(staffId && staffId != 'all' && staffId != 'all-ros' && $.inArray(staffId, ids) >= 0){
			//filter has staff selected and staff list have filter selected staff
			selectedStaff = staffId;
		}
	}

	modalStaffDd.html(options);
	if(selectedStaff)
		modalStaffDd.val(selectedStaff);
	if(!selectedStaffName)
		selectedStaffName = modalStaffDd.find('option:selected').text();
	if(!selectedStaffName){
		selectedStaffName = 'No staff found'
		staffSetGroupDispSpan.addClass('text-danger');
	}
	staffSetGroupDispSpan.text(selectedStaffName);

	modalStaffDd.selectpicker('refresh');

	if(typeof callback != 'undefined')
		callback();
}
/* end: Populate options in staff dropdown */

/* start: Get areas list according to class/service */
function getAreas(bookingType){
	//if(isUserType(['Admin'])){
		if(bookingType == 'class')
			var entityDd = classModal.find('select[name="staffClass"]');
		else if(bookingType == 'service')
			var entityDd = classModal.find('select[name="staffservice"]');

		var areaDd = classModal.find('select[name="modalLocArea"]')/*,
		areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp')*/;
		if(isFieldMarkedAsAvail(entityDd) != undefined && !isFieldMarkedAsAvail(entityDd)){
			//Entity has been deleted
			areaDd.html('').selectpicker('refresh');

			var span = '';
			eventObj.find("input[name='area[]']").each(function(){
				var data = JSON.parse($(this).val());
				if(data.isAreaDeleted == true){
					//Area has been deleted
					span += '<span class="text-danger">'+data.locAreaName+' has been deleted</span><br>';
				}
				else
					span += '<span>'+data.locAreaName+'</span><br>';
			});
			span = span.slice(0,-4)

			/*var areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp');
			areaSetGroupDisp.find('span').remove()
			areaSetGroupDisp.find('br').remove()
			areaSetGroupDisp.prepend(span)*/
			showAreasText(span);
		}
		else{
			/*areaSetGroupDisp.find('span').remove()
			areaSetGroupDisp.find('br').remove()*/
			toggleAvailableMarkOnField(areaDd, 1);
			setFieldNeutral(areaDd);

			var entityVal = entityDd.val();

			

			if(bookingType == 'class')
				var duration = classModal.find('select[name="classDur"]').val();
			else
				var duration = classModal.find('select[name="serviceDur"]').val();
			if(entityVal && duration){
				var formData = {},
				eventDate = setEventDate(classModal, 'submit');
				formData['date'] = eventDate.date;
				formData['day'] = eventDate.day;
				formData['time'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
				formData['duration'] = duration;
				if(eventObj.length)
					formData['eventId'] = eventObj.find('input[name="eventId"]').val();
				if(bookingType == 'class')
					var url = 'classes/';
				else if(bookingType == 'service')
					var url = 'services/';
				
				if(checkDateExistsBetTwo(eventDate.date)==true)
				{

					toggleWaitShield('show');
					//http://192.168.0.50/result/public/settings/business/services/12/free-areas
					$.get(public_url+'settings/business/'+url+entityVal+'/free-areas', formData, function(data){
						toggleWaitShield('hide');
						populateAreas(data);
					},'json');
				}
				
			}
			else
				populateAreas();
		}
	//}
}
/* end: Get areas list according to class/service */

/**
 * Get all Staff List
 */
function getAllStaffs(bookingType){
	$.get(public_url+'staffs/all/', function(data){   
		var classModal = $('#classModal');
		var ids = [],
		options = '';
		if(typeof data != 'undefined' && !$.isEmptyObject(data)){
			$.each(data, function(key, value){
				options += '<option value="'+value.id+'">'+value.name+'</option>';
				ids.push(key);
			});
		}
		modalStaffDd = classModal.find('select[name="staff"]');
		modalStaffDd.html(options);
		if(eventObj != undefined && !jQuery.isEmptyObject(eventObj)){
			modalStaffDd.val(eventObj.find('input[name="staffId"]').val());
		}
		
		modalStaffDd.selectpicker('refresh');
	},'json')
}

/* start: Populate options in area dropdown */
function populateAreas(data){
	var ids = [],
	options = '';
	if(typeof data != 'undefined' && !$.isEmptyObject(data)){
		$.each(data, function(key, value){
			options += '<option value="'+key+'">'+value+'</option>';
			ids.push(parseInt(key, 10));
		});
	}

	var selectedAreas = [],
	span = '',
	areaDd = classModal.find('select[name="modalLocArea"]');

	if(eventObj.length)
		var isEditMode = 1;
	else
		var isEditMode = 0;
	if(isEditMode){
		//edit case
		eventObj.find("input[name='area[]']").each(function(){
			var data = JSON.parse($(this).val()),
			ifAreaExist = $.inArray(data.id, ids);
			if(ifAreaExist < 0 && areaDd.hasClass('hidden')){
				//area list does not have selected area
				toggleAvailableMarkOnField(areaDd, 0);
				if(data.isAreaDeleted == true){
					//Area has been deleted
					span += '<span class="text-danger">'+data.locAreaName+' has been deleted</span><br>';
				}
				else
					span += '<span class="text-danger">'+data.locAreaName+'</span><br>';
			}
			else if(ifAreaExist >= 0)
				selectedAreas.push(data.id)
		});
	}
	else{
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
		if(!selectedAreas.length){
			//Selected staff doesn't have favorite areas in the current areas list
			var areaId = parseInt(findAreaId(), 10);
			if(areaId && areaId != 'all' && $.inArray(areaId, ids) >= 0){
				//filter has area selected and area list have filter selected area
				selectedAreas.push(areaId)
			}
		}
	}

	areaDd.html(options);
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
	setAreas(selectedAreas, span);

	if(!selectedAreas.length && options){
		var firstOption = areaDd.find('option:first').val();
		if(isEditMode){
			areaDd.val(firstOption)
			areaDd.selectpicker('refresh');
		}
		else
			setAreas([firstOption]);
	}

	//areaDd.selectpicker('refresh');
}
/* end: Populate options in area dropdown */

function getFavAreas(areasId){
	var selectedAreas = [],
	selectedStaffFavAreas = classModal.find('select[name="staff"] option:selected').data('fav-areas');

	if(selectedStaffFavAreas){
		//Selected staff has favorited areas
		selectedStaffFavAreas = selectedStaffFavAreas.toString();
		selectedStaffFavAreas = selectedStaffFavAreas.split(',');

		if(typeof areasId == 'undefined'){
			areasId = [];
			classModal.find('select[name="modalLocArea"] option').each(function(){
				areasId.push(parseInt($(this).val(), 10))
			})
		}
		$.each(selectedStaffFavAreas, function(key, value){
			value = parseInt(value, 10);
			if($.inArray(value, areasId) >= 0)
				selectedAreas.push(value)
		});
	}

	return selectedAreas;
}

function setAreas(selectedAreas, span){
	if(typeof span == 'undefined')
		span = '';

	var areaDd = classModal.find('select[name="modalLocArea"]');

	if(selectedAreas.length)
		areaDd.val(selectedAreas);

	areaDd.find('option:selected').each(function(){
		span += '<span>'+$(this).text()+'</span><br>';
	});
	if(span)
		span = span.slice(0,-4)
	else
		span = '<span class="text-danger">No area found</span>';
	
	showAreasText(span);
	areaDd.selectpicker('refresh');
	//areaDd.closest('.set-group').find('.set-group-disp').prepend(span)
}

function showAreasText(span){
	var areaDd = classModal.find('select[name="modalLocArea"]'),
	areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp');

	areaSetGroupDisp.find('span').remove()
	areaSetGroupDisp.find('br').remove()
	areaSetGroupDisp.prepend(span)
}

/* start: Get selected area id from the calendar */
function findAreaId(){
	if(typeof areaDdCalend != 'undefined')
		return 'all'/*areaDdCalend.val()*/;
	return '';
}
/* end: Get selected area id from the calendar */

/* start: Get selected staff id from the calendar */
function findStaffId(){
	//alert(staffFilterCalend.val());
	/*if(typeof staffFilterCalend != 'undefined'){
		if(isUserType(['Client']))
			return 'all' .staffFilterCalend.val();
		else if(isUserType(['Staff']))
			return loggedInUser.id;
	}*/
	return 'all';
}
/* end: Get selected staff id from the calendar */

/* start: Update booked client quick info */
function updateBookedclientQuickInfo(){


	var infoElem = classModal.find('.linkedclients-text'),
	linkedClientsCount = linkedclientsCount(),
	progressBar = classModal.find('div.progress-bar'),
	capacity = getEventCapacity();

	/*var bookingType = getBookingType();
	if(bookingType == 'class')
		var capacity = classModal.find('input[name="classCap"]').val();
	else if(bookingType == 'service')
		var capacity = 1;*/

	var text = 'No clients booked';
	if(linkedClientsCount)
		text = linkedClientsCount+' / '+capacity+' clients booked';
	infoElem.html(text);
	if(capacity && capacity < linkedClientsCount)
		infoElem.not('span').addClass('text-danger');
	else
		infoElem.removeClass('text-danger');

	var progressPercent = 0;
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
		progressBar.removeClass('progress-bar-danger').addClass('progress-bar-success')
}
/* end: Update booked client quick info */

/* start: Count linked clients */
function linkedclientsCount(ifConfirmedOnly){
	if(typeof ifConfirmedOnly != 'undefined')
		return classModal.find('div#linkedclientList > a[data-status="Confirm"]').length;

	return classModal.find('div#linkedclientList > a').length;
}
/* end: Count linked clients */

/* start: Count clients to reschedule */
function rescheduleClientsCount(){
	return Object.keys(rescheduleClients).length;
}
/* end: Count clients to reschedule */

/* start: Toggle client form links */
function toggleClassClientLnks(mode, calcEligibility){
	if(typeof calcEligibility != 'undefined'){
		if(!pastCurrentMode)
			toggleClassClientLnks(mode)
		else{
			classModal.find('a#linkClientClass').hide();
			// classModal.find('a#unlinkClientClass').hide();
			classModal.find('a#resetClientlinkForm').hide();
			classModal.find('a#confirmClient').hide();
		}
	}
	else{
		if(isUserType(['Staff']) && !$.isEmptyObject(eventObj) && eventObj.find('input[name="userId"]').val() != loggedInUser.userId){
			classModal.find('a#linkClientClass').hide();
			// classModal.find('a#unlinkClientClass').hide();
			classModal.find('a#resetClientlinkForm').hide();
			classModal.find('a#confirmClient').hide();
		}
		else{
			if(mode == 'create'){
				classModal.find('a#linkClientClass').show();
				// classModal.find('a#unlinkClientClass').hide();
				classModal.find('a#resetClientlinkForm').hide();
				classModal.find('a#confirmClient').hide();
			}
			else{
				classModal.find('a#linkClientClass').hide();
				classModal.find('a#unlinkClientClass').show();

				var bookingType = getBookingType()
				if(bookingType == 'class')
					classModal.find('a#resetClientlinkForm').show();
				else if(bookingType == 'service'){
					$('.unlink-prompt').addClass('hidden');
					classModal.find('a#resetClientlinkForm').hide();
					classModal.find('a#confirmClient').hide();
				}
			}
		}
	}
	toggleIf_make_up_sessionOption('hide');
}
/* end: Toggle client form links */

/* start: Reset client form */
function clearClientForm(clientForm, ifResetForm){
	clientForm.find('div.checkbox.m-t-5').show();
	clientForm.find(':input').prop('readonly', false);
	clientForm.find('.disableable').prop('disabled', false);
	if(ifResetForm){
		clearForm(clientForm);
		clientForm.find('input[name="isNewClient"]').trigger('change');
	}
	classModal.find('div#linkedclientList > a').removeClass('active');
}
/* end: Reset client form */

function getEventCapacity(){
	var bookingType = getBookingType();
	if(bookingType == 'class'){
		if(pastCurrentMode)
			return eventObj.find('input[name="capacity"]').val();
		return classModal.find('input[name="classCap"]').val();
	}
	else if(bookingType == 'service')
		return 1;
}

/* start: Check if linked clients exceed class capacity */
function ifClientExceedEventCapacity(mode){
	var capacity = getEventCapacity();

	if(mode == 'add')
		linkedClientsCount = linkedclientsCount();
	else
		linkedClientsCount = linkedclientsCount(true);

	if(!capacity || capacity <= linkedClientsCount)
		return true;

	return false;
}
/* end: Check if linked clients exceed class capacity */

/* start: Confirm if to overbook class with clients */
function classOverBookConfirm(callback){
	if(ifClientExceedEventCapacity('add')){
		swal({
			title: 'This class has reached its capacity',
			allowOutsideClick: true,
			showCancelButton: true,
			confirmButtonText: 'Still add',
			confirmButtonColor: '#ff4401',
			cancelButtonText: "Don't add"
		}, 
		function(isConfirm){
			if(isConfirm)
				callback(true);
		});
	}
	else	
		callback();
}
/* end: Confirm if to overbook class with clients */

/* start: Link existing client with class */
function linkClientClass(clientData, clientForm){
	clearClientForm(clientForm, true);

	updateLinkedclientList(clientData);

	bookingType = getBookingType();
	if(bookingType == 'class')
		toggleClassClientLnks('create')
	else if(bookingType == 'service'){
		clearNotific('serviceClientError');
		selectLatestLinkedClient()
		//toggleClassClientLnks('view')
	}
	
	updateBookedclientQuickInfo();
}
/* end: Link existing client with class */

function selectLatestLinkedClient(){
	$('#linkedclientList > a:first').trigger('click')
}

/* start: Link new client with class */
function linkNewclientClass(clientForm){

	var formData = {},
	emailField = clientForm.find('input[name="clientEmail"]');

	formData['clientName'] = clientForm.find('input[name="clientName"]').val();
	formData['clientNumb'] = getClientFullNumb(clientForm);
	formData['clientEmail'] = emailField.val();
	toggleWaitShield('show');
	$.post(public_url+'client/save', formData, function(data){
		toggleWaitShield('hide');
		var data = JSON.parse(data);
		if(data.status == "added"){
			var data = {id:data.insertId, name:formData['clientName'], phone:formData['clientNumb'], email:formData['clientEmail'], note:clientForm.find('textarea[name="clientNote"]').val()};

			if(getBookingType() == 'class'){
				data.isReducedRate = clientForm.find('input[name="isReducedRate"]:checked').val();
				data.ifRecur = clientForm.find('input[name="ifRecur"]:checked').val();
			}
			linkClientClass(data, clientForm)
			//linkClientClass({id:data.insertId, name:formData['clientName'], phone:formData['clientNumb'], email:formData['clientEmail'], note:clientForm.find('textarea[name="clientNote"]').val(), isReducedRate:clientForm.find('input[name="isReducedRate"]:checked').val(), ifRecur:clientForm.find('input[name="ifRecur"]:checked').val()}, clientForm)
		}
		else if(data.status == "error"){
			$.each(data.errorData, function(key, val){
				$.each(this, function(errorType, message){
					if(errorType == "emailExist" )								
						setFieldInvalid(emailField.closest('.form-group'), message)
				});							
			});
		}
	});
}
/* end: Link new client with class */

/* start: Update linked client list */
function updateLinkedclientList(clientData){


	if(clientData['status'] == null)
		clientData['status'] = 'Confirm';

	if(clientData['isReducedRate'] == null)
		clientData['isReducedRate'] = 0;

	if(clientData['ifRecur'] == null)
		clientData['ifRecur'] = 0;

	if('attendance' in clientData)
		var clientAttendance = clientData['attendance'];
	else
		var clientAttendance = 'Booked';

	if('isExisting' in clientData)
		var isExistingClient = 1;
	else
		var isExistingClient = 0;

	if('isCreatingMakeUpSession' in clientData && clientData['isCreatingMakeUpSession'] != null)
		var isCreatingMakeUpSession = 1;
	else
		var isCreatingMakeUpSession = 0;

	if('isMakeUp' in clientData && clientData['isMakeUp'] != null && clientData['isMakeUp'])
		var isMakeUp = 1;
	else
		var isMakeUp = 0;

	var clientAttendanceIcon = calcClientAttendanceIcon(clientAttendance);

	var listItem = "<a class='list-group-item' href='#' data-id='"+clientData['id']+"' data-name='"+clientData['name']+"' data-phone='"+clientData['phone']+"' data-email='"+clientData['email']+"' data-note='"+clientData['note']+"' data-is-reduced-rate='"+clientData['isReducedRate']+"' data-if-recur='"+clientData['ifRecur']+"' data-status='"+clientData['status']+"' data-photo='"+clientData['photo']+"'>";
	if(clientData['note'])
		listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Has note'><i class='fa fa-comment'></i></span>";
	if(clientData['isReducedRate'])
		listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Reduced Rate'><i class='fa fa-dollar'></i></span>";
	if(clientData['ifRecur'])
		listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Recurring'><i class='fa fa-retweet'></i></span>";
	if(clientData['status'] == 'Waiting')
		listItem += "<span class='badge transparentBadge epic-tooltip clientStatusIcon' rel='tooltip' data-toggle='tooltip' title='Waiting'><i class='fa fa-clock-o'></i></span>";
	if(isCreatingMakeUpSession || isMakeUp)
		listItem += "<span class='badge transparentBadge epic-tooltip' rel='tooltip' data-toggle='tooltip' title='Make Up'><i class='fa fa-code-fork'></i></span>";
	listItem += "<span class='badge transparentBadge epic-tooltip clientAttendanceIcon' rel='tooltip' data-toggle='tooltip' title='"+(clientAttendanceIcon?clientAttendance:'')+"'><i class='"+clientAttendanceIcon+"'></i></span>"+clientData['name']+"</a>";
	classModal.find('div#linkedclientList').prepend(listItem).children(':first-child').find('.epic-tooltip').tooltipster();

	if(isExistingClient && pastCurrentMode && clientData['status'] != 'Waiting')
		createClientAttendanceRow({id:clientData['id'], attendance:clientAttendance, name:clientData['name'], photo:clientData['photo']});

	toggleAttendanceTab();

classClients[clientData['id']] = {note:clientData['note'], isReducedRate:clientData['isReducedRate'], ifRecur:clientData['ifRecur'], attendance:clientAttendance, isExisting:isExistingClient, ifMakeUp:0, makeupNotes:'', isCreatingMakeUpSession:isCreatingMakeUpSession,isLdc:clientData['isLdc'], status:clientData['status'], order:clientLinkOrder/*, isDeleted:clientData['isClientDeleted']*/}; 
clientLinkOrder++;
}
/* end: Update linked client list */

function createClientAttendanceRow(clientData){
	var attendanceRow = "<div class='row m-b-10' id='eventClientAttendance-"+clientData['id']+"'><div class='col-sm-4'><img class='mw-50' src='"+dpSrc(clientData['photo'])+"'> "+clientData['name']+"</div> </div>"; 

	classModal.find('div#classAttendanceList').prepend(attendanceRow).children(':first-child').find('input[value="'+clientData['attendance']+'"]').attr('checked', true).parent().addClass('active');

	classModal.find("#classTabs li").hide();

	classModal.find(".modal-footer").hide();

}

/* start: Determine client attendance icon */
function calcClientAttendanceIcon(clientAttendance){
	if(clientAttendance == 'Attended')
		return 'fa fa-check';
	else if(clientAttendance == 'Did not show')
		return 'fa fa-thumbs-down';
	else
		return '';
}
/* end: Determine client attendance icon */

/* start: Toggle client attendace tab as per time mode */
function toggleAttendanceTab(action){
	if(typeof action == 'undefined'){
		if(!$.isEmptyObject(eventObj))
			var eventType = eventObj.find('input[name="type"]').val();
		else
			var eventType = '';
		if(linkedclientsCount(true) && pastCurrentMode && (eventType == 'class' || (eventType == 'single-service' && scriptSource == 'calendar' && eventObj.find('input[name="appointStatusOpt"]').val() == 'Confirmed'))){
			toggleAttendanceTab('show')
		}
		else
			toggleAttendanceTab('hide')
	}
	else{
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
function unlinkClientClass(clientId, withEpicCash){ 
	var targetEvents = 'this';
	
	if(eventModal == 'classModal' && getBookingType() == 'service'){
		deleteAppoint(false, targetEvents);
	}
	else{       
		var formData = {};
		formData['eventId'] = eventObj.find('input[name="eventId"]').val();
		formData['clientId'] = clientId;
		formData['isLdc'] = classModal.find('a#unlinkClientClass').data('is-ldc');
		if(eventModal == 'classModal'){
			var eventEntity = 'classes';
			if(typeof withEpicCash != 'undefined' && withEpicCash)
				formData['isEpicCash'] = 'yes';	
		}
		else
			var eventEntity = 'busy-time';

		$.post(public_url+'staffevents/'+eventEntity+'/delete', formData, function(data){
			var data = JSON.parse(data);
			if(data.status == "deleted"){
				cancelEventSuccess(data);
				showClientBookedMsg(data.message);
			} else if(data.status == 'error' && data.unsatisyReas == 'client_not_found'){
				swal({
					title: "Client is already removed from session.",
					allowOutsideClick: false,
					showCancelButton: false,
					confirmButtonText: 'Okay',
					confirmButtonColor: '#ff4401',
					type : 'warning',
				}, function(isConfirm) {
					if(isConfirm) {
						reloadEvents = true;
						classModal.modal('hide');
					}
				});	
			}
		});

	}
	$("#makeUpNotesModal").modal('hide');
}
/* end: Unlink client class */

/* start: Change client attendance over UI */
function changeclientAttendanceUi(clientId, clientNewAttendance){
	var clientAttendanceIconHtml = $('#linkedclientList a[data-id="'+clientId+'"] span.clientAttendanceIcon');
	if(clientAttendanceIconHtml.length){
		clientAttendanceIconHtml.tooltipster('content', clientNewAttendance).find('i').attr('class', calcClientAttendanceIcon(clientNewAttendance));
		classClients[clientId].attendance = clientNewAttendance;
	}
}
/* end: Change client attendance over UI */

/* start: Change client attendance */
function changeclientAttendance(clientId, clientNewAttendance, ifRescheduleClient){
	changeclientAttendanceUi(clientId, clientNewAttendance);

	var formData = {};
	formData['eventId'] = classModal.find('input[name="eventId"]').val();
	formData['clientAttendance'] = classClients[clientId].attendance;

	var bookingType = getBookingType();
	if(bookingType == 'class'){
		formData['clientId'] = [clientId];
		formData['clientIfMakeUp'] = classClients[clientId].ifMakeUp;
		formData['makeupNotes'] = classClients[clientId].makeupNotes;
		var ajaxAction = 'classes/past-update';
	}
	else if(bookingType == 'service'){
		/*if(isSalesProcessEvent()){
			if(typeof ifRescheduleClient != 'undefined' && ifRescheduleClient)
				formData['breakSalesProcess'] = false;
			else
				formData['breakSalesProcess'] = true;
		}*/
		var ajaxAction = 'single-service/change-attendance';
	}

	toggleWaitShield('show');
	$.post(public_url+'staffevents/'+ajaxAction, formData, function(data){
		toggleWaitShield('hide');
		var data = JSON.parse(data);
		if(data.status == "added"){
			reloadEvents = true;

			$('#modifiedRow').data('prev-val', clientNewAttendance).removeAttr('id')

			if(classClients[clientId].ifMakeUp){
				$("#makeUpNotesModal").modal('hide');
				unlinkClientClass(clientId);
				$(classClients).removeProp(clientId);
				leaveAttendanceTab();
			}

			if(typeof ifRescheduleClient != 'undefined')
				enterReschecduleClientMode();
		}
	});
}
/* end: Change client attendance */

/* start: Toggle class over book alert */
function toggleEventOverBookAlert(action){
	if(typeof action == 'undefined'){
		if(ifClientExceedEventCapacity('add'))
			toggleEventOverBookAlert('show')
		else
			toggleEventOverBookAlert('hide')
	}
	else{
		clearNotific('eventOverBook');
		if(action == 'show'){
			var clientsTab = classModal.find('div#classClients');
			showNotific(prepareAlert('warning', "This class has reached its capacity", 'eventOverBook'), clientsTab);
		}
	}
}
/* end: Toggle class over book alert */

/* start: Set class time as per selected */
function setEventTime(modal){
	var selectedDatetimeMoment = moment($('input[name="selectedDatetime"]').val()),
	eventTimeField = modal.find('input[name="eventTime"]');
		//console.log(eventTimeField);


		setFieldForTimepicker(eventTimeField, selectedDatetimeMoment.format("h:mm A"));
		setFieldNeutral(eventTimeField);
	}
	/* end: Set class time as per selected */

	/* start: Submit class modal data */
	function submitClassModal(form){
	//alert('aa');return false;
	if(form == null)
		form = classModal.find('form');

	var formData = {};
	formData['classClients'] = classClients;
	formData['pastEvent'] = pastCurrentMode;

	var bookingType = getBookingType();

	if(bookingType == 'class' && pastCurrentMode){
		formData['eventId'] = form.find('input[name="eventId"]').val();
		formData['targetEvents'] = form.find('input[name="targetEvents"]').val();
		formData['calendEndDate'] = getCalendEndDatePriorDate();//getCalendEndDate();
	}
	else{
		form.find(':input').each(function(){
			formData[$(this).attr('name')] = $(this).val()
		})
		var eventDate = setEventDate(classModal, 'submit');
		formData['classDate'] = eventDate.date;
		formData['classDay'] = eventDate.day;
		formData['classTime'] = timeStringToDbTime(formData['eventTime']);
		formData['eventDateTimeEmail'] = classModal.find('.eventDateDisp').text()+' '+formData['eventTime'];
		if(bookingMode != 'changeEvent'){
			formData['staff'] = classModal.find('input[name="serviceStaff"]:checked').val();
		}else{
			formData['staff'] =form.find('select[name="staff"]').val()[0];

		}

		if(bookingType == 'class'){
			formData['ifMarkFav'] = form.find("input[name='ifMarkFav']:checked").val();
			if(formData['ifMarkFav'])
				formData['markFavTill'] = moment(formData['markFavTill'], 'D MMM YYYY').format("YYYY-MM-DD");
		}
		else if(bookingType == 'service'){
			if(isSalesProcessMode())
				formData['salesProcessStepNumb'] = parent.salesProcessStepNumb;

			formData['ifAutoExpireAppoint'] = form.find("input[name='ifAutoExpireAppoint']:checked").val()
			if(formData['ifAutoExpireAppoint'] == 1){
				if(formData['autoExpireAppointDur'] == 'Custom')
					formData['autoExpireDatetime'] = moment(formData['autoExpireAppointDurDate']+' '+formData['autoExpireAppointDurTime']).format("YYYY-MM-DD H:mm:ss");
				else
					formData['autoExpireDatetime'] = moment().add(formData['autoExpireAppointDur'], 'hours').format("YYYY-MM-DD H:mm:ss")
			}
		}

		if(formData['eventRepeat'] == 'Daily' || formData['eventRepeat'] == 'Weekly' || formData['eventRepeat'] == 'Monthly'){
			formData['eventRepeatEnd'] = form.find('input[name="eventRepeatEnd"]:checked').val();

			if(formData['eventRepeatEnd'] == 'On')
				formData['eventRepeatEndOnDate'] = moment(formData['eventRepeatEndOnDate'], 'D MMM YYYY').format("YYYY-MM-DD");
			else if(formData['eventRepeatEnd'] == 'Never')
				formData['calendEndDate'] = getCalendEndDatePriorDate();//getCalendEndDate();

			if(formData['eventRepeat'] == 'Weekly'){
				var temp = [],
				eventRepeatWeekdays = form.find('.eventRepeatWeekdays input[type="checkbox"]:checked');
				if(eventRepeatWeekdays.length){
					eventRepeatWeekdays.each(function(){
						temp.push($(this).val());
					})
					formData['eventRepeatWeekdays'] = temp;
				}
			}
		}

		formData['historyText'] = '';
		if(formData['eventId'] != null && formData['eventId'] != ''){
			if(eventObj.find('input[name="staffId"]').val() != formData['staff'])
				formData['historyText'] += 'Staff changed from '+eventObj.find('input[name="staffName"]').val()+' to '+classModal.find('select[name="staff"] option:selected').text()+'|';

			var oldClassdate = eventObj.find('input[name="date"]').val(),
			oldClassTime = eventObj.find('input[name="startTime"]').val();
			if(oldClassdate != formData['classDate'] || oldClassTime != formData['classTime'])
				formData['historyText'] += 'Reschedule from '+dbDateToDateString(oldClassdate)+' '+dbTimeToTimeString(oldClassTime)+' to '+formData['eventDateTimeEmail']+'|';

			if(bookingType == 'class'){
				if(eventObj.find('input[name="classId"]').val() != formData['staffClass'])
					formData['historyText'] += 'Class changed from '+eventObj.find('input[name="className"]').val()+' to '+classModal.find('select[name="staffClass"] option:selected').text()+'|';

				var oldClassDur = eventObj.find('input[name="duration"]').val();
				if(oldClassDur != formData['classDur'])
					formData['historyText'] += 'Class duration changed from '+oldClassDur+' min to '+formData['classDur']+' min|';

				var oldClassCap = eventObj.find('input[name="capacity"]').val();
				if(oldClassCap != formData['classCap'])
					formData['historyText'] += 'Class capacity changed from '+oldClassCap+' to '+formData['classCap']+'|';

				var oldClassPrice = eventObj.find('input[name="price"]').val();
				if(oldClassPrice != formData['classPrice'])
					formData['historyText'] += 'Class price changed from $'+oldClassPrice+' to $'+formData['classPrice']+'|';
			}
			else if(bookingType == 'service'){
				oldAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val();
				if(oldAppointStatusOpt != formData['appointStatusOpt']){
					formData['historyText'] += 'Booking status changed from '+oldAppointStatusOpt+' to '+formData['appointStatusOpt']+'|';
					/*if(formData['appointStatusOpt'] == 'Confirmed' && formData['appointStatusConfirm'] != 'Not started')
					formData['historyText'] += 'Confirmation status changed from Not started to '+formData['appointStatusConfirm']+'|';*/
				}
				/*else{
					oldAppointStatusConfirm = eventObj.find('input[name="appointStatusConfirm"]').val();
					if(oldAppointStatusOpt == 'Confirmed' && formData['appointStatusOpt'] == 'Confirmed' && oldAppointStatusConfirm != formData['appointStatusConfirm'])
						formData['historyText'] += 'Confirmation status changed from '+oldAppointStatusConfirm+' to '+formData['appointStatusConfirm']+'|';
				}*/

				if(eventObj.find('input[name="serviceId"]').val() != formData['staffservice'])
					formData['historyText'] += 'Service changed from '+eventObj.find('input[name="serviceName"]').val()+' to '+classModal.find('select[name="staffservice"] option:selected').text()+'|';

				var oldServiceDur = eventObj.find('input[name="duration"]').val();
				if(oldServiceDur != formData['serviceDur'])
					formData['historyText'] += 'Service duration changed from '+oldServiceDur+' min to '+formData['serviceDur']+' min|';

				var oldServicePrice = eventObj.find('input[name="price"]').val();
				if(oldServicePrice != formData['servicePrice'])
					formData['historyText'] += 'Service price changed from $'+oldServicePrice+' to $'+formData['servicePrice']+'|';
			}
		}
	}

	if(formData['eventId'] != null && formData['eventId'] != ''){
		if(bookingType == 'class')
			var ajaxAction = 'classes/edit';
		else if(bookingType == 'service')
			var ajaxAction = 'single-service/edit';
	}
	else{
		/*if(bookingType == 'class')
			var ajaxAction = 'classes/create';
			else*/ if(bookingType == 'service')
				var ajaxAction = 'single-service/create';
		}

		var initial_date = moment();
		formData['max_date']= moment(initial_date).add('days',6).format('YYYY-MM-DD');
		formData['initial_choosed_time'] = formData['classDate']+" "+formData['classTime'];
		formData['isEpicCash'] = $("input[name='isEpicCashSer']:checked").val();
		formData['forceAdd'] = form.find("input[name='forceAdd']").val();

		toggleWaitShield('show');
		if(ajaxAction == 'single-service/create'){
			$.post(public_url+'clientevents/single-service/checkservicecondition', formData, function(response){
				toggleWaitShield('hide');
				if(response.status == 'ok'){
					swal({
						type: 'warning',
						title: 'Are you sure to continue?',
						showCancelButton: true,
						allowOutsideClick: false,
						text: response.message,
						showConfirmButton: true,
						confirmButtonColor: '#ff4401', 
					}, 
					function(isConfirm){
						if(isConfirm){
							toggleWaitShield('show');
							$.post(public_url+'clientevents/'+ajaxAction, formData, function(data){
								toggleWaitShield('hide');
								var data = JSON.parse(data);
								if(data.status == "added"){
										data.type = bookingType;
									// if($("#ini-stat").html() == 'Pencilled-In')
									// 	data.message = 'Your service has been pencilled-in. A staff will call you when it will be confirmed.';
						
									showClientBookedMsg(data.message);
									
									submitClassModalSuccess(data);
									signup_class_stat['resOnSignup']=true;
									signup_class_stat['reschedule']=false;
									signup_class_stat['eventType']='none';
									signup_class_stat['resonopen']=false;
									$('#resClassClient').attr('data-resevent-id','');
									
									var url = $(location).attr('href').split("/").reverse();
									if(url[1] == 'edit_service'){
										var url2 = $(location).attr('href').split("/").splice(0, (url.length-2)).join("/");
										window.location.replace(url2);
									}
									getStaffEvents();
								}
								else if(data.status == "error"){
									$.each(data.errorData, function(){
										$.each(this, function(errorType, message){
											if(errorType == 'membershipUnsatisfy'){
												clientEventWithInvoice(function(confirm){
													if(confirm){
														form.find("input[name='forceAdd']").val('yes');
														submitClassModal(form)
													}
													else{
														form.closest('.modal').modal('hide');
														showNotific(message);
													}	
												})
											}
											else if(errorType == 'staffUnavail' || errorType == 'areaUnavail' || errorType == 'lowEpicCash' || errorType == 'workingHourUnavail'){
												form.find('.errorMsg').append(message);
											}
											else if(errorType == 'workingHourUnavail' || errorType == 'businessClosed' || errorType == 'areaNotLink'){
												form.find('.errorMsg').append(message);
											}
											else if(errorType == 'areaUnavail'){
						
											}
										});							
									});
									calPopupHelper.trigger("click");
									switchToErrorTab(form);
								}
							});
						}else{
							classModal.modal('hide');
						}
					});
				}else{
					swal({
						type: 'error',
						title: 'Error!',
						showCancelButton: false,
						allowOutsideClick: false,
						text: response.message,
						showConfirmButton: true,     
					}, 
					function(isConfirm){
						if(!isConfirm){
							classModal.modal('hide');
						}
					});
				}
			},'json');
		}else{
			$.post(public_url+'clientevents/'+ajaxAction, formData, function(data){
				toggleWaitShield('hide');
				var data = JSON.parse(data);
				if(data.status == "added"){
						data.type = bookingType;
					// if($("#ini-stat").html() == 'Pencilled-In')
					// 	data.message = 'Your service has been pencilled-in. A staff will call you when it will be confirmed.';
		
					showClientBookedMsg(data.message);
					
					submitClassModalSuccess(data);
					signup_class_stat['resOnSignup']=true;
					signup_class_stat['reschedule']=false;
					signup_class_stat['eventType']='none';
					signup_class_stat['resonopen']=false;
					$('#resClassClient').attr('data-resevent-id','');
					
					var url = $(location).attr('href').split("/").reverse();
					if(url[1] == 'edit_service'){
						var url2 = $(location).attr('href').split("/").splice(0, (url.length-2)).join("/");
						window.location.replace(url2);
					}
					getStaffEvents();
				}
				else if(data.status == "error"){
					$.each(data.errorData, function(){
						$.each(this, function(errorType, message){
							if(errorType == 'membershipUnsatisfy'){
								clientEventWithInvoice(function(confirm){
									if(confirm){
										form.find("input[name='forceAdd']").val('yes');
										submitClassModal(form)
									}
									else{
										form.closest('.modal').modal('hide');
										showNotific(message);
									}	
								})
							}
							else if(errorType == 'staffUnavail' || errorType == 'areaUnavail' || errorType == 'lowEpicCash' || errorType == 'workingHourUnavail'){
								form.find('.errorMsg').append(message);
							}
							else if(errorType == 'workingHourUnavail' || errorType == 'businessClosed' || errorType == 'areaNotLink'){
								form.find('.errorMsg').append(message);
							}
							else if(errorType == 'areaUnavail'){
		
							}
						});							
					});
					calPopupHelper.trigger("click");
					switchToErrorTab(form);
				}
			});
		}
		
	}
	/* end: Submit class modal data */

	/* start: Initialize clients typeahead */
	function initTypeahead(data, mode){


		var fields = $('.clientList');

		if(mode == 'update')
			fields.typeahead("destroy");

		fields.typeahead({
			source:data,
			items:'all',
			afterSelect:function(selection){

				populateClientDetails(selection, $(this).prop('$element'));
			}
		})
	}
	/* end: Initialize clients typeahead */

	function isSalesProcessEvent(){
		if(!$.isEmptyObject(eventObj) && getBookingType() == 'service' && eventObj.find('input[name="salesProcessEvent"]').val() != 'null')
			return true;
		return false;
	}

	/* start: Check if calendar subview is in Sales Process Mode */
	function isSalesProcessMode(){
		if(typeof parent.salesProcessStepNumb != 'undefined' && parent.salesProcessStepNumb)
			return true;
		return false;
	}
	/* end: Check if calendar subview is in Sales Process Mode */

	/* start: Check if calendar subview is in client rescheduling Mode */
	function isRescheduleClientMode(){
		if(typeof parent.rescheduleClientMode != 'undefined' && parent.rescheduleClientMode)
			return true;
		return false;
	}
	/* end: Check if calendar subview is in client rescheduling Mode */

	/* start: Check if calendar subview is in create make up Mode */
	function isCreateMakeUpMode(){
		if(typeof parent.createMakeUpMode != 'undefined' && parent.createMakeUpMode)
			return true;
		return false;
	}
	/* end: Check if calendar subview is in create make up Mode */

	/* start: Leave attendace tab if not eligible to be displayed */
	function leaveAttendanceTab(){
		if(classModal.find('.tab-pane.active').attr('id') == 'classAttendance' && !linkedclientsCount(true))
			switchTabTo('classDetails', classModal);
	}
	/* end: Leave attendace tab if not eligible to be displayed */

	/* start: Toggle if make up session checkbox */
	function toggleIf_make_up_sessionOption(action){
		var ifMakeUpSessionField = classModal.find('.client-form input[name="isCreatingMakeUpSession"]'),
		ifMakeUpSessionGroup = ifMakeUpSessionField.closest('.form-group');

		if(action == 'show')
			ifMakeUpSessionGroup.show();
		else{
			ifMakeUpSessionGroup.hide();
			ifMakeUpSessionField.prop('checked', false);
		}
	}
	/* end: Toggle if make up session checkbox */

	/* start: Switch to tab that has error field */
	function switchToErrorTab(form){
		var errorField = form.find('.has-error:first');
		if(errorField.length){
			var tabId = errorField.closest('.tab-pane').attr('id');

			form.find('.nav-tabs a[href="#'+tabId+'"]').tab('show')
		}
	}
	/* end: Switch to tab that has error field */

	/* start: Fetch clients for typeahead */
	function fetchClientsForTypeahead(mode){
		toggleWaitShield('show');
		$.get(public_url+'clients/all?calendar=true', function(data){
			toggleWaitShield('hide');
			initTypeahead(data, mode)
		},'json');
	}
	/* end: Fetch clients for typeahead */

	/* start: Toggle between field and entity deleted message */
	function toggleField_DelMsg(data){
		var delMsgPar = data.field.closest('.delMsgPar')
		msgElem = delMsgPar.children('p');

		if(data.kase == 'trashed' && !msgElem.length){
			data.field.selectpicker('hide');
			delMsgPar.append('<p class="text-danger">'+data.entity+' has been deleted</p>');
		}
		else if(data.kase == 'exist' && msgElem.length){
			data.field.selectpicker('show');
			msgElem.remove()
		}
	}
	/* end: Toggle between field and entity deleted message */

	/* start: Populate field such as duration, capacity etc as per saved in DB */
	function populateClassRelatedFields(){
		var values = {duration:eventObj.find('input[name="duration"]').val(), capacity:eventObj.find('input[name="capacity"]').val(), price:eventObj.find('input[name="price"]').val()};

		classModal.find('select[name="classDur"]').val(values.duration).selectpicker('refresh');
		classModal.find('input[name="classCap"]').val(values.capacity);
		updateBookedclientQuickInfo();
		toggleEventOverBookAlert();
		classModal.find('input[name="classPrice"]').val(values.price);
	}
	/* end: Populate field such as duration, capacity etc as per saved in DB */

	/* start: Populate service related fields such as duration, price etc */
	function populateServiceRelatedFields2(values){
		if(typeof values == 'undefined')
			var values = {duration:eventObj.find('input[name="duration"]').val(), price:eventObj.find('input[name="price"]').val()};

		classModal.find('select[name="serviceDur"]').val(values.duration).selectpicker('refresh');
		classModal.find('input[name="servicePrice"]').val(values.price);

	}
	/* end: Populate service related fields such as duration, price etc */

	/* start: mark field as deleted or active */
	function toggleDeletedMarkOnField(elem, isDeleted){
		elem.data('is-deleted', isDeleted)
	}
	/* end: mark field as deleted or active */

	/* start: check if field is marked as deleted */
	function isFieldMarkedAsDeleted(elem){
		return elem.data('is-deleted')
	}
	/* end: check if field is marked as deleted */

	/* start: mark field as available or vice-versa */
	function toggleAvailableMarkOnField(elem, isAvail){
		elem.data('is-avail', isAvail)
	}
	/* end: mark field as available or vice-versa */

	/* start: check if field is marked as available */
	function isFieldMarkedAsAvail(elem){
		return elem.data('is-avail')
	}
	/* end: check if field is marked as available */

	/* start: Get booking type i.e. class or service */
	function getBookingType(){
		return classModal.find('input[name="bookType"]').val()
	}
	/* end: Get booking type i.e. class or service */

	/* start: Set booking type to class or service and make changes ovwer modal as per it */
	function setBookingType(setType){
		if(typeof setType != 'undefined'){
			var selectableOpts = $("#bookTypeSelectable li");
			selectableOpts.removeClass("ui-selected").show();
			selectableOpts.filter('[data-val="'+setType+'"]')[0].className += " ui-selected";
		}

		var bookType = $('#bookTypeSelectable li.ui-selected').data('val')
		classModal.find('input[name="bookType"]').val(bookType)

	//console.log(classModal.find('input[name="bookType"]').val(bookType))

	if(bookType == 'class'){
		$('#classFieldset').show()
		.find(':input').attr('required', true);
		$('#classClientsBtns').show()	
		$('.bookingTypeName').text('class')
		classModal.find('div#classAttendance > p').show()
		classModal.find("input[name='ifMarkFav']").closest('.form-group').show();

		//$('#serviceFieldset').show();
		$('#serviceFieldset').hide()
		.find(':input[required]').attr('required', false);
	}
	else if(bookType == 'service'){
		$('#classFieldset').hide()
		.find(':input[required]').attr('required', false);
		$('#classClientsBtns').hide()		
		$('#confirmClient').hide();			
		classModal.find("input[name='ifMarkFav']").closest('.form-group').hide();		   

		var serviceFieldset = $('#serviceFieldset');
		serviceFieldset.show();
		serviceFieldset.find('select[name="staffservice"]').attr('required', true)
		serviceFieldset.find('select[name="serviceDur"]').attr('required', true)
		serviceFieldset.find('input[name="servicePrice"]').attr('required', true)
		$('.bookingTypeName').text('Service')
		classModal.find('div#classAttendance > p').hide()

		if(serviceFieldset.find('input[name="appointStatusOpt"]').val() == 'Pencilled-In' && serviceFieldset.find('input[name="ifAutoExpireAppoint"]').is(":checked")){
			var autoExpireAppointDur = serviceFieldset.find('select[name="autoExpireAppointDur"]');
			autoExpireAppointDur.attr('required', true)
			if(autoExpireAppointDur.val() == 'Custom')
				serviceFieldset.find('.autoExpireAppointDurCustom input').prop('required', true)
		}
	}
}
/* start: Set booking type to class or service and make changes ovwer modal as per it */


/* start: Get staffs list according to services selected */
function getStaffsBasedOnServices(){	
	if(isUserType(['Admin'])){
		/*var servicesRow = appointModal.find('.services .row.m-t-5'),
			selectedServices = [],
			lastServiceTime = '',
			lastDuration = 0;
		$.each(servicesRow, function(){
			var $this = $(this),
				serviceName = $this.find('select.serviceName').val(),
				serviceTime = $this.find('input.timepicker1').val(),
				serviceDur = $this.find('select.serviceDur').val();

			if(serviceName && serviceTime && serviceDur){
				selectedServices.push(serviceName);
				lastServiceTime = serviceTime;
				lastDuration = serviceDur;
			}
		});

		if(selectedServices.length){*/
			var data = getServiceDataForAjax();
			if(Object.keys(data).length){
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

				toggleWaitShield('show');
				$.get(public_url+'settings/business/services/free-staffs', formData, function(data){	
					toggleWaitShield('hide');
					var selectedStaffName = '',
					options = '',
					ids = [];
					if(!$.isEmptyObject(data)){
						$.each(data, function(key, value){
							options += '<option value="'+key+'">'+value+'</option>';
							ids.push(key);
						});
					}

					var selectedStaff = 0;
				/*if(defaultStaff){
					//edit case
					if($.inArray(defaultStaff, ids) < 0){
						//staff list does not have selected staff
						staffSetGroupDispSpan.addClass('text-danger');
						toggleAvailableMarkOnField(modalStaffDd, 0);

						var staffName = eventObj.find('input[name="staffName"]').val()
						if(eventObj.find('input[name="isStaffDeleted"]').val() == 'true'){
							//Staff has been deleted
							selectedStaffName = staffName+' has been deleted';
						}
						else
							selectedStaffName = staffName+' is not available';
					}
					else
						selectedStaff = defaultStaff;
				}
				else{*/
					//add case
					var staffId = findStaffId();
					if(staffId && staffId != 'all' && staffId != 'all-ros' && $.inArray(staffId, ids) >= 0){
						//filter has staff selected and staff list have filter selected staff
						selectedStaff = staffId;
					}
				//}

				modalStaffDd.html(options);
				if(selectedStaff)
					modalStaffDd.val(selectedStaff);
				if(!selectedStaffName)
					selectedStaffName = modalStaffDd.find('option:selected').text();
				if(!selectedStaffName){
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
	function removeServiceRow(removeBtn){
		if(typeof removeBtn == 'undefined')
			removeBtn = appointModal.find('.removeService');
		removeBtn.each(function(){
			var $this = $(this);
			if($this.closest('.form-group').children('.row.m-t-5').length - 1 == 1)
				toggleRecurrTab('show');

			$this.closest('.row').remove();
		})
	}
	/* end: Remove service rows */
	/* start: Get areas list according to services selected */
	function getAreasBasedOnServices(){	
	//alert('function')
	if(isUserType(['Admin'])){
		/*var servicesRow = appointModal.find('.services .row.m-t-5'),
			selectedServices = [],
			lastServiceTime = '',
			lastDuration = 0;
		$.each(servicesRow, function(){
			var $this = $(this),
				serviceName = $this.find('select.serviceName').val(),
				serviceTime = $this.find('input.timepicker1').val(),
				serviceDur = $this.find('select.serviceDur').val();

			if(serviceName && serviceTime && serviceDur){
				selectedServices.push(serviceName);
				lastServiceTime = serviceTime;
				lastDuration = serviceDur;
			}
		});

		if(selectedServices.length){*/
			var data = getServiceDataForAjax();
			if(Object.keys(data).length){
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

				toggleWaitShield('show');
				$.get(public_url+'settings/business/services/free-areas', formData, function(data){	
					toggleWaitShield('hide');
					var selectedAreaName = '',
					options = '',
					ids = [];
					if(!$.isEmptyObject(data)){
						$.each(data, function(key, value){
							options += '<option value="'+key+'">'+value+'</option>';
							ids.push(key);
						});
					}

					var selectedArea = 0;
				/*if(defaultStaff){
					//edit case
					if($.inArray(defaultStaff, ids) < 0){
						//staff list does not have selected staff
						staffSetGroupDispSpan.addClass('text-danger');
						toggleAvailableMarkOnField(modalStaffDd, 0);

						var staffName = eventObj.find('input[name="staffName"]').val()
						if(eventObj.find('input[name="isStaffDeleted"]').val() == 'true'){
							//Staff has been deleted
							selectedStaffName = staffName+' has been deleted';
						}
						else
							selectedStaffName = staffName+' is not available';
					}
					else
						selectedStaff = defaultStaff;
				}
				else{*/
					//add case
					var areaId = findAreaId();
					if(areaId && areaId != 'all' && areaId != 'all-ros' && $.inArray(areaId, ids) >= 0){
						//filter has area selected and area list have filter selected area
						selectedArea = areaId;
					}
				//}

				areaDd.html(options);
				if(selectedArea)
					areaDd.val(selectedArea);
				if(!selectedAreaName)
					selectedAreaName = areaDd.find('option:selected').text();
				if(!selectedAreaName){
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
	function getServiceDataForAjax(){
		var selectedServices = [],
		lastServiceTime = '',
		lastDuration = 0;
		appointModal.find('.services .row.m-t-5').each(function(){
			var $this = $(this),
			serviceName = $this.find('select.serviceName').val(),
			serviceTime = $this.find('input.timepicker1').val(),
			serviceDur = $this.find('select.serviceDur').val();

			if(serviceName && serviceTime && serviceDur){
				selectedServices.push(serviceName);
				lastServiceTime = serviceTime;
				lastDuration = serviceDur;
			}
		});

		if(selectedServices.length)
			return {selectedServices:selectedServices, lastServiceTime:lastServiceTime, lastDuration:lastDuration};
		else
			return {};
	}
	function dpSrc(src, gend){
		if(typeof src != 'undefined' && src)
			return public_url+'uploads/thumb_'+src;
		else if(typeof gend != 'undefined' && gend)
			return public_url+'profiles/'+toLowerCase(gend)+'.gif';
		else
			return public_url+'profiles/noimage.gif';
	}
	function activateAttendanceRadio(attendance, attendanceRow){
		if(typeof attendanceRow == 'undefined')
			attendanceRow = classModal.find('div#classAttendanceList');

		var inputs = attendanceRow.find('input'),
		attendanceInput = inputs.filter('input[value="'+attendance+'"]');

		attendanceRow.find('label').removeClass('active');
		inputs.attr('checked', false);
		attendanceInput.attr('checked', true).parent().addClass('active');
		return attendanceInput;
	}
/*
**TEMP
*/
function getStaffsHavingArea(defaultStaff){	
	return;
	var modalStaffDd = classModal.find('select[name="staff"]'),
	staffSetGroupDisp = modalStaffDd.closest('.set-group').find('.set-group-disp'),
	staffSetGroupDispSpan = staffSetGroupDisp.find('span'),
	formData = {};

	if(typeof defaultStaff == 'undefined')
		var defaultClass = false;
	else
		var defaultClass = true;

	var eventDate = setEventDate(classModal, 'submit');
	formData['date'] = eventDate.date;
	formData['day'] = eventDate.day;
	formData['classTime'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());

	/*var duration = classModal.find('select[name="classDur"]').val();
	if(duration)
		formData['duration'] = duration;
	else if(eventObj.length)
		formData['duration'] = eventObj.find('input[name="duration"]').val();;*/
	
	if(eventObj.length){
		formData['eventId'] = eventObj.find('input[name="eventId"]').val();

		if(notAvailAjax){
			var availAreas = classModal.find('select[name="modalLocArea"] option[data-is-avail!="0"]:selected');
			if(availAreas.length){
				formData['availAreas'] = []
				availAreas.each(function(){
					formData['availAreas'].push($(this).val())
				})
			}

			var classDd = classModal.find('select[name="staffClass"]');
			if(isFieldMarkedAsAvail(classDd))
				formData['classId'] = classDd.val()
		}
	}

	staffSetGroupDispSpan.removeClass('text-danger');
	//modalStaffDd.data('is-avail', 1)	
	toggleAvailableMarkOnField(modalStaffDd, 1);
	setFieldNeutral(modalStaffDd);
	toggleDeletedMarkOnField(modalStaffDd, 0)

	if(isUserType(['Staff'])){
		if(eventObj.length)
			formData['duration'] = eventObj.find('input[name="duration"]').val();
		
		toggleWaitShield('show');
		$.get(public_url+'settings/business/staffs/'+loggedInUser.id+'/is-busy', formData, function(data){
			toggleWaitShield('hide');
			if(data == 'true'){
				//Staff is busy
				staffSetGroupDispSpan.addClass('text-danger');
				//modalStaffDd.data('is-avail', 0);
				toggleAvailableMarkOnField(modalStaffDd, 0);
				staffSetGroupDispSpan.text('You are not available');
			}
			else
				staffSetGroupDispSpan.text(loggedInUser.name);
			
			modalStaffDd.html('<option value="'+loggedInUser.id+'" selected>'+loggedInUser.name+'</option>').selectpicker('refresh');
			getAreasByStaff(modalStaffDd, defaultClass);
			staffSetGroupDisp.find('a').hide();
		},'json');	
	}
	else if(isUserType(['Admin'])){
		toggleWaitShield('show');
		$.get(public_url+'settings/business/staffs/free-and-having-area', formData, function(data){	
			toggleWaitShield('hide');
			var options = '',
			ids = [];
			if(!$.isEmptyObject(data)){
				$.each(data, function(key, value){
					options += '<option value="'+key+'">'+value+'</option>';
					ids.push(key);
				});
			}

			var selectedStaffName = '',
			selectedStaff = 0;
			if(typeof defaultStaff != 'undefined'){
				//edit case
				if($.inArray(defaultStaff, ids) < 0){
					//staff list does not have selected staff
					staffSetGroupDispSpan.addClass('text-danger');
					//modalStaffDd.data('is-avail', 0);
					toggleAvailableMarkOnField(modalStaffDd, 0);

					var staffName = eventObj.find('input[name="staffName"]').val()
					if(eventObj.find('input[name="isStaffDeleted"]').val() == 'true'){
						//Staff has been deleted
						selectedStaffName = staffName+' has been deleted';
					}
					else
						selectedStaffName = staffName+' is not available';
					
					options = '<option value="'+defaultStaff+'">'+staffName+'</option>';
				}
				selectedStaff = defaultStaff;
			}
			else{
				//add case
				var staffId = findStaffId();
				if(staffId && staffId != 'all' && staffId != 'all-ros' && $.inArray(staffId, ids) >= 0){
					//filter has staff selected and staff list have filter selected staff
					selectedStaff = staffId;
				}
			}

			modalStaffDd.html(options);
			if(selectedStaff)
				modalStaffDd.val(selectedStaff);
			if(!selectedStaffName)
				selectedStaffName = modalStaffDd.find('option:selected').text();
			if(!selectedStaffName){
				selectedStaffName = 'No staff found'
				staffSetGroupDispSpan.addClass('text-danger');
			}
			staffSetGroupDispSpan.text(selectedStaffName);

			modalStaffDd.selectpicker('refresh');
			if(!notAvailAjax)
				getAreasByStaff(modalStaffDd, defaultClass);
			notAvailAjax = false;
		},'json');
	}
}
function getAreasByStaff(staffDd, defaultClass){
	return;
	var areaDd = classModal.find('select[name="modalLocArea"]'),
	areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp'),
	formData = {};

	if(typeof defaultClass == 'undefined')
		defaultClass = false

	var eventDate = setEventDate(classModal, 'submit');
	formData['date'] = eventDate.date;
	formData['day'] = eventDate.day;
	formData['classTime'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
	
	if(eventObj.length){
		formData['eventId'] = eventObj.find('input[name="eventId"]').val();

		if(notAvailAjax){
			if(isFieldMarkedAsAvail(staffDd) && staffDd.val())
				formData['staffId'] = staffDd.val();

			var classDd = classModal.find('select[name="staffClass"]');
			if(isFieldMarkedAsAvail(classDd))
				formData['classId'] = classDd.val()
		}
	}

	areaSetGroupDisp.find('span').remove()
	areaSetGroupDisp.find('br').remove()
	setFieldNeutral(areaDd);
	toggleDeletedMarkOnField(areaDd, 0)
	toggleWaitShield('show');
	$.get(public_url+'staff/'+staffDd.val()+'/location-areas', formData, function(data){
		toggleWaitShield('hide');
		var options = '',
		ids = [];
		if(!$.isEmptyObject(data)){
			$.each(data, function(key, value){
				var areaId = value.areaId,
				name = value.locName+' - '+value.areaName;

				options += '<option value="'+areaId+'">'+name+'</option>';
				ids.push(areaId);
			});
		}

		var span = '',
			selectedAreas = []/*,
			selectedUnavailAreas = [],
			selectedDeletedAreas = []*/;
			if(defaultClass){
			//edit case
			var temp = '',
			flag = false;
			eventObj.find("input[name='area[]']").each(function(){
				var data = JSON.parse($(this).val()),
				isAvail = 1,
				isDel = 0;
				if($.inArray(data.id, ids) < 0){
					//area list does not have selected area
					isAvail = 0;
					if(data.isAreaDeleted == true){
						//Area has been deleted
						isDel = 1;
						//selectedDeletedAreas.push(data.id)
					}
					flag = true;
					/*else
					selectedUnavailAreas.push(data.id)*/
				}
				
				temp += '<option value="'+data.id+'" data-is-avail="'+isAvail+'" data-is-del="'+isDel+'">'+data.locAreaName+'</option>'
				selectedAreas.push(data.id)
			});
			//if(selectedUnavailAreas.length || selectedDeletedAreas.length)
			if(flag)
				options = temp;
		}
		else{
			//add case
			var areaId = findAreaId();
			if(areaId && areaId != 'all' && $.inArray(areaId, ids) >= 0){
				//filter has area selected and area list have filter selected area
				selectedAreas.push(areaId)
			}
		}

		areaDd.html(options);
		if(!selectedAreas.length && options)
			selectedAreas.push(areaDd.find('option:first').val())
		areaDd.val(selectedAreas);

		areaDd.find('option:selected').each(function(){
			var $this = $(this)/*,
			val = parseInt($this.attr('value'), 10)*/;

			/*if(selectedDeletedAreas.length && $.inArray(val, selectedDeletedAreas) >= 0)
				span += '<span class="text-danger">'+$this.text()+' has been deleted</span><br>';
			else if(selectedUnavailAreas.length && $.inArray(val, selectedUnavailAreas) >= 0)
				span += '<span class="text-danger">'+$this.text()+' is not available</span><br>';*/
			if($this.data('is-del') == '1')
				span += '<span class="text-danger">'+$this.text()+' has been deleted</span><br>';
			else if($this.data('is-avail') == '0')
				span += '<span class="text-danger">'+$this.text()+' is not available</span><br>';
			else
				span += '<span>'+$this.text()+'</span><br>';
		});
		if(span)
			span = span.slice(0,-4)
		else
			span = '<span class="text-danger">No area found</span>';

		areaSetGroupDisp.prepend(span)

		areaDd.selectpicker('refresh');
		if(!notAvailAjax)
			getStaffClasses(staffDd, defaultClass);
		notAvailAjax = false;
	},'json');
}
/* start: Populate classes based on the staffs */
function getStaffClasses(staffDd, defaultClass){
	return;
	var classDd = classModal.find('select[name="staffClass"]'),
		//delMsgPar = classDd.closest('.form-group'),
		errorCont = classDd.closest('.form-group').children('.text-danger'),
		formData = {},
		options = '';

		if(typeof defaultClass != 'undefined' && defaultClass)
			defaultClass = parseInt(eventObj.find('input[name="classId"]').val(), 10);
		else
			defaultClass = 0;

		formData['staffId'] = staffDd.val();
		formData['areaId'] = classModal.find('select[name="modalLocArea"]').val();

	//classDd.selectpicker('show');
	//delMsgPar.children('span.text-danger').remove()
	errorCont.html('')
	toggleAvailableMarkOnField(classDd, 1);
	toggleWaitShield('show');
	$.get(public_url+'area-staff/classes', formData, function(data){
		toggleWaitShield('hide');
		var ids = [];

		if(!$.isEmptyObject(data)){
			$.each(data, function(){
				options += '<option value="'+this.id+'" data-duration="'+this.duration+'" data-capacity="'+this.capacity+'" data-price="'+this.price+'">'+this.name+'</option>';
				ids.push(this.id);
			});
		}

		var selectedClass = 0;

		if(defaultClass){
			//edit case
			if($.inArray(defaultClass, ids) < 0){
				//class list does not have selected class
				var className = eventObj.find('input[name="className"]').val();

				if(eventObj.find('input[name="isClassDeleted"]').val() == 'true'){
					//Class has been deleted
					toggleAvailableMarkOnField(classDd, 0);
					//delMsgPar.append('<span class="text-danger">'+className+' has been deleted</span>');
					errorCont.html(className+' has been deleted')
					//classDd.selectpicker('hide');
				}
				else if(isFieldMarkedAsAvail(staffDd) && !isAreaMarkedAsUnavail()){
					toggleAvailableMarkOnField(classDd, 0);
					//delMsgPar.append('<span class="text-danger">'+className+' is not available</span>');
					errorCont.html(className+' is not available')
					//classDd.selectpicker('hide');
				}
				else
					options = '<option value="'+defaultClass+'" data-duration="'+eventObj.find('input[name="duration"]').val()+'" data-capacity="'+eventObj.find('input[name="capacity"]').val()+'" data-price="'+eventObj.find('input[name="price"]').val()+'">'+className+'</option>';
			}
			populateClassRelatedFields();
			selectedClass = defaultClass;
		}
		else{
			//add case
		}

		classDd.html('<option value="">-- Select --</option>'+options);
		if(selectedClass)
			classDd.val(selectedClass);
		classDd.selectpicker('refresh');
	},'json');



	/*if(eventObj.find('input[name="isClassDeleted"]').val() == 'true' || !isFieldMarkedAsAvail(staffDd) || isAreaMarkedAsUnavail()){

		options += '<option value="'+defaultClass+'" selected>'+eventObj.find('input[name="className"]').val()+'</option>';
		//classDd.data('is-avail', 0).html(options).selectpicker('refresh');
		toggleAvailableMarkOnField(classDd, 0);
		classDd.html(options).selectpicker('refresh');
		populateClassRelatedFields();

		if(eventObj.find('input[name="isClassDeleted"]').val() == 'true')
			toggleField_DelMsg({kase:'trashed', field:classDd, entity:'class'});
		else
			toggleField_DelMsg({kase:'exist', field:classDd});
	}
	else{
		
			
				
			classDd.html(options);

			if(defaultClass){
				//edit case
				if($.inArray(defaultClass, ids) < 0){
					//class list does not have selected class
					toggleField_DelMsg({kase:'trashed', field:classDd, entity:'class'});
					toggleAvailableMarkOnField(classDd, 0);
					options = '<option value="'+defaultClass+'">'+eventObj.find('input[name="className"]').val()+'</option>';
				}
				classDd.val(defaultClass);
				populateClassRelatedFields();
			}
			else
				classDd.trigger('change');

			toggleField_DelMsg({kase:'exist', field:classDd});
			classDd.selectpicker('refresh');
		},'json');
	}
	/*else{
		var areaDd = classModal.find('select[name="modalLocArea"]');
		
		if(isClassFetchingInEditMode(defaultClass) && (eventObj.find('input[name="isClassDeleted"]').val() == 'true') || isFieldMarkedAsDeleted(staffDd) || isFieldMarkedAsDeleted(areaDd)){
			var dd = classModal.find('select[name="staffClass"]'),
				classId = eventObj.find('input[name="classId"]').val();

			options += '<option value="'+classId+'">'+eventObj.find('input[name="className"]').val()+'</option>';
			dd.html(options);
			dd.val(classId).selectpicker('refresh');

			if(eventObj.find('input[name="isClassDeleted"]').val() == 'true')
				toggleField_DelMsg({kase:'trashed', field:dd, entity:'class'});
			else //if(isFieldMarkedAsDeleted(staffDd))
				toggleField_DelMsg({kase:'exist', field:dd});

			dd.data('new-instance', 'false')
			populateClassRelatedFields();
		}
		else{
			var formData = {};

			formData['staffId'] = staffDd.val();
			/*var //areaDd = classModal.find('select[name="modalLocArea"]'),
				isAreaMarkedAsDeleted = isFieldMarkedAsDeleted(areaDd);
			if(isAreaMarkedAsDeleted)
				formData['areaId'] = isAreaMarkedAsDeleted; 
			else*/
				/*formData['areaId'] = areaDd.val();
			//formData['areaId'] = staffDd.closest('form').find('select[name="modalLocArea"]').val();

			$.get(public_url+'area-staff/classes', formData, function(data){
				var //areaDd = classModal.find('select[name="modalLocArea"]'),
					dd = classModal.find('select[name="staffClass"]'),
					selectedClassObj = {},
					newClasses = [],
					isCompatible = true/*,
					options = '<option value="" selected>-- Select --</option>'*/;

				/*if(dd.data('new-instance') != 'true')
					var selectedClass = parseInt(dd.val(), 10);
				else
					var selectedClass = 0;

				dd.data('new-instance', 'false')

				/* REMOVE THIS LINE */
				/*selectedClass = 0;
				/* REMOVE THIS LINE */
				
				/*if(selectedClass)
					selectedClassObj = {name:dd.find('option:selected').text(), capacity:classModal.find('input[name="classCap"]').val(), duration:classModal.find('select[name="classDur"]').val(), price:classModal.find('input[name="classPrice"]').val()};

				if(data.length){
					$.each(data, function(val, text){
						newClasses.push(this.id);
						options += '<option value="'+this.id+'" data-duration="'+this.duration+'" data-capacity="'+this.capacity+'" data-price="'+this.price+'">'+this.name+'</option>';
					});
				}

				if(selectedClass){
					if($.inArray(selectedClass, newClasses) == -1) 
						isCompatible = false;
					else
						isCompatible = true;

					if(!isCompatible){
						classText = '<li class="list-group-item">'+selectedClassObj.name+'</li>'

						swal({
					        title: "This staff doesn't provide following classes:",
					        text: classText,
					        type: staffChangeSwalOpt.type,
					        showCancelButton: staffChangeSwalOpt.showCancelButton,
					        confirmButtonColor: staffChangeSwalOpt.confirmButtonColor,
					        confirmButtonText: staffChangeSwalOpt.confirmButtonText,
					        cancelButtonText: staffChangeSwalOpt.cancelButtonText,
					        allowEscapeKey: staffChangeSwalOpt.allowEscapeKey,
					        html: staffChangeSwalOpt.html,
					        customClass: staffChangeSwalOpt.customClass
					    }, 
					    function(isConfirm){
					    	if(!isConfirm){
					    		if(!$.isEmptyObject(staffDdTempOpts)){
					    			areaDd.val(areaDd.data('last-val')).selectpicker('refresh');	
					    			staffDd.html(staffDdTempOpts.html).val(staffDdTempOpts.lastSelectedId);

					    			staffDd.closest('.set-group').find('.set-group-disp span').text(staffDdTempOpts.lastSelectedText).removeClass('text-danger');
									staffDd.selectpicker('refresh');
					    			staffDdTempOpts = {};
					    		}
					    		
					    		staffDd.val(staffDd.data('last-val')).selectpicker('refresh');
					    	}
					    	else{
					    		areaDd.data('last-val', areaDd.val())
					    		staffDd.data('last-val', staffDd.val())
								dd.html(options).trigger('change');

								toggleField_DelMsg({kase:'exist', field:dd});
								dd.selectpicker('refresh');	
					    	}
					    });
					}
					else{
						areaDd.data('last-val', areaDd.val())
						staffDd.data('last-val', staffDd.val())
						dd.html(options).trigger('change');

						dd.val(selectedClass);
						populateClassRelatedFields(selectedClassObj);

						toggleField_DelMsg({kase:'exist', field:dd});
						dd.selectpicker('refresh');	
					}
				}
				else{
					areaDd.data('last-val', areaDd.val())
					staffDd.data('last-val', staffDd.val())
					dd.html(options);

					if(isClassFetchingInEditMode(defaultClass)){
						dd.val(eventObj.find('input[name="classId"]').val());
						populateClassRelatedFields();
					}
					else
						dd.trigger('change');

					toggleField_DelMsg({kase:'exist', field:dd});
					dd.selectpicker('refresh');
				}

				/*var dd = $('select[name="staffClass"]'),
				options = '<option value="">-- Select --</option>';*/

				/*if(data.length){
					$.each(data, function(val, text){
						options += '<option value="'+this.id+'" data-duration="'+this.duration+'" data-capacity="'+this.capacity+'" data-price="'+this.price+'">'+this.name+'</option>';
					});
				}*/
				/*dd.html(options);

				if(isClassFetchingInEditMode(defaultClass)/*defaultClass != null && defaultClass && eventObj.length && eventObj.find('input[name="type"]').val() == 'class'*//*){
					//classModal.find('select[name="staffClass"]').val(eventObj.find('input[name="classId"]').val());
					dd.val(eventObj.find('input[name="classId"]').val());
					/*var duration = classModal.find('select[name="classDur"]');
					duration.val(eventObj.find('input[name="duration"]').val());
					duration.selectpicker('refresh');*/
					/*populateClassRelatedFields();
					/*classModal.find('select[name="classDur"]').val(eventObj.find('input[name="duration"]').val()).selectpicker('refresh');
					classModal.find('input[name="classCap"]').val(eventObj.find('input[name="capacity"]').val());
					updateBookedclientQuickInfo();
					toggleEventOverBookAlert();
					classModal.find('input[name="classPrice"]').val(eventObj.find('input[name="price"]').val());*/
				/*}
				else
					dd.trigger('change');

				//toggleField_DelMsg('exist', dd);
				toggleField_DelMsg({kase:'exist', field:dd});
				dd.selectpicker('refresh');*/
				//classModal.find('select[name="staffClass"]').selectpicker('show').selectpicker('refresh');
		   /*},'json');
		}
	}
	/*else{
		var dd = classModal.find('select[name="staffClass"]')

		dd.html(options);
		dd.trigger('change');
		toggleField_DelMsg({kase:'exist', field:dd});
		dd.selectpicker('refresh');
	}*/
}
/* end: Populate classes based on the staffs */
function ifSendNotAvailAjax(){
	return;
	return (isUserType(['Admin']) && (!isFieldMarkedAsAvail(classModal.find('select[name="staff"]')) || !isFieldMarkedAsAvail(classModal.find('select[name="staffClass"]')) || isAreaMarkedAsUnavail()))
}
/* start: check if area field is marked as unavailable */
function isAreaMarkedAsUnavail(){
	return;
	return (classModal.find('select[name="modalLocArea"] option[data-is-avail="0"]').length)
}
/* end: check if area field is marked as unavailable */

/* start: Get staffs list according to class selected */
function getStaffsBasedOnClass(){
	return;	
	if(isUserType(['Admin'])){
		var classDd = classModal.find('select[name="staffClass"]'),
		modalStaffDd = classModal.find('select[name="staff"]'),
			//selectedStaffName = '',
			/*staffSetGroupDisp = modalStaffDd.closest('.set-group').find('.set-group-disp'),
			staffSetGroupDispSpan = staffSetGroupDisp.find('span'),*/
			staffSetGroupDispSpan = modalStaffDd.closest('.set-group').find('.set-group-disp span')/*,
			options = ''*/;

			if(!isFieldMarkedAsAvail(classDd)){
			//Class has been deleted
			//modalStaffDd.html(options).selectpicker('refresh');
			modalStaffDd.html('').selectpicker('refresh');

			var selectedStaffName = eventObj.find('input[name="staffName"]').val()
			if(eventObj.find('input[name="isStaffDeleted"]').val() == 'true'){
				//Staff has been deleted
				selectedStaffName += ' has been deleted';
			}

			staffSetGroupDispSpan.html(selectedStaffName).addClass('text-danger');
		}
		else{
			staffSetGroupDispSpan.removeClass('text-danger');
			toggleAvailableMarkOnField(modalStaffDd, 1);
			setFieldNeutral(modalStaffDd);

			var selectedClass = classDd.val(),
			duration = classModal.find('select[name="classDur"]').val();
			if(selectedClass && duration){
				var formData = {},
				eventDate = setEventDate(classModal, 'submit');
				formData['date'] = eventDate.date;
				formData['day'] = eventDate.day;
				formData['classTime'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
				formData['duration'] = duration;
				if(eventObj.length)/*{
					var defaultStaff = eventObj.find('input[name="staffId"]').val();*/
					formData['eventId'] = eventObj.find('input[name="eventId"]').val();
				/*}
				else
					var defaultStaff = 0;*/
				toggleWaitShield('show');
				$.get(public_url+'settings/business/classes/'+selectedClass+'/free-staffs', formData, function(data){	
					toggleWaitShield('hide');
					populateStaffs(data)
					/*var ids = [];
					if(!$.isEmptyObject(data)){
						$.each(data, function(key, value){
							options += '<option value="'+key+'">'+value+'</option>';
							ids.push(key);
						});
					}
					
					var selectedStaff = 0;
					if(defaultStaff){
						//edit case
						if($.inArray(defaultStaff, ids) < 0){
							//staff list does not have selected staff
							staffSetGroupDispSpan.addClass('text-danger');
							toggleAvailableMarkOnField(modalStaffDd, 0);

							var staffName = eventObj.find('input[name="staffName"]').val()
							if(eventObj.find('input[name="isStaffDeleted"]').val() == 'true'){
								//Staff has been deleted
								selectedStaffName = staffName+' has been deleted';
							}
							else
								selectedStaffName = staffName+' is not available';
							
							//options = '<option value="'+defaultStaff+'">'+staffName+'</option>';
						}
						else
							selectedStaff = defaultStaff;
					}
					else{
						//add case
						var staffId = findStaffId();
						if(staffId && staffId != 'all' && staffId != 'all-ros' && $.inArray(staffId, ids) >= 0){
							//filter has staff selected and staff list have filter selected staff
							selectedStaff = staffId;
						}
					}

					modalStaffDd.html(options);
					if(selectedStaff)
						modalStaffDd.val(selectedStaff);
					if(!selectedStaffName)
						selectedStaffName = modalStaffDd.find('option:selected').text();
					if(!selectedStaffName){
						selectedStaffName = 'No staff found'
						staffSetGroupDispSpan.addClass('text-danger');
					}
					staffSetGroupDispSpan.text(selectedStaffName);

					modalStaffDd.selectpicker('refresh');*/
				},'json');
			}
			else
				populateStaffs()
		}
	}
}
/* end: Get staffs list according to class selected */

/* start: Get areas list according to class selected */
function getAreasBasedOnClass(){
	return;
	
	if(isUserType(['Admin'])){
		var classDd = classModal.find('select[name="staffClass"]'),
		areaDd = classModal.find('select[name="modalLocArea"]'),
			areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp')/*,
			options = '',
			span = ''*/;

			if(!isFieldMarkedAsAvail(classDd)){
			//Class has been deleted
			//areaDd.html(options).selectpicker('refresh');
			areaDd.html('').selectpicker('refresh');

			var span = '';
			eventObj.find("input[name='area[]']").each(function(){
				var data = JSON.parse($(this).val());
				if(data.isAreaDeleted == true){
					//Area has been deleted
					span += '<span class="text-danger">'+data.locAreaName+' has been deleted</span><br>';
				}
				else
					span += '<span>'+data.locAreaName+'</span><br>';
			});
			span = span.slice(0,-4)
			areaSetGroupDisp.find('span').remove()
			areaSetGroupDisp.find('br').remove()
			areaSetGroupDisp.prepend(span)
		}
		else{
			areaSetGroupDisp.find('span').remove()
			areaSetGroupDisp.find('br').remove()
			toggleAvailableMarkOnField(areaDd, 1);
			setFieldNeutral(areaDd);

			var selectedClass = classDd.val(),
			duration = classModal.find('select[name="classDur"]').val();
			if(selectedClass && duration){
				var formData = {},
				eventDate = setEventDate(classModal, 'submit');
				formData['date'] = eventDate.date;
				formData['day'] = eventDate.day;
				formData['classTime'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
				formData['duration'] = duration;
				if(eventObj.length)/*{
					var isEditMode = 1;*/
					formData['eventId'] = eventObj.find('input[name="eventId"]').val();
				/*}
				else
					var isEditMode = 0;*/
				toggleWaitShield('show');
				$.get(public_url+'settings/business/classes/'+selectedClass+'/free-areas', formData, function(data){
					toggleWaitShield('hide');
					populateAreas(data);
					return;


					var ids = [];
					if(!$.isEmptyObject(data)){
						$.each(data, function(key, value){
							options += '<option value="'+key+'">'+value+'</option>';
							ids.push(parseInt(key, 10));
						});
					}

					var selectedAreas = [];
					if(isEditMode){
						//edit case
						/*var temp = '',
						flag = false;*/
						eventObj.find("input[name='area[]']").each(function(){
							var data = JSON.parse($(this).val())/*,
								isAvail = 1,
								isDel = 0*/;
								if($.inArray(data.id, ids) < 0){
								//area list does not have selected area
								/*isAvail = 0;
								if(data.isAreaDeleted == true){
									//Area has been deleted
									isDel = 1;
								}
								flag = true;*/
								toggleAvailableMarkOnField(areaDd, 0);
								if(data.isAreaDeleted == true){
									//Area has been deleted
									span += '<span class="text-danger">'+data.locAreaName+' has been deleted</span><br>';
								}
								else
									span += '<span class="text-danger">'+data.locAreaName+' is not available</span><br>';
							}
							else
							//temp += '<option value="'+data.id+'" data-is-avail="'+isAvail+'" data-is-del="'+isDel+'">'+data.locAreaName+'</option>'
						selectedAreas.push(data.id)
					});
						/*if(flag)
						options = temp;*/
					}
					else{
						//add case
						var areaId = findAreaId();
						if(areaId && areaId != 'all' && $.inArray(areaId, ids) >= 0){
							//filter has area selected and area list have filter selected area
							selectedAreas.push(areaId)
						}
					}

					areaDd.html(options);
					if(!selectedAreas.length && options)
						selectedAreas.push(areaDd.find('option:first').val())
					areaDd.val(selectedAreas);

					areaDd.find('option:selected').each(function(){
						var $this = $(this);

						/*if($this.data('is-del') == '1')
							span += '<span class="text-danger">'+$this.text()+' has been deleted</span><br>';
						else if($this.data('is-avail') == '0')
							span += '<span class="text-danger">'+$this.text()+' is not available</span><br>';
						else*/
							span += '<span>'+$this.text()+'</span><br>';
					});
					if(span)
						span = span.slice(0,-4)
					else
						span = '<span class="text-danger">No area found</span>';

					areaSetGroupDisp.prepend(span)

					areaDd.selectpicker('refresh');
				},'json');
			}
			else
				populateAreas();
		}
	}
}
/* end: Get areas list according to class selected */

/* start: Populate services based on the staffs */
function getStaffServices(staffDd, defaultServices){
	return;
	//if(1==1 /*|| !isFieldMarkedAsDeleted(staffDd)*/){
		var formData = {};

		var isStaffMarkedAsDeleted = isFieldMarkedAsDeleted(staffDd);
		if(isStaffMarkedAsDeleted)
			formData['staffId'] = isStaffMarkedAsDeleted; 
		else
			formData['staffId'] = staffDd.val();

		var areaDd = appointModal.find('select[name="modalLocArea"]'),
		isAreaMarkedAsDeleted = isFieldMarkedAsDeleted(areaDd);
		if(isAreaMarkedAsDeleted)
			formData['areaId'] = isAreaMarkedAsDeleted; 
		else
			formData['areaId'] = areaDd.val();
		//formData['areaId'] = staffDd.closest('form').find('select[name="modalLocArea"]').val();

		toggleWaitShield('show');
		$.get(public_url+'staffs/services', formData, function(data){
			toggleWaitShield('hide');
			var //areaDd = appointModal.find('select[name="modalLocArea"]'),
			servicesDd = appointModal.find('select.serviceName'),
			selectedServices = [],
			selectedServicesObj = {},
			newServices = [],
			incompatibleServices = [],
			compatibleServices = [],
			options = '<option value="" selected>-- Select --</option>';

			if(staffDd.data('services-new-instance') != 'true'){
				servicesDd.each(function(){
					var $this = $(this),
					val = parseInt($this.val(), 10);

					if(val && $this.closest('.row ').find('input[type="hidden"]').val() == 'false' && $.inArray(val, selectedServices) === -1){
						var row = $this.closest('.row');
						
						selectedServicesObj[val] = {name:$this.find('option:selected').text(), /*time:row.find('.datetimepicker input').val()*/time:row.find('input.timepicker1').val(), duration:row.find('select.serviceDur').val(), price:row.find('.servicePrice').val()};
						selectedServices.push(val);
					}
				})
			}
			staffDd.data('services-new-instance', 'false')

			if(data.length){
				$.each(data, function(val, text){
					newServices.push(this.id);
					options += '<option value="'+this.id+'" data-duration="'+this.duration+'" data-price="'+this.price+'">'+this.name+'</option>';
				});
			}

			if(selectedServices.length){
				$.grep(selectedServices, function(el){
					if($.inArray(el, newServices) == -1) 
						incompatibleServices.push(el);
					else
						compatibleServices.push(el);
				});

				if(incompatibleServices.length){
					var servicesText = '';
					$.each(incompatibleServices, function(key, val){
						servicesText += '<li class="list-group-item">'+selectedServicesObj[val].name+'</li>'
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
				    }, 
				    function(isConfirm){
				    	if(!isConfirm){
				    		if(!$.isEmptyObject(staffDdTempOpts)){
				    			areaDd.val(areaDd.data('last-val')).selectpicker('refresh');	
				    			staffDd.html(staffDdTempOpts.html).val(staffDdTempOpts.lastSelectedId);

				    			staffDd.closest('.set-group').find('.set-group-disp span').text(staffDdTempOpts.lastSelectedText).removeClass('text-danger');
				    			staffDd.selectpicker('refresh');
				    			staffDdTempOpts = {};
				    		}
				    		staffDd.val(staffDd.data('last-val')).selectpicker('refresh');
				    	}
				    	else{
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
				}
				else
					populateServiceRelatedFields(staffDd, options, compatibleServices, selectedServicesObj, areaDd)
			}
			else{		
				areaDd.data('last-val', areaDd.val())
				staffDd.data('last-val', staffDd.val())
				//appointModal.find('.removeService').trigger('click');
				removeServiceRow()
				var dd = $('select[name="serviceName0"]');
				dd.html(options).removeAttr('data-unique-id');

				if(defaultServices != null && defaultServices && eventServices.length){
					var createdIdx = 0,
					i = 0;

					$.each(eventServices, function(key, value){
						if((bookNextAppointMode && this.isDeleted != 'true' && !isStaffMarkedAsDeleted && !isAreaMarkedAsDeleted) || !bookNextAppointMode){
							if(createdIdx){
								appointModal.find('.addMoreService').trigger('click');
								createdIdx = formRowsCreated;
							}

							var serviceNameDd = appointModal.find('select[name="serviceName'+createdIdx+'"]');
							serviceNameDd.attr('data-unique-id', i);
							if(this.isDeleted == 'true' || isStaffMarkedAsDeleted || isAreaMarkedAsDeleted){
								serviceNameDd.append('<option value="'+this.id+'" selected>'+this.name+'</option>')
								if(this.isDeleted == 'true')
									toggleField_DelMsg({kase:'trashed', field:serviceNameDd, entity:this.name});
								else
									toggleField_DelMsg({kase:'exist', field:serviceNameDd});
							}
							else{
								serviceNameDd.val(this.id);
								toggleField_DelMsg({kase:'exist', field:serviceNameDd});
							}
							/*var timeField = appointModal.find('input[name="serviceTime'+createdIdx+'"]'),
								time = dbTimeToTimeString(this.time);
							timeField.val(time).data('default-time', time);
							workingHoursTimePicker(timeField)*/
							setFieldForTimepicker(appointModal.find('input[name="serviceTime'+createdIdx+'"]'), dbTimeToTimeString(this.time))
							appointModal.find('select[name="serviceDur'+createdIdx+'"]').val(this.duration);
							appointModal.find('input[name="servicePrice'+createdIdx+'"]').val(this.price);
							appointModal.find('input[name="serviceIsDeleted'+createdIdx+'"]').val(this.isDeleted);
							if(!createdIdx)
								createdIdx++;
							i++;
						}
					});
					bookNextAppointMode = false;
				}
				else{
					dd.trigger('change');
					toggleField_DelMsg({kase:'exist', field:dd});
				}

				appointModal.find('.services select').selectpicker('refresh');
				//appointModal.find('.removeService').trigger('click');

				/*var dd = $('select[name="serviceName0"]')/*,
				options = '<option value="" selected>-- Select --</option>'*/;

				/*if(data.length){
					$.each(data, function(val, text){
						options += '<option value="'+this.id+'" data-duration="'+this.duration+'" data-price="'+this.price+'">'+this.name+'</option>';
					});
				}*
				dd.html(options).removeAttr('data-unique-id');
				//dd.removeAttr('data-unique-id');

				if(defaultServices != null && defaultServices && eventServices.length){
					var createdIdx = 0,
						i = 0;

					$.each(eventServices, function(key, value){
						if(bookNextAppointMode && this.isDeleted != 'true' || !bookNextAppointMode){
							if(createdIdx){
								appointModal.find('.addMoreService').trigger('click');
								createdIdx = formRowsCreated;
							}

							var serviceNameDd = appointModal.find('select[name="serviceName'+createdIdx+'"]');
							//serviceNameDd.val(this.id).attr('data-unique-id', i);
							serviceNameDd.attr('data-unique-id', i);
							if(this.isDeleted == 'true'){
								serviceNameDd.html('<option value="'+this.id+'" selected>'+this.name+'</option>')//.selectpicker('refresh');
								toggleField_DelMsg({kase:'trashed', field:serviceNameDd, entity:'service'});
							}
							else{
								serviceNameDd.val(this.id);
								toggleField_DelMsg({kase:'exist', field:serviceNameDd});
							}
							appointModal.find('input[name="serviceTime'+createdIdx+'"]').val(dbTimeToTimeString(this.time));
							appointModal.find('select[name="serviceDur'+createdIdx+'"]').val(this.duration);
							appointModal.find('input[name="servicePrice'+createdIdx+'"]').val(this.price);
							appointModal.find('input[name="serviceIsDeleted'+createdIdx+'"]').val(this.isDeleted);
							if(!createdIdx)
								createdIdx++;
							i++;
						}
					});
					bookNextAppointMode = false;
				}
				else{
					dd.trigger('change');
					toggleField_DelMsg({kase:'exist', field:dd});
				}

				appointModal.find('.services select').selectpicker('refresh');*/
			}
		},'json');
	//}
}
/* end: Populate services based on the staffs */

/* start: Populate service related fields such as duration, price etc */
function populateServiceRelatedFields(staffDd, options, compatibleServices, selectedServicesObj, areaDd){
	removeServiceRow();

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

			setFieldForTimepicker(appointModal.find('input[name="serviceTime'+createdIdx+'"]'), selectedServicesObj[value].time)
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
	appointModal.find('.services select').selectpicker('refresh');
}
/* end: Populate service related fields such as duration, price etc */

function escSingleQuote(stringg){
	return stringg.replace(/'/g, "&#39;");
}
function escQuotes(string){
	return (escSingleQuote(string)).replace(/"/g, "&#34;");
}


/* Toggle waiting shield */
function toggleWaitShield(action){
	if(action == "show")
		$('#waitingShield').removeClass('hidden')
	else if(action == "hide")
		$('#waitingShield').addClass('hidden')
}
/* Toggle waiting shield */

