/* Start: Initialize Calender data */
var interval;
var currentVideoPlayBackTime = 0;
var videoDuration = 0;
var totalPlayTime = 0;
var replayCount = 0;
var currentTime = 0;
var videoPlayed = 0;
var public_url = $('meta[name="public_url"]').attr('content'),
calendarSettings = JSON.parse($('input[name=calendarSettingInput]').val()),
calendar = $('#calendarActivity'),
checkdateObj = {date: 'none'},
calendarFirstTime = true,
scriptSource = 'calendar',
headerHeight = calendar.closest(".app-content").find('header').height(),
week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
/* End: Initialize Calender data */

/* Start: Document ready */
$(document).ready(function () {

    /* Set gender in API */
    // FX.setGender($('input[name="gender"]').val());
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
        scrollTime: calendarSettings.cs_start_time,
        //scrollTime:'09:30';

        /* Calendar Events */
        editable: true, 
        eventRender: function (event, element, view) {
            if(event.pdf && event.type == "client-pdf"){
               var url = BASE_URL+'/uploads/'+event.pdf;
            }
            console.log('event---', event);
            console.log('event.type', event.type);
            console.log('view---', view);
            var contentElem = element.find(".fc-content"),
                eventHtml = '<input type="hidden" name="type" value="' + event.type + '"><input type="hidden" name="eventId" value="' + event.id + '">',
                iconHtml = '';

            iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Plan"><i class="fa fa-heartbeat"></i></span>';
            eventHtml += '<div class="eventStatusIcon">' + iconHtml + '</div>';

            var eventTitle = event.title;
            if (view.type == 'agendaDay') {
                eventHtml +='<strong>' + moment(event.start).format("h:mm A") + ' - ' + moment(event.end).format("h:mm A") +'</strong> ';
                if(event.type == "client-pdf"){
                    eventHtml += '<small><a class="pdf-popup-open" data-url="'+url+'" style="color: black !important;" >' + eventTitle +'</a></small><br/>';
                  } else{
                    eventHtml += '<small>' + eventTitle +'</small><br/>';   
                  }
               
                
            } else if (view.type == 'agendaWeek') {
                eventHtml += '<strong>' + moment(event.start).format("h:mm A") + ' - ' + moment(event.end).format("h:mm A") +'</strong>';
                if(event.type == "client-pdf"){
                    eventHtml += '<br/>' + '<small><a class="pdf-popup-open" data-url="'+url+'"  style="color: black !important;">' +  eventTitle + '</a></small><br/>';
                  } else{
                    eventHtml += '<br/>' + '<small>' +  eventTitle + '</small><br/>';
                  } 
                
            } else if (view.type == 'month') {
                // eventHtml += '<strong><span class="eventTimeRange">' + moment(event.start).format("h:mm A") + ' - ' + moment(event.end).format("h:mm A") + '</span></strong><br/>' + eventTitle;
                eventHtml += '<strong><span class="eventTimeRange" style="color: white !important;">' + moment(event.start).format("h:mm A") + ' - ' + moment(event.end).format("h:mm A") + '</span></strong>';
                if(event.type == "client-pdf"){
                    eventHtml +=  '<br/>' + '<span><a class="pdf-popup-open" data-url="'+url+'"  style="color: black !important;">' + eventTitle +'</a></span>';
                  } else{
                    eventHtml += '<br/>' + '<span>' +  eventTitle + '</span>';
                  } 
                // eventHtml +=  '<br/>' + '<span><a class="pdf-popup-open" data-url="'+url+'" >' + eventTitle +'</a></span>';
            }

            contentElem.addClass('calendEvent').html(eventHtml);
            event.color = '#7e8085';

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
            getClientPlan();
            var datepicker = calendar.find('#datepicker');
            if(datepicker.length){
                datepicker.val(view.intervalStart.format('MM')+'/'+view.intervalStart.format('DD')+'/'+view.intervalStart.format('YYYY'));
            }
        
        },
        eventAfterAllRender: function (view) {
            stickyFilter();
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
            /*if (!titleDatepicker.hasClass('btn-wide'))
                titleDatepicker.addClass('btn-wide')*/

            calendar.find('.fc-toolbar').addClass('custom-calendar');

            /* Intergrate datepicker into calendar and Initialize the datepicker */
            if (!calendar.find('#datepicker').length) {
                $('<input type="hidden" id="datepicker">').insertBefore(titleDatepicker);
                console.log(titleDatepicker);

                $('#datepicker').datepicker({
                    numberOfMonths: 2,
                    onSelect: function (dateText, inst) {
                        console.log(dateText);
                        calendar.fullCalendar('gotoDate', new Date(dateText));
                    }
                });
            }

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
        eventClick: function (calEvent, jsEvent, view) {
         if(calEvent.type != 'client-pdf'){
            toggleWaitShield('show');
            var modal = $('#activityModal'),
            saveDateTrainingSeg = modal.find('#saveDateTrainingSeg');
                
            modal.find('.modal-title').html(calEvent.title);
            modal.find('.panel').hide();
            modal.find('input[name="clientplan_id"]').val(calEvent.id);
            modal.find('input[name="date_id"]').val(calEvent.dateid);
            // modal.find('input[name="type"]').val(calEvent.dateid);
            if(calEvent.start._i>moment().format("YYYY-MM-DD HH:mm:ss"))
                saveDateTrainingSeg.data('event-time','future');
            else
                saveDateTrainingSeg.data('event-time','past'); 
            FX.setPlanId(calEvent.id);
            FX.setDateId(calEvent.dateid);
            FX.GetWorkoutWithExercise(modal);
          } 
        }
        
    });


    /* Start: Full calender */

    /* Initializing jquery validation */
    initCustomValidator();
});

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
/* Start: Get Client plan */
function getClientPlan(){
    var formData = {};
    calendar.fullCalendar('removeEvents');
    toggleWaitShield('show');
    var clientId = $('input[name="clientId"]').val();
    formData['clientId'] = clientId;
    $.get(public_url+'CustomPlan/activity/date/plan', formData, function (data) {
        var events = [];
        var text = $('.fc-titleDatepicker-button').html();
        var agendaDayheader = '<a href="#" class="epic-tooltip tooltipstered" data-toggle="tooltip" rel="tooltip" title="' + text + '">' + text + '</a>';
        $('.fc-agendaDay-view').find('.fc-day-header').html(agendaDayheader).addClass('today');

        if (data.length) {
            $.each(data, function () {
                var eventStart = moment(this.startDatetime),
                    eventEnd = moment(this.endDatetime);

                events.push({type: this.type, id: this.id, title: this.title, start: eventStart, end: eventEnd, dateid: this.dateid, color: '#96A6A6'});
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
        $("#calendarActivity .fc-day-number").find('.addActivityPlan').remove();
        $("#calendarActivity .fc-day-number").append("<i class='fa fa-plus fa-lg pull-left addActivityPlan'></i>");
        $(".fc-month-view .fc-day").append("<div class='open-modalp1' type='button' data-toggle='modal' data-target='#myModal1'><i class='fa fa-pencil'></i></div>");
        $(".fc-agendaWeek-view .fc-day-header").append("<div class='open-modalp1' type='button' data-toggle='modal' data-target='#myModal1'><i class='fa fa-pencil'></i></div>");
        $(".fc-agendaDay-view").prepend("<div class='open-modalp1' type='button' data-toggle='modal' data-target='#myModal1'>Add Daily log</div>");
        toggleWaitShield('hide');

    }, 'json')
}
/* ENd : Get Client Plan */

/*Start: Function to check if current view contains today date and toggle today button */
function ifToday(view) {
    var startTimestamp = view.intervalStart.format('x'),
            emdTimestamp = view.intervalEnd.format('x'),
            currentTimestamp = moment().format('x'),
            todayBtn = calendar.find('.fc-today-button');

    if (currentTimestamp >= startTimestamp && currentTimestamp <= emdTimestamp){
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

        var headerHeight = scrolled-scrollable
    }
    var filterBar = calendar.children('.fc-toolbar');

    if(scrolled > scrollable) //Stick filter
        filterBar.addClass('fixed').css({top:headerHeight})
    else
        filterBar.removeClass('fixed')

    filterBar.width(calendar.width()-20)
    // console.log(headerHeight);
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
            // if(!isSalesProcessMode() && isCreateServiceMakeUpMode())
            if(!isSalesProcessMode() )
                var headHeight = calendar.closest(".app-content").find('header').height();
            else
                var headHeight = 0;

            $('html, body').animate({
                scrollTop: (todaySlot.offset().top - headHeight)
            },0);
        }
    }, 100);
}
/* End: scroll to time slot */
/* End: Document ready */

/*****open step by step****/

function playVideoAccordingCondition(){
    $("#activityVideoCarousal .owl-item").each(function(key,obj){
      var $this = $(this);
      if(key == 0){
        if($(this).find('.ban_video').data('is-rest') == '0'){
          $(this).find('.ban_video')[0].play();
          $(".play-bt").hide();
          $(".pause-bt").show();
        }else{
          var timeleft =  $(this).find('.ban_video').data('duration');
          interval = setInterval(function(){
            if(timeleft <= 0){
              clearInterval(interval);
              myHandler();
            }
            $this.find('.image-duration').text(timeleft);
            timeleft -= 1;
          }, 1000);
        }
      }
    });
    var videoPlayedTime = 0;
    $(".ban_video").on(
      "timeupdate", 
      function(event){
        videoPlayedTime = parseInt(this.currentTime);
        videoDuration = parseInt(this.duration);
        var activeElement = $("#activityVideoCarousal .owl-item.active");
        var trainingSegment = activeElement.find('.ban_video').data('training-segment');
        var exeId = activeElement.find('.ban_video').data('exe-id');
        var count = activeElement.find('.ban_video').data('count');
        var setPanel = $("#seg_"+trainingSegment).find('.panel a[href="#content-'+trainingSegment+exeId+count+'"]');
        var length = setPanel.closest('.panel-default').find('.setRow').length;
        if(currentTime != videoPlayedTime){
          currentTime = videoPlayedTime;
          if(videoPlayedTime == videoDuration){
            console.log('here');
            replayCount = replayCount + 1;
          }else{
            currentVideoPlayBackTime = ((replayCount * videoDuration)  + videoPlayedTime) - videoPlayed;
          }
        }
        setPanel.closest('.panel-default').find('.setRow').each(function(key,element){
          $this = $(this);
          if($(this).data('is-finished') == '0'){
            var playTime = parseInt($(this).data('set-duration'));
            if(playTime > 0){
              if(playTime == currentVideoPlayBackTime){
                var videoElement = activeElement.find('.ban_video')[0];
                videoElement.pause();
                currentVideoPlayBackTime = 0;
                activeElement.find('.item .video-duration').text('0');
                var timeleft = $(this).data('rest-duration');
                interval = setInterval(function(){
                  activeElement.find('.video-loader').show();
                  if(timeleft <= 0){
                    activeElement.find('.video-loader').hide();
                    clearInterval(interval);
                    $this.data('is-finished','1');
                    replayCount = 0;
                    if(key === (length - 1)){
                      if(!$('.owl-next').hasClass('disabled')){
                        $('.owl-next').trigger('click');
                      }
                      videoPlayed = 0;
                    }else{
                      videoElement.play();
                      videoPlayed = videoPlayedTime;
                    }
                  }
                  activeElement.find('.item .video-duration').text(timeleft);
                  timeleft -= 1;
                }, 1000);
              }else{
                activeElement.find('.item .video-duration').text(playTime - currentVideoPlayBackTime);
              }
              return false;
            }
          }
        });
          
    });
  }

// video player JS starts here
$(document).ready(function(){
    $(".play-bt").click(function(){
        $(".play-bt").hide();
        $(".pause-bt").show();
        var activeVideo = $("#activityVideoCarousal .owl-item.active");
        var videoElement = activeVideo.find('.ban_video')[0];
        videoElement.play();                                    
    });
    $(".pause-bt").click(function(){
        $(".play-bt").show();
        $(".pause-bt").hide();
        $(".pause-bt").addClass("active");
        var activeVideo = $("#activityVideoCarousal .owl-item.active");
        var videoElement = activeVideo.find('.ban_video')[0];
        videoElement.pause();                                         
    });
    var owl = $('#activityVideoCarousal');
    // Listen to owl events:
    owl.on('changed.owl.carousel', function(event) {
        currentVideoPlayBackTime = 0;
        currentTime = 0;
        var activeVideo = $("#activityVideoCarousal .owl-item.active");
        var isRest = activeVideo.find('.ban_video').data('is-rest');
        var trainingSegment = activeVideo.find('.ban_video').data('training-segment');
        var exeId = activeVideo.find('.ban_video').data('exe-id');
        var count = activeVideo.find('.ban_video').data('count');
        var setPanel = $("#seg_"+trainingSegment).find('.panel a[href="#content-'+trainingSegment+exeId+count+'"]');
        setPanel.closest('.panel-default').find('.setRow').each(function(key,element){
            $(this).data('is-finished','0');
        });
        if(isRest == '0'){
            var videoElement = activeVideo.find('.ban_video')[0];
            if(videoElement != undefined){
                videoElement.pause();
                $(".play-bt").show();
                $(".pause-bt").hide();
            }
        }
    });

    $('body').on('click','.owl-prev,.owl-next',function(){
        clearInterval(interval);
        currentVideoPlayBackTime = 0;
        currentTime = 0;
        replayCount = 0;
        var activeElement = $("#activityVideoCarousal .owl-item.active");
        var trainingSegment = activeElement.find('.ban_video').data('training-segment');
        var exeId = activeElement.find('.ban_video').data('exe-id');
        var count = activeElement.find('.ban_video').data('count');
        var accordianElement = $('#caledar-exe-accordion').find("#seg_"+trainingSegment).closest('.panel').find('a[href="#seg_'+trainingSegment+'"]');
        if(accordianElement.hasClass('collapsed')){
            accordianElement.trigger('click');
        }
        if($("#seg_"+trainingSegment).find('.panel a[href="#content-'+trainingSegment+exeId+count+'"]').hasClass('collapsed')){
            $("#seg_"+trainingSegment).find('.panel a[href="#content-'+trainingSegment+exeId+count+'"]').trigger('click');
        }
        playVideo(activeElement);
    });
    $('#activityModal').on('hide.bs.modal',function(e) {
        clearInterval(interval);
        currentVideoPlayBackTime = 0;
        currentTime = 0;
        replayCount = 0;
        var activeElement = $("#activityVideoCarousal .owl-item.active");
        var isRest = activeElement.find('.ban_video').data('is-rest');
        if(isRest == '0'){
            var videoElement = activeElement.find('.ban_video')[0];
            if(videoElement != undefined){
                videoElement.pause();
            }
        }
    });
});

function myHandler() {
    if(!$('.owl-next').hasClass('disabled')){
      if(currentVideoPlayBackTime == 0){
        $('.owl-next').trigger('click');
      }else{
        var activeElement = $("#activityVideoCarousal .owl-item.active");
        playVideo(activeElement);
      }
    }else{
      if(currentVideoPlayBackTime != 0){
        var activeElement = $("#activityVideoCarousal .owl-item.active");
        if(activeElement.find('.ban_video').data('is_rest') == 0){
          playVideo(activeElement);
        }
      }
    }
  }
  
  function playVideo(activeElement){
    var isRest = activeElement.find('.ban_video').data('is-rest');
    if(isRest == '0'){
        var videoElement = activeElement.find('.ban_video')[0];
        if(videoElement != undefined){
            videoElement.currentTime = 0;
            videoElement.play();
        }
        $(".play-bt").hide();
        $(".pause-bt").show();
    }else{
        var timeleft = activeElement.find('.ban_video').data('duration');
        interval = setInterval(function(){
        if(timeleft <= 0){
            clearInterval(interval);
            myHandler();
        }
        activeElement.find('.image-duration').text(timeleft);
        timeleft -= 1;
        }, 1000);
    }
  }
