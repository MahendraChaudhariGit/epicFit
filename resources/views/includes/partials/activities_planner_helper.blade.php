
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
    </h5> </div>
	<!-- end: PANEL HEADING -->
	<!-- start: PANEL BODY -->
	<div class="panel-body">
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
        </div> --></div>
		</div>
		<div class="m-t-20">
			<div>
				<h4 class="m-b-5">Add exercises to training segments</h4>
				<p>Please choose the exercises you require in the training segments in your training routine, each segment relates to the different aspects of training and maintaining a balanced program related to an effective warm-up, cardiovascular, resistance, core, cooling down and stretching</p>
			</div>
			<div class="add-exercises">
				<div class="row hidden-xs hidden-sm">
					<div class="col-md-1">
						<label>Filters</label> <i class="fa fa-bars mb-1 filter" id="filter"></i> </div>
					<div class="col-md-8">
						<label>Search</label>
						<input placeholder="Type &amp; wait to filter" type="text" id="keySearchExercise" class="form-control mb-1"> </div>
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
								<select class="form-control  searchExercise" id="movement_type" data-dropup-auto="false"><!--dd id="pt-bodypart"-->
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
			<button class="btn btn-primary btn-o btn-wide pull-right" data-target-step="planMyProgram" id="trainingSegmentSubmit">
				<!--open-step-->Next <i class="fa fa-arrow-circle-right"></i> </button>
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
<div class="panel panel-white" data-step="planMyProgram">
	<!--step1 -->
	<!--data-name="planMyProgram"-->
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
      </h5> </div>
	<!-- end: PANEL HEADING -->
	<!-- start: PANEL BODY -->
	<div class="panel-body item_class">
		<!-- data-name="selct_program"-->
		<div id="traingPlannerMain" class="plannerContainer fit-ui">
			<div>
				<!--id="weekSelection"-->
				<div class="itemHeader">
					<!--data-name="Weeks"--><span>
              <h4>How many weeks do you want to do this plan for?</h4>
            </span> </div>
				<div class="item-class">
					<!--data-name="DaysOfWeek"-->
					<div class="itemBody">
						<div id="weekSlider-crm"></div>
					</div>
				</div>
			</div>
			<div id="timeSelection" class="m-t-20">
				<div class="itemHeader">
					<!--style="margin-top:20px" data-name="TimePerWeek" --><span>
              <h4>How long can you work out each week?</h4>
            </span> </div>
				<div class="itemBody m-t-20">
					<div id="timeSlider-crm"></div>
				</div>
			</div>

			<div class="m-t-20 form-group" id="daySelect">
				<div class="select_radio_btn">
					<div class="radio clip-radio radio-primary radio-inline m-b-0 chooseDays">
						<input type="radio" name="dayOption" id="Choosedays" required="" value="1" data-realtime="Choosedays" >
						<label for="Choosedays">
							Choose days
						</label>
					</div>
					<div class="radio clip-radio radio-primary radio-inline m-b-0 chooseWeek">
						<input type="radio" name="dayOption" id="daysInWeek" required="" value="2" data-realtime="daysInWeek" checked="checked">
						<label for="daysInWeek">
							Choose days in a week
						</label>
					</div>
					<div class="radio-inline startDateOption" style="margin-top: 8px !important">
						<label>Start Date : &nbsp;</label> 
						<input type="date" name="startDate" id="startDate" min="{{ date("yy-m-d") }}">
					</div>
					<div class="checkbox clip-check check-primary letClientSelect checkbox-inline m-b-0 w70">
						<input type="checkbox" name="letClientSelect" id="letClientSelect" value="1" class="onchange-set-neutral" />
						<label for="letClientSelect">Let client select</label>
					 </div>
				</div>
				<div id="showWeekday" style="display: none;">
					<input type="hidden" name="noOfDaysWeek" value="">
					<div class="itemHeader">
						<!--data-name="DaysOfWeek"--><span>
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
							<input type="checkbox" name="weekDay" id="tue-planner" value="1" class="onchange-set-neutral" />
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
				<div id="selectOneday">
					<div class="itemHeader">
              			<h4>Please choose days in a week</h4>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-4 col-sm-6 col-xs-12">
								<select class="form-control" name="daysInWeek">
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
			<div class="text-right m-t-20">
				<button class="btn btn-primary btn-o btn-wide open-step" data-target-step="planPreview"> Next <i class="fa fa-arrow-circle-right"></i> </button>
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
<div class="panel panel-white" data-step="personalInfo">
	<!--step1 step2-->
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
    </h5> </div>
	<!-- end: PANEL HEADING -->
	<!-- start: PANEL BODY -->@if(isset($parq))
	<div class="panel-body item_class">
		<!--data-name="select_information"-->
		<div class="row">
			<div class="col-md-3 pos" data-name="Age">
				<div class="editable">{{ $parq->age }} years</div>
				<input type="number" value="{{$parq->age}}" id="fit_age" class="form-control hidden numericField" min="18" max="98" />
				<hr class="add_hr"> MY AGE </div>
			<div class="col-md-3 pos" data-name="Weight">
				<div class="editable">{{ $parq->weight?$parq->weight:'&nbsp;' }}</div>
				<select id="fit_weight" class="form-control hidden">
					<option value="">-- Select --</option> @if($parq->weightUnit == 'Imperial') {!! renderWeightDdOptions($parq->weight, 'imperial') !!} @else {!! renderWeightDdOptions($parq->weight, 'metric') !!} @endif </select>
				<!--<input type="text" value="{{$parq->weight}}" id="fit_weight" class="form-control hidden" />-->
				<hr class="add_hr"> MY WEIGHT </div>
			<div class="col-md-3 pos" data-name="Height">
				<div class="editable">{{ $parq->height?$parq->height:'&nbsp;' }}</div>
				<select id="fit_height" class="form-control field-editable hidden">
					<option value="">-- Select --</option> @if($parq->heightUnit == 'Imperial') {!! renderHeightDdOptions($parq->height, 'imperial') !!} @else {!! renderHeightDdOptions($parq->height, 'metric') !!} @endif </select>
				<!--<input type="text" value="{{-- $parq->height --}}" id="fit_height" class="form-control hidden" />-->
				<hr class="add_hr"> MY HEIGHT </div>
			<!--<div class="col-md-3 pos"></div>--></div>
		<div class="text-right m-t-20">
			<button class="btn btn-primary btn-o btn-wide open-step" data-target-step="planPreview"> Next <i class="fa fa-arrow-circle-right"></i> </button>
			<!--<button class="btn btn-primary " id="next_plan_program">next</button>-->
		</div>
	</div> @endif
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
    </h5> </div>
	<!-- end: PANEL HEADING -->
	<!-- start: PANEL BODY -->
	<div class="panel-body">
		<div class="row p-l-40">
			<h3>Your Personal Plan</h3> </div>
		<div class="row p-l-40" id="plan-preview-area">
			<!-- Inject data throught ajax (fitness-planner rendorPlanPreview() function)-->
		</div>
		<div class="row">
			<div class="text-right m-t-20">
				<button class="btn btn-primary btn-o btn-wide" id="savePlan">
					<!--data-target-step="" id="doneButton"-->Create Training Programme <i class="fa fa-arrow-circle-right"></i> </button>
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

<!-- end of custom plan title update modal -->

<!-- <map name="Map" id="Map">
</map> -->
