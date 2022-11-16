var due_date_id;
var due_date_value;
var session_data = $('#session-data').val();
var skipManageSales = 0;
var public_url = window.location.origin;
// $(document).ready(function(){

    $(document).on('click','#projectDetail', function(e) {        
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().siblings('#openProject').toggle();
    });    
    $(document).on('click','#openProject', function(e) {
        e.stopPropagation();
    });
    $(document).on('click','body', function() {
        $('#openProject').hide();
    });
    
    // $(".columnEditPopup").hide();
    // $(".columnEdit").on('click',function(){
    //     $(this).parent().siblings('.columnEditPopup').show();
    // })
    // $(".columnEdit").dblon('click',function(){
    //     $(this).parent().siblings('.columnEditPopup').hide();
    // })


    $(document).on('click','.columnEdit', function(e) {        
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().siblings('.columnEditPopup').toggle();
    });    
    $(document).on('click','.columnEditPopup', function(e) {
        e.stopPropagation();
    });
    $(document).on('click','body', function() {
        $('.columnEditPopup').hide();
    });

    $(document).on('click','.deleteComment', function(e) {        
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().siblings('.deleteCommentPopup').toggle();
    });    
    $(document).on('click','.deleteCommentPopup', function(e) {
        e.stopPropagation();
    });
    $(document).on('click','body', function() {
        $('.deleteCommentPopup').hide();
    });


    $(document).on('click','.assignTask', function(e) {  
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().siblings('.assignTaskPopup').toggle();
    });    
    $(document).on('click','.assignTaskPopup', function(e) {
        e.stopPropagation();
    });
    $(document).on('click','body', function() {
        $('.assignTaskPopup').hide();
    });


    $(document).on('click','.priority', function(e) {        
        e.preventDefault();
        e.stopPropagation();
        // $(this).parent().siblings('.priorityPopup').toggle();
        $('.priorityPopup').toggle();
    });    
    $(document).on('click','.priorityPopup', function(e) {
        e.stopPropagation();
    });
    $(document).on('click','body', function() {
        $('.priorityPopup').hide();
    });

    $(document).on('click','.priority-name', function(e) {        
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().siblings('.priority-name-popup').toggle();
    });    
    $(document).on('click','.priority-name-popup', function(e) {
        e.stopPropagation();
    });
    $(document).on('click','body', function() {
        $('.priority-name-popup').hide();
    });

    $(document).on('click','.status-name', function(e) {        
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().siblings('.status-name-popup').toggle();
    });    
    $(document).on('click','.status-name-popup', function(e) {
        e.stopPropagation();
    });
    $(document).on('click','body', function() {
        $('.status-name-popup').hide();
    });

    // $(".dueDate").on('click',function(){

        // var id = $(this).children('.dueDateTaskId').val();
        // due_date_id = $(this).attr('data-id');
        // console.log(due_date_id);
        // localStorage.setItem('id', id);
        // console.log(due_date_id);

    // })
    
    // $(".dueDate").flatpickr({
    //     // mode: 'single',
    //     dateFormat: "Y-m-d",
    //     disableMobile: "true",
    //     // defaultDate: due_date_value,
    //     onChange: function(selectedDates, dateStr, instance) {
    //         var site_url = '/pipeline-process/task/dueDate';
    //         var date = dateStr;
    //         var id = due_date_id;
    //         var csrf = $("#token").val();
    //         $.ajax({
    //             type: "Post",
    //             url: site_url,
    //             data: {'id':id,'duedate':date,'csrf':csrf},
    //             success: function(data)
    //             {
    //                 var html = '';
    //                 html += '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current mr-1.5">'
    //                 html += '<path d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"></path>'
    //                 html += '</svg>'
    //                 html += data.duedate
    //                 $("#due-date"+id).html(data.duedate);
    //                 $(".real-time-duedate-"+id).html(html);
    //                 $("#due-date-"+id).html(data.duedate);
    //                 $(".hidden-div-"+id).show();
    //                 // sessionStorage.removeItem('due_date_value');
    //             }
    //         });  
            
    //     }
    // });
    
    // $(".addColumnPopup").hide();
    $(document).on('touchstart click',".addColumn",function(){
        // var column_id = $(this).attr('column-id');
        // $('.addColumnPopup'+column_id).show();
        $(this).parent().parent().siblings().find('.addColumnPopup').show();
    })
    $(document).on('touchstart click',".hideAddColumnPopup",function(){
        // var column_id = $(this).attr('column-id');
        $(this).parent().parent('.addColumnPopup').hide();
        // $('.addColumnPopup'+column_id).hide();
    })

    // $(".addNewColumnPopup").hide();
    $(document).on('click',".addNewColumn",function(){
        // $(this).parent().parent().parent().siblings('.addNewColumnPopup').show();
        $(".addNewColumnPopup").show();
    })
    $(document).on('click',".hideAddNewColumnPopup",function(){
        // $(this).parent().parent().parent().parent().parent('.addNewColumnPopup').hide();
        $(".addNewColumnPopup").hide();
    })
   
    // $(".commentOnColumnPopup").hide();
    $(document).on('click',".commentOnColumn",function(){
      
        var formData ={};
        due_date_id = $(this).attr('data-id');
        due_date_value = $(this).attr('due-date');
        formData['clientId'] = $(this).find('.clientSalesStep ').data('client-id');
        formData['stepIndex'] = $(this).closest('.dragboxmain').find('.salesCurrentStep').data('step');
        formData['stepName'] = $(this).closest('.dragboxmain').find('.salesCurrentStepName').text();
        projectName = $('.projectName').text();
        modal = $(this).siblings('.commentOnColumnPopup');
        $(".removeinner").sortable("disable");
        $(".dueDate").flatpickr({
            // mode: 'single',
            dateFormat: "Y-m-d",
            disableMobile: "true",
            defaultDate: due_date_value,
            onChange: function(selectedDates, dateStr, instance) {
                
                    var site_url = '/pipeline-process/task/dueDate';
                
                
                var date = dateStr;
                var id = due_date_id;
                var csrf = $("#token").val();
                $.ajax({
                    type: "Post",
                    url: site_url,
                    data: {'id':id,'duedate':date,'csrf':csrf},
                    success: function(data)
                    {
                        var html = '';
                        html += '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current mr-1.5">'
                        html += '<path d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"></path>'
                        html += '</svg>'
                        html += data.duedate
                        $("#due-date"+id).html(data.duedate);
                        $(".real-time-duedate-"+id).html(html);
                        $("#due-date-"+id).html(data.duedate);
                        $(".hidden-div-"+id).show();
                        // sessionStorage.removeItem('due_date_value');
                    }
                });  
                
            }
        });
        if(projectName == 'EPIC SALES PROCESS'){
            if( formData['stepIndex'] != undefined &&  formData['stepIndex'] != ''){
                $.ajax({
                    url: public_url+`/pipeline-process/salesProcessStep`,
                    method: "POST",
                    data: formData,
                    success: function(data){
                        if(data.status == 'success'){
                            
                                console.log(data);
                                if(data.bookData != undefined){
                                    var html ='';

                                    $.each(data.bookData,function(key,obj){
                                        if(obj != null && obj != undefined && obj != ""){
                                     html +=`<div class="col-md-3">
                                        <label class="strong">${key}</label>
                                        <span>${obj}</span>
                                    </div> `;
                                        }
                                    })
                                    $('.bookData').empty().append(html);
                                }
                                $('.salesStep').addClass('hide');
                                $('.salesOptions').removeClass('hide');
                                $('.salesOptionStep').addClass('hide');
                                $('.salesStepCol').removeAttr('data-toggle');
                                $('.salesStepCol').text(formData['stepName']);
                                $('.salesStepCol').closest('div').find('input').prop('checked',true);
                                $('.salesStepCol').attr('data-step-client', formData['clientId']);
                                $('.salesStepCol').attr('data-step-number', data.stepNumb);
                                $('.salesStepCol').attr('data-step-type', data.stepType);
                                $('.salesStepCol').attr('data-book-type', data.bookType);


                            
                        }else{
                            $('.bookData').empty();
                            $('.salesOptions').removeClass('hide');

                            $('.salesStep').removeClass('hide');
                            $('.salesOptions').removeClass('hide');
                            $('.salesOptionStep').removeClass('hide');
                            $('.salesStepCol').text(formData['stepName']);
                            $('.salesStepCol').attr('data-step-client', formData['clientId']);
                            $('.salesStepCol').attr('data-step-number', data.stepNumb);
                            $('.salesStepCol').attr('data-step-type', data.stepType);
                            $('.salesStepCol').attr('data-book-type', data.bookType);
                          
                                $('.salesStepCol').removeAttr('data-toggle');
                                if(formData['stepIndex'] == 1){
                                    $('.salesOptions').addClass('hide');
                                    $('.salesOptionStep').addClass('hide');
                            }
                            $('.salesStepCol').closest('div').find('input').prop('checked',false);
                        }
                    }
                })	
            }
        }
        $(this).siblings('.commentOnColumnPopup').show();
    })
    $(document).on('click',".hideCommentOnColumnPopup",function(){
        $(".removeinner").sortable("enable");
        $('.commentOnColumnPopup').hide();
    })

    
    $("#rangeDate").flatpickr({
        mode: 'range',
        dateFormat: "Y-m-d",
        disableMobile: "true",
        defaultDate: [ $("#start_date").val(), $("#end_date").val()]
    });

    $(document).on('click',".selectColor",function(){
        $(".selectColor").children('svg').remove();
      $(this).html('<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="remove-svg w-4 h-4 fill-current inline-block"> <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path></svg>');
      var color = $(this).attr('rgb-color');
      $("#color").val(color);
    });

    $(document).on('click',".editProject",function(){
        $("#editProjectModal").show();
    })
    $(document).on('click',".hideModal",function(){
        $("#editProjectModal").hide();
    })

    $(".editNewColumnPopup").hide();
    $(document).on('click',".editNewColumn",function(){
        $(this).parent().parent().parent().siblings('.editNewColumnPopup').show();
    })
    $(document).on('click',".hideEditNewColumnPopup",function(){
        // $(this).parent().parent().parent().parent().parent('.editNewColumnPopup').hide();
        $('.editNewColumnPopup').hide();
    })

    // Start Delete Column
    $(".deleteModal").hide();
    $(document).on('click',".deleteNewColumn",function(){
        $(this).parent().parent().parent().siblings('.deleteModal').show();
    })
    $(document).on('click',".hideDeleteNewColumnPopup",function(){
        // $(this).parent().parent().parent().parent().parent('.deleteModal').hide();
        $('.deleteModal').hide();
    })

    $(document).on('click','.deleteColumnId',function(){
        var id = $(this).val();
        var csrf = $("#token").val();
     
            var site_url = '/pipeline-process/column/delete';
        
        
        if(id){
            $.ajax({
                type:'POST',
                url:site_url,
                data:{'id':id,'csrf':csrf},
                success:function(data){
                    window.location.reload();
                }               
            });
        }
    })
    // End Delete Column

    // Start Delete Task
    $(".deleteTaskModal").hide();
    $(document).on('click',".deleteTask",function(){
        $(this).parent().parent().parent().parent().parent().parent().parent().siblings('.deleteTaskModal').show();
    })
    $(document).on('click',".hideDeleteTaskPopup",function(){
        // $(this).parent().parent().parent().parent().parent('.deleteTaskModal').hide();
        $('.deleteTaskModal').hide();
    })

    $(document).on('click','.deleteTaskId',function(){
        var id = $(this).val();
        var csrf = $("#token").val();
    
            var site_url = '/pipeline-process/delete/task';
        
        if(id){
            $.ajax({
                type:'POST',
                url:site_url,
                data:{'id':id,'csrf':csrf},
                success:function(data){
                    window.location.reload();
                }               
            });
        }
    })
    // End Delete Task

    $(".showEditTask").hide();
    $(document).on('touchstart click',".editTask",function(){
        $(this).hide();
        $(this).siblings('.showEditTask').show();
        // $(this).selectpicker('refresh');
    })
    $(document).on('touchstart click',".hideEditTask",function(){
        $(this).parent().parent('.showEditTask').hide();
        $(this).parent().parent().siblings('.editTask').show();
    })
    $(document).on('touchstart click',".saveTask",function(){
        var current = $(this);
        
            var site_url = '/pipeline-process/task/update';
        
        var csrf = $("#token").val();
        // var task_id = $(this).parent().parent('.showEditTask').children().children(".task-id").val();
        var task_id = $(this).attr('task-id');
        // var task_name = $(this).parent().parent('.showEditTask').children().children(".task-name").val();
        var task_name = $(".task-name"+task_id).val();
        $.ajax({
            type:'POST',
            url:site_url,
            data:{'id':task_id,'task_name':task_name,'csrf':csrf},
            success:function(data){
                var taskName = data.task_name;
                current.parent().parent().siblings('.editTask').html(taskName);
                current.parent().parent('.showEditTask').hide();
                current.parent().parent().siblings('.editTask').show();
                $(".task-name-"+task_id).html(taskName);
            }               
        });
    });

    $(document).on('click',".choosePriority",function(){
        var current = $(this);
      
            var site_url = '/pipeline-process/task/priority';
        
        var id = $(this).parent().siblings('.priorityTaskId').val();
        var priority = $(this).attr('priority-value');
        var csrf = $("#token").val();
        $.ajax({
            type:'POST',
            url:site_url,
            data:{'id':id,'priority':priority,'csrf':csrf},
            success:function(data){
                var priority = data.priority;
                current.parent().parent().siblings('.priority-div').children().children('.selectedPriority').html(priority);
                current.parent().parent('.priority-name-popup').hide();
                if(data.priority_no == 4){
                    var priority_css = 'text-red-700';
                }else if(data.priority_no == 3){
                    var priority_css = 'text-orange-500';
                }else if(data.priority_no == 2){
                    var priority_css = 'text-blue-500';
                }else{
                    var priority_css = '';
                }
                $(".priority"+id).html('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-3 h-3 flex-shrink-0 fill-current '+priority_css+'"> <path d="M7.667 12H2v8H0V0h12l.333 2H20l-3 6 3 6H8l-.333-2z"></path> </svg>')
                $(".hidden-div-"+id).show();
                // current.parent().parent('.showEditTask').hide();
                // current.parent().parent().siblings('.editTask').show();
            }               
        });
    })

    $(document).on('click',".contentComplete",function(){
        var currentInstance = $(this);
        if($(this). prop("checked") == true){
            var is_completed = '1';
        }
        else if($(this). prop("checked") == false){
            var is_completed = '0';
        }
       
            var site_url = '/pipeline-process/task/status';
        
        var csrf = $("#token").val();
        var id = $(this).attr('id');
        // var id = $(this).parent().siblings('.checktask').children().children('.showEditTask').children().children('.task-id').val();
        $.ajax({
            type:'POST',
            url:site_url,
            data:{'id':id,'is_completed':is_completed,'csrf':csrf},
            success:function(data){
                // if(is_completed = '1'){
                //     currentInstance.prop("checked", true);
                // }if(is_completed = '0'){
                //     currentInstance.prop("checked", false);
                // }
                
            }               
        });
    });

    $(document).on('click',".comment-btn",function(e){
        e.preventDefault();
        $(this).prop('disabled',true);
        var task_id = $(this).attr('data-id');
        var Instance = $(this);
        var data = new FormData();
        data.append("id", $(this).parent().parent().siblings(".comment_task_id").val());
        data.append("content", $(this).parent().parent().siblings(".comment-text").val());
        var totalfiles = $(this).parent().siblings('.attachmentfile').children().children().children('.filename')[0].files.length;
        var total_file =comment_attachments_array.length;
        // for (var index = 0; index < totalfiles; index++) {
        //     data.append("filename[]", $(this).parent().siblings('.attachmentfile').children().children().children('.filename')[0].files[index]);
        // }
        for (var index = 0; index < total_file; index++) {
            data.append("filename[]", comment_attachments_array[index]);
        }
        // data.append("filename[]", comment_attachments_array);
   
            var site_url = '/pipeline-process/task/comment';
        
        $.ajax({
            url: site_url,
            type:"post",
            data:data,
            contentType: false,
            cache: false,
            processData: false,
            success:function(res){
                Instance.parent().parent().siblings('.comment-text').val('');
                 $('#comment-attachment-preview'+task_id).empty();
                 comment_attachments_array = []
                Instance.parent().parent().parent().parent().siblings('.latestComments').html(res);
            },
        });
    });

    $(document).on('click',".addSubTask",function(){
        var current_instance = $(this);
        $(this).prop('disabled',true);
        var content = $(this).siblings().children('.subTaskVal').val();
        var id = $(this).siblings().children('.subTaskId').val();
        var due_date = $('#add-sub-task-due-date').val();
        var user_id = $('#add-sub-task-assign-user').val();
        var original_user_id = $('#original_user_id').val();

        var site_url = '/pipeline-process/task/subTask';
        
        var csrf = $("#token").val();
        var total_sub_task = $('#total-sub-task-'+id).val();
        var complete_sub_task = $('#complete-sub-task-'+id).val();
        // var total_sub_task = $(this).attr('total-sub-task');
        // var complete_sub_task = $(this).attr('complete-sub-task');
        $.ajax({
            type:'POST',
            url:site_url,
            data:{'id':id,'content':content,'due_date':due_date,'user_id':user_id,'original_user_id':original_user_id,'csrf':csrf},
            success:function(data){
                var tasks = Number(complete_sub_task)+'/'+(Number(total_sub_task)+1);
                var html = '';
                html += '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current mr-1.5">'
                html += '<path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z">'
                html += '</path> </svg>'
                html += tasks
                $(".real-time-task-"+id).html(html);
                current_instance.siblings().children('.subTaskVal').val('');
                $(".assign-user-profile-image-add-sub-task"+id).attr('src',document.location.origin+"/profiles/assign_user.png");
                $('.assign-user-image-add-sub-task'+id).show();
                $('.user-assigned-checbox-add-sub-task'+id).hide();
                $('#add-sub-task-due-date').val('');
                $('#add-sub-task-assign-user').val('');
                current_instance.parent().parent().siblings('.latestSubTask').html(data);
                $(".hidden-div-"+id).show();
                current_instance.prop('disabled',false)
            }               
        });
    });

    $('.editSubTask').hide();
    $(document).on('click',".hideSubTask",function(){
        $(this).hide();
        $(this).siblings('.editSubTask').show();
    })
    $(document).on('click',".hideSubTaskButton",function(){
        $(this).parent().parent('.editSubTask').hide();
        $(this).parent().parent().siblings('.hideSubTask').show();
    })
    $(document).on('click',".editSubTaskButton",function(){
        var current = $(this);
       
            var site_url = '/pipeline-process/task/updateSubTask';
        
        var csrf = $("#token").val();
        var subtaskid = $(this).parent().siblings().children(".subtaskid").val();
        var task_name = $(this).parent().siblings().children(".subtaskname").val();
        $.ajax({
            type:'POST',
            url:site_url,
            data:{'id':subtaskid,'task_name':task_name,'csrf':csrf},
            success:function(data){
                var taskName = data.task_name;
                current.parent().parent().siblings('.hideSubTask').children().html(taskName);
                current.parent().parent('.editSubTask').hide();
                current.parent().parent().siblings('.hideSubTask').show();
            }               
        });
    });

    $(document).on('click',".subTaskComplete",function(){
        var currentInstance = $(this);
        if($(this). prop("checked") == true){
            var is_completed = '1';
        }
        else if($(this). prop("checked") == false){
            var is_completed = '0';
        }
      
            var site_url = '/pipeline-process/task/status';
        
        var csrf = $("#token").val();
        var task_id = $(this).attr('main-task-id');
        var id = $(this).siblings('.sub-Task-Id').val();
        var total_sub_task = $(this).attr('total-sub-task');
        var complete_sub_task = $(this).attr('complete-sub-task');
        $.ajax({
            type:'POST',
            url:site_url,
            data:{'id':id,'task_id':task_id,'is_completed':is_completed,'csrf':csrf},
            success:function(data){
                if(is_completed == '1'){
                    var tasks = (Number(complete_sub_task) + 1)+'/'+(Number(total_sub_task));
                }
                if(is_completed == '0'){
                    var tasks = (Number(complete_sub_task) - 1)+'/'+(Number(total_sub_task));
                }
                
                var html = '';
                html += '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current mr-1.5">'
                html += '<path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z">'
                html += '</path> </svg>'
                html += tasks
                $(".real-time-task-"+task_id).html(html);
                $(".completed-task-"+task_id).html(data);
            }               
        });
    });

    $(document).on('click',".sub-task-duedate",function(){
        var currentInstance = $(this);
        var id = $(this).attr('sub-task-id');
        // due_date = $(this).attr('title');
        localStorage.setItem('id', id);
        localStorage.setItem('currentInstance', currentInstance);

    })
    $(".subTaskDueDate").flatpickr({
        mode: 'single',
        dateFormat: "Y-m-d",
        disableMobile: "true",
        // defaultDate: `2020-12-16`,
        onChange: function(selectedDates, dateStr, instance) {
          
                var site_url = '/pipeline-process/task/dueDate';
            
            var date = dateStr;
            var id =localStorage.getItem('id');
            var currentInstance =localStorage.getItem('currentInstance');
            var csrf = $("#token").val();
            $.ajax({
                type: "Post",
                url: site_url,
                data: {'id':id,'duedate':date,'csrf':csrf},
                success: function(data)
                {
                    currentInstance.css('color','black');
                    localStorage.removeItem('id');
                    localStorage.removeItem('currentInstance');
                }
            });  
            
        }
    });

    $(document).on('click','.assignSubTask', function(e) { 
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().siblings('.assignSubTaskPopup').toggle();
    });    
    $(document).on('click','.assignSubTaskPopup', function(e) {
        e.stopPropagation();
    });
    $(document).on('click','body', function() {
        $('.assignSubTaskPopup').hide();
    });

       // file selected
       $(".comment-attachment").change(function(){
        var validImageTypes = ["image/jpeg","image/png","image/jpg"];
        var file = $(this)[0].files[0];
        attachment_task_id = $(this).attr('data-id');
        comment_attachments_array.push(file);
        var total_file = comment_attachments_array.length;
        $('#comment-attachment-preview'+$(this).attr('data-id')).empty();
        renderAttachment(total_file,$(this).attr('data-id'));
    });

    $(document).on('click','.remove-attachment',function(){

        var attachment_div_id = $(this).attr('data-id');
        // console.log(attachment_div_id);
        comment_attachments_array.splice(attachment_div_id,1);
        $('#attachment-div'+attachment_div_id).remove();
        var total_file = comment_attachments_array.length;
        
        $('#comment-attachment-preview'+attachment_task_id).empty();
        renderAttachment(total_file,attachment_task_id);   

    });

    function renderAttachment(total_file,id)
    {
        for(var i=0;i<total_file;i++)
        {
            $('#comment-attachment-preview'+id).append("<div class='flex flex-col dz-processing dz-image-preview dz-success dz-complete' id='attachment-div"+i+"'><div class='pl-3 pr-4 py-3 flex items-center justify-between text-sm leading-5'><div class='w-0 flex-1 flex items-center'><svg class='flex-shrink-0 h-5 w-5 text-gray-400' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z' clip-rule='evenodd'></path></svg><span class='ml-2 truncate' data-dz-name=''>"+comment_attachments_array[i]['name']+"</span></div><div class='ml-4 flex-shrink-0'><button type='button' class='btn btn-flat btn-xs remove-attachment' data-dz-remove='' data-id="+i+"><svg viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg' class='w-3 h-3 fill-current'><path d='M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z'></path></svg></button></div></div></div>");
        }
    }
    
    $(document).on('keyup',".comment-text",function()
    {
        var content = $(this).val();
        if($(this).val() != '')
        {
            $('#comment-btn'+$(this).attr('id')).removeAttr('disabled');
        }
        else
        {
            $('#comment-btn'+$(this).attr('id')).prop('disabled',true);
        }
    });
    $(document).on('click',".contentComplete",function()
        {
            var id = $(this).attr('id');
            if($(this).prop("checked") == true){
                $('#complete-task-div'+id).show();
            }
            else if($(this).prop("checked") == false){
                $('#complete-task-div'+id).hide();
            }
        });

        $(document).on('click',".select-column",function(){
          
                var site_url = '/pipeline-process/task/change-task-column';
            
            var column_id = $(this).attr('data-id');
            var task_id = $(this).attr('data-item');
            if(task_id && column_id)
            {
                $.ajax({
                        headers: {
                        'X-CSRF-TOKEN': $('#token').val()
                        },
                        type: "POST",
                        url: site_url,
                        data:
                        {
                            task_id: task_id,
                            column_id: column_id,
                        },
                        success: function(data) {
                            if(data.status == true)
                            {
                                location.reload();
                            }
                        }
                        }); 
            }
        });

// });

    $(document).on("keyup",".assign-users-search", function() {
    var value = $(this).val().toLowerCase();
    $("#filter-assign-users a").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
    $(document).on("keyup", ".assign-users-sub-task-search",function() {
    var value = $(this).val().toLowerCase();
    $("#filter-assign-users-sub-task a").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

    $(document).on("keyup",".assign-users-add-sub-task-search", function() {
    var value = $(this).val().toLowerCase();
    $("#filter-assign-users-add-sub-task a").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

    $(document).on('click',".assign-user",function(){
       
            var site_url = '/pipeline-process/task/assign-user';
        
        var task_id = $(this).attr('id');
        var user_id = $(this).attr('data-id');
        var original_user_id = $(this).attr('user-data-id');
        var user_name = $(this).attr('data-item');
        $('.assign-user-image'+task_id).show();
        $('.user-assigned-checbox'+task_id).hide();
        $(this).find('.assign-user-image'+task_id).hide();
        $(this).find('.user-assigned-checbox'+task_id).show();
        $(".assign-user-name"+task_id).text($(this).attr('data-item'));
        $(".showassignuser").html(user_name);
        if(task_id)
        {
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('#token').val()
                },
                type: "POST",
                url: site_url,
                data:
                {
                    task_id: task_id,
                    user_id: user_id,
                    original_user_id: original_user_id,
                },
                success: function(data)
                {
                    if(data.status == true){
                        url = document.location.origin+"/uploads/thumb_"+data.staff.profile_picture;
                        var html = '';
                        html += '<img src="'+url+'" '
                        html += 'alt="'+data.staff.first_name +" "+ data.staff.first_name+'" title="'+data.staff.first_name +" "+ data.staff.first_name+'" class="avatar avatar-xs">'
                       $(".real-time-image-"+task_id).html(html);
                       $(".hidden-div-"+task_id).show();
                    }
                    
                }
            }); 
        }
    });

    $(document).on('click',".assign-user-sub-task",function(){
     
            var site_url = '/pipeline-process/task/assign-user';
        
        var sub_task_id = $(this).attr('id');
        var user_id = $(this).attr('data-id');
        $('.assign-user-image-sub-task'+sub_task_id).show();
        $('.user-assigned-checbox-sub-task'+sub_task_id).hide();
        $(this).find('.assign-user-image-sub-task'+sub_task_id).hide();
        $(this).find('.user-assigned-checbox-sub-task'+sub_task_id).show();
        $(".assign-user-profile-image-sub-task"+sub_task_id).attr('src','');
        $(".assign-user-profile-image-sub-task"+sub_task_id).attr('src',document.location.origin+"/uploads/thumb_"+$(this).attr('data-item').trim());
        if(sub_task_id)
        {
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('#token').val()
                },
                type: "POST",
                url: site_url,
                data:
                {
                    task_id: sub_task_id,
                    user_id: user_id,
                },
            }); 
        }
    });

    $(".add-sub-task-due-date-calendar").flatpickr({
        dateFormat: "Y-m-d",
        disableMobile: "true",
        onChange: function(selectedDates, dateStr, instance) 
        {
            var date = dateStr;
            $('#add-sub-task-due-date').val(date);
        }
    });

    $(document).on('click',".assign-user-add-sub-task",function(){
        var sub_task_id = $(this).attr('id');
        var user_id = $(this).attr('data-id');
        var original_user_id = $(this).attr('user-data-id');
        $('#add-sub-task-assign-user').val(user_id);
        $('#original_user_id').val(original_user_id);
        $('.assign-user-image-add-sub-task'+sub_task_id).show();
        $('.user-assigned-checbox-add-sub-task'+sub_task_id).hide();
        $(this).find('.assign-user-image-add-sub-task'+sub_task_id).hide();
        $(this).find('.user-assigned-checbox-add-sub-task'+sub_task_id).show();
        $(".assign-user-profile-image-add-sub-task"+sub_task_id).attr('src','');
        $(".assign-user-profile-image-add-sub-task"+sub_task_id).attr('src',document.location.origin+"/uploads/thumb_"+$(this).attr('data-item').trim());
    });

    $(document).on('click','#visibility-only-me',function(){
        staff_member_id = [];
        $( "#teamMembers-tab" ).attr('data-toggle','');
        $('[name="staff_member_id[]"]').val(staff_member_id);
        // $('.staff_member').find('.checkbox_div').removeClass("bg-green-400 text-green-100");
        // $('.staff_member').find('.show_checkbox').hide();
    
        });
        $('#visibility-team-members').on('click',function(){
        $( "#teamMembers-tab" ).attr('data-toggle','tab');
        });
    
    $(document).on("keyup",".staff_members_search", function() 
    {
        var value = $(this).val().toLowerCase();
        $("#filter-staff-member a").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    $(document).on("click",".staff_member",function(){
        if (!$(this).find('.checkbox_div').hasClass("bg-green-400 text-green-100")) 
        {
            $(this).find('.checkbox_div').addClass("bg-green-400 text-green-100");
            $(this).find('.show_checkbox').show();
            staff_member_id.push($(this).attr('data-id'));
            $('[name="staff_member_id[]"]').val(staff_member_id);
        }
        else
        {
            $(this).find('.checkbox_div').removeClass("bg-green-400 text-green-100");
            $(this).find('.show_checkbox').hide();
            if($.inArray($(this).attr('data-id'),staff_member_id) != -1)
            {
             staff_member_id.splice($.inArray($(this).attr('data-id'),staff_member_id),1);
             $('[name="staff_member_id[]"]').val(staff_member_id);
            }
        }
        });

        // function checkMe(id,column_id,group,count) {
        //     const checkBoxes = document.querySelectorAll(".myCheckBox"+id);
        //     const progress = document.querySelector(".progress-inner"+column_id+id);
        //     const checklistProgressInterval = 100 / checkBoxes.length;
        //     if($(".myCheckBox"+id+group).prop("checked") == true){
        //         $(".myCheckBox1"+id+group). prop("checked",true)
        //         var url = '/pipeline-process/task/sales-preference-add';
        //     }
        //     else if($(".myCheckBox"+id+group). prop("checked") == false){
        //         $(".myCheckBox1"+id+group).prop("checked",false)
        //         var url = '/pipeline-process/task/sales-preference-remove';
        //     }
        //     let width = 0;
        //     for(let i = 0; i < (checkBoxes.length); i++){
        //         if(checkBoxes[i].checked){
        //             width += checklistProgressInterval;
        //         }
        //     }
        //     if(progress != null){
        //         $(".progress-inner"+column_id+id).show();
        //         progress.style.width = `${width}%`;
        //     }
        //     saleProcess(progress,id,column_id,group,count,url,width)

        // }

        // function salesCheck(id,column_id,group,count) {
        //     const checkBoxes = document.querySelectorAll(".myCheckBox1"+id);
        //     const progress = document.querySelector(".progress-inner"+column_id+id);
        //     const checklistProgressInterval = 100 / checkBoxes.length;
        //     if($(".myCheckBox1"+id+group). prop("checked") == true){
        //         $(".myCheckBox"+id+group). prop("checked",true)
        //         var url = '/pipeline-process/task/sales-preference-add';
        //     }
        //     else if($(".myCheckBox1"+id+group). prop("checked") == false){
        //         $(".myCheckBox"+id+group). prop("checked",false)
        //         var url = '/pipeline-process/task/sales-preference-remove';
        //     }
        //     let width = 0;
        //     for(let i = 0; i < (checkBoxes.length); i++){
        //         if(checkBoxes[i].checked){
        //             width += checklistProgressInterval;
        //         }
        //     }
        //     if(progress != null){
        //         $(".progress-inner"+column_id+id).show();
        //         progress.style.width = `${width}%`;
        //     }
        //     saleProcess(progress,id,column_id,group,count,url,width);

        // }

        // function saleProcess(progress,id,column_id,group,count,url,width){
        //     $.ajax({
        //         headers: {
        //         'X-CSRF-TOKEN': $('#token').val()
        //         },
        //         type: "POST",
        //         url: url,
        //         data:
        //         {
        //             task_id: id,
        //             column_id: column_id,
        //             group: group,
        //             count: count,
        //         },
        //         success: function(res){
        //             $("#conformation"+column_id+id).html(res)
        //             // if(res != ''){
        //             //     $(".progress-inner"+column_id+id).show();
        //             //     progress.style.width = `${width}%`;
        //             // }else{
        //             //     $(".progress-inner"+column_id+id).removeAttr("style").attr("style","display:none");
        //             // }
        //         }
        //     }); 
        // }

        // $(document).on('click',".check-column",function(){
        //     var task_id = $(this).attr('task-id');
        //     var column_id = $(this).attr('column-id');
        //     var data_from = $(this).attr('data-from');
        //     var proccess_data = [];
        //     if($(this).prop("checked") == true){
        //         $('.myCheckBox'+task_id).each(function(){
        //             $(this).prop('checked',true);
        //             $('.myCheckBox1'+task_id).prop('checked',true);
        //             proccess_data.push($(this).attr('proccess-data'));
        //         })
        //         $(".progress-inner"+column_id+task_id).removeAttr("style").attr("style","width:100%");
        //         $(".progress-inner"+column_id+task_id).show();
        //         var url = '/pipeline-process/task/complete-all-epic-task';
        //     }
        //     else if($(this).prop("checked") == false){
        //         $('.myCheckBox'+task_id).each(function(){
        //             $(this).prop('checked',false);
        //             $('.myCheckBox1'+task_id).prop('checked',false);
        //         })
        //         $(".progress-inner"+column_id+task_id).hide();
        //         var url = '/pipeline-process/task/incomplete-all-epic-task';
        //         proccess_data = [];
        //     }
        //     $.ajax({
        //         headers: {
        //         'X-CSRF-TOKEN': $('#token').val()
        //         },
        //         type: "post",
        //         url: url,
        //         data: {
        //             'task_id':task_id,
        //             'column_id':column_id,
        //             'proccess_data':proccess_data,
        //             'data_from': data_from
        //         },
        //         success: function(res)
        //         {
        //             $("#conformation"+column_id+task_id).html(res)
        //         }
        //     });  
        // });

        function processCheckMe(task_id,column_id,group,count) {
            const checkBoxes = document.querySelectorAll(".processCheckBox"+task_id+column_id);
            const progress = document.querySelector(".progress-inner"+column_id+task_id);
            const checklistProgressInterval = 100 / checkBoxes.length;
            if($(".processCheckBox"+task_id+group+column_id).prop("checked") == true){
                $(".processCheckBox1"+task_id+group+column_id). prop("checked",true)
                
                    var url = '/pipeline-process/task/epic-process-add';
                
            }
            else if($(".processCheckBox"+task_id+group+column_id). prop("checked") == false){
                $(".processCheckBox1"+task_id+group+column_id).prop("checked",false)
              
                    var url = '/pipeline-process/task/epic-process-remove';
                
            }
            let width = 0;
            for(let i = 0; i < (checkBoxes.length); i++){
                if(checkBoxes[i].checked){
                    width += checklistProgressInterval;
                }
            }
            if(progress != null){
                $(".progress-inner"+column_id+task_id).show();
                progress.style.width = `${width}%`;
            }
            
            epicProcess(progress,task_id,column_id,group,count,url,width);

        }

        function processCheckMe1(task_id,column_id,group,count) {
            const checkBoxes = document.querySelectorAll(".processCheckBox1"+task_id+column_id);
            const progress = document.querySelector(".progress-inner"+column_id+task_id);
            const checklistProgressInterval = 100 / checkBoxes.length;
            if($(".processCheckBox1"+task_id+group+column_id).prop("checked") == true){
                $(".processCheckBox"+task_id+group+column_id). prop("checked",true)
                
                    var url = '/pipeline-process/task/epic-process-add';
                
            }
            else if($(".processCheckBox1"+task_id+group+column_id). prop("checked") == false){
                $(".processCheckBox"+task_id+group+column_id).prop("checked",false)
            
                    var url = '/pipeline-process/task/epic-process-remove';
                
            }
            let width = 0;
            for(let i = 0; i < (checkBoxes.length); i++){
                if(checkBoxes[i].checked){
                    width += checklistProgressInterval;
                }
            }
            if(progress != null){
                $(".progress-inner"+column_id+task_id).show();
                progress.style.width = `${width}%`;
            }
            epicProcess(progress,task_id,column_id,group,count,url,width);

        }

        function epicProcess(progress,task_id,column_id,group,count,url,width){
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('#token').val()
                },
                type: "POST",
                url: url,
                data:
                {
                    task_id: task_id,
                    column_id: column_id,
                    group: group,
                    count: count,
                },
                success: function(res){
                    $("#conformation"+column_id+task_id).html(res)
                    // if(res != ''){
                    //     $(".progress-inner"+column_id+task_id).show();
                    //     progress.style.width = `${width}%`;
                    // }else{
                    //     $(".progress-inner"+column_id+task_id).removeAttr("style").attr("style","display:none");
                    // }

                }
            }); 
        }

        $(document).on('click',".check-column1",function(){
            projectName = $('.projectName').text();
            if(projectName == 'EPIC SALES PROCESS'){
                var  $this = $(this),
                stepNumb = $this.data('sales-step-number'),
               modal =  $this.closest('.commentOnColumnPopup');
               clientId =modal.find('.salesStepCol').data('step-client');
            //    prcoessStepBooked(stepNumb, clientId);
            if(stepNumb != ''){
                var formData = {};
              
                formData['stepNumb'] = stepNumb;
                formData['clientId'] = clientId;
                var stepType =    modal.find('.salesStepCol').data('step-type');
        
                                    bookType =  modal.find('.salesStepCol').data('book-type');
                if(!$(this).is(':checked')){ //Step completed already
                    swal({
                        title: 'Do you want to mark step as incomplete?',
                        allowOutsideClick: false,
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        confirmButtonColor: '#ff4401',
                        cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            formData['action'] = 'incomplete';
                             if($.inArray(formData['stepNumb'], sessionSteps()) > -1){ //Team/indiv step
                               if(stepType == 'attend'){ //Remove manual
                                       
                                      updateSalesStepAjax(formData ,$this);
                                       
                                    }
                                    else{ //Remove booking
                                        parent.salesProcessStepNumb =stepNumb;
                                        extra = '&removeStepType='+stepType+'&removeBookType='+bookType;
                                        openSubview('calendar', 'clientId='+clientId+extra);
                                        subviewOpen = true;
                                        modal.hide();
                                    }
        
                                
                            }
                            else
                                updateSalesStepAjax(formData, $this);
                        }else{
                            window.location.reload();
                        }
                    });
                }else if(stepType == 'attend' && $(this).is(':checked')){
                    formData['action'] = 'complete';
                    updateSalesStepAjax(formData ,$this);
                 
                }else if(stepType == 'booked' && $(this).is(':checked')){
                    formData['action'] = 'complete';
                        parent.salesProcessStepNumb =stepNumb;
                        var extra ='';
                        openSubview($this, 'clientId='+formData['clientId']+extra);
                        modal.hide();
                        subviewOpen = true;
                    
                }
            }

            }else{
            var task_id = $(this).attr('task-id');
            var column_id = $(this).attr('column-id');
            // var data_from = $(this).attr('data-from');
            var total_sales = $(this).attr('total-sales');
            var proccess_data = [];
            if($(this).prop("checked") == true){
                $('.processCheckBox'+task_id+column_id).each(function(){
                    $(this).prop('checked',true);
                    $('.processCheckBox1'+task_id+column_id).prop('checked',true);
                    proccess_data.push($(this).attr('proccess-data'));
                })
                $(".progress-inner"+column_id+task_id).removeAttr("style").attr("style","width:100%");
                $(".progress-inner"+column_id+task_id).show();
               
                    var site_url = '/pipeline-process/task/complete-all-epic-task';
                
            }
            else if($(this).prop("checked") == false){
                $('.processCheckBox'+task_id+column_id).each(function(){
                    $(this).prop('checked',false);
                    $('.processCheckBox1'+task_id+column_id).prop('checked',false);
                })
                $(".progress-inner"+column_id+task_id).hide();
             
                    var site_url = '/pipeline-process/task/incomplete-all-epic-task';
                
                proccess_data = [];
            }
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('#token').val()
                },
                type: "post",
                url: site_url,
                data: {
                    'task_id':task_id,
                    'column_id':column_id,
                    'proccess_data':proccess_data,
                    // 'data_from': data_from,
                    'count': total_sales
                },
                success: function(res)
                {
                    $("#conformation"+column_id+task_id).html(res)

                    }
                }); 
            }
            
        });

        

         $(document).on('change', '.DetailsTab', function(){
            var task_id = $(this).attr('task-id');
            if(this.value == "tabComments-"+task_id) {
            $('.tab_pane_tasks-'+task_id).removeClass('active in');
            $('.tab_pane_attachments-'+task_id).removeClass('active in');
            $('.tab_pane_sales-'+task_id).removeClass('active in');
            $('.tab_pane_comments-'+task_id).addClass('active in');
        } else if(this.value == "tabTasks-"+task_id) {
            $('.tab_pane_comments-'+task_id).removeClass('active in');
            $('.tab_pane_attachments-'+task_id).removeClass('active in');
            $('.tab_pane_sales-'+task_id).removeClass('active in');
            $('.tab_pane_tasks-'+task_id).addClass('active in');
        } else if(this.value == "tabAttachments-"+task_id) {
            $('.tab_pane_comments-'+task_id).removeClass('active in');
            $('.tab_pane_tasks-'+task_id).removeClass('active in');
            $('.tab_pane_sales-'+task_id).removeClass('active in');
            $('.tab_pane_attachments-'+task_id).addClass('active in');
        } else if(this.value == "tabSales-"+task_id) {
            $('.tab_pane_comments-'+task_id).removeClass('active in');
            $('.tab_pane_tasks-'+task_id).removeClass('active in');
            $('.tab_pane_attachments-'+task_id).removeClass('active in');
            $('.tab_pane_sales-'+task_id).addClass('active in');
        }
        });

        var visibility = $('input[name="visibility"]:checked').val();
        if(visibility == 2){
            $("#team-tab").prop('disabled',true)
        }else{
            $("#team-tab").prop('disabled',false)
        }
        $(document).on('click','input[name="visibility"]',function(){
            visibility = $(this).val();
            if(visibility == 2){
                $("#team-tab").prop('disabled',true)
            }else{
                $("#team-tab").prop('disabled',false)
            }
        })
        $(document).on('change', '#editGeneralTab', function(){
            if(this.value == "tabGeneral") {
                $('.tab_pane_team').removeClass('active in');
                $('.tab_pane_general').addClass('active in');
            } else if(this.value == "tabTeam") {
                $('.tab_pane_general').removeClass('active in');
                $('.tab_pane_team').addClass('active in');
                
            }
        });

        $(document).on("click touchstart",".save-task",function(){
            var column_id = $(this).attr('column-id');
            var task_name = $("#content"+column_id).val();
            if(task_name != ''){
                $(this).prop('disabled',true);
                $("#submit-form"+column_id).submit();
            }
        })

        $('select[name="contactStatus"]').on('change',function(){
        var modal = $(this).closest('.commentOnColumnPopup');
            var contactStatus = $(this).val(),
            contactMadeOpt = modal.find('select[name="contactMadeOpt"]'),
            contactMadeOptGroup = contactMadeOpt.closest('.form-group');
            cbFields = modal.find('#cbFields'),
            dt = new Date(),
            time = dt.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
        
           
            if(contactStatus == 'contacted'){
                if(contactMadeOptGroup.hasClass('hidden')){
                contactMadeOptGroup.removeClass('hidden');
                }
                if(!cbFields.find('.callback').hasClass('hidden')){
                        cbFields.find('.callback').addClass('hidden');
                        cbFields.find('.getTimeField').addClass('hidden');
                }
            }else if(contactStatus == 'messaged' || contactStatus == 'noanswer'){
                cbFields.find('.callback').removeClass('hidden');
                cbFields.find('.getTimeField').removeClass('hidden');
                cbFields.find('input[name="contactMadeTime"]').val(time);
                if(!contactMadeOptGroup.hasClass('hidden')){
                contactMadeOptGroup.addClass('hidden');
                }
            }
           
        });

        $('select[name="contactMadeOpt"]').on('change',function(){
            var modal = $(this).closest('.commentOnColumnPopup');
            var contactStatus = modal.find('select[name="contactStatus"]').val(),
            contactMadeOpt = modal.find('select[name="contactMadeOpt"]');
            cbFields = modal.find('#cbFields'),
            clientStatusGroup = modal.find('select[name="clientStatusInContactNotes"]').closest('.form-group');
             var dt = new Date();
            var time = dt.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
            if(contactStatus == 'contacted'){
            
                if(contactMadeOpt.val() == 'cb')
                {
                    cbFields.find('.callback').removeClass('hidden');
                    cbFields.find('.getTimeField').removeClass('hidden');
                    cbFields.find('input[name="contactMadeTime"]').val(time);
                    if(!clientStatusGroup.hasClass('hidden')){
                        clientStatusGroup.addClass('hidden');
                       
                    }
                }
                if(contactMadeOpt.val() == 'ni')
                {
                    clientStatusGroup.removeClass('hidden');
                    if(!cbFields.find('.callback').hasClass('hidden')){
                        cbFields.find('.callback').addClass('hidden');
                        cbFields.find('.getTimeField').addClass('hidden');
                    }
                    
                }
                if(contactMadeOpt.val() == 'book'){
                    if(!cbFields.find('.callback').hasClass('hidden')){
                        cbFields.find('.callback').addClass('hidden');
                        cbFields.find('.getTimeField').addClass('hidden');
                    }
                    if(!clientStatusGroup.hasClass('hidden')){
                        clientStatusGroup.addClass('hidden');
                       
                    }
                }
            }
          
        });

        $('.create-note').on('click',function(){
            $('.noteWrap').removeClass('hidden');
            $('.create-note').addClass('hidden');
            $('.show-note').removeClass('hidden');

        })

        $('.show-note').on('click',function(){
            $('.noteWrap').addClass('hidden');
            $('.show-note').addClass('hidden');
            $('.create-note').removeClass('hidden');

        })


        $('.salesContactSubmit').on('click',function(){
            var modal = $(this).closest('.commentOnColumnPopup');
            $this = $(this);
            var formData = {}; 
            var taskId = $(this).data('task-id');
            var status = modal.find('select[name="contactStatus"]').val();
            var notes =  modal.find('#contactNote').val();
            var notesVal = (notes == 'Write note here...' || !notes)?'':notes;
            var ckDate =  modal.find('input[name="contactCbkDate"]').val();
            formData['status'] = status;
            if(formData['status'] == 'contacted'){
				formData['contactMadeOpt'] = modal.find('select[name="contactMadeOpt"]').val();

				if(formData['contactMadeOpt'] == 'ni')
					formData['clientStatus'] =  modal.find('select[name="clientStatusInContactNotes"]').val();
            }
            if(formData['status'] != 'contacted' || formData['contactMadeOpt'] == 'cb' ||formData['status'] == 'messaged' || formData['status'] == 'noanswer'){
				/*if(formData['status'] != 'contacted'){*/
					formData['cbkdate'] = moment(ckDate, 'ddd, D MMM YYYY').format('YYYY-MM-DD');
					formData['contactTime'] = timeStringToDbTime(modal.find('input[name="contactMadeTime"]').val());
					/*}*/
				}
			formData['note'] = notesVal;
            formData['clientId'] = $(this).data('client-id');
            var extra = '';
            $.ajax({
                url: public_url+'/sales/contact-note/save',
                method: "POST",
                data: formData,
                success: function(data){
                    var data = JSON.parse(data);
                    if(data.status == "added"){
                            if(formData['status'] == 'contacted' && formData['contactMadeOpt'] == 'book'){
                             parent.salesProcessStepNumb =2;
                             $('.salesCurrentStep').each(function(){
                                 if($(this).data('step') == 2){
                                    var colId = $(this).closest('.dragboxmain').attr('id');
                                    if(colId)
                                    {   
                                        $.ajax({
                                        headers: {
                                        'X-CSRF-TOKEN': $('#token').val()
                                        },
                                        type: "POST",
                                        url: "update-column-tasks-state",
                                        data:
                                        {
                                            column_tasks_ids:taskId,
                                            column_id: colId
                                        },
                                        
                                        success: function(data){
                                            openSubview($this, 'clientId='+formData['clientId']+extra);
                                             modal.hide();
                                                
                                            }
                                        });
                                        
                                    }
                                 }
                             })
                             
                            
                            }else{
                             swal('Data added Successfully.');
                             modal.hide();
                            }
                        
                            
                    }
                }
            });
        })
        function openSubview(elem, data){
            //var subview = $('#subview');
            var subview = '';
            if(typeof elem == 'string')
                var targetSubview = elem;
            else{
                var targetSubview = elem.data('target-subview');
                if(elem.is("[data-subview]")){
                    subviewId = elem.data('subview');
                    subview = $('#'+subviewId);
                }
            }
            if(!subview)
                subview = $('#clientSubview');

                var src = 'dashboard/calendar-new';
           
               
            
        
            if(typeof data == 'undefined' || !data)
                data = '';
            else
                data = '&'+data;
                
            subview.find("iframe").attr("src", public_url+src+'?subview=true'+data);
            subview.show("slide", {direction:"right"}, 200);
        }

        function updateSalesStepAjax(formData){
           
            // if(formData.action == 'complete' && formData.stepNumb == lastStep && !('status' in formData)){
            // 	var emm = $('#editMembSub');
            // 	emm.find('input[name="stepNumb"]').val(lastStep);
            // 	$("#editMembSub").modal('show');
            // 	return false;
            // }
            // else{
                skipManageSales = 1;
            $.post(public_url+'/clients/sales-process/update', formData, function(data){
                var data = JSON.parse(data);
                if(data.status == "updated"){
                    swal({
                        title: `Client status updated to ${data.changeStatus.split('|')[1]}`,
                        allowOutsideClick: false,
                        showCancelButton: false,
                        confirmButtonText: 'Okay',
                        confirmButtonColor: '#ff4401',
                        cancelButtonText: "No"
                    }, 
                    function(isConfirm){
                        if(isConfirm){
                            window.location.reload();
                        }
                    });
                }
            });
            // }
        }

        function timeStringToDbTime(timeString){
            return moment(timeString, 'h:mm A').format("HH:mm:ss");
        }
        

        // function prcoessStepBooked(stepNumb,clientId){
            
        //     if(stepNumb != '' && clientId != undefined){
        //         var formData ={};
        //         formData['clientId'] = clientId;
        //         formData['stepNumb'] = stepNumb;

        //         $.ajax({
        //             url: `${public_url}pipeline-process/bookeStep`,
        //             method: "POST",
        //             data: formData,
        //             success: function(data){
        //                 console.log(data);
        //                 if(data){
        //                     var stepsBooked = data;
        //                     if(stepsBooked){
        //                         stepsBooked = JSON.parse(stepsBooked)
        //                         console.log(stepsBooked);
        //                         if($.inArray(stepNumb, stepsBooked) >= 0){
        //                             window.location.href = public_url+"dashboard/calendar-new";
        //                             // e.stopImmediatePropagation();
        //                             return false;
        //                         }
        //                     }

                            
        //                 }
        //             }
        //         })	
            
                
        //     }
        // }

        function teamBookingSteps(){
            return [6,7,8,9,10];
        }
        function teamAttendSteps(){
            return [11,23,24,25,26];
        }
        function indivBookingSteps(){
            return [12,13,14,15,16];
        }
        function indivAttendSteps(){
            return [17,19,20,21,22];
        }
        function sessionSteps(){
            return teamBookingSteps().concat(teamAttendSteps()).concat(indivBookingSteps()).concat(indivAttendSteps());
        }