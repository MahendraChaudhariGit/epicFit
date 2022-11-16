@extends('layouts.app')

@section('required-styles-for-this-page')

	{!! Html::style('assets\plugins/datepicker/css/datepicker.css?v='.time()) !!}
	<!-- start: Bootstrap Select Master -->
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
    <!-- end: Bootstrap Select Master -->

	<!-- start: Sweet alert css -->
    {!! Html::style('vendor/sweetalert/sweet-alert.css?v='.time()) !!}
    <!-- end: Sweet alert css -->

	<!-- Start: NEW timepicker css -->  
    {!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css?v='.time()) !!}
    <!-- End: NEW timepicker css -->

@stop()

@section('page-title')
@stop


@section('content')

<!-- Start: rapido theme -->
     @if($business)
    <div class="col-md-4 col-lg-4 col-sm-4">
        <div class="panel panel-green">
            <div class="panel-heading border-light rapidoPanelHeading">
                <!--<span class="text-extra-small text-dark">LAST CATEGORY: </span>
                aria-expanded="false"-->
                <div class="btn-group btn-group-xs">
                 <a class="btn rapidobtn dropdown-toggle" data-toggle="dropdown" href="#" >
                  <span class="rapidodd">Select Category</span><span class="caret"></span></a>
                    <ul role="menu" class="dropdown-menu dropdown-light rapidoDropdown">
                    <li><a data-catid="">Select Category</a></li>
                    @if($business)
                    @foreach($taskcategories as $categories ) 
                     <li><a data-catid="{!! $categories->id !!}" data-userid="{!! $categories->t_cat_user_id !!}">{!! $categories->t_cat_name !!}</a></li>      
                    @endforeach
                    @endif
                    </ul>
                </div>
                

                <div class="pull-right">
                    <a href="#" class="btn btn-xs btn-transparent-white" data-toggle="modal" data-target="#addcategory"><i class="fa fa-plus"></i></a>
                    <div class="pull-right" id="deletecategsection">
                    </div>
                </div>
                <div class="panel-tools">
                    
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
                           
                        </div>
                        <div class="pull-right">
                           
                            <button class="btn btn-sm btn-transparent-white" data-toggle="modal" data-target="#addtask" ><i class="fa fa-plus"></i> Add Task</button>
                            
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
                                  
                                    
                                   @if($business)
                                    @foreach($tasks as $task ) 
                                       @if($task->task_due_date == $today)
                                           <?php  if($task->task_status=="complete" || $task->task_status=="not required")
                                                       $todocompleted++;
                                                       $todototal++; 
                                            ?>
                                    <li>
                                        <div class="todo-actions clearfix taskDiv">

                                                <div class="btn-group btn-group-xs pull-left">
                                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" >
                                                <span class="checkboxdd">
                                                <i <?php echo (($task->task_status=="complete")?"class='fa fa-check-square-o'":(($task->task_status=="not required")?"class='fa fa-exclamation'":"class='fa fa-square-o'")); ?>></i>
                                                </span>
                                                &nbsp;<span class="caret <?php if( isUserType(['Staff']) && $task->task_status!="") echo 'hidden' ?>"></span></a>
                                                <ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown <?php if( isUserType(['Staff']) && $task->task_status!="") echo 'hidden' ?>" >
                                                    <li><a data-status=""></a></li>
                                                    <li><a data-status="complete" data-taskid="{!! $task->id !!}">Complete</a></li>
                                                    <li><a data-status="incomplete" data-taskid="{!! $task->id !!}">Incomplete</a></li>
                                                    <li><a data-status="not required" data-taskid="{!! $task->id !!}">Not required</a></li>     
                                                </ul>
                                            </div>
                                                <!--<div class="checkbox pull-left clip-check check-primary m-b-0 checkboxtask">
                                                    <input type="checkbox" value="" id="todo-{!! $task->id !!}" data-taskid="{!! $task->id !!}" class="cbox" //if(// isUserType(['Staff']) && $task->is_completed==1) echo 'checked="checked" disabled'; else if($task->is_completed==1) echo 'checked="checked"'?> >
                                                    <label for="todo-{!! $task->id !!}"><strong></strong></label>
                                                </div>-->

                                            <div class="padding-horizontal-5 pull-left">
                                                <div class="block space5">
                                                    <span class="desc tasknameclass">{!! $task->task_name !!}</span>
                                                    <?php if($task->is_repeating==1) echo '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>';  ?>
                                                    <span class="label label-danger">Today </span>
                                                </div>
                                                <div class="block">
                                                    <span class="desc text-small text-light taskdatetimeclass">
                                                    <i class="fa fa-clock-o">&nbsp; </i> <?php echo date('g:i A',strtotime($task->task_due_time)) ?>
                                                    </span>
                                                    <div class="todo-tools">
                                                        <div class="btn-group btn-group-sm">
                                                        <?php if(isUserType(['Admin']) || Auth::id()==$task->task_user_id ) {?>
                                                            <a class="btn edittask" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id="{!! $task->id !!}" data-task-name="{!! $task->task_name !!}" data-task-duedate="{!! $task->task_due_date !!}" data-task-time="{!! $task->task_due_time !!}" data-task-categ="{!! $task->task_category !!}" data-task-repeat="{!! $task->is_repeating !!}" data-remindhours="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_hours ?>" data-remindcheckbox ="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_is_set ?>">
                                                            <i class="fa fa-pencil-square-o"></i>
                                                            </a>

                                                           <?php if( $task->is_repeating==1){ ?>  
                                                            <a class="btn delete-prompt" href="#" style="display: block;" data-original-title="" title="">
                                                            <i class="fa fa-trash-o"></i>
                                                            </a>
                                                            <?php } else { ?>
                                                            <a class="btn delLink" data-entity="task" href="{{ route('dashboardtask.destroy',$task->id) }}" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask">
                                                            <i class="fa fa-trash-o"></i>
                                                            </a>
                                                            <?php } ?>
                                                         <?php } ?>
                                                        </div>
                                                        <span class="username"><?php if($task->task_status=="complete" || $task->task_status=="not required") echo $task->completer->FullName ?></span>
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
                                    
                                    @if($business)
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
             
                                    <li>
                                        <div class="todo-actions clearfix taskDiv">
                                            
                                            <div class="btn-group btn-group-xs pull-left">
                                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" >
                                                <span class="checkboxdd">
                                                <i <?php echo (($task->task_status=="complete")?"class='fa fa-check-square-o'":(($task->task_status=="not required")?"class='fa fa-exclamation'":"class='fa fa-square-o'")); ?>></i>
                                                </span>
                                                &nbsp;<span class="caret <?php if( isUserType(['Staff']) && $task->task_status!="") echo 'hidden' ?>"></span></a>
                                                <ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown <?php if( isUserType(['Staff']) && $task->task_status!="") echo 'hidden' ?>" >
                                                    <li><a data-status=""></a></li>
                                                    <li><a data-status="complete" data-taskid="{!! $task->id !!}">Complete</a></li>
                                                    <li><a data-status="incomplete" data-taskid="{!! $task->id !!}">Incomplete</a></li>
                                                    <li><a data-status="not required" data-taskid="{!! $task->id !!}">Not required</a></li>     
                                                </ul>
                                            </div>
                                            <!--<div class="checkbox pull-left clip-check check-primary m-b-0">
                                                <input type="checkbox" value="" id="week-{!! $task->id !!}" data-taskid="{!! $task->id !!}" class="cbox"  //if( //isUserType(['Staff']) && $task->is_completed==1) echo 'checked="checked" disabled'; else if($task->is_completed==1) echo 'checked="checked"'?> >
                                                <label for="week-{!! $task->id !!}"><strong></strong>
                                                </label>
                                            </div>-->
                                            
                                            <div class="padding-horizontal-5 pull-left">
                                                <div class="block space5">
                                                    <span class="desc tasknameclass">{!! $task->task_name !!}</span>
                                                    <?php if($task->is_repeating==1) echo '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>';  ?>
                                                    <span class="label label-danger">
                                                    <?php if($taskduedate==$today) echo "Today"; else if($taskduedate==$tomorrow) echo "Tomorrow"; else echo $day; ?></span>
                                                </div>
                                                <div class="block">
                                                    <span class="desc text-small text-light taskdatetimeclass">
                                                    <i class="fa fa-clock-o">&nbsp; </i> <?php echo date('g:i A',strtotime($task->task_due_time)) ?>
                                                    </span>
                                                    <div class="todo-tools">
                                                        <div class="btn-group btn-group-sm">
                                                        <?php if(isUserType(['Admin']) || Auth::id()==$task->task_user_id ) {?>
                                                            <a class="btn edittask" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id="{!! $task->id !!}" data-task-name="{!! $task->task_name !!}" data-task-duedate="{!! $task->task_due_date !!}" data-task-time="{!! $task->task_due_time !!}" data-task-categ="{!! $task->task_category !!}" data-task-repeat="{!! $task->is_repeating !!}" data-remindhours="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_hours ?>" data-remindcheckbox ="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_is_set ?>">
                                                            <i class="fa fa-pencil-square-o"></i>
                                                            </a>

                                                            <?php if( $task->is_repeating==1){ ?>  
                                                            <a class="btn delete-prompt" href="#" style="display: block;" data-original-title="" title="">
                                                            <i class="fa fa-trash-o"></i>
                                                            </a>
                                                            <?php } else { ?>
                                                            <a class="btn delLink" data-entity="task" href="{{ route('dashboardtask.destroy',$task->id) }}" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask">
                                                            <i class="fa fa-trash-o"></i>
                                                            </a>
                                                            <?php } ?>
                                                        <?php } ?>

                                                        </div>
                                                        <span class="username"><?php if($task->task_status=="complete" || $task->task_status=="not required") echo $task->completer->FullName ?></span>
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
                               
                                    @if($business)
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
                                    <li>
                                        <div class="todo-actions clearfix taskDiv">
                                            
                                            <div class="btn-group btn-group-xs pull-left">
                                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" >
                                                <span class="checkboxdd">
                                                <i <?php echo (($task->task_status=="complete")?"class='fa fa-check-square-o'":(($task->task_status=="not required")?"class='fa fa-exclamation'":"class='fa fa-square-o'")); ?>></i>
                                                </span>
                                                &nbsp;<span class="caret <?php if( isUserType(['Staff']) && $task->task_status!=null) echo 'hidden' ?>"></span></a>
                                                <ul role="menu" class="dropdown-menu dropdown-light checkboxDropdown <?php if( isUserType(['Staff']) && $task->task_status!="") echo 'hidden' ?>" >
                                                    <li><a data-status="complete" data-taskid="{!! $task->id !!}">Complete</a></li>
                                                    <li><a data-status="incomplete" data-taskid="{!! $task->id !!}">Incomplete</a></li>
                                                    <li><a data-status="not required" data-taskid="{!! $task->id !!}">Not required</a></li>     
                                                </ul>
                                            </div>
                                            <!--<div class="checkbox pull-left clip-check check-primary m-b-0">
                                                <input type="checkbox" value="" id="month-{!! $task->id !!}" data-taskid="{!! $task->id !!}" class="cbox" //if(// isUserType(['Staff']) && $task->is_completed==1) echo 'checked="checked" disabled'; else if($task->is_completed==1) echo 'checked="checked"'?> >
                                                <label for="month-{!! $task->id !!}"><strong></strong></label>
                                            </div>-->

                                            <div class="padding-horizontal-5 pull-left">
                                                <div class="block space5">
                                                    <span class="desc tasknameclass">{!! $task->task_name !!}</span>
                                                    <?php if($task->is_repeating==1) echo '<span class="epic-tooltip m-l-3" rel="tooltip" data-toggle="tooltip" data-placement="top" title="Recurring Task"><i class="fa fa-retweet"></i></span>'; ?>


                                                    <span class="label label-danger"> <?php if($taskduedate==$today) echo "Today"; else if($taskduedate==$tomorrow) echo "Tomorrow"; else echo $day; ?> </span>
                                                </div>
                                                <div class="block">
                                                    <span class="desc text-small text-light taskdatetimeclass">
                                                    <i class="fa fa-clock-o">&nbsp; </i> <?php echo date('g:i A',strtotime($task->task_due_time)) ?>
                                                    </span>
                                                    <div class="todo-tools">
                                                        <div class="btn-group btn-group-sm">
                                                        <?php if(isUserType(['Admin']) || Auth::id()==$task->task_user_id ) {?>
                                                            <a class="btn edittask" href="javascript:;" data-toggle="modal" data-target="#addtask"  data-task-id="{!! $task->id !!}" data-task-name="{!! $task->task_name !!}" data-task-duedate="{!! $task->task_due_date !!}" data-task-time="{!! $task->task_due_time !!}" data-task-categ="{!! $task->task_category !!}" data-task-repeat="{!! $task->is_repeating !!}" data-remindhours="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_hours ?>" data-remindcheckbox ="<?php if($task->reminders && count( $task->reminders) ) echo $task->reminders[0]->tr_is_set ?>">
                                                            <i class="fa fa-pencil-square-o"></i>
                                                            </a>
                                                            
                                                            <?php if( $task->is_repeating==1){ ?>  
                                                            <a class="btn delete-prompt" href="#" style="display: block;" data-original-title="" title="">
                                                            <i class="fa fa-trash-o"></i>
                                                            </a>
                                                            <?php } else { ?>
                                                            <a class="btn delLink" data-entity="task" href="{{ route('dashboardtask.destroy',$task->id) }}" data-placement="top" data-original-title="Delete" data-entity="task" data-ajax-callback="deletetask">
                                                            <i class="fa fa-trash-o"></i>
                                                            </a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        </div>
                                                        <span class="username"><?php if($task->task_status=="complete" || $task->task_status=="not required") echo $task->completer->FullName ?></span>
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
     @endif
<!-- End: rapido theme -->
   
</div>
</div>                              

<!--Start: Rapido Add Task Model-->
@if($business)
<div class="modal fade" id="addtask" role="dialog" tabindex="-1" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Task</h4>
            </div>
            <div class="modal-body bg-white">
            
                {!! Form::open(['url' => 'dashboard/task', 'role' => 'form', 'id' =>'taskForm']) !!}
                <input type="hidden" name="taskFormId" value="">
                <input type="hidden" name="taskRepeat" value="">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="padding-15"> 
                            <legend>
                                Task &nbsp;&nbsp;&nbsp;&nbsp;
                            </legend>
                            <div class="form-group">
                                {!! Form::label('taskName', 'Task Name *', ['class' => 'strong']) !!}
                                {!! Form::text('taskName', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('taskDueDate', 'Task Due Date *', ['class' => 'strong']) !!}
                                <div class="row">
                                    <div class="col-md-8">
                                        {!! Form::text('taskDueDate', null, ['class' => 'form-control eventDatepicker onchange-set-neutral', 'autocomplete' => 'off', 'required', 'id'=> 'taskDueDate']) !!}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group bootstrap-timepicker timepicker">
                                            <input type="text" name="taskDueTime" class="form-control  no-clear timepicker1" data-default-time="9:30 AM" id="taskDueTime" required="required">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="form-group">
                            {!! Form::label('taskCategory', 'Task Category *', ['class' => 'strong']) !!}
                            {!! Form::select('taskCategory', $tc , null, ['class' => 'form-control onchange-set-neutral','required' => 'required', 'id'=>'taskcategoryid']) !!}
                            </div>
                            
                            <div class="form-group form-inline">
                            <div class="checkbox clip-check m-b-0 check-primary">
                                <input type="checkbox" class="onchange-set-neutral" id="remindercheck" name="reminder" value="1">
                                <label for="remindercheck" class="m-r-0 no-error-label">
                                    <strong>Remind Me Before</strong> 
                                </label>
                                {!! Form::select('reminderVal', ["" => "-- Select --", 1 => '1 hour', 2 => '2 hours', 3 => '3 hours', 4 => '4 hours', 5 => '5 hours', 6 => '6 hours',7 => '7 hours',8 => '8 hours',9 => '9 hours',10 => '10 hours',11 => '11 hours', 12 => '12 hours', 13 => '13 hours', 14 => '14 hours', 15 => '15 hours', 16 => '16 hours',17 => '17 hours',18 => '18 hours', 19 => '19 hours', 20 => '20 hours', 21 => '21 hours', 22 => '22 hours', 23 => '23 hours', 24 => '24 hours'] , null, ['class' => 'mw-100 onchange-set-neutral', 'id'=>'remindercheckid','disabled']) !!}
                                <span class="help-block m-y-0" style="display: none;"></span>
                            </div>
                        </div>
                          
                        <div class="form-group">
                                <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" value="Fri" id="rcheck">
                                <label for="rcheck"><strong>Repeat </strong></label>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="padding-15 event-reccur" id="recurrencefield"><!-- class="client-form" -->
                            <legend>
                                Recurrence &nbsp;&nbsp;&nbsp;&nbsp;
                            </legend>
                            
                            <!--<div>-->
                                <div class="form-group">
                                    {!! Form::label('eventRepeat', 'Repeat', ['class' => 'strong']) !!}
                                    {!! Form::select('eventRepeat', ['' => '-- Select --', 'None' => 'None', 'Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly'], null, ['class' => 'form-control', 'id' => 'eventrepeatid']) !!}
                                </div>

                                <div class="eventRepeatFields">
                                        <div class="form-group">
                                            {!! Form::label('eventRepeatInterval', 'Repeat every *', ['class' => 'strong']) !!}
                                            <div>
                                                {!! Form::select('eventRepeatInterval', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-94p onchange-set-neutral', 'required', 'id' => 'eventrepeatintervalid']) !!} 
                                                <span class="eventRepeatIntervalUnit">days</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            {!! Form::label(null, 'Ends *', ['class' => 'strong']) !!}
                                            <div class="moveErrMsg no-error-labels">
                                                <div class="radio clip-radio radio-primary">
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndAfter" value="After">
                                                    <label for="appointEventRepeatEndAfter">
                                                        After
                                                    </label>
                                                    {!! Form::select('eventRepeatEndAfterOccur', $eventRepeatIntervalOpt, null, ['class' => 'form-control mw-120 onchange-set-neutral', 'id' => 'eventrepeatendafteroccurid']) !!}
                                                    occurrences
                                                </div>
                                                <div class="radio clip-radio radio-primary">
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndOn" value="On">
                                                    <label for="appointEventRepeatEndOn">
                                                        On
                                                    </label>
                                                    {!! Form::text('eventRepeatEndOnDate', null, ['class' => 'form-control mw-120 inlineBlckDisp eventDatepicker onchange-set-neutral', 'autocomplete' => 'off']) !!}
                                                </div>
                                                <div class="radio clip-radio radio-primary m-b-0">
                                                    <input type="radio" name="eventRepeatEnd" id="appointEventRepeatEndNever" value="Never">
                                                    <label for="appointEventRepeatEndNever">
                                                        Never
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="help-block placeErrMsg m-t-0"></span>
                                            <div class="eventRepeatWeekdays no-error-labels">
                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays0" value="Mon" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays0"> Mon </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays1" value="Tue" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays1"> Tue </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays2" value="Wed" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays2"> Wed </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays3" value="Thu" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays3"> Thu </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays4" value="Fri" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays4"> Fri </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays5" value="Sat" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays5"> Sat </label>
                                                </div>

                                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                                                    <input id="appointEventRepeatWeekdays6" value="Sun" type="checkbox">
                                                    <label for="appointEventRepeatWeekdays6"> Sun </label>
                                                </div>
                                            </div>
                                            <span class="help-block m-t-0"></span>
                                        </div>
                                </div>       
                            <!--</div>--> 
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">Close
                </button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                {!! Form::submit('Submit', ['class' => 'btn btn-primary' , 'id' => 'taskbtn']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endif

<!--Start: Rapido Add Category Model-->
@if($business)
<div class="modal fade" id="addcategory" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Add Category</h4>
            </div>
            <div class="modal-body bg-white">
                {!! Form::open(['url' => 'dashboard/category', 'role' => 'form', 'id'=>'categoryForm']) !!}
                <input type="hidden" name="hiddenCategId" value="">
                <div class="row">
                    <div class="col-md-12">
                        <fieldset class="padding-15 "><!-- class="client-form" -->
                            <legend>
                                Category &nbsp;&nbsp;&nbsp;&nbsp;
                            </legend>
                            <div class="form-group">
                                {!! Form::label('categoryName', 'Category Name *', ['class' => 'strong']) !!}
                                {!! Form::text('categoryName', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <!--<div class="form-group">
                                {!! Form::submit('Add Category', ['class' => 'btn btn-primary form-control']) !!}
                            </div>-->
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-o" data-dismiss="modal">Close
                </button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                 {!! Form::submit('Submit', ['class' => 'btn btn-primary' , 'id' => 'categorybtn']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endif
<!--End: Rapido Add Category Model-->
@stop

@section('required-script-for-this-page')

	{!! Html::script('assets/js/jquery-ui.min.js?v='.time()) !!} 
	<!--{!! Html::script('assets/plugins/datepicker/js/bootstrap-datepicker.js') !!}-->
	<!-- start: moment -->
	<!-- {!! Html::script('vendor/moment/moment.min.js') !!}
    {!! Html::script('vendor/moment/moment-timezone-with-data.js') !!}
    {!! Html::script('assets/js/set-moment-timezone.js') !!}  -->
	<!-- end : moment  -->

	<!-- Start:  NEW timepicker js -->
    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js?v='.time()) !!} 
    <!-- End: NEW timepicker js -->

	<!-- start: Bootstrap Select Master -->
    {!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js?v='.time()) !!}
    <!-- end: Bootstrap Select Master -->
	<!-- start: jquery validation -->
    {!! Html::script('vendor/jquery-validation/jquery.validate.min.js?v='.time()) !!}
    <!-- end: jquery validation -->
	<!-- start: Full Calendar Custom Script -->
    {!! Html::script('assets/js/helper.js?v='.time()) !!}
    <!-- end: Full Calendar Custom Script -->
    
    {!! Html::script('assets/js/recurrence.js?v='.time()) !!}

    {!! Html::script('vendor/sweetalert/sweet-alert.min.js?v='.time()) !!}

	<!-- start: Index jquery -->
    {!! Html::script('assets/js/index.js?v='.time()) !!}
    <!-- end: Index jquery -->

	{!! Html::script('assets/js/new-dashboard.js?v='.time()) !!}

<script>

var loggedInUser = {
        //type: '{{ Session::get('userType') }}',
        type: '{{ Auth::user()->account_type }}',
        id: {{ Auth::user()->account_id }},
        userId: {{ Auth::id() }},
        name: '{{ Auth::user()->fullName }}'
};

var shownPopover = [];
var calPopupHelper = $('#calPopupHelper');
var popoverContainer = $('#container');

var deleteReccurEventPopoverOpt = {
        placement: 'left',
        html: true,
        content: "Would you like to cancel only this task,<br> or this and all following tasks in the<br> series?<a class='btn btn-default btn-block delete-event' href='#' data-target-event='future'>This and future</a> <a class='btn btn-default btn-block delete-event' href='#' data-target-event='this'>This only</a>",
        container: popoverContainer,
        title: "<strong>Cancel recurring task?</strong>",
        trigger: 'manual'
    };

var editReccurEventPopoverOpt = {
        placement: 'left',
        html: true,
        content: "Would you like to change only this task,<br> or this and all following tasks in the<br> series?<a class='btn btn-default btn-block update-event' href='#' data-target-event='future'>This and future</a> <a class='btn btn-default btn-block update-event' href='#' data-target-event='this'>This only</a>",
        container: popoverContainer,
        title:"<strong>Edit recurring task?</strong>",
        trigger: 'manual'
};

$(document).ready(function(){
   initCustomValidator();

   $("#taskFilter").datepicker({
        showOn: 'button',
        buttonText: moment().format('D MMM YYYY'),
        buttonImageOnly: false,
        dateFormat:"d M yy",
        
        onSelect: function( newText ){
            //$('#taskFilterSection img').attr('alt',newText);
            $('.ui-datepicker-trigger').text(newText);
            var dropDownName=$('.rapidodd').text();
            var triggerChange = $('.rapidoDropdown li a').filter(function () { return $(this).html() == dropDownName; });
            $(triggerChange).trigger('click');
        }
    });
   
   $('.ui-datepicker-trigger').addClass('btn btn-sm btn-transparent-white');
});    

var bladeType = "Dashboard";
</script>

@stop()
