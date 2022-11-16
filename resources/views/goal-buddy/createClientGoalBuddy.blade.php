<div class="panel panel-white @if(isset($goalListData) && $goalListData->count() > 0) hidden @endif" id="goalbuddy-crate-section"> 
  <div class="panel-body" >
    <form action="#" role="form" class="smart-wizard" id="goal-buddy-form">
      <input type="hidden" name="goalClientId" value="{{ $clients->id }}" />
      <input type="hidden" name="goalClientName" value="{{ $clients->firstname }} {{ $clients->lastname }}" />
      <div id="set_goal" > 
        <!--form-horizontal-->
        <div id="goalBuddyWizard" class="swMain goal-buddy-wizard parqForm">
          <ul id="wizard-ul" class="top-step">
            <li>
              <a href="#step-1">
                <div class="stepNumber"> 1 </div>
                <span class="stepDesc"><small>DEFINE YOUR GOAL</small></span> 
              </a>
            </li>
            <li> 
              <a href="#step-2">
                <div class="stepNumber"> 2 </div>
                <span class="stepDesc"><small>ESTABLISH YOUR MILE STONES</small></span> 
              </a> 
            </li> 
            <li> 
              <a href="#step-3">
                <div class="stepNumber"> 3 </div>
                <span class="stepDesc"><small>ESTABLISH NEW HABITS</small></span> 
              </a> 
            </li>
            <li> 
              <a href="#step-4">
                <div class="stepNumber"> 4 </div>
                <span class="stepDesc"><small>CREATE TASKS</small></span> 
              </a> 
            </li>
            <li> 
              <a href="#step-5">
                <div class="stepNumber"> 5 </div>
                <span class="stepDesc"><small>SMART REVIEW</small></span> 
              </a> 
            </li>
          </ul>

          <!-- start: WIZARD STEP 1 -->
          <div id="step-1">
            @include('goal-buddy.creategoal')
            <div class="row">
              <div class="col-sm-2 col-md-offset-10">
                <button class="btn btn-primary btn-o btn-wide submit-first-form" style="display:none;">Set a goal</button>
                <span id="chakra" style="display:none;">Please wait..</span>
                <button class="btn btn-primary btn-o btn-wide pull-right goal-buddy-wizard-next-step first-form-next"> Next <i class="fa fa-arrow-circle-right"></i> </button>
              </div>
            </div>
          </div>
          <!-- end: WIZARD STEP 1 --> 

          <!-- start: WIZARD STEP 2 -->
          <div id="step-2">
            <div class="row milestone-form">
              <h4 class ="p-l-15"><em>What milestones do you need top accomplish to achieve your goals?</em></h4>
              @include('goal-buddy.createmilestone')
            </div>
            <div class="row">
              <div class="col-sm-6">
                <button class="btn btn-primary btn-o goal-buddy-wizard-back-step btn-wide pull-left"> <i class="fa fa-arrow-circle-left"></i> Back </button>
              </div>
              <div class="col-sm-6 ">
                <div class="form-group">
                  <button class="btn btn-primary btn-o btn-wide pull-right goal-buddy-wizard-next-step "> Next <i class="fa fa-arrow-circle-right"></i></button>

                  <button class="btn btn-red btn-o btn-wide pull-right margin-right-15 cancel_milestone_btn hidden" id="cancel_milestone" type="button"> Cancel </button>
                </div>
              </div>
            </div>
          </div>
          <!-- end: WIZARD STEP 2 -->

          <!-- start: WIZARD STEP 3 -->
          <div id="step-3">
            <div class="row habit-listing">
              <div class ="row">
                <div class ="col-md-12 sucMsgDisp"></div> <!-- Success message display -->
                <div class ="col-md-6 col-xs-6">
                  <h4><em>What Habits Do I need to Develop to Accomplish This Goal?</em></h4>
                </div>
                <div class ="col-md-6 col-xs-6"> 
                  <a class ="add-habit pull-right">+Establish New Habit</a> 
                </div>
              </div>
              <table class="table table-striped table-bordered table-hover m-t-10 " id="client-datatable">
                <thead>
                  <tr>
                    <th>Habit Name</th>
                    <th>Frequency</th>
                    <th class="hidden-xxs">Milestone</th>
                    <th class="hidden-xs">Shared</th>
                    <th class="center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="row habit-form">
              <h4 class ="p-l-15"><em>What behaviours do you need to create to achieve your goal?</em></h4>
              @include('goal-buddy.createhabits')
            </div>
            <div class="row">
              <div class="col-sm-6">
                <button class="btn btn-primary btn-o goal-buddy-wizard-back-step btn-wide pull-left"> <i class="fa fa-arrow-circle-left"></i> Back </button>
              </div>
              <div class="col-sm-6 ">
                <div class="form-group">
                  <button class="btn btn-primary btn-o btn-wide pull-right goal-buddy-wizard-next-step "> Next <i class="fa fa-arrow-circle-right"></i> </button>

                  <button class="btn btn-red btn-o btn-wide pull-right margin-right-15 cancel_habbit_btn hidden" id="cancel_habbit" type="button"> Cancel </button>
                </div>
              </div>
            </div>
          </div>
          <!-- end: WIZARD STEP 3 --> 

          <!-- start: WIZARD STEP 4 -->
          <div id="step-4">
            <div class="row task-listing">
              <div class ="row">
                <div class ="col-md-12 task-name-sucMsgDisp"></div> <!-- Success message display -->
                <div class ="col-md-6 col-xs-6">
                  <h4><em>Would you like to establish another task?</em></h4>
                </div>
                <div class ="col-md-6 col-xs-6"> 
                  <a class ="add-task pull-right">+Schedule New Task</a>
                </div>
              </div>
              <table class="table table-striped table-bordered table-hover m-t-10 " id="client-datatable-task">
                <thead>
                  <tr>
                    <th>Task Name</th>
                    <th>Priority</th>
                    <!-- <th class="hidden-xxs">Due Date</th> -->
                    <!-- <th class="hidden-xxs">Habit</th> -->
                    <th class="hidden-xs">Shared</th>
                    <th class="center">Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <div class="task-form">
            @include('goal-buddy.createtask')
            </div>
            <div class="row">
              <div class="col-sm-6">
                <button class="btn btn-primary btn-o goal-buddy-wizard-back-step btn-wide pull-left"> <i class="fa fa-arrow-circle-left"></i> Back </button>
              </div>
              <div class="col-sm-6 ">
                <button class="btn btn-primary btn-o btn-wide pull-right goal-buddy-wizard-next-step "> Next <i class="fa fa-arrow-circle-right"></i> </button>

                <button class="btn btn-red btn-o btn-wide pull-right margin-right-15 cancel_task_btn hidden" id="cancel_task" type="button"> Cancel </button>
              </div>
            </div>
          </div>
          <!-- end: WIZARD STEP 4 --> 

          <!-- start: WIZARD STEP 5 -->
          <div id="step-5" class="smart-review">
            @include('goal-buddy.smartreview')
            <div class="row">
              <div class="col-sm-6">
                <!-- <button class="btn btn-primary btn-o goal-buddy-wizard-back-step btn-wide pull-left"> <i class="fa fa-arrow-circle-left"></i> Back </button> -->
              </div>
              <div class="col-sm-6">
                <button class="btn btn-primary btn-o btn-wide pull-right final-step-goalbuddy_edit checkboxex"> Finish <i class="fa fa-arrow-circle-right"></i> </button>
              </div>
            </div>
          </div>
          <!-- end: WIZARD STEP 5 --> 
          
        </div>
        <!-- end: PANEL BODY --> 
      </div>
    </form>
  </div>
</div>