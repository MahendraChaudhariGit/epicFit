@extends('Result.masters.app')
@section('required-styles')
    {!! Html::style('result/css/custom.css?v='.time()) !!}
    {!! Html::style('result/plugins/DataTables/media/css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!} 
    <style type="text/css">
      .pagination>.active>a, .pagination>.active>span, .pagination>.active>a:hover, .pagination>.active>span:hover, .pagination>.active>a:focus, .pagination>.active>span:focus {

    background-color: #f64c1e;
    border-color: #f64c1e;
}

    </style>
@stop
    

@section('content')
{!! displayAlert()!!}
 <!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form --> 

    <div class="row goal-action" style="text-align: center; display: flex">
       <!--  <div class="col-xs-12 col-sm-3 goal-action-item">
            <div style="margin-left: 15px;display: inline-block;margin-top: 16px;float: left;">Goal list ({{count($goals)}})</div>
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
                <a class="btn btn-primary hide" href="{{ route('goal-buddy.print') }}" style ="margin-left: 500px;"><i class="ti-printer"></i> Print Goals</a>
                <a class="btn btn-primary pull-right create-goal" style="margin-top: 7px;" href="{{ route('goal-buddy.create') }}"><i class="ti-plus"></i> Set New Goal</a>
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
                   @foreach($goals as $goalInfo)
                        <tr class="goal-row  @if($goalInfo->gb_goal_status == 1) completed @endif" id = "{{$goalInfo->id}}">
                        <input type="hidden" name ="goal_id" id ="goal-id" value ="{{$goalInfo->id}}">
                            <td><a class="goal-name">{{isset($goalInfo->gb_goal_name )?$goalInfo->gb_goal_name :null}}</a>
                              <br>
                              <div class="col-md-12 milestones hide" id="milestones-{{$goalInfo->id}}">
                                 <strong>Milestones:</strong>
                                 <!--div class="checkbox clip-check check-primary" style="margin-left: 21px;">
                                                <input type="checkbox" name="goal_compleate" id="goal-compleate-{{$goalInfo->id}}"  @if($goalInfo->gb_goal_status)  checked @endif value="1" class="compleate-goal">
                                                <label for="goal-compleate-{{$goalInfo->id}}"><strong>Goal Completed</strong></label>
                                  </div-->
                                  <div class="checkbox clip-check check-primary" style ="">
                                               <?php $persent=1; $totalpersent=0; ?>
                                           @if(isset($goalInfo->goalBuddyMilestones)) 
                                           <?php  $milestonesNo=$goalInfo->goalBuddyMilestones->count();
                                              
                                                  if($milestonesNo > 0 )
                                                   $milestonesPercentage=100/$milestonesNo; 
                                                 
                                            ?>
                                            @foreach($goalInfo->goalBuddyMilestones as $milestonesInfo)
                                        
                                          <input type="checkbox" name="milestone_compleate" id="milestone-compleate-{{$milestonesInfo->id}}"<?php echo $milestonesInfo->gb_milestones_status ? ' checked="checked"' : '' ?> value="1" class="milestone-goal" data-milestones-id="{{$milestonesInfo->id}}" data-percentage="{{$milestonesPercentage}}" autocomplete="off" />
                                                  
                                                      <label for="milestone-compleate-{{$milestonesInfo->id}}"><strong>
                                                     {{$milestonesInfo->gb_milestones_name}}</strong></label>
                                                      </br>
                                                     <?php 
                                             if($milestonesInfo->gb_milestones_status==1)
                                                $totalpersent+=$milestonesPercentage; ?> 
                                                   @endforeach 
                                                  @endif
                                       
                                                
                                  </div>
                                 </div>
                            </td>
                            <td>
                             {{ isset($goalInfo->gb_goal_seen )?$goalInfo->gb_goal_seen :null }} 
                            </td>  
                            <td>
                              {{ isset($goalInfo->gb_due_date )?dbDateToDateString($goalInfo->gb_due_date):null }}
                            </td>
                            <td>
                              <div class="progress progress-striped progress-xs" style="margin-bottom:10px" >
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="@if($totalpersent) {{$totalpersent}}  @else {{$totalpersent}} @endif" style="width: @if($totalpersent) {{$totalpersent}}%  @else   {{$totalpersent}}% @endif">
                                    </div>

                                </div>
                                
                               <p><strong>Milestones:</strong> <span class ="progress-percentage">{{ round($totalpersent,2) }}%</span></p>

                               <div class="col-md-12 habits hide" id="habit-{{$goalInfo->id}}">
                                @if(isset($goalInfo->goalBuddyHabit))  
                                  <strong>Habits:</strong>
                                  <input type ="hidden" name ="goal_name" id ="goal-name" value ="{{isset($goalInfo->gb_goal_name )?$goalInfo->gb_goal_name :null}} ">

                               
                                 @foreach($goalInfo->goalBuddyHabit as $habitsInfo)
                                 <a data-toggle="modal" data-target="#habit-modal" class="listing-habit-name" data="{{ $habitsInfo->id }}"><span>{{$habitsInfo->gb_habit_name}}</span></a>

                                 <span>{{isset($habitsInfo->gb_habit_seen )?$habitsInfo->gb_habit_seen :null}}</span>
                                  
                                  <p><strong>Completed: </strong><span class="completed-habit">{{isset($completed[$habitsInfo->id] )?$completed[$habitsInfo->id] :null}}</span><br />
                                  <strong>Missed: </strong><span class="missed-habit">{{isset($missed[$habitsInfo->id])?$missed[$habitsInfo->id] :null}}</span><br />
                                  <strong>Success: </strong><span class="success-habit">{{ round($success[$habitsInfo->id],2) }}%</span></p>

                                 @endforeach 
                                 @endif

                                  </div>


                            </td>
                            <td class="center">
                                <div>
                                    <a class="btn btn-xs btn-default tooltips" href="{{ route('goal-buddy.edit', $goalInfo->id) }}" data-placement="top" data-original-title="Edit">
                                            <i class="fa fa-pencil" style="color:#ff4401;"></i>
                                    </a>
                                </div>
                                <div>
                                    <a class="btn btn-xs btn-default tooltips delete-goal" data-entity="goal"  data-placement="top" data-original-title="delete" data = "{{ $goalInfo->id }}" style ="margin-left:57px;margin-top:-43px;">
                                        <i class="fa fa-times" style="color:#ff4401;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach 
                    </tbody>
                </table>
                    <!-- start: Paging Links -->
                    {!! $goals->render() !!}
                    {{-- {{ $goals->links() }} --}}
                    <!-- end: Paging Links -->

                             <!--habit model-->
                              @include('Result.goal-buddy.habitmodel')    
                            <!--habit model-->

            </div>
        </div>
    </div>
</div>
@stop
@section('required-script')
{!! Html::script('result/js/jquery-ui.min.js') !!}

<!-- start: Moment Library -->
{!! Html::script('result/plugins/moment/moment.min.js') !!}
<!-- end: Moment Library -->
 {!! Html::script('result/plugins/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('result/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}  
 {!! Html::script('result/plugins/jquery-validation/jquery.validate.min.js') !!}
{!! Html::script('result/plugins/sweetalert/sweet-alert.min.js') !!} 
{!! Html::script('result/js/form-wizard-goal-buddy.js?v='.time()) !!}
{!! Html::script('result/js/helper.js?v='.time()) !!}
{!! Html::script('result/plugins/DataTables/media/js/jquery.dataTables.min.js') !!}
{!! Html::script('result/plugins/DataTables/media/js/dataTableDateSort.js') !!}
{!! Html::script('result/plugins/DataTables/media/js/dataTables.bootstrap.min.js') !!}

{!! Html::script('result/js/goal-buddy.js?v='.time()) !!}

<script>
var cookieSlug = "goal";
$(document).ready(function(){
    $.fn.dataTable.moment('ddd, D MMM YYYY');
    $('#goal-datatable').dataTable({"searching": false, "paging": false, "info": false });
})

</script>
@stop
