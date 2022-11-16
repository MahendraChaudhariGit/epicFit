                <div class="step showNext" data-step='19'  data-next="0" data-value="">
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
        
        $.each(data.task_list, function(key, value) {
            if(value.task_habit_name != null)
            var habitname = value.task_habit_name;
            else
            var habitname='';
            
            $('.task-name').html('<div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your task '+value.gb_task_name+' has been saved.</div>');
            
            taskListValue += '<tr><td>'+value.gb_task_name+'</td><td>'+value.gb_task_priority+'</td><td>'+habitname+'</td><td class="center"><a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-task" data-placement="top" data-original-title="Delete" data-entity="task" data-task-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td>';
            
            taskLabel += '<a class="Step-your-goal2 task-text" data ='+value.id+'><li>'+ value.gb_task_name +'</li></a>';
            taskLabel += '<p style="margin-left:18px;">'+value.gb_task_seen+'</p>';
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
    $.ajax({
       url: public_url + 'goal-buddy/load-custom-task-step',
       type: "GET",
       dataType: 'json',
       processData: false,
       contentType: false,
       async:false,
       success: function (data) {
         $('form').html('');
         $('form').html(data.html);
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
</script>