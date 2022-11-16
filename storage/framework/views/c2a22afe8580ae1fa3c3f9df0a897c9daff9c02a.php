
<?php $__env->startSection('required-styles'); ?>
    <?php echo Html::style('result/css/custom.css?v='.time()); ?>

    <?php echo Html::style('result/plugins/DataTables/media/css/dataTables.bootstrap.min.css'); ?>

    <?php echo Html::style('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css'); ?>

    <?php echo Html::style('result/plugins/sweetalert/sweet-alert.css'); ?> 
    <style type="text/css">
      .pagination>.active>a, .pagination>.active>span, .pagination>.active>a:hover, .pagination>.active>span:hover, .pagination>.active>a:focus, .pagination>.active>span:focus {

    background-color: #f64c1e;
    border-color: #f64c1e;
}

    </style>
<?php $__env->stopSection(); ?>
    

<?php $__env->startSection('content'); ?>
<?php echo displayAlert(); ?>

 <!-- start: Delete Form -->
    <?php echo $__env->make('includes.partials.delete_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- end: Delete Form --> 

    <div class="row goal-action" style="text-align: center; display: flex">
       <!--  <div class="col-xs-12 col-sm-3 goal-action-item">
            <div style="margin-left: 15px;display: inline-block;margin-top: 16px;float: left;">Goal list (<?php echo e(count($goals)); ?>)</div>
        </div>
 -->
        <div class="col-xs-6 col-sm-9 goal-action-item" style="text-align: right;">
          <div class="checkbox clip-check check-primary" style="display: inline-block; margin-top: 13px">
              <input type="checkbox" name="goal_hide" id="hide-compleate-goal" value="1" class="">
              <label for="hide-compleate-goal" class="hide-goal" style="text-align: left"><strong>Hide Completed Goals</strong></label>
          </div>
        </div>

        <div class="col-xs-6 col-sm-3 goal-action-item">
            <div style="margin-right: 15px; display: inline-block; float: right">
                <a class="btn btn-primary hide" href="<?php echo e(route('goal-buddy.print')); ?>" style ="margin-left: 500px;"><i class="ti-printer"></i> Print Goals</a>
                <a class="btn btn-primary pull-right create-goal" style="margin-top: 7px;" href="<?php echo e(route('goal-buddy.create')); ?>"><i class="ti-plus"></i> Set New Goal</a>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-striped table-bordered table-hover m-t-10" id="goal-datatable">
                    <thead>
                        <tr>
                           <th>Goal Name</th>
                            <th>Shared</th>
                            <th>Due Date</th>
                            <th width="19%">Progress</th>
                            <th class="center ">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                   <?php $__currentLoopData = $goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goalInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="goal-row  <?php if($goalInfo->gb_goal_status == 1): ?> completed <?php endif; ?>" id = "<?php echo e($goalInfo->id); ?>">
                        <input type="hidden" name ="goal_id" id ="goal-id" value ="<?php echo e($goalInfo->id); ?>">
                            <td><a class="goal-name"><?php echo e(isset($goalInfo->gb_goal_name )?$goalInfo->gb_goal_name :null); ?></a>
                              <br>
                              <div class="col-md-12 milestones hide" id="milestones-<?php echo e($goalInfo->id); ?>">
                                 <strong>Milestones:</strong>
                                 <!--div class="checkbox clip-check check-primary" style="margin-left: 21px;">
                                                <input type="checkbox" name="goal_compleate" id="goal-compleate-<?php echo e($goalInfo->id); ?>"  <?php if($goalInfo->gb_goal_status): ?>  checked <?php endif; ?> value="1" class="compleate-goal">
                                                <label for="goal-compleate-<?php echo e($goalInfo->id); ?>"><strong>Goal Completed</strong></label>
                                  </div-->
                                  <div class="checkbox clip-check check-primary" style ="">
                                               <?php $persent=1; $totalpersent=0; ?>
                                           <?php if(isset($goalInfo->goalBuddyMilestones)): ?> 
                                           <?php  $milestonesNo=$goalInfo->goalBuddyMilestones->count();
                                              
                                                  if($milestonesNo > 0 )
                                                   $milestonesPercentage=100/$milestonesNo; 
                                                 
                                            ?>
                                            <?php $__currentLoopData = $goalInfo->goalBuddyMilestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestonesInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                          <input type="checkbox" name="milestone_compleate" id="milestone-compleate-<?php echo e($milestonesInfo->id); ?>"<?php echo $milestonesInfo->gb_milestones_status ? ' checked="checked"' : '' ?> value="1" class="milestone-goal" data-milestones-id="<?php echo e($milestonesInfo->id); ?>" data-percentage="<?php echo e($milestonesPercentage); ?>" autocomplete="off" />
                                                  
                                                      <label for="milestone-compleate-<?php echo e($milestonesInfo->id); ?>"><strong>
                                                     <?php echo e($milestonesInfo->gb_milestones_name); ?></strong></label>
                                                      </br>
                                                     <?php 
                                             if($milestonesInfo->gb_milestones_status==1)
                                                $totalpersent+=$milestonesPercentage; ?> 
                                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                                                  <?php endif; ?>
                                       
                                                
                                  </div>
                                 </div>
                            </td>
                            <td>
                             <?php echo e(isset($goalInfo->gb_goal_seen )?$goalInfo->gb_goal_seen :null); ?> 
                            </td>  
                            <td>
                              <?php echo e(isset($goalInfo->gb_due_date )?dbDateToDateString($goalInfo->gb_due_date):null); ?>

                            </td>
                            <td>
                              <div class="progress progress-striped progress-xs" style="margin-bottom:10px" >
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php if($totalpersent): ?> <?php echo e($totalpersent); ?>  <?php else: ?> <?php echo e($totalpersent); ?> <?php endif; ?>" style="width: <?php if($totalpersent): ?> <?php echo e($totalpersent); ?>%  <?php else: ?>   <?php echo e($totalpersent); ?>% <?php endif; ?>">
                                    </div>

                                </div>
                                
                               <p><strong>Milestones:</strong> <span class ="progress-percentage"><?php echo e(round($totalpersent,2)); ?>%</span></p>

                               <div class="col-md-12 habits hide" id="habit-<?php echo e($goalInfo->id); ?>">
                                <?php if(isset($goalInfo->goalBuddyHabit)): ?>  
                                  <strong>Habits:</strong>
                                  <input type ="hidden" name ="goal_name" id ="goal-name" value ="<?php echo e(isset($goalInfo->gb_goal_name )?$goalInfo->gb_goal_name :null); ?> ">

                               
                                 <?php $__currentLoopData = $goalInfo->goalBuddyHabit; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $habitsInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <a data-toggle="modal" data-target="#habit-modal" class="listing-habit-name" data="<?php echo e($habitsInfo->id); ?>"><span><?php echo e($habitsInfo->gb_habit_name); ?></span></a>

                                 <span><?php echo e(isset($habitsInfo->gb_habit_seen )?$habitsInfo->gb_habit_seen :null); ?></span>
                                  
                                  <p><strong>Completed: </strong><span class="completed-habit"><?php echo e(isset($completed[$habitsInfo->id] )?$completed[$habitsInfo->id] :null); ?></span><br />
                                  <strong>Missed: </strong><span class="missed-habit"><?php echo e(isset($missed[$habitsInfo->id])?$missed[$habitsInfo->id] :null); ?></span><br />
                                  <strong>Success: </strong><span class="success-habit"><?php echo e(round($success[$habitsInfo->id],2)); ?>%</span></p>

                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                                 <?php endif; ?>

                                  </div>


                            </td>
                            <td class="center">
                                <div>
                                    <a class="btn btn-xs btn-default tooltips" href="<?php echo e(route('goal-buddy.edit', $goalInfo->id)); ?>" data-placement="top" data-original-title="Edit">
                                            <i class="fa fa-pencil" style="color:#ff4401;"></i>
                                    </a>
                                </div>
                                <div>
                                    <a class="btn btn-xs btn-default tooltips delete-goal" data-entity="goal"  data-placement="top" data-original-title="delete" data = "<?php echo e($goalInfo->id); ?>" style ="margin-left:57px;margin-top:-43px;">
                                        <i class="fa fa-times" style="color:#ff4401;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                    </tbody>
                </table>
                    <!-- start: Paging Links -->
                    <?php echo $goals->render(); ?>

                    
                    <!-- end: Paging Links -->

                             <!--habit model-->
                              <?php echo $__env->make('Result.goal-buddy.habitmodel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>    
                            <!--habit model-->

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('required-script'); ?>
<?php echo Html::script('result/js/jquery-ui.min.js'); ?>


<!-- start: Moment Library -->
<?php echo Html::script('result/plugins/moment/moment.min.js'); ?>

<!-- end: Moment Library -->
 <?php echo Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js'); ?>

<?php echo Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js'); ?>

<?php echo Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js'); ?>  
 <?php echo Html::script('result/plugins/jquery-validation/jquery.validate.min.js'); ?>

<?php echo Html::script('result/plugins/sweetalert/sweet-alert.min.js'); ?> 
<?php echo Html::script('result/js/form-wizard-goal-buddy.js?v='.time()); ?>

<?php echo Html::script('result/js/helper.js?v='.time()); ?>

<?php echo Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js'); ?>

<?php echo Html::script('result/plugins/DataTables/media/js/dataTableDateSort.js'); ?>

<?php echo Html::script('result/plugins/DataTables/media/js/dataTables.bootstrap.min.js'); ?>


<?php echo Html::script('result/js/goal-buddy.js?v='.time()); ?>


<script>
var cookieSlug = "goal";
$(document).ready(function(){
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#goal-datatable').dataTable({"searching": false, "paging": false, "info": false });
})

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Result.masters.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/mahendra/Documents/projects/epicfitlaravelv6/resources/views/Result/goal-buddy/goallisting.blade.php ENDPATH**/ ?>