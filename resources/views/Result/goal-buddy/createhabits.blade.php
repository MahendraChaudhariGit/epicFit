<?php
 //dd($habitData);
if(isset($habitData)) {
  if(isset($habitData[0])) {
    
    $habitDetails = $habitData[0];
   
  }
}
?>
<div class="row vp-form-container container-gb-step-3">
  <div class="col-sm-12">

<input type="hidden" class="form-control" id="habit-index" name="habit_index" value="0">
    <input type="hidden" class="form-control" id="no-of-habit" name="no_of_habit" value="">

    <input type="hidden" class="form-control" id="goal-habit-id" name="goalHabitId" value="">
    <input type="hidden" class="form-control" id="habit-id" name="habit_id" value="">
   
    {{--<h4>Milestone Details</h4>--}}
    
    <ul id="viewport-3" class="vp-form-input-list">

      <!-- start: SYG_habits | 0 -->
      <li class="vp-item vp-form-active" data-index="0" data-sub-index="null" data-type="text" data-valid="@{{goalBuddy.SYG_habits.$valid}}">
        <div class="vp-input input-text-name">
          {{-- <h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3> --}}
          
          <div class="input-header">
            <h3>
              <!-- label -->
              <i class="fa fa-arrow-right" aria-hidden="true"></i> <span></i> Name Your <b>Habit</b></span> <sup>*</sup>
              <!-- description -->
              {{--<span class="description"><br>Here will be the description of the form!</span>--}}
            </h3>
            
            <i class="fa fa-dot-circle-o fa-5x title-icon" aria-hidden="true"></i>
          </div> <!-- end: INPUT HEADER -->
          
          <div class="input-body mb">
            <div class="row">
              <div class="col-sm-8">
                <input onblur="validateGoalHabit()"  data-toggle="tooltip" title="<b>Physical Activity</b> - This is the first habit to be mentioned as it is for most,<br>the easiest habit to build on. Physical activity requires less mental focus <br> and discipline and at EPIC you have further help by being 100% guided <br> and motivated in training sessions. We use this as the starting point to <br> build routine with resistance training, later adding in cardio and recovery<br>routine as build your habit further." type="text" class="form-control" id="SYG_habits" ng-model="SYG_habits"  ng-init="SYG_habits='{{ isset($habitDetails) ? $habitDetails->gb_habit_name : null}}'" ng-keypress="pressEnter($event)" value="{{isset($habitDetails)?$habitDetails->gb_habit_name:null}}" name="SYG_habits" required>
              </div>
            </div>
            
            {{-- <div ng-if="goalBuddy.SYG_habits.$touched && goalBuddy.SYG_habits.$invalid && goalBuddy.SYG_habits.$dirty" class="vp-tooltip"> --}}
             {{--  <div ng-if="goalBuddy.SYG_habits.$dirty && goalBuddy.SYG_habits.$invalid" class="vp-tooltip">
                <span>This field is required!</span>
              </div> --}}
              
              <div ng-show="goalBuddy.SYG_habits.$valid" class="enter-btn active">
                <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()" onclick="validateGoalHabit()">
                  OK <i class="fa fa-check" aria-hidden="true"></i>
                </button>
                <span class="press-enter">press <b>ENTER</b></span>
              </div>
            </div> <!-- end: INPUT BODY -->
          </div> <!-- end: INPUT TEXT NAME -->
          <div class="clear-both"></div>
        </li>
        <!-- end: SYG_habits | 0 -->
        
        
        <!-- start: SYG_habit_recurrence | 1 -->
        <li class="vp-item SYG_habit_recurrence_wrapper" data-index="1" data-sub-index="0" data-type="radio" data-valid="@{{goalBuddy.SYG_habit_recurrence.$valid}}">
          <div class="vp-input input-yes-no-btn">
            {{-- <h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3> --}}
            
            <div class="input-header">
              <h3 data-toggle="tooltip" title="How often will you be focusing on this particular habit?<br>Focusing on one habit let a lone multiple habits in the <br>early stages of your journey can be daunting, stressful and <br>unrealistic based on your current lifestyle, schedule and <br>current level of your motivation. <b>Example:</b> If you work a 16 <br> hours shift in 4 days per week can you focus on physical activity on those same days? <br> 1. <b style='color:#f64c1e;'>Daily</b> - This is if you are implementing the training in to your daily routine with no recovery days.<br> 2. <b style='color:#f64c1e;'>Weekly</b> - If you have one or more recovery days in a week.<br> 3. <b style='color:#f64c1e;'>Monthly</b> - If it related to a specific day each month may be testing.">
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
                <span>Habit <b>Recurrence</b></span>
              </h3>
            </div> <!-- end: INPUT HEADER -->
            
            
            <div class="input-body mb">

              <ul class="click-box clear-both dib">
                <script>
                  $(document).ready(function() {
                    var m_gb_habit_recurrence_type = "{{ isset($habitDetails) ? $habitDetails->gb_habit_recurrence_type : 'null' }}";

                    if(m_gb_habit_recurrence_type !== 'null') {
                      window.gbs3Data.radio[0].value = m_gb_habit_recurrence_type;
                      
                      if(m_gb_habit_recurrence_type === 'daily') {
                        window.gbs3Data.radio[0].activeOption = 0;
                      } else if(m_gb_habit_recurrence_type === 'weekly') {
                        window.gbs3Data.radio[0].activeOption = 1;
                      } else if(m_gb_habit_recurrence_type === 'monthly') {
                        window.gbs3Data.radio[0].activeOption = 2;
                      }
                      
                      window.digestGbs3();
                    }

                    $('body').on('click', '.habitRecurrenceWrapper', function(e) {
                      if (typeof e.isTrigger === "undefined") {
                        var recurrenceType = $(this).data('recurrence-type');
                        
                        if(recurrenceType === 'weekly') {
                          var weeksDiv = $(this).parent().parent().find('.show-weeks');

                          if(weeksDiv.length != 0) {
                            weeksDiv.find('.prefTrainSlot').removeClass('active');
                            weeksDiv.find('.prefTrainSlot').addClass('inactive');
                            weeksDiv.find('.prefTrainSlot').find('.goalEventRepeatWeekdays').attr('checked', false);
                          }
                        } else if(recurrenceType == 'monthly') {
                          var monthDiv = $(this).parent().parent().find('.month-count');
                          monthDiv.find('.month-date').val('1');
                        }
                      }
                    });
                  });
                </script>
                <li ng-repeat="option in data.radio[0].options" ng-click="setRadioValue(0, $index)" data-recurrence-type="@{{ option.value }}" class="habitRecurrenceWrapper @{{ (data.radio[0].activeOption == $index) ? 'active' : '' }}">
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
              
              {{--<!--<div ng-show="" class="show-weeks"  @if((isset($habitDetails))&&($habitDetails->gb_habit_recurrence_type=='weekly')) style="display: block;" @else style="display: none;"   @endif>-->--}}

                <div ng-if="data.radio[0].value == 'weekly'" class="show-weeks  pli-23">

                  <div class="row">

                    <div onclick="validateGoalHabit()" data-day="Monday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($habitDetails) && (in_array('Monday',$habitDetails->gbHabitWeekDetails))) active {{--@elseif(! isset($habitDetails)) active --}} @endif">
                      <div class="lable">
                        Mon <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="goalEventRepeatWeekdays0" class="goalEventRepeatWeekdays hidden" value="Monday" type="checkbox"  @if(isset($habitDetails) && (in_array('Monday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalHabit()" data-day="Tuesday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($habitDetails) && (in_array('Tuesday',$habitDetails->gbHabitWeekDetails))) active {{--@elseif(! isset($habitDetails)) active --}} @endif">
                      <div class="lable">
                        Tue <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="goalEventRepeatWeekdays1" class="goalEventRepeatWeekdays hidden" value="Tuesday" type="checkbox"  @if(isset($habitDetails) && (in_array('Tuesday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalHabit()" data-day="Wednesday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($habitDetails) && (in_array('Wednesday',$habitDetails->gbHabitWeekDetails))) active {{--@elseif(! isset($habitDetails)) active --}} @endif">
                      <div class="lable">
                        Wed <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="goalEventRepeatWeekdays2" class="goalEventRepeatWeekdays hidden" value="Wednesday" type="checkbox"  @if(isset($habitDetails) && (in_array('Wednesday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalHabit()" data-day="Thursday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($habitDetails) && (in_array('Thursday',$habitDetails->gbHabitWeekDetails))) active {{--@elseif(! isset($habitDetails)) active --}} @endif">
                      <div class="lable">
                        Thu <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="goalEventRepeatWeekdays3" class="goalEventRepeatWeekdays hidden" value="Thursday" type="checkbox"  @if(isset($habitDetails) && (in_array('Thursday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalHabit()" data-day="Friday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($habitDetails) && (in_array('Friday',$habitDetails->gbHabitWeekDetails))) active {{--@elseif(! isset($habitDetails)) active --}} @endif">
                      <div class="lable">
                        Fri <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="goalEventRepeatWeekdays4" class="goalEventRepeatWeekdays hidden" value="Friday" type="checkbox"  @if(isset($habitDetails) && (in_array('Friday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalHabit()" data-day="Saturday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($habitDetails) && (in_array('Saturday',$habitDetails->gbHabitWeekDetails))) active {{--@elseif(! isset($habitDetails)) active --}} @endif">
                      <div class="lable">
                        Sat <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="goalEventRepeatWeekdays5" class="goalEventRepeatWeekdays hidden" value="Saturday" type="checkbox"  @if(isset($habitDetails) && (in_array('Saturday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                    </div>
                    
                    <div onclick="validateGoalHabit()" data-day="Sunday" class="col-sm-3 m-b-20 ind-checkbox prefTrainSlot @if(isset($habitDetails) && (in_array('Sunday',$habitDetails->gbHabitWeekDetails))) active {{--@elseif(! isset($habitDetails)) active --}} @endif">
                      <div class="lable">
                        Sun <i class="fa fa-check"></i>
                      </div>
                      
                      <input id="goalEventRepeatWeekdays6" class="goalEventRepeatWeekdays hidden" value="Sunday" type="checkbox"  @if(isset($habitDetails) && (in_array('Sunday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                    </div>
                    
                  </div> <!-- end row -->
                  
                  <script>
                     $('#viewport-3').find('.prefTrainSlot').click(function() {
                      var $this = $(this),
                      input = $(this).find('input');
                      
                      var attr = $(this).find('input').attr("checked");

                      if (attr === undefined) {
                        input.attr("checked", "checked");
                        $this.addClass('active');
                        $this.removeClass('inactive');
                      } else {
                        input.removeAttr("checked");
                        $this.addClass('inactive');
                        $this.removeClass('active');
                      }
                    });
                  </script>
                  
                </div>
                
                
                {{--<div class="month-count" ng-show="goalBuddy.SYG_habit_recurrence == 'monthly'" @if((isset($habitDetails))&&($habitDetails->gb_habit_recurrence_type=='monthly')) style="display: block;" @else style="display: none;"   @endif>--}}

                  <div class="month-count pli-23" ng-if="data.radio[0].value == 'monthly'">
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
                    @else
                    <div class ="month-count-div mxw-55">Day
                      <select onchange="validateGoalHabit()" class="month-date" style="max-width: 40px">
                        @for($i = 1; $i <= calDaysInMonth(); $i++)
                        <option value ="{{ $i }}">{{ $i }}</option>
                        @endfor
                      </select> of every month
                    </div>
                    @endif
                  </div>
                  
                  <input type="hidden" ng-keypress="pressEnter($event)" id="SYG_habit_recurrence0" name="SYG_habit_recurrence" ng-value="data.radio[0].value" ng-model="SYG_habit_recurrence" placeholder="" class="form-control mb">
                  
                  {{-- <div ng-if="goalBuddy.SYG_habit_recurrence.$touched && goalBuddy.SYG_habit_recurrence.$invalid" class="vp-tooltip"> --}}
                    <div ng-if="goalBuddy.SYG_habit_recurrence.$dirty && goalBuddy.SYG_habit_recurrence.$invalid" class="vp-tooltip">
                      <span>Please, insert the correct value!</span>
                    </div>
                    
                    <div ng-show="goalBuddy.SYG_habit_recurrence.$valid && ( data.radio[0].value == 'daily' || data.radio[0].value == 'weekly' || data.radio[0].value == 'monthly' )" class="enter-btn active">
                      <button type="button" class="btn btn-primary" ng-click="jumpToNextInput()">
                        OK <i class="fa fa-check" aria-hidden="true" onclick="validateGoalHabit()"></i>
                      </button>
                      <span class="press-enter">click <b>OK</b></span>
                    </div>
                    
                  </div> <!-- end: INPUT BODY -->
                  
                </div> <!-- end: INPUT MALE FEMALE -->
                <div class="clear-both"></div>
              </li>
              <!-- end: SYG_habit_recurrence | 1 -->
              
              
              
              <!-- start: SYG_notes | 2 -->
              <li class="vp-item" data-index="2" data-sub-index="null" data-type="textarea" data-valid="@{{goalBuddy.SYG_notes.$valid}}">
                <div class="vp-input input-textarea">
                  {{-- <h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3> --}}
                  
                  <div class="input-header">
                    <h3>
                      <!-- label -->
                      <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Why is this <b>habit important</b> to me?</span>
                    </h3>
                    
                    <i class="fa fa-key fa-5x title-icon" aria-hidden="true"></i>
                    
                  </div> <!-- end: INPUT HEADER -->
                  
                  <div class="input-body mb">
                    <div class="row">
                      <div class="col-sm-8">
                        <textarea  onblur="validateGoalHabit()" data-toggle="tooltip" title="By creating this habit what aspect of your life will chnage?<br><br>Resistance training to improve functional strength.<br> Cardiovascular endurance to ensure we can do more for longer.<br> Recovery routine incorporating stretching and rolling." data-autoresize rows="3" id="SYG_notes" name="SYG_notes" ng-model="SYG_notes" ng-init="SYG_notes='{{ isset($habitDetails) ? $habitDetails->gb_habit_notes : null}}'" placeholder="" class="form-control">{{isset($habitDetails)?$habitDetails->gb_habit_notes:null}}</textarea>
                      </div>
                    </div>
                    
                    {{--<p class="pli-18 m-b-0 press-enter">Press Shift+Enter for line break</p>--}}
                    
                    {{-- <div ng-if="goalBuddy.SYG_notes.$touched && goalBuddy.SYG_notes.$invalid" class="vp-tooltip"> --}}
                      <div ng-if="goalBuddy.SYG_notes.$dirty && goalBuddy.SYG_notes.$invalid" class="vp-tooltip">
                        <span>Please, insert the correct value! <br></span>
                      </div>
                      
                      <div ng-show="goalBuddy.SYG_notes.$valid" class="enter-btn active">
                        <button type="button" onclick="validateGoalHabit()" class="btn btn-primary" ng-click="jumpToNextInput()">
                          OK <i class="fa fa-check" aria-hidden="true"></i>
                        </button>
                        <span class="press-enter">click <b>OK</b></span>
                      </div>
                    </div> <!-- end: INPUT BODY -->
                  </div> <!-- end: INPUT TEXT NAME -->
                  
                  <div class="clear-both"></div>
                </li>
                <!-- end: SYG_notes | 2 -->
                
                
                
                
                
                <!-- start: milestone_value | 3 -->
                <li class="vp-item" data-index="3" data-sub-index="null" data-type="select" data-valid="@{{goalBuddy.milestone_value.$valid}}">

                  <div class ="milestone-div" @if((isset($milestonesData))&&(count($milestonesData) < 1)) style="display: none;" @endif>

                    <div class="vp-input input-text-name">
                      {{-- <h3 class="vp-index pull-left">4. &nbsp;&nbsp;</h3> --}}
                      
                      <div class="input-header">
                        <h3 data-toggle="tooltip" title="Does successfully creating this habit contribute <br>to hitting your milestones and achieving your overall goal? <br><br> Yes select individual or <br> Yes select all.">
                          <!-- label -->
                          <i class="fa fa-arrow-right" aria-hidden="true"></i> <span>Is this habit <b>associated</b> with a milestone of this goal?</span>
                          <!-- description -->
                          {{--<span class="description"><br>Here will be the description of the form!</span>--}}
                        </h3>
                        
                        <i class="fa fa-life-ring fa-5x title-icon" aria-hidden="true"></i>
                      </div> <!-- end: INPUT HEADER -->
                      
                      
                      
                      <div class="input-body mb ml-0">
                        <input type="checkbox" id="gb_habit_select_all_milestone" name="gb_habit_select_all_milestone"/><label for="gb_habit_select_all_milestone">Select All</label>
                        <div class="row">
                          <div class="col-sm-8">
                            <?php
                            $m_gb_milestone_value = [];
                            if(isset($milestonesData)) {
                              foreach ($milestonesData as $milestones) {
                                if(isset($habitDetails) && $habitDetails->gb_milestones_id && in_array($milestones->id, explode(',', $habitDetails->gb_milestones_id))) {
                                  $m_gb_milestone_value[] = $milestones->id;
                                }
                              }
                            }
                            ?>
                            <input type="hidden" name="previusSelectedMilesone" class="previusSelectedMilesone" value="{{ !empty($m_gb_milestone_value) ? json_encode($m_gb_milestone_value) : ''}}">

                            <div class="form-group pli-23">
                              <div class="milestone-dropdown">
                                <select data-toggle="tooltip" title="Is this habit associated with a milestone of this goal?" id="milestone_div" name="milestone_value" ng-init="milestone_value='{{ json_encode($m_gb_milestone_value) }}'" ng-model="milestone_value" ng-keypress="pressEnter($event)" class="selectpicker form-control onchange-set-neutral goal-change-life" data-live-search="true" data-selected-text-format="count > 2" multiple="multiple">
                                  @if((isset($milestoneOption))&&(count($milestoneOption) > 0))
                                  @foreach($milestoneOption as $key=>$milestones)
                                  @if(isset($habitDetails) && $habitDetails->gb_milestones_id && in_array($key, explode(',', $habitDetails->gb_milestones_id)))
                                  <option value="{{$key}}" selected="">{{$milestones}}</option>
                                  @else
                                  <option value="{{$key}}">{{$milestones}}</option>
                                  @endif
                                  @endforeach
                                  @endif
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6"></div>
                        </div>
                        
                        <script>
                          $(document).ready(function(){
                            $('#gb_habit_select_all_milestone').click(function() {
                              let src = $('div.milestone_div_class div.dropdown-menu ul.dropdown-menu li');
                              //console.log(src);
                              // let previusSelectedMilesone = JSON.parse($('input.previusSelectedMilesone').val());

                              if(!$(this).is(':checked')) {
                                $('div.milestone-dropdown div.bootstrap-select button').prop('title', '');

                                $('div.milestone-dropdown div.bootstrap-select button span.filter-option').text('');

                                $('select#milestone_div option').prop('selected', false);
                                src.removeClass('selected');
                                
                              } else {
                                let allMilestones = [];

                                src.each(function(){
                                  allMilestones.push($(this).find('a span.text').text());
                                });
                                $('div.milestone-dropdown div.bootstrap-select button').prop('title', allMilestones.toString());

                                $('div.milestone-dropdown div.bootstrap-select button span.filter-option').text(allMilestones.toString());

                                $('select#milestone_div option').prop('selected', true);
                                src.addClass('selected');
                              }
                            });

                            $('#milestone_div').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
                              console.log(clickedIndex,isSelected,previousValue);
                            });
                          });
                        </script>

                        {{-- <div ng-if="goalBuddy.milestone_value.$touched && goalBuddy.milestone_value.$invalid" class="vp-tooltip"> --}}
                          {{-- <div ng-if="goalBuddy.milestone_value.$dirty && goalBuddy.milestone_value.$invalid" class="vp-tooltip">
                            <span>This field is required!</span>
                          </div> --}}

                          <div class="clear-both"></div>
                          <div ng-show="goalBuddy.milestone_value.$valid" class="enter-btn mti--15 active">
                            <button type="button" onclick="validateGoalHabit()" class="btn btn-primary mli-23" ng-click="jumpToNextInput()">
                              OK <i class="fa fa-check" aria-hidden="true"></i>
                            </button>
                            <span class="press-enter">click <b>OK</b></span>
                          </div>
                        </div> <!-- end: INPUT BODY -->
                      </div> <!-- end: INPUT TEXT NAME -->
                      <div class="clear-both"></div>

                    </div> <!-- end: MILESTONE DIV -->

                    <p id="miles_id"></p>

                  </li>
                  <!-- end: milestone_value | 3 -->



                  <!-- start: syg2_see_habit | 4 -->
                  <li class="vp-item" data-index="4" data-sub-index="1" data-type="radio" data-valid="@{{goalBuddy.syg2_see_habit.$valid}}">
                    <div class="vp-input input-yes-no-btn">
                      {{-- <h3 class="vp-index pull-left">5. &nbsp;&nbsp;</h3> --}}

                      <div class="input-header">
                        <h3 data-toggle="tooltip" title="1. <b style='color:#f64c1e;'>Everyone</b> - Share details and habits with friends and family.<br>2. <b style='color:#f64c1e;'>Just Me</b> - Only show me details and habits.">
                          <i class="fa fa-arrow-right" aria-hidden="true"></i>
                          <span>Who can <b>see</b> this habit?</span>
                        </h3>
                      </div> <!-- end: INPUT HEADER -->


                      <div class="input-body mb">

                        <ul id="wrapper_syg2_see_habit0" class="click-box clear-both dib">
                          <script>
                            $(document).ready(function() {
                              var gb_habit_seen = "{{ isset($habitDetails) ? $habitDetails->gb_habit_seen : 'null' }}";
                              if(gb_habit_seen !== 'null') {
                                window.gbs3Data.radio[1].value = gb_habit_seen;

                                if(gb_habit_seen === 'everyone') {
                                  window.gbs3Data.radio[1].activeOption = 0;
                                } else if(gb_habit_seen === 'Just Me') {
                                  window.gbs3Data.radio[1].activeOption = 1;
                                }

                                window.digestGbs3();
                              }
                            });
                          </script>
                          <li onclick="validateGoalHabit()" class="who-can-view @{{ (data.radio[1].activeOption == $index) ? 'active' : '' }}" ng-repeat="option in data.radio[1].options" ng-click="setRadioValue(1, $index)" data-value="@{{ option.value }}">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                              <p ng-if="option.icon"><i class="@{{ option.icon }}" aria-hidden="true"></i></p>
                              <p ng-if="option.iconUrl"><img src="@{{ option.iconUrl }}" alt="Image"></p>

                              <p ng-if="option.isDataReceiving=='yes'" class="vp-wrap-custom-value">
                                <span contenteditable
                                strip-br="true"
                                required ng-model="option.customValue">@{{ option.value }}</span><br>
                                <span class="btn btn-success btn-xs" ng-click="updateRadioOptionValue($event, 1, $index)">Ok</span>
                              </p>
                              <p ng-if="option.isDataReceiving=='no'" class="vp-wrap-custom-value">@{{ option.value }}</p>
                            </div> <!-- end: BOX CONTENT -->

                            <span ng-if="option.key" class="yes">@{{ option.key }}</span>
                            <span ng-if="option.key" class="yes-active">key <b>@{{ option.key }}</b></span>
                            @{{ option.label }}
                          </li>
                        </ul> <!-- end: CLICK BOX -->

                        <input type="hidden" ng-keypress="pressEnter($event)" id="syg2_see_habit0" name="syg2_see_habit" ng-value="data.radio[1].value" ng-model="syg2_see_habit" placeholder="" class="form-control mb">

                        {{-- <div ng-if="goalBuddy.syg2_see_habit.$touched && goalBuddy.syg2_see_habit.$invalid" class="vp-tooltip"> --}}
                          <div ng-if="goalBuddy.syg2_see_habit.$dirty && goalBuddy.syg2_see_habit.$invalid" class="vp-tooltip">
                            <span>Please, insert the correct value!</span>
                          </div>

                        </div> <!-- end: INPUT BODY -->

                      </div> <!-- end: INPUT MALE FEMALE -->
                      <div class="clear-both"></div>
                    </li>
                    <!-- end: syg2_see_habit | 4 -->



                    <!-- start: syg2_send_msg | 5 -->
                    <li class="vp-item" data-index="5" data-sub-index="2" data-type="radio" data-valid="@{{goalBuddy.syg2_send_msg.$valid}}">
                      <div class="vp-input input-yes-no-btn">
                        {{-- <h3 class="vp-index pull-left">6. &nbsp;&nbsp;</h3> --}}

                        <div class="input-header">
                          <h3 data-toggle="tooltip" title="1. <b style='color:#f64c1e;'>If I'm late</b><br>2. <b style='color:#f64c1e;'>Every occurrence</b><br> 3. <b style='color:#f64c1e;'>None</b>">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                            <span>Send <b>e-mail</b> / <b>SMS</b> reminders</span>
                          </h3>
                        </div> <!-- end: INPUT HEADER -->


                        <div class="input-body mb">

                          <ul id="wrapper_syg2_send_msg0" class="click-box clear-both dib">
                            <li class="send-reminders habits-reminders">
                              <input type="radio" id="habits_send_Overdue" name="habits-send-mail" class="form-control mb" value="when_overdue" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "when_overdue")) checked @endif>
                              <label for="habits_send_Overdue">
                                  <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                                  <div class="box-content">
                                      <p><i class="fa fa-check" aria-hidden="true"></i></p>
                                  </div>
                                  <span class="yes">A</span>
                                  <span class="yes-active">key <b>A</b></span>
                                  <span>When Overdue</span>
                              </label>
                          </li>
                          <li class="send-reminders habits-reminders">
                              <input type="radio" id="habits_send_Daily" name="habits-send-mail" class="form-control mb" value="daily" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "daily")) checked @endif>
                              <label for="habits_send_Daily">
                                  <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                                  <div class="box-content">
                                      <p><i class="fa fa-check" aria-hidden="true"></i></p>
                                  </div>
                                  <span class="yes">B</span>
                                  <span class="yes-active">key <b>B</b></span>
                                  <span>Daily</span>
                                  <div class="showTimeBox">
                                      <select id="daily_time_habits">                                  
                                        <option value="1" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 1)) selected @endif>1:00 am</option>
                                        <option  value="2"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 2)) selected @endif>2:00 am</option>
                                        <option value="3"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 3)) selected @endif>3:00 am</option>
                                        <option value="4"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 4)) selected @endif>4:00 am</option>
                                        <option value="5"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 5)) selected @endif>5:00 am</option>
                                        <option value="6"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 6)) selected @endif>6:00 am</option>
                                        <option value="7"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 7)) selected @endif>7:00 am</option>
                                        <option value="9"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 9)) selected @endif>9:00 am</option>
                                        <option value="10" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 10)) selected @endif>10:00 am</option>
                                        <option value="11" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 11)) selected @endif>11:00 am</option>
                                        <option value="12" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 12)) selected @endif>12:00 PM</option>
                                        <option value="13" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 13)) selected @endif>1:00 PM</option>
                                        <option value="14" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 14)) selected @endif>2:00 PM</option>
                                        <option value="15" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 15)) selected @endif>3:00 PM</option>
                                        <option value="16" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 16)) selected @endif>4:00 PM</option>
                                        <option value="17" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 17)) selected @endif>5:00 PM</option>
                                        <option value="18" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 18)) selected @endif>6:00 PM</option>
                                        <option value="19" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 19)) selected @endif>7:00 PM</option>
                                        <option value="20" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 20)) selected @endif>8:00 PM</option>
                                        <option value="21" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 21)) selected @endif>9:00 PM</option>
                                        <option value="22" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 22)) selected @endif>10:00 PM</option>
                                        <option value="23" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 23)) selected @endif>11:00 PM</option>
                                        <option value="24" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 24)) selected @endif>12:00 am</option>
                                    </select>
                                  </div>
                              </label>
                          </li>
                          <li class="send-reminders habits-reminders">
                        <input type="radio" id="habits_send_Weekly" name="habits-send-mail" class="form-control mb"  value="weekly" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "weekly")) checked @endif>
                        <label for="habits_send_Weekly">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">C</span>
                            <span class="yes-active">key <b>C</b></span>
                            <span>Weekly</span>
                            <div class="showDayBox">
                                <select id="weekly_day_habits">
                                    <option value="Mon" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Mon")) selected @endif>Mon</option>
                                    <option value="Tue" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Tue")) selected @endif>Tue</option>
                                    <option value="Wed" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Wed")) selected @endif>Wed</option>
                                    <option value="Thu" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Thu")) selected @endif>Thu</option>
                                    <option value="Fri" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Fri")) selected @endif>Fri</option>
                                    <option value="Sat" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Sat")) selected @endif>Sat</option>
                                    <option value="Sun" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Sun")) selected @endif>Sun</option>
                                </select>
                            </div>
                        </label>
                    </li>
                    <li class="send-reminders habits-reminders">
                        <input type="radio" id="habits_send_Monthly" name="habits-send-mail" class="form-control mb" value="monthly" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "monthly")) checked @endif>
                        <label for="habits_send_Monthly">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">D</span>
                            <span class="yes-active">key <b>D</b></span>
                            <span>Monthly</span>
                            <div class="showMonthBox">
                                <select id="month_date_habits">
                                  <option value="1" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "1")) selected @endif>1</option>
                                  <option value="2" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "2")) selected @endif>2</option>
                                  <option value="3" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "3")) selected @endif>3</option>
                                  <option value="4" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "4")) selected @endif>4</option>
                                  <option value="5" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "5")) selected @endif>5</option>
                                  <option value="6" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "6")) selected @endif>6</option>
                                  <option value="7" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "7")) selected @endif>7</option>
                                  <option value="8" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "8")) selected @endif>8</option>
                                  <option value="9" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "9")) selected @endif>9</option>
                                  <option value="10" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "10")) selected @endif>10</option>
                                  <option value="11" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "11")) selected @endif>11</option>
                                  <option value="12" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "12")) selected @endif>12</option>
                                  <option value="13" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "13")) selected @endif>13</option>
                                  <option value="14" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "14")) selected @endif>14</option>
                                  <option value="15" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "15")) selected @endif>15</option>
                                  <option value="16" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "16")) selected @endif>16</option>
                                  <option value="17" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "17")) selected @endif>17</option>
                                  <option value="18" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "18")) selected @endif>18</option>
                                  <option value="19" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "19")) selected @endif>19</option>
                                  <option value="20" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "20")) selected @endif>20</option>
                                  <option value="21" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "21")) selected @endif>21</option>
                                  <option value="22" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "22")) selected @endif>22</option>
                                  <option value="23" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "23")) selected @endif>23</option>
                                  <option value="24" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "24")) selected @endif>24</option>
                                  <option value="25" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "25")) selected @endif>25</option>
                                  <option value="26" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "26")) selected @endif>26</option>
                                  <option value="27" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "27")) selected @endif>27</option>
                                  <option value="28" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "28")) selected @endif>28</option>
                                  <option value="29" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "29")) selected @endif>29</option>
                                  <option value="30" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "30")) selected @endif>30</option>
                                </select>
                            </div>
                        </label>
                    </li>
                          <li class="send-reminders habits-reminders">
                              <input type="radio" id="habits_send_None" name="habits-send-mail" class="form-control mb" value="none" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "none")) checked @endif>
                              <label for="habits_send_None">
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
                            <div ng-if="goalBuddy.syg2_send_msg.$dirty && goalBuddy.syg2_send_msg.$invalid" class="vp-tooltip">
                              <span>Please, insert the correct value!</span>
                            </div>

                          </div> <!-- end: INPUT BODY -->

                        </div> <!-- end: INPUT MALE FEMALE -->
                        <div class="clear-both"></div>
                      </li>
                      <!-- end: syg2_send_msg | 5 -->
                      

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
                          <a href="javascript:void(0)" ng-click="jumpToPrevInput()"><i class="fa fa-chevron-up" aria-hidden="true">Back</i></a>
                          <a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true">Next</i></a>
                        </div> <!--  -->
                      </div> <!-- end: COL8 || SUBMIT -->
                    </div>
                  </div>
                  <!-- end: progressbar -->

                </div> <!-- end row -->






                {{--<input type="hidden" class="form-control" id="update-hebit" value="" name="update_habit">--}}
                {{--<input type="hidden" class="form-control" id="habit-id" value="{{isset($habitDetails)?$habitDetails->id:null}}" name="habit_id">--}}
<!-- input type="nexthabitid" -->
                {{--<div class="col-md-6">--}}
                  {{--<fieldset class="padding-15">--}}
                    {{--<legend>Habits</legend>--}}

                    {{--<div class="form-group">--}}
                      {{--<label for="SYG_habits" class="strong">Name Your Habit  *</span></label>--}}
                      {{--<input type="text" id="SYG_habits" value="{{isset($habitDetails)?$habitDetails->gb_habit_name:null}}" name="SYG_habits" class="form-control" required>--}}
                    {{--</div> <!-- end form group -->--}}

                    {{--<div class="form-group">--}}
                      {{--<label class="strong">Habit Recurrence</label>--}}
                      {{--<div>--}}
                        {{--<div class="radio clip-radio radio-primary radio-inline m-b-0">--}}
                          {{--<input type="radio" name="SYG_habit_recurrence" id="SYG_habit_recurrence0" value="daily"  {{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'daily'?'checked':'' }} @if(!isset($habitDetails)) checked @endif>--}}
                          {{--<label for="SYG_habit_recurrence0"> Daily </label>--}}
                        {{--</div>--}}
                        {{--<div class="radio clip-radio radio-primary radio-inline m-b-0">--}}
                          {{--<input type="radio" name="SYG_habit_recurrence" id="SYG_habit_recurrence1" value="weekly" {{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'weekly'?'checked':'' }}>--}}
                          {{--<label for="SYG_habit_recurrence1"> Weekly </label>--}}
                        {{--</div>--}}
                        {{--<div class="radio clip-radio radio-primary radio-inline m-b-0">--}}
                          {{--<input type="radio" name="SYG_habit_recurrence" id="SYG_habit_recurrence2" value="monthly" {{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'monthly'?'checked':'' }}>--}}
                          {{--<label for="SYG_habit_recurrence2"> Monthly </label>--}}
                        {{--</div>--}}

                        {{--<div class="show-weeks"  @if((isset($habitDetails))&&($habitDetails->gb_habit_recurrence_type=='weekly')) style="display: block;" @else style="display: none;"   @endif>--}}

                          {{--<div class="checkbox clip-check check-primary checkbox-inline m-b-0">--}}
                            {{--<input id="goalEventRepeatWeekdays0" class="goalEventRepeatWeekdays" value="Monday" type="checkbox"  @if(isset($habitDetails) && (in_array('Monday',$habitDetails->gbHabitWeekDetails))) checked @endif >--}}
                            {{--<label for="goalEventRepeatWeekdays0"> Mon </label>--}}
                          {{--</div>--}}
                          {{--<div class="checkbox clip-check check-primary checkbox-inline m-b-0">--}}
                            {{--<input id="goalEventRepeatWeekdays1" class="goalEventRepeatWeekdays" value="Tuesday" type="checkbox" @if(isset($habitDetails) && (in_array('Tuesday',$habitDetails->gbHabitWeekDetails))) checked @endif>--}}
                            {{--<label for="goalEventRepeatWeekdays1"> Tue </label>--}}
                          {{--</div>--}}
                          {{--<div class="checkbox clip-check check-primary checkbox-inline m-b-0">--}}
                            {{--<input id="goalEventRepeatWeekdays2" class="goalEventRepeatWeekdays" value="Wednesday" type="checkbox" @if(isset($habitDetails) && (in_array('Wednesday',$habitDetails->gbHabitWeekDetails))) checked @endif>--}}
                            {{--<label for="goalEventRepeatWeekdays2"> Wed </label>--}}
                          {{--</div>--}}
                          {{--<div class="checkbox clip-check check-primary checkbox-inline m-b-0">--}}
                            {{--<input id="goalEventRepeatWeekdays3" class="goalEventRepeatWeekdays" value="Thursday" type="checkbox" @if(isset($habitDetails) && (in_array('Thursday',$habitDetails->gbHabitWeekDetails))) checked @endif>--}}
                            {{--<label for="goalEventRepeatWeekdays3"> Thu </label>--}}
                          {{--</div>--}}
                          {{--<div class="checkbox clip-check check-primary checkbox-inline m-b-0">--}}
                            {{--<input id="goalEventRepeatWeekdays4" class="goalEventRepeatWeekdays" value="Friday" type="checkbox" @if(isset($habitDetails) && (in_array('Friday',$habitDetails->gbHabitWeekDetails))) checked @endif>--}}
                            {{--<label for="goalEventRepeatWeekdays4"> Fri </label>--}}
                          {{--</div>--}}
                          {{--<div class="checkbox clip-check check-primary checkbox-inline m-b-0">--}}
                            {{--<input id="goalEventRepeatWeekdays5" class="goalEventRepeatWeekdays"  value="Saturday" type="checkbox" @if(isset($habitDetails) && (in_array('Saturday',$habitDetails->gbHabitWeekDetails))) checked @endif>--}}
                            {{--<label for="goalEventRepeatWeekdays5"> Sat </label>--}}
                          {{--</div>--}}
                          {{--<div class="checkbox clip-check check-primary checkbox-inline m-b-0">--}}
                            {{--<input id="goalEventRepeatWeekdays6" class="goalEventRepeatWeekdays" value="Sunday" type="checkbox" @if(isset($habitDetails) && (in_array('Sunday',$habitDetails->gbHabitWeekDetails))) checked @endif>--}}
                            {{--<label for="goalEventRepeatWeekdays6"> Sun </label>--}}
                          {{--</div>--}}

                        {{--</div>--}}

                        {{--<div class="month-count" @if((isset($habitDetails))&&($habitDetails->gb_habit_recurrence_type=='monthly')) style="display: block;" @else style="display: none;"   @endif>--}}
                          {{--@if(Request::segment(2) == 'edithabit')--}}
                          {{--<div class ="month-count-div">Day <select class="month-date">--}}
                            {{--@for($i = 1; $i <= calDaysInMonth(); $i++)--}}
                            {{--@if($i==$habitDetails->gb_habit_recurrence_month)--}}
                            {{--<option value ="{{ $i }}" selected="">{{ $i }}</option>--}}
                            {{--@else--}}
                            {{--<option value ="{{ $i }}">{{ $i }}</option>--}}
                            {{--@endif--}}
                            {{--@endfor--}}
                          {{--</select>--}}
                        {{--</div>--}}
                        {{--@endif--}}
                      {{--</div>--}}
                    {{--</div>--}}
                  {{--</div> <!-- end form group -->--}}


                  {{--<div class="form-group">--}}
                    {{--<label class="strong" for="SYG_notes">Why is this habit important to me? </label>--}}
                    {{--<div>--}}
                      {{--<textarea rows="3" cols="48" id="SYG_notes" class="form-control" name="SYG_notes">{{isset($habitDetails)?$habitDetails->gb_habit_notes:null}}</textarea>--}}
                    {{--</div>--}}
                  {{--</div> <!-- end form group -->--}}


                  {{--<div class ="milestone-div" @if((isset($milestonesData))&&(count($milestonesData) < 1)) style="display: none;" @endif>--}}
                    {{--<div class="form-group"><!-- btn_dd -->--}}
                      {{--<label for="milestone_div" class="strong milestone-div-label"><span>Is this habit associated with a milestone of this goal?</span></label>--}}
                      {{--<div class="milestone-dropdown">--}}
                        {{----}}
                        {{--@if((isset($milestonesData))&&(count($milestonesData) > 0))--}}
                        {{--<select id="milestone_div" name="milestone_value" class="form-control onchange-set-neutral milestone_div_class" multiple="">--}}
                          {{--@foreach($milestonesData as $milestones)--}}
                          {{--@if(isset($habitDetails) && $habitDetails->gb_milestones_id && in_array($milestones->id, explode(',', $habitDetails->gb_milestones_id)))--}}
                          {{--<option value="{{$milestones->id}}" selected="">{{$milestones->gb_milestones_name}}</option>--}}
                          {{--@else--}}
                          {{--<option value="{{$milestones->id}}">{{$milestones->gb_milestones_name}}</option>--}}
                          {{--@endif--}}
                          {{--@endforeach--}}
                        {{--</select>--}}
                        {{--@endif--}}

                      {{--</div>--}}
                    {{--</div>--}}
                  {{--</div> <!-- end form group -->--}}

                  {{--<p id="miles_id"></p>--}}
                {{--</fieldset>--}}
              {{--</div> <!-- end col6 -->--}}


              {{--<div class="col-md-6">--}}
                {{--<fieldset class="padding-15 step2" >--}}
                  {{--<legend>Sharing</legend>--}}
                  {{--<div class="form-group">--}}
                    {{--<label class="strong">Who can see this habit? </label>--}}
                    {{--<div>--}}
                      {{--<div class="radio clip-radio radio-primary radio-inline m-b-0">--}}
                        {{--<input type="radio" name="syg2_see_habit" id="syg2_see_habit0" value="everyone"  {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'everyone'?'checked':'' }} @if(!isset($habitDetails)) checked @endif>--}}
                        {{--<label for="syg2_see_habit0"> Everyone </label>--}}
                      {{--</div>--}}
                      {{--<!--<div class="radio clip-radio radio-primary radio-inline m-b-0">--}}
                        {{--<input type="radio" name="syg2_see_habit" id="syg2_see_habit1" value="habit-friends" {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'habit-friends'?'checked':'' }}>--}}
                        {{--<label for="syg2_see_habit1"> My Friends </label>--}}
                      {{--</div>-->--}}
                      {{--<div class="radio clip-radio radio-primary radio-inline m-b-0">--}}
                        {{--<input type="radio" name="syg2_see_habit" id="syg2_see_habit2" value="Just Me" {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'Just Me'?'checked':'' }}>--}}
                        {{--<label for="syg2_see_habit2"> Just Me </label>--}}
                      {{--</div>--}}
                      {{--<div class ="habit-friends-section">--}}
                        {{--<table>--}}
                          {{--<tr>--}}
                            {{--<td><button type="button" class="btn btn-wide btn-o btn-success" style="margin-bottom: 5px;" data-toggle="modal" data-target="#friendModal">Choose</button>--}}
                            {{--</br>--}}
                            {{--<button type="button" class="btn btn-wide btn-o btn-success" data-toggle="modal" data-target="#friendModal">Friends</button></td>--}}
                            {{--<td><span> <a href="javascript:choose_friends()" id="view_count" style="margin: 0px 0px 5px 5px;">0 Friends can view</a></br>--}}
                              {{--<a href="javascript:choose_friends()" id="edit_count" style="margin: 0px 0px 5px 5px;">0 Friends can edit</a> </span></td>--}}
                            {{--</tr>--}}
                          {{--</table>--}}
                        {{--</div>--}}

                        {{--<div id="friendModal" class="modal fade" role="dialog">--}}
                          {{--<div class="modal-dialog">--}}

                            {{--<!-- Modal content-->--}}
                            {{--<div class="modal-content">--}}
                              {{--<div class="modal-header">--}}
                                {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
                              {{--</div>--}}
                              {{--<div class="modal-body">--}}
                                {{--<div class ="row">--}}
                                  {{--<div class ="col-md-6">--}}
                                    {{--<h4> Who can see this habit?</h4>--}}
                                  {{--</div>--}}
                                  {{--<div class ="col-md-6">--}}
                                    {{--<input type ="checkbox" name ="all-friends">--}}
                                  {{--All my friends can view this habit </div>--}}
                                {{--</div>--}}
                                {{--<div class ="row">--}}
                                  {{--<div class ="col-md-6">--}}
                                    {{--<h4>Your friends:</h4>--}}
                                  {{--</div>--}}
                                  {{--<div class ="col-md-6">--}}
                                    {{--<h4>Friends with access to this habit:</h4>--}}
                                  {{--</div>--}}
                                  {{--<button type="button" class="btn btn-info btn-lg" style ="margin: 10px 0px 13px 15px;" data-dismiss="modal" data-size="l">Ok</button>--}}
                                {{--</div>--}}
                                {{--<div class="modal-footer"> </div>--}}
                              {{--</div>--}}
                            {{--</div>--}}
                          {{--</div>--}}
                        {{--</div>--}}
                      {{--</div> <!-- end form group -->--}}


                      {{--<div class="form-group">--}}
                        {{--<label class="strong">Send e-mail / SMS reminders </label>--}}
                        {{--<div>--}}
                          {{--<div class="radio clip-radio radio-primary radio-inline m-b-0">--}}
                            {{--<input type="radio" name="syg2_send_msg" id="syg2_send_msg0" value="only_if_I_am_late" @if(!isset($habitDetails)) checked @endif {{ isset($habitDetails) && $habitDetails->gb_habit_reminder == 'only_if_I_am_late'?'checked':'' }}>--}}
                            {{--<label for="syg2_send_msg0"> Only if I am late </label>--}}
                          {{--</div>--}}
                          {{--<div class="radio clip-radio radio-primary radio-inline m-b-0">--}}
                            {{--<input type="radio" name="syg2_send_msg" id="syg2_send_msg1" value="Every_occurrence" {{ isset($habitDetails) && $habitDetails->gb_habit_reminder == 'Every_occurrence'?'checked':'' }}>--}}
                            {{--<label for="syg2_send_msg1"> Every occurrence </label>--}}
                          {{--</div>--}}
                          {{--<div class="radio clip-radio radio-primary radio-inline m-b-0">--}}
                            {{--<input type="radio" name="syg2_send_msg" id="syg2_send_msg2" value="none" {{ isset($habitDetails) && $habitDetails->gb_habit_reminder == 'none'?'checked':'' }}>--}}
                            {{--<label for="syg2_send_msg2"> None </label>--}}
                          {{--</div>--}}
                        {{--</div>--}}
                      {{--</div> <!-- end form group -->--}}

                    {{--</div>--}}
                  {{--</fieldset>--}}

                {{--</div> <!-- end col6 -->--}}
