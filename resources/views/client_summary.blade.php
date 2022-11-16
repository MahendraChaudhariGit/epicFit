@extends('blank')

@section('page-title')
	<span data-realtime="firstName">{{ $clients->firstname }}</span> <span data-realtime="lastName">{{ $clients->lastname }}</span>
@stop

@section('content')
<div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">Panel Configuration</h4>
            </div>
            <div class="modal-body">
                Here will be a configuration form
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary">
                    Save changes
                </button>
            </div>
        </div>
    </div>
</div>

@include('includes.partials.pic_crop_model')

@include('includes.partials.edit_field_modal', ['entity' => "client", 'entityId' => $clients->id])

<div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="strong" for="referrer">Where did you hear about EPIC?</label>
                <div><?php echo $parq->hearUs;?></div>
            </div>
            
            <div class="form-group">
                <label class="strong">Which company or person referred you to EPIC?</label>
				<div><?php echo $parq->referralNetwork;?></div>                
            </div>
            
            <div class="form-group">
                <label class="strong" for="firstName">What is your first name? *</label>
				<div><?php echo $parq->firstName;?></div>                   
            </div>
        
            <div class="form-group">
                <label class="strong" for="lastName">What is your last name? *</label>
				<div><?php echo $parq->lastName;?></div>                  
            </div>
        
            <div class="form-group">
                <label class="strong">I Identify my gender as *</label>
				<div><?php echo $parq->gender;?></div>   
            </div>
            
            <div class="form-group">
            	<label class="strong">How tall are you? </label>
                <div><?php echo $parq->heightUnit;?></div>  
				<div><?php echo $parq->height;?></div>
        	</div>
        
        	<div class="form-group">
                <label class="strong">How much do you weigh? </label>
                <div><?php echo $parq->weightUnit;?></div> 
				<div><?php echo $parq->weight;?></div>                
            </div>
			
			<div class="form-group">
                <label class="strong">Photo </label>
                <div class="m-t-10">
                    	@if($clients->profilepic)
                        	<img src="{{ asset('uploads/thumb_'.$clients->profilepic) }}" class="clientPreviewPics previewPics" />
                        @else
                        	<img class="hidden clientPreviewPics previewPics" />
                        @endif
                    </div>				            
            </div>
            
    	</div>
		
        <div class="col-md-6">
        	<div class="form-group clearfix">
            	<label class="strong" for="dd">What is your birthdate? *</label>
				<div><?php echo $parq->birthDay." - ". $parq->birthMonth ." - ".$parq->birthYear;?></div> 
            </div>
                    
            <div class="form-group">
                <label class="strong" for="occupation">What is your occupation?</label>
				<div><?php echo $parq->occupation;?></div>     
            </div>
        
            <div class="form-group">
                <label class="strong" for="primEm">Please provide your primary email address *</label>
				<div><?php echo $parq->email;?></div>
            </div>
        
            <div class="form-group">
                <label class="strong" for="contactNo">Please provide your phone number * & address</label>
				<input id="contactNo" disabled name="contactNo" type="tel" class="form-control cntryCode numericField" maxlength="16" minlength="5" required value="{{$parq->contactNo}}" data-realtime="phone">
            </div>
        
            <div class="form-group">
                <label class="strong" for="ecName">Please provide the name of your emergency contact *</label>
				<div><?php echo $parq->ecName;?></div> 
            </div>
            
            <div class="form-group">
                <label class="strong" for="ecNumber">Please provide the phone number of your emergency contact *</label>
				<div><?php echo $parq->ecNumber;?></div> 
            </div>
        
            <div class="form-group">
                <label class="strong" for="notes">Please provide any additional notes you think are relevant</label>
				<div><?php echo $parq->notes;?></div>
            </div>
        

    	</div>
		
		<div class="col-md-6">

    	<legend>General History</legend>
        <div class="form-group">
            <label class="strong" for="activity">What physical activity do you or have you done in the past, including occupation? *</label>
            <select id="activity" name="activity" class="form-control customValDdField" required>
                <option value="">-- Select --</option>
                <option value="none" <?php echo $parq->activity == 'none'?'selected':''; ?>>None</option>
                <option value="cardio" <?php echo $parq->activity == 'cardio'?'selected':''; ?>>Cardio</option>
                <option value="resistance" <?php echo $parq->activity == 'resistance'?'selected':''; ?>>Resistance</option>
                <option value="resistancecardio" <?php echo $parq->activity == 'resistancecardio'?'selected':''; ?>>Cardio & Resistance</option>
                <option value="other" <?php echo $parq->activity == 'other'?'selected':''; ?>>Other</option>
            </select>
		</div>
        
        <div class="form-group">
            <label class="strong" for="activityOther">Please provide physical activity details including physical activities at work</label>
            <div>
            <textarea class="form-control" rows="10" cols="50" id="activityOther" name="activityOther">{{$parq->activityOther}}</textarea>
            </div>
        </div>
        
        <div class="form-group">
            <label class="strong" for="frequency">How often are you physically activity per week (Current average)? *</label>
                <select id="frequency" name="frequency" class="form-control customValDdField" required>
				<option value="">-- Select --</option>
                <option value="1" <?php echo $parq->frequency == '1'?'selected':''; ?>>1</option>
                <option value="2" <?php echo $parq->frequency == '2'?'selected':''; ?>>2</option>
                <option value="3-5" <?php echo $parq->frequency == '3-5'?'selected':''; ?>>3-5</option>
                <option value="6-7" <?php echo $parq->frequency == '6-7'?'selected':''; ?>>6-7</option>
                <option value="Twice daily vigourous" <?php echo $parq->frequency == 'Twice daily vigourous'?'selected':''; ?>>Twice daily vigorous</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="strong" for="paPerWeek">Currently what is your total duration of physical activity per week (Current average)? *</label>
            <select id="paPerWeek" name="paPerWeek" class="form-control customValDdField" required>
                
				<option value="">-- Select --</option>
                <option value="30 min" <?php echo $parq->paPerWeek == '30 min'?'selected':''; ?>>30 min</option>
                <option value="60 min" <?php echo $parq->paPerWeek == '60 min'?'selected':''; ?>>60 min</option>
                <option value="90 min" <?php echo $parq->paPerWeek == '90 min'?'selected':''; ?>>90 min</option>
                <option value="120-150 min" <?php echo $parq->paPerWeek == '120-150 min'?'selected':''; ?>>120-150 min</option>
                <option value="150 min +" <?php echo $parq->paPerWeek == '150 min +'?'selected':''; ?>>150 min +</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="strong" for="intensity">What intensity is your current physical activity (Current average)? *</label>
            <?php
			if(!count($parq->intensity))
				$parq->intensity = [];
			?>
            <select id="intensity" name="intensity" class="form-control intensity customValDdField" required multiple>
                
                <option value="sedentary" <?php echo in_array('sedentary', $parq->intensity)?'selected':''; ?>>Sedentary</option>
                <option value="light" <?php echo in_array('light', $parq->intensity)?'selected':''; ?>>Light</option>
                <option value="moderate" <?php echo in_array('moderate', $parq->intensity)?'selected':''; ?>>Moderate</option>
                <option value="vigorous" <?php echo in_array('vigorous', $parq->intensity)?'selected':''; ?>>Vigorous</option>
                <option value="high" <?php echo in_array('high', $parq->intensity)?'selected':''; ?>>High</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="strong" for="paSession">What is your preferred duration of physical activity per session?</label>
            <select id="paSession" name="paSession" class="form-control">
                <option value="">-- Select --</option>
                <option value="30 min" <?php echo $parq->paSession == '30 min'?'selected':''; ?>>30 min</option>
                <option value="60 min" <?php echo $parq->paSession == '60 min'?'selected':''; ?>>60 min</option>
                <option value="90 min" <?php echo $parq->paSession == '90 min'?'selected':''; ?>>90 min</option>
                <option value="120-150 min" <?php echo $parq->paSession == '120-150 min'?'selected':''; ?>>120-150 min</option>
                <option value="150 min +" <?php echo $parq->paSession == '150 min +'?'selected':''; ?>>150 min +</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="strong" for="paIntensity">What is your preferred intensity of physical activity?</label>
            <?php
			if(!count($parq->paIntensity))
				$parq->paIntensity = [];
			?>
            <select id="paIntensity" name="paIntensity" class="form-control intensity" multiple>
				<option value="sedentary" <?php echo in_array('sedentary', $parq->paIntensity)?'selected':''; ?>>Sedentary</option>
				<option value="light" <?php echo in_array('light', $parq->paIntensity)?'selected':''; ?>>Light</option>
				<option value="moderate" <?php echo in_array('moderate', $parq->paIntensity)?'selected':''; ?>>Moderate</option>
				<option value="vigorous" <?php echo in_array('vigorous', $parq->paIntensity)?'selected':''; ?>>Vigorous</option>
				<option value="high" <?php echo in_array('high', $parq->paIntensity)?'selected':''; ?>>High</option>
			</select>
        </div>

</div>


<div class="col-md-6">
	<fieldset class="padding-15">
    	<legend>Preferences</legend>
        <div class="form-group">
            <label class="strong" for="paEnjoy">What physical activities do you personally enjoy and why? *</label>  
            <input id="paEnjoy" name="paEnjoy" type="text" class="form-control" value="{{$parq->paEnjoy}}" required>
        </div>
        
        <div class="form-group">
            <label class="strong" for="paEnjoyNo">What physical activities do you not enjoy and why?</label>  
            <input id="paEnjoyNo" name="paEnjoyNo" type="text" class="form-control input-md" value="{{$parq->paEnjoyNo}}">
         </div>
  
        <div class="form-group">
            <label class="strong">What days and times day do you prefer to train?</label>
            <?php
            if(!count($parq->preferredTraingDays0))
                $parq->preferredTraingDays0 = [];
            ?>
            <select name="preferredTraingDays0" class="form-control preferredTraingDays" multiple title="Mon">
                <option value="am" <?php echo in_array('am', $parq->preferredTraingDays0)?'selected':''; ?>>Mon AM</option>
                <option value="pm" <?php echo in_array('pm', $parq->preferredTraingDays0)?'selected':''; ?>>Mon PM</option>
            </select>
        </div>
        
        <div class="form-group">
			<?php
            if(!count($parq->preferredTraingDays1))
                $parq->preferredTraingDays1 = [];
            ?>
            <select name="preferredTraingDays1" class="form-control preferredTraingDays" multiple title="Tue">
                <option value="am" <?php echo in_array('am', $parq->preferredTraingDays1)?'selected':''; ?>>Tue AM</option>
                <option value="pm" <?php echo in_array('pm', $parq->preferredTraingDays1)?'selected':''; ?>>Tue PM</option>
            </select>
        </div>
        
        <div class="form-group">
			<?php
            if(!count($parq->preferredTraingDays2))
            $parq->preferredTraingDays2 = [];
            ?>
            <select name="preferredTraingDays2" class="form-control preferredTraingDays" multiple title="Wed">
                <option value="am" <?php echo in_array('am', $parq->preferredTraingDays2)?'selected':''; ?>>Wed AM</option>
                <option value="pm" <?php echo in_array('pm', $parq->preferredTraingDays2)?'selected':''; ?>>Wed PM</option>
            </select>
        </div>
        
        <div class="form-group">
			<?php
            if(!count($parq->preferredTraingDays3))
                $parq->preferredTraingDays3 = [];
            ?>
            <select name="preferredTraingDays3" class="form-control preferredTraingDays" multiple title="Thurs">
                <option value="am" <?php echo in_array('am', $parq->preferredTraingDays3)?'selected':''; ?>>Thurs AM</option>
                <option value="pm" <?php echo in_array('pm', $parq->preferredTraingDays3)?'selected':''; ?>>Thurs PM</option>
            </select>
        </div>
        
        <div class="form-group">
			<?php
            if(!count($parq->preferredTraingDays4))
                $parq->preferredTraingDays4 = [];
            ?>
            <select name="preferredTraingDays4" class="form-control preferredTraingDays" multiple title="Fri">
                <option value="am" <?php echo in_array('am', $parq->preferredTraingDays4)?'selected':''; ?>>Fri AM</option>
                <option value="pm" <?php echo in_array('pm', $parq->preferredTraingDays4)?'selected':''; ?>>Fri PM</option>
            </select>
        </div>
        
        <div class="form-group">
			<?php
            if(!count($parq->preferredTraingDays5))
                $parq->preferredTraingDays5 = [];
            ?>
            <select name="preferredTraingDays5" class="form-control preferredTraingDays" multiple title="Sat">
                <option value="am" <?php echo in_array('am', $parq->preferredTraingDays5)?'selected':''; ?>>Sat AM</option>
                <option value="pm" <?php echo in_array('pm', $parq->preferredTraingDays5)?'selected':''; ?>>Sat PM</option>
            </select>
        </div>
        
        <div class="form-group">
			<?php
            if(!count($parq->preferredTraingDays6))
                $parq->preferredTraingDays6 = [];
            ?>
            <select name="preferredTraingDays6" class="form-control preferredTraingDays" multiple title="Sun">
                <option value="am" <?php echo in_array('am', $parq->preferredTraingDays6)?'selected':''; ?>>Sun AM</option>
                <option value="pm" <?php echo in_array('pm', $parq->preferredTraingDays6)?'selected':''; ?>>Sun PM</option>
            </select>
        </div>
        
        <div class="form-group">
        	<label class="strong" for="epNotes">Please provide any additional notes you think are relevant</label>
            <div>
        	<textarea class="form-control" rows="10" cols="50" id="epNotes" name="epNotes">{{$parq->epNotes}}</textarea>
            </div>
        </div>

	</fieldset>
</div>

<div class="col-md-6">
            <div class="form-group">
                <label class="strong">Select a body part and injury associated with it, please add relevant notes relating to these injuries</label>
                <button type="button" class="btn btn-o btn-default btn-block" data-toggle="modal" data-target="#injuryModal">Click here to view body parts</button>
            </div>

            <div class="form-group">
                <label class="strong" for="ipfhAdditionalNotes">Please add the relevant notes relating to injuries selected above</label>
                <div>
                <textarea class="form-control"  rows="10" cols="50" id="ipfhAdditionalNotes" name="ipfhAdditionalNotes">{{$parq->ipfhAdditionalNotes}}</textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label class="strong" for="allergies">Do you have any allergies and if yes please list them *</label>
                <select id="allergies" name="allergies" class="form-control customValDdField" required>
                    <option value="">-- Select --</option>
                    <option value="Yes" <?php echo $parq->allergies == 'Yes'?'selected':''; ?>>Yes</option>
                    <option value="No" <?php echo $parq->allergies == 'No'?'selected':''; ?>>No</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="strong" for="allergiesList">Allergy details</label>
                <textarea class="form-control min-height-180" id="allergiesList" name="allergiesList">{{$parq->allergiesList}}</textarea>
            </div>
            
            <div class="form-group">
                <label class="strong" for="chronicMedication">Do you take any chronic Medication and if yes please provide details of the medication *</label>
                <select id="chronicMedication" name="chronicMedication" class="form-control customValDdField" required>
                    <option value="">-- Select --</option>
                    <option value="Yes" <?php echo $parq->chronicMedication == 'Yes'?'selected':''; ?>>Yes</option>
                    <option value="No" <?php echo $parq->chronicMedication == 'No'?'selected':''; ?>>No</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="strong" for="chronicMedicationList">Medication details</label>
                <textarea class="form-control min-height-180" id="chronicMedicationList" name="chronicMedicationList">{{$parq->chronicMedicationList}}</textarea>
            </div>
            
           
		</div>
		
		<div class="col-md-6">
        
      
            <div class="form-group">
                <label for="medicalCondition" class="strong">Have you personally suffered from *</label>
                <?php
                if(!count($parq->medicalCondition))
                    $parq->medicalCondition = [];
                ?>
                <select class="form-control medCond customValDdField" multiple name="medicalCondition" required>
                    <option value="Diabetes" <?php echo in_array('Diabetes', $parq->medicalCondition)?'selected':''; ?>>Diabetes</option>
                    <option value="High/Low blood pressure under medication" <?php echo in_array('High/Low blood pressure under medication', $parq->medicalCondition)?'selected':''; ?>>High/Low blood pressure under medication</option>
                    <option value="Stroke" <?php echo in_array('Stroke', $parq->medicalCondition)?'selected':''; ?>>Stroke</option>
                    <option value="Asthma" <?php echo in_array('Asthma', $parq->medicalCondition)?'selected':''; ?>>Asthma</option>
                    <option value="Chest pain" <?php echo in_array('Chest pain', $parq->medicalCondition)?'selected':''; ?>>Chest pain</option>
                    <option value="Arthritis" <?php echo in_array('Arthritis', $parq->medicalCondition)?'selected':''; ?>>Arthritis</option>
                    <option value="Osteoporosis" <?php echo in_array('Osteoporosis', $parq->medicalCondition)?'selected':''; ?>>Osteoporosis</option>
                    <option value="High cholesterol" <?php echo in_array('High cholesterol', $parq->medicalCondition)?'selected':''; ?>>High cholesterol</option>
                    <option value="Heart conditions" <?php echo in_array('Heart conditions', $parq->medicalCondition)?'selected':''; ?>>Heart conditions</option>
                </select>
            </div>
            
           
            
            <div class="form-group">
                <label for="relMedicalCondition" class="strong">Has any direct family member (parents, grandparents, siblings) suffered from *</label>
                <?php
                if(!count($parq->relMedicalCondition))
                    $parq->relMedicalCondition = [];
                ?>
                <select class="form-control medCond customValDdField" multiple name="relMedicalCondition" required>
                    <option value="None" <?php echo in_array('None', $parq->relMedicalCondition)?'selected':''; ?>>None</option>
                    <option value="Diabetes" <?php echo in_array('Diabetes', $parq->relMedicalCondition)?'selected':''; ?>>Diabetes</option>
                    <option value="High/Low blood pressure under medication" <?php echo in_array('High/Low blood pressure under medication', $parq->relMedicalCondition)?'selected':''; ?>>High/Low blood pressure under medication</option>
                    <option value="Stroke" <?php echo in_array('Stroke', $parq->relMedicalCondition)?'selected':''; ?>>Stroke</option>
                    <option value="Asthma" <?php echo in_array('Asthma', $parq->relMedicalCondition)?'selected':''; ?>>Asthma</option>
                    <option value="Chest pain" <?php echo in_array('Chest pain', $parq->relMedicalCondition)?'selected':''; ?>>Chest pain</option>
                    <option value="Arthritis" <?php echo in_array('Arthritis', $parq->relMedicalCondition)?'selected':''; ?>>Arthritis</option>
                    <option value="Osteoporosis" <?php echo in_array('Osteoporosis', $parq->relMedicalCondition)?'selected':''; ?>>Osteoporosis</option>
                    <option value="High cholesterol" <?php echo in_array('High cholesterol', $parq->relMedicalCondition)?'selected':''; ?>>High cholesterol</option>
                    <option value="Heart conditions" <?php echo in_array('Heart conditions', $parq->relMedicalCondition)?'selected':''; ?>>Heart conditions</option>
                </select>
            </div>
            
          
            
            <div class="form-group">
                <label class="strong" for="smoking">Do you or have you smoked within the last six months and if yes how many per day *</label>
                <select id="smoking" name="smoking" class="form-control customValDdField" required>
                    <option value="">-- Select --</option>
                    <option value="Yes" <?php echo $parq->smoking == 'Yes'?'selected':''; ?>>Yes</option>
                    <option value="No" <?php echo $parq->smoking == 'No'?'selected':''; ?>>No</option>
            	</select>
                <div id="smokeAmnt">
                	<br clear="all" />
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                        <input type="radio" name="smokingPerDay" class="customValRadField" id="smokingPerDay0" value="1-9" <?php echo ($parq->smokingPerDay == '1-9')?'checked':''; ?>>
                        <label for="smokingPerDay0">
                            1-9
                        </label>
                    </div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                        <input type="radio" name="smokingPerDay" class="customValRadField" id="smokingPerDay1" value="10-19" <?php echo ($parq->smokingPerDay == '10-19')?'checked':''; ?>>
                        <label for="smokingPerDay1">
                            10-19
                        </label>
                    </div>
                    <div class="radio clip-radio radio-primary radio-inline m-b-0">
                        <input type="radio" name="smokingPerDay" class="customValRadField" id="smokingPerDay2" value="20+" <?php echo ($parq->smokingPerDay == '20+')?'checked':''; ?>>
                        <label for="smokingPerDay2">
                            20+
                        </label>
                    </div>
                    <span class="help-block m-t--5 m-b-0"></span>
                </div>
                
			</div>
                
            <div class="form-group">
                <label class="strong" for="ipfhNotes">Please provide any additional notes you think are relevant</label>
                <div>
                <textarea class="form-control" rows="10" cols="50" id="ipfhNotes" name="ipfhNotes">{{$parq->ipfhNotes}}</textarea>
                </div>
            </div>
            
            
    	</div>
		
		
		<p>Regular exercise is associated with many health benefits. Increasing physical activity is safe for most people. <br>However, some individuals should check with a physician before they become more physically active.<br><br>
  Completion of this questionnaire is one of the first steps required when planning to increase the amount of physical activity in your life. Please read each question carefully and answer every question honestly:
  </p>
  <table class="table table-bordered table-responsive">
    <tr>
      <th>No.</th>
      <th>Questionnaire</th>
      <th>Yes</th>
      <th>No</th>
    </tr>
    <tr>
      <?php
		if(!count($parq->questionnaire))
			$parq->questionnaire = [];
	   ?>
      <td>1</td>
      <td>Have you ever had a stroke or heart condition or has a physician ever indicated you should restrict your physical activity due to these conditions?*</td>
      <td class="yes"><input type="radio" name="ans0" value="ansYes0" onClick="selectEntireRange(this.checked)" <?php echo in_array('ansYes0', $parq->questionnaire)?'checked':''; ?>/></td>
      <td class="no"><input type="radio" name="ans0" value="ansNo0" <?php echo in_array('ansNo0', $parq->questionnaire)?'checked':''; ?>/></td>
    </tr>
    <tr>
      <td>2</td>
      <td>When at rest or partaking in physical activity do you experience chest pain?*</td>
      <td class="yes"><input type="radio" name="ans1" value="ansYes1" onClick="selectEntireRange(this.checked)" <?php echo in_array('ansYes1', $parq->questionnaire)?'checked':''; ?>/></td>
      <td class="no"><input type="radio" name="ans1" value="ansNo1" <?php echo in_array('ansNo1', $parq->questionnaire)?'checked':''; ?>/></td>
    </tr>
    <tr>
      <td>3</td>
      <td>During physical activity do you ever feel faint, dizzy or lose you balance?*</td>
      <td class="yes"><input type="radio" name="ans2" value="ansYes2" onClick="selectEntireRange(this.checked)" <?php echo in_array('ansYes2', $parq->questionnaire)?'checked':''; ?>/></td>
      <td class="no"><input type="radio" name="ans2" value="ansNo2" <?php echo in_array('ansNo2', $parq->questionnaire)?'checked':''; ?>/></td>
    </tr>
   <tr>
      <td>4</td>
      <td>Do you suffer from any breathing disorders or suffered a severe case of asthma that has medical attention in the last 12 months?*</td>
      <td class="yes"><input type="radio" name="ans3" value="ansYes3" onClick="selectEntireRange(this.checked)" <?php echo in_array('ansYes3', $parq->questionnaire)?'checked':''; ?>/></td>
      <td class="no"><input type="radio" name="ans3" value="ansNo3" <?php echo in_array('ansNo3', $parq->questionnaire)?'checked':''; ?>/></td>
    </tr>
    <tr>
      <td>5</td>
      <td>Do you have insulin dependant diabetes or high blood sugar that has caused complication in the last three months?*</td>
      <td class="yes"><input type="radio" name="ans4" value="ansYes4" onClick="selectEntireRange(this.checked)" <?php echo in_array('ansYes4', $parq->questionnaire)?'checked':''; ?>/></td>
      <td class="no"><input type="radio" name="ans4" value="ansNo4" <?php echo in_array('ansNo4', $parq->questionnaire)?'checked':''; ?>/></td>
    </tr>
    <tr>
      <td>6</td>
      <td>Do you have an injury or orthopaedic condition (such as a back, hip or knee problem) that may worsen due to a change in your physical activity?*</td>
      <td class="yes"><input type="radio" name="ans5" value="ansYes5" onClick="selectEntireRange(this.checked)" <?php echo in_array('ansYes5', $parq->questionnaire)?'checked':''; ?>/></td>
      <td class="no"><input type="radio" name="ans5" value="ansNo5" <?php echo in_array('ansNo5', $parq->questionnaire)?'checked':''; ?>/></td>
    </tr>
    <tr>
      <td>7</td>
      <td>Are you pregnant or have you given birth in the last 6 weeks?*</td>
      <td class="yes"><input type="radio" name="ans6" value="ansYes6" onClick="selectEntireRange(this.checked)" <?php echo in_array('ansYes6', $parq->questionnaire)?'checked':''; ?>/></td>
      <td class="no"><input type="radio" name="ans6" value="ansNo6" <?php echo in_array('ansNo6', $parq->questionnaire)?'checked':''; ?>/></td>
    </tr>
    <tr>
      <td>8</td>
      <td>Are you over the age of 69?*</td>
      <td class="yes"><input type="radio" name="ans7" value="ansYes7" onClick="selectEntireRange(this.checked)" <?php echo in_array('ansYes7', $parq->questionnaire)?'checked':''; ?>/></td>
      <td class="no"><input type="radio" name="ans7" value="ansNo7" <?php echo in_array('ansNo7', $parq->questionnaire)?'checked':''; ?>/></td>
    </tr>
    <tr>
      <td>9</td>
      <td>Do you know of any other reason why you should not partake in, or increase your current physical activity?*</td>
      <td class="yes"><input type="radio" name="ans8" value="ansYes8" onClick="selectEntireRange(this.checked)" <?php echo in_array('ansYes8', $parq->questionnaire)?'checked':''; ?>/></td>
      <td class="no"><input type="radio" name="ans8" value="ansNo8" <?php echo in_array('ansNo8', $parq->questionnaire)?'checked':''; ?>/></td>
    </tr>
    
    <tr>
      <td colspan="4" class="yes">If you answered "yes" to any of the above questions, we suggest you seek approval/ guidance/support from your physician before commencing with any physical activity or drastic change in your nutrition.</td>
    </tr>
    <tr>
      <td colspan="4">Please complete form to supply to your physician to confirm medical clearance.</td>
    </tr>
    <tr>
      <td colspan="4" class="no">If you answered "No" to all of the above questions, and there is no other concerns regarding your health you may commence with a structured physical activity regime that takes into account your ability and any restrictions that may be present.</td>
    </tr>
    <tr>
      <td colspan="4">If your health changes and you answer "Yes" to any of the above questions, seek guidance from a physician before commencing 
with activity.</td>
    </tr>
   </table>

    <div class="form-group">
        <label class="strong" for="parqNotes">Notes</label>
        <div>
        <textarea class="form-control" rows="10" cols="50" id="parqNotes" name="parqNotes">{{$parq->parqNotes}}</textarea>
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
                        $cLabels = array('goalFitnessComponents0' => 'Body Fat %', 'goalFitnessComponents1' => 'Cardio Endurance', 'goalFitnessComponents2' => 'Flexibility &amp; mobility', 'goalFitnessComponents3' => 'Muscular strength', 'goalFitnessComponents4' => 'Explosive power');
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
			?>
            <select class="form-control healthGoals" multiple name="goalHealthWellness" data-realtime="goals">
            	<option value="Health &amp; wellness" <?php echo in_array('Health &amp; wellness', $parq->goalHealthWellness)?'selected':''; ?>>Health &amp; wellness</option>
                <option value="Increased energy" <?php echo in_array('Increased energy', $parq->goalHealthWellness)?'selected':''; ?>>Increased energy</option>
                <option value="Tone" <?php echo in_array('Tone', $parq->goalHealthWellness)?'selected':''; ?>>Tone</option>
                <option value="Injury recovery" <?php echo in_array('Injury recovery', $parq->goalHealthWellness)?'selected':''; ?>>Injury recovery</option>
                <option value="Improved nutrition" <?php echo in_array('Improved nutrition', $parq->goalHealthWellness)?'selected':''; ?>>Improved nutrition</option>
                <option value="Lose weight" <?php echo in_array('Lose weight', $parq->goalHealthWellness)?'selected':''; ?>>Lose weight</option>
                <option value="Improved performance" <?php echo in_array('Improved performance', $parq->goalHealthWellness)?'selected':''; ?>>Improved performance</option>
                <option value="Improved endurance" <?php echo in_array('Improved endurance', $parq->goalHealthWellness)?'selected':''; ?>>Improved endurance</option>
                <option value="Improved Strength &amp; Conditioning" <?php echo in_array('Improved Strength &amp; Conditioning', $parq->goalHealthWellness)?'selected':''; ?>>Improved Strength &amp; Conditioning</option>
			</select>
        </div>
        
        <div class="form-group">
            <label class="strong">Please indicate areas you would like to strengthen, tone, rehabilitate & increase flexibility and/or mobility</label>
            <button type="button" class="btn btn-o btn-default btn-block" data-toggle="modal" data-target="#goalModal">Click here to view body parts</button>
        </div>
        
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
            <label class="strong" for="lifestyleImprove">What areas in your lifestyle are you willing to improve to achieve your goal?</label>
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
            <select id="goalWantTobe" name="goalWantTobe" class="form-control goalWantTobe" multiple>
				<option value="Toned" <?php echo in_array('Toned', $parq->goalWantTobe)?'selected':''; ?>>Toned</option>
				<option value="Fitter" <?php echo in_array('Fitter', $parq->goalWantTobe)?'selected':''; ?>>Fitter</option>
				<option value="Stronger" <?php echo in_array('Stronger', $parq->goalWantTobe)?'selected':''; ?>>Stronger</option>
				<option value="Flexible" <?php echo in_array('Flexible', $parq->goalWantTobe)?'selected':''; ?>>Flexible</option>
			</select>
            
        </div>
        
       
        
        <div class="form-group">
            <label class="strong" for="goalWantfeel">I want to feel</label>
            <?php
            if(!count($parq->goalWantfeel))
                $parq->goalWantfeel = [];
            ?>
            <select id="goalWantfeel" name="goalWantfeel" class="form-control goalWantfeel" multiple>
				<option value="Happier" <?php echo in_array('Happier', $parq->goalWantfeel)?'selected':''; ?>>Happier</option>
				<option value="Energetic" <?php echo in_array('Energetic', $parq->goalWantfeel)?'selected':''; ?>>Energetic</option>
				<option value="Healthier" <?php echo in_array('Healthier', $parq->goalWantfeel)?'selected':''; ?>>Healthier</option>
				<option value="Relaxed" <?php echo in_array('Relaxed', $parq->goalWantfeel)?'selected':''; ?>>Relaxed</option>
			</select>
           
        </div>
        
        <div class="form-group">
            <label class="strong" for="goalWantHave">I want to have</label>
            <?php
            if(!count($parq->goalWantHave))
                $parq->goalWantHave = [];
            ?>
            <select id="goalWantHave" name="goalWantHave" class="form-control goalWantHave" multiple>
				<option value="Less stress" <?php echo in_array('Less stress', $parq->goalWantHave)?'selected':''; ?>>Less stress</option>
				<option value="More time" <?php echo in_array('More time', $parq->goalWantHave)?'selected':''; ?>>More time</option>
				<option value="More fun" <?php echo in_array('More fun', $parq->goalWantHave)?'selected':''; ?>>More fun</option>
				<option value="More control" <?php echo in_array('More control', $parq->goalWantHave)?'selected':''; ?>>More control</option>
			</select>
           
        </div>
        

        <div class="form-group">
            <label class="strong" for="supportFamily">How supportive are your family?</label>
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
            
        </div>
        
        <div class="form-group">
            <label class="strong" for="smartGoalNotes">SMARTER Goal notes</label>
            <a class="pull-right btn-add-more" href="javascript:void(0)" data-toggle="modal" data-target="#myModal">+ Add Smarter Goal</a>
            <div>
            <textarea class="form-control" rows="10" cols="50" id="smartGoalNotes" name="smartGoalNotes">{{$parq->smartGoalNotes}}</textarea>
            </div>
        </div>
        
        <div class="form-group">
            <label class="strong" for="goalNotes">Notes</label>
            <div>
            <textarea class="form-control" rows="10" cols="50" id="goalNotes" name="goalNotes">{{$parq->goalNotes}}</textarea>
            </div>
        </div>
    </fieldset>
</div> 



		
		
		
		
    </div>
				

@endsection
@section('script')
<!-- start: Rating -->
{!! Html::script('assets/plugins/bootstrap-rating/bootstrap-rating.min.js?v='.time()) !!}
<!-- end: Rating -->
    
<!-- start - SUBVIEW JS -->
{!! Html::script('assets/plugins/summernote/dist/summernote.min.js?v='.time()) !!}
<!-- end - SUBVIEW JS -->

<script src="{{ asset('assets/plugins/bootstrap3-typeahead.min.js') }}" ></script>
<script src="{{ asset('assets/js/clients.js') }}"></script>
@stop