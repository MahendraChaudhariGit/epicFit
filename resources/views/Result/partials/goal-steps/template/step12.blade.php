<div class="step data-step goalDetail" data-step="12">
                    <div class="row">
                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1" data-id="11"><span>11.</span></div>

                        <label class="steps-head"> <strong>Who can view </strong>your <strong>EPIC </strong>Goal?  </label>
                      </div>

                      <div class="tooltip-sign mb-10">
                          <a href="javascript:void(0)" class="goal-step" 
                           data-message="<b>Select Friends</b>—Share details with a select few friends who you believe will be supportive and who will hold you accountable to your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>.
                           <br/><br/>
                           <b>Everyone</b>– Share details of your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> with all friend and family on your Timeline 
                           <br/><br/>
                           <b>Just Me</b>—Only show me details of my <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>
                           <br/><br/>
                           The <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> online platform is designed to allow you to connect and interact with fellow team members and like-minded individuals on the same or similar journey to your own. 
                           <br/><br/>
                           Having others view your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> gives you the opportunity to be given feedback and positive encouragement throughout your journey. 
                           <br/><br/>
                           Allow others to celebrate your success with you!
                            <br/><br/>
                           Accountability is key, fellow <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> members can help hold you accountable when it comes to attending training sessions, staying on target with healthy balanced eating and completing your goal related tasks which relate to your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> progression.
                           <br/><br/>
                           <b>T.E.A.M</b> Together Everyone Achieves More"
                           data-message1="<b>Select Friends—</b>Share details with a select few friends who you believe will be supporting and who will hold you accountable to your Goal.<br/>
                           <b>Everyone–</b> share details and Goal with friend and family <br/>
                           <b>Just Me—</b>only show me details and Goal
                           "><i class="fa fa-question-circle question-mark"></i></a>
                        {{-- </label> --}}
                     
                      </div>

                    </div>
                    <div class="form-group">
                     <label class="container_radio version_2">Select Friends
                     <input type="radio" name="goal_seen" value="Selected friends" {{ isset($goalDetails) && $goalDetails->gb_goal_seen == 'Selected friends'?'checked':'' }}>
                     <span class="checkmark"></span>
                     </label>
                  </div>
                     <div class="form-group">
                        <label class="container_radio version_2">Everyone
                        <input type="radio" name="goal_seen" value="everyone" {{ isset($goalDetails) && $goalDetails->gb_goal_seen == 'everyone'?'checked':'' }}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">Just Me
                        <input type="radio" name="goal_seen" value="Just_me" {{ isset($goalDetails) && $goalDetails->gb_goal_seen == 'Just_Me'?'checked':'' }}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group hidden">
                        <input type="text" class="form-control autocomplete" id="goal_selective_friends" ng-keypress="pressEnter($event)" value="" name="goal_selective_friends" aria-invalid="false">
                     </div>
                  </div>

<script type="text/javascript">

$(document).ready(function() {
   
   loadFormData();
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
function loadFormData(){
  var current_step = $('.step').data('step');
  $.ajax({
     url: public_url + 'goal-buddy/load-form-data',
     type: "POST",
     data: {'current_step': current_step},
     success: function (data) {
          console.log(data);
          if(data.data && data.data.goal_seen != undefined ){

            $("input[type=radio][value='"+data.data.goal_seen+"']").attr("checked",true);

            if (data.data.goal_seen == "Selected friends") {
               
               $("#goal_selective_friends").val(data.data.goal_selective_friends);
               $("#goal_selective_friends").parent().removeClass("hidden");
               $("#goal_selective_friends").amsifySuggestags("refresh");
               
               // if (
               //    $("#all-my-friends").val() != undefined &&
               //    $("#all-my-friends").val() != ""
               // ) {
                  
               //    console.log("selective frields 11th step...");

               //    $("#goal_selective_friends").amsifySuggestags("refresh");
                  
               //    var my_friends = JSON.parse($("#all-my-friends").val());
               //    var options = [];

               //    for (var aaa = 0; aaa < my_friends.length; aaa++) {
               //       options[aaa] = {
               //          tag: my_friends[aaa].name,
               //          value: my_friends[aaa].id,
               //       };
               //    }

               //    $(".autocomplete").amsifySuggestags({
               //    type: "bootstrap",
               //    suggestions: options,
               //    whiteList: true,
               //    });

               // }
            }
         }
      }
  });
}  
$("input[name=goal_seen]").unbind().change(function(){
    var goal_seen = $(this).val();
    console.log(goal_seen);
    if(goal_seen == "Selected friends"){
        $("#goal_selective_friends").val('');
        $("#goal_selective_friends").parent().removeClass('hidden');
        $('#goal_selective_friends').attr('required',true);
        $('#goal_selective_friends').removeAttr("style");
        $('#goal_selective_friends').attr('style','height: 0; width: 0; visibility: hidden; padding: 0; margin: 0; float: right');
        $("#goal_selective_friends-error").attr('style','color:red');
    }else{
        $("#goal_selective_friends").val('');
        $("#goal_selective_friends").parent().addClass('hidden');
        $('#goal_selective_friends').attr('required',false);
        $('#goal_selective_friends').removeAttr("style");
        $('#goal_selective_friends').attr('style','display:none');
        $("#goal_selective_friends-error").html('');
    }
})
</script>                  