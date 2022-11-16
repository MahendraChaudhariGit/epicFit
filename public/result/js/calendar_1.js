if (!$('#noRescoureFound').length) {

    var public_url = $('meta[name="public_url"]').attr('content'),
            //calendarSettings = JSON.parse($('input[name=calendarSettingVal]').val()),
            calendar = popoverContainer,
            recurrAppointReschecModal = $('#recurrAppointReschecModal'),
            busyModal = $('#busyModal'),
            creditModal = $("#creditModal"),
            workingHrsModal = $("#workingHrsModal"),
            dateSelMsg = $('#dateSelMsg'),
            noRostStaff = $('#noRostStaff'),
            staffFilterCalend = $('select.staff-filter-cal'),
            areaDdCalend = $('select.toolBarDd.loc-area-dd'),
            signup_class_stat = {eventType: 'none', reschedule: false, resOnSignup: false, resonopen: false},
            checkdateObj = {date: 'none'},
            addEventDateSelMode = false,
            resheduleAppointDateSelMode = false,
            bookNextAppointDateSelMode = false,
            changeEventDateDateSelMode = false,
            staffFilterDdTriggered = false,
            calendarFirstTime = true,
            areaDdCalendLastVal = 0,
            staffFilterCalendLastVal = 0,
            scriptSource = 'calendar',
            week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            addEventPopoverOpt = {
                html: true,
                //content: "<a href='#' class='btn-block appointAddLnk'><i class='fa fa-calendar'></i> Add service</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block classAddLnk'><i class='fa fa-bullhorn'></i> Add class</a><hr class='m-t-0 m-b-0'><a href='#' class='btn-block busyAddLnk'><i class='fa fa-ban'></i> Add busy time</a>",
                content: "",
                /*content: "<a href='#' class='btn-block bookAddLnk'><i class='fa fa-calendar'></i> Add booking</a>",*/
                placement: function (context, source) {
                    var pos = $(source).position(),
                            leftSpace = pos.left,
                            rightSpace = calendar.width() - (leftSpace + $(source).width()),
                            topSpace = pos.top,
                            popoverWidth = 200,
                            popoverHeight = 165;

                    if (rightSpace > popoverWidth)
                        return 'right';
                    else if (leftSpace > popoverWidth)
                        return 'left';
                    else if (topSpace > popoverHeight)
                        return 'top';
                    else
                        return 'bottom';
                },
                container: calendar,
                title: function () {
                    if ($(this).text())
                        var head = $(this).text();
                    else
                        var head = '9:00am';

                    return /*"<center><strong>"+head+"</strong></center>"*/
                },
                trigger: 'manual'
            },
            editEventPopoverOpt = {
                html: true,
                content: function () {
                    var $this = $(this),
                            autoExpireDatetime = $this.find('input[name="autoExpireDatetime"]').val(),
                            summary = '',
                            eventDate = $this.find('input[name="date"]').val(),
                            startTime = calendar.find('.calendEvent.appointment-' + $this.find('input[name="eventId"]').val() + ':first input[name="startTime"]').val(),
                            timeString = dbTimeToTimeString(startTime),
                            eventDatetime = eventDate + ' ' + timeString;

                    if (!isSameOrBefore(eventDatetime)) {
                        if (isUserType(['Staff']) && $this.find('input[name="userId"]').val() != loggedInUser.userId) {
                            var editOrView = 'View',
                                    reschedOpt = '',
                                    cancelOpt = '',
                                    options = '';
                        } else {
                            var editOrView = 'Edit',
                                    reschedOpt = ($this.find('input[name="isStaffDeleted"]').val() == 'true') ? '' : '<a class="btn btn-primary btn-sm appointReschedule" href="#"><i class="fa fa-calendar"></i> Reschedule</a>',
                                    cancelOpt = '<a href="#" class="pull-left m-l-10" data-toggle="modal" data-target="#appointCancelModal"><i class="fa fa-trash"></i> Cancel</a>';

                            if ($this.find('input[name="appointStatusOpt"]').val() == 'Confirmed') {
                                var appointStatusConfirm = $this.find('input[name="appointStatusConfirm"]').val(),
                                        options = '<div class="btn-toolbar m-t-10"><div class="btn-group" role="group"><a class="btn btn-default btn-sm appointStatusChange ' + (appointStatusConfirm == 'Completed' ? 'active' : '') + '" href="#" data-new-status="Completed"><i class="fa fa-check"></i> Completed</a><a class="btn btn-default btn-sm appointStatusChange ' + (appointStatusConfirm == 'Did not show' ? 'active' : '') + '" href="#" data-new-status="Did not show"><i class="fa fa-thumbs-down"></i> Did not show</a></div></div>';
                            } else {
                                var options = '<div class="btn-toolbar m-t-10"><a class="btn btn-sm btn-success appointStatusChange" href="#" data-new-status="Not started" data-confirm="true"><i class="fa fa-check"></i> Confirm</a> <a class="btn btn-sm btn-red appointDeleteForce" href="#"><i class="fa fa-close"></i> Decline</a></div>';
                            }
                        }
                    } else {
                        var editOrView = 'View',
                                reschedOpt = '',
                                cancelOpt = '',
                                options = '';
                    }

                    if ($this.find('input[name="appointStatusOpt"]').val() != 'Confirmed' && autoExpireDatetime != '0000-00-00 00:00:00') {
                        var expireMoment = moment(autoExpireDatetime);
                        summary = '<p><i class="fa fa-bomb"></i> Expires ';

                        if (!moment().isSame(expireMoment, 'day'))
                            summary += expireMoment.format('[on] D MMM [at] h:mma');
                        else
                            summary += expireMoment.format('[at] h:mma [today]');
                    }

                    if ($this.find('input[name="isClientDeleted"]').val() != 'true')
                        var bookNextOpt = '<a class="btn btn-primary btn-sm appointBookNext" href="#"><i class="fa fa-refresh"></i> Book next</a>';
                    else
                        var bookNextOpt = '';

                    return '<div class="eventPopover"><p><i class="fa fa-gear"></i> ' + $this.find('input[name="serviceName"]').val() + ' - $' + $this.find('input[name="price"]').val() + '</p><p><i class="fa fa-user"></i> ' + $this.find('input[name="staffName"]').val() + ' </p><p><i class="fa fa-clock-o"></i> ' + $this.find('.eventTimeRange').text() + '</p>' + summary + '<div class="clearfix"><div class="btn-toolbar pull-left"><div class="btn-group" role="group"><a class="btn btn-primary btn-sm appointEdit" href="#"><i class="fa fa-edit"></i> ' + editOrView + ' </a>' + reschedOpt + bookNextOpt + '</div></div>' + cancelOpt + '</div>' + options + '</div>';
                },
                placement: function (context, source) {
                    if ($(source).closest('.fc-view').hasClass('fc-agendaDay-view'))
                        return 'top';

                    var pos = $(source).offset(),
                            rightSpace = (calendar.width() - (pos.left - calendar.offset().left) - $(source).width()),
                            popoverWidth = 400;

                    if (rightSpace > popoverWidth)
                        return 'right';
                    return 'left';
                },
                container: calendar,
                title: function () {
                    return '<strong>' + $(this).find('input[name="clientName"]').val() + '</strong>';
                }
            };

    $(document).ready(function () {
        if ($("#classModal").modal('hide'))
        {
            signup_class_stat['reschedule'] = false;
        }
        calendarSettings = {};
        calendarSettings.cs_intervals = 15;
        calendarSettings.cs_view = "monthly";
        calendarSettings.cs_first_day = "3";
        calendarSettings.cs_initial_status = "Pencilled-In";
        calendarSettings.cs_allow_appointments = 1;
        
        var h = Math.floor((calendarSettings.cs_intervals * 4) / 60),
                m = (calendarSettings.cs_intervals * 4) % 60;
        var intervels = h + ':' + m;
        /* Initialize the calendar Default view (weekly,mothaly and daily)*/
        var agendaVal = "";
        if (calendarSettings.cs_view == 'weekly')
            agendaVal = 'agendaWeek';
        else if (calendarSettings.cs_view == 'daily')
            agendaVal = 'agendaDay';
        else
            agendaVal = 'month';
        
        
        /* Initializing jquery validation */
        initCustomValidator();

        /* Initializing 'select a date' alert notification */
        if (isSalesProcessMode())
            dateSelMsg.addClass('subviewMode')
        else {
            var headElem = calendar.closest(".app-content").find('header');
            dateSelMsg.height(headElem.height() - 20);
        }
        dateSelMsg.find('a').click(function (e) {

            signup_class_stat['resOnSignup'] = true;
            signup_class_stat['reschedule'] = false;
            signup_class_stat['eventType'] = 'none';
            signup_class_stat['resonopen'] = false;
            $('#resClassClient').attr('data-resevent-id', '');
            getStaffEvents();



            resetEnableDisableDateFields();
            if (rescheduleClientsCount() || resheduleAppointDateSelMode) {
                showClassModal('add');
                areaDdCalend.selectpicker('val', areaDdCalendLastVal);
                getAreaStaffs(areaDdCalend, staffFilterCalendLastVal);
            }
            /*if(signup_class_stat.eventType=='signupClass')
             {
             signup_class_stat['eventType']='none';
             getStaffEvents();
             }*/

            resetAllModes(e);
        })

        /* Event handler to open appointment modal in edit mode */
        $('body').on('click', '.appointEdit', function (e) {
            e.preventDefault();

            showAppointmentModal('edit');
        });

        /* Event handler to cancel appointment */
        $('body').on('click', '.appointDeleteForce', function (e) {
            e.preventDefault();
            calPopupHelper.trigger("click");
            deleteAppoint(true);
        });

        /* Event handler to resechedule appointment */
        $('body').on('click', '.appointReschedule', function (e) {
            resheduleAppointDateSelMode = true;
            openSelDateNotif('Choose a new time for this service.', e);
        });

        /* Event handler to open appointment modal in book appointment again mode */
        $('body').on('click', '.appointBookNext', function (e) {
            bookNextAppointDateSelMode = true;
            openSelDateNotif('Choose a time for the next service.', e);
        });

        /* Event handler to change appointment status */
        $('body').on('click', '.appointStatusChange', function (e) {
            e.preventDefault();
            var $this = $(this);
            if (!$this.hasClass('active')) {
                calPopupHelper.trigger("click");
                changeAppointStatus($this.data('new-status'), $this.data('confirm'));
            }
        });

        /* Event handler to change event date from modals */
        $('.eventDateChange').click(function (e) {
            //var modal = $(this).closest('.modal');
            $(this).closest('.modal').modal('hide');

            signup_class_stat['reschedule'] = true;

            changeEventDateDateSelMode = true;
            //modal.modal('hide');

            if (eventModal == 'appointModal')
                openSelDateNotif('Choose a new time for this service.', e);
            else if (eventModal == 'classModal') {
                openSelDateNotif('Choose a new time for this booking.', e);

                if (isSalesProcessEvent() && $.inArray(eventObj.find('input[name="salesProcessEvent"]').val(), ['4', '6']) !== -1) {
                    setEnableDisableDateFields();
                    markWorkingHelper();
                }
            } else
                openSelDateNotif('Choose a new time for this busy-time.', e);

            //getStaffHours(modal.find('select[name="staff"]').val());
        });

//resc class 
        $("#resClassClient").click(function (e)
        {
            eventModal = $(this).data('target-modal');
            addEventDateSelMode = true;
            if (eventModal == 'resClassClient') {
                openSelDateNotif('Choose a class for the reschedule.', e);

                signup_class_stat['resonopen'] = true;
                var formData = {};
                formData['eventType'] = 'signupClass';
                signup_class_stat['eventType'] = 'signupClass';
                signup_class_stat['resOnSignup'] = false;
                formData['insertRepeatUpto'] = getCalendEndDatePriorDate();
                formData['getEventsFrom'] = getCalendStartDate();
                formData['getEventsUpto'] = getCalendEndDatePriorDate();
                var initial_date = moment();
                var num_days = 6;
                var max_date = moment(initial_date).add('days', num_days).format('YYYY-MM-DD');
                var current_date = moment().format('YYYY') + '-' + moment().format('MM') + '-' + moment().format('DD');
                formData['currentDate'] = current_date;
                formData['maxDate'] = max_date;
                signupClassAjaxRun(formData);
            }
            classModal.modal('toggle');
        });

        /* Event handler to add new event */
        $('body').on('click', '.eventAdd', function (e) {

            eventModal = $(this).data('target-modal');
            addEventDateSelMode = true;
            $('#resClassClient').attr('data-resevent-id', '');

            if (eventModal == 'appointModal') {
                openSelDateNotif('Choose a time for the new service.', e);
                signup_class_stat['eventType'] = 'appointModal';
            } else if (eventModal == 'classModal') {
                //signup_class_stat['resonopen']=false;
                signup_class_stat['eventType'] = 'classModal';
                signup_class_stat['resOnSignup'] = false;
                openSelDateNotif('Choose a time for the new booking.', e);
                getStaffEvents();
            } else if (eventModal == 'signupModal') {

                signup_class_stat['resonopen'] = false;
                signup_class_stat['resOnSignup'] = false;

                openSelDateNotif('Choose a class for the new booking.', e);

                var formData = {};
                formData['eventType'] = 'signupClass';
                signup_class_stat['eventType'] = 'signupClass';
                signup_class_stat['resOnSignup'] = false;
                formData['insertRepeatUpto'] = getCalendEndDatePriorDate();
                formData['getEventsFrom'] = getCalendStartDate();
                formData['getEventsUpto'] = getCalendEndDatePriorDate();
                var initial_date = moment();
                var num_days = 6;
                var max_date = moment(initial_date).add('days', num_days).format('YYYY-MM-DD');
                var current_date = moment().format('YYYY') + '-' + moment().format('MM') + '-' + moment().format('DD');
                formData['currentDate'] = current_date;
                formData['maxDate'] = max_date;
                signupClassAjaxRun(formData);
            }
        });

        /* Event handler to open appointment modal in add mode */
        $('body').on('click', '.appointAddLnk', function (e) {
            e.preventDefault();

            showAppointmentModal('add');
        });

        /* Event handler to open class modal in add mode */
        $('body').on('click', '.classAddLnk', function (e) {
            e.preventDefault();

            showClassModal('add');
        });

        /* Event handler to open class modal in add mode */
        $('body').on('click', '.bookAddLnk', function (e) {
            e.preventDefault();
            showClassModal('add');
        });

        /* Event handler to open class modal in add mode */
        $('body').on('click', '.busyAddLnk', function (e) {
            e.preventDefault();
            //showBusyModal('add');
        });

        classModal.on('hide.bs.modal', function () {
            if (reloadEvents) {
                getStaffEvents();
                reloadEvents = false;
            }
        });
        busyModal.on('hide.bs.modal', function () {
            calPopupHelper.trigger("click");
        });

        $('body').on("click", '.reschedule-event', function (e) {
            e.preventDefault();

            var targetEvents = $(this).data('target-event');
            rescheduleAppointment(recurrAppointReschecModal.find('input[name="date"]').val(), recurrAppointReschecModal.find('input[name="time"]').val(), targetEvents);
        });

        $('body').on("click", '.reschedule-class_client', function (e) {
            e.preventDefault();

            rescheduleClass_client($(this).data('target-event'));
        });

        /* Submitting busy modal data */
        $(".submit", busyModal).click(function () {
            var formData = {},
                    form = busyModal.find('form');

            var isFormValid = form.valid();

            var areaDd = form.find('select[name="modalLocArea"]');
            if (isFieldMarkedAsDeleted(areaDd)) {
                isFormValid = false;
                setFieldInvalid(areaDd.closest('.form-group'), 'Please select new area.')
            }

            moveErrorMsg(busyModal);

            if (isFormValid) {
                form.find(':input').each(function () {
                    formData[$(this).attr('name')] = $(this).val()
                })

                var eventDate = setEventDate(busyModal, 'submit');
                formData['busyDate'] = eventDate.date;
                formData['busyDay'] = eventDate.day;

                formData['busyTime'] = timeStringToDbTime(formData['eventTime']);

                formData['busyDenyBook'] = form.find("input[name='busyDenyBook']:checked").val();

                if (formData['eventId'] != null && formData['eventId'] != '')
                    ajaxAction = public_url + 'staffevents/busy-time/edit';
                else
                    ajaxAction = public_url + 'staffevents/busy-time/create';

                $.post(ajaxAction, formData, function (data) {
                    var data = JSON.parse(data);
                    if (data.status == "added") {
                        busyModal.modal('hide');
                        getStaffEvents();
                        showNotific(data.message);
                    } else if (data.status == "error") {
                        $.each(data.errorData, function (key, val) {
                            $.each(this, function (errorType, message) {
                                if (errorType == "workingHourUnavail")
                                    var field = form.find("span.eventDateDisp");

                                setFieldInvalid(field.closest('.form-group'), message)
                                moveErrorMsg(classModal);
                            });
                        });
                    }
                });
            }
        });

        /* Event handler to Toggle credit expiry date */
        $('input[name="creditExpireNever"]').change(function () {
            var creditExpire = $('input[name="creditExpire"]')

            if ($(this).is(":checked")) {
                creditExpire.prop('disabled', true)
                creditExpire.val('');
                setFieldNeutral($(this))
            } else
                creditExpire.prop('disabled', false)
        })

        /* Resetting credit modal form on opening */
        creditModal.on('show.bs.modal', function (event) {
            resetAllModes();
            clearForm($(event.target).find('form'));
            $(event.target).find('input[name="creditExpireNever"]').trigger('change');
        });

        /* Submitting credit modal data */
        $(".submit", creditModal).click(function () {
            var formData = {},
                    form = creditModal.find('form'),
                    isFormValid = form.valid();

            if (!validateTypeaheadClient(form))
                isFormValid = false;

            var creditExpireNever = form.find("input[name='creditExpireNever']:checked").val(),
                    creditExpire = $('input[name="creditExpire"]');
            if (creditExpireNever == null && creditExpire.val() == '') {
                isFormValid = false;
                setFieldInvalid(creditExpire.closest('.form-group'), 'This field is required.');
            }

            if (isFormValid) {
                form.find(':input').each(function () {
                    formData[$(this).attr('name')] = $(this).val()
                })

                formData['creditExpireNever'] = creditExpireNever;
                if (formData['creditExpireNever'] == null)
                    formData['creditExpire'] = moment(formData['creditExpire']).format("YYYY-MM-DD")

                $.post(public_url + 'client-credits', formData, function (data) {
                    var data = JSON.parse(data);
                    if (data.status == "added") {
                        creditModal.modal('hide');
                        showNotific(data.message);
                    }
                });
            }
        });

        /* Event handler to populate events and working hours based on the staff */
        $('body').on('change', 'select.staff-filter-cal', function () {
            staffFilterCal($(this).val());
        })

        /* Event handler to populate staffs based on the area */
        /*if(isRescheduleClientMode())
         enterReschecduleClientMode();
         else*/ if (isCreateMakeUpMode()) {
            areaDdCalend.selectpicker('val', 'all');
            areaDdCalend.trigger('change');
        } else if (areaDdCalend.val())
            //getAreaStaffs(areaDdCalend);
            staffFilterCalend.trigger('change');

        noRostStaff.find('a').click(function (e) {
            e.preventDefault();

            staffFilterCalend.val('all');
            staffFilterCalend.selectpicker('refresh');
            staffFilterCalend.trigger('change');
        })

        /* Popup helper */
        calPopupHelper.height($(document).height());
        calPopupHelper.click(function () {
            calendar.find('.calendEventWrap').popover('hide');

            if (calendar.find('.fc-month-view').length)
                calendar.find('.fc-day').popover('hide');

            else if (calendar.find('.fc-agendaWeek-view').length || calendar.find('.fc-agendaDay-view').length)
                calendar.find('.fc-slot-time').popover('hide');

            resetEnableDisableDateFields();

            if (isSalesProcessMode() || addEventDateSelMode || bookNextAppointDateSelMode || resheduleAppointDateSelMode || changeEventDateDateSelMode) {
                setTimeout(function () {
                    calendar.find('.highlightCell').removeClass('highlightCell');
                }, 500);
            } else
                calendar.find('.highlightCell').removeClass('highlightCell');
        })

        /* Event handler to jump calendar from jumper */
        $('body').on('click', '.calJumper .dropdown-menu a', function (e) {
            e.preventDefault();
            var jumpAmount = $(this).data('jump-amount'),
                    jumpUnit = $(this).data('jump-unit'),
                    operation = $(this).closest('.calJumper').parent().attr('id'),
                    datepicker = calendar.find('#datepicker'),
                    datepickerVal = datepicker.val();

            if (datepicker.length && datepickerVal != '' && datepickerVal != null)
                var momentt = moment(datepickerVal, "MM/DD/YYYY");
            else
                var momentt = moment();

            if (operation == 'jumpforw')
                calendar.fullCalendar('gotoDate', momentt.add(jumpAmount, jumpUnit))
            else if (operation == 'jumpBack')
                calendar.fullCalendar('gotoDate', momentt.subtract(jumpAmount, jumpUnit))
        })

        /* Event handler to switch to day view from week view */
        $('body').on('click', '#calendar .fc-agendaWeek-view .fc-day-header a:not(".editHrs")', function (e) {
            e.preventDefault();

            calendar.fullCalendar('changeView', 'agendaDay')
            calendar.fullCalendar('gotoDate', $(this).parent().data('date'))
        })

        /* Event handler to switch to week view from day view */
        $('body').on('click', '#calendar .fc-agendaDay-view .fc-day-header a:not(".editHrs")', function (e) {
            e.preventDefault();
            calendar.fullCalendar('changeView', 'agendaWeek')
        })

        /* Event handler to open working hours modal */
        $('body').on('click', '#calendar a.editHrs', function (e) {
            e.preventDefault();
            workingHrsModal.modal('show');
        })

        /* Saving working hours modal data */
        $('button[data-modal-button-action="submit"]', workingHrsModal).click(function () {
            var formData = {},
                    form = workingHrsModal.find('form');

            form.find(':input').each(function () {
                var type = this.type,
                        $this = $(this);

                if (type != 'checkbox' || (type == 'checkbox' && $this.is(':checked')))
                    formData[$this.attr('name')] = $this.val();
            })

            $.ajax({
                url: public_url + 'staff-hours/' + formData['staffId'],
                method: "POST",
                data: formData,
                success: function (data) {
                    var data = JSON.parse(data);
                    if (data.status == "updated") {
                        workingHrsModal.modal('hide');
                        //markWorkingHelper(data.hoursData)
                        setStaffHours(data.hoursData)
                    }
                }
            });
        });

        /* Event handler for 'Add new time button' in working hours modal */
        $('body').on('click', '.btn-add-new-time', function (e) {
            e.preventDefault();
            formRowsCreated++;

            var self = $(this),
                    parCont = self.closest('div'),
                    timeRow = self.next().clone(),
                    day = timeRow.find('input[type="checkbox"]').attr('name');

            var startTimeField = timeRow.find('input[name="' + day + '_start0"]');
            startTimeField.prop('name', day + '_start' + formRowsCreated);
            workingHoursTimePicker(startTimeField);

            var endTimeField = timeRow.find('input[name="' + day + '_end0"]');
            endTimeField.prop('name', day + '_end' + formRowsCreated);
            workingHoursTimePicker(endTimeField);

            addNewTimeHelper(timeRow, parCont);

        });
        $('body').on('click', '.btn-add-new-time-cancel', function (e) {
            $(this).closest('.row').remove();
        });

        $('body').on('change', '.showHours', function (e) {
            toggleHours($(this));
        });
        $('.showHours').trigger('change');


    });


    /* Function to populate hours in working hours modal based on staff */
    function populateHours(data, staffId) {
        $('.showHours', workingHrsModal).each(function (index, elem) {
            var parCont = $(elem).closest('.clearfix');

            parCont.find('.row:not(:first)').remove();

            var checkbox = parCont.find('input[type="checkbox"]'),
                    day = checkbox.attr('name');

            if (checkbox.is(':checked')) {
                checkbox.prop('checked', false);
                toggleHours($(elem));
            }
        });

        $('input[name="staffId"]', workingHrsModal).val(staffId);
        var daysCovered = [];
        $.each(data, function (key, value) {
            var day = (value.hr_day).toLowerCase(),
                    checkbox = $('input[name="' + day + '"]', workingHrsModal),
                    timeRow = checkbox.closest('.row'),
                    startTime = dbTimeToTimeString(value.hr_start_time),
                    endTime = dbTimeToTimeString(value.hr_end_time);

            if ($.inArray(day, daysCovered) == -1) {
                daysCovered.push(day);

                checkbox.prop('checked', true);
                toggleHours(checkbox);

                setFieldForTimepicker(timeRow.find('input[name="' + day + '_start0"]'), startTime)
                setFieldForTimepicker(timeRow.find('input[name="' + day + '_end0"]'), endTime)
            } else {
                formRowsCreated++;

                var parCont = checkbox.closest('.clearfix'),
                        timeRow = timeRow.clone(),
                        startTimeField = timeRow.find('input[name="' + day + '_start0"]'),
                        endTimeField = timeRow.find('input[name="' + day + '_end0"]');

                setFieldForTimepicker(startTimeField, startTime)
                setFieldForTimepicker(endTimeField, endTime)
                startTimeField.prop('name', day + '_start' + formRowsCreated);
                endTimeField.prop('name', day + '_end' + formRowsCreated);

                addNewTimeHelper(timeRow, parCont);
            }
        });
    }
    /* Helper function for 'Add new time button' event handler */
    function addNewTimeHelper(timeRow, parCont) {
        timeRow.addClass('remove-hidden');
        timeRow.find('.checkbox').remove();
        timeRow = timeRow.append('<div class="col-xs-3"><button class="btn btn-xs btn-danger btn-add-new-time-cancel" type="button">Remove</button></div>');
        parCont.append(timeRow);
    }
    /* Function for toggling hours in working hours modal */
    function toggleHours(self) {
        var parCont = self.closest('.clearfix');
        var notWork = parCont.find('.col-xs-10')
        var showHoursElem = parCont.find('.showHoursElem')
        var timeRow = parCont.find('.row:not(:first)');
        if (self.is(':checked')) {
            notWork.addClass('hidden');
            showHoursElem.removeClass('hidden');
            timeRow.removeClass('hidden');
        } else {
            timeRow.addClass('hidden');
            showHoursElem.addClass('hidden');
            notWork.removeClass('hidden');
        }
    }

    /* Function to mark working time slot in week and day view */
    function markWorkingSlot(element) {
        var hoursData = $('input[name="workingHours"]').val().split('|');

        element.find('.fc-slot-time.working').removeClass('working').unbind('click');
        if (hoursData.length && hoursData[0] != '') {
            $.each(hoursData, function (val, text) {
                var hourData = text.split(','),
                        rows = $(element).find('.fc-slats tr[data-time="' + hourData[0] + '"]').nextUntil('tr[data-time="' + hourData[1] + '"]').andSelf().add('tr[data-time="' + hourData[1] + '"]');

                rows.find('.fc-slot-time[data-day="' + hourData[2] + '"]').addClass('working')
            });
        }

        var enableDateUpto = enableDateFrom(element);
        disableDateFrom(element, enableDateUpto);

        var eventEligibleSlot = element.find('.fc-slot-time.working').filter(function () {
            var $this = $(this),
                    eventDate = getClickedDate($this, 'not month'),
                    eventTime = $this.closest('tr').data('time');

            var now = moment().format('YYYY-MM-DD HH:mm:ss');
            var num_of_days = 6;
            var max_date = moment(now).add('days', num_of_days).format('YYYY-MM-DD HH:mm:ss');

            if (moment(eventDate + ' ' + eventTime, 'YYYY-MM-DD HH:mm:ss').isAfter(max_date))
            {
                //alert(max_date)
                $this.removeClass('working').unbind('click');
                //$this.addClass('notWorking');
            }

            if (moment(eventDate + ' ' + eventTime, 'YYYY-MM-DD HH:mm:ss').isSameOrAfter(now))
                return $this;
            else
                $this.removeClass('working')
        });
        eventEligibleSlot.click(function () {
            var $this = $(this);
            var eventDate = getClickedDate($this, 'not month');
            var now = moment().format('YYYY-MM-DD HH:mm:ss');
            var num_of_days = 6;
            var max_date = moment(now).add('days', num_of_days).format('YYYY-MM-DD HH:mm:ss');

            if (moment(eventDate).isAfter(max_date))
            {
                return false;
            }
            if (signup_class_stat.reschedule == true && signup_class_stat.eventType != 'signupClass')
            {
                dateClicked($(this), getClickedDate($(this), 'not month'));
            }/*else
             {
             return false;
             }*/
            if ($(this).hasClass('working') && (!$("#dateSelMsg").hasClass("hidden")) && (signup_class_stat.eventType == 'classModal'))
            {
                dateClicked($(this), getClickedDate($(this), 'not month'));
            } else
            {
                //dateClicked();
                return false;
            }

        });

    }
    /* Function to mark working day in month view */
    function markWorkingDay(element) {
        var hoursData = $('input[name="workingHours"]').val().split('|'),
                cells = element.find('.fc-day:not(.notWorking)'),
                workingDays = [];

        markFreeDay(cells);
        cells.unbind('click');

        if (hoursData.length && hoursData[0] != '') {
            $.each(hoursData, function (val, text) {
                var hourData = text.split(','),
                        hoursDay = hourData[2].substring(0, 3).toLowerCase();
                if (jQuery.inArray(hoursDay, workingDays) < 0)
                    workingDays.push(hoursDay);
            });

            if (workingDays.length && workingDays[0] != '') {
                $.each(workingDays, function (val, text) {
                    element.find('.fc-' + text).removeClass('notWorking')
                });
            }
        }

        var enableDateUpto = enableDateFrom(element);
        disableDateFrom(element, enableDateUpto);

        closedDay(element);

        var eventEligibleCell = element.find('.fc-day:not(.notWorking)').filter(function () {
            var $this = $(this),
                    now = moment().format('YYYY-MM-DD');
            var num_of_days = 6;
            var max_date = moment(now).add('days', num_of_days).format('YYYY-MM-DD');

            if (moment($this.data('date')).isAfter(max_date))
            {
                $this.addClass('notWorking');
            }

            if (moment($this.data('date')).isSameOrAfter(now))
                return $this;
            else
                $this.addClass('notWorking')
        });


        eventEligibleCell.click(function () {
            //alert(signup_class_stat.eventType);

            now = moment().format('YYYY-MM-DD');
            var num_of_days = 6;
            var max_date = moment(now).add('days', num_of_days).format('YYYY-MM-DD');

            if (moment($(this).data('date')).isAfter(max_date))
            {
                return false;
            }

            if (signup_class_stat.reschedule == true && signup_class_stat.eventType != 'signupClass')
            {
                dateClicked($(this), getClickedDate($(this), 'month'));
            }

            if (!$("#dateSelMsg").hasClass("hidden") && (signup_class_stat.eventType == 'classModal') && (!$(this).hasClass('notWorking')))
            {
                dateClicked($(this), getClickedDate($(this), 'month'));

            } else
            {
                //dateClicked();
                return false;
            }







        });


    }
    /* Function to highlight free day */
    function markFreeDay($elem) {
        $elem.addClass('notWorking');
    }

    function enableDateFrom(currView) {
        var enableDateFromField = $('input[name="enableDateFrom"]'),
                enableDateUpto = '';
        if (enableDateFromField.length) {
            var enableDateFrom = enableDateFromField.val();
            if (enableDateFrom && enableDateFrom != null) {
                var enableDatePeriodField = $('input[name="enableDatePeriod"]');
                if (enableDatePeriodField.length)
                    enableDateUpto = moment(enableDateFrom).add(enableDatePeriodField.val(), 'day').format('YYYY-MM-DD');

                enableDisableDateHelper(currView, enableDateFrom, 'enable-from');
            }
        }
        return enableDateUpto;
    }

    function resetEnableDisableDateFields() {
        if (isSalesProcessEvent() && (resheduleAppointDateSelMode || changeEventDateDateSelMode)) {
            $('input[name="enableDateFrom"]').val('')
            $('input[name="enableDatePeriod"]').val('')

            if (changeEventDateDateSelMode)
                markWorkingHelper();
        }
    }

    function setEnableDisableDateFields() {
        $('input[name="enableDateFrom"]').val(eventObj.find('input[name="consultationDate"]').val())
        $('input[name="enableDatePeriod"]').val('15')
    }

    function disableDateFrom(currView, enableDateUpto) {
        if (typeof enableDateUpto == 'undefined' || !enableDateUpto) {
            var enableDateUptoField = $('input[name="enableDateUpto"]');
            if (enableDateUptoField.length)
                enableDateUpto = enableDateUptoField.val();
            else
                enableDateUpto = '';
        }
        if (enableDateUpto)
            enableDisableDateHelper(currView, enableDateUpto, 'disable-from');
    }

    function enableDisableDateHelper(currView, dateCheckAgainst, kase) {
        var viewKase = detectCalendView(currView);
        if (viewKase == 'month') {
            //Month view
            var selector = '.fc-day:not(.notWorking)';
        } else if (viewKase == 'week' || viewKase == 'day') {
            //Week || Day view
            var selector = '.fc-slot-time.working';
        }

        var eventNotEligibleCell = currView.find(selector).filter(function () {
            var $this = $(this);

            if (viewKase == 'month')
                var eventDate = $this.data('date');
            else if (viewKase == 'week' || viewKase == 'day')
                var eventDate = getClickedDate($this, 'not month');

            if (kase == 'enable-from' && moment(eventDate).isBefore(dateCheckAgainst))
                return $this;
            else if (kase == 'disable-from' && moment(eventDate).isSameOrAfter(dateCheckAgainst))
                return $this;
        });
        if (viewKase == 'month')
            eventNotEligibleCell.addClass('notWorking')
        else if (viewKase == 'week' || viewKase == 'day')
            eventNotEligibleCell.removeClass('working')
    }

    function detectCalendView(currView) {
        if (currView.hasClass('fc-month-view')) {
            //Month view
            return 'month';
        } else if (currView.hasClass('fc-agendaWeek-view')) {
            //Week view
            return 'week';
        } else if (currView.hasClass('fc-agendaDay-view')) {
            //Day view
            return 'day';
        }
    }

    /* Function to display time in each main time slot of view */
    function slotsDispTime(element, view, tstamp) {
        if (calendarSettings.cs_first_day > -1) {
            var removedDays = week.splice(calendarSettings.cs_first_day);
            week = removedDays.concat(week);
        }

        var rows = $(element).find('.fc-slats tr');

        rows.each(function (index, elem) {
            var $elem = $(elem),
                    text = moment($elem.data("time"), ["HH:mm:ss"]).format("h:mma");

            if (view == 'week') {
                var html = '<div class="fc-slot-times">';
                $.each(week, function (key, val) {
                    html += '<div class="fc-slot-time" data-day="' + val + '">' + text + '</div>';
                })
                html += '</div>';
                $elem.children(":last").html(html);
                //$elem.children(":last").html('<div class="fc-slot-times"><div class="fc-slot-time" data-day="Monday">'+text+'</div><div class="fc-slot-time" data-day="Tuesday">'+text+'</div><div class="fc-slot-time" data-day="Wednesday">'+text+'</div><div class="fc-slot-time" data-day="Thursday">'+text+'</div><div class="fc-slot-time" data-day="Friday">'+text+'</div><div class="fc-slot-time" data-day="Saturday">'+text+'</div><div class="fc-slot-time" data-day="Sunday">'+text+'</div></div>');
            } else if (view == 'day')
                $elem.children(":last").html('<div class="fc-slot-times"><div class="fc-slot-time" data-day="' + tstamp.format('dddd') + '">' + text + '</div>');
        })
    }

    function closedDay(currView) {
        var closedDates = $('input[name="closedDates"]').val();
        if (closedDates) {
            var viewKase = detectCalendView(currView);
            if (viewKase == 'month') {
                //Month view
                var selector = '.fc-day:not(.notWorking)';
            } else if (viewKase == 'week' || viewKase == 'day') {
                //Week || Day view
                var selector = '.fc-slot-time.working';
            }

            closedDates = closedDates.split(',');
            var eventNotEligibleCell = currView.find(selector).filter(function () {
                var $this = $(this);

                if (viewKase == 'month')
                    var eventDate = $this.data('date');
                else if (viewKase == 'week' || viewKase == 'day')
                    var eventDate = getClickedDate($this, 'not month');

                if ($.inArray(eventDate, closedDates) !== -1)
                    return $this;
            });
            if (viewKase == 'month')
                eventNotEligibleCell.addClass('notWorking')
            else if (viewKase == 'week' || viewKase == 'day')
                eventNotEligibleCell.removeClass('working')
        }
    }


    /* Function to change column text of day view */
    function dayviewcolText(currView) {
        if (!findStaffId())
            var selStaff = 'All Staff';
        else {
            //if(isUserType(['Admin']))
            //var selStaff = $('select.staff-filter-cal option:selected').text();
            var momentt = moment();
            //var selStaff = momentt.format('LL');
            var selStaff = '';
            //else if(isUserType(['Staff']))
            //var selStaff = loggedInUser.name;
        }

        coltextHelperFunc(currView.find('.fc-day-header'), selStaff)
    }
    /* Function to convert text into link for week view and highlight today date header */
    function weekviewcolLink(currView) {
        var momentt = moment(),
                todayDate = momentt.format('YYYY') + '-' + momentt.format('MM') + '-' + momentt.format('DD'),
                header = currView.find('.fc-day-header');

        header.each(function (index, elem) {
            var $elem = $(elem);
            if (todayDate == $elem.data('date'))
                $elem.addClass('today');
            coltextHelperFunc($elem, $elem.html())
        })
    }
    function coltextHelperFunc($elem, text) {
        //alert(text);
        $elem.html('<a href="#" class="epic-tooltip" data-toggle="tooltip" rel="tooltip" title="' + text + '">' + text + '</a> <a href="#" class="editHrs epic-tooltip" data-toggle="tooltip" rel="tooltip" title="Edit working hours"><i class="fa fa-clock-o"></i></a>');
        toggleEditHrsLnk();
        $elem.find('.epic-tooltip').tooltipster();
    }

    /* Function to check if current view contains today date and toggle today button */
    function ifToday(view) {
        var startTimestamp = view.intervalStart.format('x'),
                emdTimestamp = view.intervalEnd.format('x'),
                currentTimestamp = moment().format('x'),
                todayBtn = calendar.find('.fc-today-button');

        if (currentTimestamp >= startTimestamp && currentTimestamp <= emdTimestamp)
            todayBtn.addClass('active');
        else
            todayBtn.removeClass('active');
    }

    /* Function to populate events based on the staff */
    function getStaffEvents(staffId, ifInsertNeverEndingEventsRepeat) {



        //calendar.fullCalendar('removeEvents');

        var formData = {};

        if (staffId == null || staffId == 0)
            staffId = findStaffId();

        if (ifInsertNeverEndingEventsRepeat != null)
            formData['insertRepeatUpto'] = getCalendEndDatePriorDate();

        formData['eventType'] = ['class'];
        //formData['staffId'] = staffId;
        //formData['areaId'] = findAreaId();
        formData['getEventsFrom'] = getCalendStartDate();
        formData['getEventsUpto'] = getCalendEndDatePriorDate();



        if (staffId == 'all-ros') {
            formData['startDate'] = getCalendStartDate();
            if (!ifDayView())
                formData['endDate'] = getCalendEndDate();
        }

        if (!rescheduleClientsCount() && !isCreateMakeUpMode()) {
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
             },'json');*/
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
             },'json');*/
        } else if (isCreateMakeUpMode())
            formData['eventStatus'] = 'active';


        if (signup_class_stat.eventType == 'signupClass')
        {
            var initial_date = moment();
            var num_days = 6;
            var max_date = moment(initial_date).add('days', num_days).format('YYYY-MM-DD');
            var current_date = moment().format('YYYY') + '-' + moment().format('MM') + '-' + moment().format('DD');
            formData['currentDate'] = current_date;
            formData['maxDate'] = max_date;
            formData['eventType'] = 'signupClass';
            signupClassAjaxRun(formData);
        } else
        {
            /*var initial_date= moment(getCalendStartDate());
             
             var num_days=7;
             var max_date=moment(initial_date).add('days', num_days).format('YYYY-MM-DD');
             formData['maxDate'] =max_date;*/
            //var current_month_days=moment().daysInMonth();

            // console.log(moment(initial_date).add('days', num_days).format('DD-MM-YYYY'));

            /*var max_new_date=moment().add('days', num_days).format('DD');
             var max_new_month=moment().format('MM');
             var max_new_year=moment().format('YYYY');
             var current_date=moment().format('YYYY')+'-'+moment().format('MM')+'-'+moment().format('DD');
             
             
             //console.log(Number(moment().format('MM'))+Number(10))
             if(Number(moment().format('DD'))+Number(num_days)>current_month_days)
             {
             max_new_month=moment().add('months', 1).format('MM');
             if(Number(moment().format('MM'))+Number(1)>12)
             {
             max_new_year=moment().add('years', 1).format('YYYY');
             }
             
             }
             
             var max_date=max_new_year+'-'+max_new_month+'-'+max_new_date;*/
            //console.log(max_date)


            //formData['currentDate'] =current_date;

            $.get(public_url + 'client-events', formData, function (data) {
                var events = [];
                var text = $('.fc-titleDatepicker-button').html();
                var agendaDayheader = '<a href="#" class="epic-tooltip tooltipstered" data-toggle="tooltip" rel="tooltip" title="' + text + '">' + text + '</a>';
                $('.fc-agendaDay-view').find('.fc-day-header').html(agendaDayheader).addClass('today');
                             //console.log(data);
                if (data.length) {
                    $.each(data, function () {
                        var eventStart = moment(this.startDatetime),
                                eventEnd = moment(this.endDatetime);

                        if (this.type == 'busy-time') {
                            var title = (this.description ? this.description : 'Busy');

                            events.push({type: 'busy', id: this.id, title: title, start: eventStart, end: eventEnd, date: this.date, isStaffDeleted: this.isStaffDeleted, color: '#96A6A6'});
                        } else if (this.type == 'single-service') {
                            var pushedIdx = events.push({type: 'single-service', id: this.id, title: this.title, start: eventStart, end: eventEnd, price: this.price, date: this.date, isStaffDeleted: this.isStaffDeleted, serviceName: this.serviceName, appointStatusOpt: this.appointStatusOpt, notes: this.appointNote, color: this.serviceColor, staffName: this.staffName, isRepeating: this.isRepeating, clients: []}) - 1;

                            for (var i in this.clients)
                                events[pushedIdx].clients.push(this.clients[i])
                        } else if (this.type == 'class') {
                            var pushedIdx = events.push({type: 'class', id: this.id, title: this.title, start: eventStart, end: eventEnd, price: this.price, date: this.date, isStaffDeleted: this.isStaffDeleted, color: this.color, capacity: this.capacity, isRepeating: this.isRepeating, notes: this.notes, staffName: this.staffName, clientsCount: this.clientsCount, clients: []}) - 1;

                            for (var i in this.clients)
                                events[pushedIdx].clients.push(this.clients[i])
                        }
                    });
                }
//                if (events.length && events[0] != '')
//                    calendar.fullCalendar('addEventSource', events);


            }, 'json');
        }
    }

    /* Function to fetch working days of staff */
    function getStaffHours(staffId) {
        var formData = {};

        if (staffId == null)
            staffId = findStaffId();

        formData['staffId'] = staffId;
        formData['areaId'] = findAreaId();

        if (staffId == 'all-ros') {
            formData['startDate'] = getCalendStartDate();
            if (ifDayView())
                formData['day'] = getCalendStartDay();
            else
                formData['endDate'] = getCalendEndDate();
        }

        $.get(public_url + 'staffs/hours', formData, function (data) {
            if (staffId != 'all' && staffId != 'all-ros')
                populateHours(data, staffId);

            //markWorkingHelper(data)
            setStaffHours(data)
        }, 'json');
    }
    function setStaffHours(data) {
        if (typeof data == 'undefined')
            data = JSON.parse($('input[name="workingHours"]').val())

        var convertedData = '';

        if (data.length) {
            $.each(data, function (val, text) {
                if (convertedData)
                    convertedData += '|';
                convertedData += this.hr_start_time + ',' + this.hr_end_time + ',' + this.hr_day;
            });
        }

        $('input[name="workingHours"]').val(convertedData);

        markWorkingHelper();
    }
    function markWorkingHelper(/*data*/) {
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

        if (dayView.length)
            markWorkingSlot(dayView);
        else if (weekView.length)
            markWorkingSlot(weekView);
        else if (monthView.length)
            markWorkingDay(monthView);
    }

    /* Function to toggle edit hours link */
    function toggleEditHrsLnk(staffId) {
        if (staffId == null)
            staffId = findStaffId();

        var editHrs = calendar.find(' a.editHrs');

        if (!staffId || staffId == 'all' || staffId == 'all-ros')
            editHrs.hide();
        else
            editHrs.show();
    }

    /* Show Appointemnt Modal */
    function showAppointmentModal(mode) {

        eventModal = appointModal.prop('id');
        switchTabTo('appointDetails', appointModal);
        blockTimepickerChangeEvent = true;

        appointModal.find('input[name="serviceTime0"]').trigger('blur');

        if (changeEventDateDateSelMode) {
            setEventDate(appointModal, 'add');
            setEventdayAsRepeatWeekDay(appointModal);

            getStaffsBasedOnServices();
            getAreasBasedOnServices();
        } else {
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

            if (mode == 'add') {

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

                if (!$.isEmptyObject(defaultClientDetails)) {
                    var clientField = appointModal.find('.clientList');
                    clientField.val(defaultClientDetails.name);
                    populateClientDetails(defaultClientDetails, clientField)
                }

                resetEventReccur(appointModal);
            } else if (mode == 'edit') {
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


                if (bookNextAppointDateSelMode) {
                    bookNextAppointMode = true;
                    setEventDate(appointModal, 'add');

                    mode = 'add';
                    setPastCurrentModeFlag($('input[name="selectedDatetime"]').val())

                    appointHist.hide();
                    resetEventReccur(appointModal);
                } else {
                    setEventDate(appointModal, 'edit');

                    var timeString = dbTimeToTimeString(eventServices[0].time);
                    setPastCurrentModeFlag(eventObj.find('input[name="date"]').val() + ' ' + timeString);

                    getServices(true);

                    appointModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());

                    appointModal.find('input[name="isRepeating"]').val(eventObj.find('input[name="isRepeating"]').val());

                    populateEventReccur(appointModal)

                    populateEventHistory(appointModal)
                }

                var eventAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val()
                toggleAppointStatusFields(appointStatusSelectable, eventAppointStatusOpt);
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
    function showClassModal(mode) {
        eventModal = classModal.prop('id');
        blockTimepickerChangeEvent = true;
        if (!rescheduleClientsCount() && !resheduleAppointDateSelMode) {
            if (changeEventDateDateSelMode) {
                setEventDate(classModal, 'add');
                setEventTime(classModal);
                setEventdayAsRepeatWeekDay(classModal);
                setRepeatEndDate(classModal)

                getStaffsAndAreas()

                toggleAttendanceTab('hide');
            } else {
                var selectFirstClient = false;
                clearNotific(classModal.find('.tab-pane'));
                clientLinkOrder = 0;

                switchTabTo('classDetails', classModal);

                classClients = {};

                clearForm(classModal.find('form'));

                clearClientForm(classModal.find('.client-form'), false);
                classModal.find('input[name="isNewClient"]').trigger('change');

                classModal.find('div#linkedclientList').html('');

                toggleAttendanceTab('hide')
                classModal.find('div#classAttendanceList').html('');

                toggleTab(classModal, 'classReccur', 'show');
                toggleTab(classModal, 'classClients', 'show');

                if (mode == 'add') {
                    classModal.find("#at").removeClass("hidden");
                    classModal.find("#classDetails a").removeClass("hidden");
                    classModal.find(".timepicker").removeClass("hidden");
                    classModal.find(".set-group-disp a").removeClass("hidden");
                    classModal.find("#serviceFieldset").removeClass("hidden");
                    classModal.find(".update-event").removeClass("update-event").addClass("submit");
                    classModal.find("#resClassEveDate").empty();
                    classModal.find(".eventDateDisp").removeClass("hidden");
                    classModal.find("#resClassClient").addClass("hidden");

                    //classModal.find(".update-event").removeClass("update-event").addClass("submit");
                    eventObj = {};

                    $("#details_recur").empty();

                    $("#durup,#priup").empty();

                    //$("#details_recur,#classNotes_recur,#recur_recur").addClass("hidden");

                    //classModal.find("#classFieldset").removeClass("hidden");

                    classModal.find("#classTabs li").not("#classTabs li:nth-child(2)").show();

                    classModal.find(".clip-check").show();

                    classModal.find("#classDetails a").not("a.eventDateChange").removeClass("hidden");

                    classModal.find("#nextTab,.submit").removeClass("hidden");

                    classModal.find(".modal-footer").show();

                    setEventDate(classModal, 'add');
                    setEventTime(classModal);

                    pastCurrentMode = false;

                    //classModal.find('a[href="#classHist"]').hide();

                    resetEventReccur(classModal);

                    classModal.find('input[name="ifAutoExpireAppoint"]').trigger('change');
                    //$("#appointStatusSelectable").selectable("enable");
                    //toggleAppointStatusFields('Confirmed');
                    toggleAppointStatusFields(calendarSettings.cs_initial_status);
                    //$("#bookTypeSelectable").selectable("enable");

                    getServices('', getStaffsAndAreas);
                    setBookingType('service');
                    //disableBookTypeSelectable();

                    /*if(!isSalesProcessMode()){
                     getClasses(false, getStaffsAndAreas);
                     toggleEventOverBookAlert('hide');
                     setBookingType('class');
                     getServices();
                     }
                     else{
                     getServices('', getStaffsAndAreas);
                     setBookingType('service');
                     disableBookTypeSelectable();
                     toggleTab(classModal, 'classReccur', 'hide');
                     toggleTab(classModal, 'classClients', 'hide');
                     }*/

                    if (!$.isEmptyObject(defaultClientDetails)) {

                        var data = {id: defaultClientDetails[0].id, name: defaultClientDetails[0].name, phone: defaultClientDetails[0].phone, email: defaultClientDetails[0].email, isExisting: 1, note: ''};

                        updateLinkedclientList(data);

                        selectFirstClient = true;
                    }
                    updateBookedclientQuickInfo();
                } else if (mode == 'edit') {

                    classModal.find(".set-group-disp a").addClass("hidden");
                    //classModal.find(".submit").removeClass("submit").addClass("update-event");
                    classModal.find(".modal-footer").show();
                    setEventDate(classModal, 'edit');
                    var timeString = dbTimeToTimeString(eventObj.find('input[name="startTime"]').val());
                    //console.log(timeString);//return false;
                    
                    setFieldForTimepicker(classModal.find('input[name="eventTime"]'), timeString)

                    setPastCurrentModeFlag(eventObj.find('input[name="date"]').val() + ' ' + timeString);

                    classModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());
                    classModal.find('input[name="isRepeating"]').val(eventObj.find('input[name="isRepeating"]').val());
                    populateEventReccur(classModal)
                    populateEventHistory(classModal)
                    classModal.find('textarea[name="classNote"]').val(eventObj.find('input[name="notes"]').val());

                    eventObj.find("input[name='client[]']").each(function () {
                        var clientData = JSON.parse($(this).val());
                        clientData.isExisting = 1;
                        updateLinkedclientList(clientData);
                    });

//                    var eventType = eventObj.find("input[name='type']").val();
                    var eventType = eventObj.data('event-type');
                    
                    if (eventType == 'class') {
                        classModal.find("#at").addClass("hidden");
                        //classModal.find(".submit").removeClass("submit").addClass("update-event");
                        //classModal.find(".update-event").removeClass("update-event").addClass("submit");
                        classModal.find("#classTabs li:first").show();
                        setBookingType('class');
                        classId = eventObj.data('class-id');
                        //alert(classId);
                        getClasses(classId, getStaffsAndAreas);
                    } else if (eventType == 'single-service') {

                        classModal.find("#at").removeClass("hidden");

                        //classModal.find(".submit").removeClass("submit").addClass("update-event");
                        selectFirstClient = true;
                        var ifAutoExpireAppoint = classModal.find('input[name="ifAutoExpireAppoint"]')
                        ifAutoExpireAppoint.trigger('change');
                        setBookingType('service');
                        updateBookedclientQuickInfo();

                        getServices(eventObj.find('input[name="serviceId"]').val(), getStaffsAndAreas);

                        var eventAppointStatusOpt = eventObj.find('input[name="appointStatusOpt"]').val()
                        
                        toggleAppointStatusFields(eventAppointStatusOpt);
                        if (eventAppointStatusOpt == 'Pencilled-In') {
                            var eventAutoExpireDatetime = eventObj.find('input[name="autoExpireDatetime"]').val(),
                                    eventAutoExpireAppointDur = eventObj.find('input[name="autoExpireAppointDur"]').val();

                            if (eventAutoExpireDatetime != null && eventAutoExpireAppointDur) {
                                var autoExpireAppointDur = classModal.find('select[name="autoExpireAppointDur"]'),
                                        autoExpireAppointDurDate = classModal.find('input[name="autoExpireAppointDurDate"]'),
                                        autoExpireAppointDurTime = classModal.find('input[name="autoExpireAppointDurTime"]');

                                ifAutoExpireAppoint.prop('checked', true);
                                ifAutoExpireAppoint.trigger('change');

                                if (!eventAutoExpireAppointDur) {
                                    autoExpireAppointDur.val('Custom');
                                    autoExpireAppointDurDate.val(moment(eventAutoExpireDatetime).format("D MMM YYYY"));
                                    autoExpireAppointDurTime.val(moment(eventAutoExpireDatetime).format("h:mm A"));
                                } else
                                    autoExpireAppointDur.val(eventAutoExpireAppointDur);
                                autoExpireAppointDur.trigger('change');
                            }
                        }

                        if (isSalesProcessEvent()) {
                            disableAppointStatuselectable();
                            toggleTab(classModal, 'classReccur', 'hide');
                            //toggleTab(classModal, 'classClients', 'hide');
                        }
                    }
                    disableBookTypeSelectable()

                    if (classModal.find('ul.nav a[href="#classAttendance"]').css('display') != 'none') {
                        setTimeout(function () {
                            switchTabTo('classAttendance', classModal);
                        }, 500)
                    }
                }
                setGroupInit();
                classModal.find('select').selectpicker('refresh');

                if (selectFirstClient)
                    selectLatestLinkedClient();
                else {
                    var bookingType = getBookingType();
                    if (bookingType == 'class')
                        toggleClassClientLnks('create');
                    else if (bookingType == 'service')
                        toggleClassClientLnks('create', true);
                }

                if (isSalesProcessEvent())
                    classModal.find('a#unlinkClientClass').hide();

                toggleEventModalUiByTimeMode(classModal, mode);
            }
        } else
            leaveAttendanceTab();

        blockTimepickerChangeEvent = false;
        classModal.modal('show');
    }

    /* Show Busy Modal */
    function showBusyModal(mode) {
        eventModal = busyModal.prop('id');

        if (changeEventDateDateSelMode) {
            setEventDate(busyModal, 'add');
            setEventTime(busyModal);
        } else {
            var modalLocAreaDd = busyModal.find('select[name="modalLocArea"]'),
                    areaSetGroupDisp = modalLocAreaDd.closest('.set-group').find('.set-group-disp'),
                    areaSetGroupDispSpan = areaSetGroupDisp.find('span');

            clearForm(busyModal.find('form'));

            busyModal.find('select[name="staff"]').data('new-instance', 'true')

            if (mode == 'add') {
                eventObj = {};

                setModalLocAreaDdVal(modalLocAreaDd);
                areaSetGroupDispSpan.removeClass('text-danger');
                toggleDeletedMarkOnField(modalLocAreaDd, 0)

                getAreaStaffs(modalLocAreaDd);

                setEventDate(busyModal, 'add');
                setEventTime(busyModal);

                pastCurrentMode = false;
            } else if (mode == 'edit') {
                if (eventObj.find('input[name="isAreaDeleted"]').val() == 'true' || eventObj.find('input[name="ifAreaHasStaffs"]').val() == 'false') {
                    areaSetGroupDispSpan.addClass('text-danger');
                    toggleDeletedMarkOnField(modalLocAreaDd, eventObj.find('input[name="areaId"]').val())
                } else {
                    modalLocAreaDd.val(eventObj.find('input[name="areaId"]').val());
                    areaSetGroupDispSpan.removeClass('text-danger');
                    toggleDeletedMarkOnField(modalLocAreaDd, 0)
                }
                getAreaStaffs(modalLocAreaDd, eventObj.find('input[name="staffId"]').val());

                setEventDate(busyModal, 'edit');
                var timeString = dbTimeToTimeString(eventObj.find('input[name="startTime"]').val());
                setFieldForTimepicker(busyModal.find('input[name="eventTime"]'), timeString)

                setPastCurrentModeFlag(eventObj.find('input[name="date"]').val() + ' ' + timeString);

                busyModal.find('input[name="eventId"]').val(eventObj.find('input[name="eventId"]').val());

                busyModal.find('select[name="busyDur"]').val(eventObj.find('input[name="duration"]').val());

                busyModal.find('textarea[name="busyDesc"]').val(eventObj.find('input[name="description"]').val());

                var denyBooking = eventObj.find('input[name="denyBooking"]').val();
                if (denyBooking == 1)
                    busyModal.find('input[name="busyDenyBook"]').prop('checked', true);
            }
            setGroupInit();
            if (mode == 'edit') {
                if (eventObj.find('input[name="isAreaDeleted"]').val() == 'true')
                    areaSetGroupDispSpan.text('This area has been deleted')
                else if (eventObj.find('input[name="ifAreaHasStaffs"]').val() == 'false')
                    areaSetGroupDispSpan.text("This area doesn't have staff")
            }
            busyModal.find('select').selectpicker('refresh');
            toggleEventModalUiByTimeMode(busyModal, mode);
        }
        busyModal.modal('show');
    }

    function dateClicked(elem, date) {
        if (rescheduleClientsCount() || isCreateMakeUpMode())
            return false;

        elem.popover('show');

        var time = elem.closest('tr').data('time');

        if (time == null || time == '')
            var datetime = date + ' 09:00am';
        else
            var datetime = date + ' ' + time;

        $('input[name="selectedDatetime"]').val(datetime);
        elem.addClass('highlightCell')
        calPopupHelper.removeClass('hidden')

        if (addEventDateSelMode || bookNextAppointDateSelMode || changeEventDateDateSelMode) {
            calPopupHelper.trigger("click");
            setTimeout(function () {
                if (addEventDateSelMode || changeEventDateDateSelMode) {
                    if (eventModal == 'appointModal')
                        showAppointmentModal('add');
                    else if (eventModal == 'classModal')
                        showClassModal('add');
                    //else
                    //showBusyModal('add');
                } else if (bookNextAppointDateSelMode)
                    showAppointmentModal('edit');

                resetAllModes();
            }, 500);
        } else if (resheduleAppointDateSelMode) {
            /*if(isSalesProcessEvent()){
             $('input[name="enableDateFrom"]').val('')
             $('input[name="enableDatePeriod"]').val('')
             }*/
            resetAllModes();
            calPopupHelper.trigger("click");

            var isRepeating = eventObj.find('input[name="isRepeating"]').val();

            if (isRepeating == 1) {
                recurrAppointReschecModal.find('input[name="date"]').val(date);
                recurrAppointReschecModal.find('input[name="time"]').val(time);
                recurrAppointReschecModal.modal('show');
            } else
                rescheduleAppointment(date, time)
        } else if (isSalesProcessMode()) {
            calPopupHelper.trigger("click");
            setTimeout(function () {

                //showAppointmentModal('add');
                showClassModal('add');
            }, 500);
        }
    }

    /* Function to close 'select a date' alert notification */
    function resetAllModes(e) {
        if (e != null && e != '')
            e.preventDefault();
        closeSelDateNotif();
        addEventDateSelMode = false;
        resheduleAppointDateSelMode = false;
        bookNextAppointDateSelMode = false;
        changeEventDateDateSelMode = false;
        rescheduleClients = {};
    }

    function closeSelDateNotif() {
        dateSelMsg.addClass('hidden');
    }

    /* Function to open 'select a date' alert notification */
    function openSelDateNotif(msg, e) {
        if (e != null && e != '')
            e.preventDefault();
        dateSelMsg.removeClass('hidden');
        dateSelMsg.find('span').text(msg);
        calPopupHelper.trigger("click");
    }

    /* Function to fetch date of the calendar slot clicked */
    function getClickedDate(elem, view) {
        if (view == 'month')
            return elem.data('date');
        else {
            var index = (elem.index()) + 1;
            return elem.closest('.fc-view').find('th').eq(index).data('date');
        }
    }

    function changeAppointStatus(status, ifChangeToConfirm) {
        var formData = {};

        formData['eventId'] = eventObj.find('input[name="eventId"]').val();
        formData['appointStatusConfirm'] = status;
        formData['ifChangeToConfirm'] = ifChangeToConfirm;

        if (ifChangeToConfirm != null && ifChangeToConfirm)
            formData['historyText'] = 'Booking status changed from Pencilled-In to Confirmed|';
        else {
            if (status == 'Did not show')
                formData['historyText'] = 'Client did not show|';
            else
                formData['historyText'] = ' Booking marked as completed|';
        }

        $.post(public_url + 'staffevents/appointments/change-status', formData, function (data) {
            var data = JSON.parse(data);
            if (data.status == "updated") {
                getStaffEvents();
            } else if (data.status == "error") {
                $.each(data.errorData, function (key, val) {
                    $.each(this, function (errorType, message) {
                        if (errorType == "workingHourUnavail")
                            showNotific(message);
                    });
                });
            }
        });
    }

    function submitCancelAppointmentModalSuccess(ifForceDelete, data) {
        if (!ifForceDelete) {
            showNotific(data.message);
            appointCancelModal.modal('hide');
        }
        getStaffEvents();
    }

    function rescheduleAppointment(date, time, targetEvents) {
        var formData = {};

        formData['eventId'] = eventObj.find('input[name="eventId"]').val();
        formData['date'] = date;
        /*if(eventObj.find('input[name="appointStatusOpt"]').val() == 'Pencilled-In' && eventObj.find('input[name="autoExpireAppointDur"]').val() != '')
         formData['autoExpireDatetime'] = moment().add(eventObj.find('input[name="autoExpireAppointDur"]').val(), 'hours').format("YYYY-MM-DD H:mm:ss")*/

        var oldAppointdate = eventObj.find('input[name="date"]').val(),
                oldAppointTime = eventObj.find('input[name="startTime"]').val();
        if (time == null || time == '')
            formData['time'] = oldAppointTime;
        else
            formData['time'] = time;

        if (oldAppointdate != formData['date'] || oldAppointTime != formData['time'])
            formData['historyText'] = 'Reschedule from ' + dbDateToDateString(oldAppointdate) + ' ' + dbTimeToTimeString(oldAppointTime) + ' to ' + dbDateToDateString(formData['date']) + ' ' + dbTimeToTimeString(formData['time']) + '|';

        if (targetEvents != null) {
            formData['targetEvents'] = targetEvents;
            if (targetEvents == 'future') {
                formData['eventRepeat'] = eventObj.find('input[name="eventRepeat"]').val();
                formData['eventRepeatInterval'] = eventObj.find('input[name="eventRepeatInterval"]').val()
                formData['eventRepeatEnd'] = eventObj.find('input[name="eventRepeatEnd"]').val()
                formData['eventRepeatEndAfterOccur'] = eventObj.find('input[name="eventRepeatEndAfterOccur"]').val()

                var prevVal = eventObj.find('input[name="eventRepeatEndOnDate"]').val();
                if (prevVal != 'null') {
                    var dateMoment = moment(formData['date']);
                    if (moment(prevVal).isBefore(dateMoment))
                        formData['eventRepeatEndOnDate'] = formData['date'];
                } else
                    formData['eventRepeatEndOnDate'] = prevVal;

                if (formData['eventRepeat'] == 'Weekly')
                    formData['eventRepeatWeekdays'] = [moment(formData['date']).format("ddd")];

                if (formData['eventRepeatEnd'] == 'Never')
                    formData['calendEndDate'] = getCalendEndDate();
            }
        }


        //$.post(public_url+'staffevents/appointments/reschedule', formData, function(data){
        $.post(public_url + 'staffevents/single-service/reschedule', formData, function (data) {
            //alert('called');
            var data = JSON.parse(data);
            if (data.status == "updated") {
                /*getStaffEvents();
                 showNotific(data.message);*/
                showNotific(data.message);
                areaDdCalend.selectpicker('val', areaDdCalendLastVal);
                getAreaStaffs(areaDdCalend, staffFilterCalendLastVal);
            } else if (data.status == "error") {

                $.each(data.errorData, function (key, val) {
                    $.each(this, function (errorType, message) {
                        if (errorType == "workingHourUnavail")
                            showNotific(message);
                        else if (errorType == "businessClosed")
                            showNotific(prepareNotific('error', 'Business is closed on specified date'));
                    });
                });
            }
        });
    }

    function prepareCalendBody(staffId) {
        if (staffId == null)
            staffId = findStaffId();

        //alert(staffId)

        var monthBtn = calendar.find('.fc-month-button');

        if (staffId == 'all-ros') {
            if (calendar.fullCalendar('getView').type == 'month')
                calendar.fullCalendar('changeView', 'agendaDay');
            monthBtn.hide();
            ifAreaHasRostStaff();
        } else {
            calendar.find('.fc-view-container').show();
            noRostStaff.addClass('hidden');
            monthBtn.show();
            getStaffEvents(staffId, true);
            if (!calendarFirstTime)
                getStaffHours(staffId);
            else {
                setStaffHours();
                calendarFirstTime = false;
            }
        }
    }

    function ifAreaHasRostStaff() {
        var formData = {};
        formData['areaId'] = findAreaId();
        formData['startDate'] = getCalendStartDate();
        if (ifDayView())
            formData['day'] = getCalendStartDay();
        else
            formData['endDate'] = getCalendEndDate();

        formData['insertRepeatUpto'] = getCalendEndDatePriorDate();

        $.get(public_url + 'areas/has-rostered-staffs', formData, function (data) {
            if (data) {
                calendar.find('.fc-view-container').show();
                noRostStaff.addClass('hidden');
                getStaffEvents(0, true);
                getStaffHours();
            } else {
                calendar.find('.fc-view-container').hide();
                noRostStaff.removeClass('hidden');
                noRostStaff.find('span').text(calendar.find('h2').text());
            }
        }, 'json');
    }

    function ifDayView() {
        //return (calendar.fullCalendar('getView').type == 'agendaDay') ? true : false;
    }

    function getCalendRawStartDate() {
        //return calendar.fullCalendar('getView').start;
    }

    function getCalendStartDay() {
        //return getCalendRawStartDate().format('dddd');
    }

    function getCalendStartDate() {
        //return getCalendRawStartDate().format('YYYY-MM-DD');
    }

    function getCalendRawEndDate() {
        //return calendar.fullCalendar('getView').end;
    }

    function getCalendEndDate() {
        //return getCalendRawEndDate().format('YYYY-MM-DD');
    }

    function getCalendEndDatePriorDate() {
//        var endDate = getCalendRawEndDate().clone();
//        return endDate.subtract(1, 'days').format('YYYY-MM-DD');
    }

    function refreshCalendForRostStaff() {
//        if (!staffFilterDdTriggered && staffFilterCalend.val() == 'all-ros')
//            staffFilterCalend.trigger('change');
    }

    function staffFilterCal(selVal) {
        staffFilterDdTriggered = true;

        var dayView = $('.fc-agendaDay-view');

        if (dayView.length)
            dayviewcolText(dayView);
        else
            toggleEditHrsLnk(selVal);

        prepareCalendBody(selVal);
        staffFilterDdTriggered = false;
    }

    function submitAppointmentModalSuccess(data) {
        if (isSalesProcessMode()) {
            if ('changeStatus' in data)
                parent.realTimeUpdate('accStatus', data.changeStatus);
            if ('stepCompleted' in data) {
                parent.closeContactNoteSubview();
                parent.fetchStepAndMarkComplete(data.stepCompleted);
            }

            parent.closeSubview();
        } else {
            if (data.newClient && $.isEmptyObject(defaultClientDetails))
                fetchClientsForTypeahead('update');
            appointModal.modal('hide');
            getStaffEvents();
            showNotific(data.message);
        }
    }

    function enterReschecduleClientMode() {
        if (!isRescheduleClientMode()) {
            reloadEvents = false;
            areaDdCalendLastVal = findAreaId();
            staffFilterCalendLastVal = findStaffId();
            classModal.modal('hide');

            var bookingType = getBookingType();
            if (bookingType == 'class') {
                openSelDateNotif('Choose a class to reschedule the client(s).');
            } else if (bookingType == 'service') {
                if (isSalesProcessEvent() && $.inArray(eventObj.find('input[name="salesProcessEvent"]').val(), ['4', '5', '6', '9']) !== -1)
                    setEnableDisableDateFields();

                resheduleAppointDateSelMode = true;
                openSelDateNotif('Choose a new time for this service.');
            }
        }
        areaDdCalend.selectpicker('val', 'all');
        areaDdCalend.trigger('change');
    }

    function submitClassModalSuccess(data) {
        
        if (data.type == 'service' && isSalesProcessMode()) {
            alert('kkk');
            if ('changeStatus' in data)
                parent.realTimeUpdate('accStatus', data.changeStatus);
            if ('stepCompleted' in data) {
                parent.closeContactNoteSubview();
                parent.fetchStepAndMarkComplete(data.stepCompleted);
            }

            parent.closeSubview();
        } else {
            //alert('ggg');
            //alert('client reshcefgd')
            //fetchClientsForTypeahead('update');
//            reloadEvents = false;
            showNotific(data.message);
            signup_class_stat['eventType'] = 'none';
            //getStaffEvents();
            $("#classSignupModal").modal('hide');
            classModal.modal('hide');
            location.reload();
            $("#appointmenttab").trigger('click');
            //resetAllModes(e);

            if ('salesProcessError' in data && data.salesProcessError)
                showNotific(prepareNotific('warning', data.salesProcessError));
        }
    }

    function setModalLocAreaDdVal(modalLocAreaDd) {
        var areaDdCalendVal = findAreaId();

        if (areaDdCalendVal == 'all')
            modalLocAreaDd.val(modalLocAreaDd.find('option:first').val());
        else
            modalLocAreaDd.val(areaDdCalendVal);
    }

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

    function cancelEventSuccess(data) {
        if (eventModal == 'classModal')
            classModal.modal('hide');
        else
            busyModal.modal('hide');

        getStaffEvents();
        showNotific(data.message);
    }

    function rescheduleClass_client(targetEvents) {
        var formData = {};

        formData['eventId'] = eventObj.find('input[name="eventId"]').val();
        formData['classClients'] = rescheduleClients;
        formData['origEventId'] = classModal.find('input[name="eventId"]').val();
        if (typeof targetEvents != 'undefined')
            formData['targetEvents'] = targetEvents;
        else
            formData['targetEvents'] = '';

        $.post(public_url + 'staffevents/classes/reschedule-client', formData, function (data) {
            var data = JSON.parse(data);
            if (data.status == "added") {
                var clientId = Object.keys(rescheduleClients)[0],
                        tabs = classModal.find('.tab-pane:not("#classHist")');

                if (isRescheduleClientMode()) {
                    parent.showNotific(data.message, tabs);
                    parent.unlinkClientClass(clientId);
                    classModal.modal('show');
                    parent.leaveAttendanceTab();
                    parent.reloadEvents = true;
                    resetAllModes();
                    parent.closeSubview();
                } else {
                    showNotific(data.message, tabs);
                    unlinkClientClass(clientId);
                    showClassModal('add');
                    areaDdCalend.selectpicker('val', areaDdCalendLastVal);
                    getAreaStaffs(areaDdCalend, staffFilterCalendLastVal);
                    resetAllModes();
                }
            } else if (data.status == "clientBusy")
                showNotific(data.message);
        });
    }




    function showSignupClassModal(mode)
    {

        $("#classSignupModal").modal('show');
    }


    //signupclass ajax

    function signupClassAjaxRun(formData)
    {


        //console.log(formData);
        //signupclass ajax starts here
        $.get(public_url + 'signup-class/all', formData, function (data) {
            var events = [];
            //events['ifClientMakeupEligible']=data.ifClientMakeupEligible;
            //console.log(data[0].ifClientMakeupEligible);



            //coltextHelperFunc(currView.find('.fc-day-header'), momentt.format('LL'));


            $.each(data, function (index, value) {
                //console.log(value.ifClientMakeupEligible);
                if ((value.ifClientMakeupEligible) != 1 || (value.ifClientMakeupEligible) == 'undefined')
                {
                    $("input[name='makeupsession']").parent().addClass('hidden');
                } else if ((value.ifClientMakeupEligible) == 1)
                {
                    $("input[name='makeupsession']").parent().removeClass('hidden');
                }
            });

            $("#makeupsession").parent().removeClass('hidden');

            if (data.length) {

                $.each(data, function () {
                    var eventStart = moment(this.startDatetime),
                            eventEnd = moment(this.endDatetime);

                    if (this.type == 'classSignup') {
                        var pushedIdx = events.push({type: 'class', id: this.id, title: this.title, start: eventStart, end: eventEnd, price: this.price, date: this.date, isStaffDeleted: this.isStaffDeleted, color: this.color, capacity: this.capacity, isRepeating: this.isRepeating, notes: this.notes, staffName: this.staffName, clientsCount: this.clientsCount, clients: []}) - 1;

                        for (var i in this.clients)
                            events[pushedIdx].clients.push(this.clients[i])

                    }
                });
            }
            //if(events.length && events[0] != '')
            calendar.fullCalendar('removeEvents');
            calendar.fullCalendar('addEventSource', events);
        }, 'json');
        //signupclass ajax complete here

    }

//if modal not showing disable cell click


    $("#classSignupModal").on('hide.bs.modal', function () {
        //alert('mdoal hide')
        signup_class_stat['resOnSignup'] = true;
        //signup_class_stat['eventType']='none';
        signup_class_stat['reschedule'] = false;
        //getStaffEvents();
    });

    $("#classModal").on('hide.bs.modal', function () {
        signup_class_stat['resOnSignup'] = true;
        signup_class_stat['reschedule'] = false;
        //alert(signup_class_stat.resOnSignup)
    });



    $("#classSignupModal").on('hide.bs.modal', function () {
        if (signup_class_stat.eventType == 'signupClass')
        {
            $("#dateSelMsg").removeClass('hidden');
        }
    });
}



