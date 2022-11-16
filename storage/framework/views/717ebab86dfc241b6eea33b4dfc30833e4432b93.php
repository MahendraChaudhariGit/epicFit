<?php 
   $total_task = 0;
   $task_flag = 0;
   $taskDetails = null;
   $existingTaskNotes = null;
   $existingHabitID = null;
   if(isset($goalDataNew['current_task_step'])){
      $task_flag = $goalDataNew['current_task_step'];
   }
   if(isset($goalDataNew['taskDetails']) && count($goalDataNew['taskDetails']) > 0){
      $total_task = count($goalDataNew['taskDetails']);
      foreach($goalDataNew['taskDetails'] as $key => $task){
         if($key == $task_flag){
            $taskDetails = $task;
            if(isset($taskDetails->gb_habit_id)){
               $existingHabitID = $taskDetails->gb_habit_id;
            }
            // echo '<pre>';
            // var_dump($taskDetails);
            // echo '</pre>';
            // exit;
           // $habitData

         }
      }
   }
?>

<div class="step data-step taskNext newTaskData" data-step="18" data-value="0">

  <div class="row">

    <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
    <div class="heading-text border-head mb-10">

      <div class="watermark1" data-id="18"><span>18.</span></div>

      <label class="steps-head">Is this task associated with a habit of this <strong>EPIC </strong>Goal? *</label>
    </div>

    <div class="tooltip-sign mb-10">

       <a href="javascript:void(0)" class="goal-step" 
         data-message="In certain cases, a certain habit may have multiple task that fall under it, an example of this would be physical activity habit may include resistance training, cardiovascular endurance and stretching and recovery routines."
         data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark" style="color: #f94211;"></i></a>
    </div>

  </div>
   <div class="form-group">
      <div class="input-body mb ml-0">
         <div class="row">
           <div class="col-sm-12 col-xs-12">
             <?php
             $m_gb_habit_value = [];
             if(isset($habitData)) {
               foreach ($habitData as $habitval) {
                 if(isset($taskDetails) && $habitval->id==$taskDetails->gb_habit_id) {
                   $m_gb_habit_value[] = $habitval->id;
                 }
               }
             }
             ?>
             <input type="hidden" name="associatedHabitWithTask" value="">
             <input type="hidden" name="goalTaskData" value="">
             <input type="hidden" name="task_id" id="task_id">
          
             <div class="task-habit-dropdown pli-23 dropdown-menu-ml-0">
               <?php if((isset($habitData))&&(count($habitData) > 0)): ?>
               <select onchange="validateGoalTask()" data-toggle="tooltip" title="Is this task associated with a habit of this goal?" id="habit_div" name="habit_value" class="form-control onchange-set-neutral taskhabit_div_class" ng-init="habit_value=<?php echo e(json_encode($m_gb_habit_value)); ?>" ng-model="habit_value" ng-keypress="pressEnter($event)" required>
                 <option value="">-- Select --</option>
                  <?php $__currentLoopData = $habitData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $habitval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <?php if(isset($taskDetails) &&  $habitval->id==$taskDetails->gb_habit_id): ?>
                     <option value="<?php echo e($habitval->id); ?>" selected=""><?php echo e($habitval->gb_habit_name); ?></option>
                     <?php else: ?>
                     <option value="<?php echo e($habitval->id); ?>"><?php echo e($habitval->gb_habit_name); ?></option>
                     <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               </select>
               <?php endif; ?>
             </div> <!-- end task habit dropdown -->
             
           </div>
         </div>
         
         
       </div>
   </div>

      <label> Name Your Task 
         
         <a href="javascript:void(0)" class="goal-step" 
         data-message="This relates directly to the task you need to complete and needs to be descriptive and simple. 
         <br/><br/>
         Examples of tasks may be:
         <br/><br/>
             • Resistance training, <br/>
             • Daily walk, <br/>
             • Morning Sit up routine."
         data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a>
      </label>
   <div class="form-group">
      <input data-toggle="tooltip" title="" type="text" class="form-control" id="SYG3_task" ng-model="SYG3_task"  ng-init="SYG3_task='<?php echo e(isset($taskDetails) ? $taskDetails->gb_task_name : null); ?>'" ng-keypress="pressEnter($event)" value="<?php echo e(isset($taskDetails) ? $taskDetails->gb_task_name : null); ?>" name="SYG3_task" required>
   </div>

     
      <label> Notes related to this task
        
        <a href="javascript:void(0)" class="goal-step" 
         data-message="In this section you want to make notes related to this task that may assist you in ensuring the are done when they are supposed to be done and the importance of them.
         <br/><br/>
         <b>Frequency Per Week</b> – How often a week you need to do a certain task
         <br/><br/>
         <b>Intensity of training</b> – How hard you need to be doing physical activity if any
         <br/><br/>
         <b>Duration of activity</b> – Duration of physical activity if any
         <br/><br/>
         Requirements of hypertrophy or limiting muscle mass. - "
         data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a>
      </label>

   <div class="form-group outborder">
      <textarea onblur="validateGoalTask()" data-toggle="tooltip" title="" data-autoresize rows="3" id="SYG_task_note" name="SYG_task_note" ng-model="SYG_task_note" ng-init="SYG_task_note='<?php echo e(isset($taskDetails) ? $taskDetails->gb_task_note : null); ?>'" placeholder="" class="form-control"><?php echo e(isset($taskDetails) ? $taskDetails->gb_task_note : null); ?></textarea>
   </div>

   <div class="">
       <label data-toggle="tooltip" title="">Priority 
          
          <a href="javascript:void(0)" class="goal-step" 
         data-message="This related to how important this task is to your success of achieving your desired <span style='color:#f64c1e;'>RESULT</span> and may also be determined by if it is a habit already or if it may be a difficult task with barriers that may be difficult to overcome.
         <br/><br/>
         <b>Low</b> - Not particularly important or already a habit or behaviour
         <br/><br/>
         <b>Normal</b> - Important but not critical
         <br/><br/>
         <b>High</b> - Important and required to achieve goal
         <br/><br/>
         <b>Urgent</b> - Critical part of the goal and has priority over other tasks."
         data-message1="<b>Low</b> - Not particularly important or already a habit or behaviour
         <br/><br/>
         <b>Normal</b> - Important but not critical
         <br/><br/>
         <b>High</b> - Important and required to achieve goal
         <br/><br/>
         <b>Urgent</b> - Critical part of the goal and has priority over other tasks."><i class="fa fa-question-circle question-mark"></i></a>
         </label>

   </div>
   <div class="form-group">
      <label class="container_radio version_2">Low
      <input type="radio" name="Priority" value="Low" class="required"  <?php echo e(isset($taskDetails) && $taskDetails->gb_task_priority == 'Low' ? 'checked' : ''); ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   <div class="form-group">
      <label class="container_radio version_2">Normal
      <input type="radio" name="Priority" value="Normal" class="required"  <?php echo e(isset($taskDetails) && $taskDetails->gb_task_priority == 'Normal'? 'checked' : ''); ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   <div class="form-group">
      <label class="container_radio version_2">High
      <input type="radio" name="Priority" value="High" class="required" <?php echo e(isset($taskDetails) && $taskDetails->gb_task_priority == 'High'? 'checked' : ''); ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   <div class="form-group">
      <label class="container_radio version_2">Urgent
      <input type="radio" name="Priority" value="Urgent" class="required"  <?php echo e(isset($taskDetails) && $taskDetails->gb_task_priority == 'Urgent'? 'checked' : ''); ?>>
      <span class="checkmark"></span>
      </label>
   </div>
  <div class="habitStep">
      <div class="">
         <label data-toggle="tooltip" title="">Task Recurrence 
            
            <a href="javascript:void(0)" class="goal-step" 
         data-message="<b>Daily</b> - This is if you are implementing the training into your daily routine with no recovery days
         <br/><br/>
         <b>Weekly</b> - If you have one or more recovery days in a week.
         <br/><br/>
         <b>Monthly</b> - If it related to a specific day each month may be testing"
         data-message1="<b>Daily</b>—This is if you are implementing training into your daily routine with no recovery days
         <br/><br/>
         <b>Weekly</b>—If you have one or more physical activity days in a week
         <br/><br/>
         <b>Monthly</b>— If it related to a specific day each month maybe testing
         <br/><br/>
         Tasks are critical to any Lifestyle Design Change and always need to be addressed fully to ensure that all aspects of the tasks are understood."><i class="fa fa-question-circle question-mark"></i></a>
         </label>

      </div>
      <!-- <div class="form-group tsk_rec_daily">
         <label class="container_radio version_2">Daily
         <input type="radio" name="SYG_task_recurrence" value="daily" class="required">
         <span class="checkmark"></span>
         </label>
      </div> -->
      <div class="form-group">
         <label class="container_radio version_2">Weekly
         <input type="radio" name="SYG_task_recurrence" value="weekly" class="weekly"  <?php echo e(isset($taskDetails) && $taskDetails->gb_task_recurrence_type == 'weekly'? 'checked' : ''); ?>>
         <span class="checkmark"></span>
         </label>
      
         <div class="showDayBox row showbox" id="task_recurrence_week_div" <?php if((isset($taskDetails))&&($taskDetails->gb_task_recurrence_type=='weekly')): ?> style="display: block;" <?php else: ?> style="display: none;"   <?php endif; ?>>
            <div class="form-group col-xs-4 col-sm-3">
               <label class="container_check version_2">Mon
               
               <input name="task_recurrence_week[]" id="taskEventRepeatWeekdays0" class="taskEventRepeatWeekdays hidden" value="Monday" type="checkbox"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Monday', explode(',', $taskDetails->gb_task_recurrence_week)))): ?> checked  <?php endif; ?> >
               <span class="checkmark"></span>
               </label>
            </div>
            <div class="form-group col-xs-4 col-sm-3">
               <label class="container_check version_2">Tue
               
            <input name="task_recurrence_week[]" id="taskEventRepeatWeekdays1" class="taskEventRepeatWeekdays hidden" value="Tuesday" type="checkbox"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Tuesday', explode(',', $taskDetails->gb_task_recurrence_week)))): ?> checked  <?php endif; ?> >
               <span class="checkmark"></span>
               </label>
            </div>
            <div class="form-group col-xs-4 col-sm-3">
               <label class="container_check version_2">Wed
                  <input name="task_recurrence_week[]" onclick="validateGoalTask()" id="taskEventRepeatWeekdays2" class="taskEventRepeatWeekdays hidden" value="Wednesday" type="checkbox"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Wednesday', explode(',', $taskDetails->gb_task_recurrence_week)))): ?> checked  <?php endif; ?> >
               <span class="checkmark"></span>
               </label>
            </div>
            <div class="form-group col-xs-4 col-sm-3">
               <label class="container_check version_2">Thu
                  <input name="task_recurrence_week[]" id="taskEventRepeatWeekdays3" class="taskEventRepeatWeekdays hidden" value="Thursday" type="checkbox"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Thursday', explode(',', $taskDetails->gb_task_recurrence_week)))): ?> checked  <?php endif; ?> >
               <span class="checkmark"></span>
               </label>
            </div>
            <div class="form-group col-xs-4 col-sm-3">
               <label class="container_check version_2">Fri
                  <input name="task_recurrence_week[]" id="taskEventRepeatWeekdays4" class="taskEventRepeatWeekdays hidden" value="Friday" type="checkbox"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Friday', explode(',', $taskDetails->gb_task_recurrence_week)))): ?> checked  <?php endif; ?> >
               <span class="checkmark"></span>
               </label>
            </div>
            <div class="form-group col-xs-4 col-sm-3">
               <label class="container_check version_2">Sat
            
                  <input name="task_recurrence_week[]" onclick="validateGoalTask()" id="taskEventRepeatWeekdays5" class="taskEventRepeatWeekdays hidden" value="Saturday" type="checkbox"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Saturday', explode(',', $taskDetails->gb_task_recurrence_week)))): ?> checked  <?php endif; ?> >
               <span class="checkmark"></span>
               </label>
            </div>
            <div class="form-group col-xs-4 col-sm-3">
               <label class="container_check version_2">Sun
                  <input name="task_recurrence_week[]" id="taskEventRepeatWeekdays6" class="taskEventRepeatWeekdays hidden" value="Sunday" type="checkbox"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Sunday', explode(',', $taskDetails->gb_task_recurrence_week)))): ?> checked  <?php endif; ?> >
               <span class="checkmark"></span>
               </label>
            </div>
         </div>
   
      </div>
      <div class="form-group">
      <label class="container_radio version_2">Monthly
      <input type="radio" name="SYG_task_recurrence" value="monthly" class="monthly" <?php echo e(isset($taskDetails) && $taskDetails->gb_task_recurrence_type == 'monthly'? 'checked' : ''); ?>>
      <span class="checkmark"></span>
      <div class="showMonthBox month" id="task_recurrence_month_div" <?php if((isset($taskDetails))&&($taskDetails->gb_task_recurrence_type=='monthly')): ?> style="display: block;" <?php else: ?> style="display: none;"   <?php endif; ?>>
         <div style="display:flex;align-items: center">
            Day&nbsp;&nbsp;
            <select name="gb_task_recurrence_month" id="gb_task_recurrence_month" class="month-date-task" value="">
               <?php for($i = 1; $i <= calDaysInMonth(); $i++): ?>
               <?php 
                  $selected = "";
                  if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_month == $i)){
                     $selected = "selected";
                  }
               ?>
               <option value ="<?php echo e($i); ?>" <?php echo e($selected); ?>><?php echo e($i); ?></option>
               <?php endfor; ?>
             </select> of every month
            &nbsp;&nbsp;
         </div>
      </div>
      </label>
   </div>
  </div>

   <div class="">
       
         <label data-toggle="tooltip" title=""> Who can view your task? 
          
          <a href="javascript:void(0)" class="goal-step" 
         data-message="<b>Everyone</b> - Share details and habits with friends and family.
         <br/><br/>
         <b>Just Me</b> - Only show me details and habits."
         data-message1="<b>Select Friends</b>—Share details with a select few friends who you believe will be supportive and who will hold you accountable to your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>.
         <br/><br/>
         <b>Everyone</b>– Share details of your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> with all friend and family on your Timeline 
         <br/><br/>
         <b>Just Me</b>—Only show me details of my <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>
         <br/><br/>
         The <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> online platform is designed to allow you to connect and interact with fellow team members and like-minded individuals on the same or similar journey to your own. 
         <br/><br/>
         Having others view your habits gives you the opportunity to be given feedback and positive encouragement throughout your journey. 
         <br/><br/>
         Allow others to celebrate your success with you!
           <br/><br/>
         Accountability is key, fellow <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> members can help hold you accountable when it comes to attending training sessions, staying on target with healthy balanced eating and completing your goal related tasks which relate to your milestones and progression.
           <br/><br/>
         <b>T.E.A.M</b> Together Everyone Achieves More"><i class="fa fa-question-circle question-mark"></i></a>
         </label>

   </div>
   <div class="form-group">
      <label class="container_radio version_2">Select Friends
         <input type="radio" name="SYG3_see_task" id="SYG3_see_task" value="Selected friends"  <?php echo e(isset($taskDetails) && $taskDetails->gb_task_seen == 'Selected friends'?'checked':''); ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   <div class="form-group">
      <label class="container_radio version_2">Everyone
         <input type="radio" name="SYG3_see_task" id="SYG3_see_task0" value="everyone"  <?php echo e(isset($taskDetails) && $taskDetails->gb_task_seen == 'everyone'?'checked':''); ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   <div class="form-group">
      <label class="container_radio version_2">Just Me
         <input type="radio" name="SYG3_see_task" id="SYG3_see_task2" value="Just Me" <?php echo e(isset($taskDetails) && $taskDetails->gb_task_seen == 'Just Me'?'checked':''); ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   <div class="form-group <?php echo e(isset($taskDetails) && $taskDetails->gb_task_selective_friends != ''?'':'hidden'); ?>">
      <input type="text" class="form-control autocomplete" id="SYG3_selective_friends" ng-keypress="pressEnter($event)" value="<?php echo e(isset($taskDetails) && $taskDetails->gb_task_selective_friends != ''?$taskDetails->gb_task_selective_friends:''); ?>" name="SYG3_selective_friends" aria-invalid="false">
   </div>
   <div class="habitStep">
   <div class="">
      <label  data-toggle="tooltip" title="">Send Email / Message
         
         <a href="javascript:void(0)" class="goal-step" 
         data-message="Overdue
         <br/><br/>
         Due
         <br/><br/>
         None"
         data-message1="<b>When Overdue</b>—If tasks have not been met
         <br/><br/>
         <b>Daily</b>—Send me messages related to tasks daily
         <br/><br/>
         <b>Weekly</b>—Send me messages related to tasks weekly
         <br/><br/>
         <b>Monthly</b>—Send me messages related to tasks monthly
         <br/><br/>
         <b>None</b>—Do not send me anything related to tasks
         <br/><br/>
         At <span style='color:#f64c1e;'><b>EPIC</b></span> we know that life can sometimes get the better of the best of us, reminders keep you accountable for your actions, time management, and overall results. 
         <br/><br/>
         It is important to come to the realisation that actions that may seem insignificant such as missing a training session, skipping a meal, or altering guidelines can have adverse accumulative effects on your overall progress, the earlier it is noticed by you by being given a reminder from the <span style='color:#f64c1e;'><b>EPIC</b> TEAM</span> the sooner you can get on top of it and get back on track.
         <br/><br/>
         Approach every habit related to milestones with the utmost importance as if others are relying on you, because YOU are relying on YOU!"><i class="fa fa-question-circle question-mark"></i></a>
      </label>

   </div>
   <div class="form-group">
      <label class="container_radio version_2">When Overdue
      <input type="radio" name="creattask-send-mail" value="when_overdue"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "when_overdue")): ?> checked <?php endif; ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   <div class="form-group">
      <label class="container_radio version_2">
         Daily
         <input type="radio" name="creattask-send-mail" value="daily" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "daily")): ?> checked <?php endif; ?> class="daily">
         <span class="checkmark"></span>
         <div class="showTimeBox" <?php if((isset($taskDetails))&&($taskDetails->gb_task_reminder == 'daily')): ?> style="display: block;" <?php else: ?> style="display: none;"   <?php endif; ?>>
            <select id="daily_time_task">                                  
                  <option value="1"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "1")): ?> selected <?php endif; ?>>1:00 am</option>
                  <option  value="2" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "2")): ?> selected <?php endif; ?>>2:00 am</option>
                  <option value="3"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "3")): ?> selected <?php endif; ?>>3:00 am</option>
                  <option value="4"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "4")): ?> selected <?php endif; ?>>4:00 am</option>
                  <option value="5"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "5")): ?> selected <?php endif; ?>>5:00 am</option>
                  <option value="6"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "6")): ?> selected <?php endif; ?>>6:00 am</option>
                  <option value="7"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "7")): ?> selected <?php endif; ?>>7:00 am</option>
                  <option value="8"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "8")): ?> selected <?php endif; ?>>8:00 am</option>
                  <option value="9"  <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "9")): ?> selected <?php endif; ?>>9:00 am</option>
                  <option value="10" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "10")): ?> selected <?php endif; ?>>10:00 am</option>
                  <option value="11" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "11")): ?> selected <?php endif; ?>>11:00 am</option>
                  <option value="12" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "12")): ?> selected <?php endif; ?>>12:00 PM</option>
                  <option value="13" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "13")): ?> selected <?php endif; ?>>1:00 PM</option>
                  <option value="14" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "14")): ?> selected <?php endif; ?>>2:00 PM</option>
                  <option value="15" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "15")): ?> selected <?php endif; ?>>3:00 PM</option>
                  <option value="16" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "16")): ?> selected <?php endif; ?>>4:00 PM</option>
                  <option value="17" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "17")): ?> selected <?php endif; ?>>5:00 PM</option>
                  <option value="18" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "18")): ?> selected <?php endif; ?>>6:00 PM</option>
                  <option value="19" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "19")): ?> selected <?php endif; ?>>7:00 PM</option>
                  <option value="20" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "20")): ?> selected <?php endif; ?>>8:00 PM</option>
                  <option value="21" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "21")): ?> selected <?php endif; ?>>9:00 PM</option>
                  <option value="22" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "22")): ?> selected <?php endif; ?>>10:00 PM</option>
                  <option value="23" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "23")): ?> selected <?php endif; ?>>11:00 PM</option>
                  <option value="24" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "24")): ?> selected <?php endif; ?>>12:00 am</option>
            </select>
        </div>
      </label>
   </div>

      
           <div class="form-group">
            <label class="container_radio version_2">
               Weekly

               <input type="radio" id="creattask_send_Weekly" name="creattask-send-mail" class="weekly"  value="weekly" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "weekly")): ?> checked <?php endif; ?>>
               <span class="checkmark"></span>
               <div class="showDayBox" <?php if((isset($taskDetails))&&($taskDetails->gb_task_reminder == 'weekly')): ?> style="display: block;" <?php else: ?> style="display: none;"   <?php endif; ?>>
                  
                  <select id="weekly_day_task">
                        <option value="Mon" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "Mon")): ?> selected <?php endif; ?>>Mon</option>
                        <option value="Tue" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "Tue")): ?> selected <?php endif; ?>>Tue</option>
                        <option value="Wed" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "Wed")): ?> selected <?php endif; ?>>Wed</option>
                        <option value="Thu" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "Thu")): ?> selected <?php endif; ?>>Thu</option>
                        <option value="Fri" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "Fri")): ?> selected <?php endif; ?>>Fri</option>
                        <option value="Sat" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "Sat")): ?> selected <?php endif; ?>>Sat</option>
                        <option value="Sun" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "Sun")): ?> selected <?php endif; ?>>Sun</option>
                  </select>
              </div>
            </label>
         </div>
         <div class="form-group">

            <label class="container_radio version_2">
               Monthly
               <input type="radio" name="creattask-send-mail" value="monthly" class="monthly" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "monthly")): ?> checked <?php endif; ?>>
               <span class="checkmark"></span>
               <div class="showMonthBox" <?php if((isset($taskDetails))&&($taskDetails->gb_task_reminder == 'monthly')): ?> style="display: block;" <?php else: ?> style="display: none;"   <?php endif; ?>>
                  <select id="month_date_task">
                     <option value="1" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "1")): ?> selected <?php endif; ?>>1</option>
                     <option value="2" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "2")): ?> selected <?php endif; ?>>2</option>
                     <option value="3" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "3")): ?> selected <?php endif; ?>>3</option>
                     <option value="4" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "4")): ?> selected <?php endif; ?>>4</option>
                     <option value="5" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "5")): ?> selected <?php endif; ?>>5</option>
                     <option value="6" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "6")): ?> selected <?php endif; ?>>6</option>
                     <option value="7" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "7")): ?> selected <?php endif; ?>>7</option>
                     <option value="8" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "8")): ?> selected <?php endif; ?>>8</option>
                     <option value="9" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "9")): ?> selected <?php endif; ?>>9</option>
                     <option value="10" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "10")): ?> selected <?php endif; ?>>10</option>
                     <option value="11" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "11")): ?> selected <?php endif; ?>>11</option>
                     <option value="12" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "12")): ?> selected <?php endif; ?>>12</option>
                     <option value="13" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "13")): ?> selected <?php endif; ?>>13</option>
                     <option value="14" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "14")): ?> selected <?php endif; ?>>14</option>
                     <option value="15" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "15")): ?> selected <?php endif; ?>>15</option>
                     <option value="16" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "16")): ?> selected <?php endif; ?>>16</option>
                     <option value="17" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "17")): ?> selected <?php endif; ?>>17</option>
                     <option value="18" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "18")): ?> selected <?php endif; ?>>18</option>
                     <option value="19" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "19")): ?> selected <?php endif; ?>>19</option>
                     <option value="20" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "20")): ?> selected <?php endif; ?>>20</option>
                     <option value="21" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "21")): ?> selected <?php endif; ?>>21</option>
                     <option value="22" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "22")): ?> selected <?php endif; ?>>22</option>
                     <option value="23" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "23")): ?> selected <?php endif; ?>>23</option>
                     <option value="24" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "24")): ?> selected <?php endif; ?>>24</option>
                     <option value="25" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "25")): ?> selected <?php endif; ?>>25</option>
                     <option value="26" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "26")): ?> selected <?php endif; ?>>26</option>
                     <option value="27" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "27")): ?> selected <?php endif; ?>>27</option>
                     <option value="28" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "28")): ?> selected <?php endif; ?>>28</option>
                     <option value="29" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "29")): ?> selected <?php endif; ?>>29</option>
                     <option value="30" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "30")): ?> selected <?php endif; ?>>30</option>
                     <option value="31" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_time == "31")): ?> selected <?php endif; ?>>31</option>
                  </select>
              </div>
            </label>
         </div>

    <label>Get Notifications Through ? </label>
   <div class="form-group">
      <label class="container_radio version_2">I want to get reminder notification through email.
      <input type="radio" name="creattask-send-epichq" value="email" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_epichq == "email")): ?> checked <?php endif; ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   <div class="form-group">
      <label class="container_radio version_2">I want to get reminder notification through chat.
      <input type="radio" name="creattask-send-epichq" value="epichq" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_epichq == "epichq")): ?> checked <?php endif; ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   <div class="form-group">
      <label class="container_radio version_2">I want to get reminder notification through email and chat both.
      <input type="radio" name="creattask-send-epichq" value="email-epichq" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_epichq == "email-epichq")): ?> checked <?php endif; ?>>
      <span class="checkmark"></span>
      </label>
   </div>

   <div class="form-group">
      <label class="container_radio version_2">None
      <input type="radio" name="creattask-send-epichq" value="none" <?php if(isset($taskDetails) && ($taskDetails->gb_task_reminder_epichq == "none")): ?> checked <?php endif; ?>>
      <span class="checkmark"></span>
      </label>
   </div>
   </div>
  

</div>

<script type="text/javascript">
   $(document).ready(function() {

      var current_task_step = parseInt($("#current_task_step").val());
      var total_task_steps = '<?= isset($total_task)?$total_task:1?>';
      $('#total_task_step').val(total_task_steps);


      <?php //if(!isset($goalTaskNew['customTaskNew'])){ ?>
         //loadFormData();
      <?php //}?>

      getAllHabit();
      loadFriendData();

      $(".question-step").text(18);
   });

   $(document).on("change", "#habit_div", function (e) {
      var allhabits = JSON.parse(sessionStorage.getItem("all_habits_associated_task"));
      var habit_id = parseInt($(this).val());

      console.log('change habit div..',habit_id);

      allhabits.filter(select => select.id === habit_id).map(data => {

         $("input[type=checkbox][name='task_recurrence_week[]']").attr("checked",false);
         if(data.gb_habit_recurrence_type == "daily"){
            $("input[type=radio][name='SYG_task_recurrence'][value='weekly']").attr("checked",true);
            $('#task_recurrence_week_div').show();
            $("input[type=checkbox][name='task_recurrence_week[]']").attr("checked",true);
         }
         else if(data.gb_habit_recurrence_type == "weekly"){
            $("input[type=radio][name='SYG_task_recurrence'][value='weekly']").attr("checked",true);
            $('#task_recurrence_week_div').show();
            
            var task_weeks = data.gb_habit_recurrence_week.split(',');
            console.log('task_weeks', task_weeks);
            if(data.gb_habit_recurrence_type == "weekly" && task_weeks.length > 0){
               task_weeks.map(value => {
                  $("input[type=checkbox][value='"+value+"']").attr("checked",true);
               });
            }
         }
      });
   });   
   $('input[type="radio"][name="creattask-send-mail"]').click(function() {
      var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'daily') {
         mainDiv.find('.showTimeBox').show();           
       }
       else {
         mainDiv.find('.showTimeBox').hide();   
       }
   });
   
     $('input[type="radio"][name="creattask-send-mail"]').click(function() {
      var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'weekly') {
         mainDiv.find('.showDayBox').show();           
       }
       else {
         mainDiv.find('.showDayBox').hide();   
       }
   });
   
      $('input[type="radio"][name="creattask-send-mail"]').click(function() {
         var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'monthly') {
         mainDiv.find('.showMonthBox').show();           
       }
       else {
         mainDiv.find('.showMonthBox').hide();   
       }
   });
   $('input[type="radio"][name="SYG_task_recurrence"]').click(function() {
         var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'weekly') {
         $('#task_recurrence_week_div').show();
         //mainDiv.find('.showDayBox').show();           
       }
       else {
        //mainDiv.find('.showDayBox').hide();
         $('#task_recurrence_week_div').hide();   
       }
   });
   $('input[type="radio"][name="SYG_task_recurrence"]').click(function() {
         var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'monthly') {
         // mainDiv.find('.showMonthBox').show();   
         $('#task_recurrence_month_div').show();        
       }
       else {
         // mainDiv.find('.showMonthBox').hide(); 
         $('#task_recurrence_month_div').hide();  
       }
   });


// function loadFormData(){
//    var current_step = $('.step').data('step');
//    $.ajax({
//        url: public_url + 'goal-buddy/load-form-data',
//        type: "POST",
//        data: {'current_step': current_step},
//        success: function (data) {
//           console.log(data);

//           if(data.data && data.data.habit_value != undefined ){
//             console.log('SYG3_see_task: ',data.data.habit_value)
//             $("#habit_div").val(data.data.habit_value).selectpicker("refresh");
//           }
//           if(data.data && data.data.SYG3_task != undefined ){
//              $('#SYG3_task').val(data.data.SYG3_task);
//           }
//           if(data.data && data.data.Priority != undefined ){
//             $("input[type=radio][value='"+data.data.Priority+"']").attr("checked",true);
//           }
//           if(data.data && data.data.SYG_task_note != undefined ){
//              $('#SYG_task_note').text(data.data.SYG_task_note);
//           }
//           if(data.data && data.data.SYG_task_recurrence != undefined ){
            
//             $("input[type=radio][name='SYG_task_recurrence'][value='"+data.data.SYG_task_recurrence+"']").attr("checked",true);

//             if(data.data.SYG_task_recurrence == "weekly" && data.data.task_recurrence_week.length > 0){
//                $('#task_recurrence_week_div').show();
//                data.data.task_recurrence_week.map(value => {
//                   $("input[type=checkbox][value='"+value+"']").attr("checked",true);
//                });
//             }

//           }
//           if(data.data && data.data.SYG3_see_task != undefined ){
//             $("input[type=radio][value='"+data.data.SYG3_see_task+"']").attr("checked",true);

//             if (data.data.SYG3_see_task == "Selected friends") {
               
//                console.log('SYG3_see_task: ',data.data.SYG3_selective_friends)
//                $("#SYG3_selective_friends").val(
//                   data.data.SYG3_selective_friends
//                );
//                $("#SYG3_selective_friends").parent().removeClass("hidden");

//                // if (
//                //   $("#all-my-friends").val() != undefined &&
//                //   $("#all-my-friends").val() != ""
//                // ) {
//                //   console.log("selective frields habit edit...");
//                //   $("#syg2_selective_friends").amsifySuggestags("refresh");
//                //   var my_friends = JSON.parse($("#all-my-friends").val());
//                //   var options = [];

//                //   for (var aaa = 0; aaa < my_friends.length; aaa++) {
//                //     options[aaa] = {
//                //       tag: my_friends[aaa].name,
//                //       value: my_friends[aaa].id,
//                //     };
//                //   }

//                //   $(".autocomplete").amsifySuggestags({
//                //     type: "bootstrap",
//                //     suggestions: options,
//                //     whiteList: true,
//                //   });
//                // }
//             } else {
//                $("#SYG3_selective_friends").attr("value", "");
//                $("#SYG3_selective_friends").parent().addClass("hidden");
//             }

//           }
//           if(data.data && data.data['creattask-send-mail'] != undefined ){
//             $("input[type=radio][value='"+data.data['creattask-send-mail']+"']").attr("checked",true);
//           }
//           if(data.data && data.data['creattask-send-epichq'] != undefined ){
//             $("input[type=radio][value='"+data.data['creattask-send-epichq']+"']").attr("checked",true);
//           }
//       }
//    });
// }
$("input[name=SYG3_see_task]").change(function(){
    var SYG3_see_task = $(this).val();
    if(SYG3_see_task == "Selected friends"){
        /*$("#SYG3_selective_friends").val('');
        
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
        }*/
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
});
function loadFriendData(){
   $.ajax({
       url: public_url + 'goal-buddy/load-friend-list',
       type: "GET",
       dataType: 'json',
       processData: false,
       contentType: false,
       success: function (data) {
        var options = data.my_friends;
         $('.autocomplete').amsifySuggestags({
           type :'bootstrap',
           suggestions: options,
           whiteList:true,
         });
       }
   });
}




function getAllHabit(){
   var goalId = $('#last-insert-id').val();
   var templateCheck = $("input[name='chooseGoal']:checked").val() == 'choose_form_template' ? true : false;
   
   $.ajax({
      url: public_url+'goal-buddy/getAllHabit',
      type: 'POST',
      data: {'goal_id':goalId},
      async:false,
      success: function(data){
         var data = JSON.parse(data);
         
         if(data.allHabit != ''){
            var task_habit_value = "<?= $existingHabitID != null?$existingHabitID:'' ?>";

            $('.task-habit-div').show();
            var optionValue = '';

            var optionValue = '<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';
            
            $.each(data.allHabit,function(key, value) {
               var selected = "";
               if(task_habit_value != ""){
                  task_habit_value = parseInt(task_habit_value);
                  console.log('value : ',value.id," , task_habit_value : ",task_habit_value);
                  if(value.id == task_habit_value){
                     selected = "selected";
                  }
               }
               
               optionValue += '<option value="'+value.id+'" '+selected+'>'+value.gb_habit_name+'</option>';
            });
            optionValue += '</select>';
            $('.task-habit-dropdown').html(optionValue);
            initSelectpicker($('.task-habit-dropdown select'));
            $('.taskhabit_div_class').selectpicker('refresh');
            optionValue = '';

            sessionStorage.setItem("all_habits_associated_task", JSON.stringify(data.allHabit));
         }
      }
   });
}



/*function loadHabitList(){
   $.ajax({
       url: public_url + 'goal-buddy/load-custom-habit-list',
       type: "GET",
       dataType: 'json',
       processData: false,
       contentType: false,
       success: function (data) {
         $('.task-habit-div').show();
            var optionValue = '<select id="habit_div" name="habit_value" class="form-control  taskhabit_div_class" required=""><option value="">-- Select --</option>';
            
            $.each(data.habitTask,function(key, value) {
               if(value.id==data.goalBuddy.gb_habit_id) {
                  task_habit = value;
                  let taskHabiStringify = JSON.stringify(value);
                  $('#viewport-4').find('input[name="associatedHabitWithTask"]').val(taskHabiStringify);
                   optionValue += '<option value="'+value.id+'" >'+value.gb_habit_name+'</option>';
                } else{
                  optionValue += '<option value="'+value.id+'">'+value.gb_habit_name+'</option>';
               }
            });
            optionValue += '</select>';
            $('.task-habit-dropdown').html(optionValue);
            initSelectpicker($('.task-habit-dropdown select'));
            
            $('.taskhabit_div_class').selectpicker('refresh');
      }
     });
}*/
</script><?php /**PATH /Users/mahendra/Documents/projects/epicfitlaravelv6/resources/views/Result/partials/goal-steps/custom/step18.blade.php ENDPATH**/ ?>