var totalTodayTasks = 0;
var completedTodayTasks = 0;
var totalWeekTasks = 0;
var completedWeekTasks = 0;
var totalMonthTasks = 0;
var completedMonthTasks = 0;
var taskIdForDelete=0;
var checkboxvalue=0;
var popover=0;


function x(){
    toggleWaitShield("show", 'saveTask');
    var currentUserId = loggedInUser.userId;

    var currentDate = $.datepicker.formatDate('yy-mm-dd', new Date());
    
    var inputDate;
    var toDoSection = $('#todosection');
    var weekSection = $('#weeksection');
    var monthSection = $('#monthsection');

    var formData = {};
    var form = $('#taskForm');
        formData['taskFormId'] = $('input[name=taskFormId]').val();
        formData['taskName'] = $('#taskName').val();
        formData['taskDueDate'] = moment($('#taskDueDate').val()).format("YYYY-MM-DD");
        formData['taskDueTime'] = timeStringToDbTime($('#taskDueTime').val());
        formData['taskRepeat'] = $('input[name=taskRepeat]').val();
        formData['catuserid'] = $('.rapidodd').data("owner-id");

        formData['taskCategory'] = $('select#taskcategoryid').val();
        var pipe = formData['taskCategory'].indexOf('|');
        if(pipe >= 0){
            formData['authId'] = formData['taskCategory'].substring(pipe+1); 
            formData['taskCategory'] = formData['taskCategory'].substring(0,pipe); 
        }
        else
             formData['authId'] = loggedInUser.userId;
        
        if($("#remindercheck").is(':checked')){
            formData['isReminderSet'] = 1;
            var selectHours = $('select#remindercheckid').val();
            formData['reminderHours'] = selectHours;
            formData['reminderDateTime'] = moment(formData['taskDueDate']+' '+formData['taskDueTime'], 'YYYY-MM-DD h:mm A').subtract('hours', selectHours).format('YYYY-MM-DD h:mm A')
        }

        var taskFilterDate = $('.ui-datepicker-trigger').text();//$('#taskFilterSection img').attr('alt');
        if(taskFilterDate.length){
            formData['taskFilterDate'] = moment(taskFilterDate).format('YYYY-MM-DD');
        }
        else
            formData['taskFilterDate'] = moment().format('YYYY-MM-DD');
        taskFilterDateFormat = moment(taskFilterDate).format('YYYY-MM-DD');
        var addday = moment(taskFilterDateFormat).add(6,'d') .toDate();
        var addmonth = moment(taskFilterDateFormat).add(1,'M') .toDate();
        var subtractday = moment(addmonth).subtract(1, 'days');
        var nextWeekDate = moment(addday).format('YYYY-MM-DD');
        var nextMonthDate = moment(subtractday).format('YYYY-MM-DD');
        var addtomorrow = moment().add(1,'d') .toDate();
        var tomorrow = moment(addtomorrow).format('YYYY-MM-DD');
        
        formData['eventRepeat'] = $('select#eventrepeatid').val();
        formData['eventRepeatInterval'] = $('select#eventrepeatintervalid').val();
        

    if(formData['eventRepeat'] == 'Daily' || formData['eventRepeat'] == 'Weekly' || formData['eventRepeat'] == 'Monthly'){

        formData['eventRepeatEnd'] = form.find('input[name="eventRepeatEnd"]:checked').val();
        
        if(formData['eventRepeatEnd'] == 'After'){ 
            formData['eventRepeatEndAfterOccur'] = $('select#eventrepeatendafteroccurid').val();   
        }

        else if(formData['eventRepeatEnd'] == 'On'){
            formData['eventRepeatEndOnDate'] = $('input[name="eventRepeatEndOnDate"]').val();
            formData['eventRepeatEndOnDate'] = moment(formData['eventRepeatEndOnDate'], 'D MMM YYYY').format("YYYY-MM-DD"); 
        }
        else if(formData['eventRepeatEnd'] == 'Never')
            formData['calendEndDate'] = getCalendEndDate();
        if(formData['eventRepeat'] == 'Weekly'){
            var temp = [],
                eventRepeatWeekdays = form.find('.eventRepeatWeekdays input[type="checkbox"]:checked');
            if(eventRepeatWeekdays.length){
                eventRepeatWeekdays.each(function(){
                    temp.push($(this).val());
                })
                formData['eventRepeatWeekdays'] = temp;
            }
        }
    }


        var dropDownName=$('.rapidodd').text(); 
        var selectFieldName = $('select#taskcategoryid option').filter(function () { return $(this).html() == dropDownName; });

        categChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
        formData['categName'] = categChange.data('catid');


        formData['taskNote'] = $('[name="taskNote"]').val();
        
    //console.log(formData);//return
    $.post(public_url+'dashboard/task', formData, function(response){ 
        getUpcomingTasks();

        var data = JSON.parse(response);
        if( bladeType == "Dashboard" ){
            /*if(($("#tasksReminderModal").data('bs.modal') || {}).isShown){
                alert('ok')
                getTasksAjax();
            }
            else
                alert('fm')*/
            /*else
                getUpcomingTasks();*/
            
            toDoSection.empty();
            weekSection.empty();
            monthSection.empty();
            completedTodayTasks=totalTodayTasks=totalWeekTasks=completedWeekTasks=totalMonthTasks=completedMonthTasks=0; 
            $.each( data.db, function( key, value ){  
                
                
                if(isUserType(['Admin']) || currentUserId==value.task_user_id){

                    if(value.reminders && value.reminders[0]){
                        var remindSection = 'data-remindhours='+value.reminders[0].tr_hours+'  data-remindcheckbox='+value.reminders[0].tr_is_set+'';
                    }
                    else
                        var remindSection = "data-remindhours=''  data-remindcheckbox=''";
                    var hiddenClass ='';
                }
                else {
                    var hiddenClass = ' hidden';
                }

                var editdeletesection ='<a class="btn edittask preventTaskView" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id="'+value.id+'" data-task-name="'+value.task_name+'" data-auth-id="'+value.task_user_id+'" data-task-duedate="'+value.task_due_date+'" data-task-time="'+value.task_due_time+'" data-task-categ="'+value.task_category+'" data-task-repeat="'+value.task_tr_id+'" '+remindSection+' data-tasknote="'+value.task_note+'"><i class="fa fa-pencil-square-o"></i></a> '+((value.task_tr_id != 0)?'<a class="btn delete-prompt preventTaskView" href="#" style="display: block;" data-original-title="" title=""><i class="fa fa-trash-o"></i></a>':'<a class="btn delLink preventTaskView" data-entity="task" href="'+public_url+'dashboard/'+value.id+'" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask"> <i class="fa fa-trash-o"></i></a>')+'';

                var task_lable=''; 
                if(value.task_client_id == 0 || value.task_client_id == 'undefined' || value.task_client_id == '')
                    task_lable=value.task_name;
                else
                    task_lable='<a class="preventTaskView" href="'+public_url+'client/'+value.task_client_id+'?page=dashboard">'+value.task_name+'</a>';
                    

                if( (data.taskStatus == "created") || (data.taskStatus == "updated") ){
                    
                    if(value.task_status=="complete" || value.task_status=="not required"){
                        if(isUserType(['Staff']))
                            cboxclass='hidden';
                        else
                            cboxclass='';
                            opacity='opacity25class';
                            linethrough='textdecorclass';
                            hidden='hidden';    
                    }
                    else{ 
                        cboxclass='';
                        opacity='';
                        linethrough='';
                        hidden='';
                    }
                    var dataDueDate = value.task_due_date;
                    
                    if(dataDueDate == taskFilterDateFormat){
                        if(dataDueDate == currentDate)
                            inputDate = "Today";
                        else
                            inputDate = moment(dataDueDate).format('ddd, D MMM YYYY');
                       
                        if(value.task_status=="complete" || value.task_status=="not required"){
                            completedTodayTasks++;
                        }
                        totalTodayTasks++;

                        $("#todosection").append('<li class="showtaskmodal"><div class="todo-actions clearfix taskDiv"> <div class="btn-group btn-group-xs pull-left preventTaskView"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#" ><span class="checkboxdd"><i '+((value.task_status=="complete")?'class="fa fa-check-square-o"':((value.task_status=="not required")?'class="fa fa-exclamation"':'class="fa fa-square-o"'))+'></i></span>&nbsp; <span class="caret '+cboxclass+'"></span></a><ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown '+cboxclass+'"><li><a data-status=""></a></li><li '+((value.task_status=="complete")?'class="hidden"':'')+'><a data-status="complete" data-taskid='+value.id+'>Complete</a></li><li '+((value.task_status==null)?'class="hidden"':'')+'><a data-status="incomplete" data-taskid='+value.id+'>Incomplete</a></li><li '+((value.task_status=="not required")?'class="hidden"':'')+'><a data-status="not required" data-taskid='+value.id+'>Not required</a></li></ul></div> <div class="padding-horizontal-5 pull-left"><div class="block space5 to-do-link"><span class="desc tasknameclass '+opacity+' '+linethrough+'">'+task_lable+'</span> '+((value.task_tr_id != 0)?'<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>':'')+'<span class="label label-danger top-10'+opacity+'"> '+ inputDate +' </span></div><div class="block"><span class="desc text-small text-light taskdatetimeclass '+opacity+' '+linethrough+'"><i class="fa fa-clock-o">&nbsp;</i> '+dbTimeToTimeString(value.task_due_time)+' </span><div class="todo-tools"> <div class="btn-group btn-group-sm '+hidden+hiddenClass+'"> '+editdeletesection+' </div> '+((value.task_status=="complete" || value.task_status=="not required")?'<span class="username">'+value.completer.name+' '+value.completer.last_name+'</span>':'')+' </div></div></div></div></li>'); 
                    }

                    if((value.task_due_date >= taskFilterDateFormat) && (value.task_due_date <= nextWeekDate)){
                        if(dataDueDate == currentDate)
                            inputDate="Today";
                        else if( (dataDueDate==tomorrow))
                            inputDate="Tomorrow";
                        else 
                            inputDate = moment(dataDueDate).format('ddd, D MMM YYYY');

                        if(value.task_status=="complete" || value.task_status=="not required")
                            completedWeekTasks++;
                        totalWeekTasks++;

                        $("#weeksection").append('<li class="showtaskmodal"><div class="todo-actions clearfix taskDiv"> <div class="btn-group btn-group-xs pull-left preventTaskView"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#" ><span class="checkboxdd"><i '+((value.task_status=="complete")?'class="fa fa-check-square-o"':((value.task_status=="not required")?'class="fa fa-exclamation"':'class="fa fa-square-o"'))+'></i></span>&nbsp; <span class="caret '+cboxclass+'"></span></a><ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown '+cboxclass+'"><li><a data-status=""></a></li><li '+((value.task_status=="complete")?'class="hidden"':'')+'><a data-status="complete" data-taskid='+value.id+'>Complete</a></li><li '+((value.task_status==null)?'class="hidden"':'')+'><a data-status="incomplete" data-taskid='+value.id+'>Incomplete</a></li><li '+((value.task_status=="not required")?'class="hidden"':'')+'><a data-status="not required" data-taskid='+value.id+'>Not required</a></li></ul></div> <div class="padding-horizontal-5 pull-left"><div class="block space5 to-do-link"><span class="desc tasknameclass '+opacity+' '+linethrough+'">'+task_lable+'</span> '+((value.task_tr_id != 0)?'<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>':'')+'<span class="label label-danger top-10'+opacity+'">  '+ inputDate +'</span></div><div class="block"><span class="desc text-small text-light taskdatetimeclass '+opacity+' '+linethrough+'"><i class="fa fa-clock-o">&nbsp;</i> '+dbTimeToTimeString(value.task_due_time)+' </span><div class="todo-tools"><div class="btn-group btn-group-sm '+hidden+hiddenClass+'"> '+editdeletesection+' </div> '+((value.task_status=="complete" || value.task_status=="not required")?'<span class="username">'+value.completer.name+' '+value.completer.last_name+'</span>':'')+' </div></div></div></div></li>');
                    } 

                    if((value.task_due_date >= taskFilterDateFormat) && (value.task_due_date <= nextMonthDate)){
                        if(dataDueDate == currentDate)
                            inputDate="Today";
                        else if( (dataDueDate==tomorrow))
                            inputDate="Tomorrow";
                        else 
                            inputDate = moment(dataDueDate).format('ddd, D MMM YYYY');

                        if(value.task_status=="complete" || value.task_status=="not required")
                            completedMonthTasks++;
                        totalMonthTasks++;

                        $("#monthsection").append('<li class="showtaskmodal"><div class="todo-actions clearfix taskDiv"> <div class="btn-group btn-group-xs pull-left preventTaskView"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#" ><span class="checkboxdd"><i '+((value.task_status=="complete")?'class="fa fa-check-square-o"':((value.task_status=="not required")?'class="fa fa-exclamation"':'class="fa fa-square-o"'))+'></i></span>&nbsp; <span class="caret '+cboxclass+'"></span></a><ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown '+cboxclass+'"><li><a data-status=""></a></li><li '+((value.task_status=="complete")?'class="hidden"':'')+'><a data-status="complete" data-taskid='+value.id+'>Complete</a></li><li '+((value.task_status==null)?'class="hidden"':'')+'><a data-status="incomplete" data-taskid='+value.id+'>Incomplete</a></li><li '+((value.task_status=="not required")?'class="hidden"':'')+'><a data-status="not required" data-taskid='+value.id+'>Not required</a></li></ul></div> <div class="padding-horizontal-5 pull-left"><div class="block space5 to-do-link"><span class="desc tasknameclass '+opacity+' '+linethrough+'">'+task_lable+'</span> '+((value.task_tr_id != 0)?'<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>':'')+'<span class="label label-danger top-10 '+opacity+'">  '+ inputDate +'</span></div><div class="block"><span class="desc text-small text-light taskdatetimeclass '+opacity+' '+linethrough+'"><i class="fa fa-clock-o">&nbsp;</i> '+dbTimeToTimeString(value.task_due_time)+' </span> <div class="todo-tools"><div class="btn-group btn-group-sm '+hidden+hiddenClass+'"> '+editdeletesection+' </div> '+((value.task_status=="complete" || value.task_status=="not required")?'<span class="username">'+value.completer.name+' '+value.completer.last_name+'</span>':'')+' </div></div></div></div></li>');
                    } 
                }
            });

            if($('.todotask').parent().hasClass('active'))
                $('.todotask').trigger('click');
            else if($('.thisweektask').parent().hasClass('active'))
                $('.thisweektask').trigger('click');
            else $('.thismonthtask').trigger('click');
        }
        else if( bladeType == "DashboardCalendar" ){
            getAjaxData();
        }
        toggleWaitShield("hide", 'saveTask');
    });

            //$('input[name=taskFormId]').val('');
            $('#addtask').modal('hide');
    
}

//Category Dropdown 
$('body').on('click', '.rapidoDropdown li a', function(e){
    //getTasksAjax();
    toggleWaitShield("show", 'listTask');

    var currentUserId = loggedInUser.userId;
     
    var categData = {};
    var toDoSection = $('#todosection');
    var weekSection = $('#weeksection');
    var monthSection = $('#monthsection');
    var completeCls='';
    var incompleteCls='';
    var notReqCls='';
    categData['categId'] = $(this).attr("data-catid");

    if(hasData($(this), 'owner-id'))
        categData['ownerId'] = $(this).data("owner-id");
    else
        categData['ownerId'] = 0;

    var ddValue = $(this).text();
    $('.rapidodd').data("owner-id", categData['ownerId']).text(ddValue);
   
    var taskFilterDate = $('.ui-datepicker-trigger').text();//$('#taskFilterSection img').attr('alt');
    categData['taskFilterDate'] = moment(taskFilterDate).format('YYYY-MM-DD');
    taskFilterDateFormat = moment(taskFilterDate).format('YYYY-MM-DD');
    var catuserid = $(this).attr("data-userid");
    
    $('#deletecategsection').empty();
    if((categData['categId']!="") && (catuserid != 0)){
       if( isUserType(['Admin']) || currentUserId==catuserid ){
        //$('#deletecategsection').append('&nbsp;<button type="button" class="btn btn-xs btn-transparent-white editcategory" data-toggle="modal" data-target="#addcategory"><i class="fa fa-gear"></i></button>&nbsp;<button type="button" class="btn btn-xs dropdown-toggle btn-transparent-white" data-toggle="dropdown" aria-expanded="true"><span class="caret" id="cwhite"></span></button><ul class="dropdown-menu" id="deleteicon" role="menu"><li><a class="btn delLink" data-entity="category" href="'+public_url+'dashboardd/'+categData['categId']+'" data-placement="top" data-original-title="Delete" data-ajax-callback="deletecategory"><i class="fa fa-trash-o"></i></a></li></ul>');
        
        $('#deletecategsection').append('&nbsp;<button type="button" class="btn btn-xs btn-transparent-white editcategory" data-toggle="modal" data-target="#addcategory"><i class="fa fa-gear"></i></button>&nbsp;<a class="btn btn-xs delLink btn-transparent-white" data-entity="category" href="'+public_url+'dashboardd/'+categData['categId']+'" data-placement="top" data-original-title="Delete" data-ajax-callback="deletecategory"><i class="fa fa-trash-o"></i></a>');
       }
    }
    //&nbsp;<a class="btn btn-xs btn-transparent-white delLink" data-entity="category" href="'+public_url+'dashboardd/'+categData['categId']+'" data-placement="top" data-original-title="Delete" data-ajax-callback="deletecategory"><i class="fa fa-trash-o"></i></a>

    var addday = moment(taskFilterDateFormat).add(6,'d') .toDate();
    //var addmonth = moment(taskFilterDateFormat).add(1,'M') .toDate();
    //var subtractday = moment(addmonth).subtract(1, 'days');
    var nextWeekDate = moment(addday).format('YYYY-MM-DD');
    //var nextMonthDate = moment(subtractday).format('YYYY-MM-DD');
    var nextMonthDate = getCalendEndDate();
    var addtomorrow = moment().add(1,'d') .toDate();
    var tomorrow = moment(addtomorrow).format('YYYY-MM-DD');
    categData['taskMonthDate'] = nextMonthDate;
    

    var currentDate = $.datepicker.formatDate('yy-mm-dd', new Date());
    var inputDate;
    var cboxclass;
    var opacityclass;
    var linethrouclass;
    var hidden;

        $.post(public_url+'dashboard/categoryId', categData, function(response){
            var data = JSON.parse(response);
            toDoSection.empty();
            weekSection.empty();
            monthSection.empty();
            completedTodayTasks = totalTodayTasks=totalWeekTasks=completedWeekTasks=totalMonthTasks=completedMonthTasks=0; 
            if(data.length){
                $.each( data, function( key, value ){

                //alert( key.key + ": " + value );
                    if(value.task_status=="complete" || value.task_status=="not required"){
                        if(isUserType(['Staff'])){
                            if(checkboxvalue == value.id )
                                cboxclass='';
                            else 
                                cboxclass='hidden';
                        }
                        else
                            cboxclass='';
                         opacity='opacity25class';
                         linethrough='textdecorclass';
                         hidden='hidden';     
                    }
                    else{ 
                        cboxclass='';
                        opacity='';
                        linethrough='';
                        hidden='';
                    }
                    var dataDueDate = value.task_due_date;
                     
                    var task_lable=''; 
                    if(value.task_client_id == 0 || value.task_client_id == 'undefined' || value.task_client_id == '')
                        task_lable=value.task_name;
                    else
                        task_lable='<a class="preventTaskView" href="'+public_url+'client/'+value.task_client_id+'?page=dashboard">'+value.task_name+'</a>';

                    
                    /*if(value.task_status=="complete")
                        completeCls='hidden';
                    else if(value.task_status=="not required")
                        notReqCls='hidden';
                    else
                        incompleteCls='hidden';*/
                          

                   /* console.log(value.task_status);
                    console.log(incompleteCls);*/
                    //if(dataDueDate == currentDate){
                     if(dataDueDate == categData['taskFilterDate']){

                        if(dataDueDate == currentDate)
                            inputDate = "Today";
                        else
                            inputDate = moment(dataDueDate).format('ddd, D MMM YYYY');

                        if(value.task_status=="complete" || value.task_status=="not required")
                            completedTodayTasks++;
                        totalTodayTasks++;

                        
                        if(isUserType(['Admin']) || currentUserId==value.task_user_id){
                            
                            if(value.reminders && value.reminders[0]){
                                var remindSection = 'data-remindhours='+value.reminders[0].tr_hours+'  data-remindcheckbox='+value.reminders[0].tr_is_set+'';
                            }
                            else
                                var remindSection = "data-remindhours=''  data-remindcheckbox=''";
                            var hiddenClass = '';
                        }
                        else {
                            var hiddenClass = ' hidden';
                        }

                        var editdeletesection ='<a class="btn edittask preventTaskView" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id="'+value.id+'" data-task-name="'+value.task_name+'" data-auth-id="'+value.task_user_id+'" data-task-duedate="'+value.task_due_date+'" data-task-time="'+value.task_due_time+'" data-task-categ="'+value.task_category+'" data-task-repeat="'+value.task_tr_id+'" '+remindSection+ ' data-tasknote="'+value.task_note+'"><i class="fa fa-pencil-square-o"></i></a> '+((value.task_tr_id != 0)?'<a class="btn delete-prompt preventTaskView" href="#" style="display: block;" data-original-title="" title=""><i class="fa fa-trash-o"></i></a>':'<a class="btn delLink preventTaskView" data-entity="task" href="'+public_url+'dashboard/'+value.id+'" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask" > <i class="fa fa-trash-o"></i></a>')+'';


                        $("#todosection").append('<li class="showtaskmodal"><div class="todo-actions clearfix taskDiv"> <div class="btn-group btn-group-xs pull-left preventTaskView"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#" ><span class="checkboxdd"><i '+((value.task_status=="complete")?'class="fa fa-check-square-o"':((value.task_status=="not required")?'class="fa fa-exclamation"':'class="fa fa-square-o"'))+'></i></span>&nbsp; <span class="caret '+cboxclass+'"></span></a><ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown '+cboxclass+'"><li><a data-status=""></a></li><li '+((value.task_status=="complete")?'class="hidden"':'')+'><a data-status="complete" data-taskid='+value.id+'>Complete</a></li><li '+((value.task_status==null)?'class="hidden"':'')+'><a data-status="incomplete" data-taskid='+value.id+'>Incomplete</a></li><li '+((value.task_status=="not required")?'class="hidden"':'')+'><a data-status="not required" data-taskid='+value.id+'>Not required</a></li></ul></div> <div class="padding-horizontal-5 pull-left"><div class="block space5 to-do-link"><span class="desc tasknameclass '+opacity+' '+linethrough+'">'+task_lable+'</span> '+((value.task_tr_id != 0)?'<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>':'')+'<span class="label label-danger top-10 '+opacity+'">'+ inputDate +'</span></div><div class="block"><span class="desc text-small text-light taskdatetimeclass '+opacity+' '+linethrough+'"><i class="fa fa-clock-o">&nbsp;</i> '+dbTimeToTimeString(value.task_due_time)+' </span><div class="todo-tools"><div class="btn-group btn-group-sm '+hidden+ hiddenClass+'"> '+editdeletesection+'  </div> '+((value.task_status=="complete" || value.task_status=="not required")?'<span class="username">'+value.completer.name+' '+value.completer.last_name+'</span>':'')+' </div></div></div></div></li>');

                        //console.log($("#todosection"))
                    } 

                   // if((value.task_due_date >= currentDate) && (value.task_due_date <= nextWeekDate)){
                    if((value.task_due_date >= categData['taskFilterDate']) && (value.task_due_date <= nextWeekDate)){
                    categData['taskFilterDate']
                        //if(dataDueDate == currentDate)
                        if(dataDueDate == currentDate)
                            inputDate="Today";
                        else if( (dataDueDate==tomorrow))
                            inputDate="Tomorrow";
                        else inputDate = moment(dataDueDate).format('ddd, D MMM YYYY');
                        //else inputDate =moment(dataDueDate).format('dddd');

                        if(value.task_status=="complete" || value.task_status=="not required")
                            completedWeekTasks++;
                        totalWeekTasks++; 

                        if(isUserType(['Admin']) || currentUserId==value.task_user_id){
                            
                             if(value.reminders && value.reminders[0]){
                                var remindSection = 'data-remindhours='+value.reminders[0].tr_hours+'  data-remindcheckbox='+value.reminders[0].tr_is_set+'';
                            }
                            else
                                var remindSection = "data-remindhours=''  data-remindcheckbox=''";

                                var hiddenClass = '';
                        }
                        else {
                            var hiddenClass = ' hidden';
                        }

                        var editdeletesection ='<a class="btn edittask preventTaskView" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id='+value.id+' data-task-name="'+value.task_name+'" data-auth-id="'+value.task_user_id+'" data-task-duedate="'+value.task_due_date+'" data-task-time="'+value.task_due_time+'" data-task-categ='+value.task_category+' data-task-repeat='+value.task_tr_id+' '+remindSection+' data-tasknote="'+value.task_note+'"><i class="fa fa-pencil-square-o"></i></a> '+((value.task_tr_id != 0)?'<a class="btn delete-prompt preventTaskView" href="#" style="display: block;" data-original-title="" title=""><i class="fa fa-trash-o"></i></a>':'<a class="btn delLink preventTaskView" data-entity="task" href="'+public_url+'dashboard/'+value.id+'" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask"> <i class="fa fa-trash-o"></i></a>')+'';

                        $("#weeksection").append('<li class="showtaskmodal"><div class="todo-actions clearfix taskDiv"> <div class="btn-group btn-group-xs pull-left preventTaskView"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#" ><span class="checkboxdd"><i '+((value.task_status=="complete")?'class="fa fa-check-square-o"':((value.task_status=="not required")?'class="fa fa-exclamation"':'class="fa fa-square-o"'))+'></i></span>&nbsp; <span class="caret '+cboxclass+'"></span></a><ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown '+cboxclass+'"><li><a data-status=""></a></li><li '+((value.task_status=="complete")?'class="hidden"':'')+'><a data-status="complete" data-taskid='+value.id+'>Complete</a></li><li '+((value.task_status==null)?'class="hidden"':'')+'><a data-status="incomplete" data-taskid='+value.id+'>Incomplete</a></li><li '+((value.task_status=="not required")?'class="hidden"':'')+'><a data-status="not required" data-taskid='+value.id+'>Not required</a></li></ul></div> <div class="padding-horizontal-5 pull-left"><div class="block space5 to-do-link"><span class="desc tasknameclass '+opacity+' '+linethrough+'">'+task_lable+'</span> '+((value.task_tr_id != 0)?'<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>':'')+'<span class="label label-danger top-10 '+opacity+'">  '+ inputDate +'</span></div><div class="block"><span class="desc text-small text-light taskdatetimeclass '+opacity+' '+linethrough+'"><i class="fa fa-clock-o">&nbsp;</i> '+dbTimeToTimeString(value.task_due_time)+' </span><div class="todo-tools"><div class="btn-group btn-group-sm '+hidden+hiddenClass+'"> '+editdeletesection+' </div> '+((value.task_status=="complete" || value.task_status=="not required")?'<span class="username">'+value.completer.name+' '+value.completer.last_name+'</span>':'')+' </div></div></div></div></li>');
                    } 

                    //if((value.task_due_date >= currentDate) && (value.task_due_date <= nextMonthDate)){
                        if((value.task_due_date >= categData['taskFilterDate']) && (value.task_due_date <= nextMonthDate)){
                       // if(dataDueDate == currentDate)
                       if(dataDueDate == currentDate)
                            inputDate="Today";
                        else if( (dataDueDate==tomorrow))
                            inputDate="Tomorrow";
                        else inputDate = moment(dataDueDate).format('ddd, D MMM YYYY');

                        if(value.task_status=="complete" || value.task_status=="not required")
                            completedMonthTasks++;
                        totalMonthTasks++;

                        if(isUserType(['Admin']) || currentUserId==value.task_user_id){
                            
                             if(value.reminders && value.reminders[0]){
                                var remindSection = 'data-remindhours='+value.reminders[0].tr_hours+'  data-remindcheckbox='+value.reminders[0].tr_is_set+'';
                            }
                            else
                                var remindSection = "data-remindhours=''  data-remindcheckbox=''";

                            var hiddenClass = '';
                        }
                        else{
                           var hiddenClass = ' hidden'; 
                        }
                        //<div class="checkbox pull-left clip-check check-primary m-b-0"><input type="checkbox" value="" id='+'month-'+value.id+' data-taskid='+value.id+' class="cbox" '+cboxclass+'><label for='+'month-'+value.id+'><strong></strong></label></div></i>

                        var editdeletesection ='<a class="btn edittask preventTaskView" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id='+value.id+' data-task-name="'+value.task_name+'" data-auth-id="'+value.task_user_id+'" data-task-duedate="'+value.task_due_date+'" data-task-time="'+value.task_due_time+'" data-task-categ='+value.task_category+' data-task-repeat='+value.task_tr_id+' '+remindSection+' data-tasknote="'+value.task_note+'"><i class="fa fa-pencil-square-o"></i></a> '+((value.task_tr_id != 0)?'<a class="btn delete-prompt preventTaskView" href="#" style="display: block;" data-original-title="" title=""><i class="fa fa-trash-o"></i></a>':'<a class="btn delLink preventTaskView" data-entity="task" href="'+public_url+'dashboard/'+value.id+'" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask"> <i class="fa fa-trash-o"></i></a>')+'';
                       


                        $("#monthsection").append('<li class="showtaskmodal"><div class="todo-actions clearfix taskDiv"> <div class="btn-group btn-group-xs pull-left preventTaskView"><a class="btn dropdown-toggle" data-toggle="dropdown" href="#" ><span class="checkboxdd"><i '+((value.task_status=="complete")?'class="fa fa-check-square-o"':((value.task_status=="not required")?'class="fa fa-exclamation"':'class="fa fa-square-o"'))+'></i></span>&nbsp; <span class="caret '+cboxclass+'"></span></a><ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown '+cboxclass+'"><li><a data-status=""></a></li><li '+((value.task_status=="complete")?'class="hidden"':'')+'><a data-status="complete" data-taskid='+value.id+'>Complete</a></li><li '+((value.task_status==null)?'class="hidden"':'')+'><a data-status="incomplete" data-taskid='+value.id+'>Incomplete</a></li><li '+((value.task_status=="not required")?'class="hidden"':'')+'><a data-status="not required" data-taskid='+value.id+'>Not required</a></li></ul></div>  <div class="padding-horizontal-5 pull-left"><div class="block space5 to-do-link"><span class="desc tasknameclass '+opacity+' '+linethrough+'">'+task_lable+'</span> '+((value.task_tr_id != 0)?'<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>':'')+'<span class="label label-danger top-10 '+opacity+'">  '+ inputDate +'</span></div><div class="block"><span class="desc text-small text-light taskdatetimeclass '+opacity+' '+linethrough+'"><i class="fa fa-clock-o">&nbsp;</i> '+dbTimeToTimeString(value.task_due_time)+' </span> <div class="todo-tools"><div class="btn-group btn-group-sm '+hidden+hiddenClass+'"> '+editdeletesection+' </div> '+((value.task_status=="complete" || value.task_status=="not required")?'<span class="username">'+value.completer.name+' '+value.completer.last_name+'</span>':'')+' </div></div></div></div></li>');
                    } 
                });   
            }
            if($('.todotask').parent().hasClass('active'))
                $('.todotask').trigger('click');
            else if($('.thisweektask').parent().hasClass('active'))
                $('.thisweektask').trigger('click');
            else $('.thismonthtask').trigger('click');
            checkboxvalue='';

            toggleWaitShield("hide", 'listTask');
        });

});

$('body').on('click', '.checkboxDropdown li a', function(e){
    toggleWaitShield("show", 'saveTaskStatus');
    var $this = $(this);
    formData = {};
    formData['status'] = $this.attr("data-status");
    formData['taskid'] = $this.attr("data-taskid");
    $.post(public_url+'dashboard/checkbox', formData, function(response){
        var data = JSON.parse(response);
        if(data.ajaxStatus=="success"){
            /*if(data.status == "complete"){
             $('.checkboxdd').html('<i class="fa fa-check-square-o"></i>');
            }
            else if(data.status == "incomplete"){
             $('.checkboxdd').html('<i class="fa fa-square-o"></i>');
            }
            else if(data.status == "not required"){
             $('.checkboxdd').html('<i class="fa fa-exclamation"></i>');
            }*/
            /*$('.checkboxDropdown').find('.clsStatus').each(function(){
                //console.log($(this));
               $(this).removeClass('hidden')
            });*/
            
            checkboxvalue = formData['taskid'];
            var dropDownName = $('.rapidodd').text();
            var triggerChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
            $(triggerChange).trigger('click');

            getUpcomingTasks();

            /*if(($("#tasksReminderModal").data('bs.modal') || {}).isShown){
                getTasksAjax();
            }*/

            //console.log($this.parent());

            
        }
        toggleWaitShield("hide", 'saveTaskStatus');
    });

});

$('body').on('click', '.calendarstatus', function(){
    if(bladeType == "DashboardCalendar"){
        toggleWaitShield("show", 'saveTaskStatus');

        calPopupHelper.trigger('click');
        var $this = $(this);
        //calendarstatusvalue =$this.closest('.eventPopover').data('task-id');
        formData = {};
        formData['status'] = $this.data('taskstatus');
        formData['taskid'] = $this.closest('.eventPopover').data('task-id');
        
        if( isUserType(['Admin']) || (isUserType(['Staff']) && calendarstatusvalue==0 )){ 
                $.post(public_url+'dashboard/checkbox', formData, function(response){
                    var data = JSON.parse(response);
                    if(data.ajaxStatus=="success"){
                        calendarstatusvalue = $this.closest('.eventPopover').data('task-id');
                        $('.calendarstatus').removeClass('active');
                        $this.addClass('active');
                        $this.closest('.popover').popover('hide');
                        getAjaxData();
                    }
                    toggleWaitShield("hide", 'saveTaskStatus');
                })
        }
       //calendarstatusvalue = 0;

    } 
});

$('body').on('click', '.popoverclose', function(){
    $(this).closest('.popover').popover('hide');
});

$( "#categorybtn" ).on( "click", function(e) {
    e.preventDefault();   
    var formData = {};
    var tcat = [];
    var form = $('#categoryForm');
    isFormValid= form.valid();
    if(isFormValid){
        formData['categoryName'] = $('#addcategory').find('.form-control').val();
        formData['hiddenCategId'] = $('input[name=hiddenCategId]').val();

        toggleWaitShield("show", 'saveCat');
        $.post(public_url+'dashboard/category', formData, function(response){
            var data = JSON.parse(response);            
            if(data.categStatus == "created" ){
                $('.rapidoDropdown').append('<li> <a data-catid='+data.id+' data-userid='+data.t_cat_user_id+'>' +data.t_cat_name+ '</a></li>');
                $('#taskcategoryid').append('<option value="'+data.id+'">'+data.t_cat_name+'</option>').selectpicker('refresh'); 
                $('.rapidodd').text(data.t_cat_name);
                formData['categId'] = data.id;
                $('#todosection').empty();
                $('#weeksection').empty();
                $('#monthsection').empty();   
                $('.rapidoDropdown li a[data-catid='+data.id+']').trigger('click'); 
            }
            if(data.categStatus == "updated"){
                var dropDownName = $('.rapidodd').text();
                var triggerChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
                var taskDropdown = $('select#taskcategoryid option').filter(function () { return $(this).html() == dropDownName; });
               
                $('.rapidodd').text(data.t_cat_name);
                var dropDownName2 = $('.rapidodd').text();
                triggerChange.text(dropDownName2);
                taskDropdown.text(dropDownName2);
                formData['categId'] = data.hiddenCategId;
                /* $('#todosection').empty();
                   $('#weeksection').empty();
                   $('#monthsection').empty(); */   
                $('.rapidoDropdown li a[data-catid='+data.hiddenCategId+']').trigger('click');
            }
            toggleWaitShield("hide", 'saveCat');
        }); 

        $('#addcategory').modal('hide');
        
    }
    
});

$('body').on('click', '#taskbtn', function(e){
    e.preventDefault();
    //validation code
    var form = $('#taskForm');
    var isFormValid= form.valid();

    var eventRepeat = form.find('select[name="eventRepeat"]').val();
        if(eventRepeat == 'Weekly'){
            var eventRepeatWeekdaysGroup = form.find('.eventRepeatWeekdays'),
                eventRepeatWeekdays = eventRepeatWeekdaysGroup.find('input[type="checkbox"]:checked');
            if(!eventRepeatWeekdays.length){
                isFormValid = false;
                setFieldInvalid(eventRepeatWeekdaysGroup.closest('.form-group'), 'Please select reccurence days.', eventRepeatWeekdaysGroup.next())
            }
        }
    if(isFormValid){
        //check recurrence
        var taskRepeatValue = $('input[name=taskRepeat]').val();
        //if recurrening
        if(taskRepeatValue==1){
            //alert("ok");
            showPopoverOverModal($(this), editReccurEventPopoverOpt); //show popover
        }
        else{
            x(); //else call submit function
        }
    }
  
});

$('#addcategory').on('show.bs.modal', function (e) {
    //var categoryName = $(this).find('input[name="categoryName"]');
    //categoryName.val(''); 
    //setFieldNeutral(categoryName);  
})

/*$('#addtask').on('hide.bs.modal', function (e) {
    calPopupHelper.trigger("click");
});*/

$('#addtask').on('show.bs.modal', function (e) {
    taskmodal = $("#addtask");
    if($('input[name=taskFormId]').val()==""){
        $("#remindercheck").prop('checked', false);
        clearForm(taskmodal.find('form'));
        showRecurrence();
        $('#myModalLabel').text('Add Task');
        $('#taskbtn').val('Submit');
     
        var taskDueDate = $(this).find("#taskDueDate"); 
        if( bladeType == "Dashboard" ){
            var ddId = $('.rapidodd').text();
            var taskDropdown = $('select#taskcategoryid option').filter(function () { return $(this).html() == ddId; });
            //console.log(taskDropdown)
            if(taskDropdown.length){          
                $('select#taskcategoryid').val(taskDropdown.attr('value')); 
                //console.log($('select#taskcategoryid').val()) 
                //var val = taskDropdown.attr('value');
            }
            var currentdate=moment().format('D MMM YYYY');        
            taskDueDate.val(currentdate);
        }
        if( bladeType == "DashboardCalendar" ){
            setEventDate(taskmodal, 'add');
            setEventTime(taskmodal,'taskDueTime');
        }

        var taskname = $(this).find('input[name="taskName"]');
        resetEventReccur(taskmodal);
        setFieldNeutral(taskname);
        setFieldNeutral(taskDueDate); 
        taskmodal.find('select').selectpicker('refresh'); 
    }

    else {
        $('#myModalLabel').text('Edit Task');
        $('#taskbtn').val('Update');
    }
    showreminder();

    //$('#rcheck').prop('checked', false);
    /* var ddId = $('.rapidodd').text();
    if(taskDropdown.length){
        var val = taskDropdown.attr('value');
    }
    // else
    //    var val = '';
    //  $('select#taskcategoryid').val(val);

    $('#rcheck').prop('checked', false);
    var taskname = $(this).find('input[name="taskName"]');
    var taskDueDate = $(this).find("#taskDueDate");
    var taskCategory = $(this).find('select#taskcategoryid');
    //taskname.val('');
    //taskDueDate.val('');
    //taskCategory.val('');
    //setFieldNeutral(taskname);
    //setFieldNeutral(taskDueDate); 
    //setFieldNeutral(taskCategory); 
    //taskCategory.selectpicker('refresh'); */
})

$('#addtask').on('hide.bs.modal', function () {
    calPopupHelper.trigger("click");
    $('input[name=taskFormId]').val('');
    $('input[name=taskRepeat]').val('');
    //$('input[name=authId]').val('');

    $('select#eventrepeatid').val('');
    setFieldNeutral($('select#eventrepeatid'));
    $('select#eventrepeatintervalid').val('');
    setFieldNeutral($('select#eventrepeatintervalid'));
    $('select#eventrepeatendafteroccurid').val('');
    setFieldNeutral($('select#eventrepeatendafteroccurid'));
    //setFieldNeutral($('select#remindercheckid'));
    //$("#remindercheck").parent().find('select').attr('disabled');
    //$("#remindercheck").prop('checked', false);
    $('.inlineBlckDisp').val('');
    $('.eventRepeatWeekdays input[type="checkbox"]').prop('checked', false);

    $('[name="taskNote"]').val('');

});

$('#addcategory').on('hide.bs.modal', function () { 
    $('input[name=hiddenCategId]').val('');
    $('input[name=categoryName]').val('');
    $('.categorylabel').text('Add Category');
});

$('body').on('click', '.edittask', function(){
    toggleWaitShield("show", 'getTask');
    if( bladeType == "Dashboard" ){
        $this = $(this);
    }
    else if( bladeType == "DashboardCalendar" ){
        $this = $(this).closest('.eventPopover');
    }
   
    if( bladeType == "Dashboard" ){
        if($this.data('task-repeat')!=""){
            $('#rcheck').prop('checked', true);
        }
        else{ 
            $('#rcheck').prop('checked', false);
        }
        showRecurrence();
    }

    else if( bladeType == "DashboardCalendar" ){
        if($this.data('task-repeat')!=""){
            $("#recurrencefield").show();
        }
        else{ 
            $("#recurrencefield").hide();
        }
    }
        
    if( bladeType == "Dashboard" ){
            var currentDate = $.datepicker.formatDate('yy-mm-dd', new Date());
            var addday = moment().add(6,'d') .toDate();
            var addmonth = moment().add(1,'M') .toDate();
            var nextWeekDate = moment(addday).format('YYYY-MM-DD');
            var subtractday = moment(addmonth).subtract(1, 'days');
            var nextMonthDate = moment(subtractday).format('YYYY-MM-DD');
    }

        var taskid = $this.data('task-id');
        var authId = $this.data('auth-id');
        var taskname = $this.data('task-name');
        var taskduedate = $this.data('task-duedate'); 
        taskduedate = moment(taskduedate).format("DD MMM YYYY");
        var taskduetime = $this.data('task-time');
        var taskrepeat = $this.data('task-repeat');
        var remindHours = $this.data('remindhours');
        var reminderCheckbox = $this.data('remindcheckbox');
        var tasknote = $this.data('tasknote');

        if(reminderCheckbox && remindHours)
            $("#remindercheck").prop('checked', true);
        else
            $("#remindercheck").prop('checked', false);

        $('select#remindercheckid').val(remindHours).change();
        $('input[name=taskFormId]').val(taskid);
        $('input[name=taskRepeat]').val(taskrepeat);
        $('input[name=authId]').val(authId);
        $('#taskName').val(taskname);
        $('#taskDueDate').val(taskduedate);
        $('#taskDueTime').val(dbTimeToTimeString(taskduetime));
        $('[name="taskNote"]').val(tasknote);

        var taskcateg = $this.data('task-categ');
        if(loggedInUser.userId != authId){
            taskcateg += '|'+authId;
        }
        $("select#taskcategoryid").val(taskcateg).change();

        formData = {};
        var taskmodal = $("#addtask");
        formData['taskid'] = $this.data('task-id');
    
   
    $.post(public_url+'dashboard/edittask', formData, function(response){
        var data = JSON.parse(response);
        if(data!=""){
            populateEventReccur(taskmodal,data);
        }
        taskmodal.find('select').selectpicker('refresh');  
        toggleWaitShield("hide", 'getTask');  
    });
    
});


$('body').on('click', '.editcategory', function(){
    $('.categorylabel').text('Edit Category');
    var dropDownName = $('.rapidodd').text();
    var categChange = $('.rapidoDropdown li a').filter(function () {return $(this).html() == dropDownName;});
    var ddValue = categChange.data('catid');
    var ddName = categChange.text();
    if(ddName)
        $('#addcategory').find('input[name="categoryName"]').val(ddName);
    $('input[name=hiddenCategId]').val(ddValue);
});

/*$('body').on('change', '.cbox', function(){
    formData={};
    formData['taskId']=$(this).data('taskid');


    if($(this).is(':checked'))
        formData['isCompleted'] = 1;    
    else
        formData['isCompleted'] = 0;  

    $.post(public_url+'dashboard/checkbox', formData, function(response){
        var data = JSON.parse(response);
        if(data.ajaxStatus=="success"){
            var dropDownName = $('.rapidodd').text();
            var triggerChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
            checkboxvalue = formData['taskId'];
            $(triggerChange).trigger('click');
        }
        else 
            console.log("fail");
    });
    
    return !1;
 
});*/

//$('.delete-prompt').click(function(e){
$('body').on("click", '.delete-prompt', function(e){
        e.preventDefault();

        if(bladeType == "Dashboard")
            taskIdForDelete = $(this).closest('.todo-tools').find('.edittask').data('task-id');
        else if(bladeType == "DashboardCalendar"){ 
            taskIdForDelete = $(this).closest('.eventPopover').data('task-id');
            popover = $(this).closest('.popover');
        }

        var delPopover = deleteReccurEventPopoverOpt;
        showPopoverOverModal($(this), delPopover); 
    });


$('body').on("click", '.delete-event', function(e){
        toggleWaitShield("show", 'deleteTask');
        e.preventDefault();
        formData = {};
        var targetEvents = $(this).data('target-event');
        formData['id'] = taskIdForDelete;
        formData['targetEvents'] = targetEvents; 
        
        $.ajax({
                url:public_url+'dashboard/'+formData['id'],
                data: formData,
                type:'delete',
                success:function(msg){
                    calPopupHelper.trigger("click");
                    deletetask(msg);  
                    toggleWaitShield("hide", 'deleteTask');
                }
            });

});

/*$('body').on("click", '.showtaskmodal', function(event){
    var target = $(event.target);
    if(target.hasClass('preventTaskView') || target.closest('.preventTaskView').length )
        return;


    $('#showModal').data('trigger', this).modal('show');
})
*/
//$('body').on("click", '.preventTaskView', function(e){
/*$('.preventTaskView').click(function(e){
    e.stopPropagation();
    e.preventDefault();
    //alert('ok')
})*/

/* Start: showModal Randor value on field */
    /*$('#showModal').on('show.bs.modal', function (e) {
        var modal = $(this);

        var invoker = $(modal.data('trigger'));
        if(invoker.hasClass('showtaskmodal')){
            var row = invoker;
        }
        else
            var row = invoker.closest('.showtaskmodal')
        
        var dataElem = row.find('.edittask')
        var taskID = dataElem.data('task-id');

        modal.find('[name="taskID"]').val(taskID);
        modal.find('input[name="taskFormId"]').val(taskID);
        modal.find('#taskName').text(dataElem.data('task-name'));
        modal.find('#taskDate').text(dbDateToDateString(dataElem.data('task-duedate')));
        modal.find('#taskTime').text(dbTimeToTimeString(dataElem.data('task-time')));
       
        var optionVal = dataElem.data('task-categ');
        var authId = dataElem.data('auth-id');
        if(loggedInUser.userId != authId){
            optionVal += '|'+authId;
        }
        var taskCategoryName = $('#addtask').find('select#taskcategoryid option[value="'+optionVal+'"]').text();
        modal.find('#taskCategory').text(taskCategoryName);

        var tasknote = dataElem.data('tasknote');
        modal.find('[name="taskNote"]').val(tasknote);
        modal.find('#reminderVal').text(dataElem.data('remindhours'));

        if(dataElem.data('task-repeat') != ''){
            formData = {};
            formData['taskid'] = taskID;
            $.post(public_url+'dashboard/edittask', formData, function(response){
                var data = JSON.parse(response);
                if(data != ""){
                    randarValueOnShowModal(modal, data);
                }   
            });
            modal.find('.repeat').removeClass('hidden');
        }
        else{
           modal.find('.repeat').addClass('hidden'); 
        }

        if(dataElem.data('remindhours') != ''){
            modal.find('#reminderVal').text(dataElem.data('remindhours')+' hour(s)');
            modal.find('.rmb').removeClass('hidden');
        }
        else{
            modal.find('.rmb').addClass('hidden');
        }
            
    });*/
/* End: showModal Randor value on field */

/*$('#showModal .submit').click(function(){
    var modal = $('#showModal'),
        form = modal.find('form');
        formData={};
    var note = form.find('[name="taskNote"]').val();
        formData['note'] = note; 
    var taskId = form.find('[name="taskID"]').val();

    if(note != ''){
        $.ajax({
            url : public_url+'dashboard/edit-task-note/'+taskId,
            type : 'POST',
            data : formData,
            success : function(response) {
                //var data = JSON.parse(response);
                if(response == "success"){
                    $('[data-task-id="'+taskId+'"]').data('tasknote',formData['note']);
                    modal.modal('hide');
                }
            },
        });
    }
    else{
        modal.modal('hide');
    }
});*/

function showRecurrence(){   
    if($("#rcheck").is(':checked')){
        $("#recurrencefield").show();
    } 
    else{
        $("#recurrencefield").hide();
    }
}

function showreminder(){ 
    if($("#remindercheck").is(':checked')){
        //alert("checked");
        $('select#remindercheckid').parent().find('button').removeClass('disabled');
        $("#remindercheck").parent().find('select').removeAttr('disabled');
        $("#remindercheck").parent().find('select').prop('required',true);
    } 
    else{
        //alert("unchecked");
        $('select#remindercheckid').parent().find('button').addClass('disabled');
        $("#remindercheck").parent().find('select').attr('disabled');
        $("#remindercheck").parent().find('select').prop('required',false);
        //setFieldNeutral($('select#remindercheckid'));
    }
}

/*$('.delLink').on("click",function(){
    alert("delete");
    var element = $(this);
    var text = "abc";
    confirmDelete(element,text);

});*/


$(document).ready(function(){
    //showUpcomingTasks();
    //getUpcomingTasks();

$('body').on("click", '.update-event', function(e){
        e.preventDefault();
        var targetEvents = $(this).data('target-event')
        //$('#'+eventModal).find('input[name="targetEvents"]').val(targetEvents)
        $('input[name=taskRepeat]').val(targetEvents);
        x(); //call submit fun here
});

    //var cbox=$('.cbox');
    var cbox=$('.checkboxdd i');
    $.each( cbox, function(){
       /* if($(this).is(':checked')){
            $(this).parent().parent().find("span").addClass("opacity25class").end().find(".todo-tools .btn-group").addClass("hidden").end().parent().find(".desc").addClass("textdecorclass");
            $(this).parent().parent().find("span.username").removeClass("opacity25class");
        }*/
        if($(this).hasClass('fa-check-square-o') || $(this).hasClass('fa-exclamation')){
            $(this).closest('.taskDiv').find("span").addClass("opacity25class");
            $(this).closest('.taskDiv').find('.todo-tools .btn-group').addClass("hidden");
            $(this).closest('.taskDiv').find('.desc').addClass("textdecorclass");
            $(this).closest('.taskDiv').find(".username").removeClass("opacity25class");
            $(this).closest('.taskDiv').find(".caret").removeClass("opacity25class");
            $(this).parent().removeClass("opacity25class");

        }
    });

    $( "#rcheck" ).on("change", function(){    
        showRecurrence();
    })

    $( "#remindercheck" ).on("change", function(){    
        showreminder();

    })

    //$('#hiddendateid').on("click",function(){
    //$("#dateid").on("click",function(){
       //alert("dddfdf");
       //$("#hiddendateid").datepicker("show");
       //$(".eventDatepicker").datepicker("show");
       /* $( ".eventDatepicker" ).datepicker({
            showOn: "button",
            buttonText: "abc"
        });
 */
       /* $("#hiddendateid").datepicker({
            showOn: 'button',fzz
            buttonText: 'cvfdsf',
            dateFormat: 'dd/mm/yy',
            constrainInput: true
        }); */ 
   // })    

    $('.eventDatepicker').datepicker({autoclose:true, dateFormat:"d M yy", minDate: 0, startDate: new Date()});

    //var res=(completedTodayTasks/totalTodayTasks)*100;


    completedTodayTasks = $('#todocompletedid').val();
    totalTodayTasks = $('#todototalid').val();
    if(completedTodayTasks==0 || totalTodayTasks==0){
        var result=0
        }
    else{
        var result=(completedTodayTasks/totalTodayTasks)*100;
    }
    $('.progressBar').text(result.toFixed(2)+'%').change();
    $('.widthclass').css("width",result+'%');

    completedWeekTasks = $('#weekcompletedid').val();
    totalWeekTasks = $('#weektotalid').val();
    completedMonthTasks = $('#monthcompletedid').val();
    totalMonthTasks = $('#monthtotalid').val();


    $(".todotask").on( "click", function(){
        if(completedTodayTasks==0 || totalTodayTasks==0)
            var result=0
        else
            var result=(completedTodayTasks/totalTodayTasks)*100;
        $('.progressBar').text(result.toFixed(2)+'%').change();
        $('.widthclass').css("width",result+'%'); 
        /*console.log("todo");
        console.log(completedTodayTasks);
        console.log(totalTodayTasks);
        console.log(result); */
        
    });

    $(".thisweektask").on( "click", function(){
        if(completedWeekTasks==0 || totalWeekTasks==0)
            var result=0
        else
            var result=(completedWeekTasks/totalWeekTasks)*100;
        $('.progressBar').text(result.toFixed(2)+'%').change();
        $('.widthclass').css("width",result+'%');
       /* console.log("week");
        console.log(completedWeekTasks);
        console.log(totalWeekTasks);
        console.log(result); */
    });

    $(".thismonthtask").on( "click", function(){
        if(completedMonthTasks==0 || totalMonthTasks==0)
            var result=0
        else
            var result=(completedMonthTasks/totalMonthTasks)*100;
        $('.progressBar').text(result.toFixed(2)+'%').change();
        $('.widthclass').css("width",result+'%');
        /*console.log("month");
        console.log(completedMonthTasks);
        console.log(totalMonthTasks);
        console.log(result); */
    });

})

function deletetask(msg){
//alert("delete task");
    var data = JSON.parse(msg);
    if(data.status=="deleted"){
        getUpcomingTasks();

        if(bladeType == "Dashboard"){
            var dropDownName = $('.rapidodd').text();
            var triggerChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
            $(triggerChange).trigger('click');
        }
        else if(bladeType == "DashboardCalendar"){
            calPopupHelper.trigger("click");
            getAjaxData();
        }  
    }
}

function deletecategory(msg){
    var data = JSON.parse(msg);
    if(data.status=="deleted"){
        var selectecateg = $('.rapidoDropdown li a[data-catid='+data.id+']');
        selectecateg.parent().remove();
        var dropDownName = "Select Category";
        var triggerChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
        var selectecategtwo = $('select#taskcategoryid option[value='+data.id+']');
        selectecategtwo.remove();
        $(triggerChange).trigger('click');
    }
}



/* start: Set/get event date in various modes */
    function setEventDate(modal, mode){
        //var eventDateDisp = modal.find('.eventDateDisp');
        if(mode == 'add'){
            var selectedDatetimeMoment = moment($('input[name="selectedDatetime"]').val());
            selectedDatetimeMoment = moment(selectedDatetimeMoment).format('D MMM YYYY');
            $("#addtask").find("#taskDueDate").val(selectedDatetimeMoment);
            //eventDateDisp.text(selectedDatetimeMoment.format("ddd, D MMM YYYY"));
        }
        /*else if(mode == 'edit'){
            //eventDateDisp.text(moment(eventObj.find('input[name="date"]').val()).format("ddd, D MMM YYYY"));
            var selectedDatetimeMoment = moment($('input[name="selectedDatetime"]').val());
            selectedDatetimeMoment = moment(selectedDatetimeMoment).format('YYYY-MM-DD');
        }*/
        
        else 
            if(mode == 'submit'){
                var taskdate=$('#taskDueDate').val();
                returnData = {};

                if(taskdate){
            //var eventDateDisp = moment(eventDateDisp.text(), 'ddd, D MMM YYYY'),
            taskDueDate = moment(taskdate);//.format("YYYY-MM-DD");
            //returnData['date'] = eventDateDisp.format("YYYY-MM-DD");
            //returnData['day'] = eventDateDisp.format("dddd");
            returnData['date'] = taskDueDate.format("YYYY-MM-DD");
            returnData['day'] = taskDueDate.format("dddd");
        }
        else{
            returnData['date'] = '';
            returnData['day'] = '';
        }
            
        return returnData;
        }
    }
/* end: Set/get event date in various modes */

/* start: Set event day as default repeat weekend day */
    function setEventdayAsRepeatWeekDay(modal){
        var eventDate = setEventDate(modal, 'submit');
            eventDay = eventDate.day.slice(0,3),
            eventRepeatWeekdays = modal.find('.eventRepeatWeekdays input[type="checkbox"]');

         eventRepeatWeekdays.prop('checked', false);
         if(eventDay)
            eventRepeatWeekdays.filter('[value="'+eventDay+'"]').prop('checked', true)
    }
/* end: Set event day as default repeat weekend day */

/* Set Change Class Step Status */
$('body').on('click','#classdetail',function(){
    toggleWaitShield('show');
    if($(this).is(':checked'))
        var data = '1';
    else
        var data = '0';
    $.ajax({
        url:public_url+'settings/businesses/class-step/change-status',
        data: {is_class_step_complete:data},
        type:'post',
        success:function(response){
            toggleWaitShield('hide');
            response = JSON.parse(response);
            swal({
               title: response.message,
               allowOutsideClick: false,
               showCancelButton: false,
               confirmButtonText: 'Ok',
               confirmButtonColor: '#ff4401',
               cancelButtonText: "No"
            }, 
            function(isConfirm){
                swal.close();
                location.reload();
            });
        }
    });
});

 