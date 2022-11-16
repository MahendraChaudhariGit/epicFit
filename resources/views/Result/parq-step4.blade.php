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
      <span class="inmotion-ts-active-all all-question-step">9</span>
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
             <img src="{{asset('result/images/step-four.png')}}" alt="" class="img-fluid slide-img">
             
         </div>
         <img id="pot" src="{{asset('assets/images/h1-slider-img-1.png')}}" alt="" class="img-fluid">
         <!-- /content-left-wrapper -->
     </div>
     <!-- /content-left -->
     <div class="col-xl-6 col-lg-5 col-md-5 col-xs-12 content-right" id="start">
        <div id="wizard_container">
            <div id="top-wizard">
                <h2 class="steps-name wizard-header">PARQ</h2>
                     <!--    <span id="location"></span>
                        <div id="progressbar"></div> -->
                    </div>
                    <!-- /top-wizard -->
                    <form id="wrapped" method="post" enctype="multipart/form-data">
                        <input id="website" name="website" type="text" value="">
                        <!-- Leave for security protection, read docs for details -->
                        <div id="middle-wizard">
                            <div class="step" data-index="0">
                             <div class="row">

                              <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

                          </span>
                          <div class="heading-text border-head mb-10">
                              <div class="watermark1"><span>0.</span></div>
                              <label class="steps-head">Have you ever had a <b>stroke</b> or heart <b>condition</b> or has a physician ever indicated you should <b>restrict your physical activity</b> due to these conditions?   </label>
                          </div>
                          <div class="tooltip-sign mb-10">
                              <a href="javascript:void(0)" class="parq-step4 " data-message="A stroke occurs when the blood supply to part of your brain is interrupted or reduced, preventing brain tissue from getting oxygen and nutrients. Brain cells begin to die in minutes.
                               <br/><br/>
                              A stroke is a medical emergency, and prompt treatment is crucial. Early action can reduce brain damage and other complications. Lifestyle risk factors that can increase your risk of a stroke include: 
                              <br/><br/>
                              <b>•</b> Being overweight or obese,<br/> 
                              <b>•</b> Physical inactivity, <br/>
                              <b>•</b> Heavy drinking, <br/>
                              <b>•</b> Use of illegal drugs, <br/>
                              <b>•</b> Medical risk factors, <br/>
                              <b>•</b> High blood pressure, <br/>
                              <b>•</b> Cigarette second-hand smoke exposure,<br/> 
                              <b>•</b> High cholesterol, <br/>
                              <b>•</b> Diabetes, <br/>
                              <b>•</b> Obstructive sleep apnea,<br/> 
                              <b>•</b> Cardiovascular disease, including heart failure, heart defects, heart infection or abnormal heart rhythm, such as atrial fibrillation etc.
                               <br/><br/>
                              Personal or family history of stroke, heart attack or transient ischemic attack can also increase your risk. Anyone can suffer a stroke but generally people over the age of 55, have a higher chance compared to someone younger than 55. Lifestyle habits also play a large role in your risk factor regarding strokes.  
                              <br/><br/>
                              Stokes can, however, be prevented with amongst many things, a healthy lifestyle, including regular physical activity, a healthy & balanced nutrition plan & controlled stress & sleep levels."><i class="fa fa-question-circle question-mark"></i></a>

                          </div>

                      </div>
                      <h2 class="section_title">
                      </h2>
                      <div class="form-group">
                        <label class="container_radio version_2">Yes
                            <input type="radio" name="ans0" class="medication" value="ansYes0"{{in_array('ansYes0', $parq->questionnaire)?'checked':''}}>
                            <span class="checkmark"></span>
                        </label>
                        <label class="container_radio version_2">No
                            <input type="radio" name="ans0" class="medication" value="ansNo0" {{in_array('ansNo0', $parq->questionnaire)?'checked':''}}>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
                <!-- /step-->
                <div class="step" data-index="1">
                 <div class="row">

                  <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

              </span>
              <div class="heading-text border-head mb-10">
                  <div class="watermark1"><span>1.</span></div>
                  <label class="steps-head">When at <b>rest or partaking</b> in physical activity do you experience chest pain?   </label>
              </div>
              <div class="tooltip-sign mb-10">
                <a href="javascript:void(0)" class="parq-step4" data-message="The first thing you may think of is a heart attack. Certainly, chest pain is not something to ignore. But you should know that it has many possible causes. In fact, numerous individuals’ experiences chest pain that is not even related to the heart. Chest pain may also be caused by problems in your lungs, oesophagus, muscles, ribs, or nerves.
                 <br/><br/>
                You may feel chest pain anywhere from your neck to your upper abdomen. Depending on its cause, chest pain may be:
                <br/><br/>
                <b>•</b> Sharp<br/>
                <b>•</b> Dull<br/>
                <b>•</b> Burning<br/>
                <b>•</b> Aching<br/>
                <b>•</b> Stabbing<br/>
                <b>•</b> A tight, squeezing, or crushing sensation
                 <br/><br/>
                It may seem like nothing to you but, as mentioned earlier, it could be the first sign of something else. The earlier we can detect something, the faster we can find a cure or start with preventative treatment."><i class="fa fa-question-circle question-mark"></i></a>

            </div>

        </div>
        
        <div class="form-group">
            <label class="container_radio version_2">Yes
                <input type="radio" name="ans1" class="medication" value="ansYes1" {{in_array('ansYes1', $parq->questionnaire)?'checked':''}}>
                <span class="checkmark"></span>
            </label>
            <label class="container_radio version_2">No
                <input type="radio" name="ans1" class="medication" value="ansNo1" {{in_array('ansNo1', $parq->questionnaire)?'checked':''}}>
                <span class="checkmark"></span>
            </label>
        </div>
    </div>

    <!-- /Start Branch ============================== -->
    <div class="step" data-index="2">
      <div class="row">

          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

      </span>
      <div class="heading-text border-head mb-10">
          <div class="watermark1"><span>2.</span></div>
          <label class="steps-head">During physical activity do you ever feel <b>faint, dizzy or lose</b> your balance?   </label>
      </div>
      <div class="tooltip-sign mb-10">
          <a href="javascript:void(0)" class="parq-step4" data-message="If a person regularly feels sensations of dizziness, light headedness, floating sensations, or fainting they need to fill in a medical clearance form.
           <br/><br/>
          Although it is common for some people to feel faint after a high intensity physical activity or after standing up too quickly, it should not be ignored. Low/high blood pressure is also a major cause for these feelings of dizziness and/or fainting.
           <br/><br/>
          The concern however increases when these feelings occur during rest, or regularly, while not exerting any energy. 
           <br/><br/>
          Many individuals have felt dizzy at one point or more in their life. Please list these all, we may be able to find a commonality and avoid this situation, where possible. Often, with physical activity, exertion levels and elevation can play a large role in dizziness and balance issues. 
          <br/><br/>
          The last thing we want is for you to fall over during a training session, so share all details you have regarding anytime you have ever felt dizzy, fainted, or lost your balance."><i class="fa fa-question-circle question-mark"></i></a>

      </div>

  </div>
  
  <div class="form-group">
    <label class="container_radio version_2">Yes
        <input type="radio" name="ans2" class="medication" value="ansYes2"{{in_array('ansYes2', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">No
        <input type="radio" name="ans2" class="medication" value="ansNo2"{{in_array('ansNo2', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>

<!-- /Work Availability > Full-time ============================== -->
<div class="step" data-index="3">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>3.</span></div>
      <label class="steps-head">Do you suffer from any <b>breathing disorders or suffered </b> a severe case of asthma that has medical attention in the last 12 months?  </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step4" data-message="Asthma is a chronic lung inflammatory disease that narrows the airways. This narrowing of the airways restricts air flow to the lungs and causes: wheezing, coughing, chest tightness and a shortness of breath. When these symptoms intensify it is known as an asthma attack. 
    <br/><br/>
   The important thing for us to know is whether you have had an asthma attack recently (within the last 12 months) which required immediate medical attention and how frequently you experience attacks. 
    <br/><br/>
   There are many things that trigger asthma and medical practitioners warn asthma sufferers to avoid. One trigger that should not be avoided however is physical activity as it is an especially important factor in living a healthy lifestyle. 
    <br/><br/>
   Many asthma sufferers are highly active, and we encourage that for a healthy lifestyle. We always suggest bringing your inhaler along to all physical activities. It may also help to change training times to best suit your asthma if need be. 
    <br/><br/>
   Often in winter, evenings are better training times as mornings can be cold and in summer, high humidity should also try being avoided. We want you huffing and puffing when you are training but not in a bad way!"><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <label class="container_radio version_2">Yes
        <input type="radio" name="ans3" class="medication" value="ansYes3"{{in_array('ansYes3', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">No
        <input type="radio" name="ans3" class="medication" value="ansNo3"{{in_array('ansNo3', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<!-- /step-->

<!-- /Work Availability > Part-time ============================== -->
<div class="step" data-index="4">
  <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>4.</span></div>
      <label class="steps-head">Do you have insulin dependant <b>diabetes or high blood sugar </b> that has caused complication in the last three months?  </label>
  </div>
  <div class="tooltip-sign mb-10">
    <a href="javascript:void(0)" class="parq-step4" data-message="Diabetes is a chronic condition where the body produces too much glucose (sugar) in the blood. This is a result of the pancreas being unable to produce enough insulin or when the body cannot effectively utilise the insulin it produces. 
     <br/><br/>
    <b>TYPE 1</b>
    This is when a person makes little or no insulin at all. This results in the body being unable to use glucose for energy. The person will lose weight as the body is in effect starving itself. If you have Type 1 Diabetes, ALWAYS bring your insulin sweets/medication along to all physical activity sessions. We may also need to look at your eating habits so we can be confident you are doing the best across the board to stay safe at the same time as being healthy & active.
     <br/><br/>
    <b>TYPE 2</b>
    When a person does produce insulin but at a much slower rate than most the body resists insulin and therefore the person usually becomes overweight, which can be the start of type 2 diabetes. Starting a lifestyle design process is probably the best thing you can do for your health and life if you have Type 2 diabetes. 
    <br/><br/>
    We will need to address all aspects of your life as this disease takes a big change on a physical, emotional, and mental level. We can help you overcome mostly anything if you put your mind to it. Type 2 diabetes is a lifestyle related disease that can be reversed with a healthy lifestyle."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <label class="container_radio version_2">Yes
        <input type="radio" name="ans4" class="medication" value="ansYes4"{{in_array('ansYes4', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">No
        <input type="radio" name="ans4"class="medication"  value="ansNo4"{{in_array('ansNo4', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<!-- /step-->

<!-- /Work Availability > Freelance-Contract ============================== -->
<div class="step" data-index="5">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>5.</span></div>
      <label class="steps-head">Do you have an <b>injury or orthopaedic</b> condition (such as a <b>back, hip or knee problem</b>) that may worsen due to a change in your physical activity? </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step4" data-message="Orthopaedics relates to the musculoskeletal system (muscles and bones). Most people will experience slight muscle aches during and after certain physical activities. Some people, however, might, have a chronic condition that causes unpleasant pains during simple daily activities. It is important to know of any chronic conditions as they will most definitely increase the risk of injury with a physical activity program.
    <br/><br/>
   Some possibilities of these conditions include but are not restricted to:
    <br/><br/>
   <b>.</b>  Bone fracture<br/>
   <b>.</b>  Cerebral palsy<br/>
   <b>.</b>  Chronic muscle fatigue<br/>
   <b>.</b>  Dislocations<br/>
   <b>.</b>  Joint replacement<br/>
   <b>.</b>  Multiple sclerosis <br/>
   <b>.</b>  Muscular dystrophy <br/>
   <b>.</b>  Scoliosis <br/>
   <b>.</b>  Serious sprains or strains<br/>
   <b>.</b>  Parkinson’s disease <br/>
   <b>.</b>  ACL/MCL/LCL surgery<br/>
   <b>.</b>  Spondylolisthesis <br/>
   <b>.</b>  Spondylolysis 
    <br/><br/>
   Please list ANYTHING you have or think you may have. We know many individuals who have suffered with ailments for too long without being accurately diagnosed. We do not want that for you, so let us know any symptoms or sensations you experience and when. 
   <br/><br/>
   Let us fix you, together."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <label class="container_radio version_2">Yes
        <input type="radio" name="ans5" class="medication" value="ansYes5"{{in_array('ansYes5', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">No
        <input type="radio" name="ans5" class="medication" value="ansNo5"{{in_array('ansNo5', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step" data-index="6">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>6.</span></div>
      <label class="steps-head">Are you <b>pregnant</b> or have you <b>given birth</b> in the last 6 weeks? </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step4" data-message="Women are always advised to perform physical activities with caution during and after pregnancy. It is advisable for woman to be active during and after pregnancy but there is an increased risk of complications to the mother, foetus, or both during pregnancy. Appropriate activity prescription, however, will eliminate risks and improve health of both mum and baby.
       <br/><br/>
      Physical activity and nutritional recommendations are heavily dependent on the trimester you are in, health of your pregnancy, pregnancy, and family health history. It is critical you provide as much information as possible including but not limited to:
       <br/><br/>
      <b>.</b> Date of pregnancy <br/>
      <b>.</b> Any supplements or medication<br/>
      <b>.</b> Personal and family history of any previous births/pregnancies <br/>
      <b>.</b> Suggestion/prescription given by treating physician regarding lifestyle
        <br/><br/>
      Please always notify your trainer IMMEDIATELY after discovery of pregnancy, should it happen, so your program to be adapted as soon as possible.
       <br/><br/>
      That little baby needs to learn to adjust to your life and it is easily done. Never give up activity when you fall pregnant unless advised by your physician. We want to help with not only keeping you strong and sane during pregnancy but strong after birth ensuring you can jump back into activity as soon as it is safe!"><i class="fa fa-question-circle question-mark"></i></a>

  </div>

</div>
<div class="form-group">
    <label class="container_radio version_2">Yes
        <input type="radio" name="ans6" class="medication" value="ansYes6"{{in_array('ansYes6', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">No
        <input type="radio" name="ans6" class="medication" value="ansNo6"{{in_array('ansNo6', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step" data-index="7">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>7.</span></div>
      <label class="steps-head">Are you over the <b>age of 69</b>? </label>
  </div>
  <div class="tooltip-sign mb-10">
    <a href="javascript:void(0)" class="parq-step4" data-message="Age is connected to the risk of having a disease or even dying. Age gives your <span style='color: #f94211'><b>EPIC</b> Trainer</span> an indication of how prone the person is to negative effects associated with beginning a physical activity program. 
         <br/><br/>
        Age allows your <span style='color: #f94211'><b>EPIC</b> Trainer</span> to design a program with appropriate intensity levels for the client. No person is too old to start a physical activity program, as long as the most appropriate program is prescribed and monitored. If you are over 69, we have to evaluate which physical activity program is best suited for your current ability.
         <br/><br/>
        Progressive strength training in the elderly is efficient, even with higher intensities, to reduce sarcopenia, and to retain motor function.
         <br/><br/>
        With the elderly, weight training is the best form of activity as it forces the muscles to work against gravity which in turn makes the person stronger, more flexible, it increases bone density and has a positive effect on mental and emotional wellbeing."><i class="fa fa-question-circle question-mark"></i></a>

    </div>

</div>

<div class="form-group upload-group">
    <label class="container_radio version_2">Yes
        <input type="radio" name="ans7" class="medication" value="ansYes7"{{in_array('ansYes7', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">No
        <input type="radio" name="ans7" class="medication" value="ansNo7"{{in_array('ansNo7', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="step" data-index="8">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>8.</span></div>
      <label class="steps-head">Do you know of any other reason why you <b>should not partake</b> in, or increase your current physical activity? </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step4" data-message="Are there any other medical related health risks or concerns you might have or are experiencing that were not queried about in the PAR-Q? These could include but are not limited to:
      <br/><br/>
      <b>.</b> Acute injuries<br/>
      <b>.</b> Artificial limbs<br/>
      <b>.</b> Balance issues<br/>
      <b>.</b> Cancer<br/>
      <b>.</b> Epilepsy<br/>
      <b>.</b> Transplants<br/>
      <b>.</b> Other, please specify
       <br/><br/>
      If the trainer deems it necessary, they can insist on a medical clearance from the client’s medical practitioner before commencing any physical activity program."><i class="fa fa-question-circle question-mark"></i></a>

  </div>

</div>

<div class="form-group upload-group">
    <label class="container_radio version_2">Yes
        <input type="radio" name="ans8" class="medication" value="ansYes8"{{in_array('ansYes8', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">No
        <input type="radio" name="ans8" class="medication" value="ansNo8"{{in_array('ansNo8', $parq->questionnaire)?'checked':''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<div class="submit step" id="end">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>9.</span></div>
      <label class="steps-head">Please provide any <b>additional notes</b> you think are relevant  </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step4" data-message="Please provide any additional notes you may have relating to your personal PARQ. IF you would like us to get in contact with any treating physician, please list their details here along with relevant notes.
       <br/><br/>
      Remember, the more you detail, the more information we can work with and the better we can get you from where you are, to where you want to be."><i class="fa fa-question-circle question-mark"></i></a>

  </div>

</div>


<div class="form-group upload-group">
    <textarea rows="1" id="parqNotes" name="parqNotes" placeholder="" class="form-control">{{ isset($parq)?$parq->parqNotes : null }}</textarea>
</div>
</div>
</div>
<!-- /middle-wizard -->
<div id="bottom-wizard">
 <span class="qodef-grid-line-center"><span class="inmotion-total-slides">
  <div class="d-flex">
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" data-step-no="3" class="step-back"><span class="prev-name">Injury Profile & Family History</span></a>&nbsp;&nbsp;
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" data-step-no="3" class="arrow step-back">&#8672;</a>               
    <div class="current-section">PARQ</div>
    <a href="#"  data-step-url="{{ url('epicprogress/AssessAndProgress/GoalAndMotivation') }}" data-step-no="5" class="arrow step-forward">&#8674;</a>&nbsp;&nbsp;
    <a href="#"  data-step-url="{{ url('epicprogress/AssessAndProgress/GoalAndMotivation') }}" data-step-no="5" class="step-forward"><span class="next-name">GoalS & Motivation</span></a>
    {{-- <a class="prev-name redirect_prev_page" href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/IllnessAndInjury') }}" data-step-no="3">
        <span>Injury Profile & Family History &nbsp;&nbsp;</span>
        <span  class="arrow step-back">&#8672;</span> 
    </a>              
    <div class="current-section">PARQ</div>
    <a class="next-name redirect_next_page" href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/GoalAndMotivation') }}" data-step-no="5">
        <span  class="arrow step-forward">&#8674;</span>
        <span>&nbsp;&nbsp; GoalS & Motivation</span>
     </a> --}}
</div>

<span class="inmotion-ts-active-num section-step">04</span>
<span class="inmotion-ts-active-separator">/</span>
<span class="inmotion-ts-active-all all-section-step">05</span>
</span>
<span class="inmotion-total-slides visible-xs question-s">QUESTIONS<br>
    <span class="inmotion-ts-active-num question-step">5</span>
    <span class="inmotion-ts-active-separator">/</span>
    <span class="inmotion-ts-active-all">09</span>
</span>
<span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
<div class="row">
    <div class="col-sm-5 col-xs-5"><button type="button" name="backward" class="backward">Prev</button></div>
    <div class="col-sm-7 col-xs-7">
      
       <button type="button" name="forward" class="forward">Next</button>
       <button type="button" class="submit submit-step" data-step-url="{{ url('epicprogress/AssessAndProgress/GoalAndMotivation') }}" data-step="4">Submit</button>
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
</div>
<div class="fusion-modal modal  modal-1 avada_modal" tabindex="-1" role="dialog" aria-labelledby="modal-heading-1" aria-hidden="true">
    <style>
    .fusion-modal .fusion-modal-content{background-color:#f6f6f6}
    .fusion-modal .close{display:initial}
    .fusion-modal .fusion-button{display:initial}
    .fusion-modal #mcfTosmc{cursor:pointer}
</style>
<div class="modal-dialog">
    <div class="modal-content fusion-modal-content">
        <div class="modal-header" style="padding-bottom: 30px;">
            <button class="close awadaModalClose" type="button" data-dismiss="modal" aria-hidden="true" style="margin-top: -5px;">&times;</button>
            <h3 class="modal-title" id="modal-heading-1" data-dismiss="modal" aria-hidden="true"></h3>
        </div>
        <div class="modal-body">
            <div id="mcf">
                <h1 class="text-center"><span style="color:#333333;">Medical Clearance Form</span></h1>
                <div id="print_from" class="avada-row">
                    <h4>Dear Doctor:</h4>
                    <div class="span">
                        Your patient ________________________________________. wishes to take part in an exercise program and, or fitness assessment. the exercise program may include progressive resistannce training,frexibility exersices, and a cradiovascular program; increasing in duration and intensity over time. The fitness assessment may include a sub-maximal cardiovascular fitness test and measurements of body composition, flexibility, and muscular strength and endurance.
                        <br />
                        After completing a readiness questionnaire and discussing their medical condition(s) we agreed to seek your advise in setting limittations to their program. By completing this form, you are not assuming any responisbility for our exercise and assessment program. Please identify any recommendations or restrictions for your patient's fitness program below (Physician's recommendations).
                        <div class="seprator"></div>
                        <h2>Patient's Consent and Authorization</h2>
                        I consent to and authorize_________________________________. to release to _____________________________, health information concerning my ability to participate in an exerercise program and/or fitness assessment. I understand this consent is revicable except to the extent action has of my health information is prohibited without specific written consent of person to whom it pertains.
                        <div style="margin-top:16px;margin-bottom:10px;"></div>
                        <table width="100%" cellspacing="0" cellpadding="5" border="1" class="table">
                            <tr>
                                <td>Member's Signature</td>
                                <td>Date</td>
                            </tr>
                            <tr>
                                <td>Trainer's Signature</td>
                                <td>Date</td>
                            </tr>
                        </table>
                        <div style="margin-top:10px;margin-bottom:10px;"></div>
                        <h1>Physician's Recommendations</h1>
                        <div style="margin-top:10px;margin-bottom:10px;"></div>
                        <table width="100%" cellspacing="0" cellpadding="5" border="1" class="table">
                            <tr>
                                <td></td>
                                <td>I am not aware of any contraindicaions toward participation in a fitness program.</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>I believe the applicant can participate, but unge caution because.</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>The applicant should not engage in the following activities:</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>I recommend the applicant not participate in the above fitness program.</td>
                            </tr>
                        </table>
                        <div style="margin-top:20px;margin-bottom:10px;"></div>
                        <table width="100%" cellspacing="0" cellpadding="5" border="1" class="table">
                            <tr>
                                <td colspan="2">Physician's signature</td>
                                <td>Date</td>
                            </tr>
                            <tr>
                                <td>Physician's name (Print)</td>
                                <td>Phone</td>
                                <td>Fax</td>
                            </tr>
                            <tr>
                                <td>Address </td>
                                <td>City</td>
                                <td>State &amp; Zip</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!--- Medical seek medical clearance-->
            <div id="smc" style="display: none;">
                <h1 style="text-align: center;"><span style="color: #333333;">Medical Clearance Form</span></h1>
                <div class="avada-row">
                    <div class="span">
                        <p>I, the participant have agreed to participate in an Epic Fitness T.E.A.M or Personal Training Session, which may take place both indoors and/or outdoors.<br /></p>
                        <p>The activities of Epic Fitness T.E.A.M or Personal Training include strength training, running, jumping. intense cardiovascular activities, and flexbility training. Acknowledgement is hereby made that the activities of the program, or training, may have me spend time outside in the heat and cold, as well as inside.<br /></p>
                        <p>I further acknowledge that there are risks involved with participation in a training program and I have no debilitating injuries or history thereof, that may result in injury. The risks include, but are not limited to, those caused by terrain,facilites, temparature,weather, my physical condition,equipment, lack of hydration, actions of other people including, but not limited to participants and volunteers.
                            <br />
                        </p>
                        <p>In consiteraion to my acceptance into the program and my paticipation, I agree to release and discharge Epic Fitness Limited tand any of its employees, volunteers and supervisors, owners and shareholders of the business, from any injuries sustained by me as a result of my particitaion in the program.
                            <br />
                        </p>
                        <p>I agree to indentify and hold harmless Epic Fitness, and any of its employees, volunteers and supervisors, owners and shareholders of the Epic Fitness Limited against any liabilty incurred as a result of such inury or loss.
                            <br />
                        </p>
                        <p>Fitness activities and programs require I be in good health and have no conditions that could endanger my well being through participation. I will notify Epic Fitness of any such conditions in writing prior to enrolling in a fitness program with Epic Fitness.
                            <br />
                        </p>
                        <p> I acknowledge that Epic Fitness has advised me to seek approval form medical practitioner prior to participating in a fitness program.
                            <br />
                        </p>
                        <p>Clients may no occasion e photographed during training with Epic Fitness. The undersighned hereby consants to the use of photographs without compensation on any of the Epic Fitness Limited web sites or in any editorial or promotional material produce and/or published by or for Epic Fitness Limited.
                            <br />
                        </p>
                        <p>The undersigned agrees to save and hold harmless and indemnify each and all of the parties referred to above form all liability, loss, cost, claim, or damage whatsover which may be imposed upon said parties because of any defactin or lack of capacity to so act and release said parties on behalf of myself.
                            <br />
                        </p>
                        <div>Name of Client: <input type="text" name="nameOfClient" value=""> </div>
                        <div>Name of Participant: <input type="text" name="nameOfParticipant" value=""> </div>
                        <div>Signature of Participant: <input type="text" name="signatureOfParticipant" value=""> </div>
                        <div>Signature of parent or Gaurdian(If under age 18): <input type="text" name="SignatureOfgaurdian" value=""> </div>
                        <div>Date: <input type="text" name="date" value=""> </div>
                        <div> <input type="checkbox" name="termNcond" value="1" style="width: auto;"> Agree to our terms and conditions.</div>
                    </div>
                </div>
            </div>
            <!--- Medical seek medical clearance-->
            <div class="fusion-sep-clear"></div>
            <div class="fusion-separator sep-single" style="border-color:#cdcdcd;margin-top:15px;margin-bottom:10px;">
            </div>
            <div>
                <!-- <div class="span2"><a class="fusion-button button-default button-medium button default medium" type="button" target="_blank" title="" href="#" data-toggle="modal" data-target=".modal2"><span style="font-family: arial;" class="fusion-button-text">Email</span></a></div>-->
                <!--<div class="span2"><a  href="{{ asset('medical_clearance_form.pdf') }}" target="_blank" type="button" class="fusion-button button-default button-medium button default medium"><span style="font-family: arial;" class="fusion-button-text">Save</span></a></div>
                    <div class="span2"><a style="font-family: arial;" class="fusion-button button-default button-medium button default medium" type="button" id="mcfTosmc">Close</a></div>-->
                    <div class="span2">
                        <a href="{{ asset('medical_clearance_form.pdf') }}" target="_blank" type="button" class="btn btn-sm btn-primary"><span style="font-family:arial;" class="fusion-button-text">Print</span></a>
                        <a href="{{ asset('medical_clearance_form.pdf') }}" download type="button" class="btn btn-sm btn-primary"><span style="font-family:arial;" class="fusion-button-text"> Save as PDF</span></a>
                        <button class="btn btn-sm btn-primary" data-dismiss="modal"><span style="font-family:arial;" class="fusion-button-text">Email</span></button>
                        <button style="font-family:arial;" class="btn btn-sm btn-primary awadaModalClose" data-dismiss="modal">Close</button><!-- id="mcfTosmc" -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fusion-sep-clear"></div>
</div>

<div class="fusion-modal modal fade modal-3 modal2 in" tabindex="-1" role="dialog" aria-labelledby="modal-heading-3" aria-hidden="false">
    <style>.modal-3 .modal-header, .modal-3 .modal-footer{border-color:#ebebeb}</style>
    <div style="width: 652px;" class="modal-dialog modal-lg">
        <div class="modal-content fusion-modal-content">
            <div class="modal-body">
                <div class="modal-header">
                    <button class="close awadaModalClose" type="button" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title" id="modal-heading-3" data-dismiss="modal" aria-hidden="true">Email Send Form</h3>
                </div>
                <div id="befor_mail">
                    <input type="text" style="width: 302px;border: 3px solid #ededed; border-radius: 3px;box-shadow: none;min-height: 50px;padding: 0 15px;"id="to_eaddr" name="to_eaddr" value="" class="input-xlarge" placeholder="To Email"><a style=" height: 45px;line-height: 40px;margin-top: -12px; vertical-align: middle;" class="fusion-button button-default button-medium button default medium" onClick="showpop();" type="button" >Send</a>
                    <div style="color: red; float: left;width:100%" id="email_error"></div>
                </div>
                <div id="after_mail"></div>
            </div>
        </div>
    </div>
</div>
<div id="parq-step4" class="modal fade" role="dialog">
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
    $(document).on('click','.parq-step4',function(){
        $(this).attr('data-toggle','modal')
        $(this).attr('data-target','#parq-step4')
        $("#parq-step4").attr('aria-modal',true)
        $("#parq-step4").addClass('in')
        var message = $(this).data('message');
        $("#parq-step4").find('.message').html(message);
    })
</script>
@endsection

@section('required-script')

@stop