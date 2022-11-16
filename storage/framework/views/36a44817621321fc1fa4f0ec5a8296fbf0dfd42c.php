<div class="submit step reviewGoal" data-step='20' id="end" data-next="0" data-value="0">

                     <div class=" Smart-review-session">
                      <div class="row">

                      <div class="heading-text border-head mb-10">

                        <div class="watermark1" data-id="20"><span>20.</span></div>

                        <label class="steps-head">Let's review your goal. Is your  <strong>S.M.A.R.T.</strong> ? </label>
                      </div>

                      <div class="tooltip-sign mb-10">

                       <a href="javascript:void(0)" class="goal-step" data-message="<b>Specific </b> — A clearly defined, clear, concise, and detailed goal. i.e., losing Skg, dunking a
                       basketball, running 21km etc.
                       <br/><br/>
                       <b>Measurable </b> - Measurable to include milestones i.e., Dropping a clothing size measured in cm,
                       losing weight measured in kg, doing x amount of push ups measured in reps etc.
                       <br/><br/>
                       <b>Achievable </b>- Your motivation & commitment to all the necessary changes & improvement you
                       need to implement. Are you WILLING to make all the necessary changes? ONLY if you answer
                       YES to this can you achieve this goal and move onto the next phase of the process.
                       <br/><br/>
                       <b>Relevant </b> — Your goal needs to be relevant and meaningful to YOU. Intrinsic goals, goals for
                       you, are goals that matter to you, thus more likely to be achieved. i.e, If I lose weight, I will feel
                       more confident, when I run 21 km, I will feel proud etc. never set a goal to please anybody but
                       yourself and your life.
                       <br/><br/>
                       <b>Time Dependant </b> — The time you need to commit to achieving this goal. This includes all your
                       milestones and considers all the changes that need to be implemented. Lose 5 kg in 8 weeks,
                       do 10 Pushups in 3 months etc.
                       <br/><br/>
                       You may not know the answers to these questions, but your trainer will help you to make this
                       goal realistic and achievable." data-message1="<b>Specific </b> — A clearly defined, clear, concise, and detailed goal. i.e., losing Skg, dunking a
                       basketball, running 21km etc.
                       <br/><br/>
                       <b>Measurable </b> - Measurable to include milestones i.e., Dropping a clothing size measured in cm,
                       losing weight measured in kg, doing x amount of push ups measured in reps etc.
                       <br/><br/>
                       <b>Achievable </b>- Your motivation & commitment to all the necessary changes & improvement you
                       need to implement. Are you WILLING to make all the necessary changes? ONLY if you answer
                       YES to this can you achieve this goal and move onto the next phase of the process.
                       <br/><br/>
                       <b>Relevant </b> — Your goal needs to be relevant and meaningful to YOU. Intrinsic goals, goals for
                       you, are goals that matter to you, thus more likely to be achieved. i.e, If I lose weight, I will feel
                       more confident, when I run 21 km, I will feel proud etc. never set a goal to please anybody but
                       yourself and your life.
                       <br/><br/>
                       <b>Time Dependant </b> — The time you need to commit to achieving this goal. This includes all your
                       milestones and considers all the changes that need to be implemented. Lose 5 kg in 8 weeks,
                       do 10 Pushups in 3 months etc.
                       <br/><br/>
                       You may not know the answers to these questions, but your trainer will help you to make this
                       goal realistic and achievable."><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>

                        <h5 class="text-center m-t-20 m-b-0"><span> </span> </h5>
                        <br>
                        <div class="row">
                           <div class="vp-form-input-list p-l-0 padding-right-0">
                              
                              <div class="form-group Smart-review-check col-md-6 col-xs-12">
                                
                                  <div class="checkbox ind-checkbox prefTrainSlot">
                                  
                                  <label class="container_check version_2">Specific
                                    <input id="Specific" name="review[]"  value="specific" required type="checkbox" >
                                    
                                    <span class="checkmark"></span>
                                    </label>
                                                                                    
                                </div>
                              </div>
                              
                              <div class="form-group Smart-review-check col-md-6 col-xs-12">
                                
                                  <div class="checkbox ind-checkbox prefTrainSlot">
                                  <label class="container_check version_2">Measurable
                                    
                                    <input id="Measurable" name="review[]" value="measurable" required type="checkbox">
                                    <span class="checkmark"></span>
                                    </label>
                                  
                                  
                                </div>
                              </div>
                              <div class="form-group  Smart-review-check col-md-6 col-xs-12">
                                
                                <div class="checkbox ind-checkbox prefTrainSlot">
                                
                                  <label class="container_check version_2">Attainable
                                    
                                   <input id="Attainable" name="review[]" value="attainable" required type="checkbox" >

                                    <span class="checkmark"></span>
                                    </label>
                                  
                                  
                                </div>
                              </div>
                              <div class="form-group  Smart-review-check col-md-6 col-xs-12">
                                
                                <div class="checkbox ind-checkbox prefTrainSlot">
                                 
                                  <label class="container_check version_2">Relevant
                                  <input id="Relevant" name="review[]"   value="relevant" required type="checkbox">

                                    
                                    <span class="checkmark"></span>
                                    </label>
                                  
                                  
                                </div>
                              </div>
                              <div class="form-group Smart-review-check col-md-6 col-xs-12">
                                
                                <div class="checkbox ind-checkbox prefTrainSlot">
                                
                                  <label class="container_check version_2">Time-Bound
                                    
                                   <input id="Time-Bound" name="review[]" value="time_Bound" required type="checkbox" >

                                    <span class="checkmark"></span>
                                    </label>
                                  
                                  
                                </div>
                              </div>
                              
                             
                            </div>
                           </div>
                     <hr>
                     <div class="form-group">
                        
                           <div class="task_declare">
                              <p class="achieve-description-label"><strong> Name of goal:</strong></p>
                              <p class="goal-name"><?php echo e(!empty($goalDetails) && isset($goalDetails->gb_goal_name) ? ucwords($goalDetails->gb_goal_name) : ''); ?></p>
                              <a href="#" id="edit_goal_details" class="Step-your-goal1  edit_goal_element"><i class="fa fa-pencil editable-pencil" style="color:#ff4401; position: absolute;left: 95%;top: 0%;font-size: 17px;"></i></a>
                              
                              <p class="achieve-description-label"><strong> I want to accomplish:</strong></p>
                              <p class ="achieve-description"><?php echo e(!empty($goalDetails) && isset($goalDetails->gb_achieve_description) ? ucwords($goalDetails->gb_achieve_description) : ''); ?></p>
                           
                              <p class="fail-description-label"><strong> Why is this important:</strong></p>
                              <p class ="fail-description"><?php echo e(!empty($goalDetails) && isset($goalDetails->gb_important_accomplish) ? ucwords($goalDetails->gb_important_accomplish) : ''); ?></p>
                           </div>
                     </div>
                     <hr>
                     <div class="form-group">
                           <div class="task_declare ">
                              <p class="goal-seen"><strong></strong></p>
                              <img id="smartReviewImg" src="" class="img-responsive SYGPreviewPics previewPics hidden"/>
                              <p class="goal-due-date">
                                 <?php echo e(!empty($goalDetails) && isset($goalDetails->gb_goal_seen) ? 'Shared: '.ucwords($goalDetails->gb_goal_seen) : ''); ?><br/>
                                 <?php echo e(!empty($goalDetails) && isset($goalDetails->gb_due_date) ? 'Due date: '.date('D, d F Y', strtotime($goalDetails->gb_due_date)) : ''); ?>

                                 <strong></strong>
                              </p>
                           </div>
                     </div>
                     <hr>
                     <div class="form-group">
                         
                           <div class="show_task-section  ">
                              <h4>Milestone: <a href="#" id="edit_goal_milestone" class="Step-your-goal4  edit_goal_element"> <i class="fa fa-pencil editable-pencil" style="color:#ff4401;"></i></a></h4>
                              <ul class="milestone-label">
                                 <?php if(!empty($milestonesData)): ?>
                                 <?php $__currentLoopData = $milestonesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <a class="Step-your-goal4 milestone-text"><li><?php echo e(isset($milestone->gb_milestones_name) ? ucwords($milestone->gb_milestones_name): ''); ?>

                                    </li></a>
                                  
                                 <p><?php echo e(isset($milestone->gb_milestones_seen) ? ucwords($milestone->gb_milestones_seen): ''); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e(isset($milestone->gb_milestones_date) ? ucwords($milestone->gb_milestones_date): ''); ?></p>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                 <?php endif; ?>
                              </ul>
                           </div>
                     </div>
                    
                     <hr>
                     <div class="form-group">
                         
                         <div class="">
                           <div class="show_task-section well habbit-section ">
                             <h4>Habits: <a href="#" id="habit-edit" data-last-form="true" class="Step-your-goal2 habit-edit"><i class="fa fa-pencil editable-pencil " style="color:#ff4401;"></i></a></h4>
                             <ul class ="habit-label">
                               <?php if(!empty($habitData)): ?>
                               <?php $__currentLoopData = $habitData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $habbit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               <a class="Step-your-goal3 habit-text" data="132"><li><?php echo e(isset($habbit->gb_habit_name) ? ucwords($habbit->gb_habit_name): ''); ?></li></a>
                                 <?php if(!empty($habbit->gb_habit_seen)): ?>
                                   <p><?php echo e(isset($habbit->gb_habit_seen) ? ucwords($habbit->gb_habit_seen): ''); ?></p>
                                 <?php endif; ?>  
                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                               <?php endif; ?>
                             </ul>
                           </div>
                         </div>
                          </div>
                          <hr>
                         <div class="form-group">
                           <div class="show_task-section well task-section">
                             <h4>Tasks: <a href="#" id="task-edit" data-last-form="true" class="Step-your-goal2 task-edit"><i class="fa fa-pencil editable-pencil" style="color:#ff4401;"></i></a></h4>
                             <ul class="tasks-label">
                               <?php if(!empty($taskData)): ?>
                               <?php $__currentLoopData = $taskData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               <a class="Step-your-goal2 task-text" data="202"><li><?php echo e(isset($task->gb_task_name) ? ucwords($task->gb_task_name): ''); ?></li></a>
                                 <?php if(!empty($task->gb_task_seen)): ?>
                                   <p><?php echo e(isset($task->gb_task_seen) ? ucwords($task->gb_task_seen): ''); ?></p>
                                 <?php endif; ?>  
                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                               <?php endif; ?>
                             </ul>
                           </div>
                         </div>
                    
                      <hr>
                     
                    
                     <div class="form-group">
                           <div class="show_task-section well notes-section">
                             <h4>Notes:</h4>                 
                                <span class="gb_goal_notes"><?php echo e(!empty($goalDetails) && isset($goalDetails->gb_goal_notes) ? ucwords($goalDetails->gb_goal_notes) : ''); ?></span>
                           </div>
                         </div> 
                  </div>
                  </div>
<script type="text/javascript">
$(document).ready(function() {
    loadfinalStepData();
    
    var goal_notes = $("#goal_notes").val();

    if(goal_notes != ""){
      goal_notes = goal_notes.replace(/\n/g, "<br>");
      $(".gb_goal_notes").html(goal_notes);
    }
    
    // loadCustomMilestoneList();
});
// function loadCustomMilestoneList(){
//     $.ajax({
//        url: public_url + 'goal-buddy/load-custom-milestone-list',
//        type: "GET",
//        dataType: 'json',
//        processData: false,
//        contentType: false,
//        async:false,
//        success: function (data) {
//          $.each(review_data.milestones, function(k,obj) {
//         mile_id.push(obj.milestones_id);
//         milestoneLabel += '<a class="Step-your-goal4 milestone-text" data ='+obj.milestones_id+'><li>'+ obj.milestones_name +'</li></a>';
//         milestoneLabel += '<p style="margin-left:18px;">'+obj.gb_milestones_seen+'</p>';
//       });
      
//       $('#milestones_id').val(mile_id);
//       $('.milestone-label a').remove();
//       $('.milestone-label p').remove();
//       $('.milestone-label').append(milestoneLabel);
//        }
//    });
// }

function loadfinalStepData(){
    $.ajax({
       url: public_url + 'goal-buddy/load-final-step',
       type: "GET",
       dataType: 'json',
       processData: false,
       contentType: false,
       async:false,
       success: function (data) {

         console.log(data);

         if(data.goalInfo && data.goalInfo.gb_goal_review != undefined){
          var goal_review = data.goalInfo.gb_goal_review.split(',');
          goal_review.map(review => {
            $("input[type=checkbox][value='"+review+"']").attr("checked",true);
          });
         }
         if(data.goalInfo && data.goalInfo.gb_goal_notes != undefined){
          goal_notes = data.goalInfo.gb_goal_notes.replace(/\n/g, "<br>");
          $('.gb_goal_notes').html(goal_notes);
         }  

         showGoalInfo(data.goalInfo);

         //milestones
          var mile_id = [];
          var milestoneLabel="";
         $.each(data.milestone_list, function(k,obj) {
            mile_id.push(obj.id);
            milestoneLabel += '<a class="Step-your-goal4 milestone-text" data ='+obj.id+'><li>'+ obj.gb_milestones_name +'</li></a>';
            milestoneLabel += '<p style="margin-left:18px;">'+obj.gb_milestones_seen+'</p>';
          });
      
          $('#milestones_id').val(mile_id);
          $('.milestone-label a').remove();
          $('.milestone-label p').remove();
          $('.milestone-label').append(milestoneLabel);
         //milestones - end

         //habit
         var listValue = "";
          var habitLabel = "";
          var habitRecurrence = "";
          $('#client-datatable tbody tr').remove();
          $('.task-habit-div').show();
          var taskoptionValue = '<select id="habit_div" name="habit_value" class="selectpicker form-control onchange-set-neutral taskhabit_div_class" required=""><option value="">-- Select --</option>';
          
          $.each(data.habit_list, function(key, value) {

            if(key == 0){
              $('#habit-edit').attr('data-habit-id',value.id);
            }
            if(value.gb_milestones_name!=null)
            var milname=value.gb_milestones_name;
            else
            var milname='';
            habitRecurrence = value.gb_habit_recurrence_type;
            
            listValue += '<tr><td >'+value.gb_habit_name+'</td><td>'+habitRecurrence+'</td><td >'+milname+'</td><td>'+value.gb_habit_seen+'</td><td class="center"><a class="btn btn-xs btn-default tooltips habit-edit"  data-placement="top"data-original-title="Edit" data-habit-id = "'+value.id+'" id="edit_hab_'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-habit" data-placement="top"data-original-title="Delete" data-entity="habit" data-habit-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td></tr>';
            habitLabel += '<a class="Step-your-goal3 habit-text editHbt" data ='+value.id+' ><li>'+ value.gb_habit_name +'</li></a>';
            habitLabel += '<p style="margin-left:18px;">'+value.gb_habit_seen+'</p>';
            
            taskoptionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
          });
          $('.habit-label a').remove();
          $('.habit-label p').remove();
          $('.habit-label').append(habitLabel);
          $('#client-datatable tbody').append(listValue);
          listValue = "";
          
          taskoptionValue += '</select>';
          $('.task-habit-dropdown').html($(taskoptionValue));
         //habit - end

         //task
         isJumpTask = "true";
          $('#client-datatable-task tbody tr').remove();
          var taskListValue = "";
          var taskLabel = "";
          var taskDueDate = "";
          var milestonesValue = "";
          $.each(data.task_list, function(key, value) {

            if(key == 0){
              $('#task-edit').attr('data-task-id',value.id);
            }

            // console.log(value.gb_habit_name);
            if(value.task_habit_name!=null)
            var habitname=value.task_habit_name;
            else
            var habitname='';
            
            $('.task-name').text('Your task '+value.gb_task_name+' has been saved.');
            taskListValue += '<tr><td>'+value.gb_task_name+'</td><td>'+value.gb_task_priority+'</td><td>'+habitname+'</td><td>'+value.gb_task_seen+'<br></td><td class="center"><a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "'+value.id+'" id="edit_task_'+value.id+'"><i class="fa fa-pencil" style="color:#ff4401;"></i></a><a class="btn btn-xs btn-default tooltips delete-task" data-placement="top" data-original-title="Delete" data-entity="task" data-task-id = "'+value.id+'"><i class="fa fa-times" style="color:#ff4401;"></i></a></td>';
            taskLabel += '<a class="Step-your-goal2 task-text editTsk" data ='+value.id+'><li>'+ value.gb_task_name +'</li></a>';
            taskLabel += '<p style="margin-left:18px;">'+value.gb_task_seen+'</p>';
          });
          
          $('.tasks-label a').remove();
          $('.tasks-label p').remove();
          $('.tasks-label').append(taskLabel);
          taskLabel = '';
          $('#client-datatable-task tbody').append(taskListValue);
          listValue = '';
          //i++;
         //task - end
       }
   });
}

/* show Goal info */
    function showGoalInfo(goalData) {
 
      $('.goal-name').text(goalData.gb_goal_name);
     
      if(goalData.gb_achieve_description == ''){

        $('.achieve-description-label').hide();
      } 
      else {
        $('.achieve-description').text(goalData.gb_achieve_description);
      }
      if(goalData.gb_change_life_reason == '' || goalData.gb_change_life_reason == undefined){
        $('.change-life-label').hide();
      } 
      else {
        var changeLifeReason =goalData.gb_change_life_reason;
        var intValArray=changeLifeReason.split(',');
        var lifeChangeArr = [];
        for(var i=0;i<intValArray.length;i++){
          //lifeChangeArr[]=push(intValArray[i]);
        }
        //alert(lifeChangeArr);
        //$('.change-life').text(goalData.gb_change_life_reason);
      }
      if(goalData.gb_fail_description == ''){
        $('.fail-description-label').hide();
      } 
      else {
        // $('.fail-description').text(goalData.gb_fail_description);
        $('.fail-description').text(goalData.gb_important_accomplish);
      }
      if(goalData.gb_goal_seen){
        $('.goal-seen').text('Shared:'+goalData.gb_goal_seen);
      }
      if(goalData.gb_due_date != '0000-00-00'){
        var goalsmartDuedate=moment(goalData.gb_due_date).format("ddd, D MMM YYYY");
        $('.goal-due-date').text('Due date:'+goalsmartDuedate);
      }
    }
</script><?php /**PATH /Users/mahendra/Documents/projects/epicfitlaravelv6/resources/views/Result/partials/goal-steps/custom/step20.blade.php ENDPATH**/ ?>