/* Start : rescoureNotFound */
if ((!$('#rescoureNotFound').length) || 1==1) {

	/* Start: Initialize Calender data */
    var public_url = $('meta[name="public_url"]').attr('content'),
    	calendarSettings = JSON.parse($('input[name=calendarSettingInput]').val()),
        calendar = $('#calendar'),
        checkdateObj = {date: 'none'},
        calendarFirstTime = true,
        scriptSource = 'calendar',
        headerHeight = calendar.closest(".app-content").find('header').height(),
        week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	/* End: Initialize Calender data */

	/* Start: Document ready */
    $(document).ready(function () {

        /* Set gender in API */
        FX.setGender($('input[name="gender"]').val());

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
                var contentElem = element.find(".fc-content"),
                    eventHtml = '<input type="hidden" name="type" value="' + event.type + '"><input type="hidden" name="eventId" value="' + event.id + '">',
                    iconHtml = '';
                if(event.type == 'client-plan'){
                iconHtml += '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Plan"><i class="fa fa-heartbeat"></i></span>';
                }

                if(event.pdf != undefined){
                    eventHtml += `<input type="hidden" name="pdf_name" value="${event.pdf}">`;
                    iconHtml += '<span class="epic-tooltip m-l-3 downloadLdcPdf" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Pdf"><i class="fa fa-file-pdf-o"></i></span>'; 


                }
                eventHtml += '<div class="eventStatusIcon">' + iconHtml + '</div>';


                var eventTitle = event.title;
                if (view.type == 'agendaDay') {
                    eventHtml +='<strong>' + moment(event.start).format("h:mm A") + ' - ' + moment(event.end).format("h:mm A") +'</strong> ';
                    eventHtml += '<small>' + eventTitle +'</small><br/>';
                    
                } else if (view.type == 'agendaWeek') {
                    eventHtml += '<strong>' + moment(event.start).format("h:mm A") + ' - ' + moment(event.end).format("h:mm A") +'</strong>';

                    eventHtml += '<br/>' + '<small>' +  eventTitle + '</small><br/>';
                    
                } else if (view.type == 'month') {
                    eventHtml += '<strong><span class="eventTimeRange">' + moment(event.start).format("h:mm A") + ' - ' + moment(event.end).format("h:mm A") + '</span></strong><br/>' + eventTitle;
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
             
            },
            eventAfterAllRender: function (view) {
                stickyFilter();
                var titleDatepicker = calendar.find('.fc-titleDatepicker-button');
                titleDatepicker.html(calendar.find('h2').text());

                /*if (!titleDatepicker.hasClass('btn-wide'))
                    titleDatepicker.addClass('btn-wide')*/

                calendar.find('.fc-toolbar').addClass('custom-calendar');

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

                if(calEvent.start._i>moment().format("YYYY-MM-DD HH:mm:ss"))
                    saveDateTrainingSeg.data('event-time','future');
                else
                    saveDateTrainingSeg.data('event-time','past'); 

                FX.setPlanId(calEvent.id);
                FX.setDateId(calEvent.dateid);

                FX.GetWorkoutWithExercise(modal); 
                }else{
                    var fileName = $(this).find('input[name="pdf_name"]').val();
                    // window.location.href = public_url+'uploads/'+fileName;
                    var url = public_url+'uploads/'+fileName;
                    window.open(url, '_blank');
                }
            }
            
		});
       
        /* Start: Full calender */

        /* Initializing jquery validation */
        initCustomValidator();

        /* Start: save trainning segment */
            $('#saveDateTrainingSeg').click(function(e){
                e.preventDefault();
                var $this = $(this),
                    event_time = $this.data('event-time');

                if(event_time == 'past')
                    FX.AlertPlanSave('info', 'Are you sure plan completed?', FX.SaveCalendarPlan);
                else
                    FX.AlertPlanSave('warning', 'You can not change future plan.'); 
            });
        /* End: save trainning segement */

        /* Start: Delete exercise from calendar */
            $('body').on('click', '.deleteDateExe', function(e){
                e.preventDefault();
                FX.ConfirmDelete($(this), FX.DeleteClientExe);
            }) 
        /* End: Delete exercise from calendar */

        /* Start: Search Exercise  */
            $('.accordion-toggle button', $('#caledar-exe-accordion')).click(function(e){
                e.stopPropagation();
                e.preventDefault();
                toggleWaitShield("show");
                var $this = $(this),
                    modal = $('#addexercise');

                FX.setWorkOutId($this.data('workout'));

                loadBodyAreasForExercise(FX.genderString == 'male'?maleAreasForEx:femaleAreasForEx, $('#addexercise'));
                modal.find('#keySearch').val('');
                modal.find('#muscle_group').val('');
                modal.find('#ability').val('');
                modal.find('#equipment').val('');
                modal.find('#category').val('');
                modal.find('#movement_type').val('');
                modal.find('#movement_pattern').val('');

                modal.modal('show');
                var formData = {};
                formData['clientPlanId'] = $('#activityModal').find('input[name="clientplan_id"]').val();
                $.get(public_url+'CustomPlan/exercise-type',formData,function(response){
                    var exerciseType = response;
                    setActiveTab(modal,exerciseType);
                },'json');
                //FX.UI.searchScroll = new FX.InfiniteScroller($('#exerciseList').parent(), FX.GetExercises);
                FX.GetExercises();
            })
        /* End : Search Exercise  */
        $(document).ready(function(){
            $('#deleteClientClass').click(function(e) {
                    e.preventDefault();
                    modal = $('#activityModal');
                    var clientId = modal.find('input[name="clientplan_id"]').val();
                    if($(this).data('no-of-week') == '1'){
                        text = "<a class='btn btn-primary removeClient' href='#' data-target-event='this' data-client-id='" + clientId + "'>This only</a>";
                    }else{
                        text = "<a class='btn btn-primary m-r-10 removeClient' href='#' data-target-event='future' data-client-id='" + clientId + "'>This and future</a><a class='btn btn-primary removeClient' href='#' data-target-event='this' data-client-id='" + clientId + "'>This only</a>";
                    }
                        swal({
                            title: 'Delete From?',
                            showCancelButton: true,
                            html: true,
                            text: text,
                            showConfirmButton: false,
                            allowOutsideClick: true,
                            customClass: 'classClientUnlinkAlert',
                        });
            });    
        });


        $('body').on("click", '.removeClient', function(e) {
            e.preventDefault();
             modal = $('#activityModal');
             var formData = {};
                if(modal.find('input[name="clientplan_id"]').val() == undefined || modal.find('input[name="clientplan_id"]').val() ==''){
                    formData['clientplan_id'] = FX.PlanId;
                }else{
                    formData['clientplan_id'] = modal.find('input[name="clientplan_id"]').val();
                }
                if(modal.find('input[name="date_id"]').val() == undefined || modal.find('input[name="date_id"]').val() == ''){
                    formData['dateId'] = FX.DateId;
                }else{
                    formData['dateId'] = modal.find('input[name="date_id"]').val();
                }
                formData['targetEvents'] = $(this).data('target-event');
                $.ajax({
                    
                    url: public_url + 'activity/delete',
                    type: 'Post',
                    data: formData,

                    success: function(response) {
                         location.reload();  
                    },
                });
        });
                  
        /* Start: Searching exercise by favorite */
            $("#favSearch").on( "click", function(e){
              e.preventDefault();
              toggleWaitShield('show');
              FX.ToggleHeart($(this));
              FX.GetExercises();
            });
        /* End: Searching exercise by favorite */

        /* Start: Searching exercise by keyword */
            $("#keySearch").on("keyup", function(e){
                toggleWaitShield('show');
                clearTimeout(FX.keySearchTimeoutId);
                FX.keySearchTimeoutId = setTimeout(FX.GetExercises, 1000);
            });

            $("#addexercise .searchExercise").change(function(e){
                toggleWaitShield('show');
                FX.GetExercises();
                return false;
            });
        /* End: Searching exercise by keyword */

        /* Start: Add exercise in workout */
            $('body').on('click', '.add-exercise', function(e){
                e.stopPropagation();
                e.preventDefault();
                toggleWaitShield('show');
                $this = $(this),
                exerciseId = $this.closest('.lungemodalCls').data('exercise-id');

                FX.AddExercise(exerciseId, $this);
            })
        /* End: Add exercise in workout */

        /* Start: Add exercise in workout */
            $('.toggle-exercise').click(function(e){
                e.preventDefault();
                toggleWaitShield('show');
                $this = $(this),
                exerciseId = $this.parent().data('exercise-id');

                FX.AddExercise(exerciseId, $this);
            })
        /* End: Add exercise in workout */
        
        /* Start: Add exercise in workout */
        $('body').on('click', '.toggle-video', function(e){
            e.stopPropagation();
            e.preventDefault();
            toggleWaitShield('show');
            $this = $(this),
            exerciseId = $this.parent().data('exercise-id');
            FX.AddExercise(exerciseId, $this,"1");
        })
        /* End: Add exercise in workout */
        
        /* Start: Mark exercise as favorite or vice-versa */
            $('body').on("click", '.toggle-fav', function(e){
              e.stopPropagation();
              var toggleFavLnk = $(this),
                  exercise_id = toggleFavLnk.closest('.lungemodalCls').data('exercise-id'),
                  clientId = $('input[name="clientId"]').val(),
                  formData = {};
                
                if(toggleFavLnk.children('.fa-heart-o').length > 0){
                  var url = 'AddFavExercise',
                      addCls = 'fa-heart',
                      remCls = 'fa-heart-o';
                }
                else{
                  var url = 'RemoveFavExercise',
                      addCls = 'fa-heart-o',
                      remCls = 'fa-heart';
                }
              
              formData = {exerciseId:exercise_id, Clientid: clientId};
              API.getAjax('fitness/CustomPlan/'+url, formData, function(response){
                if(response.status == 'success'){
                  toggleFavLnk.children().removeClass(remCls).addClass(addCls);
                }
              });
            });
          /* End: Mark exercise as favorite or vice-versa */

        /* Start: randor Exercise detail on modal lungemodalCls */
            $('body').on('click', '.lungemodalCls', function(e){
                e.stopPropagation();
                e.preventDefault();
                toggleWaitShield('show');
                var $this = $(this);
                   
                    if($this.data('type') == 'video'){
                        var modal = $('#videoModal');
                        var exerciseId = $this.data('exeid');
                        modal.find('.modal-title').html($this.data('exercise-name'));
                        modal.find('.modal-footer').data('exercise-id', exerciseId);
    
                        if($this.find('.toggle-video').children('.fa-check').length > 0)
                            modal.find('.toggle-video').hide();
                        else
                            modal.find('.toggle-video').show();
                        var videoPath = $(this).data('video-url');
                        var video = $('#myVideo')[0];
                        video.src = videoPath;
                        video.load();
                        modal.modal('show');
                        toggleWaitShield("hide");
                    }else{
                        var modal = $('#lungemodal');
                        var exerciseId = $this.data('exercise-id');

                        modal.find('.modal-title').html($this.data('exercise-name'));
                        modal.find('.modal-footer').data('exercise-id', exerciseId);
    
                        if($this.find('.add-exercise').children('.fa-check').length > 0)
                            modal.find('.toggle-exercise').hide();
                        else
                            modal.find('.toggle-exercise').show();
                        FX.GetExerciseDetail(exerciseId, modal);
                    }
            })
        /* End: randor Exercise detail on modal lungemodalCls */

        /* Start: Sweet alert close */
            $('body').on('click', '.swal-close-btn', function(e){
                swal.close();
            })
        /* End: Sweet alert close */
    });
	/* End: Document ready */
}
/* End : rescoureNotFound */

/* Start: getSearch for body mapper only you cannot change it use may place */
    function getExercises(){
        FX.GetExercises();
    }
/* End : getSearch for body mapper only you cannot change it use may place */

/* Start: Get Client plan */
	function getClientPlan(){
		var formData = {};
        calendar.fullCalendar('removeEvents');
		toggleWaitShield('show');

        API.getAjax('activity/date/plan', formData, function (data) {
        	var events = [];
            var text = $('.fc-titleDatepicker-button').html();
            var agendaDayheader = '<a href="#" class="epic-tooltip tooltipstered" data-toggle="tooltip" rel="tooltip" title="' + text + '">' + text + '</a>';
            $('.fc-agendaDay-view').find('.fc-day-header').html(agendaDayheader).addClass('today');

            if (data.length) {
                $.each(data, function () {
					var eventStart = moment(this.startDatetime),
						eventEnd = moment(this.endDatetime);

                    events.push({type: this.type, id: this.id, title: this.title, start: eventStart, end: eventEnd, dateid: this.dateid, color: '#96A6A6',pdf:this.pdf});
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

function setActiveTab(modal,exerciseType = "0"){
    if(exerciseType == '1'){
      modal.find('.nav-tabs a[href="#addexerciseImage"]').parent('li').removeClass('disabled');
      modal.find('.nav-tabs a[href="#addexerciseImage"]').attr('data-toggle','tab');
      modal.find('.nav-tabs a[href="#addexerciseImage"]').tab('show');
      modal.find('.nav-tabs a[href="#addexerciseVideo"]').removeAttr('data-toggle');
      modal.find('.nav-tabs a[href="#addexerciseVideo"]').parent('li').addClass('disabled');
    }else if(exerciseType == '2'){
      modal.find('.nav-tabs a[href="#addexerciseVideo"]').parent('li').removeClass('disabled');
      modal.find('.nav-tabs a[href="#addexerciseVideo"]').attr('data-toggle','tab');
      modal.find('.nav-tabs a[href="#addexerciseVideo"]').tab('show');
      modal.find('.nav-tabs a[href="#addexerciseImage"]').removeAttr('data-toggle');
      modal.find('.nav-tabs a[href="#addexerciseImage"]').parent('li').addClass('disabled');
    }else{
      modal.find('.nav-tabs a[href="#addexerciseImage"]').parent('li').removeClass('disabled');
      modal.find('.nav-tabs a[href="#addexerciseImage"]').attr('data-toggle','tab');
      modal.find('.nav-tabs a[href="#addexerciseImage"]').tab('show');
      modal.find('.nav-tabs a[href="#addexerciseVideo"]').attr('data-toggle','tab');
      modal.find('.nav-tabs a[href="#addexerciseVideo"]').parent('li').removeClass('disabled');
    }
  }

$(document).ready(function(){
    $(".fc-day").append("<div class='open-modalp1' type='button' data-toggle='modal' data-target='#myModal1'><i class='fa fa-pencil'></i></div>");
});
  
 $('document').ready(function(){
    $('thead.fc-head').addClass('sticky-header');
  })
  $(window).scroll(function() {
    if ($(this).scrollTop() > 200){  
      $('.alt-header').show();
    }
    else{
      $('.alt-header').hide();
    }
  });

  $('document').ready(function(){
   $('.fc-toolbar').append('<table class="alt-header" style="display:none;"><thead class="fc-head" style="margin-right: 6px;"><tr><td class="fc-head-container fc-widget-header"><div class="fc-row fc-widget-header"><table><thead><tr><th class="fc-day-header fc-widget-header fc-mon">Mon</th><th class="fc-day-header fc-widget-header fc-tue">Tue</th><th class="fc-day-header fc-widget-header fc-wed">Wed</th><th class="fc-day-header fc-widget-header fc-thu">Thu</th><th class="fc-day-header fc-widget-header fc-fri">Fri</th><th class="fc-day-header fc-widget-header fc-sat">Sat</th><th class="fc-day-header fc-widget-header fc-sun">Sun</th></tr></thead></table></div></td></tr></thead></table>');
 });

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
        var setPanel = $("#"+trainingSegment).find('.panel a[href="#content-'+trainingSegment+exeId+count+'"]');
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
        var accordianElement = $('#caledar-exe-accordion').find("#"+trainingSegment).closest('.panel').find('a[href="#'+trainingSegment+'"]');
        if(accordianElement.hasClass('collapsed')){
            accordianElement.trigger('click');
        }
        if($("#"+trainingSegment).find('.panel a[href="#content-'+trainingSegment+exeId+count+'"]').hasClass('collapsed')){
            $("#"+trainingSegment).find('.panel a[href="#content-'+trainingSegment+exeId+count+'"]').trigger('click');
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

$('.daysInWeek').on('click',function(){
    var formData = {};
    var isValid = true;
    var noOfDaysInWeek = $('.weekProgram').text();
    var showTitle = $('.showTitle').text();
    var startDateRead= $('#startDateSelect').val();
    var startDateShow= $('#startDateShow').val();
    var optionValue =$('input[name="dayOptionValue"]').val();
    var sameDaysAcrossProgram = $('input[name="sameDaysAcrossProgram"]').val();
    // var checkbox=$('#editPrograme').find('.square-checkbox input[type="checkbox"]:checked').length;
    var checkbox =$('#editPrograme').find('.square-checkbox');
   
    var planType = $('input[name="planType"]').val();
    if(planType == 9){
        isFormValid = true;
        formData['id']=$('input[name="programId"]').val();
        if(optionValue == 2){
            var data ={}
            var count = 1;
            $('.multiphaseProgramDays .square-checkbox').each(function(){
                $this = $(this);
                let i = 0;
                $(this).find('.checkSelect').each(function(){
                    if($(this).is(':checked')){
                        i = i + 1;
                        data[count] = {
                            phase:$this.data('phase'),
                            week:$this.data('week'),
                            dayNo:i,
                            day:$(this).attr('name')
                        };
                        count = count + 1;
                    }
                });
                if(i != $this.data('max-day')){
                    isFormValid = false;
                }
            })
            formData['data'] = data;
            if(isFormValid){
                $.post(public_url+'activity/daysInWeek',formData,function(response){
                    if(response.status == 'success'){
                        getClientPlan();
                        var removeCheckbox=$('#editPrograme').find("input[type='checkbox']");
                        removeCheckbox.removeAttr('checked');
                        $('#editPrograme').find(".programWeek input").each(function()
                        {
                            if($(this).attr('id') == formData['id']){
                                $(this).parent('div').remove();
                                $('input[name="programId"]').val('');
                            }
                        });
                        if($('#editPrograme').find(".programWeek input").length == 0)
                        {
                            $("#editPrograme").hide();
                        }
                        $('.multiphaseProgramDays').empty().hide();
                        $('.weekProgram').text('');
                        $('.showTitle').text('');
                        $('.startDateValue').val('');
                        $('.startDateSelect').hide();
                    }else{
                        swal({
                            type: "error",
                            title: 'Something went wrong',
                            allowOutsideClick: true,
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#ff4401',
                        }); 
                    }
                });
            }else{
                swal({
                    type: "warning",
                    title: 'Please select required days',
                    allowOutsideClick: true,
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ff4401',
                }); 
            }
        }
    }else{
        if((noOfDaysInWeek !='' || optionValue == 1) && (noOfDaysInWeek != undefined || optionValue ==1)){
            checkbox.each(function(){
                var check = $(this).find('input[type="checkbox"]:checked').length;
                if(check != noOfDaysInWeek){
                    isValid = false;
                }
            });
            if(isValid || optionValue == 1){
                formData['id']=$('input[name="programId"]').val();
                if(optionValue == 2){
                    var DaysWeek = [];
                    if(sameDaysAcrossProgram != 1){
                        checkbox.each(function(){
                            var daysInWeek = $(this).find('input[type="checkbox"]');
                            DaysWeek.push(calcWorkoutdaysPattern(daysInWeek));
                        });
                        formData['weekDayPattern'] = DaysWeek;
                    }else{
                        daysInWeek = $('.editProgramDays input[type="checkbox"]');
                        formData['days']=  calcWorkoutdaysPattern(daysInWeek);
                    }
                }
                if(startDateShow != '' || startDateRead != ''){
                    if(startDateShow != ''){
                        formData['startDate']=$('#startDateShow').val();  
                    }
                    else{
                        formData['startDate']=$('#startDateSelect').val(); 
                    }
                    formData['sameDaysAcrossProgram'] = sameDaysAcrossProgram;
                    if(formData){
                        $.post(public_url+'activity/daysInWeek',formData,function(response){
                            if(response.status == 'success'){
                                swal({
                                    type: "success",
                                    title: 'Plan created successfully',
                                    allowOutsideClick: true,
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#ff4401',
                                },function(isConfirm){
                                    if(isConfirm){
                                        getClientPlan();
                                        $('#editPrograme').find('.selectProgramDays').empty().hide();
                                        $('#editPrograme').find(".programWeek input").each(function()
                                        {
                                            if($(this).attr('id') == formData['id']){
                                                $(this).parent('div').remove();
                                                $('input[name="programId"]').val('');
                                            }
                                        })
                                        if($('#editPrograme').find(".programWeek input").length == 0)
                                        {
                                            $("#editPrograme").hide();
                                        }
                                        $('.daysWeek').hide();
                                        $('.startDateSelect').hide();
                                        $('.startDateShow').hide();
                                        $('.weekProgram').text('');
                                        $('.showTitle').text('');
                                        $('.startDateValue').val('');
                                    }
                                });
                            }
                        });
                    }
                }
                else{
                    swal({
                        type: "warning",
                        title: 'Please select Start Date',
                        allowOutsideClick: true,
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ff4401',
                    }); 
                }
            }
            else{
                 swal({
                    type: "warning",
                    title: 'Please select '+noOfDaysInWeek+' days',
                    allowOutsideClick: true,
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ff4401',
                }); 
            }
        }else{
            swal({
                type: "warning",
                title: 'Please select program',
                allowOutsideClick: true,
                showCancelButton: false,
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff4401',
            }); 
        }
    }
});

function calcWorkoutdaysPattern(checkboxes){
    var dayPattern = [];
    checkboxes.each(function(){
      if($(this).is(':checked'))
        dayPattern.push(1)
      else
        dayPattern.push(0)
    });
    /*var sunday = dayPattern.pop();
    dayPattern.unshift(sunday);*/ 
    dayPattern = dayPattern.join('');
    return dayPattern;
  }

$("input[name='program']").click(function() {
    var programId= $(this).data('client-planid');
    var programTitle = $(this).data('title');
    var noOfDaysInWeek =$(this).data('no-of-days');
    var weekDays =$(this).data('week-days');
    var startDate =$(this).data('start-date');
    var optionValue =$(this).data('option-value');
    var planType = $(this).data('plan-type');
    if(startDate != '0000-00-00'){
        $('#startDateSelect').val(startDate);
        $('.startDateSelect').show();
        $('.startDateShow').hide();
    }
    else{
        $('#startDateSelect').val('');
        $('.startDateShow').show();
        $('.startDateSelect').hide();
    }
    if(optionValue == 2){
        if($(this).data('plan-type') == 9){
            let phaseData = $(this).data('phase-data');
            var html = '';
            $.each(phaseData,function(key,obj){
                $.each(obj,function(keyWeek,objWeek){
                    var count = 0; 
                    $.each(objWeek,function(keyDate,objDate){
                        count = count + 1;
                    });
                    html += '<h2 class="phaseSec">Phase '+key+'</h2>\
                    <h3>Week '+keyWeek+'</h3>\
                        <div class="row square-checkbox" data-phase="'+key+'" data-week="'+keyWeek+'" data-max-day="'+count+'">\
                        <div class="col-sm-3 m-b-20 editProgramDays">\
                            <input id="editProgramWeekdays0'+key+keyWeek+'" class="checkSelect" name="mon" value="Monday" type="checkbox">\
                            <label class="lable" for="editProgramWeekdays0'+key+keyWeek+'">Mon</label>\
                        </div>\
                        <div class="col-sm-3 m-b-20 editProgramDays">\
                            <input id="editProgramWeekdays1'+key+keyWeek+'" class="checkSelect" name="tue" value="Tuesday" type="checkbox">\
                            <label class="lable" for="editProgramWeekdays1'+key+keyWeek+'">Tue</label>\
                        </div>\
                        <div class="col-sm-3 m-b-20 editProgramDays">\
                            <input id="editProgramWeekdays2'+key+keyWeek+'" class="checkSelect" name="wed" value="Wednesday" type="checkbox">\
                            <label class="lable" for="editProgramWeekdays2'+key+keyWeek+'">Wed</label>\
                        </div>\
                        <div class="col-sm-3 m-b-20 editProgramDays">\
                            <input id="editProgramWeekdays3'+key+keyWeek+'" class="checkSelect" name="thu" value="Thursday" type="checkbox">\
                            <label class="lable" for="editProgramWeekdays3'+key+keyWeek+'">Thu</label>\
                        </div>\
                        <div class="col-sm-3 m-b-20 editProgramDays">\
                            <input id="editProgramWeekdays4'+key+keyWeek+'" class="checkSelect" name="fri" value="Friday" type="checkbox">\
                            <label class="lable" for="editProgramWeekdays4'+key+keyWeek+'">Fri</label>\
                        </div>\
                        <div class="col-sm-3 m-b-20 editProgramDays">\
                        <input id="editProgramWeekdays5'+key+keyWeek+'" class="checkSelect" name="sat" value="Saturday" type="checkbox">\
                            <label class="lable" for="editProgramWeekdays5'+key+keyWeek+'">Sat</label>\
                        </div>\
                        <div class="col-sm-3 m-b-20 editProgramDays">\
                            <input id="editProgramWeekdays6'+key+keyWeek+'" class="checkSelect" name="sun" value="Sunday" type="checkbox">\
                            <label class="lable" for="editProgramWeekdays6'+key+keyWeek+'">Sun</label>\
                        </div>\
                        </div>';  
                })
            });
            $('.multiphaseProgramDays').empty().append(html);
            $('.multiphaseProgramDays').show();
            $('.selectProgramDays').hide();
            $('.daysWeek').hide();
        }else{
            swal({
                type: "warning",
                title: 'Do you want same selected days across the program?',
                allowOutsideClick: true,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                confirmButtonColor: '#ff4401',
                cancelButtonText: 'No'
            },function(isConfirm){
                if(isConfirm){
                    $('.selectProgramDays').empty().append(getDaysHtml(1));
                    $('input[name="sameDaysAcrossProgram"]').val(1);
                    $('.selectProgramDays').show();
                    $('.daysWeek').show();
                    $('.multiphaseProgramDays').empty().hide();
                }else{
                    $('input[name="sameDaysAcrossProgram"]').val(0);
                    $('.selectProgramDays').empty().append(getDaysHtml(weekDays));
                    $('.selectProgramDays').show();
                    $('.daysWeek').show();
                    $('.multiphaseProgramDays').empty().hide();
                }
            });
        }
    }
    else{
        $('.selectProgramDays').hide();
        $('.daysWeek').hide();
    }
    $('input[name="dayOptionValue"]').val(optionValue);
    $('.showTitle').text(programTitle);
    $('.weekProgram').text(noOfDaysInWeek);
    $('input[name="programId"]').val(programId);
    $('input[name="planType"]').val(planType);      
});

const getDaysHtml = (week) => {
    let html = '';
    let i = 1;
    for(i;i<=week;i++){
        html += `<h4>Week ${week == 1?'':i}</h4><div class="row square-checkbox">
        <div class="col-sm-3 m-b-20 editProgramDays">
          <input id="editProgramWeek${i}days0" class="checkSelect" name="mon" value="Monday" type="checkbox">
          <label class="lable" for="editProgramWeek${i}days0">Mon</label>
        </div>
        <div class="col-sm-3 m-b-20 editProgramDays">
          <input id="editProgramWeek${i}days1" class="checkSelect" name="tue" value="Tuesday" type="checkbox">
          <label class="lable" for="editProgramWeek${i}days1">Tue</label>
        </div>
        <div class="col-sm-3 m-b-20 editProgramDays">
          <input id="editProgramWeek${i}days2" class="checkSelect" name="wed" value="Wednesday" type="checkbox">
          <label class="lable" for="editProgramWeek${i}days2">Wed</label>
        </div>
        <div class="col-sm-3 m-b-20 editProgramDays">
          <input id="editProgramWeek${i}days3" class="checkSelect" name="thu" value="Thursday" type="checkbox">
          <label class="lable" for="editProgramWeek${i}days3">Thu</label>
        </div>
        <div class="col-sm-3 m-b-20 editProgramDays">
          <input id="editProgramWeek${i}days4" class="checkSelect" name="fri" value="Friday" type="checkbox">
          <label class="lable" for="editProgramWeek${i}days4">Fri</label>
        </div>
        <div class="col-sm-3 m-b-20 editProgramDays">
          <input id="editProgramWeek${i}days5" class="checkSelect" name="sat" value="Saturday" type="checkbox">
          <label class="lable" for="editProgramWeek${i}days5">Sat</label>
        </div>
        <div class="col-sm-3 m-b-20 editProgramDays">
          <input id="editProgramWeek${i}days6" class="checkSelect" name="sun" value="Sunday" type="checkbox">
          <label class="lable" for="editProgramWeek${i}days6">Sun</label>
        </div>
      </div>`;
    }
    return html;
}

$('body').on('click','.checkSelect',function(){
    var noOfDaysInWeek = 0;
    let maxDays = $(this).closest('.square-checkbox').data('max-day');
    if(maxDays != undefined && maxDays != ''){
        noOfDaysInWeek = maxDays;
    }else{
        noOfDaysInWeek = $('.weekProgram').text();
    }
    var checkbox=$(this).closest('.square-checkbox').find('input[type="checkbox"]:checked').length;
    if(checkbox == noOfDaysInWeek || checkbox < noOfDaysInWeek){
        // $(this).prop("checked", true);
        // $(this).prop("checked", true);

    }
    else{
        $(this).prop("checked", false);
    }

});
