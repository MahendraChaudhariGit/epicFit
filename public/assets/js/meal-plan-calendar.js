/* Start: Initialize Calender data */
var public_url = $('meta[name="public_url"]').attr('content'),
	calendarSettingsData = JSON.parse($('input[name=calendarSettingInput]').val()),
    calendarSettings = calendarSettingsData.settings,
    calendar = $('#calendar'),
    checkdateObj = {date: 'none'},
    calendarFirstTime = true,
    scriptSource = 'calendar',
    headerHeight = calendar.closest(".app-content").find('header').height(),
    week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    mealplanmodal = $('#mealplanmodal'),
    mealPlanOptionModal = $('#mealoptionmodal'),
    selectedSlot = null;
/* End: Initialize Calender data */

/* Start: Document ready */
$(document).ready(function () {
	/* Initialize the calendar Default view (weekly,mothaly and daily)*/
    var h = Math.floor((calendarSettings.cs_intervals * 4) / 60),
            m = (calendarSettings.cs_intervals * 4) % 60;
    var intervels = h + ':' + m;
    
    var agendaVal = "";
    if (calendarSettings.cs_view == 'weekly')
        agendaVal = 'agendaWeek';
    else if (calendarSettings.cs_view == 'daily')
        agendaVal = 'agendaDay';
    else
        agendaVal = 'month';
    
    /* Start: Full calendar */
    calendar.fullCalendar({
        customButtons: {
            add: {
                text: ''
            },
            filter: {
                text: ''
            },
            titleDatepicker: {
                text: '',
                click: function () {
                    $('#datepicker').datepicker("show");
                }
            },
            jumpBack: {
                icon: 'left-double-arrow',
                click: function (e) {
                    e.stopPropagation();
                    $("#jumpBack .calJumper > a").dropdown("toggle");
                }
            },
            jumpforw: {
                icon: 'right-double-arrow',
                click: function (e) {
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

        defaultView: (Main.isMobileFn()) ? 'agendaDay' : agendaVal,

        /* Indicate Current Time */
        nowIndicator: true,

        allDaySlot: false,

        firstDay: calendarSettings.cs_first_day,

        /* Control interval between time slot in week view and day view */

        //slotLabelInterval: '01:00',
        slotLabelInterval: intervels,

        //slotDuration: '00:15:00',
        slotDuration: '00:' + calendarSettings.cs_intervals + ':00',

        slotLabelFormat: 'h:mma',

        /* Default initial time */
        minTime: calendarSettings.cs_start_time,

        /* Calendar Events */
        editable: true, 
        eventRender: function (event, element, view) {
            var contentElem = element.find(".fc-content"),
                    eventHtml = '<input type="hidden" name="type" value="' + event.type + '"><input type="hidden" name="eventId" value="' + event.id + '">',
                    iconHtml = '';

                if(event.type == 'Meal'){
                    iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Plan"><i class="fa fa-cutlery"></i></span>';
                    event.color = '#253746';
                }
                else{
                    iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Plan"><i class="fa fa-file-text-o"></i></span>';
                    event.color = '#ea7513';
                }

                eventHtml += '<div class="eventStatusIcon">'+ iconHtml +'</div>';

                var eventTitle = event.title;
                if (view.type == 'agendaDay') {
                    eventHtml += '<small>' + eventTitle +'</small><br/>';
                    
                } else if (view.type == 'agendaWeek') {
                    eventHtml += '<br/>' + '<small>' +  eventTitle + '</small><br/>';
                    
                } else if (view.type == 'month') {
                    eventHtml += '<strong style="line-height: 3">' +  eventTitle + '</strong><br>';
                }

                contentElem.addClass('calendEvent').html(eventHtml).css("min-height", "38px");

                var fontColor = invertColor(event.color);
                var colorwidth = (event.clientsCount / event.capacity) * 100;
                if (colorwidth < 20)
                    fontColor = '#000000';
                var backGroundColor = '-moz-linear-gradient(left,' + event.color + ', ' + event.color + ' ' + /*colorwidth*/ '100%, #ddd 1%)';
                contentElem.css({"background": backGroundColor});
                var backGroundColor = '-webkit-linear-gradient(left,' + event.color + ', ' + event.color + ' ' + /*colorwidth */'100%, #ddd 1%)';
                contentElem.css({"background": backGroundColor});

                contentElem.css({"border-top": event.color, "color": fontColor});
                if (iconHtml)
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
        viewRender: function (view, element) {
        	ifToday(view);
            getDbEvent();
            getWeekEvent();
            getDayEvent();

            if(view.type == 'month'){
                var todayCell = element.find('.fc-today');
                if(todayCell.length){
                    todayCell.addClass('today');
                }

                var dayCell = element.find('.fc-day');
                if(dayCell.length){
                    dayCell.addClass('addCustomEvent');
                }
            }
        },
        eventAfterAllRender: function (view) {
            stickyFilter();
            if (view.type == 'month'){
                $('.fc-content-skeleton tr > td').unbind('click.addCustomEvent').bind('click.addCustomEvent',function(e){
                    if(e.target.nodeName == 'TD'){
                        var $this = $(this),
                            row = $this.closest('.fc-row');

                        if($this.hasClass('fc-event-container')){
                            var date = $this.find('input[name="date"]').val();
                                cell = row.find('.fc-bg td[data-date="' + date + '"]');
                        } 
                        else{
                            var index = $this.index()
                            cell = row.find('.fc-bg td:eq(' + index + ')');
                        }
                        cell.trigger("click");
                    }
                });
            }

            calendar.find('h2').hide();
            var titleDatepicker = calendar.find('.fc-titleDatepicker-button');
            titleDatepicker.html(calendar.find('h2').text());

            var fcToolBar = calendar.find('.fc-toolbar'); 
            fcToolBar.addClass('custom-calendar');

            /* Add Shopping category btn in left side */
            fcToolBar.find('.fc-left').html('<button class="btn fc-button fc-state-default shopping-list-btn" type="button" style="color: #333 !important;"> Shopping List</button>');

            /* Intergrate datepicker into calendar and Initialize the datepicker */
            if (!calendar.find('#datepicker').length) {
                $('<input type="hidden" id="datepicker">').insertBefore(titleDatepicker);
                $('#datepicker').datepicker({
                    numberOfMonths: 2,
                    onSelect: function (dateText, inst) {
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
        },
        eventClick: function (calEvent, jsEvent, view) {
            toggleWaitShield('show');
            var form = mealplanmodal.find('form'),
                modal = $('#detatilModal');
            //form.find('input[name="eventId"]').val(calEvent.eventid);
            $.getJSON(public_url+'meal-planner/calendar/event/'+calEvent.eventid, function (response){
                if(response.status == 'success'){
                    //form.find('input[name="eventDate"]').val(response.date);
                    displayDeatils(calEvent.type, response);
                    modal.find('.addFromDetail').hide();
                    modal.find('.back-btn').hide();
                    modal.find('.done-btn').show();
                    modal.find('.modal-title').html(calEvent.type+' Details');
                    modal.modal("show");
                }
                toggleWaitShield('hide');
            });  
        }
        
	});
       
    /* Start: Full calender */

    /* Initializing jquery validation */
    initCustomValidator(); 

    /* Scrol window top postion */
    $(window).scroll(function(){
        stickyFilter(); 
    });

    /* Event click bind */
    $('body').on('click', '.addCustomEvent', function(e){
        e.preventDefault();
        e.stopPropagation();

        var date = $(this).data('date');
        selectedSlot = $(this);
        selectedSlot.css("background-color","#ccc");
        mealplanmodal.find('input[name="eventDate"]').val(moment(date).format('YYYY-MM-DD'));

        // getListData('mealDetail');
        mealPlanOptionModal.modal("show");
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

    /* meal plan modal open event */
    mealplanmodal.on('shown.bs.modal', function(){
       
    });

    /* meal plan modal close event */
    mealplanmodal.on('hidden.bs.modal', function(){
        if(selectedSlot != null)
            selectedSlot.css("background-color","transparent");

       mealplanmodal.find('input[name="eventId"]').val("");
       mealplanmodal.find('input[name="food_name"]').val("");
       mealplanmodal.find('input[name="meal_name"]').val("");
       mealplanmodal.find('#classTabs li:first').find('a').trigger("click");
       mealplanmodal.find('#classTabs').find('li').removeClass('hidden');
       mealplanmodal.find('#mealDetail').removeClass('hidden');
       mealplanmodal.find('#foodDetail').removeClass('hidden');
    });

    /* Tab switch event */
    $('#mealplanmodal').find('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        getListData();
    });

    /* Open shopping list */
    $('body').on('click','.shopping-list-btn', function(e) {
        var $this = $(this),
            viewName = getCalendarView();

    if($('#calendar').find('.fc-agendaDay-button').hasClass('fc-state-active')){
      date =   $('.fc-agendaDay-view').find('.fc-day-header').data('date');
      var d = moment(date);
      month =  moment(d, 'MM').format('MMMM'); 
      var startDate = moment(month, 'MMM').format('YYYY-MM-DD'),
      endDate =moment(month, 'MMM').clone().endOf('month').format('YYYY-MM-DD');
    }else{
    var text = $('.fc-titleDatepicker-button').html(),
    dateData = text.split(' ');
    var startDate = moment(dateData[1]+"-"+dateData[0], 'YYYY-MMM').format('YYYY-MM-DD'),
    endDate =moment(dateData[1]+"-"+dateData[0], 'YYYY-MMM').clone().endOf('month').format('YYYY-MM-DD');
    }
    window.location.href = public_url+"meal-planner/shopping-list?view="+viewName+"&startDate="+startDate+"&endDate="+endDate;
    });

});
/* End: Document ready */

/* Start: Get DB Event */
function getDbEvent(){
	var formData = {};
    calendar.fullCalendar('removeEvents');
	toggleWaitShield('show');
    formData['startDate'] = getCalendStartDate(); 
    formData['endDate'] = getCalendEndDate();
    $.get(public_url+'meal-planner/calendar/getEvent', formData, function (data) {
    	var events = [];
        var text = $('.fc-titleDatepicker-button').html();
        var agendaDayheader = '<a href="#" class="epic-tooltip tooltipstered" data-toggle="tooltip" rel="tooltip" title="' + text + '">' + text + '</a>';
        $('.fc-agendaDay-view').find('.fc-day-header').html(agendaDayheader).addClass('today');

        if (data.length) {
            $.each(data, function () {
				var eventStart = moment(this.startDatetime),
					eventEnd = moment(this.startDatetime);

                events.push({eventid: this.eventid, id: this.id, catid: this.catid, title: this.title, type: this.type, start: eventStart, end: eventEnd, color: '#96A6A6'});
            });
        }

        if (events.length && events[0] != ''){
            calendar.fullCalendar('addEventSource', events);
        }


        if(calendarFirstTime){
            calendarFirstTime = false;
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
            var alerts = calendar.children('.alert');

            var scrollPos = $(window).scrollTop()-alertSpace;
            $("body, html").animate({ 
                scrollTop:scrollPos+alertSpace
            }, 200);
        }
        /** Add Daily Log button**/
        $("#calendar .fc-day").append("<div class='open-modalp1' type='button' onclick='openModal(event)'><i class='fa fa-pencil'></i></div>");
        toggleWaitShield('hide');

    }, 'json');
}
/* End: Get DB Event */
var StatsModal = $('#myModal1');
function openModal(event){
    event.stopImmediatePropagation();
    resetStatisticModal();
    resetNutritionalForm();
    var eventDate = $( event.target ).parents('.fc-day').data('date');
	StatsModal.find('input[name="eventDate"]').val(eventDate);
	var formData = {}
	formData['eventDate'] = eventDate;
	toggleWaitShield('show');
	$.get(public_url+'get-statistics-data',formData,function(data){
		toggleWaitShield('hide');
		populateStatisticsData(data)
        $('#myModal1').modal('show');
	},'json');
    return false
}

/*Start: Function to check if current view contains today date and toggle today button */
function ifToday(view) {
    var startTimestamp = view.intervalStart.format('x'),
            emdTimestamp = view.intervalEnd.format('x'),
            currentTimestamp = moment().format('x'),
            todayBtn = calendar.find('.fc-today-button');

    if(currentTimestamp >= startTimestamp && currentTimestamp <= emdTimestamp){
        todayBtn.addClass('active');
    }
    else
        todayBtn.removeClass('active');
}
/*End: Function to check if current view contains today date and toggle today button */

/* Start: invert color */
function invertColor(hex) {
    function hexdec(hexString) {
        hexString = (hexString + '').replace(/[^a-f0-9]/gi, '')
        return parseInt(hexString, 16)
    }
    var r = 0.2126 * hexdec(hex.substr(1, 2)); //Converting to rgb and multiplying luminance
    var g = 0.7152 * hexdec(hex.substr(3, 2));
    var b = 0.0722 * hexdec(hex.substr(5, 2));
    return (r + g + b) < 100 ? '#ffffff' : '#000000';
}     
/* End: invert color */

/* Start: sticky filter */
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
            filterBar.width(calendar.width() - 10);
        }

}
/* End: sticky filter */

/* Satrt: scroll to time slot */
function scrollToTimeslot(element){
    setTimeout(function(){
        if(calendarSettings.cs_start_time != null)
            var todaySlot = element.find('tr[data-time="'+calendarSettings.cs_start_time+'"]');
        else
            var todaySlot = element.find('.fc-now-indicator');

        if(todaySlot.length){
            var headHeight = calendar.closest(".app-content").find('header').height();

            $('html, body').animate({
                scrollTop: (todaySlot.offset().top - headHeight)
            },0);
        }
    }, 100);
}
/* End: scroll to time slot */

/* Start: Calendar delete Event */
$('body').on('click', '#deleteEvent',function(){
    var id = $(this).data("id");
    var type = $(this).data("type");
    swal({
        title: 'Are you sure to remove this Meal?',
        allowOutsideClick: true,
        showCancelButton: true,
        confirmButtonText: 'Yes',
        confirmButtonColor: '#ff4401',
        cancelButtonText: "No"
    }, 
    function(isConfirm){
        if(isConfirm){
            toggleWaitShield("show");
            var formData = {};
            formData['id'] = id;
            formData['type'] = type;
            $.post(public_url+'meal-planner/calendar/delete-event', formData, function(resp){
                toggleWaitShield("hide");
                // var resp = JSON.parse(resp);
                if(resp.status == "ok"){
                    $('#detatilModal').modal('hide'); 
                    getDbEvent();
                    getWeekEvent();
                    getDayEvent();
                }else{
                   swal({
                        title: 'Error',
                        text: resp.message,
                        type: "error",
                        allowOutsideClick: true,
                    }); 
                }
            });
        }
    });
})
/* End: Calendar delete Event */

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

$('body').on('click','.addBtnCat',function(){
    var categoryType = $(this).data('meal-category');
    var catId = $(this).data('cat-id');
    var snackType = $(this).data('snack-type');
    mealplanmodal.find('input[name="eventCat"]').val(catId);
    if(snackType != '' && snackType != undefined){
        mealplanmodal.find('input[name="eventSnackType"]').val(snackType);
    }else{
        mealplanmodal.find('input[name="eventSnackType"]').val('0');
    }
    var eventDate = $(this).parent('.weekMeal').data('event-date');
    if(eventDate != '' && eventDate != undefined){
        mealplanmodal.find('input[name="eventDate"]').val(eventDate);
    }else{
        var eventDate = $(this).parent('.dayMeal').data('event-date');
        if(eventDate != '' && eventDate != undefined){
            mealplanmodal.find('input[name="eventDate"]').val(eventDate);
        }
    }
    getListData('mealDetail',categoryType);
    mealPlanOptionModal.modal('hide');
    mealplanmodal.modal("show");
});

$('body').on('click','#mealCustomBtn',function(){
    var eventDate = $(this).parent('.weekMeal').data('event-date');
    if(eventDate != '' && eventDate != undefined){
        mealplanmodal.find('input[name="eventDate"]').val(eventDate);
    }else{
        var eventDate = $(this).parent('.dayMeal').data('event-date');
        if(eventDate != '' && eventDate != undefined){
            mealplanmodal.find('input[name="eventDate"]').val(eventDate);
        }
    }
});

/**
 * New Meal Week and Day Section
 */
$('body').on('click','.fc-month-button',function(){
    $('.fc-view-container').show();
    $('#weekCalender').hide();
    $('#dayCalender').hide();
})

$('body').on('click','.fc-agendaDay-button',function(){
    $('.fc-view-container').hide();
    $('#weekCalender').hide();
    $('#dayCalender').show();
})

function getDayEvent(){
    toggleWaitShield("show");
    clearDayEvents();
    var startDate = getCalendStartDate();
    var endDate = getCalendEndDatePriorDate();
    renderCalendarTable(startDate,'dayCalender');
    formData = {}
    formData['startDate'] = startDate;
    formData['endDate'] = endDate;
    $.get(public_url+'/meal-planner/calendar/getEvent',formData,function(response){
        $.each(response,function(key,value){
            var date = moment(value.startDatetime).format('YYYY-MM-DD');
            var findClass = '';
            if(value.catType == 'Breakfast'){
               findClass = 'breakfastData';
            }else if(value.catType == 'Lunch'){
                findClass = 'lunchData';
            }else if(value.catType == 'Dinner'){
                findClass = 'dinnerData';
            }else if(value.catType == 'Snack'){
                if(value.snackType == '1')
                    findClass = 'morningSnackData';
                else if(value.snackType == '2')
                    findClass = 'eveningSnackData';
                else if(value.snackType == 3)
                    findClass = 'nightSnackData';
            }
            $('#dayCalender .mealtype-table').find('.'+findClass).each(function(){
                if($(this).data('event-date') == date){
                    var mealHtml = '';
                    mealHtml = '<div class="adddata" data-event-id="'+value.eventid+'" data-event-type="'+value.type+'">\
                                    <div class="eventStatusIcon">\
                                        <span class="epic-tooltip m-l-3 tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="top"><i class="fa fa-cutlery"></i></span>\
                                    </div>\
                                    <h4>'+value.title+'</h4>\
                                </div>';

                    $(this).find('.mealDeatil').append(mealHtml);
                }
            });
        });
        toggleWaitShield("hide");
    },'json');
}

function clearDayEvents(){
    $('#dayCalender .mealtype-table').find('.mealDeatil').empty();
    $('#dayCalender .custom-header tr').empty();
}

$('body').on('click','.fc-agendaWeek-button',function(){
    $('.fc-view-container').hide();
    $('#dayCalender').hide();
    $('#weekCalender').show();
});
function getWeekEvent(){
    toggleWaitShield("show");
    clearWeekEvents();
    var startDate = getCalendStartDate();
    var endDate = getCalendEndDatePriorDate();
    renderCalendarTable(startDate,'weekCalender');
    formData = {}
    formData['startDate'] = startDate;
    formData['endDate'] = endDate;
    $.get(public_url+'/meal-planner/calendar/getEvent',formData,function(response){
        $.each(response,function(key,value){
            var date = moment(value.startDatetime).format('YYYY-MM-DD');
            var findClass = '';
            if(value.catType == 'Breakfast'){
               findClass = 'breakfastData';
            }else if(value.catType == 'Lunch'){
                findClass = 'lunchData';
            }else if(value.catType == 'Dinner'){
                findClass = 'dinnerData';
            }else if(value.catType == 'Snack'){
                if(value.snackType == '1')
                    findClass = 'morningSnackData';
                else if(value.snackType == '2')
                    findClass = 'eveningSnackData';
                else if(value.snackType == 3)
                    findClass = 'nightSnackData';
                else
                    findClass = 'morningSnackData';
            }

            $('#weekCalender .mealtype-table').find('.'+findClass).each(function(){
                if($(this).data('event-date') == date){
                    var mealHtml = '';
                    mealHtml = '<div class="adddata" data-event-id="'+value.eventid+'" data-event-type="'+value.type+'">\
                                    <div class="eventStatusIcon">\
                                        <span class="epic-tooltip m-l-3 tooltipstered" rel="tooltip" data-toggle="tooltip" data-placement="top"><i class="fa fa-cutlery"></i></span>\
                                    </div>\
                                    <h4>'+value.title+'</h4>\
                                </div>';

                    $(this).find('.mealDeatil').append(mealHtml);
                }
            });
        });
        toggleWaitShield("hide");
    },'json');
}

function clearWeekEvents(){
    $('#weekCalender .mealtype-table').find('.mealDeatil').empty();
    $('#weekCalender .custom-header tr').empty();
}
$(".fc-day").append("<div class='open-modalp1' type='button' data-toggle='modal' data-target='#myModal1'><i class='fa fa-pencil'></i></div>");

function renderCalendarTable(startDate,calendarId){
    var i = 0;
    var tableHeader = $('#'+calendarId+' .custom-header tr');
    if(tableHeader.length){
        var headerContent = '';
        for(i;i <= 6;i++){
            headerContent += '<th>'+moment(startDate).add(i,'days').format('ddd DD MMM')+'<div class="open-modalp1 meal-week" type="button" data-toggle="modal" data-target="#myModal1" data-date="'+moment(startDate).add(i,'days').format('YYYY-MM-DD')+'"><i class="fa fa-pencil"></i></div></th>'
        }
        tableHeader.append(headerContent);
    }
    var j = 0;
    $('#'+calendarId+' .mealtype-table').each(function(){
        var date = moment(startDate).add(j,'days').format('YYYY-MM-DD');
        if(calendarId == 'weekCalender'){
            $(this).find('.weekMeal').each(function(){
                $(this).data('event-date',date);
            });
        }else{
            $(this).find('.dayMeal').each(function(){
                $(this).data('event-date',date);
            });
            $('.mealDayModal').data('date',date);
        }
        j = j + 1;
    });
}

$('body').on('click','.adddata',function(){
    toggleWaitShield('show');
    var form = mealplanmodal.find('form'),
        modal = $('#detatilModal');
    var eventType = $(this).data('event-type');
    $.getJSON(public_url+'meal-planner/calendar/event/'+$(this).data('event-id'), function (response){
        if(response.status == 'success'){
            //form.find('input[name="eventDate"]').val(response.date);
            displayDeatils(eventType, response);
            modal.find('.addFromDetail').hide();
            modal.find('.back-btn').hide();
            modal.find('.done-btn').show();
            modal.find('.modal-title').html(eventType+' Details');
            modal.modal("show");
        }
        toggleWaitShield('hide');
    });
});


$('body').on('click','#custom',function(e){
    e.preventDefault();
    var formData = {};
    var form = $('#customMealForm');
    form.validate({
  rules: {
    quantity: {
      required: true,
      number: true
    }
  }
});
    var isFormValid = form.valid();

    if(isFormValid){
        toggleWaitShield('hide');
        $('#mealoptionmodal').modal('hide');

        modal = $('#customMealplanmodal');
        formData = {};
        formData['recipeName'] = modal.find('input[name="recipe_name"]').val();
        formData['ingredients'] = modal.find('textarea[name="ingredients"]').val();
        formData['quantity'] = modal.find('input[name="quantity"]').val();
        formData['serving_size'] = modal.find('textarea[name="serving_size"]').val();

        var catElement = modal.find('select[name="cat_id"]');
        formData['catId'] = catElement.val();
        var isSnack = modal.find('select[name="cat_id"] option:selected').data('is-snack');
        formData['isSnack'] = isSnack;
        if(isSnack){
            formData['snackType'] = modal.find('select[name="cat_id"] option:selected').data('snack-type');
        }

        formData['eventDate'] = $('#mealplanmodal').find('input[name="eventDate"]').val();
        formData['isCustom'] = 1;
         $.post(public_url+'meal-planner/calendar/store',formData, function (response)
         {
            var response = JSON.parse(response);
            toggleWaitShield('hide');
            if(response.status == 'success'){
                modal.modal('hide');
                getDbEvent();
                getWeekEvent();
                getDayEvent();
            }else{
                swal({
                    title: 'Error',
                    text: "Something went wrong",
                    type: "error",
                    allowOutsideClick: true,
                });
            }
        });
    }

});

$('#customMealplanmodal').on('shown.bs.modal', function () {
     modal = $('#customMealplanmodal');
     modal.find('input[name="recipe_name"]').val('');
     modal.find('textarea[name="ingredients"]').val('');
     modal.find('input[name="quantity"]').val('');
     modal.find('textarea[name="serving_size"]').val('');


})

$('body').on('click','.saveCustomFormModal',function(){
    if($('#customFormModal').valid()){
        var formData = {};

        formData['is_custom'] = 2;
        formData['eventDate'] = $('#mealplanmodal').find('input[name="eventDate"]').val();
        formData['cat_id'] = $('#customMealplanmodal').find('#catId').val();
        $('#customMealplanmodal').find('#catId option:selected');
        formData['isSnack'] = $('#customMealplanmodal').find('#catId option:selected').data('is-snack');
        if(formData['isSnack']){
            formData['snackType'] = $('#customMealplanmodal').find('#catId option:selected').data('snack-type');
        }
        formData['time_opt'] = $('#customMealplanmodal').find('#custom_time_opt').val();
        if(formData['time_opt'] == 'automatic'){
            formData['nutritionalTime'] = $('#customMealplanmodal').find('#automaticTime').val();
        }else{
            formData['nutritionalTime'] = $('#customMealplanmodal').find('#time_hour').val() + ":" + $('#customMealplanmodal').find('#time_min').val()
        }
        formData['hungerRate'] = $('#customMealplanmodal').find('input[name="hunger_rate"]').val();
        formData['recipeName'] = $('#customMealplanmodal').find('input[name="recipe_name"]').val();
        var ingQuantityData = [];
        $('#customMealplanmodal').find('.row-box .form-group').each(function(){
            ingQuantityData.push({ingredient:$(this).find('input[name="ingredients"]').val(),quantity:$(this).find('input[name="quantity"]').val()});
        });
        formData['ingQuantityData'] = ingQuantityData;
        formData['servingSize'] = $('#customMealplanmodal').find('select[name="serving_size"]').val();
        formData['activityLabel'] = $('#customMealplanmodal').find('textarea[name="activity_label"]').val();
        formData['generalNotes'] = $('#customMealplanmodal').find('textarea[name="general_notes"]').val();
        formData['clickedImage'] = $('#customMealplanmodal').find('#clickedPic').val();
        formData['mealRating'] = $('#customMealplanmodal').find('select[name="meal_rating"]').val();
        formData['mealEnjoyed'] = $('#customMealplanmodal').find('select[name="enjoyed_meal"]').val();
        
        toggleWaitShield('show');
        
        $.post(public_url+'/store-nutritional-data',formData,function(response){
            toggleWaitShield('hide');
            if(response.status == 'ok'){
                $('#customMealplanmodal').modal('hide');
                $('#mealoptionmodal').modal('hide');
                swal({
                    type: 'success',
                    title: 'Success!',
                    showCancelButton: false,
                    allowOutsideClick: false,
                    text: 'Data saved successfully',
                    showConfirmButton: true,     
                }, 
                function(isConfirm){
                if(isConfirm)
                {

                getDbEvent();
                getWeekEvent();
                getDayEvent();
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
                    if(!isConfirm)
                        StatsModal.modal('hide');
                });
            }
        },'json')
    }
});
$('body').on('click','.addMoreCustomRow',function(){
    var html = '<div class="form-group">\
        <div class="row">\
            <div class="col-md-8">\
                <label class="strong">Ingredient</label>\
                <input type="text" class="form-control" name="ingredients" required>\
            </div>\
            <div class="col-md-2">\
                <label class="strong">Quantity </label>\
                <input type="text" name="quantity" class="form-control" required>\
            </div>\
            <div class="col-md-2">\
                <button type="button" class="btn btn-primary crossbtn"><i class="fa fa-times" aria-hidden="true"></i></button>\
            </div>\
        </div>\
    </div>';
    $('#customMealplanmodal').find('.row-box').append(html);
});


$('body').on('click', '#hungerCustomMinus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    count = count < 1 ? 1 : count;
    $input.val(count);
    $input.trigger('input');
    $('#hungerCustomSection').find('.hunger-custom-range-value').text(count);
    return false;
});
$('body').on('click', '#hungerCustomplus', function(){
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) + 1;
    count = count > 10 ? 10 : count;
    $input.val(count);
    $input.trigger('input');
    $('#hungerCustomSection').find('.hunger-custom-range-value').text(count);

    return false;
});
$(document).on('input', '.hunger_custom_rate', function() {
    var count = $(this).val();
    $('#hungerCustomSection').find('.hunger-custom-range-value').text(count);
});

 $('body').on('click', '#customMealplanmodal .manual_time', function(){
        $('#customMealplanmodal .automatic_time').removeClass('active');
        $('#customMealplanmodal .manual_time').addClass('active');
        $('#customMealplanmodal .add_time_manual').show();
    });

    $('body').on('click', '#customMealplanmodal .automatic_time', function(){
        $('#customMealplanmodal .manual_time').removeClass('active');
        $('#customMealplanmodal .automatic_time').addClass('active');
        $('#customMealplanmodal .add_time_manual').hide();
    });