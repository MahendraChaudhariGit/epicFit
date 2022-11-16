<div class="row ">
  <input type="hidden" class="form-control" id="task-id" value="{{isset($taskDetails)?$taskDetails->id:null}}" name="task_id">
  <h4 class ="p-l-15"><em>What Do I Need To Do to Accomplish My Goal?</em></h4>
  <div class="col-md-6">
    <fieldset class="padding-15">
      <legend>Accomplish the goal</legend>
      <div class ="task-habit-div" @if((isset($habitData))&& ($habitData->count() < 1)) style="display: none;" @endif>
        <div class="form-group btn_dd">
          <label for="task_habit_div" class="strong task-habit-label">Is this task associated with a habit of this goal? *</label>
          <div class="task-habit-dropdown">
          @if((isset($habitData))&&(count($habitData) > 0))
            <select id="habit_div" name="habit_value" class="form-control onchange-set-neutral taskhabit_div_class" required="">
              <option value="">-- Select --</option>
              @foreach($habitData as $habitval)
                @if(isset($taskDetails) &&  $habitval->id==$taskDetails->gb_habit_id)
                  <option value="{{$habitval->id}}" selected="">{{$habitval->gb_habit_name}}</option>
                @else
                  <option value="{{$habitval->id}}">{{$habitval->gb_habit_name}}</option>
                @endif
              @endforeach
            </select>
          @endif
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="SYG3_task" class="strong">Name Your task  *</label>
        <input type="text" id="SYG3_task" value="{{isset($taskDetails)?$taskDetails->gb_task_name:null}}" name="SYG3_task" class="form-control" required>
      </div>
      <div class="form-group">
        <label for="SYG3_priority" class="strong">Priority   *</label>
        <select id="SYG3_priority" name="SYG3_priority" class="form-control selectpicker onchange-set-neutral" required>
          <option value="Low" {{ isset($taskDetails) && $taskDetails->gb_task_priority == 'Low' ?'selected':'' }}>Low</option>
          <option value="Normal" {{ isset($taskDetails) && $taskDetails->gb_task_priority == 'Normal' ?'selected':'' }}>Normal</option>
          <option value="High" {{ isset($taskDetails) && $taskDetails->gb_task_priority == 'High' ?'selected':'' }}>High</option>
          <option value="Urgent" {{ isset($taskDetails) && $taskDetails->gb_task_priority == 'Urgent' ?'selected':'' }}>Urgent</option>
        </select>
      </div>
      <div class="form-group">
        <label class="strong">Task Recurrence</label>
        <div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="SYG_task_recurrence" id="SYG_task_recurrence0" value="daily"  {{ isset($taskDetails) && $taskDetails->gb_task_recurrence_type == 'daily'?'checked':'' }} @if(!isset($taskDetails)) checked @endif>
            <label for="SYG_task_recurrence0"> Daily </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="SYG_task_recurrence" id="SYG_task_recurrence1" value="weekly" {{ isset($taskDetails) && $taskDetails->gb_task_recurrence_type == 'weekly'?'checked':'' }}>
            <label for="SYG_task_recurrence1"> Weekly </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="SYG_task_recurrence" id="SYG_task_recurrence2" value="monthly" {{ isset($taskDetails) && $taskDetails->gb_task_recurrence_type == 'monthly'?'checked':'' }}>
            <label for="SYG_task_recurrence2"> Monthly </label>
          </div>
          <div class="show-weeks-task"  @if((isset($taskDetails))&&($taskDetails->gb_task_recurrence_type=='weekly')) style="display: block;" @else style="display: none;"   @endif>
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
              <input id="taskEventRepeatWeekdays0" class="taskEventRepeatWeekdays" value="Monday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Monday',$taskDetails->gbHabitWeekDetails))) checked @endif >
              <label for="taskEventRepeatWeekdays0"> Mon </label>
            </div>
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
              <input id="taskEventRepeatWeekdays1" class="taskEventRepeatWeekdays" value="Tuesday" type="checkbox" @if(isset($taskDetails) &&($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Tuesday',$taskDetails->gbHabitWeekDetails))) checked @endif>
              <label for="taskEventRepeatWeekdays1"> Tue </label>
            </div>
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
              <input id="taskEventRepeatWeekdays2" class="taskEventRepeatWeekdays" value="Wednesday" type="checkbox" @if(isset($taskDetails) &&($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Wednesday',$taskDetails->gbHabitWeekDetails))) checked @endif>
              <label for="taskEventRepeatWeekdays2"> Wed </label>
            </div>
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
              <input id="taskEventRepeatWeekdays3" class="taskEventRepeatWeekdays" value="Thursday" type="checkbox" @if(isset($taskDetails) &&($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Thursday',$taskDetails->gbHabitWeekDetails))) checked @endif>
              <label for="taskEventRepeatWeekdays3"> Thu </label>
            </div>
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
              <input id="taskEventRepeatWeekdays4" class="taskEventRepeatWeekdays" value="Friday" type="checkbox" @if(isset($taskDetails) &&($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Friday',$taskDetails->gbHabitWeekDetails))) checked @endif>
              <label for="taskEventRepeatWeekdays4"> Fri </label>
            </div>
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
              <input id="taskEventRepeatWeekdays5" class="taskEventRepeatWeekdays"  value="Saturday" type="checkbox" @if(isset($taskDetails) &&($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Saturday',$taskDetails->gbHabitWeekDetails))) checked @endif>
              <label for="taskEventRepeatWeekdays5"> Sat </label>
            </div>
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
              <input id="taskEventRepeatWeekdays6" class="taskEventRepeatWeekdays" value="Sunday" type="checkbox" @if(isset($taskDetails) &&($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Sunday',$taskDetails->gbHabitWeekDetails))) checked @endif>
              <label for="taskEventRepeatWeekdays6"> Sun </label>
            </div>
  
          </div>

          <div class="month-count-task" @if((isset($taskDetails))&&($taskDetails->gb_task_recurrence_type=='monthly')) style="display: block;" @else style="display: none;"   @endif> 
           @if(Request::segment(2) == 'edittask')
           <div class ="month-count-task-div">Day <select class="month-date-task">
            @for($i = 1; $i <= calDaysInMonth(); $i++) 
              @if($i==$taskDetails->gb_task_recurrence_month)
                <option value ="{{ $i }}" selected="">{{ $i }}</option>
               @else
               <option value ="{{ $i }}">{{ $i }}</option>
              @endif
            @endfor
           </select>
           </div>
           @endif
           </div>
        </div>
      </div>
    </fieldset>
  </div>
  <div class="col-md-6">
    <fieldset class="padding-15">
      <legend>Sharing</legend>
      <div class="form-group">
        <label class="strong">Who can see this task? </label>
        <div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="SYG3_see_task" id="SYG3_see_task0" value="everyone">
            <label for="SYG3_see_task0"> Everyone </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="SYG3_see_task" id="SYG3_see_task1" value="task-friends">
            <label for="SYG3_see_task1"> My Friends </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="SYG3_see_task" id="SYG3_see_task2" value="Just Me" checked="">
            <label for="SYG3_see_task2"> Just Me </label>
          </div>
          <div class ="task-friends-section" style="display: none;">
            <table>
              <tr>
                <td><button type="button" class="btn btn-wide btn-o btn-success" style="margin-bottom: 5px;" data-toggle="modal" data-target="#taskfriendModal">Choose</button>
                  </br>
                  <button type="button" class="btn btn-wide btn-o btn-success" data-toggle="modal" data-target="#taskfriendModal">Friends</button></td>
                <td><span> <a href="javascript:choose_friends()" id="view_count" style="margin: 0px 0px 5px 5px;">0 Friends can view</a></br>
                  <a href="javascript:choose_friends()" id="edit_count" style="margin: 0px 0px 5px 5px;">0 Friends can edit</a> </span></td>
              </tr>
            </table>
          </div>
          <div id="taskfriendModal" class="modal fade" role="dialog">
            <div class="modal-dialog"> 
              
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <div class ="row">
                    <div class ="col-md-6">
                      <h4> Who can see this task?</h4>
                    </div>
                    <div class ="col-md-6">
                      <input type ="checkbox" name ="all-friends">
                      All my friends can view this task </div>
                  </div>
                  <div class ="row">
                    <div class ="col-md-6">
                      <h4>Your friends:</h4>
                    </div>
                    <div class ="col-md-6">
                      <h4>Friends with access to this task:</h4>
                    </div>
                    <button type="button" class="btn btn-info btn-lg" style ="margin: 10px 0px 13px 15px;" data-dismiss="modal" data-size="l">Ok</button>
                  </div>
                  <div class="modal-footer"> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="strong">Send e-mail / SMS reminders </label>
        <div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="SYG3_send_msg" id="SYG3_send_msg0" value="When_task_is_overdue" checked="">
            <label for="SYG3_send_msg0"> When task is overdue </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="SYG3_send_msg" id="SYG3_send_msg1" value="When_task_is_due">
            <label for="SYG3_send_msg1">When task is due </label>
          </div>
          <div class="radio clip-radio radio-primary radio-inline m-b-0">
            <input type="radio" name="SYG3_send_msg" id="SYG3_send_msg2" value="none" >
            <label for="SYG3_send_msg2"> None </label>
          </div>
        </div>
      </div>
    </fieldset>
  </div>
</div>