<fieldset class="padding-15">
    <legend>General Details</legend>
    
    <form action="{{ url('sleep-questionnaire') }}" method="post" enctype="multipart/form-data">
        @csrf 
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="strong">First Name</label>
                <input type="text" name="first_name" value="@if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->first_name)) {{ $sleep_questionnaire->first_name }} @else  @endif" class="form-control">
            </div>
            <div class="form-group">
                <label class="strong">Last Name</label>
                <input type="text" name="last_name" value="@if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->last_name)) {{ $sleep_questionnaire->last_name }} @else  @endif" class="form-control">
            </div>
            <div class="form-group">
                <label class="strong">Contact Number</label>
                <input type="text" name="contact_number" value="@if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->contact_number)) {{ $sleep_questionnaire->contact_number }} @else  @endif" class="form-control">
            </div>
            <div class="form-group">
                <label class="strong">Best time to contact</label>
                <input type="text" name="contact_time" value="@if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->contact_time)) {{ $sleep_questionnaire->contact_time }} @else  @endif" class="form-control">
            </div>
            <div class="form-group">
                <label class="strong">Email</label>
                <input type="text" name="email" value="@if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->email)) {{ $sleep_questionnaire->email }} @else  @endif" class="form-control">
            </div>
            <div class="form-group">
                <label class="strong">Gender</label>
                <input type="text" name="gender" value="@if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->gender)) {{ $sleep_questionnaire->gender }} @else  @endif" class="form-control">
            </div>
            <div class="form-group">
                <label class="strong">Age</label>
                <input type="text" name="age" value="@if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->age)) {{ $sleep_questionnaire->age }} @else  @endif" class="form-control">
            </div>
            <div class="form-group">
                <label class="strong">Date</label>
                <input type="text" name="date" id="datepicker" value="@if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->date)){{ $sleep_questionnaire->date }}@else  @endif" class="form-control">
            </div>

            <div class="form-group">
                <label class="strong">What ethnicity are you?</label>
                <input type="text" name="ethnicity" value="@if(isset($sleep_questionnaire) && !empty($sleep_questionnaire->ethnicity)) {{ $sleep_questionnaire->ethnicity }} @else  @endif" class="form-control">
            </div>
            
            <div class="form-group">
                <label class="strong" for="marital_status">Present marital status?</label>
                <select id="marital_status" name="marital_status" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Married" @if(isset($sleep_questionnaire) && $sleep_questionnaire->marital_status == 'Married') selected @endif>Married</option>
                    <option value="Single" @if(isset($sleep_questionnaire) && $sleep_questionnaire->marital_status == 'Single') selected @endif>Single</option>
                    <option value="Defacto" @if(isset($sleep_questionnaire) && $sleep_questionnaire->marital_status == 'Defacto') selected @endif>Defacto</option>
                    <option value="Civil Union" @if(isset($sleep_questionnaire) && $sleep_questionnaire->marital_status == 'Civil Union') selected @endif>Civil Union</option>
                    <option value="Divorced" @if(isset($sleep_questionnaire) && $sleep_questionnaire->marital_status == 'Divorced') selected @endif>Divorced</option>
                    <option value="Widow/Widower" @if(isset($sleep_questionnaire) && $sleep_questionnaire->marital_status == 'Widow/Widower') selected @endif>Widow/Widower</option>
                </select>
            </div>
        
            <div class="form-group">
                <label class="strong" for="enough_sleep">Are you getting enough sleep?</label>
                <select id="enough_sleep" name="enough_sleep" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->enough_sleep == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->enough_sleep == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="quality_sleep">Are you getting quality sleep?</label>
                <select id="quality_sleep" name="quality_sleep" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->quality_sleep == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->quality_sleep == 'No') selected @endif>No</option>
                </select>
            </div>
            
        </div>
        <div class="col-md-6">

            <div class="form-group">
                <label class="strong" for="sleep_affect">Does your quality or quantity of sleep affect your functioning and duties?</label>
                <select id="sleep_affect" name="sleep_affect" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->sleep_affect == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->sleep_affect == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="wakeup_early">Do you need to wake up early and does this affect your sleep?</label>
                <select id="wakeup_early" name="wakeup_early" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->wakeup_early == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->wakeup_early == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="planning_at_night">Do you need to do chores and planning at night which affects the time you go to bed?</label>
                <select id="planning_at_night" name="planning_at_night" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->planning_at_night == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->planning_at_night == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="have_an_injury">Do you have an injury that is affecting your sleep?</label>
                <select id="have_an_injury" name="have_an_injury" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->have_an_injury == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->have_an_injury == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="have_a_partner">Do you have a partner who is affecting your sleep?</label>
                <select id="have_a_partner" name="have_a_partner" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->have_a_partner == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->have_a_partner == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="have_a_child">Do you have a child or baby who is affecting your sleep?</label>
                <select id="have_a_child" name="have_a_child" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->have_a_child == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->have_a_child == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="have_a_pets">Do you have pets that affect your sleep?</label>
                <select id="have_a_pets" name="have_a_pets" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->have_a_pets == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->have_a_pets == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="heartburn_or_indigestion">Does heartburn or indigestion affect your sleep?</label>
                <select id="heartburn_or_indigestion" name="heartburn_or_indigestion" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->heartburn_or_indigestion == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->heartburn_or_indigestion == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="work_affect">Does your work affect your sleep patterns and times?</label>
                <select id="work_affect" name="work_affect" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->work_affect == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->work_affect == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="work_stress">Does your work stress affect your sleep?</label>
                <select id="work_stress" name="work_stress" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->work_stress == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->work_stress == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="wakeup_in_night">Do you wake up in the night and go to the rest room?</label>
                <select id="wakeup_in_night" name="wakeup_in_night" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->wakeup_in_night == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->wakeup_in_night == 'No') selected @endif>No</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="wakeup_with_active">Do you wake up in the night with an active mind?</label>
                <select id="wakeup_with_active" name="wakeup_with_active" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($sleep_questionnaire) && $sleep_questionnaire->wakeup_with_active == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($sleep_questionnaire) && $sleep_questionnaire->wakeup_with_active == 'No') selected @endif>No</option>
                </select>
            </div>
    	</div>
        
        <div class="col-md-12">
            
            <button type="submit" class="btn btn-primary btn-wide pull-right">
                Save 
            </button>
            <button type="button" class="btn btn-danger remove-edit-mode btn-wide pull-right"  style="margin-right: 10px;">
                Cancel 
            </button>
        </div>
    </div>
    </form>
</fieldset>
