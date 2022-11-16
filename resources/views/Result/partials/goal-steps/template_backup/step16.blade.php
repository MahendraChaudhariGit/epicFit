<div class="step data-step milestoneEmail habitStep" data-step="16">
                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">

                        <div class="watermark1" data-id="15"><span>15.</span></div>

                        <label class="steps-head"> Send e-mail / SMS <strong>reminders</strong></label>

                      </div>

                      <div class="tooltip-sign mb-10">

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
                           It is important to come to the realisation that actions that may seem insignificant such as missing a training session, skipping a meal, or altering guidelines can have adverse accumulative effects on your overall progress, the earlier it is noticed by you by being given a reminder from the <span style='color:#f64c1e;'><b>EPIC</b> TEAM</span> the sooner you can get on top of it and get back on track.
                           <br/><br/>
                           Approach every milestone related task with the utmost importance as if others are relying on you, because YOU are relying on YOU!"
                           data-message1="<b>When Overdue—</b>If Goal has not been met<br/>
                              <b>Daily—</b>Send me messages related to Goal daily<br/>
                              <b>Weekly—</b>Send me messages related to Goal weekly<br/>
                              <b>Monthly—</b>Send me messages related to Goal monthly<br/>
                              <b>None—</b>Do not send me anything
                           "><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>
                     <div class="form-group">
                        <label class="container_radio version_2">When Overdue
                        <input type="radio" name="milestones-Send-mail" value="when_overdue">
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">
                           Daily
                           <input type="radio" name="milestones-Send-mail" value="daily" class="daily">
                           <span class="checkmark"></span>
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
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">
                           Weekly
                           <input type="radio" name="milestones-Send-mail" value="weekly" class="weekly">
                           <span class="checkmark"></span>
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
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">
                           Monthly
                           <input type="radio" name="milestones-Send-mail" value="monthly" class="monthly">
                           <span class="checkmark"></span>
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
                     </div>
                     <label>Get Notifications Through ? </label>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email.
                        <input type="radio" name="milestones-Send-epichq" value="email">
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through chat.
                        <input type="radio" name="milestones-Send-epichq" value="epichq">
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email and chat both.
                        <input type="radio" name="milestones-Send-epichq" value="email-epichq">
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group send-reminders">
                        <label class="container_radio version_2">None
                        <input type="radio" name="milestones-Send-epichq" value="none">
                        <span class="checkmark"></span>
                        </label>
                     </div>
                  </div>
<script type="text/javascript">
   $(document).ready(function() {
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
   });
    $(document).ready(function() {
       loadFormData();
    });

    function loadFormData(){
       var current_step = $('.step').data('step');
       $.ajax({
           url: public_url + 'goal-buddy/load-form-data',
           type: "POST",
           data: {'current_step': current_step},
           success: function (data) {
              console.log(data);
              if(data.data && data.data['milestones-Send-mail'] != undefined ){
                 $("input[type=radio][value='"+data.data['milestones-Send-mail']+"']").attr("checked",true);
              }
              if(data.data && data.data['milestones-Send-epichq'] != undefined ){
                 $("input[type=radio][value='"+data.data['milestones-Send-epichq']+"']").attr("checked",true);
              }
            }
       });
    }
</script>