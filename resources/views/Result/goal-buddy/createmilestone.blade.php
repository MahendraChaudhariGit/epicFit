<div class="row vp-form-container container-gb-step-2">
  <div class="col-sm-12">

    <input type="hidden"  id="milestones_id" value="{{isset($mileStoneIdStr)?$mileStoneIdStr:null}}" name="milestones_id">
    <?php
      $goalId = '';

      if(isset($goalDetails) && $goalDetails) {
        $goalId = $goalDetails->id;
      }

       /* if(isset($milestonesGoalId))
        $goalId = $milestonesGoalId;
        elseif(isset($milestonesData) && count($milestonesData))
        $goalId = $milestonesData[0]->goal_id; */
    ?>                  
    <input type="hidden"  id="goal_milestones_id" value="{{$goalId}}" name="goalmilestones_id">
    <input type="hidden"  id="goalDueDate" value="" name="goal_due_date">
    <button class="btn btn-primary btn-o btn-wide new-submit-first-form" style="display:none;">Set a goal</button>
    {{--<h4>Milestone Details</h4>--}}

    <ul id="viewport-2" class="vp-form-input-list">

      <!-- start: Milestones | 0 -->
      <li class="vp-item vp-bgs2-item vp-form-active" data-index="0" data-sub-index="null" data-type="text" data-valid="@{{goalBuddy.Milestones.$valid}}">
        <div class="vp-input input-yes-no-btn">
          {{-- <h3 class="vp-index pull-left">1. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3 data-toggle="tooltip" title="<b>Milestones</b> help breakdown your goal into bite sized chunks giving<br>you less daunting and more achievable end result.<br>If milestones are not met as required to meet your individual goal timeline,<br>your timeline or priorityof task can be adjusted as required without affecting<br> your positive outlook on the overall goal and your ability to achieve it.<br>Examples of milestone includes: 0.5-1.5 Kg loss, reducing dress size, increasing hydration by 100ml each week.<br><br>1. Measurments cm or inches<br>2. Body fat percentage<br>3. Clothing size in inches or dress size" ><i class="fa fa-arrow-right" aria-hidden="true"></i> <span>What <b>Milestones</b> I've Got to Accomplish Before I Reach My Goal?</span></h3>

            <i class="fa fa-bullseye fa-5x title-icon" aria-hidden="true"></i>

            {{--<img ng-if="input.iconUrl" class="title-icon" src="{{ input.iconUrl }}" alt="USER">--}}

            {{--<div class="description mb">--}}
              {{--Choose one from a template:--}}
            {{--</div> <!-- end: DESCRIPTION -->--}}
          </div> <!-- end: INPUT HEADER -->


          <div class="input-body mb">

            <!-- start: MILESTONE -->
            <div class="form-group">

              <div class="row">
                <div class="col-md-8 col-sm-8 res-mr-15" style="margin-bottom: 10px;">
                  <input type="text" class="form-control mli-18" id="Milestones" ng-keypress="pressEnter($event)" ng-model="Milestones" value="" name="Milestones">
                 </div> <!-- end col8 -->

                  <div class="col-md-4 col-sm-4">
                    <button class="btn btn-primary Btn_milestone mli-18" style="padding: 8px;"> <span>Add Milestone</span> </button>
                  </div> <!-- end col4 -->
              </div> <!-- end row -->

                <?php
                $milestonesSeen='';
                $milestonesReminder='';
                $milestonesGoalId='';
                ?>
              <div class="dd mile_section row" >
                <ul class="dd-list col-md-12" style="margin-left: 10px !important;">
                  @if((isset($milestonesData))&&($milestonesData->count() > 0))
                    @foreach($milestonesData as $milestones)
                    
                      <li class="dd-item row" data-milestones-id="{{$milestones->id}}" style="line-height: 20px; !important"><div class="milestones-form">
                          <div class="col-md-4 col-sm-4 milestones-date-cls">
                            <input data-toggle="tooltip" title="Milestone Name" name="milestones" class="form-control milestones-name" value="{{$milestones->gb_milestones_name}}" data-milestones-id="{{$milestones->id}}" type="text" disabled="disabled" />
                          </div>
                          <div class="col-md-4 col-sm-4 milestones-date-cls">
                            <input data-toggle="tooltip" title="Milestone Due Date" class="form-control milestones-date datepicker_SYG4" autocomplete="off" name="milestones-date" required="" value="{{$milestones->gb_milestones_date}}" type="text" disabled="disabled" />
                          </div>
                          <div class="col-md-2 col-sm-2 m-t-20 pencil_find_sibling">
                            <a><i class="fa fa-times delete-milestone-info" style="margin-right: 5px" data-milestones-id="{{$milestones->id}}"></i></a>
                            <a><i class="fa fa-pencil edit-milestone-info hidden" style="display:inline; font-size: 16px"></i></a>
                            <a><i class="fa fa-save save-milestone-info" data-milestones-id="{{$milestones->id}}" style="display:none"></i></a>
                          </div>
                        </div>
                      </li>
                            <?php $milestonesSeen =isset($milestonesData)?$milestones->gb_milestones_seen:'';
                            $milestonesReminder =isset($milestonesData)?$milestones->gb_milestones_reminder:'';
                            $milestonesGoalId =isset($milestonesData)?$milestones->goal_id:'';
                            ?>
                    @endforeach
                  @endif
                </ul>
              </div> <!-- end row -->
            </div>
            <!-- end: MILESTONE -->



            {{-- <div ng-if="goalBuddy.Milestones.$touched && goalBuddy.Milestones.$invalid" class="vp-tooltip">
              <span>This field is required!</span>
            </div> --}}

            <div class="enter-btn mti-15 active">
              <button type="button" class="btn btn-primary mli-18" ng-click="jumpToNextInput()">
                OK <i class="fa fa-check" aria-hidden="true"></i>
              </button>
              <span class="press-enter">click <b>OK</b></span>
            </div>

          </div> <!-- end: INPUT BODY -->

        </div> <!-- end: INPUT MALE FEMALE -->
        <div class="clear-both"></div>
      </li>
      <!-- end: Milestones | 0 -->



      <!-- start: gb_milestones_seen | 1 -->
      <li class="vp-item vp-bgs2-item" data-index="1" data-sub-index="0" data-type="radio" data-valid="@{{goalBuddy.gb_milestones_seen.$valid}}">
        <div class="vp-input input-yes-no-btn" style="opacity: 1">
          {{-- <h3 class="vp-index pull-left">2. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3 data-toggle="tooltip" title="1. <b style='color:#f64c1e;'>Everyone</b> - Share details and goal with friends and family<br>2. <b style='color:#f64c1e;'>Just Me</b> - Only show me details and goal">
              <i class="fa fa-arrow-right" aria-hidden="true"></i>
              <span>Who can <b>view</b> your <b>milestones</b>?</span>
            </h3>
          </div> <!-- end: INPUT HEADER -->


          <div class="input-body mb">

            <ul class="click-box clear-both dib">
              <script>
                  $(document).ready(function() {
                      var goal_seen = "{{ isset($milestonesData) ? $milestonesSeen : 'null' }}";
                      if(goal_seen !== 'null') {
                          window.gbs2Data.radio[0].value = goal_seen;

                          if(goal_seen === 'everyone') {
                              window.gbs2Data.radio[0].activeOption = 0;
                          } else if(goal_seen === 'Just_Me') {
                              window.gbs2Data.radio[0].activeOption = 1;
                          }

                          window.digestGbs2();
                      }
                  });
              </script>
              <li class="who-can-view @{{ (data.radio[0].activeOption == $index) ? 'active' : '' }}" ng-repeat="option in data.radio[0].options" ng-click="setRadioValue(0, $index)">
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

            <input type="hidden" ng-keypress="pressEnter($event)" id="SYG3_see_milestones0" name="gb_milestones_seen" ng-value="data.radio[0].value" ng-model="gb_milestones_seen" placeholder="" class="form-control mb">

            <div ng-if="goalBuddy.gb_milestones_seen.$touched && goalBuddy.gb_milestones_seen.$invalid" class="vp-tooltip">
              <span>Please, insert the correct value!</span>
            </div>

          </div> <!-- end: INPUT BODY -->

        </div> <!-- end: INPUT MALE FEMALE -->
        <div class="clear-both"></div>
      </li>
      <!-- end: gb_milestones_seen | 1 -->



      <!-- start: gb_milestones_reminder | 3 -->
      <li class="vp-item vp-bgs2-item" data-index="2" data-sub-index="1" data-type="radio" data-valid="@{{goalBuddy.gb_milestones_reminder.$valid}}">
        <div class="vp-input input-yes-no-btn" style="opacity: 1">
          {{-- <h3 class="vp-index pull-left">3. &nbsp;&nbsp;</h3> --}}

          <div class="input-header">
            <h3 data-toggle="tooltip" title="1. <b style='color:#f64c1e;'>When Ovredue</b> - If goal has not been met<br>2. <b style='color:#f64c1e;'>Daily</b> - Send me message related to goal daily.<br> 3. <b style='color:#f64c1e;'>weekly</b> - Send me message related to goal weekly.<br> 4. <b>Monthly</b> - Send me message related to goal monthly.<br> 5. <b style='color:#f64c1e;'>None</b> - Don't send me anything.">
              <i class="fa fa-arrow-right" aria-hidden="true"></i>
              <span>Send <b>e-mail</b> / <b>SMS</b> reminders</span>
            </h3>
          </div> <!-- end: INPUT HEADER -->  

          <div class="input-body mb">
            <ul class="click-box clear-both dib">              
                    <li class="send-reminders milestones-reminders">
                        <input type="radio" id="milestones_send_Overdue" name="milestones-Send-mail" class="form-control mb" value="when_overdue" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder == "when_overdue")) checked @endif>

                        <label for="milestones_send_Overdue">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">A</span>
                            <span class="yes-active">key <b>A</b></span>
                            <span>When Overdue</span>
                        </label>
                    </li>
                     <li class="send-reminders milestones-reminders">
                        <input type="radio" id="milestones_send_Daily" name="milestones-Send-mail" class="form-control mb" value="daily" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder == "daily")) checked @endif>
                        <label for="milestones_send_Daily">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">B</span>
                            <span class="yes-active">key <b>B</b></span>
                            <span>Daily</span>
                            <div class="showTimeBox">
                                <select id="daily_time_milestones">                                  
                                    <option value="1" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 1)) selected @endif>1:00 am</option>
                                    <option  value="2"  @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 2)) selected @endif>2:00 am</option>
                                    <option value="3"  @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 3)) selected @endif>3:00 am</option>
                                    <option value="4"  @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 4)) selected @endif>4:00 am</option>
                                    <option value="5"  @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 5)) selected @endif>5:00 am</option>
                                    <option value="6"  @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 6)) selected @endif>6:00 am</option>
                                    <option value="7"  @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 7)) selected @endif>7:00 am</option>
                                    <option value="9"  @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 9)) selected @endif>9:00 am</option>
                                    <option value="10" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 10)) selected @endif>10:00 am</option>
                                    <option value="11" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 11)) selected @endif>11:00 am</option>
                                    <option value="12" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 12)) selected @endif>12:00 PM</option>
                                    <option value="13" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 13)) selected @endif>1:00 PM</option>
                                    <option value="14" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 14)) selected @endif>2:00 PM</option>
                                    <option value="15" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 15)) selected @endif>3:00 PM</option>
                                    <option value="16" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 16)) selected @endif>4:00 PM</option>
                                    <option value="17" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 17)) selected @endif>5:00 PM</option>
                                    <option value="18" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 18)) selected @endif>6:00 PM</option>
                                    <option value="19" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 19)) selected @endif>7:00 PM</option>
                                    <option value="20" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 20)) selected @endif>8:00 PM</option>
                                    <option value="21" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 21)) selected @endif>9:00 PM</option>
                                    <option value="22" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 22)) selected @endif>10:00 PM</option>
                                    <option value="23" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 23)) selected @endif>11:00 PM</option>
                                    <option value="24" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == 24)) selected @endif>12:00 am</option>
                                </select>
                            </div>
                        </label>
                    </li>
                     <li class="send-reminders milestones-reminders">
                        <input type="radio" id="milestones_send_Weekly" name="milestones-Send-mail" class="form-control mb" value="weekly" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder == "weekly")) checked @endif>
                        <label for="milestones_send_Weekly">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">C</span>
                            <span class="yes-active">key <b>C</b></span>
                            <span>Weekly</span>
                            <div class="showDayBox">
                                <select id="weekly_day_milestones">
                                    <option value="Mon" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "Mon")) selected @endif>Mon</option>
                                    <option value="Tue" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "Tue")) selected @endif>Tue</option>
                                    <option value="Wed" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "Wed")) selected @endif>Wed</option>
                                    <option value="Thu" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "Thu")) selected @endif>Thu</option>
                                    <option value="Fri" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "Fri")) selected @endif>Fri</option>
                                    <option value="Sat" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "Sat")) selected @endif>Sat</option>
                                    <option value="Sun" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "Sun")) selected @endif>Sun</option>
                                </select>
                            </div>
                        </label>
                    </li>
                    <li class="send-reminders milestones-reminders">
                        <input type="radio" id="milestones_send_Monthly" name="milestones-Send-mail" class="form-control mb" value="monthly" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder == "monthly")) checked @endif>
                        <label for="milestones_send_Monthly">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">D</span>
                            <span class="yes-active">key <b>D</b></span>
                            <span>Monthly</span>
                            <div class="showMonthBox">
                                <select id="month_date_milestones">
                                  <option value="1" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "1")) selected @endif>1</option>
                                  <option value="2" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "2")) selected @endif>2</option>
                                  <option value="3" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "3")) selected @endif>3</option>
                                  <option value="4" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "4")) selected @endif>4</option>
                                  <option value="5" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "5")) selected @endif>5</option>
                                  <option value="6" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "6")) selected @endif>6</option>
                                  <option value="7" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "7")) selected @endif>7</option>
                                  <option value="8" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "8")) selected @endif>8</option>
                                  <option value="9" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "9")) selected @endif>9</option>
                                  <option value="10" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "10")) selected @endif>10</option>
                                  <option value="11" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "11")) selected @endif>11</option>
                                  <option value="12" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "12")) selected @endif>12</option>
                                  <option value="13" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "13")) selected @endif>13</option>
                                  <option value="14" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "14")) selected @endif>14</option>
                                  <option value="15" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "15")) selected @endif>15</option>
                                  <option value="16" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "16")) selected @endif>16</option>
                                  <option value="17" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "17")) selected @endif>17</option>
                                  <option value="18" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "18")) selected @endif>18</option>
                                  <option value="19" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "19")) selected @endif>19</option>
                                  <option value="20" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "20")) selected @endif>20</option>
                                  <option value="21" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "21")) selected @endif>21</option>
                                  <option value="22" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "22")) selected @endif>22</option>
                                  <option value="23" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "23")) selected @endif>23</option>
                                  <option value="24" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "24")) selected @endif>24</option>
                                  <option value="25" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "25")) selected @endif>25</option>
                                  <option value="26" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "26")) selected @endif>26</option>
                                  <option value="27" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "27")) selected @endif>27</option>
                                  <option value="28" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "28")) selected @endif>28</option>
                                  <option value="29" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "29")) selected @endif>29</option>
                                  <option value="30" @if(isset($milestonesData) && ($milestonesData[0]->gb_reminder_milestones_time == "30")) selected @endif>30</option>
                                </select>
                            </div>
                        </label>
                    </li>
                     <li class="send-reminders milestones-reminders">
                        <input type="radio" id="milestones_send_None" name="milestones-Send-mail" class="form-control mb" value="none" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder == "none")) checked @endif>
                        <label for="milestones_send_None">
                            <img class="check-img" src="{{url('result/vendor/vp-form/images/check-right.png')}}" alt="Image">
                            <div class="box-content">
                                <p><i class="fa fa-check" aria-hidden="true"></i></p>
                            </div>
                            <span class="yes">E</span>
                            <span class="yes-active">key <b>E</b></span>
                            <span>None</span>
                        </label>
                    </li>
                </ul>

            <div ng-if="goalBuddy.gb_milestones_reminder.$touched && goalBuddy.gb_milestones_reminder.$invalid" class="vp-tooltip">
              <span>Please, insert the correct value!</span>
            </div>

          </div> <!-- end: INPUT BODY -->

        </div> <!-- end: INPUT MALE FEMALE -->
        <div class="clear-both"></div>
      </li>
      <!-- end: gb_milestones_reminder | 3 -->
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
          <a href="javascript:void(0)" ng-click="jumpToNextInput()"><i class="fa fa-chevron-down" aria-hidden="true">Back</i></a>
        </div> <!--  -->
      </div> <!-- end: COL8 || SUBMIT -->
    </div>
  </div>
  <!-- end: progressbar -->
</div> <!-- end row -->
