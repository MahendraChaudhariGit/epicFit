var lastJQueryTS = 0 ,
    mileStoneSave = true,
    timeoutReference = null;

/* Start: Document ready */
$(document).ready(function(){
    $(".habit-friends-section").hide();

    /* Back to client view action */
    $('#back-to-client-view').click(function(e){
        e.preventDefault();
        var clientId = parseInt($('input[name="goalClientId"]').val());
        window.location.href = public_url+"client/"+clientId+'#panel_assess_progress';
    })

    /* Show Create goal form */
    $('body').on( 'click', '.create-goal', function(e) {
        $('.client-goalbuddylist').hide();
        $('#goalbuddy-crate-section').removeClass('hidden');
    });

    /* Show calendar div */
    $('body').on( 'click', '#showcalendardiv', function(e) {
         e.preventDefault();
         $('#showcalendar').removeClass('hidden');
         $('.clientshowListCls').addClass('hidden'); 
         $('.clientGoalListCls').addClass('hidden');     
    });

    /* Display client goal in list */
    $('body').on( 'click', '#clientGoalList', function(e) {
        e.preventDefault();
        $('#showcalendar').addClass('hidden'); 
        $('.clientshowListCls').addClass('hidden');   
        $('.clientGoalListCls').removeClass('hidden');                 
    });

    /* Display all user in list */
    $('body').on( 'click', '#clientUser', function(e) {
         e.preventDefault();
         $('#showcalendar').addClass('hidden'); 
         $('.clientGoalListCls').addClass('hidden');   
         $('.clientshowListCls').removeClass('hidden');                 
    });

    /* Intialize date picker */
    $('.datepicker1').datepicker();
    /*$('#datepicker_manage, #datepicker_SYG, #datepicker_SYG00, #gb_milestones_date' ).datepicker({
        todayHighlight: 'TRUE',
        startDate: '-0d',
        autoclose: true,
        format:'D, d M yyyy' 
    }); */
    $('#datepicker_manage, #datepicker_SYG, #datepicker_SYG00, #gb_milestones_date' ).datepicker({autoclose:true, dateFormat:"D, d M yy"});

    /* Start: Add milestone on change date */
    $('.Btn_milestone').click(function(e) {
        e.preventDefault();
        updateMilestones = true;
        var milestoneValue = $('#Milestones').val();
        $('.mile_section ul').append('<li class="dd-item row m-t-10" style="line-height: 20px; !important" data-milestones-id=""><div class="col-md-5 col-xs-5" style="padding: 0px 5px 0px 0px;"><input type="text" name ="milestones" class = "form-control milestones-name" value="'+milestoneValue+'"></div><div class="col-md-4 col-xs-4" style="padding:0px 5px 0px 0px"><input type="text" class="form-control milestones-date" autocomplete="off" name ="milestones-date"  required></div><div class="col-md-2 col-xs-2 m-t-10 pencil_find_sibling"><a><i class="fa fa-times delete-milestone-info"  style="margin-right: 8px" ></i></a><a><i class="fa fa-pencil edit-milestone-info" style="font-size: 16px; margin-right: 8px" ></i></a><a><i class="fa fa-save save-milestone-info hidden" ></i></a></div></li>');
        
        var objget=$('li.dd-item:last').prev();
        var m_date = objget.find('.milestones-date').val();
        var objset=$('li.dd-item:last');
        
        /*$('.milestones-date').datepicker({
            todayHighlight: 'TRUE',
            startDate: '-0d',
            autoclose: true,
            format:"D, d M yyyy",
        });*/
        $('.milestones-date').datepicker({autoclose:true, dateFormat:"D, d M yy"});

    });
    /* End: Add milestone on change date */

    /* Start: add datepicker on mileston date */
    /*$('.milestones-date').datepicker({
        todayHighlight: 'TRUE',
        startDate: '-0d',
        autoclose: true,
        format:"D, d M yyyy",
    });*/
     $('.milestones-date').datepicker({autoclose:true, dateFormat:"D, d M yy"});
    /* End: add datepicker on mileston date */

    /* Start: mileastone date save*/
    $('body').on('change','.milestones-date', function(e){
        e.stopImmediatePropagation();
        var send = true,
            isSend = true;
        if (typeof(event) == 'object')
            if (event.timeStamp - lastJQueryTS < 300){
                send = false;
            lastJQueryTS = event.timeStamp;
        }
        if(send){
            var obj = $(this), 
                ddi = obj.closest('.dd-item'), 
                mValue = ddi.find('.milestones-name').val(), 
                mDateValue = dateStringToDbDate(ddi.find('.milestones-date').val()), 
                milestonesId = ddi.find('.save-milestone-info').data('milestones-id');
                /*clientId = $('input[name="goalClientId"]').val();*/

            ddi.find('.milestones-name').attr('disabled', true);
            ddi.find('.milestones-date').attr('disabled', true);
            ddi.find('.save-milestone-info').hide();
            ddi.find('.edit-milestone-info').show();
            
            clearTimeout(timeoutReference);
            timeoutReference = setTimeout(function(){
                if(isSend){
                    isSend = false;
                    $.ajax({
                        url: public_url+'goal-buddy/updatemilestones',
                        type: 'POST',
                        data: {'milestonesId':milestonesId,'mValue':mValue,'mDateValue':mDateValue,'goalId':$('#last-insert-id').val()},
                        success: function(response) {
                            var data = JSON.parse(response);
                            if(data.status == 'true'){
                                if(data.id > 0)
                                    ddi.data('milestones-id', data.id);
                                showMIlestoneDd();
                            }
                        }
                    });
                }
            }, 500);
        }
        $('#Milestones').val('');
    });
    /* End: */

    /* Start: Delete mileston */
    $('body').on( 'click', '.delete-milestone-info', function() {
        var currentRow = $(this).closest('.dd-item');
        var milestonesId = currentRow.data('milestones-id');
        if(typeof milestonesId != 'undefined' && milestonesId != ''){
            deleteWithCallbackConfirmMsg('Milestone', function(confirm){
                if(confirm){
                    $.ajax({
                        url: public_url+'goal-buddy/deletemilestones',
                        type: 'POST',
                        data: {'eventId':milestonesId},
                        success: function(response) {
                          var data = JSON.parse(response);
                            if(data.status == 'true'){
                                $(currentRow).remove();
                                showMIlestoneDd();
                                createHabitDd(data.habitData);
                                showHabitList(data.habitData);
                                showTaskList(data.taskData);
                            }  
                        }
                    });
                }
            });   
        }
        else{
           $(currentRow).remove(); 
        }
    });
    /* End: Delete mileston */

    /* Start: Edit milestone */
    $('body').on('click', '.edit-milestone-info', function(e) {
        e.preventDefault();
        mileStoneSave = true;

        var ddi = $(this).closest('.dd-item'), 
            inp = ddi.find('.milestones-name');

        inp.focus();
        inp.removeAttr('disabled');
        ddi.find('.milestones-date').removeAttr('disabled');
        ddi.find('.dd-handle').css("padding","0px");
        $(this).hide();
    });
    /* End: Edit milestone */

    /* Start: Submit step 1 data */
    $('.submit-first-form').click(function() {
        var form = $('#edit_goal_form'),
            isFormValid = form.valid();

        form.find('.sucMes').addClass('hidden').empty();
        if(isFormValid){
            var formdata = {};
            var goalFromCal = $('#goal-from-cal').val();

            formdata['name'] = $('#name_goal').val();
            formdata['describe_achieve'] = $("#describe_achieve").val();
            formdata['goal_year'] = $("input[name=goal_year]").val();
            formdata['change_life'] = prepareMultiSel($("select#change_life"));
            formdata['accomplish'] = $('#accomplish').val();
            formdata['failDescription'] = $('#fail-description').val();
            formdata['gb_relevant_goal'] = $('#gb_relevant_goal').val();
            formdata['gb_relevant_goal_event'] = $('#gb_relevant_goal_event').val();
            formdata['image'] = $('input[name="prePhotoName"]').val();
            formdata['template'] = $('#goal-template').val();
            formdata['goal_seen'] = $("input[name=goal_seen]:checked").val();
            formdata['send_msg_type'] = $("input[name=send_msgss]:checked").val();
            formdata['due_date'] =  dateStringToDbDate($('#datepicker_SYG').val());
            formdata['form_no'] = 1;
            formdata['update_status'] = 'update-yes';
            formdata['last_insert_id'] = $('#last-insert-id').val();
            formdata['ClientId'] = $('input[name="goalClientId"]').val();
            ifSendAjax = true;
            savegoal(formdata, function(status){
                if(status == 'success'){
                    saveSuccess(form, {action:'show', type:'update'});
                    if(goalFromCal != undefined && goalFromCal == 'goalfromcal'){
                        setTimeout(function(){ window.location.href = document.referrer+'#panel_assess_progress'; }, 1000);
                    } 
                }
            });
        }
        scrollToTop(form);
    });
    /* End: Submit step 1 data */

    /* Start: Submit step 2 data */
    $('#create_milestone_btn').click(function() {
        initCustomValidator();
        var form = $('#build_new_milestone_form'),
            isFormValid = form.valid(),
            allMilestonesNames = [],
            allMilestonesNamesId = [],
            allMilestonesDates = [],
            formdata = {};

        form.find('.sucMes').addClass('hidden').empty();

        if(isFormValid){   
            var milestoneFromList = $("input[name=updatemilestonefromlist]").val(); 
            var milestonesDueDate = $('input[name="gb_milestones_date"]').val();

            formdata['milestones_id'] = $('#milestones_id').val();
            $('.milestones-name').each(function() {
                var milestonesNames = $(this).val() ;
                var milestonesId = $(this).closest('.dd-item').data('milestones-id');
                   
                if(milestonesNames != ''){
                    if(milestonesId != '')
                        allMilestonesNamesId.push(milestonesId +':'+ milestonesNames);  
                    else  
                        allMilestonesNames.push(milestonesNames); 
                }     
            });

            $('.milestones-date').each(function() {
                if($(this).val() != ''){
                    allMilestonesDates.push($(this).val());}
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

            formdata['gb_milestones_seen'] = $("input[name=gb_milestones_seen]:checked").val();
            formdata['gb_milestones_reminder'] = $("input[name=gb_milestones_reminder]:checked").val();
            formdata['last_insert_id'] = $('#last-insert-id').val();
            formdata['goal_id_mile']=$('#goal_milestones_id').val();
            formdata['ClientId'] = $('input[name="goalClientId"]').val();
            formdata['form_no'] = 2;
            ifSendAjax = true;  
            savegoal(formdata, function(status){
                if(status == 'success'){
                    saveSuccess(form, {action:'show', type:'update'});
                    if(milestoneFromList !=undefined && milestoneFromList == 'milestonefromlist'){
                       saveSuccess(form, {action:'show', type:'update'});
                       setTimeout(function(){ window.location.href = document.referrer; }, 3000);
                    }
                }
            });
        }
        return false;
    });
    /* End: Submit Step 2 data */

    /* Start: Submit step 3 data */
    $('#create_habbit_btn').click(function() {
        initCustomValidator();
        var form = $('#build_new_habit_form'),
            isFormValid = form.valid(),
            formdata = {};

        if(isFormValid){  
            var habitFromList = $("input[name=updatehabitfromlist]").val();

                formdata['habit_id'] = $('#habit-id').val();
                formdata['habit_name'] = $('#SYG_habits').val();
                formdata['habit_recurrence'] = $("input[name=SYG_habit_recurrence]:checked").val();
            
                if(formdata['habit_recurrence'] == 'weekly'){
                    var selectedWeeks = [];
                    $('input[type="checkbox"]:checked.goalEventRepeatWeekdays').each(function() {
                        selectedWeeks.push($(this).val());
                    });
                    formdata['habit_weeks'] = selectedWeeks;
                } 
                else if(formdata['habit_recurrence'] == 'monthly'){
                    formdata['month'] = $(".month-date option:selected").text();
                }

                formdata['habit_notes'] = $('#SYG_notes').val();
                formdata['habit_milestone']= prepareMultiSel($('#build_new_habit_form').find('select[name="milestone_value"]'));
                formdata['habit_seen'] = $("input[name=syg2_see_habit]").val();
                formdata['habit_reminders'] = $("input[name=syg2_send_msg]").val();
                formdata['last_insert_id'] = $('#last-insert-id').val();
                formdata['ClientId'] = $('input[name="goalClientId"]').val();
                formdata['form_no'] = 3;
                ifSendAjax = true;
                console.log(formdata);
                savegoal(formdata, function(status){
                    if(status == 'success'){
                        if(habitFromList !=undefined && habitFromList == 'habitfromlist'){
                           saveSuccess($('#build_new_habit_form'), {action:'show', type:'update'});
                           setTimeout(function(){ window.location.href = document.referrer; }, 3000);
                        }
                        else{
                            $('.habit-form').hide();
                            $('.habit-listing').show();
                            $('.cancel_habbit_btn').addClass('hidden');
                        }
                    }
                });
               
        }
        return false; 
    });
    /* End: Submit Step 3 data */
    /*$('#create_habbit_btn').click(function() {
        if ($('[name=habit_recurrence]:checked').length) {
            $('.error_radio').hide();
        } 
        else {
            $('.error_radio').show();
            return false;
        }
    });*/

    /* Start: Submit step 4 data */
    $('#manage_task_btn').click(function() {
        initCustomValidator();
        var form = $('#manage_task_form'),
            isFormValid = form.valid(),
            formdata = {};

        if (isFormValid) {
            var taskFromCal= $('#task-from-cal').val();

            formdata['task_id'] = $('#task-id').val();
            formdata['task_name'] = $('#SYG3_task').val();
            formdata['note'] = $('#note').val();
            formdata['task_priority'] = $("#SYG3_priority").val();
            formdata['task_habit_id'] = $('select[name="habit_value"]').val();
            formdata['task_seen'] = $("input[name=SYG3_see_task]:checked").val();
            formdata['task_reminders'] = $("input[name=SYG3_send_msg]:checked").val();
            formdata['task_recurrence'] = $("input[name=SYG_task_recurrence]:checked").val();
        
            if(formdata['task_recurrence'] == 'weekly'){
                var selectedWeeks = [];
                $('input[type="checkbox"]:checked.taskEventRepeatWeekdays').each(function() {
                    selectedWeeks.push($(this).val());
                });
                formdata['task_weeks'] = selectedWeeks;
            }
            else if(formdata['task_recurrence'] == 'monthly'){
                formdata['month'] = $(".month-date-task option:selected").text();
            }
            
            formdata['form_no'] = 4;
            formdata['last_insert_id'] = $('#last-insert-id').val();
            formdata['ClientId'] = $('input[name="goalClientId"]').val();
            ifSendAjax = true;

            savegoal(formdata, function(status){
                if(status == 'success'){
                    if(taskFromCal != undefined && taskFromCal == 'taskfromcal'){
                       saveSuccess(form, {action:'show', type:'update'});
                       setTimeout(function(){ window.location.href = document.referrer; }, 2000);
                    }
                    else{
                        $('.task-form').hide();
                        $('.task-listing').show();
                        $('.cancel_task_btn').addClass('hidden');
                    }
                }
            });
        }
        return false;
    });
    /* End: Submit step 4 data */

    /* Start: Goal buddy delete */
    $('.delete-goal').click(function() {
        var processbarDiv = $(this).closest('tr');
        var goalId = processbarDiv.find('#goal-id').val();
        var entity = $(this).data('entity');
        var eventUrl ='goal-buddy/deletegoal';
        var actionType='popup';
        if(goalConfirmDelete(goalId,entity,eventUrl,actionType, processbarDiv)){
           deleteGoal(goalId); 
           processbarDiv.remove();
        }
    });
    /* End: Goal buddy delete */

    /* Start: Jump on step */
    $('.Step-your-goal1').click(function(e){ 
        /*e.preventDefault();*/ 
        $('#goalBuddyWizard').smartWizard('goToStep', 1);
        return false;
    });

    $('.Step-your-goal2').click(function(e){ 
        e.preventDefault();
        $('#goalBuddyWizard').smartWizard('goToStep', 2);
        return false;
    });

    $('.Step-your-goal3').click(function(e){
        /*e.preventDefault();*/
        $('#goalBuddyWizard').smartWizard('goToStep', 3);
        return false;
    })

    $('.Step-your-goal4').click(function(e){
        /*e.preventDefault();*/
        $('#goalBuddyWizard').smartWizard('goToStep', 4);
        return false;
    });
    /* End: Jump on step */









    $('.compleate-goal').change(function() {
        var checked = $(this).is(":checked");
        var processbarDiv = $(this).closest('tr');
        var processbarTd = processbarDiv.find('td .progress');
        var goalId = processbarDiv.find('#goal-id').val();
        var processbarPercentage = processbarDiv.find('td .progress-percentage');
        if(checked){
            processbarTd.addClass('progress-bar-success');
            processbarPercentage.text('100%');
            updateStatus(goalId,1);
        } else {
            processbarTd.removeClass('progress-bar-success');
            processbarPercentage.text('0%');
            updateStatus(goalId,0);
        } 
    });

    $('.goal-name').click(function() {
        var currentTr = $(this).closest('tr');
        var currentMilestonesId = currentTr.find('.milestones').attr('id');
        var currentHabitsId = currentTr.find('.habits').attr('id');
        $('#'+currentMilestonesId).toggle();
        $('#'+currentMilestonesId).removeClass('hide');
        $('#'+currentHabitsId).toggle();
        $('#'+currentHabitsId).removeClass('hide');
    });


    $('input[name=habit_recurrence]').click(function() {
        if($(this).is(':checked')){
            $('.error_radio').hide();
        }
    })
    $('#custom_message').click(function(){
        $('#toggle_msg').toggle();
    });


    $('.reset_btn').click(function() {
        $('#search_form')[0].reset();
    });








    $(document).on( 'click', '.habit-day-div', function() {
        var checked = $(this).is(":checked");
        var eventId = $(this).attr('data');
        var actionStatus;
        
          if(checked) actionStatus = 1;
            else actionStatus = 0;
            
            $.ajax({
                url: public_url+'manage-status',
                method: "POST",
                data: {'actionStatus':actionStatus,'eventId':eventId},
                success: function(data){
                    var data = JSON.parse(data);
                    if(data.status == "true"){
                        if(checked){
                          $("input[id="+eventId+"]").prop('checked', true);
                          $("input[id="+data.updateHabitId+"]").prop('checked', true);
                        }
                        else{
                            $("input[id="+eventId+"]").prop('checked', false);
                          $("input[id="+data.updateHabitId+"]").prop('checked', false);
                        }
                    }
                }
            }); 
       
    });
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i].split('=');
        if(c[0].replace(/\s+/g, '')=="goalid"){
            goalid = c[1].replace(/\s+/g, '');
        }
    }
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i].split('=');
    }


    $('input[name="goal_hide"]').on('click',function(){
        if($(this).prop("checked")==true){
            $('tr.completed').hide('fast');
        } else {
            $('tr.completed').show('fast');
        }
    });

    $( document ).on( 'click', '.template-value', function() {
        $('#goal-template').val($(this).text());
        $('#demo10').modal('toggle');
    });
   
    



        $(document).on( 'click', '.listing-task-name', function() {

        var taskId =$(this).attr('data');
        var toDay=moment().format("YYYY-MM-DD");
        $.ajax({
                url: public_url+'goal-buddy/get-listing-task',
                type: 'POST',
                data: {'taskId':taskId},
                success: function(data){
                    var data = JSON.parse(data);
                    var taskdayHtml ='';
                    if(data.status == 'true'){
                    $(".popup-edit-task").data('task-id',data.taskdata[0].taskId);
                    $(".popup-delete-task").data("task-id",data.taskdata[0].taskId);
                    $('.task-name').text(data.taskdata[0].taskName);
                    $('.priority').text(data.taskdata[0].priority);
                    $('.shared').text(ucfirst(data.taskdata[0].taskSeen));
                    $('.frequency').text(ucfirst(data.taskdata[0].taskRecurrenceType));
                    $('.goal-with-habit').text(data.taskdata[0].taskHabit);
                    //$('.due-date').text(data.taskdata.gb_task_due_date); 

                if(data.taskupdateData.length >0){
                   $.each(data.taskupdateData, function (index, value) {

                    var dueTaskDate = moment(value.due_date).format("ddd, D MMM YYYY");


                  taskdayHtml +=  '<div class="checkbox clip-check check-primary"><input type="checkbox" name="goal_hide" id="popuptask_'+value.id+'" value="1" class="habit-day-div" data ="'+value.id+'" data-type ="'+data.taskdata[0].taskRecurrenceType+'"';
                  if(value.status==1){
                       taskdayHtml +='checked=""';
                    } 
                   

                  taskdayHtml += '><label for="popuptask_'+value.id+'">'+dueTaskDate+'</label></div>';
                 

                  /*taskdayHtml +='<div class="checkbox clip-check check-primary"><input type="checkbox" name="goal_hide" id="popuptask_'+value.id+'" value="1" class="habit-day-div" data ="'+value.id+'"';

                  if(value.status==1)
                   taskdayHtml +='checked';
                   taskdayHtml +='><label for="popuptask_'+value.id+'">'+data.taskdata.gb_task_name+'</label></div>';*/
                        });
                    }
 
                  $('.unchecked-days-task').html(taskdayHtml);
                     }
                }
       
       
       });

    });

     $(document).on( 'click', '.listing-milestone-name', function() {
        var milestoneId =$(this).attr('data');

         $.ajax({
                url: public_url+'goal-buddy/get-listing-milestone',
                type: 'POST',
                data: {'milestoneId':milestoneId},
                success: function(data){
                    var data = JSON.parse(data);
                    //console.log(data);
                    var milestonedayHtml ='';
                    if(data.status == 'true'){
                        var duemilestoneDate = moment(data.milestones.gb_milestones_date).format("ddd, D MMM YYYY");
                    $(".popup-edit-milestone").data('milestone-id',data.milestones.id);
                    $(".popup-delete-milestone").data("milestone-id",data.milestones.id);
                    $('.milestone-name').text(data.milestones.gb_milestones_name);
                    $('.shared').text(data.milestones.gb_milestones_seen); 
                    $('.due-date').text(duemilestoneDate); 

                    if(data.mileupdateData.length >0){
                   $.each(data.mileupdateData, function (index, value) {

                    milestonedayHtml +='<div class="checkbox clip-check check-primary"><input type="checkbox" name="goal_hide" id="popupmile_'+value.id+'" value="1" class="habit-day-div" data ="'+value.id+'"';

                  if(value.status==1)
                   milestonedayHtml +='checked';
                   milestonedayHtml +='><label for="popupmile_'+value.id+'">'+data.milestones.gb_milestones_name+'</label></div>';
                    });
                    }
                    $('.unchecked-days-mileston').html(milestonedayHtml);
                }
            }
       
       
       });

     });   

    $(document).on( 'click', '.listing-goal-name', function() {

        var goalId =$(this).attr('data');

        $.ajax({
                url: public_url+'goal-buddy/get-listing-goal',
                type: 'POST',
                data: {'goalId':goalId},
                success: function(data){
                    var data = JSON.parse(data);
                    //console.log(data);
                    //var taskdayHtml ='';
                    if(data.status == 'true'){
                    $(".popup-edit-goal").data('goal-id',data.goaldata.id);
                    $(".popup-delete-goal").data("goal-id",data.goaldata.id);
                    $('.goal-name').text(data.goaldata.gb_goal_name);
                    $('.accomplish').text(data.goaldata.gb_achieve_description);
                    //$('.lifechange').text(data.goaldata.gb_change_life_reason); 
                    $('.goal-important').text(data.goaldata.gb_fail_description); 
                    $('.shared').text(data.goaldata.gb_goal_seen); 
                    $('.due-date').text(data.goaldata.gb_due_date); 

                
                     }
                }
       
       
       });

    });


    /// edit goal 
    $(document).on( 'click', '.popup-edit-goal', function(e) {
        e.preventDefault();
        var goalId = $(this).data('goal-id');
        window.location.href = public_url+'goal-buddy/editgoal/'+goalId;
    });  
    $( document ).on( 'click', '.popup-delete-goal', function() {
      var goalId = $(this).data('goal-id');
      var entity = $(this).data('entity');
      var eventUrl ='goal-buddy/deletegoal';
      var actionType='popup';
       goalConfirmDelete(goalId,entity,eventUrl,actionType)

     /* swal({
        title: "Are you sure to delete this goal ?",
        text: (typeof warningText != 'undefined' && warningText)?warningText:'',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
    }, 
    function(){
        $(document).on( 'click', '.confirm', function(e) {

       $.ajax({
            url: public_url+'goals/deletegoal',
            type: 'POST',
            data: {'goal_id':goalId},
            success: function(response) {
              var data = JSON.parse(response);
                if(data.status == 'true'){
                    location.reload();
              }  
            }
    });
    });
    }); */  

}); 
/// edit task 
    $(document).on( 'click', '.popup-edit-task', function(e) {
        e.preventDefault();
        var taskId = $(this).data('task-id');
        
        window.location.href = public_url+'goal-buddy/edittask/'+taskId;
    }); 

 /// edit milestone 
    $(document).on( 'click', '.popup-edit-milestone', function(e) {
        e.preventDefault();
        var milestoneId = $(this).data('milestone-id');
        
        window.location.href = public_url+'goal-buddy/editmilestone/'+milestoneId;
    }); 
   
    
    $( document ).on( 'click', '.popup-delete-task', function() {
     var milestoneId = $(this).data('milestone-id');

       var entity = $this.data('entity');
       var eventUrl='goal-buddy/deletetask';
       var actionType='popup';
        //confirmDelete($this);
        goalConfirmDelete(milestoneId,entity,eventUrl,actionType)

       
   });

    $( document ).on( 'click', '.popup-delete-milestone', function() {
     var milestoneId = $(this).data('milestone-id');

       var entity = $this.data('entity');
       var eventUrl='goal-buddy/deletemilestones';
       var actionType='popup';
        //confirmDelete($this);
        goalConfirmDelete(milestoneId,entity,eventUrl,actionType)

       
   });  

$( document ).on( 'click', '.milestone-edit', function() {
   
        var processbarDiv = $(this).closest('tr');
        var milestoneId = $(this).data('milestone-id');
         $('.milestone-form').show();
         $('.milestone-listing').hide();
         $.ajax({
            url: public_url+'goal-buddy/showmilestone',
            type: 'POST',
            data: {'milestoneId':milestoneId},
            success: function(response) {
              var data = JSON.parse(response);
              $('.cancel_milestone_btn').removeClass('hidden');
                if(data.status == 'true'){
                  
                    $('#milestones_id').val(data.milestones.id);
                    $('#gb_milestones_name').val(data.milestones.gb_milestones_name);
                   
                    if(data.milestones.gb_milestones_date!='')
                    $('#gb_milestones_date').val(moment(data.milestones.gb_milestones_date).format("ddd, D MMM YYYY"));
                   
                   
                    if(data.milestones.gb_milestones_seen =='everyone')
                     $('#SYG3_see_milestones0').prop('checked', true);
                    else if(data.milestones.gb_milestones_seen =='milestones-friends')
                     $('#SYG3_see_milestones1').prop('checked', true);
                     else if(data.milestones.gb_milestones_seen =='Just Me')
                     $('#SYG3_see_milestones2').prop('checked', true);


                    if(data.milestones.gb_milestones_reminder =='when_overdue')
                     $('#send_msgss_milestones1').prop('checked', true);
                    else if(data.milestones.gb_milestones_reminder =='daily')
                     $('#send_msgss_milestones2').prop('checked', true);
                    else if(data.milestones.gb_milestones_reminder =='weekly')
                     $('#send_msgss_milestones3').prop('checked', true);
                    else if(data.milestones.gb_milestones_reminder =='monthly')
                     $('#send_msgss_milestones4').prop('checked', true);
                    else if(data.milestones.gb_milestones_reminder =='none')
                     $('#send_msgss_milestones5').prop('checked', true);



                   
                    
              }  
            }
    });

    });

$( document ).on( 'click', '.delete-milestone', function() {
        var processbarDiv = $(this).closest('tr');
        var milestoneId = $(this).data('milestone-id');
       
        processbarDiv.remove();
         $.ajax({
            url: public_url+'goal-buddy/deletemilestone',
            type: 'POST',
            data: {'milestoneId':milestoneId},
            success: function(response) {
              var data = JSON.parse(response);
                if(data.status == 'true'){
                    //location.reload();
              }  
            }
    });
  });  


    /// edit habit from goal listing
    $(document).on( 'click', '.edit-habit', function(e) {
        e.preventDefault();
        var habitId = $(this).data('edithabit');
        window.location.href = public_url+'goal-buddy/edithabit/'+habitId;
    });

    $( document ).on( 'click', '.delete-habit-list', function(e) {
        e.preventDefault();
        var habitId = $(this).data('deletehabitid');
         var entity = $(this).data('entity');
      var actionType='popup';
      var eventUrl='goal-buddy/deletehabit';
        //confirmDelete($this);
        goalConfirmDelete(habitId,entity,eventUrl,actionType)
    // goalConfirmDelete(habitId,entity,eventUrl)
         /*swal({
        title: "Are you sure to delete this habit ?",
        text: (typeof warningText != 'undefined' && warningText)?warningText:'',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
    }, 
    function(){
        $(document).on( 'click', '.confirm', function(e) {
         $.ajax({
            url: public_url+'goals/deletehabit',
            type: 'POST',
            data: {'habitId':habitId},
            success: function(response) {
              var data = JSON.parse(response);
                if(data.status == 'true'){
                    location.reload();
              }  
            }
    });
         });
        });*/
  });
$( document ).on( 'click', '.milestone-goal', function(e) {
        var checked = $(this).is(":checked");
        var processbarDiv = $(this).closest('tr');
        
        var processbarTd = processbarDiv.find('td .progress');
        var milestonesId = $(this).data('milestones-id');
        var percentage = $(this).data('percentage');
        var processbarPercentage = processbarDiv.find('td .progress-percentage');
        var processbarPercentageStyle = processbarDiv.find('td .progress-bar');
        var count = $(this).closest('tr').find("input[type=checkbox]:checked").size();
         var totalpersent=count*percentage;
        if(checked ==true){
            //processbarTd.addClass('progress-bar-success');
            processbarPercentageStyle.attr('style', 'width:'+totalpersent+'%');
            processbarPercentage.text(totalpersent.toFixed(2)+'%');
            updateMilestones(milestonesId,1);
        } else {
            processbarPercentageStyle.attr('style', 'width:'+totalpersent+'%');
            processbarTd.removeClass('progress-bar-success');
            if(totalpersent=='')
            processbarPercentage.text('0%');
           else
            processbarPercentage.text(totalpersent+'%');
            updateMilestones(milestonesId,0);
        }
        
    });

    $(document).ready(function(){
    initCustomValidator();


}); 
$( document ).on( 'click', '.delete-habit', function() {
        var processbarDiv = $(this).closest('tr');
        var habitId = $(this).data('habit-id');
        
        var entity = $(this).data('entity');
       

           swal({
        title: "Are you sure to delete this "+entity+"?",
        text: (typeof warningText != 'undefined' && warningText)?warningText:'',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
    }, 
    function(){
        $(document).on( 'click', '.confirm', function(e) {
       // var habitData =goalConfirmDelete(habitId,entity,eventUrl);
        
         $.ajax({
            url: public_url+'goal-buddy/deletehabit',
            type: 'POST',
            data: {'eventId':habitId},
            success: function(response) {
              var data = JSON.parse(response);
                if(data.status == 'true'){
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
$( document ).on( 'click', '.habit-edit', function() {

        var processbarDiv = $(this).closest('tr');
        var habitId = $(this).data('habit-id');
        var isJump = "false";

        //alert(habitId);
         $('.habit-form').show();
         $('.habit-listing').hide();
         $.ajax({
            url: public_url+'goal-buddy/showhabit',
            type: 'POST',
            data: {'habitId':habitId},
            success: function(response) {
              var data = JSON.parse(response);
                if(data.status == 'true'){
                    //console.log(data.allMilestoneArrayId);
                     $('.cancel_habbit_btn').removeClass('hidden');
                 
                      if(data.milestonesTask != ''){
                        showMIlestoneDd(data.goalBuddy.gb_milestones_id.split(','));
                      
                    }else{
                        //alert('2');
                     $('.milestone-dropdown').hide();   
                    }
                    //alert(data.goalBuddy.id);
                    //console.log(data.goalBuddy.id);
                    $('#habit-id').val(data.goalBuddy.id);
                    //$('#habit-id').val(data.goalBuddy[0].id);
                    $('#SYG_habits').val(data.goalBuddy.gb_habit_name);

                    if(data.goalBuddy.gb_habit_recurrence_type =='daily'){
                     $('#SYG_habit_recurrence0').prop('checked', true);
                     $('.show-weeks').hide();
                     $('.month-count').hide();
                    }
                    else if(data.goalBuddy.gb_habit_recurrence_type =='weekly'){
                       // console.log(data.goalBuddy.gb_habit_recurrence_week);
                        var weekData = data.goalBuddy.gb_habit_recurrence_week;
                        var weekarray = weekData.split(',');
                            for (var i = 0; i < weekarray.length; i++) {

                             if(weekarray[i] =='Monday')
                               $('#goalEventRepeatWeekdays0').prop('checked', true);
                             else if(weekarray[i] =='Tuesday')  
                                $('#goalEventRepeatWeekdays1').prop('checked', true);
                             else if(weekarray[i] =='Wednesday')  
                                $('#goalEventRepeatWeekdays2').prop('checked', true); 
                             else if(weekarray[i] =='Thursday')  
                                $('#goalEventRepeatWeekdays3').prop('checked', true); 
                             else if(weekarray[i] =='Friday')  
                                $('#goalEventRepeatWeekdays4').prop('checked', true); 
                             else if(weekarray[i] =='Saturday')  
                                $('#goalEventRepeatWeekdays5').prop('checked', true); 
                             else if(weekarray[i] =='Sunday')  
                                $('#goalEventRepeatWeekdays6').prop('checked', true);      
                           
                            }
                       // console.log(data.goalBuddy.gb_habit_recurrence_week);
                     $('#SYG_habit_recurrence1').prop('checked', true);
                     
                     $('.show-weeks').show();
                     $('.month-count').hide();
       
                     }
                     else if(data.goalBuddy.gb_habit_recurrence_type =='monthly'){
                     $('#SYG_habit_recurrence2').prop('checked', true);
                     //console.log(data.goalBuddy.gb_habit_recurrence_month);
                     var monthDate= retnum(data.goalBuddy.gb_habit_recurrence_month);

                    $('.month-count').show();
                    $('.show-weeks').hide();
                    var totalDayInMonth=getDaysInMonth();
                    var selector = "";
                    $('.month-count .month-count-div').remove();
                    selector += "<div class ='month-count-div'>Day <select class='month-date'>";
                    for (var i = 1; i <= totalDayInMonth; i++) {

                        if(i==monthDate){
                           selector += "<option value ="+i+" selected>"+i+"</option>";
                        }else{
                        selector += "<option value ="+i+">"+i+"</option>";
                    } 
                    }
                    selector +="</select> of every month</div>";
                    $('.month-count').append(selector);
                    selector = '';


                     
                 }


                    //$("input[name=SYG_habit_recurrence]:checked").val(data.goalBuddy.gb_habit_recurrence_type);
                    $('#SYG_notes').val(data.goalBuddy.gb_habit_notes);
                    //$(".milestone_div_class option:selected").val(data.goalBuddy.gb_milestones_name);
                    //$('.milestone_div_class').selectpicker('refresh');
              
                    if(data.goalBuddy.gb_habit_seen =='everyone')
                     $('#syg2_see_habit0').prop('checked', true);
                    else if(data.goalBuddy.gb_habit_seen =='habit-friends')
                     $('#syg2_see_habit1').prop('checked', true);
                     else if(data.goalBuddy.gb_habit_seen =='Just Me')
                     $('#syg2_see_habit2').prop('checked', true);


                    if(data.goalBuddy.gb_habit_reminder =='only_if_I_am_late')
                     $('#syg2_send_msg0').prop('checked', true);
                    else if(data.goalBuddy.gb_habit_reminder =='Every_occurrence')
                     $('#syg2_send_msg1').prop('checked', true);
                     else if(data.goalBuddy.gb_habit_reminder =='none')
                     $('#syg2_send_msg2').prop('checked', true);

                    $('#last-insert-id').val(data.goalBuddy.goal_id);
                    $('#SYG_notes').val(data.goalBuddy.gb_habit_notes);
                   
                    //location.reload();
              }  
            }
    });

    });
$( document ).on( 'click', '#SYG_habit_recurrence0', function() {
     $('.show-weeks').hide();
      $('.month-count').hide();
     });
$( document ).on( 'click', '#SYG_habit_recurrence1', function() {
     $('.show-weeks').show();
     $('.month-count').hide();
     });
$( document ).on( 'click', '#SYG_habit_recurrence2', function() {
    $('.month-count').show();
    $('.show-weeks').hide();
    var totalDayInMonth=getDaysInMonth();
    var selector = "";

    $('.month-count .month-count-div').remove();
    selector += "<div class ='month-count-div'>Day <select class='month-date'>";
    for (var i = 1; i <= totalDayInMonth; i++) {
        selector += "<option value ="+i+">"+i+"</option>"; 
    }
    selector +="</select> of every month</div>";
    $('.month-count').append(selector);
    selector = '';
     });

$( document ).on( 'click', '#syg2_see_habit1', function() {
      $(".habit-friends-section").show();
     });

$( document ).on( 'click', '#syg2_see_habit0', function() {
      $(".habit-friends-section").hide();
     });
$( document ).on( 'click', '#syg2_see_habit2', function() {
      $(".habit-friends-section").hide();
     });



$( document ).on( 'click', '.task-edit', function() {
   // alert('ok');
        var processbarDiv = $(this).closest('tr');
        var taskId = $(this).data('task-id');
        //alert(habitId);
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

                            // if($.inArray(value.id,data.goalBuddy.gb_habit_id) ){
                            if(value.id==data.goalBuddy.gb_habit_id){
                                 optionValue += '<option value="'+value.id+'" selected>'+value.gb_habit_name+'</option>';
                            }else{
                                 optionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';}
                            
                        });
                      optionValue += '</select>';
                      

                      $('.task-habit-dropdown').html(optionValue);
                      initSelectpicker($('.task-habit-dropdown select'));
                      
                      $('.taskhabit_div_class').selectpicker('refresh');
                      optionValue = '';
                    }else{
                        
                     $('.task-habit-div').hide();   
                    }
                    

                    $('#task-id').val(data.goalBuddy.id);
                    $('#SYG3_task').val(data.goalBuddy.gb_task_name);
                    $('#note').val(data.goalBuddy.gb_task_note);

                    $("#SYG3_priority").val(data.goalBuddy.gb_task_priority);
                    $("#SYG3_priority").selectpicker('refresh');
                    /*if(data.goalBuddy.gb_task_due_date!='')
                    $('#datepicker_SYG3').val(moment(data.goalBuddy.gb_task_due_date).format("ddd, D MMM YYYY"));*/
                    $('#SYG3-time').val(data.goalBuddy.gb_task_time);
                   
                    if(data.goalBuddy.gb_task_seen =='everyone')
                     $('#SYG3_see_task0').prop('checked', true);
                    else if(data.goalBuddy.gb_task_seen =='task-friends')
                     $('#SYG3_see_task1').prop('checked', true);
                     else if(data.goalBuddy.gb_task_seen =='Just Me')
                     $('#SYG3_see_task2').prop('checked', true);


                    if(data.goalBuddy.gb_task_reminder =='When_task_is_overdue')
                     $('#SYG3_send_msg0').prop('checked', true);
                    else if(data.goalBuddy.gb_task_reminder =='When_task_is_due')
                     $('#SYG3_send_msg1').prop('checked', true);
                     else if(data.goalBuddy.gb_task_reminder =='none')
                     $('#SYG3_send_msg2').prop('checked', true);


                if(data.goalBuddy.gb_task_recurrence_type =='daily'){
                     $('#SYG_task_recurrence0').prop('checked', true);
                     $('.show-weeks-task').hide();
                     $('.month-count-task').hide();
                }else if(data.goalBuddy.gb_task_recurrence_type =='weekly'){
                        var weekData = data.goalBuddy.gb_task_recurrence_week;
                        var weekarray = weekData.split(',');
                            for (var i = 0; i < weekarray.length; i++) {

                             if(weekarray[i] =='Monday')
                               $('#taskEventRepeatWeekdays0').prop('checked', true);
                             else if(weekarray[i] =='Tuesday')  
                                $('#taskEventRepeatWeekdays1').prop('checked', true);
                             else if(weekarray[i] =='Wednesday')  
                                $('#taskEventRepeatWeekdays2').prop('checked', true); 
                             else if(weekarray[i] =='Thursday')  
                                $('#taskEventRepeatWeekdays3').prop('checked', true); 
                             else if(weekarray[i] =='Friday')  
                                $('#taskEventRepeatWeekdays4').prop('checked', true); 
                             else if(weekarray[i] =='Saturday')  
                                $('#taskEventRepeatWeekdays5').prop('checked', true); 
                             else if(weekarray[i] =='Sunday')  
                                $('#taskEventRepeatWeekdays6').prop('checked', true);      
                           
                            }
                      
                     $('#SYG_task_recurrence1').prop('checked', true);
                     $('.show-weeks-task').show();
                     $('.month-count-task').hide();
       
                }
                     else if(data.goalBuddy.gb_task_recurrence_type =='monthly'){
                     $('#SYG_task_recurrence2').prop('checked', true);
                     var monthDate= retnum(data.goalBuddy.gb_task_recurrence_month);

                    $('.month-count-task').show();
                    $('.show-weeks-task').hide();
                    var totalDayInMonth=getDaysInMonth();
                    var selector = "";
                    $('.month-count-task .month-count-task-div').remove();
                    selector += "<div class ='month-count-task-div'>Day <select class='month-date'>";
                    for (var i = 1; i <= totalDayInMonth; i++) {

                        if(i==monthDate){
                           selector += "<option value ="+i+" selected>"+i+"</option>";
                        }else{
                        selector += "<option value ="+i+">"+i+"</option>";
                    } 
                    }
                    selector +="</select> of every month</div>";
                    $('.month-count').append(selector);
                    selector = '';


                     
                 }
                   
                    
              }  
            }
    });

    });


$( document ).on( 'change', '#habit_div', function() {

     var habitId = $(this).val();
    
    if(habitId!=''){
        $.ajax({
                url: public_url+'goal-buddy/showhabit',
                type: 'POST',
                data: {'habitId':habitId},
                success: function(response) {
                    var data = JSON.parse(response);

                        if(data.goalBuddy.gb_habit_recurrence_type =='daily'){
                             $('#SYG_task_recurrence0').prop('checked', true);
                             $('.show-weeks-task').hide();
                             $('.month-count-task').hide();
                        }else if(data.goalBuddy.gb_habit_recurrence_type =='weekly'){
                           // console.log(data.goalBuddy.gb_habit_recurrence_week);
                            var weekData = data.goalBuddy.gb_habit_recurrence_week;
                            var weekarray = weekData.split(',');
                                for (var i = 0; i < weekarray.length; i++) {
                                  
                                 if(weekarray[i] =='Monday')
                                   $('#taskEventRepeatWeekdays0').prop('checked', true);
                                 else if(weekarray[i] =='Tuesday')  
                                    $('#taskEventRepeatWeekdays1').prop('checked', true);
                                 else if(weekarray[i] =='Wednesday')  
                                    $('#taskEventRepeatWeekdays2').prop('checked', true); 
                                 else if(weekarray[i] =='Thursday')  
                                    $('#taskEventRepeatWeekdays3').prop('checked', true); 
                                 else if(weekarray[i] =='Friday')  
                                    $('#taskEventRepeatWeekdays4').prop('checked', true); 
                                 else if(weekarray[i] =='Saturday')  
                                    $('#taskEventRepeatWeekdays5').prop('checked', true); 
                                 else if(weekarray[i] =='Sunday')  
                                    $('#taskEventRepeatWeekdays6').prop('checked', true);      
                               
                                }
                           
                         $('#SYG_task_recurrence1').prop('checked', true);
                         
                         $('.show-weeks-task').show();
                         $('.month-count-task').hide();
           
                         }
                         else if(data.goalBuddy.gb_habit_recurrence_type =='monthly'){
                         $('#SYG_task_recurrence2').prop('checked', true);
                         var monthDate= retnum(data.goalBuddy.gb_habit_recurrence_month);

                        $('.month-count-task').show();
                        $('.show-weeks-task').hide();
                        var totalDayInMonth=getDaysInMonth();
                        var selector = "";
                        $('.month-count-task.month-count-task-div').remove();
                        selector += "<div class ='month-count-task-div'>Day <select class='month-date-task'>";
                        for (var i = 1; i <= totalDayInMonth; i++) {

                            if(i==monthDate){
                               selector += "<option value ="+i+" selected>"+i+"</option>";
                            }else{
                            selector += "<option value ="+i+">"+i+"</option>";
                        } 
                        }
                        selector +="</select> of every month</div>";
                        $('.month-count-task').append(selector);
                        selector = '';


                         
                     }
                }
        }); 
    }
 });   

$( document ).on( 'click', '.delete-task', function() {
        var processbarDiv = $(this).closest('tr');
        var taskId = $(this).data('task-id');
       
        
         var entity = $(this).data('entity');
       

           swal({
        title: "Are you sure to delete this "+entity+"?",
        text: (typeof warningText != 'undefined' && warningText)?warningText:'',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
    }, 
    function(){
        $(document).on( 'click', '.confirm', function(e) {
         $.ajax({
            url: public_url+'goal-buddy/deletetask',
            type: 'POST',
            data: {'eventId':taskId},
            success: function(response) {
              var data = JSON.parse(response);
                if(data.status == 'true'){
                    processbarDiv.remove();
                    //location.reload();
              }  
            }
    });
         });
        });
  });

$( document ).on( 'click', '#SYG_task_recurrence0', function() {
      $('.show-weeks-task').hide();
      $('.month-count-task').hide();
     });
$( document ).on( 'click', '#SYG_task_recurrence1', function() {
     $('.show-weeks-task').show();
     $('.month-count-task').hide();
     });
$( document ).on( 'click', '#SYG_task_recurrence2', function() {
    $('.month-count-task').show();
    $('.show-weeks-task').hide();
    var totalDayInMonth=getDaysInMonth();
    var selector = "";

    $('.month-count-task .month-count-task-div').remove();
    selector += "<div class ='month-count-task-div'>Day <select class='month-date-task'>";
    for (var i = 1; i <= totalDayInMonth; i++) {
        selector += "<option value ="+i+">"+i+"</option>"; 
    }
    selector +="</select> of every month</div>";
    $('.month-count-task').append(selector);
    selector = '';
     });


$( document ).on( 'click', '#SYG3_see_milestones1', function() {
      $(".milestones-friends-section").show();
     });
$( document ).on( 'click', '#SYG3_see_milestones0', function() {
      $(".milestones-friends-section").hide();
     });
$( document ).on( 'click', '#SYG3_see_milestones2', function() {
      $(".milestones-friends-section").hide();
     });

$( document ).on( 'click', '#SYG3_see_task1', function() {
      $(".task-friends-section").show();
     });

$( document ).on( 'click', '#SYG3_see_task0', function() {
      $(".task-friends-section").hide();
     });
$( document ).on( 'click', '#SYG3_see_task2', function() {
      $(".task-friends-section").hide();
     });



});
/* End: Document ready */

/* Start: Delete confirmation message show */
var deleteWithCallbackConfirmMsg = function(entity, callback){
   swal({
        title: "Are you sure to delete this "+entity+"?",
        text: (typeof warningText != 'undefined' && warningText)?warningText:'',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
    }, 
    function(confirm){
        callback(confirm);
    }); 
}
/* End: Delete confirmation message show */

/* Start:create and append Milestone dd */
function showMIlestoneDd(defaults) {
    var milestones = $('.mile_section .dd-item');
    $('.milestone-div').show();
    $('.milestone-dropdown').empty();
    var optionValue = '<select id="milestone_div" name="milestone_value" class="selectpicker form-control onchange-set-neutral milestone_div_class" multiple="">';

    $.each(milestones, function(k,obj) {
        var v = $(obj).data('milestones-id'), t = $(obj).find('.milestones-name').val();    
        if(defaults && $.inArray(v+"",defaults) >= 0)
            optionValue += '<option value="'+v+'" selected>'+t+'</option>';
        else
            optionValue += '<option value="'+v+'">'+t+'</option>';
    });

    optionValue += '</select>';
    $('.milestone-dropdown').html($(optionValue));
    initSelectpicker($('.milestone-dropdown select'));
    $('.milestone_div_class').selectpicker('refresh');
}
/* Start:create and append Milestone dd */

/* Start:create and append task habit dd */
function createHabitDd(habitData, val){
    $('.task-habit-div').show();
    var taskoptionValue = '<select id="habit_div" name="habit_value" class="selectpicker form-control onchange-set-neutral taskhabit_div_class" required=""><option value="">-- Select --</option>';    
    $.each(habitData, function(key, value) { 
        if(val !=null && val==value.id)
            taskoptionValue += '<option value="'+value.id+'" selected>'+value.gb_habit_name+'</option>';
        else 
           taskoptionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
    });

    taskoptionValue += '</select>';
    $('.task-habit-dropdown').html($(taskoptionValue));
    initSelectpicker($('.task-habit-dropdown select'));
    $('.taskhabit_div_class').selectpicker('refresh');
    taskoptionValue="";
}
/* End:create and append task habit dd */

/* Start:create and append show Habit List*/
function showHabitList(habitData) {
    var listValue = "";
    $('#client-datatable tbody tr').remove();
    $.each(habitData, function(key, value) {                    
        if(value.milestones!=null)
            var milname=value.milestones[0].gb_milestones_name;
        else
            var milname='';
                            
        if(value.gb_habit_recurrence_type == 'weekly'){
                habitRecurrence = 'Every '+value.gb_habit_recurrence_week;
        } 
        else if(value.gb_habit_recurrence_type == 'monthly'){
                habitRecurrence = 'Day '+value.gb_habit_recurrence_month+' of every month';
        } 
        else {
                habitRecurrence = value.gb_habit_recurrence_type;
        }
        listValue += '<tr><td class="center mw-70 w70">'+value.gb_habit_name+'</td><td>'+habitRecurrence+'</td><td class="hidden-xxs">'+milname+'</td><td class="hidden-xs">'+value.gb_habit_seen+'</td><td class="center"><a class="btn btn-xs btn-default tooltips habit-edit"  data-placement="top"data-original-title="Edit" data-habit-id = "'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-habit" data-placement="top"data-original-title="Delete" data-entity="habit" data-habit-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td></tr>';
            
    });
    $('#client-datatable tbody').append(listValue);
 }
 /* Start:create and append show Habit List*/


function showTaskList(taskData){
       /* $('#client-datatable-task tbody tr').remove();
        var taskListValue = "";
            
        $.each(taskData, function(key, value) {
                            
            if(value.taskhabit!=null)
                var habitname=value.taskhabit[0].gb_habit_name;
            else
                var habitname='';

            $('.task-name').text('Your task '+value.gb_task_name+' has been saved.');
            taskListValue += '<tr><td class="center mw-70 w70">'+value.gb_task_name+'</td><td>'+value.gb_task_priority+'</td><td class="hidden-xxs">'+habitname+'</td><td class="hidden-xs">'+value.gb_task_seen+'<br></td><td class="center"><a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-task" data-placement="top" data-original-title="Delete" data-task-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td>';
                            
            });  
        
        $('#client-datatable-task tbody').append(taskListValue);         */ 
}

function updateStatus(id,value){
    $.ajax({
        url: 'updategoal',
        type: 'POST',
        data: {'goal_id':id,'status':value},
        success: function(response) {
          var data = JSON.parse(response);
          if(data.success == 'true'){

          }  
        }
    });
}

function saveHabitMetaData(hid,hType,checkboxValue){
        $.ajax({
            url: 'insert-metadata',
            type: 'POST',
            data: {'habit_id':hid,'h_type':hType,'h_value':checkboxValue},
            success: function(response) {
              var data = JSON.parse(response);
              if(data.success == 'true'){

              }  
            }
    });
}

function deleteGoal(id){

    $.ajax({
            url: 'deletegoal',
            type: 'POST',
            dataType:'json',
            data: {'eventId':id},
            success: function(response) {

              //var data = JSON.parse(response);
                if(response.status == 'true'){
                    location.reload();
              }  
            }
    });
}


 function retnum(str) { 
    var num = str.replace(/[^0-9]/g, ''); 
    return num; 
}

function getDaysInMonth(){
    var currentYear = (new Date).getFullYear();
    var currentMonth = (new Date).getMonth() + 1;
    return new Date(currentYear, currentMonth, 0).getDate();  
    
}


function updateMilestones(id,value){ //alert('s');
    $.ajax({
            url: public_url+'goal-buddy/updatemilestones',
            type: 'POST',
            data: {'milestonesId':id,'status':value},
            success: function(response) {
              //var data = JSON.parse(response);
                
            }
    });
}


function ucfirst(string){
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function goalConfirmDelete(eventId,entity,eventUrl,actionType, processbarDiv){
    var entity = entity;
    swal({
        title: "Are you sure to delete this "+entity+"?",
        text: (typeof warningText != 'undefined' && warningText)?warningText:'',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d43f3a",
        confirmButtonText: "Yes, delete it!",
        allowOutsideClick: true,
        customClass: 'delete-alert'
    }, 
    function(){
        $(document).on( 'click', '.confirm', function(e) {
         $.ajax({
            url: public_url+eventUrl,
            type: 'POST',
            dataType:'json',
            data: { 'eventId':eventId},
            success: function(response) {

              //var data = JSON.parse(response);
                if(response.status == 'true'){
                    $('.delete-'+entity+'-'+eventId).remove();
                        console.log(actionType);

                    if(actionType == 'calender')
                        $('.delete-'+entity+'-'+eventId).remove();
                    else if(actionType == 'popup')
                        processbarDiv.remove();    
              }  
            }
         });
        });
        //$this.find("form").submit();
    });
}




