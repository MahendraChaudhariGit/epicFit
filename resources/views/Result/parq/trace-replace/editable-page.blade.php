<fieldset class="padding-15">
    <legend>General Details</legend>
    
    <form action="{{ url('epic/store-nutritional') }}" method="post" enctype="multipart/form-data">
        @csrf 
        <input type="hidden" name="client_id" value="{{ $clients->id }}">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="strong">Any food allergies or intorlerances</label>
                <input type="text" name="food_description" value="@if(isset($nutritional_journal) && !empty($nutritional_journal->food_description)) {{ $nutritional_journal->food_description }} @else  @endif" class="form-control">
            </div>

            @php
            $activity_lavel = [];
                if(isset($nutritional_journal) && !empty($nutritional_journal->activity_lavel)){ 
                    $activity_lavel = explode(',',($nutritional_journal->activity_lavel));
                }
            @endphp
            <div class="form-group ">
                <label class="strong" for="activity_lavel">Activity level, occupation and physical activities</label>
                <select id="activity_lavel" name="activity_lavel[]" class="form-control"  multiple>
                    <option value="Sedentary" <?php echo in_array('Sedentary', $activity_lavel)?'selected':''; ?>>Sedentary</option>
                    <option value="Light" <?php echo in_array('Light', $activity_lavel)?'selected':''; ?>>Light</option>
                    <option value="Moderate" <?php echo in_array('Moderate', $activity_lavel)?'selected':''; ?>>Moderate</option>
                    <option value="Vigorous" <?php echo in_array('Vigorous', $activity_lavel)?'selected':''; ?>>Vigorous</option>
                    <option value="High" <?php echo in_array('High', $activity_lavel)?'selected':''; ?>>High</option>
                </select>
            </div>

            @php
            $weight = [];
                if(isset($nutritional_journal) && !empty($nutritional_journal->weight)){ 
                    $weight = explode(',',($nutritional_journal->weight));
                }
            @endphp
            <div class="form-group ">
                <label class="strong" for="weight">How does your nutritional goal relate to your weight?</label>
                <select id="weight" name="weight[]" class="form-control"  multiple>
                    <option value="Lose weight" <?php echo in_array('Lose weight', $weight)?'selected':''; ?>>Lose weight</option>
                    <option value="Maintain Weight" <?php echo in_array('Maintain Weight', $weight)?'selected':''; ?>>Maintain Weight</option>
                    <option value="Gain Weight" <?php echo in_array('Gain Weight', $weight)?'selected':''; ?>>Gain Weight</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong">How much weight do you wish to loss or gain?</label>
                <input type="text" name="weight_loss_gain" value="@if(isset($nutritional_journal) && !empty($nutritional_journal->weight_loss_gain)) {{ $nutritional_journal->weight_loss_gain }} @else  @endif" class="form-control">
            </div>
            
            <div class="form-group">
                <label class="strong">Any other reasons to change your nutritional habits?</label>
                <input type="text" name="nutritional_habits" value="@if(isset($nutritional_journal) && !empty($nutritional_journal->nutritional_habits)) {{ $nutritional_journal->nutritional_habits }} @else  @endif" class="form-control">
            </div>
            <div class="form-group">
                <label class="strong" for="how_many_time_eat">How many time a day do you eat (including snacks)?</label>
                <select id="how_many_time_eat" name="how_many_time_eat" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="1" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 1) selected @endif>1</option>
                    <option value="2" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 2) selected @endif>2</option>
                    <option value="3" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 3) selected @endif>3</option>
                    <option value="4" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 4) selected @endif>4</option>
                    <option value="5" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 5) selected @endif>5</option>
                    <option value="6" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 6) selected @endif>6</option>
                    <option value="7" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 7) selected @endif>7+</option>
                </select>
            </div>
        
            <div class="form-group">
                <label class="strong" for="skip_meals">Do you skip meals?</label>
                <select id="skip_meals" name="skip_meals" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->skip_meals == 'No') selected @endif>No</option>
                    <option value="Occasionally" @if(isset($nutritional_journal) && $nutritional_journal->skip_meals == 'Occasionally') selected @endif>Occasionally</option>
                    <option value="Often" @if(isset($nutritional_journal) && $nutritional_journal->skip_meals == 'Often') selected @endif>Often</option>
                </select>
            </div>
        
            <div class="form-group">
                <label class="strong">What time do you eat your first meal & last meal?</label>
                <div>
                    <div class="form-group">
                        <label>First meal</label>
                        <input type="text" name="eat_first_meal" id="eat_first_meal" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->eat_first_meal }}" @endif class="form-control">
                    </div>
                    <div class="form-group">
                     <label>Last meal</label>
                     <input type="text" name="eat_last_meal" id="eat_last_meal" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->eat_last_meal }}" @endif class="form-control">
                 </div>
                </div>
            </div>
            
            <div class="form-group">
            	<label class="strong">How much water do you drink each day?</label>
                <select id="water_drink" name="water_drink" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="1L" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == '1L') selected @endif>1L</option>
                    <option value="1.5L" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == '1.5L') selected @endif>1.5L</option>
                    <option value="2L" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == '2L') selected @endif>2L</option>
                    <option value="2.5L" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == '2.5L') selected @endif>2.5L</option>
                    <option value="3L" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == '3L') selected @endif>3L</option>
                    <option value="3.5L" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == '3.5L') selected @endif>3.5L</option>
                    <option value="4L" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == '4L') selected @endif>4L</option>
                    <option value="4L+" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == '4L+') selected @endif>4L+</option>
                </select>
                
        	</div>
        
        	<div class="form-group">
                <label class="strong" for="drink_alcohol">Do you drink alcohol?</label>
                <select id="drink_alcohol" name="drink_alcohol" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($nutritional_journal) && $nutritional_journal->drink_alcohol == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->drink_alcohol == 'No') selected @endif>No</option>
                </select>
            </div>
            @php
                if(isset($nutritional_journal) && $nutritional_journal->drink_alcohol == 'No'){
                    $hide = 'hidden';
                }else{
                    $hide = '';
                }

            @endphp
           
            <div class="form-group alcohal {{ $hide }}">
                <label class="strong" for="consume_alcohol">How many units of alcohal per week?</label>
                <select id="consume_alcohol" name="consume_alcohol" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="1" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 1) selected @endif>1</option>
                    <option value="2" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 2) selected @endif>2</option>
                    <option value="3" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 3) selected @endif>3</option>
                    <option value="4" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 4) selected @endif>4</option>
                    <option value="5" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 5) selected @endif>5</option>
                    <option value="6" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 6) selected @endif>6</option>
                    <option value="7" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 7) selected @endif>7</option>
                    <option value="8" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 8) selected @endif>8</option>
                    <option value="9" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 9) selected @endif>9</option>
                    <option value="10" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == 10) selected @endif>10</option>
                </select>
            </div>
            
            <div class="form-group alcohal {{ $hide }}">
                <label class="strong">What type of alcohol do you drink?</label>
                <input type="text" name="type_of_alcohol" value="@if(isset($nutritional_journal) && !empty($nutritional_journal->type_of_alcohol)) {{ $nutritional_journal->type_of_alcohol }} @else  @endif" class="form-control">
            </div>
            

            <div class="form-group">
                <label class="strong" for="bing_drink">Do you bing drink?</label>
                <select id="bing_drink" name="bing_drink" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->bing_drink == 'No') selected @endif>No</option>
                    <option value="Occasionally" @if(isset($nutritional_journal) && $nutritional_journal->bing_drink == 'Occasionally') selected @endif>Occasionally</option>
                    <option value="Often" @if(isset($nutritional_journal) && $nutritional_journal->bing_drink == 'Often') selected @endif>Often</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="drink_tea_coffee">Do you drink tea or coffee?</label>
                <select id="drink_tea_coffee" name="drink_tea_coffee" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($nutritional_journal) && $nutritional_journal->drink_tea_coffee == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->drink_tea_coffee == 'No') selected @endif>No</option>
                </select>
                <input type="text" name="drink_tea_coffee_desc" value="@if(isset($nutritional_journal) && !empty($nutritional_journal->drink_tea_coffee_desc)) {{ $nutritional_journal->drink_tea_coffee_desc }} @else  @endif" class="form-control" style="margin-top: 10px;">
            </div>
            @php
                if(isset($nutritional_journal) && $nutritional_journal->drink_tea_coffee == 'No'){
                    $hide1 = 'hidden';
                }else{
                    $hide1 = '';
                }

            @endphp
            <div class="form-group tea_coffee {{ $hide1 }}">
            	<label class="strong" for="tea_coffee_time">How many cups of tea/coffee per day?</label>
                <select id="tea_coffee_time" name="tea_coffee_time" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="1" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == '1') selected @endif>1</option>
                    <option value="2" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == '2') selected @endif>2</option>
                    <option value="3" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == '3') selected @endif>3</option>
                    <option value="4" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == '4') selected @endif>4</option>
                    <option value="5" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == '5') selected @endif>5</option>
                    <option value="6" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == '6') selected @endif>6</option>
                    <option value="7" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == '7') selected @endif>7</option>
                    <option value="8+" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == '8+') selected @endif>8+</option>
                </select>
            </div>
                    
            <div class="form-group tea_coffee {{ $hide1 }}">
                <label class="strong" for="cup_size">What size cup?</label>
                <input type="text" name="cup_size" id="cup_size" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->cup_size }}" @endif class="form-control">
            </div>
        
            <div class="form-group">
                <label class="strong" for="primEm">Rate your energy lebels during the day?</label><br>
                <label>Morning</label>
                <select id="morning_energy_label" name="morning_energy_label" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Low" @if(isset($nutritional_journal) && $nutritional_journal->morning_energy_label == 'Low') selected @endif>Low</option>
                    <option value="Medium" @if(isset($nutritional_journal) && $nutritional_journal->morning_energy_label == 'Medium') selected @endif>Medium</option>
                    <option value="High" @if(isset($nutritional_journal) && $nutritional_journal->morning_energy_label == 'High') selected @endif>High</option>
                </select>
                <label>Afternoon</label>
                <select id="afternoon_energy_label" name="afternoon_energy_label" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Low" @if(isset($nutritional_journal) && $nutritional_journal->afternoon_energy_label == 'Low') selected @endif>Low</option>
                    <option value="Medium" @if(isset($nutritional_journal) && $nutritional_journal->afternoon_energy_label == 'Medium') selected @endif>Medium</option>
                    <option value="High" @if(isset($nutritional_journal) && $nutritional_journal->afternoon_energy_label == 'High') selected @endif>High</option>
                </select>
                <label>Evening</label>
                <select id="evening_energy_label" name="evening_energy_label" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Low" @if(isset($nutritional_journal) && $nutritional_journal->evening_energy_label == 'Low') selected @endif>Low</option>
                    <option value="Medium" @if(isset($nutritional_journal) && $nutritional_journal->evening_energy_label == 'Medium') selected @endif>Medium</option>
                    <option value="High" @if(isset($nutritional_journal) && $nutritional_journal->evening_energy_label == 'High') selected @endif>High</option>
                </select>
            </div>
        
            <div class="form-group">
                <label class="strong" for="eat_calories">Do you know how many calories you eat an average each day?</label>
                <select id="eat_calories" name="eat_calories" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($nutritional_journal) && $nutritional_journal->eat_calories == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->eat_calories == 'No') selected @endif>No</option>
                </select>
            </div>
            @php
            if(isset($nutritional_journal) && $nutritional_journal->eat_calories == 'No'){
                $hide2 = 'hidden';
            }else{
                $hide2 = '';
            }

        @endphp
            <div class="form-group calories {{ $hide2 }}">
                <label class="strong" for="how_many_calories">If yes how many?</label>
                <input type="text" name="how_many_calories" id="how_many_calories" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->how_many_calories }}" @endif class="form-control">
            </div>
            
            <div class="form-group">
                <label class="strong" for="special_diet">Are you an special diet?</label>
                <select id="special_diet" name="special_diet" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Yes" @if(isset($nutritional_journal) && $nutritional_journal->special_diet == 'Yes') selected @endif>Yes</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->special_diet == 'No') selected @endif>No</option>
                    <option value="Occasionally" @if(isset($nutritional_journal) && $nutritional_journal->special_diet == 'Occasionally') selected @endif>Occasionally</option>
                </select>
            </div>
        
            <div class="form-group">
                <label class="strong" for="which_diet">If "Yes" which diet?</label>
                <input type="text" name="which_diet" id="which_diet" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->which_diet }}" @endif class="form-control">
            </div>
            
    	</div>
        <div class="col-md-6">
        	
        
            <div class="form-group">
                <label class="strong" for="all_vitamins">List all medications, supplements or vitamins you are currently taking. (Include sport drinks and supplements)</label>
                <input type="text" name="all_vitamins" id="all_vitamins" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->all_vitamins }}" @endif class="form-control">
            </div>

            <div class="form-group">
                <label class="strong" for="use_it">Do you usually</label>
                <select id="use_it" name="use_it" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Binge" @if(isset($nutritional_journal) && $nutritional_journal->use_it == 'Binge') selected @endif>Binge</option>
                    <option value="Crave sugar" @if(isset($nutritional_journal) && $nutritional_journal->use_it == 'Crave sugar') selected @endif>Crave sugar</option>
                    <option value="Eat fast food" @if(isset($nutritional_journal) && $nutritional_journal->use_it == 'Eat fast food') selected @endif>Eat fast food</option>
                    <option value="Make and bring own food" @if(isset($nutritional_journal) && $nutritional_journal->use_it == 'Make and bring own food') selected @endif>Make and bring own food</option>
                    <option value="Eat at restaurents" @if(isset($nutritional_journal) && $nutritional_journal->use_it == 'Eat at restaurents') selected @endif>Eat at restaurents</option>
                </select>
                <input type="text" name="uses_desc" value="@if(isset($nutritional_journal) && !empty($nutritional_journal->uses_desc)) {{ $nutritional_journal->uses_desc }} @else  @endif" class="form-control" style="margin-top: 10px;">
            </div>

            <div class="form-group">
                <label class="strong" for="prepare_own_food">Do you prepare your own food often or by prepared food?</label>
                <input type="text" name="prepare_own_food" id="prepare_own_food" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->prepare_own_food }}" @endif class="form-control">
            </div>

            @php
            $prepare_own_meals = [];
                if(isset($nutritional_journal) && !empty($nutritional_journal->prepare_own_meals)){ 
                    $prepare_own_meals = explode(',',($nutritional_journal->prepare_own_meals));
                }
            @endphp
            <div class="form-group">
                <label class="strong" for="prepare_own_meals">How do you prepare most of your meals?</label>
                <select id="prepare_own_meals" name="prepare_own_meals[]" class="form-control"  multiple>
                    <option value="Grill" <?php echo in_array('Grill', $prepare_own_meals)?'selected':''; ?>>Grill</option>
                    <option value="Bake" <?php echo in_array('Bake', $prepare_own_meals)?'selected':''; ?>>Bake</option>
                    <option value="Steam" <?php echo in_array('Steam', $prepare_own_meals)?'selected':''; ?>>Steam</option>
                    <option value="Fry Pan" <?php echo in_array('Fry Pan', $prepare_own_meals)?'selected':''; ?>>Fry Pan</option>
                    <option value="Deep Fry" <?php echo in_array('Deep Fry', $prepare_own_meals)?'selected':''; ?>>Deep Fry</option>
                    <option value="Raw" <?php echo in_array('Raw', $prepare_own_meals)?'selected':''; ?>>Raw</option>
                    <option value="Stir Fry" <?php echo in_array('Stir Fry', $prepare_own_meals)?'selected':''; ?>>Stir Fry</option>
                    <option value="Smoked" <?php echo in_array('Smoked', $prepare_own_meals)?'selected':''; ?>>Smoked</option>
                    <option value="Curried" <?php echo in_array('Curried', $prepare_own_meals)?'selected':''; ?>>Curried</option>
                    <option value="Boiled" <?php echo in_array('Boiled', $prepare_own_meals)?'selected':''; ?>>Boiled</option>
                    <option value="Poaching" <?php echo in_array('Poaching', $prepare_own_meals)?'selected':''; ?>>Poaching</option>
                    <option value="Barbeque" <?php echo in_array('Barbeque', $prepare_own_meals)?'selected':''; ?>>Barbeque</option>
                    <option value="Microwave" <?php echo in_array('Microwave', $prepare_own_meals)?'selected':''; ?>>Microwave</option>
                </select>
                <input type="text" name="prepare_own_meals_desc" value="@if(isset($nutritional_journal) && !empty($nutritional_journal->prepare_own_meals_desc)) {{ $nutritional_journal->prepare_own_meals_desc }} @else  @endif" class="form-control" style="margin-top: 10px;">
            </div>

            <div class="form-group">
                <label class="strong" for="eat_outside">How many times a week do you eat out?</label>
                <select id="eat_outside" name="eat_outside" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="1" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == '1') selected @endif>1</option>
                    <option value="2" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == '2') selected @endif>2</option>
                    <option value="3" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == '3') selected @endif>3</option>
                    <option value="4" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == '4') selected @endif>4</option>
                    <option value="5" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == '5') selected @endif>5</option>
                    <option value="6" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == '6') selected @endif>6</option>
                    <option value="7" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == '7') selected @endif>7</option>
                    <option value="8+" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == '8+') selected @endif>8</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="improving_area">List 3 areas of your nutrition you would like to improve?</label>
                <input type="text" name="improving_area" id="improving_area" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->improving_area }}" @endif class="form-control">
            </div>

            <div class="form-group">
                <label class="strong" for="must_improving_area">Out of three listed above which one are most likely to adhere to</label>
                <input type="text" name="must_improving_area" id="must_improving_area" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->must_improving_area }}" @endif class="form-control">
            </div>

            <div class="form-group">
                <label class="strong" for="eating_speed">How would you describe the pace at which you eat?</label>
                <select id="eating_speed" name="eating_speed" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Slow" @if(isset($nutritional_journal) && $nutritional_journal->eating_speed == 'Slow') selected @endif>Slow</option>
                    <option value="Average" @if(isset($nutritional_journal) && $nutritional_journal->eating_speed == 'Average') selected @endif>Average</option>
                    <option value="Fast" @if(isset($nutritional_journal) && $nutritional_journal->eating_speed == 'Fast') selected @endif>Fast</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="full_plate">How full do you like your plate to be?</label>
                <select id="full_plate" name="full_plate" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="Empty" @if(isset($nutritional_journal) && $nutritional_journal->full_plate == 'Empty') selected @endif>Empty</option>
                    <option value="Medium" @if(isset($nutritional_journal) && $nutritional_journal->full_plate == 'Medium') selected @endif>Medium</option>
                    <option value="Full" @if(isset($nutritional_journal) && $nutritional_journal->full_plate == 'Full') selected @endif>Full</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="finish_plate">If there are 'left overs' after a meal do you try to finish to them?</label>
                <select id="finish_plate" name="finish_plate" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->finish_plate == 'No') selected @endif>No</option>
                    <option value="Occasionally" @if(isset($nutritional_journal) && $nutritional_journal->finish_plate == 'Occasionally') selected @endif>Occasionally</option>
                    <option value="Often" @if(isset($nutritional_journal) && $nutritional_journal->finish_plate == 'Often') selected @endif>Often</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="always_hungry">Are you always hungry when you eat?</label>
                <select id="always_hungry" name="always_hungry" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->always_hungry == 'No') selected @endif>No</option>
                    <option value="Occasionally" @if(isset($nutritional_journal) && $nutritional_journal->always_hungry == 'Occasionally') selected @endif>Occasionally</option>
                    <option value="Often" @if(isset($nutritional_journal) && $nutritional_journal->always_hungry == 'Often') selected @endif>Often</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="plate_empty">Do you leave your plate empty at every meal?</label>
                <select id="plate_empty" name="plate_empty" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->plate_empty == 'No') selected @endif>No</option>
                    <option value="Occasionally" @if(isset($nutritional_journal) && $nutritional_journal->plate_empty == 'Occasionally') selected @endif>Occasionally</option>
                    <option value="Often" @if(isset($nutritional_journal) && $nutritional_journal->plate_empty == 'Often') selected @endif>Often</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="eat_upset">Are you likely to eat if bored, vervous, stressed or upset?</label>
                <select id="eat_upset" name="eat_upset" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->eat_upset == 'No') selected @endif>No</option>
                    <option value="Occasionally" @if(isset($nutritional_journal) && $nutritional_journal->eat_upset == 'Occasionally') selected @endif>Occasionally</option>
                    <option value="Often" @if(isset($nutritional_journal) && $nutritional_journal->eat_upset == 'Often') selected @endif>Often</option>
                </select>
            </div>

            <div class="form-group">
                <label class="strong" for="eat_fast_food">Do you like to eat from fast food chains?</label>
                <select id="eat_fast_food" name="eat_fast_food" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="No" @if(isset($nutritional_journal) && $nutritional_journal->eat_fast_food == 'No') selected @endif>No</option>
                    <option value="Occasionally" @if(isset($nutritional_journal) && $nutritional_journal->eat_fast_food == 'Occasionally') selected @endif>Occasionally</option>
                    <option value="Often" @if(isset($nutritional_journal) && $nutritional_journal->eat_fast_food == 'Often') selected @endif>Often</option>
                </select>

                <div class="form-group">
                    <label> Why / Why not?
                  </label>
                  <input type="text" name="why_not_eat" id="why_not_eat" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->why_not_eat }}" @endif class="form-control">
              </div>
               <div class="form-group">
                    <label>Preferred fast food?
                  </label>
                  <input type="text" name="why_eat" id="why_eat" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->why_eat }}" @endif class="form-control">
              </div>
            </div>

            <div class="form-group">
                <label class="strong" for="favourite_food">What are some of your favourite perferred foods? (Describe a healthy breakfast, lunch, dinner and snack)?</label>
                <input type="text" name="favourite_food" id="favourite_food" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->favourite_food }}" @endif class="form-control">
            </div>

            <div class="form-group">
                <label class="strong" for="after_full">What foods do you often crave, even if you feel full</label>
                <input type="text" name="after_full" id="after_full" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->after_full }}" @endif class="form-control">
            </div>

            <div class="form-group">
                <label class="strong" for="after_dinner">What do you like to do after dinner, if anything?</label>
                <input type="text" name="after_dinner" id="after_dinner" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->after_dinner }}" @endif class="form-control">
            </div>

            <div class="form-group">
                <label class="strong" for="good_meal">Describe what a 'good' meal means to you? (Portion size, food type, tasty, sweet, gourment, different)</label>
                <input type="text" name="good_meal" id="good_meal" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->good_meal }}" @endif class="form-control">
            </div>

            <div class="form-group">
                <label class="strong" for="favourite_drinks">What are your favourite drinks?</label>
                <input type="text" name="favourite_drinks" id="favourite_drinks" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->favourite_drinks }}" @endif class="form-control">
            </div>

            <div class="form-group">
                <label class="strong" for="cook_for">If you cook, do you cookfor others or just for yourself?</label>
                <input type="text" name="cook_for" id="cook_for" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->cook_for }}" @endif class="form-control">
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
<script>
    
    $("#drink_alcohol").change(function(){
        var status = $(this).val();
        if(status == 'No'){
            $(".alcohal").addClass('hidden');
        }
        if(status == 'Yes'){
            $(".alcohal").removeClass('hidden');
        }
    })
    
    $("#drink_tea_coffee").change(function(){
        var status = $(this).val();
        if(status == 'No'){
            $(".tea_coffee").addClass('hidden');
        }
        if(status == 'Yes'){
            $(".tea_coffee").removeClass('hidden');
        }
    })

    $("#eat_calories").change(function(){
        var status = $(this).val();
        if(status == 'No'){
            $(".calories").addClass('hidden');
        }
        if(status == 'Yes'){
            $(".calories").removeClass('hidden');
        }
    })
</script>