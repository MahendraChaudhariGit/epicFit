@extends('Result.profile_details')
@section('required-styles')
<style>
.modal-popup .btn-default {
    color: #fff;
    background-color: #f94211 !important;
    border-color: #f94211 !important;
}
.modal-footer {
    padding: 15px;
    text-align: center;
    border-top: 1px solid #e5e5e5;
}
</style>
@stop
@section('content')
<input id="m-selected-step" type="hidden" value="{{$id}}">
<input id="selected-step" type="hidden" value="{{$id}}">
<form name="formPQ" action="#" role="form" class="smart-wizard" id="wizard-form" data-form-mode="create"><!--form-horizontal-->


    {!! Form::token() !!}
    <input id="client_id" type="hidden" name="client_id" value="{{$parq->client_id}}">
    <input id="client_gender" type="hidden" name="client-gender" value="{{$parq->gender}}">
    <input type="hidden" name="step_status" value="{{$parq->parq1}}, {{$parq->parq2}}, {{$parq->parq3}}, {{$parq->parq4}}, {{$parq->parq5}}" data-parq1="{{$parq->parq1}}" data-parq2="{{$parq->parq2}}" data-parq3="{{$parq->parq3}}" data-parq4="{{$parq->parq4}}" data-parq5="{{$parq->parq5}}">
    <map name="Map" id="Map"></map>
    <span class="inmotion-total-slides hidden-xs">QUESTIONS<br>
      <span class="inmotion-ts-active-num question-step">00</span>
      <span class="inmotion-ts-active-separator">/</span>
      <span class="inmotion-ts-active-all all-question-step">10</span>
  </span>

  <span class="qodef-grid-line-right">
      <span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:-450, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">

      </span>
  </span>
  <div class="container-fluid">
      <div class="watermark"><p>EPIC PROCESS</p></div>
      <div class="row row-height">
          <div class="col-xl-6 col-lg-6 col-md-5 col-xs-11 content-left">
            <img src="{{asset('assets/images/logo-epic.png')}}" alt="" class="img-fluid logo-img">
            <div class="content-left-wrapper">
               <img src="{{asset('result/images/step-two.jpg')}}" alt="" class="img-fluid">

           </div>
           <img id="pot" src="{{asset('assets/images/h1-slider-img-1.png')}}" alt="" class="img-fluid">
           <!-- /content-left-wrapper -->
       </div>
       <!-- /content-left -->
       <div class="col-xl-6 col-lg-5 col-md-5 col-xs-12 content-right" id="start">
        <div id="wizard_container">
            <div id="top-wizard">
             <h2 class="steps-name wizard-header">EXERCISE PREFERENCE</h2>
                      <!--   <span id="location"></span>
                        <div id="progressbar"></div> -->
                    </div>
                    <!-- /top-wizard -->
                    <form id="wrapped" method="post" enctype="multipart/form-data">
                        <input id="website" name="website" type="text" value="">
                        <!-- Leave for security protection, read docs for details -->
                        <div id="middle-wizard">
                            <div class="step">
                                <div class="row">

                                  <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

                              </span>
                              <div class="heading-text border-head mb-10">
                                  <div class="watermark1"><span>0.</span></div>
                                  <label class="steps-head">What physical activity do you or have you done in the past, including occupation? </label>
                              </div>
                              <div class="tooltip-sign mb-10">
                                  <a href="javascript:void(0)" class="parq-step2" data-message="It is important for us to know whether or not you are involved in physical activity at present or have done any in the past. This indicates whether you have a healthy physical activity habit or if you will need help in forming a new habit. 
                                  <br/><br/>
                                  Often individuals are unsure of what type of activity they do and are unaware that their job provides a certain degree of physical activity.  Builders and warehouse workers for example are physically active almost all of their working day, compared to an accountant who may be sedentary or inactive most of their day. 
                                   <br/><br/>
                                  Types of physical activity will relate to either:
                                   <br/><br/>
                                  <b>Cardiovascular Activity</b>—Cardio exercises utilise large muscle movements to elevate your heart rate to at least 50% of its maximum over a sustained period of time. Your cardiovascular system includes your heart, lungs, blood, and blood vessels. As stronger cardiovascular system results in more oxygen being delivered to cells within your muscles, enabling you to sustain activity for longer, as well as burning more fat both in activity and at rest. Examples of cardio exercises include walking, running, swimming, cycling, circuit training, and rowing.
                                   <br/><br/>
                                  <b>Resistance Training</b>— Resistance involves any activity or exercise that causes your muscles to contract against any form of external resistance. The external resistance may be in the form of weights such as dumbbells, machine weights or other gym equipment, but often comes in the form of resistance encountered in work environments such as lifting heavy boxes, moving furniture, laying bricks, or building etc. 
                                   <br/><br/>
                                  Knowing what you have done in the past may indicate an <span style='color: #f94211'><b>EPIC</b> Goal</span> to work towards, maybe being involved in that activity again. Often individuals, especially parents, stop doing sport or activities they enjoy because they feel they do not have the time, the ability, or the experience/strength anymore. 
                                   <br/><br/>
                                  These factors can all change with time. This answer is also a clear indication to your <span style='color: #f94211'><b>EPIC</b> Trainer</span> whether you are doing too much or too little at present.
                                   <br/><br/>
                                  If active at work, please provide a detailed explanation of what you do and specify if its more cardiovascular or resistance focussed
                                   <br/><br/>
                                  If you are unsure of which categories to select, please mention this in the notes section provided at the bottom of this page. "><i class="fa fa-question-circle question-mark"></i></a>

                              </div>

                          </div>

                          <div class="form-group">
                            <label class="container_radio version_2">none
                                <input type="radio" name="activity" value="none" {{isset($parq) && $parq->activity == 'none' ? 'checked' : ''}}>
                                <span class="checkmark"></span>
                            </label>
                            <label class="container_radio version_2">cardio
                                <input type="radio" name="activity" value="cardio" {{isset($parq) && $parq->activity == 'cardio' ? 'checked' : ''}}>
                                <span class="checkmark"></span>
                            </label>
                            <label class="container_radio version_2">resistance
                                <input type="radio" name="activity" value="resistance"{{isset($parq) && $parq->activity == 'resistance' ? 'checked' : ''}}>
                                <span class="checkmark"></span>
                            </label>
                            <label class="container_radio version_2">Cardio & Resistance
                                <input type="radio" name="activity" value="resistancecardio"{{isset($parq) && $parq->activity == 'resistancecardio' ? 'checked' : ''}}>
                                <span class="checkmark"></span>
                            </label>

                            <label class="container_radio version_2">Other
                                <input type="radio" name="activity" value="other"{{isset($parq) && $parq->activity == 'other' ? 'checked' : ''}}>
                                <span class="checkmark"></span>
                            </label>

                        </div>
                    </div>
                    <!-- /step-->

                    <div class="step">
                        <div class="row">

                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

                      </span>
                      <div class="heading-text border-head mb-10">
                          <div class="watermark1"><span>1.</span></div>
                          <label class="steps-head"> Please provide physical <b>activity details</b> including <b>physical activities</b> at work  </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                          <a href="javascript:void(0)" class="parq-step2" data-message="Provide a brief overview of your weekly physical activities you are currently or have recently been involved with as this sheds light on what particular activity you do on a regular basis. 
                           <br/><br/>
                          Also list any physical activities you may do at work. Listing the actual activities, you are involved in is especially important so we can get an idea of what you have selected to do. 
                           <br/><br/>
                          What activities you enjoy and have dedicated time to. Knowing the activities, you do at work help us decide what additional activities are required to ensure you are strong, balanced, and able to perform all the activities you would like to and need to.
                           <br/><br/>
                          Your details may include a brief overview of the type and frequency of training you do at home or in the gym, as well as the types of daily activities you are required to complete at work which are likely to have resistance and cardio demands. "><i class="fa fa-question-circle question-mark"></i></a>

                      </div>

                  </div>

                  <div class="form-group">
                     <textarea rows="3" id="activityOther" name="activityOther" placeholder="" class="form-control">{{ isset($parq)?$parq->activityOther : null }}</textarea>
                 </div>
             </div>

             <!-- /Start Branch ============================== -->
             <div class="step">
                 <div class="row">

                  <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

              </span>
              <div class="heading-text border-head mb-10">
                  <div class="watermark1"><span>2.</span></div>
                  <label class="steps-head">How often are you <b>physically active per week</b>(Current average)? </label>
              </div>
              <div class="tooltip-sign mb-10">
               <a href="javascript:void(0)" class="parq-step2" data-message="The frequency of your current physical activity provides an insight into the health of your current physical activity regime and also your availability and commitment levels. We can tell how often you consider yourself to be physically active per week and ensure it correlates with the actual frequency once analysed. 
                <br/><br/>
               Often there is a discrepancy as individuals do not consider recreational activities like golf, daily gardening & land work to be activities because they have either done them for so long or they do not seem to provide a workout as such. 
                <br/><br/>
               If you are active too much, or too little every week, this can be a deciding factor as to what steps to take next, regarding introducing new activity.
                <br/><br/>
               Please select the number of times per week you are physically active, do not consider the duration of activities. If you go for a walk twice per week and complete resistance training at a gym once you would select 3 times a week. If you are required to undertake heavy lifting at work every day from Monday to Friday, you would select 5 times a week. "><i class="fa fa-question-circle question-mark"></i></a>

           </div>

       </div>

       <div class="form-group">
        <label class="container_radio version_2">1
            <input type="radio" name="frequency" value="1" {{isset($parq) && $parq->frequency == '1' ? 'checked' : ''}}>
            <span class="checkmark"></span>
        </label>
        <label class="container_radio version_2">2
            <input type="radio" name="frequency" value="2" {{isset($parq) && $parq->frequency == '2' ? 'checked' : ''}}>
            <span class="checkmark"></span>
        </label>
        <label class="container_radio version_2">3-5
            <input type="radio" name="frequency" value="3-5" {{isset($parq) && $parq->frequency == '3-5' ? 'checked' : ''}}>
            <span class="checkmark"></span>
        </label>
        <label class="container_radio version_2">6-7
            <input type="radio" name="frequency" value="6-7" {{isset($parq) && $parq->frequency == '6-7' ? 'checked' : ''}}>
            <span class="checkmark"></span>
        </label>
        <label class="container_radio version_2">Twice daily vigorous
            <input type="radio" name="frequency" value="Twice-daily-vigorous" {{isset($parq) && $parq->frequency == 'Twice-daily-vigorous' ? 'checked' : ''}}>
            <span class="checkmark"></span>
        </label>
    </div>
</div>

<!-- /Work Availability > Full-time ============================== -->
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>3.</span></div>
      <label class="steps-head">Currently what is your total duration of physical activity <b>PER WEEK</b> (Current average)?  </label>
  </div>
  <div class="tooltip-sign mb-10">
    <a href="javascript:void(0)" class="parq-step2" data-message="Current duration is especially important as this has a risk factor associated with it. An <span style='color: #f94211'><b>EPIC</b> Trainer</span> needs to ascertain how much time you spend, or are willing to spend on your physical activity, allowing us to program according to these guidelines.  
         <br/><br/>
        This question leads on from frequency. This can help the <span style='color: #f94211'><b>EPIC</b> Trainer</span> identify whether you are active enough during the week, too little or too much. 
         <br/><br/>
        This will directly affect the planned change in physical activity moving forward. It is also a great indicator of how you use your current time for physical activity or other things to see how we can fit in physical activity.
         <br/><br/>
        Please select the total duration in minutes most relevant to the amount of time you are active in total each week. 
         <br/><br/>
        <b>Example:</b> if you walk for 1 hour once per week, and swim for 30 minutes once per week, your total average will be 90 mins. 
         <br/><br/>
        If you feel you are physically active for hours at work, be sure to take this into account."><i class="fa fa-question-circle question-mark"></i></a>

    </div>

</div>
<div class="form-group">
    <label class="container_radio version_2">30 min
        <input type="radio" name="paPerWeek" value="30 min" {{isset($parq) && $parq->paPerWeek == '30 min' ? 'checked' : ''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">60 min
        <input type="radio" name="paPerWeek" value="60 min" {{isset($parq) && $parq->paPerWeek == '60 min' ? 'checked' : ''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">90 min
        <input type="radio" name="paPerWeek" value="90 min" {{isset($parq) && $parq->paPerWeek == '90 min' ? 'checked' : ''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">120-150 min
        <input type="radio" name="paPerWeek" value="120-150 min" {{isset($parq) && $parq->paPerWeek == '120-150 min' ? 'checked' : ''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">150 min +
        <input type="radio" name="paPerWeek" value="150 min +" {{isset($parq) && $parq->paPerWeek == '150 min' ? 'checked' : ''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<!-- /step-->

<!-- /Work Availability > Part-time ============================== -->
<div class="step">
 <div class="row">

  <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

</span>
<div class="heading-text border-head mb-10">
  <div class="watermark1"><span>4.</span></div>
  <label class="steps-head">What <b>intensity</b> is your current physical activity (Current average)?  </label>
</div>
<div class="tooltip-sign mb-10">
  <a href="javascript:void(0)" class="parq-step2" data-message="Your intensity will indicate how hard you exert yourself at present, in terms of physical activity. 
   <br/><br/>
  It is important to know what intensity level you are comfortable performing your chosen physical activities at. 
   <br/><br/>
  As with anything new, you may have a change in intensity, and we need to know what you believe your current intensity is then link this with your ability and determine what intensity you can safely begin new activity with. 
   <br/><br/>
  If a recreational runner runs for 120 hrs a week, at a vigorous intensity, they may have to begin a resistance training program at a moderate intensity, as this form of activity is new with new demands both physically and mentally. Always start at the beginning.
   <br/><br/>
  The more time you are willing to commit to training, the less intense your training will be. The less time you dedicate to training results in more intense training, dependant on your specific goals.
   <br/><br/>
  Your intensity refers to how much energy is expended when exercising or partaking in any form of physical activity. Exercise activity is measure in metabolic equivalents or METs. One MET is defined as the energy it takes to sit quietly or be completely at rest. For the average adult, the energy expended at rest is approximately one calorie per every 2.2pounds (1kg) of body weight per hour. 
   <br/><br/>
  <b>Sedentary</b>—A predominantly sedentary lifestyle involves spending the majority of your time seated, somewhat inactive, and close to being at a completely physically at rest state. This can include being seated for an office-based occupation, lying down watching tv or reading.
   <br/><br/>
  <b>Light</b>—Light physical activities include activities that do not cause you to break a sweat or result in any shortness of breath. Example: A leisurely walk whilst being able to maintain a conversation throughout. 
   <br/><br/>
  <b>Moderate</b>— Moderate intensity activities require you to get moving fast enough, or strenuous enough to burn off three to six times as much energy per minute as you do when sitting or at rest. Examples include a brisk walk or jog, recreational sport, cycling flat ground with moderate resistance. 
   <br/><br/>
  <b>Vigorous</b>—Vigorous activity requires you to expend more than six times the energy you would when seated or at rest. Examples include running, competitive sport, continual heavy lifting at work. 
   <br/><br/>
  One limitation of measuring in metabolic equivalents is that it does not accurately consider your current level of fitness, for example: a brisk walk may be easy for a marathon runner, but difficult for someone who has a sedentary lifestyle.
   <br/><br/>
  If you are unsure of your current intensity, you can base your answer off of your rate of perceived exertion or RPE.  Your RPE is a subjective measure based on the increase in your heart rate, and physical exertion through muscle fatigue, respiratory rate, and sweating. 
  <br/><br/>
  <b>0</b> – Nothing at all (little to no exertion)<br/>
  <b>0.5</b> – Just noticeable<br/>
  <b>1</b> – Very light<br/>
  <b>2</b> – Light<br/>
  <b>3</b> – Moderate<br/>
  <b>4</b> – Somewhat heavy<br/>
  <b>5- 7</b>– Heavy<br/>
  <b>8-9</b> – Very heavy (vigorous)<br/>
  <b>10</b> – Very, very heavy 
   <br/><br/>
  Your <span style='color: #f94211'><b>EPIC</b> Trainer</span> will help you to understand exercise intensity in more detail as this is critical to creating your perfect physical activity routine. "><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>

<?php
if(!count($parq->intensity)) {
    $parq->intensity = [];
}
?>

<div class="form-group">
    <label class="container_radio version_2">Sedentary
        <input type="checkbox" name="intensity" value="sedentary" {{ in_array('sedentary', $parq->intensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">Light
        <input type="checkbox" name="intensity" value="light" {{ in_array('light', $parq->intensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">Moderate
        <input type="checkbox" name="intensity" value="moderate" {{ in_array('moderate', $parq->intensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">Vigorous
        <input type="checkbox" name="intensity" value="vigorous" {{ in_array('vigorous', $parq->intensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">Twice daily vigorous
        <input type="radio" name="intensity" value="Twice-daily-vigorous" {{ in_array('Twice-daily-vigorous', $parq->intensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<!-- /step-->

<!-- /Work Availability > Freelance-Contract ============================== -->
<div class="step">
    <?php
    if(!count($parq->paSession)) {
        $parq->paSession = [];
    }
    ?>
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>5.</span></div>
      <label class="steps-head">What is your preferred duration of physical activity <b>PER SESSION</b>? </label>
  </div>
  <div class="tooltip-sign mb-10">
    <a href="javascript:void(0)" class="parq-step2" data-message="This indicates the time you are willing to make available for training. 
     <br/><br/>
    The more time you are willing to commit to training, the less intense your training may need to be. The less time you dedicate to training may lead to more intense training, dependant on your specific goals. 
     <br/><br/>
    Training at a higher intensity requires a shorter session duration as you can expend the equivalent amount of energy in 30 minutes whilst training at a moderate or vigorous intensity, as you would in a light 60-minute session. 
     <br/><br/>
    The duration of your physical activity is dependent on your current ability, the time you have available, the intensity at which you train, and the <span style='color: #f94211'><b>EPIC</b> RESULT</span> you need. 
     <br/><br/>
    Some individuals prefer to train for longer, but life circumstances dictate they do not have that option and vice versa. 
     <br/><br/>
    If we know your preference, irrespective of any current circumstances, we can work on juggling all aspects of life to allow you that time that you prefer to engage in the activities you want. 
     <br/><br/>
    When answering this question, consider your current fitness level, your availability, and your preferred intensity level. 
     <br/><br/>
    Life is about making time for the things you enjoy."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <label class="container_radio version_2">30 min
        <input type="checkbox" name="paSession" value="30 min" {{ in_array('30 min', $parq->paSession)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">60 min
        <input type="checkbox" name="paSession" value="60 min" {{ in_array('60 min', $parq->paSession)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">90 min
        <input type="checkbox" name="paSession" value="90 min" {{ in_array('90 min', $parq->paSession)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">120-150 min
        <input type="checkbox" name="paSession" value="120-150 min"{{ in_array('120-150 min', $parq->paSession)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">150 min +
        <input type="checkbox" name="paSession" value="150 min +" {{ in_array('150 min +', $parq->paSession)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step">
    <?php
    if(!count($parq->paIntensity)) {
        $parq->paIntensity = [];
    }
    ?>
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>6.</span></div>
      <label class="steps-head">What is your <b>preferred intensity</b> of physical activity? </label>
  </div>
  <div class="tooltip-sign mb-10">
     <a href="javascript:void(0)" class="parq-step2" data-message="Use the information you have provide related to current intensity and duration you would like to do physical activity for. 
      <br/><br/>
     It is necessary to know what particular physical activities you enjoy doing and the intensity thereof. 
      <br/><br/>
     Some individuals want to be pushed hard in a workout class; others want to go for a long, easy walk. 
      <br/><br/>
     If we know these details, we can plan your Lifestyle Design Program to work around these preferences. 
      <br/><br/>
     Sometimes the reason you may prefer a certain intensity is because you feel that intensity gives a better <span style='color: #f94211'>RESULT</span>. If this is incorrect, we can educate you on how to improve your <span style='color: #f94211'>RESULT</span> with the right intensity. 
      <br/><br/>
     Education is empowering and helps you understand why you have to change and the best way to, which in turns helps motivate you to change.
      <br/><br/>
     Remember your preferred intensity level is relevant to your ability and level of fitness. A slow jog for you now at your current fitness level may fall under vigorous, as your fitness improves this may fall back to moderate or light. 
      <br/><br/>
     Think about your RPE and the way you like to feel during physical activity. Your preferred intensity provides an insight into your current level of motivation and will help you <span style='color: #f94211'><b>EPIC</b> Trainer</span> develop the perfect program for you. "><i class="fa fa-question-circle question-mark"></i></a>

 </div>

</div>
<div class="form-group">
    <label class="container_radio version_2">Sedentary
        <input type="radio" name="paIntensity" value="sedentary" {{ in_array('sedentary', $parq->paIntensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">Light
        <input type="radio" name="paIntensity" value="light" {{ in_array('light', $parq->paIntensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">Moderate
        <input type="radio" name="paIntensity" value="moderate" {{ in_array('moderate', $parq->paIntensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">Vigorous
        <input type="radio" name="paIntensity" value="vigorous" {{ in_array('vigorous', $parq->paIntensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">Twice daily vigorous
        <input type="radio" name="paIntensity" value="Twice-daily-vigorous"{{ in_array('Twice-daily-vigorous', $parq->paIntensity)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>7.</span></div>
      <label class="steps-head">What <b>physical activities</b> do you personally <b>enjoy</b> and why? </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step2" data-message="t is necessary to know what particular physical activities you enjoy so we can include them in your program along with other required functional movement patterns as we do not believe in prescribing physical activities you do not enjoy. 
    <br/><br/>
   If we know what movements and activities, you enjoy we can be sure to include these in your weekly schedule. 
    <br/><br/>
   All your activities should complement one another and improve your overall ability and health & wellness in general. 
    <br/><br/>
   You may be required to do some activities that are not your favourite but with the understanding of why, provided by your <span style='color: #f94211'><b>EPIC</b> Trainer</span>, you will be more inclined to want to do them knowing the results are all for you.
    <br/><br/>
   When starting your journey, choosing exercises that align with your preferences may be the deciding factor determining whether you commit to your program and achieve your desired result. 
    <br/><br/>
   Please indicate any exercises you enjoy so your EPIC Trainer can incorporate these into your routine, helping you to get started and stay motivated. 
    <br/><br/>
   If you enjoy any activities that you wish to include within your program, that require equipment you may not have access to please indicate this in the notes section provided. Our EPIC Team has the resources available to find your nearest facility, as well as offering affordable equipment you can purchase or hire."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>

<div class="form-group upload-group">
    <textarea rows="3" id="paEnjoy" name="paEnjoy" placeholder="" class="form-control" required>{{ isset($parq)?$parq->paEnjoy : null }}</textarea>
</div>
</div>
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>8.</span></div>
      <label class="steps-head">What <b>physical activities</b> do you <b>not enjoy</b> and why? </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step2" data-message="We do not believe in prescribing physical activities you do not enjoy as this will directly affect your motivation and willingness to adhere to the prescribed program.
       <br/><br/>
      Many people are hesitant to train because they have been forced to perform exercises they do not enjoy doing, or movements that cause discomfort and/or injury. 
       <br/><br/>
      If we know what you do not enjoy and why, we can work around this. If you dislike certain activities because of your ability, or perceived ability, we can work on changing this. Running, for example, is often an activity most people want to do but cannot because they feel too breathless, or their knees give them grief afterwards. 
       <br/><br/>
      If we can improve your cardiovascular fitness and show you how to reduce, or limit, knee pain, running may become an activity you can do. 
       <br/><br/>
      If the reason you do not do an activity is related to an injury, we need to focus on this first before anything else. 
       <br/><br/>
      Often movements like push ups can cause shoulder pain/injury when done incorrectly or when done by someone with limited range of motion. If we work on a mobility and flexibility program for your shoulders and posture, this exercise may be greatly beneficial for you and necessary to ensure you reach the <span style='color: #f94211'>RESULT</span> you want.
       <br/><br/>
      Please indicate any exercised you do not enjoy, it is crucial that you also indicate why you do not enjoy these particular activities as your <span style='color: #f94211'><b>EPIC</b> Trainer</span> may avoid including them in your program, or work towards improving your ability and understanding of the exercises. 
       <br/><br/>
      For example, if you dislike running due knee discomfort, your <span style='color: #f94211'><b>EPIC</b> Trainer</span> will be sure to address the cause behind the discomfort you feel, improving any injury or imbalance resulting in your ability to run with little to no discomfort. "><i class="fa fa-question-circle question-mark"></i></a>

  </div>

</div>
<div class="form-group">
    <textarea rows="3" id="paEnjoyNo" name="paEnjoyNo" placeholder="" class="form-control" required>{{ isset($parq)?$parq->paEnjoyNo : null }}</textarea>
</div>
</div>
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>9.</span></div>
      <label class="steps-head">What <b>days and times</b> day do you prefer to train?</label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step2" data-message="Knowing your availability and preferred training times allows us to customise your programs duration and training times to your work and life schedule. 
    <br/><br/>
   It is also important to know whether you prefer to train in the morning or the afternoon, as this differs from client to client and may affect bot your effort and <span style='color: #f94211'>RESULT</span>. 
    <br/><br/>
   Please indicate your preferred times and days here, not times you are being dictated by. If we know what you want, we can slowly work towards getting it done. It will take time and patience but for your health & happiness, is there another option?
    <br/><br/>
   Please select your preferred training days and time of day. It is crucial that you commit to times that fit into your current lifestyle and work schedule to ensure you are able to adhere to your program. 
    <br/><br/>
   Your level of motivation and availability throughout the week will determine your success."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group preferDayTime">
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <div class="form-group">
                <label class="">Monday
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">AM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysMonAm" value="am" type="checkbox" data-day="Mon">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">PM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysMonPm" value="pm" type="checkbox" data-day="Mon">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <div class="form-group">
                <label class="">Tuesday
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">AM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysTueAm" value="am" type="checkbox" data-day="Tue">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">PM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysTuePm" value="pm" type="checkbox" data-day="Tue">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <div class="form-group">
                <label class="">Wednessday
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">AM

                    <input class="preferredTraingDays hidden" id="preferredTraingDaysWedAm" value="am" type="checkbox" data-day="Wed">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">PM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysWedPm" value="pm" type="checkbox" data-day="Wed">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <div class="form-group">
                <label class="">Thursday
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">AM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysThuAm" value="am" type="checkbox" data-day="Thu">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">PM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysThuPm" value="pm" type="checkbox" data-day="Thu">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <div class="form-group">
                <label class="">Friday
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">AM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysFriAm" value="am" type="checkbox" data-day="Fri">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">PM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysFriPm" value="pm" type="checkbox" data-day="Fri">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <div class="form-group">
                <label class="">Saturday
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">AM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysSatAm" value="am" type="checkbox" data-day="Sat">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">PM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysSatPm" value="pm" type="checkbox" data-day="Sat">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <div class="form-group">
                <label class="">Sunday
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">AM

                    <input class="preferredTraingDays hidden" id="preferredTraingDaysSunAm" value="am" type="checkbox" data-day="Sun">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
        <div class="col-md-2 col-xs-3">
            <div class="form-group prefTrainSlot">
                <label class="container_check">PM
                    <input class="preferredTraingDays hidden" id="preferredTraingDaysSunPm" value="pm" type="checkbox" data-day="Sun">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>

    </div>
    <input type="hidden" class="form-control" id="preferredTraingDays" value="{{ isset($parq)?$parq->preferredTraingDays : null }}" name="preferredTraingDays">
</div>
</div>
<div class="submit step" id="end">
 <div class="row">

  <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

</span>
<div class="heading-text border-head mb-10">
  <div class="watermark1"><span>10.</span></div>
  <label class="steps-head">Please provide any <b>additional notes</b> you think are relevant</label>
</div>
<div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step2" data-message="Is there anything additional we need to know with regards to your exercise preferences? 
    <br/><br/>
   Please share with us any details here that will help us better understand the reason behind why you currently do, or do not do, what you do. Many individuals use exercise as a form of mental wellness and as an outlet for stress or to avoid doing activities they will regret. 
    <br/><br/>
   If you have previously had a binge eating problem and have now turned to healthy living as a way to control what you eat and focus on something else, this information can help us cater for you unique situation in the best way possible for you and your results. 
    <br/><br/>
   Perhaps you have a partner who you are trying to encourage to workout with you for accountability for you as well as them. Share this with us and we can work on a plan together to get them working on their Lifestyle Design Program, without making them feel scared or part of an intervention. 
    <br/><br/>
   If you know your loves ones are healthy, you will feel better, guaranteed.
    <br/><br/>
   Do you wish to receive information regarding equipment hire or purchase?"><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <textarea rows="1" id="epNotes" name="epNotes" placeholder="" class="form-control">{{ isset($parq)?$parq->epNotes : null }}</textarea>
</div>
</div>
</div>
<!-- /middle-wizard -->
<div id="bottom-wizard">
 <span class="qodef-grid-line-center"><span class="inmotion-total-slides">
  <div class="d-flex">
     {{-- <button type="button" class="submit submit-step" data-step-url="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" data-step="1">Submit</button> --}}
      
     <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/PersonalDetails') }}" data-step-no="1" class="step-back">
        <span class="prev-name">PERSONAL DETAIL</span>
    </a>&nbsp;&nbsp;
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/PersonalDetails') }}" data-step-no="1" class="arrow step-back">&#8672;</a>               
    <div class="current-section">Exercise Preference</div>
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" data-step-no="3" class="arrow step-forward">&#8674;</a>&nbsp;&nbsp;
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" data-step-no="3" class="step-forward"><span class="next-name">Injury Profile & Family History</span></a>

    {{-- <a class="prev-name redirect_prev_page" href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/PersonalDetails') }}" data-step-no="1">
        <span>PERSONAL DETAIL &nbsp;&nbsp;</span>
        <span  class="arrow step-back">&#8672;</span> 
    </a>              
    <div class="current-section">Exercise Preference</div>
    <a class="next-name redirect_next_page" href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" data-step-no="3">
        <span  class="arrow step-forward">&#8674;</span>
        <span>&nbsp;&nbsp; Injury Profile & Family History</span>
     </a> --}}

    
</div>

<span class="inmotion-ts-active-num section-step">02</span>
<span class="inmotion-ts-active-separator">/</span>
<span class="inmotion-ts-active-all all-section-step">05</span>
</span>
<span class="inmotion-total-slides visible-xs question-s">QUESTIONS<br>
    <span class="inmotion-ts-active-num question-step">5</span>
    <span class="inmotion-ts-active-separator">/</span>
    <span class="inmotion-ts-active-all">07</span>
</span>
<span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
<div class="row">
    <div class="col-sm-5 col-xs-5">     <button type="button" name="backward" class="backward">Prev</button></div>
    <div class="col-sm-7 col-xs-7">

       <button type="button" name="forward" class="forward">Next</button>
       <button type="button" class="submit submit-step" data-step-url="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" data-step="2">Submit</button>
   </div>
</div>


</div>
<!-- /bottom-wizard -->
</form>
</div>
<!-- /Wizard container -->
</div>
<!-- /content-right-->
</div>
<!-- /row-->

<div id="parq-step2" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content modal-popup">
        <div class="modal-body">
            <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
            <p class="message"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
  </div>

</div>
</div>
</div>
<script>
    $(document).on('click','.parq-step2',function(){
        $(this).attr('data-toggle','modal')
        $(this).attr('data-target','#parq-step2')
        $("#parq-step2").attr('aria-modal',true)
        $("#parq-step2").addClass('in')
        var message = $(this).data('message');
        $("#parq-step2").find('.message').html(message);
    })
</script>
@endsection

@section('required-script')

@stop
