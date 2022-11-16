<fieldset class="padding-15">
    <legend>General Details</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="parq-view" data-realtime="referralNetwork">
                <div class="form-group">
                    <label class="strong">Any food allergies or intorlerances</label>
                    <br/>
                    <span class="referrerCls">
                            @if(isset($nutritional_journal) && !empty($nutritional_journal->food_description)) {{ $nutritional_journal->food_description }} @else &nbsp; @endif
                    </span>
    			</div>
                <div class="form-group">
                    <label class="strong">Activity level, occupation and physical activities</label> <br/>
                    <span class="referrerCls">
                        @if(isset($nutritional_journal) && !empty($nutritional_journal->activity_lavel)) {{ $nutritional_journal->activity_lavel }} @else &nbsp; @endif
                    </span>
                </div>
                <div class="form-group">
                    <label class="strong">How does your nutritional goal relate to your weight?</label> <br/>
                    <span class="referrerCls">
                        @if(isset($nutritional_journal) && !empty($nutritional_journal->weight)) {{ $nutritional_journal->weight }} @else &nbsp; @endif
                    </span>
                </div>
            
                <div class="form-group">
                    <label class="strong">How much weight do you wish to loss or gain?</label>
                    <br/>
                    <span class="referrerCls">
                        @if(isset($nutritional_journal) && !empty($nutritional_journal->weight_loss_gain)) {{ $nutritional_journal->weight_loss_gain }} @else &nbsp; @endif
                    </span>
                </div>
            
                <div class="form-group">
                    <label class="strong">Any other reasons to change your nutritional habits?</label>
                    <br/>
                    <span class="referrerCls">
                        @if(isset($nutritional_journal) && !empty($nutritional_journal->nutritional_habits)) {{ $nutritional_journal->nutritional_habits }} @else &nbsp; @endif
                    </span>
                </div>
            
            <div class="form-group">
            	<label class="strong">How many time a day do you eat (including snacks)?</label>
                <br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->how_many_time_eat)) {{ $nutritional_journal->how_many_time_eat }} @else &nbsp; @endif
                </span>
            </div>
        
        	<div class="form-group">
                <label class="strong">Do you skip meals?</label>
                <br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->skip_meals)) {{ $nutritional_journal->skip_meals }} @else &nbsp; @endif
                </span>
            </div>
            
            <div class="form-group">
            	<label class="strong">What time do you eat your first meal & last meal?</label>
                <br>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->eat_first_meal)) First Meal - {{ $nutritional_journal->eat_first_meal }} <br/> Last Meal - {{ $nutritional_journal->eat_last_meal }} @else &nbsp; @endif
                </span>
            </div>
        </div>
    
        	<div class="form-group">
            	<label class="strong">How much water do you drink each day?</label>
                <br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->water_drink)) {{ $nutritional_journal->water_drink }} @else &nbsp; @endif
                </span>
            </div>
                    
            <div class="form-group">
                <label class="strong">Do you drink alcohol?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->drink_alcohol)) {{ $nutritional_journal->drink_alcohol }} @else &nbsp; @endif
                </span>
            </div>
        
            @php
                if(isset($nutritional_journal) && $nutritional_journal->drink_alcohol == 'No'){
                    $hide = 'hidden';
                }else{
                    $hide = '';
                }

            @endphp

            <div class="form-group {{ $hide }}">
                <label class="strong">How many units of alcohal per week?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->consume_alcohol)) {{ $nutritional_journal->consume_alcohol }} @else &nbsp; @endif
                </span>
             </div>
        
            <div class="form-group {{ $hide }}">
                <label class="strong">What type of alcohol do you drink?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->type_of_alcohol)) {{ $nutritional_journal->type_of_alcohol }} @else &nbsp; @endif
                </span>
            </div>
        
             <div class="form-group">
                <label class="strong">Do you bing drink?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->bing_drink)) {{ $nutritional_journal->bing_drink }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Do you drink tea or coffee?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->drink_tea_coffee)) {{ $nutritional_journal->drink_tea_coffee }} @else &nbsp; @endif
                </span>  
            </div>
            @php
                if(isset($nutritional_journal) && $nutritional_journal->drink_tea_coffee == 'No'){
                    $hide1 = 'hidden';
                }else{
                    $hide1 = '';
                }

            @endphp
            <div class="form-group {{ $hide1 }}">
                <label class="strong">How many cups of tea/coffee per day?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->tea_coffee_time)) {{ $nutritional_journal->tea_coffee_time }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group {{ $hide1 }}">
                <label class="strong">What size cup?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->cup_size)) {{ $nutritional_journal->cup_size }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Rate your energy lebels during the day?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->morning_energy_label)) Morning - {{ $nutritional_journal->morning_energy_label }} @else &nbsp; @endif
                    <br/>
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->afternoon_energy_label)) Afternoon - {{ $nutritional_journal->afternoon_energy_label }} @else &nbsp; @endif
                    <br/>
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->evening_energy_label)) Evening - {{ $nutritional_journal->evening_energy_label }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Do you know how many calories you eat an average each day?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->eat_calories)) {{ $nutritional_journal->eat_calories }} @else &nbsp; @endif
                </span>
            </div>
            @php
                if(isset($nutritional_journal) && $nutritional_journal->eat_calories == 'No'){
                    $hide2 = 'hidden';
                }else{
                    $hide2 = '';
                }

            @endphp
            <div class="form-group {{ $hide2 }}">
                <label class="strong">If yes how many?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->how_many_calories)) {{ $nutritional_journal->how_many_calories }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Are you an special diet?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->special_diet)) {{ $nutritional_journal->special_diet }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">If "Yes" which diet?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->which_diet)) {{ $nutritional_journal->which_diet }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">List all medications, supplements or vitamins you are currently taking. (Include sport drinks and supplements)</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->all_vitamins)) {{ $nutritional_journal->all_vitamins }} @else &nbsp; @endif
                </span>
            </div>
        </div>
        <div class="col-md-6">
            
            <div class="form-group">
                <label class="strong">Do you usually</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->use_it)) {{ $nutritional_journal->use_it }} @else &nbsp; @endif
                    <br/>
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->uses_desc)) Description - {{ $nutritional_journal->uses_desc }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Do you prepare your own food often or by prepared food?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->prepare_own_food)) {{ $nutritional_journal->prepare_own_food }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">How do you prepare most of your meals?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->prepare_own_meals)) {{ $nutritional_journal->prepare_own_meals }} @else &nbsp; @endif
                    <br>
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->prepare_own_meals_desc)) Description - {{ $nutritional_journal->prepare_own_meals_desc }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">How many times a week do you eat out?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->eat_outside)) {{ $nutritional_journal->eat_outside }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">List 3 areas of your nutrition you would like to improve?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->improving_area)) {{ $nutritional_journal->improving_area }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Out of three listed above which one are most likely to adhere to</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->must_improving_area)) {{ $nutritional_journal->must_improving_area }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">How would you describe the pace at which you eat??</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->eating_speed)) {{ $nutritional_journal->eating_speed }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">How full do you like your plate to be?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->full_plate)) {{ $nutritional_journal->full_plate }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">If there are 'left overs' after a meal do you try to finish to them?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->finish_plate)) {{ $nutritional_journal->finish_plate }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Are you always hungry when you eat?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->always_hungry)) {{ $nutritional_journal->always_hungry }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Do you leave your plate empty at every meal?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->plate_empty)) {{ $nutritional_journal->plate_empty }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Are you likely to eat if bored, vervous, stressed or upset?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->eat_upset)) {{ $nutritional_journal->eat_upset }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Do you like to eat from fast food chains?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->eat_fast_food)) {{ $nutritional_journal->eat_fast_food }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Why / Why not?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->why_not_eat)) {{ $nutritional_journal->why_not_eat }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Preferred fast food?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->why_eat)) {{ $nutritional_journal->why_eat }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">What are some of your favourite perferred foods? (Describe a healthy breakfast, lunch, dinner and snack)?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->favourite_food)) {{ $nutritional_journal->favourite_food }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">What foods do you often crave, even if you feel full</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->after_full)) {{ $nutritional_journal->after_full }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">What do you like to do after dinner, if anything?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->after_dinner)) {{ $nutritional_journal->after_dinner }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">Describe what a 'good' meal means to you? (Portion size, food type, tasty, sweet, gourment, different)</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->good_meal)) {{ $nutritional_journal->good_meal }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">What are your favourite drinks?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->favourite_drinks)) {{ $nutritional_journal->favourite_drinks }} @else &nbsp; @endif
                </span>
            </div>
            <div class="form-group">
                <label class="strong">If you cook, do you cookfor others or just for yourself?</label><br/>
                <span class="referrerCls">
                    @if(isset($nutritional_journal) && !empty($nutritional_journal->cook_for)) {{ $nutritional_journal->cook_for }} @else &nbsp; @endif
                </span>
            </div>
        </div>
        <div class="col-md-12">
            {{-- <a href="{{ url('epic/edit-nutritional') }}" class="btn btn-primary edit-mode btn-wide pull-right">
                Edit 
            </a> --}}
            <button type="button" class="btn btn-primary edit-mode btn-wide pull-right">
                Edit 
            </button>
        </div>
    </div>
</fieldset>