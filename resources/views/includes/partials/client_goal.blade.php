<!-- start: Panel -->


 <div class="row " id="showcalendar">
      <div class="col-md-12"> {!! Form::select('type', ['' => '-- Select --', 'goal' => 'Goal', 'habit' => 'Habit', 'task' => 'Task'], null,  ['class' => 'form-control goalbuddy-event-dd']) !!}
        <div id='full-calendar'></div>
      </div>
    </div>
  <!--habit model-->
                              @include('goal-buddy.habitmodel')    
                            <!--habit model-->
                             <!--task model-->
                              @include('goal-buddy.taskmodel')    
                            <!--task model-->
                             <!--goal model-->
                              @include('goal-buddy.goalmodel')    
                            <!--goal model-->
<!-- end:  Panel -->

<!-- start: goallist -->
<div class="row clientGoalListCls hidden" > 
@include('goal-buddy.clientgoallisting', ['goalListData' => $goalListData]) 
</div>
<!-- end:goallist -->

<!-- start: clientlist -->
<div class="row clientshowListCls hidden" > 
@include('goal-buddy.goalbuddy_client', ['allClientArray' => $allClientArray,'countries' => $countries]) 
</div>
<!-- end:clientlist -->
