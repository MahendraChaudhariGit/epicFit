<div class="col-md-6">
	<fieldset class="padding-15">
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
        <label class="strong" for="frequency">How often are you physically active per week (Current average)? *</label>
        <select id="frequency" name="frequency" class="form-control customValDdField" required>
            <option value="">-- Select --</option>
            <option value="1" <?php echo $parq->frequency == '1'?'selected':''; ?>>1</option>
            <option value="2" <?php echo $parq->frequency == '2'?'selected':''; ?>>2</option>
            <option value="3-5" <?php echo $parq->frequency == '3-5'?'selected':''; ?>>3-5</option>
            <option value="6-7" <?php echo $parq->frequency == '6-7'?'selected':''; ?>>6-7</option>
            <option value="Twice daily vigorous" <?php echo $parq->frequency == 'Twice daily vigorous'?'selected':''; ?>>Twice daily vigorous</option>
        </select>
    </div>

    <div class="form-group">
        <label class="strong" for="paPerWeek">Currently what is your total duration of physical activity per week (Current average)? *</label>
        <?php
        // if(!count($parq->paPerWeek))
        //     $parq->paPerWeek = [];
        ?>

        <select id="paPerWeek" name="paPerWeek" class="form-control paPerWeek customValDdField" required>
            <option value="">-- Select --</option>
            <option value="30 min" <?php echo $parq->paPerWeek == '30 min'?'selected':''; ?>>30 min</option>
            <option value="60 min" <?php echo $parq->paPerWeek == '60 min'?'selected':''; ?>>60 min</option>
            <option value="90 min" <?php echo $parq->paPerWeek == '90 min'?'selected':''; ?>>90 min</option>
            <option value="120-150 min" <?php echo $parq->paPerWeek == '120-150 min'?'selected':''; ?>>120-150 min</option>
            <option value="150 min +" <?php echo $parq->paPerWeek == '150 min +'?'selected':''; ?>>150 min+</option>

            
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
            <option value="Twice-daily-vigorous" <?php echo in_array('Twice-daily-vigorous', $parq->intensity)?'selected':''; ?>>Twice daily vigorous</option>
            
        </select>
    </div>

    <div class="form-group">
        <label class="strong" for="paSession">What is your preferred duration of physical activity per session?</label>
        <?php
        if(!count($parq->paSession))
            $parq->paSession = [];

        ?>
        <select id="paSession" name="paSession" class="form-control paSession customValDdField" required multiple>


            <option value="30 min" <?php echo in_array('30 min', $parq->paSession)?'selected':''; ?>>30 min</option>
            <option value="60 min" <?php echo in_array('60 min', $parq->paSession)?'selected':''; ?>>60 min</option>
            <option value="90 min" <?php echo in_array('90 min', $parq->paSession)?'selected':''; ?>>90 min</option>

            <option value="120-150 min" <?php echo in_array('120-150 min', $parq->paSession)?'selected':''; ?>>120-150 min</option>
            <option value="150 min +" <?php echo in_array('150 min +', $parq->paSession)?'selected':''; ?>>150 min +</option>

            
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
</fieldset>
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
        {!! Form::hidden('preferredTraingDays', $parq->preferredTraingDays )!!}
        <label class="strong">What days and times day do you prefer to train?</label>
        <div>
            <div class="col-md-3 col-xs-4 pl-0 pt-5">Monday</div>
            <div class="col-md-9 col-xs-8 pr-0">
                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input id="preferredTraingDaysMonAm" value="am" type="checkbox" class="preferredTraingDays" data-day="Mon">
                    <label for="preferredTraingDaysMonAm"> AM </label>
                </div>

                <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                    <input id="preferredTraingDaysMonPm" value="pm" type="checkbox" class="preferredTraingDays" data-day="Mon">
                    <label for="preferredTraingDaysMonPm"> PM </label>
                </div>
            </div>
        </div>
        <div class="m-t-10 col-xs-12 pl-0 pr-0">
            <div class="col-md-3 col-xs-4 pl-0 pt-5">
            Tuesday
        </div>
        <div class="col-md-9 col-xs-8 pr-0">
            <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                <input id="preferredTraingDaysTueAm" value="am" type="checkbox" class="preferredTraingDays" data-day="Tue">
                <label for="preferredTraingDaysTueAm"> AM </label>
            </div>

            <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
                <input id="preferredTraingDaysTuePm" value="pm" type="checkbox" class="preferredTraingDays" data-day="Tue">
                <label for="preferredTraingDaysTuePm"> PM </label>
            </div>
        </div>
    </div>

    <div class="m-t-10 col-xs-12 pl-0 pr-0">
     <div class="col-md-3 col-xs-4 pl-0 pt-5">
        Wednesday
    </div>
    <div class="col-md-9 col-xs-8 pr-0">
        <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
            <input id="preferredTraingDaysWedAm" value="am" type="checkbox" class="preferredTraingDays" data-day="Wed">
            <label for="preferredTraingDaysWedAm"> AM </label>
        </div>

        <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
            <input id="preferredTraingDaysWedPm" value="pm" type="checkbox" class="preferredTraingDays" data-day="Wed">
            <label for="preferredTraingDaysWedPm"> PM </label>
        </div>
    </div>
</div>

<div class="m-t-10 col-xs-12 pl-0 pr-0 pt-5">
 <div class="col-md-3 col-xs-4 pl-0 pt-5">

    Thursday
</div>
<div class="col-md-9 col-xs-8 pr-0">
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
        <input id="preferredTraingDaysThuAm" value="am" type="checkbox" class="preferredTraingDays" data-day="Thu">
        <label for="preferredTraingDaysThuAm"> AM </label>
    </div>

    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
        <input id="preferredTraingDaysThuPm" value="pm" type="checkbox" class="preferredTraingDays" data-day="Thu">
        <label for="preferredTraingDaysThuPm"> PM </label>
    </div>
</div>
</div>

<div class="m-t-10 col-xs-12 pl-0 pr-0 pt-5">
 <div class="col-md-3 col-xs-4 pl-0 pt-5">
    Friday
</div>
<div class="col-md-9 col-xs-8 pr-0">
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
        <input id="preferredTraingDaysFriAm" value="am" type="checkbox" class="preferredTraingDays" data-day="Fri">
        <label for="preferredTraingDaysFriAm"> AM </label>
    </div>

    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
        <input id="preferredTraingDaysFriPm" value="pm" type="checkbox" class="preferredTraingDays" data-day="Fri">
        <label for="preferredTraingDaysFriPm"> PM </label>
    </div>
</div>
</div>

<div class="m-t-10 col-xs-12 pl-0 pr-0 pt-5">
 <div class="col-md-3 col-xs-4 pl-0 pt-5">
    Saturday
</div>
<div class="col-md-9 col-xs-8 pr-0">
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
        <input id="preferredTraingDaysSatAm" value="am" type="checkbox" class="preferredTraingDays" data-day="Sat">
        <label for="preferredTraingDaysSatAm"> AM </label>
    </div>

    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
        <input id="preferredTraingDaysSatPm" value="pm" type="checkbox" class="preferredTraingDays" data-day="Sat">
        <label for="preferredTraingDaysSatPm"> PM </label>
    </div>
</div>
</div>

<div class="m-t-10 col-xs-12 pl-0 pr-0 pt-5">
 <div class="col-md-3 col-xs-4 pl-0 pt-5">
    Sunday
</div>
<div class="col-md-9 col-xs-8 pr-0">
    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
        <input id="preferredTraingDaysSunAm" value="am" type="checkbox" class="preferredTraingDays" data-day="Sun">
        <label for="preferredTraingDaysSunAm"> AM </label>
    </div>

    <div class="checkbox clip-check check-primary checkbox-inline m-b-0">
        <input id="preferredTraingDaysSunPm" value="pm" type="checkbox" class="preferredTraingDays" data-day="Sun">
        <label for="preferredTraingDaysSunPm"> PM </label>
    </div>
</div>
</div>
</div>

<div class="form-group col-xs-12 pl-0 pr-0">
 <label class="strong" for="epNotes">Please provide any additional notes you think are relevant</label>
 <div>
     <textarea class="form-control" rows="10" cols="50" id="epNotes" name="epNotes">{{$parq->epNotes}}</textarea>
 </div>
</div>
</fieldset>
</div>