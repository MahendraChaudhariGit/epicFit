$(document).ready(function () {
    /* start: Populate appointment modal */
    $('a.openAppointmentModal').click(function (e) {
        return false;
        e.preventDefault();

        var $this = $(this),
                eventId = $this.data('event-id');

        $.get(public_url + 'staffevents/appointment/' + eventId, function (data) {

            eventHtml = '<input type="hidden" name="type" value="appointment"><input type="hidden" name="eventId" value="' + eventId + '"><input type="hidden" name="date" value="' + data.date + '"><input type="hidden" name="startTime" value="' + data.startTime + '"><input type="hidden" name="staffId" value="' + data.staffId + '"><input type="hidden" name="areaId" value="' + data.areaId + '"><input type="hidden" name="appointStatusOpt" value="' + data.appointStatusOpt + '"><input type="hidden" name="appointStatusConfirm" value="' + data.appointStatusConfirm + '"><input type="hidden" name="autoExpireAppointDur" value="' + data.autoExpireAppointDur + '"><input type="hidden" name="autoExpireDatetime" value="' + data.autoExpireDatetime + '"><input type="hidden" name="clientName" value="' + data.clientName + '"><input type="hidden" name="clientId" value="' + data.clientId + '"><input type="hidden" name="clientEmail" value="' + data.clientEmail + '"><input type="hidden" name="clientNumb" value="' + data.clientNumb + '"><input type="hidden" name="staffName" value="' + data.staffName + '"><input type="hidden" name="locAreaName" value="' + data.locAreaName + '"><input type="hidden" name="userName" value="' + data.userName + '"><input type="hidden" name="notes" value="' + data.appointNote + '"><input type="hidden" name="eventRepeat" value="' + data.eventRepeat + '"><input type="hidden" name="eventRepeatInterval" value="' + data.eventRepeatInterval + '"><input type="hidden" name="eventRepeatEnd" value="' + data.eventRepeatEnd + '"><input type="hidden" name="eventRepeatEndAfterOccur" value="' + data.eventRepeatEndAfterOccur + '"><input type="hidden" name="eventRepeatEndOnDate" value="' + data.eventRepeatEndOnDate + '"><input type="hidden" name="isRepeating" value="' + data.isRepeating + '">';

            if (data.eventRepeatWeekDays)
                var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
            else
                var eventRepeatWeekDays = [];
            $.each(eventRepeatWeekDays, function (key, val) {
                eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\'' + val + '\'>';
            });

            for (var i in data.histories)
                eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\'' + JSON.stringify(data.histories[i]) + '\'>';

            $this.find('input').remove();
            $this.prepend(eventHtml);
            eventObj = $this;

            eventServices = [];
            for (var i in data.services) {
                var service = data.services[i];
                eventServices.push({id: service.id, name: service.serviceName, time: service.startTime, duration: service.duration, price: service.price});
            }

            eventModal = appointModal.prop('id');
            appointModal.find('input[name="serviceTime0"]').trigger('blur');
            clearForm(appointModal.find('form'));
            appointModal.find('input[name="isNewClient"]').trigger('change');
            switchTabTo('appointDetails', appointModal);

            var modalLocAreaDd = appointModal.find('select[name="modalLocArea"]'),
                    ifAutoExpireAppoint = appointModal.find('input[name="ifAutoExpireAppoint"]');

            ifAutoExpireAppoint.trigger('change');

            modalLocAreaDd.val(eventObj.find('input[name="areaId"]').val());
            getAreaStaffs(modalLocAreaDd, eventObj.find('input[name="staffId"]').val());

            setEventDate(appointModal, 'edit');

            setPastCurrentModeFlag(eventObj.find('input[name="date"]').val() + ' ' + dbTimeToTimeString(eventServices[0].time));

            appointModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());

            appointModal.find('input[name="isRepeating"]').val(eventObj.find('input[name="isRepeating"]').val());

            populateEventReccur(appointModal)

            populateEventHistory(appointModal)

            appointModal.find('a.eventDateChange').hide();

            var eventAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val()
            toggleAppointStatusFields(appointModal.find('.appoint_status_selectable'), eventAppointStatusOpt);
            if (eventAppointStatusOpt == 'Confirmed')
                appointModal.find('select[name="appointStatusConfirm"]').val(eventObj.find('input[name="appointStatusConfirm"]').val());
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
                    } else
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

            setGroupInit();
            appointModal.find('select').selectpicker('refresh');

            if ($this.data('modal-mode') == 'view')
                toggleEventModalUiByTimeMode(appointModal, 'cancel');
            else
                toggleEventModalUiByTimeMode(appointModal, 'cancel');

            appointModal.modal('show');
        }, 'json');
    });
    /* end: Populate appointment modal */

    /* start: Populate class modal */
//	$('a.openClassModal').click(function(e){
//		return false;
//		e.preventDefault();
//
//		var $this = $(this),
//			eventId = $this.data('event-id');
//
//		if(typeof eventId != 'undefined'){
//			$.get(public_url+'staffevents/classes/'+eventId, function(data){
//				eventHtml = '<input type="hidden" name="type" value="class"><input type="hidden" name="eventId" value="'+eventId+'"><input type="hidden" name="date" value="'+data.date+'"><input type="hidden" name="startTime" value="'+data.startTime+'"><input type="hidden" name="staffId" value="'+data.staffId+'"><input type="hidden" name="areaId" value="'+data.areaId+'"><input type="hidden" name="className" value="'+data.className+'"><input type="hidden" name="classId" value="'+data.classId+'"><input type="hidden" name="capacity" value="'+data.capacity+'"><input type="hidden" name="price" value="'+data.price+'"><input type="hidden" name="duration" value="'+data.duration+'"><input type="hidden" name="staffName" value="'+data.staffName+'"><input type="hidden" name="locAreaName" value="'+data.locAreaName+'"><input type="hidden" name="userName" value="'+data.userName+'"><input type="hidden" name="notes" value="'+data.notes+'"><input type="hidden" name="eventRepeat" value="'+data.eventRepeat+'"><input type="hidden" name="eventRepeatInterval" value="'+data.eventRepeatInterval+'"><input type="hidden" name="eventRepeatEnd" value="'+data.eventRepeatEnd+'"><input type="hidden" name="eventRepeatEndAfterOccur" value="'+data.eventRepeatEndAfterOccur+'"><input type="hidden" name="eventRepeatEndOnDate" value="'+data.eventRepeatEndOnDate+'"><input type="hidden" name="isRepeating" value="'+data.isRepeating+'">';
//
//				if(data.eventRepeatWeekDays)
//					var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
//				else
//					var eventRepeatWeekDays = [];
//				$.each(eventRepeatWeekDays, function(key, val){
//					eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\''+val+'\'>';						
//				});
//
//				for(var i in data.histories)
//					eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\''+JSON.stringify(data.histories[i])+'\'>';
//
//				for(var i in data.clients)
//					eventHtml += '<input type=\'hidden\' name=\'client[]\' value=\''+JSON.stringify(data.clients[i])+'\'>';
//
//				$this.find('input').remove();
//				$this.prepend(eventHtml);
//				eventObj = $this;
//
//
//				eventModal = classModal.prop('id');
//				clientLinkOrder = 0;
//				classClients = {};
//				clearNotific(classModal.find('.tab-pane'));
//				clearForm(classModal.find('form'));
//				clearClientForm(classModal.find('.client-form'), false);
//				classModal.find('input[name="isNewClient"]').trigger('change');
//				classModal.find('div#linkedclientList').html('');
//				toggleAttendanceTab('hide')
//				classModal.find('div#classAttendanceList').html('');
//				switchTabTo('classDetails', classModal);
//
//				var modalLocAreaDd = classModal.find('select[name="modalLocArea"]');
//				modalLocAreaDd.val(eventObj.find('input[name="areaId"]').val());
//				getAreaStaffs(modalLocAreaDd, eventObj.find('input[name="staffId"]').val());
//
//				setEventDate(classModal, 'edit');
//				var timeString = dbTimeToTimeString(eventObj.find('input[name="startTime"]').val());
//				classModal.find('input[name="eventTime"]').val(timeString);
//
//				setPastCurrentModeFlag(eventObj.find('input[name="date"]').val()+' '+timeString);
//
//				classModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());
//
//				classModal.find('input[name="isRepeating"]').val(eventObj.find('input[name="isRepeating"]').val());
//
//				eventObj.find("input[name='client[]']").each(function(){
//					var clientData = JSON.parse($(this).val());
//					clientData.isExisting = 1;
//					updateLinkedclientList(clientData);
//				});
//
//				populateEventReccur(classModal)
//
//				populateEventHistory(classModal)
//
//				classModal.find('a.eventDateChange').hide();
//
//				classModal.find('textarea[name="classNote"]').val(eventObj.find('input[name="notes"]').val());
//					
//				setGroupInit();
//				classModal.find('select').selectpicker('refresh');
//				toggleClassClientLnks('create', true);
//
//				if($this.data('modal-mode') == 'view')
//					toggleEventModalUiByTimeMode(classModal, 'cancel');
//				else 			
//					toggleEventModalUiByTimeMode(classModal, 'cancel');
//
//				classModal.modal('show');
//			},'json');
//		}
//	});
    $('a.openClassModal').click(function (e) {
return false;


        if (!isSalesProcessMode()) {

//                    var eventType = $(this).find('input[name="type"]').val();

            var d = new Date($(this).text());
            var calEvent_date = $.datepicker.formatDate('yy-mm-dd', d);


            var $this = $(this),
                    eventId = $this.data('event-id'),
                    eventTypeVal = $this.data('event-type'),
                    classId = $this.data('class-id');
                    
            eventType = (eventTypeVal == 'service') ? 'single-service' : 'class';

                    //alert(classId+' aa');return false;



            eventHtml = '<input type="hidden" name="type" value="' + eventType + '"><input type="hidden" name="eventId" value="' + eventId + '">';
            $("#calendEvent").addClass('calendEvent').html(eventHtml);





            if (eventType == 'appointment') {
                eventObj = $(this);
                var sameEvents = calendar.find('.calendEvent.appointment-' + calEvent.id);
                eventServices = [];
                if (sameEvents.length) {
                    sameEvents.each(function () {
                        $this = $(this)
                        eventServices.push({id: $this.find('input[name="serviceId"]').val(), name: $this.find('input[name="serviceName"]').val(), time: $this.find('input[name="startTime"]').val(), duration: $this.find('input[name="duration"]').val(), price: $this.find('input[name="price"]').val(), isDeleted: $this.find('input[name="isServiceDeleted"]').val()});
                    })
                    eventServices.sort(sortServices);
                }
                calPopupHelper.removeClass('hidden');
            } else {

                if (signup_class_stat.eventType == 'classModal' && signup_class_stat.resOnSignup == false)
                {
                    return false;
                }

                ifClientMakeupEligible = $this.find('input[name="ifClientMakeupEligible"]').val();

                if (eventType == 'class') {
//alert(eventType)

                    if (signup_class_stat.reschedule == true && signup_class_stat.eventType != 'signupClass')
                    {
                        return false;
                    }



                    if (signup_class_stat.eventType == 'signupClass') {
                        //alert(signup_class_stat.eventType)
                        eventObj = $this;

                        /*if(ifClientMakeupEligible==0)
                         {
                         $("input[name='makeupsession']").hide();
                         }*/
                        signup_class_stat['eventId'] = eventId;


                        if (signup_class_stat.resonopen == true)
                        {

                            $(".submit-class-signup").trigger("click");
                        } else
                        {

                            showSignupClassModal('edit');
                        }


                    }



                    if (checkDateExistsBetTwo(calEvent_date) == true)
                    {
                        $.get(public_url + 'clientevents/classes/' + eventId, function (data) {

                            //console.log(data)
                            var eventHtml = '<input type="hidden" name="type" value="class"><input type="hidden" name="eventId" value="' + eventId + '"><input type="hidden" name="date" value="' + data.date + '"><input type="hidden" name="startTime" value="' + data.startTime + '"><input type="hidden" name="duration" value="' + data.duration + '"><input type="hidden" name="userId" value="' + data.userId + '"><input type="hidden" name="staffId" value="' + data.staffId + '"><input type="hidden" name="staffName" value="' + data.staffName + '"><input type="hidden" name="isStaffDeleted" value="' + data.isStaffDeleted + '"><input type="hidden" name="classId" value="' + data.classId + '"><input type="hidden" name="capacity" value="' + data.capacity + '"><input type="hidden" name="price" value="' + data.price + '"><input type="hidden" name="className" value="' + data.className + '"><input type="hidden" name="notes" value="' + data.notes + '"><input type="hidden" name="eventRepeat" value="' + data.eventRepeat + '"><input type="hidden" name="eventRepeatInterval" value="' + data.eventRepeatInterval + '"><input type="hidden" name="eventRepeatEnd" value="' + data.eventRepeatEnd + '"><input type="hidden" name="eventRepeatEndAfterOccur" value="' + data.eventRepeatEndAfterOccur + '"><input type="hidden" name="eventRepeatEndOnDate" value="' + data.eventRepeatEndOnDate + '"><input type="hidden" name="isRepeating" value="' + data.isRepeating + '"><input type="hidden" name="isClassDeleted" value="' + data.isClassDeleted + '">';


                            if (data.startDatetime < moment().format('YYYY-MM-DD HH:mm:ss'))
                            {
                                return false;
                            }


                            var resClassData = "<fieldset class='padding-15'><legend>Class </legend>  <div class='form-group delMsgPar'> <label class='strong'> Class </label> <p> " + data.className + "</p></div> <div class='form-group'><label class='strong'> Duration </label><p> " + data.duration + " mins</p></div> <div class='form-group'><label class='strong'> Capacity </label><p> " + data.capacity + "</p></div><div class='form-group'><label class='strong'> Price </label> <p> $ " + data.price + "</p> </div> </fieldset>";

                            if (signup_class_stat.eventType != 'signupClass')
                            {
                                //alert(data.id)
                                $('#resClassClient').attr('data-resevent-id', data.id);

                            }


                            $("#details_recur,#classNotes_recur,#recur_recur").removeClass("hidden");
                            $("#details_recur").empty();
                            $("#details_recur").html(resClassData);
                            classModal.find("#classFieldset").addClass("hidden");





                            classModal.find("#resClassEveDate").empty();
                            classModal.find(".eventDateDisp").addClass("hidden");
                            //alert(moment(data.startDatetime).format('LLL'))

                            classModal.find("#resClassClient").removeClass("hidden");



                            classModal.find("#resClassEveDate").html(moment(data.startDatetime).format("ddd, D MMM YYYY") + " at " + moment(data.startDatetime).format('LT'));



                            classModal.find(".eventDateDisp").addClass("hidden");


                            classModal.find("#classTabs li").not("#classTabs li:first").hide();

                            classModal.find(".clip-check").hide();

                            classModal.find("#classDetails a")/*.not("a.eventDateChange")*/.addClass("hidden");

                            classModal.find(".timepicker").addClass("hidden");





                            classModal.find("#nextTab,.submit").addClass("hidden");


                            if (data.eventRepeatWeekDays)
                                var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
                            else
                                var eventRepeatWeekDays = [];
                            $.each(eventRepeatWeekDays, function (key, val) {
                                eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\'' + val + '\'>';
                            });

                            for (var i in data.clients)
                                eventHtml += '<input type=\'hidden\' name=\'client[]\' value=\'' + escQuotes(JSON.stringify(data.clients[i])) + '\'>';

                            for (var i in data.areas)
                                eventHtml += '<input type=\'hidden\' name=\'area[]\' value=\'' + escQuotes(JSON.stringify(data.areas[i])) + '\'>';

                            for (var i in data.histories)
                                eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\'' + escQuotes(JSON.stringify(data.histories[i])) + '\'>';

                            $this.find('input').remove();
                            $this.duration  = data.duration;
                            
                            $this.find('.calendEvent').prepend(eventHtml);
                            eventObj = $this;
                            
                            if (rescheduleClientsCount()) {

                                if (rescheduleClients[Object.keys(rescheduleClients)[0]].ifRecur && eventObj.find('input[name="isRepeating"]').val() == 1)
                                    $('#recurrClassClientReschecModal').modal('show');
                                else
                                    rescheduleClass_client();
                            } else if (isCreateMakeUpMode()) {
                                var formData = {};

                                formData['eventId'] = eventObj.find('input[name="eventId"]').val();
                                formData['clientId'] = defaultClientDetails.id;
                                formData['origEventId'] = parent.makeUpEventId;
                                $.post(public_url + 'staffevents/classes/makeup-client', formData, function (data) {
                                    var data = JSON.parse(data);
                                    if (data.status == "added")
                                        parent.reloadPageWithAppointmentTab();
                                    else if (data.status == "clientBusy")
                                        showNotific(data.message);
                                });
                            } else {
//alert(signup_class_stat.resonopen)
                                if (signup_class_stat.resOnSignup == true && signup_class_stat.eventType != 'signupClass' && signup_class_stat.resonopen == false)
                                {

                                    showClassModal('edit');
                                }

                            }


                        }, 'json');
                    } else
                    {
                        return false;
                    }


                } else if (eventType == 'single-service') {


                    if (signup_class_stat.reschedule == true)
                    {
                        return false;
                    }




                    if (checkDateExistsBetTwo(calEvent_date) == true)
                    {
                        $.get(public_url + 'clientevents/single-service/' + eventId, function (data) {

                            var eventHtml = '<input type="hidden" name="type" value="single-service"><input type="hidden" name="eventId" value="' + eventId + '"><input type="hidden" name="date" value="' + data.date + '"><input type="hidden" name="startTime" value="' + data.startTime + '"><input type="hidden" name="duration" value="' + data.duration + '"><input type="hidden" name="userId" value="' + data.userId + '"><input type="hidden" name="staffId" value="' + data.staffId + '"><input type="hidden" name="staffName" value="' + data.staffName + '"><input type="hidden" name="isStaffDeleted" value="' + data.isStaffDeleted + '"><input type="hidden" name="price" value="' + data.price + '"><input type="hidden" name="notes" value="' + data.notes + '"><input type="hidden" name="eventRepeat" value="' + data.eventRepeat + '"><input type="hidden" name="eventRepeatInterval" value="' + data.eventRepeatInterval + '"><input type="hidden" name="eventRepeatEnd" value="' + data.eventRepeatEnd + '"><input type="hidden" name="eventRepeatEndAfterOccur" value="' + data.eventRepeatEndAfterOccur + '"><input type="hidden" name="eventRepeatEndOnDate" value="' + data.eventRepeatEndOnDate + '"><input type="hidden" name="isRepeating" value="' + data.isRepeating + '"><input type="hidden" name="appointStatusOpt" value="' + data.appointStatusOpt + '"><input type="hidden" name="autoExpireAppointDur" value="' + data.autoExpireAppointDur + '"><input type="hidden" name="autoExpireDatetime" value="' + data.autoExpireDatetime + '"><input type="hidden" name="serviceName" value="' + data.serviceName + '"><input type="hidden" name="serviceId" value="' + data.serviceId + '"><input type="hidden" name="isServiceDeleted" value="' + data.isServiceDeleted + '"><input type="hidden" name="salesProcessEvent" value="' + data.salesProcessEvent + '"><input type="hidden" name="consultationDate" value="' + data.consultationDate + '">';



                            if (data.startDatetime < moment().format('YYYY-MM-DD HH:mm:ss'))
                            {
                                return false;
                            }


                            var resServiceData = "<fieldset class='padding-15'><legend>Service </legend>  <div class='form-group delMsgPar'> <label class='strong'> Service </label> <p> " + data.serviceName + "</p></div> <div class='form-group'><label class='strong'> Duration </label><p> " + data.duration + " mins</p></div> <div class='form-group'><label class='strong'> Price </label><p> " + data.price + "</p></div><div class='form-group'><label class='strong'> Booking status </label> <p> " + data.appointStatusOpt + "</p> </div> </fieldset>";

                            $("#details_recur").empty();

                            classModal.find("#resClassClient").addClass("hidden");

                            classModal.find("#classDetails a").removeClass("hidden");

                            classModal.find(".timepicker").removeClass("hidden");

                            classModal.find("#serviceFieldset").addClass("hidden");

                            $("#details_recur").html(resServiceData);

                            classModal.find("#classTabs li").not("#classTabs li:first").hide();

                            classModal.find("#resClassEveDate").empty();
                            classModal.find(".eventDateDisp").removeClass("hidden");



                            /*classModal.find("#durup").html("");
                             classModal.find("#priup").html("");
                             classModal.find("#durup").html(data.duration);
                             classModal.find("#priup").html(data.price);*/



                            //$("#details_recur,#classNotes_recur,#recur_recur").addClass("hidden");

                            classModal.find(".set-group-disp").find(":nth-child(3)").addClass("hidden");

                            classModal.find("#classFieldset").removeClass("hidden");

                            classModal.find(".clip-check").show();

                            classModal.find("#classDetails a").not("a.eventDateChange").removeClass("hidden");

                            classModal.find("#nextTab,.submit").removeClass("hidden");


                            if (data.eventRepeatWeekDays)
                                var eventRepeatWeekDays = JSON.parse(data.eventRepeatWeekDays);
                            else
                                var eventRepeatWeekDays = [];
                            $.each(eventRepeatWeekDays, function (key, val) {
                                eventHtml += '<input type=\'hidden\' name=\'eventRepeatWeekDays[]\' value=\'' + val + '\'>';
                            });


                            for (var i in data.clients)
                                eventHtml += '<input type=\'hidden\' name=\'client[]\' value=\'' + escQuotes(JSON.stringify(data.clients[i])) + '\'>';

                            for (var i in data.areas)
                                eventHtml += '<input type=\'hidden\' name=\'area[]\' value=\'' + escQuotes(JSON.stringify(data.areas[i])) + '\'>';

                            for (var i in data.histories)
                                eventHtml += '<input type=\'hidden\' name=\'history[]\' value=\'' + escQuotes(JSON.stringify(data.histories[i])) + '\'>';

                            $this.find('input').remove();
                            //alert(eventHtml);
                            //console.log($this);return false;
                            classModal.find('.calendEvent').prepend(eventHtml);
                            eventObj = classModal;


                            showClassModal('edit');
                        }, 'json');
                    } else
                    {
                        return false;
                    }



                } else {
                    $.get(public_url + 'clientevents/busy-time/' + eventId, function (data) {
                        var eventHtml = '<input type="hidden" name="type" value="busy"><input type="hidden" name="eventId" value="' + eventId + '"><input type="hidden" name="description" value="' + data.description + '"><input type="hidden" name="denyBooking" value="' + data.denyBooking + '"><input type="hidden" name="ifAreaHasStaffs" value="' + data.ifAreaHasStaffs + '"><input type="hidden" name="areaId" value="' + data.areaId + '"><input type="hidden" name="isAreaDeleted" value="' + data.isAreaDeleted + '"><input type="hidden" name="date" value="' + data.date + '"><input type="hidden" name="startTime" value="' + data.startTime + '"><input type="hidden" name="duration" value="' + data.duration + '"><input type="hidden" name="staffId" value="' + data.staffId + '"><input type="hidden" name="userId" value="' + data.userId + '"><input type="hidden" name="staffName" value="' + data.staffName + '"><input type="hidden" name="isStaffDeleted" value="' + data.isStaffDeleted + '">';

                        $this.find('input').remove();
                        $this.find('.calendEvent').prepend(eventHtml);
                        eventObj = $this;

                        //showBusyModal('edit');
                    }, 'json');
                }
            }

            if (!rescheduleClientsCount())
                resetAllModes();
        }

    });
    /* end: Populate class modal */

    /* start: Initialize calPopupHelper */
    calPopupHelper.height($(document).height());
    /* end: Initialize calPopupHelper */
});

function getCalendEndDate() {
    return moment().endOf('month').format('YYYY-MM-DD');
}

function submitAppointmentModalSuccess(data) {
    appointModal.modal('hide');
    reloadPageWithAppointmentTab();
}

function submitClassModalSuccess(data) {
    classModal.modal('hide');
    if (rescheduleClientsCount()) {
        $.cookie(rescheduleClientsCookieSlug, JSON.stringify(rescheduleClients), {path: '/'})
        window.location.replace(public_url + "dashboard/calendar-new");
    } else
        reloadPageWithAppointmentTab();
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
    window.location.hash = "#appointments";
    location.reload(true);
}

function switchTab(tab) {
    $('#myTab4.nav-tabs a[href="' + tab + '"]').tab('show')
}