<?php
$taskHabitDetails = null;
$taskDetails  = null;
if(isset($taskData)) {
  if(isset($taskData[0])) {
    $taskDetails = $taskData[0];
  }
}

if(!empty($habitData) && $taskDetails) {
  foreach ($habitData as $habit) {
    if($habit->id == $taskDetails->gb_habit_id)
    $taskHabitDetails = $habit;
  }
}
?>
<div class="row vp-form-container container-gb-step-4">
  <div class="col-sm-12">
     <input type="hidden" class="form-control" id="task-index" name="task_index" value="na">
    <input type="hidden" class="form-control" id="no-of-task" name="no_of_task" value="">
    <input type="hidden" min class="form-control" id="task-id" value="{{$taskDetails?$taskDetails->id:null}}" name="task_id">
    <h6 class ="step-heading"><em>What Do I Need To Do to Accomplish My Goal?</em></h6>
    
    <ul id="viewport-4" class="vp-form-input-list">
      
      <!-- start: SYG3_task | 0 -->
      <li class="vp-item" data-index="0" data-sub-index="null" data-type="select" data-valid="@{{goalBuddy.habit_value.$valid}}">
        
        <div class ="task-habit-div" @if((isset($habitData))&& ($habitData->count() < 1)) style="display: none;" @endif>
          
          <div class="vp-input input-text-name">
            {{-- <h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3> --}}
            
            <div class="input-header">
              <h3 data-toggle="tooltip" title="Physical activity would have multiple task that fall under it including: Resistance training, Cardio, Stretch">
                <!-- label -->
                <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Is this task <b>associated</b> with a habit of this goal? <sup>*</sup></span>
                <!-- description -->
                {{--<span class="description"><br>Here will be the description of the form!</span>--}}
              </h3>
              
              <i class="fa fa-life-ring fa-5x title-icon" aria-hidden="true"></i>
            </div> <!-- end: INPUT HEADER -->
            
            <div class="input-body mb ml-0">
              <div class="row">
                <div class="col-sm-8">
                  <?php
                  $m_gb_habit_value = [];
                  if(isset($habitData)) {
                    foreach ($habitData as $habitval) {
                      if(isset($taskDetails) && $habitval->id==$taskDetails->gb_habit_id) {
                        $m_gb_habit_value[] = $habitval->id;
                      }
                    }
                  }
                  ?>
                  <input type="hidden" name="associatedHabitWithTask" value="">
                  <input type="hidden" name="goalTaskData" value="">
                  <div class="task-habit-dropdown pli-23 dropdown-menu-ml-0">
                    @if((isset($habitData))&&(count($habitData) > 0))
                    <select onchange="validateGoalTask()" data-toggle="tooltip" title="Is this task associated with a habit of this goal?" id="habit_div" name="habit_value" class="form-control onchange-set-neutral taskhabit_div_class" ng-init="habit_value={{ json_encode($m_gb_habit_value) }}" ng-model="habit_value" ng-keypress="pressEnter($event)" required>
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
                  </div> <!-- end task habit dropdown -->
                  
                </div>
                <div class="col-sm-6"></div>
              </div>
              
              {{-- <div ng-if="goalBuddy.SYG3_task.$dirty && goalBuddy.SYG3_task.$invalid" class="vp-tooltip">
                <span>This field is required!</span>
              </div> --}}

             {{--  <div ng-if="goalBuddy.habit_value.$dirty || goalBuddy.habit_value.$invalid" class="vp-tooltip">
                <span>This field is required!</span>
              </div> --}}
              
              <div ng-show="true" class="enter-btn mti-15 active">
                <button onclick="validateGoalTask()" type="button" class="btn btn-primary mli-23" ng-click="jumpToNextInput()">
                  OK <i class="fa fa-check" aria-hidden="true"></i>
                </button>
                <span class="press-enter">click <b>OK</b></span>
              </div>
            </div> <!-- end: INPUT BODY -->
          </div> <!-- end: INPUT TEXT NAME -->
          <div class="clear-both"></div>
          
        </div> <!-- end: task habit div -->
        
      </li>
      <!-- end: SYG3_task | 0 -->
      
      
      <!-- start: habit_value | 1 -->
      <li class="vp-item vp-form-active" data-index="1" data-sub-index="null" data-type="text" data-valid="@{{goalBuddy.SYG3_task.$valid}}">
        <div class="vp-input input-text-name">
          {{-- <h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3> --}}
          
          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span><b>Name</b> Your task</span> <sup>*</sup>
              <!-- description -->
              {{--<span class="description"><br>Here will be the description of the form!</span>--}}
            </h3>
            
            <i class="fa fa-dot-circle-o fa-5x title-icon" aria-hidden="true"></i>
          </div> <!-- end: INPUT HEADER -->
          
          <div class="input-body mb">
            <div class="row">
              <div class="col-sm-8">
                <input data-toggle="tooltip" title="Resistance tarining, Daily walk, Morning Sit up routine." type="text" class="form-control" id="SYG3_task" ng-model="SYG3_task"  ng-init="SYG3_task='{{ isset($taskDetails) ? $taskDetails->gb_task_name : null}}'" ng-keypress="pressEnter($event)" value="{{ isset($taskDetails) ? $taskDetails->gb_task_name : null}}" name="SYG3_task" required>
              </div>
            </div>
            
            {{-- <div ng-if="goalBuddy.SYG3_task.$dirty && goalBuddy.SYG3_task.$invalid" class="vp-tooltip">
              <span>This field is required!</span>
            </div> --}}
            
            <div ng-show="goalBuddy.SYG3_task.$valid" class="enter-btn mti-15 active">
              <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">press <b>ENTER</b></span>
            </div>
          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->
        <div class="clear-both"></div>
      </li>
      <!-- end: habit_value | 1 -->
      
      
      <!-- start: note | 2 -->
      <li class="vp-item" data-index="2" data-sub-index="null" data-type="textarea" data-valid="@{{goalBuddy.note.$valid}}">
        <div class="vp-input input-textarea">
          {{-- <h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3> --}}
          
          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Note</span>
            </h3>
            
            <i class="fa fa-sticky-note-o fa-5x title-icon" aria-hidden="true"></i>
          </div> <!-- end: INPUT HEADER -->
          
          <div class="input-body mb">
            <div class="row">
              <div class="col-sm-8">
                <textarea onblur="validateGoalTask()" data-toggle="tooltip" title="Frequency Per Week.<br> Intensity of training.<br> Duration of activity.<br> Requirements of hypertrophy or limiting muscle mass." data-autoresize rows="3" id="note" name="note" ng-model="note" ng-init="note='{{ isset($taskDetails) ? $taskDetails->gb_task_note : null}}'" placeholder="" class="form-control">{{ isset($taskDetails) ? $taskDetails->gb_task_note : null}}</textarea>
              </div>
            </div>
            
            {{--<p class="pli-18 m-b-0 press-enter">Press Shift+Enter for line break</p>--}}
            
            {{-- <div ng-if="goalBuddy.note.$touched && goalBuddy.note.$invalid" class="vp-tooltip"> --}}
            <div ng-if="goalBuddy.note.$dirty && goalBuddy.note.$invalid" class="vp-tooltip">
              <span>Please, insert the correct value! <br></span>
            </div>
            
            <div ng-show="goalBuddy.note.$valid" class="enter-btn active">
              <button onclick="validateGoalTask()" type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">click <b>OK</b></span>
            </div>
          </div> <!-- end: INPUT BODY -->
        </div> <!-- end: INPUT TEXT NAME -->
        
        <div class="clear-both"></div>
      </li>
      <!-- end: note | 2 -->
      
      
      
      
      
      <!-- start: SYG3_priority | 3 -->
      <li class="vp-item" data-index="3" data-sub-index="3" data-type="radio" data-valid="@{{goalBuddy.SYG3_priority.$valid}}">
        <div class="vp-input input-yes-no-btn">
          {{-- <h3 class="vp-index pull-left">4. &nbsp;&nbsp;</h3> --}}
          
          <div class="input-header">
            <h3 data-toggle="tooltip" title="1.<bstyle='color:#f64c1e;'>Low</b> - Not very important or already a habit or behaviour</br>2. <b style='color:#f64c1e;'>Normal</b> - Important but not critical <br>3. <b style='color:#f64c1e;'>High</b> - Important and required to achieve goal<br>4. <b style='color:#f64c1e;'>Urgent</b> - Critical part of the goal and has priority over other tasks.">
              <i class="fa fa-arrow-right" aria-hidden="true"></i>
              <span>Priority <sup>*</sup></span>
            </h3>
          </div> <!-- end: INPUT HEADER -->
          
          
          <div class="input-body mb">
            <script>
              $(document).ready(function() {
                var m_gb_task_priority = "{{ isset($taskDetails) ? $taskDetails->gb_task_priority : 'null' }}";
                if(m_gb_task_priority !== 'null') {
                  window.gbs4Data.radio[3].value = m_gb_task_priority;
                  
                  if(m_gb_task_priority === 'Low') {
                    window.gbs4Data.radio[3].activeOption = 0;
                  } else if(m_gb_task_priority === 'Normal') {
                    window.gbs4Data.radio[3].activeOption = 1;
                  } else if(m_gb_task_priority === 'High') {
                    window.gbs4Data.radio[3].activeOption = 2;
                  } else if(m_gb_task_priority === 'Urgent') {
                    window.gbs4Data.radio[3].activeOption = 3;
                  }
                  
                  window.digestGbs4();
                }
              });
            </script>
            <ul id="gb_task_priority_wrapper" class="click-box clear-both dib">
              <li onclick="validateGoalTask(this)" class="task-prioities @{{ (data.radio[3].activeOption == $index) ? 'active' : '' }}" ng-repeat="option in data.radio[3].options" ng-click="setRadioValue(3, $index)" data-value="@{{ option.value }}">
                <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                <div class="box-content">
                  <p ng-if="option.icon"><i class="@{{ option.icon }}" aria-hidden="true"></i></p>
                  <p ng-if="option.iconUrl"><img src="@{{ option.iconUrl }}" alt="Image"></p>
                  
                  <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value">
                    <span contenteditable
                    strip-br="true"
                    required ng-model="option.customValue">@{{ option.value }}</span><br>
                    <span class="btn btn-success btn-xs" onclick="validateGoalTask()" ng-click="updateRadioOptionValue($event, 3, $index)">Ok</span>
                  </p>
                  <p ng-if="option.isDataReceiving=='no'" class="vp-wrap-custom-value">@{{ option.value }}</p>
                </div> <!-- end: BOX CONTENT -->
                
                <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                <span ng-if="option.key" class="yes-active">key <b>@{{ option.key }}</b></span>
                @{{ option.label }}
              </li>
            </ul> <!-- end: CLICK BOX -->
            
            <input type="hidden" ng-keypress="pressEnter($event)" id="SYG3_priority" name="SYG3_priority" ng-value="data.radio[3].value" ng-model="SYG3_priority" placeholder="" class="form-control mb">
            
            {{--<div ng-if="goalBuddy.SYG3_priority.$touched && goalBuddy.SYG3_priority.$invalid" class="vp-tooltip">--}}
              {{--<span>Thie field is required!</span>--}}
              {{--</div>--}}
              
            </div> <!-- end: INPUT BODY -->
            
          </div> <!-- end: INPUT MALE FEMALE -->
          <div class="clear-both"></div>
        </li>
        <!-- end: SYG3_priority | 3 -->
        
        
        
        
        <!-- start: SYG_task_recurrence | 4 -->
        <li class="vp-item" data-index="4" data-sub-index="0" data-type="radio" data-jump-next="no" data-valid="@{{goalBuddy.SYG_task_recurrence.$valid}}">
          <div class="vp-input input-yes-no-btn">
            {{-- <h3 class="vp-index pull-left">5. &nbsp;&nbsp;</h3> --}}
            
            <div class="input-header">
              <h3 data-toggle="tooltip" title="1. <b style='color:#f64c1e;'>Daily</b> - This is if you are implementing the training in to your daily routine with no recovery days<br> 2. <b style='color:#f64c1e;'>Weekly</b> - If you have one or more recovery days in a week.<br> 3. <b style='color:#f64c1e;'>Monthly</b> - If it realated to a specific day each month may be testing">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                <span>Task <b>Recurrence</b></span>
              </h3>
            </div> <!-- end: INPUT HEADER -->
            
            
            <div class="input-body mb">
              
              <ul id="gb_task_recurrence_type_wrapper" class="click-box clear-both dib">
                <script>
                  $(document).ready(function() {
                    m_gb_task_recurrence_type = "{{ isset($taskDetails) ? $taskDetails->gb_task_recurrence_type : 'null' }}";
                    
                    if(m_gb_task_recurrence_type !== 'null') {
                      window.gbs4Data.radio[0].value = m_gb_task_recurrence_type;
                      
                      if(m_gb_task_recurrence_type === 'daily') {
                        window.gbs4Data.radio[0].activeOption = 0;
                      } else if(m_gb_task_recurrence_type === 'weekly') {
                        window.gbs4Data.radio[0].activeOption = 1;
                      } else if(m_gb_task_recurrence_type === 'monthly') {
                        window.gbs4Data.radio[0].activeOption = 2;
                      }
                      
                      window.digestGbs4();
                    }
                  });
                </script>
                <li ng-repeat="option in data.radio[0].options" ng-click="setRadioValue(0, $index)" data-value="@{{ option.value }}" class="@{{ (data.radio[0].activeOption == $index) ? 'active' : '' }}">
                  <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                  <div class="box-content">
                    <p ng-if="option.icon"><i class="@{{ option.icon }}" aria-hidden="true"></i></p>
                    <p ng-if="option.iconUrl"><img src="@{{ option.iconUrl }}" alt="Image"></p>
                    
                    <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value">
                      <span contenteditable
                      strip-br="true"
                      required ng-model="option.customValue">@{{ option.value }}</span><br>
                      <span class="btn btn-success btn-xs" ng-click="updateRadioOptionValue($event, 0, $index)">Ok</span>
                    </p>
                    <p ng-if="option.isDataReceiving=='no'" class="vp-wrap-custom-value">@{{ option.value }}</p>
                  </div> <!-- end: BOX CONTENT -->
                  
                  <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                  <span ng-if="option.key" class="yes-active">key <b>@{{ option.key }}</b></span>
                  @{{ option.label }}
                </li>
              </ul> <!-- end: CLICK BOX -->
              
              {{--            <div class="show-weeks-task"  @if((isset($taskDetails))&&($taskDetails->gb_task_recurrence_type=='weekly')) style="display: block;" @else style="display: none;"   @endif>--}}
                
                <div class="show-weeks-task pli-23" ng-if="data.radio[0].value == 'weekly'" >
                  
                  <div id="gb_task_recurrence_weeks" class="row">
                    
                    
                    <div onclick="validateGoalTask()" data-value="Monday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Monday', explode(',', $taskDetails->gb_task_recurrence_week)))) active {{-- @elseif(! isset($taskDetails)) active --}} @endif">
                      <div class="lable">
                        Mon <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="taskEventRepeatWeekdays0" class="taskEventRepeatWeekdays hidden" value="Monday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Monday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalTask()" data-value="Tuesday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Tuesday', explode(',', $taskDetails->gb_task_recurrence_week)))) active {{-- @elseif(! isset($taskDetails)) active --}} @endif">
                      <div class="lable">
                        Tue <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="taskEventRepeatWeekdays1" class="taskEventRepeatWeekdays hidden" value="Tuesday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Tuesday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalTask()" data-value="Wednesday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Wednesday', explode(',', $taskDetails->gb_task_recurrence_week)))) active {{-- @elseif(! isset($taskDetails)) active --}} @endif">
                      <div class="lable">
                        Wed <i class="fa fa-check"></i>
                      </div>
                      
                      <input onclick="validateGoalTask()" id="taskEventRepeatWeekdays2" class="taskEventRepeatWeekdays hidden" value="Wednesday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Wednesday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalTask()" data-value="Thursday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Thursday', explode(',', $taskDetails->gb_task_recurrence_week)))) active {{-- @elseif(! isset($taskDetails)) active --}} @endif">
                      <div class="lable">
                        Thu <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="taskEventRepeatWeekdays3" class="taskEventRepeatWeekdays hidden" value="Thursday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Thursday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalTask()" data-value="Friday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Friday', explode(',', $taskDetails->gb_task_recurrence_week)))) active {{-- @elseif(! isset($taskDetails)) active --}} @endif">
                      <div class="lable">
                        Fri <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="taskEventRepeatWeekdays4" class="taskEventRepeatWeekdays hidden" value="Friday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Friday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalTask()" data-value="Saturday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Saturday', explode(',', $taskDetails->gb_task_recurrence_week)))) active {{-- @elseif(! isset($taskDetails)) active --}} @endif">
                      <div class="lable">
                        Sat <i class="fa fa-check"></i>
                      </div>
                      
                      <input onclick="validateGoalTask()" id="taskEventRepeatWeekdays5" class="taskEventRepeatWeekdays hidden" value="Saturday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Saturday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalTask()" data-value="Sunday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Sunday', explode(',', $taskDetails->gb_task_recurrence_week)))) active {{-- @elseif(! isset($taskDetails)) active --}} @endif">
                      <div class="lable">
                        Sun <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="taskEventRepeatWeekdays6" class="taskEventRepeatWeekdays hidden" value="Sunday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Sunday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >
                    </div>
                    
                    
                  </div> <!-- end row -->
                  
                  <script>
                    $('#viewport-4').find('.prefTrainSlot').click(function(event) {
                      var $this = $(this),
                      input = $(this).find('input');
                      var attr = $(this).find('input').attr("checked");
                      var dayValue = $(this).find('input').val();

                      if($(this).hasClass('taskEventRepeatWeekdayNotInHabit') && ($(this).hasClass("inactive") || attr == undefined ))  {
                        swal({
                            title: "Do you want to add it?",
                            text: dayValue+ " is not included in habit associated.",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Yes, add!",
                            closeOnConfirm: true,
                            cancelButtonText:'Don\'t Add',
                            allowOutsideClick: false
                          },
                          function(isConfirm){
                            if (isConfirm) {
                              input.attr("checked", "checked");
                              $this.addClass('active');
                              $this.removeClass('inactive');
                              setTaskHabitData(dayValue);
                            } else {
                              input.removeAttr("checked");
                              $this.addClass('inactive');
                              $this.removeClass('active');
                            }
                        });
                      } else {
                        if (attr === undefined) {
                          input.attr("checked", "checked");
                          $this.addClass('active');
                          $this.removeClass('inactive');
                        } else {
                          input.removeAttr("checked");
                          $this.addClass('inactive');
                          $this.removeClass('active');
                        }
                      }
                    });
                  </script>
                  
                </div>
                
                {{--<div class="month-count-task" ng-show="data.radio[0].value == 'monthly'" @if((isset($taskDetails))&&($taskDetails->gb_task_recurrence_type=='monthly')) style="display: block;" @else style="display: none;"   @endif>--}}
                  
                  <div class="month-count-task  pli-23" ng-if="data.radio[0].value == 'monthly'">
                    @if(Request::segment(2) == 'edittask')
                    <div class ="month-count-task-div">Day
                      <select id="gb_task_recurrence_month" class="month-date-task">
                        @for($i = 1; $i <= calDaysInMonth(); $i++)
                        @if($taskDetails && $i==$taskDetails->gb_task_recurrence_month)
                        <option value ="{{ $i }}" selected="">{{ $i }}</option>
                        @else
                        <option value ="{{ $i }}">{{ $i }}</option>
                        @endif
                        @endfor
                      </select>
                    </div>
                    @else
                    <div class ="month-count-task-div">Day
                      <select id="gb_task_recurrence_month" class="month-date-task">
                        @for($i = 1; $i <= calDaysInMonth(); $i++)
                        <option value ="{{ $i }}">{{ $i }}</option>
                        @endfor
                      </select> of every month
                    </div>
                    @endif
                  </div>
                  
                  <input type="hidden" ng-keypress="pressEnter($event)" id="SYG_task_recurrence0" name="SYG_task_recurrence" ng-value="data.radio[0].value" ng-model="SYG_task_recurrence" placeholder="" class="form-control mb">
                  
                  {{-- <div ng-if="goalBuddy.SYG_task_recurrence.$touched && goalBuddy.SYG_task_recurrence.$invalid" class="vp-tooltip"> --}}
                  <div ng-if="goalBuddy.SYG_task_recurrence.$invalid && goalBuddy.SYG_task_recurrence.$dirty" class="vp-tooltip">
                    <span>Please, insert the correct value!</span>
                  </div>
                  
                  <div ng-show="goalBuddy.SYG_task_recurrence.$valid && ( data.radio[0].value == 'daily' || data.radio[0].value == 'weekly' || data.radio[0].value == 'monthly' )" class="enter-btn active">
                    <button onclick="validateGoalTask()" type="button" class="btn btn-primary mli-23" ng-click="jumpToNextInput(true)">
                      OK <i class="fa fa-check" aria-hidden="true"></i>
                    </button>
                    <span class="press-enter">click <b>OK</b></span>
                  </div>
                  
                </div> <!-- end: INPUT BODY -->
                
              </div> <!-- end: INPUT MALE FEMALE -->
              <div class="clear-both"></div>
            </li>
            <!-- end: SYG_task_recurrence | 4 -->
            
            
            
            
            <!-- start: SYG3_see_task | 5 -->
            <li class="vp-item" data-index="5" data-sub-index="1" data-type="radio" data-valid="@{{goalBuddy.SYG3_see_task.$valid}}">
              <div class="vp-input input-yes-no-btn">
                {{-- <h3 class="vp-index pull-left">6. &nbsp;&nbsp;</h3> --}}
                
                <div class="input-header">
                  <h3 data-toggle="tooltip" title="1. <b style='color:#f64c1e;'>Everyone</b> - Share details and tasks with friends and family<br>2. <b style='color:#f64c1e;'>Just Me</b> - Only show me details and tasks">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    <span>Who can <b>see</b> this task?</span>
                  </h3>
                </div> <!-- end: INPUT HEADER -->
                
                
                <div class="input-body mb">
                  <script>
                    $(document).ready(function() {
                      var m_gb_task_seen = "{{ $taskDetails ? $taskDetails->gb_task_seen : 'null' }}";
                      console.log()
                      if(m_gb_task_seen !== 'null') {
                        window.gbs4Data.radio[1].value = m_gb_task_seen;
                        
                        if(m_gb_task_seen === 'everyone') {
                          window.gbs4Data.radio[1].activeOption = 0;
                        } else if(m_gb_task_seen === 'Just Me') {
                          window.gbs4Data.radio[1].activeOption = 1;
                        }
                        
                        window.digestGbs4();
                      }
                    });
                  </script>
                  <ul id="gb_task_seen_wrapper" class="click-box clear-both dib">
                    <li onclick="validateGoalTask(this)" class="who-can-view @{{ (data.radio[1].activeOption == $index) ? 'active' : '' }}" ng-repeat="option in data.radio[1].options" ng-click="setRadioValue(1, $index)" data-value="@{{ option.value }}">
                      <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                      <div class="box-content">
                        <p ng-if="option.icon"><i class="@{{ option.icon }}" aria-hidden="true"></i></p>
                        <p ng-if="option.iconUrl"><img src="@{{ option.iconUrl }}" alt="Image"></p>
                        
                        <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value">
                          <span contenteditable
                          strip-br="true"
                          required ng-model="option.customValue">@{{ option.value }}</span><br>
                          <span class="btn btn-success btn-xs" onclick="validateGoalTask()" ng-click="updateRadioOptionValue($event, 1, $index)">Ok</span>
                        </p>
                        <p ng-if="option.isDataReceiving=='no'" class="vp-wrap-custom-value">@{{ option.value }}</p>
                      </div> <!-- end: BOX CONTENT -->
                      
                      <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                      <span ng-if="option.key" class="yes-active">key <b>@{{ option.key }}</b></span>
                      @{{ option.label }}
                    </li>
                  </ul> <!-- end: CLICK BOX -->
                  
                  <input type="hidden" ng-keypress="pressEnter($event)" id="SYG3_see_task0" name="SYG3_see_task" ng-value="data.radio[1].value" ng-model="SYG3_see_task" placeholder="" class="form-control mb">
                  
                  {{-- <div ng-if="goalBuddy.SYG3_see_task.$touched && goalBuddy.SYG3_see_task.$invalid" class="vp-tooltip"> --}}
                  <div ng-if="goalBuddy.SYG3_see_task.$invalid && goalBuddy.SYG3_see_task.$dirty" class="vp-tooltip">
                    <span>Please, insert the correct value!</span>
                  </div>
                  
                </div> <!-- end: INPUT BODY -->
                
              </div> <!-- end: INPUT MALE FEMALE -->
              <div class="clear-both"></div>
            </li>
            <!-- end: SYG3_see_task | 5 -->
            
            
            
            <!-- start: SYG3_send_msg | 6 -->
            <li class="vp-item" data-index="6" data-sub-index="2" data-type="radio" data-valid="@{{goalBuddy.SYG3_send_msg.$valid}}">
              <div class="vp-input input-yes-no-btn">
                {{-- <h3 class="vp-index pull-left">7. &nbsp;&nbsp;</h3> --}}
                
                <div class="input-header">
                  <h3 data-toggle="tooltip" title="1. <b style='color:#f64c1e;'>Overdue</b> <br> 2. <b style='color:#f64c1e;'>Due</b> <br> 3. <b style='color:#f64c1e;'>None</b>">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    <span>Send <b>e-mail</b> / <b>SMS</b> reminders, when task is</span>
                  </h3>
                </div> <!-- end: INPUT HEADER -->
                
                
                <div class="input-body mb">
                  <ul id="gb_task_reminder_wrapper" class="click-box clear-both dib">
                    <li class="send-reminders task-reminders">
                        <input type="radio" id="creattask_send_Overdue" name="creattask-send-mail" class="form-control mb" value="when_overdue" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "when_overdue")) checked @endif>
                        <label for="creattask_send_Overdue">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">A</span>
                            <span class="yes-active">key <b>A</b></span>
                            <span>When Overdue</span>
                        </label>
                    </li>
                    <li class="send-reminders task-reminders">
                        <input type="radio" id="creattask_send_Daily" name="creattask-send-mail" class="form-control mb" value="daily" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "daily")) checked @endif>
                        <label for="creattask_send_Daily">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">B</span>
                            <span class="yes-active">key <b>B</b></span>
                            <span>Daily</span>
                            <div class="showTimeBox">
                                <select id="daily_time_task">                                  
                                    <option value="1"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 1)) selected @endif>1:00 am</option>
                                    <option  value="2" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 2)) selected @endif>2:00 am</option>
                                    <option value="3"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 3)) selected @endif>3:00 am</option>
                                    <option value="4"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 4)) selected @endif>4:00 am</option>
                                    <option value="5"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 5)) selected @endif>5:00 am</option>
                                    <option value="6"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 6)) selected @endif>6:00 am</option>
                                    <option value="7"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 7)) selected @endif>7:00 am</option>
                                    <option value="9"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 9)) selected @endif>9:00 am</option>
                                    <option value="10" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 10)) selected @endif>10:00 am</option>
                                    <option value="11" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 11)) selected @endif>11:00 am</option>
                                    <option value="12" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 12)) selected @endif>12:00 PM</option>
                                    <option value="13" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 13)) selected @endif>1:00 PM</option>
                                    <option value="14" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 14)) selected @endif>2:00 PM</option>
                                    <option value="15" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 15)) selected @endif>3:00 PM</option>
                                    <option value="16" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 16)) selected @endif>4:00 PM</option>
                                    <option value="17" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 17)) selected @endif>5:00 PM</option>
                                    <option value="18" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 18)) selected @endif>6:00 PM</option>
                                    <option value="19" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 19)) selected @endif>7:00 PM</option>
                                    <option value="20" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 20)) selected @endif>8:00 PM</option>
                                    <option value="21" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 21)) selected @endif>9:00 PM</option>
                                    <option value="22" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 22)) selected @endif>10:00 PM</option>
                                    <option value="23" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 23)) selected @endif>11:00 PM</option>
                                    <option value="24" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 24)) selected @endif>12:00 am</option>
                                </select>
                            </div>
                        </label>
                    </li>
                    <li class="send-reminders task-reminders">
                        <input type="radio" id="creattask_send_None" name="creattask-send-mail" class="form-control mb" value="none" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "none")) checked @endif>
                        <label for="creattask_send_None">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">E</span>
                            <span class="yes-active">key <b>E</b></span>
                            <span>None</span>
                        </label>
                    </li>
                  </ul> <!-- end: CLICK BOX -->
                  
                  <div ng-if="goalBuddy.SYG3_send_msg.$dirty && goalBuddy.SYG3_send_msg.$invalid" class="vp-tooltip">
                    <span>Please, insert the correct value!</span>
                  </div>
                  
                </div> <!-- end: INPUT BODY -->
                
              </div> <!-- end: INPUT MALE FEMALE -->
              <div class="clear-both"></div>
            </li>
            <!-- end: SYG3_send_msg | 6 -->
            
          </ul> <!-- end vp-form-input-list -->
          
        </div> <!-- end col12 -->
        
        <!-- end: progressbar -->
        <div class="row">
          <div class="vp-progress-bar">
            <div class="col-sm-10 col-sm-offset-2 vp-progress">
              <div class="vp-progress-content">
                <p>@{{ percentCompleted }}% complete</p>
                <progress value="@{{ percentCompleted }}" max="100"> </progress>
              </div> <!--  -->
              <div class="create-type-form">
                <a class="create-account" target="_blank" href="javascript:void(0)">Powered by Epic Trainer</a>
                <a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true">Next</i></a>
                <a href="javascript:void(0)" ng-click="jumpToNextInput(true)"><i class="fa fa-chevron-down" aria-hidden="true">Back</i></a>
              </div> <!--  -->
            </div> <!-- end: COL8 || SUBMIT -->
          </div>
        </div>
        <!-- end: progressbar -->
        
      </div> <!-- end row -->
