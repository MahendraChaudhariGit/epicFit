<div class="col-md-6">
	<fieldset class="padding-15">
    	<legend>General History</legend>
        <div class="form-group">
            <label class="strong" for="activity">What physical activity do you or have you done in the past, including occupation?</label></br>
             <?php if($parq->activity == 'resistancecardio')
            {
                echo "Resistance & Cardio";
            }
            else{
                echo ucfirst($parq->activity);
          
            }
        ?>  
        </div>
        
        <div class="form-group">
            <label class="strong" for="activityOther">Please provide physical activity details including physical activities at work</label></br>
          {{ $parq->activityOther ? ucfirst($parq->activityOther) : '--'}}  
        </div>
        
        <div class="form-group">
            <label class="strong" for="frequency">How often are you physically active per week (Current average)?</label></br>
            {{ $parq->frequency ? $parq->frequency : '--'}}  
        </div>
        
        <div class="form-group">
            <label class="strong" for="paPerWeek">Currently what is your total duration of physical activity per week (Current average)</label></br>
             
            <?php
            echo $parq->paPerWeek;
            ?>
		</div>
        
        <div class="form-group">
            <label class="strong" for="intensity">What intensity is your current physical activity (Current average)? </label></br>
            <?php
			if(count($parq->intensity)){
				echo(implode(',',$parq->intensity));
			} else { echo '--' ; } ?>
		</div>
        <div class="form-group">
            <label class="strong" for="paSession">What is your preferred duration of physical activity per session?</label></br>
            
             <?php
            if(count($parq->paSession)){
                echo(implode(',',$parq->paSession));
            } else { echo '--' ; } ?>  
        </div>
        
        <div class="form-group">
            <label class="strong" for="paIntensity">What is your preferred intensity of physical activity?</label></br>
            <?php
          	if(count($parq->paIntensity)) { 
				echo(implode(',',$parq->paIntensity));
			} else {
				echo '--';
			}
			?>
		</div>
	</fieldset>
</div>

<div class="col-md-6">
	<fieldset class="padding-15">
    	<legend>Preferences</legend>
        <div class="form-group">
            <label class="strong" for="paEnjoy">What physical activities do you personally enjoy and why?</label></br>  		
            {{ $parq->paEnjoy ? ucfirst($parq->paEnjoy) : '--'}} 	
         </div>
        
        <div class="form-group">
            <label class="strong" for="paEnjoyNo">What physical activities do you not enjoy and why?</label> </br> 			
            {{ $parq->paEnjoyNo ? $parq->paEnjoyNo : '--'}} 
         </div>
  
        <div class="form-group table-responsive">
            <label class="strong">What days and times day do you prefer to train?</label>
            {!! renderPreferredDays($parq->preferredTraingDays) !!}
        </div>
        
        <div class="form-group">
        	<label class="strong" for="epNotes">Please provide any additional notes you think are relevant</label></br>
            {{ $parq->epNotes ? $parq->epNotes : '--'}} 
       </div>
	</fieldset>
</div>