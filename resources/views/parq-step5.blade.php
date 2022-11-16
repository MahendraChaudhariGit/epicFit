<div class="modal fade smartgoalNote" id="specificGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalSpecific)&& $parq->smartGoalSpecific!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Specific</h4>
            </div>
            
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>Improving your nutrition requires smaller steps and a specific focus.</p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="specific" type="button">Next</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade smartgoalNote" id="MeasurableGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalMeasurable)&& $parq->smartGoalMeasurable!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Measurable</h4>
            </div>
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>This involves selecting what will be measured to show improvement, impact or success. </p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="measurable" type="button">Next</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade smartgoalNote" id="AchievableGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalAchievable)&& $parq->smartGoalAchievable!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Achievable</h4>
            </div>
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>Objectives should be within reach for your team or program, considering available resources, knowledge and time. </p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="achievable" type="button">Next</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade smartgoalNote" id="RelevantGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalRelevent)&& $parq->smartGoalRelevent!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Relevant</h4>
            </div>
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>Objectives should align with a corresponding goal. </p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="relevent" type="button">Next</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade smartgoalNote" id="TimeGoal" tabindex="-1" role="dialog" data-is-filled="{{ isset($parq->smartGoalTime)&& $parq->smartGoalTime!= '' ? '1' : '0' }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close resetDisp" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Time</h4>
            </div>
            <div class="modal-body panel panel-white">
                <div class="form-group">
                    <p>Objectives should be achievable within a specific time frame that isn't so soon as to prevent success, or so far away as to encourage procrastination. </p>
                    {!! Form::hidden('entity') !!}
                    {!! Form::hidden('entityOptIdx') !!}
                    {!! Form::label('smartgoalNote', 'Notes', ['class' => 'strong']) !!}
                    {!! Form::textarea('smartgoalNote', null, ['class' => 'form-control smartGoalNotes']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary submit" data-dismiss="modal" id="time" type="button">Next</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Smarter Goals</h4>
            </div>
            <div class="modal-body panel panel-white">
                <!-- start: FORM VALIDATION EXAMPLE 2 -->
                <div class="container-fluid container-fullw" style="border-bottom:none !important; padding-bottom:0 !important">
                    <div class="row">
                        <div class="panel-group accordion" id="accordion">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">
                                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                            <i class="icon-arrow"></i>Specific
                                        </a></h5>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="smartGoalSpecific" value="<?php echo $parq->smartGoalSpecific ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">
                                            <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                <i class="icon-arrow"></i> Measurable
                                            </a></h5>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="smartGoalMeasurable" value="<?php echo $parq->smartGoalMeasurable ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h5 class="panel-title">
                                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                                    <i class="icon-arrow"></i> Achievable
                                                </a></h5>
                                            </div>
                                            <div id="collapseThree" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="smartGoalAchievable" value="<?php echo $parq->smartGoalAchievable ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-white">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">
                                                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefour">
                                                        <i class="icon-arrow"></i> Relevent
                                                    </a></h5>
                                                </div>
                                                <div id="collapsefour" class="panel-collapse collapse">
                                                    <div class="panel-body">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" name="smartGoalRelevent" value="<?php echo $parq->smartGoalRelevent ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="panel panel-white">
                                                <div class="panel-heading">
                                                    <h5 class="panel-title">
                                                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsefive">
                                                            <i class="icon-arrow"></i> Time
                                                        </a></h5>
                                                    </div>
                                                    <div id="collapsefive" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="smartGoalTime" value="<?php echo $parq->smartGoalTime ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end: FORM VALIDATION EXAMPLE 2 -->
                                </div>
                                <div class="modal-footer clearfix">
                                    <a class="font-14 pull-left" data-content="Click on the tabs to expand them and fill in relevant goals." data-placement="right" data-toggle="popover" data-trigger="hover" data-title="<strong>Filling goals</strong>" data-html="true">
                                        <i class="fa fa-question-circle" href="#"></i> How to fill?
                                    </a>
                                    <button class="btn btn-primary" data-dismiss="modal" type="button">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade bodyPartModal" id="goalModal" role="dialog">
                        <div class="modal-dialog modal-lg">
                           <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title text-uppercase">Goals and motivation</h4>
                                <p>Please indicate areas on the image that you would like to strengthen, tone, rehabilitate, improve flexibility and mobility or other</p>
                            </div>
                            <div class="modal-body white-bg">
                               <div class="row">
                                <div class="alert alert-success injuryShowAlert" role="alert" style="display:none;" >Data saved successfully.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button></div>
                                   <div class="col-md-8">
                                     <div class="hidden-xs hidden-sm">
                                       @if($parq->gender == 'Male')
                                       <img src="{{ asset('bodytool/male/injuries.gif') }}" usemap="#Map" class="body" width="600" />
                                       @else
                                       <img src="{{ asset('bodytool/female/injuries.gif') }}" usemap="#Map" class="body" width="600" />
                                       @endif
                                   </div>
                                   <div class="form-group hidden-md hidden-lg">
                                    {!! Form::label('bodyParts5', 'Body parts', ['class' => 'strong']) !!}
                                    <select class="form-control bodyPartsDd"  id="bodyParts5">
                                        
                                     <option data-part="ankle-n-foot">Ankle & Feet</option>
                                         <option data-part="knee-n-legs">Knee & Legs</option>
                                         <option data-part="hips-n-lower-back">Hips & Lower Back</option>
                                          <option data-part="core">Core</option>
                                       <option data-part="mid-upper-back">Back Mid & Upper</option>
                                       <option data-part="chest">Chest</option>
                                        <option data-part="shoulders">Shoulders</option> 
                                                     
                                         <option data-part="elbows-n-arms">Elbow & Arms</option> 

                                        <option data-part="wrist-n-hand">Wrist & Hands</option>
                                        <option data-part="neck">Neck</option>
                                        <option data-part="head">Head</option>
                                    </select>
                                </div>    
                            </div>
                            <div class="col-md-4">
                               <div class="head injuryList hidden">
                                   <div class="form-group">
                                    <h4 class="text-uppercase">Head</h4>
                                    <?php
                                    if(!count($parq->headImprove))
                                        $parq->headImprove = [];
                                    ?>
                                    <div class="checkbox clip-check check-primary m-b-0">
                                        <input type="checkbox" name="headImprove0" value="Rehabilitate" id="headImprove0" <?php echo in_array('Rehabilitate', $parq->headImprove)?'checked':''; ?>/>
                                        <label for="headImprove0">
                                           Rehabilitate
                                       </label>
                                   </div>
                                   <div class="checkbox clip-check check-primary m-b-0">
                                    <input type="checkbox" name="headImprove1" value="Improve flexibility and mobility" id="headImprove1" <?php echo in_array('Improve flexibility and mobility', $parq->headImprove)?'checked':''; ?>/>
                                    <label for="headImprove1">
                                       Improve flexibility and mobility
                                   </label>
                               </div>
                               <div class="checkbox clip-check check-primary m-b-0">
                                <input type="checkbox" name="headImprove2" value="All" id="headImprove2" <?php echo in_array('All', $parq->headImprove)?'checked':''; ?>/>
                                <label for="headImprove2">
                                   All
                               </label>
                           </div>
                           <div class="checkbox clip-check check-primary m-b-0">
                            <input type="checkbox" name="headImprove3" value="Other" id="headImprove3" <?php echo in_array('Other', $parq->headImprove)?'checked':''; ?>/>
                            <label for="headImprove3">
                               Other
                           </label>
                       </div>
                   </div>                                
                   <div class="form-group">
                    <label class="strong" for="notesHeadImprove">Please add the relevant notes relating to goals selected above</label>
                    <textarea class="form-control" id="notesHeadImprove" name="notesHeadImprove">{{ $parq->headImproveNotes }}</textarea>
                </div>
            </div>

            <div class="neck injuryList hidden">
               <div class="form-group">
                <h4 class="text-uppercase">Neck</h4>
                <?php
                if(!count($parq->neckImprove))
                    $parq->neckImprove = [];
                ?>
                <div class="checkbox clip-check check-primary m-b-0">
                    <input type="checkbox" name="neckImprove0" value="Strengthen" id="neckImprove0" <?php echo in_array('Strengthen', $parq->neckImprove)?'checked':''; ?>/>
                    <label for="neckImprove0">
                      Strengthen 
                  </label>
              </div>
              <div class="checkbox clip-check check-primary m-b-0">
                <input type="checkbox" name="neckImprove1" value="Tone" id="neckImprove1" <?php echo in_array('Tone', $parq->neckImprove)?'checked':''; ?>/>
                <label for="neckImprove1">
                  Tone
              </label>
          </div>
          <div class="checkbox clip-check check-primary m-b-0">
            <input type="checkbox" name="neckImprove2" value="Rehabilitate" id="neckImprove2" <?php echo in_array('Rehabilitate', $parq->neckImprove)?'checked':''; ?>/>
            <label for="neckImprove2">
              Rehabilitate
          </label>
      </div>
      <div class="checkbox clip-check check-primary m-b-0">
        <input type="checkbox" name="neckImprove3" value="Improve flexibility and mobility" id="neckImprove3" <?php echo in_array('Improve flexibility and mobility', $parq->neckImprove)?'checked':''; ?>/>
        <label for="neckImprove3">
          Improve flexibility and mobility
      </label>
  </div>
  <div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="neckImprove4" value="All" id="neckImprove4" <?php echo in_array('All', $parq->neckImprove)?'checked':''; ?>/>
    <label for="neckImprove4">
      All
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="neckImprove5" value="Other" id="neckImprove5" <?php echo in_array('Other', $parq->neckImprove)?'checked':''; ?>/>
    <label for="neckImprove5">
      Other
  </label>
</div>
</div>    
<div class="form-group">
    <label class="strong" for="notesNeckImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesNeckImprove" name="notesNeckImprove">{{ $parq->neckImproveNotes }}</textarea>
</div>
</div>

<div class="mid-upper-back injuryList hidden">
   <div class="form-group">
    <h4 class="text-uppercase">Back Mid & Upper</h4>
    <?php
    if(!count($parq->backImprove))
        $parq->backImprove = [];
    ?>
    <div class="checkbox clip-check check-primary m-b-0">
        <input type="checkbox" name="backImprove0" value="Strengthen" id="backImprove0" <?php echo in_array('Strengthen', $parq->backImprove)?'checked':''; ?>/>
        <label for="backImprove0">
          Strengthen 
      </label>
  </div>
  <div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="backImprove1" value="Tone" id="backImprove1" <?php echo in_array('Tone', $parq->backImprove)?'checked':''; ?>/>
    <label for="backImprove1">
      Tone
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="backImprove2" value="Rehabilitate" id="backImprove2" <?php echo in_array('Rehabilitate', $parq->backImprove)?'checked':''; ?>/>
    <label for="backImprove2">
      Rehabilitate
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="backImprove3" value="Improve flexibility and mobility" id="backImprove3" <?php echo in_array('Improve flexibility and mobility', $parq->backImprove)?'checked':''; ?>/>
    <label for="backImprove3">
      Improve flexibility and mobility
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="backImprove4" value="All" id="backImprove4" <?php echo in_array('All', $parq->backImprove)?'checked':''; ?>/>
    <label for="backImprove4">
      All
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="backImprove5" value="Other" id="backImprove5" <?php echo in_array('Other', $parq->backImprove)?'checked':''; ?>/>
    <label for="backImprove5">
      Other
  </label>
</div>
</div>
<div class="form-group">
    <label class="strong" for="notesBackImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesBackImprove" name="notesBackImprove">{{ $parq->backImproveNotes }}</textarea>
</div>
</div>

<div class="ankle-n-foot injuryList hidden">
   <div class="form-group">
    <h4 class="text-uppercase">Ankle & Feet</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->footImprove))
        $parq->footImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="footImprove0" value="L_Strengthen" name="footImprove0" <?php echo in_array('L_Strengthen', $parq->footImprove)?'checked':''; ?>/> 
            <label for="footImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="footImprove1" value="R_Strengthen" id="footImprove1" <?php echo in_array('R_Strengthen', $parq->footImprove)?'checked':''; ?> /> 
            <label for="footImprove1" class="m-r-0"></label>
        </div>
        Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="footImprove2" value="L_Rehabilitate" name="footImprove2" <?php echo in_array('L_Rehabilitate', $parq->footImprove)?'checked':''; ?>/> 
            <label for="footImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="footImprove3" value="R_Rehabilitate" id="footImprove3" <?php echo in_array('R_Rehabilitate', $parq->footImprove)?'checked':''; ?> /> 
            <label for="footImprove3" class="m-r-0"></label>
        </div>
        Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="footImprove4" value="L_Improve flexibility and mobility" name="footImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->footImprove)?'checked':''; ?>/> 
            <label for="footImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="footImprove5" value="R_Improve flexibility and mobility" id="footImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->footImprove)?'checked':''; ?> /> 
            <label for="footImprove5" class="m-r-0"></label>
        </div>
        Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="footImprove6" value="L_All" name="footImprove6" <?php echo in_array('L_All', $parq->footImprove)?'checked':''; ?>/> 
            <label for="footImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="footImprove7" value="R_All" id="footImprove7" <?php echo in_array('R_All', $parq->footImprove)?'checked':''; ?> /> 
            <label for="footImprove7" class="m-r-0"></label>
        </div>
        All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary m-b-0">
           <input type="checkbox" id="footImprove8" value="Other" name="footImprove8" <?php echo in_array('Other', $parq->footImprove)?'checked':''; ?>/>
           <label for="footImprove8">
            Other
        </label>
    </div> 
</div>
</div>    
<div class="form-group">
    <label class="strong" for="notesFootImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesFootImprove" name="notesFootImprove">{{ $parq->footImproveNotes }}</textarea>
</div>
</div>

<div class="knee-n-legs injuryList hidden">
   <div class="form-group">
    <h4 class="text-uppercase">Knee & Legs</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->legImprove))
        $parq->legImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove0" value="L_Strengthen" name="legImprove0" <?php echo in_array('L_Strengthen', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove1" value="R_Strengthen" id="legImprove1" <?php echo in_array('R_Strengthen', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove1" class="m-r-0"></label>
        </div>
        Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove10" value="L_Tone" name="legImprove10" <?php echo in_array('L_Tone', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove10" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove11" value="R_Tone" id="legImprove11" <?php echo in_array('R_Tone', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove11" class="m-r-0"></label>
        </div>
        Tone
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove2" value="L_Rehabilitate" name="legImprove2" <?php echo in_array('L_Rehabilitate', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove3" value="R_Rehabilitate" id="legImprove3" <?php echo in_array('R_Rehabilitate', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove3" class="m-r-0"></label>
        </div>
        Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove4" value="L_Improve flexibility and mobility" name="legImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove5" value="R_Improve flexibility and mobility" id="legImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove5" class="m-r-0"></label>
        </div>
        Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="legImprove6" value="L_All" name="legImprove6" <?php echo in_array('L_All', $parq->legImprove)?'checked':''; ?>/>   
            <label for="legImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="legImprove7" value="R_All" id="legImprove7" <?php echo in_array('R_All', $parq->legImprove)?'checked':''; ?>/> 
            <label for="legImprove7" class="m-r-0"></label>
        </div>
        All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary m-b-0">
            <input type="checkbox" id="legImprove8" value="Other" name="legImprove8" <?php echo in_array('Other', $parq->legImprove)?'checked':''; ?>/>
            <label for="legImprove8">
                Other
            </label>
        </div> 
    </div>
</div>    
<div class="form-group">
    <label class="strong" for="notesLegImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesLegImprove" name="notesLegImprove">{{ $parq->legImproveNotes }}</textarea>
</div>
</div>

<div class="hips-n-lower-back injuryList hidden">
   <div class="form-group">
    <h4 class="text-uppercase">Hips & Lower Back</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->hipImprove))
        $parq->hipImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove0" value="L_Strengthen" name="hipImprove0" <?php echo in_array('L_Strengthen', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove1" value="R_Strengthen" id="hipImprove1" <?php echo in_array('R_Strengthen', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove1" class="m-r-0"></label>
        </div>
        Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove10" value="L_Tone" name="hipImprove10" <?php echo in_array('L_Tone', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove10" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove11" value="R_Tone" id="hipImprove11" <?php echo in_array('R_Tone', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove11" class="m-r-0"></label>
        </div>
        Tone
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove2" value="L_Rehabilitate" name="hipImprove2" <?php echo in_array('L_Rehabilitate', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove3" value="R_Rehabilitate" id="hipImprove3" <?php echo in_array('R_Rehabilitate', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove3" class="m-r-0"></label>
        </div>
        Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove4" value="L_Improve flexibility and mobility" name="hipImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove5" value="R_Improve flexibility and mobility" id="hipImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove5" class="m-r-0"></label>
        </div>
        Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="hipImprove6" value="L_All" name="hipImprove6" <?php echo in_array('L_All', $parq->hipImprove)?'checked':''; ?>/>   
            <label for="hipImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="hipImprove7" value="R_All" id="hipImprove7" <?php echo in_array('R_All', $parq->hipImprove)?'checked':''; ?> /> 
            <label for="hipImprove7" class="m-r-0"></label>
        </div>
        All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary m-b-0">
            <input type="checkbox" id="hipImprove8" value="Other" name="hipImprove8" <?php echo in_array('Other', $parq->hipImprove)?'checked':''; ?>/>
            <label for="hipImprove8">
                Other
            </label>
        </div> 
    </div>        
</div>    
<div class="form-group">
    <label class="strong" for="notesHipImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesHipImprove" name="notesHipImprove">{{ $parq->hipImproveNotes }}</textarea>
</div>
</div>

<div class="shoulders injuryList hidden">
   <div class="form-group">
    <h4 class="text-uppercase">Shoulders</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->shouldersImprove))
        $parq->shouldersImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove0" value="L_Strengthen" name="shouldersImprove0" <?php echo in_array('L_Strengthen', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove1" value="R_Strengthen" id="shouldersImprove1" <?php echo in_array('R_Strengthen', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove1" class="m-r-0"></label>
        </div>
        Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove10" value="L_Tone" name="shouldersImprove10" <?php echo in_array('L_Tone', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove10" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove11" value="R_Tone" id="shouldersImprove11" <?php echo in_array('R_Tone', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove11" class="m-r-0"></label>
        </div>
        Tone
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove2" value="L_Rehabilitate" name="shouldersImprove2" <?php echo in_array('L_Rehabilitate', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove3" value="R_Rehabilitate" id="shouldersImprove3" <?php echo in_array('R_Rehabilitate', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove3" class="m-r-0"></label>
        </div>
        Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove4" value="L_Improve flexibility and mobility" name="shouldersImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove5" value="R_Improve flexibility and mobility" id="shouldersImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove5" class="m-r-0"></label>
        </div>
        Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="shouldersImprove6" value="L_All" name="shouldersImprove6" <?php echo in_array('L_All', $parq->shouldersImprove)?'checked':''; ?>/>   
            <label for="shouldersImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="shouldersImprove7" value="R_All" id="shouldersImprove7" <?php echo in_array('R_All', $parq->shouldersImprove)?'checked':''; ?> /> 
            <label for="shouldersImprove7" class="m-r-0"></label>
        </div>
        All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary m-b-0">
            <input type="checkbox" id="shouldersImprove8" value="Other" name="shouldersImprove8" <?php echo in_array('Other', $parq->shouldersImprove)?'checked':''; ?>/>
            <label for="shouldersImprove8">
                Other
            </label>
        </div> 
    </div>
</div>    
<div class="form-group">
    <label class="strong" for="notesShouldersImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesShouldersImprove" name="notesShouldersImprove">{{ $parq->shouldersImproveNotes }}</textarea>
</div>
</div>

<div class="elbows-n-arms injuryList hidden">
   <div class="form-group">
    <h4 class="text-uppercase">Elbow & Arms</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->armsImprove))
        $parq->armsImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove0" value="L_Strengthen" name="armsImprove0" <?php echo in_array('L_Strengthen', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove1" value="R_Strengthen" id="armsImprove1" <?php echo in_array('R_Strengthen', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove1" class="m-r-0"></label>
        </div>
        Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove10" value="L_Tone" name="armsImprove10" <?php echo in_array('L_Tone', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove10" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove11" value="R_Tone" id="armsImprove11" <?php echo in_array('R_Tone', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove11" class="m-r-0"></label>
        </div>
        Tone
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove2" value="L_Rehabilitate" name="armsImprove2" <?php echo in_array('L_Rehabilitate', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove3" value="R_Rehabilitate" id="armsImprove3" <?php echo in_array('R_Rehabilitate', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove3" class="m-r-0"></label>
        </div>
        Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove4" value="L_Improve flexibility and mobility" name="armsImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove5" value="R_Improve flexibility and mobility" id="armsImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove5" class="m-r-0"></label>
        </div>
        Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="armsImprove6" value="L_All" name="armsImprove6" <?php echo in_array('L_All', $parq->armsImprove)?'checked':''; ?>/>   
            <label for="armsImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="armsImprove7" value="R_All" id="armsImprove7" <?php echo in_array('R_All', $parq->armsImprove)?'checked':''; ?>/> 
            <label for="armsImprove7" class="m-r-0"></label>
        </div>
        All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary m-b-0">
            <input type="checkbox" id="armsImprove8" value="Other" name="armsImprove8" <?php echo in_array('Other', $parq->armsImprove)?'checked':''; ?>/>
            <label for="armsImprove8">
                Other
            </label>
        </div> 
    </div>        
</div>    
<div class="form-group">
    <label class="strong" for="notesArmsImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesArmsImprove" name="notesArmsImprove">{{ $parq->armsImproveNotes }}</textarea>
</div>
</div>

<div class="wrist-n-hand injuryList hidden">
   <div class="form-group">
    <h4 class="text-uppercase">Wrist & Hands</h4>
    L &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;R<br/>
    <?php
    if(!count($parq->handImprove))
        $parq->handImprove = [];
    ?> 
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="handImprove0" value="L_Strengthen" name="handImprove0" <?php echo in_array('L_Strengthen', $parq->handImprove)?'checked':''; ?>/>  
            <label for="handImprove0" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="handImprove1" value="R_Strengthen" id="handImprove1" <?php echo in_array('R_Strengthen', $parq->handImprove)?'checked':''; ?> /> 
            <label for="handImprove1" class="m-r-0"></label>
        </div>
        Strengthen 
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="handImprove2" value="L_Rehabilitate" name="handImprove2" <?php echo in_array('L_Rehabilitate', $parq->handImprove)?'checked':''; ?>/>  
            <label for="handImprove2" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="handImprove3" value="R_Rehabilitate" id="handImprove3" <?php echo in_array('R_Rehabilitate', $parq->handImprove)?'checked':''; ?> /> 
            <label for="handImprove3" class="m-r-0"></label>
        </div>
        Rehabilitate
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="handImprove4" value="L_Improve flexibility and mobility" name="handImprove4" <?php echo in_array('L_Improve flexibility and mobility', $parq->handImprove)?'checked':''; ?>/>  
            <label for="handImprove4" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="handImprove5" value="R_Improve flexibility and mobility" id="handImprove5" <?php echo in_array('R_Improve flexibility and mobility', $parq->handImprove)?'checked':''; ?> /> 
            <label for="handImprove5" class="m-r-0"></label>
        </div>
        Improve flexibility and mobility
    </div>
    <div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" id="handImprove6" value="L_All" name="handImprove6" <?php echo in_array('L_All', $parq->handImprove)?'checked':''; ?>/>  
            <label for="handImprove6" class="m-r-0"></label>
        </div>
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0 m-r-0">
            <input type="checkbox" name="handImprove7" value="R_All" id="handImprove7" <?php echo in_array('R_All', $parq->handImprove)?'checked':''; ?> /> 
            <label for="handImprove7" class="m-r-0"></label>
        </div>
        All
    </div>
    <div class="m-t-5">
        <div class="checkbox clip-check check-primary m-b-0">
            <input type="checkbox" id="handImprove8" value="Other" name="handImprove8" <?php echo in_array('Other', $parq->handImprove)?'checked':''; ?>/>
            <label for="handImprove8">
                Other
            </label>
        </div> 
    </div>                                
</div>    
<div class="form-group">
    <label class="strong" for="notesHandImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesHandImprove" name="notesHandImprove">{{ $parq->handImproveNotes }}</textarea>
</div>
</div>

<div class="chest injuryList hidden">
    <div class="form-group">
        <h4 class="text-uppercase">Chest</h4>
        <?php
        if(!count($parq->chestImprove))
            $parq->chestImprove = [];
        ?>
        <div class="checkbox clip-check check-primary m-b-0">
            <input type="checkbox" name="chestImprove0" value="Strengthen" id="chestImprove0" <?php echo in_array('Strengthen', $parq->chestImprove)?'checked':''; ?>/>
            <label for="chestImprove0">
              Strengthen 
          </label>
      </div>
      <div class="checkbox clip-check check-primary m-b-0">
        <input type="checkbox" name="chestImprove1" value="Tone" id="chestImprove1" <?php echo in_array('Tone', $parq->chestImprove)?'checked':''; ?>/>
        <label for="chestImprove1">
          Tone
      </label>
  </div>
  <div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="chestImprove2" value="Rehabilitate" id="chestImprove2" <?php echo in_array('Rehabilitate', $parq->chestImprove)?'checked':''; ?>/>
    <label for="chestImprove2">
      Rehabilitate
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="chestImprove3" value="Improve flexibility and mobility" id="chestImprove3" <?php echo in_array('Improve flexibility and mobility', $parq->chestImprove)?'checked':''; ?>/>
    <label for="chestImprove3">
      Improve flexibility and mobility
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="chestImprove4" value="All" id="chestImprove4" <?php echo in_array('All', $parq->chestImprove)?'checked':''; ?>/>
    <label for="chestImprove4">
      All
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="chestImprove5" value="Other" id="chestImprove5" <?php echo in_array('Other', $parq->chestImprove)?'checked':''; ?>/>
    <label for="chestImprove5">
      Other
  </label>
</div>
</div>    
<div class="form-group">
    <label class="strong" for="notesChestImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesChestImprove" name="notesChestImprove">{{ $parq->chestImproveNotes }}</textarea>
</div>
</div>

<div class="core injuryList hidden">
    <div class="form-group">
        <h4 class="text-uppercase">Core</h4>
        <?php
        if(!count($parq->coreImprove))
            $parq->coreImprove = [];
        ?>
        <div class="checkbox clip-check check-primary m-b-0">
            <input type="checkbox" name="coreImprove0" value="Strengthen" id="coreImprove0" <?php echo in_array('Strengthen', $parq->coreImprove)?'checked':''; ?>/>
            <label for="coreImprove0">
              Strengthen 
          </label>
      </div>
      <div class="checkbox clip-check check-primary m-b-0">
        <input type="checkbox" name="coreImprove1" value="Tone" id="coreImprove1" <?php echo in_array('Tone', $parq->coreImprove)?'checked':''; ?>/>
        <label for="coreImprove1">
          Tone
      </label>
  </div>
  <div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="coreImprove2" value="Rehabilitate" id="coreImprove2" <?php echo in_array('Rehabilitate', $parq->coreImprove)?'checked':''; ?>/>
    <label for="coreImprove2">
      Rehabilitate
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="coreImprove3" value="Improve flexibility and mobility" id="coreImprove3" <?php echo in_array('Improve flexibility and mobility', $parq->coreImprove)?'checked':''; ?>/>
    <label for="coreImprove3">
      Improve flexibility and mobility
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="coreImprove4" value="All" id="coreImprove4" <?php echo in_array('All', $parq->coreImprove)?'checked':''; ?>/>
    <label for="coreImprove4">
      All
  </label>
</div>
<div class="checkbox clip-check check-primary m-b-0">
    <input type="checkbox" name="coreImprove5" value="Other" id="coreImprove5" <?php echo in_array('Other', $parq->coreImprove)?'checked':''; ?>/>
    <label for="coreImprove5">
      Other
  </label>
</div>
</div>    
<div class="form-group">
    <label class="strong" for="notesCoreImprove">Please add the relevant notes relating to goals selected above</label>
    <textarea class="form-control" id="notesCoreImprove" name="notesCoreImprove">{{ $parq->coreImproveNotes }}</textarea>
</div>
</div>
</div>
</div>
</div>
<div class="modal-footer">
   <div class="checkbox clip-check check-primary checkbox-inline m-b-0 alertHide">
    <input type="checkbox" name="wholeBody" id="wholeBody" class="closeModal" value="1" {{ $parq->wholeBody == 1?'checked':'' }}>
    <label for="wholeBody">
        <strong>Whole Body</strong>
    </label>
</div>
<button type="button" class="btn btn-primary submit-step-injury injuryAlert" style="margin-bottom: 0px;" data-step="5">Save</button>
<button type="button" class="btn btn-danger alertHide" data-dismiss="modal">Close</button>

</div>
</div>
</div>
</div>

<div class="modal fade" id="waiverModal" role="dialog">
    <div class="modal-dialog">
    	<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Waiver</h4>
            </div>
            <div class="modal-body white-bg">
                <form role="form" method="post">
                    <input id="client_id" type="hidden" name="client_id" value="{{$parq->client_id}}">

                    <?php $name = Auth::user()->fullName;?>
                    <div class="form-group m-x-0">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{$name}}" readonly/>
                    </div>
                    <div class="form-group m-x-0">
                        <label for="trainerwaiverDate">Date</label>
                        <input type="text" class="form-control onchange-set-neutral" id="trainerwaiverDate" name="trainerwaiverDate" required autocomplete="off" readonly="" />
                        <span class="help-block m-b-0"></span>
                    </div>
                    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                        <input type="checkbox" name="waiverTerms" id="waiverTerms" value="1" required>
                        <label for="waiverTerms" class="waiverTerms">
                            I fully understand that I have been advised to consult with a physician prior to completing any activity if the EPIC Risk Factor indicates an elevated risk status
                        </label>
                        <span class="help-block m-b-0"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button><!-- id="cancel"-->
            	<button type="button" class="btn btn-primary" id="submit">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="col-md-6">
	<fieldset class="padding-15">
       <legend>Goals & Priorities</legend>
       <div class="form-group">
        <label class="strong" for="referrer">Prioritise and drag the following fitness components relating to your specific needs: Number from 1 (most important) to 5 (least important)</label>
        <div class="dd" id="nestable">
            <ol class="dd-list">
              <?php
              $cLabels = array('goalFitnessComponents0' => 'Body Fat %', 'goalFitnessComponents1' => 'Cardio Endurance', 'goalFitnessComponents2' => 'Flexibility & mobility', 'goalFitnessComponents3' => 'Muscular strength', 'goalFitnessComponents4' => 'Explosive power');
              $comps = $parq->goalFitnessComponents ? json_decode($parq->goalFitnessComponents, true) : array(array('id' => 'goalFitnessComponents0'), array('id' => 'goalFitnessComponents1'), array('id' => 'goalFitnessComponents2'), array('id' => 'goalFitnessComponents3'), array('id' => 'goalFitnessComponents4'));
              ?>
              @foreach($comps as $carr)
              <li class="dd-item" data-id="{{ $carr['id'] }}">
                <div class="dd-handle">{{ $cLabels[$carr['id']] }}</div>
            </li>
            @endforeach
        </ol>
    </div>
    <input type="hidden" name="goalFitnessComponents" value="<?php echo htmlentities($parq->goalFitnessComponents); ?>" />
</div>

<div class="form-group">
    <label for="goalHealthWellness" class="strong">Select your specific goals</label>
    <?php
    if(!count($parq->goalHealthWellness))
        $parq->goalHealthWellness = [];
        $parq->goalHealthWellness =  array_map('strtolower',$parq->goalHealthWellness);

    ?>
    <select class="form-control healthGoals" multiple name="goalHealthWellness" data-realtime="goals">
       <option value="Health & Wellness" <?php echo in_array('health & wellness', $parq->goalHealthWellness)?'selected':''; ?>>Health & Wellness</option>
       <option value="Increased Energy" <?php echo in_array('increased energy', $parq->goalHealthWellness)?'selected':''; ?>>Increased Energy</option>
       <option value="Tone" <?php echo in_array('tone', $parq->goalHealthWellness)?'selected':''; ?>>Tone</option>
       <option value="Injury Recovery" <?php echo in_array('injury recovery', $parq->goalHealthWellness)?'selected':''; ?>>Injury Recovery</option>
       <option value="Improved Nutrition" <?php echo in_array('improved nutrition', $parq->goalHealthWellness)?'selected':''; ?>>Improved Nutrition</option>
       <option value="Lose Weight" <?php echo in_array('lose weight', $parq->goalHealthWellness)?'selected':''; ?>>Lose Weight</option>
       <option value="Improved Performance" <?php echo in_array('improved performance', $parq->goalHealthWellness)?'selected':''; ?>>Improved Performance</option>
       <option value="Improved Endurance" <?php echo in_array('improved endurance', $parq->goalHealthWellness)?'selected':''; ?>>Improved Endurance</option>
       <option value="Improved Strength & Conditioning" <?php echo in_array('improved strength & conditioning', $parq->goalHealthWellness)?'selected':''; ?>>Improved Strength & Conditioning</option>
   </select>
</div>

<div class="form-group">
    <label class="strong">Please indicate areas you would like to strengthen, tone, rehabilitate & increase flexibility and/or mobility</label>
    <button type="button" class="btn btn-o btn-default btn-block" data-toggle="modal" data-target="#goalModal">Click here to view body parts</button>
</div>



<div class="form-group">
    <label class="strong" for="lifestyleImprove">What areas of your lifestyle are you willing to improve to achieve your goal?</label>
    <?php
    if(!count($parq->lifestyleImprove))
        $parq->lifestyleImprove = [];
    ?>
    <select id="lifestyleImprove" name="lifestyleImprove" class="form-control lifestyleImprove" multiple>
        <option value="Physical activity" <?php echo in_array('Physical activity', $parq->lifestyleImprove)?'selected':''; ?>>Physical activity</option>
        <option value="Hydration" <?php echo in_array('Hydration', $parq->lifestyleImprove)?'selected':''; ?>>Hydration</option>
        <option value="Nutrition" <?php echo in_array('Nutrition', $parq->lifestyleImprove)?'selected':''; ?>>Nutrition</option>
        <option value="Sleep" <?php echo in_array('Sleep', $parq->lifestyleImprove)?'selected':''; ?>>Sleep</option>
        <option value="Occupation" <?php echo in_array('Occupation', $parq->lifestyleImprove)?'selected':''; ?>>Occupation</option>
        <option value="Relationships" <?php echo in_array('Relationships', $parq->lifestyleImprove)?'selected':''; ?>>Relationships</option>
    </select>
</div>
</fieldset>
</div>

<div class="col-md-6">
	<fieldset class="padding-15">
       <legend>Needs & Support</legend>
       <div class="form-group">
        <label class="strong" for="goalWantTobe">I want to be</label>
        <?php
        if(!count($parq->goalWantTobe))
            $parq->goalWantTobe = [];
        ?>
        <select id="goalWantTobe" name="goalWantTobe" class="form-control goalWantTobe" data-actions-box="true" multiple>
            <option value="Toned" <?php echo in_array('Toned', $parq->goalWantTobe)?'selected':''; ?>>Toned</option>
            <option value="Fitter" <?php echo in_array('Fitter', $parq->goalWantTobe)?'selected':''; ?>>Fitter</option>
            <option value="Stronger" <?php echo in_array('Stronger', $parq->goalWantTobe)?'selected':''; ?>>Stronger</option>
            <option value="Flexible" <?php echo in_array('Flexible', $parq->goalWantTobe)?'selected':''; ?>>Flexible</option>
        </select>
        {{-- <div class="checkbox clip-check check-primary m-b-0 m-t-5">
            <input type="checkbox" id="goalWantTobeAll" class="selAllDd">
            <label for="goalWantTobeAll" class="no-error-label">
                <strong>Select All</strong>
            </label>
        </div> --}}
    </div>

        <!--<div class="form-group">
            <label class="strong">I want to be</label>
            <?php
            if(!count($parq->goalWantTobe))
                $parq->goalWantTobe = [];
            ?>
            <div>                                   
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input type="checkbox" name="goalWantTobe0" id="goalWantTobe0" value="Toned" <?php echo in_array('Toned', $parq->goalWantTobe)?'checked':''; ?>>
                    <label for="goalWantTobe0">
                        Toned
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input type="checkbox" name="goalWantTobe1" id="goalWantTobe1" value="Fitter" <?php echo in_array('Fitter', $parq->goalWantTobe)?'checked':''; ?>>
                    <label for="goalWantTobe1">
                        Fitter
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input type="checkbox" name="goalWantTobe2" id="goalWantTobe2" value="Stronger" <?php echo in_array('Stronger', $parq->goalWantTobe)?'checked':''; ?>>
                    <label for="goalWantTobe2">
                        Stronger
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input type="checkbox" name="goalWantTobe3" id="goalWantTobe3" value="Flexible" <?php echo in_array('Flexible', $parq->goalWantTobe)?'checked':''; ?>>
                    <label for="goalWantTobe3">
                        Flexible
                    </label>
                </div>
            </div>
        </div>-->
        
        <div class="form-group">
            <label class="strong" for="goalWantfeel">I want to feel</label>
            <?php
            if(!count($parq->goalWantfeel))
                $parq->goalWantfeel = [];
            ?>
            <select id="goalWantfeel" name="goalWantfeel" class="form-control goalWantfeel" data-actions-box="true" multiple>
                <option value="Happier" <?php echo in_array('Happier', $parq->goalWantfeel)?'selected':''; ?>>Happier</option>
                <option value="Energetic" <?php echo in_array('Energetic', $parq->goalWantfeel)?'selected':''; ?>>Energetic</option>
                <option value="Healthier" <?php echo in_array('Healthier', $parq->goalWantfeel)?'selected':''; ?>>Healthier</option>
                <option value="Relaxed" <?php echo in_array('Relaxed', $parq->goalWantfeel)?'selected':''; ?>>Relaxed</option>
            </select>
            {{-- <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                <input type="checkbox" id="goalWantfeelAll" class="selAllDd">
                <label for="goalWantfeelAll" class="no-error-label">
                    <strong>Select All</strong>
                </label>
            </div> --}}
            <!--<div>                                   
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                	<input type="checkbox" name="goalWantfeel0" id="goalWantfeel0" value="Happier" <?php echo in_array('Happier', $parq->goalWantfeel)?'checked':''; ?>>
                    <label for="goalWantfeel0">
                        Happier
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input type="checkbox" name="goalWantfeel1" id="goalWantfeel1" value="Energetic" <?php echo in_array('Energetic', $parq->goalWantfeel)?'checked':''; ?>>
                    <label for="goalWantfeel1">
                        Energetic
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input type="checkbox" name="goalWantfeel2" id="goalWantfeel2" value="Healthier" <?php echo in_array('Healthier', $parq->goalWantfeel)?'checked':''; ?>>
                    <label for="goalWantfeel2">
                        Healthier
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input type="checkbox" name="goalWantfeel3" id="goalWantfeel3" value="Relaxed" <?php echo in_array('Relaxed', $parq->goalWantfeel)?'checked':''; ?>>
                    <label for="goalWantfeel3">
                        Relaxed
                    </label>
                </div>
            </div>-->
        </div>
        
        <div class="form-group">
            <label class="strong" for="goalWantHave">I want to have</label>
            <?php
            if(!count($parq->goalWantHave))
                $parq->goalWantHave = [];
            ?>
            <select id="goalWantHave" name="goalWantHave" class="form-control goalWantHave" data-actions-box="true" multiple>
                <option value="Less stress" <?php echo in_array('Less stress', $parq->goalWantHave)?'selected':''; ?>>Less stress</option>
                <option value="More time" <?php echo in_array('More time', $parq->goalWantHave)?'selected':''; ?>>More time</option>
                <option value="More fun" <?php echo in_array('More fun', $parq->goalWantHave)?'selected':''; ?>>More fun</option>
                <option value="More control" <?php echo in_array('More control', $parq->goalWantHave)?'selected':''; ?>>More control</option>
            </select>
            {{-- <div class="checkbox clip-check check-primary m-b-0 m-t-5">
                <input type="checkbox" id="goalWantHaveAll" class="selAllDd">
                <label for="goalWantHaveAll" class="no-error-label">
                    <strong>Select All</strong>
                </label>
            </div> --}}
            <!--<div>                                   
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
          			<input type="checkbox" name="goalWantHave0" id="goalWantHave0" value="Less stress" <?php echo in_array('Less stress', $parq->goalWantHave)?'checked':''; ?>>
                    <label for="goalWantHave0">
                        Less stress
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
          			<input type="checkbox" name="goalWantHave1" id="goalWantHave1" value="More time" <?php echo in_array('More time', $parq->goalWantHave)?'checked':''; ?>>
                    <label for="goalWantHave1">
                        More time
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
          			<input type="checkbox" name="goalWantHave2" id="goalWantHave2" value="More fun" <?php echo in_array('More fun', $parq->goalWantHave)?'checked':''; ?>>
                    <label for="goalWantHave2">
                        More fun
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
          			<input type="checkbox" name="goalWantHave3" id="goalWantHave3" value="More control" <?php echo in_array('More control', $parq->goalWantHave)?'checked':''; ?>>
                    <label for="goalWantHave3">
                        More control
                    </label>
                </div>
            </div>-->
        </div>
        
<!--<div class="form-group">
<label class="strong">How supportive are your family?</label>
<div>
<div class="radio clip-radio radio-primary radio-inline m-b-0">
<input type="radio" name="supportFamily" id="supportFamily0" value="None" <?php //echo ($parq->supportFamily == 'None')?'checked':''; ?>>
<label for="supportFamily0">
None
</label>
</div>
<div class="radio clip-radio radio-primary radio-inline m-b-0">
<input type="radio" name="supportFamily" id="supportFamily1" value="Moderate" <?php //echo ($parq->supportFamily == 'Moderate')?'checked':''; ?>>
<label for="supportFamily1">
Moderate
</label>
</div>
<div class="radio clip-radio radio-primary radio-inline m-b-0">
<input type="radio" name="supportFamily" id="supportFamily2" value="High" <?php //echo ($parq->supportFamily == 'High')?'checked':''; ?>>
<label for="supportFamily2">
High
</label>
</div>
</div>
</div>
<div class="form-group">
<label class="strong">How supportive are your friends?</label>
<div>
<div class="radio clip-radio radio-primary radio-inline m-b-0">
<input type="radio" name="supportFriends" id="supportFriends0" value="None" <?php //echo ($parq->supportFriends == 'None')?'checked':''; ?>>
<label for="supportFriends0">
None
</label>
</div>
<div class="radio clip-radio radio-primary radio-inline m-b-0">
<input type="radio" name="supportFriends" id="supportFriends1" value="Moderate" <?php //echo ($parq->supportFriends == 'Moderate')?'checked':''; ?>>
<label for="supportFriends1">
Moderate
</label>
</div>
<div class="radio clip-radio radio-primary radio-inline m-b-0">
<input type="radio" name="supportFriends" id="supportFriends2" value="High" <?php //echo ($parq->supportFriends == 'High')?'checked':''; ?>>
<label for="supportFriends2">
High
</label>
</div>
</div>
</div>
<div class="form-group">
<label class="strong">How supportive are your work colleagues?</label>
<div>
<div class="radio clip-radio radio-primary radio-inline m-b-0">
<input type="radio" name="supportWork" id="supportWork0" value="None" <?php //echo ($parq->supportWork == 'None')?'checked':''; ?>>
<label for="supportWork0">
None
</label>
</div>
<div class="radio clip-radio radio-primary radio-inline m-b-0">
<input type="radio" name="supportWork" id="supportWork1" value="Moderate" <?php //echo ($parq->supportWork == 'Moderate')?'checked':''; ?>>
<label for="supportWork1">
Moderate
</label>
</div>
<div class="radio clip-radio radio-primary radio-inline m-b-0">
<input type="radio" name="supportWork" id="supportWork2" value="High" <?php //echo ($parq->supportWork == 'High')?'checked':''; ?>>
<label for="supportWork2">
High
</label>
</div>
</div>
</div>-->
<div class="form-group">
    <label class="strong" for="supportFamily">How supportive is your family?</label>
    <select id="supportFamily" name="supportFamily" class="form-control">
        <option value="">-- Select --</option>
        <option value="None" <?php echo $parq->supportFamily == 'None'?'selected':''; ?>>None</option>
        <option value="Moderate" <?php echo $parq->supportFamily == 'Moderate'?'selected':''; ?>>Moderate</option>
        <option value="High" <?php echo $parq->supportFamily == 'High'?'selected':''; ?>>High</option>
    </select>
</div>

<div class="form-group">
    <label class="strong" for="supportFriends">How supportive are your friends?</label>
    <select id="supportFriends" name="supportFriends" class="form-control">
        <option value="">-- Select --</option>
        <option value="None" <?php echo $parq->supportFriends == 'None'?'selected':''; ?>>None</option>
        <option value="Moderate" <?php echo $parq->supportFriends == 'Moderate'?'selected':''; ?>>Moderate</option>
        <option value="High" <?php echo $parq->supportFriends == 'High'?'selected':''; ?>>High</option>
    </select>
</div>

<div class="form-group">
    <label class="strong" for="supportWork">How supportive are your work colleagues?</label>
    <select id="supportWork" name="supportWork" class="form-control">
        <option value="">-- Select --</option>
        <option value="None" <?php echo $parq->supportWork == 'None'?'selected':''; ?>>None</option>
        <option value="Moderate" <?php echo $parq->supportWork == 'Moderate'?'selected':''; ?>>Moderate</option>
        <option value="High" <?php echo $parq->supportWork == 'High'?'selected':''; ?>>High</option>
    </select>
</div>

<div class="form-group">
    <label class="strong">Which best describes your motivation levels</label>
    <?php
    if(!count($parq->motivationImprove))
        $parq->motivationImprove = [];
    ?>
    <select id="motivationImprove" name="motivationImprove" class="form-control motivationImprove" multiple>
        <option value="No motivation" <?php echo in_array('No motivation', $parq->motivationImprove)?'selected':''; ?>>No motivation</option>
        <option value="Low motivation" <?php echo in_array('Low motivation', $parq->motivationImprove)?'selected':''; ?>>Low motivation</option>
        <option value="TEAM motivated" <?php echo in_array('TEAM motivated', $parq->motivationImprove)?'selected':''; ?>>TEAM motivated</option>
        <option value="Self-motivated" <?php echo in_array('Self-motivated', $parq->motivationImprove)?'selected':''; ?>>Self-motivated</option>
    </select>
            <!--<div>                                   
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
          			<input type="checkbox" name="motivationImprove0" id="motivationImprove0" value="No motivation" <?php echo in_array('No motivation', $parq->motivationImprove)?'checked':''; ?>>
                    <label for="motivationImprove0">
                        No motivation
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
         			<input type="checkbox" name="motivationImprove1" id="motivationImprove1" value="Low motivation" <?php echo in_array('Low motivation', $parq->motivationImprove)?'checked':''; ?>>
                    <label for="motivationImprove1">
                        Low motivation
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
          			<input type="checkbox" name="motivationImprove2" id="motivationImprove2" value="TEAM motivated" <?php echo in_array('TEAM motivated', $parq->motivationImprove)?'checked':''; ?>>
                    <label for="motivationImprove2">
                        TEAM motivated
                    </label>
                </div>
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
          			<input type="checkbox" name="motivationImprove3" id="motivationImprove3" value="Self-motivated" <?php echo in_array('Self-motivated', $parq->motivationImprove)?'checked':''; ?>>
                    <label for="motivationImprove3">
                        Self-motivated
                    </label>
                </div>
            </div>-->
        </div>
        
        <div class="form-group">
            <label class="strong" for="smartGoalNotes">SMARTER Goal notes</label>
            @if($parq->smartGoalSpecific == '' || $parq->smartGoalMeasurable == '' || $parq->smartGoalAchievable == '' || $parq->smartGoalRelevent == '' || $parq->smartGoalTime == '')
                  <a class="pull-right btn-add-more openSmartGoal"> Click here to Add Notes</a>
            @endif

            <!-- <a class="pull-right btn-add-more" href="javascript:void(0)" data-toggle="modal" data-target="#myModal">+ Add Smarter Goal</a> -->
              {{-- @php 
              $smart_goal_option_value = explode(',', $parq->smart_goal_option);
              @endphp
            
               <select class="form-control smartG customValDdField" multiple name="smart_goal_option" required>


                  <option value="None" >None</option>
                    <option value="Specific" {{ in_array('Specific', $smart_goal_option_value)?'selected':''  }}>Specific</option>

                    <option value="Measurable" {{ in_array('Measurable', $smart_goal_option_value)?'selected':''  }}>Measurable</option>
                    <option value="Achievable" {{ in_array('Achievable', $smart_goal_option_value)?'selected':''  }}>Achievable</option>
                    <option value="Relevant" {{ in_array('Relevant', $smart_goal_option_value)?'selected':''  }}>Relevant</option>
                    <option value="Time" {{ in_array('Time', $smart_goal_option_value)?'selected':''  }}>Time</option>
            </select> --}}
<!--             <input name="medCondNotes" type="hidden" value="{&quot;Specific&quot;:&quot;Measurable&quot;,&quot;Achievable&quot;:&quot;Relevant&quot;Time}" id="medCondNotes">
 -->           
    
        </div>

        <div class="goal_notes" name="smart_goal_option">
                    @if($parq->smartGoalSpecific != '')
                        <div class="form-group" data-option-val="Specific">
                        <label class="strong medinotes">Specific </label>
                        <input class="form-control" value="{{$parq->smartGoalSpecific}}" name="smartGoalSpecific">
                        </div>
                    @endif
                    @if($parq->smartGoalMeasurable != '')
                        <div class="form-group" data-option-val="Measurable">
                        <label class="strong medinotes">Measurable </label>
                        <input class="form-control" value="{{$parq->smartGoalMeasurable}}" name="smartGoalMeasurable">
                        </div>
                    @endif
                    @if($parq->smartGoalAchievable != '')
                        <div class="form-group" data-option-val="Measurable">
                        <label class="strong medinotes">Achievable </label>
                        <input class="form-control" value="{{$parq->smartGoalAchievable}}" name="smartGoalAchievable">
                        </div>
                    @endif
                    @if($parq->smartGoalRelevent != '')
                        <div class="form-group" data-option-val="Relevent">
                        <label class="strong medinotes">Relevent </label>
                        <input class="form-control" value="{{$parq->smartGoalRelevent}}" name="smartGoalRelevent">
                        </div>
                    @endif
                    @if($parq->smartGoalTime != '')
                        <div class="form-group" data-option-val="Time">
                        <label class="strong medinotes">Time </label>
                        <input class="form-control" value="{{$parq->smartGoalTime}}" name="smartGoalTime">
                        </div>
                    @endif
                    </div>
                      <textarea class="form-control" rows="10" cols="50" id="smartGoalNotes" name="smartGoalNotes">{{$parq->smartGoalNotes}}</textarea> 
        {{--  <div class="med_notes1">
           @php 
                           $smart_goal_option_value = explode(',', $parq->smart_goal_option);

                            @endphp


                            @if(count($smart_goal_option_value))
                                @foreach($smart_goal_option_value as $key)
                                @if($key == 'Specific')
                                <div class="form-group" data-option-val="{{$key}}">
                                          <label class="strong">Specific</label>
                                    <input class="form-control" name="smartGoalSpecific" value="{{$parq->smartGoalSpecific}}" >
                                </div>
                                @endif
                                @if($key == 'Measurable')
                                 <div class="form-group" data-option-val="{{$key}}">
                                    <label class="strong">Measurable</label>
                                    <input class="form-control" name="smartGoalMeasurable" value="{{$parq->smartGoalMeasurable}}" >
                                </div>
                                @endif
                                 @if($key == 'Achievable')
                                  <div class="form-group" data-option-val="{{$key}}">
                                    <label class="strong">Achievable</label>
                                    <input class="form-control" name="smartGoalAchievable" value="{{$parq->smartGoalAchievable}}" >
                                </div>
                                @endif
                                 @if($key== 'Relevant')
                                 <div class="form-group" data-option-val="{{$key}}">
                                    <label class="strong">Relevant</label>
                                    <input class="form-control" name="smartGoalRelevent" value="{{$parq->smartGoalRelevent}}" >
                                </div>
                                @endif
                                 @if($key== 'Time')
                                  <div class="form-group" data-option-val="{{$key}}">
                                    <label class="strong">Time</label>
                                    <input class="form-control" name="smartGoalSpecific" value="{{$parq->smartGoalTime}}" >
                                </div>
                                @endif
                                @endforeach
                                @endif
         </div>
 --}}
       <!--  <div class="med_notes">
            <div class="form-group" data-med="Diabetes">
                <label class="strong medinotes">Specific</label>
                <input class="form-control" value="Specific notes" name="Specific" >
            </div>
        </div> -->
    

    <div class="form-group">
        <label class="strong" for="achieveGoal">How important is it to achieve your goal?</label>
        <select id="achieveGoal" name="achieveGoal" class="form-control">
            <option value="">-- Select --</option>
            <option value="Not" <?php echo $parq->achieveGoal == 'Not'?'selected':''; ?>>Not</option>
            <option value="Somewhat" <?php echo $parq->achieveGoal == 'Somewhat'?'selected':''; ?>>Somewhat</option>
            <option value="Very" <?php echo $parq->achieveGoal == 'Very'?'selected':''; ?>>Very</option>
            <option value="Extremely" <?php echo $parq->achieveGoal == 'Extremely'?'selected':''; ?>>Extremely</option>
        </select>
    </div>

    <div class="form-group">
        <label class="strong" for="goalNotes">Please provide any additional notes you think are relevant</label>
        <div>
            <textarea class="form-control" rows="10" cols="50" id="goalNotes" name="goalNotes">{{$parq->goalNotes}}</textarea>
        </div>
    </div>
</fieldset>
</div> 