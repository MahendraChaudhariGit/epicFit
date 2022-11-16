@extends('Result.profile_details')
@section('required-styles')
<style>
.pac-container{
    z-index: 9999999!important;
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
      <span class="inmotion-ts-active-all all-question-step">14</span>
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
         <img src="{{asset('result/images/step-one.jpg')}}" alt="" class="img-fluid">

     </div>
     <img id="pot" src="{{asset('assets/images/h1-slider-img-1.png')}}" alt="" class="img-fluid">
     <!-- /content-left-wrapper -->
 </div>
 <!-- /content-left -->
 <div class="col-xl-6 col-lg-5 col-md-5 col-xs-12 content-right" id="start">
    <div id="wizard_container">
        <div id="top-wizard">
           <h2 class="steps-name wizard-header">PERSONAL DETAILS</h2>
                        <!-- <span id="location"></span>
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
                                      <label class="steps-head">Where did you hear about EPIC?  </label>
                                  </div>
                                  <div class="tooltip-sign mb-10">
                                   <a href="javascript:void(0)" class="parq-step1" 
                                   data-message="Letting us know how you heard about <span style='color: #f94211'><b>EPIC</b> RESULT</span> allows us to build stronger relationships with our affiliates, as well as allowing us to reward your friends and family members for helping to grow <span style='color: #f94211'><b>EPIC</b></span> and help get as many people as possible on a positive Lifestyle Design Journey. 
                                   <br/><br/>
                                   Through our referral reward system, you will also find there is not only a benefit for those you help get started on their journey but also for you in spreading the message and being rewarded in the form of prizes and <span style='color: #f94211'><b>EPIC</b> Cash*</span>.
                                   <br/><br/>
                                   Where you are referred from is important as it reflects the effectiveness of either a partnership or an existing client relationship we have. It also allows us to reward those that share <span style='color: #f94211'><b>EPIC</b> RESULT</span> and help us reach our vision of creating a healthier community.
                                   <br/><br/>
                                   If you love us, share us with your friends and family too.">
                                   <i class="fa fa-question-circle question-mark"></i></a>

                               </div>

                           </div>

                           <div class="form-group">
                            <label class="container_radio version_2">Online & Social Media
                                <input type="radio" name="referrer" value="onlinesocial" {{isset($parq) && $parq->hearUs == 'onlinesocial' ? 'checked' : ''}}>
                                <span class="checkmark"></span>
                            </label>
                            <label class="container_radio version_2">Media & Promotions
                                <input type="radio" name="referrer" value="mediapromotions" {{isset($parq) && $parq->hearUs == 'mediapromotions' ? 'checked' : ''}}>
                                <span class="checkmark"></span>
                            </label>
                            <label class="container_radio version_2">Referral
                                <input type="radio" name="referrer" value="referral" {{isset($parq) && $parq->hearUs == 'referral' ? 'checked' : ''}}>
                                <span class="checkmark"></span>
                            </label>
                            <label class="container_radio version_2">Other
                                <input type="radio" name="referrer" value="socialmedia" {{isset($parq) && $parq->hearUs == 'socialmedia' ? 'checked' : ''}}>
                                <span class="checkmark"></span>
                            </label>
                            <div class="row fromWhere {{isset($parq) && ($parq->hearUs == 'socialmedia' || $parq->hearUs == 'mediapromotions' || $parq->hearUs == 'onlinesocial') ? '' : 'hide'}}">
                                <div class="col-sm-12 col-xs-12">
                                    <h3 class="m-b-5 m-t-15">
                                        <span></i> From <b>where</b>?</span>
                                    </h3>
                                    <?php 
                                    $fieldData = '';
                                    if(isset($parq)){
                                        if($parq->hearUs == 'onlinesocial' || $parq->hearUs == 'mediapromotions')
                                            $fieldData = $parq->referencewhere;
                                        elseif($parq->hearUs == 'socialmedia')
                                            $fieldData = $parq->referrerother; 
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            <textarea rows="1" name="referencewhere"  placeholder="" class="form-control">{{ $fieldData }}</textarea>
                                        </div>
                                    </div>

                                    {{--<p class="pli-18 m-b-0 press-enter">Press Shift+Enter for line break</p>--}}

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /step-->
                    <div class="step referredCompany exclude">
                       <div class="row">

                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

                      </span>
                      <div class="heading-text border-head mb-10">
                          <div class="watermark1"><span>1.</span></div>
                          <label class="steps-head">Which <b>company or <br>person</b> referred you to EPIC?  </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                         <a href="javascript:void(0)" class="parq-step1" data-message="Not only does this help us to reward your friends, family, and greater network for referring you but it also helps us learn a little more about you. What places you frequent and where we have mutual relationships. It also allows us the ability to connect you with like-minded individuals and affiliates in our network building your support network.">
                            <i class="fa fa-question-circle question-mark"></i></a>

                        </div>

                    </div>

                    <div class="form-group">
                        <label class="container_radio version_2">Client
                            <input type="radio" name="referralNetwork" value="Client" {{isset($parq) && $parq->referralNetwork == 'Client' ? 'checked' : ''}}>
                            <span class="checkmark"></span>
                        </label>
                        <label class="container_radio version_2">Staff
                            <input type="radio" name="referralNetwork" value="Staff"{{isset($parq) && $parq->referralNetwork == 'Staff' ? 'checked' : ''}}>
                            <span class="checkmark"></span>
                        </label>
                        <label class="container_radio version_2">Professional network
                            <input type="radio" name="referralNetwork" value="Professional network" {{isset($parq) && $parq->referralNetwork == 'Professional network' ? 'checked' : ''}}>
                            <span class="checkmark"></span>
                        </label>
                        {{-- <label class="container_radio version_2">Other
                            <input type="radio" name="referralNetwork" value="Other">
                            <span class="checkmark"></span>
                        </label> --}}


                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <textarea rows="1" name="ReferralName" placeholder="" class="form-control">{{ isset($parq)?$parq->ref_Name : null }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /Start Branch ============================== -->
                <div class="step">
                    <div class="row">

                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

                  </span>
                  <div class="heading-text border-head mb-10">
                      <div class="watermark1"><span>2.</span></div>
                      <label class="steps-head">What is your first name?  </label>
                  </div>
                  <div class="tooltip-sign mb-10">
                      <a href="javascript:void(0)" class="parq-step1" data-message="Please provide your registered/ legal first name as the complete online consultation form equates to a legal medical document and requires accurate personal details and contact information. 
                       <br/><br/>
                      If you have a preferred name or nickname, we want to know that as it creates the foundation for a better relationship between yourself, your trainer, and your network.
                       <br/><br/>
                      We will become like a family, seeing one another sometimes more than you may some family members. The better acquainted we become the more of a stronger and trusting relationship we can build.
                       <br/><br/>
                      Please provide your legal first name, followed by your preferred name in brackets if you so wish to provide one. ">
                      <i class="fa fa-question-circle question-mark"></i></a>

                  </div>

              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="firstName" value="{{ isset($parq)?$parq->firstName : null }}" name="firstName" required data-realtime="firstName">
            </div>
        </div>

        <!-- /Work Availability > Full-time ============================== -->
        <div class="step">
            <div class="row">

              <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

          </span>
          <div class="heading-text border-head mb-10">
              <div class="watermark1"><span>3.</span></div>
              <label class="steps-head">What is your last name?  </label>
          </div>
          <div class="tooltip-sign mb-10">
           <a href="javascript:void(0)" class="parq-step1" data-message="Please provide your registered/ legal last name as the complete online consultation form equates to a legal medical document and requires accurate personal details and contact information. 
            <br/><br/>
           If your surname is your maiden name or married name, we would like to know. Some women change their surnames and if other family members join <span style='color: #f94211'><b>EPIC</b></span> too, we are then able to make the connections even if the surnames differ.
            <br/><br/>
           If you are registered with a medical practitioner under a different name or wish to provide your maiden name for example, please enter your legal name first, followed by your preferred last name in brackets. "><i class="fa fa-question-circle question-mark"></i></a>

       </div>

   </div>

   <div class="form-group">
    <input type="text" class="form-control" id="lastName" value="{{ isset($parq)?$parq->lastName : null }}" name="lastName" data-realtime="lastName">
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
      <label class="steps-head">I Identify my gender as* </label>
  </div>
  <div class="tooltip-sign mb-10">
     <a href="javascript:void(0)" class="parq-step1" data-message="Your health and wellness programs are tailored to you personally, gender is particularly important because men and woman require quite different training methods and their daily needs in all aspects of health & Wellness vary considerably. 
      <br/><br/>
     If you are gender neutral, please select your birth gender for genetic reasons as we use measurement data technology that calculates based on genetic gender. You may identify as a different gender or may not wish to identify specifically at all; however, it is essential for us to understand your birth genetics so we can develop the best suited health and wellness program for you. 
      <br/><br/>
     Gender is also important, especially with online consultations forms as some names are gender neutral like Luka, Sam & Ashley. If we know what gender you are we can be better prepared when meeting you for the first time.
      <br/><br/>
     You can in the notes section provided at the end of this page disclose any further information regarding gender you may wish for <span style='color: #f94211'><b>EPIC</b></span> to account for.">
     <i class="fa fa-question-circle question-mark"></i></a>

 </div>

</div>
<div class="form-group radio_input">
    <label class="container_radio mr-3">Male
        <input type="radio" name="gender" value="Male" {{isset($parq) && $parq->gender == 'Male' ? 'checked' : ''}}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio">Female
        <input type="radio" name="gender" value="Female" {{isset($parq) && $parq->gender == 'Female' ? 'checked' : ''}}>
        <span class="checkmark"></span>
    </label>
</div>
</div>
<!-- /step-->

<!-- /Work Availability > Freelance-Contract ============================== -->
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>5.</span></div>
      <label class="steps-head">How tall are you? </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step1" data-message="Your height is needed to calculate BMI and other training and nutritional requirements and If necessary, also to complete your client profile for Heart Rate Training. 
    <br/><br/>
   Providing this information also provides a measure of your starting point allowing you to accurately track your progress throughout your journey as well as helping us to identify any risk factors related to your health. 
    <br/><br/>
   Height is also used in equipment for measurement data analysis. With many individuals wanting to improve posture, it helps to have a record of height. Height changes as posture improves over time, now you can monitor the changes. Your height measurement may change over your lifetime as your height is directly affected by your posture, and musculoskeletal health. 
    <br/><br/>
   Some individuals have also undergone spinal surgery which has changed their height significantly and affected all previous measurement data.
    <br/><br/>
   If you are unsure of your current height, you can provide an estimate. Please indicate in the notes section if you have provided an estimate so we can arrange for an accurate measurement to be taken by one of our <span style='color: #f94211'><b>EPIC</b> TRAINERS</span>. 
    <br/><br/>
   You can switch between imperial and metric measurement units using the icon."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    {{-- <input type="text" name="name" id="name" class="form-control">CM --}}
    <input type="hidden" name="heightUnit" value="{{ isset($parq->heightUnit) && $parq->heightUnit !=null?$parq->heightUnit:'Metric' }}">
    <div class="input-body ml-0 mb">
        <div class="row">
            <div class="col-sm-12 heightDiv col-xs-12">
                <div class="input-group heightMetric {{ isset($parq->heightUnit) && $parq->heightUnit =='Imperial'?'hidden':'' }}" id="metricHeight">
                    <input type="text" name="height_metric" class="form-control custom-width" value="{{isset($parq)?$parq->height:''}}"}} >
                    <span class="input-group-addon">cm</span>

                </div>
                <div class="row heightImperial {{ isset($parq->heightUnit) && ($parq->heightUnit =='Metric' || $parq->heightUnit ==null) ?'hidden':'' }}">
                    <?php 

                    isset($parq) && isset($parq->height)?$height = explode('-',$parq->height):$height=[];
                    ?>

                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="height_imperial_ft" class="form-control custom-width" value="{{$height[0]}}">
                            <span class="input-group-addon">ft</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" name="height_imperial_inch" class="form-control custom-width" value="{{$height[1]}}">
                            <span class="input-group-addon">inch</span>
                        </div>
                    </div>
                </div>
                <span class="help-block m-b-0"></span>
                <button type="button" style="color:#fff;" class="btn btn-primary btn-o btn-sm p-y-0 bg-none mli-10 pull-right show-btn" id="heightUnit">{{isset($parq->heightUnit) && $parq->heightUnit == "Imperial"?"Show Metric":"Show Imperial"}}</button>

            </div>
        </div>


    </div>
</div>
</div>
<div class="step">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>6.</span></div>
      <label class="steps-head">What is your current weight? </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step1" data-message="Your weight is needed to calculate BMI and other training and nutritional requirements and If necessary, also to complete your client profile for Heart Rate Training. 
    <br/><br/>
   Weight can be a touchy subject, but it is important to remember that it is only one unit of measurement that you can select to use as a measure of progress. There is no need to become obsessed with any measurement including weight as they are only one measure of health amongst hundreds of others that may be better suited measures for you. 
   <br/><br/>
   With this point in mind, it is also crucial that you remain realistic and remember that however undesirable weight and BMI calculations may seem they do provide and insight into your overall health and potential risk factors and associated with certain medical conditions. 
    <br/><br/>
   For health reasons it can be an immediate tell-tale sign if you are overweight. Being overweight has so many negative effects on a person physically like injuries, pain & inflammation. It can also have an effect on mental & emotional wellbeing like confidence, insecurity, happiness, job satisfaction, relationship success and so much more. So just this one answer can help lead us towards you goal plan without you even realising it. 
    <br/><br/>
   Providing your weight provides an accurate starting point and measure of your current health. If you are unsure of your current weight, please provide an estimate and indicate you have done so within the notes section provided at the bottom of this page. 
    <br/><br/>
   Our <span style='color: #f94211'><b>EPIC</b> TRAINERS</span> can accurately measure your weight, as well as a full body analysis of bodyfat %, skeletal muscle mass, visceral fat, body measurements, body balance, bone density and more using our Inbody Scan technology. 
    <br/><br/>
   Please indicate in the notes section if you are interested completing a body analysis scan. "><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    {{-- <input type="text" name="name" id="name" class="form-control">KG --}}
    <input type="hidden" name="weightUnit" value="{{ isset($parq->weightUnit) && $parq->weightUnit !=null?$parq->weightUnit:'Metric' }}">
    <div class="row">
        <div class="col-sm-12 weightDiv col-xs-12">

            <div class="input-group weightMetric {{ isset($parq->weightUnit) && $parq->weightUnit =='Imperial'?'hidden':'' }}">
                <input type="text" name="weight_metric" class="form-control custom-width" value="{{isset($parq)?$parq->weight:''}}">
                <span class="input-group-addon">Kg</span>
            </div>
            
            <div class="input-group weightImperial {{ isset($parq->weightUnit) && ($parq->weightUnit =='Metric' || $parq->weightUnit ==null) ?'hidden':'' }}">
                <input type="text" name="weight_imperial" class="form-control custom-width" value="{{isset($parq)?$parq->weight:''}}">
                <span class="input-group-addon">Pounds</span>
            </div>
            <span class="help-block m-b-0"></span>
            <button type="button" style="color:#fff;" class="btn btn-primary btn-o btn-sm p-y-0 bg-none mli-10 pull-right show-btn" id="weightUnit">{{isset($parq->weightUnit) && $parq->weightUnit == "Imperial"?"Show Metric":"Show Imperial"}}</button>
        </div>
    </div>
</div>
</div>
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>7.</span></div>
      <label class="steps-head">Please upload a recent headshot photo of yourself, as a profile picture</label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step1" data-message="Providing a recent headshot creates a more personal feel to the online process and allows trainers to know who they are going to be working with and who to greet on the first-time meeting. It allows you to personalise your online profile and once again helps to create a better connection between yourself and your trainer as well as helping you to connect with other individuals on the same journey as you both online and in person. 
    <br/><br/>
   An image helps build better relationships and trust and also allows you to create your personalised <span style='color: #f94211'><b>EPIC</b> RESULT</span> profile and dashboard. Once you connect with individuals on <span style='color: #f94211'>RESULT</span>, you may then also be able to identify and greet them in real life having seen their profile picture and vice versa. 
    <br/><br/>
   Same applies for your first-time meeting of a representative of <span style='color: #f94211'><b>EPIC</b></span>. Once you see a headshot you immediately feel more personal with that person, whether you have met them or not.
    <br/><br/>
   Remember that you are building your very own, personalised online profile. Use the select file option to choose an image, use the ‘take photo’ option to capture a photo now. You can change this photo at any stage. "><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>

{{-- <div class="fileupload">
    <input type="file" name="fileupload" accept=".pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
</div> --}}
<div class="form-group upload-group">
    <input type="hidden" name="prePhotoName" value="{{ isset($clients)?$clients->profilepic:'' }}" class="no-clear prePhotoNameClient">
    <input type="hidden" name="entityId" value="" class="no-clear">
    <input type="hidden" name="saveUrl" value="" class="no-clear">
    <input type="hidden" name="photoHelper" value="clientpic" class="no-clear">
    <input type="hidden" name="cropSelector" value="square">
    <input type="hidden" name="crmPath" id="crmPath" value="{{crmPath()}}">
    @if($clients->profilepic == '')
    <div id="userPic" class="dropzone text-center">
        <img src="/1.png">

    </div>
    @endif
    <div id="userPic" class="dropzone text-center hide">
        <img src="/1.png">

    </div>
    <span class="help-block m-b-0"></span>
    @if($clients->profilepic == '')
    <button type="button" class="btn btn-info openCamera hidden-xs takePic">Take a Picture</button>
    @endif
    <button type="button" class="btn btn-info openCamera hidden-xs hide">Take a Picture</button>

    <div class="m-t-10 remove-img" data-id="{{$clients->id}}">
        @if($clients->profilepic)
        <img src="{{ dpSrc($clients->profilepic, $clients->gender)}}" class="clientpicPreviewPics previewPics" />
        <button class="btn btn-default picRemove">Remove</button>

        @else
        <img class="hidden clientpicPreviewPics previewPics" />
        <button class="btn btn-default picRemove hide">Remove</button>
        @endif
    </div>
    <input type="hidden" value="{{ isset($clients)?$clients->profilepic:'' }}" id="check-img" name="clientpic">
</div>
</div>
<div class="step">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>8.</span></div>
      <label class="steps-head">What is your birthdate?</label>
  </div>
  <div class="tooltip-sign mb-10">
     <a href="javascript:void(0)" class="parq-step1" data-message="Date of birth is important to ascertain age to be able to allocate risk factor that is associated with age (Generally, males over 45 and females over 55 have a higher risk status.) 
      <br/><br/>
     The overall requirements for training and nutrition vary significantly, dependant on age too. It is important as nutritional requirements, training intensity, muscle conditioning, bone density, cognitive function and general ability vary greatly between age groups. 
      <br/><br/>
     Birthdate also allows us to record your birthday and be able to celebrate and wish you on your special day. 
      <br/><br/>
     Often birth dates link closely with an <span style='color: #f94211'><b>EPIC</b> Goal</span> end dates as many individuals choose to hit an <span style='color: #f94211'><b>EPIC</b> Goal</span> before or on their birth date or birthday celebration date."><i class="fa fa-question-circle question-mark"></i></a>

 </div>

</div>

<div class="form-group">
    <?php

                        // 1993-06-04

    $bday = '';
    $bmonth = '';
    $byear = '';

    $dob = '';
    if(isset($parq)) {
        $dob = $parq->dob;
    }

    $dob = explode('-', $dob);

    if(isset($dob[0])) {
        $byear = $dob[0];
    }

    if(isset($dob[1])) {
        $bmonth = $dob[1];
    }

    if(isset($dob[2])) {
        $bday = $dob[2];
    }
    ?>
    <div class="row">
        <div class="col-md-4 col-xs-4">
            <div class="styled-select">
                <select class="form-control required" id="dd" name="dd" data-realtime="dob" required>
                    <option data-hidden="true" value="">DAY</option>
                    <option value="01" <?php echo $parq->birthDay == '01'?'selected':''; ?>>1</option>
                    <option value="02" <?php echo $parq->birthDay == '02'?'selected':''; ?>>2</option>
                    <option value="03" <?php echo $parq->birthDay == '03'?'selected':''; ?>>3</option>
                    <option value="04" <?php echo $parq->birthDay == '04'?'selected':''; ?>>4</option>
                    <option value="05" <?php echo $parq->birthDay == '05'?'selected':''; ?>>5</option>
                    <option value="06" <?php echo $parq->birthDay == '06'?'selected':''; ?>>6</option>
                    <option value="07" <?php echo $parq->birthDay == '07'?'selected':''; ?>>7</option>
                    <option value="08" <?php echo $parq->birthDay == '08'?'selected':''; ?>>8</option>
                    <option value="09" <?php echo $parq->birthDay == '09'?'selected':''; ?>>9</option>
                    <option value="10" <?php echo $parq->birthDay == '10'?'selected':''; ?>>10</option>
                    <option value="11" <?php echo $parq->birthDay == '11'?'selected':''; ?>>11</option>
                    <option value="12" <?php echo $parq->birthDay == '12'?'selected':''; ?>>12</option>
                    <option value="13" <?php echo $parq->birthDay == '13'?'selected':''; ?>>13</option>
                    <option value="14" <?php echo $parq->birthDay == '14'?'selected':''; ?>>14</option>
                    <option value="15" <?php echo $parq->birthDay == '15'?'selected':''; ?>>15</option>
                    <option value="16" <?php echo $parq->birthDay == '16'?'selected':''; ?>>16</option>
                    <option value="17" <?php echo $parq->birthDay == '17'?'selected':''; ?>>17</option>
                    <option value="18" <?php echo $parq->birthDay == '18'?'selected':''; ?>>18</option>
                    <option value="19" <?php echo $parq->birthDay == '19'?'selected':''; ?>>19</option>
                    <option value="20" <?php echo $parq->birthDay == '20'?'selected':''; ?>>20</option>
                    <option value="21" <?php echo $parq->birthDay == '21'?'selected':''; ?>>21</option>
                    <option value="22" <?php echo $parq->birthDay == '22'?'selected':''; ?>>22</option>
                    <option value="23" <?php echo $parq->birthDay == '23'?'selected':''; ?>>23</option>
                    <option value="24" <?php echo $parq->birthDay == '24'?'selected':''; ?>>24</option>
                    <option value="25" <?php echo $parq->birthDay == '25'?'selected':''; ?>>25</option>
                    <option value="26" <?php echo $parq->birthDay == '26'?'selected':''; ?>>26</option>
                    <option value="27" <?php echo $parq->birthDay == '27'?'selected':''; ?>>27</option>
                    <option value="28" <?php echo $parq->birthDay == '28'?'selected':''; ?>>28</option>
                    <option value="29" <?php echo $parq->birthDay == '29'?'selected':''; ?>>29</option>
                    <option value="30" <?php echo $parq->birthDay == '30'?'selected':''; ?>>30</option>
                    <option value="31" <?php echo $parq->birthDay == '31'?'selected':''; ?>>31</option>
                </select>
            </div>
        </div>
        <div class="col-md-4 col-xs-4">
            <div class="styled-select">
                <select class="form-control js-month"{{isset($parq) && $parq->birthMonth?'checked' : ''}} name="mm" data-realtime="dob" data-size="9" required>
                    <option data-hidden="true" value="">MONTH</option>
                    {!! monthDdOptions($parq->birthMonth) !!}

                </select>
            </div>
        </div>
        <div class="col-md-4 col-xs-4">
            <div class="styled-select">
                <select class="form-control js-year"{{isset($parq) && $parq->birthYear?'checked' : ''}} name="yyyy" data-realtime="dob" data-size="9" required>
                    <option data-hidden="true" value="">YEAR</option>
                    {!! yearDdOptions($parq->birthYear,13) !!}
                </select>
            </div>
        </div>
    </div>
</div>
</div>

<div class="step">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>9.</span></div>
      <label class="steps-head">What is your occupation? </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step1" data-message="We are particularly interested in your occupation as this gives us an indication of your daily activity and stress levels & many more important details like job satisfaction, career passion, daily social environments, health hazard, activity level and travel requirements. Your occupation may initially seem as something that is unrelated to your health and wellness journey, however the sometimes-overwhelming effects certain occupational environments have on your ability to manage stress cannot be overlooked. 
       <br/><br/>
      The physical demands required in different occupations varies greatly, including but not limited to the way they may affect your posture and basic movement patterns. 
       <br/><br/>
      Too many individuals are in jobs to relieve financial or extrinsic pressure and have little to no job satisfaction. This overflows onto so many other aspects of your life and must be addressed immediately before heading further along the Lifestyle Design Journey.
       <br/><br/>
      Your Health & Wellness program will be tailored specifically to you, factoring in have the physical demands you experience at work as well as addressing issues with posture, stress, and time management. 
       <br/><br/>
      In addition to your specific training program created by your <span style='color: #f94211'><b>EPIC</b> TRAINER</span>, <span style='color: #f94211'><b>EPIC</b></span> has the ability, and industry experts available to provide both onsite and online seminars addressing workplace wellness, safe functional movement, office ergonomics and more. 
       <br/><br/>
      You may also be in an industry that can complement/assist <span style='color: #f94211'><b>EPIC</b></span> and we can build a working relationship and benefit from/support one another.
       <br/><br/>
      If you do wish to receive more information regarding the options available for yourself, your colleagues, and employers/employees please comment in the notes section and an <span style='color: #f94211'><b>EPIC</b> TRAINER</span> will get in contact with you*."><i class="fa fa-question-circle question-mark"></i></a>

  </div>

</div>
<div class="form-group">
    <input type="text" class="form-control" id="occupation" value="{{ isset($parq)?$parq->occupation : null }}" name="occupation" data-realtime="occupation" required>
</div>
</div>
<div class="step">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>10.</span></div>
      <label class="steps-head">Your primary email address</label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step1" data-message="Accurate and up to date contact number and emails are important for communication purposes and to create and maintain your access to <span style='color: #f94211'><b>EPIC</b> RESULT</span>. 
         <br/><br/>
        We use emails to communicate important information relating to membership, health, changes, latest updates and more. 
         <br/><br/>
        The email you provide will be used for communication and will also be the email used for your <span style='color: #f94211'><b>EPIC</b> RESULT</span> login. Please provide the email you most frequently view. 
         <br/><br/>
        Please always inform us if your email address changes so you can stay in contact and receive details on all important communication matters. "><i class="fa fa-question-circle question-mark"></i></a>

    </div>

</div>
<h2 class="section_title"></h2>
<div class="form-group">
    <input type="email" class="form-control" id="primEm" value="{{ isset($parq)?$parq->email: null }}" name="primEm" readonly="readonly">
</div>
</div>
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>11.</span></div>
      <label class="steps-head">Please provide your phone number & address </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step1" data-message="Contact numbers are important for communication purposes, we encourage you to provide the mobile number you use on a day-to-day basis. We often call and text our clients as this is a more personal form of communication, which is essential for us to have the relationship we need with one another, to assist you in reaching you lifestyle design goal.
       <br/><br/>
      Providing your physical address allows us to know where in the world you are. Knowing the community, you are in allows us to provide better support and connect you with local <span style='color: #f94211'><b>EPIC</b></span> clients in your area. Address details are also required for all services and billing.
       <br/><br/>
      All information you provide is strictly confidential, in accordance with the Privacy Acts, and will not be shared to any third parties under any circumstance without prior confirmation from you."><i class="fa fa-question-circle question-mark"></i></a>

  </div>

</div>
<h2 class="section_title"></h2>
<div class="form-group">
    <input id="contactNo" name="contactNo" type="tel" class="form-control cntryCode numericField" maxlength="16" minlength="5" value="{{$parq->contactNo}}" data-realtime="phone" required>
    <a class="btn-add-more add-address ml-23" href="javascript:void(0)" data-toggle="modal" data-target="#addressModal">+ Add Address</a>
</div>
</div>
<div class="step">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>12.</span></div>
      <label class="steps-head">Please provide the name of your emergency contact</label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step1" data-message="Please provide the name of a close friend, work colleague, or family member as an emergency contact in case the need to contact someone on your behalf ever arises. 
    <br/><br/>
   We require an emergency name should anything happen, and we need to get hold of your emergency contact urgently. This individual should, preferably, lives in the same region and be able to respond promptly in the case of emergency. 
    <br/><br/>
   These details can often share light on who you consider to be reliable and dependable in the case of an emergency. Sometimes this can be a person that is not a family member or has not previously been mentioned and gives us the opportunity to identify and learn about a person you consider to be close to you.
    <br/><br/>
   We will not use this information to contact this individual unless in the case of an emergency, but it allows us an avenue to try contact you if we feel you have been unreachable for a concerningly long period of time."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <input id="ecName" name="ecName" type="text" class="form-control" required value="{{$parq->ecName}}">
    <a class="btn-add-more add-address ml-23" href="javascript:void(0)" data-toggle="modal" data-target="#ecrelationModal" >+ Add Relationship</a>
</div>
<div class="showNotes">

    @if($parq->ecRelation)

    <div class="form-group">
        <label class="strong ">Relationship</label>
        <input class="form-control" value="{{$parq->ecRelation}}" id="changeRelation">
    </div>

    @endif

</div>
</div>
<div class="step">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>13.</span></div>
      <label class="steps-head">Please provide the phone number of your emergency contact</label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step1" data-message="Please provide the contact number of a close friend, work colleague, or family member as an emergency contact in case the need to contact someone on your behalf ever arises. 
     <br/><br/>
   We require an emergency number should anything happen, and we need to get hold of your emergency contact urgently. This individual should, preferably, lives in the same region and be able to respond promptly in the case of emergency. 
     <br/><br/>
   These details can often share light on who you consider to be reliable and dependable in the case of an emergency. Sometimes this can be a person that is not a family member or has not previously been mentioned and gives us the opportunity to identify and learn about a person you consider to be close to you.
     <br/><br/>
   We will not use this information to contact this individual unless in the case of an emergency, but it allows us an avenue to try contact you if we feel you have been unreachable for a concerningly long period of time."><i class="fa fa-question-circle question-mark"></i></a>
</div>

</div>
<div class="form-group">
    <input id="ecNumber" name="ecNumber" type="tel" class="form-control cntryCode numericField" maxlength="16" minlength="5" required value="{{$parq->ecNumber}}">
</div>
</div>
<div class="submit step" id="end">
   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>14.</span></div>
      <label class="steps-head">Please provide any additional notes you think are relevant </label>
  </div>
  <div class="tooltip-sign mb-10">
    <a href="javascript:void(0)" class="parq-step1" data-message="Is there anything you need to add to your personal details and contact information that you think we may need to know or find useful? We know that an online form may be rather limiting but we want to know as much about you as possible. 
     <br/><br/>
    Most individuals like to use this as a ‘tell all’ Section. 
     <br/><br/>
    You can tell us more about your kids, their ages, where they school, if they live at home, what they do that adds stress to your life. Your parents or dependant and your role in their life. Commitments that you have that create happiness or stress. Pets and living situation and the impact it has on your current situation. 
     <br/><br/>
    Feel free to share your deepest darkest secrets here as the more we know about who you are, your current situation and how it directly affects you, the better we can move towards a better version of you and your new lifestyle. 
     <br/><br/>
    Do you wish to receive information relating to workplace wellness and resources available to you as an <span style='color: #f94211'><b>EPIC</b> RESULT</span> member?
     <br/><br/>
    Do you wish to receive more information regarding a full body analysis scan?
     <br/><br/>
    Do you wish to receive more information regarding our referral rewards system?"><i class="fa fa-question-circle question-mark"></i></a>
</div>

</div>
<div class="form-group">
    <textarea rows="3" id="notes" name="notes" placeholder="" class="form-control">{{ isset($parq)?$parq->notes : null }}</textarea>
</div>
</div>
<!-- /step-->

{{-- <div class="submit step" id="end">
    <div class="summary">
        <div class="wrapper">
            <h3>Thank your for your time<br><span id="name_field"></span>!</h3>
            <p>We will contat you shorly at the following email address <strong id="email_field"></strong></p>
        </div>
        <div class="text-center">
            <div class="form-group terms">
                <label class="container_check">Please accept our <a href="#" data-toggle="modal" data-target="#terms-txt">Terms and conditions</a> before Submit
                    <input type="checkbox" name="terms" value="Yes" class="required">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>
    </div>
</div> --}}
<!-- /step last-->

</div>
<!-- /middle-wizard -->
<div id="bottom-wizard">
 <span class="qodef-grid-line-center"><span class="inmotion-total-slides">
  <div class="d-flex">
  
    <a href="#" class="step-back invisible">            
    <span class="prev-name"></span>&nbsp;&nbsp;</a> 
    <a href="#" class="arrow step-back invisible">&#8672; </a> 
    <div class="current-section">PERSONAL DETAIL</div>
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" data-step-no="2" class="arrow step-forward">&#8674;</a>&nbsp;&nbsp;
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" data-step-no="2" class="step-forward"><span class="next-name">Exercise Preference</span></a>
     {{-- <a class="next-name redirect_next_page" href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" data-step-no="2">
        <span  class="arrow step-forward">&#8674;</span>
        <span>&nbsp;&nbsp; Exercise Preference</span>
     </a> --}}
</div>

<span class="inmotion-ts-active-num section-step">01</span>
<span class="inmotion-ts-active-separator">/</span>
<span class="inmotion-ts-active-all all-section-step">05</span>
</span>
<span class="inmotion-total-slides visible-xs question-s">QUESTIONS<br>
    <span class="inmotion-ts-active-num question-step">5</span>
    <span class="inmotion-ts-active-separator">/</span>
    <span class="inmotion-ts-active-all">14</span>
</span>
<span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
<div class="row">
    <div class="col-sm-5 col-xs-5">     <button type="button" name="backward" class="backward">Prev</button></div>
    <div class="col-sm-7 col-xs-7">

       <button type="button" name="forward" class="forward">Next</button>
     <button type="button" class="submit submit-step" data-step-url="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" data-step="1">Submit</button>
   </div>
</div>


</div>

<!-- /bottom-wizard -->
<div class="modal" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="Address Modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Address Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="vp-form-input-list ml-0">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="autocomplete" class="strong">Address Line 1 *</span></label>
                                <input type="text" class="form-control" id="autocomplete"  name="addressline1" value="{{$parq->addressline1}}" required onFocus ='geolocate()' autocomplete = 'off'>
                            </div>

                            <div class="form-group">
                                <label for="addressline2" class="strong">Address Line 2</span></label>
                                <input type="text" class="form-control" id="addressline2" name="addressline2" value="{{$parq->addressline2}}">
                            </div>

                            <div class="form-group">
                                <label for="city" class="strong">City *</span></label>
                                <input type="text" class="form-control" id="city" value="{{$parq->city}}" name="city" required>
                            </div>

                            <div class="form-group">
                                <label for="postal_code" class="strong">Postal code *</span></label>
                                <input type="tel" class="form-control" id="postal_code" name="postal_code" value="{{$parq->postal_code}}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="country" class="strong">Country *</span></label>
                                <select id="country" name="country" class="form-control" required>
                                    <option value="">-- Select --</option>
                                    <?php
                                    foreach($countries as $shortCode => $country)
                                        echo '<option value="'.$shortCode.'" '.($parq->country == $shortCode?'selected':'').'>'.$country.'</option>';
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="addrState" class="strong">State / Region *</span></label>
                                <select class="form-control" id="addrState" name="addrState" required data-selected="{{$parq->addrState}}">
                                    <option value="">-- Select --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="timezone" class="strong">Time Zone *</span></label>
                                <select id="timezone" name="timezone" class="form-control" required data-live-search = 'true'>
                                    <option value="">-- Select --</option>
                                    <?php
                                    foreach($timezones as $country => $timezone){
                                        echo '<optgroup label="'.$country.'">';
                                        foreach($timezone as $key => $value){
                                            echo '<option value="'.$key.'" '.($parq->timezone == $key?'selected':'').'>'.$value.'</option>';
                                        }
                                        echo '</optgroup>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="currency" class="strong">Currency *</span></label>
                                <select id="currency" name="currency" class="form-control" required data-live-search = 'true'>
                                    <option value=""></option>
                                    <?php
                                    foreach($currencies as $shortCode => $currency)
                                        echo '<option value="'.$shortCode.'" '.($parq->currency == $shortCode?'selected':'').'>'.$currency.'</option>';
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div> <!-- end vp form list -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="addModalCanc">Cancel</button>
                <button type="button" class="btn btn-primary" id="addModalOk">Ok</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="ecrelationModal" tabindex="-1" role="dialog" aria-labelledby="ecRelation Modal" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Relationship Details</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="vp-form-input-list ml-0">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="relation" class="strong">Relationship to Emergency Contact</span></label>
                                <input type="text" class="form-control" id="ecRelation" name="ecRelation" value="{{$parq->ecRelation}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary showRelation" data-dismiss="modal">Ok</button>
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




<div class="modal" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cropperModalLabel">Cropper</h5>
          <button type="button" class="close saveImg" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="photoName">
        <div class="img-container">
            <img id="imageCrop" src="" alt="Picture" height="340" width="100%">
        </div>
    </div>
    <div class="modal-footer">
         <!--  <button type="button" class="btn btn-primary rotate">
            <span class="fa fa-undo-alt"></span></button> -->


            <button type="button" class="btn btn-success cropImg">Crop</button>
            <button type="button" class="btn btn-secondary saveImg" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>
</div>





<div class="modal" id="webcam-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Click a Picture</h4>
            </div>
            <div class="modal-body">
                <div id="camera" style="margin-left:112px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default close-webcam">Cancel</button>
                <button type="button" class="btn btn-info snap">Take picture</button>
            </div>
        </div>
    </div>
</div>

<div id="parq-step1" class="modal fade" role="dialog">
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
    $(document).on('click','.parq-step1',function(){
        $(this).attr('data-toggle','modal')
        $(this).attr('data-target','#parq-step1')
        $("#parq-step1").attr('aria-modal',true)
        $("#parq-step1").addClass('in')
        var message = $(this).data('message');
        $("#parq-step1").find('.message').html(message);
    })
</script>
@endsection
@section('required-script')
@stop