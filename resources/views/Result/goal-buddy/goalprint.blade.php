<html>
<head>
<body>
<span>My Goals (<?php print_r(count($goals));?>)</span>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover m-t-10" id="client-datatable">
                    <thead>
                        <tr>
                           <th>Goal Name</th>
                            <th class="hidden-xxs">Shared</th>
                            <th class="hidden-xs">DueDate</th>
                             <th class="hidden-xs">Progress</th>
                            <th class="center hide"></th>
                        </tr>
                    </thead>
                    <tbody>
                   @foreach($goals as $goalInfo)
                        <tr class="goal-row <?php if($goalInfo->gb_goal_status == 1){ ?>completed <?php } ?>" id = "<?php print_r($goalInfo->id);?>">
                        <input type="hidden" name ="goal_id" id ="goal-id" value ="<?php print_r($goalInfo->id);?>">
                            <td>
                                <a class="goal-name"> {!! $goalInfo->gb_goal_name or 'Default' !!}
                                </a> 
                                <br>
                              <div class="col-md-12 milestones hide" id="milestones-<?php print_r($goalInfo->id);?>">
                                 <strong>Milestones:</strong>
                                 <p>(tasks)
                                 <div class="checkbox clip-check check-primary" style="position:absolute;top: 10%;left: 38%;">
                                                <input type="checkbox" name="goal_compleate" id="goal-compleate-<?php print_r($goalInfo->id);?>" <?php if($goalInfo->gb_goal_status) { ?> checked <?php } ?> value="1" class="compleate-goal">
                                                <label for="goal-compleate-<?php print_r($goalInfo->id);?>"><strong>Goal Completed</strong></label>
                                  </div>
                                </p>
                                 <p>
                                   <div class="checkbox clip-check check-primary" style ="margin-left:20px;position: absolute;top: 65%;left: 41%;">
                                                
                                                <?php if(count($goalDetails[$goalInfo->id]['milestones'])>0) { 
                                                  foreach($goalDetails[$goalInfo->id]['milestones'] as $milestonesInfo){ ?>
                                                    <input type="checkbox" name="task_name" id="task-name" value="1" class="">
                                                      <label for="task-name" class="task-text"><strong>
                                                      <?php print_r($milestonesInfo['m_name']);?></strong></label>
                                                      </br>
                                                  <?php }} ?>
                                                
                                  </div>
                                </p>
                              </div>
                            </td>
                            <td class="hidden-xxs">
                                {!! $goalInfo->gb_goal_seen or '' !!}
                            </td>
                            <td class="hidden-xs">
                            <?php if($goalInfo->gb_due_date != '1970-01-01'){
                                print_r(date('j M Y',strtotime($goalInfo->gb_due_date)));
                            } else {
                                print_r('');                               
                            } ?>
                            </td>
                            <td>
                                <div class="progress progress-striped progress-sm <?php if($goalInfo->gb_goal_status){ ?> progress-bar-success <?php }?>" id = "<?php print_r($goalInfo->id);?>">
                                    <div class="progress-bar progress-bar-success" role="progressbar">
                                    </div>

                                </div>
                                <p class ="progress-percentage"><?php if($goalInfo->gb_goal_status){ ?> 100 % <?php } else { ?> 0% <?php } ?></p>
                                 <div class="col-md-12 habits hide" id="habit-<?php print_r($goalInfo->id);?>">
                                  <strong>Habits:</strong>
                                  <br/>
                              <?php if(count($goalDetails[$goalInfo->id]['habits'])>0) { 
                                 foreach($goalDetails[$goalInfo->id]['habits'] as $habitsInfo){ ?>
                                   <span><?php print_r($habitsInfo['h_name']); ?></span>
                                  <br/>
                                  <span><?php print_r($habitsInfo['h_seen']); ?></span>
                                  <?php } }?>
                                  <p><strong>Completed:</strong>1
                                  <strong>Missed:</strong>4
                                  <strong>Success:</strong>20%</p>
                              </div>
                            </td>
                            <td class="center">
                                <div>
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('goal-buddy.create') }}" data-placement="top" data-original-title="Edit">
                                        <i class="fa fa-pencil edit-goal" style="color:#ff4401;" data ="list-edit" id="<?php echo $goalInfo->id;?>"></i>
                                    </a>
                                </div>
                                <div>
                                    <a class="btn btn-xs btn-default tooltips delete-goal" href="#" data-placement="top" data-original-title="delete" data = "<?php echo $goalInfo->id;?>" style ="margin-left:57px;margin-top:-43px;">
                                        <i class="fa fa-times" style="color:#ff4401;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</head>
</html>

