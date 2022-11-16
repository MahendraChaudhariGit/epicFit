@extends('blank')

@section('plugin-css')
    {!! Html::style('assets/css/plugins.css') !!}
    {!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css') !!}
    {!! Html::style('vendor/sweetalert/sweet-alert.css') !!}

    <!-- start: Bootstrap datepicker --> 
    {!! Html::style('vendor/bootstrap-datepicker/bootstrap-datepicker3.min.css') !!} 
    <!-- end: Bootstrap datepicker --> 

    <!-- start: Bootstrap daterangepicker -->
    {!! Html::style('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') !!}
    <!-- end: Bootstrap daterangepicker -->

    <!-- start: JCrop -->
    {!! Html::style('assets/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}
    <!-- end: JCrop -->

    {!! HTML::style('assets/plugins/fitness-planner/custom/style.css') !!} 
    {!! Html::style('assets/plugins/fitness-planner/css/api.css') !!}
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
     </style>
@stop

@section('page-title')
    @if(isset($libraryProgram))
        Edit
    @else
        Add
    @endif
    Multi Phase Library Program
@stop

@section('content')
   <input type="hidden" name="programView" id="programView" value="modal">
   <input type="hidden" name="clientPlanId" id="clientPlanId" value="{{$libraryProgram->id}}">
   <input type="hidden" name="clientPlanProgramId" id="clientPlanProgramId" value="">
    <!-- start: Delete Form -->
    @include('includes.partials.delete_form')
    <!-- end: Delete Form -->

    <!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
    <!-- end: Pic crop Model -->

    <!-- start: plan design -->
    @include('includes.partials.add_exercise_modal',['exerciseData'=>$exerciseData])
    <!-- start: plan design -->
    <div id="alertDiv" class="hidden"></div>
    <div id="programGenerateField" >
        @include('ActivityBuilder.LibraryProgram.MultiPhase.form',['exerciseData'=>$exerciseData])
    </div>

    <!-- start: plan design -->
    <div id="libraryActivityPlanner">
        {{-- @include('includes.partials.activities_planner_helper',['page'=>'Admin']) --}}
    </div>
    
    <!-- End: Plan design -->
    
   <!-- Create parogram modal -->
   <div class="modal fade" id="selectDaysModal" tabindex="-1" role="dialog" aria-labelledby="selectDaysLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
               <h4 class="modal-title" id="selectDaysLabel">Select Days or Week</h4> 
            </div>
            <div class="modal-body bg-white">
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
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>
               <button type="button" class="btn btn-primary nextStepPhaseButton" data-current-step="selectDays" data-target-step="createCustomProgram">Next</button>
            </div>
         </div>
      </div>
   </div>

   <div class="modal fade width-100" id="createProgramModal" tabindex="-1" role="dialog" aria-labelledby="createProgramLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
               <h4 class="modal-title" id="createProgramLabel">Create Program</h4> 
            </div>
            <div class="modal-body bg-white" style="min-height: 300px">
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
                        <input type="image" class="gif_h_w" value="6" src="{{ asset('assets/plugins/fitness-planner/images/program_lib.gif') }}">
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
               <button type="button" class="btn btn-primary nextStepPhaseButton" data-current-step="stepChooseProgram">Next</button>
            </div>
         </div>
      </div>
   </div>

   <div class="modal fade Activitycalendar width-100" id="currentAbility" data-backdrop="static" data-keyboard="false">
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
                                 <div class="col-md-2 pos currentAbilityOptions">
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
                                 <div class="col-md-2 pos currentAbilityOptions">
                                       <a class="inactive open-step" href="#" data-target-step="equipmentHave">
                                          <!--data-toggle="collapse" data-parent="#accordion11"-->
                                          <input type="image" value="2" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/beginner.gif')}}" />
                                          <!--<p>-->
                                          <br />
                                          BEGINNER
                                          <!--</p>-->
                                       </a>
                                 </div>
                                 <div class="col-md-2 pos currentAbilityOptions">
                                       <a class="inactive open-step" href="#" data-target-step="equipmentHave">
                                          <!--data-toggle="collapse" data-parent="#accordion11"-->
                                          <input type="image" value="3" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/intermediate.gif')}}" />
                                          <!--<p>-->
                                          <br />
                                          INTERMEDIATE
                                          <!--</p>-->
                                       </a>
                                 </div>
                                 <div class="col-md-2 pos currentAbilityOptions">
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
                  <button type="button" class="btn btn-primary nextStepPhaseButton" data-current-step="stepCurrentAbility">Next</button>
               </div>
         </div>
      </div>
   </div>

   <div class="modal fade Activitycalendar width-100" id="equipmentLibraryModal" data-backdrop="static" data-keyboard="false">
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
                                       <a class="inactive" href="#" data-target-step="personalInfoLibrary">
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
                  <button type="button" class="btn btn-primary nextStepPhaseButton" data-current-step="stepLibraryEquipmentModal">Next</button>
               </div>
         </div>
      </div>
   </div>

   <div class="modal fade Activitycalendar width-100" id="libraryProgramWant" data-backdrop="static" data-keyboard="false">
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

   <div class="modal fade Activitycalendar width-100" id="createTrainingSegmants" data-backdrop="static" data-keyboard="false">
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
                                 <div class="panel-group accordion sortable" id="choosedTrainingsAccordion">
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
                                 </div>
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
               <button type="button" class="btn btn-primary nextStepPhaseButton" data-current-step="trainingSegment" id="trainingSegmentSubmit">Next</button>
            </div>
         </div>
      </div>
   </div>

   <div class="modal fade" id="customPlanUpdateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
               <h4 class="modal-title" id="myModalLabel">Design a Program</h4> </div>
            <div class="modal-body bg-white"> {!! Form::open(['url' => '', 'role' => 'form']) !!} {!! Form::hidden('progId') !!}
               <div class="form-group"> {!! Form::label('progName', 'Title *', ['class' => 'strong']) !!} {!! Form::text('progName', null, ['class' => 'form-control', 'required']) !!} </div>
               <div class="form-group"> {!! Form::label('progDesc', 'Description', ['class' => 'strong']) !!} {!! Form::textarea('progDesc', null, ['class' => 'form-control textarea' , 'required']) !!} </div> {!! Form::close() !!} </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary pull-left backStepButton" data-prev-step="">Back</button>

               <button type="button" class="btn btn-primary nextStepPhaseButton" data-current-step="designProgramMultiPhase" data-target-step="trainingSegment">Next</button>
            </div>
         </div>
      </div>
   </div>


   
   <!-- start of exercise details modal -->
<div id="lungemodal" class="modal fade" role="dialog" >
   <div class="modal-dialog modal-dialog modal-lg">
     <div class="modal-content panel-white">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title"></h4>
       </div>
       <div class="modal-body bg-white">
         <div class="row">
           <div class="col-md-6">
             <div style="max-height:500px;overflow-x:hidden">
               <div class="panel-group accordion" id="accordion-fit">
                 <div class="panel panel-white">
                   <div class="panel-heading">
                     <h4 class="panel-title text-center">
                       <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapseOne1">
                         MUSCLES
                       </a>
                     </h4>
                   </div>
                   <div id="collapseOne1" class="panel-collapse collapse">
                     <div class="panel-body" id="muscles">
                       <!-- here message inject throught js --> 
                     </div>
                   </div>
                 </div>
 
                 <div class="panel panel-white">
                   <div class="panel-heading">
                     <h4 class="panel-title text-center">
                       <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapseTwo2">
                         BENEFITS
                       </a>
                     </h4>
                   </div>
                   <div id="collapseTwo2" class="panel-collapse collapse">
                     <div class="panel-body" id="benifits">     
                          <!-- here message inject throught js --> 
                     </div>
                   </div>
                 </div>
 
                 <div class="panel panel-white">
                   <div class="panel-heading">
                     <h4 class="panel-title text-center">
                       <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapseThree3">
                         CUES
                       </a>
                     </h4>
                   </div>
                   <div id="collapseThree3" class="panel-collapse collapse">
                     <div class="panel-body" id="cues"> 
                        <!-- here message inject throught js --> 
                     </div>
                   </div>
                 </div>
 
                 <div class="panel panel-white">
                   <div class="panel-heading">
                     <h4 class="panel-title text-center">
                       <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapsefour4">
                         MOVEMENT DESCRIPTION
                       </a>
                     </h4>
                   </div>
                   <div id="collapsefour4" class="panel-collapse collapse">
                     <div class="panel-body" id="movement_desc">
                        <!-- here message inject throught js --> 
                     </div>
                   </div>
                 </div>
 
                 <div class="panel panel-white">
                   <div class="panel-heading">
                     <h4 class="panel-title text-center">
                       <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapsefive5">
                         COMMON MISTAKES
                       </a>
                     </h4>
                   </div>
                   <div id="collapsefive5" class="panel-collapse collapse">
                     <div class="panel-body" id="common_mistekes">
                         <!-- here message inject throught js --> 
                     </div>
                   </div>
                 </div>
 
                 <div class="panel panel-white">
                   <div class="panel-heading">
                     <h4 class="panel-title text-center">
                       <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-fit" href="#collapsesix6">
                         PROGRESS
                       </a>
                     </h4>
                   </div>
                   <div id="collapsesix6" class="panel-collapse collapse">
                     <div class="panel-body" id="progress">
                         <!-- here message inject throught js --> 
                     </div>  
                   </div>
                 </div>
                 <div class="panel panel-white" id="exerciseImageTab">
                   <div class="panel-heading">
                     <h4 class="panel-title text-center">
                       <a class="accordion-toggle collapsed" href="#" onclick="javascript:showExerciseImages()">
                         IMAGES
                       </a>
                     </h4>
                   </div>
                 </div>
                 <div class="panel panel-white" id="exerciseVideoTab">
                   <div class="panel-heading">
                     <h4 class="panel-title text-center">
                       <a class="accordion-toggle collapsed" href="#" onclick="javascript:showExerciseVideo()">
                         PROGRAM VIDEO
                       </a>
                     </h4>
                   </div>
                 </div>
                 <div class="panel panel-white" id="exerciseTutorialTab">
                   <div class="panel-heading">
                     <h4 class="panel-title text-center">
                       <a class="accordion-toggle collapsed" href="#" onclick="javascript:showTutorialVideo()">
                        TUTORIAL VIDEO
                       </a>
                     </h4>
                   </div>
                 </div>
 
 
               </div>
             </div>
           </div>
 
           <div class="col-md-6 hidden" id="exe-img-area">
             <!-- <img src="" class="exerciseImg" height="100%" width="100%"> -->
           </div>
           <div class="col-md-6 hidden" id="exe-vid-area">
             <!-- <img src="" class="exerciseImg" height="100%" width="100%"> -->
           </div>
            <div class="col-md-6" id="exe-tutorial-area">
             <!-- <img src="" class="exerciseImg" height="100%" width="100%"> -->
           </div>
         </div>
       </div>
       <div class="modal-footer">
         <a class="btn btn-primary btn-o pull-left" href="#" data-dismiss="modal"> Back to exercises </a>
         <!-- <a class="btn btn-primary toggle-fav">
           <i class="fa fa-heart"></i>
         </a> -->
         <a class="btn btn-primary toggle-exercise" href="#">Add to program</a><!--id="addToProgram" -->
       </div>
     </div>
   </div>
 </div> 
 <!-- end of exercise details modal -->
 
 <!-- Activity Video Detail Modal -->
 <div id="videoModal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog modal-lg">
     <div class="modal-content panel-white">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title"></h4>
       </div>
       <div class="modal-body bg-white">
         <div class="row">
           <div class="col-md-6">
             <video controls class="video" height="300" width="100%" id="myVideo">               
             </video>
           </div>
           <div class="col-md-6">
             <div class="form-group">
                 {!! Form::label('title', 'Description ', ['class' => 'strong']) !!}
                 <p id="description"></p>
             </div>
             <div class="form-group">
                 <table class="tb-movement">
                 </table>
             </div>
           </div>
         </div>
       </div>
       <div class="modal-footer" data-exercise-id="" data-exercise-type-id="">
         <a class="btn btn-primary btn-o pull-left" href="#" data-dismiss="modal"> Back to exercisess </a>
         <!-- <a class="btn btn-primary toggle-fav">
           <i class="fa fa-heart"></i>
         </a> -->
         <a class="btn btn-primary toggle-video" href="#">Add to program</a><!--id="addToProgram" -->
       </div>
     </div>
   </div>
 </div> 
 <!-- End -->
   
@stop

@section('script')
    <!-- Start:  NEW timepicker js -->
    {!! Html::script('assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js') !!}
    <!-- End: NEW timepicker js --> 

    <!-- Start: Movement -->
    {!! Html::script('assets/js/movement.js') !!}
    <!-- End: Movement -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
    {!! Html::script('assets/plugins/Jcrop/js/jquery.Jcrop.min.js') !!}
    {!! Html::script('assets/plugins/Jcrop/js/script.js') !!}
    {!! Html::script('assets/js/helper.js') !!}

   <!-- Start: Activity Planner -->
    {!! Html::script('assets/js/fitness-planner/api.js') !!} 
    {!! Html::script('assets/js/fitness-planner/bodymapper.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/jquery.json-2.4.min.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.placeholder.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/jquery.ui.touch-punch.min.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/jwplayer/jwplayer.js?v='.time()) !!}
    {!! Html::script('assets/plugins/fitness-planner/js/jquery.ui.labeledslider.js') !!}
    {!! Html::script('assets/plugins/fitness-planner/custom/js/popup.js') !!}
    {!! Html::script('assets/js/fitness-planner/fitness-planner.js?v='.time()) !!}
    {!! Html::script('assets/js/fitness-planner/library-helper.js?v='.time()) !!}
    <!-- ENd: Activity Planner -->
    <script>
        jQuery(document).ready(function() {
            //Metronic.init();
            $( ".panel-collapse.closed" ).trigger( "click" );
        });
    </script>

<script>
   $(document).ready(function(){
     $("#filter").click(function(){
       $("#mySidepanel").toggle();
     });

   });
</script>
@stop