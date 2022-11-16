<fieldset class="padding-15">
    <legend>General Details</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="parq-view" data-realtime="referralNetwork">
                <div class="form-group">
                    <label class="strong">Sleep Genie Questionnaire/Servey</label>
                    <br/>
                    <span class="referrerCls">
                       <b> Name</b> - @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->first_name)) {{ $sleep_questionnaire->first_name }} {{ $sleep_questionnaire->last_name }} @else &nbsp; @endif <br>
                       <b>Contact Number</b> - @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->contact_number)) {{ $sleep_questionnaire->contact_number }} @else &nbsp; @endif <br>
                       <b>Best time to contact</b> - @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->contact_time)) {{ $sleep_questionnaire->contact_time }} @else &nbsp; @endif <br>
                       <b>Email</b> - @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->email)) {{ $sleep_questionnaire->email }} @else &nbsp; @endif <br>
                       <b>Gender</b> - @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->gender)) {{ $sleep_questionnaire->gender }} @else &nbsp; @endif <br>
                       <b>Age</b> - @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->age)) {{ $sleep_questionnaire->age }} @else &nbsp; @endif <br>
                       <b>Date</b> - @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->date)) {{ $sleep_questionnaire->date }} @else &nbsp; @endif 
                    </span>
    			</div>
                <div class="form-group">
                    <label class="strong">What ethnicity are you?</label> <br/>
                    <span class="referrerCls">
                        @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->ethnicity)) {{ $sleep_questionnaire->ethnicity }} @else &nbsp; @endif
                    </span>
                </div>
                <div class="form-group">
                    <label class="strong">Present marital status?</label> <br/>
                    <span class="referrerCls">
                        @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->marital_status)) {{ $sleep_questionnaire->marital_status }} @else &nbsp; @endif
                    </span>
                </div>
            
                <div class="form-group">
                    <label class="strong">Are you getting enough sleep?</label>
                    <br/>
                    <span class="referrerCls">
                        @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->enough_sleep)) {{ $sleep_questionnaire->enough_sleep }} @else &nbsp; @endif
                    </span>
                </div>
            
                <div class="form-group">
                    <label class="strong">Are you getting quality sleep?</label>
                    <br/>
                    <span class="referrerCls">
                        @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->quality_sleep)) {{ $sleep_questionnaire->quality_sleep }} @else &nbsp; @endif
                    </span>
                </div>
            
            <div class="form-group">
            	<label class="strong">Does your quality or quantity of sleep affect your functioning and duties?</label>
                <br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->sleep_affect)) {{ $sleep_questionnaire->sleep_affect }} @else &nbsp; @endif
                </span>
            </div>
        
        	<div class="form-group">
                <label class="strong">Do you need to wake up early and does this affect your sleep?</label>
                <br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->wakeup_early)) {{ $sleep_questionnaire->wakeup_early }} @else &nbsp; @endif
                </span>
            </div>
            
            <div class="form-group">
            	<label class="strong">Do you need to do chores and planning at night which affects the time you go to bed?</label>
                <br>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->planning_at_night)) {{ $sleep_questionnaire->planning_at_night }} @else &nbsp; @endif
                </span>
            </div>
        </div>
        </div>
        <div class="col-md-6">
        	<div class="form-group">
            	<label class="strong">Do you have an injury that is affecting your sleep?</label>
                <br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->have_an_injury)) {{ $sleep_questionnaire->have_an_injury }} @else &nbsp; @endif
                </span>
            </div>
                    
            <div class="form-group">
                <label class="strong">Do you have a partner who is affecting your sleep?</label><br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->have_a_partner)) {{ $sleep_questionnaire->have_a_partner }} @else &nbsp; @endif
                </span>
            </div>
            
            <div class="form-group">
                <label class="strong">Do you have a child or baby who is affecting your sleep?</label><br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->have_a_child)) {{ $sleep_questionnaire->have_a_child }} @else &nbsp; @endif
                </span>
             </div>
        
            <div class="form-group">
                <label class="strong">Do you have pets that affect your sleep?</label><br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->have_a_pets)) {{ $sleep_questionnaire->have_a_pets }} @else &nbsp; @endif
                </span>
            </div>
        
             <div class="form-group">
                <label class="strong">Does heartburn or indigestion affect your sleep?</label><br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->heartburn_or_indigestion)) {{ $sleep_questionnaire->heartburn_or_indigestion }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Does your work affect your sleep patterns and times?</label><br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->work_affect)) {{ $sleep_questionnaire->work_affect }} @else &nbsp; @endif
                </span>  
            </div>
            
            <div class="form-group">
                <label class="strong">Does your work stress affect your sleep?</label><br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->work_stress)) {{ $sleep_questionnaire->work_stress }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Do you wake up in the night and go to the rest room?</label><br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->wakeup_in_night)) {{ $sleep_questionnaire->wakeup_in_night }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Do you wake up in the night with an active mind?</label><br/>
                <span class="referrerCls">
                    @if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->wakeup_with_active)) {{ $sleep_questionnaire->wakeup_with_active }} @else &nbsp; @endif
                </span>
            </div>
            
        </div>
        
        <div class="col-md-12">
            <button type="button" class="btn btn-primary edit-mode btn-wide pull-right">
                Edit 
            </button>
        </div>
    </div>
</fieldset>