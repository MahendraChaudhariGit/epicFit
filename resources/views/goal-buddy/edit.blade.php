@extends('blank')

@section('required-styles')
  {!! Html::style('assets/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!} 
  {!! Html::style('assets/plugins/bootstrap-datepicker/css/datepicker.css') !!}
  <!-- {!! Html::style('assets/plugins/sweetalert/sweet-alert.css') !!} --> 

  <!-- {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!} -->
  {!! Html::style('assets/css/custom.css?v='.time()) !!}
  {!! Html::style('assets/css/goal-buddy.css?v='.time()) !!}
@stop()

@section('page-title')
<span> Goal Buddy</span>
<button class="btn btn-primary pull-right btn-wide" id="back-to-client-view">Back</button>
@stop

@section('content') 

  <!-- start: Delete Form -->
  @include('includes.partials.delete_form')
  <!-- end: Delete Form -->

  <!-- start: Pic crop Model --> 
  @include('includes.partials.pic_crop_model') 
  <!-- end: Pic crop Model -->

  <input type="hidden" name="goalClientId" value="{{ isset($clientId)?$clientId:'' }}">

  <!-- start: acc1 --> 
  @if (Request::segment(2) != 'edithabit' && Request::segment(2) != 'edittask' && Request::segment(2) != 'editmilestone')
  <div class="panel panel-white" id="set-acc1"> 
    <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="clip-menu"></i>
          </span>
          Set Your Goals 
          <span class="icon-group-right">
            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
              <i class="fa fa-wrench"></i>
            </a>
            <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="client-overview">
              <i class="fa fa-chevron-down"></i>
            </a>
          </span>
        </h5>
    </div> 
    <div class="panel-body" >
      <div id="set_goal" > 
        <div  class="swMain goal-buddy-wizard ">
          <div id="step-1">
            @if(isset($goalDetails))
            <form id="edit_goal_form" method="POST">
            @endif
            <div class="sucMes hidden"></div> 
            @if (Request::segment(2) == 'editgoal')
              <input type="hidden" class="form-control" id="goal-from-cal" value="goalfromcal" >
            @endif
            @include('goal-buddy.creategoal')
            <div class="row">
              <div class="col-sm-2 col-md-offset-10">
                <button type="submit" class="btn btn-primary btn-o btn-wide submit-first-form" >Set a goals</button>
             </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
  <!-- end: acc1 --> 

  <!-- start: acc2 -->
  @if (Request::segment(2) != 'edittask' && Request::segment(2) != 'editgoal'&& Request::segment(2) != 'edithabit')
  <div class="panel panel-white"> 
    <div class="panel-heading">
      <h5 class="panel-title">
        <span class="icon-group-left">
            <i class="clip-menu"></i>
        </span> 
        Build New Milestone
        <span class="icon-group-right">
          <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
            <i class="fa fa-wrench"></i>
          </a>
          <a class="btn btn-xs pull-right panel-collapse   @if (Request::segment(2) != 'editmilestone') closed @endif" href="#" data-panel-group="client-overview">
            <i class="fa fa-chevron-down"></i>
          </a>
        </span>
      </h5>
    </div>
    <div class="panel-body"> 
      <div class="row milestone-form" >
        <form id="build_new_milestone_form" method="POST">
          <div class="sucMes hidden"></div> 
          <div class="col-md-12">
            @if (Request::segment(2) == 'editmilestone')
            <input type="hidden" class="form-control" id="Milestone-from-list" value="milestonefromlist" name="updatemilestonefromlist">
            @endif
            @include('goal-buddy.createmilestone')
          </div>
          <div class="row">
            <div class="col-sm-3 pull-right">
              <button class="btn btn-primary  btn-wide margin-right-15" id="create_milestone_btn" data-step="1"> Establish a Milestone </button>
            </div>
          </div>
        </form>
     </div>     
    </div> 
  </div>
  @endif
  <!-- end: acc2 -->

  <!-- start: acc3 -->
    @if (Request::segment(2) != 'edittask' && Request::segment(2) != 'editgoal' && Request::segment(2) != 'editmilestone')
    <div class="panel panel-white"> 
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
              <i class="clip-menu"></i>
          </span> 
          Build New Habits
          <span class="icon-group-right">
            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                <i class="fa fa-wrench"></i>
            </a>
            <a class="btn btn-xs pull-right panel-collapse   @if (Request::segment(2) != 'edithabit') closed @endif" href="#" data-panel-group="client-overview">
                <i class="fa fa-chevron-down"></i>
            </a>
          </span>
        </h5>
      </div>
      <div class="panel-body"> 
      @if(Request::segment(2) != 'edithabit')
      <div class ="row habit-listing" @if((isset($habitData))&&(count($habitData) < 1)) style="display:none; " @endif> 
        <div class ="row">
          <div class ="col-md-12 sucMsgDisp"></div> <!-- Success message display -->
          <div class ="col-md-6 col-xs-6">
            <h4><em>What Habits Do I need to Develop to Accomplish This Goal?</em></h4>
          </div>
          <div class ="col-md-6 col-xs-6"> 
            <a class ="add-habit pull-right">+Establish New Habit</a> 
          </div>
        </div>
        <table class="table table-striped table-bordered table-hover m-t-10 " id="client-datatable">
          <thead>
            <tr>
              <th>Habit Name</th>
              <th>Frequency</th>
              <th>Milestone</th>
              <th>Shared</th>
              <th class="center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($habitData as $habits)
            <tr>
              <td>{{isset($habits->gb_habit_name)?$habits->gb_habit_name:null}}</td>
              <td>
                <?php 
                  if($habits->gb_habit_recurrence_type == 'weekly') echo 'Every '.$habits->gb_habit_recurrence_week; 
                  elseif($habits->gb_habit_recurrence_type == 'monthly') echo 'Day '.$habits->gb_habit_recurrence_month .' of every month';
                  else echo ucfirst($habits->gb_habit_recurrence_type) ;  
                ?>
              </td>
              <td>{{implode(', ', $habits->getMilestoneNames())}}</td>
              <td>{{isset($habits->gb_habit_seen)?$habits->gb_habit_seen:null}}</td>
              <td class="center">
                <a class="btn btn-xs btn-default tooltips habit-edit" data-placement="top" data-original-title="Edit" data-habit-id = "{{$habits->id}}">
                      <i class="fa fa-pencil" style="color:#ff4401;"></i>
                </a>
                <a class="btn btn-xs btn-default tooltips delete-habit" data-entity="habit" data-placement="top" data-original-title="Delete" data-habit-id = "{{$habits->id}}">
                  <i class="fa fa-times" style="color:#ff4401;"></i>
                </a>
              </td>
            </tr>
           @endforeach 
          </tbody>
        </table>
      </div>
      @endif

      <div class="row habit-form" @if((isset($habitData))&&($habitData->count() > 0) && (Request::segment(2) != 'edithabit')) style="display: none;" @endif>
        <form id="build_new_habit_form" method="POST">
        <div class="sucMes hidden"></div> 
        <div class="col-md-12">
        @if (Request::segment(2) == 'edithabit')
        <input type="hidden" class="form-control" id="Habit-from-list" value="habitfromlist" name="updatehabitfromlist">
        @endif
        <!--input type="hidden" class="form-control" id="habit-id" value="" name="habit_id"-->
            @include('goal-buddy.createhabits')
        </div>
        <!-- end: PANEL BODY -->
        <div class="row">
          <div class="col-sm-2 pull-right">
            <button class="btn btn-primary  btn-wide margin-right-15" id="create_habbit_btn" data-step="1"> Establish a Habit </button>
          </div>

          <div class="col-sm-2 pull-right cancel_habbit_btn hidden" >
            <button class="btn btn-danger  btn-wide  margin-right-15" id="cancel_habbit" type="button"> Cancel </button>
          </div>
        </div>
        </form>
       </div>
            
    </div>
  </div>
  @endif
  <!-- end: acc3 --> 

  <!-- start: acc3 -->
  @if (Request::segment(2) != 'edithabit' && Request::segment(2) != 'editgoal' && Request::segment(2) != 'editmilestone')
  <div class="panel panel-white">
    <div class="panel-heading">
      <h5 class="panel-title">
        <span class="icon-group-left">
          <i class="clip-menu"></i>
        </span> 
        Manage Your Tasks
        <span class="icon-group-right">
          <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
            <i class="fa fa-wrench"></i>
          </a>
          <a class="btn btn-xs pull-right panel-collapse @if (Request::segment(2) != 'edittask') closed @endif" href="#" data-panel-group="client-overview">
            <i class="fa fa-chevron-down"></i>
          </a>
        </span>
      </h5>
    </div>
    <div class="panel-body " >    
    @if (Request::segment(2) != 'edittask')  
    <div class="row task-listing"  @if((isset($taskData))&&(count($taskData) < 1)) style="display:none; " @endif>
      <div class ="col-md-12">
        <h4 class ="padding-15"><em class="task-name"></em></h4>
      </div>
      <div class ="row">
        <div class ="col-md-12 task-name-sucMsgDisp"></div> <!-- Success message display -->
        <div class ="col-md-6 col-xs-6">
          <h4><em>Would you like to establish another task?</em></h4>
        </div>
        <div class ="col-md-6 col-xs-6"> 
          <a class ="add-task pull-right">+Schedule New Task</a>
        </div>
      </div>
      <table class="table table-striped table-bordered table-hover m-t-10 " id="client-datatable-task">
        <thead>
          <tr>
            <th>Task Name</th>
            <th>Priority</th>
            <!-- <th>Due Date</th> -->
            <th>Shared</th>
            <th class="center">Actions</th>
          </tr>
        </thead>
        <tbody>
        @foreach($taskData as $tasks)
        <tr>
          <td>{{isset($tasks->gb_task_name)?$tasks->gb_task_name:null}}</td>
          <td>{{isset($tasks->gb_task_priority)?ucfirst($tasks->gb_task_priority):null}}</td>
          <!-- <td>{{isset($tasks->gb_task_due_date)?$tasks->gb_task_due_date:null}}</td> -->
          <td>{{isset($tasks->gb_task_seen)?$tasks->gb_task_seen:null}}</td>
          <td class="center">
            <a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "{{$tasks->id}}">
              <i class="fa fa-pencil" style="color:#ff4401;"></i>
            </a>

            <a class="btn btn-xs btn-default tooltips delete-task" data-entity="task" data-placement="top" data-original-title="Delete" data-task-id = "{{$tasks->id}}">
              <i class="fa fa-times" style="color:#ff4401;"></i>
            </a>
          </td>
        </tr>
        @endforeach 
        </tbody>
      </table>
    </div>
    @endif

    <div class="task-form"  @if((isset($taskData))&&($taskData->count() > 0) && (Request::segment(2) != 'edittask')) style="display: none;" @endif>       
      <form id="manage_task_form" method="POST">
        <div class="sucMes hidden"></div> 
        <div class="col-md-12">
          @if (Request::segment(2) == 'edittask')
            <input type="hidden" class="form-control" id="task-from-cal" value="taskfromcal" >
          @endif
          @include('goal-buddy.createtask')
        </div>
        <div class="row">
          <div class="col-sm-2 pull-right">
            <button class="btn btn-primary  btn-wide  margin-right-15" id="manage_task_btn" data-step="2"> Establish a Task </button>
          </div>
          <div class="col-sm-2 pull-right cancel_task_btn hidden" >
            <button type="button" class="btn btn-danger btn-wide pull-right margin-right-15  " id="cancel_task" > Cancel</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>   
@endif
<!-- end: acc3 --> 
@stop

@section('script')
<!-- {!! Html::script('assets/js/jquery-ui.min.js') !!}
{!! Html::script('assets/plugins/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('assets/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('assets/plugins/Jcrop/js/script.js') !!} -->
{!! Html::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js?v='.time()) !!}
{!! Html::script('assets/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
<!-- {!! Html::script('assets/plugins/sweetalert/sweet-alert.min.js') !!}  -->

{!! Html::script('assets/js/form-wizard-goal-buddy.js?v='.time()) !!}
<!-- {!! Html::script('assets/plugins/DataTables/media/js/jquery.dataTables.min.js') !!} -->

{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/goal-buddy.js?v='.time()) !!}



@stop()

@section('script-handler-for-this-page')
$( ".panel-collapse.closed" ).trigger( "click" );


@stop()

