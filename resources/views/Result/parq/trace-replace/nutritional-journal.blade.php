@extends('Result.profile_details')

@section('page-title')
    <span> Nutritional Journal </span> 
@stop
@section('required-styles')
{!! Html::style('result/plugins/tooltipster-master/tooltipster.css') !!}

<!-- start: Summernote -->
{!! Html::style('result/plugins/summernote/dist/summernote.css') !!}
<!-- end: Summernote -->
{!! Html::style('result/plugins/bootstrap-select-master/css/bootstrap-select.min.css') !!}
{!! Html::style('result/plugins/intl-tel-input-master/build/css/intlTelInput.css') !!}
{!! Html::style('result/plugins/bootstrap-datepicker/css/datepicker.css') !!}
{!! Html::style('result/plugins/nestable-cliptwo/jquery.nestable.css') !!}


{!! Html::style('result/plugins/sweetalert/sweet-alert.css') !!}

{!! Html::style('result/plugins/Jcrop/css/jquery.Jcrop.min.css') !!}


{!! Html::style('result/css/custom.css?v='.time()) !!}

<style>
    .swMain.wizard-headding-style > ul{position:static;margin-bottom:25px}
    .wizard-headding-style .control-label{text-align:left}
</style>
<style type="text/css">
    .pac-container{
        z-index: 9999;
    }
    input.form-control.custom-width{
        margin-left: 0px;
        width:100%;
    }
</style>

<!-- VpForm -->
{!! Html::style('result/vendor/vp-form/css/vp-form.css') !!}
@stop

@section('angular-scripts-required')
    <!-- start: VpForm -->
    {!! Html::script('result/vendor/vp-form/js/jquery.windows.js') !!}
    {!! Html::script('result/vendor/vp-form/js/angular.js') !!}
    {!! Html::script('result/vendor/vp-form/js/autogrow.js') !!}
    {!! Html::script('result/vendor/vp-form/js/vp-form-parq.js') !!}
    <!-- end: VpForm -->
@stop

@section('content')
<div class="container-fluid">
        <div class="row row-height">
            <div class="col-xl-4 col-lg-4 content-left">
                <div class="content-left-wrapper">
                   <img src="{{asset('result/images/step-four.jpg')}}" alt="" class="img-fluid">
                    
                </div>
                <!-- /content-left-wrapper -->
            </div>
            <div class="col-xl-8 col-lg-8 content-right" id="start">
                <div id="wizard_container">
                    <div id="top-wizard">
                        <span id="location"></span>
                        <div id="progressbar"></div>
                    </div>
                      <!-- /top-wizard -->
                     {{-- <form id="wrapped" method="post" enctype="multipart/form-data"> --}}
                        <form action="{{ url('epic/store-nutritional') }}" method="post" enctype="multipart/form-data">
                            @csrf 
                        <input id="website" name="website" type="text" value="">
                         <div id="middle-wizard">
                            <div class="step">
                                <h2>Nutrition & Habits Questionnaire</h2>
                                <h2 class="section_title">Any food allergies or intorlerances</h2>
                                <div class="form-group">
                                       <textarea rows="7" id="food_description" name="food_description" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->food_description }} @else {{ old('food_description') }} @endif</textarea>
                                </div>
                            </div>
                            <!-- /step-->
                                <div class="step">
                                <h2 class="section_title">Activity level, occupation and physical activities</h2>
                                <div class="form-group add_top_30">
                                    <label class="container_check version_2">Sedentary
                                          <input type="checkbox" name="activity_lavel[]" @if(isset($nutritional_journal) && in_array("Sedentary",$activity_lavel)) checked @endif value="Sedentary" id="sedentary">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2">Light
                                      <input type="checkbox" name="activity_lavel[]" @if(isset($nutritional_journal) && in_array("Light",$activity_lavel)) checked @endif value="Light" id="Light">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2">Moderate
                                        <input type="checkbox" name="activity_lavel[]" @if(isset($nutritional_journal) && in_array("Moderate",$activity_lavel)) checked @endif value="Moderate" id="Moderate">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2">Vigorous
                                        <input type="checkbox" name="activity_lavel[]" @if(isset($nutritional_journal) && in_array("Vigorous",$activity_lavel)) checked @endif value="Vigorous" id="Vigorous">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2">High
                                     <input type="checkbox" name="activity_lavel[]" @if(isset($nutritional_journal) && in_array("High",$activity_lavel)) checked @endif value="High" id="High">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->
                             <div class="step">
                                <h2 class="section_title">How does your nutritional goal relate to your weight?</h2>
                               <div class="form-group add_top_30">
                                    <label class="container_check version_2"> Lose weight
                                         <input type="checkbox" name="weight[]" @if(isset($nutritional_journal) && in_array("Lose Weight",$weight)) checked @endif value="Lose Weight" id="LoseWeight">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2">Maintain weight
                                      <input type="checkbox" name="weight[]" @if(isset($nutritional_journal) && in_array("Maintain Weight",$weight)) checked @endif value="Maintain Weight" id="MaintainWeight">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2">Gain weight
                                       <input type="checkbox" name="weight[]" @if(isset($nutritional_journal) && in_array("Gain Weight",$weight)) checked @endif value="Gain Weight" id="GainWeight">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">How much weight do you wish to loss or gain?</h2>
                               <div class="form-group add_top_30">
                                   <textarea rows="7" id="weight_loss_gain" name="weight_loss_gain" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->weight_loss_gain }} @else {{ old('weight_loss_gain') }} @endif</textarea>
                                </div>
                            </div>
                             <!-- /step-->
                             <div class="step">
                                <h2 class="section_title">Any other reasons to change your nutritional habits?</h2>
                               <div class="form-group add_top_30">
                                     <textarea rows="7" id="nutritional_habit" name="nutritional_habits" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->nutritional_habits }} @else {{ old('nutritional_habits') }} @endif</textarea>
                                </div>
                            </div>
                         <!-- /step-->
                             <div class="step">
                                <h2 class="section_title">How many time a day do you eat (including snacks)?</h2>
                               <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> 1
                                       <input type="radio" name="how_many_time_eat" id="value1" value="1" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 1) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">2
                                    <input type="radio" name="how_many_time_eat" id="value2" value="2" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 2) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_radio version_2">3
                                    <input type="radio" name="how_many_time_eat" id="value3" value="3" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 3) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">4
                                    <input type="radio" name="how_many_time_eat" id="value4" value="4" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 4) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">5
                                     <input type="radio" name="how_many_time_eat" id="value5" value="5" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 5) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">6
                                     <input type="radio" name="how_many_time_eat" id="value6" value="6" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 6) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">7+
                                      <input type="radio" name="how_many_time_eat" id="value7" value="7" @if(isset($nutritional_journal) && $nutritional_journal->how_many_time_eat == 7) checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                          <!-- /step-->
                             <div class="step">
                                <h2 class="section_title">Do you skip meals?</h2>
                               <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> No
                                       <input type="radio" name="skip_meals" @if(isset($nutritional_journal) && $nutritional_journal->skip_meals == "No") checked @endif id="valueNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">Occasionally
                                     <input type="radio" name="skip_meals" @if(isset($nutritional_journal) && $nutritional_journal->skip_meals == "Occasionally") checked @endif id="Occasionally" value="Occasionally">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_radio version_2">Often
                                    <input type="radio" name="skip_meals" @if(isset($nutritional_journal) && $nutritional_journal->skip_meals == "Often") checked @endif id="Often" value="Often">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                             <!-- /step-->
                             <div class="step">
                                <h2 class="section_title">What time do you eat your first meal & last meal?</h2>
                                <div class="form-group add_top_30">
                                   <label>First meal</label>
                                   <input type="text" name="eat_first_meal" id="eat_first_meal" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->eat_first_meal }}" @endif class="form-control">
                               </div>
                               <div class="form-group">
                                <label>Last meal</label>
                                <input type="text" name="eat_last_meal" id="eat_last_meal" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->eat_last_meal }}" @endif class="form-control">
                            </div>
                        </div>
                        <!-- /step-->
                             <div class="step">
                                <h2 class="section_title">How much water do you drink each day?</h2>
                               <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> 1L
                                        <input type="radio" name="water_drink" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == "1L") checked @endif id="value1L" value="1L">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">  1.5L
                                         <input type="radio" name="water_drink" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == "1.5L") checked @endif id="value15L" value="1.5L">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">  2L
                                          <input type="radio" name="water_drink" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == "2L") checked @endif id="value2L" value="2L">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                  <div class="form-group">
                                    <label class="container_radio version_2">  2.5L
                                       <input type="radio" name="water_drink" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == "2.5L") checked @endif id="value25L" value="2.5L">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_radio version_2">  3L
                                     <input type="radio" name="water_drink" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == "3L") checked @endif id="value3L" value="3L">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">  3.5L
                                     <input type="radio" name="water_drink" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == "3.5L") checked @endif id="value35L" value="3.5L">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                  <div class="form-group">
                                    <label class="container_radio version_2">  4L
                                    <input type="radio" name="water_drink" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == "4L") checked @endif id="value4L" value="4L">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                  <div class="form-group">
                                    <label class="container_radio version_2">  4L+
                                     <input type="radio" name="water_drink" @if(isset($nutritional_journal) && $nutritional_journal->water_drink == "4L+") checked @endif id="value4L1" value="4L+">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                        </div>
                        <!-- /step-->
                             <div class="step">
                                <h2 class="section_title">Do you drink alcohol?</h2>
                                 <div class="form-group add_top_30">
                                    <label class="container_radio version_2"> Yes
                                        <input type="radio" name="drink_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->drink_alcohol == "Yes") checked @endif id="drinkYes" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                     <label class="container_radio version_2">No
                                        <input type="radio" name="drink_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->drink_alcohol == "No") checked @endif id="drinkNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                            </div>
                            <!-- /step-->
                             <div class="alcohal hidden">
                                <h2 class="section_title">How many units of alcohal per week?</h2>
                                 <div class="form-group add_top_30">
                                    <label class="container_radio version_2">1
                                         <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "1") checked @endif id="drinkWeek1" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                     <label class="container_radio version_2">2
                                        <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "2") checked @endif id="drinkWeek2" value="2">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                                   <div class="form-group">
                                     <label class="container_radio version_2">3
                                         <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "3") checked @endif id="drinkWeek3" value="3">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                                  <div class="form-group">
                                     <label class="container_radio version_2">4
                                         <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "4") checked @endif id="drinkWeek4" value="4">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                                  <div class="form-group">
                                     <label class="container_radio version_2">5
                                         <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "5") checked @endif id="drinkWeek5" value="5">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                                  <div class="form-group">
                                     <label class="container_radio version_2">6
                                        <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "6") checked @endif id="drinkWeek6" value="6">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                                  <div class="form-group">
                                     <label class="container_radio version_2">7
                                         <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "7") checked @endif id="drinkWeek7" value="7">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-group">
                                     <label class="container_radio version_2">8
                                        <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "8") checked @endif id="drinkWeek8" value="8">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-group">
                                     <label class="container_radio version_2">9
                                         <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "9") checked @endif id="drinkWeek9" value="9">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                                 <div class="form-group">
                                     <label class="container_radio version_2">10
                                        <input type="radio" name="consume_alcohol" @if(isset($nutritional_journal) && $nutritional_journal->consume_alcohol == "10") checked @endif id="drinkWeek10" value="10">
                                        <span class="checkmark"></span>
                                    </label>
                                 </div>
                        </div>
                         <!-- /step-->
                            <div class="alcohal hidden">
                                <h2 class="section_title">What type of alcohol do you drink?</h2>
                               <div class="form-group add_top_30">
                                    <textarea rows="1" id="type_of_alcohol" name="type_of_alcohol" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->type_of_alcohol }} @else {{ old('type_of_alcohol') }} @endif</textarea>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Do you bing drink?</h2>
                              <div class="form-group add_top_30">
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">No
                                           <input type="radio" name="bing_drink" @if(isset($nutritional_journal) && $nutritional_journal->bing_drink == "No") checked @endif id="bingdrinkNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2"> Occasionally
                                         <input type="radio" name="bing_drink" @if(isset($nutritional_journal) && $nutritional_journal->bing_drink == "Occasionally") checked @endif id="bingOccasionally" value="Occasionally">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_radio version_2">Often
                                        <input type="radio" name="bing_drink" @if(isset($nutritional_journal) && $nutritional_journal->bing_drink == "Often") checked @endif id="bingdrinkOften" value="Often">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>

                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Do you drink tea or coffee?</h2>
                                <div class="form-group add_top_30">
                                    <label class="container_radio version_2">Yes
                                         <input type="radio" name="drink_tea_coffee" @if(isset($nutritional_journal) && $nutritional_journal->drink_tea_coffee == "Yes") checked @endif id="drinkteaYes" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">No
                                        <input type="radio" name="drink_tea_coffee" @if(isset($nutritional_journal) && $nutritional_journal->drink_tea_coffee == "No") checked @endif id="drinkteaNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>

                                <div class="form-group">
                                     <textarea rows="1" id="drink_tea_coffee_desc" name="drink_tea_coffee_desc" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->drink_tea_coffee_desc }} @else {{ old('drink_tea_coffee_desc') }} @endif</textarea>
                                    
                                </div>
                            </div>
                            
                            <!-- /step-->
                            <div class="tea-coffee hidden">
                                <h2 class="section_title">How many cups of tea/coffee per day?</h2>
                                 <div class="form-group add_top_30">
                                    <label class="container_radio version_2">1
                                        <input type="radio" name="tea_coffee_time" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == "1") checked @endif id="teaPerDay1" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">2
                                        <input type="radio" name="tea_coffee_time" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == "2") checked @endif id="teaPerDay2" value="2">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">3
                                        <input type="radio" name="tea_coffee_time" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == "3") checked @endif id="teaPerDay3" value="3">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">4
                                        <input type="radio" name="tea_coffee_time" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == "4") checked @endif id="teaPerDay4" value="4">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">5
                                       <input type="radio" name="tea_coffee_time" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == "5") checked @endif id="teaPerDay5" value="5">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">6
                                         <input type="radio" name="tea_coffee_time" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == "6") checked @endif id="teaPerDay6" value="6">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">7
                                         <input type="radio" name="tea_coffee_time" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == "7") checked @endif id="teaPerDay7" value="7">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">8+
                                         <input type="radio" name="tea_coffee_time" @if(isset($nutritional_journal) && $nutritional_journal->tea_coffee_time == "8+") checked @endif id="teaPerDay8" value="8+">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->
                            <div class="tea-coffee hidden">
                                <h2 class="section_title">What size cup?</h2>
                              
                                <div class="form-group add_top_30">
                                    <input type="text" name="cup_size" id="cup_size" @if(isset($nutritional_journal)) value="{{ $nutritional_journal->cup_size }}" @endif class="form-control">
                                </div>
                                
                            </div>
                            <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Rate your energy lebels during the day?</h2>
                              <div class="form-group add_top_30">
                                    <label>Morning</label>
                                    <br>
                                     <label class="container_radio version_2">Low
                                          <input type="radio" name="morning_energy_label" @if(isset($nutritional_journal) && $nutritional_journal->morning_energy_label == "Low") checked @endif id="MorningLow" value="Low">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>
                            <div class="form-group">
                                     <label class="container_radio version_2"> Medium
                                         <input type="radio" name="morning_energy_label" @if(isset($nutritional_journal) && $nutritional_journal->morning_energy_label == "Medium") checked @endif id="MorningMedium" value="Medium">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>

                            <div class="form-group">
                                     <label class="container_radio version_2"> High
                                         <input type="radio" name="morning_energy_label" @if(isset($nutritional_journal) && $nutritional_journal->morning_energy_label == "High") checked @endif id="MorningHigh" value="High">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>
                            <div class="form-group">
                                 <label>Afternoon</label>
                                    <br>
                                     <label class="container_radio version_2"> Low
                                       <input type="radio" name="afternoon_energy_label" @if(isset($nutritional_journal) && $nutritional_journal->afternoon_energy_label == "Low") checked @endif id="AfternoonLow" value="Low">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>
                            <div class="form-group">
                                     <label class="container_radio version_2"> Medium
                                       <input type="radio" name="afternoon_energy_label" @if(isset($nutritional_journal) && $nutritional_journal->afternoon_energy_label == "Medium") checked @endif id="AfternoonMedium" value="Medium">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>
                            <div class="form-group">
                                     <label class="container_radio version_2"> High
                                      <input type="radio" name="afternoon_energy_label" @if(isset($nutritional_journal) && $nutritional_journal->afternoon_energy_label == "High") checked @endif id="AfternoonHigh" value="High">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>
                              <div class="form-group add_top_30">
                                    <label>Evening</label>
                                    <br>
                                     <label class="container_radio version_2">Low
                                         <input type="radio" name="evening_energy_label" @if(isset($nutritional_journal) && $nutritional_journal->evening_energy_label == "Low") checked @endif id="EveningLow" value="Low">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>
                            <div class="form-group">
                                     <label class="container_radio version_2"> Medium
                                        <input type="radio" name="evening_energy_label" @if(isset($nutritional_journal) && $nutritional_journal->evening_energy_label == "Medium") checked @endif id="EveningMedium" value="Medium">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>
                            <div class="form-group">
                                     <label class="container_radio version_2"> High
                                      <input type="radio" name="evening_energy_label" @if(isset($nutritional_journal) && $nutritional_journal->evening_energy_label == "High") checked @endif id="EveningHigh" value="High">
                                        <span class="checkmark"></span>
                                    </label>
                            </div>
                        </div>
                         <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Do you know how many calories you eat an average each day?</h2>
                                 <div class="form-group add_top_30">
                                    <label class="container_radio version_2">Yes
                                       <input type="radio" name="eat_calories" @if(isset($nutritional_journal) && $nutritional_journal->eat_calories == "Yes") checked @endif id="caloriesYes" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">No
                                        <input type="radio" name="eat_calories" @if(isset($nutritional_journal) && $nutritional_journal->eat_calories == "No") checked @endif id="caloriesNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                              <!-- /step-->
                            <div class="calories hidden" id="calories">
                                <h2 class="section_title">If yes how many?</h2>
                                 <div class="form-group add_top_30">
                                      <textarea rows="1" id="how_many_calories" name="how_many_calories" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->how_many_calories }} @else {{ old('how_many_calories') }} @endif</textarea>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Are you an special diet?</h2>
                                 <div class="form-group add_top_30">
                                    <label class="container_radio version_2">Yes
                                        <input type="radio" name="special_diet" @if(isset($nutritional_journal) && $nutritional_journal->special_diet == "Yes") checked @endif id="specialYes" value="Yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">No
                                        <input type="radio" name="special_diet" @if(isset($nutritional_journal) && $nutritional_journal->special_diet == "No") checked @endif id="specialNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_radio version_2">Occasionally
                                         <input type="radio" name="special_diet" @if(isset($nutritional_journal) && $nutritional_journal->special_diet == "Occasionally") checked @endif id="specialOccasionally" value="Occasionally">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">If "Yes" which diet?</h2>
                                 <div class="form-group add_top_30">
                                      <textarea rows="1" id="which_diet" name="which_diet" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->which_diet }} @else {{ old('which_diet') }} @endif</textarea>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">List all medications, supplements or vitamins you are currently taking. (Include sport drinks and supplements)</h2>
                                 <div class="form-group add_top_30">
                                        <textarea rows="1" id="all_vitamins" name="all_vitamins" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->all_vitamins }} @else {{ old('all_vitamins') }} @endif</textarea>
                                </div>
                            </div>
                            <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Do you usually</h2>
                                 <div class="form-group add_top_30">
                                    <label class="container_radio version_2">Binge
                                      <input type="radio" name="use_it" @if(isset($nutritional_journal) && $nutritional_journal->use_it == "Binge") checked @endif id="Binge" value="Binge">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">Crave sugar
                                        <input type="radio" name="use_it" @if(isset($nutritional_journal) && $nutritional_journal->use_it == "Crave sugar") checked @endif id="Cravesugar" value="Crave sugar">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">Eat fast food
                                        <input type="radio" name="use_it" @if(isset($nutritional_journal) && $nutritional_journal->use_it == "Eat fast food") checked @endif id="Eatfastfood" value="Eat fast food">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2"> Make and bring own food
                                        <input type="radio" name="use_it" @if(isset($nutritional_journal) && $nutritional_journal->use_it == "Make and bring own food") checked @endif id="bringownfood" value="Make and bring own food">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2"> Eat at restaurents
                                          <input type="radio" name="use_it" @if(isset($nutritional_journal) && $nutritional_journal->use_it == "Eat at restaurents") checked @endif id="Eatrestaurents" value="Eat at restaurents">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                     <textarea rows="1" id="uses_desc" name="uses_desc" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->uses_desc }} @else {{ old('uses_desc') }} @endif</textarea>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Do you prepare your own food often or by prepared food?</h2>
                                  <div class="form-group add_top_30">
                                     <textarea rows="1" id="prepare_own_food" name="prepare_own_food" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->prepare_own_food }} @else {{ old('prepare_own_food') }} @endif</textarea>
                                </div>
                            </div>
                            <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">How do you prepare most of your meals?</h2>
                               <div class="form-group add_top_30">
                                    <label class="container_check version_2">Grill
                                      <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Grill",$prepare_own_meals)) checked @endif id="Grill" value="Grill">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_check version_2">Bake
                                      <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Bake",$prepare_own_meals)) checked @endif id="Bake" value="Bake">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2">Steam
                                      <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Steam",$prepare_own_meals)) checked @endif id="Steam" value="Steam">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_check version_2"> Fry Pan
                                     <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Fry Pan",$prepare_own_meals)) checked @endif id="FryPan" value="Fry Pan">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_check version_2"> Deep Fry
                                    <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Deep Fry",$prepare_own_meals)) checked @endif id="DeepFry" value="Deep Fry">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2"> Raw
                                   <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Raw",$prepare_own_meals)) checked @endif id="Raw" value="Raw">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2"> Stir Fry
                                   <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Stir Fry",$prepare_own_meals)) checked @endif id="StirFry" value="Stir Fry">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_check version_2"> Smoked
                                     <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Smoked",$prepare_own_meals)) checked @endif id="Smoked" value="Smoked">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_check version_2">Curried
                                      <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Curried",$prepare_own_meals)) checked @endif id="Curried" value="Curried">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2">Boiled
                                      <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Boiled",$prepare_own_meals)) checked @endif id="Curried" value="Boiled">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_check version_2">Poaching
                                      <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Poaching",$prepare_own_meals)) checked @endif id="Curried" value="Poaching">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_check version_2">Barbeque
                                      <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Barbeque",$prepare_own_meals)) checked @endif id="Curried" value="Barbeque">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_check version_2">Microwave
                                       <input type="checkbox" name="prepare_own_meals[]" @if(isset($nutritional_journal) && in_array("Microwave",$prepare_own_meals)) checked @endif id="Microwave" value="Microwave">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                        <textarea rows="1" id="prepare_own_meals_desc" name="prepare_own_meals_desc" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->prepare_own_meals_desc }} @else {{ old('prepare_own_meals_desc') }} @endif</textarea>
                                </div>
                            </div>
                           <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">How many times a week do you eat out?</h2>
                               <div class="form-group add_top_30">
                                    <label class="container_radio version_2">1
                                     <input type="radio" name="eat_outside" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == "1") checked @endif id="eatWeek1" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_radio version_2">2
                                      <input type="radio" name="eat_outside" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == "2") checked @endif id="eatWeek2" value="2">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">3
                                    <input type="radio" name="eat_outside" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == "3") checked @endif id="eatWeek3" value="3">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_radio version_2">4
                                      <input type="radio" name="eat_outside" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == "4") checked @endif id="eatWeek4" value="4">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_radio version_2">5
                                    <input type="radio" name="eat_outside" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == "5") checked @endif id="eatWeek5" value="5">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2"> 6
                                  <input type="radio" name="eat_outside" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == "6") checked @endif id="eatWeek6" value="6">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="container_radio version_2">7
                                   <input type="radio" name="eat_outside" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == "7") checked @endif id="eatWeek7" value="7">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                    <label class="container_radio version_2"> 8
                                 <input type="radio" name="eat_outside" @if(isset($nutritional_journal) && $nutritional_journal->eat_outside == "8+") checked @endif id="eatWeek8" value="8+">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                              <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">List 3 areas of your nutrition you would like to improve?</h2>
                               <div class="form-group add_top_30">
                                     <textarea rows="1" id="improving_area" name="improving_area" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->improving_area }} @else {{ old('improving_area') }} @endif</textarea>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Out of three listed above which one are most likely to adhere to</h2>
                               <div class="form-group add_top_30">
                                     <textarea rows="1" id="must_improving_area" name="must_improving_area" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->must_improving_area }} @else {{ old('must_improving_area') }} @endif</textarea>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2>Eating Habits</h2>
                                <h2 class="section_title">How would you describe the pace at which you eat?</h2>
                               <div class="form-group add_top_30">
                                      <label class="container_radio version_2"> Slow
                                 <input type="radio" name="eating_speed" @if(isset($nutritional_journal) && $nutritional_journal->eating_speed == "Slow") checked @endif id="eatSlow" value="Slow">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                      <label class="container_radio version_2">Average
                                <input type="radio" name="eating_speed" @if(isset($nutritional_journal) && $nutritional_journal->eating_speed == "Average") checked @endif id="eatAverage" value="Average">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                      <label class="container_radio version_2">Fast
                                   <input type="radio" name="eating_speed" @if(isset($nutritional_journal) && $nutritional_journal->eating_speed == "Fast") checked @endif id="eatFast" value="Fast">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">How full do you like your plate to be?</h2>
                               <div class="form-group add_top_30">
                                      <label class="container_radio version_2"> Empty
                                 <input type="radio" name="full_plate" @if(isset($nutritional_journal) && $nutritional_journal->full_plate == "Empty") checked @endif id="plateEmpty" value="Empty">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                      <label class="container_radio version_2">Medium
                                 <input type="radio" name="full_plate" @if(isset($nutritional_journal) && $nutritional_journal->full_plate == "Medium") checked @endif id="plateMedium" value="Medium">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                      <label class="container_radio version_2">Full
                                    <input type="radio" name="full_plate" @if(isset($nutritional_journal) && $nutritional_journal->full_plate == "Full") checked @endif id="plateFull" value="Full">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">If there are 'left overs' after a meal do you try to finish to them?</h2>
                               <div class="form-group add_top_30">
                                      <label class="container_radio version_2"> No
                                 <input type="radio" name="finish_plate" @if(isset($nutritional_journal) && $nutritional_journal->finish_plate == "No") checked @endif id="finishNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                      <label class="container_radio version_2">Occasionally
                                  <input type="radio" name="finish_plate" @if(isset($nutritional_journal) && $nutritional_journal->finish_plate == "Occasionally") checked @endif id="finishOccasionally" value="Occasionally">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                      <label class="container_radio version_2"> Often
                                    <input type="radio" name="finish_plate" @if(isset($nutritional_journal) && $nutritional_journal->finish_plate == "Often") checked @endif id="finishOften" value="Often">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Are you always hungry when you eat?</h2>
                               <div class="form-group add_top_30">
                                      <label class="container_radio version_2"> No
                                 <input type="radio" name="always_hungry" @if(isset($nutritional_journal) && $nutritional_journal->always_hungry == "No") checked @endif id="hungryNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                      <label class="container_radio version_2">Occasionally
                                  <input type="radio" name="always_hungry" @if(isset($nutritional_journal) && $nutritional_journal->always_hungry == "Occasionally") checked @endif id="hungryOccasionally" value="Occasionally">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                      <label class="container_radio version_2"> Often
                                   <input type="radio" name="always_hungry" @if(isset($nutritional_journal) && $nutritional_journal->always_hungry == "Often") checked @endif id="hungryOften" value="Often">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                             <!-- /step-->
                             <div class="step">
                                <h2 class="section_title">Do you leave your plate empty at every meal?</h2>
                               <div class="form-group add_top_30">
                                      <label class="container_radio version_2"> No
                                 <input type="radio" name="plate_empty" @if(isset($nutritional_journal) && $nutritional_journal->plate_empty == "No") checked @endif id="plateNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                      <label class="container_radio version_2">Occasionally
                                  <input type="radio" name="plate_empty" @if(isset($nutritional_journal) && $nutritional_journal->plate_empty == "Occasionally") checked @endif id="plateOccasionally" value="Occasionally">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                      <label class="container_radio version_2"> Often
                                   <input type="radio" name="plate_empty" @if(isset($nutritional_journal) && $nutritional_journal->plate_empty == "Often") checked @endif id="plateOften" value="Often">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Are you likely to eat if bored, vervous, stressed or upset?</h2>
                               <div class="form-group add_top_30">
                                      <label class="container_radio version_2"> No
                                 <input type="radio" name="eat_upset" @if(isset($nutritional_journal) && $nutritional_journal->eat_upset == "No") checked @endif id="boredNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                      <label class="container_radio version_2">Occasionally
                                  <input type="radio" name="eat_upset" @if(isset($nutritional_journal) && $nutritional_journal->eat_upset == "Occasionally") checked @endif id="boredOccasionally" value="Occasionally">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                      <label class="container_radio version_2"> Often
                                    <input type="radio" name="eat_upset" @if(isset($nutritional_journal) && $nutritional_journal->eat_upset == "Often") checked @endif id="boredOften" value="Often">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                
                            </div>
                             <!-- /step-->
                             <div class="step">
                                <h2 class="section_title">Do you like to eat from fast food chains?</h2>
                               <div class="form-group add_top_30">
                                      <label class="container_radio version_2"> No
                                 <input type="radio" name="eat_fast_food" @if(isset($nutritional_journal) && $nutritional_journal->eat_fast_food == "No") checked @endif id="foodNo" value="No">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                 <div class="form-group">
                                      <label class="container_radio version_2">Occasionally
                                  <input type="radio" name="eat_fast_food" @if(isset($nutritional_journal) && $nutritional_journal->eat_fast_food == "Occasionally") checked @endif id="foodOccasionally" value="Occasionally">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                      <label class="container_radio version_2"> Often
                                   <input type="radio" name="eat_fast_food" @if(isset($nutritional_journal) && $nutritional_journal->eat_fast_food == "Often") checked @endif id="foodOften" value="Often">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label> Why / Why not?
                                  </label>
                                   <textarea rows="1" id="why_not_eat" name="why_not_eat" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->why_not_eat }} @else {{ old('why_not_eat') }} @endif</textarea>
                              </div>
                               <div class="form-group">
                                    <label>Preferred fast food?
                                  </label>
                                  <textarea rows="1" id="why_eat" name="why_eat" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->why_eat }} @else {{ old('why_eat') }} @endif</textarea>
                              </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">What are some of your favourite perferred foods? (Describe a healthy breakfast, lunch, dinner and snack)?</h2>
                               <div class="form-group add_top_30">
                                       <textarea rows="1" id="favourite_food" name="favourite_food" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->favourite_food }} @else {{ old('favourite_food') }} @endif</textarea>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">What foods do you often crave, even if you feel full</h2>
                               <div class="form-group add_top_30">
                                      <textarea rows="1" id="after_full" name="after_full" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->after_full }} @else {{ old('after_full') }} @endif</textarea>
                                </div>
                            </div>
                            <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">What do you like to do after dinner, if anything?</h2>
                               <div class="form-group add_top_30">
                                     <textarea rows="1" id="after_dinner" name="after_dinner" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->after_dinner }} @else {{ old('after_dinner') }} @endif</textarea>
                                </div>
                            </div>
                            <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">Describe what a 'good' meal means to you? (Portion size, food type, tasty, sweet, gourment, different)</h2>
                               <div class="form-group add_top_30">
                                    <textarea rows="1" id="good_meal" name="good_meal" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->good_meal }} @else {{ old('good_meal') }} @endif</textarea>
                                </div>
                            </div>
                              <!-- /step-->
                            <div class="step">
                                <h2 class="section_title">What are your favourite drinks?</h2>
                               <div class="form-group add_top_30">
                                   <textarea rows="1" id="favourite_drinks" name="favourite_drinks" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->favourite_drinks }} @else {{ old('favourite_drinks') }} @endif</textarea>
                                </div>
                            </div>
                             <!-- /step-->
                            <div class="submit step" id="end">
                                <h2 class="section_title">If you cook, do you cookfor others or just for yourself?</h2>
                               <div class="form-group add_top_30">
                                      <textarea rows="1" id="cook_for" name="cook_for" class="form-control">@if(isset($nutritional_journal)) {{ $nutritional_journal->cook_for }} @else {{ old('cook_for') }} @endif</textarea>
                                </div>
                            </div>
                          <!-- /middle-wizard -->
                        <div id="bottom-wizard">
                            <button type="button" name="backward" class="backward">Prev</button>
                            <button type="button" name="forward" class="forward">Next</button>
                            <button type="submit" class="submit submit-step" data-step-url="" data-step="4">Submit</button>
                        </div>
                        <!-- /bottom-wizard -->
                        <input type="hidden" name="" id="output" value="1">
                         </form>
                </div>
            </div>
        </div>
    </div>

<!-- start: Pic crop Model -->
    @include('includes.partials.pic_crop_model')
<!-- end: Pic crop Model -->
<script>
    $("input[name='drink_alcohol']").click(function(){
        var status = $(this).val();
        if(status == 'Yes'){
            $(".alcohal").addClass('step');
            // $(".alcohal").addClass('wizard-step');
            $(".alcohal").removeClass('hidden');
        }else{
            $(".alcohal").removeClass('step');
            // $(".alcohal").removeClass('wizard-step');
            $(".alcohal").addClass('hidden');
        }
    })
    
    $("input[name='drink_tea_coffee']").click(function(){
        var status = $(this).val();
        if(status == 'Yes'){
            $(".tea-coffee").addClass('step');
            // $(".tea-coffee").addClass('wizard-step');
            $(".tea-coffee").removeClass('hidden');
        }else{
            $(".tea-coffee").removeClass('step');
            // $(".tea-coffee").removeClass('wizard-step');
            $(".tea-coffee").addClass('hidden');
        }
    })

    $("input[name='eat_calories']").click(function(){
        var status = $(this).val();
        if(status == 'Yes'){
            $(".calories").addClass('step');
            // $(".calories").addClass('wizard-step');
            $(".calories").removeClass('hidden');
        }else{
            $(".calories").removeClass('step');
            // $(".calories").removeClass('wizard-step');
            $(".calories").addClass('hidden');
        }
    })

    $('.forward').click(function() {
        $(".alcohal").addClass('hidden');
        $(".tea-coffee").addClass('hidden');
        $(".calories").addClass('hidden');
        // $('#output').val(function(i, val) { return val*1+1 });
    });
    // $('.backward').click(function() {
    //     $('#output').val(function(i, val) { return val*1-1 });
    // });
</script>

@endsection
@section('required-script')

<script type="text/javascript">
    $('body').on('click', '#submitNutritionalForm', function(){
        alert();
        $('#hideSelectOption').hide();
        $('#showNutritionalForm').show();
    });


    $('.okbtn').click(function () {
        var fuller = $(this).closest('.step_item').next(),
            section = $(this).closest('.stepform_section');

        $('html, body').animate({
            scrollTop: section.scrollTop() + fuller.offset().top
        }, 200);
    });

    

</script>
@stop


