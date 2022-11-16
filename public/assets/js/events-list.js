var rescheduleClientMode = false,
    createMakeUpMode = false,
    createServiceMakeUpMode = false,
    makeUpEventId = 0,
    calendarSettings = JSON.parse($('input[name=calendarSettingVal]').val()),
    scriptSource = 'list',
    serviceEventId = 0;
$(document).ready(function() {
    /* start: Populate appointment modal */
    $('a.openAppointmentModal').click(function(e) {
        e.preventDefault();
        return;
        var $this = $(this),
            eventId = $this.data('event-id');
        serviceEventId = eventId;
        $.get(public_url + 'staffevents/appointment/' + eventId, function(data) {
            eventHtml = '<input type="hidden" name="type" value="appointment"><input type="hidden" name="eventId" value="' + eventId + '"><input type="hidden" name="date" value="' + data.date + '"><input type="hidden" name="startTime" value="' + data.startTime + '"><input type="hidden" name="staffId" value="' + data.staffId + '"><input type="hidden" name="areaId" value="' + data.areaId + '"><input type="hidden" name="appointStatusOpt" value="' + data.appointStatusOpt + '"><input type="hidden" name="appointStatusConfirm" value="' + data.appointStatusConfirm + '"><input type="hidden" name="autoExpireAppointDur" value="' + data.autoExpireAppointDur + '"><input type="hidden" name="autoExpireDatetime" value="' + data.autoExpireDatetime + '"><input type="hidden" name="clientName" value="' + data.clientName + '"><input type="hidden" name="clientId" value="' + data.clientId + '"><input type="hidden" name="clientEmail" value="' + data.clientEmail + '"><input type="hidden" name="clientNumb" value="' + data.clientNumb + '"><input type="hidden" name="staffName" value="' + data.staffName + '"><input type="hidden" name="locAreaName" value="' + data.locAreaName + '"><input type="hidden" name="notes" value="' + data.appointNote + '"><input type="hidden" name="eventRepeat" value="' + data.eventRepeat + '"><input type="hidden" name="eventRepeatInterval" value="' + data.eventRepeatInterval + '"><input type="hidden" name="eventRepeatEnd" value="' + data.eventRepeatEnd + '"><input type="hidden" name="eventRepeatEndAfterOccur" value="' + data.eventRepeatEndAfterOccur + '"><input type="hidden" name="eventRepeatEndOnDate" value="' + data.eventRepeatEndOnDate + '"><input type="hidden" name="isRepeating" value="' + data.isRepeating + '"><input type="hidden" name="userId" value="' + data.userId + '"><input type="hidden" name="isStaffDeleted" value="' + data.isStaffDeleted + '"><input type="hidden" name="isAreaDeleted" value="' + data.isAreaDeleted + '"><input type="hidden" name="ifAreaHasStaffs" value="' + data.ifAreaHasStaffs + '"><input type="hidden" name="isInvoice" value="' + data.isInvoice + '">';
            if (data.eventRepeatWeekDays) var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
            else var eventRepeatWeekDays = [];
            $.each(eventRepeatWeekDays, function(key, val) {
                eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\'' + val + '\'>';
            });
            for (var i in data.histories) eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\'' + JSON.stringify(data.histories[i]) + '\'>';
            $this.find('input').remove();
            $this.prepend(eventHtml);
            eventObj = $this;
            eventServices = [];
            for (var i in data.services) {
                var service = data.services[i];
                eventServices.push({
                    id: service.id,
                    name: service.serviceName,
                    time: service.startTime,
                    duration: service.duration,
                    price: service.price,
                    isDeleted: service.isServiceDeleted.toString()
                });
            }
            eventServices.sort(sortServices);
            blockTimepickerChangeEvent = true;
            eventModal = appointModal.prop('id');
            appointModal.find('input[name="serviceTime0"]').trigger('blur');
            clearForm(appointModal.find('form'));
            appointModal.find('input[name="isNewClient"]').trigger('change');
            switchTabTo('appointDetails', appointModal);
            var //modalLocAreaDd = appointModal.find('select[name="modalLocArea"]'),
                ifAutoExpireAppoint = appointModal.find('input[name="ifAutoExpireAppoint"]')
            /*,
            				areaSetGroupDisp = modalLocAreaDd.closest('.set-group').find('.set-group-disp'),
            				areaSetGroupDispSpan = areaSetGroupDisp.find('span')*/
            ;
            ifAutoExpireAppoint.trigger('change');
            /*appointModal.find('select[name="staff"]').data('new-instance', 'true').data('services-new-instance', 'true')

            if(eventObj.find('input[name="isAreaDeleted"]').val() == 'true' || eventObj.find('input[name="ifAreaHasStaffs"]').val() == 'false'){
            	areaSetGroupDispSpan.addClass('text-danger');
            	toggleDeletedMarkOnField(modalLocAreaDd, eventObj.find('input[name="areaId"]').val())
            }
            else{
            	modalLocAreaDd.val(eventObj.find('input[name="areaId"]').val());
            	areaSetGroupDispSpan.removeClass('text-danger');
            	toggleDeletedMarkOnField(modalLocAreaDd, 0)	
            }
            getAreaStaffs(modalLocAreaDd, eventObj.find('input[name="staffId"]').val());*/
            setEventDate(appointModal, 'edit');
            setPastCurrentModeFlag(eventObj.find('input[name="date"]').val() + ' ' + dbTimeToTimeString(eventServices[0].time));
            appointModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());
            appointModal.find('input[name="isRepeating"]').val(eventObj.find('input[name="isRepeating"]').val());
            populateEventReccur(appointModal)
            populateEventHistory(appointModal)
            appointModal.find('a.eventDateChange').hide();
            var eventAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val()
            toggleAppointStatusFields(appointModal.find('.appoint_status_selectable'), eventAppointStatusOpt);
            if (eventAppointStatusOpt == 'Confirmed') appointModal.find('select[name="appointStatusConfirm"]').val(eventObj.find('input[name="appointStatusConfirm"]').val());
            else {
                var eventAutoExpireDatetime = eventObj.find('input[name="autoExpireDatetime"]').val(),
                    eventAutoExpireAppointDur = eventObj.find('input[name="autoExpireAppointDur"]').val();
                if (eventAutoExpireDatetime != '0000-00-00 00:00:00' || eventAutoExpireAppointDur != '') {
                    var autoExpireAppointDur = appointModal.find('select[name="autoExpireAppointDur"]'),
                        autoExpireAppointDurDate = appointModal.find('input[name="autoExpireAppointDurDate"]'),
                        autoExpireAppointDurTime = appointModal.find('input[name="autoExpireAppointDurTime"]');
                    ifAutoExpireAppoint.prop('checked', true);
                    ifAutoExpireAppoint.trigger('change');
                    if (eventAutoExpireAppointDur == '') {
                        autoExpireAppointDur.val('Custom');
                        autoExpireAppointDurDate.val(moment(eventAutoExpireDatetime).format("D MMM YYYY"));
                        autoExpireAppointDurTime.val(moment(eventAutoExpireDatetime).format("h:mm A"));
                    } else autoExpireAppointDur.val(eventAutoExpireAppointDur);
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
            setGroupInit();
            /*if(eventObj.find('input[name="isAreaDeleted"]').val() == 'true')
            	areaSetGroupDispSpan.text('This area has been deleted')
            else if(eventObj.find('input[name="ifAreaHasStaffs"]').val() == 'false')
            	areaSetGroupDispSpan.text("This area doesn't have any staff")*/
            appointModal.find('select').selectpicker('refresh');
            if ($this.data('modal-mode') == 'view') toggleEventModalUiByTimeMode(appointModal, 'cancel');
            else toggleEventModalUiByTimeMode(appointModal, 'edit');
            blockTimepickerChangeEvent = false;
            appointModal.modal('show');
        }, 'json');
    });
    /* end: Populate appointment modal */
    /* start: Populate class modal */
    $('a.openClassModal').click(function(e) {
        // console.log('Event Clicked');
        e.preventDefault();
        var $this = $(this),
            $eventType = $this.data('event-type'),
            eventId = $this.data('event-id');
        serviceEventId = eventId;
        getBookingDetails(eventId, $eventType, function(eventHtml) {
            $this.find('input').remove();
            $this.prepend(eventHtml);
            eventObj = $this;
            if ($this.data('modal-mode') == 'view') cancelledBooking = true;
            else cancelledBooking = false;
            eventModal = classModal.prop('id');
            blockTimepickerChangeEvent = true;
            clientLinkOrder = 0;
            classClients = {};
            clearNotific(classModal.find('.tab-pane'));
            clearForm(classModal.find('form'));
            clearClientForm(classModal.find('.client-form'), false);
            classModal.find('div#linkedclientList').html('');
            classModal.find('div#waitingLinkedclientList').html('');
            classModal.find('div#waitingClassAttendanceList').html('');
            toggleAttendanceTab('hide')
            classModal.find('div#classAttendanceList').html('');
            classModal.find('.waiting-clients-tab').empty();
            classModal.find('.waitingClient').empty();
            switchTabTo('classDetails', classModal);
            classModal.find('a.eventDateChange').hide();
            /*toggleTab(classModal, 'classReccur', 'show');
            toggleTab(classModal, 'classClients', 'show');*/
            setEventDate(classModal, 'edit');
            var timeString = dbTimeToTimeString(eventObj.find('input[name="startTime"]').val());
            setFieldForTimepicker(classModal.find('input[name="eventTime"]'), timeString)
            setPastCurrentModeFlag(eventObj.find('input[name="date"]').val() + ' ' + timeString);
            classModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());
            classModal.find('input[name="isRepeating"]').val(eventObj.find('input[name="isRepeating"]').val());
            populateEventReccur(classModal)
            populateEventHistory(classModal)
            classModal.find('textarea[name="classNote"]').val(eventObj.find('input[name="notes"]').val());
            eventObj.find("input[name='client[]']").each(function() {
                var clientData = JSON.parse($(this).val());
                clientData.isExisting = 1;
                updateLinkedclientList(clientData);
            });
            if (classModal.find('div#linkedclientList > a[data-deleted="1"]').length) showNotific(prepareAlert('warning', 'Marked clients have been deleted from the business.'), classModal.find('div#classClients'));
            var eventType = eventObj.find("input[name='type']").val();
            if (eventType == 'class') {
                setBookingType('class');
                /*getClasses(eventObj.find('input[name="classId"]').val(), function(){
                	getStaffsAndAreas();
                	getResources();
                });*/
                getClasses(eventObj.find('input[name="classId"]').val(), getStaffsAndAreas);
                //getClasses(eventObj.find('input[name="classId"]').val());
            } else if (eventType == 'single-service') {
                var ifAutoExpireAppoint = classModal.find('input[name="ifAutoExpireAppoint"]');
                //ifAutoExpireAppoint.trigger('change');
                toggleAutoExpireFields(ifAutoExpireAppoint);
                setBookingType('service');
                updateBookedclientQuickInfo();
                /*getServices(eventObj.find('input[name="serviceId"]').val(), function(){
                	getStaffsAndAreas();
                	getResources();
                });*/
                getServices(eventObj.find('input[name="serviceId"]').val(), getStaffsAndAreas);
                var eventAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val()
                toggleAppointStatusFields(eventAppointStatusOpt);
                if (eventAppointStatusOpt == 'Pencilled-In') {
                    var eventAutoExpireDatetime = eventObj.find('input[name="autoExpireDatetime"]').val(),
                        eventAutoExpireAppointDur = eventObj.find('input[name="autoExpireAppointDur"]').val();
                    if ((eventAutoExpireDatetime != 'null' && eventAutoExpireDatetime != null) || eventAutoExpireAppointDur) {
                        var autoExpireAppointDur = classModal.find('select[name="autoExpireAppointDur"]'),
                            autoExpireAppointDurDate = classModal.find('input[name="autoExpireAppointDurDate"]'),
                            autoExpireAppointDurTime = classModal.find('input[name="autoExpireAppointDurTime"]');
                        ifAutoExpireAppoint.prop('checked', true);
                        //ifAutoExpireAppoint.trigger('change');
                        toggleAutoExpireFields(ifAutoExpireAppoint);
                        if (!eventAutoExpireAppointDur) {
                            autoExpireAppointDur.val('Custom');
                            autoExpireAppointDurDate.val(moment(eventAutoExpireDatetime).format("D MMM YYYY"));
                            autoExpireAppointDurTime.val(moment(eventAutoExpireDatetime).format("h:mm A"));
                        } else autoExpireAppointDur.val(eventAutoExpireAppointDur);
                        //autoExpireAppointDur.trigger('change');
                        toggleManualExpireFields(autoExpireAppointDur)
                    }
                }
                //if(/*eventObj.find('input[name="salesProcessEvent"]').val() != 'null'*/isSalesProcessEvent()){
                //disableAppointStatuselectable();
                //toggleTab(classModal, 'classReccur', 'hide');
                //toggleTab(classModal, 'classClients', 'hide');
                //}
            }
            disableBookTypeSelectable()
            setGroupInit();
            classModal.find('select').selectpicker('refresh');
            if (eventType == 'single-service') selectLatestLinkedClient();
            else {
                var bookingType = getBookingType();
                if (bookingType == 'class') toggleClassClientLnks('create');
                else if (bookingType == 'service') toggleClassClientLnks('create', true);
            }
            //if(isSalesProcessEvent())
            //classModal.find('a#unlinkClientClass').hide();
            if ( /*$this.data('modal-mode') == 'view'*/ cancelledBooking) toggleEventModalUiByTimeMode(classModal, 'cancel');
            else toggleEventModalUiByTimeMode(classModal, 'edit');
            toggleSubmitBtn();
            blockTimepickerChangeEvent = false;

            if (classModal.find('ul.nav a[href="#classAttendance"]').css('display') != 'none') {
                setTimeout(function() {
                    switchTabTo('classAttendance', classModal);
                }, 200)
            } else {
	            setTimeout(function() {
	                    switchTabTo('classClients', classModal);
	            }, 200);
        	}
           
            classModal.modal('show');
        })
        //},'json');
    });
    /* end: Populate class modal */
    $('a.js-createMakeup').click(function(e) {
        e.preventDefault();
        var eventId = $(this).data('event-id');
        if (typeof eventId == 'undefined') makeUpEventId = 0;
        else makeUpEventId = eventId;
        if ($(this).data('event-type') == 'class') createMakeUpMode = true;
        else createServiceMakeUpMode = true;
        //openSubview('calendar', 'clientId='+$('#form input[name="client_id"]').val());
        openSubview('calendar', calendarSubviewConds());
    });
    /* start: Initialize calPopupHelper */
    calPopupHelper.height($(document).height());
    /* end: Initialize calPopupHelper */
    classModal.on('hide.bs.modal', function() {
        if (reloadEvents && (!deleteServiceMode || rescheduleClientMode)) {
            reloadPageWithAppointmentTab();
        }
    });
    /* Start: Cancelled Appointment/Class action*/
    if ($('[name="pastCancel"]').is(':checked')) {
        $('.pastCancelled').removeClass('hidden');
    }
    if ($('[name="futureCancel"]').is(':checked')) {
        $('.futureCancelled').removeClass('hidden');
    }
    $('[name="pastCancel"]').change(function() {
        if (this.checked) {
            $('.pastCancelled').removeClass('hidden');
        } else {
            $('.pastCancelled').addClass('hidden');
        }
    });
    $('[name="futureCancel"]').change(function() {
        if (this.checked) {
            $('.futureCancelled').removeClass('hidden');
        } else {
            $('.futureCancelled').addClass('hidden');
        }
    });
    /* End: Cancelled Appointment/Class action*/
});

function calendarSubviewConds() {
    if (!isUserType(['Staff'])) return 'clientId=' + $('#form input[name="client_id"]').val();
    return '';
}
// function enterReschecduleClientMode(eventId){
//     rescheduleClients = rescheduleClients;
//     console.log(rescheduleClients);
// 	reloadEvents = false;
// 	classModal.modal('hide');
// 	rescheduleClientMode = true;
// 	reloadEvents = false;
// 	areaDdCalendLastVal = findAreaId();
// 	staffFilterCalendLastVal = findStaffId();
// 	classModal.modal('hide');
// 	var bookingType = getBookingType();
// 	if(bookingType == 'class'){
// 		openSelDateNotif('Choose a class to reschedule the client(s).');
// 		var staff = 'all';
// 	}
// 	else if(bookingType == 'service'){
// 		//if(isSalesProcessEvent() && $.inArray(eventObj.find('input[name="salesProcessEvent"]').val(), ['4', '5', '6', '11']) !== -1 )//'9'
// 		if(isSalesProcessEvent()){
// 		//var stepNumb = eventObj.find('input[name="salesProcessEvent"]').val();
// 			var stepNumb = parseInt(eventObj.find('input[name="salesProcessEvent"]').val(), 10);
// 			if(!isNaN(stepNumb)){
// 				if(stepNumb == 4 || stepNumb == 5 || $.inArray(stepNumb, sessionSteps()) > -1){
// 					setEnableDisableDateFields();
// 				}
// 			}
// 		}
// 		resheduleAppointDateSelMode = true;
// 		openSelDateNotif('Choose a new time for this service.');
// 		var staff = eventObj.find('input[name="staffId"]').val();
// 	}
// 	areaDdCalend.selectpicker('val', 'all');
// 	getAreaStaffs(areaDdCalend, staff);
// 	openSubview('calendar', calendarSubviewConds()+'&resheduleAppointFromOverview=true&eventId='+serviceEventId);
// }
function getCalendEndDate() {
    return moment().endOf('month').format('YYYY-MM-DD');
}

function getCalendEndDatePriorDate() {
    return getCalendEndDate();
}

function getRescheduledClients() {
    return rescheduleClients;
}

function submitAppointmentModalSuccess(data) {
    appointModal.modal('hide');
    reloadPageWithAppointmentTab();
}

function submitClassModalSuccess(data) {
    classModal.modal('hide');
    /*if(rescheduleClientsCount()){
    	$.cookie(rescheduleClientsCookieSlug, JSON.stringify(rescheduleClients), { path:'/'})
    	window.location.replace(public_url+"dashboard/calendar-new");
    }
    else*/
    getStaffEvents(0,true, data.repeatId);
    // reloadPageWithAppointmentTab();
}

function submitCancelAppointmentModalSuccess(ifForceDelete, data) {
    appointCancelModal.modal('hide');
    reloadPageWithAppointmentTab();
}

function cancelEventSuccess(data) {
    classModal.modal('hide');
    reloadPageWithAppointmentTab();
}

function reloadPageWithAppointmentTab() {
    var param = getUrlVars()["tab"];

    // reloadPageWithTab("#appointments");
    //window.location.hash = "#appointments"; 
    if(param != 'assess_progress'){
    location.reload(true);
    }
}

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function switchTab(tab) {
    $('#myTab4.nav-tabs a[href="' + tab + '"]').tab('show')
}