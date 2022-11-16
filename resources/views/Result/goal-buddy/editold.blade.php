@extends('Result.masters.app')
@section('required-styles')

{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!} 
{!! Html::style('result/plugins/tipped-tooltip/css/tipped/tipped.css') !!}
<!-- start: Bootstrap datepicker --> 
{!! Html::style('result/plugins/bootstrap-datepicker/css/datepicker.css') !!}
<!-- end: Bootstrap datepicker --> 
{!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!} 

{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
{!! Html::style('result/plugins/DataTables/media/css/DT_bootstrap.css') !!}
{!! Html::style('result/css/custom.css?v='.time()) !!}
{!! Html::style('result/css/goal-buddy.css?v='.time()) !!}

<!-- VpForm -->
{!! Html::style('result/vendor/vp-form/css/vp-form.css') !!}
@stop()

@section('header-scripts')
    {!! Html::script('result/vendor/vp-form/js/jquery.windows.js') !!}
    {!! Html::script('result/vendor/vp-form/js/angular.js') !!}
    {!! Html::script('result/vendor/vp-form/js/autogrow.js') !!}
    {!! Html::script('result/vendor/vp-form/js/vp-form.js') !!}
    {!! Html::script('result/plugins/tipped-tooltip/js/tipped/tipped.js') !!}
@stop

@section('page-title')
<span> Goal Buddy</span>
@stop

@section('content') 

 <!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form --> 
<!-- start: acc1 --> 
<div class="leftcolomn" ng-app="vp-form">
    <input id="m-selected-step" type="hidden" value="1">
@if (Request::segment(2) != 'edithabit' && Request::segment(2) != 'edittask' && Request::segment(2) != 'editmilestone')

<!-- start: ngApp = vp-form -->
    <div class="panel panel-white" id="set-acc1">
        <!-- start: PANEL HEADING -->
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
                    <a data-step="1" id="set_your_goal" class="btn btn-xs btn-step-toggle pull-right panel-collapse" href="#" data-panel-group="client-overview">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </span>
            </h5>
        </div>
        <!-- start: PANEL HEADING -->

        <script>
            $(document).ready(function() {
                $('.btn-step-toggle').click(function() {
                    var step = parseInt($(this).attr('data-step'));

                    $("#m-selected-step").val(step)
                        .trigger('change');
                })
            })
        </script>

        <!-- start: PANEL BODY -->
        <div class="panel-body" >
            <!-- start: Pic crop Model -->
            @include('includes.partials.pic_crop_model')
            <!-- end: Pic crop Model -->

            <div id="set_goal" >
              <!--form-horizontal-->
              <div  class="swMain goal-buddy-wizard ">

                <div class="gb-step-edit-1" id="step-1" ng-controller="GBWidgetOne">
                 @if(isset($goalDetails))
                 <form name="goalBuddy" id="edit_goal_form" method="POST">
                 @endif
                    <div class="sucMes hidden"></div>
                    @if (Request::segment(2) == 'editgoal')
                       <input type="hidden" class="form-control" id="goal-from-cal" value="goalfromcal" >
                    @endif

                    @include('Result.goal-buddy.creategoal')
                    <div class="row row-btn-step-container">
                       <div class="col-sm-2 col-md-offset-10">
                          <button ng-disabled="goalBuddy.goal_modal.$invalid ||
                            goalBuddy.name_goal.$invalid || goalBuddy.describe_achieve.$invalid ||
                            goalBuddy.goal_year.$invalid ||goalBuddy.accomplish.$invalid || 
                            goalBuddy.gb_relevant_goal.$invalid ||
                            goalBuddy.gb_relevant_goal_event.$invalid || goalBuddy.due_date.$invalid ||
                            goalBuddy.goal_seen.$invalid || goalBuddy.send_msgss.$invalid" type="submit" class="btn btn-primary btn-o btn-wide btn-step submit-first-form" >Set a goal</button>
                       </div>
                    </div>
                  {!! Form::close() !!}
                </div> <!-- end step-1 -->

              </div>
            </div> <!-- end set goal -->
        </div> <!-- end panel body -->
    </div> <!-- end set acc-1 -->
    @endif
    <!-- end: acc1 -->

    <!-- start: acc2 -->
    @if (Request::segment(2) != 'edittask' && Request::segment(2) != 'editgoal'&& Request::segment(2) != 'edithabit')
    <div class="panel panel-white">
        <!-- start: PANEL HEADING -->
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
                    <a data-step="2" id="Build_Milestone" class="btn btn-xs btn-step-toggle pull-right panel-collapse   @if (Request::segment(2) != 'editmilestone') closed @endif" href="#" data-panel-group="client-overview">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </span>
            </h5>
        </div>
        <!-- end: PANEL HEADING -->


        <!-- start: PANEL BODY -->
        <div class="panel-body">
            <div class="row milestone-form gb-step-edit-2" ng-controller="GBWidgetTwo">
                <form name="goalBuddy" id="build_new_milestone_form" method="POST">
                    <div class="sucMes hidden"></div>

                    <div class="col-md-12">
                        @if (Request::segment(2) == 'editmilestone')
                        <input type="hidden" class="form-control" id="Milestone-from-list" value="milestonefromlist" name="updatemilestonefromlist">
                        @endif

                        @include('Result.goal-buddy.createmilestone')
                    </div> <!-- end col12 -->

                    <div class="row row-btn-step-container">
                      <div class="col-sm-3 pull-right">
                        <button class="btn btn-primary  btn-wide btn-step margin-right-15" id="create_milestone_btn" data-step="1"> Establish a Milestone </button>
                      </div>

                      <!--div class="col-sm-2 pull-right cancel_milestone_btn hidden" >
                        <button class="btn btn-danger  btn-wide  margin-right-15" id="cancel_milestone" type="button"> Cancel </button>
                      </div-->
                    </div> <!-- end row -->
                </form>
           </div>
        </div>
        <!-- end: PANEL BODY -->
    </div>
    @endif
    <!-- end: acc2 -->



    <!-- start: acc3 -->
    @if (Request::segment(2) != 'edittask' && Request::segment(2) != 'editgoal' && Request::segment(2) != 'editmilestone')
    <div class="panel panel-white">
        <!-- start: PANEL HEADING -->
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
                    <a data-step="3" id="Build_Habits" class="btn btn-xs btn-step-toggle pull-right panel-collapse   @if (Request::segment(2) != 'edithabit') closed @endif" href="#" data-panel-group="client-overview">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </span>
            </h5>
        </div>
        <!-- end: PANEL HEADING -->

        <!-- start: PANEL BODY -->
        <div class="panel-body  gb-step-edit-3" ng-controller="GBWidgetThree">
            @if(Request::segment(2) != 'edithabit')
            <div class ="row habit-listing" @if((isset($habitData))&&(count($habitData) < 1)) style="display:none; " @endif>
              <div class ="row">
                  <div class ="col-md-8">
                    <h6><em>What Habits Do I need to Develop to Accomplish This Goal?</em></h6>
                  </div>

                  <div class ="col-md-4 container-btn-new-habit">
                      <a class ="btn btn-primary pull-right add-habit">Establish New Habit</a>
                  </div>
              </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover m-t-10 " id="client-datatable">
                        <thead>
                        <tr>
                            <th class="">Habit Name</th>
                            <th>Frequency</th>
                            <th class="hidden-xxs">Milestone</th>
                            <th class="hidden-xs">Shared</th>
                            <th class="center">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($habitData as $habits)

                            <?php

                            /*$allmileNameArr=[];
                              $allMileNameData='';

                              if(isset($habits->habitmilestone)){
                                  $allmileNameArr[]=$habits->habitmilestone->pluck('gb_milestones_name')->toArray();
                                    if(isset($allmileNameArr[0]))
                                         $allMileNameData=implode(", ",$allmileNameArr[0]);



                              }*/

                            ?>

                            <tr>
                                <td>{{isset($habits->gb_habit_name)?$habits->gb_habit_name:null}}</td>
                                <td><?php if($habits->gb_habit_recurrence_type == 'weekly') echo 'Every '.$habits->gb_habit_recurrence_week; elseif($habits->gb_habit_recurrence_type == 'monthly') echo 'Day '.$habits->gb_habit_recurrence_month .' of every month';
                                    else echo ucfirst($habits->gb_habit_recurrence_type) ;  ?></td>

                                <td>{{implode(', ', $habits->getMilestoneNames())}}</td>
                                <td>{{isset($habits->gb_habit_seen)?$habits->gb_habit_seen:null}}</td>
                                <td class="center">

                                    <a class="btn btn-xs btn-default tooltips habit-edit" data-placement="top" data-original-title="Edit" data-habit-id = "{{$habits->id}}">
                                        <i class="fa fa-pencil" style="color:#ff4401;"></i>
                                    </a>

                                    <a class="btn btn-xs btn-default tooltips delete-habit" data-entity="habit" data-placement="top" data-original-title="Delete" data-habit-id = "{{$habits->id}}">
                                        <i class="fa fa-times" style="color:#ff4401;"></i></a>

                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div> <!-- end habit listing -->
           @endif

            <div class="row habit-form" @if((isset($habitData))&&($habitData->count() > 0) && (Request::segment(2) != 'edithabit')) style="display: none;" @endif>
                <form name="goalBuddy" id="build_new_habit_form" method="POST">
                    <div class="sucMes hidden"></div>

                    <div class="col-md-12">
                        @if (Request::segment(2) == 'edithabit')
                        <input type="hidden" class="form-control" id="Habit-from-list" value="habitfromlist" name="updatehabitfromlist">
                        @endif
                        <!--input type="hidden" class="form-control" id="habit-id" value="" name="habit_id"-->

                        @include('Result.goal-buddy.createhabits')
                    </div>

                    <div class="row row-btn-step-container">
                      <div class="col-sm-2 pull-right">
                        <button ng-disabled="goadBuddy.SYG_habits.$invalid" class="btn btn-primary  btn-wide btn-step margin-right-15" id="create_habbit_btn" data-step="1"> Establish a Habit </button>
                      </div>

                      <div class="col-sm-2 pull-right cancel_habbit_btn hidden" >
                        <button class="btn btn-danger  btn-wide  margin-right-15" id="cancel_habbit" type="button"> Cancel </button>
                      </div>
                    </div>
                </form> <!-- end form -->
           </div> <!-- end row -->
        </div>
        <!-- end: PANEL BODY -->
    </div>
    @endif
    <!-- end: acc3 -->


     <!-- start: acc4 -->
     @if (Request::segment(2) != 'edithabit' && Request::segment(2) != 'editgoal' && Request::segment(2) != 'editmilestone')
     <div class="panel panel-white">
        <!-- start: PANEL HEADING -->
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
                    <a data-step="4"  id="Manage_Tasks" class="btn btn-xs btn-step-toggle pull-right panel-collapse @if (Request::segment(2) != 'edittask') closed @endif" href="#" data-panel-group="client-overview">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </span>
            </h5>
        </div>
        <!-- start: PANEL HEADING -->

        <!-- start: PANEL BODY -->
        <div class="panel-body gb-step-edit-4" ng-controller="GBWidgetFour">

            @if (Request::segment(2) != 'edittask')
            <div class="row task-listing"  @if((isset($taskData))&&(count($taskData) < 1)) style="display:none; " @endif>
                <div class ="col-md-12 p-l-0 padding-right-0">
                  {{--<h6 class="m-b-0"><em class="task-name"></em></h6>--}}
                </div>

                <div class ="row">
                  <div class ="col-md-6">
                    <h6 class="m-t-10"><em>Would you like to establish another task?</em></h6>
                  </div>
                  <div class ="col-md-6">
                      <a class ="btn btn-primary pull-right add-task">Schedule New Task</a>
                  </div>
                </div>

                <table class="table table-striped table-bordered table-hover m-t-10 " id="client-datatable-task">
                  <thead>
                    <tr>
                      <th class="hidden-xxs">Task Name</th>
                      <th class="center mw-70 w70 no-sort">Priority</th>
                      <!--th class="hidden-xxs">Due Date</th-->
                      <th class="hidden-xs">Habit</th>
                      <th class="center mw-70 w70 no-sort">Shared</th>
                      <th class="center mw-70 w70 no-sort">Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                  @foreach($taskData as $tasks)
                  <tr>
                    <td>{{isset($tasks->gb_task_name)?$tasks->gb_task_name:''}}</td>
                    <td>{{isset($tasks->gb_task_priority)?ucfirst($tasks->gb_task_priority):''}}</td>

                    <td>{{isset($tasks->taskhabit->gb_habit_name)?$tasks->taskhabit->gb_habit_name:''}}</td>
                    <td>{{isset($tasks->gb_task_seen)?$tasks->gb_task_seen:''}}</td>
                    <td class="center">

                        <a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "{{$tasks->id}}">
                            <i class="fa fa-pencil" style="color:#ff4401;"></i>
                        </a>

                         <a class="btn btn-xs btn-default tooltips delete-task" data-entity="task" data-placement="top" data-original-title="Delete" data-task-id = "{{$tasks->id}}">
                            <i class="fa fa-times" style="color:#ff4401;"></i></a>

                      </td>
                  </tr>
                   @endforeach
                  </tbody>
                </table>
            </div>
            @endif

            <div class="task-form"  @if((isset($taskData))&&($taskData->count() > 0) && (Request::segment(2) != 'edittask')) style="display: none;" @endif>
                <form name="goalBuddy" id="manage_task_form" method="POST">
                    <div class="sucMes hidden"></div>

                    <div class="col-md-12">
                        @if (Request::segment(2) == 'edittask')
                        <input type="hidden" class="form-control" id="task-from-cal" value="taskfromcal" >
                        @endif

                        @include('Result.goal-buddy.createtask')
                    </div> <!-- end col12 -->

                    <div class="row row-btn-step-container">
                        <div class="col-sm-2 pull-right">
                           <button ng-disabled="goalBuddy.habit_value.$invalid ||
                                    goalBuddy.SYG3_task.$invalid ||
                                    goalBuddy.SYG3_priority.$invalid" class="btn btn-primary  btn-wide btn-step  margin-right-15" id="manage_task_btn" data-step="2"> Establish a Task </button>
                        </div>

                        <div class="col-sm-2 pull-right cancel_task_btn hidden" >
                           <button type="button" class="btn btn-danger btn-wide pull-right margin-right-15  " id="cancel_task" > Cancel
                           </button>
                        </div>
                    </div>
                </form> <!-- end form -->
            </div>

         </div>
         <!-- end: PANEL BODY -->
    </div> <!-- end panel -->
    @endif



    <!-- end: acc3 -->
    <!-- review stat -->
    <!-- start: acc2 -->
    <input type="hidden" id="review_data" value="{{isset($review_data)?$review_data:''}}" name="review_data">

     @if (Request::segment(2) != 'edittask' && Request::segment(2) != 'editgoal'&& Request::segment(2) != 'edithabit' && Request::segment(2) != 'editmilestone')

    <div class="panel panel-white">
        <!-- start: PANEL HEADING -->
        <div class="panel-heading">
            <h5 class="panel-title">
                <span class="icon-group-left">
                    <i class="clip-menu"></i>
                </span>
                Manage Smart Review
                <span class="icon-group-right">
                    <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <a data-step="5" id="smart_review_a" class="btn btn-xs btn-step-toggle pull-right panel-collapse   @if (Request::segment(2) != 'editmilestone') closed @endif" href="#" data-panel-group="client-overview">
                        <i class="fa fa-chevron-down"></i>
                    </a>
                </span>
            </h5>
        </div>
        <!-- end: PANEL HEADING -->

        <!-- start: PANEL BODY -->
        <div class="panel-body gb-step-edit-5">
            <div class="row milestone-form" >
                <form id="smart_review_form" method="POST">
                    <div class="sucMes hidden"></div>

                    <div class="col-md-12 p-l-5 padding-right-0">
                    @if (Request::segment(2) == 'editmilestone')
                    <input type="hidden" class="form-control" id="Milestone-from-list" value="milestonefromlist" name="updatemilestonefromlist">
                    @endif

                       @include('Result.goal-buddy.smartreview')
                    </div>

                    <div class="row">
                        <!-- <div class="col-sm-6">
                          <button class="btn btn-primary btn-o back-step btn-wide pull-left"> <i class="fa fa-circle-arrow-left"></i> Back </button>
                        </div> -->
                        <div class="col-sm-12">
                          <button class="btn btn-primary btn-o btn-wide pull-right final-step-goalbuddy_edit" id="final_step_id"><i class="fa fa-spinner fa-spin fa-fw finalSubmitLoader" style="display:none;"></i> Finish <i class="fa fa-arrow-circle-right"></i> </button>
                        </div>
                    </div>
                </form>
           </div>
      </div>
      <!-- end: PANEL BODY -->
    </div>
    @endif
    <!-- end: acc2 -->

    <!-- review end -->

</div>
<!-- end: ngApp = vp-form -->
<div class="rightimagefixed" style="background-image: url('{{ asset('result/images/calcul-2.jpeg') }}');">
    <div class="note_area">
       <textarea rows="5" data-autoresize id="goal_notes" name="describe_achieve" placeholder="GENERAL NOTES" class="form-control">{{ !empty($goalDetails) && isset($goalDetails->gb_goal_notes) ? ucwords($goalDetails->gb_goal_notes) : ''}}</textarea>
    </div>
</div>

@stop

@section('required-script')
{!! Html::script('result/js/jquery-ui.min.js') !!}

<!-- start: Moment Library -->
{!! Html::script('result/plugins/moment/moment.min.js') !!}
<!-- end: Moment Library -->      
{!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}
{!! Html::script('result/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
{!! Html::script('result/plugins/Jcrop/js/script.js') !!}
{!! Html::script('result/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
{!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!} 

{!! Html::script('result/js/form-wizard-goal-buddy.js?v='.time()) !!}
{!! Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js') !!}

{!! Html::script('result/js/helper.js?v='.time()) !!}
{!! Html::script('result/js/goal-buddy.js?v='.time()) !!} 

<script>
    setTimeout(function() {
        $('#set_your_goal').trigger('click');
        var reviewData = $('#review_data').val();
        reviewData = JSON.parse(reviewData);
        var step1Completed = reviewData.is_step_completed;
        if(step1Completed == 0){
            $("#m-selected-step").val(1);
        }else{
            var step2Completed = true;
            if(reviewData.milestones.length > 0){
                $.each(reviewData.milestones,function(key,obj){
                    if(obj.is_step_completed == 0){
                        step2Completed = false;
                        $("#m-selected-step").val(2);
                        $('#Build_Milestone').trigger('click');
                        return false;
                    }
                });
            }else{
                step2Completed = false;
                $("#m-selected-step").val(2);
                $('#Build_Milestone').trigger('click');
            }
            if(step2Completed){
                var step3Completed = true;
                $.each(reviewData.taskhabit,function(key,obj){
                    if(obj.is_step_completed == 0){
                        step3Completed = false;
                        $("#m-selected-step").val(3);
                        $('#Build_Habits').trigger('click');
                        $('.habit-edit').each(function(){
                            if($(this).data('habit-id') == obj.id){
                                $(this).trigger('click');
                            }
                        })
                        return false;
                    }
                });
                if(step3Completed){
                    var step4Completed = true;
                    $.each(reviewData.taskdata,function(key,obj){
                        if(obj.is_step_completed == 0){
                            step4Completed = false;
                            $("#m-selected-step").val(4);
                            $('#Manage_Tasks').trigger('click');
                            $('.task-edit').each(function(){
                                if($(this).data('task-id') == obj.id){
                                    $(this).trigger('click');
                                }
                            })
                            return false;
                        }
                    });
                    
                    if(step4Completed){
                        if(reviewData.final_submitted == 0){
                            $("#m-selected-step").val(5);
                            $('#smart_review_a').trigger('click');
                        }
                    }
                }
            }
        }
        $("#m-selected-step").trigger('change');
    }, 500);

    $(document).ready(function () {
        $('.add-habit, .habit-edit').click(function() {
            $('#m-selected-step').val(3).trigger('change');
        });

        $('.add-task, .task-edit').click(function() {
            $('#m-selected-step').val(4).trigger('change');
        })
    });
</script>

    <script type="text/javascript">
        $.each(jQuery('textarea[data-autoresize]'), function() {
            var offset = this.offsetHeight - this.clientHeight;
             
            var resizeTextarea = function(el) {
                $(el).css('height', 'auto').css('height', el.scrollHeight + offset);
            };
            jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
        });

        $(document).ready(function() {
            Tipped.create('[data-toggle="tooltip"]', {
                skin: 'light', 
                radius: true, 
                size:'large',
            });
        });
    </script>


@stop()

@section('script-handler-for-this-page')
    $( ".panel-collapse.closed" ).trigger( "click" );
@stop()

