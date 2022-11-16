var rescheduleSessionMode = false;
var origEventId = '';
var public_url = $('meta[name="public_url"]').attr('content');
var classClients = {};

function getClientMobileCalendarData(date) {
    activaTab('mysession');
    $('#classTabs').find('li').removeClass('active');
    // $('.sectionheading').addClass('active');
	var formData = {};
	formData['eventType']= ["class", "single-service"]
    // formData['getEventsFrom'] = moment(date,'YYYY-MM-DD').startOf('month').format('YYYY-MM-DD');
    formData['getEventsFrom'] = date;
    var startDate = moment(date,'YYYY-MM-DD');
    var endDate = moment(startDate,'YYYY-MM-DD').endOf('month').format('YYYY-MM-DD');
    formData['getEventsUpto'] = endDate;
    formData['insertRepeatUpto'] = endDate;
    formData['isInsertClassService'] = false;
	// toggleWaitShield('show');
    $.get(public_url + 'client-events', formData, function (response) {
        // toggleWaitShield('hide');
        $('input[name="currentMonthYear"]').val(moment(date,'YYYY-MM-DD').format('YYYY, MMM'));
        $('.eventFullMonth').text(moment(date,'YYYY-MM-DD').format('MMMM'));
        $('#eventYear').text(moment(date,'YYYY-MM-DD').format('YYYY'));
        var events = [];
        var html = $('.session-data');
        html.empty();
        if(JSON.parse(response).length) {
            var eventHtml = '';
            $.each(JSON.parse(response), function () {
                eventHtml = '';
                eventHtml = '<li>';
                eventHtml += '<div class="crossicon"></div>';
                eventHtml += '<div class="left_date">';
                eventHtml += '<span class="d_month">'+moment(this.startDatetime).format('MMM')+'</span>';
                eventHtml += '<span class="d_date">'+moment(this.startDatetime).format('DD')+'</span>';
                eventHtml += '<span class="d_day">'+moment(this.startDatetime).format('ddd')+'</span>';
                eventHtml += '<span class="d_year">'+moment(this.startDatetime).format('YYYY')+'</span>';
                eventHtml += '</div>';
                eventHtml += '<div class="data_box" style="background-image:url('+this.logo+')" data-event-id="'+this.id+'" data-event-type="'+this.type+'">';
                if(this.isLdc == '1'){
                    eventHtml += '<span class="ldc_icon"><img src="'+public_url+'result/images/ldc-icon.png" /></span>';
                }
                eventHtml += '<h4>'+this.title+'</h4>';
                eventHtml += '<span>Time- '+moment(this.startDatetime).format('LT')+':'+moment(this.endDatetime).format('LT')+'</span>';
                eventHtml += '<span>Trainer- '+this.staffName+'</span>';
                eventHtml += '</div>';
                eventHtml += '</li>';

                html.append(eventHtml);
            });
        }else{
            html.append('<div class="noany_data"><p>No Session Available</p></div>'); 
        }
    });
}

$('document').ready(function(){
	var date = $('input[name="currentEventDate"]').val();
	getClientMobileCalendarData(date);
})

// Swipe Js Starts Here
var myElement = document.getElementById('mysession');

// create a simple instance
// by default, it only adds horizontal recognizers
var mc = new Hammer(myElement);

// listen to events...
mc.on("swipeleft swiperight", function(ev) {
    var currentMonth = $('input[name="currentMonthYear"]').val();
    if(ev.type == 'swipeleft'){
        var nextMonth = moment(currentMonth,'YYYY, MMM').add(1, 'months').format('YYYY, MMM');
    }else{
        var nextMonth = moment(currentMonth,'YYYY, MMM').subtract(1, 'months').format('YYYY, MMM');
    }
    renderEventsOfMonth(currentMonth,nextMonth);
    $('input[name="currentMonthYear"]').val(nextMonth);
});

$('body').on('click','#mysession .leftarrowbtn,#mysession .rightarrowbtn',function(){
    var currentMonth = $('input[name="currentMonthYear"]').val();
    if($(this).hasClass('leftarrowbtn')){
       var nextMonth = moment(currentMonth,'YYYY, MMM').subtract(1, 'months').format('YYYY, MMM');
   }else{
        var nextMonth = moment(currentMonth,'YYYY, MMM').add(1, 'months').format('YYYY, MMM');
   }
   renderEventsOfMonth(currentMonth,nextMonth);
   $('input[name="currentMonthYear"]').val(nextMonth);
});

/** Start: Render Events Of Month **/
function renderEventsOfMonth(currentMonth,nextMonth){
    activaTab('mysession');
    $('.sectionheading').trigger('click');
    if(currentMonth != '' && currentMonth != undefined){
        toggleWaitShield('show');
        var formData = {};
        formData['eventType']= ["class", "single-service"]
        formData['getEventsFrom'] = moment(nextMonth,'YYYY, MMM').startOf('month').format('YYYY-MM-DD');
        formData['getEventsUpto'] = moment(nextMonth,'YYYY, MMM').endOf('month').format('YYYY-MM-DD');
        formData['insertRepeatUpto'] = moment(nextMonth,'YYYY, MMM').endOf('month').format('YYYY-MM-DD');
        formData['isInsertClassService'] = false;
        fetchClientEpicBalance();
        $.get(public_url + 'client-events', formData, function (response) {
            $('.eventFullMonth').text(moment(nextMonth,'YYYY, MMM').format('MMMM'));
            $('#eventYear').text(moment(nextMonth,'YYYY, MMM').format('YYYY'));
            var events = [];
            var html = $('.session-data');
            html.empty();
            if(JSON.parse(response).length) {
                var eventHtml = '';
                $.each(JSON.parse(response), function () {
                    eventHtml = '';
                    eventHtml = '<li>';
                    eventHtml += '<div class="crossicon"></div>';
                    eventHtml += '<div class="left_date">';
                    eventHtml += '<span class="d_month">'+moment(this.startDatetime).format('MMM')+'</span>';
                    eventHtml += '<span class="d_date">'+moment(this.startDatetime).format('DD')+'</span>';
                    eventHtml += '<span class="d_day">'+moment(this.startDatetime).format('ddd')+'</span>';
                    eventHtml += '<span class="d_year">'+moment(this.startDatetime).format('YYYY')+'</span>';
                    eventHtml += '</div>';
                    eventHtml += '<div class="data_box" style="background-image:url('+this.logo+')" data-event-id="'+this.id+'" data-event-type="'+this.type+'">';
                    if(this.isLdc == '1'){
                        eventHtml += '<span class="ldc_icon"><img src="'+public_url+'result/images/ldc-icon.png" /></span>';
                    }
                    eventHtml += '<h4>'+this.title+'</h4>';
                    eventHtml += '<span>Time- '+moment(this.startDatetime).format('LT')+':'+moment(this.endDatetime).format('LT')+'</span>';
                    eventHtml += '<span>Trainer- '+this.staffName+'</span>';
                    eventHtml += '</li>';
                    eventHtml += '</div>';
                    html.append(eventHtml);
                });
            }else{
                html.append('<div class="noany_data"><p>No Session Available</p></div>'); 
            }
            toggleWaitShield('hide');
        });
    }
}
/** End: Render Events Of Month **/

/****** Start: Booking in Session *********/
$('body').on('click','#bookSession', function(){
    if(rescheduleSessionMode){
        resetRescheduleMode();
    }
    var eventDate = moment().add(1,'days').format('YYYY-MM-DD');
    $('input[name="currentEventDate"]').val(eventDate);
    renderEventsTimings(eventDate)
});

// Swipe Js Starts Here
var bookSessionElement = document.getElementById('Booksession');

// create a simple instance
// by default, it only adds horizontal recognizers
var bookSession = new Hammer(bookSessionElement);

var minDate = moment().format('YYYY-MM-DD');
var maxDate = moment().add(6,'days').format('YYYY-MM-DD');
bookSession.on("swipeleft swiperight", function(ev) {
    var currentDate = $('input[name="currentEventDate"]').val();
   if(ev.type == 'swipeleft'){
        var nextDate = moment(currentDate,'YYYY-MM-DD').add(1, 'days').format('YYYY-MM-DD');
        if(nextDate <= maxDate){
            renderEventsTimings(nextDate);
            $('input[name="currentEventDate"]').val(nextDate);
        }
    }else{
        var nextDate = moment(currentDate,'YYYY-MM-DD').subtract(1, 'days').format('YYYY-MM-DD');
        if(nextDate >= minDate){
            renderEventsTimings(nextDate);
            $('input[name="currentEventDate"]').val(nextDate);
        }
    }
});

$('body').on('click','.top_date .leftarrowbtn,.top_date .rightarrowbtn',function(){
    var currentDate = $('input[name="currentEventDate"]').val();
    if($(this).hasClass('leftarrowbtn')){
        var nextDate = moment(currentDate,'YYYY-MM-DD').subtract(1, 'days').format('YYYY-MM-DD');
        if(nextDate >= minDate){
            renderEventsTimings(nextDate);
            $('input[name="currentEventDate"]').val(nextDate);
        }
   }else{
        var nextDate = moment(currentDate,'YYYY-MM-DD').add(1, 'days').format('YYYY-MM-DD');
        if(nextDate <= maxDate){
            renderEventsTimings(nextDate);
            $('input[name="currentEventDate"]').val(nextDate);
        }
   }
});

/** Start: Render Available Events Slot for Booking **/
function renderEventsTimings(eventDate){
    resetData();
    toggleWaitShield('show');
    var formData = {};
    formData['eventDate'] = eventDate;
    $.get(public_url + 'get-events-timing', formData, function (response) {
        if(response.status == 'ok'){
            $('.fulldate').text(moment(eventDate,'YYYY-MM-DD').format('D dddd'));
            var eventTimingAmElement = $('#eventTimingsAm');
            var eventTimingPmElement = $('#eventTimingsPm');
            eventTimingAmElement.empty();
            eventTimingPmElement.empty();
            if(response.data.length){
                var events = response.data;
                var htmlAm = '';
                var htmlPm = '';
                $.each(events,function(){
                    var eventTime = moment(this.startDatetime).format('h:mm');
                    var eventTimeSub = moment(this.startDatetime).format('a');
                    if(moment(this.startDatetime).format('a') == 'am'){
                        if(this.bookedClients >= this.capacity || this.isClientAlreadyBooked || (moment().format('YYYY-MM-DD HH:mm') > moment(this.startDatetime).format('YYYY-MM-DD HH:mm'))){
                            htmlAm += '<li data-event-id="'+this.secId+'" data-class-name="'+this.name+'" data-class-logo="'+this.classLogo+'" data-class-start-time="'+moment(this.startDatetime).format('h:mm A')+'" data-class-end-time="'+moment(this.endDateTime).format('h:mm A')+'" data-trainer-name="'+this.trainerName+'" class="eventTiming disble">'+eventTime+' <sub>'+eventTimeSub.toUpperCase()+'</sub><p>'+this.name+'</p></li>';
                        }else{
                            htmlAm += '<li data-event-id="'+this.secId+'" data-class-name="'+this.name+'" data-class-logo="'+this.classLogo+'" data-class-start-time="'+moment(this.startDatetime).format('h:mm A')+'" data-class-end-time="'+moment(this.endDateTime).format('h:mm A')+'" data-trainer-name="'+this.trainerName+'" class="eventTiming">'+eventTime+' <sub>'+eventTimeSub.toUpperCase()+'</sub><p>'+this.name+'</p></li>';
                        }
                    }else{
                        if(this.bookedClients >= this.capacity || this.isClientAlreadyBooked || (moment().format('YYYY-MM-DD HH:mm') > moment(this.startDatetime).format('YYYY-MM-DD HH:mm'))){
                            htmlPm += '<li data-event-id="'+this.secId+'" data-class-name="'+this.name+'" data-class-logo="'+this.classLogo+'" data-class-start-time="'+moment(this.startDatetime).format('h:mm A')+'" data-class-end-time="'+moment(this.endDateTime).format('h:mm A')+'" data-trainer-name="'+this.trainerName+'" class="eventTiming disble">'+eventTime+' <sub>'+eventTimeSub.toUpperCase()+'</sub><p>'+this.name+'</p></li>';
                        }else{
                            htmlPm += '<li data-event-id="'+this.secId+'" data-class-name="'+this.name+'" data-class-logo="'+this.classLogo+'" data-class-start-time="'+moment(this.startDatetime).format('h:mm A')+'" data-class-end-time="'+moment(this.endDateTime).format('h:mm A')+'" data-trainer-name="'+this.trainerName+'" class="eventTiming">'+eventTime+' <sub>'+eventTimeSub.toUpperCase()+'</sub><p>'+this.name+'</p></li>';
                        }
                    }
                });
                eventTimingAmElement.append(htmlAm);
                eventTimingPmElement.append(htmlPm);
                if(!eventTimingAmElement.find('li').length){
                    eventTimingAmElement.append('<div class="noany_data"><p>No Session Available</p></div>');
                }
                if(!eventTimingPmElement.find('li').length){
                    eventTimingPmElement.append('<div class="noany_data"><p>No Session Available</p></div>');
                }
            }else{
               eventTimingAmElement.append('<div class="noany_data"><p>No Session Available</p></div>');
                eventTimingPmElement.append('<div class="noany_data"><p>No Session Available</p></div>'); 
            } 
        }
        toggleWaitShield('hide');
    });
}
/** End: Render Available Events Slot for Booking **/

/** Start: Event Detail of available time slot **/
$('body').on('click','.eventTiming', function(){
    var instance = $(this);
        $('.eventTiming').removeClass('active');
        instance.addClass('active');
        var sessionElement = $('#sessionDetails');
        sessionElement.empty();
    if(!instance.hasClass('disble')){
        var html = '<div class="sessionshow">';
        html += '<div class="boxwithbg" style="background-image:url('+instance.data('class-logo')+')">';
        // html += '<div class="crossicon"></div>';
        html += '<span class="sessionname">'+instance.data('class-name')+'</span>';
        html += '<br><span class="eventTimeRange">'+instance.data('class-start-time')+' - '+instance.data('class-end-time')+'</span><br>';
        html += '<span class="tranername"> Trainer Name - '+instance.data('trainer-name')+'</span>';
        var date = $('input[name="currentEventDate"]').val();
        if(date < moment().format('YYYY-MM-DD')){
            html += '</div></div>';
        }else{
            html += '</div></div><a href="javascript:void(0)" data-event-id="'+instance.data('event-id')+'" class="addsessionbtn btn-primary">Book a session</a>';
        }
        sessionElement.append(html);
        sessionElement.show();
    }
})
/** End: Event Detail of available time slot **/

$('body').on('click','.timesession',function(){
    $('#sessionDetails').hide();
    $('.timesession').removeClass('active');
    $(this).addClass('active');
    if($(this).hasClass('timesessionAm')){
        $('#eventTimingsPm').hide();
        $('#eventTimingsAm').show();
        
    }else{
        $('#eventTimingsPm').show();
        $('#eventTimingsAm').hide();
    }
});

function resetData(){
    $('.timesession').removeClass('active');
    $('.timesessionAm').addClass('active');
    $('#eventTimingsPm').hide();
    $('#eventTimingsAm').show();
    $('#sessionDetails').hide();
}

/** Start: Book client in session **/
$('body').on('click','.addsessionbtn',function(){
    var formData = {};
    var url = '';
    var isReschedule = false;
    if(rescheduleSessionMode){
        isReschedule = true;
        rescheduleClients[loggedInUser.id] = {note:"", isReducedRate:"0", ifRecur:"0", order:"1",isLdc:classClients[loggedInUser.id].isLdc};
        formData['classClients'] = rescheduleClients;
        formData['origEventId'] = origEventId;
        formData['targetEvents'] = "";
        url = public_url + 'staffevents/classes/reschedule-client';
    }else{
        url =  public_url+'clientevents/classes/create';
    }
    formData['eventId'] = $(this).data('event-id');
        toggleWaitShield('show');
        if(!isReschedule){
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
                            $.post(url, formData, function(data){
                                toggleWaitShield('hide');
                                var data = JSON.parse(data);
                                if(data.status == "added"){
                                    data.type = formData.bookType;
                                    if(rescheduleSessionMode){
                                        var message = data.orgMessage;
                                    }else{
                                        var message = data.message;
                                    }
                                    showClientBookedMsg(message)
                    
                                    signup_class_stat['resonopen'] = true;
                                    getStaffEvents();
                                    resetRescheduleMode();
                                    var currentMonth = $('input[name="currentMonthYear"]').val();
                                    renderEventsOfMonth(currentMonth,currentMonth);
                                }
                                else if(data.status == "error"){
                                    $.each(data.errorData, function(){
                                        $.each(this, function(errorType, message){
                                            $('#errorBox').append(message);
                                        });                         
                                    });
                                }else if (data.status == "clientBusy"){
                                    $('#errorBox').append(data.message);
                                }           
                            });
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
                        confirmButtonColor: '#ff4401',     
                    });
                }
            },'json');
        }else{
            $.post(url, formData, function(data){
                toggleWaitShield('hide');
                var data = JSON.parse(data);
                if(data.status == "added"){
                    data.type = formData.bookType;
                    if(rescheduleSessionMode){
                        var message = data.orgMessage;
                    }else{
                        var message = data.message;
                    }
                    showClientBookedMsg(message)
    
                    signup_class_stat['resonopen'] = true;
                    getStaffEvents();
                    resetRescheduleMode();
                    var currentMonth = $('input[name="currentMonthYear"]').val();
                    renderEventsOfMonth(currentMonth,currentMonth);
                }
                else if(data.status == "error"){
                    $.each(data.errorData, function(){
                        $.each(this, function(errorType, message){
                            $('#errorBox').append(message);
                        });                         
                    });
                }else if (data.status == "clientBusy"){
                    $('#errorBox').append(data.message);
                }           
            });
        }
});
/** End: Book client in session **/

/****** End: Booking in Session *********/

/****** Start: Open Session Modal ********/
$('body').on('click','.data_box',function(){
    toggleWaitShield('show');
    classModal = $(".class_schdule_model");
    var eventId = $(this).data('event-id');
    var eventType = $(this).data('event-type');
    var userId = $('.userId').val();
    if(eventType == 'single-service'){
        var invoice = getClientEventInvoiceAmountMobile(userId, eventId, 'service');
    }else{
        var invoice = getClientEventInvoiceAmountMobile(userId, eventId, eventType);
    }
    if(eventType == 'class'){
        $.get(public_url + 'clientevents/classes/' + eventId, function (data) {
            toggleWaitShield('hide');
            var isLdc = 0;
            $.each(data.clients,function(key,value){
                if(value.id == userId){
                    isLdc = value.isLdc;
                }
            });
            updateClassClients(data.clients);
            classModal.find('input[name="isLdc"]').val(isLdc);
            classModal.find('input[name="eventDate"]').val(data.date);
            if(moment(data.startDatetime).subtract(edit_time_limit, 'hours').format('YYYY-MM-DD HH:mm:ss')<moment().format('YYYY-MM-DD HH:mm:ss')){
                classModal.find('#evReschedule').addClass('hidden');
                classModal.find('#evAddEpicCredit').addClass('hidden');
                // if(isLdc){
                //     classModal.find('#evJustDeleteIt').removeClass('hidden');
                // }else{
                //     classModal.find('#evJustDeleteIt').addClass('hidden');
                // }
                
                if(moment(data.startDatetime).format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')){
                    classModal.find('#evError').removeClass('hidden').html('No changes allowed,This event is in past.');
                    classModal.find('#evJustDeleteIt').addClass('hidden');
                }else{
                    // classModal.find('#evError').removeClass('hidden').html('No changes allowed before '+edit_time_limit+' hours of booking .');
                    classModal.find('#evJustDeleteIt').removeClass('hidden');
                }
            }else{
                if((invoice != '' && invoice.inv_status != 'Paid' && invoice.inv_credit_used == 0)  || isLdc == '1')
                    classModal.find('#evAddEpicCredit').addClass('hidden');
                else
                    classModal.find('#evAddEpicCredit').removeClass('hidden');
                
                classModal.find('#evReschedule').removeClass('hidden');
                classModal.find('#evJustDeleteIt').removeClass('hidden');
                classModal.find('#evError').addClass('hidden').html();
            }
            classModal.find('.evType').text('Class');
            classModal.find('#evClassName').text(data.className);
            classModal.find('#evDuration').text(data.duration+' mins');
            classModal.find('#evStaff').text(data.staffName);
            var locationHtml = '';
            $.each(data.areas,function(key,value){
                locationHtml += '<p>'+value.locAreaName+'</p>';
            })
            classModal.find('#evLocation').html(locationHtml);
            classModal.find('input[name="mobEventId"]').val(data.id);
            classModal.find('input[name="mobEventType"]').val('class');
            classModal.fadeIn();
        }, 'json');
    }else if(eventType == 'single-service'){
        $.get(public_url + 'clientevents/single-service/' + eventId, function (data) {
            toggleWaitShield('hide');
            if(moment(data.startDatetime).subtract(edit_time_limit, 'hours').format('YYYY-MM-DD HH:mm:ss')<moment().format('YYYY-MM-DD HH:mm:ss')){
                classModal.find('#evReschedule').addClass('hidden');
                classModal.find('#evAddEpicCredit').addClass('hidden');
                // classModal.find('#evJustDeleteIt').addClass('hidden');
                if(moment(data.startDatetime).format('YYYY-MM-DD')<moment().format('YYYY-MM-DD')){
                    classModal.find('#evError').removeClass('hidden').html('No changes allowed,This event is in past.');
                    classModal.find('#evJustDeleteIt').addClass('hidden');
                }else{
                    // classModal.find('#evError').removeClass('hidden').html('No changes allowed before '+edit_time_limit+' hours of booking .');
                    classModal.find('#evJustDeleteIt').removeClass('hidden');
                }
            }else{  
                if((invoice != '' && invoice.inv_status == 'Paid') || (invoice == '' && data.membershipId != 0 && data.isLdc == 0) || (invoice != '' && invoice.inv_credit_used != 0))
                    classModal.find('#evAddEpicCredit').removeClass('hidden');
                else
                    classModal.find('#evAddEpicCredit').addClass('hidden');
                classModal.find('#evReschedule').removeClass('hidden');
                classModal.find('#evJustDeleteIt').removeClass('hidden');
                classModal.find('#evError').addClass('hidden').html();
            }
            classModal.find('.evType').text('Service');
            classModal.find('#evClassName').text(data.serviceName);
            classModal.find('#evDuration').text(data.duration+' mins');
            classModal.find('#evStaff').text(data.staffName);
            var locationHtml = '';
            $.each(data.areas,function(key,value){
                locationHtml += '<p>'+value.locAreaName+'</p>';
            })
            classModal.find('#evLocation').html(locationHtml);
            classModal.find('input[name="mobEventId"]').val(data.id);
            classModal.find('input[name="mobEventType"]').val('service');
            classModal.fadeIn();
        },'json');
    }
});

function activaTab(tab){
    $('#classTabs a[href="#' + tab + '"]').tab('show');
};
/****** End: Open Session Modal ********/

/***** Book Service *****/

// Swipe Js Starts Here
var bookServiceElement = document.getElementById('Bookservice');

// create a simple instance
// by default, it only adds horizontal recognizers
var bookService= new Hammer(bookServiceElement);
bookService.on("swipeleft swiperight", function(ev) {
    var currentDate = $('input[name="eventServiceDate"]').val();
    if(ev.type == 'swipeleft'){
        var nextDate = moment(currentDate,'ddd, D MMM YYYY').add(1, 'days').format('YYYY-MM-DD');
        if(nextDate <= maxDate){
            resetFormService();
            $('input[name="eventServiceDate"]').val(moment(nextDate,'YYYY-MM-DD').format('ddd, D MMM YYYY'));
            $('.serEventDay').text(moment(nextDate,'YYYY-MM-DD').format('D dddd'));
        }
    }else{
        var nextDate = moment(currentDate,'ddd, D MMM YYYY').subtract(1, 'days').format('YYYY-MM-DD');
        if(nextDate >= minDate){
            resetFormService();
            $('input[name="eventServiceDate"]').val(moment(nextDate,'YYYY-MM-DD').format('ddd, D MMM YYYY'));
            $('.serEventDay').text(moment(nextDate,'YYYY-MM-DD').format('D dddd'));
        }
    }
});

$('body').on('click','.left-arrow-btn,.right-arrow-btn',function(){
    var currentDate = $('input[name="eventServiceDate"]').val();
    if($(this).hasClass('left-arrow-btn')){
        var nextDate = moment(currentDate,'ddd, D MMM YYYY').subtract(1, 'days').format('YYYY-MM-DD');
        if(nextDate >= minDate){
            resetFormService();
            $('input[name="eventServiceDate"]').val(moment(nextDate,'YYYY-MM-DD').format('ddd, D MMM YYYY'));
            $('.serEventDay').text(moment(nextDate,'YYYY-MM-DD').format('D dddd'));
        }
   }else{
        var nextDate = moment(currentDate,'ddd, D MMM YYYY').add(1, 'days').format('YYYY-MM-DD');
        if(nextDate <= maxDate){
            resetFormService();
            $('input[name="eventServiceDate"]').val(moment(nextDate,'YYYY-MM-DD').format('ddd, D MMM YYYY'));
            $('.serEventDay').text(moment(nextDate,'YYYY-MM-DD').format('D dddd'));
        }
   }
});

/* Service Form Section */
$('body').on('click','#bookService',function(){
    if(rescheduleSessionMode){
        resetRescheduleMode();
    }
    $('.serEventDay').text(moment().format("D dddd"));
    $('input[name="eventServiceDate"]').val(moment().format('ddd, D MMM YYYY'));
    resetFormService();
    toggleWaitShield('show');
    $.get(public_url+'settings/business/services/all', function(data){  
        toggleWaitShield('hide');
        var options = '<option value="">-- Select --</option>';
        if(!$.isEmptyObject(data)){
            $.each(data, function(key, value){
                if(key != 'initial_status')
                    options += '<option value="'+value.id+'" data-salesStep="'+value.forSalesProcessStep+'" data-duration="'+value.duration+'" data-price="'+value.price+'">'+value.name+'</option>';

            });
        }
        var serviceDd = $('#Bookservice').find('select[name="services"]');
        serviceDd.html(options);
        serviceDd.selectpicker('refresh');
        $('input[name="eventServiceDate"]').val(moment().format("ddd, D MMM YYYY"));
        $('.eventDateDispMob').text(moment().format("ddd, D MMM YYYY"));
        getAllStaffsMobile('service');
        getAreasMobile('service');
    },'json');
});
/* Service Form Section */

/* Bootstrap Datetimepicker */
$('.serviceDateChange').datetimepicker({
    minDate: moment().millisecond(0).second(0).minute(0).hour(0), // Current day
    maxDate: moment().add(6, 'days'), // 6 days from the current day
    format: 'L',
    format: 'ddd, D MMM YYYY',
    defaultDate: moment(),
}).on('dp.change', function (e) {
    $('.serEventDay').text(e.date.format("D dddd")); 
    resetFormService();
}).on('dp.show', function(){
    var defaultSerDate = moment($('input[name="eventServiceDate"]').val()).format('YYYY-MM-DD');
    $('.serviceDateChange').data("DateTimePicker").date(new Date(defaultSerDate));
});

$('.sessionCalender').datetimepicker({
    minDate: moment().millisecond(0).second(0).minute(0).hour(0), // Current day
    maxDate: moment().add(6, 'days'), // 6 days from the current day
    format: 'L',
    format: 'YYYY-MM-DD',
    defaultDate: moment(),
}).on('dp.change', function (e) {
    var updatedDate = e.date.format("YYYY-MM-DD");
    renderEventsTimings(updatedDate);
    $('input[name="currentEventDate"]').val(updatedDate);
}).on('dp.show', function(){
    var defaultSerDate = moment($('input[name="currentEventDate"]').val()).format('YYYY-MM-DD');
    $('.sessionCalender').data("DateTimePicker").date(new Date(defaultSerDate));
});

$('#timepickerMob').datetimepicker({
    format:'LT',
    inline:true,
    useCurrent:false,
}).on('dp.change', function (e) {
    $('.timepickerMobInput').val(e.date.format("HH:mm:ss"));
    getAvailableStaff('service');
    getAreasMobile('service');
});

function resetTime(){
    var formattedDate = moment($('input[name="eventServiceDate"]').val()).format('YYYY-MM-DD');
    $('#timepickerMob').data("DateTimePicker").date(new Date(formattedDate+' 09:00:00'));
}

/* Bootstrap Datetimepicker */

function resetFormService(){
    var serviceDD = $('#Bookservice select[name="services"]');
    serviceDD.val('');
    serviceDD.selectpicker('refresh');
    $('.serGeneralSec').hide();
    $('#Bookservice').find("#iniStatMobile").html("");
    $('#Bookservice').find("#iniStatMobile").data('init-status',"");
    $('#Bookservice').find("#durupMobile").html("");
    $('#Bookservice').find("#priupMobile").html("");
    $('#Bookservice').find("#durupMobile").data('service-duration',"");
    $('#Bookservice').find("#priupMobile").data('service-price',"");
    resetTime();
    getAllStaffsMobile('service');
    $('.service_staff_check').empty();
    getAreasMobile('service');
}

$('#Bookservice select[name="services"]').change(function(){
    var $this = $(this);
    getAllStaffsMobile('service');
    $('.service_staff_check').empty();
    getAreasMobile('service');
    var service_val = $('#Bookservice select[name="services"]').val();
    if(service_val != '' && service_val != undefined){
        $('.serGeneralSec').show();
    }else{
        $('.serGeneralSec').hide();
    }
    $('#Bookservice').find("#iniStatMobile").html("Pencilled-In");
    $('#Bookservice').find("#iniStatMobile").data('init-status',"Pencilled-In");
    selOpt = $this.find(":selected");
    $('#Bookservice').find("#durupMobile").html("");
    $('#Bookservice').find("#priupMobile").html("");
    $('#Bookservice').find("#durupMobile").html(selOpt.data('duration')+" mins");
    $('#Bookservice').find("#durupMobile").data('service-duration',selOpt.data('duration'));
    $('#Bookservice').find("#priupMobile").html("$ "+selOpt.data('price'));
    $('#Bookservice').find("#priupMobile").data('service-price',selOpt.data('price'));
    getAllStaffsMobile('service');
    getAreasMobile('service');
});

$('#Bookservice select[name="staff"]').change(function(){
    getAvailableStaff('service');

});

function getSelectedStaffIds(){
    $('#Bookservice select[name="staff"]')
    var selectedStaffIds = [];
    $('#Bookservice select[name="staff"]').find('option:selected').each(function(){
        selectedStaffIds.push($(this).val());
    });
    return selectedStaffIds;
}

/* start: Get staff list based on the class/service */
function getAllStaffsMobile(bookingType){
    $.get(public_url+'staffs/all/', function(data){   
        populateStaffsMobile(data);
    },'json')
}

function getAvailableStaff(bookingType, callback){
    var classModal = $('#Bookservice');
    var formData = {};
    var entityDd = classModal.find('select[name="services"]');
    eventDate = $('input[name="eventServiceDate"]').val();
    var entityVal = entityDd.val();
    var duration = classModal.find('#durupMobile').data('service-duration');
    formData['date'] = moment(eventDate,'ddd, D MMM YYYY').format("YYYY-MM-DD");
    formData['day'] = moment(eventDate,'ddd, D MMM YYYY').format("dddd");
    formData['duration'] = duration;
    formData['time'] = classModal.find('input[name="eventTime"]').val();
    var url = 'services/';
    if(entityVal != '' && entityVal != undefined){
        toggleWaitShield('show');
        $.get(public_url+'settings/business/'+url+entityVal+'/free-staffs', formData, function(data){   
            toggleWaitShield('hide');
            populateAvailableStaffList(data);
        },'json');
    }       
}

function populateAvailableStaffList(data){
    var selectedStaffIds = getSelectedStaffIds();
    $('.service_staff_check').empty();
    var staffListHtml = '';
    $.each(data, function(key, value){
        if($.inArray(key, selectedStaffIds) != '-1'){
            staffListHtml +='<input type="radio" name="serviceStaff" value="'+key+'" id="staff'+key+'"><label for="staff'+key+'">'+value.name+' </label>' 
        }
    });
    $('.book_services').find('.service_staff_check').append(staffListHtml);
}

function getAreasMobile(bookingType){
    var classModal = $('#Bookservice');
    var entityDd = classModal.find('select[name="services"]');
    var areaDd = classModal.find('select[name="modalLocArea"]');
    setFieldNeutral(areaDd);
    var entityVal = entityDd.val();
    var duration = classModal.find('#durupMobile').data('service-duration');
    if(entityVal && duration){
        var formData = {};
        eventDate = $('input[name="eventServiceDate"]').val();
        formData['date'] = moment(eventDate,'ddd, D MMM YYYY').format("YYYY-MM-DD");
        formData['day'] = moment(eventDate,'ddd, D MMM YYYY').format("dddd");
        formData['time'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
        formData['duration'] = duration;
        var url = 'services/';   
        toggleWaitShield('show');
        //http://192.168.0.50/result/public/settings/business/services/12/free-areas
        $.get(public_url+'settings/business/'+url+entityVal+'/free-areas', formData, function(data){
            toggleWaitShield('hide');
            populateAreasMobile(data);
        },'json');
    }
    else
        populateAreasMobile();
}
/* start: Populate options in staff dropdown */
function populateStaffsMobile(data, callback){
    var classModal = $('#Bookservice');
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
    modalStaffDd.selectpicker('refresh');
}

function populateAreasMobile(data, callback){
    var classModal = $('#Bookservice');
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
    selectedAreas = getFavAreasMobile(ids);
    if(!selectedAreas.length){
        //Selected staff doesn't have favorite areas in the current areas list
        var areaId = 'all';
        if(areaId && areaId != 'all' && $.inArray(areaId, ids) >= 0){
            //filter has area selected and area list have filter selected area
            selectedAreas.push(areaId)
        }
    }
    areaDd.html(options);
    setAreasMobile(selectedAreas, span);

    if(!selectedAreas.length && options){
        var firstOption = areaDd.find('option:first').val();
        setAreasMobile([firstOption]);
    }
}

function getFavAreasMobile(areasId){
    var classModal = $('#Bookservice');
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

function setAreasMobile(selectedAreas, span){
    var classModal = $('#Bookservice');
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
    
    showAreasTextMobile(span);
    areaDd.selectpicker('refresh');
    //areaDd.closest('.set-group').find('.set-group-disp').prepend(span)
}

function showAreasTextMobile(span){
    var classModal = $('#Bookservice');
    var areaDd = classModal.find('select[name="modalLocArea"]'),
    areaSetGroupDisp = areaDd.closest('.set-group').find('.set-group-disp');

    areaSetGroupDisp.find('span').remove()
    areaSetGroupDisp.find('br').remove()
    areaSetGroupDisp.prepend(span)
}

var serviceClients = {};
$('body').on('click','#serviceSubmit',function(){
    toggleWaitShield('show');
    var classModal = $('#Bookservice');
    var formData = {};
    formData['bookType'] = "service";
    formData['staffservice'] = classModal.find('select[name="services"]').val();
    formData['serviceDur'] = classModal.find('#durupMobile').data('service-duration');
    formData['servicePrice'] = classModal.find('#priupMobile').data('service-price');
    formData['appointStatusOpt'] = classModal.find("#iniStatMobile").data('init-status');
    formData['staff'] = classModal.find('input[name="serviceStaff"]:checked').val();
    formData['modalLocArea'] = classModal.find('select[name="modalLocArea"]').val();
    formData['eventRepeat'] = '';
    eventDate = $('input[name="eventServiceDate"]').val();
    formData['classDate'] = moment(eventDate,'ddd, D MMM YYYY').format("YYYY-MM-DD");
    formData['classDay'] = moment(eventDate,'ddd, D MMM YYYY').format("dddd");
    formData['classTime'] = timeStringToDbTime(classModal.find('input[name="eventTime"]').val());
    formData['classNote'] = "";
    if (!$.isEmptyObject(defaultClientDetails)) {
        var loggedClientDetails = [];
        defaultClientDetails.forEach(function(client) {
            if(client.id == loggedInUser.id)
            loggedClientDetails = client;  
        }); 
        var data;
        var data = {id: loggedClientDetails.id, name: loggedClientDetails.name, phone: loggedClientDetails.phone, email: loggedClientDetails.email, isExisting: 1, note: ''};
        updateLinkedclientListMobile(data);
    }
    formData['classClients'] = serviceClients;
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
                    $.post(public_url+'clientevents/single-service/create', formData, function(data){
                        toggleWaitShield('hide');
                        var data = JSON.parse(data);
                        if(data.status == "added"){
                            data.type = formData.bookType;
                            showClientBookedMsg(data.message)
                
                            signup_class_stat['resonopen'] = true;
                            getStaffEvents();
                            var currentMonth = $('input[name="currentMonthYear"]').val();
                            renderEventsOfMonth(currentMonth,currentMonth);
                        }
                        else if(data.status == "error"){
                            $.each(data.errorData, function(){
                                $.each(this, function(errorType, message){
                                    $('#errorBoxSer').append(message);
                                });                         
                            });
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
                confirmButtonColor: '#ff4401',  
            }, 
            function(isConfirm){
                if(!isConfirm){
                    classModal.modal('hide');
                }
            });
        }
    },'json');
});

function updateLinkedclientListMobile(clientData){
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

    serviceClients[clientData['id']] = {note:clientData['note'], isReducedRate:clientData['isReducedRate'], ifRecur:clientData['ifRecur'], attendance:clientAttendance, isExisting:isExistingClient, ifMakeUp:0, makeupNotes:'', isCreatingMakeUpSession:isCreatingMakeUpSession, status:clientData['status'], order:clientLinkOrder}; 
    clientLinkOrder++;
}

function getClientEventInvoiceAmountMobile(clientId, eventId, bookingType) {
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

    return data.invoice;
}
/* end: Get client event invoice */

/**** Start:Delete Sessions ****/
$('body').on('click','#evJustDeleteIt',function(){
    swal({
        title: "Are you sure to delete this session?",
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        type : 'warning',
    }, function(isConfirm) {
        if(isConfirm) {
            var detailModal = $('.class_schdule_model');
            var eventType = detailModal.find('input[name="mobEventType"]').val();
            var formData = {};
            if(eventType == 'class'){
                formData['eventId'] = detailModal.find('input[name="mobEventId"]').val();
                formData['clientId'] = $('.userId').val();
                formData['isLdc'] = detailModal.find('input[name="isLdc"]').val();
                
            }else{
                formData['eventId'] = detailModal.find('input[name="mobEventId"]').val();
                formData['targetEvents'] = "this";
                formData['cancelReas'] = "";
                formData['isEpicCash'] = 'no';
            }
            deleteSession(formData,detailModal,eventType);
        }
    }); 
});

$('body').on('click','#evAddEpicCredit',function(){
    swal({
        title: "Are you sure to delete this session with Epic Credit?",
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        type : 'warning',
    }, function(isConfirm) {
        if(isConfirm) {
            var detailModal = $('.class_schdule_model');
            var formData = {};
            var eventType = detailModal.find('input[name="mobEventType"]').val();
            if(eventType == 'class'){
                formData['eventId'] = detailModal.find('input[name="mobEventId"]').val();
                formData['clientId'] = $('.userId').val();
                formData['isEpicCash'] = 'yes';
                formData['isLdc'] = detailModal.find('input[name="isLdc"]').val(); 
            }else{
                formData['eventId'] = detailModal.find('input[name="mobEventId"]').val();
                formData['targetEvents'] = "this";
                formData['cancelReas'] = "";
                formData['isEpicCash'] = 'yes';
            }
            deleteSession(formData,detailModal,eventType);
        }
    }); 
})

function deleteSession(formData,detailModal,eventType){
    var url = '';
    if(eventType == 'class'){
        url = public_url+'staffevents/classes/delete';
    }else{
        url = public_url+'clientevents/single-service-mobile/delete';
    }
    toggleWaitShield('show');
    $.post(url, formData, function(data){
        toggleWaitShield('hide');
        var data = JSON.parse(data);
        if(data.status == "deleted"){
            swal({
                title: data.message,
                type: "success",
                confirmButtonColor: "#ff4401",
                confirmButtonText: "Okay"
            }, function(isConfirm) {
                if(isConfirm) {
                    detailModal.fadeOut();
                    var currentMonth = $('input[name="currentMonthYear"]').val();
                    renderEventsOfMonth(currentMonth,currentMonth);
                }
            });
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
                    detailModal.fadeOut();
                    var currentMonth = $('input[name="currentMonthYear"]').val();
                    renderEventsOfMonth(currentMonth,currentMonth);
                }
            }); 
        }
    });
}

$('body').on('click','.crossicon',function(){
    toggleWaitShield('show');
    var eventElement = $(this).parent('li');
    var eventId = eventElement.find('.data_box').data('event-id');
    var eventType = eventElement.find('.data_box').data('event-type');
    var userId = $('.userId').val();
    if(eventType == 'single-service'){
        var invoice = getClientEventInvoiceAmountMobile(userId, eventId, 'service');
    }else{
        var invoice = getClientEventInvoiceAmountMobile(userId, eventId, eventType);
    }
    if(eventType == 'class'){
        $.get(public_url + 'clientevents/classes/' + eventId, function (data) {
            toggleWaitShield('hide');
            var isLdc = 0;
            $.each(data.clients,function(key,value){
                if(value.id == userId){
                    isLdc = value.isLdc;
                }
            });
            updateClassClients(data.clients);
            $('.mobile_calender_model').find('input[name="isLdc"]').val(isLdc);
            $('.mobile_calender_model').find('input[name="eventDate"]').val(data.date);
            if(moment(data.startDatetime).subtract(edit_time_limit, 'hours').format('YYYY-MM-DD HH:mm:ss')<moment().format('YYYY-MM-DD HH:mm:ss')){
                if(moment(data.startDatetime).format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')){
                    $('#ppError').removeClass('hidden').html('This event is in past, No changes allowed.');
                    $('.content_box').addClass('hidden');
                }else{
                    $('#creditMobAction').addClass('hidden');
                    $('#resMobAction').addClass('hidden');
                    $('#deleteMobAction').removeClass('hidden');
                    $('.content_box').removeClass('hidden');
                    $('#ppError').addClass('hidden').html();
                }
                
                // if(isLdc){
                //     $('#creditMobAction').addClass('hidden');
                //     $('#resMobAction').addClass('hidden');
                //     $('#deleteMobAction').removeClass('hidden');
                //     $('.content_box').removeClass('hidden');
                //     $('#ppError').addClass('hidden').html();
                // }else{
                //     $('#ppError').removeClass('hidden').html('No changes allowed before '+edit_time_limit+' hours of booking .');
                //     $('.content_box').addClass('hidden');
                // }
            }else{
                if((invoice != '' && invoice.inv_status != 'Paid' && invoice.inv_credit_used == 0) || isLdc == '1')
                    $('#creditMobAction').addClass('hidden');
                else
                    $('#creditMobAction').removeClass('hidden');
                
                $('#resMobAction').removeClass('hidden');
                $('#deleteMobAction').removeClass('hidden');
                $('.content_box').removeClass('hidden');
                $('#ppError').addClass('hidden').html();
            }
            $('input[name="ppEventId"]').val(data.id);
            $('input[name="ppEventType"]').val('class');
            $('.mobile_calender_model').show();
        }, 'json');
    }else if(eventType == 'single-service'){
        $.get(public_url + 'clientevents/single-service/' + eventId, function (data) {
            toggleWaitShield('hide');
            if(moment(data.startDatetime).subtract(edit_time_limit, 'hours').format('YYYY-MM-DD HH:mm:ss')<moment().format('YYYY-MM-DD HH:mm:ss')){
                $('#creditMobAction').addClass('hidden');
                $('#resMobAction').addClass('hidden');
                $('#deleteMobAction').removeClass('hidden');
                $('.content_box').removeClass('hidden');
                // $('#ppError').removeClass('hidden').html('No changes allowed before '+edit_time_limit+' hours of booking .');
                // $('.content_box').addClass('hidden');
            }else{  
                if((invoice != '' && invoice.inv_status == 'Paid') || (invoice == '' && data.membershipId != 0 && data.isLdc == 0) || (invoice != '' && invoice.inv_credit_used != 0))
                    $('#creditMobAction').removeClass('hidden');
                else
                    $('#creditMobAction').addClass('hidden');
                $('#resMobAction').removeClass('hidden');
                $('#deleteMobAction').removeClass('hidden');
                $('.content_box').removeClass('hidden');
                $('#ppError').addClass('hidden').html();
            }
            $('input[name="ppEventId"]').val(data.id);
            $('input[name="ppEventType"]').val('service');
            $('.mobile_calender_model').show();
        },'json');
    }
});

$('body').on('click','#deleteMobAction',function(){
    swal({
        title: "Are you sure to delete this session?",
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        type : 'warning',
    }, function(isConfirm) {
        if(isConfirm) {
            var detailModal = $('.mobile_calender_model');
            var eventType = detailModal.find('input[name="ppEventType"]').val();
            var formData = {};
            if(eventType == 'class'){
                formData['eventId'] = detailModal.find('input[name="ppEventId"]').val();
                formData['clientId'] = $('.userId').val();
                formData['isLdc'] = detailModal.find('input[name="isLdc"]').val();
                
            }else{
                formData['eventId'] = detailModal.find('input[name="ppEventId"]').val();
                formData['targetEvents'] = "this";
                formData['cancelReas'] = "";
                formData['isEpicCash'] = 'no';
            }
            deleteSession(formData,detailModal,eventType);
        }
    }); 
});

$('body').on('click','#creditMobAction',function(){
    swal({
        title: "Are you sure to delete this session with Epic Credit?",
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        type : 'warning',
    }, function(isConfirm) {
        if(isConfirm) {
            var detailModal = $('.mobile_calender_model');
            var formData = {};
            var eventType = detailModal.find('input[name="ppEventType"]').val();
            if(eventType == 'class'){
                formData['eventId'] = detailModal.find('input[name="ppEventId"]').val();
                formData['clientId'] = $('.userId').val();
                formData['isEpicCash'] = 'yes';
                formData['isLdc'] = detailModal.find('input[name="isLdc"]').val(); 
            }else{
                formData['eventId'] = detailModal.find('input[name="ppEventId"]').val();
                formData['targetEvents'] = "this";
                formData['cancelReas'] = "";
                formData['isEpicCash'] = 'yes';
            }
            deleteSession(formData,detailModal,eventType);
        }
    }); 
})
/**** End:Delete Sessions ****/

function fetchClientEpicBalance(){
    $.get(public_url+'get-epic-balance', function(data){
        $('.mycredit_mobile span').text('$'+data);
    });
}

/**
 * Reschedule Session
 */
$('body').on('click','#evReschedule,#resMobAction',function(){
    var eventType = '';
    if($(this).hasClass('poppupAction')){
        origEventId = $('input[name = "ppEventId"]').val();
        eventType = $('input[name = "ppEventType"]').val();
        var eventModal = $('.mobile_calender_model');
    }else{
        origEventId = $('input[name = "mobEventId"]').val();
        eventType = $('input[name = "mobEventType"]').val();
        var eventModal = $('.class_schdule_model');
    }
    rescheduleSessionMode = true;
    if(eventType == 'class'){
        $("#resSessionMsg").removeClass('hidden');
        $("#resSessionMsg").find('span').text('Choose a class for the reschedule.');
        eventModal.fadeOut();
        var isLdc = eventModal.find('input[name="isLdc"]').val();
        var eventOrgDate = eventModal.find('input[name="eventDate"]').val();
       var startWeekDate = moment(eventOrgDate,'YYYY-MM-DD').startOf('isoWeek').format('YYYY-MM-DD');
        if(isLdc == '1' && startWeekDate < moment().format('YYYY-MM-DD')){
            var eventDate = moment().format('YYYY-MM-DD');
            minDate = eventDate;
            maxDate = moment(eventOrgDate,'YYYY-MM-DD').endOf('isoWeek').format('YYYY-MM-DD');
        }
        else if(isLdc == '1' && startWeekDate >= moment().format('YYYY-MM-DD')){
            var eventDate = startWeekDate;
            minDate = eventDate;
            maxDate = moment(eventOrgDate,'YYYY-MM-DD').endOf('isoWeek').format('YYYY-MM-DD');
        }
        else
            var eventDate = moment().format('YYYY-MM-DD');
        $('input[name="currentEventDate"]').val(eventDate);
        renderEventsTimings(eventDate);
        activaTab('Booksession');
    }else{
        $("#resSessionMsg").removeClass('hidden');
        $("#resSessionMsg").find('span').text('Choose a new time for this booking..');
        eventModal.fadeOut();
        $('#bookService,#bookSession,#mySession').addClass('hidden');
        $("#resService").removeClass('hidden');
        var eventDate = moment().format('DD, dddd');
        $('.listingdataService').empty();
        var dataHtml = '';
        var i = 1;
        for(i; i <= 6; i++){
            eventDate = moment().add(i,'days').format('DD, dddd');
            dataHtml += '<li class="eventTiming rescheduleService" data-schedule-date="'+moment().add(i,'days').format('YYYY-MM-DD')+'">'+eventDate+'</p></li>'
        }
        $('.listingdataService').append(dataHtml);
        activaTab('ResService');
    }  
});

function resetRescheduleMode(){
    rescheduleSessionMode = false;
    minDate = moment().format('YYYY-MM-DD');
    maxDate = moment().add(6,'days').format('YYYY-MM-DD');
    origEventId = '';
    $('#bookService,#bookSession,#mySession').removeClass('hidden');
    $("#resService").addClass('hidden');
    $("#resSessionMsg").addClass('hidden');
}

$("#resSessionMsg").find('a').click(function (e) {
    resetRescheduleMode();
    var currentMonth = $('input[name="currentMonthYear"]').val();
    renderEventsOfMonth(currentMonth,currentMonth);
});

$('body').on('click','#mySession',function(){
    if(rescheduleSessionMode){
        resetRescheduleMode();
    }
    let renderDate = moment().format('YYYY-MM-DD');
    getClientMobileCalendarData(renderDate);
});

$('body').on('click','.rescheduleService',function(){
    toggleWaitShield('show');
    var formData = {};
    formData['eventId'] = origEventId;
    formData['classDate'] = $(this).data('schedule-date');
    $.post(public_url+'clientevents/single-service-mobile/edit', formData, function(data){
        toggleWaitShield('hide');
        $('#errorBoxRes').empty();
        var data = JSON.parse(data);
            if(data.status == "added"){
                var message = data.message;
                showClientBookedMsg(message)
                getStaffEvents();
                resetRescheduleMode();
                var currentMonth = $('input[name="currentMonthYear"]').val();
                renderEventsOfMonth(currentMonth,currentMonth);
            }
            else if(data.status == "error"){
                $.each(data.errorData, function(){
                    $.each(this, function(errorType, message){
                        $('#errorBoxRes').append(message);
                    });                         
                });
            }     
    });
})
/**
 * End:Reschedule Session
 */

 $('body').on('click','.cancel_btn',function(){
    $('.class_schdule_model').fadeOut();
 })

 function updateClassClients(clientsData){
    $.each(clientsData,function(key,value){
        classClients[value.id] = {note:value['note'], isReducedRate:value['isReducedRate'], ifRecur:value['ifRecur'],ifMakeUp:0, makeupNotes:'', isLdc:value['isLdc'], status:value['status']};
    });   
 }