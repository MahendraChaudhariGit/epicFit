@extends('Result.goal-buddy.main_goal')
@section('required-styles')
{!! Html::style('result/css/autocomplete.css') !!}
{!! Html::style('result/plugins/tipped-tooltip/css/tipped/tipped.css') !!}
{!! Html::style('result/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}
{!! Html::style('result/parq-theme/goal.css') !!}
<style type="text/css">
 textarea.form-control{
  min-height: 100px;
  resize: vertical;
  overflow:auto;
}
/*.outborder{
    border: 2px solid #f94211;
    padding: 0px 10px 10px 10px;
}*/
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
  #client-datatable tr td:nth-child(3) {
    display: none
}
  #client-datatable-task tr td:nth-child(2),  #client-datatable-task tr td:nth-child(3) {
    display: none
}
/*.picCropModel .modal-dialog{
  width: 90%;
}*/
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
      padding: 2px !important;
      min-width: 78px;
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
.bootstrap-select.open.show .dropdown-menu.open.show{
  display: block !important;
}
.bootstrap-select.open .dropdown-menu.open{
  display: none !important;
}
</style>
@stop
@section('content')


<span class="inmotion-total-slides hidden-xs question-section">QUESTIONS<br>
  <span class="inmotion-ts-active-num question-step">01</span>
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
       <img src="{{asset('assets/images/logo-epic.png')}}" alt="" class="img-fluid logo-img"> 
       <div class="content-left-wrapper">
          <img src="{{asset('assets/images/BM-slimming_1.png')}}" alt="" class="img-fluid slide-img"> 
     </div>
         <img id="pot" src="{{asset('assets/images/h1-slider-img-1.png')}}" alt="" class="img-fluid">
      </div>

      <div class="col-xl-6 col-lg-5 col-md-5 col-xs-12 content-right" id="start">        
                   
         <div class="wizard_container" id="wizard_container">
         

            <div id="top-wizard">
                <h2 class="steps-name">DEFINE YOUR GOAL</h2>
            </div>
            <input type="hidden" id="goal_type">
            <input type="hidden" id="goal_due_date">
            <input type="hidden" id="goal_start_date">
            <input type="hidden" id="goal_buddy_id">
            <input type="hidden" id="goal_template_id">
            
            <input type="hidden" id="current_habit_step" value="1">
            <input type="hidden" id="total_habit_step" value="1">
            <input type="hidden" id="current_task_step" value="1">
            <input type="hidden" id="total_task_step" value="1">
            
            <input type="hidden" id="add_new_task" value="0">
            <input type="hidden" id="edit_task" value="0">

            <input type="hidden" id="edit_goal" value="0">


            <input type="hidden" id="last_form_edit_habit" value="0">
            <input type="hidden" id="last_form_edit_task" value="0">

            <input type="hidden" id="section_completed" value="0">

            <!-- <input type="hidden" id="choose_immediate_priority" value="0"> -->
            <form id="wrapped" class="goal_form" method="post" enctype="multipart/form-data">
              
            </form>
                  <!-- /middle-wizard -->
      <div id="bottom-wizard">
                    
         <span class="qodef-grid-line-center"><span class="inmotion-total-slides">
            


            <div class="d-flex">
              
              <a href="#" class="step-back formStepfirst"><span class="prev-name">DEFINE YOUR GOAL</span></a>&nbsp;&nbsp;
              <a href="#" class="arrow step-back">&#8672;</a>               
              
              <div class="current-section">DEFINE YOUR GOAL</div>
              
              <a href="#" class="arrow step-forward">&#8674;</a>&nbsp;&nbsp;
              <a  href="#" class="step-forward formStepSecond"><span class="next-name">ESTABLISH YOUR MILE STONES</span></a>
            
            </div>
         


            <span class="inmotion-ts-active-num section-step">01</span>
            <span class="inmotion-ts-active-separator">/</span>
            <span class="inmotion-ts-active-all all-section-step">05</span>
            </span>
            <span class="inmotion-total-slides visible-xs question-s question-section">QUESTIONS<br>
            <span class="inmotion-ts-active-num question-step">5</span>
            <span class="inmotion-ts-active-separator">/</span>
            <span class="inmotion-ts-active-all">20</span>
            </span>
            <span class="qodef-grid-line-inner" data-parallax="{&quot;y&quot;:750, &quot;smoothness&quot;:25}" style="transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); -webkit-transform:translate3d(0px, 0.024px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1); "></span>
        </span>
            <div class="row">
            <div class="col-sm-5 col-xs-5">
               <button type="button" name="backward" class="backward">Prev</button>
             </div>
            <div class="col-sm-7 col-xs-7">
               <div class="general-notes-button note_area">

          <a href="#" class="btn btn-primary add-note" data-toggle="modal" data-target="#general-notes-popup"><i class="fa fa-plus"></i>  Notes</a>


        </div>
            <!-- Scroll Top for each step -->
            <a href="#wizard_container" id="scroll_top"></a>

            <button type="button" name="forward" class="forward data-save nextData">Next</button>
            <button type="button" class="submit submit-step final-step-submit">Submit</button>
           </div>
            </div>
            <div class="back_to_goal_list backto_dashboard">
                <button type="button" name="" class="btn">Back to Goal List</button>
            </div>
          </div>
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
     <p> Why is this an intrinsic <b>EPIC</b> goal?, how long have you wanted to achieve this goal for?</p> <p>And what are the positive effects on all aspects of your life as a result of achieving it?</p><p><b style='color:#f64c1e;'>Example includes: Feeling Confident, Looking and feeling stronger.</p>
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
Does successfully creating this habit contribute to hitting your milestones and achieving your overall <b>EPIC</b> goal? </p><p>Yes select individual or Yes select all.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
--}}

<div id="general-notes-popup" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">
      
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
         <h4 style='color: #f94211'><b>Enter notes</b></h4>
          <textarea class="form-control" id="goal_notes" name="" placeholder="General Notes"></textarea>
      </div>
      <div class="modal-footer">
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
 <!--template modal-->

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
            <label class="container_radio version_2"><img src="{{asset('result/images/weightmanagement.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="1" data-id='1'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/drop_a_size.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="2" data-id='2'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/eat.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="3" data-id='3'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/improve_h.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="4" data-id='4'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/reduce_stress.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="5" data-id='5'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/improve_my_sleep.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="6" data-id='6'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/improve_health.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="7" data-id='7'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/injury.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="8" data-id='8'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/increase_activity.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="9" data-id='9'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/balance.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="10" data-id='10'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/health.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="11" data-id='11'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/improve_posture.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="12" data-id='12'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/time_man.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="13" data-id='13'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/improve_per.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="14" data-id='14'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/improve_c.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="15" data-id='15'>
            <span class="checkmark"></span>
            </label>
         </div>
            <div class="form-group col-md-3 col-xs-4">
            <label class="container_radio version_2"><img src="{{asset('result/images/become_proactive.jpg')}}">
            <input type="radio" name="template" data-from="popup" value="16" data-id='16'>
            <span class="checkmark"></span>
            </label>
         </div>
      </div>
      </div>
      <div class="modal-footer">
         <a type="button" class="btn btn-default choose-immediate-priority" disabled="true" style="background:black;color: white;">Continue with above selected goal</a>
       
        <button type="button" class="btn btn-default same-template" data-dismiss="modal">No, I am ok with previously selected goal</button>
         <a type="button" class="btn-default btn choose-create-new-goal popup-create-new-goal">Create New Goal</a>
      </div>
    </div>

  </div>
</div>
 <!-- {!! Html::script('result/js/autocomplete.js?v='.time()) !!} -->
<script type="text/javascript">

    $(document).ready(function() {
       $(window).scroll(function() {
     $('.bootstrap-select').removeClass('open');
    });
    });

    $(document).on('click','.goal-step',function(){
         goal_type = $("#goal_type").val();
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


</script>
@endsection
@section('required-script')
@stop