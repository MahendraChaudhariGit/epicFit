<div class="step data-step" data-step="7">
   <div class="row">
    <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
    <div class="heading-text border-head mb-10">
      <div class="watermark1" data-id="7"><span>7.</span></div>

      <label class="steps-head">What happen if you do <strong>not achieve</strong> your <strong>EPIC </strong>Goal? * </label>

    </div>
    <div class="tooltip-sign mb-10">

       <a href="javascript:void(0)" class="goal-step" 
         data-message="Some simple questions to ask yourself:
         <br/><br/>
         • How does maintaining the status quo affect you? <br/>
         • Can you maintain your current lifestyle for the next 5, 10, 15 years and be happy? <br/>
         • Will you be able to perform at an event and in life? <br/>
         • Will you be able to enjoy your holiday/event and feel good/participate?<br/>
         • Is your current situation negatively affecting your loved ones?<br/>
         • Are your loved ones around you willing to put up with your failure to change?"
         data-message1="Questions to ask yourself:<br/>
         How does maintaining the status quo affect you?<br/>
         Can you maintain your current lifestyle for the next 5, 10, 15 years?<br/>
         Are your loved ones around you willing to put up with your failure to change?<br/>
         Is it negatively affecting them?
         "><i class="fa fa-question-circle question-mark"></i></a>
   </div>

  </div>
   <div class="form-group fail_description">
    <div class="outborder">
      <textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="" data-autoresize id="fail-description" name="fail-description" ng-model="fail_description" ng-init="fail_description='{{ isset($goalDetails) ? $goalDetails->gb_fail_description: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_fail_description:null}}</textarea>
    </div>
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
              if(data.data && data.data['fail-description'] != undefined ){
                 $("#fail-description").text(data.data['fail-description']);
              }
          }
     });
  }
</script>