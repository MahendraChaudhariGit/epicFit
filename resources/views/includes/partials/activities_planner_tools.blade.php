<div id="alertDiv" class="hidden"></div>
<!-- Start: Activity planner tool -->
<input type="hidden" id="fit_clientId" value="{{ $parq->client_id }}" name="fit_clientId" />
<input type="hidden" id="fit_gender" value="{{ $parq->gender }}" name="fit_gender" />
<input type="hidden" id="fit_email" value="tester@epicstride.com" />
<input type="hidden" id="clientName" value="{{ ucfirst($parq->firstName) }} {{ ucfirst($parq->lastName) }}" name="fit-clientName" />
<input type="hidden" id="fit_height" value="{{ $parq->height }}" />
<input type="hidden" id="fit_weight" value="{{$parq->weight}}" />
<input type="hidden" id="fit_age" value="{{$parq->age}}" />
<!-- <input type="hidden" id="fit_session" value="d95a16d3-3a54-4598-a55a-af4abc086a29" /> -->

<div id="planSelector1">
  <!---start of my program-->
  <div id="program_create">
    <!--- start of RESISTANCE TRAINING BUILDER-->
    <div class="panel panel-white">
      <!-- start: PANEL HEADING -->
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="fa fa-ellipsis-v"></i>
          </span>
          RESISTANCE TRAINING BUILDER
          <span class="icon-group-right">
            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
              <i class="fa fa-wrench"></i>
            </a>
            <a class="btn btn-xs pull-right panel-collapse" href="#" data-panel-group="fitness-planner">
              <i class="fa fa-chevron-down"></i>
            </a>
          </span>
        </h5>
      </div>
      <!-- end: PANEL HEADING -->
      <!-- start: PANEL BODY -->
      <div class="panel-body">
        <div class="panel-group item_class" data-name=""><!--data-name="select_traning" id="accordion211" -->
          <div class="row">
            <div class="col-md-3 pos">
              <a class="inactive open-step" data-target-step="programChoose" data-change-flow="true" href="#"> <!--data-parent="#accordion211" change-flow id="panel-collapse1" data-toggle="collapse" panel-collapse-->
                <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/resistance_traning.png')}}" value="" class=""> <!--value="8" -->
                <!--<p> -->
                <br>
                RESISTANCE TRAINING
                <br/>
                Select resistance training 
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-3 pos">
              <a class="inactive center" href="#"> <!--change-flow  data-toggle="collapse" data-parent="#accordion11" id="panel-collapse1" panel-collapse-->
                <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/cardioa.png')}}" value="" class=""><!--value="9" -->
                <br>
                <!--<p >--> 
                CARDIOVASCULAR ACTIVITY
                <br/> 
                Select cardiovascular activities
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-3 pos">
              <a class="inactive open-step" data-target-step="acticityVideo" data-change-flow="true" href="#"> <!--change-flow  data-toggle="collapse" data-parent="#accordion11" id="panel-collapse1" panel-collapse-->
                <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/video-icon.png')}}" value="" class=""><!--value="9" -->
                <br>
                <!--<p >--> 
                Videos
                <br/> 
                Select activity videos
                <!--</p>-->
              </a>
            </div>
            <!--<div class="col-md-2"></div>
            <div class="col-md-2"></div>-->
          </div>
          <!--<div id="collapseOne12" class="panel-collapse collapse">
            <div class="panel-body">
            </div>
          </div>-->
        </div>
      </div>
      <!-- end: PANEL BODY -->
    </div>
    <!---end of RESISTANCE TRAINING BUILDER -->

    <!---start of what do you want to do -->
    <div class="panel panel-white" data-step="programChoose">
      <!-- start: PANEL HEADING -->
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="fa fa-ellipsis-v"></i>
          </span>
          WHAT DO YOU WANT TO DO
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
        <div class="panel-group item_class" data-name="Plan"><!--id="accordion211"-->
          <div class="row">
            <div class="col-md-3 pos">
              <a class="inactive open-step" data-target-step="activityChoose" data-change-flow="true" href="#"><!--data-parent="#accordion211" data-toggle="collapse" panel-collapse id="program_gen_avt" -->
                <input type="image" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/program_gen.gif') }}" value="5">
                <!--<p>-->
                <br>
                PROGRAM GENERATOR
                <br/>
                Let us design a program for you
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-3 pos">
              <a class="inactive open-step" data-target-step="currentAbility" data-change-flow="true" href="#"><!--data-toggle="collapse" data-parent="#accordion11" href="#collapseOne1" panel-collapse id="program_liv_first" -->
                <input type="image" class="gif_h_w" value="6" src="{{ asset('assets/plugins/fitness-planner/images/program_lib.gif') }}">
                <!--<p>-->
                <br>
                PROGRAM LIBRARY
                <br/>
                Choose an off the shelf pre design
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-3 pos">
              <a class="inactive open-step" data-target-step="designProgram" data-change-flow="true" href="#"><!--data-toggle="collapse" data-parent="#accordion11" panel-collapse id="program_work_first"  -->
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
      </div>
      <!-- end: PANEL BODY -->
    </div>
    <!---end of the what do you want to do -->

    <!---start of WHICH ACTIVITY DO YOU WANT -->
    <div class="panel panel-white" data-step="activityChoose">
      <!-- start: PANEL HEADING -->
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="fa fa-ellipsis-v"></i>
          </span>
          WHICH ACTIVITY DO YOU WANT
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
        <div class="panel-group item_class" data-name="Plan"><!--id="accordion211"-->
          <div class="row">
            <div class="col-md-3 pos">
              <a class="inactive open-step" data-target-step="activityHabits" href="#"><!--data-parent="#accordion211" data-toggle="collapse" panel-collapse id="program_gen_avt" -->
                <input type="image" class="gif_h_w" value="1" src="{{ asset('assets/plugins/fitness-planner/images/inc_strength.gif')}}">
                <!--<p>-->
                <br>
                INCREASE STRENGTH
              </a>
            </div>
            <div class="col-md-3 pos">
              <a class="inactive open-step" data-target-step="activityHabits" href="#"><!--data-toggle="collapse" data-parent="#accordion11" href="#collapseOne1" panel-collapse id="program_liv_first" -->
                <input type="image" class="gif_h_w" value="2" src="{{ asset('assets/plugins/fitness-planner/images/weight_loss.gif')}}">
                <!--<p>-->
                <br>
                WEIGHT LOSS / TONE
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-3 pos">
              <a class="inactive open-step" data-target-step="activityHabits" href="#"><!--data-toggle="collapse" data-parent="#accordion11" panel-collapse id="program_work_first"  -->
                <input type="image"  class="gif_h_w" src="{{  asset('assets/plugins/fitness-planner/images/general_health.gif')}}" value="3" class="mw-100p">
                <!--<p>-->
                <br>
                GENERAL HEALTH
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- end: PANEL BODY -->
    </div>
    <!---end of WHICH ACTIVITY DO YOU WANT -->

    <!---start of activity work -->
    <div class="panel panel-white" data-step="activityHabits"><!--step1 step2-->
      <!-- start: PANEL HEADING -->
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="fa fa-ellipsis-v"></i>
          </span>
          ACTIVITY HABITS 
          <span class="icon-group-right">
            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
              <i class="fa fa-wrench"></i>
            </a>
            <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="fitness-planner"><!--id="program_gen_avt_act"-->
              <i class="fa fa-chevron-down"></i>
            </a>
          </span>
        </h5>
      </div>
      <!-- end: PANEL HEADING -->
      <!-- start: PANEL BODY -->
      <div class="panel-body item_class"><!--select_activity_habit data-name="Habit"-->
        <h4>CURRENT PHYSICAL ACTIVITY HABITS</h4>
        <div class="item_class" data-name="Habit">
          <div class="row act">
            <div class="col-md-2 pos">
              <a href="#" class="inactive"><!--id="no_exercise" -->
                <input type="image" class="gif_h_w" value="3" src="{{ asset('assets/plugins/fitness-planner/images/no_exercise.gif')}}">
                <!--<p>--> 
                <br>
                NO EXERCISE
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#"><!--id="inactive" -->
                <input type="image" class="gif_h_w" value="2" src="{{ asset('assets/plugins/fitness-planner/images/moderate_exercise.gif')}}">
                <!--<p>-->
                <br>
                INFREQUENT EXERCISE
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="1"  class="imga gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/high_exercise.gif')}}">
                <!--<p>-->
                <br>
                REGULAR EXERCISE
                <!--</p>-->
              </a>
            </div>
          </div>
        </div>
        <hr>
        <h4>PREVIOUS PHYSICAL ACTIVITY HABITS</h4>
        <div class="item_class" data-name="Experience">
          <div class="row act">
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="1" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/no_exercise.gif')}}">
                <!--<p>-->
                <br> 
                A LITTLE
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="2" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/low_exercise.gif')}}">
                <!--<p>-->
                <br>
                SOME
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="3"  class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/moderate_exercise.gif')}}">
                <!--<p>-->
                <br>
                ALOT
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="4"  class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/freq_exercise.gif')}}">
                <!--<p>-->
                <br>
                INTERMEDIATE
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="5" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/high_exercise.gif')}}">
                <!--<p>-->
                <br>
                A TON
                <!--</p>-->
              </a>
            </div>
          </div>
        </div>
        <hr>
        <h4>WANNA BE PHYSICAL ACTIVITY HABITS</h4>
        <div class="item_class" data-name="Intensity">
          <div class="row act">
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="1" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/no_exercise.gif')}}">
                <!--<p>-->
                <br> 
                A LITTLE
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="2" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/low_exercise.gif')}}">
                <!--<p>-->
                <br>
                SOME
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="3" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/moderate_exercise.gif')}}">
                <!--<p>-->
                <br>
                ALOT
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="4" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/freq_exercise.gif')}}">
                <!--<p>-->
                <br>
                INTERMEDIATE
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" value="5" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/high_exercise.gif')}}">
                <!--<p>-->
                <br>
                A TON
                <!--</p>-->
              </a>
            </div>
          </div>
        </div>
        <hr>
        <h4>CURRENT INTENSITY OF PHYSICAL ACTIVITY</h4>
        <div class="item-class" data-name="temp"><!--data-name="Intensity"-->
          <div class="row act">
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/sedentary.gif')}}" value="1">
                <!--<p>-->
                <br>
                SENDATORY
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/light.png')}}" value="2">
                <!--<p>-->
                <br>
                LIGHT
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/light.png')}}" value="3">
                <!--<p>-->
                <br>
                MODERATE
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/light.png')}}" value="4">
                <!--<p>-->
                <br>
                VIGOROUS
                <!--</p>-->
              </a>
            </div>
            <div class="col-md-2 pos">
              <a class="inactive" href="#">
                <input type="image" src="{{ asset('assets/plugins/fitness-planner/images/light.png')}}" value="5">
                <!--<p>-->
                <br>
                HIGH
                <!--</p>-->
              </a>
            </div>
          </div>
        </div>
        <div class="text-right m-t-20">
          <button class="btn btn-primary btn-o btn-wide open-step" data-run-validation="true" data-target-step="currentAbility"><!--id="next_program_gen_avt"-->
            Next 
            <i class="fa fa-arrow-circle-right"></i>
          </button>
          <!--<button class="btn btn-primary" id="next_program_gen_avt">Next</button>-->
        </div>
      </div>
      <!-- end: PANEL BODY -->
    </div>
    <!---end of activity work-->
    
    <!---start of CURRENT ABILITY-->                        
    <div class="panel panel-white" data-step="currentAbility"><!--step1-->
      <!-- start: PANEL HEADING -->
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="fa fa-ellipsis-v"></i>
          </span>
          CURRENT ABILITY
          <span class="icon-group-right">
            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
              <i class="fa fa-wrench"></i>
            </a>
            <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="fitness-planner"> <!--id="current_ability"-->
              <i class="fa fa-chevron-down"></i>
            </a>
          </span>
        </h5>
      </div>
      <!-- end: PANEL HEADING -->
      <!-- start: PANEL BODY -->
      <div class="panel-body item_class" data-name="Habit"><!--data-name="select-current_ability"-->
        <div class="row">
          <div class="col-md-2 pos">
            <a class="inactive open-step" href="#" data-target-step="equipmentHave"><!--open-step-->
              <input type="image" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/rehabilitation.gif')}}" value="1"><!--value="1" -->
              <!--<p>-->
              <br>
              REHABILITATION
              <!--</p>-->
            </a>
          </div>
          <div class="col-md-2 pos">
            <a class="inactive open-step" href="#" data-target-step="equipmentHave"><!--data-toggle="collapse" data-parent="#accordion11"-->
              <input type="image" value="2" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/beginner.gif')}}">
              <!--<p>-->
              <br>
              BEGINNER
              <!--</p>-->
            </a>
          </div>
          <div class="col-md-2 pos">
            <a class="inactive open-step" href="#" data-target-step="equipmentHave"><!--data-toggle="collapse" data-parent="#accordion11"-->
              <input type="image" value="3" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/intermediate.gif')}}">
              <!--<p>-->
              <br>
              INTERMEDIATE
              <!--</p>-->
            </a>
          </div>
          <div class="col-md-2 pos">
            <a class="inactive open-step" href="#" data-target-step="equipmentHave"><!--data-toggle="collapse" data-parent="#accordion11" -->
              <input type="image" value="4" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/advanced.gif')}}">
              <!--<p>-->
              <br>
              ADVANCED
              <!--</p>-->
            </a>
          </div>
          <!--<div class="col-md-2"></div>-->
        </div>
      </div>
      <!-- end: PANEL BODY -->
    </div>
    <!---end of CURRENT ABILITY-->

    <!---start of WHAT EQUIPMENT DO YOU HAVE-->  
    
  
    <div class="panel panel-white" data-step="equipmentHave"><!--step1-->
      <!-- start: PANEL HEADING -->
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="fa fa-ellipsis-v"></i>
          </span>
          WHAT EQUIPMENT DO YOU HAVE
          <span class="icon-group-right">
            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
              <i class="fa fa-wrench"></i>
            </a>
            <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="fitness-planner"><!--id="equipment"-->
              <i class="fa fa-chevron-down"></i>
            </a>
          </span>
        </h5>
      </div>
      <!-- end: PANEL HEADING -->
      <!-- start: PANEL BODY -->
       
      <div class="panel-body item_class" data-name="Method">
        <div class="row">
              @foreach ($exerciseData['equipments'] as $key => $item)
          <div class="col-md-2 pos" data-equip="gym">
            <a class="inactive open-step" href="#" data-target-step="personalInfo"><!--data-toggle="collapse" data-parent="#accordion11" -->
              <input type="image" value="{{ $key }}" class="gif_h_w" src="{{ asset('assets/plugins/fitness-planner/images/beginner.gif')}}">
              <!--<p>-->
              <br>
             {{$item}}
              <!--</p>-->
            </a>
          </div>
           @endforeach
     
        </div>
      </div>
     
    
 
      <!-- end: PANEL BODY -->
    </div>
    <!---end of WHAT EQUIPMENT DO YOU HAVE-->

    <!---start of WHICH PROGRAME DO YOU WANT-->                                 
    <div class="panel panel-white" id="program-want" data-step="programWant"><!--step1-->
      <!-- start: PANEL HEADING -->
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="fa fa-ellipsis-v"></i>
          </span>
          WHICH PROGRAME DO YOU WANT
          <span class="icon-group-right">
            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
              <i class="fa fa-wrench"></i>
            </a>
            <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="fitness-planner"><!--id="equipment"-->
              <i class="fa fa-chevron-down"></i>
            </a>
          </span>
        </h5>
      </div>
      <!-- end: PANEL HEADING -->
      <!-- start: PANEL BODY -->
      <div class="panel-body">
        <div class="panel-group item_class" data-name="FixedProgramId"> <!--id="program_12wk"-->
          
        </div>
        
      </div>
      <!-- end: PANEL BODY -->
    </div>
    <!---end of WHICH PROGRAME DO YOU WANT-->
    
    <!-- start of program design -->
    <div class="panel panel-white" data-step="designProgram"><!--id="program_design"-->
      <!-- start: PANEL HEADING -->
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="fa fa-ellipsis-v"></i>
          </span>
          DESIGN A PROGRAM
          <span class="icon-group-right">
            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
              <i class="fa fa-wrench"></i>
            </a>
            <a class="btn btn-xs pull-right panel-collapse closed" data-panel-group="fitness-planner" href="#" id="getCustomPlans"><!--id="program_design_addon" -->
              <i class="fa fa-chevron-down"></i>
            </a>
          </span>
        </h5>
      </div>
      <!-- end: PANEL HEADING -->
      <!-- start: PANEL BODY -->
      <div class="panel-body">
        <div class="panel-group" style="display: block;"><!--id="accordion211"-->
          <p>
            Design your custom made program using this simple program design tool. Follow the steps below to get started: 
            <br>
            Create Program name or choose existing program to edit, Choose the training segments that you require for your routine, Choose the exercise or stretches for each segment, Confirm program length and days you choose to train, Click design and follow your program and achieve EPIC Results.
          </p>
          <div class="item"><!--id="sliderIte-custom" sliderIte fi-plan-program-->
            <!--<div class="itemHadr" data-name="FixdProramId">
              <span></span>
            </div>-->
            <div><!--class="itemBoy"-->
              <div class="pts"><!--cotent_box pg_width-->
                <!--<div class="left"></div><!--mob_vie-->
                <div class="program_design m-b-20">
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
                        <th>PROGRAM TITLE</th><!--class="w70"-->
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
      <!-- end: PANEL BODY -->
    </div>
    <!--- end of program design -->

    <!-- Start of Select video -->
    <div class="panel panel-white" data-step="acticityVideo"><!--id="program_design"-->
      <!-- start: PANEL HEADING -->
      <div class="panel-heading">
        <h5 class="panel-title">
          <span class="icon-group-left">
            <i class="fa fa-ellipsis-v"></i>
          </span>
          SELECT A VIDEO
          <span class="icon-group-right">
            <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
              <i class="fa fa-wrench"></i>
            </a>
            <a class="btn btn-xs pull-right panel-collapse closed" data-panel-group="fitness-planner" href="#"><!--id="program_design_addon" -->
              <i class="fa fa-chevron-down"></i>
            </a>
          </span>
        </h5>
      </div>
      <!-- end: PANEL HEADING -->
      <!-- start: PANEL BODY -->
      <div class="panel-body">
        <div class="panel-group" style="display: block;"><!--id="accordion211"-->
          <div class="item"><!--id="sliderIte-custom" sliderIte fi-plan-program-->
            <div><!--class="itemBoy"-->
              <div class="pts"><!--cotent_box pg_width-->
                <div>
                  <table class="table table-striped table-bordered table-hover m-t-10 my_pt" id="acticity-video-datatable" width="100%">
                    <thead>
                      <tr>
                        <th>VIDEO TITLE</th><!--class="w70"-->
                        <th>DATE CREATED</th>
                        <th class="center">ACTIONS</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end: PANEL BODY -->
    </div>
    <!-- End of Selct video -->

    <!--Start: plans design -->
      @include('includes.partials.activities_planner_helper',['parq'=>$parq])
    <!--End: plan design -->

    <!---start of schedule program -->
    <div id="scheduler" class="hidden">
      <div class="panel panel-white"> <!--id="program_design"-->
        <!-- start: PANEL HEADING-->
        <div class="panel-heading">
          <h5 class="panel-title">
            <span class="icon-group-left">
              <i class="fa fa-ellipsis-v"></i>
            </span>
            SCHEDULE YOUR PROGRAM
            <span class="icon-group-right">
              <a class="btn btn-xs pull-right" href="#" data-toggle="modal" data-target="#configModal">
                <i class="fa fa-wrench"></i>
              </a>
              <a class="btn btn-xs pull-right panel-collapse closed progrme_s" data-panel-group="fitness-planner" href="#">
                <i class="fa fa-chevron-down"></i>
              </a>
            </span>
          </h5>
        </div>
        <!-- end: PANEL HEADING -->
        <!-- start: PANEL BODY -->
        <div class="panel-body">
          <div class="panel-group"><!--id="accordion211"-->
            <p>
              Please choose the training segments that you require in your training routine, each segment relates to the different aspects of training and maintaining a balanced program related to an effective warm-up, cardiovascular, resistance, core, cooling down and stretching.
            </p>
            <div class="panel panel-default add_exercise">
              <div class="panel-heading clearfix">
                <button class="btn btn-default pull-right btn-sm" id="sdu_next" style="margin-bottom: 3px; padding: 3px; margin-left: 10px; width:100px;">
                  next
                </button>
                &nbsp;
                <button class="btn btn-default pull-right btn-sm" id="again_back" style="margin-bottom: 3px; padding: 3px;  margin-left: 10px; width:100px;">
                  back
                </button>
              </div>
            </div>
                                                                                                  
            <div class="panel panel-default add_exercise">
              <div class="panel-heading">
                <h3 class="panel-title">Please choose training plan duration</h3>
              </div>
              <div class="panel-body">
                <div id="traingPlannerMain" class="plannerContainer fit-ui" style="padding:10px; margin-bottom: 20px;">
                  <div id="sliderIt" class="sliderIte itema">
                    <span id="weekSelection">
                      <div class="itemBody">
                        <div class="ui-slider-wrapper ui-widget horizontal" style="font-size: 13px; width: 1080px;" aria-disabled="false">
                          <div class="ui-slider-labels">
                            <div class="ui-slider-label-ticks ui-slider-label-first" style="left: 0%;">
                              <span>1</span>
                            </div>
                            <div class="ui-slider-label-ticks" style="left: 20%;">
                              <span>2</span>
                            </div>
                            <div class="ui-slider-label-ticks" style="left: 40%;">
                              <span>3</span>
                            </div>
                            <div class="ui-slider-label-ticks" style="left: 60%;">
                              <span>4</span>
                            </div>
                            <div class="ui-slider-label-ticks" style="left: 80%;">
                              <span>5</span>
                            </div>
                            <div class="ui-slider-label-ticks ui-slider-label-last" style="left: 100%;">
                              <span>6</span>
                            </div>
                          </div>
                          <div class="ui-slider ui-slider-horizontal ui-widget-content ui-corner-all"><!--id="weekSlider" -->
                            <div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 100%;"></div>
                            <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 100%;"></a>
                          </div>
                        </div>
                      </div>
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!--<br/><br/>-->
            <div class="panel panel-default add_exercise">
              <div class="panel-heading">
                <h3 class="panel-title">Please choose training days</h3>
                <!--<div class="pull-right"><button class="btn btn-default  btn-sm ">DROP DOWN</button></div>-->
              </div>
              <div class="panel-body">
                <span id="weekSelectionq">
                  <div class="itemBody">
                    <div></div><!--id="weekSlider"-->
                  </div>
                </span>
              </div>
            </div>

            <div>
              <input type="button" value="Create" id="doneButton" class="btn btn-default addButton ui-button ui-widget ui-state-default"  role="button" aria-disabled="false" style="width:">
                <div style="display: none" class="fit_saveplan_ajax"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end of schedule program -->
  </div>
  <!---end of my program-->
</div>
<!--</div>-->






