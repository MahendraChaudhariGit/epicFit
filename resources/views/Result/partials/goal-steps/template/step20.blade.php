<div class="step showNext" data-step='20'  data-next="0" data-value="">
                  <div class="row">

                    <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1" data-id="19"><span>19.</span></div>


                        <label class="steps-head">Would you like to establish another <strong>task</strong> ? </label>

                      </div>

                      <div class="tooltip-sign mb-10">
                     <a href="javascript:void(0)" class="goal-step" 
                           data-message="Tooltip not provided on the documents"
                           data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>
                  <div class="form-group">
                    
                     {{-- <span> Schedule New Task</span> --}}
                     <a class ="btn btn-primary add-task">Schedule New Task</a>
                     <div class="tooltip-sign mb-10 tooltip_btn">
                        <a href="javascript:void(0)" class="goal-step" 
                            data-message="Please provide tooltip content"
                            data-message1="Please provide tooltip content"><i class="fa fa-question-circle question-mark"></i></a>
                       </div>
                    
                  </div>
                   <div class="table-responsive">
                     <table class="table table-striped table-bordered table-hover" id="client-datatable-task">
                         <thead>
                         <tr>
                             <th class="">Task Name</th>
                             <th class="center mw-70 w70 no-sort hidden-xs">Priority</th>
                             <!--th class="hidden-xxs">Due Date</th-->
                             <th class="hidden-xs">Habit</th>
                           <!--   <th class="center mw-70 w70 no-sort">Shared</th> -->
                             <th class="center mw-70 w70 no-sort">Actions</th>
                         </tr>
                         </thead>
                         <tbody id="tasklist">
                         </tbody>
                     </table>
                 </div>
               </div>

<script type="text/javascript">

$(document).ready(function() {
      
$.ajax({
    url: public_url + 'goal-buddy/load-custom-task-list',
    type: "GET",
    dataType: 'json',
    success: function (data) {
        isJumpTask = "true";
        $('#client-datatable-task tbody tr').remove();
        var taskListValue = "";
        var taskLabel = "";
        var taskDueDate = "";
        var milestonesValue = "";
        // $('#task-id').val(data.taskId);
        var step_no = 1;

        var templateData = JSON.parse(sessionStorage.getItem("templateData"));


        $.each(data.task_list, function(key, value) {
            if(value.task_habit_name != null)
            var habitname = value.task_habit_name;
            else
            var habitname='';

            var hidden = '';
            var primary_task = false;
            if(value.is_primary){
                hidden = 'hidden';
                primary_task = true;
            }
            
            // if(key < 9){
            //     hidden = 'hidden';
            //     primary_task = true;
            // }
            
            $('.task-name').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your task '+value.gb_task_name+' has been saved.</div>');
            
            taskListValue += '<tr><td>'+value.gb_task_name+'</td><td>'+value.gb_task_priority+'</td><td>'+habitname+'</td><td class="center"><a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-step-no="'+step_no+'" data-task-primary="'+primary_task+'" data-task-id = "'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-task '+hidden+'" data-placement="top" data-original-title="Delete" data-entity="task" data-task-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td>';
            
            taskLabel += '<a class="Step-your-goal2 task-text" data ='+value.id+'><li>'+ value.gb_task_name +'</li></a>';
            taskLabel += '<p style="margin-left:18px;">'+value.gb_task_seen+'</p>';

            step_no++;
        });
        
        $('.tasks-label a').remove();
        $('.tasks-label p').remove();
        $('.tasks-label').append(taskLabel);
        taskLabel = '';
        
        $('#client-datatable-task tbody').append(taskListValue);
    }
});

});


$(document).on('click','.add-task',function(){
    $("#waitingShield").removeClass("hidden");
    var goal_type = $("#goal_type").val();
    $.ajax({
       url: public_url + 'goal-buddy/load-custom-task-step?goal_type=' + goal_type,
       type: "GET",
       dataType: 'json',
       processData: false,
       contentType: false,
       success: function (data) {

         $('#add_new_task').val('1');
         $('form').html('');
         $('form').html(data.html);
         setTimeout(function () {
          $("#waitingShield").addClass("hidden");
        }, 2000);
       }
   });
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

// $( document ).on( 'click', '.task-edit', function() {
//         j=1;
//         isJump = "false";
//         var processbarDiv = $(this).closest('tr');
//         // console.log(processbarDiv);
//         // $('.habit-form').show();
//         // $('.habit-listing').hide();
//         var taskId = $(this).attr('data-task-id');
//        // $('.backward').trigger('click');
//         $("#SYG3_selective_friends").attr('value','');
//         loadCustomTaskStep();
//         getTask(taskId, processbarDiv);
//     });


// function getTask(tid, processbarDiv){
//     $('#waitingShield').removeClass('hidden');
//     $.ajax({
//         url: public_url+'goal-buddy/showtask',
//         type: 'POST',
//         data: {'taskId':tid},
//         success: function(data){
//             var data = JSON.parse(data);
//             console.log(data);
//             if(data.status == 'true'){

//                 $('#task-id').val('');
//                 $('input[name="task_id"]').val(data.goalBuddy.id);
//                 // $('#task-id').val(data.goalBuddy.id);
//                 $('#task_id_new').val(data.goalBuddy.id);

//                 var task_habit = [];
//                 $('input[name="SYG_task_recurrence"]').prop('checked', false);
//                 $('#note').val('');
//                 // $('#gb_habit_select_all_milestone').trigger('click');
//                 $('input[name="Priority"]').prop('checked', false);
//                 $('input[name="creattask-send-mail"]').prop('checked', false);
//                 $('input[name="SYG3_see_task"]').prop('checked', false);
            
//                 $('input[name="associatedHabitWithTask"]').val('');
//                 $('input[name="goalTaskData"]').val(data.goalBuddy.id);
//                 $('.cancel_task_btn').removeClass('hidden');
//                 $('.taskNext input[type="radio"]').each(function(){
//                     $(this).prop('checked',false);
//                 })

//                 $('.taskEventRepeatWeekdays').each(function(){
//                     $(this).prop('checked',false);
                    

//                 })
//                 $('.taskNext .showTimeBox').hide(); 
//                 $('.taskNext .showDayBox').hide(); 
//                 $('.taskNext .showMonthBox').hide(); 
//                 // Set default task priority
//                 $("#SYG3_priority").val('Low');
//                 $('#viewport-4').find('#gb_task_priority_wrapper').find('li[data-value="Low"]').trigger('click').trigger('change');

//                 // Set default task recurrence 
//                 // $("input[name=SYG3_see_task]").val('daily');
//                 $('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').find('li[data-value="daily"]').trigger('click').trigger('change');
//                 $('#viewport-4').find('ul#gb_task_recurrence_type_wrapper').children('li').addClass('disabled_task_recurrence');

//                 // Set default task seen
//                 $('#viewport-4').find('#gb_task_seen_wrapper').find('li[data-value="everyone"]').trigger('click').trigger('change');
//                 // $("input[name=SYG3_see_task]").val('everyone');
                
//                 // Set default task reminder
//                 $('#viewport-4').find('#gb_task_reminder_wrapper').find('li[data-value="When_task_is_overdue"]').trigger('click').trigger('change');
//                 $("input[name=SYG3_send_msg]").val('When_task_is_overdue');

//                 // Set default task notes
//                 $('#note').val('');
//                 $('input[name="Priority"]').each(function(){
//                     if($(this).val() == data.goalBuddy.gb_task_priority){
//                         $(this).prop('checked',true);
//                     }
//                 })
//                 $('input[name="SYG3_see_task"]').each(function(){
//                     if($(this).val() == data.goalBuddy.gb_task_seen){
//                         $(this).prop('checked',true);
//                     }
//                 })
//                 if(data.goalBuddy.gb_task_seen == 'Selected friends'){
//                     // console.log(data.goalBuddy.gb_habit_selective_friends)
//                     $("#SYG3_selective_friends").attr('value',data.goalBuddy.gb_task_selective_friends);
//                     if($("#all-my-friends").val() != undefined && $("#all-my-friends").val() != ''){
//                         $("#SYG3_selective_friends").amsifySuggestags("refresh");
//                         var my_friends = JSON.parse($("#all-my-friends").val());
//                         var options = [];
                            
//                         for(var aaa =0; aaa < my_friends.length; aaa++ ){
//                             options[aaa] = {'tag':my_friends[aaa].name,'value':my_friends[aaa].id}
//                         }
                    
//                         $('.autocomplete').amsifySuggestags({
//                             type :'bootstrap',
//                             suggestions: options,
//                             whiteList:true,
//                         });
//                     }
//                     $("#SYG3_selective_friends").parent().removeClass('hidden');
//                 }else{
//                     $("#SYG3_selective_friends").attr('value','');
//                     $("#SYG3_selective_friends").parent().addClass('hidden');
//                 }
//                 $('input[name="SYG_task_recurrence"]').each(function(){
//                     if($(this).val() == data.goalBuddy.gb_task_recurrence_type){
//                         $(this).prop('checked',true);
//                     }
//                     if(data.goalBuddy.gb_task_recurrence_type == 'weekly'){
//                         var recWeek = data.goalBuddy.gb_task_recurrence_week.split(',');
//                         $('.taskEventRepeatWeekdays').each(function(){
//                             if(jQuery.inArray($(this).val(), recWeek) !== -1){
//                                 $(this).prop('checked',true);
//                             }
                            

//                         })

//                         $('.taskNext .showDayBox').show();
//                         $('.taskNext .showMonthBox').hide();
//                     }else if(data.goalBuddy.gb_task_recurrence_type == 'monthly'){
//                         $('.taskNext .showMonthBox').show();
//                         $('.taskNext .showDayBox').hide();
//                                 $('#gb_task_recurrence_month').val(data.goalBuddy.gb_task_recurrence_month).selectpicker('refresh');
//                     }
//                 })

//                 $('input[name="creattask-send-mail"]').each(function(){
//                     if($(this).val() == data.goalBuddy.gb_task_reminder){
//                         $(this).prop('checked',true);
                        
                        
//                     }
//                     if($(this).val() == 'daily')
//                     {
//                         $('#daily_time_task').val(data.goalBuddy.gb_task_reminder_time).selectpicker('refresh');
//                         $('.taskNext .showTimeBox').show();
//                     }else{
//                         $('.taskNext .showTimeBox').hide(); 
//                     }
//                 })

//                 $('input[name="creattask-send-epichq"]').each(function(){
//                     if($(this).val() == data.goalBuddy.gb_task_reminder_epichq){
//                         $(this).prop('checked',true);                               
//                     }
//                 })

//             var goalTempleteId = $('input[name="template"]:checked').data('id');
//             console.log('goalTempleteId', goalTempleteId);  
//              if(goalTempleteId == undefined){

//                 if(data.habitTask != ''){
//                     $('.task-habit-div').show();
//                     var optionValue = '<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';
                    
//                     $.each(data.habitTask,function(key, value) {
//                         if(value.id==data.goalBuddy.gb_habit_id) {
//                             task_habit = value;
//                             let taskHabiStringify = JSON.stringify(value);
//                             $('#viewport-4').find('input[name="associatedHabitWithTask"]').val(taskHabiStringify);
//                              optionValue += '<option value="'+value.id+'" >'+value.gb_habit_name+'</option>';
//                             //  optionValue += '<option value="'+value.id+'" selected>'+value.gb_habit_name+'</option>';
//                         } else{
//                             optionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
//                         }
//                     });
//                     optionValue += '</select>';
//                     $('.task-habit-dropdown').html(optionValue);
//                     initSelectpicker($('.task-habit-dropdown select'));
                    
//                     $('.taskhabit_div_class').selectpicker('refresh');
//                     optionValue = '';
//                 }
//                 else{ 
//                     $('.task-habit-div').hide();   
//                 }

//              } else {
//                 $('.task-habit-div').show();
//                 var optionValue = '';
                
//                 $.each(data.habitTask,function(key, value) {
//                     if(value.id==data.goalBuddy.gb_habit_id) {

//                         optionValue = '<input data-toggle="tooltip" title="" type="text" class="form-control taskhabit_div_class" id="habit_div" value="'+value.gb_habit_name+'" name="habit_value" disabled>';
//                     }
//                 });
//                 $('.task-habit-dropdown').html(optionValue);
//                 optionValue = '';
                 
//              }
//                 if(data.goalBuddy.gb_task_recurrence_type == "" || data.goalBuddy.gb_task_recurrence_type == undefined){
//                     if(data.goalBuddy.gb_task_name == "Resistance Training" || data.goalBuddy.gb_task_name == "Recovery Routine" || data.goalBuddy.gb_task_name == "Cardiovascular Training" || data.goalBuddy.gb_task_name == "Recovery Routines"){
//                         var habitData = data.habitTask;
//                         $.each(habitData,function(key,value){

//                             var recWeek = value.gb_habit_recurrence_week.split(',');
//                             if(key == 0 && value.gb_habit_recurrence_type == 'weekly'){
//                                 $("#hbt_rec_tsk").val('none');
//                                 $('.taskEventRepeatWeekdays').each(function(){
//                                     if(jQuery.inArray($(this).val(), recWeek) !== -1){
//                                         $(this).prop('checked',true);
//                                     }   
//                                 })
//                                 $('.taskNext .showDayBox').show(); 
//                                 $('.taskNext .showMonthBox').hide();
                                
//                                 $('input[name="SYG_task_recurrence"][value="weekly"]').prop('checked', true);
//                                 $('#habitRecValue').val(recWeek);
//                                 $('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
//                                 //$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
//                             }else if(key == 0 && value.gb_habit_recurrence_type == 'daily'){
//                                 $("#hbt_rec_tsk").val('daily');
//                                 $('input[name="SYG_task_recurrence"][value="weekly"]').trigger('click');
//                                 //prop('checked', true);
//                                 $('.taskEventRepeatWeekdays').each(function(){
//                                         $(this).prop('checked',true);
//                                 });
//                                 $('.taskNext .showDayBox').show(); 
//                             }else if(key == 0 && value.gb_habit_recurrence_type == 'monthly'){
//                                 $('input[name="SYG_task_recurrence"][value="monthly"]').prop('checked', true);
//                                 $('.taskNext .showMonthBox').show();
//                                 $('#gb_task_recurrence_month').val(value.gb_habit_recurrence_month).selectpicker('refresh');
//                                 $('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
//                                 $('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
//                                 $('.taskNext .showDayBox').hide(); 
//                             }
//                         });
                        
//                     }else if(data.goalBuddy.gb_task_name == "Portion Distortion" || data.goalBuddy.gb_task_name == "Food preparation" || data.goalBuddy.gb_task_name == "Trace and Replace"){
//                         var habitData = data.habitTask;
//                         $.each(habitData,function(key,value){
//                             var recWeek = value.gb_habit_recurrence_week.split(',');
//                             if(key == 1 && value.gb_habit_recurrence_type == 'weekly'){
//                                 $("#hbt_rec_tsk").val('none');
//                                 $('.taskEventRepeatWeekdays').each(function(){
//                                     if(jQuery.inArray($(this).val(), recWeek) !== -1){
//                                         $(this).prop('checked',true);
//                                     }   
//                                 })
//                                 $('.taskNext .showMonthBox').hide();
//                                 $('.taskNext .showDayBox').show(); 
//                                 $('input[name="SYG_task_recurrence"][value="weekly"]').prop('checked', true);
//                                 $('#habitRecValue').val(recWeek);
//                                 $('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
//                                 //$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
//                             }else if(key == 1 && value.gb_habit_recurrence_type == 'daily'){
//                                 $("#hbt_rec_tsk").val('daily');
//                                 $('input[name="SYG_task_recurrence"][value="weekly"]').trigger('click');
//                                 //prop('checked', true);
//                                 $('.taskEventRepeatWeekdays').each(function(){
//                                         $(this).prop('checked',true);
//                                 });
//                                 $('.taskNext .showDayBox').show();
                                
//                             }else if(key == 1 && value.gb_habit_recurrence_type == 'monthly'){
//                                 $('input[name="SYG_task_recurrence"][value="monthly"]').prop('checked', true);
//                                 $('.taskNext .showMonthBox').show();
//                                 $('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
//                                 $('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
//                                 $('#gb_task_recurrence_month').val(value.gb_habit_recurrence_month).selectpicker('refresh');
//                                 $('.taskNext .showDayBox').hide(); 
//                             }
//                         });
//                     }else if(data.goalBuddy.gb_task_name == "Limit blue light" || data.goalBuddy.gb_task_name == "Sleep" || data.goalBuddy.gb_task_name == "Time Management"){
//                         var habitData = data.habitTask;
//                         $('.taskEventRepeatWeekdays').each(function(){
//                             $(this).prop('checked',false);
//                         })
//                         $.each(habitData,function(key,value){
//                             var recWeek = value.gb_habit_recurrence_week.split(',');
//                             if(key == 2 && value.gb_habit_recurrence_type == 'weekly'){
//                                 $("#hbt_rec_tsk").val('none');
//                                 $('.taskEventRepeatWeekdays').each(function(){
//                                     if(jQuery.inArray($(this).val(), recWeek) !== -1){
//                                         $(this).prop('checked',true);
//                                     }   
//                                 })
//                                 $('.taskNext .showMonthBox').hide();
//                                 $('.taskNext .showDayBox').show(); 
//                                 $('input[name="SYG_task_recurrence"][value="weekly"]').prop('checked', true);
//                                 $('#habitRecValue').val(recWeek);
//                                 $('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
//                                 //$('input[name="SYG_task_recurrence"][value="monthly"]').prop('disabled',true);
//                             }else if(key == 2 && value.gb_habit_recurrence_type == 'daily'){
                                
//                                 $("#hbt_rec_tsk").val('daily');
//                                 $('input[name="SYG_task_recurrence"][value="weekly"]').trigger('click');
//                                 //prop('checked', true);
//                                 $('.taskEventRepeatWeekdays').each(function(){
//                                         $(this).prop('checked',true);
//                                 });
//                                 $('.taskNext .showDayBox').show();
                                
//                             }else if(key == 2 && value.gb_habit_recurrence_type == 'monthly'){
//                                 $('input[name="SYG_task_recurrence"][value="monthly"]').prop('checked', true);
//                                 $('.taskNext .showMonthBox').show();
//                                 $('input[name="SYG_task_recurrence"][value="daily"]').prop('disabled',true);
//                                 $('input[name="SYG_task_recurrence"][value="weekly"]').prop('disabled',true);
//                                 $('#gb_task_recurrence_month').val(value.gb_habit_recurrence_month).selectpicker('refresh');
//                                 $('.taskNext .showDayBox').hide(); 
//                             }
//                         });
//                     }
//                 }

//                 // $('#task-id').val(data.goalBuddy.id);
//                 $('#SYG3_task').val(data.goalBuddy.gb_task_name);
//                 $('#SYG3-time').val(data.goalBuddy.gb_task_time);
//                 $("#SYG3_priority").val(data.goalBuddy.gb_task_priority);
//                 // $("input[name=SYG3_see_task]").val(data.goalBuddy.gb_task_seen);
//                 $("input[name=SYG3_send_msg]").val(data.goalBuddy.gb_task_reminder);
//                 // $('select#habit_div').attr('disabled', true);
//                 $('select#habit_div').val(data.goalBuddy.gb_habit_id).selectpicker("refresh");
//                 $('#SYG_task_note').val(data.goalBuddy.gb_task_note);
//                 // alert('hiii');
//                 //console.log('pinki', data.goalBuddy.gb_task_note.length);
//                 $('#note').on( 'keydown', function (e){
//                     $(this).css('height', 'auto' );
//                     $(this).height( this.scrollHeight );
//                 });
//                 $('#note').keydown();
                
//                 var task_priority = data.goalBuddy.gb_task_priority;
//                 var task_recurrence_type = data.goalBuddy.gb_task_recurrence_type;
//                 var task_recurrence_week = data.goalBuddy.gb_task_recurrence_week;
//                 var task_recurrence_month = data.goalBuddy.gb_task_recurrence_month;
//                 var task_seen = data.goalBuddy.gb_task_seen;
//                 var task_reminder = data.goalBuddy.gb_task_reminder;
                
//                 task_recurrence_type = data.goalBuddy.gb_task_recurrence_type == '' ? task_habit.gb_habit_recurrence_type : data.goalBuddy.gb_task_recurrence_type;
//                 task_recurrence_week = data.goalBuddy.gb_task_recurrence_type == '' ? task_habit.gb_habit_recurrence_week : data.goalBuddy.gb_task_recurrence_week;
//                 task_recurrence_month = data.goalBuddy.gb_task_recurrence_type == '' ? task_habit.gb_habit_recurrence_month : data.goalBuddy.gb_task_recurrence_month;
//             }
//             setTimeout(function() {
//                 $('#waitingShield').addClass('hidden');
//             },2000);
//         }
//     });
// }
</script>