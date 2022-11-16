@extends('layouts.app')

@section('meta_description')
@stop()

@section('meta_author')
@stop()

@section('meta')
@stop()

@section('before-styles-end')

@stop()

@section('required-styles-for-this-page')

{!! Html::style('vendor/bootstrap-select-master/css/bootstrap-select.min.css?v='.time()) !!}
{!! Html::style('assets/css/goal-buddy.css?v='.time()) !!}

@stop()

@section('page-title')
   
@stop

@section('content')
<div class="page-header">
  <h1>Goal Buddy</h1>
</div>

<!-- start: acc1 -->

<div class="panel panel-white"> 
  <!-- start: PANEL HEADING -->
  <div class="panel-heading">
    <h5 class="panel-title"> <span class="icon-group-left"> <i class="fa fa-ellipsis-v"></i> </span> Build New Habits <span class="icon-group-right"> <a class="btn btn-xs pull-right" href="#" data-toggle="collapse" data-target="#build_habit"> <i class="fa fa-wrench"></i> </a> <a class="btn btn-xs pull-right panel-collapse closed" href="#"> <i class="fa fa-chevron-down"></i> </a> </span> </h5>
  </div>
  <!-- end: PANEL HEADING --> 
  <!-- start: PANEL BODY -->
  <div class="panel-body collapse in" id="build_habit">
    <div class="col-md-12">
      <fieldset class="padding-15">
        <legend>Build New Habits</legend>
        <form id="build_new_habit_form" method="POST">
          <div class="col-md-6">
            <div class="form-group">
              <label for="habit" class="strong">Name Your Habit  *</span></label>
              <input type="text" class="form-control" id="habit" value="" name="habit" required>
            </div>
            <div class="form-group">
              <label for="benefit_affect_life" class="strong">How Will this Habit Affect My Life? *</span></label>
              <select id="benefit_affect_life" name="benefit_affect_life" class="form-control selectpicker onchange-set-neutral ">
                <option value="">-- Select --</option>
                <option value="">I will become a better community member</option>
                <option value="">I'll have better relationships</option>
                <option value="">My business/career will be better off </option>
                <option value="">My family will be better off </option>
                <option value="">My finances will improve</option>
                <option value="">My health will improve</option>
                <option value="">My life will be spiritually enriched </option>
                <option value="">My lifestyle will improve</option>
                <option value="">Other</option>
              </select>
            </div>
            <div class="form-group">
              <label class="strong">Habit Recurrence *</label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="habit_recurrence" id="habit_recurrence0" value="">
                  <label for="habit_recurrence0"> Daily </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="habit_recurrence" id="habit_recurrence1" value="">
                  <label for="habit_recurrence1"> Weekly </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="habit_recurrence" id="habit_recurrence2" value="">
                  <label for="habit_recurrence2"> Monthly </label>
                </div>
              </div>
              <p class="error_radio help-block">This field is required</p>
            </div>
            <div class="form-group">
              <label class="strong" for="notes">Why is this habit important to me? </label>
              <div>
                <textarea class="form-control" rows="10" cols="50" id="notes" name="notes"></textarea>
              </div>
            </div>
            <button class="btn btn-primary  btn-wide pull-right margin-right-15" id="create_habbit_btn" data-step="1"> Establish a Habit </button>
          </div>
          <div class="col-md-6">
            <div class="row define_habit">
              <h5> <span class="glyphicon glyphicon-question-sign question-sign"></span> Don't know how to define your habit?</h5>
              <button type="button" class="btn btn-info btn_demo" data-toggle="collapse" data-target="#demo">Choose one from a template</button>
              <div id="demo" class="collapse">
                <ul class="nav nav-tabs nav_goal_buddy">
                  <li class="active"><a href="#">Habit Templates</a></li>
                </ul>
                <br />
                <ul>
                  <li><a>I will become a better</br>
                    community member</a></li>
                  <li><a>I will become a better</br>
                    community member</a></li>
                </ul>
              </div>
            </div>
            <br />
            <br />
            <div class="form-group">
              <label class="strong">Who can see this habit? </label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="see_habit" id="see_habit0" value="">
                  <label for="see_habit0"> Everyone </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="see_habit" id="see_habit1" value="">
                  <label for="see_habit1"> Just Me </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="strong">Send e-mail / SMS reminders </label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="send_msg" id="send_msg0" value="">
                  <label for="send_msg0"> Only if I am late </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="send_msg" id="send_msg1" value="">
                  <label for="send_msg1"> Every occurrence </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="send_msg" id="send_msg2" value="">
                  <label for="send_msg2"> None </label>
                </div>
              </div>
            </div>
          </div>
        </form>
      </fieldset>
    </div>
    <!-- end: PANEL BODY --> 
  </div>
</div>
<!-- end: acc1 --> 
<!-- start: acc2 -->
<div class="panel panel-white"> 
  <!-- start: PANEL HEADING -->
  <div class="panel-heading">
    <h5 class="panel-title"> <span class="icon-group-left"> <i class="fa fa-ellipsis-v"></i> </span> Set Your Goals <span class="icon-group-right"> <a class="btn btn-xs pull-right" href="#" data-toggle="collapse" data-target="#set_goal"> <i class="fa fa-wrench"></i> </a> <a class="btn btn-xs pull-right panel-collapse closed" href="#" data-panel-group="epic-process"> <i class="fa fa-chevron-down"></i> </a> </span> </h5>
  </div>
  <!-- end: PANEL HEADING --> 
  <!-- start: PANEL BODY -->
  <div class="panel-body" id="set_goal">
    <form  class="smart-wizard" id="form">
      <!--form-horizontal-->
      
      <div id="wizard" class="swMain parqForm">
        <ul>
          <li> <a href="#step-1S">
            <div class="stepNumber"> 1 </div>
            <span class="stepDesc"><small>DEFINE YOUR GOAL</small></span> </a> </li>
          <li> <a href="#step-2S">
            <div class="stepNumber"> 2 </div>
            <span class="stepDesc"><small>ESTABLISH NEW HABITS</small></span> </a> </li>
          <li> <a href="#step-3S">
            <div class="stepNumber"> 3 </div>
            <span class="stepDesc"><small>CREATE TASKS</small></span> </a> </li>
          <li> <a href="#step-4S">
            <div class="stepNumber"> 4 </div>
            <span class="stepDesc"><small>SMART REVIEW</small></span> </a> </li>
          <li> <a href="#step-5S">
            <div class="stepNumber"> 5 </div>
            <span class="stepDesc"><small>FIND GOAL BUDDIES</small></span> </a> </li>
        </ul>
        <!-- start: WIZARD STEP 1 -->
        <div id="step-2S">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="name_goal" class="strong">Name Your Goal  *</span></label>
                <input type="text" class="form-control" id="name_goal" value="" name="name_goal">
              </div>
              <div class="form-group">
                <label class="strong" for="describe_achieve">Describe what you want to achieve </label>
                <div>
                  <textarea class="form-control" rows="10" cols="10" id="describe_achieve" name="describe_achieve"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="strong">Is This a Top Goal for This Year? (maximum 3) </label>
                <div>
                  <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input type="radio" name="goal_year" id="goal_year0" value="">
                    <label for="goal_year0"> Yes </label>
                  </div>
                  <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input type="radio" name="goal_year" id="goal_year1" value="">
                    <label for="goal_year1"> No </label>
                  </div>
                </div>
                <p class="error_radio help-block">This field is required</p>
              </div>
              <div class="form-group">
                <label for="change_life" class="strong">How Will My Life Change When I Accomplish This Goal?  *</span></label>
                <select id="change_life" name="change_life" class="form-control selectpicker onchange-set-neutral">
                  <option value="">-- Select --</option>
                  <option value="">I will become a better community member</option>
                  <option value="">I'll have better relationships</option>
                  <option value="">My business/career will be better off </option>
                  <option value="">My family will be better off </option>
                  <option value="">My finances will improve</option>
                  <option value="">My health will improve</option>
                  <option value="">My life will be spiritually enriched </option>
                  <option value="">My lifestyle will improve</option>
                  <option value="">Other</option>
                </select>
              </div>
                <div class="form-group">
                  <label class="strong" for="Accomplish">Why is it Important to Accomplish? What Happens if I Fail? </label>
                  <div>
                    <textarea class="form-control" rows="10" cols="10" id="Accomplish" name="Accomplish"></textarea>
                  </div>
                </div>
              <div class="form-group">
                <label class="strong" for="Milestones">What Milestones I've Got to Accomplish Before I Reach My Goal? </label>
                <div>
                  <input type="text" class="form-control" id="Milestones" value="" name="Milestones">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row define_habit">
                <h5> <span class="glyphicon glyphicon-question-sign question-sign"></span> Need help with creating a task?</h5>
                <button type="button" class="btn btn-info btn_demo" data-toggle="collapse" data-target="#demo3">Choose one from a template</button>
                <div id="demo3" class="collapse">
                  <ul class="nav nav-tabs nav_goal_buddy">
                    <li class="active"><a href="#">Habit Templates</a></li>
                  </ul>
                  <br />
                  <ul>
                    <li>My business/career will<br />
                      be better off </li>
                  </ul>
                </div>
              </div>
              <br />
              <br />
              <div class="form-group">
                <label class="strong" for="date-time">Does it have a due date/time? </label>
                <div class="row">
                  <div class="col-md-6"> 
                    
                    <input type='text' class="form-control" id='datetimepicker4' />
                    <div class="input-group date">
                      <input type="text" class="form-control" value="12-02-2012" id='datetimepicker4'>
                      <div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span> </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group radio_goal_buddy">
                <label class="strong">Who can see this goal? </label>
                <div>
                  <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input type="radio" name="see_goal" id="see_goal0" value="">
                    <label for="see_goal0"> Everyone </label>
                  </div>
                  <div class="radio clip-radio radio-primary radio-inline m-b-0">
                    <input type="radio" name="see_goal" id="see_goal1" value="">
                    <label for="see_goal1"> Just Me </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="strong">Send e-mail / SMS reminders </label>
                  <div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                      <input type="radio" name="send_msgss" id="send_msgss1" value="">
                      <label for="send_msgss1">When Overdue</label>
                    </div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                      <input type="radio" name="send_msgss" id="send_msgss2" value="">
                      <label for="send_msgss2"> Daily </label>
                    </div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                      <input type="radio" name="send_msgss" id="send_msgss3" value="">
                      <label for="send_msgss3"> Weekly </label>
                    </div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                      <input type="radio" name="send_msgss" id="send_msgss4" value="">
                      <label for="send_msgss4"> Monthly </label>
                    </div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                      <input type="radio" name="send_msgss" id="send_msgss5" value="">
                      <label for="send_msgss5"> None </label>
                    </div>
                  </div>
                </div>
              </div>
            <div class="form-group">
				<button class="btn btn-primary btn-o next-step btn-wide pull-right">
					Next <i class="fa fa-arrow-circle-right"></i>
				</button>
			</div>
            </div>
          </div>
        </div>
        <!-- end: WIZARD STEP 1 -->
        <div id="step-3S">
          <div class="col-md-6">
            <div class="form-group">
              <label for="SYG_habits" class="strong">Name Your Habit  *</span></label>
              <input type="text" id="SYG_habits" value="" name="SYG_habits">
            </div>
            <div class="form-group">
              <label for="SYG_benefit_affect_life" class="strong">How Will this Habit Affect My Life? *</span></label>
              <select id="SYG_benefit_affect_life" name="SYG_benefit_affect_life" class=" selectpicker onchange-set-neutral ">
                <option value="">-- Select --</option>
                <option value="">I will become a better community member</option>
                <option value="">I'll have better relationships</option>
                <option value="">My business/career will be better off </option>
                <option value="">My family will be better off </option>
                <option value="">My finances will improve</option>
                <option value="">My health will improve</option>
                <option value="">My life will be spiritually enriched </option>
                <option value="">My lifestyle will improve</option>
                <option value="">Other</option>
              </select>
            </div>
            <div class="form-group">
              <label class="strong">Habit Recurrence *</label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="SYG_habit_recurrence" id="SYG_habit_recurrence0" value="">
                  <label for="SYG_habit_recurrence0"> Daily </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="SYG_habit_recurrence" id="SYG_habit_recurrence1" value="">
                  <label for="SYG_habit_recurrence1"> Weekly </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="SYG_habit_recurrence" id="SYG_habit_recurrence2" value="">
                  <label for="SYG_habit_recurrence2"> Monthly </label>
                </div>
              </div>
              <p class="error_radio help-block">This field is required</p>
            </div>
            <div class="form-group">
              <label class="strong" for="SYG_notes">Why is this habit important to me? </label>
              <div>
                <textarea rows="10" cols="50" id="SYG_notes" name="SYG_notes"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="SYG_goal_associate" class="strong">Is there a goal associated with this habit?</span></label>
              <select id="SYG_goal_associate" name="SYG_goal_associate" class="selectpicker onchange-set-neutral ">
                <option value="">-- Select --</option>
                <option value="">I will become a better community member</option>
                <option value="">I'll have better relationships</option>
                <option value="">My business/career will be better off </option>
                <option value="">My family will be better off </option>
                <option value="">My finances will improve</option>
                <option value="">My health will improve</option>
                <option value="">My life will be spiritually enriched </option>
                <option value="">My lifestyle will improve</option>
                <option value="">Other</option>
              </select>
            </div>
            
          </div>
          <div class="col-md-6">
            <div class="row define_habit">
              <h5> <span class="glyphicon glyphicon-question-sign question-sign"></span> Don't know how to define your habit?</h5>
              <button type="button" class="btn btn-info btn_demo" data-toggle="collapse" data-target="#demo">Choose one from a template</button>
              <div id="demo" class="collapse">
                <ul class="nav nav-tabs nav_goal_buddy">
                  <li class="active"><a href="#">Habit Templates</a></li>
                </ul>
                <br />
                <ul>
                  <li><a>I will become a better</br>
                    community member</a></li>
                  <li><a>I will become a better</br>
                    community member</a></li>
                </ul>
              </div>
            </div>
            <br />
            <br />
            <div class="form-group">
              <label class="strong">Who can see this habit? </label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="see_habit" id="see_habit0" value="">
                  <label for="see_habit0"> Everyone </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="see_habit" id="see_habit1" value="">
                  <label for="see_habit1"> Just Me </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="strong">Send e-mail / SMS reminders </label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="send_msg" id="send_msg0" value="">
                  <label for="send_msg0"> Only if I am late </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="send_msg" id="send_msg1" value="">
                  <label for="send_msg1"> Every occurrence </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="send_msg" id="send_msg2" value="">
                  <label for="send_msg2"> None </label>
                </div>
              </div>
            </div>
            <button class="btn btn-primary  btn-wide pull-right margin-right-15" id="SYG_habbit_btns" data-step="1"> Establish a Habit </button>
          </div>
        </div>
        <!-- start: WIZARD STEP 2 --> 
        <div id="step-1S">
        <h3>What Do I Need To Do to Accomplish My Goal?</h3>
          <div class="col-md-6">
            <div class="form-group">
              <label for="SYG3_task" class="strong">Name Your task  *</span></label>
              <input type="text" id="SYG3_task" value="" name="SYG3_task" class="form-control">
            </div>
            <div class="form-group">
              <label for="SYG3_priority" class="strong">Priority   *</span></label>
              <select id="SYG3_priority" name="SYG3_priority" class="form-control selectpicker onchange-set-neutral ">
                <option value="">-- Select --</option>
                <option value="">Low</option>
                <option value="">Normal</option>
                <option value="">High </option>
                <option value="">Urgent</option>
               
              </select>
            </div>
            <div class="form-group">
                <label class="strong" for="SYG3_date-time">Does it have a due date/time? </label>
                <div class="row">
                  <div class="col-md-6"> 
                    
                   
                    <div class="input-group date">
                      <input type="text" class="form-control" value="12-02-2012" id='datetimepicker5'>
                      <div class="input-group-addon"> <span class="glyphicon glyphicon-th"></span> </div>
                    </div>
                  </div>
                </div>
              </div>
            
            
            
            
          </div>
          <div class="col-md-6">
           
            <div class="form-group">
              <label class="strong">Who can see this task? </label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="SYG3_see_task" id="SYG3_see_task0" value="">
                  <label for="SYG3_see_task0"> Everyone </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="SYG3_see_task" id="SYG3_see_task1" value="">
                  <label for="SYG3_see_task1"> My Friends </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="SYG3_see_task" id="SYG3_see_task2" value="">
                  <label for="SYG3_see_task2"> Just Me </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="strong">Send e-mail / SMS reminders </label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="SYG3_send_msg" id="SYG3_send_msg0" value="">
                  <label for="SYG3_send_msg0"> When task is overdue </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="SYG3_send_msg" id="SYG3_send_msg1" value="">
                  <label for="SYG3_send_msg1">When task is due </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="SYG3_send_msg" id="SYG3_send_msg2" value="">
                  <label for="SYG3_send_msg2"> None </label>
                </div>
              </div>
            </div>
            <div class="row">
		<div class="col-sm-6"></div>
		<div class="col-sm-3">
			
				<button class="btn btn-primary btn-o next-step btn-wide pull-right">
					Next <i class="fa fa-arrow-circle-right"></i>
				</button>
			</div>
		<div class="col-sm-3">
			
				
			</div>
		</div>
	</div>
        </div>
        <!-- end: WIZARD STEP 2 --> 
        <div id="step-5S">
        
          <div class="col-md-12">
            
            
            <div class="container Smart-review-session">
            <h5 class="text-center">Let's review your goal. Is your goal<span> S.M.A.R.T.?</span> * </h5>
            <br>
            <div class="col-md-11 col-md-offset-1 text-center ">
               <div class="col-md-2 text-center Smart-review-check">
                <div class="checkbox clip-check check-primary m-b-0">
					<input type="checkbox" name="Specific" id="Specific" value="1" checked="">
					<label for="Specific">
						<strong>Specific</strong> <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
					</label>
					
				</div>
                </div>
                   <div class="col-md-3 text-center Smart-review-check">
                <div class="checkbox clip-check check-primary m-b-0">
					<input type="checkbox" name="Measurable" id="Measurable" value="1" checked="">
					<label for="Measurable">
						<strong>Measurable  </strong> <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
					</label>
					
				</div>
                </div>
                 <div class="col-md-3 text-center Smart-review-check">
                <div class="checkbox clip-check check-primary m-b-0">
					<input type="checkbox" name="Attainable" id="Attainable" value="1" checked="">
					<label for="Attainable">
						<strong>Attainable   </strong> <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
					</label>
					
				</div>
                </div>
                 <div class="col-md-2 text-center Smart-review-check">
                <div class="checkbox clip-check check-primary m-b-0">
					<input type="checkbox" name="Relevant" id="Relevant" value="1" checked="">
					<label for="Relevant">
						<strong> Relevant</strong> <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
					</label>
					
				</div>
                </div>
                 <div class="col-md-2 text-center Smart-review-check">
                <div class="checkbox clip-check check-primary m-b-0">
					<input type="checkbox" name="Time-Bound" id="Time-Bound" value="1" checked="">
					<label for="Time-Bound">
						<strong>Time-Bound</strong> <span class="epic-tooltip tooltipstered" data-toggle="tooltip"></span>
					</label>
					
				</div>
                </div>
                </div>
                
                <div class="col-md-12  show_task-section">
             
                 
                  <div class="col-md-6  task_declare">
                 <h4>122 <span class="glyphicon glyphicon-pencil pencil-edit" style="font-size:12px"></span></h4> 
                 
                 <p><strong> How will my life change:</strong></p>
                 <p>My health will improve</p>
                  </div>
                  <div class="col-md-6 text-center task_declare">
                 <p><strong>Shared: Everyone</strong></p> 
                 
                
                </div>
                </div>
                <div class="col-md-7">
                <div class="show_task-section">
             
                 
                 
                 <h4>Milestones (tasks): <span class="glyphicon glyphicon-pencil pencil-edit" style="font-size:12px"></span></h4>


                 
                 <ul>
                 <li>12</li>
                 </ul>
                 </div>
                 
                
              
                </div>
                <div class="col-md-5">
                <div class="show_task-section">
             
                 
                 
                 <h4>Habits: <span class="glyphicon glyphicon-pencil pencil-edit" style="font-size:12px"> </span></h4>

 
                 
                 <ul>
                 <li>12312</li>
                 </ul>
                 </div>
                 
                
              
                </div>
                
            </div>
            <br />
            <br />
            
            <div class="row">
		<div class="col-sm-6"></div>
		<div class="col-sm-3">
			
				<button class="btn btn-primary btn-o next-step btn-wide pull-right">
					Next <i class="fa fa-arrow-circle-right"></i>
				</button>
			</div>
		<div class="col-sm-3">
			
				
			</div>
		</div>
          </div>
          
        </div>
        <!-- start: WIZARD STEP 3 --> 
        
      </div>
    </form>
  </div>
  <!-- end: PANEL BODY --> 
</div>
<!-- end: acc2 --> 
<!-- start: acc3 -->
<div class="panel panel-white"> 
  <!-- start: PANEL HEADING -->
  <div class="panel-heading">
    <h5 class="panel-title"> <span class="icon-group-left"> <i class="fa fa-ellipsis-v"></i> </span> Build New Habits <span class="icon-group-right"> <a class="btn btn-xs pull-right" href="#" data-toggle="collapse" data-target="#manage_build"> <i class="fa fa-wrench"></i> </a> <a class="btn btn-xs pull-right panel-collapse closed" href="#"> <i class="fa fa-chevron-down"></i> </a> </span> </h5>
  </div>
  <!-- end: PANEL HEADING --> 
  <!-- start: PANEL BODY -->
  <div class="panel-body collapse in" id="manage_build">
    <div class="col-md-12">
      <fieldset class="padding-15">
        <legend>Manage your tasks</legend>
        <form id="manage_task_form" method="POST">
          <div class="col-md-6">
            <div class="form-group">
              <label for="task" class="strong">Name Your task  *</span></label>
              <input type="text" class="form-control" id="task" value="" name="task" required>
            </div>
            <div class="form-group">
              <label for="compleing_task" class="strong">How Will Compleing this Task Affect My Life?   *</span></label>
              <select id="compleing_task" name="compleing_tasks" class="selectpicker form-control" required>
                <option value="">-- Select --</option>
                <option value="">I will become a better community member</option>
                <option value="">I'll have better relationships</option>
                <option value="">My business/career will be better off </option>
                <option value="">My family will be better off </option>
                <option value="">My finances will improve</option>
                <option value="">My health will improve</option>
                <option value="">My life will be spiritually enriched </option>
                <option value="">My lifestyle will improve</option>
                <option value="">Other</option>
              </select>
            </div>
            <div class="form-group">
              <label for="Priority" class="strong">How Will Compleing this Task Affect My Life?   *</span></label>
              <select id="Priority" name="Priority" class="selectpicker onchange-set-neutral form-control" required>
                <option value="">-- Select --</option>
                <option value="">I will become a better community member</option>
                <option value="">I'll have better relationships</option>
                <option value="">My business/career will be better off </option>
                <option value="">My family will be better off </option>
                <option value="">My finances will improve</option>
                <option value="">My health will improve</option>
                <option value="">My life will be spiritually enriched </option>
                <option value="">My lifestyle will improve</option>
                <option value="">Other</option>
              </select>
            </div>
            <button class="btn btn-primary  btn-wide pull-right margin-right-15" id="manage_task_btn" data-step="1"> Establish a Habit </button>
          </div>
          <div class="col-md-6">
            <div class="row define_habit">
              <h5> <span class="glyphicon glyphicon-question-sign question-sign"></span> Need help with creating a task?</h5>
              <button type="button" class="btn btn-info btn_demo" data-toggle="collapse" data-target="#demo1">Choose one from a template</button>
              <div id="demo1" class="collapse">
                <ul class="nav nav-tabs nav_goal_buddy">
                  <li class="active"><a href="#">Habit Templates</a></li>
                </ul>
                <br />
                <ul>
                  <li><a>I will become a better</br>
                    community member</a></li>
                  <li><a>I will become a better</br>
                    community member</a></li>
                </ul>
              </div>
            </div>
            <br />
            <br />
            <div class="form-group">
              <label class="strong">Who can see this task? </label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="see_task" id="see_task0" value="">
                  <label for="see_task0"> Everyone </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="see_task" id="see_task1" value="">
                  <label for="see_task1"> Just Me </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="strong">Send e-mail / SMS reminders </label>
              <div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="send_msgs" id="send_msgs0" value="">
                  <label for="send_msgs0"> Only if I am late </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="send_msgs" id="send_msgs1" value="">
                  <label for="send_msgs1"> Every occurrence </label>
                </div>
                <div class="radio clip-radio radio-primary radio-inline m-b-0">
                  <input type="radio" name="send_msgs" id="send_msgs2" value="">
                  <label for="send_msgs2"> None </label>
                </div>
              </div>
            </div>
          </div>
        </form>
      </fieldset>
    </div>
    <!-- end: PANEL BODY --> 
  </div>
</div>
<!-- end: acc3 --> 



@stop

@section('required-script-for-this-page')
{!! Html::script('assets/plugins/jquery-validation/dist/jquery.validate.js') !!}
{!! Html::script('assets/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}
{!! Html::script('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') !!}
{!! Html::script('vendor/bootstrap-select-master/js/bootstrap-select.min.js') !!}
{!! Html::script('assets/js/form-wizard-goal-buddy.js?v='.time()) !!}

{!! Html::script('assets/js/helper.js?v='.time()) !!}
{!! Html::script('assets/js/goal-buddy.js?v='.time()) !!}

@stop()

@section('script-handler-for-this-page')
@stop()

@section('script-after-page-handler')
@stop() 