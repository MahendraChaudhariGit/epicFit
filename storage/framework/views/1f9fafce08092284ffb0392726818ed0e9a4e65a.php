<div class="step data-step milestoneEmail habitStep" data-step="15">
                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">

                        <div class="watermark1" data-id="15"><span>15.</span></div>

                        <label class="steps-head"> Send Email / Message <strong>reminders</strong></label>

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
                                  <option value="1" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "1")): ?> selected <?php endif; ?>>1:00 am</option>
                                  <option  value="2"  <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "2")): ?> selected <?php endif; ?>>2:00 am</option>
                                  <option value="3"  <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "3")): ?> selected <?php endif; ?>>3:00 am</option>
                                  <option value="4"  <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "4")): ?> selected <?php endif; ?>>4:00 am</option>
                                  <option value="5"  <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "5")): ?> selected <?php endif; ?>>5:00 am</option>
                                  <option value="6"  <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "6")): ?> selected <?php endif; ?>>6:00 am</option>
                                  <option value="7"  <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "7")): ?> selected <?php endif; ?>>7:00 am</option>
                                  <option value="8"  <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "8")): ?> selected <?php endif; ?>>8:00 am</option>
                                  <option value="9"  <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "9")): ?> selected <?php endif; ?>>9:00 am</option>
                                  <option value="10" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "10")): ?> selected <?php endif; ?>>10:00 am</option>
                                  <option value="11" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "11")): ?> selected <?php endif; ?>>11:00 am</option>
                                  <option value="12" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "12")): ?> selected <?php endif; ?>>12:00 PM</option>
                                  <option value="13" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "13")): ?> selected <?php endif; ?>>1:00 PM</option>
                                  <option value="14" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "14")): ?> selected <?php endif; ?>>2:00 PM</option>
                                  <option value="15" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "15")): ?> selected <?php endif; ?>>3:00 PM</option>
                                  <option value="16" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "16")): ?> selected <?php endif; ?>>4:00 PM</option>
                                  <option value="17" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "17")): ?> selected <?php endif; ?>>5:00 PM</option>
                                  <option value="18" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "18")): ?> selected <?php endif; ?>>6:00 PM</option>
                                  <option value="19" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "19")): ?> selected <?php endif; ?>>7:00 PM</option>
                                  <option value="20" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "20")): ?> selected <?php endif; ?>>8:00 PM</option>
                                  <option value="21" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "21")): ?> selected <?php endif; ?>>9:00 PM</option>
                                  <option value="22" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "22")): ?> selected <?php endif; ?>>10:00 PM</option>
                                  <option value="23" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "23")): ?> selected <?php endif; ?>>11:00 PM</option>
                                  <option value="24" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "24")): ?> selected <?php endif; ?>>12:00 am</option>
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
                                  <option value="Mon" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "Mon")): ?> selected <?php endif; ?>>Mon</option>
                                  <option value="Tue" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "Tue")): ?> selected <?php endif; ?>>Tue</option>
                                  <option value="Wed" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "Wed")): ?> selected <?php endif; ?>>Wed</option>
                                  <option value="Thu" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "Thu")): ?> selected <?php endif; ?>>Thu</option>
                                  <option value="Fri" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "Fri")): ?> selected <?php endif; ?>>Fri</option>
                                  <option value="Sat" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "Sat")): ?> selected <?php endif; ?>>Sat</option>
                                  <option value="Sun" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "Sun")): ?> selected <?php endif; ?>>Sun</option>
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
                                <option value="1" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "1")): ?> selected <?php endif; ?>>1</option>
                                <option value="2" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "2")): ?> selected <?php endif; ?>>2</option>
                                <option value="3" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "3")): ?> selected <?php endif; ?>>3</option>
                                <option value="4" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "4")): ?> selected <?php endif; ?>>4</option>
                                <option value="5" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "5")): ?> selected <?php endif; ?>>5</option>
                                <option value="6" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "6")): ?> selected <?php endif; ?>>6</option>
                                <option value="7" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "7")): ?> selected <?php endif; ?>>7</option>
                                <option value="8" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "8")): ?> selected <?php endif; ?>>8</option>
                                <option value="9" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "9")): ?> selected <?php endif; ?>>9</option>
                                <option value="10" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "10")): ?> selected <?php endif; ?>>10</option>
                                <option value="11" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "11")): ?> selected <?php endif; ?>>11</option>
                                <option value="12" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "12")): ?> selected <?php endif; ?>>12</option>
                                <option value="13" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "13")): ?> selected <?php endif; ?>>13</option>
                                <option value="14" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "14")): ?> selected <?php endif; ?>>14</option>
                                <option value="15" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "15")): ?> selected <?php endif; ?>>15</option>
                                <option value="16" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "16")): ?> selected <?php endif; ?>>16</option>
                                <option value="17" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "17")): ?> selected <?php endif; ?>>17</option>
                                <option value="18" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "18")): ?> selected <?php endif; ?>>18</option>
                                <option value="19" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "19")): ?> selected <?php endif; ?>>19</option>
                                <option value="20" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "20")): ?> selected <?php endif; ?>>20</option>
                                <option value="21" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "21")): ?> selected <?php endif; ?>>21</option>
                                <option value="22" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "22")): ?> selected <?php endif; ?>>22</option>
                                <option value="23" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "23")): ?> selected <?php endif; ?>>23</option>
                                <option value="24" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "24")): ?> selected <?php endif; ?>>24</option>
                                <option value="25" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "25")): ?> selected <?php endif; ?>>25</option>
                                <option value="26" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "26")): ?> selected <?php endif; ?>>26</option>
                                <option value="27" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "27")): ?> selected <?php endif; ?>>27</option>
                                <option value="28" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "28")): ?> selected <?php endif; ?>>28</option>
                                <option value="29" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "29")): ?> selected <?php endif; ?>>29</option>
                                <option value="30" <?php if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == "30")): ?> selected <?php endif; ?>>30</option>
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
    $('input[type="radio"][name="milestones-Send-mail"]').click(function() {
      var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'daily') {
         mainDiv.find('.showTimeBox').show();           
       }
       else {
         mainDiv.find('.showTimeBox').hide();   
       }
   });
   
     $('input[type="radio"][name="milestones-Send-mail"]').click(function() {
      var mainDiv = $(this).closest('.habitStep');
        if($(this).attr('class') == 'weekly') {
         mainDiv.find('.showDayBox').show();           
       }
       else {
         mainDiv.find('.showDayBox').hide();   
       }
   });
   
      $('input[type="radio"][name="milestones-Send-mail"]').click(function() {
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
               
                  if(data.data['milestones-Send-mail'] == "daily"){
                     $(".showTimeBox").show();
                     $("#daily_time_milestones").val(data.data['Send_mail_milestones_time']);
                  }else if(data.data['milestones-Send-mail'] == "weekly"){
                     $(".showDayBox").show();
                     $("#weekly_day_milestones").val(data.data['Send_mail_milestones_time']);
                  }else if(data.data['milestones-Send-mail'] == "monthly"){
                     $(".showMonthBox").show();
                     $("#month_date_milestones").val(data.data['Send_mail_milestones_time']);
                  }
               
               }

              if(data.data && data.data['milestones-Send-epichq'] != undefined ){
                 $("input[type=radio][value='"+data.data['milestones-Send-epichq']+"']").attr("checked",true);
              }
            }
       });
    }
</script><?php /**PATH /Users/mahendra/Documents/projects/epicfitlaravelv6/resources/views/Result/partials/goal-steps/custom/step15.blade.php ENDPATH**/ ?>