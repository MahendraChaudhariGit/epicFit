<fieldset class="padding-15">
    <legend>General Details</legend>
    
    <form action="{{ url('store-chronotype-survey') }}" method="post" enctype="multipart/form-data">
        @csrf 
    <div class="row">
        <div class="col-md-6">
            

            <div class="form-group">
                <label class="strong" for="getup_score">What time would you get up if were entirely free to plan your day?</label>
                <select id="getup_score" name="getup_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="5" value="5:00 - 6:29 am,5" @if(isset($chronotype_survey) && $chronotype_survey->getup_score == '5:00 - 6:29 am,5') selected @endif>5:00 - 6:29 am</option>
                    <option data-value="4" value="6:30 - 7:44 am,4" @if(isset($chronotype_survey) && $chronotype_survey->getup_score == '6:30 - 7:44 am,4') selected @endif>6:30 - 7:44 am</option>
                    <option data-value="3" value="7:45 - 9:44 am,3" @if(isset($chronotype_survey) && $chronotype_survey->getup_score == '7:45 - 9:44 am,3') selected @endif>7:45 - 9:44 am</option>
                    <option data-value="2" value="9:45 - 10:59 am,2" @if(isset($chronotype_survey) && $chronotype_survey->getup_score == '9:45 - 10:59 am,2') selected @endif>9:45 - 10:59 am</option>
                    <option data-value="1" value="11:00 - 11:59 am,1" @if(isset($chronotype_survey) && $chronotype_survey->getup_score == '11:00 - 11:59 am,1') selected @endif>11:00 - 11:59 am</option>
                    <option data-value="0" value="Midday - 5:00 pm,0" @if(isset($chronotype_survey) && $chronotype_survey->getup_score == 'Midday - 5:00 pm,0') selected @endif>Midday - 5:00 pm</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="goto_bed_score">What time would you go to bed if you were entirely free to plan your evening?</label>
                <select id="goto_bed_score" name="goto_bed_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="5" value="8:00 - 8:59 pm,5" @if(isset($chronotype_survey) && $chronotype_survey->goto_bed_score == '8:00 - 8:59 pm,5') selected @endif>8:00 - 8:59 pm</option>
                    <option data-value="4" value="9:00 - 10:14 pm,4" @if(isset($chronotype_survey) && $chronotype_survey->goto_bed_score == '9:00 - 10:14 pm,4') selected @endif>9:00 - 10:14 pm</option>
                    <option data-value="3" value="10:15 pm - 12:29 am,3" @if(isset($chronotype_survey) && $chronotype_survey->goto_bed_score == '10:15 pm - 12:29 am,3') selected @endif>10:15 pm - 12:29 am</option>
                    <option data-value="2" value="12:30 - 1:44 am,2" @if(isset($chronotype_survey) && $chronotype_survey->goto_bed_score == '12:30 - 1:44 am,2') selected @endif>12:30 - 1:44 am</option>
                    <option data-value="1" value="1:45 - 2:59 am,1" @if(isset($chronotype_survey) && $chronotype_survey->goto_bed_score == '1:45 - 2:59 am,1') selected @endif>1:45 - 2:59 am</option>
                    <option data-value="0" value="3:00 - 8:00 am,0" @if(isset($chronotype_survey) && $chronotype_survey->goto_bed_score == '3:00 - 8:00 am,0') selected @endif>3:00 - 8:00 am</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="specific_time_getup_score">If there is a specific time at which you have to get up in the morning, to what extend to you depend on being woken up by an alarm clock?</label>
                <select id="specific_time_getup_score" name="specific_time_getup_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="4" value="Not at all dependent,4" @if(isset($chronotype_survey) && $chronotype_survey->specific_time_getup_score == 'Not at all dependent,4') selected @endif>Not at all dependent</option>
                    <option data-value="3" value="Slightly dependent,3" @if(isset($chronotype_survey) && $chronotype_survey->specific_time_getup_score == 'Slightly dependent,3') selected @endif>Slightly dependent</option>
                    <option data-value="2" value="Fairly dependent,2" @if(isset($chronotype_survey) && $chronotype_survey->specific_time_getup_score == 'Fairly dependent,2') selected @endif>Fairly dependent</option>
                    <option data-value="1" value="Very dependent,1" @if(isset($chronotype_survey) && $chronotype_survey->specific_time_getup_score == 'Very dependent,1') selected @endif>Very dependent</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="easy_to_getup_score">If there is a specific time at which you have to get up in the morning, to what extend to you depend on being woken up by an alarm clock?</label>
                <select id="easy_to_getup_score" name="easy_to_getup_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="1" value="Not at all easy,1" @if(isset($chronotype_survey) && $chronotype_survey->easy_to_getup_score == 'Not at all easy,1') selected @endif>Not at all easy</option>
                    <option data-value="2" value="Not very easy,2" @if(isset($chronotype_survey) && $chronotype_survey->easy_to_getup_score == 'Not very easy,2') selected @endif>Not very easy</option>
                    <option data-value="3" value="Fairly easy,3" @if(isset($chronotype_survey) && $chronotype_survey->easy_to_getup_score == 'Fairly easy,3') selected @endif>Fairly easy</option>
                    <option data-value="4" value="Very easy,4" @if(isset($chronotype_survey) && $chronotype_survey->easy_to_getup_score == 'Very easy,4') selected @endif>Very easy</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="first_half_hour_score">How alert do you feel during the first half hour after you wake up in morning?</label>
                <select id="first_half_hour_score" name="first_half_hour_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="1" value="Not at all alert,1" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_score == 'Not at all alert,1') selected @endif>Not at all alert</option>
                    <option data-value="2" value="Slightly alert,2" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_score == 'Slightly alert,2') selected @endif>Slightly alert</option>
                    <option data-value="3" value="Fairly alert,3" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_score == 'Fairly alert,3') selected @endif>Fairly alert</option>
                    <option data-value="4" value="Very alert,4" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_score == 'Very alert,4') selected @endif>Very alert</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="first_half_hour_hungry_score">How hungry do you feel during the first half hour after you wake up in morning?</label>
                <select id="first_half_hour_hungry_score" name="first_half_hour_hungry_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="1" value="Not at all hungry,1" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_hungry_score == 'Not at all hungry,1') selected @endif>Not at all hungry</option>
                    <option data-value="2" value="Slightly hungry,2" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_hungry_score == 'Slightly hungry,2') selected @endif>Slightly hungry</option>
                    <option data-value="3" value="Fairly hungry,3" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_hungry_score == 'Fairly hungry,3') selected @endif>Fairly hungry</option>
                    <option data-value="4" value="Very hungry,4" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_hungry_score == 'Very hungry,4') selected @endif>Very hungry</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="first_half_hour_tired_score">During the first-half hour after you wake up in morning, how tired do you feel?</label>
                <select id="first_half_hour_tired_score" name="first_half_hour_tired_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="1" value="Very tired,1" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_tired_score == 'Very tired,1') selected @endif>Very tired</option>
                    <option data-value="2" value="Fairly tired,2" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_tired_score == 'Fairly tired,2') selected @endif>Fairly tired</option>
                    <option data-value="3" value="Fairly refreshed,3" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_tired_score == 'Fairly refreshed,3') selected @endif>Fairly refreshed</option>
                    <option data-value="4" value="Very refreshed,4" @if(isset($chronotype_survey) && $chronotype_survey->first_half_hour_tired_score == 'Very refreshed,4') selected @endif>Very refreshed</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="no_commitment_goto_bed_score">If you have no commitment the next day, what time would you go to bed compared to your usual bedtime?</label>
                <select id="no_commitment_goto_bed_score" name="no_commitment_goto_bed_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="4" value="Seldom or never later,4" @if(isset($chronotype_survey) && $chronotype_survey->no_commitment_goto_bed_score == 'Seldom or never later,4') selected @endif>Seldom or never later</option>
                    <option data-value="3" value="Less then one hour later,3" @if(isset($chronotype_survey) && $chronotype_survey->no_commitment_goto_bed_score == 'Less then one hour later,3') selected @endif>Less then one hour later</option>
                    <option data-value="2" value="1-2 hours later,2" @if(isset($chronotype_survey) && $chronotype_survey->no_commitment_goto_bed_score == '1-2 hours later,2') selected @endif>1-2 hours later</option>
                    <option data-value="1" value="More then two hours later,1" @if(isset($chronotype_survey) && $chronotype_survey->no_commitment_goto_bed_score == 'More then two hours later,1') selected @endif>More then two hours later</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="engage_in_physical_exercise_score">You have decided to engage in some physical exercsise. A friend suggests that you do this for one hour twice a week
                    and the best time for him/her is between 7:00 - 8:00 am. Bearing in mind nothing but your own internal "clock",how do think you would perform? </label>
                <select id="engage_in_physical_exercise_score" name="engage_in_physical_exercise_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="4" value="Would be in good form,4" @if(isset($chronotype_survey) && $chronotype_survey->engage_in_physical_exercise_score == 'Would be in good form,4') selected @endif>Would be in good form</option>
                    <option data-value="3" value="Would be in reasonable form,3" @if(isset($chronotype_survey) && $chronotype_survey->engage_in_physical_exercise_score == 'Would be in reasonable form,3') selected @endif>Would be in reasonable form</option>
                    <option data-value="2" value="Would find it difficult,2" @if(isset($chronotype_survey) && $chronotype_survey->engage_in_physical_exercise_score == 'Would find it difficult,2') selected @endif>Would find it difficult</option>
                    <option data-value="1" value="Would find it very difficult,1" @if(isset($chronotype_survey) && $chronotype_survey->engage_in_physical_exercise_score == 'Would find it very difficult,1') selected @endif>Would find it very difficult</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="feel_tired_in_day_score">At what time of day do you feel you become tired as a result of need for sleep?</label>
                <select id="feel_tired_in_day_score" name="feel_tired_in_day_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="5" value="8:00 - 8:59 pm,5" @if(isset($chronotype_survey) && $chronotype_survey->feel_tired_in_day_score == '8:00 - 8:59 pm,5') selected @endif>8:00 - 8:59 pm</option>
                    <option data-value="4" value="9:00 - 10:14 pm,4" @if(isset($chronotype_survey) && $chronotype_survey->feel_tired_in_day_score == '9:00 - 10:14 pm,4') selected @endif>9:00 - 10:14 pm</option>
                    <option data-value="3" value="10:15 pm - 12:44 am,3" @if(isset($chronotype_survey) && $chronotype_survey->feel_tired_in_day_score == '10:15 pm - 12:44 am,3') selected @endif>10:15 pm - 12:44 am</option>
                    <option data-value="2" value="12:45 - 1:59 am,2" @if(isset($chronotype_survey) && $chronotype_survey->feel_tired_in_day_score == '12:45 - 1:59 am,2') selected @endif>12:45 - 1:59 am</option>
                    <option data-value="1" value="2:00 - 3:00 am,1" @if(isset($chronotype_survey) && $chronotype_survey->feel_tired_in_day_score == '2:00 - 3:00 am,1') selected @endif>2:00 - 3:00 am</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="peak_performance_score">You want to be at your peak performance for a test that you know is going to be mentally exhausting
                    and will last for two hours. You are entirely free to plan your day. Considering only your own  internal "clock",
                    wich ONE of the four testing times would you choose?</label>
                <select id="peak_performance_score" name="peak_performance_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="4" value="8:00 - 10:00 am,4" @if(isset($chronotype_survey) && $chronotype_survey->peak_performance_score == '8:00 - 10:00 am,4') selected @endif>8:00 - 10:00 am</option>
                    <option data-value="3" value="11:00 am - 1:00 pm,3" @if(isset($chronotype_survey) && $chronotype_survey->peak_performance_score == '11:00 am - 1:00 pm,3') selected @endif>11:00 am - 1:00 pm</option>
                    <option data-value="2" value="3:00 - 5:00 pm,2" @if(isset($chronotype_survey) && $chronotype_survey->peak_performance_score == '3:00 - 5:00 pm,2') selected @endif>3:00 - 5:00 pm</option>
                    <option data-value="1" value="7:00 - 9:00 pm,1" @if(isset($chronotype_survey) && $chronotype_survey->peak_performance_score == '7:00 - 9:00 pm,1') selected @endif>7:00 - 9:00 pm</option>
                </select>
            </div>
            
    	</div>
        <div class="col-md-6">

            

            <div class="form-group">
                <label class="strong" for="got_into_bed_tired_score">If you got into bed at 11:00 pm,how tired would you be?</label>
                <select id="got_into_bed_tired_score" name="got_into_bed_tired_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="1" value="Not at all tired,1" @if(isset($chronotype_survey) && $chronotype_survey->got_into_bed_tired_score == 'Not at all tired,1') selected @endif>Not at all tired</option>
                    <option data-value="2" value="A little tired,2" @if(isset($chronotype_survey) && $chronotype_survey->got_into_bed_tired_score == 'A little tired,2') selected @endif>A little tired</option>
                    <option data-value="3" value="Fairly tired,3" @if(isset($chronotype_survey) && $chronotype_survey->got_into_bed_tired_score == 'Fairly tired,3') selected @endif>Fairly tired</option>
                    <option data-value="4" value="Very tired,4" @if(isset($chronotype_survey) && $chronotype_survey->got_into_bed_tired_score == 'Very tired,4') selected @endif>Very tired</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="goto_several_hour_into_bed_score">For some reason, you have gone to bed several hours later than usual, but there is no need to get up
                    any perticular time the next morning. Which ONE of the following are you most likely to do? </label>
                <select id="goto_several_hour_into_bed_score" name="goto_several_hour_into_bed_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="4" value="Will wake up at usual time but will NOT fall back asleep,4" @if(isset($chronotype_survey) && $chronotype_survey->goto_several_hour_into_bed_score == 'Will wake up at usual time but will NOT fall back asleep,4') selected @endif>Will wake up at usual time but will NOT fall back asleep</option>
                    <option data-value="3" value="Will wake up at usual time and will doze thereafter,3" @if(isset($chronotype_survey) && $chronotype_survey->goto_several_hour_into_bed_score == 'Will wake up at usual time and will doze thereafter,3') selected @endif>Will wake up at usual time and will doze thereafter</option>
                    <option data-value="2" value="Will wake up at usual time but will fall asleep again,2" @if(isset($chronotype_survey) && $chronotype_survey->goto_several_hour_into_bed_score == 'Will wake up at usual time but will fall asleep again,2') selected @endif>Will wake up at usual time but will fall asleep again</option>
                    <option data-value="1" value="Will NOT wake up until later then usual,1" @if(isset($chronotype_survey) && $chronotype_survey->goto_several_hour_into_bed_score == 'Will NOT wake up until later then usual,1') selected @endif>Will NOT wake up until later then usual</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="one_night_awake_score">One night you have to remain awake between 4:00 - 6:00 am in order to carry out a night watch. you 
                    have no commitments the next day. Which one of the alternatives will suite you best?</label>
                <select id="one_night_awake_score" name="one_night_awake_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="1" value="Would NOT go to bed until watch was over,1" @if(isset($chronotype_survey) && $chronotype_survey->one_night_awake_score == 'Would NOT go to bed until watch was over,1') selected @endif>Would NOT go to bed until watch was over</option>
                    <option data-value="2" value="Would take a nap before and sleep after,2" @if(isset($chronotype_survey) && $chronotype_survey->one_night_awake_score == 'Would take a nap before and sleep after,2') selected @endif>Would take a nap before and sleep after</option>
                    <option data-value="3" value="Would take a good sleep before and nap after,3" @if(isset($chronotype_survey) && $chronotype_survey->one_night_awake_score == 'Would take a good sleep before and nap after,3') selected @endif>Would take a good sleep before and nap after</option>
                    <option data-value="4" value="Would sleep only before watch,4" @if(isset($chronotype_survey) && $chronotype_survey->one_night_awake_score == 'Would sleep only before watch,4') selected @endif>Would sleep only before watch</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="two_hour_hard_work_score">You have to do two hours of hard physical work. You aur entirely free to plan your day and considering
                    only your own internal "clock" Which one of the following times would you choose? </label>
                <select id="two_hour_hard_work_score" name="two_hour_hard_work_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="4" value="8:00 - 10:00 am,4" @if(isset($chronotype_survey) && $chronotype_survey->two_hour_hard_work_score == '8:00 - 10:00 am,4') selected @endif>8:00 - 10:00 am</option>
                    <option data-value="3" value="11:00 am - 1:00 pm,3" @if(isset($chronotype_survey) && $chronotype_survey->two_hour_hard_work_score == '11:00 am - 1:00 pm,3') selected @endif>11:00 am - 1:00 pm</option>
                    <option data-value="2" value="3:00 - 5:00 pm,2" @if(isset($chronotype_survey) && $chronotype_survey->two_hour_hard_work_score == '3:00 - 5:00 pm,2') selected @endif>3:00 - 5:00 pm</option>
                    <option data-value="1" value="7:00 - 9:00 pm,1" @if(isset($chronotype_survey) && $chronotype_survey->two_hour_hard_work_score == '7:00 - 9:00 pm,1') selected @endif>7:00 - 9:00 pm</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="physical_exercise_score">You have decided to engage in hard physical exercise. A friend suggests that you do this for one hour
                    twice a week and the best time for him/her is between 10:00 - 11:00 pm. Bearing in mind nothing else but
                    your own internal "clock", how well do you think you would perform? </label>
                <select id="physical_exercise_score" name="physical_exercise_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="4" value="Would be in good form,4" @if(isset($chronotype_survey) && $chronotype_survey->physical_exercise_score == 'Would be in good form,4') selected @endif>Would be in good form</option>
                    <option data-value="3" value="Would be in reasonable form,3" @if(isset($chronotype_survey) && $chronotype_survey->physical_exercise_score == 'Would be in reasonable form,3') selected @endif>Would be in reasonable form</option>
                    <option data-value="2" value="Would find it difficult,2" @if(isset($chronotype_survey) && $chronotype_survey->physical_exercise_score == 'Would find it difficult,2') selected @endif>Would find it difficult</option>
                    <option data-value="1" value="Would find it very difficult,1" @if(isset($chronotype_survey) && $chronotype_survey->physical_exercise_score == 'Would find it very difficult,1') selected @endif>Would find it very difficult</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="school_hours_score"> Suppose that you can choose your school hours. Assume that you went to school for five hours per day
                    and that school was interesting and enjoyable. Which five consecutive hours would you select?</label>
                <select id="school_hours_score" name="school_hours_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="5" value="5 hours starting between 4:00 - 7:59 am,5" @if(isset($chronotype_survey) && $chronotype_survey->school_hours_score == '5 hours starting between 4:00 - 7:59 am,5') selected @endif>5 hours starting between 4:00 - 7:59 am</option>
                    <option data-value="4" value="5 hours starting between 8:00 - 8:59 am,4" @if(isset($chronotype_survey) && $chronotype_survey->school_hours_score == '5 hours starting between 8:00 - 8:59 am,4') selected @endif>5 hours starting between 8:00 - 8:59 am</option>
                    <option data-value="3" value="5 hours starting between 9:00 am - 1:59 pm,3" @if(isset($chronotype_survey) && $chronotype_survey->school_hours_score == '5 hours starting between 9:00 am - 1:59 pm,3') selected @endif>5 hours starting between 9:00 am - 1:59 pm</option>
                    <option data-value="2" value="5 hours starting between 2:00 - 4:59 pm,2" @if(isset($chronotype_survey) && $chronotype_survey->school_hours_score == '5 hours starting between 2:00 - 4:59 pm,2') selected @endif>5 hours starting between 2:00 - 4:59 pm</option>
                    <option data-value="1" value="5 hours starting between 5:00 pm - 3:59 am,1" @if(isset($chronotype_survey) && $chronotype_survey->school_hours_score == '5 hours starting between 5:00 pm - 3:59 am,1') selected @endif>5 hours starting between 5:00 pm - 3:59 am</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="feeling_best_score">At what time of the day do you think that you reach your "feeling best" peak?</label>
                <select id="feeling_best_score" name="feeling_best_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="5" value="5:00 - 7:59 am,5" @if(isset($chronotype_survey) && $chronotype_survey->feeling_best_score == '5:00 - 7:59 am,5') selected @endif>5:00 - 7:59 am</option>
                    <option data-value="4" value="8:00 - 9:59 am,4" @if(isset($chronotype_survey) && $chronotype_survey->feeling_best_score == '8:00 - 9:59 am,4') selected @endif>8:00 - 9:59 am</option>
                    <option data-value="3" value="10:00 am - 4:59 pm,3" @if(isset($chronotype_survey) && $chronotype_survey->feeling_best_score == '10:00 am - 4:59 pm,3') selected @endif>10:00 am - 4:59 pm</option>
                    <option data-value="2" value="5:00 - 9:59 pm,2" @if(isset($chronotype_survey) && $chronotype_survey->feeling_best_score == '5:00 - 9:59 pm,2') selected @endif>5:00 - 9:59 pm</option>
                    <option data-value="1" value="10:00 pm - 4:59 am,1" @if(isset($chronotype_survey) && $chronotype_survey->feeling_best_score == '10:00 pm - 4:59 am,1') selected @endif>10:00 pm - 4:59 am</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="types_of_people_score">One hears about "morning" and "evening" types of people. Which ONE of these types do you consider yourself to be?</label>
                <select id="types_of_people_score" name="types_of_people_score" class="form-control">
                    <option data-value="0" value="">-- Select --</option>
                    <option data-value="6" value="Definitely a 'morning' type,6" @if(isset($chronotype_survey) && $chronotype_survey->types_of_people_score == "Definitely a 'morning' type,6") selected @endif>Definitely a 'morning' type</option>
                    <option data-value="4" value="Rather more a 'morning' type then an 'evening' type,4" @if(isset($chronotype_survey) && $chronotype_survey->types_of_people_score == "Rather more a 'morning' type then an 'evening' type,4") selected @endif>Rather more a 'morning' type then an 'evening' type</option>
                    <option data-value="2" value="Rather more an 'evening' type then  'morning' type,2" @if(isset($chronotype_survey) && $chronotype_survey->types_of_people_score == "Rather more an 'evening' type then  'morning' type,2") selected @endif>Rather more an 'evening' type then  'morning' type</option>
                    <option data-value="0" value="Definitely an 'evening' type,0" @if(isset($chronotype_survey) && $chronotype_survey->types_of_people_score == "Definitely an 'evening' type,0") selected @endif>Definitely an 'evening' type</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="getup_score">Total Score</label>
                <input type="text" id="total_score" readonly name="total_score" value="@if(isset($chronotype_survey) && !empty($chronotype_survey->total_score)) {{ $chronotype_survey->total_score }} @else  @endif" class="form-control">
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
