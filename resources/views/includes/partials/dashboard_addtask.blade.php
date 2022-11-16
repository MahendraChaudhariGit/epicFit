<div class="col-md-4 col-lg-4 col-sm-12">
    <div class="panel panel-green">
        <div class="panel-heading border-light rapidoPanelHeading">
            <!--<span class="text-extra-small text-dark">LAST CATEGORY: </span> aria-expanded="false"-->
            <div class="btn-group btn-group-xs">
             <a class="btn rapidobtn dropdown-toggle" data-toggle="dropdown" href="#" >
              <span class="rapidodd"> Personal </span><span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu dropdown-light rapidoDropdown">
                <li><a data-catid="">Select Category</a></li>
                @if($business)
                @foreach($taskcategories->sortBy('t_cat_name') as $categories ) 
                    <li><a data-catid="{!! $categories->id !!}" data-userid="{!! $categories->t_cat_user_id !!}">{!! $categories->t_cat_name !!}</a></li>      
                @endforeach

                @if(count($bussUsers))
                    <?php
                    $commonCategory = $taskcategories->where('t_cat_user_id',0)->where('t_cat_business_id',0)->first();
                    ?>
                     @foreach($bussUsers->sortBy('FullName') as $bussUser ) 
                        <li><a data-catid="{!! $commonCategory->id !!}" data-userid="{!! $commonCategory->t_cat_user_id !!}" data-owner-id="{!! $bussUser->id !!}">{!! $bussUser->FullName !!}'s {!! $commonCategory->t_cat_name !!}</a></li>      
                    @endforeach
                @endif
                @endif
                </ul>
            </div>
            

            <div class="pull-right">
                <a href="#" class="btn btn-xs btn-transparent-white" data-toggle="modal" data-target="#addcategory"><i class="fa fa-plus"></i></a>
                <div class="pull-right" id="deletecategsection">
                <!--<a class="btn btn-xs btn-transparent-white delLink" data-entity="category" href="" data-placement="top" data-original-title="Delete" data-ajax-callback="deletecategory"><i class="fa fa-trash-o"></i></a>  --> 
                </div>
            </div>
            <div class="panel-tools">
                <!--<div class="dropdown">
                    <a data-toggle="dropdown" class="btn btn-xs dropdown-toggle btn-transparent-white">
                    	<i class="fa fa-cog"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-light pull-right" role="menu">
                    	<li>
                    		<a class="panel-collapse collapses" href="#"><i class="fa fa-angle-up"></i> <span>Collapse</span> </a>
                    	</li>
                    	<li>
                    		<a class="panel-refresh" href="#">
                    			<i class="fa fa-refresh"></i> <span>Refresh</span>
                    		</a>
                    	</li>
                    	<li>
                    		<a class="panel-config" href="#panel-config" data-toggle="modal">
                    			<i class="fa fa-wrench"></i> <span>Configurations</span>
                    		</a>
                    	</li>
                    	<li>
                    		<a class="panel-expand" href="#">
                    			<i class="fa fa-expand"></i> <span>Fullscreen</span>
                    		</a>
                    	</li>
                    </ul>
                    </div>-->
                <!--<a class="btn btn-xs btn-link panel-close" href="#">
                    <i class="fa fa-times"></i>
                    </a>-->
            </div>
        </div>
        <div class="panel-body no-padding">
            <div class="row no-margin rapidoPanelRow">
    <?php  
          $today=date("Y-m-d");
          $thisweek=date('Y-m-d', strtotime("+6 days"));
          $tomorrow=date('Y-m-d', strtotime("+1 days"));
          $todocompleted = 0;
          $todototal = 0;
          $weekcompleted = 0;
          $weektotal = 0;
          $monthcompleted = 0;
          $monthtotal = 0;
    ?>

                <div class="padding-10 col-md-12">
                    <div class="progress progress-xs transparent-black no-radius space5">
                        <div aria-valuetransitiongoal="0" class="progress-bar progress-bar-success partition-white animate-progress-bar widthclass"  aria-valuenow="88"></div><!-- style="width: 0%;"-->
                    </div>
                    <span class="text-extra-small progressBar">0% status</span>
                </div>
  


                <div class="padding-10 col-md-12 clearfix">
                    <div class="pull-left" id="taskFilterSection">
                         <input type='hidden' id='taskFilter'>
                         
                       <!-- <div class="input-group">classTime datetimepicker-->
                           <!-- {!! Form::text('eventTime', null, ['class' => 'form-control eventDatepicker', 'autocomplete' => 'off', 'readonly' ]) !!}
                            
                            <span class="input-group-addon">
                                <span class="fa fa-calendar-o"></span>
                            </span> -->
                            <!-- <a class="btn btn-xs btn-default" href="#"><i class="fa fa-calendar-o" style="color:#ff4401;"></i> 01 March 2017</a> -->
                           

                          <!-- <a class="btn btn-sm btn-transparent-white dateclass" for="hiddenid"><i class="fa fa-calendar-o"></i>&nbsp; 01 March 2017</a>
                            <input type="hidden" class="eventDatepicker" id="hiddendateid"> --> 

                           <!-- <button class="btn btn-sm btn-transparent-white dateclass" id="dateid">abc</button>
                            <input type="button" class="xyzclass" id="xyzid" value="xyz">
                            <input type="hidden" class="eventDatepicker" id="hiddendateid">-->
                           
                            
                       <!-- </div>-->
                        <!--<span class="background-dark padding-5 radius-5 text-small inline-block"><i class="fa fa-clock-o"></i> 43 days </span>-->
                       <!-- <span class="background-dark padding-5 radius-5 text-small inline-block"><i class="fa fa-calendar-o"></i>
                        <input class=" form-control eventDatepicker onchange-set-neutral" autocomplete="off" name="eventRepeatEndOnDatee" readonly required type="text"></span>-->
                        <!--<span class="background-dark padding-5 radius-5 text-small inline-block"><i class="fa fa-calendar-o"></i> January 04</span>-->
                    </div>
                    <div class="pull-right">
                        <!--<a href="#" class="btn btn-sm btn-transparent-white"><i class="fa fa-plus"></i> New Category </a>-->
                        <button class="btn btn-sm btn-transparent-white" data-toggle="modal" data-target="#addtask" ><i class="fa fa-plus"></i> Add Task</button>
                        <!--<button class="btn btn-primary btn-o pull-right" data-toggle="modal" data-target="#myModal">
                            Open Deafult Modal
                            </button>-->
                    </div>
                </div>
            </div>
            <div class="tabbable no-margin no-padding partition-dark">
                <ul class="nav nav-tabs" id="myTab2">
                    <li class="active">
                        <a data-toggle="tab" href="#todo_tab_example1" data-todototal="" data-todocompleted="" class="todotask">
                        To-do
                        </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#todo_tab_example2" data-weektotal="" data-weekcompleted="" class="thisweektask">
                        Next Week
                        </a>
                    </li>
                    <li class="">
                        <a data-toggle="tab" href="#todo_tab_example3" data-monthtotal="" data-monthcompleted="" class="thismonthtask">
                        Next Month
                        </a>
                    </li>
                </ul>
                <div class="tab-content partition-white">
                    <div id="todo_tab_example1" class="tab-pane padding-bottom-5 active rapidoPadding">
                        <div class="panel-scroll height-330 ps-container perfect-scrollbar">
                            <ul class="todo" id="todosection">
                              
                                <!--<li>
                                    <div class="todo-actions">
                                    	<i class="fa fa-square-o"></i>
                                    	<div class="padding-horizontal-5">
                                    		<div class="block space5">
                                    			<span class="desc">Staff Meeting</span><span class="label label-danger"> toda</span>
                                    		</div>
                                    		<div class="block">
                                    			<span class="desc text-small text-light"><i class="fa fa-clock-o"></i> 13:00 PM Toda</span>
                                    			<div class="btn-group btn-group-sm todo-tools">
                                    				<a class="btn" href="javascript:;">
                                    					<i class="fa fa-pencil-square-o"></i>
                                    				</a>
                                    				<a class="btn" href="javascript:;">
                                    					<i class="fa fa-trash-o"></i>
                                    				</a>
                                    			</div>
                                    		</div>
                                    	</div>
                                    </div>
                                    </li>
                                    <li>
                                    <div class="todo-actions">
                                    	<i class="fa fa-square-o"></i>
                                    	<div class="padding-horizontal-5">
                                    		<div class="block space5">
                                    			<span class="desc">Hire developers</span><span class="label label-warning"> tommorow</span>
                                    		</div>
                                    		<div class="block">
                                    			<span class="desc text-small text-light"><i class="fa fa-clock-o"></i> 7:00 AM Tomorrow</span>
                                    			<div class="btn-group btn-group-sm todo-tools">
                                    				<a class="btn" href="javascript:;">
                                    					<i class="fa fa-pencil-square-o"></i>
                                    				</a>
                                    				<a class="btn" href="javascript:;">
                                    					<i class="fa fa-trash-o"></i>
                                    				</a>
                                    			</div>
                                    		</div>
                                    	</div>
                                    </div>
                                    </li>
                                    <li>
                                    <div class="todo-actions">
                                    	<i class="fa fa-square-o"></i>
                                    	<div class="padding-horizontal-5">
                                    		<div class="block space5">
                                    			<span class="desc">Staff Meeting</span><span class="label label-success"> this week</span>
                                    		</div>
                                    		<div class="block">
                                    			<span class="desc text-small text-light"><i class="fa fa-clock-o"></i> 12:00 AM this week</span>
                                    			<div class="btn-group btn-group-sm todo-tools">
                                    				<a class="btn" href="javascript:;">
                                    					<i class="fa fa-pencil-square-o"></i>
                                    				</a>
                                    				<a class="btn" href="javascript:;">
                                    					<i class="fa fa-trash-o"></i>
                                    				</a>
                                    			</div>
                                    		</div>
                                    	</div>
                                    </div>
                                    </li>-->
                                   
                               @if($business && count($tasks))
                                @foreach($tasks as $task ) 
                                   @if($task->task_due_date == $today)
                                        <?php  if($task->task_status=="complete" || $task->task_status=="not required")
                                                   $todocompleted++;
                                                   $todototal++; 
                                        ?>
                                <li class="showtaskmodal">
                                    <div class="todo-actions clearfix taskDiv">

                                        <div class="btn-group btn-group-xs pull-left preventTaskView">
                                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" >
                                            <span class="checkboxdd">
                                            <i <?php echo (($task->task_status=="complete")?"class='fa fa-check-square-o'":(($task->task_status=="not required")?"class='fa fa-exclamation'":"class='fa fa-square-o'")); ?>></i>
                                            
                                            </span>
                                            &nbsp;<span class="caret"></span></a>
                                            <ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown">
                                                <li><a data-status=""></a></li>

                                                <li class="<?php if($task->task_status == "complete") echo 'hidden' ; ?>">
                                                <a data-status="complete" data-taskid="{!! $task->id !!}">Complete</a>
                                                </li>

                                                <li class="<?php if($task->task_status == '') echo 'hidden'; ?>" >
                                                <a data-status="incomplete" data-taskid="{!! $task->id !!}">Incomplete</a>
                                                </li>

                                                <li class="<?php if($task->task_status == "not required") echo 'hidden'; ?>" >
                                                <a data-status="not required" data-taskid="{!! $task->id !!}">Not required</a>
                                                </li> 

                                            </ul>
                                        </div>
                                            


                                        <div class="padding-horizontal-5 pull-left">
                                            <div class="block space5 to-do-link">
                                                <span class="desc tasknameclass">
                                                @if($task->task_client_id)
                                                    <a class="preventTaskView" href="{{ route('clients.show', $task->task_client_id) }}?page=dashboard">
                                                    {!! $task->task_name !!}
                                                    </a>
                                                @else
                                                    {!! $task->task_name !!}
                                                @endif
                                                </span>
                                                <?php if($task->task_tr_id != 0) echo '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>';  ?>
                                                <span class="label label-danger top-10">Today </span>
                                            </div>
                                            <div class="block">
                                                <span class="desc text-small text-light taskdatetimeclass">
                                                <i class="fa fa-clock-o">&nbsp; </i>  <?php echo date('g:i A',strtotime($task->task_due_time)) ?>
                                                </span>
                                                <div class="todo-tools">
                                                    <div class="btn-group btn-group-sm">
                                                    <?php if(isUserType(['Admin']) || Auth::id()==$task->task_user_id ) {?>

                                                        <!-- <a class="btn showTask" href="javascript:;" data-toggle="modal" data-target="#showModal">
                                                        <i class="fa fa-share-square-o"></i>
                                                        </a> -->

                                                        <a class="btn edittask preventTaskView" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id="{!! $task->id !!}" data-task-name="{!! $task->task_name !!}" data-auth-id="{!! $task->task_user_id !!}" data-task-duedate="{!! $task->task_due_date !!}" data-task-time="{!! $task->task_due_time !!}" data-task-categ="{!! $task->task_category !!}" data-task-repeat="{!! $task->task_tr_id !!}" data-tasknote="{!! $task->task_note !!}"  data-remindhours="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_hours ?>" data-remindcheckbox ="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_is_set ?>" >
                                                        <i class="fa fa-pencil-square-o"></i>
                                                        </a>
                                                       
                                                        <?php if( $task->task_tr_id != 0){ ?>  
                                                        <a class="btn delete-prompt preventTaskView" href="#" style="display: block;" data-original-title="" title="">
                                                        <i class="fa fa-trash-o"></i>
                                                        </a>
                                                        <?php } else { ?>
                                                        <a class="btn delLink preventTaskView" data-entity="task" href="{{ route('dashboardtask.destroy',$task->id) }}" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask">
                                                        <i class="fa fa-trash-o"></i>
                                                        </a>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                    <span class="username">
                                                    
                                                        <?php if(($task->task_status=="complete" || $task->task_status=="not required") && $task->completer) echo $task->completer->FullName ?>
                                                        
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @endforeach
                                @endif
                            </ul>
                            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px; width: 291px; display: none;">
                                <div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div>
                            </div>
                            <div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px; height: 180px; display: inherit;">
                                <div class="ps-scrollbar-y" style="top: 0px; height: 128px;"></div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="todocompletedid" value=<?php echo $todocompleted ?>>
                    <input type="hidden" id="todototalid" value=<?php echo $todototal ?>>
                    <div id="todo_tab_example2" class="tab-pane padding-bottom-5 rapidoPadding">
                        <div class="panel-scroll height-330 ps-container perfect-scrollbar">
                            <ul class="todo" id="weeksection">
                                
                                @if($business && count($tasks))
                                @foreach($tasks as $task ) 
                                <?php 
                                      $taskduedate=$task->task_due_date;
                                      $day=date('D, j M Y', strtotime($taskduedate));
                                ?>
                                @if( $taskduedate >= $today and $taskduedate <= $thisweek)
                                    <?php  if($task->task_status=="complete" || $task->task_status=="not required")
                                                   $weekcompleted++;
                                                   $weektotal++;
                                    ?>
         
                                <li class="showtaskmodal">
                                    <div class="todo-actions clearfix taskDiv">
                                       
                                        <div class="btn-group btn-group-xs pull-left preventTaskView">
                                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" >
                                            <span class="checkboxdd">
                                            <i <?php echo (($task->task_status=="complete")?"class='fa fa-check-square-o'":(($task->task_status=="not required")?"class='fa fa-exclamation'":"class='fa fa-square-o'")); ?> ></i>
                                            </span>
                                            &nbsp;<span class="caret"></span>
                                            </a>

                                            <ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown">
                                                <li><a data-status=""></a></li>
                                                <!-- <li><a data-status="complete" data-taskid="{!! $task->id !!}">Complete</a></li>
                                                <li><a href="#" data-status="incomplete" data-taskid="{!! $task->id !!}">Incomplete</a></li>
                                                <li><a data-status="not required" data-taskid="{!! $task->id !!}">Not required</a></li>  -->

                                                <li class="<?php if($task->task_status == "complete") echo 'hidden' ; ?>">
                                                <a data-status="complete" data-taskid="{!! $task->id !!}">Complete</a>
                                                </li>

                                                <li class="<?php if($task->task_status == '') echo 'hidden'; ?>" >
                                                <a data-status="incomplete" data-taskid="{!! $task->id !!}">Incomplete</a>
                                                </li>

                                                <li class="<?php if($task->task_status == "not required") echo 'hidden'; ?>" >
                                                <a data-status="not required" data-taskid="{!! $task->id !!}">Not required</a>
                                                </li>     
                                            </ul>
                                        </div>
                                        <!--<div class="checkbox pull-left clip-check check-primary m-b-0">
                                            <input type="checkbox" value="" id="week-{!! $task->id !!}" data-taskid="{!! $task->id !!}" class="cbox"//if(//isUserType(['Staff']) && ($task->is_completed=="completed" || $task->is_completed=="not required" )) echo 'checked="checked" disabled'; else if($task->is_completed=="completed" || $task->is_completed=="not required" ) echo 'checked="checked"'?> >
                                            <label for="week-{!! $task->id !!}"><strong></strong>
                                            </label>
                                        </div>-->
                                        
                                        <div class="padding-horizontal-5 pull-left">
                                            <div class="block space5 to-do-link">
                                                <span class="desc tasknameclass">
                                                @if($task->task_client_id)
                                                    <a href="{{ route('clients.show', $task->task_client_id) }}?page=dashboard" class="preventTaskView">
                                                    {!! $task->task_name !!}
                                                    </a>
                                                @else
                                                    {!! $task->task_name !!}
                                                @endif
                                                </span>
                                                <?php if($task->task_tr_id != 0) echo '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>';  ?>
                                                <span class="label label-danger top-10">
                                                <?php if($taskduedate==$today) echo "Today"; else if($taskduedate==$tomorrow) echo "Tomorrow"; else echo $day; ?></span>
                                            </div>
                                            <div class="block">
                                                <span class="desc text-small text-light taskdatetimeclass">
                                                <i class="fa fa-clock-o">&nbsp; </i>  <?php echo date('g:i A',strtotime($task->task_due_time)) ?>
                                                </span>
                                                <div class="todo-tools">
                                                    <div class="btn-group btn-group-sm">
                                                    <?php if(isUserType(['Admin']) || Auth::id()==$task->task_user_id ) {?>

                                                        <!-- <a class="btn showTask" href="javascript:;" data-toggle="modal" data-target="#showModal">
                                                        <i class="fa fa-share-square-o"></i>
                                                        </a> -->

                                                        <a class="btn edittask preventTaskView" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id="{!! $task->id !!}" data-task-name="{!! $task->task_name !!}" data-auth-id="{!! $task->task_user_id !!}" data-task-duedate="{!! $task->task_due_date !!}" data-task-time="{!! $task->task_due_time !!}" data-task-categ="{!! $task->task_category !!}" data-task-repeat="{!! $task->task_tr_id !!}"   data-tasknote="{!! $task->task_note !!}"  data-remindhours="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_hours ?>" data-remindcheckbox ="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_is_set ?>">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                        </a>

                                                        <?php if( $task->task_tr_id != 0){ ?>  
                                                        <a class="btn delete-prompt preventTaskView" href="#" style="display: block;" data-original-title="" title="">
                                                        <i class="fa fa-trash-o"></i>
                                                        </a>
                                                        <?php } else { ?>
                                                        <a class="btn delLink preventTaskView" data-entity="task" href="{{ route('dashboardtask.destroy',$task->id) }}" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask">
                                                        <i class="fa fa-trash-o"></i>
                                                        </a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    </div>
                                                    <span class="username">
                                                    <?php if(($task->task_status=="complete" || $task->task_status=="not required") && $task->completer) echo $task->completer->FullName ?>
                                                        
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @endforeach
                                @endif
                            </ul>
                            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px; width: 0px; display: none;">
                                <div class="ps-scrollbar-x" style="left: -10px; width: 0px;"></div>
                            </div>
                            <div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px; height: 180px; display: inherit;">
                                <div class="ps-scrollbar-y" style="top: 0px; height: 0px;"></div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="weekcompletedid" value=<?php echo $weekcompleted ?>>
                    <input type="hidden" id="weektotalid" value=<?php echo $weektotal ?>>
                    <div id="todo_tab_example3" class="tab-pane padding-bottom-5 rapidoPadding">
                        <div class="panel-scroll height-330 ps-container perfect-scrollbar">
                            <ul class="todo" id="monthsection">
                            
                                @if($business && count($tasks))
                                @foreach($tasks as $task )
                                <?php 
                                      $taskduedate=$task->task_due_date;
                                      $day = date('D, j M Y', strtotime($taskduedate));
                                      $thismonth = date('D, j M Y', strtotime("+1 month")); 
                                ?> 
                                @if( $taskduedate >= $today and $taskduedate < $thismonth) 
                                     <?php  if($task->task_status=="complete" || $task->task_status=="not required")
                                                   $monthcompleted++;
                                                   $monthtotal++;
                                      ?>
                                <li class="showtaskmodal">
                                    <div class="todo-actions clearfix taskDiv">
                                        
                                        <div class="btn-group btn-group-xs pull-left preventTaskView">
                                            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" >
                                            <span class="checkboxdd">
                                            <i <?php echo (($task->task_status=="complete")?"class='fa fa-check-square-o'":(($task->task_status=="not required")?"class='fa fa-exclamation'":"class='fa fa-square-o'")); ?>></i>
                                            </span>
                                            &nbsp;<span class="caret"></span></a>
                                            <ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown">
                                                <li><a data-status=""></a></li>
                                                <!-- <li><a data-status="complete" data-taskid="{!! $task->id !!}">Complete</a></li>
                                                <li><a data-status="incomplete" data-taskid="{!! $task->id !!}">Incomplete</a></li>
                                                <li><a data-status="not required" data-taskid="{!! $task->id !!}">Not required</a></li>  -->

                                                <li class="<?php if($task->task_status == "complete") echo 'hidden' ; ?>">
                                                <a data-status="complete" data-taskid="{!! $task->id !!}">Complete</a>
                                                </li>

                                                <li class="<?php if($task->task_status == '') echo 'hidden'; ?>" >
                                                <a data-status="incomplete" data-taskid="{!! $task->id !!}">Incomplete</a>
                                                </li>

                                                <li class="<?php if($task->task_status == "not required") echo 'hidden'; ?>" >
                                                <a data-status="not required" data-taskid="{!! $task->id !!}">Not required</a>
                                                </li>     
                                            </ul>
                                        </div>
                                            <!--<div class="checkbox pull-left clip-check check-primary m-b-0">
                                            <input type="checkbox" value="" id="month-{!! $task->id !!}" data-taskid="{!! $task->id !!}" class="cbox" //if( //isUserType(['Staff']) && ($task->is_completed=="completed" || $task->is_completed=="not required" )) echo 'checked="checked" disabled'; else if($task->is_completed=="completed" || $task->is_completed=="not required") echo 'checked="checked"'?> >
                                            <label for="month-{!! $task->id !!}"><strong></strong></label>
                                            </div>-->

                                        <div class="padding-horizontal-5 pull-left">
                                            <div class="block space5 to-do-link">
                                                <span class="desc tasknameclass">
                                                @if($task->task_client_id)
                                                    <a href="{{ route('clients.show', $task->task_client_id) }}?page=dashboard" class="preventTaskView">
                                                    {!! $task->task_name !!}
                                                    </a>
                                                @else
                                                    {!! $task->task_name !!}
                                                @endif
                                                </span>
                                                <?php if($task->task_tr_id != 0) echo '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>'; ?>


                                                <span class="label label-danger top-10"> <?php if($taskduedate==$today) echo "Today"; else if($taskduedate==$tomorrow) echo "Tomorrow"; else echo $day; ?> </span>
                                            </div>
                                            <div class="block">
                                                <span class="desc text-small text-light taskdatetimeclass">
                                                <i class="fa fa-clock-o">&nbsp; </i> <?php echo date('g:i A',strtotime($task->task_due_time)) ?>
                                                </span>
                                                <div class="todo-tools">
                                                    <div class="btn-group btn-group-sm">
                                                    <?php if(isUserType(['Admin']) || Auth::id()==$task->task_user_id ) {?>

                                                        <!-- <a class="btn showTask" href="javascript:;" data-toggle="modal" data-target="#showModal">
                                                        <i class="fa fa-share-square-o"></i>
                                                        </a> -->

                                                        <a class="btn edittask preventTaskView" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id="{!! $task->id !!}" data-task-name="{!! $task->task_name !!}" data-auth-id="{!! $task->task_user_id !!}" data-task-duedate="{!! $task->task_due_date !!}" data-task-time="{!! $task->task_due_time !!}" data-task-categ="{!! $task->task_category !!}" data-task-repeat="{!! $task->task_tr_id !!}"  data-tasknote="{!! $task->task_note !!}"  data-remindhours="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_hours ?>" data-remindcheckbox ="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_is_set ?>">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                        </a>
                                                        
                                                        <?php if( $task->task_tr_id != 0){ ?>  
                                                        <a class="btn delete-prompt preventTaskView" href="#" style="display: block;" data-original-title="" title="">
                                                        <i class="fa fa-trash-o"></i>
                                                        </a>
                                                        <?php } else { ?>
                                                        <a class="btn delLink preventTaskView" data-entity="task" href="{{ route('dashboardtask.destroy',$task->id) }}" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask">
                                                        <i class="fa fa-trash-o"></i>
                                                        </a>
                                                        <?php } ?>
                                                    <?php }?>
                                                    </div>
                                                    <span class="username">
                                                    <?php if(($task->task_status=="complete" || $task->task_status=="not required") && $task->completer) echo $task->completer->FullName ?>
                                                        
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @endforeach
                                @endif
                            </ul>
                            <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 3px; width: 0px; display: none;">
                                <div class="ps-scrollbar-x" style="left: -10px; width: 0px;"></div>
                            </div>
                            <div class="ps-scrollbar-y-rail" style="top: 0px; right: 3px; height: 180px; display: inherit;">
                                <div class="ps-scrollbar-y" style="top: 0px; height: 0px;"></div>
                            </div>
                             
                        </div>
                    </div>
                    <input type="hidden" id="monthcompletedid" value=<?php echo $monthcompleted ?>>
                    <input type="hidden" id="monthtotalid" value=<?php echo $monthtotal ?>>
                </div>
            </div>
        </div>
    </div>
</div>