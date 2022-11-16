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
      <span class="inmotion-ts-active-all all-question-step">07</span>
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
             <img src="{{asset('result/images/step-three.jpg')}}" alt="" class="img-fluid">

         </div>
         <img id="pot" src="{{asset('assets/images/h1-slider-img-1.png')}}" alt="" class="img-fluid">
         <!-- /content-left-wrapper -->
     </div>
     <!-- /content-left -->
     <div class="col-xl-6 col-lg-5 col-md-5 col-xs-12 content-right" id="start">
        <div id="wizard_container">
            <div id="top-wizard">
              <h2 class="steps-name wizard-header">INJURY PROFILE & FAMILY HISTORY</h2>
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
                                  <label class="steps-head">Select a <b>body part</b> and <b>injury</b> associated with it, please add relevant notes relating to these injuries </label>
                              </div>
                              <div class="tooltip-sign mb-10">
                                <a href="javascript:void(0)" class="parq-step3" data-message="It is crucial that we know whether you have any injuries, present or past. Injuries can either present little to no restrictions, restrict training completely or just limit certain aspects of training for an indefinite period, depending on the injury. 
                                 <br/><br/>
                                You may think that because an injury happened in your childhood, you do not need to indicate it. This could not be more untrue. Past injuries, most often, have long term results especially related to movement patterns.  
                                 <br/><br/>
                                Most of our muscular imbalances and or differences stem from a movement pattern created years back. This could have been caused by an injury, a repeated movement pattern, a sport, surgery, and many other reasons. 
                                 <br/><br/>
                                The more we know, the more we can work on creating balance and get you progressing and improving towards your <span style='color: #f94211'><b>EPIC</b> Goal</span>, injury free.
                                 <br/><br/>
                                Use the body mapper/selector to click and highlight areas of the body where you have an injury or experience discomfort in any form. Select and body part the use the check boxes to select the type and location (left or right side) of the injury/ discomfort.
                                 <br/><br/>
                                If you are unsure what to select or do not find the most appropriate selection for your injury, please add notes in the notes section provided at the bottom of the page.
                                 <br/><br/>
                                There are always ways to train with most injuries, so, if we know more about what you are experiencing or have experienced, it will help us complete the puzzle of you and be able to get you to reach your goal.
                                 <br/><br/>
                                Most individuals feel it unnecessary to list small issues, like a sore wrist after gardening or a sore knee after walking in the bush. These are all PARTICULARLY important as these are signs that the body is giving to report something that is not right. If we know this all before starting any physical activity program, they can all be catered for, hopefully eliminated, or reduced, which will lead to a quicker <span style='color: #f94211'>RESULT</span>.
                                 <br/><br/>
                                Remember, at <span style='color: #f94211'><b>EPIC</b></span>, our expertise lies in making you stronger, more functional, and better equipped to handle your life and body, with knowledge and experience and to know how to understand the messages from the body yourself and deal with them before they cause further concern."><i class="fa fa-question-circle question-mark"></i></a>

                            </div>

                        </div>
                        <div class="form-group">

                            <button type="button" class="btn btn-o btn-default btn-block border-orange" data-toggle="modal" data-target="#injuryModal">Click here to view body parts</button>
                        </div>
                    </div>
                    <!-- /step-->

                    <div class="step">
                        <div class="row">

                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

                      </span>
                      <div class="heading-text border-head mb-10">
                          <div class="watermark1"><span>1.</span></div>
                          <label class="steps-head">Please add the <b>relevant notes</b> relating to <b>injuries</b> selected above  </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                          <a href="javascript:void(0)" class="parq-step3" data-message="Providing information regarding any injuries and or discomforts allows us to take the most appropriate action when prescribing your program and commencing training. 
                           <br/><br/>
                          The more we know, the more we can consider when designing your Lifestyle Design Program. 
                           <br/><br/>
                          Details can include: 
                          <br/><br/>
                          <b>·</b> Date of the initial injury <br/>
                          <b>·</b> Treatment history & outcomes <br/>
                          <b>·</b> Physicians contact details <br/>
                          <b>·</b> Level of discomfort currently felt <br/>
                          <b>·</b> Any movements you feel aggravate the injury <br/>
                          <b>·</b> And any other relevant information"><i class="fa fa-question-circle question-mark"></i></a>

                      </div>

                  </div>
                  <div class="form-group">
                   <textarea rows="3" id="ipfhAdditionalNotes" name="ipfhAdditionalNotes" placeholder="" class="form-control">{{ isset($parq)?$parq->ipfhAdditionalNotes : null }}</textarea>
               </div>
           </div>

           <!-- /Start Branch ============================== -->
           <div class="step">
            <div class="row">

              <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

          </span>
          <div class="heading-text border-head mb-10">
              <div class="watermark1"><span>2.</span></div>
              <label class="steps-head">Do you have any <b>allergies</b> and if yes please list them  </label>
          </div>
          <div class="tooltip-sign mb-10">
             <a href="javascript:void(0)" class="parq-step3" data-message="An allergy is an atypical reaction of the human immune system to particles in the environment, known as allergens. 
              <br/><br/>
             These allergens (pollen, mould, dust mites, animal hair and grass) trigger the release of the inflammatory histamine chemical causing allergic reactions such as sneezing, coughing, itching, watering eyes, post-nasal drip and a runny nose.
              <br/><br/>
             Allergies should not restrict training but there are ways to minimise the onset of allergic symptoms while training and having a list of your allergies allows us to cater for them.
              <br/><br/>
             You may also be allergic to certain food types and medications, so it is critical you provide accurate information.
              <br/><br/>
             Allergies and inflammation can often be caused by a compromised immune system. If we need to reduce inflammation in your body, we can use this information to prescribe better lifestyle habits and ensure we limit inflammatory foods and activities and any circumstances that can trigger an allergic reaction. 
              <br/><br/>
             If you are allergic to nuts, we will not be recommending you eat nut butters as a form of healthy fats when we complete your Trace and Replace food journal."><i class="fa fa-question-circle question-mark"></i></a>

         </div>

     </div>
     <div class="form-group">
        <label class="container_radio version_2">Yes
            <input type="radio" name="allergy" value="Yes" {{isset($parq) && $parq->allergies == 'Yes' ? 'checked' : '' }}>
            <span class="checkmark"></span>
        </label>
        <label class="container_radio version_2">No
            <input type="radio" name="allergy" value="No" {{isset($parq) && $parq->allergies == 'No' ? 'checked' : '' }}>
            <span class="checkmark"></span>
        </label>
        <input type="text" class="hidden" id="allergies" name="allergies"  placeholder="" required>
    </div>
    <div class="row AllergyDetails {{isset($parq) && $parq->allergies == 'Yes' ? '' : 'hide' }}">
        <div class="col-sm-12 col-xs-12">
            <h3 class="ml-23"><span>Allergy details</span></h3>
            <textarea rows="3" id="allergiesList" name="allergiesList" placeholder="" class="form-control">{{ isset($parq)?$parq->allergiesList : null }}</textarea>
        </div>
    </div>
</div>

<!-- /Work Availability > Full-time ============================== -->
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>3.</span></div>
      <label class="steps-head">Do you take any <b>chronic medication</b> and if yes please provide details of the medication  </label>
  </div>
  <div class="tooltip-sign mb-10">
    <a href="javascript:void(0)" class="parq-step3" data-message="Chronic medication is important in regard to training. Some medication may have an effect of heart rate during training i.e., beta blockers.
     <br/><br/>
    Some medication may cause weight gain i.e., anti-depressant medication, while some may cause fatigue i.e., hay fever/sinus tablets.
     <br/><br/>
    We require an up-to-date list of any medication you are on, at all times, as this has a direct effect on the type and intensity of training and your nutritional requirements. Do not feel ashamed in any way to list any chronic medication you take. This will help us provide a program around these details and also give us more insight into the ‘why’ behind you having to take that medication. 
     <br/><br/>
    Some medication is chronic, but many medications can be avoided with the correct lifestyle changes. 
     <br/><br/>
    Blood pressure medication can be lessened and hopefully eliminated after a certain period of time, while making lifestyle changes. The same goes for sleeping tablets, anxiety medication and even diabetic medication. When you get your body into its healthiest state, you will no longer needs medication to try regulate it and help it function, your body will do it by itself, as it was designed to do."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <label class="container_radio version_2">Yes
        <input type="radio" name="chronic" value="Yes" {{isset($parq) && $parq->chronicMedication == 'Yes' ? 'checked' : '' }}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">No
        <input type="radio" name="chronic" value="No" {{isset($parq) && $parq->chronicMedication == 'No' ? 'checked' : '' }}>
        <span class="checkmark"></span>
    </label>
    <input type="text" class="hidden" id="chronicMedication" name="chronicMedication" placeholder="" required>
</div>
<div class="row chronicMedication {{isset($parq) && $parq->chronicMedication == 'Yes' ? '' : 'hide' }}" >
    <div class="col-sm-12 col-xs-12">
        <h3 class="ml-23"><span>Medication details</span></h3>
        <textarea rows="3" id="chronicMedicationList" name="chronicMedicationList" placeholder="" class="form-control">{{ isset($parq)?$parq->chronicMedicationList : null }}</textarea>
    </div>
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
      <label class="steps-head">Have you personally <b>suffered</b> from </label>
  </div>
  <div class="tooltip-sign mb-10">
   <a href="javascript:void(0)" class="parq-step3" data-message="Select any and all conditions which you have experienced or continue to experience. Once selected you can add additional notes in the pop-up modal.
   <br/><br/>
   Notes should include:
   <br/><br/>
   <b>·</b> Diagnosis date <br/>
   <b>·</b> Treating physician <br/>
   <b>·</b> Description and severity of symptoms <br/>
   <b>·</b> Current treatments and other relevant notes 
    <br/><br/>
   These are all serious conditions that do require a certain approach when making lifestyle changes. It is important we know every detail regarding when these incidences took place, what the outcome was and how they affect you now. 
   <br/><br/>
   We can safely work around most cases but the more information you share with us the better we can approach your situation with the correct amount of care and support. We believe in inclusive Health & Wellness, meaning, if you have the will, no matter anything else, we have the way and we will get you to where you want to be."><i class="fa fa-question-circle question-mark"></i></a>

</div>

</div>
<div class="form-group">
    <?php
    if(!count($parq->medicalCondition))
        $parq->medicalCondition = [];
    ?>
    
    {{-- <script>
        $(document).ready(function() {
            setTimeout(function () {
                var selectedMC = {!! json_encode($parq->medicalCondition) !!};
                $("#medicalCondition").selectpicker("val", selectedMC);

                window.pqs3DataSetMedicalCondition(selectedMC);
                window.digestPqs3();
            }, 1000);
        });
    </script> --}}
    <div class="styled-select">
        <select id="medicalCondition" class="form-control medCond customValDdField" multiple name="medicalCondition" ng-model="medicalCondition" ng-keypress="pressEnter($event)" data-size="9" required>
            <option value="None" <?php echo in_array('None', $parq->medicalCondition)?'selected':''; ?>>None</option>
            <option value="Diabetes" <?php echo in_array('Diabetes', $parq->medicalCondition)?'selected':''; ?>>Diabetes</option>
            <option value="High/Low blood pressure under medication" <?php echo in_array('High/Low blood pressure under medication', $parq->medicalCondition)?'selected':''; ?>>High/Low BP under medication</option>
            <option value="Stroke" <?php echo in_array('Stroke', $parq->medicalCondition)?'selected':''; ?>>Stroke</option>
            <option value="Asthma" <?php echo in_array('Asthma', $parq->medicalCondition)?'selected':''; ?>>Asthma</option>
            <option value="Chest pain" <?php echo in_array('Chest pain', $parq->medicalCondition)?'selected':''; ?>>Chest pain</option>
            <option value="Arthritis" <?php echo in_array('Arthritis', $parq->medicalCondition)?'selected':''; ?>>Arthritis</option>
            <option value="Osteoporosis" <?php echo in_array('Osteoporosis', $parq->medicalCondition)?'selected':''; ?>>Osteoporosis</option>
            <option value="High cholesterol" <?php echo in_array('High cholesterol', $parq->medicalCondition)?'selected':''; ?>>High cholesterol</option>
            <option value="Heart conditions" <?php echo in_array('Heart conditions', $parq->medicalCondition)?'selected':''; ?>>Heart conditions</option>
        </select>
        {!! Form::hidden('medCondNotes', $parq->medicaNotes) !!}
    </div>

</div>
<div class="med_notes">
    @php 
    isset($parq) && isset($parq->medicaNotes)?$medicaNotes = json_decode($parq['medicaNotes']):$medicaNotes=[];
    @endphp
    @if(count($medicaNotes))
    @foreach($medicaNotes as $key => $valueNotes)
    <div class="form-group" data-med="{{$key}}">
        <label class="strong medinotes">{{$key}}</label>
        <input class="form-control" value="{{$valueNotes}}" name="medicaNotes" >
    </div>
    @endforeach
    @endif
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
      <label class="steps-head">Has any <b>direct family member</b> (parents, grandparents, siblings) suffered from </label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step3" data-message="A family medical history can identify people with a higher-than-usual chance of having common disorders, such as heart disease, high blood pressure, stroke, certain cancers, and diabetes. 
       <br/><br/>
      We may also encourage regular check-ups or testing for people with a medical condition that runs in their family. Additionally, lifestyle changes such as adopting a healthier diet, doing regular exercise, and quitting smoking, help many people lower their chances of developing heart disease and other common illnesses. 
       <br/><br/>
      We passionately believe that many illnesses are lifestyle related as opposed to genetic and you may have an exceptionally low risk if your lifestyle is healthier than theirs. Some illnesses, however, are hereditary and we like to play it safe and encourage medical clearances signed and check-ups before making any lifestyle changes. 
      <br/><br/>
      The test results, whether positive or negative, can be used to give you peace of mind and predict a plan for your lifestyle design goal moving forward.
       <br/><br/>
      Your family medical history does not define you but your lifestyle decisions you choose to follow do."><i class="fa fa-question-circle question-mark"></i></a>

  </div>

</div>
<div class="form-group">
    <?php
    if(!count($parq->relMedicalCondition))
        $parq->relMedicalCondition = [];
    ?>

    <div class="styled-select">
        <select id="relMedicalCondition" class="form-control medCond customValDdField" multiple name="relMedicalCondition" required data-size="9">
            <option value="None" <?php echo in_array('None', $parq->relMedicalCondition)?'selected':''; ?>>None</option>
            <option value="Diabetes" <?php echo in_array('Diabetes', $parq->relMedicalCondition)?'selected':''; ?>>Diabetes</option>
            <option value="High/Low blood pressure under medication" <?php echo in_array('High/Low blood pressure under medication', $parq->relMedicalCondition)?'selected':''; ?>>High/Low BP under medication</option>
            <option value="Stroke" <?php echo in_array('Stroke', $parq->relMedicalCondition)?'selected':''; ?>>Stroke</option>
            <option value="Asthma" <?php echo in_array('Asthma', $parq->relMedicalCondition)?'selected':''; ?>>Asthma</option>
            <option value="Chest pain" <?php echo in_array('Chest pain', $parq->relMedicalCondition)?'selected':''; ?>>Chest pain</option>
            <option value="Arthritis" <?php echo in_array('Arthritis', $parq->relMedicalCondition)?'selected':''; ?>>Arthritis</option>
            <option value="Osteoporosis" <?php echo in_array('Osteoporosis', $parq->relMedicalCondition)?'selected':''; ?>>Osteoporosis</option>
            <option value="High cholesterol" <?php echo in_array('High cholesterol', $parq->relMedicalCondition)?'selected':''; ?>>High cholesterol</option>
            <option value="Heart conditions" <?php echo in_array('Heart conditions', $parq->relMedicalCondition)?'selected':''; ?>>Heart conditions</option>
        </select>
        {!! Form::hidden('relMedCondNotes', $parq->relMedicaNotes) !!}
    </div>
    <div class="rel_med_notes">
        @php 
        isset($parq) && isset($parq->relMedicaNotes)?$relMedicaNotes = json_decode($parq['relMedicaNotes']):$relMedicaNotes=[];
        @endphp
        @if(count($relMedicaNotes))
        @foreach($relMedicaNotes as $key => $valueNotes)
        <div class="form-group" data-med="{{$key}}">
            <label class="strong relmedinotes">{{$key}}</label>
            <input class="form-control" value="{{$valueNotes}}" name="relmedicaNotes" >
        </div>
        @endforeach
        @endif
    </div>
</div>
</div>
<div class="step">
    <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>6.</span></div>
      <label class="steps-head">Do you or have you <b>smoked within the last six months? </b></label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step3" data-message="Your history and/or current smoking habits directly affect your risk of developing medical issues and affect your cardiovascular ability. Please indicate how cigarettes per day you smoke, using the pop-up select option.
       <br/><br/>
      We are not here to judge you, but smoking can negatively affect your performance both in resistance training and cardiovascular ability.
       <br/><br/>
      If we know what you smoke and how often, we can tailor your program around that and try encouraging you to stop. It is your choice at the end of the day, and we cannot force you to do anything, but we do know that the feeling of being healthy, active & being able to progress cardiovascular will eventually outweigh any desire to smoke. 
      <br/><br/>
      If you need help to stop, ask us, we want to help and we know the best way to show you how."><i class="fa fa-question-circle question-mark"></i></a>

  </div>

</div>

<div class="form-group">
    <label class="container_radio version_2">Yes
        <input type="radio" name="smoke" value="Yes" {{isset($parq) && $parq->smoking == 'Yes' ? 'checked' : '' }}>
        <span class="checkmark"></span>
    </label>
    <label class="container_radio version_2">No
        <input type="radio" name="smoke" value="No" {{isset($parq) && $parq->smoking == 'No' ? 'checked' : '' }}>
        <span class="checkmark"></span>
    </label>
    <input type="text" class="hidden" id="smoking" name="smoking"  placeholder="" required value="">

</div>
<div class="smokeData {{isset($parq) && $parq->smoking == 'Yes' ? '' : 'hide' }}">
    <br clear="all" />
    <div class="clip-radio radio-primary radio-inline m-b-0">
        <input type="radio" name="smokingPer" class="onchange-set-neutral" id="smokingPerDay0" value="1-9"{{isset($parq) && $parq->smokingPerDay == '1-9' ? 'checked' : '' }}>
        <label for="smokingPerDay0">
            1-9
        </label>
    </div>
    <div class="clip-radio radio-primary radio-inline m-b-0">
        <input type="radio" name="smokingPer" class="onchange-set-neutral" id="smokingPerDay1" value="10-19" {{isset($parq) && $parq->smokingPerDay == '10-19' ? 'checked' : '' }}>
        <label for="smokingPerDay1">
            10-19
        </label>
    </div>
    <div class="clip-radio radio-primary radio-inline m-b-0">
        <input type="radio" name="smokingPer" class="onchange-set-neutral" id="smokingPerDay2" value="20+" {{isset($parq) && $parq->smokingPerDay == '20+' ? 'checked' : '' }}>
        <label for="smokingPerDay2">
            20+
        </label>
    </div>
    <input type="text" class="hidden" id="smokingPerDay" name="smokingPerDay" placeholder="" value="" class="form-control mb" required>
    <span class="help-block m-t--5 m-b-0"></span>
</div>
</div>
<div class="submit step" id="end">

   <div class="row">

      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>

  </span>
  <div class="heading-text border-head mb-10">
      <div class="watermark1"><span>7.</span></div>
      <label class="steps-head">Please provide any <b>additional notes</b> you think are relevant</b></label>
  </div>
  <div class="tooltip-sign mb-10">
      <a href="javascript:void(0)" class="parq-step3" data-message="Please provide any additional notes relating to your injury profile or your family medical history. Please also include notes on what you smoke i.e., vape, cigarette, cigar, cannabis etc.
       <br/><br/>
      If you felt that there was something else that you wanted to share, now is the chance. Perhaps you have suffered with an illness that was not listed and you would like to share it. We have many clients who have, or are suffering with cancer, rare diseases, autoimmune diseases and more. 
       <br/><br/>
      These are all particularly important to consider before designing a new lifestyle and we want to know as much about you as possible. Do not be shy to list things that you may have only ever told your partner or doctor before, we are qualified health care professionals who live to assist individuals in changing their lives on a daily basis."><i class="fa fa-question-circle question-mark"></i></a>

  </div>

</div>


<div class="form-group upload-group">
    <textarea rows="1" id="ipfhNotes" name="ipfhNotes" placeholder="" class="form-control">{{ isset($parq)?$parq->ipfhNotes : null }}</textarea>
</div>
</div>
</div>
<!-- /middle-wizard -->
<div id="bottom-wizard">
 <span class="qodef-grid-line-center"><span class="inmotion-total-slides">
  <div class="d-flex">
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" data-step-no="2" class="step-back">  <span class="prev-name">Exercise Preference</span></a>&nbsp;&nbsp;
    <a href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" data-step-no="2" class="arrow step-back">&#8672;</a>               
    <div class="current-section">Injury Profile & Family History</div>
    <a href="#"  data-step-url="{{ url('epicprogress/AssessAndProgress/PARQ') }}" data-step-no="4" class="arrow step-forward">&#8674;</a>&nbsp;&nbsp;
    <a href="#"  data-step-url="{{ url('epicprogress/AssessAndProgress/PARQ') }}" data-step-no="4" class="step-forward"><span class="next-name">PARQ</span></a>

    {{-- <a class="prev-name redirect_prev_page" href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/ExercisePreference') }}" data-step-no="2">
        <span>Exercise Preference &nbsp;&nbsp;</span>
        <span  class="arrow step-back">&#8672;</span> 
    </a>              
    <div class="current-section">Injury Profile & Family History</div>
    <a class="next-name redirect_next_page" href="#" data-step-url="{{ url('epicprogress/AssessAndProgress/PARQ') }}" data-step-no="4">
        <span  class="arrow step-forward">&#8674;</span>
        <span>&nbsp;&nbsp; PARQ</span>
     </a> --}}
</div>

<span class="inmotion-ts-active-num section-step">03</span>
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
    <div class="col-sm-5 col-xs-5">  <button type="button" name="backward" class="backward">Prev</button></div>
    <div class="col-sm-7 col-xs-7">
      
       <button type="button" name="forward" class="forward">Next</button>
        <button type="button" class="submit submit-step" data-step-url="{{ url('epicprogress/AssessAndProgress/PARQ') }}" data-step="3">Submit</button>
  </div>
</div>


</div>
<!-- /bottom-wizard -->
<div class="modal fade bodyPartModal" id="injuryModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-uppercase">Injury</h4>
                <p>Please choose the area by selecting with the mouse or by using the drop down menu where you have an injury </p>
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
                            <img src="{{ asset('bodytool/male/injuries.gif') }}" usemap="#Map" class="body img-responsive" width="600"/>
                            @else
                            <img src="{{ asset('bodytool/female/injuries.gif') }}" usemap="#Map" class="body img-responsive" width="600"/>
                            @endif
                        </div>
                        <div class="form-group hidden-md hidden-lg">
                            {!! Form::label('bodyParts3', 'Body parts', ['class' => 'strong']) !!}
                            <select class="form-control bodyPartsDd"  id="bodyParts3">
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
                            if(!count($parq->headInjury))
                                $parq->headInjury = [];
                            ?>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="headInjury0" id="headInjury0" value="Headaches" {{ in_array('Headaches', $parq->headInjury)?'checked':'' }}>
                                <label for="headInjury0">
                                    Headaches
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="headInjury1" id="headInjury1" value="Migraines" {{ in_array('Migraines', $parq->headInjury)?'checked':'' }}>
                                <label for="headInjury1">
                                    Migraines
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="headInjury2" id="headInjury2" value="Sensitiveness" {{ in_array('Sensitiveness', $parq->headInjury)?'checked':'' }}>
                                <label for="headInjury2">
                                    Sensitiveness
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="headInjury3" id="headInjury3" value="Dizzy" {{ in_array('Dizzy', $parq->headInjury)?'checked':'' }}>
                                <label for="headInjury3">
                                    Dizzy
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="strong" for="notesHeadInjury">Please add the relevant notes relating to injuries selected above</label>
                            <div>
                                <textarea class="form-control"  rows="10" cols="50" id="notesHeadInjury" name="notesHeadInjury">{{ $parq->headInjuryNotes }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="neck injuryList hidden">
                        <div class="form-group">
                            <h4 class="text-uppercase">Neck</h4>
                            <?php
                            if(!count($parq->neckInjury))
                                $parq->neckInjury = [];
                            ?>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury0" id="neckInjury0" value="Pain" {{ in_array('Pain', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury0">
                                    Pain
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury1" id="neckInjury1" value="Sprain" {{ in_array('Sprain', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury1">
                                    Sprain
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury2" id="neckInjury2" value="Strain" {{ in_array('Strain', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury2">
                                    Strain
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury3" id="neckInjury3" value="Break" {{ in_array('Break', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury3">
                                    Break
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury4" id="neckInjury4" value="Pinched nerve" {{ in_array('Pinched nerve', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury4">
                                    Pinched nerve
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury5" id="neckInjury5" value="Fracture" {{ in_array('Fracture', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury5">
                                    Fracture
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury6" id="neckInjury6" value="Dislocation" {{ in_array('Dislocation', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury6">
                                    Dislocation
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury7" id="neckInjury7" value="Torn ligaments" {{ in_array('Torn ligaments', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury7">
                                    Torn ligaments
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury8" id="neckInjury8" value="Whiplash" {{ in_array('Whiplash', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury8">
                                    Whiplash
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury9" id="neckInjury9" value="Ruptured disc" {{ in_array('Ruptured disc', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury9">
                                    Ruptured disc
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="neckInjury10" id="neckInjury10" value="Other" {{ in_array('Other', $parq->neckInjury)?'checked':'' }}>
                                <label for="neckInjury10">
                                    Other
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="strong" for="notesNeckInjury">Please add the relevant notes relating to injuries selected above</label>
                            <textarea class="form-control" id="notesNeckInjury" name="notesNeckInjury">{{ $parq->neckInjuryNotes }}</textarea>
                        </div>
                    </div>

                    <div class="mid-upper-back injuryList hidden">
                        <div class="form-group">
                            <h4 class="text-uppercase">Back Mid & Upper</h4>
                            <?php
                            if(!count($parq->backInjury))
                                $parq->backInjury = [];
                            ?>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury0" value="pain" id="backInjury0" <?php echo in_array('pain', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury0">
                                    Pain
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury1" value="sprain" id="backInjury1" <?php echo in_array('sprain', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury1">
                                    Sprain
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury2" value="strain" id="backInjury2" <?php echo in_array('strain', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury2">
                                    Strain
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury3" value="break" id="backInjury3" <?php echo in_array('break', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury3">
                                    Break
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury4" value="pinched nerve" id="backInjury4" <?php echo in_array('pinched nerve', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury4">
                                    Pinched nerve
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury5" value="fracture/slipped vertebrae" id="backInjury5" <?php echo in_array('fracture/slipped vertebrae', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury5">
                                    Fracture/slipped vertebrae
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury6" value="dislocation" id="backInjury6" <?php echo in_array('dislocation', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury6">
                                    Dislocation
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury7" value="torn ligament" id="backInjury7" <?php echo in_array('torn ligament', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury7">
                                    Torn ligaments
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury8" value="ruptured disc" id="backInjury8" <?php echo in_array('ruptured disc', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury8">
                                    Ruptured disc
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury9" value="Compressed disc" id="backInjury9" <?php echo in_array('Compressed disc', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury9">
                                    Compressed disc
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury10" value="Scoliosis" id="backInjury10" <?php echo in_array('Scoliosis', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury10">
                                    Scoliosis
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury11" value="Spinal fusion" id="backInjury11" <?php echo in_array('Spinal fusion', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury11">
                                    Spinal fusion
                                </label>
                            </div>
                            <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="backInjury12" value="other" id="backInjury12" <?php echo in_array('other', $parq->backInjury)?'checked':''; ?>/>
                                <label for="backInjury12">
                                    Other
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="strong" for="notesBackInjury">Please add the relevant notes relating to injuries selected above</label>
                            <textarea class="form-control" id="notesBackInjury" name="notesBackInjury">{{ $parq->backInjuryNotes }}</textarea>
                        </div>
                    </div>

                    <div class="ankle-n-foot injuryList hidden">
                        <div class="form-group">
                            <h4 class="text-uppercase">Ankle & Feet</h4>
                            L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
                            <?php
                            if(!count($parq->footInjury))
                                $parq->footInjury = [];
                            ?>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury0" value="L_Pain" name="footInjury0" <?php echo in_array('L_Pain', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury0" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury1" value="R_Pain" id="footInjury1" <?php echo in_array('R_Pain', $parq->footInjury)?'checked':''; ?> />
                                    <label for="footInjury1" class="m-r-0"></label>
                                </div>
                                Pain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury2" value="L_Sprain" name="footInjury2" <?php echo in_array('L_Sprain', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury2" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury3" value="R_Sprain" id="footInjury3" <?php echo in_array('R_Sprain', $parq->footInjury)?'checked':''; ?> />
                                    <label for="footInjury3" class="m-r-0"></label>
                                </div>
                                Sprain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury4" value="L_Strain" name="footInjury4" <?php echo in_array('L_Strain', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury4" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury5" value="R_Strain" id="footInjury5" <?php echo in_array('R_Strain', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury5" class="m-r-0"></label>
                                </div>
                                Strain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury6" value="L_Break" name="footInjury6" <?php echo in_array('L_Break', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury6" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury7" value="R_Break" id="footInjury7" <?php echo in_array('R_Break', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury7" class="m-r-0"></label>
                                </div>
                                Break
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury8" value="L_Pinched nerve" name="footInjury8" <?php echo in_array('L_Pinched nerve', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury8" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury9" value="R_Pinched nerve" id="footInjury9" <?php echo in_array('R_Pinched nerve', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury9" class="m-r-0"></label>
                                </div>
                                Pinched nerve
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury10" value="L_Fracture" name="footInjury10" <?php echo in_array('L_Fracture', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury10" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury11" value="R_Fracture" id="footInjury11" <?php echo in_array('R_Fracture', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury11" class="m-r-0"></label>
                                </div>
                                Fracture
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury12" value="L_Bursitis" name="footInjury12" <?php echo in_array('L_Bursitis', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury12" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury13" value="R_Bursitis" id="footInjury13" <?php echo in_array('R_Bursitis', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury13" class="m-r-0"></label>
                                </div>
                                Bursitis
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury14" value="L_Arthritis" name="footInjury14" <?php echo in_array('L_Arthritis', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury14" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury15" value="R_Arthritis" id="footInjury15" <?php echo in_array('R_Arthritis', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury15" class="m-r-0"></label>
                                </div>
                                Arthritis
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury16" value="L_Torn ligament" name="footInjury16" <?php echo in_array('L_Torn ligament', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury16" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury17" value="R_Torn ligament" id="footInjury17" <?php echo in_array('R_Torn ligament', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury17" class="m-r-0"></label>
                                </div>
                                Torn ligament
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury18" value="L_Dislocation" name="footInjury18" <?php echo in_array('L_Dislocation', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury18" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury19" value="R_Dislocation" id="footInjury19" <?php echo in_array('R_Dislocation', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury19" class="m-r-0"></label>
                                </div>
                                Dislocation
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury20" value="L_Gout" name="footInjury20" <?php echo in_array('L_Gout', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury20" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury21" value="R_Gout" id="footInjury21" <?php echo in_array('R_Gout', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury21" class="m-r-0"></label>
                                </div>
                                Gout
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury22" value="L_Bunion" name="footInjury22" <?php echo in_array('L_Bunion', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury22" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury23" value="R_Bunion" id="footInjury23" <?php echo in_array('R_Bunion', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury23" class="m-r-0"></label>
                                </div>
                                Bunion
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury24" value="L_Metatarsalgia" name="footInjury24" <?php echo in_array('L_Metatarsalgia', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury24" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury25" value="R_Metatarsalgia" id="footInjury25" <?php echo in_array('R_Metatarsalgia', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury25" class="m-r-0"></label>
                                </div>
                                Metatarsalgia
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury26" value="L_Achilles tendonitis" name="footInjury26" <?php echo in_array('L_Achilles tendonitis', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury26" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury27" value="R_Achilles tendonitis" id="footInjury27" <?php echo in_array('R_Achilles tendonitis', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury27" class="m-r-0"></label>
                                </div>
                                Achilles tendonitis
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="footInjury28" value="L_Plantar fasciitis" name="footInjury28" <?php echo in_array('L_Plantar fasciitis', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury28" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="footInjury29" value="R_Plantar fasciitis" id="footInjury29" <?php echo in_array('R_Plantar fasciitis', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury29" class="m-r-0"></label>
                                </div>
                                Plantar fasciitis
                            </div>
                            <div class="m-t-5">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" id="footInjury30" value="Other" name="footInjury30" <?php echo in_array('Other', $parq->footInjury)?'checked':''; ?>/>
                                    <label for="footInjury30">
                                        Other
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="strong" for="notesFootInjury">Please add the relevant notes relating to injuries selected above</label>
                            <textarea class="form-control" id="notesFootInjury" name="notesFootInjury">{{ $parq->footInjuryNotes }}</textarea>
                        </div>
                    </div>

                    <div class="knee-n-legs injuryList hidden">
                        <div class="form-group">
                            <h4 class="text-uppercase">Knee & Legs</h4>
                            L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
                            <?php
                            if(!count($parq->legInjury))
                                $parq->legInjury = [];
                            ?>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury0" value="L_Pain" name="legInjury0" <?php echo in_array('L_Pain', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury0" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury1" value="R_Pain" id="legInjury1" <?php echo in_array('R_Pain', $parq->legInjury)?'checked':''; ?> />
                                    <label for="legInjury1" class="m-r-0"></label>
                                </div>
                                Pain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury2" value="L_Sprain" name="legInjury2" <?php echo in_array('L_Sprain', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury2" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury3" value="R_Sprain" id="legInjury3" <?php echo in_array('R_Sprain', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury3" class="m-r-0"></label>
                                </div>
                                Sprain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury6" value="L_Break" name="legInjury6" <?php echo in_array('L_Break', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury6" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury7" value="R_Break" id="legInjury7" <?php echo in_array('R_Break', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury7" class="m-r-0"></label>
                                </div>
                                Break
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury8" value="L_Pinched nerve/ Sciatica" name="legInjury8" <?php echo in_array('L_Pinched nerve/ Sciatica', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury8" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury9" value="R_Pinched nerve/ Sciatica" id="legInjury9" <?php echo in_array('R_Pinched nerve/ Sciatica', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury9" class="m-r-0"></label>
                                </div>
                                Pinched nerve/ Sciatica
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury10" value="L_Fracture" name="legInjury10" <?php echo in_array('L_Fracture', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury10" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury11" value="R_Fracture" id="legInjury11" <?php echo in_array('R_Fracture', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury11" class="m-r-0"></label>
                                </div>
                                Fracture
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury14" value="L_Torn ligament" name="legInjury14" <?php echo in_array('L_Torn ligament', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury14" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury15" value="R_Torn ligament" id="legInjury15" <?php echo in_array('R_Dislocation', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury15" class="m-r-0"></label>
                                </div>
                                Torn ligament
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury16" value="L_Dislocation" name="legInjury16" <?php echo in_array('L_Dislocation', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury16" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury17" value="R_Dislocation" id="legInjury17" <?php echo in_array('R_Dislocation', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury17" class="m-r-0"></label>
                                </div>
                                Dislocation
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury18" value="L_Patella injury" name="legInjury18" <?php echo in_array('L_Patella injury', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury18" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury19" value="R_Patella injury" id="legInjury19" <?php echo in_array('R_Patella injury', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury19" class="m-r-0"></label>
                                </div>
                                Patella injury
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury20" value="L_ACL" name="legInjury20" <?php echo in_array('L_ACL', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury20" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury21" value="R_ACL" id="legInjury21" <?php echo in_array('R_ACL', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury21" class="m-r-0"></label>
                                </div>
                                ACL
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury22" value="L_MCL" name="legInjury22" <?php echo in_array('L_MCL', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury22" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury23" value="R_MCL" id="legInjury23" <?php echo in_array('R_MCL', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury23" class="m-r-0"></label>
                                </div>
                                MCL
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury24" value="L_PCL" name="legInjury24" <?php echo in_array('L_PCL', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury24" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury25" value="R_PCL" id="legInjury25" <?php echo in_array('R_PCL', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury25" class="m-r-0"></label>
                                </div>
                                PCL
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury26" value="L_Calf strain" name="legInjury26" <?php echo in_array('L_Calf strain', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury26" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury27" value="R_Calf strain" id="legInjury27" <?php echo in_array('L_Calf strain', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury27" class="m-r-0"></label>
                                </div>
                                Calf strain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury28" value="L_Tibial Periostitis/ Shin Splints" name="legInjury28" <?php echo in_array('L_Tibial Periostitis/ Shin Splints', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury28" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury29" value="R_Tibial Periostitis/ Shin Splints" id="legInjury29" <?php echo in_array('L_Tibial Periostitis/ Shin Splints', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury29" class="m-r-0"></label>
                                </div>
                                Tibial Periostitis/ Shin Splints
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury30" value="L_ITB Syndrome" name="legInjury30" <?php echo in_array('L_ITB Syndrome', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury30" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury31" value="R_ITB Syndrome" id="legInjury31" <?php echo in_array('R_ITB Syndrome', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury31" class="m-r-0"></label>
                                </div>
                                ITB Syndrome
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="legInjury33" value="L_Strain" name="legInjury33" <?php echo in_array('L_Strain', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury33" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="legInjury34" value="R_Strain" id="legInjury34" <?php echo in_array('R_Strain', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury34" class="m-r-0"></label>
                                </div>
                                Strain
                            </div>
                            <div class="m-t-5">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" id="legInjury32" value="Other" name="legInjury32" <?php echo in_array('Other', $parq->legInjury)?'checked':''; ?>/>
                                    <label for="legInjury32">
                                        Other
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="strong" for="notesLegInjury">Please add the relevant notes relating to injuries selected above</label>
                            <textarea class="form-control" id="notesLegInjury" name="notesLegInjury">{{ $parq->legInjuryNotes }}</textarea>
                        </div>
                    </div>

                    <div class="hips-n-lower-back injuryList hidden">
                        <div class="form-group">
                            <h4 class="text-uppercase">Hips & Lower Back</h4>
                            L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
                            <?php
                            if(!count($parq->hipInjury))
                                $parq->hipInjury = [];
                            ?>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury0" value="L_Pain" name="hipInjury0" <?php echo in_array('L_Pain', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury0" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury1" value="R_Pain" id="hipInjury1" <?php echo in_array('R_Pain', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury1" class="m-r-0"></label>
                                </div>
                                Pain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury2" value="L_Sprain" name="hipInjury2" <?php echo in_array('L_Sprain', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury2" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury3" value="R_Sprain" id="hipInjury3" <?php echo in_array('R_Sprain', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury3" class="m-r-0"></label>
                                </div>
                                Sprain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury4" value="L_Strain" name="hipInjury4" <?php echo in_array('L_Strain', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury4" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury5" value="R_Strain" id="hipInjury5" <?php echo in_array('R_Strain', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury5" class="m-r-0"></label>
                                </div>
                                Strain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury6" value="L_Break" name="hipInjury6" <?php echo in_array('L_Break', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury6" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury7" value="R_Break" id="hipInjury7" <?php echo in_array('R_Break', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury7" class="m-r-0"></label>
                                </div>
                                Break
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury10" value="L_Fracture" name="hipInjury10" <?php echo in_array('L_Fracture', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury10" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury11" value="R_Fracture" id="hipInjury11" <?php echo in_array('R_Fracture', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury11" class="m-r-0"></label>
                                </div>
                                Fracture
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury12" value="L_Osteoarthritis" name="hipInjury12" <?php echo in_array('L_Osteoarthritis', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury12" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury13" value="R_Osteoarthritis" id="hipInjury13" <?php echo in_array('R_Osteoarthritis', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury13" class="m-r-0"></label>
                                </div>
                                Osteoarthritis
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury14" value="L_Arthritis" name="hipInjury14" <?php echo in_array('L_Arthritis', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury14" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury15" value="R_Arthritis" id="hipInjury15" <?php echo in_array('R_Arthritis', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury15" class="m-r-0"></label>
                                </div>
                                Arthritis
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury20" value="L_Bursitis Avascular Necrosis" name="hipInjury20" <?php echo in_array('L_Bursitis Avascular Necrosis', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury20" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury21" value="R_Bursitis Avascular Necrosis" id="hipInjury21" <?php echo in_array('R_Bursitis Avascular Necrosis', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury21" class="m-r-0"></label>
                                </div>
                                Bursitis Avascular Necrosis
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury8" value="L_Pinched nerve" name="hipInjury8" <?php echo in_array('L_Pinched nerve', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury8" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury9" value="R_Pinched nerve" id="hipInjury9" <?php echo in_array('R_Pinched nerve', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury9" class="m-r-0"></label>
                                </div>
                                Pinched nerve
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury18" value="L_Dislocation" name="hipInjury18" <?php echo in_array('L_Dislocation', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury18" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury19" value="R_Dislocation" id="hipInjury19" <?php echo in_array('R_Dislocation', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury19" class="m-r-0"></label>
                                </div>
                                Dislocation
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury16" value="L_Torn ligament" name="hipInjury16" <?php echo in_array('L_Torn ligament', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury16" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury17" value="R_Torn ligament" id="hipInjury17" <?php echo in_array('R_Torn ligament', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury17" class="m-r-0"></label>
                                </div>
                                Torn ligament
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury26" value="L_ITB syndrome" name="hipInjury26" <?php echo in_array('L_ITB syndrome', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury26" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury27" value="R_ITB syndrome" id="hipInjury27" <?php echo in_array('R_ITB syndrome', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury27" class="m-r-0"></label>
                                </div>
                                ITB syndrome
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury22" value="L_Groin injuries" name="hipInjury22" <?php echo in_array('L_Groin injuries', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury22" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury23" value="R_Groin injuries" id="hipInjury23" <?php echo in_array('R_Groin injuries', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury23" class="m-r-0"></label>
                                </div>
                                Groin injuries
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury24" value="L_Fracture/slipped vertebrae" name="hipInjury24" <?php echo in_array('L_Fracture/slipped vertebrae', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury24" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury25" value="R_Fracture/slipped vertebrae" id="hipInjury25" <?php echo in_array('R_Fracture/slipped vertebrae', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury25" class="m-r-0"></label>
                                </div>
                                Fracture/slipped vertebrae
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury39" value="L_Ruptured disc" name="hipInjury39" <?php echo in_array('L_Ruptured disc', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury39" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury40" value="R_Ruptured disc" id="hipInjury40" <?php echo in_array('R_Ruptured disc', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury40" class="m-r-0"></label>
                                </div>
                                Ruptured disc
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury28" value="L_Compressed disk" name="hipInjury28" <?php echo in_array('L_Compressed disk', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury28" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury29" value="R_Compressed disk" id="hipInjury29" <?php echo in_array('R_Compressed disk', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury29" class="m-r-0"></label>
                                </div>
                                Compressed disk
                            </div>
                            <div>

                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury30" value="L_Scoliosis" name="hipInjury30" <?php echo in_array('L_Scoliosis', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury30" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury31" value="R_Scoliosis" id="hipInjury31" <?php echo in_array('R_Scoliosis', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury31" class="m-r-0"></label>
                                </div>
                                Scoliosis
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury32" value="L_Spinal fusion" name="hipInjury32" <?php echo in_array('L_Spinal fusion', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury32" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury33" value="R_Spinal fusion" id="hipInjury33" <?php echo in_array('R_Spinal fusion', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury33" class="m-r-0"></label>
                                </div>
                                Spinal fusion
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury34" value="L_Sciatica" name="hipInjury34" <?php echo in_array('L_Sciatica', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury34" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury35" value="R_Sciatica" id="hipInjury35" <?php echo in_array('R_Sciatica', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury35" class="m-r-0"></label>
                                </div>
                                Sciatica
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="hipInjury36" value="L_Coccyx" name="hipInjury36" <?php echo in_array('L_Coccyx', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury36" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="hipInjury37" value="R_Coccyx" id="hipInjury37" <?php echo in_array('R_Coccyx', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury37" class="m-r-0"></label>
                                </div>
                                Coccyx
                            </div>
                            <div class="m-t-5">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" id="hipInjury38" value="Other" name="hipInjury38" <?php echo in_array('Other', $parq->hipInjury)?'checked':''; ?>/>
                                    <label for="hipInjury38">
                                        Other.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="strong" for="notesHipInjury">Please add the relevant notes relating to injuries selected above</label>
                            <textarea class="form-control" id="notesHipInjury" name="notesHipInjury">{{ $parq->hipInjuryNotes }}</textarea>
                        </div>
                    </div>

                    <div class="shoulders injuryList hidden">
                        <div class="form-group">
                            <h4 class="text-uppercase">Shoulders</h4>
                            L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
                            <?php
                            if(!count($parq->shoulderInjury))
                                $parq->shoulderInjury = [];
                            ?>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury0" value="L_Pain" name="shoulderInjury0" <?php echo in_array('L_Pain', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury0" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury1" value="R_Pain" id="shoulderInjury1" <?php echo in_array('R_Pain', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury1" class="m-r-0"></label>
                                </div>
                                Pain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury2" value="L_Sprain" name="shoulderInjury2" <?php echo in_array('L_Sprain', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury2" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury3" value="R_Sprain" id="shoulderInjury3" <?php echo in_array('R_Sprain', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury3" class="m-r-0"></label>
                                </div>
                                Sprain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury4" value="L_Strain" name="shoulderInjury4" <?php echo in_array('L_Strain', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury4" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury5" value="R_Strain" id="shoulderInjury5" <?php echo in_array('R_Strain', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury5" class="m-r-0"></label>
                                </div>
                                Strain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury6" value="L_Break" name="shoulderInjury6" <?php echo in_array('L_Break', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury6" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury7" value="R_Break" id="shoulderInjury7" <?php echo in_array('R_Break', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury7" class="m-r-0"></label>
                                </div>
                                Break
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury8" value="L_Pinched nerve" name="shoulderInjury8" <?php echo in_array('L_Pinched nerve', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury8" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury9" value="R_Pinched nerve" id="shoulderInjury9" <?php echo in_array('R_Pinched nerve', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury9" class="m-r-0"></label>
                                </div>
                                Pinched nerve
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury10" value="L_Fracture" name="shoulderInjury10" <?php echo in_array('L_Fracture', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury10" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury11" value="R_Fracture" id="shoulderInjury11" <?php echo in_array('R_Fracture', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury11" class="m-r-0"></label>
                                </div>
                                Fracture
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury12" value="L_Bursitis" name="shoulderInjury12" <?php echo in_array('L_Bursitis', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury12" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury13" value="R_Bursitis" id="shoulderInjury13" <?php echo in_array('R_Bursitis', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury13" class="m-r-0"></label>
                                </div>
                                Bursitis
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury14" value="L_Rotator cuff" name="shoulderInjury14" <?php echo in_array('L_Rotator cuff', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury14" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury15" value="R_Rotator cuff" id="shoulderInjury15" <?php echo in_array('R_Rotator cuff', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury15" class="m-r-0"></label>
                                </div>
                                Rotator cuff
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury16" value="L_Torn ligament" name="shoulderInjury16" <?php echo in_array('L_Torn ligament', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury16" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury17" value="R_Torn ligament" id="shoulderInjury17" <?php echo in_array('R_Torn ligament', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury17" class="m-r-0"></label>
                                </div>
                                Torn ligament
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury18" value="L_Dislocation" name="shoulderInjury18" <?php echo in_array('L_Dislocation', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury18" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury19" value="R_Dislocation" id="shoulderInjury19" <?php echo in_array('R_Dislocation', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury19" class="m-r-0"></label>
                                </div>
                                Dislocation
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury20" value="L_Impingement" name="shoulderInjury20" <?php echo in_array('L_Impingement', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury20" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury21" value="R_Impingement" id="shoulderInjury21" <?php echo in_array('R_Impingement', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury21" class="m-r-0"></label>
                                </div>
                                Impingement
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="shoulderInjury22" value="L_Collarbone injury" name="shoulderInjury22" <?php echo in_array('L_Collarbone injury', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury22" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="shoulderInjury23" value="R_Collarbone injury" id="shoulderInjury23" <?php echo in_array('R_Collarbone injury', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury23" class="m-r-0"></label>
                                </div>
                                Collarbone injury
                            </div>
                            <div class="m-t-5">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" id="shoulderInjury24" value="Other" name="shoulderInjury24" <?php echo in_array('Other', $parq->shoulderInjury)?'checked':''; ?>/>
                                    <label for="shoulderInjury24">
                                        Other.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="strong" for="notesShoulderInjury">Please add the relevant notes relating to injuries selected above</label>
                            <textarea class="form-control" id="notesShoulderInjury" name="notesShoulderInjury">{{ $parq->shoulderInjuryNotes }}</textarea>
                        </div>
                    </div>

                    <div class="elbows-n-arms injuryList hidden">
                        <div class="form-group">
                            <h4 class="text-uppercase">Elbow & Arms</h4>
                            L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
                            <?php
                            if(!count($parq->armInjury))
                                $parq->armInjury = [];
                            ?>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury0" value="L_Pain" name="armInjury0" <?php echo in_array('L_Pain', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury0" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury1" value="R_Pain" id="armInjury1" <?php echo in_array('R_Pain', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury1" class="m-r-0"></label>
                                </div>
                                Pain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury2" value="L_Sprain" name="armInjury2" <?php echo in_array('L_Sprain', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury2" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury3" value="R_Sprain" id="armInjury3" <?php echo in_array('R_Sprain', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury3" class="m-r-0"></label>
                                </div>
                                Sprain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury4" value="L_Strain" name="armInjury4" <?php echo in_array('L_Strain', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury4" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury5" value="R_Strain" id="armInjury5" <?php echo in_array('R_Strain', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury5" class="m-r-0"></label>
                                </div>
                                Strain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury6" value="L_Break" name="armInjury6" <?php echo in_array('L_Break', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury6" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury7" value="R_Break" id="armInjury7" <?php echo in_array('R_Break', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury7" class="m-r-0"></label>
                                </div>
                                Break
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury8" value="L_Pinched nerve" name="armInjury8" <?php echo in_array('L_Pinched nerve', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury8" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury9" value="R_Pinched nerve" id="armInjury9" <?php echo in_array('R_Pinched nerve', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury9" class="m-r-0"></label>
                                </div>
                                Pinched nerve
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury10" value="L_Fracture" name="armInjury10" <?php echo in_array('L_Fracture', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury10" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury11" value="R_Fracture" id="armInjury11" <?php echo in_array('R_Fracture', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury11" class="m-r-0"></label>
                                </div>
                                Fracture
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury12" value="L_Bursitis/ Tennis elbow" name="armInjury12" <?php echo in_array('L_Bursitis/ Tennis elbow', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury12" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury13" value="R_Bursitis/ Tennis elbow" id="armInjury13" <?php echo in_array('R_Bursitis/ Tennis elbow', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury13" class="m-r-0"></label>
                                </div>
                                Bursitis/ Tennis elbow
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury14" value="L_Torn ligament" name="armInjury14" <?php echo in_array('L_Torn ligament', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury14" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury15" value="R_Torn ligament" id="armInjury15" <?php echo in_array('R_Torn ligament', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury15" class="m-r-0"></label>
                                </div>
                                Torn ligament
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury16" value="L_Dislocation" name="armInjury16" <?php echo in_array('L_Dislocation', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury16" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury17" value="R_Dislocation" id="armInjury17" <?php echo in_array('R_Dislocation', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury17" class="m-r-0"></label>
                                </div>
                                Dislocation
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="armInjury19" value="L_Arthritis" name="armInjury19" <?php echo in_array('L_Arthritis', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury19" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="armInjury20" value="R_Arthritis" id="armInjury20" <?php echo in_array('R_Arthritis', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury20" class="m-r-0"></label>
                                </div>
                                Arthritis
                            </div>
                            <div class="m-t-5">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" id="armInjury18" value="Other" name="armInjury18" <?php echo in_array('Other', $parq->armInjury)?'checked':''; ?>/>
                                    <label for="armInjury18">
                                        Other.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="strong" for="notesArmInjury">Please add the relevant notes relating to injuries selected above</label>
                            <textarea class="form-control" id="notesArmInjury" name="notesArmInjury">{{ $parq->armInjuryNotes }}</textarea>
                        </div>
                    </div>

                    <div class="wrist-n-hand injuryList hidden">
                        <div class="form-group">
                            <h4 class="text-uppercase">Wrist & Hands</h4>
                            L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
                            <?php
                            if(!count($parq->handInjury))
                                $parq->handInjury = [];
                            ?>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury0" value="L_Pain" name="handInjury0" <?php echo in_array('L_Pain', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury0" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury1" value="R_Pain" id="handInjury1" <?php echo in_array('R_Pain', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury1" class="m-r-0"></label>
                                </div>
                                Pain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury2" value="L_Sprain" name="handInjury2" <?php echo in_array('L_Sprain', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury2" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury3" value="R_Sprain" id="handInjury3" <?php echo in_array('R_Sprain', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury3" class="m-r-0"></label>
                                </div>
                                Sprain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury4" value="L_Strain" name="handInjury4" <?php echo in_array('L_Strain', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury4" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury5" value="R_Strain" id="handInjury5" <?php echo in_array('R_Strain', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury5" class="m-r-0"></label>
                                </div>
                                Strain
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury6" value="L_Break" name="handInjury6" <?php echo in_array('L_Break', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury6" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury7" value="R_Break" id="handInjury7" <?php echo in_array('R_Break', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury7" class="m-r-0"></label>
                                </div>
                                Break
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury8" value="L_Pinched nerve" name="handInjury8" <?php echo in_array('L_Pinched nerve', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury8" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury9" value="R_Pinched nerve" id="handInjury9" <?php echo in_array('R_Pinched nerve', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury9" class="m-r-0"></label>
                                </div>
                                Pinched nerve
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury10" value="L_Fracture" name="handInjury10" <?php echo in_array('L_Fracture', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury10" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury11" value="R_Fracture" id="handInjury11" <?php echo in_array('R_Fracture', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury11" class="m-r-0"></label>
                                </div>
                                Fracture
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury12" value="L_Arthritis" name="handInjury12" <?php echo in_array('L_Arthritis', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury12" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury13" value="R_Arthritis" id="handInjury13" <?php echo in_array('R_Arthritis', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury13" class="m-r-0"></label>
                                </div>
                                Arthritis
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury14" value="L_Torn ligament and tendons" name="handInjury14" <?php echo in_array('L_Torn ligament and tendons', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury14" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury15" value="R_Torn ligament and tendons" id="handInjury15" <?php echo in_array('R_Torn ligament and tendons', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury15" class="m-r-0"></label>
                                </div>
                                Torn ligament and tendons
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury16" value="L_Dislocation" name="handInjury16" <?php echo in_array('L_Dislocation', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury16" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury17" value="R_Dislocation" id="handInjury17" <?php echo in_array('R_Dislocation', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury17" class="m-r-0"></label>
                                </div>
                                Dislocation
                            </div>
                            <div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" id="handInjury18" value="L_Carpal Tunnel Syndrome" name="handInjury18" <?php echo in_array('L_Carpal Tunnel Syndrome', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury18" class="m-r-0"></label>
                                </div>
                                <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
                                    <input type="checkbox" name="handInjury19" value="R_Carpal Tunnel Syndrome" id="handInjury19" <?php echo in_array('R_Carpal Tunnel Syndrome', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury19" class="m-r-0"></label>
                                </div>
                                Carpal Tunnel Syndrome
                            </div>
                            <div class="m-t-5">
                                <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" id="handInjury20" value="Other" name="handInjury20" <?php echo in_array('Other', $parq->handInjury)?'checked':''; ?>/>
                                    <label for="handInjury20">
                                        Other.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="strong" for="notesHandInjury">Please add the relevant notes relating to injuries selected above</label>
                            <textarea class="form-control" id="notesHandInjury" name="notesHandInjury">{{ $parq->handInjuryNotes }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">

            <div class="checkbox clip-check check-primary checkbox-inline m-b-0 alertHide no-injury-div">
                <input type="checkbox" name="noInjury" id="noInjury"  value="1" {{ $parq->noInjury == 1 ? 'checked':'' }}>
                <label for="noInjury">
                    <strong>No Injury</strong>
                </label>
            </div>
            <button type="button" class="btn btn-primary res-btn-savedata submit-step injuryAlert" style="margin-bottom: 0px;">Save</button>
            <button type="button" class="btn btn-danger alertHide" data-dismiss="modal">Close</button>
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


<div class="modal" id="medCondNotesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Notes</h4>
            </div>
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('medCondNotes', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('medCondNotesInput', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" type="button">Submit</button>
            </div>
        </div>
    </div>
</div>
<div id="parq-step3" class="modal fade" role="dialog">
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
    $(document).on('click','.parq-step3',function(){
        $(this).attr('data-toggle','modal')
        $(this).attr('data-target','#parq-step3')
        $("#parq-step3").attr('aria-modal',true)
        $("#parq-step3").addClass('in')
        var message = $(this).data('message');
        $("#parq-step3").find('.message').html(message);
    })
</script>
@endsection

@section('required-script')

<script>
    $('#m-selected-step').on('change', function() {
        var stepNum = parseInt($('#m-selected-step').val());


        if(stepNum == 3) {
            setTimeout(function() {
                $('#medicalCondition, #relMedicalCondition').trigger('change');
            }, 1200)
        }
    });
</script>
@stop