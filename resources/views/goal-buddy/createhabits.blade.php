<input type="hidden" class="form-control" id="update-hebit" value="" name="update_habit">
<input type="hidden" class="form-control" id="habit-id" value="{{isset($habitDetails)?$habitDetails->id:null}}" name="habit_id">

<div class="col-md-6">
  <fieldset class="padding-15">
    <legend>Habits</legend>
    <div class="form-group">
      <label for="SYG_habits" class="strong">Name Your Habit  *</label>
      <input type="text" id="SYG_habits" value="{{isset($habitDetails)?$habitDetails->gb_habit_name:null}}" name="SYG_habits" class="form-control" required>
    </div>
    <div class="form-group">
      <label class="strong">Habit Recurrence</label>
      <div>
        <div class="radio clip-radio radio-primary radio-inline m-b-0">
          <input type="radio" name="SYG_habit_recurrence" id="SYG_habit_recurrence0" value="daily"  {{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'daily'?'checked':'' }} @if(!isset($habitDetails)) checked @endif>
          <label for="SYG_habit_recurrence0"> Daily </label>
        </div>
        <div class="radio clip-radio radio-primary radio-inline m-b-0">
          <input type="radio" name="SYG_habit_recurrence" id="SYG_habit_recurrence1" value="weekly" {{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'weekly'?'checked':'' }}>
          <label for="SYG_habit_recurrence1"> Weekly </label>
        </div>
        <div class="radio clip-radio radio-primary radio-inline m-b-0">
          <input type="radio" name="SYG_habit_recurrence" id="SYG_habit_recurrence2" value="monthly" {{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'monthly'?'checked':'' }}>
          <label for="SYG_habit_recurrence2"> Monthly </label>
        </div>
        <div class="show-weeks"  @if((isset($habitDetails))&&($habitDetails->gb_habit_recurrence_type=='weekly')) style="display: block;" @else style="display: none;"   @endif>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
            <input id="goalEventRepeatWeekdays0" class="goalEventRepeatWeekdays" value="Monday" type="checkbox"  @if(isset($habitDetails) && (in_array('Monday',$habitDetails->gbHabitWeekDetails))) checked @endif >
            <label for="goalEventRepeatWeekdays0"> Mon </label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
            <input id="goalEventRepeatWeekdays1" class="goalEventRepeatWeekdays" value="Tuesday" type="checkbox" @if(isset($habitDetails) && (in_array('Tuesday',$habitDetails->gbHabitWeekDetails))) checked @endif>
            <label for="goalEventRepeatWeekdays1"> Tue </label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
            <input id="goalEventRepeatWeekdays2" class="goalEventRepeatWeekdays" value="Wednesday" type="checkbox" @if(isset($habitDetails) && (in_array('Wednesday',$habitDetails->gbHabitWeekDetails))) checked @endif>
            <label for="goalEventRepeatWeekdays2"> Wed </label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
            <input id="goalEventRepeatWeekdays3" class="goalEventRepeatWeekdays" value="Thursday" type="checkbox" @if(isset($habitDetails) && (in_array('Thursday',$habitDetails->gbHabitWeekDetails))) checked @endif>
            <label for="goalEventRepeatWeekdays3"> Thu </label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
            <input id="goalEventRepeatWeekdays4" class="goalEventRepeatWeekdays" value="Friday" type="checkbox" @if(isset($habitDetails) && (in_array('Friday',$habitDetails->gbHabitWeekDetails))) checked @endif>
            <label for="goalEventRepeatWeekdays4"> Fri </label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
            <input id="goalEventRepeatWeekdays5" class="goalEventRepeatWeekdays"  value="Saturday" type="checkbox" @if(isset($habitDetails) && (in_array('Saturday',$habitDetails->gbHabitWeekDetails))) checked @endif>
            <label for="goalEventRepeatWeekdays5"> Sat </label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
            <input id="goalEventRepeatWeekdays6" class="goalEventRepeatWeekdays" value="Sunday" type="checkbox" @if(isset($habitDetails) && (in_array('Sunday',$habitDetails->gbHabitWeekDetails))) checked @endif>
            <label for="goalEventRepeatWeekdays6"> Sun </label>
          </div>
        </div>

        <div class="month-count" @if((isset($habitDetails))&&($habitDetails->gb_habit_recurrence_type=='monthly')) style="display: block;" @else style="display: none;"   @endif> 
          @if(Request::segment(2) == 'edithabit')
          <div class ="month-count-div">Day 
            <select class="month-date">
            @for($i = 1; $i <= calDaysInMonth(); $i++) 
              @if($i==$habitDetails->gb_habit_recurrence_month)
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
    <div class="form-group">
      <label class="strong" for="SYG_notes">Why is this habit important to me? </label>
      <div>
        <?php 
          $snotes = null; 
          if(isset($goalDetails) && isset($habitDetails))
            $snotes = $habitDetails->gb_habit_notes;
        ?>
        {!! Form::textarea('SYG_notes', $snotes, ['class'=>'form-control', 'id'=>'SYG_notes']) !!}
      </div>
    </div>
    <div class ="milestone-div" @if((isset($milestonesData))&&(count($milestonesData) < 1)) style="display: none;" @endif>
      <div class="form-group btn_dd">
        <label for="milestone_div" class="strong milestone-div-label">Is this habit associated with a milestone of this goal? *</label>
        <div class="milestone-dropdown"> 
          @if((isset($milestonesData))&&(count($milestonesData) > 0))
          <select id="milestone_div" name="milestone_value" class="form-control onchange-set-neutral milestone_div_class" required="" multiple="">
            <option value="">-- Select --</option>
            @foreach($milestonesData as $milestones)
              @if(isset($habitDetails) && $milestones->id == $habitDetails->gb_milestones_id(in_array($milestones->id,$habitDetails->gbHabitWeekDetails)))
                <option value="{{$milestones->id}}" selected="">{{$milestones->gb_milestones_name}}</option>
              @else
                <option value="{{$milestones->id}}">{{$milestones->gb_milestones_name}}</option>
              @endif
            @endforeach
          </select>
          @endif
        </div>
      </div>
    </div>
  </fieldset>
</div>

<div class="col-md-6">
  <fieldset class="padding-15 step2" >
    <legend>Sharing</legend>
    <div class="form-group">
      <label class="strong">Who can see this habit? </label>
      <div>
        <div class="radio clip-radio radio-primary radio-inline m-b-0">
          <input type="radio" name="syg2_see_habit" id="syg2_see_habit0" value="everyone"  {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'everyone'?'checked':'' }} @if(!isset($habitDetails)) checked @endif>
          <label for="syg2_see_habit0"> Everyone </label>
        </div>
        <div class="radio clip-radio radio-primary radio-inline m-b-0">
          <input type="radio" name="syg2_see_habit" id="syg2_see_habit1" value="habit-friends" {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'habit-friends'?'checked':'' }}>
          <label for="syg2_see_habit1"> My Friends </label>
        </div>
        <div class="radio clip-radio radio-primary radio-inline m-b-0">
          <input type="radio" name="syg2_see_habit" id="syg2_see_habit2" value="Just Me" {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'Just Me'?'checked':'' }}>
          <label for="syg2_see_habit2"> Just Me </label>
        </div>
        <div class ="habit-friends-section">
          <table>
            <tr>
              <td>
                <button type="button" class="btn btn-wide btn-o btn-success" style="margin-bottom: 5px;" data-toggle="modal" data-target="#friendModal">Choose</button>
                <br>
                <button type="button" class="btn btn-wide btn-o btn-success" data-toggle="modal" data-target="#friendModal">Friends</button>
              </td>
              <td>
                <span> 
                  <a href="javascript:choose_friends()" id="view_count" style="margin: 0px 0px 5px 5px;">0 Friends can view</a>
                  <br>
                  <a href="javascript:choose_friends()" id="edit_count" style="margin: 0px 0px 5px 5px;">0 Friends can edit</a> 
                </span>
              </td>
            </tr>
          </table>
        </div>
        <div id="friendModal" class="modal fade" role="dialog">
          <div class="modal-dialog"> 
            
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class ="row">
                  <div class ="col-md-6">
                    <h4> Who can see this habit?</h4>
                  </div>
                  <div class ="col-md-6">
                    <input type ="checkbox" name ="all-friends">
                    All my friends can view this habit </div>
                </div>
                <div class ="row">
                  <div class ="col-md-6">
                    <h4>Your friends:</h4>
                  </div>
                  <div class ="col-md-6">
                    <h4>Friends with access to this habit:</h4>
                  </div>
                  <button type="button" class="btn btn-info btn-lg" style ="margin: 10px 0px 13px 15px;" data-dismiss="modal" data-size="l">Ok</button>
                </div>
                <div class="modal-footer"> </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="form-group">
      <label class="strong">Send e-mail / SMS reminders </label>
      <div>
        <div class="radio clip-radio radio-primary radio-inline m-b-0">
          <input type="radio" name="syg2_send_msg" id="syg2_send_msg0" value="only_if_I_am_late" @if(!isset($habitDetails)) checked @endif {{ isset($habitDetails) && $habitDetails->gb_habit_reminder == 'only_if_I_am_late'?'checked':'' }}>
          <label for="syg2_send_msg0"> Only if I am late </label>
        </div>
        <div class="radio clip-radio radio-primary radio-inline m-b-0">
          <input type="radio" name="syg2_send_msg" id="syg2_send_msg1" value="Every_occurrence" {{ isset($habitDetails) && $habitDetails->gb_habit_reminder == 'Every_occurrence'?'checked':'' }}>
          <label for="syg2_send_msg1"> Every occurrence </label>
        </div>
        <div class="radio clip-radio radio-primary radio-inline m-b-0">
          <input type="radio" name="syg2_send_msg" id="syg2_send_msg2" value="none" {{ isset($habitDetails) && $habitDetails->gb_habit_reminder == 'none'?'checked':'' }}>
          <label for="syg2_send_msg2"> None </label>
        </div>
      </div>
    </div>
    </div>
  </fieldset>
</div>
