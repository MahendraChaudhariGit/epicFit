@extends('blank')

@section('plugin-css')
{!! Html::style('assets/css/plugins.css') !!}
<!-- start: Bootstrap datepicker --> 
{!! Html::style('assets/plugins/datepicker/css/datepicker.css') !!}
<!-- end: Bootstrap datepicker --> 

<!-- Start: NEW timepicker css -->  
{!! Html::style('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
<!-- End: NEW timepicker css -->

<!-- Start: NEW datetimepicker css -->
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') !!}
{!! Html::style('assets/plugins/bootstrap-material-datetimepicker/css/custom-css-style.css') !!}
<!-- End: NEW datetimepicker css -->

<!-- Start: Activities planner -->
{!! HTML::style('assets/plugins/fitness-planner/custom/style.css?v='.time()) !!} 
{!! Html::style('assets/plugins/fitness-planner/css/api.css?v='.time()) !!}
{!! Html::style('result/css/owl.carousel.css') !!}
<!-- End: Activities planner -->
    <style type="text/css">
@media(max-width: 767px){
#ui-datepicker-div{
    left: 5px !important;
    width: 97% !important;
}
.ui-datepicker-multi-2 .ui-datepicker-group {
    width: 49%;
    font-size: 11px;
}
.dropdown-menu.dropdown-light{
    min-width: 91px;

}
.dropdown-menu.dropdown-light li a{
    font-size: 13px;
    padding: 3px 0px;
}
}
</style>
<style>
   /* Float four columns side by side */
   .column {
   float: left;
   width: 25%;
   padding: 10px 10px;
   }

   /* Clear floats after the columns */
   .row:after {
   content: "";
   display: table;
   clear: both;
   }

   /* Responsive columns */
   @media screen and (max-width: 600px) {
   .column {
      width: 100%;
      display: block;
      margin-bottom: 20px;
   }
   .heading-training{
        width: calc(100% - 138px) !important;
   }
   }

   /* Style the counter cards */
   .card {
   box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
   padding: 16px;
   text-align: center;
   background-color: #f1f1f1;
   }
   hr {
      margin-top: 20px;
      margin-bottom: 20px;
      border: 0;
      border-top: 1px solid #253746;
   }
   #lungemodal {
      z-index: 999999 !important;
   }

        .panel-white.exercise .panel-body{
          padding: 0px;

        }
        .accordion h2{
          background: none;
        }
          .showRes{
    margin-bottom: 20px;
  }
  .exeRow{
    margin-bottom: 8px;
  }
</style>
@stop

@section('page-title')
<span data-realtime="firstName">{{ $client->firstname }}</span> <span data-realtime="lastName">{{ $client->lastname }}</span>
@stop

@section('content')
<a class="btn btn-primary" href="{{route('clients.show',[id => $client->id])}}"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i> Back to Profile</a>
<input type="hidden" name="clientPlanId" id="clientPlanId" value="{{$libraryProgram->id}}">
<input type="hidden" name="clientPlanProgramId" id="clientPlanProgramId" value="">
<input type="hidden" name="programView" id="programView" value="modal">
<input type="hidden" name="clientId" value="{{$client->id}}">
<input type="hidden" name="fit_clientId" value="{{$client->id}}">
<input type="hidden" name="fit_gender" value="{{$client->gender}}">
<input type="hidden" name="calendarSettingInput" value='{{ json_encode($calendar_settings) }}'>
<div id="calendarActivity"></div>
@include('ActivityPlan.activityModal')

<div class="btn-group calJumper">
   <a class="btn btn-primary btn-o dropdown-toggle hidden" data-toggle="dropdown" href="#">
      <i class="fa fa-angle-double-left"></i>
   </a>
   <ul role="menu" class="dropdown-menu dropdown-light">
      <li>
         <a href="#" data-jump-amount="1" data-jump-unit="weeks">
            1 week
         </a>
      </li>
      <li>
         <a href="#" data-jump-amount="2" data-jump-unit="weeks">
            2 weeks
         </a>
      </li>
      <li>
         <a href="#" data-jump-amount="3" data-jump-unit="weeks">
            3 weeks
         </a>
      </li>
      <li>
         <a href="#" data-jump-amount="4" data-jump-unit="weeks">
            4 weeks
         </a>
      </li>
      <li>
         <a href="#" data-jump-amount="5" data-jump-unit="weeks">
            5 weeks
         </a>
      </li>
      <li>
         <a href="#" data-jump-amount="6" data-jump-unit="weeks">
            6 weeks
         </a>
      </li>
      <li>
         <a href="#" data-jump-amount="7" data-jump-unit="weeks">
            7 weeks
         </a>
      </li>
      <li>
         <a href="#" data-jump-amount="8" data-jump-unit="weeks">
            8 weeks
         </a>
      </li>
      <li>
         <a href="#" data-jump-amount="6" data-jump-unit="months">
            6 months
         </a>
      </li>
      <li>
         <a href="#" data-jump-amount="1" data-jump-unit="years">
            1 year
         </a>
      </li>
   </ul>
</div>
<!--popup1 RESISTANCE TRAINING BUILDER-->
<!-- The Modal -->
<div class="modal fade Activitycalendar" id="modalpopup1" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title">RESISTANCE TRAINING BUILDER</h4>
           <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
           <div class="row">
              <div class="col-md-6 pos trainingStep">
                 <a class="inactive" data-target-step="programChoose" data-change-flow="true" href="#">
                    <!--data-parent="#accordion211" change-flow id="panel-collapse1" data-toggle="collapse" panel-collapse-->
                    <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/resistance_traning.png')}}" value="" class=""> <!--value="8" -->
                    <!--<p> -->
                    <br>
                    RESISTANCE TRAINING
                    <br/>
                    Select resistance training 
                    <!--</p>-->
                 </a>
              </div>
              <div class="col-md-6 pos trainingStep">
                 <a class="inactive center" href="#">
                    <!--change-flow  data-toggle="collapse" data-parent="#accordion11" id="panel-collapse1" panel-collapse-->
                    <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/cardioa.png')}}" value="" class=""><!--value="9" -->
                    <br>
                    <!--<p >--> 
                    CARDIOVASCULAR ACTIVITY
                    <br/>
                    Select cardiovascular activities
                    <!--</p>-->
                 </a>
              </div>
           </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
           <button type="button" class="btn btn-primary nextStepButton" data-current-step="stepChooseTraining">Next</button>
        </div>
     </div>
  </div>
</div>
<!--popup2 WHAT DO YOU WANT TO DO-->
<!-- The Modal -->
<div class="modal fade Activitycalendar width-100" id="modalpopup2" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title">WHAT DO YOU WANT TO DO</h4>
           <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
           <div class="row">
              <div class="col-md-4 pos chooseProgram">
                 <a class="inactive" data-target-step="activityChoose" data-change-flow="true" href="#">
                    <!--data-parent="#accordion211" data-toggle="collapse" panel-collapse id="program_gen_avt" -->
                    <input type="image" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/program_gen.gif') }}" value="5">
                    <!--<p>-->
                    <br>
                    PROGRAM GENERATOR
                    <br/>
                    Let us design a program for you
                    <!--</p>-->
                 </a>
              </div>
              <div class="col-md-4 pos chooseProgram">
                 <a class="inactive" data-target-step="libraryOption" data-change-flow="true" href="#">
                    <!--data-toggle="collapse" data-parent="#accordion11" href="#collapseOne1" panel-collapse id="program_liv_first" -->
                    <input type="image" class="gif_h_w" value="6" src="{{ asset('assets/plugins/fitness-planner/images/program_lib.gif') }}">
                    <!--<p>-->
                    <br>
                    PROGRAM LIBRARY
                    <br/>
                    Choose an off the shelf pre design
                    <!--</p>-->
                 </a>
              </div>
              <div class="col-md-4 pos chooseProgram">
                 <a class="inactive" data-target-step="designProgram" data-change-flow="true" href="#">
                    <!--data-toggle="collapse" data-parent="#accordion11" panel-collapse id="program_work_first"  -->
                    <input type="image" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/program_design.gif') }}" value="7">
                    <!--<p>-->
                    <br>
                    PROGRAM DESIGNER
                    <br/>
                    Design from scratch
                    <!--</p>-->
                 </a>
              </div>
           </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            <button type="button" class="btn btn-primary nextStepButton" data-current-step="stepChooseProgram">Next</button>
        </div>
     </div>
  </div>
</div>

<!--popup2 WHAT DO YOU WANT TO DO-->
<!-- The Modal -->
<div class="modal fade Activitycalendar" id="libraryOptionModal" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Choose Library Program</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6 pos chooseProgram">
                  <a class="inactive" data-target-step="activityChoose" data-change-flow="true" href="#">
                     <!--data-parent="#accordion211" data-toggle="collapse" panel-collapse id="program_gen_avt" -->
                     <input type="image" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/program_gen.gif') }}" value="6">
                     <!--<p>-->
                     <br>
                     SINGLE PHASE
                     <br/>
                     Let us design a single phase program for you
                     <!--</p>-->
                  </a>
               </div>
               <div class="col-md-6 pos chooseProgram">
                  <a class="inactive" data-target-step="currentAbility" data-change-flow="true" href="#">
                     <!--data-toggle="collapse" data-parent="#accordion11" href="#collapseOne1" panel-collapse id="program_liv_first" -->
                     <input type="image" class="gif_h_w" value="9" src="{{ asset('assets/plugins/fitness-planner/images/program_lib.gif') }}">
                     <!--<p>-->
                     <br>
                     MULTI PHASE
                     <br/>
                     Let us design a Multi phase program for you
                     <!--</p>-->
                  </a>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
             <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
             <button type="button" class="btn btn-primary nextStepButton" data-current-step="libraryOption">Next</button>
         </div>
      </div>
   </div>
 </div>

 <!--popup3 DESIGN A PROGRAM-->
<!-- The Modal -->
<div class="modal fade Activitycalendar width-100" id="modalpopup3" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title">DESIGN A PROGRAM</h4>
           <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
           <div class="row">
              <div class="col-md-12">
                 <p>
                    Design your custom made program using this simple program design tool. Follow the steps below to get started: 
                    <br>
                    Create Program name or choose existing program to edit, Choose the training segments that you require for your routine, Choose the exercise or stretches for each segment, Confirm program length and days you choose to train, Click design and follow your program and achieve EPIC Results.
                 </p>
              </div>
              <div class="item">
                 <!--id="sliderIte-custom" sliderIte fi-plan-program-->
                 <!--<div class="itemHadr" data-name="FixdProramId">
                    <span></span>
                    </div>-->
                 <div>
                    <!--class="itemBoy"-->
                    <div class="pts">
                       <!--cotent_box pg_width-->
                       <!--<div class="left"></div><!-mob_vie-->
                       <div class="program_design m-b-20 col-md-12">
                          <div class="error-msg">Please enter a program name.</div>
                          <form action="javascript:void(0)" onsubmit="FX.createProgram(this)">
                             <label class="prg_name" for="pname">
                             <strong>NEW PROGRAM TITLE:</strong>
                             </label>
                             &nbsp; 
                             <input type="text" name="pname" class="txtbox1" id="pname" />
                             &nbsp;
                             <input type="submit" name="btn" value="Design program" class="btn btn-default btn-sm" data-target-step="trainingSegment" /><!--id="create_pr" create_for_design-->
                          </form>
                       </div>
                       <!--<br>-->
                       <div>
                          <table class="table table-striped table-bordered table-hover m-t-10 my_pt" id="design-program-datatable" width="100%">
                             <thead>
                                <tr>
                                   <th>PROGRAM TITLE</th>
                                   <!--class="w70"-->
                                   <th>DATE CREATED</th>
                                   <th class="hidden-xs">CREATED BY</th>
                                   <th class="center">ACTIONS</th>
                                </tr>
                             </thead>
                             <tbody>
                                <!--<div class=""></div>-->
                             </tbody>
                          </table>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            {{-- <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modalpopup3">Next</button> --}}
        </div>
     </div>
  </div>
</div><!--popup4 DESIGN A PROGRAM-->
<!-- The Modal -->
<div class="modal fade Activitycalendar width-100" id="modalpopup4" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">CREATE TRAINING SEGMENTS</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div>
                     <p> Please choose the training segments that you require in your training routine, each segment relates to the different aspects of training and maintaining a balanced program related to an effective warm-up, cardiovascular, resistance, core, cooling down and stretching </p>
                     <div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="exercises" name="exercises" value="3" type="checkbox" class="choosetrainingSegment">
                           <label for="exercises">Resistance Training</label>
                        </div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="cardio" name="cardio" value="2" type="checkbox" class="choosetrainingSegment">
                           <label for="cardio">Cardiovascular Training</label>
                        </div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="ploymetric-metcon" name="ploymetric-metcon" value="9" type="checkbox" class="choosetrainingSegment">
                           <label for="ploymetric-metcon">Ploymetric / MetCon</label>
                        </div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="stretch" name="stretch" value="7" type="checkbox" class="choosetrainingSegment">
                           <label for="stretch">Recovery Routine / Stretching</label>
                        </div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="olympic-lifts" name="olympic-lifts" value="10" type="checkbox" class="choosetrainingSegment">
                           <label for="olympic-lifts">Olympic Lifts / Power Lifting</label>
                        </div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="skill" name="skill" value="4" type="checkbox" class="choosetrainingSegment">
                           <label for="skill">Skill Training</label>
                        </div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="warm_up" name="warm up" value="1" type="checkbox" class="choosetrainingSegment">
                           <label for="warm_up">Warm-Up</label>
                        </div>
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="abdominal_training" name="abdominal_training" value="5" type="checkbox" class="choosetrainingSegment">
                           <label for="abdominal_training">Abdominal Training</label>
                        </div>
                        <!-- <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="core" name="core" value="5" type="checkbox" class="choosetrainingSegment">
                           <label for="core">Core</label>
                           </div> -->
                        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="cool_down" name="cool down" value="6" type="checkbox" class="choosetrainingSegment">
                           <label for="cool_down">Cool-Down</label>
                        </div>
                        <!--  <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
                           <input id="stretching" name="stretching" value="8" type="checkbox" class="choosetrainingSegment">
                           <label for="stretching">Stretching</label>
                           </div> -->
                     </div>
                  </div>
                  <div class="m-t-20">
                     <div>
                        <h4 class="m-b-5">Add exercises to training segments</h4>
                        <p>Please choose the exercises you require in the training segments in your training routine, each segment relates to the different aspects of training and maintaining a balanced program related to an effective warm-up, cardiovascular, resistance, core, cooling down and stretching</p>
                     </div>
                     <div class="add-exercises">
                        <div class="row hidden-xs hidden-sm">
                           <div class="col-md-1">
                              <label>Filters</label> <i class="fa fa-bars mb-1 filter" id="filter"></i> 
                           </div>
                           <div class="col-md-8">
                              <label>Search</label>
                              <input placeholder="Type &amp; wait to filter" type="text" id="keySearchExercise" class="form-control mb-1"> 
                           </div>
                           <div class="col-md-3 p-0 favShow" style="display: none;">
                              <label>Search favourite</label>
                              <button type="button" class="form-control mb-1 search" id="favSearch"> <i class="fa fa-heart-o"></i> </button>
                           </div>
                        </div>
                        <div class="row exeTypeRow hidden-xs hidden-sm">
                           <div class="col-md-12">
                              <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                 <input type="radio" name="exerciseType" id="ExerciseType1" required value="1" data-realtime="ExerciseType1" class="onchange-set-neutral">
                                 <label for="ExerciseType1">
                                 Exercise
                                 </label>
                              </div>
                              <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                 <input type="radio" name="exerciseType" id="ExerciseType2" required value="2" data-realtime="ExerciseType1" class="onchange-set-neutral">
                                 <label for="ExerciseType2">
                                 Video Exercise
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="row position-relative">
                           <div id="mySidepanel" class="filter-detail-sidepanel col-md-3 style-4">
                              <div class="filter-section">
                                 <div class="form-group">
                                    <label>Muscle group</label>
                                    <select name="bodypart" class="form-control searchExercise" id ="muscle_group" data-dropup-auto="false">
                                       <option value="" data-part=""> -- Select -- </option>
                                       <option value="1" data-part="abdominals">Abdominals</option>
                                       <option value="2" data-part="adductors">Adductors</option>
                                       <option value="3" data-part="back-low">Back Low</option>
                                       <option value="4" data-part="back-mid">Back Mid</option>
                                       <option value="5" data-part="back-upper">Back Upper</option>
                                       <option value="6" data-part="biceps">Biceps</option>
                                       <option value="7" data-part="calves">Calves</option>
                                       <option value="8" data-part="chest">Chest</option>
                                       <option value="9" data-part="forearms">Forearms</option>
                                       <option value="10" data-part="gluteus">Glutes</option>
                                       <option value="11" data-part="hamstrings">Hamstrings</option>
                                       <option value="12" data-part="latissimus-dorsi">Lats</option>
                                       <option value="13" data-part="neck">Neck</option>
                                       <option value="14" data-part="quadriceps">Quads</option>
                                       <option value="15" data-part="shoulders">Shoulders</option>
                                       <option value="16" data-part="trapezius">Traps</option>
                                       <option value="17" data-part="triceps">Triceps</option>
                                    <select>
                                 </div>
                                 <hr>
                                 <div class="form-group">
                                 <label>Ability</label>
                                 {!! Form::select('ability', isset($exerciseData)?$exerciseData['abilitys']:[], null, ['class' => 'form-control  searchExercise','id'=>'ability']) !!} 
                                 </div>
                                 <hr>
                                 <div class="form-group">
                                 <label>Equipment</label>
                                 <select name="equipment" class="form-control searchExercise" id="equipment" title="--Select--" data-dropup-auto="false">
                                 <option value="" data-part=""> -- Select -- </option>
                                 @foreach ($exerciseData['equipments'] as $key => $item)
                                 <option value="{{$key}}">{{$item}}</option>
                                 @endforeach
                                 </select>
                                 {{-- {!! Form::select('equipment', isset($exerciseData)?$exerciseData['equipments']:[], null, ['class' => 'form-control searchExercise','id' => 'equipment','title' => '--Select--']) !!} --}}
                                 </div>
                                 <hr>
                                 <div class="form-group">
                                    <label>Exercise Type</label>
                                    {!! Form::select('exerciseTypeID', isset($exerciseData)?$exerciseData['exetype']:[], null, ['class' => 'form-control searchExercise', 'id'=>'category']) !!}  
                                 </div>
                                 <hr>
                                 <div class="form-group">
                                    <label>Movement Type</label>
                                    <select class="form-control  searchExercise" id="movement_type" data-dropup-auto="false">
                                       <!--dd id="pt-bodypart"-->
                                       <option value=""> -- Select -- </option>
                                       <option value="1"> Compound </option>
                                       <option value="2"> Isolated </option>
                                       <option value="3"> Isometric </option>
                                    </select>
                                 </div>
                                 <hr>
                                 <div class="form-group">
                                    <label>Movement Patterns</label>
                                    <select name="movement_pattern" class="form-control searchExercise" id="movement_pattern" title="--Select--" data-dropup-auto="false">
                                       <option value="" data-part=""> -- Select -- </option>
                                       @foreach ($exerciseData['movepattern'] as $key => $item)
                                       <option value="{{$key}}">{{$item}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                                 <div class="form-group video-filter">
                                    <label class="strong">Workout Type</label>
                                    <select class="form-control" name="filter" id="filterVideoDesktop" data-dropup-auto="false">
                                       @foreach($abWorkouts as $key => $value)
                                       <option value="{{$key}}">{{$value}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                                 <hr>
                              </div>
                           </div>
                           <div class="col-md-7 video-thumbnail exerciseVideoList hidden-xs style-4 hidden-sm" data-plan-type="1">
                           </div>
                           <div class="col-md-5 max-height500 style-4">
                              <div class="program_design m-b-10 hidden-xs hidden-sm">
                                 <div class="error-msg">Please enter a program name.</div>
                                 <form id="updateProgramName">
                                    <label class="prg_name" for="pname"> <strong>NEW PROGRAM TITLE:</strong> </label> &nbsp;
                                    <input type="text" name="programname" class="txtbox1 w-auto" id="pname"> &nbsp;
                                    <button type="button" id="updateProgramNameSubmit" class="btn btn-default btn-sm">Edit</button>
                                    <!--id="create_pr" create_for_design-->
                                 </form>
                              </div>
                              <ul class="panel-group accordion sortable" id="choosedTrainingsAccordion">
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_warm_up">
                                          <i class="icon-arrow"></i>
                                          WARM-UP
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='1'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_warm_up" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" data-work-out="warm_up" data-work-name="warm_up">
                                          <div class="row">
                                             <a href="#" class="btn btn-sm btn-default tooltips delAllExercise pull-left" data-placement="top" data-original-title="Delete all exercises">
                                             <i class="fa fa-trash-o link-btn"></i>
                                             </a>
                                             <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          </div>
                                          <div class="row choosedExercRow sortExe" data-work-out="warm_up" data-work-name="warm_up"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_cardio">
                                          <i class="icon-arrow"></i>
                                          CARDIOVASCULAR TRAINING
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='2'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_cardio" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" data-work-out="cardio" data-work-name="cardio">
                                          <div class="row">
                                             <a href="#" class="btn btn-sm btn-default tooltips delAllExercise pull-left" data-placement="top" data-original-title="Delete all exercises">
                                             <i class="fa fa-trash-o link-btn"></i>
                                             </a>
                                             <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          </div>
                                          <div class="row choosedExercRow sortExe" data-work-out="cardio" data-work-name="cardio"> </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_exercises">
                                          <i class="icon-arrow"></i>
                                          RESISTANCE TRAINING
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='3'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_exercises" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" data-work-out="exercises" data-work-name="resist">
                                          <div class="row">
                                             <a href="#" class="btn btn-sm btn-default tooltips delAllExercise pull-left" data-placement="top" data-original-title="Delete all exercises">
                                             <i class="fa fa-trash-o link-btn"></i>
                                             </a>
                                             <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          </div>
                                          <div class="row choosedExercRow sortExe" data-work-out="exercises" data-work-name="resist"> </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_skill">
                                          <i class="icon-arrow"></i>
                                          SKILL TRAINING
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='4'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_skill" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" 
                                          data-work-out="skill" data-work-name="skill">
                                          <div class="row">
                                             <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          </div>
                                          <div class="row choosedExercRow sortExe" data-work-out="skill" data-work-name="skill"> </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_abdominal_training">
                                          <i class="icon-arrow"></i>
                                          ABDOMINAL TRAINING
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='5'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_abdominal_training" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" data-work-out="abdominal_training" data-work-name="abdominal_training">
                                          <a href="#" class="btn btn-sm btn-default tooltips delAllExercise pull-left" data-placement="top" data-original-title="Delete all exercises">
                                          <i class="fa fa-trash-o link-btn"></i>
                                          </a>
                                          <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          <div class="row choosedExercRow sortExe" data-work-out="abdominal_training" data-work-name="abdominal_training"> </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_cool_down">
                                          <i class="icon-arrow"></i>
                                          COOL-DOWN
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='6'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_cool_down" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" data-work-out="cool_down" data-work-name="cool_down">
                                          <div class="row">
                                             <a href="#" class="btn btn-sm btn-default tooltips delAllExercise pull-left" data-placement="top" data-original-title="Delete all exercises">
                                             <i class="fa fa-trash-o link-btn"></i>
                                             </a>
                                             <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          </div>
                                          <div class="row choosedExercRow sortExe" data-work-out="cool_down" data-work-name="cool_down"> </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_stretch">
                                          <i class="icon-arrow"></i>
                                          RECOVERY ROUTINE/STRETCHING
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='7'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_stretch" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" data-work-out="stretch" data-work-name="stretch">
                                          <div class="row">
                                             <a href="#" class="btn btn-sm btn-default tooltips delAllExercise pull-left" data-placement="top" data-original-title="Delete all exercises">
                                             <i class="fa fa-trash-o link-btn"></i>
                                             </a>
                                             <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          </div>
                                          <div class="row choosedExercRow sortExe" data-work-out="stretch" data-work-name="stretch"> </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_stretching">
                                          <i class="icon-arrow"></i>
                                          STRETCHING
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='8'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_stretching" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" data-work-out="stretching" data-work-name="stretching">
                                          <div class="row">
                                             <a href="#" class="btn btn-sm btn-default tooltips delAllExercise pull-left" data-placement="top" data-original-title="Delete all exercises">
                                             <i class="fa fa-trash-o link-btn"></i>
                                             </a>
                                             <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          </div>
                                          <div class="row choosedExercRow sortExe" data-work-out="stretching" data-work-name="stretching"> </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_ploymetric-metcon">
                                          <i class="icon-arrow"></i>
                                          Ploymetric/MetCon
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='9'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_ploymetric-metcon" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" data-work-out="ploymetric-metcon" data-work-name="ploymetric-metcon">
                                          <div class="row">
                                             <a href="#" class="btn btn-sm btn-default tooltips delAllExercise pull-left" data-placement="top" data-original-title="Delete all exercises">
                                             <i class="fa fa-trash-o link-btn"></i>
                                             </a>
                                             <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          </div>
                                          <div class="row choosedExercRow sortExe" data-work-out="ploymetric-metcon" data-work-name="ploymetric-metcon"> </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="panel panel-white" style="display:none;">
                                    <div class="panel-heading">
                                       <h5 class="panel-title">
                                          <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_olympic-lifts">
                                          <i class="icon-arrow"></i>
                                          Olympic Lifts / Power Lifting
                                          <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='10'>
                                          Add exercise
                                          </button>
                                          </a>
                                       </h5>
                                    </div>
                                    <div id="accord_olympic-lifts" class="panel-collapse collapse">
                                       <div class="panel-body" ondrop="drop(event,this)" ondragover="allowDrop(event)" data-work-out="olympic-lifts" data-work-name="olympic-lifts">
                                          <div class="row">
                                             <a href="#" class="btn btn-sm btn-default tooltips delAllExercise pull-left" data-placement="top" data-original-title="Delete all exercises">
                                             <i class="fa fa-trash-o link-btn"></i>
                                             </a>
                                             <button class="btn btn-warning showRes float-right"><i class="fa fa-hand-stop-o"></i>&nbsp;&nbsp;ADD REST </button>
                                          </div>
                                          <div class="row choosedExercRow sortExe" data-work-out="olympic-lifts" data-work-name="olympic-lifts"> </div>
                                       </div>
                                    </div>
                                 </div>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="m-t-20 clearfix">
                     <!--text-right -->
                     <h4 class="pull-left" id="proExTime">Total Estimated Time: <span id="programTotalTime">0</span> minutes <span id="programTotalTimeSec">0</span> seconds</h4>
                     <h4 class="pull-left" style="display:none" id="proVidTime">Total Estimated Video Time: <span id="programTotalVideoTime">0</span> minutes <span id="programTotalVideoTimeSec">0</span> seconds</h4>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            <button type="button" class="btn btn-primary nextStepButton" data-current-step="trainingSegment" id="trainingSegmentSubmit">Next</button>
         </div>
      </div>
   </div>
</div>
<!--popu5 DESIGN A PROGRAM-->
<!-- The Modal -->
<div class="modal fade Activitycalendar width-100" id="modalpopup5" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">PLAN MY PROGRAM</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div class="panel-body item_class">
                     <!-- data-name="selct_program"-->
                     <div id="traingPlannerMain" class="plannerContainer fit-ui">
                        <div>
                           <!--id="weekSelection"-->
                           <div class="itemHeader">
                              <!--data-name="Weeks"-->
                              <span>
                                 <h4>How many weeks do you want to do this plan for?</h4>
                              </span>
                           </div>
                           <div class="item-class">
                              <!--data-name="DaysOfWeek"-->
                              <div class="itemBody">
                                 <div id="weekSlider-crm"></div>
                              </div>
                           </div>
                        </div>
                        <div id="timeSelection" class="m-t-20">
                           <div class="itemHeader">
                              <!--style="margin-top:20px" data-name="TimePerWeek" -->
                              <span>
                                 <h4>How long can you work out each week?</h4>
                              </span>
                           </div>
                           <div class="itemBody m-t-20">
                              <div id="timeSlider-crm"></div>
                           </div>
                        </div>
                        <div class="m-t-20 form-group" id="daySelect">
                           <div class="select_radio_btn">
                              <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                 <input type="radio" name="dayOption" id="Choosedays" required="" value="1" data-realtime="Choosedays" checked="checked">
                                 <label for="Choosedays">
                                 Choose days
                                 </label>
                              </div>
                              <div class="radio clip-radio radio-primary radio-inline m-b-0">
                                 <input type="radio" name="dayOption" id="daysInWeek" required="" value="2" data-realtime="daysInWeek">
                                 <label for="daysInWeek">
                                 Choose days in a week
                                 </label>
                              </div>
                              <div class="radio-inline" style="margin-top: 8px !important">
                                 <label>Start Date : &nbsp;</label> 
                                 <input type="date" name="startDate" id="startDate" min="{{ date("yy-m-d") }}">
                              </div>
                              <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
                                 <input type="checkbox" name="letClientSelect" id="letClientSelect" value="1" class="onchange-set-neutral" />
                                 <label for="letClientSelect">Let client select</label>
                              </div>
                           </div>
                           <div id="showWeekday">
                              <div class="itemHeader">
                                 <!--data-name="DaysOfWeek"-->
                                 <span>
                                    <h4>Please choose <span id="daySelectionTextCRM"></span> days to work out</h4>
                                    <!--id="daySelection"-->
                                 </span>
                              </div>
                              <div class="itemBody no-error-labels" id="days-crm">
                                 <!--id="weekDays" -->
                                 <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
                                    <input type="checkbox" name="weekDay" id="mon-planner" value="1" class="onchange-set-neutral" />
                                    <label for="mon-planner">Mon</label>
                                 </div>
                                 <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
                                    <input type="checkbox" name="weekDay" id="tue-planner" value="1" class="onchange-set-neutral" autocomplete="off"/>
                                    <label for="tue-planner">Tue</label>
                                 </div>
                                 <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
                                    <input type="checkbox" name="weekDay" id="wed-planner" value="1" class="onchange-set-neutral" />
                                    <label for="wed-planner">Wed</label>
                                 </div>
                                 <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
                                    <input type="checkbox" name="weekDay" id="thu-planner" value="1" class="onchange-set-neutral" />
                                    <label for="thu-planner">Thu</label>
                                 </div>
                                 <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
                                    <input type="checkbox" name="weekDay" id="fri-planner" value="1" class="onchange-set-neutral" />
                                    <label for="fri-planner">Fri</label>
                                 </div>
                                 <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
                                    <input type="checkbox" name="weekDay" id="sat-planner" value="1" class="onchange-set-neutral" />
                                    <label for="sat-planner">Sat</label>
                                 </div>
                                 <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
                                    <input type="checkbox" name="weekDay" id="sun-planner" value="1" class="onchange-set-neutral" />
                                    <label for="sun-planner">Sun</label>
                                 </div>
                              </div>
                           </div>
                           <div id="selectOneday" style="display: none;">
                              <div class="itemHeader">
                                 <h4>Please choose days in a week</h4>
                              </div>
                              <div class="form-group">
                                 <div class="row">
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                       <select class="form-control" name="daysInWeek" title="--Select--">
                                          <option value="">--Select--</option>
                                          <option value="1">1</option>
                                          <option value="2">2</option>
                                          <option value="3">3</option>
                                          <option value="4">4</option>
                                          <option value="5">5</option>
                                          <option value="6">6</option>
                                          <option value="7">7</option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <span class="help-block m-t-0"></span> 
                        </div>
                        <div class="form-group">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            <button type="button" class="btn btn-primary nextStepButton" data-current-step="planMyProgram" data-target-step="planPreview">Next</button>
         </div>
      </div>
   </div>
</div>
<!--popu6  PLAN PREVIEW-->
<!-- The Modal -->
<div class="modal fade Activitycalendar width-100" id="modalpopup6" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title"> PLAN PREVIEW</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div class="panel-body">
                     <div class="row p-l-40">
                        <h3>Your Personal Plan</h3>
                     </div>
                     <div class="row p-l-40" id="plan-preview-area">
                        <!-- Inject data throught ajax (fitness-planner rendorPlanPreview() function)-->
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            <button type="button" class="btn btn-primary" id="savePlan">Create Training Program </button>
         </div>
      </div>
   </div>
</div>

<!--WHICH ACTIVITY DO YOU WANT-->
<div class="modal fade Activitycalendar width-100" id="activity-popup" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">WHICH ACTIVITY DO YOU WANT</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-group item_class" data-name="Plan">
                            <!--id="accordion211"-->
                            <div class="row">
                                <div class="col-md-3 pos">
                                    <a class="inactive open-step" data-target-step="activityHabits" href="#">
                                        <!--data-parent="#accordion211" data-toggle="collapse" panel-collapse id="program_gen_avt" -->
                                        <input type="image" class="gif_h_w" value="1" src="{{ asset('assets/plugins/fitness-planner/images/inc_strength.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        INCREASE STRENGTH
                                    </a>
                                </div>
                                <div class="col-md-3 pos">
                                    <a class="inactive open-step" data-target-step="activityHabits" href="#">
                                        <!--data-toggle="collapse" data-parent="#accordion11" href="#collapseOne1" panel-collapse id="program_liv_first" -->
                                        <input type="image" class="gif_h_w" value="2" src="{{ asset('assets/plugins/fitness-planner/images/weight_loss.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        WEIGHT LOSS / TONE
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-3 pos">
                                    <a class="inactive open-step" data-target-step="activityHabits" href="#">
                                        <!--data-toggle="collapse" data-parent="#accordion11" panel-collapse id="program_work_first"  -->
                                        <input type="image" class="gif_h_w" src="{{  asset('assets/plugins/fitness-planner/images/general_health.gif')}}" value="3" class="mw-100p" />
                                        <!--<p>-->
                                        <br />
                                        GENERAL HEALTH
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
               <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="">Next</button>
            </div>
        </div>
    </div>
</div>
<!--WHICH ACTIVITY DO YOU WANT-->

<!-- ACTIVITY HABITS-->
<div class="modal fade Activitycalendar width-100" id="activity-habits" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">ACTIVITY HABITS</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4>CURRENT PHYSICAL ACTIVITY HABITS</h4>
                        <div class="item_class" data-name="Habit">
                            <div class="row act">
                                <div class="col-md-2 pos">
                                    <a href="#" class="inactive">
                                        <!--id="no_exercise" -->
                                        <input type="image" class="gif_h_w" value="3" src="{{ asset('assets/plugins/fitness-planner/images/no_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        NO EXERCISE
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <!--id="inactive" -->
                                        <input type="image" class="gif_h_w" value="2" src="{{ asset('assets/plugins/fitness-planner/images/moderate_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        INFREQUENT EXERCISE
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="1" class="imga gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/high_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        REGULAR EXERCISE
                                        <!--</p>-->
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <h4>PREVIOUS PHYSICAL ACTIVITY HABITS</h4>
                        <div class="item_class" data-name="Experience">
                            <div class="row act">
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="1" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/no_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        A LITTLE
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="2" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/low_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        SOME
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="3" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/moderate_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        ALOT
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="4" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/freq_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        INTERMEDIATE
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="5" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/high_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        A TON
                                        <!--</p>-->
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <h4>WANNA BE PHYSICAL ACTIVITY HABITS</h4>
                        <div class="item_class" data-name="Intensity">
                            <div class="row act">
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="1" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/no_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        A LITTLE
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="2" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/low_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        SOME
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="3" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/moderate_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        ALOT
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="4" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/freq_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        INTERMEDIATE
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" value="5" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/high_exercise.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        A TON
                                        <!--</p>-->
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <h4>CURRENT INTENSITY OF PHYSICAL ACTIVITY</h4>
                        <div class="item-class" data-name="temp">
                            <!--data-name="Intensity"-->
                            <div class="row act">
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/sedentary.gif')}}" value="1" />
                                        <!--<p>-->
                                        <br />
                                        SENDATORY
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/light.png')}}" value="2" />
                                        <!--<p>-->
                                        <br />
                                        LIGHT
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/light.png')}}" value="3" />
                                        <!--<p>-->
                                        <br />
                                        MODERATE
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/light.png')}}" value="4" />
                                        <!--<p>-->
                                        <br />
                                        VIGOROUS
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos">
                                    <a class="inactive" href="#">
                                        <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/light.png')}}" value="5" />
                                        <!--<p>-->
                                        <br />
                                        HIGH
                                        <!--</p>-->
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="text-right m-t-20">
                            <button class="btn btn-primary btn-o btn-wide open-step" data-run-validation="true" data-target-step="currentAbility">
                                <!--id="next_program_gen_avt"-->
                                Next
                                <i class="fa fa-arrow-circle-right"></i>
                            </button>
                            <!--<button class="btn btn-primary" id="next_program_gen_avt">Next</button>-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
               <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- CURRENT ABILITY-->
<div class="modal fade Activitycalendar width-100" id="current-ability" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">CURRENT ABILITY</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="item_class" data-name="Habit">
                            <!--data-name="select-current_ability"-->
                            <div class="row">
                                <div class="col-md-2 pos currentAbilityOption">
                                    <a class="inactive open-step" href="#" data-target-step="equipmentHave">
                                        <!--open-step-->
                                        <input type="image" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/rehabilitation.gif')}}" value="1" />
                                        <!--value="1" -->
                                        <!--<p>-->
                                        <br />
                                        REHABILITATION
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos currentAbilityOption">
                                    <a class="inactive open-step" href="#" data-target-step="equipmentHave">
                                        <!--data-toggle="collapse" data-parent="#accordion11"-->
                                        <input type="image" value="2" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/beginner.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        BEGINNER
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos currentAbilityOption">
                                    <a class="inactive open-step" href="#" data-target-step="equipmentHave">
                                        <!--data-toggle="collapse" data-parent="#accordion11"-->
                                        <input type="image" value="3" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/intermediate.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        INTERMEDIATE
                                        <!--</p>-->
                                    </a>
                                </div>
                                <div class="col-md-2 pos currentAbilityOption">
                                    <a class="inactive open-step" href="#" data-target-step="equipmentHave">
                                        <!--data-toggle="collapse" data-parent="#accordion11" -->
                                        <input type="image" value="4" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/advanced.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        ADVANCED
                                        <!--</p>-->
                                    </a>
                                </div>
                                <!--<div class="col-md-2"></div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
               <button type="button" class="btn btn-primary nextStepButton" data-current-step="stepChooseCurrentAbility">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- WHAT EQUIPMENT DO YOU HAVE-->
<div class="modal fade Activitycalendar width-100" id="equipmentModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">WHAT EQUIPMENT DO YOU HAVE</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="item_class" data-name="Method">
                            <div class="row">
                                @foreach ($exerciseData['equipments'] as $key => $item)
                                <div class="col-md-2 pos equipmentOption" data-equip="gym">
                                    <a class="inactive" href="#" data-target-step="personalInfo">
                                        <!--data-toggle="collapse" data-parent="#accordion11" -->
                                        <input type="image" value="{{ $key }}" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/beginner.gif')}}" />
                                        <!--<p>-->
                                        <br />
                                        {{$item}}
                                        <!--</p>-->
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
               <button type="button" class="btn btn-primary nextStepButton" data-current-step="stepEquipmentModal">Next</button>
            </div>
        </div>
    </div>
</div>

<!--  WHICH PROGRAME DO YOU WANT-->
<div class="modal fade Activitycalendar width-100" id="program-want" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">WHICH PROGRAME DO YOU WANT</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-body">
                           <div class="panel-group item_class" data-name="FixedProgramId"> <!--id="program_12wk"-->
                           </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
               {{-- <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="">Next</button> --}}
            </div>
        </div>
    </div>
</div>

<!-- start of custom plan title update modal -->
<div class="modal fade" id="customPlanUpdateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
				<h4 class="modal-title" id="myModalLabel">Update Program</h4> </div>
			<div class="modal-body bg-white"> {!! Form::open(['url' => '', 'role' => 'form']) !!} {!! Form::hidden('progId') !!}
				<div class="form-group"> {!! Form::label('progName', 'Title *', ['class' => 'strong']) !!} {!! Form::text('progName', null, ['class' => 'form-control', 'required']) !!} </div>
				<div class="form-group"> {!! Form::label('progDesc', 'Description', ['class' => 'strong']) !!} {!! Form::textarea('progDesc', null, ['class' => 'form-control textarea']) !!} </div> {!! Form::close() !!} </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="customPlanUpdate">Submit</button>
			</div>
		</div>
	</div>
</div>

<!-- Custom Program Modal Starts here -->
<div class="modal fade" id="choosePhaseModal" tabindex="-1" role="dialog" aria-labelledby="choosePhaseLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <h4 class="modal-title" id="choosePhaseLabel">Choose Phase</h4> 
         </div>
			<div class="modal-body bg-white">
            <div class="itemHeader">
               <h4>Please choose number of phase</h4>
            </div>
            <div class="form-group">
               <div class="row">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                     <select class="form-control" name="noOfPhase" title="--Select--">
                        <option value="">--Select--</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                     </select>
                  </div>
               </div>
            </div>
         </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            <button type="button" class="btn btn-primary nextStepButton" data-current-step="choosePhase" data-target-step="createCustomProgram">Next</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="addProgramLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <h4 class="modal-title" id="addProgramLabel">Add Program</h4> 
         </div>
			<div class="modal-body bg-white">
            <div class="form-group">
               <div class="row">
                  <div id="bloodhound" class="program_design m-b-20 col-md-12">
                     <input type="hidden" name="exerciseId" id="exerciseId">
                     <input type="search" name="q" class="form-control search-input" placeholder="Search Exercises..." autocomplete="off">
                  </div>
               </div>
            </div>
         </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            <button type="button" class="btn btn-primary nextStepButton" data-current-step="choosePhase" data-target-step="createCustomProgram">Next</button>
			</div>
		</div>
	</div>
</div>

<!-- Multiphase Program Modal -->
<!-- Create parogram modal -->
<div class="modal fade width-100" id="createProgramModal" tabindex="-1" role="dialog" aria-labelledby="createProgramLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            <h4 class="modal-title" id="createProgramLabel">Create Program</h4> 
         </div>
         <div class="modal-body bg-white">
            <button class="btn btn-primary addPhase" style="margin-bottom:5px;">+ Add Phase</button>
            <div class="row phaseDiv">
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            <button type="button" class="btn btn-primary nextStepPhaseButton" data-current-step="createCustomProgram" data-target-step="">Next</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade Activitycalendar" id="chooseProgram" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">WHAT DO YOU WANT TO DO</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6 pos chooseProgram">
                  <a class="inactive" data-target-step="currentAbility" data-change-flow="true" href="#">
                     <!--data-toggle="collapse" data-parent="#accordion11" href="#collapseOne1" panel-collapse id="program_liv_first" -->
                     <input type="image" class="gif_h_w" value="6" src="{{ asset('assets/plugins/fitness-planner/images/program_lib.gif') }}" data-multiphase="1">
                     <!--<p>-->
                     <br>
                     PROGRAM LIBRARY
                     <br/>
                     Choose an off the shelf pre design
                     <!--</p>-->
                  </a>
               </div>
               <div class="col-md-6 pos chooseProgram">
                  <a class="inactive" data-target-step="designProgram" data-change-flow="true" href="#">
                     <!--data-toggle="collapse" data-parent="#accordion11" panel-collapse id="program_work_first"  -->
                     <input type="image" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/program_design.gif') }}" value="7" data-multiphase="1">
                     <!--<p>-->
                     <br>
                     PROGRAM DESIGNER
                     <br/>
                     Design from scratch
                     <!--</p>-->
                  </a>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            <button type="button" class="btn btn-primary nextStepButton" data-current-step="stepChooseProgram">Next</button>
         </div>
      </div>
   </div>
</div>

<div class="modal fade Activitycalendar width-100" id="planPreview" data-backdrop="static" data-keyboard="false">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title"> PLAN PREVIEW</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div class="panel-body">
                     <div class="row p-l-40">
                        <h3>Your Personal Plan</h3>
                     </div>
                     <div class="row p-l-40" id="plan-preview">

                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
            <button type="button" class="btn btn-primary" id="saveProgramData">Create Training Program </button>
         </div>
      </div>
   </div>
</div>

@include('includes.partials.add_exercise_modal',['exerciseData'=>$exerciseData])
@endsection
@section('script')

 <!-- start: Rating -->
 {!! Html::script('assets/plugins/bootstrap-rating/bootstrap-rating.min.js') !!}
 <!-- end: Rating -->

 <!-- start: Bootstrap Typeahead -->
 {!! Html::script('assets/plugins/bootstrap3-typeahead.min.js') !!}  
 <!-- end: Bootstrap Typeahead --> 

<!-- start: Bootstrap timepicker
{!! Html::script('vendor/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}
end: Bootstrap timepicker -->

<!-- Start:  NEW datetimepicker js -->
{!! Html::script('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') !!}
<!-- End: NEW datetimepicker js -->

<!-- Start:  NEW timepicker js -->
{!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
<!-- End: NEW timepicker js --> 

<!-- start: Bootstrap calendar -->
{!! Html::script('assets/plugins/fullcalendar-2.9.1/fullcalendar.min.js') !!}
<!-- end: Bootstrap calendar -->

{!! Html::script('vendor/jquery-validation/jquery.validate.min.js') !!}

<!-- start: Dirty Form -->
{!! Html::script('assets/js/dirty-form.js?v='.time()) !!}
<!-- end: Dirty Form -->

{!! Html::script('assets/js/helper.js?v='.time()) !!}

<!-- start: Events -->
<script>    
  var loggedInUser = {
    //type: '{{ Session::get('userType') }}',
    type: '{{ Auth::user()->account_type }}',
    id: {{ Auth::user()->account_id }},
    userId: {{ Auth::id() }},
    name: '{{ Auth::user()->fullName }}'
  },
  popoverContainer = $('#client-overview');
</script>
<!-- Start: Activity Planner -->
{!! Html::script('assets/js/fitness-planner/api.js?v='.time()) !!} 
{!! Html::script('assets/js/fitness-planner/bodymapper.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/jquery.json-2.4.min.js') !!}
{!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.placeholder.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.ui.touch-punch.min.js') !!}
{!! Html::script('assets/plugins/fitness-planner/custom/jwplayer/jwplayer.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/js/jquery.ui.labeledslider.js?v='.time()) !!}
{!! Html::script('assets/plugins/fitness-planner/custom/js/popup.js?v=1') !!}
{!! Html::script('assets/js/fitness-planner/fitness-planner.js?v='.time()) !!}
{!! Html::script('assets/js/fitness-planner/library-helper.js?v='.time()) !!}
{!! Html::script('assets/js/activity-calendar.js?v='.time()) !!}
{!! Html::script('result/js/owl.carousel.js') !!}
<!-- ENd: Activity Planner -->
    <script>
      jQuery(document).ready(function() {
        $( ".panel-collapse.closed" ).trigger( "click" );
      });
    </script>
    <script>
      $(document).ready(function(){
        $("#filter").click(function(){
          $("#mySidepanel").toggle();
        });

      });


      $("input[type='radio']").on("change", function() {
        // Regardless of WHICH radio was clicked, is the
        //  showSelect radio active?
        if ($("#ExerciseType2").is(':checked')) {
        $('.checked-hide').removeClass("hidden");
      } else {
        $('.checked-hide').addClass("hidden");
      }
      });

      $(function () {
        $("#Choosedays").click(function () {
          if ($(this).is(":checked")) {
            $('#selectOneday').hide();
            $('#showWeekday').show();
          }
        });
        $("#daysInWeek").click(function () {
          if ($(this).is(":checked")) {
            $('#showWeekday').hide();
            $('#selectOneday').show();
          }
        });
      });
    </script>
    @stop
