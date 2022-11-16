
<!---start of training segment -->
<div class="panel panel-white" data-step="trainingSegment">
  <!-- start: PANEL HEADING -->
  <div class="panel-heading">
    <h5 class="panel-title">
      <span class="icon-group-left">
        <i class="fa fa-ellipsis-v"></i>
      </span>
      CREATE TRAINING SEGMENTS
      <span class="icon-group-right">
        <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
          <i class="fa fa-wrench"></i>
        </a>
        <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="fitness-planner">
          <i class="fa fa-chevron-down"></i>
        </a>
      </span>
    </h5>
  </div>
  <!-- end: PANEL HEADING -->
   
  <!-- start: PANEL BODY -->
  <div class="panel-body">
    <div>
      <p>
        Please choose the training segments that you require in your training routine, each segment relates to the different aspects of training and maintaining a balanced program related to an effective warm-up, cardiovascular, resistance, core, cooling down and stretching
      </p>
      <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
          <input id="warm_up" name="warm up" value="1" type="checkbox" class="choosetrainingSegment">
          <label for="warm_up">Warm-Up</label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
          <input id="cardio" name="cardio" value="2" type="checkbox" class="choosetrainingSegment">
          <label for="cardio">Cardiovascular Training</label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
          <input id="exercises" name="exercises" value="3" type="checkbox" class="choosetrainingSegment">
          <label for="exercises">Resistance Training</label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
          <input id="skill" name="skill" value="4" type="checkbox" class="choosetrainingSegment">
          <label for="skill">Skill Training</label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
          <input id="core" name="core" value="5" type="checkbox" class="choosetrainingSegment">
          <label for="core">Core</label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
          <input id="cool_down" name="cool down" value="6" type="checkbox" class="choosetrainingSegment">
          <label for="cool_down">Cool-Down</label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 wd-block">
          <input id="stretch" name="stretch" value="7" type="checkbox" class="choosetrainingSegment">
          <label for="stretch">Recovery Routine/Stretching</label>
        </div>
      </div>
    </div>

    <div class="m-t-20">
      <div>
        <h4 class="m-b-5">Add exercises to training segments</h4>
        <p>Please choose the exercises you require in the training segments in your training routine, each segment relates to the different aspects of training and maintaining a balanced program related to an effective warm-up, cardiovascular, resistance, core, cooling down and stretching</p>
      </div>
      <div class="panel-group accordion" id="choosedTrainingsAccordion">
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
            <div class="panel-body">
              <div class="row choosedExercRow" data-work-out="warm_up" data-work-name="warm_up">
              </div>
              <!--<div class="list-group">
                <a class="list-group-item active" href="#">
                  List 1
                  <button class="btn btn-xs btn-default tooltips delLink pull-right m-l-3 link-btn" data-placement="top" data-original-title="Delete" onclick=" confirmDeletee()">
                      <i class="fa fa-trash-o"></i>
                    </button>
                    <button class="btn btn-xs btn-default tooltips pull-right link-btn" data-placement="top" data-original-title="Edit" data-toggle="modal" data-target="#myModal">
                      <i class="fa fa-pencil" ></i>
                   </button>  
                </a>
              </div>-->
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
            <div class="panel-body">
              <div class="row choosedExercRow" data-work-out="cardio" data-work-name="cardio">
              </div>
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
            <div class="panel-body">
              <div class="row choosedExercRow" data-work-out="exercises" data-work-name="resist">
              </div>
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
            <div class="panel-body">
              <div class="row choosedExercRow" data-work-out="skill"  data-work-name="skill">
              </div>
            </div>
          </div>
        </div>

        <div class="panel panel-white" style="display:none;">
          <div class="panel-heading">
            <h5 class="panel-title">
              <a class="accordion-toggle collapsed clearfix" data-toggle="collapse" data-parent="#choosedTrainingsAccordion" href="#accord_core">
                <i class="icon-arrow"></i>
                CORE
                <button type="button" class="btn btn-xs btn-default pull-right add-exercise-btn" data-workout='5'>
                  Add exercise
                </button>
              </a>
            </h5>
          </div>
          <div id="accord_core" class="panel-collapse collapse">
            <div class="panel-body">
              <div class="row choosedExercRow" data-work-out="core" data-work-name="core">
              </div>
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
            <div class="panel-body">
              <div class="row choosedExercRow" data-work-out="cool_down" data-work-name="cool_down">
              </div>
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
            <div class="panel-body">
              <div class="row choosedExercRow" data-work-out="stretch" data-work-name="stretch">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="m-t-20 clearfix"><!--text-right -->
      <h4 class="pull-left">Total Estimated Time: <span id="programTotalTime">0</span> minutes</h4>
      <button class="btn btn-primary btn-o btn-wide pull-right" data-target-step="planMyProgram" id="trainingSegmentSubmit"><!--open-step-->
        Next 
        <i class="fa fa-arrow-circle-right"></i>
      </button>
    </div>
  </div>
  <!-- end: PANEL BODY -->
</div>
<!--<div class="panel panel-white" data-step="trainingSegment"><!--id="create_program_hide" -->
  <!-- start: PANEL HEADING -->
  <!--<div class="panel-heading">
    <h5 class="panel-title">
      <span class="icon-group-left">
        <i class="fa fa-ellipsis-v"></i>
      </span>
      CREATE TRAINING SEGMENTS
      <span class="icon-group-right">
        <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
          <i class="fa fa-wrench"></i>
        </a>
        <a class="btn btn-xs pull-right panel-collapse closed" data-panel-group="fitness-planner" href="#" id="create_program_design">
          <i class="fa fa-chevron-down"></i>
        </a>
      </span>
    </h5>
  </div>
  <!-- end: PANEL HEADING -->
  <!-- start: PANEL BODY -->
  <!--<div class="panel-body">
    <div class="panel-group"><!--id="accordion211"-->
      <!--<p>
        Please choose the training segments that you require in your training routine, each segment relates to the different aspects of training and maintaining a balanced program related to an effective warm-up, cardiovascular, resistance, core, cooling down and stretching
      </p>
      <!--<br/><br/>-->
      <!--<div cid="pt-accordion">
        <!--- start of warm up -->
        <!--<div class="panel panel-default add_exercise">
          <div class="panel-heading clearfix">
            <h5 class="panel-title pull-left margin_class">
              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href=" #collapseOne">
                WARM UP:
              </a>
            </h5>
            <button class="btn btn-default pull-right create_for_design"  data-toggle="modal" data-target="#addexercise">
              Add exercise
            </button>
          </div>
          <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
              <ul class="cat_det ui-sortable" style="display: block;"></ul>
            </div>
          </div>
        </div>
        <!--- end of warm up -->

        <!--- start of cardio -->
        <!--<div class="panel panel-default add_exercise">
          <div class="panel-heading clearfix">
            <h5 class="panel-title pull-left margin_class">
              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsetwo">
                CARDIOVASCULAR TRAINING:
              </a>
            </h5>
            <button class="btn btn-default pull-right create_for_design"  data-toggle="modal" data-target="#addexercise">
              Add exercise
            </button>
          </div>
          <div id="collapsetwo" class="panel-collapse collapse">
            <div class="panel-body"></div>
          </div>
        </div>
        <!---end of cardio -->

        <!--- start of resistance traning-->
        <!--<div class="panel panel-default add_exercise">
          <div class="panel-heading clearfix">
            <h5 class="panel-title pull-left margin_class">
              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsethird">
                RESISTANCE TRANING:
              </a>
            </h5>
            <button class="btn btn-default pull-right create_for_design"  data-toggle="modal" data-target="#addexercise">
              Add exercise
            </button>
          </div>
          <div id="collapsethird" class="panel-collapse collapse">
            <div class="panel-body"></div>
          </div>
        </div>
        <!---end of resistance traning-->

        <!---skill traning-->
        <!--<div class="panel panel-default add_exercise">
          <div class="panel-heading clearfix">
            <h5 class="panel-title pull-left margin_class">
              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefourth">
                SKILL TRANING:
              </a>
            </h5>
            <button class="btn btn-default pull-right create_for_design"  data-toggle="modal" data-target="#addexercise">
              Add exercise
            </button>
          </div>
          <div id="collapsefourth" class="panel-collapse collapse">
            <div class="panel-body"></div>
          </div>
        </div>
        <!--</div>-->
        <!---end of skills traning-->

        <!---start of core-->
        <!--<div class="panel panel-default add_exercise">
          <div class="panel-heading clearfix">
            <h5 class="panel-title pull-left margin_class">
              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefivth">
                CORE:
              </a>
            </h5>
            <button class="btn btn-default pull-right create_for_design"  data-toggle="modal" data-target="#addexercise">
              Add exercise
            </button>
          </div>
          <div id="collapsefivth" class="panel-collapse collapse">
            <div class="panel-body"></div>
          </div>
        </div>
        <!---end of core-->

        <!---start of cool down-->
        <!--<div class="panel panel-default add_exercise">
          <div class="panel-heading clearfix">
            <h5 class="panel-title pull-left margin_class">
              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsetsix">
                COOL DOWN:
              </a>
            </h5>
            <button class="btn btn-default pull-right create_for_design"  data-toggle="modal" data-target="#addexercise">
              Add exercise
            </button>
          </div>
          <div id="collapsesix" class="panel-collapse collapse">
            <div class="panel-body"></div>
          </div>
        </div>
        <!---end of cool down-->
        
        <!--<div class="text-right">
          <button class="btn btn-primary" id="Schedule_program">SCHEDULE YOUR PROGRAM</button>
        </div>
        <!--<button class="btn btn-default pull-right" id="Schedule_program">
          SCHEDULE YOUR PROGRAM
        </button>-->
      <!--</div>
    </div>
  </div>
  <!-- end: PANEL BODY -->
<!--</div>-->
<!---end of training segment -->

<!---start of plan my program-->
<div class="panel panel-white" data-step="planMyProgram"><!--step1 -->  <!--data-name="planMyProgram"-->
  <!-- start: PANEL HEADING -->
  <div class="panel-heading">
    <h5 class="panel-title">
      <span class="icon-group-left">
        <i class="fa fa-ellipsis-v"></i>
      </span>
      PLAN MY PROGRAM
      <span class="icon-group-right">
        <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
          <i class="fa fa-wrench"></i>
        </a>
        <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="fitness-planner"><!--id="plan_program"-->
          <i class="fa fa-chevron-down"></i>
        </a>
      </span>
    </h5>
  </div>
  <!-- end: PANEL HEADING -->                                                                    
  <!-- start: PANEL BODY -->
  <div class="panel-body item_class"><!-- data-name="selct_program"-->
    <div id="traingPlannerMain" class="plannerContainer fit-ui">
      <div><!--id="weekSelection"-->
        <div class="itemHeader"><!--data-name="Weeks"-->
          <span>
            <h4>How many weeks do you want to do this plan for?</h4>
          </span>
        </div>
        <div class="item-class"><!--data-name="DaysOfWeek"-->
          <div class="itemBody" >
            <div id="weekSlider-crm"></div>
          </div>
        </div>
      </div>

      <div id="timeSelection" class="m-t-20">
        <div class="itemHeader"><!--style="margin-top:20px" data-name="TimePerWeek" -->
          <span>
            <h4>How long can you work out each week?</h4>
          </span>
        </div>
        <div class="itemBody m-t-20">
          <div id="timeSlider-crm"></div>
        </div>
      </div>

      <div class="m-t-20 form-group">
        <div class="itemHeader"><!--data-name="DaysOfWeek"-->
          <span>
            <h4>Please choose <span id="daySelectionTextCRM"></span> days to work out</h4><!--id="daySelection"-->
          </span>
        </div>
        <div class="itemBody no-error-labels" id="days-crm"><!--id="weekDays" -->
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
            <input type="checkbox" id="mon-planner" value="1" class="onchange-set-neutral" />
            <label for="mon-planner">Mon</label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
            <input type="checkbox" id="tue-planner" value="1" class="onchange-set-neutral" />
            <label for="tue-planner">Tue</label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
            <input type="checkbox" id="wed-planner" value="1" class="onchange-set-neutral" />
            <label for="wed-planner">Wed</label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
            <input type="checkbox" id="thu-planner" value="1" class="onchange-set-neutral" />
            <label for="thu-planner">Thu</label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
            <input type="checkbox" id="fri-planner" value="1" class="onchange-set-neutral" />
            <label for="fri-planner">Fri</label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
            <input type="checkbox" id="sat-planner" value="1" class="onchange-set-neutral" />
            <label for="sat-planner">Sat</label>
          </div>
          <div class="checkbox clip-check check-primary checkbox-inline m-b-0 w70">
            <input type="checkbox" id="sun-planner" value="1" class="onchange-set-neutral" />
            <label for="sun-planner">Sun</label>
          </div>
        </div>
        <span class="help-block m-t-0"></span>
      </div>

      <div class="text-right m-t-20">
        <button class="btn btn-primary btn-o btn-wide open-step" data-target-step="planPreview">
          Next 
          <i class="fa fa-arrow-circle-right"></i>
        </button>
      </div>


      <!--<div id="planInfo" class="plannerInfo">
        <div id="planMessage" class="plannerDetail"></div>
        <h2 id="planPreviewTitle">Plan Preview</h2>
        <h2 id="planFinalTitle">Your Personal Plan</h2>
        <p id="planLink" style="display: none;">Visit 
          <a href="/member/home/">your homepage</a> to see your new training calendar
        </p>
        <div id="planSchedule" class="plannerSchedule"></div>
      </div>-->

      <!--<div id="timeSelection"><div id="select_program_genr"><div class="itemBody"><div id="program_liv_plan"></div></div></div></div>-->
      <!--<div id="timeSelection">
        <div class="itemHeader" style="margin-top:20px"><!-- data-name="TimePerWeek" -->
          <!--<span>
            <h4>How long can you work out each week?</h4>
          </span>
        </div>
        <div class="itemBody">
          <div id="timeSlider"></div>
        </div>
      </div>
      <br/>-->
      <!--<div>
        <!-- <input value="Create Training Programme" id="doneButton" class="addButton ui-button ui-widget ui-state-default ui-corner-all ui-button-success" role="button" aria-disabled="false" type="button"> -->
        <!--<button class="btn btn-primary pull-right" id="doneButton" role="button" aria-disabled="false">Create Training Programme</button>
        <div style="display: none" class="fit_saveplan_ajax"></div>
      </div>-->
    </div>
  </div>
  <!-- end: PANEL BODY -->
</div>
<!--end of plan my program-->

<!---start of the PERSONAL INFORMATION-->
  <div class="panel panel-white" data-step="personalInfo"><!--step1 step2-->
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
      <h5 class="panel-title">
        <span class="icon-group-left">
          <i class="fa fa-ellipsis-v"></i>
        </span>
        PERSONAL INFORMATION
        <span class="icon-group-right">
          <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
            <i class="fa fa-wrench"></i>
          </a>
          <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="fitness-planner"><!--id="pinformation" -->
            <i class="fa fa-chevron-down"></i>
          </a>
        </span>
      </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    @if(isset($parq))
    <div class="panel-body item_class"><!--data-name="select_information"-->
      <div class="row">
        <div class="col-md-3 pos" data-name="Age">
          <div class="editable">{{ $parq->age }} years</div>
          <input type="number" value="{{$parq->age}}" id="fit_age" class="form-control hidden numericField" min="18" max="98" />
          <hr class="add_hr">
          MY AGE             
        </div> 
        <div class="col-md-3 pos" data-name="Weight">
          <div class="editable">{{ $parq->weight?$parq->weight:'&nbsp;' }}</div>
          <select id="fit_weight" class="form-control hidden">
            <option value="">-- Select --</option>
            @if($parq->weightUnit == 'Imperial')
              {!! renderWeightDdOptions($parq->weight, 'imperial') !!}
            @else
              {!! renderWeightDdOptions($parq->weight, 'metric') !!}
            @endif
          </select>
          <!--<input type="text" value="{{$parq->weight}}" id="fit_weight" class="form-control hidden" />-->
          <hr class="add_hr">
          MY WEIGHT
        </div>
        <div class="col-md-3 pos" data-name="Height">
          <div class="editable">{{ $parq->height?$parq->height:'&nbsp;' }}</div>
          <select id="fit_height" class="form-control field-editable hidden">
            <option value="">-- Select --</option>
            @if($parq->heightUnit == 'Imperial')
              {!! renderHeightDdOptions($parq->height, 'imperial') !!}
            @else
              {!! renderHeightDdOptions($parq->height, 'metric') !!}
            @endif
          </select> 
          <!--<input type="text" value="{{-- $parq->height --}}" id="fit_height" class="form-control hidden" />-->
          <hr class="add_hr">
          MY HEIGHT
        </div>
        <!--<div class="col-md-3 pos"></div>-->
      </div>
      <div class="text-right m-t-20">
        <button class="btn btn-primary btn-o btn-wide open-step" data-target-step="planPreview">
          Next 
          <i class="fa fa-arrow-circle-right"></i>
        </button>
        <!--<button class="btn btn-primary " id="next_plan_program">next</button>-->
      </div>
    </div>
    @endif
    <!-- end: PANEL BODY -->
  </div>
<!---end of PERSONAL INFORMATION-->

<!---Start: Preview -->
  <div class="panel panel-white" data-step="planPreview">
    <!-- start: PANEL HEADING -->
    <div class="panel-heading">
      <h5 class="panel-title">
        <span class="icon-group-left">
          <i class="fa fa-ellipsis-v"></i>
        </span>
        PLAN PREVIEW
        <span class="icon-group-right">
          <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
            <i class="fa fa-wrench"></i>
          </a>
          <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="fitness-planner"><!--data-panel-group="epic-process" id="collapseTwoOne1" -->
            <i class="fa fa-chevron-down"></i>
          </a>
        </span>
      </h5>
    </div>
    <!-- end: PANEL HEADING -->
    <!-- start: PANEL BODY -->
    <div class="panel-body">
      <div class="row p-l-40">
        <h3>Your Personal Plan</h3>
      </div>
      <div class="row p-l-40" id="plan-preview-area">
        <!-- Inject data throught ajax (fitness-planner rendorPlanPreview() function)-->
      </div>
      <div class="row">
        <div class="text-right m-t-20">
          <button class="btn btn-primary btn-o btn-wide" id="savePlan" ><!--data-target-step="" id="doneButton"-->
            Create Training Programme 
            <i class="fa fa-arrow-circle-right"></i>
          </button>
        </div>
      </div>
    </div>
    <!-- end: PANEL BODY -->
  </div>
<!---End: Preview -->

<!-- Start: all modal here -->
<!-- start of custom plan title update modal -->
<div class="modal fade" id="customPlanUpdateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Update Program</h4>
      </div>
      <div class="modal-body bg-white">
        {!! Form::open(['url' => '', 'role' => 'form']) !!}
          {!! Form::hidden('progId') !!}
          <div class="form-group">
            {!! Form::label('progName', 'Title *', ['class' => 'strong']) !!}
            {!! Form::text('progName', null, ['class' => 'form-control', 'required']) !!}
          </div>
          <div class="form-group">
            {!! Form::label('progDesc', 'Description', ['class' => 'strong']) !!}
            {!! Form::textarea('progDesc', null, ['class' => 'form-control textarea']) !!}
          </div>
        {!! Form::close() !!}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="customPlanUpdate">Submit</button>
      </div>
    </div>
  </div>
</div>
<!-- end of custom plan title update modal -->

<!-- <map name="Map" id="Map">
</map> -->

