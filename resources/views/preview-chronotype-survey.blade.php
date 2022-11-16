<fieldset class="padding-15">
    <legend>General Details</legend>
    <div class="row">
        <div class="col-md-6">
            
            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->getup_score)){
                    $getup_score = explode(',',($chronotype_survey->getup_score));
                }else{
                    $getup_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">What time would you get up if were entirely free to plan your day?</label><br/>
                <span class="referrerCls">
                  <b> Time</b> - {{ $getup_score[0] }}
                </span>
            </div>
            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->goto_bed_score)){
                    $goto_bed_score = explode(',',($chronotype_survey->goto_bed_score));
                }else{
                    $goto_bed_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">What time would you go to bed if you were entirely free to plan your evening?</label><br/>
                <span class="referrerCls">
                    <b> Time</b> - {{ $goto_bed_score[0] }}
                </span>
            </div>
            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->specific_time_getup_score)){
                    $specific_time_getup_score = explode(',',($chronotype_survey->specific_time_getup_score));
                }else{
                    $specific_time_getup_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">If there is a specific time at which you have to get up in the morning, to what extend to you depend on being woken up by an alarm clock?</label><br/>
                <span class="referrerCls">
                    {{ $specific_time_getup_score[0] }}
                </span>
            </div>
            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->easy_to_getup_score)){
                    $easy_to_getup_score = explode(',',($chronotype_survey->easy_to_getup_score));
                }else{
                    $easy_to_getup_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong"> How easy do you find it to get up in the morning (when you are not woken up unexpectedly)? </label><br/>
                <span class="referrerCls">
                    {{ $easy_to_getup_score[0] }}
                </span>
            </div>

            @php
            if(isset($chronotype_survey) && !empty($chronotype_survey->first_half_hour_score)){
                $first_half_hour_score = explode(',',($chronotype_survey->first_half_hour_score));
            }else{
                $first_half_hour_score = [];
            }
        @endphp
        <div class="form-group">
            <label class="strong"> How alert do you feel during the first half hour after you wake up in morning? </label><br/>
            <span class="referrerCls">
                {{ $first_half_hour_score[0] }}
            </span>
        </div>
        @php
            if(isset($chronotype_survey) && !empty($chronotype_survey->first_half_hour_hungry_score)){
                $first_half_hour_hungry_score = explode(',',($chronotype_survey->first_half_hour_hungry_score));
            }else{
                $first_half_hour_hungry_score = [];
            }
        @endphp
        <div class="form-group">
            <label class="strong">How hungry do you feel during the first half hour after you wake up in morning?</label><br/>
            <span class="referrerCls">
                {{ $first_half_hour_hungry_score[0] }}
            </span>
        </div>

        @php
            if(isset($chronotype_survey) && !empty($chronotype_survey->first_half_hour_tired_score)){
                $first_half_hour_tired_score = explode(',',($chronotype_survey->first_half_hour_tired_score));
            }else{
                $first_half_hour_tired_score = [];
            }
        @endphp
        <div class="form-group">
            <label class="strong">During the first-half hour after you wake up in morning, how tired do you feel?</label><br/>
            <span class="referrerCls">
                {{ $first_half_hour_tired_score[0] }}
            </span>
        </div>

        @php
            if(isset($chronotype_survey) && !empty($chronotype_survey->no_commitment_goto_bed_score)){
                $no_commitment_goto_bed_score = explode(',',($chronotype_survey->no_commitment_goto_bed_score));
            }else{
                $no_commitment_goto_bed_score = [];
            }
        @endphp
        <div class="form-group">
            <label class="strong">If you have no commitment the next day, what time would you go to bed compared to your usual bedtime?</label><br/>
            <span class="referrerCls">
                {{ $no_commitment_goto_bed_score[0] }}
            </span>
        </div>

        @php
            if(isset($chronotype_survey) && !empty($chronotype_survey->engage_in_physical_exercise_score)){
                $engage_in_physical_exercise_score = explode(',',($chronotype_survey->engage_in_physical_exercise_score));
            }else{
                $engage_in_physical_exercise_score = [];
            }
        @endphp
        <div class="form-group">
            <label class="strong">You have decided to engage in some physical exercsise. A friend suggests that you do this for one hour twice a week
                and the best time for him/her is between 7:00 - 8:00 am. Bearing in mind nothing but your own internal "clock",how do think you would perform?</label><br/>
            <span class="referrerCls">
                {{ $engage_in_physical_exercise_score[0] }}
            </span>
        </div>

        @php
            if(isset($chronotype_survey) && !empty($chronotype_survey->feel_tired_in_day_score)){
                $feel_tired_in_day_score = explode(',',($chronotype_survey->feel_tired_in_day_score));
            }else{
                $feel_tired_in_day_score = [];
            }
        @endphp
        <div class="form-group">
            <label class="strong"> At what time of day do you feel you become tired as a result of need for sleep? </label><br/>
            <span class="referrerCls">
                <b> Time</b> - {{ $feel_tired_in_day_score[0] }}
            </span>
        </div>

        @php
            if(isset($chronotype_survey) && !empty($chronotype_survey->peak_performance_score)){
                $peak_performance_score = explode(',',($chronotype_survey->peak_performance_score));
            }else{
                $peak_performance_score = [];
            }
        @endphp
        <div class="form-group">
            <label class="strong">You want to be at your peak performance for a test that you know is going to be mentally exhausting
                and will last for two hours. You are entirely free to plan your day. Considering only your own  internal "clock",
                wich ONE of the four testing times would you choose?</label><br/>
            <span class="referrerCls">
                <b> Time</b> - {{ $peak_performance_score[0] }}
            </span>
        </div>
        
        </div>
        <div class="col-md-6">
            
           

            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->got_into_bed_tired_score)){
                    $got_into_bed_tired_score = explode(',',($chronotype_survey->got_into_bed_tired_score));
                }else{
                    $got_into_bed_tired_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">If you got into bed at 11:00 pm,how tired would you be?</label><br/>
                <span class="referrerCls">
                    {{ $got_into_bed_tired_score[0] }}
                </span>
            </div>

            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->goto_several_hour_into_bed_score)){
                    $goto_several_hour_into_bed_score = explode(',',($chronotype_survey->goto_several_hour_into_bed_score));
                }else{
                    $goto_several_hour_into_bed_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">For some reason, you have gone to bed several hours later than usual, but there is no need to get up
                    any perticular time the next morning. Which ONE of the following are you most likely to do?</label><br/>
                <span class="referrerCls">
                    {{ $goto_several_hour_into_bed_score[0] }}
                </span>
            </div>

            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->one_night_awake_score)){
                    $one_night_awake_score = explode(',',($chronotype_survey->one_night_awake_score));
                }else{
                    $one_night_awake_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">One night you have to remain awake between 4:00 - 6:00 am in order to carry out a night watch. you 
                    have no commitments the next day. Which one of the alternatives will suite you best?</label><br/>
                <span class="referrerCls">
                    {{ $one_night_awake_score[0] }}
                </span>
            </div>

            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->two_hour_hard_work_score)){
                    $two_hour_hard_work_score = explode(',',($chronotype_survey->two_hour_hard_work_score));
                }else{
                    $two_hour_hard_work_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">You have to do two hours of hard physical work. You aur entirely free to plan your day and considering
                    only your own internal "clock" Which one of the following times would you choose? </label><br/>
                <span class="referrerCls">
                    <b> Time</b> - {{ $two_hour_hard_work_score[0] }}
                </span>
            </div>

            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->physical_exercise_score)){
                    $physical_exercise_score = explode(',',($chronotype_survey->physical_exercise_score));
                }else{
                    $physical_exercise_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">You have decided to engage in hard physical exercise. A friend suggests that you do this for one hour
                    twice a week and the best time for him/her is between 10:00 - 11:00 pm. Bearing in mind nothing else but
                    your own internal "clock", how well do you think you would perform?</label><br/>
                <span class="referrerCls">
                    {{ $physical_exercise_score[0] }}
                </span>
            </div>

            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->school_hours_score)){
                    $school_hours_score = explode(',',($chronotype_survey->school_hours_score));
                }else{
                    $school_hours_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">Suppose that you can choose your school hours. Assume that you went to school for five hours per day
                    and that school was interesting and enjoyable. Which five consecutive hours would you select?</label><br/>
                <span class="referrerCls">
                    {{ $school_hours_score[0] }}
                </span>
            </div>

            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->feeling_best_score)){
                    $feeling_best_score = explode(',',($chronotype_survey->feeling_best_score));
                }else{
                    $feeling_best_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">At what time of the day do you think that you reach your "feeling best" peak?</label><br/>
                <span class="referrerCls">
                    <b> Time</b> - {{ $feeling_best_score[0] }}
                </span>
            </div>

            @php
                if(isset($chronotype_survey) && !empty($chronotype_survey->types_of_people_score)){
                    $types_of_people_score = explode(',',($chronotype_survey->types_of_people_score));
                }else{
                    $types_of_people_score = [];
                }
            @endphp
            <div class="form-group">
                <label class="strong">One hears about "morning" and "evening" types of people. Which ONE of these types do you consider yourself to be?</label><br/>
                <span class="referrerCls">
                    {{ $types_of_people_score[0] }}
                </span>
            </div>

            <div class="form-group">
                <label class="strong">Total Score</label><br/>
                <span class="referrerCls">
                    @if(isset($chronotype_survey) && !empty($chronotype_survey->total_score)) {{ $chronotype_survey->total_score }} @endif
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