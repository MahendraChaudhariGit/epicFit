
  <div class="col-md-12 client-goalbuddylist"> 
  <h1 class="clearfix">
  Goal list ({{$goalListData->count()}})
    <!--a class="btn btn-primary" href="{{ route('goal-buddy.print') }}" style ="margin-left: 500px;"><i class="ti-printer"></i> Print Goals</a--> 
    <a class="btn btn-primary pull-right create-goal"><i class="ti-plus"></i> Set New Goal</a></h1>

   
    <table class="table table-striped table-bordered table-hover m-t-10 " id="client-datatable " >
      <thead>
        <tr>
          <th>Goal Name</th>
          <th class="hidden-xxs">Shared</th>
          <th class="hidden-xs">DueDate</th>
          <th class="hidden-xs" width="19%">Progress</th>
          <th class="center ">Actions</th>
        </tr>
      </thead>
      <tbody>
      
      @foreach($goalListData as $goalInfo)
      <tr class="goal-row  @if($goalInfo->gb_goal_status == 1) completed @endif" id = "{{$goalInfo->id}}">
        <input type="hidden" name ="goal_id" id ="goal-id" value ="{{$goalInfo->id}}">
        <td><a class="goal-name">{{isset($goalInfo->gb_goal_name )?$goalInfo->gb_goal_name :null}}</a> <br>
          <div class="col-md-12 milestones hide" id="milestones-{{$goalInfo->id}}" > <strong>Milestones:</strong>
            <p>(tasks)</p>
            <div class="checkbox clip-check check-primary" style ="">
              <?php $persent=1; $totalpersent=0; ?>
              @if($goalDetailsData[$goalInfo->id]['milestones']->count() > 0)
              <?php  $milestonesNo = $goalDetailsData[$goalInfo->id]['milestones']->count();
                                              
                                                  if($milestonesNo > 0 )
                                                   $milestonesPercentage=100/$milestonesNo; 
                                                 
                                            ?>
              @foreach($goalDetailsData[$goalInfo->id]['milestones'] as $milestonesInfo)
              <input type="checkbox" name="milestone_compleate" id="milestone-compleate-{{$milestonesInfo->id}}"  @if($milestonesInfo->
              gb_milestones_status==1)  checked @endif value="1" class="milestone-goal" data-milestones-id="{{$milestonesInfo->id}}" data-percentage="{{$milestonesPercentage}}">
              <label for="milestone-compleate-{{$milestonesInfo->id}}"><strong> {{$milestonesInfo->gb_milestones_name}}</strong></label>
              <br>
              <?php 
                                             if($milestonesInfo->gb_milestones_status==1)
                                                $totalpersent=$persent * $milestonesPercentage;
                                              else $totalpersent=0;
                                                     $persent++; ?>
              @endforeach 
              @endif </div>
          </div></td>
        <td> {{isset($goalInfo->gb_goal_seen )?$goalInfo->gb_goal_seen :null}} </td>
        <td>{{isset($goalInfo->gb_due_date )?dbDateToDateString($goalInfo->gb_due_date) :null}} </td>
        <td><div class="progress progress-striped progress-xs " >
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="@if($totalpersent) {{$totalpersent}}  @else {{$totalpersent}} @endif" style="width: @if($totalpersent) {{$totalpersent}}%  @else   {{$totalpersent}}% @endif"> </div>
          </div>
          <p class ="progress-percentage"> @if($totalpersent) {{round($totalpersent,2)}} %  @else   {{round($totalpersent,2)}}% @endif </p>
          <div class="col-md-12 habits hide" id="habit-{{$goalInfo->id}}"> @if(count($goalDetailsData[$goalInfo->id]['habits'])>0) <strong>Habits:</strong>
            <input type ="hidden" name ="goal_name" id ="goal-name" value ="{{isset($goalInfo->gb_goal_name )?$goalInfo->gb_goal_name :null}} ">
            @foreach($goalDetailsData[$goalInfo->id]['habits'] as $habitsInfo) <a data-toggle="modal" data-target="#habit-modal" class="listing-habit-name" data="{{ $habitsInfo['h_id'] }}"><span>{{$habitsInfo['h_name']}}</span></a> <span>{{isset($habitsInfo['h_seen'] )?$habitsInfo['h_seen'] :null}}</span>
            <p><strong>Completed:</strong><span class="completed-habit">{{isset($completed[$habitsInfo['h_id']] )?$completed[$habitsInfo['h_id']] :null}}</span> <strong>Missed:</strong><span class="missed-habit">{{isset($missed[$habitsInfo['h_id']])?$missed[$habitsInfo['h_id']] :null}}</span> <strong>Success:</strong><span class="success-habit">{{ isset($success[$habitsInfo['h_id']])?round($success[$habitsInfo['h_id']],2) :0}}%</span></p>
            @endforeach 
            @endif </div></td>
        <td class="center"><div> <a class="btn btn-xs btn-default tooltips" href="{{ route('goal-buddy.edit', $goalInfo->id) }}" data-placement="top" data-original-title="Edit"> <i class="fa fa-pencil" style="color:#ff4401;"></i> </a> </div>
          <div> <a class="btn btn-xs btn-default tooltips delete-goal" href="#" data-placement="top" data-original-title="delete" data = "{{ $goalInfo->id }}" style ="margin-left:57px;margin-top:-43px;"> <i class="fa fa-times" style="color:#ff4401;"></i> </a> </div></td>
      </tr>
      @endforeach
        </tbody>
      
    </table>
    
      </div>
    
