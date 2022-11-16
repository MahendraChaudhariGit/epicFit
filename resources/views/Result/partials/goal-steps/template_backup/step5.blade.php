<div class="step data-step" data-step="5">
                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">

                        <div class="watermark1" data-id="4"><span>4.</span></div>
                        <label class="steps-head">Is this <strong>EPIC</strong> Goal an <strong>immediate priority</strong> for you?</label>
                      </div> 
                      <div class="tooltip-sign mb-10">

                       <a href="javascript:void(0)" class="goal-step" 
                           data-message="<b>Yes</b>—Goal is particularly important and critical to my happiness and confidence
                           <br/><br/>
                           <b>No</b>—this is a basic Goal and there may be more important Goals for me to address
                           <br/><br/>
                           Are there immediate negative effects that may arise as a direct result of putting this goal<br/>
                           off? And are you willing to prioritise this goal and associated tasks over other tasks and life events?
                            <br/><br/>
                           Example: <br/>
                           Are there any immediate health issues to be addressed?<br/>
                           Is there mounting stress as a result of failing to address an issue? <br/>
                           Is a lack of results affecting your motivation and or confidence levels? <br/>
                           Are poor choices affecting you in anyway financially?
                            <br/><br/>
                           Example of tasks that may need to be implemented into your lifestyle:<br/>
                           Are you willing to sacrifice 2 coffees per week to pay for an extra training session? <br/>
                           Are you willing to sacrifice time spent with loved ones or friends who currently have a negative impact on your lifestyle?
                            <br/><br/>
                           <span style='color:#f64c1e;'><b>NOTE:</b> (maximum 3) must be removed</span>"
                           data-message1="Yes—this <span style='color:#f64c1e;'> <b>EPIC</b> Goal </span> is particularly important and critical to my happiness and confidence.
                           <br/><br/>
                           No—this is a basic Goal and there may be a more important <span style='color:#f64c1e;'> <b>EPIC</b> Goal </span> for me to address.
                           <br/><br/>
                           Are there immediate negative effects that may arise as a direct result of putting this goal
                           off? And are you willing to prioritise this <span style='color:#f64c1e;'> <b>EPIC</b> Goal </span> and associated tasks over other tasks and life events?
                           <br/><br/>
                           <b>Example:</b><br/>
                           Are there any immediate health issues to be addressed?<br/>
                           Is there mounting stress as a result of failing to address an issue? <br/>
                           Is a lack of results affecting your motivation and or confidence levels? <br/>
                           Are poor choices affecting you in anyway financially?
                           <br/><br/>
                           Example of tasks that may need to be implemented into your lifestyle:<br/>
                           Are you willing to sacrifice 2 coffees per week to pay for an extra training session? <br/>
                           Are you willing to sacrifice time spent with loved ones or friends who currently have a negative impact on your lifestyle?
                           "><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>
                       
                     <div class="form-group">
                        <label class="container_radio version_2">No
                        <input type="radio" name="goal" value="No" data-toggle="modal" data-target="#temp-modal">
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">Yes
                        <input type="radio" name="goal" value="Yes">
                        <span class="checkmark"></span>
                        </label>
                     </div>
                  </div>

<script type="text/javascript">
   
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
            if(data.data && data.data.goal != undefined ){
               $("input[type=radio][value='"+data.data.goal+"']").attr("checked",true);
            }
        }
   });
}
</script>