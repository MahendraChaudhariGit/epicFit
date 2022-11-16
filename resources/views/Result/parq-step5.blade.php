@extends('Result.profile_details')
@section('required-styles')
<style type="text/css">
#goalModal .radio label,#goalModal .checkbox label{
    padding-left: 10px;
}
#goalModal .radio,#goalModal .checkbox,#goalModal .radio-inline+.radio-inline,#goalModal .checkbox-inline+.checkbox-inline{
    margin-top: 5px;
}
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
@media(max-width: 767px){
    #waiverModal .modal-dialog{
    max-width: 90%;
    }
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
      <span class="inmotion-ts-active-all all-question-step">13</span>
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
         <img src="{{asset('result/images/step-five.jpg')}}" alt="" class="img-fluid">

     </div>
     <img id="pot" src="{{asset('assets/images/h1-slider-img-1.png')}}" alt="" class="img-fluid">
     <!-- /content-left-wrapper -->
 </div>
 <!-- /content-left -->

 <div class="col-xl-6 col-lg-5 col-md-5 col-xs-12 content-right" id="start">
    <div id="wizard_container">
        <div id="top-wizard">
           <h2 class="steps-name wizard-header">GOALS & MOTIVATION</h2>
                   <!--  <span id="location"></span>
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
                              <label class="steps-head">Prioritise the following  <b>fitness components</b> according to your  <b>specific needs</b>Drag and order from top (most important) to bottom (least important)</label>
                          </div>
                          <div class="tooltip-sign mb-10">
                              <a href="javascript:void(0)" class="parq-step5" data-message="Click and hold each tab then drag into position, ordering from 1 to 5 (1 being the top of the list and 5 at the bottom) 
                              <br/><br/>
                              This helps indicate to us which components of fitness are priorities to you. This question is extremely specific and allows you to order your areas of focus accordingly.
                              <br/><br/>
                              Components being:  
                               <br/><br/>
                              <b>Muscular strength</b> – the ability of your muscles to exert force to overcome resistance. Strength is a measure of how much force your muscles can exert, while endurance is the measure of how many times your muscles can repeat a specific exertion of force. Muscular strength includes toning, shaping, building, and getting stronger.
                               <br/><br/>
                              <b>Explosive Power</b>– a combination of strength and speed. Power is the ability to exert maximum force in a quick, explosive burst. The ability to exert maximum muscular contraction instantly in an explosive burst of movements. The two components of power are strength and speed. Explosive power includes jumps, explosive sports actions, and reactive work etc.
                               <br/><br/>
                              <b>Flexibility & Mobility </b>- the range of motion of your joints, surrounding muscles and connective tissue. Flexibility refers to the absolute range of movement in a joint or series of joints and length in muscles that cross the joints to induce a bending movement or motion. Mobility is the ability to move or be moved freely and easily. Flexibility & mobility includes stretching, foam rolling and rehabilitation programs etc.
                               <br/><br/>
                              <b>Cardiovascular Endurance</b> – how efficiently your heart and lungs provide fuel, in the form of blood and oxygen, to your tissues and cells to sustain continuous movement.                       The cardiovascular system, also known as the circulatory system, includes the heart, arteries, veins, capillaries, and blood.  An improved cardiovascular system helps with any aerobic activity. Cardiovascular endurance includes sports, swimming, running etc.
                               <br/><br/>
                              <b>Body Composition</b> – In physical fitness, body composition is used to describe the percentages of fat, bone, water, and muscle in human bodies. Some clients may wish to reduce certain components to create a more balanced & healthier body composition. Body composition includes body fat %, skeletal muscles mass, BMI, bone density, fat mass etc.
                               <br/><br/>
                              Overall, these components are equally as important and interlinked. Aspects of all, are required to create a holistic and successful health and wellness program, however, your priorities will be considered when designing your program while setting your BE SMARTER Goal."><i class="fa fa-question-circle question-mark"></i></a>

                          </div>

                      </div>
                      <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">

                                <div class="dd" id="nestable">
                                    <ol class="dd-list">
                                        <?php
                                        $cLabels = array('goalFitnessComponents0' => 'Body Fat %', 'goalFitnessComponents1' => 'Cardio Endurance', 'goalFitnessComponents2' => 'Flexibility & Mobility', 'goalFitnessComponents3' => 'Muscular Strength', 'goalFitnessComponents4' => 'Explosive Power');
                                        $comps = $parq->goalFitnessComponents ? json_decode($parq->goalFitnessComponents, true) : array(array('id' => 'goalFitnessComponents0'), array('id' => 'goalFitnessComponents1'), array('id' => 'goalFitnessComponents2'), array('id' => 'goalFitnessComponents3'), array('id' => 'goalFitnessComponents4'));
                                        ?>
                                        @foreach($comps as $carr)
                                        <li class="dd-item" data-id="{{ $carr['id'] }}">
                                            <div class="dd-handle">{{ $cLabels[$carr['id']] }}</div>
                                        </li>
                                        @endforeach
                                    </ol>
                                </div>

                                <input type="hidden" name="goalFitnessComponents" value="<?php echo htmlentities($parq->goalFitnessComponents); ?>" id="goalFitnessComponents" class="form-control">

                            </div>
                        </div> 
                    </div>
                </div>
                <!-- /step-->
                        <div class="step">
                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

                  </span>
                  <div class="heading-text border-head mb-10">
                      <div class="watermark1"><span>1.</span></div>
                      <label class="steps-head">Select your specific <b>goals</b></label>
                      </div>
                      <div class="tooltip-sign mb-10">
                         <a href="javascript:void(0)" class="parq-step5" data-message="These can relate to all aspects of your lifestyle, and you need to investigate and select the specific areas you wish to focus on. Once again, these focus areas each have specific aspects which are important for everyone, and they are all interlinked. Indicating which aspects, you feel you need to improve on the most, at this stage of your life, helps us to focus your program on what you need to work on first. 
                          <br/><br/>
                         For example, if you want to improve your performance but your current situation has you feeling physically and mentally drained, we cannot jump straight into an intense training program without addressing other areas of your life first specifically your emotions and mindset related to your mental and physical state.
                          <br/><br/>
                         <b>Heath & Wellness -</b> Health is related to your physical wellbeing and wellness is related to mental and emotional wellbeing
                         <br/><br/>
                         <b>Increased Energy -</b> Low energy levels is a major concern for many people. Lifestyle changes such as activity levels, eating habits, sleep and stress levels can help increase your energy levels
                         <br/><br/>
                         <b>Tone -</b> To tone is to give greater strength or firmness, either to your body as a whole or a specific muscle. 
                         <br/><br/>
                         <b>Injury Recovery -</b> If you have an injury, it is important to rehabilitate/recover from this before progressing with your training.
                         <br/><br/>
                         <b>Improved nutrition -</b> Making small and sustainable changes to your eating & hydration habits.
                         <br/><br/>
                         <b>Lose weight -</b> Reducing your physical weight in kg’s/pounds
                         <br/><br/>
                         <b>Improved Performance -</b> Mostly related to sports people. Improving your performance will allow for a better performance of a specific function. This could be recreational or competitive sport.
                         <br/><br/>
                         <b>Improve Endurance -</b> Mostly related to endurance athletes. Improving endurance is improving the time spent performing a specific function i.e., long distance running, swimming.
                         <br/><br/>
                         <b>Strength & Conditioning -</b> Is extremely specific to building strength & conditioning the body to perform a specific function."><i class="fa fa-question-circle question-mark"></i></a>

                     </div>

                 </div>
                            <?php
                            if(!count($parq->goalHealthWellness))
                                $parq->goalHealthWellness = [];
                            ?>
                            <div class="form-group">
                                <label class="container_check version_2">Health & Wellness
                                    <input type="checkbox" name="goalHealthWellness" value="Health & Wellness"{{in_array('Health & Wellness', $parq->goalHealthWellness)?'checked':''}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="container_check version_2">Increased Energy
                                    <input type="checkbox" name="goalHealthWellness" value="Increased Energy"{{in_array('Increased Energy', $parq->goalHealthWellness)?'checked':''}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="container_check version_2">Tone
                                    <input type="checkbox" name="goalHealthWellness" value="Tone"{{in_array('Tone', $parq->goalHealthWellness)?'checked':''}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="container_check version_2">Injury Recovery
                                    <input type="checkbox" name="goalHealthWellness" value="Injury Recovery"{{in_array('Injury Recovery', $parq->goalHealthWellness)?'checked':''}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="container_check version_2">Improved Nutrition
                                    <input type="checkbox" name="goalHealthWellness" value="Improved Nutrition"{{in_array('Improved Nutrition', $parq->goalHealthWellness)?'checked':''}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="container_check version_2">Lose Weight
                                    <input type="checkbox" name="goalHealthWellness" value="Lose Weight"{{in_array('Lose Weight', $parq->goalHealthWellness)?'checked':''}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="container_check version_2">Improved Performance
                                    <input type="checkbox" name="goalHealthWellness" value="Improved Performance"{{in_array('Improved Performance', $parq->goalHealthWellness)?'checked':''}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="container_check version_2">Improved Endurance
                                    <input type="checkbox" name="goalHealthWellness" value="Improved Endurance"{{in_array('Improved Endurance', $parq->goalHealthWellness)?'checked':''}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="container_check version_2">Improved Strength & Conditioning
                                    <input type="checkbox" name="goalHealthWellness" value="Improved Strength & Conditioning"{{in_array('Improved Strength & Conditioning', $parq->goalHealthWellness)?'checked':''}}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            {{-- <input type="hidden"  name="goalHealthWellness" value="" placeholder="" class="form-control mb"> --}}
                        </div>

                        <!-- /Start Branch ============================== -->
            <div class="step">
                <div class="row">

                  <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

              </span>
              <div class="heading-text border-head mb-10">
                  <div class="watermark1"><span>2.</span></div>
                  <label class="steps-head">Please indicate <b>areas</b> you would like to <b>strengthen, tone, rehabilitate & increase flexibility and/or mobility</b></label>
                  </div>
                  <div class="tooltip-sign mb-10">
                      <a href="javascript:void(0)" class="parq-step5" data-message="This section allows you to point out any specific parts of the body you wish to improve and/or change. These are usually physical aspects, and they can be very specific here.
                       <br/><br/>
                      Use the body mapper/selector to click and select any areas of the body you wish to work on, then use the selection boxes to pinpoint the area you with to focus on. You can add additional notes for each body part in the notes section found in the bottom right corner.
                       <br/><br/>
                      You can select ‘whole body’ to indicate there is no specific target area you wish to work on, but your body as a whole. "><i class="fa fa-question-circle question-mark"></i></a>

                  </div>

              </div>
              <div class="form-group">
                <button type="button" class="btn btn-o btn-default btn-block border-orange" data-toggle="modal" data-target="#goalModal">Click here to view body parts</button>
            </div>
        </div>
        <div class="step">
          <div class="row">

              <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

          </span>
          <div class="heading-text border-head mb-10">
              <div class="watermark1"><span>3.</span></div>
              <label class="steps-head">What <b>areas</b> of your lifestyle are you <b>willing to improve</b> to achieve your goal? </label>
          </div>
          <div class="tooltip-sign mb-10">
             <a href="javascript:void(0)" class="parq-step5" data-message="This identifies how much effort you are willing to put in to achieve your <span style='color: #f94211'><b>EPIC</b> Goal</span> and it identifies what aspects of your life you are willing to make changes to, in order to achieve your goal.
                 <br/><br/>
                <b>Physical Activity -</b> The fact you are completing this online consultation form is a testament to the fact that you are willing to work on your level of physical activity. You cannot disagree that you do not need to make any improvements or changes to your current physical activity habits to make achieving your goal even more possible? Physical activity covers resistance, cardiovascular, recovery routines, rehabilitation, sports specific programs and so much more.
                 <br/><br/>
                <b>Nutrition & Hydration -</b> This is critical to change & improve in order to achieve your Health & Wellness goals. You cannot expect to see positive change in your lifestyle and overall health and wellness if you are not addressing hydration and nutritional habits. This can often be a challenging area to make changes in but with the help of the <span style='color: #f94211'><b>EPIC</b> Process</span> and your <span style='color: #f94211'><b>EPIC</b> Trainer</span>, the changes you will implement will be slow and sustainable allowing for long term success and results.
                 <br/><br/>
                <b>Occupation -</b> If you are unhappy/stressed at work, we need to explain the immediate and broad impact stress has on your life and results. Stress affects performance, sleep, happiness and most importantly results. If stress levels are high, results will take longer to be achieved. You may not be able to change your current work environment, but there are many different tools and information which can help to manage your stress levels and ways to improve both your perception and your actual work environment.
                 <br/><br/>
                <b>Sleep -</b> You might not be getting enough sleep. Insufficient sleep will leave you tired, with bad cravings and looking for potential energy boosters in the form of sugar, caffeine, or poor food choices. It is not always the duration of your sleep that is the issue, you need to consider the quality of your sleep. How do you feel when you wake up, are you energised for the day, how do you feel throughout the day? Sleep is the most, underestimated, powerful tool you have to give yourself so much control over decisions, actions, behaviours, thoughts, and moods.
                 <br/><br/>
                <b>Relationships -</b> This question is more than just relationships with other people. Toxic relationships have a bad influence over you and your choices and decisions across the board. If we can identify and address these and work on how to best deal with them to limit the influence they have, we can focus on your success. Relationships can include relationships with people, food, sugar, social media, alcohol, bad habits etc. "><i class="fa fa-question-circle question-mark"></i></a>

            </div>

        </div>

        <?php
        if(!count($parq->lifestyleImprove))
            $parq->lifestyleImprove = [];
        ?>
        <div class="form-group">
            <label class="container_check version_2">Physical activity
                <input type="checkbox" name="lifestyleImprove" value="Physical activity"{{in_array('Physical activity', $parq->lifestyleImprove)?'checked':''}}>
                <span class="checkmark"></span>
            </label>
        </div>
        <div class="form-group">
            <label class="container_check version_2">Hydration
                <input type="checkbox" name="lifestyleImprove" value="Hydration"{{in_array('Hydration', $parq->lifestyleImprove)?'checked':''}} >
                <span class="checkmark"></span>
            </label>
        </div>
        <div class="form-group">
            <label class="container_check version_2">Nutrition
                <input type="checkbox" name="lifestyleImprove" value="Nutrition"{{in_array('Nutrition', $parq->lifestyleImprove)?'checked':''}} >
                <span class="checkmark"></span>
            </label>
        </div>
        <div class="form-group">
            <label class="container_check version_2">Sleep
                <input type="checkbox" name="lifestyleImprove" value="Sleep"{{in_array('Sleep', $parq->lifestyleImprove)?'checked':''}} >
                <span class="checkmark"></span>
            </label>
        </div>
        <div class="form-group">
            <label class="container_check version_2">Occupation
                <input type="checkbox" name="lifestyleImprove" value="Occupation"{{in_array('Occupation', $parq->lifestyleImprove)?'checked':''}}>
                <span class="checkmark"></span>
            </label>
        </div>
        <div class="form-group">
            <label class="container_check version_2">Relationships
                <input type="checkbox" name="lifestyleImprove" value="Relationships"{{in_array('Relationships', $parq->lifestyleImprove)?'checked':''}}>
                <span class="checkmark"></span>
            </label>
        </div>
    </div>
    <div class="step">
        <div class="row">

          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

      </span>
      <div class="heading-text border-head mb-10">
          <div class="watermark1"><span>4.</span></div>
          <label class="steps-head">I want <b>to be</b></label>
      </div>
      <div class="tooltip-sign mb-10">
          <a href="javascript:void(0)" class="parq-step5" data-message="These are related to physical outcomes most individuals are looking for with regards to their goal. 
           <br/><br/>
          <b>Toned—</b>Toning is the defining of muscles on the body. In order to this you need to make changes to all aspects of your lifestyle to achieve the toned look you are after.
          <br/><br/>
          <b>Fitter—</b>There is no definition of fitter, so you need to self-assess and benchmark your current fitness level then at a later stage we will assess your fitness, so we have an exact measure and gauge of improvement, based on your personal outcome.
          <br/><br/>
          <b>Stronger—</b> Strength is not only related to lifting weights and resistance training, but strength can also simply mean feeling strong enough to complete your daily activities comfortably with little to no discomfort.
          <br/><br/>
          <b>Flexible—</b> Flexibility is a crucial aspect of physical activity and relates to how mobile and limber you feel around joints and in muscles themselves. As you age flexibility decreases but is particularly important to improve and maintain."><i class="fa fa-question-circle question-mark"></i></a>

      </div>

  </div>
  <?php
  if(!count($parq->goalWantTobe))
    $parq->goalWantTobe = [];
?>
<div class="form-group">
    <label class="container_check version_2">Select All
        <input type="checkbox" name="goalWantTobe" id="wantToBeCheckAll" value="">
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Toned
        <input type="checkbox" name="goalWantTobe" value="Toned"{{in_array('Toned', $parq->goalWantTobe)?'checked':''}} >
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Fitter
        <input type="checkbox" name="goalWantTobe" value="Fitter"{{in_array('Fitter', $parq->goalWantTobe)?'checked':''}} >
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Stronger
        <input type="checkbox" name="goalWantTobe" value="Stronger"{{in_array('Stronger', $parq->goalWantTobe)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Flexible
       <input type="checkbox" name="goalWantTobe" value="Flexible"{{in_array('Flexible', $parq->goalWantTobe)?'checked':''}}          >
       <span class="checkmark"></span>
   </label>
</div>
</div>
<div class="step">
  <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>5.</span></div>
      <label class="steps-head">I want <b>to feel</b> </label>
  </div>
  <div class="tooltip-sign mb-10">
    <a href="javascript:void(0)" class="parq-step5" data-message="These are related to wellness states both emotionally and mentally.
    <br/><br/>
    <b>Happier—</b> Happiness is relative to you as an individual and this may seem like a strange request, but you need to ask yourself, are you genuinely happy with your life, your physical ability, your relationships, who you are or have become and your life in general? Physical activity & healthy, balanced nutrition & lifestyle changes help to balance cortisol levels (the stress hormone) and results in the release of serotonin & endorphin release which make you feel happier among other things. 
    <br/><br/>
    <b>Energetic—</b> How do you feel when you wake up? Do you have sustained energy at work and throughout the day? Developing a sustainable level of energy is crucial for a healthy happy lifestyle to be achieved. If you wake up tired, you start your day with low energy and can set yourself up for a bad day ahead. Energy is often linked to sleep, stress & nutrition.
    <br/><br/>
    <b>Healthier—</b> This starts with what your perception of what healthy is. You may have the right idea or be completely off track. Knowing where you think your overall health is and whether or not you need to improve is critical. We all know what healthy is and how far away from that we are. Have you felt healthier is a good starting point to gauge how far from your level you want to be?
    <br/><br/>
    <b>More relaxed—</b> You may not realise how stressed you actually are, and you will be surprised at how many supressed stresses you actually have draining your mental, physical, and emotional energy. Regular physical activity, healthy lifestyle & healthy nutrition habits can help improve mood and decrease stress levels, allowing a more relaxed version of you to exist."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <label class="container_check version_2">Select All
        <input type="checkbox" name="goalWantfeel" value=""  id="goalWantHaveCheckAll">
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Happier 
        <input type="checkbox" name="goalWantfeel" value="Happier"{{in_array('Happier', $parq->goalWantfeel)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Energetic
        <input type="checkbox" name="goalWantfeel" value="Energetic"{{in_array('Energetic', $parq->goalWantfeel)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Healthier
        <input type="checkbox" name="goalWantfeel" value="Healthier"{{in_array('Healthier', $parq->goalWantfeel)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Relaxed
        <input type="checkbox" name="goalWantfeel" value="Relaxed"{{in_array('Relaxed', $parq->goalWantfeel)?'checked':''}} >
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>6.</span></div>
      <label class="steps-head">I want <b>to have</b>  </label>
  </div>
  <div class="tooltip-sign mb-10">
    <a href="javascript:void(0)" class="parq-step5" data-message="These are the outcomes individuals seek to experience change in:
     <br/><br/>
    <b>Less stress—</b> Do you need a lifestyle change to reduce your stress levels. What are your main causes of stress? Physical activity can help improve mood and decrease stress levels, but you can also work on identifying and limiting or eliminating stresses.
     <br/><br/>
    <b>More time—</b> Many individuals feel they do not have enough time to focus on creating a healthy lifestyle. We may need to tailor your program to get you doing more effective activity in less time if we cannot improve your overall time management. The perception of no time is a big concern too that we may need to address.
     <br/><br/>
    <b>More fun—</b> The more you enjoy something, the more motivated you are to continue. Physical activity may be hard initially but once you have improved your movement patterns and are moving with maximum comfort, control, and have confidence in your ability, you will experience how fun it is to be active, strong, and able. This also relates to what you want to do outside of your normal life, friends, family and social events and gatherings.
    <br/><br/>
    <b>More control—</b> Often there may be one or more aspects in your life that you wish to have control over.  A healthy lifestyle helps build consistency, giving you the confidence to take better control of other aspects in your life that you may previously have felt you lost control in. Examples would be controlling your hunger, your sleep patterns, or your daily habits."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <label class="container_check version_2">Select All
        <input type="checkbox" name="goalWantHave" value=""  id="goalWantHaveCheck" >
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Less stress 
        <input type="checkbox" name="goalWantHave" value="Less stress" {{in_array('Less stress', $parq->goalWantHave)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">More time
        <input type="checkbox" name="goalWantHave" value="More time"{{in_array('More time', $parq->goalWantHave)?'checked':''}}  >
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">More fun
        <input type="checkbox" name="goalWantHave" value="More fun"{{in_array('More fun', $parq->goalWantHave)?'checked':''}} >
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">More control
        <input type="checkbox" name="goalWantHave" value="More control"{{in_array('More control', $parq->goalWantHave)?'checked':''}} >
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
      <label class="steps-head">How <b>Supportive</b> is your <b>family</b>?   </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step5" data-message="This section also identifies what kind of support you have, if any. It is important for your <span style='color: #f94211'><b>EPIC</b> Trainer</span> to know what support you have, in making the necessary changes to become physically active and to live a healthier life. 
                                <br/><br/>
    Strong support structures help maintain mental and emotional wellbeing, which in turn affects how people handle challenges, changes, and problems. If you have a good support structure you are most likely going to achieve your <span style='color: #f94211'><b>EPIC</b> Goal</span> as you have support from trusted friends and loved ones, who will encourage you through challenges and changes you will experience, relating to lifestyle habit improvements.
     <br/><br/>
    If you have no support structure, your <span style='color: #f94211'><b>EPIC</b> Trainer</span> needs to initially become the support structure and then help you identify other people in your life who could potentially help form your support structure.
     <br/><br/>
    Family (None, Moderate, High)<br/>
    This relates to support and encouragement you receive from your immediate family. If you receive little support, your <span style='color: #f94211'><b>EPIC</b> Trainer</span> may have to become that support structure and/or help you to become more self-motivated to support yourself despite your environment or encourage and teach family hoe best to support you.
    <br/><br/>
    Support levels include:
    <br/><br/>
    <b>None -</b> meaning no support. 
    <br/><br/>
    <b>Moderate -</b> meaning a limited amount of support or conditional/inconsistent support. 
    <br/><br/>
    <b>High -</b> you can rely 100% on your support structure."><i class="fa fa-question-circle question-mark"></i></a>

</div>
</div>


<div class="form-group">
    <label class="container_check version_2">None
        <input type="radio" name="supportFamily" value="None"{{$parq->supportFamily == 'None'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Moderate
        <input type="radio" name="supportFamily" value="Moderate"{{$parq->supportFamily == 'Moderate'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">High
        <input type="radio" name="supportFamily" value="High" {{$parq->supportFamily == 'High'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>8.</span></div>
      <label class="steps-head">How <b>supportive</b> are your <b>friends</b>?  </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step5" data-message="If you receive little support, your trainer may have to become that support structure and introduce you to the <span style='color: #f94211'><b>EPIC</b></span> community to be supported by the right kind of like-minded individuals.
        <br/><br/>
        It does not mean these people cannot be friends, it just means you have to decide how much influence you will allow them to have over your lifestyle changes. 
         <br/><br/>
        Friends (None, Moderate, High)<br/>
        This relates to support and encouragement you receive from your friends. You may have certain friends who are supportive & are positive influences in your life and you may have friends who are negative or bad influences. It is important to be aware and identify this. 
         <br/><br/>
        Support levels include:
        <br/><br/>
        <b>None -</b> meaning no support. 
        <br/><br/>
        <b>Moderate -</b> meaning a limited amount of support or conditional/inconsistent support. 
        <br/><br/>
        <b>High -</b> you can rely 100% on your support structure."><i class="fa fa-question-circle question-mark"></i></a>

    </div>
</div>
<div class="form-group">
    <label class="container_check version_2">None
        <input type="radio" name="supportFriends" value="None"{{$parq->supportFriends == 'None'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Moderate
        <input type="radio" name="supportFriends" value="Moderate"{{$parq->supportFriends == 'Moderate'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">High
        <input type="radio" name="supportFriends" value="High"{{$parq->supportFriends == 'High'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>9.</span></div>
      <label class="steps-head">How <b>supportive</b> are your <b>work colleagues</b>?  </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step5" data-message="Work (None, Moderate, High)<br/>
   This relates to support and encouragement you receive from your work associates/colleagues. Some workplaces are very encouraging and provide a lot of support and motivation for stair staff to be healthy & active. If you receive little support, your <span style='color: #f94211'><b>EPIC</b> Trainer</span> may have to become that support structure or you may need to request more support from your place of work if you require it.
   <br/><br/>
   Support levels include:
   <br/><br/>
   <b>None -</b> meaning no support. 
   <br/><br/>
   <b>Moderate -</b> meaning a limited amount of support or conditional/inconsistent support. 
   <br/><br/>
   <b>High -</b> you can rely 100% on your support structure."><i class="fa fa-question-circle question-mark"></i></a>

</div>
</div>
<div class="form-group">
    <label class="container_check version_2">None
        <input type="radio" name="supportWork" value="None"{{$parq->supportWork == 'None'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Moderate
        <input type="radio" name="supportWork" value="Moderate"{{$parq->supportWork == 'Moderate'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">High
        <input type="radio" name="supportWork" value="High"{{$parq->supportWork == 'High'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step">
    <?php
    if(!count($parq->motivationImprove))
        $parq->motivationImprove = [];
    ?>
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>10.</span></div>
      <label class="steps-head">Which best <b>describes</b> your <b>motivation levels</b>  </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step5" data-message="It is extremely useful to your <span style='color: #f94211'><b>EPIC</b> Trainer</span> if they know what level of motivation you currently have. This will influence how long it will take you to achieve your <span style='color: #f94211'><b>EPIC</b> Goal</span>. We need to know how much encouragement and support you need to adhere to your program as well as what environment you feel most motivated in.
        <br/><br/>
        Your motivation levels may change over time as your progression happens and also you may be more, or less, motivated in certain aspects of your journey than other aspects. We will then focus on improving your motivation in those areas where it lacks."><i class="fa fa-question-circle question-mark"></i></a>

    </div>
</div>

<div class="form-group">
    <label class="container_check version_2">No motivation
        <input type="checkbox" name="motivationImprove" value="No motivation"{{in_array('No motivation', $parq->motivationImprove)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Low motivation
        <input type="checkbox" name="motivationImprove" value="Low motivation"{{in_array('Low motivation', $parq->motivationImprove)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">TEAM motivated
        <input type="checkbox" name="motivationImprove" value="TEAM motivated"{{in_array('TEAM motivated', $parq->motivationImprove)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Self-motivated 
        <input type="checkbox" name="motivationImprove" value="Self-motivated"{{in_array('Self-motivated', $parq->motivationImprove)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>11.</span></div>
      <label class="steps-head">SMARTER <b>Goal notes</b>  </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step5" data-message="This is a brief overview of the <span style='color: #f94211'><b>EPIC</b> Goal</span> you are wanting to work towards. Your <span style='color: #f94211'><b>EPIC</b> Trainer</span> can take these details and use them to create your final BE SMARTER Goal on your Goal Setting Step, coming later. 
         <br/><br/>
        You may have more than one <span style='color: #f94211'><b>EPIC</b> Goal</span> you wish to achieve but you need to prioritise the most important Goal related to your current health and wellness state and focus on achieving that one first. 
         <br/><br/>
        <b>Specific –</b> A clearly defined, clear, concise, and detailed goal. i.e., losing 5kg, dunking a basketball, running 21km etc. 
         <br/><br/>
        <b>Measurable -</b> Measurable to include milestones i.e., Dropping a clothing size measured in cm, losing weight measured in kg, doing x amount of push ups measured in reps etc 
         <br/><br/>
        <b>Achievable -</b> Your motivation & commitment to all the necessary changes & improvement you need to implement. Are you WILLING to make all the necessary changes?  ONLY if you answer YES to this can you achieve this goal and move onto the next phase of the process.
         <br/><br/>
        <b>Relevant –</b> Your goal needs to be relevant and meaningful to YOU. Intrinsic goals, goals for you, are goals that matter to you, thus more likely to be achieved. i.e., If I lose weight, I will feel more confident, when I run 21 km, I will feel proud etc. never set a goal to please anybody but yourself and your life.
         <br/><br/>
        <b>Time Dependant –</b> The time you need to commit to achieving this goal. This includes all your milestones and considers all the changes that need to be implemented. Lose 5 kg in 8 weeks, do 10 Pushups in 3 months etc. 
         <br/><br/>
        You may not know the answers to these questions, but your trainer will help you to make this goal realistic and achievable."><i class="fa fa-question-circle question-mark"></i></a>

    </div>
</div>
@if($parq->smartGoalSpecific == '' || $parq->smartGoalMeasurable == '' || $parq->smartGoalAchievable == '' || $parq->smartGoalRelevent == '' || $parq->smartGoalTime == '')

<div class="mobile_center_text">
    <a class="btn-add-more openSmartGoal"> + Add SMART Goal</a>
</div>
@endif
<textarea rows="1" id="smartGoalNotes" name="smartGoalNotes" ng-model="smartGoalNotes" ng-init="smartGoalNotes='{{ isset($parq) ? $parq->smartGoalNotes : null }}'" placeholder="" class="form-control">{{ isset($parq)?$parq->smartGoalNotes : null }}</textarea>
<div class="goal_notes" name="smart_goal_option">
    @if($parq->smartGoalSpecific != '')
    <div class="form-group" data-option-val="Specific">
        <label class="strong medinotes">Specific </label>
        <input class="form-control" value="{{$parq->smartGoalSpecific}}" name="smartGoalSpecific">
    </div>
    @endif
    @if($parq->smartGoalMeasurable != '')
    <div class="form-group" data-option-val="Measurable">
        <label class="strong medinotes">Measurable </label>
        <input class="form-control" value="{{$parq->smartGoalMeasurable}}" name="smartGoalMeasurable">
    </div>
    @endif
    @if($parq->smartGoalAchievable != '')
    <div class="form-group" data-option-val="Measurable">
        <label class="strong medinotes">Achievable </label>
        <input class="form-control" value="{{$parq->smartGoalAchievable}}" name="smartGoalAchievable">
    </div>
    @endif
    @if($parq->smartGoalRelevent != '')
    <div class="form-group" data-option-val="Relevent">
        <label class="strong medinotes">Relevent </label>
        <input class="form-control" value="{{$parq->smartGoalRelevent}}" name="smartGoalRelevent">
    </div>
    @endif
    @if($parq->smartGoalTime != '')
    <div class="form-group" data-option-val="Time">
        <label class="strong medinotes">Time </label>
        <input class="form-control" value="{{$parq->smartGoalTime}}" name="smartGoalTime">
    </div>
    @endif
</div>
</div>
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>12.</span></div>
      <label class="steps-head">How <b>important</b> is it to achieve your goal?  </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step5" data-message="This is an indicator of where you prioritise your <span style='color: #f94211'><b>EPIC</b> Goal</span> in your life. It will indicate to the trainer your current commitment levels.
    <br/><br/>
    <b>VERY IMPORTANT—</b>if you are not fully committed to achieving this goal, you make it more difficult to achieve. If the goal is not ‘extremely’ important to you, we need to figure out how we can change your perception of the importance of it and how we can move it up your priority list. "><i class="fa fa-question-circle question-mark"></i></a>

</div>
</div>
<div class="form-group">
    <label class="container_check version_2">Not
        <input type="checkbox" name="achieveGoal" value="Not"{{$parq->achieveGoal == 'Not'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Somewhat
        <input type="checkbox" name="achieveGoal" value="Somewhat"{{$parq->achieveGoal == 'Somewhat'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Very
        <input type="checkbox" name="achieveGoal" value="Very"{{$parq->achieveGoal == 'Very'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
<div class="form-group">
    <label class="container_check version_2">Extremely 
        <input type="checkbox" name="achieveGoal" value="Extremely"{{$parq->achieveGoal == 'Extremely'?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="submit step" id="end">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>13.</span></div>
      <label class="steps-head">Please provide any <b>additional notes</b> you think are relevant </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step5" data-message="This is an indicator of where you prioritise your <span style='color: #f94211'><b>EPIC</b> Goal</span> in your life. It will indicate to the trainer your current commitment levels.
    <br/><br/>
    <b>VERY IMPORTANT—</b>if you are not fully committed to achieving this goal, you make it more difficult to achieve. If the goal is not ‘extremely’ important to you, we need to figure out how we can change your perception of the importance of it and how we can move it up your priority list. "><i class="fa fa-question-circle question-mark"></i></a>

</div>
</div>

<div class="form-group">
    <textarea rows="1" id="goalNotes" name="goalNotes" ng-model="goalNotes" ng-init="goalNotes='{{ isset($parq) ? $parq->goalNotes : null }}'" placeholder="" class="form-control">{{ isset($parq)?$parq->goalNotes : null }}</textarea>
</div>
</div>
</div>
<!-- /middle-wizard -->

<div id="bottom-wizard">
 <span class="qodef-grid-line-center"><span class="inmotion-total-slides">
  <div class="d-flex">
    {{-- <a class="prev-name redirect_prev_page" href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/PARQ') }}" data-step-no="4">
        <span>PARQ &nbsp;&nbsp;</span>
        <span  class="arrow step-back">&#8672;</span> 
    </a>              
    <div class="current-section">GOALS & MOTVATION</div>
    <a class="next-name" href="#">
        <span  class="arrow step-forward"></span>
        <span></span>
    </a> --}}
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/PARQ') }}" data-step-no="4" class="step-back"><span class="prev-name">PARQ</span></a>&nbsp;&nbsp; 
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/PARQ') }}" data-step-no="4" class="arrow step-back">&#8672;</a>               
    <div class="current-section">GOALS & MOTVATION</div>
   
    <a class="arrow step-forward invisible" href="#">&#8674;</a> 
    <a href="#" class="step-forward invisible">&nbsp;&nbsp;
    <span class="next-name">PERSONAL DETAILS</span> </a>
</div>

<span class="inmotion-ts-active-num section-step">05</span>
<span class="inmotion-ts-active-separator">/</span>
<span class="inmotion-ts-active-all all-section-step">05</span>
</span>
<span class="inmotion-total-slides visible-xs question-s">QUESTIONS<br>
    <span class="inmotion-ts-active-num question-step">5</span>
    <span class="inmotion-ts-active-separator">/</span>
    <span class="inmotion-ts-active-all">13</span>
</span>
<span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
<div class="row">
    <div class="col-sm-5 col-xs-5">  <button type="button" name="backward" class="backward">Prev</button></div>
    <div class="col-sm-7 col-xs-7">

      <button type="button" name="forward" class="forward">Next</button>
      <button type="button" class="submit submit-step" data-step="5">Submit</button>
  </div>
</div>


</div>

<!-- /bottom-wizard -->


<div class="modal bodyPartModal" id="goalModal" role="dialog">
    <div class="modal-dialog modal-lg">
     <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-uppercase">Goals and motivation</h4>
            <p>Please indicate areas on the image that you would like to strengthen, tone, rehabilitate, improve flexibility and mobility or other</p>
        </div>
        <div class="modal-body white-bg">
         <div class="row">
            <div class="alert alert-success injuryShowAlert" role="alert" style="display:none;" >Data saved successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button></div>
                <div class="col-md-8">
                   <div class="hidden-xs hidden-sm">
                     @if($parq->gender == 'Male')
                     <img src="{{ asset('bodytool/male/injuries.gif') }}" usemap="#Map" class="body" width="600" />
                     @else
                     <img src="{{ asset('bodytool/female/injuries.gif') }}" usemap="#Map" class="body" width="600" />
                     @endif
                 </div>
                 <div class="form-group hidden-md hidden-lg">
                    {!! Form::label('bodyParts5', 'Body parts', ['class' => 'strong']) !!}
                    <select class="form-control bodyPartsDd"  id="bodyParts5">
                        <option data-part="">-- Select --</option>
                        <option data-part="ankle-n-foot">Ankle & Feet</option>
                        <option data-part="knee-n-legs">Knee & Legs</option>
                        <option data-part="hips-n-lower-back">Hips & Lower Back</option>
                        <option data-part="core">Core</option>
                        <option data-part="mid-upper-back">Back Mid & Upper</option>
                        <option data-part="chest">Chest</option>
                        <option data-part="shoulders">Shoulders</option> 

                        <option data-part="elbows-n-arms">Elbow & Arms</option> 

                        <option data-part="wrist-n-hand">Wrist & Hands</option>
                        <option data-part="neck">Neck</option>
                        <option data-part="head">Head</option>
                    </select>
                </div>    
            </div>
            <div class="col-md-4">
             <div class="head injuryList hidden">
                 <div class="form-group">
                    <h4 class="text-uppercase">Head</h4>
                    <?php
                    if(!count($parq->headImprove))
                        $parq->headImprove = [];
                    ?>
                    <div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                            <input type="checkbox" name="headImprove0" value="Rehabilitate" id="headImprove0" <?php echo in_array('Rehabilitate', $parq->headImprove)?'checked':''; ?>/>
                            <label for="headImprove0">

                            </label>
                        </div>Rehabilitate
                    </div>
                    <div>
                     <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                        <input type="checkbox" name="headImprove1" value="Improve flexibility and mobility" id="headImprove1" <?php echo in_array('Improve flexibility and mobility', $parq->headImprove)?'checked':''; ?>/>
                        <label for="headImprove1">

                        </label>
                    </div>Improve flexibility and mobility
                </div>
                <div>
                 <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                    <input type="checkbox" name="headImprove2" value="All" id="headImprove2" <?php echo in_array('All', $parq->headImprove)?'checked':''; ?>/>
                    <label for="headImprove2">

                    </label>
                </div>All
            </div>
            <div>
             <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                <input type="checkbox" name="headImprove3" value="Other" id="headImprove3" <?php echo in_array('Other', $parq->headImprove)?'checked':''; ?>/>
                <label for="headImprove3">

                </label>
            </div>Other
        </div>
    </div>                                
    <div class="form-group">
        <label class="strong" for="notesHeadImprove">Please add the relevant notes relating to goals selected above</label>
        <textarea class="form-control" id="notesHeadImprove" name="notesHeadImprove">{{ $parq->headImproveNotes }}</textarea>
    </div>
</div>

<div class="neck injuryList hidden">
 <div class="form-group">
    <h4 class="text-uppercase">Neck</h4>
    <?php
    if(!count($parq->neckImprove))
        $parq->neckImprove = [];
    ?>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="neckImprove0" value="Strengthen" id="neckImprove0" <?php echo in_array('Strengthen', $parq->neckImprove)?'checked':''; ?>/>
            <label for="neckImprove0">

            </label>
        </div>Strengthen
        <div>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="neckImprove1" value="Tone" id="neckImprove1" <?php echo in_array('Tone', $parq->neckImprove)?'checked':''; ?>/>
            <label for="neckImprove1">

            </label>
        </div>Tone
    </div>
    <div>
      <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="neckImprove2" value="Rehabilitate" id="neckImprove2" <?php echo in_array('Rehabilitate', $parq->neckImprove)?'checked':''; ?>/>
        <label for="neckImprove2">

        </label>
    </div>Rehabilitate
</div>
<div>
  <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
    <input type="checkbox" name="neckImprove3" value="Improve flexibility and mobility" id="neckImprove3" <?php echo in_array('Improve flexibility and mobility', $parq->neckImprove)?'checked':''; ?>/>
    <label for="neckImprove3">

    </label>
</div>Improve flexibility and mobility
</div>
<div>
  <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
    <input type="checkbox" name="neckImprove4" value="All" id="neckImprove4" <?php echo in_array('All', $parq->neckImprove)?'checked':''; ?>/>
    <label for="neckImprove4">

    </label>
</div>All
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="neckImprove5" value="Other" id="neckImprove5" <?php echo in_array('Other', $parq->neckImprove)?'checked':''; ?>/>
        <label for="neckImprove5">

        </label>
    </div>Other
</div>
</div>    
<div class="form-group">
    <label class="strong" for="notesNeckImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesNeckImprove" name="notesNeckImprove">{{ $parq->neckImproveNotes }}</textarea>
</div>
</div>

<div class="mid-upper-back injuryList hidden">
   <div class="form-group">
    <h4 class="text-uppercase">Back Mid & Upper</h4>
    <?php
    if(!count($parq->backImprove))
        $parq->backImprove = [];
    ?>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="backImprove0" value="Strengthen" id="backImprove0" <?php echo in_array('Strengthen', $parq->backImprove)?'checked':''; ?>/>
            <label for="backImprove0">

            </label>
        </div>Strengthen
    </div>
    <div>
      <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="backImprove1" value="Tone" id="backImprove1" <?php echo in_array('Tone', $parq->backImprove)?'checked':''; ?>/>
        <label for="backImprove1">

        </label>
    </div>Tone
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="backImprove2" value="Rehabilitate" id="backImprove2" <?php echo in_array('Rehabilitate', $parq->backImprove)?'checked':''; ?>/>
        <label for="backImprove2">

        </label>
    </div>Rehabilitate
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="backImprove3" value="Improve flexibility and mobility" id="backImprove3" <?php echo in_array('Improve flexibility and mobility', $parq->backImprove)?'checked':''; ?>/>
        <label for="backImprove3">

        </label>
    </div>Improve flexibility and mobility
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="backImprove4" value="All" id="backImprove4" <?php echo in_array('All', $parq->backImprove)?'checked':''; ?>/>
        <label for="backImprove4">

        </label>
    </div>All
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="backImprove5" value="Other" id="backImprove5" <?php echo in_array('Other', $parq->backImprove)?'checked':''; ?>/>
        <label for="backImprove5">

        </label>
    </div>Other
</div>
</div>
<div class="form-group">
    <label class="strong" for="notesBackImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesBackImprove" name="notesBackImprove">{{ $parq->backImproveNotes }}</textarea>
</div>
</div>

<div class="ankle-n-foot injuryList hidden">
 <div class="form-group">
    <h4 class="text-uppercase">Ankle & Feet</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->footImprove))
        $parq->footImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="footImprove0" value="L_Strengthen" name="footImprove0" <?php echo in_array('L_Strengthen', $parq->footImprove)?'checked':''; ?>/> 
            <label for="footImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="footImprove1" value="R_Strengthen" id="footImprove1" <?php echo in_array('R_Strengthen', $parq->footImprove)?'checked':''; ?> /> 
            <label for="footImprove1" class="m-r-0"></label>
        </div>Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="footImprove2" value="L_Rehabilitate" name="footImprove2" <?php echo in_array('L_Rehabilitate', $parq->footImprove)?'checked':''; ?>/> 
            <label for="footImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="footImprove3" value="R_Rehabilitate" id="footImprove3" <?php echo in_array('R_Rehabilitate', $parq->footImprove)?'checked':''; ?> /> 
            <label for="footImprove3" class="m-r-0"></label>
        </div>Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="footImprove4" value="L_Improve flexibility and mobility" name="footImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->footImprove)?'checked':''; ?>/> 
            <label for="footImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="footImprove5" value="R_Improve flexibility and mobility" id="footImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->footImprove)?'checked':''; ?> /> 
            <label for="footImprove5" class="m-r-0"></label>
        </div>Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="footImprove6" value="L_All" name="footImprove6" <?php echo in_array('L_All', $parq->footImprove)?'checked':''; ?>/> 
            <label for="footImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="footImprove7" value="R_All" id="footImprove7" <?php echo in_array('R_All', $parq->footImprove)?'checked':''; ?> /> 
            <label for="footImprove7" class="m-r-0"></label>
        </div>All
    </div>
    <div class="m-t-5">
     <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <label for="" class="m-r-0"></label>
    </div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
     <input type="checkbox" id="footImprove8" value="Other" name="footImprove8" <?php echo in_array('Other', $parq->footImprove)?'checked':''; ?>/>
     <label for="footImprove8">

     </label>
 </div>Other 
</div>
</div>    
<div class="form-group">
    <label class="strong" for="notesFootImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesFootImprove" name="notesFootImprove">{{ $parq->footImproveNotes }}</textarea>
</div>
</div>

<div class="knee-n-legs injuryList hidden">
 <div class="form-group">
    <h4 class="text-uppercase">Knee & Legs</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->legImprove))
        $parq->legImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove0" value="L_Strengthen" name="legImprove0" <?php echo in_array('L_Strengthen', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove1" value="R_Strengthen" id="legImprove1" <?php echo in_array('R_Strengthen', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove1" class="m-r-0"></label>
        </div>Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove10" value="L_Tone" name="legImprove10" <?php echo in_array('L_Tone', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove10" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove11" value="R_Tone" id="legImprove11" <?php echo in_array('R_Tone', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove11" class="m-r-0"></label>
        </div>Tone
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove2" value="L_Rehabilitate" name="legImprove2" <?php echo in_array('L_Rehabilitate', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove3" value="R_Rehabilitate" id="legImprove3" <?php echo in_array('R_Rehabilitate', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove3" class="m-r-0"></label>
        </div>Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove4" value="L_Improve flexibility and mobility" name="legImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove5" value="R_Improve flexibility and mobility" id="legImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove5" class="m-r-0"></label>
        </div>Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove6" value="L_All" name="legImprove6" <?php echo in_array('L_All', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove7" value="R_All" id="legImprove7" <?php echo in_array('R_All', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove7" class="m-r-0"></label>
        </div>All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <label for="" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove8" value="Other" name="legImprove8" <?php echo in_array('Other', $parq->legImprove)?'checked':''; ?>/>
            <label for="legImprove8">

            </label>
        </div>Other
    </div>
</div>    
<div class="form-group">
    <label class="strong" for="notesLegImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesLegImprove" name="notesLegImprove">{{ $parq->legImproveNotes }}</textarea>
</div>
</div>

<div class="hips-n-lower-back injuryList hidden">
 <div class="form-group">
    <h4 class="text-uppercase">Hips & Lower Back</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->hipImprove))
        $parq->hipImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove0" value="L_Strengthen" name="hipImprove0" <?php echo in_array('L_Strengthen', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove1" value="R_Strengthen" id="hipImprove1" <?php echo in_array('R_Strengthen', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove1" class="m-r-0"></label>
        </div>Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove10" value="L_Tone" name="hipImprove10" <?php echo in_array('L_Tone', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove10" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove11" value="R_Tone" id="hipImprove11" <?php echo in_array('R_Tone', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove11" class="m-r-0"></label>
        </div>Tone
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove2" value="L_Rehabilitate" name="hipImprove2" <?php echo in_array('L_Rehabilitate', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove3" value="R_Rehabilitate" id="hipImprove3" <?php echo in_array('R_Rehabilitate', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove3" class="m-r-0"></label>
        </div>Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove4" value="L_Improve flexibility and mobility" name="hipImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove5" value="R_Improve flexibility and mobility" id="hipImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove5" class="m-r-0"></label>
        </div>Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove6" value="L_All" name="hipImprove6" <?php echo in_array('L_All', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove7" value="R_All" id="hipImprove7" <?php echo in_array('R_All', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove7" class="m-r-0"></label>
        </div>All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <label for="" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove8" value="Other" name="hipImprove8" <?php echo in_array('Other', $parq->hipImprove)?'checked':''; ?>/>
            <label for="hipImprove8">

            </label>
        </div>Other
    </div>        
</div>    
<div class="form-group">
    <label class="strong" for="notesHipImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesHipImprove" name="notesHipImprove">{{ $parq->hipImproveNotes }}</textarea>
</div>
</div>

<div class="shoulders injuryList hidden">
 <div class="form-group">
    <h4 class="text-uppercase">Shoulders</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->shouldersImprove))
        $parq->shouldersImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove0" value="L_Strengthen" name="shouldersImprove0" <?php echo in_array('L_Strengthen', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove1" value="R_Strengthen" id="shouldersImprove1" <?php echo in_array('R_Strengthen', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove1" class="m-r-0"></label>
        </div>Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove10" value="L_Tone" name="shouldersImprove10" <?php echo in_array('L_Tone', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove10" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove11" value="R_Tone" id="shouldersImprove11" <?php echo in_array('R_Tone', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove11" class="m-r-0"></label>
        </div>Tone
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove2" value="L_Rehabilitate" name="shouldersImprove2" <?php echo in_array('L_Rehabilitate', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove3" value="R_Rehabilitate" id="shouldersImprove3" <?php echo in_array('R_Rehabilitate', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove3" class="m-r-0"></label>
        </div>Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove4" value="L_Improve flexibility and mobility" name="shouldersImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove5" value="R_Improve flexibility and mobility" id="shouldersImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove5" class="m-r-0"></label>
        </div>Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove6" value="L_All" name="shouldersImprove6" <?php echo in_array('L_All', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove7" value="R_All" id="shouldersImprove7" <?php echo in_array('R_All', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove7" class="m-r-0"></label>
        </div>All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <label for="" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove8" value="Other" name="shouldersImprove8" <?php echo in_array('Other', $parq->shouldersImprove)?'checked':''; ?>/>
            <label for="shouldersImprove8">

            </label>
        </div>Other 
    </div>
</div>    
<div class="form-group">
    <label class="strong" for="notesShouldersImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesShouldersImprove" name="notesShouldersImprove">{{ $parq->shouldersImproveNotes }}</textarea>
</div>
</div>

<div class="elbows-n-arms injuryList hidden">
 <div class="form-group">
    <h4 class="text-uppercase">Elbow & Arms</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->armsImprove))
        $parq->armsImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove0" value="L_Strengthen" name="armsImprove0" <?php echo in_array('L_Strengthen', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove1" value="R_Strengthen" id="armsImprove1" <?php echo in_array('R_Strengthen', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove1" class="m-r-0"></label>
        </div>Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove10" value="L_Tone" name="armsImprove10" <?php echo in_array('L_Tone', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove10" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove11" value="R_Tone" id="armsImprove11" <?php echo in_array('R_Tone', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove11" class="m-r-0"></label>
        </div>Tone
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove2" value="L_Rehabilitate" name="armsImprove2" <?php echo in_array('L_Rehabilitate', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove3" value="R_Rehabilitate" id="armsImprove3" <?php echo in_array('R_Rehabilitate', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove3" class="m-r-0"></label>
        </div>Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove4" value="L_Improve flexibility and mobility" name="armsImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove5" value="R_Improve flexibility and mobility" id="armsImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove5" class="m-r-0"></label>
        </div>Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove6" value="L_All" name="armsImprove6" <?php echo in_array('L_All', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove7" value="R_All" id="armsImprove7" <?php echo in_array('R_All', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove7" class="m-r-0"></label>
        </div>All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <label for="" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove8" value="Other" name="armsImprove8" <?php echo in_array('Other', $parq->armsImprove)?'checked':''; ?>/>
            <label for="armsImprove8">

            </label>
        </div>Other
    </div>        
</div>    
<div class="form-group">
    <label class="strong" for="notesArmsImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesArmsImprove" name="notesArmsImprove">{{ $parq->armsImproveNotes }}</textarea>
</div>
</div>

<div class="wrist-n-hand injuryList hidden">
 <div class="form-group">
    <h4 class="text-uppercase">Wrist & Hands</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->handImprove))
        $parq->handImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="handImprove0" value="L_Strengthen" name="handImprove0" <?php echo in_array('L_Strengthen', $parq->handImprove)?'checked':''; ?>/>  
            <label for="handImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="handImprove1" value="R_Strengthen" id="handImprove1" <?php echo in_array('R_Strengthen', $parq->handImprove)?'checked':''; ?> /> 
            <label for="handImprove1" class="m-r-0"></label>
        </div>Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="handImprove2" value="L_Rehabilitate" name="handImprove2" <?php echo in_array('L_Rehabilitate', $parq->handImprove)?'checked':''; ?>/>  
            <label for="handImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="handImprove3" value="R_Rehabilitate" id="handImprove3" <?php echo in_array('R_Rehabilitate', $parq->handImprove)?'checked':''; ?> /> 
            <label for="handImprove3" class="m-r-0"></label>
        </div>Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="handImprove4" value="L_Improve flexibility and mobility" name="handImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->handImprove)?'checked':''; ?>/>  
            <label for="handImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="handImprove5" value="R_Improve flexibility and mobility" id="handImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->handImprove)?'checked':''; ?> /> 
            <label for="handImprove5" class="m-r-0"></label>
        </div>Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="handImprove6" value="L_All" name="handImprove6" <?php echo in_array('L_All', $parq->handImprove)?'checked':''; ?>/>  
            <label for="handImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="handImprove7" value="R_All" id="handImprove7" <?php echo in_array('R_All', $parq->handImprove)?'checked':''; ?> /> 
            <label for="handImprove7" class="m-r-0"></label>
        </div>All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <label for="" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="handImprove8" value="Other" name="handImprove8" <?php echo in_array('Other', $parq->handImprove)?'checked':''; ?>/>
            <label for="handImprove8">

            </label>
        </div>Other 
    </div>                                
</div>    
<div class="form-group">
    <label class="strong" for="notesHandImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesHandImprove" name="notesHandImprove">{{ $parq->handImproveNotes }}</textarea>
</div>
</div>

<div class="chest injuryList hidden">
    <div class="form-group">
        <h4 class="text-uppercase">Chest</h4>
        <?php
        if(!count($parq->chestImprove))
            $parq->chestImprove = [];
        ?>
        <div>
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                <input type="checkbox" name="chestImprove0" value="Strengthen" id="chestImprove0" <?php echo in_array('Strengthen', $parq->chestImprove)?'checked':''; ?>/>
                <label for="chestImprove0">

                </label>
            </div>Strengthen 
        </div>
        <div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="chestImprove1" value="Tone" id="chestImprove1" <?php echo in_array('Tone', $parq->chestImprove)?'checked':''; ?>/>
            <label for="chestImprove1">

            </label>
        </div>Tone
    </div>
    <div>
      <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="chestImprove2" value="Rehabilitate" id="chestImprove2" <?php echo in_array('Rehabilitate', $parq->chestImprove)?'checked':''; ?>/>
        <label for="chestImprove2">

        </label>
    </div>Rehabilitate
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="chestImprove3" value="Improve flexibility and mobility" id="chestImprove3" <?php echo in_array('Improve flexibility and mobility', $parq->chestImprove)?'checked':''; ?>/>
        <label for="chestImprove3">

        </label>
    </div>Improve flexibility and mobility
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="chestImprove4" value="All" id="chestImprove4" <?php echo in_array('All', $parq->chestImprove)?'checked':''; ?>/>
        <label for="chestImprove4">

        </label>
    </div> All
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="chestImprove5" value="Other" id="chestImprove5" <?php echo in_array('Other', $parq->chestImprove)?'checked':''; ?>/>
        <label for="chestImprove5">

        </label>
    </div>Other
</div>
</div> 

<div class="form-group">
    <label class="strong" for="notesChestImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesChestImprove" name="notesChestImprove">{{ $parq->chestImproveNotes }}</textarea>
</div>
</div>

<div class="core injuryList hidden">
    <div class="form-group">
        <h4 class="text-uppercase">Core</h4>
        <?php
        if(!count($parq->coreImprove))
            $parq->coreImprove = [];
        ?>
        <div>
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                <input type="checkbox" name="coreImprove0" value="Strengthen" id="coreImprove0" <?php echo in_array('Strengthen', $parq->coreImprove)?'checked':''; ?>/>
                <label for="coreImprove0">

                </label>
            </div>Strengthen
        </div>
        <div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="coreImprove1" value="Tone" id="coreImprove1" <?php echo in_array('Tone', $parq->coreImprove)?'checked':''; ?>/>
            <label for="coreImprove1">

            </label>
        </div>Tone
    </div>
    <div>
      <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="coreImprove2" value="Rehabilitate" id="coreImprove2" <?php echo in_array('Rehabilitate', $parq->coreImprove)?'checked':''; ?>/>
        <label for="coreImprove2">

        </label>
    </div>Rehabilitate
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="coreImprove3" value="Improve flexibility and mobility" id="coreImprove3" <?php echo in_array('Improve flexibility and mobility', $parq->coreImprove)?'checked':''; ?>/>
        <label for="coreImprove3">

        </label>
    </div>Improve flexibility and mobility
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="coreImprove4" value="All" id="coreImprove4" <?php echo in_array('All', $parq->coreImprove)?'checked':''; ?>/>
        <label for="coreImprove4">

        </label>
    </div>All
</div>
<div>
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
        <input type="checkbox" name="coreImprove5" value="Other" id="coreImprove5" <?php echo in_array('Other', $parq->coreImprove)?'checked':''; ?>/>
        <label for="coreImprove5">

        </label>
    </div>Other
</div>
</div>    
<div class="form-group">
    <label class="strong" for="notesCoreImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesCoreImprove" name="notesCoreImprove">{{ $parq->coreImproveNotes }}</textarea>
</div>
</div>
</div>
</div>
</div>
<div class="modal-footer">
 <div class="checkbox clip-check check-primary checkbox-inline m-t-5 m-b-0 m-r-0 alertHidessssss">
    <input type="checkbox" name="wholeBody" id="wholeBody" class="closeModal" value="1" {{ $parq->wholeBody == 1?'checked':'' }}>
    <label for="wholeBody" class="p-l-0">
        <strong>Whole Body</strong>
    </label>
</div>&nbsp;&nbsp;
<button type="button" class="btn btn-primary res-btn-savedata submit-step injuryAlert" style="margin-bottom: 0px;">Save</button>
<button type="button" class="btn btn-danger alertHide" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>

<div class="modal" id="waiverModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close waiverModalClose" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Waiver</h4>
            </div>
            <div class="modal-body">
                <form role="form" method="post">
                    <input id="client_id" type="hidden" name="client_id" value="{{$parq->client_id}}">
                    <div class="form-group m-x-0">
                        <label>Name</label>
                        <input type="text" class="form-control" value="{{$parq->firstName}} {{$parq->lastName}}" readonly/>
                    </div>
                    <div class="form-group m-x-0">
                        <label for="waiverDate">Date</label>
                        <input type="text" class="form-control onchange-set-neutral" id="waiverDate" name="waiverDate" required autocomplete="off" readonly="" />
                        <span class="help-block m-b-0"></span>
                    </div>
                    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                        <input type="checkbox" name="client_waiver_term" id="client_waiver_term" value="1" required>
                        <label for="client_waiver_term" class="waiverTerms">
                            I fully understand that I have been advised to consult with a physician prior to completing any activity if the EPIC Risk Factor indicates an elevated risk status
                        </label>
                        <span class="help-block m-b-0"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waiverModalClose" data-dismiss="modal">Cancel</button><!-- id="mod_cancel"-->
                <button type="button" class="btn btn-primary submit-step" data-step="5" id="submit">Submit</button>
            </div>
        </div>
    </div>
</div>


</form>
</div>
<!-- /Wizard container -->
</div>
<!-- /content-right-->
</div>
<!-- /row-->
</div>


<div class="modal smartgoalNote" id="specificGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalSpecific)&& $parq->smartGoalSpecific!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Specific</h4>
            </div>
            
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>Improving your nutrition requires smaller steps and a specific focus.</p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="specific" type="button">Next</button>
            </div>
        </div>
    </div>
</div>

<div class="modal smartgoalNote" id="MeasurableGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalMeasurable)&& $parq->smartGoalMeasurable!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Measurable</h4>
            </div>
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>This involves selecting what will be measured to show improvement, impact or success. </p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="measurable" type="button">Next</button>
            </div>
        </div>
    </div>
</div>

<div class="modal smartgoalNote" id="AchievableGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalAchievable)&& $parq->smartGoalAchievable!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Achievable</h4>
            </div>
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>Objectives should be within reach for your team or program, considering available resources, knowledge and time. </p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="achievable" type="button">Next</button>
            </div>
        </div>
    </div>
</div>

<div class="modal smartgoalNote" id="RelevantGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalRelevent)&& $parq->smartGoalRelevent!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Relevant</h4>
            </div>
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>Objectives should align with a corresponding goal. </p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="relevent" type="button">Next</button>
            </div>
        </div>
    </div>
</div>

<div class="modal smartgoalNote" id="TimeGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalTime)&& $parq->smartGoalTime!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Time</h4>
            </div>
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>Objectives should be achievable within a specific time frame that isn't so soon as to prevent success, or so far away as to encourage procrastination. </p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="time" type="button">Next</button>
            </div>
        </div>
    </div>
</div>
<div id="parq-step5" class="modal fade" role="dialog">
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
<script>
    $(document).on('click','.parq-step5',function(){
        $(this).attr('data-toggle','modal')
        $(this).attr('data-target','#parq-step5')
        $("#parq-step5").attr('aria-modal',true)
        $("#parq-step5").addClass('in')
        var message = $(this).data('message');
        $("#parq-step5").find('.message').html(message);
    })

    $(document).on('click','.waiverModalClose',function(){
        $('#waiverModal').hide();
    })
</script>
@endsection
@section('required-script')
@stop