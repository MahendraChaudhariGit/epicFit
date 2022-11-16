<fieldset class="padding-15">
    <legend>Injuries & Medical</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="strong">Part and injury associated with it and relevant notes relating to these injuries</label>
                @if(count($parq->footInjury)>0)
                <h5 style="margin-bottom: 0px;"><b>Foot Injury</b></h5>
                {!! implode("</br>",$parq->footInjury) !!}
                @if($parq->footInjuryNotes != '')
                </br>Notes-<?php echo $parq->footInjuryNotes; ?>
                @endif
                </br>
             @endif

             @if(count($parq->legInjury)>0)
                </br><h5 style="margin-bottom: 0px;"><b>Leg Injury</b></h5>
                {!! implode("</br>",$parq->legInjury) !!}
                @if($parq->legInjuryNotes != '')
                </br>Notes-<?php echo $parq->legInjuryNotes; ?>
                @endif
                </br>
            @endif

            @if(count($parq->hipInjury)>0)
                </br><h5 style="margin-bottom: 0px;"><b>Hip Injury</b></h5>
                {!! implode("</br>",$parq->hipInjury) !!}
                @if($parq->hipInjuryNotes != '')
                </br>Notes-<?php echo $parq->hipInjuryNotes; ?>
                @endif
                </br>
             @endif

            @if(count($parq->backInjury)>0)
                </br><h5 style="margin-bottom: 0px;"><b>Back Injury</b></h5>
                {!!implode("</br>",$parq->backInjury) !!}
                @if($parq->backInjuryNotes != '')
                </br>Notes-<?php echo $parq->backInjuryNotes; ?>
                @endif
                </br>
            @endif

            @if(count($parq->shoulderInjury)>0)
                </br><h5 style="margin-bottom: 0px;"><b>Shoulder Injury</b></h5>
                {!! implode("</br>",$parq->shoulderInjury) !!}
                @if($parq->shoulderInjuryNotes != '')
                </br>Notes-<?php echo $parq->shoulderInjuryNotes; ?>
                @endif
                </br>
            @endif

            @if(count($parq->armInjury)>0)
                </br><h5 style="margin-bottom: 0px;"><b>Elbow & Arms Injury</b></h5>
                {!! implode("</br>",$parq->armInjury) !!}
                @if($parq->armInjuryNotes != '')
                </br>Notes-<?php echo $parq->armInjuryNotes; ?>
                @endif
                </br>
            @endif

     
             @if(count($parq->handInjury)>0)
                </br><h5 style="margin-bottom: 0px;"><b>Wrist & Hands Injury</b></h5>
                {!! implode("</br>",$parq->handInjury) !!}
                @if($parq->handInjuryNotes != '')
                </br>Notes-<?php echo $parq->handInjuryNotes; ?>
                @endif
                </br>
             @endif

             @if(count($parq->neckInjury)>0)
                </br><h5 style="margin-bottom: 0px;"><b>Neck Injury</b></h5>
                {!! implode("</br>",$parq->neckInjury) !!}
                @if($parq->neckInjuryNotes != '')
                </br>Notes-<?php echo $parq->neckInjuryNotes; ?>
                @endif
                </br>
            @endif



            @if(count($parq->headInjury)>0)
                </br><h5 style="margin-bottom: 0px;"><b>Head Injury</b></h5>
                {!! implode("</br>",$parq->headInjury) !!}
                @if($parq->headInjuryNotes != '')
                </br>Notes-<?php echo $parq->headInjuryNotes; ?>
                @endif
                </br>
            @endif

         
            </div>

            <div class="form-group">
                <label class="strong" for="ipfhAdditionalNotes">Please add the relevant notes relating to injuries selected above</label></br>
                {{ $parq->ipfhAdditionalNotes ? $parq->ipfhAdditionalNotes : '--'}} 
            </div>
            
            <div class="form-group">
                <label class="strong" for="allergies">Do you have any allergies and if yes please list them</label></br>
          {{ $parq->allergies ? $parq->allergies : '--'}}        
        </div>
            <?php if($parq->allergies == 'Yes') { ?>
            <div class="form-group">
                <label class="strong" for="allergiesList">Allergy details</label></br>
                {{ $parq->allergiesList ? $parq->allergiesList : '--'}} 
             </div>
            <?php } ?>
            <div class="form-group">
                <label class="strong" for="chronicMedication">Do you take any chronic medication and if yes please provide details of the medication </label></br>
      {{ $parq->chronicMedication ? $parq->chronicMedication : '--'}}           
    </div>
        <?php if($parq->chronicMedication == 'Yes') { ?>
        <div class="form-group">
            <label class="strong" for="chronicMedicationList">Medication details</label></br>
            {{ $parq->chronicMedicationList ? $parq->chronicMedicationList : '--'}} 
        </div>
        <?php } ?>
            
            
        </div>
        <div class="col-md-6">
            <div class="form-group table-responsive">
                <label for="medicalCondition" class="strong">Have you personally suffered from </label>
                </br>
                {!! renderDisease($parq, 'self') !!}
            </div>
            
            <div class="form-group table-responsive">
                <label for="relMedicalCondition" class="strong">Has any direct family member (parents, grandparents, siblings) suffered from </label>
                </br>
                {!! renderDisease($parq, 'relative') !!}
            </div>
            
            <div class="form-group">
                <label class="strong" for="smoking">Do you or have you smoked within the last six months and if yes how many per day </label></br>
                <?php 

                if($parq->smoking == 'Yes'){

                    echo $parq->smokingPerDay;
                    } 
                    else
                    {
                        echo "No";
                    }
                    ?> 
            </div>
                
            <div class="form-group">
                <label class="strong" for="ipfhNotes">Please provide any additional notes you think are relevant</label></br>
                {{ $parq->ipfhNotes ? $parq->ipfhNotes : '--'}} 
          </div>
        </div>
    </div>
</fieldset>