<div class="step data-step newHabitForm" id="newHabitForm" data-step="18" data-habit="" data-habit-value="" data-value='0'>
                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">

                        <div class="watermark1" data-id="16"><span>Finish.</span></div>

                        {{-- <label class="steps-head">Name Your <strong>Habit</strong>? *</label> --}}
                        <label class="steps-head">Name Your Habit? *</label>
                      </div>

                      <div class="tooltip-sign mb-10">

                        <a href="javascript:void(0)" class="goal-step" 
                           data-message="Examples of habits to include in most <span style='color:#f64c1e;'><b>EPIC</b> Goals</span> would be:
                           <br/><br/>
                           <b>Physical Activity</b> – This would relate to resistance training, cardiovascular endurance and stretching and recovery routines.
                           <br/><br/>
                           <b>Healthy Balanced Nutrition</b> – This would include basics such as knowing calories and portion distortion, managing food preparation, and planning and limiting vices and tracing and replacing things in your current diet with healthier options.
                           <br/><br/>
                           <b>Stress Management</b> – This would address things such as limiting blue light and focussing on sleep, working on time management"
                           data-message1="Examples of habits to include in most <span style='color:#f64c1e;'><b>EPIC</b> Goals</span> would be:
                           <br/><br/>
                           <b>Physical Activity</b> – This would relate to resistance training, cardiovascular endurance and stretching and recovery routines.
                           <br/><br/>
                           <b>Healthy Balanced Nutrition</b> – This would include basics such as knowing calories and portion distortion, managing food preparation, and planning and limiting vices and tracing and replacing things in your current diet with healthier options.
                           <br/><br/>
                           <b>Stress Management</b> – This would address things such as limiting blue light and focussing on sleep, working on time management"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>
                     <div class="form-group ">
                        <input type="hidden" name="SYG_habits" class="SYG_habits">
                        <input onblur="validateGoalHabit()"  data-toggle="tooltip" title="" type="text" class="form-control SYG_habits" id="SYG_habits" value="{{isset($habitDetails)?$habitDetails->gb_habit_name:null}}" required>
                     </div>

                       <!-- /step-->
                        <div class="habitStep">
                                 <label> Habit Recurrence <a href="javascript:void(0)" class="goal-step" 
                                    data-message="<b>Daily</b>—This is if you are implementing training into your daily routine with no recovery days
                                    <br/><br/>
                                    <b>Weekly</b>—If you have one or more physical activity days in a week
                                    <br/><br/>
                                    <b>Monthly</b>— If it related to a specific day each month maybe testing
                                    <br/><br/>
                                    Habits are critical to any Lifestyle Design Change and always need to be addressed fully to ensure that all aspects of the habits are understood."
                                    data-message1="<b>Daily</b>—This is if you are implementing training into your daily routine with no recovery days
                                    <br/><br/>
                                    <b>Weekly</b>—If you have one or more physical activity days in a week
                                    <br/><br/>
                                    <b>Monthly</b>— If it related to a specific day each month maybe testing
                                    <br/><br/>
                                    Habits are critical to any Lifestyle Design Change and always need to be addressed fully to ensure that all aspects of the habits are understood."><i class="fa fa-question-circle question-mark"></i></a>
                                 </label>
                              <div class="form-group">
                                 <label class="container_radio version_2">
                                 Daily
                                 <input type="radio" name="SYG_habit_recurrence" value="daily" {{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'daily'?'checked':''}} >
                                 <span class="checkmark"></span>
                                 </label>
                              </div>
                              <div class="form-group">
                                 <label class="container_radio version_2">
                                 Weekly
                                 <input type="radio" name="SYG_habit_recurrence" value="weekly"{{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'weekly'?'checked':''}}  class="weekly">
                                 <span class="checkmark"></span>
                                 </label>
                              </div>
                              <div class="showDayBox row showbox" @if((isset($habitDetails))&&($habitDetails->gb_habit_recurrence_type=='weekly')) style="display: block;" @else style="display: none;"   @endif>
                                 <div class="form-group col-xs-4 col-sm-3">
                                    <label class="container_check version_2">Mon
                                    {{-- <input type="checkbox" name="week" value="" class="required"> --}}
                                    <input name="habitRecWeek[]" id="goalEventRepeatWeekdays0" class="goalEventRepeatWeekdays " value="Monday" type="checkbox"  @if(isset($habitDetails) && (in_array('Monday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                                    <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-group col-xs-4 col-sm-3">
                                    <label class="container_check version_2">Tue
                                       <input name="habitRecWeek[]" id="goalEventRepeatWeekdays1" class="goalEventRepeatWeekdays" value="Tuesday" type="checkbox"  @if(isset($habitDetails) && (in_array('Tuesday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                                    <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-group col-xs-4 col-sm-3">
                                    <label class="container_check version_2">Wed
                                       <input name="habitRecWeek[]" id="goalEventRepeatWeekdays2" class="goalEventRepeatWeekdays" value="Wednesday" type="checkbox"  @if(isset($habitDetails) && (in_array('Wednesday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                                    <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-group col-xs-4 col-sm-3">
                                    <label class="container_check version_2">Thu
                                       <input name="habitRecWeek[]" id="goalEventRepeatWeekdays3" class="goalEventRepeatWeekdays hidden" value="Thursday" type="checkbox"  @if(isset($habitDetails) && (in_array('Thursday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                                    <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-group col-xs-4 col-sm-3">
                                    <label class="container_check version_2">Fri
                                       <input name="habitRecWeek[]" id="goalEventRepeatWeekdays4" class="goalEventRepeatWeekdays hidden" value="Friday" type="checkbox"  @if(isset($habitDetails) && (in_array('Friday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                                    <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-group col-xs-4 col-sm-3">
                                    <label class="container_check version_2">Sat
                                   
                                 <input name="habitRecWeek[]" id="goalEventRepeatWeekdays5" class="goalEventRepeatWeekdays" value="Saturday" type="checkbox"  @if(isset($habitDetails) && (in_array('Saturday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                                    <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-group col-xs-4 col-sm-3">
                                    <label class="container_check version_2">Sun
                                       <input name="habitRecWeek[]" id="goalEventRepeatWeekdays6" class="goalEventRepeatWeekdays" value="Sunday" type="checkbox"  @if(isset($habitDetails) && (in_array('Sunday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >
                                    <span class="checkmark"></span>
                                    </label>
                                 </div>
                              </div>
                              <!-- <div class="form-group hbt_rec_monthly">
                                 <label class="container_radio version_2">
                                    Monthly
                                    <input type="radio" name="SYG_habit_recurrence" value="monthly" class="monthly">
                                    <span class="checkmark"></span>
                                    <div class="showMonthBox month">
                                       <div style="display:flex;align-items: center">
                                          Day&nbsp;&nbsp;
                                          <select class="month-date" style="max-width: 40px">
                                             <option value="1">1</option>
                                             <option value="2">2</option>
                                             <option value="3">3</option>
                                             <option value="4">4</option>
                                             <option value="5">5</option>
                                             <option value="6">6</option>
                                             <option value="7">7</option>
                                             <option value="8">8</option>
                                             <option value="9">9</option>
                                             <option value="10">10</option>
                                             <option value="11">11</option>
                                             <option value="12">12</option>
                                             <option value="13">13</option>
                                             <option value="14">14</option>
                                             <option value="15">15</option>
                                             <option value="16">16</option>
                                             <option value="17">17</option>
                                             <option value="18">18</option>
                                             <option value="19">19</option>
                                             <option value="20">20</option>
                                             <option value="21">21</option>
                                             <option value="22">22</option>
                                             <option value="23">23</option>
                                             <option value="24">24</option>
                                             <option value="25">25</option>
                                             <option value="26">26</option>
                                             <option value="27">27</option>
                                             <option value="28">28</option>
                                             <option value="29">29</option>
                                             <option value="30">30</option>
                                             <option value="31">31</option>
                                          </select>
                                          &nbsp;&nbsp;
                                          of every month
                                       </div>
                                    </div>
                                 </label>
                              </div> -->
                        </div>

                        {{-- <label> Why is this habit <strong>important</strong> to me?  --}}
                           <label> Why is this habit important to me?  
                           {{-- <a href="#" data-toggle="modal" data-target="#important-habit"><i class="fa fa-question-circle question-mark"></i></a> --}}
                           <a href="javascript:void(0)" class="goal-step" 
                           data-message="By creating this habit what aspect of your life will change?
                           <br/><br/>
                           Resistance training to improve functional strength.
                           <br/><br/>
                           Cardiovascular endurance to ensure we can do more for longer.
                           <br/><br/>
                           Recovery routine incorporating stretching and rolling."
                           data-message1="By creating this habit what aspect of your life will change?"><i class="fa fa-question-circle question-mark"></i></a>
                        </label>
                     <div class="form-group habit_notes outborder">
                       
                        <textarea data-toggle="tooltip" title="" data-autoresize rows="3" id="SYG_notes" name="SYG_notes" ng-model="SYG_notes" ng-init="SYG_notes='{{ isset($habitDetails) ? $habitDetails->gb_habit_notes : null}}'" placeholder="" class="form-control">{{isset($habitDetails)?$habitDetails->gb_habit_notes:null}}</textarea>
                     </div>
                      <div>
                     <div class="">
                        {{-- <label data-toggle="tooltip" title=""> Is this habit <b>associated</b> with a <b>milestone</b> of this <b>EPIC </b>goal?  --}}
                           <label data-toggle="tooltip" title=""> Is this habit associated with a milestone of this <b>EPIC</b> Goal? 
                           {{-- <a href="#" data-toggle="modal" data-target="#milestone"><i class="fa fa-question-circle question-mark"></i></a> --}}
                           <a href="javascript:void(0)" class="goal-step" 
                           data-message="Does successfully creating this habit contribute to hitting your milestones and achieving your overall goal?
                           <br/><br/>
                           Yes, select individual or yes select all."
                           data-message1="Does successfully creating this habit contribute to hitting your milestones and achieving your overall goal?
                           <br/><br/>
                           Yes, select individual or yes select all."><i class="fa fa-question-circle question-mark"></i></a>
                        </label>
                     </div>
                    <!--  <input type="checkbox" id="gb_habit_select_all_milestone" name="gb_habit_select_all_milestone"/>
                     <label for="gb_habit_select_all_milestone">Select All</label> -->
                     <div class="row">
                       <div class="col-sm-12 col-xs-12">
                         <?php
                         $m_gb_milestone_value = [];
                         if(isset($goalDataNew['milestonesData'])) {
                           foreach ($goalDataNew['milestonesData'] as $milestones) {
                             if(isset($habitDetails) && $habitDetails->gb_milestones_id && in_array($milestones->id, explode(',', $habitDetails->gb_milestones_id))) {
                               $m_gb_milestone_value[] = $milestones->id;
                             }
                           }
                         }
                         ?>
                         <input type="hidden" name="previusSelectedMilesone" class="previusSelectedMilesone" value="{{ !empty($m_gb_milestone_value) ? json_encode($m_gb_milestone_value) : ''}}">

                         <div class="form-group pli-23">
                           <div class="milestone-dropdown">
                              <select id="milestone_div" name="milestone_value[]" class="selectpicker form-control onchange-set-neutral milestone_div_class" multiple="" data-actions-box="true">
                             <!-- <select data-toggle="tooltip" title="Is this habit associated with a milestone of this goal?" id="milestone_div" name="milestone_value" ng-init="milestone_value='{{ json_encode($m_gb_milestone_value) }}'" ng-model="milestone_value" ng-keypress="pressEnter($event)" class="selectpicker form-control onchange-set-neutral goal-change-life milestone_div_class" data-live-search="true" data-selected-text-format="count > 2" multiple="multiple"> -->
                               @if((isset($goalDataNew['milestoneOption']))&&(count($goalDataNew['milestoneOption']) > 0))
                               @foreach($goalDataNew['milestoneOption'] as $key=>$milestones)
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
                      
                     </div>
                     </div>

                     <div class="">
                        {{-- <label data-toggle="tooltip" title=""> Who can view this <b>habit</b>?   --}}
                           <label data-toggle="tooltip" title=""> Who can view your habit?  
                           {{-- <a href="#" data-toggle="modal" data-target="#see-habit"><i class="fa fa-question-circle question-mark"></i></a> --}}
                           <a href="javascript:void(0)" class="goal-step" 
                           data-message="<b>Select Friends</b>—Share details with a select few friends who you believe will be supportive and who will hold you accountable to your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>.
                           <br/><br/>
                           <b>Everyone</b>– Share details of your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> with all friend and family on your Timeline 
                           <br/><br/>
                           <b>Just Me</b>—Only show me details of my <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>
                           <br/><br/>
                           The <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span>  online platform is designed to allow you to connect and interact with fellow team members and like-minded individuals on the same or similar journey to your own. 
                           <br/><br/>
                           Having others view your milestones gives you the opportunity to be given feedback and positive encouragement throughout your journey. 
                           <br/><br/>
                           Allow others to celebrate your success with you!
                            <br/><br/>
                           Accountability is key, fellow <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> members can help hold you accountable when it comes to attending training sessions, staying on target with healthy balanced eating and completing your goal related tasks which relate to your milestones and progression.
                            <br/><br/>
                           <b>T.E.A.M</b> Together Everyone Achieves More"
                           data-message1="<b>Select Friends</b>—Share details with a select few friends who you believe will be supportive and who will hold you accountable to your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>.
                           <br/><br/>
                           <b>Everyone</b>– Share details of your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> with all friend and family on your Timeline 
                           <br/><br/>
                           <b>Just Me</b>—Only show me details of my <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>
                           <br/><br/>
                           The <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> online platform is designed to allow you to connect and interact with fellow team members and like-minded individuals on the same or similar journey to your own. 
                           <br/><br/>
                           Having others view your habits gives you the opportunity to be given feedback and positive encouragement throughout your journey. 
                           <br/><br/>
                           Allow others to celebrate your success with you!
                             <br/><br/>
                           Accountability is key, fellow <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> members can help hold you accountable when it comes to attending training sessions, staying on target with healthy balanced eating and completing your goal related tasks which relate to your milestones and progression.
                             <br/><br/>
                           <b>T.E.A.M</b> Together Everyone Achieves More"><i class="fa fa-question-circle question-mark"></i></a>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">Select Friends
                           <input type="radio" name="syg2_see_habit" id="syg2_see_habit" value="Selected friends"  {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'Selected friends'?'checked':'' }}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">Everyone
                           <input type="radio" name="syg2_see_habit" id="syg2_see_habit0" value="everyone"  {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'everyone'?'checked':'' }}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">Just Me
                           <input type="radio" name="syg2_see_habit" id="syg2_see_habit2" value="Just Me" {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'Just Me'?'checked':'' }}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group hidden">
                        <input type="text" class="form-control autocomplete" id="syg2_selective_friends" ng-keypress="pressEnter($event)" value="" name="syg2_selective_friends" aria-invalid="false">
                     </div>
                     <div class="habitStep">
                      <div class="">
                        {{-- <label>Send e-mail / SMS <b>reminders</b> --}}
                           <label>Send e-mail / SMS 
                           <a href="javascript:void(0)" class="goal-step" 
                           data-message="<b>When Overdue</b>—If milestones have not been met
                           <br/><br/>
                           <b>Daily</b>—Send me messages related to milestones daily
                           <br/><br/>
                           <b>Weekly</b>—Send me messages related to milestones weekly
                           <br/><br/>
                           <b>Monthly</b>—Send me messages related to milestones monthly
                           <br/><br/>
                           <b>None</b>—Do not send me anything related to milestones
                           <br/><br/>
                           At <span style='color:#f64c1e;'><b>EPIC</b></span> we know that life can sometimes get the better of the best of us, reminders keep you accountable for your actions, time management, and overall results. 
                           <br/><br/>
                           It is important to come to the realisation that actions that may seem insignificant such as missing a training session, skipping a meal, or altering guidelines can have adverse accumulative effects on your overall progress, the earlier it is noticed by you by being given a reminder from the <span style='color:#f64c1e;'><b>EPIC</b> TEAM</span>  the sooner you can get on top of it and get back on track.
                           <br/><br/>
                           Approach every milestone related task with the utmost importance as if others are relying on you, because YOU are relying on YOU!"
                           data-message1="<b>When Overdue</b>—If tasks have not been met
                           <br/><br/>
                           <b>Daily</b>—Send me messages related to tasks daily
                           <br/><br/>
                           <b>Weekly</b>—Send me messages related to tasks weekly
                           <br/><br/>
                           <b>Monthly</b>—Send me messages related to tasks monthly
                           <br/><br/>
                           <b>None</b>—Do not send me anything related to tasks
                           <br/><br/>
                           At <span style='color:#f64c1e;'><b>EPIC</b></span> we know that life can sometimes get the better of the best of us, reminders keep you accountable for your actions, time management, and overall results. 
                           <br/><br/>
                           It is important to come to the realisation that actions that may seem insignificant such as missing a training session, skipping a meal, or altering guidelines can have adverse accumulative effects on your overall progress, the earlier it is noticed by you by being given a reminder from the <span style='color:#f64c1e;'><b>EPIC</b> TEAM</span> the sooner you can get on top of it and get back on track.
                           <br/><br/>
                           Approach every habit related to milestones with the utmost importance as if others are relying on you, because YOU are relying on YOU!"><i class="fa fa-question-circle question-mark"></i></a>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">When Overdue
                        <input type="radio" id="habits_send_Overdue" name="habits-send-mail" class="form-control mb" value="when_overdue" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "when_overdue")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">
                           Daily
                           <input type="radio" id="habits_send_Daily" name="habits-send-mail" class="daily" value="daily" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "daily")) checked @endif>
                           <span class="checkmark"></span>
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
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">
                           Weekly
                           <input type="radio" id="habits_send_Weekly" name="habits-send-mail" class="weekly"  value="weekly" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "weekly")) checked @endif>
                           <span class="checkmark"></span>
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
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">
                           Monthly
                           <input type="radio" name="habits-send-mail" value="monthly" class="monthly">
                           <span class="checkmark"></span>
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
                     </div>

                     <label>Get Notifications Through ? </label>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email.
                        <input type="radio" name="habits-send-epichq" value="email" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder_epichq == "email")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through chat.
                        <input type="radio" name="habits-send-epichq" value="epichq" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder_epichq == "epichq")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email and chat both.
                        <input type="radio" name="habits-send-epichq" value="email-epichq" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder_epichq == "email-epichq")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>

                     <div class="form-group send-reminders">
                        <label class="container_radio version_2">None
                           <input type="radio" id="habits_send_None" name="habits-send-epichq" class="form-control mb" value="none" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "none")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     </div>
                  </div>


   <script type="text/javascript">
   
   $(document).ready(function() {
      $("html, body").animate({ scrollTop: 0 }, "slow");
      templateLoad();

      initSelectpicker($('.milestone-dropdown select'));
      $('.milestone_div_class').selectpicker('refresh');
      
      <?php if(!isset($goalDataNew['customHabitNew'])){ ?>
         console.log('no established goal data..')
         loadFormData();
      <?php }?>
      
      loadFriendData();
      
   });

   function loadFriendData(){
   $.ajax({
       url: public_url + 'goal-buddy/load-friend-list',
       type: "GET",
       dataType: 'json',
       processData: false,
       contentType: false,
       success: function (data) {
        var options = data.my_friends;
         $('.autocomplete').amsifySuggestags({
           type :'bootstrap',
           suggestions: options,
           whiteList:true,
         });
       }
   });
}

   $('input[type="radio"]').click(function() {
      var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'weekly') {
         mainDiv.find('.showDayBox').show();           
       }
       else {
         mainDiv.find('.showDayBox').hide();   
       }
   });

   $("input[name=syg2_see_habit]").change(function(){
    var syg2_see_habit = $(this).val();
    if(syg2_see_habit == "Selected friends"){
        $("#syg2_selective_friends").val('');
        
       // loadFriendData();
        $("#syg2_selective_friends").parent().removeClass('hidden');
        $('#syg2_selective_friends').attr('required',true);
        $('#syg2_selective_friends').removeAttr("style");
        $('#syg2_selective_friends').attr('style','height: 0; width: 0; visibility: hidden; padding: 0; margin: 0; float: right');
        $("#syg2_selective_friends-error").attr('style','color:red');
    }else{
        $("#syg2_selective_friends").val('');
        $("#syg2_selective_friends").parent().addClass('hidden');
        $('#syg2_selective_friends').attr('required',false);
        $('#syg2_selective_friends').removeAttr("style");
        $('#syg2_selective_friends').attr('style','display:none');
        $("#syg2_selective_friends-error").html('');
    }
});
   
$('input[type="radio"]').click(function() {
      var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'daily') {
         mainDiv.find('.showTimeBox').show();           
       }
       else {
         mainDiv.find('.showTimeBox').hide();   
       }
   });
   
     $('input[type="radio"]').click(function() {
      var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'weekly') {
         mainDiv.find('.showDayBox').show();           
       }
       else {
         mainDiv.find('.showDayBox').hide();   
       }
   });
   
      $('input[type="radio"]').click(function() {
         var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'monthly') {
         mainDiv.find('.showMonthBox').show();           
       }
       else {
         mainDiv.find('.showMonthBox').hide();   
       }
   });

function loadFormData(){
   var current_step = $('.step').data('step');
   $.ajax({
      url: public_url + 'goal-buddy/load-form-data',
      type: "POST",
      data: {'current_step': current_step},
      success: function (data) {
         console.log(data);
         if(data.data && data.data.milestone_value != undefined){
            $("select#milestone_div").val(data.data.milestone_value).selectpicker("refresh");
         }
         
         //if(data.data && data.data.SYG_habits != undefined ){
            //$("#SYG_habits").val(data.data.SYG_habits);
         //}
         if(data.data && data.data.gb_habit_note_other != undefined ){
            $('#gb_habit_note_other').removeClass('hidden');
            $('#gb_habit_note_other').attr('required',true);
            $('#gb_habit_note_other').attr('placeholder','Input Your Specific Goal note Here...');
            $('#gb_habit_note_other').text(data.data.gb_habit_note_other);
         }
         if(data.data && data.data.SYG_notes != undefined ){
            if(data.data.SYG_notes && data.data.SYG_notes.length > 0){
               data.data.SYG_notes.map(value => {
                  $("input[type=checkbox][value='"+value+"']").attr("checked",true);
               });
            }
         }

         if(data.data && data.data.SYG_habit_recurrence != undefined ){
            
            $("input[type=radio][name='SYG_habit_recurrence'][value='"+data.data.SYG_habit_recurrence+"']").attr("checked",true);

            if(data.data.habitRecWeek && data.data.habitRecWeek.length > 0){

               $('.showDayBox').show();
               data.data.habitRecWeek.map(data => {
                  $("input[type=checkbox][value='"+data+"']").attr("checked",true);
               });

            }
            
         }
         if(data.data && data.data.syg2_see_habit != undefined ){
            $("input[type=radio][value='"+data.data.syg2_see_habit+"']").attr("checked",true);

            if (data.data.syg2_see_habit == "Selected friends") {
               
               $("#syg2_selective_friends").val(
                  data.data.syg2_selective_friends
               );
               $("#syg2_selective_friends").parent().removeClass("hidden");

               // if (
               //   $("#all-my-friends").val() != undefined &&
               //   $("#all-my-friends").val() != ""
               // ) {
               //   console.log("selective frields habit edit...");
               //   $("#syg2_selective_friends").amsifySuggestags("refresh");
               //   var my_friends = JSON.parse($("#all-my-friends").val());
               //   var options = [];

               //   for (var aaa = 0; aaa < my_friends.length; aaa++) {
               //     options[aaa] = {
               //       tag: my_friends[aaa].name,
               //       value: my_friends[aaa].id,
               //     };
               //   }

               //   $(".autocomplete").amsifySuggestags({
               //     type: "bootstrap",
               //     suggestions: options,
               //     whiteList: true,
               //   });
               // }
            } else {
               $("#syg2_selective_friends").attr("value", "");
               $("#syg2_selective_friends").parent().addClass("hidden");
            }

         }
         if(data.data && data.data['habits-send-mail'] != undefined ){
            $("input[type=radio][name='habits-send-mail'][value='"+data.data['habits-send-mail']+"']").attr("checked",true);
         }
         if(data.data && data.data['habits-send-epichq'] != undefined ){
            $("input[type=radio][name='habits-send-epichq'][value='"+data.data['habits-send-epichq']+"']").attr("checked",true);
         }
         if(data.data && data.data.gb_habit_note_other != undefined && data.data.gb_habit_note_other != ""){
            $('#gb_habit_note_other').removeClass('hidden');
            $('#gb_habit_note_other').attr('required',true);
            $('#gb_habit_note_other').attr('placeholder','Describe your achievement Here...');
            $('#gb_habit_note_other').text(data.data.gb_habit_note_other);
         }
      }
   });
}    
$(document).on('change',".gb_habit_note_other",function(){
   var other = $('.gb_habit_note_other').prop('checked');
   if(other == true){
      $('#gb_habit_note_other').removeClass('hidden');
      $('#gb_habit_note_other').attr('required',true);
      $('#gb_habit_note_other').attr('placeholder','Input Your Specific Goal Here...');
   }else{
      $('#gb_habit_note_other').addClass('hidden');
      $('#gb_habit_note_other').attr('required',false);
      $("#gb_habit_note_other-error").html('');
      $('#gb_habit_note_other').val('');
   }
})

function templateLoad(){

      var data = JSON.parse(sessionStorage.getItem("templateData"));

      var html1 = "";
      var radio_val = "";
      var goal_other = null;
      //var fetch_data = data.fetch_data;
      var other1 = "";
      if(data.goal_template.goal_buddy_habit.length > 0){

         var habitData = data.goal_template.goal_buddy_habit[1]; //Second habit data
         var gb_habit_notes = habitData.gb_habit_notes.split("\n");
         
         $(".habit_notes").html("");
         $(".SYG_habits").val(habitData.gb_habit_name);
         $("#SYG_habits").attr("disabled", true);
         
         if(gb_habit_notes.length > 0){
            gb_habit_notes.push("Other");

            for (var i = 0; i < gb_habit_notes.length; i++) {
               // if (fetch_data != null) {
               //    radio_val = fetch_data.gb_habit_notes == gb_habit_notes[i] ? "checked" : "";
               //    goal_other = fetch_data.gb_habit_note_other;
               // }
               
               other1 = "gb_habit_note";

               if (gb_habit_notes[i] == "Other") {
                  
                  other1 = "gb_habit_note_other";
                  if (goal_other != null) {
                     html1 =
                        '<textarea rows="7" class="form-control" id="gb_habit_note_other" name="gb_habit_note_other">' +
                        goal_other +
                        "</textarea>";
                  } else {
                     html1 = '<textarea rows="7" class="form-control hidden" id="gb_habit_note_other" name="gb_habit_note_other" placeholder="Input Your Specific Goal Here..."></textarea>';
               
                  }
               }
               
               if(gb_habit_notes[i] != ""){
                  gb_habit_notes[i] = gb_habit_notes[i].replace(/(\r\n|\n|\r)/gm, "");
                  $(".habit_notes").append(
                  '<div class="form-group">\
                                    <label class="container_check version_2">' +
                     gb_habit_notes[i] +
                     '\
                                    <input type="checkbox" class="' +
                     other1 +
                     '" name="SYG_notes[]" required value="' +
                     gb_habit_notes[i] +
                     '" ' +
                     radio_val +
                     '>\
                                    <span class="checkmark"></span>\
                                    ' +
                     html1 +
                     "\
                                    </label>\
                              </div>"
                  );
               }
            }
         }
         

      }
   }
</script>                  