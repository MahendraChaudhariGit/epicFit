
<!-- Start: Add exercise modal -->
<div id="addexercise" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Exercise</h4>
      </div>
      <div class="modal-body bg-white">
        <div class="row">
          <div class="col-md-8 hidden-sm hidden-xs">
            <img src="" usemap="#Map" class="body"  width="600" />
            <map id="Map" name="Map"></map>
          </div>
          <div class="col-md-4"> 
            <div class="form-group">
              <label class="strong">Search favourite</label>
              <div>
                <a class="btn btn-wide btn-default" id="favSearch" href="#"><!--toggle-heart-->
                  <i class="fa fa-heart-o"></i>
                </a>
              </div>
            </div>
            <div class="form-group">
              <label class="strong">Keyword search</label>
              <div>
                <input class="form-control" placeholder="Type &amp; wait to filter" type="text" id="keySearch"><!--txtboxa id="search_b" -->
              </div>
            </div> 
            <div class="form-group">  
              {!! Form::label('bodypart', 'Muscle group', ['class' => 'strong']) !!}
              <select name="bodypart" class="form-control searchExercise" id ="muscle_group">
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
            <div class="form-group"> 
              {!! Form::label('ability', 'Ability', ['class' => 'strong']) !!}
              {!! Form::select('ability', isset($exerciseData)?$exerciseData['abilitys']:[], null, ['class' => 'form-control  searchExercise','id'=>'ability']) !!} 
            </div> 
            <div class="form-group">  
                {!! Form::label('equipment', 'Equipment', ['class' => 'strong']) !!}
                {!! Form::select('equipment', isset($exerciseData)?$exerciseData['equipments']:[], null, ['class' => 'form-control searchExercise','id' => 'equipment']) !!}
              </select>
            </div>
            <div class="form-group">
              {!! Form::label('exerciseTypeID', 'Exercise Type', ['class' => 'strong']) !!}
              {!! Form::select('exerciseTypeID', isset($exerciseData)?$exerciseData['exetype']:[], null, ['class' => 'form-control searchExercise', 'id'=>'category']) !!}  
            </div>
            <div class="form-group">  
              <label class="strong">Movement type</label>
              <select class="form-control  searchExercise" id="movement_type"><!--dd id="pt-bodypart"-->
                <option value=""> -- Select -- </option>
                <option value="1"> Compound </option>
                <option value="2"> Isolated </option>
                <option value="3"> Isometric </option>
              </select>
            </div>
            <div class="form-group">
              {!! Form::label('movement_pattern', 'Movement Pattern', ['class' => 'strong']) !!}
              {!! Form::select('movement_pattern', isset($exerciseData)?$exerciseData['movepattern']:[], null, ['class' => 'form-control searchExercise', 'id'=>'movement_pattern']) !!}  
            </div>
          </div>
        </div>

        <div style="max-height:200px;overflow-y:scroll;overflow-x:hidden"><!--class="exercise_list" -->
          <div class="row" id="exerciseList">
            <!--<div class="col-md-4">
              <a data-toggle="modal" data-target="#lungemodal"><!--class="lunge"-->
                  <!--<div class="panel panel-white">
                    <div class="panel-body">
                      <div class="row">
                        <!--<div class="col-md-5">
                          <img src="{{asset('fitness-planner/images/lunge.png')}}" class="mw-100p">
                        </div>
                        <div class="col-md-4">
                          <h5>
                            Lunge 
                            <br/>
                            <small>Beginner</small> 
                          </h5>
                        </div>
                        <div class="col-md-3">
                          <button class="btn btn-xs btn-primary m-b-2 toggle-heart">
                            <i class="fa fa-heart-o"></i>
                          </button>
                          <button class="btn btn-xs btn-primary toggle-exercise" href="#">
                            <i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    <!--</div>
                  </div>
                </a>
            </div>
            <div class="col-md-4">
              <a data-toggle="modal" data-target="#lungemodal"><!--class="lunge"-->
                  <!--<div class="panel panel-white">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-5">
                          <img src="{{asset('fitness-planner/images/lunge.png')}}" class="mw-100p">
                        </div>
                        <div class="col-md-4">
                          <h5>
                            Lunge 
                            <br/>
                            <small>Beginner</small> 
                          </h5>
                        </div>
                        <div class="col-md-3">
                          <button class="btn btn-xs btn-primary m-b-2 toggle-heart">
                            <i class="fa fa-heart-o"></i>
                          </button>
                          <button class="btn btn-xs btn-primary toggle-exercise" href="#">
                            <i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
            </div>
            <div class="col-md-4">
              <a data-toggle="modal" data-target="#lungemodal"><!--class="lunge"-->
                  <!--<div class="panel panel-white">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-5">
                          <img src="{{asset('fitness-planner/images/lunge.png')}}" class="mw-100p">
                        </div>
                        <div class="col-md-4">
                          <h5>
                            Lunge 
                            <br/>
                            <small>Beginner</small> 
                          </h5>
                        </div>
                        <div class="col-md-3">
                          <button class="btn btn-xs btn-primary m-b-2 toggle-heart">
                            <i class="fa fa-heart-o"></i>
                          </button>
                          <button class="btn btn-xs btn-primary toggle-exercise" href="#">
                            <i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
            </div>
            <div class="col-md-4">
              <a data-toggle="modal" data-target="#lungemodal"><!--class="lunge"-->
                  <!--<div class="panel panel-white">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-5">
                          <img src="{{asset('fitness-planner/images/lunge.png')}}" class="mw-100p">
                        </div>
                        <div class="col-md-4">
                          <h5>
                            Lunge 
                            <br/>
                            <small>Beginner</small> 
                          </h5>
                        </div>
                        <div class="col-md-3">
                          <button class="btn btn-xs btn-primary m-b-2 toggle-heart">
                            <i class="fa fa-heart-o"></i>
                          </button>
                          <button class="btn btn-xs btn-primary toggle-exercise" href="#">
                            <i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
            </div>-->
          </div>
          <div class="alert alert-info">Loading exercise...</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>    
  </div>
</div>
<!-- End: Add exercise modal -->

<!-- start of exercise details modal -->
<div id="lungemodal" class="modal fade" role="dialog">
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

              </div>
            </div>
          </div>

          <div class="col-md-6" id="exe-img-area">
            <!-- <img src="" class="exerciseImg" height="100%" width="100%"> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div> 
<!-- end of exercise details modal -->