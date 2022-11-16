<div class="col-md-6">
	<fieldset class="padding-15">
    	<legend>Goals & Priorities</legend>
		<div class="form-group">
                <label class="strong" for="referrer">Prioritise and drag the following fitness components relating to your specific needs: Number from 1 (most important) to 5 (least important)</label>
                <div class="dd" id="nestablee">
                    <ol class="dd-list">
						<?php
                        $cLabels = array('goalFitnessComponents0' => 'Body Fat %', 'goalFitnessComponents1' => 'Cardio Endurance', 'goalFitnessComponents2' => 'Flexibility & mobility', 'goalFitnessComponents3' => 'Muscular strength', 'goalFitnessComponents4' => 'Explosive power');
                        $comps = $parq->goalFitnessComponents ? json_decode($parq->goalFitnessComponents, true) : array(array('id' => 'goalFitnessComponents0'), array('id' => 'goalFitnessComponents1'), array('id' => 'goalFitnessComponents2'), array('id' => 'goalFitnessComponents3'), array('id' => 'goalFitnessComponents4'));

                        ?>
                        @foreach($comps as $carr)
                            <li class="dd-item" data-id="{{ $carr['id'] }}">
                                <div class="">{{ $cLabels[$carr['id']] }}</div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        
        <div class="form-group">
            <label for="goalHealthWellness" class="strong">Select your specific goals</label></br>
            <?php
			if(count($parq->goalHealthWellness)>0) { 
				 echo '<span data-realtime="goals">'.(implode('</br> ', $parq->goalHealthWellness)).'</span>';
			} else { echo '--' ; } ?>
		</div>
        
        <div class="form-group">
            <label class="strong">Please indicate areas you would like to strengthen, tone, rehabilitate & increase flexibility and/or mobility</label>
      
            @if(count($parq->footImprove)>0)
            <h5 style="margin-bottom: 0px;"><b>Ankle & Feet Improve</b></h5>
            @if(count($parq->footImprove)>0)
                {{  $footImprove = array_diff($parq->footImprove, ['L_All', 'R_All']) }}
            {!! str_replace('_',' ',implode("</br>",$footImprove)) !!}
            @if($parq->footImproveNotes != '')
            </br>Notes-<?php echo $parq->footImproveNotes; ?>
            @endif
            @endif
            </br>
        @endif

        @if(count($parq->legImprove)>0)
            </br><h5 style="margin-bottom: 0px;"><b>Leg Improve</b></h5>
            @if(count($parq->legImprove)>0)
                {{  $legImprove = array_diff($parq->legImprove, ['L_All', 'R_All']) }}
            {!! str_replace('_',' ',implode("</br>",$legImprove)) !!}
            @if($parq->legImproveNotes != '')
            </br>Notes-<?php echo $parq->legImproveNotes; ?>
            @endif
            @endif
            </br>
        @endif
         
        @if(count($parq->hipInjury)>0)
            </br><h5 style="margin-bottom: 0px;"><b>Hip Improve</b></h5>
            @if(count($parq->hipInjury)>0)
                {{  $hipInjury = array_diff($parq->hipInjury, ['L_All', 'R_All']) }}
            {!! str_replace('_',' ',implode("</br>",$hipInjury)) !!}
            @if($parq->hipInjuryNotes != '')
            </br>Notes-<?php echo $parq->hipInjuryNotes; ?>
            @endif
            @endif
            </br>
        @endif

        

        @if(count($parq->coreImprove)>0)
            </br><h5 style="margin-bottom: 0px;"><b>Core Improve</b></h5>
            @if(count($parq->coreImprove)>0)
                {{  $coreImprove = array_diff($parq->coreImprove, ['All']) }}
            {!! implode("</br>", $coreImprove) !!}
            @if($parq->coreImproveNotes != '')
            </br>Notes-<?php echo $parq->coreImproveNotes; ?>
            @endif
            @endif
            </br>
        @endif

        @if(count($parq->backImprove)>0)
            </br><h5 style="margin-bottom: 0px;"><b>Back Improve</b></h5>
            @if(count($parq->backImprove)>0)
                {{  $backImprove = array_diff($parq->backImprove, ['All']) }}
            {!! implode("</br>", $backImprove) !!}
            @if($parq->backImproveNotes != '')
            </br>Notes-<?php echo $parq->backImproveNotes; ?>
            @endif
            @endif
            </br>
        @endif


        @if(count($parq->chestImprove)>0)
            </br><h5 style="margin-bottom: 0px;"><b>Chest Improve</b></h5>
            @if(count($parq->chestImprove)>0)
                {{  $chestImprove = array_diff($parq->chestImprove, ['All']) }}
            {!! implode("</br>", $chestImprove) !!}
            @if($parq->chestImproveNotes != '')
            </br>Notes-<?php echo $parq->chestImproveNotes; ?>
            @endif
            @endif
            </br>
        @endif

        @if(count($parq->shouldersImprove)>0)
            </br><h5 style="margin-bottom: 0px;"><b>Shoulder  Improve</b></h5>
            @if(count($parq->shouldersImprove)>0)
                {{  $shouldersImprove = array_diff($parq->shouldersImprove, ['L_All', 'R_All']) }}
            {!! str_replace('_',' ',implode("</br>",$shouldersImprove)) !!}
            @if($parq->shouldersImproveNotes != '')
            </br>Notes-<?php echo $parq->shouldersImproveNotes; ?>
            @endif
            @endif
            </br>
         @endif

         
            @if(count($parq->armsImprove)>0)
                </br> <h5 style="margin-bottom: 0px;"><b>Arms Improve</b></h5>
                @if(count($parq->armsImprove)>0)
                    {{  $armsImprove = array_diff($parq->armsImprove, ['L_All', 'R_All']) }}
                {!! str_replace('_',' ',implode("</br>",$armsImprove)) !!}
                @if($parq->armsImproveNotes != '')
                </br>Notes-<?php echo $parq->armsImproveNotes; ?>
                @endif
                @endif
                </br>
            @endif

        @if(count($parq->handImprove)>0)
            </br><h5 style="margin-bottom: 0px;"><b>Hand Improve</b></h5>
            @if(count($parq->handImprove)>0)
                {{  $handImprove = array_diff($parq->handImprove, ['L_All', 'R_All']) }}
            {!! str_replace('_',' ',implode("</br>",$handImprove)) !!}
            @if($parq->handImproveNotes != '')
            </br>Notes-<?php echo $parq->handImproveNotes; ?>
            @endif
            @endif
            </br>
        @endif
        
         @if(count($parq->calvesImprove)>0)
            </br> <h5 style="margin-bottom: 0px;"><b>Calves Improve</b></h5>
            @if(count($parq->calvesImprove)>0)
                {{ $calvesImprove = array_diff($parq->calvesImprove, ['All']) }}
            {!! implode("</br>", $calvesImprove) !!}
            @if($parq->calvesImproveNotes != '')
            </br>Notes-<?php echo $parq->calvesImproveNotes; ?>
            @endif
            @endif
            </br>
        @endif

           @if(count($parq->quadsImprove)>0)
            </br><h5 style="margin-bottom: 0px;"><b>Quads Improve</b></h5>
            @if(count($parq->quadsImprove)>0)
                {{ $quadsImprove = array_diff($parq->quadsImprove, ['All']) }}
            {!! implode("</br>", $quadsImprove) !!}
            @if($parq->quadsImproveNotes != '')
            </br>Notes-<?php echo $parq->quadsImproveNotes; ?>
            @endif
            @endif
            </br>
        @endif

        @if(count($parq->neckImprove)>0)
            </br><h5 style="margin-bottom: 0px;"><b>Neck Improve</b></h5>
            @if(count($parq->neckImprove)>0)
                {{  $neckImprove = array_diff($parq->neckImprove, ['All']) }}
            {!! implode("</br>",$neckImprove) !!}
            @if($parq->neckImproveNotes != '')
            </br>Notes-<?php echo $parq->neckImproveNotes; ?>
            @endif
            @endif
            </br>
        @endif

        @if(count($parq->headImprove)>0)
          </br><h5 style="margin-bottom: 0px;"><b>Head Improve</b></h5>
            @if(count($parq->headImprove)>0)
             {{  $headImprove = array_diff($parq->headImprove, ['All']) }}
            {!! implode("</br>",$headImprove) !!}
            @if($parq->headImproveNotes != '')
            </br>Notes-<?php echo $parq->headImproveNotes; ?>
            @endif
            @endif
            </br>
         @endif

        </div>
        
      <div class="form-group">
            <label class="strong" for="lifestyleImprove">What areas of your lifestyle are you willing to improve to achieve your goal?</label></br>
            <?php
			if(count($parq->lifestyleImprove)){
				print_r(implode(', ', $parq->lifestyleImprove));
			} else { echo '--' ; } ?>
		</div>
    </fieldset>
</div>

<div class="col-md-6">
	<fieldset class="padding-15">
    	<legend>Needs & Support</legend>
        <div class="form-group">
            <label class="strong" for="goalWantTobe">I want to be</label></br>
            <?php //print_r(count($parq->goalWantTobe)); die;
            if(count($parq->goalWantTobe)){
                print_r(implode(',',$parq->goalWantTobe));
			} else { echo '--' ; } ?>
        </div>
        
        <div class="form-group">
            <label class="strong" for="goalWantfeel">I want to feel</label></br>
            <?php
			if(count($parq->goalWantfeel)){ 
                 print_r(implode(',',$parq->goalWantfeel));
			} else { echo '--' ; } ?>
        </div>
        
        <div class="form-group">
            <label class="strong" for="goalWantHave">I want to have</label></br>
            <?php
            if(count($parq->goalWantHave)){
                print_r(implode(',',$parq->goalWantHave));
			} else { echo '--' ; } ?>
        </div>
        <div class="form-group">
            <label class="strong" for="supportFamily">How supportive is your family?</label></br>
             {{ $parq->supportFamily ? $parq->supportFamily : '--'}} 
		</div>
        
        <div class="form-group">
            <label class="strong" for="supportFriends">How supportive are your friends?</label></br>
            {{ $parq->supportFriends ? $parq->supportFriends : '--'}} 
</div>
        
        <div class="form-group">
            <label class="strong" for="supportWork">How supportive are your work colleagues?</label></br>
           {{ $parq->supportWork ? $parq->supportWork : '--'}} 
</div>
        
        <div class="form-group">
            <label class="strong">Which best describes your motivation levels</label></br>
            <?php
            if(count($parq->motivationImprove)) { 
               print_r(implode(',',$parq->motivationImprove));
			} else { echo '--' ; } ?>
        </div>
        
       
        @if($parq->smartGoalSpecific)
          <div class="form-group">
            <label class="strong" for="smartGoalNotes">SMARTER Specific Goal notes</label></br>

            <div>
            {{ $parq->smartGoalSpecific ? ucfirst($parq->smartGoalSpecific)  : '--'}}
           </div>
        </div>
         @endif
         @if($parq->smartGoalMeasurable)
           <div class="form-group">
            <label class="strong" for="smartGoalNotes">SMARTER Measurable Goal notes</label></br>

            <div>
            {{ $parq->smartGoalMeasurable ? ucfirst($parq->smartGoalMeasurable) : '--'}}
           </div>
        </div>
        @endif
        @if($parq->smartGoalAchievable)
           <div class="form-group">
            <label class="strong" for="smartGoalNotes">SMARTER Achievable Goal notes</label></br>

            <div>
            {{ $parq->smartGoalAchievable ? ucfirst($parq->smartGoalAchievable) : '--'}}
           </div>
        </div>
        @endif
         @if($parq->smartGoalRelevent)
             <div class="form-group">
            <label class="strong" for="smartGoalNotes">SMARTER Relevent Goal notes</label></br>

            <div>
            {{ $parq->smartGoalRelevent ? ucfirst($parq->smartGoalRelevent) : '--'}}
           </div>
        </div>
        @endif
        @if($parq->smartGoalTime)
           <div class="form-group">
            <label class="strong" for="smartGoalNotes">SMARTER Time Goal notes</label></br>

            <div>
            {{ $parq->smartGoalTime ? ucfirst($parq->smartGoalTime) : '--'}}
           </div>
        </div>
        @endif
         <div class="form-group">
            <label class="strong" for="smartGoalNotes">SMARTER Goal notes</label></br>

            <div>
            {{ $parq->smartGoalNotes ? ucfirst($parq->smartGoalNotes) : '--'}}
           </div>
        </div>
        <div class="form-group">
            <label class="strong" for="achieveGoal">How important is it to achieve your goal?</label></br>
            {{ $parq->achieveGoal ? $parq->achieveGoal : '--'}} 
        </div>

        <div class="form-group">
          <label class="strong" for="goalNotes">Notes</label></br>
           {{ $parq->goalNotes ? $parq->goalNotes : '--'}} 
        </div>
    </fieldset>
</div>
