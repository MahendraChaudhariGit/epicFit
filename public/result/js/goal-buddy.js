var lastJQueryTS = 0,
    timeoutReference = null; // this is a global variable.
    
$(document).ready(function() {
    

    /*var templateCheck = $("input[name='chooseGoal']:checked").val() == 'choose_form_template' ? true : false;
    if(!templateCheck){
        $(".tsk_rec_daily").hide();
        $(".hbt_rec_monthly").hide();
    }else{
        $(".tsk_rec_daily").show();
        $(".hbt_rec_monthly").show();
    }*/

    $('body').on('click', '.create-new-goal', function(){
       
    });
    $('.compleate-goal').change(function() {
        var checked = $(this).is(":checked");
        var processbarDiv = $(this).closest('tr');
        var processbarTd = processbarDiv.find('td .progress');
        var goalId = processbarDiv.find('#goal-id').val();
        var processbarPercentage = processbarDiv.find('td .progress-percentage');
        if (checked) {
            processbarTd.addClass('progress-bar-success');
            processbarPercentage.text('100%');
            updateStatus(goalId, 1);
        } else {
            processbarTd.removeClass('progress-bar-success');
            processbarPercentage.text('0%');
            updateStatus(goalId, 0);
        }
    });



    $('.delete-goal').click(function() {
        var processbarDiv = $(this).closest('tr');
        var goalId = processbarDiv.find('#goal-id').val();
        var entity = $(this).data('entity');
        var eventUrl = 'goal-buddy/deletegoal';
        var actionType = 'popup';
        if (goalConfirmDelete(goalId, entity, eventUrl, actionType)) {
            deleteGoal(goalId);
            processbarDiv.remove();
        }
    });

    $('.goal-name').click(function() {
        var currentTr = $(this).closest('tr');
        var currentMilestonesId = currentTr.find('.milestones').attr('id');
        var currentHabitsId = currentTr.find('.habits').attr('id');
        $('#' + currentMilestonesId).toggle();
        $('#' + currentMilestonesId).removeClass('hide');
        $('#' + currentHabitsId).toggle();
        $('#' + currentHabitsId).removeClass('hide');
    });
    //$('.show-weeks').hide();
    //$('.month-count').hide();
    $(".habit-friends-section").hide();
    $('.datepicker1').datepicker();
    //console.log()
    $('#datepicker_manage, #datepicker_SYG, #datepicker_SYG00,#gb_milestones_date,.datepicker_SYG4').datepicker({

        todayHighlight: 'TRUE',
        startDate: '-0d',
        autoclose: true,
        minDate: moment(),
        format: 'D, d M yyyy'
    });
    // $('.datepicker_SYG4').datepicker({
    //     todayHighlight: 'TRUE',
    //     startDate: '-0d',
    //     autoclose: true,
    //     format: 'D, d M yyyy'
    // });
    $('#create_habbit_btn').click(function() {
        initCustomValidator();
        var validForm = $('#build_new_habit_form').valid();
        var formdata = {};
        if (validForm) {
            var habitFromList = $("input[name=updatehabitfromlist]").val();
            // formdata['habit_id'] = $('#habit-id').val();
            formdata['habit_id'] = $('#goal-habit-id').val();
            // console.log($('#goal-habit-id').val());
            formdata['habit_name'] = $('#SYG_habits').val();
            formdata['habit_recurrence'] = $("input[name=SYG_habit_recurrence]").val();
            if (formdata['habit_recurrence'] == 'weekly') {
                var selectedWeeks = [];
                $('#viewport-3').find('div.prefTrainSlot').each(function() {
                    if ($(this).hasClass('active')) selectedWeeks.push($(this).find('input.goalEventRepeatWeekdays').val());
                });
                formdata['habit_weeks'] = selectedWeeks;
            } else if (formdata['habit_recurrence'] == 'monthly') {
                formdata['month'] = $(".month-date option:selected").text();
            }
            // formdata['habit_notes'] = $('#SYG_notes').val();  14-07-2021

            formdata['habit_notes'] = [];
            $('input[name="SYG_notes"]:checked').each(function(){
                formdata['habit_notes'].push($(this).val());
                if($(this).val() == 'Other'){
                    formdata['gb_habit_note_other'] = $('#habit_note_other').val();
                }
            });

            formdata['habit_milestone'] = prepareMultiSel($('#build_new_habit_form').find('select[name="milestone_value"]'));
            formdata['habit_seen'] = $("input[name=syg2_see_habit]:checked").val();
            formdata['habit_reminders'] = $("input[name=syg2_send_msg]:checked").val();
            formdata['last_insert_id'] = $('#last-insert-id').val();
            formdata['form_no'] = 3;
            savegoal(formdata);
            if (habitFromList != undefined && habitFromList == 'habitfromlist') {
                saveSuccess($('#build_new_habit_form'), {
                    action: 'show',
                    type: 'update'
                });
                setTimeout(function() {
                    window.location.href = document.referrer;
                }, 3000);
            } else {
                var nextKey = 0;
                var habitLength = $('.habit-listing table tbody tr .habit-edit').length;
                $('.habit-listing table tbody tr .habit-edit').each(function(key,obj){
                    var habitVal = $('#habit-id').val();
                    if(habitVal == $(this).data('habit-id')){
                        nextKey = key + 1;
                        if(nextKey >= habitLength){
                            $('.habit-form').hide();
                            $('.habit-listing').show();
                            $('.cancel_habbit_btn').addClass('hidden');
                            return false;
                        }
                    }
                    if(nextKey == key){
                        $(this).trigger('click');
                    }
                })
            }
        }
        return false;
    });
    $('#create_habbit_btn').click(function() {
        if ($('[name=habit_recurrence]:checked').length) {
            $('.error_radio').hide();
        } else {
            $('.error_radio').show();
            return false;
        }
    });
    $('input[name=habit_recurrence]').click(function() {
        if ($(this).is(':checked')) {
            $('.error_radio').hide();
        }
    })
    $('#custom_message').click(function() {
        $('#toggle_msg').toggle();
    });
    $('#manage_task_btn').click(function() {
        initCustomValidator();
        var validForms = $('#manage_task_form').valid();
      
        var formdata = {};
        // if (validForms){
        // console.log($('#viewport-4').find('input[name="goalTaskData"]').val());
        var taskFromCal = $('#task-from-cal').val();
        // formdata['task_id'] = $('#task-id').val();
        formdata['task_id'] = $('#viewport-4').find('input[name="goalTaskData"]').val();
        formdata['task_name'] = $('#SYG3_task').val();
        formdata['note'] = $('#note').val();
        formdata['task_priority'] = $("#SYG3_priority").val();
        formdata['task_habit_id'] = $('select[name="habit_value"]').val();
        formdata['task_seen'] = $("input[name=SYG3_see_task]").val();
        formdata['task_reminders'] = $("input[name=SYG3_send_msg]").val();
        formdata['task_recurrence'] = $("input[name=SYG_task_recurrence]").val();
        if (formdata['task_recurrence'] == 'weekly') {
            var selectedWeeks = [];
            // $('input[type="checkbox"]:checked.taskEventRepeatWeekdays').each(function() {
            $('#viewport-4').find('.prefTrainSlot').each(function() {
                let dayValue = $(this).find('.taskEventRepeatWeekdays').val();
                if ($(this).hasClass('active')) {
                    selectedWeeks.push(dayValue);
                }
            });
            formdata['task_weeks'] = selectedWeeks;
        } else if (formdata['task_recurrence'] == 'monthly') {
            formdata['month'] = $(".month-date-task option:selected").text();
        }
        formdata['form_no'] = 4;
        formdata['last_insert_id'] = $('#last-insert-id').val();
        savegoal(formdata);
        if (taskFromCal != undefined && taskFromCal == 'taskfromcal') {
            saveSuccess($('#manage_task_form'), {
                action: 'show',
                type: 'update'
            });
            setTimeout(function() {
                window.location.href = document.referrer;
            }, 2000);
        } else {
                var nextKey = 0;
                var taskLength = $('.task-listing table tbody tr .task-edit').length;
                $('.task-listing table tbody tr .task-edit').each(function(key,obj){
                    var taskVal = $('#task-id').val();
                    if(taskVal == $(this).data('task-id')){
                        nextKey = key + 1;
                        if(nextKey >= taskLength){
                            $('.task-form').hide();
                            $('.task-listing').show();
                            $('.cancel_task_btn').addClass('hidden');
                            return false;
                        }
                    }
                    if(nextKey == key){
                        $(this).trigger('click');
                    }
                })
        }
        // }
        return false;
    });
    /* goal buddy form initialization */
    FormWizard.init();
    /* Custom vailidation intilization */
    initCustomValidator();
    $('.reset_btn').click(function() {
        $('#search_form')[0].reset();
    });
    // $('body').on('click','.Btn_milestone',function() {
    //     console.log('hi');
    //     var milestoneValue = $('#Milestones').val();
    //     $('.mile_section ul').append('<li class="dd-item row" data-milestones-id="" style="line-height: 20px; !important"><div class="milestones-form"><div class=" col-md-4 milestones-date-cls"><input type="text" name ="milestones" class = "form-control milestones-name" value="' + milestoneValue + '" data-milestones-id=""></div><div class="col-md-4 milestones-date-cls" ><input type="text" class="form-control milestones-date datepicker_SYG4" autocomplete="off" name ="milestones-date"  required></div><div class="col-md-2 m-t-20 pencil_find_sibling"><a><i class="fa fa-times delete-milestone-info"  style="margin-right: 8px" data-milestones-id=" " ></i></a><a><i class="fa fa-pencil edit-milestone-info" style="font-size: 16px; margin-right: 8px" ></i></a><a><i class="fa fa-save save-milestone-info hidden"  data-milestones-id=" "></i></a></div></li>');
    //     var objget = $('li.dd-item:last').prev();
    //     var dueDate = $('#goalDueDate').val();
    //     var start = new Date(),
    //         end   = new Date(dueDate),
    //         diff  = new Date(end - start),
    //         days  = diff/1000/60/60/24;
    //         days = Math.round(days);
    //     if(objget.length == 0)
    //     {
    //         $('.datepicker_SYG4 ').datepicker({

    //             todayHighlight: 'TRUE',
    //             startDate: '-0d',
    //             autoclose: true,
    //             minDate: moment(),
    //             format: 'D, d M yyyy',
    //             endDate: '+'+days+'d',
    //             beforeShowDay: function(date) {
    //                 var day = date.getDate();
    //                 var month = date.getMonth() + 1;
    //                 var Year = date.getFullYear();
    //                 eventDate = Year+'-'+str_pad(month)+'-'+str_pad(day);
    //                 if (dueDate == eventDate) {
    //                     return {classes: 'highlight', tooltip: 'Title'};
    //                 }
    //             }
    //         });
    //     }
    //     var prevMileDate = new Date(objget.find('.milestones-date').val());
    //     var m_date = prevMileDate != null && prevMileDate != undefined ? new moment(prevMileDate).add(1, 'days') : new moment(prevMileDate);
    //     var objset = $('li.dd-item:last');
    //     $('.datepicker_SYG4').datepicker({
    //         todayHighlight: 'TRUE',
    //         startDate: m_date.toDate(),
    //         autoclose: true,
    //         format: "D, d M yyyy",
    //         endDate: '+'+days+'d',
    //         beforeShowDay: function(date) {
    //             var day = date.getDate();
    //             var month = date.getMonth() + 1;
    //             var Year = date.getFullYear();
    //             eventDate = Year+'-'+str_pad(month)+'-'+str_pad(day);
    //             if (dueDate == eventDate) {
    //                 return {classes: 'highlight', tooltip: 'Title'};
    //             }
    //         }
    //     });
    // });
    function str_pad(n) {
        return String("00" + n).slice(-2);
    }

    $(document).on('click', '.edit-milestone-info', function() {
        var ddi = $(this).closest('.dd-item'),
            inp = ddi.find('.milestones-name');
        inp.focus();
        inp.removeAttr('disabled');
        ddi.find('.milestones-date').removeAttr('disabled');
        ddi.find('.dd-handle').css("padding", "0px");

        // Eanabling edit milestone date section
        // var objget = $('li.dd-item:last').prev();
        var objget = $(this).parent().parent().parent().parent().prev();
        var prevMileDate = new Date(objget.find('.milestones-date').val());
        var m_date = prevMileDate != null && prevMileDate != undefined ? new moment(prevMileDate).add(1, 'days') : new moment(prevMileDate);
        var objset = $(this).parent().parent().parent().parent();

        objset.find('.datepicker_SYG4').datepicker({
            todayHighlight: 'TRUE',
            startDate: m_date.toDate(),
            autoclose: true,
            format: "D, d M yyyy",
        }); 
        $(this).hide();
    });
    $(document).on('click', '.habit-day-div', function() {
        var checked = $(this).is(":checked");
        var eventId = $(this).attr('data');
        var actionStatus;
        if (checked) actionStatus = 1;
        else actionStatus = 0;
        $.ajax({
            url: public_url + 'manage-status',
            method: "POST",
            data: {
                'actionStatus': actionStatus,
                'eventId': eventId
            },
            success: function(data) {
                var data = JSON.parse(data);
                if (data.status == "true") {
                    if (checked) {
                        $("input[id=" + eventId + "]").prop('checked', true);
                        $("input[id=" + data.updateHabitId + "]").prop('checked', true);
                    } else {
                        $("input[id=" + eventId + "]").prop('checked', false);
                        $("input[id=" + data.updateHabitId + "]").prop('checked', false);
                    }
                }
            }
        });
    });
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i].split('=');
        if (c[0].replace(/\s+/g, '') == "goalid") {
            goalid = c[1].replace(/\s+/g, '');
        }
    }
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i].split('=');
    }
    $('input[name="goal_hide"]').on('click', function() {
        if ($(this).prop("checked") == true) {
            $('tr.completed').hide('fast');
        } else {
            $('tr.completed').show('fast');
        }
    });
    // $(document).on('click', '.delete-milestone-info', function() {
    //     console.log('hii',$(this).closest('.dd-item'));
    //     var currentRow = $(this).closest('.dd-item');
    //     var milestonesId = $(this).data('milestones-id');
    //     $(currentRow).remove();
    //     if (milestonesId != '') {
    //         $.ajax({
    //             url: public_url + 'goal-buddy/deletemilestones',
    //             type: 'POST',
    //             data: {
    //                 'eventId': milestonesId
    //             },
    //             success: function(response) {
    //                 var data = JSON.parse(response);
    //                 if (data.status == 'true') {
    //                     createHabitDd(data.habitData);
    //                     showHabitList(data.habitData);
    //                     showTaskList(data.taskData);
    //                 }
    //             }
    //         });
    //     }
    // });
    $(document).on('change', '.save-milestone-info', function() {
console.log('check==========');
        var obj = $(this),
            ddi = obj.closest('.dd-item'),
            mValue = ddi.find('.milestones-name').val(),
            mDateValue = moment(ddi.find('.milestones-date').val(), 'ddd, D MMM YYYY').format("YYYY-MM-DD"),
            milestonesId = obj.data('milestones-id');
        // ddi.find('.milestones-name').attr('disabled', 'disabled');
        // ddi.find('.milestones-date').attr('disabled', 'disabled');
        obj.hide();
        ddi.find('.edit-milestone-info').show();
        var pre_goalId = $('#last-insert-id').val();
        if (typeof pre_goalId == 'undefined' || pre_goalId == '') pre_goalId = $('#goal_milestones_id').val();
        $.ajax({
            url: public_url + 'goal-buddy/updatemilestones',
            type: 'POST',
            data: {
                'milestonesId': milestonesId,
                'mValue': mValue,
                'mDateValue': mDateValue,
                'goalId': pre_goalId
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status == 'true') {
                    if (data.id > 0) {
                        ddi.data('milestones-id', data.id);
                    }
                    showMIlestoneDd();
                }
            }
        });
    });
    $(document).on('click', '.template-value', function() {
        $('#goal-template').val($(this).text());
        $('#demo10').modal('toggle');
    });
    $('.Step-your-goal1').click(function() {
        $('#wizard').smartWizard('goToStep', 1);
        return false;
    });
    $('.Step-your-goal2').click(function() {
        $('#wizard').smartWizard('goToStep', 4);
        return false;
    });
    $('.Step-your-goal3').click(function() {
        $('#wizard').smartWizard('goToStep', 3);
        return false;
    })
    $('.Step-your-goal4').click(function() {
    //     var check = $('#wrapped .wizard-step.current');
    //     check.removeClass('current');
    //     check.hide();
    //     console.log('hi shivi',$('.milestoneData'));
    //     $('.milestoneData').addClass('current');
    //     $('#wrapped').find('.milestoneData').removeAttr('style');
    //    ;
    //    $('.submit-step').attr('disabled','disabled');
    //    $('.nextData').removeAttr('disabled');

        return false;
    })
    $('.submit-first-form').click(function() {
        console.log('hiiii', $('#name_goal').val());
        form = $('#edit_goal_form');
        var isFormValid = $('#edit_goal_form').valid();
        // alert(isFormValid);
        if (isFormValid) {
            var formdata = {};
            //var allMilestonesNames = [];
            //var allMilestonesDates = [];
            var goalFromCal = $('#goal-from-cal').val();
            var goaldeudate = $('#datepicker_SYG').val();
            formdata['gb_change_life_reason_other'] = $('#gb_change_life_reason_other').val();
            formdata['name'] = $('#name_goal').val();
            formdata['describe_achieve'] = $("#describe_achieve").val();
            formdata['goal_year'] = $("input[name=goal_year]").val();
            var lc = $("#change_life").val();
            formdata['change_life'] = $("#change_life").val();
            console.log(formdata['change_life']);
            $.inArray('Other',formdata['change_life'])
			{	 
				formdata['gb_change_life_reason_other'] = $('#gb_change_life_reason_other').val();
			}
            formdata['accomplish'] = $('#accomplish').val();
            formdata['failDescription'] = $('#fail-description').val();
            formdata['gb_relevant_goal'] = $('#gb_relevant_goal').val();
            formdata['gb_relevant_goal_event'] = $('#gb_relevant_goal_event').val();
            formdata['image'] = $('input[name="prePhotoName"]').val();;
            formdata['template'] = $('#goal-template').val();
            //formdata['due_date'] = $('#datepicker_SYG').val();
            formdata['goal_seen'] = $("input[name=goal_seen]").val();
            formdata['send_msg_type'] = $("input[name=send_msgss]").val();
            if (goaldeudate != '') {
                formdata['due_date'] = moment(goaldeudate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");
            }
            formdata['form_no'] = 1;
            formdata['update_status'] = 'update-yes';
            formdata['last_insert_id'] = $('#last-insert-id').val();
            savegoal(formdata);
            saveSuccess(form, {
                action: 'show',
                type: 'update'
            });
            if (goalFromCal != undefined && goalFromCal == 'goalfromcal') {
                setTimeout(function() {
                    window.location.href = document.referrer;
                }, 2000);
            }
        }
        scrollToTop(form);
    });
    $(document).on('click', '.delete-habit', function() {
        var processbarDiv = $(this).closest('tr');
        var habitId = $(this).data('habit-id');
        var entity = $(this).data('entity');
        swal({
            title: "Are you sure to delete this " + entity + "?",
            text: (typeof warningText != 'undefined' && warningText) ? warningText : '',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d43f3a",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: true,
            customClass: 'delete-alert'
        }, function() {
            $(document).on('click', '.confirm', function(e) {
                $.ajax({
                    url: public_url + 'goal-buddy/deletehabit',
                    type: 'POST',
                    data: {
                        'eventId': habitId
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status == 'true') {
                            createHabitDd(data.habitData);
                            showHabitList(data.habitData);
                            showTaskList(data.taskData);
                            processbarDiv.remove();
                            //location.reload();
                        }
                    }
                });
            });
        });
    });
    /*$( document ).on( 'click', '.habit-edit', function() {
        var processbarDiv = $(this).closest('tr');
        var habitId = $(this).data('habit-id');
        var isJump = "false";
        
        $('.habit-form').show();
        $('.habit-listing').hide();
        $.ajax({
            url: public_url+'goal-buddy/showhabit',
            type: 'POST',
            data: {'habitId':habitId},
            success: function(response) {
                var data = JSON.parse(response);
                if(data.status == 'true'){
                    $('.cancel_habbit_btn').removeClass('hidden');
                    if(data.milestonesTask != '')
                    showMIlestoneDd(data.goalBuddy.gb_milestones_id.split(','));
                    else
                    $('.milestone-dropdown').hide();   
                    
                    $('#habit-id').val(data.goalBuddy.id);
                    $('#SYG_habits').val(data.goalBuddy.gb_habit_name);
                    var habit_recurrence_type = data.goalBuddy.gb_habit_recurrence_type;
                    
                    setTimeout(function() {
                        $('#SYG_habits').trigger('change');
                        // habit recurrence
                        $('#viewport-3').find('.SYG_habit_recurrence_wrapper .click-box').find('li[data-recurrence-type="'+habit_recurrence_type+'"]').trigger('click').trigger('change');
                        
                        if(habit_recurrence_type === 'weekly') {
                            var days = data.goalBuddy.gb_habit_recurrence_week;
                            days = days.split(',');
                            
                            for(var i = 0; i < days.length; i++) {
                                $('#viewport-3').find('.SYG_habit_recurrence_wrapper .show-weeks').find('div[data-day="'+days[i]+'"]').trigger('click').trigger('change');
                            }
                        }
                        
                        if(habit_recurrence_type === 'monthly') {
                            var month = data.goalBuddy.gb_habit_recurrence_month;
                            
                            $('#viewport-3').find('.SYG_habit_recurrence_wrapper .month-count').find('.month-date').val(month).trigger('change');
                            
                        }
                        
                        // who can see this habit
                        var habit_seen = data.goalBuddy.gb_habit_seen;
                        $('#viewport-3').find('#wrapper_syg2_see_habit0').find('li[data-value="'+habit_seen+'"]').trigger('click').trigger('change');
                        
                        // habit reminder
                        var habit_reminder = data.goalBuddy.gb_habit_reminder;
                        $('#viewport-3').find('#wrapper_syg2_send_msg0').find('li[data-value="'+habit_reminder+'"]').trigger('click').trigger('change');
                        
                        jQuery('html, body').stop().animate({
                            scrollTop: $('#viewport-3').find('li[data-index=0]').offset().top - 50
                        }, 'slow');
                    }, 700);
                    
                    $("input[name=SYG_habit_recurrence]").val(data.goalBuddy.gb_habit_recurrence);
                    
                    $('#SYG_notes').val(data.goalBuddy.gb_habit_name);
                    $(".milestone_div_class option:selected").val(data.goalBuddy.gb_milestones_name);
                    $("select#milestone_div").val(data.goalBuddy.gb_milestones_id).selectpicker("refresh");
                    
                    $("input[name=syg2_see_habit]").val(data.goalBuddy.gb_habit_name);
                    $("input[name=syg2_send_msg]").val(data.goalBuddy.gb_habit_name);
                    $('#last-insert-id').val(data.goalBuddy.goal_id);
                    $('#SYG_notes').val(data.goalBuddy.gb_habit_notes);
                }  
            }
        });
    });*/
    $(document).on('click', '#SYG_habit_recurrence0', function() {
        $('.show-weeks').hide();
        $('.month-count').hide();
    });
    $(document).on('click', '#SYG_habit_recurrence1', function() {
        $('.show-weeks').show();
        $('.month-count').hide();
    });
    $(document).on('click', '#SYG_habit_recurrence2', function() {
        $('.month-count').show();
        $('.show-weeks').hide();
        var totalDayInMonth = getDaysInMonth();
        var selector = "";
        $('.month-count .month-count-div').remove();
        selector += "<div class ='month-count-div'>Day <select class='month-date'>";
        for (var i = 1; i <= totalDayInMonth; i++) {
            selector += "<option value =" + i + ">" + i + "</option>";
        }
        selector += "</select> of every month</div>";
        $('.month-count').append(selector);
        selector = '';
    });
    $(document).on('click', '#syg2_see_habit1', function() {
        $(".habit-friends-section").show();
    });
    $(document).on('click', '#syg2_see_habit0', function() {
        $(".habit-friends-section").hide();
    });
    $(document).on('click', '#syg2_see_habit2', function() {
        $(".habit-friends-section").hide();
    });
    /*$( document ).on( 'click', '.task-edit', function() {
        var processbarDiv = $(this).closest('tr');
        var taskId = $(this).data('task-id');
        $('.task-form').show();
        $('.task-listing').hide();
        $.ajax({
            url: public_url+'goal-buddy/showtask',
            type: 'POST',
            data: {'taskId':taskId},
            success: function(response) {
                var data = JSON.parse(response);
                $('.cancel_task_btn').removeClass('hidden');
                if(data.status == 'true'){
                    if(data.habitTask != ''){
                        $('.task-habit-div').show();
                        var optionValue = '<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';
                        
                        $.each(data.habitTask,function(key, value) {
                            if(value.id==data.goalBuddy.gb_habit_id)
                            optionValue += '<option value="'+value.id+'" selected>'+value.gb_habit_name+'</option>';
                            else
                            optionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
                        });
                        optionValue += '</select>';
                        $('.task-habit-dropdown').html(optionValue);
                        initSelectpicker($('.task-habit-dropdown select'));
                        
                        $('.taskhabit_div_class').selectpicker('refresh');
                        optionValue = '';
                    }
                    else{ 
                        $('.task-habit-div').hide();   
                    }
                    
                    $('#task-id').val(data.goalBuddy.id);
                    $('#SYG3_task').val(data.goalBuddy.gb_task_name);
                    
                    $('#SYG3-time').val(data.goalBuddy.gb_task_time);
                    $("#SYG3_priority").val(data.goalBuddy.gb_task_priority);
                    $("input[name=SYG3_see_task]").val(data.goalBuddy.gb_task_seen);
                    $("input[name=SYG3_send_msg]").val(data.goalBuddy.gb_task_reminder);
                    $('select#habit_div').val(data.goalBuddy.gb_habit_id).selectpicker("refresh");
                    $('#note').val(data.goalBuddy.gb_task_note);
                    
                    var task_priority = data.goalBuddy.gb_task_priority,
                    task_recurrence_type = data.goalBuddy.gb_task_recurrence_type,
                    task_recurrence_week = data.goalBuddy.gb_task_recurrence_week,
                    task_recurrence_month = data.goalBuddy.gb_task_recurrence_month,
                    task_seen = data.goalBuddy.gb_task_seen,
                    task_reminder = data.goalBuddy.gb_task_reminder;
                    
                    setTimeout( function(){
                        $('#SYG3_task').trigger('change');
                        
                        // task_priority
                        $('#viewport-4').find('#gb_task_priority_wrapper').find('li[data-value="'+task_priority+'"]').trigger('click').trigger('change');
                        
                        // start: task_recurrence_type
                        $('#viewport-4').find('#gb_task_recurrence_type_wrapper').find('li[data-value="'+task_recurrence_type+'"]').trigger('click').trigger('change');
                        
                        if(task_recurrence_type === 'weekly') {
                            var days = task_recurrence_week.split(',');
                            for(var i = 0; i < days.length; i++) {
                                $('#viewport-4').find('#gb_task_recurrence_weeks').find('div[data-value="'+days[i]+'"]').trigger('click').trigger('change');
                            }
                        }
                        
                        if(task_recurrence_type === 'monthly') {
                            $('#viewport-4').find('#gb_task_recurrence_month').val(task_recurrence_month).trigger('change');
                        }
                        // end: task_recurrence_type
                        
                        // task_seen
                        $('#viewport-4').find('#gb_task_seen_wrapper').find('li[data-value="'+task_seen+'"]').trigger('click').trigger('change');
                        
                        // task_reminder
                        $('#viewport-4').find('#gb_task_reminder_wrapper').find('li[data-value="'+task_reminder+'"]').trigger('click').trigger('change');
                        
                        jQuery('html, body').stop().animate({
                            scrollTop: $('#viewport-4').find('li[data-index=0]').offset().top - 50
                        }, 'slow');
                        
                    }, 700);
                }  
            }
        });
    });*/
    $(document).on('change', '#habit_div', function() {
        var habitId = $('#habit_div').val();
        /*var templateCheck = $("input[name='chooseGoal']:checked").val() == 'choose_form_template' ? true : false;

        if(!templateCheck){
            $(".tsk_rec_daily").hide();
            $(".hbt_rec_monthly").hide();
        }*/

        if (habitId != '' && habitId != null && habitId != undefined && habitId != " ") {
            $.ajax({
                url: public_url + 'goal-buddy/showhabit',
                type: 'POST',
                data: {
                    'habitId': habitId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    //console.log(data);
                    if (data.status == "true") {
                        // setTimeout(function() {
                            // start: task_recurrence_type
                            $('#viewport-4').find('input[name="associatedHabitWithTask"]').val(JSON.stringify(data.goalBuddy));
                            $('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').children('li').addClass('disabled_task_recurrence');
                            
                            /*if (data.goalBuddy.gb_habit_recurrence_type == 'daily') {*/
                                // $('#SYG_task_recurrence0').prop('checked', true);
                                // $('.show-weeks-task').hide();
                                // $('.month-count-task').hide();

                            //remove old selected data
                            $('.taskEventRepeatWeekdays').each(function(){
                                $(this).prop('checked',false);
                            });
                            $('input[name="SYG_task_recurrence"][value="weekly"]').prop('checked', false);
                            //remove old selected data - end

                            if(data.goalBuddy.gb_habit_recurrence_type == 'daily'){
                                $("#hbt_rec_tsk").val('daily');
                                $('input[name="SYG_task_recurrence"][value="weekly"]').trigger('click');
                                //prop('checked', true);
                                $('.taskEventRepeatWeekdays').each(function(){
                                        $(this).prop('checked',true);
                                });
                            }else{
                                $("#hbt_rec_tsk").val('none');
                            }

                            if (data.goalBuddy.gb_habit_recurrence_type == 'weekly') {
                                //console.log( data.goalBuddy);
                                $('#viewport-4').find('#gb_task_recurrence_type_wrapper').find('li[data-value="weekly"]').trigger('click').trigger('change');
                                $('#SYG_task_recurrence1').prop('checked', true);
                                $('.show-weeks-task').show();
                                $('.month-count-task').hide();
                                var weekData, days;
                                if(data.goalBuddy.gb_habit_recurrence_type == 'weekly') {
                                    weekData = data.goalBuddy.gb_habit_recurrence_week;
                                    days = weekData.split(',');
                                } else {
                                    weekData = 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday';
                                    days = weekData.split(',');
                                }
                                var recWeek = data.goalBuddy.gb_habit_recurrence_week.split(',');
                                if(data.goalBuddy.gb_habit_recurrence_type == 'weekly'){
                                    $('.taskEventRepeatWeekdays').each(function(){
                                        if(jQuery.inArray($(this).val(), recWeek) !== -1){
                                            $(this).prop('checked',true);
                                        }	
                                    })
                                    $('.taskNext .showDayBox').show(); 
                                    $('input[name="SYG_task_recurrence"][value="weekly"]').prop('checked', true);
                                   $('#habitRecValue').val(recWeek);
                                   $('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
										//$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
                                }/*else if(data.goalBuddy.gb_habit_recurrence_type == 'daily'){
                                    $('input[name="SYG_task_recurrence"][value="daily"]').prop('checked', true);
                                    $('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
                                    $('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
                                    $('.taskNext .showDayBox').hide(); 
                                }*/
                                
                                var checkBoxLi = $('#viewport-4').find('#gb_task_recurrence_weeks');
                                var dayDiv = '';
                                checkBoxLi.find('div.prefTrainSlot').removeClass('active');
                                checkBoxLi.find('div.prefTrainSlot').addClass('inactive');
                                // checkBoxLi.find('div.prefTrainSlot').find('input.taskEventRepeatWeekdays').attr("checked", false);
                                checkBoxLi.find('div.prefTrainSlot').find('input.taskEventRepeatWeekdays').removeAttr("checked");
                                checkBoxLi.find('div.prefTrainSlot').removeClass('taskEventRepeatWeekdayNotInHabit');
                                for (var i = 0; i < days.length; i++) {
                                    var dayDiv = checkBoxLi.find('div[data-value="' + days[i] + '"]');
                                    dayDiv.addClass('active');
                                    dayDiv.removeClass('inactive');
                                    // dayDiv.find('input.taskEventRepeatWeekdays').attr("checked", true);
                                    dayDiv.find('input.taskEventRepeatWeekdays').attr("checked", "checked");
                                }

                                if(data.goalBuddy.gb_habit_recurrence_type == 'weekly') {
                                    checkBoxLi.find('div.prefTrainSlot').each(function(index, value) {
                                        let dayValue = $(this).find('input.taskEventRepeatWeekdays').val();
                                        if ($.inArray(dayValue, days) == -1) {
                                            $(this).addClass('taskEventRepeatWeekdayNotInHabit');
                                        }
                                    });
                                }
                            } /*else if (data.goalBuddy.gb_habit_recurrence_type == 'monthly') {
                                 $('#viewport-4').find('#gb_task_recurrence_type_wrapper').find('li[data-value="monthly"]').trigger('click').trigger('change');
                                $('#SYG_task_recurrence2').prop('checked', true);
                                var monthDate = retnum(data.goalBuddy.gb_habit_recurrence_month);
                                $('.month-count-task').show();
                                $('.show-weeks-task').hide();
                                if(data.goalBuddy.gb_habit_recurrence_type == 'monthly'){
                                    $('input[name="SYG_task_recurrence"][value="monthly"]').prop('checked', true);
                                    $('.taskNext .showMonthBox').show();
                                    $('#gb_task_recurrence_month').val(data.goalBuddy.gb_habit_recurrence_month).selectpicker('refresh');
                                }
                                $('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
                                        $('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
                                        $('.taskNext .showDayBox').hide(); 
                                // var totalDayInMonth = getDaysInMonth();
                                // var selector = "";
                                // $('.month-count-task.month-count-task-div').remove();
                                // selector += "<div class ='month-count-task-div'>Day <select class='month-date-task'>";
                                // for (var i = 1; i <= totalDayInMonth; i++) {
                                //     if (i == monthDate) selector += "<option value =" + i + " selected>" + i + "</option>";
                                //     else selector += "<option value =" + i + ">" + i + "</option>";
                                // }
                                // selector += "</select> of every month</div>";
                                // $('.month-count-task').append(selector);
                                // selector = '';
                            }*/
                        // });
                    }
                }
            });
        }
    });
    $(document).on('click','.save-notes',function() {
        $('#update-record').val('update-yes');
        $(this).closest("#general-notes-popup").hide();
        $(this).closest("#general-notes-popup").removeClass('in show');
        $(".data-step").trigger('change');
    })
    
    $(document).on('click', '.delete-task', function() {
        var processbarDiv = $(this).closest('tr');
        var taskId = $(this).data('task-id');
        var entity = $(this).data('entity');
        swal({
            title: "Are you sure to delete this " + entity + "?",
            text: (typeof warningText != 'undefined' && warningText) ? warningText : '',
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d43f3a",
            confirmButtonText: "Yes, delete it!",
            allowOutsideClick: true,
            customClass: 'delete-alert'
        }, function() {
            $(document).on('click', '.confirm', function(e) {
                $.ajax({
                    url: public_url + 'goal-buddy/deletetask',
                    type: 'POST',
                    data: {
                        'eventId': taskId
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status == 'true') {
                            processbarDiv.remove();
                            //location.reload();
                        }
                    }
                });
            });
        });
    });

    $(document).on('click', '#SYG_task_recurrence0', function() {
        $('.show-weeks-task').hide();
        $('.month-count-task').hide();
    });
    $(document).on('click', '#SYG_task_recurrence1', function() {
        $('.show-weeks-task').show();
        $('.month-count-task').hide();
    });
    $(document).on('click', '#SYG_task_recurrence2', function() {
        $('.month-count-task').show();
        $('.show-weeks-task').hide();
        var totalDayInMonth = getDaysInMonth();
        var selector = "";
        $('.month-count-task .month-count-task-div').remove();
        selector += "<div class ='month-count-task-div'>Day <select class='month-date-task'>";
        for (var i = 1; i <= totalDayInMonth; i++) {
            selector += "<option value =" + i + ">" + i + "</option>";
        }
        selector += "</select> of every month</div>";
        $('.month-count-task').append(selector);
        selector = '';
    });
    $(document).on('click', '#SYG3_see_milestones1', function() {
        $(".milestones-friends-section").show();
    });
    $(document).on('click', '#SYG3_see_milestones0', function() {
        $(".milestones-friends-section").hide();
    });
    $(document).on('click', '#SYG3_see_milestones2', function() {
        $(".milestones-friends-section").hide();
    });
    $(document).on('click', '#SYG3_see_task1', function() {
        $(".task-friends-section").show();
    });
    $(document).on('click', '#SYG3_see_task0', function() {
        $(".task-friends-section").hide();
    });
    $(document).on('click', '#SYG3_see_task2', function() {
        $(".task-friends-section").hide();
    });
    /* edit habit from goal listing */
    $(document).on('click', '.edit-habit', function(e) {
        e.preventDefault();
        var habitId = $(this).data('edithabit');
        // console.log(habitId);
        window.location.href = public_url + 'goal-buddy/edithabit/' + habitId;
    });
    $(document).on('click', '.delete-habit-list', function(e) {
        e.preventDefault();
        var habitId = $(this).data('deletehabitid');
        var entity = $(this).data('entity');
        var actionType = 'popup';
        var eventUrl = 'goal-buddy/deletehabit';
        goalConfirmDelete(habitId, entity, eventUrl, actionType)
    });
    $(document).on('click', '.milestone-goal', function(e) {
        var checked = $(this).is(":checked");
        var processbarDiv = $(this).closest('tr');
        var processbarTd = processbarDiv.find('td .progress');
        var milestonesId = $(this).data('milestones-id');
        var percentage = $(this).data('percentage');
        var processbarPercentage = processbarDiv.find('td .progress-percentage');
        var processbarPercentageStyle = processbarDiv.find('td .progress-bar');
        // var count = $(this).closest('tr').find("input[type=checkbox]:checked").size();
        var count = $(this).closest('tr').find("input[type=checkbox]:checked").length;
        var totalpersent = count * percentage;
        var roundedTotalpersent = totalpersent.toFixed(2);
        var goalId = processbarDiv.find('input[name="goal_id"]').val();
        if(roundedTotalpersent == 100)
        var goalStatus = 1;
        else
        var goalStatus = 0;
        updateGoalStatus(goalId,goalStatus,processbarDiv);

        if (checked == true) {
            processbarPercentageStyle.attr('style', 'width:' + totalpersent + '%');
            processbarPercentage.text(totalpersent.toFixed(2) + '%');
            updateMilestones(milestonesId, 1);
        } else {
            processbarPercentageStyle.attr('style', 'width:' + totalpersent + '%');
            processbarTd.removeClass('progress-bar-success');
            if (totalpersent == '') processbarPercentage.text('0%');
            else processbarPercentage.text(totalpersent.toFixed(2) + '%');
            updateMilestones(milestonesId, 0);
        }
    });

    $(document).on('click', '.listing-task-name', function() {
        var taskId = $(this).attr('data');
        var toDay = moment().format("YYYY-MM-DD");
        $.ajax({
            url: public_url + 'goal-buddy/get-listing-task',
            type: 'POST',
            data: {
                'taskId': taskId
            },
            success: function(data) {
                var data = JSON.parse(data);
                var taskdayHtml = '';
                if (data.status == true) {
                    $(".popup-edit-task").data('task-id', data.taskDetails.id);
                    $(".popup-delete-task").data("task-id", data.taskDetails.id);
                    $('.task-name').text(data.taskDetails.gb_task_name);
                    $('.priority').text(data.taskDetails.gb_task_priority);
                    $('.notes').text(data.taskDetails.gb_task_note);
                    $('.shared').text(ucfirst(data.taskDetails.gb_task_seen));
                    $('.frequency').text(ucfirst(data.taskDetails.gb_task_recurrence_type));
                    $('.goal-with-habit').text(data.taskDetails.task_habit_name);
                    /*if (data.taskupdateData.length > 0) {
                        $.each(data.taskupdateData, function(index, value) {
                            var dueTaskDate = moment(value.due_date).format("ddd, D MMM YYYY");
                            taskdayHtml += '<div class="checkbox clip-check check-primary"><input type="checkbox" name="goal_hide" id="popuptask_' + value.id + '" value="1" class="habit-day-div" data ="' + value.id + '" data-type ="' + data.taskdata[0].taskRecurrenceType + '"';
                            if (value.status == 1) {
                                taskdayHtml += 'checked=""';
                            }
                            taskdayHtml += '><label for="popuptask_' + value.id + '">' + dueTaskDate + '</label></div>';
                        });
                    }
                    $('.unchecked-days-task').html(taskdayHtml);*/
                }
            }
        });
    });
    $(document).on('click', '.listing-milestone-name', function() {
        var milestoneId = $(this).attr('data');
        $.ajax({
            url: public_url + 'goal-buddy/get-listing-milestone',
            type: 'POST',
            data: {
                'milestoneId': milestoneId
            },
            success: function(data) {
                var data = JSON.parse(data);
                var milestonedayHtml = '';
                if (data.status == true) {
                    var duemilestoneDate = moment(data.milestoneDetails.gb_milestones_date).format("ddd, D MMM YYYY");
                    $(".popup-edit-milestone").data('milestone-id', data.milestoneDetails.id);
                    $(".popup-delete-milestone").data("milestone-id", data.milestoneDetails.id);
                    $('.milestone-name').text(data.milestoneDetails.gb_milestones_name);
                    $('.shared').text(data.milestoneDetails.gb_milestones_seen);
                    $('.due-date').text(data.milestoneDetails.gb_milestones_date);
                    // if (data.mileupdateData.length > 0) {
                    //     $.each(data.mileupdateData, function(index, value) {
                    //         milestonedayHtml += '<div class="checkbox clip-check check-primary"><input type="checkbox" name="goal_hide" id="popupmile_' + value.id + '" value="1" class="habit-day-div" data ="' + value.id + '"';
                    //         if (value.status == 1) milestonedayHtml += 'checked';
                    //         milestonedayHtml += '><label for="popupmile_' + value.id + '">' + data.milestones.gb_milestones_name + '</label></div>';
                    //     });
                    // }
                    // $('.unchecked-days-mileston').html(milestonedayHtml);
                }
            }
        });
    });
    $(document).on('click', '.listing-goal-name', function() {
        var goalId = $(this).attr('data');
        $.ajax({
            url: public_url + 'goal-buddy/get-listing-goal',
            type: 'POST',
            data: {
                'goalId': goalId
            },
            success: function(data) {
                var data = JSON.parse(data);
                if (data.status == true) {
                    $(".popup-edit-goal").data('goal-id', data.goalDetails.id);
                    $(".popup-delete-goal").data("goal-id", data.goalDetails.id);
                    $('.goal-name').text(data.goalDetails.gb_goal_name);
                    $('.accomplish').text(data.goalDetails.gb_achieve_description);
                    $('.goal-important').text(data.goalDetails.gb_fail_description);
                    $('.goal-shared').text(data.goalDetails.gb_goal_seen);
                    $('.goal-due-date').text(data.goalDetails.gb_due_date);
                }
            }
        });
    });
    /// edit goal 
    $(document).on('click', '.popup-edit-goal', function(e) {
        e.preventDefault();
        var goalId = $(this).data('goal-id');
        window.location.href = public_url + 'goal-buddy/editgoal/' + goalId;
    });
    
    $(document).on('click', '.popup-delete-goal', function() {
        var goalId = $(this).data('goal-id');
        var entity = $(this).data('entity');
        var eventUrl = 'goal-buddy/deletegoal';
        var actionType = 'popup';
        goalConfirmDelete(goalId, entity, eventUrl, actionType)
    });
    /* edit task */
    $(document).on('click', '.popup-edit-task', function(e) {
        e.preventDefault();
        var taskId = $(this).data('task-id');
        // console.log(taskId);
        window.location.href = public_url + 'goal-buddy/edittask/' + taskId;
    });
    /* edit milestone */
    $(document).on('click', '.popup-edit-milestone', function(e) {
        e.preventDefault();
        var milestoneId = $(this).data('milestone-id');
        window.location.href = public_url + 'goal-buddy/editmilestone/' + milestoneId;
    });
    $(document).on('click', '.popup-delete-task', function() {
        var milestoneId = $(this).data('milestone-id');
        var entity = $this.data('entity');
        var eventUrl = 'goal-buddy/deletetask';
        var actionType = 'popup';
        goalConfirmDelete(milestoneId, entity, eventUrl, actionType)
    });
    $(document).on('click', '.popup-delete-milestone', function() {
        // console.log('Milestones Edit Clicked');
        var milestoneId = $(this).data('milestone-id');
        // console.log(milestoneId);
        var entity = $this.data('entity');
        var eventUrl = 'goal-buddy/deletemilestones';
        var actionType = 'popup';
        goalConfirmDelete(milestoneId, entity, eventUrl, actionType)
    });
    $(document).on('click', '.milestone-edit', function() {
        var processbarDiv = $(this).closest('tr');
        var milestoneId = $(this).data('milestone-id');
        $('.milestone-form').show();
        $('.milestone-listing').hide();
        $.ajax({
            url: public_url + 'goal-buddy/showmilestone',
            type: 'POST',
            data: {
                'milestoneId': milestoneId
            },
            success: function(response) {
                var data = JSON.parse(response);
                $('.cancel_milestone_btn').removeClass('hidden');
                if (data.status == 'true') {
                    $('#milestones_id').val(data.milestones.id);
                    $('#gb_milestones_name').val(data.milestones.gb_milestones_name);
                    if (data.milestones.gb_milestones_date != '') $('#gb_milestones_date').val(moment(data.milestones.gb_milestones_date).format("ddd, D MMM YYYY"));
                    if (data.milestones.gb_milestones_seen == 'everyone') $('#SYG3_see_milestones0').prop('checked', true);
                    else if (data.milestones.gb_milestones_seen == 'milestones-friends') $('#SYG3_see_milestones1').prop('checked', true);
                    else if (data.milestones.gb_milestones_seen == 'Just Me') $('#SYG3_see_milestones2').prop('checked', true);
                    if (data.milestones.gb_milestones_reminder == 'when_overdue') $('#send_msgss_milestones1').prop('checked', true);
                    else if (data.milestones.gb_milestones_reminder == 'daily') $('#send_msgss_milestones2').prop('checked', true);
                    else if (data.milestones.gb_milestones_reminder == 'weekly') $('#send_msgss_milestones3').prop('checked', true);
                    else if (data.milestones.gb_milestones_reminder == 'monthly') $('#send_msgss_milestones4').prop('checked', true);
                    else if (data.milestones.gb_milestones_reminder == 'none') $('#send_msgss_milestones5').prop('checked', true);
                }
            }
        });
    });
    $(document).on('click', '.delete-milestone', function() {
        var processbarDiv = $(this).closest('tr');
        var milestoneId = $(this).data('milestone-id');
        processbarDiv.remove();
        $.ajax({
            url: public_url + 'goal-buddy/deletemilestone',
            type: 'POST',
            data: {
                'milestoneId': milestoneId
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status == 'true') {
                    //location.reload();
                }
            }
        });
    });
    $('#create_milestone_btn').click(function() {
        initCustomValidator();
        var validForm = $('#build_new_milestone_form').valid();
        var formdata = {};
        var allMilestonesNames = [];
        var allMilestonesNamesId = [];
        var allMilestonesDates = [];
        if (validForm) {
            var milestoneFromList = $("input[name=updatemilestonefromlist]").val();
            var milestonesDueDate = $('input[name="gb_milestones_date"]').val();
            formdata['milestones_id'] = $('#milestones_id').val();
            $('.milestones-name').each(function() {
                var milestonesNames = $(this).val();
                var milestonesId = $(this).data('milestones-id');
                if (milestonesNames != '') {
                    if (milestonesId != '') allMilestonesNamesId.push(milestonesId + ':' + milestonesNames);
                    else allMilestonesNames.push(milestonesNames);
                }
            });
            $('.datepicker_SYG4').each(function() {
                if ($(this).val() != '') {
                    allMilestonesDates.push($(this).val());
                }
            });
            if (allMilestonesNames.length > 0) {
                formdata['milestones-names'] = allMilestonesNames;
            }
            if (allMilestonesNamesId.length > 0) {
                formdata['milestones-names-id'] = allMilestonesNamesId;
            }
            if (allMilestonesDates.length > 0) {
                formdata['milestones-dates'] = allMilestonesDates;
            }
            formdata['gb_milestones_seen'] = $("input[name=gb_milestones_seen]").val();
            formdata['gb_milestones_reminder'] = $("input[name=gb_milestones_reminder]").val();
            formdata['last_insert_id'] = $('#last-insert-id').val();
            formdata['goal_id_mile'] = $('#goal_milestones_id').val();
            formdata['form_no'] = 2;
            savegoal(formdata);
            saveSuccess($('#build_new_milestone_form'), {
                action: 'show',
                type: 'update'
            });
            if (milestoneFromList != undefined && milestoneFromList == 'milestonefromlist') {
                saveSuccess($('#build_new_milestone_form'), {
                    action: 'show',
                    type: 'update'
                });
                setTimeout(function() {
                    window.location.href = document.referrer;
                }, 3000);
            }
        }
        return false;
    });
});

function showMIlestoneDd(defaults) {
    var milestones = $('.mile_section .dd-item');
    $('.milestone-div').show();
    var optionValue = '<select id="milestone_div" name="milestone_value" class="selectpicker form-control onchange-set-neutral milestone_div_class" multiple="" data-actions-box="true">';
    var url = window.location.href;
    var result = url.split('/');
    var Param = result[result.length - 3];
    var Param1 = result[result.length - 2];
    $.each(milestones, function(k, obj) {
        if ((Param == 'goal-buddy') && (Param1 == 'edit')) {
        // var v = $(obj).find('.save-milestone-info').data('milestones-id'),
         var v = $(obj).find('.milestones-name').data('milestones-id'),
             t = $(obj).find('.milestones-name').val();
             console.log('alert=== ',obj, v , t);
            if (defaults && $.inArray(v + "", defaults) >= 0){
                optionValue += '<option value="' + v + '" selected>' + t + '</option>';
            }else{
                optionValue += '<option value="' + v + '">' + t + '</option>';
            }  

        } else {
            console.log('alert=== ',obj, v , t);
           var v = $(obj).closest('.dd-item').data('milestones-id'),
             t = $(obj).find('.milestones-name').val();
             optionValue += '<option value="' + v + '">' + t + '</option>';
        }
        // console.log('alert=== ',obj, v , t);
        // if (defaults && $.inArray(v + "", defaults) >= 0){
        //     // optionValue += '<option value="' + v + '" selected>' + t + '</option>';
        // }else{
        //     optionValue += '<option value="' + v + '">' + t + '</option>';
        // } 
    });
    optionValue += '</select>';
    $('.milestone-dropdown').html($(optionValue));
    initSelectpicker($('.milestone-dropdown select'));

    $('.milestone_div_class').selectpicker('refresh');
    
}
/*function showMIlestoneDd(milestonesData, defaults) {
    var optionValue = '<select id="milestone_div" name="milestone_value" class="selectpicker form-control onchange-set-neutral milestone_div_class" required="" multiple=""><option value="">-- Select --</option>';
    $('.milestone-div').show();
    if(milestonesData){
        
    }
    else{
        var milestones = $('.mile_section .dd-item');
        $.each(milestones, function(k,obj) {
            optionValue += '<option value="'+$(obj).find('.save-milestone-info').data('milestones-id')+'">'+$(obj).find('.milestones-name').val()+'</option>';
        });
    }
    optionValue += '</select>';
    $('.milestone-dropdown').html($(optionValue));
    initSelectpicker($('.milestone-dropdown select'));
    $('.milestone_div_class').selectpicker('refresh');
}*/
function createHabitDd(habitData, val) {
   
    $('.task-habit-div').show();
    var taskoptionValue = '<select id="habit_div" name="habit_value" class="selectpicker form-control onchange-set-neutral taskhabit_div_class" required=""><option value="">-- Select --</option>';
    $.each(habitData, function(key, value) {
        if (val != null && val == value.id) taskoptionValue += '<option value="' + value.id + '" selected>' + value.gb_habit_name + '</option>';
        else taskoptionValue += '<option value="' + value.id + '">' + value.gb_habit_name + '</option>';
    });
    taskoptionValue += '</select>';
    $('.task-habit-dropdown').html($(taskoptionValue));
    initSelectpicker($('.task-habit-dropdown select'));
    $('.taskhabit_div_class').selectpicker('refresh');
    taskoptionValue = "";
}

function showHabitList(habitData) {
    var listValue = "";
    $('#client-datatable tbody tr').remove();
    var count = 0;
    $.each(habitData, function(key, value) {
        console.log(value.gb_habit_recurrence_month);
        if (value.milestones != null) var milname = value.milestones[0].gb_milestones_name;
        else var milname = '';
        if (value.gb_habit_recurrence_type == 'weekly') {
            habitRecurrence = 'Every ' + value.gb_habit_recurrence_week;
        } else if (value.gb_habit_recurrence_type == 'monthly') {
            habitRecurrence = 'Day ' + value.gb_habit_recurrence_month + ' of every month';
        } else {
            habitRecurrence = value.gb_habit_recurrence_type;
        }
        listValue += '<tr><td class="center mw-70 w70">' + value.gb_habit_name + '</td><td>' + habitRecurrence + '</td><td class="hidden-xxs">' + milname + '</td><td class="hidden-xs">' + value.gb_habit_seen + '</td><td class="center"><a class="btn btn-xs btn-default tooltips habit-edit"  data-placement="top"data-original-title="Edit" data-habit-id = "' + value.id + '"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-habit" data-placement="top"data-original-title="Delete" data-entity="habit" data-habit-id = "' + value.id + '"><i class="fa fa-times" style="color:#ff4401;"></i></a></td></tr>';
        count = count+1;
    });
    $('#client-datatable tbody').append(listValue);
    if(count == 0)
    {
        $('#goalHabitNextButton').addClass('disabled');
    }
}

function showTaskList(taskData) {
    $('#client-datatable-task tbody tr').remove();
    var taskListValue = "";
    $.each(taskData, function(key, value) {
        if (value.taskhabit != null) var habitname = value.taskhabit.gb_habit_name;
        else var habitname = '';
        $('.task-name').text('Your task ' + value.gb_task_name + ' has been saved.');
        taskListValue += '<tr><td class="center mw-70 w70">' + value.gb_task_name + '</td><td>' + value.gb_task_priority + '</td><td class="hidden-xxs">' + habitname + '</td><td class="hidden-xs">' + value.gb_task_seen + '<br></td><td class="center"><a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "' + value.id + '"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-task" data-placement="top" data-original-title="Delete" data-task-id = "' + value.id + '"><i class="fa fa-times" style="color:#ff4401;"></i></a></td>';
    });
    $('#client-datatable-task tbody').append(taskListValue);
}

function updateStatus(id, value) {
    $.ajax({
        url: 'updategoal',
        type: 'POST',
        data: {
            'goal_id': id,
            'status': value
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success == 'true') {}
        }
    });
}

function saveHabitMetaData(hid, hType, checkboxValue) {
    $.ajax({
        url: 'insert-metadata',
        type: 'POST',
        data: {
            'habit_id': hid,
            'h_type': hType,
            'h_value': checkboxValue
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.success == 'true') {}
        }
    });
}

function deleteGoal(id) {
    $.ajax({
        url: 'deletegoal',
        type: 'POST',
        dataType: 'json',
        data: {
            'eventId': id
        },
        success: function(response) {
            //var data = JSON.parse(response);
            if (response.status == 'true') {
                location.reload();
            }
        }
    });
}

function retnum(str) {
    var num = str.replace(/[^0-9]/g, '');
    return num;
}

function getDaysInMonth() {
    var currentYear = (new Date).getFullYear();
    var currentMonth = (new Date).getMonth() + 1;
    return new Date(currentYear, currentMonth, 0).getDate();
}

function updateMilestones(id, value) { //alert('s');
    $.ajax({
        url: public_url + 'goal-buddy/updatemilestones',
        type: 'POST',
        data: {
            'milestonesId': id,
            'status': value
        },
        success: function(response) {
            //var data = JSON.parse(response); 
        }
    });
}

function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function goalConfirmDelete(eventId, entity, eventUrl, actionType) {
    var entity = entity;
    swal({
        title: "Are you sure to delete this " + entity + "?",
        text: (typeof warningText != 'undefined' && warningText) ? warningText : '',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
    }, function() {
        $(document).on('click', '.confirm', function(e) {
            $.ajax({
                url: public_url + eventUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    'eventId': eventId
                },
                success: function(response) {
                    console.log('response', response);
                    if (response.status == 'true') {
                        $('.delete-' + entity + '-' + eventId).remove();
                        if (actionType == 'calender') {
                            $('.delete-' + entity + '-' + eventId).remove();
                         } else if (actionType == 'popup') {
                            location.reload();
                            // $('#'+eventId).remove();
                         }
                         
                    }
                }
            });
        });
    });
}

function setTaskHabitData(dayValue) {
    console.log('1');
    var formdata = {};
    var taskHabitData = $('#viewport-4').find('input[name="associatedHabitWithTask"]').val();
    if (dayValue != null && dayValue != undefined && dayValue != '' && taskHabitData != '') {
        taskHabitData = JSON.parse(taskHabitData);
        formdata['habit_id'] = taskHabitData.id;
        formdata['habit_name'] = taskHabitData.gb_habit_name;
        formdata['habit_recurrence'] = taskHabitData.gb_habit_recurrence_type;
        if (taskHabitData.gb_habit_recurrence_type == 'weekly' && taskHabitData.gb_habit_recurrence_week != '') {
            var selectedWeeks = taskHabitData.gb_habit_recurrence_week.split(',');
            if ($.inArray(dayValue, selectedWeeks) == -1) {
                selectedWeeks.push(dayValue);
            }
            formdata['habit_weeks'] = selectedWeeks;
        } else if (taskHabitData.gb_habit_recurrence_type == 'monthly') {
            formdata['month'] = taskHabitData.gb_habit_recurrence_month;
        }
        formdata['habit_notes'] = taskHabitData.gb_habit_notes;
        if (taskHabitData.gb_milestones_id != '') formdata['habit_milestone'] = taskHabitData.gb_milestones_id.split(',');
        else formdata['habit_milestone'] = '';
        formdata['habit_seen'] = taskHabitData.gb_habit_seen;
        formdata['habit_reminders'] = taskHabitData.gb_habit_reminder;
        formdata['last_insert_id'] = taskHabitData.goal_id;
        formdata['form_no'] = 3;
        // savegoal(formdata);
        $.ajax({
            url: public_url + 'goal-buddy/savegoal',
            type: 'POST',
            data: {
                'formData': formdata
            },
            success: function(data) {
               
                var data = JSON.parse(data);
               console.log(data);
                if (data.form == 'habit-list') {
                    isJump = "true";
                    isJumpTask = "false";
                    var listValue = "";
                    var habitLabel = "";
                    var habitRecurrence = "";
                    $('#client-datatable tbody tr').remove();
                    $.each(data.listData, function(key, value) {
                        if (value.mile_stone_name != null) var milname = value.mile_stone_name;
                        else var milname = '';
                        $('.habit-name').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your Habit ' + value.gb_habit_name + ' has been saved.</div>');
                        if (value.gb_habit_recurrence_type == 'weekly') {
                            habitRecurrence = 'Every ' + value.gb_habit_recurrence_week;
                        } else if (value.gb_habit_recurrence_type == 'monthly') {
                            habitRecurrence = 'Day ' + value.gb_habit_recurrence_month + ' of every month';
                        } else {
                            habitRecurrence = value.gb_habit_recurrence_type;
                        }
                        listValue += '<tr><td >' + value.gb_habit_name + '</td><td>' + habitRecurrence + '</td><td >' + milname + '</td><td>' + value.gb_habit_seen + '</td><td class="center"><a class="btn btn-xs btn-default tooltips habit-edit"  data-placement="top"data-original-title="Edit" data-habit-id = "' + value.id + '"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-habit" data-placement="top"data-original-title="Delete" data-entity="habit" data-habit-id = "' + value.id + '"><i class="fa fa-times" style="color:#ff4401;"></i></a></td></tr>';
                        habitLabel += '<a class="Step-your-goal3 habit-text" data =' + value.id + '><li>' + value.gb_habit_name + '</li></a>';
                        habitLabel += '<p>' + value.gb_habit_seen + '</p>';
                    });
                    $('.habit-label a').remove();
                    $('.habit-label p').remove();
                    $('.habit-label').append(habitLabel);
                    $('#client-datatable tbody').append(listValue);
                }
            }
        });
    }
}

$(document).ready(function() {
    $('body').on('click', '.goal-predifine-template', function(){
        var bg_url=$('#bgUrl').css('background-image');
        var goalTempleteId = $(this).data('goal-template-id');

        setGoalTemplateDetails(goalTempleteId);
        $('#goal-template').val(goalTempleteId);
        $('#rightimage').show();
        $('#rightimage').css('background-image', bg_url)

    });

    // Add for notes in custom goal 
    $("#create-new-goal").click(function(){
        var bg_url=$('#bgUrl').css('background-image');
        $('#rightimage').show();
        $('#rightimage').css('background-image', bg_url)
    });
    // end
});

function setGoalTemplateDetails(goalTempleteId) {
    var goal_id = $('#goal_milestones_id').val();
   
    $.ajax({
            url: public_url + 'goal-buddy/template/'+goalTempleteId,
            type: 'GET',
            async: false,
            data: {'goal_id': goal_id},
            success: function(data) {
                var data = JSON.parse(data);
                console.log('dt---', data);
                if(data.status == true) {
                    /* tooltip */
                    var templet_id = data.goal_template.id;

                    if(templet_id == 1 || templet_id == 4){
                        switch (templet_id) {
                            case 1:
                                 var image_path = BASE_URL + '/assets/images/next-weightmanagement.png';
                                 $(".slide-img").attr("src",image_path); 
                               break;
                            case 4:
                                var image_path = BASE_URL + '/assets/images/next-improve_h.png';
                                $(".slide-img").attr("src",image_path); 
                              break;
                          }
                     }
                    
                    if(templet_id != 1 && templet_id != 12){
                        let text_tooltip;
                        switch (templet_id) {
                            case 2:
                                text_tooltip = "1. Measurements cm or inches, dropping to a specific weight or loss of a specific weight in increments <br/>\
                                                2. Body Fat Percentages, dropping to a specific BFP or loss of a specific BFP in increments <br/>\
                                                3. Clothing sizes in inches or dress size, dropping to a specific size or loss of a specific size in increments";
                               break;
                            case 3:
                                text_tooltip = "1. Meal Prep for 1 day per week, then 2, then 3 etc.<br/>\
                                                2. 1 Clean eating day per week, then 2 then 3 etc.<br/>\
                                                3. Fast for 3 hours per day, build up to 8-hour window ";
                              break;
                            case 4:
                                text_tooltip = "1. Increase daily intake by 100ml each week ";
                              break;
                            case 5:
                                text_tooltip = "1. Blood pressure decreasing in increments of ….<br/>\
                                        2. Turning off work phone 1 x per week, then 2, 3 etc.";
                              break;
                            case 6:
                                text_tooltip = "1. Increasing deep sleep in 20 min increments <br/>\
                                      2. Falling asleep 5 Minutes faster each week  <br/>\
                                      3. Waking up before alarm once a week, then 2, then 3 etc.";
                              break;
                            case 7:
                                text_tooltip = "1. Blood pressure levels <br/>\
                                   2. Cholesterol levels <br/>\
                                   3. Cycle/ Run Distance and comfortable breathing pace increase ";
                              break;
                            case 8:
                                text_tooltip = "1. Self-assess discomfort during movement—drop 1 level every 2 weeks <br/>\
                                   2. Increased range of motion through reach measurements <br/>\
                                   3. Walking 10m further each week ";
                                break;
                            case 9:
                                text_tooltip = "1. Increase activity total to 150 mins, 160 mins, 170 mins <br/>\
                                    2. Increase Walking/Running distance by 1km each month—build to 10km";
                                break;
                            case 10:
                                text_tooltip = "1. Improving financial situation—increase of x amount,<br/>\
                                    2. Allocating 10mins more alone time each week ";
                                break;
                            case 11:
                                text_tooltip = "1. Participate in a certain number of events e.g., Marathon event <br/>\
                                2. Gain a certain number of friends in a positive environment (the gym) <br/>\
                                3. Increase number of weeks with no alcohol or partying";
                                break;
                            case 13:
                                text_tooltip = "1. Allocate specific hours <br/>\
                                       2. Allocate specific time for tasks";
                                break;
                            case 14:
                                text_tooltip = "1. Measurements cm or inches<br/>\
                                2. Body fat percentages<br/>\
                                3. Timed event run swim bike <br/>\
                                4. 1RM";
                                break;
                            case 15:
                                text_tooltip = "1. Earn a specific amount<br/>\
                                2. Earn an additional amount <br/>\
                                3. Work way through the ranks";
                                break;
                            case 16:
                                text_tooltip = "1. Improved efficiency in percentage<br/>\
                                2. Work additional hours<br/>\
                                3. Allocate more tasks career<br/>\
                                4. Allocate more tasks personal";
                               break;
                          }

                         $('.tooltip-diff').attr('data-message1',text_tooltip);
                     }
               

                    /* end tooltip */
                    var fetch_data = data.fetch_data;
                    var goalTemplate = data.goal_template;
                    var milestoneDataHtml = '';
                    var habitlisthtml='';
                    var html1='',html2='',html3='',html4='',html5 = '', html6 = '';
                    var goal_name = goalTemplate.gb_goal_name.split(',');
                    $(".append-template-goal-name").html('');
                    var radio_val = '';
                    var goal_other = null;

                    for(var i = 0; i < goal_name.length; i++) {   
                        if(fetch_data != null){
                               radio_val = (fetch_data.gb_goal_name == goal_name[i])? 'checked' : '';
                               goal_other = fetch_data.gb_goal_name_other;
                         }
                      
                        if(goal_name[i] == 'Other'){
                            var other1 = 'name_goal_other';
                            if(goal_other != null){
                                html1 = '<textarea rows="7" class="form-control" id="name_goal_other" name="name_goal_other">'+goal_other+'</textarea>';  
                            } else {
                                html1 = '<textarea rows="7" class="form-control hidden" id="name_goal_other" name="name_goal_other" placeholder="Input Your Specific Goal Here..."></textarea>';
                            }
                        }
                        
                        $(".append-template-goal-name").append(
                            '<div class="form-group">\
                                <label class="container_radio version_2">'+goal_name[i]+'\
                                <input type="radio" class="'+other1+'" name="name_goal" required value="'+goal_name[i]+'" '+radio_val+'>\
                                <span class="checkmark"></span>\
                                '+html1+'\
                                </label>\
                            </div>'
                        )
                    }

                    var describe_achieve = goalTemplate.gb_achieve_description.split(',');
                    $(".template-description-achieve").html('');
                    var radio_val = '';
                    var achieve_other = null;
                    for(var i = 0; i < describe_achieve.length; i++) {
                        if(fetch_data != null){
                             radio_val = (fetch_data.gb_achieve_description == describe_achieve[i])? 'checked' : '';
                             achieve_other = fetch_data.gb_achieve_description_other;
                         }

                        if(describe_achieve[i] == 'Other'){
                            var other2 = 'describe_achieve_other';
                            if(achieve_other != null){
                                html2 = '<textarea rows="7" class="form-control" id="describe_achieve_other" name="describe_achieve_other">'+ achieve_other +'</textarea>';
                            } else {
                                html2 = '<textarea rows="7" class="form-control hidden" id="describe_achieve_other" name="describe_achieve_other"></textarea>';
                            }
                        }
                        $(".template-description-achieve").append(
                            '<div class="form-group">\
                                <label class="container_radio version_2">'+describe_achieve[i]+'\
                                <input type="radio" class="'+other2+'" name="describe_achieve" required value="'+describe_achieve[i]+'" '+radio_val+'>\
                                <span class="checkmark"></span>\
                                '+html2+'\
                                </label>\
                            </div>'
                        )
                    }

                    var accomplish = goalTemplate.gb_important_accomplish.split(',');
                    $(".template-accomplish").html('');
                    var radio_val = '';
                    var accomplish_other = null;
                    if(fetch_data != null ){
                        var accomplish_check_array = fetch_data.gb_important_accomplish.split(',');
                    }
                   
                    for(var i = 0; i < accomplish.length; i++) {
                        if(fetch_data != null){
                            for(var j = 0; j < accomplish_check_array.length; j++) {
                                radio_val = (accomplish_check_array[j] == accomplish[i]) ? 'checked' : '';
                                if(radio_val == 'checked'){
                                     break;
                                 }
                              }
                             accomplish_other = fetch_data.gb_important_accomplish_other;
                         }
                        if(accomplish[i] == 'Other'){
                            var other3 = 'accomplish_other';
                            if(accomplish_other != null){
                                html3 = '<textarea rows="7" class="form-control" id="accomplish_other" name="accomplish_other">'+ accomplish_other +'</textarea>';
                            } else {
                                html3 = '<textarea rows="7" class="form-control hidden" id="accomplish_other" name="accomplish_other"></textarea>';
                            }
                        }
                        $(".template-accomplish").append(
                            '<div class="form-group">\
                            <label class="container_check version_2">'+accomplish[i]+'\
                            <input type="checkbox" class="'+other3+'" name="accomplish" required value="'+accomplish[i]+'" '+radio_val+'>\
                            <span class="checkmark"></span>\
                            '+html3+'\
                            </label>\
                         </div>'
                        )
                    }
                    /*  */
                    var fail_description = goalTemplate.gb_fail_description.split(',');
                    $(".fail_description").html('');
                    var radio_val = '';
                     var fail_description_other = null;
                    if(fetch_data != null ){
                        var fail_check_array = fetch_data.gb_fail_description.split(',');
                    }
                    // console.log('fail_check_array=======',fail_check_array, fail_description);
                   
                    for(var i = 0; i < fail_description.length; i++) {
                        if(fetch_data != null){
                            for(var j = 0; j < fail_check_array.length; j++) {
                                radio_val = (fail_check_array[j] == fail_description[i]) ? 'checked' : '';
                                if(radio_val == 'checked'){
                                     break;
                                 }
                              }
                              fail_description_other = fetch_data.gb_fail_description_other;
                         }
                        if(fail_description[i] == 'Other'){
                            var other6 = 'fail_description_other';
                            if(fail_description_other != null){
                                html6 = '<textarea rows="7" class="form-control" id="fail_description_other" name="fail_description_other">'+ fail_description_other +'</textarea>';
                            } else {
                                html6 = '<textarea rows="7" class="form-control hidden" id="fail_description_other" name="fail_description_other"></textarea>';
                            }
                        }
                        if(fail_description[i].includes('_')){
                            fail_description[i]= fail_description[i].replace('_', ', ')
                        }
                        // var fail_description = fail_description[i].replace('-', ',')
                        $(".fail_description").append(
                            '<div class="form-group">\
                            <label class="container_check version_2">'+fail_description[i]+'\
                            <input type="checkbox"  class="'+other6+'" name="fail-description" required value="'+fail_description[i]+'" '+radio_val+'>\
                            <span class="checkmark"></span>\
                            '+html6+'\
                            </label>\
                         </div>'
                        )
                    }


                    /*  */

                    var gb_relevant_goal = goalTemplate.gb_relevant_goal.split(',');
                    $(".template-relevant-goal").html('');
                    if(fetch_data != null ){
                        var relevant_check_array = fetch_data.gb_relevant_goal.split(',');
                    }
                   
                    var relevant_other = null;
                    var radio_val = '';
                    for(var i = 0; i < gb_relevant_goal.length; i++) {
                        if(fetch_data != null){
                            for(var j = 0; j < relevant_check_array.length; j++) {
                              radio_val = (relevant_check_array[j] == gb_relevant_goal[i]) ? 'checked' : '';
                              if(radio_val == 'checked'){
                                   break;
                               }
                            }
                              relevant_other = fetch_data.gb_relevant_goal_other;                     
                          }
                        if(gb_relevant_goal[i] == 'Other'){
                            var other4 = 'gb_relevant_goal_other';
                            if(relevant_other != null){
                                html4 = '<textarea rows="7" class="form-control" id="gb_relevant_goal_other" name="gb_relevant_goal_other">'+ relevant_other +'</textarea>';
                            } else {
                                html4 = '<textarea rows="7" class="form-control hidden" id="gb_relevant_goal_other" name="gb_relevant_goal_other"></textarea>';
                            }
                        }
                        $(".template-relevant-goal").append(
                            '<div class="form-group">\
                            <label class="container_check version_2">'+gb_relevant_goal[i]+'\
                            <input type="checkbox" class="'+other4+'" name="gb_relevant_goal" required value="'+gb_relevant_goal[i]+'" '+radio_val+'>\
                            <span class="checkmark"></span>\
                            '+html4+'\
                            </label>\
                         </div>'
                        )
                    }

                    var gb_relevant_goal_event = goalTemplate.gb_relevant_goal_event.split(',');
                    $(".template-relevant-goal-event").html('');
                    var radio_val = '';
                    var relevant_event_other = null;
                    for(var i = 0; i < gb_relevant_goal_event.length; i++) {
                        if(fetch_data != null){
                             radio_val = (fetch_data.gb_relevant_goal_event == gb_relevant_goal_event[i])? 'checked' : '';
                             relevant_event_other = fetch_data.gb_relevant_goal_event_other;
                         }
                        if(gb_relevant_goal_event[i] == 'Other'){
                            var other5 = 'gb_relevant_goal_event_other';
                            if(relevant_event_other != null){
                                html5 = '<textarea rows="7" class="form-control" id="gb_relevant_goal_event_other" name="gb_relevant_goal_event_other">'+ relevant_event_other +'</textarea>';
                            } else {
                                html5 = '<textarea rows="7" class="form-control hidden" id="gb_relevant_goal_event_other" name="gb_relevant_goal_event_other"></textarea>';
                            }
                        }
                        $(".template-relevant-goal-event").append(
                            '<div class="form-group">\
                                <label class="container_radio version_2">'+gb_relevant_goal_event[i]+'\
                                <input type="radio" class="'+other5+'" name="gb_relevant_goal_event" required value="'+gb_relevant_goal_event[i]+'" '+radio_val+'>\
                                <span class="checkmark"></span>\
                                '+html5+'\
                                </label>\
                            </div>'
                        )
                    }

                    
                    /* 06-07-2021 */
                    // if(fetch_data != null){
                    //     alert('hiii');
                    //     var goal_name = fetch_data.gb_goal_name; 
                    //     $("input[name=name_goal][value=" + goal_name + "]").attr('checked', true);
                    // }
                  
                    /* end 06-07-2021 */
                    // $('#name_goal').text(goalTemplate.gb_goal_name);
                    // $('#name_goal').attr("placeholder", goalTemplate.gb_goal_name);
                    // $('#name_goal').trigger('input');
                    // $('#description').text(goalTemplate.gb_achieve_description);
                    // $('#description').trigger('input');
                    // $('#accomplish').text(goalTemplate.gb_important_accomplish);
                    // $('#accomplish').trigger('input');
                    $('#fail-description').text(goalTemplate.gb_fail_description);
                    $('#btn_fail-description').removeClass('hidden');
                    // $('#relevant_goal').text(goalTemplate.gb_relevant_goal);
                    // $('#relevant_goal').trigger('input');
                    // $('#gb_relevant_goal_event').text(goalTemplate.gb_relevant_goal_event);
                    // $('#gb_relevant_goal_event').attr("placeholder", goalTemplate.gb_relevant_goal_event);
                    // $('#gb_relevant_goal_event').trigger('input');
                    // if(goalTemplate.gb_change_life_reason != null && goalTemplate.gb_change_life_reason != '') {
                        if(fetch_data != null){
                            if(fetch_data.gb_change_life_reason != null && fetch_data.gb_change_life_reason != '') {
                                var selected_change_life = fetch_data.gb_change_life_reason.split(',');
                                // var checkboxIndex = 0;
                                // var options = getCheckBoxOptions(checkboxIndex);
                                for(var i = 0; i < selected_change_life.length; i++) {
                                    var item = selected_change_life[i];
                                    $('.life_change_reason').each(function(){
                                     var lifeChange = $(this).val();
                                        if(lifeChange == item){
                                        $(this).prop("checked", true);
                                        }
                                    })
                                }
                           }
                  
                        
                        if($('.life_change_reason').is(":checked")){
                            $("#gb_change_life_reason_other").show();
                        }
                    }
                }
            }
        });
}

// Validate Goal habit form
function validateGoalHabit(){
    var currentLocation = window.location.href;
    var habitButton = currentLocation.includes('/goal-buddy/edit') ? $('#create_habbit_btn') :  $('#goalHabitNextButton');
    var selectedDays = [];
    var monthCount;
    habitButton.attr('disabled', true);

    var habitNotes = [];
    $('input[name="SYG_notes"]:checked').each(function(){
        habitNotes.push($(this).val());
    });

    var habitName = $('input[name="SYG_habits"]').val(),
    habitRecurrenceType = $('input[name="SYG_habit_recurrence"]').val(),
    milestoneValue = $('select[name="milestone_value"]').val(),

    taskSeen = $('input[name="syg2_see_habit"]').val(),
    sendMsg = $('input[name="syg2_send_msg"]').val(),
    // habitNotes = $('#SYG_notes').val(),
    
    isHabitValid = true;

    if(habitRecurrenceType == 'weekly') {
        
        var checkBoxLi = $('#viewport-3').find('.SYG_habit_recurrence_wrapper .show-weeks');
                                     
        checkBoxLi.find('.prefTrainSlot').each(function(index, value) {
              if($(this).hasClass('active'))
                selectedDays.push($(this).data('day'));                
        });

    } else if(habitRecurrenceType == 'monthly') {
        monthCount = $('#viewport-3').find('.SYG_habit_recurrence_wrapper .month-count').find('.month-date').val();
    }

    if(typeof habitName == undefined || habitName == '')
        isHabitValid = false;
    else
        isHabitValid = true;
    if(typeof habitRecurrenceType == undefined || habitRecurrenceType == '' || (habitRecurrenceType == 'weekly' && selectedDays.length == 0) || (habitRecurrenceType == 'monthly' && (typeof monthCount == undefined || monthCount == ''))) 
        isHabitValid = false;
     else
        isHabitValid = true;
    if(typeof milestoneValue == undefined || milestoneValue == '')
        isHabitValid = false;
     else
        isHabitValid = true;
    if(typeof taskSeen == undefined || taskSeen == '')
        isHabitValid = false;
     else
        isHabitValid = true;
    if(typeof sendMsg == undefined || sendMsg == '')
        isHabitValid = false;
     else
        isHabitValid = true;
    // if(typeof habitNotes == undefined || habitNotes == '')
    if( habitNotes.length == 0 )
        isHabitValid = false;
     else
        isHabitValid = true;
    if(isHabitValid)
        habitButton.attr('disabled', false);
    else
        habitButton.attr('disabled', true);
   
}


// Validate goal task form
function validateGoalTask(element){
    /***** Set Value *******/
    var dataVal = $(element).data('value');
    var parentDiv = $(element).parents('.vp-item');
    if(dataVal != undefined && dataVal != ''){
        parentDiv.find('input[type="hidden"]').val(dataVal);
    }
    /***** Set Value *******/
    var currentLocation = window.location.href;
    var habitButton = currentLocation.includes('/goal-buddy/edit') ? $('#manage_task_btn') :  $('#goalTaskNextButton');

    habitButton.attr('disabled', true);
    var taskName = $('input[name="SYG3_task"]').val(),
    taskRecurrenceType = $('input[name="SYG_task_recurrence"]').val(),
    habitValue = $('select[name="habit_value"]').val(),
    task_priority = $('input[name="SYG3_priority"]').val(),
    taskSeen = $('input[name="SYG3_see_task"]').val(),
    sendMsg = $('input[name="SYG3_send_msg"]').val(),
    taskNotes = $('input[name=note]').val(),
    isHabitValid = true;
    if(taskRecurrenceType == 'weekly') {
        var selectedDays = [];
        // var checkBoxLi = $('#viewport-4').find('.SYG_habit_recurrence_wrapper .show-weeks');                      
        $('#viewport-4').find('.prefTrainSlot').each(function(index, value) {
              if($(this).hasClass('active'))
                selectedDays.push($(this).data('day'));                
        });

        isHabitValid = selectedDays.length == 0 ? false : true;
    } else if(taskRecurrenceType == 'monthly') {
        var monthCount = $('#viewport-4').find('.SYG_habit_recurrence_wrapper .month-count').find('.month-date').val();
    }

    if(typeof taskName == undefined || taskName == ''){
        isHabitValid = false;
    }
    if(typeof taskRecurrenceType == undefined || taskRecurrenceType == '' || (taskRecurrenceType == 'weekly' && selectedDays.length == 0) || (taskRecurrenceType == 'monthly' && (typeof monthCount == undefined || monthCount == ''))){
        isHabitValid = false;
    }
    if(typeof habitValue == undefined || habitValue == ''){
        isHabitValid = false;
    }
    if(typeof taskSeen == undefined || taskSeen == ''){
        isHabitValid = false;
    }
    if(typeof sendMsg == undefined || sendMsg == ''){
        isHabitValid = false;
    }
    if(typeof taskNotes == undefined || taskNotes == ''){
        isHabitValid = false;
    }
    if(typeof task_priority == undefined || task_priority == ''){
        isHabitValid = false;
    }
    if(isHabitValid)
        habitButton.attr('disabled', false);
    else
        habitButton.attr('disabled', true);
   
}
/////Goal Validation//////////////
function checkValue()
{
    //alert();
     //var btn_val=$('#create-new-goal').val();
     
     var goal_name=$('#name_goal').val();
     // var describe_achiv=$('#describe_achieve').val();
     var accomplish=$('#accomplish').val();
     var fail=$('#fail-description').val();
     var relevant=$('#gb_relevant_goal').val();
     var relevant_event=$('#gb_relevant_goal_event').val();
     var isgoal_itemfill=true;
     if(goal_name=='' || goal_name==undefined){
        $('#goalNextButton').css('display', 'none');
       
     }else if(describe_achiv=='' || describe_achiv==undefined){
        $('#goalNextButton').css('display', 'none');
        
     }else if(accomplish=='' || accomplish==undefined){
        $('#goalNextButton').css('display', 'none');
        
     }else if(fail=='' || fail==undefined){
       $('#goalNextButton').css('display', 'none');
        
     }else if(relevant=='' || relevant==undefined){
        $('#goalNextButton').css('display', 'none');
        
     }else if(relevant_event=='' || relevant_event==undefined){
       $('#goalNextButton').css('display', 'none');
       
     }else{
        $('#goalNextButton').css('display', 'block');
     }
        
    

}

/*********** Custom JS *************/

$('document').ready(function(){
    var failDesc = $('#fail-description').val();
    if(failDesc == '' || failDesc == undefined)
    {
        $('#btn_fail-description').addClass('hidden');
    }

    $('body').on('keyup','#fail-description',function(){
        var val = $(this).val();
        if(val == '' || val == undefined)
        {
            $('#btn_fail-description').addClass('hidden');
        }
        else
        {
            $('#btn_fail-description').removeClass('hidden');
        }
    });
});

function updateGoalStatus(goalId,goalStatus,trElement)
{
    $.ajax({
        url: public_url + 'goal-buddy/updategoalstatus',
        type: 'POST',
        data: {
            'goal_id': goalId,
            'status': goalStatus
        },
        async:false,
        success: function(response) {
            var response = JSON.parse(response);
            if(response.status)
            {
                if(goalStatus)
                {
                    trElement.addClass('completed');
                }
                else
                {
                    trElement.removeClass('completed');
                }    
            }
        }
    });
}
/*********** Custom JS *************/


$('body').on('click','.res-btn-next',function(e){

     var describe_achiv=$('#description').val();
if(describe_achiv=='' || describe_achiv==undefined){

    $('.checkValue').show();

    }

});

$('body').on('input','#description',function(e){
 var describe_achiv=$('#description').val();
    if(describe_achiv != ''){

    $('.checkValue').hide();


        }
    });

$('body').on('click','.res-btn-next',function(e){

  var accomplish=$('#accomplish').val();
  if(accomplish=='' || accomplish==undefined){

    $('.imp_accomplish').show();

    }

});

$('body').on('input','#accomplish',function(e){
 var accomplish=$('#accomplish').val();
    if(accomplish != ''){

    $('.imp_accomplish').hide();


        }
    });


$('body').on('click','.res-btn-next',function(e){

     var relevant=$('#relevant_goal').val();
  if(relevant=='' || relevant==undefined){

    $('.goal_relevant').show();

    }

});

$('body').on('input','#relevant_goal',function(e){
 var relevant=$('#relevant_goal').val();
    if(relevant != ''){

    $('.goal_relevant').hide();


        }
    });

$('body').on('click','.res-btn-next',function(e){

     var relevant_event=$('#gb_relevant_goal_event').val();
  if(relevant_event=='' || relevant_event==undefined){

    $('.gb_relevant_event').show();

    }

});

$('body').on('input','#gb_relevant_goal_event',function(e){
     var relevant_event=$('#gb_relevant_goal_event').val();
    if(relevant_event != ''){

    $('.gb_relevant_event').hide();


        }
    });


$('body').on('click','.res-btn-next',function(e){

     var fail=$('#fail-description').val();
  if(fail=='' || fail==undefined){

    $('.description_details').show();

    }

});

$('body').on('input','#fail-description',function(e){
     var fail=$('#fail-description').val();
    if(fail != ''){

    $('.description_details').hide();


        }
    });


$('body').on('click','.res-btn-next',function(e){

     var fail=$('#ecRelation').val();
  if(fail=='' || fail==undefined){

    $('.ecRelation').show();

    }

});
$('body').on('input','#ecRelation',function(e){
     var fail=$('#ecRelation').val();
    if(fail != ''){

    $('.ecRelation').hide();


        }
    });
    // $(document).ready(function() {
        $(document).on('click','input[name="template"]',function(){
            // var goalTempleteId = $('input[name="template"]:checked').data('id');
            var goalTempleteId = $(this).data('id');
            var datafrom = $(this).data('from');
            if(datafrom == 'popup'){
                $(".choose-immediate-priority").attr('disabled',false);
                $('.same-template').prop('disabled', true);
                // $(".same-template").attr('disabled',true);
            }
            setGoalTemplateDetails(goalTempleteId);
        });
    // });
    /* 06-7-2021 */
    $(document).ready(function() {
        var goalTempleteId = $('input[name="template"]:checked').data('id');
         setGoalTemplateDetails(goalTempleteId);
 
    });

 
    /*  */


    $('body').on('click','.new_Btn_milestone',function(){
   
        var milestones_id = $(this).attr('data-new-milestones-id');
        console.log('milestones_id', milestones_id);
        var milestoneValue = $('#Milestones').val();
        var date = $('#milestones-date-pickup').val();
        if(milestoneValue == '' || date == ''){      
            return false;
        }
        console.log(milestoneValue, date);
        console.log(date);
        $('.mile_section ul').append('<li class="dd-item row" data-milestones-id="'+milestones_id+'" style="line-height: 20px; !important"><div class="milestones-form"><div class=" col-md-5 col-xs-5 milestones-date-cls"><input type="text" name ="milestones" class = "form-control milestones-name edit-milestones-name" value="' + milestoneValue + '" data-milestones-id="'+milestones_id+'" disabled></div><div class="col-md-5 col-xs-5 milestones-date-cls" ><input type="text" class="form-control milestones-date edit-milestones-date datepicker_SYG4" autocomplete="off" name ="milestones-date" value="'+ date +'" disabled required></div><div class="col-md-2 col-xs-2 p-0 pencil_find_sibling"><a><i class="fa fa-times new-delete-milestone-info"  style="margin-right: 5px" data-milestones-id=" " ></i></a><a><i class="fa fa-save hidden"  data-milestones-id=" "></i></a></div></li>');
        var objget = $('li.dd-item:last').prev();
        var dueDate = $('#goalDueDate').val();

        $('#Milestones').val('');
        $('#milestones-date-pickup').val('');
        // updateMilestones(id, value)
        var start = new Date(),
            end   = new Date(dueDate),
            diff  = new Date(end - start),
            days  = diff/1000/60/60/24;
            days = Math.round(days);
            if(objget.length == 0)
            {
                $('.datepicker_SYG4 ').datepicker({
                    // todayHighlight: 'TRUE',
                    // startDate: '-0d',
                    // autoclose: true,
                    // minDate: moment(),
                    // format: 'D, d M yyyy'
                    todayHighlight: 'TRUE',
                startDate: '-0d',
                autoclose: true,
                minDate: moment(),
                format: 'D, d M yyyy',
                endDate: '+'+days+'d',
                beforeShowDay: function(date) {
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var Year = date.getFullYear();
                    eventDate = Year+'-'+new_str_pad(month)+'-'+new_str_pad(day);
                    if (dueDate == eventDate) {
                        return {classes: 'highlight', tooltip: 'Title'};
                    }
                }
                });
            }
            var prevMileDate = new Date(objget.find('.milestones-date').val());
            var m_date = prevMileDate != null && prevMileDate != undefined ? new moment(prevMileDate).add(1, 'days') : new moment(prevMileDate);
            var objset = $('li.dd-item:last');
            $('.datepicker_SYG4').datepicker({
                todayHighlight: 'TRUE',
                startDate: m_date.toDate(),
                autoclose: true,
                format: "D, d M yyyy",
                endDate: '+'+days+'d',
                beforeShowDay: function(date) {
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var Year = date.getFullYear();
                    eventDate = Year+'-'+new_str_pad(month)+'-'+new_str_pad(day);
                    if (dueDate == eventDate) {
                        return {classes: 'highlight', tooltip: 'Title'};
                    }
                }
            });
             showMIlestoneDd();
            //disable past date for first input datepicker
            // var last_date = $('li.dd-item:last').find('.edit-milestones-date').val();
            var startDateMilestone = new Date(date);
            startDateMilestone.setDate(startDateMilestone.getDate() + 1);
            $('.create-milestones-date').datepicker('setStartDate', startDateMilestone);
            // $('.milestones-date').datepicker('setStartDate', startDateMilestone);
            //end
       
    });

    function new_str_pad(n) {
        return String("00" + n).slice(-2);
    }

    $('body').on('click', '.new-delete-milestone-info', function() {
        //enable past date for first input datepicker
        var li_length = $('li.dd-item').find('.edit-milestones-date').length;
        if(li_length == 1){
            var startDateMilestone = new Date();
            $('.create-milestones-date').datepicker('setStartDate', startDateMilestone);
         }
        //end enable past date for first input datepicker
        var currentRow = $(this).closest('.dd-item');
        var milestonesId = $(this).data('milestones-id');
        $(currentRow).remove();
        if (milestonesId != '') {
            $.ajax({
                url: public_url + 'goal-buddy/deletemilestones',
                type: 'POST',
                data: {
                    'eventId': milestonesId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status == 'true') {
                        createHabitDd(data.habitData);
                        showHabitList(data.habitData);
                        showTaskList(data.taskData);
                        if($('.milestones-date').length <= 1){
                            var startDateMilestone = new Date();
                            startDateMilestone.setDate(startDateMilestone.getDate());
                            $('.milestones-date').datepicker('setStartDate', startDateMilestone);
                        }
                    }
                }
            });
        }
    });

    $('body').on('change', '.new-milestones-date', function(e) {
        e.stopImmediatePropagation();
        var send = true,
            isSend = true;
        if (typeof(event) == 'object')
            if (event.timeStamp - lastJQueryTS < 300) {
                send = false;
                lastJQueryTS = event.timeStamp;
            }
        if (send) {
            var obj = $(this),
                ddi = obj.closest('.dd-item'),
                mValue = ddi.find('.milestones-name').val(),
                mDateValue = dateStringToDbDate(ddi.find('.milestones-date').val()),
                milestonesId = ddi.find('.save-milestone-info').data('milestones-id');
                console.log('  ddi ==', ddi);
            /*clientId = $('input[name="goalClientId"]').val();*/
            ddi.find('.milestones-name').attr('disabled', true);
            ddi.find('.milestones-date').attr('disabled', true);
            ddi.find('.save-milestone-info').hide();
            ddi.find('.edit-milestone-info').show();
            var pre_goalId = $('#last-insert-id').val();
       
            if (typeof pre_goalId == 'undefined' || pre_goalId == '') pre_goalId = $('#goal_milestones_id').val();
            clearTimeout(timeoutReference);
            timeoutReference = setTimeout(function() {
                if (isSend) {
                    isSend = false;
                    $.ajax({
                        url: public_url + 'goal-buddy/updatemilestones',
                        type: 'POST',
                        data: {
                            'milestonesId': milestonesId,
                            'mValue': mValue,
                            'mDateValue': mDateValue,
                            'goalId': pre_goalId
                        },
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.status == 'true') {
                                
                                if (data.id > 0){   
                                            
                                    ddi.find('.milestones-name').attr('data-milestones-id', data.id);
                                    ddi.attr('data-milestones-id', data.id);
                               
                               
                                    // $('.mile_section .dd-list li:last').attr('data-milestones-id', data.id);
                                    // ddi.data('milestones-id', data.id);
                                } 
                                showMIlestoneDd();
                            }
                        }
                    });
                }
            }, 500);
        }
        $('#Milestones').val('');
    });
    $("input[name=goal_seen]").change(function(){
        var goal_seen = $(this).val();
        console.log(goal_seen);
        if(goal_seen == "Selected friends"){
            $("#goal_selective_friends").val('');
            $("#goal_selective_friends").parent().removeClass('hidden');
            $('#goal_selective_friends').attr('required',true);
            $('#goal_selective_friends').removeAttr("style");
            $('#goal_selective_friends').attr('style','height: 0; width: 0; visibility: hidden; padding: 0; margin: 0; float: right');
            $("#goal_selective_friends-error").attr('style','color:red');
        }else{
            $("#goal_selective_friends").val('');
            $("#goal_selective_friends").parent().addClass('hidden');
            $('#goal_selective_friends').attr('required',false);
            $('#goal_selective_friends').removeAttr("style");
            $('#goal_selective_friends').attr('style','display:none');
            $("#goal_selective_friends-error").html('');
        }
    })

    $('body').on('change','.data-step',function(){
      var id=  $(this).data('step');
      var formdata = {};
      formdata['goal_notes'] = $('#goal_notes').val();
      if(id ==  13){
        $('.newHabitForm').data('habit-value',0);
            
            var goalDueDate = $('#datepicker_SYG').val();
            
            if($('input[name="template"]:checked').data('id') != undefined){
                formdata['name'] = $("input[name=name_goal]:checked").val();
                formdata['describe_achieve'] = $("input[name=describe_achieve]:checked").val();
                formdata['gb_relevant_goal_event'] = $("input[name=gb_relevant_goal_event]:checked").val();
                formdata['accomplish'] = [];
                $('input[name="accomplish"]:checked').each(function(){
                    formdata['accomplish'].push($(this).val());
                    if($(this).val() == 'Other'){
                        formdata['gb_important_accomplish_other'] = $('#accomplish_other').val();
                    }
                });
                formdata['gb_relevant_goal'] = [];
                $('input[name="gb_relevant_goal"]:checked').each(function(){
                    formdata['gb_relevant_goal'].push($(this).val());
                    if($(this).val() == 'Other'){
                        formdata['gb_relevant_goal_other'] = $('#gb_relevant_goal_other').val();
                    }
                });

                if($("input[name=name_goal]:checked").val() == 'Other'){
                    formdata['gb_goal_name_other'] = $('#name_goal_other').val();
                }
                if($("input[name=describe_achieve]:checked").val() == 'Other'){
                    formdata['gb_achieve_description_other'] = $('#describe_achieve_other').val();
                }
                if($("input[name=gb_relevant_goal_event]:checked").val() == 'Other'){
                    formdata['gb_relevant_goal_event_other'] = $('#gb_relevant_goal_event_other').val();
                }

                formdata['failDescription'] = [];
                $('input[name="fail-description"]:checked').each(function(){
                    formdata['failDescription'].push($(this).val());
                    if($(this).val() == 'Other'){
                        formdata['gb_fail_description_other'] = $('#fail_description_other').val();
                    }
                });
                
            }else{
                formdata['name'] = $('#name_goal').val();
                formdata['describe_achieve'] = $("#description").val();
                formdata['accomplish'] = $('#accomplish').val();
                formdata['gb_relevant_goal'] = $('#relevant_goal').val();
                formdata['gb_relevant_goal_event'] = $('#gb_relevant_goal_event').val();
                formdata['failDescription'] = $('#fail-description').val();
            }
            formdata['goal_year'] = $("input[name=goal]:checked").val();
            formdata['change_life'] = [];
            $('input[name="life-change"]:checked').each(function(){
                formdata['change_life'].push($(this).val());
            });

            // formdata['change_life'] = prepareCheckBoxSelect($('#form').find('input[name="life-change"]').val());
            // $.inArray('Other',formdata['change_life'])
            	 
           
			formdata['gb_change_life_reason_other'] = $('#gb_change_life_reason_other').val();
           
            //  formdata['failDescription'] = $('#fail-description').val();  08-07-2021

            formdata['image'] = $('input[name="prePhotoName"]').val();
            formdata['template'] = $('input[name="template"]:checked').data('id');
            if(goalDueDate!=''){
                formdata['due_date'] =  moment(goalDueDate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");
            }
            formdata['goal_seen'] = $("input[name=goal_seen]:checked").val();
            formdata['goal_selective_friends'] = $("input[name=goal_selective_friends]").val();
            formdata['send_msg_type'] = $("input[name='goal-Send-mail']:checked").val(); 
            if(formdata['send_msg_type'] == "daily"){
                formdata['Send_mail_time'] = $('#daily_time_goal option:selected').val();
            }else if(formdata['send_msg_type'] == "weekly"){
                formdata['Send_mail_time'] = $('#weekly_day_goal option:selected').val();
            }else if(formdata['send_msg_type'] == "monthly"){
                formdata['Send_mail_time'] = $('#month_date_goal option:selected').val();
            }
            formdata['gb_reminder_type_epichq'] = $("input[name='goal-Send-epichq']:checked").val();
            formdata['form_no'] = 1;
            
            var result = window.location.pathname.split('/');
            var Param = result[result.length - 3];
            var Param1 = result[result.length - 2];
            if ((Param == 'goal-buddy') && (Param1 == 'edit')) {
                $('#update-record').val('update-yes');
            }
            formdata['update_status'] = $('#update-record').val();

            formdata['last_insert_id'] = $('#last-insert-id').val();
            formdata['save_as_draft'] = false;
            console.log(formdata);
            if(formdata['update_status'] == 'update-yes') {
                formdata['last_insert_id'] = $('#last-insert-id').val();
                savegoal(formdata);
            }
            if(formdata['name'] != '' && formdata['change_life'].length > 0 && formdata['update_status'] != 'update-yes' && formdata['due_date'] != undefined ){
                savegoal(formdata);
            }
    
    }else if(id == 20){
        $('.newHabitForm').data('habit-value',id);
    }
});


$("input[name=gb_milestones_seen]").change(function(){
    var gb_milestones_seen = $(this).val();
    console.log(gb_milestones_seen);
    if(gb_milestones_seen == "Selected friends"){
        $("#gb_milestones_selective_friends").val('');
        $("#gb_milestones_selective_friends").parent().removeClass('hidden');
        $('#gb_milestones_selective_friends').attr('required',true);
        $('#gb_milestones_selective_friends').removeAttr("style");
        $('#gb_milestones_selective_friends').attr('style','height: 0; width: 0; visibility: hidden; padding: 0; margin: 0; float: right');
        $("#gb_milestones_selective_friends-error").attr('style','color:red');
    }else{
        $("#gb_milestones_selective_friends").val('');
        $("#gb_milestones_selective_friends").parent().addClass('hidden');
        $('#gb_milestones_selective_friends').attr('required',false);
        $('#gb_milestones_selective_friends').removeAttr("style");
        $('#gb_milestones_selective_friends').attr('style','display:none');
        $("#gb_milestones_selective_friends-error").html('');
    }
})
$("input[name=syg2_see_habit]").change(function(){
    var syg2_see_habit = $(this).val();
    if(syg2_see_habit == "Selected friends"){
        $("#syg2_selective_friends").val('');
        
        console.log($("#all-my-friends").val());
        if($("#all-my-friends").val() != undefined && $("#all-my-friends").val() != ''){
            $("#syg2_selective_friends").amsifySuggestags("refresh");
            var my_friends = JSON.parse($("#all-my-friends").val());
            var options = [];
                
            for(var aaa =0; aaa < my_friends.length; aaa++ ){
                options[aaa] = {'tag':my_friends[aaa].name,'value':my_friends[aaa].id}
            }
        
            $('.autocomplete').amsifySuggestags({
                type :'bootstrap',
                suggestions: options,
                whiteList:true,
            });
        }
        $("#syg2_selective_friends").parent().removeClass('hidden');
        $('#syg2_selective_friends').attr('required',true);
        $('#syg2_selective_friends').removeAttr("style");
        $('#syg2_selective_friends').attr('style','height: 0; width: 0; visibility: hidden; padding: 0; margin: 0; float: right');
        $("#syg2_selective_friends-error").attr('style','color:red');
    }else{
        $("#syg2_selective_friends").val('');
        $("#syg2_selective_friends").parent().addClass('hidden');
        $('#syg2_selective_friends').attr('required',false);
        $('#syg2_selective_friends').removeAttr("style");
        $('#syg2_selective_friends').attr('style','display:none');
        $("#syg2_selective_friends-error").html('');
    }
})
$("input[name=SYG3_see_task]").change(function(){
    var SYG3_see_task = $(this).val();
    if(SYG3_see_task == "Selected friends"){
        $("#SYG3_selective_friends").val('');
        
        if($("#all-my-friends").val() != undefined && $("#all-my-friends").val() != ''){
            $("#SYG3_selective_friends").amsifySuggestags("refresh");
            var my_friends = JSON.parse($("#all-my-friends").val());
            var options = [];
                
            for(var aaa =0; aaa < my_friends.length; aaa++ ){
                options[aaa] = {'tag':my_friends[aaa].name,'value':my_friends[aaa].id}
            }
        
            $('.autocomplete').amsifySuggestags({
                type :'bootstrap',
                suggestions: options,
                whiteList:true,
            });
        }
        $("#SYG3_selective_friends").parent().removeClass('hidden');
        $('#SYG3_selective_friends').attr('required',true);
        $('#SYG3_selective_friends').removeAttr("style");
        $('#SYG3_selective_friends').attr('style','height: 0; width: 0; visibility: hidden; padding: 0; margin: 0; float: right');
        $("#SYG3_selective_friends-error").attr('style','color:red');
    }else{
        $("#SYG3_selective_friends").val('');
        $("#SYG3_selective_friends").parent().addClass('hidden');
        $('#SYG3_selective_friends').attr('required',false);
        $('#SYG3_selective_friends').removeAttr("style");
        $('#SYG3_selective_friends').attr('style','display:none');
        $("#SYG3_selective_friends-error").html('');
    }
})
$('body').on('change', '.milestones-date', function(e) {
    console.log('==============>');
    $('.new_Btn_milestone').prop('disabled', true);
    e.stopImmediatePropagation();
    var send = true,
        isSend = true;
    if (typeof(event) == 'object')
        if (event.timeStamp - lastJQueryTS < 300) {
            send = false;
            lastJQueryTS = event.timeStamp;
        }
    if (send) {
        var obj = $(this),
            ddi = obj.closest('.dd-item'),
            mValue = ddi.find('.milestones-name').val(),
            mDateValue = dateStringToDbDate(ddi.find('.milestones-date').val()),
            milestonesId = ddi.find('.save-milestone-info').data('milestones-id');
        /*clientId = $('input[name="goalClientId"]').val();*/
        // ddi.find('.milestones-name').attr('disabled', true);
        // ddi.find('.milestones-date').attr('disabled', true);
        ddi.find('.edit-milestones-name').attr('disabled', true);
        ddi.find('.edit-milestones-date').attr('disabled', true);

        ddi.find('.save-milestone-info').hide();
        ddi.find('.edit-milestone-info').show();
        var pre_goalId = $('#last-insert-id').val();
        if (typeof pre_goalId == 'undefined' || pre_goalId == '') pre_goalId = $('#goal_milestones_id').val();
        clearTimeout(timeoutReference);
        timeoutReference = setTimeout(function() {
            if (isSend) {
                isSend = false;
                $.ajax({
                    url: public_url + 'goal-buddy/updatemilestones',
                    type: 'POST',
                    data: {
                        'milestonesId': milestonesId,
                        'mValue': mValue,
                        'mDateValue': mDateValue,
                        'goalId': pre_goalId
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status == 'true') {
                            if (data.id > 0){
                                console.log('milll', ddi, data.id);
                                ddi.find('.milestones-name').attr('data-milestones-id', data.id);
                                ddi.attr('data-milestones-id', data.id);
                                $('.new_Btn_milestone').attr('data-new-milestones-id',data.id);
                                $('.new_Btn_milestone').prop('disabled', false);
                                // ddi.data('milestones-id', data.id);
                            } 

                            // showMIlestoneDd();
                        }
                    }
                });
            }
        }, 500);
    }
    // $('#Milestones').val(''); // 02-07-2021
});
var goal_type;
goal_type = $('input[name="chooseGoal"]:checked').val();
$('.nextData').click(function() {

    goal_type = $('input[name="chooseGoal"]:checked').val();
    var check = $('#wrapped .wizard-step.current');
    var step_no = $('#wrapped .wizard-step.current').data('step');
    // var goal_id = $('#goal_milestones_id').val();
    var goalTempleteId = $('input[name="template"]:checked').data('id');
    if(goalTempleteId != undefined ){
        if(goalTempleteId == 1 || goalTempleteId == 4){
            switch (goalTempleteId) {
                case 1:
                     var image_path = BASE_URL + '/assets/images/next-weightmanagement.png';
                     $(".slide-img").attr("src",image_path); 
                   break;
                case 4:
                    var image_path = BASE_URL + '/assets/images/next-improve_h.png';
                    $(".slide-img").attr("src",image_path); 
                  break;
              }
        }
        
    }
    $("html").animate({ scrollTop: 0 }, "slow"); //  scroll up
    console.log('goalTempleteId===',goalTempleteId,'check--------------',step_no, check, goal_type);
    var habitId = $('#habit-id').val();
    var formdata={};
    // if(check.hasClass('milestoneEmail')){
    //     $('#gb_habit_select_all_milestone').trigger('click');
    // }

    if(step_no == 13){
        $('.newHabitForm').data('habit-value',0);    
            var goalDueDate = $('#datepicker_SYG').val();           
            if($('input[name="template"]:checked').data('id') != undefined){
                formdata['name'] = $("input[name=name_goal]:checked").val();
                formdata['describe_achieve'] = $("input[name=describe_achieve]:checked").val();
                formdata['gb_relevant_goal_event'] = $("input[name=gb_relevant_goal_event]:checked").val();
                formdata['accomplish'] = [];
                $('input[name="accomplish"]:checked').each(function(){
                    formdata['accomplish'].push($(this).val());
                    if($(this).val() == 'Other'){
                        formdata['gb_important_accomplish_other'] = $('#accomplish_other').val();
                    }
                });
                formdata['gb_relevant_goal'] = [];
                $('input[name="gb_relevant_goal"]:checked').each(function(){
                    formdata['gb_relevant_goal'].push($(this).val());
                    if($(this).val() == 'Other'){
                        formdata['gb_relevant_goal_other'] = $('#gb_relevant_goal_other').val();
                    }
                });

                if($("input[name=name_goal]:checked").val() == 'Other'){
                    formdata['gb_goal_name_other'] = $('#name_goal_other').val();
                }
                if($("input[name=describe_achieve]:checked").val() == 'Other'){
                    formdata['gb_achieve_description_other'] = $('#describe_achieve_other').val();
                }
                if($("input[name=gb_relevant_goal_event]:checked").val() == 'Other'){
                    formdata['gb_relevant_goal_event_other'] = $('#gb_relevant_goal_event_other').val();
                }

                formdata['failDescription'] = [];
                $('input[name="fail-description"]:checked').each(function(){
                    formdata['failDescription'].push($(this).val());
                    if($(this).val() == 'Other'){
                        formdata['gb_fail_description_other'] = $('#fail_description_other').val();
                    }
                });
                
            }else{
                formdata['name'] = $('#name_goal').val();
                formdata['describe_achieve'] = $("#description").val();
                formdata['accomplish'] = $('#accomplish').val();
                formdata['gb_relevant_goal'] = $('#relevant_goal').val();
                formdata['gb_relevant_goal_event'] = $('#gb_relevant_goal_event').val();
                formdata['failDescription'] = $('#fail-description').val();
            }
            formdata['goal_year'] = $("input[name=goal]:checked").val();
            formdata['change_life'] = [];
            $('input[name="life-change"]:checked').each(function(){
                formdata['change_life'].push($(this).val());
            });

            // formdata['change_life'] = prepareCheckBoxSelect($('#form').find('input[name="life-change"]').val());
            // $.inArray('Other',formdata['change_life'])
                 
           
            formdata['gb_change_life_reason_other'] = $('#gb_change_life_reason_other').val();
           
            //  formdata['failDescription'] = $('#fail-description').val();  08-07-2021

            formdata['image'] = $('input[name="prePhotoName"]').val();
            formdata['template'] = $('input[name="template"]:checked').data('id');
            if(goalDueDate!=''){
                formdata['due_date'] =  moment(goalDueDate, 'ddd, D MMM YYYY').format("YYYY-MM-DD");
            }
            formdata['goal_seen'] = $("input[name=goal_seen]:checked").val();
            formdata['goal_selective_friends'] = $("input[name=goal_selective_friends]").val();
            formdata['send_msg_type'] = $("input[name='goal-Send-mail']:checked").val(); 
            if(formdata['send_msg_type'] == "daily"){
                formdata['Send_mail_time'] = $('#daily_time_goal option:selected').val();
            }else if(formdata['send_msg_type'] == "weekly"){
                formdata['Send_mail_time'] = $('#weekly_day_goal option:selected').val();
            }else if(formdata['send_msg_type'] == "monthly"){
                formdata['Send_mail_time'] = $('#month_date_goal option:selected').val();
            }
         
            formdata['form_no'] = 1;
            
            var result = window.location.pathname.split('/');
            var Param = result[result.length - 3];
            var Param1 = result[result.length - 2];
            if ((Param == 'goal-buddy') && (Param1 == 'edit')) {
                $('#update-record').val('update-yes');
            }
            formdata['update_status'] = $('#update-record').val();

            formdata['last_insert_id'] = $('#last-insert-id').val();
            formdata['save_as_draft'] = false;
            console.log(formdata);
            if(formdata['update_status'] == 'update-yes') {
                $('#stones_form_button').val(1);
                formdata['last_insert_id'] = $('#last-insert-id').val();
                savegoal(formdata);
            }
            if(formdata['name'] != '' && formdata['change_life'].length > 0 && formdata['update_status'] != 'update-yes' && formdata['due_date'] != undefined ){
                $('#stones_form_button').val(1);
                savegoal(formdata);
            }
         }


    if(check.hasClass('milestoneEmail')){
        console.log('milestoneEmail');
        $('.newHabitForm').data('habit-value',0);
        var allMilestonesNames = [];
				var allMilestonesNamesId=[];
				var allMilestonesDates = [];
				$('.milestones-name').each(function() {
					var milestonesNames=$(this).val() ;
					var milestonesId = $(this).data('milestones-id');
					if(milestonesNames != ''){
						if(milestonesId!= '')
						allMilestonesNamesId.push(milestonesId +':'+ milestonesNames);
						else
						allMilestonesNames.push(milestonesNames);
					}
				});
				
				$('.datepicker_SYG4').each(function() {
					if($(this).val() != ''){
						allMilestonesDates.push($(this).val());
					}
				});

				if(allMilestonesNames.length > 0){
					formdata['milestones-names'] = allMilestonesNames;
				}
				if(allMilestonesNamesId.length > 0){
					formdata['milestones-names-id'] = allMilestonesNamesId;
				}
				if(allMilestonesDates.length > 0){
					formdata['milestones-dates'] = allMilestonesDates;
				}
				var totalNameLength=parseInt(allMilestonesNames.length)+parseInt(allMilestonesNamesId.length);
				var totalDateLength=allMilestonesDates.length;
				console.log('formdata=====', formdata);
				formdata['gb_milestones_seen'] = $("input[name=gb_milestones_seen]:checked").val();
				formdata['gb_milestones_selective_friends'] = $("input[name=gb_milestones_selective_friends]").val();

				formdata['gb_milestones_reminder'] = $("input[name='milestones-Send-mail']:checked").val(); 
				if(formdata['gb_milestones_reminder'] == "daily"){
					formdata['Send_mail_milestones_time'] = $('#daily_time_milestones').val();
				}else if(formdata['gb_milestones_reminder'] == "weekly"){
					formdata['Send_mail_milestones_time'] = $('#weekly_day_milestones').val();
				}else if(formdata['gb_milestones_reminder'] == "monthly"){
					formdata['Send_mail_milestones_time'] = $('#month_date_milestones').val();
				}
                formdata['gb_milestones_reminder_epichq'] = $("input[name='milestones-Send-epichq']:checked").val();
				
                formdata['form_no'] = 2;
				formdata['last_insert_id'] = $('#last-insert-id').val();
				formdata['goal_id_mile']=$('#goal_milestones_id').val();
				formdata['milestones_id'] = $('#milestones_id').val();
                formdata['save_as_draft'] = false;
				if( formdata['milestones_id'] == '' || formdata['milestones_id'] == undefined){
					if(totalNameLength == totalDateLength){
						savegoal(formdata);
                        
					} 
					else {
						console.log('error');
					}
				}else{
					savegoal(formdata);
                }
                console.log(formdata);
                $('#habitlist tr').find('.habit-edit').each(function(key,obj) {
                    var noOfHabits = $('#habitlist').find('tr').length;
                    $("input[name=no_of_habit]").val(noOfHabits);
                    if(key == 0){
                        $("input[name=habit_index]").val(key);
                        var habit_id = $(this).data('habit-id');
                        $('.habit-listing').hide();
                        $('.habit-form').show();
                        if(goal_type == 'create_new_goal'){
                            $('#SYG_habits').attr('readonly', false);
                        }else if(goal_type == 'choose_form_template'){
                            $('#SYG_habits').attr('readonly', true);
                        }
                        
                       
                        getHabit(habit_id);
                    }				
                });
    }
    if(check.hasClass('newTask')){
        console.log('milestoneEmail1');
        $('#wizard-ul').find('.formStepFourth').removeClass('disabled');
        $('#wizard-ul').find('.formStepFourth').addClass('selected');
        $('#wizard-ul').find('.formStepFourth').addClass('activeFormStep');
        $('#wizard-ul').find('.formStepFourth').attr('isDone',1);
        $('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
        var targetElm = document.querySelector('#wizard_container'); // reference to scroll 
        targetElm.scrollIntoView();
        $('.task-listing').hide();
        $('.task-friends-section').hide();
        // $('.task-form').show();
        $('#tasklist tr').find('.task-edit').each(function(key,obj) {
           
        var noOfTask = $('#tasklist').find('tr').length;
        $("input[name=no_of_task]").val(noOfTask);
        if(key == 0){
            $("input[name=task_index]").val(key);
            var task_id = $(this).data('task-id');
            $('.task-listing').hide();
            $('.task-form').show();
            if(goal_type == 'create_new_goal'){
                $('#SYG3_task').attr('readonly', false);
            }else if(goal_type == 'choose_form_template'){
                $('#SYG3_task').attr('readonly', true);
            }

            getTask(task_id);
         }				
      });
    }
    
    if(check.hasClass('habitStep')){
        console.log('first setp', 'milestoneEmail' );
        $('#wizard-ul').find('.formStepSecond').removeClass('disabled');
        $('#wizard-ul').find('.formStepSecond').addClass('selected');
        $('#wizard-ul').find('.formStepSecond').attr('isDone',1);
        $('#wizard-ul').find('.formStepSecond').addClass('activeFormStep');
        $('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
        var result = window.location.pathname.split('/');
        var Param = result[result.length - 3];
        var Param1 = result[result.length - 2];
        if ((Param == 'goal-buddy') && (Param1 == 'edit')) {
            $('#update-record').val('update-yes');
            $('.data-step').trigger('change');
        }
    }
    if(check.hasClass('showNext')){
        console.log('milestoneEmail');
        $('#wizard-ul').find('.formStepFive').removeClass('disabled');
        $('#wizard-ul').find('.formStepFive').addClass('selected');
        $('#wizard-ul').find('.formStepFive').attr('isDone',1);
        $('#wizard-ul').find('.formStepFive').addClass('activeFormStep');
        $('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');

    }
    
    if(check.hasClass('milestoneEmail')){
        console.log('milestoneEmail');
        $('#wizard-ul').find('.formStepThird').removeClass('disabled');
        $('#wizard-ul').find('.formStepThird').addClass('selected');
        $('#wizard-ul').find('.formStepThird').attr('isDone',1);
        $('#wizard-ul').find('.formStepThird').addClass('activeFormStep');
        $('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
        $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
    }
    if(check.hasClass('newHabitForm')){

        console.log('milestoneEmail');
        
        // if( $('#SYG_habits').attr('readonly') == undefined){
        // $('#SYG_habits').attr('readonly','readonly');
        // }

// var new_habit_index = parseInt(habit_index) + 1;
// var no_of_index = $('#habitlist').find('tr').length;
    
        formdata['habit_name'] = $('#SYG_habits').val();
        formdata['habit_recurrence'] = $('input[name="SYG_habit_recurrence"]:checked').val();
        // $('.habitRecurrenceWrapper').each(function(){
        //     if($(this).hasClass('active'))
        //     {
        //         formdata['habit_recurrence'] = $(this).data('recurrence-type');
        //     }
        // })
        if(formdata['habit_recurrence'] == 'weekly'){
            var selectedWeeks = [];
            $('#wrapped').find('.goalEventRepeatWeekdays').each(function() {
                if(this.checked)
                {
                    selectedWeeks.push($(this).val());
                }
            });
            formdata['habit_weeks'] = selectedWeeks;
        } 
        else if(formdata['habit_recurrence'] == 'monthly'){
            formdata['month'] = $(".month-date option:selected").text();
        }
        // formdata['habit_notes'] = $('#SYG_notes').val(); //14-07-2021
        if(goal_type == 'create_new_goal'){
            formdata['habit_notes'] = $('#SYG_notes').val();
        }else if(goal_type == 'choose_form_template'){
            formdata['habit_notes'] = [];
            $('input[name="SYG_notes"]:checked').each(function(){
                formdata['habit_notes'].push($(this).val());
                if($(this).val() == 'Other'){
                    formdata['gb_habit_note_other'] = $('#habit_note_other').val();
                }
            });
        }
        
       
       console.log('formdata-----', formdata);
        formdata['habit_milestone']=$('select[name="milestone_value"]').val();
        formdata['habit_seen'] = $("input[name=syg2_see_habit]:checked").val();
        formdata['syg2_selective_friends'] = $("input[name=syg2_selective_friends]").val();
        // formdata['habit_reminders'] = $("input[name=syg2_send_msg]").val();
        formdata['form_no'] = 3;
        formdata['last_insert_id'] = $('#last-insert-id').val();
        // viewStep.find('.who-can-view').each(function(){
        //     if($(this).hasClass('active'))
        //     {
        //         formdata['habit_seen'] = $(this).data('value');
        //     }
        // })
        // viewStep.find('.send-reminders').each(function(){
        // 	if($(this).hasClass('active'))
        // 	{
        // 		formdata['habit_reminders'] = $(this).data('value');
        // 	}
        // })

        formdata['habit_reminders'] = $("input[name='habits-send-mail']:checked").val(); 
        if(formdata['habit_reminders'] == "daily"){
            formdata['Send_mail_habits_time'] = $('#daily_time_habits option:selected').val();
        }else if(formdata['habit_reminders'] == "weekly"){
            formdata['Send_mail_habits_time'] = $('#weekly_day_habits option:selected').val();
        }else if(formdata['habit_reminders'] == "monthly"){
            formdata['Send_mail_habits_time'] = $('#month_date_habits option:selected').val();
        }

        formdata['gb_habit_reminder_epichq'] = $("input[name='habits-send-epichq']:checked").val();
        console.log(formdata);
        var habitId = $('#habit-id').val();
        
        f=2;
        if($('#SYG_habits').val() != ''){
            console.log('5')
            f=2;
            $('.habit-form').hide();
            $('.habit-listing').show();
            $('.cancel_habbit_btn').addClass('hidden');
        }
        if(habitId == '' || habitId == undefined){
            if(j == 1){
                console.log('6')
                if(formdata['habit_name'] != ''){

                    savegoal(formdata);
                    f=1;
                } 
                else {
                    f=2;
                }
            }
        } 
        else {
            console.log('7')
            formdata['habit_id'] = habitId;
            savegoal(formdata);
            f=1;
        }

        // $('input[name="SYG_habit_recurrence"]').prop('checked', false);
        // $('#gb_habit_select_all_milestone').trigger('click');
        // $('input[name="habit"]').prop('checked', false);
        // $('input[name="habits-send-mail"]').prop('checked', false);

            var habit_index = $('#habit-index').val();
            var new_habit_index = parseInt(habit_index) + 1;
            var no_of_index = $('#habitlist').find('tr').length;
            $('#habitlist tr').find('.habit-edit').each(function(key,obj) {
                // console.log('habit-edit');
                if(new_habit_index == key){
                    if(no_of_index  == new_habit_index){
                        console.log('3');
                        $('.habit-form').hide();
                        $('.habit-listing').show();
                        $('.cancel_habbit_btn').addClass('hidden');
                    }else{
                        $('#habit-index').val(new_habit_index);
                        
                        // $('.newHabitForm').data('habit',new_habit_index);
                        console.log('4');
                        getHabit($(this).data('habit-id'));
                    }
                }
            
            });
            
            
        
    
    }

    if(check.hasClass('taskNext')){
        console.log('milestoneEmail');
        $('#m-selected-step').val(5);
        formdata['task_name'] = $('#SYG3_task').val();
        formdata['note'] = $('#note').val();
        
        formdata['task_priority'] = $("input[name=Priority]:checked").val();
        formdata['task_habit_id'] = $('select[name="habit_value"]').val();
        formdata['task_seen'] = $("input[name=SYG3_see_task]:checked").val();
        formdata['SYG3_selective_friends'] = $("input[name=SYG3_selective_friends]").val();

        formdata['task_reminders'] = $("input[name='creattask-send-mail']:checked").val(); 
        if(formdata['task_reminders'] == "daily"){
            formdata['Send_mail_task_time'] = $('#daily_time_task').val();
        } 

        formdata['gb_task_reminder_epichq'] = $("input[name='creattask-send-epichq']:checked").val();

        formdata['task_recurrence'] = $("input[name=SYG_task_recurrence]:checked").val();
        
        if(formdata['task_recurrence'] == 'weekly'){
            var selectedWeeks = [];
            $('#wrapped').find('.taskEventRepeatWeekdays').each(function() {
                if(this.checked)
                {
                    selectedWeeks.push($(this).val());
                }
            });
            formdata['task_weeks'] = selectedWeeks;
        } 
        else if(formdata['task_recurrence'] == 'monthly'){
            formdata['month'] = $(".month-date-task option:selected").text();
        }
        formdata['form_no'] = 4;
        formdata['last_insert_id'] = $('#last-insert-id').val();
        // var taskId = $('#task-id').val();
        var taskId = $('#task_id_new').val();
        if( taskId == '' || taskId == undefined){

            if(formdata['task_name'] != '' && formdata['task_priority'] != ''){
                    savegoal(formdata);
                    g=1;
                
            } 
        }
        else {
            formdata['task_id'] = taskId;
            savegoal(formdata);
            
        }
console.log(formdata);
    //    if( $(".task-listing").css('display') == 'block') {
    //             isJumpTask = "true";
    //             b =1;
    //         }
    
            var task_index = $('#task-index').val();
            var new_task_index = parseInt(task_index) + 1;
            var no_of_index = $('#tasklist').find('tr').length;
            if(task_index != "na"){
                $('#tasklist tr').find('.task-edit').each(function(key,obj) {
                    if(new_task_index == key)
                    {
                        if(no_of_index  == new_task_index){
                            $('.task-form').hide();
                            $('.task-listing').show();
                            $('.cancel_htask_btn').addClass('hidden');
                        }else{
                            $('#task-index').val(new_task_index);
                            getTask($(this).data('task-id'));
                            // $(this).trigger('click');
                        }
                    }else{
                        if(no_of_index  == new_task_index)
                        {
                            if($('a[href="#step-4"]').hasClass('done'))
                            {
                            }
                            else{
                                $('.task-listing').show();
                                $('.task-friends-section').hide();
                                $('.task-form').hide();
                                $('.cancel_task_btn').addClass('hidden');
                                // return false;
                            }
                        }
                    }
                });
                // e=1;
                // isJumpTask = "true";
                // return false;
            }else{
                $('.task-listing').show();
                $('.task-friends-section').hide();
                $('.task-form').hide();
                $('.cancel_task_btn').addClass('hidden');
                // isJumpTask = "true";
                // return false;
            }
        
    
    
                        
    }
    

    
}) 

$('.backward').click(function() {
    var check = $('#wrapped .wizard-step.current');
    var templateCheck = $("input[name='chooseGoal']:checked").val() == 'choose_form_template' ? true : false;
    
    if(check.hasClass('newHabitForm') && templateCheck){
        var habit_index = $('#habit-index').val();
        var new_habit_index = parseInt(habit_index) - 1;
        if(new_habit_index == 2)
            new_habit_index = 1;
        var no_of_index = $('#habitlist').find('tr').length;
        $('#habitlist tr').find('.habit-edit').each(function(key,obj) {
            // console.log('habit-edit');
            if(new_habit_index == key){
                if(no_of_index  == new_habit_index){
                    console.log('3');
                    $('.habit-form').hide();
                    $('.habit-listing').show();
                    $('.cancel_habbit_btn').addClass('hidden');
                }else{
                    $('#habit-index').val(new_habit_index);
                    
                    // $('.newHabitForm').data('habit',new_habit_index);
                    console.log('4');
                    getHabit($(this).data('habit-id'));
                }
            }
        
        });
    }

    if(check.hasClass('taskNext') && templateCheck){
        var task_index = $('#task-index').val();
        var new_task_index = parseInt(task_index) - 1;
        if(parseInt(task_index) == 9)
            new_task_index = 7;
        var no_of_index = $('#tasklist').find('tr').length;

        $('#tasklist tr').find('.task-edit').each(function(key,obj) {
            if(new_task_index == key){
                if(no_of_index  == new_task_index){
                    /*console.log('3');
                    $('.habit-form').hide();
                    $('.habit-listing').show();
                    $('.cancel_habbit_btn').addClass('hidden');*/
                }else{
                    $('#task-index').val(new_task_index);
                    getTask($('#tasklist tr:eq('+new_task_index+')').find('.task-edit').data('task-id'));
                }
            }
        
        });
    }

});

$('.final-step-submit').click(function(){
    // e.preventDefault();
    $('#m-selected-st').val(5);
			var allVals = [];
            var formdata = {};
            isFormValid = true;
			$('input[type="checkbox"]:checked.goalsmart').each(function() {
				allVals.push($(this).val());
			});
				// if(allVals.length > 0){
				// 	formdata['review'] = allVals;
				
                // formdata['form_no'] = context.fromStep;

                if(allVals.length > 0){
                    formdata['review'] = allVals;
                }
                	
					var is_specificField_checked = $("#Specific").attr("data-is-checked");
					var is_measurableField_checked = $("#Measurable").attr("data-is-checked");
					var is_attainableField_checked = $("#Attainable").attr("data-is-checked");
					var is_relevantField_checked = $("#Relevant").attr("data-is-checked");
					var is_timeBoundField_checked = $("#Time-Bound").attr("data-is-checked");
					if((is_specificField_checked != 'yes') || (is_measurableField_checked != 'yes') || (is_attainableField_checked != 'yes') || (is_relevantField_checked != 'yes') || (is_timeBoundField_checked != 'yes') ){
						isFormValid = false;
                    }
               if(isFormValid){
                    formdata['form_no'] = 5;
                    formdata['goal_notes'] = $('#goal_notes').val();
                    formdata['last_insert_id'] = $('#last-insert-id').val();
                    savegoal(formdata);
                    swal('Data has been saved successfully');
                    window.location.href = public_url+'goals';
               }else{
                swal('Please select all S.M.A.R.T. goals');
               }
			
});

$('input[name="template"]').on('click',function(){
   var div = $(this).closest('div'),
  img = div.find('img').attr('src');
    $('.content-left-wrapper').find('img').attr('src',img);
    console.log( $('.content-left-wrapper').find('img'));
})

$(".add-note").on('click',function() {
    $("#general-notes-popup").show();
})


$(document).on('change',"input[name=name_goal]:checked",function(){
    if($(this).val() == 'Other'){
        $('#name_goal_other').removeClass('hidden');
        $('#name_goal_other').attr('required',true);
        $('#name_goal_other').attr('placeholder','Input Your Specific Goal Here...');
    }else{
        $('#name_goal_other').addClass('hidden');
        $('#name_goal_other').attr('required',false);
        $("#name_goal_other-error").html('');
        $('#name_goal_other').val('');
    }
})

$(document).on('change',"input[name=describe_achieve]:checked",function(){
    if($(this).val() == 'Other'){
        $('#describe_achieve_other').removeClass('hidden');
        $('#describe_achieve_other').attr('required',true);
        $('#describe_achieve_other').attr('placeholder','Describe your achievement Here...');
    }else{
        $('#describe_achieve_other').addClass('hidden');
        $('#describe_achieve_other').attr('required',false);
        $("#describe_achieve_other-error").html('');
        $('#describe_achieve_other').val('');
    }
})

$(document).on('click',".accomplish_other",function(){
    if($(this).is(":checked")) {
        $('#accomplish_other').removeClass('hidden');
        $('#accomplish_other').attr('required',true);
    } else {
        $('#accomplish_other').addClass('hidden');
        $('#accomplish_other').attr('required',false);
        $("#accomplish_other-error").html('');
        $('#accomplish_other').val('');
    }
})

$(document).on('click',".habit_note_other",function(){
    if($(this).is(":checked")) {
        $('#habit_note_other').removeClass('hidden');
        $('#habit_note_other').attr('required',true);
    } else {
        $('#habit_note_other').addClass('hidden');
        $('#habit_note_other').attr('required',false);
        $("#habit_note_other-error").html('');
        $('#habit_note_other').val('');
    }
})

$(document).on('click',".fail_description_other",function(){
    if($(this).is(":checked")) {
        $('#fail_description_other').removeClass('hidden');
        $('#fail_description_other').attr('required',true);
    } else {
        $('#fail_description_other').addClass('hidden');
        $('#fail_description_other').attr('required',false);
        $("#fail_description_other-error").html('');
        $('#fail_description_other').val('');
    }
})

$(document).on('click',".gb_relevant_goal_other",function(){
    if($(this).is(":checked")) {
        $('#gb_relevant_goal_other').removeClass('hidden');
        $('#gb_relevant_goal_other').attr('required',true);
    } else {
        $('#gb_relevant_goal_other').addClass('hidden');
        $('#gb_relevant_goal_other').attr('required',false);
        $("#gb_relevant_goal_other-error").html('');
        $('#gb_relevant_goal_other').val('');
    }
})

$(document).on('change',"input[name=gb_relevant_goal_event]:checked",function(){
    if($(this).val() == 'Other'){
        $('#gb_relevant_goal_event_other').removeClass('hidden');
        $('#gb_relevant_goal_event_other').attr('required',true);
    }else{
        $('#gb_relevant_goal_event_other').addClass('hidden');
        $('#gb_relevant_goal_event_other').attr('required',false);
        $("#gb_relevant_goal_event_other-error").html('');
        $('#gb_relevant_goal_event_other').val('');
    }
})

$(document).on('click','.choose-immediate-priority',function() {
    // $("input[name=goal]").siblings('span').html('');
    // $("input[name=goal]").trigger('refresh');
    // alert($("input[name=goal]:checked").val());
    $("#temp-modal").hide();
    $("#temp-modal").removeClass('in show');
    $('.backward').trigger('click');
    $('.backward').trigger('click');
})

$(document).on('click','.choose-create-new-goal',function() {
    // $('input[name="goal"][value="No"]').prop('checked', false);
   
    var image_path = BASE_URL + '/assets/images/BM-slimming_1.png';
    $(".slide-img").attr("src",image_path);

    goal_type = 'create_new_goal';
    $('input[name="template"]').each(function(){
        $(this).prop("checked", false);
    })
    $(".append-template-goal-name").html('');
    $(".template-description-achieve").html('');
    $(".template-accomplish").html('');
    $(".template-relevant-goal").html('');
    $(".template-relevant-goal-event").html('');
    $('.fail_description').text('');

    $('.life_change_reason').each(function(){
        $(this).prop("checked", false);
    })
    $(".append-template-goal-name").append(
        '<div class="outborder"><textarea ng-mouseenter="pressEnter($event)" data-toggle="tooltip"  data-html="true" title="" data-autoresize id="name_goal" name="name_goal" ng-model="name_goal"  placeholder="" class="form-control" required></textarea></div>'
    )
    $(".template-description-achieve").append(
        '<div class="outborder"><textarea ng-blur="pressEnter($event)" id="description" data-toggle="tooltip" data-html="true" title="" data-autoresize id="describe_achieve" name="describe_achieve" ng-model="describe_achieve" placeholder="" class="form-control" required></textarea></div>'
    )
    $(".template-accomplish").append(
        '<div class="outborder"><textarea ong-blur="pressEnter($event)" data-toggle="tooltip" data-html="true" title="" data-autoresize id="accomplish" name="accomplish" ng-model="accomplish" placeholder="" class="form-control" required></textarea></div>'
    )
    $(".template-relevant-goal").append(
        '<div class="outborder"><textarea ng-blur="pressEnter($event)" id="relevant_goal" data-toggle="tooltip" title="" data-autoresize id="gb_relevant_goal" name="gb_relevant_goal" ng-model="gb_relevant_goal" placeholder="" class="form-control" required></textarea></div>'
    )
    $(".template-relevant-goal-event").append(
        '<div class="outborder"><textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="" data-autoresize id="gb_relevant_goal_event" name="gb_relevant_goal_event" ng-model="gb_relevant_goal_event" placeholder="" class="form-control" required></textarea></div>'
    )
    $(".fail_description").append(
        '<div class="outborder"><textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="" data-autoresize id="fail-description" name="fail-description" ng-model="fail_description" placeholder="" class="form-control" required></textarea></div>'
    )
    $("#temp-modal").hide();
    $("#temp-modal").removeClass('in show');
    $('.backward').trigger('click');
    $('.backward').trigger('click');
    $('.backward').trigger('click');
    $('.backward').trigger('click'); 
    $('.choose-create-new-goal-first').prop('checked', true);
    $('.nextData').trigger('click'); 
})

$(document).on('click','.choose-create-new-goal-first',function() {
    var check_class = $('.choose-create-new-goal').hasClass('check-class-popup');
   
    var image_path = BASE_URL + '/assets/images/BM-slimming_1.png';
    $(".slide-img").attr("src",image_path);

    goal_type = 'create_new_goal';
    $('input[name="template"]').each(function(){
        $(this).prop("checked", false);
    })
    $(".append-template-goal-name").html('');
    $(".template-description-achieve").html('');
    $(".template-accomplish").html('');
    $(".template-relevant-goal").html('');
    $(".template-relevant-goal-event").html('');
    $('.fail_description').text('');

    $('.life_change_reason').each(function(){
        $(this).prop("checked", false);
    })
    $(".append-template-goal-name").append(
        '<textarea ng-mouseenter="pressEnter($event)" rows="3" data-toggle="tooltip"  data-html="true" title="" data-autoresize id="name_goal" name="name_goal" ng-model="name_goal"  placeholder="" class="form-control" required></textarea>'
    )
    $(".template-description-achieve").append(
        '<textarea ng-blur="pressEnter($event)" rows="3" id="description" data-toggle="tooltip" data-html="true" title="" data-autoresize id="describe_achieve" name="describe_achieve" ng-model="describe_achieve" placeholder="" class="form-control" required></textarea>'
    )
    $(".template-accomplish").append(
        '<textarea ong-blur="pressEnter($event)" data-toggle="tooltip" data-html="true" title="" data-autoresize rows="3" id="accomplish" name="accomplish" ng-model="accomplish" placeholder="" class="form-control" required></textarea>'
    )
    $(".template-relevant-goal").append(
        '<textarea ng-blur="pressEnter($event)" id="relevant_goal" data-toggle="tooltip" title="" data-autoresize rows="3" id="gb_relevant_goal" name="gb_relevant_goal" ng-model="gb_relevant_goal" placeholder="" class="form-control" required></textarea>'
    )
    $(".template-relevant-goal-event").append(
        '<textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="" data-autoresize rows="3" id="gb_relevant_goal_event" name="gb_relevant_goal_event" ng-model="gb_relevant_goal_event" placeholder="" class="form-control" required></textarea>'
    )
    $(".fail_description").append(
        '<textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="" data-autoresize rows="3" id="fail-description" name="fail-description" ng-model="fail_description" placeholder="" class="form-control" required></textarea>'
    )
    $("#temp-modal").hide();
    $("#temp-modal").removeClass('in show');
    // $('.backward').trigger('click');
    // $('.backward').trigger('click');
    // $('.backward').trigger('click');
    // $('.backward').trigger('click');

})


$(document).on('click', '.step-back',function(){
    scrollToGoalTop();
    var check = $('.step-back');
    var templateCheck = $("input[name='chooseGoal']:checked").val() == 'choose_form_template' ? true : false;

    if(check.hasClass('formStepfirst')){ //<13
        moveBackwardToMilestone();
        return false;
        /*var countLen = moveToBack();
        for (var i = 1; i < countLen; i++) {
            $('.backward').trigger('click');
        }
        return false;*/
     }

     if(check.hasClass('formStepSecond')){ // <4
        /*var countLen = moveToBack();
        for (var i = 1; i <= countLen; i++) {
            $('.backward').trigger('click');
        }*/
        //moveBackwardToHabitList();
        moveBackwardToMilestone();
        return false;
     }

     if(check.hasClass('formStepThird')){ // <=2
        /*var countLen = moveToBack();
        for (var i = 1; i <= countLen; i++) {
            $('.backward').trigger('click');
        }*/
        moveBackwardToHabitList();
        return false;
     }

     if(check.hasClass('formStepFourth')){ // <= 2
        /*var countLen = moveToBack();
        for (var i = 1; i <= countLen; i++) {
            $('.backward').trigger('click');
        }*/
        /*if(templateCheck)
            moveBackwardToHabitList();
        else*/
            moveBackwardToTaskList();

        return false;
     }
});


$(document).on('click', '.step-forward',function(){
    scrollToGoalTop();
    var check = $('.step-forward');
    var templateCheck = $("input[name='chooseGoal']:checked").val() == 'choose_form_template' ? true : false;

     if(check.hasClass('formStepSecond')){ // <=13
     if($("#stones_form_button").val() == 1){
           moveForwardToMilestone();
        } else {
          $('.formStepSecond').addClass('disable-btn');  
        }
        return false;
        /*var countLen = moveToNext();
        for (var i = 1; i <= countLen; i++) {
            $('.forward').trigger('click');
        }
       return false;  */
     }

     if(check.hasClass('formStepThird')){ // <4
        /*var countLen = moveToNext();
        for (var i = 1; i <= countLen; i++) {
            $('.forward').trigger('click');
        }*/
//  console.log('pppppppppppppppppppp==', $("#habit_form_validation").val());
        // if($("#habit_form_validation").val() == 1){
        //     $('.forward_validation').text('hhhhhhhhhhhhhhhhhhhhhhh');
            moveForwardToHabitList();
        //  }
      
        return false;
     }

     if(check.hasClass('formStepFourth')){ // <=2
        /*var countLen = moveToNext();
        for (var i = 1; i <= countLen; i++) {
            $('.forward').trigger('click');
        }*/
        if(templateCheck)
            moveForwardToTaskList();
        else
            moveForwardToTaskListCstm();

       return false;
     }

     if(check.hasClass('formStepFive')){ // < 2
        var countLen = moveToNext();
        for (var i = 1; i <= countLen; i++) {
            $('.forward').trigger('click');
        }
       return false;
     }
});

function moveToNext(){
    var dataStep = 0;
    var returnCount = 0;
    var templateCheck = $("input[name='chooseGoal']:checked").val() == 'choose_form_template' ? true : false;
    var task_index = $('#task-index').val();
    var habit_index = $('#habit-index').val();
    var total_task_count = 9;
    //$('#tasklist').find('tr').length;
    var total_habit_count = 3;
    $('#habitlist').find('tr').length;

    $('.step').each(function(){
        if($(this).is(":visible")){
         dataStep = $(this).data('step');
        }
    });

    if(dataStep == 1 && !templateCheck){
        return 12;
    }

    if(dataStep == 1 && templateCheck){
        return 13;
    }

    if(dataStep > 1 && dataStep < 14){
        returnCount = 14 - dataStep;
        return returnCount;
    }
    if( (dataStep > 14 && dataStep < 17 ) && !templateCheck){
        returnCount = 17 - dataStep ;
        return returnCount;
    }

    if(dataStep == 14 && !templateCheck){
        return 3;
    }

    if(dataStep == 14 && templateCheck){
        return 3+total_habit_count;
    }

    if(dataStep == 15 && templateCheck){
        return 2+total_habit_count;
    }

    if(dataStep == 16 && templateCheck){
        return 1+total_habit_count;
    }

    if(dataStep == 22 && templateCheck){
        return total_task_count - task_index+1;
    }
    
    if(dataStep == 20 && templateCheck){
        return (total_habit_count - habit_index) + 1 + total_task_count + 1;
    }

    if(dataStep == 21 && templateCheck){
        return total_task_count+1;
    }

    if(dataStep == 18 || dataStep == 20 || dataStep == 22){
        return 2;
    }
    if(dataStep == 19 || dataStep == 21 || dataStep == 23){
        return 1;
    }
    
}

function moveToBack(){
    var dataStep = 0;
    var returnCount = 0;
    var templateCheck = $("input[name='chooseGoal']:checked").val() == 'choose_form_template' ? true : false;
    var task_index = $('#task-index').val();
    var habit_index = $('#habit-index').val();
    var total_task_count = 9;
    //$('#tasklist').find('tr').length;
    var total_habit_count = 3;
    //$('#habitlist').find('tr').length;

    $('.step').each(function(){
        if($(this).is(":visible")){
         dataStep = $(this).data('step');
        }
    });

    if(dataStep > 1 && dataStep <= 14){
        returnCount = dataStep;
        return returnCount;
    }
    if(dataStep > 14 && dataStep < 17){
        returnCount = dataStep - 14 ;
        return returnCount;
    }

    if(dataStep == 20){
        return 3;
    }

    if((dataStep == 22 || dataStep == 24 ) && !templateCheck){
        return 2;
    }

    if(dataStep == 24 && templateCheck){
        return 1;
    }

    if(dataStep == 22 && templateCheck){
        if(task_index == 0){
            return 1;
        }else if(task_index == 9){
            return 8;
        }else
        return task_index;
    }

    if( (dataStep == 21 || dataStep == 23) && !templateCheck ){
        return 1;
    }

    if(dataStep == 23 && templateCheck){
        return total_task_count;
    }

    if(dataStep == 21 && templateCheck){
        return total_habit_count+2;
    }
}

function moveBackwardToMilestone(){
    $('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepSecond').addClass('activeFormStep');
    $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
    $('.backward').trigger('click');
    
    $('.step').each(function(){
        if($('.milestoneData').data('value') == 0){
            $('.backward').trigger('click');
        }else{
            return false;
        }
    });
}

function moveForwardToMilestone(){
    $('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepSecond').addClass('activeFormStep');
    $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
    $('.forward').trigger('click');
    
    $('.step').each(function(){
        if($('.milestoneData').data('value') == 0){
            $('.forward').trigger('click');
        }else{
            return false;
        }
    });
}

function moveForwardToHabitList(){
    $('#wizard-ul').find('.formStepThird').addClass('activeFormStep');
    $('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
    $('.forward').trigger('click');
    
    $('.step').each(function(){
        if($('.newTask').data('value') == 0){
            $('.forward').trigger('click');
        }else{
            return false;
        }
    });
}

function moveBackwardToHabitList(){
    $('#wizard-ul').find('.formStepThird').addClass('activeFormStep');
    $('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFourth').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
    $('.backward').trigger('click');
    
    $('.step').each(function(){
        if($('.newTask').data('value') == 0){
            $('.backward').trigger('click');
        }else{
            return false;
        }
    });
}

function moveForwardToTaskList(){
    $('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFourth').addClass('activeFormStep');
    $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
    $('.forward').trigger('click');
    
    $('.step').each(function(){
        if($('.showNext').data('value') == 0){
            $('.forward').trigger('click');
        }else{
            return false;
        }
    });
}

function moveForwardToTaskListCstm(){
    $('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFourth').addClass('activeFormStep');
    $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
    $('.forward').trigger('click');
    
    $('.step').each(function(){
        if($('.showNext').data('value') == 0){
            $('.forward').trigger('click');
        }else{
            return false;
        }
    });
}

function moveBackwardToTaskList(){
    $('#wizard-ul').find('.formStepThird').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepSecond').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepfirst').removeClass('activeFormStep');
    $('#wizard-ul').find('.formStepFourth').addClass('activeFormStep');
    $('#wizard-ul').find('.formStepFive').removeClass('activeFormStep');
    $('.backward').trigger('click');
    
    $('.step').each(function(){
        if($('.showNext').data('value') == 0){
            $('.backward').trigger('click');
        }else{
            return false;
        }
    });
}

/* notification */
	$('body').on('click','input[name="goal-Send-epichq"]',function(){	     
		var goal_val = $('input[name="goal-Send-epichq"]:checked').val();
        if( goal_val == 'none'){
            $('input[name="goal-Send-mail"]:checked').each(function(){
                $(this).prop('checked', false);
            });
         }   
	});

  $('body').on('click','input[name="milestones-Send-epichq"]',function(){	   	
		var goal_val = $('input[name="milestones-Send-epichq"]:checked').val();
        if( goal_val == 'none'){
            $('input[name="milestones-Send-mail"]:checked').each(function(){
                $(this).prop('checked', false);
            });
         }   
	});

    $('body').on('click','input[name="habits-send-epichq"]',function(){		
		var goal_val = $('input[name="habits-send-epichq"]:checked').val();
        if( goal_val == 'none'){
            $('input[name="habits-send-mail"]:checked').each(function(){
                $(this).prop('checked', false);
            });
         }   
	});

    $('body').on('click','input[name="creattask-send-epichq"]',function(){	
		var goal_val = $('input[name="creattask-send-epichq"]:checked').val();
        if( goal_val == 'none'){
            $('input[name="creattask-send-epichq"]:checked').each(function(){
                $(this).prop('checked', false);
            });
         }   
	});

/* end  */
