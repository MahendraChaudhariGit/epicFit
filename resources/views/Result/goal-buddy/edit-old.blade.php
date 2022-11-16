@extends('Result.goal-buddy.main_goal-old')

@section('required-styles')
{!! Html::style('result/css/autocomplete.css') !!}
{!! Html::style('result/plugins/tipped-tooltip/css/tipped/tipped.css') !!}

{!! Html::style('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
{!! Html::style('result/parq-theme/goal.css') !!}

<style type="text/css">
 textarea.form-control{
  /*min-height: 70px;*/
  resize: vertical;
  overflow:auto;
}
.outborder textarea.form-control:focus{
  min-height: 100px;
}
.outborder textarea.form-control[aria-invalid="false"]{
  min-height: 100px;
}
.outborder .help-block{
  margin:0px;
}
.modal-popup{
/* background: #212121;
 color: white;*/
 font-weight: normal;
}
.modal-popup .btn-default {
  color: #fff;
  background-color: #f94211 !important;
  border-color: #f94211 !important;
}
.modal-popup .modal-footer{
  text-align: center;
}
.question-mark{
  font-size: 25px;
  color: #f94211;
}
.content-right .form-group .tooltip-sign.tooltip_btn{
  display: inline-block;
  vertical-align: top;
}
.content-right .form-group .tooltip_btn i.question-mark{
  font-size: 25px;
  color: #f94211;
}
.content-right .form-group .tooltip-sign.tooltip_btn:hover{
  text-decoration: none;
}
.show_task-section ul{
   padding-left: 18px;
}
@media (max-width: 768px){
  table th, table td{
   padding:4px !important;
  }
  #client-datatable tr td:nth-child(3),  #client-datatable tr td:nth-child(4) {
    display: none
}
  #client-datatable-task tr td:nth-child(2),  #client-datatable-task tr td:nth-child(3) {
    display: none
}
.picCropModel .modal-dialog{
  width: 90%;
}
}
.general-notes-button{
     text-align: left;
    width: max-content;
    float: left;
    margin-right: 10px;
}
.general-notes-button button{
    color: #fff;
    background-color: #f94211;
    border-color: #f94211;
}
#general-notes-popup .modal-header .close {
    margin-top: -8px;
}
.table-responsive table td, .table-responsive table th{
  font-size: 12px;
  padding: 5px !important;
}
.table-responsive table td {
      word-break: break-word;
      white-space: initial !important;
}
.table-responsive table th{
  vertical-align: top !important;
}
textarea::-webkit-scrollbar {
  display: none;
}

/* Hide scrollbar for IE, Edge add Firefox */
textarea{
  -ms-overflow-style: none;
  scrollbar-width: none; /* Firefox */
}
</style>

@stop

@section('content')
<span class="inmotion-total-slides  hidden-xs">QUESTIONS<br>
  <span class="inmotion-ts-active-num question-step">00</span>
  <span class="inmotion-ts-active-separator">/</span>
  <span class="inmotion-ts-active-all all-question-step">20</span>
</span>

<span class="qodef-grid-line-right">
  <span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:-450, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); ">
    
  </span>
</span>
<div class="container-fluid">
  <div class="watermark"><p>EPIC GOAL</p></div>
   <div class="row row-height">

      <div class="col-xl-6 col-lg-6 col-md-5 col-xs-11 content-left">
         <div class="content-left-wrapper">
            <img src="{{asset('assets/images/BM-slimming_1.png')}}" alt="" class="img-fluid slide-img">
           
           
         </div>
         <!-- /content-left-wrapper -->
      </div>

      <div class="col-xl-6 col-lg-5 col-md-5 col-xs-12 content-right" id="start">              
         <div id="wizard_container">



            <div id="top-wizard">
 <h2 class="steps-name">DEFINE YOUR GOAL</h2>
               <div id="" class="swMain parqForm">

<!--                   <ul id="wizard-ul" class="top-step anchor">

                            <li class="selected">

                                <a href="javascript:void(0)" class="formStepfirst editForm selected" isdone="1" rel="1" data-value="1">

                                    <div class="stepNumber"> 1 </div>

                                    <span class="stepDesc"><small>Define your Goal</small></span>

                                </a>

                            </li>

                            <li>

                                <a href="javascript:void(0)" class="formStepSecond editForm  disabled" isdone="0" rel="2" data-value="2">

                                    <div class="stepNumber"> 2 </div>

                                    <span class="stepDesc"><small>Establish your Mile Stones</small></span>

                                </a>

                            </li>

                            <li>

                                <a href="javascript:void(0)" class="formStepThird editForm  disabled  " isdone="0" rel="3" data-value="3">

                                    <div class="stepNumber"> 3 </div>

                                    <span class="stepDesc"><small>Establish New Habit</small></span>

                                </a>

                            </li>

                            <li>

                                <a href="javascript:void(0)" class="formStepFourth editForm disabled" isdone="0" rel="4" data-value="4">

                                    <div class="stepNumber"> 4 </div>

                                    <span class="stepDesc"><small>Create Tasks</small></span>

                                </a>

                            </li>

                            <li>

                                <a href="javascript:void(0)" class="formStepFive editForm disabled" isdone="0" rel="5" data-value="5">

                                    <div class="stepNumber"> 5 </div>

                                    <span class="stepDesc"><small>Smart Review </small></span>

                                </a>

                            </li>

                  </ul> -->

               </div>

              <!--  <span id="location"></span>

               <div id="progressbar"></div> -->



            </div>

            <!-- /top-wizard -->

            <form id="wrapped" method="post" enctype="multipart/form-data">
                <input type="hidden" id="hbt_rec_tsk" value="none">
               <input type="hidden" id="all-my-friends" value="{{ $my_friends }}">
                <input type="hidden" id="review_data" value="{{isset($review_data)?$review_data:''}}" name="review_data">

               <input type="hidden" class="form-control" id="habit-index" name="habit_index" value="0">

               <input type="hidden" class="form-control" id="no-of-habit" name="no_of_habit" value="">

               <input type="hidden" class="form-control" id="m-selected-step" name="m-selected-step" value="0">

               <input type="hidden" class="form-control" id="editFormWizard" name="editFormWizard" value="0">



               <input type="hidden" class="form-control" id="goal-habit-id" name="goalHabitId" value="">

               <input type="hidden" class="form-control" id="habit-id" name="habit_id" value="">

               <input type="hidden" class="form-control" id="update-record" value="" name="update_value">

               <input type="hidden" value ="{{isset($goalDetails)?$goalid:null}}" name ="lastId" id ="last-insert-id"> <input type="hidden" class="form-control" id="task-index" name="task_index" value="0">

               <input type="hidden" class="form-control" id="no-of-task" name="no_of_task" value="">

               <input type="hidden" min class="form-control" id="task-id" value="{{$taskDetails?$taskDetails->id:null}}" name="task_id">
               <input type="hidden" min class="form-control" id="task_id_new" value="{{$taskDetails?$taskDetails->id:null}}" name="task_id_new">
               
               <input type="hidden"  id="SYG3_priority" name="SYG3_priority"  placeholder="" class="form-control mb">

               <input type="hidden" ng-keypress="pressEnter($event)" id="SYG3_see_task0" name="SYG3_see_task"  placeholder="" class="form-control mb">

               {{-- <input type="hidden"  id="goal_milestones_id" value="{{$goalId}}" name="goalmilestones_id"> --}}

               <input type="hidden"  id="habitRecValue" name="habitRecValue"  value="">

               <input type="hidden"  id="goalDueDate" value="" name="goal_due_date">

               <input type="hidden"  id="milestones_id" value="{{isset($mileStoneIdStr)?$mileStoneIdStr:null}}" name="milestones_id">

               <input type="hidden"  id="stones_form_button"  value="0">
               <?php

                 $goalId = '';

           

                 if(isset($goalDetails) && $goalDetails) {

                   $goalId = $goalDetails->id;
                 }

                  /* if(isset($milestonesGoalId))

                   $goalId = $milestonesGoalId;

                   elseif(isset($milestonesData) && count($milestonesData))

                   $goalId = $milestonesData[0]->goal_id; */

               ?>                  

               <input type="hidden"  id="goal_milestones_id" value="{{$goalId}}" name="goalmilestones_id">

               <input id="website" name="website" type="text" value="">

               <div id="middle-wizard">

                  <!-- /step-->

                  <div class="step data-step newGoalStep " data-step="1" data-value="0">

                     <div class="form-group">

                        <label class="container_radio version_2">CREATE NEW GOAL 

                        <input type="radio" name="chooseGoal" id="create_new_goal" value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '' ? 'checked' : ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">CHOOSE FROM ONE OF OUR TEMPLATES 

                        <input type="radio" name="chooseGoal" id="choose_form_template" value="choose_form_template"{{ isset($goalDetails) && $goalDetails->gb_template != '' ? 'checked' : ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                  </div>

                  <!-- /step-->

                  <div class="step data-step goal-images goal-predifine-template" id="create_new_goal" data-step="2">

                        <div class="row">
                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                        <div class="heading-text border-head mb-10">
                          <div class="watermark1"><span>1.</span></div>
                           <label class="steps-head">Don't know how to define your <b>EPIC</b> goal? <br>Choose one from a  <strong>template:</strong>  </label>
                        </div>
                        <div class="tooltip-sign mb-10">
                           <a href="javascript:void(0)" class="goal-step" 
                           data-message="Tooltip not provided on the documents"
                           data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a>
                        </div>
                         
                       </div>

                     <div class="row">

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2">
                              <img src="{{asset('result/images/weightmanagement.jpg')}}" >

                           <input type="radio" name="template" data-id='1'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '1' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/drop_a_size.jpg')}}">

                           <input type="radio" name="template" data-id='2'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '2' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/eat.jpg')}}">

                           <input type="radio" name="template" data-id='3'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '3' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/improve_h.jpg')}}">

                           <input type="radio" name="template" data-id='4'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '4' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                        <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/reduce_stress.jpg')}}">

                           <input type="radio" name="template" data-id='5'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '5' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                        <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/improve_my_sleep.jpg')}}">

                           <input type="radio" name="template" data-id='6'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '6' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                        <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/improve_health.jpg')}}">

                           <input type="radio" name="template" data-id='7'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '7' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/injury.jpg')}}">

                           <input type="radio" name="template" data-id='8'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '8' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/increase_activity.jpg')}}">

                           <input type="radio" name="template" data-id='9'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '9' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/balance.jpg')}}">

                           <input type="radio" name="template" data-id='10'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '10' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/health.jpg')}}">

                           <input type="radio" name="template" data-id='11'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '11' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/improve_posture.jpg')}}">

                           <input type="radio" name="template" data-id='12'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '12' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/time_man.jpg')}}">

                           <input type="radio" name="template" data-id='13'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '13' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                        <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/improve_per.jpg')}}">

                           <input type="radio" name="template" data-id='14'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '14' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/improve_c.jpg')}}">

                           <input type="radio" name="template" data-id='15'  value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '15' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                         <div class="form-group col-md-3 col-xs-4">

                           <label class="container_radio version_2"><img src="{{asset('result/images/become_proactive.jpg')}}">

                           <input type="radio" name="template" data-id='16' value="create_new_goal"{{ isset($goalDetails) && $goalDetails->gb_template == '16' ? 'checked' : ''}}>

                           <span class="checkmark"></span>

                           </label>

                        </div>

                     </div>

                  </div>

                  <!-- /step-->

                  <div class="step data-step goalName" data-step="3" data-value='0'>

                   <div class="row">
                    <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                    <div class="heading-text border-head mb-10">
                      <div class="watermark1"><span>2.</span></div>
                      <label class="steps-head">Name Your <b>EPIC</b> Goal? *  </label>
                    </div>
                    <div class="tooltip-sign mb-10">
                      <a href="javascript:void(0)" class="goal-step" 
                      data-message="You have selected to create your own unique <span style='color:#f64c1e'><b>EPIC</b> Goal</span>. Please provide a short and definitive name for the Goal you want achieve, this may assist in making it easier to bring it to life and make it reality.
                      <br/><br/>
                      Example may include: <br/>
                      • compete in an event<br/>
                      • complete a marathon/half marathon<br/>
                      • improve relationships<br/>
                      • Master Box Jump<br/>
                      • Improve 100m Sprint
                      <br/><br/>
                      Choose a descriptive name that best describes your Goal and the way you envision it."
                      data-message1="You have selected weight management as your template, a short and definitive name of what you want to achieve can help bring it to life and make it a reality.
                      <br/><br/>
                      Example may include: <br/>
                      ·<b> Lose Weight </b>(This may be a certain weight loss or get down to a certain weight) <br/>
                      ·<b> Gain Weight </b>(This may be a certain weight gain or get up to a certain weight) <br/>
                      ·<b> Build Muscle </b>(This may be a related to a certain body composition) <br/>
                      ·<b> Manage & Maintain Weight </b>(This may be maintaining a certain weight that you are happy with)
                      <br/><br/>
                      If you would like to select something unique to you, then choose a name that best describe your Goal and the way you envision it."><i class="fa fa-question-circle question-mark"></i></a>
                    </div>

                  </div>

                     <div class="form-group tooltip-hover append-template-goal-name">
                      <div class="outborder">
                        <textarea ng-mouseenter="pressEnter($event)" data-toggle="tooltip"  data-html="true" title="" data-autoresize id="name_goal" name="name_goal" ng-model="name_goal" ng-init="name_goal='{{ isset($goalDetails) ? $goalDetails->gb_goal_name: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_goal_name:null}}</textarea>
                      </div>
                     

                     </div>

                  </div>

                  <!-- /step-->

                  <div class="step data-step" data-step="4">

                  <div class="row">
                    <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>3.</span></div>
                        <label class="steps-head">Describe what you want to <b>achieve</b>? *  </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                        <a href="javascript:void(0)" class="goal-step" 
                           data-message="Describe your desired result or outcome, including the changes you wish to see along the way, and what aspect of the Goal matters most to you.
                           <br/><br/>
                           Examples may be:<br/>
                              • Limit stress<br/>
                              • To complete a certain event in a certain time or in a certain way<br/>
                              • performing at a competitive level in a sport<br/>
                              • leaving work stress in the work environment<br/>
                              • achieving a balanced and sustainable diet/ training routine<br/>
                              • reaching 10% bodyfat
                               <br/><br/>
                           An example of your description may be if your goal is to limit stress: <br/>
                              • describe exactly what type of stress you are wanting to work on. <br/>
                              • Is that what you are wanting to remove yourself from 50% of the stressful situation you currently find yourself in weekly?<br/>
                              • Do you want to learn to switch off, rather than bringing work stress into home environment?
                               <br/><br/>
                           Additional information is critical as the more information and the more effort you put into this the more likely you are to succeed.
                           <br/><br/>
                           Add a Photo of what you are wanting to achieve, a visual idea may be more enticing instead of just words or saying it."
                           data-message1="Describe your desired result or outcome, including the changes you wish to see along the way, and what aspect of the Goal matters most to you.
                           <br/><br/>
                           <b>For example</b>
                           <br/><br/>
                           <b>Lose weight,</b> describe exactly how much weight you would like to lose and in what area?<br/>
                           <b>Gain muscle,</b> describe exactly how much and focus on what area?<br/>
                           <b>Tone up,</b> describe exactly which areas and how much you would like to tone up?<br/>
                           <b>Lower BFP,</b> describe exactly how much you want to lose or what you want to be?
                           <br/><br/>
                           Additional information is critical as the more information and the more effort you put into this the more likely you are to succeed.
                           <br/><br/>
                           Add a Photo of what you are wanting to achieve, a visual idea may be more enticing instead of just words or saying it."><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>

                     <div class="form-group template-description-achieve">
                      <div class="outborder">
                        <textarea ng-blur="pressEnter($event)" id="description" data-toggle="tooltip" data-html="true" title="" data-autoresize id="describe_achieve" name="describe_achieve" ng-model="describe_achieve" ng-init="describe_achieve='{{ isset($goalDetails) ? $goalDetails->gb_achieve_description: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_achieve_description:null}}</textarea>
                      </div>
                     </div>

                     <div class="">
                        <div class="row d-flex">
                        <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                           <div class="heading-text border-head mb-10">

                        <div class="padding-left-20 custom-padding">

                           <div class="form-group upload-group m-t-10" style="padding-left: 3px">

                              <input type="hidden" name="prePhotoName" value="{{isset($goalDetails)?$goalDetails->gb_image_url:null}}">

                              <input type="hidden" name="entityId" value="">

                              <input type="hidden" name="saveUrl" value="photo/save" >

                              <input type="hidden" name="photoHelper" value="SYG" >

                              <input type="hidden" name="cropSelector" value="">

                              <label class="btn btn-primary btn-file add-photo"> <span><i class="fa fa-plus"></i> Add Photo</span>

                              <input type="file" class="hidden filePreviewCls" onChange="fileSelectHandler(this)" accept="image/*">

                              </label>

                              <div class="m-t-10">

                                 @if(isset($goalDetails->gb_image_url) && ($goalDetails->gb_image_url != ''))

                                 <img src="{{ dpSrc($goalDetails->gb_image_url) }}" class="SYGPreviewPics previewPics"  />

                                 @else

                                 <img class="hidden SYGPreviewPics previewPics" />

                                 @endif

                              </div>

                              <span class="help-block m-b-0"></span>

                              <input type="hidden" name="logo" value="">

                           </div>

                        </div>

                     </div>

                     <div class="tooltip-sign mb-10">
                        <a href="javascript:void(0)" class="goal-step" 
                           data-message="Please provide an accurate image or photo of what you are aiming to achieve, please ensure the image is achievable and realistic to your expectations."
                           data-message1="Please provide an accurate image or photo of what you are aiming to achieve, please ensure the image is achievable and realistic to your expectations."><i class="fa fa-question-circle question-mark"></i></a>
                     </div> 
                     </div>
                  </div>

                  </div>

                  <!-- /step-->

                  

                  <div class="step data-step" data-step="5">

                         <div class="row">
                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>4.</span></div>
                        <label class="steps-head">Is this <b>EPIC</b> Goal an <b>immediate priority</b> for you? </label>
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
                           <b>Example:</b> <br/>
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
                           data-message1="<b>Yes</b>—this <span style='color:#f64c1e'><b>EPIC</b> Goal</span> is particularly important and critical to my happiness and confidence.
                           <br/><br/>
                           <b>No</b>—this is a basic Goal and there may be a more important <span style='color:#f64c1e'><b>EPIC</b> Goal</span> for me to address.
                           <br/><br/>
                           Are there immediate negative effects that may arise as a direct result of putting this goal
                           off? And are you willing to prioritise this <span style='color:#f64c1e'><b>EPIC</b> Goal</span> and associated tasks over other tasks and life events?
                           <br/><br/>
                           <b>Example:</b> <br/>
                           Are there any immediate health issues to be addressed?<br/>
                           Is there mounting stress as a result of failing to address an issue? <br/>
                           Is a lack of results affecting your motivation and or confidence levels? <br/>
                           Are poor choices affecting you in anyway financially?
                           <br/><br/>
                           Example of tasks that may need to be implemented into your lifestyle:<br/>
                           Are you willing to sacrifice 2 coffees per week to pay for an extra training session? <br/>
                           Are you willing to sacrifice time spent with loved ones or friends who currently have a negative impact on your lifestyle?"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>

                     <div class="form-group">

                        <label class="container_radio version_2">No

                        <input type="radio" name="goal" value="No"{{ isset($goalDetails) && $goalDetails->gb_is_top_goal== "No"? 'checked' : '' }} data-toggle="modal" data-target="#temp-modal">

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">Yes

                        <input type="radio" name="goal" value="Yes" {{ isset($goalDetails) && $goalDetails->gb_is_top_goal== "Yes"? 'checked' : '' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                  </div>

                  <!-- /step-->

                  <?php

                  $gb_change_life_reason_details = [];

    

                  if(isset($goalDetails)) {

                      $gb_change_life_reason_details = $goalDetails->gb_change_life_reason_details;

                  }

    

                  ?>

    

                <script>

                    $(document).ready(function() {

                        setTimeout(function () {

                            var selected_change_life = {!! json_encode($gb_change_life_reason_details) !!};

                           for(var i = 0; i < selected_change_life.length; i++) {

                                var item = selected_change_life[i];

    

                                $('.life_change_reason').each(function(){

                             var lifeChange = $(this).val();

                                if(lifeChange == item){

                                $(this).prop("checked", true);

                                }

                            })

                            }
                            
                            if($('.life_change_reason').is(":checked")){
                                $("#gb_change_life_reason_other").show();
                            }

                        }, 1000);

                    });

                </script>

                   <div class="step data-step" data-step="6">
                     <div class="row">
                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>5.</span></div>
                        <label class="steps-head">By accomplish Your <b>EPIC</b> Goal, how will your  <b>life change</b>? *</label>
                      </div>
                      <div class="tooltip-sign mb-10">
                        <a href="javascript:void(0)" class="goal-step" 
                           data-message="What aspect/aspects of your life if any will be positively or negatively affected by working towards and making lifestyle changes to reach your goal?
                           <br/><br/>
                           How will achieving this Goal affect and make my life better. How will it improve my life and how will I benefit from it?
                           <br/><br/>
                           <b>Health</b>— will I be healthier and less prone to illness and injury; how will I feel?
                           <br/><br/>
                           <b>Mental & Emotional Wellness</b>— Will my outlook on life and my mood be improved and if so, how?
                           <br/><br/>
                           <b>Lifestyle</b>— Will my daily routines, habits and behaviours change for the better and how would that make me feel?
                           <br/><br/>
                           <b>Self-Image</b>— Will I be more self-confident and be more confident in being me, will this make me do more?
                           <br/><br/>
                           <b>Family/Home Environment</b>—Will family and friends’ benefit, and will I be treated and treat them differently?
                           <br/><br/>
                           <b>Personal Relationships</b>— Will my relationship change for the better and will I be a better person?
                           <br/><br/>
                           <b>Career Satisfaction</b>—Will my careers and work environment change and how will it change for the better?
                           <br/><br/>
                           <b>Financial Situation</b>—How will my financial situation improve, will I be more confident, more efficient, be more proactive and do more which may result in a change of job, a promotion, or a pay increase?
                           <br/><br/>
                           <b>Other</b> – Add any other changes that achieving your Goal may result in this may be anything you believe will happen once you have achieved your desired <span style='color:#f64c1e;'>RESULT</span>."
                           data-message1="How will achieving this <span style='color:#f64c1e'><b>EPIC</b> Goal</span> affect and make my life better. How will it improve my life and how will I benefit from it?
                           <br/><br/>
                           <b>Health</b>— will I be healthier and less prone to illness and injury; how will I feel?
                           <br/><br/>
                           <b>Mental & Emotional Wellness</b>— Will my outlook on life and my mood be improved and if so, how?
                           <br/><br/>
                           <b>Lifestyle</b>— Will my daily routines, habits and behaviours change for the better and how would that make me feel?
                           <br/><br/>
                           <b>Self-Image</b>— Will I be more self-confident and be more confident in being me, will this make me do more?
                           <br/><br/>
                           <b>Family/Home Environment</b>—Will family and friends’ benefit, and will I be treated and treat them differently?
                           <br/><br/>
                           <b>Personal Relationships</b>— Will my relationship change for the better and will I be a better person?
                           <br/><br/>
                           <b>Career Satisfaction</b>—Will my careers and work environment change and how will it change for the better?
                           <br/><br/>
                           <b>Financial Situation</b>—How will my financial situation improve, will I be more confident, more efficient, be more proactive and do more which may result in a change of job, a promotion, or a pay increase?
                           <br/><br/>
                           <b>Other</b>– Add any other changes that achieving your <span style='color:#f64c1e'><b>EPIC</b> Goal</span> may result in this may be anything you believe will happen once you have achieved your desired <span style='color:#f64c1e;'>RESULT</span>."><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>

                  <div class="form-group">

                     <label class="container_check version_2">Improve Health     

                     <input type="checkbox" name="life-change" class="life_change_reason" value="Improve_health">

                     <span class="checkmark"></span>

                     </label>

                  </div>

                  <div class="form-group">

                     <label class="container_check version_2">Improve Mental and Emotional Wellness    

                     <input type="checkbox" name="life-change" class="life_change_reason" value="Improve_mental_and_emotional_wellness">

                     <span class="checkmark"></span>

                     </label>

                  </div>

                  <div class="form-group">

                     <label class="container_check version_2"> Improve Lifestyle      

                     <input type="checkbox" name="life-change" class="life_change_reason" value="Improve_lifestyle">

                     <span class="checkmark"></span>

                     </label>

                  </div>

                  <div class="form-group">

                     <label class="container_check version_2"> Improve Self Image      

                     <input type="checkbox" name="life-change" class="life_change_reason" value="Improve_self_image">

                     <span class="checkmark"></span>

                     </label>

                  </div>

                  <div class="form-group">

                     <label class="container_check version_2">Improve Family/Home Environment       

                     <input type="checkbox" name="life-change" class="life_change_reason" value="Improve_family_home_environment">

                     <span class="checkmark"></span>

                     </label>

                  </div>

                  <div class="form-group">

                     <label class="container_check version_2">Improve Personal Relationships        

                     <input type="checkbox" name="life-change" class="life_change_reason" value="Improve_personal_relationships">

                     <span class="checkmark"></span>

                     </label>

                  </div>

                  <div class="form-group">

                     <label class="container_check version_2">Improve Career Satisfaction    

                     <input type="checkbox" name="life-change" class="life_change_reason" value="Improve_career_satisfaction">

                     <span class="checkmark"></span>

                     </label>

                  </div>

                  <div class="form-group">

                     <label class="container_check version_2">Improve Financial Situation     

                     <input type="checkbox" name="life-change" class="life_change_reason" value="Improve_financial_situation">

                     <span class="checkmark"></span>

                     </label>

                  </div>

                  <div class="form-group">

                     <label class="container_check version_2">Other    

                     <input type="checkbox" name="life-change" class="life_change_reason" value="Other" id="other">

                     <span class="checkmark"></span>

                     <textarea rows="7" class="form-control" id="gb_change_life_reason_other" name="gb_change_life_reason_other">{{isset($goalDetails)?$goalDetails->gb_change_life_reason_other:''}}</textarea>

                     </label>

                  </div>

               </div>

                  <!-- /step-->

                  <div class="step data-step" data-step="7">
                     <div class="row">
                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>6.</span></div>
                        <label class="steps-head">Why is it <b>important</b> to you to accomplish this <b>EPIC</b> Goal? *
                        </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                        <a href="javascript:void(0)" class="goal-step" 
                           data-message="Why is this an intrinsic Goal? What makes this Goal important to you and how long have you wanted to achieve this Goal for? What does it mean to achieve this goal? Does it affect others in your life positively? 
                           <br/><br/>
                           If this Goal is important to you and you are doing it for yourself and not to please or cater for someone else, you are more likely to achieve the desired <span style='color:#f64c1e;'>RESULT</span>. 
                           <br/><br/>
                           Make your WHY big enough to overcome the How and the What!
                           <br/><br/>
                           Include all the positive effects related to all aspects of your life will result from achieving this Goal?
                           <br/><br/>
                           Examples include:
                           <br/><br/>
                               • Feeling Confident,<br/> 
                               • Looking Healthier <br/>
                               • Feeling stronger.
                               <br/><br/>
                           Explain why this is an intrinsic goal and how long have you wanted to achieve this goal for?"
                           data-message1="Why is this an intrinsic Goal? What makes this <span style='color:#f64c1e'><b>EPIC</b> Goal</span> important to you and how long have you wanted to achieve this Goal for? 
                           <br/><br/>
                           If this <span style='color:#f64c1e'><b>EPIC</b> Goal</span> is important to you and you are doing it for yourself and not to please or cater for someone else, you are more likely to achieve the desired <span style='color:#f64c1e;'>RESULT</span>. 
                           <br/><br/>
                           Make your WHY big enough to overcome the How and the What!
                           <br/><br/>
                           What are the positive effects on all aspects of your life as a result of achieving it?
                           <br/><br/>
                           <b>Example includes</b> feeling confident, looking, and feeling stronger."><i class="fa fa-question-circle question-mark"></i></a>
                     </div>

                   </div>

                     <div class="form-group template-accomplish">
                      <div class="outborder">
                        <textarea ong-blur="pressEnter($event)" data-toggle="tooltip" data-html="true" title="" data-autoresize rows="3" id="accomplish" name="accomplish" ng-model="accomplish" ng-init="accomplish='{{ isset($goalDetails) ? $goalDetails->gb_important_accomplish: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_important_accomplish:null}}</textarea>
                      </div>
                     </div>

                  </div>

                  <!-- /step-->

                  <div class="step data-step" data-step="8">

                        <div class="row">
                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>7.</span></div>
                        <label class="steps-head">What happen if you do <b>not achieve</b> your <b>EPIC</b> Goal? * </label>
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
                           Is it negatively affecting them?"><i class="fa fa-question-circle question-mark"></i></a>
                     </div>

                    </div>

                     <div class="form-group fail_description">
                      <div class="outborder">
                        <textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="" data-autoresize id="fail-description" name="fail-description" ng-model="fail_description" ng-init="fail_description='{{ isset($goalDetails) ? $goalDetails->gb_fail_description: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_fail_description:null}}</textarea>
                      </div>
                     </div>

                  </div>

                  <!-- /step-->

                  <div class="step data-step" data-step="9">

                         <div class="row">
                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>8.</span></div>
                        <label class="steps-head">Why is this <b>EPIC</b> Goal <b>relevant</b>? * </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                     <a href="javascript:void(0)" class="goal-step" 
                           data-message="To address this question, you need to ask simple question such as:
                           <br/><br/>
                           What makes this goal intrinsic and important to you specifically?<br/>
                           Why is this Goal more important to you than everyone else around you?<br/>
                           How does it relate directly to you and your current position in life?<br/>
                           What aspect of your life does it affect? <br/>
                           Why is it specific to your current position?<br/>
                           What internal changes will you experience that may not be realised by other individuals initially?<br/>
                           Are you doing this for you are as a request from someone else?"
                           data-message1="Why is this <span style='color:#f64c1e'><b>EPIC</b> Goal</span> more important to you than everyone else around you?<br/>
                           What internal changes will you fill that other may not realise initially?<br/>
                           (Why is it specific to your current position)<br/>
                           Are you doing this for you are as a request from someone else?"><i class="fa fa-question-circle question-mark"></i></a>
                     </div>

                    </div>



                     <div class="form-group template-relevant-goal">
                      <div class="outborder">
                        <textarea ng-blur="pressEnter($event)" id="relevant_goal" data-toggle="tooltip" title="" data-autoresize id="gb_relevant_goal" name="gb_relevant_goal" ng-model="gb_relevant_goal" ng-init="gb_relevant_goal='{{ isset($goalDetails) ? $goalDetails->gb_relevant_goal: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_relevant_goal:null}}</textarea>
                      </div>
                     </div>

                  </div>

                  <!-- /step-->

                  <div class="step data-step" data-step="10">

                         <div class="row">
                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>9.</span></div>
                        <label class="steps-head">Is this goal associated with a <b>life event or special occasion</b>? * </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                          <a href="javascript:void(0)" class="goal-step" 
                           data-message="Do you have any special occasion coming up? This may be anything from:
                           <br/><br/>
                           • Turning a certain age and changing your perspective on life and order of priorities? <br/>
                           • Needing to perform at a certain event? <br/>
                           • Getting married?<br/>
                           • Feeling good and confident on holiday?
                           <br/><br/>
                           Do you need to achieve this goal in time for the event? (Wedding, holiday, or summer)
                           <br/><br/>
                           Is there a larger life event or stage of life that you may be reaching that has shifted your mindset and helped you decide to act? (Closing in on 50's or possibly of children or grandchildren)"
                           data-message1="Do you have any special occasion coming up?<br/>
                           Do you need to achieve this <span style='color:#f64c1e'><b>EPIC</b> Goal</span> in time for the event? (Wedding)<br/>
                           Is there a larger life event or stage of life that you may be reaching that has shifted your midst and helped you decide to act? (Closing in on 50's or Possibility of children or grandchildren)"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>



                     <div class="form-group template-relevant-goal-event">
                      <div class="outborder">
                        <textarea ng-blur="pressEnter($event)" data-toggle="tooltip" title="" data-autoresize id="gb_relevant_goal_event" name="gb_relevant_goal_event" ng-model="gb_relevant_goal_event" ng-init="gb_relevant_goal_event='{{ isset($goalDetails) ? $goalDetails->gb_relevant_goal_event: null }}'" placeholder="" class="form-control" required>{{isset($goalDetails)?$goalDetails->gb_relevant_goal_event:null}}</textarea>
                      </div>
                     </div>

                  </div>

                  <!-- /step-->

                  <div class="step data-step" data-step="11">

                       <div class="row">
                        <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>10.</span></div>
                        <label class="steps-head">What is the <b>due date</b> for this <b>EPIC</b> Goal? * </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                         <a href="javascript:void(0)" class="goal-step" 
                           data-message="A realistic <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> date must be considered, and the requirements and expectations need to be addressed. 
                           <br/><br/>
                           • how much can you lose each week sustainably? <br/>
                           • Are they willing to commit to the required tasks? <br/>
                           • Are they willing to make lifestyle changes in order to meet the goal?
                           <br/><br/>
                       When using the Date Selector - Be sure to select a realistic due date for your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> and do not set yourself up failure before beginning. You must consider your willingness to commit to your habit related tasks, the <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> may need to be adjusted/lowered if the date cannot be moved (special occasion date)"
                           data-message1="<b>Date Selector</b>- Be sure to select a realistic due date for your Goal and do not set yourself <br/> up failure before beginning. You must consider your willingness to commit to your habit <br/> related tasks. "><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>



                     <div class="form-group">

                        <input type="text" data-toggle="tooltip" title="" class="form-control vdp" name="due_date" id='datepicker_SYG' autocomplete="off" value="{{isset($goalDetails)? $goalDetails->goal_due_date:null}}"  required>

                     </div>

                  </div>

                  <!-- /step-->

                  <div class="step data-step goalDetail" data-step="12">
                   <div class="row">
                    <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                    <div class="heading-text border-head mb-10">
                      <div class="watermark1"><span>11.</span></div>
                      <label class="steps-head"> <b>Who can view</b> this <b>EPIC</b> Goal?  </label>
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
                      data-message1="<b>Select Friends</b>—Share details with a select few friends who you believe will be supporting and who will hold you accountable to your Goal.
                      <br/>
                      <b>Everyone</b>– share details and Goal with friend and family 
                      <br/>
                      <b>Just Me</b>—only show me details and Goal"><i class="fa fa-question-circle question-mark"></i></a>
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
                     @php
                         if(isset($goalDetails) && $goalDetails->gb_goal_seen == 'Selected friends'){
                           $visibility = '';
                         }else{
                           $visibility = 'hidden';
                         }
                     @endphp
                     <div class="form-group {{ $visibility }}">
                        <input type="text" class="form-control autocomplete" id="goal_selective_friends" ng-keypress="pressEnter($event)" value="{{ isset($goalDetails) && !empty($goalDetails->gb_goal_selective_friends) ? $goalDetails->gb_goal_selective_friends :'' }}" name="goal_selective_friends" aria-invalid="false">
                     </div>
                  </div>

                  <!-- /step-->

                  <div class="step data-step habitStep" data-step="13">
                     <div class="row">
                      <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>12.</span></div>
                        <label class="steps-head"> Send e-mail / SMS <b>reminders</b> </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                             <a href="javascript:void(0)" class="goal-step" 
                           data-message="<b>When Overdue</b>—If <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> has not been met
                           <br/><br/>
                           <b>Daily</b>—Send me messages related to <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> daily
                           <br/><br/>
                           <b>Weekly</b>—Send me messages related to <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> weekly
                           <br/><br/>
                           <b>Monthly</b>—Send me messages related to <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> monthly
                           <br/><br/>
                           <b>None</b>—Do not send me anything related to <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>
                           <br/><br/>
                           At <span style='color:#f64c1e;'><b>EPIC</b></span> we know that life can sometimes get the better of the best of us, reminders keep you accountable for your actions, time management, and overall results. 
                           <br/><br/>
                           It is important to come to the realisation that actions that may seem insignificant such as missing a training session, skipping a meal, or altering guidelines can have adverse accumulative effects on your overall progress, the earlier it is noticed by you by being given a reminder from the <span style='color:#f64c1e;'><b>EPIC</b> TEAM</span> the sooner you can get on top of it and get back on track.
                           <br/><br/>
                           Approach every <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> related task with the utmost importance as if others are relying on you, because YOU are relying on YOU!"
                           data-message1="<b>When Overdue</b>—If Goal has not been met<br/>
                           <b>Daily</b>—Send me messages related to Goal daily<br/>
                           <b>Weekly</b>—Send me messages related to Goal weekly<br/>
                           <b>Monthly</b>—Send me messages related to Goal monthly<br/>
                           <b>None</b>—Do not send me anything"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>

                     <div class="form-group">

                        <label class="container_radio version_2">When Overdue

                        <input type="radio" name="goal-Send-mail" value="when_overdue"{{isset($goalDetails) && $goalDetails->gb_reminder_type == "when_overdue" ? 'checked': ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">

                           Daily

                           <input type="radio" name="goal-Send-mail" value="daily" class="daily" {{isset($goalDetails) && $goalDetails->gb_reminder_type == "daily" ? 'checked': ''}}>

                           <span class="checkmark"></span>

                           <div class="showTimeBox" {{isset($goalDetails) && $goalDetails->gb_reminder_type == "daily" ? 'style=display:block': ''}}>

                              <select id="daily_time_goal">                                  

                                  <option value="1" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 1)) selected @endif>1:00 am</option>

                                  <option  value="2"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 2)) selected @endif>2:00 am</option>

                                  <option value="3"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 3)) selected @endif>3:00 am</option>

                                  <option value="4"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 4)) selected @endif>4:00 am</option>

                                  <option value="5"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 5)) selected @endif>5:00 am</option>

                                  <option value="6"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 6)) selected @endif>6:00 am</option>

                                  <option value="7"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 7)) selected @endif>7:00 am</option>

                                  <option value="9"  @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 9)) selected @endif>9:00 am</option>

                                  <option value="10" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 10)) selected @endif>10:00 am</option>

                                  <option value="11" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 11)) selected @endif>11:00 am</option>

                                  <option value="12" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 12)) selected @endif>12:00 PM</option>

                                  <option value="13" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 13)) selected @endif>1:00 PM</option>

                                  <option value="14" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 14)) selected @endif>2:00 PM</option>

                                  <option value="15" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 15)) selected @endif>3:00 PM</option>

                                  <option value="16" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 16)) selected @endif>4:00 PM</option>

                                  <option value="17" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 17)) selected @endif>5:00 PM</option>

                                  <option value="18" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 18)) selected @endif>6:00 PM</option>

                                  <option value="19" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 19)) selected @endif>7:00 PM</option>

                                  <option value="20" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 20)) selected @endif>8:00 PM</option>

                                  <option value="21" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 21)) selected @endif>9:00 PM</option>

                                  <option value="22" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 22)) selected @endif>10:00 PM</option>

                                  <option value="23" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 23)) selected @endif>11:00 PM</option>

                                  <option value="24" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == 24)) selected @endif>12:00 am</option>

                              </select>

                          </div>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">

                           Weekly

                           <input type="radio" name="goal-Send-mail" value="weekly" class="weekly" {{isset($goalDetails) && $goalDetails->gb_reminder_type == "weekly" ? 'checked': ''}}>

                           <span class="checkmark"></span>

                           <div class="showDayBox" {{isset($goalDetails) && $goalDetails->gb_reminder_type == "weekly" ? 'style=display:block': ''}}>

                              <select id="weekly_day_goal" >

                                  <option value="Mon" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Mon")) selected @endif>Mon</option>

                                  <option value="Tue" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Tue")) selected @endif>Tue</option>

                                  <option value="Wed" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Wed")) selected @endif>Wed</option>

                                  <option value="Thu" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Thu")) selected @endif>Thu</option>

                                  <option value="Fri" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Fri")) selected @endif>Fri</option>

                                  <option value="Sat" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Sat")) selected @endif>Sat</option>

                                  <option value="Sun" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "Sun")) selected @endif>Sun</option>

                              </select>

                          </div>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">

                           Monthly

                           <input type="radio" name="goal-Send-mail" value="monthly" class="monthly" {{isset($goalDetails) && $goalDetails->gb_reminder_type == "monthly" ? 'checked': ''}}>

                           <span class="checkmark"></span>

                           <div class="showMonthBox" {{isset($goalDetails) && $goalDetails->gb_reminder_type == "monthly" ? 'style=display:block': ''}}>

                              <select id="month_date_goal">

                                <option value="1" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "1")) selected @endif>1</option>

                                <option value="2" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "2")) selected @endif>2</option>

                                <option value="3" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "3")) selected @endif>3</option>

                                <option value="4" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "4")) selected @endif>4</option>

                                <option value="5" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "5")) selected @endif>5</option>

                                <option value="6" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "6")) selected @endif>6</option>

                                <option value="7" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "7")) selected @endif>7</option>

                                <option value="8" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "8")) selected @endif>8</option>

                                <option value="9" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "9")) selected @endif>9</option>

                                <option value="10" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "10")) selected @endif>10</option>

                                <option value="11" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "11")) selected @endif>11</option>

                                <option value="12" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "12")) selected @endif>12</option>

                                <option value="13" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "13")) selected @endif>13</option>

                                <option value="14" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "14")) selected @endif>14</option>

                                <option value="15" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "15")) selected @endif>15</option>

                                <option value="16" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "16")) selected @endif>16</option>

                                <option value="17" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "17")) selected @endif>17</option>

                                <option value="18" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "18")) selected @endif>18</option>

                                <option value="19" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "19")) selected @endif>19</option>

                                <option value="20" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "20")) selected @endif>20</option>

                                <option value="21" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "21")) selected @endif>21</option>

                                <option value="22" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "22")) selected @endif>22</option>

                                <option value="23" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "23")) selected @endif>23</option>

                                <option value="24" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "24")) selected @endif>24</option>

                                <option value="25" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "25")) selected @endif>25</option>

                                <option value="26" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "26")) selected @endif>26</option>

                                <option value="27" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "27")) selected @endif>27</option>

                                <option value="28" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "28")) selected @endif>28</option>

                                <option value="29" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "29")) selected @endif>29</option>

                                <option value="30" @if(isset($goalDetails) && ($goalDetails->gb_reminder_goal_time == "30")) selected @endif>30</option>

                              </select>

                          </div>

                        </label>

                     </div>
                     <label>Get Notifications Through ? </label>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email.
                        <input type="radio" name="goal-Send-epichq" value="email" {{isset($goalDetails) && $goalDetails->gb_reminder_type_epichq == "email" ? 'checked': ''}}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through chat.
                        <input type="radio" name="goal-Send-epichq" value="epichq" {{isset($goalDetails) && $goalDetails->gb_reminder_type_epichq == "epichq" ? 'checked': ''}}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email and chat both.
                        <input type="radio" name="goal-Send-epichq" value="email-epichq" {{isset($goalDetails) && $goalDetails->gb_reminder_type_epichq == "email-epichq" ? 'checked': ''}}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group send-reminders">

                        <label class="container_radio version_2">None

                        <input type="radio" name="goal-Send-epichq" value="none" {{isset($goalDetails) && $goalDetails->gb_reminder_type == "none" ? 'checked': ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                  </div>

                  <!-- /step-->

                  <div class="step data-step milestoneData" data-step="14" data-value="0" data-next="0">

                       <div class="row">
                        <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>13.</span></div>
                        <label class="steps-head"> What <b>milestones</b> do I need to accomplish before I reach my <b>EPIC</b> Goal? </label>
                      </div>
                      <div class="tooltip-sign mb-10">
                        <a href="javascript:void(0)" class="goal-step tooltip-diff" 
                           data-message="Milestones can be seen as mini <span style='color:#f64c1e;'><b>EPIC</b> Goals</span> which you set and need to achieve along the way. These are a set target and on a set date, to ensure you reach these milestones we recommend setting simple and achievable milestones. 
                           <br/><br/>
                           Milestones help breakdown your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> into bite sized chunks giving you a less daunting and more achievable end result. If milestones are not met as required to meet your individual goal timeline, your timeline or priority of tasks can be adjusted as required without effecting your positive outlook on the overall goal and your ability to achieve it.
                            <br/><br/>
                           Below are brief examples of milestones:
                            <br/><br/>
                               • Measurements cm or inches, dropping to a specific weight or loss of a specific weight in increments<br/>
                               • Body Fat Percentages, dropping to a specific BFP or loss of a specific BFP in increments<br/>
                               • Clothing sizes in inches or dress size, dropping to a specific size or loss of a specific size in increments"
                           data-message1="Milestones can be seen as mini <span style='color:#f64c1e'><b>EPIC</b> Goals</span> which you set and need to achieve along the way. These are a set target and on a set date, to ensure you reach these milestones we recommend setting simple and achievable milestones. 
                           <br/><br/>
                           Below are brief examples of milestones:
                           <br/><br/>
                           <b>Measurements</b> - cm or inches, dropping to a specific weight or loss of a specific weight in increments<br/>
                           <b>Body Fat Percentages</b> - dropping to a specific BFP or loss of a specific BFP in increments<br/>
                           <b>Clothing sizes</b> - in inches or dress size, dropping to a specific size or loss of a specific size in increments"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>

                     <div class="form-group">
                        <div class="row dd-item">
                           <div class="col-xs-5">
                              <div class="form-group">
                             <input type="text" class="form-control milestones-name" id="Milestones"  name="Milestones" >
                             </div>
                           </div>
                           <div class="col-xs-5">
                             <div class="form-group">
                             <input type="text" id="milestones-date-pickup" class="form-control milestones-date create-milestones-date" name ="milestones-date">
                           </div>
                           </div>
                         </div>
                        {{-- <input type="text" class="form-control" id="Milestones" value="" name="Milestones" > --}}
                        {{-- <input type="text" id="milestones-date-pickup" class="form-control milestones-date datepicker_SYG4" name ="milestones-date"> --}}
                     </div>

                      <div class="row">
                     <div class="form-group new_Btn_milestone col-xs-10">
                        <label class="btn btn-primary"> 
                        <span> Add Milestone</span>
                        </label>
                     </div>
                     </div>

                     <?php

                        $milestonesSeen='';

                        $milestonesReminder='';

                        $milestonesGoalId='';

                        ?>

                     <div class="dd mile_section row" >

                        <ul class="dd-list">

                           @if((isset($milestonesData))&&($milestonesData->count() > 0))

                           @foreach($milestonesData as $milestones)

                           <li class="dd-item row mb-10" data-milestones-id="{{$milestones->id}}" style="line-height: 20px; !important">

                              <div class="milestones-form">

                                 <div class="col-xs-5 milestones-date-cls">

                                    <input data-toggle="tooltip" title="Milestone Name" name="milestones" class="form-control milestones-name" value="{{$milestones->gb_milestones_name}}" data-milestones-id="{{$milestones->id}}" type="text" disabled="disabled" />

                                 </div>

                                 <div class="col-xs-5 milestones-date-cls date">

                                    <input data-toggle="tooltip" title="Milestone Due Date" class="form-control milestones-date datepicker_SYG4" autocomplete="off" name="milestones-date" required="" value="{{$milestones->gb_milestones_date}}" type="text" disabled="disabled" />

                                 </div>

                                 <div class="col-xs-2 p-0 pencil_find_sibling">

                                    <a><i class="fa fa-times new-delete-milestone-info" style="margin-right: 5px" data-milestones-id="{{$milestones->id}}"></i></a>

                                    <!-- <a><i class="fa fa-pencil edit-milestone-info hidden" style="display:inline; font-size: 16px"></i></a> -->

                                    <a><i class="fa fa-save save-milestone-info" data-milestones-id="{{$milestones->id}}" style="display:none"></i></a>

                                 </div>

                              </div>

                           </li>

                           <?php $milestonesSeen =isset($milestonesData)?$milestones->gb_milestones_seen:'';

                              $milestonesReminder =isset($milestonesData)?$milestones->gb_milestones_reminder:'';

                              $milestonesGoalId =isset($milestonesData)?$milestones->goal_id:'';

                              ?>

                           @endforeach

                           @endif

                        </ul>

                     </div>

                     <!--  <div class="form-group">

                        <div class="row">

                            <a><i class="fa fa-times delete-milestone-info" style="margin-right: 5px" data-milestones-id="{{$milestones->id}}"></i></a>

                            <a><i class="fa fa-pencil edit-milestone-info" style="display:inline; font-size: 16px"></i></a>

                            <a><i class="fa fa-save save-milestone-info" data-milestones-id="{{$milestones->id}}" style="display:none"></i></a>

                        </div>

                        

                        </div> -->

                  </div>

                  <!-- /step-->

                  <div class="step data-step" data-step="15">

                        <div class="row">
                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>14.</span></div>
                        <label class="steps-head"> <b>Who can view</b> this milestone? </label>
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
                           Having others view your milestones gives you the opportunity to be given feedback and positive encouragement throughout your journey. 
                           <br/><br/>
                           Allow others to celebrate your success with you!
                            <br/><br/>
                           Accountability is key, fellow <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> members can help hold you accountable when it comes to attending training sessions, staying on target with healthy balanced eating and completing your goal related tasks which relate to your milestones and progression.
                            <br/><br/>
                           <b>T.E.A.M</b> Together Everyone Achieves More"
                           data-message1="<b>Select Friends</b>—Share details with a select few friends who you believe will be supporting <br/> and who will hold you accountable to your Goal.
                           <br/>
                           <b>Everyone</b>– share details and Goal with friend and family <br/>
                           <b>Just Me</b>—only show me details and Goal"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>

                     <div class="form-group">

                        <label class="container_radio version_2">Select Friends

                        <input type="radio" name="gb_milestones_seen" value="Selected friends" {{ isset($milestonesData) && $milestones->gb_milestones_seen == 'Selected friends'?'checked':'' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>
                     <div class="form-group">

                        <label class="container_radio version_2">Everyone

                        <input type="radio" name="gb_milestones_seen" value="everyone" {{ isset($milestonesData) && $milestones->gb_milestones_seen == 'everyone'?'checked':'' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">Just me

                           <input type="radio" name="gb_milestones_seen" value="Just_Me"  {{ isset($milestonesData) && $milestones->gb_milestones_seen == 'Just_Me'?'checked':'' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>
                     @php
                         if(isset($milestonesData) && $milestones->gb_milestones_seen == 'Selected friends'){
                           $visibility = '';
                         }else{
                           $visibility = 'hidden';
                         }
                     @endphp
                     <div class="form-group {{ $visibility }}">
                        <input type="text" class="form-control autocomplete" id="gb_milestones_selective_friends" ng-keypress="pressEnter($event)" value="{{ isset($milestonesData) && !empty($milestones->gb_milestones_selective_friends) ? $milestones->gb_milestones_selective_friends :'' }}" name="gb_milestones_selective_friends" aria-invalid="false">
                     </div>
                  </div>

                  <!-- /step-->

                  <div class="step data-step milestoneEmail habitStep" data-step="16">
                   <div class="row">
                    <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                    <div class="heading-text border-head mb-10">
                      <div class="watermark1"><span>15.</span></div>
                      <label class="steps-head"> Send e-mail / SMS <b>reminders</b> </label>
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
                      data-message1="<b>When Overdue</b>—If Goal has not been met<br/>
                      <b>Daily</b>—Send me messages related to Goal daily<br/>
                      <b>Weekly</b>—Send me messages related to Goal weekly<br/>
                      <b>Monthly</b>—Send me messages related to Goal monthly<br/>
                      <b>None</b>—Do not send me anything"><i class="fa fa-question-circle question-mark"></i></a>
                    </div>

                  </div>

                     <div class="form-group">

                        <label class="container_radio version_2">When Overdue

                        <input type="radio" name="milestones-Send-mail" value="when_overdue"{{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder == "when_overdue" ? 'checked': ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">

                           Daily

                           <input type="radio" name="milestones-Send-mail" value="daily" class="daily" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder == "daily" ? 'checked': ''}}>

                           <span class="checkmark"></span>

                           <div class="showTimeBox" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder == "daily" ? 'style=display:block': ''}}>

                              <select id="daily_time_milestones">                                  

                                  <option value="1" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 1)) selected @endif>1:00 am</option>

                                  <option  value="2"  @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 2)) selected @endif>2:00 am</option>

                                  <option value="3"  @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 3)) selected @endif>3:00 am</option>

                                  <option value="4"  @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 4)) selected @endif>4:00 am</option>

                                  <option value="5"  @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 5)) selected @endif>5:00 am</option>

                                  <option value="6"  @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 6)) selected @endif>6:00 am</option>

                                  <option value="7"  @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 7)) selected @endif>7:00 am</option>

                                  <option value="9"  @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 9)) selected @endif>9:00 am</option>

                                  <option value="10" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 10)) selected @endif>10:00 am</option>

                                  <option value="11" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 11)) selected @endif>11:00 am</option>

                                  <option value="12" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 12)) selected @endif>12:00 PM</option>

                                  <option value="13" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 13)) selected @endif>1:00 PM</option>

                                  <option value="14" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 14)) selected @endif>2:00 PM</option>

                                  <option value="15" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 15)) selected @endif>3:00 PM</option>

                                  <option value="16" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 16)) selected @endif>4:00 PM</option>

                                  <option value="17" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 17)) selected @endif>5:00 PM</option>

                                  <option value="18" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 18)) selected @endif>6:00 PM</option>

                                  <option value="19" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 19)) selected @endif>7:00 PM</option>

                                  <option value="20" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 20)) selected @endif>8:00 PM</option>

                                  <option value="21" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 21)) selected @endif>9:00 PM</option>

                                  <option value="22" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 22)) selected @endif>10:00 PM</option>

                                  <option value="23" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 23)) selected @endif>11:00 PM</option>

                                  <option value="24" @if(isset($milestonesData) && ($milestonesData[0]->gb_milestones_reminder_time == 24)) selected @endif>12:00 am</option>

                              </select>

                          </div>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">

                           Weekly

                           <input type="radio" name="milestones-Send-mail" value="weekly" class="weekly" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder == "weekly" ? 'checked': ''}}>

                           <span class="checkmark"></span>

                           <div class="showDayBox" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder == "weekly" ? 'style=display:block': ''}}>

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

                           <input type="radio" name="milestones-Send-mail" value="monthly" class="monthly" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder == "monthly" ? 'checked': ''}}>

                           <span class="checkmark"></span>

                           <div class="showMonthBox" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder == "monthly" ? 'style=display:block': ''}}>

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
                        <input type="radio" name="milestones-Send-epichq" value="email" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder_epichq == "email" ? 'checked': ''}}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through chat.
                        <input type="radio" name="milestones-Send-epichq" value="epichq" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder_epichq == "epichq" ? 'checked': ''}}>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email and chat both.
                        <input type="radio" name="milestones-Send-epichq" value="email-epichq" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder_epichq == "email-epichq" ? 'checked': ''}}>
                        <span class="checkmark"></span>
                        </label>
                     </div>

                     <div class="form-group send-reminders">

                        <label class="container_radio version_2">None

                        <input type="radio" name="milestones-Send-epichq" value="none" {{isset($milestonesData) && $milestonesData[0]->gb_milestones_reminder == "none" ? 'checked': ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                  </div>

                  <!-- /step-->

                  {{-- <div class="step  data-step" data-step="17">

                     

                  </div> --}}

                

                  <div class="step data-step newHabitForm" id="newHabitForm" data-step="20" data-habit="" data-habit-value="" data-value='0' data-next="0">

                        <div class="row">
                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>16.</span></div>
                        <label class="steps-head">Name Your <b>Habit</b> ? *</label>
                      </div>
                      <div class="tooltip-sign mb-10">
                        <a href="javascript:void(0)" class="goal-step" 
                           data-message="Examples of habits to include in most <span style='color:#f64c1e;'><b>EPIC</b> Goals</span> would be:
                           <br/><br/>
                           <b>Physical Activity</b> – This would relate to resistance training, cardiovascular endurance and stretching and recovery routines.
                           <br/><br/>
                           <b>Healthy Balanced Nutrition</b> – This would include basics such as knowing calories and portion distortion, managing food preparation, and planning and limiting vices and tracing and replacing things in your current diet with healthier options.
                           <br/><br/>
                           <b>Stress Management</b> – This would address things such as limiting blue light and focussing on sleep, working on time management"
                           data-message1="Examples of habits to include in most <span style='color:#f64c1e;'><b>EPIC</b> Goals</span> would be:
                           <br/><br/>
                           <b>Physical Activity</b> – This would relate to resistance training, cardiovascular endurance and stretching and recovery routines.
                           <br/><br/>
                           <b>Healthy Balanced Nutrition</b> – This would include basics such as knowing calories and portion distortion, managing food preparation, and planning and limiting vices and tracing and replacing things in your current diet with healthier options.
                           <br/><br/>
                           <b>Stress Management</b> – This would address things such as limiting blue light and focussing on sleep, working on time management"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>



                     <div class="form-group">

                        <input onblur="validateGoalHabit()"  data-toggle="tooltip" title="" type="text" class="form-control" id="SYG_habits" value="{{isset($habitDetails)?$habitDetails->gb_habit_name:null}}" name="SYG_habits">

                     </div>



                       <!-- /step-->

                        <div class="habitStep">

                                 <label> Habit <b>Recurrence</b><a href="javascript:void(0)" class="goal-step" 
                                    data-message="<b>Daily</b>—This is if you are implementing training into your daily routine with no recovery days
                                    <br/><br/>
                                    <b>Weekly</b>—If you have one or more physical activity days in a week
                                    <br/><br/>
                                    <b>Monthly</b>— If it related to a specific day each month maybe testing
                                    <br/><br/>
                                    Habits are critical to any Lifestyle Design Change and always need to be addressed fully to ensure that all aspects of the habits are understood."
                                    data-message1="<b>Daily</b>—This is if you are implementing training into your daily routine with no recovery days
                                    <br/><br/>
                                    <b>Weekly</b>—If you have one or more physical activity days in a week
                                    <br/><br/>
                                    <b>Monthly</b>— If it related to a specific day each month maybe testing
                                    <br/><br/>
                                    Habits are critical to any Lifestyle Design Change and always need to be addressed fully to ensure that all aspects of the habits are understood."><i class="fa fa-question-circle question-mark"></i></a>
                                 </label>

                              <div class="form-group">

                                 <label class="container_radio version_2">

                                 Daily

                                 <input type="radio" name="SYG_habit_recurrence" value="daily" {{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'daily'?'checked':''}} >

                                 <span class="checkmark"></span>

                                 </label>

                              </div>

                              <div class="form-group">

                                 <label class="container_radio version_2">

                                 Weekly

                                 <input type="radio" name="SYG_habit_recurrence" value="weekly"{{ isset($habitDetails) && $habitDetails->gb_habit_recurrence_type == 'weekly'?'checked':''}}  class="weekly">

                                 <span class="checkmark"></span>

                                 </label>

                              </div>

                              <div class="showDayBox row showbox" @if((isset($habitDetails))&&($habitDetails->gb_habit_recurrence_type=='weekly')) style="display: block;" @else style="display: none;"   @endif>

                                 <div class="form-group col-xs-4 col-sm-3">

                                    <label class="container_check version_2">Mon

                                    {{-- <input type="checkbox" name="week" value="" class="required"> --}}

                                    <input name="habitRecWeek" id="goalEventRepeatWeekdays0" class="goalEventRepeatWeekdays " value="Monday" type="checkbox"  @if(isset($habitDetails) && (in_array('Monday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >

                                    <span class="checkmark"></span>

                                    </label>

                                 </div>

                                 <div class="form-group col-xs-4 col-sm-3">

                                    <label class="container_check version_2">Tue

                                       <input name="habitRecWeek" id="goalEventRepeatWeekdays1" class="goalEventRepeatWeekdays" value="Tuesday" type="checkbox"  @if(isset($habitDetails) && (in_array('Tuesday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >

                                    <span class="checkmark"></span>

                                    </label>

                                 </div>

                                 <div class="form-group col-xs-4 col-sm-3">

                                    <label class="container_check version_2">Wed

                                       <input name="habitRecWeek" id="goalEventRepeatWeekdays2" class="goalEventRepeatWeekdays" value="Wednesday" type="checkbox"  @if(isset($habitDetails) && (in_array('Wednesday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >

                                    <span class="checkmark"></span>

                                    </label>

                                 </div>

                                 <div class="form-group col-xs-4 col-sm-3">

                                    <label class="container_check version_2">Thu

                                       <input name="habitRecWeek" id="goalEventRepeatWeekdays3" class="goalEventRepeatWeekdays hidden" value="Thursday" type="checkbox"  @if(isset($habitDetails) && (in_array('Thursday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >

                                    <span class="checkmark"></span>

                                    </label>

                                 </div>

                                 <div class="form-group col-xs-4 col-sm-3">

                                    <label class="container_check version_2">Fri

                                       <input name="habitRecWeek" id="goalEventRepeatWeekdays4" class="goalEventRepeatWeekdays hidden" value="Friday" type="checkbox"  @if(isset($habitDetails) && (in_array('Friday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >

                                    <span class="checkmark"></span>

                                    </label>

                                 </div>

                                 <div class="form-group col-xs-4 col-sm-3">

                                    <label class="container_check version_2">Sat

                                   

                                 <input name="habitRecWeek" id="goalEventRepeatWeekdays5" class="goalEventRepeatWeekdays" value="Saturday" type="checkbox"  @if(isset($habitDetails) && (in_array('Saturday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >

                                    <span class="checkmark"></span>

                                    </label>

                                 </div>

                                 <div class="form-group col-xs-4 col-sm-3">

                                    <label class="container_check version_2">Sun

                                       <input name="habitRecWeek" id="goalEventRepeatWeekdays6" class="goalEventRepeatWeekdays" value="Sunday" type="checkbox"  @if(isset($habitDetails) && (in_array('Sunday',$habitDetails->gbHabitWeekDetails))) checked {{-- @elseif(! isset($habitDetails)) checked --}} @endif >

                                    <span class="checkmark"></span>

                                    </label>

                                 </div>

                              </div>

                              <!-- <div class="form-group hbt_rec_monthly">

                                 <label class="container_radio version_2">

                                    Monthly

                                    <input type="radio" name="SYG_habit_recurrence" value="monthly" class="monthly">

                                    <span class="checkmark"></span>

                                    <div class="showMonthBox month">

                                       <div style="display:flex;align-items: center">

                                          Day&nbsp;&nbsp;
                                          
                                          <select class="month-date" style="max-width: 40px">

                                            @for($i = 1; $i <= calDaysInMonth(); $i++)

                                                <option value ="{{ $i }}">{{ $i }}</option>

                                            @endfor

                                          </select>

                                          &nbsp;&nbsp;

                                          of every month

                                       </div>

                                    </div>

                                 </label>

                              </div>
 -->
                        </div>



                        <label> Why is this habit <b>important</b> to me? 
                           {{-- <a href="#" data-toggle="modal" data-target="#important-habit"><i class="fa fa-question-circle question-mark"></i></a> --}}
                           <a href="javascript:void(0)" class="goal-step" 
                           data-message="By creating this habit what aspect of your life will change?
                           <br/><br/>
                           Resistance training to improve functional strength.
                           <br/><br/>
                           Cardiovascular endurance to ensure we can do more for longer.
                           <br/><br/>
                           Recovery routine incorporating stretching and rolling."
                           data-message1="By creating this habit what aspect of your life will change?"><i class="fa fa-question-circle question-mark"></i></a>
                        </label>


                     <div class="form-group habit_notes outborder">



                        <textarea data-toggle="tooltip" title="" data-autoresize rows="3" id="SYG_notes" name="SYG_notes" ng-model="SYG_notes" ng-init="SYG_notes='{{ isset($habitDetails) ? $habitDetails->gb_habit_notes : null}}'" placeholder="" class="form-control">{{isset($habitDetails)?$habitDetails->gb_habit_notes:null}}</textarea>

                     </div>

                      <div>

                     <div class="">

                        <label data-toggle="tooltip" title=""> Is this habit <b>associated</b> with a milestone of this <b>EPIC</b> goal? 
                           {{-- <a href="#" data-toggle="modal" data-target="#milestone"><i class="fa fa-question-circle question-mark"></i></a> --}}
                           <a href="javascript:void(0)" class="goal-step" 
                           data-message="Does successfully creating this habit contribute to hitting your milestones and achieving your overall goal?
                           <br/><br/>
                           Yes, select individual or yes select all."
                           data-message1="Does successfully creating this habit contribute to hitting your milestones and achieving your overall goal?
                           <br/><br/>
                           Yes, select individual or yes select all."><i class="fa fa-question-circle question-mark"></i></a>
                        </label>

                     </div>

                     {{-- <input type="checkbox" id="gb_habit_select_all_milestone" name="gb_habit_select_all_milestone"/><label for="gb_habit_select_all_milestone">Select All</label> --}}

                     <div class="row">

                       <div class="col-sm-12 col-xs-12">

                         <?php

                         $m_gb_milestone_value = [];

                         if(isset($milestonesData)) {

                           foreach ($milestonesData as $milestones) {

                             if(isset($habitDetails) && $habitDetails->gb_milestones_id && in_array($milestones->id, explode(',', $habitDetails->gb_milestones_id))) {

                               $m_gb_milestone_value[] = $milestones->id;

                             }

                           }

                         }

                         ?>

                         <input type="hidden" name="previusSelectedMilesone" class="previusSelectedMilesone" value="{{ !empty($m_gb_milestone_value) ? json_encode($m_gb_milestone_value) : ''}}">



                         <div class="form-group pli-23">

                           <div class="milestone-dropdown">

                             <select data-toggle="tooltip" title="" id="milestone_div" name="milestone_value" ng-init="milestone_value='{{ json_encode($m_gb_milestone_value) }}'" ng-model="milestone_value" ng-keypress="pressEnter($event)" class="selectpicker form-control onchange-set-neutral goal-change-life" data-live-search="true" data-selected-text-format="count > 2" multiple="multiple" data-actions-box="true">

                               @if((isset($milestoneOption))&&(count($milestoneOption) > 0))

                               @foreach($milestoneOption as $key=>$milestones)

                               @if(isset($habitDetails) && $habitDetails->gb_milestones_id && in_array($key, explode(',', $habitDetails->gb_milestones_id)))

                               <option value="{{$key}}" selected="">{{$milestones}}</option>

                               @else

                               <option value="{{$key}}">{{$milestones}}</option>

                               @endif

                               @endforeach

                               @endif

                             </select>

                           </div>

                         </div>

                       </div>

                      

                     </div>

                     </div>



                     <div class="">

                         <label data-toggle="tooltip" title=""> Who can view your habit? 
                            {{-- <a href="#" data-toggle="modal" data-target="#see-habit"><i class="fa fa-question-circle question-mark"></i></a> --}}
                            <a href="javascript:void(0)" class="goal-step" 
                           data-message="<b>Select Friends</b>—Share details with a select few friends who you believe will be supportive and who will hold you accountable to your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>.
                           <br/><br/>
                           <b>Everyone</b>– Share details of your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> with all friend and family on your Timeline 
                           <br/><br/>
                           <b>Just Me</b>—Only show me details of my <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>
                           <br/><br/>
                           The <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span>  online platform is designed to allow you to connect and interact with fellow team members and like-minded individuals on the same or similar journey to your own. 
                           <br/><br/>
                           Having others view your milestones gives you the opportunity to be given feedback and positive encouragement throughout your journey. 
                           <br/><br/>
                           Allow others to celebrate your success with you!
                            <br/><br/>
                           Accountability is key, fellow <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> members can help hold you accountable when it comes to attending training sessions, staying on target with healthy balanced eating and completing your goal related tasks which relate to your milestones and progression.
                            <br/><br/>
                           <b>T.E.A.M</b> Together Everyone Achieves More"
                           data-message1="<b>Select Friends</b>—Share details with a select few friends who you believe will be supportive and who will hold you accountable to your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>.
                           <br/><br/>
                           <b>Everyone</b>– Share details of your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> with all friend and family on your Timeline 
                           <br/><br/>
                           <b>Just Me</b>—Only show me details of my <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>
                           <br/><br/>
                           The <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> online platform is designed to allow you to connect and interact with fellow team members and like-minded individuals on the same or similar journey to your own. 
                           <br/><br/>
                           Having others view your habits gives you the opportunity to be given feedback and positive encouragement throughout your journey. 
                           <br/><br/>
                           Allow others to celebrate your success with you!
                             <br/><br/>
                           Accountability is key, fellow <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> members can help hold you accountable when it comes to attending training sessions, staying on target with healthy balanced eating and completing your goal related tasks which relate to your milestones and progression.
                             <br/><br/>
                           <b>T.E.A.M</b> Together Everyone Achieves More"><i class="fa fa-question-circle question-mark"></i></a>
                           </label>



                     </div>
                     <div class="form-group">

                        <label class="container_radio version_2">Select Friends

                           <input type="radio" name="syg2_see_habit" id="syg2_see_habit" value="Selected friends"  {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'Selected friends'?'checked':'' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>
                     <div class="form-group">

                        <label class="container_radio version_2">Everyone

                           <input type="radio" name="syg2_see_habit" id="syg2_see_habit0" value="everyone"  {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'everyone'?'checked':'' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">Just Me

                           <input type="radio" name="syg2_see_habit" id="syg2_see_habit2" value="Just Me" {{ isset($habitDetails) && $habitDetails->gb_habit_seen == 'Just Me'?'checked':'' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>
                     {{-- @php
                        if(isset($habitData) && $habitData[0]->gb_habit_seen == 'Selected friends'){
                        $visibility = '';
                        }else{
                        $visibility = 'hidden';
                        }
                     @endphp --}}
                     <div class="form-group hidden">
                        <input type="text" class="form-control autocomplete" id="syg2_selective_friends" ng-keypress="pressEnter($event)"  name="syg2_selective_friends" aria-invalid="false">
                     </div>
                     <div class="habitStep">

                      <div class="">

                        <label>Send e-mail / SMS 
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
                           It is important to come to the realisation that actions that may seem insignificant such as missing a training session, skipping a meal, or altering guidelines can have adverse accumulative effects on your overall progress, the earlier it is noticed by you by being given a reminder from the <span style='color:#f64c1e;'><b>EPIC</b> TEAM</span>  the sooner you can get on top of it and get back on track.
                           <br/><br/>
                           Approach every milestone related task with the utmost importance as if others are relying on you, because YOU are relying on YOU!"
                           data-message1="<b>When Overdue</b>—If tasks have not been met
                           <br/><br/>
                           <b>Daily</b>—Send me messages related to tasks daily
                           <br/><br/>
                           <b>Weekly</b>—Send me messages related to tasks weekly
                           <br/><br/>
                           <b>Monthly</b>—Send me messages related to tasks monthly
                           <br/><br/>
                           <b>None</b>—Do not send me anything related to tasks
                           <br/><br/>
                           At <span style='color:#f64c1e;'><b>EPIC</b></span> we know that life can sometimes get the better of the best of us, reminders keep you accountable for your actions, time management, and overall results. 
                           <br/><br/>
                           It is important to come to the realisation that actions that may seem insignificant such as missing a training session, skipping a meal, or altering guidelines can have adverse accumulative effects on your overall progress, the earlier it is noticed by you by being given a reminder from the <span style='color:#f64c1e;'><b>EPIC</b> TEAM</span> the sooner you can get on top of it and get back on track.
                           <br/><br/>
                           Approach every habit related to milestones with the utmost importance as if others are relying on you, because YOU are relying on YOU!"><i class="fa fa-question-circle question-mark"></i></a>
                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">When Overdue

                        <input type="radio" id="habits_send_Overdue" name="habits-send-mail" class="form-control mb" value="when_overdue" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "when_overdue")) checked @endif>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">

                           Daily

                           <input type="radio" id="habits_send_Daily" name="habits-send-mail" class="daily" value="daily" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "daily")) checked @endif>

                           <span class="checkmark"></span>

                           <div class="showTimeBox">

                              <select id="daily_time_habits">                                  

                                <option value="1" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 1)) selected @endif>1:00 am</option>

                                <option  value="2"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 2)) selected @endif>2:00 am</option>

                                <option value="3"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 3)) selected @endif>3:00 am</option>

                                <option value="4"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 4)) selected @endif>4:00 am</option>

                                <option value="5"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 5)) selected @endif>5:00 am</option>

                                <option value="6"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 6)) selected @endif>6:00 am</option>

                                <option value="7"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 7)) selected @endif>7:00 am</option>

                                <option value="9"  @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 9)) selected @endif>9:00 am</option>

                                <option value="10" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 10)) selected @endif>10:00 am</option>

                                <option value="11" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 11)) selected @endif>11:00 am</option>

                                <option value="12" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 12)) selected @endif>12:00 PM</option>

                                <option value="13" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 13)) selected @endif>1:00 PM</option>

                                <option value="14" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 14)) selected @endif>2:00 PM</option>

                                <option value="15" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 15)) selected @endif>3:00 PM</option>

                                <option value="16" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 16)) selected @endif>4:00 PM</option>

                                <option value="17" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 17)) selected @endif>5:00 PM</option>

                                <option value="18" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 18)) selected @endif>6:00 PM</option>

                                <option value="19" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 19)) selected @endif>7:00 PM</option>

                                <option value="20" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 20)) selected @endif>8:00 PM</option>

                                <option value="21" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 21)) selected @endif>9:00 PM</option>

                                <option value="22" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 22)) selected @endif>10:00 PM</option>

                                <option value="23" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 23)) selected @endif>11:00 PM</option>

                                <option value="24" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == 24)) selected @endif>12:00 am</option>

                            </select>

                          </div>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">

                           Weekly

                           <input type="radio" id="habits_send_Weekly" name="habits-send-mail" class="weekly"  value="weekly" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "weekly")) checked @endif>

                           <span class="checkmark"></span>

                           <div class="showDayBox">

                              <select id="weekly_day_habits">

                                  <option value="Mon" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Mon")) selected @endif>Mon</option>

                                  <option value="Tue" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Tue")) selected @endif>Tue</option>

                                  <option value="Wed" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Wed")) selected @endif>Wed</option>

                                  <option value="Thu" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Thu")) selected @endif>Thu</option>

                                  <option value="Fri" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Fri")) selected @endif>Fri</option>

                                  <option value="Sat" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Sat")) selected @endif>Sat</option>

                                  <option value="Sun" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "Sun")) selected @endif>Sun</option>

                              </select>

                          </div>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">

                           Monthly

                           <input type="radio" name="habits-send-mail" value="monthly" class="monthly">

                           <span class="checkmark"></span>

                           <div class="showMonthBox">

                              <select id="month_date_habits">

                                <option value="1" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "1")) selected @endif>1</option>

                                <option value="2" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "2")) selected @endif>2</option>

                                <option value="3" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "3")) selected @endif>3</option>

                                <option value="4" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "4")) selected @endif>4</option>

                                <option value="5" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "5")) selected @endif>5</option>

                                <option value="6" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "6")) selected @endif>6</option>

                                <option value="7" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "7")) selected @endif>7</option>

                                <option value="8" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "8")) selected @endif>8</option>

                                <option value="9" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "9")) selected @endif>9</option>

                                <option value="10" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "10")) selected @endif>10</option>

                                <option value="11" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "11")) selected @endif>11</option>

                                <option value="12" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "12")) selected @endif>12</option>

                                <option value="13" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "13")) selected @endif>13</option>

                                <option value="14" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "14")) selected @endif>14</option>

                                <option value="15" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "15")) selected @endif>15</option>

                                <option value="16" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "16")) selected @endif>16</option>

                                <option value="17" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "17")) selected @endif>17</option>

                                <option value="18" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "18")) selected @endif>18</option>

                                <option value="19" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "19")) selected @endif>19</option>

                                <option value="20" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "20")) selected @endif>20</option>

                                <option value="21" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "21")) selected @endif>21</option>

                                <option value="22" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "22")) selected @endif>22</option>

                                <option value="23" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "23")) selected @endif>23</option>

                                <option value="24" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "24")) selected @endif>24</option>

                                <option value="25" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "25")) selected @endif>25</option>

                                <option value="26" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "26")) selected @endif>26</option>

                                <option value="27" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "27")) selected @endif>27</option>

                                <option value="28" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "28")) selected @endif>28</option>

                                <option value="29" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "29")) selected @endif>29</option>

                                <option value="30" @if(isset($habitDetails) && ($habitDetails->gb_reminder_habits_time == "30")) selected @endif>30</option>

                              </select>

                          </div>

                        </label>

                     </div>

                      <label>Get Notifications Through ? </label>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email.
                        <input type="radio" name="habits-send-epichq" value="email" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder_epichq == "email")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through chat.
                        <input type="radio" name="habits-send-epichq" value="epichq" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder_epichq == "epichq")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email and chat both.
                        <input type="radio" name="habits-send-epichq" value="email-epichq" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder_epichq == "email-epichq")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>

                     <div class="form-group send-reminders">

                        <label class="container_radio version_2">None

                           <input type="radio" id="habits_send_None" name="habits-send-epichq" class="form-control mb" value="none" @if(isset($habitDetails) && ($habitDetails->gb_habit_reminder == "none")) checked @endif>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     </div>

                  </div>

                 

                  {{-- <div class="step data-step newHabitForm" data-step="19" data-habit="">

                    

                  </div>

                  <!-- /step-->

                  <div class="step">

                    

                  </div>

                  <!-- /step--> --}}

                  <div class="step data-step newTask" data-step="21" data-value="0" data-next="0">

                        <div class="row">
                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>17.</span></div>
                        <label class="steps-head">What Habits Do I need to Develop to Accomplish This Goal?</label>
                        {{-- <label class="steps-head">What Tasks Do I need to Develop to Accomplish This Goal?</label> --}}
                      </div>
                      <div class="tooltip-sign mb-10">
                       <a href="javascript:void(0)" class="goal-step" 
                           data-message="Tooltip not provided on the documents"
                           data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a>
                      </div>

                    </div>

                     <div class="form-group">

                        {{-- <label class="">  --}}

                        {{-- <span> Establish New Habit</span> --}}

                        {{-- <a class ="btn btn-primary pull-right add-habit">Establish New Habit</a> --}}
                        <a class ="btn btn-primary add-habit">Establish New Habit</a> 
                        <div class="tooltip-sign mb-10 tooltip_btn">
                           <a href="javascript:void(0)" class="goal-step" 
                               data-message="Please provide tooltip content"
                               data-message1="Please provide tooltip content"><i class="fa fa-question-circle question-mark"></i></a>
                          </div>
                        

                        {{-- </label> --}}

                     </div>

                     <div class="form-group">

                        <div class="table-responsive">

                             <table class="table table-striped table-bordered table-hover" id="client-datatable">

                              <thead>

                                 <tr>

                                    <th class="">Habit Name</th>

                                    <th>Frequency</th>

                                    <th class="hidden-xs">Milestone</th>

                                    <th class="hidden-xs">Shared</th>

                                    <th class="center">Actions</th>

                                 </tr>

                              </thead>

                              <tbody id="habitlist">

                                @foreach($habitData as $habits)



                                <?php

    

                                /*$allmileNameArr=[];

                                  $allMileNameData='';

    

                                  if(isset($habits->habitmilestone)){

                                      $allmileNameArr[]=$habits->habitmilestone->pluck('gb_milestones_name')->toArray();

                                        if(isset($allmileNameArr[0]))

                                             $allMileNameData=implode(", ",$allmileNameArr[0]);

    

    

    

                                  }*/

    

                                ?>

    

                                <tr>

                                    <td>{{isset($habits->gb_habit_name)?$habits->gb_habit_name:null}}</td>

                                    <td><?php if($habits->gb_habit_recurrence_type == 'weekly') echo 'Every '.$habits->gb_habit_recurrence_week; elseif($habits->gb_habit_recurrence_type == 'monthly') echo 'Day '.$habits->gb_habit_recurrence_month .' of every month';

                                        else echo ucfirst($habits->gb_habit_recurrence_type) ;  ?></td>

    

                                    <td>{{implode(', ', $habits->getMilestoneNames())}}</td>

                                    <td>{{isset($habits->gb_habit_seen)?$habits->gb_habit_seen:null}}</td>

                                    <td class="center">

    

                                        <a class="btn btn-xs btn-default tooltips habit-edit" data-placement="top" data-original-title="Edit" data-habit-id = "{{$habits->id}}">

                                            <i class="fa fa-pencil" style="color:#ff4401;"></i>

                                        </a>

    

                                        <a class="btn btn-xs btn-default tooltips delete-habit" data-entity="habit" data-placement="top" data-original-title="Delete" data-habit-id = "{{$habits->id}}">

                                            <i class="fa fa-times" style="color:#ff4401;"></i></a>

    

                                    </td>

                                </tr>

                            @endforeach

                              </tbody>

                           </table>

                              {{-- <tbody>

                                 <tr>

                                    <td>Physical Activity</td>

                                    <td></td>

                                    <td></td>

                                    <td></td>

                                    <td class="center">

                                       <a class="btn btn-xs btn-default tooltips habit-edit" data-placement="top" data-original-title="Edit" data-habit-id = "{{$habits->id}}">

                                          <i class="fa fa-pencil" style="color:#ff4401;"></i>

                                      </a>

                                       <a class="btn btn-xs btn-default tooltips delete-habit" data-entity="habit" data-placement="top" data-original-title="Delete" data-habit-id="1059">

                                       <i class="fa fa-times" style="color:#ff4401;"></i></a>

                                    </td>

                                 </tr>

                              </tbody> --}}

                           {{-- </table> --}}

                        </div>

                        {{-- <div class="row habit-form" id="habit_form">

                           <h6 class="step-heading"><em>What Habits Do I need to Develop to Accomplish This Goal?</em></h6>



                           @include('Result.goal-buddy.createhabits')



                       </div> --}}

                     </div>

                  </div>

                 

                  <!-- /step-->

                  <div class="step data-step taskNext newTaskData" data-step="22" data-next="0" data-value="0">

                     {{-- <div class="form-group"> --}}

                        <div class="row">
                          <span class="qodef-grid-line-center heading-border"><span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
                      <div class="heading-text border-head mb-10">
                        <div class="watermark1"><span>18.</span></div>
                        <label class="steps-head">Is this task associated with a habit of this <b>EPIC</b> Goal? *</label>
                      </div>
                      <div class="tooltip-sign mb-10">
                         <a href="javascript:void(0)" class="goal-step" 
                           data-message="In certain cases, a certain habit may have multiple task that fall under it, an example of this would be physical activity habit may include resistance training, cardiovascular endurance and stretching and recovery routines."
                           data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark" style="color: #f94211;"></i></a>
                      </div>

                    </div>



                     {{-- </div> --}}

                     <div class="form-group">

                        <div class="input-body mb ml-0">

                           <div class="row">

                             <div class="col-xs-12 col-sm-12">

                               <?php

                               $m_gb_habit_value = [];

                               if(isset($habitData)) {

                                 foreach ($habitData as $habitval) {

                                   if(isset($taskDetails) && $habitval->id==$taskDetails->gb_habit_id) {

                                     $m_gb_habit_value[] = $habitval->id;

                                   }

                                 }

                               }

                               ?>

                               <input type="hidden" name="associatedHabitWithTask" value="">

                               <input type="hidden" name="goalTaskData" value="">

                               

                               <div class="task-habit-dropdown pli-23 dropdown-menu-ml-0">
                                <input data-toggle="tooltip" title="" type="text" class="form-control taskhabit_div_class" id="habit_div" ng-model=""  ng-init="SYG3_task=''" ng-keypress="pressEnter($event)" value="" name="habit_value" disabled>

                                 <!-- @if((isset($habitData))&&(count($habitData) > 0))

                                 <select onchange="validateGoalTask()" data-toggle="tooltip" title="" id="habit_div" name="habit_value" class="form-control onchange-set-neutral taskhabit_div_class" ng-init="habit_value={{ json_encode($m_gb_habit_value) }}" ng-model="habit_value" ng-keypress="pressEnter($event)" required>

                                   <option value="">-- Select --</option>

                                   @foreach($habitData as $habitval)

                                   @if(isset($taskDetails) &&  $habitval->id==$taskDetails->gb_habit_id)

                                   <option value="{{$habitval->id}}" selected="">{{$habitval->gb_habit_name}}</option>

                                   @else

                                   <option value="{{$habitval->id}}">{{$habitval->gb_habit_name}}</option>

                                   @endif

                                   @endforeach

                                 </select>

                                 @endif -->


                               </div> <!-- end task habit dropdown -->

                               

                             </div>

                           </div>

                           

                           

                         </div>

                     </div>



                         <label> Name Your Task *
                            {{-- <a href="#" data-toggle="modal" data-target="#task-name"><i class="fa fa-question-circle question-mark"></i></a> --}}
                            <a href="javascript:void(0)" class="goal-step" 
                           data-message="This relates directly to the task you need to complete and needs to be descriptive and simple. 
                           <br/><br/>
                           Examples of tasks may be:
                           <br/><br/>
                               • Resistance training, <br/>
                               • Daily walk, <br/>
                               • Morning Sit up routine."
                           data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a>
                           </label>



                     <div class="form-group">

                        <input data-toggle="tooltip" title="" type="text" class="form-control" id="SYG3_task" ng-model="SYG3_task"  ng-init="SYG3_task='{{ isset($taskDetails) ? $taskDetails->gb_task_name : null}}'" ng-keypress="pressEnter($event)" value="{{ isset($taskDetails) ? $taskDetails->gb_task_name : null}}" name="SYG3_task">

                     </div>



                     <label>Note related to this task 
                        {{-- <a href="#" data-toggle="modal" data-target="#notes"><i class="fa fa-question-circle question-mark"></i></a> --}}
                        <a href="javascript:void(0)" class="goal-step" 
                           data-message="In this section you want to make notes related to this task that may assist you in ensuring the are done when they are supposed to be done and the importance of them.
                           <br/><br/>
                           <b>Frequency Per Week</b> – How often a week you need to do a certain task
                           <br/><br/>
                           <b>Intensity of training</b> – How hard you need to be doing physical activity if any
                           <br/><br/>
                           <b>Duration of activity</b> – Duration of physical activity if any
                           <br/><br/>
                           Requirements of hypertrophy or limiting muscle mass. - "
                           data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a>
                     </label>



                     <div class="form-group outborder">

                        <textarea onblur="validateGoalTask()" data-toggle="tooltip" title="" data-autoresize rows="3" id="note" name="note" ng-model="note" ng-init="note='{{ isset($taskDetails) ? $taskDetails->gb_task_note : null}}'" placeholder="" class="form-control">{{ isset($taskDetails) ? $taskDetails->gb_task_note : null}}</textarea>

                     </div>



                     <div class="">

                           <label data-toggle="tooltip" title="">Priority 
                              {{-- <a href="#" data-toggle="modal" data-target="#priority"><i class="fa fa-question-circle question-mark"></i></a> --}}
                              <a href="javascript:void(0)" class="goal-step" 
                              data-message="This related to how important this task is to your success of achieving your desired <span style='color:#f64c1e;'>RESULT</span> and may also be determined by if it is a habit already or if it may be a difficult task with barriers that may be difficult to overcome.
                              <br/><br/>
                              <b>Low</b> - Not particularly important or already a habit or behaviour
                              <br/><br/>
                              <b>Normal</b> - Important but not critical
                              <br/><br/>
                              <b>High</b> - Important and required to achieve goal
                              <br/><br/>
                              <b>Urgent</b> - Critical part of the goal and has priority over other tasks."
                              data-message1="<b>Low</b> - Not particularly important or already a habit or behaviour
                              <br/><br/>
                              <b>Normal</b> - Important but not critical
                              <br/><br/>
                              <b>High</b> - Important and required to achieve goal
                              <br/><br/>
                              <b>Urgent</b> - Critical part of the goal and has priority over other tasks."><i class="fa fa-question-circle question-mark"></i></a>
                           </label>



                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">Low

                        <input type="radio" name="Priority" value="Low" class="required"  {{ isset($taskDetails) && $taskDetails->gb_task_priority == 'Low' ? 'checked' : ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">Normal

                        <input type="radio" name="Priority" value="Normal" class="required"  {{ isset($taskDetails) && $taskDetails->gb_task_priority == 'Normal'? 'checked' : ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">High

                        <input type="radio" name="Priority" value="High" class="required" {{ isset($taskDetails) && $taskDetails->gb_task_priority == 'High'? 'checked' : ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">Urgent

                        <input type="radio" name="Priority" value="Urgent" class="required"  {{ isset($taskDetails) && $taskDetails->gb_task_priority == 'Urgent'? 'checked' : ''}}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                    <div class="habitStep">

                     <div class="">

                         <label data-toggle="tooltip" title="">Task Recurrence 
                            {{-- <a href="#" data-toggle="modal" data-target="#task-recurrence"><i class="fa fa-question-circle question-mark"></i></a> --}}
                            <a href="javascript:void(0)" class="goal-step" 
                           data-message="<b>Daily</b> - This is if you are implementing the training into your daily routine with no recovery days
                           <br/><br/>
                           <b>Weekly</b> - If you have one or more recovery days in a week.
                           <br/><br/>
                           <b>Monthly</b> - If it related to a specific day each month may be testing"
                           data-message1="<b>Daily</b>—This is if you are implementing training into your daily routine with no recovery days
                           <br/><br/>
                           <b>Weekly</b>—If you have one or more physical activity days in a week
                           <br/><br/>
                           <b>Monthly</b>— If it related to a specific day each month maybe testing
                           <br/><br/>
                           Tasks are critical to any Lifestyle Design Change and always need to be addressed fully to ensure that all aspects of the tasks are understood."><i class="fa fa-question-circle question-mark"></i></a>
                           </label>



                     </div>

<!--                      <div class="form-group tsk_rec_daily">

                        <label class="container_radio version_2">Daily

                        <input type="radio" name="SYG_task_recurrence" value="daily" class="required">

                        <span class="checkmark"></span>

                        </label>

                     </div> -->

                     <div class="form-group">

                        <label class="container_radio version_2">Weekly

                        <input type="radio" name="SYG_task_recurrence" value="weekly" class="weekly">

                        <span class="checkmark"></span>

                        </label>

                     

                        <div class="showDayBox row showbox" @if((isset($taskDetails))&&($taskDetails->gb_task_recurrence_type=='weekly')) style="display: block;" @else style="display: none;"   @endif>

                           <div class="form-group col-xs-4 col-sm-3">

                              <label class="container_check version_2">Mon

                              {{-- <input type="checkbox" name="week" value="" class="required"> --}}

                              <input id="taskEventRepeatWeekdays0" class="taskEventRepeatWeekdays hidden" value="Monday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Monday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >

                              <span class="checkmark"></span>

                              </label>

                           </div>

                           <div class="form-group col-xs-4 col-sm-3">

                              <label class="container_check version_2">Tue

                               

                             <input id="taskEventRepeatWeekdays1" class="taskEventRepeatWeekdays hidden" value="Tuesday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Tuesday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >

                              <span class="checkmark"></span>

                              </label>

                           </div>

                           <div class="form-group col-xs-4 col-sm-3">

                              <label class="container_check version_2">Wed

                                 <input onclick="validateGoalTask()" id="taskEventRepeatWeekdays2" class="taskEventRepeatWeekdays hidden" value="Wednesday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Wednesday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >

                              <span class="checkmark"></span>

                              </label>

                           </div>

                           <div class="form-group col-xs-4 col-sm-3">

                              <label class="container_check version_2">Thu

                                 <input id="taskEventRepeatWeekdays3" class="taskEventRepeatWeekdays hidden" value="Thursday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Thursday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >

                              <span class="checkmark"></span>

                              </label>

                           </div>

                           <div class="form-group col-xs-4 col-sm-3">

                              <label class="container_check version_2">Fri

                                 <input id="taskEventRepeatWeekdays4" class="taskEventRepeatWeekdays hidden" value="Friday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Friday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >

                              <span class="checkmark"></span>

                              </label>

                           </div>

                           <div class="form-group col-xs-4 col-sm-3">

                              <label class="container_check version_2">Sat

                             

                                 <input onclick="validateGoalTask()" id="taskEventRepeatWeekdays5" class="taskEventRepeatWeekdays hidden" value="Saturday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Saturday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >

                              <span class="checkmark"></span>

                              </label>

                           </div>

                           <div class="form-group col-xs-4 col-sm-3">

                              <label class="container_check version_2">Sun

                                 <input id="taskEventRepeatWeekdays6" class="taskEventRepeatWeekdays hidden" value="Sunday" type="checkbox"  @if(isset($taskDetails) && ($taskDetails->gb_task_recurrence_type=='weekly') && (in_array('Sunday', explode(',', $taskDetails->gb_task_recurrence_week)))) checked {{-- @elseif(! isset($taskDetails)) checked --}} @endif >

                              <span class="checkmark"></span>

                              </label>

                           </div>

                        </div>

                  

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">Monthly

                        <input type="radio" name="SYG_task_recurrence" value="monthly" class="required">

                        <span class="checkmark"></span>

                        <div class="showMonthBox month" style="display:none;">

                           <div style="display:flex;align-items: center">

                              Day&nbsp;&nbsp;

                              <select id="gb_task_recurrence_month" class="month-date-task">

                                 @for($i = 1; $i <= calDaysInMonth(); $i++)

                                 <option value ="{{ $i }}">{{ $i }}</option>

                                 @endfor

                               </select> of every month

                              &nbsp;&nbsp;

                           </div>

                        </div>

                        </label>

                     </div>

                    </div>



                     <div class="">

                        <label data-toggle="tooltip" title=""> Who can view your task?
                           {{-- <a href="#" data-toggle="modal" data-target="#taskk"><i class="fa fa-question-circle question-mark"></i></a> --}}
                           <a href="javascript:void(0)" class="goal-step" 
                           data-message="<b>Select Friends</b>—Share details with a select few friends who you believe will be supportive and who will hold you accountable to your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>.
                           <br/><br/>
                           <b>Everyone</b>– Share details of your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> with all friend and family on your Timeline 
                           <br/><br/>
                           <b>Just Me</b>—Only show me details of my <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>
                           <br/><br/>
                           The <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span>  online platform is designed to allow you to connect and interact with fellow team members and like-minded individuals on the same or similar journey to your own. 
                           <br/><br/>
                           Having others view your milestones gives you the opportunity to be given feedback and positive encouragement throughout your journey. 
                           <br/><br/>
                           Allow others to celebrate your success with you!
                            <br/><br/>
                           Accountability is key, fellow <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> members can help hold you accountable when it comes to attending training sessions, staying on target with healthy balanced eating and completing your goal related tasks which relate to your milestones and progression.
                            <br/><br/>
                           <b>T.E.A.M</b> Together Everyone Achieves More"
                           data-message1="<b>Select Friends</b>—Share details with a select few friends who you believe will be supportive and who will hold you accountable to your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>.
                           <br/><br/>
                           <b>Everyone</b>– Share details of your <span style='color:#f64c1e;'><b>EPIC</b> Goal</span> with all friend and family on your Timeline 
                           <br/><br/>
                           <b>Just Me</b>—Only show me details of my <span style='color:#f64c1e;'><b>EPIC</b> Goal</span>
                           <br/><br/>
                           The <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> online platform is designed to allow you to connect and interact with fellow team members and like-minded individuals on the same or similar journey to your own. 
                           <br/><br/>
                           Having others view your habits gives you the opportunity to be given feedback and positive encouragement throughout your journey. 
                           <br/><br/>
                           Allow others to celebrate your success with you!
                             <br/><br/>
                           Accountability is key, fellow <span style='color:#f64c1e;'><b>EPIC</b> RESULT</span> members can help hold you accountable when it comes to attending training sessions, staying on target with healthy balanced eating and completing your goal related tasks which relate to your milestones and progression.
                             <br/><br/>
                           <b>T.E.A.M</b> Together Everyone Achieves More"><i class="fa fa-question-circle question-mark"></i></a>
                        </label>



                     </div>
                     <div class="form-group">

                        <label class="container_radio version_2">Select Friends

                           <input type="radio" name="SYG3_see_task" id="SYG3_see_task" value="Selected friends"  {{ isset($taskDetails) && $taskDetails->gb_task_seen == 'Selected friends'?'checked':'' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>
                     <div class="form-group">

                        <label class="container_radio version_2">Everyone

                           <input type="radio" name="SYG3_see_task" id="SYG3_see_task0" value="everyone"  {{ isset($taskDetails) && $taskDetails->gb_task_seen == 'everyone'?'checked':'' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">Just Me

                           <input type="radio" name="SYG3_see_task" id="SYG3_see_task2" value="Just Me" {{ isset($taskDetails) && $taskDetails->gb_task_seen == 'Just Me'?'checked':'' }}>

                        <span class="checkmark"></span>

                        </label>

                     </div>
                     {{-- @php
                        if(isset($taskData) && $taskData[0]->gb_task_seen == 'Selected friends'){
                        $visibility = '';
                        }else{
                        $visibility = 'hidden';
                        }
                     @endphp --}}
                     <div class="form-group hidden">
                        <input type="text" class="form-control autocomplete" id="SYG3_selective_friends" ng-keypress="pressEnter($event)" name="SYG3_selective_friends" aria-invalid="false">
                     </div>
                     <div class="habitStep">

                     <div class="">

                        <label  data-toggle="tooltip" title="">Send email / SMS 
                           {{-- <a href="#" data-toggle="modal" data-target="#send-email"><i class="fa fa-question-circle question-mark"></i></a> --}}
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
                           It is important to come to the realisation that actions that may seem insignificant such as missing a training session, skipping a meal, or altering guidelines can have adverse accumulative effects on your overall progress, the earlier it is noticed by you by being given a reminder from the <span style='color:#f64c1e;'><b>EPIC</b> TEAM</span>  the sooner you can get on top of it and get back on track.
                           <br/><br/>
                           Approach every milestone related task with the utmost importance as if others are relying on you, because YOU are relying on YOU!"
                           data-message1="<b>When Overdue</b>—If tasks have not been met
                           <br/><br/>
                           <b>Daily</b>—Send me messages related to tasks daily
                           <br/><br/>
                           <b>Weekly</b>—Send me messages related to tasks weekly
                           <br/><br/>
                           <b>Monthly</b>—Send me messages related to tasks monthly
                           <br/><br/>
                           <b>None</b>—Do not send me anything related to tasks
                           <br/><br/>
                           At <span style='color:#f64c1e;'><b>EPIC</b></span> we know that life can sometimes get the better of the best of us, reminders keep you accountable for your actions, time management, and overall results. 
                           <br/><br/>
                           It is important to come to the realisation that actions that may seem insignificant such as missing a training session, skipping a meal, or altering guidelines can have adverse accumulative effects on your overall progress, the earlier it is noticed by you by being given a reminder from the <span style='color:#f64c1e;'><b>EPIC</b> TEAM</span> the sooner you can get on top of it and get back on track.
                           <br/><br/>
                           Approach every habit related to milestones with the utmost importance as if others are relying on you, because YOU are relying on YOU!"><i class="fa fa-question-circle question-mark"></i></a>
                        </label>



                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">When Overdue

                        <input type="radio" name="creattask-send-mail" value="when_overdue"  @if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "when_overdue")) checked @endif>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">

                           Daily

                           <input type="radio" name="creattask-send-mail" value="daily" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "daily")) checked @endif class="daily">

                           <span class="checkmark"></span>

                           <div class="showTimeBox">

                              <select id="daily_time_task">                                  

                                  <option value="1"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 1)) selected @endif>1:00 am</option>

                                  <option  value="2" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 2)) selected @endif>2:00 am</option>

                                  <option value="3"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 3)) selected @endif>3:00 am</option>

                                  <option value="4"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 4)) selected @endif>4:00 am</option>

                                  <option value="5"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 5)) selected @endif>5:00 am</option>

                                  <option value="6"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 6)) selected @endif>6:00 am</option>

                                  <option value="7"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 7)) selected @endif>7:00 am</option>

                                  <option value="9"  @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 9)) selected @endif>9:00 am</option>

                                  <option value="10" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 10)) selected @endif>10:00 am</option>

                                  <option value="11" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 11)) selected @endif>11:00 am</option>

                                  <option value="12" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 12)) selected @endif>12:00 PM</option>

                                  <option value="13" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 13)) selected @endif>1:00 PM</option>

                                  <option value="14" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 14)) selected @endif>2:00 PM</option>

                                  <option value="15" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 15)) selected @endif>3:00 PM</option>

                                  <option value="16" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 16)) selected @endif>4:00 PM</option>

                                  <option value="17" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 17)) selected @endif>5:00 PM</option>

                                  <option value="18" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 18)) selected @endif>6:00 PM</option>

                                  <option value="19" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 19)) selected @endif>7:00 PM</option>

                                  <option value="20" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 20)) selected @endif>8:00 PM</option>

                                  <option value="21" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 21)) selected @endif>9:00 PM</option>

                                  <option value="22" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 22)) selected @endif>10:00 PM</option>

                                  <option value="23" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 23)) selected @endif>11:00 PM</option>

                                  <option value="24" @if(isset($taskDetails) && ($taskDetails->gb_reminder_task_time == 24)) selected @endif>12:00 am</option>

                              </select>

                          </div>
                  

                        </label>

                     </div>

                             {{--  --}}
                             <div class="form-group">
                              <label class="container_radio version_2">
                                 Weekly

                                 <input type="radio" id="creattask_send_Weekly" name="creattask-send-mail" class="weekly"  value="weekly" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "weekly")) checked @endif>
                                 <span class="checkmark"></span>
                                 <div class="showDayBox">
                                    {{-- daily_time_task --}}
                                    <select id="weekly_day_task">
                                        <option value="Mon" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "Mon")) selected @endif>Mon</option>
                                        <option value="Tue" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "Tue")) selected @endif>Tue</option>
                                        <option value="Wed" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "Wed")) selected @endif>Wed</option>
                                        <option value="Thu" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "Thu")) selected @endif>Thu</option>
                                        <option value="Fri" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "Fri")) selected @endif>Fri</option>
                                        <option value="Sat" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "Sat")) selected @endif>Sat</option>
                                        <option value="Sun" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "Sun")) selected @endif>Sun</option>
                                    </select>
                                </div>
                              </label>
                           </div>
                           <div class="form-group">

                              <label class="container_radio version_2">
                                 Monthly
                                 <input type="radio" name="creattask-send-mail" value="monthly" class="monthly" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "monthly")) checked @endif>
                                 <span class="checkmark"></span>
                                 <div class="showMonthBox">
                                    <select id="month_date_task">
                                      <option value="1" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "1")) selected @endif>1</option>
                                      <option value="2" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "2")) selected @endif>2</option>
                                      <option value="3" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "3")) selected @endif>3</option>
                                      <option value="4" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "4")) selected @endif>4</option>
                                      <option value="5" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "5")) selected @endif>5</option>
                                      <option value="6" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "6")) selected @endif>6</option>
                                      <option value="7" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "7")) selected @endif>7</option>
                                      <option value="8" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "8")) selected @endif>8</option>
                                      <option value="9" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "9")) selected @endif>9</option>
                                      <option value="10" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "10")) selected @endif>10</option>
                                      <option value="11" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "11")) selected @endif>11</option>
                                      <option value="12" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "12")) selected @endif>12</option>
                                      <option value="13" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "13")) selected @endif>13</option>
                                      <option value="14" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "14")) selected @endif>14</option>
                                      <option value="15" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "15")) selected @endif>15</option>
                                      <option value="16" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "16")) selected @endif>16</option>
                                      <option value="17" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "17")) selected @endif>17</option>
                                      <option value="18" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "18")) selected @endif>18</option>
                                      <option value="19" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "19")) selected @endif>19</option>
                                      <option value="20" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "20")) selected @endif>20</option>
                                      <option value="21" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "21")) selected @endif>21</option>
                                      <option value="22" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "22")) selected @endif>22</option>
                                      <option value="23" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "23")) selected @endif>23</option>
                                      <option value="24" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "24")) selected @endif>24</option>
                                      <option value="25" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "25")) selected @endif>25</option>
                                      <option value="26" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "26")) selected @endif>26</option>
                                      <option value="27" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "27")) selected @endif>27</option>
                                      <option value="28" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "28")) selected @endif>28</option>
                                      <option value="29" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "29")) selected @endif>29</option>
                                      <option value="30" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "30")) selected @endif>30</option>
                                      <option value="31" @if(isset($taskDetails) && ($taskDetails->gb_reminder_habits_time == "31")) selected @endif>31</option>
                                    </select>
                                </div>
                              </label>
                           </div>
   
                        <label>Get Notifications Through ? </label>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email.
                        <input type="radio" name="creattask-send-epichq" value="email" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder_epichq == "email")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through chat.
                        <input type="radio" name="creattask-send-epichq" value="epichq" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder_epichq == "epichq")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                     <div class="form-group">
                        <label class="container_radio version_2">I want to get reminder notification through email and chat both.
                        <input type="radio" name="creattask-send-epichq" value="email-epichq" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder_epichq == "email-epichq")) checked @endif>
                        <span class="checkmark"></span>
                        </label>
                     </div>

                     <div class="form-group">

                        <label class="container_radio version_2">None

                        <input type="radio" name="creattask-send-epichq" value="none" @if(isset($taskDetails) && ($taskDetails->gb_task_reminder == "none")) checked @endif>

                        <span class="checkmark"></span>

                        </label>

                     </div>

                     </div>

                    



                  </div>

                  

               <div class="step showNext" data-step='23' data-next="0" data-value="">

                  <div class="row">
                     <div class="col-md-5 border-head mb-10">
                       <div class="watermark1"><span>19.</span></div>
                       <label class="steps-head">Would you like to establish another <b>task</b> ? *</label>
                     </div>
                     <div class="col-md-7 mb-10 text-center">
                    <a href="javascript:void(0)" class="goal-step" 
                          data-message="Tooltip not provided on the documents"
                          data-message1="Tooltip not provided on the documents"><i class="fa fa-question-circle question-mark"></i></a>
                     </div>

                   </div>

                  <div class="form-group">

                     {{-- <label class="">  --}}

                     {{-- <span> Schedule New Task</span> --}}

                     {{-- <a class ="btn btn-primary pull-right add-task">Schedule New Task</a> --}}
                     <a class ="btn btn-primary add-task">Schedule New Task</a>
                     <div class="tooltip-sign mb-10 tooltip_btn">
                          <a href="javascript:void(0)" class="goal-step" 
                            data-message="Please provide tooltip content"
                            data-message1="Please provide tooltip content"><i class="fa fa-question-circle question-mark"></i></a>
                       </div>

                     {{-- </label> --}}

                  </div>

                   <div class="table-responsive">

                     <table class="table table-striped table-bordered table-hover" id="client-datatable-task">

                         <thead>

                         <tr>

                             <th class="">Task Name</th>

                             <th class="center mw-70 w70 no-sort hidden-xs">Priority</th>

                             <!--th class="hidden-xxs">Due Date</th-->

                             <th class="hidden-xs">Habit</th>

                             <th class="center mw-70 w70 no-sort">Shared</th>

                             <th class="center mw-70 w70 no-sort">Actions</th>

                         </tr>

                         </thead>

                         <tbody id="tasklist">

                            @foreach($taskData as $tasks)

                            <tr>

                              <td>{{isset($tasks->gb_task_name)?$tasks->gb_task_name:''}}</td>

                              <td>{{isset($tasks->gb_task_priority)?ucfirst($tasks->gb_task_priority):''}}</td>

          

                              <td>{{isset($tasks->taskhabit->gb_habit_name)?$tasks->taskhabit->gb_habit_name:''}}</td>

                              <td>{{isset($tasks->gb_task_seen)?$tasks->gb_task_seen:''}}</td>

                              <td class="center">

          

                                  <a class="btn btn-xs btn-default tooltips task-edit" data-placement="top" data-original-title="Edit" data-task-id = "{{$tasks->id}}">

                                      <i class="fa fa-pencil" style="color:#ff4401;"></i>

                                  </a>

          

                                   <a class="btn btn-xs btn-default tooltips delete-task" data-entity="task" data-placement="top" data-original-title="Delete" data-task-id = "{{$tasks->id}}">

                                      <i class="fa fa-times" style="color:#ff4401;"></i></a>

          

                                </td>

                            </tr>

                             @endforeach

                         </tbody>

                     </table>

                 </div>

               </div>

               @php

               $smartReview = [];

               if(!empty($goalDetails) && $goalDetails) {

                  if($goalDetails->gb_goal_review && isset($goalDetails->gb_goal_review))

                     $smartReview = explode(',', $goalDetails->gb_goal_review);



               }

               @endphp



                  <div class="submit step reviewGoal" data-step='24' id="end" data-next="0" data-value="0">



                     <div class=" Smart-review-session">

                        <div class="row">
                           <div class="col-md-5 border-head mb-10">
                             <div class="watermark1"><span>20.</span></div>
                             <label class="steps-head">Let's review your goal. Is your  <b>S.M.A.R.T.</b> ? </label>
                           </div>
                           <div class="col-md-7 mb-10 text-center">
                           <a href="javascript:void(0)" class="goal-step" 
                              data-message="<b>Specific </b> — A clearly defined, clear, concise, and detailed goal. i.e., losing Skg, dunking a
                              basketball, running 21km etc.
                              <br/><br/>
                              <b>Measurable </b> - Measurable to include milestones i.e., Dropping a clothing size measured in cm,
                              losing weight measured in kg, doing x amount of push ups measured in reps etc.
                              <br/><br/>
                              <b>Achievable </b>- Your motivation & commitment to all the necessary changes & improvement you
                              need to implement. Are you WILLING to make all the necessary changes? ONLY if you answer
                              YES to this can you achieve this goal and move onto the next phase of the process.
                              <br/><br/>
                              <b>Relevant </b> — Your goal needs to be relevant and meaningful to YOU. Intrinsic goals, goals for
                              you, are goals that matter to you, thus more likely to be achieved. i.e, If I lose weight, I will feel
                              more confident, when I run 21 km, I will feel proud etc. never set a goal to please anybody but
                              yourself and your life.
                              <br/><br/>
                              <b>Time Dependant </b> — The time you need to commit to achieving this goal. This includes all your
                              milestones and considers all the changes that need to be implemented. Lose 5 kg in 8 weeks,
                              do 10 Pushups in 3 months etc.
                              <br/><br/>
                              You may not know the answers to these questions, but your trainer will help you to make this
                              goal realistic and achievable." data-message1="<b>Specific </b> — A clearly defined, clear, concise, and detailed goal. i.e., losing Skg, dunking a
                              basketball, running 21km etc.
                              <br/><br/>
                              <b>Measurable </b> - Measurable to include milestones i.e., Dropping a clothing size measured in cm,
                              losing weight measured in kg, doing x amount of push ups measured in reps etc.
                              <br/><br/>
                              <b>Achievable </b>- Your motivation & commitment to all the necessary changes & improvement you
                              need to implement. Are you WILLING to make all the necessary changes? ONLY if you answer
                              YES to this can you achieve this goal and move onto the next phase of the process.
                              <br/><br/>
                              <b>Relevant </b> — Your goal needs to be relevant and meaningful to YOU. Intrinsic goals, goals for
                              you, are goals that matter to you, thus more likely to be achieved. i.e, If I lose weight, I will feel
                              more confident, when I run 21 km, I will feel proud etc. never set a goal to please anybody but
                              yourself and your life.
                              <br/><br/>
                              <b>Time Dependant </b> — The time you need to commit to achieving this goal. This includes all your
                              milestones and considers all the changes that need to be implemented. Lose 5 kg in 8 weeks,
                              do 10 Pushups in 3 months etc.
                              <br/><br/>
                              You may not know the answers to these questions, but your trainer will help you to make this
                              goal realistic and achievable.">
                       <i class="fa fa-question-circle question-mark"></i></a>
                           </div>
     
                         </div>
     
                             <h5 class="text-center m-t-20 m-b-0"><span> </span> </h5>
                             <br>

                        <div class="row">

                           <div class="vp-form-input-list p-l-0 padding-right-0">

                              {{--<div class="col-md-1"></div>--}}

                              <div class="form-group Smart-review-check col-md-6">

                                <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('specific', $smartReview)) active @endif">

                                  {{-- <div class="lable">

                                    Specific <span class="checkmark" data-toggle="tooltip"></span>

                                  </div> --}}

                                  <label class="container_check version_2">Specific

                                    <input type="checkbox" name="smart" value="" {{in_array('specific', $smartReview)?'checked' : ''}}>

                                    <span class="checkmark"></span>

                                    </label>

                                  

                                  <input id="Specific" name="Specific" class="goalsmart hidden" data-is-checked="{{in_array('specific', $smartReview)?'yes' : 'no'}}" value="specific" required type="checkbox" @if(in_array('specific', $smartReview)) checked @endif>

                                </div>

                              </div>

                              

                              <div class="form-group Smart-review-check col-md-6">

                                <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('measurable', $smartReview)) active @endif">

                                

                                  <label class="container_check version_2">Measurable

                                    <input type="checkbox" name="smart" value="" {{in_array('measurable', $smartReview)?'checked' : ''}}>

                                    <span class="checkmark"></span>

                                    </label>

                                  

                                  <input id="Measurable" name="Measurable" class="goalsmart hidden" data-is-checked="{{in_array('measurable', $smartReview)?'yes' : 'no'}}" value="measurable" required type="checkbox" @if(in_array('measurable', $smartReview)) checked @endif>

                                </div>

                              </div>

                              <div class="form-group  Smart-review-check col-md-6">

                                <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('attainable', $smartReview)) active @endif">

                                

                                  <label class="container_check version_2">Attainable

                                    <input type="checkbox" name="smart" value="" {{in_array('attainable', $smartReview)?'checked' : ''}}>

                                    <span class="checkmark"></span>

                                    </label>

                                  

                                  <input id="Attainable" name="Attainable" class="goalsmart hidden" data-is-checked="{{in_array('attainable', $smartReview)?'yes' : 'no'}}" value="attainable" required type="checkbox" @if(in_array('attainable', $smartReview)) checked @endif>

                                </div>

                              </div>

                              <div class="form-group  Smart-review-check col-md-6">

                                <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('relevant', $smartReview)) active @endif">

                                 

                                  <label class="container_check version_2">Relevant

                                    <input type="checkbox" name="smart" value=""{{in_array('relevant', $smartReview)?'checked' : ''}}>

                                    <span class="checkmark"></span>

                                    </label>

                                  

                                  <input id="Relevant" name="Relevant" class="goalsmart hidden" data-is-checked="{{in_array('relevant', $smartReview)?'yes' : 'no'}}" value="relevant" required type="checkbox"  @if(in_array('relevant', $smartReview)) checked @endif>

                                </div>

                              </div>

                              <div class="form-group Smart-review-check col-md-6">

                                <div class="checkbox ind-checkbox prefTrainSlot @if(in_array('time_Bound', $smartReview)) active @endif">

                                

                                  <label class="container_check version_2">Time-Bound

                                    <input type="checkbox" name="smart" value=""{{in_array('time_Bound', $smartReview)?'checked' : ''}}>

                                    <span class="checkmark"></span>

                                    </label>

                                  

                                  <input id="Time-Bound" name="Time-Bound" class="goalsmart hidden" data-is-checked="{{in_array('time_Bound', $smartReview)?'yes' : 'no'}}" value="time_Bound" required type="checkbox" @if(in_array('time_Bound', $smartReview)) checked @endif>

                                </div>

                              </div>

                              {{-- {{ dd($goalDetails->toArray()) }} --}}

                             

                            </div>

                           </div>

                     <hr>

                     <div class="form-group">

                        {{-- <a href="" class="Step-your-goal1 edit_goal_element" style="color:#ff4401;position: absolute;right: 0;top: 0%;font-size: 17px;"><i class="fa fa-pencil editable-pencil"></i></a> --}}

                           <div class="task_declare">

                              <p class="achieve-description-label"><strong> Name of goal:</strong></p>

                              <p class="goal-name">{{ !empty($goalDetails) && isset($goalDetails->gb_goal_name) ? ucwords($goalDetails->gb_goal_name) : ''}}</p>

                              <a href="" id="Step-your-goal1" class="Step-your-goal1  edit_goal_element"><i class="fa fa-pencil editable-pencil" style="color:#ff4401; position: absolute;left: 95%;top: 0%;font-size: 17px;"></i></a>

                              <p class="achieve-description-label"><strong> I want to accomplish:</strong></p>

                              <p class ="achieve-description" style="word-break: break-all;">{{ !empty($goalDetails) && isset($goalDetails->gb_achieve_description) ? ucwords($goalDetails->gb_achieve_description) : ''}}</p>

                           

                              <p class="fail-description-label"><strong> Why is this important:</strong></p>

                              <p class ="fail-description">{{ !empty($goalDetails) && isset($goalDetails->gb_important_accomplish) ? ucwords($goalDetails->gb_important_accomplish) : ''}}</p>

                           </div>

                     </div>

                     <hr>

                     <div class="form-group">

                           <div class="task_declare ">

                              <p class="goal-seen"><strong></strong></p>

                              <img id="smartReviewImg" src="" class="img-responsive SYGPreviewPics previewPics hidden"/>

                              <p class="goal-due-date">

                                 {{ !empty($goalDetails) && isset($goalDetails->gb_goal_seen) ? 'Shared: '.ucwords($goalDetails->gb_goal_seen) : ''}}<br/>

                                 {{ !empty($goalDetails) && isset($goalDetails->gb_due_date) ? 'Due date: '.date('D, d F Y', strtotime($goalDetails->gb_due_date)) : ''}}

                                 <strong></strong>

                              </p>

                           </div>

                     </div>

                     <hr>

                     <div class="form-group">

                         {{-- <a href="" class="Step-your-goal1 edit_goal_element" style="color:#ff4401;position: absolute;right: 0;top: 0%;font-size: 17px;"><i class="fa fa-pencil editable-pencil"></i></a> --}}

                           <div class="show_task-section  ">

                              <h4>Milestone: <a href="" id="Step-your-goal4" class="Step-your-goal4  edit_goal_element"> <i class="fa fa-pencil editable-pencil" style="color:#ff4401;"></i></a></h4>

                              <ul class="milestone-label">

                                 @if(!empty($milestonesData))

                                 @foreach($milestonesData as $milestone)

                                 <a class="Step-your-goal4 milestone-text"><li>{{ isset($milestone->gb_milestones_name) ? ucwords($milestone->gb_milestones_name): ''}}

                                    </li></a>

                                  

                                 <p>{{ isset($milestone->gb_milestones_seen) ? ucwords($milestone->gb_milestones_seen): ''}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ isset($milestone->gb_milestones_date) ? ucwords($milestone->gb_milestones_date): ''}}</p>

                                 @endforeach

                                 @endif

                              </ul>

                           </div>

                     </div>

                    

                     <hr>

                     <div class="form-group">

                         {{-- <a href="" class="Step-your-goal1 edit_goal_element" style="color:#ff4401;position: absolute;right: 0;top: 0%;font-size: 17px;"><i class="fa fa-pencil editable-pencil"></i></a> --}}

                         <div class="">

                           <div class="show_task-section well habbit-section ">

                             <h4>Habits: <a href="" id="Step-your-goal3" class="Step-your-goal3 edit_goal_element"> <i class="fa fa-pencil editable-pencil " style="color:#ff4401;"></i></a></h4>

                             <ul class ="habit-label">

                               @if(!empty($habitData))

                               @foreach($habitData as $habbit)

                               <a class="Step-your-goal3 habit-text" data="132"><li>{{ isset($habbit->gb_habit_name) ? ucwords($habbit->gb_habit_name): ''}}</li></a>

                                 @if(!empty($habbit->gb_habit_seen))

                                   <p>{{ isset($habbit->gb_habit_seen) ? ucwords($habbit->gb_habit_seen): ''}}</p>

                                 @endif  

                               @endforeach

                               @endif

                             </ul>

                           </div>

                         </div>

                          </div>

                          <hr>

                         <div class="form-group">

                           <div class="show_task-section well task-section">

                             <h4>Tasks: <a href="" id="Step-your-goal2" class="Step-your-goal2 edit_goal_element"><i class="fa fa-pencil editable-pencil" style="color:#ff4401;"></i></a></h4>

                             <ul class="tasks-label">

                               @if(!empty($taskData))

                               @foreach($taskData as $task)

                               <a class="Step-your-goal2 task-text" data="202"><li>{{ isset($task->gb_task_name) ? ucwords($task->gb_task_name): ''}}</li></a>

                                 @if(!empty($task->gb_task_seen))

                                   <p>{{ isset($task->gb_task_seen) ? ucwords($task->gb_task_seen): ''}}</p>

                                 @endif  

                               @endforeach

                               @endif

                             </ul>

                           </div>

                         </div>

                    

                      <hr>

                     {{-- <div class="form-group">

                         <a href="" class="Step-your-goal1 edit_goal_element" style="color:#ff4401;position: absolute;right: 0;top: 0%;font-size: 17px;"><i class="fa fa-pencil editable-pencil"></i></a>

                         <label>Tasks</label>

                         <ul>

                             <li>Resistance Trainings

                             </li>

                             <li>Cardiovascular Training

                             </li>Recovery Routines</li>

                             <li>Portion Distortion</li>

                         </ul>

                     </div> --}}

                    

                     <div class="form-group">

                           <div class="show_task-section well notes-section">

                             <h4>Notes:</h4>                 

                                <p class="gb_goal_notes">{{ !empty($goalDetails) && isset($goalDetails->gb_goal_notes) ? ucwords($goalDetails->gb_goal_notes) : ''}}</p>

                           </div>

                         </div> 

                  </div>

                  </div>

               </div>

               <!-- /middle-wizard -->

              <div id="bottom-wizard">
                    <span class="qodef-grid-line-center"><span class="inmotion-total-slides">
              <div class="d-flex">
                <a href="#" class="step-back"><span class="prev-name">DEFINE YOUR GOAL</span></a>&nbsp;&nbsp;<a href="#" class="arrow step-back">&#8672;</a>               
                <div class="current-section">DEFINE YOUR GOAL</div>
                <a href="#" class="arrow step-forward formStepSecond">&#8674;</a>&nbsp;&nbsp;
                <a href="#" class="step-forward"> <span class="next-name">ESTABLISH YOUR MILE STONES</span></a>
              </div>
           
            <span class="inmotion-ts-active-num section-step">01</span>
            <span class="inmotion-ts-active-separator">/</span>
            <span class="inmotion-ts-active-all all-section-step">05</span>
            </span>
            <span class="inmotion-total-slides visible-xs question-s">QUESTIONS<br>
            <span class="inmotion-ts-active-num question-step">5</span>
            <span class="inmotion-ts-active-separator">/</span>
            <span class="inmotion-ts-active-all">20</span>
            </span>
            <span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span>
             <!-- <span class="qodef-grid-line-center"><span class="inmotion-total-slides">SECTION<br>
            <span class="inmotion-ts-active-num section-step">01</span>
            <span class="inmotion-ts-active-separator">/</span>
            <span class="inmotion-ts-active-all all-section-step">05</span>
            </span>
            <span class="inmotion-total-slides visible-xs question-s">QUESTIONS<br>
  <span class="inmotion-ts-active-num question-step">00</span>
  <span class="inmotion-ts-active-separator">/</span>
  <span class="inmotion-ts-active-all">20</span>
</span>
            <span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span></span> -->
                <div class="row">
                <div class="col-sm-5 col-xs-5"><button type="button" name="backward" class="backward">Prev</button></div>
                <div class="col-sm-7 col-xs-7  btn-section">
                   <div class="general-notes-button note_area">
              <a href="javascript:void(0)" class="btn btn-primary add-note" data-toggle="modal" data-target="#general-notes-popup"><i class="fa fa-plus"></i> Notes</a>

            </div>
                  <button type="button" name="forward" class="forward data-save nextData">Next</button>
                 <button type="button" class="submit submit-step final-step-submit">Submit</button>
               </div>
                </div>
                  

                 <div class="back_to_goal_list backto_dashboard">

        <button type="button" name="" class="btn">Back to Goal List</button>
    </div>
                </div>

            </form>

            <!-- /bottom-wizard -->

         </div>

      </div>

   </div>

</div>

<div class="modal picCropModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

   <div class="modal-dialog">

      <div class="modal-content">

         <div class="modal-header">

            <h4 class="modal-title">Choose Headshot </h4>

         </div>

         <div class="modal-body">

            <div class="btn-group m-b-10">

               <a class="btn btn-primary btn-o toggle-ratio" href="#" data-ratio="1" data-crop-selector="square">Square</a>

               <a class="btn btn-primary btn-o toggle-ratio" href="#" data-ratio="0" data-crop-selector="rectangle">Rectangle</a>

            </div>

            <div class="center">

               <img alt="Loading..." class="preview" />

               <input type="hidden" name="ui-x1" />

               <input type="hidden" name="ui-y1" />

               <input type="hidden" name="ui-w" />

               <input type="hidden" name="ui-h" />

               <input type="hidden" name="widthScale" />

               <input type="hidden" name="heightScale" />

               <input type="hidden" name="photoName">

            </div>

         </div>

         <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

            <button type="button" class="btn btn-primary save">Save</button>

         </div>

      </div>

   </div>

</div>

<!--goal modal-->

<!-- Modal -->
{{-- <div id="goal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
        <p> You have selected to create your own unique goal, a short and  definitive name of the goal you want achieve can help bring it to life and make it reality.</p>
        <p>Example may include: <b style='color:#f64c1e;'>Master Box Jump</b>, <b style='color:#f64c1e;'>Improve 100m Sprint</b>.</p><p> Choose a name that best describe your goal and the way you envision it.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="describe" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
        <p>Describe your desired result or outcome, including the changes you wish to see along the way, and what aspect of the goal matters to you most.</p>
       <p><b style='color:#f64c1e;'>For example,<br> if your goal is to limit stress, describe exactly what type of stress you are wanting to work on.</p><p> Is that what you wanting to remove self from 50% of the stressful situation you currently find yourself in weekly?</p><p>Do you want to learn to switch off, rather than bringing work stress into home environment?</b>
       </p></div>
       <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="accomplish-goal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
     <p> Why is this an intrinsic goal?, how long have you wanted to achieve this goal for?</p> <p>And what are the positive effects on all aspects of your life as a result of achieving it?</p><p><b style='color:#f64c1e;'>Example includes: Feeling Confident, Looking and feeling stronger.</p>
     </div>
     <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>

</div>
</div>

<div id="achieve-goal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
       <p>Questions to ask your self:</p><p> How does maintaining the status quo affect you?</p><p>Can you maintain your current lifestyle for the next 5, 10, 15 years?</p><p>Are your loved ones around you willing to put up with your failure to change?</p><p>Is it negatively affecting them?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="relevant-goal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
       <p> Why is this goal more important to you than everyone else around you?</p><p>What internal chnages will you fill that other may not realise initially?</p><p>(Why is it specific your current position)</p><p>Are you doing this for you are as a request from someone else?
      </p></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="special-occasion" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
       <p> Do you have any special occasion coming up?</p><p>Do you need to achieve this goal in time for the event? (Wedding)</p><p>Is their a larger life event or stage of life that you may be reaching that has shifted you midst and helped you decide to take action? (Closing in on 50's)(Possibly of children or grandchildren)</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="due-goal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
      <p> <b> Date Selector</b> - Be sure to select a realistic due date for your goal and do nou set your self up failure before beginning. You must take into account your willingness to commit to your habit related tasks.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="your-habit" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
      <p>   <b>Physical Activity</b> - This is the first habit to be mentioned as it is for most, the easiest habit to build on. Physical activity requires less mental focus and discipline and at EPIC you have further help by being 100% guided and motivated in training sessions. We use this as the starting point to build routine with resistance training, later adding in cardio and recovery routine as build your habit further.</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="important-habit" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
   <p>By creating this habit what aspect of your life will chnage?</p> <p>  Resistance training to improve functional strength.</p><p>Cardiovascular endurance to ensure we can do more for longer.</p><p> Recovery routine incorporating stretching and rolling.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="see-habit" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
   <p>1. <b style='color:#f64c1e;'>Everyone</b> - Share details and habits with friends and family.</p><p>2. <b style='color:#f64c1e;'>Just Me</b> - Only show me details and habits.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<div id="task-habit" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
    <p>    Physical activity would have multiple task that fall under it including: Resistance training, Cardio, Stretch</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="task-name" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
    <p>    Resistance tarining, Daily walk, Morning Sit up routine.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="notes" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
      <p>Frequency Per Week.</p><p>Intensity of training.</p><p> Duration of activity.</p><p> Requirements of hypertrophy or limiting muscle mass.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="priority" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
     <p>   1. <b style='color:#f64c1e;'>Low</b> - Not very important or already a habit or behaviour</p><p>2. <b style='color:#f64c1e;'>Normal</b> - Important but not critical </p><p>3. <b style='color:#f64c1e;'>High</b> - Important and required to achieve goal</p><p>4. <b style='color:#f64c1e;'>Urgent</b> - Critical part of the goal and has priority over other tasks.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="task-recurrence" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
       <p> 1. <b style='color:#f64c1e;'>Daily</b> - This is if you are implementing the training in to your daily routine with no recovery days</p><p> 2. <b style='color:#f64c1e;'>Weekly</b> - If you have one or more recovery days in a week.</p><p> 3. <b style='color:#f64c1e;'>Monthly</b> - If it realated to a specific day each month may be testing</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="taskk" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
       <p>  1. <b style='color:#f64c1e;'>Everyone</b> - Share details and habits with friends and family.</p><p>2. <b style='color:#f64c1e;'>Just Me</b> - Only show me details and habits.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="send-email" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
        <p> 1. <b style='color:#f64c1e;'>Overdue</b> </p><p> 2. <b style='color:#f64c1e;'>Due</b> </p><p> 3. <b style='color:#f64c1e;'>None</b></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="milestone" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-popup">
      <div class="modal-body">
         <h4 style='color: #f94211'><b>TOOL TIP</b></h4>
         <p>
Does successfully creating this habit contribute to hitting your milestones and achieving your overall goal? </p><p>Yes select individual or Yes select all.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div> --}}


<div id="general-notes-popup" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->

    <div class="modal-content">



      <div class="modal-header">

      

        <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>

      <div class="modal-body">
        <h4 style='color: #f94211'><b>Enter notes</b></h4>
        <div class="note_area">

               <textarea class="form-control" id="goal_notes" name="describe_achieve" placeholder="General Notes">{{ !empty($goalDetails) && isset($goalDetails->gb_goal_notes) ? ucwords($goalDetails->gb_goal_notes) : ''}}</textarea>

             </div>

      </div>

      <div class="modal-footer">

         <button type="button" class="btn save-notes" style="background: #f94211;color:white">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>

    </div>



  </div>

</div>
<div id="goal-step" class="modal fade" role="dialog">
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
 <div id="temp-modal" class="temp-modal modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
   <div class="modal-dialog">
 
     <!-- Modal content-->
     <div class="modal-content modal-popup">
        {{-- <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <br>
       </div> --}}
       <div class="modal-body">
          
       <div class="row">
          <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/weightmanagement.png')}}">
             <input type="radio" name="template" data-from="popup" data-id='1'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/drop_a_size.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='2'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/eat.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='3'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/improve_h.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='4'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/reduce_stress.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='5'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/improve_my_sleep.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='6'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/improve_health.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='7'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/injury.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='8'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/increase_activity.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='9'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/balance.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='10'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/health.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='11'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/improve_posture.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='12'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/time_man.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='13'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/improve_per.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='14'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/improve_c.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='15'  value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
             <div class="form-group col-md-3 col-xs-4">
             <label class="container_radio version_2"><img src="{{asset('result/images/become_proactive.jpg')}}">
             <input type="radio" name="template" data-from="popup" data-id='16' value="create_new_goal">
             <span class="checkmark"></span>
             </label>
          </div>
       </div>
       </div>
       <div class="modal-footer">
          <a type="button" class="btn-default btn choose-immediate-priority" disabled="true" style="background-color: black;
         color: white;">Continue with above selected goal</a>
         <a type="button" class="btn choose-create-new-goal" style="background-color: black;
         color: white;">Create New Goal</a>
         <button type="button" class="btn btn-default same-template" data-dismiss="modal">No, I am ok with previously selected goal</button>
         
       </div>
     </div>
 
   </div>
 </div>
 {!! Html::script('result/js/autocomplete.js?v='.time()) !!}
<script>
       $(document).on('click','.goal-step',function(){
         goal_type = $('input[name="chooseGoal"]:checked').val();
         if(goal_type == 'create_new_goal'){
            var message = $(this).data('message');
         }else if(goal_type == 'choose_form_template'){
            var message = $(this).data('message1');
         }
         $(this).attr('data-toggle','modal')
         $(this).attr('data-target','#goal-step')
         $("#goal-step").attr('aria-modal',true)
         $("#goal-step").addClass('in')
         $("#goal-step").find('.message').html(message);
         $("#goal-step").find('.message').css('color','#333');
   })

   var options = [
      @foreach($my_friends as $key => $client)
      { 'tag':'{{$client["name"]}}','value': '{{$client["id"]}}' },
      @endforeach
      ];

      $('.autocomplete').amsifySuggestags({
      	type :'bootstrap',
      	suggestions: options,
      	whiteList:true,
      });
</script>
<script>

   function scrollToGoalTop() { 

          

       var targetElm = document.querySelector('#wizard_container'); // reference to scroll 

      targetElm.scrollIntoView();



       } 

   </script>

<script>

   

   $(document).ready(function(){

    $(document).on('click', 'input[name="SYG_task_recurrence"][value="monthly"]', function() {
        $('.showMonthBox').show();
    });

     $('#gb_habit_select_all_milestone').click(function() {

       let src = $('div.milestone_div_class div.dropdown-menu ul.dropdown-menu li');

       //console.log(src);

       // let previusSelectedMilesone = JSON.parse($('input.previusSelectedMilesone').val());



       if(!$(this).is(':checked')) {

         $('div.milestone-dropdown div.bootstrap-select button').prop('title', '');



         $('div.milestone-dropdown div.bootstrap-select button span.filter-option').text('');



         $('select#milestone_div option').prop('selected', false);

         src.removeClass('selected');

         

       } else {

         let allMilestones = [];



         src.each(function(){

           allMilestones.push($(this).find('a span.text').text());

         });

         $('div.milestone-dropdown div.bootstrap-select button').prop('title', allMilestones.toString());



         $('div.milestone-dropdown div.bootstrap-select button span.filter-option').text(allMilestones.toString());



         $('select#milestone_div option').prop('selected', true);

         src.addClass('selected');

       }

     });



     $('#milestone_div').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {

     });

   });

 </script>

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

</script>

<script>

   $(document).ready(function() {

   $('.prefTrainSlot').click(function() {

       var $this = $(this),

       input = $(this).find('input');                  

       var attr = input.attr('data-is-checked');                

       if (attr == 'no') {

         $this.addClass('active');

         $this.attr('data-status', 'active');

         input.attr('data-is-checked', 'yes');

         input.prop('checked', true);

       } else {

         $this.removeClass('active');

         $this.attr('data-status', 'inactive');                    

         input.attr('data-is-checked', 'no');

         input.prop('checked' , false);

       }

     });

   });

 </script>

<script>

    jQuery(document).ready(function() {

        setTimeout(function() {



     var reviewData = $('#review_data').val();

        reviewData = JSON.parse(reviewData);

        if(reviewData.gb_template != "" && reviewData.gb_template != undefined){

        $('input[name="chooseGoal"][value="choose_form_template"]').prop('checked',true);

        }

        var check =$('#wrapped .wizard-step.current');

        var step1Completed = reviewData.is_step_completed;

        if(step1Completed == 0){

            $("#m-selected-step").val(1);

        }else{

            var step2Completed = true;

            if(reviewData.milestones.length > 0){

                $.each(reviewData.milestones,function(key,obj){

                    if(obj.is_step_completed == 0){

                        step2Completed = false;

                        $("#m-selected-step").val(2);

                        $('.step').each(function(){

                        if($('.milestoneData').data('next') == 0){

                            $('.forward').trigger('click');

                        }else{

                           

                            return false;

                        }



                        });

                        // $('#Build_Milestone').trigger('click');

                        return false;

                    }

                });

            }else{

                step2Completed = false;

                $("#m-selected-step").val(2);

                $('.step').each(function(){

                    if($('.milestoneData').data('next') == 0){

                        $('.forward').trigger('click');

                    }else{

                        return false;

                    }



                });

            }

            if(step2Completed){

                var step3Completed = true;

                if(reviewData.taskhabit.length > 0){

                $.each(reviewData.taskhabit,function(key,obj){

                    if(obj.is_step_completed == 0){

                        step3Completed = false;

                        $("#m-selected-step").val(3);

                        $('.step').each(function(){

                                if($('.newHabitForm').data('next') == 0){

                                    $('.forward').trigger('click');

                                }else{

                                   if(reviewData.gb_template != '' && reviewData.gb_template != undefined){

                                       $('.habit-edit').each(function(){

                                          if($(this).data('habit-id') == obj.id){

                                             $(this).trigger('click');

                                          }

                                       })

                                    }

                                    return false;

                                }



                                });

                            

                        return false;

                    }

                });

               }else{

                  step3Completed = false;

                  $("#m-selected-step").val(3);

                   $('.step').each(function(){

                  if($('.newHabitForm').data('next') == 0){

                     $('.forward').trigger('click');

                  }else{

                     

                     return false;

                  }



                  });

               }

                if(step3Completed){

                    var step4Completed = true;

                    if(reviewData.taskdata.length > 0){

                    $.each(reviewData.taskdata,function(key,obj){

                        if(obj.is_step_completed == 0){

                            step4Completed = false;

                            $("#m-selected-step").val(4);

                            $('.step').each(function(){

                                if($('.showNext').data('next') == 0){

                                    $('.forward').trigger('click');

                                }else{

                                 if(reviewData.gb_template != '' && reviewData.gb_template != undefined){

                                    $('.task-edit').each(function(){

                                       if($(this).data('task-id') == obj.id){

                                             $(this).trigger('click');

                                       }

                                  })

                                    }

                                    return false;

                                }



                                });

                            return false;

                        }

                    });

                  }else{

                     step4Completed = false;

                     $("#m-selected-step").val(4);

                            $('.step').each(function(){

                                if($('.newTaskData').data('next') == 0){

                                    $('.forward').trigger('click');

                                }else{

                                    return false;

                                }



                                });

                  }

                    if(step4Completed){ 

                        if(reviewData.final_submitted == 0){

                            $("#m-selected-step").val(5);

                            $('.step').each(function(){

                                if($('.reviewGoal').data('next') == 0){

                                    $('.forward').trigger('click');

                                }else{

                                    return false;

                                }



                                });

                        }else{

                           $('.editForm').each(function(){

                              if($(this).hasClass('formStepfirst')){

                                 $(this).addClass('activeFormStep');

                              }

                              $(this).removeClass('disabled');

                              $(this).addClass('selected');

                              $(this).attr('isdone','1');

                           })

                        }

                    }

                }

            }

        }

    $("#m-selected-step").trigger('change');

    }, 500);

});

    </script>

     <script>

      $(document).ready(function() {

        $('.prefTrainSlot').click(function() {

          var $this = $(this),

          input = $(this).find('input');                  

          var attr = input.attr('data-is-checked');                

          if (attr == 'no') {

            $this.addClass('active');

            $this.attr('data-status', 'active');

            input.attr('data-is-checked', 'yes');

            input.prop('checked', true);

          } else {

            $this.removeClass('active');

            $this.attr('data-status', 'inactive');                    

            input.attr('data-is-checked', 'no');

            input.prop('checked' , false);

          }

        });

      });

    </script>



 <script type="text/javascript">

  $("#gb_change_life_reason_other").hide();

$("#other").click(function() {

    if($(this).is(":checked")) {

        $("#gb_change_life_reason_other").show(300);

    } else {

        $("#gb_change_life_reason_other").hide(200);

    }

});



</script>
<script>
   $(document).ready(function() {
   var div = $('input[name="template"]:checked').closest('div');

   img = div.find('img').attr('src');
    $('.content-left-wrapper').find('img').attr('src',img);
    console.log( $('.content-left-wrapper').find('img'));
   });
   </script>
@endsection

@section('required-script')
<script>
@stopw(300);

    } else {

        $("#gb_change_life_reason_other").hide(200);

    }

});



</script>
<script>
   $(document).ready(function() {
   var div = $('input[name="template"]:checked').closest('div');
   img = div.find('img').attr('src');
    $('.content-left-wrapper').find('img').attr('src',img);
    console.log( $('.content-left-wrapper').find('img'));
   });
   </script>
@endsection

@section('required-script')

@stop